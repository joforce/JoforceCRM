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

include_once 'includes/data/CRMEntity.php';
require_once 'libraries/modlib/Head/Link.php';
include_once 'libraries/modlib/Head/Module.php';
include_once('libraries/modlib/Head/Menu.php');
require 'includes/events/include.inc';
require_once 'includes/utils/utils.php';

class PBXManager extends CRMEntity
{

    protected $incominglinkLabel = 'Incoming Calls';
    protected $tabId = 0;
    protected $headerScriptLinkType = 'HEADERSCRIPT';
    protected $dependentModules = array('Contacts', 'Leads', 'Accounts');


    var $db;
    var $table_name = 'jo_pbxmanager';
    var $table_index = 'pbxmanagerid';
    var $customFieldTable = array('jo_pbxmanagercf', 'pbxmanagerid');
    var $tab_name = array('jo_crmentity', 'jo_pbxmanager', 'jo_pbxmanagercf');
    var $tab_name_index = array(
        'jo_crmentity' => 'crmid',
        'jo_pbxmanager' => 'pbxmanagerid',
        'jo_pbxmanagercf' => 'pbxmanagerid'
    );
    var $list_fields = array(
        /* Format: Field Label => Array(tablename, columnname) */
        // tablename should not have prefix 'jo_'
        'Call Status'    => array('jo_pbxmanager', 'callstatus'),
        'Customer' => array('jo_pbxmanager', 'customer'),
        'User' => array('jo_pbxmanager', 'user'),
        'Recording' => array('jo_pbxmanager', 'recordingurl'),
        'Start Time' => array('jo_pbxmanager', 'starttime'),
    );
    var $list_fields_name = array(
        /* Format: Field Label => fieldname */
        'Call Status' => 'callstatus',
        'Customer' => 'customer',
        'User'     => 'user',
        'Recording' => 'recordingurl',
        'Start Time' => 'starttime',
    );
    // Make the field link to detail view
    var $list_link_field = 'customernumber';
    // For Popup listview and UI type support
    var $search_fields = array(
        /* Format: Field Label => Array(tablename, columnname) */
        // tablename should not have prefix 'jo_'
        'Customer' => array('jo_pbxmanager', 'customer'),
    );
    var $search_fields_name = array(
        /* Format: Field Label => fieldname */
        'Customer' => 'customer',
    );
    // For Popup window record selection
    var $popup_fields = array('customernumber');
    // For Alphabetical search
    var $def_basicsearch_col = 'customer';
    // Column value to use on detail view record text display
    var $def_detailview_recname = 'customernumber';
    // Used when enabling/disabling the mandatory fields for the module.
    // Refers to jo_field.fieldname values.
    //    var $mandatory_fields = Array('assigned_user_id');
    var $column_fields = array();
    var $default_order_by = 'customernumber';
    var $default_sort_order = 'ASC';

    function PBXManager()
    {
        $this->db = PearDatabase::getInstance();
        $this->column_fields = getColumnFields('PBXManager');
    }

    /**
     * Invoked when special actions are performed on the module.
     * @param String Module name
     * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
     */
    function modlib_handler($modulename, $event_type)
    {
        if ($event_type == 'module.postinstall') {
            $this->addLinksForPBXManager();
            $this->registerLookupEvents();
            $this->addSettingsLinks();
            $this->addActionMapping();
            $this->setModuleRelatedDependencies();
            $this->addUserExtensionField();
        } else if ($event_type == 'module.disabled') {
            $this->removeLinksForPBXManager();
            $this->unregisterLookupEvents();
            $this->removeSettingsLinks();
            $this->removeActionMapping();
            $this->unsetModuleRelatedDependencies();
        } else if ($event_type == 'module.enabled') {
            $this->addLinksForPBXManager();
            $this->registerLookupEvents();
            $this->addSettingsLinks();
            $this->addActionMapping();
            $this->setModuleRelatedDependencies();
        } else if ($event_type == 'module.preuninstall') {
            $this->removeLinksForPBXManager();
            $this->unregisterLookupEvents();
            $this->removeSettingsLinks();
            $this->removeActionMapping();
            $this->unsetModuleRelatedDependencies();
        } else if ($event_type == 'module.preupdate') {
            // TODO Handle actions before this module is updated.
        } else if ($event_type == 'module.postupdate') {
            // TODO Handle actions before this module is updated.
        }
    }


    /** Function to handle module specific operations when saving a entity
     */
    function save_module($module)
    {
    }

    /**
     * To add a phone extension field in user preferences page 
     */
    function addUserExtensionField()
    {
        global $log;
        $module = Head_Module::getInstance('Users');
        if ($module) {
            $module->initTables();
            $blockInstance = Head_Block::getInstance('LBL_MORE_INFORMATION', $module);
            if ($blockInstance) {
                $fieldInstance = new Head_Field();
                $fieldInstance->name = 'phone_crm_extension';
                $fieldInstance->label = 'CRM Phone Extension';
                $fieldInstance->uitype = 11;
                $fieldInstance->typeofdata = 'V~O';
                $blockInstance->addField($fieldInstance);
            }
        }
        $log->fatal('User Extension Field added');
    }

    /**
     * To register phone lookup events 
     */
    function registerLookupEvents()
    {
        global $log;
        $adb = PearDatabase::getInstance();
        $EventManager = new EventsManager($adb);
        $createEvent = 'jo.entity.aftersave';
        $deleteEVent = 'jo.entity.afterdelete';
        $restoreEvent = 'jo.entity.afterrestore';
        $batchSaveEvent = 'jo.batchevent.save';
        $batchDeleteEvent = 'jo.batchevent.delete';
        $handler_path = 'modules/PBXManager/PBXManagerHandler.php';
        $className = 'PBXManagerHandler';
        $batchEventClassName = 'PBXManagerBatchHandler';
        $EventManager->registerHandler($createEvent, $handler_path, $className, '', '["EntityDelta"]');
        $EventManager->registerHandler($deleteEVent, $handler_path, $className);
        $EventManager->registerHandler($restoreEvent, $handler_path, $className);
        $EventManager->registerHandler($batchSaveEvent, $handler_path, $batchEventClassName);
        $EventManager->registerHandler($batchDeleteEvent, $handler_path, $batchEventClassName);
        $log->fatal('Lookup Events Registered');
    }

    /**
     * To add PBXManager module in module($this->dependentModules) related lists
     */
    function setModuleRelatedDependencies()
    {
        global $log;
        $pbxmanager = Head_Module::getInstance('PBXManager');
        foreach ($this->dependentModules as $module) {
            $moduleInstance = Head_Module::getInstance($module);
            $moduleInstance->setRelatedList($pbxmanager, "PBXManager", array(), 'get_dependents_list');
        }
        $log->fatal('Successfully added Module Related lists');
    }

    /**
     * To remove PBXManager module from module($this->dependentModules) related lists
     */
    function unsetModuleRelatedDependencies()
    {
        global $log;
        $pbxmanager = Head_Module::getInstance('PBXManager');
        foreach ($this->dependentModules as $module) {
            $moduleInstance = Head_Module::getInstance($module);
            $moduleInstance->unsetRelatedList($pbxmanager, "PBXManager", 'get_dependents_list');
        }
        $log->fatal('Successfully removed Module Related lists');
    }

    /**
     * To unregister phone lookup events 
     */
    function unregisterLookupEvents()
    {
        global $log;
        $adb = PearDatabase::getInstance();
        $EventManager = new EventsManager($adb);
        $className = 'PBXManagerHandler';
        $batchEventClassName = 'PBXManagerBatchHandler';
        $EventManager->unregisterHandler($className);
        $EventManager->unregisterHandler($batchEventClassName);
        $log->fatal('Lookup Events Unregistered');
    }

    /**
     * To add a link in jo_links which is to load our PBXManagerJS.js 
     */
    function addLinksForPBXManager()
    {
        global $log;
        $handlerInfo = array(
            'path' => 'modules/PBXManager/PBXManager.php',
            'class' => 'PBXManager',
            'method' => 'checkLinkPermission'
        );

        Head_Link::addLink($this->tabId, $this->headerScriptLinkType, $this->incominglinkLabel, 'modules/PBXManager/resources/PBXManagerJS.js', '', '', $handlerInfo);
        $log->fatal('Links added');
    }

    /**
     * To remove link for PBXManagerJS.js from jo_links
     */
    function removeLinksForPBXManager()
    {
        global $log;
        //Deleting Headerscripts links
        Head_Link::deleteLink($this->tabId, $this->headerScriptLinkType, $this->incominglinkLabel, 'modules/PBXManager/resources/PBXManagerJS.js');
        $log->fatal('Links Removed');
    }

    /**
     * To add Integration->PBXManager block in Settings page
     */
    function addSettingsLinks()
    {
        global $log;
        $adb = PearDatabase::getInstance();
        $integrationBlock = $adb->pquery('SELECT * FROM jo_settings_blocks WHERE label=?', array('LBL_INTEGRATION'));
        $integrationBlockCount = $adb->num_rows($integrationBlock);

        // To add Block
        if ($integrationBlockCount > 0) {
            $blockid = $adb->query_result($integrationBlock, 0, 'blockid');
        } else {
            $blockid = $adb->getUniqueID('jo_settings_blocks');
            $sequenceResult = $adb->pquery("SELECT max(sequence) as sequence FROM jo_settings_blocks", array());
            if ($adb->num_rows($sequenceResult)) {
                $sequence = $adb->query_result($sequenceResult, 0, 'sequence');
            }
            $adb->pquery("INSERT INTO jo_settings_blocks(blockid, label, sequence) VALUES(?,?,?)", array($blockid, 'LBL_INTEGRATION', ++$sequence));
        }

        // To add a Field
        $fieldid = $adb->getUniqueID('jo_settings_field');
        $adb->pquery("INSERT INTO jo_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence, active)
            VALUES(?,?,?,?,?,?,?,?)", array($fieldid, $blockid, 'LBL_PBXMANAGER', 'fa fa-phone', 'PBXManager module Configuration', 'index.php?module=PBXManager&parent=Settings&view=Index', 2, 0));
        $log->fatal('Settings Block and Field added');
    }

    /**
     * To delete Integration->PBXManager block in Settings page
     */
    function removeSettingsLinks()
    {
        global $log;
        $adb = PearDatabase::getInstance();
        $adb->pquery('DELETE FROM jo_settings_field WHERE name=?', array('LBL_PBXMANAGER'));
        $log->fatal('Settings Field Removed');
    }

    /**
     * To enable(ReceiveIncomingCall & MakeOutgoingCall) tool in profile
     */
    function addActionMapping()
    {
        global $log;
        $adb = PearDatabase::getInstance();
        $module = new Head_Module();
        $moduleInstance = $module->getInstance('PBXManager');

        //To add actionname as ReceiveIncomingcalls
        $maxActionIdresult = $adb->pquery('SELECT max(actionid+1) AS actionid FROM jo_actionmapping', array());
        if ($adb->num_rows($maxActionIdresult)) {
            $actionId = $adb->query_result($maxActionIdresult, 0, 'actionid');
        }
        $adb->pquery('INSERT INTO jo_actionmapping
                     (actionid, actionname, securitycheck) VALUES(?,?,?)', array($actionId, 'ReceiveIncomingCalls', 0));
        $moduleInstance->enableTools('ReceiveIncomingcalls');
        $log->fatal('ReceiveIncomingcalls ActionName Added');

        //To add actionname as MakeOutgoingCalls
        $maxActionIdresult = $adb->pquery('SELECT max(actionid+1) AS actionid FROM jo_actionmapping', array());
        if ($adb->num_rows($maxActionIdresult)) {
            $actionId = $adb->query_result($maxActionIdresult, 0, 'actionid');
        }
        $adb->pquery('INSERT INTO jo_actionmapping
                     (actionid, actionname, securitycheck) VALUES(?,?,?)', array($actionId, 'MakeOutgoingCalls', 0));
        $moduleInstance->enableTools('MakeOutgoingCalls');
        $log->fatal('MakeOutgoingCalls ActionName Added');
    }

    /**
     * To remove(ReceiveIncomingCall & MakeOutgoingCall) tool from profile
     */
    function removeActionMapping()
    {
        global $log;
        $adb = PearDatabase::getInstance();
        $module = new Head_Module();
        $moduleInstance = $module->getInstance('PBXManager');

        $moduleInstance->disableTools('ReceiveIncomingcalls');
        $adb->pquery('DELETE FROM jo_actionmapping 
                     WHERE actionname=?', array('ReceiveIncomingCalls'));
        $log->fatal('ReceiveIncomingcalls ActionName Removed');

        $moduleInstance->disableTools('MakeOutgoingCalls');
        $adb->pquery('DELETE FROM jo_actionmapping 
                      WHERE actionname=?', array('MakeOutgoingCalls'));
        $log->fatal('MakeOutgoingCalls ActionName Removed');
    }

    static function checkLinkPermission($linkData)
    {
        $module = new Head_Module();
        $moduleInstance = $module->getInstance('PBXManager');

        if ($moduleInstance) {
            return true;
        } else {
            return false;
        }
    }
}
