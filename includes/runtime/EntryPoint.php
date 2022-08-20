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

vimport ('includes.exceptions.AppException');

vimport ('includes.http.Request');
vimport ('includes.http.Response');
vimport ('includes.http.Session');

vimport ('includes.runtime.Globals');
vimport ('includes.runtime.Controller');
vimport ('includes.runtime.Viewer');
vimport ('includes.runtime.Theme');
vimport ('includes.runtime.BaseModel');
vimport ('includes.runtime.JavaScript');

vimport ('includes.runtime.LanguageHandler');
vimport ('includes.runtime.Cache');
vimport ('modlib.Head.Runtime');


$site_url=explode("migration/",$_SERVER['REQUEST_URI']);
 if (!isset($_GET['reload'])) {
	 if($_SERVER['REQUEST_URI'] === $site_url[0].$site_url[1].'migration/') {
		 header('Refresh:0');
	 }
	 
}
abstract class Head_EntryPoint {

	/**
	 * Login data
	 */
	protected $login = false;

	/**
	 * Get login data.
	 */
	function getLogin() {
		return $this->login;
	}

	/**
	 * Set login data.
	 */
	function setLogin($login) {
		if ($this->login) throw new AppException('Login is already set.');
		$this->login = $login;
	}

	/**
	 * Check if login data is present.
	 */
	function hasLogin() {
		return $this->getLogin()? true: false;
	}

	abstract function process (Head_Request $request);

}