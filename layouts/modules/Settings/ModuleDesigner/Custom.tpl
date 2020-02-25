<script type="text/javascript" src="{$SITEURL}layouts/modules/Settings/{$MODULE}/resources/CustomScript.js"></script>

<h2>{vtranslate('LBL_CUSTOM_VALUES', $QUALIFIED_MODULE)}</h2>

<table class="custom-field">
<tr>
<td colspan="2">
{vtranslate('LBL_CUSTOM_VALUES_DESCRIPTION', $QUALIFIED_MODULE)}
</td>
</tr>
<tr >
<td class="custom-field-variable col-md-6">{vtranslate('LBL_MY_VARIABLE', $QUALIFIED_MODULE)}</td>
<td class="custom-field-select col-md-6">
<select name="myVariable" class='select2'>
<option value="value1">{vtranslate('LBL_MY_VALUE', $QUALIFIED_MODULE)} 1</option>
<option value="value2">{vtranslate('LBL_MY_VALUE', $QUALIFIED_MODULE)} 2</option>
</select>
</td>
</tr>
</table>
<div class="row">
	  <button class="btn btn-primary cust-next-btn ">Next</button>
	  <button class="btn btn-default cust-prev-btn ">Previous</button>
</div>
