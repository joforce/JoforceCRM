{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************} 
<!DOCTYPE html>
<html>
<head>
    <title>Joforce</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">    
     <style>
    /* Style the tab */
    .tab {
      overflow: hidden;
     /* border: 1px solid #ccc;
      background-color: #f1f1f1;  */
      width: 75%;
      margin-left: 12%;
    /*   border-bottom: 1px solid #e4e6e3; */
    }

    /* Style the buttons inside the tab */
    .tab button {
      background-color: inherit;
      float: left;
      border: none;
      outline: none;
      cursor: pointer;
      padding: 14px 16px;
      transition: 0.3s;
      font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
      background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
      border-bottom: 3px solid #1c7c54;
    }

    /* Style the tab content */
    .tabcontent {
      display: none;
      padding: 6px 12px     
      border-top: none;
    }
    .form-detail{
    padding: 10px;
    box-shadow: 0px 0px 1.5px 1.5px #bbb3b3;
    width: 76.5%;
    margin-left: 11.2%;
    margin-top: 5%;
    margin-bottom: 10%;

    }
    .row-space{
        margin-top: 3%;
    } 
    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: lightgrey;
        /*CSS counters to number the steps*/
        counter-reset: step;
    }

    #progressbar .active {
        color: #0444A7
    }

    #progressbar li {
        list-style-type: none;
        font-size: 13px;
        width: 19%;
        float: left;
        position: relative;
        font-weight: 400;
        text-align: center;
    }
     
    #progressbar li:before {
        content: counter(step);
        counter-increment: step;
        width: 40px;
        height: 40px;
        line-height: 40px;
        display: block;
        font-size: 20px;
        color: #ffffff;
        background: lightgray;
        border-radius: 50%;
        margin: 0 auto 10px auto; 
        text-align: center;  
    }

    #progressbar li:after {
        content: '';
        width: 79%;
        height: 2px;
        background: lightgray;
        position: absolute;
        left: -36.5%;
        top: 18px; 
    }

    #progressbar li:first-child:after {
    /*connector not needed before the first step*/
    content: none;
    }

    #progressbar li.active:before, #progressbar li.active:after {
    background: #0444A7
    }
    </style>
</head>
<body>

   <div class="tab">
        <ul id="progressbar">
            <li id="tab1" class="tablinks active"  onclick="Brandtabview(event, 'step-1')"><strong>CompanyDetails</strong></li>
            <li  id="tab2" class="tablinks" onclick="Brandtabview(event, 'step-2')"><strong>OutgoingServer</strong></li>
            <li id="tab3" class="tablinks" onclick="Brandtabview(event, 'step-3')"><strong>Branding logo</strong></li>
            <li id="tab4" class="tablinks" onclick="Brandtabview(event, 'step-4')"><strong>Login logo</strong></li>
            <li id="tab5" class="tablinks" onclick="Brandtabview(event, 'step-5')"><strong>Language Editor</strong></li>
        </ul>               
    </div>

<div class="container">
   
    <input type="hidden" name="record" value="{$CURRENT_USER_MODEL->getId()}">
    <input type="hidden" id="current-step" value="1">
    <div class="form-detail">
        <div class="notification-section"></div>
        <div id="step-1" class="step-1 tabcontent" style="text-align: center;display:block">
           {include file='modules/Users/CompanyDetails.tpl'}
        </div>
        <div id="step-2" class="step-2 tabcontent">
            {include file='modules/Users/OutgoingServer.tpl'}
        </div>
        <div id="step-3" class="step-3 tabcontent">
        {include file='modules/Settings/Head/Brandinglogo.tpl'}
        </div>
        <div id="step-4" class="step-4 tabcontent">
            {include file='modules/Settings/Head/loginlogo.tpl'}
        </div>
         <div id="step-5" class="step-5 tabcontent">
            {include file='modules/Settings/LanguageEditor/Index.tpl'}
        </div>

        <div class="" style="text-align: center;">
            <button type="button" class="btn btn-primary form-submit">{vtranslate('Next', $MODULE)}</button>&nbsp;&nbsp;
            <button type="button" class="btn btn-secondary  skip-the-form">{vtranslate('Skip', $MODULE)}</button>&nbsp;&nbsp;
            <button type="button" class="btn btn-secondary  go-back">{vtranslate('Back', $MODULE)}</button>&nbsp;&nbsp;
        </div>
    </div>      
</body> 

<script type="text/javascript">
var Brandtabview; 
function Brandtabview(evt, type) {
    var i, tabcontent, tablinks; 
    if(type !='step-1'){
        $('.go-back').show();
        $('.skip-the-form').show();
    }else if(type =='step-1'){
        $('.go-back').hide();
        $('.skip-the-form').hide();
    }
    if(type == 'step-5'){ 
        $('.go-back').show();
        $('.skip-the-form').hide();
    }
    var num = type.replace(/\D/g,'');
  
    $('#current-step').val(num);
   

    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) { 
       tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks"); 
    for (i = 0; i < num; i++){ 
        tablinks[i].classList.add("active");
    }
    for (i = num; i < tablinks.length; i++){ 
        tablinks[i].classList.remove("active");
    }
    document.getElementById(type).style.display = "block";
    evt.currentTarget.className += " active";
}  
</script> 
</html>
