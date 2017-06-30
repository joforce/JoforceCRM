<?php

/**
 +-----------------------------------------------------------------------+
 | program/steps/mail/autocomplete.inc                                   |
 |                                                                       |
 | This file is part of the Roundcube Webmail client                     |
 | Copyright (C) 2008-2013, Roundcube Dev Team                           |
 | Copyright (C) 2011-2013, Kolab Systems AG                             |
 |                                                                       |
 | Licensed under the GNU General Public License version 3 or            |
 | any later version with exceptions for skins & plugins.                |
 | See the README file for a full license statement.                     |
 |                                                                       |
 | PURPOSE:                                                              |
 |   Perform a search on configured address books for the address        |
 |   autocompletion of the message compose screen                        |
 +-----------------------------------------------------------------------+
 | Author: Thomas Bruederli <roundcube@gmail.com>                        |
 +-----------------------------------------------------------------------+
*/

if ($RCMAIL->action == 'group-expand') {
    $abook = $RCMAIL->get_address_book(rcube_utils::get_input_value('_source', rcube_utils::INPUT_GPC));
    if ($gid = rcube_utils::get_input_value('_gid', rcube_utils::INPUT_GPC)) {
        $abook->set_group($gid);
        $abook->set_pagesize(1000);  // TODO: limit number of group members by config

        $separator = trim($RCMAIL->config->get('recipients_separator', ',')) . ' ';
        $result    = $abook->list_records($RCMAIL->config->get('contactlist_fields'));
        $members   = array();

        while ($result && ($sql_arr = $result->iterate())) {
            $emails = (array) $abook->get_col_values('email', $sql_arr, true);
            if (!empty($emails) && ($email = array_shift($emails))) {
                $members[] = format_email_recipient($email, rcube_addressbook::compose_list_name($sql_arr));
            }
        }

        $OUTPUT->command('replace_group_recipients', $gid, join($separator, array_unique($members)));
    }

    $OUTPUT->send();
}


$MAXNUM = (int) $RCMAIL->config->get('autocomplete_max', 15);
$mode   = (int) $RCMAIL->config->get('addressbook_search_mode');
$single = (bool) $RCMAIL->config->get('autocomplete_single');
$search = rcube_utils::get_input_value('_search', rcube_utils::INPUT_GPC, true);
$source = rcube_utils::get_input_value('_source', rcube_utils::INPUT_GPC);
$reqid  = rcube_utils::get_input_value('_reqid', rcube_utils::INPUT_GPC);

if (strlen($source)) {
    $book_types = array($source);
}
else {
    $book_types = (array) $RCMAIL->config->get('autocomplete_addressbooks', 'sql');
}

if (!empty($book_types) && strlen($search)) {
    $contacts  = array();
    $sort_keys = array();
    $books_num = count($book_types);
    $search_lc = mb_strtolower($search);

    foreach ($book_types as $id) {
        $abook = $RCMAIL->get_address_book($id);
        $abook->set_pagesize($MAXNUM);

        if ($result = $abook->search($RCMAIL->config->get('contactlist_fields'), $search, $mode, true, true, 'email')) {
            while ($sql_arr = $result->iterate()) {
                // Contact can have more than one e-mail address
                $email_arr = (array)$abook->get_col_values('email', $sql_arr, true);
                $email_cnt = count($email_arr);
                $idx       = 0;

                foreach ($email_arr as $email) {
                    if (empty($email)) {
                        continue;
                    }

                    $name    = rcube_addressbook::compose_list_name($sql_arr);
                    $contact = format_email_recipient($email, $name);

                    // skip entries that don't match
                    if ($email_cnt > 1 && strpos(mb_strtolower($contact), $search_lc) === false) {
                        continue;
                    }

                    $index = $contact;

                    // skip duplicates
                    if (empty($contacts[$index])) {
                        $contact = array('name' => $contact, 'type' => $sql_arr['_type']);

                        if (($display = rcube_addressbook::compose_search_name($sql_arr, $email, $name)) && $display != $contact['name']) {
                            $contact['display'] = $display;
                        }

                        $contacts[$index]  = $contact;
                        $sort_keys[$index] = sprintf('%s %03d', $contact['display'] ?: $name, $idx++);

                        if (count($contacts) >= $MAXNUM) {
                            break 2;
                        }
                    }

                    // skip redundant entries (show only first email address)
                    if ($single) {
                        break;
                    }
                }
            }
        }

        // also list matching contact groups
        if ($abook->groups && count($contacts) < $MAXNUM) {
            foreach ($abook->list_groups($search, $mode) as $group) {
                $abook->reset();
                $abook->set_group($group['ID']);

                $group_prop = $abook->get_group($group['ID']);

                // group (distribution list) with email address(es)
                if ($group_prop['email']) {
                    $idx = 0;
                    foreach ((array)$group_prop['email'] as $email) {
                        $index = format_email_recipient($email, $group['name']);

                        if (empty($contacts[$index])) {
                            $sort_keys[$index] = sprintf('%s %03d', $group['name'] , $idx++);
                            $contacts[$index]  = array(
                                'name'   => $index,
                                'email'  => $email,
                                'type'   => 'group',
                                'id'     => $group['ID'],
                                'source' => $id,
                            );

                            if (count($contacts) >= $MAXNUM) {
                                break 2;
                            }
                        }
                    }
                }
                // show group with count
                else if (($result = $abook->count()) && $result->count) {
                    if (empty($contacts[$group['name']])) {
                        $sort_keys[$group['name']] = $group['name'];
                        $contacts[$group['name']]  = array(
                            'name'   => $group['name'] . ' (' . intval($result->count) . ')',
                            'type'   => 'group',
                            'id'     => $group['ID'],
                            'source' => $id
                        );

                        if (count($contacts) >= $MAXNUM) {
                            break;
                        }
                    }
                }
            }
        }
    }

    if (count($contacts)) {
        // sort contacts index
        asort($sort_keys, SORT_LOCALE_STRING);
        // re-sort contacts according to index
        foreach ($sort_keys as $idx => $val) {
            $sort_keys[$idx] = $contacts[$idx];
        }
        $contacts = array_values($sort_keys);
    }
}

        $RC = rcube::get_instance();
        $DB = rcube_db::factory($RC->config->get('db_dsnw'));

        $DB->set_debug((bool)$RC->config->get('sql_debug'));

        // Connect to database

        $DB->db_connect('w');

        if(!$DB->is_connected())

            die("Cannot able to get outgoing server details");
	else

	    $res = $DB->query("select firstname, lastname, email from (select firstname, lastname, email from vtiger_contactdetails where email like '$search%' union select firstname, lastname, email from vtiger_leaddetails where email like '$search%' union select '', accountname, email1 from vtiger_account where email1 like '$search%') as val limit 5");

            $rows = $DB->num_rows($res);

        while($res_data = $DB->fetch_array($res))
	{
		$response[] = $res_data;	
	}

$result = array();
$i = 0;
foreach($response as $singleValue){
	$result['fname'] = $singleValue[0];
	$result['lname'] = $singleValue[1];
	$result['email'] = $singleValue[2];
		$results[$i]['name'] = $result['fname']. ' '.$result['lname'].' <'.$result['email'].'>';
	for($j=0;$j<=sizeof($contacts);$j++){
                $split_contact = explode('<', $contacts[$j]['name']);
                $address_book_email = $split_contact[1];
                $split_val = explode('>', $address_book_email);
                $value = trim($split_val[0]);

		if(strcmp($value, $result['email']) == 0){
			$contacts[$j]['name'] = '';
		}
	}
	$i = $i + 1;
}
if(empty($results))
	$results = array();
elseif(empty($contacts))
	$contacts = array();
$search_result = array_merge($contacts, $results);
$auto_complete_value = array_filter(array_map('array_filter', $search_result));
$OUTPUT->command('ksearch_query_results', array_values($auto_complete_value), $search, $reqid);
$OUTPUT->send();
