<table id="md-filters-table" style="width:100%;">
<tr>
<td style="width:20%;">
<div id="md-filters-toolbar">
	<h2>{vtranslate('LBL_FILTER_FIELDS', $QUALIFIED_MODULE)}</h2>
	
	<ul id="md-filter-fields-list">
	<!-- Fields added with JS -->
	</ul>
</div>
</td>
<td style="width:80%;">

<div id="md-add-filter-btn" class="btn btn-primary" style="float:right;">
	<img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/filter.png" alt="{vtranslate('LBL_ADD_FILTER_ALT', $QUALIFIED_MODULE)}"/> <a href="#" onclick="md_addFilter(); return false;">{vtranslate('LBL_ADD_FILTER', $QUALIFIED_MODULE)}</a>
</div>

<div>
<ul id="md-filters-ul">
<!-- Filters added with JS -->
</ul>
</div>
</td>
</table>
<div class="modulnext">
	  
	  <button class="btn btn-default filter-prev-btn ">Previous</button>
	  <button class="btn btn-primary filter-next-btn ">Next</button>
</div>
