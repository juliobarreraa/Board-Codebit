<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: attachiFrame
//===========================================================================
function attachiFrame($JSON, $id) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<html>
<head>
	<style type="text/css">
		* {
			margin: 0px;
			padding: 0px;
		}
		
		#waitImg {
			position: relative;
			top: 4px;
		}
	</style>
	{parse template="includeRTL" group="global" params=""}
</head>
<body style='background-color: transparent;'>
<form id='iframeUploadForm' method='post' enctype="multipart/form-data" action=''>
<script type='text/javascript'>
	parent.ipb.attach._jsonPass( $id, $JSON );
</script>
<input type='file' id='iframeUploadBox_{$id}' style='display: inline' name='FILE_UPLOAD' /> <input type='reset' value='{$this->lang->words['clear_selection']}' style='display: inline' />
<img src="{$this->settings['img_url']}/loading.gif" style='display: none' id="waitImg" />
</form>
</body>
</html>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: errors
//===========================================================================
function errors($data="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<p class='message error'>
	{$data}
</p>
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: pollBox
//===========================================================================
function pollBox($data) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="poll"}
<script type='text/javascript'>
//<![CDATA[
	ipb.poll.maxQuestions = parseInt( {$data['max_poll_questions']} );
	ipb.poll.maxChoices = parseInt( {$data['max_poll_choices']} );
	ipb.poll.isPublicPoll = parseInt( {$data['poll_view_voters']} );
	ipb.poll.showOnLoad = {$data['show_open']};
	ipb.poll.isMod = {$data['is_mod']};
	
	ipb.poll.questions = \$H(
		<if test="hasPollQuestions:|:$data['poll_questions'] != '[]'">{$data['poll_questions']}</if>
	);
	
	ipb.poll.choices = \$H(
		<if test="hasPollChoices:|:$data['poll_choices'] != '[]'">{$data['poll_choices']}</if>
	);
	
	ipb.poll.votes = \$H(
		<if test="hasPollVotes:|:$data['poll_votes'] != '[]'">{$data['poll_votes']}</if>
	);
	
	ipb.poll.multi = \$H(
		<if test="hasPollMulti:|:$data['poll_multi'] != '[]'">{$data['poll_multi']}</if>
	);
	
	ipb.templates['poll_question'] = 	new Template("<li class='ipsBox_container question' id='question_#{qid}_wrap' style='display: none'>" +
											"<a href='#' class='ipsPad right' id='remove_question_#{qid}' title='{$this->lang->words['remove_question']}'>{parse replacement="remove_poll_question"}</a>" +
											"	<ul class='ipsForm ipsForm_vertical question_title ipsPad'>"+
											"		<li class='ipsField'>" + 
											" 			<label for='#{qid}' class='ipsType_subtitle'>{$this->lang->words['question_title']} #{qid}</label>"+
											"			<p class='ipsField_content'>" +
											" 				<input type='text' class='input_text' id='#{qid}' value='#{value}' size='62' maxlength='254' name='question[#{qid}]' style='max-width: 70%' /></span> <input type='checkbox' class='input_check' value='1' name='multi[#{qid}]' id='multi_#{qid}'/> <label for='multi_#{qid}'><span class='desc lighter'>{$this->lang->words['poll_multichoice']}</span></label>" +
											"			</p>" +
											"		</li>"+
											"	</ul>" + 
											"	<div class='wrap ipsPad'>" + 
											"		<ol id='choices_for_#{qid}'></ol>" + 
											"		<p class='ipsPad poll_control'><a href='#' class='ipsType_small' id='add_choice_#{qid}' title='{$this->lang->words['add_poll_choice']}'>{parse replacement="add_poll_choice"} {$this->lang->words['add_another_choice']}</a></p>" +
											"	</div>" +
											"</li>");
	ipb.templates['poll_choice'] = 		new Template("<li id='poll_#{qid}_#{cid}_wrap' style='display: none'><input type='text' id='poll_#{qid}_#{cid}' name='choice[#{qid}_#{cid}]' class='input_text' value='#{choice}' size='66' maxlength='254' /> <input type='text' id='poll_#{qid}_#{cid}_votes' name='votes[#{qid}_#{cid}]' value='#{votes}' class='input_text' size='5' title='{$this->lang->words['poll_votes_desc']}' /> <a href='#' id='remove_#{qid}_#{cid}' title='{$this->lang->words['remove_choice']}'>{parse replacement="remove_poll_choice"}</a></li>");
//]]>
</script>
<div class='post_form poll_form' id='poll_form'>
	<a href='#' id='add_poll' class='ipsType_small'><img src='{$this->settings['img_url']}/bullet_toggle_plus.png' /> {$this->lang->words['poll_manage_link']}</a>
	
	<div id='poll_popup_content' style='display: none'>
		<div id='poll_wrap'>
			<h3>{$this->lang->words['poll_manager']}</h3>
			<div class='ipsBox_container ipsPad'>
				<label for='poll_title'><strong>{$this->lang->words['poll_fs_title']}</strong></label>&nbsp;&nbsp;
				<input type='text' class='input_text ipsForm_primary' name='poll_question' id='poll_title' size='40' value='{$data['poll_question']}' />&nbsp;&nbsp;&nbsp;&nbsp;
				<if test="allowPublicPoll:|:$this->settings['poll_allow_public'] AND ! $data['poll_total_votes']">
					<span class='ipsType_small' id='check_public_poll' data-tooltip="{$this->lang->words['poll_public_warning']}">
						<input type='checkbox' class='input_check' name='poll_view_voters' id='poll_view_voters' value='1' <if test="viewPollVoters:|:$data['poll_view_voters']">checked='checked'</if> />
						<label for='poll_view_voters'>{$this->lang->words['poll_fs_public']}</label>
					</span>
				</if>
				&nbsp;&nbsp;
				<if test="makePollOnly:|:$this->settings['ipb_poll_only']">
					<span class='ipsType_small' id='check_poll_only' data-tooltip="{$this->lang->words['poll_only_desc']}">
						<input type='checkbox' class='input_check' name='poll_only' id='poll_only' value='1' <if test="pollOnlyChecked:|:$data['poll_only']">checked='checked'</if> />
						<label for='poll_only'>{$this->lang->words['poll_only_title']}</label>
					</span>
				</if>
			</div>
			<div class='ipsBox fixed_inner' id='poll_container_wrap'>
				<ul id='poll_container'></ul>
			</div>
			<div id='poll_footer' class='ipsForm_submit clearfix' style='margin-top: 0;'>
				<a id='add_new_question' class='ipsButton left'>{parse replacement="add_poll_question"} {$this->lang->words['add_another_question']}</a> <span class='desc ipsType_smaller left ipsPad' id='poll_stats'></span>
				<a id='close_poll' class='ipsButton no_width right'>{parse replacement="close_poll_form"} {$this->lang->words['close_poll_form']}</a>
			</div>
		</div>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: postFormTemplate
//===========================================================================
function postFormTemplate($formData=array(), $form = array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="open_close_perm:|:$formData['modOptionsData']['canSetOpenTime'] == 1 || $formData['modOptionsData']['canSetCloseTime'] == 1">
	{parse addtohead="{$this->settings['public_dir']}style_css/{$this->registry->output->skin['_csscacheid']}/calendar_select.css" type="css"}
	<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/calendar_date_select/calendar_date_select.js'></script>
</if>
{parse js_module="post"}
{parse striping="post_stripe" classes="row1,row2"}
<h1 class='ipsType_pagetitle'>{$formData['title']}</h1>
<br />
<!--FORUM RULES-->
<form id='postingform' action='{$this->settings['base_url']}' method='post' enctype='multipart/form-data'>
	<div class='ipsBox ipsForm_vertical ipsLayout ipsLayout_withright ipsPostForm clearfix'>
	    <div class='ipsBox_container ipsLayout_right ipsPostForm_sidebar'>
			<if test="pollboxHtml:|:$formData['pollBoxHTML']">
				<h3 class='bar'>{$this->lang->words['post_poll']}</h3>
				<fieldset id='poll_fieldset' class='ipsPad' style='display: none'>
					{$formData['pollBoxHTML']}
				</fieldset>
				<script type='text/javascript'>
					$('poll_fieldset').show();
				</script>
			</if>
			<div class='ipsPostForm_sidebar_block'>
				<h3 class='bar'>{$this->lang->words['post_options']}</h3>
				<ul class='ipsPad ipsForm ipsForm_vertical ipsType_small'>
				<if test="htmlstatus:|:$formData['checkBoxes']['html'] !== null">
					<li class='ipsField ipsField_checkbox'>
						<input type="checkbox" name="post_htmlstatus" class="input_check" value="1" id='post_htmlstatus' {$formData['checkBoxes']['html']} />
						<p class='ipsField_content'>
							<label for='post_htmlstatus' data-tooltip='{$this->lang->words['pp_html_tooltip']}'>{$this->lang->words['pp_html']}</label>
						</p>
					</li>
				</if>
					<li class='ipsField ipsField_checkbox'>
						<input type="checkbox" name="enableemo" class="input_check" value="yes" id='enable_emo' {$formData['checkBoxes']['emo']} />
						<p class='ipsField_content'>
							<label for='enable_emo'>{$this->lang->words['enable_emo']}</label>
						</p>
					</li>
				<if test="enablesig:|:$this->memberData['member_id']">
					<li class='ipsField ipsField_checkbox'>
						<input type="checkbox" name="enablesig" class="input_check" value="yes" id='enable_sig' {$formData['checkBoxes']['sig']} />
						<p class='ipsField_content'>
							<label for='enable_sig'>{$this->lang->words['enable_sig']}</label>
						</p>
					</li>
					<li class='ipsField ipsField_checkbox'>
						<input type="checkbox" name="enabletrack" class="input_check" id='enable_track' value="1" {$formData['checkBoxes']['tra']} <if test="tracking:|:$formData['checkBoxes']['tra'] == '-tracking-'">checked='checked'</if> />
						<p class='ipsField_content'>
							<label for='enable_track'>{$this->lang->words['enable_track']}</label>
						</p>
					</li>
				</if>
				</ul>
			</div>
			<if test="showModOptions:|:$formData['modOptionsData']['dropDownOptions'] || $formData['modOptionsData']['canSetOpenTime'] || $formData['modOptionsData']['canSetCloseTime']">
				<div class='ipsPostForm_sidebar_block'>
					<h3 class='bar'>{$this->lang->words['moderator_options']}</h3>
					<ul class='ipsPad ipsForm ipsForm_vertical'>
						<if test="mod_options_check:|:$formData['modOptionsData']['dropDownOptions'] != """>
							<li class='ipsField'>
								<label for='forminput'><strong>{$this->lang->words['after_posting_pf']}</strong></label>
								<p class='ipsField_content'>
									{$formData['modOptionsData']['dropDownOptions']}</select>
								</p>
							</li>
						</if>
						<if test="open_time_check:|:$formData['modOptionsData']['canSetOpenTime'] == 1 ">
							<li class='ipsField'>
								<label for='mod_open_date'><strong>{$this->lang->words['mod_open_time']}</strong></label>
								<p class='ipsField_content'>
									<input type='text' size='7' name='open_time_time' id='mod_open_time' class='date input_text' value='{$formData['modOptionsData']['myTimes']['open_time']}' />&nbsp;
									<input type='text' size='16' name='open_time_date' id='mod_open_date' class='input_text date' value='{$formData['modOptionsData']['myTimes']['open_date']}' />
									<img src='{$this->settings['img_url']}/date.png' alt='{$this->lang->words['generic_date']}' id='mod_open_date_icon' class='clickable' />
								</p>
							</li>
						</if>
						<if test="close_time_check:|:$formData['modOptionsData']['canSetCloseTime'] == 1">
							<li class='ipsField'>
								<label for='mod_close_date'><strong>{$this->lang->words['mod_close_time']}</strong></label>
								<p class='ipsField_content'>
									<input type='text' size='7' name='close_time_time' id='mod_close_time' class='date input_text' value='{$formData['modOptionsData']['myTimes']['close_time']}' />&nbsp;
									<input type='text' size='16' name='close_time_date' id='mod_close_date' class='date input_text' value='{$formData['modOptionsData']['myTimes']['close_date']}' />
									<img src='{$this->settings['img_url']}/date.png' alt='{$this->lang->words['generic_date']}' id='mod_close_date_icon' class='clickable' />
							</li>
						</if>
					</ul>
					<script type='text/javascript'>
						document.observe("dom:loaded", function(){
							if( $('mod_open_time') ){ $('mod_open_time').defaultize( "{$this->lang->words['mod_time_format']}" ); }
							if( $('mod_open_date') ){ $('mod_open_date').defaultize( "{$this->lang->words['mod_date_format']}" ); }
							if( $('mod_close_time') ){ $('mod_close_time').defaultize( "{$this->lang->words['mod_time_format']}" ); }
							if( $('mod_close_date') ){ $('mod_close_date').defaultize( "{$this->lang->words['mod_date_format']}" ); }
						});
					</script>
				</div>
			</if>
			<if test="edit_options_check:|:$formData['extraData']['showEditOptions']">
				<div class='ipsPostForm_sidebar_block'>
					<h3 class='bar'>{$this->lang->words['edit_options']}</h3>
					<ul class='ipsPad ipsForm ipsForm_vertical'>
						<li class='ipsField ipsField_checkbox'>
							<input type="checkbox" name="add_edit" value="1" id='append_edit' <if test="checkShowEdit:|:$formData['extraData']['checked']">checked="checked"</if> class="input_check" />
							<p class='ipsField_content'>
								<label for='append_edit'>{$this->lang->words['append_edit']}</label>
							</p>
						</li>
						<if test="showeditreason:|:$formData['extraData']['showReason']">
							<li class='ipsField'>
								<label for='post_edit_reason'><strong>{$this->lang->words['preason_for_edit']}</strong></label>
								<p class='ipsField_content'>
									<input type="text" name="post_edit_reason" id='post_edit_reason' value="{$formData['extraData']['reasonForEdit']}" size='30' maxlength='250' class='input_text' />
								</p>
							</li>
						</if>
					</ul>
				</div>
			</if>
		</div>
		<div class='ipsBox_container ipsLayout_content'>
			<ul class='ipsForm ipsForm_vertical ipsPad'>
				<if test="logged_in_check:|:!$this->memberData['member_id']">
					<li class='ipsField'>
						<label for='username' class='ipsField_title'>{$this->lang->words['guest_name']}</label>
						<p class='ipsField_content'>
							<input type='text' name='UserName' id='username' size='50' value='{$this->request['UserName']}' maxlength="{$this->settings['max_user_name_length']}" class='input_text' tabindex='0' />
						</p>
					</li>
					<if test="guestCaptcha:|:$formData['captchaHTML']">
						{$formData['captchaHTML']}
					</if>
				</if>
				<if test="edit_title_check:|:$formData['formType'] == 'new' OR ( $formData['formType'] == 'edit' AND $formData['canEditTitle'] )">
					<li class='ipsField ipsField_primary'>
						<label for='topic_title' class='ipsField_title'>{$this->lang->words['topic_title']}</label>
						<p class='ipsField_content'>
							<input id='topic_title' class='input_text' type="text" size="60" maxlength="{$this->settings['topic_title_max_len']}" name="TopicTitle" value="{$formData['topicTitle']}" tabindex="0" />
						</p>
					</li>
				</if>
				<if test="edit_tags_check:|:$formData['formType'] == 'new' OR ( $formData['formType'] == 'edit')">
					<if test="hazTag:|:$formData['tagBox']">
						<li class='ipsField tag_field'>
							<label for='ipbTags' class='ipsField_title'>{$this->lang->words['topic_tags']}</label>
							<p class='ipsField_content'>{$formData['tagBox']}</p>
						</li>
					</if>
				</if>
		
				<li class='ipsField ipsField_editor'>
					<if test="statusMsgs:|:is_array($formData['statusMsg']) AND count($formData['statusMsg']) AND strlen($formData['statusMsg'][0])">
						<div class='message'>{parse expression="implode( '<br />', $formData['statusMsg'])"}</div>
					</if>
					{$formData['editor']}
				</li>				
			</ul>
			<if test="upload_form_check:|:$formData['uploadForm']">
				<fieldset class='attachments'>
					{$formData['uploadForm']}
				</fieldset>
			</if>
			
		</div>
		<if test="shareEnabled:|: ! $formData['socialShareOff']">
			{parse template="socialSharePostStrip" group="global_other"}
		</if>
	</div>	
	<fieldset class='submit clear'>
		<input type='hidden' name='st' value='{$this->request['st']}' />
		<input type='hidden' name='app' value='forums' />
		<input type='hidden' name='module' value='post' />
		<input type='hidden' name='section' value='post' />
		<input type='hidden' name='do' value='{$form['doCode']}' />
		<input type='hidden' name='s' value='{$this->member->session_id}' />
		<input type='hidden' name='p' value='{$form['p']}' />
		<input type='hidden' name='t' value='{$form['t']}' />
		<input type='hidden' name='f' value='{$form['f']}' />
		<input type='hidden' name='parent_id' value='{$form['parent']}' />
		<input type='hidden' name='attach_post_key' value='{$form['attach_post_key']}' />
		<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
		<input type='hidden' name='removeattachid' value='0' />
		<input type='hidden' name='return' value='{$this->request['return']}' />
		<input type='hidden' name='_from' value='{$this->request['_from']}' />
		<input type="submit" name="dosubmit" value="{$formData['buttonText']}" tabindex="3" class="input_submit" accesskey="s"  />&nbsp;
		<input type="submit" name="preview" value="{$this->lang->words['button_preview']}" tabindex="0" class="input_submit alt" />
		{$this->lang->words['or']}
		<if test="$this->request['return'] == 'modcp:unapproved'">
			<a href='{parse url="app=core&module=modcp&fromapp=forums&tab=unapprovedposts" base="public"}' title='{$this->lang->words['cancel']}' class='cancel' tabindex='0'>{$this->lang->words['cancel']}</a>
		<else />
			<if test="cancelposting:|:$form['t']">
				<a href='{parse url="showtopic={$form['t']}" template="showtopic" seotitle="{$formData['seoTopic']}" base="public"}' title='{$this->lang->words['cancel']}' class='cancel' tabindex='0'>{$this->lang->words['cancel']}</a>
			<else />
				<a href='{parse url="showforum={$form['f']}" template="showforum" seotitle="{$formData['seoForum']}" base="public"}' title='{$this->lang->words['cancel']}' class='cancel' tabindex='0'>{$this->lang->words['cancel']}</a>
			</if>
		</if>
	</fieldset>
</form>
{$formData['topicSummary'][0]['html']}
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: preview
//===========================================================================
function preview($data="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="postpreview:|:$data AND trim($data) != '<br />' AND trim($data) != '<br>'">
	<if test="disablelightbox:|:!$this->settings['disable_lightbox']">
		{parse template="include_lightbox" group="global" params=""}
	</if>
	<h2 class='maintitle'>{$this->lang->words['post_preview']}</h2>
	<div class='post_block no_sidebar'>
		<div class='post_wrap ipsBox' id='post_preview'>
			<div class='ipsBox_container ipsPad'>
				<div class='post_body'>
					<div class='post entry-content'>{$data}</div>
				</div>
			</div>
		</div>
	</div>
	<br />
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: topicSummary
//===========================================================================
function topicSummary($posts=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="topic"}
{parse template="include_highlighter" group="global" params=""}
<br /><br />
<h3 class='maintitle'>{$this->lang->words['topic_summary']}</h3>
<div class='generic_bar'></div>
<div id='topic_summary'>
<if test="topicsummaryposts:|:is_array( $posts ) AND count( $posts )">
	<foreach loop="posts:$posts as $pid => $data">
		<div class='post_block no_sidebar topic_summary'>
			<div class='post_wrap'>
				<h3 class='row2<if test="isGuest:|:empty($data['member_id'])"> guest</if>'>{parse template="userHoverCard" group="global" params="$data"}</h3>
				<div class='post_body'>
					<p class='posted_info'>{$this->lang->words['summary_posted']} {$data['date']}</p>
					<if test="ignoringpost:|:isset( $data['_ignored'] ) && $data['_ignored'] == 1">
						<div class='post'>{$this->lang->words['ignore_first_line']} {$data['members_display_name']}.</div>
					<else />
						<div class="post">{$data['post']}</div>
					</if>
				</div>
			</div>
			<br />
		</div>
	</foreach>
</if>
<p class='submit'>
	<a href='{parse url="showtopic={$this->request['t']}" base="public"}' title='{$this->lang->words['review_topic']}' id='review_topic'>{$this->lang->words['review_topic']}</a>
</p>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: uploadForm
//===========================================================================
function uploadForm($post_key="",$type="",$stats=array(),$id="",$forum_id=0) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="$this->memberData['member_uploader'] == 'flash'">
	<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/swfupload/swfupload.js'></script>
	<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/swfupload/plugins/swfupload.swfobject.js'></script>
	<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/swfupload/plugins/swfupload.cookies.js'></script>
	<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/swfupload/plugins/swfupload.queue.js'></script>
</if>
<script type='text/javascript'>
//<![CDATA[
	ipb.lang['used_space'] = "{parse expression="sprintf( $this->lang->words['used_space_js'], "{$stats['max_single_upload_human']}" )"}";
//]]>
</script>
{parse js_module="attach"}
<div class='ipsPad'>
	<div id='attach_error_box' class='message error' style='display:none'></div>
	<input type='file' id='nojs_attach_{$id}_1' class='input_upload' name='FILE_UPLOAD' tabindex='1' />
	<input type='file' id='nojs_attach_{$id}_2' class='input_upload' name='FILE_UPLOAD' tabindex='1' />
	
	<ul id='attachments'><li style='display: none'></li></ul>
	
	<div class='attach_controls'>
		<h2 class='ipsType_subtitle'>{$this->lang->words['attach_header']}</h2>
		<span id='buttonPlaceholder'></span>
		<input type='button' id='add_files_attach_{$id}' class='ipsType_small ipsButton_secondary attach_button' value='{$this->lang->words['attach_selected']}' style='display: none; clear: both' tabindex='-1' />
		&nbsp;&nbsp;
		<span class='desc ipsType_small' id='space_info_attach_{$id}'>
			<if test="attachNotAllowed:|:$stats['space_left_human'] == $this->lang->words['attach_notallowed']">
				{$this->lang->words['used_space_exceeded']}
			<else />
				<if test="unlimitedSpace:|:$stats['space_left_human'] == $this->lang->words['attach_unlimited']">
					{parse expression="sprintf( $this->lang->words['used_space_unlimited'], "{$stats['max_single_upload_human']}")"}
				<else />
					{parse expression="sprintf( $this->lang->words['used_space'], "{$stats['space_left_human']}", "{$stats['max_single_upload_human']}")"}
				</if>
			</if>
		</span>
		<if test="helpMessage:|:!IN_ACP AND $this->settings['uploadFormType']">
			<p class='desc lighter ipsType_smaller' id='help_msg'>
				<if test="flashuploadhelp:|:$this->memberData['member_uploader'] == 'flash'">
					 {$this->lang->words['trouble_uploading']} <a href='#' data-switch='default' title='{$this->lang->words['switch']}' tabindex='1'>{$this->lang->words['switch_to_basic']}</a>
				<else />
					<a href='#' data-switch='flash' title='{$this->lang->words['switch']}' tabindex='-1'>{$this->lang->words['switch_to_advanced']}</a>
				</if>
			</p>
		</if>
	</div>
	
</div>
<script type='text/javascript'>
//<![CDATA[
	ipb.delegate.register("[data-switch]", function(e, elem){
		ipb.attach.switchUploadType( elem.readAttribute('data-switch') );
	});
	
	// Show the button and info
	$('add_files_attach_{$id}').show();
	$('space_info_attach_{$id}').show();
	
	var useType = 'default';
	var uploadURL = ipb.vars['base_url'] + "app=core&module=attach&section=attach&do=attachiFrame&attach_rel_module={$type}&attach_rel_id={$id}&attach_post_key={$post_key}&forum_id={$forum_id}&attach_id=attach_{$id}&fetch_all=1";
	if ( ipb.vars['use_swf_upload'] && ( jimAuld.utils.flashsniffer.meetsMinVersion( 9 ) && ( ipb.vars['swfupload_enabled'] ) ) )
	{
		useType = 'swf';
		var uploadURL = ipb.vars['base_url'] + "app=core&module=attach&section=attach&do=attach_upload_process&attach_rel_module={$type}&attach_rel_id={$id}&attach_post_key={$post_key}&forum_id={$forum_id}&_nsc=1";
	}
	
	ipb.attach.template = "<li id='ali_[id]' class='attach_row' style='display: none'><div><h4 class='attach_name'>[name]</h4><p class='info'>[info]</p><span class='img_holder'></span><p class='progress_bar'><span style='width: 0%'>0%</span></p><p class='links'><a href='#' class='add_to_post' title='{$this->lang->words['attach_button_title']}' tabindex='-1'>{$this->lang->words['attach_button']}</a> | <a href='#' class='cancel delete' title='{$this->lang->words['attach_delete_title']}' tabindex='-1'>{$this->lang->words['attach_delete']}</a></p></div></li>"; 
	document.observe('dom:loaded', function(){
		ipb.attach.registerUploader( 'attach_{$id}', useType, 'attachments', {
			'upload_url': uploadURL,
			'attach_rel_module': "{$type}",
			'attach_rel_id': "{$id}",
			'attach_post_key': "{$post_key}",
			'forum_id': "{$forum_id}",
			'file_size_limit': "{$stats['max_single_upload']}"
		} )});
//]]>
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>