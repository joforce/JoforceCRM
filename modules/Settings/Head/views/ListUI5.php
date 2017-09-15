<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Settings_Head_ListUI5_View extends Settings_Head_UI5Embed_View {
	
	protected function getUI5EmbedURL(Head_Request $request) {
        $module = $request->getModule();
        if($module == 'EmailTemplate') {
            return 'index.php?module=Settings&action=listemailtemplates&parenttab=Settings';
        } else if($module == 'PDFMaker') {
            return 'index.php?module=PDFMaker&action=index&parenttab=Settings';
        }
	}
}
