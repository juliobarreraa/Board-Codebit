<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: showOnlineList
//===========================================================================
function showOnlineList($rows, $links="", $defaults=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='topic_controls'>
	{$links}
</div>
<h2 class='maintitle'>{$this->lang->words['online_page_title']}</h2>
<div class='ipsBox'>
	<div class='ipsBox_container'>
		<table class='ipb_table ipsMemberList' summary="{$this->lang->words['users_online']}">
			<tr class='header'>
				<th scope='col' width='55'>&nbsp;</th>
				<th scope='col'>{$this->lang->words['member_name']}</th>
				<th scope='col'>{$this->lang->words['where']}</th>
				<th scope='col'>{$this->lang->words['time']}</th>
				<th scope='col'>&nbsp;</th>
			</tr>
			<if test="onlineusers:|:count($rows)">
				{parse striping="online" classes="row1,row2"}
				<foreach loop="online:$rows as $session">
					<tr class='{parse striping="online"}'>
						<td>{parse template="userSmallPhoto" group="global" params="array_merge( $session['_memberData'], array( 'alt' => sprintf($this->lang->words['users_photo'], $session['_memberData']['members_display_name'] ? $session['_memberData']['members_display_name'] : $this->lang->words['global_guestname']) ) )"}</td>
						<td>
							<if test="userid:|:$session['_memberData']['member_id']">
								{parse template="userHoverCard" group="global" params="array_merge( $session['_memberData'], array( 'members_display_name' => IPSMember::makeNameFormatted( $session['_memberData']['members_display_name'], $session['_memberData']['member_group_id'] ) ) )"}
							<else />
								<if test="username:|:$session['member_name']">
									{IPSMember::makeNameFormatted( $session['member_name'], $session['member_group'] )}
								<else />
									{$this->lang->words['global_guestname']}
								</if>
							</if>
							<if test="anonymous:|:$session['login_type'] == 1">
								<if test="viewanon:|:$this->memberData['g_access_cp']">*</if>
							</if>
							<if test="showip:|:$this->memberData['g_access_cp']">
								<br />
								<span class='ip desc lighter ipsText_smaller'>({$session['ip_address']})</span>
							</if>
						</td>
						<td>
							<if test="nowhere:|:!$session['where_line'] || $session['in_error']">
								{$this->lang->words['board_index']}
							<else />
								<if test="wheretext:|:$session['where_link'] AND !$session['where_line_more']">
									<if test="wheretextseo:|:$session['_whereLinkSeo']">
										<a href='{$session['_whereLinkSeo']}'>
									<else />
										<a href='{parse url="{$session['where_link']}" base="public"}'>
									</if>
								</if>
								{$session['where_line']} 
								<if test="moredetails:|:$session['where_line_more']">
									&nbsp;
									<if test="wheretextseo:|:$session['_whereLinkSeo']">
										<a href='{$session['_whereLinkSeo']}'>
									<else />
										<if test="detailslink:|:$session['where_link']"><a href='{parse url="{$session['where_link']}" base="public"}'></if>
									</if>
									{$session['where_line_more']}
									<if test="enddetailslink:|:$session['where_link']"></a></if>
								<else />
									<if test="nomoreenddetailslink:|:$session['where_link']"></a></if>
								</if>
							</if>
						</td>
						<td>
							{parse date="$session['running_time']" format="long" relative="false"}
						</td>
						<td>
							<if test="options:|:$session['member_id'] AND $session['member_name']">
								<ul class='ipsList_inline ipsList_nowrap right'>
									<if test="notus:|:$this->memberData['member_id'] AND $this->memberData['member_id'] != $session['member_id'] && $this->settings['friends_enabled'] AND $this->memberData['g_can_add_friends']">
										<if test="addfriend:|:IPSMember::checkFriendStatus( $session['member_id'] )">
											<li class='mini_friend_toggle is_friend' id='friend_online_{$session['member_id']}'><a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=remove&amp;member_id={$session['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['remove_friend']}' class='ipsButton_secondary'>{parse replacement="remove_friend"}</a></li>
										<else />
											<li class='mini_friend_toggle is_not_friend' id='friend_online_{$session['member_id']}'><a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=add&amp;member_id={$session['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['add_friend']}' class='ipsButton_secondary'>{parse replacement="add_friend"}</a></li>								
										</if>
									</if>
									<if test="sendpm:|:$this->memberData['member_id'] AND $this->memberData['member_id'] != $session['member_id'] AND $this->memberData['g_use_pm'] AND $this->memberData['members_disable_pm'] == 0 AND IPSLib::moduleIsEnabled( 'messaging', 'members' )">
										<li class='pm_button' id='pm_online_{$session['member_id']}'><a href='{parse url="app=members&amp;module=messaging&amp;section=send&amp;do=form&amp;fromMemberID={$session['member_id']}" base="public"}' title='{$this->lang->words['pm_member']}' class='ipsButton_secondary'>{parse replacement="send_msg"}</a></li>
									</if>
									<if test="blog:|:$session['memberData']['has_blog'] AND IPSLib::appIsInstalled( 'blog' )">
										<li><a href='{parse url="app=blog&amp;module=display&amp;section=blog&amp;mid={$session['member_id']}" base="public"}' title='{$this->lang->words['view_blog']}' class='ipsButton_secondary'>{parse replacement="blog_link"}</a></li>
									</if>
									<if test="gallery:|:$session['memberData']['has_gallery'] AND IPSLib::appIsInstalled( 'gallery' )">
										<li><a href='{parse url="app=gallery&amp;user={$session['member_id']}" template="useralbum" seotitle="{$session['memberData']['members_seo_name']}" base="public"}' title='{$this->lang->words['view_gallery']}' class='ipsButton_secondary'>{parse replacement="gallery_link"}</a></li>
									</if>
								</ul>
							<else />
								<span class='desc'>{$this->lang->words['no_options_available']}</span>
							</if>
						</td>
					</tr>
				</foreach>
			</if>
		</table>
	</div>
</div>
<div id='forum_filter' class='ipsForm_center ipsPad'>
	<form method="post" action="{parse url="app=members&amp;section=online&amp;module=online" base="public"}">
		<label for='sort_key'>{$this->lang->words['s_by']}</label>
		<select name="sort_key" id='sort_key' class='input_select'>
			<foreach loop="sort_key:array( 'click', 'name' ) as $sort">
				<option value='{$sort}'<if test="defaultsort:|:$defaults['sort_key'] == $sort"> selected='selected'</if>>{$this->lang->words['s_sort_key_' . $sort ]}</option>
			</foreach>
		</select>
		<select name="show_mem" class='input_select'>
			<foreach loop="show_mem:array( 'reg', 'guest', 'all' ) as $filter">
				<option value='{$filter}'<if test="defaultfilter:|:$defaults['show_mem'] == $filter"> selected='selected'</if>>{$this->lang->words['s_show_mem_' . $filter ]}</option>
			</foreach>
		</select>
		<select name="sort_order" class='input_select'>
			<foreach loop="sort_order:array( 'desc', 'asc' ) as $order">
				<option value='{$order}'<if test="defaultorder:|:$defaults['sort_order'] == $order"> selected='selected'</if>>{$this->lang->words['s_sort_order_' . $order ]}</option>
			</foreach>
		</select>
		<input type="submit" value="{$this->lang->words['s_go']}" class="input_submit alt" />
	</form>
</div>
<br />
<div class='topic_controls'>
	{$links}
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>