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
include_once('vtlib/Head/Utils.php');
include_once('vtlib/Head/FieldBasic.php');
require_once 'includes/runtime/Cache.php';

/**
 * Provides APIs to control vtiger CRM Field
 * @package vtlib
 */
class Head_Field extends Head_FieldBasic {

	/**
	 * Get unique picklist id to use
	 * @access private
	 */
	function __getPicklistUniqueId() {
		global $adb;
		return $adb->getUniqueID('jo_picklist');
	}

	/**
	 * Set values for picklist field (for all the roles)
	 * @param Array List of values to add.
	 *
	 * @internal Creates picklist base if it does not exists
	 */
	function setPicklistValues($values) {
		global $adb,$default_charset;

		// Non-Role based picklist values
		if($this->uitype == '16') {
			$this->setNoRolePicklistValues($values);
			return;
		}
        
		$picklist_table = 'jo_'.$this->name;
		$picklist_idcol = $this->name.'id';
		if(!Head_Utils::CheckTable($picklist_table)) {
			Head_Utils::CreateTable(
				$picklist_table,
				"($picklist_idcol INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				$this->name VARCHAR(200) NOT NULL,
				presence INT (1) NOT NULL DEFAULT 1,
				picklist_valueid INT NOT NULL DEFAULT 0,
				sortorderid INT DEFAULT 0,
				color VARCHAR(10))",
				true);
			$new_picklistid = $this->__getPicklistUniqueId();
			$adb->pquery("INSERT INTO jo_picklist (picklistid,name) VALUES(?,?)",Array($new_picklistid, $this->name));
			self::log("Creating table $picklist_table ... DONE");
		} else {
			$picklistResult = $adb->pquery("SELECT picklistid FROM jo_picklist WHERE name=?", Array($this->name));
			$new_picklistid = $adb->query_result($picklistResult, 0, 'picklistid');
		}

		$specialNameSpacedPicklists  = array(
			'opportunity_type'=>'opptypeid',
			'duration_minutes'=>'minutesid',
			'recurringtype'=>'recurringeventid',
			'salutationtype' => 'salutationid',
		);

		// Fix Table ID column names
		$fieldName = (string)$this->name;
		if(in_array($fieldName.'_id', $adb->getColumnNames($picklist_table))) {
			$picklist_idcol = $fieldName.'_id';
		} elseif(array_key_exists($fieldName, $specialNameSpacedPicklists)) {
			$picklist_idcol = $specialNameSpacedPicklists[$fieldName];
		}
		// END

		// Add value to picklist now
		$maxSortIdResult = $adb->pquery("SELECT MAX(sortorderid) as maxsortid FROM $picklist_table", array());
                $sortid = $adb->query_result($maxSortIdResult, 0, 'maxsortid');
                if (empty($sortid)) 
                        $sortid = 0; // TODO To be set per role

		// for jo_sales_stage
		$sales_stage_array = getSalesStageArray('picklist');
		$stage_names_array = array_values($sales_stage_array);

		foreach($values as $value) {
			$new_picklistvalueid = getUniquePicklistID();
			$presence = 1; // 0 - readonly, Refer function in includes/ComboUtil.php
			$new_id = $adb->getUniqueID($picklist_table);

			if($picklist_table == 'jo_sales_stage')
			{
				if (is_array($value)) {
					if(!in_array($value[0], $stage_names_array))
					{
					$sortid = $sortid + 1;
					$adb->pquery("INSERT INTO $picklist_table($picklist_idcol, $this->name, presence, picklist_valueid,sortorderid,color) VALUES(?,?,?,?,?,?)", Array($new_id, $value[0], $presence, $new_picklistvalueid, $sortid, $value[1]));
					// Associate picklist values to all the role
		                        $adb->pquery("INSERT INTO jo_role2picklist(roleid, picklistvalueid, picklistid, sortid) SELECT roleid, $new_picklistvalueid, $new_picklistid, $sortid FROM jo_role", array());
					}
				} else {
					if(!in_array($value, $stage_names_array))
					{
					$sortid = $sortid + 1;
					$adb->pquery("INSERT INTO $picklist_table($picklist_idcol, $this->name, presence, picklist_valueid,sortorderid) VALUES(?,?,?,?,?)", Array($new_id, $value, $presence, $new_picklistvalueid, $sortid));
					}
					// Associate picklist values to all the role
                                        $adb->pquery("INSERT INTO jo_role2picklist(roleid, picklistvalueid, picklistid, sortid) SELECT roleid, $new_picklistvalueid, $new_picklistid, $sortid FROM jo_role", array());
				}
			}
			else
			{
				$sortid = $sortid + 1;
				if (is_array($value)) {
                                        $adb->pquery("INSERT INTO $picklist_table($picklist_idcol, $this->name, presence, picklist_valueid,sortorderid,color) VALUES(?,?,?,?,?,?)", Array($new_id, $value[0], $presence, $new_picklistvalueid, $sortid, $value[1]));
                                } else {
                                        $adb->pquery("INSERT INTO $picklist_table($picklist_idcol, $this->name, presence, picklist_valueid,sortorderid) VALUES(?,?,?,?,?)", Array($new_id, $value, $presence, $new_picklistvalueid, $sortid));
                                }
				// Associate picklist values to all the role
                                $adb->pquery("INSERT INTO jo_role2picklist(roleid, picklistvalueid, picklistid, sortid) SELECT roleid, $new_picklistvalueid, $new_picklistid, $sortid FROM jo_role", array());
			}
		}
	}

	/**
	 * Set values for picklist field (non-role based)
	 * @param Array List of values to add
	 *
	 * @internal Creates picklist base if it does not exists
	 * @access private
	 */
	function setNoRolePicklistValues($values) {
		global $adb;
        $pickListName_ids = array('recurring_frequency','payment_duration');
		$picklist_table = 'jo_'.$this->name;
		$picklist_idcol = $this->name.'id';
        if(in_array($this->name, $pickListName_ids)){
           $picklist_idcol =  $this->name.'_id';
        }
		if(!Head_Utils::CheckTable($picklist_table)) {
			Head_Utils::CreateTable(
				$picklist_table,
				"($picklist_idcol INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				$this->name VARCHAR(200) NOT NULL,
				sortorderid INT(11),
				presence INT (11) NOT NULL DEFAULT 1,
				color VARCHAR(10))",
				true);
			self::log("Creating table $picklist_table ... DONE");
		}

		// Add value to picklist now
		$maxSortIdResult = $adb->pquery("SELECT MAX(sortorderid) as maxsortid FROM $picklist_table", array());
		$sortid = $adb->query_result($maxSortIdResult, 0, 'maxsortid');
		if (empty($sortid)) {
			$sortid = 0; // TODO To be set per role
		} else {
			++$sortid;
		}

		foreach($values as $value) {
			$presence = 1; // 0 - readonly, Refer function in includes/ComboUtil.php
			$new_id = $adb->getUniqueId($picklist_table);
			if (is_array($value)) {
				$adb->pquery("INSERT INTO $picklist_table($picklist_idcol, $this->name, sortorderid, presence, color) VALUES(?,?,?,?,?)", Array($new_id, $value[0], $sortid, $presence, $value[1]));
			} else {
				$adb->pquery("INSERT INTO $picklist_table($picklist_idcol, $this->name, sortorderid, presence) VALUES(?,?,?,?)", Array($new_id, $value, $sortid, $presence));
			}
			$sortid = $sortid+1;
		}
	}

	/**
	 * Set relation between field and modules (UIType 10)
	 * @param Array List of module names
	 * @internal Creates table jo_fieldmodulerel if it does not exists
	 */
	function setRelatedModules($moduleNames) {
		global $adb;

		// We need to create core table to capture the relation between the field and modules.
		if(!Head_Utils::CheckTable('jo_fieldmodulerel')) {
			Head_Utils::CreateTable(
				'jo_fieldmodulerel',
				'(fieldid INT NOT NULL, module VARCHAR(100) NOT NULL, relmodule VARCHAR(100) NOT NULL, status VARCHAR(10), sequence INT)',
				true
			);
		}

		$thisModule = Head_Module::getInstance($this->getModuleName());
		foreach ($moduleNames as $relmodule => $relLabel) {
			// Backward compatiblilty
			if (is_numeric($relmodule)) {
				$relmodule = $relLabel;
				$relLabel = null;
			}

			$checkres = $adb->pquery('SELECT * FROM jo_fieldmodulerel WHERE fieldid=? AND module=? AND relmodule=?',
				Array($this->id, $this->getModuleName(), $relmodule));

			// If relation already exist continue
			if($adb->num_rows($checkres)) continue;

			$adb->pquery('INSERT INTO jo_fieldmodulerel(fieldid, module, relmodule) VALUES(?,?,?)',
				Array($this->id, $this->getModuleName(), $relmodule));

			self::log("Setting $this->name relation with $relmodule ... DONE");

            if ($relLabel) {
				$otherModule = Head_Module::getInstance($relmodule);
				$moduleModel = Head_Module_Model::getInstance($this->getModuleName());
				//get default relation actions from module instance
				$actions = $moduleModel->getRelationShipActions();
				$otherModule->setRelatedList($thisModule, $relLabel, $actions, 'get_dependents_list', $this->id);
			}
		}
		return true;
	}

	/**
	 * Remove relation between the field and modules (UIType 10)
	 * @param Array List of module names
	 */
	function unsetRelatedModules($moduleNames) {
		global $adb;
		foreach($moduleNames as $relmodule) {
			$adb->pquery('DELETE FROM jo_fieldmodulerel WHERE fieldid=? AND module=? AND relmodule = ?',
				Array($this->id, $this->getModuleName(), $relmodule));

			Head_Utils::Log("Unsetting $this->name relation with $relmodule ... DONE");
		}
		return true;
	}

	/**
	 * Get Head_Field instance by fieldid or fieldname
	 * @param mixed fieldid or fieldname
	 * @param Head_Module Instance of the module if fieldname is used
	 */
	static function getInstance($value, $moduleInstance=false) {
		global $adb;
		$instance = false;
		$data = Head_Functions::getModuleFieldInfo($moduleInstance->id, $value);
		if ($data) {
            $instance = new self();
			$instance->initialize($data, $moduleInstance);
        }
        return $instance;
	}

	/**
	 * Get Head_Field instances related to block
	 * @param Head_Block Instnace of block to use
	 * @param Head_Module Instance of module to which block is associated
	 */
	 static function getAllForBlock($blockInstance, $moduleInstance=false) {
		$cache = Head_Cache::getInstance();
		if($cache->getBlockFields($moduleInstance->name,$blockInstance->id)){
			return $cache->getBlockFields($moduleInstance->name,$blockInstance->id);
		} else {
			global $adb;
			$instances = false;
			$query = false;
			$queryParams = false;
			if($moduleInstance) {
				$query = "SELECT * FROM jo_field WHERE block=? AND tabid=? ORDER BY sequence";
				$queryParams = Array($blockInstance->id, $moduleInstance->id);
			} else {
				$query = "SELECT * FROM jo_field WHERE block=? ORDER BY sequence";
				$queryParams = Array($blockInstance->id);
			}
			$result = $adb->pquery($query, $queryParams);
			for($index = 0; $index < $adb->num_rows($result); ++$index) {
				$instance = new self();
				$instance->initialize($adb->fetch_array($result), $moduleInstance, $blockInstance);
				$instances[] = $instance;
			}
			$cache->setBlockFields($blockInstance->module->name,$blockInstance->id,$instances);
			return $instances;
		}
	}

	/**
	 * Get Head_Field instances related to module
	 * @param Head_Module Instance of module to use
	 */
	static function getAllForModule($moduleInstance) {
		global $adb;
		$instances = false;

		$query = "SELECT * FROM jo_field WHERE tabid=? ORDER BY sequence";
		$queryParams = Array($moduleInstance->id);

		$result = $adb->pquery($query, $queryParams);
		for($index = 0; $index < $adb->num_rows($result); ++$index) {
			$instance = new self();
			$instance->initialize($adb->fetch_array($result), $moduleInstance);
			$instances[] = $instance;
		}
		return $instances;
	}

	/**
	 * Delete fields associated with the module
	 * @param Head_Module Instance of module
	 * @access private
	 */
	static function deleteForModule($moduleInstance) {
		global $adb;
		$adb->pquery("DELETE FROM jo_field WHERE tabid=?", Array($moduleInstance->id));
		self::log("Deleting fields of the module ... DONE");
	}
}
?>
