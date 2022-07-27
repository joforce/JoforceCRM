{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{strip}
{* Change to this also refer: AddCommentForm.tpl *}
{assign var="COMMENT_TEXTAREA_DEFAULT_ROWS" value="2"}
{assign var="PRIVATE_COMMENT_MODULES" value=Head_Functions::getPrivateCommentModules()}
{assign var=IS_CREATABLE value=$COMMENTS_MODULE_MODEL->isPermitted('CreateView')}
{assign var=IS_EDITABLE value=$COMMENTS_MODULE_MODEL->isPermitted('EditView')}

<div class="commentContainer recentComments">
    <div class="commentTitle">
	{if $IS_CREATABLE}
	    <div class="addCommentBlock">
		<div class="row">
		    <div class="" style="width: 100%;">
			<div class="commentTextArea ">
			    <textarea name="commentcontent" class="commentcontent form-control mention_listener" placeholder="{vtranslate('LBL_POST_YOUR_COMMENT_HERE', $MODULE_NAME)}" rows="{$COMMENT_TEXTAREA_DEFAULT_ROWS}"></textarea>
			</div>
		    </div>
		</div>
		<div class='row'>

			<div class="pull-left mt5">
			{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE_NAME) MODULE="ModComments"}
			{if in_array($MODULE_NAME, $PRIVATE_COMMENT_MODULES)}
				<div class="" style="margin: 7px 0;">
				    <label>
					<input type="checkbox" id="is_private" style="margin:2px 0px -2px 0px">&nbsp;&nbsp;{vtranslate('LBL_INTERNAL_COMMENT')}
				    </label>&nbsp;&nbsp;
				    <i class="fa fa-question-circle cursorPointer" data-toggle="tooltip" data-placement="top" data-original-title="{vtranslate('LBL_INTERNAL_COMMENT_INFO')}"></i>&nbsp;&nbsp;
				</div>
			{/if}
		    </div>

			<div class="pull-right mt5"style="">
			<div class="d-flex" style="text-align: right;">
			    <button class="btn btn-primary btn-sm detailViewSaveComment" type="button" data-mode="add">{vtranslate('LBL_POST', $MODULE_NAME)}</button>
			</div>
		    </div>
		   
		</div>
	    </div>
	{/if}
    </div>

    <hr>
    <div class="recentCommentsHeader row">
	<h4 class="display-inline-block col-lg-12 textOverflowEllipsis" title="{vtranslate('LBL_RECENT_COMMENTS', $MODULE_NAME)}">
	    {vtranslate('LBL_RECENT_COMMENTS', $MODULE_NAME)}
	</h4>
	<a class="show_all_comments" id="show_all_comments">Show All</a>
	{if $MODULE_NAME ne 'Leads'}
	    <div class="col-lg-12 commentHeader pull-right" style="margin-top:5px;text-align:right;padding-right:20px;">
		<div class="display-inline-block mr15">
		    <span class="">{vtranslate('LBL_ROLL_UP',$QUALIFIED_MODULE)} &nbsp;</span>
		    <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{vtranslate('LBL_ROLLUP_COMMENTS_INFO',$QUALIFIED_MODULE)}"></span>&nbsp;&nbsp;
		</div>
		<input type="checkbox" class="pull-right bootstrap-switch pull-right" id="rollupcomments" hascomments="1" startindex="{$STARTINDEX}" data-view="summary" rollupid="{$ROLLUPID}" rollup-status="{$ROLLUP_STATUS}" module="{$MODULE_NAME}" record="{$PARENT_RECORD}" checked data-on-color="success"/> 
	    </div> 
	{/if}
    </div>
    <div class="commentsBody">
	{if !empty($COMMENTS)}
	    <div class="recentCommentsBody">
		{assign var=COMMENTS_COUNT value=count($COMMENTS)}
		{foreach key=index item=COMMENT from=$COMMENTS}
		    {assign var=CREATOR_NAME value={decode_html($COMMENT->getCommentedByName())}}
		    <div class="commentDetails">
			<div class="commentDiv">
			<div class="singleComment" {if $COMMENT->get('is_private')}style="background: #fff9ea;"{/if}>
			    <input type="hidden" name='is_private' value="{$COMMENT->get('is_private')}">
			    {assign var=PARENT_COMMENT_MODEL value=$COMMENT->getParentCommentModel()}
			    {assign var=CHILD_COMMENTS_MODEL value=$COMMENT->getChildComments()}
	    		<div class="commentInfoHeader" data-commentid="{$COMMENT->getId()}" data-parentcommentid="{$COMMENT->get('parent_comments')}" data-relatedto = "{$COMMENT->get('related_to')}">
			    <div class="row">
				<div class="toggle-comments col-lg-12">
				<div class="media">
					<div class="media-left title col-lg-2 col-md-2 col-sm-2 p0 mt10">
					    <div class="commentImage commentInfoHeader" data-commentid="{$COMMENT->getId()}" data-parentcommentid="{$COMMENT->get('parent_comments')}" data-relatedto = "{$COMMENT->get('related_to')}">
						{assign var=IMAGE_PATH value=$COMMENT->getImagePath()}
						{if !empty($IMAGE_PATH)}
						    <img src="{$SITEURL}{$IMAGE_PATH}" width="50px" height="50px" align="left" style="border-radius:47px;">
						{else}
						    <div class="name"><span><strong> {$CREATOR_NAME|mb_substr:0:2|escape:"html"} </strong></span></div>
						{/if}
					    </div>
					</div>
					<div class="media-body col-lg-10 col-md-10 col-sm-9">
					    <div class="comment" style="line-height:1;">
						<div class="d-flex justify-content-between">
							<span class="creatorName">{$CREATOR_NAME}</span>&nbsp;&nbsp;
							<span class="commentTime text-muted cursorDefault">
								<small title="{Head_Util_Helper::formatDateTimeIntoDayString($COMMENT->getCommentedTime())}">{Head_Util_Helper::formatDateDiffInStrings($COMMENT->getCommentedTime())}</small>
							</span>
						</div>

						{if $ROLLUP_STATUS and ($COMMENT->get('module') ne $MODULE_NAME or $COMMENT->get('related_to') ne $PARENT_RECORD)}
						    {assign var=SINGULR_MODULE value='SINGLE_'|cat:$COMMENT->get('module')}
						    {assign var=ENTITY_NAME value=getEntityName($COMMENT->get('module'), array($COMMENT->get('related_to')))}
						    <span class="text-muted wordbreak display-inline-block">
							{vtranslate('LBL_ON','Head')}&nbsp;
							{vtranslate($SINGULR_MODULE,$COMMENT->get('module'))}&nbsp;
							<a href="index.php?module={$COMMENT->get('module')}&view=Detail&record={$COMMENT->get('related_to')}">
							{$ENTITY_NAME[$COMMENT->get('related_to')]}
							</a>
						    </span>&nbsp;&nbsp;
						{/if}

						<div class="d-block">
						    {if $COMMENT->get('module') eq 'Cases' and !$COMMENT->get('is_private')}
							{assign var=COMMENT_CONTENT value={decode_html($COMMENT->get('commentcontent'))}}
						    {else}
							{assign var=COMMENT_CONTENT value={nl2br($COMMENT->get('commentcontent'))}}
						    {/if}
						    {if $COMMENT_CONTENT}
							{assign var=DISPLAYNAME value={decode_html($COMMENT_CONTENT)}}
							<span class="commentInfoContent" style="display: block" data-fullComment="{$COMMENT_CONTENT|escape:"html"}" data-shortComment="{$DISPLAYNAME|mb_substr:0:200|escape:"html"}..." data-more='{vtranslate('LBL_SHOW_MORE',$MODULE)}' data-less='{vtranslate('LBL_SHOW',$MODULE)} {vtranslate('LBL_LESS',$MODULE)}'>
							{if $DISPLAYNAME|count_characters:true gt 200} 
							    {mb_substr(trim($DISPLAYNAME),0,200)}... 
							    <br><a class="pull-right toggleComment showMore"><small>{vtranslate('LBL_SHOW_MORE',$MODULE)}</small></a>
							{else} 
							    {$COMMENT_CONTENT}
							{/if}
							</span>
						    {/if}
						</div>
						{assign var="FILE_DETAILS" value=$COMMENT->getFileNameAndDownloadURL()}
						{foreach key=index item=FILE_DETAIL from=$FILE_DETAILS}
						    {assign var="FILE_NAME" value=$FILE_DETAIL['trimmedFileName']}
						    {if !empty($FILE_NAME)}
							<div class="commentAttachmentName">
							    <div class="filePreview clearfix">
								<span class="fa fa-paperclip cursorPointer" ></span>&nbsp;&nbsp;
								<a class="previewfile" onclick="Head_Detail_Js.previewFile(event,{$COMMENT->get('id')},{$FILE_DETAIL['attachmentId']});" data-filename="{$FILE_NAME}" href="javascript:void(0)" name="viewfile">
									<span title="{$FILE_DETAIL['rawFileName']}" style="line-height:1.5em;">{$FILE_NAME}</span>&nbsp
								</a>&nbsp;
								<a name="downloadfile" href="{$FILE_DETAIL['url']}">
								    <i title="{vtranslate('LBL_DOWNLOAD_FILE',$MODULE_NAME)}" class="hide fa fa-download alignMiddle" ></i>
								</a>
							    </div>
							</div>
						    {/if}
						{/foreach}
												&nbsp;
						<br>
						<div class="row commentEditStatus marginBottom10px" name="editStatus">
						    {assign var="REASON_TO_EDIT" value=$COMMENT->get('reasontoedit')}
						    <span class="col-lg-12 col-md-12 col-sm-12{if empty($REASON_TO_EDIT)} hide{/if}">
							<small> [{vtranslate('LBL_EDIT_REASON',$MODULE_NAME)}] : {nl2br($REASON_TO_EDIT)}</small>
						    </span>
						    {if $COMMENT->getCommentedTime() neq $COMMENT->getModifiedTime()}
							<span class="{if empty($REASON_TO_EDIT)}row{else} col-lg-12 col-md-12 col-sm-12{/if}">
							<p class="text-muted">
							<small><em>{vtranslate('LBL_MODIFIED',$MODULE_NAME)}</em></small>&nbsp;
																<small title="{Head_Util_Helper::formatDateTimeIntoDayString($COMMENT->getModifiedTime())}" class="commentModifiedTime">{Head_Util_Helper::formatDateDiffInStrings($COMMENT->getModifiedTime())}</small>
							</p>
							</span>
						    {/if}
						</div> 

						{* <div class="row marginBottom10px">
						    <div class="col-lg-12 col-md-12 col-sm-12">
							<p class="text-muted">
							    <small>
								<span name="editReason" class="wordbreak">{nl2br($REASON_TO_EDIT)}</span>
							    </small>
							</p>
						    </div>
						</div> *}

						<div class="commentActionsContainer" style="margin-top: 2px;">
						    <span>
							{if $PARENT_COMMENT_MODEL neq false or $CHILD_COMMENTS_MODEL neq null}
							    {* <a href="javascript:void(0);" class="cursorPointer detailViewThread">{vtranslate('LBL_VIEW_THREAD',$MODULE_NAME)}</a>&nbsp;&nbsp; *}
							    {* <a href="javascript:void(0);" class="cursorPointer viewThread">{vtranslate('LBL_VIEW_THREAD',$MODULE_NAME)}</a>&nbsp;&nbsp; *}
							{/if}

						    </span>
						    <span class="summarycommemntActionblock commentActions" >
							{if $IS_CREATABLE}
							    {if $PARENT_COMMENT_MODEL neq false or $CHILD_COMMENTS_MODEL neq null}
									{* <span>&nbsp;|&nbsp;</span> *}
								{/if}
								<a href="javascript:void(0);" class="cursorPointer replyComment feedback joforce-link fa fa-reply" title="{vtranslate('LBL_REPLY',$MODULE_NAME)}"></a>
							    {/if}
							    {if $CURRENTUSER->getId() eq $COMMENT->get('userid') && $IS_EDITABLE}
							 	{if $IS_CREATABLE}&nbsp;&nbsp;&nbsp;{/if}
								    <a href="javascript:void(0);" class="cursorPointer editComment feedback joforce-link fa fa-pencil" title="{vtranslate('LBL_EDIT',$MODULE_NAME)}"></a>
							    {/if}

								{assign var=CHILD_COMMENTS_COUNT value=$COMMENT->getChildCommentsCount()}
								{if $CHILD_COMMENTS_MODEL neq null and ($CHILDS_ROOT_PARENT_ID neq $PARENT_COMMENT_ID)}
								{if $COMMENTS_MODULE_MODEL->isPermitted('EditView')}&nbsp;&nbsp;&nbsp;{/if}
									<span class="viewThreadBlock" data-child-comments-count="{$CHILD_COMMENTS_COUNT}" style="display:none;">
									<a href="javascript:void(0)" class="cursorPointer viewThread">
										<span class="childCommentsCount">{$CHILD_COMMENTS_COUNT}</span>&nbsp;{if $CHILD_COMMENTS_COUNT eq 1}{vtranslate('LBL_REPLY',$MODULE_NAME)}{else}{vtranslate('LBL_REPLIES',$MODULE_NAME)}{/if}&nbsp;
									</a>
									</span>
									<span class="hideThreadBlock" data-child-comments-count="{$CHILD_COMMENTS_COUNT}">
									<a href="javascript:void(0)" class="cursorPointer hideThread">
										<span class="childCommentsCount">{$CHILD_COMMENTS_COUNT}</span>&nbsp;{if $CHILD_COMMENTS_COUNT eq 1}{vtranslate('LBL_REPLY',$MODULE_NAME)}{else}{vtranslate('LBL_REPLIES',$MODULE_NAME)}{/if}&nbsp;
									</a>
									</span>
								{elseif $CHILD_COMMENTS_MODEL neq null and ($CHILDS_ROOT_PARENT_ID eq $PARENT_COMMENT_ID)}
									{if $COMMENTS_MODULE_MODEL->isPermitted('EditView')}&nbsp;&nbsp;&nbsp;{/if}
									<span class="viewThreadBlock" data-child-comments-count="{$CHILD_COMMENTS_COUNT}">
									<a href="javascript:void(0)" class="cursorPointer viewThread">
									<span class="childCommentsCount">{$CHILD_COMMENTS_COUNT}</span>&nbsp;{if $CHILD_COMMENTS_COUNT eq 1}{vtranslate('LBL_REPLY',$MODULE_NAME)}{else}{vtranslate('LBL_REPLIES',$MODULE_NAME)}{/if}&nbsp;
									</a>
									</span>
									<span class="hideThreadBlock" data-child-comments-count="{$CHILD_COMMENTS_COUNT}" style="display:none;">
									<a href="javascript:void(0)" class="cursorPointer hideThread">
									<span class="childCommentsCount">{$CHILD_COMMENTS_COUNT}</span>&nbsp;{if $CHILD_COMMENTS_COUNT eq 1}{vtranslate('LBL_REPLY',$MODULE_NAME)}{else}{vtranslate('LBL_REPLIES',$MODULE_NAME)}{/if}&nbsp;
									</a>
									</span>
								{/if}
						    </span>
						</div>

					    </div>
					</div>
				    </div>
				</div>
			    </div>
				</div>
			</div>
			</div>
		    </div>
		    {if $index+1 neq $COMMENTS_COUNT}
			<hr style='visibility:hidden;'>
		    {/if}
		{/foreach}
	    </div>
	{else}
		{include file="NoComments.tpl"|@vtemplate_path}
	{/if}
	{if $PAGING_MODEL->isNextPageExists()}
	    <div class="row">
		<div class="textAlignCenter">
		<a href="javascript:void(0)" class="moreRecentComments">{vtranslate('LBL_SHOW_MORE',$MODULE_NAME)}</a>
		</div>
	    </div>
	{/if}
    </div>

    <div class="hide basicAddCommentBlock container-fluid">
	<div class="commentTextArea row">
	    <textarea name="commentcontent" class="commentcontent" placeholder="{vtranslate('LBL_ADD_YOUR_COMMENT_HERE', $MODULE_NAME)}" rows="{$COMMENT_TEXTAREA_DEFAULT_ROWS}"></textarea>
	</div>
	<div class="pull-right row">
	    {if in_array($MODULE_NAME, $PRIVATE_COMMENT_MODULES)}
		<div class="checkbox">
		<label>
		<input type="checkbox" id="is_private">&nbsp;&nbsp;{vtranslate('LBL_INTERNAL_COMMENT')}&nbsp;&nbsp;
		</label>
		</div>
	    {/if}
	    <button class="btn btn-primary btn-sm detailViewSaveComment" type="button" data-mode="add">{vtranslate('LBL_POST', $MODULE_NAME)}</button>
		<a href="javascript:void(0);" class="cursorPointer closeCommentBlock cancelLink btn btn-danger" type="">{vtranslate('LBL_CANCEL', $MODULE_NAME)}</a>
	</div>
    </div>

    <div class="hide basicEditCommentBlock container-fluid" style="">
	<div class="row commentArea" >
	    <input style="" type="text" name="reasonToEdit" placeholder="{vtranslate('LBL_REASON_FOR_CHANGING_COMMENT', $MODULE_NAME)}" class="input-block-level form-control"/>
	</div>
	<div class="row" style="">
	    <div class="commentTextArea">
		<textarea name="commentcontent" class="commentcontenthidden" placeholder="{vtranslate('LBL_ADD_YOUR_COMMENT_HERE', $MODULE_NAME)}" rows="{$COMMENT_TEXTAREA_DEFAULT_ROWS}"></textarea>
	    </div>
	</div>
	<input type="hidden" name="is_private">
	<div class="pull-right row">
	    <button class="btn btn-primary btn-sm detailViewSaveComment" type="button" data-mode="edit">{vtranslate('LBL_POST', $MODULE_NAME)}</button>
	<a href="javascript:void(0);" class="cursorPointer closeCommentBlock cancelLink btn btn-danger" type="">{vtranslate('LBL_CANCEL', $MODULE_NAME)}</a>
    </div>
</div>
</div>
{/strip}
