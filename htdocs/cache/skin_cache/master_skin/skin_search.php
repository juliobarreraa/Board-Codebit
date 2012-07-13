<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: asForumPosts
//===========================================================================
function asForumPosts($data) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<!--Begin Msg Number {$data['pid']}-->
<div class='post_block hentry clear no_sidebar ipsBox_container' id='post_id_{$data['pid']}'>
	<div class='post_wrap'>
		<if test="postMid:|:$data['member_id']">
			<h3 class='row2'>
		<else />
			<h3 class='guest row2'>
		</if>
		<if test="postModCheckbox:|:$this->memberData['g_is_supmod']">
			<span class='right'>
				<label for='checkbox_{$data['pid']}' class='post_mod hide'>{$this->lang->words['mod_select_post']}</label><input type='checkbox' id='checkbox_{$data['pid']}' name='selectedpids[]' value='{$data['pid']}' class='post_mod right'<if test="postModSelected:|:!empty( $data['_pid_selected'] )"> checked='checked'</if> />
			</span>
		</if>
			<span class='post_id right ipsType_small desc blend_links'><a href='{parse url="showtopic={$data['topic_id']}&amp;view=findpost&amp;p={$data['pid']}" template="showtopic" seotitle="{$data['title_seo']}" base="public"}' rel='bookmark' title='{$this->lang->words['link_to_post']} #{$data['pid']}'>#{$data['pid']}</a></span>
			<a href="{parse url="showtopic={$data['tid']}" seotitle="{$data['title_seo']}" template="showtopic" base="public"}">{IPSText::truncate( $data['topic_title'], 80)}</a>
		</h3>
		<div class='post_body'>
			<p class='posted_info desc lighter ipsType_small'>
				<img src='{$data['pp_thumb_photo']}' class='ipsUserPhoto ipsUserPhoto_tiny' /> {$this->lang->words['posted']} {$this->lang->words['search_by']}
				<if test="postMember:|:$data['member_id']"><span class="author vcard">{parse template="userHoverCard" group="global" params="$data"}</span><else />{$data['members_display_name']}</if>
				{$this->lang->words['on']} <abbr class="published" title="{parse expression="date( 'c', $data['_post_date'] )"}">{parse date="$data['_post_date']" format="long"}</abbr>
				<if test="hasForumTrail:|:$data['_forum_trail']">
					{$this->lang->words['in']}
					<foreach loop="topicsForumTrail:$data['_forum_trail'] as $i => $f">
						<if test="notLastFtAsForum:|:$i+1 == count( $data['_forum_trail'] )"><a href='{parse url="{$f[1]}" template="showforum" seotitle="{$f[2]}" base="public"}'>{$f[0]}</a></if>
					</foreach>
				</if>
			</p>
			<div class='post entry-content'>
				{$data['post']}
				{$data['attachmentHtml']}
			</div>
		</div>
	</div>
	<br />
</div>
<hr />
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: asForumTopics
//===========================================================================
function asForumTopics($data) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<tr class='__topic __tid{$data['tid']} <if test="!$data['_icon']['is_read']">unread</if> expandable <if test="$data['approved'] != 1"> moderated</if>' id='trow_{$data['tid']}' data-tid="{$data['tid']}">
	<td class='col_f_icon short altrow'>
		{parse template="generateTopicIcon" group="global_other" params="$data['_icon'], $data['_unreadUrl']"}
	</td>
	<td>
		<if test="archivedBadge:|:$this->registry->class_forums->fetchArchiveTopicType( $data ) == 'archived'">
			<span class='ipsBadge ipsBadge_lightgrey'>{$this->lang->words['topic_is_archived']}</span>
		</if>
		<if test="hasPrefix:|:!empty($data['tags']['formatted']['prefix'])">
			{$data['tags']['formatted']['prefix']}
		</if>
		<h4><a href='{parse url="showtopic={$data['tid']}<if test="isNewPostTR:|:$this->request['do']=='new_posts' OR $this->request['do']=='active'">&amp;view=getnewpost<else /><if test="resultIsPostTR:|:$data['pid'] AND $data['pid'] != $data['topic_firstpost']">&amp;view=findpost&amp;p={$data['pid']}</if></if>&amp;hl={$data['cleanSearchTerm']}" seotitle="{$data['title_seo']}" template="showtopic" base="public"}' title='{$this->lang->words['view_result']}'>{$data['_shortTitle']}</a></h4>
		<span class='desc blend_links'>
			<foreach loop="topicsForumTrail:$data['_forum_trail'] as $i => $f">
			<if test="notLastFtAsForum:|:$i+1 == count( $data['_forum_trail'] )"><span class='desc lighter'>{$this->lang->words['search_aft_in']}</span> <a href='{parse url="{$f[1]}" template="showforum" seotitle="{$f[2]}" base="public"}'>{$f[0]}</a></if>
			</foreach>
		</span>
		<span class='desc lighter blend_links toggle_notify_off'>
			<br />{$this->lang->words['aft_started_by']} {$data['starter']}, {parse date="$data['start_date']" format="DATE"}
			<if test="hasTags:|:count($data['tags']['formatted'])">
				&nbsp;<img src='{$this->settings['img_url']}/icon_tag.png' /> {$data['tags']['formatted']['truncatedWithLinks']}
			</if>
		</span>
		<if test="multipages:|:isset( $data['pages'] ) AND is_array( $data['pages'] ) AND count( $data['pages'] )">
			<ul class='mini_pagination toggle_notify_off'>
			<foreach loop="pages:$data['pages'] as $page">
					<if test="haslastpage:|:$page['last']">
						<li><a href="{parse url="showtopic={$data['tid']}&amp;st={$page['st']}" seotitle="{$data['title_seo']}" template="showtopic" base="public"}" title='{$this->lang->words['topic_goto_page']} {$page['page']}'>{$page['page']} {$this->lang->words['_rarr']}</a></li>
					<else />
						<li><a href="{parse url="showtopic={$data['tid']}&amp;st={$page['st']}" seotitle="{$data['title_seo']}" template="showtopic" base="public"}" title='{$this->lang->words['topic_goto_page']} {$page['page']}'>{$page['page']}</a></li>
					</if>
			</foreach>
			</ul>
		</if>
		<if test="bothSearchUnderTitle:|:IPSSearchRegistry::get('set.searchResultType') == 'both'">
			<span class='desc lighter blend_links toggle_notify_off'>
				<br />{$this->lang->words['n_last_post_by']} {$data['last_poster']},
				<a href='{parse url="showtopic={$data['tid']}&amp;view=getlastpost" seotitle="{$data['title_seo']}" template="showtopic" base="public"}' title='{$this->lang->words['goto_last_post']}'>{parse date="$data['_last_post']" format="DATE"}</a>
			</span>
		</if>
		<if test="isFollowedStuff:|:count($data['_followData'])">
			{parse template="followData" group="search" params="$data['_followData']"}
		</if>
	</td>
	<td class='col_f_preview __topic_preview'>
		<a href='#' class='expander closed' title='{$this->lang->words['view_topic_preview']}'>&nbsp;</a>
	</td>
	<td class='col_f_views'>
		<ul>
			<li>{parse format_number="$data['posts']"} <if test="replylang:|:intval($data['posts']) == 1">{$this->lang->words['reply']}<else />{$this->lang->words['replies']}</if></li>
			<li class='views desc'>{parse format_number="$data['views']"} {$this->lang->words['views']}</li>
		</ul>
	</td>
	<td class='col_f_post'>
		{parse template="userSmallPhoto" group="global" params="$data"}
		<ul class='last_post ipsType_small'>
			<if test="bothSearch:|:IPSSearchRegistry::get('set.searchResultType') == 'both'">
				<li>{parse template="userHoverCard" group="global" params="$data"}</li>
				<li>
					<a href='{parse url="showtopic={$data['tid']}&amp;view=getlastpost" seotitle="{$data['title_seo']}" template="showtopic" base="public"}' title='{$this->lang->words['goto_last_post']}'>{$this->lang->words['n_posted']} {parse date="$data['_post_date']" format="DATE"}</a>
				</li>
			<else />
				<li>{$data['last_poster']}</li>
				<li>
					<a href='{parse url="showtopic={$data['tid']}&amp;view=getlastpost" seotitle="{$data['title_seo']}" template="showtopic" base="public"}' title='{$this->lang->words['goto_last_post']}'>{parse date="$data['_last_post']" format="DATE"}</a>
				</li>
			</if>
		</ul>
	</td>
	<if test="isFollowedStuff:|:count($data['_followData'])">
		<td class='col_f_mod'>
			<input class='input_check checkall toggle_notify_on' type="checkbox" name="likes[]" value="{$data['_followData']['like_app']}-{$data['_followData']['like_area']}-{$data['_followData']['like_rel_id']}" />
		</td>
	<else />
		<if test="isAdmin:|:$this->memberData['g_is_supmod']">
			<td class='col_f_mod'>
				<if test="isArchivedCb:|:$this->request['search_app_filters']['forums']['liveOrArchive'] == 'archive'">
					&nbsp;
				<else />
					<input type='checkbox' class='input_check topic_mod' id='tmod_{$data['tid']}' />
				</if>
			</td>
		</if>
	</if>
</tr>
<if test="$data['pid']">
<script type='text/javascript'>
ipb.global.searchResults[ {$data['tid']} ] = { pid: {parse expression="intval($data['pid'])"}, searchterm:"{$data['cleanSearchTerm']}" };
</script>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: followData
//===========================================================================
function followData($followData) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<p class='notify_info toggle_notify_on'>
	<span>
		<if test="$followData['like_notify_do']">
			<img src='{$this->settings['img_url']}/icon_follow_freq.png' /> &nbsp;{$this->lang->words['notify_freq_' . $followData['like_notify_freq'] ]}
		<else />
		 	<img src='{$this->settings['img_url']}/icon_follow_freq_none.png' /> &nbsp;{$this->lang->words['notify_freq_none']}
		</if>
	</span>
	<if test="$followData['like_is_anon']">
		<span>
			<img src='{$this->settings['img_url']}/icon_follow_anon.png' /> &nbsp;{$this->lang->words['anonymous']}
		</span>
	</if>
</p>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: followedContentForumsWrapper
//===========================================================================
function followedContentForumsWrapper($results) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="forums"}
<script type='text/javascript' src='{$this->settings['public_dir']}js/ips.forums.js'></script>
<table class='ipb_table topic_list' id='forum_table'>
	<if test="count($results)">
		<foreach loop="NCresultsAsForum:$results as $result">
			{$result['html']}
		</foreach>
	</if>
</table>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: followedContentForumsWrapperForums
//===========================================================================
function followedContentForumsWrapperForums($results) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type='text/javascript'>
//<![CDATA[
	var markerURL  = ipb.vars['base_url'] + "app=forums&module=ajax&section=markasread&i=1"; // Ajax URL so don't use &amp;
	var unreadIcon = "<img src='{$this->settings['img_url']}/f_icon_read.png' />";
//]]>
</script>
<table class='ipb_table topic_list' id='forum_table'>
	<if test="count($results)">
		<foreach loop="NCresultsAsForum:$results as $forum_data">
			<tr class='<if test="$forum_data['_has_unread']">unread</if>'>
				<td class='col_c_icon altrow'>
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
					<h4><a href="{parse url="showforum={$forum_data['id']}" seotitle="{$forum_data['name_seo']}" template="showforum" base="public"}" title='{$forum_data['name']}'>{$forum_data['name']}</a></h4>
					
					<if test="showSubForums:|:$forum_data['show_subforums'] AND count( $forum_data['subforums'] ) AND $forum_data['show_subforums']">
						<br />
						<ol class='ipsList_inline ipsType_small subforums toggle_notify_off' id='subforums_{$forum_data['id']}'>
							<foreach loop="subforums:$forum_data['subforums'] as $__id => $__data">
								<if test="showSubForumsLit:|:$__data[3]"><li class='unread'><else /><li></if>
									<a href="{parse url="showforum={$__data[0]}" seotitle="{$__data[2]}" template="showforum" base="public"}" title='{$__data[1]}'>{$__data[1]}</a>
								</li>
							</foreach>
						</ol>
					</if>
					<if test="isFollowedStuff:|:count($forum_data['_followData'])">
						{parse template="followData" group="search" params="$forum_data['_followData']"}
					</if>
				</td>
				<td class='col_c_stats ipsType_small'>
					<strong>{$forum_data['topics']}</strong> {$this->lang->words['topics']}<br />
					<strong>{$forum_data['posts']}</strong> {$this->lang->words['replies']}
				</td>
				<td class='col_c_post'>
					<if test="hideLastInfo:|:$forum_data['hide_last_info']">
						<ul class='last_post'>
							<li class='desc'>{$this->lang->words['f_protected']}</li>
						</ul>
					<else />
						<if test="hasphoto:|:$forum_data['pp_small_photo'] AND !$forum_data['hide_last_info']">
							<a href='{parse url="showuser={$forum_data['last_poster_id']}" template="showuser" seotitle="{$forum_data['seo_last_name']}" base="public"}' class='ipsUserPhotoLink left'>
								<img src='{$forum_data['pp_small_photo']}' alt='{$this->lang->words['photo']}' class='ipsUserPhoto ipsUserPhoto_mini' />
							</a>
						</if>
						<ul class='last_post ipsType_small'>
							<if test="!$forum_data['last_id']">
								<li class='desc lighter'><em>{$this->lang->words['f_none']}</em></li>
							<else />
								<li>
									<a href='{parse url="showtopic={$forum_data['last_topic_id']}&amp;view=getnewpost" seotitle="{$forum_data['seo_last_title']}" template="showtopic" base="public"}' title="{$this->lang->words['view_new_post']}">
										{parse expression="IPSText::truncate( $forum_data['last_title'], 28)"}
									</a>
								</li>
								<if test="lastPosterID:|:$forum_data['last_poster_id']">
									<li>{$this->lang->words['by_ucfirst']} {parse template="userHoverCard" group="global" params="$forum_data"}</li>
								</if>
								<if test="hideDateUrl:|:$forum_data['_hide_last_date']">
									<li class='desc lighter'>{parse date="$forum_data['last_post']" format="DATE"}</li>
								<else />
									<li class='desc lighter blend_links'><a href='{parse url="showtopic={$forum_data['last_id']}&amp;view=getlastpost" base="public" template="showtopic" seotitle="{$forum_data['seo_last_title']}"}' title='{$this->lang->words['view_last_post']}'>{parse date="$forum_data['last_post']" format="DATE"}</a></li>
								</if>
							</if>
						</ul>
					</if>
				</td>
				<td class='col_f_mod'>
					<input class='input_check checkall toggle_notify_on' type="checkbox" name="likes[]" value="{$forum_data['_followData']['like_app']}-{$forum_data['_followData']['like_area']}-{$forum_data['_followData']['like_rel_id']}" />
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
// Name: followedContentView
//===========================================================================
function followedContentView($results, $pagination, $total, $error, $contentTypes) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="haslikeserror:|:$error">
<p class='message error'>
	{$error}
</p>
<else />
	<if test="hasconfirm:|:$this->request['confirm']">
	<p class='message'>
		{$this->lang->words['likes_rem_suc']}
	</p>
	</if>
</if>
<br />
<input type='hidden' name="usedInJsLater" id="urlString" value="{parse expression="base64_encode( $this->registry->output->buildUrl( "app=core&amp;module=search&amp;do=followed&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}", "public" ) )"}" />
<h1 class='ipsType_pagetitle'>{$this->lang->words['followed_content']}</h1>
<div class='ipsType_pagedesc'>
	{$this->lang->words['followed_content_desc']}
</div>
<br />
<div class='ipsLayout ipsLayout_withleft clearfix'>
	<div class='ipsLayout_left'>
		<div class='ipsBox'>
			<div class='ipsBox_container ipsSideMenu'>
				<h4>{$this->lang->words['filter_by_app']}</h4>
				<ul>
					<if test="memberFollow:|:IPSLib::appSupportsExtension( 'forums', array('like') )"><li <if test="forumsTab:|:$this->request['search_app'] == 'forums'">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=followed&amp;search_app=forums&amp;sid={$this->request['_sid']}" base="public"}'>{IPSLib::getAppTitle( 'forums' )}</a></li></if>
					<if test="memberFollow:|:IPSLib::appSupportsExtension( 'members', array('like') )"><li <if test="membersTab:|:$this->request['search_app'] == 'members'">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=followed&amp;search_app=members&amp;sid={$this->request['_sid']}" base="public"}'>{IPSLib::getAppTitle( 'members' )}</a></li></if>
					<foreach loop="apps:$this->registry->getApplications() as $app">
						<if test="supportsLikes:|:IPSLib::appSupportsExtension( $app['app_directory'], array('like') ) AND !in_array( $app['app_directory'], array('core','forums','members') )">
							<li <if test="appIsSearched:|:$this->request['search_app'] == $app['app_directory']">class='active'</if>>
								<a href='{parse url="app=core&amp;module=search&amp;do=followed&amp;search_app={$app['app_directory']}&amp;sid={$this->request['_sid']}" base="public"}'>
									{IPSLib::getAppTitle( $app['app_directory'] )}
								</a>
							</li>
				 		</if>
					</foreach>
				</ul>
				<if test="is_array($contentTypes) AND count( $contentTypes )">
					<h4>{$this->lang->words['filter_by_section']}</h4>
					<ul>
						<foreach loop="$contentTypes as $type">
							<li <if test="$this->request['contentType'] == $type">class='active'</if>>
								<a href='{parse url="app=core&amp;module=search&amp;do=followed&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;contentType={$type}" base="public"}'>{$this->lang->words['followed_type__' . $type]}</a>
							</li>
						</foreach>
					</ul>
				</if>
			</div>
		</div>
	</div>
	<form action="{parse url="app=core&amp;module=search&amp;do=manageFollowed&amp;search_app={$this->request['search_app']}&amp;contentType={$this->request['contentType']}" base="public"}" id="checkBoxForm" method="post">
	<input type="hidden" name="secure_key" value="{$this->member->form_hash}" />
	
	<div class='right clearfix'>
		<a href='#' id='toggle_notification' class='ipsButton_secondary'>{$this->lang->words['display_edit_options']}</a>
	</div>
	<br /><br />
	
	<div class='ipsLayout_content'>
		<h2 class='maintitle'>
			<span class='right'>
				<input type='checkbox' id='checkAllLikes' class='input_check toggle_notify_on' title='{$this->lang->words['search_select_all']}' value='1' />
			</span>
			<if test="searchedApp:|:$this->request['search_app']">{IPSLib::getAppTitle( $this->request['search_app'] )}<else />{$this->lang->words['search_all']}</if>
		</h2>
		<div>
			<if test="NPTotal:|:$total">
				{$results}
				
				<if test="hasLikeForMod:|:count($results)">
					
					<div class='moderation_bar rounded with_action toggle_notify_on'>
						<select name='modaction' class='input_select'>
							<option value=''>{$this->lang->words['like_mod__chose']}</option>
							<option value='delete'>{$this->lang->words['like_mod__delete']}</option>
							<option value='change-donotify'>{$this->lang->words['like_mod__change_donotify']}</option>
							<option value='change-donotnotify'>{$this->lang->words['like_mod__change_nonotify']}</option>
							<option value='change-immediate'>{$this->lang->words['like_mod__change_immediate']}</option>
							<option value='change-offline'>{$this->lang->words['like_mod__change_offline']}</option>
							<if test="forumsDigests:|:$this->request['search_app'] == 'forums'">
								<option value='change-daily'>{$this->lang->words['like_mod__change_daily']}</option>
								<option value='change-weekly'>{$this->lang->words['like_mod__change_weekly']}</option>
							</if>
							<option value='change-anon'>{$this->lang->words['like_mod__change_anon']}</option>
							<option value='change-noanon'>{$this->lang->words['like_mod__change_noanon']}</option>
						</select>
							
						<input type="submit" class="input_submit alt" value="{$this->lang->words['update_selected']}" />
					</div>
				</if>
				<br />
				{$pagination}
			<else />
				<p class='no_messages'>{$this->lang->words['followed_content_none']}</p>
			</if>
		</div>
	</div>
	
	</form>
</div>
<br class='clear' />
<script type='text/javascript'>
	ipb.global.registerCheckAll('checkAllLikes', 'checkall');
	
	if( $('toggle_notification') ){
		$('toggle_notification').observe( 'click', function(e){
			Event.stop(e);
			$('checkBoxForm').toggleClassName('show_notify');
		});
	}
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: forumAdvancedSearchFilters
//===========================================================================
function forumAdvancedSearchFilters($forums, $archivedPostCount=0, $topic) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<fieldset class='{parse striping="search"}'>
	<span class='search_msg'>
		{$this->lang->words['s_forum_desc']}
	</span>
	<ul class='ipsForm_horizontal'>
		<if test="hasArchives:|:$archivedPostCount > 0">
			<li class='ipsField clear'>
				<label class='ipsField_title' for='forums_display'>{$this->lang->words['fs_search_type_title']}</label>
				<p class='ipsField_content'>
					<input type='radio' name='search_app_filters[forums][liveOrArchive]' value='live' checked="checked" /> {$this->lang->words['fs_search_type_live']}&nbsp;&nbsp;&nbsp;
					<input type='radio' name='search_app_filters[forums][liveOrArchive]' value='archive' /> {$this->lang->words['fs_search_type_archive']}
				</p>
			</li>
		</if>
		<li class='ipsField ipsField_select clear'>
			<if test="is_null( $topic )">
				<label class='ipsField_title' for='forums_filter'>{$this->lang->words['find_forum']}</label>
				<p class='ipsField_content'>
					<select name='search_app_filters[forums][forums][]' class='input input_select' size='6' multiple='multiple'>
						{$forums}
					</select>
				</p>
			<else />
				<input type='hidden' name='cType' value='topic' />
				<input type='hidden' name='cId' value='{$topic['tid']}' />
				<label class='ipsField_title' for='topic_checkbox'>{$this->lang->words['find_topic']}</label>
				<p class='ipsField_content'>
					{$topic['title']}
				</p>
			</if>
		</li>
		<li class='ipsField clear'>
			<label class='ipsField_title' for='forums_display'>{$this->lang->words['s_forum_display']}</label>
			<p class='ipsField_content'>
				<input type='radio' name='search_app_filters[forums][noPreview]' value='0' /> {$this->lang->words['s_forum_asposts']}&nbsp;&nbsp;&nbsp;
				<input type='radio' name='search_app_filters[forums][noPreview]' value='1' checked="checked" /> {$this->lang->words['s_forum_astopics']}
			</p>
		</li>
		<li class='ipsField clear'>
			<label class='ipsField_title' for='f_p_data'>{$this->lang->words['s_forum_stuff']}</label>
			<p class='ipsField_content'>
				{parse expression="sprintf( $this->lang->words['s_forum_stuff_2'], "<input id='f_p_count' type='text' name='search_app_filters[forums][pCount]' class='input_text' style='vertical-align: middle; width:40px' size='5' value='' />", "<input id='f_p_views' type='text' name='search_app_filters[forums][pViews]' class='input_text' style='vertical-align: middle; width:40px' size='5' value='' />")"}
			</p>
		</li>
	</ul>
</fieldset>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: forumsVncFilters
//===========================================================================
function forumsVncFilters($data, $currentPrefs) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>
	{$this->lang->words['vnc_filters_header']}
</h3>
<div class='fixed_inner ipsBox ipsLayout clearfix'>
	<div class='ipsLayout_content ipsBox_container search_filter_container'>
		<div id="vnc_filter_popup_close"><a class='input_submit' id='save_vnc_filters' href='#'>{$this->lang->words['save_vnc_filters']}</a> &nbsp;&nbsp;{$this->lang->words['or']}&nbsp;&nbsp;<a href='#' id='cancel_vnc_filters' class='cancel'>{$this->lang->words['cancel']}</a></div>
		<ul class='block_list'>
			<li class='clickable<if test="noFiltersSet:|:$currentPrefs == null OR !count($currentPrefs)"> active</if>' id='forum_all'><span><strong>{$this->lang->words['vnc_filter_showall_forums']}</strong></span><input type='hidden' name='forum_all' id='hf_all' value='<if test="noFiltersSet:|:$currentPrefs == null OR !count($currentPrefs)">1</if>' /></li>
			<foreach loop="forumlist:$data as $_data">
				<if test="hasTitle:|: ! empty( $_data['important'] )">
					<li><span class='heading'>{$_data['title']}</span></li>
				<else />
					<li class='clickable<if test="hasFiltersSet:|:count($currentPrefs) AND in_array($_data['id'], $currentPrefs)"> active</if>' id='forum_{$_data['id']}'><if test="depth:|:!empty($_data['depth'])"></if><span>{parse expression="str_repeat( '&nbsp;&nbsp;&nbsp;&nbsp;', $_data['depth'])"}{$_data['title']}</span><input type='hidden' name='forum_{$_data['id']}' id='hf_{$_data['id']}' value='<if test="hasFiltersSet:|:count($currentPrefs) AND in_array($_data['id'], $currentPrefs)">1<else />0</if>' /></li>
				</if>
			</foreach>
		</ul>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: helpSearchResult
//===========================================================================
function helpSearchResult($r, $resultAsTitle=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>
$st = IPSSearchRegistry::get('in.search_higlight');
</php>
<div class='result_info'>
	<h3><a href='{parse url="app=core&amp;module=help&amp;do=01&amp;HID={$r['type_id_2']}&amp;hl={$st}" base="public"}'>{$r['content_title']}</a></h3>
	<if test="showHelpContent:|:!$resultAsTitle">
	<p>
		{$r['content']}
	</p>
	</if>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: memberCommentsSearchResult
//===========================================================================
function memberCommentsSearchResult($r, $resultAsTitle=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<span class='icon'>
	{parse expression="IPSMember::buildPhotoTag( $r['status_author'], 'small' )"}
</span>
<div class='result_info'>
	<h3>
		{parse template="userHoverCard" group="global" params="$r['status_author']"}
		<if test="isUpdate:|: $r['status_member_id'] != $r['status_author_id']">
			&rarr;
			{parse template="userHoverCard" group="global" params="$r['status_member']"}
		</if>
	</h3>
	<span class='desc breadcrumb'>
		<a href='{parse url="app=members&amp;module=profile&amp;section=status&amp;type=single&amp;status_id={$r['status_id']}" seotitle="true" template="members_status_single" base="public"}'>{$this->lang->words['results_pf_comment']}</a>, {parse date="$r['status_date']" format="short"}
	</span>
	<p>{$r['status_content']}</p>
	<div id="statusFeedback-{$r['status_id']}" class='status_feedback'>
		<if test="$r['status_replies'] AND count( $r['replies'] )">
			<if test="hasMore:|:$r['status_replies'] > 3">
				<div class='status_mini_wrap row2 altrow' id='statusMoreWrap-{$r['status_id']}'>
					<img src="{$this->settings['img_url']}/comments.png" alt="" /> &nbsp;<a href="#" id="statusMore-{$r['status_id']}" class='__showAll __x{$r['status_id']}'>{parse expression="sprintf( $this->lang->words['status_show_all_x'], $r['status_replies'] )"}</a>
				</div>
			</if>
			<ul id='statusReplies-{$r['status_id']}' class='ipsList_withtinyphoto clear'>
				{parse template="statusReplies" group="profile" params="$r['replies'], 1"}
			</ul>
		</if>
		<div id='statusReplyBlank-{$r['status_id']}'></div>
		<div class='status_mini_wrap row2 altrow' id='statusMaxWrap-{$r['status_id']}' <if test="maxReplies:|:$r['status_replies'] < $this->settings['su_max_replies']">style='display:none'</if>>
			<img src="{$this->settings['img_url']}/locked_replies.png" title="{$this->lang->words['status_too_many_replies']}" alt='x' /> {$this->lang->words['status_too_many_replies']}
		</div>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: memberSearchResult
//===========================================================================
function memberSearchResult($r, $resultAsTitle=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<span class='icon'>
	{parse template="userSmallPhoto" group="global" params="array_merge( $r, array( '_customClass' => 'ipsUserPhoto_medium' ) )"}
</span>
<div class='result_info'>
	<h3>{parse template="userHoverCard" group="global" params="$r"}</h3>
	<span class='desc lighter breadcrumb'>
		{$this->lang->words['member_joined']} {parse date="$r['joined']" format="short"}<br />
		{IPSMember::makeNameFormatted( $r['group'], $r['member_group_id'] )} &middot; {parse format_number="$r['posts']"} {$this->lang->words['member_posts']}
	</span>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: newContentView
//===========================================================================
function newContentView($results, $pagination, $total, $sortDropDown, $sortIn, $dateCutSet=0) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="search"}
<input type='hidden' name="usedInJsLater" id="urlString" value="{parse expression="base64_encode( $this->registry->output->buildUrl( "app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}", "public" ) )"}" />
<h1 class='ipsType_pagetitle'>{$this->lang->words['new_content']}</h1>
<div class='ipsType_pagedesc'>
	{$this->lang->words['new_content_desc']}
</div>
<br />
<div class='ipsLayout ipsLayout_withleft clearfix'>
	<div class='ipsLayout_left'>
		<div class='ipsBox'>
			<div class='ipsBox_container ipsSideMenu'>
				<h4>{$this->lang->words['filter_by_app']}</h4>
				<ul>
					<if test="IPSLib::appIsSearchable( 'forums', 'vnc' ) || IPSLib::appIsSearchable( 'forums', 'active' )">
						<li <if test="forumsTab:|:$this->request['search_app'] == 'forums'">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app=forums&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;period={$this->request['period']}&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}" base="public"}'>{IPSLib::getAppTitle( 'forums' )}</a></li>
					</if>
					<if test="IPSLib::appIsSearchable( 'members', 'vnc' ) || IPSLib::appIsSearchable( 'members', 'active' )">
						<li <if test="membersTab:|:$this->request['search_app'] == 'members'">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app=members&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;period={$this->request['period']}&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}" base="public"}'>{IPSLib::getAppTitle( 'members' )}</a></li>
					</if>
					<if test="IPSLib::appIsSearchable( 'core', 'vnc' ) || IPSLib::appIsSearchable( 'core', 'active' )">
						<li <if test="helpTab:|:$this->request['search_app'] == 'core'">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app=core&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;period={$this->request['period']}&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}" base="public"}'>{IPSLib::getAppTitle( 'core' )}</a></li>
					</if>
					<foreach loop="apps:$this->registry->getApplications() as $app">
						<if test="appIsSearchable:|:(IPSLib::appIsSearchable( $app['app_directory'], 'vnc' ) || IPSLib::appIsSearchable( $app['app_directory'], 'active' ) ) AND !in_array( $app['app_directory'], array('core','forums','members') )">
							<li <if test="appIsSearched:|:$this->request['search_app'] == $app['app_directory']">class='active'</if>>
								<a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$app['app_directory']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;period={$this->request['period']}&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}" base="public"}'>
									{IPSLib::getAppTitle( $app['app_directory'] )}
								</a>
							</li>
				 		</if>
					</foreach>
				</ul>
			
				<if test="is_array($sortIn) AND count( $sortIn )">
					<h4>{$this->lang->words['filter_by_section']}</h4>
					<ul>
						<foreach loop="$sortIn as $id => $_data">
							<li <if test="$this->request['search_app_filters'][$this->request['search_app']]['searchInKey'] == $_data[0]">class='active'</if>>
								<a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$_data['0']}&amp;period={$this->request['period']}&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}" base="public"}'>{$_data[1]}</a>
							</li>
						</foreach>
					</ul>
				</if>
			
				<h4>{$this->lang->words['filter_by_time']}</h4>
				<ul>
					<if test="hazVNC:|:IPSLib::appIsSearchable( IPSSearchRegistry::get('in.search_app'), 'vncWithUnreadContent' ) AND $this->memberData['member_id']">
						<li <if test="$this->request['period']=='unread'">class='active'</if>>
							<a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;change=1&amp;period=unread&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}" base="public"}'>{$this->lang->words['filter_not_read']}</a>
						</li>
					</if>
					<if test="hazMember:|:$this->memberData['member_id']">
						<li <if test="$this->request['period']=='lastvisit'">class='active'</if>>
							<a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;change=1&amp;period=lastvisit&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}" base="public"}'>{$this->lang->words['filter_new_visit']}</a>
						</li>
					</if>
					<li <if test="$this->request['period']=='today'">class='active'</if>>
						<a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;change=1&amp;period=today&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}" base="public"}'>{$this->lang->words['actperiod_today']}</a>
					</li>
					<li <if test="$this->request['period']=='week'">class='active'</if>>
						<a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;change=1&amp;period=week&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}" base="public"}'>{$this->lang->words['actperiod_week']}</a>
					</li>
					<li <if test="$this->request['period']=='weeks'">class='active'</if>>
						<a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;change=1&amp;period=weeks&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}" base="public"}'>{$this->lang->words['actperiod_weeks']}</a>
					</li>
					<li <if test="$this->request['period']=='month'">class='active'</if>>
						<a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;change=1&amp;period=month&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}" base="public"}'>{$this->lang->words['actperiod_month']}</a>
					</li>
					<li <if test="$this->request['period']=='months'">class='active'</if>>
						<a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;change=1&amp;period=months&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}" base="public"}'>{$this->lang->words['actperiod_months']}</a>
					</li>
					<li <if test="$this->request['period']=='year'">class='active'</if>>
						<a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;change=1&amp;period=year&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}" base="public"}'>{$this->lang->words['actperiod_year']}</a>
					</li>
				</ul>
				
				<if test="canFollowFilter:|:IPSLib::appIsSearchable( $this->request['search_app'], 'vncWithFollowFilter' ) AND $this->memberData['member_id']">
					<h4>{$this->lang->words['filter_by_other']}</h4>
					<ul>
						<if test="checked:|:$this->request['followedItemsOnly']">
							<li class="active">
								<a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;period={$this->request['period']}&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly=0" base="public"}'>{$this->lang->words['filter_i_follow']}</a>
							</li>
						<else />
							<li>
								<a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;period={$this->request['period']}&amp;userMode={$this->request['userMode']}&amp;followedItemsOnly=1" base="public"}'>{$this->lang->words['filter_i_follow']}</a>
							</li>
						</if>
						<li <if test="$this->request['userMode'] == 'all'">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;period={$this->request['period']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}&amp;userMode=<if test="$this->request['userMode'] != 'all'">all</if>" base="public"}'>{$this->lang->words['vnc_topics_and_posts']}</a></li>
						<li <if test="$this->request['userMode'] == 'title'">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app={$this->request['search_app']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;period={$this->request['period']}&amp;followedItemsOnly={$this->request['followedItemsOnly']}&amp;userMode=<if test="$this->request['userMode'] != 'title'">title</if>" base="public"}'>{$this->lang->words['vnc_topics_only']}</a></li>
						<if test="vncFilterForumsOnly:|:$this->request['search_app'] == 'forums'"><li <if test="hasFilters:|:IPSSearchRegistry::get('forums.vncForumFilters') != null">class='active'</if>><a href='' id='vncForumFilter'>{$this->lang->words['vnc_filter_by_forum']}</a></li></if>
					</ul>
				</if>
			</div>
		</div>
	</div>
	<div class='ipsLayout_content'>
		<div class='clearfix'>
			{$pagination}
		</div>
		<h2 class='maintitle'>
			<if test="searchismod:|:$this->memberData['g_is_supmod'] == 1 && IPSSearchRegistry::get('config.can_moderate_results')">
				<span class='right'>
					<input type='checkbox' id='tmod_all' class='input_check' title='{$this->lang->words['search_select_all']}' value='1' />
				</span>
			</if>
			{IPSLib::getAppTitle( $this->request['search_app'] )}
		</h2>
		<div>
			<if test="NPTotal:|:$total">
				{$results}
				<br />
				{$pagination}
			<else />
				<p class='no_messages'>{$this->lang->words['new_content_none']}</p>
			</if>
		</div>
	</div>
</div>
<br class='clear' />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: searchAdvancedForm
//===========================================================================
function searchAdvancedForm($filters='', $msg='', $current_app, $removed_search_terms=array(), $isFT=false, $canTag=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<style type='text/css'>
 	@import url('{$this->settings['public_dir']}style_css/{$this->registry->output->skin['_csscacheid']}/calendar_select.css');
</style>
<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/calendar_date_select/calendar_date_select.js'></script>
{parse js_module="search"}
<h1 class='ipsType_pagetitle'>{$this->lang->words['search']}</h1>
<if test="searchTermsRemoved:|:is_array( $removed_search_terms ) && count( $removed_search_terms )">
<br />
<p class='message error'>{$this->lang->words['removed_search_terms']} <strong>{parse expression="implode( ',', $removed_search_terms )"}</strong></p>
</if>
<if test="searchError:|:$msg">
<br />
<p class='message error'>{$msg}</p>
</if>
<br />
<div class='ipsBox' id='main_search_form'>
	<form action="{parse url="app=core&amp;module=search&amp;section=search&amp;do=search" base="public"}&amp;fromsearch=1" method="post" id='search-box' >
		<input type='hidden' name='search_app' id='search_app' value='{$this->request['search_app']}' />
		<fieldset id='' class='ipsBox_container ipsPad'>
			<ul class='ipsForm_horizontal'>
				<li class='ipsField'>
					<label for='query' class='ipsField_title'>{$this->lang->words['find_words']}</label>
					<p class='ipsField_content'>
						<input type='text' class='input_text' name='search_term' id='query' value='{$this->request['search_term']}' size='50' /><br />
						<span class='desc lighter'>{$this->lang->words['s_andor_quotes']}</span>
					</p>
				</li>
				<if test="count($filters)">
					<li class='ipsField app_chooser'>
						<label class='ipsField_title' for=''>{$this->lang->words['search_section']}</label>
						<div class='ipsField_content'>
							<ul id='sapps'>
								<if test="IPSLib::appIsSearchable( 'forums', 'search' )">
									<li style='display: inline-block;' class='search_app' id='sapp_forums'>
										<input type='radio' data-allowtags='{$canTag[ 'forums' ]}' name='search_app' value='forums' id='radio_forums' <if test="$this->request['search_app'] == 'forums'">checked="checked"</if> /> <label for='radio_forums'>{IPSLib::getAppTitle( 'forums' )}</label>
									</li>
								</if>
								<if test="IPSLib::appIsSearchable( 'members', 'search' )">
									<li style='display: inline-block;' class='search_app' id='sapp_members'>
										<input type='radio' data-allowtags='{$canTag[ 'members' ]}' name='search_app' value='members' id='radio_members' <if test="$this->request['search_app'] == 'members'">checked="checked"</if> /> <label for='radio_members'>{IPSLib::getAppTitle( 'members' )}</label>
									</li>
								</if>
								<if test="IPSLib::appIsSearchable( 'core', 'search' )">
									<li style='display: inline-block;' class='search_app' id='sapp_core'>
										<input type='radio' data-allowtags='{$canTag[ 'core' ]}' name='search_app' value='core' id='radio_core' <if test="$this->request['search_app'] == 'core'">checked="checked"</if> /> <label for='radio_core'>{IPSLib::getAppTitle( 'core' )}</label>
									</li>
								</if>
			
								<foreach loop="appLoop:$this->registry->getApplications() as $app => $data">
									<if test="IPSLib::appIsSearchable( $app, 'search' ) AND !in_array( $app, array( 'forums', 'members', 'core' ) )">
										<li style='display: inline-block;' class='search_app' id='sapp_{$app}'>
											<input type='radio' data-allowtags='{$canTag[ $app ]}' name='search_app' value='{$app}' id='radio_{$app}' <if test="$app == $this->request['search_app']">checked="checked"</if> /> <label for='radio_{$app}'>{IPSLib::getAppTitle( $app )}</label>
										</li>
									</if>
								</foreach>
							</ul>
						</div>
					</li>
				</if>
			</ul>
		</fieldset>
		
		<fieldset id='other_filters' class='ipsBox_container ipsPad'>
			<ul class='ipsForm_horizontal'>
				<li class='ipsField ipsField_select clear'>
					<label for='andor_type' class='ipsField_title'><strong>{$this->lang->words['matchlabel']}</strong></label>
					<p class='ipsField_content'>
						<if test="isFullText:|:$this->settings['use_fulltext'] AND $this->DB->checkBooleanFulltextSupport()">
							<select name="andor_type" id="andor_type">
								<option value="and" <if test="$this->settings['s_andor_type'] == 'and'">selected='selected'</if>>{$this->lang->words['s_andor_and']}</option>
								<option value="or" <if test="$this->settings['s_andor_type'] == 'or'">selected='selected'</if>>{$this->lang->words['s_andor_or']}</option>
							</select>
							&nbsp;&nbsp;
						</if>
						
						<select name="search_content" id="search_content">
							<option value="both">{$this->lang->words['search_both_types']}</option>
							<option value="titles">{$this->lang->words['search_titles_types']}</option>
							<option value="content">{$this->lang->words['search_content_types']}</option>
						</select>
					</p>
				</li>
				<if test="tagyouareit:|:$canTag[ $current_app ]">
					<li class='ipsField clear' id='tag_row'>
						<label for='tags' class='ipsField_title'>{$this->lang->words['find_by_tags']}</label>
						<p class='ipsField_content'>
							<input type='text' class='input_text input' name='search_tags' id='tags' value='{$this->request['find_by_tags']}' size='50' />
							<br />
							<span class='desc lighter'>{$this->lang->words['find_by_tags_desc']}</span>
						</p>
					</li>
				</if>
				<li class='ipsField clear'>
					<label for='author' class='ipsField_title'>{$this->lang->words['find_author']}</label>
					<p class='ipsField_content'>
						<input type='text' class='input_text input' name='search_author' id='author' value='{$this->request['search_author']}' size='50' />
					</p>
				</li>
				<li class='ipsField clear'>
					<label for='date_start' class='ipsField_title'>{$this->lang->words['find_date']}</label>
					<p class='ipsField_content'>
						<input type='text' class='input_text' name='search_date_start' id='date_start' value='{$this->request['_search_date_start']}' /><img src='{$this->settings['img_url']}/date.png' alt='' id='date_start_icon' style='cursor: pointer' /> &nbsp;
						<strong>{$this->lang->words['to']}</strong> &nbsp;<input type='text' class='input_text' name='search_date_end' id='date_end' value='{$this->request['_search_date_end']}' /><img src='{$this->settings['img_url']}/date.png' alt='' id='date_end_icon' style='cursor: pointer' />
					</p>
				</li>
				
			</ul>
		</fieldset>
		<if test="count($filters)">
			<foreach loop="$filters as $app => $data">
				<div id='app_filter_{$app}' class='ipsBox_container ipsPad' style='display: none'>
					<if test="!empty($data['html'])">
						{$data['html']}
					</if>
					<if test="count($data['sortDropDown'])">
						<fieldset class='{parse striping="search"}'>
							<if test="is_array($data['sortDropIn']) AND count( $data['sortDropIn'] )">
								<foreach loop="$data['sortDropIn'] as $id => $_data">
									<ul class='ipsForm_horizontal'>
										<li class='ipsField ipsField_select'>
											<label class='ipsField_title' for='search_by_{$_data[0]}'><if test="! $id">{$this->lang->words['s_member_sin']}<else />&nbsp;</if></label>
											<p class='ipsField_content'>
												<input type='radio' name='search_app_filters[$app][searchInKey]' value='{$_data[0]}' <if test="$this->request['search_app_filters'][$app]['searchInKey'] == $_data[0] || ( ! $this->request['search_app_filters'][$app]['searchInKey'] AND ! $id )">checked="checked"</if> /> $_data[1]
												<select name='search_app_filters[$app][{$_data[0]}][sortKey]' class='input_select' style='width:auto'>
												<foreach loop="$data['sortDropDown'][$_data[0]] as $k => $l">
													<option value='$k' <if test="$this->request['search_app_filters'][$app][$_data[0]]['sortKey'.$_data[0]] == $k">selected='selected'</if>>{$l}</option>
												</foreach>
												</select>
												<select name='search_app_filters[$app][{$_data[0]}][sortDir]' class='input_select' style='width:auto'>
													<option value='0' <if test="!$this->request['search_app_filters'][$app][$_data[0]]['sortDir']">selected='selected'</if>>{$this->lang->words['s_search_type_o_0']}</option>
													<option value='1' <if test="$this->request['search_app_filters'][$app][$_data[0]]['sortDir']">selected='selected'</if>>{$this->lang->words['s_search_type_o_1']}</option>
												</select>
											</p>
										</li>
									</ul>
								</foreach>
							<else />
								<ul class='ipsForm_horizontal'>
									<li class='ipsField ipsField_select'>
										<label class='ipsField_title' for='search_by'>{$this->lang->words['s_search_type']}</label>
										<p class='ipsField_content'>
											<select name='search_app_filters[$app][sortKey]' id='search_sort_by_{$app}' class='input_select' style='width:auto'>
											<foreach loop="$data['sortDropDown'] as $k => $l">
												<option value='$k' <if test="$this->request['search_app_filters'][$app]['sortKey'] == $k">selected='selected'</if>>{$l}</option>
											</foreach>
											</select>
											<select name='search_app_filters[$app][sortDir]' id='search_sort_order_{$app}' class='input_select' style='width:auto'>
												<option value='0' <if test="!$this->request['search_app_filters'][$app]['sortDir']">selected='selected'</if>>{$this->lang->words['s_search_type_o_0']}</option>
												<option value='1' <if test="$this->request['search_app_filters'][$app]['sortDir']">selected='selected'</if>>{$this->lang->words['s_search_type_o_1']}</option>
											</select>
										</p>
									</li>
								</ul>
							</if>
						</fieldset>
					</if>
				</div>
			</foreach>
		</if>
		<fieldset class='submit'>
			<input type='submit' name='submit' class='input_submit' value='{$this->lang->words['do_search']}'> {$this->lang->words['or']} <a href='{$this->settings['board_url']}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
		</fieldset>
	</form>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: searchResults
//===========================================================================
function searchResults($results, $titlesOnly) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse striping="searchStripe" classes="row1,row2"}
<div id='search_results'>
	<ol>
		<if test="hasResults:|:is_array($results) && count($results)">
			<foreach loop="results:$results as $result">
				<if test="subResult:|:$result['sub']">
					<li class='{parse striping="searchStripe"} sub clearfix clear'>
						{$result['html']}
					</li>
				<else />
					<li class='{parse striping="searchStripe"} clearfix clear'>
						{$result['html']}
					</li>
				</if>
			</foreach>
		</if>
	</ol>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: searchResultsAsForum
//===========================================================================
function searchResultsAsForum($results, $titlesOnly) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="forums"}
<if test="asTawpiks:|:$titlesOnly">
<script type='text/javascript' src='{$this->settings['public_dir']}js/ips.forums.js'></script>
<table class='ipb_table topic_list' id='forum_table'>
</if>
	<if test="count($results)">
		<if test="asPostsStart:|:!$titlesOnly"><div class='ipsBox'></if>
		<foreach loop="NCresultsAsForum:$results as $result">
			{$result['html']}
		</foreach>
		<if test="asPostsEnd:|:!$titlesOnly"></div></if>
	</if>
<if test="asTawpiks2:|:$titlesOnly">
	</table>
	<if test="isAdminBottom:|:$this->memberData['g_is_supmod'] && $this->request['search_app_filters']['forums']['liveOrArchive'] != 'archive'">
		<div id='topic_mod' class='moderation_bar rounded with_action clear'>
			<form id='modform' method="post" action="{parse url="" base="public"}">
				<fieldset>
					<input type="hidden" name="app" value="forums" />
					<input type="hidden" name="module" value="moderate" />
					<input type="hidden" name="section" value="moderate" />
					<input type="hidden" name="do" value="topicchoice" />
					<input type="hidden" name="st" value="{$this->request['st']}" />
					<input type="hidden" name="auth_key" value="{$this->member->form_hash}" />
					<input type='hidden' name='fromSearch' value='1' />
					<input type='hidden' name='returnUrl' id='returnUrl' value='{$this->request['returnUrl']}' />
					<input type="hidden" name="modfilter" value="{$this->request['modfilter']}" />
					<input type="hidden" value="{$this->request['selectedtids']}" id='selectedtids' name="selectedtids" />
				
					<select name="tact" id='mod_tact'>
						<option value='approve'>{$this->lang->words['cpt_approve_f']}</option>
						<option value='pin'>{$this->lang->words['cpt_pin_f']}</option>
						<option value='unpin'>{$this->lang->words['cpt_unpin_f']}</option>
						<option value='open'>{$this->lang->words['cpt_open_f']}</option>
						<option value='close'>{$this->lang->words['cpt_close_f']}</option>
						<option value='move'>{$this->lang->words['cpt_move_f']}</option>
						<option value='merge'>{$this->lang->words['cpt_merge_f']}</option>
						<option value='delete'>{$this->lang->words['cpt_hide_f']}</option>
						<option value='sundelete'>{$this->lang->words['cpt_unhide_f']}</option>
						<option value='deletedo'>{$this->lang->words['cpt_delete_f']}</option>
					</select>&nbsp;
					<input type="submit" name="gobutton" value="{$this->lang->words['f_go']}" class="input_submit alt" id='mod_submit' />
				</fieldset>
			</form>
			<script type='text/javascript'>
				/* Set return string */
				$('returnUrl').value = $('urlString').value;
				$('modform').observe('submit', ipb.forums.submitModForm);
				$('mod_tact').observe('change', ipb.forums.updateTopicModButton);
			</script>
		</div>
	</if>
<else />
	<script type='text/javascript' src='{$this->settings['public_dir']}js/ips.topic.js'></script>
	<script type="text/javascript">
		ipb.topic.inSection = 'searchview';
	</script>
	<if test="isAdmin:|:$this->memberData['g_is_supmod'] && $this->request['search_app_filters']['forums']['liveOrArchive'] != 'archive'">
	<div id='topic_mod_2' class='moderation_bar rounded'>
		<form method="post" id="modform" name="modform" action="{parse url="" base="public"}">
			<fieldset>
				<input type="hidden" name="app" value="forums" />
	 			<input type="hidden" name="module" value="moderate" />
	 			<input type="hidden" name="section" value="moderate" />
	 			<input type="hidden" name="do" value="postchoice" />
	 			<input type="hidden" name="auth_key" value="{$this->member->form_hash}" />
	 			<input type="hidden" name="st" value="{$this->request['st']}" />
	 			<input type="hidden" value="{$this->request['selectedpids']}" name="selectedpidsJS" id='selectedpidsJS' />
				<input type='hidden' name='fromSearch' value='1' />
				<input type='hidden' name='returnUrl' id='returnUrl' value='{$this->request['returnUrl']}' />
				<select name="tact" class="input_select" id='topic_moderation'>
					<option value='approve'>{$this->lang->words['cpt_approve']}</option>
					<option value='delete'>{$this->lang->words['cpt_hide']}</option>
					<option value='sundelete'>{$this->lang->words['cpt_undelete']}</option>
					<option value='deletedo'>{$this->lang->words['cpt_delete']}</option>
					<option value='merge'>{$this->lang->words['cpt_merge']}</option>
					<option value='split'>{$this->lang->words['cpt_split']}</option>
					<option value='move'>{$this->lang->words['cpt_move']}</option>
				</select>&nbsp;
				<input type="submit" value="{$this->lang->words['jmp_go']}" class="input_submit alt" />
			</fieldset>
		</form>
		
		<script type='text/javascript'>
			/* Set return string */
			$('returnUrl').value = $('urlString').value;
			$('modform').observe('submit', ipb.topic.submitPostModeration );
		</script>
	</div>
	</if>
	<if test="disablelightbox:|:!$this->settings['disable_lightbox']">
		{parse template="include_lightbox" group="global" params=""}
	</if>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: searchResultsWrapper
//===========================================================================
function searchResultsWrapper($results, $sortDropDown, $sortIn, $pagination, $total, $showing, $search_term, $url_string, $current_key, $removed_search_terms=array(), $limited=0, $wasLimited=false, $search_tags) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<input type='hidden' name="usedInJsLater" id="urlString" value="{parse expression="base64_encode( $this->registry->output->buildUrl( "{$url_string}&amp;search_app={$current_key}", "public" ) )"}" />
<h1 class='ipsType_pagetitle'>{$this->lang->words['search_results']}</h1>
<div class='ipsType_pagedesc'>
	<if test="hasSearchResults:|:$total > 0 AND $search_term != ''">
		<if test="hasSearchResultsCut:|:$limited AND $wasLimited">
			{parse expression="sprintf( $this->lang->words['your_search_limited'], $search_term, $limited )"}
		<else />
			{$this->lang->words['your_search']} <em><strong>{$search_term}</strong></em> {$this->lang->words['your_search_returned']} <strong>{$total}</strong> {$this->lang->words['your_search_results']}
		</if>
	<else />
		<if test="hasSearchResults:|:$total > 0 AND $search_tags != ''">
			{parse expression="sprintf( $this->lang->words['tag_search_results'], $total, $search_tags )"}
		</if>
	</if>
</div>
<br />
<div class='ipsLayout ipsLayout_withleft clearfix'>
	<div class='ipsLayout_left'>
		<div class='ipsBox'>
			<div class='ipsBox_container ipsSideMenu'>
				<h4>{$this->lang->words['filter_by_app']}</h4>
				<ul>
					<if test="IPSLib::appIsSearchable( 'forums', 'search' )">
						<li <if test="forumsTab:|:$this->request['search_app'] == 'forums'">class='active'</if>><a href='{parse url="{$url_string}&amp;search_app=forums" base="public"}'>{IPSLib::getAppTitle( 'forums' )}</a></li>
					</if>
					<if test="IPSLib::appIsSearchable( 'members', 'search' )">
						<li <if test="membersTab:|:$this->request['search_app'] == 'members'">class='active'</if>><a href='{parse url="{$url_string}&amp;search_app=members" base="public"}'>{IPSLib::getAppTitle( 'members' )}</a></li>
					</if>
					<if test="IPSLib::appIsSearchable( 'core', 'search' )">
						<li <if test="helpTab:|:$this->request['search_app'] == 'core'">class='active'</if>><a href='{parse url="{$url_string}&amp;search_app=core" base="public"}'>{IPSLib::getAppTitle( 'core' )}</a></li>
					</if>
					<foreach loop="apps:$this->registry->getApplications() as $app">
						<if test="appIsSearchable:|:IPSLib::appIsSearchable( $app['app_directory'], 'search' ) AND !in_array( $app['app_directory'], array('core','forums','members') )">
							<li <if test="appIsSearched:|:$this->request['search_app'] == $app['app_directory']">class='active'</if>><a href='{parse url="{$url_string}&amp;search_app={$app['app_directory']}" base="public"}'>{IPSLib::getAppTitle( $app['app_directory'] )}</a></li>
				 		</if>
					</foreach>
				</ul>
			
				<if test="is_array($sortIn) AND count( $sortIn )">
					<h4>{$this->lang->words['filter_by_section']}</h4>
					<ul>
						<foreach loop="$sortIn as $id => $_data">
							<li <if test="$this->request['search_app_filters'][$this->request['search_app']]['searchInKey'] == $_data[0]">class='active'</if>>
								<a href='{parse url="{$url_string}&amp;search_app={$current_key}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$_data['0']}" base="public"}'>{$_data[1]}</a>
							</li>
						</foreach>
					</ul>
				</if>
			</div>
		</div>
	</div>
	<div class='ipsLayout_content'>		
		<div class='clearfix'>
			{$pagination}
		</div>
		<div class='maintitle ipsFilterbar'>
			<if test="searchismod:|:$this->memberData['g_is_supmod'] == 1 && IPSSearchRegistry::get('config.can_moderate_results')">
				<span class='right'>
					<input type='checkbox' id='tmod_all' class='input_check' title='{$this->lang->words['search_select_all']}' value='1' />
				</span>
			</if>		
			<if test="IPSSearchRegistry::get('config.contentTypes') AND is_array( IPSSearchRegistry::get('config.contentTypes') )">
				<if test="count($sortDropDown)">
					<span class='ipsType_small'>{$this->lang->words['sort_by']}</span>&nbsp;&nbsp;
					<if test="count($sortDropDown) <= 4">
						<ul class='ipsList_inline ipsType_smaller' style='display: inline'>
							<foreach loop="$sortDropDown as $k => $l">
								<li <if test="$this->request['search_app_filters'][$current_key][$this->request['search_app_filters'][$current_key]['searchInKey']]['sortKey'] == $k">class='active'</if>>
									<a href='{parse url="{$url_string}&amp;search_app={$current_key}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[$current_key][{$this->request['search_app_filters'][$current_key]['searchInKey']}][sortKey]={$k}&amp;search_app_filters[$current_key][{$this->request['search_app_filters'][$current_key]['searchInKey']}][sortDir]={$this->request['search_app_filters'][$current_key][$this->request['search_app_filters'][$current_key]['searchInKey']]['sortDir']}" base="public"}'>{$l}</a>
								</li>
							</foreach>
						</ul>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<else />
						<ul class='ipsList_inline ipsType_smaller' style='display: inline'>
							<li class='active'>
								<a href='#' id='search_sort' class='ipbmenu'>{$sortDropDown[ $this->request['search_app_filters'][$current_key][$this->request['search_app_filters'][$current_key]['searchInKey']]['sortKey'] ]} &nbsp;&nbsp;<span class='submenu_indicator'></span>&nbsp;</a>
							</li>
						</ul>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</if>
				</if>
				<span class='ipsType_small'>{$this->lang->words['order']}</span>&nbsp;&nbsp;
				<ul class='ipsList_inline ipsType_smaller' style='display: inline'>
					<li <if test="$this->request['search_app_filters'][$current_key][$this->request['search_app_filters'][$current_key]['searchInKey']]['sortDir'] == 0">class='active'</if>>
						<a href='{parse url="{$url_string}&amp;search_app={$current_key}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[$current_key][{$this->request['search_app_filters'][$current_key]['searchInKey']}][sortKey]={$this->request['search_app_filters'][$current_key][$this->request['search_app_filters'][$current_key]['searchInKey']]['sortKey']}&amp;search_app_filters[$current_key][{$this->request['search_app_filters'][$current_key]['searchInKey']}][sortDir]=0" base="public"}'>{$this->lang->words['s_search_type_o_0']}</a>
					</li>
					<li <if test="$this->request['search_app_filters'][$current_key][$this->request['search_app_filters'][$current_key]['searchInKey']]['sortDir'] == 1">class='active'</if>>
						<a href='{parse url="{$url_string}&amp;search_app={$current_key}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[$current_key][{$this->request['search_app_filters'][$current_key]['searchInKey']}][sortKey]={$this->request['search_app_filters'][$current_key][$this->request['search_app_filters'][$current_key]['searchInKey']]['sortKey']}&amp;search_app_filters[$current_key][{$this->request['search_app_filters'][$current_key]['searchInKey']}][sortDir]=1" base="public"}'>{$this->lang->words['s_search_type_o_1']}</a>
					</li>
				</ul>
			<else />
				<if test="count($sortDropDown)">
					<span class='ipsType_small'>{$this->lang->words['sort_by']}</span>&nbsp;&nbsp;
					<if test="count($sortDropDown) <= 4">
						<ul class='ipsList_inline ipsType_smaller' style='display: inline'>
							<foreach loop="$sortDropDown as $k => $l">
								<li <if test="$this->request['search_app_filters'][$current_key]['sortKey'] == $k">class='active'</if>>
									<a href='{parse url="{$url_string}&amp;search_app={$current_key}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[$current_key][sortKey]={$k}&amp;search_app_filters[$current_key][sortDir]={$this->request['search_app_filters'][$current_key]['sortDir']}" base="public"}'>{$l}</a>
								</li>
							</foreach>
						</ul>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<else />
						<ul class='ipsList_inline ipsType_smaller' style='display: inline'>
							<li class='active'>
								<a href='#' id='search_sort' class='ipbmenu'>{$sortDropDown[ $this->request['search_app_filters'][$current_key]['sortKey'] ]} &nbsp;&nbsp;<span class='submenu_indicator'></span>&nbsp;</a>
							</li>
						</ul>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</if>
				</if>
				<span class='ipsType_small'>{$this->lang->words['order']}</span>&nbsp;&nbsp;
				<ul class='ipsList_inline ipsType_smaller' style='display: inline'>
					<li <if test="$this->request['search_app_filters'][$current_key]['sortDir'] == 0">class='active'</if>>
						<a href='{parse url="{$url_string}&amp;search_app={$current_key}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[$current_key][sortKey]={$this->request['search_app_filters'][$current_key]['sortKey']}&amp;search_app_filters[$current_key][sortDir]=0" base="public"}'>{$this->lang->words['s_search_type_o_0']}</a>
					</li>
					<li <if test="$this->request['search_app_filters'][$current_key]['sortDir'] == 1">class='active'</if>>
						<a href='{parse url="{$url_string}&amp;search_app={$current_key}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[$current_key][sortKey]={$this->request['search_app_filters'][$current_key]['sortKey']}&amp;search_app_filters[$current_key][sortDir]=1" base="public"}'>{$this->lang->words['s_search_type_o_1']}</a>
					</li>
				</ul>
			</if>
		</div>
		<if test="count($sortDropDown) && count($sortDropDown) >= 5">
			<!-- The menu for changing the sort field -->
			<ul id='search_sort_menucontent' class='ipbmenu_content with_checks' style='display: none'>
				<if test="IPSSearchRegistry::get('config.contentTypes') AND is_array( IPSSearchRegistry::get('config.contentTypes') )">
					<foreach loop="$sortDropDown as $k => $l">
						<li <if test="$this->request['search_app_filters'][$current_key][$this->request['search_app_filters'][$current_key]['searchInKey']]['sortKey'] == $k">class='selected'</if>>
							<a href='{parse url="{$url_string}&amp;search_app={$current_key}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[$current_key][{$this->request['search_app_filters'][$current_key]['searchInKey']}][sortKey]={$k}&amp;search_app_filters[$current_key][{$this->request['search_app_filters'][$current_key]['searchInKey']}][sortDir]={$this->request['search_app_filters'][$current_key][$this->request['search_app_filters'][$current_key]['searchInKey']]['sortDir']}" base="public"}'>{$l}</a>
						</li>
					</foreach>
				<else />
					<foreach loop="$sortDropDown as $k => $l">
						<li <if test="$this->request['search_app_filters'][$current_key]['sortKey'] == $k">class='selected'</if>>
							<a href='{parse url="{$url_string}&amp;search_app={$current_key}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[$current_key][sortKey]={$k}&amp;search_app_filters[$current_key][sortDir]={$this->request['search_app_filters'][$current_key]['sortDir']}" base="public"}'>{$l}</a>
						</li>
					</foreach>
				</if>
			</ul>
		</if>
		<div class='ipsBox_container'>
			<if test="hasTotal:|:$total">
				{$results}
			<else />
				<p class='no_messages'>{$this->lang->words['no_results_found']}<if test="noResultsTerm:|:$search_term"> {$this->lang->words['no_results_found_for']} '{$search_term}'</if>.</p>
			</if>
		</div>
		<div class='clearfix'>
			{$pagination}
		</div>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: searchRowGenericFormat
//===========================================================================
function searchRowGenericFormat($r, $resultAsTitle=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='result_info'>
	<h3>{$r['content_title']}</h3>
	<if test="showGenericContent:|:!$resultAsTitle">
	<p>
		{$r['content']}
	</p>
	</if>
</div>
<if test="$r['updated'] OR $r['member_id']">
	<div class='result_details desc'>
		<ul>
			<if test="$r['updated']">
				<li>{parse date="$r['updated']" format="short"}</li>
			</if>
			<if test="$r['member_id']">
				<li>{$this->lang->words['search_by']} <a href='{parse url="showuser={$r['member_id']}" template="showuser" seotitle="{$r['members_seo_name']}" base="public"}'>{$r['members_display_name']}</a></li>
			</if>
		</ul>
	</div>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: topicPostSearchResultAsForum
//===========================================================================
function topicPostSearchResultAsForum($data, $resultAsTitle=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="whichWayToGo:|:$resultAsTitle">
	{parse template="asForumTopics" group="search" params="$data"}
<else />
	{parse template="asForumPosts" group="search" params="$data"}
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: userPostsView
//===========================================================================
function userPostsView($results, $pagination, $total, $member, $limited=0, $wasLimited=false, $beginTime=0, $sortIn=null, $sortDropDown=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>
	$datecut		= ( $this->settings['search_ucontent_days'] ) ? $this->registry->class_localization->getDate( time() - ( 86400 * intval( $this->settings['search_ucontent_days'] ) ), 'joined' ) : 0;
</php>
<h2 class='ipsType_pagetitle'>{parse expression="sprintf( $this->lang->words['s_participation_title'], $member['members_display_name'] )"}</h2>
<input type='hidden' name="usedInJsLater" id="urlString" value="{parse expression="base64_encode( $this->registry->output->buildUrl( "app=core&amp;module=search&amp;do=user_activity&amp;search_app={$this->request['search_app']}&amp;mid={$this->request['mid']}&amp;userMode={$this->request['userMode']}&amp;sid={$this->request['_sid']}", "public" ) )"}" />
<if test="NPhasResults:|:$total">
		<p class='ipsType_pagedesc'>
			{parse expression="sprintf( $this->lang->words['s_participation_msg'], $total, $member['members_display_name'] )"}
			<if test="$datecut">
				<span class='desc lighter'>{parse expression="sprintf( $this->lang->words['s_participation_range'], $datecut )"}</span>
			</if>
		</p>
	<br />
</if>
<div class='ipsLayout ipsLayout_withleft clearfix'>
	<div class='ipsLayout_left'>
		<div class='ipsBox'>
			<div class='ipsBox_container ipsSideMenu'>
				<h4>{$this->lang->words['filter_by_app']}</h4>
				<ul>
					<if test="IPSLib::appIsSearchable( 'forums', 'usercontent' )">
						<li <if test="forumsTab:|:$this->request['search_app'] == 'forums'">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;search_app=forums&amp;mid={$this->request['mid']}&amp;userMode={$this->request['userMode']}&amp;sid={$this->request['_sid']}" base="public"}'>{IPSLib::getAppTitle( 'forums' )}</a></li>
					</if>
					<if test="IPSLib::appIsSearchable( 'members', 'usercontent' )">
						<li <if test="membersTab:|:$this->request['search_app'] == 'members'">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;search_app=members&amp;mid={$this->request['mid']}&amp;userMode={$this->request['userMode']}&amp;sid={$this->request['_sid']}" base="public"}'>{IPSLib::getAppTitle( 'members' )}</a></li>
					</if>
					<if test="IPSLib::appIsSearchable( 'core', 'usercontent' )">
						<li <if test="helpTab:|:$this->request['search_app'] == 'core'">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;search_app=core&amp;mid={$this->request['mid']}&amp;userMode={$this->request['userMode']}&amp;sid={$this->request['_sid']}" base="public"}'>{IPSLib::getAppTitle( 'core' )}</a></li>
					</if>
					<foreach loop="apps:$this->registry->getApplications() as $app">
						<if test="appIsSearchable:|:IPSLib::appIsSearchable( $app['app_directory'], 'usercontent' ) AND !in_array( $app['app_directory'], array('core','forums','members') )">
							<li <if test="appIsSearched:|:$this->request['search_app'] == $app['app_directory']">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;search_app={$app['app_directory']}&amp;mid={$this->request['mid']}&amp;userMode={$this->request['userMode']}&amp;sid={$this->request['_sid']}" base="public"}'>{IPSLib::getAppTitle( $app['app_directory'] )}</a></li>
				 		</if>
					</foreach>
				</ul>
				
				<if test="$this->request['search_app'] == 'forums'">
					<h4>{$this->lang->words['userposts_morefilters']}</h4>
					<ul>
						<li <if test="$this->request['userMode'] == 'all'">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;search_app={$this->request['search_app']}&amp;mid={$this->request['mid']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;userMode=all" base="public"}'>{$this->lang->words['viewall_opt_dd']}</a></li>
						<li <if test="$this->request['userMode'] == 'title'">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;search_app={$this->request['search_app']}&amp;mid={$this->request['mid']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;userMode=title" base="public"}'>{$this->lang->words['viewallt_opt_dd']}</a></li>
						<li <if test="$this->request['userMode'] == 'content'">class='active'</if>><a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;search_app={$this->request['search_app']}&amp;mid={$this->request['mid']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;userMode=content" base="public"}'>{$this->lang->words['viewallp_opt_dd']}</a></li>
					</ul>
				</if>
				
				<if test="is_array($sortIn) AND count( $sortIn )">
					<h4>{$this->lang->words['filter_by_type']}</h4>
					<ul>
						<foreach loop="$sortIn as $id => $_data">
							<li <if test="$this->request['search_app_filters'][$this->request['search_app']]['searchInKey'] == $_data[0]">class='active'</if>>
								<a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;search_app={$this->request['search_app']}&amp;mid={$this->request['mid']}&amp;userMode={$this->request['userMode']}&amp;sid={$this->request['_sid']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$_data[0]}" base="public"}'>{$_data[1]}</a>
							</li>
						</foreach>
					</ul>
				</if>
			</div>
		</div>
	</div>
	<div class='ipsLayout_content'>
		<if test="$pagination">
			<div class='topic_controls'>
				{$pagination}
			</div>
			<br />
		</if>
		<div class='maintitle ipsFilterbar'>
			<if test="IPSSearchRegistry::get('config.contentTypes') AND is_array( IPSSearchRegistry::get('config.contentTypes') )">
				<if test="count($sortDropDown)">
				<span class='ipsType_small'>{$this->lang->words['sort_by']}</span>&nbsp;&nbsp;
					<if test="count($sortDropDown) <= 4">
						<ul class='ipsList_inline ipsType_smaller' style='display: inline'>
							<foreach loop="$sortDropDown as $k => $l">
								<li <if test="$this->request['search_app_filters'][$this->request['search_app']][$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']]['sortKey'] == $k">class='active'</if>>
									<a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;sid={$this->request['_sid']}&amp;mid={$this->request['mid']}&amp;search_app={$this->request['search_app']}&amp;userMode={$this->request['userMode']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[{$this->request['search_app']}][{$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}][sortKey]={$k}&amp;search_app_filters[{$this->request['search_app']}][{$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}][sortDir]={$this->request['search_app_filters'][$this->request['search_app']][$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']]['sortDir']}" base="public"}'>{$l}</a>
								</li>
							</foreach>
						</ul>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<else />
						<ul class='ipsList_inline ipsType_smaller' style='display: inline'>
							<li class='active'>
								<a href='#' id='search_sort' class='ipbmenu'>{$sortDropDown[ $this->request['search_app_filters'][$this->request['search_app']][$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']]['sortKey'] ]} &nbsp;&nbsp;<span class='submenu_indicator'></span>&nbsp;</a>
							</li>
						</ul>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</if>
				</if>
				<span class='ipsType_small'>{$this->lang->words['order']}</span>&nbsp;&nbsp;
				<ul class='ipsList_inline ipsType_smaller' style='display: inline'>
					<li <if test="$this->request['search_app_filters'][$this->request['search_app']][$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']]['sortDir'] == 0">class='active'</if>>
						<a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;sid={$this->request['_sid']}&amp;mid={$this->request['mid']}&amp;userMode={$this->request['userMode']}&amp;search_app={$this->request['search_app']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[{$this->request['search_app']}][{$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}][sortKey]={$this->request['search_app_filters'][$this->request['search_app']][$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']]['sortKey']}&amp;search_app_filters[{$this->request['search_app']}][{$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}][sortDir]=0" base="public"}'>{$this->lang->words['s_search_type_o_0']}</a>
					</li>
					<li <if test="$this->request['search_app_filters'][$this->request['search_app']][$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']]['sortDir'] == 1">class='active'</if>>
						<a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;sid={$this->request['_sid']}&amp;mid={$this->request['mid']}&amp;userMode={$this->request['userMode']}&amp;search_app={$this->request['search_app']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[{$this->request['search_app']}][{$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}][sortKey]={$this->request['search_app_filters'][$this->request['search_app']][$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']]['sortKey']}&amp;search_app_filters[{$this->request['search_app']}][{$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}][sortDir]=1" base="public"}'>{$this->lang->words['s_search_type_o_1']}</a>
					</li>
				</ul>
			<else />
				<if test="count($sortDropDown)">
					<span class='ipsType_small'>{$this->lang->words['sort_by']}</span>&nbsp;&nbsp;
					<if test="count($sortDropDown) <= 4">
						<ul class='ipsList_inline ipsType_smaller' style='display: inline'>
							<foreach loop="$sortDropDown as $k => $l">
								<li <if test="$this->request['search_app_filters'][$this->request['search_app']]['sortKey'] == $k">class='active'</if>>
									<a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;sid={$this->request['_sid']}&amp;mid={$this->request['mid']}&amp;userMode={$this->request['userMode']}&amp;search_app={$this->request['search_app']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[{$this->request['search_app']}][sortKey]={$k}&amp;search_app_filters[{$this->request['search_app']}][sortDir]={$this->request['search_app_filters'][$this->request['search_app']]['sortDir']}" base="public"}'>{$l}</a>
								</li>
							</foreach>
						</ul>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<else />
						<ul class='ipsList_inline ipsType_smaller' style='display: inline'>
							<li class='active'>
								<a href='#' id='search_sort' class='ipbmenu'>{$sortDropDown[ $this->request['search_app_filters'][$this->request['search_app']]['sortKey'] ]} &nbsp;&nbsp;<span class='submenu_indicator'></span>&nbsp;</a>
							</li>
						</ul>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</if>
				</if>
				<span class='ipsType_small'>{$this->lang->words['order']}</span>&nbsp;&nbsp;
				<ul class='ipsList_inline ipsType_smaller' style='display: inline'>
					<li <if test="$this->request['search_app_filters'][$this->request['search_app']]['sortDir'] == 0">class='active'</if>>
						<a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;sid={$this->request['_sid']}&amp;mid={$this->request['mid']}&amp;userMode={$this->request['userMode']}&amp;search_app={$this->request['search_app']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[{$this->request['search_app']}][sortKey]={$this->request['search_app_filters'][$this->request['search_app']]['sortKey']}&amp;search_app_filters[{$this->request['search_app']}][sortDir]=0" base="public"}'>{$this->lang->words['s_search_type_o_0']}</a>
					</li>
					<li <if test="$this->request['search_app_filters'][$this->request['search_app']]['sortDir'] == 1">class='active'</if>>
						<a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;sid={$this->request['_sid']}&amp;mid={$this->request['mid']}&amp;userMode={$this->request['userMode']}&amp;search_app={$this->request['search_app']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[{$this->request['search_app']}][sortKey]={$this->request['search_app_filters'][$this->request['search_app']]['sortKey']}&amp;search_app_filters[{$this->request['search_app']}][sortDir]=1" base="public"}'>{$this->lang->words['s_search_type_o_1']}</a>
					</li>
				</ul>
			</if>
		</div>
		<if test="count($sortDropDown) && count($sortDropDown) >= 5">
			<!-- The menu for changing the sort field -->
			<ul id='search_sort_menucontent' class='ipbmenu_content with_checks' style='display: none'>
				<if test="IPSSearchRegistry::get('config.contentTypes') AND is_array( IPSSearchRegistry::get('config.contentTypes') )">
					<foreach loop="$sortDropDown as $k => $l">
						<li <if test="$this->request['search_app_filters'][$this->request['search_app']][$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']]['sortKey'] == $k">class='selected'</if>>
							<a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;sid={$this->request['_sid']}&amp;mid={$this->request['mid']}&amp;search_app={$this->request['search_app']}&amp;userMode={$this->request['userMode']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[{$this->request['search_app']}][{$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}][sortKey]={$k}&amp;search_app_filters[{$this->request['search_app']}][{$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}][sortDir]={$this->request['search_app_filters'][$this->request['search_app']][$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']]['sortDir']}" base="public"}'>{$l}</a>
						</li>
					</foreach>
				<else />
					<foreach loop="$sortDropDown as $k => $l">
						<li <if test="$this->request['search_app_filters'][$this->request['search_app']]['sortKey'] == $k">class='selected'</if>>
							<a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;sid={$this->request['_sid']}&amp;mid={$this->request['mid']}&amp;userMode={$this->request['userMode']}&amp;search_app={$this->request['search_app']}&amp;search_app_filters[{$this->request['search_app']}][searchInKey]={$this->request['search_app_filters'][$this->request['search_app']]['searchInKey']}&amp;search_app_filters[{$this->request['search_app']}][sortKey]={$k}&amp;search_app_filters[{$this->request['search_app']}][sortDir]={$this->request['search_app_filters'][$this->request['search_app']]['sortDir']}" base="public"}'>{$l}</a>
						</li>
					</foreach>
				</if>
			</ul>
		</if>
		<div>
			<if test="NPTotal:|:$total">
				{$results}
				<br />
				{$pagination}
			<else />
				<p class='no_messages'>{$this->lang->words['user_posts_none']}</p>
			</if>
		</div>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>