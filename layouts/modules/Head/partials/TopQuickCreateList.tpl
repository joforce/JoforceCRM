{strip}
<ul class="dropdown-menu dropdown-menu-right ms_quick_create_menu" role="menu" aria-labelledby="dropdownMenu1" style="width:500px;">
                                <li class="title" style="padding: 5px 0 0 15px;">
                                    <strong>{vtranslate('LBL_QUICK_CREATE',$MODULE)}</strong>
                                </li>
                                <hr/>
                                <li id="quickCreateModules" style="padding: 0 5px;">
                                <div class="col-lg-12" style="padding-bottom:15px;">
                                    {foreach key=moduleName item=moduleModel from=$QUICK_CREATE_MODULES}
                                        {if $moduleModel->isPermitted('CreateView') || $moduleModel->isPermitted('EditView')}
                                            {assign var='quickCreateModule' value=$moduleModel->isQuickCreateSupported()}
                                            {assign var='singularLabel' value=$moduleModel->getSingularLabelKey()}
                                            {assign var=hideDiv value={!$moduleModel->isPermitted('CreateView') && $moduleModel->isPermitted('EditView')}}
                                            {if $quickCreateModule == '1'}
                                                {if $count % 3 == 0}
                                                    <div class="row">
                                                {/if}
                                                {* Adding two links,Event and Task if module is Calendar *}
                                                {if $singularLabel == 'SINGLE_Calendar'}
                                                    {assign var='singularLabel' value='LBL_TASK'}
                                                    <div class="{if $hideDiv}create_restricted_{$moduleModel->getName()} hide{else}col-md-4 col-4{/if}">
                                                        <a id="menubar_quickCreate_Events" class="quickCreateModule" data-name="Events" data-url="index.php?module=Events&view=QuickCreateAjax&page={$smarty.request.view}" href="javascript:void(0)"><i class="joicon-calendar pull-left"></i><span class="quick-create-module">{vtranslate('LBL_EVENT',$moduleName)}</span></a>
                                                    </div>
                                                    {if $count % 3 == 2}
                                                        </div><br>
                                                        <div class="row">
                                                    {/if}
							<div class="{if $hideDiv}create_restricted_{$moduleModel->getName()} hide{else}col-md-4 col-4{/if}">
                                                        <a id="menubar_quickCreate_{$moduleModel->getName()}" class="quickCreateModule" data-name="{$moduleModel->getName()}" data-url="{$moduleModel->getQuickCreateUrl()}&page={$smarty.request.view}" href="javascript:void(0)"><i class="joicon-task pull-left"></i><span class="quick-create-module">{vtranslate($singularLabel,$moduleName)}</span></a>
                                                    </div>
                                                    {if !$hideDiv}
                                                        {assign var='count' value=$count+1}
                                                    {/if}
                                                {else if $singularLabel == 'SINGLE_Documents'}
                                                    <div class="{if $hideDiv}create_restricted_{$moduleModel->getName()} hide{else}col-lg-4 col-4{/if} dropdown">
                                                        <a id="menubar_quickCreate_{$moduleModel->getName()}" class="quickCreateModuleSubmenu" data-name="{$moduleModel->getName()}" data-toggle="dropdown" data-url="{$moduleModel->getQuickCreateUrl()}&page={$smarty.request.view}" href="javascript:void(0)">
                                                            <i class="joicon-{strtolower($moduleName)} pull-left"></i>
                                                            <span class="quick-create-module">
                                                                {vtranslate($singularLabel,$moduleName)}
                                                                <i class="fa fa-caret-down quickcreateMoreDropdownAction"></i>
                                                            </span>
                                                        </a>
                                                        <ul class="dropdown-menu quickcreateMoreDropdown top_header_quick_create" aria-labelledby="menubar_quickCreate_{$moduleModel->getName()}">
                                                            <li class="dropdown-header"><i class="fa fa-upload"></i> {vtranslate('LBL_FILE_UPLOAD', $moduleName)}</li>
                                                            <li id="HeadAction">
                                                                <a href="javascript:Documents_Index_Js.uploadTo('Head')">
                                                                    <img style="  margin-top: -3px;margin-right: 4%;" title="Joforce" alt="Joforce" src="{$SITEURL}layouts/skins//images/JoForce.png"> 
                                                                    {* {vtranslate('LBL_TO_SERVICE', $moduleName, {vtranslate('LBL_VTIGER', $moduleName)})}  *}Vtiger
                                                                    </a>
                                                            </li>
                                                            <li class="dropdown-header"><i class="fa fa-link"></i> {vtranslate('LBL_LINK_EXTERNAL_DOCUMENT', $moduleName)}</li>
                                                            <li id="shareDocument"><a href="javascript:Documents_Index_Js.createDocument('E')">&nbsp;<i class="fa fa-external-link"></i>&nbsp;&nbsp; {vtranslate('LBL_FROM_SERVICE', $moduleName, {vtranslate('LBL_FILE_URL', $moduleName)})}</a></li>
                                                            <li role="separator" class="divider"></li>
                                                            <li id="createDocument"><a href="javascript:Documents_Index_Js.createDocument('W')"><i class="fa fa-file-text"></i> {vtranslate('LBL_CREATE_NEW', $moduleName, {vtranslate('SINGLE_Documents', $moduleName)})}</a></li>
                                                        </ul>
                                                    </div>
                                                {else}
                                                    <div class="{if $hideDiv}create_restricted_{$moduleModel->getName()} hide{else}col-md-4 col-4{/if}">
                                                        <a id="menubar_quickCreate_{$moduleModel->getName()}" class="quickCreateModule" data-name="{$moduleModel->getName()}" data-url="{$moduleModel->getQuickCreateUrl()}&page={$smarty.request.view}" href="javascript:void(0)">
                                                            <i class="joicon-{strtolower($moduleName)} pull-left"></i>
                                                            <span class="quick-create-module">{vtranslate($singularLabel,$moduleName)}</span>
                                                        </a>
                                                    </div>
                                                {/if}
                                                {if $count % 3 == 2}
                                                    </div>
                                                    <br>
                                                {/if}
                                                {if !$hideDiv}
                                                    {assign var='count' value=$count+1}
                                                {/if}
                                            {/if}
                                        {/if}
                                    {/foreach}
                                </div>
                            </li>
                        </ul>
{/strip}
