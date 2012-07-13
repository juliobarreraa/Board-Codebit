<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: acknowledgeWarning
//===========================================================================
function acknowledgeWarning($warning) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{$this->lang->words['warnings_acknowledge']}</h1>
<br />
<p class='message'>{$this->lang->words['warnings_acknowledge_desc']}</p>
<br />
<form action="{parse url="app=members&module=profile&section=warnings&do=do_acknowledge" base="public"}" method="post">
	<input type='hidden' name='member' value='{$warning['wl_member']}' />
	<input type='hidden' name='id' value='{$warning['wl_id']}' />
	<input type='hidden' name='secure_key' value='{$this->member->form_hash}' />
	<input type='hidden' name='ref' value='{$_SERVER['HTTP_REFERER']}' />
	<div class='ipsBox'>
		<if test="memberNote:|:$warning['wl_note_member']">
			<div class='ipsBox_container ipsPad'>
				{$warning['wl_note_member']}
			</div>
			<br />
		</if>
		<div class='ipsBox_container ipsPad'>
			<if test="hasReason:|:$warning['wl_reason']">
				<if test="hasReasonAndContent:|:$warning['content']">
					{parse expression="sprintf( $this->lang->words['warning_blurb_yy'], "{parse template="userHoverCard" group="global" params="$warning['wl_moderator']"}", $warning['wl_reason']['wr_name'], $warning['content'] )"}
				<else />
					{parse expression="sprintf( $this->lang->words['warning_blurb_yn'], "{parse template="userHoverCard" group="global" params="$warning['wl_moderator']"}", $warning['wl_reason']['wr_name'] )"}
				</if>
			<else />
				<if test="hasContent:|:$warning['content']">
					{parse expression="sprintf( $this->lang->words['warning_blurb_ny'], "{parse template="userHoverCard" group="global" params="$warning['wl_moderator']"}", $warning['content'] )"}
				<else />
					{parse expression="sprintf( $this->lang->words['warning_blurb_nn'], "{parse template="userHoverCard" group="global" params="$warning['wl_moderator']"}" )"}
				</if>
			</if>
			<br />
			<if test="isVerbalWarning:|:!$warning['wl_points'] and !$warning['wl_mq'] and !$warning['wl_rpa'] and !$warning['suspend']">
				{$this->lang->words['warnings_verbal_only']}
			<else />
				<if test="hasPoints:|:$warning['wl_points']">
					<if test="hasExpiration:|:$warning['wl_expire']">
						<if test="hasExpireDate:|:$warning['wl_expire_date']">
							{parse expression="sprintf( $this->lang->words['warnings_given_points_expire'], $warning['wl_points'], $this->lang->getDate( $warning['wl_expire_date'], 'SHORT' ) )"}<br />
						<else />
							{parse expression="sprintf( $this->lang->words['warnings_given_points_expired'], $warning['wl_points'] )"}<br />
					</if>
					<else />
						{parse expression="sprintf( $this->lang->words['warnings_given_points'], $warning['wl_points'] )"}<br />
					</if>
				</if>
				<foreach loop="options:array( 'mq', 'rpa', 'suspend' ) as $k">
					<if test="hasValue:|:$warning[ 'wl_' . $k ]">
						<if test="valueIsPermanent:|:$warning[ 'wl_' . $k ] == -1">
							{parse expression="sprintf( $this->lang->words[ 'warnings_' . $k ], $this->lang->words['warnings_permanently'] )"}<br />
						<else />
							{parse expression="sprintf( $this->lang->words[ 'warnings_' . $k ], sprintf( $this->lang->words['warnings_for'], $warning[ 'wl_' . $k ], $this->lang->words[ 'warnings_time_' . $warning[ 'wl_' . $k . '_unit' ] ] ) )"}<br />
						</if>
					</if>
				</foreach>
			</if>
		</div>
	</div>
	<fieldset class='submit'>
		<input type='submit' class='input_submit' value='{$this->lang->words['warnings_acknowledge_confirm']}' />
	</fieldset>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: addWarning
//===========================================================================
function addWarning($member, $reasons, $errors, $editor, $currentPunishments) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="warn"}
<h1 class='ipsType_pagetitle'>{parse expression="sprintf( $this->lang->words['warnings_add_for'], $member['members_display_name'] )"}</h1>
<br />
<form action='{parse url="app=members&amp;module=profile&amp;section=warnings&amp;do=save&amp;member={$member['member_id']}" base="public"}' method='post'>
	<input type='hidden' name='from_app' value='{$this->request['from_app']}' />
	<input type='hidden' name='from_id1' value='{$this->request['from_id1']}' />
	<input type='hidden' name='from_id2' value='{$this->request['from_id2']}' />
	<div class='ipsBox'>
		<div class='ipsBox_container ipsPad'>
			<ul class='ipsForm ipsForm_vertical'>
				<li class='ipsField ipsField_select'>
					<label class='ipsField_title'>{$this->lang->words['warnings_reason']} <span class='ipsForm_required'>*</span></label>
					<div class='ipsField_content'>
						<select name='reason' id='reason-select'>
							<option value=''>{$this->lang->words['warnings_reason_select']}</option>
							<foreach loop="reasons:$reasons as $r">
								<option value='{$r['wr_id']}'>{$r['wr_name']}</option>
							</foreach>
							<if test="hasOtherOption:|:$this->settings['warnings_enable_other'] or $this->memberData['g_access_cp']">
								<option value='0'>{$this->lang->words['warnings_reasons_other']}</option>
							</if>
						</select>
						<span class='error'>{$errors['reason']}</span>
					</div>
				</li>
				<li class='ipsField ipsField_select' style='display:none' id='points-li'>
					<label class='ipsField_title'>{$this->lang->words['warnings_points']} <span class='ipsForm_required'>*</span></label>
					<div class='ipsField_content'>
						<input name='points' class='input_text' size='5' id='points-field' />
						<a href='#' id='points-explain-button'><img src="{$this->settings['img_url']}/help.png" alt='{$this->lang->words['warnings_points_explain']}' title='{$this->lang->words['warnings_points_explain']}' /></a>
						<span class='error'>{$errors['points']}</span>
					</div>
				</li>
				<li class='ipsField'>
					<label class='ipsField_title'>{$this->lang->words['warnings_note_member']}</label>
					<div class='ipsField_content'>
						{$editor['member']}
						<span class='error'>{$errors['note_member']}</span>
					</div>
					<span class='desc'>{$this->lang->words['warnings_note_member_desc']}</span>
					<br />
					<span class='error'>{$errors['note_member']}</span>
				</li>
				<li class='ipsField'>
					<label class='ipsField_title'>{$this->lang->words['warnings_note_mods']}</label>
					<div class='ipsField_content'>
						{$editor['mod']}
						<span class='error'>{$errors['note_member']}</span>
					</div>
					<span class='desc'>{$this->lang->words['warnings_note_mods_desc']}</span>
					<br />
					<span class='error'>{$errors['note_member']}</span>
				</li>
				<li class='ipsField' id='punishment_li'>
					<label class='ipsField_title'>{$this->lang->words['warnings_punishment']}</label>
					<div class='ipsField_content'>
						<p class='message unspecific'>
							<span id='specified-punishment'>{$this->lang->words['warnings_punishment_select']}</span>
							<br />
							<a href='#' class='ipsButton_secondary' id='change-punishment-button' style='display:none'>{$this->lang->words['warnings_verbal_change']}</a>
						</p>
					</div>
				</li>
				<li class='ipsField' style='display:none' id='mq_li'>
					<label class='ipsField_title'>{$this->lang->words['warnings_punishment_mq']}</label>
					<div class='ipsField_content'>
						<if test="currentMq:|:$currentPunishments['mq']">
							<span class='error'>{$currentPunishments['mq']}{$this->lang->words['warnings_already_change']}<br /></span>
						</if>
						<input type='checkbox' name='mq_perm' id='mq_perm' /> <label for='mq_perm'>{$this->lang->words['warnings__permanently']}</label>
						<span id='mq_time'>{$this->lang->words['warnings_or_for']} <input name='mq' class='input_text' size='2' id='mq_input' /> <select name='mq_unit' id='mq_unit_select'><option value='d'>{$this->lang->words['warnings_time_d']}</option><option value='h'>{$this->lang->words['warnings_time_h']}</option></select></span>
					</div>
					<span class='desc'>{$this->lang->words['warnings_punishment_content_desc']}</span>
				</li>
				<li class='ipsField' style='display:none' id='rpa_li'>
					<label class='ipsField_title'>{$this->lang->words['warnings_punishment_rpa']}</label>
					<div class='ipsField_content'>
						<if test="currentRpa:|:$currentPunishments['rpa']">
							<span class='error'>{$currentPunishments['rpa']}{$this->lang->words['warnings_already_change']}<br /></span>
						</if>
						<input type='checkbox' name='rpa_perm' id='rpa_perm' /> <label for='rpa_perm'>{$this->lang->words['warnings__permanently']}</label>
						<span id='rpa_time'>{$this->lang->words['warnings_or_for']} <input name='rpa' class='input_text' size='2' id='rpa_input' /> <select name='rpa_unit' id='rpa_unit_select'><option value='d'>{$this->lang->words['warnings_time_d']}</option><option value='h'>{$this->lang->words['warnings_time_h']}</option></select></span>
					</div>
					<span class='desc'>{$this->lang->words['warnings_punishment_content_desc']}</span>
				</li>
				<li class='ipsField' style='display:none' id='suspend_li'>
					<label class='ipsField_title'>{$this->lang->words['warnings_punishment_suspend']}</label>
					<div class='ipsField_content'>
						<if test="currentSuspend:|:$currentPunishments['suspend']">
							<span class='error'>{$currentPunishments['suspend']}{$this->lang->words['warnings_already_change']}<br /></span>
						</if>
						<input type='checkbox' name='suspend_perm' id='suspend_perm' /> <label for='suspend_perm'>{$this->lang->words['warnings__permanently']}</label>
						<span id='suspend_time'>{$this->lang->words['warnings_or_for']} <input name='suspend' class='input_text' size='2' id='suspend_input' /> <select name='suspend_unit' id='suspend_unit_select'><option value='d'>{$this->lang->words['warnings_time_d']}</option><option value='h'>{$this->lang->words['warnings_time_h']}</option></select></span>
						<br />
						<input type='checkbox' name='ban_group' id='ban_group' value='1' /> <label for='ban_group'>{$this->lang->words['warnings_ban_group']}</label>
						<select name='ban_group_id'>
						<foreach loop="banGroups:$this->caches['group_cache'] as $id => $data">
							<if test="canUseAsBanGroup:|:!$data['g_access_cp'] && !$data['g_is_supmod'] && ($id != $this->settings['guest_group'])">
								<option value="{$id}">{$data['g_title']}</option>
							</if>
						</foreach>
						</select>
					</div>
				</li>
				<li class='ipsField' style='display:none' id='remove-points-li'>
					<label class='ipsField_title'>{$this->lang->words['warnings_remove_points']}</label>
					<div class='ipsField_content'>
						{$this->lang->words['warnings_after']} <input name='remove' class='input_text' size='2' id='remove_input' /> <select name='remove_unit' id='remove_unit_select'><option value='d'>{$this->lang->words['warnings_time_d']}</option><option value='h'>{$this->lang->words['warnings_time_h']}</option></select>
						<br />
						<span class='desc'>{$this->lang->words['warnings_remove_points_desc']}</span>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<fieldset class='submit'>
		<input type='submit' class='input_submit' value='{$this->lang->words['warnings_add']}' />
	</fieldset>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: customField__gender
//===========================================================================
function customField__gender($f) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<span class='row_title'>{$f->raw_data['pf_title']}</span>
<div class='row_data'>
	<if test="gender_set:|:$f->value">
		<if test="male:|:$f->value == 'm'">
			<img src='{$this->settings['img_url']}/profile/male.png' alt='{$this->lang->words['js_gender_male']}' /> {$this->lang->words['js_gender_male']}
		</if>
	
		<if test="female:|:$f->value == 'f'">
			<img src='{$this->settings['img_url']}/profile/female.png' alt='{$this->lang->words['js_gender_female']}' /> {$this->lang->words['js_gender_female']}
		</if>
	
		<if test="nottelling:|:$f->value != 'f' AND $f->value != 'm'">
			<img src='{$this->settings['img_url']}/profile/mystery.png' alt='{$this->lang->words['js_gender_mystery']}' /> {$f->parsed[0]}
		</if>
	<else />
		<img src='{$this->settings['img_url']}/profile/mystery.png' alt='{$this->lang->words['js_gender_mystery']}' /> {$this->lang->words['js_gender_mystery']}
	</if>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: customField__generic
//===========================================================================
function customField__generic($f) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="$f->parsed != ''">
	<span class='row_title'>{$f->raw_data['pf_title']}</span>
	<div class='row_data'>
		<if test="genericIsArray:|:is_array($f->parsed)">
			{parse expression="implode( '<br />', $f->parsed )"}
		<else />
			{$f->parsed}
		</if>
	</div>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: customFieldGroup__contact
//===========================================================================
function customFieldGroup__contact($f) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="contact_field:|:$f->parsed">
<li>
	<span class='row_title'>{$f->raw_data['pf_title']}</span>
	<span class='row_data'>
		<if test="cf_icon:|:$f->raw_data['pf_icon']"><img src='{$this->settings['public_dir']}{$f->raw_data['pf_icon']}' alt='{$f->raw_data['pf_title']}' />&nbsp;</if>
		<if test="cf_array:|:is_array( $f->parsed )">
			<foreach loop="cfieldgroups:$f->parsed as $value">
				{$value}
			</foreach>
		<else />
			<if test="cf_aim:|:$f->raw_data['pf_key'] == 'aim'">
				<a class='url' href='aim:goim?screenname={$f->parsed}'>{$f->parsed}</a>
			<else />
				<if test="cf_msn:|:$f->raw_data['pf_key'] == 'msn'">
					<a class='url' href='msnim:chat?contact={$f->parsed}'>{$f->parsed}</a>
				<else />
					<if test="cf_yahoo:|:$f->raw_data['pf_key'] == 'yahoo'">
						<a class='url' href='ymsgr:sendIM?{$f->parsed}'>{$f->parsed}</a>
					<else />
						<if test="cf_icq:|:$f->raw_data['pf_key'] == 'icq'">
							<a class='url' type="application/x-icq" href='http://www.icq.com/people/cmd.php?uin={$f->parsed}&amp;action=message'>{$f->parsed}</a>
						<else />
							<if test="cf_website:|:$f->raw_data['pf_key'] == 'website'">
								<a class='url uid' rel="external me" href='{$f->parsed}'>{$f->parsed}</a>
							<else />
								<if test="cf_jabber:|:$f->raw_data['pf_key'] == 'jabber'">
									<a class='url' href='xmpp:{$f->parsed}'>{$f->parsed}</a>
								<else />
									<if test="cf_skype:|:$f->raw_data['pf_key'] == 'skype'">
										<a class='url' href='skype:{$f->parsed}?call'>{$f->parsed}</a>
									<else />
										{$f->parsed}
									</if>
								</if>
							</if>
						</if>
					</if>
				</if>
			</if>
		</if>
	</span>
</li>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: customizeProfile
//===========================================================================
function customizeProfile($member) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<style type="text/css">
/* Overwrite some of the standard IPB rules */
/* Content, is the main page under the header */
#content {
	max-width:87%;
	min-width: 980px;
	margin-left: auto;
	margin-right: auto;
	background: transparent url("{style_images_url}/opacity70.png");
	background: rgba(255,255,255,0.3);
}
#profile_background {
	background: transparent url("{style_images_url}/opacity70.png");
	background: rgba(255,255,255,0.3);
}
.topic_buttons li.non_button a, #footer_utilities {
	background: #fff !important;
}
<if test="hasBodyCustomization:|:$member['customization']['bg_color'] OR $member['customization']['_bgUrl']">
body {
	<if test="hasBackgroundColor:|:$member['customization']['bg_color']">
		background-color: #{$member['customization']['bg_color']};
	</if>
	<if test="hasBackgroundImage:|:$member['customization']['_bgUrl']">
		background-image: url("{$member['customization']['_bgUrl']}");
		<if test="backgroundIsFixed:|:! $member['customization']['bg_tile']">
			background-position: 0px 0px;
			background-attachment: fixed;
			background-repeat: no-repeat;
		<else />
			background-position: 0px 0px;
			background-attachment: fixed;
			background-repeat: repeat;
		</if>
	</if>
}
</if>
</style>
<script type="text/javascript">
	ipb.profile.customization = 1;
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: dnameWrapper
//===========================================================================
function dnameWrapper($member_name="",$records=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="isAjaxModule:|:$this->request['module']=='ajax'">
	<h3>{$this->lang->words['dname_window_title']} {$member_name}</h3>
<else />
	<h3 class='maintitle'>{$this->lang->words['dname_window_title']} {$member_name}</h3>
</if>
<table class='ipb_table'>
	<tr class='header'>
		<th scope='col' style='width: 33%'>{$this->lang->words['dname_name_from']}</th>
		<th scope='col' style='width: 33%'>{$this->lang->words['dname_name_to']}</th>
		<th scope='col' style='width: 33%'>{$this->lang->words['dname_date']}</th>
	</tr>
	<if test="hasDnameHistory:|:is_array($records) AND count($records)">
		{parse striping="dname" classes="row1,row2"}
    	<foreach loop="records:$records as $row">
		<tr class='{parse striping="dname"}'>
		 	<td>{$row['dname_previous']}</td>
		 	<td><strong>{$row['dname_current']}</strong></td>
		 	<td class='altrow'>{parse date="$row['dname_date']" format="short"}</td>
		</tr>
		</foreach>
	<else />
		<tr>
			<td colspan='3' class='no_messages'>{$this->lang->words['dname_no_history']}</td>
		</tr>
    </if>
</table>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: explainPoints
//===========================================================================
function explainPoints($reasons, $actions) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['warnings_points']}</h3>
<div class='ipsPad'>
	{$this->lang->words['warnings_points_explain_1']}
</div>
<table class='ipb_table'>
	<tr class='header'>
		<th>{$this->lang->words['warnings_reason']}</th>
		<th>{$this->lang->words['warnings_points']}</th>
	</tr>
	<foreach loop="reasons:$reasons as $r">
		<tr>
			<td>{$r['wr_name']}</td>
			<td>{$r['wr_points']}</td>
		</tr>
	</foreach>
</table>
<if test="hasActions:|:!empty( $actions )">
	<div class='ipsPad'>
		{$this->lang->words['warnings_points_explain_2']}
	</div>
	<table class='ipb_table'>
		<tr class='header'>
			<th>{$this->lang->words['warnings_points']}</th>
			<th>{$this->lang->words['warnings_punishment']}</th>
		</tr>
		<foreach loop="actions:$actions as $a">
			<tr>
				<td>{$a['wa_points']}</td>
				<td>
					<foreach loop="options:array( 'mq', 'rpa', 'suspend' ) as $k">
						<if test="hasValue:|:$a[ 'wa_' . $k ]">
							<if test="valueIsPermanent:|:$a[ 'wa_' . $k ] == -1">
								{parse expression="sprintf( $this->lang->words[ 'warnings_' . $k ], $this->lang->words['warnings_permanently'] )"}<br />
							<else />
								{parse expression="sprintf( $this->lang->words[ 'warnings_' . $k ], sprintf( $this->lang->words['warnings_for'], $a['wa_' . $k ], $this->lang->words[ 'warnings_time_' . $a[ 'wa_' . $k . '_unit' ] ] ) )"}<br />
							</if>
						</if>
					</foreach>
				</td>
			</tr>
		</foreach>
	</table>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: friendsList
//===========================================================================
function friendsList($friends, $pages) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="friends"}
<h1 class='ipsType_pagetitle'>{$this->lang->words['m_friends_list']}</h1>
<if test="friendListPages:|:$pages">
	<div class='topic_controls'>
		$pages
	</div>
</if>
<div class='maintitle ipsFilterbar clear'>
	<ul class='ipsList_inline clearfix'>
		<if test="tabIsList:|:$this->request['tab'] == 'list' || !$this->request['tab']">
			<li class='active'><a href='{parse url="app=members&amp;section=friends&amp;module=profile&amp;do=list&amp;tab=list" base="public"}' title='{$this->lang->words['m_friends_list']}'>{$this->lang->words['m_friends_list']}</a></li>
			<li><a href='{parse url="app=members&amp;section=friends&amp;module=profile&amp;do=list&amp;tab=pending" base="public"}' title='{$this->lang->words['m_friends_pending']}'>{$this->lang->words['m_friends_pending']}</a></li>
		</if>
		<if test="tabIsPending:|:$this->request['tab'] == 'pending'">
			<li><a href='{parse url="app=members&amp;section=friends&amp;module=profile&amp;do=list&amp;tab=list" base="public"}' title='{$this->lang->words['m_friends_list']}'>{$this->lang->words['m_friends_list']}</a></li>
			<li class='active'><a href='{parse url="app=members&amp;section=friends&amp;module=profile&amp;do=list&amp;tab=pending" base="public"}' title='{$this->lang->words['m_friends_pending']}'>{$this->lang->words['m_friends_pending']}</a></li>
		</if>
	</ul>
</div>
<if test="hasFriendsList:|:is_array($friends) and count($friends) && $this->settings['friends_enabled']">
	{parse striping="memberStripe" classes="row1,row2"}
	<ul class='ipsMemberList'>
		<foreach loop="friendsList:$friends as $friend">
			<if test="loopOnPending:|:$this->request['tab'] == 'pending'">
				<li id='member_id_{$friend['member_id']}' class='ipsPad clearfix member_entry {parse striping="memberStripe"}'>
					<a href='{parse url="showuser={$friend['member_id']}" template="showuser" seotitle="{$friend['members_seo_name']}" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'><img src='{$friend['pp_small_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$friend['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_medium' /></a>
					<div class='ipsBox_withphoto'>
						<ul class='ipsList_inline right'>
							<li><a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=moderate&amp;pp_option=approve&amp;pp_friend_id[{$friend['member_id']}]=1&amp;md5check={$this->member->form_hash}" base="public"}' title='{$this->lang->words['approve_request']}' class='ipsButton_secondary'>{$this->lang->words['approve_request']}</a></li>
							<li><a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=moderate&amp;pp_option=delete&amp;pp_friend_id[{$friend['member_id']}]=1&amp;md5check={$this->member->form_hash}" base="public"}' title='{$this->lang->words['deny_request']}' class='ipsButton_secondary important'>{$this->lang->words['deny_request']}</a></li>
						</ul>
						
						<h3 class='ipsType_subtitle'>
							<strong><a href='{parse url="showuser={$friend['member_id']}" template="showuser" seotitle="{$friend['members_seo_name']}" base="public"}' title='{$this->lang->words['view_profile']}'>{$friend['members_display_name']}</a></strong>
						</h3>
						<if test="repson:|:$this->settings['reputation_enabled'] && $this->settings['reputation_show_profile']">
							<if test="norep:|:$friend['pp_reputation_points'] == 0 || !$friend['pp_reputation_points']">
								<p class='reputation zero ipsType_small left'>
							</if>
							<if test="posrep:|:$friend['pp_reputation_points'] > 0">
								<p class='reputation positive ipsType_small left'>
							</if>
							<if test="negrep:|:$friend['pp_reputation_points'] < 0">
								<p class='reputation negative ipsType_small left'>
							</if>							
									<span class='number'>{$friend['pp_reputation_points']}</span>
								</p>
						</if>
						<span class='desc'>
							{$this->lang->words['member_joined']} {parse date="$friend['joined']" format="joined"}<br />
							{IPSMember::makeNameFormatted( $friend['group'], $friend['member_group_id'] )} &middot; {parse format_number="$friend['posts']"} {$this->lang->words['member_posts']}
						</span>
					</div>
				</li>
			<else />
				<li id='member_id_{$friend['member_id']}' class='ipsPad clearfix member_entry {parse striping="memberStripe"}'>
					<a href='{parse url="showuser={$friend['member_id']}" template="showuser" seotitle="{$friend['members_seo_name']}" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'><img src='{$friend['pp_small_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$friend['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_medium' /></a>
					<div class='ipsBox_withphoto'>
						<ul class='ipsList_inline right'>
							<if test="weAreSupmod:|:$this->memberData['g_is_supmod'] == 1 && $friend['member_id'] != $this->memberData['member_id']">
								<li><a href='{parse url="app=core&amp;module=modcp&amp;do=editmember&amp;auth_key={$this->member->form_hash}&amp;mid={$friend['member_id']}&amp;pf={$friend['member_id']}" base="public"}' class='ipsButton_secondary'>{$this->lang->words['supmod_edit_member']}</a></li>
							</if>
							<if test="notus:|:$this->memberData['member_id'] AND $this->memberData['member_id'] != $friend['member_id'] && $this->settings['friends_enabled'] AND $this->memberData['g_can_add_friends']">
								<if test="addfriend:|:IPSMember::checkFriendStatus( $friend['member_id'] )">
									<li class='mini_friend_toggle is_friend' id='friend_mlist_{$friend['member_id']}'><a href='{parse url="app=members&amp;module=list&amp;module=profile&amp;section=friends&amp;do=remove&amp;member_id={$friend['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['remove_friend']}' class='ipsButton_secondary'>{parse replacement="remove_friend"}</a></li>
								<else />
									<li class='mini_friend_toggle is_not_friend' id='friend_mlist_{$friend['member_id']}'><a href='{parse url="app=members&amp;module=list&amp;module=profile&amp;section=friends&amp;do=add&amp;member_id={$friend['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['add_friend']}' class='ipsButton_secondary'>{parse replacement="add_friend"}</a></li>								
								</if>
							</if>
							<if test="sendpm:|:$this->memberData['g_use_pm'] AND $this->memberData['members_disable_pm'] == 0 AND IPSLib::moduleIsEnabled( 'messaging', 'members' ) && $friend['member_id'] != $this->memberData['member_id']">
								<li class='pm_button' id='pm_xxx_{$friend['pp_member_id']}'><a href='{parse url="app=members&amp;module=list&amp;module=messaging&amp;section=send&amp;do=form&amp;fromMemberID={$friend['pp_member_id']}" base="public"}' title='{$this->lang->words['pm_member']}' class='ipsButton_secondary'>{parse replacement="send_msg"}</a></li>
							</if>
							<li><a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;mid={$friend['member_id']}" base="public"}' title='{$this->lang->words['gbl_find_my_content']}' class='ipsButton_secondary'>{parse replacement="find_topics_link"}</a></li>
							<if test="blog:|:$friend['has_blog'] AND IPSLib::appIsInstalled( 'blog' )">
								<li><a href='{parse url="app=blog&amp;module=display&amp;section=blog&amp;mid={$friend['member_id']}" base="public"}' title='{$this->lang->words['view_blog']}' class='ipsButton_secondary'>{parse replacement="blog_link"}</a></li>
							</if>
							<if test="gallery:|:$friend['has_gallery'] AND IPSLib::appIsInstalled( 'gallery' )">
								<li><a href='{parse url="app=gallery&amp;user={$friend['member_id']}" seotitle="{$friend['members_seo_name']}" template="useralbum" base="public"}' title='{$this->lang->words['view_gallery']}' class='ipsButton_secondary'>{parse replacement="gallery_link"}</a></li>
							</if>
						</ul>
						
						<h3 class='ipsType_subtitle'>
							<strong><a href='{parse url="showuser={$friend['member_id']}" template="showuser" seotitle="{$friend['members_seo_name']}" base="public"}' title='{$this->lang->words['view_profile']}'>{$friend['members_display_name']}</a></strong>
						</h3>
						<if test="repson:|:$this->settings['reputation_enabled'] && $this->settings['reputation_show_profile']">
							<if test="norep:|:$friend['pp_reputation_points'] == 0 || !$friend['pp_reputation_points']">
								<p class='reputation zero ipsType_small left'>
							</if>
							<if test="posrep:|:$friend['pp_reputation_points'] > 0">
								<p class='reputation positive ipsType_small left'>
							</if>
							<if test="negrep:|:$friend['pp_reputation_points'] < 0">
								<p class='reputation negative ipsType_small left'>
							</if>							
									<span class='number'>{$friend['pp_reputation_points']}</span>
								</p>
						</if>
						<span class='desc'>
							{$this->lang->words['member_joined']} {parse date="$friend['joined']" format="joined"}<br />
							{IPSMember::makeNameFormatted( $friend['group'], $friend['member_group_id'] )} &middot; {parse format_number="$friend['posts']"} {$this->lang->words['member_posts']}
						</span>
					</div>
				</li>
			</if>
		
		</foreach>
	</ul>
<else />
	<p class='no_messages'>
		<if test="friendListNone:|:$this->request['tab'] == 'pending'">
			{$this->lang->words['no_friends_awaiting_approval']}
		<else />
			{$this->lang->words['no_friends_to_display']}
		</if>
	</p>
</if>
<if test="friendListPagesBottom:|:$pages">
	<div class='topic_controls'>
		{$pages}
	</div>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: listWarnings
//===========================================================================
function listWarnings($member, $warnings, $pagination, $reasons, $canWarn) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{parse expression="sprintf( $this->lang->words['warnings_member'], $member['members_display_name'] )"}</h1>
<p class='ipsType_pagedesc'>{parse expression="sprintf( $this->lang->words['warn_status'], $member['warn_level'] )"}</p>
<if test="hasPaginationOrWarn:|:$pagination || $canWarn">
	<div class='topic_controls clearfix'>
		<if test="paginationTop:|:$pagination">{$pagination}</if>
		<if test="canWarn:|:$canWarn">
			<ul class='topic_buttons'>
				<li><a href='{parse url="app=members&module=profile&section=warnings&do=add&member={$member['member_id']}&from_app={$this->request['from_app']}&from_id1={$this->request['from_id1']}&from_id2={$this->request['from_id2']}" base="public"}'>{$this->lang->words['warnings_add']}</a></li>
			</ul>
		</if>
	</div>
</if>
<br />
<div class='maintitle'>{$this->lang->words['warnings']}</div>
<if test="noWarnings:|:empty( $warnings )">
	<div class='no_messages'>
		{$this->lang->words['warnings_empty']}
	</div>
<else />
	<div class='ipsBox'>
		<div class='ipsBox_container'>
			<table class='ipb_table'>
				<thead>
					<tr class='header'>
						<th width='20%'>{$this->lang->words['warnings_date']}</th>
						<th width='20%'>{$this->lang->words['warnings_reason']}</th>
						<th width='20%'>{$this->lang->words['warnings_points']}</th>
						<th width='20%'>{$this->lang->words['warnings_moderator']}</th>
						<th width='20%'>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<foreach loop="warnings:$warnings as $warning">
						<tr>
							<td>{parse date="$warning['wl_date']" format="JOINED"}</td>
							<td>
								<if test="hasReason:|:isset( $reasons[ $warning['wl_reason'] ] )">
									{$reasons[ $warning['wl_reason'] ]['wr_name']}
								<else />
									{$this->lang->words['warnings_reasons_other']}
								</if>
							</td>
							<td>{$warning['wl_points']}</td>
							<td>{parse template="userHoverCard" group="global" params="$warning['wl_moderator']"}</td>
							<td><a href='javascript:void(0);' class='ipsButton_secondary' onclick='warningPopup( this, {$warning['wl_id']} )'>{$this->lang->words['warnings_moreinfo']}</a></td>
						</tr>
					</foreach>
				</tbody>
			</table>
		</div>
	</div>
</if>
<br />
<if test="paginationBottom:|:$pagination">{$pagination}</if>
<br /><br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: photoEditor
//===========================================================================
function photoEditor($data, $member) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<link rel="stylesheet" type="text/css" media="screen" href="{$this->settings['public_dir']}style_css/{$this->registry->output->skin['_csscacheid']}/ipb_photo_editor.css" />
<script type="text/javascript">
 	// Can't use normal link as loaded by Ajax
    $$("head")[0].insert(new Element("script", {
      type: "text/javascript",
      src: '{$this->settings['public_dir']}js/ips.inlineUploader.js'
    })).
    insert(new Element("script", {
      type: "text/javascript",
      src: '{$this->settings['public_dir']}js/ips.photoEditor.js'
    })).
    insert(new Element("script", {
      type: "text/javascript",
      src: '{$this->settings['public_dir']}js/3rd_party/cropper/cropper.uncompressed.js'
    }));
</script>
<form method='post' enctype="multipart/form-data" action="{parse url="app=members&amp;module=profile&amp;section=photo&amp;do=save" base="public"}" id='photoEditorForm' name='photoEditorForm'>
<h3>{$this->lang->words['pe_title']}</h3>
<div class='ipsBox'>
<div class='fixed_inner'>
	<fieldset id='ips_photoWrap' class='fixed_inner ipsBox_container'>
		<div id='ips_sidePanel'>
			<div id="ips_currentPhoto">
				{$data['currentPhoto']['tag']}
			</div>
			<div id='ips_cropperControls' style='display:none'>
				<a href="javascript:void(0);" class="ipsButton_secondary desc cropperCancel">{$this->lang->words['pe_cancel']}</a> <a href="javascript:void(0);" class="ipsButton_secondary desc cropperAccept">{$this->lang->words['pe_ok']}</a>
			</div>
			<div id='ips_cropperStart' style='<if test="$data['type'] == 'gravatar'">display:none</if>;'>
				<a href="javascript:void(0);" class="ipsButton_secondary desc cropperStart">{$this->lang->words['adjust_crop_lnk']}</a>
			</div>
		</div>
		<div id='ips_photoOptions'>
			<ul>
				<li class='ips_option row2'>
					<div class='ips_photoPreview _custom'><label>{$data['custom']['tag']}</label></div>
					<div class='ips_photoControls'>
						<input type='radio' name='photoType' id='ips_ptype_custom' value='custom' <if test="$data['type'] == 'custom'">checked='checked'</if> /> <strong>{$this->lang->words['pe_use_custom_photo']}</strong>
						<div class='ips_photoOptionText'>
							<span class='desc'>{$this->lang->words['pe_upload_desc']}.<br />{$this->lang->words['pe_upload_rec']}</span>
							<br />
							<if test="canHasUpload:|:IPSMember::canUploadPhoto( $member )">
								<br />
								<span class='desc'>{$this->lang->words['pe_upload_from_file']}</span>
								<br /><input type="file" name="upload_photo" id='upload_photo' class='input_text' value="" size="20" title="{$this->lang->words['pe_formats']}" /><br />
							</if>
							<if test="canHasURL:|:$this->settings['mem_photo_url']">
								<br />
								<span class='desc'>{$this->lang->words['pe_import_from_url']}</span>
								<br /><input type='text' class='input_text' size='20' id='url_photo' name='url_photo' /> <input type='button' id='url_import' value='{$this->lang->words['pe_import_button']}' class='ipsButton_secondary desc' /><br />
							</if>
							<div class='message error' style='display:none' id='ips_type_custom_error'></div>
							<br />
						</div>
					</div>
				</li>
				<if test="allowGravatars:|:$this->settings['allow_gravatars']">
				<li class='ips_option row2'>
					<div class='ips_photoPreview _gravatar'><label>{$data['gravatar']['tag']}</label></div>
					<div class='ips_photoControls'>
						<input type='radio' name='photoType' id='ips_ptype_gravatar' value='gravatar' <if test="$data['type'] == 'gravatar'">checked='checked'</if> /> <strong>{$this->lang->words['pe_use_gravatar']}</strong>
						<div class='ips_photoOptionText'>
							<span class='desc'><a href="http://www.gravatar.com" rel="external">{$this->lang->words['pe_what_is_gravatar']}</a></span>
							<br /><br />
							<input type="text" name="gravatar" id='gravatar' class='input_text' size="35" value="{$member['pp_gravatar']}" />
							<br /><span class='desc'>{$this->lang->words['pe_enter_gravatar_email']}</span>
						</div>
					</div>
				</li>
				</if>
				<if test="hasTwitter:|:! empty($data['twitter']['tag'])">
				<li class='ips_option row2'>
					<div class='ips_photoPreview _twitter'><label>{$data['twitter']['tag']}</label></div>
					<div class='ips_photoControls'>
						<input type='radio' name='photoType' id='ips_ptype_twitter' value='twitter' <if test="$data['type'] == 'twitter'">checked='checked'</if> /> <strong>{$this->lang->words['pe_use_twitter']}</strong>
						<div class='ips_photoOptionText'>
							<span class='desc'>{$this->lang->words['pe_twitter_desc']}</span>					
						</div>
					</div>
				</li>
				</if>
				<if test="hasFacebook:|:! empty($data['facebook']['tag'])">
				<li class='ips_option row2'>
					<div class='ips_photoPreview _facebook'><label>{$data['facebook']['tag']}</label></div>
					<div class='ips_photoControls'>
						<input type='radio' name='photoType' id='ips_ptype_facebook' value='facebook' <if test="$data['type'] == 'facebook'">checked='checked'</if> /> <strong>{$this->lang->words['pe_use_facebook']}</strong>
						<div class='ips_photoOptionText'>
							<span class='desc'>{$this->lang->words['pe_facebook_desc']}</span>					
						</div>
					</div>
				</li>
				</if>
				<li class='ipsPad_half clearfix right'>
					<input type='button' name='remove' value='{$this->lang->words['pe_remove_button']}' class='ipsButton important ips_photoRemove' />
					<input type='submit' name='saveit' value='{$this->lang->words['pe_done_button']}' class='ipsButton ips_photoSubmit' />
				</li>
			</ul>
		</div>
	</fieldset>
</div>
</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: profileModern
//===========================================================================
function profileModern($tabs=array(), $member=array(), $visitors=array(), $default_tab='status', $default_tab_content='', $friends=array(), $status=array(), $warns=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="status"}
{parse js_module="rating"}
{parse js_module="profile"}
<script type='text/javascript'>
//<![CDATA[
	ipb.profile.viewingProfile = parseInt( {$member['member_id']} );
	<if test="$this->memberData['member_id']">
		ipb.templates['remove_friend'] = "<a href='{parse url="app=members&amp;section=friends&amp;module=profile&amp;do=remove&amp;member_id={$member['member_id']}" base="public"}' title='{$this->lang->words['remove_as_friend']}'><img src='{$this->settings['img_url']}/user_delete.png' alt='{$this->lang->words['remove_as_friend']}' />&nbsp;&nbsp; {$this->lang->words['remove_as_friend']}</a>";
		ipb.templates['add_friend'] = "<a href='{parse url="app=members&amp;section=friends&amp;module=profile&amp;do=add&amp;member_id={$member['member_id']}" base="public"}' title='{$this->lang->words['add_me_friend']}'><img src='{$this->settings['img_url']}/user_add.png' alt='{$this->lang->words['add_me_friend']}' />&nbsp;&nbsp; {$this->lang->words['add_me_friend']}</a>";
	</if>
	ipb.templates['edit_status'] = "<span id='edit_status'><input type='text' class='input_text' style='width: 60%' id='updated_status' maxlength='150' /> <input type='submit' value='{$this->lang->words['save']}' class='input_submit' id='save_status' /> &nbsp;<a href='#' id='cancel_status' class='cancel' title='{$this->lang->words['cancel']}'>{$this->lang->words['cancel']}</a></span>";
	<if test="friendsEnabled:|:$this->settings['friends_enabled'] AND $this->memberData['g_can_add_friends']">
		<if test="jsIsFriend:|:IPSMember::checkFriendStatus( $member['member_id'] )">
			ipb.profile.isFriend = true;
		<else />
			ipb.profile.isFriend = false;
		</if>
	</if>
//]]>
</script>
<if test="hasCustomization:|:is_array($member['customization']) AND $member['customization']['type']">
	{parse template="customizeProfile" group="profile" params="$member"}
</if>
<if test="canEditUser:|:($this->memberData['member_id'] && $member['member_id'] == $this->memberData['member_id']) || $this->memberData['g_is_supmod'] == 1 || ($this->memberData['member_id'] && $member['member_id'] != $this->memberData['member_id'])">
	<ul class='topic_buttons'>
		<if test="weAreSupmod:|:$this->memberData['g_is_supmod'] == 1 && $member['member_id'] != $this->memberData['member_id']">
			<li><a href='{parse url="app=core&amp;module=modcp&amp;do=editmember&amp;auth_key={$this->member->form_hash}&amp;mid={$member['member_id']}&amp;pf={$member['member_id']}" base="public"}'>{$this->lang->words['supmod_edit_member']}</a></li>
		</if>
		<if test="weAreOwner:|:$this->memberData['member_id'] && $member['member_id'] == $this->memberData['member_id']">
			<li><a href='{parse url="app=core&amp;module=usercp&amp;tab=core" base="public"}'>{$this->lang->words['edit_profile']}</a></li>
		</if>
		<if test="supModCustomization:|:($member['member_id'] == $this->memberData['member_id'] ) AND $member['customization']['type']">
			<li class='non_button'><a href='{parse url="showuser={$member['member_id']}&amp;secure_key={$this->member->form_hash}&amp;removeCustomization=1" seotitle="{$member['members_seo_name']}" template="showuser" base="public"}'>{$this->lang->words['cust_remove']}</a></li>
		</if>
	</ul>
</if>
<div class='ipsBox clear vcard' id='profile_background'>
	<div class='ipsVerticalTabbed ipsLayout ipsLayout_withleft ipsLayout_smallleft clearfix'>
		<div class='ipsVerticalTabbed_tabs ipsLayout_left' id='profile_tabs'>
			<p class='short photo_holder'>
				<if test="canEditPic:|:($this->memberData['member_id'] && $member['member_id'] == $this->memberData['member_id']) AND (IPSMember::canUploadPhoto($member, TRUE))">
					<a data-clicklaunch="launchPhotoEditor" href="{parse url="app=members&amp;module=profile&amp;section=photo" base="public"}" id='change_photo' class='ipsType_smaller ipsPad' title='{$this->lang->words['change_photo_desc']}'>{$this->lang->words['change_photo_link']}</a>
				</if>
				<img class="ipsUserPhoto" id='profile_photo' src='{$member['pp_main_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$member['members_display_name'])"}"  />
			</p>
			<if test="haswarn:|:$member['show_warn']">
				<div class='warn_panel clear ipsType_small'>
					<strong><a href='{parse url="app=members&amp;module=profile&amp;section=warnings&amp;member={$member['member_id']}&amp;from_app=members" base="public"}' id='warn_link_xxx_{$member['member_id']}' title='{$this->lang->words['warn_view_history']}'>{parse expression="sprintf( $this->lang->words['warn_status'], $member['warn_level'] )"}</a></strong>
				</div>
			</if>
			<ul class='clear'>
				<li id='tab_link_core:info' class='tab_toggle <if test="$default_tab == 'core:info'">active</if>' data-tabid='user_info'><a href='#'>{$this->lang->words['pp_tab_info']}</a></li>
				<foreach loop="tabs:$tabs as $tab">
					<li id='tab_link_{$tab['app']}:{$tab['plugin_key']}' class='<if test="tabactive:|:$tab['app'].':'.$tab['plugin_key'] == $default_tab || $this->request['tab'] == $tab['plugin_key']">active</if> tab_toggle' data-tabid='{$tab['plugin_key']}'><a href='{parse url="showuser={$member['member_id']}&amp;tab={$tab['plugin_key']}" seotitle="{$member['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view']} {$tab['_lang']}'>{$tab['_lang']}</a></li>
				</foreach>
			</ul>
		</div>
		<div class='ipsVerticalTabbed_content ipsLayout_content ipsBox_container' id='profile_content'>
			<div class='ipsPad'>
				<div id='profile_content_main'>
					<div id='user_info_cell'>
						<h1 class='ipsType_pagetitle'>
							<span class='fn nickname'>{$member['members_display_name']}</span>
						</h1>
						{$this->lang->words['m_member_since']} {parse date="$member['joined']" format="DATE"}<br />
						<if test="hasWarns:|:!empty( $warns )">
							<foreach loop="warnsLoop:array( 'ban', 'suspend', 'rpa', 'mq' ) as $k">
								<if test="warnIsSet:|:isset( $warns[ $k ] )">
									<span class='ipsBadge ipsBadge_red<if test="warnClickable:|:$warns[ $k ]"> clickable</if>' <if test="warnPopup:|:$warns[ $k ]">onclick='warningPopup( this, {$warns[ $k ]} )'</if>>{$this->lang->words[ 'warnings_profile_badge_' . $k ]}</span>
								</if>
							</foreach>
						</if>
						<if test="onlineDetails:|:$member['_online'] && ($member['online_extra'] != $this->lang->words['not_online'])">
							<span class='ipsBadge ipsBadge_green reset_cursor' data-tooltip="{parse expression="strip_tags($member['online_extra'])"}">{$this->lang->words['online_online']}</span>
						<else />
							<span class='ipsBadge ipsBadge_lightgrey reset_cursor'>{$this->lang->words['online_offline']}</span>
						</if>
						<span class='desc lighter'>{$this->lang->words['m_last_active']} {$member['_last_active']}</span> 
					</div>
					<if test="userStatus:|:$status['status_id']">
					<div id='user_status_cell'>
						<div id='user_latest_status'>
							<div>
								{parse expression="IPSText::truncate( strip_tags( $status['status_content'] ), 180 )"}
								<span class='ipsType_smaller desc lighter blend_links'><a href='{parse url="app=members&amp;module=profile&amp;section=status&amp;type=single&amp;status_id={$status['status_id']}" seotitle="true" template="members_status_single" base="public"}'>{$this->lang->words['ps_updated']} {parse date="$status['status_date']" format="manual{%d %b}" relative="true"} &middot; {parse expression="intval($status['status_replies'])"} {$this->lang->words['ps_comments']}</a></span>
							</div>
						</div>
					</div>
					</if>
					<if test="allowRate:|:$this->settings['pp_allow_member_rate']">
						<span class='rating left clear' style='margin-bottom: 10px'>
							<if test="noRateYourself:|:$this->memberData['member_id'] == $member['member_id'] || !$this->memberData['member_id']">
									<if test="rate1:|:$member['pp_rating_real'] >= 1">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if><if test="rate2:|:$member['pp_rating_real'] >= 2">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if><if test="rate3:|:$member['pp_rating_real'] >= 3">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if><if test="rate4:|:$member['pp_rating_real'] >= 4">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if><if test="rate5:|:$member['pp_rating_real'] >= 5">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if><span id='rating_text' class='desc'></span>
							<else />
									<a href='#' id='user_rate_1' title='{$this->lang->words['m_rate_1']}'><if test="rated1:|:$member['pp_rating_real'] >= 1">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if></a><a href='#' id='user_rate_2' title='{$this->lang->words['m_rate_2']}'><if test="rated2:|:$member['pp_rating_real'] >= 2">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if></a><a href='#' id='user_rate_3' title='{$this->lang->words['m_rate_3']}'><if test="rated3:|:$member['pp_rating_real'] >= 3">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if></a><a href='#' id='user_rate_4' title='{$this->lang->words['m_rate_4']}'><if test="rated4:|:$member['pp_rating_real'] >= 4">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if></a><a href='#' id='user_rate_5' title='{$this->lang->words['m_rate_5']}'><if test="rated5:|:$member['pp_rating_real'] >= 5">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if></a> <span id='rating_text' class='desc'></span>
								<script type='text/javascript'>
									rating = new ipb.rating( 'user_rate_', { 
														url: ipb.vars['base_url'] + 'app=members&module=ajax&section=rate&member_id={$member['member_id']}&md5check=' + ipb.vars['secure_hash'],
														cur_rating: <if test="hasrating:|:isset($member['pp_rating_real'])">{$member['pp_rating_real']}<else />0</if>,
														rated: null,
														allow_rate: ( {$this->memberData['member_id']} != 0 ) ? 1 : 0,
														show_rate_text: false
													  } );
								</script>
							</if>
						</span>
					</if>
					<ul class='ipsList_inline' id='user_utility_links'>
						<if test="noFriendYourself:|:$this->memberData['member_id'] AND $this->memberData['member_id'] != $member['member_id'] && $this->settings['friends_enabled'] AND $this->memberData['g_can_add_friends']">
							<li id='friend_toggle' class='ipsButton_secondary'>
								<if test="isFriend:|:IPSMember::checkFriendStatus( $member['member_id'] )">
									<a href='{parse url="app=members&amp;section=friends&amp;module=profile&amp;do=remove&amp;member_id={$member['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['remove_friend']}'><img src='{$this->settings['img_url']}/user_delete.png' alt='{$this->lang->words['remove_friend']}' />&nbsp;&nbsp; {$this->lang->words['remove_as_friend']}</a>
								<else />
									<a href='{parse url="app=members&amp;section=friends&amp;module=profile&amp;do=add&amp;member_id={$member['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['add_friend']}'><img src='{$this->settings['img_url']}/user_add.png' alt='{$this->lang->words['add_friend']}' />&nbsp;&nbsp; {$this->lang->words['add_me_friend']}</a>
								</if>
							</li>
						</if>
						<if test="pmlink:|:($member['member_id'] != $this->memberData['member_id']) AND $this->memberData['g_use_pm'] AND $this->memberData['members_disable_pm'] == 0 AND IPSLib::moduleIsEnabled( 'messaging', 'members' ) AND $member['members_disable_pm'] == 0">
							<li class='pm_button' id='pm_xxx_{$member['member_id']}'><a href='{parse url="app=members&amp;module=messaging&amp;section=send&amp;do=form&amp;fromMemberID={$member['member_id']}" base="public"}' title='{$this->lang->words['pm_this_member']}' class='ipsButton_secondary'>{parse replacement="send_msg"}&nbsp;&nbsp; {$this->lang->words['send_message']}</a></li>
						</if>
						<li>
							<a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;mid={$member['member_id']}" base="public"}' class='ipsButton_secondary'>{parse replacement="find_topics_link"}&nbsp;&nbsp;  {$this->lang->words['gbl_find_my_content']}</a>
						</li>
					</ul>
				</div>
				<div id='profile_panes_wrap' class='clearfix'>
					
					<div id='pane_core:info' class='ipsLayout ipsLayout_withright ipsLayout_largeright clearfix' <if test="$default_tab != 'core:info'">style='display: none'</if>>						
						<div class='ipsLayout_content'>
							<if test="$member['pp_about_me']">
								<div class='general_box clearfix'>
									<h3>{$this->lang->words['pp_tab_aboutme']}</h3>
									<div class='ipsPad'>
										
											{$member['pp_about_me']}
										
									</div>
								</div>
								<hr/>
							</if>
							<div class='general_box clearfix'>
								<h3>{$this->lang->words['community_stats']}</h3>
								<br />								
								<ul class='ipsList_data clearfix'>									
									<li class='clear clearfix'>
										<span class='row_title'>{$this->lang->words['m_group']}</span>
										<span class='row_data'>{$member['g_title']}</span>
									</li>
									<li class='clear clearfix'>
										<span class='row_title'>{$this->lang->words['m_posts']}</span>
										<span class='row_data'>{parse format_number="$member['posts']"}</span>
									</li>
									<li class='clear clearfix'>
										<span class='row_title'>{$this->lang->words['m_profile_views']}</span>
										<span class='row_data'>{parse format_number="$member['members_profile_views']"}</span>
									</li>
									<if test="member_title:|:$member['title'] != ''">
										<li class='clear clearfix'>
											<span class='row_title'>{$this->lang->words['m_member_title']}</span>
											<span class='row_data'>{$member['title']}</span>
										</li>
									</if>
									<li class='clear clearfix'>
										<span class='row_title'>{$this->lang->words['m_age_prefix']}</span>
										<if test="member_age:|:$member['_age'] > 0">
											<span class='row_data'>{$member['_age']} {$this->lang->words['m_years_old']}</span>
										<else />
											<span class='row_data desc lighter'>{$this->lang->words['m_age_unknown']}</span>
										</if>
									</li>
									<li class='clear clearfix'>
										<span class='row_title'>{$this->lang->words['m_birthday_prefix']}</span>
										<if test="member_birthday:|:$member['bday_day']">
											<span class='row_data'>{$member['_bday_month']} {$member['bday_day']}<if test="member_bday_year:|:$member['bday_year']">, {$member['bday_year']}</if></span>
										<else />
											<span class='row_data desc lighter'>{$this->lang->words['m_bday_unknown']}</span>
										</if>
									</li>
									<if test="pcfields:|:$member['custom_fields']['profile_info'] != """>
										<foreach loop="pcfieldsLoop:$member['custom_fields']['profile_info'] as $key => $value">
											<if test="!empty($value)">
												<li class='clear clearfix'>
													{$value}
												</li>
											</if>
										</foreach>
									</if>
								</ul>
								<br />
							</div>
							
							<if test="pcfieldsOther:|:$member['custom_fields']">
								<foreach loop="pcfieldsOtherLoop:$member['custom_fields'] as $group => $mdata">
									<if test="pcfieldsOtherLoopCheck:|:$group != 'profile_info' AND $group != 'contact'">
										<if test="pcfieldsOtherLoopCheck2:|:is_array( $member['custom_fields'][ $group ] ) AND count( $member['custom_fields'][ $group ] )">
											<div class='general_box clearfix' id='custom_fields_{$group}'>
												<h3 class='bar'>{$member['custom_field_groups'][ $group ]}</h3>
												<br />
												<ul class='ipsList_data clearfix'>
													<foreach loop="pcfieldsOtherLoopCheckInner:$member['custom_fields'][ $group ] as $key => $value">
														<li class='clear clearfix'>
															{$value}
														</li>
													</foreach>
												</ul>
												<br />
											</div>
										</if>
									</if>
								</foreach>
							</if>
							
							<if test="hasContactFields:|:$this->memberData['g_access_cp'] == 1 || is_array( $member['custom_fields']['contact'])">
								<div class='general_box clearfix'>
									<h3>{$this->lang->words['contact_info']}</h3>
									<br />
								
									<ul class='ipsList_data clearfix'>
										<if test="isadmin:|:$this->memberData['g_access_cp'] == 1">
											<li class='clear clearfix'>
												<span class='row_title'>{$this->lang->words['m_email']}</span>
												<span class='row_data'>
													<a href='mailto:{$member['email']}'>{$member['email']}</a>
												</span>
											</li>
										</if>
										<if test="member_contact_fields:|:is_array( $member['custom_fields']['contact'])">
											<foreach loop="cfields:$member['custom_fields']['contact'] as $field">
												{$field}
											</foreach>
										</if>
									</ul>
								</div>
							</if>
						</div>
						
						<div class='ipsLayout_right'>
							<if test="ourReputation:|:$this->settings['reputation_enabled'] && $this->settings['reputation_show_profile']">
								<if test="RepPositive:|:$member['pp_reputation_points'] > 0">
									<div class='reputation positive' data-tooltip="{parse expression="sprintf( $this->lang->words['rep_description'], $member['members_display_name'], $member['pp_reputation_points'])"}">
								</if>
								<if test="RepNegative:|:$member['pp_reputation_points'] < 0">
									<div class='reputation negative' data-tooltip="{parse expression="sprintf( $this->lang->words['rep_description'], $member['members_display_name'], $member['pp_reputation_points'])"}">
								</if>
								<if test="RepZero:|:$member['pp_reputation_points'] == 0">
									<div class='reputation zero' data-tooltip="{parse expression="sprintf( $this->lang->words['rep_description'], $member['members_display_name'], $member['pp_reputation_points'])"}">
								</if>
										<span class='number'>{$member['pp_reputation_points']}</span>
										<if test="RepText:|:$member['author_reputation'] && $member['author_reputation']['text']">
											<span class='title'>{$member['author_reputation']['text']}</span>
										</if>
										<if test="RepImage:|:$member['author_reputation'] && $member['author_reputation']['image']">
											<span class='image'><img src='{$member['author_reputation']['image']}' alt='{$this->lang->words['m_reputation']}' /></span>
										</if>
									</div>
								
								<br />
							</if>
							
							<if test="checkModTools:|:($member['spamStatus'] !== NULL && $member['member_id'] != $this->memberData['member_id']) || ($this->memberData['g_mem_info'] && $this->settings['auth_allow_dnames']) || (($member['member_id'] != $this->memberData['member_id'] AND $this->memberData['g_is_supmod'] ) AND $member['customization']['type'])">
								<div class='general_box clearfix'>
									<h3>{$this->lang->words['user_tools']}</h3>
									<ul class='ipsPad'>	
										<if test="authorspammer:|:$member['spamStatus'] !== NULL && $member['member_id'] != $this->memberData['member_id']">
											<if test="authorspammerinner:|:$member['spamStatus'] === TRUE">
												<li><a href='#' onclick="return ipb.global.toggleFlagSpammer({$member['member_id']}, false)">{parse replacement="spammer_on"} {$this->lang->words['spm_on']}</a></li>
											<else />
												<li><a href='{$this->settings['base_url']}app=core&amp;module=modcp&amp;do=setAsSpammer&amp;member_id={$member['member_id']}&amp;auth_key={$this->member->form_hash}' onclick="return ipb.global.toggleFlagSpammer({$member['member_id']}, true)">{parse replacement="spammer_off"} {$this->lang->words['spm_off']}</a></li>
											</if>
										</if>
										<if test="dnameHistory:|:$this->memberData['member_id'] && $this->memberData['g_mem_info'] && $this->settings['auth_allow_dnames']">
											<li id='dname_history'><a href='{parse url="app=members&amp;module=profile&amp;section=dname&amp;id={$member['member_id']}" base="public"}' title='{$this->lang->words['view_dname_history']}'>{parse replacement="display_name"} {$this->lang->words['display_name_history']}</a></li>
										</if>
								
										<if test="supModCustomizationDisable:|:($member['member_id'] != $this->memberData['member_id'] AND $this->memberData['g_is_supmod'] ) AND $member['customization']['type']">
											<li><strong><a href='{parse url="showuser={$member['member_id']}&amp;secure_key={$this->member->form_hash}&amp;removeCustomization=1" seotitle="{$member['members_seo_name']}" template="showuser" base="public"}'><img src='{$this->settings['img_url']}/delete.png' alt='-' /> {$this->lang->words['cust_remove']}</a></strong></li>
											<li><strong><a href='{parse url="showuser={$member['member_id']}&amp;secure_key={$this->member->form_hash}&amp;removeCustomization=1&amp;disableCustomization=1" seotitle="{$member['members_seo_name']}" template="showuser" base="public"}'><img src='{$this->settings['img_url']}/delete.png' alt='-' /> {$this->lang->words['cust_disable']}</a></strong></li>
										</if>
									</ul>
								</div>
							</if>
							
							<if test="$member['pp_setting_count_friends'] and $this->settings['friends_enabled']">
								<div class='general_box clearfix' id='friends_overview'>
									<h3>{$this->lang->words['m_title_friends']}</h3>
									<div class='ipsPad'>
										<if test="hasFriends:|:count($friends) AND is_array($friends)">
											<foreach loop="friendsLoop:$friends as $friend">
												<a href='{parse url="showuser={$friend['member_id']}" base="public" template="showuser" seotitle="{$friend['members_seo_name']}"}' class='ipsUserPhotoLink'>
													<img src='{$friend['pp_mini_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' data-tooltip='{$friend['members_display_name']}' />
												</a>
											</foreach>
										<else />
											<p class='desc'>
												{$member['members_display_name']} {$this->lang->words['no_friends_yet']}
											</p>
										</if>
									</div>
								</div>
							</if>
							
							<if test="latest_visitors:|:$member['pp_setting_count_visitors']">
								<div class='general_box clearfix'>
									<h3>{$this->lang->words['latest_visitors']}</h3>
									<if test="has_visitors:|:is_array( $visitors ) && count( $visitors )">
										<ul class='ipsList_withminiphoto ipsPad'>
											<foreach loop="latest_visitors_loop:$visitors as $visitor">
											<li class='clearfix'>
												<if test="visitorismember:|:$visitor['member_id']">
													<a href='{parse url="showuser={$visitor['member_id']}" seotitle="{$visitor['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'><img src='{$visitor['pp_mini_photo']}' alt='{$this->lang->words['photo']}' class='ipsUserPhoto ipsUserPhoto_mini' /></a>
												<else />
													<img src='{$visitor['pp_mini_photo']}' alt='{$this->lang->words['photo']}' class='ipsUserPhoto ipsUserPhoto_mini left' />
												</if>
												<div class='list_content'>
													{parse template="userHoverCard" group="global" params="$visitor"}
													<br />
													<span class='desc lighter'>{$visitor['_visited_date']}</span>
												</div>
											</li>
											</foreach>
										</ul>
									<else />
										<p class='ipsPad desc'>{$this->lang->words['no_latest_visitors']}</p>
									</if>
								</div>
							</if>
						</div>
					</div>
					
					<if test="$default_tab != 'core:info'">
					<div id='pane_{$default_tab}'>
						{$default_tab_content}
					</div>
					</if>
				</div>
				
			</div>
		</div>
		
	</div>
</div>
<if test="thisIsNotUs:|:($this->memberData['member_id'] && $member['member_id'] != $this->memberData['member_id'])">
	<br />
	<ul class='topic_buttons'>
		<li class='non_button clearfix'><a href='{parse url="app=core&amp;module=reports&amp;section=reports&amp;rcom=profiles&amp;member_id={$member['member_id']}" base="public"}'>{$this->lang->words['report_member']}</a></li>
	</ul>
</if>
<script type='text/javascript'>
	$("profile_content").setStyle( { minHeight: $('profile_tabs').measure('margin-box-height') + 138 + "px" } );
</script>
<!-- ******************************************************************************************* -->
{parse template="include_highlighter" group="global" params=""}
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: reputationPage
//===========================================================================
function reputationPage($langBit, $currentApp='', $supportedApps=array(), $processedResults='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="like"}
<h1 class='ipsType_pagetitle'>{$this->lang->words[ $langBit ]}</h1>
<br />
<div class='general_box'>
	<if test="hasMoreThanOneApp:|:count( $supportedApps ) > 1">
		<div class='maintitle ipsFilterbar clearfix'>
			<ul class='ipsList_inline ipsType_smaller left'>
				<foreach loop="apps:$supportedApps as $_app">
					<li <if test="isTheActiveApp:|:$_app['app_directory'] == $currentApp">class='active'</if>>
						<a href='{parse url="app=members&amp;module=reputation&amp;section=most&amp;app_tab={$_app['app_directory']}" base="public" template="most_liked" seotitle="most_liked"}'>
							{IPSLib::getAppTitle($_app['app_directory'])}
						</a>
					</li>
				</foreach>
			</ul>
		</div>
	</if>
	<if test="hasResults:|:$processedResults">
		{$processedResults}
	<else />
		<div class='no_messages'>{$this->lang->words['reputation_empty']}</div>
	</if>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showCard
//===========================================================================
function showCard($member, $download=0) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='vcard userpopup'>
	<h3><a href="{parse url="showuser={$member['member_id']}" seotitle="{$member['members_seo_name']}" template="showuser" base="public"}" class="fn nickname url">{$member['members_display_name']}</a></h3>
	<div class='side left ipsPad'>
		<a href="{parse url="showuser={$member['member_id']}" seotitle="{$member['members_seo_name']}" template="showuser" base="public"}" class="ipsUserPhotoLink">
			<img src="{$member['pp_thumb_photo']}" alt="{$this->lang->words['get_photo']}" class='ipsUserPhoto ipsUserPhoto_large' />
		</a>
		<br />
		<if test="cardRep:|:$this->settings['reputation_enabled'] && $this->settings['reputation_show_profile']">
			<if test="cardRepPos:|:$member['pp_reputation_points'] > 0">
				<div class='reputation positive'>
			</if>
			<if test="cardRepNeg:|:$member['pp_reputation_points'] < 0">
				<div class='reputation negative'>
			</if>
			<if test="cardRepZero:|:$member['pp_reputation_points'] == 0">
				<div class='reputation zero'>
			</if>
					<span class='number'>{$member['pp_reputation_points']}</span>
				</div>
		</if>
		<a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;mid={$member['member_id']}" base="public"}' title='{$this->lang->words['gbl_find_my_content']}' class='ipsButton_secondary ipsType_smaller'>{$this->lang->words['gbl_find_my_content']}</a>
		<if test="cardSendPm:|:$this->memberData['member_id'] AND $this->memberData['member_id'] != $member['member_id'] AND $this->memberData['g_use_pm'] AND $this->memberData['members_disable_pm'] == 0 AND IPSLib::moduleIsEnabled( 'messaging', 'members' ) AND $member['members_disable_pm'] == 0">
			<a href='{parse url="app=members&amp;module=messaging&amp;section=send&amp;do=form&amp;fromMemberID={$member['member_id']}" base="public"}' title='{$this->lang->words['pm_this_member']}' id='pm_xxx_{$member['member_id']}' class='pm_button ipsButton_secondary ipsType_smaller'>{$this->lang->words['pm_this_member']}</a>
		</if>
	</div>
	<div class='ipsPad'>
		<if test="cardStatus:|:$member['_status']['status_content']">
			<p class='message user_status'>{$member['_status']['status_content']}</p>
		</if>
		<div class='info'>			
			<dl>
				<dt>{$this->lang->words['m_group']}</dt>
				<dd>{$member['_group_formatted']}</dd>
				<dt>{$this->lang->words['m_posts']}</dt>
				<dd>{parse format_number="$member['posts']"}</dd>
				<dt>{$this->lang->words['m_member_since']}</dt>
				<dd>{parse date="$member['joined']" format="joined"}</dd>
				<dt>{$this->lang->words['m_last_active']}</dt>
				<dd><if test="cardOnline:|:$member['_online']"><span class='ipsBadge ipsBadge_green'>{$this->lang->words['online_online']}</span><else /><span class='ipsBadge ipsBadge_grey'>{$this->lang->words['online_offline']}</span></if> {$member['_last_active']}</dd>
				<if test="cardWhere:|:$member['_online'] && ($member['online_extra'] != $this->lang->words['not_online'])">
					<dt>{$this->lang->words['m_currently']}</dt>
					<dd>
						{$member['online_extra']}
					</dd>
				</if>
				<if test="isadmin:|:$this->memberData['g_access_cp'] == 1">
					<dt>{$this->lang->words['m_email']}</dt>
					<dd><a href='mailto:{$member['email']}'>{$member['email']}</a></dd>
				</if>
			</dl>
		</div>
		<ul class='user_controls clear'>
			<if test="authorspammer:|:$member['spamStatus'] !== NULL && $member['member_id'] != $this->memberData['member_id']">
				<if test="authorspammerinner:|:$member['spamStatus'] === TRUE">
					<li><a href='#' title='{$this->lang->words['spm_on']}' onclick="return ipb.global.toggleFlagSpammer({$member['member_id']}, false)">{parse replacement="spammer_on"}</a></li>
				<else />
					<li><a title='{$this->lang->words['spm_off']}' href='{$this->settings['base_url']}app=core&amp;module=modcp&amp;do=setAsSpammer&amp;member_id={$member['member_id']}&amp;auth_key={$this->member->form_hash}' onclick="return ipb.global.toggleFlagSpammer({$member['member_id']}, true)">{parse replacement="spammer_off"}</a></li>
				</if>
			</if>
			<if test="cardFriend:|:$this->memberData['member_id'] AND $this->memberData['member_id'] != $member['member_id'] && $this->settings['friends_enabled'] AND $this->memberData['g_can_add_friends']">
				<if test="cardIsFriend:|:IPSMember::checkFriendStatus( $member['member_id'] )">
					<li><a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=remove&amp;member_id={$member['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['remove_friend']}'>{parse replacement="remove_friend"}</a></li>
				<else />
					<li><a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=add&amp;member_id={$member['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['add_friend']}'>{parse replacement="add_friend"}</a></li>								
				</if>
			</if>			
			<if test="cardBlog:|:$member['has_blog'] AND IPSLib::appIsInstalled( 'blog' )">
				<li><a href='{parse url="app=blog&amp;module=display&amp;section=blog&amp;mid={$member['member_id']}" base="public"}' title='{$this->lang->words['view_blog']}'>{parse replacement="blog_link"}</a></li>
			</if>
			<if test="cardGallery:|:$member['has_gallery'] AND IPSLib::appIsInstalled( 'gallery' )">
				<li><a href='{parse url="app=gallery&amp;user={$member['member_id']}" seotitle="{$member['members_seo_name']}" template="useralbum" base="public"}' title='{$this->lang->words['view_gallery']}'>{parse replacement="gallery_link"}</a></li>
			</if>
		</ul>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: statusReplies
//===========================================================================
function statusReplies($replies=array(), $no_wrapper=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="!$no_wrapper">
	<ul class='ipsList_withtinyphoto clear'>
</if>
<foreach loop="innerLoop:$replies as $reply">
	<li id='statusReply-{$reply['reply_id']}' class='ipsPad row2'>
		<a href='{parse url="showuser={$reply['member_id']}" seotitle="{$reply['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'><img src='{$reply['pp_mini_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$reply['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_tiny' /></a>	
		<div class="status_mini_content list_content">
			<h5><strong>{parse template="userHoverCard" group="global" params="$reply"}</strong></h5>
			{$reply['reply_content']}
			<br />
			<span class='desc lighter'>{$reply['reply_date_formatted']}</span>
			<span class='desc mod_links'>
				<if test="canDelete:|:$reply['_canDelete']"> &middot; <a href="{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=deleteReply&amp;status_id={$reply['reply_status_id']}&amp;reply_id={$reply['reply_id']}&amp;k={$this->member->form_hash}" id="statusReplyDelete-{$reply['reply_status_id']}-{$reply['reply_id']}" class="__sDR __dr{$reply['reply_status_id']}-{$reply['reply_id']}">{$this->lang->words['status_delete_link']}</a></if>
			</span>
		</div>
	</li>
</foreach>
<if test="!$no_wrapper">
	</ul>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: statusUpdates
//===========================================================================
function statusUpdates($updates=array(), $smallSpace=0, $latestOnly=0) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<foreach loop="outerLoop:$updates as $id => $status">
	<if test="$this->memberData['member_id'] AND $latestOnly AND $status['member_id'] == $this->memberData['member_id']">
	<script type="text/javascript">
		ipb.status.myLatest = {$status['status_id']};
	</script>
	</if>
	<div class='ipsBox_container ipsPad' id='statusWrap-{$status['status_id']}'>
		<a href='{parse url="showuser={$status['member_id']}" seotitle="{$status['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'>
			<img src='{$status['pp_small_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$status['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_medium' />
		</a>	
		<div class="ipsBox_withphoto status_content">
			<div id="statusContent-{$status['status_id']}">
				<h4>
					{parse template="userHoverCard" group="global" params="$status"}
					<if test="forSomeoneElse:|:$status['status_member_id'] != $status['status_author_id']">
						&rarr;
						{parse template="userHoverCard" group="global" params="$status['owner']"}
					</if>
				</h4>
				<div class='status_status'>
					{$status['status_content']}
				</div>
				<span class='desc lighter blend_links'>
					<img src="{$this->settings['img_url']}/icon_lock.png" id='statusLockImg-{$status['status_id']}' alt="{$this->lang->words['status__locked']}" <if test="noLocked:|:!$status['status_is_locked']">style='display: none'</if> />
					<if test="cImg:|:! $smallSpace AND $status['_creatorImg']"><img src="{$status['_creatorImg']}" alt='' /></if>
					<a href='{parse url="app=members&amp;module=profile&amp;section=status&amp;type=single&amp;status_id={$status['status_id']}" seotitle="true" template="members_status_single" base="public"}'>{$status['status_date_formatted_short']}</a><if test="creatorText:|:$smallSpace AND $status['_creatorText'] AND $status['status_creator'] AND $status['status_creator'] != 'ipb'"> {$this->lang->words['su_via']} {$status['_creatorText']}</if>
				</span>
				<span class='mod_links'>
					<if test="canDelete:|:$status['_canDelete']"> &middot; <a rel="nofollow" href="{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=deleteStatus&amp;status_id={$status['status_id']}&amp;k={$this->member->form_hash}" id="statusDelete-{$status['status_id']}" class="__sD __d{$status['status_id']}">{$this->lang->words['status_delete_link']}</a></if>
					<span id='statusUnlock-{$status['status_id']}' <if test="isLocked:|:$status['_isLocked'] AND $status['_canUnlock']">style='display:inline'<else />style='display:none'</if>> &middot; <a rel="nofollow"  href="{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=unlockStatus&amp;status_id={$status['status_id']}&amp;k={$this->member->form_hash}" id="statusUnlockLink-{$status['status_id']}" class="__sU __u{$status['status_id']}">{$this->lang->words['status_unlock_link']}</a></span>
					<span id='statusLock-{$status['status_id']}' <if test="canLock:|:$status['_canLock'] AND ! $status['_isLocked']">style='display:inline'<else />style='display:none'</if>> &middot; <a rel="nofollow"  href="{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=lockStatus&amp;status_id={$status['status_id']}&amp;k={$this->member->form_hash}" id="statusLockLink-{$status['status_id']}" class="__sL __l{$status['status_id']}">{$this->lang->words['status_lock_link']}</a></span>
				</span>
			</div>
			<div id="statusFeedback-{$status['status_id']}" class='status_feedback'>
				<if test="$status['status_replies'] AND count( $status['replies'] )">
					<if test="hasMore:|:$status['status_replies'] > 3">
						<div class='status_mini_wrap row2 altrow' id='statusMoreWrap-{$status['status_id']}'>
							<img src="{$this->settings['img_url']}/comments.png" alt="" /> &nbsp;<a href="#" id="statusMore-{$status['status_id']}" class='__showAll __x{$status['status_id']}'>{parse expression="sprintf( $this->lang->words['status_show_all_x'], $status['status_replies'] )"}</a>
						</div>
					</if>
					<ul id='statusReplies-{$status['status_id']}' class='ipsList_withtinyphoto clear'>
						{parse template="statusReplies" group="profile" params="$status['replies'], 1"}
					</ul>
				</if>
				<div id='statusReplyBlank-{$status['status_id']}'></div>
				<div id='statusReply-{$status['status_id']}'>
				<if test="$status['_userCanReply']">
					<ul class='ipsList_withtinyphoto reply row2 ipsPad'>
						<li>
							<form id='statusReplyForm-{$status['status_id']}' action='{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=reply&amp;status_id={$status['status_id']}&amp;k={$this->member->form_hash}&amp;id={$this->memberData['member_id']}' method='post'>
								<a href='{parse url="showuser={$this->memberData['member_id']}" seotitle="{$this->memberData['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'><img src='{$this->memberData['pp_mini_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$this->memberData['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_tiny' /></a>
								<div class='list_content'>
									<div class="status_mini_content">
										<textarea class='status_textarea input_text' rows='3' cols='50' name='comment-{$status['status_id']}' id='statusText-{$status['status_id']}'></textarea>
										<div class="status_submit"><input type='submit' class='__submit input_submit' id='statusSubmit-{$status['status_id']}' value='{$this->lang->words['status__dfcomment']}' /></div>
									</div>									
								</div>
							</form>
							<a href='#' class='__showform' id='statusReplyFormShow-{$status['status_id']}' style='display: none'>{$this->lang->words['status__addcomment']}</a>
						</li>
					</ul>
					<script type='text/javascript'>
						if( $('statusReplyForm-{$status['status_id']}') )
						{
							$('statusReplyForm-{$status['status_id']}').hide();
						}
						
						if( $('statusReplyFormShow-{$status['status_id']}') )
						{
							$('statusReplyFormShow-{$status['status_id']}').show();
						}
					</script>
				</if>
				</div>
				<div class='status_mini_wrap row2 altrow' id='statusMaxWrap-{$status['status_id']}' <if test="maxReplies:|:$status['status_replies'] < $this->settings['su_max_replies']">style='display:none'</if>>
					<img src="{$this->settings['img_url']}/locked_replies.png" title="{$this->lang->words['status_too_many_replies']}" alt='x' /> {$this->lang->words['status_too_many_replies']}
				</div>
			</div>
		</div>
	</div>
</foreach>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: statusUpdatesPage
//===========================================================================
function statusUpdatesPage($updates=array(), $pages='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="status"}
{parse striping="recent_status" classes="row1,row2 altrow"}
<h1 class='ipsType_pagetitle'>{$this->lang->words['status_updates__overview']}</h1>
<br />
<div id='status_standalone_page'>
	<div class='maintitle ipsFilterbar'>
		<ul class='ipsList_inline'>
			<li id='status_all' class='<if test="tabactive:|:! $this->request['status_id'] AND ! $this->request['member_id'] AND ! $this->request['type'] OR $this->request['type'] == 'all'">active</if>'><a href='{parse url="app=members&amp;module=profile&amp;section=status&amp;type=all" seotitle="true" template="members_status_all" base="public"}'>{$this->lang->words['status__all_updates']}</a></li>
			<if test="$this->memberData['member_id'] AND $this->settings['friends_enabled']">
				<li id='status_all' class='tab_toggle <if test="tabactive2:|:$this->request['type'] == 'friends'">active</if>'><a href='{parse url="app=members&amp;module=profile&amp;section=status&amp;type=friends" seotitle="true" template="members_status_friends" base="public"}'>{$this->lang->words['status__myfriends']}</a></li>
			</if>
			<if test="$this->request['member_id']">
				<li id='status_by_id' class='active'><a href='#'>{$this->lang->words['status__membersupdats']}</a></li>
			</if>
			<if test="$this->request['status_id']">
				<li id='status_by_sid' class='active'><a href='#'>{$this->lang->words['status__singleupdate']}</a></li>
			</if>
		</ul>
	</div>
	<if test="canCreate:|:$this->memberData['member_id'] AND $this->registry->getClass('memberStatus')->canCreate( $this->memberData )">
		<div class='status_update row2'>
			<form id='statusForm' action='{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=new&amp;k={$this->member->form_hash}&amp;id={$this->memberData['member_id']}' method='post'>
			<input type='text' id='statusUpdate_page' name='content' style='width:60%' class='input_text'> <input type='submit' class='ipsButton' id='statusSubmit_page' value='{$this->lang->words['gbl_post']}' />
			<if test="update:|:(IPSLib::twitter_enabled() OR IPSLib::fbc_enabled() ) AND ( $this->memberData['fb_uid'] OR $this->memberData['twitter_id'] )">
				<p class='desc' style='padding-top:5px;'>{$this->lang->words['st_update']}
					<if test="updateTwitter:|:IPSLib::twitter_enabled() AND ( $this->memberData['twitter_id'] )"><input type='checkbox' id='su_Twitter' value='1' name='su_Twitter' /> <img src="{$this->settings['public_dir']}style_status/twitter.png" style='vertical-align:top' alt='' /></if>
					<if test="updateFacebook:|:IPSLib::fbc_enabled() AND ( $this->memberData['fb_uid'] )"><input type='checkbox' id='su_Facebook' value='1' name='su_Facebook' /> <img src="{$this->settings['public_dir']}style_status/facebook.png" style='vertical-align:top' alt='' /></if>
				</p>
			</if>
			</form>
		</div>
	</if>
	<div id="status_wrapper" class='ipsBox'>
		<if test="hasUpdates:|:count( $updates )">
			{parse template="statusUpdates" group="profile" params="$updates"}
		<else />
			<p class='no-status'>{$this->lang->words['status_updates_none']}</p>
		</if>
	</div>
	<div class='topic_controls clearfix'>
		{$pages}
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: tabFriends
//===========================================================================
function tabFriends($friends=array(), $member=array(), $pagination='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='general_box'>
	<if test="friends:|:$this->settings['friends_enabled'] AND $this->memberData['g_can_add_friends'] AND $member['pp_setting_count_friends']">
		<div class='friend_list clear' id='friend_list'>
			<h3 class='bar'>{$this->lang->words['m_title_friends']}</h3>
			<if test="friends_loop:|:is_array($friends) and count($friends)">
				<ul class='clearfix'>
				<foreach loop="friends:$friends as $friend">
					<li>
						<a href='{parse url="showuser={$friend['member_id']}" seotitle="{$friend['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink'>
							<img src='{$friend['pp_small_photo']}' alt='{$this->lang->words['photo']}' class='ipsUserPhoto ipsUserPhoto_medium' />
						</a><br />
						<span class='name'>
							{parse expression="IPSMember::makeProfileLink($friend['members_display_name_short'], $friend['member_id'], $friend['members_seo_name'])"}
						</span>
					</li>
				</foreach>
				</ul>				
			<else />
				<p>
					<em>{$member['members_display_name']} {$this->lang->words['no_friends_yet']}</em>
				</p>
			</if>
		</div>
		<br />
		{$pagination}
	</if>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: tabNoContent
//===========================================================================
function tabNoContent($langkey) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='no_messages'>{$this->lang->words[ $langkey ]}</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: tabPosts
//===========================================================================
function tabPosts($content) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3 class='maintitle'>{$this->lang->words['posts_made']}</h3>
<div class='ipsBox'>
	<div class='ipsBox_container'>
		{$content}
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: tabReputation
//===========================================================================
function tabReputation($member, $currentApp='', $type='', $supportedApps=array(), $processedResults='', $pagination='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='general_box'>
	<div class='maintitle ipsFilterbar clearfix'>
		<if test="hasMoreThanOneApp:|:count( $supportedApps ) > 1">
			<ul class='ipsList_inline ipsType_smaller left'>
				<foreach loop="apps:$supportedApps as $_app">
					<li <if test="isTheActiveApp:|:$_app['app_directory'] == $currentApp">class='active'</if>>
						<a href='{parse url="showuser={$member['member_id']}&amp;tab=reputation&amp;app_tab={$_app['app_directory']}&amp;type={$type}" seotitle="{$member['members_seo_name']}" template="showuser" base="public"}'>
							{IPSLib::getAppTitle($_app['app_directory'])}
						</a>
					</li>
				</foreach>
			</ul>
		</if>
		<ul class='ipsList_inline ipsType_smaller right'>
			<li <if test="currentIsGiven:|:$type == 'given'">class='active'</if>>
				<a href='{parse url="showuser={$member['member_id']}&amp;tab=reputation&amp;app_tab={$currentApp}&amp;type=given" seotitle="{$member['members_seo_name']}" template="showuser" base="public"}'>
					{$this->lang->words['reputation_given']}
				</a>
			</li>
			<li <if test="currentIsReceived:|:$type == 'received'">class='active'</if>>
				<a href='{parse url="showuser={$member['member_id']}&amp;tab=reputation&amp;app_tab={$currentApp}&amp;type=received" seotitle="{$member['members_seo_name']}" template="showuser" base="public"}'>
					{$this->lang->words['reputation_received']}
				</a>
			</li>
		</ul>
	</div>
	<if test="hasResults:|:$processedResults">
		{$processedResults}
	<else />
		<div class='no_messages'>{$this->lang->words['reputation_empty']}</div>
	</if>
	<if test="bottomPagination:|:$pagination">
		<br />
		{$pagination}
		<br class='clear' />
	</if>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: tabReputation_calendar
//===========================================================================
function tabReputation_calendar($results) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='ipsBox'>
	<foreach loop="$results as $data">
		<if test="$data['type'] == 'comment_id'">
			<div class='post_block hentry clear no_sidebar ipsBox_container'>
				<div class='post_wrap'>
					<if test="postMid:|:$data['comment_mid']">
						<h3 class='row2'>
					<else />
						<h3 class='guest row2'>
					</if>
						<a href="{parse url="app=calendar&amp;module=calendar&amp;section=view&amp;do=showevent&amp;event_id={$data['event_id']}" template="cal_event" seotitle="{$data['event_title_seo']}" base="public"}">{IPSText::truncate( $data['event_title'], 80)}</a>
					</h3>
					<div class='post_body'>
						<p class='posted_info desc lighter ipsType_small'>
							<img src='{$data['pp_thumb_photo']}' class='ipsUserPhoto ipsUserPhoto_tiny' /> {$this->lang->words['posted']} {$this->lang->words['by']}
							<if test="postMember:|:$data['member_id']"><span class="author vcard">{parse template="userHoverCard" group="global" params="$data"}</span><else />{$data['members_display_name']}</if>
							{$this->lang->words['on']} <abbr class="published" title="{parse expression="date( 'c', $data['comment_date'] )"}">{parse date="$data['comment_date']" format="long"}</abbr>
							{$this->lang->words['cal_in']}
							<a href="{parse url="app=calendar&amp;module=calendar&amp;section=view&amp;do=showevent&amp;event_id={$data['event_id']}" template="cal_event" seotitle="{$data['event_title_seo']}" base="public"}">{$data['event_title']}</a>
						</p>
						<div class='post entry-content clearfix'>
							{$data['comment_text']}
							<br />
							{parse template="repButtons" group="global_other" params="$data, array_merge( array( 'primaryId' => $data['comment_id'], 'domLikeStripId' => 'like_comment_id_' . $data['comment_id'], 'domCountId' => 'rep_comment_id_' . $data['comment_id'], 'app' => 'calendar', 'type' => 'comment_id', 'likeFormatted' => $data['repButtons']['formatted'] ), $data )"}
						</div>
					</div>
				</div>
				<br />
			</div>
			<br />
		<else />
			<div class='post_block hentry clear no_sidebar ipsBox_container'>
				<div class='post_wrap'>
					<if test="postMid:|:$data['event_member_id']">
						<h3 class='row2'>
					<else />
						<h3 class='guest row2'>
					</if>
						<a href="{parse url="app=calendar&amp;module=calendar&amp;section=view&amp;do=showevent&amp;event_id={$data['event_id']}" template="cal_event" seotitle="{$data['event_title_seo']}" base="public"}">{IPSText::truncate( $data['event_title'], 80)}</a>
					</h3>
					<div class='post_body'>
						<p class='posted_info desc lighter ipsType_small'>
							<img src='{$data['pp_thumb_photo']}' class='ipsUserPhoto ipsUserPhoto_tiny' /> {$this->lang->words['posted']} {$this->lang->words['by']}
							<if test="postMember:|:$data['member_id']"><span class="author vcard">{parse template="userHoverCard" group="global" params="$data"}</span><else />{$data['members_display_name']}</if>
							{$this->lang->words['on']} <abbr class="published" title="{parse expression="date( 'c', $data['event_saved'] )"}">{parse date="$data['event_saved']" format="long"}</abbr>
						</p>
						<div class='post entry-content clearfix'>
							{$data['event_content']}
							<br />
							{parse template="repButtons" group="global_other" params="$data, array_merge( array( 'primaryId' => $data['event_id'], 'domLikeStripId' => 'like_event_id_' . $data['event_id'], 'domCountId' => 'rep_event_id_' . $data['event_id'], 'app' => 'calendar', 'type' => 'event_id', 'likeFormatted' => $data['repButtons']['formatted'] ), $data )"}
						</div>
					</div>
				</div>
				<br />
			</div>
			<br />
		</if>
	</foreach>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: tabReputation_posts
//===========================================================================
function tabReputation_posts($results) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='ipsBox'>
	<foreach loop="$results as $data">
		<div class='post_block hentry clear no_sidebar ipsBox_container'>
			<div class='post_wrap'>
				<if test="postMid:|:$data['author_id']">
					<h3 class='row2'>
				<else />
					<h3 class='guest row2'>
				</if>
					<span class='post_id right ipsType_small desc blend_links'><a href='{parse url="showtopic={$data['topic_id']}&amp;view=findpost&amp;p={$data['pid']}" template="showtopic" seotitle="{$data['title_seo']}" base="public"}' rel='bookmark' title='{$this->lang->words['link_to_post']} #{$data['pid']}'>#{$data['pid']}</a></span>
					<a href="{parse url="showtopic={$data['tid']}" seotitle="{$data['title_seo']}" template="showtopic" base="public"}">{IPSText::truncate( $data['topic_title'], 80)}</a>
				</h3>
				<div class='post_body'>
					<p class='posted_info desc lighter ipsType_small'>
						<img src='{$data['pp_thumb_photo']}' class='ipsUserPhoto ipsUserPhoto_tiny' /> {$this->lang->words['posted']} {$this->lang->words['search_by']}
						<if test="postMember:|:$data['member_id']"><span class="author vcard">{parse template="userHoverCard" group="global" params="$data"}</span><else />{$data['members_display_name']}</if>
						{$this->lang->words['on']} <abbr class="published" title="{parse expression="date( 'c', $data['post_date'] )"}">{parse date="$data['post_date']" format="long"}</abbr>
						<if test="hasForumTrail:|:$data['_forum_trail']">
							in
							<foreach loop="topicsForumTrail:$data['_forum_trail'] as $i => $f">
								<if test="notLastFtAsForum:|:$i+1 == count( $data['_forum_trail'] )"><a href='{parse url="{$f[1]}" template="showforum" seotitle="{$f[2]}" base="public"}'>{$f[0]}</a></if>
							</foreach>
						</if>
					</p>
					<div class='post entry-content clearfix'>
						{$data['post']}
						<br />
						{parse template="repButtons" group="global_other" params="$data, array_merge( array( 'primaryId' => $data['pid'], 'domLikeStripId' => 'like_post_' . $data['pid'], 'domCountId' => 'rep_post_' . $data['pid'], 'app' => 'forums', 'type' => 'pid', 'likeFormatted' => $data['repButtons']['formatted'], 'has_given_rep' => $data['repButtons']['iLike'] ), $data )"}
					</div>
				</div>
			</div>
			<br />
		</div>
		<br />
	</foreach>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: tabSingleColumn
//===========================================================================
function tabSingleColumn($row=array(), $read_more_link='', $url='', $title='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='post_block no_sidebar'>
	<div class='post_wrap'>
		<if test="singleColumnTitle:|:$title">
			<if test="singleColumnUrl:|:$url">
				<h3 class='row2'><a href="$url" title="{$this->lang->words['view_topic']}">{parse expression="IPSText::truncate( $title, 90 )"}</a></h3>
			<else />
				<h3 class='row2'>{parse expression="IPSText::truncate( $title, 90 )"}</h3>
			</if>
		</if>
		<div class='post_body'>
			<p class='posted_info'>
				<if test="date:|:$row['_raw_date']">{parse date="$row['_raw_date']" format="long"}<else />{$this->lang->words['posted']} {$row['_date_array']['mday']} {$row['_date_array']['smonth']} {$row['_date_array']['year']}</if>
			</p>
			<div class='post'>
				{$row['post']}
			</div>
		</div>
	</div>
	<br />
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: tabStatusUpdates
//===========================================================================
function tabStatusUpdates($updates=array(), $actions, $member=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse striping="recent_status" classes="row1,row2"}
<h2 class='maintitle'>{$this->lang->words['pp_tab_statusupdates']}</h2>
<if test="canCreate:|:$this->memberData['member_id'] AND ( $this->memberData['member_id'] == $member['member_id'] ) AND $this->registry->getClass('memberStatus')->canCreate( $member )">
	<div class='status_update'>
		<form id='statusForm' action='{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=new&amp;k={$this->member->form_hash}&amp;id={$this->memberData['member_id']}&amp;forMemberId={$member['member_id']}' method='post'>
		<input type='text' name='content' id='statusUpdate_page' class='input_text' /> <input type='submit' class='ipsButton' id='statusSubmit_page' value='{$this->lang->words['gbl_post']}' />
		<if test="update:|:( IPSLib::loginMethod_enabled('facebook') AND $this->memberData['fb_uid'] ) OR ( IPSLib::loginMethod_enabled('twitter') AND $this->memberData['twitter_id'] )">
			<p class='desc' style='padding-top:5px;'>{$this->lang->words['st_update']}
				<if test="updateTwitter:|:IPSLib::loginMethod_enabled('twitter') AND $this->memberData['twitter_id']"><input type='checkbox' id='su_Twitter' value='1' name='su_Twitter' /> <img src="{$this->settings['public_dir']}style_status/twitter.png" style='vertical-align:top' alt='' /></if>
				<if test="updateFacebook:|:IPSLib::loginMethod_enabled('facebook') AND $this->memberData['fb_uid']">&nbsp;<input type='checkbox' id='su_Facebook' value='1' name='su_Facebook' /> <img src="{$this->settings['public_dir']}style_status/facebook.png" style='vertical-align:top' alt='' /></if>
			</p>
		</if>
		</form>
	</div>
</if>
<if test="leave_comment:|:$this->memberData['member_id'] && $this->memberData['member_id'] != $member['member_id'] && $member['pp_setting_count_comments']">
	<div class='status_update'>
		<form id='commentForm' action='{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=new&amp;k={$this->member->form_hash}&amp;id={$this->memberData['member_id']}&amp;forMemberId={$member['member_id']}' method='post'>
				<input type='hidden' name='member_id' value='{$member['member_id']}' />
				<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
				<div id='post_comment'>
					<input type='text' class='input_text' cols='50' rows='3' id='statusUpdate_page' name='content' data-for-member-id="{$member['member_id']}" />
					<input type='submit' class='ipsButton' value='{$this->lang->words['comment_submit_post']}' data-for-member-id="{$member['member_id']}" id='statusSubmit_page' />
				</div> 
		</form>
	</div>
</if>
<div class='ipsBox clearfix'>
	<div id="status_wrapper" data-member="{$member['member_id']}">
		<if test="hasUpdates:|:count( $updates )">
			{parse template="statusUpdates" group="profile" params="$updates"}
			<div style='text-align: center'>
				<a href='{parse url="app=members&amp;module=profile&amp;section=status&amp;member_id={$member['member_id']}" seotitle="true" template="members_status_all" base="public"}' class='ipsButton_secondary'>{$this->lang->words['status__viewall']}</a>
			</div>
		<else />
			<p class='ipsBox_container ipsPad'>{$this->lang->words['status_updates_none']}</p>
		</if>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: tabTopics
//===========================================================================
function tabTopics($content) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3 class='maintitle'>{$this->lang->words['topics_started']}</h3>
<div class='ipsBox'>
	<div class='ipsBox_container'>
		{$content}
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>