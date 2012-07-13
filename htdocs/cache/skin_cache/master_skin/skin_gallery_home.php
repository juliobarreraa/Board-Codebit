<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: browse
//===========================================================================
function browse($children, $album, $pages, $stats) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<ul class='topic_buttons'>
	<if test="canIuploadDearSirWellCanI:|:$this->registry->gallery->helper('albums')->canCreate()">
		<li><a href='{parse url="app=gallery&amp;module=post&amp;section=image" base="public"}'>{$this->lang->words['upload']}</a></li>
	</if>
	<if test="$this->settings['gallery_enable_both_views']">
		<li class='non_button'>
			<if test="browseIsDefault:|:$this->settings['gallery_default_view'] == 'browse' && empty( $this->request['home'] )">
				<a href='{parse url="app=gallery&amp;home=portal" seotitle="false" template="galleryportal" base="public"}'>{$this->lang->words['home_ucfirst']}</a>
			<else />
				<a href='{parse url="app=gallery" seotitle="false" template="app=gallery" base="public"}'>{$this->lang->words['home_ucfirst']}</a>
			</if>
		</li>
	</if>
</ul>
<h1 class='ipsType_pagetitle'><if test="hasName:|:$album['album_name']">{$album['album_name']}<else />{IPSLib::getAppTitle('gallery')}</if></h1>
<br />
<div class='maintitle ipsFilterbar'>
	<ul class='ipsList_inline' style='display:inline'>
		<li <if test="( $this->request['sort_key'] == 'date' || !$this->request['sort_key'] ) && ( $this->request['sort_order'] == 'desc' || !$this->request['sort_order'] )">class='active'</if>>
			<a href='{parse url="app=gallery&amp;browseAlbum={$album['album_id']}&amp;dosort=1&amp;sort_key=date&amp;sort_order=desc&amp;filter_key={$this->request['filter_key']}" seotitle="{$album['album_name_seo']}" template="browsealbum"  base="public"}'>{$this->lang->words['filter_most_recent']}</a>
		</li>
		<li <if test="$this->request['sort_key'] == 'rated' && $this->request['sort_order'] == 'desc'">class='active'</if>>
			<a href='{parse url="app=gallery&amp;browseAlbum={$album['album_id']}&amp;dosort=1&amp;sort_key=rated&amp;sort_order=desc&amp;filter_key={$this->request['filter_key']}" seotitle="{$album['album_name_seo']}" template="browsealbum" base="public"}'>{$this->lang->words['filter_highest_rated']}</a>
		</li>
		<li <if test="$this->request['sort_key'] == 'images' && $this->request['sort_order'] == 'desc'">class='active'</if>>
			<a href='{parse url="app=gallery&amp;browseAlbum={$album['album_id']}&amp;dosort=1&amp;sort_key=images&amp;sort_order=desc&amp;filter_key={$this->request['filter_key']}" seotitle="{$album['album_name_seo']}" template="browsealbum" base="public"}'>{$this->lang->words['filter_most_images']}</a>
		</li>
		<li <if test="$this->request['sort_key'] == 'comments' && $this->request['sort_order'] == 'desc'">class='active'</if>>
			<a href='{parse url="app=gallery&amp;browseAlbum={$album['album_id']}&amp;dosort=1&amp;sort_key=comments&amp;sort_order=desc&amp;filter_key={$this->request['filter_key']}" seotitle="{$album['album_name_seo']}" template="browsealbum" base="public"}'>{$this->lang->words['filter_most_comments']}</a>
		</li>
	</ul>
	<ul class='ipsList_inline right' style='display:inline'>
		<li <if test="$this->request['filter_key'] == 'all' || !$this->request['filter_key']">class='active'</if>>
			<a href='{parse url="app=gallery&amp;browseAlbum={$album['album_id']}&amp;dosort=1&amp;sort_key=date&amp;sort_order=desc&amp;filter_key=all" seotitle="{$album['album_name_seo']}" template="browsealbum" base="public"}'>{$this->lang->words['filter_show_all']}</a>
		</li>
		<li <if test="$this->request['filter_key'] == 'global'">class='active'</if>>
			<a href='{parse url="app=gallery&amp;browseAlbum={$album['album_id']}&amp;dosort=1&amp;sort_key=date&amp;sort_order=desc&amp;filter_key=global" seotitle="{$album['album_name_seo']}" template="browsealbum" base="public"}'>{$this->lang->words['filter_show_global']}</a>
		</li>
		<li <if test="$this->request['filter_key'] == 'member'">class='active'</if>>
			<a href='{parse url="app=gallery&amp;browseAlbum={$album['album_id']}&amp;dosort=1&amp;sort_key=date&amp;sort_order=desc&amp;filter_key=member" seotitle="{$album['album_name_seo']}" template="browsealbum" base="public"}'>{$this->lang->words['filter_show_member']}</a>
		</li>
	</ul>
</div>
<if test="catnorows:|:!count($children)">
	<div class='no_messages'>
		{$this->lang->words['albums_none_to_see']}
	</div>
<else />
	<div class='ipsBox'>
		<div class='ipsBox_container'>
			<table class='ipb_table'>
				{parse striping="browseRow" classes="row1,row2 altrow"}
				<foreach loop="albumsLoop:$children as $data">
					<tr class='{parse striping="browseRow"}'>
						<td class='col_c_icon'>{parse gallery_resize="$data['thumb']" width="thumb_medium"}</td>
						<td>
							<if test="showAlbumOptions:|:$this->registry->gallery->helper('albums')->isUploadable($data) || $this->registry->gallery->helper('albums')->isOwner($data) || $this->registry->gallery->helper('albums')->canModerate($data)">
								<ul class='ipsList_inline right'>
									<if test="canUploadToAlbum:|:$this->registry->gallery->helper('albums')->isUploadable($data)">
										<li><a href='{parse url="app=gallery&amp;module=post&amp;section=image&amp;album_id={$data['album_id']}" base="public"}' title='{$this->lang->words['upload']}' class='ipsButton_secondary'>{parse replacement="gallery_image"}</a></li>
									</if>
									<if test="canEditAlbum:|:$this->registry->gallery->helper('albums')->canEdit($data)">
										<li><a href='{parse url="app=gallery&amp;albumedit={$data['album_id']}" base="public" template="editalbum" seotitle="{$data['album_name_seo']}"}' class='ipsButton_secondary' title='{$this->lang->words['edit']}'>{parse replacement="galery_album_edit"}</a></li>
									</if>
									<if test="canDeleteAlbum:|:(!$this->registry->gallery->helper('albums')->isGlobal($data) && $this->registry->gallery->helper('albums')->canDelete($data))">
										<li><a href="javascript:void(0);" album-id="{$data['album_id']}" class="_albumDelete ipsButton_secondary" title='{$this->lang->words['delete_link']}'>{parse replacement="gallery_album_delete"}</a></li>
									</if>
								</ul>
							</if>
							<if test="albumIsFriendOnly:|:$this->registry->gallery->helper('albums')->isFriends($data)">
								<span class='ipsBadge ipsBadge_green reset_cursor' data-tooltip='{$this->lang->words['friend_only_prefix_desc']}' title='{$this->lang->words['friend_only_prefix_desc']}'>{$this->lang->words['friend_only_prefix']}</span>&nbsp;
							</if>
							<if test="albumIsPrivate:|:$this->memberData['g_album_private'] && $this->registry->gallery->helper('albums')->isPrivate($data)">
								<span class='ipsBadge ipsBadge_red reset_cursor'>{$this->lang->words['private_ucfirst']}</span>&nbsp;
							</if>
							<if test="albumIsGlobal:|:$this->request['filter_key'] != 'global' && $this->registry->gallery->helper('albums')->isGlobal($data)">
								<span class='ipsBadge ipsBadge_grey reset_cursor'>{$this->lang->words['global_prefix']}</span>&nbsp;
							</if>
							<strong>
								{$data['depthString']}
								<if test="hasLittleWeeBabbies:|:$data['_childrenCount']">
									<a href='{parse url="app=gallery&amp;album={$data['album_id']}&amp;display=detail" seotitle="{$data['album_name_seo']}" template="viewalbum" base="public"}' title='{parse expression="sprintf( $this->lang->words['view_child_albums'], $data['_childrenCount'] )"}'>{$data['album_name']}</a>
								<else />
									<a href='{parse url="app=gallery&amp;album={$data['album_id']}" base="public" template="viewalbum" seotitle="{$data['album_name_seo']}"}'>{$data['album_name']}</a>
								</if>
							</strong>
							
							<if test="hasDescription:|:$data['album_description']">
								<div class='album_desc'>
									{parse expression="IPSText::truncate( strip_tags( IPSText::getTextClass('bbcode')->stripAllTags( $data['album_description'] ), '<br />' ), 200 )"}
								</div>
							</if>
						</td>
						<td class='col_c_post desc'>
							<if test="albumIsNotContainer:|:!$this->registry->gallery->helper('albums')->isContainerOnly($data)">
								<strong>{$data['album_count_imgs']}<if test="$data['mod_images']"> <em class='moderated inline-moderated'>(+ {$data['mod_images']} {$this->lang->words['queued_suffix']})</em></if></strong> {$this->lang->words['images']}<br />
								<strong>{$data['album_count_comments']}<if test="$data['mod_comments']"> <em class='moderated inline-moderated'>(+ {$data['mod_comments']} {$this->lang->words['queued_suffix']})</em></if></strong> {$this->lang->words['comments']}
								<if test="albumIsUploadable:|:$this->registry->gallery->helper('albums')->isUploadable($data)">
									<br /><br />{$this->lang->words['last_upload']}: {parse date="$data['album_last_img_date']" format="DATE"}
								</if>
							</if>
						</td>
					</tr>
				</foreach>
			</table>
		</div>
	</div>		
</if>
<br />
<if test="$pages">
	{$pages}
	<br />
</if>
<br />
<div id='board_stats'>
	<ul class='ipsType_small ipsList_inline'>
		<li class='clear'>
			<span class='value'>{$stats['albums']}</span>
			{$this->lang->words['stats_total_albums']}
		</li>
		<li class='clear'>
			<span class='value'>{$stats['images']}</span>
			{$this->lang->words['stats_total_images']}
		</li>
		<li class='clear'>
			<span class='value'>{$stats['comments']}</span>
			{$this->lang->words['stats_total_comments']}
		</li>
		<li class='clear'>
			<span class='value'>{$stats['diskspace']}</span>
			{$this->lang->words['stats_total_size']}
		</li>
	</ul>
</div>
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: home
//===========================================================================
function home($feature, $sidebars, $albums, $pages='', $stats) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<ul class='topic_buttons'>
	<if test="canIuploadDearSirWellCanI:|:$this->registry->gallery->helper('albums')->canCreate()">
		<li><a href='{parse url="app=gallery&amp;module=post&amp;section=image" base="public"}'>{$this->lang->words['upload']}</a></li>
	</if>
	<if test="$this->settings['gallery_enable_both_views']">
		<li class='non_button'><a href='{parse url="app=gallery&amp;browseAlbum=0" seotitle="false" template="browsealbumroot" base="public"}'>{$this->lang->words['gbutton_browse']}</a></li>
	</if>
</ul>
<h1 class='ipsType_pagetitle'>{IPSLib::getAppTitle('gallery')}</h1>
<br />
<div class='ipsLayout ipsLayout_withright ipsLayout_bigright'>
	<div class='ipsLayout_right'>
		<if test="globalAlbums:|:is_array($sidebars['globalAlbums'])">
			{parse template="miniAlbumStripHorizontal" group="gallery_albums" params="$sidebars['globalAlbums'], $this->lang->words['home_active_global_albums']"}
			<br />
		</if>
		<if test="recentImages:|:$sidebars['recentImages']">
			<div class='general_box'>
				<h3>{$this->lang->words['home_recent_images']}</h3>
				<ul class='ipsPad_half short ipsList_inline ipsList_reset' id='home_side_recents'>
					<foreach loop="$sidebars['recentImages'] as $id => $image">
							<li class='gallery_tiny_box' -data-id="{$id}">
								{parse gallery_resize="$image['thumb']" width="thumb_small"}
							</li>
					</foreach>
				</ul>
			</div>
			<br />
		</if>
		<if test="activeAlbums:|:$sidebars['recentComments']">
			{parse template="miniLatestCommentBlock" group="gallery_albums" params="$sidebars['recentComments'], $this->lang->words['home_recent_comments']"}
			<br />
		</if>
		<if test="activeAlbums:|:is_array($sidebars['activeAlbums']) && count($sidebars['activeAlbums'])">
			<div class='general_box'>
				<h3>{$this->lang->words['home_active_albums']}</h3>
				<ul>
					<foreach loop="$sidebars['activeAlbums'] as $i => $album">
						<li class='{parse striping="activeAlbums"} clear'>
							<div class='album horizontal'>
								{parse gallery_resize="$album['album']['thumb']" width="thumb_small"}
								<p><a href='{parse url="app=gallery&amp;album={$album['album']['album_id']}" seotitle="{$album['album']['album_name_seo']}" template="viewalbum" base="public"}'>{$album['album']['album_name']}</a></p>
								<div style='padding-top:5px'>
									<if test="hasAnyone:|:count($album['users']) OR $album['others']">
										<span class='desc'>{$this->lang->words['browsing_ucfirst']}:</span>
										<if test="hasUsers:|:count($album['users'])">
											{parse expression="implode( ", ", $album['users'])"}
										</if>
										<if test="hasOther:|:$album['others']">
											<span class="desc">({$album['others']} {$this->lang->words['others_lower']})</span>
										</if>
									</if>
								</div>
							</div>
						</li>
					</foreach>
				</ul>
			</div>
		</if>
	</div>
	<div class='ipsLayout_content'>
		<div class='ipsBox'>
			<div class='ipsBox_container ipsPad short'>
				<if test="$feature['id']">
					<div id="image_wdesc_{$feature['id']}">
						{$feature['tag']}
						<p id="image_wdesc_{$feature['id']}_description" class='imageDescription' style="display:none;"><strong>{$feature['caption']}</strong><br />{$feature['description']}<br />{$this->lang->words['from_album']}: {$feature['album_name']}</p>
					</div>
				
					<script type="text/javascript">
						document.observe("dom:loaded", function(){
							ipb.gallery.registerDescription({$feature['id']});
						} );
					</script>
					<br />
				</if>
				<if test="hasRecentAlbums:|:is_array( $albums ) && count( $albums )">
					<a id='recentalbums'></a>
					<div class='ipsPad'>
						<h2 class='ipsType_subtitle'>{$this->lang->words['home_recent_albums']}</h2><br />
						<ul id='home_recent_albums' class='ipsList_inline ipsList_reset short'>
						<foreach loop="recentAlbums:$albums as $id => $data">
							<li>
								{parse gallery_resize="$data['thumb']" width="thumb_medium"}
								<div class='desc ipsType_smaller homepage'>
									<a href='{parse url="app=gallery&amp;album={$data['album_id']}" seotitle="{$data['album_name_seo']}" template="viewalbum" base="public"}'>{$data['album_name']}</a>
									<if test="isMember:|:$data['member_id']"><br />{$this->lang->words['by_ucfirst']}: {parse template="userHoverCard" group="global" params="$data"}<else /><br />&nbsp;</if>
								</div>
							</li>
						</foreach>
						</ul>
						<if test="hasPages:|:! empty( $pages )">
							<div class='ipsPad clearfix'>{$pages}</div>
						</if>
					</div>
				</if>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var st = {parse expression="intval($this->request['st'])"};
	if ( st > 0 )
	{
		$('recentalbums').scrollTo();
	}
</script>
<br class='clear' />
<br />
<div id='board_stats'>
	<ul class='ipsType_small ipsList_inline'>
		<li class='clear'>
			<span class='value'>{parse format_number="$stats['albums']"}</span>
			{$this->lang->words['stats_total_albums']}
		</li>
		<li class='clear'>
			<span class='value'>{parse format_number="$stats['images']"}</span>
			{$this->lang->words['stats_total_images']}
		</li>
		<li class='clear'>
			<span class='value'>{parse format_number="$stats['comments']"}</span>
			{$this->lang->words['stats_total_comments']}
		</li>
		<if test="hasImages:|:$stats['images']">
		<li class='clear'>
			<span class='value'>{$stats['diskspace']}</span>
			{$this->lang->words['stats_total_size']}
		</li>
		</if>
	</ul>
</div>
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>