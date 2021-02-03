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

require_once("includes/Webservices/QL_Lexer.php");
require_once("includes/Webservices/QL_Parser.php");

class Parser
{

	private $query = "";
	private $out;
	private $meta;
	private $hasError;
	private $error;
	private $user;
	function Parser($user, $q)
	{
		$this->query = $q;
		$this->out = array();
		$this->hasError = false;
		$this->user = $user;
	}

	function parse()
	{

		$lex = new QL_Lexer($this->query);
		$parser = new QL_Parser($this->user, $lex, $this->out);
		while ($lex->yylex()) {
			$parser->doParse($lex->token, $lex->value);
		}
		$parser->doParse(0, 0);

		if ($parser->isSuccess()) {
			$this->hasError = false;
			$this->query = $parser->getQuery();
			$this->meta = $parser->getObjectMetaData();
		} else {
			$this->hasError = true;
			$this->error = $parser->getErrorMsg();
		}

		return $this->hasError;
	}

	function getSql()
	{
		return $this->query;
	}

	function getObjectMetaData()
	{
		return $this->meta;
	}

	function getError()
	{
		return $this->error;
	}
}
