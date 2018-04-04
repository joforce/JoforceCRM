{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
<div class="modal-dialog">
    <div class="modal-content" id="add-main-menu-content-modal">
	<div class="modal-header" style="min-height:53px">
                <strong>Give Section Name and Choose any module </strong>
                <button type="button" class="close" aria-label="Close" data-dismiss="modal" style="color: inherit;">
                        <span aria-hidden="true" class="fa fa-close"></span>
                </button>
        </div>

        <div class="modal-body">
        	<form class="main-menu-modal-form" action>
                	<div class="add-main-menu-content">
				 <div style="margin-bottom:10px;">
                        		 <span>Section Name:</span>
                                         <input class="inputElement" name="section-name" id="section-name">
                                 </div>
			
				 <div style="margin-bottom:10px;">
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
		
				<div style="margin-bottom:10px;">
                                         <span> Font Awesome Icon:</span>
                                         <input class="inputElement" name="icon-info" id="icon-info">
                                </div>
                        </div>
        	</form>
	
		<div class="pull-right fa fa-hand-o-right">
        	        <a href="https://www.w3schools.com/icons/fontawesome_icons_webapp.asp">Choose Icon Here</a>
	        </div>

	</div>

        <div class="modal-footer ">
        	<center>
                        <button class="btn btn-success save-section" id="save-section" type="submit" name="saveButton" data-type="module">
                                <strong>Save</strong>
                        </button>
                        <a href="#" class="cancelLink" type="reset" data-dismiss="modal">Cancel</a>
                </center>
	</div>
    </div>
</div>
