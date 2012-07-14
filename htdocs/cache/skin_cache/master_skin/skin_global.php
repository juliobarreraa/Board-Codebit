<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: defaultHeader
//===========================================================================
function defaultHeader() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<a href='{$this->settings['board_url']}' title='{$this->lang->words['go_home']}' rel="home" accesskey='1'><img src='{parse replacement="logo_img"}' alt='{$this->lang->words['logo']}' /></a>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: facebookShareButton
//===========================================================================
function facebookShareButton($url, $title) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<li class='fbLike'><div class="fb-like" data-href="{$url}" data-send="false" data-layout="button_count" data-width="150" data-show-faces="false" data-action="like"></div><div id="fb-root"></div></li>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/{$this->settings['fb_locale']}/all.js#xfbml=1&appId={$this->settings['fbc_appid']}";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: forum_jump
//===========================================================================
function forum_jump($html) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form id='forum_jump' action='{parse url="" base="public"}' method='get'>
		<fieldset>
			<select name='showforum' id='showforumJump' class='input_select'>
				<optgroup label="{$this->lang->words['forum_jump']}">
					<option value='0'>{$this->lang->words['forum_home']}</option>
					{$html}
				</optgroup>
			</select>
			<if test="$this->member->session_type != 'cookie'"><input type='hidden' name='s' value='{$this->member->session_id}' /></if>
			<input type='submit' value='{$this->lang->words['jmp_go']}' class='input_submit alt' id='forum_jump_submit' />
		</fieldset>
	</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: globalTemplate
//===========================================================================
function globalTemplate($html, $documentHeadItems, $css, $jsModules, $metaTags, array $header_items, $items=array(), $footer_items=array(), $stats=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<!DOCTYPE html>
	<html lang="en" <if test="fbcenabled:|:IPSLib::fbc_enabled() === TRUE || $this->settings['fbc_appid']"> xmlns:fb="http://www.facebook.com/2008/fbml"</if>>
	<head>
		<meta charset="{$this->settings['gb_char_set']}" />
		<title>{$header_items['title']}</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<link rel="shortcut icon" href='<if test="$this->registry->output->isHTTPS">{$this->settings['board_url_https']}<else />{$this->settings['board_url']}</if>/favicon.ico' />
		<link rel="image_src" href='{$this->settings['meta_imagesrc']}' />
		<script type='text/javascript'>
		//<![CDATA[
			jsDebug			= {parse expression="intval($this->settings['_jsDebug'])"}; /* Must come before JS includes */
			USE_RTE			= 1;
			DISABLE_AJAX	= parseInt({$this->settings['disable_text_ajax']}); /* Disables ajax requests where text is sent to the DB; helpful for charset issues */
			inACP			= false;
			var isRTL		= false;
			var rtlIe		= '';
			var rtlFull		= '';
		//]]>
		</script>
		{parse template="includeCSS" group="global" params="$css"}
		<meta property="og:title" content="{$this->registry->output->encodeMetaTagContent( str_replace( ' - ' . $this->settings['board_name'], '', $header_items['title'] ) )}"/>
		<meta property="og:site_name" content="{$this->registry->output->encodeMetaTagContent( $this->settings['board_name'] )}"/>
		<meta property="og:image" content="{$this->settings['meta_imagesrc']}"/>
		<meta property="og:type" content="article" />
		{parse template="includeMeta" group="global" params="$metaTags"}
		<if test="isLargeTouch:|:$this->registry->output->isLargeTouchDevice()">
		<!--<meta name="viewport" content="width=device-width;">-->
		</if>
		<if test="isSmallTouch:|:$this->registry->output->isSmallTouchDevice()">
		<meta name="viewport" content="width=1024px">
		</if>
		{parse template="includeJS" group="global" params="$jsModules"}
		{parse template="includeFeeds" group="global" params="$documentHeadItems"}
		{parse template="includeRTL" group="global" params=""}		
		{parse template="includeVars" group="global" params="$header_items"}
	</head>
	<body id='ipboard_body'>
		<p id='content_jump' class='hide'><a id='top'></a><a href='#j_content' title='{$this->lang->words['jump_to_content']}' accesskey='m'>{$this->lang->words['jump_to_content']}</a></p>
		<div id='ipbwrapper'>
			<!-- ::: TOP BAR: Sign in / register or user drop down and notification alerts ::: -->
			<div id='header_bar' class='clearfix'>
				<div class='main_width'>
					<if test="accessreports:|:$this->memberData['is_mod'] OR !empty($this->memberData['access_report_center']) || ($this->memberData['g_access_cp'] AND !$this->settings['security_remove_acp_link'])">
						<ul id='admin_bar' class='ipsList_inline left'>
							<if test="showacplink:|:$this->memberData['g_access_cp'] AND !$this->settings['security_remove_acp_link']">
								<li>
									<a href="{$this->settings['_admin_link']}" title='{$this->lang->words['admin_cp']}' target="_blank">{$this->lang->words['login_to_acp']}</a>
								</li>
							</if>
							<li><a href="{parse url="app=core&amp;module=modcp" base="public"}" title='{$this->lang->words['gbl_modcp_link']}'>{$this->lang->words['gbl_modcp_link']}</a></li>
							<if test="rclink:|:$this->memberData['access_report_center'] && $this->memberData['_cache']['report_num'] > 0">
								<li class='active'>
									<a href="{parse url="app=core&amp;module=reports&amp;do=index" base="public"}" title='{$this->lang->words['view_reports']}'>{$this->memberData['_cache']['report_num']} {$this->lang->words['report_member_bar']}</a>
								</li>
							</if>
						</ul>
					</if>
					<if test="memberbox:|:$this->memberData['member_id']">
						<div id='user_navigation' class='logged_in'>
							<ul class='ipsList_inline right'>
								<if test="showInboxNotify:|:! ( ! $this->memberData['member_id'] && $this->settings['force_login'] ) && !($this->settings['board_offline'] && !$this->memberData['g_access_offline'])">
									<if test="messengerlink:|:$this->memberData['g_use_pm'] AND $this->memberData['members_disable_pm'] == 0">
										<li><a data-clicklaunch="getInboxList" id='inbox_link' href='{parse url="app=members&amp;module=messaging" base="public"}' title='{$this->lang->words['your_messenger']}'><if test="notifications:|:$this->memberData['msg_count_new']"><span class='ipsHasNotifications'>{$this->memberData['msg_count_new']}</span></if>&nbsp;</a></li>
									</if>
									<li><a data-clicklaunch="getNotificationsList" id='notify_link' href="{parse url="app=core&amp;module=usercp&amp;area=notificationlog" base="public"}" title="{$this->lang->words['notifications_at_the_top']}"><if test="notifications:|:$this->memberData['notification_cnt']"><span class='ipsHasNotifications'>{$this->memberData['notification_cnt']}</span></if>&nbsp;</a></li>
								</if>
								<li><a id='user_link' href="{parse url="showuser={$this->memberData['member_id']}" seotitle="{$this->memberData['members_seo_name']}" template="showuser" base="public"}"  title='{$this->lang->words['your_profile']}'>{$this->memberData['members_display_name']} &nbsp;<span id='user_link_dd'></span></a></li>							
								<li><a href="{parse url="app=core&amp;module=global&amp;section=login&amp;do=logout&amp;k={$this->member->form_hash}" base="public"}">{$this->lang->words['log_out']}</a></li>								
								<if test="authenticating:|:$this->memberData['member_group_id'] == $this->settings['auth_group']">
									<li>&nbsp;&nbsp;&nbsp;<a href="{parse url="app=core&amp;module=global&amp;section=register&amp;do=reval" base="public"}" title='{$this->lang->words['resend_val']}'>{$this->lang->words['resend_val']}</a></li>
								</if>
							</ul>
						</div>
						<!-- ::: USER DROP DOWN MENU ::: -->
						<div id='user_link_menucontent' class='ipsHeaderMenu clearfix boxShadow' style='display: none'>
							<a href="{parse url="showuser={$this->memberData['member_id']}" seotitle="{$this->memberData['members_seo_name']}" template="showuser" base="public"}" title='{$this->lang->words['your_profile']}' class='ipsUserPhotoLink left'>
								<img src='{$this->memberData['pp_small_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$this->memberData['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_medium photo' />
							</a>
							
							<div class='left'>
								<if test="canUpdateStatus:|:$this->registry->getClass('memberStatus')->canCreate( $this->memberData ) && !($this->settings['board_offline'] && !$this->memberData['g_access_offline'])">
									<form id='statusForm' action='{$this->settings['base_url']}app=members&amp;module=profile&amp;section=status&amp;do=new&amp;k={$this->member->form_hash}&amp;id={$this->memberData['member_id']}' method='post'>
										<input type='text' id='statusUpdateGlobal' name='content' class='input_text' style='width: 97%' /><br />
										<input type='submit' id='statusSubmitGlobal' class='ipsButton_secondary' value='{$this->lang->words['global_update_status']}' />
										<if test="update:|:(IPSLib::twitter_enabled() OR IPSLib::fbc_enabled() ) AND ( $this->memberData['fb_uid'] OR $this->memberData['twitter_id'] )">
											&nbsp;&nbsp;
											<if test="updateTwitter:|:IPSLib::twitter_enabled() AND ( $this->memberData['twitter_id'] )"><input type='checkbox' id='su_TwitterGlobal' value='1' name='su_Twitter' /> <label for='su_TwitterGlobal' class='desc ipsType_smaller'>Twitter</label></if> &nbsp; 
											<if test="updateFacebook:|:IPSLib::fbc_enabled() AND ( $this->memberData['fb_uid'] )">&nbsp;<input type='checkbox' id='su_FacebookGlobal' value='1' name='su_Facebook' /> <label for='su_FacebookGlobal' class='desc ipsType_smaller'>Facebook</label></if>
										</if>
										<br />
										<hr />
									</form>
								</if>
									<ul id='links'>
										<li id='user_profile'><a href='{parse url="showuser={$this->memberData['member_id']}" seotitle="{$this->memberData['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['your_profile']}'>{$this->lang->words['my_profile']}</a></li>
										<li id='user_ucp'><a href="{parse url="app=core&amp;module=usercp" base="public"}" title="{$this->lang->words['cp_tool_tip']}">{$this->lang->words['your_cp']}</a></li>
										<li id='user_content'><a href="{parse url="app=core&amp;module=search&amp;do=user_activity&amp;mid={$this->memberData['member_id']}" base="public"}" title="{$this->lang->words['my_content_link']}">{$this->lang->words['my_content_link']}</a></li>
										<if test="userLikeLink:|:count( IPSLib::getEnabledApplications('like') )">
											<li id='user_likes'><a href='{parse url="app=core&amp;module=search&amp;do=followed" base="public"}' title='{$this->lang->words['your_likes']}'>{$this->lang->words['your_likes']}</a></li>
										</if>
										<if test="nobbyNoMates:|:$this->settings['friends_enabled'] && $this->memberData['g_can_add_friends']">
											<li id='user_friends'><a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=list" base="public"}' title="{$this->lang->words['manage_friends']}" class='manage_friends'>{$this->lang->words['manage_friends']}</a></li>
										</if>
										<li id='user_enemies'><a href='{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=ignoredusers" base="public"}' title="{$this->lang->words['manage_ignored_users']}" class='manage_enemies'>{$this->lang->words['manage_ignored_users']}</a></li>
										<if test="bloglink:|:$this->memberData['has_blog'] AND IPSLib::appIsInstalled('blog')">
											<li id='user_blog'><a href="{parse url="app=blog&amp;module=manage" base="public" template="manageblog" seotitle="false"}">{$this->lang->words['manage_blogs']}</a></li>
										</if>
										<if test="pmLink:|:$this->memberData['members_disable_pm'] != 2">
											<li id='user_pm'><a href="{parse url="app=members&module=messaging" base="public"}">{$this->lang->words['user_dd_go_pm']}</a></li>
										</if>
										<if test="gallerylink:|:$this->memberData['has_gallery'] AND IPSLib::appIsInstalled('gallery')">
											<li id='user_gallery'><a href='{parse url="app=gallery&amp;user={$this->memberData['member_id']}" base="public" seotitle="{$this->memberData['members_seo_name']}" template="useralbum"}' title="{$this->lang->words['go_to_my_gallery']}">{$this->lang->words['my_gallery']}</a></li>
										</if>
										<if test="nexuslink:|:IPSLib::appIsInstalled('nexus')">
											<li id='user_nexus'><a href='{parse url="app=nexus&amp;module=clients" base="public"}' title="{$this->lang->words['client_area']}">{$this->lang->words['client_area']}</a></li>
										</if>
									</ul>
							</div>
						</div>
					<else />
						<div id='user_navigation' class='not_logged_in'>
							
							<ul class='ipsList_inline right'>
								<li>
									<span class='services'>
										<if test="limFacebook:|:IPSLib::loginMethod_enabled('facebook')">
											<a href='{parse url="app=core&amp;module=global&amp;section=login&amp;serviceClick=facebook" base="public"}'>{parse replacement="lim_facebook"}</a>
										</if>
										<if test="limTwitter:|:IPSLib::loginMethod_enabled('twitter')">
											<a href='{parse url="app=core&amp;module=global&amp;section=login&amp;serviceClick=twitter" base="public"}'>{parse replacement="lim_twitter"}</a>
										</if>
										<if test="limWindows:|:IPSLib::loginMethod_enabled('live')">
											<a href='{parse url="app=core&amp;module=global&amp;section=login&amp;do=process&amp;use_live=1&amp;auth_key={$this->member->form_hash}" base="public"}'>{parse replacement="lim_windows"}</a>
										</if>
									</span>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<a href='{parse url="app=core&amp;module=global&amp;section=login" base="public"}' title='{$this->lang->words['sign_in']}' id='sign_in'>{$this->lang->words['sign_in']}</a>&nbsp;&nbsp;&nbsp;
								</li>
								<li>
									<a href="{parse url="app=core&amp;module=global&amp;section=register" base="public"}" title='{$this->lang->words['register']}' id='register_link'>{$this->lang->words['register']}</a>
								</li>
							</ul>
						</div>
					</if>
				</div>
			</div>
			<!-- ::: BRANDING STRIP: Logo and search box ::: -->
			<div id='branding'>
				<div class='main_width'>
					<div id='logo'>
						<if test="brandingBar:|:ipsRegistry::$applications[ $this->registry->getCurrentApplication() ]['hasCustomHeader']">
							{parse template="overwriteHeader" group="{current_app}_global" params=""}
						<else />
							{parse template="defaultHeader" group="global" params=""}
						</if>
					</div>
					<if test="canSearch:|:$this->memberData['g_use_search']">
						{parse template="quickSearch" group="global" params=""}
					</if>
				</div>
			</div>
			<!-- ::: APPLICATION TABS ::: -->
			<div id='primary_nav' class='clearfix'>
				<div class='main_width'>
					<ul class='ipsList_inline' id='community_app_menu'>
						<if test="showQuickNav:|:! ( ! $this->memberData['member_id'] && $this->settings['force_login'] ) && !($this->settings['board_offline'] && !$this->memberData['g_access_offline']) && $this->memberData['g_view_board']">
							<li class='right'>
								<a href="{parse url="app=core&amp;module=global&amp;section=navigation&amp;inapp={parse expression="IPS_APP_COMPONENT"}" base="public"}" rel="quickNavigation" accesskey='9' id='quickNavLaunch' title='{$this->lang->words['launch_quicknav']}'><span>&nbsp;</span></a>
							</li>
						</if>
						<li id='nav_explore' class='right'>
							<a href='{parse url="app=core&amp;module=search&amp;do=viewNewContent&amp;search_app=<if test="viewnewcontentapp:|:$this->registry->getCurrentApplication() != 'core' AND IPSLib::appIsSearchable( $this->registry->getCurrentApplication() )">{$this->registry->getCurrentApplication()}<else />forums</if>" base="public"}' accesskey='2'>{$this->lang->words['view_new_posts']}</a>
						</li>
						<if test="showhomeurl:|:$this->settings['home_url'] AND $this->settings['home_name']">
							<li id='nav_home' class='left'><a href='{$this->settings['home_url']}' title='{$this->lang->words['homepage_title']}' rel="home">{$this->settings['home_name']}</a></li>
						</if>
						<if test="hasCustomPrimaryNavigation:|:!empty($header_items['primary_navigation_menu'])">
							{$header_items['primary_navigation_menu']}
						<else />
							<if test="applicationsloop:|:is_array($header_items['applications']) AND count($header_items['applications'])">
								<foreach loop="applications:$header_items['applications'] as $data">
									<if test="showingapp:|:$data['app_show']">
										{parse variable="appActive" default="" oncondition="$data['app_active']" value="active"}
										<li id='nav_app_{$data['app_dir']}' class="left {parse variable="appActive"}"><a href='{parse url="{$data['app_link']}" seotitle="{$data['app_seotitle']}" template="{$data['app_template']}" base="{$data['app_base']}"}' title='{parse expression="sprintf( $this->lang->words['go_to_prefix'], IPSLib::getAppTitle($data['app_dir']) )"}'>{IPSLib::getAppTitle($data['app_dir'])}</a></li>
									</if>
								</foreach>
							</if>
						</if>
						<li id='nav_other_apps' style='display: none'>
							<a href='#' class='ipbmenu' id='more_apps'>{$this->lang->words['more_apps']} <img src='{$this->settings['img_url']}/useropts_arrow.png' /></a>
						</li>
					</ul>
				</div>
				<script type='text/javascript'>
					if( $('primary_nav') ){	ipb.global.activateMainMenu(); }
				</script>
			</div>	
			
			<!-- ::: MAIN CONTENT AREA ::: -->
			<div id='content' class='clearfix'>
				<!-- ::: NAVIGATION BREADCRUMBS ::: -->
				<if test="count( $items['navigation'] )">
					<div id='secondary_navigation' class='clearfix'>
						<ol class='breadcrumb top ipsList_inline left'>
							<php>$this->did_first = 0;</php>
							<if test="switchnavigation:|:!$this->settings['remove_forums_nav'] OR ipsRegistry::$current_application == 'forums'">
								<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
									<a href='{parse url="act=idx" seotitle="false" base="public"}' itemprop="url">
										<span itemprop="title">{$this->settings['board_name']}</span>
									</a>
								</li>
								<if test="didfirstnav:|:$this->did_first=1"></if>
							</if>
							<foreach loop="navigation:$items['navigation'] as $idx => $data">
								<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
									<if test="didfirstappnow:|:$this->did_first"><span class='nav_sep'>{parse replacement="f_nav_sep"}</span></if>
									 <if test="navigationlink:|:$data[1]"><a href='{parse url="{$data[1]}" base="$data[4]" seotitle="$data[2]" template="$data[3]"}' title='{$this->lang->words['nav_return_to']} {$data[0]}' itemprop="url"></if><span itemprop="title">{$data[0]}</span><if test="closenavigationlink:|:$data[1]"></a></if>
								</li>
								<if test="forsuredidfirstnav:|:$this->did_first=1"></if>
							</foreach>
						</ol>
					</div>
					<br />
				</if>
				<noscript>
					<div class='message error'>
						<strong>{$this->lang->words['gbl_no_js_title']}</strong>
						<p>{$this->lang->words['gbl_no_js']}</p>
					</div>
					<br />
				</noscript>
				<!-- ::: CONTENT ::: -->
				<if test="hasHeaderAd:|:$items['adHeaderCode']">
					<div class='ipsAd'>{$items['adHeaderCode']}</div>
				</if>
				<if test="mainpageContent:|:$html">{$html}</if>
				<if test="hasFooterAd:|:$items['adFooterCode']">
					<div class='ipsAd'>{$items['adFooterCode']}</div>
				</if>
				<ol class='breadcrumb bottom ipsList_inline left clearfix clear'>
					<if test="count( $items['navigation'] )">
						<php>$this->did_first_bottom = 0;</php>
						<if test="switchnavigation:|:!$this->settings['remove_forums_nav'] OR ipsRegistry::$current_application == 'forums'">
							<li><a href='{parse url="act=idx" seotitle="false" base="public"}'>{$this->settings['board_name']}</a></li>
							<if test="didfirstnav:|:$this->did_first_bottom=1"></if>
						</if>
						<foreach loop="navigation:$items['navigation'] as $idx => $data">
							<li><if test="didfirstappnow:|:$this->did_first_bottom"><span class='nav_sep'>{parse replacement="f_nav_sep"}</span></if> <if test="navigationlink:|:$data[1]"><a href='{parse url="{$data[1]}" base="$data[4]" seotitle="$data[2]" template="$data[3]"}' title='{$this->lang->words['nav_return_to']} {$data[0]}'></if>{$data[0]}<if test="closenavigationlink:|:$data[1]"></a></if></li>
							<if test="forsuredidfirstnav:|:$this->did_first_bottom=1"></if>
						</foreach>
					<else />
						<li>&nbsp;</li>
					</if>
					<if test="privvy:|:$this->settings['priv_title']">
					<li class='right ipsType_smaller'>
						<a rel="nofollow" href='{parse url="app=core&amp;module=global&amp;section=privacy" template="privacy" seotitle="false" base="public"}'>{$this->settings['priv_title']}</a>
					</li>
					</if>
					<if test="siteruleslink:|:$this->settings['gl_show'] and $this->settings['gl_title']">
						<li class='right ipsType_smaller'>
							<a href='<if test="ruleslink:|:$this->settings['gl_link']">{$this->settings['gl_link']}<else />{parse url="app=forums&amp;module=extras&amp;section=boardrules" base="public"}</if>'><if test="siterulestitle:|:$this->settings['gl_title']">{$this->settings['gl_title']}<else />{$this->lang->words['board_rules']}</if></a><if test="privvyMiddot:|:$this->settings['priv_title']"> &middot; </if>
						</li>
					</if>	
				</ol>
			</div>
			<!-- ::: FOOTER (Change skin, language, mark as read, etc) ::: -->
			<div id='footer_utilities' class='main_width clearfix clear'>
				<a rel="nofollow" href='#top' id='backtotop' title='{$this->lang->words['go_to_top']}'><img src='{$this->settings['img_url']}/top.png' alt='' /></a>
				<ul class='ipsList_inline left'>
					<li>
						<img src='{$this->settings['img_url']}/feed.png' alt='{$this->lang->words['rss_feed']}' id='rss_feed' class='clickable' />
					</li>
					<if test="skinchangerOuter:|: ! $this->member->is_not_human">
						<if test="uagentlocked:|:$this->memberData['userAgentLocked'] AND ! $this->memberData['userAgentBypass']">
							<li id='useragent_msg'>
								{$this->lang->words['skin_browser_set']} <a href='#' data-clicklaunch='changeSkin' data-skinid='unlockUserAgent'>{$this->lang->words['override_browser_theme']}</a>
							</li>
						<else />
						<if test="isTouchDevice:|:$this->registry->output->isTouchDevice()">
							<li>
								<a href='#' data-clicklaunch='changeSkin' data-skinid='setAsMobile'>{$this->lang->words['set_mobile_theme']}</a>
							</li>
						</if>
							<if test="skinchangerInner:|:count($footer_items['skin_chooser']) > 1">
								<li>
									<a rel="nofollow" id='new_skin' href='#'>{$this->lang->words['change_theme']}</a>			
									<ul id='new_skin_menucontent' class='ipbmenu_content with_checks' style='display: none'>
										<foreach loop="$footer_items['skin_chooser'] as $skin">
											<li <if test="$skin['selected']">class='selected'</if>>
												<a href='#' data-clicklaunch='changeSkin' data-skinid='{$skin['id']}'>{$skin['title']}</a>
											</li>
										</foreach>
									</ul>
								</li>
							</if>
						</if>				
					</if>
					<if test="langchooser:|: ! $this->member->is_not_human && count( $footer_items['lang_chooser']['options'] ) > 1">
						<li>
							<a rel="nofollow" href='#' id='new_language'>{$footer_items['lang_chooser']['default']}</a>							
							<ul id='new_language_menucontent' class='ipbmenu_content with_checks' style='display: none'>
								<foreach loop="$footer_items['lang_chooser']['options'] as $lang">
									<li <if test="$lang['selected']">class='selected'</if>>
										<a href="{parse url="{$this->settings['query_string_formatted']}&amp;k={$this->member->form_hash}&amp;setlanguage=1&amp;langurlbits={$this->settings['query_string_safe']}&amp;cal_id={$this->request['cal_id']}&amp;langid={$lang['id']}" base="public"}">{$lang['title']}</a>
									</li>
								</foreach>
							</ul>
						</li>
					</if>
					<if test="markRead:|: ! $this->member->is_not_human">
						<li>
							<a rel="nofollow" id='mark_all_read' href="{parse url="app=forums&amp;module=forums&amp;section=markasread&amp;marktype=all&amp;k={$this->member->form_hash}" base="public"}" title='{$this->lang->words['mark_all_as_read']}'>{$this->lang->words['mark_board_as_read']}</a>
							<ul id='mark_all_read_menucontent' class='ipbmenu_content' style='display: none'>
								<foreach loop="$footer_items['mark_read_apps'] as $app => $appData">
									<li>
										<if test="hideRcForPerms:|:$this->memberData['showReportCenter'] OR $app != 'core'">
											<a href="{parse url="app=forums&amp;module=forums&amp;section=markasread&amp;marktype=app&amp;markApp={$app}&amp;k={$this->member->form_hash}" base="public"}"><if test="isCoreRC:|:$app=='core'">{$this->lang->words['markread_rc_link']}<else />{IPSLib::getAppTitle($app)}</if></a>
										</if>
									</li>
								</foreach>
								<li>
									<a href="{parse url="app=forums&amp;module=forums&amp;section=markasread&amp;marktype=all&amp;k={$this->member->form_hash}" base="public"}"><strong>{$this->lang->words['mark_all_as_read']}</strong></a>
								</li>
							</ul>
						</li>
					</if>
					<li>
						<a href="{parse url="app=core&amp;module=help" base="public"}" title='{$this->lang->words['view_help']}' rel="help" accesskey='6'>{$this->lang->words['sj_help']}</a>
					</li>				
				</ul>
				{$footer_items['copyright']}
			</div>
			<if test="showdebuglevel:|:$this->memberData['member_id'] and $this->settings['debug_level']">
				<div id='ipsDebug_footer'>
					<strong>{$this->lang->words['time_now']}</strong> {$footer_items['time']}
					<if test="lastvisit:|:$this->memberData['member_id'] AND $this->memberData['last_visit']"><strong>{$this->lang->words['you_last_visit']}</strong> {parse date="$this->memberData['last_visit']" format="short"}</if>
					<strong>{$this->lang->words['stat_exec']}</strong> {$stats['ex_time']} <if test="isfloat:|:is_float($stats['ex_time'])">{$this->lang->words['stats_sec']}</if>
					<strong>{$this->lang->words['stat_load']}</strong> {$stats['server_load']}
					<strong>{$this->lang->words['stat_queries']}</strong> {$stats['queries']} <if test="sqldebuglink:|:IPS_SQL_DEBUG_MODE"><a href='{parse url="{$this->settings['query_string_safe']}&amp;debug=1" base="public"}'></if>{$this->lang->words['stats_queries']}<if test="closesqldebuglink:|:IPS_SQL_DEBUG_MODE"></a></if>
					<strong>{$this->lang->words['stat_gzip']}</strong> {$stats['gzip_status']}
				</div>
			</if>
			{$stats['task']}
			<if test="includeLightboxDoReal:|:$this->settings['do_include_lightbox_real']">
				{parse template="include_lightbox_real" group="global" params=""}
			<else />
				<script type="text/javascript">
					ipb.global.lightBoxIsOff();
				</script>
			</if>
			<if test="!$this->memberData['member_id']">
				{parse template="inlineLogin" group="global" params=""}
			</if>
		</div>
		
		<!--DEBUG_STATS-->
	</body>
</html>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: googlePlusOneButton
//===========================================================================
function googlePlusOneButton($url, $title, $params) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
	<if test="!empty( $params ) and isset( $params['lang'] )">
		{lang: '{$params['lang']}'}
	</if>
</script>
<li><div class='googlePlusOne'><g:plusone count="false" size="small"></g:plusone></div></li>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: include_highlighter
//===========================================================================
function include_highlighter($load_when_needed=0) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse addtohead="{$this->settings['css_base_url']}style_css/prettify.css" type="importcss"}
	<script type="text/javascript" src="{$this->settings['js_base_url']}js/3rd_party/prettify/prettify.js"></script>
	<script type='text/javascript' src='{$this->settings['js_base_url']}js/3rd_party/prettify/lang-sql.js'></script>
	<!-- By default we load generic code, php, css, sql and xml/html; load others here if desired -->
	<script type="text/javascript">
	//<![CDATA[
		Event.observe( window, 'load', function(e){ prettyPrint() });
	//]]>
	</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: include_lightbox
//===========================================================================
function include_lightbox() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>$this->settings['do_include_lightbox_real'] = 1;</php>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: include_lightbox_real
//===========================================================================
function include_lightbox_real() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/lightbox.js'></script>
<script type='text/javascript'>
//<![CDATA[
	// Lightbox Configuration
	LightboxOptions = Object.extend({
	    fileLoadingImage:        '{$this->settings['img_url']}/lightbox/loading.gif',     
	    fileBottomNavCloseImage: '{$this->settings['img_url']}/lightbox/closelabel.gif',
	    overlayOpacity: 0.8,   // controls transparency of shadow overlay
	    animate: true,         // toggles resizing animations
	    resizeSpeed: 7,        // controls the speed of the image resizing animations (1=slowest and 10=fastest)
	    borderSize: 10,         //if you adjust the padding in the CSS, you will need to update this variable
		// When grouping images this is used to write: Image # of #.
		// Change it for non-english localization
		labelImage: "{$this->lang->words['lightbox_label']}",
		labelOf: "{$this->lang->words['lightbox_of']}"
	}, window.LightboxOptions || {});
/* Watch for a lightbox image and set up our downloadbutton watcher */
document.observe('click', (function(event){
    var target = event.findElement('a[rel*="lightbox"]') || event.findElement('area[rel*="lightbox"]') || event.findElement('span[rel*="lightbox"]');
    if (target) {
        event.stop();
        gbl_addDownloadButton();
    }
}).bind(this));
var _to    = '';
var _last  = '';
function gbl_addDownloadButton()
{
	if ( typeof( ipsLightbox.lightboxImage ) != 'undefined' && ipsLightbox.lightboxImage.src )
	{
		if ( _last != ipsLightbox.lightboxImage.src )
		{
			if ( ! $('gbl_d') )
			{
				$('bottomNav').insert( { top: "<div id='gbl_d' style='text-align:right;padding-bottom:4px;'></div>" } );
			}
			
			$('gbl_d').update( "<a href='"+ ipsLightbox.lightboxImage.src + "' target='_blank'><img src='{$this->settings['img_url']}/lightbox/download-icon.png' /></a>" );
			
			_last = ipsLightbox.lightboxImage.src;
		}
	}
	
	/* Check for init and then keep checking for new image */
	_to = setTimeout( "gbl_addDownloadButton()", 1000 );
}
//]]>
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: includeCSS
//===========================================================================
function includeCSS($css) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="hasimportcss:|:is_array( $css['import'] )">
	<if test="minifycss:|:$this->settings['use_minify']"><php>$this->minify = array();</php></if>
	<foreach loop="cssImport:$css['import'] as $data">
		<php>
			$importCss = true;
			if( stripos( $data['content'], $this->settings['css_base_url'] ) === 0 && $this->settings['use_minify'] AND ( ! $data['attributes'] OR stripos( $data['attributes'], 'screen' ) !== false ) )
			{
				$importCss		= false;
				$this->minify[]	= "{$data['content']}";
			}
		</php>
		<if test="donotminifycss:|:$importCss">
			<link rel="stylesheet" type="text/css" {$data['attributes']} href="{$data['content']}?ipbv={$this->registry->output->antiCacheHash}" />
		</if>
	</foreach>
	<if test="csstominify:|:$this->settings['use_minify'] AND count($this->minify)">
		<link rel="stylesheet" type="text/css" media='screen,print' href="{$this->settings['css_base_url']}min/index.php?ipbv={$this->registry->output->antiCacheHash}&amp;f={parse expression="str_replace( $this->settings['css_base_url'], PUBLIC_DIRECTORY . '/', implode( ',', $this->minify ) )"}" />
	</if>
</if>
<if test="inlinecss:|:is_array( $css['inline'] ) AND count( $css['inline'] )">
	<foreach loop="cssInline:$css['inline'] as $data">
	<style type="text/css" {$data['attributes']}>
		/* Inline CSS */
		{$data['content']}
	</style>
	</foreach>
</if>
<!--[if lte IE 7]>
	<link rel="stylesheet" type="text/css" title='Main' media="screen" href="{$this->settings['css_base_url']}style_css/{$this->registry->output->skin['_csscacheid']}/ipb_ie.css" />
<![endif]-->
<!--[if lte IE 8]>
	<style type='text/css'>
		.ipb_table { table-layout: fixed; }
		.ipsLayout_content { width: 99.5%; }
	</style>
<![endif]-->
<if test="$this->settings['resize_img_force']">
	<!-- Forces resized images to an admin-defined size -->
	<style type='text/css'>
		img.bbc_img {
			max-width: {$this->settings['resize_img_force']}px !important;
			max-height: {$this->settings['resize_img_force']}px !important;
		}
	</style>
<else />
	<style type='text/css'>
		img.bbc_img { max-width: 100% !important; }
	</style>
</if>
<link href='https://fonts.googleapis.com/css?family=PT+Sans&subset=latin' rel='stylesheet' type='text/css' />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: includeFeeds
//===========================================================================
function includeFeeds($documentHeadItems) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="hasdocheaditems:|:count($documentHeadItems)">
	<foreach loop="headitemsType:$documentHeadItems as $type => $idx">
		<foreach loop="documentHeadItems:$documentHeadItems[ $type ] as $idx => $data">
			<if test="dhjavascript:|:$type == 'javascript'">
				<script type="text/javascript" src="{$data}" charset="<% CHARSET %>"></script>
			</if>
			<if test="dhcss:|:$type == 'rss'">
				<link rel="alternate" type="application/rss+xml" title="{$data['title']}" href="{$data['url']}" />
			</if>
			<if test="dhrsd:|:$type == 'rsd'">
				<link rel="EditURI" type="application/rsd+xml" title="{$data['title']}" href="{$data['url']}" />
			</if>
			<if test="dhraw:|:$type == 'raw'">
				{$data}
			</if>
		</foreach>
	</foreach>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: includeJS
//===========================================================================
function includeJS($jsModules) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="minifyjs:|:$this->settings['use_minify']">
	<if test="remoteloadjs:|:$this->settings['remote_load_js']">
		<script type='text/javascript' src='<if test="usehttpsprototype:|:$this->registry->output->isHTTPS">https<else />http</if>://ajax.googleapis.com/ajax/libs/prototype/1.7/prototype.js'></script>
		<script type='text/javascript' src='<if test="usehttpsscriptaculous:|:$this->registry->output->isHTTPS">https<else />http</if>://ajax.googleapis.com/ajax/libs/scriptaculous/1.8/scriptaculous.js?load=effects,dragdrop,builder'></script>
	<else />
		<script type='text/javascript' src='{$this->settings['js_base_url']}min/index.php?ipbv={$this->registry->output->antiCacheHash}&amp;g=js'></script>
	</if>
	<script type='text/javascript' src='{$this->settings['js_base_url']}min/index.php?ipbv={$this->registry->output->antiCacheHash}&amp;charset={$this->settings['gb_char_set']}&amp;f={parse expression="PUBLIC_DIRECTORY"}/js/ipb.js,cache/lang_cache/{$this->lang->lang_id}/ipb.lang.js,{parse expression="PUBLIC_DIRECTORY"}/js/ips.hovercard.js,{parse expression="PUBLIC_DIRECTORY"}/js/ips.quickpm.js<if test="hasjsmodules:|:count($jsModules)">,{parse expression="PUBLIC_DIRECTORY"}/js/ips.{parse expression="implode('.js,' . PUBLIC_DIRECTORY . '/js/ips.', array_unique( array_keys( $jsModules ) ) )"}.js</if>' charset='{$this->settings['gb_char_set']}'></script>
<else />
	<if test="nominifyremoteloadjs:|:$this->settings['remote_load_js']">
		<script type='text/javascript' src='<if test="nmusehttpsp:|:$this->registry->output->isHTTPS">https<else />http</if>://ajax.googleapis.com/ajax/libs/prototype/1.7/prototype.js'></script>
	<else />
		<script type='text/javascript' src='{$this->settings['js_base_url']}js/3rd_party/prototype.js'></script>
	</if>
	<script type='text/javascript' src='{$this->settings['js_base_url']}js/ipb.js?ipbv={$this->registry->output->antiCacheHash}&amp;load=quickpm,hovercard,{parse expression="implode(',', array_unique( array_keys( $jsModules ) ) )"}'></script>
	<if test="nominifyremoteloadjs2:|:$this->settings['remote_load_js']">
		<script type='text/javascript' src='<if test="nmusehttpss:|:$this->registry->output->isHTTPS">https<else />http</if>://ajax.googleapis.com/ajax/libs/scriptaculous/1.8/scriptaculous.js?load=effects,dragdrop,builder'></script>
	<else />
		<script type='text/javascript' src='{$this->settings['js_base_url']}js/3rd_party/scriptaculous/scriptaculous-cache.js'></script>
	</if>
	<script type="text/javascript" src='{$this->settings['cache_dir']}lang_cache/{$this->lang->lang_id}/ipb.lang.js' charset='{$this->settings['gb_char_set']}'></script>
</if>
{parse template="liveEditJs" group="global"}
<if test="isLargeTouch:|:$this->registry->output->isLargeTouchDevice()">
<script type="text/javascript" src='{$this->settings['js_base_url']}js/3rd_party/iscroll/iscroll.js'></script>
</if>
<!-- Include JS Portal -->
<script language='javascript' type='text/javascript' src='{$this->settings['js_base_url']}js/3rd_party/jquery.min.js'>
</script>
<script language='javascript' type='text/javascript' src='{$this->settings['js_base_url']}js/3rd_party/underscore-min.js'>
</script>
<script language='javascript' type='text/javascript' src='{$this->settings['js_base_url']}js/3rd_party/jquery_mentions/jquery.mentionsInput.js'>
</script>
<script language='javascript' type='text/javascript' src='{$this->settings['js_base_url']}js/3rd_party/jquery_mentions/lib/jquery.elastic.js'>
</script>
<script language='javascript' type='text/javascript' src='{$this->settings['js_base_url']}js/3rd_party/jquery_mentions/lib/jquery.events.input.js'>
</script>
<script language='javascript' type='text/javascript'>
<!--
jQuery.noConflict();
//-->
</script>
<script language='javascript' type='text/javascript' src='{$this->settings['js_base_url']}js/ips.status.js'>
</script>
<script language='javascript' type='text/javascript' src='{$this->settings['js_base_url']}js/3rd_party/jquery_mentions/mentions.js'>
</script>

<script language='javascript' type='text/javascript' src='{$this->settings['js_base_url']}js/ips.textEditor.js'>
</script>
<!-- End Include JS Portal -->
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: includeMeta
//===========================================================================
function includeMeta($metaTags) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="metatags:|:is_array( $metaTags ) AND count( $metaTags )">
	<foreach loop="metaTags:$metaTags as $tag => $content">
		<if test="ogCaveman:|:stristr( $tag, 'og:' )">
			<meta property="{$tag}" content="{$content}" />
		<else />
			<meta name="{$tag}" content="{$content}" />
		</if>
		<if test="hasIdentifier:|:$tag == 'identifier-url'">
			<meta property="og:url" content="{$content}" />
		</if>
		<if test="hasDescription:|:$tag == 'description'">
			<meta property="og:description" content="{$content}" />
		</if>
	</foreach>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: includeRTL
//===========================================================================
function includeRTL() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>$this->isRtlLang	= false;</php>
<foreach loop="langData:$this->cache->getCache('lang_data') as $data">
	<if test="checkrtl:|:intval($this->member->language_id) == intval($data['lang_id'])">
		<if test="isrtl:|:$data['lang_isrtl']">
			<if test="$this->isRtlLang = true"></if>
		</if>
	</if>
</foreach>
<if test="importrtlcss:|:$this->isRtlLang AND is_file( DOC_IPS_ROOT_PATH . '/' . PUBLIC_DIRECTORY . '/style_css/' . $this->registry->output->skin['_csscacheid'] . '/ipb_rtl.css' )">
	<link rel="stylesheet" type="text/css" media="screen" href="{$this->settings['public_dir']}style_css/{$this->registry->output->skin['_csscacheid']}/ipb_rtl.css" />
	<script type='text/javascript'>
		var rtlFull	= "{$this->settings['public_dir']}style_css/{$this->registry->output->skin['_csscacheid']}/ipb_rtl.css";
		var isRTL	= true;
	</script>
<else />
	<if test="importrtlcss:|:$this->isRtlLang AND is_file( DOC_IPS_ROOT_PATH . '/' . PUBLIC_DIRECTORY . '/style_css/ipb_rtl.css' )">
		<link rel="stylesheet" type="text/css" media="screen" href="{$this->settings['public_dir']}style_css/ipb_rtl.css" />
		<script type='text/javascript'>
			var rtlFull	= "{$this->settings['public_dir']}style_css/ipb_rtl.css";
			var isRTL	= true;
		</script>
	</if>
</if>
<if test="importrtlcss:|:$this->isRtlLang AND is_file( DOC_IPS_ROOT_PATH . '/' . PUBLIC_DIRECTORY . '/style_css/' . $this->registry->output->skin['_csscacheid'] . '/ipb_rtl_ie.css' )">
	<!--[if lte IE 7]>
	<link rel="stylesheet" type="text/css" media="screen" href="{$this->settings['public_dir']}style_css/{$this->registry->output->skin['_csscacheid']}/ipb_rtl_ie.css" />
	<![endif]-->
	<script type='text/javascript'>
		var rtlIe	= "{$this->settings['public_dir']}style_css/{$this->registry->output->skin['_csscacheid']}/ipb_rtl_ie.css";
	</script>
<else />
	<if test="importrtlcss:|:$this->isRtlLang AND is_file( DOC_IPS_ROOT_PATH . '/' . PUBLIC_DIRECTORY . '/style_css/ipb_rtl_ie.css' )">
		<!--[if lte IE 7]>
		<link rel="stylesheet" type="text/css" media="screen" href="{$this->settings['public_dir']}style_css/ipb_rtl_ie.css" />
		<![endif]-->
		<script type='text/javascript'>
			var rtlIe	= "{$this->settings['public_dir']}style_css/ipb_rtl_ie.css";
		</script>
	</if>
</if>
<if test="hasMemberTopicMax:|:$this->settings['member_topic_avatar_max']">
	<!-- Forces topic photo to show without thumb -->
	<style type='text/css'>
		.ipsUserPhoto_variable { max-width: {parse expression="intval($this->settings['member_topic_avatar_max'])"}px !important; }
		<if test="RTLMargin:|:$this->isRtlLang">
			.post_body { margin-right: {parse expression="((intval($this->settings['member_topic_avatar_max']  + 25 ) < 185 ) ? 185 : intval($this->settings['member_topic_avatar_max']  + 25 ) )"}px !important; }
		<else />
			.post_body { margin-left: {parse expression="((intval($this->settings['member_topic_avatar_max']  + 25 ) < 185 ) ? 185 : intval($this->settings['member_topic_avatar_max']  + 25 ) )"}px !important; }
		</if>
	</style>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: includeVars
//===========================================================================
function includeVars($header_items=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type='text/javascript'>
	//<![CDATA[
		/* ---- URLs ---- */
		ipb.vars['base_url'] 			= '<if test="$this->registry->output->isHTTPS">{parse expression="str_replace( 'http://', 'https://', "{$this->settings['js_base']}" )"}<else />{$this->settings['js_base']}</if>';
		ipb.vars['board_url']			= '{$this->settings['board_url']}';
		ipb.vars['img_url'] 			= "{$this->settings['img_url']}";
		ipb.vars['loading_img'] 		= '{$this->settings['img_url']}/loading.gif';
		ipb.vars['active_app']			= '{$this->registry->getCurrentApplication()}';
		ipb.vars['upload_url']			= '{$this->settings['upload_url']}';
		/* ---- Member ---- */
		ipb.vars['member_id']			= parseInt( {$this->memberData['member_id']} );
		ipb.vars['is_supmod']			= parseInt( {parse expression="intval($this->memberData['g_is_supmod'])"} );
		ipb.vars['is_admin']			= parseInt( {$this->memberData['g_access_cp']} );
		ipb.vars['secure_hash'] 		= '{$this->member->form_hash}';
		ipb.vars['session_id']			= '{$this->member->session_id}';
		ipb.vars['twitter_id']			= {parse expression="intval($this->memberData['twitter_id'])"};
		ipb.vars['fb_uid']				= <if test="hasFBCHash:|:$this->memberData['fb_token']">{parse expression="intval($this->memberData['fb_uid'])"}<else />0</if>;
		ipb.vars['auto_dst']			= parseInt( {$this->memberData['members_auto_dst']} );
		ipb.vars['dst_in_use']			= parseInt( {$this->memberData['dst_in_use']} );
		ipb.vars['is_touch']			= <if test="istl:|:$this->registry->output->isLargeTouchDevice()">'large';<else /><if test="istm:|:$this->registry->output->isSmallTouchDevice()">'small';<else />false;</if></if>
		ipb.vars['member_group']		= {parse expression="json_encode( array( 'g_mem_info' => $this->memberData['g_mem_info'] ) )"}
		/* ---- cookies ----- */
		ipb.vars['cookie_id'] 			= '{$this->settings['cookie_id']}';
		ipb.vars['cookie_domain'] 		= '{$this->settings['cookie_domain']}';
		ipb.vars['cookie_path']			= '{$this->settings['cookie_path']}';
		/* ---- Rate imgs ---- */
		ipb.vars['rate_img_on']			= '{$this->settings['img_url']}/star.png';
		ipb.vars['rate_img_off']		= '{$this->settings['img_url']}/star_off.png';
		ipb.vars['rate_img_rated']		= '{$this->settings['img_url']}/star_rated.png';
		/* ---- Uploads ---- */
		ipb.vars['swfupload_swf']		= '{parse url="js/3rd_party/swfupload/swfupload.swf" base="public_dir"}';
		ipb.vars['swfupload_enabled']	= <if test="canswfupload:|:$this->settings['uploadFormType']">true<else />false</if>;
		ipb.vars['use_swf_upload']		= ( '{$this->memberData['member_uploader']}' == 'flash' ) ? true : false;
		ipb.vars['swfupload_debug']		= false;
		/* ---- other ---- */
		ipb.vars['highlight_color']     = "#ade57a";
		ipb.vars['charset']				= "{$this->settings['gb_char_set']}";
		ipb.vars['seo_enabled']			= {parse expression="intval($this->settings['use_friendly_urls'])"};
		<if test="usefurl:|:$this->settings['use_friendly_urls']">
		ipb.vars['seo_params']			= {parse expression="json_encode($this->registry->getClass('output')->seoTemplates['__data__'])"};
		</if>
		/* Templates/Language */
		ipb.templates['inlineMsg']		= "{$header_items['inlineMsg']}";
		ipb.templates['ajax_loading'] 	= "<div id='ajax_loading'><img src='{$this->settings['img_url']}/ajax_loading.gif' alt='" + ipb.lang['loading'] + "' /></div>";
		ipb.templates['close_popup']	= "<img src='{$this->settings['img_url']}/close_popup.png' alt='x' />";
		ipb.templates['rss_shell']		= new Template("<ul id='rss_menu' class='ipbmenu_content'>#{items}</ul>");
		ipb.templates['rss_item']		= new Template("<li><a href='#{url}' title='#{title}'>#{title}</a></li>");
		<if test="$this->memberData['member_id']">
			ipb.templates['m_add_friend']	= new Template("<a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=add&amp;member_id=#{id}" base="public"}' title='{$this->lang->words['add_friend']}' class='ipsButton_secondary'>{parse replacement="add_friend"}</a>");
			ipb.templates['m_rem_friend']	= new Template("<a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=remove&amp;member_id=#{id}" base="public"}' title='{$this->lang->words['remove_friend']}' class='ipsButton_secondary'>{parse replacement="remove_friend"}</a>");
		</if>
		ipb.templates['autocomplete_wrap'] = new Template("<ul id='#{id}' class='ipb_autocomplete' style='width: 250px;'></ul>");
		ipb.templates['autocomplete_item'] = new Template("<li id='#{id}' data-url='#{url}'><img src='#{img}' alt='' class='ipsUserPhoto ipsUserPhoto_mini' />&nbsp;&nbsp;#{itemvalue}</li>");
		ipb.templates['page_jump']		= new Template("<div id='#{id}_wrap' class='ipbmenu_content'><h3 class='bar'>{$this->lang->words['global_page_jump']}</h3><p class='ipsPad'><input type='text' class='input_text' id='#{id}_input' size='8' /> <input type='submit' value='{$this->lang->words['jmp_go']}' class='input_submit add_folder' id='#{id}_submit' /></p></div>");
		ipb.templates['global_notify'] 	= new Template("<div class='popupWrapper'><div class='popupInner'><div class='ipsPad'>#{message} #{close}</div></div></div>");
		<if test="hasNotification:|:! empty( $header_items['notifications'] )">
			ipb.vars['notificationData']          = {$header_items['notifications']};
			ipb.templates['notificationTemplate'] = new Template( "<div><h3>#{notify_title}</h3><div class='fixed_inner ipsPad row1'><h4 class='ipsType_sectiontitle'>#{member_PhotoTag} #{title} <span class='ipsType_smaller'>{$this->lang->words['by_ucfirst']} #{member_members_display_name} - #{date_parsed}</span><p class='ipsType_smaller right ipsPad_half'><a href='#{url}'>{parse expression="sprintf( $this->lang->words['global_pm_read_short'], '#{type}' )"}</a></p></h4><p class='ipsPad_half'>#{content}</p></div></div>");
		</if>
		
		ipb.templates['header_menu'] 	= new Template("<div id='#{id}' class='ipsHeaderMenu boxShadow'></div>");
		<if test="autodst:|:$this->memberData['members_auto_dst'] == 1 AND $this->settings['time_dst_auto_correction']">
			ipb.global.checkDST();
		</if>
		Loader.boot();
	//]]>
	</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: inlineLogin
//===========================================================================
function inlineLogin() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>
	$uses_name		= false;
	$uses_email		= false;
	$_redirect		= '';
	$login_methods  = array();
	
	foreach( $this->cache->getCache('login_methods') as $method )
	{
		if( $method['login_user_id'] == 'username' or $method['login_user_id'] == 'either' )
		{
			$uses_name	= true;
		}
		
		if( $method['login_user_id'] == 'email' or $method['login_user_id'] == 'either' )
		{
			$uses_email	= true;
		}
		
		if( $method['login_login_url'] )
		{
			$_redirect	= $method['login_login_url'];
		}
		
		$login_methods[] = $method['login_folder_name'];
	}
	if( $uses_name AND $uses_email )
	{
		$this->lang->words['enter_name']	= $this->lang->words['enter_name_and_email'];
	}
	else if( $uses_email )
	{
		$this->lang->words['enter_name']	= $this->lang->words['enter_useremail'];
	}
	else
	{
		$this->lang->words['enter_name']	= $this->lang->words['enter_username'];
	}
</php>
<if test="!$_redirect">
	<div id='inline_login_form' style='display: none'>
		<form action="{parse url="app=core&amp;module=global&amp;section=login&amp;do=process" base="public"}" method="post" id='login'>
			<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
			<input type="hidden" name="referer" value="{$this->settings['this_url']}" />
			<h3>{$this->lang->words['log_in']}</h3>
			<if test="registerServices:|:IPSLib::loginMethod_enabled('facebook') || IPSLib::loginMethod_enabled('twitter') || IPSLib::loginMethod_enabled('live')">
				<div class='ipsBox_notice'>
					<ul class='ipsList_inline'>
						<if test="facebook:|:IPSLib::loginMethod_enabled('facebook')">
							<li><a href="{parse url="app=core&amp;module=global&amp;section=login&amp;serviceClick=facebook" base="public"}" class='ipsButton_secondary'><img src="{$this->settings['img_url']}/loginmethods/facebook.png" alt="Facebook" /> &nbsp; {$this->lang->words['use_facebook']}</a></li>
						</if>
						<if test="twitterBox:|:IPSLib::loginMethod_enabled('twitter')">
							<li><a href="{parse url="app=core&amp;module=global&amp;section=login&amp;serviceClick=twitter" base="public"}" class='ipsButton_secondary'><img src="{$this->settings['img_url']}/loginmethods/twitter.png" alt="Twitter" /> &nbsp; {$this->lang->words['use_twitter']}</a></li>
						</if>
						<if test="haswindowslive:|:IPSLib::loginMethod_enabled('live')">
							<li><a href='{parse url="app=core&amp;module=global&amp;section=login&amp;do=process&amp;use_live=1&amp;auth_key={$this->member->form_hash}" base="public"}' class='ipsButton_secondary'><img src="{$this->settings['img_url']}/loginmethods/windows.png" alt="Windows Live" /> &nbsp; {$this->lang->words['use_live']}</a></li>
						</if>
					</ul>
				</div>
			</if>
			<br />
			<div class='ipsForm ipsForm_horizontal'>
				<fieldset>
					<ul>
						<li class='ipsField'>
							<div class='ipsField_content'>
								{$this->lang->words['register_prompt_1']} <a href="{parse url="app=core&amp;module=global&amp;section=register" base="public"}" title='{$this->lang->words['register_prompt_2']}'>{$this->lang->words['register_prompt_2']}</a>
							</div>
						</li>
						<li class='ipsField ipsField_primary'>
							<label for='ips_username' class='ipsField_title'>{$this->lang->words['enter_name']}</label>
							<div class='ipsField_content'>
								<input id='ips_username' type='text' class='input_text' name='ips_username' size='30' />
							</div>
						</li>
						<li class='ipsField ipsField_primary'>
							<label for='ips_password' class='ipsField_title'>{$this->lang->words['enter_pass']}</label>
							<div class='ipsField_content'>
								<input id='ips_password' type='password' class='input_text' name='ips_password' size='30' /><br />
								<a href='{parse url="app=core&amp;module=global&amp;section=lostpass" base="public"}' title='{$this->lang->words['retrieve_pw']}'>{$this->lang->words['login_forgotten_pass']}</a>
							</div>
						</li>
						<li class='ipsField ipsField_checkbox'>
							<input type='checkbox' id='inline_remember' checked='checked' name='rememberMe' value='1' class='input_check' />
							<div class='ipsField_content'>
								<label for='inline_remember'>
									<strong>{$this->lang->words['rememberme']}</strong><br />
									<span class='desc lighter'>{$this->lang->words['notrecommended']}</span>
								</label>
							</div>
						</li>
						<if test="anonymous:|:!$this->settings['disable_anonymous']">
							<li class='ipsField ipsField_checkbox'>
								<input type='checkbox' id='inline_invisible' name='anonymous' value='1' class='input_check' />
								<div class='ipsField_content'>
									<label for='inline_invisible'>
										<strong>{$this->lang->words['form_invisible']}</strong><br />
										<span class='desc lighter'>{$this->lang->words['anon_name']}</span>
									</label>
								</div>
							</li>
						</if>
						<if test="privvy:|:$this->settings['priv_title']">
						<li class='ipsPad_top ipsForm_center desc ipsType_smaller'>
							<a rel="nofollow" href='{parse url="app=core&amp;module=global&amp;section=privacy" template="privacy" seotitle="false" base="public"}'>{$this->settings['priv_title']}</a>
						</li>
						</if>
					</ul>
				</fieldset>
				
				<div class='ipsForm_submit ipsForm_center'>
					<input type='submit' class='ipsButton' value='{$this->lang->words['log_in']}' />
				</div>
			</div>
		</form>
	</div>
<else />
	<script type='text/javascript'>
		ipb.global.loginRedirect = "{$_redirect}";
	</script>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: liveEditJs
//===========================================================================
function liveEditJs() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="liveEditOn:|: defined( 'IPS_LIVE_EDIT') && IPS_LIVE_EDIT">
	<script type='text/javascript'>	
		skingen_imgs = "{$this->settings['public_dir']}style_extra/skin_creator";
		<if test="hasSkinData:|: ! empty( $this->registry->output->skinGenSession['skin_gen_data'] )">
		var IPS_SKIN_GEN_SAVED_DATA = {parse expression="json_encode( $this->registry->output->skinGenSession['skin_gen_data'] )"};
		</if>
	</script>
	<link rel="stylesheet" href="{$this->settings['public_dir']}style_css/skin_creator.css" type="text/css"  media="screen" />
	<link rel="stylesheet" href="{$this->settings['js_base_url']}js/3rd_party/prototype/colorpicker/css/prototype_colorpicker.css" type="text/css"  media="screen" />
	<script type="text/javascript" src="{$this->settings['js_base_url']}js/3rd_party/prototype/colorpicker/js/prototype_colorpicker.js"></script>
	<script type="text/javascript" src="{$this->settings['cache_dir']}skinGenJsonCache.js"></script>
	<script type="text/javascript" src="{$this->settings['js_base_url']}js/ips.skin_gen.js"></script>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: nextPreviousTemplate
//===========================================================================
function nextPreviousTemplate($data) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="haspages:|:$data['totalItems'] > $data['itemsPerPage']">
	<div class='left pagination clear'>
		<ul class='ipsList_inline back forward'>
			<if test="prevpage:|:$data['_hasPrevious']">
				<li class='prev'>
					<if test="useAjaxPrev:|:$data['ajaxLoad']">
						<a href="#" onclick="return ipb.global.ajaxPagination( '{$data['ajaxLoad']}', '{parse url="{$data['baseUrl']}&amp;secure_key={$this->member->form_hash}&amp;{$data['startValueKey']}={parse expression="intval( $data['currentStartValue'] - $data['itemsPerPage'] )"}{$data['anchor']}" base="{$data['base']}" seotitle="{$data['seoTitle']}" template="{$data['seoTemplate']}"}' );" title="{$this->lang->words['tpl_prev']}" rel='prev'>{$this->lang->words['_larr']} {$this->lang->words['prev']}</a>
					<else />
						<a href="{parse url="{$data['baseUrl']}&amp;{$data['startValueKey']}={parse expression="intval( $data['currentStartValue'] - $data['itemsPerPage'] )"}{$data['anchor']}" base="{$data['base']}" seotitle="{$data['seoTitle']}" template="{$data['seoTemplate']}"}" title="{$this->lang->words['tpl_prev']}" rel='prev'>{$this->lang->words['_larr']} {$this->lang->words['prev']}</a>
					</if>
				</li>
			</if>
			<if test="nextpage:|:$data['_hasNext']">
				<li class='next'>
					<if test="useAjaxNext:|:$data['ajaxLoad']">
						<a href="#" onclick="return ipb.global.ajaxPagination( '{$data['ajaxLoad']}', '{parse url="{$data['baseUrl']}&amp;secure_key={$this->member->form_hash}&amp;{$data['startValueKey']}={parse expression="intval( $data['currentStartValue'] + $data['itemsPerPage'] )"}{$data['anchor']}" base="{$data['base']}" seotitle="{$data['seoTitle']}" template="{$data['seoTemplate']}"}' );" title="{$this->lang->words['tpl_next']}" rel='next'>{$this->lang->words['next']} {$this->lang->words['_rarr']}</a>
					<else />
						<a href="{parse url="{$data['baseUrl']}&amp;{$data['startValueKey']}={parse expression="intval( $data['currentStartValue'] + $data['itemsPerPage'] )"}{$data['anchor']}" base="{$data['base']}" seotitle="{$data['seoTitle']}" template="{$data['seoTemplate']}"}" title="{$this->lang->words['tpl_next']}" rel='next'>{$this->lang->words['next']} {$this->lang->words['_rarr']}</a>
					</if>
				</li>
			</if>
		</ul>
	</div>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: paginationTemplate
//===========================================================================
function paginationTemplate($work, $data) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="haspages:|:$work['pages'] > 1">
	<div class='pagination clearfix left <if test="!$data['showNumbers']">no_numbers</if>'>
		<ul class='ipsList_inline back left'>
			<if test="firstpage:|:1 < ($work['current_page'] - $data['dotsSkip'])">
				<li class='first'><a href='{parse url="{$data['baseUrl']}&amp;{$data['startValueKey']}=0{$data['anchor']}" base="{$data['base']}" seotitle="{$data['seoTitle']}" template="{$data['seoTemplate']}"}' title='{$data['realTitle']} - {$this->lang->words['tpl_gotofirst']}' rel='start'>{$this->lang->words['_laquo']} <!--{$this->lang->words['ps_first']}--></a></li>
			</if>
			<if test="prevpage:|:$work['current_page'] > 1">
				<li class='prev'><a href="{parse url="{$data['baseUrl']}&amp;{$data['startValueKey']}={parse expression="intval( $data['currentStartValue'] - $data['itemsPerPage'] )"}{$data['anchor']}" base="{$data['base']}" seotitle="{$data['seoTitle']}" template="{$data['seoTemplate']}"}" title="{$data['realTitle']} - {$this->lang->words['tpl_prev']}" rel='prev'>{$this->lang->words['prev']}</a></li>
			</if>
		</ul>
		<ul class='ipsList_inline left pages'>
			<if test="$this->request['module'] != 'ajax' AND !$data['noDropdown']">
				<li class='pagejump clickable pj{$data['uniqid']}'>
					<a href='#'>{parse expression="sprintf( $this->lang->words['tpl_pages'], $work['current_page'], $work['pages'] )"} <!--{parse replacement="dropdown"}--></a>
					<script type='text/javascript'>
						ipb.global.registerPageJump( '{$data['uniqid']}', { url: "{parse url="{$data['baseUrl']}" template="{$data['seoTemplate']}" seotitle="{$data['seoTitle']}" base="{$data['base']}"}<if test="furlinfo:|:$data['isFriendly']">/</if>", stKey: '{$data['startValueKey']}', perPage: {$data['itemsPerPage']}, totalPages: {$work['pages']}, anchor: '{$data['anchor']}' } );
					</script>
				</li>
			<else />
				<li class='pagejump'>
					{parse expression="sprintf( $this->lang->words['tpl_pages'], $work['current_page'], $work['pages'] )"}
				</li>
			</if>
			<if test="normalpages:|:is_array( $work['_pageNumbers'] ) && count( $work['_pageNumbers'] )">
				<foreach loop="pagination:$work['_pageNumbers'] as $_real => $_page">
					<if test="activepage:|:$_real == $data['currentStartValue']">
						<li class='page active'>{$_page}</li>
					<else />
						<li class='page'><a href="{parse url="{$data['baseUrl']}&amp;{$data['startValueKey']}={$_real}{$data['anchor']}" base="{$data['base']}" seotitle="{$data['seoTitle']}" template="{$data['seoTemplate']}"}" title="<if test="hasRealTitle:|:$data['realTitle']">{parse expression="sprintf( $this->lang->words['page_title_text'],$data['realTitle'],$_page)"}<else />$_page</if>">{$_page}</a></li>
					</if>
				</foreach>
			</if>
		</ul>
		<ul class='ipsList_inline forward left'>
			<if test="nextpage:|:$work['current_page'] < $work['pages']">
				<li class='next'><a href="{parse url="{$data['baseUrl']}&amp;{$data['startValueKey']}={parse expression="intval( $data['currentStartValue'] + $data['itemsPerPage'] )"}{$data['anchor']}" base="{$data['base']}" seotitle="{$data['seoTitle']}" template="{$data['seoTemplate']}"}" title="{$data['realTitle']} - {$this->lang->words['tpl_next']}" rel='next'>{$this->lang->words['next']}</a></li>
			</if>
			<if test="lastpage:|:!empty( $work['_showEndDots'] )">
				<li class='last'><a href="{parse url="{$data['baseUrl']}&amp;{$data['startValueKey']}={parse expression="intval( ( $work['pages'] - 1 ) * $data['itemsPerPage'] )"}{$data['anchor']}" base="{$data['base']}" seotitle="{$data['seoTitle']}" template="{$data['seoTemplate']}"}" title="{$data['realTitle']} - {$this->lang->words['tpl_gotolast']}" rel='last'>{$this->lang->words['_raquo']}</a></li>
			</if>
		</ul>
	</div>
<else />
	<if test="notDisableSinglePage:|:!$data['disableSinglePage']">
		<p class='pagination no_pages left ipsType_small'><span>{$this->lang->words['page_1_of_1']}</span></p>
	</if>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: quickSearch
//===========================================================================
function quickSearch() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div id='search' class='right'>
	<form action="{parse url="app=core&amp;module=search&amp;do=search&amp;fromMainBar=1" base="public"}" method="post" id='search-box' >
		<fieldset>
			<label for='main_search' class='hide'>{$this->lang->words['sj_search']}</label>
			<a href='{parse url="app=core&amp;module=search&amp;search_in=<if test="hasSearchApp:|:isset($this->request['search_app']) AND $this->request['search_app']">{$this->request['search_app']}<else />{$this->registry->getCurrentApplication()}</if>" base="public"}' title='{$this->lang->words['advanced_search']}' accesskey='4' rel="search" id='adv_search' class='right'>{$this->lang->words['advanced']}</a>
			<span id='search_wrap' class='right'>
				<input type='text' id='main_search' name='search_term' class='inactive' size='17' tabindex='6' />
				<span class='choice ipbmenu clickable' id='search_options' style='display: none'></span>
				<ul id='search_options_menucontent' class='ipbmenu_content ipsPad' style='display: none'>
					<li class='title'><strong>{$this->lang->words['context_search_title']}</strong></li>
					<if test="inTopic:|:$this->request['showtopic'] or ( isset( $this->request['search_app'] ) and substr( $_POST['search_app'], 0, 13 ) == 'forums:topic:' )">
						<li class='special'>
							<label for='s_topic' title='{$this->lang->words['context_search_topic']}'>
								<if test="$this->request['showtopic']">
									<input type='radio' name='search_app' value="forums:topic:{$this->request['showtopic']}" class='input_radio' id='s_topic' checked="checked" /> 
								<else />
									<input type='radio' name='search_app' value="forums:topic:{parse expression="substr( $_POST['search_app'], 13 )"}" class='input_radio' id='s_topic' checked="checked" /> 
								</if>
								<strong>{$this->lang->words['context_search_topic']}</strong>
							</label>
						</li>
					</if>
					<if test="inForum:|:( $this->request['showforum'] AND $this->registry->getClass('class_forums')->forum_by_id[ $this->request['showforum'] ]['sub_can_post'] ) or ( isset( $this->request['search_app'] ) and substr( $_POST['search_app'], 0, 13 ) == 'forums:forum:' )">
						<li class='special'>
							<label for='s_forum' title='{$this->lang->words['context_search_forum']}'>
								<if test="$this->request['showforum']">
									<input type='radio' name='search_app' value="forums:forum:{$this->request['f']}" class='input_radio' id='s_forum' <if test="!$this->request['showtopic']">checked="checked"</if> /> 
								<else />
									<input type='radio' name='search_app' value="forums:forum:{parse expression="substr( $_POST['search_app'], 13 )"}" class='input_radio' id='s_forum' checked="checked" /> 
								</if>
								<strong>{$this->lang->words['context_search_forum']}</strong>
							</label>
						</li>
					</if>
					<if test="lookElsewhere:|:! in_array( $this->registry->getCurrentApplication(), array( 'forums', 'members', 'core' ) )">
						<if test="appContextSearch:|:method_exists( $this->registry->output->getTemplate( $this->registry->getCurrentApplication() . '_global' ), 'contextSearch' )">
							{parse template="contextSearch" group="{current_app}_global" params=""}
						</if>
					</if>
					<if test="IPSLib::appIsSearchable( 'forums', 'search' )">
						<li class='app'><label for='s_forums' title='{IPSLib::getAppTitle('forums')}'><input type='radio' name='search_app' class='input_radio' id='s_forums' value="forums" <if test="inThisApp:|:( ( isset($this->request['search_app']) AND $this->request['search_app'] == 'forums' ) ) || ( !IPSLib::appIsSearchable( $this->registry->getCurrentApplication(), 'search' ) ) || ( ( $this->registry->getCurrentApplication() == 'forums' ) && ! $this->request['search_app'] ) && !$this->request['showtopic'] && !$this->request['showforum']"><if test="substr( $_POST['search_app'], 0, 13 ) != 'forums:topic:' and substr( $_POST['search_app'], 0, 13 ) != 'forums:forum:'">checked="checked"</if></if> />{IPSLib::getAppTitle( 'forums' )}</label></li>
					</if>
					<if test="IPSLib::appIsSearchable( 'members', 'search' )">
						<li class='app'><label for='s_members' title='{IPSLib::getAppTitle('members')}'><input type='radio' name='search_app' class='input_radio' id='s_members' value="members" <if test="inThisApp:|:( ( isset($this->request['search_app']) AND $this->request['search_app'] == 'members' ) ) || ( ( $this->registry->getCurrentApplication() == 'members' ) && ! $this->request['search_app'] ) && !$this->request['showtopic'] && !$this->request['showforum']">checked="checked"</if> />{IPSLib::getAppTitle( 'members' )}</label></li>
					</if>
					<if test="IPSLib::appIsSearchable( 'core', 'search' )">
						<li class='app'><label for='s_core' title='{IPSLib::getAppTitle('core')}'><input type='radio' name='search_app' class='input_radio' id='s_core' value="core" <if test="inThisApp:|:( ( isset($this->request['search_app']) AND $this->request['search_app'] == 'core' ) ) || ( ( $this->registry->getCurrentApplication() == 'core' ) && ! $this->request['search_app'] ) && !$this->request['showtopic'] && !$this->request['showforum']">checked="checked"</if> />{IPSLib::getAppTitle( 'core' )}</label></li>
					</if>
					<foreach loop="appLoop:$this->registry->getApplications() as $app => $data">
						<if test="IPSLib::appIsSearchable( $app, 'search' ) AND !in_array( $app, array( 'forums', 'members', 'core' ) )">
							<li class='app'><label for='s_{$app}' title='{IPSLib::getAppTitle( $app )}'><input type='radio' name='search_app' class='input_radio' id='s_{$app}' value="{$app}" <if test="inThisApp:|:( ( isset($this->request['search_app']) AND $this->request['search_app'] == $app ) || ( !IPSLib::appIsSearchable( $this->registry->getCurrentApplication(), 'search' ) AND $app == 'core' ) || ( ( $this->registry->getCurrentApplication() == $app ) && ( !isset($this->request['search_app']) OR !$this->request['search_app'] ) ) ) && !$this->request['showtopic'] && !$this->request['showforum'] && ( !method_exists( $this->registry->output->getTemplate( $this->registry->getCurrentApplication() . '_global' ), 'contextSearch' ) or ipsRegistry::$appSearch )">checked="checked"</if> />{IPSLib::getAppTitle( $app )}</label></li>
						</if>
					</foreach>
				</ul>
				<input type='submit' class='submit_input clickable' value='{$this->lang->words['sj_search']}' />
			</span>
			
		</fieldset>
	</form>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: shareLinks
//===========================================================================
function shareLinks($links, $title='', $url='', $cssClass='topic_share left') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="sharelinks"}
<if test="gotLinks:|:is_array( $links )">
	<ul class='{$cssClass} shareButtons ipsList_inline'>
	<foreach loop="cacheLoop:$links as $id => $data">
		<if test="isEnabled:|:$data['share_enabled']">
			<if test="hasCustom:|:$data['customOutput'] and is_array( $data['customOutput'] )">
				{parse expression="$this->registry->output->getTemplate($data['customOutput'][0])->$data['customOutput'][1]( $data['_rawUrl'], $title, $data['customOutput'][2] )"}
			<else />
			<li><a href="{parse url="sharelink={$data['share_key']};{$data['_url']};{$title}" base="public"}<if test="hasOverrideApp:|:$data['overrideApp']">&amp;overrideApp={$data['overrideApp']}</if>" rel="nofollow" target="_blank" title="<if test="isset( $this->lang->words['gbl_sharelink_with_' . $data['share_key'] ] )">{$this->lang->words['gbl_sharelink_with_' . $data['share_key'] ]}<else />{$this->lang->words['gbl_sharelink_with']} {$data['share_title']}</if>" class='_slink' id='slink_{$data['share_key']}'><img src="{$this->settings['public_cdn_url']}style_extra/sharelinks/{$data['share_key']}.png" /></a></li>
			</if>
		</if>
	</foreach>
	</ul>
	<script type="text/javascript">
		ipb.sharelinks.url   = "{parse expression="IPSText::base64_decode_urlSafe($url)"}";
		ipb.sharelinks.title = "{parse expression="IPSText::base64_decode_urlSafe( IPSText::htmlspecialchars( $title ) )"}";
		ipb.sharelinks.bname = "{parse expression="trim(addslashes($this->settings['board_name']))"}";
	</script>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: signature_separator
//===========================================================================
function signature_separator($sig="", $author_id=0, $can_ignore=true) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class="signature" data-memberid="{$author_id}">
	<if test="notMe:|: $this->memberData['member_id'] && $this->memberData['member_id'] != $author_id && $can_ignore"><a href='#' class='hide_signature'></a></if>
	{$sig}
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: userHoverCard
//===========================================================================
function userHoverCard($member=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="canSeeProfiles:|:$member['member_id'] && ( $this->memberData['g_is_supmod'] || ($this->memberData['g_mem_info'] && ! IPSMember::isInactive($member) ) )">
	<a hovercard-ref="member" hovercard-id="{$member['member_id']}" class="_hovertrigger url fn name <if test="hasClassName:|:isset($member['_hoverClass'])"> {$member['_hoverClass']}</if>" href='{parse url="showuser={$member['member_id']}" template="showuser" seotitle="{$member['members_seo_name']}" base="public"}' title='<if test="hasTitle:|:!empty($member['_hoverTitle'])">{$member['_hoverTitle']}<else />{$this->lang->words['view_profile']}</if>'><span itemprop="name">{$member['members_display_name']}</span></a>
<else />
	{$member['members_display_name']}
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: userInfoPane
//===========================================================================
function userInfoPane($author, $contentid, $options) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div itemscope itemtype="http://schema.org/Person" class='user_details'>
	<span class='hide' itemprop="name">{$author['members_display_name']}</span>
	<ul class='basic_info'>
		<if test="membertitle:|:$author['member_title']">
			<p class='desc member_title'>{$author['member_title']}</p>
		</if>
		<if test="avatar:|:$author['member_id']">
			<li class='avatar'>
				<if test="canSeeProfiles:|:$this->memberData['g_is_supmod'] OR ( $this->memberData['g_mem_info'] && ! IPSMember::isInactive( $author ) )">
				<a itemprop="url" href="{parse url="showuser={$author['member_id']}" template="showuser" seotitle="{$author['members_seo_name']}" base="public"}" title="{$this->lang->words['view_profile']}: {$author['members_display_name']}" class='ipsUserPhotoLink'>
				</if>
				<if test="hasVariable:|:$this->settings['member_topic_avatar_max']">	
					<img itemprop="image" src='{$author['pp_main_photo']}' class='ipsUserPhoto ipsUserPhoto_variable' />
				<else />
					<img itemprop="image" src='{$author['pp_thumb_photo']}' class='ipsUserPhoto ipsUserPhoto_large' />
				</if>
				<if test="canSeeProfiles2:|:$this->memberData['g_is_supmod'] OR ( $this->memberData['g_mem_info'] && ! IPSMember::isInactive( $author ) )">
				</a>
				</if>
			</li>
		<else />
			<li class='avatar'>
				<img itemprop="image" src='{$author['pp_thumb_photo']}' class='ipsUserPhoto ipsUserPhoto_large' />
			</li>
		</if>
		<li class='group_title'>
			{$author['_group_formatted']}
		</li>
		<if test="rankimage:|:$author['member_rank_img']">
			<li class='group_icon'>
			<if test="rankimageimage:|:$author['member_rank_img_i'] == 'img'">
				<img src='{$author['member_rank_img']}' alt='' />
			<else />
				{$author['member_rank_img']}
			</if>
			</li>
		</if>
		<if test="postCount:|:$author['member_id']">
		<li class='post_count desc lighter'>
			{parse expression="$this->registry->getClass('class_localization')->formatNumber( intval( $author['posts'] ) )"} {$this->lang->words['m_posts']}
		</li>
		</if>
		<if test="authorwarn:|:$author['show_warn']">
			<li>
				<if test="hasWarningId:|:$options['wl_id']">
					<img src='{$this->settings['img_url']}/warn.png' class='clickable' onclick='warningPopup( this, {$options['wl_id']} )' title='{$this->lang->words['warnings_issued']}' />
				</if>
				<a class='desc lighter blend_links' href='{parse url="app=members&amp;module=profile&amp;section=warnings&amp;member={$author['member_id']}&amp;from_app={$this->request['app']}&amp;from_id1={$contentid}&amp;from_id2={$options['id2']}" base="public"}' id='warn_link_{$contentid}_{$author['member_id']}' title='{$this->lang->words['warn_view_history']}'>{parse expression="sprintf( $this->lang->words['warn_status'], $author['warn_level'] )"}</a>
			</li>
		</if>
	</ul>
	
	<if test="authorcfields:|:$author['custom_fields'] != """>
		<ul class='custom_fields'>
			<foreach loop="customFieldsOuter:$author['custom_fields'] as $group => $data">
				<foreach loop="customFields:$author['custom_fields'][ $group ] as $field">
					<if test="$field != ''">
						<li>
							{$field}
						</li>
					</if>
				</foreach>
			</foreach>
		</ul>
	</if>
	
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: userSmallPhoto
//===========================================================================
function userSmallPhoto($member=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="linkAvatarOpen:|:$member['member_id'] && ( $this->memberData['g_is_supmod'] || ($this->memberData['g_mem_info'] && ! IPSMember::isInactive($member) ) )">
	<a href='{parse url="showuser={$member['member_id']}" template="showuser" seotitle="{$member['members_seo_name']}" base="public"}' class='ipsUserPhotoLink left'>
<else />
	<div class='left'>
</if>
<if test="hasphoto:|:$member['member_id']">
	<img src='{$member['pp_small_photo']}' alt='<if test="hasAlt:|:$member['alt']">{$member['alt']}<else />{$this->lang->words['photo']}</if>' class='ipsUserPhoto <if test="hasCustomClass:|:empty($member['_customClass'])">ipsUserPhoto_mini<else />{$member['_customClass']}</if>' />
<else />
	{IPSMember::buildNoPhoto(0, 'mini' )}
</if>
<if test="linkAvatarClose:|:$member['member_id'] && ( $this->memberData['g_is_supmod'] || ($this->memberData['g_mem_info'] && ! IPSMember::isInactive($member) ) )">
	</a>
<else />
	</div>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: warnDetails
//===========================================================================
function warnDetails($warning, $canSeeModNote) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['warnings_details']}</h3>
<div class='ipsBox'>
	<p class='message unspecific'>
		<if test="hasReason:|:$warning['wl_reason']">
			<if test="hasReasonAndContent:|:$warning['content']">
				{parse expression="sprintf( $this->lang->words['warning_blurb_yy'], "{parse template="userHoverCard" group="global" params="$warning['wl_moderator']"}", $warning['wl_reason'], $warning['content'] )"}
			<else />
				{parse expression="sprintf( $this->lang->words['warning_blurb_yn'], "{parse template="userHoverCard" group="global" params="$warning['wl_moderator']"}", $warning['wl_reason'] )"}
			</if>
		<else />
			<if test="hasContent:|:$warning['content']">
				{parse expression="sprintf( $this->lang->words['warning_blurb_ny'], "{parse template="userHoverCard" group="global" params="$warning['wl_moderator']"}", $warning['content'] )"}
			<else />
				{parse expression="sprintf( $this->lang->words['warning_blurb_nn'], "{parse template="userHoverCard" group="global" params="$warning['wl_moderator']"}" )"}
			</if>
		</if>
		<br />
		<if test="isVerbalOnly:|:!$warning['wl_points'] and !$warning['wl_mq'] and !$warning['wl_rpa'] and !$warning['wl_suspend']">
			{$this->lang->words['warnings_verbal_only']}
		<else />
			<if test="hasPoint:|:$warning['wl_points']">
				<if test="canExpire:|:$warning['wl_expire']">
					<if test="hasExpireDate:|:$warning['wl_expire_date']">
						{parse expression="sprintf( $this->lang->words['warnings_given_points_expire'], $warning['wl_points'], $this->lang->getDate( $warning['wl_expire_date'], 'SHORT' ) )"}<br />
					<else />
						{parse expression="sprintf( $this->lang->words['warnings_given_points_expired'], $warning['wl_points'] )"}<br />
					</if>
				<else />
					{parse expression="sprintf( $this->lang->words['warnings_given_points'], $warning['wl_points'] )"}<br />
				</if>
			</if>
			<foreach loop="actions:array( 'mq', 'rpa', 'suspend' ) as $k">
				<if test="hasAction:|:$warning[ 'wl_' . $k ]">
					<if test="actionIsPermanent:|:$warning[ 'wl_' . $k ] == -1">
						{parse expression="sprintf( $this->lang->words[ 'warnings_' . $k ], $this->lang->words['warnings_permanently'] )"}<br />
					<else />
						{parse expression="sprintf( $this->lang->words[ 'warnings_' . $k ], sprintf( $this->lang->words['warnings_for'], $warning[ 'wl_' . $k ], $this->lang->words[ 'warnings_time_' . $warning[ 'wl_' . $k . '_unit' ] ] ) )"}<br />
					</if>
				</if>
			</foreach>
		</if>
	</p>
	<if test="canSeeModNote:|:$canSeeModNote and $warning['wl_note_mods']">
		<div class='ipsBox_container ipsPad'>
			<strong>{$this->lang->words['warnings_note_member']}</strong>
			<br /><br />
			<if test="hasModAndMemberNote:|:$warning['wl_note_member']">
				{$warning['wl_note_member']}
			<else />
				<em>{$this->lang->words['warnings_no_note']}</em>
			</if>
		</div>
		<div class='ipsBox_container ipsPad'>
			<strong>{$this->lang->words['warnings_note_mods']}</strong>
			<br /><br />
			{$warning['wl_note_mods']}
		</div>
	<else />
		<div class='ipsBox_container ipsPad'>
			<if test="hasMemberNote:|:$warning['wl_note_member']">
				{$warning['wl_note_member']}
			<else />
				<em>{$this->lang->words['warnings_no_note']}</em>
			</if>
		</div>
	</if>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>