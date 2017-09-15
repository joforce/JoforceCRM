<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * *********************************************************************************** */

/**
 * Head Edit View Record Structure Model
 */
class Reports_RecordStructure_Model extends Head_RecordStructure_Model {

	/**
	 * Function to get the values in stuctured format
	 * @return <array> - values in structure array('block'=>array(fieldinfo));
	 */
	public function getStructure($moduleName) {
		if (!empty($this->structuredValues[$moduleName])) {
			return $this->structuredValues[$moduleName];
		}
		$moduleModel = Head_Module_Model::getInstance($moduleName);
		if ($moduleName === 'Emails') {
			$restrictedTablesList = array('jo_emaildetails', 'jo_attachments');
			$moduleRecordStructure = array();
			$blockModelList = $moduleModel->getBlocks();
			foreach ($blockModelList as $blockLabel => $blockModel) {
				$fieldModelList = $blockModel->getFields();
				if (!empty($fieldModelList)) {
					$moduleRecordStructure[$blockLabel] = array();
					foreach ($fieldModelList as $fieldName => $fieldModel) {
						if($fieldModel->get('table')=='jo_activity' && $this->getRecord()->getPrimaryModule()!='Emails'){
							$fieldModel->set('table','jo_activityEmails');
						}
						if (!in_array($fieldModel->get('table'), $restrictedTablesList) && $fieldModel->isViewable()) {
							$moduleRecordStructure[$blockLabel][$fieldName] = $fieldModel;
						}
					}
				}
			}
		} else if($moduleName === 'Calendar') { 
			$recordStructureInstance = Head_RecordStructure_Model::getInstanceForModule($moduleModel);
			$moduleRecordStructure = array();
			$calendarRecordStructure = $recordStructureInstance->getStructure();
			
			$eventsModel = Head_Module_Model::getInstance('Events');
			$recordStructureInstance = Head_RecordStructure_Model::getInstanceForModule($eventsModel);
			$eventRecordStructure = $recordStructureInstance->getStructure();

			foreach($eventRecordStructure as $blockLabel =>$blockFields){
				foreach($blockFields as $fieldName=>$fieldModel){
					if($fieldModel->isCustomField()){
						$eventCustomFields[$fieldName] = $fieldModel;
					}
				}
			}

			$blockLabel = 'LBL_CUSTOM_INFORMATION';
			if($eventCustomFields) {
				if($calendarRecordStructure[$blockLabel]) {
					$calendarRecordStructure[$blockLabel] = array_merge($calendarRecordStructure[$blockLabel],$eventCustomFields);
				} else {
					$calendarRecordStructure[$blockLabel] = $eventCustomFields;
				}
			}
			$moduleRecordStructure = $calendarRecordStructure;
		} else {
			$recordStructureInstance = Head_RecordStructure_Model::getInstanceForModule($moduleModel);
			$moduleRecordStructure = $recordStructureInstance->getStructure();
		}
		//To remove starred and tag fields 
		foreach($moduleRecordStructure as $blockLabel => $blockFields) {
			foreach($blockFields as $fieldName => $fieldModel) {
				if($fieldModel->getDisplayType() == '6') {
					unset($moduleRecordStructure[$blockLabel][$fieldName]);
				}
			}
		}
		$this->structuredValues[$moduleName] = $moduleRecordStructure;
		return $moduleRecordStructure;
	}

	/**
	 * Function returns the Primary Module Record Structure
	 * @return <Head_RecordStructure_Model>
	 */
	function getPrimaryModuleRecordStructure() {
		$primaryModule = $this->getRecord()->getPrimaryModule();
		$primaryModuleRecordStructure = $this->getStructure($primaryModule);
		return $primaryModuleRecordStructure;
	}

	/**
	 * Function returns the Secondary Modules Record Structure
	 * @return <Array of Head_RecordSructure_Models>
	 */
	function getSecondaryModuleRecordStructure() {
		$recordStructureInstances = array();

		$secondaryModule = $this->getRecord()->getSecondaryModules();
		if (!empty($secondaryModule)) {
			$moduleList = explode(':', $secondaryModule);

			foreach ($moduleList as $moduleName) {
				if (!empty($moduleName)) {
					$recordStructureInstances[$moduleName] = $this->getStructure($moduleName);
				}
			}
		}
		return $recordStructureInstances;
	}

}

?>
