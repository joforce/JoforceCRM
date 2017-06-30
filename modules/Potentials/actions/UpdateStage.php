<?php
class Potentials_UpdateStage_Action extends Vtiger_Save_Action {

        public function process(Vtiger_Request $request) {
		global $adb;
		$recordId = $request->get('recordId');
		$stages_info = array('Prospecting', 'Qualification', 'Needs Analysis', 'Value Proposition', 'Id. Decision Makers', 'Perception Analysis', 'Proposal or Price Quote', 'Negotiation or Review', 'Closed Won', 'Closed Lost');
		$type = $request->get('type');
		if($type == 'update'){
			$stageId = $request->get('stage_id');
			$sales_stage = $stages_info[$stageId - 1];
			$adb->pquery('update vtiger_potential set sales_stage = ? where potentialid = ?', array($sales_stage, $recordId));
		}
		elseif($type == 'onload'){
			$getSalesStage = $adb->pquery('select sales_stage from vtiger_potential where potentialid = ?', array($recordId));
			$sales_stage = $adb->query_result($getSalesStage, 0, 'sales_stage');

			$key = array_search ($sales_stage, $stages_info);
			$stageId = $key + 1;
		}

		for($i=0;$i<=9;$i++){
			$id = $i+1;
			if($stageId == 9 && $i == 8){
				$html .= '<li id='.$id.' class="completed"><a href="#" data-toggle="tab">'.$stages_info[$i].'</a></li>';
			}
			elseif($stageId == 10 && $i == 8){
				$html .= '<li id=9 class=""><a href="#" data-toggle="tab">'.$stages_info[8].'</a></li>';
				$html .= '<li id=10 class="lost"><a href="#" data-toggle="tab">'.$stages_info[9].'</a></li>';
				break;
			}
			elseif($i == ($stageId - 1) && $stageId != 9){
				$html .= '<li id='.$id.' class="active"><a href="#" data-toggle="tab">'.$stages_info[$i].'</a></li>';
			}
			else if($i<=$stageId - 1){
				$html .= '<li id='.$id.' class="completed"><a href="#" data-toggle="tab">'.$stages_info[$i].'</a></li>';
			}
			else
				$html .= '<li id='.$id.'><a href="#" data-toggle="tab">'.$stages_info[$i].'</a></li>';

		}
		$response = new Vtiger_Response();
		$response->setEmitType(Vtiger_Response::$EMIT_JSON);
		$response->setResult($html);
		$response->emit();

	}

}

?>
