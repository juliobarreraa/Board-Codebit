<?php
/**
 * Master skin file
 * Written: Mon, 09 Jul 2012 18:16:42 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: ajax__deleteTopic
//===========================================================================
function ajax__deleteTopic() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['topic_delete']}</h3>
<div class='ipsBox'>
	<div class='ipsBox_container'>
		<div class='ipsPad ipsForm_center'>
			{$this->lang->words['topic_delete_confirm']}<br />
			<br />
			<form action='#{deleteUrl}' method='POST'>
				<input type='submit' class='input_submit' id='delPush' value='{$this->lang->words['topic_delete']}' />
			</form>
		</div>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: forumAttachments
//===========================================================================
function forumAttachments($title, $rows, $pages) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="$this->request['module']=='ajax'">
	<h3>{$this->lang->words['attach_page_title']}: $title</h3>
	<if test="$pages">
		<div class='topic_controls' style='padding-top: 4px;'>{$pages}</div>
	</if>
<else />
	<if test="$pages">
		<div class='topic_controls'>{$pages}</div>
	</if>
	<h3 class='maintitle'>{$this->lang->words['attach_page_title']}: $title</h3>
</if>
<table class='ipb_table'>
	<tr class='header'>
		<th>&nbsp;</th>
		<th>{$this->lang->words['attach_title']}</th>
		<th>{$this->lang->words['attach_size']}</th>
		<th>{$this->lang->words['attach_post']}</th>
	</tr>
	{parse striping="attachies" classes="row1,row2"}
	<if test="count($rows)">
		<foreach loop="attachments:$rows as $data">
			<tr class='{parse striping="attachies"}' id="{$data['attach_id']}">
				<td class="altrow short">
					<img src="{$this->settings['mime_img']}/{$data['image']}" alt="{$this->lang->words['attached_file']}" />
				</td>
				<td>
					<a href="{parse url="app=core&amp;module=attach&amp;section=attach&amp;attach_rel_module=post&amp;attach_id={$data['attach_id']}" base="public"}" title="{$data['attach_file']}">{$data['short_name']}</a><br />
					<span class="desc">( {$this->lang->words['attach_hits']}: {$data['attach_hits']} )<br />( {$this->lang->words['attach_post_date']} {$data['attach_date']} )</span>
				</td>
				<td class="altrow short">{$data['real_size']}</td>
				<td class='short'><a href="{parse url="app=forums&amp;module=forums&amp;section=findpost&amp;pid={$data['pid']}" base="public"}" title="{$this->lang->words['view_post']}">{$data['pid']}</a></td>
			</tr>
		</foreach>
	</if>
</table>
<if test="$pages">
	<br />
	<div class='topic_controls'>{$pages}</div>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: forumIndexTemplate
//===========================================================================
function forumIndexTemplate($forum_data, $announce_data, $topic_data, $other_data, $multi_mod_data, $sub_forum_data, $footer_filter, $active_user_data, $mod_data, $inforum=1) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="forums"}
<script type="text/javascript">
//<![CDATA[
	//Search Setup
	ipb.vars['search_type']		= 'forum';
	ipb.vars['search_type_id']	= {$this->request['showforum']};
	ipb.templates['topic_rename'] = new Template("<input type='text' id='#{inputid}' value='#{value}' class='input_text' size='50' maxlength='{$this->settings['topic_title_max_len']}' /> <input type='submit' value='{$this->lang->words['rename_topic_update']}' class='input_submit' id='#{submitid}' /> <a href='#' id='#{cancelid}' class='cancel' title='{$this->lang->words['cancel']}'>{$this->lang->words['cancel']}</a>");
	var markerURL  = ipb.vars['base_url'] + "app=forums&module=ajax&section=markasread&i=1"; // Ajax URL so don't use &amp;
	var unreadIcon = "<img src='{$this->settings['img_url']}/f_icon_read.png' />";
	
	ipb.templates['topic_moderation'] = new Template("<div id='comment_moderate_box' class='ipsFloatingAction' style='display: none'><span class='desc'>{$this->lang->words['f_comment_action_count']} </span><select id='tactInPopup' class='input_select'></select>&nbsp;&nbsp;<input type='button' class='input_submit' id='submitModAction' value='{$this->lang->words['comments_act_go']}' /></div>");
//]]>
</script>
<if test="watchismember:|:$this->memberData['member_id']">
	<if test="hasFollowData:|:$other_data['follow_data']">
		{$other_data['follow_data']}
	</if>
</if>
<h1 class='ipsType_pagetitle'>{$forum_data['name']}</h1>
<div class='ipsType_pagedesc forum_rules'>
	<if test="rules:|:$forum_data['show_rules']">
		<if test="rulesinline:|:$forum_data['show_rules'] == 2">
			<strong>{$forum_data['rules_title']}</strong>
			{$forum_data['rules_text']}
		</if>
		<if test="ruleslink:|:$forum_data['show_rules'] == 1">
			<a href='{parse url="app=forums&amp;module=forums&amp;section=rules&amp;f={$forum_data['id']}" base="public"}' title='{$this->lang->words['view_forum_rules']}'>{$forum_data['rules_title']}</a>
		</if>
	<else />
		{$forum_data['description']}
	</if>
</div>
<br />
<if test="hasannouncements:|:is_array( $announce_data ) AND count( $announce_data )">
	<table class='ipb_table topic_list hover_rows' summary='{$this->lang->words['forum_topic_list']} "{$forum_data['name']}"' id='announcements'>
		<foreach loop="announcements:$announce_data as $aid => $adata">
			<tr class='row2 announcement' id='arow_{$adata['announce_id']}'>
				<td>
					<php>$_seoTitle	= $adata['announce_seo_title'] ? $adata['announce_seo_title'] : "%%{$adata['announce_title']}%%";</php>
					{parse replacement="t_announcement"}
					<h4>
						<a href="{parse url="showannouncement={$adata['announce_id']}&amp;f={$forum_data['id']}" seotitle="{$_seoTitle}" template="showannouncement" base="public"}" title='{$this->lang->words['view_announcement']}'>{$adata['announce_title']}</a>
					</h4>&nbsp;
					<span class='desc'>{$this->lang->words['posted_by']} {parse template="userHoverCard" group="global" params="$adata"}
						<if test="announcedates:|:$adata['announce_start'] AND $adata['announce_start'] != '--'">, {$adata['announce_start']}</if>
					</span>
				</td>
			</tr>
		</foreach>
	</table>
	<br />
</if>
<!-- __-SUBFORUMS-__ -->
<if test="hassubforums:|:is_array( $sub_forum_data ) AND count( $sub_forum_data )">
	<div class='category_block block_wrap'>
		<h3 class='maintitle'>{$this->lang->words['sub_forum_title']}</h3>
		<div class='ipsBox table_wrap'>
			<div class='ipsBox_container'>
				<table class='ipb_table' summary="{$this->lang->words['cat_subforums']} '{$forum_data['name']}'">
					<foreach loop="subforums:$sub_forum_data as $_data">
						<if test="subforumdata:|:is_array( $_data['forum_data'] ) AND count( $_data['forum_data'] )">
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
									<tr class='<if test="$forum_data['_has_unread']">unread</if>'>
										<td class='col_c_icon'>
											<if test="$forum_data['_has_unread']">
												<a id='forum_img_{$forum_data['id']}' href="{parse url="app=forums&amp;module=forums&amp;section=markasread&amp;marktype=forum&amp;forumid={$forum_data['id']}&amp;returntoforumid={$this->request['f']}&amp;i=1" base="public"}" data-tooltip="{$this->lang->words['bi_markread']}" class='forum_marker'><img src='{$this->settings['img_url']}/f_icon.png' /></a>
												<script type='text/javascript'>
													ipb.global.registerMarker( "forum_img_{$forum_data['id']}", "{$forum_data['img_new_post']}", markerURL + "&forumid={$forum_data['id']}" );
												</script>
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
										<if test="canSeeLastInfoSubs:|:$this->memberData['gbw_view_last_info']">
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
														<li>{$forum_data['last_topic_title']}</li>
														<if test="lastPosterID:|:$forum_data['last_poster_id']">
															<li>{$this->lang->words['by_ucfirst']} {parse template="userHoverCard" group="global" params="$forum_data"}</li>
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
						</if>
					</foreach>
				</table>
			</div>
		</div>
	</div>
	<br /><br />
</if>
<if test="showtopics:|:$forum_data['sub_can_post']">
	<div class='topic_controls clearfix'>
		{$forum_data['SHOW_PAGES']}
		<ul class='topic_buttons'>
			<if test="usercanpost:|:$forum_data['_user_can_post']">
				<li><a href='{parse url="module=post&amp;section=post&amp;do=new_post&amp;f={$forum_data['id']}" base="publicWithApp"}' title='{$this->lang->words['topic_start']}' accesskey='s'>{$this->lang->words['topic_start']}</a></li>
			<else />
				<li class='disabled'><span><if test="isGuestPostTopicTop:|: ! $this->memberData['member_id']">{$this->lang->words['forum_no_start_topic_guest']}<else />{$this->lang->words['forum_no_start_topic']}</if></span></li>
			</if>
			<if test="moderationDropdownLink:|:$this->memberData['is_mod'] == 1">
				<li class='non_button'>
					<a href='#' id='forum_mod_options' class='ipbmenu'>{$this->lang->words['forum_management']}</a>
				</li>
			</if>
			<li class='non_button'>
				<a data-clicklaunch="forumMarkRead" data-fid="{$forum_data['id']}" href='{parse url="app=forums&amp;module=forums&amp;section=markasread&amp;marktype=forum&amp;forumid={$forum_data['id']}&amp;returntoforumid={$forum_data['id']}" base="public"}' title='{$this->lang->words['mark_as_read']}'><img src='{$this->settings['img_url']}/icon_check.png' /> &nbsp;{$this->lang->words['mark_as_read']}</a>
			</li>
		</ul>
		
		
	</div>
	
	<if test="moderationDropdownMenu:|:$this->memberData['is_mod'] == 1">
		<ul class='ipbmenu_content' id='forum_mod_options_menucontent' style='display: none'>
			<li><a href='{parse url="showforum={$forum_data['id']}&amp;modfilter=unapproved" seotitle="{$forum_data['name_seo']}" template="showforum" base="public"}' title='{$this->lang->words['mod_unapproved']}'>{$this->lang->words['mod_unapproved']}</a></li>
			<li><a href='{parse url="showforum={$forum_data['id']}&amp;modfilter=hidden" seotitle="{$forum_data['name_seo']}" template="showforum" base="public"}' title='{$this->lang->words['mod_hidden']}'>{$this->lang->words['mod_hidden']}</a></li>
			<if test="$this->memberData['g_is_supmod']">
				<li><a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=prune_start&amp;f={$forum_data['id']}&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['mod_prumemovetopics']}'>{$this->lang->words['mod_prumemovetopics']}</a></li>
			</if>
		</ul>
	</if>
	<div class='ipsFilterbar maintitle'>
		<if test="topicsismod:|:$this->memberData['is_mod'] == 1">
			<span class='right'>
				<input type='checkbox' id='tmod_all' class='input_check' title='{$this->lang->words['topic_select_all']}' value='1' />
				&nbsp;
			</span>
		</if>
		<ul class='ipsList_inline ipsType_small'>
			<li <if test="ka_last_post:|:$this->request['sort_key'] == 'last_post' and $this->request['sort_by'] == 'Z-A'">class='active'</if>><a href='{parse url="showforum={$forum_data['id']}&amp;st={$this->request['st']}&amp;sort_key=last_post&amp;sort_by=Z-A" base="public" seotitle="{$forum_data['name_seo']}" template="showforum"}' rel='nofollow'>{$this->lang->words['sort_recent']}</a></li>
			<li <if test="ka_start_date:|:$this->request['sort_key'] == 'start_date' and $this->request['sort_by'] == 'Z-A'">class='active'</if>><a href='{parse url="showforum={$forum_data['id']}&amp;st={$this->request['st']}&amp;sort_key=start_date&amp;sort_by=Z-A" base="public" seotitle="{$forum_data['name_seo']}" template="showforum"}' rel='nofollow'>{$this->lang->words['sort_start']}</a></li>
			<li <if test="ka_replies:|:$this->request['sort_key'] == 'posts' and $this->request['sort_by'] == 'Z-A'">class='active'</if>><a href='{parse url="showforum={$forum_data['id']}&amp;st={$this->request['st']}&amp;sort_key=posts&amp;sort_by=Z-A" base="public" seotitle="{$forum_data['name_seo']}" template="showforum"}' rel='nofollow'>{$this->lang->words['sort_replies']}</a></li>
			<li <if test="ka_viewed:|:$this->request['sort_key'] == 'views' and $this->request['sort_by'] == 'Z-A'">class='active'</if>><a href='{parse url="showforum={$forum_data['id']}&amp;st={$this->request['st']}&amp;sort_key=views&amp;sort_by=Z-A" base="public" seotitle="{$forum_data['name_seo']}" template="showforum"}' rel='nofollow'>{$this->lang->words['sort_views']}</a></li>
			<li <if test="$this->request['sort_by'] == 'A-Z' or !in_array( $this->request['sort_key'], array( 'last_post', 'start_date', 'posts', 'views' ) )">class='active'</if>><a href='#forum_filter_menucontent' id='forum_filter'>{$this->lang->words['sort_custom']}</a></li>
		</ul>
	</div>
	<div id='forum_filter_menucontent' class='ipbmenu_content ipsPad' style='display: none'>
		<form id='filter_form' action="{parse url="showforum={$forum_data['id']}&amp;st={$this->request['st']}&amp;changefilters=1" base="public" seotitle="{$forum_data['name_seo']}" template="showforum"}" method="post">
			<strong>{$this->lang->words['filter_type']}</strong><br />
			<select name="topicfilter" id='topic_filter' class='input_select'>{$footer_filter['topic_filter']}</select>
			<br /><br />
		
			<strong>{$this->lang->words['filter_sort']}</strong><br />
			<select name="sort_key" id='sort_by' class='input_select'>{$footer_filter['sort_by']}</select>
			<br /><br />
		
			<strong>{$this->lang->words['filter_direction']}</strong><br />
			<select name="sort_by" id='direction' class='input_select'>{$footer_filter['sort_order']}</select>
			<br /><br />
		
			<strong>{$this->lang->words['filter_time']}</strong><br />
			<select name="prune_day" id='time_frame' class='input_select'>{$footer_filter['sort_prune']}</select>
			<br /><br />
		
			<input type='checkbox' value='1' name='remember' class='input_check' id='remember_filter' /> <label for='remember_filter'>{$this->lang->words['remember_options']}</label>
			<br /><br />
			
			<input type="submit" value="{$this->lang->words['sort_submit']}" class="input_submit" />
		</form>
	</div>
	<script type='text/javascript'>
		new ipb.Menu( $('forum_filter'), $('forum_filter_menucontent'), { stopClose: true } );
	</script>
	<div class='ipsBox'>
		<div class='ipsBox_container'>
			<table class='ipb_table topic_list hover_rows <if test="topicsismod:|:$this->memberData['is_mod'] == 1">is_mod</if>' summary='{$this->lang->words['forum_topic_list']} "{$forum_data['name']}"' id='forum_table'>
				<tr class='header hide'>
					<th scope='col' class='col_f_icon'>&nbsp;</th>
					<th scope='col' class='col_f_topic'>{$this->lang->words['forum_topic']}</th>
					<th scope='col' class='col_f_starter short'>{$this->lang->words['forum_started_by']}</th>
					<th scope='col' class='col_f_views stats'>{$this->lang->words['forum_stats']}</th>
					<if test="canSeeLastInfoHeader:|:$this->memberData['gbw_view_last_info']"><th scope='col' class='col_f_post'>{$this->lang->words['forum_last_post_info']}</th></if>
					<if test="topicsismod:|:$this->memberData['is_mod'] == 1">
						<th scope='col' class='col_f_mod short'><input type='checkbox' id='tmod_all' class='input_check' title='{$this->lang->words['topic_select_all']}' value='1' /></th>
					</if>
				</tr>	
				<!-- BEGIN TOPICS -->
				<if test="hastopics:|:is_array( $topic_data ) AND count( $topic_data )">
					<foreach loop="topics:$topic_data as $tid => $data">
						{parse template="topic" group="forum" params="$data, $forum_data, $other_data, $inforum"}
					</foreach>
				<else />
					<tr> 
						<if test="notopicsspan:|:$this->memberData['is_mod'] == 1">
							<td colspan='6' class='no_messages'>
						<else />
							<td colspan='5' class='no_messages'>
						</if>
							{$this->lang->words['no_topics']}
						</td>
					</tr>
				</if>
			</table>
			<if test="hastopics:|:$other_data['hasMore']">
				<div id='more_topics' style='display: none'>
					<a href='#' id='forum_load_more'>{$this->lang->words['load_more_topics']}</a>
				</div>
				<script type='text/javascript'>
					ipb.forums.fetchMore = {
						'f': parseInt("{$this->request['showforum']}")	,
						'st': parseInt("{$this->request['st']}"),
						'sort_by': "{$this->request['sort_by']}",
						'sort_key': "{$this->request['sort_key']}",
						'topicfilter': "{$this->request['topicfilter']}",
						'prune_day': "{$this->request['prune_day']}",
						'max_topics': "{$this->settings['display_max_topics']}"
					};
				</script>
			</if>
		</div>
	</div>
		
	<br />
	<div class='topic_controls clear'>
		{$forum_data['SHOW_PAGES']}
		<ul class='topic_buttons'>
			<if test="usercanpost:|:$forum_data['_user_can_post']">
				<li><a href='{parse url="module=post&amp;section=post&amp;do=new_post&amp;f={$forum_data['id']}" base="publicWithApp"}' title='{$this->lang->words['topic_start']}' rel='nofollow' accesskey='s'>{$this->lang->words['topic_start']}</a></li>
			<else />
				<li class='disabled'><span><if test="isGuestPostTopic:|: ! $this->memberData['member_id']">{$this->lang->words['forum_no_start_topic_guest']}<else />{$this->lang->words['forum_no_start_topic']}</if></span></li>
			</if>
		</ul>
	</div>
	<br class='clear' />	
	<div id='forum_footer' class='statistics clear clearfix'>
		<if test="hasmoderators:|:is_array( $mod_data ) AND count( $mod_data )">
			<div id='forum_led_by' class='right ipsType_small'>
				<img src='{$this->settings['img_url']}/icon_users.png' /> &nbsp;{$this->lang->words['forum_led_by']}
				<foreach loop="moderators:$mod_data as $p => $r">
					<a href='{$r[0]}' title='{$this->lang->words['view_profile']}'>{$r[1]}</a><if test="moderatorpopup:|:$r[2]"></if><if test="moderatorscomma:|:$p + 1 != count( $mod_data )">,</if>
				</foreach>
			</div>
		</if>
		<if test="showactiveusers:|:is_array( $active_user_data ) AND count( $active_user_data )">
			<div id='forum_active_users' class='active_users stats_list'>
				<h4 class='statistics_head'>{parse expression="sprintf( $this->lang->words['active_users_titlef'], $active_user_data['stats']['total'] )"}</h4>
				<p class='statistics_brief'>{parse expression="sprintf( $this->lang->words['active_users_detail'], $active_user_data['stats']['members'], $active_user_data['stats']['guests'], $active_user_data['stats']['anon'] )"}</p>
				<br />
				<ul class='ipsList_inline'>
					<if test="hasactiveusers:|:is_array( $active_user_data['names'] ) AND count( $active_user_data['names'] )">
						{parse expression="implode( ', ', $active_user_data['names'] )"}
					</if>
				</ul>
			</div>
		</if>	
	</div>
</if>
<br class='clear' />
	
<div id='delPopUp' style='display:none'>
	<h3 class='bar'>{$this->lang->words['dlt_title']}</h3>
	<div class='general_box'>
		<form action='#{removeUrl}' method='POST'>
			<strong>{$this->lang->words['dlt_remove_from_view']}</strong>
			<p class='desc'>{$this->lang->words['dlt_remove_from_view_desc']}</p>
			<p style='padding:4px 0px 4px 0px'>{$this->lang->words['dlt_reason']} <input type='text' name='deleteReason' id='delPop_reason' value='' style='width:65%' /> <input type='submit' class='input_submit' value='{$this->lang->words['dlt_remove']}' /></p>
		</form>
		<div style='#{permaDelete}padding-top:5px'>
			<strong>{$this->lang->words['dlt_delete_from_topic']}</strong>
			<p class='desc'>{$this->lang->words['dlt_delete_from_topic_desc']}</p>
			<p style='padding:4px 0px 4px 0px'><input type='button' class='input_submit' onclick="window.location='#{permaUrl}';" value='{$this->lang->words['dlt_delete_now']}' /></p>
		</div>
	</div>
</div>
<if test="moderationform:|:$this->memberData['is_mod'] == 1">
	<form id='modform' class='right' method="post" action="{parse url="" base="public"}">
		<input type="hidden" name="app" value="forums" />
		<input type="hidden" name="module" value="moderate" />
		<input type="hidden" name="section" value="moderate" />
		<input type="hidden" name="do" value="topicchoice" />
		<input type="hidden" name="st" value="{$this->request['st']}" />
		<input type="hidden" name="f" value="{$forum_data['id']}" />
		<input type="hidden" name="auth_key" value="{$this->member->form_hash}" />
		<input type="hidden" name="modfilter" value="{$this->request['modfilter']}" />
		<input type="hidden" value="{$this->request['selectedtids']}" id='selectedtids' name="selectedtids" />
		<input type="hidden" name="tact" id="tact" value="" />
	</form>
</if>
<select style='display:none' id='multiModOptions'>
<if test="hasMultiModeratorOptions:|:is_array( $multi_mod_data ) AND count( $multi_mod_data )">
	<foreach loop="mm:$multi_mod_data as $mm_data">
		<option value="t_{$mm_data[0]}">{$mm_data[1]}</option>
	</foreach>
</if>
</select>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: forumPasswordLogIn
//===========================================================================
function forumPasswordLogIn($fid="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form action="{parse url="showforum={$fid}" base="public"}" method="post" style='width: 60%; margin: 0 auto;'>
	<input type="hidden" name="L" value="1" />
	
	<h1 class='ipsType_pagetitle'>{$this->lang->words['need_password']}</h1>
	<div class='ipsType_pagedesc'>{$this->lang->words['need_password_txt']}</div>
	
	<br />
	
	<div>
		<fieldset>
			<br />
			<ul class='ipsForm ipsForm_horizontal'>
				<li class='ipsField'>
					<label for='forum_pass' class='ipsField_title'>{$this->lang->words['enter_forum_pass']}</label>
					<p class='ipsField_content'>
						<input type="password" size="40" name="f_password" id='forum_pass' class='input_text' />
					</p>
				</li>
			</ul>
			<br />
		</fieldset>
		<fieldset class='submit'>
			<input type="submit" value="{$this->lang->words['f_pass_submit']}" class="input_submit" /> {$this->lang->words['or']} <a href='{$this->settings['board_url']}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
		</fieldset>
	</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: likeSummary
//===========================================================================
function likeSummary($data, $relId, $opts) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="like"}
<div class='__like right' data-app="{$data['app']}" data-area="{$data['area']}" data-relid="{$relId}" data-isfave="{$data['iLike']}">
	{parse template="likeSummaryContents" group="forum" params="$data, $relId, $opts"}
</div>
<script type="text/javascript">
	var FAVE_TEMPLATE = new Template( "<h3>{parse expression="sprintf( $this->lang->words['unset_fave_title'], $this->lang->words['like_ucfirst_un' . $data['vernacular'] ])"}</h3><div class='ipsPad'><span class='desc'>{parse expression="sprintf( $this->lang->words['unset_fave_words'], $this->lang->words['like_un' . $data['vernacular'] ])"}</span><br /><p class='ipsForm_center'><input type='button' value='{parse expression="sprintf( $this->lang->words['unset_button'], $this->lang->words['like_ucfirst_un' . $data['vernacular'] ])"}' class='input_submit _funset' /></p></div>");
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: likeSummaryContents
//===========================================================================
function likeSummaryContents($data, $relId, $opts=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<span class='ipsButton_extra right <if test="$data['totalCount']">_fmore clickable</if>' title='{parse expression="sprintf( $this->lang->words['like_totalcount_' . $data['vernacular'] ], $data['totalCount'] )"}' data-tooltip="{parse expression="sprintf( $this->lang->words['like_totalcount_' . $data['vernacular'] ], $data['totalCount'] )"}"><img src='{$this->settings['img_url']}/icon_users.png' /> <strong>{$data['totalCount']}</strong></span>
<if test="likeOnlyMembers:|:$this->memberData['member_id']">
	<a href='#' title="<if test="$data['iLike']">{parse expression="sprintf( $this->lang->words['fave_tt_on'], $this->lang->words['like_ucfirst_un' . $data['vernacular'] ])"}<else />{parse expression="sprintf( $this->lang->words['fave_tt_off'], $this->lang->words['like_ucfirst_' . $data['vernacular'] ])"}</if>" class='ftoggle ipsButton_secondary'><if test="$data['iLike']">{parse expression="sprintf( $this->lang->words['unset_fave_button'], $this->lang->words['like_ucfirst_un' . $data['vernacular'] ])"}<else />{parse expression="sprintf( $this->lang->words['set_fave_button'], $this->lang->words['like_ucfirst_' . $data['vernacular'] ])"}</if></a>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: show_rules
//===========================================================================
function show_rules($rules="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h2 class='ipsType_pagetitle'>{$rules['title']}</h2>
<div class='row2 ipsPad' style='line-height: 1.6'>
	{$rules['body']}
</div>
<fieldset class='submit'>
	<a href="{parse url="showforum={$rules['fid']}" base="public" seotitle="{$rules['fseo']}" template="showforum"}">{$this->lang->words['back_to_forum']}</a>
</fieldset>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: topic
//===========================================================================
function topic($data, $forum_data, $other_data, $inforum) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="queuedtopic:|:($this->memberData['is_mod'] AND ! $data['approved']) OR $data['_isDeleted']">
<tr itemscope itemtype="http://schema.org/Article" class='__topic <if test="!$data['folder_img']['is_read']">unread</if> expandable moderated' id='trow_{$data['_tid']}' data-tid="{$data['_tid']}">
<else />
<tr itemscope itemtype="http://schema.org/Article" class='__topic <if test="!$data['folder_img']['is_read']">unread</if> expandable' id='trow_{$data['_tid']}' data-tid="{$data['_tid']}">
</if>
	<td class='col_f_icon altrow short'>
		{parse template="generateTopicIcon" group="global_other" params="$data['folder_img'], isset($data['_unreadUrl']) ? $data['_unreadUrl'] : ''"}
	</td>
	<td class='col_f_content <if test="hasmodlinks:|:$this->memberData['is_mod'] == 1 || $forum_data['permissions']['TopicSoftDelete']"> with_mod_links</if>'>
		<if test="archivedBadge:|:$data['_archiveFlag'] == 'archived' && $this->lang->words['topic_is_archived']">
			<span class='ipsBadge ipsBadge_lightgrey'>{$this->lang->words['topic_is_archived']}</span>
		</if>
		<if test="archivingBadge:|:$data['_archiveFlag'] == 'working'">
			<span class='ipsBadge ipsBadge_grey'>{$this->lang->words['topic_is_beingarchived']}</span>
		</if>
		<if test="topicUnapproved:|:$data['approved'] == 0">
			<span class='ipsBadge ipsBadge_orange'>{$this->lang->words['f_queued_badge']}</span>
		<else />
			<if test="queuedpostsImg:|:($this->memberData['is_mod'] AND $data['_hasqueued'])">
				<a href='{parse url="showtopic={$data['tid']}&amp;modfilter=invisible_posts" seotitle="{$data['title_seo']}" template="showtopic" base="public"}' title='{$this->lang->words['view_uapproved_posts']}' data-tooltip="{parse expression="sprintf( $this->lang->words['topic_queued_count'], $data['topic_queuedposts'] )"}"><span class='ipsBadge ipsBadge_orange'>{$this->lang->words['f_queued_badge']}</span></a>
			</if>
		</if>
		<if test="hasPrefix:|:!empty($data['tags']['formatted']['prefix'])">
			{$data['tags']['formatted']['prefix']}
		</if>
		<if test="showForumNav:|: ! $inforum && $data['nav']">
			<foreach loop="nav:$data['nav'] as $nav">
				<a href="{parse url="$nav[1]" template="showforum" base="public" seotitle="$nav[2]"}" class="ipsText_small desc">{$nav[0]}</a> <span class="ipsText_small desc">&rarr;</span>&nbsp;
			</foreach>
		</if>
		<h4>
			{$data['prefix']}
			<a itemprop="url" id="tid-link-{$data['_tid']}" href="{$data['_url']}" title='{parse expression="strip_tags($data['title'])"} {$this->lang->words['topic_started_on']} {parse date="$data['start_date']" format="LONG"}' class='topic_title' <if test="topicDeletedReason:|:$data['_isDeleted']">data-tooltip="{parse expression="sprintf( $this->lang->words['tdb__forumindex'], $other_data['sdData'][ $data['tid'] ]['members_display_name'] )"} {parse date="$other_data['sdData'][ $data['tid'] ]['sdl_obj_date']" format="long"}
					<if test="showReason:|:$forum_data['permissions']['SoftDeleteReason']">
						<br /><span><if test="$other_data['sdData'][ $data['tid'] ]['sdl_obj_reason']">{$other_data['sdData'][ $data['tid'] ]['sdl_obj_reason']}<else />{$this->lang->words['tdb__noreasongi']}</if></span>
					</if>
				"</if>>
				<span itemprop="name">{$data['title']}</span>
			</a>
		</h4>
		<br />
		<span class='desc lighter blend_links'>
			{parse expression="sprintf( $this->lang->words['topic_started_by'], $data['starter'] )"} <span itemprop="dateCreated">{parse date="$data['start_date']" format="DATE"}</span>
			<if test="hasTags:|:isset($data['tags']) AND $data['tags']">
				&nbsp; <img src='{$this->settings['img_url']}/icon_tag.png' /> {$data['tags']['formatted']['truncatedWithLinks']}
			</if>
		</span>
		<if test="multipages:|:isset( $data['pages'] ) AND is_array( $data['pages'] ) AND count( $data['pages'] )">
			<ul class='mini_pagination'>
			<foreach loop="pages:$data['pages'] as $page">
					<if test="haslastpage:|:$page['last']">
						<li><a href="{parse url="showtopic={$data['tid']}&amp;st={$page['st']}" seotitle="{$data['title_seo']}" template="showtopic" base="public"}" title='{$data['title']} {$this->lang->words['topic_goto_page']} {$page['page']}'>{$page['page']} {$this->lang->words['_rarr']}</a></li>
					<else />
						<li><a href="{parse url="showtopic={$data['tid']}&amp;st={$page['st']}" seotitle="{$data['title_seo']}" template="showtopic" base="public"}" title='{$data['title']} {$this->lang->words['topic_goto_page']} {$page['page']}'>{$page['page']}</a></li>
					</if>
			</foreach>
			</ul>
		</if>
	</td>
	<td class='col_f_preview __topic_preview'>
		<if test="$this->registry->permissions->check( 'read', $forum_data )">
			<a href='{$data['_url']}' class='expander closed' title='{$this->lang->words['view_topic_preview']}'>&nbsp;</a>
		</if>
	</td>
	<td class='col_f_views desc blend_links'>
		<ul>
			<li>
				<if test="isLink:|:$data['state'] != 'link'">
					<if test="isHot:|:$data['folder_img']['is_hot']">
						<span class='ipsBadge ipsBadge_orange'>{$this->lang->words['topic_is_hot']}</span>&nbsp;
					</if>
					<if test="isMember:|:$this->memberData['member_id'] && $data['_archiveFlag'] != 'archived'"><a href="{parse url="app=forums&amp;module=extras&amp;section=stats&amp;do=who&amp;t={$data['tid']}" base="public"}" onclick="return ipb.forums.retrieveWhoPosted( {$data['tid']} );"></if>{parse format_number="$data['posts']"} <if test="replylang:|:intval($data['posts']) == 1">{$this->lang->words['reply']}<else />{$this->lang->words['replies']}</if><if test="isMemberCloseA:|:$this->memberData['member_id'] && $data['_archiveFlag'] != 'archived'"></a></if>
					<meta itemprop="interactionCount" content="UserComments:{$data['posts']}"/>
				</if>
			</li>
			<li class='views desc'>{parse format_number="$data['views']"} {$this->lang->words['views']}</li>
		</ul>
	</td>
	<if test="canSeeLastInfo:|:$this->memberData['gbw_view_last_info']">
	<td class='col_f_post'>
		{parse template="userSmallPhoto" group="global" params="array_merge( $data, array( 'alt' => sprintf( $this->lang->words['findex_userphoto_alt'], $data['title'], $data['members_display_name'] ) ) )"}
		<ul class='last_post ipsType_small'>
			<li>{$data['last_poster']}</li>
			<li>
				<a href='{parse url="showtopic={$data['tid']}&amp;view=getlastpost" seotitle="{$data['title_seo']}" template="showtopic" base="public"}' title='{$this->lang->words['goto_last_post']}: {$data['title']}'>
					<if test="$data['last_real_post']">
						{parse date="$data['last_real_post']" format="DATE"}
					<else />
						{parse date="$data['last_post']" format="DATE"}
					</if>
				</a>
			</li>
		</ul>
	</td>
	</if>
	<if test="mmicon:|:$this->memberData['is_mod'] == 1 and $inforum == 1">
		<if test="archivedCb:|:$data['_isArchived']">
			<td class='col_f_mod short'>&nbsp;</td>
		<else />
			<td class='col_f_mod short'>
				<a href='#' class='ipsModMenu' id='topic_mod_{$data['real_tid']}' title='{$this->lang->words['mod_actions']}'>&nbsp;</a>
				<if test="mmtidon:|:$data['tidon'] == 0">
					<input type='checkbox' class='input_check topic_mod' id='tmod_{$data['real_tid']}' data-approved="{$data['approved']}" data-open="<if test="$data['state'] == 'open'">1<else />0</if>" data-pinned="{$data['pinned']}" />
				<else />
					<input type='checkbox' class='input_check topic_mod' id='tmod_{$data['real_tid']}' checked='checked' data-approved="{$data['approved']}" data-open="<if test="$data['state'] == 'open'">1<else />0</if>" data-pinned="{$data['pinned']}" />
				</if>
				<if test="topicIsDeleted:|:$data['_isDeleted']">
					<ul id='topic_mod_{$data['real_tid']}_menucontent' class='ipbmenu_content' style='display: none'>
						<if test="tidRestore:|:$forum_data['permissions']['TopicSoftDeleteRestore']">
							<li class='t_restore'><a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;f={$forum_data['id']}&amp;t={$data['tid']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;do=sundelete" base="public"}' title='{$this->lang->words['dlt_restore_topic']}'>{$this->lang->words['dlt_restore_topic']}</a></li>
						</if>
						<if test="$this->memberData['g_is_supmod'] == 1 || $this->memberData['forumsModeratorData'][ $forum_data['id'] ]['delete_topic']">
							<li><a class='t_delete' href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=topicchoice&amp;tact=deletedo&amp;f={$forum_data['id']}&amp;st={$this->request['st']}&amp;selectedtids[{$data['real_tid']}]={$data['real_tid']}&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['topic_delete']}'>{$this->lang->words['topic_delete']}</a></li>
						</if>
					</ul>
				<else />
					<if test="topicmoderator:|:$this->memberData['is_mod'] == 1  || $forum_data['permissions']['TopicSoftDelete']">
						<ul id='topic_mod_{$data['real_tid']}_menucontent' class='ipbmenu_content' style='display: none'>
							<if test="isUnapproved:|:$data['approved'] == 0">
								<li><a class='t_approve' href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;f={$forum_data['id']}&amp;st={$this->request['st']}&amp;t={$data['real_tid']}&amp;auth_key={$this->member->form_hash}&amp;do=topic_approve&amp;from=forum" base="public"}' title='{$this->lang->words['topic_hide']}'>{$this->lang->words['topic_approve']}</a></li>
							</if>
							<li><a class='t_rename' href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;f={$forum_data['id']}&amp;t={$data['tid']}&amp;auth_key={$this->member->form_hash}&amp;do=05" base="public"}' title='{$this->lang->words['topic_rename']}'>{$this->lang->words['topic_rename']}</a></li>
							<if test="islink:|:$data['state'] != 'link'">
								<if test="ispinned:|:$data['pinned'] && ($this->memberData['g_is_supmod'] OR $this->memberData['forumsModeratorData'][ $forum_data['id'] ]['pin_topic'])">
									<li><a class='t_pin' href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;f={$forum_data['id']}&amp;t={$data['tid']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;do=16&amp;from=forum" base="public"}' title='{$this->lang->words['topic_unpin']}'>{$this->lang->words['topic_unpin']}</a></li>
								<else />
									<if test="issupermod:|:$this->memberData['g_is_supmod'] OR $this->memberData['forumsModeratorData'][ $forum_data['id'] ]['unpin_topic']">
										<li><a class='t_pin' href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;f={$forum_data['id']}&amp;t={$data['tid']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;do=15&amp;from=forum" base="public"}' title='{$this->lang->words['topic_pin']}'>{$this->lang->words['topic_pin']}</a></li>
									</if>
								</if>
							</if>
							<if test="closedtopic:|:$data['state'] == 'closed' && $other_data['can_open_topics']">
								<li><a class='t_lock' href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;f={$forum_data['id']}&amp;t={$data['tid']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;do=01" base="public"}' title='{$this->lang->words['topic_open']}'>{$this->lang->words['topic_open']}</a></li>
							</if>
							<if test="opentopic:|:$data['state'] == 'open' && $other_data['can_close_topics']">
								<li><a class='t_lock' href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;f={$forum_data['id']}&amp;t={$data['tid']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;do=00&amp;_from=forum" base="public"}' title='{$this->lang->words['topic_close']}'>{$this->lang->words['topic_close']}</a></li>
							</if>
							<if test="islink2:|:$data['state'] != 'link' && $other_data['can_move_topics']">
								<li><a class='t_move' href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;f={$forum_data['id']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;do=topicchoice&amp;tact=move&amp;selectedtids={$data['tid']}" base="public"}'>{$this->lang->words['topic_move']}</a></li>
							</if>
							<if test="$this->memberData['g_is_supmod'] == 1 || $forum_data['permissions']['TopicSoftDelete']">
								<li><a class='t_hide' href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;f={$forum_data['id']}&amp;st={$this->request['st']}&amp;t={$data['real_tid']}&amp;auth_key={$this->member->form_hash}&amp;do=03" base="public"}' title='{$this->lang->words['topic_hide']}'>{$this->lang->words['topic_hide']}</a></li>
							</if>
							<if test="$this->memberData['g_is_supmod'] == 1 || $this->memberData['forumsModeratorData'][ $forum_data['id'] ]['delete_topic']">
								<li><a class='t_delete' href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=topicchoice&amp;tact=deletedo&amp;f={$forum_data['id']}&amp;st={$this->request['st']}&amp;selectedtids[{$data['real_tid']}]={$data['real_tid']}&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['topic_delete']}'>{$this->lang->words['topic_delete']}</a></li>
							</if>
						</ul>
					</if>
				</if>
				<script type='text/javascript'>
					new ipb.Menu( $('topic_mod_{$data['real_tid']}'), $('topic_mod_{$data['real_tid']}_menucontent') );
				</script>
			</td>
		</if>
	</if>
</tr>
<if test="adCodeCheck:|:isset($data['_adCode']) AND $data['_adCode']">
<tr>
	<th scope='col' colspan='<if test="adCodeColSpan:|:$this->memberData['is_mod'] == 1">6<else />5</if>'>
		{$data['_adCode']}
	</th>
</tr>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: topic_rating_image
//===========================================================================
function topic_rating_image($rating_id=1) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<span class='mini_rate'>
<foreach loop="ratings:array(1,2,3,4,5) as $int">
	<if test="ratinggood:|:$rating_id >= $int">
		{parse replacement="mini_rate_on"}
	<else />
		{parse replacement="mini_rate_off"}
	</if>
</foreach>
</span>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: topicPrefixWrap
//===========================================================================
function topicPrefixWrap($text) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="prefix:|:$text">
	<span class='ipsBadge ipsBadge_green'>{$text}</span>&nbsp;
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>