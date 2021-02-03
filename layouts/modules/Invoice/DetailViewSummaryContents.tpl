{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is: vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
 * Contributor(s): JoForce.com
  *
 ********************************************************************************/
-->*}
{strip}
  <div class="">
             
             <div class=" main-content-body-invoice">
                 <div class=" detailview-header-block"> 
                     <div class="card-body"> 
                         <div class="invoice-header"> 
                             <h1 class="invoice-title">Invoice</h1> 
                             <div class="billed-from"> 
                             <h6>{$COMPANY_DETAIL['summary']}</h6>
                             <p>{$COMPANY_DETAIL['address']}<br>{$COMPANY_DETAIL['phone']}<br>{$COMPANY_DETAIL['website']}</p>
                             </div>
                             <!-- billed-from --> 
                         </div>
                         <!-- invoice-header -->
                         <div class="row mg-t-20"> 
                             <div class="col-md-6">
                                 <label class="tx-gray-600">Billed To</label> 
                                 <div class="billed-to"> 
                                     <p>{$BILLING_ADDRESS['address_1']}<br>{$BILLING_ADDRESS['address_2']}<br>{$BILLING_ADDRESS['address_3']}</p>
                                 </div> </div> 
                             <div class="col-md-6"> 
                             <label class="tx-gray-600">Invoice Information</label> 
                             <p class="invoice-info-row">
                                 <span>Invoice No</span>
                                 <span>{$INVOICE_NO}</span>
                             </p>
                             <p class="invoice-info-row">
                                 <span>Issue Date:</span>
                                 <span>{$INVOICE_DATE}</span>
                             </p><p class="invoice-info-row">
                             <span>Due Date:</span> 
                             <span>{$DUE_DATE}</span>
                             </p>
                             </div>
                         </div> 
                         <div class="table-responsive mg-t-40"> 
                             <table class="table table-invoice border text-md-nowrap mb-0"> <thead>
                                 <tr>
                                     <th class="wd-20p">Type</th>
                                     <th class="wd-40p">Description</th>
                                     <th class="tx-center">QNTY</th>
                                     <th class="tx-right">Unit Price</th>
                                     <th class="tx-right">Amount</th>
                                 </tr> 
                                 </thead> 
                                 <tbody>
                                  {include file='ShowLineItemsDetail.tpl'|@vtemplate_path:'Inventory'}
                                     
                                 </tbody>
                             </table> 
                         </div> 
                         <hr class="" style="border-top: 0px"> 
                         <a href="#" class="btn btn-danger float-right mt-3 ml-2 printDiv" > 
                             <i class="fa fa-print" aria-hidden="true"></i></i>Print 
                         </a>
                         <a href="javascript:PDFMaker_Helper_Js.sendEmail('{$RECORDID}')" class="btn btn-success float-right mt-3">
                             <i class="fa fa-paper-plane" aria-hidden="true"></i>Send Invoice </a>
                     </div> 
                 </div> 
             </div>
      </div>
    <form id="detailView" method="POST">
        {include file='SummaryViewWidgets.tpl'|vtemplate_path:$MODULE_NAME}
    </form>
{/strip}
