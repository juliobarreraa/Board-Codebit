<?php
/**
 * Master skin file
 * Written: Mon, 09 Jul 2012 18:16:42 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: ajax__deletePost
//===========================================================================
function ajax__deletePost() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['dlp_title']}</h3>
<div class='ipsBox'>
	<div class='ipsBox_container'>
		<div class='ipsPad ipsForm_center'>
			{$this->lang->words['dlp_remove_from_view_desc']}<br />
			<span class='desc lighter ipsType_smaller'>{$this->lang->words['dlp_remove_from_view_extra']}</span><br /><br />
			<form action='#{removeUrl}' method='POST'>
				<input type='text' name='deleteReason' id='delPop_reason' value='' class='input_text' style='width: 60%' /> <input type='submit' class='input_submit' value='{$this->lang->words['post_hide']}' />
			</form>
		</div>
	</div>
</div>
<script type='text/javascript'>
$('delPop_reason').defaultize( "{$this->lang->words['post_hide_reason_default']}" );
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: ajax__doDeletePost
//===========================================================================
function ajax__doDeletePost() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['post_delete']}</h3>
<div class='ipsBox'>
	<div class='ipsBox_container'>
		<div class='ipsPad ipsForm_center'>
			{$this->lang->words['post_delete_confirm']}<br />
			<br />
			<form action='#{permaUrl}' method='POST'>
				<input type='submit' class='input_submit' id='delPush' value='{$this->lang->words['post_delete']}' />
			</form>
		</div>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: ajax__restoreTopicDialog
//===========================================================================
function ajax__restoreTopicDialog() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['drestore_title']}</h3>
<div class='ipsBox'>
	<div class='ipsBox_container ipsPad'>
		{$this->lang->words['drestore_text']}
		<br />
		<br />
		<a href='#' class='ipsButton_secondary important' data-clicklaunch="restoreTopicDialogGo" data-scope="topic">{$this->lang->words['drestore_title']}</a>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: ajaxSigCloseMenu
//===========================================================================
function ajaxSigCloseMenu($post) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<ul class='ipbmenu_content'>
	<li class='clearfix'>
		<a href='#' data-clicklaunch='ignoreUsersSig' data-scope='ipb.topic' data-id="{$post['member_id']}">{parse expression="sprintf( $this->lang->words['ignore_userx_signature'], $post['members_display_name'] )"}</a>
	</li>
	<li class='clearfix'>
		<a href='#' data-clicklaunch='ignoreUsersSig' data-scope='ipb.topic' data-id="all">{$this->lang->words['ignore_all_signatures']}</a>
	</li>
</ul>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: announcement_show
//===========================================================================
function announcement_show($announce="",$author="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="topic"}
<h1 class='ipsType_pagetitle'>{$this->lang->words['announce_title']}: {$announce['announce_title']}</h1>
<br />
<h2 class='maintitle'>{$this->lang->words['posted_by']} {parse template="userHoverCard" group="global" params="$author"}</h2>
<div class='post_block first hentry announcement' id='announce_id_{$announce['announce_id']}'>
	<div class='post_wrap'>
		<br />
		<div class='author_info'>
			{parse template="userInfoPane" group="global" params="$author, 'announcement', array( 'id2' => $announce['announce_id'] )"}
		</div>
		<div class='post_body'>
			<div class='post entry-content'>
				{$announce['announce_post']}
			</div>
			<ul class='post_controls clear clearfix'>
				<if test="canmanage:|:$this->memberData['g_is_supmod'] == 1">
					<li class='post_edit'><a class='ipsButton_secondary' href='{parse url="app=core&amp;module=modcp&amp;tab=announcements&amp;fromapp=forums&amp;_do=edit&amp;announce_id={$announce['announce_id']}" base="public"}' title='{$this->lang->words['post_edit_announce']}' class='edit_post'>{$this->lang->words['post_edit_announce']}</a></li>
				</if>
			</ul>
		</div>
	</div>
</div>
<if test="disablelightbox:|:!$this->settings['disable_lightbox']">
{parse template="include_lightbox" group="global" params=""}
</if>
{parse template="include_highlighter" group="global" params="1"}
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: archiveStatusMessage
//===========================================================================
function archiveStatusMessage($topic, $forum) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="isRestoring:|:$this->registry->class_forums->fetchArchiveTopicType($topic) == 'restore'">
	<div class='message unspecific'>
		<if test="isAdminArchivedTop:|:$this->memberData['g_access_cp']">{$this->lang->words['top_archived_topic_restore']}<else />{$this->lang->words['top_archived_topic']}</if>
	</div>
<else />
	<div class='message unspecific'>
		{$this->lang->words['top_archived_topic']}
		<if test="isModArchived:|:$this->memberData['is_mod']">{$this->lang->words['top_archived_topic_mod']}</if>
		<if test="isAdminArchived:|:$this->memberData['g_access_cp']">
			<br />
			<if test="isArchivedDone:|:$this->registry->class_forums->fetchArchiveTopicType( $topic ) == 'archived'">
				<a href='#' class='ipsButton_secondary' data-clicklaunch="restoreTopicDialog" data-scope="topic">{$this->lang->words['top_archived_topic_admin']}</a>
				<a href='#' class='ipsButton_secondary important' data-clicklaunch="deleteTopicDialog" data-scope="topic">{$this->lang->words['tarchived_delete']}</a>
			</if>
		</if>
	</div>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: hookFacebookLike
//===========================================================================
function hookFacebookLike() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="fbAppIdPresent:|:$this->settings['fbc_appid']">
<if test="checkAccess:|:$this->registry->getClass('class_forums')->guestCanSeeTopic($this->request['f'], $this->settings['fbc_bot_group'])">
<br />
<div class='left facebook-like'>
	<fb:like href="{$this->registry->getClass('output')->fetchRootDocUrl()}" layout="standard" show_faces="false" width="450" action="like" colorscheme="light" />
</div>
<div id="fb-root"></div>
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
</if>
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
	{parse template="likeSummaryContents" group="topic" params="$data, $relId, $opts"}
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
// Name: pollDisplay
//===========================================================================
function pollDisplay($poll, $topicData, $forumData, $pollData, $showResults, $editPoll=1) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type='text/javascript'>
//<![CDATA[
	ipb.templates['poll_voters'] = new Template("<h3 class='bar'>{$this->lang->words['poll_voted_for']} #{title}</h3><div class='ipsPad'>#{content}</div>");
//]]>
</script>
<div class='general_box alt poll' id='poll_{$poll['pid']}'>
	<form action="{parse url="app=forums&amp;module=extras&amp;section=vote&amp;t={$topicData['tid']}&amp;st={$this->request['st']}&amp;do=add&amp;secure_key={$this->member->form_hash}" base="public"}" name='pollForm' method="post">
		<h3>{$this->lang->words['poll']} {$poll['poll_question']}<if test="showPollResults:|:$showResults"> <span class='desc'>({$poll['_totalVotes']} {$this->lang->words['poll_vote_casted']})</span></if></h3>
		<if test="publicPollNotice:|:$this->settings['poll_allow_public'] AND $poll['poll_view_voters'] AND ! $showResults">
			<div class='message unspecified'>{$this->lang->words['poll_public_notice']}</div>
		</if>
		<foreach loop="poll_questions:$pollData as $questionID => $questionData">
			<div class='poll_question<if test="votedClass:|:$showResults"> voted</if>'>
				<h4 class='rounded'>{$pollData[ $questionID ]['question']}</h4>
				<if test="noGuestVote:|:! $this->settings['allow_result_view'] AND ! $this->memberData['member_id']">
					{$this->lang->words['poll_noview_guest']}
				<else />
					<ol>
						<foreach loop="poll_choices:$pollData[ $questionID ]['choices'] as $choiceID => $choiceData">
							<if test="showingResults:|:$showResults">
								<li>
									<span class='answer'><if test="hasVoters:|:is_array( $choiceData['voters'] ) AND in_array( $this->memberData['member_id'], array_keys( $choiceData['voters'] ) )"> {parse replacement="your_vote"} </if>{$choiceData['choice']}</span>
									<if test="viewVoters:|:$poll['poll_view_voters'] AND is_array( $choiceData['voters'] ) AND $this->settings['poll_allow_public'] AND $choiceData['votes']">
										<a href='#' class='votes' id='l_voters_{$questionID}_{$choiceID}' title='{$this->lang->words['poll_view_voters']}'>({$choiceData['votes']} {$this->lang->words['poll_votes']} [{$choiceData['percent']}%] - <strong>{$this->lang->words['poll_view']}</strong>)</a>
									<else />
										<span class='votes'> ({$choiceData['votes']} {$this->lang->words['poll_votes']} [{$choiceData['percent']}%])</span>
									</if>
									<if test="votersJs:|:$poll['poll_view_voters'] AND is_array( $choiceData['voters'] ) AND $this->settings['poll_allow_public'] AND $choiceData['votes']">
										<script type='text/javascript'>
											$('l_voters_{$questionID}_{$choiceID}').observe('click', ipb.topic.showVoters.bindAsEventListener( this, {$questionID}, {$choiceID} ) );
											if( Object.isUndefined( ipb.topic.poll[ $questionID ] ) ){
												ipb.topic.poll[ $questionID ] = [];
											}
											
											var users = "";
											
											<foreach loop="poll_voters:$choiceData['voters'] as $id => $member">
												users += "<a href='{parse url="showuser={$member['member_id']}" base="public" seotitle="{$member['members_seo_name']}" template="showuser"}'>{$member['members_colored_name']}</a><if test="lastVoter:|:$member['_last'] == 0">, </if>";
											</foreach>
											ipb.topic.poll[ $questionID ][ $choiceID ] = { name: "{$choiceData['choice']}", users: users};
										</script>
									</if>
									<p class='progress_bar topic_poll' title='{$this->lang->words['poll_percent_of_vote']} {$choiceData['percent']}%'>
										<span style='width: {$choiceData['percent']}%'><span>{$this->lang->words['poll_percent_of_vote']} {$choiceData['percent']}%</span></span>
									</p>
								</li>
							<else />
								<if test="multiVote:|:$choiceData['type'] == 'multi'">
									<li><input type='checkbox' id='choice_{$questionID}_{$choiceID}' name='choice_{$questionID}_{$choiceID}' value='1' class='input_check' /> <label for='choice_{$questionID}_{$choiceID}'>{$choiceData['choice']}</label></li>
								<else />
									<li><input type='radio' name='choice[{$questionID}]' id='choice_{$questionID}_{$choiceID}' class='input_radio' value='{$choiceID}' /> <label for='choice_{$questionID}_{$choiceID}'>{$choiceData['choice']}</label></li>
								</if>
							</if>
						</foreach>
					</ol>
				</if>
			</div>
		</foreach>
		<fieldset class='submit'>
			<legend>{$this->lang->words['poll_vote']}</legend>
			<if test="voteButton:|:$topicData['state'] != 'closed'">
				<if test="voteButtonMid:|:$this->memberData['member_id']">
					<if test="voteButtonVoted:|:$poll['_memberVoted']">
						<if test="deleteVote:|:$this->settings['poll_allow_vdelete'] OR $this->memberData['g_is_supmod']">
							<a href='{parse url="app=forums&amp;module=extras&amp;section=vote&amp;t={$topicData['tid']}&amp;st={$this->request['st']}&amp;do=delete&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['poll_delete_vote']}' id='poll_deletevote' class='input_submit alt'>{$this->lang->words['poll_delete_vote']}</a>
						<else />
							{$this->lang->words['poll_you_voted']}
						</if>
					<else />
						<if test="youCreatedPoll:|:($poll['starter_id'] == $this->memberData['member_id']) and ($this->settings['allow_creator_vote'] != 1)">
							{$this->lang->words['poll_you_created']}
						<else />
							<!-- VOTE Button -->
							<if test="cast:|:$this->request['mode'] != 'show'">
								<input class='input_submit' type="submit" name="submit" value="{$this->lang->words['poll_add_vote']}" title="{$this->lang->words['tt_poll_vote']}" />
							</if>
							<!-- SHOW Button -->
							<if test="displayVotes:|:$this->settings['allow_result_view'] == 1">
								<if test="alreadyDisplayVotes:|:$this->request['mode'] == 'show'">
									<if test="viewVotersLink:|:! $poll_view_voters">
										<a href='{parse url="showtopic={$topicData['tid']}&amp;st={$this->request['st']}" base="public" seotitle="{$topicData['title_seo']}" template="showtopic"}' title='{$this->lang->words['tt_poll_svote']}' id='poll_nullvote' class='input_submit alt'>{$this->lang->words['pl_show_vote']}</a>
									</if>
								<else />
									<a href='{parse url="showtopic={$topicData['tid']}&amp;mode=show&amp;st={$this->request['st']}" base="public" seotitle="{$topicData['title_seo']}" template="showtopic"}' title='{$this->lang->words['tt_poll_show']}' id='poll_showresults' class='input_submit alt'>{$this->lang->words['pl_show_results']}</a>
								</if>
							<else />
								<input class='input_submit' type="submit" name="nullvote" value="{$this->lang->words['poll_null_vote']}" title="{$this->lang->words['tt_poll_null']}" />
							</if>
						</if>
					</if>
				<else />
					{$this->lang->words['poll_no_guests']}
				</if>
			</if>
			<if test="editPoll:|:$editPoll">
				<a href='{parse url="app=forums&amp;module=post&amp;section=post&amp;do=edit_post&amp;f={$poll['forum_id']}&amp;t={$poll['tid']}&amp;p={$topicData['topic_firstpost']}" base="publicWithApp"}' title='{$this->lang->words['poll_edit']}' class='input_submit alt' >{$this->lang->words['poll_edit']}</a>
			</if>
		</fieldset>
	</form>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: post
//===========================================================================
function post($post, $displayData, $topic, $forum=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<!--post:{$post['post']['pid']}-->
<if test="sDeleted:|:$post['post']['_isDeleted'] AND $post['post']['_softDeleteSee']">
	{parse template="softDeletedPostBit" group="topic" params="$post, $displayData['sdData'], $topic"}
</if>
<if test="sDeletedNot:|:! $post['post']['_isDeleted']">
	<div class='post_block hentry clear clearfix <if test="postQueued:|:$post['post']['_isHidden']">moderated</if>' id='post_id_{$post['post']['pid']}'>
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
						{$post['post']['rep_points']}</span> {$this->lang->words['top_this_post_by']} {parse template="userHoverCard" group="global" params="$post['author']"} {$this->lang->words['top_below_thresh']}. <a href='#' title='{$this->lang->words['ignore_view_post']}' id='unhide_post_{$post['post']['pid']}'>{$this->lang->words['rep_view_anyway']}</a>
			</div>
		</if>
	
		<if test="userIgnored:|:$post['post']['_repignored'] == 1 || $post['post']['_ignored']">
			<div class='post_ignore'>
				<if test="userIgnoredLang:|:$post['post']['_repignored'] == 1">{$this->lang->words['post_ignored_rep']}<else />{$this->lang->words['post_ignored']}</if> {parse template="userHoverCard" group="global" params="$post['author']"}. <a href='#entry{$post['post']['pid']}' title='{$this->lang->words['ignore_view_post']}' style='display: none' id='unhide_post_{$post['post']['pid']}'>{$this->lang->words['rep_view_anyway']}</a>
				<if test="userIgnoredLangTwo:|:$this->settings['reputation_enabled'] AND $post['post']['_repignored'] == 1"><div><a href="{parse url="showtopic={$post['post']['topic_id']}&amp;st={$this->request['st']}&amp;rep_filter_set=*&amp;rep_filter=update&amp;secure_key={$this->member->form_hash}" template="showtopic" seotitle="{$topic['title_seo']}" base="public"}">{$this->lang->words['post_ignore_reset_rep']}</a></div></if>
			</div>
		</if>
		<div class='post_wrap' <if test="isNotIgnoring:|:$post['post']['_ignored'] == 1 || $post['post']['_repignored'] == 1">style='display: none'</if>>
			<if test="postMid:|:$post['author']['member_id']">
				<h3 class='row2'>
			<else />
				<h3 class='guest row2'>
			</if>
				<if test="postModCheckbox:|:$this->memberData['is_mod'] && ! $topic['_isArchived']">
					<span class='right'>
						<label for='checkbox_{$post['post']['pid']}' class='post_mod hide'>{$this->lang->words['mod_select_post']}</label><input type='checkbox' id='checkbox_{$post['post']['pid']}' name='selectedpids[]' value='{$post['post']['pid']}' class='post_mod right'<if test="postModSelected:|:!empty( $post['post']['_pid_selected'] )"> checked='checked'</if> data-status='{$post['post']['queued']}' />
					</span>
				</if>
				<span class='post_id right ipsType_small desc blend_links'>
					<if test="hasPages:|:$this->request['st']">
						<a itemprop="replyToUrl" href='{parse url="showtopic={$post['post']['topic_id']}&amp;st={$this->request['st']}#entry{$post['post']['pid']}" template="showtopic" seotitle="{$topic['title_seo']}" base="public"}' rel='bookmark' title='{$topic['title']}{$this->lang->words['link_to_post']} #{$post['post']['post_count']}'>
					<else />
						<a itemprop="replyToUrl" href='{parse url="showtopic={$post['post']['topic_id']}&#entry{$post['post']['pid']}" template="showtopic" seotitle="{$topic['title_seo']}" base="public"}' rel='bookmark' title='{$topic['title']}{$this->lang->words['link_to_post']} #{$post['post']['post_count']}'>
					</if>
					#{$post['post']['post_count']}
					</a>
				</span>
				<if test="postMember:|:$post['author']['member_id']">
					<span itemprop="creator name" class="author vcard">{parse template="userHoverCard" group="global" params="$post['author']"}</span>
				<else />
					{parse template="userHoverCard" group="global" params="$post['author']"}
				</if>
			
				<if test="postIp:|:$post['post']['_show_ip']">
					<span class='ip right ipsType_small'>({$this->lang->words['ip']}:
					<if test="postAdmin:|:$post['author']['g_access_cp']">
						<em>{$this->lang->words['ip_private']}</em>
					<else />
						<if test="accessModCP:|:$this->memberData['g_is_supmod']"><a href="{parse url="app=core&amp;module=modcp&amp;fromapp=members&amp;tab=iplookup&amp;ip={$post['post']['ip_address']}" base="public"}" title='{$this->lang->words['info_about_this_ip']}'>{$post['post']['ip_address']}</a><else />{$post['post']['ip_address']}</if>
					</if>)
					</span>
				</if>
			</h3>
			<div class='author_info'>
				{parse template="userInfoPane" group="global" params="$post['author'], $post['post']['pid'], array( 'isTopicView' => true, 'wl_id' => $post['post']['wl_id'] )"}
			</div>
			<div class='post_body'>
				<p class='posted_info desc lighter ipsType_small'>
					{$this->lang->words['posted']} <abbr class="published" itemprop="commentTime" title="{parse expression="date( 'c', $post['post']['post_date'] )"}">{parse date="$post['post']['post_date']" format="long"}</abbr>
				</p>
				<if test="repHighlight:|:$this->settings['reputation_highlight'] AND $post['post']['rep_points'] >= $this->settings['reputation_highlight']">
					<p class='rep_highlight'>{parse replacement="popular_post"}<br />{$this->lang->words['popular_post']}</p>
				</if>
				<div itemprop="commentText" class='post entry-content <if test="$post['post']['_repignored'] == 1">imgsize_ignore</if>'>
					{$post['post']['post']}
					{$post['post']['attachmentHtml']}
					<br />
					<if test="postEditBy:|:$post['post']['edit_by']">
						<p class='edit'>
							{$post['post']['edit_by']}
							<if test="postEditByReason:|:$post['post']['post_edit_reason'] != ''">
								<br />
								<span class='reason'>{$post['post']['post_edit_reason']}</span>
							</if>
						</p>
					</if>
				</div>
				
				<if test="repButtonsLike:|: ! $topic['_isArchived']">
				{parse template="repButtons" group="global_other" params="$post['author'], array_merge( array( 'primaryId' => $post['post']['pid'], 'domLikeStripId' => 'like_post_' . $post['post']['pid'], 'domCountId' => 'rep_post_' . $post['post']['pid'], 'app' => 'forums', 'type' => 'pid', 'likeFormatted' => $post['post']['like']['formatted'] ), $post['post'] )"}
				</if>
								
				<if test="postSignature:|:$post['post']['signature']">
					{$post['post']['signature']}
				</if>
				
				<if test="controlsForUnapprovedPost:|:$this->memberData['is_mod'] && ! $topic['_isArchived'] && $post['post']['queued']">
					<ul id='postControlsUnapproved_{$post['post']['pid']}' class='post_controls clear clearfix'>
						<if test="canDelete:|:$post['post']['_can_delete'] === TRUE && ! $topic['_isArchived']">
							<li class='post_del'>
								<a class='ipsButton_secondary' href='{parse url="module=moderate&amp;section=moderate&amp;do=postchoice&amp;tact=deletedo&amp;f={$topic['forum_id']}&amp;t={$topic['tid']}&amp;selectedpids[]={$post['post']['pid']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}" base="publicWithApp"}' title='{$this->lang->words['post_delete_title']}' onclick='if( !confirm("{$this->lang->words['post_delete_confirm']}") ){ return false; }' >{$this->lang->words['post_delete']}</a>
							</li>
						</if>
						<li class='post_toggle toggle_post' id='toggle_post_{$post['post']['pid']}' style='display: none'>
							<a class='ipsButton_secondary' href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=postchoice&amp;tact=<if test="!$post['post']['queued']">unapprove<else />approve</if>&amp;selectedpids[{$post['post']['pid']}]={$post['post']['pid']}&amp;t={$topic['tid']}&amp;f={$forum['id']}&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['post_toggle_visible']}'><span id='toggletext_post_{$post['post']['pid']}'><if test="approveUnapprove:|:$post['post']['queued']==1">{$this->lang->words['post_approve']}<else />{$this->lang->words['post_unapprove']}</if></span></a>
						</li>
						<if test="canEdit:|:$post['post']['_can_edit'] === TRUE">
							<li class='post_edit'><a href='{parse url="module=post&amp;section=post&amp;do=edit_post&amp;f={$topic['forum_id']}&amp;t={$topic['tid']}&amp;p={$post['post']['pid']}&amp;st={$this->request['st']}" base="publicWithApp"}' title='{$this->lang->words['post_edit_title']}' class='ipsButton_secondary edit_post' id='edit_post_{$post['post']['pid']}'>{$this->lang->words['post_edit']}</a></li>
						</if>
					</ul>
				</if>
			
				<ul id='postControlsNormal_{$post['post']['pid']}' class='post_controls clear clearfix' <if test="hideNormalControlsForUnapprovedPost:|:$this->memberData['is_mod'] && ! $topic['_isArchived'] && $post['post']['queued']">style='display:none'</if>>
					<li class='top hide'><a href='#ipboard_body' class='top' title='{$this->lang->words['back_top']}'>{$this->lang->words['back_top']}</a></li>
					<if test="replyButtonWarn:|:!$this->memberData['unacknowledged_warnings'] && !$this->memberData['restrict_post']">
						<if test="replyButton:|:$post['post']['_canReply']">
							<li><a href="{parse url="module=post&amp;section=post&amp;do=reply_post&amp;f={$this->request['f']}&amp;t={$this->request['t']}&amp;qpid={$post['post']['pid']}" base="publicWithApp"}"  class='_ips_trigger_quote ipsButton_secondary' pid="{$post['post']['pid']}" title="{$this->lang->words['tt_reply_to_post']}">{$this->lang->words['post_reply']}</a></li>
							<if test="multiquote:|:!empty( $post['post']['_mq_selected'] )">
								<li class='selected multiquote' id='multiq_{$post['post']['pid']}' style='display: none'>
							<else />
								<li class='multiquote' id='multiq_{$post['post']['pid']}' style='display: none'>
							</if>
								<a href="{parse url="module=post&amp;section=post&amp;do=reply_post&amp;f={$this->request['f']}&amp;t={$this->request['t']}&amp;qpid={$post['post']['pid']}" base="publicWithApp"}" title="{$this->lang->words['quote_with_mq']}" class='ipsButton_secondary'>{$this->lang->words['mq']}</a>
							</li>
						</if>
					</if>
					<if test="canDelete:|:$post['post']['_can_delete'] === TRUE && ! $topic['_isArchived']">
						<li id='del_post_{$post['post']['pid']}' class='post_del'>
							<a href='{parse url="module=moderate&amp;section=moderate&amp;do=postchoice&amp;tact=deletedo&amp;f={$topic['forum_id']}&amp;t={$topic['tid']}&amp;selectedpids[]={$post['post']['pid']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}" base="publicWithApp"}' title='{$this->lang->words['post_delete_title']}' class='delete_post' onclick='return false;' >{$this->lang->words['post_delete']}</a>
						</li>
					</if>
					<if test="canHide:|:$post['post']['_softDelete'] && ! $topic['_isArchived']">
						<li id='hide_post_{$post['post']['pid']}'>
							<a href='{parse url="module=moderate&amp;section=moderate&amp;do=postchoice&amp;tact=delete&amp;f={$topic['forum_id']}&amp;t={$topic['tid']}&amp;selectedpids[]={$post['post']['pid']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}" base="publicWithApp"}' class='hide_post'>{$this->lang->words['post_hide']}</a>
						</li>
					</if>
					<if test="canEdit:|:$post['post']['_can_edit'] === TRUE">
						<li class='post_edit'><a href='{parse url="module=post&amp;section=post&amp;do=edit_post&amp;f={$topic['forum_id']}&amp;t={$topic['tid']}&amp;p={$post['post']['pid']}&amp;st={$this->request['st']}" base="publicWithApp"}' title='{$this->lang->words['post_edit_title']}' class='edit_post' id='edit_post_{$post['post']['pid']}'>{$this->lang->words['post_edit']}</a></li>
					</if>
					<if test="hasblog:|:$this->settings['blog_allow_bthis'] AND $this->memberData['has_blog'] AND IPSLib::appIsInstalled( 'blog' ) AND $post['post']['_canReply']">
						<li>
							<a href='{parse url="app=blog&amp;blog_this=forums&amp;id1={$this->request['t']}&amp;id2={$post['post']['pid']}" base="public"}'>{$this->lang->words['blog_this']}</a>
						</li>
					</if>
					<if test="canReportPost:|:$topic['_canReport'] and ( $this->memberData['member_id'] ) && ! $topic['_isArchived']">
						<li class='report'>
							<a href='{parse url="app=core&amp;module=reports&amp;rcom=post&amp;tid={$this->request['t']}&amp;pid={$post['post']['pid']}&amp;st={$this->request['st']}" base="public"}'>{$this->lang->words['report']}</a>
						</li>
						<if test="postIsReported:|:$this->memberData['_cache']['report_temp']['post_marker']['post'][ $post['post']['pid'] ]['gfx'] > 0">
							<li class='report'>
								<a href="{$this->settings['base_url']}app=core&amp;module=reports&amp;section=reports&amp;do=show_report&amp;rid={$this->memberData['_cache']['report_temp']['post_marker']['post'][$post['post']['pid']]['info']['id']}" id='post-report-{$post['post']['pid']}' class='ipbmenu'> <span id="rstat-{$this->memberData['_cache']['report_temp']['post_marker']['post'][ $post['post']['pid'] ]['info']['id']}"><img src="{$this->settings['img_url']}/reports/post_alert_{$this->memberData['_cache']['report_temp']['post_marker']['post'][$post['post']['pid']]['gfx']}.png" alt="" /></span> </a>
							</li>
						</if>
					</if>
				</ul>
				<if test="reportedPostData:|:! $topic['_isArchived'] AND $this->memberData['_cache']['report_temp']['post_marker']['post'][ $post['post']['pid'] ]['gfx'] > 0">
					<ul id='post-report-{$post['post']['pid']}_menucontent' class='ipbmenu_content report_menu'>
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
	</div>
</if>
		
		<if test="initIgnoredPost:|:$post['post']['_repignored'] == 1 || $post['post']['_ignored']">
			<script type='text/javascript'>
				ipb.topic.setPostHidden( {$post['post']['pid']} );
				$('unhide_post_{$post['post']['pid']}').show();
			</script>
		</if>
		<hr />
		
		<if test="adCodeCheck:|:$post['post']['_adCode']">
			{$post['post']['_adCode']}
		</if>
<script type="text/javascript">
var pid = parseInt({$post['post']['pid']});
if ( pid > ipb.topic.topPid ){
	ipb.topic.topPid = pid;
}
<if test="sDeletedNotMQ:|:! $post['post']['_isDeleted']">
	// Show multiquote for JS browsers
	if ( $('multiq_{$post['post']['pid']}') )
	{
		$('multiq_{$post['post']['pid']}').show();
	}
	
	if( $('toggle_post_{$post['post']['pid']}') )
	{
		$('toggle_post_{$post['post']['pid']}').show();
	}
	
	// Add perm data
	ipb.topic.deletePerms[{$post['post']['pid']}] = { 'canDelete' : {parse expression="intval($post['post']['_can_delete'])"}, 'canSoftDelete' : {parse expression="intval($post['post']['_softDelete'])"} };
</if>
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: quickEditPost
//===========================================================================
function quickEditPost($post) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{$post['post']}
    {$post['attachmentHtml']}
	<br />
	<if test="editByQe:|:$post['edit_by']">
		<p class='edit'>
			{$post['edit_by']}
			<if test="editReasonQe:|:$post['post_edit_reason'] != ''">
				<br />
				<span class='reason'>{$this->lang->words['reason_for_edit']} {$post['post_edit_reason']}</span>
			</if>
		</p>
	</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: show_attachment_title
//===========================================================================
function show_attachment_title($title="",$data="",$type="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div id='attach_wrap' class='clearfix'>
	<h4>{$title}</h4>
	<ul>
		<foreach loop="attach:$data as $file">
			<li class='<if test="attachType:|:$type == 'attach'">attachment</if>'>
				{$file}
			</li>
		</foreach>
	</ul>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: Show_attachments
//===========================================================================
function Show_attachments($data="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<a href="{parse url="app=core&amp;module=attach&amp;section=attach&amp;attach_id={$data['attach_id']}" base="public"}" title="{$this->lang->words['attach_dl']}"><img src="{$this->settings['public_dir']}<if test="hasmime:|:$data['mime_image']">{$data['mime_image']}<else />style_extra/mime_types/unknown.gif</if>" alt="{$this->lang->words['attached_file']}" /></a>
&nbsp;<a href="{parse url="app=core&amp;module=attach&amp;section=attach&amp;attach_id={$data['attach_id']}" base="public"}" title="{$this->lang->words['attach_dl']}"><strong>{$data['attach_file']}</strong></a> &nbsp;&nbsp;<span class='desc'><strong>{$data['file_size']}</strong></span>
&nbsp;&nbsp;<span class="desc lighter">{$data['attach_hits']} {$this->lang->words['attach_hits']}</span>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: Show_attachments_img
//===========================================================================
function Show_attachments_img($data=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<a class='resized_img' rel='lightbox[{$data['attach_rel_id']}]' id='ipb-attach-url-{$data['_attach_id']}' href="{parse url="app=core&amp;module=attach&amp;section=attach&amp;attach_rel_module={$data['type']}&amp;attach_id={$data['attach_id']}" base="public"}" title="{$data['location']} - {$this->lang->words['attach_size']} {$data['file_size']}, {$this->lang->words['attach_ahits']} {$data['attach_hits']}"><img itemprop="image" src="{$this->settings['upload_url']}/{$data['o_location']}" class='bbc_img linked-image' alt="{$data['location']}" /></a>
{parse expression="$this->registry->output->addMetaTag( 'og:image', "{$this->settings['upload_url']}/{$data['o_location']}", false )"}
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: Show_attachments_img_thumb
//===========================================================================
function Show_attachments_img_thumb($data=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<a class='resized_img' rel='lightbox[{$data['attach_rel_id']}]' id='ipb-attach-url-{$data['_attach_id']}' href="{parse url="app=core&amp;module=attach&amp;section=attach&amp;attach_rel_module={$data['type']}&amp;attach_id={$data['attach_id']}" base="public"}" title="{$data['location']} - {$this->lang->words['attach_size']} {$data['file_size']}, {$this->lang->words['attach_ahits']} {$data['attach_hits']}"><img itemprop="image" src="{$this->settings['upload_url']}/{$data['t_location']}" id='ipb-attach-img-{$data['_attach_id']}' style='width:{$data['t_width']};height:{$data['t_height']}' class='attach' width="{$data['t_width']}" height="{$data['t_height']}" alt="{$data['location']}" /></a>
{parse expression="$this->registry->output->addMetaTag( 'og:image', "{$this->settings['upload_url']}/{$data['t_location']}", false )"}
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: softDeletedPostBit
//===========================================================================
function softDeletedPostBit($post, $sdData, $topic) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>
	$_sD = $sdData[ $post['post']['pid'] ];
	$_sM = $_sD;
</php>
<div class='post_block hentry clear moderated' id='post_id_{$post['post']['pid']}'>
	<a id='entry{$post['post']['pid']}'></a>
	<div class='post_wrap'>
		<if test="postMid:|:$post['author']['member_id']">
			<h3 class='row2'>
		<else />
			<h3 class='row2 guest'>
		</if>
			<if test="postModCheckbox:|:$this->memberData['is_mod'] OR $this->memberData['g_is_supmod']">
				<span class='post_id right'>
					<label for='checkbox_{$post['post']['pid']}' class='post_mod hide'>{$this->lang->words['mod_select_post']}</label><input type='checkbox' id='checkbox_{$post['post']['pid']}' name='selectedpids[]' value='{$post['post']['pid']}' class='post_mod'<if test="postModSelected:|:!empty( $post['post']['_pid_selected'] )"> checked='checked'</if> data-status='{$post['post']['queued']}' />
				</span>
			</if>
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
						<a href="{parse url="app=core&amp;module=modcp&amp;tab=iplookup&amp;fromapp=forums&amp;_do=submit&amp;ip={$post['post']['ip_address']}" base="public"}" title='{$this->lang->words['info_about_this_ip']}'>{$post['post']['ip_address']}</a>
					</if>
					)</span>
				</if>
			</h3>
		<div class='post_body' id='postsDelete_{$post['post']['pid']}' style='margin-left: 0;'>
			<div class='post entry-content' style='padding:10px'>
				{$this->lang->words['post_deleted_by']} {parse template="userHoverCard" group="global" params="$_sD"} {parse date="$_sD['sdl_obj_date']" format="long"}.
				<if test="showReason:|:$post['post']['_softDeleteReason']">
					<p class='desc'><if test="$_sD['sdl_obj_reason']">{$_sD['sdl_obj_reason']}<else />{$this->lang->words['no_reason_given']}</if></p>
				</if>
			</div>
		</div>
		<if test="showPost:|:$post['post']['_softDeleteContent']">
		<div id='postsDeleteShow_{$post['post']['pid']}' style='display:none'>
			<div class='author_info'>
				{parse template="userInfoPane" group="global" params="$post['author'], $post['post']['pid'], array()"}
			</div>
			<div class='post_body'>
				<p class='posted_info'>
					{$this->lang->words['posted']} <abbr class="published" title="{parse expression="@date( 'c', $post['post']['post_date'] )"}">{parse date="$post['post']['post_date']" format="long"}</abbr>
				</p>
				<div class='post entry-content'>
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
				<if test="postSignature:|:$post['post']['signature']">
					{$post['post']['signature']}
				</if>
			</div>
		</div>
		</if>
		<if test="sdOptions:|:$post['post']['_softDeleteContent'] OR $post['post']['_softDeleteRestore']">
			<ul class='post_controls clear clearfix'>
				<if test="$post['post']['_can_delete']">
					<li>
						<a class='ipsButton_secondary' href='{parse url="module=moderate&amp;section=moderate&amp;do=04&amp;f={$topic['forum_id']}&amp;t={$topic['tid']}&amp;p={$post['post']['pid']}&amp;pid={$post['post']['pid']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;nr=1" base="publicWithApp"}' title='{$this->lang->words['post_delete_title']}' class='sd_remove' onclick='if( !confirm("{$this->lang->words['post_delete_confirm']}") ){ return false; }'>{$this->lang->words['post_delete']}</a>
					</li>
				</if>
				<if test="$post['post']['_softDeleteRestore']">
					<li class='post_toggle sd_restore' id='restoreContent_{$post['post']['pid']}'>
						<a class='ipsButton_secondary' href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=postchoice&amp;tact=sundelete&amp;selectedpids[{$post['post']['pid']}]={$post['post']['pid']}&amp;pid={$post['post']['pid']}&amp;t={$topic['tid']}&amp;f={$topic['forum_id']}&amp;auth_key={$this->member->form_hash}&amp;nr=1" base="public"}'><span>{$this->lang->words['sdpost_restore']}</span></a>
					</li>
				</if>
				<if test="$post['post']['_softDeleteContent']">
					<li class='post_toggle sd_content' id='seeContent_{$post['post']['pid']}'>
						<a href='#'><span>{$this->lang->words['togglepostcontent']}</span></a>
					</li>
				</if>
			</ul>
		</if>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: topicPreview
//===========================================================================
function topicPreview($topic, $posts) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<foreach loop="$posts as $t => $post">
	{parse template="userSmallPhoto" group="global" params="array_merge( $post, array( 'pp_small_photo' => $post['pp_thumb_photo'], '_customClass' => 'ipsUserPhoto_medium' ) )"}
	<div class='preview_col'>
		<p class='preview_info'>
			<strong><if test="$post['members_display_name']">{parse template="userHoverCard" group="global" params="$post"}<else />{$this->settings['guest_name_pre']}{$post['author_name']}{$this->settings['guest_name_suf']}</if></strong> <if test="$t != 'search'">{$this->lang->words['tg_madethe']} <a href="{parse url="showtopic={$topic['tid']}&amp;findpost={$post['pid']}" seotitle="{$topic['title_seo']}" template="showtopic" base="public"}">{$this->lang->words['tg_'.$t]}</a><else />{$this->lang->words['tg_'.$t]}</if>
		</p>
		{$post['post']}
	</div>
	<br class='clear' />
</foreach>
<br class='clear' />
<a href='{parse url="showtopic={$topic['tid']}" base="public" template="showtopic" seotitle="{$topic['title_seo']}"}' class='ipsButton'>{$this->lang->words['tg_gototopic']}</a>
<if test="! $topic['_lastRead'] OR $topic['_unreadPosts']">
	&nbsp;&nbsp;<span class='desc'>
		<if test="$topic['_unreadPosts']">
			{$topic['_unreadPosts']} {$this->lang->words['newpoststp']}
			&middot; <a href='{parse url="showtopic={$topic['tid']}&amp;view=getnewpost" base="public" template="showtopic" seotitle="{$topic['title_seo']}"}'>{$this->lang->words['tg_go_last_post']}</a>
		</if>
		<a data-clicklaunch="topicMarkRead" data-tid="{$topic['tid']}" href="{parse url="showtopic={$topic['tid']}&amp;view=getnewpost" base="public" template="showtopic" seotitle="{$topic['title_seo']}"}"><if test="$topic['_unreadPosts']">&middot; </if>{$this->lang->words['mark_topic_read']}</a>
	</span>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: topicViewTemplate
//===========================================================================
function topicViewTemplate($forum, $topic, $post_data, $displayData) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="allowRating:|:$forum['forum_allow_rating']">
{parse js_module="rating"}
</if>
{parse js_module="topic"}
<script type="text/javascript">
//<![CDATA[
	ipb.topic.inSection = 'topicview';
	ipb.topic.topic_id  = {$topic['tid']};
	ipb.topic.forum_id  = {$forum['id']};
	ipb.topic.start_id  = {parse expression="intval($this->request['st'])"};
	ipb.topic.topPid    = 0;
	ipb.topic.counts    = { postTotal: {parse expression="intval($topic['posts']+1)"},
							curStart:  ipb.topic.start_id,
							perPage:   {parse expression="intval($this->settings['display_max_posts'])"} };
	//Search Setup
	ipb.vars['search_type']			= 'forum';
	ipb.vars['search_type_id']		= {$forum['id']};
	ipb.vars['search_type_2']		= 'topic';
	ipb.vars['search_type_id_2']	= {$topic['tid']};
	
	<if test="canDeleteUrls:|:!$this->member->is_not_human">
	// Delete stuff set up
	ipb.topic.deleteUrls['hardDelete'] = new Template( ipb.vars['base_url'] + "app=forums&module=moderate&section=moderate&do=04&f={$forum['id']}&t={$topic['tid']}&st={$this->request['st']}&auth_key={$this->member->form_hash}&p=#{pid}" );
	ipb.topic.deleteUrls['softDelete'] = new Template( ipb.vars['base_url'] + "app=forums&module=moderate&section=moderate&do=postchoice&tact=sdelete&t={$topic['tid']}&f={$forum['id']}&auth_key={$this->member->form_hash}&selectedpids[#{pid}]=#{pid}&pid=#{pid}" );
	</if>
	
	ipb.templates['post_moderation'] = new Template("<div id='comment_moderate_box' class='ipsFloatingAction' style='display: none'><span class='desc'>{$this->lang->words['comment_action_count']} </span><select id='tactInPopup' class='input_select'><option value='approve'>{$this->lang->words['cpt_approve']}</option><option value='delete'>{$this->lang->words['cpt_hide']}</option><option value='sundelete'>{$this->lang->words['cpt_undelete']}</option><option value='deletedo'>{$this->lang->words['cpt_delete']}</option><option value='merge'>{$this->lang->words['cpt_merge']}</option><option value='split'>{$this->lang->words['cpt_split']}</option><option value='move'>{$this->lang->words['cpt_move']}</option></select>&nbsp;&nbsp;<input type='button' class='input_submit' id='submitModAction' value='{$this->lang->words['comments_act_go']}' /></div>");
	
//]]>
</script>
<if test="disablelightbox:|:!$this->settings['disable_lightbox']">
{parse template="include_lightbox" group="global" params=""}
</if>
{$displayData['follow_data']}
<if test="linkAvatarOpen:|:!empty($topic['_starter']['member_id']) && $this->memberData['g_mem_info']">
<a href='{parse url="showuser={$topic['_starter']['member_id']}" seotitle="{$topic['_starter']['members_seo_name']}" template="showuser" base="public"}' class='ipsUserPhotoLink'>
</if>
	<img src='{$topic['_starter']['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_medium left' />
<if test="linkAvatarclose:|:!empty($topic['_starter']['member_id']) && $this->memberData['g_mem_info']">
</a>
</if>
<div itemscope itemtype="http://schema.org/Article" class='ipsBox_withphoto'>
	<if test="topicRating:|:$forum['forum_allow_rating']">
		<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class='rating ipsType_smaller'>
			<if test="$topic['_allow_rate']">
				<strong>{$this->lang->words['js_rate_me']}</strong>&nbsp;&nbsp;
			</if>
			<if test="$topic['_allow_rate']">
				<a href='{parse url="app=forums&amp;module=extras&amp;section=rating&amp;t={$topic['tid']}&amp;rating=1&amp;secure_key={$this->member->form_hash}" base="public"}' id='topic_rate_1' title='{$this->lang->words['top_js_1star']}'>
			</if>
			<if test="rate1:|:$topic['_rate_int'] >= 1">
				{parse replacement="rate_on"}
			<else />
				{parse replacement="rate_off"}
			</if>
			<if test="$topic['_allow_rate']">
				</a><a href='{parse url="app=forums&amp;module=extras&amp;section=rating&amp;t={$topic['tid']}&amp;rating=2&amp;secure_key={$this->member->form_hash}" base="public"}' id='topic_rate_2' title='{$this->lang->words['top_js_2star']}'>
			</if>
			<if test="rate2:|:$topic['_rate_int'] >= 2">
				{parse replacement="rate_on"}
			<else />
				{parse replacement="rate_off"}
			</if>
			<if test="$topic['_allow_rate']">
				</a><a href='{parse url="app=forums&amp;module=extras&amp;section=rating&amp;t={$topic['tid']}&amp;rating=3&amp;secure_key={$this->member->form_hash}" base="public"}' id='topic_rate_3' title='{$this->lang->words['top_js_3star']}'>
			</if>
			<if test="rate3:|:$topic['_rate_int'] >= 3">
				{parse replacement="rate_on"}
			<else />
				{parse replacement="rate_off"}
			</if>
			<if test="$topic['_allow_rate']">
				</a><a href='{parse url="app=forums&amp;module=extras&amp;section=rating&amp;t={$topic['tid']}&amp;rating=4&amp;secure_key={$this->member->form_hash}" base="public"}' id='topic_rate_4' title='{$this->lang->words['top_js_4star']}'>
			</if>
			<if test="rate4:|:$topic['_rate_int'] >= 4">
				{parse replacement="rate_on"}
			<else />
				{parse replacement="rate_off"}
			</if>
			<if test="$topic['_allow_rate']">
				</a><a href='{parse url="app=forums&amp;module=extras&amp;section=rating&amp;t={$topic['tid']}&amp;rating=5&amp;secure_key={$this->member->form_hash}" base="public"}' id='topic_rate_5' title='{$this->lang->words['top_js_5star']}'>
			</if>
			<if test="rate5:|:$topic['_rate_int'] >= 5">
				{parse replacement="rate_on"}
			<else />
				{parse replacement="rate_off"}
			</if>
			<if test="$topic['_allow_rate']">
				</a>
			</if>
			<span id='rating_text'>
				<if test="hasRates:|:$topic['topic_rating_hits'] > 0">
					<span itemprop="ratingCount" id='rating_hits'>{$topic['topic_rating_hits']}</span> <span>{$this->lang->words['poll_s_votes']}</span>
					<meta itemprop="ratingValue" content="{parse expression="floor($topic['_rate_int'])"}" />
				</if>
			</span>
			<if test="$topic['_allow_rate']">
				<script type='text/javascript'>
				//<![CDATA[
					rating = new ipb.rating( 'topic_rate_', { 
										url: ipb.vars['base_url'] + 'app=forums&module=ajax&section=topics&do=rateTopic&t={$topic['tid']}&md5check=' + ipb.vars['secure_hash'],
										cur_rating: {$topic['_rate_int']},
										rated: <if test="jsHasRates:|:$topic['_rating_value'] != -1">1<else />0</if>,
										allow_rate: {$topic['_allow_rate']},
										multi_rate: 1,
										show_rate_text: true
									  } );
				//]]>
				</script>
			</if>
		</span>
	</if>
	<h1 itemprop="name" class='ipsType_pagetitle'>{$topic['title']}</h1>
	<div class='desc lighter blend_links'>
		 {$this->lang->words['started_by']} <span itemprop="creator">{parse template="userHoverCard" group="global" params="$topic['_starter']"}</span>, <span itemprop="dateCreated">{parse date="$topic['start_date']" format="SHORT"}</span>
	</div>
	<if test="hasTags:|:is_array($topic['tags'])">
		{$topic['tags']['formatted']['parsedWithoutComma']}
		<br />
	</if>
	<meta itemprop="interactionCount" content="UserComments:{parse expression="intval($topic['posts'] + 1)"}" />
</div>
<if test="topicHasBeenHidden:|:$topic['approved'] == -1">
	<br />
	<div class='message error'>
		{parse expression="sprintf( $this->lang->words['tdb__forumindex'], $this->registry->output->getTemplate('global')->userHoverCard($topic['sdData']) )"} {parse date="$topic['sdData']['sdl_obj_date']" format="long"}
		<if test="showReason:|:$this->registry->getClass('class_forums')->canSeeSoftDeleteReason( $forum['id'] )">
			<br /><span><if test="$topic['sdData']['sdl_obj_reason']">{$topic['sdData']['sdl_obj_reason']}<else />{$this->lang->words['tdb__noreasongi']}</if></span>
		</if>
		<br /><br />
		<if test="tbdSoftRestore:|:$this->memberData['g_is_supmod'] == 1 || $this->memberData['forumsModeratorData'][ $forum['id'] ]['bw_mod_un_soft_delete_topic']">
			<a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;f={$forum['id']}&amp;t={$topic['tid']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;do=sundelete" base="public"}' title='{$this->lang->words['dl_ths_restore']}' class='ipsButton_secondary'>{$this->lang->words['dl_ths_restore']}</a>
		</if>
		<if test="tbdRestore:|:$this->memberData['g_is_supmod'] == 1 || $this->memberData['forumsModeratorData'][ $forum['id'] ]['delete_topic']">
			<a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;f={$forum['id']}&amp;st={$this->request['st']}&amp;t={$topic['tid']}&amp;auth_key={$this->member->form_hash}&amp;do=08" base="public"}' title='{$this->lang->words['dl_ths_delete']}' class='ipsButton_secondary important'>{$this->lang->words['dl_ths_delete']}</a>
		</if>
	</div>
</if>
<if test="topicHasBeenDeleted:|:$topic['approved'] == 2">
	<br />
	<div class='message error'>
		{$this->lang->words['topic_deleted']}
		<br /><br />
		<a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=topic_restore&amp;t={$topic['tid']}&amp;f={$forum['id']}&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['dl_ths_restore']}' class='ipsButton_secondary'>{$this->lang->words['restore_post']}</a>
		<a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=09&amp;t={$topic['tid']}&amp;f={$forum['id']}&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['dl_ths_delete']}' class='ipsButton_secondary'>{$this->lang->words['perm_delete_post']}</a>
	</div>
</if>
<br />
<div class='topic_controls'>
	{$topic['SHOW_PAGES']}
	<if test="isArchivedPostBox:|:$topic['_isArchived']">
		{parse template="archiveStatusMessage" group="topic" params="$topic,$forum"}
	<else />
		<ul class='topic_buttons'>
			<if test="closedButton:|:$displayData['reply_button']['image'] == 'locked'">
				<li class='important'>
					<if test="pollOnly:|:isset($displayData['poll_data']['poll']['poll_only']) && $displayData['poll_data']['poll']['poll_only']">
						<if test="closedButtonLink:|:$displayData['reply_button']['url']">
							<a href='{$displayData['reply_button']['url']}' accesskey='r'>{parse replacement="lock_icon"} {$this->lang->words['top_poll_only_reply']}</a>
						<else />
							<span>{parse replacement="lock_icon"} {$this->lang->words['top_poll_only']}</span>
						</if>
					<else />
						<if test="closedButtonLink:|:$displayData['reply_button']['url']">
							<a href='{$displayData['reply_button']['url']}' accesskey='r'>{parse replacement="lock_icon"} {$this->lang->words['top_locked_reply']}</a>
						<else />
							<span>{parse replacement="lock_icon"} {$this->lang->words['top_locked']}</span>
						</if>
					</if>
				</li>
			<else />
				<if test="replyButton:|:$displayData['reply_button']['image']">
					<if test="replyButtonLink:|:$displayData['reply_button']['url']">
						<li><a href='{$displayData['reply_button']['url']}' title='{$this->lang->words['topic_add_reply']}' accesskey='r'>{$this->lang->words['topic_add_reply']}</a></li>
					<else />
						<li class='disabled'><span><if test="isMemberTop:|: ! $this->memberData['member_id']">{$this->lang->words['topic_no_reply_guest']}<else />{$this->lang->words['top_cannot_reply']}</if></span></li>
					</if>
				</if>
			</if>
			<if test="topicDescription:|:$topic['hasUnreadPosts'] AND ( $topic['posts'] + 1 ) > $this->settings['display_max_posts']">
				<li class='non_button'><a href='{parse url="showtopic={$topic['tid']}&amp;view=getnewpost" template="showtopic" seotitle="{$topic['title_seo']}" base="public"}' title='{$this->lang->words['first_unread_post']}'>{$this->lang->words['go_to_first_unread']}</a></li>
			</if>
			<if test="modOptions:|:$displayData['mod_links'] AND ( $this->memberData['is_mod'] OR $this->memberData['member_id'] == $topic['starter_id'] )">
				<li class='non_button'><a href='#' id='topic_mod_options' title='{$this->lang->words['topic_moderation']}'>{$this->lang->words['topic_moderation']}</a></li>
			</if>
		</ul>	
	</if>
</div>
<if test="modOptionsDropdown:|:$displayData['mod_links'] AND ( $this->memberData['is_mod'] OR $this->memberData['member_id'] == $topic['starter_id'] )">
<ul class='ipbmenu_content' id='topic_mod_options_menucontent'  style='display:none'>
	<foreach loop="mod_links:$displayData['mod_links'] as $_mod_link">
		<li><a <if test="isDelete:|:$_mod_link['option'] == '08'"> data-confirmaction="true"</if> href="{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;t={$topic['tid']}&amp;f={$topic['forum_id']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;_fromTopic=1&amp;do={$_mod_link['option']}" base="public"}" class='modlink_{$_mod_link['option']}'>{$_mod_link['value']}</a></li>
	</foreach>
	<if test="$topic['topic_queuedposts'] AND $this->registry->class_forums->canQueuePosts( $forum['id'] )">
		<li><a href="{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;t={$topic['tid']}&amp;f={$topic['forum_id']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;_fromTopic=1&amp;do=p_approve" base="public"}">{parse expression="sprintf( $this->lang->words['nmo_p_approve'], $topic['topic_queuedposts'])"}</a></li>
	</if>
	<if test="$topic['topic_queuedposts'] AND $this->registry->class_forums->canHardDeletePosts( $forum['id'], $topic )">
		<li><a href="{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;t={$topic['tid']}&amp;f={$topic['forum_id']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;_fromTopic=1&amp;do=p_delete_approve" base="public"}">{parse expression="sprintf( $this->lang->words['nmo_p_delete_approve'], $topic['topic_queuedposts'])"}</a></li>
	</if>
	<if test="$this->registry->class_forums->can_Un_SoftDeletePosts( $forum['id'] ) AND $topic['topic_deleted_posts']">
		<li><a href="{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;t={$topic['tid']}&amp;f={$topic['forum_id']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;_fromTopic=1&amp;do=p_restore" base="public"}">{parse expression="sprintf( $this->lang->words['nmo_p_restore'], $topic['topic_deleted_posts'])"}</a></li>
	</if>
	<if test="$topic['topic_deleted_posts'] AND $this->registry->class_forums->canHardDeletePosts( $forum['id'], $topic )">
		<li><a href="{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;t={$topic['tid']}&amp;f={$topic['forum_id']}&amp;st={$this->request['st']}&amp;auth_key={$this->member->form_hash}&amp;_fromTopic=1&amp;do=p_delete_softed" base="public"}">{parse expression="sprintf( $this->lang->words['nmo_p_delete_softed'], $topic['topic_deleted_posts'])"}</a></li>	
	</if>
	<if test="mmModOptions:|:is_array( $displayData['multi_mod'] ) AND count( $displayData['multi_mod'] )">
		<foreach loop="mm:$displayData['multi_mod'] as $mm_data">
			<li><a href="{parse url="app=forums&amp;module=moderate&amp;section=multimod&amp;t={$topic['tid']}&amp;mm_id={$mm_data[0]}" base="public"}">{$mm_data[1]}</a></li>
		</foreach>
	</if>
</ul>
<script type='text/javascript'>
	document.observe("dom:loaded", function()
	{
		new ipb.Menu( $('topic_mod_options'), $('topic_mod_options_menucontent') );
	} );
</script>
</if>
<div class='maintitle clear clearfix'>
	<span class='ipsType_small'>
		<if test="$topic['posts']">
			{parse expression="sprintf( ( $topic['posts'] > 1 ) ? $this->lang->words['x_replies_to_topic'] : $this->lang->words['x_reply_to_topic'], $topic['posts'] )"}
		<else />
			{$this->lang->words['no_replies_to_topic']}
		</if>
	</span>
	<if test="reputationFilter:|:$this->settings['reputation_enabled'] && $this->settings['reputation_point_types'] != 'like' && $this->settings['reputation_show_content'] && $this->memberData['member_id'] != 0 && is_array($this->caches['reputation_levels'])">
		<a href='#rep_filter_menucontent' id='rep_filter' class='right ipsType_smaller'>
			<if test="repFilterDefault:|:$this->memberData['_members_cache']['rep_filter'] && $this->memberData['_members_cache']['rep_filter'] === '*'">
				{$this->lang->words['rep_f_viewing_all']}
			<else />
				{parse expression="sprintf( $this->lang->words['rep_f_hiding'], $this->memberData['_members_cache']['rep_filter'] )"}
			</if>
			<img src='{$this->settings['img_url']}/useropts_arrow.png' />
		</a>
	</if>	
</div>
<if test="reputationFilter:|:$this->settings['reputation_enabled'] && $this->settings['reputation_point_types'] != 'like' && $this->settings['reputation_show_content'] && $this->memberData['member_id'] != 0 && is_array($this->caches['reputation_levels'])">
	<ul id='rep_filter_menucontent' class='ipbmenu_content with_checks' style='display: none'>
		<li <if test="optSelectStar:|:isset( $this->memberData['_members_cache']['rep_filter'] ) AND $this->memberData['_members_cache']['rep_filter'] === '*'">class='selected'</if>>
			<a href='{parse url="app=forums&amp;module=forums&amp;section=topics&amp;rep_filter=update&amp;f={$topic['forum_id']}&amp;t={$topic['tid']}&amp;secure_key={$this->member->form_hash}&amp;st={$this->request['st']}&amp;rep_filter_set=*" base="public"}'>{$this->lang->words['rep_f_all_posts']}</a>
		</li>
		<if test="repFilterOptions:|:is_array($this->caches['reputation_levels'])">
			<foreach loop="reputation_levels:$this->caches['reputation_levels'] as $k => $v">
				<li <if test="optRepFilterSelected:|:isset( $this->memberData['_members_cache']['rep_filter'] ) AND $v['level_points'] == $this->memberData['_members_cache']['rep_filter']">class='selected'</if>>
					<a href='{parse url="app=forums&amp;module=forums&amp;section=topics&amp;rep_filter=update&amp;f={$topic['forum_id']}&amp;t={$topic['tid']}&amp;secure_key={$this->member->form_hash}&amp;st={$this->request['st']}&amp;rep_filter_set={$v['level_points']}" base="public"}' rel='nofollow'>
						{$this->lang->words['rep_f_hide']} <strong>{$v['level_points']} {$this->lang->words['rep_f_points']}</strong>
					</a>
				</li>
			</foreach>
		</if>
	</ul>
	<script type='text/javascript'>
		new ipb.Menu( $('rep_filter'), $('rep_filter_menucontent') );
	</script>
</if>
<div class='topic hfeed clear clearfix'>
	
	{$displayData['poll_data']['html']}
<if test="hasPosts:|:is_array( $post_data ) AND count( $post_data )">
	<div class='ipsBox'>
		<div class='ipsBox_container' id='ips_Posts'>
			<foreach loop="post_data:$post_data as $post">
				{parse template="post" group="topic" params="$post, $displayData, $topic, $forum"}
			</foreach>
		</div>
	</div>
</if>
<hr />
<div class='topic_controls clear ipsPad_top_bottom_half'>
	<if test="$topic['SHOW_PAGES']">
		<div class='left'>{$topic['SHOW_PAGES']}</div>
	</if>
	<div class='ipsPad_top_slimmer right'>
		<if test="modOptions:|:$displayData['mod_links'] AND ( $this->memberData['is_mod'] OR $this->memberData['member_id'] == $topic['starter_id'] )">
			<a href='#' id='topic_mod_options_alt'  class="ipsType_small desc" title='{$this->lang->words['topic_moderation']}'>{$this->lang->words['topic_moderation']}</a> &middot;
		</if>
		<a href='{parse url="showforum={$forum['id']}" template="showforum" seotitle="{$forum['name_seo']}" base="public"}' class="ipsType_small desc">{parse expression="sprintf( $this->lang->words['go_back_to'], $forum['name'] )"}</a>
		<if test="hasUnreadNext:|:$forum['_hasUnreadTopics']">
			&middot; <a href='{parse url="showtopic={$topic['tid']}&amp;view=getnextunread" template="showtopicnextunread" seotitle="{$topic['title_seo']}" base="public"}' class="ipsType_small desc">{$this->lang->words['goto_next_unread_topic']}</a>
		</if>
	</div>	
</div>
<if test="fastReply:|:$displayData['fast_reply'] && $displayData['reply_button']['url']">
<hr />
<div class='ipsBox' id='fast_reply_wrapper'>
	<div class='ipsBox_container ipsPad'>
		<h1 class='ipsType_subtitle'>{$this->lang->words['topic_add_reply']}</h1>
		<if test="isLockedFR:|:$topic['state'] == 'closed'"><span class='error'>{$this->lang->words['locked_reply_fr']}</span><br /></if>
		<br />
		<if test="isMember:|:$this->memberData['member_id']">
			<a href="{parse url="showuser={$this->memberData['member_id']}" seotitle="{$this->memberData['members_seo_name']}" template="showuser" base="public"}" title='{$this->lang->words['your_profile']}' class='ipsUserPhotoLink left'><img src='{$this->memberData['pp_small_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$this->memberData['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_medium' /></a>
		<else />
			<div class='left'>{IPSMember::buildNoPhoto(0, 'small' )}</div>
		</if>
		<div class='ipsBox_withphoto clearfix'>
			<form action="{parse url="" base="public"}" method="post" id='ips_fastReplyForm'>
				<input type="hidden" name="app" value="forums" />
				<input type="hidden" name="module" value="post" />
				<input type="hidden" name="section" value="post" />
				<input type="hidden" name="do" value="reply_post_do" />
				<input type="hidden" name="f" value="{$forum['id']}" />
				<input type="hidden" name="t" value="{$topic['tid']}" />
				<input type="hidden" name="st" value="{$this->request['st']}" />
				<input type="hidden" name="auth_key" value="{$this->member->form_hash}" />
				<input type="hidden" name="fast_reply_used" value="1" />
				<input type="hidden" name="enableemo" value="yes" />
				<input type="hidden" name="enablesig" value="yes" />
				<if test="$this->memberData['auto_track']">
					<input type="hidden" name="enabletrack" value="1" />
				</if>
				<if test="is_array($topic['_fastReplyStatusMessage']) AND count($topic['_fastReplyStatusMessage']) AND strlen($topic['_fastReplyStatusMessage'][0])">
					<div class='message'>{parse expression="implode( '<br />', $topic['_fastReplyStatusMessage'] )"}</div>
				</if>
				{parse editor="Post" options="array( 'type' => 'full', 'minimize' => 1, 'isTypingCallBack' => 'ipb.topic.isTypingCallBack', 'height' => 180, 'autoSaveKey' => 'reply-' . $topic[tid], 'warnInfo' => 'fastReply', 'modAll' => $topic['_fastReplyModAll'] )"}
				<br />
				
				<fieldset class='right' id='fast_reply_controls'>
					<input type='submit' name="submit" class='input_submit' value='{$this->lang->words['qr_post']}' tabindex='3' accesskey='s' id='submit_post' />&nbsp;&nbsp;<input type='submit' name="preview" class='input_submit alt' value='{$this->lang->words['qr_more_opts']}' tabindex='0' id='full_compose' />			
				</fieldset>
			</form>
		</div>
		<div id='ips_HasReplies'></div>
	</div>
</div>
<script type='text/javascript'>
	ipb.topic.fastReplyId	= '{$this->settings['_lastEditorId']}';
</script>
<else />
	<if test="loadJsManually:|:$displayData['load_editor_js']">
		{parse template="editorLoadJs" group="editors" params="$displayData['smilies']"}
	</if>
</if>
<!-- Close topic -->
</div>
<!-- BOTTOM BUTTONS -->
<if test="canShare:|:!$forum['disable_sharelinks'] AND $this->settings['sl_enable']">
	<br />
	<div class='clear clearfix left'>
		{IPSLib::shareLinks( $topic['title'] )}
	</div>
	<br />
</if>
<!-- SAME TAGGED -->
<if test="sameTagged:|:is_array( $displayData['same_tagged'] ) and count( $displayData['same_tagged'] )">
	<br />
	<div class='ipsBox'>
		<h3 class='maintitle'>{parse expression="sprintf( $this->lang->words['topic_same_tagged_as'], $topic['tags']['formatted']['string'] )"}</h3>
		<div class='ipsBox_container'>
			<table class='ipb_table topic_list'>
			<foreach loop="topics:$displayData['same_tagged'] as $tid => $tdata">
				{parse template="topic" group="forum" params="$tdata, $forum, array(), false"}
			</foreach>
			</table>
		</div>
	</div>
</if>
<!-- ACTIVE USERS -->
<if test="topicActiveUsers:|:is_array( $displayData['active_users'] ) AND count( $displayData['active_users'] )">
	<div id='topic_stats' class='statistics clear clearfix'>
		<div id='topic_active_users' class='active_users'>
			<h4 class='statistics_head'>{parse expression="sprintf( $this->lang->words['active_users_title'], $displayData['active_users']['stats']['total'] )"}</h4>
			<p class='statistics_brief desc'>{parse expression="sprintf( $this->lang->words['active_users_detail'], $displayData['active_users']['stats']['members'], $displayData['active_users']['stats']['guests'], $displayData['active_users']['stats']['anon'] )"}</p>
			<if test="auNames:|:is_array( $displayData['active_users']['names'] ) AND count( $displayData['active_users']['names'] )">
				<br />
				<ul class='ipsList_inline'>
					{parse expression="implode( ', ', $displayData['active_users']['names'] )"}
				</ul>
			</if>
		</div>
	</div>
</if>
<if test="scrollToPost:|:$this->request['gopid']">
<script type='text/javascript'>
	var gopid = "{$this->request['gopid']}".replace(/&amp;/g, '');
	ipb.topic.scrollToPost( parseInt( gopid ) );
</script>
</if>
{parse template="include_highlighter" group="global" params="1"}
<div id='multiQuoteInsert' style='display: none;' class='ipsFloatingAction'>
	<span class='ipsButton no_width' id='mqbutton'>{$this->lang->words['mq_reply_quoted_posts']}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' id='multiQuoteClear' class='ipsType_smaller desc' title='{$this->lang->words['mq_clear_desc']}'>{$this->lang->words['mq_clear']}</a> &nbsp;&nbsp;&nbsp;
</div>
<form id="modform" method="post" action="{parse url="" base="public"}">
	<input type="hidden" name="app" value="forums" />
	<input type="hidden" name="module" value="moderate" />
	<input type="hidden" name="section" value="moderate" />
	<input type="hidden" name="do" value="postchoice" />
	<input type="hidden" name="f" value="{$topic['forum_id']}" />
	<input type="hidden" name="t" value="{$topic['tid']}" />
	<input type="hidden" name="auth_key" value="{$this->member->form_hash}" />
	<input type="hidden" name="st" value="{$this->request['st']}" />
	<input type="hidden" value="{$this->request['selectedpids']}" name="selectedpidsJS" id='selectedpidsJS' />
	<input type="hidden" name="tact" id="tact" value="" />
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>