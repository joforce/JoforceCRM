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

include_once dirname(__FILE__).'/RestClient.php';

/**
 * Provides API to work with HTTP Connection.
 * @package modlib
 */
class Settings_ExtensionStore_NetClient {

	protected $client;
	protected $url;
	protected $response;
	protected $headers = array();

	/**
	 * Constructor
	 * @param String URL of the site
	 * Example: 
	 * $client = new Head_New_Client('http://www.vtiger.com');
	 */
	function __construct($url) {
		$this->url = $url;
		$this->client = new Settings_ExtensionStore_RestClient();
		$this->response = false;
		$this->setDefaultHeaders();
	}

	function setDefaultHeaders() {
		$headers = array();
		if (isset($_SERVER)) {
			global $site_URL;
			$headers['referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ($site_URL."?noreferer");

			if (isset($_SERVER['HTTP_USER_AGENT'])) {
				$headers['user-agent'] = $_SERVER['HTTP_USER_AGENT'];
			}
		} else {
			global $site_URL;
			$headers['referer'] = ($site_URL."?noreferer");
		}

		$this->headers = $headers;
	}

	function setAuthorization($username, $password) {
		$this->client->setBasicAuthentication($username, $password);
	}

	/**
	 * Set custom HTTP Headers
	 * @param Map HTTP Header and Value Pairs
	 */
	function setHeaders($headers) {
		$this->client->buildCurlOptions($headers, array());
	}

	/**
	 * Perform a GET request
	 * @param Map key-value pair or false
	 * @param Integer timeout value
	 */
	function doGet($params = false) {
		$response = $this->client->get($this->url, $params, $this->headers);
		return $response;
	}

	/**
	 * Perform a POST request
	 * @param Map key-value pair or false
	 * @param Integer timeout value
	 */
	function doPost($params = false) {
		$response = $this->client->post($this->url, $params, $this->headers);
		return $response;
	}

	/**
	 * Perform a PUT request
	 * @param Map key-value pair or false
	 * @param Integer timeout value
	 */
	function doPut($params = false) {
		$response = $this->client->put($this->url, $params, $this->headers);
		return $response;
	}

}

?>