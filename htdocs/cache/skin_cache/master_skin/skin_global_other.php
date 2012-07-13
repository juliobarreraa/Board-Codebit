<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: ajaxPopUpNoPermission
//===========================================================================
function ajaxPopUpNoPermission($msg='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['ap_error']}</h3>
<div class='general_box'>
	<div>
		<if test="msg:|:$msg">
			$msg
		<else />
			{$this->lang->words['ap_np_msg']}
		</if>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: captchaGD
//===========================================================================
function captchaGD($captcha_unique_id) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<input type="hidden" id="regid" name="captcha_unique_id" value="{$captcha_unique_id}" />
<fieldset id='captcha' class='gd'>
	<ul class='ipsForm <if test="$this->request['section'] == 'register' || $this->request['section'] == 'lostpass'">ipsForm_horizontal<else />ipsForm_vertical</if>'>
		<li class='ipsField'>
			<label class='ipsField_title'>{$this->lang->words['glb_captcha_image']} <span class='ipsForm_required'>*</span></label>
			<div class='ipsField_content'>
				<p class='clearfix'>
					<img id='gd-antispam' class='antispam_img left' src='{$this->settings['base_url']}app=core&amp;module=global&amp;section=captcha&amp;do=showimage&amp;captcha_unique_id={$captcha_unique_id}' />
					<a href='#' id='gd-image-link' class='ipsButton_secondary left' title="{$this->lang->words['captcah_new']}"><img src='{$this->settings['img_url']}/icon_refresh.png' alt="{$this->lang->words['captcah_new']}" /></a>
				</p>
				<input type="text" class='input_text' size="36" maxlength="20" value="" name="captcha_string" tabindex='0' id='captcha_string' />
			</div>
		</li>
	</ul>
</fieldset>
<script type='text/javascript'>
	ipb.global.initGD();
	$('captcha_string').defaultize( "{$this->lang->words['captcha_string_enter']}" );
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: captchaRecaptcha
//===========================================================================
function captchaRecaptcha($html="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<fieldset id='captcha' class='recaptcha'>
	<ul class='ipsForm <if test="$this->request['section'] == 'register' || $this->request['section'] == 'lostpass'">ipsForm_horizontal<else />ipsForm_vertical</if>'>
		<li class='ipsField'>
			<label class='ipsField_title'>{$this->lang->words['glb_captcha_image']} <span class='ipsForm_required'>*</span></label>
			<div class='ipsField_content'>
				{$html}
			</div>
		</li>
	</ul>
</fieldset>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: displayBoardOffline
//===========================================================================
function displayBoardOffline($message="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{parse expression="sprintf( $this->lang->words['board_offline_desc'], $this->settings['board_name'])"}</h1>
<div class='ipsType_pagedesc'>{$message}</div>
<br /><br />
<p>
	<if test="isGuest:|:!$this->memberData['member_id']">
		<a href='{parse url="app=core&amp;module=global&amp;section=login" base="public"}' title='{$this->lang->words['attempt_login']}' class='ipsButton'>{$this->lang->words['click_login']}</a>
	<else />
		<a href='{parse url="app=core&amp;module=global&amp;section=login&amp;do=logout&amp;k={$this->member->form_hash}" base="public"}' title='{$this->lang->words['log_out']}' class='ipsButton_secondary'>{$this->lang->words['log_out']}</a>
	</if>
</p>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: displayPopUpWindow
//===========================================================================
function displayPopUpWindow($documentHeadItems, $css, $jsLoaderItems, $title="", $output="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<!-- THIS TEMPLATE (displayPopUpWindow) IS DEPRICATED AND WILL BE REMOVED IN A FUTURE VERSION -->
		<meta http-equiv="content-type" content="text/html; charset={$this->settings['gb_char_set']}" />
		<title>{$title}</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<if test="hasimportcss:|:is_array( $css['import'] )">
			<if test="minifycss:|:$this->settings['use_minify']"><php>$this->minify = array();</php></if>
			<foreach loop="cssImport:$css['import'] as $data">
				<php>
					if( $this->settings['use_minify'] AND ( ! $data['attributes'] OR stripos( $data['attributes'], 'screen' ) !== false ) )
					{
						$this->minify[] = "{$data['content']}";
					}
				</php>
				<if test="donotminifycss:|:!$this->settings['use_minify'] OR ( $data['attributes'] AND stripos( $data['attributes'], 'screen' ) === false )">
					<link rel="stylesheet" type="text/css" {$data['attributes']} href="{$data['content']}?ipbv={$this->registry->output->antiCacheHash}" />
				</if>
			</foreach>
			<if test="csstominify:|:$this->settings['use_minify'] AND count($this->minify)">
				<link rel="stylesheet" type="text/css" media='screen,print' href="{$this->settings['css_base_url']}min/index.php?ipbv={$this->registry->output->antiCacheHash}&amp;f={parse expression="str_replace( $this->settings['css_base_url'], PUBLIC_DIRECTORY . '/', implode( ',', $this->minify ) )"}" />
			</if>
		</if>
		<if test="popupcssinline:|:is_array( $css['inline'] ) AND count( $css['inline'] )">
			<foreach loop="popupcssInline:$css['inline'] as $data">
			<style type="text/css" {$data['attributes']}>
				/* Inline CSS */
				{$data['content']}
			</style>
			</foreach>
		</if>
		<!--[if lte IE 7]>
			<link rel="stylesheet" type="text/css" title='Main' media="screen" href="{$this->settings['public_dir']}style_css/{$this->registry->output->skin['_csscacheid']}/ipb_ie.css" />
		<![endif]-->
		
		{parse template="includeRTL" group="global" params=""}	
		<if test="popupmetatags:|:is_array( $metaTags ) AND count( $metaTags )">
			<foreach loop="popupmetaTags:$metaTags as $tag => $content">
			<meta name="{$tag}" content="$content" />
			</foreach>
		</if>
		<script type='text/javascript'>
			jsDebug		= {parse expression="intval($this->settings['_jsDebug'])"}; /* Must come before JS includes */
			USE_RTE		= 1;
			var inACP	= false;
			var isRTL	= false;
			var rtlIe	= '';
			var rtlFull	= '';
		</script>
		<if test="popupminifyjs:|:$this->settings['use_minify']">
			<if test="popupminfyjsrl:|:$this->settings['remote_load_js']">
				<script type='text/javascript' src='<if test="popupminfiyrlhttpsp:|:$this->registry->output->isHTTPS">https<else />http</if>://ajax.googleapis.com/ajax/libs/prototype/1.7/prototype.js'></script>
				<script type='text/javascript' src='<if test="popupminfiyrlhttpss:|:$this->registry->output->isHTTPS">https<else />http</if>://ajax.googleapis.com/ajax/libs/scriptaculous/1.8/scriptaculous.js?load=effects,dragdrop,builder'></script>
			<else />
				<script type='text/javascript' src='{$this->settings['public_dir']}min/index.php?g=js'></script>
			</if>
			<script type='text/javascript' src='{$this->settings['public_dir']}min/index.php?f={parse expression="PUBLIC_DIRECTORY"}/js/ipb.js,{parse expression="PUBLIC_DIRECTORY"}/js/ips.quickpm.js<if test="popupjsmodules:|:count($jsLoaderItems) AND is_array($jsLoaderItems)">,{parse expression="PUBLIC_DIRECTORY"}/js/ips.{parse expression="implode('.js,' . PUBLIC_DIRECTORY . '/js/ips.', array_unique( array_keys( $jsLoaderItems ) ) )"}.js</if>,cache/lang_cache/{$this->lang->lang_id}/ipb.lang.js'></script>
		<else />
			<if test="popuprl:|:$this->settings['remote_load_js']">
				<script type='text/javascript' src='<if test="popuphttpsp:|:$this->registry->output->isHTTPS">https<else />http</if>://ajax.googleapis.com/ajax/libs/prototype/1.7/prototype.js'></script>
			<else />
				<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/prototype.js'></script>
			</if>
			<script type='text/javascript' src='{$this->settings['public_dir']}js/ipb.js?load=quickpm<if test="popupjsmodules:|:count($jsLoaderItems) AND is_array($jsLoaderItems)">,{parse expression="implode(',', array_unique( array_keys( $jsLoaderItems ) ) )"}</if>'></script>
			
			<if test="popuprl2:|:$this->settings['remote_load_js']">
				<script type='text/javascript' src='<if test="popuphttpss:|:$this->registry->output->isHTTPS">https<else />http</if>://ajax.googleapis.com/ajax/libs/scriptaculous/1.8/scriptaculous.js?load=effects,dragdrop,builder'></script>
			<else />
				<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/scriptaculous/scriptaculous-cache.js'></script>
			</if>
			<script type="text/javascript" src='{$this->settings['cache_dir']}lang_cache/{$this->member->language_id}/ipb.lang.js'></script>
		</if>
		<if test="popupdocumenthead:|:count($documentHeadItems)">
			<foreach loop="popupheaditemtype:$documentHeadItems as $type => $idx">
				<foreach loop="popupdocumentHeadItems:$documentHeadItems[ $type ] as $idx => $data">
					<if test="popupjavascript:|:$type == 'javascript'">
						<script type="text/javascript" src="{$data}" charset="<% CHARSET %>"></script>
					</if>
					<if test="popuprss:|:$type == 'rss'">
						<link rel="alternate feed" type="application/rss+xml" title="{$data['title']}" href="{$data['url']}" />
					</if>
					<if test="popuprss:|:$type == 'rsd'">
						<link rel="EditURI" type="application/rsd+xml" title="{$data['title']}" href="{$data['url']}" />
					</if>
					<if test="popupraw:|:$type == 'raw'">
						{$data}
					</if>
				</foreach>
			</foreach>
		</if>
		
		<script type='text/javascript'>
		//<![CDATA[
			/* ---- URLs ---- */
			ipb.vars['base_url'] 			= '{parse url="" base="public"}';
			ipb.vars['board_url']			= '{$this->settings['board_url']}';
			ipb.vars['loading_img'] 		= '{$this->settings['img_url']}/loading.gif';
			ipb.vars['active_app']			= '{$this->registry->getCurrentApplication()}';
			ipb.vars['upload_url']			= '{$this->settings['upload_url']}';
			/* ---- Member ---- */
			ipb.vars['member_id']			= parseInt( {$this->memberData['member_id']} ),
			ipb.vars['is_supmod']			= parseInt( {parse expression="intval($this->memberData['g_is_supmod'])"} ),
			ipb.vars['is_admin']			= parseInt( {$this->memberData['g_access_cp']} ),
			ipb.vars['secure_hash'] 		= '{$this->member->form_hash}';
			ipb.vars['session_id']			= '{$this->member->session_id}';
			ipb.vars['can_befriend']		= <if test="popupbefriend:|:$this->settings['friends_enabled'] AND $this->memberData['g_can_add_friends']">true<else />false</if>;
			ipb.vars['auto_dst']			= parseInt( {$this->memberData['members_auto_dst']} );
			ipb.vars['dst_in_use']			= parseInt( {$this->memberData['dst_in_use']} );
			ipb.vars['is_touch']			= <if test="istl:|:$this->registry->output->isLargeTouchDevice()">'large';<else /><if test="istm:|:$this->registry->output->isSmallTouchDevice()">'small';<else />false;</if></if>
			ipb.vars['member_group']		= {parse expression="json_encode( array( 'g_mem_info' => $this->memberData['g_mem_info'] ) )"}
			/* ---- cookies ----- */
			ipb.vars['cookie_id'] 			= '{$this->settings['cookie_id']}';
			ipb.vars['cookie_domain'] 		= '{$this->settings['cookie_domain']}';
			ipb.vars['cookie_path']			= '{$this->settings['cookie_path']}';
			/* ---- Rate imgs ---- */
			ipb.vars['rate_img_on']			= '{$this->settings['img_url']}/bullet_star.png';
			ipb.vars['rate_img_off']		= '{$this->settings['img_url']}/bullet_star_off.png';
			ipb.vars['rate_img_rated']		= '{$this->settings['img_url']}/bullet_star_rated.png';
			/* ---- Uploads ---- */
			ipb.vars['swfupload_swf']		= '{parse url="js/3rd_party/swfupload/swfupload.swf" base="public_dir" httpauth="true"}';
			ipb.vars['swfupload_enabled']	= <if test="popupswfupload:|:$this->settings['uploadFormType']">true<else />false</if>;
			ipb.vars['use_swf_upload']		= ( '{$this->memberData['member_uploader']}' == 'flash' ) ? true : false;
			ipb.vars['swfupload_debug']		= false;
			/* ---- other ---- */
			ipb.vars['highlight_color']		= "#ade57a";
			ipb.vars['charset']				= "{$this->settings['gb_char_set']}";
			ipb.vars['use_rte']				= 1;
			ipb.vars['image_resize_force']  = {parse expression="intval($this->settings['resize_img_force'])"};
			ipb.vars['seo_enabled']			= {parse expression="intval($this->settings['use_friendly_urls'])"};
			<if test="popupfurl:|:$this->settings['use_friendly_urls']">
			ipb.vars['seo_params']			= {parse expression="json_encode($this->registry->getClass('output')->seoTemplates['__data__'])"};
			</if>
			/* Templates/Language */
			ipb.templates['ajax_loading'] 	= "<div id='ajax_loading'>" + ipb.lang['loading'] + "</div>";
			ipb.templates['close_popup']	= "<img src='{$this->settings['img_url']}/close_popup.png' alt='x' />";
			ipb.templates['rss_shell']		= new Template("<ul id='rss_menu'>#{items}</ul>");
			ipb.templates['rss_item']		= new Template("<li><a href='#{url}' title='#{title}'>#{title}</a></li>");
			ipb.templates['resized_img']	= new Template("<span>{$this->lang->words['resized_image']}</span>");
			<if test="$this->memberData['member_id']">
				ipb.templates['m_add_friend']	= new Template("<a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=add&amp;member_id=#{id}" base="public"}' title='{$this->lang->words['add_friend']}'>{parse replacement="add_friend"}</a>");
				ipb.templates['m_rem_friend']	= new Template("<a href='{parse url="app=members&amp;module=profile&amp;section=friends&amp;do=remove&amp;member_id=#{id}" base="public"}' title='{$this->lang->words['remove_friend']}'>{parse replacement="remove_friend"}</a>");
			</if>
			ipb.templates['autocomplete_wrap'] = new Template("<ul id='#{id}' class='ipb_autocomplete' style='width: 250px;'></ul>");
			ipb.templates['autocomplete_item'] = new Template("<li id='#{id}' data-url='#{url}'><img src='#{img}' alt='' width='#{img_w}' height='#{img_h}' />&nbsp;&nbsp;#{itemvalue}</li>");
			ipb.templates['page_jump']		= new Template("<div id='#{id}_wrap' class='ipbmenu_content'><h3 class='bar'>{$this->lang->words['global_page_jump']}</h3><input type='text' class='input_text' id='#{id}_input' size='8' /> <input type='submit' value='{$this->lang->words['jmp_go']}' class='input_submit add_folder' id='#{id}_submit' /></div>");
			Loader.boot();
		//]]>
		</script>
	</head>
	<body id='ipboard_body' style='padding: 20px'>
		<div id='ipbwrapper'>
			{$output}
		</div>
	</body>
</html>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: Error
//===========================================================================
function Error($message="",$code=0,$ad_email_one="",$ad_email_two="", $title="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type="text/javascript">
<!--
function contact_admin() {
  // Very basic spam bot stopper
  admin_email_one = '{$ad_email_one}';
  admin_email_two = '{$ad_email_two}';
  window.location = 'mailto:'+admin_email_one+'@'+admin_email_two+'?subject={$this->lang->words['mailto_erroronforums']}';  
}
//-->
</script>
<br />
<h1 class='ipsType_pagetitle'>{$title}</h1>
<br />
<div class='ipsBox'>
	<div class='ipsBox_container ipsPad'>
		<span class="right desc ipsType_smaller ipsPad_top"><if test="haserrorcode:|:$code">[#{$code}] </if></span>
		<p class='ipsType_sectiontitle'>
			{$message}
		</p>
		<br />
		<p>{$this->lang->words['er_useful_links']}</p>
		<ul class='ipsPad_top bullets'>
			<if test="! $this->memberData['member_id']">
				<li><a href='{parse url="app=core&amp;module=global&amp;section=login" base="public"}' title='{$this->lang->words['submit_li']}'>{$this->lang->words['click_login']}</a></li>
			</if>
			<li><a href="{parse url="app=core&module=help" base="public"}" rel="help" title='{$this->lang->words['er_help_files']}'>{$this->lang->words['er_help_files']}</a></li>
			<li><a href="javascript:contact_admin();" title='{$this->lang->words['er_contact_admin']}'>{$this->lang->words['er_contact_admin']}</a></li>
		</ul>
	</div>
</div>
<if test="savedpost:|: $_POST['Post']">
	<br />
	<br />
	<h2 class='ipsType_subtitle'>{$this->lang->words['err_title']}</h2><br />
	<div class='ipsBox'>
		<div class='ipsBox_container ipsPad'>
			{$this->lang->words['err_expl']}<br />
			<br />
			{parse editor="Post" content="" options="array( 'editorName' => 'Post', 'type' => 'full', 'minimize' => 0, 'recover' => TRUE )"}
		</div>
	</div>
<br />
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: facebookDone
//===========================================================================
function facebookDone($user) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='ipsForm_center'>
	<br />
	<strong>{$this->lang->words['facebook_post_ok']}</strong>
	<br />
	<br />
	<br />
	<br />
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: facebookPop
//===========================================================================
function facebookPop($user=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['share_via_facebook']}</h3>
<div class='fixed_inner'>
	<div class='ipsPad'><fb:like href="" id="fbLikeButton" layout="standard" show_faces="false" width="350" height="45" action="like" colorscheme="light" /></div>
	<if test="hasFbConnected:|:$user['id']">
		<ul class='ipsList_withminiphoto' id="fParent">
			<li class='clearfix ipsPad_half'>
				<img src='{$user['pic_square']}' class='left ipsUserPhoto ipsUserPhoto_small'/>
				<img src="{$this->settings['public_dir']}style_status/facebook.png" class='left' style='margin-top: -2px; margin-left: -12px;'/>
				<div class='list_content' style='margin-left:58px' id='fWrap' >
					<textarea class='input_text' rows='3' cols='10' name='fContent' style='width: 97%;' id='fContent'></textarea>
					<div>
						<div class='right clear'>
							<input type='button' id='fSubmit' class='input_submit' value='{$this->lang->words['facebook_share']}' />
						</div>
						<div class='ipsType_smaller desc'>{$this->lang->words['share_fb_name']} {$user['first_name']} {$user['last_name']}</div>
					</div>
					
				</div>
			</li>
		</ul>
	<else />
		<div class='ipsPad ipsForm_center'><a href="{$this->settings['_original_base_url']}/interface/facebook/index.php"><img src="{$this->settings['img_url']}/facebook_login.png" alt="" /></a></div>
	</if>
	<div id="fb-root"></div>
	<script>
		window.fbAsyncInit = function() {
			FB.init({appId: '{$this->settings['fbc_appid']}', status: true, cookie: true, xfbml: true});
			if ( $('fParent') )
			{
				FB.Event.subscribe('edge.create', function(response) {
	 				new Effect.Fade( $('fParent'), { duration: 0.2 } );
				});
						
				FB.Event.subscribe('edge.remove', function(response) {
	 				new Effect.Appear( $('fParent'), { duration: 0.2 } );
				});
			}
		};
		(function() {
			var e = document.createElement('script'); e.async = true;
		    e.src = document.location.protocol + '//connect.facebook.net/{$this->settings['fb_locale']}/all.js';
			document.getElementById('fb-root').appendChild(e);
				    
			$('fbLikeButton').writeAttribute('href', $('ipsCanonical').readAttribute('href') );
			
			if ( $('fContent') )
			{
				$('fContent').defaultize('{$this->lang->words['fb_share_default']}');
			}
		}());
	</script>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: followUnsubscribe
//===========================================================================
function followUnsubscribe($data, $meta) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form action="{parse url="app=core&amp;module=global&amp;section=like&amp;do=doUnsubscribe" base="public"}" method="post">
<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
<input type='hidden' name='like_rel_id' value='{$data[ $this->memberData['member_id'] ]['like_rel_id']}' />
<input type='hidden' name='like_area' value='{$data[ $this->memberData['member_id'] ]['like_area']}' />
<input type='hidden' name='like_app' value='{$data[ $this->memberData['member_id'] ]['like_app']}' />
<input type='hidden' name='like_id' value='{$data[ $this->memberData['member_id'] ]['like_id']}' />
<h3 class='maintitle'>{$this->lang->words['pg_unfollow_title']}</h3>
<div class='ipsBox'>
	<div class='ipsBox_container ipsPad_double ipsType_textblock'>
		{parse expression="sprintf( $this->lang->words['pg_unfollow_text'], $data[ $this->memberData['member_id'] ]['members_display_name'], $meta['like.url'], $meta['like.title'] )"}
		<br />
		<br />
		<p>
			<input type="submit" value="{$this->lang->words['pg_unfollow_confirm']}" class='ipsButton'>
			{$this->lang->words['or']}
			<a href='{parse url="app=core&amp;module=search&amp;do=followed" base="public"}' title='{$this->lang->words['cancel']}' class='cancel' tabindex='0'>{$this->lang->words['cancel']}</a>
		</p>
	</div>
</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: generateTopicIcon
//===========================================================================
function generateTopicIcon($imgArray, $unreadUrl) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="topicIsMoved:|:$imgArray['is_moved']">
	<span title="{$this->lang->words['pm_moved']}">{parse replacement="t_moved"}</span>
</if>
<if test="topicIsClosed:|:$imgArray['is_closed']">
	<span title="{$this->lang->words['pm_locked']}">{parse replacement="t_closed"}</span>
</if>
<if test="topicReadDot:|:$imgArray['show_dot'] && empty( $unreadUrl )">{parse replacement="t_read_dot"}</if>
<if test="gotolatestwrap:|: ! empty( $unreadUrl ) && ! $imgArray['is_moved']">
	<a href='{$unreadUrl}' title='{$this->lang->words['first_unread_post']}'>
	<if test="topicUnreadDot:|:$imgArray['show_dot']">
		{parse replacement="t_unread_dot"}
	<else />
		{parse replacement="t_unread"}
	</if>
	</a>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: globalTemplateMinimal
//===========================================================================
function globalTemplateMinimal($html, $documentHeadItems, $css, $jsModules, $metaTags, array $header_items, $items=array(), $footer_items=array(), $stats=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<!DOCTYPE html>
	<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml"<if test="fbcenabled:|:IPSLib::fbc_enabled() === TRUE"> xmlns:fb="http://www.facebook.com/2008/fbml"</if>>
	<head>
		<meta http-equiv="content-type" content="text/html; charset={$this->settings['gb_char_set']}" />
		<title>{$header_items['title']}<if test="pagenumberintitle:|:$header_items['page']"> {$this->lang->words['page_title_page']} {$header_items['page']}</if></title>
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
		{parse template="includeMeta" group="global" params="$metaTags"}
		<meta property="og:title" content="{IPSText::htmlspecialchars( str_replace( ' - ' . $this->settings['board_name'], '', $header_items['title'] ) )}"/>
		<meta property="og:site_name" content="{IPSText::htmlspecialchars( $this->settings['board_name'] )}"/>
		<meta property="og:image" content="{$this->settings['meta_imagesrc']}"/>
		<if test="isLargeTouch:|:$this->registry->output->isLargeTouchDevice()">
		<meta name="viewport" content="width=1024px; initial-scale=1.0; minimum-scale=1.0;">
		</if>
		<if test="isSmallTouch:|:$this->registry->output->isSmallTouchDevice()">
		<meta name="viewport" content="width=1024px">
		</if>
		{parse template="includeJS" group="global" params="$jsModules"}
		{parse template="includeFeeds" group="global" params="$documentHeadItems"}
		{parse template="includeRTL" group="global" params=""}		
		{parse template="includeVars" group="global" params="$header_items"}
	</head>
	<body id='ipboard_body' class='minimal'>
		<p id='content_jump' class='hide'><a id='top'></a><a href='#j_content' title='{$this->lang->words['jump_to_content']}' accesskey='m'>{$this->lang->words['jump_to_content']}</a></p>
		<div id='ipbwrapper'>		
			<div id='content' class='clearfix'>
				{$items['adHeaderCode']}
				<if test="mainpageContent:|:$html">{$html}</if>
				{$items['adFooterCode']}
			</div>
			<div id='footer_utilities' class='main_width clearfix clear'>
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
		</div>
		
		<!--DEBUG_STATS-->
	</body>
</html>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: inboxList
//===========================================================================
function inboxList($topics) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h4 class='ipsType_sectiontitle'>{$this->lang->words['inbox_at_the_top']}<p class='ipsPad_half ipsType_smaller right'><a href='{parse url="app=members&amp;module=messaging" base="public"}' class='configure'>{$this->lang->words['inbox_list_view']}</a> &middot; <a href='{parse url="module=messaging&amp;section=send&amp;do=form" base="publicWithApp"}' title='{$this->lang->words['compose_new']}'>{$this->lang->words['compose_new']}</a></p></h4>
<ul class='ipsList_withminiphoto'>
	<if test="hasTopics:|:count($topics)">
		<foreach loop="loopynotify:$topics as $topic">
		<li class='<if test="$topic['map_has_unread']">unread</if> ipsType_small clearfix'>
			<img src='{$topic['_starterMemberData']['pp_mini_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$topic['_starterMemberData']['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_mini left' />
			<div class='list_content'>
				<a href='{parse url="app=members&amp;module=messaging&amp;section=view&amp;do=findMessage&amp;topicID={$topic['mt_id']}&amp;msgID=__firstUnread__" base="public"}'>
				<if test="$topic['map_has_unread']"><strong></if>{$topic['mt_title']}<if test="$topic['map_has_unread']"></strong></if>
				</a>
				<br />
				<span class='ipsType_smaller desc lighter'>{$topic['_starterMemberData']['members_display_name']} - {parse date="$topic['msg_date']" format="short"}</span>
			</div>
		</li>
		</foreach>
	<else />
		<li class='row1 ipsPad_half ipsType_smaller'>{$this->lang->words['inbox_list_none']}</li>
	</if>
</ul>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: inlineUploaderComplete
//===========================================================================
function inlineUploaderComplete($json) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<html>
<head>
</head>
<body>
<script type='text/javascript'>
	parent.ipb.inlineUploader.complete( $json );
</script>
</body>
</html>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: likeMoreDialogue
//===========================================================================
function likeMoreDialogue($data, $relId, $cache) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['followed_by']}</h3>
<div class='likepop fixed_inner'>
	<if test="hasVisibleEntries:|:is_array($data) && count($data)">
		<ul class='ipsList_withminiphoto'>
			<foreach loop="$data as $mid => $data">
				<li class='clearfix ipsPad_half <if test="$data['like_is_anon']">faded</if>'>
					<a href='{parse url="showuser={$data['member_id']}" seotitle="{$data['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'>
						<img src='{$data['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' />
					</a>
					<div class='list_content'>
						<a href='{parse url="showuser={$data['member_id']}" seotitle="{$data['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}'><strong>{$data['members_display_name']}</strong></a>
						<if test="$data['like_is_anon']"><em class='desc'>{$this->lang->words['followed_anonymously']}</em></if>
						<p class='desc'>
							{parse expression="IPSMember::makeNameFormatted( $data['g_title'], $data['g_id'] )"}
						</p>
					</div>
				</li>
			</foreach>
		</ul>
	</if>
	<if test="hasAnonEntries:|:$cache['anonCount']">
		<p class='desc ipsPad ipsForm_center'>
			(<if test="count( $data )">{$this->lang->words['fave_and']}</if> {parse expression="sprintf( ($cache['anonCount'] > 1) ? $this->lang->words['follow_anon_count_p'] : $this->lang->words['follow_anon_count_s'], $cache['anonCount'] )"})
		</p>
	</if>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: likeSetDialogue
//===========================================================================
function likeSetDialogue($app, $area, $relid, $data) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{parse expression="$this->lang->words['like_ucfirst_' . $data['vernacular'] ]"}</h3>
<fieldset class='ipsPad ipsForm ipsForm_vertical'>
	<ul>
		<li class='ipsField ipsField_checkbox'>
			<input type="checkbox" name="notify" id="like_notify" class="input_check" value="1" checked='checked' />
			<p class='ipsField_content'>
				<label for="like_notify">{$this->lang->words['label_notify']}</label>
				<select name="freq" id="like_freq" class="input_select">
					<foreach loop="$data['frequencies'] as $freq">
						<option value="$freq">{$this->lang->words[ 'freq_' . $freq ]}</option>
					</foreach>
				</select>
			</p>
		</li>
		<li class='ipsField ipsField_checkbox'>
			<input type="checkbox" name="anon" id="like_anon" class="input_check" value="1" />
			<p class='ipsField_content'>
				<label for="like_anon">{$this->lang->words['label_anon']}<br /><span class='desc lighter'>{$this->lang->words['label_anon_desc']}</span></label>
			</p>
		</li>
		<li class="submit ipsForm_center">
			<input type="submit" value="{parse expression="sprintf( $this->lang->words['set_fave_button'], $this->lang->words['like_ucfirst_' . $data['vernacular'] ])"}" class="input_submit _fsubmit" />
		</li>
</fieldset>
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
<div class='ips_like' data-app="{$data['app']}" data-area="{$data['area']}" data-relid="{$relId}" data-isfave="{$data['iLike']}">
	{parse template="likeSummaryContents" group="global_other" params="$data, $relId, $opts"}
</div>
<script type="text/javascript">
	var FAVE_TEMPLATE = new Template( "<h3>{parse expression="sprintf( $this->lang->words['unset_fave_title'], $this->lang->words['like_ucfirst_un' . $data['vernacular'] ])"}</h3><div><span class='desc'>{parse expression="sprintf( $this->lang->words['unset_fave_words'], $this->lang->words['like_un' . $data['vernacular'] ])"}</span><br /><p style='text-align:right'><input type='button' value='{parse expression="sprintf( $this->lang->words['unset_button'], $this->lang->words['like_ucfirst_un' . $data['vernacular'] ])"}' class='input_submit _funset' /></p></div>");
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
<if test="$data['formatted']">{$data['formatted']}<else />&nbsp;</if>
<if test="likeOnlyMembers:|:$this->memberData['member_id']">
	<a href='#' title="<if test="$data['iLike']">{parse expression="sprintf( $this->lang->words['fave_tt_on'], $this->lang->words['like_ucfirst_un' . $data['vernacular'] ])"}<else />{parse expression="sprintf( $this->lang->words['fave_tt_off'], $this->lang->words['like_ucfirst_' . $data['vernacular'] ])"}</if>" class='ftoggle <if test="$data['iLike']"> on</if><if test="$opts['button-new-line']"> _newline</if>'><if test="$data['iLike']">{parse expression="sprintf( $this->lang->words['unset_fave_button'], $this->lang->words['like_ucfirst_un' . $data['vernacular'] ])"}<else />{parse expression="sprintf( $this->lang->words['set_fave_button'], $this->lang->words['like_ucfirst_' . $data['vernacular'] ])"}</if></a>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: notificationsList
//===========================================================================
function notificationsList($notifications) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h4 class='ipsType_sectiontitle'>{$this->lang->words['notifications_at_the_top']}
	<p class='ipsPad_half ipsType_smaller right'>
	<a href='{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=notificationlog" base="public"}' class='configure'>{$this->lang->words['notifications_viewall']}</a>
	&middot; <a href='{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=notifications" base="public"}' class='configure'>{$this->lang->words['generic_opts_link']}</a>
	</p>
</h4>
<ul class='ipsList_withminiphoto'>
	<if test="hasnotifications:|:count($notifications)">
		<foreach loop="loopynotify:$notifications as $notify">
		<li class='<if test="!$notify['notify_read']">unread</if> ipsType_small clearfix'>
			<img src='{$notify['member']['pp_mini_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$notify['member']['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_mini left' />
			<div class='list_content'>
				<if test="strpos( $notify['notify_title'], '<a href' ) === false">
					<a href='{parse url="app=core&amp;module=usercp&amp;tab=core&amp;area=viewNotification&amp;do=view&amp;view={$notify['notify_id']}" base="public"}'>
				</if>
				<if test="!$notify['notify_read']"><strong></if>{$notify['notify_title']}<if test="!$notify['notify_read']"></strong></if>
				<if test="strpos( $notify['notify_title'], '<a href' ) === false">
					</a>
				</if>
				<br />
				<span class='ipsType_smaller desc lighter'>{parse date="$notify['notify_sent']" format="short"}</span>
			</div>
		</li>
		</foreach>
	<else />
		<li class='desc'>{$this->lang->words['nonotifications_list']}</li>
	</if>
</ul>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: privacyPolicy
//===========================================================================
function privacyPolicy($title, $text) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h2 class='ipsType_pagetitle'>{$title}</h2>
<div class='row2 ipsPad' style='line-height: 1.6'>
	{$text}
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: questionAndAnswer
//===========================================================================
function questionAndAnswer($data) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<fieldset id='q_n_a'>	
	<input type="hidden" name="qanda_id" value="{$data['qa_id']}" />
	<ul class='ipsForm ipsForm_horizontal'>
		<li class='ipsField'>
			<label for='qna' class='ipsField_title'>{$this->lang->words['registration_question']} <span class='ipsForm_required'>*</span></label>
			<div class='ipsField_content'>
				<strong>{$data['qa_question']}</strong><br />
				<input type="text" class='input_text' size="45" maxlength="100" value="<if test="qadefault:|:$this->request['qanda_id'] AND $this->request['qanda_id']==$data['qa_id']">{$this->request['qa_answer']}</if>" name="qa_answer" id='qna' tabindex='0' /><br />
				<span class='desc lighter'>{$this->lang->words['qa_question_desc']}</span>
			</div>
		</li>
	</ul>
</fieldset>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: quickNavigationOffline
//===========================================================================
function quickNavigationOffline($message) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['navigation_title']}</h3>
<div class='fixed_inner ipsBox'>
	<div class='message error'><strong>{$this->lang->words['board_offline_desc']}</strong><br /><br />{$message}</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: quickNavigationPanel
//===========================================================================
function quickNavigationPanel($data=array(), $currentApp='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div>
	<foreach loop="outer:$data as $_data">
		<if test="hasTitle:|: ! empty( $_data['title'] )"><div class='ipsPad row2'><strong>{$_data['title']}</strong></div></if>
		<ul class='block_list'>
		<if test="linksisarray:|:is_array($_data['links']) AND count($_data['links'])">
			<foreach loop="innerAgain:$_data['links'] as $link">
		 		<li><if test="depth:|:!empty($link['depth'])">{parse expression="str_repeat( '&nbsp;&nbsp;&nbsp;&nbsp;', $link['depth'])"}</if><a <if test="important:|:!empty($link['important'])">style="font-weight:bold"</if> href="{$link['url']}">{$link['title']}</a></li>
			</foreach>
		</if>
		</ul>
	</foreach>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: quickNavigationWrapper
//===========================================================================
function quickNavigationWrapper($tabs=array(), $data=array(), $currentApp='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['navigation_title']}</h3>
<div class='fixed_inner ipsBox'>
	<if test="isLargeTouch:|:$this->registry->output->isLargeTouchDevice()">
		<div class='message' style='margin-bottom: 5px;'>{$this->lang->words['scroll_tip']}</div>
	</if>
	<div class='ipsVerticalTabbed ipsLayout ipsLayout_withleft ipsLayout_smallleft clearfix'>
		<div class='ipsVerticalTabbed_tabs ipsLayout_left'>		
			<ul>
				<foreach loop="navTabs:$tabs as $app => $title">
					<li <if test="active:|:$app == $currentApp">class='active'</if>><a href='{parse url="app=core&amp;module=global&amp;section=navigation&amp;inapp={$app}" base="public"}' rel="ipsQuickNav" data-app="{$app}">{$title}</a></li>
				</foreach>
			</ul>
		</div>
	
		<div class='ipsVerticalTabbed_content ipsLayout_content ipsBox_container' style='position: relative' id='ipsNav_content'>
			{parse template="quickNavigationPanel" group="global_other" params="$data, $currentApp"}
		</div>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: redirectTemplate
//===========================================================================
function redirectTemplate($documentHeadItems, $css, $jsLoaderItems, $text="",$url="", $full=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
	    <meta http-equiv="content-type" content="text/html; charset=<% CHARSET %>" /> 
		<title>{$this->lang->words['stand_by']}</title>
		<if test="redirectfull:|:$full==true">
			<meta http-equiv="refresh" content="2; url={parse url="$url" base="none"}" />
		<else />
			<meta http-equiv="refresh" content="2; url={parse url="$url" base="public"}" />
		</if>
		<link rel="shortcut icon" href='<if test="$this->registry->output->isHTTPS">{$this->settings['board_url_https']}<else />{$this->settings['board_url']}</if>/favicon.ico' />
		<if test="redirectcssimport:|:is_array( $css['import'] )">
			<foreach loop="redirectcssImport:$css['import'] as $data">
				<link rel="stylesheet" type="text/css" {$data['attributes']} href="{$data['content']}">
			</foreach>
		</if>
		<if test="redirectcssinline:|:is_array( $css['inline'] ) AND count( $css['inline'] )">
			<foreach loop="redirctcssInline:$css['inline'] as $data">
				<style type="text/css" {$data['attributes']}>
					/* Inline CSS */
					{$data['content']}
				</style>
			</foreach>
		</if>
		<!--[if lte IE 7]>
			<link rel="stylesheet" type="text/css" title='Main' media="screen" href="{$this->settings['public_dir']}style_css/{$this->registry->output->skin['_csscacheid']}/ipb_ie.css" />
		<![endif]-->
		
		{parse template="includeRTL" group="global" params=""}	
		<script type='text/javascript'>
			jsDebug		= 0; /* Must come before JS includes */
			var isRTL	= false;
			var rtlIe	= '';
			var rtlFull	= '';
		</script>
		<if test="redirectdh:|:count($documentHeadItems)">
			<foreach loop="redirectdocumentHeadItems:$documentHeadItems as $type => $idx">
				<foreach loop="redirectdocumentHeadItemsSub:$documentHeadItems[ $type ] as $idx => $data">
					<if test="redirectjs:|:$type == 'javascript'">
						<script type="text/javascript" src="{$data}" charset="<% CHARSET %>"></script>
					</if>
					<if test="redirectrss:|:$type == 'rss'">
						<link rel="alternate" type="application/rss+xml" title="{$data['title']}" href="{$data['url']}" />
					</if>
					<if test="redirectrsd:|:$type == 'rsd'">
						<link rel="EditURI" type="application/rsd+xml" title="{$data['title']}" href="{$data['url']}" />
					</if>
					<if test="redirectraw:|:$type == 'raw'">
						$data
					</if>
				</foreach>
			</foreach>
		</if>
		<!--/CSS-->
		<script type='text/javascript'>
		//<![CDATA[
		// Fix Mozilla bug: 209020
		if ( navigator.product == 'Gecko' )
		{
			navstring = navigator.userAgent.toLowerCase();
			geckonum  = navstring.replace( /.*gecko\/(\d+)/, "$1" );
			setTimeout("moz_redirect()",1500);
		}
		function moz_redirect()
		{
			<if test="redirectmozfull:|:$full==true">
				var url_bit     = "{parse url="$url" base="none"}";
			<else />
				var url_bit     = "{parse url="$url" base="public"}";
			</if>
			window.location = url_bit.replace( new RegExp( "&amp;", "g" ) , '&' );
		}
		//>
		</script>
	</head>
	<body  id='ipboard_body' class='redirector'>
		<div id='ipbredirectwrapper'>
			<p class='message'>
				<strong>{$text}</strong>
				<br /><br />
				{$this->lang->words['transfer_you']}
				<br />
				<span class='desc'>(<a href="<if test="redirectlink:|:$full==true">{parse url="{$url}" base="none"}<else />{parse url="{$url}" base="public"}</if>">{$this->lang->words['dont_wait']}</a>)</span>	
			</p>
		</div>
	</body>
</html>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: repButtons
//===========================================================================
function repButtons($member, $data=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>
	// Apps can set the key empty to disable, but this will allow all apps to use the popup to show who repped
	if( !isset($data['jsCallback']) )
	{
		$data['jsCallback']	= "ipb.global.repPopUp( this, {$data['primaryId']}, '{$data['app']}', '{$data['type']}' );";
	}
	$repClickable	= ( $this->memberData['gbw_view_reps'] AND $data['jsCallback'] ) ? 'onclick="' . $data['jsCallback'] . '"' : '';
</php>
<if test="reputationBox:|:$this->settings['reputation_enabled']">
	<if test="canRep:|:!( $this->settings['reputation_protected_groups'] && in_array( $member['member_group_id'], explode( ',', $this->settings['reputation_protected_groups'] ) ) ) and $this->memberData['member_id']">	
		<if test="isLike:|:$this->settings['reputation_point_types'] == 'like'">
			<div class='ipsLikeBar right clearfix' id='{$data['domCountId']}'>
				<ul class='ipsList_inline'>
					<if test="!isset($data['hide_text']) OR !$data['hide_text']">
						<li id="{$data['domLikeStripId']}" class='ipsLikeBar_info' <if test="hasNoLikes:|:!$data['likeFormatted']">style="display:none"</if>>
							{$data['likeFormatted']}
						</li>
					</if>
					<if test="canGiveRep:|:IPSMember::canGiveRep( $data, $member ) !== false">
						<li <if test="giveRepUp:|:IPSMember::canRepUp( $data, $member ) === false">style="display:none"</if>>
							<a class='ipsLikeButton ipsLikeButton_enabled rep_up' href='{$this->settings['base_url']}app=core&amp;module=global&amp;section=reputation&amp;do=add_rating&amp;app_rate={$data['app']}&amp;type={$data['type']}&amp;type_id={$data['primaryId']}&amp;rating=1&amp;secure_key={$this->member->form_hash}&amp;post_return={$data['primaryId']}' title='{$this->lang->words['do_like_up']}'>{$this->lang->words['like_this']}</a>
						</li>
						<li <if test="giveRepDown:|:IPSMember::canRepDown( $data, $member ) === false">style="display:none"</if>>
							<a class='ipsLikeButton ipsLikeButton_disabled rep_down' href='{$this->settings['base_url']}app=core&amp;module=global&amp;section=reputation&amp;do=add_rating&amp;app_rate={$data['app']}&amp;type={$data['type']}&amp;type_id={$data['primaryId']}&amp;rating=-1&amp;secure_key={$this->member->form_hash}&amp;post_return={$data['primaryId']}' title='{$this->lang->words['do_like_down']}'>{$this->lang->words['unlike_this']}</a>
						</li>
					</if>
				</ul>
			</div>
		<else />
			<div class='rep_bar clearfix <if test="pos:|:!$data['position'] || $data['position'] == 'right'">right</if>' id='{$data['domCountId']}'>
				{parse variable="repClickable" default="" oncondition="$this->memberData['gbw_view_reps']" value=" clickable"}
				<ul class='ipsList_inline'>
					<if test="canGiveRep:|:IPSMember::canGiveRep( $data, $member ) !== false">
						<li <if test="giveRepUp:|:IPSMember::canRepUp( $data, $member ) === false">style="display:none"</if>>
							<a href='{$this->settings['base_url']}app=core&amp;module=global&amp;section=reputation&amp;do=add_rating&amp;app_rate={$data['app']}&amp;type={$data['type']}&amp;type_id={$data['primaryId']}&amp;rating=1&amp;secure_key={$this->member->form_hash}&amp;post_return={$data['primaryId']}' class='rep_up' title='{$this->lang->words['reputation_up']}'>{parse replacement="rep_up"}</a>
						</li>
						<li <if test="giveRepDown:|:IPSMember::canRepDown( $data, $member ) === false">style="display:none"</if>>
							<a href='{$this->settings['base_url']}app=core&amp;module=global&amp;section=reputation&amp;do=add_rating&amp;app_rate={$data['app']}&amp;type={$data['type']}&amp;type_id={$data['primaryId']}&amp;rating=-1&amp;secure_key={$this->member->form_hash}&amp;post_return={$data['primaryId']}' class='rep_down' title='{$this->lang->words['reputation_down']}'>{parse replacement="rep_down"}</a>
						</li>
					</if>
					<if test="isNotLike:|:$this->settings['reputation_point_types'] != 'like'">
						<if test="hasNoRep:|:$data['rep_points'] == 0">
							<li><span class='reputation zero rep_show{parse variable="repClickable"}' title='{$this->lang->words['reputation']}' {$repClickable}>
						</if>
						<if test="hasPosRep:|:$data['rep_points'] > 0">
							<li><span class='reputation positive rep_show{parse variable="repClickable"}' title='{$this->lang->words['reputation']}' {$repClickable}>
						</if>
						<if test="hasNegRep:|:$data['rep_points'] < 0">
							<li><span class='reputation negative rep_show{parse variable="repClickable"}' title='{$this->lang->words['reputation']}' {$repClickable}>
						</if>
							{parse expression="intval($data['rep_points'])"}
							</span>
						</li>
					</if>
				</ul>
			</div>
		</if>
	</if>
</if>
<script type='text/javascript'>
	ipb.global.registerReputation( '{$data['domCountId']}', { domLikeStripId: '{$data['domLikeStripId']}', app: '{$data['app']}', type: '{$data['type']}', typeid: '{$data['primaryId']}' }, parseInt('{$data['rep_points']}') );
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: repMoreDialogue
//===========================================================================
function repMoreDialogue($data, $relId) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['liked_by_title']}</h3>
<div class='likepop fixed_inner'>
	<ul class='ipsList_withminiphoto'>
		<foreach loop="$data as $mid => $data">
			<li class='clearfix ipsPad_half <if test="$data['like_is_anon']">faded</if>'>
				<a href='{parse url="showuser={$data['member_id']}" seotitle="{$data['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'>
					<img src='{$data['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' />
				</a>
				<div class='list_content'>
					<a href='{parse url="showuser={$data['member_id']}" seotitle="{$data['members_seo_name']}" template="showuser" base="public"}' title='{$this->lang->words['view_profile']}'><strong>{$data['members_display_name']}</strong></a>
					<em class='desc'>{$this->lang->words['likeadded']} {parse date="$data['rep_date']" format="short"}</em>
					<p class='desc'>
						{parse expression="IPSMember::makeNameFormatted( $data['g_title'], $data['g_id'] )"}
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
// Name: reputationPopup
//===========================================================================
function reputationPopup($reps) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div>
	<h3>{$this->lang->words['rep_given']}</h3>
	<div class='ipsPad' style='min-height: 60px'>
		<if test="empty($reps)">
			<em>{$this->lang->words['no_rep']}</em>
		<else />
			<foreach loop="$reps as $r">
				<if test="$r['rep_rating'] > 0">
					{parse replacement="rep_up"}
				<else />
					{parse replacement="rep_down"}
				</if>
				 <a href='{parse url="showuser={$r['member']['member_id']}" base="public" template="showuser" seotitle="{$r['member']['members_seo_name']}"}'>{$r['member']['members_display_name']}</a><br />
			</foreach>
		</if>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: socialSharePostStrip
//===========================================================================
function socialSharePostStrip($id=null) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="facebook"}
<php>
	$prefs = IPSMember::getFromMemberCache( $this->memberData, 'postSocialPrefs' );
	$id    = ( $id === null ) ? '' : $id .= '_';
</php>
<if test="canShare:|:$this->settings['sm_autoshare'] && IPSMember::canSocialShare()">
 <div class='ipsBox_container ipsMargin_top ipsPad_top'>
	<ul class='ipsPad ipsForm_vertical ipsList_inline ipsType_small clearfix'>
		<foreach loop="services:array('facebook', 'twitter') as $service">
			<if test="canShareThis:|:IPSMember::canSocialShare( $service )">
			<li class='ipsField ipsField_checkbox left' style='margin-right: 40px'>
				<input type="checkbox" name="{$id}share_x_{$service}" class="input_check _share_x_" value="yes" id='{$id}share_x_{$service}' <if test="! empty( $_REQUEST['share_' . $service ] ) || ! empty( $prefs[$service] )">checked='checked'</if> /> &nbsp;&nbsp;
				<span class='' data-tooltip='{parse expression="sprintf( $this->lang->words['gbl_post_to_x_tt'],UCFirst($service) )"}'>
					<label for='{$id}share_x_{$service}'> <img src="{$this->settings['public_dir']}style_extra/sharelinks/{$service}.png" style='vertical-align:top' alt='' /> &nbsp; {parse expression="sprintf( $this->lang->words['gbl_post_to_x'], UCFirst($service) )"}</label>
				</span>
			</li>
			</if>
		</foreach>
		<if test="canSave:|: ! $id">
			<li class='ipsField left' style='display: none' id='{$id}_save_share'>
				<a href='#' data-clicklaunch='saveSocialShareDefaults' class='ipsType_smaller'>{$this->lang->words['remember_share_prefs']}</a>
			</li>
		</if>
	</ul>
</div>
	<script type="text/javascript">
		ipb.delegate.register('._share_x_', function(e, elem){
			if( $('{$id}_save_share') && !$('{$id}_save_share').visible() ){
				new Effect.Appear( $('{$id}_save_share'), { duration: 0.3 } );
			}
		});
		ipb.delegate.register('#share_x_facebook', ipb.facebook.connectNow);
		ipb.facebook.load({$this->settings['fbc_appid']});
	</script>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: tagCloud
//===========================================================================
function tagCloud($tags) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3 class='maintitle'>Tags</h3>
<div class='ipsBox'>
	<div class='ipsBox_container ipsPad'>
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
// Name: tagEntry
//===========================================================================
function tagEntry($tag, $noClass=false, $app='all', $section='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<a <if test="noClass:|:!$noClass">class='ipsTag'</if> href="{parse url="app=core&amp;module=search&amp;do=search&amp;search_tags={parse expression="IPSText::urlencode_furlSafe($tag)"}&amp;search_app=<if test="inSearch:|:isset($this->request['search_app']) AND $this->request['search_app']">{$this->request['search_app']}<else />{$app}</if><if test="hasSearchSection:|:$section">&amp;search_app_filters[<if test="inSearchSub:|:isset($this->request['search_app']) AND $this->request['search_app']">{$this->request['search_app']}<else />{$app}</if>][searchInKey]={$section}</if>" base="public" template="tags" seotitle="false"}" data-tooltip="{parse expression="sprintf( $this->lang->words['find_more_tags'], $tag )"}"><span>{$tag}</span></a>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: tagPrefix
//===========================================================================
function tagPrefix($tag, $app='', $section='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<a href="{parse url="app=core&amp;module=search&amp;do=search&amp;search_tags={parse expression="urlencode($tag)"}&amp;search_app=<if test="inSearch:|:isset($this->request['search_app']) AND $this->request['search_app']">{$this->request['search_app']}<else />{$app}</if><if test="hasSearchSection:|:$section">&amp;search_app_filters[<if test="inSearchSub:|:isset($this->request['search_app']) AND $this->request['search_app']">{$this->request['search_app']}<else />{$app}</if>][searchInKey]={$section}</if>" base="public" template="tags" seotitle="false"}" class='ipsBadge ipsBadge_lightgrey' data-tooltip="{parse expression="sprintf( $this->lang->words['find_more_tags'], $tag )"}">{$tag}</a>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: tagsAsPopUp
//===========================================================================
function tagsAsPopUp($tags) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='ipsBox'>
	<div class='ipsBox_container ipsPad'>
		{$tags['formatted']['parsedWithoutComma']}
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: tagTextEntryBox
//===========================================================================
function tagTextEntryBox($tags, $options, $where) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="tags"}
<input type="text" class="input_text" size="50" value="" name="{$options['fieldId']}" id="{$options['fieldId']}" <if test="isClosedField:|: ! $options['isOpenSystem']">readonly="readonly"</if> />
<if test="canPrefix:|:$options['prefixesEnabled'] && !$this->memberData['gbw_disable_prefixes']">
	&nbsp;&nbsp;<span class="desc lighter"><input type="checkbox" value="1" name="{$options['fieldId']}_prefix" id="{$options['fieldId']}_prefix" <if test="prefixChecked:|:$tags['formatted']['prefix'] OR $this->request[ $options['fieldId'] . '_prefix' ]">checked='checked'</if> /> {$this->lang->words['firsttagprefix']}</span>
</if>
<if test="!$options['minTags'] && $options['maxTags']">
	<br /><span class='desc lighter'>{parse expression="sprintf( $this->lang->words['tags_max_no_min'], $options['maxTags'] )"}</span>
</if>
<if test="$options['minTags'] && !$options['maxTags']">
	<br /><span class='desc lighter'>{parse expression="sprintf( $this->lang->words['tags_min_no_max'], $options['minTags'] )"}</span>
</if>
<if test="$options['minTags'] && $options['maxTags']">
	<br /><span class='desc lighter'>{parse expression="sprintf( $this->lang->words['tags_max_and_min'], $options['minTags'], $options['maxTags'] )"}</span>
</if>
<php>
	$options['lang']['tag_add_link']	= $this->lang->words['add_a_tag'];
	$options['lang']['tip_text']		= $this->lang->words['tag_tip'];
	$options['forceLowercase']			= $this->settings['tags_force_lower'];
	$options['existingTags']			= count($tags['tags']) ? $tags['tags'] : null;
	
	$_tmp = $options;
	
	/* Always return as UTF-8 */
	$jsonEncoded = IPSText::jsonEncodeForTemplate( $_tmp );
</php>
<script type="text/javascript">
	$( "{$options['fieldId']}" ).tagify( {parse expression="$jsonEncoded"} );
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: twitterDone
//===========================================================================
function twitterDone($user) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='ipsForm_center'>
	<br />
	<strong>{$this->lang->words['tweet_ok']}</strong>
	<br />
	<br />
	<p><a href="http://twitter.com/{$user['screen_name']}/status/{$user['status']['id']}" target="_blank">twitter.com/{$user['screen_name']}/status/{$user['status']['id']}</a></p>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: twitterPop
//===========================================================================
function twitterPop($user) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['share_via_twitter']}</h3>
<div class='fixed_inner'>
	<ul class='ipsList_withminiphoto'>
		<li class='clearfix ipsPad_half'>
			<img src='{$user['profile_image_url']}' class='left ipsUserPhoto ipsUserPhoto_small'/>
			<img src="{$this->settings['public_dir']}style_status/twitter.png" class='left' style='margin-top: -2px; margin-left: -12px;'/>
			<div class='list_content' style='margin-left:58px' id='tWrap' >
				<p class='ipsType_small desc'>{$user['screen_name']}</p>
				<textarea class='input_text' rows='3' cols='10' name='tContent' style='width: 97%;' id='tContent'></textarea>
				<div>
					<div class='right'><input type='button' id='tSubmit' class='input_submit' value='{$this->lang->words['twitter_share']}' /></div>
					<div class='ipsType_smaller desc'><span id='cLeft' style='font-weight:bold'>0</span> {$this->lang->words['twitter_chrs_left']}</div>
				</div>
			</div>
		</li>
	</ul>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>