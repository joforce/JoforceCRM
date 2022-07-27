<div class="setmanagement mt0 card" style="width:100% !important;">
    <div class="row-fluid widget_head" style="">
	<div class="col-md-12 card-header-new p0 ml5 mb10">
	    <a href="index.php?module={$MODULE}&view=Index&parent=Settings"><h3>{vtranslate('LBL_MODULEDESIGNER', $QUALIFIED_MODULE)}</h3></a>
	    
	</div>
    </div>

    <div id="md-container">
	<div id="md-header" class='related-tabs '>
		<ul class="nav nav-tabs bread-box">
		  <li class="active md-tab" id='md-tab-general'>
		 
		    <a class="active step1" data-toggle="tab" href="#md-page-general"><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/config.png" alt="{vtranslate('LBL_GENERAL_ALT', $QUALIFIED_MODULE)}" /> {vtranslate('LBL_GENERAL', $QUALIFIED_MODULE)}</a>
		  </li>
		  <li class="md-tab" id='md-tab-blocks-fields'>
		    <a class="" href="#md-page-blocks-fields"><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/field.png" alt="{vtranslate('LBL_BLOCKS_FIELDS_ALT', $QUALIFIED_MODULE)}" /> {vtranslate('LBL_BLOCKS_FIELDS', $QUALIFIED_MODULE)}</a>
		  </li>
		  <li class="md-tab " id='md-tab-custom-links'>
		    <a class="" href="#md-page-custom-links"><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/link.png" alt="{vtranslate('LBL_CUSTOM_LINKS_ALT', $QUALIFIED_MODULE)}" /> {vtranslate('LBL_CUSTOM_LINKS', $QUALIFIED_MODULE)}</a>
		  </li>
		  <li class="md-tab " id='md-tab-related-lists'>
		    <a class="" href="#md-page-related-lists"><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/related.png" alt="{vtranslate('LBL_RELATED_LISTS_ALT', $QUALIFIED_MODULE)}" /> {vtranslate('LBL_RELATED_LISTS', $QUALIFIED_MODULE)}</a>
		  </li>
		  <li class="md-tab " id='md-tab-events'>
		    <a class="" href="#md-page-events"><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/event.png" alt="{vtranslate('LBL_EVENTS_ALT', $QUALIFIED_MODULE)}" /> {vtranslate('LBL_EVENTS', $QUALIFIED_MODULE)}</a>
		  </li>
		  <li class="md-tab " id='md-tab-filters'>
		    <a class="" href="#md-page-filters"><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/filter.png" alt="{vtranslate('LBL_FILTERS_ALT', $QUALIFIED_MODULE)}" /> {vtranslate('LBL_FILTERS', $QUALIFIED_MODULE)}</a>
		  </li>
		  <li class="md-tab " id='md-tab-custom'>
		    <a class="" href="#md-page-custom"><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/custom.png" alt="{vtranslate('LBL_CUSTOM_ALT', $QUALIFIED_MODULE)}" /> {vtranslate('LBL_CUSTOM', $QUALIFIED_MODULE)}</a>
		  </li>
		  <li class="md-tab " id='md-tab-export'>
		    <a class="" href="#md-page-export"><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/export.png" alt="{vtranslate('LBL_EXPORT_ALT', $QUALIFIED_MODULE)}" /> {vtranslate('LBL_EXPORT', $QUALIFIED_MODULE)}</a>
		  </li>
		</ul>
	</div><!-- md-header -->
	<div id="md-body" class='tab-content ml50'>
			<div id="md-trash" class="md-trash"></div>
	
			<div id="md-page-general" class="md-page active tab-pane">
				{include file='modules/Settings/'|@cat:$MODULE|@cat:'/General.tpl'}
			</div><!-- md-page-general -->
			
			<div id="md-page-blocks-fields" class="md-page tab-pane">
				{include file='modules/Settings/'|@cat:$MODULE|@cat:'/BlocksFields.tpl'}
			</div><!-- md-page-blocks-fields -->
			
			<div id="md-page-custom-links" class="md-page tab-pane">
				{include file='modules/Settings/'|@cat:$MODULE|@cat:'/CustomLinks.tpl'}
			</div><!-- md-page-custom-links -->
			
			<div id="md-page-related-lists" class="md-page tab-pane">
				{include file='modules/Settings/'|@cat:$MODULE|@cat:'/RelatedLists.tpl'}
			</div><!-- md-page-related-list -->
			
			<div id="md-page-events" class="md-page tab-pane">
				{include file='modules/Settings/'|@cat:$MODULE|@cat:'/Events.tpl'}
			</div><!-- md-page-events -->
			
			<div id="md-page-filters" class="md-page tab-pane">
				{include file='modules/Settings/'|@cat:$MODULE|@cat:'/Filters.tpl'}
			</div><!-- md-page-filters -->
			
			<div id="md-page-custom" class="md-page tab-pane">
				{include file='modules/Settings/'|@cat:$MODULE|@cat:'/Custom.tpl'}
			</div><!-- md-page-custom -->
			
			<div id="md-page-export" class="md-page tab-pane">
				{include file='modules/Settings/'|@cat:$MODULE|@cat:'/Export.tpl'}
			</div><!-- md-page-export -->	
			
			
	</div><!-- md-body -->
	
</div><!-- md-container -->
</div> <!-- joforce bg end -->

<input type="hidden" id="md-default-language" value="{$DEFAULT_LANGUAGE}" />
<a id="md-edit-popup-link" data-fancybox-type="iframe" href="#">Edit popup link</a>

