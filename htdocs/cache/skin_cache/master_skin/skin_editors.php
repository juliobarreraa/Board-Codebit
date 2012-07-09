<?php
/**
 * Master skin file
 * Written: Mon, 09 Jul 2012 18:16:42 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: ajaxEditBox
//===========================================================================
function ajaxEditBox($post="", $pid=0, $error_msg="", $extraData) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="jsNotLoaded:|:$extraData['_loadJs']">
	{parse template="editorLoadJs" group="editors" params="$extraData['smilies']"}
</if>
<if test="ajaxerror:|:$error_msg">
	<p id='error_msg_e{$pid}' class='message error'>{$error_msg}</p>
<else />
	<p id='error_msg_e{$pid}' class='message error' style='display: none'>{$error_msg}</p>
</if>
<input type='hidden' name='editor_ids[]' value='e{$pid}' />
<div class='ipsBox clearfix'>
	<div class='ipsBox_container'>
		<div class='ips_editor' id='editor_e{$pid}'>
			<if test="forceStd:|:$extraData['isHtml']">
				{parse editor="Post" content="$post" options="array( 'editorName' => 'edit-' . $pid, 'type' => 'full', 'minimize' => 0, 'isRte' => 0, 'isHtml' => 1 )"}
			<else />
				{parse editor="Post" content="$post" options="array( 'editorName' => 'edit-' . $pid, 'type' => 'full', 'minimize' => 0 )"}
			</if>
			<if test="showeditoptions:|:$extraData['showEditOptions']">
				<div class='row2 ipsPad ipsText_small desc'>
					<if test="showreason:|:$extraData['showReason']">
						{$this->lang->words['preason_for_edit']} <input type='text' size='35' maxlength='250' class='input_text' id='post_edit_reason_{$pid}' name='post_edit_reason_{$pid}' value='{$extraData['reasonForEdit']}' tabindex='0' />
					</if>
					<if test="showappendedit:|:$extraData['showAppendEdit']">
					<input type='checkbox' name='add_edit_{$pid}' id='add_edit_{$pid}' <if test="appendedit:|:$extraData['append_edit']">checked='checked'</if> value='1' /> <label for='add_edit_{$pid}'>{$this->lang->words['show_edited_by']}</label>
					</if>
				</div>
				<if test="htmlstatus:|:$extraData['checkBoxes']['html'] !== null">
					<div class='row2 ipsPad ipsText_small desc'>
						<input type="checkbox" name="post_htmlstatus" class="input_check" value="1" id='post_htmlstatus_{$pid}' {$extraData['checkBoxes']['html']} /> <label for='post_htmlstatus' data-tooltip='{$this->lang->words['pp_html_tooltip']}'>{$this->lang->words['pp_html']}</label>
						<script type="text/javascript">
							ipb.textEditor.bindHtmlCheckbox( $('post_htmlstatus_{$pid}') );
						</script>
					</div>
				</if>
			</if>
			<fieldset class='submit'>			
				<input type='submit' value='{$this->lang->words['save_changes']}' class='input_submit' id='edit_save_e{$pid}' tabindex='0' /> <if test="! $extraData['skipFullButton']"><input type='submit' value='{$this->lang->words['use_full_editor']}' class='input_submit alt' id='edit_switch_e{$pid}' tabindex='0' /></if> {$this->lang->words['or']} <a href='#' title='{$this->lang->words['cancel']}' class='cancel' id='edit_cancel_e{$pid}' tabindex='0'>{$this->lang->words['cancel']}</a>
			</fieldset>
		</div>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: editor
//===========================================================================
function editor($formField='post', $content='', $options=array(), $autoSaveData=array(), $warningInfo='', $acknowledge=FALSE) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="$acknowledge">
	<p class='message'>{$this->lang->words['warnings_acknowledge_desc']} <a href='{parse url="app=members&amp;module=profile&amp;section=warnings&amp;do=acknowledge&amp;id={$acknowledge}" base="public"}' class='ipsButton_secondary'>{$this->lang->words['warnings_acknowledge_review']}</a></p>
<else />
	<if test="$warningInfo">
		<p class='message'>{$warningInfo}</p>
		<br />
	</if>
	<php>
		/* Always return as UTF-8 */
		$jsonEncoded = IPSText::jsonEncodeForTemplate( $autoSaveData );
	</php>
	<if test="jsNotLoaded:|:empty($this->_editorJsLoaded)">
		{parse template="editorLoadJs" group="editors" params="$options"}
	</if>
	<input type='hidden' name='isRte' id='isRte_{$options['editorName']}' value='{parse expression="intval( $options['isRte'] )"}' />
	<input type='hidden' name='noSmilies' id='noSmilies_{$options['editorName']}' value='{parse expression="intval( $options['noSmilies'] )"}' />
	<textarea id="{$options['editorName']}" name="{$formField}" class='ipsEditor_textarea input_text<if test="ismini:|:$options['type'] == 'mini'"> mini</if>'>{$content}</textarea>
	<p class='desc ipsPad' style='display: none' id='editor_html_message_{$options['editorName']}'>{$this->lang->words['editor_html_message']}</p>
	<script type="text/javascript">
		ipb.textEditor.initialize('{$options['editorName']}', { type: <if test="hasType:|:$options['type']">'{$options['type']}'<else />''</if>,
																height: <if test="hasHeight:|:$options['height'] > 0">{$options['height']}<else /><if test="ismini:|:$options['type'] == 'mini'">150<else />300</if></if>,
																minimize: <if test="hasMinimize:|:$options['minimize']">'{$options['minimize']}'<else />0</if>,
																bypassCKEditor: {parse expression="intval( $options['bypassCKEditor'] )"},
																delayInit: {parse expression="intval( $options['delayInit'] )"},
																isHtml: {parse expression="intval( $options['isHtml'] )"},
																isRte: {parse expression="intval( $options['isRte'] )"},
																noSmilies: {parse expression="intval( $options['noSmilies'] )"},
																isTypingCallBack: <if test="hasCallback:|:$options['isTypingCallBack']">{$options['isTypingCallBack']}<else />''</if>,
																ips_AutoSaveKey: <if test="hasSaveKey:|:$options['autoSaveKey']">'{$options['autoSaveKey']}'<else />''</if>,
												                ips_AutoSaveData: {$jsonEncoded} } );
	</script>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: editorJS
//===========================================================================
function editorJS($emoticons='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
ipb.editor_values.get('templates')['link'] = new Template("<label for='#{id}_url'>{$this->lang->words['js_template_url']}</label><input type='text' class='input_text' id='#{id}_url' value='http://' tabindex='10' /><label for='#{id}_urltext'>{$this->lang->words['js_template_link']}</label><input type='text' class='input_text _select' id='#{id}_urltext' value='{$this->lang->words['js_template_default']}' tabindex='11' /><input type='submit' class='input_submit' value='{$this->lang->words['js_template_insert_link']}' tabindex='12' />");
ipb.editor_values.get('templates')['image'] = new Template("<label for='#{id}_img'>{$this->lang->words['js_template_imageurl']}</label><input type='text' class='input_text' id='#{id}_img' value='http://' tabindex='10' /><input type='submit' class='input_submit' value='{$this->lang->words['js_template_insert_img']}' tabindex='11' />");
ipb.editor_values.get('templates')['email'] = new Template("<label for='#{id}_email'>{$this->lang->words['js_template_email_url']}</label><input type='text' class='input_text' id='#{id}_email' tabindex='10' /><label for='#{id}_emailtext'>{$this->lang->words['js_template_link']}</label><input type='text' class='input_text _select' id='#{id}_emailtext' value='{$this->lang->words['js_template_email_me']}' tabindex='11' /><input type='submit' class='input_submit' value='{$this->lang->words['js_template_insert_email']}' tabindex='12' />");
ipb.editor_values.get('templates')['media'] = new Template("<label for='#{id}_media'>{$this->lang->words['js_template_media_url']}</label><input type='text' class='input_text' id='#{id}_media' value='http://' tabindex='10' /><input type='submit' class='input_submit' value='{$this->lang->words['js_template_insert_media']}' tabindex='11' />");
ipb.editor_values.get('templates')['generic'] = new Template("<div class='rte_title'>#{title}</div><strong>{$this->lang->words['js_template_example']}</strong><pre>#{example}</pre><label for='#{id}_option' class='optional'>#{option_text}</label><input type='text' class='input_text optional' id='#{id}_option' tabindex='10' /><label for='#{id}_text'>#{value_text}</label><input type='text' class='input_text _select' id='#{id}_text' tabindex='11' /><input type='submit' class='input_submit' value='{$this->lang->words['js_template_add']}' tabindex='12' />");
ipb.editor_values.get('templates')['togglesource'] = new Template("<fieldset id='#{id}_ts_controls' class='submit' style='text-align: left'><input type='button' class='input_submit' value='{$this->lang->words['js_template_update']}' id='#{id}_ts_update' />&nbsp;&nbsp;&nbsp; <a href='#' id='#{id}_ts_cancel' class='cancel'>{$this->lang->words['js_template_cancel_source']}</a></fieldset>");
	
ipb.editor_values.get('templates')['toolbar'] = new Template("<ul id='#{id}_toolbar_#{toolbarid}' class='toolbar' style='display: none'>#{content}</ul>");
ipb.editor_values.get('templates')['button'] = new Template("<li><span id='#{id}_cmd_custom_#{cmd}' class='rte_control rte_button specialitem' title='#{title}'><img src='{$this->settings['img_url']}/rte_icons/#{img}' alt='' /></span></li>");
ipb.editor_values.get('templates')['menu_item'] = new Template("<li id='#{id}_cmd_custom_#{cmd}' class='specialitem clickable'>#{title}</li>");
ipb.editor_values.get('templates')['togglesource'] = new Template("<fieldset id='#{id}_ts_controls' class='submit' style='text-align: left'><input type='button' class='input_submit' value='{$this->lang->words['js_template_update']}' id='#{id}_ts_update' />&nbsp;&nbsp;&nbsp; <a href='#' id='#{id}_ts_cancel' class='cancel'>{$this->lang->words['js_template_cancel_source']}</a></fieldset>");
ipb.editor_values.get('templates')['emoticons_showall'] = new Template("<input class='input_submit emoticons' type='button' id='#{id}_all_emoticons' value='{$this->lang->words['show_all_emoticons']}' />");
ipb.editor_values.get('templates')['emoticon_wrapper'] = new Template("<h4><span>{$this->lang->words['emoticons_template_title']}</span></h4><div id='#{id}_emoticon_holder' class='emoticon_holder'></div>");

// Add smilies into the mix
ipb.editor_values.set( 'show_emoticon_link', false );
<if test="hasemoticons:|:$emoticons != ''">
	ipb.editor_values.set( 'emoticons', \$H({ $emoticons }) );
</if>
ipb.editor_values.set( 'bbcodes', \$H({IPSLib::fetchBbcodeAsJson()}) );
	ipb.vars['emoticon_url'] = "{$this->settings['emoticons_url']}";
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: editorLoadJs
//===========================================================================
function editorLoadJs($options='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="textEditor"}
<php>
	$this->_editorJsLoaded = true;
	$jsonEncoded = IPSText::jsonEncodeForTemplate( is_array($options['smilies']) ? $options['smilies'] : array() );
</php>
<if test="bypassCkEditor:|: ! $options['bypassCKEditor']">
	<if test="defined("CK_LOAD_SOURCE") AND CK_LOAD_SOURCE">
		<!-- Load source files, not the minified version -->
		<script type="text/javascript" src="{$this->settings['js_base_url']}js/3rd_party/ckeditor/ckeditor_source.js"></script>
	<else />
		<script type="text/javascript" src="{$this->settings['js_base_url']}js/3rd_party/ckeditor/ckeditor.js"></script>
	</if>
	<script type="text/javascript">
		/* Dynamic items */
		CKEDITOR.config.IPS_BBCODE          = {IPSLib::fetchBbcodeAsJson( array( 'skip' => array( 'sharedmedia' ) ) )};
		CKEDITOR.config.IPS_BBCODE_IMG_URL  = "{$this->settings['public_cdn_url']}style_extra/bbcode_icons";
		CKEDITOR.config.IPS_BBCODE_BUTTONS  = [];
		
		/* Has to go before config load */
		var IPS_smiley_path			= "{$this->settings['emoticons_url']}/";
		var IPS_smiles       		= <if test="hasimages:|:! empty($options['smilies']['count'])">{$jsonEncoded}<else />{}</if>;
		var IPS_remove_plugins      = [];
		var IPS_hide_contextMenu    = {parse expression="intval($this->settings['cke_hide_contextMenu'])"};
		var IPS_rclick_contextMenu  = {parse expression="intval($this->memberData['bw_cke_contextmenu'])"};
		
		/* Load our configuration */
		CKEDITOR.config.customConfig  = '{$this->settings['js_base_url']}js/3rd_party/ckeditor/ips_config.js';
	</script>
</if>
{parse expression="$this->registry->output->addToDocumentHead( 'importcss', "{$this->settings['css_base_url']}style_css/{$this->registry->output->skin['_csscacheid']}/ipb_ckeditor.css" )"}
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: editorSettings
//===========================================================================
function editorSettings() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['editor_options']}</h3>
<div class='fixed_inner ipsBox row1'>
	<div class='ipsSettings'>
		<fieldset class='ipsSettings_section'>
			<ul class='ipsForm ipsForm_horizontal'>
				<li class='ipsField'>
					<label for='bw_cke_contextmenu'>{$this->lang->words['editor_context_menu']}</label>
					<br />
					<select name='bw_cke_contextmenu' id='bw_cke_contextmenu' class='input_select'>
					<option value='0' <if test="isSelected:|:$this->memberData['bw_cke_contextmenu'] != 1"> selected="selected"</if>>{$this->lang->words['editor_context_menu_crc']}</option>
					<option value='1' <if test="isSelected:|:$this->memberData['bw_cke_contextmenu'] == 1"> selected="selected"</if>>{$this->lang->words['editor_context_menu_rc']}</option>
					
					</select>
				</li>			
				<li>
					<input type='checkbox' class='input_check' id='clearSavedContent' name="clearSavedContent" value="1" /> &nbsp;<label for='clearSavedContent'>{$this->lang->words['editor_clear_data']}</label>
				</li>
			</ul>
		</fieldset>
	</div>
	<div class='right' style='position: relative'>
		<a href='#' id='ipsEditorOptionsSave' class='ipsButton_secondary'>{$this->lang->words['editor_ok']}</a>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: editorShell
//===========================================================================
function editorShell($editor_id, $field='Post', $content='', $no_sidebar=1, $lightweight=0) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse addtohead="{$this->settings['css_base_url']}style_css/{$this->registry->output->skin['_csscacheid']}/ipb_editor.css" type="importcss"}
<if test="$show_sidebar = IPSCookie::get('emoticon_sidebar')"></if>
	<div class='ips_editor' id='editor_{$editor_id}'>
		<div class='sidebar row1 altrow' id='{$editor_id}_sidebar' style='display: none'>
			<h4><img src='{$this->settings['img_url']}/close_popup.png' alt='' id='{$editor_id}_close_sidebar' /><span>{$this->lang->words['emoticons_template_title']}</span></h4>
			<div id='{$editor_id}_emoticon_holder' class='emoticon_holder'></div>
			<div class='show_all_emoticons' id='{$editor_id}_showall_bar'>
				<input type='button' value='{$this->lang->words['show_all_emotes']}' id='{$editor_id}_showall_emoticons' class='input_submit emoticons' />
			</div>
		</div>
		<div id='{$editor_id}_controls' class='controls'>
			<if test="notlightweight:|:!$lightweight">
				<ul id='{$editor_id}_toolbar_1' class='toolbar' style='display: none'>
					<li class='left'>
						<span id='{$editor_id}_cmd_removeformat' class='rte_control rte_button' title='{$this->lang->words['js_tt_noformat']}'><img src='{$this->settings['img_url']}/rte_icons/remove_formatting.png' alt='{$this->lang->words['js_tt_noformat']}' /></span>
					</li>
					<!--<li class='left'>
						<span id='{$editor_id}_cmd_togglesource' class='rte_control rte_button' title='{$this->lang->words['js_tt_htmlsource']}'><img src='{$this->settings['img_url']}/rte_icons/toggle_source.png' alt='{$this->lang->words['js_tt_htmlsource']}' /></span>
					</li>-->
					<li class='left'>
						<span id='{$editor_id}_cmd_otherstyles' class='rte_control rte_menu rte_special' title='{$this->lang->words['box_other']}' style='display: none'>{$this->lang->words['box_other']}</span>
					</li>
					<li class='left'>
						<span id='{$editor_id}_cmd_fontname' class='rte_control rte_menu rte_font' title='{$this->lang->words['box_font']}'>{$this->lang->words['box_font']}</span>
					</li>
					<li class='left'>
						<span id='{$editor_id}_cmd_fontsize' class='rte_control rte_menu rte_fontsize' title='{$this->lang->words['box_size']}'>{$this->lang->words['box_size']}</span>
					</li>
					<li class='left'>
						<span id='{$editor_id}_cmd_forecolor' class='rte_control rte_palette' title='{$this->lang->words['js_tt_font_col']}'><img src='{$this->settings['img_url']}/rte_icons/font_color.png' alt='{$this->lang->words['js_tt_font_col']}' /></span>
					</li>
					<!--<li class='left'>
						<span id='{$editor_id}_cmd_backcolor' class='rte_control rte_palette' title='{$this->lang->words['js_tt_back_col']}'><img src='{$this->settings['img_url']}/rte_icons/background_color.png' alt='{$this->lang->words['js_tt_back_col']}' /></span>
					</li>-->
					<li class='right'>
						<span id='{$editor_id}_cmd_spellcheck' class='rte_control rte_button' title='{$this->lang->words['js_tt_spellcheck']}'><img src='{$this->settings['img_url']}/rte_icons/spellcheck.png' alt='{$this->lang->words['js_tt_spellcheck']}' /></span>
					</li>
					<li class='right'>
						<span id='{$editor_id}_cmd_r_small' class='rte_control rte_button' title='{$this->lang->words['js_tt_resizesmall']}'><img src='{$this->settings['img_url']}/rte_icons/resize_small.png' alt='{$this->lang->words['js_tt_resizesmall']}' /></span>
					</li>
					<li class='right'>
						<span id='{$editor_id}_cmd_r_big' class='rte_control rte_button' title='{$this->lang->words['js_tt_resizebig']}'><img src='{$this->settings['img_url']}/rte_icons/resize_big.png' alt='{$this->lang->words['js_tt_resizebig']}' /></span>
					</li>
					<li class='right sep'>
						<span id='{$editor_id}_cmd_help' class='rte_control rte_button' title='{$this->lang->words['js_tt_help']}'><a href='{parse url="app=forums&amp;module=extras&amp;section=legends&amp;do=bbcode" base="public"}' title='{$this->lang->words['js_tt_help']}'><img src='{$this->settings['img_url']}/rte_icons/help.png' alt='{$this->lang->words['js_tt_help']}' /></a></span>
					</li>
					<li class='right'>
						<span id='{$editor_id}_cmd_undo' class='rte_control rte_button' title='{$this->lang->words['js_tt_undo']}'><img src='{$this->settings['img_url']}/rte_icons/undo.png' alt='{$this->lang->words['js_tt_undo']}' /></span>
					</li>
					<li class='right'>
						<span id='{$editor_id}_cmd_redo' class='rte_control rte_button' title='{$this->lang->words['js_tt_redo']}'><img src='{$this->settings['img_url']}/rte_icons/redo.png' alt='{$this->lang->words['js_tt_redo']}' /></span>
					</li>
				</ul>
			</if>
			<ul id='{$editor_id}_toolbar_2' class='toolbar' style='display: none'>
				<li>
					<span id='{$editor_id}_cmd_bold' class='rte_control rte_button' title='{$this->lang->words['js_tt_bold']}'><img src='{$this->settings['img_url']}/rte_icons/bold.png' alt='{$this->lang->words['js_tt_bold']}' /></span>
				</li>
				<li>
					<span id='{$editor_id}_cmd_italic' class='rte_control rte_button' title='{$this->lang->words['js_tt_italic']}'><img src='{$this->settings['img_url']}/rte_icons/italic.png' alt='{$this->lang->words['js_tt_italic']}' /></span>
				</li>
				<li>
					<span id='{$editor_id}_cmd_underline' class='rte_control rte_button' title='{$this->lang->words['js_tt_underline']}'><img src='{$this->settings['img_url']}/rte_icons/underline.png' alt='{$this->lang->words['js_tt_underline']}' /></span>
				</li>
				<li class='sep'>
					<span id='{$editor_id}_cmd_strikethrough' class='rte_control rte_button' title='{$this->lang->words['js_tt_strike']}'><img src='{$this->settings['img_url']}/rte_icons/strike.png' alt='{$this->lang->words['js_tt_strike']}' /></span>
				</li>
				<if test="shellnotlightweight:|:!$lightweight">
					<li>
						<span id='{$editor_id}_cmd_subscript' class='rte_control rte_button' title='{$this->lang->words['js_tt_sub']}'><img src='{$this->settings['img_url']}/rte_icons/subscript.png' alt='{$this->lang->words['js_tt_sub']}' /></span>
					</li>
					<li class='sep'>
						<span id='{$editor_id}_cmd_superscript' class='rte_control rte_button' title='{$this->lang->words['js_tt_sup']}'><img src='{$this->settings['img_url']}/rte_icons/superscript.png' alt='{$this->lang->words['js_tt_sup']}' /></span>
					</li>
					<li>
						<span id='{$editor_id}_cmd_insertunorderedlist' class='rte_control rte_button' title='{$this->lang->words['js_tt_list']}'><img src='{$this->settings['img_url']}/rte_icons/unordered_list.png' alt='{$this->lang->words['js_tt_list']}' /></span>
					</li>
					<li class='sep'>
						<span id='{$editor_id}_cmd_insertorderedlist' class='rte_control rte_button' title='{$this->lang->words['js_tt_list']}'><img src='{$this->settings['img_url']}/rte_icons/ordered_list.png' alt='{$this->lang->words['js_tt_list']}' /></span>
					</li>
				</if>
			<if test="shellremoveemoticons:|:$this->settings['_remove_emoticons']==0">
				<li>
					<span id='{$editor_id}_cmd_emoticons' class='rte_control rte_button' title='{$this->lang->words['js_tt_emoticons']}'><img src='{$this->settings['img_url']}/rte_icons/emoticons.png' alt='{$this->lang->words['js_tt_emoticons']}' /></span>
				</li>
			</if>
				<li>
					<span id='{$editor_id}_cmd_link' class='rte_control rte_palette' title='{$this->lang->words['js_tt_link']}'><img src='{$this->settings['img_url']}/rte_icons/link.png' alt='{$this->lang->words['js_tt_link']}' /></span>
				</li>
				<li>
					<span id='{$editor_id}_cmd_image' class='rte_control rte_palette' title='{$this->lang->words['js_tt_image']}'><img src='{$this->settings['img_url']}/rte_icons/picture.png' alt='{$this->lang->words['js_tt_image']}' /></span>
				</li>
				<li>
					<span id='{$editor_id}_cmd_email' class='rte_control rte_palette' title='{$this->lang->words['js_tt_email']}'><img src='{$this->settings['img_url']}/rte_icons/email.png' alt='{$this->lang->words['js_tt_email']}' /></span>
				</li>
				<li>
					<span id='{$editor_id}_cmd_ipb_quote' class='rte_control rte_button' title='{$this->lang->words['js_tt_quote']}'><img src='{$this->settings['img_url']}/rte_icons/quote.png' alt='{$this->lang->words['js_tt_quote']}' /></span>
				</li>
				<li>
					<span id='{$editor_id}_cmd_ipb_code' class='rte_control rte_button' title='{$this->lang->words['js_tt_code']}'><img src='{$this->settings['img_url']}/rte_icons/code.png' alt='{$this->lang->words['js_tt_code']}' /></span>
				</li>
				<li>
					<span id='{$editor_id}_cmd_media' class='rte_control rte_palette' title='{$this->lang->words['js_tt_media']}'><img src='{$this->settings['img_url']}/rte_icons/media.png' alt='{$this->lang->words['js_tt_media']}' /></span>
				</li>
				<if test="shellsecondbarlightweight:|:!$lightweight">
					<li class='right'>
						<span id='{$editor_id}_cmd_justifyright' class='rte_control rte_button' title='{$this->lang->words['js_tt_right']}'><img src='{$this->settings['img_url']}/rte_icons/align_right.png' alt='{$this->lang->words['js_tt_right']}' /></span>
					</li>
					<li class='right'>
						<span id='{$editor_id}_cmd_justifycenter' class='rte_control rte_button' title='{$this->lang->words['js_tt_center']}'><img src='{$this->settings['img_url']}/rte_icons/align_center.png' alt='{$this->lang->words['js_tt_center']}' /></span>
					</li>
					<li class='right'>
						<span id='{$editor_id}_cmd_justifyleft' class='rte_control rte_button' title='{$this->lang->words['js_tt_left']}'><img src='{$this->settings['img_url']}/rte_icons/align_left.png' alt='{$this->lang->words['js_tt_left']}' /></span>
					</li>
					<li class='right sep'>
						<span id='{$editor_id}_cmd_indent' class='rte_control rte_button' title='{$this->lang->words['js_tt_indent']}'><img src='{$this->settings['img_url']}/rte_icons/indent.png' alt='{$this->lang->words['js_tt_indent']}' /></span>
					</li>
					<li class='right'>
						<span id='{$editor_id}_cmd_outdent' class='rte_control rte_button' title='{$this->lang->words['js_tt_outdent']}'><img src='{$this->settings['img_url']}/rte_icons/outdent.png' alt='{$this->lang->words['js_tt_outdent']}' /></span>
					</li>
				</if>
			</ul>
		</div>
		<div id='{$editor_id}_wrap' class='editor'>
			<textarea name="{$field}" class="input_rte" id="{$editor_id}_textarea" rows="10" cols="60" tabindex="0">{$content}</textarea>
		</div>
	</div>
	<script type='text/javascript'>
		if( $( '{$editor_id}_toolbar_1' ) ){ $( '{$editor_id}_toolbar_1' ).show(); }
		if( $( '{$editor_id}_toolbar_2' ) ){ $( '{$editor_id}_toolbar_2' ).show(); }
	</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: ips_editor
//===========================================================================
function ips_editor($form_field="",$initial_content="",$images_path="",$rte_mode=0,$editor_id='ed-0',$smilies='',$allow_sidebar=1) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<!-- RTE ON: $rte_mode -->
{parse js_module="editor"}
{parse addtohead="{$this->settings['css_base_url']}style_css/{$this->registry->output->skin['_csscacheid']}/ipb_editor.css" type="importcss"}
<!--top-->
<input type='hidden' name='{$editor_id}_wysiwyg_used' id='{$editor_id}_wysiwyg_used' value='0' />
<input type='hidden' name='editor_ids[]' value='{$editor_id}' />
<if test="$show_sidebar = IPSCookie::get('emoticon_sidebar')"></if>
<div class='ips_editor <if test="$show_sidebar == '1' && $this->settings['_remove_emoticons'] == 0">with_sidebar</if>' id='editor_{$editor_id}'>
	<if test="$this->settings['_remove_emoticons'] == 0">
		<div class='sidebar row1 altrow' id='{$editor_id}_sidebar' <if test="IPSCookie::get('emoticon_sidebar') != '1'">style='display: none'</if>>
			<h4><img src='{$this->settings['img_url']}/close_popup.png' alt='' id='{$editor_id}_close_sidebar' /><span>{$this->lang->words['emoticons_template_title']}</span></h4>
			<div id='{$editor_id}_emoticon_holder' class='emoticon_holder'></div>
			<div class='show_all_emoticons' id='{$editor_id}_showall_bar'>
				<input type='button' value='{$this->lang->words['show_all_emotes']}' id='{$editor_id}_showall_emoticons' class='input_submit emoticons' />
			</div>
		</div>
	</if>
	<div id='{$editor_id}_controls' class='controls'>
		<ul id='{$editor_id}_toolbar_1' class='toolbar' style='display: none'>
			<li class='left'>
				<span id='{$editor_id}_cmd_removeformat' class='rte_control rte_button' title='{$this->lang->words['js_tt_noformat']}'><img src='{$this->settings['img_url']}/rte_icons/remove_formatting.png' alt='{$this->lang->words['js_tt_noformat']}' /></span>
			</li>
			<!--<li class='left'>
				<span id='{$editor_id}_cmd_togglesource' class='rte_control rte_button' title='{$this->lang->words['js_tt_htmlsource']}'><img src='{$this->settings['img_url']}/rte_icons/toggle_source.png' alt='{$this->lang->words['js_tt_htmlsource']}' /></span>
			</li>-->
			<li class='left'>
				<span id='{$editor_id}_cmd_otherstyles' class='rte_control rte_menu rte_special' title='{$this->lang->words['box_other_desc']}' style='display: none'>{$this->lang->words['box_other']}</span>
			</li>
			<li class='left'>
				<span id='{$editor_id}_cmd_fontname' class='rte_control rte_menu rte_font' title='{$this->lang->words['box_font_desc']}'>{$this->lang->words['box_font']}</span>
			</li>
			<li class='left'>
				<span id='{$editor_id}_cmd_fontsize' class='rte_control rte_menu rte_fontsize' title='{$this->lang->words['box_size_desc']}'>{$this->lang->words['box_size']}</span>
			</li>
			<li class='left'>
				<span id='{$editor_id}_cmd_forecolor' class='rte_control rte_palette' title='{$this->lang->words['js_tt_font_col']}'><img src='{$this->settings['img_url']}/rte_icons/font_color.png' alt='{$this->lang->words['js_tt_font_col']}' /></span>
			</li>
			<!--<li class='left'>
				<span id='{$editor_id}_cmd_backcolor' class='rte_control rte_palette' title='{$this->lang->words['js_tt_back_col']}'><img src='{$this->settings['img_url']}/rte_icons/background_color.png' alt='{$this->lang->words['js_tt_back_col']}' /></span>
			</li>-->
			
			<li class='right'>
				<span id='{$editor_id}_cmd_spellcheck' class='rte_control rte_button' title='{$this->lang->words['js_tt_spellcheck']}'><img src='{$this->settings['img_url']}/rte_icons/spellcheck.png' alt='{$this->lang->words['js_tt_spellcheck']}' /></span>
			</li>
			<li class='right'>
				<span id='{$editor_id}_cmd_r_small' class='rte_control rte_button' title='{$this->lang->words['js_tt_resizesmall']}'><img src='{$this->settings['img_url']}/rte_icons/resize_small.png' alt='{$this->lang->words['js_tt_resizesmall']}' /></span>
			</li>
			<li class='right'>
				<span id='{$editor_id}_cmd_r_big' class='rte_control rte_button' title='{$this->lang->words['js_tt_resizebig']}'><img src='{$this->settings['img_url']}/rte_icons/resize_big.png' alt='{$this->lang->words['js_tt_resizebig']}' /></span>
			</li>
			<li class='right sep'>
				<span id='{$editor_id}_cmd_help' class='rte_control rte_button' title='{$this->lang->words['js_tt_help']}'><a href='{parse url="app=forums&amp;module=extras&amp;section=legends&amp;do=bbcode" base="public"}' title='{$this->lang->words['js_tt_help']}'><img src='{$this->settings['img_url']}/rte_icons/help.png' alt='{$this->lang->words['js_tt_help']}' /></a></span>
			</li>			
			<li class='right sep'>
				<span id='{$editor_id}_cmd_undo' class='rte_control rte_button' title='{$this->lang->words['js_tt_undo']}'><img src='{$this->settings['img_url']}/rte_icons/undo.png' alt='{$this->lang->words['js_tt_undo']}' /></span>
			</li>
			<li class='right'>
				<span id='{$editor_id}_cmd_redo' class='rte_control rte_button' title='{$this->lang->words['js_tt_redo']}'><img src='{$this->settings['img_url']}/rte_icons/redo.png' alt='{$this->lang->words['js_tt_redo']}' /></span>
			</li>
		</ul>
		<ul id='{$editor_id}_toolbar_2' class='toolbar' style='display: none'>
			<li>
				<span id='{$editor_id}_cmd_bold' class='rte_control rte_button' title='{$this->lang->words['js_tt_bold']}'><img src='{$this->settings['img_url']}/rte_icons/bold.png' alt='{$this->lang->words['js_tt_bold']}' /></span>
			</li>
			<li>
				<span id='{$editor_id}_cmd_italic' class='rte_control rte_button' title='{$this->lang->words['js_tt_italic']}'><img src='{$this->settings['img_url']}/rte_icons/italic.png' alt='{$this->lang->words['js_tt_italic']}' /></span>
			</li>
			<li>
				<span id='{$editor_id}_cmd_underline' class='rte_control rte_button' title='{$this->lang->words['js_tt_underline']}'><img src='{$this->settings['img_url']}/rte_icons/underline.png' alt='{$this->lang->words['js_tt_underline']}' /></span>
			</li>
			<li class='sep'>
				<span id='{$editor_id}_cmd_strikethrough' class='rte_control rte_button' title='{$this->lang->words['js_tt_strike']}'><img src='{$this->settings['img_url']}/rte_icons/strike.png' alt='{$this->lang->words['js_tt_strike']}' /></span>
			</li>
			<li>
				<span id='{$editor_id}_cmd_subscript' class='rte_control rte_button' title='{$this->lang->words['js_tt_sub']}'><img src='{$this->settings['img_url']}/rte_icons/subscript.png' alt='{$this->lang->words['js_tt_sub']}' /></span>
			</li>
			<li class='sep'>
				<span id='{$editor_id}_cmd_superscript' class='rte_control rte_button' title='{$this->lang->words['js_tt_sup']}'><img src='{$this->settings['img_url']}/rte_icons/superscript.png' alt='{$this->lang->words['js_tt_sup']}' /></span>
			</li>
			<li>
				<span id='{$editor_id}_cmd_insertunorderedlist' class='rte_control rte_button' title='{$this->lang->words['js_tt_list']}'><img src='{$this->settings['img_url']}/rte_icons/unordered_list.png' alt='{$this->lang->words['js_tt_list']}' /></span>
			</li>
			<li class='sep'>
				<span id='{$editor_id}_cmd_insertorderedlist' class='rte_control rte_button' title='{$this->lang->words['js_tt_list']}'><img src='{$this->settings['img_url']}/rte_icons/ordered_list.png' alt='{$this->lang->words['js_tt_list']}' /></span>
			</li>			
		<if test="removeemoticons:|:$this->settings['_remove_emoticons'] == 0">
			<li>
				<span id='{$editor_id}_cmd_emoticons' class='rte_control rte_button' title='{$this->lang->words['js_tt_emoticons']}'><img src='{$this->settings['img_url']}/rte_icons/emoticons.png' alt='{$this->lang->words['js_tt_emoticons']}' /></span>
			</li>
		</if>
			<li>
				<span id='{$editor_id}_cmd_link' class='rte_control rte_palette' title='{$this->lang->words['js_tt_link']}'><img src='{$this->settings['img_url']}/rte_icons/link.png' alt='{$this->lang->words['js_tt_link']}' /></span>
			</li>
			<li>
				<span id='{$editor_id}_cmd_image' class='rte_control rte_palette' title='{$this->lang->words['js_tt_image']}'><img src='{$this->settings['img_url']}/rte_icons/picture.png' alt='{$this->lang->words['js_tt_image']}' /></span>
			</li>
			<li>
				<span id='{$editor_id}_cmd_email' class='rte_control rte_palette' title='{$this->lang->words['js_tt_email']}'><img src='{$this->settings['img_url']}/rte_icons/email.png' alt='{$this->lang->words['js_tt_email']}' /></span>
			</li>
			<li>
				<span id='{$editor_id}_cmd_ipb_quote' class='rte_control rte_button' title='{$this->lang->words['js_tt_quote']}'><img src='{$this->settings['img_url']}/rte_icons/quote.png' alt='{$this->lang->words['js_tt_quote']}' /></span>
			</li>
			<li>
				<span id='{$editor_id}_cmd_ipb_code' class='rte_control rte_button' title='{$this->lang->words['js_tt_code']}'><img src='{$this->settings['img_url']}/rte_icons/code.png' alt='{$this->lang->words['js_tt_code']}' /></span>
			</li>
			<li>
				<span id='{$editor_id}_cmd_media' class='rte_control rte_palette' title='{$this->lang->words['js_tt_media']}'><img src='{$this->settings['img_url']}/rte_icons/media.png' alt='{$this->lang->words['js_tt_media']}' /></span>
			</li>
			<li class='right'>
				<span id='{$editor_id}_cmd_justifyright' class='rte_control rte_button' title='{$this->lang->words['js_tt_right']}'><img src='{$this->settings['img_url']}/rte_icons/align_right.png' alt='{$this->lang->words['js_tt_right']}' /></span>
			</li>
			<li class='right'>
				<span id='{$editor_id}_cmd_justifycenter' class='rte_control rte_button' title='{$this->lang->words['js_tt_center']}'><img src='{$this->settings['img_url']}/rte_icons/align_center.png' alt='{$this->lang->words['js_tt_center']}' /></span>
			</li>
			<li class='right'>
				<span id='{$editor_id}_cmd_justifyleft' class='rte_control rte_button' title='{$this->lang->words['js_tt_left']}'><img src='{$this->settings['img_url']}/rte_icons/align_left.png' alt='{$this->lang->words['js_tt_left']}' /></span>
			</li>
			<li class='right sep'>
				<span id='{$editor_id}_cmd_indent' class='rte_control rte_button' title='{$this->lang->words['js_tt_indent']}'><img src='{$this->settings['img_url']}/rte_icons/indent.png' alt='{$this->lang->words['js_tt_indent']}' /></span>
			</li>
			<li class='right'>
				<span id='{$editor_id}_cmd_outdent' class='rte_control rte_button' title='{$this->lang->words['js_tt_outdent']}'><img src='{$this->settings['img_url']}/rte_icons/outdent.png' alt='{$this->lang->words['js_tt_outdent']}' /></span>
			</li>
		</ul>
	</div>
	<div id='{$editor_id}_wrap' class='editor'>
		<textarea name="{$form_field}" class="input_rte" id="{$editor_id}_textarea" rows="10" cols="60" tabindex="0">{$initial_content}</textarea>
	</div>
</div>
	
<!-- Toolpanes -->
<script type="text/javascript">
//<![CDATA[
$('{$editor_id}_toolbar_1').show();
$('{$editor_id}_toolbar_2').show();
// Rikki: Had to remove <form>... </form> because Opera would see </form> and not pass the topic icons / hidden fields properly. Tried "</" + "form>" but when it is parsed, it had the same affect
ipb.editor_values.get('templates')['link'] = new Template("<label for='#{id}_url'>{$this->lang->words['js_template_url']}</label><input type='text' class='input_text' id='#{id}_url' value='http://' tabindex='10' /><label for='#{id}_urltext'>{$this->lang->words['js_template_link']}</label><input type='text' class='input_text _select' id='#{id}_urltext' value='{$this->lang->words['js_template_default']}' tabindex='11' /><input type='submit' class='input_submit' value='{$this->lang->words['js_template_insert_link']}' tabindex='12' />");
ipb.editor_values.get('templates')['image'] = new Template("<label for='#{id}_img'>{$this->lang->words['js_template_imageurl']}</label><input type='text' class='input_text' id='#{id}_img' value='http://' tabindex='10' /><input type='submit' class='input_submit' value='{$this->lang->words['js_template_insert_img']}' tabindex='11' />");
ipb.editor_values.get('templates')['email'] = new Template("<label for='#{id}_email'>{$this->lang->words['js_template_email_url']}</label><input type='text' class='input_text' id='#{id}_email' tabindex='10' /><label for='#{id}_emailtext'>{$this->lang->words['js_template_link']}</label><input type='text' class='input_text _select' id='#{id}_emailtext' value='{$this->lang->words['js_template_email_me']}' tabindex='11' /><input type='submit' class='input_submit' value='{$this->lang->words['js_template_insert_email']}' tabindex='12' />");
ipb.editor_values.get('templates')['media'] = new Template("<label for='#{id}_media'>{$this->lang->words['js_template_media_url']}</label><input type='text' class='input_text' id='#{id}_media' value='http://' tabindex='10' /><input type='submit' class='input_submit' value='{$this->lang->words['js_template_insert_media']}' tabindex='11' />");
ipb.editor_values.get('templates')['generic'] = new Template("<div class='rte_title'>#{title}</div><strong>{$this->lang->words['js_template_example']}</strong><pre>#{example}</pre><label for='#{id}_option' class='optional'>#{option_text}</label><input type='text' class='input_text optional' id='#{id}_option' tabindex='10' /><label for='#{id}_text' class='tagcontent'>#{value_text}</label><textarea class='input_text _select tagcontent' id='#{id}_text' tabindex='11' rows='4' cols='30' style='width: 98%' /></textarea><input type='submit' class='input_submit' value='{$this->lang->words['js_template_add']}' tabindex='12' />");
ipb.editor_values.get('templates')['toolbar'] = new Template("<ul id='#{id}_toolbar_#{toolbarid}' class='toolbar' style='display: none'>#{content}</ul>");
ipb.editor_values.get('templates')['button'] = new Template("<li><span id='#{id}_cmd_custom_#{cmd}' class='rte_control rte_button specialitem' title='#{title}'><img src='{$this->settings['img_url']}/rte_icons/#{img}' alt='' /></span></li>");
ipb.editor_values.get('templates')['menu_item'] = new Template("<li id='#{id}_cmd_custom_#{cmd}' class='specialitem clickable'>#{title}</li>");
ipb.editor_values.get('templates')['togglesource'] = new Template("<fieldset id='#{id}_ts_controls' class='submit' style='text-align: left'><input type='button' class='input_submit' value='{$this->lang->words['js_template_update']}' id='#{id}_ts_update' />&nbsp;&nbsp;&nbsp; <a href='#' id='#{id}_ts_cancel' class='cancel'>{$this->lang->words['js_template_cancel_source']}</a></fieldset>");
ipb.editor_values.get('templates')['emoticons_showall'] = new Template("<input class='input_submit emoticons' type='button' id='#{id}_all_emoticons' value='{$this->lang->words['show_all_emoticons']}' />");
ipb.editor_values.get('templates')['emoticon_wrapper'] = new Template("<h4><span>{$this->lang->words['emoticons_template_title']}</span></h4><div id='#{id}_emoticon_holder' class='emoticon_holder'></div>");
// Add smilies into the mix
ipb.editor_values.set( 'show_emoticon_link', <if test="$allow_sidebar">true<else />false</if> );
ipb.editor_values.set( 'emoticons', \$H({ $smilies }) );
ipb.editor_values.set( 'bbcodes', \$H({IPSLib::fetchBbcodeAsJson()}) );
ipb.vars['emoticon_url'] = "{$this->settings['emoticons_url']}";
ipb.editors[ '{$editor_id}' ] = new ipb.editor( '{$editor_id}', USE_RTE );
//]]>
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: mediaGenericWrapper
//===========================================================================
function mediaGenericWrapper($rows, $pages, $app, $plugin) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='clearfix'>
	{$pages}
</div>
<div>
	<ul class='media_results'>
		<if test="hasrows:|:count($rows)">
			<foreach loop="genericmedia:$rows as $row">
				<li class='result' onclick="return CKEDITOR.plugins.ipsmedia.insert( '{$row['insert']}' );">
					<if test="hasimage:|:$row['image']">
							<img src='{$row['image']}' alt=''<if test="haswidth:|:$row['width']"> width='{$row['width']}'</if><if test="hasheight:|:$row['height']"> height='{$row['height']}'</if> style='max-width: 80px;' class='media_image' /><br />
					</if>
					
						<strong>{parse expression="IPSText::truncate( $row['title'], 15 )"}</strong>
						<if test="hasdescription:|:$row['desc']">
							<br /><span class='desc'>{parse expression="IPSText::truncate( $row['desc'], 15 )"}</span>
						</if>
				</li>
			</foreach>
		<else />
			<li class='no_messages'>
				{$this->lang->words['no_mymedia_rows']}
			</li>
		</if>
	</ul>
</div>
<div class='clearfix'>
	{$pages}
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: sharedMedia
//===========================================================================
function sharedMedia($tabs) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['mymedia_title']}</h3>
<div class='fixed_inner ipsBox'>
	<div id='mymedia_inserted' style='display: none'>{$this->lang->words['added_to_editor']}</div>
	<div class='ipsVerticalTabbed ipsLayout ipsLayout_withleft ipsLayout_smallleft clearfix'>
		<div class='ipsVerticalTabbed_tabs ipsLayout_left'>
			<ul id='mymedia_tabs'>
				<foreach loop="mediatabs:$tabs as $tab">
					<li id='{$tab['app']}_{$tab['plugin']}'><a href='#' onclick="return CKEDITOR.plugins.ipsmedia.loadTab( '{$tab['app']}', '{$tab['plugin']}' );">{$tab['title']}</a></li>
				</foreach>
			</ul>
		</div>
		<div class='ipsVerticalTabbed_content ipsLayout_content ipsBox_container' style='position: relative'>
			<div class='ipsType_small' id='mymedia_toolbar'>
				<a href='#' id='mymedia_finish' class='ipsButton no_width' onclick="CKEDITOR.plugins.ipsmedia.popup.hide(); return false;"><img src='{$this->settings['img_url']}/accept.png' /> &nbsp;{$this->lang->words['mymedia_finished']}</a>
				<input type='hidden' name='sharedmedia_search_app' id='sharedmedia_search_app' value='' />
				<input type='hidden' name='sharedmedia_search_plugin' id='sharedmedia_search_plugin' value='' />
				<input type='text' name='search_string' id='sharedmedia_search' value="{$this->lang->words['start_typing_sms']}" size='30' class='input_text inactive' />
				<input class='input_submit' type='button' id='sharedmedia_submit' value='{$this->lang->words['search_string_search']}' />
				&nbsp;&nbsp;<a href='#' id='sharedmedia_reset' class='ipsType_smaller'>{$this->lang->words['search_string_reset']}</a>
			</div>
			<div id='mymedia_content' class='ipsPad'>
				{parse template="sharedMediaDefault" group="editors" params=""}
			</div>
		</div>
	</div>
</div>
<script type='text/javascript'>
ipb.vars['sm_init_value']	= "{$this->lang->words['start_typing_sms']}";
CKEDITOR.plugins.ipsmedia.searchinit();
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: sharedMediaDefault
//===========================================================================
function sharedMediaDefault() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle' style='text-align: center'>{$this->lang->words['mymedia_title']}</h1>
				<h2 class='ipsType_subtitle desc' style='text-align: center'>{$this->lang->words['shareable_media_warn']}</h2>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>