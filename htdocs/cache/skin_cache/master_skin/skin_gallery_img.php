<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: ajaxDetail
//===========================================================================
function ajaxDetail($image, $album="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$image['caption']}</h3>
<div class='ipsBox short'>
	{$image['small']}
	<br /><br />
	<p class='desc'>
		{$this->lang->words['in_ucfirst']} <a href='{parse url="app=gallery&amp;album={$album['album_id']}" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}'>{$album['album_name']}</a>
		<br />{$this->lang->words['by_lower']}: {parse template="userHoverCard" group="global" params="$image"}
	</p>
<div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: mediaEmbedPlayer
//===========================================================================
function mediaEmbedPlayer($file, $playerOptions=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<embed autoplay="true" showcontrols="true" showstatusbar="true" showtracker="true" src="{$file}" width="640" height="480">
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: mediaFlashPlayer
//===========================================================================
function mediaFlashPlayer($file, $playerOptions=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type="text/javascript" src="{$this->settings['public_dir']}flowplayer/example/flowplayer-3.2.6.min.js"></script>
<div id="player" style="display:block;width:640px;height:480px;margin:0 auto"></div>
<script language="JavaScript">
	ipb.gallery.flashPlayerInit( "$file", "{$this->settings['public_dir']}flowplayer/flowplayer-3.2.7.swf" );
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: meta_html
//===========================================================================
function meta_html($content="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse striping="meta" classes="row1,row2"}
<div id='metacontent' style='display: none'>
	<h3>{$this->lang->words['meta_table_header']}</h3>
	<div class='fixed_inner'>
		<table class='ipb_table'>
			<foreach loop="exif_data:$content as $key => $value">
				<tr class='{parse striping="meta"}'>
					<td class='altrow'><strong>{$key}</strong></td>
					<td>{$value}</td>
				</tr>
			</foreach>
		</table>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: moveDialogue
//===========================================================================
function moveDialogue($image, $album) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form action="{parse url="app=gallery&amp;module=images&amp;section=mod&amp;do=move&amp;imageid={$image['id']}&amp;secure_key={$this->member->form_hash}" base="public"}" method="post">
<h3>{$this->lang->words['img_move_title']}</h3>
<div class='row2 pad' style='text-align: center'>
 {$this->lang->words['img_move_to_album']}
 <input type='hidden' id='albumParentId' name='move_to_album_id' value=0 /><div class='albumSelected' id='asDiv'>{$album['_parent']['album_name']}</div>
 <a href='#' class='ipsButton_secondary' data-album-selector-auto-update='{"field": "albumParentId", "div": "asDiv"}' data-album-selector='type=moveImage&moderate=1&album_id={$album['album_id']}'>{$this->lang->words['as_select_album']}</a>
 <br /><br />
 <input type='submit' class="input_submit" value="{$this->lang->words['img_move_submit']}" />
</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: newSticker
//===========================================================================
function newSticker($size=24) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class="img_new_sticker_{$size}"></div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: photostrip
//===========================================================================
function photostrip($album, $images=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>
	/* Always return as UTF-8 */
	array_walk_recursive( $images, array( 'IPSText', 'arrayWalkCallbackConvert' ) );
	$jsonEncoded = json_encode( $images );
	$jsonEncoded = IPSText::convertCharsets($jsonEncoded, "UTF-8", IPS_DOC_CHAR_SET);
</php>
<div id='photostripwrap'>
	<div id='photostrip'>
		<ul id='strip'></ul>
	</div>
</div>
{parse js_module="gallery_photostrip"}
<script type="text/javascript">
	ipb.vars['img_url'] = "{$this->settings['img_url']}";
	
	document.observe("dom:loaded", function(){
		ipb.photostrip.setPhotoData( {$jsonEncoded} );
		ipb.photostrip.display();
	} );
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: show_image
//===========================================================================
function show_image($info=array(), $author=array(), $can_do_avatar=0, $photostrip, $comments, $nextPrev=array(), $follow, $album) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="editor"}
{parse js_module="imagenotes"}
{parse js_module="gallery_lightbox"}
{parse js_module="gallery_albumchooser"}
<link rel="stylesheet" type="text/css" media="screen" href="{$this->settings['public_dir']}js/3rd_party/cropper/cropper.css" />
<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/cropper/cropper.uncompressed.js'></script>
<script type="text/javascript">
//<![CDATA[
	ipb.gallery.inSection = 'image';
	ipb.gallery.imageID = parseInt( {$info['id']} );
	ipb.gallery.isMedia = parseInt( {$info['media']} );
	var curnotes = \$H( {
	<foreach loop="$info['image_notes'] as $k => $v">
		{$k}: { 'top': {$v['top']}, 'left': {$v['left']}, 'height': {$v['height']}, 'width': {$v['width']},
				'content': '{$v['note']}',
				'noteId': '{$v['id']}'
			}<if test="$k != $info['_last_image_note']">,</if>
	</foreach>
						});
								
	var noteTemplate = new Template("<div id='note_#{id}' class='note_wrap rounded ipsPad_half'><div id='note_box_#{id}' class='note_box'><div class='internal'><div id='note_fill_#{id}' class='note_fill'><div id='note_handle_#{id}' class='handle'></div></div></div></div><div id='note_text_#{id}' class='note_text ipsPad_half rounded' style='display: none'>#{text}</div></div>");
	var editTemplate = new Template("<div id='note_form_#{id}' class='note_form rounded ipsPad ipsType_small'><textarea rows='3' cols='20' id='note_content_#{id}'>#{content}</textarea><br /><input type='submit' class='input_submit' value='{$this->lang->words['save']}' id='note_save_#{id}' /> <input type='submit' class='input_submit alt' value='{$this->lang->words['cancel']}' id='note_cancel_#{id}' />&nbsp;&nbsp;<a href='#' class='cancel' id='note_delete_#{id}'>{$this->lang->words['delete']}</a></div>");
//]]>
</script>
<div class='topic_controls right'>
	<if test="hasFollow:|:$follow">
		{$follow}
		<br /><br /><br />
	</if>
	<ul class='topic_buttons'>
		<li><a id='imageOptions' class='ipbmenu' href='#imageOptions'>{$this->lang->words['options_ucfirst']} &nbsp;<img src='{$this->settings['img_url']}/opts_arrow.png' alt='&gt;' /></a></li>
	</ul>
</div>
<a href='{parse url="showuser={$author['member_id']}" seotitle="{$author['members_seo_name']}" template="showuser" base="public"}' class='ipsUserPhotoLink'>
	<img src='{$author['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_medium left' />
</a>
<div class='ipsBox_withphoto'>
	<span class='rating ipsType_smaller'>
		<if test="canRate:|:$this->registry->gallery->helper('rate')->canRate($album) !== false && $this->memberData['member_id'] != $info['member_id']">
			<strong>{$this->lang->words['rate_image']}</strong>&nbsp;&nbsp;
			<foreach loop="voteRatingLoop:array(1,2,3,4,5) as $_rating">
				<a href='{parse url="app=gallery&amp;module=images&amp;section=rate&amp;where=image&amp;id={$info['id']}&amp;rating=$_rating&amp;secure_key={$this->member->form_hash}" base="public"}' id='album_rate_{$_rating}' title='{$this->lang->words[ 'rate_file_'.$_rating ]}'><if test="voteImageRate:|:$info['rating'] >= $_rating">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if></a>
			</foreach>
			<span id='rating_text' class='desc'>
				<if test="filevotes:|:$info['ratings_count'] > 0">{$info['ratings_count']} {$this->lang->words['votes_cnt']}</if>
				<if test="fileyourvote:|:$info['rate']">({$this->lang->words['you_voted']} {$info['rate']})</if>
			</span>
			<script type='text/javascript'>
				rating = new ipb.rating( 'album_rate_', { 
									url: 		'{parse url="app=gallery&amp;module=ajax&amp;section=rate&amp;id={$info['id']}&where=image" base="public"}&md5check=' + ipb.vars['secure_hash'],
									cur_rating: {parse expression="intval($info['rating'])"},
									rated: 		<if test="jsRated:|:$info['ratings_count']">1<else />0</if>,
									allow_rate: 1
								  } );
			</script>
		<else />
			<foreach loop="viewRatingLoop:array(1,2,3,4,5) as $_rating">
				<if test="viewImageRate:|:$info['rating'] >= $_rating">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if>
			</foreach>
		</if>
	</span>
	
	<h1 class='ipsType_pagetitle'>{$info['caption']}</h1>
	<div class='desc lighter blend_links'>
		 {$this->lang->words['viewimg_uploaded_by']} {parse template="userHoverCard" group="global" params="$author"}, {parse date="$info['idate']" format="SHORT"}
	</div>
	<if test="hasTags:|:is_array($info['tags'])">
		{$info['tags']['formatted']['parsedWithoutComma']}
	</if>
</div>
<ul class="ipbmenu_content" id='imageOptions_menucontent' style='display:none' >
	<if test="showSizesLink:|:!$info['media']">
		<li><a href='{parse url="app=gallery&amp;image={$info['id']}&amp;size=medium" seotitle="{$info['caption_seo']}" template="viewsizes" base="public"}'>{$this->lang->words['view_all_sizes']}</a></li>
	</if>
	<if test="metahtml:|:$info['metahtml'] != ''">
		<li><a href='#metaInfo' data-clicklaunch="showMeta" data-scope="gallery" title='{$this->lang->words['view_exif_prop']}'>{$this->lang->words['view_exif_prop']}</a></li>
	</if>
	<if test="setascover:|:$info['set_as_cover']">
		<li id='menu_set_cover'><a data-clicklaunch="imageSetAsCover" data-scope="gallery" href='{parse url="app=gallery&amp;module=images&amp;section=mod&amp;imageId={$info['id']}&amp;do=setAsCover" base="public"}' title='{$this->lang->words['cover_set_title']}'> {$this->lang->words['set_as_cover']}</a></li>
	</if>
	<if test="makeProfilePhoto:|:$info['can_set_as_profile_photo'] && $info['image']">
		<li><a href='#' id='profileTrigger'>{$this->lang->words['set_as_photo']}</a></li>
	</if>
	<li><a href='#shareLinks' data-clicklaunch="showShareLinks" data-scope="gallery">{$this->lang->words['gallery_share_links']}</a></li>
</ul>

<br class='clear' />
<div class='ipsLayout ipsLayout_withright ipsLayout_hugeright'>
	<div class='ipsLayout_right'>
		<div class='ipsBox'>
			<div class='ipsBox_container ipsPad'>
				<ul class='image_info ipsType_small'>
				<li><strong>{$this->lang->words['viewimg_owner']}:</strong> {parse template="userHoverCard" group="global" params="$author"}<if test="notGuest:|:$info['member_id']"> (<a href="{parse url="app=gallery&amp;user={$author['member_id']}" seotitle="{$author['members_seo_name']}" template="useralbum" base="public"}">{$this->lang->words['view_all_albums']}</a>)</if></li>
					<li><strong>{$this->lang->words['uploaded_ucfirst']}:</strong> {parse date="$info['idate']" format="short"}</li>
					<if test="$info['_camera_model']">
						<li><strong>{$this->lang->words['camera_ucfirst']}:</strong> {$info['_camera_model']}</li>
					</if>
					<if test="$info['_date_taken']">
						<li><strong>{$this->lang->words['taken_ucfirst']}:</strong> {parse date="$info['_date_taken']" format="short"}</li>
					</if>
					<li><strong>{$this->lang->words['views']}</strong> {$info['views']}</li>
					<if test="imageHasNotes:|:count($info['image_notes'])">
						<li><strong>{$this->lang->words['note_count']}</strong> {parse format_number="count($info['image_notes'])"}</li>
					</if>
					<li><strong>{$this->lang->words['viewimg_album']}</strong> <a href="{parse url="app=gallery&amp;album={$album['album_id']}" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}">{$album['album_name']}</a></li>
				</ul>
				{$photostrip}
				<if test="hascopyright:|:$info['copyright'] != ''">
					<div class='ipsBox ipsType_small'>
						<strong>{$this->lang->words['user_copyright_name']}</strong>
						<p class='desc'>{$info['copyright']}</p>
					</div>
				</if>
			</div>
			<if test="$info['_latLon']">
				<br />
				<div class='ipsBox_container ipsPad'>
					<if test="gpsOn:|:$info['image_gps_show']">
						<h4 id='map_on_header' class='__mapon'>Location
						<if test="geoData:|:$info['_locShort']">
							<span class='desc' style='font-weight:normal'>
								&nbsp; {$info['_locShort']}
							</span>
						</if>
						</h4>
					<else />
					<if test="isMe:|:$this->memberData['member_id'] == $info['member_id']">
						<h4 id='map_off_header'>Location
							<span class='desc __mapoff' style='font-weight:normal'>&nbsp;({$this->lang->words['private_ucfirst']})</span>
						</h4>
						<div class='desc __mapoff'>
							{$this->lang->words['photo_taken_at']}: {$info['_locShort']}.
							<br /><br /><a href='#addMap' data-clicklaunch="addMap" data-scope="gallery">{$this->lang->words['img_make_loc_public']}</a>
						</div>
					</if>
					</if>
					<div id="map" class='__mapon ipsPad_half' style="margin:0 auto; width: 300px; display: none">
						<a href="{$info['_mapUrl']}" rel="newwindow"><img id='map_0' src="{$info['_maps'][0]}" alt="map" /></a>
						<img style='display:none' id='map_1' src="{$info['_maps'][1]}" alt="map" />
						<if test="isStillMe:|:$this->memberData['member_id'] == $info['member_id']"><div class='desc' style='text-align:right;padding-top:3px'><a href='#removeMap' data-clicklaunch="removeMap" data-scope="gallery">{$this->lang->words['img_remove_map']}</a></div></if>
					</div>
				</div>
			</if>
		</div>
		<if test="hasShareReportLinks:|:$this->settings['sl_enable']">
			<div class='ipsPad'>
				{IPSLib::shareLinks( $info['caption'], array( 'skip' => array( 'print', 'download' ) ) )}
			</div>
		</if>
	</div>
	<div class='ipsLayout_content' id='image_content'>
		<div class='ipsBox'>
			<div class='ipsBox_container ipsPad short'>
				<div id='theImage'>
					<ul id='image_nav_links' class='ipsPad'>
						<if test="onOffPrev:|:$nextPrev['prev'] !== null">
							<li><a class='ipsButton_secondary' href='{parse url="app=gallery&amp;image={$nextPrev['prev']['id']}" base="public" template="viewimage" seotitle="{$nextPrev['prev']['caption_seo']}"}'>&larr; {$this->lang->words['previous_ucfirst']}</a></li>
						<else />
							<li class="off">&larr; {$this->lang->words['previous_ucfirst']}</li>
						</if>
						<if test="onOffNext:|:$nextPrev['next'] !== null">
							<li><a class='ipsButton_secondary' href='{parse url="app=gallery&amp;image={$nextPrev['next']['id']}" base="public" template="viewimage" seotitle="{$nextPrev['next']['caption_seo']}"}'>{$this->lang->words['next_ucfirst']} &rarr;</a></li>
						<else />
							<li class="off">{$this->lang->words['next_ucfirst']} &rarr;</li>
						</if>
					</ul>
					<if test="unApproved:|: ! $info['approved']">
						<div class='message error'>{parse expression="sprintf($this->lang->words['gallery_unapproved_image_view'], $this->registry->output->buildUrl("app=gallery&amp;module=images&amp;section=mod&amp;do=approveToggle&amp;val=1&amp;imageid={$info['id']}&amp;auth_key={$this->member->form_hash}", 'public') )"}</div>
						<br />
					</if>
					<if test="isMedia:|:$info['media']">
						{$info['movie']}
					<else />
						{$info['image']}
					</if>	
				</div>
				
				<if test="hasDescription:|:$info['description']">
					<p class='ipsPad'>
						{$info['description']}
					</p>
				</if>
				<br />
				<if test="hasOptions:|:$info['mod_buttons'] || $info['image_control_mod'] || $info['_canReport']">
					<ul class='ipsList_inline left'>
						<if test="showImageOptions:|:$info['image_control_mod']">
							<li id='add_note'><a href='#' title='{$this->lang->words['add_note']}' class='ipsButton_secondary'><img src="{$this->settings['img_url']}/gallery/add_note.png"></a></li>
							<li><a href='{parse url="app=gallery&amp;module=images&amp;section=mod&amp;imageId={$info['id']}&amp;do=rotate&amp;dir=left" base="public"}' id='rotate_left' title='{$this->lang->words['rotate_left']}' class='ipsButton_secondary'><img src="{$this->settings['img_url']}/gallery/rotate_left.png"></a></li>
							<li><a href='{parse url="app=gallery&amp;module=images&amp;section=mod&amp;imageId={$info['id']}&amp;do=rotate&amp;dir=right" base="public"}' id='rotate_right' title='{$this->lang->words['rotate_right']}' class='ipsButton_secondary'><img src="{$this->settings['img_url']}/gallery/rotate_right.png"></a></li>
						</if>
						<if test="showImageModerationOptions:|:$info['mod_buttons']">
							<li><a href='#modOptions' id='modOptions' class='ipsButton_secondary ipbmenu'>{$this->lang->words['image_moderation']}</a></li>
							<ul class='ipbmenu_content' id='modOptions_menucontent' style='display:none'>
								<if test="approve_button:|:$info['approve_button']">
									<li>
										<if test="is_approved:|:$info['approved']">
											<a href='{parse url="app=gallery&amp;module=images&amp;section=mod&amp;do=approveToggle&amp;val=0&amp;imageid={$info['id']}&amp;auth_key={$this->member->form_hash}" base="public"}'>{$this->lang->words['unapprove_image']}</a>
										<else />
											<a href='{parse url="app=gallery&amp;module=images&amp;section=mod&amp;do=approveToggle&amp;val=1&amp;imageid={$info['id']}&amp;auth_key={$this->member->form_hash}" base="public"}'>{$this->lang->words['approve_image']}</a>
										</if>
									</li>
								</if>
								<if test="edit_button:|:$info['edit_button']">
									<li><a href='{parse url="app=gallery&amp;module=post&amp;section=image&amp;do=editImage&amp;img={$info['id']}" base="public"}'>{$this->lang->words['edit_post']}</a></li>
								</if>
								<if test="move_button:|:$info['move_button']">
									<li><a data-clicklaunch="imageMoveDialogue" data-scope="gallery" href="{parse url="app=gallery&amp;module=images&amp;section=mod&amp;do=move&amp;imageid={$info['id']}" base="public"}">{$this->lang->words['submit_move']}</a></li>
								</if>
								<if test="delete_button:|:$info['delete_button']">
									<li><a data-clicklaunch="imageDeleteDialogue" data-scope="gallery" href="{parse url="app=gallery&amp;module=images&amp;section=mod&amp;do=delete&amp;imageid={$info['id']}&amp;secure_key={$this->member->form_hash}" base="public"}">{$this->lang->words['delete_image']}</a></li>
								</if>
							</ul>
						</if>
						<if test="canReportImage:|:$info['_canReport']">
							<li><a class='ipsButton_secondary' href='{parse url="app=core&amp;module=reports&amp;rcom=gallery&amp;imageId={$info['id']}&amp;ctyp=image" base="public"}'>{$this->lang->words['report_image']}</a></li>
						</if>
					</ul>
				</if>
				<!-- Reputation: -->
				<div class='clearfix'>
				{parse template="repButtons" group="global_other" params="$info, array_merge( array( 'primaryId' => $info['id'], 'domLikeStripId' => 'like_post_' . $info['id'], 'domCountId' => 'rep_post_' . $info['id'], 'app' => 'gallery', 'type' => 'id', 'likeFormatted' => $info['like']['formatted'] ), $info )"}
				</div>
			</div>
		</div>
		
		<if test="hasComments:|:$comments">
			{$comments}
		</if>
	</div>
</div>
<br class='clear' />
{$info['metahtml']}
<div id='share_links_content' style='display: none'>
	<h3>{$this->lang->words['gallery_share_links']}</h3>
		<table class='ipb_table'>
			<if test="isNotMedia:|:!$info['media']">
				<tr class='{parse striping="shareLinks"}'>
					<td class='altrow'><strong>{$this->lang->words['gal_bbcode_pre']}</strong></td>
					<td><input onclick="this.select();" type='text' size='50' readonly='readonly' name='bbcode1' value='[URL={parse url="app=gallery&amp;image={$info['id']}" base="public" template="viewimage" seotitle="{$info['caption_seo']}"}][IMG]{$info['image_url']}[/IMG][/URL]' class='input_text' /></td>
				</tr>
				<tr class='{parse striping="shareLinks"}'>
					<td class='altrow'><strong>{$this->lang->words['gal_html_pre']}</strong></td>
					<td><input onclick="this.select();" type='text' size='50' readonly='readonly' name='html1' value='&lt;a href=&#39;{parse url="app=gallery&amp;image={$info['id']}" base="public" template="viewimage" seotitle="{$info['caption_seo']}"}&#39;&gt;&lt;img src=&#39;{$info['image_url']}&#39; alt=&#39;<if test="set_cleancaption:|:$info['clean_caption'] = htmlspecialchars( $info['caption'], ENT_QUOTES )">{$info['clean_caption']}</if>&#39; /&gt;&lt;/a&gt;' class='input_text'/></td>
				</tr>
				<tr class='{parse striping="shareLinks"}'>
					<td class='altrow'><strong>{$this->lang->words['gal_imgurl_pre']}</strong></td>
					<td><input onclick="this.select();" type='text' size='50' readonly='readonly' name='link1' value='{$info['image_url']}' class='input_text' /></td>
				</tr>
			</if>
			<tr class='{parse striping="shareLinks"}'>
				<td class='altrow'><strong>{$this->lang->words['gal_pgurl_pre']}</strong></td>
				<td><input onclick="this.select();" type='text' size='50' readonly='readonly' name='link2' value='{parse url="app=gallery&amp;image={$info['id']}" base="public" template="viewimage" seotitle="{$info['caption_seo']}"}' class='input_text' /></td>
			</tr>
		</table>
</div>
<div id='template_sizes' style='display:none'>
	<div class='ipsBox short'>
		{$this->lang->words['view_sizes']}: <a href='{parse url="app=gallery&amp;image={$info['id']}&amp;size=square" seotitle="{$info['caption_seo']}" template="viewsizes" base="public"}'>{$this->lang->words['square_ucfirst']}</a> &middot;
		<a href='{parse url="app=gallery&amp;image={$info['id']}&amp;size=small" seotitle="{$info['caption_seo']}" template="viewsizes" base="public"}'>{$this->lang->words['small_ucfirst']}</a> &middot;
		<a href='{parse url="app=gallery&amp;image={$info['id']}&amp;size=medium" seotitle="{$info['caption_seo']}" template="viewsizes" base="public"}'>{$this->lang->words['medium_ucfirst']}</a> &middot;
		<a href='{parse url="app=gallery&amp;image={$info['id']}&amp;size=large" seotitle="{$info['caption_seo']}" template="viewsizes" base="public"}'>{$this->lang->words['large_ucfirst']}</a>
		<br />
		<p class='ipsPad_half'>
			<a href='{$info['image_url']}' class='desc'>{$this->lang->words['img_rc_direct']}</a>
		</p>
	</div>
</div>
<div id='template_delete' style='display:none'>
	<form action="{parse url="app=gallery&amp;module=images&amp;section=mod&amp;do=delete&amp;imageid={$info['id']}&amp;secure_key={$this->member->form_hash}" base="public"}" method="post">
		<h3>{$this->lang->words['mod_img_del_title']}</h3>
		<div class='ipsBox short'>
			{$this->lang->words['mod_img_del_desc']}
			<br /><br />
			<input type='submit' class='input_submit' value="{$this->lang->words['mod_img_del_go']}" />
		</div>
	</form>
</div>
<div id='template_photo' style='display:none'>
	<h3>{$this->lang->words['set_as_photo']}</h3>
	<div class='ipsBox short'>
		<div id='ipsPad'>{parse expression="str_replace( 'image_view_', 'photo_view_', $info['image'])"}</div>
		<br />
		<div class='ipsPad'>
			<span id='setAsPhoto_accept' class='input_submit'>{$this->lang->words['save_ucfirst']}</span>&nbsp;&nbsp;<span id='setAsPhoto_cancel' class='input_submit alt'>{$this->lang->words['cancel_ucfirst']}</span>
		</div>
	</div>
</div>
<script type='text/javascript'>
	document.observe("dom:loaded", function()
	{
		var notes = new ipb.imagenotes( $('theImage').down('img'), curnotes, { editable: <if test="$info['image_control_mod']">true<else />false</if>, add_note: $('add_note') } );
	} );
	
	<if test="$info['image_gps_show']">
		ipb.gallery.latLon = "{$info['_latLon']}";
	</if>
</script>
<style type="text/css">
 @import url("{$this->settings['public_dir']}style_css/{$this->registry->output->skin['_csscacheid']}/ipgallery_lightbox.css");
</style>
<div id="ips_lightbox" style='display:none' setup="false" available="true" dimensions="{$info['_data']['sizes']['full'][0]}-{$info['_data']['sizes']['full'][1]}" caption="{$info['caption']}" fullimage="{$info['image_url-full']}"></div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: sizes
//===========================================================================
function sizes($image, $album="", $size="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='gallery_wrap'>
	<h2 class="gallery_title">{$image['caption']}</h2>
	<div id="photo_sizes">
		<ul class='gallery_buttons'>
			<li>
				<a <if test="$size == 'square'">class="on"</if> href='{parse url="app=gallery&amp;image={$image['id']}&amp;size=square" seotitle="{$image['caption_seo']}" template="viewsizes" base="public"}'>{$this->lang->words['square_ucfirst']}</a>
				<span>({$image['_data']['sizes']['thumb'][0]} x {$image['_data']['sizes']['thumb'][1]})</span>
			</li>
	 		<li>
	 			<a <if test="$size == 'small'">class="on"</if> href='{parse url="app=gallery&amp;image={$image['id']}&amp;size=small" seotitle="{$image['caption_seo']}" template="viewsizes" base="public"}'>{$this->lang->words['small_ucfirst']}</a>
	 			<span>({$image['_data']['sizes']['small'][0]} x {$image['_data']['sizes']['small'][1]})</span>
	 		</li>
	 		<li>
	 			<a <if test="$size == 'medium'">class="on"</if> href='{parse url="app=gallery&amp;image={$image['id']}&amp;size=medium" seotitle="{$image['caption_seo']}" template="viewsizes" base="public"}'>{$this->lang->words['medium_ucfirst']}</a>
	 			<span>({$image['_data']['sizes']['medium'][0]} x {$image['_data']['sizes']['medium'][1]})</span>
	 		</li>
	 		<li>
	 			<a <if test="$size == 'large'">class="on"</if> href='{parse url="app=gallery&amp;image={$image['id']}&amp;size=large" seotitle="{$image['caption_seo']}" template="viewsizes" base="public"}'>{$this->lang->words['large_ucfirst']}</a>
	 			<span>({$image['_data']['sizes']['max'][0]} x {$image['_data']['sizes']['max'][1]})</span>
	 		</li>
	 	</ul>
	 	<p>
	 		{$image['tag']}
	 	</p>
	 </div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>