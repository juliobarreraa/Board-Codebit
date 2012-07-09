<?php
/**
 * Master skin file
 * Written: Mon, 09 Jul 2012 18:16:42 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: ajax__inlineLogInForm
//===========================================================================
function ajax__inlineLogInForm() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>
	$uses_name		= false;
	$uses_email		= false;
	$_redirect		= '';
	
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
<if test="hasRedirect:|:$_redirect">
<script type='text/javascript'>
window.location = '{$_redirect}';
</script>
<else />
<div id='inline_login_form'>
	<form action="{parse url="app=core&amp;module=global&amp;section=login&amp;do=process" base="public"}" method="post" id='login'>
		<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
		<input type="hidden" name="referer" value="{parse expression="str_replace( array( '<', '>', '(', ')' ), '-', my_getenv('HTTP_REFERER') )"}" />
		<h3>{$this->lang->words['log_in']}</h3>
		<if test="registerServices:|:IPSLib::loginMethod_enabled('facebook') || IPSLib::loginMethod_enabled('twitter') || IPSLib::loginMethod_enabled('live')">
			<div class='ipsBox_notice'>
				<ul class='ipsList_inline'>
					<if test="registerUsingFb:|:IPSLib::loginMethod_enabled('facebook')">
						<li><a href="{parse url="app=core&amp;module=global&amp;section=login&amp;serviceClick=facebook" base="public"}"><img src="{$this->settings['img_url']}/facebook_login.png" alt="" /></a></li>
					</if>
					<if test="twitterBox:|:IPSLib::loginMethod_enabled('twitter')">
						<li><a href="{parse url="app=core&amp;module=global&amp;section=login&amp;serviceClick=twitter" base="public"}"><img src="{$this->settings['img_url']}/twitter_login.png" alt="" /></a></li>
					</if>
					<if test="haswindowslive:|:IPSLib::loginMethod_enabled('live')">
						<li><a href='{parse url="app=core&amp;module=global&amp;section=login&amp;do=process&amp;use_live=1&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['use_live']}'>{parse replacement="live_small"} {$this->lang->words['sign_in_winlive']}</a></li>
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
				</ul>
			</fieldset>
			<div class='ipsForm_submit ipsForm_center'>
				<input type='submit' class='ipsButton' value='{$this->lang->words['log_in']}' />
			</div>
		</div>
	</form>
</div>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: errors
//===========================================================================
function errors($data="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<p class='message error'>
	{$data}
</p>
<br /><br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showLogInForm
//===========================================================================
function showLogInForm($message="",$referer="",$extra_form="", $login_methods=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="signin"}
<div id='login_form' class='clearfix'>
	<div id='member_login'>
		<h2 class='maintitle'>{$this->lang->words['log_in']}</h2>
		<form action="{parse url="app=core&amp;module=global&amp;section=login&amp;do=process" base="public"}" method="post" id='login'>
			<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
			<if test="referer:|:$referer"><input type="hidden" name="referer" value="{$referer}" /></if>
			<div id='regular_signin'>
				<a id='_regularsignin'></a>
				<h3 class='bar'>{$this->lang->words['enter_name_and_pass']}</h3>
				<ul class='ipsForm ipsForm_vertical ipsPad_double left'>
					<li class='ipsField'>
						<label for='ips_username' class='ipsField_title'>{$this->lang->words['enter_name']}</label>
						<p class='ipsField_content'>
							<input id='ips_username' type='text' class='input_text' name='ips_username' size='50' tabindex='1' /><br />
							<span class='desc ipsType_smaller'>{$this->lang->words['register_prompt_1']} <a href='{parse url="app=core&amp;module=global&amp;section=register" base="public"}' title='{$this->lang->words['register_prompt_2']}'>{$this->lang->words['register_prompt_2']}</a></span>
						</p>
					</li>
					<li class='ipsField'>
						<label for='ips_password' class='ipsField_title'>{$this->lang->words['enter_pass']}</label>
						<p class='ipsField_content'>
							<input id='ips_password' type='password' class='input_text' name='ips_password' size='50' tabindex='2' /><br />
							<a href='{parse url="app=core&amp;module=global&amp;section=lostpass" base="public"}' class='ipsType_smaller' title='{$this->lang->words['retrieve_pw']}'>{$this->lang->words['login_forgotten_pass']}</a>
						</p>
					</li>
				</ul>
				<div class='right ipsPad_double' id='other_signin'>
					<ul class='ipsList_data clear ipsType_small'>
						<if test="facebook:|:IPSLib::loginMethod_enabled('facebook')">
							<li><a href="{parse url="app=core&amp;module=global&amp;section=login&amp;serviceClick=facebook" base="public"}" class='ipsButton_secondary fixed_width'><img src="{$this->settings['img_url']}/loginmethods/facebook.png" alt="Facebook" /> &nbsp; {$this->lang->words['have_facebook']}</a></li>
						</if>
						<if test="twitterBox:|:IPSLib::loginMethod_enabled('twitter')">
							<li><a href="{parse url="app=core&amp;module=global&amp;section=login&amp;serviceClick=twitter" base="public"}" class='ipsButton_secondary fixed_width'><img src="{$this->settings['img_url']}/loginmethods/twitter.png" alt="Twitter" /> &nbsp; {$this->lang->words['have_twitter']}</a></li>
						</if>
						<if test="haswindowslive:|:IPSLib::loginMethod_enabled('live')">
							<li><a href='{parse url="app=core&amp;module=global&amp;section=login&amp;do=process&amp;use_live=1&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['use_live']}' class='ipsButton_secondary fixed_width'><img src="{$this->settings['img_url']}/loginmethods/windows.png" alt="Windows Live" /> &nbsp; {$this->lang->words['sign_in_winlive']}</a></li>
						</if>
						<if test="extraform:|:is_array($extra_form) AND count($extra_form)">
							<foreach loop="extrafields:$extra_form as $form_fields">
								{$form_fields}
							</foreach>
						</if>
					</ul>
				</div>
			</div>
			<if test="liveform:|:IPSLib::loginMethod_enabled('live')">
				<div id='live_signin'>
					<a id='_live'></a>
					<h3 class='bar'>{$this->lang->words['sign_in_winlive']}</h3>
					<div class='ipsPad_double'>
						<br />
						<a href='{parse url="app=core&amp;module=global&amp;section=login&amp;do=process&amp;use_live=1&amp;auth_key={$this->member->form_hash}" base="public"}' class='ipsButton'>{parse replacement="live_large"} &nbsp;&nbsp;{$this->lang->words['signin_with_live']}</a>
					</div>
					<p class='extra'><a href='#_regularsignin' title='{$this->lang->words['regular_signin']}' id='live_close'>{$this->lang->words['use_regular']}</a></p>
				</div>
			</if>
			<hr />
			<fieldset id='signin_options'>
				<legend>{$this->lang->words['sign_in_options']}</legend>
				<ul class='ipsForm ipsForm_vertical ipsPad_double'>
					<li class='ipsField ipsField_checkbox clearfix'>
						<input type='checkbox' id='remember' checked='checked' name='rememberMe' value='1' class='input_check' />
						<p class='ipsField_content'>
							<label for='remember'>{$this->lang->words['rememberme']}</label><br />
							<span class='desc lighter'>{$this->lang->words['notrecommended']}</span>
						</p>
					</li>
					<if test="anonymous:|:!$this->settings['disable_anonymous']">
						<li class='ipsField ipsField_checkbox clearfix'>
							<input type='checkbox' id='invisible' name='anonymous' value='1' class='input_check' />
							<p class='ipsField_content'>
								<label for='invisible'>{$this->lang->words['form_invisible']}</label><br />
								<span class='desc lighter'>{$this->lang->words['anon_name']}</span>
							</p>
						</li>
					</if>
					<if test="privvy:|:$this->settings['priv_title']">
					<li class='ipsPad_top ipsForm_center desc ipsType_smaller'>
						<a rel="nofollow" href='{parse url="app=core&amp;module=global&amp;section=privacy" template="privacy" seotitle="false" base="public"}'>{$this->settings['priv_title']}</a>
					</li>
					</if>
				</ul>
			</fieldset>
			<fieldset class='submit'>
				<input type='submit' class='input_submit' value='{$this->lang->words['sign_in_button']}' tabindex='3' /> {$this->lang->words['or']} <a href='{$this->settings['board_url']}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
			</fieldset>
		</form>
	</div>
</div>
<if test="toggleLive:|:$this->request['serviceClick'] == 'live'">
<script type='text/javascript'>
document.observe("dom:loaded", function(e){ ipb.signin.toggleLive(e); });
</script>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>