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
<div class="dashboardWidgetHeader">
  {include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME}
</div>
<div class="dashboardWidgetContent">
    {if count($DATA) gt 0 }
        <input class="widgetData" type=hidden value='{Head_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
        <input class="yAxisFieldType" type="hidden" value="{$YAXIS_FIELD_TYPE}" />
        <div style="margin:0px 10px;">
                <div class="widgetChartContainer" id="widgetChartContainer__{$WIDGET->get('linkid')}" name='chartcontent' style="height: 200px; min-width:300px; margin:0 auto">

                </div>
                <br>
                </div>
                </div> 
    {else}
        <span class="noDataMsg">
            {vtranslate('LBL_NO')} {vtranslate($MODULE_NAME, $MODULE_NAME)} {vtranslate('LBL_MATCHED_THIS_CRITERIA')}
        </span>
    {/if}

</div>
<div class="widgeticons dashBoardWidgetFooter">
    <div class="footerIcons pull-right">
        {include file="dashboards/DashboardFooterIcons.tpl"|@vtemplate_path:$MODULE_NAME}
    </div>
</div>

<script type="text/javascript">
 Head_Pie_Widget_Js('Head_OpenTickets_Widget_Js',{},{
    /**
     * Function which will give chart related 
     */
    generateData : function(type=false) {
      var container = this.getContainer();
      var jData = container.find('.widgetData').val();
      var data = JSON.parse(jData);
      console.log(data);
      var chart_data = [];
        for (var index in data){
            highchart_data = {
               
                'y': parseInt(data[index]['count']),
                 'name':data[index]['name'],
                'links': data[index]['links']
            }
             chart_data.push(highchart_data);
        }
     var chart_id = this.getContainer().find('.widgetChartContainer').attr('id');
      var load_chart_data = {
           'id': chart_id,
            'chart_data': chart_data,
         
        };
        console.log(load_chart_data);
        return load_chart_data;
      
    }
  });
</script>
