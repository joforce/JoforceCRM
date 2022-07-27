{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
********************************************************************************/
-->*}

<div class='dashboardHeading container-fluid'>
	<div class="buttonGroups pull-right">
		<div class="btn-group" style="margin-top: 5px;">
			{if $SELECTABLE_WIDGETS|count gt 0}
			  <div class="dropdown">
				<button class='btn btn-default addButton dropdown-toggle ms_dashborad_head_btn' data-toggle='dropdown'>
					{vtranslate('LBL_ADD_WIDGET')}
				</button>

			 <div class="dropdown-menu dropdown-menu-right get {if $MODULE eq 'Home'} ms_dashboard_drop_down {/if}"id="content">
			 	<form>
			 	<input type="text" id="Widget" placeholder="Search Widgets" onkeyup="myFunction();" class="inputElement">
			 	</form>
				<ul class=" widgetsList pull-right" style="min-width:100%;text-align:left;">
					{assign var="MINILISTWIDGET" value=""}
					{foreach from=$SELECTABLE_WIDGETS item=WIDGET}
						{if $WIDGET->getName() eq 'MiniList'}
							{assign var="MINILISTWIDGET" value=$WIDGET} {* Defer to display as a separate group *}
						{elseif $WIDGET->getName() eq 'Notebook'}
							{assign var="NOTEBOOKWIDGET" value=$WIDGET} {* Defer to display as a separate group *}
						{else}
							<li>
								<a onclick="Head_DashBoard_Js.addWidget(this, '{$WIDGET->getUrl()}')" href="javascript:void(0);"
									data-linkid="{$WIDGET->get('linkid')}" data-name="{$WIDGET->getName()}" data-width="{$WIDGET->getWidth()}" data-height="{$WIDGET->getHeight()}">
									{vtranslate($WIDGET->getTitle(), $MODULE_NAME)}</a>
							</li>
						{/if}
					{/foreach}

					{if $MINILISTWIDGET && $MODULE_NAME == 'Home'}
						
						<li>
							<a onclick="Head_DashBoard_Js.addMiniListWidget(this, '{$MINILISTWIDGET->getUrl()}')" href="javascript:void(0);"
								data-linkid="{$MINILISTWIDGET->get('linkid')}" data-name="{$MINILISTWIDGET->getName()}" data-width="{$MINILISTWIDGET->getWidth()}" data-height="{$MINILISTWIDGET->getHeight()}">
								{vtranslate($MINILISTWIDGET->getTitle(), $MODULE_NAME)}</a>
						</li>
						<li>
							<a onclick="Head_DashBoard_Js.addNoteBookWidget(this, '{$NOTEBOOKWIDGET->getUrl()}')" href="javascript:void(0);"
								data-linkid="{$NOTEBOOKWIDGET->get('linkid')}" data-name="{$NOTEBOOKWIDGET->getName()}" data-width="{$NOTEBOOKWIDGET->getWidth()}" data-height="{$NOTEBOOKWIDGET->getHeight()}">
								{vtranslate($NOTEBOOKWIDGET->getTitle(), $MODULE_NAME)}</a>
						</li>
					{/if}

				</ul>
			 </div>
		  </div>
			{else if $MODULE_PERMISSION}
				<button class='btn addButton dropdown-toggle' disabled="disabled" data-toggle='dropdown'>
					<strong>{vtranslate('LBL_ADD_WIDGET')}</strong> &nbsp;&nbsp;
				</button>
			{/if}
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
function myFunction()
{
	var input,filter,div,a,i,txtvalue;
	input=document.getElementById("Widget");
	filter=input.value.toUpperCase();
	div=document.getElementById("content");
	a=div.getElementsByTagName("li");
	for(i=0;i<a.length;i++)
	{
		txtvalue=a[i].textContent || a[i].innerText;
		if(txtvalue.toUpperCase().indexOf(filter)>-1)
		{
		   a[i].style.display="block";
		}
		else
		{
		  a[i].style.display="none";
		
		}
	}
}
</script>
{/literal}
