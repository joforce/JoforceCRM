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

class Settings_Pipeline_Module_Model extends Settings_Head_Module_Model {

    var $name = 'Pipeline';

    /**
     * Function to get picklist field label
     **/
    public static function getPicklistFieldLabel($picklistname, $moduleid){
	global $adb;
	$query_result = $adb->pquery('SELECT * FROM jo_field where fieldname = ? and tabid = ?', array($picklistname, $moduleid));
	$result = $adb->fetch_array($query_result);
	return $result['fieldlabel'];
    }

    public function getPicklistOfModule($selected_module) {
        $picklist_fields = $this->getPicklists($selected_module);
        $html = '<select class="select2 inputElement" name="pipe-picklists" value="" id="pipe-picklists">';
        $html .='<option>'.vtranslate('LBL_SELECT').'</option>';

	if(count($picklist_fields) > 0) {
            foreach($picklist_fields as $picklist_name => $picklist_label) {
		$html .= "<option value=$picklist_name>$picklist_label</option>";
            }
	}
        $html .= '</select>';
	return $html;
    }

    public function getPicklists($selected_module) {
        require_once ('modules/Settings/PickListDependency/models/Record.php');
        $pd_obj = new Settings_PickListDependency_Record_Model(array('sourceModule' => $selected_module));
        $picklist_fields = $pd_obj->getAllPickListFields();
        return $picklist_fields;
    }

    public function getPipelineDetails($pipeline_id = false, $module = false) {
	global $adb;
	if(isset($module) && !empty($module)) {
	    $pipeline_query = $adb->pquery("select * from jo_visualpipeline where tabname = ?", array($module));
	} else {
	    $pipeline_query = $adb->pquery("select * from jo_visualpipeline where pipeline_id = ?", array($pipeline_id));
	}
        $pipeline_info = $adb->fetch_array($pipeline_query);
	return $pipeline_info;
    }

    public function SavePipeline($request) {
	global $adb, $site_URL;
	$sel_moduleName = $request->get('kanban-module');
	$picklistName = $request->get('pipe-picklists');
	$pipelineid = $request->get('pipelineid');
	$no_of_records = $request->get('records_per_page');
	$fieldnames = json_encode($request->get('role2fieldnames'));

	$tabid = getTabid($sel_moduleName);
	if(!empty($pipelineid)) {
	    $result = $adb->pquery('update jo_visualpipeline set tabid = ?, tabname = ? , picklist_name = ?,records_per_page =? , selected_fields =? where pipeline_id = ? ' ,array($tabid, $sel_moduleName, $picklistName, $no_of_records, $fieldnames, $pipelineid));
	} else {
	    $picklistid = $adb->getUniqueId("jo_visualpipeline");
	    $result = $adb->pquery('INSERT INTO jo_visualpipeline values(?,?,?,?,?,?)', array($picklistid, $tabid, $sel_moduleName, $picklistName,$no_of_records,$fieldnames));
	}
	header('Location: ' . $site_URL . 'Pipeline/Settings/Index');
    }

    public function deletePipeline($pipeline_id) {
	global $adb;
        $pipeline_query = $adb->pquery("delete from jo_visualpipeline where pipeline_id = ?", array($pipeline_id));
	return $pipeline_query;
    }

    public function getModuleList() {
	$allModelsList = Head_Menu_Model::getAll(true);
	$entity_modules = array();
	$pipeine_modules = $this->getPipelineEnabledModules();

        foreach($allModelsList as $modulename => $moduleModal) {
	    if(in_array($modulename, $pipeine_modules)) {
		continue;
	    }

	    $entity_type = $moduleModal->isentitytype;
            if(!empty($entity_type) && isset($entity_type)) {
                $entity_modules[$modulename] = $moduleModal->label;
            }
        }
	return $entity_modules;
    }

    public function getPipelineEnabledModules() {
	global $adb;
        $pipeline_query = $adb->pquery("select tabname from jo_visualpipeline");
	$count = $adb->getRowCount($pipeline_query);
	$modules = array();

	if($count > 0) {
	    while($result = $adb->fetch_array($pipeline_query)) {
		array_push($modules , $result['tabname']);
	    }
	}
        return $modules;
    }

    public function getRecordsPerPage($tabid) {
        global $adb;
        $query = $adb->pquery("select records_per_page from jo_visualpipeline where tabid = ?", array($tabid));
        $count = $adb->getRowCount($query);

        $records_per_page = 100;
        if($count > 0) {
            $result = $adb->fetch_array($query);
            $records_per_page = $result['records_per_page'];
        }
        return $records_per_page;
    }

    public function getModuleFieldsWithoutNameFields($selected_module) {
	$sourceModuleModel = Head_Module_Model::getInstance($selected_module);
	$fields = $sourceModuleModel->getFields();
	$name_fields = $sourceModuleModel->getNameFields();

	foreach($name_fields as $nameField) {
	    unset($fields[$nameField]);
	}
	return $fields;
    }

    public function getModuleFieldsWithNameFields($selected_module) {
        $sourceModuleModel = Head_Module_Model::getInstance($selected_module);
        $fields = $sourceModuleModel->getFields();
        return $fields;
        }
}
