<?php
class autocomplete extends rcube_plugin
{

	public function init()
	{
		$this->add_hook('contacts_autocomplete_after', array($this, 'crm_autocomplete'));
	}

	public function crm_autocomplete($value)
	{
		$search = $value['search'];
		$RC = rcube::get_instance();
		$DB = rcube_db::factory($RC->config->get('db_dsnw'));

		$DB->set_debug((bool)$RC->config->get('sql_debug'));

		// Connect to database

		$DB->db_connect('w');

		if(!$DB->is_connected())

			die("Cannot able to get outgoing server details");
		else

			$res = $DB->query("select firstname, lastname, email from (select firstname, lastname, email from jo_contactdetails where email like '$search%' union select firstname, lastname, email from jo_leaddetails where email like '$search%' union select '', accountname, email1 from jo_account where email1 like '$search%') as val limit 5");

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
		$result = array('contacts' => $search_result);
		return $result;
	}

}
