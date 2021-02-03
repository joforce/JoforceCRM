<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Class Settings_ModuleManager_LoaderSuggest {

	function jo_extensionloader_suggest() {
		$PHPVER = sprintf("%s.%s", PHP_MAJOR_VERSION, PHP_MINOR_VERSION);
		$OSHWINFO = str_replace('Darwin', 'Mac', PHP_OS).'_'.php_uname('m');

		$WIN = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? true : false;

		$EXTFNAME = 'vtigerextn_loader';
		$EXTNFILE = $EXTFNAME.($WIN ? '.dll' : '.so');

		$DISTFILE = sprintf("%s_%s_%s.so", $EXTFNAME, $PHPVER, $OSHWINFO);
		$DISTFILEZIP = sprintf("%s_%s_%s-yyyymmdd.zip", $EXTFNAME, $PHPVER, $OSHWINFO);

		return array(
			'loader_zip' => $DISTFILEZIP,
			'loader_file' => $DISTFILE,
			'php_ini' => php_ini_loaded_file(),
			'extensions_dir' => ini_get('extension_dir')
		);
	}
}
