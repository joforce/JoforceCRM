<?php

class PDFMaker {

	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
		global $adb, $site_URL;
 		if($eventType == 'module.postinstall') {
			// TODO Handle actions after this module is installed.

			$adb->pquery("CREATE TABLE `jo_pdfmakersettings` (
                                                        `id` int(10) NOT NULL AUTO_INCREMENT,
                                                        `version` varchar(10) DEFAULT NULL,
                                                        PRIMARY KEY (`id`)
                                                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8",array());
                        $adb->pquery('insert into jo_pdfmakersettings (id, version) values (1, ?)', array('0.1'));

			include_once('vtlib/Head/Module.php');
			$moduleInstance = Head_Module::getInstance('PDFMaker');
			$moduleInstance->addLink('HEADERSCRIPT', 'HEADERSCRIPT', 'layouts/modules/PDFMaker/resources/Helper.js');

			$adb->pquery("insert into jo_pdfmaker values(?,?,?,?,?,?,?,?,?)", array(1, 'Invoice', 'Invoice', '', '
			<table width="985">
	<tbody>
		<tr>
			<td style="width:50%;"><img alt="" height="79" src="$image_URL$" width="200" /></td>
			<td style="width:50%;font-size:20px;text-align:right;">
                        <h3>INVOICE</h3>
                        </td>
		</tr>
		<tr>
			<td style="width:50%;"><b>$company-organizationname$</b></td>
			<td style="font-size:20px;width:50%;text-align:right;color:rgb(128,128,128);">$invoice-invoice_no$</td>
		</tr>
		<tr>
			<td style="width:50%;">$company-address$</td>
			<td style="width:50%;"></td>
		</tr>
		<tr>
			<td style="width:50%;">$company-country$</td>
			<td style="width:50%;"></td>
		</tr>
		<tr>
			<td style="width:50%;"></td>
			<td style="text-align:right;width:50%;"><span style="font-size:12px;"><b>Balance Due</b></span></td>
		</tr>
		<tr>
			<td style="width:50%;"></td>
			<td style="text-align:right;width:50%;">$invoice-total$</td>
		</tr>
	</tbody>
</table>
<table width="985">
        <tbody>
                <tr>
                        <td style="width:50%;"><span style="color:#A9A9A9;font-size:23px;">Bill To:</span>

                        <p style="font-size:23px;">$invoice-accountid:accountname$</p>

                        <p style="font-size:23px;">$invoice-bill_street$</p>

                        <p style="font-size:23px;">$invoice-bill_city$</p>

                        <p style="font-size:23px;">$invoice-bill_country$</p>
                        </td>
                        <td style="text-align:right;width:100%;font-size:23px;">
                        <p style="text-align:right;width:100%;font-size:23px;"><span style="color:#808080;text-align:left;font-size:23px;"><b>Sales Date:</b></span> $custom-currentdate$</p>

                        <p style="text-align:right;width:100%;font-size:23px;"><span style="color:#808080;text-align:left;font-size:23px;"><b>Due Date:</b></span>$invoice-duedate$</p>
                        </td>
                </tr>
        </tbody>
</table>
<br />

<table border="0" cellpadding="1" cellspacing="1" class="layout" style="border-collapse:collapse;" width="991">
	<tbody>
		<tr style="background:#000;">
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>Sno.</strong></font></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>Product Name</strong></font></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>Quantity</strong></font></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>List Price</strong></font></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>Total</strong></font></td>
		</tr>
		<tr>
			<td colspan="5" style="border-bottom:1px solid #ccc;padding:4mm;">$productblock_start$</td>
		</tr>
		<tr>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$productblock_sno$</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$products-productname$</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$products-quantity$</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$products-listprice$</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$products-total$</td>
		</tr>
		<tr>
			<td colspan="5" style="border-bottom:1px solid #ccc;padding:4mm;">$productblock_end$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Items Total</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-subtotal$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Discount</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-discount_amount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Tax</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-tax_totalamount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;"><span class="pull-right">Shipping &amp; Handling Charges</span></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-s_h_amount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Taxes For Shipping and Handling</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-shtax_totalamount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Adjustment</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-adjustment$</td>
		</tr>
		<tr style="height:10px;">
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Grand Total <span><b>( in </b><b>$invoice-currency_id$</b><b> )</b></span></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-total$</td>
		</tr>
	</tbody>
</table>
<br />',1, 'YTo5OntzOjk6ImZpbGVfbmFtZSI7czo3OiJJbnZvaWNlIjtzOjExOiJwYWdlX2Zvcm1hdCI7czoyOiJBNCI7czoxNjoicGFnZV9vcmllbnRhdGlvbiI7czoxOiJQIjtzOjEwOiJtYXJnaW5fdG9wIjtzOjM6IjEwJSI7czoxMzoibWFyZ2luX2JvdHRvbSI7czozOiIxMCUiO3M6MTE6Im1hcmdpbl9sZWZ0IjtzOjM6IjEwJSI7czoxMjoibWFyZ2luX3JpZ2h0IjtzOjM6IjEwJSI7czoxMDoiZGV0YWlsdmlldyI7czoyOiJvbiI7czo4OiJsaXN0dmlldyI7czoyOiJvbiI7fQ==', '', '##Page##'));
			$adb->pquery("insert into jo_pdfmaker values(?,?,?,?,?,?,?,?,?)", array(2, 'Quotes', 'Quotes', '', '
			<table width="985">
		<tbody>
		<tr>
			<td style="width:50%;"><img alt="" height="79" src="$image_URL$" width="200" /></td>
			<td style="width:50%;font-size:20px;text-align:right;">
                        <h3>QUOTE</h3>
                        </td>
		</tr>
		<tr>
			<td style="width:50%;"><b>$company-organizationname$</b></td>
			<td style="font-size:20px;width:50%;text-align:right;color:rgb(128,128,128);">$quotes-quote_no$</td>
		</tr>
		<tr>
			<td style="width:50%;">$company-address$</td>
			<td style="width:50%;"></td>
		</tr>
		<tr>
			<td style="width:50%;">$company-country$</td>
			<td style="width:50%;"></td>
		</tr>
		<tr>
			<td style="width:50%;"></td>
			<td style="text-align:right;width:50%;"><span style="font-size:12px;"><b>Balance Due</b></span></td>
		</tr>
		<tr>
			<td style="width:50%;"></td>
			<td style="text-align:right;width:50%;">$quotes-total$</td>
		</tr>
	</tbody>
</table>
<table width="985">
        <tbody>
                <tr>
                        <td style="width:50%;"><span style="color:#A9A9A9;font-size:23px;">Bill To:</span>

                        <p style="font-size:23px;">$quotes-accountid:accountname$</p>

                        <p style="font-size:23px;">$quotes-bill_street$</p>

                        <p style="font-size:23px;">$quotes-bill_city$</p>

                        <p style="font-size:23px;">$quotes-bill_country$</p>
                        </td>
                        <td style="text-align:right;width:100%;font-size:23px;">
                        <p style="text-align:right;width:100%;font-size:23px;"><span style="color:#808080;text-align:left;font-size:23px;"><b>Quote Date:</b></span> $custom-currentdate$</p>

                        </td>
                </tr>
        </tbody>
</table>
<br />

<br />
<table border="0" cellpadding="1" cellspacing="1" class="layout" width="991" style = "border-collapse: collapse;">
	<tbody>
		<tr style="background:#000;" >
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;"><font color="#fff"><strong>Sno.</strong></font></td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;"><font color="#fff"><strong>Product Name</strong></font></td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;"><font color="#fff"><strong>Quantity</strong></font></td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;"><font color="#fff"><strong>List Price</strong></font></td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;"><font color="#fff"><strong>Total</strong></font></td>
		</tr>
		<tr>
			<td colspan="5" style="border-bottom: 1px solid #ccc;padding: 4mm;">$productblock_start$</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;">$productblock_sno$</td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;">$products-productname$</td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;">$products-quantity$</td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;">$products-listprice$</td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;">$products-total$</td>
		</tr>
		<tr>
			<td colspan="5" style="border-bottom: 1px solid #ccc;padding: 4mm;">$productblock_end$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom: 1px solid #ccc;padding: 4mm;">Items Total</td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;">$pdt-subtotal$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom: 1px solid #ccc;padding: 4mm;">Discount</td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;">$pdt-discount_amount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom: 1px solid #ccc;padding: 4mm;">Tax</td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;">$pdt-tax_totalamount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom: 1px solid #ccc;padding: 4mm;"><span class="pull-right">Shipping &amp; Handling Charges</span></td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;">$pdt-s_h_amount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom: 1px solid #ccc;padding: 4mm;">Taxes For Shipping and Handling</td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;">$pdt-shtax_totalamount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom: 1px solid #ccc;padding: 4mm;">Adjustment</td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;">$pdt-adjustment$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom: 1px solid #ccc;padding: 4mm;">Grand Total<span><b>( in </b><b>$quotes-currency_id$</b><b> )</b></span></td>
			<td style="border-bottom: 1px solid #ccc;padding: 4mm;">$pdt-total$</td>
		</tr>
	</tbody>
</table>
<br />
', 1, 'YTo5OntzOjk6ImZpbGVfbmFtZSI7czo2OiJRdW90ZXMiO3M6MTE6InBhZ2VfZm9ybWF0IjtzOjI6IkE0IjtzOjE2OiJwYWdlX29yaWVudGF0aW9uIjtzOjE6IlAiO3M6MTA6Im1hcmdpbl90b3AiO3M6MzoiMTAlIjtzOjEzOiJtYXJnaW5fYm90dG9tIjtzOjM6IjEwJSI7czoxMToibWFyZ2luX2xlZnQiO3M6MzoiMTAlIjtzOjEyOiJtYXJnaW5fcmlnaHQiO3M6MzoiMTAlIjtzOjEwOiJkZXRhaWx2aWV3IjtzOjI6Im9uIjtzOjg6Imxpc3R2aWV3IjtzOjI6Im9uIjt9', '', '##Page##'));
			$adb->pquery("insert into jo_pdfmaker values(?,?,?,?,?,?,?,?,?)", array(3, 'PurchaseOrder', 'PurchaseOrder', '', '<table width="985">
	<tbody>
		<tr>
			<td style="width:50%;"><img alt="" height="79" src="$image_URL$" width="200" /></td>
			<td style="width:50%;font-size:20px;text-align:right;">
                        <h3>Purchase Order</h3>
                        </td>
		</tr>
		<tr>
			<td style="width:50%;"><b>$company-organizationname$</b></td>
			<td style="font-size:20px;width:50%;text-align:right;color:rgb(128,128,128);">$purchaseorder-purchaseorder_no$</td>
		</tr>
		<tr>
			<td style="width:50%;">$company-address$</td>
			<td style="width:50%;"></td>
		</tr>
		<tr>
			<td style="width:50%;">$company-country$</td>
			<td style="width:50%;"></td>
		</tr>
		<tr>
			<td style="width:50%;"></td>
			<td style="text-align:right;width:50%;"><span style="font-size:12px;"><b>Balance Due</b></span></td>
		</tr>
		<tr>
			<td style="width:50%;"></td>
			<td style="text-align:right;width:50%;">$purchaseorder-total$</td>
		</tr>
	</tbody>
</table>
<br />
<table width="985">
        <tbody>
                <tr>
                        <td style="width:50%;"><span style="color:#A9A9A9;font-size:23px;">Bill To:</span>

                        <p style="font-size:23px;">$purchaseorder-vendorid:vendorname$</p>

                        <p style="font-size:23px;">$purchaseorder-bill_street$</p>

                        <p style="font-size:23px;">$purchaseorder-bill_city$</p>

                        <p style="font-size:23px;">$purchaseorder-bill_country$</p>
                        </td>
                        <td style="text-align:right;width:100%;font-size:23px;">
                        <p style="text-align:right;width:100%;font-size:23px;"><span style="color:#808080;text-align:left;font-size:23px;"><b>Purchase Date:</b></span> $custom-currentdate$</p>

                        <p style="text-align:right;width:100%;font-size:23px;"><span style="color:#808080;text-align:left;font-size:23px;"><b>Due Date:</b></span> $purchaseorder-duedate$</p>
                        </td>
                </tr>
        </tbody>
</table>
<br />


<table border="0" cellpadding="1" cellspacing="1" class="layout" style="border-collapse:collapse;" width="991">
	<tbody>
		<tr style="background:#000;">
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>Sno.</strong></font></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>Product Name</strong></font></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>Quantity</strong></font></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>List Price</strong></font></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>Total</strong></font></td>
		</tr>
		<tr>
			<td colspan="5" style="border-bottom:1px solid #ccc;padding:4mm;">$productblock_start$</td>
		</tr>
		<tr>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$productblock_sno$</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$products-productname$</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$products-quantity$</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$products-listprice$</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$products-total$</td>
		</tr>
		<tr>
			<td colspan="5" style="border-bottom:1px solid #ccc;padding:4mm;">$productblock_end$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Items Total</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-subtotal$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Discount</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-discount_amount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Tax</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-tax_totalamount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1"><span class="pull-right" style="border-bottom:1px solid #ccc;padding:4mm;">Shipping &amp; Handling Charges</span></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-s_h_amount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Taxes For Shipping and Handling</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-shtax_totalamount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Adjustment</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-adjustment$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Grand Total<span><b>( in </b> <b>$purchaseorder-currency_id$</b> <b> ) </b></span></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-total$</td>
		</tr>
	</tbody>
</table>
<br />
', 1, 'YTo5OntzOjk6ImZpbGVfbmFtZSI7czoxMzoiUHVyY2hhc2VPcmRlciI7czoxMToicGFnZV9mb3JtYXQiO3M6MjoiQTQiO3M6MTY6InBhZ2Vfb3JpZW50YXRpb24iO3M6MToiUCI7czoxMDoibWFyZ2luX3RvcCI7czozOiIxMCUiO3M6MTM6Im1hcmdpbl9ib3R0b20iO3M6MzoiMTAlIjtzOjExOiJtYXJnaW5fbGVmdCI7czozOiIxMCUiO3M6MTI6Im1hcmdpbl9yaWdodCI7czozOiIxMCUiO3M6MTA6ImRldGFpbHZpZXciO3M6Mjoib24iO3M6ODoibGlzdHZpZXciO3M6Mjoib24iO30=', '', '##Page##'));
			$adb->pquery("insert into jo_pdfmaker values(?,?,?,?,?,?,?,?,?)", array(4, 'SalesOrder', 'SalesOrder', '', '
			<table width="985">
	<tbody>
		<tr>
			<td style="width:50%;"><img alt="" height="79" src="$image_URL$" width="200" /></td>
			<td style="width:50%;font-size:20px;text-align:right;">
			<h3>Sales Order</h3>
			</td>
		</tr>
		<tr>
			<td style="width:50%;"><b>$company-organizationname$</b></td>
			<td style="font-size:20px;width:50%;text-align:right;color:rgb(128,128,128);">$salesorder-salesorder_no$</td>
		</tr>
		<tr>
			<td style="width:50%;">$company-address$</td>
			<td style="width:50%;"></td>
		</tr>
		<tr>
			<td style="width:50%;">$company-country$</td>
			<td style="width:50%;"></td>
		</tr>
		<tr>
			<td style="width:50%;"></td>
			<td style="text-align:right;width:50%;"><span style="font-size:15px;"><b>Balance Due</b></span></td>
		</tr>
		<tr>
			<td style="width:50%;"></td>
			<td style="text-align:right;width:50%;">$salesorder-total$</td>
		</tr>
	</tbody>
</table>

<table width="985">
	<tbody>
		<tr>
			<td style="width:50%;"><span style="color:#A9A9A9;font-size:23px;">Bill To:</span>

			<p style="font-size:23px;">$salesorder-accountid:accountname$</p>

			<p style="font-size:23px;">$salesorder-bill_street$</p>

			<p style="font-size:23px;">$salesorder-bill_city$</p>

			<p style="font-size:23px;">$salesorder-bill_country$</p>
			</td>
			<td style="text-align:right;width:100%;font-size:23px;">
			<p style="text-align:right;width:100%;font-size:23px;"><span style="color:#808080;text-align:left;font-size:23px;"><b>Invoice Date:</b></span> $custom-currentdate$</p>

			<p style="text-align:right;width:100%;font-size:23px;"><span style="color:#808080;text-align:left;font-size:23px;"><b>Due Date:</b></span>$salesorder-duedate$</p>
			</td>
		</tr>
	</tbody>
</table>
<br />
<table border="0" cellpadding="1" cellspacing="1" class="layout" style="border-collapse:collapse;" width="991">
	<tbody>
		<tr style="background:#000;">
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>Sno.</strong></font></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>Product Name</strong></font></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>Quantity</strong></font></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>List Price</strong></font></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;"><font color="#ffffff"><strong>Total</strong></font></td>
		</tr>
		<tr>
			<td colspan="5" style="border-bottom:1px solid #ccc;padding:4mm;">$productblock_start$</td>
		</tr>
		<tr>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$productblock_sno$</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$products-productname$</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$products-quantity$</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$products-listprice$</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$products-total$</td>
		</tr>
		<tr>
			<td colspan="5" style="border-bottom:1px solid #ccc;padding:4mm;">$productblock_end$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Items Total</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-subtotal$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Discount</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-discount_amount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Tax</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-tax_totalamount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;"><span class="pull-right">Shipping &amp; Handling Charges</span></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-s_h_amount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Taxes For Shipping and Handling</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-shtax_totalamount$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Adjustment</td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-adjustment$</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="border-bottom:1px solid #ccc;padding:4mm;">Grand Total<span><b>( in </b><b>$salesorder-currency_id$</b><b> )</b></span></td>
			<td style="border-bottom:1px solid #ccc;padding:4mm;">$pdt-total$</td>
		</tr>
	</tbody>
</table>
<br />
', 1, 'YTo5OntzOjk6ImZpbGVfbmFtZSI7czoxMDoiU2FsZXNPcmRlciI7czoxMToicGFnZV9mb3JtYXQiO3M6MjoiQTQiO3M6MTY6InBhZ2Vfb3JpZW50YXRpb24iO3M6MToiUCI7czoxMDoibWFyZ2luX3RvcCI7czozOiIxMCUiO3M6MTM6Im1hcmdpbl9ib3R0b20iO3M6MzoiMTAlIjtzOjExOiJtYXJnaW5fbGVmdCI7czozOiIxMCUiO3M6MTI6Im1hcmdpbl9yaWdodCI7czozOiIxMCUiO3M6MTA6ImRldGFpbHZpZXciO3M6Mjoib24iO3M6ODoibGlzdHZpZXciO3M6Mjoib24iO30=', '', '##Page##'));
			$adb->pquery('insert into jo_pdfmaker_seq values(?)', array(4));
                        $getcvId = $adb->pquery('select id from jo_customview_seq', array());
                        $cvId = $adb->query_result($getcvId, 0, 'id');
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
