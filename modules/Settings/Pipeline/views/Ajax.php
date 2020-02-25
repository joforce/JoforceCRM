<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Settings_Pipeline_Ajax_View extends Settings_Head_Index_View {
	
    public function process(Head_Request $request) {
	global $adb, $current_user;
	$model = new Settings_Pipeline_Module_Model();
	$mode = $request->get('mode');
	$moduleName = $request->getModule();
        $qualifiedModuleName = $request->getModule(false);
	$user_id = $current_user->id;

	$response = new Head_Response();
	if($mode == 'getpicklist') {
	    $selected_module = $request->get('moduleName');
	    $html = $model->getPicklistOfModule($selected_module);

	    $sourceModuleModel = Head_Module_Model::getInstance($selected_module);
	    $fields = $model->getModuleFieldsWithoutNameFields($selected_module);
	    $fields_html = $this->getFieldsHTML($fields, $selected_module);
 
            $response->setResult(array('success' => true, 'picklists'=> $html, 'fields' => $fields_html));
	} 
	$response->emit();
    }

    public function getFieldsHTML($fields, $sel_module) {
	$fields_html = '';

	foreach($fields as $fieldname => $fieldmodel) {
	    $fields_html .= "<option class='role2fieldnames_{$fieldname}' value='{$fieldname}'>";
            $fields_html .= vtranslate($fieldmodel->label,$sel_module);
            $fields_html .= "</option>";
	}
	return $fields_html;
    }
}
