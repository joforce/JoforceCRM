<form class="form user_setup" method="POST" action="index.php?module=Users&action=UserSetupSave" style="display: inline-block;width:100%">
    <input type="hidden" name="record" value="{$CURRENT_USER_MODEL->getId()}">
   
       
    <label class="col-form-label"><strong>{vtranslate('Preferences', $MODULE)}</strong><span class="muted">{vtranslate('LBL_ALL_FIELDS_BELOW_ARE_REQUIRED', $MODULE)}</label>
    {if $IS_FIRST_USER}
        <div class="controls" id="currency_name_controls" style="margin-top: 3%;">
            <select name="currency_name" id="currency_name" placeholder="{vtranslate('LBL_BASE_CURRENCY', $MODULE)}" data-errormessage="{vtranslate('LBL_CHOOSE_BASE_CURRENCY', $MODULE)}" class="validate[required] select2 inputElement" style="width:250px;">
                <option value=""></option>
                {foreach key=header item=currency from=$CURRENCIES}
                    <!--Open source fix to select user preferred currency during installation -->
                    <option value="{$header}" {if $header eq $CURRENT_USER_MODEL->get('currency_name')}selected{/if}>{$header|@getTranslatedCurrencyString}({$currency.1})</option>
                {/foreach}
            </select>&nbsp;
            <span rel="tooltip" title="{vtranslate('LBL_OPERATING_CURRENCY', $MODULE)}" id="currency_name_tooltip" class="icon-info-sign"></span> 
        </div>
    {/if}
    <div class="controls">
        <select name="lang_name" id="lang_name" style="width:250px;" placeholder="{vtranslate('LBL_LANGUAGE', $MODULE)}" data-errormessage="{vtranslate('LBL_CHOOSE_LANGUAGE', $MODULE)}" class="validate[required] select2 inputElement">
            <option value=""></option>
            {foreach key=header item=language from=$LANGUAGES}
                <option value="{$header}" {if $header eq $CURRENT_USER_MODEL->get('language')}selected{/if}>{$language|@getTranslatedString:$MODULE}</option>
            {/foreach}
        </select>
        <div style="padding-top:10px;"></div>
    </div>
    <div class="controls">
        <select name="time_zone" id="time_zone" style="width:250px;" placeholder="{vtranslate('LBL_CHOOSE_TIMEZONE', $MODULE)}" data-errormessage="{vtranslate('LBL_CHOOSE_TIMEZONE', $MODULE)}" class="validate[required] select2 inputElement">
            <option value=""></option>
            {foreach key=header item=time_zone from=$TIME_ZONES}
                <option value="{$header}" {if $header eq $CURRENT_USER_MODEL->get('time_zone')}selected{/if}>{$time_zone|@getTranslatedString:$MODULE}</option>
            {/foreach}
        </select>
        <div style="padding-top:10px;"></div>
    </div>
    <div class="controls">
        <select name="date_format" id="date_format" style="width:250px;" placeholder="{vtranslate('LBL_DATE_FORMAT', $MODULE)}" data-errormessage="{vtranslate('LBL_CHOOSE_DATE_FORMAT', $MODULE)}" class="validate[required] select2 inputElement">
            <option value=""></option>
            <option value="dd-mm-yyyy" {if $CURRENT_USER_MODEL->get('date_format') eq "dd-mm-yyyy"} selected{/if}>dd-mm-yyyy</option>
            <option value="mm-dd-yyyy" {if $CURRENT_USER_MODEL->get('date_format') eq "mm-dd-yyyy"} selected{/if}>mm-dd-yyyy</option>
            <option value="yyyy-mm-dd" {if $CURRENT_USER_MODEL->get('date_format') eq "yyyy-mm-dd"} selected{/if}>yyyy-mm-dd</option>
        </select>
        <div style="padding-top:10px;"></div>
    </div>
       
</form>
