<?php
class Potentials_UpdateStage_Action extends Head_Save_Action {

        public function process(Head_Request $request) {
		global $adb;
		$recordId = $request->get('recordId');
		$moduleName = $request->getModule();
		$moduleModel = Head_Module_Model::getInstance($moduleName);
                $recordModel = Head_Record_Model::getInstanceById($recordId, $moduleName);
		$stages_info = getSalesStageArray('picklist');
		$type = $request->get('type');

		if($type == 'update'){
			$moduleName = $request->getModule();
			$stageId = $request->get('stage_id');

		        $recordModel->set('id', $recordId);
		        $recordModel->set('mode', 'edit');

		        $fieldModelList = $moduleModel->getFields();
		        $sales_stage = getStageName($stageId);
		        $recordModel->set('sales_stage', $sales_stage);
		        $recordModel->save();
		}
		elseif($type == 'onload'){
			$sales_stage = $recordModel->get('sales_stage');

			$stageId = array_search ($sales_stage, $stages_info);
		}
		$stage_seqnence_array = getStageSequenceArray();
		$current_stage_name = getStageName($stageId); 
		$current_stage_sequence = $stage_seqnence_array[$stageId]; 

		foreach($stages_info as $id => $stage_name) {
			
			if($id == $stageId && $stage_seqnence_array[$id] == $current_stage_sequence)
			{
				$html .= '<li id='.$id.' class="active"><a href="#" data-toggle="tab">'.$stage_name.'</a></li>';
			}
			elseif($stage_seqnence_array[$id] < $current_stage_sequence)
			{
				$html .= '<li id='.$id.' class="completed"><a href="#" data-toggle="tab">'.$stage_name.'</a></li>';
			}
			elseif($stage_seqnence_array[$id] > $current_stage_sequence)
			{
				$html .= '<li id='.$id.'><a href="#" data-toggle="tab">'.$stage_name.'</a></li>';
			}
		}

		$response = new Head_Response();
		$response->setEmitType(Head_Response::$EMIT_JSON);
		$response->setResult($html);
		$response->emit();
	}
}

?>
