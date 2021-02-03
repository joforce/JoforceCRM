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

require_once 'includes/runtime/Cache.php';
include_once('libraries/modlib/Head/ModuleBasic.php');
/**
 * Provides API to work with vtiger CRM Modules
 * @package modlib
 */
class Head_Module extends Head_ModuleBasic {

	/**
	 * Function to get the Module/Tab id
	 * @return <Number>
	 */
	public function getId() {
		return $this->id;
	}
        
	/**
	 * Get unique id for related list
	 * @access private
	 */
	function __getRelatedListUniqueId() {
		global $adb;
		return $adb->getUniqueID('jo_relatedlists');
	}

	/**
	 * Get related list sequence to use
	 * @access private
	 */
	function __getNextRelatedListSequence() {
		global $adb;
		$max_sequence = 0;
		$result = $adb->pquery("SELECT max(sequence) as maxsequence FROM jo_relatedlists WHERE tabid=?", Array($this->id));
		if($adb->num_rows($result)) $max_sequence = $adb->query_result($result, 0, 'maxsequence');
		return ++$max_sequence;
	}

	/**
	 * Set related list information between other module
	 * @param Head_Module Instance of target module with which relation should be setup
	 * @param String Label to display in related list (default is target module name)
	 * @param Array List of action button to show ('ADD', 'SELECT')
	 * @param String Callback function name of this module to use as handler
	 *
	 * @internal Creates table jo_crmentityrel if it does not exists
	 */
	function setRelatedList($moduleInstance, $label = '', $actions = false, $function_name = 'get_related_list', $fieldId = null) {
		global $adb;

		if(empty($moduleInstance)) return;

		if(!Head_Utils::CheckTable('jo_crmentityrel')) {
			Head_Utils::CreateTable(
				'jo_crmentityrel',
				'(crmid INT NOT NULL, module VARCHAR(100) NOT NULL, relcrmid INT NOT NULL, relmodule VARCHAR(100) NOT NULL)',
				true
			);
		}

		$relation_id = $this->__getRelatedListUniqueId();
		$sequence = $this->__getNextRelatedListSequence();
		$presence = 0; // 0 - Enabled, 1 - Disabled

		if(empty($label)) $label = $moduleInstance->name;

		// Allow ADD action of other module records (default)
		if($actions === false) $actions = Array('ADD');

		$useactions_text = $actions;
		if(is_array($actions)) $useactions_text = implode(',', $actions);
		$useactions_text = strtoupper($useactions_text);

		// Add column to jo_relatedlists to save extended actions
		Head_Utils::AddColumn('jo_relatedlists', 'actions', 'VARCHAR(50)');

		$adb->pquery("INSERT INTO jo_relatedlists(relation_id,tabid,related_tabid,name,sequence,label,presence,actions,relationfieldid) VALUES(?,?,?,?,?,?,?,?,?)",
			Array($relation_id,$this->id,$moduleInstance->id,$function_name,$sequence,$label,$presence,$useactions_text,$fieldId));

		self::log("Setting relation with $moduleInstance->name [$useactions_text] ... DONE");
	}

	/**
	 * Unset related list information that exists with other module
	 * @param Head_Module Instance of target module with which relation should be setup
	 * @param String Label to display in related list (default is target module name)
	 * @param String Callback function name of this module to use as handler
	 */
	function unsetRelatedList($moduleInstance, $label='', $function_name='get_related_list') {
		global $adb;

		if(empty($moduleInstance)) return;

		if(empty($label)) $label = $moduleInstance->name;

		$adb->pquery("DELETE FROM jo_relatedlists WHERE tabid=? AND related_tabid=? AND name=? AND label=?",
			Array($this->id, $moduleInstance->id, $function_name, $label));

		self::log("Unsetting relation with $moduleInstance->name ... DONE");
	}
    
	function unsetRelatedListForField($fieldId) {
		$db = PearDatabase::getInstance();
		$db->pquery("DELETE FROM jo_relatedlists WHERE relationfieldid=?", array($fieldId));
	}

	/**
	 * Add custom link for a module page
	 * @param String Type can be like 'DETAILVIEW', 'LISTVIEW' etc..
 	 * @param String Label to use for display
	 * @param String HREF value to use for generated link
	 * @param String Path to the image file (relative or absolute)
	 * @param Integer Sequence of appearance
	 *
	 * NOTE: $url can have variables like $MODULE (module for which link is associated),
	 * $RECORD (record on which link is dispalyed)
	 */
	function addLink($type, $label, $url, $iconpath='', $sequence=0, $handlerInfo=null) {
		Head_Link::addLink($this->id, $type, $label, $url, $iconpath, $sequence, $handlerInfo);
	}

	/**
	 * Delete custom link of a module
	 * @param String Type can be like 'DETAILVIEW', 'LISTVIEW' etc..
 	 * @param String Display label to lookup
	 * @param String URL value to lookup
	 */
	function deleteLink($type, $label, $url=false) {
		Head_Link::deleteLink($this->id, $type, $label, $url);
	}

	/**
	 * Get all the custom links related to this module.
	 */
	function getLinks() {
		return Head_Link::getAll($this->id);
	}


	/**
	 * Get all the custom links related to this module for exporting.
	 */
	function getLinksForExport() {
		return Head_Link::getAllForExport($this->id);
	}

	/**
	 * Initialize webservice setup for this module instance.
	 */
	function initWebservice() {
		Head_Webservice::initialize($this);
	}

	/**
	 * De-Initialize webservice setup for this module instance.
	 */
	function deinitWebservice() {
		Head_Webservice::uninitialize($this);
	}

	/**
	 * Get instance by id or name
	 * @param mixed id or name of the module
	 */
	static function getInstance($value) {
		$instance = Head_Cache::get('module', $value);
		if (!$instance && $instance !== NULL) {
			$data = Head_Functions::getModuleData($value);
			if ($data) {
				$instance = new self();
				$instance->initialize($data);
				Head_Cache::set('module', $instance->id, $instance);
				Head_Cache::set('module', $instance->name, $instance);
			} else {
				Head_Cache::set('module', $value, NULL);
			}
		}
		return $instance ? $instance : false;
	}

	/**
	 * Get instance of the module class.
	 * @param String Module name
	 */
	static function getClassInstance($modulename) {
        $remove_modules = array('Rss', 'Portal', 'ExtensionStore', 'Assets', 'ServiceContracts', 'Faq');
        if(!in_array($modulename, $remove_modules)){
            if($modulename == 'Calendar') $modulename = 'Activity';

            $instance = false;
            $filepath = "modules/$modulename/$modulename.php";
            if(Head_Utils::checkFileAccessForInclusion($filepath, false)) {
                checkFileAccessForInclusion($filepath);
                include_once($filepath);
                if(class_exists($modulename)) {
                    $instance = new $modulename();
                }
            }
            return $instance;
        }
    }
	/**
	 * Fire the event for the module (if modlib_handler is defined)
	 */
	static function fireEvent($modulename, $event_type) {
		$instance = self::getClassInstance((string)$modulename);
		if($instance) {
			if(method_exists($instance, 'modlib_handler')) {
				self::log("Invoking modlib_handler for $event_type ...START");
				$instance->modlib_handler((string)$modulename, (string)$event_type);
				self::log("Invoking modlib_handler for $event_type ...DONE");
			}
		}
	}
}
?>
