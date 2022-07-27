/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Head_List_Js("Inventory_List_Js", {

},
        {

            showQuickPreviewForId: function(recordId, templateId) {
                var self = this;
                var vtigerInstance = Head_Index_Js.getInstance();
                vtigerInstance.showQuickPreviewForId(recordId, self.getModuleName(), templateId);
            },
            
            registerEvents: function() {
                this._super();
            }

        });
