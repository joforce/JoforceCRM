{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}

{strip}
		<div class="col-lg-12" style="height:60px;">
		    <div class="d-sm-none d-none recordImage bg_{$MODULE}">
		        {assign var=IMAGE_DETAILS value=$RECORD->getImageDetails()}
		    	{if empty($IMAGE_DETAILS)}
			    {assign var=avatar value=$RECORD->getName()}
			    <div class="name"><span class="nameSize"><strong> {strtoupper($avatar[0])} </strong></span></div>
		    	{else}
			    {foreach key=ITER item=IMAGE_INFO from=$IMAGE_DETAILS}
			    	{if !empty($IMAGE_INFO.path)}
				    <img src="{$SITEURL}/{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" width="100%" height="100%" align="left"><br>
			    	{else}
				    {assign var=mod_img value="summary_`$MODULE_NAME`.png"}
				    <img src="{$SITEURL}/{vimage_path($mod_img)}" class="summaryImg" value="{$mod_img}"/>
			    	{/if}
			    {/foreach}
		        {/if}
		    </div>
	  	</div>

		<div class="recordBasicInfo">
		    <div class="info-row">
			<h4>
			    <span class="recordLabel pushDown" title="{$RECORD->getName()}">
				{if $MODULE_NAME eq 'Contacts' || $MODULE_NAME eq 'Leads'}
				    {assign var=COUNTER value=0}
        	                    {foreach item=NAME_FIELD from=$MODULE_MODEL->getNameFields()}
                	            	{assign var=FIELD_MODEL value=$MODULE_MODEL->getField($NAME_FIELD)}
                        	    	{if $FIELD_MODEL->getPermissions()}
	                                    <span class="{$NAME_FIELD}">
        	                            	{if $RECORD->getDisplayValue('salutationtype') && $FIELD_MODEL->getName() eq 'firstname'}
                	                            {$RECORD->getDisplayValue('salutationtype')}&nbsp;
                        	            	{/if}
                                	    	{trim($RECORD->get($NAME_FIELD))}
	                                    </span>
        	                        	{if $COUNTER eq 0 && ($RECORD->get($NAME_FIELD))}&nbsp;{assign var=COUNTER value=$COUNTER+1}{/if}
                	            	{/if}
	                            {/foreach}
				{else}
				    {foreach item=NAME_FIELD from=$MODULE_MODEL->getNameFields()}
				    	{assign var=FIELD_MODEL value=$MODULE_MODEL->getField($NAME_FIELD)}
			    		{if $FIELD_MODEL->getPermissions()}
					    <span class="{$NAME_FIELD}">{trim($RECORD->get($NAME_FIELD))}</span>&nbsp;
				        {/if}
				    {/foreach}
				{/if}
			    </span>
			</h4>
		    </div>

		    {include file="DetailViewHeaderFieldsView.tpl"|vtemplate_path:$MODULE}
		    {assign var=map_array value=array('Contacts','Accounts','Leads')}
		    {if $MODULE_NAME|in_array:$map_array}
			<div class="buttons-row">
                	    <i class="fa fa-map-marker"></i>&nbsp;
	                    <a class="showMap " href="javascript:void(0);" onclick='Head_Index_Js.showMap(this);' data-module='{$RECORD->getModule()->getName()}' data-record='{$RECORD->getId()}'>{vtranslate('LBL_SHOW_MAP', $MODULE_NAME)}</a>
        	        </div>
		    {/if}

		</div>
{/strip}
