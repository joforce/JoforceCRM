<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/
class ProjectTask extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name = 'jo_projecttask';
	var $table_index= 'projecttaskid';
	var $column_fields = Array();

	/** Indicator if this is a custom module or standard module */
	var $IsCustomModule = true;

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('jo_projecttaskcf', 'projecttaskid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('jo_crmentity', 'jo_projecttask', 'jo_projecttaskcf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'jo_crmentity' => 'crmid',
		'jo_projecttask'   => 'projecttaskid',
		'jo_projecttaskcf' => 'projecttaskid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'jo_'
		'Project Task Name'=> Array('projecttask', 'projecttaskname'),
		'Start Date'=> Array('projecttask', 'startdate'),
		'End Date'=> Array('projecttask', 'enddate'),
		'Type'=>Array('projecttask','projecttasktype'),
		'Progress'=>Array('projecttask','projecttaskprogress'),
		'Assigned To' => Array('crmentity','smownerid')

	);
	var $list_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Project Task Name'=> 'projecttaskname',
		'Start Date'=>'startdate',
		'End Date'=> 'enddate',
		'Type'=>'projecttasktype',
		'Progress'=>'projecttaskprogress',
		'Assigned To' => 'assigned_user_id'
	);

	// Make the field link to detail view from list view (Fieldname)
	var $list_link_field = 'projecttaskname';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'jo_'
		'Project Task Name'=> Array('projecttask', 'projecttaskname'),
		'Start Date'=> Array('projecttask', 'startdate'),
		'Type'=>Array('projecttask','projecttasktype'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $search_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Project Task Name'=> 'projecttaskname',
		'Start Date'=>'startdate',
		'Type'=>'projecttasktype',
		'Assigned To' => 'assigned_user_id'
	);

	// For Popup window record selection
	var $popup_fields = Array('projecttaskname');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	// For Alphabetical search
	var $def_basicsearch_col = 'projecttaskname';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'projecttaskname';

	// Required Information for enabling Import feature
	var $required_fields = Array('projecttaskname'=>1);

	// Callback function list during Importing
	var $special_functions = Array('set_import_assigned_user');

	var $default_order_by = 'projecttaskname';
	var $default_sort_order='ASC';
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to jo_field.fieldname values.
	var $mandatory_fields = Array('createdtime', 'modifiedtime', 'projecttaskname', 'projectid', 'assigned_user_id');

	function __construct() {
		global $log, $currentModule;
		$this->column_fields = getColumnFields(get_class($this));
		$this->db = PearDatabase::getInstance();
		$this->log = $log;
	}

   function save_module($module) {
	}

	/**
	 * Return query to use based on given modulename, fieldname
	 * Useful to handle specific case handling for Popup
	 */
	function getQueryByModuleField($module, $fieldname, $srcrecord) {
		// $srcrecord could be empty
	}

	/**
	 * Get list view query (send more WHERE clause condition if required)
	 */
	function getListQuery($module, $where='') {
		$query = "SELECT jo_crmentity.*, $this->table_name.*";

		// Keep track of tables joined to avoid duplicates
		$joinedTables = array();

		// Select Custom Field Table Columns if present
		if(!empty($this->customFieldTable)) $query .= ", " . $this->customFieldTable[0] . ".* ";

		$query .= " FROM $this->table_name";

		$query .= "	INNER JOIN jo_crmentity ON jo_crmentity.crmid = $this->table_name.$this->table_index";

		$joinedTables[] = $this->table_name;
		$joinedTables[] = 'jo_crmentity';

		// Consider custom table join as well.
		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
					  " = $this->table_name.$this->table_index";
			$joinedTables[] = $this->customFieldTable[0];
		}
		$query .= " LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid";
		$query .= " LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid";

		$joinedTables[] = 'jo_users';
		$joinedTables[] = 'jo_groups';

		$linkedModulesQuery = $this->db->pquery("SELECT distinct fieldname, columnname, relmodule FROM jo_field" .
				" INNER JOIN jo_fieldmodulerel ON jo_fieldmodulerel.fieldid = jo_field.fieldid" .
				" WHERE uitype='10' AND jo_fieldmodulerel.module=?", array($module));
		$linkedFieldsCount = $this->db->num_rows($linkedModulesQuery);

		for($i=0; $i<$linkedFieldsCount; $i++) {
			$related_module = $this->db->query_result($linkedModulesQuery, $i, 'relmodule');
			$fieldname = $this->db->query_result($linkedModulesQuery, $i, 'fieldname');
			$columnname = $this->db->query_result($linkedModulesQuery, $i, 'columnname');

			$other =  CRMEntity::getInstance($related_module);
			modlib_setup_modulevars($related_module, $other);

			if(!in_array($other->table_name, $joinedTables)) {
				$query .= " LEFT JOIN $other->table_name ON $other->table_name.$other->table_index = $this->table_name.$columnname";
				$joinedTables[] = $other->table_name;
			}
		}

		global $current_user;
		$query .= $this->getNonAdminAccessControlQuery($module,$current_user);
		$query .= "	WHERE jo_crmentity.deleted = 0 ".$usewhere;
		return $query;
	}

	/**
	 * Apply security restriction (sharing privilege) query part for List view.
	 */
	function getListViewSecurityParameter($module) {
		global $current_user;
        $get_userdetails = get_privileges($current_user->id);
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
        $get_sharingdetails = get_sharingprivileges($current_user->id);
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


		$sec_query = '';
		$tabid = getTabid($module);

		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1
			&& $defaultOrgSharingPermission[$tabid] == 3) {

				$sec_query .= " AND (jo_crmentity.smownerid in($current_user->id) OR jo_crmentity.smownerid IN
					(
						SELECT jo_user2role.userid FROM jo_user2role
						INNER JOIN jo_users ON jo_users.id=jo_user2role.userid
						INNER JOIN jo_role ON jo_role.roleid=jo_user2role.roleid
						WHERE jo_role.parentrole LIKE '".$current_user_parent_role_seq."::%'
					)
					OR jo_crmentity.smownerid IN
					(
						SELECT shareduserid FROM jo_tmp_read_user_sharing_per
						WHERE userid=".$current_user->id." AND tabid=".$tabid."
					)
					OR
						(";

					// Build the query based on the group association of current user.
					if(sizeof($current_user_groups) > 0) {
						$sec_query .= " jo_groups.groupid IN (". implode(",", $current_user_groups) .") OR ";
					}
					$sec_query .= " jo_groups.groupid IN
						(
							SELECT jo_tmp_read_group_sharing_per.sharedgroupid
							FROM jo_tmp_read_group_sharing_per
							WHERE userid=".$current_user->id." and tabid=".$tabid."
						)";
				$sec_query .= ")
				)";
		}
		return $sec_query;
	}

	/**
	 * Create query to export the records.
	 */
	function create_export_query($where)
	{
		global $current_user;

		include("includes/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery('ProjectTask', "detail_view");

		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list, jo_users.user_name AS user_name
					FROM jo_crmentity INNER JOIN $this->table_name ON jo_crmentity.crmid=$this->table_name.$this->table_index";

		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
					  " = $this->table_name.$this->table_index";
		}

		$query .= " LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid";
		$query .= " LEFT JOIN jo_users ON jo_crmentity.smownerid = jo_users.id and jo_users.status='Active'";

		$linkedModulesQuery = $this->db->pquery("SELECT distinct fieldname, columnname, relmodule FROM jo_field" .
				" INNER JOIN jo_fieldmodulerel ON jo_fieldmodulerel.fieldid = jo_field.fieldid" .
				" WHERE uitype='10' AND jo_fieldmodulerel.module=?", array($thismodule));
		$linkedFieldsCount = $this->db->num_rows($linkedModulesQuery);

		for($i=0; $i<$linkedFieldsCount; $i++) {
			$related_module = $this->db->query_result($linkedModulesQuery, $i, 'relmodule');
			$fieldname = $this->db->query_result($linkedModulesQuery, $i, 'fieldname');
			$columnname = $this->db->query_result($linkedModulesQuery, $i, 'columnname');

			$other = CRMEntity::getInstance($related_module);
			modlib_setup_modulevars($related_module, $other);

			$query .= " LEFT JOIN $other->table_name ON $other->table_name.$other->table_index = $this->table_name.$columnname";
		}

		$query .= $this->getNonAdminAccessControlQuery($thismodule,$current_user);
		$where_auto = " jo_crmentity.deleted=0";

		if($where != '') $query .= " WHERE ($where) AND $where_auto";
		else $query .= " WHERE $where_auto";

		return $query;
	}

	/**
	 * Transform the value while exporting
	 */
	function transform_export_value($key, $value) {
		return parent::transform_export_value($key, $value);
	}

	/**
	 * Function which will give the basic query to find duplicates
	 */
	function getDuplicatesQuery($module,$table_cols,$field_values,$ui_type_arr,$select_cols='') {
		$select_clause = "SELECT ". $this->table_name .".".$this->table_index ." AS recordid, jo_users_last_import.deleted,".$table_cols;

		// Select Custom Field Table Columns if present
		if(isset($this->customFieldTable)) $query .= ", " . $this->customFieldTable[0] . ".* ";

		$from_clause = " FROM $this->table_name";

		$from_clause .= "	INNER JOIN jo_crmentity ON jo_crmentity.crmid = $this->table_name.$this->table_index";

		// Consider custom table join as well.
		if(isset($this->customFieldTable)) {
			$from_clause .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
					  " = $this->table_name.$this->table_index";
		}
		$from_clause .= " LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid
						LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid";

		$where_clause = "	WHERE jo_crmentity.deleted = 0";
		$where_clause .= $this->getListViewSecurityParameter($module);

		if (isset($select_cols) && trim($select_cols) != '') {
			$sub_query = "SELECT $select_cols FROM  $this->table_name AS t " .
				" INNER JOIN jo_crmentity AS crm ON crm.crmid = t.".$this->table_index;
			// Consider custom table join as well.
			if(isset($this->customFieldTable)) {
				$sub_query .= " LEFT JOIN ".$this->customFieldTable[0]." tcf ON tcf.".$this->customFieldTable[1]." = t.$this->table_index";
			}
			$sub_query .= " WHERE crm.deleted=0 GROUP BY $select_cols HAVING COUNT(*)>1";
		} else {
			$sub_query = "SELECT $table_cols $from_clause $where_clause GROUP BY $table_cols HAVING COUNT(*)>1";
		}


		$query = $select_clause . $from_clause .
					" LEFT JOIN jo_users_last_import ON jo_users_last_import.bean_id=" . $this->table_name .".".$this->table_index .
					" INNER JOIN (" . $sub_query . ") AS temp ON ".get_on_clause($field_values,$ui_type_arr,$module) .
					$where_clause .
					" ORDER BY $table_cols,". $this->table_name .".".$this->table_index ." ASC";

		return $query;
	}

	/**
	 * Invoked when special actions are performed on the module.
	 * @param String Module name
	 * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
	 */
	function modlib_handler($modulename, $event_type) {
		global $adb;
		if($event_type == 'module.postinstall') {
			$projectTaskResult = $adb->pquery('SELECT tabid FROM jo_tab WHERE name=?', array('ProjectTask'));
			$projecttaskTabid = $adb->query_result($projectTaskResult, 0, 'tabid');

			// Mark the module as Standard module
			$adb->pquery('UPDATE jo_tab SET customized=0 WHERE name=?', array($modulename));

			if(getTabid('CustomerPortal')) {
				$checkAlreadyExists = $adb->pquery('SELECT 1 FROM jo_customerportal_tabs WHERE tabid=?', array($projecttaskTabid));
				if($checkAlreadyExists && $adb->num_rows($checkAlreadyExists) < 1) {
					$maxSequenceQuery = $adb->query("SELECT max(sequence) as maxsequence FROM jo_customerportal_tabs");
					$maxSequence = $adb->query_result($maxSequenceQuery, 0, 'maxsequence');
					$nextSequence = $maxSequence+1;
					$adb->query("INSERT INTO jo_customerportal_tabs(tabid,visible,sequence) VALUES ($projecttaskTabid,1,$nextSequence)");
					$adb->query("INSERT INTO jo_customerportal_prefs(tabid,prefkey,prefvalue) VALUES ($projecttaskTabid,'showrelatedinfo',1)");
				}
			}

			$modcommentsModuleInstance = Head_Module::getInstance('ModComments');
			if($modcommentsModuleInstance && file_exists('modules/ModComments/ModComments.php')) {
				include_once 'modules/ModComments/ModComments.php';
				if(class_exists('ModComments')) ModComments::addWidgetTo(array('ProjectTask'));
			}

			$result = $adb->pquery("SELECT 1 FROM jo_modentity_num WHERE semodule = ? AND active = 1", array($modulename));
			if (!($adb->num_rows($result))) {
				//Initialize module sequence for the module
				$adb->pquery("INSERT INTO jo_modentity_num values(?,?,?,?,?,?)", array($adb->getUniqueId("jo_modentity_num"), $modulename, 'PT', 1, 1, 1));
			}
		} else if($event_type == 'module.disabled') {
			// TODO Handle actions when this module is disabled.
		} else if($event_type == 'module.enabled') {
			// TODO Handle actions when this module is enabled.
		} else if($event_type == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($event_type == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($event_type == 'module.postupdate') {

			$modcommentsModuleInstance = Head_Module::getInstance('ModComments');
			if($modcommentsModuleInstance && file_exists('modules/ModComments/ModComments.php')) {
				include_once 'modules/ModComments/ModComments.php';
				if(class_exists('ModComments')) ModComments::addWidgetTo(array('ProjectTask'));
			}

			$result = $adb->pquery("SELECT 1 FROM jo_modentity_num WHERE semodule = ? AND active = 1", array($modulename));
			if (!($adb->num_rows($result))) {
				//Initialize module sequence for the module
				$adb->pquery("INSERT INTO jo_modentity_num values(?,?,?,?,?,?)", array($adb->getUniqueId("jo_modentity_num"), $modulename, 'PT', 1, 1, 1));
			}
		}
	}

	/**
	 * Function to check the module active and user action permissions before showing as link in other modules
	 * like in more actions of detail view(Projects).
	 */
	static function isLinkPermitted($linkData) {
		$moduleName = "ProjectTask";
		if(modlib_isModuleActive($moduleName) && isPermitted($moduleName, 'EditView') == 'yes') {
			return true;
		}
		return false;
	}

	/**
	 * Handle saving related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	// function save_related_module($module, $crmid, $with_module, $with_crmid) { }

	/**
	 * Handle deleting related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function delete_related_module($module, $crmid, $with_module, $with_crmid) { }

	/**
	 * Handle getting related list information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function get_related_list($id, $cur_tab_id, $rel_tab_id, $actions=false) { }

	/**
	 * Handle getting dependents list information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function get_dependents_list($id, $cur_tab_id, $rel_tab_id, $actions=false) { }

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryPlanner){

		$matrix = $queryPlanner->newDependencyMatrix();
		$matrix->setDependency('jo_crmentityProjectTask', array('jo_groupsProjectTask', 'jo_usersProjectTask', 'jo_lastModifiedByProjectTask'));

		if (!$queryPlanner->requireTable('jo_projecttask', $matrix)) {
			return '';
		}
		$matrix->setDependency('jo_projecttask', array('jo_crmentityProjectTask'));

		$query .= $this->getRelationQuery($module,$secmodule,"jo_projecttask","projecttaskid", $queryPlanner);

		if ($queryPlanner->requireTable('jo_crmentityProjectTask', $matrix)) {
			$query .= " left join jo_crmentity as jo_crmentityProjectTask on jo_crmentityProjectTask.crmid=jo_projecttask.projecttaskid and jo_crmentityProjectTask.deleted=0";
		}
		if ($queryPlanner->requireTable('jo_projecttaskcf')) {
			$query .= " left join jo_projecttaskcf on jo_projecttask.projecttaskid = jo_projecttaskcf.projecttaskid";
		}
		if ($queryPlanner->requireTable('jo_groupsProjectTask')) {
			$query .= "	left join jo_groups as jo_groupsProjectTask on jo_groupsProjectTask.groupid = jo_crmentityProjectTask.smownerid";
		}
		if ($queryPlanner->requireTable('jo_usersProjectTask')) {
			$query .= " left join jo_users as jo_usersProjectTask on jo_usersProjectTask.id = jo_crmentityProjectTask.smownerid";
		}
		if ($queryPlanner->requireTable('jo_lastModifiedByProjectTask')) {
			$query .= " left join jo_users as jo_lastModifiedByProjectTask on jo_lastModifiedByProjectTask.id = jo_crmentityProjectTask.modifiedby ";
		}
		if ($queryPlanner->requireTable("jo_createdbyProjectTask")){
			$query .= " left join jo_users as jo_createdbyProjectTask on jo_createdbyProjectTask.id = jo_crmentityProjectTask.smcreatorid ";
		}
		return $query;
	}

	function get_emails($recordId, $currentTabId, $relTabId, $actions=false) {
		global $currentModule,$single_pane_view;
		$relModuleName = modlib_getModuleNameById($relTabId);
		$singularRelModuleName = modlib_tosingular($relModuleName);
		require_once "modules/$relModuleName/$relModuleName.php";
		$relModuleFocus = new $relModuleName();
		modlib_setup_modulevars($relModuleName, $relModuleFocus);


		$returnSet = '&return_module='.$currentModule.'&return_action=CallRelatedList&return_id='.$recordId;

		$button .= '<input type="hidden" name="email_directing_module"><input type="hidden" name="record">';
		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('ADD', $actions) && isPermitted($relModuleName,1, '') == 'yes') {
				$button .= "<input title='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singularRelModuleName)."' accessyKey='F' class='crmbutton small create' onclick='fnvshobj(this,\"sendmail_cont\");sendmail(\"$currentModule\",$recordId);' type='button' name='button' value='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singularRelModuleName)."'></td>";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
				jo_activity.activityid, jo_activity.subject, jo_activity.activitytype, jo_crmentity.modifiedtime,
				jo_crmentity.crmid, jo_crmentity.smownerid, jo_activity.date_start,jo_activity.time_start, jo_seactivityrel.crmid as parent_id
				FROM jo_activity, jo_seactivityrel, jo_projecttask,jo_users, jo_crmentity
				LEFT JOIN jo_groups ON jo_groups.groupid=jo_crmentity.smownerid
				WHERE jo_seactivityrel.activityid = jo_activity.activityid
				AND jo_seactivityrel.crmid = $recordId
				AND jo_users.id=jo_crmentity.smownerid
				AND jo_crmentity.crmid = jo_activity.activityid
				AND jo_activity.activitytype='Emails'
				AND jo_projecttask.projecttaskid = $recordId
				AND jo_crmentity.deleted = 0";

		$returnValue = GetRelatedList($currentModule, $relModuleName, $relModuleFocus, $query, $button, $returnSet);

		if(!$returnValue) $returnValue = array();

		$returnValue['CUSTOM_BUTTON'] = $button;
		return $returnValue;
	}

	/**
	 * Move the related records of the specified list of id's to the given record.
	 * @param String This module name
	 * @param Array List of Entity Id's from which related records need to be transfered
	 * @param Integer Id of the the Record to which the related records are to be moved
	 */
	function transferRelatedRecords($module, $transferEntityIds, $entityId) {
		global $adb;

		$rel_table_arr = Array("Documents" => "jo_senotesrel");

		$tbl_field_arr = Array("jo_senotesrel" => "notesid");

		$entity_tbl_field_arr = Array("jo_senotesrel" => "crmid");

		foreach ($transferEntityIds as $transferId) {
			foreach ($rel_table_arr as $rel_module => $rel_table) {
				if (Head_Module::getInstance($rel_module) != FALSE) {
					$id_field = $tbl_field_arr[$rel_table];
					$entity_id_field = $entity_tbl_field_arr[$rel_table];
					// IN clause to avoid duplicate entries
					$sel_result = $adb->pquery("select $id_field from $rel_table where $entity_id_field=? " .
							" and $id_field not in (select $id_field from $rel_table where $entity_id_field=?)", array($transferId, $entityId));
					$res_cnt = $adb->num_rows($sel_result);
					if ($res_cnt > 0) {
						for ($i = 0; $i < $res_cnt; $i++) {
							$id_field_value = $adb->query_result($sel_result, $i, $id_field);
							$adb->pquery("update $rel_table set $entity_id_field=? where $entity_id_field=? and $id_field=?", array($entityId, $transferId, $id_field_value));
						}
					}
				}
			}
		}
		parent::transferRelatedRecords($module, $transferEntityIds, $entityId);
	}

}

?>
