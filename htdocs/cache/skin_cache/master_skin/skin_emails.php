<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: boardRules
//===========================================================================
function boardRules($title="",$body="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{$title}</h1>
<div class='row2 ipsPad rules'>
	{$body}
</div>
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: forward_form
//===========================================================================
function forward_form($title="",$text="",$lang="", $captchaHTML='', $msg='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form action="{parse url="app=forums&amp;module=extras&amp;section=forward" base="public"}" method="post" name='REPLIER'>
	<input type="hidden" name="do" value="01" />
	<input type="hidden" name="st" value="{$this->request['st']}" />
	<input type="hidden" name="f" value="{$this->request['f']}" />
	<input type="hidden" name="t" value="{$this->request['t']}" />
	<input type="hidden" name="url" value="{$this->request['url']}" />
	<input type="hidden" name="title" value="{$this->request['title']}" />
	<input type='hidden' name='k' value='{$this->member->form_hash}' />
	<if test="hasError:|:$msg">
		<p class='message error'>{$this->lang->words[ $msg ]}</p><br />
	<else />
		<p class='message'>{$this->lang->words['email_friend']}</p><br />
	</if>
	
	<h2 class='maintitle'>{$this->lang->words['title']}</h2>
	<div class='generic_bar'></div>
	<div class='ipsForm ipsForm_horizontal'>
		<fieldset>
			<h3 class='bar'>{$this->lang->words['email_recepient']}</h3>
			<ul class='ipsPad'>
				<if test="count($this->caches['lang_data']) == 1">
					<input type='hidden' name='lang' value='{$this->caches['lang_data'][0]['lang_id']}' />
				<else />
					<li class='ipsField clear'>
						<label for='to_lang' class='ipsField_title'>{$this->lang->words['send_lang']}</label>
						<p class='ipsField_content'>
							<select name='lang' class='input_select' id='to_lang'>
								<foreach loop="lang:$this->caches['lang_data'] as $l">
									<option value='{$l['lang_id']}' <if test="language:|:$l['lant_id'] == $this->memberData['language']">selected='selected'</if>>{$l['lang_title']}</option>
								</foreach>
							</select>
						</p>
					</li>
				</if>
				<li class='ipsField clear'>
					<label for='to_name' class='ipsField_title'>{$this->lang->words['to_name']}</label>
					<p class='ipsField_content'>
						<input type="text" id='to_name' class='input_text' name="to_name" value="{$this->request['to_name']}" size="30" maxlength="100" />
					</p>
				</li>
				<li class='ipsField clear'>
					<label for='to_email' class='ipsField_title'>{$this->lang->words['to_email']}</label>
					<p class='ipsField_content'>
						<input type="text" id='to_email' class='input_text' name="to_email" value="{$this->request['to_email']}" size="30" maxlength="100" />
					</p>
				</li>
				<li class='ipsField clear'> 
					<label for='subject' class='ipsField_title'>{$this->lang->words['subject']}</label>
					<p class='ipsField_content'>
						<input type="text" id="subject" class="input_text" name="subject" value="<if test="hasSubject:|:$this->request['subject']">{$this->request['subject']}<else />$title</if>" size="30" maxlength="120" />
					</p>
				</li> 				
				<li class='ipsField clear'>
					<label for='to_message' class='ipsField_title'>{$this->lang->words['message']}</label>
					<p class='ipsField_content'>
						<textarea id='to_message' cols="60" rows="12" wrap="soft" name="message" class="input_text"><if test="hasText:|:$this->request['message']">{$this->request['message']}<else />$text</if></textarea>
					</p>
				</li>
				<if test="hasCaptcha:|:$captchaHTML">
					<li class='ipsField clear'>
						{$captchaHTML}
					</li>
				</if>
			</ul>
		</fieldset>
		<fieldset class='submit'>
			<input class='input_submit' type="submit" value="{$this->lang->words['submit_send']}" />
		</fieldset>
	</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>