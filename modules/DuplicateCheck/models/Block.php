<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class DuplicateCheck_Block_Model extends Head_Block_Model {

    public function isActionsAllowed () {
        $actionNotSupportedModules = array('calendar','events');
        if(in_array(strtolower($this->module->name), $actionNotSupportedModules)) {
			return false;
		}
		return true;
	}

    /**
	 * Function to check whether adding custom field is allowed or not
	 * @return <Boolean> true/false
	 */
	public function isAddCustomFieldEnabled() {
        $actionNotSupportedModules = array('calendar','events','faq', 'helpdesk');
		$blocksEliminatedArray = array('calendar' => array('LBL_TASK_INFORMATION', 'LBL_DESCRIPTION_INFORMATION'),
									'helpdesk' =>  array('LBL_TICKET_RESOLUTION', 'LBL_COMMENTS'),
                                                                   'faq'=>array('LBL_COMMENT_INFORMATION'),
                                    'events' => array('LBL_EVENT_INFORMATION','LBL_REMINDER_INFORMATION','LBL_DESCRIPTION_INFORMATION',
                                                      'LBL_RECURRENCE_INFORMATION','LBL_RELATED_TO','LBL_INVITE_USER_BLOCK'));
        if(in_array(strtolower($this->module->name), $actionNotSupportedModules)) {
			if(!empty($blocksEliminatedArray[strtolower($this->module->name)])) {
				if(in_array($this->get('label'), $blocksEliminatedArray[strtolower($this->module->name)])) {
					return false;
				}
			} else {
				return false;
			}
		}
        return true;
    }

    public static function updateFieldSequenceNumber($blockFieldSequence) {
        $fieldIdList = array();
        $db = PearDatabase::getInstance();

        $query = 'UPDATE jo_field SET ';
        $query .=' sequence= CASE ';
        foreach($blockFieldSequence as $newFieldSequence ) {
			$fieldId = $newFieldSequence['fieldid'];
			$sequence = $newFieldSequence['sequence'];
			$block = $newFieldSequence['block'];
            $fieldIdList[] = $fieldId;

			$query .= ' WHEN fieldid='.$fieldId.' THEN '.$sequence;
        }

		$query .=' END, block=CASE ';

		foreach($blockFieldSequence as $newFieldSequence ) {
			$fieldId = $newFieldSequence['fieldid'];
			$sequence = $newFieldSequence['sequence'];
			$block = $newFieldSequence['block'];
			$query .= ' WHEN fieldid='.$fieldId.' THEN '.$block;
		}
		$query .=' END ';

        $query .= ' WHERE fieldid IN ('.generateQuestionMarks($fieldIdList).')';

        $db->pquery($query, array($fieldIdList));
    }

    public static function getInstance($value, $moduleInstance = false) {
		$blockInstance = parent::getInstance($value, $moduleInstance);
		$blockModel = self::getInstanceFromBlockObject($blockInstance);
		return $blockModel;
	}

	/**
	 * Function to retrieve block instance from Head_Block object
	 * @param Head_Block $blockObject - modlib block object
	 * @return Head_Block_Model
	 */
	public static function getInstanceFromBlockObject(Head_Block $blockObject) {
		$objectProperties = get_object_vars($blockObject);
		$blockModel = new self();
		foreach($objectProperties as $properName=>$propertyValue) {
			$blockModel->$properName = $propertyValue;
		}
		return $blockModel;
	}

    /**
	 * Function to retrieve block instances for a module
	 * @param <type> $moduleModel - module instance
	 * @return <array> - list of Head_Block_Model
	 */
	public static function getAllForModule($moduleModel) {
		$blockObjects = parent::getAllForModule($moduleModel);
		$blockModelList = array();

		if($blockObjects) {
			foreach($blockObjects as $blockObject) {
				$blockModelList[] = self::getInstanceFromBlockObject($blockObject);
			}
		}
		return $blockModelList;
	}

	public function getLayoutBlockActiveFields() {
		$fields = $this->getFields();
		$activeFields = array();
		foreach($fields as $fieldName => $fieldModel) {
			if($fieldModel->isActiveField()) {
				$activeFields[$fieldName] = $fieldModel;
			}
		}
		return $activeFields;
	}
}
