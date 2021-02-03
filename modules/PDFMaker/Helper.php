<?php
class Helper{

	public function __construct(){
	}

	public function convertFieldAndExportPDF($sourceModule, $recordIds, $tempId, $action_type = false, $templatecontent = false, $filename=false){
		global $adb, $site_URL;
		$html = "";

		$companyDetails = Head_CompanyDetails_Model::getInstanceById();
		$companyLogo = $companyDetails->getLogo();
		$image_path = $companyLogo->get('imagepath');

		$recordId_value = explode(',', $recordIds);
		if($unserializedValue['page_format'])
			$page_format = $unserializedValue['page_format'];
		else
			$page_format = 'A4';
		$getValue = $adb->pquery('select * from jo_pdfmaker where pdfmakerid = ?', array($tempIds[0]));
		$settings = $adb->query_result($getValue, 0, 'settings');
		$unserializedValue = unserialize(base64_decode($settings));

		if($unserializedValue['margin_left'])
			$margin_left = $unserializedValue['margin_left'];
		else
			$margin_left = '10%';
                if($unserializedValue['margin_right'])
                        $margin_right = $unserializedValue['margin_right'];
                else
                        $margin_right = '10%';

                if($unserializedValue['margin_top'])
                        $margin_top = $unserializedValue['margin_top'];
                else
                        $margin_top = '10%';

                if($unserializedValue['margin_bottom'])
                        $margin_bottom = $unserializedValue['margin_bottom'];
                else
                        $margin_bottom = '10%';


		$mpdf=new \Mpdf\Mpdf();

		foreach($recordId_value as $recordId){
			$getValue = $adb->pquery('select * from jo_pdfmaker where pdfmakerid = ?', array($tempId));
			        if(empty($templatecontent)){
					$templatecontent = $adb->query_result($getValue, 0, 'body');
				}
			$templateheader = $adb->query_result($getValue, 0, 'header');
			$templatefooter = $adb->query_result($getValue, 0, 'footer');
			$tempName = $adb->query_result($getValue, 0, 'name');
			$settings = $adb->query_result($getValue, 0, 'settings');
			$unserializedValue = unserialize(base64_decode($settings));
			if(empty($filename)) {
			    if(!empty($unserializedValue['file_name']))
				$filename = $unserializedValue['file_name'];
			    else
				$filename = $tempName;
			}
			$html = $templatecontent;
			$token_data_pair = explode('$', $html);
			$tokenDataPair = explode('$', $html);
			//$html = str_replace('$image_URL$', $image_path, $html);
			$html = str_replace('$image_URL$', "<img src='$image_path' alt='Logo' />", $html);
			$fields = Array();
			for ($i = 1; $i < count($token_data_pair); $i+=1) {
				$module = explode('-', $tokenDataPair[$i]);
				$fields[$module[0]][] = $module[1];
			}

			$values = array();
			$fieldColumnMapping = array();

			if(is_array($fields['listviewblock_start']) && count($fields['listviewblock_start']) > 0 && is_array($fields['listviewblock_end']) && count($fields['listviewblock_end']) > 0){

				$parsed = $this->get_string_between($templatecontent, '$listviewblock_start$', '$listviewblock_end$');
				$recordIds = explode(',', $recordId);
				$html = '';
				$count = 0;
				foreach($recordIds as $singleRecordId){
					$count = $count + 1;
					$html .= getMergedDescription($parsed, $singleRecordId, $sourceModule); 
					$html = str_replace('$listviewblock_sno$', $count, $html);
				}
				$html = str_replace($parsed, $html, $templatecontent);
				$html = str_replace('$listviewblock_start$', "", $html);
				$html = str_replace('$listviewblock_end$', "", $html);
				//$html = str_replace('$image_URL$', $image_path, $html);
				$html = str_replace('$image_URL$', "<img src='$image_path' alt='Logo' />", $html);

			}
			else if(is_array($fields['productblock_start']) && count($fields['productblock_start']) > 0 && is_array($fields['productblock_end']) && count($fields['productblock_end']) > 0){
				$parsed = $this->get_string_between($templatecontent, '$productblock_start$', '$productblock_end$');
				$html = '';
				$count = 0;
				$get_product_details = $adb->pquery('select * from jo_inventoryproductrel where id = ?', array($recordId));
unset($record);
				while($record_details = $adb->fetch_array($get_product_details))      {
					$record[] = $record_details;
				}
				foreach($record as $single_record){
					$count = $count + 1;
					$get_product_name = $adb->pquery('select * from jo_products inner join jo_productcf on jo_products.productid = jo_productcf.productid where jo_products.productid = ?', array($single_record['productid']));
					if($adb->num_rows($get_product_name) == 0){
						$get_product_name = $adb->pquery('select * from jo_service inner join jo_servicecf on jo_service.serviceid = jo_servicecf.serviceid where jo_service.serviceid = ?', array($single_record['productid']));
						$product_name = $adb->query_result($get_product_name, 0, 'servicename');
						$record_module_name = 'Services';
					} else {
						$product_name = $adb->query_result($get_product_name, 0, 'productname');
						$record_module_name = 'Products';
					}

					$html_value = $parsed;
					foreach ($fields['products'] as $column) {
						$needle = '$products'."-$column$";
						$fieldColumnMapping[$column] = $column;
						if($column == 'productname')
							$values[$column] = $product_name;
						elseif($column == 'total'){
							$related_value = $this->getProductDetails($recordId, $sourceModule);
							$values[$column] = $related_value[$count]['productTotal'.$count];
						}
						else{
						    $uitype = $this->getUITypeByName($record_module_name, $column);
						    if($uitype == 0) {
						    	$single_record[$column] = floatval($single_record[$column]);
			                            	//format float value    
                        			    	if(is_float($single_record[$column])){
			                                    $single_record[$column] = number_format((float)$single_record[$column], 2, '.', '');
                        			    	}
						    	$values[$column] = $single_record[$column];
						    } else {
						    	$values[$column] = $adb->query_result($get_product_name, 0, $column);
						    }

						    if($column == 'product_no' && $record_module_name == 'Services') {
							$values[$column] = $adb->query_result($get_product_name, 0, 'service_no');
						    }
						}
						$html_value = str_replace($needle, $values[array_search($column, $fieldColumnMapping)], $html_value);
					}
       				        $modulevalue = strtolower($sourceModule);
			                    foreach($fields[$modulevalue] as $column){
                        			$needle = '$'."$modulevalue"."-$column$";
			                        $fieldColumnMapping[$column] = $column;
                        			if($column == 'comment'){
			                            $get_invoice_comment = $adb->pquery('select * from jo_inventoryproductrel where productid = ? and id=? and lineitem_id=?', array($single_record['productid'], $recordId, $single_record['lineitem_id']));
	
        			                    while($record_details = $adb->fetch_array($get_invoice_comment))
							{
			                                $invoice_comment[] = $record_details;
                            				}
			                            foreach($invoice_comment as $single_value)
							{
                        			        if($single_record['productid'] == $single_value['productid'])
								{
				                                    $values[$column] = $single_value['comment'];
                                				}
                            				}
                                                }
						if($column == 'currency_id')
                                               {
                                                       $moduleInfo = array('PurchaseOrder' => array('id' => 'purchaseorderid', 'table' => 'jo_purchaseorder'),
                                                               'Invoice' => array('id' => 'invoiceid', 'table' => 'jo_invoice'),
                                                               'Quotes' => array('id' => 'quoteid', 'table' => 'jo_quotes'),
                                                               'SalesOrder' => array('id' => 'salesorderid', 'table' => 'jo_salesorder'));

                                               $getValue = $adb->pquery("select * from {$moduleInfo[$sourceModule]['table']} where {$moduleInfo[$sourceModule]['id']}  = ?", array($recordId));
                                               $value_array = $adb->fetch_array($getValue);
                                               $currency_id = $value_array['currency_id'];
                                               $getCurrency = $adb->pquery("select * from jo_currency_info where id = ?", array($currency_id)); 
                                               $currency_array = $adb->fetch_array($getCurrency);
                                               $currency_symbol = $currency_array['currency_symbol'];
                                               $values[$column] = $currency_symbol;
					       $currency_mapped_array = ['needle' => $needle, 'fieldColumnMapping' => $fieldColumnMapping[$column], 'value' => $currency_symbol];
                                               }
                                                $html_value = str_replace($needle, $values[array_search($column, $fieldColumnMapping)], $html_value);
                                        }

					$html .= $html_value;
					$html = str_replace('$productblock_sno$', $count, $html);
					//$html = str_replace('$image_URL$', $image_path, $html);
					$html = str_replace('$image_URL$', "<img src='$image_path' alt='Logo' />", $html);
				}
				$html = str_replace($parsed, $html, $templatecontent);
				//$html = str_replace('$image_URL$', $image_path, $html);
				$html = str_replace('$image_URL$', "<img src='$image_path' alt='Logo' />", $html);
//				$html = str_replace('$productblock_start$', "", $html);
//				$html = str_replace('$productblock_end$', "", $html);
				$dom = new DOMDocument();
                                $dom->loadHTML('<?xml encoding="UTF-8">' . html_entity_decode($html));
                                $xpath = new DOMXPath($dom);
                                $i = 0;
			        foreach( $xpath->query('//table/tbody/tr') as $key => $tr)
				{
		                    $tds = $tr->getElementsByTagName('td');
                		    foreach($tds as $single_td)
				    {
		                    $colspan = $single_td->getAttribute('colspan');
		                        if( $single_td->nodeValue == '$productblock_start$' || $single_td->nodeValue == '$productblock_end$' || $single_td->nodeValue == '$listviewblock_start$'|| $single_td->nodeValue == '$listviewblock_end$')
					{
                		            $tr->parentNode->removeChild($tr);
                        		}
		                        elseif(!$single_td->nodeValue && $colspan != '')
					{
                		            $tr->parentNode->removeChild($tr);
                        		}
		                        unset($colspan);
                		    }
                    		$i++;
                		}
                        $html = $dom->saveHTML();
				$moduleInfo = array('PurchaseOrder' => array('id' => 'purchaseorderid', 'table' => 'jo_purchaseorder'),
						'Invoice' => array('id' => 'invoiceid', 'table' => 'jo_invoice'),
						'Quotes' => array('id' => 'quoteid', 'table' => 'jo_quotes'),
						'SalesOrder' => array('id' => 'salesorderid', 'table' => 'jo_salesorder')
						);

				$getValue = $adb->pquery("select * from {$moduleInfo[$sourceModule]['table']} where {$moduleInfo[$sourceModule]['id']}  = ?", array($recordId));
				foreach ($fields['pdt'] as $column) {
					$needle = '$pdt'."-$column$";
					$fieldColumnMapping[$column] = $column;
					$related_value = $this->getProductDetails($recordId, $sourceModule);

					$value = $adb->query_result($getValue, 0, $column);
					if($column == 'shtax_totalamount' || $column == 'tax_totalamount'){
						$related_value = $this->getProductDetails($recordId, $sourceModule);
						$values[$column] = $related_value[1]['final_details'][$column];
					}
					else{
						$value = $adb->query_result($getValue, 0, $column);
						$values[$column] = $value;
					}
			// commanted and modified by aruna. Because converting tax value into float makes the value wrong. If any changes needed, modify this.
                       /*              $values[$column] = floatval($values[$column]);
                                          //format float value 
                                          if(is_float($values[$column])){
                                                  $values[$column] = number_format((float)$values[$column], 2, '.', '');
                                          }*/
                                       if($column == 'shtax_totalamount' || $column == 'tax_totalamount'){
                                           $html = str_replace($needle,$values[array_search($column, $fieldColumnMapping)], $html);
					   //$html = str_replace('$image_URL$', $image_path, $html);
					   $html = str_replace('$image_URL$', "<img src='$image_path' alt='Logo' />", $html);
                                       }
                                       else{
                                           $values[$column] = number_format((float)$values[$column], 2, '.', '');
                                           $html = str_replace($needle,$values[array_search($column, $fieldColumnMapping)], $html);
					   //$html = str_replace('$image_URL$', $image_path, $html);
					   $html = str_replace('$image_URL$', "<img src='$image_path' alt='Logo' />", $html);
                                       }
				}
			$html = str_replace($currency_mapped_array['needle'], $currency_mapped_array['value'], $html);
		 	//$html = str_replace('$image_URL$', $image_path, $html);
			$html = str_replace('$image_URL$', "<img src='$image_path' alt='Logo' />", $html);
			}
			else {
				$html = getMergedDescription($templatecontent, $recordId, $sourceModule);
				//$html = str_replace('$image_URL$', $image_path, $html);
				$html = str_replace('$image_URL$', "<img src='$image_path' alt='Logo' />", $html);
			}

			if (is_array($fields['company']) && count($fields['company']) > 0) {

				foreach ($fields['company'] as $column) {
					$needle = '$company'."-$column$";
					$getValue = $adb->pquery('select * from jo_organizationdetails', array());
					$columnvalue = $adb->query_result($getValue, 0, $column);
					$fieldColumnMapping[$column] = $column;
					$values[$column] = $columnvalue;
					$html = str_replace($needle,
							$values[array_search($column, $fieldColumnMapping)], $html);
				}
			}
			if(count($fields['terms']) > 0){
				$getTandC = $adb->pquery('select * from jo_inventory_tandc', array());
				$TandC = $adb->query_result($getTandC, 0, 'tandc');
				$html = str_replace('$terms-and-condition$', $TandC,$html);
			}
			$html = getMergedDescription($html, $recordId, $sourceModule); 
			$bar_code = explode('**', $html);
			$barCode = explode('**', $html);
                        for ($i = 1; $i < count($bar_code); $i+=1) {
                                $module = explode('-', $barCode[$i]);
                                $bar_code_fields[$module[0]][] = $module[1];
                        }
			$bar_code_value = array();
			$fieldColumnMappingBarCode = array();
			if($bar_code_fields['BARCODE'] && count($bar_code_fields['BARCODE']) > 0){
                                foreach ($bar_code_fields['BARCODE'] as $column) {
					list($type, $code) = explode('=', $column);
                                        $needle = '**BARCODE'."-$column**";
                                        $fieldColumnMappingBarCode[$column] = $column;
                                        $bar_code_value[$column] = '<barcode code="'.$code.'" type="'.$type.'" class="barcode" height="0.66" text="1" />';
                                        $html = str_replace($needle,
                                                        $bar_code_value[array_search($column, $fieldColumnMappingBarCode)], $html);
                                }


			}
				if($unserializedValue['page_format'])
					$page_format = $unserializedValue['page_format'];
				else
					$page_format = 'A4';
				$this->pages = $this->pages + 1;
				$mpdf->AddPage();
				if(!empty($templateheader)){
					$header_value = explode('##', $templateheader);
					if(count($header_value) == 3){
						$header_value1 = explode('/', $templateheader);
						if(count($header_value1) == 2){
							$mpdf->SetHeader('{PAGENO} / '.$this->pages);
						}
						else
							$mpdf->SetHeader('{PAGENO}');
					}
					elseif($templateheader != '&nbsp;'){
	                                        $templateheader = getMergedDescription($templateheader, $recordId, $sourceModule);
						$token_data_pair = explode('$', $templateheader);
						$tokenDataPair = explode('$', $templateheader);
						$fields = Array();
						for ($i = 1; $i < count($token_data_pair); $i+=1) {
							$module = explode('-', $tokenDataPair[$i]);
							$fields[$module[0]][] = $module[1];
						}

						$values = array();
						$fieldColumnMapping = array();

						if (is_array($fields['company']) && count($fields['company']) > 0) {

							foreach ($fields['company'] as $column) {
								$needle = '$company'."-$column$";
								$getValue = $adb->pquery('select * from jo_organizationdetails', array());
								$columnvalue = $adb->query_result($getValue, 0, $column);
								$fieldColumnMapping[$column] = $column;
								$values[$column] = $columnvalue;
								$templateheader = str_replace($needle,
										$values[array_search($column, $fieldColumnMapping)], $templateheader);
							}
						}

						$mpdf->SetHeader($templateheader);
					}
				}

				if(!empty($templatefooter)){
					$footer_value = explode('##', $templatefooter);
					if(count($footer_value) == 3){
						$footer_value1 = explode('/', $templatefooter);

						if(count($footer_value1) == 2){
							$mpdf->setFooter('{PAGENO} / '.$this->pages);
						}
						else
							$mpdf->setFooter('{PAGENO}');
					}

					elseif($templatefooter != '&nbsp;'){
	                                        $templatefooter = getMergedDescription($templatefooter, $recordId, $sourceModule);
                                                $token_data_pair = explode('$', $templatefooter);
                                                $tokenDataPair = explode('$', $templatefooter);
                                                $fields = Array();
                                                for ($i = 1; $i < count($token_data_pair); $i+=1) {
                                                        $module = explode('-', $tokenDataPair[$i]);
                                                        $fields[$module[0]][] = $module[1];
                                                }

                                                $values = array();
                                                $fieldColumnMapping = array();

                                                if (is_array($fields['company']) && count($fields['company']) > 0) {

                                                        foreach ($fields['company'] as $column) {
                                                                $needle = '$company'."-$column$";
                                                                $getValue = $adb->pquery('select * from jo_organizationdetails', array());
                                                                $columnvalue = $adb->query_result($getValue, 0, $column);
                                                                $fieldColumnMapping[$column] = $column;
                                                                $values[$column] = $columnvalue;
                                                                $templatefooter = str_replace($needle,
                                                                                $values[array_search($column, $fieldColumnMapping)], $templatefooter);
                                                        }
                                                }
						$mpdf->setFooter($templatefooter);
					}
				}
				$mpdf->WriteHTML(html_entity_decode($html));

				$recordModel = Inventory_Record_Model::getInstanceById($recordId);
				$lower_value = strtolower($sourceModule);
		                $record_related_number = $recordModel->get($lower_value.'_no');

			}

                        if($action_type == 'detailview_save_doc' || $action_type == 'detailview_send_mail'){
                                return $mpdf;
                        }
			else {
				ob_clean();
		  		$mpdf->Output("$filename-$record_related_number.pdf", 'D');
			}
		}


	public function get_string_between($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}

	public function getProductDetails($record, $moduleName){


		$recordModel = Inventory_Record_Model::getInstanceById($record);
		$relatedProducts = $recordModel->getProducts();

		//##Final details convertion started
		$finalDetails = $relatedProducts[1]['final_details'];

		//Final tax details convertion started
		$taxtype = $finalDetails['taxtype'];
		if ($taxtype == 'group') {
			$taxDetails = $finalDetails['taxes'];
			$taxCount = count($taxDetails);
			for($i=0; $i<$taxCount; $i++) {
				$taxDetails[$i]['amount'] = Head_Currency_UIType::transformDisplayValue($taxDetails[$i]['amount'], null, true);
			}
			$finalDetails['taxes'] = $taxDetails;
		}
		//Final tax details convertion ended

		//Final shipping tax details convertion started
		$shippingTaxDetails = $finalDetails['sh_taxes'];
		$taxCount = count($shippingTaxDetails);
		for($i=0; $i<$taxCount; $i++) {
			$shippingTaxDetails[$i]['amount'] = Head_Currency_UIType::transformDisplayValue($shippingTaxDetails[$i]['amount'], null, true);
		}
		$finalDetails['sh_taxes'] = $shippingTaxDetails;
		//Final shipping tax details convertion ended

		$currencyFieldsList = array('adjustment', 'grandTotal', 'hdnSubTotal', 'preTaxTotal', 'tax_totalamount',
				'shtax_totalamount', 'discountTotal_final', 'discount_amount_final', 'shipping_handling_charge', 'totalAfterDiscount');
		foreach ($currencyFieldsList as $fieldName) {
			$finalDetails[$fieldName] = Head_Currency_UIType::transformDisplayValue($finalDetails[$fieldName], null, true);
		}
		$relatedProducts[1]['final_details'] = $finalDetails;
		//##Final details convertion ended

		//##Product details convertion started
		$productsCount = count($relatedProducts);
		for ($i=1; $i<=$productsCount; $i++) {
			$product = $relatedProducts[$i];

			//Product tax details convertion started
			if ($taxtype == 'individual') {
				$taxDetails = $product['taxes'];
				$taxCount = count($taxDetails);
				for($j=0; $j<$taxCount; $j++) {
					$taxDetails[$j]['amount'] = Head_Currency_UIType::transformDisplayValue($taxDetails[$j]['amount'], null, true);
				}
				$product['taxes'] = $taxDetails;
			}
			//Product tax details convertion ended

			$currencyFieldsList = array('taxTotal', 'netPrice', 'listPrice', 'unitPrice', 'productTotal',
					'discountTotal', 'discount_amount', 'totalAfterDiscount');
			foreach ($currencyFieldsList as $fieldName) {
				$product[$fieldName.$i] = Head_Currency_UIType::transformDisplayValue($product[$fieldName.$i], null, true);
			}

			$relatedProducts[$i] = $product;
		}
		return $relatedProducts;

	}

	public function getUITypeByName($module, $fieldname) {
		global $adb;
		$tabid = getTabid($module);
		$run_query = $adb->pquery('select fieldname,uitype from jo_field where tabid = ? and fieldname = ?', array($tabid , $fieldname));
		$result = $adb->fetch_array( $run_query );
		return (int)$result['uitype'];
	}
}
