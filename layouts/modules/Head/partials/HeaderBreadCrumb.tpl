{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
*************************************************************************************}

	    <div class="module-breadcrumb module-breadcrumb-{$smarty.request.view} transitionsAllHalfSecond">
                {assign var=MODULE_MODEL value=Head_Module_Model::getInstance($MODULE)}
                {if $MODULE_MODEL->getDefaultViewName() neq 'List'}
                    {assign var=DEFAULT_FILTER_URL value=$MODULE_MODEL->getDefaultUrl()}
                {else}
                    {assign var=DEFAULT_FILTER_ID value=$MODULE_MODEL->getDefaultCustomFilter()}
                    {if $DEFAULT_FILTER_ID}
                        {assign var=CVURL value="/"|cat:$DEFAULT_FILTER_ID}
                        {assign var=DEFAULT_FILTER_URL value=$MODULE_MODEL->getListViewUrl()|cat:$CVURL}
                    {else}
                        {assign var=DEFAULT_FILTER_URL value=$MODULE_MODEL->getListViewUrlWithAllFilter()}
                    {/if}
                {/if}
                <a title="{vtranslate($MODULE, $MODULE)}" href='{$DEFAULT_FILTER_URL}'><h4 class="module-title pull-left text-uppercase"> {vtranslate($MODULE, $MODULE)} </h4>&nbsp;&nbsp;</a>
                {if $smarty.session.lvs.$MODULE.viewname}
                    {assign var=VIEWID value=$smarty.session.lvs.$MODULE.viewname}
                {/if}
                {if $VIEWID}
                    {foreach item=FILTER_TYPES from=$CUSTOM_VIEWS}
                        {foreach item=FILTERS from=$FILTER_TYPES}
                            {if $FILTERS->get('cvid') eq $VIEWID}
                                {assign var=CVNAME value=$FILTERS->get('viewname')}
                                {break}
                            {/if}
                        {/foreach}
                    {/foreach}
                    <p class="current-filter-name filter-name pull-left cursorPointer" title="{$CVNAME}"><span class="fa fa-angle-right pull-left" aria-hidden="true"></span><a href='{$MODULE_MODEL->getListViewUrl()}/filter/{$VIEWID}'>&nbsp;&nbsp;{$CVNAME}&nbsp;&nbsp;</a> </p>
                {/if}
                {assign var=SINGLE_MODULE_NAME value='SINGLE_'|cat:$MODULE}
                {if $RECORD and $smarty.request.view eq 'Edit'}
                    <p class="current-filter-name filter-name pull-left "><span class="fa fa-angle-right pull-left" aria-hidden="true"></span><a title="{$RECORD->get('label')}">&nbsp;&nbsp;{vtranslate('LBL_EDITING', $MODULE)} : {$RECORD->get('label')} &nbsp;&nbsp;</a></p>
                {else if $smarty.request.view eq 'Edit'}
                    <p class="current-filter-name filter-name pull-left "><span class="fa fa-angle-right pull-left" aria-hidden="true"></span><a>&nbsp;&nbsp;{vtranslate('LBL_ADDING_NEW', $MODULE)}&nbsp;&nbsp;</a></p>
                {/if}
                {if $smarty.request.view eq 'Detail' || $smarty.request.view eq 'Activity'}
                    <p class="current-filter-name filter-name pull-left"><span class="fa fa-angle-right pull-left" aria-hidden="true"></span><a title="{$RECORD->get('label')}">{$RECORD->get('label')}</a></p>
                {/if}
            </div>
