<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: galleryAdvancedSearchFilters
//===========================================================================
function galleryAdvancedSearchFilters($options) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<fieldset class='{parse striping="search"}'>
	<span class='search_msg'>
		{$this->lang->words['s_gallery_desc']}
	</span>
	<ul class='ipsForm_horizontal'>
		<if test="$options">
			<li class='ipsField ipsField_select clear'>
				<label class='ipsField_title' for='album_filter'>{$this->lang->words['find_in_category']}</label>
				<p class='ipsField_content'>
					<select name='search_app_filters[gallery][albumids][]' class='input input_select' size='6' multiple='multiple'>
						{$options}
					</select>
				</p>
			</li>
		</if>
		<li class='ipsField ipsField_checkbox clear'>
			<input type='checkbox' id='exclude_albums' name='search_app_filters[gallery][excludeAlbums]' value='1' class='input_check' />
			<label for='exclude_albums'>{$this->lang->words['find_in_albums']}</label>
		</li>
	</ul>
</fieldset>
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: galleryAlbumSearchResult
//===========================================================================
function galleryAlbumSearchResult($data, $resultAsTitle=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<tr class='{parse striping="searchResults"}'>
	<td class='col_c_icon short altrow'>
		{parse gallery_resize="$data['thumb']" width="thumb_medium"}
	</td>
	<td>
		<span class='right'>
			<foreach loop="ratingLoop:array(1,2,3,4,5) as $_rating">
				<if test="albumrate:|:$data['album_rating_aggregate'] >= $_rating">{parse replacement="mini_rate_on"}<else />{parse replacement="mini_rate_off"}</if>
			</foreach>
		</span>
		
		<h4>
			<if test="albumIsGlobal:|:$this->registry->gallery->helper('albums')->isGlobal($data)">
				<span class='ipsBadge ipsBadge_grey reset_cursor'>{$this->lang->words['global_album']}</span>
			</if>
			<a href='{parse url="app=gallery&amp;album={$data['album_id']}" base="public" template="viewalbum" seotitle="{$data['album_name_seo']}"}'>{IPSText::truncate( $data['album_name'], 150)}</a>
		</h4>
		<p class='desc lighter blend_links toggle_notify_off'>
			<if test="hasDescription:|:$data['album_description']">{IPSText::truncate( strip_tags( IPSText::getTextClass('bbcode')->stripAllTags( $data['album_description'] ), '<br />' ), 230 )}<else />&nbsp;</if>
		</p>
		
		<if test="isFollowedStuff:|:count($data['_followData'])">
			{parse template="followData" group="search" params="$data['_followData']"}
		</if>
		
		<if test="hasLittleWeeBabbies:|:$data['_childrenCount']">
			<br class='clear' /><span class='right'><a href="{parse url="app=gallery&amp;browseAlbum={$data['album_id']}" base="public" template="browsealbum" seotitle="{$data['album_name_seo']}"}"><span class='ipsBadge ipsBadge_lightgrey reset_cursor'>{parse expression="sprintf( $this->lang->words['view_child_albums'], $data['_childrenCount'] )"}</span></a></span>
		</if>
	</td>
	<td class='col_f_views'>
		<ul>
			<li>{parse format_number="$data['_totalImages']"} {$this->lang->words['images_lower']}</li>
			<li class='views desc'>{parse format_number="$data['_totalComments']"} {$this->lang->words['comments_lower']}</li>
		</ul>
	</td>
	<td class='col_f_post'>
		<if test="albumIsMember:|:!$this->registry->gallery->helper('albums')->isGlobal($data)">
			<a href='{parse url="showuser={$data['member_id']}" seotitle="{$data['members_seo_name']}" template="showuser" base="public"}' class='ipsUserPhotoLink left'>
				<img src='{$data['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' />
			</a>
			<ul class='last_post ipsType_small'>
				<li>{parse template="userHoverCard" group="global" params="$data"}</li>
				<li>{parse date="$data['album_last_img_date']" format="DATE"}</li>
			</ul>
		</if>
	</td>
	<if test="isFollowedStuff:|:count($data['_followData'])">
		<td class='col_f_mod'>
			<input class='input_check checkall toggle_notify_on' type="checkbox" name="likes[]" value="{$data['_followData']['like_app']}-{$data['_followData']['like_area']}-{$data['_followData']['like_rel_id']}" />
		</td>
	</if>
</tr>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: galleryCommentSearchResult
//===========================================================================
function galleryCommentSearchResult($data, $resultAsTitle=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<tr class='{parse striping="searchResults"}'>
	<td class='col_f_icon short altrow'>
		{parse gallery_resize="$data['thumb']" width="thumb_medium"}
	</td>
	<td>
		<span class='right'>
			<foreach loop="ratingLoop:array(1,2,3,4,5) as $_rating">
				<if test="imagerate:|:$data['rating'] >= $_rating">{parse replacement="mini_rate_on"}<else />{parse replacement="mini_rate_off"}</if>
			</foreach>
		</span>
		
		<h4><a href='{parse url="app=gallery&amp;image={$data['id']}" base="public" template="viewimage" seotitle="{$data['caption_seo']}"}' title='{$data['caption']}'>{IPSText::truncate( $data['caption'], 100)}</a></h4>
		<span class='desc blend_links'>
			<span class='desc lighter'>{$this->lang->words['in_lower']}</span> <a href='{parse url="app=gallery&amp;album={$data['album_id']}" base="public" template="viewalbum" seotitle="{$data['album_name_seo']}"}' title='{$data['album_name']}'>{IPSText::truncate( $data['album_name'], 75)}</a>
		</span>
		<p class='desc lighter blend_links'>{$data['content']}</p>
	</td>
	<td class='col_f_views'>
		<ul>
			<li>{parse format_number="$data['comments']"} {$this->lang->words['comments_lower']}</li>
			<li class='views desc'>{parse format_number="$data['views']"} {$this->lang->words['views_lower']}</li>
		</ul>
	</td>
	<td class='col_f_post'>
		<a href='#' class='ipsUserPhotoLink left'>
			<img src='{$data['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' />
		</a>
		<ul class='last_post ipsType_small'>
			<li>{parse template="userHoverCard" group="global" params="$data"}</li>
			<li>{parse date="$data['post_date']" format="short"}</li>
		</ul>
	</td>
</tr>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: galleryImageSearchResult
//===========================================================================
function galleryImageSearchResult($data, $resultAsTitle=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<tr class='{parse striping="searchResults"}<if test="imageIsUnapproved:|:!$data['approved']"> moderated</if>'>
	<td class='col_c_icon short altrow'>
		{parse gallery_resize="$data['thumb']" width="thumb_medium"}
	</td>
	<td>
		<span class='right'>
			<foreach loop="ratingLoop:array(1,2,3,4,5) as $_rating">
				<if test="imagerate:|:$data['rating'] >= $_rating">{parse replacement="mini_rate_on"}<else />{parse replacement="mini_rate_off"}</if>
			</foreach>
		</span>
		
		<h4><a href='{parse url="app=gallery&amp;image={$data['id']}" base="public" template="viewimage" seotitle="{$data['caption_seo']}"}' title='{$data['caption']}'>{IPSText::truncate( $data['caption'], 100)}</a></h4>
		<span class='desc blend_links'>
			<span class='desc lighter'>{$this->lang->words['in_lower']}</span> <a href='{parse url="app=gallery&amp;album={$data['album_id']}" base="public" template="viewalbum" seotitle="{$data['album_name_seo']}"}' title='{$data['album_name']}'>{IPSText::truncate( $data['album_name'], 75)}</a>
		</span>
		<p class='desc lighter blend_links toggle_notify_off'>
			<if test="hasDescription:|:$data['description']">{IPSText::truncate( strip_tags( IPSText::getTextClass('bbcode')->stripAllTags( $data['description'] ), '<br />' ), 230 )}<else />&nbsp;</if>
		</p>
		<if test="isFollowedStuff:|:count($data['_followData'])">
			{parse template="followData" group="search" params="$data['_followData']"}
		</if>
		<if test="hasTags:|:count($data['tags']['formatted'])">
			<img src='{$this->settings['img_url']}/icon_tag.png' /> {$data['tags']['formatted']['truncatedWithLinks']}
		</if>
		
	</td>
	<td class='col_f_views'>
		<ul>
			<li>{parse format_number="$data['comments']"} {$this->lang->words['comments_lower']}</li>
			<li class='views desc'>{parse format_number="$data['views']"} {$this->lang->words['views_lower']}</li>
		</ul>
	</td>
	<td class='col_f_post'>
		<a href='{parse url="showuser={$data['member_id']}" seotitle="{$data['members_seo_name']}" template="showuser" base="public"}' class='ipsUserPhotoLink left'>
			<img src='{$data['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' />
		</a>
		<ul class='last_post ipsType_small'>
			<li>{parse template="userHoverCard" group="global" params="$data"}</li>
			<li>{parse date="$data['idate']" format="DATE"}</li>
		</ul>
	</td>
	<if test="isFollowedStuff:|:count($data['_followData'])">
		<td class='col_f_mod'>
			<input class='input_check checkall toggle_notify_on' type="checkbox" name="likes[]" value="{$data['_followData']['like_app']}-{$data['_followData']['like_area']}-{$data['_followData']['like_rel_id']}" />
		</td>
	</if>
</tr>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: profileBlock
//===========================================================================
function profileBlock($member, $albums=array(), $images=array(), $hasMore=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse template="galleryCss" group="gallery_global" params=""}
{parse striping="albumData" classes="row1,row2"}
<if test="hasAnything:|: ! count( $albums ) && ! count( $images )">
	<div class='no_messages'>{$this->lang->words['gallery_prof_tabs_aint_none']}</div>
</if>
<if test="hasAlbums:|:count($albums)">
	<h3 class='maintitle clearfix'>
		<if test="hasMoreAlbums:|:$hasMore">
			<span class='right ipsType_small'>
				<a href="{parse url="app=gallery&amp;user={$member['member_id']}" seotitle="{$member['members_seo_name']}" template="useralbum" base="public"}">{$this->lang->words['view_all_albums']}</a>
			</span>
			{parse expression="sprintf( $this->lang->words['last_x_albums'], count($albums) )"}
		<else />
			{$this->lang->words['advsearch_albums']}
		</if>
	</h3>
	<div class='ipsBox'>
		<div class='ipsBox_container'>
			<table class='ipb_table'>
				<foreach loop="albumsLoop:$albums as $id => $data">
					{parse template="galleryAlbumSearchResult" group="gallery_user" params="$data"}
				</foreach>
			</table>
		</div>
	</div>
</if>
<br />
<if test="hasImages:|:count($images)">
<h3 class='maintitle clearfix'>{$this->lang->words['advsearch_images']}</h3>
<div class='ipsBox'>
	<div class='ipsBox_container'>
		<table class='ipb_table'>
			<foreach loop="imagesLoop:$images as $id => $data">
				{parse template="galleryImageSearchResult" group="gallery_user" params="$data"}
			</foreach>
		</table>
	</div>
</div>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: searchResultsAsGallery
//===========================================================================
function searchResultsAsGallery($results, $titlesOnly=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse template="galleryCss" group="gallery_global" params=""}
<if test="hasResults:|:count($results)">
	<table class='ipb_table'>
		<foreach loop="results:$results as $result">
			{$result['html']}
		</foreach>
	</table>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: unapprovedComments
//===========================================================================
function unapprovedComments($comments) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse template="subTabLoop" group="modcp" params=""}
<div class='clearfix'>
	<if test="hasPosts:|:is_array( $comments ) AND count( $comments )">
		<div id='ips_Posts2'>
			<foreach loop="post_data:$comments as $comment">
				<div class='post_block hentry clear no_sidebar'>
					<div class='post_wrap'>
						<if test="postMid:|:$comment['member_id']">
						<h3 class='row2'>
						<else />
						<h3 class='guest row2'>
						</if>
							<img src='{$comment['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_tiny' />&nbsp;
							<if test="postMember:|:$comment['member_id']">
								<span class="author vcard">{parse template="userHoverCard" group="global" params="$comment"}</span>
							<else />
								{$comment['members_display_name']}
							</if>
						</h3>
						
						<div class='post_body'>
							<ul class='ipsList_inline modcp_post_controls'>
								<li class='post_edit'>
									<a href='{parse url="app=core&module=global&section=comments&do=showEdit&comment_id={$comment['pid']}&parentId={$comment['img_id']}&fromApp=gallery-images&modcp=gallerycomments" base="public"}' title='{$this->lang->words['post_edit_title']}' class='ipsButton_secondary ipsType_smaller' id='edit_post_{$comment['pid']}'>{$this->lang->words['post_edit']}</a>
								</li>
								<li>
									<a href='{parse url="app=core&amp;module=global&amp;section=comments&amp;do=approve&amp;comment_id={$comment['pid']}&amp;parentId={$comment['img_id']}&amp;fromApp=gallery-images&amp;modcp=gallerycomments&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['post_toggle_visible']}' class='ipsButton_secondary ipsType_smaller'>{$this->lang->words['post_approve']}</span></a>
								</li>
								<li class='post_del'>
									<a href='{parse url="app=core&amp;module=global&amp;section=comments&amp;do=delete&amp;comment_id={$comment['pid']}&amp;parentId={$comment['img_id']}&amp;fromApp=gallery-images&amp;modcp=gallerycomments&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['post_delete_title']}' class='delete_post ipsButton_secondary important ipsType_smaller'>{$this->lang->words['post_delete']}</a>
								</li>
								<li class='desc'>
									<strong>{$this->lang->words['posted']}:</strong> <a rel='bookmark' class='desc lighter ipsType_smaller' href='{parse url="app=core&amp;module=global&amp;section=comments&amp;do=findComment&amp;comment_id={$comment['id']}&amp;parentId={$comment['img_id']}&amp;fromApp=gallery-images" base="public"}' title='{$this->lang->words['comment_permalink']}'>{parse date="$comment['post_date']" format="short"}</a>
								</li>
								<li class='desc'>
									<strong>{$this->lang->words['modcp_gal_caption']}:</strong> <a class='desc lighter ipsType_smaller' href='{parse url="app=gallery&amp;image={$comment['img_id']}" seotitle="{$comment['caption_seo']}" template="viewimage" base="public"}'>{$comment['album_name']} &rarr; {IPSText::truncate( $comment['caption'], 35 )}</a>
								</li>
							</ul>
							<div class='post entry-content'>
								{$comment['comment']}
							</div>
							<br />
						</div>
					</div>
				</div>
			</foreach>
		</div>
	<else />
		<div class='no_messages'>
			{$this->lang->words['no_unapproved_posts']}
		</div>
	</if>
	<div>{$pagelinks}</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: unapprovedImages
//===========================================================================
function unapprovedImages($images) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse template="subTabLoop" group="modcp" params=""}
<div class='clearfix'>
	<if test="hasPosts:|:is_array( $images ) AND count( $images )">
		<div id='ips_Posts2'>
			<foreach loop="post_data:$images as $image">
				<div class='post_block hentry clear no_sidebar'>
					<div class='post_wrap'>
						<if test="postMid:|:$image['member_id']">
						<h3 class='row2'>
						<else />
						<h3 class='guest row2'>
						</if>
							<img src='{$image['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_tiny' />&nbsp;
							<if test="postMember:|:$comment['member_id']">
								<span class="author vcard">{parse template="userHoverCard" group="global" params="$image"}</span>
							<else />
								{$image['members_display_name']}
							</if>
						</h3>
						
						<div class='post_body'>
							<ul class='ipsList_inline modcp_post_controls'>
								<li>
									<a href='{parse url="app=gallery&amp;module=images&amp;section=mod&amp;do=approveToggle&amp;val=1&amp;imageid={$image['id']}&amp;modcp=galleryimages&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['post_toggle_visible']}' class='ipsButton_secondary ipsType_smaller'>{$this->lang->words['post_approve']}</span></a>
								</li>
								<li class='post_del'>
									<a href='{parse url="app=gallery&amp;module=images&amp;section=mod&amp;do=delete&amp;imageid={$image['id']}&amp;modcp=galleryimages&amp;auth_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['post_delete_title']}' class='delete_img ipsButton_secondary important ipsType_smaller'>{$this->lang->words['post_delete']}</a>
								</li>
								<li class='desc'>
									<strong>{$this->lang->words['posted']}:</strong> <a rel='bookmark' class='desc lighter ipsType_smaller' href='{parse url="app=core&amp;module=global&amp;section=comments&amp;do=findComment&amp;comment_id={$comment['id']}&amp;parentId={$comment['img_id']}&amp;fromApp=gallery-images" base="public"}' title='{$this->lang->words['comment_permalink']}'>{parse date="$image['idate']" format="short"}</a>
								</li>
								<li class='desc'>
									<strong>{$this->lang->words['modcp_gal_caption']}:</strong> <a class='desc lighter ipsType_smaller' href='{parse url="app=gallery&amp;image={$image['id']}" seotitle="{$image['caption_seo']}" template="viewimage" base="public"}'>{$image['album_name']} &rarr; {IPSText::truncate( $image['caption'], 35 )}</a>
								</li>
							</ul>
							<div class='post entry-content'>
								{$image['tag']}
								<if test="hasDesc:|:$image['description']"><p class='ipsPad'>{$image['description']}</p></if>
							</div>
							<br />
						</div>
					</div>
				</div>
			</foreach>
		</div>
	<else />
		<div class='no_messages'>
			{$this->lang->words['no_unapproved_posts']}
		</div>
	</if>
	<div>{$pagelinks}</div>
</div>
<script type="text/javascript">
ipb.delegate.register('.delete_img', deletePopUp );
function deletePopUp(e, elem)
{
	Event.stop(e);
	
	if ( confirm( ipb.lang['delete_confirm'] ) )
	{
		window.location = elem.href;
	}
}
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>