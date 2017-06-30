<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class Settings_Vtiger_License_View extends Settings_Vtiger_Index_View
{

    public function process(Vtiger_Request $request)
    {
        $module = $request->getModule(false);
        $viewer = $this->getViewer($request);
        $viewer->view('License.tpl', $module);
    }
}

