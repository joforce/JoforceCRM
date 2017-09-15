<?php

class VTPDFMaker {

	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
		global $adb, $site_URL;
 		if($eventType == 'module.postinstall') {
			// TODO Handle actions after this module is installed.

			$adb->pquery("CREATE TABLE `jo_vtpdfmakersettings` (
                                                        `id` int(10) NOT NULL AUTO_INCREMENT,
                                                        `version` varchar(10) DEFAULT NULL,
                                                        PRIMARY KEY (`id`)
                                                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8",array());
                        $adb->pquery('insert into jo_vtpdfmakersettings (id, version) values (1, ?)', array('0.1'));

			include_once('vtlib/Head/Module.php');
			$moduleInstance = Head_Module::getInstance('VTPDFMaker');
			$moduleInstance->addLink('HEADERSCRIPT', 'HEADERSCRIPT', 'layouts/v7/modules/VTPDFMaker/resources/Helper.js');

			$adb->pquery("insert into jo_vtpdfmaker values(?,?,?,?,?,?,?,?,?)", array(1, 'Invoice', 'Invoices', '', '<table width="985"><tbody><tr><td style="width:50%;"><img alt="" height="79" src="'.$site_URL.'test/logo/JoForce-Logo.png" width="200" /></td>
			<td style="width:50%;"> </td>
		</tr><tr><td style="width:50%;">$company-organizationname$</td>
			<td style="font-size:20px;width:50%;text-align:right;"><b>INVOICE</b></td>
		</tr><tr><td style="width:50%;">$company-address$</td>
			<td style="color:rgb(128,128,128);width:50%;text-align:right;">$invoice-invoice_no$</td>
		</tr><tr><td style="width:50%;">$company-country$</td>
			<td style="width:50%;"> </td>
		</tr><tr><td style="width:50%;"> </td>
			<td style="text-align:right;width:50%;"><span style="font-size:12px;"><b>Balance Due</b></span></td>
		</tr><tr><td style="width:50%;"> </td>
			<td style="text-align:right;width:50%;">$invoice-total$</td>
		</tr><tr><td style="width:50%;"><span style="color:#A9A9A9;">Bill To</span><br />
			$invoice-accountid:accountname$</td>
			<td style="width:50%;text-align:right;"><span style="color:#808080;"><b>Invoice Date:</b></span>  $custom-currentdate$</td>
		</tr></tbody></table>
 <br /><br />
 
<table border="1" cellpadding="1" cellspacing="1" class="layout" width="991"><tbody><tr><td><strong>Sno.</strong></td>
			<td><strong>Product Name</strong></td>
			<td><strong>Quantity</strong></td>
			<td><strong>List Price</strong></td>
			<td><strong>Total</strong></td>
		</tr><tr><td colspan="5">$productblock_start$</td>
		</tr><tr><td>$productblock_sno$</td>
			<td>$products-productname$</td>
			<td>$products-quantity$</td>
			<td>$products-listprice$</td>
			<td>$products-total$</td>
		</tr><tr><td colspan="5">$productblock_end$</td>
		</tr><tr><td colspan="4" rowspan="1">Items Total</td>
			<td>$pdt-subtotal$</td>
		</tr><tr><td colspan="4" rowspan="1">Discount</td>
			<td>$pdt-discount_amount$</td>
		</tr><tr><td colspan="4" rowspan="1">Tax</td>
			<td>$pdt-tax_totalamount$</td>
		</tr><tr><td colspan="4" rowspan="1"><span class="pull-right">Shipping & Handling Charges</span></td>
			<td>$pdt-s_h_amount$</td>
		</tr><tr><td colspan="4" rowspan="1">Taxes For Shipping and Handling</td>
			<td>$pdt-shtax_totalamount$</td>
		</tr><tr><td colspan="4" rowspan="1">Adjustment</td>
			<td>$pdt-adjustment$</td>
		</tr><tr><td colspan="4" rowspan="1">Grand Total</td>
			<td>$pdt-total$</td>
		</tr></tbody></table><br /><br />
', 1, 'YTo5OntzOjk6ImZpbGVfbmFtZSI7czo3OiJJbnZvaWNlIjtzOjExOiJwYWdlX2Zvcm1hdCI7czoyOiJBNCI7czoxNjoicGFnZV9vcmllbnRhdGlvbiI7czoxOiJQIjtzOjEwOiJtYXJnaW5fdG9wIjtzOjM6IjEwJSI7czoxMzoibWFyZ2luX2JvdHRvbSI7czozOiIxMCUiO3M6MTE6Im1hcmdpbl9sZWZ0IjtzOjM6IjEwJSI7czoxMjoibWFyZ2luX3JpZ2h0IjtzOjM6IjEwJSI7czoxMDoiZGV0YWlsdmlldyI7czoyOiJvbiI7czo4OiJsaXN0dmlldyI7czoyOiJvbiI7fQ==', '', '##Page##'));
			$adb->pquery("insert into jo_vtpdfmaker values(?,?,?,?,?,?,?,?,?)", array(2, 'Quotes', 'Quotes', '', '<table width="985"><tbody><tr><td style="width:50%;"><img alt="" height="79" src="'.$site_URL.'test/logo/JoForce-Logo.png" width="200" /></td>
			<td style="width:50%;"> </td>
		</tr><tr><td style="width:50%;">$company-organizationname$</td>
			<td style="font-size:20px;width:50%;text-align:right;"><b>QUOTE</b></td>
		</tr><tr><td style="width:50%;">$company-address$</td>
			<td style="color:rgb(128,128,128);width:50%;text-align:right;">$quotes-quote_no$</td>
		</tr><tr><td style="width:50%;">$company-country$</td>
			<td style="width:50%;"> </td>
		</tr><tr><td style="width:50%;"> </td>
			<td style="text-align:right;width:50%;"><span style="font-size:12px;"><b>Balance Due</b></span></td>
		</tr><tr><td style="width:50%;"> </td>
			<td style="text-align:right;width:50%;">$quotes-total$</td>
		</tr><tr><td style="width:50%;"><span style="color:#A9A9A9;">Bill To</span><br />
			$quotes-accountid:accountname$</td>
			<td style="width:50%;text-align:right;"><span style="color:#808080;"><b>Quote Date:</b></span>  $custom-currentdate$</td>
		</tr></tbody></table>
 <br /><br />
 
<table border="1" cellpadding="1" cellspacing="1" class="layout" width="991"><tbody><tr><td><strong>Sno.</strong></td>
			<td><strong>Product Name</strong></td>
			<td><strong>Quantity</strong></td>
			<td><strong>List Price</strong></td>
			<td><strong>Total</strong></td>
		</tr><tr><td colspan="5">$productblock_start$</td>
		</tr><tr><td>$productblock_sno$</td>
			<td>$products-productname$</td>
			<td>$products-quantity$</td>
			<td>$products-listprice$</td>
			<td>$products-total$</td>
		</tr><tr><td colspan="5">$productblock_end$</td>
		</tr><tr><td colspan="4" rowspan="1">Items Total</td>
			<td>$pdt-subtotal$</td>
		</tr><tr><td colspan="4" rowspan="1">Discount</td>
			<td>$pdt-discount_amount$</td>
		</tr><tr><td colspan="4" rowspan="1">Tax</td>
			<td>$pdt-tax_totalamount$</td>
		</tr><tr><td colspan="4" rowspan="1"><span class="pull-right">Shipping & Handling Charges</span></td>
			<td>$pdt-s_h_amount$</td>
		</tr><tr><td colspan="4" rowspan="1">Taxes For Shipping and Handling</td>
			<td>$pdt-shtax_totalamount$</td>
		</tr><tr><td colspan="4" rowspan="1">Adjustment</td>
			<td>$pdt-adjustment$</td>
		</tr><tr><td colspan="4" rowspan="1">Grand Total</td>
			<td>$pdt-total$</td>
		</tr></tbody></table><br /><br />
', 1, 'YTo5OntzOjk6ImZpbGVfbmFtZSI7czo2OiJRdW90ZXMiO3M6MTE6InBhZ2VfZm9ybWF0IjtzOjI6IkE0IjtzOjE2OiJwYWdlX29yaWVudGF0aW9uIjtzOjE6IlAiO3M6MTA6Im1hcmdpbl90b3AiO3M6MzoiMTAlIjtzOjEzOiJtYXJnaW5fYm90dG9tIjtzOjM6IjEwJSI7czoxMToibWFyZ2luX2xlZnQiO3M6MzoiMTAlIjtzOjEyOiJtYXJnaW5fcmlnaHQiO3M6MzoiMTAlIjtzOjEwOiJkZXRhaWx2aWV3IjtzOjI6Im9uIjtzOjg6Imxpc3R2aWV3IjtzOjI6Im9uIjt9', '', '##Page##'));
			$adb->pquery("insert into jo_vtpdfmaker values(?,?,?,?,?,?,?,?,?)", array(3, 'PurchaseOrder', 'Purchase Orders', '', '<table width="985"><tbody><tr><td style="width:50%;"><img alt="" height="79" src="'.$site_URL.'test/logo/JoForce-Logo.png" width="200" /></td>
			<td style="width:50%;"> </td>
		</tr><tr><td style="width:50%;">$company-organizationname$</td>
			<td style="font-size:20px;width:50%;text-align:right;"><b>INVOICE</b></td>
		</tr><tr><td style="width:50%;">$company-address$</td>
			<td style="color:rgb(128,128,128);width:50%;text-align:right;">$purchaseorder-purchaseorder_no$</td>
		</tr><tr><td style="width:50%;">$company-country$</td>
			<td style="width:50%;"> </td>
		</tr><tr><td style="width:50%;"> </td>
			<td style="text-align:right;width:50%;"><span style="font-size:12px;"><b>Balance Due</b></span></td>
		</tr><tr><td style="width:50%;"> </td>
			<td style="text-align:right;width:50%;">$purchaseorder-total$</td>
		</tr><tr><td style="width:50%;"><span style="color:#A9A9A9;">Bill To</span><br />
			$purchaseorder-vendorid:vendorname$</td>
			<td style="width:50%;text-align:right;"><span style="color:#808080;"><b>Purchase Order Date:</b></span>  $custom-currentdate$</td>
		</tr></tbody></table>
 <br /><br />
 
<table border="1" cellpadding="1" cellspacing="1" class="layout" width="991"><tbody><tr><td><strong>Sno.</strong></td>
			<td><strong>Product Name</strong></td>
			<td><strong>Quantity</strong></td>
			<td><strong>List Price</strong></td>
			<td><strong>Total</strong></td>
		</tr><tr><td colspan="5">$productblock_start$</td>
		</tr><tr><td>$productblock_sno$</td>
			<td>$products-productname$</td>
			<td>$products-quantity$</td>
			<td>$products-listprice$</td>
			<td>$products-total$</td>
		</tr><tr><td colspan="5">$productblock_end$</td>
		</tr><tr><td colspan="4" rowspan="1">Items Total</td>
			<td>$pdt-subtotal$</td>
		</tr><tr><td colspan="4" rowspan="1">Discount</td>
			<td>$pdt-discount_amount$</td>
		</tr><tr><td colspan="4" rowspan="1">Tax</td>
			<td>$pdt-tax_totalamount$</td>
		</tr><tr><td colspan="4" rowspan="1"><span class="pull-right">Shipping & Handling Charges</span></td>
			<td>$pdt-s_h_amount$</td>
		</tr><tr><td colspan="4" rowspan="1">Taxes For Shipping and Handling</td>
			<td>$pdt-shtax_totalamount$</td>
		</tr><tr><td colspan="4" rowspan="1">Adjustment</td>
			<td>$pdt-adjustment$</td>
		</tr><tr><td colspan="4" rowspan="1">Grand Total</td>
			<td>$pdt-total$</td>
		</tr></tbody></table><br /><br />
', 1, 'YTo5OntzOjk6ImZpbGVfbmFtZSI7czoxMzoiUHVyY2hhc2VPcmRlciI7czoxMToicGFnZV9mb3JtYXQiO3M6MjoiQTQiO3M6MTY6InBhZ2Vfb3JpZW50YXRpb24iO3M6MToiUCI7czoxMDoibWFyZ2luX3RvcCI7czozOiIxMCUiO3M6MTM6Im1hcmdpbl9ib3R0b20iO3M6MzoiMTAlIjtzOjExOiJtYXJnaW5fbGVmdCI7czozOiIxMCUiO3M6MTI6Im1hcmdpbl9yaWdodCI7czozOiIxMCUiO3M6MTA6ImRldGFpbHZpZXciO3M6Mjoib24iO3M6ODoibGlzdHZpZXciO3M6Mjoib24iO30=', '', '##Page##'));
			$adb->pquery("insert into jo_vtpdfmaker values(?,?,?,?,?,?,?,?,?)", array(4, 'SalesOrder', 'Sales Orders', '', '<table width="985"><tbody><tr><td style="width:50%;"><img alt="" height="79" src="'.$site_URL.'test/logo/JoForce-Logo.png" width="200" /></td>
			<td style="width:50%;"> </td>
		</tr><tr><td style="width:50%;">$company-organizationname$</td>
			<td style="font-size:20px;width:50%;text-align:right;"><b>Sales Order</b></td>
		</tr><tr><td style="width:50%;">$company-address$</td>
			<td style="color:rgb(128,128,128);width:50%;text-align:right;">$salesorder-salesorder_no$</td>
		</tr><tr><td style="width:50%;">$company-country$</td>
			<td style="width:50%;"> </td>
		</tr><tr><td style="width:50%;"> </td>
			<td style="text-align:right;width:50%;"><span style="font-size:12px;"><b>Balance Due</b></span></td>
		</tr><tr><td style="width:50%;"> </td>
			<td style="text-align:right;width:50%;">$salesorder-total$</td>
		</tr><tr><td style="width:50%;"><span style="color:#A9A9A9;">Bill To</span><br />
			$salesorder-accountid:accountname$</td>
			<td style="width:50%;text-align:right;"><span style="color:#808080;"><b>Invoice Date:</b></span>  $custom-currentdate$</td>
		</tr></tbody></table>
 <br /><br />
 
<table border="1" cellpadding="1" cellspacing="1" class="layout" width="991"><tbody><tr><td><strong>Sno.</strong></td>
			<td><strong>Product Name</strong></td>
			<td><strong>Quantity</strong></td>
			<td><strong>List Price</strong></td>
			<td><strong>Total</strong></td>
		</tr><tr><td colspan="5">$productblock_start$</td>
		</tr><tr><td>$productblock_sno$</td>
			<td>$products-productname$</td>
			<td>$products-quantity$</td>
			<td>$products-listprice$</td>
			<td>$products-total$</td>
		</tr><tr><td colspan="5">$productblock_end$</td>
		</tr><tr><td colspan="4" rowspan="1">Items Total</td>
			<td>$pdt-subtotal$</td>
		</tr><tr><td colspan="4" rowspan="1">Discount</td>
			<td>$pdt-discount_amount$</td>
		</tr><tr><td colspan="4" rowspan="1">Tax</td>
			<td>$pdt-tax_totalamount$</td>
		</tr><tr><td colspan="4" rowspan="1"><span class="pull-right">Shipping & Handling Charges</span></td>
			<td>$pdt-s_h_amount$</td>
		</tr><tr><td colspan="4" rowspan="1">Taxes For Shipping and Handling</td>
			<td>$pdt-shtax_totalamount$</td>
		</tr><tr><td colspan="4" rowspan="1">Adjustment</td>
			<td>$pdt-adjustment$</td>
		</tr><tr><td colspan="4" rowspan="1">Grand Total</td>
			<td>$pdt-total$</td>
		</tr></tbody></table><br /><br />
', 1, 'YTo5OntzOjk6ImZpbGVfbmFtZSI7czoxMDoiU2FsZXNPcmRlciI7czoxMToicGFnZV9mb3JtYXQiO3M6MjoiQTQiO3M6MTY6InBhZ2Vfb3JpZW50YXRpb24iO3M6MToiUCI7czoxMDoibWFyZ2luX3RvcCI7czozOiIxMCUiO3M6MTM6Im1hcmdpbl9ib3R0b20iO3M6MzoiMTAlIjtzOjExOiJtYXJnaW5fbGVmdCI7czozOiIxMCUiO3M6MTI6Im1hcmdpbl9yaWdodCI7czozOiIxMCUiO3M6MTA6ImRldGFpbHZpZXciO3M6Mjoib24iO3M6ODoibGlzdHZpZXciO3M6Mjoib24iO30=', '', '##Page##'));
			$adb->pquery('insert into jo_vtpdfmaker_seq values(?)', array(4));
                        $getcvId = $adb->pquery('select id from jo_customview_seq', array());
                        $cvId = $adb->query_result($getcvId, 0, 'id');
                        $adb->pquery('insert into jo_customview values (?, ?, ?, ?, ?, ?, ?)', array($cvId, 'All', 1, 0, 'VTPDFMaker', 0, 1));
		} else if($eventType == 'module.disabled') {
			// TODO Handle actions before this module is being uninstalled.
		} else if($eventType == 'module.enabled') {
                        // TODO Handle actions when this module is enabled.
		} else if($eventType == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
		}
 	}
}
