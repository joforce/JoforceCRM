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
ini_set("auto_detect_line_endings", true);

class Import_CSVReader_Reader extends Import_FileReader_Reader {

	public function arrayCombine($key, $value) { 
		$combine = array(); 
		$dup = array(); 
		for($i=0;$i<count($key);$i++) { 
			if(array_key_exists($key[$i], $combine)){ 
				if(!$dup[$key[$i]]) $dup[$key[$i]] = 1;
				$key[$i] = $key[$i]."(".++$dup[$key[$i]].")";
			} 
			$combine[$key[$i]] = $value[$i]; 
		} 
		return $combine; 
	}

	public function getFirstRowData($hasHeader=true) {
		global $default_charset;

		$fileHandler = $this->getFileHandler();

		$headers = array();
		$firstRowData = array();
		$currentRow = 0;
		while($data = fgetcsv($fileHandler, 0, $this->request->get('delimiter'), "\"", "\"")) {
			if($currentRow == 0 || ($currentRow == 1 && $hasHeader)) {
				if($hasHeader && $currentRow == 0) {
					foreach($data as $key => $value) {
						$headers[$key] = trim($this->convertCharacterEncoding(strip_tags(decode_html($value)), $this->request->get('file_encoding'), $default_charset));
					}
				} else {
					foreach($data as $key => $value) {
						$firstRowData[$key] = trim($this->convertCharacterEncoding(strip_tags(decode_html($value)), $this->request->get('file_encoding'), $default_charset));
					}
					break;
				}
			}
			$currentRow++;
		}

		if($hasHeader) {
			$noOfHeaders = count($headers);
			$noOfFirstRowData = count($firstRowData);
			// Adjust first row data to get in sync with the number of headers
			if($noOfHeaders > $noOfFirstRowData) {
				$firstRowData = array_merge($firstRowData, array_fill($noOfFirstRowData, $noOfHeaders-$noOfFirstRowData, ''));
			} elseif($noOfHeaders < $noOfFirstRowData) {
				$firstRowData = array_slice($firstRowData, 0, count($headers), true);
			}
			$rowData = $this->arrayCombine($headers, $firstRowData);
		} else {
			$rowData = $firstRowData;
		}

		unset($fileHandler);
		return $rowData;
	}

	public function read() {
		global $default_charset;
		$filePath = $this->getFilePath();

		// if file encoded type is other than over default database charset we need to convert
		if($this->request->get('file_encoding') != $default_charset) {
			$data = file_get_contents($filePath);
			$result =  mb_convert_encoding($data,$default_charset,$this->request->get('file_encoding'));
			file_put_contents($filePath, $result);
		}
		// to add escape slashes
		$filePath = addslashes($this->getFilePath());
		$status = $this->createTable();
		if(!$status) {
			return false;
		}

		$fieldMapping = $this->request->get('field_mapping');
		$fieldNames = array();
		foreach($fieldMapping as $fieldName => $index) {
			$fieldNames[$index] = $fieldName;
		}
		$this->addRecordsToDB($filePath,$fieldNames);
	}

	public function addRecordsToDB($filePath,$columnNames) {
		$db = PearDatabase::getInstance();
		$tableName = Import_Utils_Helper::getDbTableName($this->user);
		$delimiter = $this->request->get('delimiter');
		$query = 'LOAD DATA LOCAL INFILE "'.$filePath.'" INTO TABLE '.$tableName.' FIELDS TERMINATED BY "'.$delimiter.'" OPTIONALLY ENCLOSED BY "\"" LINES TERMINATED BY "\n"';
		if($this->hasHeader()){
			$query .= " IGNORE 1 LINES ";
		}

		// to ignore values from file which are not mapped
		$keys = array_keys($columnNames);
		$maxValue = max($keys);
		for($i=0;$i<$maxValue;$i++){
			if(!$columnNames[$i]){
				$columnNames[$i] = "@ignore";
			}
		}
		ksort($columnNames);
		$query .= '('.implode(',',$columnNames).')';

		global $dbconfigoption; 
		$db->database = null; // we shouldn't use existing connection with client flag = 0
		$dbconfigoption['clientFlags'] = 128; // To enable LOAD DATA INFILE... query for database
		$db->pquery($query,array());
		$this->setNumberOfRecordsRead($tableName,$db);
	}
}
?>
