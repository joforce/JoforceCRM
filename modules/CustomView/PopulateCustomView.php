<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
*
 ********************************************************************************/
require_once('include/database/PearDatabase.php');

$customviews = Array(Array('viewname'=>'All',
			   'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
			   'cvmodule'=>'Leads','stdfilterid'=>'','advfilterid'=>''),

		     Array('viewname'=>'Hot Leads',
			   'setdefault'=>'0','setmetrics'=>'1','status'=>'3','userid'=>'1',
			   'cvmodule'=>'Leads','stdfilterid'=>'','advfilterid'=>'0'),

		     Array('viewname'=>'This Month Leads',
			   'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
			   'cvmodule'=>'Leads','stdfilterid'=>'0','advfilterid'=>''),

		     Array('viewname'=>'All',
                           'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                           'cvmodule'=>'Accounts','stdfilterid'=>'','advfilterid'=>''),

		     Array('viewname'=>'Prospect Accounts',
                           'setdefault'=>'0','setmetrics'=>'1','status'=>'3','userid'=>'1',
                           'cvmodule'=>'Accounts','stdfilterid'=>'','advfilterid'=>'1'),

		     Array('viewname'=>'New This Week',
                           'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                           'cvmodule'=>'Accounts','stdfilterid'=>'1','advfilterid'=>''),

		     Array('viewname'=>'All',
                           'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                           'cvmodule'=>'Contacts','stdfilterid'=>'','advfilterid'=>''),

		     Array('viewname'=>'Contacts Address',
                           'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                           'cvmodule'=>'Contacts','stdfilterid'=>'','advfilterid'=>''),

		     Array('viewname'=>'Todays Birthday',
                           'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                           'cvmodule'=>'Contacts','stdfilterid'=>'2','advfilterid'=>''),

		     Array('viewname'=>'All',
                           'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                           'cvmodule'=>'Potentials','stdfilterid'=>'','advfilterid'=>''),

		     Array('viewname'=>'Potentials Won',
                           'setdefault'=>'0','setmetrics'=>'1','status'=>'3','userid'=>'1',
                           'cvmodule'=>'Potentials','stdfilterid'=>'','advfilterid'=>'2'),

		     Array('viewname'=>'Prospecting',
                           'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                           'cvmodule'=>'Potentials','stdfilterid'=>'','advfilterid'=>'3'),

		     Array('viewname'=>'All',
                           'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                           'cvmodule'=>'HelpDesk','stdfilterid'=>'','advfilterid'=>''),

	             Array('viewname'=>'Open Tickets',
                           'setdefault'=>'0','setmetrics'=>'1','status'=>'3','userid'=>'1',
                           'cvmodule'=>'HelpDesk','stdfilterid'=>'','advfilterid'=>'4'),

		     Array('viewname'=>'High Prioriy Tickets',
                           'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                           'cvmodule'=>'HelpDesk','stdfilterid'=>'','advfilterid'=>'5'),

		     Array('viewname'=>'All',
                           'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                           'cvmodule'=>'Quotes','stdfilterid'=>'','advfilterid'=>''),

		     Array('viewname'=>'Open Quotes',
                           'setdefault'=>'0','setmetrics'=>'1','status'=>'3','userid'=>'1',
                           'cvmodule'=>'Quotes','stdfilterid'=>'','advfilterid'=>'6'),

		     Array('viewname'=>'Rejected Quotes',
                           'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                           'cvmodule'=>'Quotes','stdfilterid'=>'','advfilterid'=>'7'),

		    Array('viewname'=>'All',
                          'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                          'cvmodule'=>'Calendar','stdfilterid'=>'','advfilterid'=>''),

		    Array('viewname'=>'All',
                          'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                          'cvmodule'=>'Emails','stdfilterid'=>'','advfilterid'=>''),

		    Array('viewname'=>'All',
                          'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                          'cvmodule'=>'Invoice','stdfilterid'=>'','advfilterid'=>''),

		    Array('viewname'=>'All',
                          'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                          'cvmodule'=>'Documents','stdfilterid'=>'','advfilterid'=>''),

	            Array('viewname'=>'All',
                          'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                          'cvmodule'=>'PriceBooks','stdfilterid'=>'','advfilterid'=>''),

	            Array('viewname'=>'All',
                          'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                          'cvmodule'=>'Products','stdfilterid'=>'','advfilterid'=>''),

	            Array('viewname'=>'All',
                          'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                          'cvmodule'=>'PurchaseOrder','stdfilterid'=>'','advfilterid'=>''),

	            Array('viewname'=>'All',
                          'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                          'cvmodule'=>'SalesOrder','stdfilterid'=>'','advfilterid'=>''),

	            Array('viewname'=>'All',
                          'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                          'cvmodule'=>'Vendors','stdfilterid'=>'','advfilterid'=>''),

		    Array('viewname'=>'All',
                          'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                          'cvmodule'=>'Faq','stdfilterid'=>'','advfilterid'=>''),

		    Array('viewname'=>'All',
                          'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                          'cvmodule'=>'Campaigns','stdfilterid'=>'','advfilterid'=>''),

		    Array('viewname'=>'All',
                          'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
			  'cvmodule'=>'Webmails','stdfilterid'=>'','advfilterid'=>''),

		    Array('viewname'=>'Drafted FAQ',
                          'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                          'cvmodule'=>'Faq','stdfilterid'=>'','advfilterid'=>'8'),

		    Array('viewname'=>'Published FAQ',
                          'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
			  'cvmodule'=>'Faq','stdfilterid'=>'','advfilterid'=>'9'),

	            Array('viewname'=>'Open Purchase Orders',
                          'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                          'cvmodule'=>'PurchaseOrder','stdfilterid'=>'','advfilterid'=>'10'),

	            Array('viewname'=>'Received Purchase Orders',
                          'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                          'cvmodule'=>'PurchaseOrder','stdfilterid'=>'','advfilterid'=>'11'),

		    Array('viewname'=>'Open Invoices',
                          'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
			  'cvmodule'=>'Invoice','stdfilterid'=>'','advfilterid'=>'12'),

		    Array('viewname'=>'Paid Invoices',
                          'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
			  'cvmodule'=>'Invoice','stdfilterid'=>'','advfilterid'=>'13'),

	            Array('viewname'=>'Pending Sales Orders',
                          'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                          'cvmodule'=>'SalesOrder','stdfilterid'=>'','advfilterid'=>'14'),
              Array('viewname'=>'All',
                          'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1','cvmodule'=>'VTPDFMaker'
      ),
		    );


$cvcolumns = Array(Array('jo_leaddetails:lead_no:lead_no:Leads_Lead_No:V',
						 'jo_leaddetails:lastname:lastname:Leads_Last_Name:V',
                         'jo_leaddetails:firstname:firstname:Leads_First_Name:V',
                         'jo_leaddetails:company:company:Leads_Company:V',
			 'jo_leadaddress:phone:phone:Leads_Phone:V',
                         'jo_leadsubdetails:website:website:Leads_Website:V',
                         'jo_leaddetails:email:email:Leads_Email:E',
			 'jo_crmentity:smownerid:assigned_user_id:Leads_Assigned_To:V'),

	           Array('jo_leaddetails:firstname:firstname:Leads_First_Name:V',
                         'jo_leaddetails:lastname:lastname:Leads_Last_Name:V',
                         'jo_leaddetails:company:company:Leads_Company:V',
                         'jo_leaddetails:leadsource:leadsource:Leads_Lead_Source:V',
                         'jo_leadsubdetails:website:website:Leads_Website:V',
                         'jo_leaddetails:email:email:Leads_Email:E'),

		   Array('jo_leaddetails:firstname:firstname:Leads_First_Name:V',
                         'jo_leaddetails:lastname:lastname:Leads_Last_Name:V',
                         'jo_leaddetails:company:company:Leads_Company:V',
                         'jo_leaddetails:leadsource:leadsource:Leads_Lead_Source:V',
                         'jo_leadsubdetails:website:website:Leads_Website:V',
                         'jo_leaddetails:email:email:Leads_Email:E'),

		  		 Array('jo_account:account_no:account_no:Accounts_Account_No:V',
				 		'jo_account:accountname:accountname:Accounts_Account_Name:V',
                         'jo_accountbillads:bill_city:bill_city:Accounts_City:V',
                         'jo_account:website:website:Accounts_Website:V',
                         'jo_account:phone:phone:Accounts_Phone:V',
                         'jo_crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),

		   Array('jo_account:accountname:accountname:Accounts_Account_Name:V',
			 'jo_account:phone:phone:Accounts_Phone:V',
			 'jo_account:website:website:Accounts_Website:V',
			 'jo_account:rating:rating:Accounts_Rating:V',
			 'jo_crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),

		   Array('jo_account:accountname:accountname:Accounts_Account_Name:V',
                         'jo_account:phone:phone:Accounts_Phone:V',
                         'jo_account:website:website:Accounts_Website:V',
                         'jo_accountbillads:bill_city:bill_city:Accounts_City:V',
                         'jo_crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),

		   Array('jo_contactdetails:contact_no:contact_no:Contacts_Contact_Id:V',
		   			'jo_contactdetails:firstname:firstname:Contacts_First_Name:V',
                         'jo_contactdetails:lastname:lastname:Contacts_Last_Name:V',
                         'jo_contactdetails:title:title:Contacts_Title:V',
						 'jo_contactdetails:accountid:account_id:Contacts_Account_Name:I',
                         'jo_contactdetails:email:email:Contacts_Email:E',
                         'jo_contactdetails:phone:phone:Contacts_Office_Phone:V',
			 'jo_crmentity:smownerid:assigned_user_id:Contacts_Assigned_To:V'),

		   Array('jo_contactdetails:firstname:firstname:Contacts_First_Name:V',
                         'jo_contactdetails:lastname:lastname:Contacts_Last_Name:V',
                         'jo_contactaddress:mailingstreet:mailingstreet:Contacts_Mailing_Street:V',
                         'jo_contactaddress:mailingcity:mailingcity:Contacts_Mailing_City:V',
                         'jo_contactaddress:mailingstate:mailingstate:Contacts_Mailing_State:V',
			 'jo_contactaddress:mailingzip:mailingzip:Contacts_Mailing_Zip:V',
			 'jo_contactaddress:mailingcountry:mailingcountry:Contacts_Mailing_Country:V'),

		   Array('jo_contactdetails:firstname:firstname:Contacts_First_Name:V',
                 'jo_contactdetails:lastname:lastname:Contacts_Last_Name:V',
                 'jo_contactdetails:title:title:Contacts_Title:V',
                 'jo_contactdetails:accountid:account_id:Contacts_Account_Name:I',
                 'jo_contactdetails:email:email:Contacts_Email:E',
				 'jo_contactsubdetails:otherphone:otherphone:Contacts_Phone:V',
				 'jo_crmentity:smownerid:assigned_user_id:Contacts_Assigned_To:V'),

		   Array('jo_potential:potential_no:potential_no:Potentials_Potential_No:V',
 	   			 'jo_potential:potentialname:potentialname:Potentials_Potential_Name:V',
                 'jo_potential:related_to:related_to:Potentials_Related_To:V',
                 'jo_potential:sales_stage:sales_stage:Potentials_Sales_Stage:V',
                 'jo_potential:leadsource:leadsource:Potentials_Lead_Source:V',
                 'jo_potential:closingdate:closingdate:Potentials_Expected_Close_Date:D',
                 'jo_crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),

	       Array('jo_potential:potentialname:potentialname:Potentials_Potential_Name:V',
	             'jo_potential:related_to:related_to:Potentials_Related_To:V',
	             'jo_potential:amount:amount:Potentials_Amount:N',
	             'jo_potential:closingdate:closingdate:Potentials_Expected_Close_Date:D',
	             'jo_crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),

		   Array('jo_potential:potentialname:potentialname:Potentials_Potential_Name:V',
                 'jo_potential:related_to:related_to:Potentials_Related_To:V',
                 'jo_potential:amount:amount:Potentials_Amount:N',
                 'jo_potential:leadsource:leadsource:Potentials_Lead_Source:V',
                 'jo_potential:closingdate:closingdate:Potentials_Expected_Close_Date:D',
                 'jo_crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),

		   Array(//'jo_crmentity:crmid::HelpDesk_Ticket_ID:I',
		   				'jo_troubletickets:ticket_no:ticket_no:HelpDesk_Ticket_No:V',
			 'jo_troubletickets:title:ticket_title:HelpDesk_Title:V',
                         'jo_troubletickets:parent_id:parent_id:HelpDesk_Related_To:I',
                         'jo_troubletickets:status:ticketstatus:HelpDesk_Status:V',
                         'jo_troubletickets:priority:ticketpriorities:HelpDesk_Priority:V',
                         'jo_crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),

		   Array('jo_troubletickets:title:ticket_title:HelpDesk_Title:V',
                         'jo_troubletickets:parent_id:parent_id:HelpDesk_Related_To:I',
                         'jo_troubletickets:priority:ticketpriorities:HelpDesk_Priority:V',
                         'jo_troubletickets:product_id:product_id:HelpDesk_Product_Name:I',
                         'jo_crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),

		   Array('jo_troubletickets:title:ticket_title:HelpDesk_Title:V',
                         'jo_troubletickets:parent_id:parent_id:HelpDesk_Related_To:I',
                         'jo_troubletickets:status:ticketstatus:HelpDesk_Status:V',
                         'jo_troubletickets:product_id:product_id:HelpDesk_Product_Name:I',
                         'jo_crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),

		   Array('jo_quotes:quote_no:quote_no:Quotes_Quote_No:V',
			 'jo_quotes:subject:subject:Quotes_Subject:V',
                         'jo_quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                         'jo_quotes:potentialid:potential_id:Quotes_Potential_Name:I',
						 'jo_quotes:accountid:account_id:Quotes_Account_Name:I',
                         'jo_quotes:total:hdnGrandTotal:Quotes_Total:I',
			 'jo_crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V'),

		   Array('jo_quotes:subject:subject:Quotes_Subject:V',
                         'jo_quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                         'jo_quotes:potentialid:potential_id:Quotes_Potential_Name:I',
						'jo_quotes:accountid:account_id:Quotes_Account_Name:I',
                         'jo_quotes:validtill:validtill:Quotes_Valid_Till:D',
			 'jo_crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V'),

		   Array('jo_quotes:subject:subject:Quotes_Subject:V',
                         'jo_quotes:potentialid:potential_id:Quotes_Potential_Name:I',
						'jo_quotes:accountid:account_id:Quotes_Account_Name:I',
                         'jo_quotes:validtill:validtill:Quotes_Valid_Till:D',
                         'jo_crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V'),

		   Array('jo_activity:status:taskstatus:Calendar_Status:V',
                         'jo_activity:activitytype:activitytype:Calendar_Type:V',
                         'jo_activity:subject:subject:Calendar_Subject:V',
                         'jo_seactivityrel:crmid:parent_id:Calendar_Related_to:V',
                         'jo_activity:date_start:date_start:Calendar_Start_Date:D',
                         'jo_activity:due_date:due_date:Calendar_End_Date:D',
                         'jo_crmentity:smownerid:assigned_user_id:Calendar_Assigned_To:V'),

		   Array('jo_activity:subject:subject:Emails_Subject:V',
       			 'jo_emaildetails:to_email:saved_toid:Emails_To:V',
                 	 'jo_activity:date_start:date_start:Emails_Date_Sent:D'),

		   Array('jo_invoice:invoice_no:invoice_no:Invoice_Invoice_No:V',
                         'jo_invoice:subject:subject:Invoice_Subject:V',
                         'jo_invoice:salesorderid:salesorder_id:Invoice_Sales_Order:I',
                         'jo_invoice:invoicestatus:invoicestatus:Invoice_Status:V',
                         'jo_invoice:total:hdnGrandTotal:Invoice_Total:I',
                         'jo_crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V'),

		  Array('jo_notes:note_no:note_no:Notes_Note_No:V',
		  				'jo_notes:title:notes_title:Notes_Title:V',
                        'jo_notes:filename:filename:Notes_File:V',
                        'jo_crmentity:modifiedtime:modifiedtime:Notes_Modified_Time:DT',
		  				'jo_crmentity:smownerid:assigned_user_id:Notes_Assigned_To:V'),

		  Array('jo_pricebook:pricebook_no:pricebook_no:PriceBooks_PriceBook_No:V',
					  'jo_pricebook:bookname:bookname:PriceBooks_Price_Book_Name:V',
                        'jo_pricebook:active:active:PriceBooks_Active:V',
                        'jo_pricebook:currency_id:currency_id:PriceBooks_Currency:I'),

		  Array('jo_products:product_no:product_no:Products_Product_No:V',
		  		'jo_products:productname:productname:Products_Product_Name:V',
                        'jo_products:productcode:productcode:Products_Part_Number:V',
                        'jo_products:commissionrate:commissionrate:Products_Commission_Rate:V',
			'jo_products:qtyinstock:qtyinstock:Products_Quantity_In_Stock:V',
                        'jo_products:qty_per_unit:qty_per_unit:Products_Qty/Unit:V',
                        'jo_products:unit_price:unit_price:Products_Unit_Price:V'),

		  Array('jo_purchaseorder:purchaseorder_no:purchaseorder_no:PurchaseOrder_PurchaseOrder_No:V',
                        'jo_purchaseorder:subject:subject:PurchaseOrder_Subject:V',
                        'jo_purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:I',
                        'jo_purchaseorder:tracking_no:tracking_no:PurchaseOrder_Tracking_Number:V',
						'jo_purchaseorder:total:hdnGrandTotal:PurchaseOrder_Total:V',
                        'jo_crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V'),

	          Array('jo_salesorder:salesorder_no:salesorder_no:SalesOrder_SalesOrder_No:V',
                        'jo_salesorder:subject:subject:SalesOrder_Subject:V',
						'jo_salesorder:accountid:account_id:SalesOrder_Account_Name:I',
                        'jo_salesorder:quoteid:quote_id:SalesOrder_Quote_Name:I',
                        'jo_salesorder:total:hdnGrandTotal:SalesOrder_Total:V',
                        'jo_crmentity:smownerid:assigned_user_id:SalesOrder_Assigned_To:V'),

	          Array('jo_vendor:vendor_no:vendor_no:Vendors_Vendor_No:V',
			  'jo_vendor:vendorname:vendorname:Vendors_Vendor_Name:V',
			'jo_vendor:phone:phone:Vendors_Phone:V',
			'jo_vendor:email:email:Vendors_Email:E',
                        'jo_vendor:category:category:Vendors_Category:V'),




		 Array(//'jo_faq:id::Faq_FAQ_Id:I',
		 		'jo_faq:faq_no:faq_no:Faq_Faq_No:V',
		       'jo_faq:question:question:Faq_Question:V',
		       'jo_faq:category:faqcategories:Faq_Category:V',
		       'jo_faq:product_id:product_id:Faq_Product_Name:I',
		       'jo_crmentity:createdtime:createdtime:Faq_Created_Time:DT',
                       'jo_crmentity:modifiedtime:modifiedtime:Faq_Modified_Time:DT'),
		      //this sequence has to be maintained
		 Array('jo_campaign:campaign_no:campaign_no:Campaigns_Campaign_No:V',
		 		'jo_campaign:campaignname:campaignname:Campaigns_Campaign_Name:V',
		       'jo_campaign:campaigntype:campaigntype:Campaigns_Campaign_Type:N',
		       'jo_campaign:campaignstatus:campaignstatus:Campaigns_Campaign_Status:N',
		       'jo_campaign:expectedrevenue:expectedrevenue:Campaigns_Expected_Revenue:V',
		       'jo_campaign:closingdate:closingdate:Campaigns_Expected_Close_Date:D',
		       'jo_crmentity:smownerid:assigned_user_id:Campaigns_Assigned_To:V'),


		 Array('subject:subject:subject:Subject:V',
		       'from:fromname:fromname:From:N',
		       'to:tpname:toname:To:N',
		       'body:body:body:Body:V'),

		 Array ('jo_faq:question:question:Faq_Question:V',
		 	'jo_faq:status:faqstatus:Faq_Status:V',
			'jo_faq:product_id:product_id:Faq_Product_Name:I',
			'jo_faq:category:faqcategories:Faq_Category:V',
			'jo_crmentity:createdtime:createdtime:Faq_Created_Time:DT'),

		 Array( 'jo_faq:question:question:Faq_Question:V',
			 'jo_faq:answer:faq_answer:Faq_Answer:V',
			 'jo_faq:status:faqstatus:Faq_Status:V',
			 'jo_faq:product_id:product_id:Faq_Product_Name:I',
			 'jo_faq:category:faqcategories:Faq_Category:V',
			 'jo_crmentity:createdtime:createdtime:Faq_Created_Time:DT'),

		 Array(	 'jo_purchaseorder:subject:subject:PurchaseOrder_Subject:V',
			 'jo_purchaseorder:postatus:postatus:PurchaseOrder_Status:V',
			 'jo_purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:I',
			 'jo_crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V',
			 'jo_purchaseorder:duedate:duedate:PurchaseOrder_Due_Date:V'),

		 Array ('jo_purchaseorder:subject:subject:PurchaseOrder_Subject:V',
			 'jo_purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:I',
			 'jo_crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V',
			 'jo_purchaseorder:postatus:postatus:PurchaseOrder_Status:V',
			 'jo_purchaseorder:carrier:carrier:PurchaseOrder_Carrier:V',
			 'jo_poshipads:ship_street:ship_street:PurchaseOrder_Shipping_Address:V'),

		 Array(  'jo_invoice:invoice_no:invoice_no:Invoice_Invoice_No:V',
		 	 'jo_invoice:subject:subject:Invoice_Subject:V',
			 'jo_invoice:accountid:account_id:Invoice_Account_Name:I',
			 'jo_invoice:salesorderid:salesorder_id:Invoice_Sales_Order:I',
			 'jo_invoice:invoicestatus:invoicestatus:Invoice_Status:V',
			 'jo_crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V',
			 'jo_crmentity:createdtime:createdtime:Invoice_Created_Time:DT'),

		 Array(	 'jo_invoice:invoice_no:invoice_no:Invoice_Invoice_No:V',
			 'jo_invoice:subject:subject:Invoice_Subject:V',
			 'jo_invoice:accountid:account_id:Invoice_Account_Name:I',
			 'jo_invoice:salesorderid:salesorder_id:Invoice_Sales_Order:I',
			 'jo_invoice:invoicestatus:invoicestatus:Invoice_Status:V',
			 'jo_invoiceshipads:ship_street:ship_street:Invoice_Shipping_Address:V',
			 'jo_crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V'),

		 Array(	 'jo_salesorder:subject:subject:SalesOrder_Subject:V',
			 'jo_salesorder:accountid:account_id:SalesOrder_Account_Name:I',
			 'jo_salesorder:sostatus:sostatus:SalesOrder_Status:V',
			 'jo_crmentity:smownerid:assigned_user_id:SalesOrder_Assigned_To:V',
			 'jo_soshipads:ship_street:ship_street:SalesOrder_Shipping_Address:V',
			 'jo_salesorder:carrier:carrier:SalesOrder_Carrier:V'),

                  );



$cvstdfilters = Array(Array('columnname'=>'jo_crmentity:modifiedtime:modifiedtime:Leads_Modified_Time',
                            'datefilter'=>'thismonth',
                            'startdate'=>'2005-06-01',
                            'enddate'=>'2005-06-30'),

		      Array('columnname'=>'jo_crmentity:createdtime:createdtime:Accounts_Created_Time',
                            'datefilter'=>'thisweek',
                            'startdate'=>'2005-06-19',
                            'enddate'=>'2005-06-25'),

		      Array('columnname'=>'jo_contactsubdetails:birthday:birthday:Contacts_Birthdate',
                            'datefilter'=>'today',
                            'startdate'=>'2005-06-25',
                            'enddate'=>'2005-06-25')
                     );

$cvadvfilters = Array(
                	Array(
               			 Array('columnname'=>'jo_leaddetails:leadstatus:leadstatus:Leads_Lead_Status:V',
		                      'comparator'=>'e',
        		              'value'=>'Hot'
                     			)
                     	 ),
		      		Array(
                          Array('columnname'=>'jo_account:account_type:accounttype:Accounts_Type:V',
                                'comparator'=>'e',
                                 'value'=>'Prospect'
                                 )
                           ),
				     Array(
                            Array('columnname'=>'jo_potential:sales_stage:sales_stage:Potentials_Sales_Stage:V',
                                  'comparator'=>'e',
                                  'value'=>'Closed Won'
                                 )
                           ),
				     Array(
                            Array('columnname'=>'jo_potential:sales_stage:sales_stage:Potentials_Sales_Stage:V',
                                  'comparator'=>'e',
                                  'value'=>'Prospecting'
                                 )
                           ),
				     Array(
                            Array('columnname'=>'jo_troubletickets:status:ticketstatus:HelpDesk_Status:V',
                                  'comparator'=>'n',
                                  'value'=>'Closed'
                                 )
                           ),
				     Array(
                            Array('columnname'=>'jo_troubletickets:priority:ticketpriorities:HelpDesk_Priority:V',
                                  'comparator'=>'e',
                                  'value'=>'High'
                                 )
                           ),
				     Array(
	                        Array('columnname'=>'jo_quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                                  'comparator'=>'n',
                                  'value'=>'Accepted'
                                 ),
						    Array('columnname'=>'jo_quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                                  'comparator'=>'n',
                                  'value'=>'Rejected'
                                 )
                           ),
				     Array(
                            Array('columnname'=>'jo_quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                                  'comparator'=>'e',
                                  'value'=>'Rejected'
                                 )
			 ),

			Array(
                          Array('columnname'=>'jo_faq:status:faqstatus:Faq_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Draft'
                                 )
			 ),

			Array(
                          Array('columnname'=>'jo_faq:status:faqstatus:Faq_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Published'
                                 )
			 ),

			Array(
                          Array('columnname'=>'jo_purchaseorder:postatus:postatus:PurchaseOrder_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Created, Approved, Delivered'
                                 )
			 ),

			Array(
                          Array('columnname'=>'jo_purchaseorder:postatus:postatus:PurchaseOrder_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Received Shipment'
                                 )
			 ),

			Array(
                          Array('columnname'=>'jo_invoice:invoicestatus:invoicestatus:Invoice_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Created, Approved, Sent'
                                 )
			 ),

			Array(
                          Array('columnname'=>'jo_invoice:invoicestatus:invoicestatus:Invoice_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Paid'
                                 )
			 ),

			Array(
                          Array('columnname'=>'jo_salesorder:sostatus:sostatus:SalesOrder_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Created, Approved'
                                 )
			 )

                     );

foreach($customviews as $key=>$customview)
{
	$queryid = insertCustomView($customview['viewname'],$customview['setdefault'],$customview['setmetrics'],$customview['cvmodule'],$customview['status'],$customview['userid']);
	insertCvColumns($queryid,$cvcolumns[$key]);

	if(isset($cvstdfilters[$customview['stdfilterid']]))
	{
		$i = $customview['stdfilterid'];
		insertCvStdFilter($queryid,$cvstdfilters[$i]['columnname'],$cvstdfilters[$i]['datefilter'],$cvstdfilters[$i]['startdate'],$cvstdfilters[$i]['enddate']);
	}
	if(isset($cvadvfilters[$customview['advfilterid']]))
	{
		insertCvAdvFilter($queryid,$cvadvfilters[$customview['advfilterid']]);
	}
}

	/** to store the details of the customview in jo_customview table
	  * @param $viewname :: Type String
	  * @param $setdefault :: Type Integer
	  * @param $setmetrics :: Type Integer
	  * @param $cvmodule :: Type String
	  * @returns  $customviewid of the stored custom view :: Type integer
	 */
function insertCustomView($viewname,$setdefault,$setmetrics,$cvmodule,$status,$userid)
{
	global $adb;

	$genCVid = $adb->getUniqueID("jo_customview");

	if($genCVid != "")
	{

		$customviewsql = "insert into jo_customview(cvid,viewname,setdefault,setmetrics,entitytype,status,userid) values(?,?,?,?,?,?,?)";
		$customviewparams = array($genCVid, $viewname, $setdefault, $setmetrics, $cvmodule, $status, $userid);
		$customviewresult = $adb->pquery($customviewsql, $customviewparams);
	}
	return $genCVid;
}

	/** to store the custom view columns of the customview in jo_cvcolumnlist table
	  * @param $cvid :: Type Integer
	  * @param $columnlist :: Type Array of columnlists
	 */
function insertCvColumns($CVid,$columnslist)
{
	global $adb;
	if($CVid != "")
	{
		for($i=0;$i<count($columnslist);$i++)
		{
			$columnsql = "insert into jo_cvcolumnlist (cvid,columnindex,columnname) values(?,?,?)";
			$columnparams = array($CVid, $i, $columnslist[$i]);
			$columnresult = $adb->pquery($columnsql, $columnparams);
		}
	}
}

	/** to store the custom view stdfilter of the customview in jo_cvstdfilter table
	  * @param $cvid :: Type Integer
	  * @param $filtercolumn($tablename:$columnname:$fieldname:$fieldlabel) :: Type String
	  * @param $filtercriteria(filter name) :: Type String
	  * @param $startdate :: Type String
	  * @param $enddate :: Type String
	  * returns nothing
	 */
function insertCvStdFilter($CVid,$filtercolumn,$filtercriteria,$startdate,$enddate)
{
	global $adb;
	if($CVid != "")
	{
		$stdfiltersql = "insert into jo_cvstdfilter(cvid,columnname,stdfilter,startdate,enddate) values (?,?,?,?,?)";
		$stdfilterparams = array($CVid, $filtercolumn, $filtercriteria, $startdate, $enddate);
		$stdfilterresult = $adb->pquery($stdfiltersql, $stdfilterparams);
	}
}

	/** to store the custom view advfilter of the customview in jo_cvadvfilter table
	  * @param $cvid :: Type Integer
	  * @param $filters :: Type Array('columnname'=>$tablename:$columnname:$fieldname:$fieldlabel,'comparator'=>$comparator,'value'=>$value)
	  * returns nothing
	 */

function insertCvAdvFilter($CVid,$filters)
{
	global $adb;
	if($CVid != "")
	{
		$columnIndexArray = array();
		foreach($filters as $i=>$filter)
		{
			$advfiltersql = "insert into jo_cvadvfilter(cvid,columnindex,columnname,comparator,value) values (?,?,?,?,?)";
			$advfilterparams = array($CVid, $i, $filter['columnname'], $filter['comparator'], $filter['value']);
			$advfilterresult = $adb->pquery($advfiltersql, $advfilterparams);
		}
		$conditionExpression = implode(' and ', $columnIndexArray);
		$adb->pquery('INSERT INTO jo_cvadvfilter_grouping VALUES(?,?,?,?)', array(1, $CVid, '', $conditionExpression));
	}
}
?>
