{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{* modules/Head/views/Detail.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
{include file="modules/Head/partials/Topbar.tpl"}
{if !$MODULE} {assign var=MODULE value=$MODULE_NAME} {/if}
</nav>
<div id='overlayPageContent' class='fade modal overlayPageContent content-area overlay-container-60' tabindex='-1'
	role='dialog' aria-hidden='true'>
	<div class="data"></div>
	<div class="modal-dialog"></div>
</div>
<div class="hide container-fluid app-nav module-header  {if $LEFTPANELHIDE eq '1'} full-header {/if}">
	<div class="row">
		{include file="ModuleHeader.tpl"|vtemplate_path:$MODULE}
	</div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 detail-view-header {if $LEFTPANELHIDE eq '0'} detail-view-header-shrinked {/if}"
	id="detail-view-header">
	<div class="col-md-5 col-lg-5 col-sm-12 col-xs-12 detail-view-breadcrumb">
		{include file="partials/HeaderBreadCrumb.tpl"|vtemplate_path:$MODULE}
	</div>
	<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 detailViewButtoncontainer">
		{include file="DetailViewActions.tpl"|vtemplate_path:$MODULE}
	</div>
</div>

<div class="main-container main-container-{$MODULE} ">
	<div id="sidebar-essentials" class="sidebar-essentials {if $LEFTPANELHIDE eq '1'} shrinked-sidebar {/if}">
		{include file="partials/SidebarAppMenu.tpl"|vtemplate_path:$MODULE}
	</div>
	<div class="quick-panel"></div>
	<div class="detailViewPageDiv content-area {$MODULE} {if $LEFTPANELHIDE eq '1'} full-width {/if}" id="detailViewContent">
		<div id="licence-alert-waring" class="alert">
			<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>

			<strong> <span class="licence-waring-icon"><i class="fa fa-warning"></i> </span> Danger!</strong>  You are not secure

		</div>
		{if !in_array($MODULE,
					array('Invoice','Quotes','SalesOrder','PurchaseOrder','Calendar','Campaigns','Documents','PriceBooks','Vendors'))}
		<div class='joforce-bg'>
			<div
				class="detailViewContainer viewContent clearfix {if in_array($MODULE,array('Quotes','Campaigns','Vendors','PriceBooks','PurchaseOrder','SalesOrder','Documents','Calendar','Auction'))} ipad_Quote_detail_page {/if}">
				{assign var=FIELDS_MODELS_LIST value=$MODULE_MODEL->getFields()}
				<div class="col-lg-12 col-sm-12 col-xs-12">
					{* <h1 class="mt50 mb50">{$MODULE}</h1> *}
					
					<div
						class=" detailview-header-block details_view_detailview_header_block {if in_array($MODULE,array('Services','Products','Contacts','Accounts','Leads','Potentials','HelpDesk'))} details_view_detailview_header_block {/if}">
						<div class="detailview-header">
							<div class="row">
								<div
									class="col-lg-12 col-sm-12 col-xs-12 pl0 {if in_array($MODULE,array('Products','HelpDesk'))} mt0 {/if}">
									<div class="col-lg-6 col-md-12 col-sm-6 p0 pull-left ">
										{if in_array($MODULE,
										array('Products','Contacts','Accounts','Leads','Potentials'))}
										<div class="col-lg-6 col-md-6 col-sm-3 pull-left clearfix ">
											<div class="white-background m0 p0 ipad_white_background">
												{assign var=map_array value=array('Contacts','Accounts','Leads',
												'Vendors','Potentials','Auction')}
												{if $MODULE_NAME|in_array:$map_array}
												{include file="DetailViewHeaderTitleCard.tpl"|vtemplate_path:$MODULE}
												{else}
												{include file="DetailViewHeaderTitle.tpl"|vtemplate_path:$MODULE}

												{/if}
											</div>
										</div>
										{/if}
										<div
											class="{if in_array($MODULE, array('Products','Contacts','Accounts','Leads','Potentials'))} col-lg-6 col-md-6 col-sm-3 pull-left pl0  {else} resClass mr20 ml20 mob_mr-top {/if} ">
											<div class="white-background m0 {if !in_array($MODULE,array('HelpDesk'))}ipad_white_background{/if}"
												style="padding: 0 10px;">


												{include
												file="DetailViewHeaderSummaryContents.tpl"|vtemplate_path:$MODULE}
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-12 col-sm-6 pull-left p0 dash">
										<div class="white-background m0">
											{include file="CumulativeSummary.tpl"|vtemplate_path:$MODULE}
										</div>
									</div>
								</div>
							</div>
						</div>




						<div class="showAllTagContainer hide">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<form class="detailShowAllModal MultiFile-intercepted">
										<div class="modal-header">
											<div class="clearfix">
												<div class="pull-right "><button type="button" class="close"
														aria-label="Close" data-dismiss="modal"><span aria-hidden="true"
															class="fa fa-close"></span></button></div>
												<h4 class="pull-left">Add/Select Tag</h4>
											</div>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-lg-6 selectTagContainer">
													<div class="form-group"><label class="col-form-label">Current
															Tags</label>
														<div class="currentTagScroll">
															<div class="currentTag multiLevelTagList form-control"><span
																	class="noTagsPlaceHolder"
																	style="padding:3px;display:none;border:1px solid transparent;color:#999">No
																	Tag Exists</span>
																<span class="tag tag_2 " title="sarva"
																	data-type="private" data-id="3">
																	<i class="activeToggleIcon fa  fa-circle "></i>
																	<span
																		class="tagLabel display-inline-block textOverflowEllipsis"
																		title="sarva">sarva</span>
																	<i class="editTag fa fa-pencil"></i>
																	<i class="deleteTag fa fa-times"></i>
																</span>

																<span class="tag tag_2 " title="vijay"
																	data-type="private" data-id="2">
																	<i class="activeToggleIcon fa  fa-circle "></i>
																	<span
																		class="tagLabel display-inline-block textOverflowEllipsis"
																		title="vijay">vijay</span>
																	<i class="editTag fa fa-pencil"></i>
																	<i class="deleteTag fa fa-times"></i>
																</span>
															</div>
														</div>
													</div>
													<div class="form-group"><label class="col-form-label">Select from
															available tags</label>
														<div class="dropdown"><input
																class="form-control currentTagSelector dropdown-toggle"
																data-toggle="dropdown"
																placeholder="Type here to select an existing tag">
															<div class="dropdown-menu currentTagMenu">
																<div class="scrollable" style="max-height:300px">
																	<ul style="padding-left:0px;">
																		<li
																			class="dummyExistingTagElement tag-item list-group-item hide">
																			<a style="margin-left:0px;">
																				<span class="tag tag_2 " title=""
																					data-type="" data-id="">
																					<i
																						class="activeToggleIcon fa  fa-circle "></i>
																					<span
																						class="tagLabel display-inline-block textOverflowEllipsis"
																						title=""></span>
																				</span>
																			</a></li>
																		<li class="tag-item list-group-item"><span
																				class="noTagExistsPlaceHolder"
																				style="padding:3px;color:#999">No Tag
																				Exists</span></li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class=" col-lg-6 selectTagContainerborder">
													<div class="form-group"><label class="col-form-label">Create new
															tag</label>
														<div><input name="createNewTag" value="" class="form-control"
																placeholder="Enter tag name"></div>
													</div>
													<div class="form-group">
														<div>
															<div class="checkbox"><label><input type="hidden"
																		name="visibility" value="private"><input
																		type="checkbox" name="visibility"
																		value="public">&nbsp; Public Tags</label></div>
															<div class="pull-right"></div>
														</div>
													</div>
													<div class="form-group">
														<div class=" vt-default-callout vt-info-callout tagInfoblock">
															<h5 class="vt-callout-header"><span
																	class="fa fa-info-circle"></span>&nbsp; Info </h5>
															<div>Use comma to separate multiple tags</div><br>
															<div>Shared tags are accessible by all users in Joforce
															</div><br>
															<div>Go to Settings &gt; My preferences &gt; My Tags to Edit
																or Delete your private tags</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer ">
											<center>
												<button class="btn btn-primary" type="submit"
													name="saveButton"><strong>Save</strong></button>
												<a href="#" class="cancelLink btn btn-danger" type=""
													data-dismiss="modal">Cancel</a><a class="btn back-button"
													onclick=""><strong>Back</strong></a>
											</center>
										</div>
									</form>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>







		</div>

		{/if}
		<div class="col-md-12">
			{if $kanban_view_enabled}
			<div id='pipeline_stages' class="{$MODULE}">
				<ul class="nav nav-pills nav-wizard nav-justified pipe-stage"></ul>
			</div>
			{/if}
		</div>
		{*closing div of detailviewHeader*}

		<div class="joforce-tabs-list" id="joforce-tabs-list">
			<div class="detailview-content container-fluid {$MODULE}">
				<input id="recordId" type="hidden" value="{$RECORD->getId()}" />
				<div class="" style="float:right;position:relative">
					{include file="ModuleRelatedTabs.tpl"|vtemplate_path:$MODULE}
				</div>
				<div class="details row row-sm col-lg-12 col-xl-12 col-md-12 col-sm-11 pull-left p0 m3 {$MODULE}">
					<div
						class="col-lg-12 col-xl-12 col-md-12 col-sm-12 p0 m0 pull-left {if in_array($MODULE,array('Potentials','Accounts','HelpDesk'))} full_width_doc_acti {/if}">
						<div
							class="col-lg-12 col-xl-12 col-md-12 col-sm-12 pl10 m0 pull-left {if in_array($MODULE,array('Campaigns'))} ipad_header_less_details_page {elseif in_array($MODULE,array('Accounts','Potentials'))} big_scr_details_page {elseif in_array($MODULE,array('Contacts'))}big_scr_details_page_contact {elseif in_array($MODULE,array('Services'))}big_scr_details_page_services {elseif in_array($MODULE,array('Leads'))} Mac_scr_Leads_page_details {/if}">