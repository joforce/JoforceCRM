<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

class Head_AccessControl {

	protected $privileges;
	protected static $PRIVILEGE_ATTRS = array('is_admin', 'current_user_role', 'current_user_parent_role_seq',
		'current_user_profiles', 'profileGlobalPermission', 'profileTabsPermission', 'profileActionPermission',
		'current_user_groups', 'subordinate_roles', 'parent_roles', 'subordinate_roles_users', 'user_info'
	);

	protected function __consturct() {
		$this->privileges = array();
	}

	protected function loadUserPrivilegesWithId($id) {
		global $adb;
		if (!isset($this->privileges[$id])) {
			$user_query =  $adb->pquery("SELECT * FROM jo_privileges where user_id=?",array($id));
        	$user_count = $adb->num_rows($user_query);
        	if($user_count > 0){
          		$user_privilege =  $adb->query_result($user_query,0,'user_privilege');
          		$decode_user_privilege_value = json_decode(html_entity_decode($user_privilege));
          		foreach ($decode_user_privilege_value as $key => $value) {
          			if(is_object($value)){
          				$value = (array) $value;
          				foreach ($value as $decode_key => $decode_value) {
          					if(is_object($decode_value)){
          						$value[$decode_key] = (array) $decode_value;
          					}
          				}
          				
          				$$key = $value;
          			}else{
          					foreach ($value as $decode_key => $decode_value) {
          					if(is_object($decode_value)){
          						$value[$decode_key] = (array) $decode_value;
          					}
          				}
          					$$key = $value;
          			}
            		
          		}
      		}
			$privilege = new stdClass;
			foreach (self::$PRIVILEGE_ATTRS as $attr) {
				if($attr == 'currency_symbol'){
        			$$attr = htmlspecialchars($$attr);
      			}
				if (isset($attr) && $$attr !=''){
					$privilege->$attr = $$attr;
				}
			}
			$this->privileges[$id] = $privilege;
		}

		return $this->privileges[$id];
	}

	protected static $singleton = null;
	public static function loadUserPrivileges($id) {
		if (self::$singleton == null) {
			self::$singleton = new self();
		}
		return self::$singleton->loadUserPrivilegesWithId($id);
	}

	public static function clearUserPrivileges($id) {
		if (self::$singleton == null) {
			self::$singleton = new self();
		}

		if (self::$singleton->privileges[$id]) {
			unset(self::$singleton->privileges[$id]);
		}
	}

}
