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

class Products extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name = 'jo_products';
	var $table_index= 'productid';
	var $column_fields = Array();

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('jo_productcf','productid');

	var $tab_name = Array('jo_crmentity','jo_products','jo_productcf');

	var $tab_name_index = Array('jo_crmentity'=>'crmid','jo_products'=>'productid','jo_productcf'=>'productid','jo_seproductsrel'=>'productid','jo_producttaxrel'=>'productid');



	// This is the list of jo_fields that are in the lists.
	var $list_fields = Array(
		'Product Name'=>Array('products'=>'productname'),
		'Part Number'=>Array('products'=>'productcode'),
		'Commission Rate'=>Array('products'=>'commissionrate'),
		'Qty/Unit'=>Array('products'=>'qty_per_unit'),
		'Unit Price'=>Array('products'=>'unit_price')
	);
	var $list_fields_name = Array(
		'Product Name'=>'productname',
		'Part Number'=>'productcode',
		'Commission Rate'=>'commissionrate',
		'Qty/Unit'=>'qty_per_unit',
		'Unit Price'=>'unit_price'
	);

	var $list_link_field= 'productname';

	var $search_fields = Array(
		'Product Name'=>Array('products'=>'productname'),
		'Part Number'=>Array('products'=>'productcode'),
		'Unit Price'=>Array('products'=>'unit_price')
	);
	var $search_fields_name = Array(
		'Product Name'=>'productname',
		'Part Number'=>'productcode',
		'Unit Price'=>'unit_price'
	);

	var $required_fields = Array('productname'=>1);

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();
	var $def_basicsearch_col = 'productname';

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'productname';
	var $default_sort_order = 'ASC';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to jo_field.fieldname values.
	var $mandatory_fields = Array('createdtime', 'modifiedtime', 'productname', 'assigned_user_id');
	 // Josh added for importing and exporting -added in patch2
	var $unit_price;

	/**	Constructor which will set the column_fields in this object
	 */
	function Products() {
		$this->log =LoggerManager::getLogger('product');
		$this->log->debug("Entering Products() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Products');
		$this->log->debug("Exiting Product method ...");
	}

	function save_module($module)
	{
		//Inserting into product_taxrel table
		if($_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'ProcessDuplicates' && !$this->isWorkFlowFieldUpdate)
		{
			if ($_REQUEST['ajxaction'] != 'CurrencyUpdate') {
				$this->insertTaxInformation('jo_producttaxrel', 'Products');
			}

			if ($_REQUEST['action'] != 'MassEditSave' ) {
				$this->insertPriceInformation('jo_productcurrencyrel', 'Products');
			}
		}

		if($_REQUEST['action'] == 'SaveAjax' && isset($_REQUEST['base_currency']) && isset($_REQUEST['unit_price'])){
			$this->insertPriceInformation('jo_productcurrencyrel', 'Products');
		}
		// Update unit price value in jo_productcurrencyrel
		$this->updateUnitPrice();
		//Inserting into attachments, handle image save in crmentity uitype 69
		//$this->insertIntoAttachment($this->id,'Products');

	}

	/**	function to save the product tax information in jo_producttaxrel table
	 *	@param string $tablename - jo_tablename to save the product tax relationship (producttaxrel)
	 *	@param string $module	 - current module name
	 *	$return void
	*/
	function insertTaxInformation($tablename, $module)
	{
		global $adb, $log;
		$log->debug("Entering into insertTaxInformation($tablename, $module) method ...");
		$tax_details = getAllTaxes();

		$tax_per = '';
		//Save the Product - tax relationship if corresponding tax check box is enabled
		//Delete the existing tax if any
		if($this->mode == 'edit' && $_REQUEST['action'] != 'MassEditSave')
		{
			for($i=0;$i<count($tax_details);$i++)
			{
				$taxid = getTaxId($tax_details[$i]['taxname']);
				$sql = "DELETE FROM jo_producttaxrel WHERE productid=? AND taxid=?";
				$adb->pquery($sql, array($this->id,$taxid));
			}
		}
		for($i=0;$i<count($tax_details);$i++)
		{
			$tax_name = $tax_details[$i]['taxname'];
			$tax_checkname = $tax_details[$i]['taxname']."_check";
			if($_REQUEST[$tax_checkname] == 'on' || $_REQUEST[$tax_checkname] == 1)
			{
				$taxid = getTaxId($tax_name);
				$tax_per = $_REQUEST[$tax_name];

				$taxRegions = $_REQUEST[$tax_name.'_regions'];
				if ($taxRegions || $_REQUEST[$tax_name.'_defaultPercentage'] != '') {
					$tax_per = $_REQUEST[$tax_name.'_defaultPercentage'];
				} else {
					$taxRegions = array();
				}

				if($tax_per == '')
				{
					$log->debug("Tax selected but value not given so default value will be saved.");
					$tax_per = getTaxPercentage($tax_name);
				}

				$log->debug("Going to save the Product - $tax_name tax relationship");

				if ($_REQUEST['action'] === 'MassEditSave') {
					$adb->pquery('DELETE FROM jo_producttaxrel WHERE productid=? AND taxid=?', array($this->id, $taxid));
				}

				$query = "INSERT INTO jo_producttaxrel VALUES(?,?,?,?)";
				$adb->pquery($query, array($this->id, $taxid, $tax_per, Zend_Json::encode($taxRegions)));
			}
		}

		$log->debug("Exiting from insertTaxInformation($tablename, $module) method ...");
	}

	/**	function to save the product price information in jo_productcurrencyrel table
	 *	@param string $tablename - jo_tablename to save the product currency relationship (productcurrencyrel)
	 *	@param string $module	 - current module name
	 *	$return void
	*/
	function insertPriceInformation($tablename, $module)
	{
		global $adb, $log, $current_user;
		$log->debug("Entering into insertPriceInformation($tablename, $module) method ...");
		//removed the update of currency_id based on the logged in user's preference : fix 6490

		$currency_details = getAllCurrencies('all');

		//Delete the existing currency relationship if any
		if($this->mode == 'edit' && $_REQUEST['action'] !== 'CurrencyUpdate')
		{
			for($i=0;$i<count($currency_details);$i++)
			{
				$curid = $currency_details[$i]['curid'];
				$sql = "delete from jo_productcurrencyrel where productid=? and currencyid=?";
				$adb->pquery($sql, array($this->id,$curid));
			}
		}

		$product_base_conv_rate = getBaseConversionRateForProduct($this->id, $this->mode);
		$currencySet = 0;
		//Save the Product - Currency relationship if corresponding currency check box is enabled
		for($i=0;$i<count($currency_details);$i++)
		{
			$curid = $currency_details[$i]['curid'];
			$curname = $currency_details[$i]['currencylabel'];
			$cur_checkname = 'cur_' . $curid . '_check';
			$cur_valuename = 'curname' . $curid;

			$requestPrice = CurrencyField::convertToDBFormat($_REQUEST['unit_price'], null, true);
			$actualPrice = CurrencyField::convertToDBFormat($_REQUEST[$cur_valuename], null, true);
			$isQuickCreate = false;
			if($_REQUEST['action']=='SaveAjax' && isset($_REQUEST['base_currency']) && $_REQUEST['base_currency'] == $cur_valuename){
				$actualPrice = $requestPrice;
				$isQuickCreate = true;
			}
			if($_REQUEST[$cur_checkname] == 'on' || $_REQUEST[$cur_checkname] == 1 || $isQuickCreate)
			{
				$conversion_rate = $currency_details[$i]['conversionrate'];
				$actual_conversion_rate = $product_base_conv_rate * $conversion_rate;
				$converted_price = $actual_conversion_rate * $requestPrice;

				$log->debug("Going to save the Product - $curname currency relationship");

				if ($_REQUEST['action'] === 'CurrencyUpdate') {
					$adb->pquery('DELETE FROM jo_productcurrencyrel WHERE productid=? AND currencyid=?', array($this->id, $curid));
				}

				$query = "insert into jo_productcurrencyrel values(?,?,?,?)";
				$adb->pquery($query, array($this->id,$curid,$converted_price,$actualPrice));

				// Update the Product information with Base Currency choosen by the User.
				if ($_REQUEST['base_currency'] == $cur_valuename) {
					$currencySet = 1;
					$adb->pquery("update jo_products set currency_id=?, unit_price=? where productid=?", array($curid, $actualPrice, $this->id));
				}
			}
			if(!$currencySet){
				$curid = fetchCurrency($current_user->id);
				$adb->pquery("update jo_products set currency_id=? where productid=?", array($curid, $this->id));
			}
		}

		$log->debug("Exiting from insertPriceInformation($tablename, $module) method ...");
	}

	function updateUnitPrice() {
		$prod_res = $this->db->pquery("select unit_price, currency_id from jo_products where productid=?", array($this->id));
		$prod_unit_price = $this->db->query_result($prod_res, 0, 'unit_price');
		$prod_base_currency = $this->db->query_result($prod_res, 0, 'currency_id');

		$query = "update jo_productcurrencyrel set actual_price=? where productid=? and currencyid=?";
		$params = array($prod_unit_price, $this->id, $prod_base_currency);
		$this->db->pquery($query, $params);
	}

	function insertIntoAttachment($id,$module)
	{
		global  $log,$adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;
		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				  if($_REQUEST[$fileindex.'_hidden'] != '')
					  $files['original_name'] = modlib_purify($_REQUEST[$fileindex.'_hidden']);
				  else
					  $files['original_name'] = stripslashes($files['name']);
				  $files['original_name'] = str_replace('"','',$files['original_name']);
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		//Updating image information in main table of products
		$existingImageSql = 'SELECT name FROM jo_seattachmentsrel INNER JOIN jo_attachments ON
								jo_seattachmentsrel.attachmentsid = jo_attachments.attachmentsid LEFT JOIN jo_products ON
								jo_products.productid = jo_seattachmentsrel.crmid WHERE jo_seattachmentsrel.crmid = ?';
		$existingImages = $adb->pquery($existingImageSql,array($id));
		$numOfRows = $adb->num_rows($existingImages);
		$productImageMap = array();

		for ($i = 0; $i < $numOfRows; $i++) {
			$imageName = $adb->query_result($existingImages, $i, "name");
			array_push($productImageMap, decode_html($imageName));
		}
		$commaSeperatedFileNames = implode(",", $productImageMap);

		$adb->pquery('UPDATE jo_products SET imagename = ? WHERE productid = ?',array($commaSeperatedFileNames,$id));

		//Remove the deleted jo_attachments from db - Products
		if($module == 'Products' && $_REQUEST['del_file_list'] != '')
		{
			$del_file_list = explode("###",trim($_REQUEST['del_file_list'],"###"));
			foreach($del_file_list as $del_file_name)
			{
				$attach_res = $adb->pquery("select jo_attachments.attachmentsid from jo_attachments inner join jo_seattachmentsrel on jo_attachments.attachmentsid=jo_seattachmentsrel.attachmentsid where crmid=? and name=?", array($id,$del_file_name));
				$attachments_id = $adb->query_result($attach_res,0,'attachmentsid');

				$del_res1 = $adb->pquery("delete from jo_attachments where attachmentsid=?", array($attachments_id));
				$del_res2 = $adb->pquery("delete from jo_seattachmentsrel where attachmentsid=?", array($attachments_id));
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}



	/**	function used to get the list of leads which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_leads($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_leads(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = "SELECT jo_leaddetails.leadid, jo_crmentity.crmid, jo_leaddetails.firstname, jo_leaddetails.lastname, jo_leaddetails.company, jo_leadaddress.phone, jo_leadsubdetails.website, jo_leaddetails.email, case when (jo_users.user_name not like \"\") then jo_users.user_name else jo_groups.groupname end as user_name, jo_crmentity.smownerid, jo_products.productname, jo_products.qty_per_unit, jo_products.unit_price, jo_products.expiry_date
			FROM jo_leaddetails
			INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_leaddetails.leadid
			INNER JOIN jo_leadaddress ON jo_leadaddress.leadaddressid = jo_leaddetails.leadid
			INNER JOIN jo_leadsubdetails ON jo_leadsubdetails.leadsubscriptionid = jo_leaddetails.leadid
			INNER JOIN jo_seproductsrel ON jo_seproductsrel.crmid=jo_leaddetails.leadid
			INNER JOIN jo_products ON jo_seproductsrel.productid = jo_products.productid
			INNER JOIN jo_leadscf ON jo_leaddetails.leadid = jo_leadscf.leadid
			LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid
			LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
			WHERE jo_crmentity.deleted = 0 AND jo_leaddetails.converted=0 AND jo_products.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_leads method ...");
		return $return_value;
	}

	/**	function used to get the list of accounts which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_accounts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_accounts(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = "SELECT jo_account.accountid, jo_crmentity.crmid, jo_account.accountname, jo_accountbillads.bill_city, jo_account.website, jo_account.phone, case when (jo_users.user_name not like \"\") then jo_users.user_name else jo_groups.groupname end as user_name, jo_crmentity.smownerid, jo_products.productname, jo_products.qty_per_unit, jo_products.unit_price, jo_products.expiry_date
			FROM jo_account
			INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_account.accountid
			INNER JOIN jo_accountbillads ON jo_accountbillads.accountaddressid = jo_account.accountid
			LEFT JOIN jo_accountshipads ON jo_accountshipads.accountaddressid = jo_account.accountid
			INNER JOIN jo_seproductsrel ON jo_seproductsrel.crmid=jo_account.accountid
			INNER JOIN jo_products ON jo_seproductsrel.productid = jo_products.productid
			INNER JOIN jo_accountscf ON jo_account.accountid = jo_accountscf.accountid
			LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid
			LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
			WHERE jo_crmentity.deleted = 0 AND jo_products.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_accounts method ...");
		return $return_value;
	}

	/**	function used to get the list of contacts which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_contacts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_contacts(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = "SELECT jo_contactdetails.firstname, jo_contactdetails.lastname, jo_contactdetails.title, jo_contactdetails.accountid, jo_contactdetails.email, jo_contactdetails.phone, jo_crmentity.crmid, case when (jo_users.user_name not like \"\") then jo_users.user_name else jo_groups.groupname end as user_name, jo_crmentity.smownerid, jo_products.productname, jo_products.qty_per_unit, jo_products.unit_price, jo_products.expiry_date,jo_account.accountname
			FROM jo_contactdetails
			INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_contactdetails.contactid
			INNER JOIN jo_seproductsrel ON jo_seproductsrel.crmid=jo_contactdetails.contactid
			INNER JOIN jo_contactaddress ON jo_contactdetails.contactid = jo_contactaddress.contactaddressid
			INNER JOIN jo_contactsubdetails ON jo_contactdetails.contactid = jo_contactsubdetails.contactsubscriptionid
			INNER JOIN jo_customerdetails ON jo_contactdetails.contactid = jo_customerdetails.customerid
			INNER JOIN jo_contactscf ON jo_contactdetails.contactid = jo_contactscf.contactid
			INNER JOIN jo_products ON jo_seproductsrel.productid = jo_products.productid
			LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid
			LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_account ON jo_account.accountid = jo_contactdetails.accountid
			WHERE jo_crmentity.deleted = 0 AND jo_products.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}


	/**	function used to get the list of potentials which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_opportunities($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_opportunities(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_potential.potentialid, jo_crmentity.crmid,
			jo_potential.potentialname, jo_account.accountname, jo_potential.related_to, jo_potential.contact_id,
			jo_potential.sales_stage, jo_potential.amount, jo_potential.closingdate,
			case when (jo_users.user_name not like '') then $userNameSql else
			jo_groups.groupname end as user_name, jo_crmentity.smownerid,
			jo_products.productname, jo_products.qty_per_unit, jo_products.unit_price,
			jo_products.expiry_date FROM jo_potential
			INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_potential.potentialid
			INNER JOIN jo_seproductsrel ON jo_seproductsrel.crmid = jo_potential.potentialid
			INNER JOIN jo_products ON jo_seproductsrel.productid = jo_products.productid
			INNER JOIN jo_potentialscf ON jo_potential.potentialid = jo_potentialscf.potentialid
			LEFT JOIN jo_account ON jo_potential.related_to = jo_account.accountid
			LEFT JOIN jo_contactdetails ON jo_potential.contact_id = jo_contactdetails.contactid
			LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid
			LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
			WHERE jo_crmentity.deleted = 0 AND jo_products.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_opportunities method ...");
		return $return_value;
	}

	/**	function used to get the list of tickets which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_tickets($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_tickets(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'product_id','readwrite') == '0') {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT  case when (jo_users.user_name not like \"\") then $userNameSql else jo_groups.groupname end as user_name, jo_users.id,
			jo_products.productid, jo_products.productname,
			jo_troubletickets.ticketid,
			jo_troubletickets.parent_id, jo_troubletickets.title,
			jo_troubletickets.status, jo_troubletickets.priority,
			jo_crmentity.crmid, jo_crmentity.smownerid,
			jo_crmentity.modifiedtime, jo_troubletickets.ticket_no
			FROM jo_troubletickets
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_troubletickets.ticketid
			LEFT JOIN jo_products
				ON jo_products.productid = jo_troubletickets.product_id
			LEFT JOIN jo_ticketcf ON jo_troubletickets.ticketid = jo_ticketcf.ticketid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			WHERE jo_crmentity.deleted = 0
			AND jo_products.productid = ".$id;

		$log->debug("Exiting get_tickets method ...");

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_tickets method ...");
		return $return_value;
	}

	/**	function used to get the list of quotes which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_quotes($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_quotes(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_crmentity.*,
			jo_quotes.*,
			jo_potential.potentialname,
			jo_account.accountname,
			jo_inventoryproductrel.productid,
			case when (jo_users.user_name not like '') then $userNameSql
				else jo_groups.groupname end as user_name
			FROM jo_quotes
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_quotes.quoteid
			INNER JOIN jo_inventoryproductrel
				ON jo_inventoryproductrel.id = jo_quotes.quoteid
			LEFT OUTER JOIN jo_account
				ON jo_account.accountid = jo_quotes.accountid
			LEFT OUTER JOIN jo_potential
				ON jo_potential.potentialid = jo_quotes.potentialid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_quotescf
				ON jo_quotescf.quoteid = jo_quotes.quoteid
			LEFT JOIN jo_quotesbillads
				ON jo_quotesbillads.quotebilladdressid = jo_quotes.quoteid
			LEFT JOIN jo_quotesshipads
				ON jo_quotesshipads.quoteshipaddressid = jo_quotes.quoteid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid
			WHERE jo_crmentity.deleted = 0
			AND jo_inventoryproductrel.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}

	/**	function used to get the list of purchase orders which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_purchase_orders($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_purchase_orders(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_crmentity.*,
			jo_purchaseorder.*,
			jo_products.productname,
			jo_inventoryproductrel.productid,
			case when (jo_users.user_name not like '') then $userNameSql
				else jo_groups.groupname end as user_name
			FROM jo_purchaseorder
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_purchaseorder.purchaseorderid
			INNER JOIN jo_inventoryproductrel
				ON jo_inventoryproductrel.id = jo_purchaseorder.purchaseorderid
			INNER JOIN jo_products
				ON jo_products.productid = jo_inventoryproductrel.productid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_purchaseordercf
				ON jo_purchaseordercf.purchaseorderid = jo_purchaseorder.purchaseorderid
			LEFT JOIN jo_pobillads
				ON jo_pobillads.pobilladdressid = jo_purchaseorder.purchaseorderid
			LEFT JOIN jo_poshipads
				ON jo_poshipads.poshipaddressid = jo_purchaseorder.purchaseorderid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid
			WHERE jo_crmentity.deleted = 0
			AND jo_products.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_purchase_orders method ...");
		return $return_value;
	}

	/**	function used to get the list of sales orders which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_salesorder($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_salesorder(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_crmentity.*,
			jo_salesorder.*,
			jo_products.productname AS productname,
			jo_account.accountname,
			case when (jo_users.user_name not like '') then $userNameSql
				else jo_groups.groupname end as user_name
			FROM jo_salesorder
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_salesorder.salesorderid
			INNER JOIN jo_inventoryproductrel
				ON jo_inventoryproductrel.id = jo_salesorder.salesorderid
			INNER JOIN jo_products
				ON jo_products.productid = jo_inventoryproductrel.productid
			LEFT OUTER JOIN jo_account
				ON jo_account.accountid = jo_salesorder.accountid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_salesordercf
				ON jo_salesordercf.salesorderid = jo_salesorder.salesorderid
			LEFT JOIN jo_invoice_recurring_info
				ON jo_invoice_recurring_info.start_period = jo_salesorder.salesorderid
			LEFT JOIN jo_sobillads
				ON jo_sobillads.sobilladdressid = jo_salesorder.salesorderid
			LEFT JOIN jo_soshipads
				ON jo_soshipads.soshipaddressid = jo_salesorder.salesorderid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid
			WHERE jo_crmentity.deleted = 0
			AND jo_products.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_salesorder method ...");
		return $return_value;
	}

	/**	function used to get the list of invoices which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_invoices($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_invoices(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_crmentity.*,
			jo_invoice.*,
			jo_inventoryproductrel.quantity,
			jo_account.accountname,
			case when (jo_users.user_name not like '') then $userNameSql
				else jo_groups.groupname end as user_name
			FROM jo_invoice
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_invoice.invoiceid
			LEFT OUTER JOIN jo_account
				ON jo_account.accountid = jo_invoice.accountid
			INNER JOIN jo_inventoryproductrel
				ON jo_inventoryproductrel.id = jo_invoice.invoiceid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_invoicecf
				ON jo_invoicecf.invoiceid = jo_invoice.invoiceid
			LEFT JOIN jo_invoicebillads
				ON jo_invoicebillads.invoicebilladdressid = jo_invoice.invoiceid
			LEFT JOIN jo_invoiceshipads
				ON jo_invoiceshipads.invoiceshipaddressid = jo_invoice.invoiceid
			LEFT JOIN jo_users
				ON  jo_users.id=jo_crmentity.smownerid
			WHERE jo_crmentity.deleted = 0
			AND jo_inventoryproductrel.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_invoices method ...");
		return $return_value;
	}

	/**	function used to get the list of pricebooks which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_product_pricebooks($id, $cur_tab_id, $rel_tab_id, $actions=false)
	{
		global $log,$singlepane_view,$currentModule;
		$log->debug("Entering get_product_pricebooks(".$id.") method ...");

		$related_module = modlib_getModuleNameById($rel_tab_id);
		checkFileAccessForInclusion("modules/$related_module/$related_module.php");
		require_once("modules/$related_module/$related_module.php");
		$focus = new $related_module();
		$singular_modname = modlib_toSingular($related_module);

		$button = '';
		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes' && isPermitted($currentModule,'EditView',$id) == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_TO'). " ". getTranslatedString($related_module) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"AddProductToPriceBooks\";this.form.module.value=\"$currentModule\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_TO'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
		}

		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;


		$query = "SELECT jo_crmentity.crmid,
			jo_pricebook.*,
			jo_pricebookproductrel.productid as prodid
			FROM jo_pricebook
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_pricebook.pricebookid
			INNER JOIN jo_pricebookproductrel
				ON jo_pricebookproductrel.pricebookid = jo_pricebook.pricebookid
			INNER JOIN jo_pricebookcf
				ON jo_pricebookcf.pricebookid = jo_pricebook.pricebookid
			WHERE jo_crmentity.deleted = 0
			AND jo_pricebookproductrel.productid = ".$id;
		$log->debug("Exiting get_product_pricebooks method ...");

		$return_value = GetRelatedList($currentModule, $related_module, $focus, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		return $return_value;
	}

	/**	function used to get the number of vendors which are related to the product
	 *	@param int $id - product id
	 *	@return int number of rows - return the number of products which do not have relationship with vendor
	 */
	function product_novendor()
	{
		global $log;
		$log->debug("Entering product_novendor() method ...");
		$query = "SELECT jo_products.productname, jo_crmentity.deleted
			FROM jo_products
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_products.productid
			WHERE jo_crmentity.deleted = 0
			AND jo_products.vendor_id is NULL";
		$result=$this->db->pquery($query, array());
		$log->debug("Exiting product_novendor method ...");
		return $this->db->num_rows($result);
	}

	/**
	* Function to get Product's related Products
	* @param  integer   $id      - productid
	* returns related Products record in array format
	*/
	function get_products($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_products(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions && $this->ismember_check() === 0) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input type='hidden' name='createmode' id='createmode' value='link' />".
					"<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = "SELECT jo_products.productid, jo_products.productname,
			jo_products.productcode, jo_products.commissionrate,
			jo_seproductsrel.quantity AS qty_per_unit, jo_products.unit_price, 
			jo_crmentity.crmid, jo_crmentity.smownerid
			FROM jo_products
			INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_products.productid
			INNER JOIN jo_productcf
				ON jo_products.productid = jo_productcf.productid
			LEFT JOIN jo_seproductsrel ON jo_seproductsrel.crmid = jo_products.productid AND jo_seproductsrel.setype='Products'
			LEFT JOIN jo_users
				ON jo_users.id=jo_crmentity.smownerid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			WHERE jo_crmentity.deleted = 0 AND jo_seproductsrel.productid = $id ";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	}

	/**
	* Function to get Product's related Products
	* @param  integer   $id      - productid
	* returns related Products record in array format
	*/
	function get_parent_products($id)
	{
		global $log, $singlepane_view;
				$log->debug("Entering get_products(".$id.") method ...");

		global $app_strings;

		$focus = new Products();

		$button = '';

		if(isPermitted("Products",1,"") == 'yes')
		{
			$button .= '<input title="'.$app_strings['LBL_NEW_PRODUCT'].'" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Products\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
		}
		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&is_parent=1&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&is_parent=1&return_id='.$id;

		$query = "SELECT jo_products.productid, jo_products.productname,
			jo_products.productcode, jo_products.commissionrate,
			jo_products.qty_per_unit, jo_products.unit_price,
			jo_crmentity.crmid, jo_crmentity.smownerid
			FROM jo_products
			INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_products.productid
			INNER JOIN jo_seproductsrel ON jo_seproductsrel.productid = jo_products.productid AND jo_seproductsrel.setype='Products'
			INNER JOIN jo_productcf ON jo_products.productid = jo_productcf.productid

			WHERE jo_crmentity.deleted = 0 AND jo_seproductsrel.crmid = $id ";

		$log->debug("Exiting get_products method ...");
		return GetRelatedList('Products','Products',$focus,$query,$button,$returnset);
	}

	/**	function used to get the export query for product
	 *	@param reference $where - reference of the where variable which will be added with the query
	 *	@return string $query - return the query which will give the list of products to export
	 */
	function create_export_query($where)
	{
		global $log, $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("includes/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Products", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list FROM ".$this->table_name ."
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_products.productid
			LEFT JOIN jo_productcf
				ON jo_products.productid = jo_productcf.productid
			LEFT JOIN jo_vendor
				ON jo_vendor.vendorid = jo_products.vendor_id";

		$query .= " LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid";
		$query .= " LEFT JOIN jo_users ON jo_crmentity.smownerid = jo_users.id AND jo_users.status='Active'";
		$query .= $this->getNonAdminAccessControlQuery('Products',$current_user);
		$where_auto = " jo_crmentity.deleted=0";

		if($where != '') $query .= " WHERE ($where) AND $where_auto";
		else $query .= " WHERE $where_auto";

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}

	/** Function to check if the product is parent of any other product
	*/
	function isparent_check(){
		global $adb;
		$isparent_query = $adb->pquery(getListQuery("Products")." AND (jo_products.productid IN (SELECT productid from jo_seproductsrel WHERE jo_seproductsrel.productid = ? AND jo_seproductsrel.setype='Products'))",array($this->id));
		$isparent = $adb->num_rows($isparent_query);
		return $isparent;
	}

	/** Function to check if the product is member of other product
	*/
	function ismember_check(){
		global $adb;
		$ismember_query = $adb->pquery(getListQuery("Products")." AND (jo_products.productid IN (SELECT crmid from jo_seproductsrel WHERE jo_seproductsrel.crmid = ? AND jo_seproductsrel.setype='Products'))",array($this->id));
		$ismember = $adb->num_rows($ismember_query);
		return $ismember;
	}

	/**
	 * Move the related records of the specified list of id's to the given record.
	 * @param String This module name
	 * @param Array List of Entity Id's from which related records need to be transfered
	 * @param Integer Id of the the Record to which the related records are to be moved
	 */
	function transferRelatedRecords($module, $transferEntityIds, $entityId) {
		global $adb,$log;
		$log->debug("Entering function transferRelatedRecords ($module, $transferEntityIds, $entityId)");

		$rel_table_arr = Array("HelpDesk"=>"jo_troubletickets","Products"=>"jo_seproductsrel","Attachments"=>"jo_seattachmentsrel",
				"Quotes"=>"jo_inventoryproductrel","PurchaseOrder"=>"jo_inventoryproductrel","SalesOrder"=>"jo_inventoryproductrel",
				"Invoice"=>"jo_inventoryproductrel","PriceBooks"=>"jo_pricebookproductrel","Leads"=>"jo_seproductsrel",
				"Accounts"=>"jo_seproductsrel","Potentials"=>"jo_seproductsrel","Contacts"=>"jo_seproductsrel",
				"Documents"=>"jo_senotesrel",'Assets'=>'jo_assets',);

		$tbl_field_arr = Array("jo_troubletickets"=>"ticketid","jo_seproductsrel"=>"crmid","jo_seattachmentsrel"=>"attachmentsid",
				"jo_inventoryproductrel"=>"id","jo_pricebookproductrel"=>"pricebookid","jo_seproductsrel"=>"crmid",
				"jo_senotesrel"=>"notesid",'jo_assets'=>'assetsid');

		$entity_tbl_field_arr = Array("jo_troubletickets"=>"product_id","jo_seproductsrel"=>"crmid","jo_seattachmentsrel"=>"crmid",
				"jo_inventoryproductrel"=>"productid","jo_pricebookproductrel"=>"productid","jo_seproductsrel"=>"productid",
				"jo_senotesrel"=>"crmid",'jo_assets'=>'product');

		foreach($transferEntityIds as $transferId) {
			foreach($rel_table_arr as $rel_module=>$rel_table) {
				$id_field = $tbl_field_arr[$rel_table];
				$entity_id_field = $entity_tbl_field_arr[$rel_table];
				// IN clause to avoid duplicate entries
				$sel_result =  $adb->pquery("select $id_field from $rel_table where $entity_id_field=? " .
						" and $id_field not in (select $id_field from $rel_table where $entity_id_field=?)",
						array($transferId,$entityId));
				$res_cnt = $adb->num_rows($sel_result);
				if($res_cnt > 0) {
					for($i=0;$i<$res_cnt;$i++) {
						$id_field_value = $adb->query_result($sel_result,$i,$id_field);
						$adb->pquery("update $rel_table set $entity_id_field=? where $entity_id_field=? and $id_field=?",
							array($entityId,$transferId,$id_field_value));
					}
				}
			}
		}
		$log->debug("Exiting transferRelatedRecords...");
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryplanner) {
		global $current_user;
		$matrix = $queryplanner->newDependencyMatrix();

		$matrix->setDependency("jo_crmentityProducts",array("jo_groupsProducts","jo_usersProducts","jo_lastModifiedByProducts"));
		//query planner Support  added
		if (!$queryplanner->requireTable('jo_products', $matrix)) {
			return '';
		}
		$matrix->setDependency("jo_products",array("innerProduct","jo_crmentityProducts","jo_productcf","jo_vendorRelProducts"));

		$query = $this->getRelationQuery($module,$secmodule,"jo_products","productid", $queryplanner);
		if ($queryplanner->requireTable("innerProduct")){
			$query .= " LEFT JOIN (
					SELECT jo_products.productid,
							(CASE WHEN (jo_products.currency_id = 1 ) THEN jo_products.unit_price
								ELSE (jo_products.unit_price / jo_currency_info.conversion_rate) END
							) AS actual_unit_price
					FROM jo_products
					LEFT JOIN jo_currency_info ON jo_products.currency_id = jo_currency_info.id
					LEFT JOIN jo_productcurrencyrel ON jo_products.productid = jo_productcurrencyrel.productid
					AND jo_productcurrencyrel.currencyid = ". $current_user->currency_id . "
				) AS innerProduct ON innerProduct.productid = jo_products.productid";
		}
		if ($queryplanner->requireTable("jo_crmentityProducts")){
			$query .= " left join jo_crmentity as jo_crmentityProducts on jo_crmentityProducts.crmid=jo_products.productid and jo_crmentityProducts.deleted=0";
		}
		if ($queryplanner->requireTable("jo_productcf")){
			$query .= " left join jo_productcf on jo_products.productid = jo_productcf.productid";
		}
			if ($queryplanner->requireTable("jo_groupsProducts")){
			$query .= " left join jo_groups as jo_groupsProducts on jo_groupsProducts.groupid = jo_crmentityProducts.smownerid";
		}
		if ($queryplanner->requireTable("jo_usersProducts")){
			$query .= " left join jo_users as jo_usersProducts on jo_usersProducts.id = jo_crmentityProducts.smownerid";
		}
		if ($queryplanner->requireTable("jo_vendorRelProducts")){
			$query .= " left join jo_vendor as jo_vendorRelProducts on jo_vendorRelProducts.vendorid = jo_products.vendor_id";
		}
		if ($queryplanner->requireTable("jo_lastModifiedByProducts")){
			$query .= " left join jo_users as jo_lastModifiedByProducts on jo_lastModifiedByProducts.id = jo_crmentityProducts.modifiedby ";
		}
		if ($queryplanner->requireTable("jo_createdbyProducts")){
			$query .= " left join jo_users as jo_createdbyProducts on jo_createdbyProducts.id = jo_crmentityProducts.smcreatorid ";
		}
		return $query;
	}

	/*
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (
			"HelpDesk" => array("jo_troubletickets"=>array("product_id","ticketid"),"jo_products"=>"productid"),
			"Quotes" => array("jo_inventoryproductrel"=>array("productid","id"),"jo_products"=>"productid"),
			"PurchaseOrder" => array("jo_inventoryproductrel"=>array("productid","id"),"jo_products"=>"productid"),
			"SalesOrder" => array("jo_inventoryproductrel"=>array("productid","id"),"jo_products"=>"productid"),
			"Invoice" => array("jo_inventoryproductrel"=>array("productid","id"),"jo_products"=>"productid"),
			"Leads" => array("jo_seproductsrel"=>array("productid","crmid"),"jo_products"=>"productid"),
			"Accounts" => array("jo_seproductsrel"=>array("productid","crmid"),"jo_products"=>"productid"),
			"Contacts" => array("jo_seproductsrel"=>array("productid","crmid"),"jo_products"=>"productid"),
			"Potentials" => array("jo_seproductsrel"=>array("productid","crmid"),"jo_products"=>"productid"),
			"Products" => array("jo_products"=>array("productid","product_id"),"jo_products"=>"productid"),
			"PriceBooks" => array("jo_pricebookproductrel"=>array("productid","pricebookid"),"jo_products"=>"productid"),
			"Documents" => array("jo_senotesrel"=>array("crmid","notesid"),"jo_products"=>"productid"),
		);
		return $rel_tables[$secmodule];
	}

	function deleteProduct2ProductRelation($record,$return_id,$is_parent){
		global $adb;
		if($is_parent==0){
			$sql = "delete from jo_seproductsrel WHERE crmid = ? AND productid = ?";
			$adb->pquery($sql, array($record,$return_id));
		} else {
			$sql = "delete from jo_seproductsrel WHERE crmid = ? AND productid = ?";
			$adb->pquery($sql, array($return_id,$record));
		}
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;
		//Backup Campaigns-Product Relation
		$cmp_q = 'SELECT campaignid FROM jo_campaign WHERE product_id = ?';
		$cmp_res = $this->db->pquery($cmp_q, array($id));
		if ($this->db->num_rows($cmp_res) > 0) {
			$cmp_ids_list = array();
			for($k=0;$k < $this->db->num_rows($cmp_res);$k++)
			{
				$cmp_ids_list[] = $this->db->query_result($cmp_res,$k,"campaignid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'jo_campaign', 'product_id', 'campaignid', implode(",", $cmp_ids_list));
			$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//we have to update the product_id as null for the campaigns which are related to this product
		$this->db->pquery('UPDATE jo_campaign SET product_id=0 WHERE product_id = ?', array($id));

		// restoring products relations
		$productRelRB = $this->db->pquery('SELECT * FROM jo_seproductsrel WHERE productid = ?' ,array($id));
		$rows = $this->db->num_rows($productRelRB);
		if($this->db->num_rows($productRelRB)) {
			for($i=0; $i<$rows; $i++) {
				$crmid = $this->db->query_result($productRelRB, $i, "crmid");
				$params = array($id, RB_RECORD_DELETED, 'jo_seproductsrel', 'productid', 'crmid', $crmid);
				$this->db->pquery('INSERT INTO jo_relatedlists_rb(entityid, action, rel_table, rel_column, ref_column, related_crm_ids)
						VALUES (?,?,?,?,?,?)', $params);
			}
		}
		$this->db->pquery('DELETE from jo_seproductsrel WHERE productid=? or crmid=?',array($id,$id));

		parent::unlinkDependencies($module, $id);
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Calendar') {
			$sql = 'DELETE FROM jo_seactivityrel WHERE crmid = ? AND activityid = ?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Leads' || $return_module == 'Contacts' || $return_module == 'Potentials') {
			$sql = 'DELETE FROM jo_seproductsrel WHERE productid = ? AND crmid = ?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Vendors') {
			$sql = 'UPDATE jo_products SET vendor_id = ? WHERE productid = ?';
			$this->db->pquery($sql, array(null, $id));
		} elseif($return_module == 'Accounts') {
			$sql = 'DELETE FROM jo_seproductsrel WHERE productid = ? AND (crmid = ? OR crmid IN (SELECT contactid FROM jo_contactdetails WHERE accountid=?))';
			$param = array($id, $return_id,$return_id);
			$this->db->pquery($sql, $param);
		} elseif($return_module == 'Documents') {
			$sql = 'DELETE FROM jo_senotesrel WHERE crmid=? AND notesid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} else {
			parent::unlinkRelationship($id, $return_module, $return_id);
		}
	}

	function save_related_module($module, $crmid, $with_module, $with_crmids, $otherParams = array()) {
		$adb = PearDatabase::getInstance();

		$qtysList = array();
		if ($otherParams && is_array($otherParams['quantities'])) {
			$qtysList = $otherParams['quantities'];
		}

		if(!is_array($with_crmids)) $with_crmids = Array($with_crmids);
		foreach($with_crmids as $with_crmid) {
			$qty = 0;
			if (array_key_exists($with_crmid, $qtysList)) {
				$qty = (float) $qtysList[$with_crmid];
			}
			if (!$qty) {
				$qty = 1;
			}

			if (in_array($with_module, array('Leads', 'Accounts', 'Contacts', 'Potentials', 'Products'))) {
				$query = $adb->pquery("SELECT * FROM jo_seproductsrel WHERE crmid=? AND productid=?", array($crmid, $with_crmid));
				if($adb->num_rows($query) == 0) {
					$adb->pquery('INSERT INTO jo_seproductsrel VALUES (?,?,?,?)', array($with_crmid, $crmid, $with_module, $qty));
				}
			} else {
				parent::save_related_module($module, $crmid, $with_module, $with_crmid);
			}
		}
	}

}
?>
