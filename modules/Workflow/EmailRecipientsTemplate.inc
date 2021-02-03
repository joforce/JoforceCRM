<?php
/*+*******************************************************************************
 *  The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *
 *********************************************************************************/

require_once 'modules/Workflow/SimpleTemplate.inc';

/**
 * Description of EmailRecipientsTemplate
 *
 * @author MAK
 */
class EmailRecipientsTemplate extends SimpleTemplate {

	public function __construct($templateString) {
		parent::__construct($templateString);
	}

	protected function useValue($data, $fieldname) {
		return $data['emailoptout'] == 0;
	}
}
?>