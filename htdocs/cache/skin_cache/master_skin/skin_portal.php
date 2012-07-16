<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: affiliates
//===========================================================================
function affiliates($links="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='ipsSideBlock'>
    <h3>{$this->lang->words['aff_title']}</h3>
    {$this->settings['portal_fav']}
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: articles
//===========================================================================
function articles($articles) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="topic"}
<div class='ipsBox clear'>

    <foreach loop="articles:$articles as $topic">

        <div class='ipsBox_container ipsPad'>        
		
            <a href='{parse url="showuser={$topic['member_id']}" seotitle="{$topic['members_seo_name']}" template="showuser" base="public"}' class='ipsUserPhotoLink left ipsPad_half'><img src='{$topic['pp_small_photo']}' alt='{$r['members_display_name']} {$this->lang->words['photo']}' class='ipsUserPhoto ipsUserPhoto_medium' /></a>
		
            <h2 class='ipsType_pagetitle'><a href='{parse url="showtopic={$topic['tid']}" base="public" seotitle="{$topic['title_seo']}" template="showtopic"}'>{$topic['title']}</a></h2>
            <div class='desc'>{parse date="$topic['start_date']" format="DATE"}</div><br class='clear' />

        
            <div class='desc ipsType_smaller ipsPad_half'>		
                {$this->lang->words['posted_by']} <if test="$topic['members_display_name']">{parse template="userHoverCard" group="global" params="$topic"}<else />{$this->settings['guest_name_pre']}{$topic['starter_name']}{$this->settings['guest_name_suf']}</if>
                {$this->lang->words['in']} <a href='{parse url="showforum={$topic['id']}" base="public" seotitle="{$topic['name_seo']}" template="showforum"}'>{$topic['name']}</a>
            </div>

            <div class='ipsType_textblock ipsPad'>
                {$topic['post']}
                <!--IBF.ATTACHMENT_{$topic['pid']}-->
            </div>
            
        </div>

        <div class='general_box'>
            <h3 class='ipsType_smaller'>
            
                <span class='right'>{$topic['share_links']}</span>

                {parse format_number="$topic['views']"} {$this->lang->words['views']} &middot;
                {parse format_number="$topic['posts']"} {$this->lang->words['replies']}
	
                <if test="entryHasPosts:|:$topic['posts']">
                    ( {$this->lang->words['last_reply_by']} <if test="entryLastPoster:|:$topic['last_poster_id']"><a href='{parse url="showuser={$topic['last_poster_id']}" template="showuser" seotitle="{$topic['seo_last_name']}" base="public"}'>{$topic['last_poster_name']}</a><else />{$this->settings['guest_name_pre']}{$topic['last_poster_name']}{$this->settings['guest_name_suf']}</if> )
                </if>
                	
            </h3>
                        
	</div>

</foreach>
</div><br class='clearfix' />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: latestPosts
//===========================================================================
function latestPosts($topics=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='ipsSideBlock'>
     <h3>{$this->lang->words['discuss_title']}</h3>
		<ul class='ipsList_withminiphoto'>
		<foreach loop="topics_hook:$topics as $r">
		<li class='clearfix'>
			<a href='{parse url="showuser={$r['member_id']}" seotitle="{$r['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'><img src='{$r['pp_mini_photo']}' alt="{$r['members_display_name']}{$this->lang->words['photo']}" class='ipsUserPhoto ipsUserPhoto_mini' /></a>
			<div class='list_content'>
				<a href='{parse url="showtopic={$r['tid']}" base="public" template="showtopic" seotitle="{$r['title_seo']}"}' rel='bookmark' class='ipsType_small' title='{$this->lang->words['view_topic']}'>{$r['topic_title']}</a>
				<p class='desc ipsType_smaller'>
					<if test="$r['members_display_name']">{parse template="userHoverCard" group="global" params="$r"}<else />{$this->settings['guest_name_pre']}{$r['starter_name']}{$this->settings['guest_name_suf']}</if>
					- {parse date="$r['start_date']" format="short"}
				</p>
			</div>
		</li>
		</foreach>
		</ul>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: onlineUsers
//===========================================================================
function onlineUsers($active) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='ipsSideBlock'>
    <h3><a href="{parse url="app=members&amp;module=online&amp;section=online" base="public"}">{$this->lang->words['online_title']}</a></h3>
		<span class='desc'>{parse expression="sprintf( $this->lang->words['online_split'], intval($active['MEMBERS']), intval($active['visitors']), intval($active['ANON']) )"}</span>
		<br /><br />
		<p>
			<span class='name'>{parse expression="implode( ",</span> <span class='name'>", $active['NAMES'] )"}</span>					
		</p>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: pluginPostStatus
//===========================================================================
function pluginPostStatus($active = false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="loadPluginPostStatus:|:$active">
<!-- Load Plugin Post Status -->
<!-- End Load Plugin Post Status -->
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: pluginSuggest
//===========================================================================
function pluginSuggest($active = false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="loadPluginSuggest:|:$active">
<!-- Load Plugin Post Status -->
<!-- End Load Plugin Post Status -->
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: pollWrapper
//===========================================================================
function pollWrapper($content='',$topic=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="topic"}
<div class='ipsSideBlock'>
    <h3><a href='{parse url="showtopic={$topic['tid']}" base="public" seotitle="{$topic['title_seo']}" template="showtopic"}'>{$this->lang->words['poll_title']}</a></h3>
<div class='ipsPad'>
    {$content['html']}</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: poststatusShow
//===========================================================================
function poststatusShow($statuses_output) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='poststatusShow ipsBox'>
	<form id='statusPostForm' action="{parse url="app=portal&amp;module=portal&amp;section=status&amp;do=new&amp;k={$this->member->form_hash}&amp;id={$this->memberData['member_id']}" base="public"}" method='post'>
		<div class='wdthink-attach ipsList_data'>
			<div class='wdthink row_data mentions-input-box'>
			   <textarea class='mention' name='content' id='statusContent'></textarea>
			</div>
			<div class='attach row_data'>
				<input type='button' id='attach' value='{$this->lang->words['poststatus_attachment']}' />
			</div>
		</div>
		<div class='publish'>
			<input type='submit' value='{$this->lang->words['poststatus_submit']}' />
		</div>
	</form>
</div>
<div id='status_standalone_page'>
	<div id="status_wrapper" class='ipsBox'>
		{$statuses_output}
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
// Name: suggestShow
//===========================================================================
function suggestShow( array $members ) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="isArraySuggest:|:is_array($members) && count($members) > 0">
    <div class="clearfix" id="suggestfriends">
        <h3>
            {$this->lang->words['mightknow']}
        </h3>
        <ul class="activity-feed">
	        <foreach loop="$members as $member">
						<script type='text/javascript'>
						//<![CDATA[
							ipb.profile.viewingProfile = parseInt( {$member['member_id']} );
							<if test="$this->memberData['member_id']">
								ipb.templates['add_friend'] = "";
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
	                    <li class="clearfix">
	                        <a href='{parse url="showuser={$member['member_id']}" template="showuser" seotitle="{$member['members_seo_name']}" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink'>
	                           <img src='{$member['pp_thumb_photo']}' alt='{$this->lang->words['photo']}' class='ipsUserPhoto_mini' />
	                        </a>
	                        <div class="activity" style="margin-left: 4px !important; display:inline-block;">
	                            {parse template="userHoverCard" group="global" params="$member"}
	                            <a href="" class="friends">{$member['common']} amigos en com&uacute;n</a>
	                        </div>
	                        <if test="noFriendYourself:|:$this->memberData['member_id'] AND $this->memberData['member_id'] != $member['member_id'] && $this->settings['friends_enabled'] AND $this->memberData['g_can_add_friends']">
	                            <div class="friend_toggle_suggest">
	                            	<if test="isFriend:|:!IPSMember::checkFriendStatus( $member['member_id'] )">
				                        <a class="add-friend" href='{parse url="app=members&amp;section=friends&amp;module=profile&amp;do=add&amp;member_id={$member['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['add_friend']}'>Agregar a mis amigos</a>
	                            	</if>
	                            </div>
	                        </if>
	                    </li>
	        </foreach>
      </ul>
    </div>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showBlocks
//===========================================================================
function showBlocks($blocks=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="showBlocks:|:is_array( $blocks ) AND count( $blocks )">
     <foreach loop="showBlockCode:$blocks as $block">
          <if test="templateHeader:|:$block['template']"><div class='ipsSideBlock clearfix'><h3>{$block['title']}</h3></if>
              {$block['block_code']}
          <if test="templateFooter:|:$block['template']"></div></if>
     </foreach>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showStatus
//===========================================================================
function showStatus($publish) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div id='status_standalone_page'>
	<div id="status_wrapper" class='ipsBox'>
		<if test="hasUpdates:|:count( $publish )">
			{parse template="statusUpdates" group="portal" params="$publish"}
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
// Name: attachLink
//===========================================================================
function attachLink( $propertys ) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class="attachmentMetaArea">
    <div class="clearfix pvm phs composerShareStageWrapper">
         <div>
                <div class="UIShareStage clearfix UIShareStage_HasImage" id="stage50044f0ae376f0115951929">
                        <div class="UIShareStage_Image">
                                <div id="c50044f0ae39859383299776" class="UIShareStage_ThumbPager UIThumbPager">
                                        <div class="UIThumbPager_Thumbs">
                                            <img alt="" src="{$propertys['image']}" class="img" style="width: 100px;">
                                        </div>
                                </div>
                        </div>
                        <div class="UIShareStage_ShareContent">
                                <div class="UIShareStage_Title">
                                    <span><a class="UIShareStage_InlineEdit inline_edit" onclick="new InlineEditor(this, &quot;attachment[params][title]&quot;, $(&quot;stage50044f0ae376f0115951929&quot;), null, false); return false;">{$propertys['title']}</a>
                                    </span>
                                </div>
                                <div class="UIShareStage_Subtitle">{$propertys['url']}</div>
                                <div class="UIShareStage_Summary">
                                        <p class="UIShareStage_BottomMargin">
                                            <a class="UIShareStage_InlineEdit inline_edit" onclick="new InlineEditor(this, &quot;attachment[params][summary]&quot;, $(&quot;stage50044f0ae376f0115951929&quot;), null, true); return false;">{$propertys['description']}</a>
                                        </p>
                                        <div id="c50044f0ae40af8677756064" class="UIShareStage_ThumbPagerControl UIThumbPagerControl UIThumbPagerControl_First UIThumbPagerControl_Last">
                                            <div class="UIThumbPagerControl_Buttons">
                                                <a class="UIThumbPagerControl_Button UIThumbPagerControl_Button_Left"></a><a class="UIThumbPagerControl_Button UIThumbPagerControl_Button_Right"></a>
                                            </div>
                                            <div class="UIThumbPagerControl_Text">
                                                    <span class="UIThumbPagerControl_PageNumber">
                                                        <span class="UIThumbPagerControl_PageNumber_Current">1</span> de <span class="UIThumbPagerControl_PageNumber_Total">1</span>
                                                    </span>Elegir imagen en miniatura
                                            </div>
                                            <div class="uiInputLabel clearfix mts">
                                                    <input type="checkbox" id="utti9i_1" name="no_picture" value="true" class="UIThumbPagerControl_NoPicture uiInputLabelCheckbox">
                                                    <label for="utti9i_1">Sin imagen en miniatura</label>
                                            </div>
                                        </div>
                                </div>
                        </div>
                </div>
         </div>
    </div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: siteNavigation
//===========================================================================
function siteNavigation($links=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='ipsSideBlock'>
        <h3>{$this->lang->words['links_title']}</h3>
	<ul>
		<foreach loop="links:$links as $link">
			<li>&bull; <a href="{$link[1]}">{$link[2]}</a></li>		
		</foreach>
	</ul>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: skeletonTemplate
//===========================================================================
function skeletonTemplate($leftBlocks, $mainBlocks, $rightBlocks) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="disablelightbox:|:!$this->settings['disable_lightbox']">
{parse template="include_lightbox" group="global" params=""}
</if>

<div class='ipsLayout <if test="setupLeftBlockSide:|:is_array( $leftBlocks ) AND count( $leftBlocks )">ipsLayout_withleft ipsLayout_largeleft</if> <if test="setupRightBlockSide:|:is_array( $rightBlocks ) AND count( $rightBlocks )">ipsLayout_withright ipsLayout_largeright</if> clearfix'>
      <div class='ipsLayout_left'>
           {parse template="showBlocks" group="portal" params="$leftBlocks"}
      </div>
      <div class='ipsLayout_content clearfix'>
           {parse template="showBlocks" group="portal" params="$mainBlocks"}
      </div>
      <div class='ipsLayout_right'>
           {parse template="showBlocks" group="portal" params="$rightBlocks"} 
      </div>
</div>

{parse template="include_highlighter" group="global" params="1"}
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: statusUpdates
//===========================================================================
function statusUpdates($status=array(), $smallSpace=0, $latestOnly=0) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="$this->memberData['member_id'] AND $latestOnly AND $status['member_id'] == $this->memberData['member_id']">
<script type="text/javascript">
	ipb.status.myLatest = {$status['id']};
</script>
</if>
<div class='ipsBox_container ipsPad' id='statusWrap-{$status['id']}'>
	<a href='{parse url="showuser={$status['member_id']}" seotitle="{$status['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'>
		<img src='{$status['pp_small_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$status['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_medium' />
	</a>	
	<div class="ipsBox_withphoto status_content">
		<div id="statusContent-{$status['id']}">
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
		<div id="statusFeedback-{$status['status_id']}" class='status_feedback' style='margin-left: -65px;'>
			<if test="$status['status_replies']">
				<if test="hasMore:|:$status['status_replies'] > 3">
					<div class='status_mini_wrap row2 altrow' id='statusMoreWrap-{$status['status_id']}'>
						<img src="{$this->settings['img_url']}/comments.png" alt="" /> &nbsp;<a href="#" id="statusMore-{$status['status_id']}" class='__showAll __x{$status['status_id']}'>{parse expression="sprintf( $this->lang->words['status_show_all_x'], $status['status_replies'] )"}</a>
					</div>
				</if>
				<ul id='statusReplies-{$status['id']}' class='ipsList_withtinyphoto clear'>
					{$status['status_replies']}
				</ul>
			</if>
			<div id='statusReplyBlank-{$status['id']}'></div>
			<div id='statusReply-{$status['id']}'>
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
EOF;
//--endhtml--//
return $IPBHTML;
}


//===========================================================================
// Name: statusUpdates
//===========================================================================
function showPhoto($status=array(), $smallSpace=0, $latestOnly=0) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='ipsBox_container ipsPad' id='statusWrap-{$status['id']}'>
	<a href='{parse url="showuser={$status['member_id']}" seotitle="{$status['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'>
		<img src='{$status['pp_small_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$status['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_medium' />
	</a>
	<div class="ipsBox_withphoto status_content">
		<div id="statusContent-{$status['id']}">
			<h4>
				{parse template="userHoverCard" group="global" params="$status"}
				<if test="forSomeoneElse:|:$status['status_member_id'] != $status['status_author_id']">
					&rarr;
					{parse template="userHoverCard" group="global" params="$status['owner']"}
				</if>
			</h4>
			<div class='status_status'>
				Aquí va una fotografía de nombre: {$status[ 'caption' ]}
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
     </div>
		<div id="statusFeedback-{$status['status_id']}" class='status_feedback'>
			<if test="$status['status_replies']">
				<if test="hasMore:|:$status['status_replies'] > 3">
					<div class='status_mini_wrap row2 altrow' id='statusMoreWrap-{$status['status_id']}'>
						<img src="{$this->settings['img_url']}/comments.png" alt="" /> &nbsp;<a href="#" id="statusMore-{$status['status_id']}" class='__showAll __x{$status['status_id']}'>{parse expression="sprintf( $this->lang->words['status_show_all_x'], $status['status_replies'] )"}</a>
					</div>
				</if>
				<ul id='statusReplies-{$status['id']}' class='ipsList_withtinyphoto clear'>
					{$status['status_replies']}
				</ul>
			</if>
			<div id='statusReplyBlank-{$status['id']}'></div>
			<div id='statusReply-{$status['id']}'>
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
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showLike
//===========================================================================
function showLike($status=array(), $smallSpace=0, $latestOnly=0) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='ipsBox_container ipsPad' id='statusWrap-{$status['id']}'>
	<a href='{parse url="showuser={$status['member_id']}" seotitle="{$status['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'>
		<img src='{$status['pp_small_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$status['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_medium' />
	</a>
	<div class="ipsBox_withphoto status_content">
		<div id="statusContent-{$status['id']}">
			<h4>
				{parse template="userHoverCard" group="global" params="$status"}
				<if test="forSomeoneElse:|:$status['status_member_id'] != $status['status_author_id']">
					&rarr;
					{parse template="userHoverCard" group="global" params="$status['owner']"}
				</if>
			</h4>
			<div class='status_status'>
				A {parse template="userHoverCard" group="global" params="$status"} le gusta una publicación.
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
     </div>
		<div id="statusFeedback-{$status['status_id']}" class='status_feedback'>
			<if test="$status['status_replies']">
				<if test="hasMore:|:$status['status_replies'] > 3">
					<div class='status_mini_wrap row2 altrow' id='statusMoreWrap-{$status['status_id']}'>
						<img src="{$this->settings['img_url']}/comments.png" alt="" /> &nbsp;<a href="#" id="statusMore-{$status['status_id']}" class='__showAll __x{$status['status_id']}'>{parse expression="sprintf( $this->lang->words['status_show_all_x'], $status['status_replies'] )"}</a>
					</div>
				</if>
				<ul id='statusReplies-{$status['id']}' class='ipsList_withtinyphoto clear'>
					{$status['status_replies']}
				</ul>
			</if>
			<div id='statusReplyBlank-{$status['id']}'></div>
			<div id='statusReply-{$status['id']}'>
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
EOF;
//--endhtml--//
return $IPBHTML;
}

}
?>