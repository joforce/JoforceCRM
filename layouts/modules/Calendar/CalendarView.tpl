{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{* modules/Calendar/views/Calendar.php *}
{strip}
<input type="hidden" id="currentView" value="{$smarty.request.view}" />
<input type="hidden" id="start_day" value="{$CURRENT_USER->get('dayoftheweek')}" />
<input type="hidden" id="activity_view" value="{$CURRENT_USER->get('activity_view')}" />
<input type="hidden" id="time_format" value="{$CURRENT_USER->get('hour_format')}" />
<input type="hidden" id="start_hour" value="{$CURRENT_USER->get('start_hour')}" />
<input type="hidden" id="date_format" value="{$CURRENT_USER->get('date_format')}" />
<input type="hidden" id="hideCompletedEventTodo" value="{$CURRENT_USER->get('hidecompletedevents')}">
<input type="hidden" id="show_allhours" value="{$CURRENT_USER->get('showallhours')}" />

<div style="" class="calender_page_view">
    <div id="datepicker-calendar" class=""> 
        <div class=" card card--calendar p-0 mg-b-20"> 
            <div class="p-4 border-bottom"> 
                <h2 class="main-content-title mg-b-15 tx-16">My Calendar</h2> 
                <div class="text-center mx-auto"> <img src="{$Site_Url}layouts/skins/images/mycalendar.png" alt="calendar" style="width:80%;heidht:80%;"></div> 
            </div> 
            <div class=" card p-4 mb-0 pb-0 pl-4 pr-4 pt-4 border-0"> 
                <div class="fc-datepicker">
                </div>
            </div>
        </div>
    </div>  
    <div id="mycalendar" class="calendarview" class=""></div>
</div>

{/strip}
