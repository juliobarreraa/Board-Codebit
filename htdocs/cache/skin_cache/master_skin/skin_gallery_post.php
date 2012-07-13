<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: attachiFrameMediaThumb
//===========================================================================
function attachiFrameMediaThumb($id, $JSON='{}') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<html>
	<body style='background-color: transparent;'>
		<form id='mtiframeUploadForm' method='post' enctype="multipart/form-data" action=''>
			<script type='text/javascript'>
				parent.ipb.gallery.mediaUpload._setJSON( '$id', $JSON );
			</script>
			<input type='file' id='mtiframeUploadBox_{$id}' style='display: inline' name='FILE_UPLOAD' /> <input type='reset' value='{$this->lang->words['clear_selection']}' style='display: inline' />
		</form>
	</body>
</html>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: attachiFrameUpload
//===========================================================================
function attachiFrameUpload($id, $JSON='{}') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<html>
	<head>
		<style type="text/css">
			* {
				margin: 0px;
				padding: 0px;
			}
			
			#waitImg {
				position: relative;
				top: 4px;
			}
			
			.input {
				padding: 4px;
				background-color: #1D3652;
				border-color: #4F7298 #113051 #113051 #4F7298;
				color: white;
			}
		</style>
	</head>
	<body style='background-color: transparent; width: 400px;'>
		<form id='iframeUploadForm' method='post' enctype="multipart/form-data" action=''>
			<script type='text/javascript'>
				parent.ipb.uploader._jsonPass( '$id', $JSON );
			</script>
			<input type='file' id='iframeUploadBox_{$id}' style='display: inline' class='input' name='FILE_UPLOAD' /> <input type='reset' class='input' value='{$this->lang->words['clear_selection']}' style='display: inline' />
			<img src="{$this->settings['img_url']}/loading.gif" style='display: none' id="waitImg" />
		</form>
	</body>
</html>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: editImageForm
//===========================================================================
function editImageForm($image=array(), $album=array(), $errors=array(), $preview='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{parse expression="sprintf( $this->lang->words['editing_image'], $image['caption'] )"}</h1>
<br />
<if test="hasErrors:|:is_array($errors) && count($errors)">
	<div class='message error'>
		{parse expression="implode( '<br />', $errors )"}
	</div>
	<br />
</if>
<if test="hasPreview:|:$preview">
	<h3 class='maintitle'>{$this->lang->words['form_preview']}</h3>
	<div class='ipsBox'>
		<div class='ipsBox_container ipsPad'>
			{$preview}
		</div>
	</div>
	<br />
</if>
<form method='post' action="{parse url="app=gallery&amp;module=post&amp;section=image&amp;do=editImageSave" base="public"}" enctype="multipart/form-data">
	<input type="hidden" name="img" value="{$image['id']}" />
	<div class='ipsBox'>
		<div class='ipsBox_container ipsPad'>
			<div class='ipsLayout ipsLayout_withleft ipsLayout_smallleft clearfix'>
				<div class='ipsLayout_left'>
					{$image['thumb']}
				</div>
				<div class='ipsLayout_content'>
					<ul class='ipsForm ipsForm_vertical'>
						<li class='ipsField'>
							<label class='ipsField_title'>{$this->lang->words['title_ucfirst']}</label>
							<p class='ipsField_content'><input type='text' name='caption' class='input_text' style='width:95%;' value='{$image['_caption']}' /></p>
						</li>
						<if test="hasTags:|:$image['_tagBox']">
							<li class='ipsField'>
								<label class='ipsField_title'>{$this->lang->words['review_tags']}</label>
								<div class='ipsField_content'>
								{$image['_tagBox']}
								</div>
							</li>
						</if>
						<li class='ipsField'>
							<label class='ipsField_title'>{$this->lang->words['description_ucfirst']}</label>
							<div class='ipsField_content'>
								{$image['editor']}
							</div>
						</li>
						<if test="geo:|:$image['image_gps_lat'] AND $image['image_loc_short']">
							<li class='ipsField ipsField_checkbox'>
								<input type='checkbox' id='image_gps_show' name='image_gps_show' <if test="clickGps:|:$image['image_gps_show']">checked='checked'</if>value='1'>
								<label for='image_gps_show'>
									<p class='class='ipsField_content'>
										&nbsp;{$this->lang->words['review_show_loc']} <span class='desc'>({$image['image_loc_short']})</span>
									<p>
								</label>
							</li>
						</if>
						<li class='ipsField'>
							<label class='ipsField_title'>{$this->lang->words['copyright_ucfirst']}</label>
							<p class='ipsField_content'><input type='text'name='copyright' class='input_text' style='width:95%;' value='{$image['copyright']}' /></p>
						</li>
						<li class='ipsField'>
							<label class='ipsField_title'>{$this->lang->words['upload_new_image']}</label>
							<p class='ipsField_content'>
								<input type='file' class='input_upload' name='newImage' />
								<div class='desc'>{$this->lang->words['upload_new_image_desc']}</div>
							</p>
						</li>
					</ul>
				</div>
			</div>				
		</div>
		<br />
	</div>
	<fieldset class='submit'>
		<input type='submit' name='dosubmit' value='{$this->lang->words['edit_post']}' class='input_submit' tabindex='0' />&nbsp;<input type='submit' name='preview' value='{$this->lang->words['form_preview']}' class='input_submit alt' tabindex='0' />
		{$this->lang->words['or']} <a href='{parse url="app=gallery&amp;image={$image['id']}" template="viewimage" seotitle="{$image['caption_seo']}" base="public"}' title='{$this->lang->words['cancel']}' class='cancel' tabindex='0'>{$this->lang->words['cancel']}</a>
	</fieldset>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: uploadForm
//===========================================================================
function uploadForm($sessionKey='', $album=array(), $stats=array(), $allowed_file_types) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/swfupload/swfupload.js'></script>
<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/swfupload/plugins/swfupload.swfobject.js'></script>
<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/swfupload/plugins/swfupload.cookies.js'></script>
<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/swfupload/plugins/swfupload.queue.js'></script>
<script type='text/javascript'>
//<![CDATA[
	ipb.lang['used_space'] = "{parse expression="sprintf( $this->lang->words['used_space_js'], "{$stats['maxItemHuman']}" )"}";
//]]>
</script>
{parse js_module="gallery_uploader"}
{parse js_module="gallery_albumchooser"}

<h1 class='ipsType_pagetitle'>{$this->lang->words['upload_ucfirst']}</h1>
<br />
<div class='ipsSteps clearfix'>
	<ul>
		<li class="ipsSteps_active">
			<strong class='ipsSteps_title'>{parse expression="sprintf( $this->lang->words['step'], '1' )"}</strong>
			<span class='ipsSteps_desc'>{$this->lang->words['review_step_upload']}</span>
			<span class='ipsSteps_arrow'>&nbsp;</span>
		</li>
		<li>
			<strong class='ipsSteps_title'>{parse expression="sprintf( $this->lang->words['step'], '2' )"}</strong>
			<span class='ipsSteps_desc'>{$this->lang->words['review_step_publish']}</span>
			<span class='ipsSteps_arrow'>&nbsp;</span>
		</li>	
	</ul>
</div>
<br />
<if test="isModerating:|:$album['album_g_approve_img'] AND ! $this->registry->gallery->helper('albums')->canModerate( $album )">
<div class='message unspecific'>
<h3>{$this->lang->words['image_approval_required']}</h3>
<p>{$this->lang->words['image_approval_required_text']}</p>
</div>
<br />
</if>
<div id='albumBoxWrap' class='ipsBox'>
	<div class='ipsBox_container ipsPad'>
		<div id='albumWrap'></div>
		<div id='albumWrapNone' style='display: none'>
			<div class='ipsLayout ipsLayout_withleft'>
				<div class='ipsLayout_left'>
					<img src="{$this->settings['img_url']}/gallery/missingphotothumb.png" class="ipsUserPhoto galattach" />
				</div>
				<div class='ipsLayout_content'>
					<br />
					<h4>{$this->lang->words['no_album_selected']}</h4><br />
					<br />
					<p class='desc'>
						<if test="canCreate1:|:$this->registry->gallery->helper('albums')->canCreate()"><a href='javascript:void(0);' data-parentid="{parse expression="intval($album['_parent_id'])"}"class='ipsButton_secondary _albumNew'>{$this->lang->words['new_album']}</a>&nbsp;&nbsp;</if>
						<a href='javascript:void(0);' class='ipsButton_secondary' data-album-selector-callback='ipb.gallery.callBackForUploadFormForAlbumSelector' data-album-selector='type=upload'>{$this->lang->words['select_album']}</a>
					</p>
				</div>
			</div>
			<br class='clear' />
		</div>
	</div>
</div>
<div class='ipsBox' id='uploadBoxWrapParent'>
	<div id='uploadBoxWrap' class='ipsBox_container ipsPad' style='display:none'>
		<div id='attachWrap'>
			<ul id='attachments'><li style='display: none'></li></ul>
		</div>
	</div>
	<div id='attach_error_box' class='message error forum_rules' style='display:none'></div>
	<!--SKINNOTE: traditional uploader needs this. -->
	<input type='file' id='nojs_attach_{$sessionKey}_1' class='input_upload' name='FILE_UPLOAD' tabindex='1' />
	<input type='file' id='nojs_attach_{$sessionKey}_2' class='input_upload' name='FILE_UPLOAD' tabindex='1' />
	<div id='uploadFieldWrap' style='display:none'>
		<div class='ipsBox_container'>
			<div class='ipsPad'>
				<div class='galleryNextButton' style='display:none'><a class='input_submit right' href='{parse url="app=gallery&module=images&section=review&do=show&sessionKey={$sessionKey}" base="public"}'>{$this->lang->words['review_next_button']}</a></div>
				<span id='buttonPlaceholder'></span>
				<input type='button' id='add_files_attach_{$sessionKey}' class='input_submit ipsType_small clear' value='{$this->lang->words['upload_ucfirst']}' style='display: none;' tabindex='1' />
				&nbsp;<span class='desc ipsType_smaller' id='space_info_attach_{$sessionKey}'>{parse expression="sprintf( $this->lang->words['upload_used_txt'], '<strong>'.IPSLib::sizeFormat( $stats['used'] ).'</strong>', "<strong>{$stats['maxTotalHuman']}</strong>", '<strong>'.IPSLib::sizeFormat( $stats['maxItem'] ).'</strong>')"}</span> <span class='desc ipsType_smaller'><a href='javascript:void(0);' id='showFileTypes'>{$this->lang->words['upload_types']}</a></span>
				<if test="!IN_ACP AND $this->settings['uploadFormType']">
				<br /><br/><span class='desc lighter ipsType_smaller' id='help_msg'>
					<if test="flashuploadhelp:|:$this->memberData['member_uploader'] == 'flash'">
						 {$this->lang->words['trouble_uploading']} <a href='#' data-switch='default' title='{$this->lang->words['switch']}' tabindex='1'>{$this->lang->words['switch_to_basic']}</a>
					<else />
						<a href='#' data-switch='flash' title='{$this->lang->words['switch']}' tabindex='-1'>{$this->lang->words['switch_to_advanced']}</a>
					</if>
				</span>
				</if>
			</div>
		</div>
	</div>
</div>
<div id='showFileTypesContent' style='display:none'>
	<div class='ipsPad_double'>
		<strong>{$this->lang->words['upload_allowed']}</strong>: {$allowed_file_types}
	</div>
</div>
<script type='text/javascript'>
//<![CDATA[
	// Show the button and info
	$('add_files_attach_{$sessionKey}').show();
	$('space_info_attach_{$sessionKey}').show();
	
	ipb.delegate.register("[data-switch]", function(e, elem){
		ipb.uploader.switchUploadType( elem.readAttribute('data-switch') );
	});
	
	var useType       = 'simple';
	var uploadURL     = ipb.vars['base_url'] + "app=gallery&module=post&section=image&do=upload&type=album&sessionKey={$sessionKey}&album_id={$album['album_id']}&fetch_all=1&s={$this->member->session_id}&_nsc=1";
	var albumTemplate = new Template( "<div id='albumWrap_#{album_id}'><div class='ipsLayout ipsLayout_withleft'><div class='ipsLayout_left'>#{thumb}</div><div class='ipsLayout_content'><br /><h4>{$this->lang->words['selected_album']}: #{album_name}</h4><br /><p class='desc'>#{album_count_imgs} {$this->lang->words['images_lower']}, #{album_count_comments} {$this->lang->words['comments_lower']}<br /><br /><if test="canCreate:|:$this->registry->gallery->helper('albums')->canCreate()"><a href='javascript:void(0);' class='ipsButton_secondary _albumNew'>{$this->lang->words['new_album']}</a>&nbsp;&nbsp;</if><a href='javascript:void(0);' class='ipsButton_secondary' data-album-selector-callback='ipb.gallery.callBackForUploadFormForAlbumSelector' data-album-selector='type=upload&album_id=#{album_id}'>{$this->lang->words['change_album']}</a></p></div></div><br class='clear' /></div>" );
	
	if ( ipb.vars['use_swf_upload'] && ipb.vars['swfupload_enabled'] && jaaulde.utils.flashsniffer.meetsMinVersion( 9 ) )
	{
		useType = 'swf';
		var uploadURL = "{$this->settings['base_url']}app=gallery&module=post&section=image&do=process&sessionKey={$sessionKey}&album_id={$album['album_id']}&s={$this->member->session_id}&_nsc=1";
		
		$('add_files_attach_{$sessionKey}').observe('mouseover', function(e){  } );
	}
	
	ipb.uploader.template = "<li id='ali_[id]' class='attach_row' style='display: none'><div><h4 class='attach_name'>[name]</h4><p class='info'>[info]</p><span class='img_holder'></span><p class='progress_bar'><span style='width: 0%'>0%</span></p><p class='links'> <a href='javascript:void(0);' class='cancel delete' title='{$this->lang->words['attach_delete_title']}'>{$this->lang->words['attach_delete']}</a></p></div></li>"; 
	
	document.observe('dom:loaded', function(){
		/* Load album box */
		ipb.uploader.setCurrentAlbumId( {parse expression="intval($album['album_id'])"} );
		ipb.uploader.buildAlbumBox( {parse expression="intval($album['album_id'])"}, albumTemplate, 'albumWrap' );
		
		/* Load up uploaders */
		ipb.uploader.registerUploader( 'attach_{$sessionKey}', useType, 'attachments', {
			'upload_url': uploadURL,
			'album_id': "{parse expression="intval($album['album_id'])"}",
			'sessionKey': "{$sessionKey}",
			'file_size_limit': "{$stats['maxItem']}"
		} )});
//]]>
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>