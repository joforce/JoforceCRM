<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * ***********************************************************************************/

class Settings_Workflows_ValidateExpression_Action extends Settings_Head_Basic_Action {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('ForTaskEdit');
		$this->exposeMethod('ForWorkflowEdit');
	}

	public function process(Head_Request $request) {
		$mode = $request->getMode();
		if (!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	public function ForTaskEdit(Head_Request $request) {
		require_once 'modules/Workflow/expression_engine/include.inc';

		$result = new Head_Response();
		$fieldMapping = Zend_Json::decode($request->getRaw('field_value_mapping'));
		if (empty($fieldMapping)) {
			$fieldMapping = array();
		}
		foreach ($fieldMapping as $key => $mappingInfo) {
			if ($mappingInfo['valuetype'] == 'expression') {
				try {
					$parser = new VTExpressionParser(new VTExpressionSpaceFilter(new VTExpressionTokenizer($mappingInfo['value'])));
					$expression = $parser->expression();
				} catch (Exception $e) {
					$result->setError($mappingInfo);
					$result->emit();
					return;
				}
			}
		}
		$result->setResult(array('success' => true));
		$result->emit();
	}

	public function ForWorkflowEdit(Head_Request $request) {
		require_once 'modules/Workflow/expression_engine/include.inc';

		$result = new Head_Response();

		//For workflows that are created in vtiger5 we are ignoring checking of expression validation
		if ($request->get('filtersavedinnew') != '6') {
			$result->setResult(array('success' => false));
			$result->emit();
			return;
		}

		$conditions = $request->get('conditions');

		foreach ($conditions as $info) {
			foreach ($info['columns'] as $conditionRow) {
				if ($conditionRow['valuetype'] == 'expression') {
					try {
						$parser = new VTExpressionParser(new VTExpressionSpaceFilter(new VTExpressionTokenizer($conditionRow['value'])));
						$expression = $parser->expression();
					} catch (Exception $e) {
						$result->setError($conditionRow);
						$result->emit();
						return;
					}
				}
			}
		}
		$result->setResult(array('success' => true));
		$result->emit();
	}
}
