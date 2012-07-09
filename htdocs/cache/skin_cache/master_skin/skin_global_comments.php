<?php
/**
 * Master skin file
 * Written: Mon, 09 Jul 2012 18:16:42 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: comment
//===========================================================================
function comment($r, $parent, $settings) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>
// Adjust author name as needed
if( empty($r['author']['member_id']) && !empty($r['author']['comment_author_name']) )
{
	$r['author']['members_display_name'] = $r['author']['comment_author_name'];
}
</php>
<if test="canEditNoPost:|:!$this->hasEditJs AND !$parent['_canComment'] AND $r['comment']['_canEdit']">
	{parse template="getEditJs" group="global_comments" params=""}
</if>
<a id='comment_{$r['comment']['comment_id']}'></a>
<div data-commentid='{$r['comment']['comment_id']}' class='ipsComment clearfix <if test="commentQueued:|:!$r['comment']['comment_approved']">moderated</if>' id='comment_id_{$r['comment']['comment_id']}'>
	<if test="commentignored:|:$r['comment']['_ignored'] || $r['comment']['_repignored']">
		<div class='post_ignore'>
			<if test="userIgnoredLang:|:$r['comment']['_repignored'] == 1">{$this->lang->words['comment_ignored_rep']}<else />{$this->lang->words['ignored_comments_not']}</if> <if test="hasauthormemid:|:$r['author']['member_id']"><a href='{parse url="showuser={$r['author']['member_id']}" base="public"}'></if>{$r['author']['members_display_name']}<if test="hasauthormidclose:|:$r['author']['member_id']"></a></if>. <a href='#entry{$r['comment']['comment_id']}' title='{$this->lang->words['ignored_comments_not']}' style='display: none' id='unhide_post_{$r['comment']['comment_id']}'>{$this->lang->words['rep_view_anyway']}</a>
		</div>
	</if>
	<div class='ipsComment_author'>
		<if test="linkPhotoStart:|:$r['author']['member_id']"><a class='ipsUserPhotoLink' href='{parse url="showuser={$r['author']['member_id']}" template="showuser" seotitle="{$r['author']['members_seo_name']}" base="public"}'></if><img src='{$r['author']['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' /><if test="linkPhotoEnd:|:$r['author']['member_id']"></a></if><br />{parse template="userHoverCard" group="global" params="$r['author']"}<br />
		<span class='post_id'>
			<a rel='bookmark' class='desc lighter ipsType_smaller' href='{parse url="{$settings['baseUrl']}&amp;do=findComment&amp;comment_id={$r['comment']['comment_id']}" base="public"}' title='{$this->lang->words['comment_permalink']}'>{parse date="$r['comment']['comment_date']" format="short"}</a>
		</span>
		<if test="authorwarn:|:$r['author']['show_warn']">
			<br />
			<if test="$options['wl_id']">
				<img src='{$this->settings['img_url']}/warn.png' class='clickable' onclick='warningPopup( this, {$options['wl_id']} )' title='{$this->lang->words['warnings_issued']}' />
			</if>
			<a class='desc lighter ipsType_smaller blend_links' href='{parse url="app=members&amp;module=profile&amp;section=warnings&amp;member={$r['author']['member_id']}&amp;from_app={$settings['thisApp']}&amp;from_id1={$settings['fromApp']}&amp;from_id2={$parent['parent_id']}-{$r['comment']['comment_id']}" base="public"}' id='warn_link_{$r['comment']['comment_id']}_{$r['author']['member_id']}' title='{$this->lang->words['warn_view_history']}'>{parse expression="sprintf( $this->lang->words['warn_status'], $r['author']['warn_level'] )"}</a>
		</if>
	</div>	
	<div id='comment_{$r['comment']['comment_id']}' class='ipsComment_comment'>
		<div class='comment_content'>{$r['comment']['comment_text']}</div>
		
		<ul class='ipsComment_controls ipsList_inline ipsType_smaller'>
			<if test="moderateCheckbox:|:$parent['_canModerate']">
				<li class='right'>&nbsp;&nbsp;<input class="ipsComment_mod" type='checkbox' name='' id='mod_comment_id_{$r['comment']['comment_id']}' data-status='{$r['comment']['comment_approved']}' /></li>
			</if>
			<if test="enableRep:|:$settings['enableRep']">
				<li class='right'>
					{parse template="repButtons" group="global_other" params="$r['author'], array_merge( array( 'primaryId' => $r['comment']['comment_id'], 'domLikeStripId' => 'like_post_' . $r['comment']['comment_id'], 'domCountId' => 'rep_comment_' . $r['comment']['comment_id'], 'app' => $settings['repApp'] ? $settings['repApp'] : $settings['thisApp'], 'type' => $settings['repType'], 'likeFormatted' => $r['comment']['like']['formatted'] ), $r['comment'] )"}
				</li>
			</if>
			<if test="canReply:|:$r['comment']['_canReply']">
				<li><a href='{$this->settings['this_url']}<if test="hasAppend:|:! strstr( $this->settings['this_url'], '_rcid')"><if test="hasQ:|:strstr($this->settings['this_url'], '?')">&amp;<else />?</if>_rcid={$r['comment']['comment_id']}</if>#fastreply' title="{$this->lang->words['reply_to_comment']}" id='reply_comment_{$r['comment']['comment_id']}' class='ipsButton_secondary reply_comment'>{$this->lang->words['comment_reply']}</a></li>
			</if>
			<if test="canEdit:|:$r['comment']['_canEdit']">
				<li>
					<a href="{parse url="{$settings['baseUrl']}&amp;do=showEdit&amp;comment_id={$r['comment']['comment_id']}" base="public"}" id='edit_comment_{$r['comment']['comment_id']}' class='edit_comment'>{$this->lang->words['edit_link']}</a>
				</li>
			</if>
			<if test="canHide:|:$r['comment']['comment_approved'] == 1 and $r['comment']['_canHide']">
				<li><a href='{parse url="{$settings['baseUrl']}&amp;do=hide&amp;comment_id={$r['comment']['comment_id']}&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['hide_link']}' id='hide_comment_{$r['comment']['comment_id']}' class='hide_comment'>{$this->lang->words['hide_link']}</a></li>
			</if>
			<if test="canApprove:|:$r['comment']['comment_approved'] == 0 and $r['comment']['_canApprove']">
				<li><a href='{parse url="{$settings['baseUrl']}&amp;do=approve&amp;comment_id={$r['comment']['comment_id']}&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['approve_link']}'>{$this->lang->words['approve_link']}</a></li>
			</if>
			<if test="canDelete:|:$r['comment']['_canDelete']">
				<li>
					<a href='{parse url="{$settings['baseUrl']}&amp;do=delete&amp;comment_id={$r['comment']['comment_id']}&amp;auth_key={$this->member->form_hash}" base="public"}' id='delete_comment_{$r['comment']['comment_id']}' class='delete_comment'  title='{$this->lang->words['delete_comment']}'>{$this->lang->words['delete_link']}</a>
				</li>
			</if>
			<if test="hasReport:|:$r['comment']['urls-report']">
				<li>
					<a href="{parse url="{$r['comment']['urls-report']}&amp;st={$this->request['st']}" base="public"}">{$this->lang->words['report_link']}</a>
				</li>
			</if>
		</ul>
		
	</div>
</div>
<if test="commentinitignored:|:$r['comment']['_ignored'] || $r['comment']['_repignored']">
	<script type='text/javascript'>
		ipb.comments.setCommentHidden( {$r['comment']['comment_id']} );
	</script>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: commentHidden
//===========================================================================
function commentHidden($r, $parent, $settings) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>
// Adjust author name as needed
if( empty($r['author']['member_id']) && !empty($r['author']['comment_author_name']) )
{
	$r['author']['members_display_name'] = $r['author']['comment_author_name'];
}
</php>
<if test="canEditNoPost:|:!$this->hasEditJs AND !$parent['_canComment'] AND $r['comment']['_canEdit']">
	{parse template="getEditJs" group="global_comments" params=""}
</if>
<a id='comment_{$r['comment']['comment_id']}'></a>
<div data-commentid='{$r['comment']['comment_id']}' class='ipsComment clearfix moderated' id='comment_id_{$r['comment']['comment_id']}'>
	<div class='ipsComment_author'>
		{parse template="userHoverCard" group="global" params="$r['author']"}<br />
	</div>	
	<div id='comment_{$r['comment']['comment_id']}' class='ipsComment_comment'>
		<div class='comment_content' id='hidden_text_{$r['comment']['comment_id']}'>
			{$this->lang->words['post_deleted_by']} <a href='{parse url="showuser={$r['sD']['sdl_obj_member_id']}" base="public" template="showuser" seotitle="{$r['sD']['member']['members_seo_name']}"}'>{$r['sD']['member']['members_display_name']}</a> {$this->lang->words['on']} {parse date="$r['sD']['sdl_obj_date']" format="long"}.
			<p class='desc'><if test="$r['sD']['sdl_obj_reason']">{$r['sD']['sdl_obj_reason']}<else />{$this->lang->words['no_reason_given']}</if></p>
		</div>
		<div class='comment_content' id='comment_content_{$r['comment']['comment_id']}' style='display:none'>
			{$r['comment']['comment_text']}
		</div>
		<ul class='ipsComment_controls ipsList_inline ipsType_smaller'>
			<if test="moderateCheckbox:|:$parent['_canModerate']">
				<li class='right'>&nbsp;&nbsp;<input class="ipsComment_mod" type='checkbox' name='' id='mod_comment_id_{$r['comment']['comment_id']}' data-status='{$r['comment']['comment_approved']}' /></li>
			</if>
			<li>
				<span class='clickable' onclick="$('hidden_text_{$r['comment']['comment_id']}').toggle();$('comment_content_{$r['comment']['comment_id']}').toggle();">{$this->lang->words['comment_hidden_content']}</span>
			</li>
			<if test="canUnhide:|:$r['comment']['_canUnhide']">
				<li><a href='{parse url="{$settings['baseUrl']}&amp;do=unhide&amp;comment_id={$r['comment']['comment_id']}&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['unhide_link']}'>{$this->lang->words['unhide_link']}</a></li>
			</if>
			<if test="canDelete:|:$r['comment']['_canDelete']">
				<li>
					<a href='{parse url="{$settings['baseUrl']}&amp;do=delete&amp;comment_id={$r['comment']['comment_id']}&amp;auth_key={$this->member->form_hash}" base="public"}' id='delete_comment_{$r['comment']['comment_id']}' class='delete_comment' title='{$this->lang->words['delete_comment']}'>{$this->lang->words['delete_link']}</a>
				</li>
			</if>
		</ul>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: commentsList
//===========================================================================
function commentsList($comments, $settings, $pages, $parent, $preReply='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type='text/javascript'>
	ipb.templates['comment_moderation'] = new Template("<div id='comment_moderate_box' class='ipsFloatingAction' style='display: none'><span class='desc'>{$this->lang->words['comment_action_count']} </span><select name='modOptions' class='input_select' id='commentModAction'><option value='approve'>{$this->lang->words['approve_x_comments']}</option><option value='hide'>{$this->lang->words['hide_x_comments']}</option><option value='unhide'>{$this->lang->words['unhide_x_comments']}</option><option value='delete'>{$this->lang->words['delete_x_comments']}</option></select>&nbsp;&nbsp;<input type='button' class='input_submit' id='submitModAction' value='{$this->lang->words['comments_act_go']}' /></div>");
	
	ipb.templates['comment_delete'] = new Template("<h3>{$this->lang->words['comm_confirm_delete']}</h3><div class='ipsPad ipsForm_center desc'>{$this->lang->words['comm_confirm_delete_desc']}<br /><br /><input type='button' class='input_submit' id='delPush' onclick='ipb.comments.deleteIt(event)' value='{$this->lang->words['del_comm_now']}' />");
	
	ipb.templates['comment_hide'] = new Template("<form action='#{url}' method='post'><h3>{$this->lang->words['comm_confirm_hide']}</h3><div class='ipsPad ipsForm_center desc'>{$this->lang->words['comm_confirm_hide_desc']}<br /><br /><input type='text' name='reason' id='hidePop_reason' value='' class='input_text' style='width: 60%' /> <input type='submit' class='input_submit' value='{$this->lang->words['comm_confirm_hide']}' /></form>");
</script>
{parse js_module="comments"}
<a id='commentsStart'></a>
<if test="comments_top:|:$pages">
	<div class='topic_controls'>
		{$pages}
	</div>
</if>
<div class='ipsComment_wrap' id='comment_wrap'>
	<foreach loop="comments:$comments as $id => $r">
		<if test="$r['comment']['comment_approved'] == -1">
			{parse template="commentHidden" group="global_comments" params="$r, $parent, $settings"}
		<else />
			{parse template="comment" group="global_comments" params="$r, $parent, $settings"}
		</if>
	</foreach>
</div>
<if test="comments_bottom:|:$pages">
	<div class='topic_controls'>
		{$pages}
	</div>
</if>
<if test="allow_comments:|:$parent['_canComment']">
	<br />
	<a id="fastreply"></a>
	<div id='fast_reply' class='ipsComment_reply row2 ipsPad'>
		<form action="{$settings['formUrl']}" method="post">
			<input type="hidden" name="auth_key" value="{$this->member->form_hash}" />
			<input type="hidden" name="fromApp" value="{$settings['fromApp']}" />
			<input type="hidden" name="app" value="{$settings['formApp']}" />
			<input type="hidden" name="module" value="{$settings['formModule']}" />
			<input type="hidden" name="section" value="{$settings['formSection']}" />	
			<input type="hidden" name="do" value="add" />
			<input type="hidden" name="parentId" value="{$parent['parent_id']}" />
			<input type="hidden" name="fast_reply_used" value="1" />
			<div class='ipsComment_reply_user'>
				<img src='{$this->memberData['pp_small_photo']}' alt='{$this->lang->words['your_photo']}' class='ipsUserPhoto ipsUserPhoto_medium' />
			</div>
			<div id='commentReply' class='ipsComment_comment'>
				<if test="guestName:|:!$this->memberData['member_id']">
					<div id='commentName'>
						<label for='comment_name'>{$this->lang->words['yourname_captcha']}</label> <input type='text' id='comment_name' name='comment_name' class='input_text' />
						{$this->lang->words['or']} <a href='{parse url="app=core&amp;module=global&amp;section=login" base="public"}' title='{$this->lang->words['sign_in']}'>{$this->lang->words['sign_in']}</a>
					</div>
					<if test="hasCaptcha:|:$settings['captcha']">
						<div id='commentCaptcha' style='display:none;'>
							{$settings['captcha']}
						</div>
					</if>
				</if>
				{parse editor="Post" content="$preReply" options="array( 'type' => 'mini', 'minimize' => 1, 'autoSaveKey' => $settings['autoSaveKey'], 'warnInfo' => 'fastReply', 'editorName' => 'commentFastReply' )"}
				<br />
				<if test="!$this->memberData['unacknowledged_warnings'] and !$this->memberData['restrict_post']">
					<div id='commentButtons'>
						<input type='submit' name="submit" class='input_submit' id='commentPost' value='{$this->lang->words['comment_button_post']}' tabindex='0' accesskey='s' />
					</div>
				</if>
			</div>
		</form>
	</div>
</if>
<script type='text/javascript'>
	ipb.global.post_width			= 500;
	document.observe("dom:loaded", function(){
		ipb.comments.parentId = {parse expression="intval( $parent['parent_id'] )"};
		ipb.comments.setData( {parse expression="json_encode( $settings )"} );
	});
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: form
//===========================================================================
function form($comment, $parent, $editor="", $settings, $errors="", $do='saveEdit') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="comment_errors:|:$errors">
	<p class='message error'>{$errors}</p>
</if>
<div class='post_form'>
	<form method="post" action="{$settings['formAction']}" name="REPLIER">
		<input type="hidden" name="auth_key" value="{$this->member->form_hash}" />
		<input type="hidden" name="fromApp" value="{$settings['fromApp']}" />
		<input type="hidden" name="app" value="{$settings['formApp']}" />
		<input type="hidden" name="module" value="{$settings['formModule']}" />
		<input type="hidden" name="section" value="{$settings['formSection']}" />	
		<input type="hidden" name="do" value="saveEdit" />
		<input type="hidden" name="parentId" value="{$parent['parent_id']}" />
		<input type="hidden" name="comment_id" value="{$comment['comment']['comment_id']}" />
		<input type="hidden" name="auth_key" value="{$this->member->form_hash}" />
		<input type="hidden" name="modcp" value="{$this->request['modcp']}" />
		
		<h3 class='maintitle'>
		<if test="isEditing:|:$do == 'saveEdit'">
			{$this->lang->words['edit_comment']} {$parent['parent_title']}
		</if>
		</h3>
		<div class='generic_bar'></div>
		
		<if test="guest_captcha:|:!$this->memberData['member_id'] AND $this->settings['guest_captcha'] AND $this->settings['bot_antispam_type'] != 'none'">
			<fieldset>
				<ul>
					<li class='field'>
						<label for=''>{$this->lang->words['guest_captcha']}</label>
					</li>
				</ul>
			</fieldset>
		</if>
		
		<fieldset>
			{$editor}
		</fieldset>
		<fieldset class='submit'>
			<input type="submit" name="submit" value="{$this->lang->words['comment_save']}" tabindex="0" class="input_submit" accesskey="s" /> {$this->lang->words['or']} <a href='{parse url="{$settings['baseUrl']}&amp;do=findComment&amp;comment_id={$comment['comment']['comment_id']}" base="public"}' class='cancel' title='{$this->lang->words['cancel']}'>{$this->lang->words['cancel']}</a>
		</fieldset>
	</form>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: getEditJs
//===========================================================================
function getEditJs() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>
$pluginEditorHook = IPSLib::loadLibrary( IPS_ROOT_PATH . 'sources/classes/editor/composite.php', 'classes_editor_composite' );
$editor = new $pluginEditorHook();
$smilies	= $editor->fetchEmoticons( 20 );
$bypass		= $editor->getRteEnabled() ? 0 : 1;
$this->hasEditJs = true;
</php>
{parse template="editorLoadJs" group="editors" params="array( 'bypassCKEditor' => $bypass, 'smilies' => $smilies )"}
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>