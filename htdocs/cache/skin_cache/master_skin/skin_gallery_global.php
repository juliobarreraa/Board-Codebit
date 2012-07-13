<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: bbCodeAlbum
//===========================================================================
function bbCodeAlbum($album) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='_sharedMediaBbcode'>
	<div class='bbcode_mediaWrap clearfix'>
		<a href="{parse url="app=gallery&amp;album={$album['album_id']}" base="public" template="viewalbum" seotitle="{$album['album_name_seo']}"}"><img src='{parse url="app=gallery&amp;module=images&amp;section=img_ctrl&amp;img={$album['album_cover_img_id']}&amp;tn=1" base="public"}' class='sharedmedia_image' /></a>
		<div class='details'>
			<h5><a href="{parse url="app=gallery&amp;album={$album['album_id']}" base="public" template="viewalbum" seotitle="{$album['album_name_seo']}"}">{$this->lang->words['album_ucfirst']}: {$album['album_name']}</a></h5>
			<div>{$album['album_count_imgs']} {$this->lang->words['images_lower']}</div>
			<div>{$album['album_count_comments']} {$this->lang->words['comments_lower']}</div>
		</div>
	</div>
</div>
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: bbCodeImage
//===========================================================================
function bbCodeImage($image="", $album="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div class='_sharedMediaBbcode'>
	<div class='bbcode_mediaWrap clearfix'>
		<if test="ismediathumb:|:$image['media'] == 1">
            <a href="{parse url="app=gallery&amp;image={$image['id']}" base="public" template="viewimage" seotitle="{$image['caption_seo']}"}"><img src='{parse url="app=gallery&amp;module=images&amp;section=img_ctrl&amp;img={$image['id']}&amp;file=media" base="public"}' class='sharedmedia_image' /></a>
        <else />
            <a href="{parse url="app=gallery&amp;image={$image['id']}" base="public" template="viewimage" seotitle="{$image['caption_seo']}"}"><img src='{parse url="app=gallery&amp;module=images&amp;section=img_ctrl&amp;img={$image['id']}&amp;tn=1" base="public"}' class='sharedmedia_image' /></a>
        </if>		<div class='details'>
			<h5><a href="{parse url="app=gallery&amp;image={$image['id']}" base="public" template="viewimage" seotitle="{$image['caption_seo']}"}">{$image['caption']}</a></h5>
			<div><a href="{parse url="app=gallery&amp;album={$image['album_id']}" base="public" template="viewalbum" seotitle="{$image['album_name_seo']}"}">{$this->lang->words['album_ucfirst']}: {$image['album_name']}</a></div>
			<div>{$this->lang->words['uploaded_ucfirst']} {parse date="$image['idate']" format="tiny"}</div>
		</div>
	</div>
</div>
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: galleryCss
//===========================================================================
function galleryCss() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<link rel="stylesheet" type="text/css" media="screen" href="{$this->settings['public_dir']}style_css/{$this->registry->output->skin['_csscacheid']}/ipgallery.css" />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: general_warning
//===========================================================================
function general_warning($data='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<!-- Show inline warning -->
<div class='message unspecific'>
	<strong>{$data['title']}</strong><br />
	{$data['body']}
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: globals
//===========================================================================
function globals($data='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="gallery"}
{parse js_module="rating"}
{parse striping="imagelisting" classes="row1,row2"}
{parse template="include_lightbox" group="global" params=""}
{parse template="include_highlighter" group="global" params="1"}
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: hookRecentGalleryImages
//===========================================================================
function hookRecentGalleryImages($rows) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<style type="text/css">
#appGallLatestHook
{
	overflow:auto;
	height: 117px;
}
	#appGallLatestHook a {
		display: block;
	}
	
	#appGallLatestHook ul li img
	{
		max-width: 100px;
		max-height: 100px;
	}
		
		#appGallLatestHook ul li:last-child { margin-right: 10px; }
</style>
<div id='category_gallrecent' class='category_block block_wrap'>
	<h3 class='maintitle'>
		<a class='toggle right' href='#' title="{$this->lang->words['toggle_ucfirst']}">{$this->lang->words['toggle_ucfirst']}</a> <a href="{parse url="app=gallery" seotitle="false" template="app=gallery" base="public"}">{$this->lang->words['recent_gallery_images']}</a>
	</h3>
	<div id='appGallLatestHook' class='ipsBox table_wrap'>
		<ul class='ipsList_inline ipsList_nowrap'>
		<foreach loop="gallery_images_hook:$rows as $r">
			<li>{parse gallery_resize="$r['thumb']" width="thumb_large"}</li>
		</foreach>
		</ul>
	</div>
</div>
<br />
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
<div class='__like right' data-app="{$data['app']}" data-area="{$data['area']}" data-relid="{$relId}" data-isfave="{$data['iLike']}">
	{parse template="likeSummaryContents" group="gallery_global" params="$data, $relId, $opts"}
</div>
<script type="text/javascript">
	var FAVE_TEMPLATE = new Template( "<h3>{parse expression="sprintf( $this->lang->words['unset_fave_title'], $this->lang->words['like_ucfirst_un' . $data['vernacular'] ])"}</h3><div class='ipsPad'><span class='desc'>{parse expression="sprintf( $this->lang->words['unset_fave_words'], $this->lang->words['like_un' . $data['vernacular'] ])"}</span><br /><p class='ipsForm_center'><input type='button' value='{parse expression="sprintf( $this->lang->words['unset_button'], $this->lang->words['like_ucfirst_un' . $data['vernacular'] ])"}' class='input_submit _funset' /></p></div>");
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
<span class='ipsButton_extra right <if test="$data['totalCount']">_fmore clickable</if>' title='{parse expression="sprintf( $this->lang->words['like_totalcount_' . $data['vernacular'] ], $data['totalCount'] )"}' data-tooltip="{parse expression="sprintf( $this->lang->words['like_totalcount_' . $data['vernacular'] ], $data['totalCount'] )"}"><img src='{$this->settings['img_url']}/icon_users.png' /> <strong>{$data['totalCount']}</strong></span>
<if test="likeOnlyMembers:|:$this->memberData['member_id']">
	<a href='#' title="<if test="$data['iLike']">{parse expression="sprintf( $this->lang->words['fave_tt_on'], $this->lang->words['like_ucfirst_un' . $data['vernacular'] ])"}<else />{parse expression="sprintf( $this->lang->words['fave_tt_off'], $this->lang->words['like_ucfirst_' . $data['vernacular'] ])"}</if>" class='ftoggle ipsButton_secondary'><if test="$data['iLike']">{parse expression="sprintf( $this->lang->words['unset_fave_button'], $this->lang->words['like_ucfirst_un' . $data['vernacular'] ])"}<else />{parse expression="sprintf( $this->lang->words['set_fave_button'], $this->lang->words['like_ucfirst_' . $data['vernacular'] ])"}</if></a>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: profileWrapper
//===========================================================================
function profileWrapper($member,$data='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse template="galleryCss" group="gallery_global" params=""}
<div class='tab_general'>
	<h3 class="bar"><img src='{$this->settings['img_url']}/picture.png' alt='' /> <a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;search_app=gallery&amp;mid={$member['member_id']}&amp;search_app=gallery" base="public"}'>{$this->lang->words['view_all_images']}</a></h3>
	<div class='gallery_row'>
		{$data}
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>