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

class Settings_Google_SaveGoogleSettings_View extends Settings_Head_Index_View
{
    public function process(Head_Request $request) {
        global $site_URL;
        $client_id = $request->get('client-id');
        $client_secret = $request->get('client-secret');
        $block_id = $request->get('block');
        $field_id = $request->get('fieldid');
        $file_content = "<?php

				/* 
				 * Google app informations 
				 */

				Class Google_Config_Connector {
				    static ".'$clientId'." = '$client_id';
				    static ".'$clientSecret'." = '$client_secret';
				}
				?>";
        $myfile = fopen("modules/Google/connectors/Config.php", "w") or die("Unable to open file!");
        fwrite($myfile, $file_content);
        fclose($myfile);
        header ('Location: '.$site_URL.'Google/Settings/GoogleSettings/'.$block_id.'/'.$field_id.'/success');
        die;
    }
}
