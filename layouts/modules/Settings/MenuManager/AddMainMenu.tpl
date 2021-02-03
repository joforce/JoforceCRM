{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{if $MESSAGE}
<div class="modal-dialog">
    <div class="modal-content" id="add-main-menu-content-modal">
      <div class="modal-header" style="min-height:53px">
        <strong>Permission Denied</strong>
        <button type="button" class="close" aria-label="Close" data-dismiss="modal" style="color: inherit;"><span aria-hidden="true" class="fa fa-close"></span></button>
      </div>

      <div class="modal-body">
           <div class="">
                <form class="main-menu-modal-form" action>
                        <div class="add-main-menu-content">{vtranslate($MESSAGE,$QUALIFIED_MODULE )}
                        </div>  
                </form> 
            </div>
      </div>
        
      <div class="modal-footer ">
            <center>
                <a href="#" class="cancelLink btn btn-secondary" type="reset" data-dismiss="modal">Cancel</a>
            </center>
      </div>
    </div>
</div>
{else}
    {if $TYPE == "module"}
<div class="modal-dialog">
	<div class="modal-content" id="add-main-menu-content-modal">
	      <div class="modal-header" style="min-height:53px">
		<strong>{vtranslate('LBL_CHOOSE_MODULE_SHORTCUT', $QUALIFIED_MODULE)}</strong>
		<button type="button" class="close" aria-label="Close" data-dismiss="modal" style="color: inherit;">
			<span aria-hidden="true" class="fa fa-close"></span>
		</button>
	      </div>
	
	      <div class="modal-body">
		   <div class="">
			<form class="main-menu-modal-form" action>
				<div class="add-main-menu-content">
					<span>Choose Module:</span>
					<select class="select2 inputElement" name="selected-menu" value='' id="select-module">
						<option></option>
						{foreach item=tabid from=$TADID_ARRAY}
							{assign var=moduleModel value=Settings_MenuManager_Module_Model::getModuleInstanceById($tabid)}
	                       			        {assign var=moduleName value=$moduleModel->get('name')}
			                                {assign var=translatedModuleLabel value=vtranslate($moduleModel->get('label'),$moduleName )}
							<option value="{$tabid}">{$translatedModuleLabel}</option>
						{/foreach}
					</select>
				</div>
			</form>
	      	    </div>
	      </div>
	
	      <div class="modal-footer ">
      		    <center>
			<button class="btn btn-success save-main-menu" id="save-main-menu" type="submit" name="saveButton" data-type="module">
				<strong>Save</strong>
			</button>
			<a href="#" class="cancelLink" type="reset" data-dismiss="modal">Cancel</a>
		    </center>
	      </div>
	</div>
</div>
    {else}
	<div class="modal-dialog">
	<div class="modal-content" id="add-main-menu-content-modal">
              <div class="modal-header" style="min-height:53px">
                <strong>{vtranslate('LBL_ADD_LINK_AND_URL', $QUALIFIED_MODULE)}</strong>
                <button type="button" class="close" aria-label="Close" data-dismiss="modal" style="color: inherit;">
                        <span aria-hidden="true" class="fa fa-close"></span>
                </button>
              </div>

              <div class="modal-body">
                   <div class="">
                        <form class="main-menu-modal-form" action>
                                <div class="add-main-menu-content">
					<div style="margin-bottom: 20px;margin-top: 20px;">
	                                        <span>Link Label : </span>
						<input type="text" style="width:400px;" name="link-name" id="linkname" class="inputElement">
					</div>
					
					<div style="margin-bottom: 20px;">					
						<span>Link URL   : </span>
        	                                <input type="text" style="width:400px;" name="link-url" id="linkurl" class="inputElement">
					</div>
                                </div>
                        </form>
                    </div>
              </div>

              <div class="modal-footer ">
                    <center>
                        <button class="btn btn-primary save-main-menu" id="save-main-menu" type="submit" data-type="link" name="saveButton">
				<strong>Save</strong>
			</button>
                        <a href="#" class="cancelLink btn btn-secondary" type="reset" data-dismiss="modal">Cancel</a>
                    </center>
              </div>
        </div>	
	</div>
    {/if}
{/if}
