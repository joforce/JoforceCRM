{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{strip}
	<div class="commentDiv {if $COMMENT->get('is_private')}privateComment{/if}">
		<div class="singleComment">
			<input type="hidden" name="is_private" value="{$COMMENT->get('is_private')}">
			<div class="commentInfoHeader" data-commentid="{$COMMENT->getId()}" data-parentcommentid="{$COMMENT->get('parent_comments')}" data-relatedto = "{$COMMENT->get('related_to')}">

				{assign var=PARENT_COMMENT_MODEL value=$COMMENT->getParentCommentModel()}
				{assign var=CHILD_COMMENTS_MODEL value=$COMMENT->getChildComments()}

				<div class="row">
					<div class="col-lg-12">
						<div class="media">
							<div class="col-lg-1 col-md-1 col-sm-1 media-left title" id="{$COMMENT->getId()}">
								{assign var=CREATOR_NAME value=$COMMENT->getCommentedByName()}
								<div class="recordImage commentInfoHeader" data-commentid="{$COMMENT->getId()}" data-parentcommentid="{$COMMENT->get('parent_comments')}" data-relatedto = "{$COMMENT->get('related_to')}">
									{assign var=IMAGE_PATH value=$COMMENT->getImagePath()}
									{if !empty($IMAGE_PATH)}
										<img src="{$SITEURL}{$IMAGE_PATH}" width="100%" height="100%" align="left">
									{else}
										<div class="name"><span><strong> {$CREATOR_NAME|substr:0:2} </strong></span></div>
									{/if}
								</div>
							</div>
							<div class="media-body col-lg-11 col-md-4 col-sm-11">
								<div class="comment" style="line-height:1;">
									<span class="creatorName" style="color:blue">
										{$CREATOR_NAME}
									</span>&nbsp;&nbsp;
									<div class="commentActionsContainer">
										<span class="commentActions">
											{if $CHILDS_ROOT_PARENT_MODEL}
												{assign var=CHILDS_ROOT_PARENT_ID value=$CHILDS_ROOT_PARENT_MODEL->getId()}
											{/if}

											{if $COMMENTS_MODULE_MODEL->isPermitted('EditView')}
												{if $CHILDS_ROOT_PARENT_MODEL}
													{assign var=CHILDS_ROOT_PARENT_ID value=$CHILDS_ROOT_PARENT_MODEL->getId()}
												{/if}
												<a href="javascript:void(0);" class="cursorPointer replyComment feedback joforce-link fa fa-reply" title="{vtranslate('LBL_REPLY',$MODULE_NAME)}"></a>
												{if $CURRENTUSER->getId() eq $COMMENT->get('userid')}
													&nbsp;&nbsp;&nbsp;
													<a href="javascript:void(0);" class="cursorPointer editComment feedback joforce-link fa fa-pencil" title="{vtranslate('LBL_EDIT',$MODULE_NAME)}">
													</a>
												{/if}
											{/if}

											{assign var=CHILD_COMMENTS_COUNT value=$COMMENT->getChildCommentsCount()}
											{if $CHILD_COMMENTS_MODEL neq null and ($CHILDS_ROOT_PARENT_ID neq $PARENT_COMMENT_ID)}
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
											{elseif $CHILD_COMMENTS_MODEL neq null and ($CHILDS_ROOT_PARENT_ID eq $PARENT_COMMENT_ID)}
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
											{/if}
										</span>

										<span class="commentTime text-muted cursorDefault" style="padding:20px;">
											<small title="{Head_Util_Helper::formatDateTimeIntoDayString($COMMENT->getCommentedTime())}">{Head_Util_Helper::formatDateDiffInStrings($COMMENT->getCommentedTime())}</small>
										</span>
									</div>

									{if $ROLLUP_STATUS and $COMMENT->get('module') ne $MODULE_NAME}
										{assign var=SINGULR_MODULE value='SINGLE_'|cat:$COMMENT->get('module')}
										{assign var=ENTITY_NAME value=getEntityName($COMMENT->get('module'), array($COMMENT->get('related_to')))}
										<span class="text-muted">
											{vtranslate('LBL_ON','Head')}&nbsp;
											{vtranslate($SINGULR_MODULE, $COMMENT->get('module'))}&nbsp;
											<a href="index.php?module={$COMMENT->get('module')}&view=Detail&record={$COMMENT->get('related_to')}">
												{$ENTITY_NAME[$COMMENT->get('related_to')]}
											</a>
										</span>&nbsp;&nbsp;
									{/if}
									<div class="">
										<span class="commentInfoContent">
											{nl2br($COMMENT->get('commentcontent'))}
										</span>
									</div>
									<br>
									<br>
									{assign var="REASON_TO_EDIT" value=$COMMENT->get('reasontoedit')}
									<div class="editedStatus" name="editStatus">
										<div class="{if empty($REASON_TO_EDIT)}hide{/if} editReason">
											<p class="text-muted"><small>[ {vtranslate('LBL_EDIT_REASON',$MODULE_NAME)} ] : <span name="editReason" class="textOverflowEllipsis">{nl2br($REASON_TO_EDIT)}</span></small></p>
										</div>
										{if $COMMENT->getCommentedTime() neq $COMMENT->getModifiedTime()}
											<p class="text-muted cursorDefault"><small><em>{vtranslate('LBL_MODIFIED',$MODULE_NAME)}</em></small>&nbsp;<small title="{Head_Util_Helper::formatDateTimeIntoDayString($COMMENT->getModifiedTime())}" class="commentModifiedTime">{Head_Util_Helper::formatDateDiffInStrings($COMMENT->getModifiedTime())}</small></p>
										{/if}
									</div>
									<div style="margin-top:5px;">
										{assign var="FILE_DETAILS" value=$COMMENT->getFileNameAndDownloadURL()}
										{foreach key=index item=FILE_DETAIL from=$FILE_DETAILS}
											{assign var="FILE_NAME" value=$FILE_DETAIL['trimmedFileName']}
											{if !empty($FILE_NAME)}
												<div class="row-fluid">
													<div class="span11 commentAttachmentName">
														<span class="filePreview">
															<a onclick="Head_Detail_Js.previewFile(event,{$COMMENT->get('id')},{$FILE_DETAIL['attachmentId']});" data-filename="{$FILE_NAME}" href="javascript:void(0)" name="viewfile">
																<span title="{$FILE_DETAIL['rawFileName']}" style="line-height:1.5em;">{$FILE_NAME}</span>&nbsp
															</a>&nbsp;
															<a name="downloadfile" href="{$FILE_DETAIL['url']}">
																<i title="{vtranslate('LBL_DOWNLOAD_FILE',$MODULE_NAME)}" class="pull-left hide fa fa-download alignMiddle"></i>
															</a>
														</span>
													</div>
												</div>
											{/if}
										{/foreach}
									</div>
								</div>
							</div>
							<hr>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
{/strip}
