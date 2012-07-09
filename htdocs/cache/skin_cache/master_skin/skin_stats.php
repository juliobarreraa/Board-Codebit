<?php
/**
 * Master skin file
 * Written: Mon, 09 Jul 2012 18:16:42 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: group_strip
//===========================================================================
function group_strip($group="", $members=array(), $pagination='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="hasPaginationTop:|:$pagination">
	<div class='topic_controls'>{$pagination}</div>
</if>
<h3 class='maintitle'>{$group}</h3>
<table class='ipb_table ipsMemberList'>
	<tr class='header'>
		<th scope='col' style='width: 3%'>&nbsp;</th>
		<th scope='col' style='width: 20%'>{$this->lang->words['leader_name']}</th>
		<th scope='col' style='width: 15%'>{$this->lang->words['leader_group']}</th>
		<th scope='col' style='width: 25%' class='short'>{$this->lang->words['leader_forums']}</th>
		<th scope='col' style='width: 25%'>{$this->lang->words['leader_last_seen']}</th>
		<th scope='col' style='width: 12%'>&nbsp;</th>
	</tr>
	<if test="hasLeaders:|:count($members) AND is_array($members)">
		{parse striping="staff" classes="row1,row2"}
		<foreach loop="members:$members as $info">
			<tr class='{parse striping="staff"}'>
				<td>{parse template="userSmallPhoto" group="global" params="$info"}</td>
				<td>{parse template="userHoverCard" group="global" params="$info"}</td>
				<td>{$info['_group_formatted']}</td>
				<td class='altrow short'>
					<if test="specificForums:|:is_array($info['forums'])">
						<if test="noVisibleForums:|: empty( $info['forums'] )">
							--
						<else />
							<if test="moreThanOne:|: count( $info['forums'] ) == 1">
								<foreach loop="forums:$info['forums'] as $id => $name">
									<a href="{parse url="showforum={$id}" base="public" template="showforum" seotitle="{$this->registry->class_forums->forum_by_id[ $id ]['name_seo']}"}">{$name}</a>
								</foreach>
							<else />
								<a href='#' id='mod_page_{$info['member_id']}'>{parse expression="sprintf($this->lang->words['no_forums'],count($info['forums']))"}</a>
								<ul class='ipbmenu_content' id='mod_page_{$info['member_id']}_menucontent'  style='display:none'>
								<foreach loop="forums:$info['forums'] as $id => $name">
									<li><a href="{parse url="showforum={$id}" base="public" template="showforum" seotitle="{$this->registry->class_forums->forum_by_id[ $id ]['name_seo']}"}">{$name}</a></li>
								</foreach>
								</ul>
								<script type='text/javascript'>
									document.observe("dom:loaded", function()
									{
										new ipb.Menu( $('mod_page_{$info['member_id']}'), $('mod_page_{$info['member_id']}_menucontent') );
									} );
								</script>
							</if>
						</if>
					<else />
						{$info['forums']}
					</if>
				</td>
				<td>
					<span class='ipsText_small desc'>{$info['_last_active']}</span>
					<if test="isonline:|:$info['_online'] && ($info['online_extra'] != $this->lang->words['not_online'])">
						<span data-tooltip="{$info['online_extra']}" class='ipsBadge ipsBadge_green'>{$this->lang->words['m_online']}</span>
					</if>
				</td>
				<td class='short altrow'>
					<ul class='ipsList_inline right ipsList_nowrap'>
						<if test="isFriendable:|:$this->memberData['member_id'] AND $this->memberData['member_id'] != $info['member_id'] && $this->settings['friends_enabled'] AND $this->memberData['g_can_add_friends']">
							<if test="isFriend:|:IPSMember::checkFriendStatus( $info['member_id'] )">
								<li class='mini_friend_toggle is_friend' id='friend_xxx_{$info['member_id']}'><a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=remove&amp;member_id={$info['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['remove_friend']}' class='ipsButton_secondary'>{parse replacement="remove_friend"}</a></li>
							<else />
								<li class='mini_friend_toggle is_not_friend' id='friend_xxx_{$info['member_id']}'><a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=add&amp;member_id={$info['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['add_friend']}' class='ipsButton_secondary'>{parse replacement="add_friend"}</a></li>
							</if>
						</if>
						<if test="canPm:|:$this->memberData['g_use_pm'] AND $this->memberData['member_id'] != $info['member_id'] AND $this->memberData['members_disable_pm'] == 0 AND IPSLib::moduleIsEnabled( 'messaging', 'members' )">
							<li class='pm_button' id='pm_xxx_{$info['member_id']}'><a href='{parse url="app=members&amp;module=messaging&amp;section=send&amp;do=form&amp;fromMemberID={$info['member_id']}" base="public"}' title='{$this->lang->words['pm_member']}' class='ipsButton_secondary'>{parse replacement="send_msg"}</a></li>
						</if>
					</ul>
				</td>
			</tr>
		</foreach>
	</if>
</table>
<if test="hasPaginationBottom:|:$pagination">
	<div class='topic_controls'>{$pagination}</div>
</if>
<br class='clear' />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: top_posters
//===========================================================================
function top_posters($rows) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{$this->lang->words['todays_posters']}</h1>
<br />
<table class='ipb_table ipsMemberList'>
	<tr class='header'>
		<th scope='col' style='width: 3%'>&nbsp;</th>
		<th scope='col'>{$this->lang->words['member']}</th>
		<th scope='col'>{$this->lang->words['member_joined']}</th>
		<th scope='col' class='short'>{$this->lang->words['member_posts']}</th>
		<th scope='col' class='short'>{$this->lang->words['member_today']}</th>
		<th scope='col' class='short'>{$this->lang->words['member_percent']}</th>
		<th scope='col' class='short'>&nbsp;</th>
	</tr>
	<if test="hasTopPosters:|:!is_array($rows) OR !count($rows)">
		<tr>
			<td colspan='7' class='no_messages'>{$this->lang->words['no_info']}</td>
		</tr>
	<else />
		{parse striping="top_posters" classes="row1,row2"}
		<foreach loop="topposters:$rows as $info">
			<tr class='{parse striping="top_posters"}'>
				<td>{parse template="userSmallPhoto" group="global" params="array_merge( $info, array( 'alt' => sprintf($this->lang->words['users_photo'], $info['members_display_name']) ) )"}</td>
				<td>{parse template="userHoverCard" group="global" params="$info"}</td>
				<td class='altrow'>
					{parse date="$info['joined']" format="joined"}
				</td>
				<td class='short'>
					{parse format_number="$info['posts']"}
				</td>
				<td class='altrow short'>
					{parse format_number="$info['tpost']"}
				</td>
				<td class='short'>
					{$info['today_pct']}%
				</td>
				<td class='altrow short'>
					<ul class='ipsList_inline right'>
						<if test="tpIsFrindable:|:$this->memberData['member_id'] AND $this->memberData['member_id'] != $info['member_id'] && $this->settings['friends_enabled'] AND $this->memberData['g_can_add_friends']">
							<if test="tpIsFriend:|:IPSMember::checkFriendStatus( $info['member_id'] )">
								<li class='mini_friend_toggle is_friend' id='friend_xxx_{$info['member_id']}'><a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=remove&amp;member_id={$info['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['remove_friend']}' class='ipsButton_secondary'>{parse replacement="remove_friend"}</a></li>
							<else />
								<li class='mini_friend_toggle is_not_friend' id='friend_xxx_{$info['member_id']}'><a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=add&amp;member_id={$info['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['add_friend']}' class='ipsButton_secondary'>{parse replacement="add_friend"}</a></li>
							</if>
						</if>
						<if test="tpPm:|:$this->memberData['g_use_pm'] AND $this->memberData['member_id'] != $info['member_id'] AND $this->memberData['members_disable_pm'] == 0 AND IPSLib::moduleIsEnabled( 'messaging', 'members' )">
							<li class='pm_button' id='pm_xxx_{$info['member_id']}'><a href='{parse url="app=members&amp;module=messaging&amp;section=send&amp;do=form&amp;fromMemberID={$info['member_id']}" base="public"}' title='{$this->lang->words['pm_member']}' class='ipsButton_secondary'>{parse replacement="send_msg"}</a></li>
						</if>
						<if test="tpBlog:|:$info['has_blog'] AND IPSLib::appIsInstalled( 'blog' )">
							<li><a href='{parse url="app=blog&amp;module=display&amp;section=blog&amp;mid={$info['member_id']}" base="public"}' title='{$this->lang->words['view_blog']}' class='ipsButton_secondary'>{parse replacement="blog_link"}</a></li>
						</if>
						<if test="tpGallery:|:$info['has_gallery'] AND IPSLib::appIsInstalled( 'gallery' )">
							<li><a href='{parse url="app=gallery&amp;user={$info['member_id']}" base="public" seotitle="{$info['members_seo_name']}" template="useralbum"}' title='{$this->lang->words['view_gallery']}' class='ipsButton_secondary'>{parse replacement="gallery_link"}</a></li>
						</if>
					</ul>
				</td>
			</tr>
		</foreach>
	</if>
</table>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: whoPosted
//===========================================================================
function whoPosted($tid=0, $title="", $rows=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="$this->request['module']=='ajax'">
	<h3>{$this->lang->words['who_farted']} {IPSText::truncate( $title, 40 )}</h3>
<else />
	<h3 class='maintitle'>{$this->lang->words['who_farted']} {IPSText::truncate( $title, 30 )}</h3>
</if>
<div class='fixed_inner'>
	<table class='ipb_table'>
		<tr class='header'>
			<th>{$this->lang->words['whoposted_name']}</th>
			<th>{$this->lang->words['whoposted_posts']}</th>
		</tr>
		<if test="hasPosters:|:count($rows) AND is_array($rows)">
			{parse striping="whoposted" classes="row1,row2"}
			<foreach loop="whoposted:$rows as $row">
				<tr class='{parse striping="whoposted"}'>
					<td>{parse template="userHoverCard" group="global" params="$row"}</td>
					<td>{$row['pcount']}</td>
				</tr>
			</foreach>
		</if>
	</table>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>