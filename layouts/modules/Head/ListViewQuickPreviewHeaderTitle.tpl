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
   {assign var=QUICK_PREVIEW value="true"}
        <div class="col-lg-5 col-md-5 col-sm-5">
                <div class="record-header clearfix">
                        {if !$MODULE}
                                {assign var=MODULE value=$MODULE_NAME}
                        {/if}
                        <div class="quickviewimage bg_{$MODULE}" id="quick-view-module-image">
                                <div class="name">
                                        <span><strong><i class="joicon-{strtolower($MODULE)}"></i></strong></span>
                                </div>
                        

	                        <div class="recordBasicInfo">
        	                        <div class="info-row">
                	                        <h4>
                        	                        <span class="recordLabel pushDown" title="{$RECORD->getName()}">
                                                        {foreach item=NAME_FIELD from=$MODULE_MODEL->getNameFields()}
                                                                {assign var=FIELD_MODEL value=$MODULE_MODEL->getField($NAME_FIELD)}
                                                                {if $FIELD_MODEL->getPermissions()}
                                                                        <span class="{$NAME_FIELD}">{$RECORD->get($NAME_FIELD)}</span>&nbsp;
                                                                {/if}
                                                        {/foreach}
                                	                </span>
                                        	</h4>
	                                </div>
                                {include file="DetailViewHeaderFieldsView.tpl"|vtemplate_path:$MODULE}
        	                </div>

                        </div>
                </div>
        </div>
{/strip}
