<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Emails_List_View extends Head_List_View {

        public function preProcess(Head_Request $request) {
        }

        public function process(Head_Request $request) {
                global $site_URL;
                header("Location: ".$site_URL."EmailPlus/view/List");
        }
}
