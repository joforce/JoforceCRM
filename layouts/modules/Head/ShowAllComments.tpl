{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{strip}
{assign var=MODULE value=$MODULE_NAME}
<form id="detailView" method="POST" style="1000px">
	{assign var="COMMENT_TEXTAREA_DEFAULT_ROWS" value="2"}
	{assign var="PRIVATE_COMMENT_MODULES" value=Head_Functions::getPrivateCommentModules()}
	{assign var=IS_CREATABLE value=$COMMENTS_MODULE_MODEL->isPermitted('CreateView')}
	{assign var=IS_EDITABLE value=$COMMENTS_MODULE_MODEL->isPermitted('EditView')}

	<div class="commentContainer commentsRelatedContainer container-fluid p0 ml35 {if in_array($MODULE,array('Contacts','HelpDesk'))} mac_scrn_comments_section {if in_array($MODULE,array('HelpDesk'))} ipadpro_scr_style {/if}  {elseif in_array($MODULE,array('Leads'))} mac_scrn_lead_comments {/if} {if in_array($MODULE,array('Contacts'))} big_scr_details_view_contact_comment {/if} {if in_array($MODULE,array('PurchaseOrder','SalesOrder','Quotes','Invoice'))} ms_scr_cmt_sec {/if}" >
		{if $IS_CREATABLE}
		
			<div class="col-lg-12 p0 {if in_array($MODULE,array('PurchaseOrder','SalesOrder','Quotes','Invoice'))} ms_scr_cmt {/if}">
			<div class="showcomments">
				<div class="addCommentBlock">
					<div class="commentTextArea">
						<div class="card-header header-elements-inline">
							<h4 class="card-title">Comment</h4>
						</div>
						<textarea name="commentcontent" class="commentcontent form-control mention_listener"  placeholder="{vtranslate('LBL_POST_YOUR_COMMENT_HERE', $MODULE_NAME)}" rows="{$COMMENT_TEXTAREA_DEFAULT_ROWS}"></textarea>
					</div>
					<div class="d-flex justify-content-between" style="width:95%;margin:0 auto;">
					{if $MODULE_NAME ne 'Leads'}
					<div class="col-lg-5 commentHeader " style="">
						<div class="display-inline-block">
							<span class="">{vtranslate('LBL_ROLL_UP',$QUALIFIED_MODULE)} &nbsp;</span>
							<span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{vtranslate('LBL_ROLLUP_COMMENTS_INFO',$QUALIFIED_MODULE)}"></span>&nbsp;&nbsp;
						</div>
						<input type="checkbox" class="bootstrap-switch" id="rollupcomments" hascomments="1" startindex="{$STARTINDEX}" data-view="relatedlist" rollupid="{$ROLLUPID}" 
							   rollup-status="{$ROLLUP_STATUS}" module="{$MODULE_NAME}" record="{$MODULE_RECORD}" checked data-on-color="success"/>
					</div> 
				{/if}
				<div class="d-flex flex-row-reverse">
				<div class="pull-right mt2">
								<button class="btn btn-primary btn-sm saveComment" type="button" data-mode="add"><strong>{vtranslate('LBL_POST', $MODULE_NAME)}</strong></button>
								</div>
						<div>
							{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE_NAME) MODULE="ModComments"}
						</div>
						<div>
							<div class="mt-2 ">
								{if in_array($MODULE_NAME, $PRIVATE_COMMENT_MODULES)}

									<input type="checkbox" id="is_private">&nbsp;&nbsp;{vtranslate('LBL_INTERNAL_COMMENT')}&nbsp;
									<i class="fa fa-question-circle cursorPointer" data-toggle="tooltip" data-placement="top" data-original-title="{vtranslate('LBL_INTERNAL_COMMENT_INFO')}"></i>&nbsp;&nbsp;
								{/if}
								
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>
			<div class=" container-fluid ">
			
			<hr>
			<div class="commentsList commentsBody marginBottom15">
				{include file='CommentsList.tpl'|@vtemplate_path COMMENT_MODULE_MODEL=$COMMENTS_MODULE_MODEL IS_CREATABLE=$IS_CREATABLE IS_EDITABLE=$IS_EDITABLE}
			</div>

			<div class="hide basicAddCommentBlock container-fluid" style="border:solid 1px #eee;border-radius:5px;padding:10px;margin-top:20px;">
				<textarea name="commentcontent" class="commentcontent form-control" placeholder="{vtranslate('LBL_POST_YOUR_COMMENT_HERE', $MODULE_NAME)}" rows="{$COMMENT_TEXTAREA_DEFAULT_ROWS}"></textarea>
				<div class="d-flex justify-content-end" style="width:95%;margin:0 auto;">
					{if in_array($MODULE_NAME, $PRIVATE_COMMENT_MODULES)}
						<input type="checkbox" id="is_private">&nbsp;&nbsp;{vtranslate('LBL_INTERNAL_COMMENT')}&nbsp;&nbsp;
					{/if}
					<button class="btn btn-primary btn-sm saveComment" type="button" data-mode="add"><strong>{vtranslate('LBL_POST', $MODULE_NAME)}</strong></button>
					<a href="javascript:void(0);" class="cursorPointer closeCommentBlock cancelLink btn btn-danger" type="">{vtranslate('LBL_CANCEL', $MODULE_NAME)}</a>
				</div>
			</div>

			<div class="hide basicEditCommentBlock container-fluid" style="border:solid 1px #eee;border-radius:5px;padding:10px;margin-top:20px;">
					<input style="width:95%;height:30px;margin:0 auto;" type="text" name="reasonToEdit" placeholder="{vtranslate('LBL_REASON_FOR_CHANGING_COMMENT', $MODULE_NAME)}" class="input-block-level form-control"/>
					<textarea name="commentcontent" class="commentcontenthidden form-control"  placeholder="{vtranslate('LBL_ADD_YOUR_COMMENT_HERE', $MODULE_NAME)}" rows="{$COMMENT_TEXTAREA_DEFAULT_ROWS}"></textarea>
				<input type="hidden" name="is_private">
				<div class="d-flex justify-content-end" style="width:95%;margin:0 auto;">
					<button class="btn btn-primary btn-sm saveComment" type="button" data-mode="edit"><strong>{vtranslate('LBL_POST', $MODULE_NAME)}</strong></button>
					<a href="javascript:void(0);" class="cursorPointer closeCommentBlock cancelLink btn btn-danger" type="">{vtranslate('LBL_CANCEL', $MODULE_NAME)}</a>
				</div>
			</div>
			</div>
		</div>
		{/if}
		
	</div>
</form>
{/strip}
<style>
.card-header:first-child {
border-radius: calc(0.125rem - 1px) calc(0.125rem - 1px) 0 0;
}
.card-header:first-child {
border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
}
.activityContents .card-head, .card-header {
background-color: #fff0 !important;
padding: 0px 0;
margin-bottom: 0;
background-color: #00000008;
}
.card-header {
padding: 1rem 1rem;
margin-bottom: 0;
background-color: #0000;
border-bottom: 1px solid #0000;
}
.card-header {
padding: 0.75rem 1.25rem;
margin-bottom: 0;
background-color: #00000008;
border-bottom: 1px solid #00000020;
}
.detailview-content .commentsRelatedContainer .commentTitle {
border: 1px solid #CCC; background-color: #fff;
padding: 0px;
}
.detailview-content .commentsRelatedContainer .commentTextArea {
padding-bottom: 10px;
}
.card-header {
padding: 0.75rem 1.25rem;
margin-bottom: 0;
background-color: #00000008;
border-bottom: unset;
}
.addCommentBlock .card-title {
font-size: 1.5rem; 
font-weight: 400;background: #fff;
padding: 17px 22px;box-shadow: -3px -1px 13px 0 #dadee8;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}
.header-elements-inline .header-elements {
display: -ms-flexbox!important;
display: flex!important;
-ms-flex-align: center;
align-items: center;
-ms-flex-wrap: wrap;
flex-wrap: wrap;
padding: 0;
background-color: #0000;
border: 0;
margin-left: 0;
margin-right: 0;
}
.form-check-switchery:not(.dropdown-item).form-check-right {
padding-left: 0;
padding-right: 3rem;}
.switchery>small {
background-color: #fff;
width: 1.125rem;
height: 1.125rem;
position: absolute;
top: 0;
box-shadow: 0 1px 3px rgb(0 0 0 / 40%);
border-radius: 100px;
}
.form-check-switchery:not(.dropdown-item).form-check-right .switchery {
left:1000px;
top: -50px;
}
.activityContents .card-head, .card-header {
background-color: hsla(0,0%,100%,0) !important;
padding: 0px 0;
margin-bottom: 0;
background-color: rgba(0,0,0,.03);
}
.form-check-switchery .switchery {
position: absolute;
top: 0;
left: 0;
margin-top: .00002rem;
}
.switchery {
background-color: #fff;
border: 1px solid #ddd;
cursor: pointer;
display: block;
line-height: 1;
width: 2.25rem;
height: 1.125rem;
position: relative;
box-sizing: content-box;
border-radius: 100px;}
.addCommentBlock .commentTextArea .header-elements{ float: left;}
.detailview-content .commentsRelatedContainer textarea {
width: 95%;
height: 73px;
resize: none;margin: 0 auto;border-radius: 5px; margin-top:20px;
}
.addCommentBlock .commentTextArea .post {
width: 94%;
margin: 0 0 0px 70px;
height: 35px;
background: #f9fafb;
position: relative;
top: -2px;
left: -44px;
}
.addCommentBlock .commentTextArea .post i {
margin: 10px 10px;}
.addCommentBlock .commentTextArea .post button {
float: right;
background-color: #56b1ff;
color: #fff;
border: none;
padding: 7px 30px;
}
.comment_profile_photo .comment_bg {
background-color: #fff;
padding: 10px;
margin-bottom: 10px;margin-top:10px;
box-shadow: -8px 12px 18px 0 #dadee8;
border-radius: 5px;
}
.comment_profile_photo .comment_bg .fist_comment .profile_img {
height: 40px;
margin: 0 20px;
float: left;
}
.comment_profile_photo .comment_bg h5 {
padding-right: 10px;
display: inline-block;
float: left;
color: #3977c2 !important;
}
.comment_profile_photo .comment_bg p {
display: inline-block;
}
.comment_profile_photo .comment_bg span {
float: right;
color: #aeb2b7;
}
.comment_bg .fist_comment .remove_reply {
margin-left: 30px;
}
.comment_bg .remove_reply a {
padding: 5px;
}
.comment_profile_photo .comment_bg {
background-color: #fff;
padding: 10px;
margin-bottom: 10px;
box-shadow: -8px 12px 18px 0 #dadee8;
border-radius: 5px;
}
.comment_bg .fist_comment .reply_comment {
padding: 18px 0px 0px 80px;
}
.comment_profile_photo .comment_bg .reply_comment .standard {
height: 40px;
float: left;
margin-right: 10px;
}

.commentDetails{
	margin-top:20px;
}
.commentContainer.commentsRelatedContainer .noCommentsMsgContainer .textAlignCenter{
	
}
</style>
