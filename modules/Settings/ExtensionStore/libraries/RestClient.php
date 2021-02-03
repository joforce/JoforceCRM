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

class Settings_ExtensionStore_RestClient {

	protected static $name = 'ExtensionStoreRestClient';
	protected static $version = '1.0';
	protected $defaultHeaders = array();
	protected $defaultOptions = array();

	public function __construct() {
		global $site_URL, $jo_current_version;

		$this->defaultOptions[CURLOPT_REFERER] = $site_URL;
		$this->defaultOptions[CURLOPT_USERAGENT] = self::$name.'/'.self::$version.'(CRM '.$jo_current_version.')';
		$this->defaultOptions[CURLOPT_RETURNTRANSFER] = true;
		$this->defaultOptions[CURLOPT_FOLLOWLOCATION] = true;
		$this->defaultOptions[CURLOPT_MAXREDIRS] = 5;
		$this->defaultOptions[CURLOPT_SSL_VERIFYPEER] = 0;
		$this->defaultOptions[CURLOPT_SSL_VERIFYHOST] = 0;
		$this->defaultOptions[CURLOPT_TIMEOUT] = 30;

		$this->defaultHeaders['Cache-Control'] = 'no-cache';
		$this->defaultHeaders['Content-Type'] = 'text/xml';
	}

	public function setDefaultOption($option, $value) {
		$this->defaultOptions[$option] = $value;
		return $this;
	}

	public function setDefaultHeader($header, $value) {
		$this->defaultHeaders[$header] = $value;
		return $this;
	}

	public function setBasicAuthentication($username, $password) {
		$this->defaultHeaders['Authorization'] = 'Basic '.base64_encode($username.':'.$password);
	}

	protected function exec($curlopts) {
		$curl = curl_init();
		foreach ($curlopts as $option => $value) {
			if ($option) {				
				curl_setopt($curl, $option, $value);
			}
		}
		$output = curl_exec($curl);
		$ob= simplexml_load_string($output);
		
		$array = get_object_vars($ob);
		$result = $array['row'];
		$result1 = array('success'=>true,'result'=> $result);
		$response  = json_encode($result1);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$responseData = array('response' => $response, 'status' => $status);
		if (curl_errno($curl)) {
			$errorMessage = curl_error($curl);
			$responseData['errorMessage'] = $errorMessage;
		}		
		curl_close($curl);

		return $responseData;
	}

	protected function buildCurlOptions(array $headers, array $options) {
		foreach ($this->defaultOptions as $option => $value) {
			switch ($option) {
				// Stop overrides on some keys.
				case CURLOPT_REFERER:
				case CURLOPT_USERAGENT:
					$options[$option] = $value;
					break;
				default:
					// Pickup the overriding value
					if (!isset($options[$option])) {
						$options[$option] = $value;
					}
					break;
			}
		}

		$headeropts = array();
		foreach ($this->defaultHeaders as $key => $value) {
			// Respect the overriding value
			if ($headers && isset($headers[$key]))
				continue;
			$headeropts[] = ($key.': '.$value);
		}
		foreach ($headers as $key => $value)
			$headeropts[] = ($key.': '.$value);
		$options[CURLOPT_HTTPHEADER] = $headeropts;

		return $options;
	}

	public function get($url, $params = array(), $headers = array(), $options = array()) {
		$url = 'https://www.joforce.com/marketplace/joforce-products-feed.xml';
		$curlopts = $this->buildCurlOptions($headers, $options);

		$curlopts[CURLOPT_HTTPGET] = true;

		if (!empty($params)) {
			if (stripos($url, '?') === false)
				$url .= '?';
			else
				$url .= '&';
			$url .= http_build_query($params, '', '&');
		}

		$curlopts[CURLOPT_URL] = $url;
		return $this->exec($curlopts);
	}

	public function post($url, $params = array(), $headers = array(), $options = array()) {
		$curlopts = $this->buildCurlOptions($headers, $options);

		$curlopts[CURLOPT_POST] = true;
		if ($params) {
			$curlopts[CURLOPT_POSTFIELDS] = http_build_query($params, '', '&');
		}

		$curlopts[CURLOPT_URL] = $url;
		return $this->exec($curlopts);
	}

	public function put($url, $params = array(), $headers = array(), $options = array()) {
		$curlopts = $this->buildCurlOptions($headers, $options);

		$curlopts[CURLOPT_CUSTOMREQUEST] = 'PUT';
		if ($params) {
			$curlopts[CURLOPT_POSTFIELDS] = http_build_query($params, '', '&');
		}

		$curlopts[CURLOPT_URL] = $url;
		return $this->exec($curlopts);
	}

}
