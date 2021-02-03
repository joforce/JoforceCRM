<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

class Settings_Webhooks_Block_Model extends Head_Block_Model {

	/**
	 * Function to get fields for this block
	 * @return <Array> list of Field models list <Settings_Webforms_Field_Model>
	 */
	public function getFields() {
		if(empty($this->fields)) {
			$tableName = 'jo_webforms';
			$tabId = getTabid('Webforms');
            $blockName = $this->get('name');
            switch ($blockName) {
                case 'LBL_WEBHOOK_INFORMATION' : 
                            $fieldsList = array(
                            'name' => array(
                                    'uitype' => '1',
                                    'name' => 'name',
                                    'label' => 'Webhook Name',
                                    'typeofdata' => 'V~M',
                                    'diplaytype' => '1',
                            ),
                            'targetmodule' => array(
                                    'uitype' => '16',
                                    'name' => 'targetmodule',
                                    'label' => 'Module',
                                    'typeofdata' => 'V~O',
                                    'diplaytype' => '1',
                            ),
                            'url' => array(
                                    'uitype' => '17',
                                    'name' => 'returnurl',
                                    'label' => 'Endpoint Url',
                                    'typeofdata' => 'V~O',
                                    'diplaytype' => '1',
                                    'defaultvalue' => '',
                            ),
                            'ownerid' => array(
                                    'uitype' => '53',
                                    'name' => 'ownerid',
                                    'label' => 'Assigned To',
                                    'typeofdata' => 'V~M',
                                    'diplaytype' => '1',
                            ),
                            'enabled' => array(
                                    'uitype' => '56',
                                    'name' => 'enabled',
                                    'label' => 'Status',
                                    'typeofdata' => 'C~O',
                                    'diplaytype' => '1',
                                    'defaultvalue' => '1',
                            ),
			    'fields' => array(
                                    'uitype' => '33',
                                    'name' => 'fields',
                                    'label' => 'Fields',
                                    'typeofdata' => 'V~O',
                                    'defaultvalue' => '',				
			    ),
                            'description' => array(
                                    'uitype' => '19',
                                    'name' => 'description',
                                    'label' => 'Description',
                                    'typeofdata' => 'V~O',
                                    'defaultvalue' => '',
                            ),
			    'events' => array(
			            'uitype' => '33',
				    'name' => 'events',
				    'label' => 'Events',
				    'typeofdata' => 'V~O',
				    'defaultvalue' => '',	
			    )
                    );
                    break;
                
            }
			
			foreach ($fieldsList as $fieldName => $fieldDetails) {
				$fieldModel = Settings_Webhooks_Field_Model::getInstanceByRow($fieldDetails);
				$fieldModel->block = $this;
				$fieldModel->module = $this->module;
				$fieldModelsList[$fieldName] = $fieldModel;
			}
			$this->fields = $fieldModelsList;
		}

		return $this->fields;
	}

	/**
	 * Function to get list of all blocks for selected module
	 * @param <Settings_Webforms_Module_Model> $moduleModel
	 * @return <Array> list of Block models
	 */
	public static function getAllForModule($moduleModel) {
		$blockLabels = array('LBL_WEBHOOK_INFORMATION');

		foreach ($blockLabels as $blockName) {
			$blockModels[$blockName] = Settings_Webhooks_Block_Model::getInstanceFromName($blockName, $moduleModel);
		}
		return $blockModels;
	}

	/**
	 * Function to get Instance for Block by using name
	 * @param <String> $blockName
	 * @param <Settings_Webhooks_Module_Model> $moduleModel
	 * @return <Settings_Webhooks_Block_Model> BlockModel
	 */
	public static function getInstanceFromName($blockName, $moduleModel) {
		$blockModel = new self();
		$blockModel->name = $blockName;
		$blockModel->blocklabel = $blockName;
		$blockModel->module = $moduleModel;

		return $blockModel;
	}
}
