<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * *********************************************************************************** */

class Settings_Workflows_EditAjax_View extends Settings_Workflows_Edit_View {

   public function preProcess(Head_Request $request) {
      return true;
   }

   public function postProcess(Head_Request $request) {
      return true;
   }

   function __construct() {
      parent::__construct();
      $this->exposeMethod('getWorkflowConditions');
      $this->exposeMethod('getWorkflowAndConditions');
   }

   public function process(Head_Request $request) {
      $mode = $request->get('mode');
      if (!empty($mode)) {
         $this->invokeExposedMethod($mode, $request);
         return;
      }
   }
   function getWorkflowAndConditions(Head_Request $request) {
      $viewer = $this->getViewer($request);
      $moduleName = $request->getModule();
      $qualifiedModuleName = $request->getModule(false);

      $recordId = $request->get('record');

      if ($recordId) {
         $workFlowModel = Settings_Workflows_Record_Model::getInstance($recordId);
         $selectedModule = $workFlowModel->getModule();
         $selectedModuleName = $selectedModule->getName();
      } else {
         $selectedModuleName = $request->get('module_name');
         $selectedModule = Head_Module_Model::getInstance($selectedModuleName);
         $workFlowModel = Settings_Workflows_Record_Model::getCleanInstance($selectedModuleName);
      }
      
      //Added to support advance filters
      $recordStructureInstance = Settings_Workflows_RecordStructure_Model::getInstanceForWorkFlowModule($workFlowModel, Settings_Workflows_RecordStructure_Model::RECORD_STRUCTURE_MODE_FILTER);
      
      $viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
      $recordStructure = $recordStructureInstance->getStructure();
      if (in_array($selectedModuleName, getInventoryModules())) {
         $itemsBlock = "LBL_ITEM_DETAILS";
         unset($recordStructure[$itemsBlock]);
      }
      $viewer->assign('RECORD_STRUCTURE', $recordStructure);

      $viewer->assign('WORKFLOW_MODEL', $workFlowModel);

      $viewer->assign('MODULE_MODEL', $selectedModule);
      $viewer->assign('SELECTED_MODULE_NAME', $selectedModuleName);

      $dateFilters = Head_Field_Model::getDateFilterTypes();
      foreach ($dateFilters as $comparatorKey => $comparatorInfo) {
         $comparatorInfo['startdate'] = DateTimeField::convertToUserFormat($comparatorInfo['startdate']);
         $comparatorInfo['enddate'] = DateTimeField::convertToUserFormat($comparatorInfo['enddate']);
         $comparatorInfo['label'] = vtranslate($comparatorInfo['label'], $qualifiedModuleName);
         $dateFilters[$comparatorKey] = $comparatorInfo;
      }
      $viewer->assign('DATE_FILTERS', $dateFilters);
      $viewer->assign('ADVANCED_FILTER_OPTIONS', Settings_Workflows_Field_Model::getAdvancedFilterOptions());
      $viewer->assign('ADVANCED_FILTER_OPTIONS_BY_TYPE', Settings_Workflows_Field_Model::getAdvancedFilterOpsByFieldType());
      $viewer->assign('COLUMNNAME_API', 'getWorkFlowFilterColumnName');

      $viewer->assign('FIELD_EXPRESSIONS', Settings_Workflows_Module_Model::getExpressions());
      $viewer->assign('META_VARIABLES', Settings_Workflows_Module_Model::getMetaVariables());
      $viewer->assign('ADVANCE_CRITERIA', "");
      $viewer->assign('RECORD', $recordId);
      
      // Added to show filters only when saved from vtiger6
      if ($workFlowModel->isFilterSavedInNew()) {
         $condition_count = $request->get('count');
         $condition = $workFlowModel->transformToAdvancedFilterCondition();
         $and_condition = $condition[2]['columns'][$condition_count-1];
         $construct_array = array();
         $construct_array[1]=$condition[1];
         $construct_array[2]['columns'][0]=$condition[2]['columns'][$condition_count-1];
         $viewer->assign('ADVANCE_CRITERIA', $construct_array);
      }
      
      $viewer->assign('IS_FILTER_SAVED_NEW', $workFlowModel->isFilterSavedInNew());
      $viewer->assign('MODULE', $moduleName);
      $viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);

      $userModel = Users_Record_Model::getCurrentUserModel();
      
      $viewer->assign('DATE_FORMAT', $userModel->get('date_format'));
      $condition = $request->get('condition');
      if(empty($condition)){
            $condition = 'Or';
      }
      $viewer->assign('CONDITION_TYPE', $condition);
      $moduleModel = $workFlowModel->getModule();
      $viewer->assign('TASK_TYPES', Settings_Workflows_TaskType_Model::getAllForModule($moduleModel));
      $viewer->assign('TASK_LIST', $workFlowModel->getTasks());
      $viewer->view('WorkFlowConditions.tpl', $qualifiedModuleName);
   }
   function getWorkflowConditions(Head_Request $request) {
      $viewer = $this->getViewer($request);
      $moduleName = $request->getModule();
      $qualifiedModuleName = $request->getModule(false);

      $recordId = $request->get('record');

      if ($recordId) {
         $workFlowModel = Settings_Workflows_Record_Model::getInstance($recordId);
         $selectedModule = $workFlowModel->getModule();
         $selectedModuleName = $selectedModule->getName();
      } else {
         $selectedModuleName = $request->get('module_name');
         $selectedModule = Head_Module_Model::getInstance($selectedModuleName);
         $workFlowModel = Settings_Workflows_Record_Model::getCleanInstance($selectedModuleName);
      }
      
      //Added to support advance filters
      $recordStructureInstance = Settings_Workflows_RecordStructure_Model::getInstanceForWorkFlowModule($workFlowModel, Settings_Workflows_RecordStructure_Model::RECORD_STRUCTURE_MODE_FILTER);
      
      $viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
      $recordStructure = $recordStructureInstance->getStructure();
      if (in_array($selectedModuleName, getInventoryModules())) {
         $itemsBlock = "LBL_ITEM_DETAILS";
         unset($recordStructure[$itemsBlock]);
      }
      $viewer->assign('RECORD_STRUCTURE', $recordStructure);

      $viewer->assign('WORKFLOW_MODEL', $workFlowModel);

      $viewer->assign('MODULE_MODEL', $selectedModule);
      $viewer->assign('SELECTED_MODULE_NAME', $selectedModuleName);

      $dateFilters = Head_Field_Model::getDateFilterTypes();
      foreach ($dateFilters as $comparatorKey => $comparatorInfo) {
         $comparatorInfo['startdate'] = DateTimeField::convertToUserFormat($comparatorInfo['startdate']);
         $comparatorInfo['enddate'] = DateTimeField::convertToUserFormat($comparatorInfo['enddate']);
         $comparatorInfo['label'] = vtranslate($comparatorInfo['label'], $qualifiedModuleName);
         $dateFilters[$comparatorKey] = $comparatorInfo;
      }
      $viewer->assign('DATE_FILTERS', $dateFilters);
      $viewer->assign('ADVANCED_FILTER_OPTIONS', Settings_Workflows_Field_Model::getAdvancedFilterOptions());
      $viewer->assign('ADVANCED_FILTER_OPTIONS_BY_TYPE', Settings_Workflows_Field_Model::getAdvancedFilterOpsByFieldType());
      $viewer->assign('COLUMNNAME_API', 'getWorkFlowFilterColumnName');

      $viewer->assign('FIELD_EXPRESSIONS', Settings_Workflows_Module_Model::getExpressions());
      $viewer->assign('META_VARIABLES', Settings_Workflows_Module_Model::getMetaVariables());
      $viewer->assign('ADVANCE_CRITERIA', "");
      $viewer->assign('RECORD', $recordId);
      
      // Added to show filters only when saved from vtiger6
      if ($workFlowModel->isFilterSavedInNew()) {
         $viewer->assign('ADVANCE_CRITERIA', $workFlowModel->transformToAdvancedFilterCondition());
      }
      
      $viewer->assign('IS_FILTER_SAVED_NEW', $workFlowModel->isFilterSavedInNew());
      $viewer->assign('MODULE', $moduleName);
      $viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);

      $userModel = Users_Record_Model::getCurrentUserModel();
      
      $viewer->assign('DATE_FORMAT', $userModel->get('date_format'));
      $condition = $request->get('condition');
      if(empty($condition)){
            $condition = 'Or';
      }
      $viewer->assign('CONDITION_TYPE', $condition);
      $moduleModel = $workFlowModel->getModule();
      $viewer->assign('TASK_TYPES', Settings_Workflows_TaskType_Model::getAllForModule($moduleModel));
        //echo'<pre>';print_r($workFlowModel->transformToAdvancedFilterCondition());die('WORKFLOW_MODEL');
      $viewer->assign('TASK_LIST', $workFlowModel->getTasks());
      $viewer->view('WorkFlowConditions.tpl', $qualifiedModuleName);
   }

}
