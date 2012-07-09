<?php
/**
 * Master skin file
 * Written: Mon, 09 Jul 2012 18:16:42 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: deletedPosts
//===========================================================================
function deletedPosts($posts, $other_data=array(), $pagelinks='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="topic"}
<if test="disablelightbox:|:!$this->settings['disable_lightbox']">
{parse template="include_lightbox" group="global" params=""}
</if>
<script type='text/javascript'>
	ipb.topic.inSection	= 'topicview';
</script>
<style type='text/css'>
	.post_mod, .post_id { display: none; }
</style>
{$this->templateVars['test']}
<div>{$pagelinks}</div>
{parse template="subTabLoop" group="modcp" params=""}
<div class='clearfix'>
	<if test="hasPosts:|:is_array( $posts ) AND count( $posts )">
		<div id='ips_Posts'>
			<foreach loop="post_data:$posts as $post">
					{parse template="modCPpost" group="modcp" params="$post, array( 'sdData' => $other_data ), $post, 'deleted'"}
			</foreach>
		</div>
	<else />
		<div class='no_messages'>
			{$this->lang->words['no_deleted_posts']}
		</div>
	</if>
	<div>{$pagelinks}</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: deletedTopics
//===========================================================================
function deletedTopics($topics, $sdelete=array(), $pagelinks='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="forums"}
<div>{$pagelinks}</div>
{parse template="subTabLoop" group="modcp" params=""}
<div class='clearfix'>
	<table class='ipb_table topic_list' id='forum_table'>
		<if test="hastopics:|:is_array( $topics ) AND count( $topics )">
			{parse template="modCPtopic" group="modcp" params="$topics, $pagelinks, 'deleted', $sdelete"}
		<else />
			<tr> 
				<td colspan='3' class='no_messages'>{$this->lang->words['no_deleted_topics']}</td>
			</tr>
		</if>
	</table>
	<div>{$pagelinks}</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: editUserForm
//===========================================================================
function editUserForm($profile, $custom_fields=null) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form action='{parse url="app=core&amp;module=modcp&amp;do=doeditmember" base="public"}' method='post'>
<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
<input type='hidden' name='mid' value='{$profile['member_id']}' />
<input type="hidden" name="_st" value="{$this->request['_st']}" />
<input type="hidden" name="t" value="{$this->request['t']}" />
<input type="hidden" name="pf" value="{$this->request['pf']}" />
<h2 class='maintitle'>{$this->lang->words['cp_em_title']}: <a href='{parse url="showuser={$profile['_parsedMember']['member_id']}" seotitle="{$profile['_parsedMember']['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}'>{$profile['_parsedMember']['members_display_name']}</a></h2>
<div class=''>
	
	<fieldset class='with_subhead'>
		<h3 class='bar'>{$this->lang->words['warn_member_details']}</h3>
		<h4>
			<img class="ipsUserPhoto ipsUserPhoto_large" src='{$profile['_parsedMember']['pp_thumb_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$profile['_parsedMember']['members_display_name'])"}" />
		</h4>
		<ul>
			<li class='field'>
				{parse replacement="find_topics_link"} <a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;mid={$profile['_parsedMember']['member_id']}" base="public"}'>{$this->lang->words['gbl_find_my_content']}</a>
			</li>
			<if test="displaynamehistory:|:$this->memberData['g_mem_info'] && $this->settings['auth_allow_dnames']">
				<li class='field' id='dname_history'>
					{parse replacement="display_name"} <a href='{parse url="app=members&amp;module=profile&amp;section=dname&amp;id={$profile['_parsedMember']['member_id']}" base="public"}' title='{$this->lang->words['view_dname_history']}'>{$this->lang->words['display_history']}</a>
				</li>
			</if>
			<li class='field'>
				<img src='{$this->settings['img_url']}/warn.png' /> <a href='{parse url="app=members&amp;module=profile&amp;section=warnings&amp;member={$profile['_parsedMember']['member_id']}&amp;from_app=members" base="public"}' id='warn_link_xxx_{$profile['_parsedMember']['member_id']}' title='{$this->lang->words['warn_view_history']}'>{parse expression="sprintf( $this->lang->words['warn_status'], $profile['_parsedMember']['warn_level'] )"}</a>
			</li>
		</ul>
	</fieldset>
	<fieldset>
		<h3 class='bar'>{$this->lang->words['edit_user_images']}</h3>
		<ul class='ipsForm ipsForm_horizontal'>
			<li class='ipsField clearfix ipsPad_top'>
				<label for='photo' class='ipsField_title'>{$this->lang->words['cp_remove_photo']}</label>
				<p class='ipsForm_content'>
					<select name="photo" id='photo' class='input_select'>
						<option value="0">{$this->lang->words['no']}</option>
						<option value="1">{$this->lang->words['yes']}</option>
					</select>
				</p>
			</li>
		</ul>
		<h3 class='bar'>{$this->lang->words['edit_user_permissions']}</h3>
		<ul class='ipsForm ipsForm_horizontal'>
			<li class='ipsField clearfix ipsPad_top'>
				<label for='photo' class='ipsField_title'>{$this->lang->words['cp_can_post_status_updates']}</label>
				<p class='ipsForm_content'>
					<select name="status_updates" class='input_select'>
						<option value="0" <if test="$profile['_parsedMember']['bw_no_status_update'] == 1">selected='selected'</if>>{$this->lang->words['no']}</option>
						<option value="1" <if test="$profile['_parsedMember']['bw_no_status_update'] == 0">selected='selected'</if>>{$this->lang->words['yes']}</option>
					</select>
				</p>
			</li>
		</ul>
		<if test="editusercfields:|:count($custom_fields->out_fields)">
			<h3 class='bar'>{$this->lang->words['edit_user_profile']}</h3>
			<ul class='ipsForm ipsForm_horizontal'>
				<li class='ipsField clearfix ipsPad_top'>
					<label for='title' class='ipsField_title'>{$this->lang->words['edit_user_title']}</label>
					<p class='ipsField_content'>
						<input type='text' class="input_text" name='title' size='50' value='{$profile['title']}' />
					</p>
				</li>
				<foreach loop="custom_fields:$custom_fields->out_fields as $id => $data">
					<li class='ipsField clearfix ipsPad_top'>
						<label for='field_{$id}' class='ipsField_title'>{$custom_fields->field_names[ $id ]}</label>
						<div class='ipsField_content'>
							{$data}
							<if test="editusercfielddesc:|:$custom_fields->field_desc[ $id ]">
								<br /><span class='desc'>{$custom_fields->field_desc[ $id ]}</span>
							</if>
						</div>
					</li>
				</foreach>
			</ul>
		</if>
		<h3 class='bar'>{$this->lang->words['cp_edit_signature']}</h3>
		<div class='ipsPad'>
			{$profile['signature']}
		</div>
		<h3 class='bar'>{$this->lang->words['cp_edit_aboutme']}</h3>
		<div class='ipsPad'>
			{$profile['aboutme']}
		</div>
	</fieldset>
	<fieldset class='submit'>
		<input type="submit" class='input_submit' value="{$this->lang->words['cp_em_submit']}" />
	</fieldset>
</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: inlineModIPMessage
//===========================================================================
function inlineModIPMessage($msg='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<p class='message unspecific'>{$msg}</p>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: latestWarnLogs
//===========================================================================
function latestWarnLogs($warnings, $pagelinks) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div>{$pagelinks}</div>
{parse template="subTabLoop" group="modcp" params=""}
<div class='clearfix'>
	<table class='ipb_table'>
		<tr class='header'>
			<th scope='col'>{$this->lang->words['w_v_warnto']}</th>
			<th scope='col'>{$this->lang->words['w_v_warnby']}</th>
			<th scope='col'>{$this->lang->words['w_v_reason']}</th>
			<th scope='col'>&nbsp;</th>
		</tr>
		<if test="haswarnings:|:is_array( $warnings ) AND count( $warnings )">
			{parse striping="warnings" classes="row1,row2 altrow"}
			<foreach loop="warningloop:$warnings as $r">
				<tr class='{parse striping="warnings"}'>
					<td><img src='{$r['pp_mini_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' alt='' /> {parse template="userHoverCard" group="global" params="$r"}</td>
					<td>{$r['punisher_photo']} {parse template="userHoverCard" group="global" params="$r['punisherMember']"}</td>
					<td>{$r['wl_reason']}</td>
					<td><span class='ipsButton_secondary clickable' onclick='warningPopup( this, {$r['wl_id']} )'>{$this->lang->words['w_v_moreinfo']}</span></td>
				</tr>
			</foreach>
		<else />
			<tr>
				<td class="no_messages" colspan="3">{$this->lang->words['warn__no_recent']}</td>
			</tr>
		</if>
	</table>
	<div>{$pagelinks}</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: memberLookup
//===========================================================================
function memberLookup() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="canEditMember:|:$this->memberData['g_is_supmod']">
{parse js_module="modcp"}
<script type='text/javascript'>
ipb.modcp.initialtext = "{$this->lang->words['start_typing_lup']}";
</script>
<h2 class='maintitle'>{$this->lang->words['memberlookup_title']}</h2>
<div class='clearfix ipsBox'>
	<div class='ipsBox_container ipsPad'>
		<h3 class='ipsType_subtitle'>{$this->lang->words['memberlookup_desc']}</h3>
		<p class='desc'>{$this->lang->words['starttypeingmember']}</p>
		<form action='#' method='post' style='margin-top: 10px'>
			<input type='text' name='memberlookup' id='memberlookup' class='input_text' size='50' />
		</form>
	</div>
	<script type='text/javascript'>
		$('memberlookup').defaultize( "{$this->lang->words['start_typing_lup']}" );
	</script>
</div>
<else />
<div class='clearfix '>
	<div class='ipsBox_container ipsPad'>
		<p>{$this->lang->words['intro_blurb']}</p>
	</div>
</div>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: membersList
//===========================================================================
function membersList($type, $members, $pagelinks='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div>{$pagelinks}</div>
{parse template="subTabLoop" group="modcp" params=""}
<div class='clearfix'>
	<div id='member_list' class='clear block_wrap'>
		<div id='member_wrap'>
			<ul class='ipsMemberList'>
				<if test="showmembers:|:is_array( $members ) and count( $members )">
					{parse striping="memberStripe" classes="row1,row2"}
					<foreach loop="members:$members as $member">
						<li id='member_id_{$member['member_id']}' class='ipsPad clearfix member_entry {parse striping="memberStripe"}'>
							<a href='{parse url="showuser={$member['member_id']}" template="showuser" seotitle="{$member['members_seo_name']}" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'><img src='{$member['pp_small_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$member['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_medium' /></a>
							<div class='ipsBox_withphoto'>
								<ul class='ipsList_inline right'>
									<if test="weAreSupmod:|:$this->memberData['g_is_supmod'] == 1 && $member['member_id'] != $this->memberData['member_id']">
										<li><a href='{parse url="app=core&amp;module=modcp&amp;do=editmember&amp;auth_key={$this->member->form_hash}&amp;mid={$member['member_id']}&amp;pf={$member['member_id']}" base="public"}' class='ipsButton_secondary'>{$this->lang->words['edit_member']}</a></li>
									</if>
									<if test="memberwarn:|:$member['show_warn']">
										<a href='{parse url="app=members&amp;module=profile&amp;section=warnings&amp;member={$member['member_id']}" base="public"}' id='warn_link_modcp_{$member['member_id']}' title='{$this->lang->words['warn_view_history']}' class='ipsButton_secondary'>{$this->lang->words['warn_view_history']}</a>
									</if>
									<if test="sendpm:|:$this->memberData['g_use_pm'] AND $this->memberData['members_disable_pm'] == 0 AND IPSLib::moduleIsEnabled( 'messaging', 'members' ) && $member['member_id'] != $this->memberData['member_id']">
										<li class='pm_button' id='pm_xxx_{$member['pp_member_id']}'><a href='{parse url="app=members&amp;module=list&amp;module=messaging&amp;section=send&amp;do=form&amp;fromMemberID={$member['pp_member_id']}" base="public"}' title='{$this->lang->words['pm_member']}' class='ipsButton_secondary'>{parse replacement="send_msg"}</a></li>
									</if>
									<li><a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;mid={$member['member_id']}" base="public"}' title='{$this->lang->words['gbl_find_my_content']}' class='ipsButton_secondary'>{parse replacement="find_topics_link"}</a></li>
									<if test="blog:|:$member['has_blog'] AND IPSLib::appIsInstalled( 'blog' )">
										<li><a href='{parse url="app=blog&amp;module=display&amp;section=blog&amp;mid={$member['member_id']}" base="public"}' title='{$this->lang->words['view_blog']}' class='ipsButton_secondary'>{parse replacement="blog_link"}</a></li>
									</if>
									<if test="gallery:|:$member['has_gallery'] AND IPSLib::appIsInstalled( 'gallery' )">
										<li><a href='{parse url="app=gallery&amp;user={$member['member_id']}" seotitle="{$member['members_seo_name']}" template="useralbum" base="public"}' title='{$this->lang->words['view_gallery']}' class='ipsButton_secondary'>{parse replacement="gallery_link"}</a></li>
									</if>
								</ul>
								
								<h3 class='ipsType_subtitle'>
									<strong>{parse template="userHoverCard" group="global" params="$member"}</strong>
								</h3>
								<if test="repson:|:$this->settings['reputation_enabled'] && $this->settings['reputation_show_profile']">
									<if test="norep:|:$member['pp_reputation_points'] == 0 || !$member['pp_reputation_points']">
										<p class='reputation zero ipsType_small left'>
									</if>
									<if test="posrep:|:$member['pp_reputation_points'] > 0">
										<p class='reputation positive ipsType_small left'>
									</if>
									<if test="negrep:|:$member['pp_reputation_points'] < 0">
										<p class='reputation negative ipsType_small left'>
									</if>							
											<span class='number'>{$member['pp_reputation_points']}</span>
										</p>
								</if>
								<span class='desc'>
									{$this->lang->words['member_joined']} {parse date="$member['joined']" format="joined"}<br />
									{IPSMember::makeNameFormatted( $member['group'], $member['member_group_id'] )} &middot; <strong>{$this->lang->words['modqueued_til']}: <if test="isnotbanned:|:in_array( $type, array( 'modposts', 'suspended', 'restrictposts' ) )"><a href='{parse url="app=members&amp;module=profile&amp;section=warnings&amp;member={$member['member_id']}&amp;_tab=warn&amp;type=minus" base="public"}'></if><em>{$member['_language']}</em><if test="isnotbanned2:|:in_array( $type, array( 'modposts', 'suspended', 'restrictposts' ) )"></a></if></strong>
								</span>
							</div>
						</li>
					</foreach>
				<else />
					<p class='no_messages'>{$this->lang->words['no_results']}</p>
				</if>
			</ul>
		</div>
	</div>
</div>
	<div>{$pagelinks}</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: membersModIPForm
//===========================================================================
function membersModIPForm($ip="", $inlineMsg='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h2 class='maintitle'>{$this->lang->words['menu_ipsearch']}</h2>
<div class='ipsBox clearfix'>
	<form id="userCPForm" action="{parse url="app=core&amp;module=modcp&amp;tab=iplookup&amp;fromapp=members&amp;_do=submit" base="public"}" method="post">
	<fieldset class="ipsBox_container ipsPad">
		<if test="modIpIe:|:$inlineMsg">
			{$inlineMsg}
		</if>
		<br />
		
		<ul class='ipsForm ipsForm_horizontal'>
			<li class='ipsField'>
				<label for='ipsearch' class='ipsField_title'>{$this->lang->words['ip_enter']}</label>
				<p class='ipsField_content'>
					<input type="text" size="40" maxlength="24" class='input_text' name="ip" value="<if test="$ip">{$ip}<else />{$this->request['ip']}</if>" />
					<select name="iptool" class='input_select'>
						<option value="resolve">{$this->lang->words['ip_resolve']}</option>
						<option value="posts">{$this->lang->words['ip_posts']}</option>
						<option value="members">{$this->lang->words['ip_members']}</option>
					</select>&nbsp;
					<input type="submit" class='input_submit' value="{$this->lang->words['ip_submit']}" />
				</p>
			</li>
		</ul>
		<p class='message unspecific'>
			{$this->lang->words['ip_desc_text']}
		</p>
		<p class='desc'>
			{$this->lang->words['ip_warn_text']}
		</p>
	</fieldset>
	</form>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: membersModIPFormMembers
//===========================================================================
function membersModIPFormMembers($pages="",$members) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='topic_controls'>
	{$pages}
</div>
<br />
<table class='ipb_table'>
	<tr class='header'>
		<th style='width: 20%'>{$this->lang->words['ipm_name']}</th>
		<th style='width: 15%'>{$this->lang->words['ipm_ip']}</th>
		<th style='width: 10%' class='short'>{$this->lang->words['ipm_posts']}</th>
		<th style='width: 20%' class='short'>{$this->lang->words['ipm_reg']}</th>
		<th style='width: 15%'>{$this->lang->words['ipm_group']}</th>
		<th style='width: 10%'>&nbsp;</th>
	</tr>
	<if test="modIPMembers:|:is_array( $members ) AND count( $members )">
		{parse striping="members" classes="row1,row2"}
		<foreach loop="ipMembers:$members as $id => $row">
			<tr class='{parse striping="members"}'>
				<td>{parse template="userHoverCard" group="global" params="$row"}</td>
				<td class='altrow'>{$row['ip_address']}</td>
				<td class='short'>{$row['posts']}</td>
				<td class="altrow short">{$row['joined']}</td>
				<td>{$row['groupname']}</td>
				<td class="altrow short">
					<a href="{parse url="app=core&amp;module=modcp&amp;do=editmember&amp;mid={$row['member_id']}&amp;auth_key={$this->member->form_hash}" base="public"}" title="{$this->lang->words['ipm_edit']}" class='ipsButton_secondary ipsType_smaller'>{$this->lang->words['ipm_edit']}</a>
				</td>
			</tr>
		</foreach>
	</if>
</table>
<br />
<div class='topic_controls'>
	{$pages}
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: membersModIPFormPosts
//===========================================================================
function membersModIPFormPosts($count=0, $pageLinks='', $results) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<p class='message'>
	{$this->lang->words['ipp_found']} {$count}
</p>
<br />
<div class='topic_controls'>
	{$pageLinks}
</div>
<br />
<if test="modFindMemberPosts:|:count($results)">
	{parse striping="posts" classes="row1,row2"}
	<foreach loop="ipPosts:$results as $result">
		<div class='post_block ucp no_sidebar {parse striping="posts"}'>
			<h3>{parse template="userHoverCard" group="global" params="$result"} | <a href='{parse url="showtopic={$result['topic_id']}&amp;findpost={$result['pid']}" template="showtopic" seotitle="{$result['title_seo']}" base="public"}' title='{$this->lang->words['modip_viewposts']}'>{parse date="$result['post_date']" format="long"}</a></h3>
			<div class='post_body'>
				<div class='post'>{$result['post']}<br /></div>
			</div>
		</div>
		<hr />
	</foreach>
</if>
<br />
<div class='topic_controls'>
	{$pageLinks}
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: modAnnounceForm
//===========================================================================
function modAnnounceForm($announce,$forum_html,$type,$editor_html,$msg="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form action="{parse url="app=core&amp;module=modcp&amp;tab=announcements&amp;fromapp=forums&amp;_do=save&amp;announce_id={$announce['announce_id']}&amp;type={$type}" base="public"}" method="post" id='postingform'>
<input type="hidden" name="secure_key" value="{$this->member->form_hash}" />
<fieldset class='row1'>
	<h3 class='ipsType_subtitle'><if test="buttonlang:|:$type=='add'">{$this->lang->words['announce_add']}<else />{$this->lang->words['announce_button_edit']}</if></h3>
	<br />
	<if test="announceMessage:|:$msg">
		<p class='message unspecific'>{$msg}</p>
	</if>
	<ul class='ipsForm ipsForm_horizontal'>
		<li class='ipsField clearfix'>
			<label for='announce_title' class='ipsField_title'>{$this->lang->words['announce_form_title']}</label>
			<p class='ipsField_content'>
				<input class='input_text' type="text" size="50" name="announce_title" id='announce_title' value="{$announce['announce_title']}" tabindex="1" />
			</p>
		</li>
		<li class='ipsField clearfix'>
			<label for='announce_start' class='ipsField_title'>{$this->lang->words['announce_form_start']}</label>
			<p class='ipsField_content'>
				<input class='input_text' type="text" size="10" name="announce_start" id='announce_start' value="{$announce['announce_start']}" tabindex="2" /><br />
				<span class="desc lighter">{$this->lang->words['announce_form_date']} {$this->lang->words['announce_form_startdesc']}</span>
			</p>
		</li>
		<li class='ipsField clearfix'>
			<label for='announce_end' class='ipsField_title'>{$this->lang->words['announce_form_end']}</label>
			<p class='ipsField_content'>
				<input class='input_text' type="text" size="10" name="announce_end" id='announce_end' value="{$announce['announce_end']}" tabindex="2" /><br />
				<span class="desc lighter">{$this->lang->words['announce_form_date']} {$this->lang->words['announce_form_enddesc']}</span>
			</p>				
		</li>
		<li class='ipsField clearfix'>
			<label for='announce_forum' class='ipsField_title'>{$this->lang->words['announce_form_forums']}</label>
			<p class='ipsField_content'>
				<select class="input_select" multiple="multiple" size="10" id='announce_forum' name="announce_forum[]" tabindex="4">{$forum_html}</select><br />
				<span class="desc lighter">{$this->lang->words['announce_form_forums2']}</span>
			</p>
		</li>
		<li class='ipsField clearfix'>
			<label for='' class='ipsField_title'>{$this->lang->words['announce_form_announce']}</label>
			<div class='ipsField_content'>
				{$editor_html}
			</div>
		</li>
		<li class='ipsField ipsField_checkbox'>
			<input type="checkbox" class="checkbox" name="announce_active" id='announce_active' value="1" {$announce['announce_active_checked']} />
			<p class='ipsField_content'>
				<label for='announce_active'>{$this->lang->words['announce_form_enable']}</label>
			</p>
		</li>
		<li class='ipsField ipsField_checkbox'>
			<input type="checkbox" class="checkbox" name="announce_html_enabled" id='announce_html_enabled' value="1" {$announce['html_checkbox']} />
			<p class='ipsField_content'>
				<label for='announce_html_enabled'>{$this->lang->words['announce_use_html']}</label>
			</p>
		</li>
	</ul>
</fieldset>
<br />
<fieldset class='submit'>
	<input type="submit" value="<if test="buttonlang:|:$type=='add'">{$this->lang->words['announce_add']}<else />{$this->lang->words['announce_button_edit']}</if>" class="input_submit" tabindex="7" /> {$this->lang->words['or']} <a href='{parse url="app=core&amp;module=modcp&amp;tab=announcements&amp;fromapp=forums" base="public"}' title='{$this->lang->words['cancel_edit']}' class='cancel'>{$this->lang->words['cancel']}</a>
</fieldset>
</form>
<script type="text/javascript">
document.observe("dom:loaded", function() {
	if ( $('announce_html_enabled') )
	{
		ipb.textEditor.bindHtmlCheckbox( $('announce_html_enabled') );
	}
} );
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: modAnnouncements
//===========================================================================
function modAnnouncements($announcements) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="ucp"}
<form action="{parse url="app=core&amp;module=modcp&amp;tab=announcements&amp;fromapp=forums&amp;_do=save" base="public"}" id="mutliact" method="post">
<div class='topic_buttons'>
	<ul class='topic_controls'>
		<li>
			<a href="{parse url="app=core&amp;module=modcp&amp;tab=announcements&amp;fromapp=forums&amp;_do=add" base="public"}" class='ipsButton'>{$this->lang->words['announce_add']}</a>
		</li>
	</ul>
</div>
	<h3 class='maintitle clear'>{$this->lang->words['announce_current']}</h3>
	<table class='ipb_table' summary="{$this->lang->words['ucp_announcements']}">
		<tr class='header'>
			<th scope='col' style="width: 40%">{$this->lang->words['announce_title']}</th>
			<th scope='col' class='short' style="width: 10%">{$this->lang->words['announce_form_starts']}</th>
			<th scope='col' class='short' style="width: 10%">{$this->lang->words['announce_form_end']}</th>
			<th scope='col' style="width: 25%">{$this->lang->words['announce_forums']}</th>
			<th scope='col' style="width: 15%">&nbsp;</th>
		</tr>
		<if test="hasAnnouncements:|:is_array( $announcements ) AND count( $announcements )">
			{parse striping="announcements" classes="row1,row2"}
			<foreach loop="announcements:$announcements as $announce">
				<tr class="{parse striping="announcements"} <if test="notactive:|:!$announce['announce_active']">moderated</if>">
					<td>
						<strong><a href="{parse url="showannouncement={$announce['announce_id']}&amp;f=0" base="public" template="showannouncement" seotitle="{$announce['announce_seo_title']}"}">{$announce['announce_title']}</a></strong> <if test="notactive:|:!$announce['announce_active']"><span class='desc'>{$this->lang->words['announce_page_disabled']}</span></if><br /><span class="desc">{$this->lang->words['by_ucfirst']} {parse template="userHoverCard" group="global" params="$announce"}</span>
					</td>
					<td class="short">
						{parse date="$announce['announce_start']" format="DATE" relative="false"}
					</td>
					<td class="short">
						{parse date="$announce['announce_end']" format="DATE" relative="false"}
					</td>
					<td>
						<div class="forumdesc">
							<if test="announceForum:|:$announce['announce_forum'] == '*'">
								{$this->lang->words['announce_page_allforums']}
							<else />
								<if test="announceHasForums:|:is_array( $announce['_forums'] ) and count( $announce['_forums'] )">
									<ul>
									<foreach loop="announce_forums:$announce['_forums'] as $forum">
										<li><a href="{parse url="showforum={$forum[0]}" base="public"}">{$forum[1]}</a></li>
									</foreach>
									</ul>
								<else />
									{$announce['announce_forum_show']}
								</if>
							</if>
						</div>
					</td>
					<td class="short">
						<ul class='ipsList_inline ipsList_nowrap'>
							<li>
								<a href="{parse url="app=core&amp;module=modcp&amp;tab=announcements&amp;fromapp=forums&amp;_do=edit&amp;announce_id={$announce['announce_id']}" base="public"}" class='ipsButton_secondary'>{$this->lang->words['announce_edit']}</a>
							</li>
							<li>
								<a href="{parse url="app=core&amp;module=modcp&amp;tab=announcements&amp;fromapp=forums&amp;_do=delete&amp;announce_id={$announce['announce_id']}&amp;secure_key={$this->member->form_hash}" base="public"}" id="del_{$announce['announce_id']}" class='ipsButton_secondary'>{$this->lang->words['announce_delete']}</a>
							</li>
						</ul>
						<script type='text/javascript'>
							$('del_{$announce['announce_id']}').observe('click', ipb.ucp.deleteAnnouncement.bindAsEventListener( this, {$announce['announce_id']} ) );
						</script>
					</td>
				</tr>
			</foreach>
		<else />
			<tr>
				<td class='no_messages' colspan="5">{$this->lang->words['announce_none']}</td>
			</tr>
		</if>
	</table>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: modcpMessage
//===========================================================================
function modcpMessage($message='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='clearfix '>
	<div class='ipsBox_container ipsPad'>
		<p><if test="hasMessage:|:$message">{$message}<else />{$this->lang->words['noperm_for_modcp_act']}</if></p>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: modCPpost
//===========================================================================
function modCPpost($post, $displayData, $topic, $type) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<!--post:{$post['post']['pid']}-->
<script type="text/javascript">
var pid = parseInt({$post['post']['pid']});
if ( pid > ipb.topic.topPid ){
	ipb.topic.topPid = pid;
}
</script>
<div class='post_block hentry clear no_sidebar <if test="reputation:|:$this->settings['reputation_enabled']">with_rep</if>' id='post_id_{$post['post']['pid']}'>
	<a id='entry{$post['post']['pid']}'></a>
	<if test="repIgnored:|:!empty( $post['post']['_repignored'] ) AND $post['post']['_repignored'] == 1 && $post['post']['_ignored'] != 1">
		<div class='post_ignore'>
			<if test="noRep:|:$post['post']['rep_points'] == 0">
				<span class='reputation zero' title='{$this->lang->words['top_rep']}'>
			</if>
			<if test="posRep:|:$post['post']['rep_points'] > 0">
				<span class='reputation positive' title='{$this->lang->words['top_rep']}'>
			</if>
			<if test="negRep:|:$post['post']['rep_points'] < 0">
				<span class='reputation negative' title='{$this->lang->words['top_rep']}' >
			</if>
					{$post['post']['rep_points']}</span> {$this->lang->words['top_this_post_by']} <a href='{parse url="showuser={$post['author']['member_id']}" base="public"}'>{$post['author']['members_display_name']}</a> {$this->lang->words['top_below_thresh']}. <a href='#' title='{$this->lang->words['ignore_view_post']}' id='unhide_post_{$post['post']['pid']}'>{$this->lang->words['rep_view_anyway']}</a>
		</div>
	</if>
	
	<div class='post_wrap'>
		<h3 class='<if test="postMid:|:empty($post['author']['member_id'])">guest </if>row2'>
			<img src='{$post['author']['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_tiny' />&nbsp;
			<if test="postMember:|:$post['author']['member_id']">
				<span class="author vcard">{parse template="userHoverCard" group="global" params="$post['author']"}</span>
			<else />
				{parse template="userHoverCard" group="global" params="$post['author']"}
			</if>
			
			<if test="postIp:|:$post['post']['_show_ip']">
				<span class='ip right ipsType_small'>({$this->lang->words['ip']}:
				<if test="postAdmin:|:$post['author']['g_access_cp']">
					<em>{$this->lang->words['ip_private']}</em>
				<else />
					<a href="{parse url="app=core&amp;module=modcp&amp;tab=iplookup&amp;fromapp=members&amp;_do=submit&amp;ip={$post['post']['ip_address']}" base="public"}" title='{$this->lang->words['info_about_this_ip']}'>{$post['post']['ip_address']}</a>
				</if>)
				</span>
			</if>
		</h3>
		
		<div class='post_body'>
			<if test="isDeleted:|:$type == 'deleted'">
				<ul class='ipsList_inline modcp_post_controls right'>
					<if test="postIsHardDeleted:|:!$post['post']['_isDeleted']">
						<li>
							<img data-tooltip="{$this->lang->words['hard_delete_warn_post']}" src='{$this->settings['img_url']}/icon_timewarning.png' />
						</li>
					</if>
					<if test="canSoftDelete:|:$this->registry->getClass('class_forums')->canSoftDeletePosts( $post['post']['forum_id'], $post )">
						<li>
							<a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=p_hrestore&amp;f={$post['post']['forum_id']}&amp;t={$post['post']['topic_id']}&amp;pid[]={$post['post']['pid']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;return=modcp:deleted" base="public"}' class='ipsButton_secondary ipsType_smaller' title='{$this->lang->words['restore_post_desc']}'>{$this->lang->words['restore_post']}</a>
						</li>
					</if>
					<if test="canHardDelete:|:$this->registry->getClass('class_forums')->canHardDeletePosts( $post['post']['forum_id'], $post )">
						<li>
							<a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=p_hdelete&amp;f={$post['post']['forum_id']}&amp;t={$post['post']['topic_id']}&amp;pid[]={$post['post']['pid']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;return=modcp:deleted" base="public"}' class='ipsButton_secondary ipsType_smaller important' title='{$this->lang->words['perm_delete_post']}'>{$this->lang->words['perm_delete_post']}</a>
						</li>
					</if>
				</ul>
				<ul class='ipsList_inline<if test="hasDeletedReasonRow:|:empty($displayData['sdData'][ $post['post']['pid'] ])"> modcp_post_controls</if>'>
					<li class='desc'>
						<strong>{$this->lang->words['posted']}</strong> <span class='desc lighter'>{parse date="$post['post']['post_date']" format="short"}</span>
					</li>
					<li class='desc'>
						<strong>{$this->lang->words['deleted_post_date']}</strong> <span class='desc lighter'>{parse date="$topic['post']['pdelete_time']" format="short"}</span>
					</li>
					<li class='desc'>
						<strong>{$this->lang->words['delete_from_topic']}</strong> <a class='desc lighter' href='{parse url="showtopic={$topic['post']['tid']}" seotitle="{$topic['post']['title_seo']}" template="showtopic" base="public"}' title='{$this->lang->words['go_to_topic']}'>{IPSText::truncate( $topic['post']['title'], 25 )}</a>
					</li>
				</ul>
				<if test="postDeletedReason:|:! empty($displayData['sdData'][ $post['post']['pid'] ])">
					<ul class='ipsList_inline modcp_post_controls'>
						<li class='desc'>
							<strong>{$this->lang->words['dlt_topic_deletedby']}</strong> <span class='desc lighter blend_links'>{parse template="userHoverCard" group="global" params="$displayData['sdData'][ $post['post']['pid'] ]"}</span>
						</li>
						<if test="showReason:|:$post['post']['_softDeleteReason']">
							<li class='desc'>
								<strong>{$this->lang->words['dlt_topic_reason']}</strong> <span class='desc lighter'><em><if test="hasDeletedReason:|:$displayData['sdData'][ $post['post']['pid'] ]['sdl_obj_reason']">{$displayData['sdData'][ $post['post']['pid'] ]['sdl_obj_reason']}<else />{$this->lang->words['tdb__noreasongi']}</if></em></span>
							</li>
						</if>
					</ul>
				</if>
			</if>
			<if test="isUnapproved:|:$type == 'unapproved'">
				<ul class='ipsList_inline modcp_post_controls'>
					<if test="canEdit:|:$post['post']['_can_edit'] === TRUE">
						<li class='post_edit'><a href='{parse url="app=forums&amp;module=post&amp;section=post&amp;do=edit_post&amp;f={$post['post']['forum_id']}&amp;t={$post['post']['topic_id']}&amp;p={$post['post']['pid']}&amp;st={$this->request['st']}&amp;return=modcp:unapproved" base="public"}' title='{$this->lang->words['post_edit_title']}' class='ipsButton_secondary ipsType_smaller' id='edit_post_{$post['post']['pid']}'>{$this->lang->words['post_edit']}</a></li>
					</if>
					<!-- Matt: Approve / unapprove post button -->
					<if test="approvePost:|:$this->memberData['is_mod']">
						<li id='toggle_post_{$post['post']['pid']}'>
							<a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=postchoice&amp;tact=approve&amp;selectedpids[{$post['post']['pid']}]={$post['post']['pid']}&amp;t={$post['post']['topic_id']}&amp;f={$post['post']['forum_id']}&amp;auth_key={$this->member->form_hash}&amp;return=modcp:unapproved" base="public"}' title='{$this->lang->words['post_toggle_visible']}' class='ipsButton_secondary ipsType_smaller'><span id='toggletext_post_{$post['post']['pid']}'><if test="approveUnapprove:|:$post['post']['queued']==1">{$this->lang->words['post_approve']}<else />{$this->lang->words['post_unapprove']}</if></span></a>
						</li>
					</if>
					<if test="canDelete:|:$post['post']['_can_delete'] === TRUE OR $post['post']['_softDelete']">
						<li class='post_del' id='del_post_{$post['post']['pid']}'>
							<a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=04&amp;f={$post['post']['forum_id']}&amp;t={$post['post']['topic_id']}&amp;p={$post['post']['pid']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;return=modcp:unapproved" base="public"}' title='{$this->lang->words['post_delete_title']}' class='delete_post ipsButton_secondary important ipsType_smaller'>{$this->lang->words['post_delete']}</a>
						</li>
					</if>
					<if test="reportedPostData:|:$this->memberData['_cache']['report_temp']['post_marker']['post'][ $post['post']['pid'] ]['gfx'] > 0">
						<img src="{$this->settings['img_url']}/reports/post_alert_{$this->memberData['_cache']['report_temp']['post_marker']['post'][ $post['post']['pid']]['gfx']}.png" alt="" class='ipbmenu clickable' id='post-report-{$post['post']['pid']}' />
					</if>
					<li class='desc'>
						<strong>{$this->lang->words['posted']}</strong> <span class='desc lighter'>{parse date="$post['post']['post_date']" format="short"}</span>
					</li>
					<li class='desc'>
						<strong>{$this->lang->words['in_topic']}</strong> <a class='desc lighter' href='{parse url="showtopic={$topic['post']['tid']}" seotitle="{$topic['post']['title_seo']}" template="showtopic" base="public"}'>{IPSText::truncate( $topic['post']['title'], 35 )}</a>
					</li>
				</ul>
			</if>
			<div class='post entry-content <if test="$post['post']['_repignored'] == 1">imgsize_ignore</if>'>
				{$post['post']['post']}
				{$post['post']['attachmentHtml']}
				<br />
				<if test="postEditBy:|:$post['post']['edit_by']">
					<p class='edit'>
						{$post['post']['edit_by']}
						<if test="postEditByReason:|:$post['post']['post_edit_reason'] != ''">
							<br />
							<span class='reason'>{$this->lang->words['reason_for_edit']} {$post['post']['post_edit_reason']}</span>
						</if>
					</p>
				</if>
			</div>
			<br />
		</div>
		
		<script type='text/javascript'>
			if( $('toggle_post_{$post['post']['pid']}') ){
				$('toggle_post_{$post['post']['pid']}').show();
			}
			
			// Add perm data
			ipb.topic.deletePerms[{$post['post']['pid']}] = { 'canDelete' : {parse expression="intval($post['post']['_can_delete'])"}, 'canSoftDelete' : {parse expression="intval($post['post']['_softDelete'])"} };
		</script>
		<if test="reportedPostData:|:$this->memberData['_cache']['report_temp']['post_marker']['post'][ $post['post']['pid'] ]['gfx'] > 0">
			<ul id='post-report-{$post['post']['pid']}_menucontent' class='ipbmenu_content report_menu' style='display: none'>
				<li><a href="{parse url="app=core&amp;module=reports&amp;do=show_report&amp;rid={$this->memberData['_cache']['report_temp']['post_marker']['post'][ $post['post']['pid'] ]['info']['id']}" base="public"}" id='report_mark_{$post['post']['pid']}'><img src='{$this->settings['img_url']}/reports/mark_complete.png' alt='' /> {$this->lang->words['report_menu_mark_complete']}</a></li>
				<li><a href="{$this->settings['base_url']}app=core&amp;module=reports&amp;section=reports&amp;do=show_report&amp;rid={$this->memberData['_cache']['report_temp']['post_marker']['post'][$post['post']['pid']]['info']['id']}"><img src='{$this->settings['img_url']}/reports/view_report.png' alt='' /> {$this->lang->words['report_menu_view_report']}</a></li>
			</ul>
			<script type="text/javascript">
				$('report_mark_{$post['post']['pid']}').observe('click',
				 	ipb.global.updateReportStatus.bindAsEventListener( 	
						this, {$this->memberData['_cache']['report_temp']['post_marker']['post'][$post['post']['pid']]['info']['id']},2,2
					)
				);
			</script>
		</if>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: modCPtopic
//===========================================================================
function modCPtopic($topics, $pagelinks, $type, $sdelete=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<foreach loop="topics:$topics as $tid => $data">
	<tr class='__topic' id='trow_{$data['tid']}' data-tid="{$data['tid']}">
		<td class='__tid{$data['tid']} with_mod_links'>	
			<if test="isLink:|:$data['state'] == 'link'">
				{$this->lang->words['moved_topic_link']}
				<em>
			</if>
			<a id="tid-link-{$data['tid']}" href="{parse url="showtopic={$data['tid']}" base="public" template="showtopic" seotitle="{$data['title_seo']}"}" title='{$data['title']} {$this->lang->words['topic_started_on']} {parse date="$data['start_date']" format="LONG"}' class='topic_title'>{$data['title']}</a>
			<if test="isLinkEnd:|:$data['state'] == 'link'">
				</em>
			</if>
			<if test="multipages:|:isset( $data['pages'] ) AND is_array( $data['pages'] ) AND count( $data['pages'] )">
				<ul class='mini_pagination'>
				<foreach loop="pages:$data['pages'] as $page">
						<if test="haslastpage:|:$page['last']">
							<li><a href="{parse url="showtopic={$data['tid']}&amp;st={$page['st']}" seotitle="{$data['title_seo']}" template="showtopic" base="public"}" title='{$this->lang->words['topic_goto_page']} {$page['page']}'>{$page['page']} {$this->lang->words['_rarr']}</a></li>
						<else />
							<li><a href="{parse url="showtopic={$data['tid']}&amp;st={$page['st']}" seotitle="{$data['title_seo']}" template="showtopic" base="public"}" title='{$this->lang->words['topic_goto_page']} {$page['page']}'>{$page['page']}</a></li>
						</if>
				</foreach>
				</ul>
			</if>
			<br />
			<if test="isntLink:|:$data['state'] != 'link'">
				<ul class='ipsList_inline'>
					<li class='desc lighter'>
						<strong>{$this->lang->words['dlt_topic_startedby']}</strong> <span class='desc lighter blend_links'><if test="hasStarterId:|:$data['starter_id']">{parse template="userHoverCard" group="global" params="$data"}<else />{$data['starter']}</if></span>
					</li>
					<li class='desc lighter'>
						<strong>{$this->lang->words['dlt_topic_posted']}</strong> <span class='desc lighter'>{parse date="$data['start_date']" format="short"}</span>
					</li>
					<li class='desc lighter'>
						<strong>{$this->lang->words['dlt_topic_inforum']}</strong> <span class='desc lighter blend_links'><a href='{parse url="showforum={$data['forum']['id']}" template="showforum" seotitle="{$data['forum']['name_seo']}" base="public"}'>{$data['forum']['name']}</a></span>
					</li>
				</ul>
			<else />
				<ul class='ipsList_inline'>
					<li class='desc lighter'>
						<strong>{$this->lang->words['dlt_topic_link_from']}</strong>
						<span class='desc lighter'><a href='{parse url="showforum={$data['_toForum']['id']}" template="showforum" seotitle="{$data['_toForum']['name_seo']}" base="public"}'>{$data['_toForum']['name']}</a></span>
					</li>
					<li class='desc lighter'>
						<strong>{$this->lang->words['dlt_topic_link_to']}</strong>
						<span class='desc lighter'><a href='{parse url="showforum={$data['forum']['id']}" template="showforum" seotitle="{$data['forum']['name_seo']}" base="public"}'>{$data['forum']['name']}</a></span>
					</li>
				</ul>
			</if>
			<if test="topicDeletedReason:|:$data['_isDeleted']">
				<ul class='ipsList_inline'>
					<li class='desc lighter'>
						<strong>{$this->lang->words['dlt_topic_deletedby']}</strong> <span class='desc lighter blend_links'>{parse template="userHoverCard" group="global" params="$sdelete[ $data['tid'] ]"}</span>
					</li>
					<if test="showReason:|:$data['permissions']['SoftDeleteReason']">
						<li class='desc lighter'>
							<strong>{$this->lang->words['dlt_topic_reason']}</strong> <span class='desc lighter'><em><if test="hasDeletedReason:|:$sdelete[ $data['tid'] ]['sdl_obj_reason']">{$sdelete[ $data['tid'] ]['sdl_obj_reason']}<else />{$this->lang->words['tdb__noreasongi']}</if></em></span>
						</li>
					</if>
				</span>
			</if>
		</td>
		<td>
			<if test="isntLink3:|:$data['state'] == 'link'">
				<span class='desc'>{parse format_number="$data['posts']"} <if test="replylang:|:intval($data['posts']) == 1">{$this->lang->words['reply']}<else />{$this->lang->words['replies']}</if></span>
			</if>
		</td>
		<td>
			<ul class='ipsList_inline right'>
				<if test="$type == 'deleted'">
					<if test="isntLink4:|:$data['state'] == 'link'">
						<if test="tidRestoreLink:|:$data['permissions']['TopicSoftDeleteRestore']">
							<li><a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=topic_restore&amp;t={$data['real_tid']}&amp;f={$data['_toForum']['id']}&amp;auth_key={$this->member->form_hash}&amp;return=modcp:deleted" base="public"}' title='{$this->lang->words['restore_topic_desc']}' class='ipsButton_secondary ipsType_smaller'>{$this->lang->words['restore_topic']}</a></li>
						</if>
						<if test="canHardDeleteLinkFromDb:|:$this->registry->getClass('class_forums')->canHardDeleteTopics( $data['forum_id'], $data )">
							<li><a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=09&amp;t={$data['real_tid']}&amp;f={$data['_toForum']['id']}&amp;auth_key={$this->member->form_hash}&amp;return=modcp:deleted" base="public"}' title='{$this->lang->words['dlt_delete_topic']}' class='ipsButton_secondary ipsType_smaller important'>{$this->lang->words['perm_delete_topic']}</a></li>
						</if>
					<else />
						<if test="topicIsHardDeleted:|:!$data['_isDeleted']">
							<li>
								<img data-tooltip="{$this->lang->words['hard_delete_warn_topic']}" src='{$this->settings['img_url']}/icon_timewarning.png' />
							</li>
						</if>
						<if test="tidRestore:|:$data['permissions']['TopicSoftDeleteRestore']">
							<li><a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=topic_restore&amp;t={$data['real_tid']}&amp;f={$data['forum_id']}&amp;auth_key={$this->member->form_hash}&amp;return=modcp:deleted" base="public"}' title='{$this->lang->words['restore_topic_desc']}' class='ipsButton_secondary ipsType_smaller'>{$this->lang->words['restore_topic']}</a></li>
						</if>
						<if test="canHardDeleteFromDb:|:$this->registry->getClass('class_forums')->canHardDeleteTopics( $data['forum_id'], $data )">
							<li><a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=09&amp;t={$data['real_tid']}&amp;f={$data['forum_id']}&amp;auth_key={$this->member->form_hash}&amp;return=modcp:deleted" base="public"}' title='{$this->lang->words['dlt_delete_topic']}' class='ipsButton_secondary ipsType_smaller important'>{$this->lang->words['perm_delete_topic']}</a></li>
						</if>
					</if>
				</if>
				<if test="$type == 'unapproved'">
					<li>
						<a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=topic_approve&amp;t={$data['real_tid']}&amp;f={$data['forum_id']}&amp;auth_key={$this->member->form_hash}&amp;return=modcp:unapproved" base="public"}' class='ipsButton_secondary ipsType_smaller'>{$this->lang->words['modcp_approvelink']}</a>
					</li>
					<if test="canSoftDelete:|:$data['permissions']['TopicSoftDelete']">
						<li>
							<a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=03&amp;t={$data['real_tid']}&amp;f={$data['forum_id']}&amp;auth_key={$this->member->form_hash}&amp;return=modcp:unapproved" base="public"}' class='ipsButton_secondary ipsType_smaller'>{$this->lang->words['HIDE_TOPIC']}</a>
						</li>
					</if>
					<if test="canHardDelete:|:$this->registry->getClass('class_forums')->canHardDeleteTopics( $data['forum_id'], $data )">
						<li>
							<a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=08&amp;t={$data['real_tid']}&amp;f={$data['forum_id']}&amp;auth_key={$this->member->form_hash}&amp;return=modcp:unapproved" base="public"}' class='ipsButton_secondary ipsType_smaller important'>{$this->lang->words['modcp_deletelink']}</a>
						</li>
					</if>
				</if>
			</ul>
		</td>
	</tr>
</foreach>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: portalPage
//===========================================================================
function portalPage($output, $tabs=array(), $_activeNav=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{$this->lang->words['modcp_page_title']}</h1>
<br />
<div class='ipsBox'>
	<div class='ipsVerticalTabbed ipsLayout ipsLayout_withleft ipsLayout_smallleft clearfix'>
		<div class='ipsVerticalTabbed_tabs ipsLayout_left' id='modcp_tabs'>
			<ul class='clear'>
				<li id='index' class='<if test="$_activeNav['primary'] == 'index' && $_activeNav['secondary'] == 'index'">active </if>tab_toggle'>
					<a href='{parse url="app=core&amp;module=modcp" base="public"}'>{$this->lang->words['modcp_tab_index']}</a>
				</li>
				<if test="accessRC:|:IPSMember::isInGroup( $this->memberData, explode( ',', IPSText::cleanPermString( $this->settings['report_mod_group_access'] ) ) )">
					<li id='reported_content' class='<if test="$_activeNav['primary'] == 'reported_content' && $_activeNav['secondary'] == 'reports'">active </if>tab_toggle'>
						<a href='{parse url="app=core&amp;module=reports&amp;do=index" base="public"}'>{$this->lang->words['modcp_tab_reports']}</a>
					</li>
				</if>
				<foreach loop="tabs:$tabs as $mainTabKey => $subTabs">
					<li id='{$mainTabKey}' class='<if test="$_activeNav['primary'] == $mainTabKey">active </if>tab_toggle' data-tabid='{$mainTabKey}'>
						<a href='{parse url="app=core&amp;module=modcp&amp;fromapp={$subTabs[0][1]}&amp;tab={$subTabs[0][0]}" base="public"}'>{$this->lang->words['modcp_mtab_' . $mainTabKey ]}</a>
					</li>
				</foreach>
			</ul>
		</div>
		<div class='ipsVerticalTabbed_content ipsLayout_content ipsBox_container' id='modcp_content'>
			<div class='ipsPad'>
				{$output}
			</div>
		</div>
	</div>
</div>
<script type='text/javascript'>
	$("modcp_content").setStyle( { minHeight: $('modcp_tabs').measure('margin-box-height') + 5 + "px" } );
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: subTabLoop
//===========================================================================
function subTabLoop() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<foreach loop="findTab:$this->templateVars['tabs'] as $mainTabKey => $subTabs">
	<if test="isMainTab:|:$mainTabKey == $this->templateVars['activeNav']['primary']">
		<if test="count( $subTabs ) > 1">
			<div class='maintitle ipsFilterbar clear'>
				<ul class='ipsList_inline'>									
					<foreach loop="subTabs:$subTabs as $sub">
						<li<if test="$this->templateVars['activeNav']['secondary'] == $sub[0]"> class='active'</if>>
							<a href='{parse url="app=core&amp;module=modcp&amp;fromapp={$sub[1]}&amp;tab={$sub[0]}" base="public"}'>
							{$this->lang->words['modcp_tab_' . $sub[0] ]}</a>
						</li>
					</foreach>
				</ul>
			</div>
		</if>
	</if>
</foreach>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: unapprovedPosts
//===========================================================================
function unapprovedPosts($posts, $pagelinks) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="topic"}
<if test="disablelightbox:|:!$this->settings['disable_lightbox']">
{parse template="include_lightbox" group="global" params=""}
</if>
<script type='text/javascript'>
	ipb.topic.inSection	= 'topicview';
</script>
<style type='text/css'>
	.post_mod { display: none; }
</style>
<div>{$pagelinks}</div>
{parse template="subTabLoop" group="modcp" params=""}
<div class='clearfix'>
	<if test="hasPosts:|:is_array( $posts ) AND count( $posts )">
		<div id='ips_Posts2'>
			<foreach loop="post_data:$posts as $post">
				{parse template="modCPpost" group="modcp" params="$post, array(), $post, 'unapproved'"}
			</foreach>
		</div>
	<else />
		<div class='no_messages'>
			{$this->lang->words['no_unapproved_posts']}
		</div>
	</if>
	<div>{$pagelinks}</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: unapprovedTopics
//===========================================================================
function unapprovedTopics($topics, $pagelinks) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="forums"}
<div>{$pagelinks}</div>
{parse template="subTabLoop" group="modcp" params=""}
<div class='clearfix'>
	<table class='ipb_table topic_list' id='forum_table'>
		<if test="hastopics:|:is_array( $topics ) AND count( $topics )">
			{parse template="modCPtopic" group="modcp" params="$topics, $pagelinks, 'unapproved'"}
		<else />
			<tr> 
				<td colspan='3' class='no_messages'>{$this->lang->words['no_unapproved_topics']}</td>
			</tr>
		</if>
	</table>
	<div>{$pagelinks}</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>