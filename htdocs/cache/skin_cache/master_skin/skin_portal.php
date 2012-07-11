<?php
/**
 * Master skin file
 * Written: Mon, 09 Jul 2012 18:16:42 +0000
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

//Plugin Templates
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


//Hook Templates

//===========================================================================
// Name: poststatusShow
//===========================================================================
function poststatusShow() {
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
EOF;
//--endhtml--//
return $IPBHTML;
}



}
?>