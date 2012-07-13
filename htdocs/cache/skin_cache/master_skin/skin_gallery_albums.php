<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: albumFeatureView
//===========================================================================
function albumFeatureView($feature, $recents, $album, $children, $childrenTitle, $sideblocks=array(), $comments='', $childrenRootCount=0, $isUserView=false, $subAlbums=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type="text/javascript">
 ipb.gallery.inSection = 'albumOverview';
 ipb.gallery.albumId   = parseInt({$album['album_id']});
 ipb.gallery.albumUrl  = "{parse url="app=gallery&amp;album={$album['album_id']}" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}";
</script>
<if test="isUserViewStart:|:$isUserView">
	<a href='{parse url="showuser={$album['owner']['member_id']}" seotitle="{$album['owner']['members_seo_name']}" template="showuser" base="public"}' class='ipsUserPhotoLink left'>
		<img src='{$album['owner']['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_medium' />
	</a>
	<div class='ipsBox_withphoto'>
		<h1 class='ipsType_pagetitle'>{$album['album_name_userview']}</h1>
		<span class='desc'>{$this->lang->words['joined_ucfirst']}: {parse date="$album['owner']['joined']" format="joined"}</span>
	</div>
<else />
	<h1 class='ipsType_pagetitle'>
		<if test="isPrivate:|:$this->registry->gallery->helper('albums')->isPrivate($album) === true">
			<span class='ipsBadge ipsBadge_red reset_cursor'>{$this->lang->words['private_ucfirst']}</span>
		</if>
		{$album['album_name']}
	</h1>
</if>
<if test="hasRules:|:is_array( $album['album_g_rules_expanded'] )">
	<div class='ipsType_pagedesc forum_rules'>
		<if test="$album['album_g_rules_expanded']['text']">
			<strong>{$album['album_g_rules_expanded']['title']}</strong>
			{$album['album_g_rules_expanded']['text']}
		<else />
			{$album['album_description']}
		</if>
	</div>
</if>
<if test="canIuploadDearSirWellCanI:|:$this->registry->gallery->helper('albums')->isUploadable($album) || $this->registry->gallery->helper('albums')->canCreateSubAlbumInside($album)">
	<div class='topic_controls'>
		<ul class='topic_buttons'>
			<li><a href='{parse url="app=gallery&amp;module=post&amp;section=image&amp;album_id={$album['album_id']}" base="public"}' title='{$this->lang->words['upload']}'>{$this->lang->words['upload']}</a></li>
		</ul>
	</div>
</if>
<br />
<div class='ipsLayout ipsLayout_withright ipsLayout_largeright'>
	<div class='ipsLayout_right'>
		{$sideblocks['top']}
		{parse template="miniAlbumStripHorizontal" group="gallery_albums" params="$subAlbums, $childrenTitle, $album, $childrenRootCount"}
		{$sideblocks['bottom']}
		<if test="hasComments:|:$comments">
			<br />
			{parse template="miniLatestCommentBlock" group="gallery_albums" params="$comments, $this->lang->words['latest_commments']"}
		</if>
	</div>
	
	<div class='ipsLayout_content'>
		<div class='ipsBox'>
			<div class='ipsBox_container ipsPad'>
				<if test="hasFeature:|:!empty($feature['id'])">
					<div class='featured' id="image_wdesc_{$feature['id']}">
						{$feature['tag']}
						<p id='image_wdesc_{$feature['id']}_description' class='imageDescription'><strong>{$feature['caption']}</strong><br />{$feature['description']}<br />{$this->lang->words['from_album']}: {$feature['album_name']}</p>
					</div>
					
					<script type="text/javascript">
						document.observe("dom:loaded", function(){
							ipb.gallery.registerDescription({$feature['id']});
						} );
					</script>
					<br /><br />
				</if>
				<a id="albumimages"></a>
				<if test="hasRecent:|:count($recents)">
					<ul class='ipsList_inline ipsList_reset short wrap'>
						<foreach loop="images:$recents as $id => $data">
							<if test="isUserViewList:|:$isUserView">
								<li class='ipsPad_half gallery_tiny_box' -data-id='{$id}'>
							<else />
								<li class='ipsPad_half'>
							</if>
								<if test="comments:|:$data['comments']"><div class='small'>{$data['comments']}</div></if>
								{parse gallery_resize="$data['thumb']" width="thumb_large"}
								<div class='desc desc ipsType_smaller'>
									{IPSText::truncate($data['caption'], 16)}
									<if test="isOverviewView:|:!$isUserView">
										<br />{$this->lang->words['album_ucfirst']}: <a href='{parse url="app=gallery&amp;album={$data['album_id']}" seotitle="{$data['album_name_seo']}" template="viewalbum" base="public"}'>{$data['album_name']}</a>
									</if>
								</div>
							</li>
						</foreach>
					</ul>
					<if test="hasPages:|:! empty( $album['_pages'] )">
						<div class='ipsPad'>{$album['_pages']}</div>
						<br />
					</if>
					<br />
				</if>
			</div>
		</div>
	</div>
	
</div>
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: albumSelector
//===========================================================================
function albumSelector($albums, $recents, $albumData=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h3>{$this->lang->words['as_title']}</h3>
<div class='fixed_inner ipsBox'>
	<if test="isLargeTouch:|:$this->registry->output->isLargeTouchDevice()">
		<div class='message' style='margin-bottom: 5px;'>{$this->lang->words['scroll_tip']}</div>
	</if>
	<div class='ipsVerticalTabbed ipsLayout ipsLayout_withleft ipsLayout_smallleft clearfix'>
		<div class='ipsVerticalTabbed_tabs ipsLayout_left'>
			<ul id='albumSelector_nav'>
				<li class='active'><a data-album-pane-update="albums=global" href='#'>{$this->lang->words['as_global_albums']}</a></li>
				<if test="in_array( $this->request['type'], array( 'upload', 'createMembersAlbum', 'createAlbum', 'create' ) )">
					<li><a data-album-pane-update="albums=member&type={$this->request['type']}" href='#'>{$this->lang->words['as_your_albums']}</a></li>
				</if>
				<if test="$albumData['album_id'] && ! $albumData['album_is_global'] && ( $this->request['moderate'] || IN_ACP )">
					<li><a data-album-pane-update="albums=othermember&member_id={$albumData['album_owner_id']}&type={$this->request['type']}" href='#'>
					<if test="$albumData['album_owner_id'] != $this->memberData['member_id']">{$albumData['owners_members_display_name']}'s Albums<else />{$this->lang->words['as_your_albums']}</if>
					</a></li>
				</if>
				<li><a data-album-pane-update="albums=search&type={$this->request['type']}" href='#'>{$this->lang->words['as_search_albums']}</a></li>
				<li><a data-album-pane-update="albums=recent&type={$this->request['type']}" href='#'>{$this->lang->words['as_recent_albums']}</a></li>
			</ul>
		</div>
		<div class='ipsVerticalTabbed_content ipsLayout_content ipsBox_container' style='position: relative' id='albumSelector_content'>
			{$albums}
		</div>
	</div>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: albumSelectorPanel
//===========================================================================
function albumSelectorPanel($albums, $filters, $navAlbums, $albumData) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="isSearch:|:$this->request['albums'] == 'search'">
<div class='ipsPad row2 ipsText_small desc ipsForm_center'>
	{$this->lang->words['filters_search']}
	<select id='searchType'>
		<option value='member' id='searchType_member' <if test="$this->request['searchType']=='member'">selected='selected'</if>>{$this->lang->words['filters_owners_name']}</option>
		<option value='album' id='searchType_album' <if test="$this->request['searchType']=='album'">selected='selected'</if>>{$this->lang->words['filters_albums_name']}</option>
		<option value='parent' id='searchType_parent' <if test="$this->request['searchType']=='parent'">selected='selected'</if>>{$this->lang->words['filters_albums_parent']}</option>
	</select>
	<select id='searchMatch'>
		<option value='is' <if test="$this->request['searchMatch']=='is'">selected='selected'</if>>{$this->lang->words['filters_is']}</option>
		<option value='contains' <if test="$this->request['searchMatch']=='contains'">selected='selected'</if>>{$this->lang->words['filters_contains']}</option>
	</select>
	<input type='text' size='20' class='input_text ipsText_small' id='searchText' value='{$_REQUEST['searchText']}' />
	<select id='searchIsGlobal'>
		<option value='0' id='searchIsGlobal_0' <if test="$this->request['searchIsGlobal']=='0'">selected='selected'</if>>{$this->lang->words['as_search_member']}</option>
		<option value='1' id='searchIsGlobal_1' <if test="$this->request['searchIsGlobal']=='1'">selected='selected'</if>>{$this->lang->words['as_search_global']}</option>
	</select>
	<select id='searchSort'>
		<option value='date' id='searchSort_date' <if test="$this->request['searchSort']=='date'">selected='selected'</if>>{$this->lang->words['filters_sort_upload']}</option>
		<option value='name' id='searchSort_name' <if test="$this->request['searchSort']=='name'">selected='selected'</if>>{$this->lang->words['filters_sort_name']}</option>
		<option value='images' id='searchSort_images' <if test="$this->request['searchSort']=='images'">selected='selected'</if>>{$this->lang->words['filters_sort_images']}</option>
		<option value='comments' id='searchSort_comments' <if test="$this->request['searchSort']=='comments'">selected='selected'</if>>{$this->lang->words['filters_sort_comments']}</option>
	</select>
	<select id='searchDir'>
		<option value='desc' id='searchDir_desc' <if test="$this->request['searchDir']=='desc'">selected='selected'</if>>{$this->lang->words['filters_desc']}</option>
		<option value='asc' id='searchDir_asc' <if test="$this->request['searchDir']=='asc'">selected='selected'</if>>{$this->lang->words['filters_asc']}</option>
	</select>
	<input type='button' id='searchGo' value='{$this->lang->words['filters_update']}' class='ipsButton_secondary' />
</div>
</if>
<if test="hasNav:|:$navAlbums">
<div class="ipsPad">
	<foreach loop="nav:$navAlbums as $data">
		<a href='#' class="ipsText_small desc" data-album-pane-update="albums=search&searchType=parent&searchText={$data['album_id']}&searchisGlobal={$album['album_is_global']}">{$data['album_name']}</a>
		<if test="separator:|:!$data['_last']">
			<span class='ipsText_small desc'>&rarr;</span>
		</if>
	</foreach>
</div>
</if>
<if test="hazAlbums:|:count($albums)">
	<table class='ipb_table'>
		<if test="canMoveToRoot:|:$this->request['type'] == 'createGlobalAlbum' || ($albumData['album_is_global'] && ( $this->request['moderate'] || IN_ACP ) && ( $this->request['type'] != 'moveImages' ))">
			<tr class='row2'>
				<td width='1%'>&nbsp;</td>
				<td>
					<strong>{$this->lang->words['as_root']}</strong>
					<div class='ipsType_small desc'>{$this->lang->words['as_select_root']}</div>
				</td>
				<td width='1%'>
					<input type='button' data-album-select-album-id="0" class='ipsButton_secondary' value='{$this->lang->words['as_select']}' />
				</td>
			</tr>
		</if>
		<foreach loop="outer:$albums as $id => $album">
			<tr <if test="isThisAlbum:|:$album['album_id'] == $this->request['album_id']">class='row2'</if>>
				<td width='1%'><div class='ipsUserPhoto'>{parse gallery_resize="$album['thumb']" width="30"}</div></td>
				<td>
					<if test="hasParents:|:$album['_parents']">
						<foreach loop="parents:$album['_parents'] as $xid => $data">
							<a class='ipsType_small desc' data-album-pane-update="albums=search&searchType=parent&searchText={$data['album_id']}&searchisGlobal={$album['album_is_global']}" href='#'>{$data['album_name']}</a> <span class='ipsText_small desc'>&rarr;</span>
						</foreach>
					</if>
					<strong>{$album['album_name']}</strong>
					<if test="hasChildren:|:$album['_children']">
						<div class='ipsPad'>
							<ul class='ipsList'>
								<foreach loop="children:$album['_children'] as $xid => $data">
									<li>{parse gallery_resize="$data['thumb']" width="14"} <a href='#' data-album-pane-update="albums=global&parent={$data['album_parent_id']}">{$data['album_name']}</a></li>
								</foreach>
							</ul>
						</div>
					</if>
					<if test="isNotGlobal:|: ! $album['album_is_global'] && ( $this->memberData['member_id'] != $album['album_owner_id'] )">
						<div class='ipsType_small desc'>{$this->lang->words['acp_albums_owners_name']} <a href='#'>{$album['owners_members_display_name']}</a></div>
					</if>
					<if test="hasImages:|:$album['album_count_imgs']">
						<div class='ipsType_smaller desc lighter'>{parse expression="sprintf( $this->lang->words['as_images_comments'], $album['album_count_imgs'], $album['album_count_comments'] )"} {$this->lang->words['as_last_upload']} {parse date="$album['album_last_img_date']" format="tiny"}</div>
					</if>
				</td>
				<td width='1%'>
					<if test="restrictUpload:|: isset($album['_canUpload']) && ! $album['_canUpload']">
						&nbsp;
					<else />
						<input type='button' data-album-select-album-id="{$album['album_id']}" class='ipsButton_secondary' value='{$this->lang->words['as_select']}' />
					</if>
				</td>
			</tr>
		</foreach>
	</table>
<else />
	<div class='ipsPad desc'>{$this->lang->words['as_no_albums_show']}</div>
</if>
<script type="text/javascript">
	ipb.gallery_albumChooser.setParam( 'type'     , '{$this->request['type']}' );
	ipb.gallery_albumChooser.setParam( 'albums'   , '<if test="$isGlobal">global<else />member</if>' );
	ipb.gallery_albumChooser.setParam( 'moderate' , '{$this->request['moderate']}' );
	ipb.gallery_albumChooser.setParam( 'album_id' , '{$this->request['album_id']}' );
	ipb.gallery_albumChooser.setParam( 'member_id', '{$this->request['member_id']}' );
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: albumView
//===========================================================================
function albumView($cover, $images, $album, $children, $parents, $follow='', $subAlbums='', $childrenRootCount) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type="text/javascript">
 ipb.gallery.inSection = 'albumOverview';
 ipb.gallery.albumId   = parseInt({$album['album_id']});
 ipb.gallery.albumUrl  = "{parse url="app=gallery&amp;album={$album['album_id']}" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}";
</script>
{parse js_module="gallery_albumchooser"}
<if test="hasFollow:|:$follow">
	{$follow}
</if>
{$cover['tag']}
<div class='ipsBox_withphoto'>
	<if test="! $this->registry->gallery->helper('albums')->isGlobal($album)">
		<span class='rating ipsType_smaller'>
			<strong>{$this->lang->words['rate_album']}</strong>&nbsp;&nbsp;
			<if test="guestrate1:|:$this->memberData['member_id'] && $this->memberData['member_id'] != $album['album_owner_id']"><a href='{parse url="app=gallery&amp;module=images&amp;section=rate&amp;where=album&amp;id={$album['album_id']}&amp;rating=1&amp;secure_key={$this->member->form_hash}" base="public"}' id='album_rate_1' title='{$this->lang->words['rate_file_1']}'></if><if test="filerate1:|:$album['album_rating_aggregate'] >= 1"><img src="{$this->settings['img_url']}/star.png" alt='*' class='rate_img' /><else /><img src="{$this->settings['img_url']}/star_off.png" alt='*' class='rate_img' /></if><if test="endguestrate1:|:$this->memberData['member_id']"></a></if>
			<if test="guestrate2:|:$this->memberData['member_id'] && $this->memberData['member_id'] != $album['album_owner_id']"><a href='{parse url="app=gallery&amp;module=images&amp;section=rate&amp;where=album&amp;id={$album['album_id']}&amp;rating=2&amp;secure_key={$this->member->form_hash}" base="public"}' id='album_rate_2' title='{$this->lang->words['rate_file_2']}'></if><if test="filerate2:|:$album['album_rating_aggregate'] >= 2"><img src="{$this->settings['img_url']}/star.png" alt='*' class='rate_img' /><else /><img src="{$this->settings['img_url']}/star_off.png" alt='*' class='rate_img' /></if><if test="endguestrate2:|:$this->memberData['member_id']"></a></if>
			<if test="guestrate3:|:$this->memberData['member_id'] && $this->memberData['member_id'] != $album['album_owner_id']"><a href='{parse url="app=gallery&amp;module=images&amp;section=rate&amp;where=album&amp;id={$album['album_id']}&amp;rating=3&amp;secure_key={$this->member->form_hash}" base="public"}' id='album_rate_3' title='{$this->lang->words['rate_file_3']}'></if><if test="filerate3:|:$album['album_rating_aggregate'] >= 3"><img src="{$this->settings['img_url']}/star.png" alt='*' class='rate_img' /><else /><img src="{$this->settings['img_url']}/star_off.png" alt='*' class='rate_img' /></if><if test="endguestrate3:|:$this->memberData['member_id']"></a></if>
			<if test="guestrate4:|:$this->memberData['member_id'] && $this->memberData['member_id'] != $album['album_owner_id']"><a href='{parse url="app=gallery&amp;module=images&amp;section=rate&amp;where=album&amp;id={$album['album_id']}&amp;rating=4&amp;secure_key={$this->member->form_hash}" base="public"}' id='album_rate_4' title='{$this->lang->words['rate_file_4']}'></if><if test="ralerate4:|:$album['album_rating_aggregate'] >= 4"><img src="{$this->settings['img_url']}/star.png" alt='*' class='rate_img' /><else /><img src="{$this->settings['img_url']}/star_off.png" alt='*' class='rate_img' /></if><if test="endguestrate4:|:$this->memberData['member_id']"></a></if>
			<if test="guestrate5:|:$this->memberData['member_id'] && $this->memberData['member_id'] != $album['album_owner_id']"><a href='{parse url="app=gallery&amp;module=images&amp;section=rate&amp;where=album&amp;id={$album['album_id']}&amp;rating=5&amp;secure_key={$this->member->form_hash}" base="public"}' id='album_rate_5' title='{$this->lang->words['rate_file_5']}'></if><if test="filerate5:|:$album['album_rating_aggregate'] >= 5"><img src="{$this->settings['img_url']}/star.png" alt='*' class='rate_img' /><else /><img src="{$this->settings['img_url']}/star_off.png" alt='*' class='rate_img' /></if><if test="endguestrate5:|:$this->memberData['member_id']"></a></if>
			<span id='rating_text' class='desc'>
				<if test="filevotes:|:$album['album_rating_count'] > 0">{$album['album_rating_count']} {$this->lang->words['votes_cnt']}</if>
				<if test="fileyourvote:|:$album['_youRated']">({$this->lang->words['you_voted']} {$album['_youRated']})</if>
			</span>
			<if test="checkGuestRate:|:$this->memberData['member_id']">
				<script type='text/javascript'>
					rating = new ipb.rating( 'album_rate_', { 
										url: 		'{parse url="app=gallery&amp;module=ajax&amp;section=rate&amp;id={$album['album_id']}&where=album" base="public"}&md5check=' + ipb.vars['secure_hash'],
										cur_rating: {parse expression="intval($album['album_rating_aggregate'])"},
										rated: 		<if test="filejsvotes:|:$album['album_rating_count']">1<else />0</if>,
										allow_rate: <if test="filejsallowvote:|:$this->registry->gallery->helper('rate')->canRate($album) !== false && $this->memberData['member_id'] != $album['album_owner_id']">1<else />0</if>
									  } );
				</script>
			</if>
		</span>
	</if>
	<h1 class='ipsType_pagetitle'>
		<if test="isPrivate:|:$this->registry->gallery->helper('albums')->isPrivate($album) === true">
			<span class='ipsBadge ipsBadge_red reset_cursor'>{$this->lang->words['private_ucfirst']}</span>
		</if>
		{$album['album_name']}
	</h1>
	<if test="hasRules:|:$album['album_description'] || $album['album_g_rules_expanded']['text']">
		<div class='ipsType_pagedesc forum_rules'>
			<if test="hasExpandedTextRules:|:$album['album_g_rules_expanded']['text']">
				<strong>{$album['album_g_rules_expanded']['title']}</strong>
				{$album['album_g_rules_expanded']['text']}
			<else />
				{$album['album_description']}
			</if>
		</div>
	</if>
</div>
<div class='topic_controls clear clearfix'>
	<if test="hasPagesTop:|:$album['_pages']">
		{$album['_pages']}
	</if>
	<ul class='topic_buttons'>
		<if test="canIuploadDearSirWellCanI:|:$this->registry->gallery->helper('albums')->isUploadable($album) || $this->registry->gallery->helper('albums')->canCreateSubAlbumInside($album)">
			<li><a href='{parse url="app=gallery&amp;module=post&amp;section=image&amp;album_id={$album['album_id']}" base="public"}' title='{$this->lang->words['upload']}'>{$this->lang->words['upload']}</a></li>
		</if>
		<li class='non_button'><a href="{parse url="app=gallery&amp;module=images&amp;section=slideshow&amp;album={$album['album_id']}" base="public"}">{$this->lang->words['ss_title']}</a></li>
		<li class='non_button'><a href='{parse url="app=gallery&amp;album={$album['album_id']}&amp;display=detail" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}'>{$this->lang->words['detail_ucfirst']}</a></li>
	</ul>
</div>
<div class='ipsLayout ipsLayout_withright ipsLayout_hugeright'>
	
	<div class='ipsLayout_right'>
		<div class='ipsBox'>
			<div class='ipsBox_container ipsPad clearfix'>
				<if test="boxAlbumIsGlobal:|:$this->registry->gallery->helper('albums')->isGlobal($album)">
					<span class='desc'>
						<a href="{parse url="app=gallery&amp;module=albums&amp;section=rss&amp;album={$album['album_id']}" base="public" template="rssalbum" seotitle="{$album['album_name_seo']}"}"><img src="{$this->settings['img_url']}/rss-mini.png" alt="{$this->lang->words['rss_feed']}" /></a> {$album['album_count_imgs']} {$this->lang->words['images_lower']}, {$album['album_count_comments']} {$this->lang->words['comments_lower']}
					</span>
				<else />
					<ul class='ipsList_withmediumphoto'>
						<li class='clearfix'>
							<if test="uploadedByMember:|:$album['owner']['member_id']">
								<a href='{parse url="showuser={$album['owner']['member_id']}" seotitle="{$album['owner']['members_seo_name']}" template="showuser" base="public"}' class='ipsUserPhotoLink left'>
									<img src='{$album['owner']['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_medium' />
								</a>
							<else />
								<div class='left'>{IPSMember::buildNoPhoto(0, 'small' )}</div>
							</if>
							<div class='list_content'>
								{$this->lang->words['by_ucfirst']} {parse template="userHoverCard" group="global" params="$album['owner']"} <span class='ipsType_smaller'>(<a href="{parse url="app=gallery&amp;user={$album['owner']['member_id']}" seotitle="{$album['owner']['members_seo_name']}" template="useralbum" base="public"}">{$this->lang->words['view_all_albums']}</a>)</span>
								<br /><br />
								<span class='desc'>
									<if test="hasLovelyRss:|:$this->registry->gallery->helper('albums')->isPrivate($album) !== true">
										<a href="{parse url="app=gallery&amp;module=albums&amp;section=rss&amp;album={$album['album_id']}" base="public" template="rssalbum" seotitle="{$album['album_name_seo']}"}"><img src="{$this->settings['img_url']}/rss-mini.png" alt="{$this->lang->words['rss_feed']}" /></a>
									</if>
									{$album['album_count_imgs']} {$this->lang->words['images_lower']}, {$album['album_count_comments']} {$this->lang->words['comments_lower']}
								</span>
							</div>
						</li>
					</ul>
					
					<if test="canDoStuff:|:$album['_canEdit'] || $album['_canDelete']">
						<br class='clear' />
						<ul class='ipsList_inline right'>
							<if test="canEditAlbum:|:$album['_canEdit']">
								<li><a href="{parse url="app=gallery&amp;albumedit={$album['album_id']}" base="public" template="editalbum" seotitle="{$album['album_name_seo']}"}" class='ipsButton_secondary'>{$this->lang->words['edit_ucfirst']}</a></li>
							</if>
							<if test="canDeleteAlbum:|:$album['_canDelete']">
								<li><a href="javascript:void(0);" base="public"}" album-id="{$album['album_id']}" class='ipsButton_secondary important _albumDelete'>{$this->lang->words['delete_ucfirst']}</a></li>
							</if>
						</ul>
					</if>
				</if>
			</div>
		</div>
		<if test="hasKids:|:is_array( $subAlbums ) && count($subAlbums)">
			<br />
			<div class='ipsBox_container'>
				{parse template="miniAlbumStripHorizontal" group="gallery_albums" params="$subAlbums, $this->lang->words['sub_albums'], $album, $childrenRootCount"}
			</div>
		</if>
	</div>
	
	<div class='ipsLayout_content'>
		<div class='maintitle ipsFilterbar'>
			<ul class='ipsList_inline ipsType_smaller'>
				<li <if test="$this->request['sortby'] == 'idate' AND $this->request['sortorder'] == 'DESC'">class='active'</if>><a href='{parse url="app=gallery&amp;album={$album['album_id']}&amp;sortby=idate" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}'>{$this->lang->words['most_recent']}</a></li>
				<li <if test="$this->request['sortby'] == 'views' AND $this->request['sortorder'] == 'DESC'">class='active'</if>><a href='{parse url="app=gallery&amp;album={$album['album_id']}&amp;sortby=views" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}'>{$this->lang->words['most_viewed']}</a></li>
				<li <if test="$this->request['sortby'] == 'rating' AND $this->request['sortorder'] == 'DESC'">class='active'</if>><a href='{parse url="app=gallery&amp;album={$album['album_id']}&amp;sortby=rating" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}'>{$this->lang->words['most_popular']}</a></li>
				<if test="enabledComments:|:$album['album_allow_comments']">
					<li <if test="$this->request['sortby'] == 'comments' AND $this->request['sortorder'] == 'DESC'">class='active'</if>><a href='{parse url="app=gallery&amp;album={$album['album_id']}&amp;sortby=comments" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}'>{$this->lang->words['most_comments']}</a></li>
				</if>
				<li <if test="$this->request['sortby'] == 'name' AND $this->request['sortorder'] == 'ASC'">class='active'</if>><a href='{parse url="app=gallery&amp;album={$album['album_id']}&amp;sortby=name&amp;sortorder=ASC" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}'>{$this->lang->words['sort_by_name']}</a></li>
			</ul>
		</div>
		<div class='ipsBox'>
			<div class='ipsBox_container ipsPad'>
				<if test="hasImages:|:is_array($images) && count($images)">
					<ul class='ipsList_inline ipsList_reset wrap'>
						<foreach loop="images:$images as $id => $data">
							<li>
								<if test="moderate:|:$album['_canModerate']"><input type="checkbox" name="modBox_{$data['id']}" id="modBox_{$data['id']}" value="1" class="albumModBox" /></if>
								<if test="comments:|:$data['comments']"><div class='small'>{$data['comments']}</div></if>
								{parse gallery_resize="$data['thumb']" width="thumb_large"}
							</li>
						</foreach>
					</ul>
				<else />
					{$this->lang->words['category_no_images']}
				</if>
			</div>
		</div>
		<if test="hasPagesBottom:|:$album['_pages']">
			<div class='ipsPad'>{$album['_pages']}</div>
			<br />
		</if>
	</div>
	
</div>
<br class='clear' />
<if test="hasShareLinks:|:$this->settings['sl_enable']">
	<div class='ipsPad'>
		{IPSLib::shareLinks( $album['album_name'], array( 'skip' => array( 'print', 'download' ) ) )}
	</div>
	<br />
</if>
<if test="albumMod:|:$album['_canModerate']">
	{parse template="inlineAlbumModeration" group="gallery_albums" params=""}
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: albumViewDetail
//===========================================================================
function albumViewDetail($cover, $images, $album, $children, $parents, $follow='', $subAlbums='', $childrenRootCount) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type="text/javascript">
 ipb.gallery.inSection = 'albumDetailView';
 ipb.gallery.albumId   = parseInt({$album['album_id']});
 ipb.gallery.albumUrl  = "{parse url="app=gallery&amp;album={$album['album_id']}" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}";
</script>
{parse js_module="gallery_albumchooser"}
<if test="hasFollow:|:$follow">
	{$follow}
</if>
{$cover['tag']}
<div class='ipsBox_withphoto'>
	<if test="! $this->registry->gallery->helper('albums')->isGlobal($album)">
		<span class='rating ipsType_smaller'>
			<strong>{$this->lang->words['rate_album']}</strong>&nbsp;&nbsp;
			<if test="guestrate1:|:$this->memberData['member_id'] && $this->memberData['member_id'] != $album['album_owner_id']"><a href='{parse url="app=gallery&amp;module=images&amp;section=rate&amp;where=album&amp;id={$album['album_id']}&amp;rating=1&amp;secure_key={$this->member->form_hash}" base="public"}' id='album_rate_1' title='{$this->lang->words['rate_file_1']}'></if><if test="filerate1:|:$album['album_rating_aggregate'] >= 1"><img src="{$this->settings['img_url']}/star.png" alt='*' class='rate_img' /><else /><img src="{$this->settings['img_url']}/star_off.png" alt='*' class='rate_img' /></if><if test="endguestrate1:|:$this->memberData['member_id']"></a></if>
			<if test="guestrate2:|:$this->memberData['member_id'] && $this->memberData['member_id'] != $album['album_owner_id']"><a href='{parse url="app=gallery&amp;module=images&amp;section=rate&amp;where=album&amp;id={$album['album_id']}&amp;rating=2&amp;secure_key={$this->member->form_hash}" base="public"}' id='album_rate_2' title='{$this->lang->words['rate_file_2']}'></if><if test="filerate2:|:$album['album_rating_aggregate'] >= 2"><img src="{$this->settings['img_url']}/star.png" alt='*' class='rate_img' /><else /><img src="{$this->settings['img_url']}/star_off.png" alt='*' class='rate_img' /></if><if test="endguestrate2:|:$this->memberData['member_id']"></a></if>
			<if test="guestrate3:|:$this->memberData['member_id'] && $this->memberData['member_id'] != $album['album_owner_id']"><a href='{parse url="app=gallery&amp;module=images&amp;section=rate&amp;where=album&amp;id={$album['album_id']}&amp;rating=3&amp;secure_key={$this->member->form_hash}" base="public"}' id='album_rate_3' title='{$this->lang->words['rate_file_3']}'></if><if test="filerate3:|:$album['album_rating_aggregate'] >= 3"><img src="{$this->settings['img_url']}/star.png" alt='*' class='rate_img' /><else /><img src="{$this->settings['img_url']}/star_off.png" alt='*' class='rate_img' /></if><if test="endguestrate3:|:$this->memberData['member_id']"></a></if>
			<if test="guestrate4:|:$this->memberData['member_id'] && $this->memberData['member_id'] != $album['album_owner_id']"><a href='{parse url="app=gallery&amp;module=images&amp;section=rate&amp;where=album&amp;id={$album['album_id']}&amp;rating=4&amp;secure_key={$this->member->form_hash}" base="public"}' id='album_rate_4' title='{$this->lang->words['rate_file_4']}'></if><if test="ralerate4:|:$album['album_rating_aggregate'] >= 4"><img src="{$this->settings['img_url']}/star.png" alt='*' class='rate_img' /><else /><img src="{$this->settings['img_url']}/star_off.png" alt='*' class='rate_img' /></if><if test="endguestrate4:|:$this->memberData['member_id']"></a></if>
			<if test="guestrate5:|:$this->memberData['member_id'] && $this->memberData['member_id'] != $album['album_owner_id']"><a href='{parse url="app=gallery&amp;module=images&amp;section=rate&amp;where=album&amp;id={$album['album_id']}&amp;rating=5&amp;secure_key={$this->member->form_hash}" base="public"}' id='album_rate_5' title='{$this->lang->words['rate_file_5']}'></if><if test="filerate5:|:$album['album_rating_aggregate'] >= 5"><img src="{$this->settings['img_url']}/star.png" alt='*' class='rate_img' /><else /><img src="{$this->settings['img_url']}/star_off.png" alt='*' class='rate_img' /></if><if test="endguestrate5:|:$this->memberData['member_id']"></a></if>
			<span id='rating_text' class='desc'>
				<if test="filevotes:|:$album['album_rating_count'] > 0">{$album['album_rating_count']} {$this->lang->words['votes_cnt']}</if>
				<if test="fileyourvote:|:$album['_youRated']">({$this->lang->words['you_voted']} {$album['_youRated']})</if>
			</span>
			<if test="checkGuestRate:|:$this->memberData['member_id']">
				<script type='text/javascript'>
					rating = new ipb.rating( 'album_rate_', { 
										url: 		'{parse url="app=gallery&amp;module=ajax&amp;section=rate&amp;id={$album['album_id']}&where=album" base="public"}&md5check=' + ipb.vars['secure_hash'],
										cur_rating: {parse expression="intval($album['album_rating_aggregate'])"},
										rated: 		<if test="filejsvotes:|:$album['album_rating_count']">1<else />0</if>,
										allow_rate: <if test="filejsallowvote:|:$this->registry->gallery->helper('rate')->canRate($album) !== false && $this->memberData['member_id'] != $album['album_owner_id']">1<else />0</if>
									  } );
				</script>
			</if>
		</span>
	</if>
	<h1 class='ipsType_pagetitle'>
		<if test="isPrivate:|:$this->registry->gallery->helper('albums')->isPrivate($album) === true">
			<span class='ipsBadge ipsBadge_red reset_cursor'>{$this->lang->words['private_ucfirst']}</span>
		</if>
		{$album['album_name']}
	</h1>
	<if test="hasRules:|:$album['album_description'] || $album['album_g_rules_expanded']['text']">
		<div class='ipsType_pagedesc forum_rules'>
			<if test="hasExpandedTextRules:|:$album['album_g_rules_expanded']['text']">
				<strong>{$album['album_g_rules_expanded']['title']}</strong>
				{$album['album_g_rules_expanded']['text']}
			<else />
				{$album['album_description']}
			</if>
		</div>
	</if>
</div>
<div class='topic_controls clear'>
	<if test="hasPagesTop:|:! empty( $album['_pages'] )">
		{$album['_pages']}
	</if>
	<ul class='topic_buttons'>
		<if test="canIuploadDearSirWellCanI:|:$this->registry->gallery->helper('albums')->isUploadable($album) || $this->registry->gallery->helper('albums')->canCreateSubAlbumInside($album)">
			<li><a href='{parse url="app=gallery&amp;module=post&amp;section=image&amp;album_id={$album['album_id']}" base="public"}' title='{$this->lang->words['upload']}'>{$this->lang->words['upload']}</a></li>
		</if>
		<li class='non_button'><a href="{parse url="app=gallery&amp;module=images&amp;section=slideshow&amp;album={$album['album_id']}" base="public"}">{$this->lang->words['ss_title']}</a></li>
		<li class='non_button'><a class='on' href='{parse url="app=gallery&amp;album={$album['album_id']}&amp;display=overview" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}'>{$this->lang->words['overview_ucfirst']}</a></li>
	</ul>
</div>
<br />
<if test="hasKids:|:array( $children ) && count( $children )">
	<h3 class="maintitle">{$album['album_name']} {$this->lang->words['album_detail_subalbums']}</h3>
	<div class='ipsBox'>
		<div class='ipsBox_container'>
			<table class='ipb_table'>
				<foreach loop="results:$children as $data">
					<tr>
						<td class='col_c_icon short altrow'>
							{parse gallery_resize="$data['thumb']" width="thumb_small"}
						</td>
						<td>
							<span class='right'>
								<foreach loop="ratingLoop:array(1,2,3,4,5) as $_rating">
									<if test="albumrate:|:$data['album_rating_aggregate'] >= $_rating">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if>
								</foreach>
							</span>
							
							<h4>
								<if test="albumIsGlobal:|:$this->registry->gallery->helper('albums')->isGlobal($data)">
									<span class='ipsBadge ipsBadge_grey reset_cursor'>{$this->lang->words['global_album']}</span>
								</if>
								<a href='{parse url="app=gallery&amp;album={$data['album_id']}" base="public" template="viewalbum" seotitle="{$data['album_name_seo']}"}'>{IPSText::truncate( $data['album_name'], 200)}</a>
							</h4>
							<if test="hasDescription:|:$data['album_description']">
								<div class='desc lighter blend_links'>
									{parse expression="IPSText::truncate( strip_tags( IPSText::getTextClass('bbcode')->stripAllTags( $data['album_description'] ), '<br />' ), 230 )"}
								</div>
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
								<a href='{parse url="showuser={$data['member']['member_id']}" seotitle="{$data['member']['members_seo_name']}" template="showuser" base="public"}' class='ipsUserPhotoLink left'>
									<img src='{$data['member']['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' />
								</a>
								<ul class='last_post ipsType_small'>
									<li>{parse template="userHoverCard" group="global" params="$data['member']"}</li>
									<li>{parse date="$data['album_last_img_date']" format="DATE"}</li>
								</ul>
							</if>
						</td>
					</tr>
				</foreach>
			</table>
		</div>
	</div>
	<br />
</if>
<table class='ipb_table' id='albumDetailTable'>
	<foreach loop="$images as $id => $image">
		<if test="$__iteratorCount == 1 OR $__iteratorCount + 1 % 3 == 0">
			<tr>
		</if>
			<td class='ipsBox_container ipsPad<if test="imageIsUnapproved:|:!$image['approved']"> moderated</if>'>
				<h3><if test="moderate:|:$album['_canModerate']"><input type="checkbox" name="modBox_{$image['id']}" id="modBox_{$image['id']}" value="1" class="albumModBox right" /></if>{$image['caption']}</h3>
				<br />
				<div class='short'>{$image['_smallTag']}</div>
				<br />
				<div class='desc'>
					<p class='short'><em><if test="imageHasDescription:|:$image['_descriptionParsed']">{$image['_descriptionParsed']}<else />&nbsp;</if></em></p>
					<br />
					{$this->lang->words['uploaded_ucfirst']}: {parse date="$image['idate']" format="short"}
					<if test="$image['member_id']"><br />{$this->lang->words['by_ucfirst']} {parse template="userHoverCard" group="global" params="array('member_id' => $image['member_id'], 'members_display_name' => $image['members_display_name'], 'members_seo_name' => $image['members_seo_name'] )"}</strong> (<a href="{parse url="app=gallery&amp;user={$image['member_id']}" seotitle="{$image['members_seo_name']}" template="useralbum" base="public"}">{$this->lang->words['view_all_albums']}</a>)</if>
					<br />{parse expression="intval($image['comments'])"} {$this->lang->words['comments_lower']} &middot; {parse expression="intval($image['views'])"} {$this->lang->words['views_lower']}
				</div>
			</td>
		<if test="$__iteratorCount % 3 == 0">
			</tr>
		</if>
	</foreach>
	<if test="count( $images ) % 3">
		<foreach loop="array_fill( 0, 3 - ( count( $images ) % 3 ), '' ) as $foo">
			<td class='ipsBox_container ipsPad'>&nbsp;</td>
		</foreach>
		</tr>
	</if>
</table>
<if test="hasPagesBottom:|:! empty( $album['_pages'] )">
	<div class='ipsPad'>{$album['_pages']}</div>
	<br />
</if>
<br />
<if test="hasShareLinks:|:$this->settings['sl_enable']">
	<div class='ipsPad'>
		{IPSLib::shareLinks( $album['album_name'], array( 'skip' => array( 'print', 'download' ) ) )}
	</div>
	<br />
</if>
<if test="albumMod:|:$album['_canModerate']">
	{parse template="inlineAlbumModeration" group="gallery_albums" params=""}
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: boardIndexEntry
//===========================================================================
function boardIndexEntry($album) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<style type="text/css">
.inlineimage img {
	float: right;
	width: 30px;
	height: 30px;
	padding-left: 5px;
}
</style>
<php>
	if ( $this->registry->gallery->helper('albums')->isContainerOnly( $album ) OR $this->registry->gallery->helper('albums')->isGlobal( $album ) )
	{
		$children     = $this->registry->gallery->helper('albums')->fetchAlbumChildren( $album['album_id'], array( 'isViewable' => true, 'limit' => 50, 'sortKey' => 'date', 'sortOrder' => 'desc' ) );
		$this->_recentImages[ $album['album_id'] ] = $this->registry->gallery->helper('image')->fetchImages( $this->memberData['member_id'], array( 'albumId' => array_keys( $children ), 'limit' => 8, 'sortKey' => 'date', 'sortOrder' => 'desc' ) );
	}
</php>
<tr class='{parse striping="catTable"}'>
	<td class='altrow' align='center'>
		{parse gallery_resize="$album['thumb']" width="30"}
	</td>
	<td>
		<h4><a href="{parse url="app=gallery&amp;album={$album['album_id']}" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}">{$this->lang->words['album_ucfirst']} {$album['album_name']}</a></h4>
		<p class='desc'>{$album['album_description']}</p>
	</td>
	<if test="isContainerOnly:|:$this->registry->gallery->helper('albums')->isContainerOnly( $album ) OR $this->registry->gallery->helper('albums')->isGlobal( $album )">
		<td class='altrow stats' colspan="2" style='overflow:hidden'>
		<if test="is_array( $this->_recentImages[ $album['album_id'] ] ) && count($this->_recentImages[ $album['album_id'] ])">
			<foreach loop="$this->_recentImages[ $album['album_id'] ] as $id => $data">
				<div class='inlineimage'>{parse gallery_resize="$data['thumb']" width="thumb_tiny"}</div>
			</foreach>
		</if>
		</td>
	<else />
	<td class='altrow stats'>
		<ul>
			<li>{$album['album_count_imgs']} {$this->lang->words['images_lower']}</li>
			<li>{$album['album_count_comments']} {$this->lang->words['comments_lower']}</li>
		</ul>
	</td>
	<td>
		<ul class='last_post'>
			<if test="hideLastInfo:|:$album['album_last_img_date']">
				<li>{parse date="$album['album_last_img_date']" format="LONG"}</li>
			</if>
		</ul>
	</td>
	</if>
</tr>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: deleteAlbumDialogue
//===========================================================================
function deleteAlbumDialogue($data=array(), $hasKids=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="hasKids:|:$hasKids">
	<h3>{$this->lang->words['delete_album']}</h3>
	<div class='pad center'>
	 {$this->lang->words['mod_alb_del_not_able_children']}
	</div>
<else />
<form action="{$this->settings['base_url']}app=gallery&module=albums&section=album&amp;do=delete&amp;album={$data['album_id']}" method="post" id="albumDeleteForm_{$data['album_id']}">
	<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
	{$data['hiddens']}
	{$data['errors']}
	
	<h3>{$this->lang->words['delete_album']}</h3>
	<div class='ipsBox short'>
	 {$this->lang->words['mod_alb_del_title']}
	 <if test="hasAlbums:|:$data['options'] !== false">
	 	<div style="width:auto; display:inline-block; margin: 0 auto; text-align: left;" class='ipsPad'>
	 	 	<input type="radio" name="doDelete" value="0" checked="checked" /> {$this->lang->words['mod_alb_del_move']}
		 	<select name='move_to_album_id' id='move_to_album_id' class='input_select'>
				{$data['options']}
			</select>
			<br />
			<input type="radio" name="doDelete" value="1" /> {$this->lang->words['mod_alb_del_desc']}
		</div>
	 <else />
	 	<input type="hidden" name="doDelete" value="1" />
	 </if>
	 <br /><br />
	 <input type='submit' class="input_submit" value="{$this->lang->words['mod_alb_del_go']}" />
	</div>
</form>
</if>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: galleryPhotoAlbum
//===========================================================================
function galleryPhotoAlbum($albumImages, $albumID) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<fieldset class='row1'>
		<h3>{$this->lang->words['profile_photo_album']}</h3>
		
		<foreach loop="$albumImages as $r">
			<div class='avatar_gallery'>
				<a href='{parse url="app=gallery&amp;module=images&amp;section=profilephoto&amp;id={$r['id']}&amp;return=usercp" base="public"}' title='{$this->lang->words['profile_photo_thumb_title']}'><img src="{$r['_image']}" alt="" /></a>
			</div>
		</foreach>
		<p>&nbsp;</p>
		<p align='right'><a href='{parse url="app=gallery&amp;module=user&amp;user={$this->memberData['member_id']}&amp;do=view_album&amp;album={$albumID}" base="public"}'>{$this->lang->words['profile_photo_view_album']}</a></p>
	</fieldset>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: inlineAlbumModeration
//===========================================================================
function inlineAlbumModeration() {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type='text/javascript'>
	albumSelectorUpdate = '{"field": "albumModBox_moveTo","div": "asDiv"}';
	ipb.templates['album_moderation'] = new Template("<div id='album_moderate_box' class='ipsFloatingAction' style='display: none'><span class='desc'>{$this->lang->words['album_modaction_count']} </span><select name='modOptions' id='albumModAction' class='input_select'><option value='approve'>{$this->lang->words['mod_img_approve']}</option><option value='unapprove'>{$this->lang->words['mod_img_unapprove']}</option><option value='delete'>{$this->lang->words['mod_img_delete']}</option><option value='move'>{$this->lang->words['mod_img_move']}</option></select>&nbsp;&nbsp;<input type='button' class='input_submit' id='submitModAction' value='{$this->lang->words['jmp_go']}' /><div id='albumModBox_move' class='desc ipsPad_half short' style='display:none'></div><div class='desc ipsPad_half short'><input type='checkbox' name='albumModSelectAll' id='albumModSelectAll' value='1' #{checked} /> <label for='albumModSelectAll'>{$this->lang->words['mod_select_all']}</label></p></div>");
	ipb.templates['album_img_moveto'] = new Template("{$this->lang->words['album_modaction_moveto']}: <input type='hidden' id='albumModBox_moveTo' value=0 /><div style='display:none' class='albumSelected' id='asDiv'></div><a class='ipsButton_secondary' data-album-selector-auto-update='" + albumSelectorUpdate + "' data-album-selector='type=moveImages&moderate=1&album_id=#{album_id}'>{$this->lang->words['as_select_album']}</a>");
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: miniAlbumStripHorizontal
//===========================================================================
function miniAlbumStripHorizontal($albums, $title='', $parent=array(), $beforeTrimCount=0) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse striping="gallglobal" classes="row1,row2"}
<div class='general_box'>
	<if test="hasTitle:|:$title"><h3>{$title}</h3></if>
	<if test="noAlbums:|:! count( $albums ) OR ! is_array( $albums )">
		<div class='ipsPad'>{$this->lang->words['albums_none_to_see']}</div>
	<else />
		<ul>
			<foreach loop="$albums as $i => $album">
				<li class='{parse striping="gallglobal"} clear'>
					<div class='album horizontal'>
						{parse gallery_resize="$album['thumb']" width="thumb_small"}
						<p>
							<if test="terabyteWillComplainIfIDontAddThisHookId:|:$this->registry->gallery->helper('albums')->isPrivate($album)">
								<span class='ipsBadge ipsBadge_red reset_cursor'>{$this->lang->words['private_ucfirst']}</span>
							</if>
							<a href='{parse url="app=gallery&amp;album={$album['album_id']}" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}'>{$album['album_name']}</a>
						</p>
						<if test="hasSubChildren:|:is_array($album['_children']) && count($album['_children'])">
							{parse template="miniAlbumStripSubAlbums" group="gallery_albums" params="$album['_children'], $album"}
						</if>
						<if test="isGlobal:|:$album['album_is_global']">
							<if test="notContainer:|:is_array( $album['_latestImages'] )">
								<ol class='ipsList_inline ipsType_small' style='margin-left:76px'>
								<foreach loop="latestImgs:$album['_latestImages'] as $id => $img">
									{parse gallery_resize="$img['thumb']" width="teeny" class="ipsUserPhoto"}
								</foreach>
								</ol>
								<if test="hasSubChildren:|:is_array($album['_children']) && count($album['_children'])">
									<br class='clear' /><br />
								</if>
							</if>
						<else />
							<if test="hasCount:|:$album['album_count_imgs']">
								<br />
								<div class='ipsType_small desc'>
								{parse expression="intval($album['album_count_imgs'])"} {$this->lang->words['total_images']}
								<br />{$this->lang->words['last_ucfirst']}: {parse date="$album['album_last_img_date']" format="short"}
								</div>
							</if>
						</if>
					</div>
				</li>
			</foreach>
			<if test="hasmore:|:$beforeTrimCount && ( $beforeTrimCount > count( $albums ) )">
				<li class='{parse striping="gallglobal"} desc showMore clear ipsPad'>
					<a href="{parse url="app=gallery&amp;browseAlbum={$parent['album_id']}" seotitle="{$parent['album_name_seo']}" template="browsealbum" base="public"}">{$this->lang->words['show_all_dots']}</a>
				</li>
			</if>
		</ul>
	</if>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: miniAlbumStripSubAlbums
//===========================================================================
function miniAlbumStripSubAlbums($albums, $parent) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<php>
$count         = count( $albums );
$this->_lang   = '';
if ( $count > 5 )
{
	$albums      = array_slice( $albums, 0, 5, true );
	$this->_lang = sprintf( $this->lang->words['gal_and_n_more'], ( $count - 5 ) );
}
$ids           = array_keys( $albums );
$this->_lastId = array_pop( $ids );
</php>
<ol class='ipsList_inline ipsType_small sub_albums' id='subalbums_{$parent['album_id']}'>
	<foreach loop="subAlbums:$albums as $id => $album">
		<li>
			<a href="{parse url="app=gallery&amp;album={$album['album_id']}" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}">{$album['album_name']}</a><if test="isNotLast:|: $album['album_id'] != $this->_lastId">,</if>
		</li>
		<if test="hasMore:|:$this->_lang && ($album['album_id'] == $this->_lastId )">
			<li><a href="{parse url="app=gallery&amp;album={$parent['album_id']}" seotitle="{$parent['album_name_seo']}" template="viewalbum" base="public"}">{$this->_lang}</a></li>
		</if>
	</foreach>
</ol>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: miniLatestCommentBlock
//===========================================================================
function miniLatestCommentBlock($images, $title='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse striping="recentComments" classes="row1,row2"}
<div class='general_box'>
	<if test="hasTitle:|:$title"><h3>{$title}</h3></if>
	
	<ul class='ipsList_withmediumphoto ipsPad_half'>
		<foreach loop="imagesLoop:$images as $i => $image">
			<li class='{parse striping="recentComments"} clear ipsPad_half'>
				<span class='left' data-tooltip="{$image['_commentShort']}">{parse gallery_resize="$image['thumb']" width="thumb_small" class="ipsUserPhoto"}</span>
				<div class='list_content ipsPad_left'>
					<strong><a href='{parse url="app=gallery&amp;image={$image['id']}" seotitle="{$image['caption_seo']}" template="viewimage" base="public"}'>{$image['caption']}</a></strong>
					<br />
					<div class='ipsPad_top_bottom_half desc'>
						{$image['_commentShort']}
					</div>
					<div class='ipsType_small lighter blendlinks desc'>
						<if test="hasMember:|:$image['_commentAuthor']">{parse template="userHoverCard" group="global" params="$image['_commentAuthor']"} {$this->lang->words['on']} </if>{parse date="$image['lastcomment']" format="short"}
					</div>
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
// Name: newAlbumDialogue
//===========================================================================
function newAlbumDialogue($data=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form action="{$this->settings['base_url']}" method="post" name="theForm">
	<input type="hidden" name="app" value="core" />
	<input type="hidden" name="module" value="usercp" />
	<input type="hidden" name="tab" value="gallery" />
	<input type="hidden" name="area" value="albums" />
	<input type="hidden" name="do" value="{$data['type_op']}" />
	<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
	{$data['hiddens']}
	{$data['errors']}
	
	<h3>{$this->lang->words['new_album']}</h3>
	<fieldset class='row1'>
		<ul>
			<li class='field'>
				<label for='name'>{$this->lang->words['album_name']}</label>
				<input type="text" size="40" maxlength="60" name="album_name" id='album_name' value="{$data['album_name']}" class='input_text' />
			</li>
			<li class='field'>
				<label for='desc'>{$this->lang->words['album_desc']}</label>
				<textarea name='album_description' rows='5' cols='60' style='width:95%' id='album_description' class='input_text'>{$data['album_description']}</textarea>
			</li>
			<li class='field'>
				<label for='parent'>{$this->lang->words['parent_album']}</label>
				<input type='hidden' id='album_parent_id' name='album_parent_id' value='{$data['_parent']['album_id']}' /><div class='albumSelected' id='asDiv'>{$data['_parent']['album_name']}</div>
				<a href='#' class='ipsButton_secondary' data-album-selector-auto-update='{"field": "album_parent_id", "div": "asDiv"}' data-album-selector='type=createMembersAlbum&album_id={$data['_parent']['album_id']}'>{$this->lang->words['as_select_album']}</a>
			</li>
			<li class='field'>
				<select name="album_sort_options__key" id='album_sort_options__key'>
					<foreach loop="array('idate','views','comments','rating', 'name' ) as $field">
						<option value="{$field}" <if test="$album['album_sort_options__key'] == 'idate'">selected='selected'</if>>{$this->lang->words['album_sort_'.$field]}</option>
					</foreach>
				</select>
				<select name="album_sort_options__dir" id='album_sort_options__dir'>
					<foreach loop="array('asc', 'desc' ) as $field">
						<option value="{$field}" <if test="$album['album_sort_options__dir'] == 'asc'">selected='selected'</if>>{$this->lang->words['album_sort_'.$field]}</option>
					</foreach>
				</select>
			</li>
			<if test="canPrivate:|:$this->memberData['g_album_private']">
			<li class='field checkbox'>
				<input type="radio" name="album_is_public" id='album_is_profile' value="0" class='input_check' <if test="$data['album_is_profile']">checked='checked'</if> />
				<label for='album_is_profile'>{$this->lang->words['private_album']}</label>
				<span class='desc'>{$this->lang->words['new_alb_prv_desc']}</span>
			</li>
			</if>
			<li class='field checkbox'>
				<input type="radio" name="album_is_public" id='album_is_public' value="1" class='input_check' <if test="$data['album_is_public'] == 1">checked='checked'</if> />
				<label for='album_is_public'>{$this->lang->words['album_public_frm']}</label>
				<span class='desc'>{$this->lang->words['album_public_chk']}</span>
			</li>
			<li class='field checkbox'>
				<input type="radio" name="album_is_public" id='album_is_friends' value="2" class='input_check' <if test="$data['album_is_public'] == 2">checked='checked'</if> />
				<label for='album_is_friends'>{$this->lang->words['album_friend_only_frm']}</label>
				<span class='desc'>{$this->lang->words['album_friend_only_chk']}</span>
			</li>
			<li class='field checkbox'>
				<input type='checkbox' value='1' class='input_check' name='album_detail_default' id='album_detail_default' />
				<label for='album_detail_default'>{$this->lang->words['album_detail_default']}</label>
				<span class='desc'>{$this->lang->words['album_detail_default_frm']}</span>
			</li>
			<li class='field checkbox' id='parentAlbumWatermark' style='display:none;'>
				<input type='checkbox' value='1' class='input_check' name='album_watermark' id='album_watermark' />
				<label for='album_watermark'>{$this->lang->words['album_watermark_check']}</label>
				<span class='desc'>{$this->lang->words['album_watermark_check_desc']}</span>
			</li>
		</ul>
	</fieldset>
	<fieldset class='submit' id='fieldset_aSubmit'>
		<input type='submit' value="{$this->lang->words['save_ucfirst']}" class='input_submit _aSubmit' /> {$this->lang->words['or']} <a href='{parse url="app=core&amp;module=usercp&amp;tab=gallery" base="public"}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
	</fieldset>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: slideShow
//===========================================================================
function slideShow($album, $imageIds, $imageData, $lastID) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="slideshow"}
<script type='text/javascript'>
	/* URL to full-size images */
<if test="$this->settings['gallery_web_accessible'] == 'yes'">
	ipb.slideshow.imageURL = "{$this->settings['gallery_images_url']}/";
<else />
	ipb.slideshow.imageURL = "{parse url="app=gallery&module=images&section=img_ctrl" base="public"}";
</if>
	/* The image ID's in order to be displayed */
	var IMAGES = [ $imageIds ];
	/* Array of image data, order doesn't matter. Key is image ID to match above array */
	var IMAGE_DATA = \$H({
<foreach loop="$imageData as $r">
						{$r['id']}: { 	
										'title': '{$r['caption']}',
<if test="$this->settings['gallery_web_accessible'] == 'yes'">
						 				'filename': '{$r['directory']}/{$r['masked_file_name']}',
										'thumb': '{$r['directory']}/tn_{$r['masked_file_name']}',
<else />
										'filename': '&img={$r['id']}',
										'thumb': '&img={$r['id']}&tn=1',
</if>
										'author': {
													'id': {$r['member_id']},
													'name': '{$r['members_display_name']}',
													'photo': '{$r['_photo']['pp_small_photo']}',
													'width': '{$r['_photo']['pp_small_width']}',
													'height': '{$r['_photo']['pp_small_height']}'
												}
									}<if test="$lastID != $r['id']">,</if>
</foreach>
						});
	/* URL to where user photos will be */
	ipb.slideshow.userPhotoURL = "";
	/* Just templates */
	ipb.slideshow.userInfo = new Template("<div id='info_#{id}' class='info' style='display: none'><img src='#{photo}' width='#{width}' height='#{height}' class='photo' /><div class='info_body'><h1>#{title}</h1><h2>{$this->lang->words['by_ucfirst']} #{name}</h2></div>");
	ipb.slideshow.thumbnail = new Template("<div id='thumb_#{id}' class='thumb'></div>");
</script>
<div id='slideshow'>
	
	<div id='image_info'></div>
	<div id='thumbnail_bar'>
		<div id='thumbnails'>
			<div id='thumbnail_wrap'></div>
		</div>
		<div id='button_left'></div>
		<div id='button_right'></div>
	</div>
	<div id='image_holder'></div>
	<div id='slideshow_controls'>
		<div id='controls'><a href='#' id='c_PREV' class='control' title='{$this->lang->words['previous_image']}'>&nsbp;</a><a href='#' id='c_PAUSE_PLAY' class='control' title='{$this->lang->words['ss_playpause']}'>&nsbp;</a><a href='#' id='c_NEXT' class='control' title='{$this->lang->words['ss_next_img']}'>&nsbp;</a></div>
		<div id='loading'></div>
	</div>
	<a href='{parse url="app=gallery&amp;album={$album['album_id']}" seotitle="{$album['album_name_seo']}" template="viewalbum" base="public"}' id='close_slideshow'>{$this->lang->words['ss_back']}</a>
</div>
<div style='display: none'>
	<img src='{$this->settings['img_url']}/gallery/slideshow/next.png' />
	<img src='{$this->settings['img_url']}/gallery/slideshow/next_hover.png' />
	<img src='{$this->settings['img_url']}/gallery/slideshow/prev.png' />
	<img src='{$this->settings['img_url']}/gallery/slideshow/prev_hover.png' />
	<img src='{$this->settings['img_url']}/gallery/slideshow/pause.png' />
	<img src='{$this->settings['img_url']}/gallery/slideshow/play.png' />
	<img src='{$this->settings['img_url']}/gallery/slideshow/pause_hover.png' />
	<img src='{$this->settings['img_url']}/gallery/slideshow/play_hover.png' />
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: userCpAlbumIndexView
//===========================================================================
function userCpAlbumIndexView($rows="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse template="galleryCss" group="gallery_global" params=""}
<if test="canIuploadDearSirWellCanI:|:$this->registry->gallery->helper('albums')->canCreate()">
	<ul class='topic_buttons'>
		<li><a href='{parse url="app=gallery&amp;module=post&amp;section=image" base="public"}'>{$this->lang->words['container_noup_title']}</a></li>
	</ul>
</if>
<h3 class='ipsType_subtitle ipsSettings_pagetitle'>{$this->lang->words['your_albums']}</h3>
<br class='clear' />
<fieldset class='row1'>
	<if test="hasalbums:|:count( $rows )">
		{parse js_module="gallery"}
		<table class='ipb_table'>
			{parse striping="albums" classes="row2,row1"}
			<foreach loop="albums:$rows as $data">
				<tr class='{parse striping="albums"}'>
					<td class='col_c_icon'>{parse gallery_resize="$data['thumb']" width="thumb_small"}</td>
					<td>
						<if test="showAlbumOptions:|:$this->registry->gallery->helper('albums')->isUploadable($data) || $this->registry->gallery->helper('albums')->isOwner($data) || $this->registry->gallery->helper('albums')->canModerate($data)">
							<ul class='ipsList_inline right'>
								<if test="canUploadToAlbum:|:$this->registry->gallery->helper('albums')->canEdit($data)">
									<li><a href='{parse url="app=gallery&amp;module=post&amp;section=image&amp;album_id={$data['album_id']}" base="public"}' title='{$this->lang->words['upload']}' class='ipsButton_secondary'>{parse replacement="gallery_image"}</a></li>
								</if>
								<if test="canUploadToAlbum:|:$this->registry->gallery->helper('albums')->isUploadable($data)">
									<li><a href='{parse url="app=gallery&amp;albumedit={$data['album_id']}" base="public" template="editalbum" seotitle="{$data['album_name_seo']}"}' class='ipsButton_secondary' title='{$this->lang->words['edit']}'>{parse replacement="galery_album_edit"}</a></li>
								</if>
								<if test="canUploadToAlbum:|:$this->registry->gallery->helper('albums')->canDelete($data)">
									<li><a href="javascript:void(0);" album-id="{$data['album_id']}" class="ipsButton_secondary _albumDelete" title='{$this->lang->words['delete_link']}'>{parse replacement="gallery_album_delete"}</a></li>
								</if>
							</ul>
						</if>
						<if test="albumIsFriendOnly:|:$this->registry->gallery->helper('albums')->isFriends($data)">
							<span class='ipsBadge ipsBadge_green reset_cursor' data-tooltip='{$this->lang->words['friend_only_prefix_desc']}' title='{$this->lang->words['friend_only_prefix_desc']}'>{$this->lang->words['friend_only_prefix']}</span>&nbsp;
						</if>
						<if test="albumIsPrivate:|:$this->memberData['g_album_private'] && $this->registry->gallery->helper('albums')->isPrivate($data)">
							<span class='ipsBadge ipsBadge_red reset_cursor'>{$this->lang->words['private_ucfirst']}</span>&nbsp;
						</if>
						<strong>{$data['depthString']}<a href='{parse url="app=gallery&amp;album={$data['album_id']}" base="public" template="viewalbum" seotitle="{$data['album_name_seo']}"}'>{$data['album_name']}</a></strong>
						
						<if test="hasDescription:|:$data['album_description']">
							<div class='album_desc'>
								{parse expression="IPSText::truncate( strip_tags( IPSText::getTextClass('bbcode')->stripAllTags( $data['album_description'] ), '<br />' ), 200 )"}
							</div>
						</if>
					</td>
					<td class='col_c_post desc'>
						<strong>{$data['album_count_imgs']}<if test="$data['mod_images']"> <em class='moderated inline-moderated'>(+ {$data['mod_images']} {$this->lang->words['queued_suffix']})</em></if></strong> {$this->lang->words['images']}<br />
						<strong>{$data['album_count_comments']}<if test="$data['mod_comments']"> <em class='moderated inline-moderated'>(+ {$data['mod_comments']} {$this->lang->words['queued_suffix']})</em></if></strong> {$this->lang->words['comments']}
						<if test="albumIsUploadable:|:$this->registry->gallery->helper('albums')->isUploadable($data)">
							<br /><br />{$this->lang->words['last_upload']}: {parse date="$data['album_last_img_date']" format="DATE"}
						</if>
					</td>
				</tr>
			</foreach>
		</table>
	<else />
		<p class='no_messages'>{$this->lang->words['no_album']}</p>
	</if>
</fieldset>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>