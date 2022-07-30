<?php

namespace Joforce;
require 'vendor/autoload.php';
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\InputObjectType;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class JoHelper
{
    private $db;

    private $user;

    private $module_fields_info;

    private $records_per_page = 20;

    /**
     * JoHelper constructor.
     *
     * @param $adb
     * @param $user
     */
    public function __construct($adb, $user)
    {
        $this->db = $adb;

        $this->user = $user;
    }

    /**
     * Resolve data for given arguments
     *
     * @param array $requested_data
     * @param $args
     * @return array|mixed
     * @throws \Exception
     * @throws \WebServiceException
     */
    public function resolve($requested_data, $args)
    {
        $data = [];
        if ($requested_data['action'] == 'get_modules') {
            $data = $this->getJoModules();
        } else if ($requested_data['action'] == 'menu') {
            $data = $this->getUserMenu();
        } else if ($requested_data['action'] == 'global_search') {
            $data = $this->globalSearch($args);
            $data = ['results' => $data];
        } else if ($requested_data['action'] == 'calendar_view') {
            $data = $this->getCalendarInfo($args);
            $data['events'] = $data;
        } else if ($requested_data['action'] == 'describe') {
            $data = $this->getModuleFields($args['module'],$requested_data);
        } else if ($requested_data['action'] == 'describe_with_block') {
            $data = $this->getModuleFieldswithblock($args['module']);
        } else if ($requested_data['action'] == 'filters') {
            $data = $this->getUserFilters($args);
        } else if ($requested_data['action'] == 'filter_columns') {
            $data = $this->getFilterColumns($args);
        } else if ($requested_data['action'] == 'get_module_relations') {
            $data = $this->returnRelatedModules($args['module']);
        } else if ($requested_data['action'] == 'get_related_records') {
            $data = $this->returnRelatedRecords($args);
        } else if ($requested_data['action'] == 'widget_info') {
            $data = $this->getWidgetData($args);
            $data = ['data' => $data];
        } else if ($requested_data['action'] == 'get_record') {
            $data = $this->retrieve($args['module'], $args['id']);
        } else if ($requested_data['action'] == 'list') {
            $data = $this->listRecords($requested_data, $args);
        } else if ($requested_data['action'] == 'get_users') {
            $data = $this->returnUsers($requested_data, $args);
        } else if ($requested_data['action'] == 'get_forecast') {
            $data = $this->returnForecast($requested_data, $args);
        } else if ($requested_data['action'] == 'tax') { 
            $data = $this->gettax($requested_data, $args); 
        } else if ($requested_data['action'] == 'forgotpassword') { 
            $data = $this->forgotpassword($args); 
        } else if ($requested_data['action'] == 'savepassword') {
            $data = $this->savePassword($args); 
        } else if ($requested_data['action'] == 'feedback') { 
            $data = $this->sendFeedback($args);
        } else if ($requested_data['action'] == 'uploadprofile') { 
            $data = $this->uploadProfile($args);
        } else if ($requested_data['action'] == 'getprofile') { 
            $data = $this->getProfile($args);
        } else if ($requested_data['action'] == 'get_all_relation') { 
            $data = $this->getAllRealtedRecords($args);
        }
        return $data;
    }
    public function related_product_lineitems($module_name,$id)
    {
        $data_info =[];
        $data =[];
        global $current_user;
         $moduleModel = \Head_Module_Model::getInstance($module_name);
        $recordModel = \Head_Record_Model::getInstanceById($id, $moduleModel);
        $unresolved_data = $recordModel->getData();
        if ($module_name == 'PurchaseOrder' || $module_name == 'Invoice' || $module_name == 'SalesOrder' || $module_name == 'Quotes'){
             $relatedProducts = $recordModel->getProducts();
             if(!empty($relatedProducts)){          
                for ($i=1; $i <=count($relatedProducts); $i++) {
                        $sets = array();
                        $sets['productid']=$relatedProducts[$i]['productName'.$i];
                        $sets['productid_id']=$relatedProducts[$i]['hdnProductId'.$i];
                        $sets['quantity']=$relatedProducts[$i]['qty'.$i];
                        $sets['listprice']=$relatedProducts[$i]['listPrice'.$i];
                        $sets['comment']=$relatedProducts[$i]['comment'.$i];
                        $data[] =$sets;                 
                }
            } 
        }
        $data_info['related_product_lineitems'] ['Products']= $data;
        return $data_info;
    }
    public function savePassword($args) {
        include_once "includes/Webservices/Custom/ChangePassword.php";
        try {
            $userId = getUserId_Ol($args['username']);
            if($userId == 0) {
                return ["success" => false, 'message' => "User not found"];
            }

            $user = \Users::getActiveAdminUser();
			$wsUserId = vtws_getWebserviceEntityId('Users', $userId);
			$response = vtws_changePassword($wsUserId, '', $args['password'], $args['confirmpassword'], $user);

            return ["success" => true, 'message' => $response['message']];
        } catch(\Exception $e) {
            return ["success" => false, 'message' => $e->getMessage()];
        }
    }
    public function forgotpassword($args) {
        $success = false;
        try {
            $userId = getUserId_Ol($args['username']);
            if($userId != 0) {
                $success = true;
            }
            return ["success" => $success, 'user_id' => $userId];
        } catch(\Exception $e) {
            return ["success" => $success];
        }
    }
    public function sendFeedback($args) {
        try {
            global $HELPDESK_SUPPORT_EMAIL_ID, $HELPDESK_SUPPORT_NAME, $HELPDESK_SUPPORT_EMAIL_REPLY_ID;
            $subject = "User Feedback";
            $body = "Hi team,";
            $body .= "<p> User sent a feedback as <b>".$args['content']."<b></p>";

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->isHTML(true);
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true; 
            $mail->Username = 'subaakalyan@smackcoders.com';
            $mail->Password = 'akalya48*';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('mailtestsmack@gmail.com', 'Smack Support');
            $mail->addAddress('ponvimalrajv@smackcoders.com', 'Smack Support');
            $mail->Subject = $subject;
            $mail->Body = $body;

            if(!$mail->send()) {
                return ["mail_status" => false, 'message' => $mail->ErrorInfo];
            } else {
                return ["mail_status" => true, 'message' => "Message has been sent successfully"];
            }
        } catch(\Exception $e) {
            return ["mail_status" => false, 'message' => $e->getMessage()];
        }
    }
    public function getAllRealtedRecords($args) {
        $modules = $this->returnRelatedModules($args['module'], true);
        $related_args = [];
        $all_records = [];
        $related_args['module'] = $args['module'];
        $related_args['id'] = $args['id'];
        $skip_modules = ["PBXManager"];
        foreach($modules['relations'] as $module_list) {
            if(in_array($module_list['module_name'], $skip_modules)) { continue; }
            $related_args['related_module'] = $module_list['module_name'];  
            $related_module = $module_list['module_name'];
            $related_records = $this->returnRelatedRecords($related_args, true);
            if($related_records['success']) {
                if(count($related_records['related_records']) > 0) {
                    $recordDataList = $related_records['related_records'];
                    $i = 1; $more_records = false;
                    foreach($recordDataList as $recordData) {
                        $record_array = array('common'=>'','name'=>'','common_label'=>'');
                        $starred = false;
                        if(isset($recordData['starred']) && $recordData['starred']) {
                            $starred = true;
                        }
                        $record_array['is_favourite'] = $starred;
                        $record_array['created_at'] =  $recordData['createdtime'];
                        if($related_module == 'Contacts' || $related_module == 'Leads') {
                            $record_array['common'] = $recordData['email'];
                            $record_array['common_label'] = 'Email';
                            $record_array['name'] = $recordData['firstname']." ".$recordData['lastname'];
                        } else if($related_module == 'Products') {
                            $record_array['common_label'] = 'Unit Price';
                            $record_array['common'] = number_format($recordData['unit_price'], 2);
                            $record_array['name'] = $recordData['productname'];
                        } else if($related_module == 'Accounts') {
                            $record_array['common_label'] = 'Email';
                            $record_array['common'] = $recordData['email1'];
                            $record_array['name'] = $recordData['accountname'];
                        } else if($related_module == 'Campaigns') {
                            $record_array['common_label'] = 'Closing Date';
                            $record_array['common'] = $recordData['closingdate'];
                            $record_array['name'] = $recordData['campaignname'];
                        } else if($related_module == 'Services') {
                            $record_array['common_label'] = 'Service Number';
                            $record_array['common'] = $recordData['service_no'];
                            $record_array['name'] = $recordData['servicename'];
                        } else if($related_module == 'PriceBooks') {
                            $record_array['name'] = $recordData['bookname'];
                            $record_array['common_label'] = 'Currency';
                            $record_array['common'] = getCurrencyName($recordData['currency_id']);
                        } else if($related_module == 'Invoice' || $related_module == 'Quotes' || 
                        $related_module == 'SalesOrder' || $related_module == 'PurchaseOrder') {
                            $record_array['name'] = $recordData['subject'];
                            $record_array['common_label'] = 'Total Amount';
                            $record_array['common'] = number_format($recordData['hdnGrandTotal'], 2);
                        } else if($related_module == 'Vendors') {
                            $record_array['name'] = $recordData['vendorname'];
                            $record_array['common_label'] = 'Vendor Number';
                            $record_array['common'] = $recordData['vendor_no'];
                        } else if($related_module == 'HelpDesk') {
                            $record_array['name'] = $recordData['ticket_title'];
                            $record_array['common_label'] = 'Priority';
                            $record_array['common'] = $recordData['ticketpriorities'];
                        } else if($related_module == 'Documents') {
                            $record_array['name'] = $recordData['filename'];
                            $record_array['common'] = $recordData['filetype'];
                            $record_array['common_label'] = "File Type";
                        }
                         else if($related_module == 'ModComments') {
                            $record_array['name'] = $recordData['username'];
                            $record_array['common'] = $recordData['content'];
                            $record_array['common_label'] = "Content";
                        }
                        else if($related_module == 'Calendar') {
                            $record_array['name'] = $recordData['subject'];
                            $record_array['common'] = $recordData['taskstatus'];
                            $record_array['common_label'] = "Status";
                        } 
                        if($i > 5) {
                            $more_records = true;
                            break;
                        }
                        $all_records[$module_list['module_name']]['related_records'][] = $record_array;
                        $i += 1;
                    }
                    $all_records[$module_list['module_name']]['moreRecords'] = $more_records;
                }
            }
        }
       
        $new_Records = [];
        $new_Records['blocks']=[];
foreach($all_records as $key => $record) {
            $new_Records['blocks'][] = [
                'module' => $key,
                'moreRecords' => $record['moreRecords'],
                'related_records' => $record['related_records'],
            ];
        }
        return $new_Records;
    }

    public function getProfile($args) {
        global $site_URL;
        $userId = getUserId_Ol($args['username']);
        if($userId <= 0) {
            return ["success" => false, "message" => "User not found"];
        }

        $query = "SELECT imagename FROM jo_users WHERE user_name = '" . $args['username']."'";
        $result = $this->db->pquery($query);
        $recordModel = $this->db->fetch_array($result);

        $imageUrl = "$site_URL".$recordModel['imagename'];
        return ["success" => true, "imageurl" => "$imageUrl"];
    }

    public function uploadProfile($args) {
        global $site_URL;
        try {
            global $root_directory;
            $userId = getUserId_Ol($args['username']);
            $file_path = "storage";
            $rootDirectory = vglobal('root_directory');
            $permissions = exec("ls -l $rootDirectory/config.inc.php | awk 'BEGIN {OFS=\":\"}{print $3,$4}'");
    
            $file_path .= "/profile";
            if (!is_dir($file_path)) {
                mkdir($file_path);
                exec("chown -R $permissions  $file_path");
            }
    
            $file_path .= "/$userId";
            if (!is_dir($file_path)) {
                mkdir($file_path);
                exec("chown -R $permissions  $file_path");
            }
    
            $new_filename = $args['filename']."_$userId.".$args['filetype'];
            $data = $args['imagecontent'];

            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);

            file_put_contents($root_directory.$file_path."/".$new_filename, $data);

            $query = "UPDATE vtiger_users set imagename='".$file_path."/$new_filename"."' WHERE user_name = '" . $args['username']."'";
            $result = $this->db->pquery($query);
            $imageUrl = "$site_URL".$file_path."/$new_filename";

            return ["success" => true, "message" => "Profile image updated", 'imageurl' => $imageUrl];
        } catch(\Exception $e) {
            return ["success" => false, "message" => "Error occurred while updating profile image"];
        }
    }

    public function getModuleFieldswithblock($module)
    { 
        global $app_strings,$current_language;
        // if (!$app_strings) {
        //     $currentLanguage = Head_Language_Handler::getLanguage();
        //     $moduleLanguageStrings = Head_Language_Handler::getModuleStringsFromFile($currentLanguage);
        //     $app_strings = $moduleLanguageStrings['languageStrings'];
        // }
        $module = ucfirst($module);
        include_once 'include/Webservices/DescribeObject.php';
        $describeInfo = vtws_describe($module, $this->user);
        $current_module_strings = return_module_language($current_language, $module);
        $fields = $describeInfo['fields'];
        $moduleModel = \Head_Module_Model::getInstance($module);
        $nameFields = $moduleModel->getNameFields();
        if (is_string($nameFields)) {
            $nameFieldModel = $moduleModel->getField($nameFields);
            $headerFields[] = $nameFields;
            $fields = array('name' => $nameFieldModel->get('name'), 'label' => $nameFieldModel->get('label'), 'fieldType' => $nameFieldModel->getFieldDataType(),'blockid'=>$nameFieldModel->get('block')->id,'block_label'=>$nameFieldModel->get('block')->label);
        } else if (is_array($nameFields)) {
            foreach ($nameFields as $nameField) {
                $nameFieldModel = $moduleModel->getField($nameField);
                $headerFields[] = $nameField;
                $fields[] = array('name' => $nameFieldModel->get('name'), 'label' => $nameFieldModel->get('label'), 'fieldType' => $nameFieldModel->getFieldDataType(),'blockid'=>$nameFieldModel->get('block')->id,'block_label'=>$nameFieldModel->get('block')->label);
            }
        }

        $fieldModels = $moduleModel->getFields();

        foreach ($fields as $index => $field) {
            if ($module == 'PurchaseOrder' || $module == 'Invoice' || $module == 'SalesOrder' || $module == 'Quotes') {
                if ($field['name'] == 'shipping_&_handling' || $field['name'] == 'shipping_&_handling_shtax1' || $field['name'] == 'shipping_&_handling_shtax2' || $field['name'] == 'shipping_&_handling_shtax3') {
                    continue;
                }
            }
            if ($field['type']['name'] == 'boolean' && $field['default'] == 'on') {
                $field['default'] = true;
            }
            if ($field['name'] == 'activitytype' && $module == 'Calendar') {
                $field['mandatory'] = true;
            }
            if ($field['name'] == 'salutationtype') {
                $field['type']['name'] = picklist;
                $field['type']['picklistValues'] = array(array('label' => 'None', 'value' => 'None'), array('label' => 'Mr.', 'value' => 'Mr.'), array('label' => 'Mrs.', 'value' => 'Mrs.'), array('label' => 'Ms.', 'value' => 'Ms.'), array('label' => 'Dr.', 'value' => 'Dr.'), array('label' => 'Prof.', 'value' => 'Prof.'));
            }

            $fieldModel = $fieldModels[$field['name']];
            if ($fieldModel) {
                $field['block_id'] =$fieldModel->get('block')->id;
                $get_transulation = $app_strings[$fieldModel->get('block')->label];
                if($get_transulation){
                     $field['block_label'] = $get_transulation;
                }
                else{
                $field['block_label'] = vtranslate($fieldModel->get('block')->label,$module);                 }
                $field['headerfield'] = $fieldModel->get('headerfield');
                if(empty($field['headerfield'])){
                    $field['headerfield']= 0;
                }
                $field['summaryfield'] = $fieldModel->get('summaryfield');
            }
            //tags taken out from schema
            if ($field['name'] == 'tags') {
                continue;
            } else {
                $newFields[] = $field;
            }
        }
        $fields = null;
        $looping_array = array("productid", "quantity", "listprice","comment","productname" );
        if($module=='Invoice' || $module=='Quotes' || $module=='SalesOrder' || $module=='PurchaseOrder'){ 
            
            if(!empty($_REQUEST['id'])){
                $recordModel = \Head_Record_Model::getInstanceById($_REQUEST['id'], $module);
                $relatedProducts = $recordModel->getProducts();
                $itemcount = count($relatedProducts); 
                $describeInfo['count'] =$itemcount;
            }  
            $addloop =array();
            for ($i=1;$i <=$itemcount ; $i++) {
                foreach ($newFields as $key_change  => $value) { 
                    if(in_array($value['name'], $looping_array)){ 
                       $value['name']= $value['name'].$i;
                       $value['label']= $value['label'].$i;
                       $newFields[$value['block_id'].$i][] =$value ;  
                       if($value['name']=='productid'.$i){ 
                            $value['name']= $value['name'].'_id';
                            $value['label']= $value['label'];
                            $value['editable']= 0;
                            $newFields[] =$value ; 
                        }                      
                    }                   
                }
            } 
            for ($i=0; $i < count($newFields); $i++) {
               if(in_array($newFields[$i]['name'], $looping_array)){ 
                    $newFields[$i]['editable']='0';
               }
            }             
        } 
        $data_array = array();
        foreach ($newFields as $key => $value) {
            $data_array[$value['block_id']][]=$value;
        }
        $data = array();
        foreach ($data_array as $data_key => $data_value) {
            if($data_value[0]['block_id'] != '')
            $data[] = array('id'=>$data_value[0]['block_id'],'name'=>$data_value[0]['block_label'],'blocks'=>$data_value);
        }
        $describeInfo['nameFields'] = $nameFields;
        $describeInfo['fields'] = $data;  
        if($describeInfo['count'] ==''){
            $describeInfo['count'] =0;
        }    
        return $describeInfo;
    }

    public function gettax($requested_data, $args) {
        if($requested_data['mode'] == 'region') {
            $allRegions = \Inventory_TaxRegion_Model::getAllTaxRegions();
            $regions = [];
            for ($i = 1; $i < count($allRegions) + 1; $i++) {
                $regions[$i]['regionid'] = $allRegions[$i]->get('regionid');
                $regions[$i]['regionname'] = $allRegions[$i]->get('name');
            }
            $response = [
                'records' => $regions,
            ];
            return $response;
        } else if($requested_data['mode'] == 'tax_by_region') {
            $productAndServicesTaxList = \Inventory_TaxRecord_Model::getProductTaxes();
            $count = count($productAndServicesTaxList);
            $related_modules = [];
            
            for ($i=1; $i <=$count ; $i++) {
                $taxes = [];
                $reglist = json_decode( html_entity_decode($productAndServicesTaxList[$i]->get('regions')));
                $count_reg = count($reglist);
                for ($k=0; $k < $count_reg; $k++) { 
                    $listcount = count($reglist[$k]->list);
                    for ($j=0; $j < $listcount; $j++) {
                        if($reglist[$k]->list[$j] == $args['filter_id']) {
                            $taxes['id'] = $productAndServicesTaxList[$i]->get('taxid');
                            $taxes['taxname'] =$productAndServicesTaxList[$i]->get('taxname');
                            $taxes['taxlabel'] = $productAndServicesTaxList[$i]->get('taxlabel');
                            $taxes['percentage'] = $productAndServicesTaxList[$i]->get('percentage');
                            $taxes['method'] = $productAndServicesTaxList[$i]->get('method');
                            $taxes['type'] = $productAndServicesTaxList[$i]->get('type');
                            $taxes['compoundon']= html_entity_decode($productAndServicesTaxList[$i]->get('compoundon'));

                            $regionRecord = \Inventory_TaxRegion_Model::getRegionModel($reglist[$k]->list[$j] );
                            $taxes['region_name'] = $regionRecord->get('name');
                            $taxes['region_tax'] = $reglist[$k]->value;

                            $related_modules[] = $taxes;            
                        }
                    }
                }
            } 
            $response = [
                'records' => $related_modules,
            ];
    
            return $response;  
        } else {
            $productAndServicesTaxList = \Inventory_TaxRecord_Model::getProductTaxes();
            $count=count($productAndServicesTaxList);  
            for ($i=1; $i <=$count ; $i++) {  
                $taxes = [];
                $taxes['id']        =$productAndServicesTaxList[$i]->get('taxid');
                $taxes['taxname']   =$productAndServicesTaxList[$i]->get('taxname');
                $taxes['taxlabel']  =$productAndServicesTaxList[$i]->get('taxlabel');
                $taxes['percentage']=$productAndServicesTaxList[$i]->get('percentage');
                $taxes['method']    =$productAndServicesTaxList[$i]->get('method');
                $taxes['type']      =$productAndServicesTaxList[$i]->get('type');
                $taxes['compoundon']= html_entity_decode($productAndServicesTaxList[$i]->get('compoundon'));
                $reglist = json_decode( html_entity_decode($productAndServicesTaxList[$i]->get('regions')));
                $count_reg = count($reglist);
                for ($k=0; $k < $count_reg; $k++) { 
                    $listcount=count($reglist[$k]->list);
                    for ($j=0; $j < $listcount; $j++) { 
                        $regval=array();
                        $regval['list']=$reglist[$k]->list[$j]; 
                        $regval['value']=$reglist[$k]->value;
                        $list_region[]=$regval;
                    }
                }          
                $taxes['regions']   =json_encode($list_region);
                $related_modules[] = $taxes;            
            } 
            $moreRecords = false;
            $response = [
                'records' => $related_modules,
            ];
    
            return $response;  
        }
    }
    public function returnForecast($requested_data, $args)
    {
        $month = $args['month'];
        $year = $args['year'];

        $date = $year . '-' . $month . '-01';
        $date2 = $year . '-' . $month . '-31';
        $query = "SELECT potentialname,sales_stage,closingdate,amount,potentialid FROM jo_potential WHERE closingdate >= '" . $date . "' AND closingdate <= '" . $date2 . "' limit 100";
        $result = $this->db->pquery($query);
        $opportunities = [];
        while ($myrow = $this->db->fetch_array($result)) {
            $row = [];
            $row['oppurtinities_name'] = $myrow['potentialname'];
            $row['sales_stage'] = $myrow['sales_stage'];
            $row['closingdate'] = $myrow['closingdate'];
            $row['amount'] = $myrow['amount'];
            $row['id'] = $myrow['potentialid'];
            array_push($opportunities, $row);
        }
        $oppor = [];
        $oppor['opportunitylist'] = $opportunities;
        $final_array = [];
        $final_array["data"] = $oppor;
        print_r(json_encode($final_array));
        die();
    }

    /**
     * Retrieve record from module
     *
     * @param $module
     * @param $record_id
     * @return mixed
     * @throws \WebServiceException
     */
    public function retrieve($module, $record_id)
    {
        
	    if(!isRecordExists($record_id))
	    return; //return if recordid is deleted or does not exist.
        
        $module_name = ucfirst($module);
        
        include_once 'include/Webservices/DescribeObject.php';
        $this->module_fields_info = \vtws_describe($module_name, $this->user);
        
        // TODO Check permission using vtws_retrieve function
        $moduleModel = \Head_Module_Model::getInstance($module_name);
        $recordModel = \Head_Record_Model::getInstanceById($record_id, $moduleModel);
        
        $unresolved_data = $recordModel->getData();
       
        if ($module == 'PurchaseOrder' || $module == 'Invoice' || $module == 'SalesOrder' || $module == 'Quotes'){
             $relatedProducts = $recordModel->getProducts();
        }
        if ($module == 'Products') {
            
            $recordTaxDetails = $recordModel->getTaxClassDetails();
            
            foreach ($recordTaxDetails as $tax_details) {
                if ($tax_details['check_value'] == 1) {
                    
                    $unresolved_data[$tax_details['taxname']] = $tax_details['percentage'];
                }
            }
        } elseif ($module == 'Contacts') {
            
            global $site_URL;
            $user_image = $recordModel->getImageDetails();
            $user_profile_url = '';
            if ($user_image) {
                if (isset($user_image[0]['url']) && !empty($user_image[0]['url'])) {
                    $user_profile_url = $user_image[0]['url'];
                }
            }
            $unresolved_data['imageurl'] = $user_profile_url;
        }
	    elseif($module == 'Documents'){
            
		    global $adb, $root_directory, $site_URL;
		    $sql = "SELECT jo_attachments.*, jo_crmentity.setype FROM jo_attachments
			INNER JOIN jo_seattachmentsrel ON jo_seattachmentsrel.attachmentsid = jo_attachments.attachmentsid
			INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_attachments.attachmentsid
			WHERE jo_seattachmentsrel.crmid = ?";
            
		    $result = $adb->pquery($sql, array($record_id));
            
		    $imageId = $adb->query_result($result, 0, 'attachmentsid');
		    $imagePath = $adb->query_result($result, 0, 'path');
		    $imageName = $adb->query_result($result, 0, 'name');
		    $url = $this->getFilePublicURL($imageId, $imageName);
		    if($url) {
			    $url = $site_URL.$url;
		    }
		    $unresolved_data['file'] = $url;
	    }
        
        $data = $this->resolveRecordValues($unresolved_data, $moduleModel);
        if(!empty($relatedProducts)){          
            for ($i=1; $i <=count($relatedProducts); $i++) {
                $data['productname_'.$i]=$relatedProducts[$i]['productName'.$i];
                $data['productid_'.$i]=$relatedProducts[$i]['hdnProductId'.$i];
                $data['quantity_'.$i]=$relatedProducts[$i]['qty'.$i];
                $data['listprice_'.$i]=$relatedProducts[$i]['listPrice'.$i];
                $data['comment_'.$i]=$relatedProducts[$i]['comment'.$i];                
            }
        } 
        return $data;
    }

    /**
     * Retrieve record from module
     *
     * @param $module
     * @param $record_id
     * @return mixed
     * @throws \WebServiceException
     */
    public function retrieveJOFORCE($module, $record_id)
    {
        global $current_user;
        $module_name = ucfirst($module);
        require_once('includes/utils/utils.php');
        require_once('includes/Webservices/Retrieve.php');
        require_once('includes/Webservices/DescribeObject.php');
        $this->module_fields_info = \vtws_describe($module_name, $this->user);
        $webservice_id = \vtws_getWebserviceEntityId($module, $record_id);
        $unresolved_data = \vtws_retrieve($webservice_id, $current_user);
        $resolved_data = $this->resolveRecordValues($unresolved_data, $current_user, $module_name);
        if ($module_name == 'Products') {
            $moduleModel = \Head_Module_Model::getInstance($module_name);
            $recordModel = \Head_Record_Model::getInstanceById($record_id, $moduleModel);
            $recordTaxDetails = $recordModel->getTaxClassDetails();
            foreach ($recordTaxDetails as $tax_details) {
                if ($tax_details['check_value'] == 1) {
                    $resolved_data[$tax_details['taxname']] = $tax_details['percentage'];
                }
            }
        }
        $data = $this->returnDataInBlocks($unresolved_data, $module_name, []);
        return $data;
    }

    /**
     * Return list of users and groups
     *
     * @param $requested_data
     * @param $args
     * @return array
     * @throws \Exception
     */
    public function returnUsers($requested_data, $args)
    {
        global $current_user;
        $current_user = $this->user;
        $userModal = \Users_Record_Model::getCurrentUserModel();
        $users_info = $userModal->getAccessibleUsers();
        $groups_info = $userModal->getAccessibleGroups();

        $response = array();
        foreach ($users_info as $user_id => $user_name) {
            $response[] = ['id' => $user_id, 'label' => $user_name, 'is_user' => true];
        }

        if ($args['groups'] === true) {
            foreach ($groups_info as $group_id => $group_name) {
                $response[] = ['id' => $group_id, 'label' => $group_name, 'is_user' => false];
            }
        }
        return ['response' => $response];
    }

    /**
     * Return list of users and groups
     *
     * @param $requested_data
     * @param $args
     * @return array
     * @throws \Exception
     */
    public function returnUsersList($requested_data, $args)
    {
        global $current_user;
        $current_user = $this->user;
        $currentUserModel = \Users_Record_Model::getInstanceFromUserObject($current_user);

        $moduleName = 'Users';
        $users = $this->getUsers($currentUserModel, $moduleName);
        $groups = $this->getGroups($currentUserModel, $moduleName);
        $response = array('users' => $users, 'groups' => $groups);
        return $response;
    }

    /**
     * Return Users
     *
     * @param object $currentUserModel
     * @param string $moduleName
     * @return array
     */
    public function getUsers($currentUserModel, $moduleName)
    {
        $users = $currentUserModel->getAccessibleUsersForModule($moduleName);
        $userIds = array_keys($users);
        $usersList = array();
        require_once('includes/utils/utils.php');
        $usersWSId = getEntityModuleWSId('Users');
        foreach ($userIds as $userId) {
            $userRecord = \Users_Record_Model::getInstanceById($userId, 'Users');
            $usersList[] = array(
                'value' => $usersWSId . 'x' . $userId,
                'label' => \decode_html($userRecord->get("first_name") . ' ' . $userRecord->get('last_name'))
            );
        }
        return $usersList;
    }

    /**
     * Return Groups
     *
     * @param object $currentUserModel
     * @param string $moduleName
     * @return array $groupsList
     */
    public function getGroups($currentUserModel, $moduleName)
    {
        $groups = $currentUserModel->getAccessibleGroupForModule($moduleName);
        $groupIds = array_keys($groups);
        $groupsList = array();
        require_once('includes/utils/utils.php');
        $groupsWSId = getEntityModuleWSId('Groups');
        foreach ($groupIds as $groupId) {
            $groupName = getGroupName($groupId);
            $groupsList[] = array(
                'value' => $groupsWSId . 'x' . $groupId,
                'label' => decode_html($groupName[0])
            );
        }
        return $groupsList;
    }

    /**
     * Return list of records
     *
     * @param $requested_data
     * @param $args
     * @return array
     * @throws \Exception
     */
    public function listRecords($requested_data, $args)
    {
        global $current_user;

        $current_user = $this->user;

        $module_name = ucfirst($args['module']);
        $moduleModel = \Head_Module_Model::getInstance($module_name);
        $headerFieldModels = $moduleModel->getHeaderViewFieldsList();

        $orderBy = $args['order_by'];
        $sortOrder = $args['sort_by'];
        $filterId = $args['filter_id'];

        $headerFields = array();
        $fields = array();

        $nameFields = $moduleModel->getNameFields();
        if (is_string($nameFields)) {
            $nameFieldModel = $moduleModel->getField($nameFields);
            $headerFields[] = $nameFields;
            $fields = array('name' => $nameFieldModel->get('name'), 'label' => $nameFieldModel->get('label'), 'fieldType' => $nameFieldModel->getFieldDataType());
        } else if (is_array($nameFields)) {
            foreach ($nameFields as $nameField) {
                $nameFieldModel = $moduleModel->getField($nameField);
                $headerFields[] = $nameField;
                $fields[] = array('name' => $nameFieldModel->get('name'), 'label' => $nameFieldModel->get('label'), 'fieldType' => $nameFieldModel->getFieldDataType());
            }
        }

        foreach ($headerFieldModels as $fieldName => $fieldModel) {
            $headerFields[] = $fieldName;
            $fields[] = array('name' => $fieldName, 'label' => $fieldModel->get('label'), 'fieldType' => $fieldModel->getFieldDataType());
        }

        $listViewModel = \Head_ListView_Model::getInstance($module_name, $filterId, $headerFields = array());
        if (!empty($sortOrder)) {
            $listViewModel->set('orderby', $orderBy);
            $listViewModel->set('sortorder', $sortOrder);
        }

        if (!empty($args['search_key']) && !empty($args['search_value'])) {
            $listViewModel->set('search_value', $args['search_value']);
            $listViewModel->set('search_key', $args['search_key']);
            $listViewModel->set('operator', 'c');
        }

        $pagingModel = new \Head_Paging_Model();
        if (isset($args['limit']) && !empty($args['limit'])) {
            $pageLimit = $args['limit'];
        } else {
            $pageLimit = $pagingModel->getPageLimit();
        }
        $pagingModel->set('page', $args['page']);
        $pagingModel->set('limit', $pageLimit + 1);

        $listViewEntries = $listViewModel->getListViewEntries($pagingModel);

        $customView = new \CustomView($module_name);
        if (empty($filterId)) {
            $filterId = $customView->getViewId($module_name);
        }

        if ($listViewEntries) {
            foreach ($listViewEntries as $index => $listViewEntryModel) {
                $data = $listViewEntryModel->getData();
                $record = array('id' => $listViewEntryModel->getId());
                $name = ''; $created_at = ''; $common = ''; $starred = false;
                foreach ($data as $i => $value) {
                    $recordModel = \Head_Record_Model::getInstanceById($listViewEntryModel->getId(), $module_name);
                    $recordData = $recordModel->getData();
                    if(isset($recordData['starred']) && $recordData['starred']) {
                        $starred = true;
                    }
                    $created_at =  $recordData['createdtime'];
                    if (is_string($i)) {
                        if($module_name == 'Contacts' || $module_name == 'Leads') {
                            $common = $recordData['email'];
                            $common_label = 'Email';
                            $name = $recordData['firstname']." ".$recordData['lastname'];
                        } else if($module_name == 'Products') {
                            $common_label = 'Unit Price';
                            $common = number_format($recordData['unit_price'], 2);
                            $name = $recordData['productname'];
                        } else if($module_name == 'Accounts') {
                            $common_label = 'Email';
                            $common = $recordData['email1'];
                            $name = $recordData['accountname'];
                        } else if($module_name == 'Campaigns') {
                            $common_label = 'Closing Date';
                            $common = $recordData['closingdate'];
                            $name = $recordData['campaignname'];
                        } else if($module_name == 'Services') {
                            $common_label = 'Service Number';
                            $common = $recordData['service_no'];
                            $name = $recordData['servicename'];
                        } else if($module_name == 'SMSNotifier') {
                            $name = $recordData['message'];
                        } else if($module_name == 'PriceBooks') {
                            $name = $recordData['bookname'];
                            $common_label = 'Currency';
                            $common = getCurrencyName($recordData['currency_id']);
                        } else if($module_name == 'Invoice' || $module_name == 'Quotes' || 
                        $module_name == 'SalesOrder' || $module_name == 'PurchaseOrder') {
                            $name = $recordData['subject'];
                            $common_label = 'Total Amount';
                            $common = number_format($recordData['hdnGrandTotal'], 2);
                        } else if($module_name == 'Vendors') {
                            $name = $recordData['vendorname'];
                            $common_label = 'Vendor Number';
                            $common = $recordData['vendor_no'];
                        } else if($module_name == 'HelpDesk') {
                            $name = $recordData['ticket_title'];
                            $common_label = 'Priority';
                            $common = $recordData['ticketpriorities'];
                        } else if($module_name == 'ServiceContracts') {
                            $name = $recordData['subject'];
                            $common_label = 'Type';
                            $common = $recordData['contract_type'];
                        } else if($module_name == 'Assets') {
                            $name = getProductName($recordData['product']);
                            $common_label = 'Date Sold';
                            $common = $recordData['datesold'];
                        } else if($module_name == 'Project') {
                            $name = $recordData['projectname'];
                            $common_label = 'Status';
                            $common = $recordData['projectstatus'];
                        } else if($module_name == 'ProjectTask') {
                            $name = $recordData['projecttaskname'];
                            $common_label = 'Priority';
                            $common = $recordData['projecttaskpriority'];
                        } else if($module_name == 'ProjectMilestone') {
                            $name = $recordData['projectmilestonename'];
                            $common_label = 'Milestone Date';
                            $common = $recordData['projectmilestonedate'];
                        }   
                        $record[$i] = decode_html($value);
                    }
                    $record['name'] = trim($name);
                    $record['common'] = trim($common);
                    $record['common_label'] = trim($common_label);
                    $record['created_at'] = trim($created_at);
                    $record['is_favourite'] = $starred;
                }
                $records[] = $record;
            }
        }

        $moreRecords = false;
        if (count($listViewEntries) > $pageLimit) {
            $moreRecords = true;
            array_pop($records);
        }

        $response = [
            'records' => $records,
            'headers' => $fields,
            'selectedFilter' => $filterId,
            'nameFields' => $nameFields,
            'moreRecords' => $moreRecords,
            'orderBy' => $orderBy,
            'sortOrder' => $sortOrder,
            'page' => $args['page']
        ];

        return $response;
    }

    public function convertToUTCTime($time)
    {
        if (empty($time) || $time == '') {
            return '';
        }

        $date = '2020-04-01 ' . $time;
        $converted_date = \Head_Datetime_UIType::getDBDateTimeValue($date);
        $result = explode(' ', $converted_date);
        return $result['1'];
    }

    /**
     * Sync record to Joforce
     *
     * @param $args
     * @param $module
     * @param $id
     * @return \Array|bool
     * @throws \Exception
     */
    public function syncRecord($args, $module, $id = null)
    {
        global $current_user;
        $current_user = $this->user;

        if (empty($args)) {
            throw new \Exception('No post data');
        }
        $module_name = ucfirst($module);
        if (empty($id)) {
            $recordModel = \Head_Record_Model::getCleanInstance($module_name);
        } else {
            $record_exists = $this->checkRecordExists($id);
            if (!$record_exists) {
                throw new \Exception('Record not exists');
            }
            $recordModel = \Head_Record_Model::getInstanceById($id, $module_name);
        }
        $moduleModel = \Head_Module_Model::getInstance($module_name);
        $fieldModelList = $moduleModel->getFields();

        foreach ($fieldModelList as $fieldName => $fieldModel) {
            if (isset($args[$fieldName])) {
                $fieldValue = $args[$fieldName];

                //To fix time issue
                if ($module_name == 'Calendar') {
                    global $current_user;
                $sDateTime = $recordModel->get('date_start').' '.$recordModel->get('time_start');
                $eDateTime = $recordModel->get('due_date').' '.$recordModel->get('time_end');
                $dbStartDateOject = \DateTimeField::convertToDBTimeZone($sDateTime, $current_user)->format('Y-m-d H:i:s');
                $dbEndDateOject = \DateTimeField::convertToDBTimeZone($eDateTime, $current_user)->format('Y-m-d H:i:s');
                $dbStartDateTime = explode(' ', $dbStartDateOject);
                $dbEndDateTime = explode(' ', $dbEndDateOject);
                $recordModel->set('date_start', $dbStartDateTime[0]);
                $recordModel->set('time_start', $dbStartDateTime[1]);
                $recordModel->set('due_date', $dbEndDateTime[0]);
                $recordModel->set('time_end', $dbEndDateTime[1]);
                }
                $recordModel->set($fieldName, $fieldValue);
            }
        }
        if (!empty($id)) {
            $recordModel->set('id', $id);
            $recordModel->set('mode', 'edit');
            $recordModel->save();
        } else {
            $recordModel->save();
        }
        if($module_name == 'Invoice' || $module_name == 'Quotes' || $module_name == 'SalesOrder' || $module_name == 'PurchaseOrder' ){ 
            $this->addlineitem($recordModel->entity->column_fields,$args);
        }
    if($module_name =='ModComments'){
        $datas = $recordModel->getData();
        $datas['id'] = $datas['modcommentsid'];
        return $datas;
    }
        return array('record' => $recordModel->getData());
    }

    public function relateRecord($sourceModule,$sourceRecordId,$relatedModule,$relatedRecordIdList){
        global $current_user;
        $current_user = $this->user; 
        $record_exists = $this->checkRecordExists($sourceRecordId);
        if (!$record_exists) {
            throw new \Exception('Record not exists');
        }
        if(!is_array($relatedRecordIdList)) $relatedRecordIdList = Array($relatedRecordIdList);
         try {
            $sourceModuleModel = \Head_Module_Model::getInstance($sourceModule);
            $relatedModuleModel = \Head_Module_Model::getInstance($relatedModule);
            $relationModel = \Head_Relation_Model::getInstance($sourceModuleModel, $relatedModuleModel);
            foreach($relatedRecordIdList as $relatedRecordId) {
                $relationModel->addRelation($sourceRecordId,$relatedRecordId);
            }
                return ['success' => true,'message' => 'Record Related Succsfully'];
            } catch (\Exception $e) {
                $message = $e->getMessage();
                if (empty($message)) {
                    $message = 'Something went wrong';
                }
                return ['success' => false, 'message' => $message];
            }
    }
    /**
     * Delete record
     *
     * @param $module
     * @param $record_id
     * @return array
     */
    public function deleteRecord($module, $record_id)
    {
        global $current_user;
        $current_user = $this->user;
        try {
            $module_name = ucfirst($module);
            $recordModel = \Head_Record_Model::getInstanceById($record_id, $module_name);
            $recordModel->delete();
            return array('success' => true, 'id' => $record_id);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Something went wrong';
            }
            return array('success' => false, 'message' => $message);
        }
    }

    /**
     * Check record exists in Joforce
     *
     * @param $id
     * @return bool
     */
    public function checkRecordExists($id)
    {
        // Check record exists
        $checkRecord = $this->db->pquery('select crmid from jo_crmentity where crmid = ?', array($id));
        $crm_id = $this->db->query_result($checkRecord, 0, 'crmid');
        if (empty($crm_id)) {
            return false;
        }

        // Check record is deleted
        $checkRecordDeleted = $this->db->pquery('select crmid from jo_crmentity where deleted = 0 and crmid = ?', array($id));
        $crm_id = $this->db->query_result($checkRecordDeleted, 0, 'crmid');
        if (empty($crm_id)) {
            return false;
        }
        return true;
    }

    /**
     * Return related modules of given Module
     * @param $module
     * @return mixed
     */
    public function returnRelatedModules($module)
    {
        global $current_user;
        $current_user = $this->user;
        $module_name = ucfirst($module);
        $moduleModel = \Head_Module_Model::getInstance($module_name);
        $relations = $moduleModel->getRelations();
        foreach ($relations as $relation) {
            $relation_info['module_name'] = $relation->get('relatedModuleName');
            $relation_info['label'] = $relation->get('label');
            $relation_info['tab_id'] = $relation->get('related_tabid');

            $related_modules[] = $relation_info;
        }
        return array('relations' => $related_modules);
    }

    /**
     * Return record history
     *
     * @param array $data
     * @param string $record_id
     * @return array $result
     */
    public function returnRecordHistory($data, $record_id)
    {
        global $current_user;
        $current_user = $this->user;

        $options = array(
            'module' => $data['module'],
            'record' => $record_id,
            'mode'   => $data['mode'],
            'page'   => $data['page']
        );

        require_once('include/Webservices/History.php');
        $historyItems = \vtws_history($options, $current_user);

        $this->resolveReferences($historyItems, $current_user);

        $result = array('history' => $historyItems);
        return $result;
    }

    protected function resolveReferences(&$items, $user)
    {
        global $current_user;
        if (!isset($current_user)) $current_user = $user; /* Required in getEntityFieldNameDisplay */

        foreach ($items as &$item) {
            $item['modifieduser'] = $this->fetchResolvedValueForId($item['modifieduser'], $user);
            $item['label'] = $this->fetchRecordLabelForId($item['id'], $user);
            unset($item);
        }
    }

    protected function fetchResolvedValueForId($id, $user)
    {
        $label = $this->fetchRecordLabelForId($id, $user);
        return array('value' => $id, 'label' => $label);
    }

    /**
     * Return related module records
     *
     * @param $args
     * @return array
     * @throws \Exception
     * @throws \WebServiceException
     */
    public function returnRelatedRecords($args, $is_array = false) {
            
        try {
            // include_once 'include/Webservices/Query.php';
            include_once 'includes/Webservices/Query.php';
            global $current_user;
            $current_user = $this->user;
            
            $record_id = $args['id'];
            //$args['related_module']="Products";       
            $currentPage = ucfirst($args['page']);
            if (!empty($currentPage)) {
                $currentPage = $currentPage - 1;
            }
            if (empty($record_id)) {
                
                if($is_array) { return ['success' => false, "message" => "Record Id not passed"]; } 
                throw new \Exception('Record Id not passed');
            }
            
            $currentModule = ucfirst($args['module']);
            $related_module = ucfirst($args['related_module']);
            $functionHandler = $this->getRelatedFunctionHandler($currentModule, $related_module);
            if($args['related_module'] == 'ModComments'){
                $functionHandler = 'get_comments';
            }    
            if ($functionHandler) {
                
                $sourceFocus = \CRMEntity::getInstance($currentModule);
                
                $relationResult = call_user_func_array(array($sourceFocus, $functionHandler), array($record_id, getTabid($currentModule), getTabid($related_module)));
                
                $query = $relationResult['query'];
                
                $moduleModel = \Head_Module_Model::getInstance($currentModule);
                
                $nameFields = $moduleModel->getNameFields();
                if($args['related_module'] == 'ModComments'){ 
                    if($args['parent_comments'] != '') {
                        $db = \PearDatabase::getInstance();
                        $query = 'SELECT jo_modcomments.parent_comments, jo_crmentity.createdtime, 
                                jo_modcomments.commentcontent as content, jo_modcomments.related_to,
                                jo_modcomments.modcommentsid, jo_modcomments.userid,
                                jo_modcomments.filename FROM jo_modcomments  
                                INNER JOIN jo_crmentity ON jo_modcomments.modcommentsid = jo_crmentity.crmid 
                                LEFT JOIN jo_users ON jo_crmentity.smownerid = jo_users.id 
                                LEFT JOIN jo_groups ON jo_crmentity.smownerid = jo_groups.groupid  
                                WHERE jo_crmentity.deleted=0 AND jo_modcomments.modcommentsid > 0 AND 
                                related_to = ? AND jo_modcomments.parent_comments = ? ORDER BY jo_crmentity.createdtime ASC';
                        
                        $result = $db->pquery($query, array($record_id, $args['parent_comments']));
                        $rows = $db->num_rows($result);
                        // echo json_encode($rows);die();
                        $recentComments = array();
                        
                        for ($key = 0; $key<$rows; $key++) {
                            $row = $db->query_result_rowdata($result, $key);
                            $recentComments[$key]['parent_comments'] = $row['parent_comments'];
                            $recentComments[$key]['content'] = $row['content'];
                            $recentComments[$key]['modcommentsid'] = $row['modcommentsid'];
                            $recentComments[$key]['filename'] = $row['filename'];
                            $recentComments[$key]['userid'] = $row['userid'];
                            $getUsermodule = \Head_Module_Model::getInstance('Users');
                            $getuserrecord = \Head_Record_Model::getInstanceById($row['userid'], $getUsermodule);
                            $userrecord = $getuserrecord->getData();
                            $recentComments[$key]['username'] = $userrecord['user_name'];
                            $recentComments[$key]['timestamp'] = self::formatDateInStrings($row['createdtime']);
                            $recentComments[$key]['childcount'] = self::getChildCommentsCount($row['modcommentsid'], $row['related_to']);
                        }
                    } else {
                        
                        $parentCommentModels = \ModComments_Record_Model::getAllParentComments($record_id);
                        $recentComments = array();
                        foreach($parentCommentModels as $key => $comments) {
                            $recentComments[$key]['parent_comments'] = $comments->get('parent_comments');
                            $recentComments[$key]['content'] = $comments->get('commentcontent');
                            $recentComments[$key]['modcommentsid'] = $comments->get('modcommentsid');
                            $recentComments[$key]['filename'] = $comments->get('filename');
                            $recentComments[$key]['userid'] = $comments->get('userid');
                            $getUsermodule = \Head_Module_Model::getInstance('Users');
                            $getuserrecord = \Head_Record_Model::getInstanceById($comments->get('userid'), $getUsermodule);
                            $userrecord = $getuserrecord->getData();
                            $recentComments[$key]['username'] = $userrecord['user_name'];
                            $recentComments[$key]['timestamp'] = self::formatDateInStrings($comments->get('createdtime'));
                            $recentComments[$key]['childcount'] = self::getChildCommentsCount($comments->get('modcommentsid'), $comments->get('related_to'));
                        }
                    }
                    if($is_array) { return ['success' => true, 'related_records' => $recentComments]; } 
                    return ['related_records' => $recentComments];
                }
                $querySEtype = "jo_crmentity.setype as setype";
                if ($related_module == 'Calendar') {
                    $querySEtype = "jo_activity.activitytype as setype";
                }
                
                $query = sprintf("SELECT jo_crmentity.crmid, $querySEtype %s", substr($query, stripos($query, 'FROM')));
                
                $queryResult = $this->db->query($query);
                
                // Gather resolved record id's
                $relatedRecords = array();
                
                while ($row = $this->db->fetch_array($queryResult)) {
                    
                    $targetSEtype = $row['setype'];
                    
                    if ($related_module == 'Calendar') {
                        if ($row['setype'] != 'Task' && $row['setype'] != 'Emails') {
                            $targetSEtype = 'Events';
                        } else {
                            $targetSEtype = $related_module;
                        }
                    }
                    $moduleWSId = $this->getModuleWSId($targetSEtype);
                    $relatedRecords[] = sprintf("%sx%s", $moduleWSId, $row['crmid']);
                }
                
                $FETCH_LIMIT = 0;
                $queryResult = [];
                if (count($relatedRecords) > 0) {
                    
                    // Perform query to get record information with grouping
                    $ws_query = sprintf("SELECT * FROM %s WHERE id IN ('%s')", $related_module, implode("','", $relatedRecords));
                    $FETCH_LIMIT = $this->records_per_page;
                    $startLimit = $currentPage * $FETCH_LIMIT;
                    
                    $queryWithLimit = sprintf("%s LIMIT %u,%u;", $ws_query, $startLimit, ($FETCH_LIMIT + 1));
                    $queryResult = \vtws_query($queryWithLimit, $current_user);
                    
                }
                if (count($queryResult) > 0) {
                    // Resolve the ID
                    // TODO move the resolve to the GraphQL
                    foreach ($queryResult as $key => $single_entity) {
                        list($entity_tab_id, $entity_id) = explode('x', $single_entity['id']);
                        $queryResult[$key]['id'] = $entity_id;
                    }
                }
                
                $moreRecords = false;
                if ((count($queryResult) == $FETCH_LIMIT) && count($queryResult) != 0) {
                    $moreRecords = true;
                }
                if($is_array) { 
                    return ['success' => true, 'related_records' => $queryResult, 'page' => $currentPage, "nameFields" => $nameFields, "moreRecords" => $moreRecords]; } 
                return ['related_records' => $queryResult, 'page' => $currentPage, "nameFields" => $nameFields, "moreRecords" => $moreRecords];
            }
            if($is_array) {
                return ['success' => false, "message" => "No handler for given module"];
            }
            throw new \Exception('No handler for given module');
        } catch(\Exception $e) {
            return ['success' => false, "message" => "$e->getMessage()"];
        }
    }
     static function getChildCommentsCount($parentRecordId, $related_to) {
        $db = \PearDatabase::getInstance();
        $query = "SELECT 1 FROM jo_modcomments WHERE parent_comments = ? AND related_to = ?";
        $result = $db->pquery($query, array($parentRecordId, $related_to));
        if ($db->num_rows($result)) {
            return $db->num_rows($result);
        } else {
            return 0;
        }
    }
     static function formatDateInStrings($date) {
        $currentDateTime = date("Y-m-d H:i:s");
        $dateTime = date("Y-m-d H:i:s", strtotime($date));
        $seconds = strtotime($currentDateTime) - strtotime($dateTime);
        if ($seconds == 0) {
            return "Just now";
        }
        if ($seconds > 0) {
            $prefix = '';
            $suffix = " ago";
        } else {
            if ($seconds < 0) {
                $prefix = "due in ";
                $suffix = '';
                $seconds = - $seconds;
            }
        }
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $days = floor($hours / 24);
        $months = floor($days / 30);
        if ($seconds < 60) {
            return $prefix . self::pluralize($seconds, "second") . $suffix;
        }
        if ($minutes < 60) {
            return $prefix . self::pluralize($minutes, "minute") . $suffix;
        }
        if ($hours < 24) {
            return $prefix . self::pluralize($hours, "hour") . $suffix;
        }
        if ($days < 30) {
            return $prefix . self::pluralize($days, "day") . $suffix;
        }
        if ($months < 12) {
            return $prefix . self::pluralize($months, "month") . $suffix;
        }
        if ($months > 11) {
            return $prefix . self::pluralize(floor($days / 365), "year") . $suffix;
        }
    }
    static function pluralize($count, $text) {
        return $count . " " . ($count == 1 ? $text : $text . "s");
    }

    /**
     * Return handler function of related module
     *
     * @param $module
     * @param $related_module
     * @return bool
     */
    public function getRelatedFunctionHandler($module, $related_module)
    {
        $relationResult = $this->db->pquery("SELECT name FROM jo_relatedlists WHERE tabid = ? and related_tabid = ? and presence = 0", array(getTabid($module), getTabid($related_module)));
        $functionName = false;
        if ($this->db->num_rows($relationResult)) $functionName = $this->db->query_result($relationResult, 0, 'name');
        return $functionName;
    }

    public static function getFilePublicURL($imageId, $imageName) {
		$publicUrl = '';
        $fileId = $imageId;
        $fileName = $imageName;
		if ($fileId) {
			$publicUrl = "public.php?fid=$fileId&key=".md5($fileName);
		}
		return $publicUrl;
	}

    /**
     * Return Module WS Id for given Module
     *
     * @param $module
     * @return bool
     */
    public function getModuleWSId($module)
    {
        $result = $this->db->pquery("SELECT id FROM jo_ws_entity WHERE name = ?", array($module));
        if ($result && $this->db->num_rows($result)) {
            return $this->db->query_result($result, 0, 'id');
        }
        return false;
    }

    /**
     * Generate fields
     *
     * @param $args
     * @return array|bool
     * @throws \WebServiceException
     */
    public function generateFields($args)
    {
        if ($args['action'] == 'get_modules') {
            return [
                'name' => $args['module'] . 'Field',
                'description' => "CRM {$args['module']} fields",
                'type' => 'list',
                'action' => 'get_modules',
                'fields' => [
                    'id' => [
                        'type' => Type::id(),
                    ],
                    'name' => [
                        'type' => Type::string(),
                    ],
                    'isEntity' => [
                        'type' => Type::id(),
                    ],
                    'label' => [
                        'type' => Type::string(),
                    ],
                    'singular' => [
                        'type' => Type::string()
                    ],
                ]
            ];
        } else if ($args['action'] == 'menu') {
            $more_menu = new ObjectType([
                'name' => 'MainMenuInformation',
                'description' => 'Main Menu information',
                'fields' => [
                    'tabid' => Type::id(),
                    'name' => Type::string(),
                    'label' => Type::string(),
                    'image_url' => Type::string(),
                ]
            ]);

            $module_info = new ObjectType([
                'name' => 'ModuleInformation',
                'description' => 'Module Information',
                'fields' => [
                    'tabid' => Type::int(),
                    'name' => Type::string(),
                    'label' => Type::string(),
                    'image_url' => Type::string(),
                ]
            ]);

            $section_module = new ObjectType([
                'name' => 'MoreMenuInformation',
                'description' => 'More Menu information',
                'fields' => [
                    'section' => Type::string(),
                    'module_info' => Type::listOf($module_info),
                ]
            ]);

            return [
                'name' => 'UserMenu',
                'description' => "User menu",
                'type' => 'single',
                'action' => 'get_menu',
                'fields' => [
                    'Main' => Type::listOf($section_module),
                    'More' => Type::listOf($more_menu)
                ]
            ];
        } else if ($args['action'] == 'global_search') {

            $jo_record = new ObjectType([
                'name' => 'RecordStructure',
                'description' => 'Record structure',
                'fields' => [
                    'label' => Type::string(),
                    'crmid' => Type::int(),
                    'createdtime' => Type::string()
                ]
            ]);

            $search_results = new ObjectType([
                'name' => 'SearchResults',
                'description' => 'Search results',
                'fields' => [
                    'module' => Type::string(),
                    'data' => Type::listOf($jo_record),
                ]
            ]);

            return [
                'name' => 'GlobalSearch',
                'description' => "Global Search",
                'args' => [
                    'value' => Type::nonNull(Type::string()),
                    'searchModule' => Type::string()
                ],
                'action' => 'global_search',
                'fields' => [
                    'results' => Type::listOf($search_results),
                ]
            ];
        } else if ($args['action'] == 'calendar_view') {
            if (isset($args['day'])) {

                $calendarObject = new ObjectType([
                    'name' => 'CalendarInformation',
                    'description' => 'Calendar information',
                    'fields' => [
                        'id' => Type::int(),
                        'visibility' => Type::string(),
                        'activitytype' => Type::string(),
                        'status' => Type::string(),
                        'priority' => Type::string(),
                        'userfullname' => Type::string(),
                        'title' => Type::string(),
                        'start' => Type::string(),
                        'startDate' => Type::string(),
                        'startTime' => Type::string(),
                        'end' => Type::string(),
                        'endDate' => Type::string(),
                        'endTime' => Type::string(),
                        'recurringcheck' => Type::string(),
                    ]
                ]);
            } else {

                $calendarObject = new ObjectType([
                    'name' => 'CalendarInformation',
                    'description' => 'Calendar information',
                    'fields' => [
                        'date' => Type::string(),
                        'count' => Type::int()
                    ]
                ]);
            }

            return [
                'name' => $args['module'] . 'Module',
                'description' => "Joforce - Calendar",
                'type' => 'single',
                'action' => 'calendar_view',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'date' => Type::nonNull(Type::string()),
                    'day' => Type::boolean(),
                ],
                'fields' => [
                    'events' => Type::listOf($calendarObject),
                ]
            ];
        } else if ($args['action'] == 'describe') {

            $picklistType = new ObjectType([
                'name' => 'PicklistType',
                'Description' => 'Picklist field type',
                'fields' => [
                    'label' => Type::string(),
                    'value' => Type::string()
                ]
            ]);

            // Joforce Field Type - string, integer, boolean etc
            $type = new ObjectType([
                'name' => 'FieldType',
                'description' => 'Field type',
                'fields' => [
                    'name' => Type::string(),
                    'refersTo' => Type::listOf(Type::string()),
                    'defaultValue' => Type::string(),
                    'picklistValues' => Type::listOf($picklistType),
                    'format' => Type::string(),
                ]
            ]);

            $fields = new ObjectType([
                'name' => 'Fields',
                'description' => 'CRM Fields',
                'fields' => [
                    'name' => Type::string(),
                    'label' => Type::string(),
                    'mandatory' => Type::boolean(),
                    'nullable' => Type::boolean(),
                    'editable' => Type::boolean(),
                    'default' => Type::string(),
                    'headerfield' => Type::boolean(),
                    'summaryfield' => Type::boolean(),
                    'type' => $type,
                ]
            ]);
            return [
                'name' => $args['module'] . 'FieldInfo',
                'description' => "Joforce - {$args['module']} fields info",
                'type' => 'single',
                'action' => 'get_schema',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                ],
                'fields' => [
                    'label' => Type::string(),
                    'name' => Type::string(),
                    'createable' => Type::boolean(),
                    'updateable' => Type::boolean(),
                    'deleteable' => Type::boolean(),
                    'retrieveable' => Type::id(),
                    'nameFields' => Type::listOf(Type::string()),
                    'fields' => Type::listOf($fields),
                ]
            ];
        } else if ($args['action'] == 'describe_with_block') {

            $picklistType = new ObjectType([
                'name' => 'PicklistType',
                'Description' => 'Picklist field type',
                'fields' => [
                    'label' => Type::string(),
                    'value' => Type::string()
                ]
            ]);

            // Vtiger Field Type - string, integer, boolean etc
            $type = new ObjectType([
                'name' => 'FieldType',
                'description' => 'Field type',
                'fields' => [
                    'name' => Type::string(),
                    'refersTo' => Type::listOf(Type::string()),
                    'defaultValue' => Type::string(),
                    'picklistValues' => Type::listOf($picklistType),
                    'format' => Type::string(),
                ]
            ]);
             
            $fields = new ObjectType([
                'name' => 'Fields',
                'description' => 'CRM Fields',
                'fields' => [
                    'name' => Type::string(),
                    'label' => Type::string(),
                    'mandatory' => Type::boolean(),
                    'nullable' => Type::boolean(),
                    'editable' => Type::boolean(),
                    'default' => Type::string(),
                    'headerfield' => Type::boolean(),
                    'summaryfield' => Type::boolean(),
                    'type' => $type,
                    ]
            ]);
            $blocks =new ObjectType([
                'name' => 'blocks',
                'description' => 'CRM Fields',
                'fields' => [
                    'id' => Type::id(),
                    'name' => Type::string(),
                    'blocks'=>  Type::listOf($fields),
                    ]
            ]);
            return [
                'name' => $args['module'] . 'FieldInfo',
                'description' => "Vtiger - {$args['module']} fields info",
                'type' => 'single',
                'action' => 'get_schema',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                ],
                'fields' => [
                    'label' => Type::string(),
                    'name' => Type::string(),
                    'createable' => Type::boolean(),
                    'updateable' => Type::boolean(),
                    'deleteable' => Type::boolean(),
                    'retrieveable' => Type::id(),
                    'nameFields' => Type::listOf(Type::string()),
                    'fields'=>  Type::listOf($blocks),
                    'count'=>Type::int()
                ],
            ];
        } else if ($args['action'] == 'filters') {

            $filter = new ObjectType([
                'name' => 'FilterInformation',
                'description' => 'Filter information',
                'fields' => [
                    'id' => Type::id(),
                    'name' => Type::string(),
                    'default' => Type::string()
                ]
            ]);

            return [
                'name' => $args['module'] . 'Filters',
                'description' => "Joforce - {$args['module']} filters",
                'type' => 'single',
                'action' => 'get_filters',
                'args' => [
                    'module' => Type::nonNull(Type::string())
                ],
                'fields' => [
                    'Mine' => Type::listOf($filter),
                    'Shared' => Type::listOf($filter),
                    'Others' => Type::listOf($filter),
                ]
            ];
        } else if ($args['action'] == 'filter_columns') {

            $filter = new ObjectType([
                'name' => 'FilterInformation',
                'description' => 'Filter information',
                'fields' => [
                    'fieldname' => Type::string(),
                    'fieldlabel' => Type::string()
                ]
            ]);

            return [
                'name' => 'FilterColumns',
                'description' => "Filter column fields",
                'type' => 'single',
                'action' => 'get_filter_columns',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id())
                ],
                'fields' => [
                    'filter' => Type::listOf($filter),
                ]
            ];
        } else if ($args['action'] == 'get_module_relations') {

            $relationType = new ObjectType([
                'name' => 'RelationsInformation',
                'description' => 'Relations information',
                'fields' => [
                    'module_name' => Type::string(),
                    'label' => Type::string(),
                    'tab_id' => Type::id(),
                    'actions' => Type::string(),

                ]
            ]);

            return [
                'name' => 'ModuleRelations',
                'description' => "Module relations information",
                'type' => 'single',
                'action' => 'get_module_relations',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'related_module' => Type::nonNull(Type::string())
                    
                ],
                'fields' => [
                    'relations' => Type::listOf($relationType),
                ]
            ];
        } else if ($args['action'] == 'widget_info') {

            $widgetType = new ObjectType([
                'name' => 'WidgetInfo',
                'description' => 'Widget Information',
                'fields' => [
                    'count' => Type::int(),
                    'value' => Type::string(),
                    'label' => Type::string()
                ]
            ]);

            return [
                'name' => 'JoforceWidget',
                'description' => "Joforce widget",
                'type' => 'single',
                'action' => 'widget_info',
                'args' => [
                    'name' => Type::nonNull(Type::string()),
                ],
                'fields' => [
                    'data' => Type::listOf($widgetType)
                ],
            ];
        } else if ($args['action'] == 'get_related_records') {

         $module_fields = $this->generateModuleFields($args['related_module']);

            $moduleFieldsType = new ObjectType([
                'name' => $args['module'] . 'FieldsType',
                'description' => $args['module'] . ' fields type',
                'fields' => $module_fields
            ]);

            if($args['related_module'] == 'ModComments') {
                $fields = new ObjectType([
                    'name' => $args['module'] . 'FieldsType',
                    'description' => $args['module'] . ' fields type',
                    'fields' => [
                        'username' => Type::string(),
                        'content' => Type::string(),
                        'modcommentsid' => Type::int(),
                        'userid' => Type::int(),
                        'timestamp' => Type::string(),
                        'parent_comments' => Type::int(),
                        'filename' => Type::string(),
                        'childcount' => Type::int(),
                    ],
                ]);
                return [
                    'name' => 'RelatedModuleRecords',
                    'description' => "Related module records",
                    'type' => 'c',
                    'action' => 'get_related_records',
                    'args' => [
                        'module' => Type::nonNull(Type::string()),
                        'id' => Type::nonNull(Type::id()),
                        'related_module' => Type::nonNull(Type::string()),
                        'parent_comments' => Type::int(),
                    ],
                    'fields' => [
                        'related_records' => Type::listOf($fields),
                    ]
                ];
            }

           return [
                'name' => 'RelatedModuleRecords',
                'description' => "Related module records",
                'type' => 'single',
                'action' => 'get_related_records',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id()),
                    'related_module' => Type::nonNull(Type::string()),
                    'page' => Type::nonNull(Type::id()),
                ],
                'fields' => [
                    'related_records' => Type::listOf($moduleFieldsType),
                    'page' => Type::int(),
                    'moreRecords' => Type::boolean(),
                    'nameFields' => Type::listOf(Type::string())
                ]
            ];  
        } else if ($args['action'] == 'get_users') {

            $userInfo = new ObjectType([
                'name' => 'UserInfo',
                'description' => 'User/Group Information',
                'fields' => [
                    'id' => Type::int(),
                    'label' => Type::string(),
                    'is_user' => Type::boolean()
                ]
            ]);

            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'action' => 'get_users',
                'args' => [
                    'groups' => Type::boolean(),
                ],
                'fields' => [
                    'response' => Type::listOf($userInfo),
                ]
            ];
        }
        // TODO Add related module type. Should be able to query in a single shot
        else if ($args['action'] == 'list') {

            $headerType = new ObjectType([
                'name' => 'HeaderFields',
                'description' => 'Header field information',
                'fields' => [
                    'name' => Type::string(),
                    'label' => Type::string(),
                    'fieldType' => Type::string()
                ]
            ]);

            $module_fields = $this->generateModuleFields($args['module']);
            $module_fields['name'] = Type::string();
            $module_fields['common'] = Type::string();
            $module_fields['common_label'] = Type::string();
            $module_fields['created_at'] = Type::string();
            $module_fields['is_favourite'] = Type::boolean();

            $moduleFieldsType = new ObjectType([
                'name' => $args['module'] . 'FieldsType',
                'description' => $args['module'] . ' fields type',
                'fields' => $module_fields
            ]);

            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'action' => 'get_records',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'page' => Type::int(),
                    'limit' => Type::int(),
                    'order_by' => Type::string(),
                    'sort_by' => Type::string(),
                    'filter_id' => Type::int(),
                    'search_key' => Type::string(),
                    'search_value' => Type::string()
                ],
                'fields' => [
                    'records' => Type::listOf($moduleFieldsType),
                    'headers' => Type::listOf($headerType),
                    'selectedFilter' => Type::int(),
                    'nameFields' => Type::listOf(Type::string()),
                    'moreRecords' => Type::boolean(),
                    'orderBy' => Type::string(),
                    'sortOrder' => Type::string(),
                    'page' => Type::int(),
                ]
            ];
        } else if ($args['action'] == 'get_recordJOFORCE') { //changed schema like vtiger

            $module_fields = $this->generateBlockType();

            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'fields' => $module_fields,
                'action' => 'get_record',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id())
                ]
            ];
        } else if ($args['action'] == 'forgotpassword') {
            return [
                'name' => 'ForgotPassword',
                'description' => "Joforce - ForgotPassword",
                'type' => 'single',
                'action' => 'forgotpassword',
                'args' => [
                    'username' => Type::nonNull(Type::string())
                ],
                'fields' => [
                    'success' => Type::boolean(),
                    'user_id' => Type::string(),
                    'message' => Type::string(),
                ]
            ];
        } else if ($args['action'] == 'get_record') {
            $module_fields = $this->generateModuleFields($args['module']);

            if ($args['module'] == 'Contacts') {
                $module_fields['imageurl'] = Type::string();
            }

            if ($args['module'] == 'Documents') {
                $module_fields['file'] = Type::string();
            }
            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'fields' => $module_fields,
                'action' => 'get_record',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id())
                ]
            ];
        } else if ($args['action'] == 'get_forecast') { // jo_potential for forcast
            $module_fields = [];
            $module_fields['potentialname'] = Type::string();
            $moduleFieldsType = new ObjectType([
                'name' => 'PotentialsFieldsType',
                'description' => 'Potentials fields type',
                'fields' => $module_fields
            ]);

            return [
                'name' => 'ForecastFields',
                'description' => "Forecast fields",
                'type' => 'single',
                'action' => 'get_forecast',
                'args' => [
                    'month' => Type::int(),
                    'year' => Type::int()
                ],
                'fields' => [
                    'records' => Type::listOf($moduleFieldsType),
                    'moreRecords' => Type::boolean(),
                    'page' => Type::int(),
                    'orderBy' => Type::string(),
                    'sortOrder' => Type::string()
                ]
            ];
        } else if ($args['action'] == 'tax') { 
            if($args['mode'] == 'region' || $args['mode'] == 'tax_by_region') {
                if($args['mode'] == 'region') {
                    $fields = new ObjectType([
                        'name'        => $args['module'] . 'FieldsType',
                        'description' => $args['module'] . ' fields type',
                        'fields'      => [
                            'regionid' => Type::int(),
                            'regionname' => Type::string(),
                        ],
                    ]);
                } else if($args['mode'] == 'tax_by_region'){
                    $fields = new ObjectType([
                        'name'        => $args['module'] . 'FieldsType',
                        'description' => $args['module'] . ' fields type',
                        'fields'      => [
                            'id' => Type::int(),
                            'taxname' => Type::string(),
                            'taxlabel' => Type::string(),
                            'method' => Type::string(),
                            'type' => Type::string(),
                            'percentage' => Type::string(),
                            'compoundon' => Type::string(),
                            'region_name' => Type::string(),
                            'region_tax' => Type::int(),
                        ],
                    ]);
                }
                
                return [
                    'name' => $args['module'] . 'Fields',
                    'description' => "{$args['module']} fields",
                    'type' => 'single',
                    'action' => 'get_records',
                    'args' => [
                        'module' => Type::nonNull(Type::string()),
                        'filter_id' => Type::int(),              
                        'mode' => Type::string(),              
                    ],
                    'fields' => [
                        'records' => Type::listOf($fields),
                    ]
                ];
            }

            $module_fields = $this->generateModuleFields($args['module']);
            $moduleFieldsType = new ObjectType([
                'name'        => $args['module'] . 'FieldsType',
                'description' => $args['module'] . ' fields type',
                'fields'      => $module_fields
            ]);

            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'action' => 'get_records',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'filter_id' => Type::int(),              
                    'mode' => Type::string(),          
                ],
                'fields' => [
                    'records' => Type::listOf($moduleFieldsType),
                ]
            ];
        } else if ($args['action'] == 'savepassword') {
            return [
                'name' => 'SavePassword',
                'description' => "Joforce - SavePassword",
                'type' => 'single',
                'action' => 'savepassword',
                'args' => [
                    'username' => Type::nonNull(Type::string()),
                    'confirmpassword' => Type::nonNull(Type::string()),
                    'password' => Type::nonNull(Type::string()),
                ],
                'fields' => [
                    'success' => Type::boolean(),
                    'message' => Type::string(),
                ]
            ];
        } else if ($args['action'] == 'feedback') {
            return [
                'name' => 'FeedBack',
                'description' => "Joforce - Feedback",
                'type' => 'single',
                'action' => 'feedback',
                'args' => [
                    'content' => Type::nonNull(Type::string())
                ],
                'fields' => [
                    'mail_status' => Type::boolean(),
                    'message' => Type::string()
                ]
            ];
        } else if ($args['action'] == 'uploadprofile') {
            return [
                'name' => 'UploadProfile',
                'description' => "Joforce - UploadProfile",
                'type' => 'single',
                'action' => 'uploadprofile',
                'args' => [
                    'filename' => Type::nonNull(Type::string()),
                    'filetype' => Type::nonNull(Type::string()),
                    'username' => Type::nonNull(Type::string()),
                    'imagecontent' => Type::nonNull(Type::string())
                ],
                'fields' => [
                    'success' => Type::boolean(),
                    'message' => Type::string(),
                    'imageurl' => Type::string()
                ]
            ];
        } else if ($args['action'] == 'getprofile') {
            return [
                'name' => 'GetProfile',
                'description' => "Joforce - GetProfile",
                'type' => 'single',
                'action' => 'getprofile',
                'args' => [
                    'username' => Type::nonNull(Type::string()),
                ],
                'fields' => [
                    'success' => Type::boolean(),
                    'imageurl' => Type::string(),
                    'message' => Type::string()
                ]
            ];
        } else if ($args['action'] == 'get_all_relation') {
            $moduleFieldsType = new ObjectType([
                'name' => $args['module'] . 'RelatedFieldsType',
                'description' => $args['module'] . ' related fields type',
                'fields' => [
                    'name' => Type::string(),
                    'common' => Type::string(),
                    'common_label' => Type::string(),
                    'created_at' => Type::string(),
                    'is_favourite' => Type::string(),
                ]
            ]);

            $fields = new ObjectType([
                'name' => 'Fields',
                'description' => 'CRM Fields',
                'fields' => [
                    'related_records' => Type::listOf($moduleFieldsType),
                    'page' => Type::int(),
                    'moreRecords' => Type::boolean(),
                    'module' => Type::string()
                ]
            ]);

            return [
                'name' => 'RelatedModuleRecords',
                'description' => "Related module records",
                'type' => 'single',
                'action' => 'get_related_records',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id())
                ],
                'fields' => [
                    'blocks'=>  Type::listOf($fields),
                ]
            ];
        } else {
            $module_fields = $this->generateModuleFields($args['module']);
            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'fields' => $module_fields,
                'action' => 'get_record',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id())
                ]
            ];
        }
        return false;
    }

    /**
     * Generate Block Field Syntax
     *
     * @return array
     */
    public function generateBlockType()
    {
        $defaultValue = new ObjectType([
            'name' => 'DefaultValue',
            'description' => 'Default Value',
            'fields' => [
                'defaultValue' => Type::string(),
            ]
        ]);

        $fieldInformation = new ObjectType([
            'name' => 'FieldInformation',
            'description' => 'Field Information',
            'fields' => [
                'name' => Type::string(),
                'value' => Type::string(),
                'record_label' => Type::string(), // Record Label for related record
                'label' => Type::string(),
                'uitype' => Type::int(),
                'type' => Type::listOf($defaultValue),
            ]
        ]);

        $blockInformation = new ObjectType([
            'name' => 'BlockInformation',
            'description' => 'Block Information',
            'fields' => [
                'label' => Type::string(),
                'fields' => Type::listOf($fieldInformation),
            ]
        ]);

        $module_fields = array(
            'blocks' => Type::listOf($blockInformation),
        );

        return $module_fields;
    }

    /**
     * Generate Module fields
     *
     * @param $module
     * @return mixed
     * @throws \WebServiceException
     */
    public function generateModuleFields($module)
    {
        $module_schema = [];
        $module_name = ucfirst($module);
        include_once 'includes/Webservices/DescribeObject.php';
        $this->module_fields_info = vtws_describe($module_name, $this->user);
        if (isset($this->module_fields_info['fields'])) {
            foreach ($this->module_fields_info['fields'] as $module_field) {
                // Check if field is related to other module or field is assigned_user_id. If so, add the module type as string
                if ($module_field['type']['name'] == 'reference' || $module_field['name'] == 'assigned_user_id') {
                    // We are passing reference as String. Need to resolve the Id to Value before passing.
                    $module_schema[$module_field['name']] = Type::string();
                } else if ($module_field['name'] == 'id') {
                    $module_schema[$module_field['name']] = Type::int();
                } else {
                    $module_schema[$module_field['name']] = $this->typesMapping($module_field['type']['name']);
                }
            }
        }
        $looping_array = array("productid", "quantity", "listprice","comment", "product_id" );
        if($module_name=='Invoice' || $module_name=='Quotes' || $module_name=='SalesOrder' || $module_name=='PurchaseOrder'){
            for ($i=1;$i <=50 ; $i++) {
                foreach ($module_schema as $key_change  => $value) { 
                    if(in_array($key_change, $looping_array)){
                       $module_schema[$key_change.$i] =$value ; 
                       if($key_change=='productid') 
                       $module_schema[$key_change.$i.'_id'] =$value ;
                    }                   
                }
            }        
        } 
        return $module_schema;
    }

    /**
     * Return filter columns
     *
     * @param $args
     * @return array
     * @throws \Exception
     */
    public function getFilterColumns($args)
    {
        $module = $args['module'];
        $module_name = ucfirst($module);
        $tab_id = getTabid($module_name);
        $filter_id = $args['id'];
        if (empty($filter_id)) {
            throw new \Exception('Filter Id is mandatory');
        }

        $customView = new \CustomView($module_name);
        $filter_columns = $customView->getColumnsListByCvid($filter_id);
        if (empty($filter_columns)) {
            throw new \Exception('No column present in given Filter Id');
        }

        foreach ($filter_columns as $filter_column) {
            $details = explode(':', $filter_column);
            if (empty($details[2]) && $details[1] == 'crmid' && $details[0] == 'jo_crmentity') {
                $name = 'id';
                $customViewFields[] = $name;
            } else {
                $fields[] = $details[2];
                $customViewFields[] = $details[2];
            }
        }

        $filter_field_names = "'" . implode("','", $fields) . "'";
        $getFieldLabels = $this->db->pquery("select fieldname, fieldlabel from jo_field where fieldname IN ($filter_field_names) and tabid = ?", array($tab_id));
        while ($field_info = $this->db->fetch_row($getFieldLabels)) {
            $response[] = $field_info;
        }

        return ['filter' => $response];
    }

    /**
     * Return entity modules of Joforce
     *
     * @return array
     * @throws \WebServiceException
     */
    public function getJoModules()
    {
        $result = $this->db->pquery("SELECT id, name FROM jo_ws_entity WHERE ismodule = 1 and name NOT IN ('Users', 'Events')", array());
        while ($module_info = $this->db->fetch_array($result)) {
            $modules[$module_info['name']] = $module_info['id'];
        }

        $list_types = vtws_listtypes(null, $this->user);

        $listing = array();
        foreach ($list_types['types'] as $index => $module_name) {
            if (!isset($modules[$module_name])) continue;

            $listing[] = [
                'id'   => $modules[$module_name],
                'name' => $module_name,
                'isEntity' => $list_types['information'][$module_name]['isEntity'],
                'label' => $list_types['information'][$module_name]['label'],
                'singular' => $list_types['information'][$module_name]['singular'],
            ];
        }
        return $listing;
    }

    /**
     * Return user filters
     *
     * @param $args
     * @return array
     */
    public function getUserFilters($args)
    {
        global $current_user;
        $current_user = $this->user;

        $allFilters = \CustomView_Record_Model::getAllByGroup($args['module']);
        unset($allFilters['Public']);
        $result = array('Mine'=>array(),'Shared'=>array());
        if ($allFilters) {
            foreach ($allFilters as $group => $filters) {
                $result[$group] = array();
                foreach ($filters as $filter) {
                    $result[$group][] = array('id' => $filter->get('cvid'), 'name' => $filter->get('viewname'), 'default' => $filter->isDefault());
                }
            }
        }
        return $result;
    }

    /**
     * Return module fields
     *
     * @param $module
     * @return mixed
     * @throws \Exception
     * @throws \WebServiceException
     */
    public function getModuleFields($module,$requested_data)
    {
        $module = ucfirst($module);
        include_once 'includes/Webservices/DescribeObject.php';
        $describeInfo = vtws_describe($module, $this->user);

        $fields = $describeInfo['fields'];

        $moduleModel = \Head_Module_Model::getInstance($module);
        $nameFields = $moduleModel->getNameFields();
        if (is_string($nameFields)) {
            $nameFieldModel = $moduleModel->getField($nameFields);
            $headerFields[] = $nameFields;
            $fields = array('name' => $nameFieldModel->get('name'), 'label' => $nameFieldModel->get('label'), 'fieldType' => $nameFieldModel->getFieldDataType());
        } else if (is_array($nameFields)) {
            foreach ($nameFields as $nameField) {
                $nameFieldModel = $moduleModel->getField($nameField);
                $headerFields[] = $nameField;
                $fields[] = array('name' => $nameFieldModel->get('name'), 'label' => $nameFieldModel->get('label'), 'fieldType' => $nameFieldModel->getFieldDataType());
            }
        }

        $fieldModels = $moduleModel->getFields();

        //&& issue start
        foreach ($fields as $index => $field) {
    if ($requested_data['status'] == "taskstatus"){
                if($field['name'] == "eventstatus"){
                        continue;
                }
        }
        elseif ($requested_data['status'] == "eventstatus"){
                if($field['name'] == "taskstatus"){
                        continue;
                }
        }
     if(!isset($field['type'])) {
                    continue;
            }
        if($field['name']=='salutationtype'){
                $field_saluation = \Head_Field_Model::getInstance('salutationtype', $moduleModel);
                $picklist = $field_saluation->getPicklistValues();
                $lists = array();
                foreach ($picklist as $keys => $values) {
                    $lists[] = array('label' => $keys,'value' => $values);

                }
                $field['type']['name'] = 'picklist';
                $field['type']['picklistValues'] = $lists;
            }
            if ($module == 'PurchaseOrder' || $module == 'Invoice' || $module == 'SalesOrder' || $module == 'Quotes') {

                if (strpos($field['name'], '&') !== false) {
                    continue;
                }
            }
            //&& issue end       

            if ($field['type']['name'] == 'boolean' && $field['default'] == 'on') {
                $field['default'] = true;
            }

            if ($module == 'Calendar'|| $module == 'Events') {
                if ($field['name'] == 'taskstatus') {
                    $field['label'] = 'Task Status';
                }
                if ($field['name'] == 'activitytype' || $field['name'] == 'eventstatus' || $field['name'] == 'visibility') {
                    $field['mandatory'] = true;
                }
            }

            $fieldModel = $fieldModels[$field['name']];
            if ($fieldModel) {
                $field['headerfield'] = $fieldModel->get('headerfield');
                $field['summaryfield'] = $fieldModel->get('summaryfield');
            }
         if($field['headerfield']==''){
                $field['headerfield']=false;
            }if($field['summaryfield']==''){
                $field['summaryfield']=false;
            }if($field['nullable']==''){
                $field['nullable']=false;
            }if($field['mandatory']==''){
                $field['mandatory']=false;
            }if($field['type']==''){
                $field['type']=false;
            }if($field['editable']==''){
                $field['editable']=false;
            }
            $newFields[] = $field;
        }
        $fields = null;
        $looping_array = array("productid", "quantity", "listprice","comment" );
        if($module=='Invoice' || $module=='Quotes' || $module=='SalesOrder' || $module=='PurchaseOrder'){ 
            if(!empty($id=$_REQUEST['id'])){
                $recordModel = \Head_Record_Model::getInstanceById($id, $module);
                $relatedProducts = $recordModel->getProducts();
                $itemcount = count($relatedProducts); 
            }                       
            $addloop =array();
            for ($i=1;$i <=$itemcount ; $i++) {
                foreach ($newFields as $key_change  => $value) {
                    if(in_array($value['name'], $looping_array)){ 
                       $value['name']= $value['name'].$i;
                       $value['label']= $value['label'].$i;
                       $newFields[] =$value ;  
                       if($value['name']=='productid'.$i){ 
                            $value['name']= $value['name'].'_id';
                            $value['label']= $value['label'];
                            $value['editable']= 0;
                            $newFields[] =$value ; 
                        }                      
                    }                   
                }
            } 
            for ($i=0; $i < count($newFields); $i++) {
               if(in_array($newFields[$i]['name'], $looping_array)){ 
                    $newFields[$i]['editable']='0';
               }
            }             
        }
        $describeInfo['nameFields'] = $nameFields;
        $describeInfo['fields'] = $newFields;

        return $describeInfo;
    }

    /**
     * Return GraphQL type
     *
     * @param $type
     * @return \GraphQL\Type\Definition\BooleanType|\GraphQL\Type\Definition\IntType|\GraphQL\Type\Definition\StringType
     */
    public function typesMapping($type)
    {
        if (
            $type == 'string' || $type == 'email' || $type == 'phone' || $type == 'date' || $type == 'datetime' || $type == 'text'
            || $type == 'picklist' || $type == 'url' || $type == 'password' || $type == 'autogenerated'
        ) {
            return Type::string();
        } else if ($type == 'boolean') {
            return Type::boolean();
        } else if ($type == 'owner') {
            return Type::int();
        } else {
            return Type::string();
        }
    }

    /**
     * Return Widget data
     *
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public function getWidgetData($args)
    {
        global $current_user;
        $data = [];
        $current_user = $this->user;
        $allowed_widget_info = [
            'GroupedBySalesStage' => ['module' => 'Potentials', 'function' => 'getPotentialsCountBySalesStage'],
            'GroupedBySalesPerson' => ['module' => 'Potentials', 'function' => 'getPotentialsCountBySalesPerson'],
            'LeadsByStatus' => ['module' => 'Leads', 'function' => 'getLeadsByStatus'],
            'LeadsBySource' => ['module' => 'Leads', 'function' => 'getLeadsBySource'],
        ];

        if (!array_key_exists($args['name'], $allowed_widget_info)) {
            throw new \Exception('Widget not allowed');
        }

        $moduleModel = \Head_Module_Model::getInstance($allowed_widget_info[$args['name']]['module']);
        $response_data = $moduleModel->{$allowed_widget_info[$args['name']]['function']}($this->user->id, null);
        if ($args['name'] == 'GroupedBySalesStage') {
            foreach ($response_data as $data_key => $resolve_data) {
                $data[$data_key]['count'] = $resolve_data[1];
                $data[$data_key]['value'] = $resolve_data[0];
                $data[$data_key]['label'] = $resolve_data[2];
            }
        } else if ($args['name'] == 'GroupedBySalesPerson') {
            foreach ($response_data as $data_key => $resolve_data) {
                $data[$data_key]['count'] = $resolve_data['count'];
                $data[$data_key]['value'] = $resolve_data['last_name'];
                $data[$data_key]['label'] = $resolve_data['last_name'];
            }
        } else {
            foreach ($response_data as $data_key => $resolve_data) {
                $data[$data_key]['count'] = $resolve_data[0];
                $data[$data_key]['value'] = $resolve_data[1];
                $data[$data_key]['label'] = $resolve_data[2];
            }
        }
        return $data;
    }

    /**
     * Return Calendar Info
     *
     * @param $args
     * @return array
     */
    public function getCalendarInfoJOFORCE($args)
    {
        global $current_user;
        $current_user = $this->user;

        $start = $args['date'];
        // TODO Need improvements on this function
        if (!empty($args['day']) && $args['day'] === true) {
            $noOfDays = 1;
        } else {
            $noOfDays = 31;
        }

        $user_formatted_date = \DateTimeField::convertToUserFormat($start, $current_user);

        $dbStartDateOject = \DateTimeField::convertToDBTimeZone($start, $current_user);
        $dbStartDateTime = $dbStartDateOject->format('Y-m-d H:i:s');

        $cache_datetime = strtotime($dbStartDateTime);
        $secondsDelta = 24 * 60 * 60 * $noOfDays;
        $futureDate = $cache_datetime + $secondsDelta;
        $dbEndDateTime = date("Y-m-d H:i:s", $futureDate);

        $currentUser = \Users_Record_Model::getCurrentUserModel();
        $db = $this->db;

        $query = 'SELECT jo_activity.subject, jo_activity.eventstatus, jo_activity.priority ,jo_activity.visibility,
                        jo_activity.date_start, jo_activity.time_start, jo_activity.due_date, jo_activity.time_end,
                        jo_crmentity.smownerid, jo_activity.activityid, jo_activity.activitytype, jo_activity.recurringtype,
                        jo_activity.location FROM jo_activity
                        INNER JOIN jo_crmentity ON jo_activity.activityid = jo_crmentity.crmid
                        LEFT JOIN jo_users ON jo_crmentity.smownerid = jo_users.id
                        LEFT JOIN jo_groups ON jo_crmentity.smownerid = jo_groups.groupid
                        WHERE jo_crmentity.deleted=0 AND jo_activity.activityid > 0 AND jo_activity.activitytype NOT IN ("Emails","Task") AND ';

        $hideCompleted = $currentUser->get('hidecompletedevents');
        if ($hideCompleted) {
            $query .= "jo_activity.eventstatus != 'HELD' AND ";
        }
        $query .= " (concat(date_start,' ',time_start)) >= '$dbStartDateTime' AND (concat(date_start,' ',time_start)) < '$dbEndDateTime'";

        $eventUserId = $currentUser->getId();
        $params = array_merge(array($eventUserId), $this->getGroupsIdsForUsers($eventUserId));
        $query .= " AND jo_crmentity.smownerid IN (" . generateQuestionMarks($params) . ")";
        $query .= ' ORDER BY time_start';
        $queryResult = $db->pquery($query, $params);
        while ($record = $db->fetchByAssoc($queryResult)) {
            $item = array();
            $item['id']                = $record['activityid'];
            $item['visibility']        = $record['visibility'];
            $item['activitytype']    = $record['activitytype'];
            $item['status']            = $record['eventstatus'];
            $item['priority']        = $record['priority'];
            $item['userfullname']    = getUserFullName($record['smownerid']);
            $item['title']            = decode_html($record['subject']);

            $dateTimeFieldInstance = new \DateTimeField($record['date_start'] . ' ' . $record['time_start']);
            $userDateTimeString = $dateTimeFieldInstance->getDisplayDateTimeValue($currentUser);
            $startDateComponents = explode(' ', $userDateTimeString);

            $item['start'] = $userDateTimeString;
            $item['startDate'] = $startDateComponents[0];
            $item['startTime'] = $startDateComponents[1];

            $dateTimeFieldInstance = new \DateTimeField($record['due_date'] . ' ' . $record['time_end']);
            $userDateTimeString = $dateTimeFieldInstance->getDisplayDateTimeValue($currentUser);
            $endDateComponents = explode(' ', $userDateTimeString);

            $item['end'] = $userDateTimeString;
            $item['endDate'] = $endDateComponents[0];
            $item['endTime'] = $endDateComponents[1];

            if ($currentUser->get('hour_format') == '12') {
                $item['startTime'] = \Head_Time_UIType::getTimeValueInAMorPM($item['startTime']);
                $item['endTime'] = \Head_Time_UIType::getTimeValueInAMorPM($item['endTime']);
            }
            $recurringCheck = false;
            if ($record['recurringtype'] != '' && $record['recurringtype'] != '--None--') {
                $recurringCheck = true;
            }
            $item['recurringcheck'] = $recurringCheck;
            $result[$startDateComponents[0]][] = $item;
        }

        if (!isset($args['day']) || $args['day'] === false) {
            if (empty($result)) {
                $response[] = ['date' => $start, 'count' => 0];
            } else {
                foreach ($result as $date => $date_wise) {
                    $response[] = ['date' => $date, 'count' => count($result[$date])];
                }
            }

            return $response;
        } else {
            return isset($result[$user_formatted_date]) && !empty($result[$user_formatted_date]) ? $result[$user_formatted_date] : [];
        }
    }
   public function getCalendarInfo($args)
    {
        global $current_user;
        $current_user = $this->user;

        $start = $args['date'];
        // TODO Need improvements on this function
        if (!empty($args['day']) && $args['day'] === true) {
            $noOfDays = 1;
        } else {
            $noOfDays = 31;
        }

        $user_formatted_date = \DateTimeField::convertToUserFormat($start, $current_user);

        $dbStartDateOject = \DateTimeField::convertToDBTimeZone($start, $current_user);
        $dbStartDateTime = $dbStartDateOject->format('Y-m-d H:i:s');

        $cache_datetime = strtotime($dbStartDateTime);
        $secondsDelta = 24 * 60 * 60 * $noOfDays;
        $futureDate = $cache_datetime + $secondsDelta;
        $dbEndDateTime = date("Y-m-d H:i:s", $futureDate);

        $currentUser = \Users_Record_Model::getCurrentUserModel();
        $db = $this->db;

        $query = 'SELECT jo_activity.subject,jo_activity.status, jo_activity.eventstatus, jo_activity.priority ,jo_activity.visibility,
        jo_activity.date_start, jo_activity.time_start, jo_activity.due_date, jo_activity.time_end,
        jo_crmentity.smownerid, jo_activity.activityid, jo_activity.activitytype, jo_activity.recurringtype,
        jo_activity.location FROM jo_activity
        INNER JOIN jo_crmentity ON jo_activity.activityid = jo_crmentity.crmid
        LEFT JOIN jo_users ON jo_crmentity.smownerid = jo_users.id
        LEFT JOIN jo_groups ON jo_crmentity.smownerid = jo_groups.groupid
        WHERE jo_crmentity.deleted=0 AND jo_activity.activityid > 0 AND jo_activity.activitytype NOT IN ("Emails","Task") AND ';

        $hideCompleted = $currentUser->get('hidecompletedevents');
        if ($hideCompleted) {
            $query .= "jo_activity.eventstatus != 'HELD' AND ";
        }
        $query .= " (concat(date_start,' ',time_start)) >= '$dbStartDateTime' AND (concat(date_start,' ',time_start)) < '$dbEndDateTime'";
        $eventUserId = $currentUser->getId();
        $params = array_merge(array($eventUserId), $this->getGroupsIdsForUsers($eventUserId));
        $query .= " AND jo_crmentity.smownerid IN (" . generateQuestionMarks($params) . ")";
        $query .= ' ORDER BY time_start';
        $queryResult = $db->pquery($query, $params);
        $items = [];
        while ($record = $db->fetchByAssoc($queryResult)) {
            $item = array();
            $item['id']                = $record['activityid'];
            $item['visibility']        = $record['visibility'];
            $item['activitytype']    = $record['activitytype'];
            $item['status']            = $record['eventstatus'];
            $item['priority']        = $record['priority'];
            $item['userfullname']    = getUserFullName($record['smownerid']);
            $item['title']            = decode_html($record['subject']);
            $dateTimeFieldInstance = new \DateTimeField($record['date_start'] . ' ' . $record['time_start']);
            $userDateTimeString = $dateTimeFieldInstance->getDisplayDateTimeValue($currentUser);
            $startDateComponents = explode(' ', $userDateTimeString);
            //$startDateComponents[0] = \DateTimeField::convertToUserFormat($startDateComponents[0], $current_user);
            $item['start'] = $userDateTimeString;
            $item['startDate'] = $startDateComponents[0];
            $item['startTime'] = $startDateComponents[1];
            $dateTimeFieldInstance = new \DateTimeField($record['due_date'] . ' ' . $record['time_end']);
            $userDateTimeString = $dateTimeFieldInstance->getDisplayDateTimeValue($currentUser);
            $endDateComponents = explode(' ', $userDateTimeString);
            $item['end'] = $userDateTimeString;
            $item['endDate'] = $endDateComponents[0];
            $item['endTime'] = $endDateComponents[1];
            if ($currentUser->get('hour_format') == '12') {
                $item['startTime'] = \Head_Time_UIType::getTimeValueInAMorPM($item['startTime']);
                $item['endTime'] = \Head_Time_UIType::getTimeValueInAMorPM($item['endTime']);
            }
            $recurringCheck = false;
            if ($record['recurringtype'] != '' && $record['recurringtype'] != '--None--') {
                $recurringCheck = true;
            }
            $item['recurringcheck'] = $recurringCheck;
            array_push($items, $item);
            $result[$startDateComponents[0]][] = $item;
        }
        if (!isset($args['day']) || $args['day'] === false) {
            if (empty($result)) {
                $response[] = ['date' => $start, 'count' => 0];
            } else {
                foreach ($result as $date => $date_wise) {
                    $response[] = ['date' => $date, 'count' => count($result[$date])];
                }
            }
            return $response; //for count
        } else {
            // return isset($result[$user_formatted_date]) && !empty($result[$user_formatted_date]) ? $result[$user_formatted_date] : [];
        
            $events = [];
            foreach ($items as  $value) {
                $event = [];
                $event['id'] = $value['id'];
                $event['title'] = $value['title'];
                $event['startTime'] = $value['startTime'];
                $event['endTime'] = $value['endTime'];
                $event['status'] = $value['status'];
                $event['priority'] = $value['priority'];

                $events[$value['activitytype']][] = $event;
            }

            $new_events = [];
            $query = "select * from jo_activitytype";
            $queryResult = $db->pquery($query);
            while ($record = $db->fetchByAssoc($queryResult)) {
                $event = isset($events[$record['activitytype']]) ? $events[$record['activitytype']] : [];

                $new_events['block'][] = [
                    'activity_type' => $record['activitytype'],
                    'event_details' => $event,
                ];
          }
        }

            $calendar_view = [];
            $calendar_view['date'] = $start;
            $calendar_view['events'] = $new_events;
            $data = [];
            $data['calendar_view'] = $calendar_view;
            $finalresult = [];
            $finalresult['data'] = $data;

            print_r(json_encode($finalresult));
            die();
        }

    /**
     * Return User menu
     *
     * @return array
     */
    public function getUserMenu()
    {
        $data = [];
        $more_section = [];
        $user_id = $this->user->id;
        $main_menu = getMainMenuList($user_id);
        // Adding module label
        foreach ($main_menu as $key => $menu_info) {
            $main_menu[$key]['label'] = vtranslate($menu_info['name']);
            $is_entity_module = $this->checkEntityModule($menu_info['name']);
            if (!$is_entity_module) {
                unset($main_menu[$key]);
            }
        }

        $modules_and_sections = getAppModuleList($user_id);
        $data['More'] = $main_menu;

        $i = 0;
        foreach ($modules_and_sections as $section => $modules) {
            $more_section[$i]['section'] = $section;
            $more_section[$i]['module_info'] = [];
            foreach ($modules as $tab_id) {
                $module_name = getTabModuleName($tab_id);
                $is_entity_module = $this->checkEntityModule($module_name);
                if ($is_entity_module) {
                    global $site_URL; 
                    $imageurl =vimage_path($module_name. '.png');
                    if(empty($imageurl)){
                        $imageurl=vimage_path('DefaultModule.png');
                    }
                    $more_section[$i]['module_info'][] = ['name' => $module_name, 'label' => vtranslate($module_name), 'tabid' => $tab_id,'image_url' =>$site_URL.$imageurl ];
                }
            }

            // If no modules present in the section, unset the section
            if (count($more_section[$i]['module_info']) == 0) {
                unset($more_section[$i]);
                continue;
            }
            $i = $i + 1;
        }
        $data['Main'] = $more_section;
        return $data;
    }

    /**
     * Check whether module is Entity or Extension
     *
     * @param $module_name
     * @return bool
     */
    public function checkEntityModule($module_name)
    {
        $getEntity = $this->db->pquery('select isentitytype from jo_tab where name = ?', array($module_name));
        if ($getEntity && $this->db->num_rows($getEntity)) {
            return $this->db->query_result($getEntity, 0, 'isentitytype');
        }
        return false;
    }

    /**
     * Search all modules and return response
     *
     * @param $args
     * @return array
     */
    public function globalSearch($args)
    {
        global $current_user;
        $current_user = $this->user;
        $request = new \Head_Request($args);
        $listView = new \Head_ListAjax_View();
        $search_result = $listView->searchAll($request, true);
        $i = 0;
        $response = [];
        if ($search_result) {
            foreach ($search_result as $module => $module_records) {
                $response[$i]['module'] = $module;
                if (!empty($module_records)) {
                    foreach ($module_records as $module_record) {
                        $response[$i]['data'][] = $module_record->getData();
                    }
                } else {
                    $response[$i]['data'] = [];
                }
                $i = $i + 1;
            }
        }
        return $response;
    }

    protected function getGroupsIdsForUsers($userId)
    {
        vimport('~~/includes/utils/GetUserGroups.php');
        $userGroupInstance = new \GetUserGroups();
        $userGroupInstance->getAllUserGroups($userId);
        return $userGroupInstance->user_groups;
    }

    /**
     * Return location details related to the user.
     * @param $request
     * @return mixed
     */
    public function returnLocationDetails($request)
    {
        include_once 'includes/Webservices/Query.php';

        $code = $request['code'];
        $city = $request['city'];
        $state = $request['state'];
        $request_array = array();

        if ($request['module'] == 'Leads') {

            $code_query = $city_query = $state_query = $query_concat = '';

            $query_concat = "SELECT leadid,concat(details.firstname,' ',details.lastname) as label,concat(address.lane,' ',address.city,' ',address.state,' ',address.code,' ',address.country) as address,address.code,address.city,address.state FROM jo_leaddetails details JOIN jo_crmentity crm ON crm.crmid = details.leadid JOIN jo_leadaddress address ON address.leadaddressid = crm.crmid WHERE crm.deleted = 0 AND";

            $code_query = $city_query = $state_query = $query_concat;

            if (!empty($code)) {
                $code_query .= " code =? LIMIT 30";

                $mailingzip = $this->db->pquery($code_query, array($code));

                while ($module_info = $this->db->fetchByAssoc($mailingzip)) {

                    $request_array[] = $module_info;
                }
            }
            if (!empty($city)) {
                $city_query .= " city =? LIMIT 30";

                $mailingzip = $this->db->pquery($city_query, array($city));

                while ($module_info = $this->db->fetchByAssoc($mailingzip)) {

                    $request_array[] = $module_info;
                }
            }
            if (!empty($state)) {
                $state_query .= " state =? LIMIT 30";

                $mailingzip = $this->db->pquery($state_query, array($state));

                while ($module_info = $this->db->fetchByAssoc($mailingzip)) {

                    $request_array[] = $module_info;
                }
            }

            $transform_array = array_unique($request_array, SORT_REGULAR);
            $temp_array = [];

            $i = 0;
            foreach ($transform_array as $key => $value) {
                $temp_array[$i] = ['label' => $value['label'], 'address' => $value['address'],'recordId'=>$value['leadid']];
                $i = $i + 1;
            }

            if(!empty($temp_array)){
                $responcearray = array("success"=> true,'locationList' => $temp_array);
            }else{
                $responcearray = array("success"=> true,'locationList' => 'No Records founds');
            }  
            return $responcearray;
        } elseif ($request['module'] == 'Contacts') {  // for Contacts 
            $mailingzip_query = $mailingcode_query = $mailingstate_query = $query_concat = '';

            $query_concat = "SELECT contactid,concat(details.firstname,' ', details.lastname) as label, concat(address.mailingstreet, ' ', address.mailingcity,' ', address.mailingstate, ' ', address.mailingzip, ' ', address.mailingcountry) as address,address.mailingstreet,address.mailingzip,address.mailingcity,address.mailingstate,address.mailingcountry FROM jo_contactdetails details JOIN jo_crmentity crm ON crm.crmid = details.contactid JOIN jo_contactaddress address ON address.contactaddressid = crm.crmid WHERE crm.deleted = 0 AND";

            $mailingzip_query = $mailingcode_query = $mailingstate_query = $query_concat;

            if (!empty($code)) {
                $mailingzip_query .= " mailingzip =? LIMIT 30";

                $mailingzip = $this->db->pquery($mailingzip_query, array($code));

                while ($module_info = $this->db->fetchByAssoc($mailingzip)) {

                    $request_array[] = $module_info;
                }
            }
            if (!empty($city)) {
                $mailingcode_query .= " mailingcity=? LIMIT 30";

                $mailingzip = $this->db->pquery($mailingcode_query, array($city));

                while ($module_info = $this->db->fetchByAssoc($mailingzip)) {

                    $request_array[] = $module_info;
                }
            }
            if (!empty($state)) {
                $mailingstate_query .= " mailingstate=? LIMIT 30";

                $mailingzip = $this->db->pquery($mailingstate_query, array($state));

                while ($module_info = $this->db->fetchByAssoc($mailingzip)) {

                    $request_array[] = $module_info;
                }
            }
            $transform_array = array_unique($request_array, SORT_REGULAR);
            $temp_array = [];
            $i = 0;
            foreach ($transform_array as $key => $value) {
                $temp_array[$i] = ['label' => $value['label'], 'address' => $value['address'], 'recordId'=>$value['contactid']];
                $i = $i + 1;
            }
            if(!empty($temp_array)){
                $responcearray = array("success"=> true,'locationList' => $temp_array);
            }else{
                $responcearray = array("success"=> true,'locationList' => 'No Records founds');
            }
            return $responcearray;
        }
    }

    /**
     * Run the query given
     *
     * @param array $requested_data
     * @param array $args
     * @return void
     */
    public function query($requested_data, $args)
    {
        global $current_user;
        $current_user = $this->user;
        $page = $requested_data['page'];
        if (empty($page)) {
            $page = 0;
        }
        $query = $requested_data['query'];
        $nextPage = 0;
        $queryResult = false;

        require_once('include/Webservices/Query.php');
        if (preg_match("/(.*) LIMIT[^;]+;/i", $query)) {
            $queryResult = \vtws_query($query, $current_user);
        } else {
            // Implicit limit and paging
            $query = rtrim($query, ";");

            $currentPage = intval($page);
            $FETCH_LIMIT = 10;
            $startLimit = $currentPage * $FETCH_LIMIT;

            $queryWithLimit = sprintf("%s LIMIT %u,%u;", $query, $startLimit, ($FETCH_LIMIT + 1));
            $queryResult = \vtws_query($queryWithLimit, $current_user);

            // Determine paging
            $hasNextPage = (count($queryResult) > $FETCH_LIMIT);
            if ($hasNextPage) {
                array_pop($queryResult); // Avoid sending next page record now
                $nextPage = $currentPage + 1;
            }
        }

        $fieldsInfo = $this->getModuleFields($requested_data['module']);

        $records = array();
        if (!empty($queryResult)) {
            foreach ($queryResult as $recordValues) {
                $unresolved_data = $this->resolveRecordValues($recordValues, $current_user, $requested_data['module']);
                $records[] = $this->returnDataInBlocks($unresolved_data, $requested_data['module'], $fieldsInfo['labelFields']);
            }
        }
        $result = array('records' => $records, 'nextPage' => $nextPage, 'labelFields' => explode(',', $fieldsInfo['labelFields']));
        return $result;
    }

    /**
     * Resolve record values
     *
     * @param array $data
     * @param string $module_name
     * @return array $modifiedResult
     */
    public function returnDataInBlocks($data, $module_name, $labelFields)
    {
        global $current_user;
        require_once('includes/utils/utils.php');
        $moduleFieldGroups = gatherModuleFieldGroupInfo($module_name);
        $blocks = $modifiedResult = array();
        foreach ($moduleFieldGroups as $blocklabel => $fieldgroups) {
            $fields = array();
            foreach ($fieldgroups as $fieldname => $fieldinfo) {
                // Pickup field if its part of the result
                if (isset($data[$fieldname])) {
                    $field = array(
                        'name'  => $fieldname,
                        'value' => $data[$fieldname],
                        'label' => $fieldinfo['label'],
                        'uitype' => $fieldinfo['uitype']
                    );

                    // Fix the assigned to uitype
                    if ($field['uitype'] == '53') {
                        $field['type']['defaultValue'] = array('value' => "19x{$current_user->id}", 'label' => $current_user->column_fields['last_name']);
                    } else if ($field['uitype'] == '117') {
                        $field['type']['defaultValue'] = $field['value'];
                    }
                    // Special case handling to pull configured Terms & Conditions given through webservices.
                    else if ($field['name'] == 'terms_conditions' && in_array($module, array('Quotes', 'Invoice', 'SalesOrder', 'PurchaseOrder'))) {
                        $field['type']['defaultValue'] = $field['value'];
                    }
                    // Special case handling to set defaultValue for visibility field in calendar.
                    else if ($field['name'] == 'visibility' && in_array($module, array('Calendar', 'Events'))) {
                        $field['type']['defaultValue'] = $field['value'];
                    } else if ($field['type']['name'] != 'reference') {
                        $field['type']['defaultValue'] = $field['default'];
                    }
                    $fields[] = $field;
                }
            }
            $blocks[] = array('label' => $blocklabel, 'fields' => $fields);
        }

        $sections = array();
        $moduleFieldGroupKeys = array_keys($moduleFieldGroups);
        foreach ($moduleFieldGroupKeys as $blocklabel) {
            // Eliminate empty blocks
            if (isset($groups[$blocklabel]) && !empty($groups[$blocklabel])) {
                $sections[] = array('label' => $blocklabel, 'count' => count($groups[$blocklabel]));
            }
        }

        $modifiedResult = array('blocks' => $blocks, 'id' => $data['id']);
        if ($labelFields) $modifiedResult['labelFields'] = explode(',', $labelFields);

        if (isset($data['LineItems'])) {
            $modifiedResult['LineItems'] = $data['LineItems'];
        }

        return $modifiedResult;
    }

    /**
     * Resolve record values
     *
     * @param string $record
     * @param object $user
     * @param string $module_name
     * @param boolean $ignoreUnsetFields
     * @return mixed
     */
    function resolveRecordValues($data)
    {
        foreach ($this->module_fields_info['fields'] as $field_info) {
            if ($field_info['type']['name'] == 'reference') {
                $data[$field_info['name']] = decode_html(\Head_Functions::getCRMRecordLabel($data[$field_info['name']]));
            } else if ($field_info['name'] == 'assigned_user_id') {
                $assigned_user_id_label = decode_html(\Head_Functions::getUserRecordLabel($data[$field_info['name']]));
                if (empty($assigned_user_id_label)) {
                    $assigned_user_id_label = decode_html(\Head_Functions::getGroupRecordLabel($data[$field_info['name']]));
                }
                $data[$field_info['name']] = $assigned_user_id_label;
            } else if ($field_info['type']['name'] == 'boolean') {
                $boolean_response = false;
                if (!empty($data[$field_info['name']]) && $data[$field_info['name']] == 1)
                    $boolean_response = true;

                $data[$field_info['name']] = $boolean_response;
            } else if ($field_info['type']['name'] == 'time' && ($field_info['name'] == 'time_start' || $field_info['name'] == 'time_end')) {
                if (!empty($data[$field_info['name']])) {
                    $date = $data['date_start'];
                    if ($field_info['name'] == 'time_end')
                        $date = $data['due_date'];

                    $temp_value = $date . ' ' . $data[$field_info['name']];
                    $datetime = \DateTimeField::convertToUserTimeZone($temp_value)->format('Y-m-d H:i:s');
                    $convertedDateTime = explode(' ',$datetime);
                    $data[$field_info['name']] = $convertedDateTime[1];
                }
            } else if($field_info['name'] == 'createdtime' || $field_info['name'] == 'modifiedtime') {
                    $temp_value = $data[$field_info['name']];
                    $dattime = new \Calendar_Datetime_UIType();
                    $displayDateTime = $dattime->getDisplayValue($temp_value);
                    $data[$field_info['name']] = $displayDateTime;
            }
        }
        return $data;
    }

    /**
     * Fetch record label for Id
     *
     * @param string $id
     * @param object $user
     * @return void
     */
    public function fetchRecordLabelForId($id, $user)
    {
        $value = null;

        if (isset($this->resolvedValueCache[$id])) {
            $value = $this->resolvedValueCache[$id];
        } else if (!empty($id)) {
            $value = trim(\vtws_getName($id, $user));
            $this->resolvedValueCache[$id] = $value;
        } else {
            $value = $id;
        }
        return \decode_html($value);
    }

    public function addlineitem($focus,$lineitem){  

        global $log, $adb; 
        $moduleName=$module=$focus['record_module'];
        $id=$focus['id']; 
        if(empty($moduleName))
            $moduleName=$module=$_REQUEST['module']; 
            $ext_prod_arr = Array();
            $all_available_taxes = getAllTaxes('available', '', 'edit', $id);  
            $tableName = '';
        switch($moduleName) {
            case 'Quotes'       : $tableName = 'jo_quotes';         $index = 'quoteid';         break;
            case 'Invoice'      : $tableName = 'jo_invoice';        $index = 'invoiceid';       break;
            case 'SalesOrder'   : $tableName = 'jo_salesorder';     $index = 'salesorderid';    break;
            case 'PurchaseOrder': $tableName = 'jo_purchaseorder';  $index = 'purchaseorderid'; break;
        }
        $tot_no_prod = 10;
    //If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
        $prod_seq=1;
        for($i=1; $i<=$tot_no_prod; $i++)
        {    
            if($focus["deleted".$i] == 1)
                continue;

            $prod_id = modlib_purify($lineitem['productid'.$i]);   

            if (!$prod_id) {
                continue;
            }  
            if(isset($lineitem['productDescription'.$i]))
            $description = modlib_purify($lineitem['productDescription'.$i]);
            $qty = modlib_purify($lineitem['quantity'.$i]);
            $listprice = modlib_purify($lineitem['listprice'.$i]);
            $comment = modlib_purify($lineitem['comment'.$i]);
            $purchaseCost = ($qty*$listprice);
            $$margin =($qty*$listprice); 

        if($module == 'SalesOrder') {
            if($updateDemand == '-')
            {
                deductFromProductDemand($prod_id,$qty);
            }
            elseif($updateDemand == '+')
            {
                addToProductDemand($prod_id,$qty);
            }
        }

        $query = 'INSERT INTO jo_inventoryproductrel(id, productid, sequence_no, quantity, listprice, comment, description, purchase_cost, margin)
                    VALUES(?,?,?,?,?,?,?,?,?)';
        $qparams = array($id,$prod_id,$prod_seq,$qty,$listprice,$comment,$description, $purchaseCost, $margin);  
        $adb->pquery($query,$qparams); 
        $lineitem_id = $adb->getLastInsertID(); 
        $sub_prod_str = modlib_purify($lineitem['subproduct_ids'.$i]);
        if (!empty($sub_prod_str)) {
             $sub_prod = split(',', rtrim($sub_prod_str, ','));
             foreach ($sub_prod as $subProductInfo) {
                 list($subProductId, $subProductQty) = explode(':', $subProductInfo);
                 $query = 'INSERT INTO jo_inventorysubproductrel VALUES(?, ?, ?, ?)';
                 if (!$subProductQty) {
                     $subProductQty = 1;
                 }
                 $qparams = array($id, $prod_seq, $subProductId, $subProductQty);
                $adb->pquery($query,$qparams);
            }
        }
        $prod_seq++; 
        if($module != 'PurchaseOrder')
        {
            //update the stock with existing details
            updateStk($prod_id,$qty,$lineitem['mode'],$ext_prod_arr,$module);
        } 
        //we should update discount and tax details
        $updatequery = "update jo_inventoryproductrel set ";
        $updateparams = array();

        //set the discount percentage or discount amount in update query, then set the tax values
        if($lineitem['discount_type'.$i] == 'percentage')
        {
            $updatequery .= " discount_percent=?,";
            array_push($updateparams, modlib_purify($lineitem['discount_percentage'.$i]));
        }
        elseif($lineitem['discount_type'.$i] == 'amount')
        {
            $updatequery .= " discount_amount=?,";
            $discount_amount = modlib_purify($lineitem['discount_amount'.$i]);
            array_push($updateparams, $discount_amount);
        }
 
        $compoundTaxesInfo = getCompoundTaxesInfoForInventoryRecord($id, $module);
    
        if($lineitem['hdnTaxType'] == 'group')
        {
            for($tax_count=0;$tax_count<count($all_available_taxes);$tax_count++)
            {
                $taxDetails = $all_available_taxes[$tax_count];
                if ($taxDetails['method'] === 'Deducted') {
                    continue;
                } else if ($taxDetails['method'] === 'Compound') {
                    $compoundExistingInfo = $compoundTaxesInfo[$taxDetails['taxid']];
                    if (!is_array($compoundExistingInfo)) {
                        $compoundExistingInfo = array();
                    }
                    $compoundNewInfo = Zend_Json::decode(html_entity_decode($taxDetails['compoundon']));
                    $compoundFinalInfo = array_merge($compoundExistingInfo, $compoundNewInfo);
                    $compoundTaxesInfo[$taxDetails['taxid']] = array_unique($compoundFinalInfo);
                }

                $tax_name = $taxDetails['taxname'];
                $request_tax_name = $tax_name."_group_percentage";
                $tax_val = 0;
                if(isset($lineitem[$request_tax_name])) {
                    $tax_val = modlib_purify($lineitem[$request_tax_name]);
                }
                $updatequery .= " $tax_name = ?,";
                array_push($updateparams, $tax_val);
            }
        }
        else
        {
            $taxes_for_product = getTaxDetailsForProduct($prod_id,'all');
            for($tax_count=0;$tax_count<count($taxes_for_product);$tax_count++)
            {
                $taxDetails = $taxes_for_product[$tax_count];
                if ($taxDetails['method'] === 'Compound') {
                    $compoundExistingInfo = $compoundTaxesInfo[$taxDetails['taxid']];
                    if (!is_array($compoundExistingInfo)) {
                        $compoundExistingInfo = array();
                    }

                    $compoundFinalInfo = array_merge($compoundExistingInfo, $taxDetails['compoundon']);
                    $compoundTaxesInfo[$taxDetails['taxid']] = array_unique($compoundFinalInfo);
                }
                $tax_name = $taxDetails['taxname'];
                $request_tax_name = $tax_name."_percentage".$i;

                $updatequery .= " $tax_name = ?,";
                array_push($updateparams, modlib_purify($lineitem[$request_tax_name]));
            }
        }

        //Adding deduct tax value to query
        for($taxCount=0; $taxCount<count($all_available_taxes); $taxCount++) {
            if ($all_available_taxes[$taxCount]['method'] === 'Deducted') {
                $taxName = $all_available_taxes[$taxCount]['taxname'];
                $requestTaxName = $taxName.'_group_percentage';
                $taxValue = 0;
                if(isset($lineitem[$requestTaxName])) {
                    $taxValue = modlib_purify($lineitem[$requestTaxName]);
                }

                $updatequery .= " $taxName = ?,";
                array_push($updateparams, (-$taxValue));
            }
        }

        $updatequery = trim($updatequery, ',').' WHERE id = ? AND productid = ? AND lineitem_id = ?';
        array_push($updateparams, $id, $prod_id, $lineitem_id);

        if( !preg_match( '/set\s+where/i', $updatequery)) {
            $adb->pquery($updatequery,$updateparams);
        }
    }  

    $updatequery  = " update " .$tableName. " set";
    $updateparams = array();
    $subtotal = modlib_purify($lineitem['hdnSubTotal']);
    $updatequery .= " subtotal=?,";
    array_push($updateparams, $subtotal);

    $pretaxTotal = modlib_purify($lineitem['pre_tax_total']); 
    $updatequery .= " pre_tax_total=?,"; 
    array_push($updateparams, $pretaxTotal);

    $updatequery .= " taxtype=?,";
    array_push($updateparams, $lineitem['hdnTaxType']);

    $discount_amount_final = modlib_purify($lineitem['discount_amount']);
    $updatequery .= " discount_amount=?,discount_percent=?,";
    array_push($updateparams, $discount_amount_final);
    array_push($updateparams, null);

    
    $shipping_handling_charge = modlib_purify($lineitem['hdnS_H_Amount']);
    $updatequery .= " s_h_amount=?,";
    array_push($updateparams, $shipping_handling_charge);

    //if the user gave - sign in adjustment then add with the value
    $adjustmentType = '';
    if($lineitem['adjustmentType'] == '-')
        $adjustmentType = modlib_purify($lineitem['adjustmentType']);

    $adjustment = modlib_purify($lineitem['adjustment']);   
    $updatequery .= " adjustment=?,";
    array_push($updateparams, $adjustmentType.$adjustment);

    $total = modlib_purify($lineitem['hdnSubTotal']);
    $updatequery .= " total=?,";
    array_push($updateparams, $total);
    
    if(!empty($compoundTaxesInfo)){
    $updatequery .= ' compound_taxes_info = ?,'; 
    $result = \Zend\Json\Json::decode($compoundTaxesInfo);
    array_push($updateparams, $result); }

    if (isset($lineitem['region_id'])) {
        $updatequery .= " region_id = ?,";
        array_push($updateparams, modlib_purify($lineitem['region_id']));
    }
    //to save the S&H tax details in jo_inventoryshippingrel table 
    $chargesInfo = array();
    if (isset($lineitem['charges'])) {
        $chargesInfo = $lineitem['charges'];
    } 
    $updatequery .= " s_h_percent=?"; //hdnS_H_Percent
    array_push($updateparams, $shipping_handling_charge);
 
    //Added where condition to which entity we want to update these values
    $updatequery .= " where ".$index."=?";
    array_push($updateparams, $id); 
    $adb->pquery($updatequery,$updateparams); 
    $log->debug("Exit from function saveInventoryProductDetails($module).");    
    
    }

    
}

