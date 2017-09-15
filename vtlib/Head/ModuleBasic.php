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
include_once('vtlib/Head/Access.php');
include_once('vtlib/Head/Block.php');
include_once('vtlib/Head/Field.php');
include_once('vtlib/Head/Filter.php');
include_once('vtlib/Head/Profile.php');
include_once('vtlib/Head/Menu.php');
include_once('vtlib/Head/Link.php');
include_once('vtlib/Head/Event.php');
include_once('vtlib/Head/Webservice.php');
include_once('vtlib/Head/Version.php');
require_once 'includes/runtime/Cache.php';

/**
 * Provides API to work with vtiger CRM Module
 * @package vtlib
 */
class Head_ModuleBasic {
	/** ID of this instance */
	var $id = false;
	var $name = false;
	var $label = false;
	var $version= 0;
	var $minversion = false;
	var $maxversion = false;

	var $presence = 0;
	var $ownedby = 0; // 0 - Sharing Access Enabled, 1 - Sharing Access Disabled
	var $tabsequence = false;
	var $parent = false;
	var $customized = 0;
	var $trial = 0;
	var $isentitytype = true; // Real module or an extension?

	var $entityidcolumn = false;
	var $entityidfield = false;

	var $basetable = false;
	var $basetableid=false;
	var $customtable=false;
	var $grouptable = false;

	const EVENT_MODULE_ENABLED     = 'module.enabled';
	const EVENT_MODULE_DISABLED    = 'module.disabled';
	const EVENT_MODULE_POSTINSTALL = 'module.postinstall';
	const EVENT_MODULE_PREUNINSTALL= 'module.preuninstall';
	const EVENT_MODULE_PREUPDATE   = 'module.preupdate';
	const EVENT_MODULE_POSTUPDATE  = 'module.postupdate';


	/**
	 * Constructor
	 */
	function __construct() {
	}

	/**
	 * Initialize this instance
	 * @access private
	 */
	function initialize($valuemap) {
		$this->id = $valuemap['tabid'];
		$this->name=$valuemap['name'];
		$this->label=$valuemap['tablabel'];
		$this->version=$valuemap['version'];

		$this->presence = $valuemap['presence'];
		$this->ownedby = $valuemap['ownedby'];
		$this->tabsequence = $valuemap['tabsequence'];
		$this->parent = $valuemap['parent'];
		$this->customized = $valuemap['customized'];
		$this->trial = $valuemap['trial']; 

		$this->isentitytype = $valuemap['isentitytype'];
		if($this->isentitytype || $this->name == 'Users') {
			// Initialize other details too
			$this->initialize2();
		}
	}

	/**
	 * Initialize more information of this instance
	 * @access private
	 */
	function initialize2() {
		$entitydata = Head_Functions::getEntityModuleInfo($this->name);
		if ($entitydata) {
			$this->basetable = $entitydata['tablename'];
			$this->basetableid=$entitydata['entityidfield'];
		}
	}

	/**
	 * Get unique id for this instance
	 * @access private
	 */
	function __getUniqueId() {
		global $adb;
		$result = $adb->query("SELECT MAX(tabid) AS max_seq FROM jo_tab");
		$maxseq = $adb->query_result($result, 0, 'max_seq');
		return ++$maxseq;
	}

	/**
	 * Get next sequence to use for this instance
	 * @access private
	 */
	function __getNextSequence() {
		global $adb;
		$result = $adb->pquery("SELECT MAX(tabsequence) AS max_tabseq FROM jo_tab", array());
		$maxtabseq = $adb->query_result($result, 0, 'max_tabseq');
		return ++$maxtabseq;
	}

	/**
	 * Initialize vtiger schema changes.
	 * @access private
	 */
	function __handleHeadCoreSchemaChanges() {
		// Add version column to the table first
		Head_Utils::AddColumn('jo_tab', 'version', ' VARCHAR(10)');
        Head_Utils::AddColumn('jo_tab', 'parent', ' VARCHAR(30)');
	}

	/**
	 * Create this module instance
	 * @access private
	 */
	function __create() {
		global $adb;

		self::log("Creating Module $this->name ... STARTED");

		$this->id = $this->__getUniqueId();
		if(!$this->tabsequence) $this->tabsequence = $this->__getNextSequence();
		if(!$this->label) $this->label = $this->name;

		$customized = 1; // To indicate this is a Custom Module

		$this->__handleHeadCoreSchemaChanges();

		$adb->pquery("INSERT INTO jo_tab (tabid,name,presence,tabsequence,tablabel,modifiedby,
			modifiedtime,customized,ownedby,version,parent) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
			Array($this->id, $this->name, $this->presence, -1, $this->label, NULL, NULL, $customized, $this->ownedby, $this->version,$this->parent));

		$useisentitytype = $this->isentitytype? 1 : 0;
		$adb->pquery('UPDATE jo_tab set isentitytype=? WHERE tabid=?',Array($useisentitytype, $this->id));

		if(!Head_Utils::CheckTable('jo_tab_info')) {
			Head_Utils::CreateTable(
				'jo_tab_info',
				'(tabid INT, prefname VARCHAR(256), prefvalue VARCHAR(256), FOREIGN KEY fk_1_jo_tab_info(tabid) REFERENCES jo_tab(tabid) ON DELETE CASCADE ON UPDATE CASCADE)',
				true);
		}
		if($this->minversion) {
			$tabResult = $adb->pquery("SELECT 1 FROM jo_tab_info WHERE tabid=? AND prefname='jo_min_version'", array($this->id));
			if ($adb->num_rows($tabResult) > 0) {
				$adb->pquery("UPDATE jo_tab_info SET prefvalue=? WHERE tabid=? AND prefname='jo_min_version'", array($this->minversion,$this->id));
			} else {
				$adb->pquery('INSERT INTO jo_tab_info(tabid, prefname, prefvalue) VALUES (?,?,?)', array($this->id, 'jo_min_version', $this->minversion));
			}
		}
		if($this->maxversion) {
			$tabResult = $adb->pquery("SELECT 1 FROM jo_tab_info WHERE tabid=? AND prefname='jo_max_version'", array($this->id));
			if ($adb->num_rows($tabResult) > 0) {
				$adb->pquery("UPDATE jo_tab_info SET prefvalue=? WHERE tabid=? AND prefname='jo_max_version'", array($this->maxversion,$this->id));
			} else {
				$adb->pquery('INSERT INTO jo_tab_info(tabid, prefname, prefvalue) VALUES (?,?,?)', array($this->id, 'jo_max_version', $this->maxversion));
			}
		}

		Head_Profile::initForModule($this);

		self::syncfile();

		if($this->isentitytype) {
			Head_Access::initSharing($this);
		}

		$moduleInstance = Head_Module::getInstance($this->name);
		$parentTab = $this->parent;

		if (!empty($parentTab)) {
			$menuInstance = Head_Menu::getInstance($parentTab);
			$menuInstance->addModule($moduleInstance);
		}

		self::log("Creating Module $this->name ... DONE");
	}

	/**
	 * Update this instance
	 * @access private
	 */
	function __update() {
		self::log("Updating Module $this->name ... DONE");
	}

	/**
	 * Delete this instance
	 * @access private
	 */
	function __delete() {
		Head_Module::fireEvent($this->name,
			Head_Module::EVENT_MODULE_PREUNINSTALL);

		global $adb;
		if($this->isentitytype) {
			$this->unsetEntityIdentifier();
			$this->deleteRelatedLists();
		}

		$adb->pquery("DELETE FROM jo_tab WHERE tabid=?", Array($this->id));
		self::log("Deleting Module $this->name ... DONE");
	}

	/**
	 * Update module version information
	 * @access private
	 */
	function __updateVersion($newversion) {
		$this->__handleHeadCoreSchemaChanges();
		global $adb;
		$adb->pquery("UPDATE jo_tab SET version=? WHERE tabid=?", Array($newversion, $this->id));
		$this->version = $newversion;
		self::log("Updating version to $newversion ... DONE");
	}

	/**
	 * Save this instance
	 */
	function save() {
		if($this->id) $this->__update();
		else $this->__create();
		Head_Cache::delete('module', $this->name);
		return $this->id;
	}

	/**
	 * Delete this instance
	 */
	function delete() {
		if($this->isentitytype) {
			Head_Access::deleteSharing($this);
			Head_Access::deleteTools($this);
			Head_Filter::deleteForModule($this);
			Head_Block::deleteForModule($this);
			if(method_exists($this, 'deinitWebservice')) {
				$this->deinitWebservice();
			}
		}
		$this->__delete();
		Head_Profile::deleteForModule($this);
		Head_Link::deleteAll($this->id);
		Head_Menu::detachModule($this);
		self::syncfile();
        Head_Cache::flushModuleCache($this->name);
	}

	/**
	 * Initialize table required for the module
	 * @param String Base table name (default modulename in lowercase)
	 * @param String Base table column (default modulenameid in lowercase)
	 *
	 * Creates basetable, customtable, grouptable <br>
	 * customtable name is basetable + 'cf'<br>
	 * grouptable name is basetable + 'grouprel'<br>
	 */
	function initTables($basetable=false, $basetableid=false) {
		$this->basetable = $basetable;
		$this->basetableid=$basetableid;

		// Initialize tablename and index column names
		$lcasemodname = strtolower($this->name);
		if(!$this->basetable) $this->basetable = "jo_$lcasemodname";
		if(!$this->basetableid)$this->basetableid=$lcasemodname . "id";

		if(!$this->customtable)$this->customtable = $this->basetable . "cf";
		if(!$this->grouptable)$this->grouptable = $this->basetable."grouprel";

		Head_Utils::CreateTable($this->basetable,"($this->basetableid INT(19) PRIMARY KEY)",true);
		Head_Utils::CreateTable($this->customtable,"($this->basetableid INT(19) PRIMARY KEY)", true);
		if(Head_Version::check('5.0.4', '<=')) {
			Head_Utils::CreateTable($this->grouptable, "($this->basetableid INT PRIMARY KEY, groupname varchar(100))",true);
		}
	}

	/**
	 * Set entity identifier field for this module
	 * @param Head_Field Instance of field to use
	 */
	function setEntityIdentifier($fieldInstance) {
		global $adb;

		if($this->basetableid) {
			if(!$this->entityidfield) $this->entityidfield = $this->basetableid;
			if(!$this->entityidcolumn)$this->entityidcolumn= $this->basetableid;
		}
		if ($this->entityidfield && $this->entityidcolumn) {
			$result = $adb->pquery("SELECT tabid FROM jo_entityname WHERE tablename=? AND tabid=?", array($fieldInstance->table, $this->id));
			if ($adb->num_rows($result) == 0) {
				$adb->pquery("INSERT INTO jo_entityname(tabid, modulename, tablename, fieldname, entityidfield, entityidcolumn) VALUES(?,?,?,?,?,?)", Array($this->id, $this->name, $fieldInstance->table, $fieldInstance->name, $this->entityidfield, $this->entityidcolumn));
				self::log("Setting entity identifier ... DONE");
			} else {
				$adb->pquery("UPDATE jo_entityname SET fieldname=?,entityidfield=?,entityidcolumn=? WHERE tablename=? AND tabid=?", array($fieldInstance->name, $this->entityidfield, $this->entityidcolumn, $fieldInstance->table, $this->id));
				self::log("Updating entity identifier ... DONE");
			}
		}
	}

	/**
	 * Unset entity identifier information
	 */
	function unsetEntityIdentifier() {
		global $adb;
		$adb->pquery("DELETE FROM jo_entityname WHERE tabid=?", Array($this->id));
		self::log("Unsetting entity identifier ... DONE");
	}

	/**
	 * Delete related lists information
	 */
	function deleteRelatedLists() {
		global $adb;
		$adb->pquery("DELETE FROM jo_relatedlists WHERE tabid=?", Array($this->id));
		self::log("Deleting related lists ... DONE");
	}

	/**
	 * Delete links information
	 */
	function deleteLinks() {
		global $adb;
		$adb->pquery("DELETE FROM jo_links WHERE tabid=?", Array($this->id));
		self::log("Deleting links ... DONE");
	}

	/**
	 * Configure default sharing access for the module
	 * @param String Permission text should be one of ['Public_ReadWriteDelete', 'Public_ReadOnly', 'Public_ReadWrite', 'Private']
	 */
	function setDefaultSharing($permission_text='Public_ReadWriteDelete') {
		Head_Access::setDefaultSharing($this, $permission_text);
	}

	/**
	 * Allow module sharing control
	 */
	function allowSharing() {
		Head_Access::allowSharing($this, true);
	}
	/**
	 * Disallow module sharing control
	 */
	function disallowSharing() {
		Head_Access::allowSharing($this, false);
	}

	/**
	 * Enable tools for this module
	 * @param mixed String or Array with value ['Import', 'Export', 'Merge']
	 */
	function enableTools($tools) {
		if(is_string($tools)) {
			$tools = Array(0 => $tools);
		}

		foreach($tools as $tool) {
			Head_Access::updateTool($this, $tool, true);
		}
	}

	/**
	 * Disable tools for this module
	 * @param mixed String or Array with value ['Import', 'Export', 'Merge']
	 */
	function disableTools($tools) {
		if(is_string($tools)) {
			$tools = Array(0 => $tools);
		}
		foreach($tools as $tool) {
			Head_Access::updateTool($this, $tool, false);
		}
	}

	/**
	 * Add block to this module
	 * @param Head_Block Instance of block to add
	 */
	function addBlock($blockInstance) {
		$blockInstance->save($this);
		return $this;
	}

	/**
	 * Add filter to this module
	 * @param Head_Filter Instance of filter to add
	 */
	function addFilter($filterInstance) {
		$filterInstance->save($this);
		return $this;
	}

	/**
	 * Get all the fields of the module or block
	 * @param Head_Block Instance of block to use to get fields, false to get all the block fields
	 */
	function getFields($blockInstance=false) {
		$fields = false;
		if($blockInstance) $fields = Head_Field::getAllForBlock($blockInstance, $this);
		else $fields = Head_Field::getAllForModule($this);
		return $fields;
	}

	/**
	 * Helper function to log messages
	 * @param String Message to log
	 * @param Boolean true appends linebreak, false to avoid it
	 * @access private
	 */
	static function log($message, $delimit=true) {
		Head_Utils::Log($message, $delimit);
	}

	/**
	 * Synchronize the menu information to flat file
	 * @access private
	 */
	static function syncfile() {
		self::log("Updating tabdata file ... ", false);
		create_tab_data_file();
		self::log("DONE");
	}
}
?>
