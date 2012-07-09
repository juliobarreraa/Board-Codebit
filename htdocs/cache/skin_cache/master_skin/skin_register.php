<?php
/**
 * Master skin file
 * Written: Mon, 09 Jul 2012 18:16:42 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: completePartialLogin
//===========================================================================
function completePartialLogin($mid="",$key="",$custom_fields="",$errors="", $reg="", $userFromService=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="register"}
<script type='text/javascript'>
//<![CDATA[
	ipb.register.inSection	   = "completeReg";
	ipb.register.nameMaxLength = "{$this->settings['max_user_name_length']}";
	ipb.register.allowedChars  = "{$this->settings['username_characters']}";
	ipb.register.memberPartial = parseInt('{$mid}');
	ipb.templates['accept']    = "&nbsp;<span id='[id]_msg' class='reg_msg reg_accept' style='display: none'><img src='{$this->settings['img_url']}/accept.png' alt='' /> [msg]</span>";
	ipb.templates['error']     = "&nbsp;<span id='[id]_msg' class='reg_msg reg_error' style='display: none'><img src='{$this->settings['img_url']}/exclamation.png' alt='' /> [msg]</span>";
//]]>
</script>
<div id='register_form'>	
	<h1 class='ipsType_pagetitle'><if test="hasAName:|:$this->request['members_display_name'] OR $userFromService['_name']">{parse expression="sprintf($this->lang->words['connect_sub'], $userFromService['_name'] ? IPSText::utf8ToEntities( $userFromService['_name'] ) : $this->request['members_display_name'])"}<else />{$this->lang->words['connect_sub2']}</if></h1>
	<br />
	<if test="count( $userFromService ) AND ! empty( $userFromService['service'] )">
		<input type='hidden' name='connectService' value='{$userFromService['service']}' />
		<div id='facebookComplete' class='clearfix'>
			<img src="{$userFromService['_pic']}" class='ipsUserPhoto ipsUserPhoto_medium left' />
			<img src="{$userFromService['_sImage']}" class='servicepic' />
			<p class='ipsBox_withphoto'>
				{parse expression="sprintf($this->lang->words['connect_sub_desc'], ucfirst($userFromService['service']))"}
				<br />
				{parse expression="sprintf($this->lang->words['connect_desc'], ucfirst($userFromService['service']))"}
			</p>
		</div>
	<else />
		<p class='message'>{$this->lang->words['clogin_text']}</p>
	</if>
	<br />
	<if test="partialLoginErrors:|:!empty( $errors )">
		<p class='message error'>
			<strong>{$this->lang->words['errors_found']}</strong>
			{$errors}
		</p>
		<br />
	</if>
	<div id='connect_choose'>
		<h2 class='ipsType_subtitle'>{parse expression="sprintf( $this->lang->words['already_have_account'], $this->settings['board_name'] )"}</h2>
		<div class='ipsBox' style='margin-top: 5px'>
			<div class='ipsBox_container ipsPad' style='text-align: center'>
				<a href='#connect_new' class='ipsButton' id='choose_new'>{$this->lang->words['need_to_create_acc']}</a>&nbsp;&nbsp;&nbsp;
				<if test="count( $userFromService ) AND ! empty( $userFromService['service'] )">
					<a href='#connect_existing' id='choose_existing'>{$this->lang->words['use_an_existing_acc']}</a>
				</if>
			</div>
		</div>
	</div>
	<div id='connect_new'>
		<br />
		<h2 class='maintitle'>{$this->lang->words['new_account_title']}</h2>
		<div class='ipsBox'>
			<div class='ipsBox_container <if test="count( $userFromService ) AND ! empty( $userFromService['service'] )"> completeLeft</if>'>
				<form action="{parse url="app=core&amp;module=global&amp;section=register&amp;do=complete_login_do&amp;key=$key&amp;mid=$mid&amp;connectService={$userFromService['service']}" base="public"}" method="POST">
					<input type="hidden" name="termsread" value="1" />
					<input type="hidden" name="agree_to_terms" value="1" />
					<input type='hidden' name='from' value='new' />
					<fieldset class='main'>
						<ul class='ipsForm ipsForm_horizontal ipsPad'>
							<if test="partialAllowDnames:|:$this->settings['auth_allow_dnames'] == 1">
								<li class='ipsField clear'>
									<if test="fbDisplayName:|:$userFromService['service'] == 'facebook' AND $this->settings['fb_realname'] != 'any' AND $userFromService['_displayName']">
										<if test="fbDNInner:|:$this->settings['fb_realname'] == 'prefilled'">
											<label for='display_name' class='ipsField_title'>{$this->lang->words['dname_name']}</label>
											<p class='ipsField_content'>
												<input id='display_name' class='input_text'  type="text" size="40" maxlength="64" value="{$userFromService['_displayName']}" name="members_display_name" />
											</p>
										<else />
											<span class='ipsField_title'></span>
											<p class='ipsField_content'><strong>{IPSText::utf8ToEntities( $userFromService['_displayName'] )}</strong></p>
											<input type="hidden" value="{$userFromService['_displayName']}" name="members_display_name" />
										</if>
									<else />
										<label for='display_name' class='ipsField_title'>{$this->lang->words['dname_name']}</label>
										<p class='ipsField_content'>
											<input id='display_name' class='input_text'  type="text" size="40" maxlength="64" value="{$this->request['members_display_name']}" name="members_display_name" />
										</p>
									</if>
								</li>
							</if>
							<if test="partialNoEmail:|:! $reg['partial_email_ok']">
								<li class='ipsField clear'>
									<label for='email_1' class='ipsField_title'>{$this->lang->words['email_address']}</label>
									<p class='ipsField_content'>
										<input id='email_1' class='input_text'  type="text" size="40" maxlength="50" value="{$this->request['EmailAddress']}" name="EmailAddress" />
									</p>
								</li>
								<li class='ipsField clear'>
									<label for='email_2' class='ipsField_title'>{$this->lang->words['email_address_confirm']}</label>
									<p class='ipsField_content'>
										<input id='email_2' class='input_text'  type="text" size="40" maxlength="50"  value="{$this->request['EmailAddress_two']}" name="EmailAddress_two" />
									</p>
								</li>
							</if>
							<if test="partialCustomFields:|:$custom_fields != ''">
								<fieldset class='rcomplete'>
								<if test="reqCfields:|:is_array( $custom_fields['required'] ) && count( $custom_fields['required'] )">
									<foreach loop="custom_required:$custom_fields['required'] as $_field">
										<li class='ipsField clear {$_field['type']}'>
											<label for='cprofile_{$_field['id']}' class='ipsField_title'>{$_field['name']} <span class='ipsForm_required'>*</span></label>
											<div class='ipsField_content'>
												{$_field['field']}
												<if test="reqCfieldDescSpan:|:$_field['desc'] != ''"><span class='desc'>{$_field['desc']}</span></if>
											</div>
										</li>
									</foreach>
								</if>
	
								<if test="optCfields:|:is_array( $custom_fields['optional'] ) && count( $custom_fields['optional'] )">
									<foreach loop="custom_optional:$custom_fields['optional'] as $_field">
										<li class='ipsField clear {$_field['type']}'>
											<label for='cprofile_{$_field['id']}' class='ipsField_title'>{$_field['name']}</label>
											<div class='ipsField_content'>
												{$_field['field']}
												<if test="optCfieldDescSpan:|:$_field['desc'] != ''"><span class='desc'>{$_field['desc']}</span></if>
											</div>
										</li>
									</foreach>
								</if>
								</fieldset>
							</if>
						</ul>
					</fieldset>
					<fieldset class='submit'>
						<input type='submit' value='{$this->lang->words['new_account_submit']}' class='input_submit' />
					</fieldset>
				</form>
			</div>
		</div>
	</div>
	<if test="count( $userFromService ) AND ! empty( $userFromService['service'] )">
		<br />
		<div id='connect_existing'>
			<h2 class='maintitle'>{$this->lang->words['connect_account_title']}</h2>
			<div class='ipsBox'>
				<div class='ipsBox_container'>
					<form action="{parse url="app=core&amp;module=global&amp;section=register&amp;do=complete_login_do&amp;key={$key}&amp;mid={$mid}&amp;connectService={$userFromService['service']}" base="public"}" method="POST">
						<input type="hidden" name="termsread" value="1" />
						<input type="hidden" name="agree_to_terms" value="1" />
						<input type='hidden' name="from" value="existing" />
						<fieldset class='ipsPad_double'>
							<ul class='ipsForm ipsForm_horizontal'>
								<li class='ipsField clear'>
									<label for='login_user' class='ipsField_title'>{$this->lang->words['connect_username']}</label>
									<div class='ipsField_content'>
										<input id='login_user' class='input_text' type="text" size="50" maxlength="50" value="{$this->request['login_user']}" name="login_user" /><br />
										<span class='desc'>{$this->lang->words['connect_username_desc']}</span> 
									</div>
								</li>
								<li class='ipsField clear'>
									<label for='login_pass' class='ipsField_title'>{$this->lang->words['connect_password']}</label>
									<div class='ipsField_content'>
										<input id='login_pass' class='input_text' type="password" size="50" maxlength="50"  value="" name="login_pass" /><br />
										<span class='desc'>{$this->lang->words['connect_password_desc']}</span>
									</div>
								</li>
							</ul>
						</fieldset>
						<fieldset class='submit'>
							<input type='submit' value='{$this->lang->words['connect_account_submit']}' class='input_submit' />
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</if>
</div>
<script type='text/javascript'>
	<if test="$this->request['from']">
		ipb.vars['register_active'] = "{$this->request['from']}";
	</if>
	
	function setUpSignin(){
		if( !$('connect_existing') ){
			$('connect_choose').hide();
			return;
		}
		
		if( !Object.isUndefined( ipb.vars['register_active'] ) ){
			if( ipb.vars['register_active'] == 'new' ){
				$('connect_new').show();
				$('connect_existing').hide();
			} else {
				$('connect_existing').show();
				$('connect_new').hide();
			}
		} else {
			$('connect_new').hide();
			$('connect_existing').hide();
		}
		
		$('choose_new').observe('click', toggleSignIn);
		$('choose_existing').observe('click', toggleSignIn);
	}
	
	function toggleSignIn(e){
		Event.stop(e);
		var toggleTo = Event.findElement(e, 'a');
		if( !toggleTo ){ return; }
		
		if( toggleTo == $('choose_new') ){
			if( !$('connect_new').visible() ){
				$('connect_existing').fade( { duration: 0.3, afterFinish: function(){
					$('connect_new').appear( { duration: 0.2 } );
				} } );
			}
		} else {
			if( !$('connect_existing').visible() ){
				$('connect_new').fade( { duration: 0.3, afterFinish: function(){
					$('connect_existing').appear({ duration: 0.2});
				}});
			}
		}
	}
	
	setUpSignin();
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: lostPasswordForm
//===========================================================================
function lostPasswordForm($errors="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{$this->lang->words['lost_pass_form']}</h1>
<div class='ipsType_pagedesc'>
	{$this->lang->words['lost_your_password']} {$this->lang->words['lp_text']}
</div>
<br />
<if test="lostPasswordErrors:|:!empty( $errors )">
	<p class='message error'>
		<strong>{$this->lang->words['errors_found']}</strong><br />
		{$errors}
	</p>
	<br />
</if>

<form action="{parse url="app=core&module=global&section=lostpass" base="public"}" method="post">
<input type="hidden" name="do" value="11" />
<h2 class='maintitle'>{$this->lang->words['recover_password']}</h2>
<div id='lost_pass_form' class='ipsBox'>
	<div class='ipsBox_container ipsPad_double'>
		<fieldset>
			<ul class='ipsForm ipsForm_horizontal'>
				<li class='ipsField clear'>
					<label for='member_name' class='ipsField_title'>{$this->lang->words['lp_user_name']}</label>
					<p class='ipsField_content'>
						<input type="text" size="32" name="member_name" id='member_name' class='input_text' />
					</p>
				</li>
				<li class='ipsField clear'>
					<label for='email_addy' class='ipsField_title'>{$this->lang->words['lp_email_or']}</label>
					<p class='ipsField_content'>
						<input type="text" size="32" name="email_addy" id='email_addy' class='input_text' />
					</p>
				</li>
			</ul>
		</fieldset>
		<!--{REG.ANTISPAM}-->
	</div>
	<fieldset class='submit'>
			<input class='input_submit' type="submit" value="{$this->lang->words['lp_send']}" /> {$this->lang->words['or']} <a href='{$this->settings['board_url']}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
	</fieldset>
</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: lostPasswordWait
//===========================================================================
function lostPasswordWait($member="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{$this->lang->words['lpf_title']}</h1>
<br />
<p class='message'>
	{$this->lang->words['lpass_text']}
</p>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: registerCoppaForm
//===========================================================================
function registerCoppaForm() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=<% CHARSET %>" />
		<title>{$this->lang->words['cpf_title']}</title>
	</head>
	
	<body>
	<h2>{$this->settings['board_name']}: {$this->lang->words['cpf_title']}</h2>
	<if test="coppaConsentExtra:|:$this->settings['coppa_consent_extra']">
		<div>
			<b>{$this->lang->words['coppa_additional_info']}</b>
			<div>{$this->settings['coppa_consent_extra']}</div>
		</div>
	</if>
	<hr />
	<div>
		<b>{$this->lang->words['cpf_perm_parent']}</b>
		<div style='padding:8px;'>{$this->lang->words['cpf_fax']} <u>{$this->settings['coppa_fax']}</u></div>
		<div style='padding:8px;'>{$this->lang->words['cpf_address']}<br />
			<address>{$this->settings['coppa_address']}</address>
		</div>
	</div>
	<hr />
	<div>	
		<table class='ipbtable' cellspacing="1" cellpadding='8'>
			<tr>
				<td width="40%">{$this->lang->words['user_name']}</td>
				<td style='text-decoration:underline;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="40%">{$this->lang->words['password']}</td>
				<td style='text-decoration:underline;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="40%">{$this->lang->words['email_address']}</td>
				<td style='text-decoration:underline;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
		</table>
	</div>
	<hr />
	<div>
		<b>{$this->lang->words['cpf_sign']}</b>
	</div>
	<br />
	<div>
		<table class='ipbtable' cellspacing="1" cellpadding='8'>
			<tr>
				<td width="40%">{$this->lang->words['cpf_name']}</td>
				<td style='text-decoration:underline;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="40%">{$this->lang->words['cpf_relation']}</td>
				<td style='text-decoration:underline;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="40%">{$this->lang->words['cpf_signature']}</td>
				<td style='text-decoration:underline;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="40%">{$this->lang->words['cpf_email']}</td>
				<td style='text-decoration:underline;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="40%">{$this->lang->words['cpf_phone']}</td>
				<td style='text-decoration:underline;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="40%">{$this->lang->words['cpf_date']}</td>
				<td style='text-decoration:underline;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
		</table>
	</div>
	<hr />
	<div><b>{$this->lang->words['coppa_admin_reminder']}</b>
		<div>{$this->lang->words['coppa_admin_remtext']}</div>
	</div>
	</body>
</html>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: registerCoppaStart
//===========================================================================
function registerCoppaStart() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="useCoppa:|:$this->settings['use_coppa'] && (!$this->request['coppa'] && !IPSCookie::get('coppa'))">
<div class='post_form' id='coppa_form'>
	<fieldset class='general_box'>
		<h3>{$this->lang->words['coppa_info']}</h3>
		<div class='ipsPad'>
			{$this->lang->words['confirm_over_thirteen']}
		</div>
		<div class='ipsPad ipsForm_center'>
			<form>
			<select name='month' id='coppa_bday_m'>
				<foreach loop="coppaMRange:range( 1, 12 ) as $k">
					<option value='{$k}'>{$this->lang->words[ 'M_' . $k ]}</option>
				</foreach>
			</select>
			&nbsp;
			<select name='day' id='coppa_bday_d'>
				<foreach loop="coppaDRange:range( 1, 31 ) as $_day">
					<option value='{$_day}'>{$_day}</option>
				</foreach>
			</select>
			&nbsp;
			<select name='year' id='coppa_bday_y'>
				<foreach loop="coppaYRange:array_reverse( range( date('Y') - 110, date('Y') ) ) as $_year">
					<option value='{$_year}'>{$_year}</option>
				</foreach>
			</select>
			</form>
		</div>
	</fieldset>
	<fieldset class='ipsForm_submit ipsForm_right'>
		<a href='{parse url="app=core&amp;module=global&amp;section=register&amp;do=coppa_two&amp;coppa=1" base="public"}' id='confirm_coppa' class='ipsButton'>{$this->lang->words['coppa_continue_button']}</a>
	</fieldset>
</div>
<script type='text/javascript'>
	$('coppa_form').hide();
	
	function validateDate( popup )
	{
		var day		= parseInt($('coppa_bday_d').value);
		var month	= parseInt($('coppa_bday_m').value);
		var year	= parseInt($('coppa_bday_y').value);
		var compare		= Math.round( new Date( year + 13, month - 1, day ).getTime() / 1000 );
		var today		= Math.round( new Date().getTime() / 1000 );
		if ( (today - compare) < 0 )
		{
			window.location = ipb.vars['base_url'] + "app=core&module=global&section=register&do=coppa_two";
		}
		else
		{
			popup.hide();
			ipb.Cookie.set('coppa', 'no', true);
		}
	}
	
	ipb.vars['coppa_popup'] = new ipb.Popup( 'coppa_popup', {	type: 'pane',
																initial: $('coppa_form').show(),
																hideAtStart: false,
																hideClose: true,
																defer: false,
																modal: true,
																w: '550px' },
															{
																afterInit: function( popup ){
																	popup.getObj().select("#confirm_coppa")[0].observe('click', function(e){
																		Event.stop(e);
																		
																		return validateDate( popup );
																	});
																}
															} );
</script>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: registerCoppaTwo
//===========================================================================
function registerCoppaTwo() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="register"}
<h1 class='ipsType_pagetitle'>{$this->lang->words['cp2_title']}</h1>
<div class='ipsBox'>
	<div class='ipsBox_container ipsPad_double'>
		<h2 class='clear ipsType_subtitle'>{$this->lang->words['coppa_form']}</h2>
		<p>
			{$this->lang->words['cp2_text']}<br /><br />
			<strong><a href='{parse url="app=core&amp;module=global&amp;section=register&amp;do=12" base="public"}' title='{$this->lang->words['coppa_form']}'>{$this->lang->words['coppa_clickhere']}</a></strong>
		</p>
		<p>
			<br />
			{$this->lang->words['coppa_form_text']}
		</p>
		<p>
			<br />
			<a href='{parse url="app=core&amp;module=global&amp;section=register&amp;do=coppa_one" base="public"}' title='{$this->lang->words['cancel']}' class='cancel'>&lt; {$this->lang->words['cancel']}</a>
		</p>
	</div>	
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: registerForm
//===========================================================================
function registerForm($general_errors=array(), $data=array(), $inline_errors=array(), $time_select=array(), $custom_fields=array(), $nexusFields=array(), $nexusStates=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="register"}
<script type='text/javascript'>
//<![CDATA[
	ipb.register.inSection = "mainform";
	ipb.register.nameMaxLength = "{$this->settings['max_user_name_length']}";
	ipb.register.allowedChars = "{$this->settings['username_characters']}";
	ipb.templates['accept'] = "&nbsp;<span id='[id]_msg' class='reg_msg reg_accept' style='display: none'><img src='{$this->settings['img_url']}/accept.png' alt='' /> [msg]</span>";
	ipb.templates['error'] = "&nbsp;<span id='[id]_msg' class='reg_msg reg_error' style='display: none'><img src='{$this->settings['img_url']}/exclamation.png' alt='' /> [msg]</span>";
//]]>
</script>
<div id='register_form'>
	<!--<h1 class='ipsType_pagetitle'>{$this->lang->words['ready_register']}</h1>-->
	{parse template="registerStepBar" group="register" params="array('register_form' => 'ipsSteps_active', 'confirmation' => '')"}
	<if test="registerHasErrors:|:is_array( $general_errors ) && count( $general_errors )">
	<div class='message error'>
		{$this->lang->words['following_errors']}
		<ul>
			<foreach loop="general_errors:$general_errors as $r">
				<li>{$r}</li>
			</foreach>
		</ul>
	</div>
	<br />
	</if>
	<form action="{parse url="app=core&amp;module=global&amp;section=register" base="public"}" method="post" name="REG" id='register'>
		<input type="hidden" name="termsread" value="1" />
		<input type="hidden" name="agree_to_terms" value="1" />
		<input type="hidden" name="do" value="process_form" />
		<input type="hidden" name="coppa_user" value="{$data['coppa_user']}" />
		<input type='hidden' name='nexus_pass' value='1' />
		<input type='hidden' name='time_offset' id='auto_time_offset' value='0' />
		<input type='hidden' name='dst' id='auto_dst' value='0' />
		
		<h1 class='maintitle'>
			{$this->lang->words['ready_register']}
		</h1>
		<div class='ipsBox'>
		
			<div class='ipsBox_container ipsPad'>
			
				<if test="registerServices:|:IPSLib::loginMethod_enabled('facebook') || IPSLib::loginMethod_enabled('twitter')">
					<div class='ipsBox_container ipsBox_notice ipsForm ipsForm_horizontal' id='external_services'>
						<strong class='ipsField_title' id='save_time'>{$this->lang->words['want_to_save_time']}</strong>
						<div class='ipsField_content'>
							<ul class='ipsList_inline'>
								<if test="registerUsingFb:|:IPSLib::loginMethod_enabled('facebook')">
									<li><a href="{parse url="app=core&amp;module=global&amp;section=login&amp;serviceClick=facebook" base="public"}"><img src="{$this->settings['img_url']}/facebook_login.png" alt="" /></a></li>
								</if>
								<if test="twitterBox:|:IPSLib::loginMethod_enabled('twitter')">
									<li><a href="{parse url="app=core&amp;module=global&amp;section=login&amp;serviceClick=twitter" base="public"}"><img src="{$this->settings['img_url']}/twitter_login.png" alt="" /></a></li>
								</if>
							</ul>
						</div>
					</div>
				</if>
			
				<if test="registerHasInlineErrors:|:is_array( $inline_errors ) && ( $inline_errors['username'] || $inline_errors['dname'] || $inline_errors['email'] || $inline_errors['password'] )">
					<p class='message error'>{$this->lang->words['reg_errors_found']}</p>
					<br />
				</if>
				<fieldset>
					<ul class='ipsForm ipsForm_horizontal'>
						<li class='ipsField'>
							<p class='ipsField_content'>
								<span class='ipsForm_required ipsType_smaller'>* {$this->lang->words['required_field']}</span>
							</p>
						</li>
						<li class='ipsField clear <if test="ieDnameClass:|:$inline_errors['dname']">error</if>'>
							<label for='display_name' class='ipsField_title'>{$this->lang->words['reg_choose_dname']} <span class='ipsForm_required'>*</span></label>
							<p class='ipsField_content'>
								<input type='text' class='input_text' id='display_name' size='45' maxlength='{$this->settings['max_user_name_length']}' value='{$this->request['members_display_name']}' name='members_display_name' /><br />
								<span class='desc primary lighter'>
									<if test="ieDname:|:$inline_errors['dname']"><span class='error'>{$inline_errors['dname']}<br /></span></if>
									{parse expression="sprintf( $this->lang->words['dname_desc'], $this->settings['max_user_name_length'])"}
								</span>
							</p>
						</li>
						<li class='ipsField clear <if test="ieEmailClass:|:$inline_errors['email']">error</if>'>
							<label for='email_1' class='ipsField_title'>{$this->lang->words['reg_enter_email']} <span class='ipsForm_required'>*</span></label>
							<p class='ipsField_content'>
								<input type='text' id='email_1' class='input_text email' size='45' maxlength='150' name='EmailAddress' value='{$this->request['EmailAddress']}' /><br />
								<if test="ieEmail:|:$inline_errors['email']"><span class='desc'><span class='error'>{$inline_errors['email']}</span></span></if>
							</p>
						</li>
						<li class='ipsField clear <if test="iePasswordClass:|:$inline_errors['password']">error</if>'>
							<label for='password_1' class='ipsField_title'>{$this->lang->words['reg_choose_password']} <span class='ipsForm_required'>*</span></label>
							<p class='ipsField_content'>
								<input type='password' id='password_1' class='input_text password' size='45' maxlength='32' value='{$this->request['PassWord']}' name='PassWord' /><br />
								<span class='desc lighter'><if test="iePassword:|:$inline_errors['password']"><span class='error'>{$inline_errors['password']}<br /></span></if>{$this->lang->words['reg_choose_password_desc']}</span>
							</p>
						</li>
						<li class='ipsField clear'>
							<label for='password_2' class='ipsField_title'>{$this->lang->words['reg_reenter_password']} <span class='ipsForm_required'>*</span></label>
							<p class='ipsField_content'>
								<input type='password' id='password_2' class='input_text password' size='45' maxlength='32' value='{$this->request['PassWord_Check']}' name='PassWord_Check' /><br />
							</p>
						</li>
					</ul>
				</fieldset>
				<if test="hasNexusFields:|:!empty( $nexusFields )">
					<script type='text/javascript'>
						var _countriesWithStates = [];
						<foreach loop="statesJs:$nexusStates as $k => $v">
							_countriesWithStates["{$k}"] = 1;
						</foreach>
					</script>
					<hr />
					<fieldset>
						<ul class='ipsForm ipsForm_horizontal'>
							<foreach loop="fields:$nexusFields as $f">
								<if test="isAddressOrPhone:|:in_array( $f['f_column'], array( 'cm_address_1', 'cm_phone' ) )">
									<br />
								</if>
								<if test="isText:|:$f['f_type'] == 'text'">
									<li class='ipsField clear'>
										<label for='{$f['f_column']}' class='ipsField_title'><if test="isAddress1:|:$f['f_column'] == 'cm_address_1'">{$this->lang->words['cm_address']}<else /><if test="isAddress2:|:$f['f_column'] == 'cm_address_2'">&nbsp;<else />{$f['f_name']}</if></if> <if test="textRequired:|:$f['f_reg_require']"><span class='ipsForm_required'>*</span></if></label>
										<p class='ipsField_content'>
											<input type='text' class='input_text' id='{$f['f_column']}' size='25' maxlength='255' value='{$this->request[ $f['f_column'] ]}' name='{$f['f_column']}' />
										</p>
										<if test="textErrorMessage:|:$f['f_reg_require'] and $this->request['do'] == 'process_form' and !$this->request[ $f['f_column'] ]">
											<span class='error'>{$this->lang->words['err_complete_form']}</span>
										</if>
									</li>
								</if>
								<if test="isDropdown:|:$f['f_type'] == 'dropdown'">
									<li class='ipsField clear'>
										<label for='{$f['f_column']}' class='ipsField_title'>{$f['f_name']} <if test="dropdownRequired:|:$f['f_reg_require']"><span class='ipsForm_required'>*</span></if></label>
										<div class='ipsField_content'>
											<select name='{$f['f_column']}' id='{$f['f_column']}' <if test="isCountry:|:$f['f_column'] == 'cm_country'">onchange='states()'</if>>
												<foreach loop="options:explode( "\n", $f['f_extra'] ) as $k => $v">
													{parse variable="selected" default="" oncondition="$k == $this->request[ $f['f_column'] ] or $v == $this->request[ $f['f_column'] ]" value=" selected='selected'"}
													<option value='<if test="isCountrySelect:|:$f['f_column'] == 'cm_country'">{$v}<else />{$k}</if>'{parse variable="selected"}><if test="isCountryWords:|:$f['f_column'] == 'cm_country'">{$this->lang->words['nc_'.$v]}<else />{$v}</if></option>
												</foreach>
											</select>
										</div>
										<if test="dropdownErrorMessage:|:$f['f_reg_require'] and $this->request['do'] == 'process_form' and !$this->request[ $f['f_column'] ]">
											<span class='error'>{$this->lang->words['err_complete_form']}</span>
										</if>
									</li>
								</if>
								<if test="isSpecial:|:$f['f_type'] == 'special'">
									<li class='ipsField clear'>
										<label for='cm_state' class='ipsField_title'>{$this->lang->words['cm_state']} <if test="specialRequired:|:$f['f_reg_require']"><span class='ipsForm_required'>*</span></if></label>
										<div class='ipsField_content'>
											<input type='text' class='input_text' id='text-states' size='25' name='cm_state' value='{$this->request['cm_state']}' />
											<foreach loop="statesCountries:$nexusStates as $country => $_states">
												<select name='_cm_state' id='{$country}-states' class='input_select' style='display:none'>
													<foreach loop="states:$_states as $s">
														{parse variable="selected" default="" oncondition="$s[0] == $this->request['cm_state']" value=" selected='selected'"}
														<option value='{$s[0]}'{parse variable="selected"}>{$s[1]}</option>
													</foreach>
												</select>
											</foreach>
										</div>
										<if test="specialErrorMessage:|:$f['f_reg_require'] and $this->request['do'] == 'process_form' and !$this->request[ $f['f_column'] ]">
											<span class='error'>{$this->lang->words['err_complete_form']}</span>
										</if>
									</li>
								</if>
							</foreach>
						</ul>
					</fieldset>
					<script type='text/javascript'>
						function states()
						{
							var c = $('cm_country').value;
							if ( c in _countriesWithStates )
							{
								$( _display ).style.display = 'none';
								$( _display ).name = '_cm_state';
								
								$( c + '-states' ).style.display = '';
								$( c + '-states' ).name = 'cm_state';
								
								_display = c + '-states';
							}
							else
							{
								$( _display ).style.display = 'none';
								$( _display ).name = '_cm_state';
								
								$( 'text-states' ).style.display = '';
								$( 'text-states' ).name = 'cm_state';
								
								_display = 'text-states';
							}
						}
						
						var _display = 'text-states';
						states();
					</script>
				</if>				
				<if test="hasCfields:|:( is_array( $custom_fields['required'] ) && count( $custom_fields['required'] ) ) || ( is_array( $custom_fields['optional'] ) && count( $custom_fields['optional'] ) )">
				<hr />
					<fieldset>
						<ul class='ipsForm ipsForm_horizontal'>
						<if test="reqCfields:|:is_array( $custom_fields['required'] ) && count( $custom_fields['required'] )">
							<foreach loop="custom_required:$custom_fields['required'] as $_field">
								<li class='ipsField clear ipsField_{$_field['type']}'>
									<label for='cprofile_{$_field['id']}' class='ipsField_title'>{$_field['name']} <span class='ipsForm_required'>*</span></label>
									<div class='ipsField_content'>
										{$_field['field']}
										<if test="reqCfieldDescSpan:|:$_field['desc'] != ''"><br /><span class='desc lighter'>{$_field['desc']}</span></if>
									</div>
								</li>
							</foreach>
						</if>
			
						<if test="optCfields:|:is_array( $custom_fields['optional'] ) && count( $custom_fields['optional'] )">
							<foreach loop="custom_optional:$custom_fields['optional'] as $_field">
								<li class='ipsField clear ipsField_{$_field['type']}'>
									<label for='cprofile_{$_field['id']}' class='ipsField_title'>{$_field['name']}</label>
									<div class='ipsField_content'>
										{$_field['field']}
										<if test="optCfieldDescSpan:|:$_field['desc'] != ''"><br /><span class='desc lighter'>{$_field['desc']}</span></if>
									</div>
								</li>
							</foreach>
						</if>
					</fieldset>
				</if>
				<hr />
				{$data['qandaHTML']}
				{$data['captchaHTML']}
				<hr />
				<fieldset>
					<ul class='ipsForm ipsForm_horizontal'>
						<li class='ipsField clear ipsField_checkbox'>
							<input type="checkbox" name="allow_admin_mail" id="allow_admin_mail" value="1" class="input_check" <if test="defaultAAE:|:$this->request['allow_admin_mail'] || !isset( $this->request['allow_admin_mail'] )">checked='checked'</if> />
							<p class='ipsField_content'>
								<label for='allow_admin_mail'>{$this->lang->words['receive_admin_emails']}</label>
							</p>
						</li>
						<li class='ipsField clear ipsField_checkbox'>
							<input type='checkbox' name='agree_tos' id='agree_tos' value='1' class='input_check' <if test="checkedTOS:|:$this->request['agree_tos']">checked="checked"</if> />
							<p class='ipsField_content'>
								<label for='agree_tos' <if test="ieDnameClass:|:$inline_errors['dname']">error</if>>
									<strong>{$this->lang->words['agree_to_tos']} <a href='#' id='tou_link'>{$this->lang->words['terms_of_use']}</a></strong>
									<if test="ieTOS:|:$inline_errors['tos']"><br /><span class='error'>{$inline_errors['tos']}</span></if>
								</label>	
								<textarea id='tou' class='input_text' style='width: 350px; height: 100px; display: block;'>
									{$this->settings['_termsAndConditions']}
								</textarea>
							</p>
						</li>
						<if test="privvy:|:$this->settings['priv_title']">
						<li class='ipsPad_top ipsForm_center desc ipsType_smaller'>
							<a rel="nofollow" href='{parse url="app=core&amp;module=global&amp;section=privacy" template="privacy" seotitle="false" base="public"}'>{$this->settings['priv_title']}</a>
						</li>
						</if>
					</ul>
					<script type='text/javascript'>
						$('tou').hide();
					</script>
				</fieldset>
				<br />
				<fieldset>
					<input type='submit' class='ipsButton' id='register_submit' value='{$this->lang->words['register']}' />
				</fieldset>
			</div>
		</div>
	</form>
	<script type='text/javascript'>
		ipb.templates['registration_terms'] = new Template("<h3>{$this->lang->words['reg_terms_popup_title']}</h3><div class='ipsPad' id='tou_popup'>#{content}</div>");
	</script>
	{parse template="registerCoppaStart" group="register" params=""}
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: registerStepBar
//===========================================================================
function registerStepBar($step) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>
	$this->templateVars['step_count'] = 1;
</php>
<div class='ipsSteps clearfix'>
	<ul>
		<if test="IPSLib::appIsInstalled('nexus') AND $this->settings['nexus_reg_show'] AND $this->settings['nexus_store_online']">
			<li class='<if test="$this->request['nexus_pass']">ipsSteps_done<else />ipsSteps_active</if>'>
				<strong class='ipsSteps_title'>{parse expression="sprintf($this->lang->words['regstep'], $this->templateVars['step_count'])"}</strong>
				<span class='ipsSteps_desc'>{$this->lang->words['regstep_choose_package']}</span>
				<span class='ipsSteps_arrow'>&nbsp;</span>
			</li>
			<!--Don't delete: {parse expression="$this->templateVars['step_count']++"}-->
		</if>
		<li class='{$step['register_form']}'>
			<strong class='ipsSteps_title'>{parse expression="sprintf($this->lang->words['regstep'], $this->templateVars['step_count'])"}</strong>
			<span class='ipsSteps_desc'>{$this->lang->words['regstep_your_account']}</span>
			<span class='ipsSteps_arrow'>&nbsp;</span>
			<!--Don't delete: {parse expression="$this->templateVars['step_count']++"}-->
		</li>
		<li class='{$step['confirmation']}'>
			<strong class='ipsSteps_title'>{parse expression="sprintf($this->lang->words['regstep'], $this->templateVars['step_count'])"}</strong>
			<span class='ipsSteps_desc'>{$this->lang->words['regstep_confirm']}</span>
			<span class='ipsSteps_arrow'>&nbsp;</span>
		</li>
	</ul>
</div>
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: show_lostpass_form_auto
//===========================================================================
function show_lostpass_form_auto($aid="",$uid="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<input type="hidden" name="uid" value="{$uid}" />
<input type="hidden" name="aid" value="{$aid}" />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: show_lostpass_form_manual
//===========================================================================
function show_lostpass_form_manual() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3 class='bar'>{$this->lang->words['lpf_title']}</h3>
	<ul>
		<li class='field'>
			<label for='user_id'>{$this->lang->words['user_id']}</label>
			<input type="text" size="32" maxlength="32" name="uid" id='user_id' class='input_text' />
		</li>
		<li class='field'>
			<label for='aid'>{$this->lang->words['val_key']}</label>
			<input type="text" size="32" maxlength="50" name="aid" id='aid' class='input_text' />
		</li>
	</ul>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showAuthorize
//===========================================================================
function showAuthorize($member="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div id='register_form'>
	{parse template="registerStepBar" group="register" params="array('register_form' => '', 'confirmation' => 'ipsSteps_active')"}
	<h1 class='maintitle'>{$this->lang->words['ready_register']}</h1>
	<div class='ipsBox'>
		<div class='ipsBox_container ipsPad'>
			{parse expression="sprintf( $this->lang->words['auth_text'], $member['members_display_name'], $member['email'] )"}
		</div>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showLostpassForm
//===========================================================================
function showLostpassForm($error) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{$this->lang->words['dumb_header']}</h1>
<div class='ipsType_pageinfo'>
	{$this->lang->words['dumb_text']}
</div>
<if test="lostpassFormErrors:|:!empty( $error )">
	<p class='message error'>
		{$error}
	</p>
</if>
<form action="{parse url="app=core&module=global&section=lostpass" base="public"}" method="post">
<input type="hidden" name="do" value="03" />
<input type="hidden" name="type" value="lostpass" />
<div class='generic_bar'></div>
	<div class='post_form'>
		<!--IBF.INPUT_TYPE-->
		<h3 class='bar'>{$this->lang->words['new_password']}</h3>
		<if test="lpFormMethodChoose:|:$this->settings['lp_method'] == 'choose'">
			<ul>
				<li class='field'>
					<label for="pass1">{$this->lang->words['lpf_pass1']}</label>
					<input type="password" size="32" maxlength="32" name="pass1" id='pass1' class='input_text' />
					<br /><span class='desc'>{$this->lang->words['lpf_pass11']}</span>
				</li>
				<li class='field'>
					<label for="pass2">{$this->lang->words['lpf_pass2']}</label>
					<input type="password" size="32" maxlength="32" name="pass2" id='pass2' class='input_text' />
					<br /><span class='desc'>{$this->lang->words['lpf_pass22']}</span>
				</li>
			</ul>
		<else />
			<p class='field'>
		 		{$this->lang->words['lp_random_pass']}
			</p>
		</if>
		<!--{REG.ANTISPAM}-->
		<fieldset class='submit'>
			<input class='input_submit' type="submit" value="{$this->lang->words['dumb_submit']}" />
		</fieldset>
	</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showLostPassWaitRandom
//===========================================================================
function showLostPassWaitRandom($member="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{$this->lang->words['lpf_title']}</h1>
<br />
<p class='message'>
	{$this->lang->words['lpass_text_random']}
</p>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showManualForm
//===========================================================================
function showManualForm($type="reg") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{$this->lang->words['dumb_header']}</h1>
<br />
<p class='message unspecific'>
	{$this->lang->words['validate_instructions_1']}<br />
	{parse expression="sprintf( $this->lang->words['validate_instructions_2'], "<a href='{parse url="app=core&module=global&section=register&do=reval" base="public"}'>{$this->lang->words['validate_instructions_click']}</a>" )"}
</p>
<br />
<form action="{parse url="" base="public"}" method="post" name="REG">
	<input type="hidden" name="app" value="core" />
	<input type="hidden" name="module" value="global" />
	<input type="hidden" name="section" value="register" />
	<input type="hidden" name="do" value="auto_validate" />
	<input type="hidden" name="type" value="{$type}" />
	<div class='ipsBox'>
		<div class='ipsBox_container ipsPad'>
			<ul class='ipsForm ipsForm_horizontal'>
				<li class='ipsField'>
					<label class='ipsField_title'>{$this->lang->words['user_id']} <span class='ipsForm_required'>*</span></label>
					<div class='ipsField_content'>
						<input type="text" size="32" maxlength="32" name="uid" id='userid' class='input_text' />
					</div>
				</li>
				<li class='ipsField'>
					<label class='ipsField_title'>{$this->lang->words['val_key']} <span class='ipsForm_required'>*</span></label>
					<div class='ipsField_content'>
						<input type="text" size="32" maxlength="50" name="aid" id='valkey' class='input_text' />
					</div>
				</li>
			</ul>
		</div>
	</div>
	<fieldset class='submit'>
		<input class='input_submit' type="submit" value="{$this->lang->words['dumb_submit']}" />
	</fieldset>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showPreview
//===========================================================================
function showPreview($member="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{$this->lang->words['registration_process']}</h1>
<br />
<p class='message'>
	{$this->lang->words['thank_you']} {$member['members_display_name']}. {$this->lang->words['preview_reg_text']}
</p>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showRevalidated
//===========================================================================
function showRevalidated() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{$this->lang->words['rv_title']}</h1>
<br />
<p class='message'>
	{$this->lang->words['rv_process']}<br />
	{$this->lang->words['rv_done']}
</p>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: showRevalidateForm
//===========================================================================
function showRevalidateForm($name="",$error="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{$this->lang->words['rv_title']}</h1>
<br />
<if test="revalidateError:|:$error">
<p class='message error'>{$this->lang->words[$error]}</p>
<br />
</if>
<form action="{parse url="app=core&amp;module=global&amp;section=register" base="public"}" method="post" name="REG">
<input type="hidden" name="do" value="reval2" />
	<p class='message unspecific'>
		{$this->lang->words['rv_ins']}
	</p>
	
	<div class='post_form'>
		<fieldset class='general_box'>
			<h3>{$this->lang->words['rv_bar_title']}</h3>
			<label for='username'>{$this->lang->words['rv_name']}</label>
			<input type="text" class='input_text' id='username' size="32" maxlength="64" name="username" value="$name" />
		</fieldset>
		<fieldset class='submit'>
			<input class='input_submit' type="submit" value="{$this->lang->words['rv_go']}" /> {$this->lang->words['or']} <a href='{$this->settings['board_url']}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
		</fieldset>
	</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>