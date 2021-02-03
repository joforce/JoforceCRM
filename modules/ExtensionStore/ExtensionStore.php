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

require_once 'modules/Webforms/model/WebformsModel.php';
require_once 'includes/Webservices/DescribeObject.php';

class ExtensionStore {
    
    var $LBL_MODULE_NAME='ExtensionStore';
    var $LBL_MODULE_NAME_OLD='ExtensionStore';
    
    // Cache to speed up describe information store
    protected static $moduleDescribeCache = array();

    function __construct() {
        global $log, $currentModule;

        $this->db = PearDatabase::getInstance();
        $this->log = $log;
    }
    
    /**
     * Invoked when special actions are performed on the module.
     * @param String Module name
     * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
     */
    function modlib_handler($modulename, $event_type) {  
    global $adb;        
        if($event_type == 'module.postinstall') {
            $this->addCustomLink();
        } else if($event_type == 'module.disabled') {
            $adb->pquery("UPDATE jo_settings_field set active=? where name=?",array(1,'ExtensionStore'));
        } else if($event_type == 'module.enabled') {
            $this->addCustomLink();
            $adb->pquery("UPDATE jo_settings_field set active=? where name=?",array(0,'ExtensionStore'));
        } else if($event_type == 'module.preuninstall') {
        } else if($event_type == 'module.preupdate') {
            $this->addCustomLink();
        } else if($event_type == 'module.postupdate') {
            $this->addCustomLink();     
        }
    }
    
    function addCustomLink(){
        $image = 'joicon-inventory';
        $description = 'ExtensionStore';
        $linkto = 'ExtensionStore/Settings/ExtensionStore';
        
        $result1=$this->db->pquery('SELECT 1 FROM jo_settings_field WHERE name=?',array($this->LBL_MODULE_NAME_OLD));
        if($this->db->num_rows($result1)){
            $this->db->pquery('UPDATE jo_settings_field SET name=?, iconpath=?, description=?, linkto=? WHERE name=?',array($this->LBL_MODULE_NAME, $image, $description, $linkto, $this->LBL_MODULE_NAME_OLD));
        }   
        
        $result2=$this->db->pquery('SELECT 1 FROM jo_settings_field WHERE name=?',array($this->LBL_MODULE_NAME));
        if(!$this->db->num_rows($result2)){

            $fieldid = $this->db->getUniqueID('jo_settings_field');
            $blockid = getSettingsBlockId('LBL_MARKETPLACE');
            $seq_res = $this->db->pquery("SELECT max(sequence) AS max_seq FROM jo_settings_field WHERE blockid = ?", array($blockid));
            if ($this->db->num_rows($seq_res) > 0) {
                    $cur_seq = $this->db->query_result($seq_res, 0, 'max_seq');
                    if ($cur_seq != null)   $seq = $cur_seq + 1;
            }

            $this->db->pquery('INSERT INTO jo_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence) VALUES (?,?,?,?,?,?,?)', array($fieldid, $blockid, $this->LBL_MODULE_NAME , 'joicon-inventory', $description, $linkto, $seq));
        }      
    }

}

?>
