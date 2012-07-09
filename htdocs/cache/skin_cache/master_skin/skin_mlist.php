<?php
/**
 * Master skin file
 * Written: Mon, 09 Jul 2012 18:16:42 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: member_list_show
//===========================================================================
function member_list_show($members, $pages="", $dropdowns=array(), $defaults=array(), $custom_fields=null, $url='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<script type='text/javascript' src='{$this->settings['public_dir']}js/3rd_party/calendar_date_select/calendar_date_select.js'></script>
{parse js_module="memberlist"}
<!-- SEARCH FORM -->
<h1 class='ipsType_pagetitle'>{$this->lang->words['mlist_header']}</h1>
<div class='topic_controls'>
	{$pages}	
	<ul class='topic_buttons'>
		<li><a href='#filters' title='{$this->lang->words['mlist_adv_filt']}' id='use_filters'>{$this->lang->words['mlist_adv_filt']}</a></li>
	</ul>
</div>
<div id='member_filters' class='general_box alt clear'>
	<form action="{parse url="app=members&amp;module=list" base="public" seotitle="false"}" method="post">
		<h3 class='bar'>{$this->lang->words['mlist_adv_filt_opt']}</h3>
	
		<ul id='filters_1'>
			<li class='field'>
				<label for='member_name'>{$this->lang->words['s_name']}</label>
				<select name="name_box" class='input_select'>
					<option value="begins"<if test="namebox_begins:|:$this->request['name_box'] == 'begins'"> selected='selected'</if>>{$this->lang->words['s_begins']}</option>
					<option value="contains"<if test="namebox_contains:|:$this->request['name_box'] == 'contains'"> selected='selected'</if>>{$this->lang->words['s_contains']}</option>
				</select>&nbsp;&nbsp;
				<input type="text" size="15" name="name" id='member_name' class='input_text' value="{parse expression="urldecode($this->request['name'])"}" />
			</li>
			<li class='field'>
				<label for='photo_only'>{$this->lang->words['photo_only']}</label>
				<input class='input_check' id='photo_only' type="checkbox" value="1" name="photoonly" <if test="photoonly:|:$defaults['photoonly']">checked='checked'</if> />
			</li>
			<if test="canFilterRate:|:$this->settings['pp_allow_member_rate']">
				<li class='field'>
					<label for='rating'>{$this->lang->words['m_rating_morethan']}</label>
					<select name='pp_rating_real' id='rating'>
						<option value='0'<if test="rating0:|:! $this->request['pp_rating_real']"> selected='selected'</if>>0</option>
						<option value='1'<if test="rating1:|:$this->request['pp_rating_real'] == 1"> selected='selected'</if>>1</option>
						<option value='2'<if test="rating2:|:$this->request['pp_rating_real'] == 2"> selected='selected'</if>>2</option>
						<option value='3'<if test="rating3:|:$this->request['pp_rating_real'] == 3"> selected='selected'</if>>3</option>
						<option value='4'<if test="rating4:|:$this->request['pp_rating_real'] == 4"> selected='selected'</if>>4</option>
					</select>
					{$this->lang->words['m_stars']}
				</li>
			</if>
			<if test="hascfields:|:count( $custom_fields->out_fields )">
				<foreach loop="customfields:$custom_fields->out_fields as $id => $field">
					<li class='field custom'>
						<label for='field_{$id}'>{$custom_fields->field_names[$id]}</label>
						{$field}
					</li>
				</foreach>
			</if>			
		</ul>
		<ul id='filters_2'>
			<li class='field'>
				<label for='signature'>{$this->lang->words['s_signature']}</label>
				<input type="text" class='input_text' size="28" id='signature' name="signature" value="{parse expression="urldecode($this->request['signature'])"}" />
			</li>
			<li class='field'>
				<label for='posts'>{$this->lang->words['s_posts']}</label>
				<select class="dropdown" name="posts_ltmt">
					<option value="lt"<if test="posts_ltmt_lt:|:$this->request['posts_ltmt'] == 'lt'"> selected='selected'</if>>{$this->lang->words['s_lessthan']}</option>
					<option value="mt"<if test="posts_ltmt_mt:|:$this->request['posts_ltmt'] == 'mt'"> selected='selected'</if>>{$this->lang->words['s_morethan']}</option>
				</select>
				&nbsp;<input type="text" class='input_text' id='posts' size="15" name="posts" value="{$this->request['posts']}" />
			</li>
			<li class='field'>
				<label for='joined'>{$this->lang->words['s_joined']}</label>
				<select class="dropdown" name="joined_ltmt">
					<option value="lt"<if test="joined_ltmt_lt:|:$this->request['joined_ltmt'] == 'lt'"> selected='selected'</if>>{$this->lang->words['s_before']}</option>
					<option value="mt"<if test="joined_ltmt_mt:|:$this->request['joined_ltmt'] == 'mt'"> selected='selected'</if>>{$this->lang->words['s_after']}</option>
				</select>
				&nbsp;<input type="text" class='input_text' id='joined' size="10" name="joined" value="{$this->request['joined']}" /> <img src='{$this->settings['img_url']}/date.png' alt='{$this->lang->words['generic_date']}' id='joined_date_icon' class='clickable' /> 
				<span class="desc">{$this->lang->words['s_dateformat']}</span>
			</li>
			<li class='field'>
				<label for='last_post'>{$this->lang->words['s_lastpost']}</label>
				<select class="dropdown" name="lastpost_ltmt">
					<option value="lt"<if test="lastpost_ltmt_lt:|:$this->request['lastpost_ltmt'] == 'lt'"> selected='selected'</if>>{$this->lang->words['s_before']}</option>
					<option value="mt"<if test="lastpost_ltmt_mt:|:$this->request['lastpost_ltmt'] == 'mt'"> selected='selected'</if>>{$this->lang->words['s_after']}</option>
				</select>
				&nbsp;<input type="text" class='input_text' id='last_post' size="10" name="lastpost" value="{$this->request['lastpost']}" /> <img src='{$this->settings['img_url']}/date.png' alt='{$this->lang->words['generic_date']}' id='last_post_date_icon' class='clickable' /> 
				<span class="desc">{$this->lang->words['s_dateformat']}</span>
			</li>
			<li class='field'>
				<label for='last_visit'>{$this->lang->words['s_lastvisit']}</label>
				<select class="dropdown" name="lastvisit_ltmt">
					<option value="lt"<if test="lastvisit_ltmt_lt:|:$this->request['lastvisit_ltmt'] == 'lt'"> selected='selected'</if>>{$this->lang->words['s_before']}</option>
					<option value="mt"<if test="lastvisit_ltmt_mt:|:$this->request['lastvisit_ltmt'] == 'mt'"> selected='selected'</if>>{$this->lang->words['s_after']}</option>
				</select>
				&nbsp;<input type="text" class='input_text' id='last_visit' size="10" name="lastvisit" value="{$this->request['lastvisit']}" /> <img src='{$this->settings['img_url']}/date.png' alt='{$this->lang->words['generic_date']}' id='last_visit_date_icon' class='clickable' />
				<span class="desc">{$this->lang->words['s_dateformat']}</span>
			</li>			
		</ul>
		<fieldset class='other_filters row2 altrow'>
			<select name='filter' class='input_select'>
				<foreach loop="filter:$dropdowns['filter'] as $k => $v">
					<option value='{$k}'<if test="filterdefault:|:$k == $defaults['filter']"> selected='selected'</if>>{$v}</option>
				</foreach>
			</select>
			{$this->lang->words['sorting_text_by']}
			<select name='sort_key' class='input_select'>
				<foreach loop="sort_key:$dropdowns['sort_key'] as $k => $v">
					<option value='{$k}'<if test="sortdefault:|:$k == $defaults['sort_key']"> selected='selected'</if>>{$this->lang->words[ $v ]}</option>
				</foreach>
			</select>
			{$this->lang->words['sorting_text_in']}
			<select name='sort_order' class='input_select'>
				<foreach loop="sort_order:$dropdowns['sort_order'] as $k => $v">
					<option value='{$k}'<if test="orderdefault:|:$k == $defaults['sort_order']"> selected='selected'</if>>{$this->lang->words[ $v ]}</option>
				</foreach>
			</select>
			{$this->lang->words['sorting_text_with']}
			<select name='max_results' class='input_select'>
				<foreach loop="max_results:$dropdowns['max_results'] as $k => $v">
					<option value='{$k}'<if test="limitdefault:|:$k == $defaults['max_results']"> selected='selected'</if>>{$v}</option>
				</foreach>
			</select>
			{$this->lang->words['sorting_text_results']}
		</fieldset>
		<fieldset class='submit clear'>
			<input type="submit" value="{$this->lang->words['sort_submit']}" class="input_submit" /> {$this->lang->words['or']} <a href='#j_memberlist' title='{$this->lang->words['cancel']}' id='close_filters' class='cancel'>{$this->lang->words['cancel']}</a>
		</fieldset>
	</form>
</div>
<script type='text/javascript'>
	$('member_filters').hide();
</script>
<br />
<div class='ipsBox ipsVerticalTabbed ipsLayout ipsLayout_withleft ipsLayout_tinyleft clearfix'>
	<div class='ipsVerticalTabbed_tabs ipsVerticalTabbed_minitabs ipsLayout_left' id='mlist_tabs'>
		<ul>
			<if test="letterquickjump:|:!$this->request['quickjump']">
				<li class='active'><a href='{parse url="app=members&amp;module=list" template="members_list" base="public" seotitle="false"}' title='{$this->lang->words['members_start_with']}{$letter}'>{$this->lang->words['mlist_view_all_txt']}</a></li>
			<else />
				<li><a href='{parse url="app=members&amp;module=list" template="members_list" base="public" seotitle="false"}' title='{$this->lang->words['mlist_view_all_title']}'>{$this->lang->words['mlist_view_all_txt']}</a></li>
			</if>
			<foreach loop="chars:range(65,90) as $char">
				<if test="letterdefault:|:$letter = strtoupper(chr($char))">
					<li <if test="selected:|:strtoupper( $this->request['quickjump'] ) == $letter">class='active'</if>><a href='{parse url="{$url}&amp;quickjump={$letter}" template="members_list" base="public" seotitle="false"}' title='{$this->lang->words['mlist_view_start_title']} {$letter}'>{$letter}</a></li>
				</if>
			</foreach>
		</ul>
	</div>
	<div class='ipsVerticalTabbed_content ipsLayout_content'>
		<div class='maintitle ipsFilterbar clear clearfix'>
			<ul class='ipsList_inline left'>
				<li <if test="filtermembers:|:$this->request['sort_key'] == 'members_display_name' || !$this->request['sort_key']">class='active'</if>>
					<a href='{parse url="app=members&amp;module=list&amp;{$url}&amp;sort_key=members_display_name&amp;sort_order=asc" template="members_list" base="public" seotitle="false"}' title='{$this->lang->words['sort_by_mname']}'>{$this->lang->words['sort_by_name']}</a>
				</li>
				<li <if test="filterposts:|:$this->request['sort_key'] == 'posts'">class='active'</if>>
					<a href='{parse url="app=members&amp;module=list&amp;{$url}&amp;sort_key=posts&amp;sort_order=desc" template="members_list" base="public" seotitle="false"}' title='{$this->lang->words['sort_by_posts']}'>{$this->lang->words['pcount']}</a>
				</li>
				<li <if test="filterjoined:|:$this->request['sort_key'] == 'joined'">class='active'</if>>
					<a href='{parse url="app=members&amp;module=list&amp;{$url}&amp;sort_key=joined" template="members_list" base="public" seotitle="false"}' title='{$this->lang->words['sorty_by_jdate']}'>{$this->lang->words['sort_by_joined']}</a>
				</li>
			</ul>
		</div>
		<div class='ipsBox_container ipsPad' id='mlist_content'>
			<ul class='ipsMemberList'>
				<if test="showmembers:|:is_array( $members ) and count( $members )">
					{parse striping="memberStripe" classes="row1,row2"}
					<foreach loop="members:$members as $member">
						<li id='member_id_{$member['member_id']}' class='ipsPad clearfix member_entry {parse striping="memberStripe"}'>
							<a href='{parse url="showuser={$member['member_id']}" template="showuser" seotitle="{$member['members_seo_name']}" base="public"}' title='{$this->lang->words['view_profile']}' class='ipsUserPhotoLink left'><img src='{$member['pp_small_photo']}' alt="{parse expression="sprintf($this->lang->words['users_photo'],$member['members_display_name'])"}" class='ipsUserPhoto ipsUserPhoto_medium' /></a>
							<div class='ipsBox_withphoto'>
								<ul class='ipsList_inline right'>
									<if test="weAreSupmod:|:$this->memberData['g_is_supmod'] == 1 && $member['member_id'] != $this->memberData['member_id']">
										<li><a href='{parse url="app=core&amp;module=modcp&amp;do=editmember&amp;auth_key={$this->member->form_hash}&amp;mid={$member['member_id']}&amp;pf={$member['member_id']}" base="public"}' class='ipsButton_secondary'>{$this->lang->words['edit_member']}</a></li>
									</if>
									<if test="notus:|:$this->memberData['member_id'] AND $this->memberData['member_id'] != $member['member_id'] && $this->settings['friends_enabled'] AND $this->memberData['g_can_add_friends']">
										<if test="addfriend:|:IPSMember::checkFriendStatus( $member['member_id'] )">
											<li class='mini_friend_toggle is_friend' id='friend_mlist_{$member['member_id']}'><a href='{parse url="app=members&amp;module=list&amp;module=profile&amp;section=friends&amp;do=remove&amp;member_id={$member['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['remove_friend']}' class='ipsButton_secondary'>{parse replacement="remove_friend"}</a></li>
										<else />
											<li class='mini_friend_toggle is_not_friend' id='friend_mlist_{$member['member_id']}'><a href='{parse url="app=members&amp;module=list&amp;module=profile&amp;section=friends&amp;do=add&amp;member_id={$member['member_id']}&amp;secure_key={$this->member->form_hash}" base="public"}' title='{$this->lang->words['add_friend']}' class='ipsButton_secondary'>{parse replacement="add_friend"}</a></li>								
										</if>
									</if>
									<if test="sendpm:|:$this->memberData['g_use_pm'] AND $this->memberData['members_disable_pm'] == 0 AND IPSLib::moduleIsEnabled( 'messaging', 'members' ) && $member['member_id'] != $this->memberData['member_id']">
										<li class='pm_button' id='pm_xxx_{$member['pp_member_id']}'><a href='{parse url="app=members&amp;module=list&amp;module=messaging&amp;section=send&amp;do=form&amp;fromMemberID={$member['pp_member_id']}" base="public"}' title='{$this->lang->words['pm_member']}' class='ipsButton_secondary'>{parse replacement="send_msg"}</a></li>
									</if>
									<li><a href='{parse url="app=core&amp;module=search&amp;do=user_activity&amp;mid={$member['member_id']}" base="public"}' title='{$this->lang->words['gbl_find_my_content']}' class='ipsButton_secondary'>{parse replacement="find_topics_link"}</a></li>
									<if test="blog:|:$member['has_blog'] AND IPSLib::appIsInstalled( 'blog' )">
										<li><a href='{parse url="app=blog&amp;module=display&amp;section=blog&amp;mid={$member['member_id']}" base="public"}' title='{$this->lang->words['view_blog']}' class='ipsButton_secondary'>{parse replacement="blog_link"}</a></li>
									</if>
									<if test="gallery:|:$member['has_gallery'] AND IPSLib::appIsInstalled( 'gallery' )">
										<li><a href='{parse url="app=gallery&amp;user={$member['member_id']}" template="useralbum" seotitle="{$member['members_seo_name']}" base="public"}' title='{$this->lang->words['view_gallery']}' class='ipsButton_secondary'>{parse replacement="gallery_link"}</a></li>
									</if>
								</ul>
								
								<h3 class='ipsType_subtitle'>
									<strong><a href='{parse url="showuser={$member['member_id']}" template="showuser" seotitle="{$member['members_seo_name']}" base="public"}' title='{$this->lang->words['view_profile']}'>{$member['members_display_name']}</a></strong>
									
									<if test="rating:|:$this->settings['pp_allow_member_rate'] && $this->request['pp_rating_real']">
										<span class='rating'> 
											<if test="rate1:|:$member['pp_rating_real'] >= 1">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if><if test="rate2:|:$member['pp_rating_real'] >= 2">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if><if test="rate3:|:$member['pp_rating_real'] >= 3">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if><if test="rate4:|:$member['pp_rating_real'] >= 4">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if><if test="rate5:|:$member['pp_rating_real'] >= 5">{parse replacement="rate_on"}<else />{parse replacement="rate_off"}</if>
										</span>
									</if>
								</h3>
								<if test="repson:|:$this->settings['reputation_enabled'] && $this->settings['reputation_show_profile'] && $member['pp_reputation_points'] !== null">
									<if test="norep:|:$member['pp_reputation_points'] == 0 || !$member['pp_reputation_points']">
										<p class='reputation zero ipsType_small left' data-tooltip="{parse expression="sprintf( $this->lang->words['member_has_x_rep'], $member['members_display_name'], $member['pp_reputation_points'] )"}">
									</if>
									<if test="posrep:|:$member['pp_reputation_points'] > 0">
										<p class='reputation positive ipsType_small left' data-tooltip="{parse expression="sprintf( $this->lang->words['member_has_x_rep'], $member['members_display_name'], $member['pp_reputation_points'] )"}">
									</if>
									<if test="negrep:|:$member['pp_reputation_points'] < 0">
										<p class='reputation negative ipsType_small left' data-tooltip="{parse expression="sprintf( $this->lang->words['member_has_x_rep'], $member['members_display_name'], $member['pp_reputation_points'] )"}">
									</if>							
											<span class='number'>{$member['pp_reputation_points']}</span>
										</p>
								</if>
								<span class='desc'>
									{$this->lang->words['member_joined']} {parse date="$member['joined']" format="joined"}<br />
									{IPSMember::makeNameFormatted( $member['group'], $member['member_group_id'] )} &middot;
									<if test="filterViews:|:$this->request['sort_key'] == 'members_profile_views'">
										{parse format_number="$member['members_profile_views']"} {$this->lang->words['m_views']}
									<else />
										{parse format_number="$member['posts']"} {$this->lang->words['member_posts']}
									</if>
								</span>
							</div>
						</li>						
					</foreach>
				<else />
					<li class='no_messages'>
						{$this->lang->words['no_results']}
					</li>
				</if>
			</ul>
		</div>
	</div>
</div>
<script type='text/javascript'>
	$("mlist_content").setStyle( { minHeight: $('mlist_tabs').measure('margin-box-height') + 5 + "px" } );
</script>
{$pages}
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>