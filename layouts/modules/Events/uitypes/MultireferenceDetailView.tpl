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
{strip}
    {foreach item=CONTACT_INFO from=$RELATED_CONTACTS}
        <a href='{$CONTACT_INFO['_model']->getDetailViewUrl()}' title='{vtranslate("Contacts", "Contacts")}'> {Head_Util_Helper::getRecordName($CONTACT_INFO['id'])}</a>
        <br>
    {/foreach}
{/strip}