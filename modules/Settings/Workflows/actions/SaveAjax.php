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

class Settings_Workflows_SaveAjax_Action extends Settings_Head_IndexAjax_View {

   public function process(Head_Request $request) {
      $record = $request->get('record');
      $status = $request->get('status');
      
      if($record){
         if($status == 'off')
            $status = 0;
         else if($status == 'on')
            $status = 1;
         Settings_Workflows_Record_Model::updateWorkflowStatus($record, $status);
      }
      
      $response = new Head_Response();
      $response->setResult(array('success'));
      $response->emit();
   }

   public function validateRequest(Head_Request $request) {
      $request->validateWriteAccess();
   }

}
