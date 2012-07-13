<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: coreAttachments
//===========================================================================
function coreAttachments($info="",$pages="",$attachments) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3 class='ipsType_subtitle'>{$this->lang->words['m_attach']}</h3>
<br />
<div class='row1'>	
	<if test="hasAttachLimit:|:$info['has_limit'] == 1">
		<div id='space_allowance' class='general_box'>
			<p><strong>{$info['attach_space_used']}</strong></p>
			<p class='progress_bar <if test="attachAlmostFull:|:$info['full_percent'] > 80">limit</if>' title='{$this->lang->words['ucp_attach_allowance']} {$info['full_percent']}% {$this->lang->words['ucp_full']}'>
				<span style='width: {$info['full_percent']}%'>{$info['full_percent']}%</span>
			</p>
			<p class='desc'>{$info['attach_space_count']}</p>
		</div>
	</if>
	<if test="hasPagesTop:|:$pages">
		<div class='topic_controls'>
			{$pages}
		</div>
		<br class='clear' />
	</if>
	<!-- ATTACHMENTS TABLE -->
	<form action="{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=updateAttachments&amp;do=saveIt" base="public"}" id="checkBoxForm" method="post">
	<table class='ipb_table' summary="{$this->lang->words['ucp_user_attach']}">
		<tr class='header'>
				<th scope='col' style='width: 2%'>&nbsp;</th>
				<th scope='col' style='width: 35%'>{$this->lang->words['attach_title']}</th>
				<th scope='col' style='width: 7%'>{$this->lang->words['attach_hsize']}</th>
				<th scope='col' style='width: 27%'>{$this->lang->words['attach_topic']}</th>
				<th scope='col' class='short' style='width: 3%'><input class='input_check' id="checkAllAttachments" type="checkbox" value="{$this->lang->words['check_all']}" /></th>
			</tr>
			<if test="hasAttachments:|:count($attachments)">
				{parse striping="attach" classes="row1,row2"}
				<foreach loop="attach:$attachments as $idx => $data">
					<tr id="a{$data['attach_id']}" class='{parse striping="attach"}'>
							<td class='short altrow'>
								<if test="attachmentThumbLocation:|:$data['attach_thumb_location']">
									<a href="{parse url="app=core&amp;module=attach&amp;section=attach&amp;attach_rel_module={$data['_type']}&amp;attach_id={$data['attach_id']}" base="public"}" title="{$data['attach_file']}"><img src="{$this->settings['upload_url']}/{$data['attach_thumb_location']}" width="30" height="30" alt='{$this->lang->words['attached_file']}' /></a>
								<else />
									<img src="{$this->settings['mime_img']}/{$data['image']}" alt="{$this->lang->words['attached_file']}" />
								</if>
							</td>
							<td>
								<a href="{parse url="app=core&amp;module=attach&amp;section=attach&amp;attach_rel_module={$data['_type']}&amp;attach_id={$data['attach_id']}" base="public"}" title="{$data['attach_file']}">{$data['short_name']}</a><br />
								<span class="desc">( {$this->lang->words['attach_hits']}: {$data['attach_hits']} )</span>
							</td>
							<td class='short altrow'>{$data['real_size']}</td>
							<td>
								<if test="attachmentPost:|:$data['attach_rel_id'] > 0 AND $data['attach_rel_module'] == 'post'">
									<a href="{parse url="showtopic={$data['tid']}&amp;view=findpost&amp;p={$data['attach_rel_id']}" base="public"}" title='{$this->lang->words['ucp_view_org']}'>{$data['title']}</a>
								<else />
									{$data['title']}
								</if>
								<br />
								<span class="desc">{$data['attach_date']}</span>
							</td>
							<td class='altrow short'><input type="checkbox" name="attach[{$data['attach_id']}]" value="1" class="input_check checkall" /></td>
						</tr>
				</foreach>
			<else />
				<tr>
					<td colspan="5" class='no_messages'>{$this->lang->words['splash_noattach']}</td>
				</tr>
			</if>
		</table>
		<if test="attachmentMultiDelete:|:count($attachments)">
			<div class='moderation_bar rounded with_action clear' id='topic_mod'>
				<input type="hidden" name="authKey" value="{$this->member->form_hash}" />
				<input type="submit" value="{$this->lang->words['attach_delete']}" class="input_submit alt" />
			</div>
		</if>
	</form>
</div>
<if test="hasPagesBottom:|:$pages">
	<div class='topic_controls'>
		{$pages}
	</div>
	<br class='clear' />
</if>
<script type='text/javascript'>
	ipb.global.registerCheckAll( 'checkAllAttachments', 'checkall' );
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: displayNameForm
//===========================================================================
function displayNameForm($form=array(),$error="",$okmessage="", $isFB=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="dnameOkMessage:|:$okmessage != """>
	<p class='message'>{$okmessage}</p>
</if>
<fieldset class='row1'>
	<h3 class='ipsType_subtitle'>{$this->lang->words['dname_title']}</h3>
	<p class='ipsType_pagedesc'>{$form['_lang_string']}</p>
	<br />
	<if test="$form['_noPerm']">
		<p class='message error'>{$form['_noPerm']}</p>
	<else />
		<ul class='ipsForm ipsForm_horizontal'>
			<li class='ipsField'>
				<label for='displayName' class='ipsField_title'>{$this->lang->words['dname_choose']}</label>
				<p class='ipsField_content'>
					<input class='input_text' type="text" maxlength='{$this->settings['max_user_name_length']}' name="displayName" id="displayName" value="{$this->request['displayName']}" size='30' /><br />
					<span class='desc lighter'>{$this->lang->words['dname_choose2']}</span>
				</p>
			</li>
			<if test="dnameFbUser:|:!$isFB">
			<li class='ipsField'>
				<label for='displayPassword' class='ipsField_title'>{$this->lang->words['dname_password']}</label>
				<p class='ipsField_content'>
					<input class='input_text' type="password" name="displayPassword" id="displayPassword" value="" size='30' /> <br />
					<span class='desc lighter'>{$this->lang->words['dname_password2']}</span>
				</p>
			</li>
			</if>
		</ul>
	</if>
</fieldset>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: emailPasswordChangeForm
//===========================================================================
function emailPasswordChangeForm($txt, $_message, $isFB=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type="text/javascript">
var msg = "$_message";
	if ( msg != ""){
		alert(msg);
	}
</script>
<fieldset class='row1'>
<h3 class='ipsType_subtitle'>{$this->lang->words['m_email_pass_change']}</h3>
<if test="hideMessageIfAdmin:|:!$this->memberData['g_access_cp']"><p class='ipsType_pagedesc'>{$this->lang->words['m_email_pass_change_desc']}</p></if>
</fieldset>
<if test="emailIsAdmin:|:$this->memberData['g_access_cp']">
	<p class='message unspecific'>
		{$this->lang->words['admin_emailpassword']}
	</p>
</if>
<if test="emailIsNotAdmin:|:!$this->memberData['g_access_cp']">
	<fieldset class='row1'>
		<h3 class='ipsType_subtitle'>{$this->lang->words['change_email_title']}</h3>
		<p class='ipsType_pagedesc'>
			{$txt}
		</p>
		<br />
		<ul class='ipsForm ipsForm_horizontal'>
			<li class='ipsField clearfix'>
				<span class='ipsField_title'>{$this->lang->words['ce_current']}</span>
				<div class='ipsField_content'>
					<strong style='line-height: 1.8'>{$this->memberData['email']}</strong>
				</div>
			</li>
			<li class='ipsField clearfix'>
				<label for='in_email_1' class='ipsField_title'>{$this->lang->words['ce_new_email']}</label>
				<p class='ipsField_content'>
					<input type="text" size='30' name="in_email_1" id='in_email_1' class='input_text' value="" />
				</p>
			</li>
			<li class='ipsField clearfix'>
				<label for='in_email_2' class='ipsField_title'>{$this->lang->words['ce_new_email2']}</label>
				<p class='ipsField_content'>
					<input type="text" size='30' name="in_email_2" id='in_email_2' class='input_text' value="" />
				</p>
			</li>
			<if test="passFbUser:|:!$isFB">
			<li class='ipsField clearfix'>
				<label for='password' class='ipsField_title'>{$this->lang->words['ec_passy']}</label>
				<p class='ipsField_content'>
					<input type="password" size='30' id='password' class='input_text' name="password" value="" />
				</p>
			</li>
			</if>
		</ul>
	</fieldset>
	<fieldset class='row1'>
		<h3 class='ipsType_subtitle'>{$this->lang->words['account_pass_title']}</h3>
		<p class='ipsType_pagedesc'>
			<if test="removeUser:|:! $this->memberData['bw_local_password_set'] AND $this->memberData['members_created_remote']">
				{$this->lang->words['remote_pass_set']}
			<else />
				{$this->lang->words['pass_change_text']}
			</if>
		</p>
		<br />
		<ul class='ipsForm ipsForm_horizontal'>
			<if test="removeUser2:|:$this->memberData['bw_local_password_set'] OR ! $this->memberData['members_created_remote']">
			<li class='ipsField'>
				<label for='current_pass' class='ipsField_title'>{$this->lang->words['account_pass_old']}</label>
				<p class='ipsField_content'>
					<input type="password" name="current_pass" value="" id='current_pass' class='input_text' size='30' />
				</p>
			</li>
			</if>
			<li class='ipsField'>
				<label for='new_pass_1' class='ipsField_title'><if test="removeUser3:|:$this->memberData['bw_local_password_set'] OR ! $this->memberData['members_created_remote']">{$this->lang->words['account_pass_new']}<else />{$this->lang->words['enter_pass_remote1']}</if></label>
				<p class='ipsField_content'>
					<input type="password" name="new_pass_1" value="" id='new_pass_1' class='input_text' size='30' />
				</p>
			</li>
			<li class='ipsField'>
				<label for='new_pass_2' class='ipsField_title'><if test="removeUser4:|:$this->memberData['bw_local_password_set'] OR ! $this->memberData['members_created_remote']">{$this->lang->words['account_pass_new2']}<else />{$this->lang->words['enter_pass_remote2']}</if></label>
				<p class='ipsField_content'>
					<input type="password" name="new_pass_2" value="" id='new_pass_2' class='input_text' size='30' />
				</p>
			</li>
		</ul>
	</fieldset>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: field_dropdown
//===========================================================================
function field_dropdown($name="",$options="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<select name="{$name}" class='input_select'>
	{$options}
</select>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: field_entry
//===========================================================================
function field_entry($title="",$desc="",$content="",$id="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<li class='custom'>
	<label for='field_{$id}' class='ipsSettings_fieldtitle'>{$title}</label>
	<if test="stristr($content, 'radio') || stristr($content, 'checkbox')">
		<p class='wrap'>{$content}</p>
		<if test="cfieldDesc:|:$desc"><span class='desc'>{$desc}</span></if>
	<else />
		{$content}
		<if test="cfieldDesc:|:$desc"><br /><span class='desc'>{$desc}</span></if>
	</if>
	
</li>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: field_textarea
//===========================================================================
function field_textarea($name="",$value="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<textarea cols="80" rows="5" wrap="soft" name="$name" class='input_text'>{$value}</textarea>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: field_textinput
//===========================================================================
function field_textinput($name="",$value="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<input type="text" size="50" name="{$name}" value='{$value}' class='input_text' />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: membersFacebookConnect
//===========================================================================
function membersFacebookConnect($fbuid, $fbUserData, $linkedMemberData, $perms) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse striping="usercp" classes="row1,row2"}
<if test="fbMismatch:|:$fbuid AND $linkedMemberData['member_id'] AND $linkedMemberData['member_id'] != $this->memberData['member_id']">
	<!-- currently logged in FB user is linked to a different account -->
	<div class='message error'>
		<strong>{$this->lang->words['fb_connect_mismatch']}</strong>
		<p>{$this->lang->words['fb_connect_mismatch_desc']}</p>
	</div>
<else />
	<if test="! $fbUserData['id']">
		<div id='fbUserBox'>
			<p class='message'>{$this->lang->words['fb_config']}</p>
			<br />
			<br />
			<a href="{$this->settings['_original_base_url']}/interface/facebook/index.php"><img src="{$this->settings['img_url']}/facebook_login.png" alt="" /></a>
		</div>
	<else />
		<div>
			<fieldset class='ipsPad_half row2'>
				<div class='left ipsUserPhoto' style='margin-right:4px;'>
					<img src="{$fbUserData['pic_square']}" alt="" />
				</div>
				<div class='desc'>
				{$this->lang->words['fb_logged_in_as']} <strong>{$fbUserData['name']}</strong>
				<if test="fbShowStatus:|:is_array($fbUserData['status']) AND $fbUserData['status']['message']">
					<br /><br />{$fbUserData['status']['message']}
				</if>
				</div>
			</fieldset>
			<br />
			<fieldset class='row1'>
				<h3 class='ipsType_subtitle'>{$this->lang->words['fb_sync_options']}</h3>
				<ul>
					<li class='field checkbox'>
						<input class='input_check' type='checkbox' value='1' name='fbc_s_pic' value='1' <if test="fbSyncPic:|:$this->memberData['fbc_s_pic'] > 0">checked='checked'</if> id='fbc_s_pic' /><label for='fbc_s_pic'>{$this->lang->words['fb_sync_photo']}</label><br />
						<span class='desc lighter'>{$this->lang->words['fb_sync_photo_info']}</span>
					</li>
					<if test="canUpdateStatus:|:$this->memberData['can_updated_status']">
					<li class='field checkbox'>
						<input class='input_check' type='checkbox' value='1' name='fbc_s_status' value='1' <if test="fbSyncStatus:|:$this->memberData['fbc_s_status'] > 0">checked='checked'</if> id='fbc_s_status' /><label for='fbc_s_status'>{$this->lang->words['fb_sync_status']}</label>
					</li>
					</if>
					<if test="statusImportGroup:|: ! $this->memberData['gbw_no_status_import']">
					<li class='field checkbox'>
						<input class='input_check' type='checkbox' value='1' name='fbc_si_status' value='1' <if test="fbSyncStatus:|:$this->memberData['fbc_si_status'] > 0">checked='checked'</if> id='fbc_si_status' /><label for='fbc_si_status'>{$this->lang->words['fb_sync_status_in']}</label>
					</li>
					</if>
				</ul>
				<p class='desc'>
					{$this->lang->words['fb_last_syncd']}
					<if test="fbLastSync:|:$this->memberData['fb_lastsync']">
						{parse date="$this->memberData['fb_lastsync']" format="long"}
					<else />
						{$this->lang->words['fb_never']}
					</if>
				</p>
				<br />
				<p><input type='submit' class='input_submit alt' id='fbc_sync' value='{$this->lang->words['fb_sync_now']}' /></p>
			</fieldset>
			<br />
			<fieldset class='row1'>
				<h3 class='ipsType_subtitle'>{$this->lang->words['fbp_title']}</h3>
				<ul>
					<li class='field checkbox'>
						<img src="{$this->settings['img_url']}/<if test="pImgOA:|:$perms['offline_access']">accept.png<else />cross.png</if>" style='vertical-align:top' alt='' />&nbsp;{$this->lang->words['fbp_offline_access']}
						<if test="pCheckOA:|:!$perms['offline_access']">&nbsp;(<fb:prompt-permission perms="offline_access">{$this->lang->words['fbp_request']}</fb:prompt-permission>)</if>
						<p class='desc' style='left:0px'>{$this->lang->words['fbp_offline_access_desc']}</p>
					</li>
					<li class='field checkbox'>
						<img src="{$this->settings['img_url']}/<if test="pImgE:|:$perms['email']">accept.png<else />cross.png</if>" style='vertical-align:top' alt='' />&nbsp;{$this->lang->words['fbp_email_access']}
						<if test="pCheckE:|:!$perms['email']">&nbsp;(<fb:prompt-permission perms="email">{$this->lang->words['fbp_request']}</fb:prompt-permission>)</if>
						<p class='desc' style='left:0px'>{$this->lang->words['fbp_email_access_desc']}</p>
					</li>
					<li class='field checkbox'>
						<img src="{$this->settings['img_url']}/<if test="pImgPS:|:$perms['publish_stream']">accept.png<else />cross.png</if>" style='vertical-align:top' alt='' />&nbsp;{$this->lang->words['fbp_publish_stream']}
						<if test="pCheckPS:|:!$perms['publish_stream']">&nbsp;(<fb:prompt-permission perms="publish_stream">{$this->lang->words['fbp_request']}</fb:prompt-permission>)</if>
						<p class='desc' style='left:0px'>{$this->lang->words['fbp_publish_stream_desc']}</p>
					</li>
					<li class='field checkbox'>
						<img src="{$this->settings['img_url']}/<if test="pImgRS:|:$perms['read_stream']">accept.png<else />cross.png</if>" style='vertical-align:top' alt='' />&nbsp;{$this->lang->words['fbp_read_stream']}
						<if test="pCheckRS:|:!$perms['read_stream']">&nbsp;(<fb:prompt-permission perms="read_stream">{$this->lang->words['fbp_request']}</fb:prompt-permission>)</if>
						<p class='desc' style='left:0px'>{$this->lang->words['fbp_read_stream_desc']}</p>
					</li>
				</ul>
			</fieldset>
			<fieldset class='row1'>
				<if test="fbPassword:|:! $this->memberData['bw_local_password_set'] AND $this->memberData['members_created_remote']">
					<div class='message'>
						<strong>{$this->lang->words['remote_no_password']}</strong>
						<p>{$this->lang->words['remote_no_password_1']} <a href='{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=email" base="public"}'>{$this->lang->words['remote_no_password_2']}</a>
						</p>
					</div>
					<br />
				</if>
				<h3 class='ipsType_subtitle'>{$this->lang->words['fb_disassociate']}</h3>
				<p class='desc'>{$this->lang->words['fb_disassociate_desc']}</p>
				<if test="fbDefaultEmail:|:strstr( $this->memberData['email'], '@proxymail.facebook.com' )">
					<div class='message'>
						<strong>{$this->lang->words['fb_using_email']}</strong>
						<p>{$this->lang->words['fb_disassociate_info_1']} <a href='{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=email" base="public"}'>{$this->lang->words['fb_disassociate_info_2']}</a> {$this->lang->words['fb_disassociate_info_3']}
						</p>
					</div>
					<br />
				</if>
				<br />
				<p><input type='button' class='input_submit alt' id='fbc_remove' value='{$this->lang->words['fb_disassociate_now']}' /></p>
				<br />
			</fieldset>
		</div>
		<script type="text/javascript">
		$('fbc_remove').observe( 'click', usercp_remove );
		function usercp_remove(){
			window.location = ipb.vars['base_url'] + 'app=core&module=usercp&tab=core&area=facebookRemove&do=custom&secure_key=' + ipb.vars['secure_hash'];
		}
		</script>
	</if>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: membersIgnoredUsersForm
//===========================================================================
function membersIgnoredUsersForm($members, $pagination) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type='text/javascript'>
//<![CDATA[
	ipb.templates['autocomplete_wrap'] = new Template("<ul id='#{id}' class='ipbmenu_content' style='width: 250px;'></ul>");
	ipb.templates['autocomplete_item'] = new Template("<li id='#{id}'><img src='#{img}' alt='' width='#{img_w}' height='#{img_h}' />&nbsp;&nbsp;#{itemvalue}</li>");
//]]>
</script>
<!--<h2 class='ipsType_subtitle'>{$this->lang->words['ucp_global_prefs']}</h2>-->
<div class='ipsPad'>
	<input type='checkbox' name='donot_view_sigs' id='donot_view_sigs' value='1' <if test="canSee:|: ! $this->memberData['view_sigs']">checked='checked'</if> />
	<label class='desc' for='donot_view_sigs'>{$this->lang->words['ucp_global_prefs_desc']}</label>
</div>
<br />
<h2 class='ipsType_subtitle'>{$this->lang->words['mi5_title']}</h2>
<br />
<if test="topPagination:|:$pagination">
	{$pagination}
	<br class='clear' />
	<br />
</if>
<table class='ipb_table' summary="{$this->lang->words['ucp_ignored_users']}">
	<tr class='header'>
		<th scope='col' width="30%">{$this->lang->words['mi5_name']}</th>
		<th scope='col' width="30%">{$this->lang->words['mi5_group']}</th>
		<th scope='col' class='short'>{$this->lang->words['ucp_ignore_posts']}</th>
		<th scope='col' class='short'>{$this->lang->words['ucp_ignore_sigs']}</th>
		<th scope='col' class='short'>{$this->lang->words['ucp_ignore_msgs']}</th>
		<if test="hasChat:|:IPSLib::appIsInstalled('ipchat')"><th scope='col' class='short'>{$this->lang->words['ucp_ignore_chats']}</th></if>
		<th scope='col' class='short'>&nbsp;</th>
	</tr>
	{parse striping="members" classes="row1,row2"}
	<if test="count( $members )">
		<foreach loop="members:$members as $member">
			<tr class='{parse striping="members"}'>
				<td>
					<img src='{$member['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_mini left' style='margin-right: 5px' />
					<strong>{parse template="userHoverCard" group="global" params="$member"}</strong><br />
					<span class='desc lighter'>{$this->lang->words['m_joined']} {parse date="$member['joined']" format="joined"}</span>
				</td>
				<td>{$member['g_title']}</td>
				<td class='short'>
					<if test="ignoreMemberTopics:|:$member['ignoreData']['ignore_topics'] == 1">
						<a class='ipsButton_secondary' href="{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=toggleIgnoredUser&amp;do=saveIt&amp;id={$member['member_id']}&amp;field=topics" base="public"}" title="{$this->lang->words['click_toggle']}"><span style='color:red'>{$this->lang->words['ucp_hide_disallow']}</span></a>
					<else />
						<a class='ipsButton_secondary' href="{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=toggleIgnoredUser&amp;do=saveIt&amp;id={$member['member_id']}&amp;field=topics" base="public"}" title="{$this->lang->words['click_toggle']}"><span style='color:green'>{$this->lang->words['ucp_hide_allow']}</span></a>
					</if>
				</td>
				<td class='short'>
					<if test="ignoreGlobal:|:! $this->memberData['view_sigs']">
						<span class='desc'>{$this->lang->words['ucp_ignore_sigs_glb']}</span>
					<else />
						<if test="ignoreMemberSigs:|:$member['ignoreData']['ignore_signatures'] == 1">
							<a class='ipsButton_secondary' href="{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=toggleIgnoredUser&amp;do=saveIt&amp;id={$member['member_id']}&amp;field=signatures" base="public"}" title="{$this->lang->words['click_toggle']}"><span style='color:red'>{$this->lang->words['ucp_hide_disallow']}</span></a>
						<else />
							<a class='ipsButton_secondary' href="{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=toggleIgnoredUser&amp;do=saveIt&amp;id={$member['member_id']}&amp;field=signatures" base="public"}" title="{$this->lang->words['click_toggle']}"><span style='color:green'>{$this->lang->words['ucp_hide_allow']}</span></a>
						</if>
					</if>
				</td>
				<td class='short'>
					<if test="ignoreMemberPms:|:$member['ignoreData']['ignore_messages'] == 1">
						<a class='ipsButton_secondary' href="{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=toggleIgnoredUser&amp;do=saveIt&amp;id={$member['member_id']}&amp;field=messages" base="public"}" title="{$this->lang->words['click_toggle']}"><span style='color:red'>{$this->lang->words['ucp_hide_disallow_msg']}</span></a>
					<else />
						<a class='ipsButton_secondary' href="{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=toggleIgnoredUser&amp;do=saveIt&amp;id={$member['member_id']}&amp;field=messages" base="public"}" title="{$this->lang->words['click_toggle']}"><span style='color:green'>{$this->lang->words['ucp_hide_allow_msg']}</span></a>
					</if>
				</td>
				<if test="hasChatRow:|:IPSLib::appIsInstalled('ipchat')">
					<td class='short'>
						<if test="ignoreUserchats:|:$member['ignoreData']['ignore_chats'] == 1">
							<a class='ipsButton_secondary' href="{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=toggleIgnoredUser&amp;do=saveIt&amp;id={$member['member_id']}&amp;field=chats" base="public"}" title="{$this->lang->words['click_toggle']}"><span style='color:red'>{$this->lang->words['ucp_hide_disallow_msg']}</span></a>
						<else />
							<a class='ipsButton_secondary' href="{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=toggleIgnoredUser&amp;do=saveIt&amp;id={$member['member_id']}&amp;field=chats" base="public"}" title="{$this->lang->words['click_toggle']}"><span style='color:green'>{$this->lang->words['ucp_hide_allow_msg']}</span></a>
						</if>
					</td>
				</if>
				<td class='short'><a href="{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=removeIgnoredUser&amp;do=saveIt&amp;id={$member['member_id']}" base="public"}" class='ipsButton_secondary' class='delete_ignored'>{$this->lang->words['mi5_remove']}</a></td>
			</tr>
		</foreach>
	<else />
		<tr>
			<td colspan='<if test="hasChatNone:|:IPSLib::appIsInstalled('ipchat')">7<else />6</if>' class='no_messages desc'>{$this->lang->words['no_ignored_users']}</td>
		</tr>
	</if>
</table>
<if test="bottomPagination:|:$pagination">
	<br />
	{$pagination}
	<br class='clear' />
</if>
<br />
<div class='row2 ipsPad'>
	<h3 class='ipsType_subtitle' style='margin-bottom: 15px'>{$this->lang->words['mi5_addem']}</h3>
	<input type="text" class='input_text' size='40' name="newbox_1" id="newbox_1" value="{$this->request['newbox_1']}" />
	 &nbsp;&nbsp;<strong>{$this->lang->words['ucp_add_prefix']}</strong>&nbsp;
	<input type='checkbox' class='input_check' name='ignore_topics' id='ignore_topics' value='1' />
	<label class='desc' for='ignore_topics'>{$this->lang->words['ucp_ignore_posts']}</label>
	&nbsp;&nbsp;
	<input type='checkbox' class='input_check' name='ignore_signatures' id='ignore_signatures' value='1' />
	<label class='desc' for='ignore_signatures'>{$this->lang->words['ucp_ignore_sigs']}</label>
	&nbsp;&nbsp;
	<input type='checkbox' class='input_check' name='ignore_messages' id='ignore_messages' value='1' />
	<label class='desc' for='ignore_messages'>{$this->lang->words['ucp_ignore_pc']}</label>
	<if test="hasChatRowCheckbox:|:IPSLib::appIsInstalled('ipchat')">
		&nbsp;&nbsp;
		<input type='checkbox' class='input_check' name='ignore_chats' id='ignore_chats' value='1' />
		<label class='desc' for='ignore_chats'>{$this->lang->words['ucp_ignore_chats']}</label>
	</if>
</div>
<script type="text/javascript">
	$('newbox_1').defaultize( "{$this->lang->words['ucp_members_name']}" );
	
	ipb.delegate.register('.delete_ignored', confirmIgnoredDelete);
	
	var confirmIgnoredDelete = function(e, elem){
		if( !confirm("{$this->lang->words['ignore_del_areusure']}") ){
			Event.stop(e);
		}
	};
	
	document.observe("dom:loaded", function(){
		var url = ipb.vars['base_url'] + 'app=core&module=ajax&section=findnames&do=get-member-names&secure_key=' + ipb.vars['secure_hash'] + '&name=';
		new ipb.Autocomplete( $('newbox_1'), { multibox: false, url: url, templates: { wrap: ipb.templates['autocomplete_wrap'], item: ipb.templates['autocomplete_item'] } } );
	});
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: membersProfileCustomize
//===========================================================================
function membersProfileCustomize($options, $input, $errors) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/colorpicker/jscolor.js'></script>
<h3 class='ipsType_subtitle ipsSettings_pagetitle'>{$this->lang->words['ucp_cust_bg']}</h3>
<p class='ipsType_pagedesc'>{$this->lang->words['ucp_customize_msg']}</p>
<br />
<if test="$input['_preview']">
	<fieldset class='ipsSettings_section'>
		<h3 class='ipsSettings_sectiontitle'>{$this->lang->words['ucp_cust_bg_preview']}</h3>
		<div>
			<div style='width:98%; height: 100px; background-image:url("{$input['_preview']}");<if test="!$options['bg_tile']">background-repeat:no-repeat</if>'>&nbsp;</div>
		</div>
	</fieldset>
</if>
<fieldset class='ipsSettings_section'>
	<h3 class='ipsSettings_sectiontitle'>{$this->lang->words['ucp_cust_remove_t']}</h3>
	<div>
		<a href="{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=customize&amp;do=save&amp;secure_hash={$this->member->form_hash}&amp;bg_nix=1" base="public"}" class="ipsButton_secondary">{$this->lang->words['ucp_cust_remove']}</a>
	</div>
</fieldset>
<if test="$this->memberData['gbw_allow_url_bgimage'] OR $this->memberData['gbw_allow_upload_bgimage']">
	<fieldset class='ipsSettings_section'>
		<h3 class='ipsSettings_sectiontitle'>{$this->lang->words['ucp_cust_bg_title']}</h3>
		<div>
			<ul>
				<if test="$this->memberData['gbw_allow_url_bgimage']">
					<li>
						<label for='bg_img' class='ipsSettings_fieldtitle'>{$this->lang->words['ucp_cust_bg_image']}</label>
						<input type="text" name="bg_url" id='bg_url' class='input_text' size='50' value="{$input['bg_url']}" /><br />
						<p class='desc lighter'>{$this->lang->words['ucp_cust_bg_image_img_d']}</p>
					</li>
				</if>
				<if test="$this->memberData['gbw_allow_upload_bgimage']">
					<li>
						<label for='bg_img' class='ipsSettings_fieldtitle'>{$this->lang->words['ucp_cust_bg_image_upl']}</label>
						<input type="file" name="bg_upload" id='bg_upload' value="" size="70" /><br />
						<p class='desc lighter'>{parse expression="sprintf($this->lang->words['ucp_cust_bg_image_upl_d'], intval($this->memberData['g_max_bgimg_upload']) ? intval($this->memberData['g_max_bgimg_upload']) : $this->lang->words['pf__unlimited'] )"}</p>
					</li>
				</if>
			</ul>
		</div>
	</fieldset>
	<fieldset class='ipsSettings_section'>
		<h3 class='ipsSettings_sectiontitle'>{$this->lang->words['background_options']}</h3>
		<div>
			<ul>
				<if test="$this->memberData['gbw_allow_url_bgimage'] OR $this->memberData['gbw_allow_upload_bgimage']">
					<li>
						<input type="checkbox" name="bg_tile" id='bg_tile' class='input_check' value="1" <if test="$input['bg_tile']">checked='checked'</if>/> &nbsp;<label for='bg_tile'>{$this->lang->words['ucp_cust_bg_tile']}</label>
					</li>
				</if>
				<li>
					<label for='bg_color' class='ipsSettings_fieldtitle'>{$this->lang->words['ucp_cust_bg_color']} </label>
					#<input type="text" name="bg_color" id='bg_color' class='input_text color' size='10' value="{$input['bg_color']}" />
					<p class="desc lighter">{$this->lang->words['ucp_cust_bg_color_d']}</p>
				</li>
			</ul>
		</div>
	</fieldset>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: membersProfileForm
//===========================================================================
function membersProfileForm($custom_fields='',$group_titles='',$day='',$mon='',$year='', $amEditor='', $times=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h2 class='ipsType_subtitle ipsSettings_pagetitle'>{$this->lang->words['general_account_settings']}</h2>
<div class='ipsSettings'>
	<if test="canUploadPhoto:|:IPSMember::canUploadPhoto( $this->memberData )">
		<fieldset class='ipsSettings_section'>
			<h3 class='ipsSettings_sectiontitle'>{$this->lang->words['ucp_photo_title']}</h3>
			<div>
				<a data-clicklaunch="launchPhotoEditor" href="{parse url="app=members&amp;module=profile&amp;section=photo" base="public"}" class='ipsButton_secondary'>{$this->lang->words['ucp_photo_change']}</a>
			</div>
		</fieldset>
	</if>
	
	<fieldset class='ipsSettings_section'>
		<h3 class='ipsSettings_sectiontitle'>{$this->lang->words['settings_time']}</h3>
		<div>
			<ul class='ipsForm ipsForm_horizontal'>
				<li class='ipsField'>
					<label for='timezone'>{$this->lang->words['ucp_timzeone']}</label>
					<select name='timeZone' id='timezone' class='input_select'>
					<foreach loop="timezones:$times as $off => $words">
						<option value='{$off}' <if test="isOurTimezone:|:$off == $this->memberData['time_offset']"> selected="selected"</if>>{$words}</option>
					</foreach>
					</select><br />
					<span class='desc lighter'>{$this->lang->words['settings_time_txt2']} {parse date="" format="LONG" relative="false"}</span>
				</li>
				<if test="$this->settings['time_dst_auto_correction']">
					<li>
						<if test="dstError:|:$this->request['dsterror'] == 1">
							{$this->lang->words['dst_error']}
						</if>
						<input type='checkbox' class='input_check' id='dst' name="dstCheck" onclick='toggle_dst()' value="1"<if test="doAutoDst:|:$this->memberData['members_auto_dst']"> checked="checked"</if>/> &nbsp;<label for='dst'>{$this->lang->words['dst_correction_title']}</label>
					</li>
					<li id='dst-manual'>
						<input type='checkbox' class='input_check' id='dstManual' name="dstOption" value="1"<if test="doManualDst:|:$this->memberData['dst_in_use']"> checked="checked"</if>/> &nbsp;<label for='dstManual'>{$this->lang->words['ucp_dst_now']}</label>
					</li>
				<else />
					<li>
						<input type='checkbox' class='input_check' id='dstManual' name="dstOption" value="1"<if test="doManualDst:|:$this->memberData['dst_in_use']"> checked="checked"</if>/> &nbsp;<label for='dstManual'>{$this->lang->words['ucp_dst_now']}</label><br />
						<span class='desc lighter'>{$this->lang->words['ucp_dst_effect']}</span>
					</li>
				</if>
			</ul>
		</div>
	</fieldset>
	<fieldset class='ipsSettings_section'>
		<h3 class='ipsSettings_sectiontitle'>{$this->lang->words['ucp_comments']}</h3>
		<div>
			<ul>
				<li>
					<input class='input_check' type='checkbox' value='1' name='pp_setting_count_comments' value='1' <if test="showComments:|:$this->memberData['pp_setting_count_comments'] > 0">checked='checked'</if> id='comments_enable' /> &nbsp;<label for='comments_enable'>{$this->lang->words['ucp_enable_comments']}</label>
				</li>
				<!-- proposing removal -->
				<li id='approve_comments'>
					<input class='input_check' type='checkbox' value='1'  name='pp_setting_moderate_comments' id='pp_setting_moderate_comments' <if test="yesModComments:|:$this->memberData['pp_setting_moderate_comments']">checked="checked"</if> /> &nbsp;<label for='pp_setting_moderate_comments'>{$this->lang->words['op_dd_enabled']}</label>
				</li>
				<li>
					<input class='input_check' type='checkbox' value='1' name='pp_setting_count_visitors' value='1' <if test="showLastVisitors:|:$this->memberData['pp_setting_count_visitors'] > 0">checked='checked'</if> id='pp_latest_visitors' /> &nbsp;<label for='pp_latest_visitors'>{$this->lang->words['ucp_show_x_latest']}</label>
				</li>
			</ul>
		</div>
	</fieldset>
	<if test="friendsEnabled:|:$this->settings['friends_enabled'] AND $this->memberData['g_can_add_friends']">
		<fieldset class='ipsSettings_section'>
			<h3 class='ipsSettings_sectiontitle'>{$this->lang->words['ucp_friends']}</h3>
			<div>
				<ul>
					<li>
						<input class='input_check' type='checkbox' value='1' name='pp_setting_count_friends' value='1' <if test="showFriends:|:$this->memberData['pp_setting_count_friends'] > 0">checked='checked'</if> id='friends_enable' /> &nbsp;<label for='friends_enable'>{$this->lang->words['ucp_show_friends_profile']}</label>
					</li>
					<li>
						<input class='input_check' type='checkbox' value='1' name='pp_setting_moderate_friends' id='pp_setting_moderate_friends' <if test="yesModFriends:|:$this->memberData['pp_setting_moderate_friends']">checked="checked"</if> /> &nbsp;<label for='pp_setting_moderate_friends'>{$this->lang->words['ucp_friend_approve']}</label>
					</li>
				</ul>
			</div>
		</fieldset>
	</if>
	<if test="showProfileInfo:|:($day && $mon && $year) || ($this->settings['post_titlechange'] && ($this->memberData['posts'] >= $this->settings['post_titlechange']))">
		<fieldset class='ipsSettings_section'>
			<h3 class='ipsSettings_sectiontitle'>{$this->lang->words['profile_information']}</h3>
			<div>
				<ul>
					<li><a href='#' class='ipsButton_secondary' id='edit_aboutme'>{$this->lang->words['edit_my_about_me']}</a></li>
				</ul>
				<if test="changeMemberTitle:|:$this->settings['post_titlechange'] && ($this->memberData['posts'] >= $this->settings['post_titlechange'])">
					<br />
					<ul>
						<li>
							<label for='member_title' class='ipsSettings_fieldtitle'>{$this->lang->words['member_title']}</label>
							<input type='text' class='input_text' size='40' id='member_title' name='member_title' value='{$this->memberData['title']}' />
							<br />
							<span class='desc'>{$this->lang->words['member_title_desc']}</span>
						</li>
					</ul>
				</if>
				<if test="birthdayFields:|:$day AND $mon AND $year">
					<br />
					<ul>
						<li>
							<label for='birthday' class='ipsSettings_fieldtitle'>{$this->lang->words['ucp_birthday_select']}</label>
							<select name="month">&nbsp;
								<foreach loop="months:$mon as $m">
									<option value='{$m[0]}'<if test="monthSelected:|:$m[0] == $this->memberData['bday_month']"> selected="selected"</if>>{$m[1]}</option>
								</foreach>
							</select>			
							<select name="day">&nbsp;
								<foreach loop="days:$day as $d">
									<option value='{$d[0]}'<if test="daySelected:|:$d[0] == $this->memberData['bday_day']"> selected="selected"</if>>{$d[1]}</option>
								</foreach>
							</select> 
							<select name="year">&nbsp;
								<foreach loop="years:$year as $y">
									<option value='{$y[0]}'<if test="yearSelected:|:$y[0] == $this->memberData['bday_year']"> selected="selected"</if>>{$y[1]}</option>
								</foreach>
							</select> <br />
							<span class='desc'>{$this->lang->words['ucp_birthday_optional']}</span>
						</li>
					</ul>
				</if>
			</div>
		</fieldset>
	</if>
	
	<if test="count( $custom_fields )">
		<foreach loop="$custom_fields as $cgroup => $cfields">
			<if test="count( $cfields )">
				<fieldset class='ipsSettings_section'>
					<h3 class='ipsSettings_sectiontitle'>{$group_titles[ $cgroup ]}</h3>
					<div>
						<ul>
							<foreach loop="$cfields as $fid => $cfield">
								{$cfield['field']}
							</foreach>
						</ul>
					</div>
				</fieldset>
			</if>
		</foreach>
	</if>
	
	<div id='aboutme_editor' style='display: none'>
		<h3>{$this->lang->words['cp_edit_aboutme']}</h3>
		<div>
			{$amEditor}
		</div>
		<div class='ipsForm_submit' style='margin-top: 0;'>
			<a href='#' id='close_aboutme_editor' class='ipsButton'>{$this->lang->words['finish_aboutme_edit']}</a>
		</div>
	</div>
	<if test="requiredCfields:|:$required_output">
		<fieldset class='{parse striping="usercp"}'>
			<h3>{$this->lang->words['ucp_required_info']}</h3>
			<ul>
				{$required_output}
			</ul>
		</fieldset>
	</if>
	<if test="optionalCfields:|:$optional_output">
		<fieldset class='{parse striping="usercp"}'>
			<h3>{$this->lang->words['ucp_other_info']}</h3>
			<ul>
				{$optional_output}
			</ul>
		</fieldset>
	</if>
</div>
<script type="text/javascript">
//<![CDATA[
function toggle_dst()
{
	if ( $( 'dst' ) )
	{
		if ( $( 'dst' ).checked ){
			$( 'dst-manual' ).style.display = 'none';
		} else {
			$( 'dst-manual' ).style.display = 'block';
		}
	}
}
toggle_dst();
//]]>
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: membersSignatureForm
//===========================================================================
function membersSignatureForm($editor_html="",$sig_restrictions=array(),$signature='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3 class='ipsType_subtitle'>{$this->lang->words['cp_current_sig']}</h3>
<div class='row1 signature'>{$signature}</div>
<br />
<input type='hidden' name='removeattachid' value='0' />
{parse striping="usercp" classes="row1,row2"}
<fieldset class='{parse striping="usercp"}'>
	<h3 class='ipsType_subtitle'>{$this->lang->words['cp_edit_sig']}</h3>
	<if test="hasSignatureLimits:|:$this->memberData['g_signature_limits']">
		<div class='ipsType_pagedesc'>
			{$this->lang->words['sig_restrictions_contain']}<br />
			<ul class='ipsList_inline' style='display: inline'>
				<if test="$sig_restrictions[1] !== ''">
					<li>&bull; {parse expression="sprintf( $this->lang->words['sig_max_imagesr'], $sig_restrictions[1] )"}</li>
				<else />
					<li>&bull; {$this->lang->words['sig_max_imagesr_nl']}</li>
				</if>
				<if test="$sig_restrictions[2] !== '' || $sig_restrictions[3] !== ''">
					<li>&bull; {parse expression="sprintf( $this->lang->words['sig_max_imgsize'], $sig_restrictions[2], $sig_restrictions[3] )"}</li>
				<else />
					<li>&bull; {$this->lang->words['sig_max_imgsize_nl']}</li>
				</if>
				<if test="$sig_restrictions[4] !== ''">
					<li>&bull; {parse expression="sprintf( $this->lang->words['sig_max_urls'], $sig_restrictions[4] )"}</li>
				<else />
					<li>&bull; {$this->lang->words['sig_max_urls_nl']}</li>
				</if>
				<if test="$sig_restrictions[5] !== ''">
					<li>&bull; {parse expression="sprintf( $this->lang->words['sig_max_lines'], $sig_restrictions[5] )"}</li>
				<else />
					<li>&bull; {$this->lang->words['sig_max_lines_nl']}</li>
				</if>
			</ul>
		</div><br />
	</if>
	<div>
		{$editor_html}
	</div>
</fieldset>
<if test="disablelightbox:|:!$this->settings['disable_lightbox']">
{parse template="include_lightbox" group="global" params=""}
</if>
{parse template="include_highlighter" group="global" params="1"}
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: membersSignatureFormError
//===========================================================================
function membersSignatureFormError($form) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<fieldset class='row1'>
	<h3>{$this->lang->words['cp_edit_sig']}</h3>
	<br />
	<if test="$form['_noPerm']">
		<p class='message error'>{$form['_noPerm']}</p>
	</if>
</fieldset>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: membersTwitterConnect
//===========================================================================
function membersTwitterConnect($isConnected, $twitterUser=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse striping="usercp" classes="row1,row2"}
<if test="! $isConnected">
<div>
	<p class='message'>{$this->lang->words['twitter_config']}</p>
	<br />
	<br />
	<a href="{$this->settings['_original_base_url']}/interface/twitter/index.php"><img src="{$this->settings['img_url']}/twitter_connect.png" alt="" /></a>
	<br />
</div>
<else />
<div>
	<div class='ipsPad row2 clearfix'>
		<if test="tcHasPic:|:$twitterUser['profile_image_url']">	
			<img src="{$twitterUser['profile_image_url']}" alt='' class='left ipsUserPhoto ipsUserPhoto_medium' />
			<div class='ipsBox_withphoto'>
		</if>
		<h2 class='ipsType_subtitle'>
			{$this->lang->words['tc_logged_in_as']} <strong>{$twitterUser['screen_name']}</strong> ({$twitterUser['name']})
		</h2>
		<if test="tcShowStatus:|:is_array($twitterUser['status']) AND $twitterUser['status']['text']">
			<p class='desc'>{$twitterUser['status']['text']}</p>
		</if>
		<if test="tcHasPic:|:$twitterUser['profile_image_url']">
			</div>
		</if>
	</div>
	
	<br />
	<div class='right row2 ipsPad clearfix' style='width: 250px'>
		<span class='ipsType_smaller'>
			{$this->lang->words['tc_last_syncd']}
			<if test="tcLastSync:|:$this->memberData['tc_lastsync']">
				{parse date="$this->memberData['tc_lastsync']" format="long"}
			<else />
				{$this->lang->words['fb_never']}
			</if>
		</span>
		<br /><br />
		<input type='submit' class='ipsButton_secondary' id='tc_sync' value='{$this->lang->words['fb_sync_now']}' />
		<br /><br />
		<input type='button' class='ipsButton_secondary' id='tc_remove_start' value='{$this->lang->words['tc_disassociate']}' />
		
		<div id='tc_remove_popup' style='display: none'>
			<h3>{$this->lang->words['tc_disassociate']}</h3>
			<div class='ipsPad'>
				<p>{$this->lang->words['twitter_revoke']}</p>
				<p class='desc'>{$this->lang->words['tc_disassociate_desc']}</p>
				<br />
				<input type='button' class='ipsButton' id='tc_remove' value='{$this->lang->words['tc_disassociate_now']}' />
			</div>
		</div>
	</div>
	<div>
		<h2 class='ipsType_subtitle'>{$this->lang->words['tc_sync_options']}</h2>
		<p class='desc'>{$this->lang->words['tc_sync_options_desc']}</p>
		<ul class='ipsForm ipsForm_vertical ipsPad'>
			<li class='ipsField ipsField_checkbox'>
				<input class='input_check' type='checkbox' value='1' name='tc_s_pic' value='1' <if test="tcSyncPic:|:$this->memberData['tc_s_pic'] > 0">checked='checked'</if> id='tc_s_pic' />
				<p class='ipsField_content'>
					<label for='tc_s_pic'>{$this->lang->words['tc_sync_photo']}</label><br />
					<span class='desc lighter'>{$this->lang->words['tc_sync_photo_info']}</span>
				</p>
			</li>
			<li class='ipsField ipsField_checkbox'>
				<input class='input_check' type='checkbox' value='1' name='tc_s_status' value='1' <if test="tcSyncStatus:|:$this->memberData['tc_s_status'] > 0">checked='checked'</if> id='tc_s_status' />
				<p class='ipsField_content'>
					<label for='tc_s_status'>{$this->lang->words['tc_sync_status_out']}</label>
				</p>
			</li>
			<if test="statusImportGroup:|: ! $this->memberData['gbw_no_status_import']">
			<li class='ipsField ipsField_checkbox'>
				<input class='input_check' type='checkbox' value='1' name='tc_si_status' value='1' <if test="tcSyncIStatus:|:$this->memberData['tc_si_status'] > 0">checked='checked'</if> id='tc_si_status' />
				<p class='ipsField_content'>
					<label for='tc_si_status'>{$this->lang->words['tc_sync_status_in']}</label>
				</p>
			</li>
			</if>
			<li class='ipsField ipsField_checkbox'>
				<input class='input_check' type='checkbox' value='1' name='tc_s_aboutme' value='1' <if test="tcSyncAboutMe:|:$this->memberData['tc_s_aboutme'] > 0">checked='checked'</if> id='tc_s_aboutme' />
				<p class='ipsField_content'>
					<label for='tc_s_aboutme'>{$this->lang->words['tc_sync_aboutme']}</label>
				</p>
			</li>
			<if test="canBG:|:$this->memberData['gbw_allow_customization'] AND ! $this->memberData['bw_disable_customization']">
				<li class='ipsField ipsField_checkbox'>
					<input class='input_check' type='checkbox' value='1' name='tc_s_bgimg' value='1' <if test="tcSyncBgImg:|:$this->memberData['tc_s_bgimg'] > 0">checked='checked'</if> id='tc_s_bgimg' />
					<p class='ipsField_content'>
						<label for='tc_s_bgimg'>{$this->lang->words['tc_sync_bgimg']}</label>
					</p>
				</li>
			</if>
		</ul>
	</div>
	
	<if test="twitterPassword:|:! $this->memberData['bw_local_password_set'] AND $this->memberData['members_created_remote']">
		<div class='message'>
			<strong>{$this->lang->words['remote_no_password']}</strong>
			<p>{$this->lang->words['remote_no_password_1']} <a href='{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=email" base="public"}'>{$this->lang->words['remote_no_password_2']}</a>
			</p>
		</div>
		<br />
	</if>
	
</div>
<script type="text/javascript">
	$('tc_remove').observe( 'click', usercp_remove );
	function usercp_remove(){
		window.location = ipb.vars['base_url'] + 'app=core&module=usercp&tab=core&area=twitterRemove&do=custom&secure_key=' + ipb.vars['secure_hash'];
	}
	
	$('tc_remove_start').on('click', function(e){
		new ipb.Popup( 'sign_in_popup', {	type: 'pane',
											initial: $('tc_remove_popup').show(),
											hideAtStart: false,
											hideClose: false,
											modal: true,
											w: '600px' } );
	});
</script>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: notificationsForm
//===========================================================================
function notificationsForm($config, $emailData) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3 class='ipsType_subtitle'>{$this->lang->words['board_prefs']}</h3>
<fieldset class='row1'>
	<h3>{$this->lang->words['privacy_settings']}</h3>
	<ul>
		<li class='field checkbox'>
			<input type='checkbox' class='input_check' id='admin_updates' name='admin_send' value='1'<if test="allowAdminMails:|:$this->memberData['allow_admin_mails']"> checked="checked"</if>/> <label for='admin_updates'>{$this->lang->words['admin_send']}</label><br />
			<span class='desc lighter'>{$this->lang->words['admin_send_desc']} {$time}</span>
		</li>
	</ul>
</fieldset>
<fieldset class='row1'>
	<span class='desc'><strong>{$this->lang->words['notifications_info_acp']}</strong></span>
	<php>
		$this->notifyGroups = array(
			'topics_posts' => array( 'followed_topics', 'followed_forums', 'followed_topics_digest', 'followed_forums_digest', 'post_quoted', 'new_likes' ),
			'status_updates' => array( 'reply_your_status', 'reply_any_status', 'friend_status_update' ),
			'profiles_friends' => array( 'profile_comment', 'profile_comment_pending', 'friend_request', 'friend_request_pending', 'friend_request_approve' ),
			'private_msgs' => array( 'new_private_message', 'reply_private_message', 'invite_private_message' )
		);
		
		$this->_config = $config;
		
		$this->_colCount = IPSMember::canReceiveMobileNotifications() ? 4 : 3;
		
		$this->_lastApp	= '';
	</php>
	<table class='ipb_table notification_table'>
		<tr>
			<th>&nbsp;</th>
			<th style='width: 15%' class='short'><span class='notify_icon inline'>&nbsp;</span> {$this->lang->words['notify_type_inline']}</th>
			<th style='width: 15%' class='short'><span class='notify_icon email'>&nbsp;</span> {$this->lang->words['notify_type_email']}</th>
			<if test="IPSMember::canReceiveMobileNotifications()">
			<th style='width: 15%' class='short'><span class='notify_icon mobile'>&nbsp;</span> {$this->lang->words['notify_type_mobile']}</th>
			</if>
		</tr>
		<foreach loop="notifyGroupsList:$this->notifyGroups as $groupKey => $group">
			<tr class='row2'>
				<td colspan='{$this->_colCount}'>
					<h3>{$this->lang->words[ 'notifytitle_' . $groupKey ]}</h3>
					<if test="isPrivateMsg:|:$groupKey == 'private_msgs'">
						<p class='ipsPad_half checkbox ipsType_smaller desc '>
							<input type='checkbox' class='input_check' id='show_notification_popup' name='show_notification_popup' value='1' <if test="$this->memberData['_cache']['show_notification_popup']">checked='checked'</if> /> <label for='show_notification_popup' />{$this->lang->words['show_notification_popup']}</label><br />
						</p>
					</if>
					<if test="isTopicsOrPosts:|:$groupKey == 'topics_posts'">
						<p class='ipsPad_half checkbox ipsType_smaller desc '>
							<input class='input_check' type="checkbox" id='auto_track' name="auto_track" value="1" {$emailData['auto_track']} /> <label for='auto_track' />{$this->lang->words['auto_track']}</label>
							<select name="trackchoice" id='track_choice' class='input_select'>
								<option value="none" {$emailData['trackOption']['none']}>{$this->lang->words['like_notify_freq_none']}</option>
								<option value="immediate" {$emailData['trackOption']['immediate']}>{$this->lang->words['like_notify_freq_immediate']}</option>
								<option value="offline" {$emailData['trackOption']['offline']}>{$this->lang->words['like_notify_freq_offline']}</option>
								<option value="daily" {$emailData['trackOption']['daily']}>{$this->lang->words['like_notify_freq_daily']}</option>
								<option value="weekly" {$emailData['trackOption']['weekly']}>{$this->lang->words['like_notify_freq_weekly']}</option>
							</select>
							<if test="badConfig:|:$emailData['auto_track'] AND $emailData['trackOption']['none']">
								<br />{$this->lang->words['auto_but_no_email']}
							</if>
						</p>
					</if>
				</td>
			</tr>
			<foreach loop="groupKeys:$group as $key">
				<if test="keyExists:|:$this->_config[ $key ]">
					<tr>
						<td class='notify_title desc'>{$this->lang->words['notify__' . $key]}</td>
						<td class='short'>
							<span class='notify_icon inline' title='{$this->lang->words['notify_type_inline']}'>&nbsp;</span>
							<if test="$groupKey == 'private_msgs'">
								<input type='checkbox' class='input_check' name='' checked='checked' disabled='disabled' /> <span class='ipsBadge ipsBadge_lightgrey ipsType_smaller' data-tooltip='{$this->lang->words['nots_pm_whatthef']}'>{$this->lang->words['nots_pm_list']}</span>
							<else />
								<if test="isset( $this->_config[$key]['options']['inline'] ) && $groupKey != 'private_msgs'">
									<input type='checkbox' class='input_check' id='inline_{$key}' name="config_{$key}[]" value="inline"<if test="hasconfignotify:|:is_array($this->_config[$key]['defaults']) AND in_array('inline',$this->_config[$key]['defaults'])"> checked="checked"</if> <if test="hasconfigdisable:|:$this->_config[$key]['disabled']"> disabled="disabled"</if> />
								<else />
									<input type='checkbox' class='input_check' name='' disabled='disabled' />
								</if>
							</if>
						</td>
						<td class='short'>
							<span class='notify_icon email' title='{$this->lang->words['notify_type_email']}'>&nbsp;</span>
							<if test="isset( $this->_config[$key]['options']['email'] )">
								<input type='checkbox' class='input_check' id='email_{$key}' name="config_{$key}[]" value="email"<if test="hasconfignotify:|:is_array($this->_config[$key]['defaults']) AND in_array('email',$this->_config[$key]['defaults'])"> checked="checked"</if> <if test="hasconfigdisable:|:$this->_config[$key]['disabled']"> disabled="disabled"</if> />
							<else />
								<input type='checkbox' class='input_check' name='' disabled='disabled' />
							</if>
						</td>
						<if test="IPSMember::canReceiveMobileNotifications()">
						<td class='short'>
							<span class='notify_icon mobile' title='{$this->lang->words['notify_type_mobile']}'>&nbsp;</span>
							<if test="isset( $this->_config[$key]['options']['mobile'] )">
								<input type='checkbox' class='input_check' id='mobile_{$key}' name="config_{$key}[]" value="mobile"<if test="hasconfignotify:|:is_array($this->_config[$key]['defaults']) AND in_array('mobile',$this->_config[$key]['defaults'])"> checked="checked"</if> <if test="hasconfigdisable:|:$this->_config[$key]['disabled']"> disabled="disabled"</if> />
							<else />
								<input type='checkbox' class='input_check' name='' disabled='disabled' />
							</if>
						</td>
						</if>
					</tr>
					<if test="$this->_config[$key]['_done'] = 1"></if>
				</if>
			</foreach>							
		</foreach>
		<foreach loop="notifyOther:$this->_config as $key => $_config">
			<if test="keyNotDone:|:!isset( $_config['_done'] ) && $_config['_done'] != 1">
				<if test="newNotApp:|:$this->_lastApp != $_config['app']">
					<tr class='row2'>
						<td colspan='{$this->_colCount}'>
							<h3><if test="isCoreNot:|:$_config['app'] == 'core'">{$this->lang->words['notifytitle_other']}<else />{IPSLib::getAppTitle( $_config['app'] )}</if></h3>
						</td>
					</tr>
					<if test="updateLastApp:|:$this->_lastApp = $_config['app']"></if>
				</if>
				<tr>
					<td class='notify_title desc'>{$this->lang->words['notify__' . $_config['key'] ]}</h3></td>
					<td class='short'>
						<span class='notify_icon inline' title='{$this->lang->words['notify_type_inline']}'>&nbsp;</span>
						<if test="isset( $_config['options']['inline'] )">
							<input type='checkbox' class='input_check' id='inline_{$key}' name="config_{$key}[]" value="inline"<if test="hasconfignotify:|:is_array($_config['defaults']) AND in_array('inline',$_config['defaults'])"> checked="checked"</if> <if test="hasconfigdisable:|:$_config['disabled']"> disabled="disabled"</if> />
						<else />
							<input type='checkbox' class='input_check' name='' disabled='disabled' />
						</if>
					</td>
					<td class='short'>
						<span class='notify_icon email' title='{$this->lang->words['notify_type_email']}'>&nbsp;</span>
						<if test="isset( $_config['options']['email'] )">
							<input type='checkbox' class='input_check' id='email_{$key}' name="config_{$key}[]" value="email"<if test="hasconfignotify:|:is_array($_config['defaults']) AND in_array('email',$_config['defaults'])"> checked="checked"</if> <if test="hasconfigdisable:|:$_config['disabled']"> disabled="disabled"</if> />
						<else />
							<input type='checkbox' class='input_check' name='' disabled='disabled' />
						</if>
					</td>
					<if test="IPSMember::canReceiveMobileNotifications()">
					<td class='short'>
						<span class='notify_icon mobile' title='{$this->lang->words['notify_type_mobile']}'>&nbsp;</span>
						<if test="isset( $_config['options']['mobile'] )">
							<input type='checkbox' class='input_check' id='mobile_{$key}' name="config_{$key}[]" value="mobile"<if test="hasconfignotify:|:is_array($this->_config[$key]['defaults']) AND in_array('mobile',$this->_config[$key]['defaults'])"> checked="checked"</if> <if test="hasconfigdisable:|:$this->_config[$key]['disabled']"> disabled="disabled"</if> />
						<else />
							<input type='checkbox' class='input_check' name='' disabled='disabled' />
						</if>
					</td>
					</if>
				</tr>
			</if>
		</foreach>
	</table>
</fieldset>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: notificationsLog
//===========================================================================
function notificationsLog($notifications, $error='', $pages='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="hasnotifyerror:|:$error">
<p class='message error'>
	{$error}
</p>
<else />
	<if test="hasconfirm:|:$this->request['confirm']">
	<p class='message'>
		{$this->lang->words['notify_rem_suc']}
	</p>
	</if>
</if>
<div id='notificationlog'>
	<form action="{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=removeNotifications&amp;do=remove" base="public"}" id="checkBoxForm" method="post">
	<input type="hidden" name="secure_key" value="{$this->member->form_hash}" />
	<if test="hasNotifications:|:is_array( $notifications ) AND count( $notifications )">
		<div class='right ipsPad'>
			<input id="checkAllNotifications" type="checkbox" value="{$this->lang->words['check_all']}" />
		</div>
		<h2 class='ipsType_subtitle'>{$this->lang->words['arch_notifications_head']}</h2>
		<br />
		<table class='ipb_table' summary="{$this->lang->words['notifications_table_head']}">
			<tr class='header hide'>
				<th scope='col' width="5%">&nbsp;</th>
				<th scope='col' width="70%">{$this->lang->words['th_notification']}</th>
				<th scope='col' width="25%">{$this->lang->words['th_sent']}</th>
				<th scope='col' align="center" width="5%" class='short'>&nbsp;</th>
			</tr>
			{parse striping="notify" classes="row2,row1"}
			<foreach loop="categories:$notifications as $notification">
				<tr class='{parse striping="notify"} <if test="hasReadNotify:|:!$notification['notify_read']">unread</if>'>
					<td class="col_n_icon altrow short"><if test="hasReadNotify:|:$notification['notify_read']">{parse replacement="t_read_dot"}<else />{parse replacement="t_unread_dot"}</if></td>
					<td class='col_n_photo short'>
						<if test="$notification['member']['member_id']">
							<a href='{parse url="showuser={$notification['member']['member_id']}" template="showuser" seotitle="{$notification['member']['members_seo_name']}" base="public"}' class='ipsUserPhotoLink'>
								<img src='{$notification['member']['pp_thumb_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' />
							</a>
						<else />
							{IPSMember::buildNoPhoto(0, 'mini' )}
						</if>
					</td>
					<td>
						<h4><if test="strpos( $notification['notify_title'], '<a href' ) === false"><a href='{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=viewNotification&amp;do=view&amp;view={$notification['notify_id']}" base="public"}'></if>
						{$notification['notify_title']}
						<if test="strpos( $notification['notify_title'], '<a href' ) === false"></a></if>
						</h4>
					</td>
					<td class="col_n_date desc"><a href='{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=viewNotification&amp;do=view&amp;view={$notification['notify_id']}" base="public"}' title='{$this->lang->words['view_notification_logentry']}'>{$this->lang->words['th_sent']} {parse date="$notification['notify_sent']" format="long"}</a></td>
					<td class="short col_n_mod"><input class='input_check checkall' type="checkbox" name="notifications[]" value="{$notification['notify_id']}" /></td>
				</tr>
			</foreach>
		</table>
		<script type='text/javascript'>
			ipb.global.registerCheckAll('checkAllNotifications', 'checkall');
		</script>
	<else />
		<p class='no_messages'>{$this->lang->words['notifications_none']}</p>
	</if>
	<if test="hasNotifyForMod:|:count($notifications)">
		<br />
		<a href='{parse url="app=core&amp;module=usercp&amp;area=markNotification&amp;do=mark&amp;mark=all" base="public"}' id='ack_pm_notification' class='input_submit left'>{$this->lang->words['notificationlog_mar']}</a>
		<div class='moderation_bar rounded with_action'>
			<input type="submit" class="input_submit alt" value="{$this->lang->words['ndel_selected']}" />
		</div>
		<br />
		<div class='topic_controls'>
			{$pages}
		</div>
	</if>
</form>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showNotification
//===========================================================================
function showNotification($notification) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<fieldset class='ipsPad row2'>
	<h2>{$notification['notify_title']}</h2>
	<ul>
		<li class='field'>
			{$this->lang->words['notifyview_date']}
			<em>{parse date="$notification['notify_sent']" format="long"}</em>
		</li>
	</ul>
</fieldset>
<fieldset class='ipsPad row1'>
	<ul>
		<li class='field'>
			{$notification['notify_text']}
		</li>
	</ul>
</fieldset>
<fieldset class='ipsPad row2'>
	<ul>
		<li class='field short'>
			<a href='{parse url="app=core&amp;module=usercp&amp;area=notificationlog" base="public"}' class='input_submit'>{$this->lang->words['goback']}</a>	
			<a href='{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=removeNotifications&amp;do=remove&amp;secure_key={$this->member->form_hash}&amp;notifications[]={$notification['notify_id']}" base="public"}' class='input_submit delete'>{$this->lang->words['deletenotification']}</a>
		</li>
	</ul>
</fieldset>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: userCPTemplate
//===========================================================================
function userCPTemplate($current_tab, $html, $tabs, $current_area, $errors=array(), $hide_form=0, $maxUpload=0) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="ucp"}
<php>
$hasMoreTabs = (is_array($tabs[ $current_tab ]['_menu']) && count($tabs[ $current_tab ]['_menu']) > 1) ? true : false;
</php>
<if test="usercp_form:|:$hide_form == 0">
	<if test="has_max_upload:|:$maxUpload">
		<form method='post' enctype="multipart/form-data" action='{parse url="app=core&amp;module=usercp&amp;tab={$current_tab}&amp;area={$current_area}" base="public"}' id='userCPForm'>
	<else />
		<form method='post' action='{parse url="app=core&amp;module=usercp&amp;tab={$current_tab}&amp;area={$current_area}" base="public"}' id='userCPForm'>
	</if>
</if>
	<fieldset>
		<input type="hidden" name="MAX_FILE_SIZE" value="{$maxUpload}" />
		<input type='hidden' name='do' value='save' />
		<input type='hidden' name='secure_hash' value='{$this->member->form_hash}' />
		<input type='hidden' name='s' value='{$this->request['s']}' />
	</fieldset>
<h1 class='ipsType_pagetitle'>{$this->lang->words['ucp_title']}</h1>
<br />
<if test="count($tabs) > 1">
	<div class='maintitle ipsFilterbar clearfix'>
		<ul class='ipsList_inline'>
			<foreach loop="tabs:$tabs as $tab_app => $tab">
				<if test="active_tab:|:$tab_app == $current_tab">
					<li class='active'><a href="{parse url="module=usercp&amp;tab={$tab_app}" base="publicWithApp"}" title="<if test="isSettings:|:$tab_app=='core'">{$this->lang->words['settings_for_coretab']}<else />{parse expression="sprintf( $this->lang->words['settings_for_ucp'], $tab['_name'] )"}</if>">{$tab['_name']}</a></li>
				<else />
					<li><a href="{parse url="module=usercp&amp;tab={$tab_app}" base="publicWithApp"}" title="<if test="isSettingsInactive:|:$tab_app=='core'">{$this->lang->words['settings_for_coretab']}<else />{parse expression="sprintf( $this->lang->words['settings_for_ucp'], $tab['_name'] )"}</if>">{$tab['_name']}</a></li>
				</if>
			</foreach>
		</ul>
	</div>
</if>
<div class='ipsBox'>
	<div class='ipsLayout<if test="hasMoreThanOneTabClass:|:$hasMoreTabs"> ipsLayout_withleft ipsLayout_smallleft ipsVerticalTabbed</if> clearfix usercp_body'>
		<if test="hasMoreThanOneTabSidebar:|:$hasMoreTabs">
			<div class='ipsVerticalTabbed_tabs ipsLayout_left' id='usercp_tabs'>
				<ul>
					<foreach loop="items:$tabs[ $current_tab ]['_menu'] as $idx => $item">
						<if test="tabsMenus_active:|:$item['area'] == $current_area OR $item['active']">
							<li class='active'><a href="{parse url="module=usercp&amp;tab={$current_tab}&amp;{$item['url']}" base="publicWithApp"}">{$item['title']}</a></li>
						<else />
							<li><a href="{parse url="module=usercp&amp;tab={$current_tab}&amp;{$item['url']}" base="publicWithApp"}">{$item['title']}</a></li>
						</if>
					</foreach>
				</ul>
			</div>
		</if>
		<div class='<if test="hasMoreThanOneTabClassContent:|:$hasMoreTabs">ipsVerticalTabbed_content </if>ipsLayout_content ipsBox_container' id='usercp_content'>
			<div class='ipsPad'>
				<if test="has_errors:|:is_array( $errors ) AND count( $errors )">
					<p class='message error'>
						<foreach loop="errors:$errors as $error">
							{$error}<br />
						</foreach>
					</p>
					<br />
				</if>
				<if test="didSave:|:$this->request['saved'] == 1">
					<p class='message'>{$this->lang->words['ucp__settings_saved']}</p>
					<br />
				</if>
				{$html}
		
				<if test="submit_button:|:$hide_form == 0">
				<fieldset class='submit'>
					<input type='submit' class='input_submit' name='submitForm' value='{$this->lang->words['ucp__save_changes']}' /> {$this->lang->words['or']} <a href='{parse url="app=core&amp;module=usercp&amp;tab={$current_tab}&amp;area={$current_area}" base="public"}' title='{$this->lang->words['cancel_edit']}' class='cancel'>{$this->lang->words['cancel']}</a>
				</fieldset>
				</if>
			</div>
		</div>
	</div>
</div>
<if test="end_form:|:$hide_form == 0">
</form>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>