<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/jo_crm/sugarcrm/modules/Emails/Emails.php,v 1.41 2005/04/28 08:11:21 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): ______________________________________..
 ********************************************************************************/

// Email is used to store customer information.
class Emails extends CRMEntity {

	var $log;
	var $db;
	var $table_name = "jo_activity";
	var $table_index = 'activityid';
	// Stored jo_fields
	// added to check email save from plugin or not
	var $plugin_save = false;
	var $rel_users_table = "jo_salesmanactivityrel";
	var $rel_contacts_table = "jo_cntactivityrel";
	var $rel_serel_table = "jo_seactivityrel";
	var $tab_name = Array('jo_crmentity', 'jo_activity', 'jo_emaildetails');
	var $tab_name_index = Array('jo_crmentity' => 'crmid', 'jo_activity' => 'activityid',
		'jo_seactivityrel' => 'activityid', 'jo_cntactivityrel' => 'activityid', 'jo_email_track' => 'mailid', 'jo_emaildetails' => 'emailid');
	// This is the list of jo_fields that are in the lists.
	var $list_fields = Array(
		'Subject' => Array('activity' => 'subject'),
		'Related to' => Array('seactivityrel' => 'parent_id'),
		'Date Sent' => Array('activity' => 'date_start'),
		'Time Sent' => Array('activity' => 'time_start'),
		'Assigned To' => Array('crmentity', 'smownerid'),
		'Access Count' => Array('email_track', 'access_count'),
		'Click Count' => Array('email_track','click_count'),
	);
	var $list_fields_name = Array(
		'Subject' => 'subject',
		'Related to' => 'parent_id',
		'Date Sent' => 'date_start',
		'Time Sent' => 'time_start',
		'Assigned To' => 'assigned_user_id',
		'Access Count' => 'access_count',
		'Click Count' => 'click_count'
	);
	var $list_link_field = 'subject';
	var $column_fields = Array();
	var $sortby_fields = Array('subject', 'date_start', 'saved_toid');
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'date_start';
	var $default_sort_order = 'DESC';
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to jo_field.fieldname values.
	var $mandatory_fields = Array('subject', 'assigned_user_id');

	/** This function will set the columnfields for Email module
	 */
	function Emails() {
		$this->log = LoggerManager::getLogger('email');
		$this->log->debug("Entering Emails() method ...");
		$this->log = LoggerManager::getLogger('email');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Emails');
		$this->log->debug("Exiting Email method ...");
	}

	function save_module($module) {
		global $adb;
		//Inserting into seactivityrel
		//modified by Richie as raju's implementation broke the feature for addition of webmail to jo_crmentity.need to be more careful in future while integrating code
		if ($_REQUEST['module'] == "Emails" && $_REQUEST['smodule'] != 'webmails' && (!$this->plugin_save)) {
			if ($_REQUEST['currentid'] != '') {
				$actid = $_REQUEST['currentid'];
			} else {
				$actid = $_REQUEST['record'];
			}
			$parentid = $_REQUEST['parent_id'];
			if ($_REQUEST['module'] != 'Emails' && $_REQUEST['module'] != 'Webmails') {
				if (!$parentid) {
					$parentid = $adb->getUniqueID('jo_seactivityrel');
				}
				$mysql = 'insert into jo_seactivityrel values(?,?)';
				$adb->pquery($mysql, array($parentid, $actid));
			} else {
				$myids = explode("|", $parentid);  //2@71|
				for ($i = 0; $i < (count($myids) - 1); $i++) {
					$realid = explode("@", $myids[$i]);
					$mycrmid = $realid[0];
					//added to handle the relationship of emails with jo_users
					if ($realid[1] == -1) {
						$del_q = 'delete from jo_salesmanactivityrel where smid=? and activityid=?';
						$adb->pquery($del_q, array($mycrmid, $actid));
						$mysql = 'insert into jo_salesmanactivityrel values(?,?)';
					} else {
						$del_q = 'delete from jo_seactivityrel where crmid=? and activityid=?';
						$adb->pquery($del_q, array($mycrmid, $actid));
						$mysql = 'insert into jo_seactivityrel values(?,?)';
					}
					$params = array($mycrmid, $actid);
					$adb->pquery($mysql, $params);
				}
			}
		} else {
			if (isset($this->column_fields['parent_id']) && $this->column_fields['parent_id'] != '') {
				$adb->pquery("DELETE FROM jo_seactivityrel WHERE crmid = ? AND activityid = ? ",
						array($this->column_fields['parent_id'], $this->id));
				//$this->insertIntoEntityTable('jo_seactivityrel', $module);
				$sql = 'insert into jo_seactivityrel values(?,?)';
				$params = array($this->column_fields['parent_id'], $this->id);
				$adb->pquery($sql, $params);
			} elseif ($this->column_fields['parent_id'] == '' && $insertion_mode == "edit") {
				$this->deleteRelation('jo_seactivityrel');
			}
		}


		//Insert into cntactivity rel

		if (isset($this->column_fields['contact_id']) && $this->column_fields['contact_id'] != '') {
			$this->insertIntoEntityTable('jo_cntactivityrel', $module);
		} elseif ($this->column_fields['contact_id'] == '' && $insertion_mode == "edit") {
			$this->deleteRelation('jo_cntactivityrel');
		}

		//Inserting into attachment

		$this->insertIntoAttachment($this->id, $module);
	}

	/**
	 * Function to get the array of record ids from a string pattern like "2@71|17@-1|120@15"
	 * This will filter user record ids
	 * @param type $recordIdsStr
	 * @return type
	 */
	function getCRMIdsFromStringPattern($recordIdsStr) {
		$recordIds = array();
		if(strpos($recordIdsStr, '@') !== false && strpos($recordIdsStr, '|') !== false) {
			$recordIdsParts = explode('|', $recordIdsStr);
			for ($i = 0; $i < (count($recordIdsParts) - 1); $i++) {
				$recordIdParts = explode('@', $recordIdsParts[$i]);
				//filter user records 
				if($recordIdParts[1] !== -1) {
					$recordIds[] = $recordIdParts[0];
				}
			}
		}
		return $recordIds;
	}

	function insertIntoAttachment($id, $module) {
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;

		//Added to send generated Invoice PDF with mail
		$pdfAttached = $_REQUEST['pdf_attachment'];
		//created Invoice pdf is attached with the mail
		if (isset($_REQUEST['pdf_attachment']) && $_REQUEST['pdf_attachment'] != '') {
			$file_saved = pdfAttach($this, $module, $pdfAttached, $id);
		}

		if ($_FILES) {
			//This is to added to store the existing attachment id of the contact where we should delete this when we give new image
			foreach ($_FILES as $fileindex => $files) {
				if ($files['name'] != '' && $files['size'] > 0) {
					$files['original_name'] = modlib_purify($_REQUEST[$fileindex . '_hidden']);
					$file_saved = $this->uploadAndSaveFile($id, $module, $files);
				}
			}
		}

		if ($module == 'Emails' && isset($_REQUEST['att_id_list']) && $_REQUEST['att_id_list'] != '') {
			$att_lists = explode(";", $_REQUEST['att_id_list'], -1);
			$id_cnt = count($att_lists);
			if ($id_cnt != 0) {
				for ($i = 0; $i < $id_cnt; $i++) {
					$sql_rel = 'insert into jo_seattachmentsrel values(?,?)';
					$adb->pquery($sql_rel, array($id, $att_lists[$i]));
				}
			}
		}
		if ($_REQUEST['att_module'] == 'Webmails') {
			require_once("modules/Webmails/Webmails.php");
			require_once("modules/Webmails/MailParse.php");
			require_once('modules/Webmails/MailBox.php');
			//$mailInfo = getMailServerInfo($current_user);
			//$temprow = $adb->fetch_array($mailInfo);

			$MailBox = new MailBox($_REQUEST["mailbox"]);
			$mbox = $MailBox->mbox;
			$webmail = new Webmails($mbox, $_REQUEST['mailid']);
			$array_tab = Array();
			$webmail->loadMail($array_tab);
			if (isset($webmail->att_details)) {
				foreach ($webmail->att_details as $fileindex => $files) {
					if ($files['name'] != '' && $files['size'] > 0) {
						//print_r($files);
						$file_saved = $this->saveForwardAttachments($id, $module, $files);
					}
				}
			}
		}
		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}

	function saveForwardAttachments($id, $module, $file_details) {
		global $log;
		$log->debug("Entering into saveForwardAttachments($id,$module,$file_details) method.");
		global $adb, $current_user;
		global $upload_badext;
		require_once('modules/Webmails/MailBox.php');
		$mailbox = $_REQUEST["mailbox"];
		$MailBox = new MailBox($mailbox);
		$mail = $MailBox->mbox;
		$binFile = sanitizeUploadFileName($file_details['name'], $upload_badext);
		$filename = ltrim(basename(" " . $binFile)); //allowed filename like UTF-8 characters
		$filetype = $file_details['type'];
		$filesize = $file_details['size'];
		$filepart = $file_details['part'];
		$transfer = $file_details['transfer'];
		$file = imap_fetchbody($mail, $_REQUEST['mailid'], $filepart);
		if ($transfer == 'BASE64')
			$file = imap_base64($file);
		elseif ($transfer == 'QUOTED-PRINTABLE')
			$file = imap_qprint($file);
		$current_id = $adb->getUniqueID("jo_crmentity");
		$date_var = date('Y-m-d H:i:s');
		//to get the owner id
		$ownerid = $this->column_fields['assigned_user_id'];
		if (!isset($ownerid) || $ownerid == '')
			$ownerid = $current_user->id;
		$upload_file_path = decideFilePath();
		file_put_contents($upload_file_path . $current_id . "_" . $filename, $file);

		$sql1 = "insert into jo_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(?,?,?,?,?,?,?)";
		$params1 = array($current_id, $current_user->id, $ownerid, $module . " Attachment", $this->column_fields['description'], $adb->formatDate($date_var, true), $adb->formatDate($date_var, true));
		$adb->pquery($sql1, $params1);

		$sql2 = "insert into jo_attachments(attachmentsid, name, description, type, path) values(?,?,?,?,?)";
		$params2 = array($current_id, $filename, $this->column_fields['description'], $filetype, $upload_file_path);
		$result = $adb->pquery($sql2, $params2);

		if ($_REQUEST['mode'] == 'edit') {
			if ($id != '' && $_REQUEST['fileid'] != '') {
				$delquery = 'delete from jo_seattachmentsrel where crmid = ? and attachmentsid = ?';
				$adb->pquery($delquery, array($id, $_REQUEST['fileid']));
			}
		}
		$sql3 = 'insert into jo_seattachmentsrel values(?,?)';
		$adb->pquery($sql3, array($id, $current_id));
		return true;
		$log->debug("exiting from  saveforwardattachment function.");
	}

	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved.
 * Contributor(s): JoForce.com.
	 * Contributor(s): ______________________________________..
	 */
	function get_contacts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view, $currentModule, $current_user;
		$log->debug("Entering get_contacts(" . $id . ") method ...");
		$this_module = $currentModule;

		$related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		$returnset = '&return_module=' . $this_module . '&return_action=DetailView&return_id=' . $id;

		$button = '';

		if ($actions) {
			if (is_string($actions))
				$actions = explode(',', strtoupper($actions));
			if (in_array('SELECT', $actions) && isPermitted($related_module, 4, '') == 'yes') {
				$button .= "<input title='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "'>&nbsp;";
			}
			if (in_array('BULKMAIL', $actions) && isPermitted($related_module, 1, '') == 'yes') {
				$button .= "<input title='" . getTranslatedString('LBL_BULK_MAILS') . "' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"sendmail\";this.form.module.value=\"$this_module\"' type='submit' name='button'" .
						" value='" . getTranslatedString('LBL_BULK_MAILS') . "'>";
			}
		}

		$query = 'select jo_contactdetails.accountid, jo_contactdetails.contactid, jo_contactdetails.firstname,jo_contactdetails.lastname, jo_contactdetails.department, jo_contactdetails.title, jo_contactdetails.email, jo_contactdetails.phone, jo_contactdetails.emailoptout, jo_crmentity.crmid, jo_crmentity.smownerid, jo_crmentity.modifiedtime from jo_contactdetails inner join jo_cntactivityrel on jo_cntactivityrel.contactid=jo_contactdetails.contactid inner join jo_crmentity on jo_crmentity.crmid = jo_contactdetails.contactid left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid where jo_cntactivityrel.activityid=' . $adb->quote($id) . ' and jo_crmentity.deleted=0';

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if ($return_value == null)
			$return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}

	/** Returns the column name that needs to be sorted
	 * Portions created by vtigerCRM are Copyright (C) vtigerCRM.
	 * All Rights Reserved.
 * Contributor(s): JoForce.com.
	 * Contributor(s): Mike Crowe
	 */
	function getSortOrder() {
		global $log;
		$log->debug("Entering getSortOrder() method ...");
		if (isset($_REQUEST['sorder']))
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['EMAILS_SORT_ORDER'] != '') ? ($_SESSION['EMAILS_SORT_ORDER']) : ($this->default_sort_order));

		$log->debug("Exiting getSortOrder method ...");
		return $sorder;
	}

	/** Returns the order in which the records need to be sorted
	 * Portions created by vtigerCRM are Copyright (C) vtigerCRM.
	 * All Rights Reserved.
 * Contributor(s): JoForce.com.
	 * Contributor(s): Mike Crowe
	 */
	function getOrderBy() {
		global $log;
		$log->debug("Entering getOrderBy() method ...");

		$use_default_order_by = '';
		if (PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}

		if (isset($_REQUEST['order_by']))
			$order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
		else
			$order_by = (($_SESSION['EMAILS_ORDER_BY'] != '') ? ($_SESSION['EMAILS_ORDER_BY']) : ($use_default_order_by));

		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}

	// Mike Crowe Mod --------------------------------------------------------

	/** Returns a list of the associated jo_users
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved.
 * Contributor(s): JoForce.com.
	 * Contributor(s): ______________________________________..
	 */
	function get_users($id) {
		global $log;
		$log->debug("Entering get_users(" . $id . ") method ...");
		global $adb;
		global $mod_strings;
		global $app_strings;

		$id = $_REQUEST['record'];

		$button = '<input title="' . getTranslatedString('LBL_BULK_MAILS') . '" accessykey="F" class="crmbutton small create"
				onclick="this.form.action.value=\"sendmail\";this.form.return_action.value=\"DetailView\";this.form.module.value=\"Emails\";this.form.return_module.value=\"Emails\";"
				name="button" value="' . getTranslatedString('LBL_BULK_MAILS') . '" type="submit">&nbsp;
				<input title="' . getTranslatedString('LBL_BULK_MAILS') . '" accesskey="" tabindex="2" class="crmbutton small edit"
				value="' . getTranslatedString('LBL_SELECT_USER_BUTTON_LABEL') . '" name="Button" language="javascript"
				onclick=\"return window.open("index.php?module=Users&return_module=Emails&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=true&return_id=' . $id . '&recordid=' . $id . '","test","width=640,height=520,resizable=0,scrollbars=0");\"
				type="button">';

		$query = 'SELECT jo_users.id, jo_users.first_name,jo_users.last_name, jo_users.user_name, jo_users.email1, jo_users.email2, jo_users.secondaryemail , jo_users.phone_home, jo_users.phone_work, jo_users.phone_mobile, jo_users.phone_other, jo_users.phone_fax from jo_users inner join jo_salesmanactivityrel on jo_salesmanactivityrel.smid=jo_users.id and jo_salesmanactivityrel.activityid=?';
		$result = $adb->pquery($query, array($id));

		$noofrows = $adb->num_rows($result);
		$header [] = $app_strings['LBL_LIST_NAME'];

		$header [] = $app_strings['LBL_LIST_USER_NAME'];

		$header [] = $app_strings['LBL_EMAIL'];

		$header [] = $app_strings['LBL_PHONE'];
		while ($row = $adb->fetch_array($result)) {

			global $current_user;

			$entries = Array();

			if (is_admin($current_user)) {
				$entries[] = getFullNameFromArray('Users', $row);
			} else {
				$entries[] = getFullNameFromArray('Users', $row);
			}

			$entries[] = $row['user_name'];
			$entries[] = $row['email1'];
			if ($email == '')
				$email = $row['email2'];
			if ($email == '')
				$email = $row['secondaryemail'];

			$entries[] = $row['phone_home'];
			if ($phone == '')
				$phone = $row['phone_work'];
			if ($phone == '')
				$phone = $row['phone_mobile'];
			if ($phone == '')
				$phone = $row['phone_other'];
			if ($phone == '')
				$phone = $row['phone_fax'];

			//Adding Security Check for User

			$entries_list[] = $entries;
		}

		if ($entries_list != '')
			$return_data = array("header" => $header, "entries" => $entries);

		if ($return_data == null)
			$return_data = Array();
		$return_data['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_users method ...");
		return $return_data;
	}

	/**
	 * Returns a list of the Emails to be exported
	 */
	function create_export_query(&$order_by, &$where) {
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(" . $order_by . "," . $where . ") method ...");

		include("includes/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Emails", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list FROM jo_activity
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid=jo_activity.activityid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid
			LEFT JOIN jo_seactivityrel
				ON jo_seactivityrel.activityid = jo_activity.activityid
			LEFT JOIN jo_contactdetails
				ON jo_contactdetails.contactid = jo_seactivityrel.crmid
			LEFT JOIN jo_cntactivityrel
				ON jo_cntactivityrel.activityid = jo_activity.activityid
				AND jo_cntactivityrel.contactid = jo_cntactivityrel.contactid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_salesmanactivityrel
				ON jo_salesmanactivityrel.activityid = jo_activity.activityid
			LEFT JOIN jo_emaildetails
				ON jo_emaildetails.emailid = jo_activity.activityid
			LEFT JOIN jo_seattachmentsrel
				ON jo_activity.activityid=jo_seattachmentsrel.crmid
			LEFT JOIN jo_attachments
				ON jo_seattachmentsrel.attachmentsid = jo_attachments.attachmentsid";
		$query .= getNonAdminAccessControlQuery('Emails', $current_user);
		$query .= "WHERE jo_activity.activitytype='Emails' AND jo_crmentity.deleted=0 ";

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}

	/**
	 * Used to releate email and contacts -- Outlook Plugin
	 */
	function set_emails_contact_invitee_relationship($email_id, $contact_id) {
		global $log;
		$log->debug("Entering set_emails_contact_invitee_relationship(" . $email_id . "," . $contact_id . ") method ...");
		$query = "insert into $this->rel_contacts_table (contactid,activityid) values(?,?)";
		$this->db->pquery($query, array($contact_id, $email_id), true, "Error setting email to contact relationship: " . "<BR>$query");
		$log->debug("Exiting set_emails_contact_invitee_relationship method ...");
	}

	/**
	 * Used to releate email and salesentity -- Outlook Plugin
	 */
	function set_emails_se_invitee_relationship($email_id, $contact_id) {
		global $log;
		$log->debug("Entering set_emails_se_invitee_relationship(" . $email_id . "," . $contact_id . ") method ...");
		$query = "insert into $this->rel_serel_table (crmid,activityid) values(?,?)";
		$this->db->pquery($query, array($contact_id, $email_id), true, "Error setting email to contact relationship: " . "<BR>$query");
		$log->debug("Exiting set_emails_se_invitee_relationship method ...");
	}

	/**
	 * Used to releate email and Users -- Outlook Plugin
	 */
	function set_emails_user_invitee_relationship($email_id, $user_id) {
		global $log;
		$log->debug("Entering set_emails_user_invitee_relationship(" . $email_id . "," . $user_id . ") method ...");
		$query = "insert into $this->rel_users_table (smid,activityid) values (?,?)";
		$this->db->pquery($query, array($user_id, $email_id), true, "Error setting email to user relationship: " . "<BR>$query");
		$log->debug("Exiting set_emails_user_invitee_relationship method ...");
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;

		$sql = 'DELETE FROM jo_seactivityrel WHERE activityid=? AND crmid = ?';
		$this->db->pquery($sql, array($id, $return_id));

		parent::unlinkRelationship($id, $return_module, $return_id);

		$this->db->pquery('UPDATE jo_crmentity SET modifiedtime = ? WHERE crmid = ?', array(date('y-m-d H:i:d'), $id));
	}

	public function getNonAdminAccessControlQuery($module, $user, $scope='') {
  		        $get_userdetails = get_privileges($user->id);
        foreach ($get_userdetails as $key => $value) {
            if(is_object($value)){
                $value = (array) $value;
                foreach ($value as $decode_key => $decode_value) {
                    if(is_object($decode_value)){
                        $value[$decode_key] = (array) $decode_value;
                    }
                }
                $$key = $value;
                }else{
                    $$key = $value;
                }
        }
        $get_sharingdetails = get_sharingprivileges($user->id);
        foreach ($get_sharingdetails as $key => $value) {
            if(is_object($value)){
                $value = (array) $value;
                    foreach ($value as $decode_key => $decode_value) {
                       if(is_object($decode_value)){
                          $value[$decode_key] = (array) $decode_value;
                        }
                    }
                    $$key = $value;
            }else{
                $$key = $value;
            }
        }

		$query = ' ';
		$tabId = getTabid($module);
		if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2]
				== 1 && $defaultOrgSharingPermission[$tabId] == 3) {
			$tableName = 'vt_tmp_u' . $user->id;
			$sharingRuleInfoVariable = $module . '_share_read_permission';
			$sharingRuleInfo = $sharingRuleInfoVariable;
			$sharedTabId = null;
			if (!empty($sharingRuleInfo) && (count($sharingRuleInfo['ROLE']) > 0 ||
					count($sharingRuleInfo['GROUP']) > 0)) {
				$tableName = $tableName . '_t' . $tabId;
				$sharedTabId = $tabId;
			}
			$this->setupTemporaryTable($tableName, $sharedTabId, $user, $current_user_parent_role_seq, $current_user_groups);
			$query = " INNER JOIN $tableName $tableName$scope ON $tableName$scope.id = " .
					"jo_crmentity$scope.smownerid ";
		}
		return $query;
	}

	protected function setupTemporaryTable($tableName, $tabId, $user, $parentRole, $userGroups) {
		$module = null;
		if (!empty($tabId)) {
			$module = getTabname($tabId);
		}
		$query = $this->getNonAdminAccessQuery($module, $user, $parentRole, $userGroups);
		$query = "create temporary table IF NOT EXISTS $tableName(id int(11) primary key, shared int(1) default 0) ignore ".$query;
		$db = PearDatabase::getInstance();
		$result = $db->pquery($query, array());
		if(is_object($result)) {
			return true;
		}
		return false;
	}

	/*
	* Function to get the relation tables for related modules
	* @param - $secmodule secondary module name
	* returns the array with table names and fieldnames storing relations between module and this module
	*/
	function setRelationTables($secmodule) {
		$rel_tables = array (
				"Leads" => array("jo_seactivityrel" => array("activityid", "crmid"), "jo_activity" => "activityid"),
				"Vendors" => array("jo_seactivityrel" => array("activityid", "crmid"), "jo_activity" => "activityid"),
				"Contacts" => array("jo_seactivityrel" => array("activityid", "crmid"), "jo_activity" => "activityid"),
				"Accounts" => array("jo_seactivityrel" => array("activityid", "crmid"), "jo_activity" => "activityid"),
		);
		return $rel_tables[$secmodule];
	}

	/*
	* Function to get the secondary query part of a report
	* @param - $module primary module name
	* @param - $secmodule secondary module name
	* returns the query string formed on fetching the related data for report for secondary module
	*/
	function generateReportsSecQuery($module, $secmodule, $queryPlanner){
		$focus = CRMEntity::getInstance($module);
		$matrix = $queryPlanner->newDependencyMatrix();

		$matrix->setDependency("jo_crmentityEmails",array("jo_groupsEmails","jo_usersEmails","jo_lastModifiedByEmails"));

		if (!$queryPlanner->requireTable('jo_activity', $matrix)) {
			return '';
		}

		$matrix->setDependency("jo_activity",array("jo_crmentityEmails","jo_email_track"));

		$query = $this->getRelationQuery($module, $secmodule, "jo_activity","activityid", $queryPlanner);
		if ($queryPlanner->requireTable("jo_crmentityEmails")){
			$query .= " LEFT JOIN jo_crmentity AS jo_crmentityEmails ON jo_crmentityEmails.crmid=jo_activityEmails.activityid and jo_crmentityEmails.deleted = 0";
		}
		if ($queryPlanner->requireTable("jo_groupsEmails")){
			$query .= " LEFT JOIN jo_groups AS jo_groupsEmails ON jo_groupsEmails.groupid = jo_crmentityEmails.smownerid";
		}
		if ($queryPlanner->requireTable("jo_usersEmails")){
			$query .= " LEFT JOIN jo_users AS jo_usersEmails ON jo_usersEmails.id = jo_crmentityEmails.smownerid";
		}
		if ($queryPlanner->requireTable("jo_lastModifiedByEmails")){
			$query .= " LEFT JOIN jo_users AS jo_lastModifiedByEmails ON jo_lastModifiedByEmails.id = jo_crmentityEmails.modifiedby and jo_seactivityreltmpEmails.activityid = jo_activityEmails.activityid";
		}
		if ($queryPlanner->requireTable("jo_createdbyEmails")){
			$query .= " left join jo_users as jo_createdbyEmails on jo_createdbyEmails.id = jo_crmentityEmails.smcreatorid and jo_seactivityreltmpEmails.activityid = jo_activityEmails.activityid";
		}
		if ($queryPlanner->requireTable("jo_email_track")){
			$query .= " LEFT JOIN jo_email_track ON jo_email_track.mailid = jo_activityEmails.activityid and jo_email_track.crmid = ".$focus->table_name.".".$focus->table_index;
		}
		return $query;
	}

	/*
	 * Function to store the email access count value of emails in 'jo_email_track' table
	 * @param - $mailid
	 */
	function setEmailAccessCountValue($mailid) {
		global $adb;
		$successIds = array();
		$result = $adb->pquery('SELECT idlists FROM jo_emaildetails WHERE emailid=?', array($mailid));
		$idlists = $adb->query_result($result,0,'idlists');
		$idlistsArray = explode('|', $idlists);

		for ($i=0; $i<(count($idlistsArray)-1); $i++) {
			$crmid = explode("@",$idlistsArray[$i]);
			array_push($successIds, $crmid[0]);
		}
		$successIds = array_unique($successIds);
		sort($successIds);
		for ($i=0; $i<count($successIds); $i++) {
			$adb->pquery("INSERT INTO jo_email_track(crmid, mailid,  access_count) VALUES(?,?,?)", array($successIds[$i], $mailid, 0));
		}
	}

}

//added for attach the generated pdf with email
function pdfAttach($obj, $module, $file_name, $id, $filePath = 'storage/') {
	global $log;
	$log->debug("Entering into pdfAttach() method.");

	global $adb, $current_user;
	global $upload_badext;
	$date_var = date('Y-m-d H:i:s');

	$ownerid = $obj->column_fields['assigned_user_id'];
	if (!isset($ownerid) || $ownerid == '')
		$ownerid = $current_user->id;

	$current_id = $adb->getUniqueID("jo_crmentity");

	$upload_file_path = decideFilePath();

	//Copy the file from temporary directory into storage directory for upload
	$source_file_path = $filePath . $file_name;
	$status = copy($source_file_path, $upload_file_path . $current_id . "_" . $file_name);
	//Check wheather the copy process is completed successfully or not. if failed no need to put entry in attachment table
	if ($status) {
		$query1 = "insert into jo_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(?,?,?,?,?,?,?)";
		$params1 = array($current_id, $current_user->id, $ownerid, $module . " Attachment", $obj->column_fields['description'], $adb->formatDate($date_var, true), $adb->formatDate($date_var, true));
		$adb->pquery($query1, $params1);

		$query2 = "insert into jo_attachments(attachmentsid, name, description, type, path) values(?,?,?,?,?)";
		$params2 = array($current_id, $file_name, $obj->column_fields['description'], 'pdf', $upload_file_path);
		$result = $adb->pquery($query2, $params2);

		$query3 = 'insert into jo_seattachmentsrel values(?,?)';
		$adb->pquery($query3, array($id, $current_id));

		// Delete the file that was copied
		checkFileAccessForDeletion($source_file_path);
		unlink($source_file_path);

		return true;
	} else {
		$log->debug("pdf not attached");
		return false;
	}
}

//this function check email fields profile permission as well as field access permission
function emails_checkFieldVisiblityPermission($fieldname, $mode='readonly') {
	global $current_user;
	$ret = getFieldVisibilityPermission('Emails', $current_user->id, $fieldname, $mode);
	return $ret;
}

?>
