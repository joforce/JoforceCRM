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

{literal}
	<script type="text/javascript">
		Head_MultiBarchat_Widget_Js('Head_PipelinedAmountPerSalesPerson_Widget_Js',{},{
			getCharRelatedData : function() {
        var container = this.getContainer();
        var data = container.find('.widgetData').val();
        data = JSON.parse(data);
console.log(data);

        var users = new Array();
        var stages = new Array();
        var count = new Array();
        for(var i=0; i<data.length ;i++) {
          if($.inArray(data[i].last_name, users) == -1) {
            users.push(data[i].last_name);
          }
          if($.inArray(data[i].sales_stage, stages) == -1) {
            stages.push(data[i].sales_stage);
          }
        }
        var allLinks = new Array();
        for(j in stages) {
          var salesStageCount = new Array();
                    var links = new Array();
          for(i in users) {
            var salesCount = 0;
            for(var k in data) {
              var userData = data[k];
              if(userData.sales_stage == stages[j] && userData.last_name == users[i]) {
                salesCount = parseFloat(userData.amount);
                                link = userData.links
                break;
              }
            }
                        links.push(link);
            salesStageCount.push(salesCount);
          }
                    allLinks.push(link);
          count.push(salesStageCount);
        }
          return {
          'data' : count,
          'ticks' : users,
          'labels' : stages,
          'links' : allLinks
        }
      }
    });
  </script>
{/literal}
