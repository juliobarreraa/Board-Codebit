<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: messengerDisabled
//===========================================================================
function messengerDisabled() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['pm_disabled_title']}</h3>
<div class='ipsPad'>
	{$this->lang->words['your_pm_is_disabled']}
	<if test="notByAdmin:|:$this->memberData['members_disable_pm'] != 2">
	<p class='ipsForm_center'>
		<br />
		<a href="{parse url="module=messaging&amp;section=view&amp;do=enableMessenger&amp;authKey={$this->member->form_hash}" base="publicWithApp"}" class='ipsButton_secondary'>{$this->lang->words['pm_disabled_reactivate']}</a>
		<a href='{parse url="act=idx" template="act=idx" seotitle="true"  base="public"}' class='ipsButton_secondary'>{$this->lang->words['go_board_index']}</a>
		</p>
	</if>
 </div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: messengerTemplate
//===========================================================================
function messengerTemplate($html, $jumpmenu, $dirData, $totalData=array(), $topicParticipants=array(), $inlineError='', $deletedTopic=0) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="messenger"}
<script type="text/javascript">
	ipb.messenger.disabled  = {parse expression="intval($this->memberData['members_disable_pm'])"};
</script>
<if test="PMDisabled:|:$this->memberData['members_disable_pm']">
<noscript>
	{parse template="messengerDisabled" group="messaging"}
</noscript>
</if>
<div id='messenger_utilities' class='left'>
	<!-- Show topic participants -->
	<if test="hasParticipants:|:is_array( $topicParticipants ) and count( $topicParticipants )">
		<div class='ipsSideBlock' id='participants'>
			<h3 class='bar'>{$this->lang->words['participants']}</h3>
			<ul id='participants_list' class='ipsList_withminiphoto'>
				<foreach loop="participants:$topicParticipants as $memberID => $memberData">
					<li class='clearfix'>
						<if test="isMemberPartOpen:|:$memberData['member_id']"><a href='{parse url="showuser={$memberData['member_id']}" seotitle="{$memberData['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'></if><img src='{$memberData['pp_small_photo']}' alt='{$this->lang->words['photo']}' class='ipsUserPhoto ipsUserPhoto_mini<if test="isMemberPartFloat:|:!$memberData['member_id']"> left</if>' /><if test="isMemberPartClose:|:$memberData['member_id']"></a></if>
						<p class='list_content'>
							<if test="userIsActive:|:$memberData['map_user_active']">
								<if test="userIsStarter:|:$memberData['map_is_starter']">
									<span class='name starter'>{parse template="userHoverCard" group="global" params="$memberData"}</span>
								<else />
									<span class='name'>{parse template="userHoverCard" group="global" params="$memberData"}</span>
								</if>
								<br />
								<span class='ipsType_smaller desc'>
									<if test="messageIsDeleted:|:$memberData['_topicDeleted']">
										<em>{$this->lang->words['topic_deleted']}</em>
									<else />
										{$this->lang->words['last_read']}
										<if test="lastReadTime:|:$memberData['map_read_time']">
											{parse date="$memberData['map_read_time']" format="short"}
										<else />
											<em>{$this->lang->words['not_yet_read']}</em>
										</if>
									</if>
									<if test="notification:|:$memberData['map_ignore_notification']">
										<span class='ipsBadge ipsBadge_lightgrey right'>{$this->lang->words['msg_no_notify']}</span>
									</if>						
								</span>
								<if test="blockUserLink:|:! $memberData['map_is_starter'] AND $memberData['_canBeBlocked'] AND ($topicParticipants[ $this->memberData['member_id'] ]['map_is_starter'] OR $this->memberData['g_is_supmod']) AND ( $memberData['map_user_id'] != $this->memberData['member_id'] ) AND !$memberData['_topicDeleted']">
									<br /><a href="{parse url="module=messaging&amp;section=view&amp;do=blockParticipant&amp;topicID={$this->request['topicID']}&amp;memberID={$memberData['map_user_id']}&amp;authKey={$this->member->form_hash}" base="publicWithApp"}" title='{$this->lang->words['block_this_user']}' class='cancel'>{$this->lang->words['block']}</a>
								</if>
							<else />
								<if test="userIsBanned:|:$memberData['map_user_banned']">
									<span class='name blocked'><a href='{parse url="showuser={$memberData['member_id']}" seotitle="{$memberData['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}'><strong>{$memberData['members_display_name_short']}</strong></a></span>
									<br />
									<span class='desc'>{$this->lang->words['user_is_blocked']}</span>
									<br />
									<if test="unbanUserLink:|:$memberData['_canBeBlocked'] AND ($topicParticipants[ $this->memberData['member_id'] ]['map_is_starter'] OR $this->memberData['g_is_supmod'])">
										<a href="{parse url="module=messaging&amp;section=view&amp;do=unblockParticipant&amp;topicID={$this->request['topicID']}&amp;memberID={$memberData['map_user_id']}&amp;authKey={$this->member->form_hash}" base="publicWithApp"}" title='{$this->lang->words['unblock_this_user']}' class='cancel'>{$this->lang->words['unblock']}</a>
									</if>
								<else />
									<span class='name left_convo'><a href='{parse url="showuser={$memberData['member_id']}" seotitle="{$memberData['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}'><strong>{$memberData['members_display_name_short']}</strong></a></span>
									<br />
									<span class='desc'>
										<if test="topicUnavailable:|:$memberData['_topicDeleted']">
										<em>{$this->lang->words['topic_deleted']}</em>
										<else />
											<if test="systemMessage:|:$memberData['map_is_system']">
												{$this->lang->words['is_unable_part']}
											<else />
												{$this->lang->words['has_left_convo']}
											</if>
										</if>
									</span>
								</if>
							</if>
						</p>
					</li>
				</foreach>					
			</ul>
			<ul class='post_controls'>
				<li>
					<a href='{parse url="module=messaging&amp;section=view&amp;do=toggleNotifications&amp;topicID={$this->request['topicID']}&amp;authKey={$this->member->form_hash}" base="publicWithApp"}' title='{$this->lang->words['toggle_noti_title']}'>
					<if test="changeNotifications:|:$topicParticipants[ $this->memberData['member_id'] ]['map_ignore_notification']">
						{$this->lang->words['turn_noti_on']}
					<else />
						{$this->lang->words['turn_noti_off']}
					</if>
					</a>
				</li>
			</ul>
		</div>
		<if test="inviteMoreParticipants:|:$this->memberData['g_max_mass_pm'] && count( $topicParticipants ) < $this->memberData['g_max_mass_pm'] && ! $deletedTopic">
			<div id='invite_more' class='ipsSideBlock'>
				<h3>{$this->lang->words['invite_part']}</h3>
				<div id='invite_more_dialogue'>
					<form method='post' action='{parse url="app=members&amp;module=messaging&amp;section=view&amp;do=addParticipants" base="public"}'>
					<input type='hidden' name='authKey' value='{$this->member->form_hash}' />
					<input type='hidden' name='topicID' value='{$this->request['topicID']}' />
					<input type='hidden' name='st' value='{$this->request['st']}' />
					<ul><li><label for='invite_more_autocomplete'>{$this->lang->words['enter_member_names']}</label> 
					<input type='text' class='input_text' name='inviteNames' id='invite_more_autocomplete' value='{$this->request['inviteNames']}' size='38' /> 
					<br /><span class='desc'>[x]{$this->lang->words['separated_with_commas']}</span> 
					</li></ul><fieldset class='submit'><input type='submit' class='input_submit' value='{$this->lang->words['part_add']}' />  
					{$this->lang->words['or']} <a href='#' id='invite_more_cancel' class='cancel' title='{$this->lang->words['cancel']}'>{$this->lang->words['cancel']}</a></fieldset></form>
				</div>
				<div id='invite_more_default'>
					<if test="unlimitedInvites:|:$this->memberData['g_max_mass_pm'] == 0">
						<p class='desc'>{$this->lang->words['can_invite_unlimited']}</p>
						<script type='text/javascript'>
							ipb.messenger.invitesLeft = parseInt( 0 );
							ipb.messenger.nameText = ipb.lang['enter_unlimited_names'];
						</script>
					<else />
						<p class='desc'>{$this->lang->words['may_invite_upto']} <strong>{parse expression="( $this->memberData['g_max_mass_pm'] - count( $topicParticipants ) )"}</strong> {$this->lang->words['more_members']}</p>
						<script type='text/javascript'>
							ipb.messenger.invitesLeft = parseInt( {parse expression="( $this->memberData['g_max_mass_pm'] - count( $topicParticipants ) )"} );
							ipb.messenger.nameText = ipb.lang['enter_x_names'].gsub(/\[x\]/, ipb.messenger.invitesLeft);
						</script>
					</if>
					<ul class='post_controls'>
						<li id='add_participants'><a href='#' title='{$this->lang->words['add_participants']}'>{$this->lang->words['part_add']}</a></li>
					</ul>
				</div>
			</div>
		</if>
	</if>
	<div id='folder_list' class='ipsSideBlock'>
		<h3>{$this->lang->words['folders']}</h3>
		<ol id='folders'>
			<if test="myDirectories:|:count($dirData)">
				<foreach loop="dirs:$dirData as $id => $data">
					<if test="protectedFolder:|:$data['protected']">
						<li class='folder protected' id='f_{$id}'>{parse replacement="folder_{$id}"}
					<else />
						<li class='folder' id='f_{$id}'>{parse replacement="folder_generic"}
					</if>
					<a href="{parse url="module=messaging&amp;section=view&amp;do=showFolder&amp;folderID={$id}" base="publicWithApp"}" title="{$this->lang->words['go_to_folder']}" rel="folder_name">{$data['real']}</a>
					<span class='total rounded'>
						<if test="allFolder:|:$id == 'all'">
							{parse expression="intval($this->memberData['msg_count_total'])"}
						<else />
							{parse expression="intval($data['count'])"}
						</if>
					</span>
					<if test="unprotectedFolder:|:! $data['protected']">
						<span class='edit_folders' style='display: none'><a href='#' id='delete_{$id}' class='f_delete' title="{$this->lang->words['delete_folder_title']}">{parse replacement="folder_delete"}</a> <a href='#' id='empty_{$id}' class='f_empty' title="{$this->lang->words['empty_folder_title']}">{parse replacement="folder_empty"}</a></span></li>
					<else />
						<span class='edit_folders' style='display: none'><a href='#' id='empty_{$id}' class='f_empty' title="{$this->lang->words['empty_folder_title']}">{parse replacement="folder_empty"}</a></span></li>
					</if>
				</foreach>
			</if>
		</ol>
		<div class='clearfix post_controls'>
			<ul class='post_controls'>
				<li id='add_folder'><a href='#' title='{$this->lang->words['add_folder']}'>{$this->lang->words['folder_add']}</a></li>
				<li id='edit_folders'><a href='#' title='{$this->lang->words['edit_folders']}'>{$this->lang->words['folders_edit']}</a></li>
			</ul>
		</div>
		<script type='text/javascript'>
		//<![CDATA[
			ipb.messenger.folderTemplate = "<li class='folder' id='f_[id]'>{parse replacement="folder_generic"} <a href='{parse url="module=messaging&amp;section=view&amp;do=showFolder&amp;folderID=[id]" base="publicWithApp"}' title='{$this->lang->words['go_to_folder']}' rel='folder_name'>[name]</a> <span class='total rounded'>[total]</span><span class='edit_folders' style='display: none'><a href='#' id='delete_[id]' class='f_delete' title='{$this->lang->words['delete_folder_title']}'>{parse replacement="folder_delete"}</a> <a href='#' id='empty_[id]' class='f_empty' title='{$this->lang->words['empty_folder_title']}'>{parse replacement="folder_empty"}</a></span></li>";
		//]]>
		</script>
	</div>
	<if test="storageBar:|:$this->memberData['g_max_messages'] > 0">
		<div id='space_allowance' class='ipsSideBlock'>
			<h3>{$this->lang->words['storage']}</h3>
			<p>{$this->lang->words['your_messenger_storage']}</p>
			<p class='progress_bar' title='{parse expression="sprintf( $this->lang->words['pmpc_full_string'], $totalData['full_percent'] )"}' <if test="almostFull:|:$totalData['full_percent'] > 80">class='limit'</if>>
				<span style='width: {$totalData['full_percent']}%'>{$totalData['full_percent']}%</span>
			</p>
			<p>
				<span class='desc'>{$totalData['full_percent']}% {$this->lang->words['of_your_quota']} ({$this->memberData['g_max_messages']} {$this->lang->words['messages']})</span>
			</p>
		</div>
	</if>
	<div id='message_search' class='ipsSideBlock'>
		<h3>{$this->lang->words['search_messages']}</h3>
		<form action='{$this->settings['base_url']}app=members&amp;module=messaging&amp;section=search' method='post'>
			<fieldset>
				<input type='text' name='searchFor' class='input_text' size='15' style='width: 60%' /> <input type='submit' class='input_submit' value='{$this->lang->words['jmp_go']}' />
			</fieldset>
		</form>
	</div>
	<br />
	
	<a class='ipsButton_secondary cancel' href="{parse url="module=messaging&amp;section=view&amp;do=disableMessenger&amp;authKey={$this->member->form_hash}" base="publicWithApp"}"'>{$this->lang->words['disable_messenger']}</a>
	<!--<ul class='topic_buttons'>
		<li class='important'></li>
	</ul>-->
</div>
<div id='messenger_content' class='right'>
	<if test="inlineError:|:$inlineError">
	<div class='message error'>
		<h4>{$inlineError}</h4>
	</div>
	<br />
	</if>
	{$html}
</div>
<!-- end -->
<div id='pmDisabled' style='display:none'>
	{parse template="messengerDisabled" group="messaging"}
</div>
<br class='clear' />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: PMQuickForm
//===========================================================================
function PMQuickForm($toMemberData) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form id='pm_to_member_{$toMemberData['member_id']}'>
	<input type='hidden' name='toMemberID' value='{$toMemberData['member_id']}' />
	
	<fieldset class='row1'>
		<h3>{$this->lang->words['quick_send_to']} {$toMemberData['members_display_name']}</h3>
		<div id='pm_error_{$toMemberData['member_id']}'>
			<br />
			<p class='message error'></p>
		</div>
		<script type='text/javascript'>
			$('pm_error_{$toMemberData['member_id']}').hide();
		</script>
		<ul class='ipsForm ipsForm_vertical ipsPad'>
			<li class='ipsField'>
				<label for='pm_subject_{$toMemberData['member_id']}' class='ipsField_title'>{$this->lang->words['quick_subject']}</label>
				<p class='ipsField_content'>
					<input type='text' class='input_text' id='pm_subject_{$toMemberData['member_id']}' name='msg_title' size='50' tabindex='0' />
				</p>
			</li>
			<li class='ipsField'>
				<label for='pm_textarea_{$toMemberData['member_id']}' class='ipsField_title'>{$this->lang->words['quick_message']}</label>
				<p class='ipsField_content'>
					<textarea class='input_text' name='Post' tabindex='0' id='pm_textarea_{$toMemberData['member_id']}' cols='65' rows='8'></textarea>
				</p>
			</li>
		</ul>
	</fieldset>
	<fieldset class='submit'>
		<input type='submit' class='input_submit' name='send_msg' value='{$this->lang->words['quick_send_button']}' accesskey='s' tabindex='0' /> <input type='submit' class='input_submit alt use_full' name='use_full' value='{$this->lang->words['use_full_editor']}' tabindex='0' /> {$this->lang->words['or']} <a href='#' class='cancel' title='{$this->lang->words['cancel']}' id='pm_cancel_{$toMemberData['member_id']}' tabindex='0'>{$this->lang->words['cancel']}</a>
	</fieldset>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: sendNewPersonalTopicForm
//===========================================================================
function sendNewPersonalTopicForm($displayData) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div id='message_compose' class='post_form'>
	<form id='msgForm' style='display:block' action="{parse url="module=messaging&amp;section=send&amp;do=send" base="publicWithApp"}" method="post" enctype='multipart/form-data'>
	
<if test="newTopicPreview:|:$displayData['preview']">
	<h2 class='maintitle'>{$this->lang->words['pm_preview']}</h2>
	<div class='post_wrap' id='post_preview'>
		<div class='row2' style='padding:8px'>
			<div class='post entry-content'>{$displayData['preview']}</div>
		</div>
	</div>
	<br />
</if>
<h2 class='maintitle'>{$this->lang->words['mess_new']}</h2>
<if test="newTopicError:|:is_array($displayData['errors']) AND count($displayData['errors'])">
<div class='message error'>
	<h4>{$this->lang->words['err_errors']}</h4>
	<foreach loop="newtopicerrors:$displayData['errors'] as $_error">
		<p>{$_error}</p>
	</foreach>
	<p>{$this->lang->words['pme_none_sent']}</p>
</div>
</if>
<div class='ipsBox'>
	<div class='ipsBox_container'>
		<fieldset>
			<h3 class='bar'>{$this->lang->words['pro_recips']}</h3>
			<ul>
				<li class='field'>
					<label for='entered_name'>{$this->lang->words['to_whom']}</label>
					<input type="text" class='input_text' id='entered_name' name="entered_name" size="30" value="{$displayData['name']}" tabindex="0" />
				</li>
				<if test="newTopicInvite:|:intval($this->memberData['g_max_mass_pm'])">
					<li class='field'>
						<label for='more_members'>{$this->lang->words['other_recipients']}</label>
						<input type='text' size="50" class='input_text' name='inviteUsers' value='{$displayData['inviteUsers']}' id='more_members' tabindex='0' />
						<span class='desc'>{$this->lang->words['youmay_add_to']} <strong>{$this->memberData['g_max_mass_pm']}</strong> {$this->lang->words['youmay_suffix']}</span>
					</li>
					<li class='field'>
						<label for='send_type'>{$this->lang->words['send_to_as']}</label>
						<select name='sendType' id='send_type' tabindex='0'>
							<option value='invite'<if test="formReloadInvite:|:$this->request['sendType']=='invite'"> selected='selected'</if>>{$this->lang->words['send__invite']}</option>
							<option value='copy'<if test="formReloadCopy:|:$this->request['sendType']=='copy'"> selected='selected'</if>>{$this->lang->words['send__copy']}</option>
						</select>
						<span class='desc'>
							<strong>{$this->lang->words['send__invite']}</strong> {$this->lang->words['invite__desc']}<br />
							<strong>{$this->lang->words['send__copy']}</strong> {$this->lang->words['copy__desc']}
						</span>
					</li>				
				</if>
			</ul>
		</fieldset>
		<fieldset>
			<h3 class='bar'>{$this->lang->words['pro_message']}</h3>
			<ul>
				<li class='field'>
					<label for='message_subject'>{$this->lang->words['message_subject_send']}</label>
					<input type="text" name="msg_title" id='message_subject' class='input_text' size="40" tabindex="1" maxlength="40" value="{$displayData['title']}" />
				</li>
				<li>
					{$displayData['editor']}
				</li>
			</ul>
		</fieldset>
		
		<if test="newTopicUploads:|:$displayData['uploadData']['canUpload']">
		<fieldset class='attachments'>
			{parse template="uploadForm" group="post" params="$displayData['postKey'], 'msg', $displayData['uploadData']['attach_stats'], 0"}
		</fieldset>
		</if>
		<input type='hidden' name='topicID' value="{$displayData['topicID']}" />
		<input type='hidden' name="postKey" value="{$displayData['postKey']}" />
		<input type="hidden" name="auth_key" value="{$this->member->form_hash}" />
		<fieldset class='submit'>
			<input class='input_submit' name="dosubmit" type="submit" value="{$this->lang->words['submit_send']}" tabindex="3" accesskey="s" />
			<input class='input_submit alt' type="submit" value="{$this->lang->words['pm_pre_button']}" tabindex="0" name="preview" />
			<input class='input_submit alt' type="submit" value="{$this->lang->words['pms_send_later']}" tabindex="0" name="save" />
			{$this->lang->words['or']} <a href='{parse url="app=members&amp;module=messaging" base="public"}' title='{$this->lang->words['cancel']}' class='cancel' tabindex='0'>{$this->lang->words['cancel']}</a>
		</fieldset>
	</div>
</div>
</form>
</div>
{parse template="include_highlighter" group="global" params=""}
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: sendReplyForm
//===========================================================================
function sendReplyForm($displayData) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div id='message_compose' class='post_form'>
	<if test="replyForm:|:$displayData['type'] == 'reply'">
		<form id='msgForm' style='display:block' action="{parse url="module=messaging&amp;section=send&amp;do=sendReply" base="publicWithApp"}" method="post" name="REPLIER">
	<else />
		<form id='msgForm' style='display:block' action="{parse url="module=messaging&amp;section=send&amp;do=sendEdit" base="publicWithApp"}" method="post" name="REPLIER">
	</if>
		<input type="hidden" name="msgID" value="{$displayData['msgID']}" />
	<input type='hidden' name='topicID' value="{$displayData['topicID']}" />
	<input type='hidden' name="postKey" value="{$displayData['postKey']}" />
	<input type="hidden" name="authKey" value="{$this->member->form_hash}" />
	{$data['upload']}	
<if test="previewPm:|:$displayData['preview']">
	<h2 class='maintitle'>{$this->lang->words['pm_preview']}</h2>
	<div class='post_wrap' id='post_preview'>
		<div class='row2' style='padding:8px'>
			<div class='post entry-content'>{$displayData['preview']}</div>
		</div>
	</div>
	<br />
</if>
<h2 class='maintitle'>
	<if test="formHeaderText:|:$displayData['type'] == 'reply'">
		{$this->lang->words['compose_reply']}
	<else />
		{$this->lang->words['editing_message']}
	</if>
</h2>
<if test="formErrors:|:$displayData['errors']">
<div class='message error'>
	<h4>{$this->lang->words['err_errors']}</h4>
	<foreach loop="replyerrors:$displayData['errors'] as $_error">
		<p>{$_error}</p>
	</foreach>
	<p>{$this->lang->words['pme_none_sent']}</p>
</div>
</if>
<div class='ipsBox'>
	<div class='ipsBox_container'>
		<fieldset>
			<ul>
				<li>
					{$displayData['editor']}
				</li>
			</ul>
		</fieldset>
		
		<if test="attachmentForm:|:$displayData['uploadData']['canUpload']">
		<fieldset class='attachments'>
			{parse template="uploadForm" group="post" params="$displayData['postKey'], 'msg', $displayData['uploadData']['attach_stats'], $displayData['msgID'], 0"}
		</fieldset>
		</if>
		
		<fieldset class='submit'>
			<if test="replyOptions:|:$displayData['type'] == 'reply'">
				<input class='input_submit' type="submit" value="{$this->lang->words['submit_send']}" tabindex="3" accesskey="s" />
				<input class='input_submit alt' type="submit" value="{$this->lang->words['pm_pre_button']}" tabindex="0" name="previewReply" />
				{$this->lang->words['or']} <a href='{parse url="app=members&amp;module=messaging&amp;do=showConversation&amp;topicID={$displayData['topicID']}" base="public"}' title='{$this->lang->words['cancel']}' class='cancel' tabindex='0'>{$this->lang->words['cancel']}</a>
			<else />
				<input class='input_submit' type="submit" value="{$this->lang->words['save_message_button']}" tabindex="3" accesskey="s" />
				<input class='input_submit alt' type="submit" value="{$this->lang->words['pm_pre_button']}" tabindex="0" name="previewReply" />
				{$this->lang->words['or']} <a href='{parse url="app=members&amp;module=messaging&amp;section=view&amp;do=showConversation&amp;topicID={$displayData['topicID']}" base="public"}' title='{$this->lang->words['cancel']}' class='cancel' tabindex='0'>{$this->lang->words['cancel']}</a>
			</if>
			
		</fieldset>
	</div>
</div>
</form>
</div>
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showConversation
//===========================================================================
function showConversation($topic, $replies, $members, $jump="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="disablelightbox:|:!$this->settings['disable_lightbox']">
{parse template="include_lightbox" group="global" params=""}
</if>
{parse template="include_highlighter" group="global" params="1"}
{parse js_module="topic"}
<script type="text/javascript">
//<![CDATA[
	ipb.topic.inSection = 'messenger';
//]]>
</script>
<div id='conversation'>
<div class='topic_controls clearfix'>
	{$topic['_pages']}
	<ul class='topic_buttons'>
		<li><a href='{parse url="module=messaging&amp;section=send&amp;do=form" base="publicWithApp"}' title='{$this->lang->words['go_to_compose']}'>{$this->lang->words['compose_new']}</a></li>
 		<li class='important'><a id='pm_delete_t_{$topic['mt_id']}' href='{parse url="module=messaging&amp;section=view&amp;do=deleteConversation&amp;topicID={$topic['mt_id']}&amp;authKey={$this->member->form_hash}" base="publicWithApp"}'>{$this->lang->words['option__delete']}</a></li>
	</ul>
	<script type='text/javascript'>
		$('pm_delete_t_{$topic['mt_id']}').observe('click', ipb.messenger.deletePM.bindAsEventListener( this, {$topic['mt_id']} ) );
	</script>
</div>
<h2 class='maintitle'>{$topic['mt_title']}</h2>
<div class='ipsBox'>
	<div class='ipsBox_container'>
		<foreach loop="replies:$replies as $msg_id => $msg">
			<a id='msg{$msg['msg_id']}'></a>
			<div class='post_block first hentry' id='post_id_{$msg['msg_id']}'>
				<div class='post_wrap'>
					<if test="hasAuthorId:|:$members[ $msg['msg_author_id'] ]['member_id']">
						<h3 class='row2'>
					<else />
						<h3 class='guest row2'>
					</if>
							<if test="authorOnline:|:$members[ $msg['msg_author_id'] ]['member_id']">
								<span class="author vcard">{parse template="userHoverCard" group="global" params="$members[ $msg['msg_author_id'] ]"}</span>
							<else />
								{$members[ $msg['msg_author_id'] ]['members_display_name']}
							</if>
							<if test="authorIpAddress:|:$msg['_ip_address'] != ''">
								<span class='ip right ipsType_small'>({$this->lang->words['ip']}:
								<if test="authorPrivateIp:|:$members[ $msg['msg_author_id'] ]['g_access_cp']">
									<em>{$this->lang->words['ip_private']}</em>
								<else />
									<if test="accessModCP:|:$this->memberData['g_is_supmod']"><a href="{parse url="app=core&amp;module=modcp&amp;fromapp=members&amp;tab=iplookup&amp;ip={$msg['_ip_address']}" base="public"}" title='{$this->lang->words['find_info_about_ip']}'>{$msg['_ip_address']}</a><else />{$msg['_ip_address']}</if>
								</if>
								)</span>
							</if>
						</h3>
					<div class='author_info'>
						{parse template="userInfoPane" group="global" params="$members[ $msg['msg_author_id'] ], $msg_id, array()"}
					</div>
					<div class='post_body'>
						<p class='posted_info'>{$this->lang->words['pc_sent']} {parse date="$msg['msg_date']" format="long"}</p>
						<div class='post entry-content'>
							{$msg['msg_post']}
							{$msg['attachmentHtml']}
						</div>
						<if test="viewSigs:|:$this->memberData['view_sigs'] AND $members[ $msg['msg_author_id'] ]['signature']">
							{parse template="signature_separator" group="global" params="$members[ $msg['msg_author_id'] ]['signature'], $msg['msg_author_id'], IPSMember::isIgnorable( $members[ $msg['msg_author_id'] ]['member_group_id'], $members[ $msg['msg_author_id'] ]['mgroup_others'] )"}
						</if>
						<ul class='post_controls clearfix clear'>
							<if test="quickReply:|:$topic['_canReply'] AND empty( $topic['_everyoneElseHasLeft'] )">
								<li>
									<a class='ipsButton_secondary' href="{parse url="module=messaging&amp;section=send&amp;do=replyForm&amp;topicID={$topic['mt_id']}&amp;msgID={$msg['msg_id']}" base="publicWithApp"}" title="{$this->lang->words['tt_reply_to_post']}">{$this->lang->words['pc_reply']}</a>
								</li>
							</if>
							<if test="reportPm:|:$topic['_canReport'] and $this->memberData['member_id']">
								<li class='report'>
									<a href='{parse url="app=core&amp;module=reports&amp;rcom=messages&amp;topicID={$this->request['topicID']}&amp;st={$this->request['st']}&amp;msg={$msg['msg_id']}" base="public"}'>{$this->lang->words['pc_report']}</a>
								</li>
							</if>
							<if test="canEdit:|:$msg['_canEdit'] === TRUE">
								<li class='post_edit' id='edit_post_{$msg['msg_id']}'>
									<a href='{parse url="module=messaging&amp;section=send&amp;do=editMessage&amp;topicID={$topic['mt_id']}&amp;msgID={$msg['msg_id']}" base="publicWithApp"}' title='{$this->lang->words['edit_this_post']}'>{$this->lang->words['pc_edit']}</a>
								</li>
							</if>
							<if test="canDelete:|:$msg['_canDelete'] === TRUE && $msg['msg_is_first_post'] != 1">
								<li class='post_del' id='del_post_{$msg['msg_id']}'>
									<a href='{parse url="module=messaging&amp;section=send&amp;do=deleteReply&amp;topicID={$topic['mt_id']}&amp;msgID={$msg['msg_id']}&amp;authKey={$this->member->form_hash}" base="publicWithApp"}' title='{$this->lang->words['delete_this_post']}' class='delete_post'>{$this->lang->words['pc_delete']}</a>
								</li>
							</if>
						</ul>
					</div>
				</div>
			</div>
		</foreach>
	</div>
</div>
<div class='topic_controls clear'>
	{$topic['_pages']}
	<ul class='topic_buttons'>
 		<li class='non_button'><a id='email_convo_{$this->request['topicID']}' data-tooltip='{parse expression="sprintf( $this->lang->words['msg_email_convo_text'], $this->memberData['email'])"}' href='#' class='email_convo'>{$this->lang->words['msg_email_convo']}</a></li>
	</ul>
</div>
<if test="allAlone:|: ! empty( $topic['_everyoneElseHasLeft'] )">
<div class='ipsBox'>
	<div class='ipsBox_container ipsPad'>
		<h1 class='ipsType_subtitle'>{$this->lang->words['msg_all_alone_title']}</h1>
		<p>{$this->lang->words['msg_all_alone_desc']}</p>
	</div>
</div>
<else />
	<if test="canReplyEditor:|:$topic['_canReply']">
	<div class='ipsBox'>
		<div class='ipsBox_container ipsPad'>
			<h1 class='ipsType_subtitle'>{$this->lang->words['pc_fast_reply']}</h1>
			<br />
			<a href="{parse url="showuser={$this->memberData['member_id']}" seotitle="{$this->memberData['members_seo_name']}" template="showuser" base="public"}" title='{$this->lang->words['your_profile']}' class='ipsUserPhotoLink left'><img src='{$this->memberData['pp_small_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$this->memberData['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_medium' /></a>
			<div class='ipsBox_withphoto clearfix'>
				<form action='{parse url="app=members&amp;module=messaging&amp;section=send&amp;do=sendReply&amp;topicID={$topic['mt_id']}" base="public"}' method='post'>
					<input type="hidden" name="authKey" value="{$this->member->form_hash}" />
					<input type="hidden" name="fast_reply_used" value="1" />
					<input type="hidden" name="enableemo" value="yes" />
					<input type="hidden" name="enablesig" value="yes" />
					{parse editor="msgContent" options="array( 'type' => 'full', 'minimize' => 1 )"}
					<br />
					<fieldset class='right'>
						<input type='submit' name="submit" class='input_submit' value='{$this->lang->words['pc_post_button']}' tabindex='3' accesskey='s' />&nbsp;&nbsp;<input type='submit' name="previewReply" class='input_submit alt' value='{$this->lang->words['pc_use_full_reply']}' />
					</fieldset>
				</form>
			</div>
		</div>
	</div>
	</if>
</if>
</div>
{parse template="include_highlighter" group="global" params=""}
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showConversationForArchive
//===========================================================================
function showConversationForArchive($topic, $replies, $members) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>
foreach( $members as $id => $member )
{
	$mems[] = $member['members_display_name'];
}
$memberNames = implode( $mems, ', ' );
</php>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset={$this->settings['gb_char_set']}" />
		<title>{$topic['mt_title']}</title>
		<style type="text/css">
			* {
				font-family: Georgia, "Times New Roman", serif;
			}
			
			html #content {
				font-size: 10pt;
			}
			
			ol,ul { list-style:none; }
			
			ul.pagination {
				margin-left: -35px;
			}
			
			ul.pagination a,
			ul.pagination li.active, 
			ul.pagination li.pagejump,
			ul.pagination li.total {
				text-decoration: none;
				padding: 1px 4px 1px 0px;
				display: block;
			}
			
			ul.pagination li {
				font-size: 0.9em;	
				margin: 0 2px 0 2px;
				float: left;
			}
			
				ul.pagination li.total,
				ul.pagination li.active {
					background: none;
					border: 0;
					margin-left: 0px;
				}
			
				ul.pagination li.active {
					color: #000;
					font-size: 1em;
					font-weight: bold;
				}
				
				ul.pagination li.pagejump {
					display: none;
				}
			
			#admin_bar,
			#header,
			#footer_utilities,
			#utility_links,
			.post_mod,
			.author_info,
			.rep_bar,
			.post_controls,
			.top,
			#content_jump,
			.topic_buttons,
			.topic_options,
			h1,
			.post_id,
			h3 img,
			.ip,
			hr,
			.moderation_bar,
			.topic_jump,
			.topic_share,
			#fast_reply,
			#reputation_filter,
			.statistics,
			.rating,
			.message,
			#debug_wrapper,
			fieldset,
			.signature {
				display: none;
			}
			
			#breadcrumb {
				display: block !important;
			}
				#breadcrumb li {
					float: left;
				}
			
			.topic, .hfeed {
				clear: both;
			}
			
			.post_block {
				margin-bottom: 10pt;
				border-top: 2pt solid gray;
				line-height: 60%; 
				padding-top: 10px;
			}
			
			.posted_info {
				color: gray !important;
				font-size: 8pt !important;
				text-decoration: none !important;
				padding-bottom: 3px;
				float: right;
				margin-top: -30px;
			}
			
			span.main_topic_title {
				font-size: 1.7em;
				padding-left: 2px;
			}
			
			.post_block h3 {
				display: inline !important;
				margin: 0px 0px 10px !important;
				padding: 0px !important;
				float: left;
			}
			
			.post_block h3 a {
				color: black !important;
				text-decoration: none !important;
				font-style: normal !important;
			}
			
				.post_block .post_body a:after {
				    content: " (" attr(href) ") ";
				}
			
			.post_body {
				line-height: 100%;
				margin-top: 15px;
				clear: both;
				display: block;
				padding: 10px;
				border-top: 1pt solid #d3d3d3;
			}
			
			h1, h2, h3 {
				font-weight: bold;
			}
			
			#copyright {
				text-align: center;
				color: gray;
				font-size: 9pt;
			}
			
			a img {
				border: 0px;
			}
			
			abbr.published {
				text-decoration: none !important;
				border: 0px;
			}
		</style>
	</head>
	<body>
		<h2 class='maintitle'>{$topic['mt_title']}</h2>
		<em>
			{$this->lang->words['email_participants']} {$memberNames}
		</em>
		<br />
		<br />
		<foreach loop="replies:$replies as $msg_id => $msg">
			<div class='post_block first hentry'>
				<div class='post_wrap'>
					<h3>
						{$members[ $msg['msg_author_id'] ]['members_display_name']}
					</h3>
					<div class='post_body'>
						<p class='posted_info'>{$this->lang->words['pc_sent']} {parse date="$msg['msg_date']" format="long"}</p>
						<div class='post entry-content'>
							{$msg['msg_post']}
						</div>
					</div>
					<ul class='post_controls'>
						<li>&nbsp;</li>
					</ul>
				</div>
			</div>
		</foreach>
	</body>
</html>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showFolder
//===========================================================================
function showFolder($messages, $dirname, $pages, $currentFolderID, $jumpFolderHTML) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type='text/javascript'>
	ipb.messenger.curFolder = '{$currentFolderID}';
</script>
<div class='topic_controls'>
{$pages}
<if test="folderNotDrafts:|:$currentFolderID != 'drafts'">
	{parse variable="folder_all" default=""}
	{parse variable="folder_all" oncondition="$this->request['folderFilter'] == 'all' OR ! $this->request['folderFilter']" value="selected='selected'"}
	{parse variable="folder_in" default=""}
	{parse variable="folder_in" oncondition="$this->request['folderFilter'] == 'in'" value="selected='selected'"}
	{parse variable="folder_sent" default=""}
	{parse variable="folder_sent" oncondition="$this->request['folderFilter'] == 'sent'" value="selected='selected'"}
</if>
<ul class='topic_buttons'>
	<li><a href='{parse url="module=messaging&amp;section=send&amp;do=form" base="publicWithApp"}' title='{$this->lang->words['go_to_compose']}'>{$this->lang->words['compose_new']}</a></li>
</ul>
</div>
<div id='message_list'>
	<form action="{parse url="app=members&amp;module=messaging&amp;section=view&amp;do=multiFile&amp;cFolderID={$currentFolderID}" base="public"}" id='msgFolderForm' method="post">
		<input type="hidden" name="sort" value="{$this->request['sort']}" />
		<input type="hidden" name="st" value="{$this->request['st']}" />
		<input type="hidden" name="auth_key" value="{$this->member->form_hash}" />
		<div class='maintitle'>
			<span class='right'>
				<input type='checkbox' class='input_check' id='msg_checkall' value='1' /> &nbsp;
			</span>
			{$dirname}
		</div>
		<div class='ipsBox'>
			<div class='ipsBox_container'>
				<table class='ipb_table' id='message_table'>
					<tr class='header hide'>
						<th scope='col' class='col_m_status'>&nbsp;</th>
						<th scope='col' class='col_m_subject'>{$this->lang->words['col_topic']}</th>
						<th scope='col' class='col_m_replies short'>{$this->lang->words['col_replies']}</th>
						<th scope='col' class='col_m_date'>{$this->lang->words['col_last_message']}</th>
						<th scope='col' class='col_mod short'>&nbsp;</th>
					</tr>
					<if test="folderMessages:|:count( $messages )">
						<foreach loop="folderMessages:$messages as $id => $msg">
							<tr id='{$msg['mt_id']}' class='<if test="$msg['map_has_unread']">unread</if>'>
								<td class='col_m_photo altrow short'>
									<if test="hasStarterPhoto:|:$msg['_starterMemberData']['member_id']">
										<a href='{parse url="showuser={$msg['_starterMemberData']['member_id']}" template="showuser" seotitle="{$msg['_starterMemberData']['members_seo_name']}" base="public"}' class='ipsUserPhotoLink'>
											<img src='{$msg['_starterMemberData']['pp_thumb_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' />
										</a>
									<else />
										{IPSMember::buildProfilePhoto(0,'mini')}
									</if>
								</td>
								<td class='col_m_subject'>
									<if test="folderNotifications:|:$msg['mt_is_deleted'] OR $msg['map_user_banned']">
										<span class='ipsBadge ipsBadge_red'>
											{$this->lang->words['msg_deleted']}
										</span>
									</if>
									<h4>
										<if test="$msg['map_has_unread']">
											<a href='{parse url="app=members&amp;module=messaging&amp;section=view&amp;do=findMessage&amp;topicID={$msg['mt_id']}&amp;msgID=__firstUnread__" base="public"}' title='{$this->lang->words['first_unread_reply']}'>{parse replacement="f_newpost"}</a>
											<strong>
										</if>
										<a href='<if test="folderDrafts:|:$currentFolderID == 'drafts'">{parse url="app=members&amp;module=messaging&amp;section=send&amp;do=form&amp;topicID={$msg['mt_id']}" base="public"}<else />{parse url="app=members&amp;module=messaging&amp;section=view&amp;do=showConversation&amp;topicID={$msg['mt_id']}" base="public"}</if>' title='{$this->lang->words['msg_view_conversation']}'>
										{$msg['mt_title']}
										</a>
										<if test="$msg['map_has_unread']"></strong></if>
									</h4>
									<if test="folderNotifications:|:$msg['map_ignore_notification']">
										<span class='ipsBadge ipsBadge_lightgrey'>
											{$this->lang->words['msg_no_notify']}
										</span>
									</if>
									<br />
									<span class='desc lighter blend_links'>
										{$this->lang->words['msg_startedby']}
										<if test="folderStarter:|:$msg['_starterMemberData']['members_display_name']">
											{parse template="userHoverCard" group="global" params="$msg['_starterMemberData']"},
										<else />
											<span class='desc'>{$this->lang->words['deleted_user']},</span>
										</if>
										<span class='desc lighter blend_links'>
											{$this->lang->words['msg_sentto']}
											<if test="folderToMember:|:$msg['_toMemberData']['members_display_name']">
												{parse template="userHoverCard" group="global" params="$msg['_toMemberData']"}
											<else />
												<span class='desc'>{$this->lang->words['deleted_user']}</span>
											</if>
											<if test="folderMultipleUsers:|:$msg['_otherInviteeCount'] > 0">
												<if test="folderFixPlural:|:$msg['_otherInviteeCount'] > 1">
													<span title='{parse expression="implode( ', ', $msg['_invitedMemberNames'] )"}'>({$this->lang->words['pc_and']} {$msg['_otherInviteeCount']} {$this->lang->words['pc_others']})</p>
												<else />
													<span title='{parse expression="implode( ', ', $msg['_invitedMemberNames'] )"}'>({$this->lang->words['pc_and']} {$msg['_otherInviteeCount']} {$this->lang->words['pc_other']})</p>
												</if>
											</if>
										</span>
										<if test="folderNew:|:in_array( $currentFolderID, array( 'new' ) )">
											<p class='ipsType_small desc'>{$this->lang->words['folder_prefix']} {$msg['_folderName']}</p>
										</if>
									</span>	
									<if test="folderPages:|:is_array( $msg['pages'] ) AND count( $msg['pages'] )">
										<ul class='mini_pagination'>
										<foreach loop="messagePages:$msg['pages'] as $page">
												<if test="folderLastPage:|:$page['last']">
													<li><a href="{parse url="app=members&amp;module=messaging&amp;section=view&amp;do=showConversation&amp;topicID={$msg['mt_id']}&amp;st={$page['st']}" base="public"}" title='{$this->lang->words['goto_page']} {$page['page']}'>{$page['page']} {$this->lang->words['_rarr']}</a></li>
												<else />
													<li><a href="{parse url="app=members&amp;module=messaging&amp;section=view&amp;do=showConversation&amp;topicID={$msg['mt_id']}&amp;st={$page['st']}" base="public"}" title='{$this->lang->words['goto_page']} {$page['page']}'>{$page['page']}</a></li>
												</if>
										</foreach>
										</ul>
									</if>
								</td>
								<td class='col_m_replies desc blend_links'>
									<ul>
										<li><if test="folderBannedIndicator:|:$msg['map_user_banned']">-<else />{parse expression="sprintf( $this->lang->words['msg_xreplies'], intval( $msg['mt_replies'] ) )"}</if></li>
									</ul>
								</td>
								<td class='col_f_post'>
									<if test="hasPosterPhoto:|:$msg['_lastMsgAuthor']['member_id']">
										<a href='{parse url="showuser={$msg['_lastMsgAuthor']['member_id']}" template="showuser" seotitle="{$msg['_lastMsgAuthor']['members_seo_name']}" base="public"}' class='ipsUserPhotoLink left'>
											<img src='{$msg['_lastMsgAuthor']['pp_thumb_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' />
										</a>
									<else />
										<span class='left'>{IPSMember::buildProfilePhoto(0,'mini')}</span>
									</if>
									<ul class='last_post ipsType_small'>
										<if test="folderBannedUser:|:$msg['map_user_banned']">
											<li><em>{$this->lang->words['info_not_available']}</em></li>
										<else />
											<li>{$this->lang->words['pc_by']} <if test="folderToMember:|:$msg['_lastMsgAuthor']['members_display_name']">{parse template="userHoverCard" group="global" params="$msg['_lastMsgAuthor']"}<else /><span class='desc'>{$this->lang->words['deleted_user']}</span></if></li>
										</if>
										<li class='desc'>
											<a href='{parse url="app=members&amp;module=messaging&amp;section=view&amp;do=findMessage&amp;topicID={$msg['mt_id']}&amp;msgID={$msg['mt_last_msg_id']}" base="public"}' title='{$this->lang->words['goto_last_post']}'>{parse date="$msg['mt_last_post_time']" format="DATE"}</a>
										</li>
									</ul>
								</td>
								<td class='col_m_mod short'>
									<input type='checkbox' class='input_check msg_check' name='msgid[{$msg['mt_id']}]' id='msg_check_{$msg['mt_id']}' />
								</td>
							</tr>
						</foreach>
					<else />
						<tr>
							<td colspan='8' class='no_messages row1'>
								{$this->lang->words['folder_no_messages_row']}
							</td>
						</tr>
					</if>
				</table>
			</div>
		</div>
		<div id='messenger_mod' class='moderation_bar rounded with_action right'>
			<select id='pm_multifile' name='method' class='input_select'>
				<optgroup label="{$this->lang->words['with_selected_opt']}">
					<option value="delete">{$this->lang->words['option__delete']}</option>
					<if test="folderMultiOptions:|:$currentFolderID != 'drafts'">
						<option value="markread">{$this->lang->words['option__markread']}</option>
						<option value="markunread">{$this->lang->words['option__markunread']}</option>
						<option value="notifyon">{$this->lang->words['option__turnon']}</option>
						<option value="notifyoff">{$this->lang->words['option__turnoff']}</option>
					</if>
				</optgroup>
				<if test="folderJumpHtml:|:$jumpFolderHTML AND $currentFolderID != 'drafts' AND $currentFolderID != 'new'">
					<optgroup label="{$this->lang->words['move_to_opt']}">
						{$jumpFolderHTML}
					</optgroup>
				</if>
			</select>
			<input type="submit" class='input_submit alt' id='folder_moderation' value="{$this->lang->words['jmp_go']}" />
		</div>
	</form>
	<div id='messenger_filter' class='left ipsPad_half'>
		<form method='post' action='{parse url="app=members&amp;module=messaging&amp;section=view&amp;do=showFolder&amp;folderID={$currentFolderID}" base="public"}'>
			<label for='conversation_filter' class='desc'>{$this->lang->words['filter__show']} </label>
			<select id='conversation_filter' name='folderFilter' class='input_select'>
				<option value='' {parse variable="folder_all"}>{$this->lang->words['filter__all']}</option>
				<option value='in' {parse variable="folder_in"}>{$this->lang->words['filter__others']}</option>
				<option value='sent' {parse variable="folder_sent"}>{$this->lang->words['filters__i']}</option>
			</select>
			<input type='submit' class='input_submit alt' value='{$this->lang->words['filters__update']}' />
		</form>
	</div>
</div>
<br />
<div class='topic_controls clear clearfix'>
{$pages}
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showSearchResults
//===========================================================================
function showSearchResults($messages, $pages, $error) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type='text/javascript'>
//<![CDATA[
	ipb.messenger.curFolder = 'in';
//]]>
</script>
<if test="searchError:|:$error">
	<p class='message error'>
		{$error}
	</p>
	<br />
</if>
<if test="$pages">
	<div class='topic_controls'>
	{$pages}
	</div>
	<br />
</if>
<h2 class='maintitle clear'>{$this->lang->words['your_search_results']}</h2>
<div id='message_list'>
	<div class='ipsBox'>
		<div class='ipsBox_container'>
			<table class='ipb_table' id='message_table'>
				<tr class='header hide'>
					<th scope='col' class='col_m_photo'>&nbsp;</th>
					<th scope='col' class='col_m_subject'>{$this->lang->words['col_subject']}</th>
					<th scope='col' class='col_m_replies short'>{$this->lang->words['col_replies']}</th>
					<th scope='col' class='col_m_date'>{$this->lang->words['col_last_message']}</th>
				</tr>
		
				<if test="searchMessages:|:count( $messages )">
					<foreach loop="messages:$messages as $id => $msg">
						<tr id='{$msg['mt_id']}' class='<if test="$msg['map_has_unread']">unread</if>'>
							<td class='col_m_photo altrow short'>
								<a href='{parse url="showuser={$msg['_starterMemberData']['member_id']}" template="showuser" seotitle="{$msg['_starterMemberData']['members_seo_name']}" base="public"}' class='ipsUserPhotoLink'>
									<img src='{$msg['_starterMemberData']['pp_thumb_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' />
								</a>
							</td>
							<td>
								<if test="folderNotifications:|:$msg['mt_is_deleted'] OR $msg['map_user_banned']">
									<span class='ipsBadge ipsBadge_red'>
										{$this->lang->words['msg_deleted']}
									</span>
								</if>
								<h4><a href="{parse url="app=members&amp;module=messaging&amp;section=view&amp;do=showConversation&amp;topicID={$msg['mt_id']}" base="public"}">{$msg['mt_title']}</a></h4><br />
								<span class='desc lighter blend_links'>
									{$this->lang->words['msg_startedby']}
									<if test="folderStarter:|:$msg['_starterMemberData']['members_display_name']">
										{parse template="userHoverCard" group="global" params="$msg['_starterMemberData']"}
									<else />
										<span class='desc'>{$this->lang->words['deleted_user']}</span>
									</if>
									<span class='desc lighter blend_links'>
										, {$this->lang->words['msg_sentto']}
										<if test="folderToMember:|:$msg['_toMemberData']['members_display_name']">
											{parse template="userHoverCard" group="global" params="$msg['_toMemberData']"}
										<else />
											<span class='desc'>{$this->lang->words['deleted_user']}</span>
										</if>
										<if test="folderMultipleUsers:|:$msg['_otherInviteeCount'] > 0">
											<if test="folderFixPlural:|:$msg['_otherInviteeCount'] > 1">
												<span title='{parse expression="implode( ', ', $msg['_invitedMemberNames'] )"}'>({$this->lang->words['pc_and']} {$msg['_otherInviteeCount']} {$this->lang->words['pc_others']})</p>
											<else />
												<span title='{parse expression="implode( ', ', $msg['_invitedMemberNames'] )"}'>({$this->lang->words['pc_and']} {$msg['_otherInviteeCount']} {$this->lang->words['pc_other']})</p>
											</if>
										</if>
									</span>
									<if test="folderNew:|:in_array( $currentFolderID, array( 'new' ) )">
										<p class='ipsType_small desc'>{$this->lang->words['folder_prefix']} {$msg['_folderName']}</p>
									</if>
								</span>
								<p class='ipsType_small desc'>{$this->lang->words['label_pc']} {$msg['_folderName']}</p>
							</td>
							<td class='col_m_replies desc blend_links'>
								<ul>
									<li><if test="folderBannedIndicator:|:$msg['map_user_banned']">-<else />{parse expression="sprintf( $this->lang->words['msg_xreplies'], intval( $msg['mt_replies'] ) )"}</if></li>
								</ul>
							</td>
							<td class='col_f_post'>
								<a href='{parse url="showuser={$msg['_lastMsgAuthor']['member_id']}" template="showuser" seotitle="{$msg['_lastMsgAuthor']['members_seo_name']}" base="public"}' class='ipsUserPhotoLink left'>
									<img src='{$msg['_lastMsgAuthor']['pp_thumb_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' />
								</a>
								<ul class='last_post ipsType_small'>
									<if test="folderBannedUser:|:$msg['map_user_banned']">
										<li><em>{$this->lang->words['info_not_available']}</em></li>
									<else />
										<li>{$this->lang->words['pc_by']} {parse template="userHoverCard" group="global" params="$msg['_lastMsgAuthor']"}</li>
									</if>
									<li class='desc blend_links'><a href='{parse url="app=members&amp;module=messaging&amp;section=view&amp;do=findMessage&amp;topicID={$msg['mt_id']}&amp;msgID={$msg['mt_last_msg_id']}" base="public"}' title='{$this->lang->words['goto_last_post']}'>{parse date="$msg['mt_last_post_time']" format="DATE"}</a></li>
								</ul>
							</td>
						</tr>
					</foreach>
				<else />
					<tr>
						<td colspan='5' class='no_messages row1'>
							{$this->lang->words['no_messages_row']}
						</td>
					</tr>
				</if>
			</table>
		</div>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>