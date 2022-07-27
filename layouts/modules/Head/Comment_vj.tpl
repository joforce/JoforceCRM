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
			<div class="col-lg-1 col-md-1 col-sm-1 media-left title" id="154">
			    <div class="commentImage commentInfoHeader" data-commentid="154" data-parentcommentid="0" data-relatedto="78">
				<div class="name">
				    <span>
					<strong> vi </strong>
				    </span>
				</div>
			    </div>
			</div>
			<div class="commentActionsContainer col-lg-3 col-md-3 col-sm-3">
			    <span class="commentActions">
				<a href="javascript:void(0);" class="cursorPointer replyComment feedback joforce-link fa fa-reply" title="Reply"></a>&nbsp;&nbsp;&nbsp;
				<a href="javascript:void(0);" class="cursorPointer editComment feedback joforce-link fa fa-pencil" title="Edit"></a>
			    </span>
			</div>
			<div class="media-body col-lg-8 col-md-8 col-sm-8">
			    <a href="#" class="shot-comment-author">Vijay</a>
			    <div class="shot-comment-content">
				<div class="shot-comment-text">
				    <span class="commentInfoContent">Great work!</span>
				</div> 
				<div class="comment-time-and-actions">
				    <span class="time-ago commentTime text-muted cursorDefault">
					<small title="Sat, Feb 06, 2021 at 6:28 PM">16 minutes ago</small>
				    </span> 
				    <a href="#" class="like-comment">Like</a>
				    <a href="#" class="reply-comment">Reply</a>
				</div>
			    </div>
			</div>
		    </div>
		</div>
	    </div>
	</div>
    </div>
</div>
{/strip}

<style>
.shot-comment .shot-comment-content {
	}
	.shot-comment .shot-comment-avatar-and-author {
    
    padding-right: 32px;
	pointer-events: none;}
	.shot-comment .profile-avatar {
    position: absolute;
    top: 0;
    left: 0;
    pointer-events: auto;
}
.profile-avatar .avatar-link {
    display: inline-block;
    opacity: 1;
    -webkit-transition: opacity 0.2s ease;
	transition: opacity 0.2s ease;}
	.profile-avatar .avatar-image-wrapper.avatar-regular {
    width: 32px;
    height: 32px;
}
.lazy-img-wrapper .lazyloaded {
    opacity: 1;
    width: 100%;
    height: auto;
    -webkit-transition: opacity 0.2s ease;
    transition: opacity 0.2s ease;
}

.profile-avatar .avatar-image {
    border-radius: 50%;
    width: 100%;
    height: auto;
}
.lazy-img-wrapper img {
    display: block;
}
.shot-comment .comment-time-and-actions .time-ago {
    font-family: 'Haas Grot Text R Web', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 12px;
    font-weight: 400;
    line-height: 16px;
    color: #6e6d7a;
}
.shot-comment .comment-time-and-actions .like-comment, .shot-comment .comment-time-and-actions .reply-comment {
    font-family: 'Haas Grot Text R Web', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 12px;
    font-weight: 500;
    line-height: 16px;
    color: #6e6d7a;
    margin-left: 13px;
}
.shot-comment .comment-time-and-actions {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: center;
    align-items: center;
}
.comment-time-and-actions a{ padding-left: 10px; padding-right: 10px;}
</style>
