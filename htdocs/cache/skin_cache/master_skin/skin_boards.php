<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: boardIndexTemplate
//===========================================================================
function boardIndexTemplate($lastvisit="", $stats=array(), $cat_data=array(), $show_side_blocks=true, $side_blocks=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="board"}
{parse variable="sidebar_enabled" default="$show_side_blocks"}
<if test="boardIndexTop:|:true"></if>
<div id='board_index' class='ipsLayout <if test="sideBarEnabledL:|:$this->templateVars['sidebar_enabled']">ipsLayout_withright</if> ipsLayout_largeright clearfix <if test="sidebarclosed:|:IPSCookie::get('hide_sidebar') == '1'">no_sidebar</if>'>	
	<div id='categories' class='ipsLayout_content clearfix'>
	<!-- CATS AND FORUMS -->
		<if test="cats_forums:|:is_array( $cat_data ) AND count( $cat_data )">
			<foreach loop="categories:$cat_data as $_data">
				<if test="cat_has_forums:|:is_array( $_data['forum_data'] ) AND count( $_data['forum_data'] )">
					<div id='category_{$_data['cat_data']['id']}' class='category_block block_wrap'>
						<h3 class='maintitle'>
							<a class='toggle right' href='#' title="{parse expression="sprintf( $this->lang->words['toggle_cat'], $_data['cat_data']['name'] )"}">{parse expression="sprintf( $this->lang->words['toggle_cat'], $_data['cat_data']['name'] )"}</a> <a href="{parse url="showforum={$_data['cat_data']['id']}" seotitle="{$_data['cat_data']['name_seo']}" template="showforum" base="public"}" title='{parse expression="sprintf( $this->lang->words['view_cat'], $_data['cat_data']['name'] )"}'>{$_data['cat_data']['name']}</a>
						</h3>
						<div class='ipsBox table_wrap'>
							<div class='ipsBox_container'>
								<table class='ipb_table' summary="{$this->lang->words['forums_in_cat']} '{$_data['cat_data']['name']}'">
									<tr class='header hide'>
										<th scope='col' class='col_c_icon'>&nbsp;</th>
										<th scope='col' class='col_c_forum'>{$this->lang->words['cat_name']}</th>
										<th scope='col' class='col_c_stats stats'>{$this->lang->words['stats']}</th>
										<if test="canSeeLastInfoHeader:|:$this->memberData['gbw_view_last_info']"><th scope='col' class='col_c_post'>{$this->lang->words['last_post_info']}</th></if>
									</tr>
									<!-- / CAT HEADER -->
									<foreach loop="forums:$_data['forum_data'] as $forum_id => $forum_data">
										<if test="forumRedirect:|:$forum_data['redirect_on']">
											<tr class='redirect_forum' id='f_{$forum_data['id']}'>
												<td class='col_c_icon'>
													<img src='{$this->settings['img_url']}/f_redirect.png' />
												</td>
												<td <if test="canSeeLastInfoRedirect:|:$this->memberData['gbw_view_last_info']">colspan='2'</if> class='col_c_forum'>
													<h4><a href="{parse url="showforum={$forum_data['id']}" seotitle="{$forum_data['name_seo']}" template="showforum" base="public"}" title='{$forum_data['name']}'>{$forum_data['name']}</a></h4>
													<p class='desc'>{$forum_data['description']}</p>
												</td>
												<td class='desc'>
													<ul class='last_post ipsType_small'>
														<li class='desc lighter'><em>{parse format_number="$forum_data['redirect_hits']"} {$this->lang->words['rd_hits']}</em></li>
													</ul>
												</td>
											</tr>
										<else />
											<tr class='<if test="hasUnreadClass:|:$forum_data['_has_unread']">unread</if>'>
												<td class='col_c_icon'>
													<if test="hasUnread:|:$forum_data['_has_unread']">
														<a id='forum_img_{$forum_data['id']}' href="{parse url="app=forums&amp;module=forums&amp;section=markasread&amp;marktype=forum&amp;forumid={$forum_data['id']}&amp;returntoforumid={$this->request['f']}&amp;i=1" base="public"}" data-tooltip="{$this->lang->words['bi_markread']}" class='forum_marker'><img src='{$this->settings['img_url']}/f_icon.png' /></a>
													<else />
														<img src='{$this->settings['img_url']}/f_icon_read.png' />
													</if>
												</td>
												<td class='col_c_forum'>
													
													<h4>
														<if test="hasQueuedAndCanSeeIcon:|:!empty($forum_data['_has_queued_and_can_see_icon'])">
															<a href='{parse url="showforum={$forum_data['id']}&amp;modfilter=unapproved" seotitle="{$forum_data['name_seo']}" template="showforum" base="public"}' title='{$this->lang->words['view_unapproved']}' class='ipsBadge ipsBadge_orange' data-tooltip="{parse expression="sprintf( $this->lang->words['f_queued'], $forum_data['queued_topics'], $forum_data['queued_posts'])"}" style='vertical-align: top'>{$this->lang->words['f_queued_badge']}</a>
														</if>
														<a href="{parse url="showforum={$forum_data['id']}" seotitle="{$forum_data['name_seo']}" template="showforum" base="public"}" title='{$forum_data['name']}'>{$forum_data['name']}</a>
													</h4>
														
													<if test="showSubForums:|:$forum_data['show_subforums'] AND count( $forum_data['subforums'] ) AND $forum_data['show_subforums']">
														<br />
														<ol class='ipsList_inline ipsType_small subforums' id='subforums_{$forum_data['id']}'>
															<foreach loop="subforums:$forum_data['subforums'] as $__id => $__data">
																<if test="showSubForumsLit:|:$__data[3]"><li class='unread'><else /><li></if>
																	<a href="{parse url="showforum={$__data[0]}" seotitle="{$__data[2]}" template="showforum" base="public"}" title='{$__data[1]}'>{$__data[1]}</a><if test="isNotLast:|: empty($__data[4] )">,</if>
																</li>
															</foreach>
														</ol>
													</if>
																					
													<p class='desc __forum_desc ipsType_small'>{$forum_data['description']}</p>											
												</td>
												<td class='col_c_stats ipsType_small'>
													<ul>
														<li><strong>{$forum_data['topics']}</strong> {$this->lang->words['topics']}</li>
														<li><strong>{$forum_data['posts']}</strong> {$this->lang->words['replies']}</li>
													</ul>
												</td>
												<if test="canSeeLastInfo:|:$this->memberData['gbw_view_last_info']">
												<td class='col_c_post'>
													<if test="hideLastInfo:|:$forum_data['hide_last_info']">
														<ul class='last_post ipsType_small'>
															<li class='desc lighter'><em>{$this->lang->words['f_protected']}</em></li>
														</ul>
													<else />
														{parse template="userSmallPhoto" group="global" params="array('member_id' => $forum_data['last_poster_id'], 'members_seo_name' => $forum_data['seo_last_name'], 'pp_small_photo' => $forum_data['pp_small_photo'], 'alt' => sprintf( $this->lang->words['bindex_userphoto_alt'], $forum_data['last_title'], $forum_data['members_display_name'] ) )"}
														<ul class='last_post ipsType_small'>
															<if test="!$forum_data['last_id']">
																<li class='desc lighter'><em>{$this->lang->words['f_none']}</em></li>
															<else />
																<li>
																	{$forum_data['last_topic_title']}
																</li>
																<if test="lastPosterID:|:$forum_data['last_poster_id']">
																	<li>{$this->lang->words['by']} {parse template="userHoverCard" group="global" params="$forum_data"}</li>
																</if>
																<if test="hideDateUrl:|:$forum_data['_hide_last_date']">
																	<li class='desc lighter blend_links'>{parse date="$forum_data['last_post']" format="DATE"}</li>
																<else />
																	<li class='desc lighter blend_links'><a href='{parse url="showtopic={$forum_data['last_id']}&amp;view=getlastpost" base="public" template="showtopic" seotitle="{$forum_data['seo_last_title']}"}' title='{$this->lang->words['view_last_post']}'>{parse date="$forum_data['last_post']" format="DATE"}</a></li>
																</if>
															</if>
														</ul>
													</if>
												</td>
												</if>
											</tr>
										</if>
									</foreach>
								</table>
							</div>
						</div>
						<br />
					</div>
				</if>
			</foreach>
		</if>
	</div>
	<if test="sideBarEnabled2:|:$this->templateVars['sidebar_enabled']">
		<div id='index_stats' class='ipsLayout_right clearfix' <if test="sidebarclosed2:|:IPSCookie::get('hide_sidebar') == '1'">style='display: none'</if>>
			<foreach loop="side_blocks:$side_blocks as $block">
				{$block}
			</foreach>
		</div>
		<a href='#' id='toggle_sidebar' title='{$this->lang->words['toggle_sidebar']}' data-closed="{$this->lang->words['_laquo']}" data-open="&times;">&nbsp;</a>
	</if>
</div>
<script type='text/javascript'>
//<![CDATA[
	var markerURL  = ipb.vars['base_url'] + "app=forums&module=ajax&section=markasread&i=1"; // Ajax URL so don't use &amp;
	var unreadIcon = "<img src='{$this->settings['img_url']}/f_icon_read.png' />";
	
	<if test="markercatforums:|:is_array( $cat_data ) AND count( $cat_data )">
		<foreach loop="markercategories:$cat_data as $_data">
			<if test="markerhasforums:|:is_array( $_data['forum_data'] ) AND count( $_data['forum_data'] )">
				<foreach loop="markerforums:$_data['forum_data'] as $forum_id => $forum_data">
					<if test="markernotredirect:|:!$forum_data['redirect_on']">
						<if test="markerhasunread:|:$forum_data['_has_unread']">
	ipb.global.registerMarker( "forum_img_{$forum_data['id']}", "{$forum_data['img_new_post']}", markerURL + "&forumid={$forum_data['id']}" );
						</if>
					</if>
				</foreach>
			</if>
		</foreach>
	</if>
//]]>
</script>
<if test="showTotals:|:$this->settings['show_totals']">
	<div id='board_stats'>		
		<ul class='ipsType_small ipsList_inline'>
			<li class='clear'>
				<span class='value'>{$stats['info']['total_posts']}</span>
				{$this->lang->words['total_posts']}
			</li>
			<li class='clear'>
				<span class='value'>{$stats['info']['mem_count']}</span>
				{$this->lang->words['total_members']}
			</li>
			<li class='clear'>
				{IPSMember::makeProfileLink( $stats['info']['last_mem_name'], $stats['info']['last_mem_id'], $stats['info']['last_mem_seo'], 'value' )}
				{$this->lang->words['newest_member']}
			</li>
			<li class='clear' data-tooltip="{$stats['info']['most_time']}">
				<span class='value'>{$stats['info']['most_online']}</span>
				{$this->lang->words['online_at_once']}
			</li>
		</ul>
	</div>
</if>
<div id='board_statistics' class='statistics clearfix'>
	<ul id='stat_links' class='ipsList_inline right ipsType_small'>
		<if test="statsLinks:|:1==1"> <!-- Hook point -->
			<li><a href="{parse url="app=forums&amp;module=extras&amp;section=stats&amp;do=leaders" base="public"}" title="{$this->lang->words['sm_forum_leaders_title']}">{$this->lang->words['sm_forum_leaders']}</a></li>
			<li><a href="{parse url="app=forums&amp;module=extras&amp;section=stats" base="public"}" title="{$this->lang->words['sm_all_posters_title']}">{$this->lang->words['sm_today_posters']}</a></li>
			<li><a href="{parse url="app=members&amp;module=list&amp;max_results=20&amp;sort_key=posts&amp;sort_order=desc&amp;filter=ALL" base="public" seotitle="false"}" title="{$this->lang->words['sm_overall_posters_title']}">{$this->lang->words['sm_overall_posters']}</a></li>
			<if test="reputationEnabled:|:$this->settings['reputation_enabled']">
				<li>
					<a href="{parse url="app=members&amp;module=reputation&amp;section=most" base="public" template="most_liked" seotitle="most_liked"}">
						<if test="reputationType:|:$this->settings['reputation_point_types'] == 'like'">
							{$this->lang->words['most_rep_likes']}
						<else />
							{$this->lang->words['most_rep_rep']}
						</if>
					</a>
				</li>
			</if>
		</if>
	</ul>
	
	<if test="showActive:|:$this->settings['show_active'] && $this->memberData['gbw_view_online_lists']">
		<h4 class='statistics_head'>{parse expression="sprintf( $this->lang->words['online_right_now'], $stats['TOTAL'] )"} {$this->lang->words['active_users']}</h4>
		<p class='statistics_brief desc'>
			{parse expression="sprintf( $this->lang->words['active_users_detail'], $stats['MEMBERS'], $stats['GUESTS'], $stats['ANON'] )"}
			<if test="onlineListEnabled:|:$this->settings['allow_online_list']">&nbsp;&nbsp;<a href='{parse url="app=members&amp;module=online&amp;sort_order=desc" base="public"}'>({$this->lang->words['online_link']})</a></if>
		</p>
	</if>
	<if test="activeNames:|:count($stats['NAMES']) && $this->settings['show_active']">
		<br />
		<p>
			<span class='name'>{parse expression="implode( ",</span> <span class='name'>", $stats['NAMES'] )"}</span>
		</p>
	</if>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: hookBoardIndexShareLinks
//===========================================================================
function hookBoardIndexShareLinks($data) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='general_box alt clearfix' id='hook_watched_items'>
		<h3><img src='{$this->settings['img_url']}/comment_new.png' alt='' /> {$this->lang->words['slh_title']}</h3>
		<div class='recent_activity _sbcollapsable'>
			<ol class='tab_bar no_title mini'>
				<li id='tab_link_most' class='tab_toggle active clickable'>{$this->lang->words['slh_mostly']}</li>
				<li id='tab_link_recent' class='tab_toggle clickable'>{$this->lang->words['slh_recently']}</li>
			</ol>
			<div id='tab_content_most' class='tab_toggle_content'>
				<if test="mostShared:|:is_array( $data['mostitems'] ) && count( $data['mostitems'] )">
					<ul class='clearfix'>
						{parse striping="recent_topics" classes="row1,row2 altrow"}
						<foreach loop="mostitems:$data['mostitems'] as $item">
							<li class='{parse striping="recent_topics"}'>
								<img src="{$this->settings['img_url']}/{$item['icon']}" alt="" />
								<span class='desc'>[{$item['count']}]</span>
								<a href='{$item['url']}'>{$item['title']}</a>
							</li>
						</foreach>
					</ul>
				<else />
					<ul class='clearfix'>
						<if test="updatedforumsn:|:!is_array( $data['mostitems'] ) OR !count( $data['mostitems'] )">
							<li class='{parse striping="recent_topics"}'>{$this->lang->words['slh_nothingtoshow']}</li>
						</if>
					</ul>
				</if>
			</div>
			
			<div id='tab_content_recent' class='tab_toggle_content' style='display:none;'>
				<if test="mostShared:|:is_array( $data['mostrecent'] ) && count( $data['mostrecent'] )">
					<ul class='clearfix'>
						{parse striping="recent_topics" classes="row1,row2 altrow"}
						<foreach loop="mostitems:$data['mostrecent'] as $item">
							<li class='{parse striping="recent_topics"}'>
								<img src="{$this->settings['img_url']}/{$item['icon']}" alt="" />
								<a href='{$item['url']}'>{$item['title']}</a>
							</li>
						</foreach>
					</ul>
				<else />
					<ul class='clearfix'>
						<if test="updatedforumsn:|:!is_array( $data['mostitems'] ) OR !count( $data['mostitems'] )">
							<li class='{parse striping="recent_topics"}'>{$this->lang->words['slh_nothingtoshow']}</li>
						</if>
					</ul>
				</if>
			</div>
		</div>
	</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: hookBoardIndexStatusUpdates
//===========================================================================
function hookBoardIndexStatusUpdates($updates=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type='text/javascript' src="{$this->settings['public_dir']}js/ips.status.js"></script>
<div class='ipsSideBlock clearfix' id='statusHook'>
	<h3>{$this->lang->words['recent_status_updates']}</h3>
	<div class='_sbcollapsable'>
		<div id="status_wrapper">
			<div id="status_wrapper_inside">{parse template="statusUpdates" group="boards" params="$updates, 1, 1"}</div>
			<div class="status_main_content ipsType_small" style='text-align: center'>
				<a href='{parse url="app=members&amp;module=profile&amp;section=status&amp;type=all" seotitle="true" template="members_status_all" base="public"}'>{$this->lang->words['viewallupdates']}</a>
			</div>
		</div>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: hookFacebookActivity
//===========================================================================
function hookFacebookActivity() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="fbAppIdPresent:|:IPSLib::fbc_enabled()">
<div class='general_box clearfix'>
	<h3>{$this->lang->words['hook_facebookactivity']}</h3>
	<div  class='block_list clearfix _sbcollapsable'>
		<div><fb:activity site="" width="270" height="300" header="false" border_color="#FFF" colorscheme="light" /></div>
	</div>
</div><div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({appId: '{$this->settings['fbc_appid']}', status: true, cookie: true,
             xfbml: true});
  };
  (function() {
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol +
      '//connect.facebook.net/{$this->settings['fb_locale']}/all.js';
    document.getElementById('fb-root').appendChild(e);
  }());
</script>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: hookRecentTopics
//===========================================================================
function hookRecentTopics($topics) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="recenttopics:|:is_array( $topics ) && count( $topics )">
<div class='ipsSideBlock clearfix'>
	<h3>{$this->lang->words['recently_added_topics']}</h3>
	<div class='_sbcollapsable'>
		<ul class='ipsList_withminiphoto'>
		<foreach loop="topics_hook:$topics as $r">
		<li class='clearfix'>
			{parse template="userSmallPhoto" group="global" params="$r"}
			<div class='list_content'>
				<a href="{parse url="showtopic={$r['tid']}" base="public" template="showtopic" seotitle="{$r['title_seo']}"}" rel='bookmark' class='ipsType_small' title='{parse expression="strip_tags($r['topic_title'])"} {$this->lang->words['topic_started_on']} {parse date="$r['start_date']" format="LONG"}'>{$r['topic_title']}</a>
				<p class='desc ipsType_smaller'>
					<if test="$r['members_display_name']">{parse template="userHoverCard" group="global" params="$r"}<else />{$this->settings['guest_name_pre']}{$r['starter_name']}{$this->settings['guest_name_suf']}</if>
					- {parse date="$r['start_date']" format="short"}
				</p>
			</div>
		</li>
		</foreach>
		</ul>
	</div>
</div>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: hookTagCloud
//===========================================================================
function hookTagCloud($tags) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='ipsSideBlock clearfix'>
	<h3>{$this->lang->words['popular_tags']}</h3>
	<div class='_sbcollapsable'>
		<ul class='ipsList_inline'>
		<foreach loop="eachTag:$tags['tags'] as $id => $data">
			<li><span class="{$data['className']}">{$data['tagWithUrl']}</span></li>
		</foreach>
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
function statusReplies($replies=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<foreach loop="innerLoop:$replies as $reply">
	<div id='statusReply-{$reply['reply_id']}' class='ipsPad_half'>
		<a href='{parse url="showuser={$reply['member_id']}" seotitle="{$reply['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'><img src='{$reply['pp_mini_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$reply['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_tiny' /></a>
		<div class='list_content'>
			<div class='right lighter'>
				<span class='desc ipsType_smaller'>{parse date="$reply['reply_date']" format="manual{%d %b}" relative="true"}&nbsp;&nbsp;</span>
				<span class='desc mod_links ipsType_smaller'>
					<if test="canDelete:|:$reply['_canDelete']"><a href="{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=deleteReply&amp;status_id={$reply['reply_status_id']}&amp;reply_id={$reply['reply_id']}&amp;k={$this->member->form_hash}" id="statusReplyDelete-{$reply['reply_status_id']}-{$reply['reply_id']}" class="__sDR __dr{$reply['reply_status_id']}-{$reply['reply_id']}">{$this->lang->words['status_delete_link']}</a></if>
				</span>
			</div>
			<strong>{parse template="userHoverCard" group="global" params="$reply"}</strong>
			<p class='index_status_update ipsType_small'>{$reply['reply_content']}</p>
		</div>
	</div>
</foreach>
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
<script type="text/javascript">
	ipb.status.maxReplies = {parse expression="intval($this->settings['su_max_replies'])"};
	ipb.status.smallSpace = {parse expression="intval($smallSpace)"};
	ipb.status.skin_group = 'boards';
</script>
<foreach loop="outerLoop:$updates as $id => $status">
	<if test="$this->memberData['member_id'] AND $latestOnly AND $status['member_id'] == $this->memberData['member_id']">
		<script type="text/javascript">
			ipb.status.myLatest = {$status['status_id']};
		</script>
	</if>
	<ul class='ipsList_withminiphoto status_list' id='statusWrap-{$status['status_id']}'>
		<li class='clearfix'>
			{parse template="userSmallPhoto" group="global" params="array('member_id' => $status['member_id'], 'members_seo_name' => $status['members_seo_name'], 'pp_small_photo' => $status['pp_mini_photo'] )"}
			<div class='list_content'>
				<div id="statusContent-{$status['status_id']}">
					<div class='right desc lighter'>
						<span class='desc mod_links ipsType_smaller'>
							<if test="canDelete:|:$status['_canDelete']"><a rel="nofollow" href="{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=deleteStatus&amp;status_id={$status['status_id']}&amp;k={$this->member->form_hash}" id="statusDelete-{$status['status_id']}" class="__sD __d{$status['status_id']}">{$this->lang->words['status_delete_link']}</a></if>
							<span id='statusUnlock-{$status['status_id']}' <if test="isLocked:|:$status['_isLocked'] AND $status['_canUnlock']">style='display:inline'<else />style='display:none'</if>> &middot; <a rel="nofollow"  href="{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=unlockStatus&amp;status_id={$status['status_id']}&amp;k={$this->member->form_hash}" id="statusUnlockLink-{$status['status_id']}" class="__sU __u{$status['status_id']}">{$this->lang->words['status_unlock_link']}</a></span>
							<span id='statusLock-{$status['status_id']}' <if test="canLock:|:$status['_canLock'] AND ! $status['_isLocked']">style='display:inline'<else />style='display:none'</if>> &middot; <a rel="nofollow"  href="{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=lockStatus&amp;status_id={$status['status_id']}&amp;k={$this->member->form_hash}" id="statusLockLink-{$status['status_id']}" class="__sL __l{$status['status_id']}">{$this->lang->words['status_lock_link']}</a></span>
						</span>
						&nbsp;&nbsp;<span class='ipsType_smaller blend_links'><a href='{parse url="app=members&amp;module=profile&amp;section=status&amp;type=single&amp;status_id={$status['status_id']}" seotitle="true" template="members_status_single" base="public"}'>{parse date="$status['status_date']" format="manual{%d %b}" relative="true"}</a></span>
					</div>
					{parse template="userHoverCard" group="global" params="$status"}
					<p class='index_status_update ipsType_small'>{$status['status_content']}</p>
					<span class='desc' id='statusToggle-{$status['status_id']}'>
						<img src="{$this->settings['img_url']}/icon_lock.png" id='statusLockImg-{$status['status_id']}' alt="{$this->lang->words['status__locked']}" <if test="noLocked:|:!$status['status_is_locked']">style='display: none'</if> />
						<if test="cImg:|:! $smallSpace AND $status['_creatorImg']"><img src="{$status['_creatorImg']}" alt='' data-tooltip="{$this->lang->words['su_via']} {$status['_creatorText']}" /></if>
						<if test="$status['_userCanReply']">
							<if test="$status['status_replies']">
								<a href="#" class="__sT __t{$status['status_id']} ipsType_smaller ">{parse expression="sprintf( $this->lang->words['view_comments_and_add'], $status['status_replies'])"}</a>
							<else />
								<a href="#" class="__sT __t{$status['status_id']} ipsType_smaller">{$this->lang->words['add_comments_only']}</a>
							</if>
						<else />
							<if test="$status['status_replies']">
								<a href="#" class="__sT __t{$status['status_id']} ipsType_smaller">{parse expression="sprintf( $this->lang->words['view_comments_only'], $status['status_replies'])"}</a>
							</if>
						</if>
					</span>
					<span class='desc' id='statusToggleOff-{$status['status_id']}' style='display:none'>
						<a href="#" class="__sTO __to{$status['status_id']}">{$this->lang->words['view_comments_collapse']}</a>
					</span>
				</div>
			</div>
			<div id="statusFeedback-{$status['status_id']}" class='status_feedback' style='display:none'>
				<div class='ipsList_withtinyphoto status_list' id='statusReplies-{$status['status_id']}'>
					<if test="$status['status_replies'] AND count( $status['replies'] )">
						<if test="hasMore:|:$status['status_replies'] > 3">
							<div class='status_mini_wrap row2 altrow' id='statusMoreWrap-{$status['status_id']}'>
								<img src="{$this->settings['img_url']}/comments.png" alt="" /> &nbsp;<a href="#" id="statusMore-{$status['status_id']}" class='__showAll __x{$status['status_id']}'>{parse expression="sprintf( $this->lang->words['status_show_all_x'], $status['status_replies'] )"}</a>
							</div>
						</if>
						{parse template="statusReplies" group="boards" params="$status['replies']"}
					</if>
					<div id='statusReplyBlank-{$status['status_id']}' style='display: none'></div>
				</div>
				<if test="$status['_userCanReply']">
					<div class='ipsList_withtinyphoto status_list status_reply clearfix ipsPad_half' id='statusReply-{$status['status_id']}'>
						<img src='{$this->memberData['pp_mini_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$this->memberData['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_tiny left' />
						<div class='list_content'>
							<form id='statusReplyForm-{$status['status_id']}' action='{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=reply&amp;status_id={$status['status_id']}&amp;k={$this->member->form_hash}&amp;id={$this->memberData['member_id']}' method='post'>
								<textarea class='status_textarea' rows='3' cols='50' name='comment-{$status['status_id']}' id='statusText-{$status['status_id']}'></textarea>
								<div class="status_submit"><input type='submit' class='__submit input_submit' id='statusSubmit-{$status['status_id']}' value='{$this->lang->words['status__dfcomment']}' /></div>
							</form>
							<a href='#' class='__showform' id='statusReplyFormShow-{$status['status_id']}' style='display: none'>{$this->lang->words['status__addcomment']}</a>
						</div>
					</div>
					<script type='text/javascript'>
						if( $('statusReplyForm-{$status['status_id']}') ){
							$('statusReplyForm-{$status['status_id']}').hide();
						}
						if( $('statusReplyFormShow-{$status['status_id']}') ){
							$('statusReplyFormShow-{$status['status_id']}').show();
						}
					</script>
				</if>
			</div>
		</li>
	</ul>
</foreach>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>