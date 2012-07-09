<?php
/**
 * Master skin file
 * Written: Mon, 09 Jul 2012 18:16:42 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: basicReportForm
//===========================================================================
function basicReportForm($name="", $url="", $extra_data="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h2 class='maintitle'>{$this->lang->words['report_basic_title']}</h2>
<div class='generic_bar'></div>
<form action="{$this->settings['base_url']}app=core&amp;module=reports&amp;rcom={$this->request['rcom']}&amp;send=1" method="post" name="REPLIER">
	<input type='hidden' name='k' value='{$this->member->form_hash}' />
	<div class='ipsForm ipsForm_horizontal'>
		<fieldset>
			<h3 class='bar'>{$this->lang->words['reporting_title']} <a href="{$url}" title='{$this->lang->words['view_content']}'>{$name}</a></h3>
			<ul class='ipsPad'>
				<li class='ipsField'>
					<label for='message' class='ipsField_title'>{$this->lang->words['report_basic_enter']}</label>
					<p class='ipsField_content'>
						<textarea id='message' class='input_text' name='message' cols='60' rows='8'></textarea><br />
						<span class='desc'>{$this->lang->words['report_basic_desc']}</span>
					</p>
				</li>
				{$extra_data}
			</ul>
		</fieldset>
		<fieldset class='submit'>
			<input type="submit" class='input_submit' value="{$this->lang->words['report_basic_submit']}" accesskey='s' /> {$this->lang->words['or']} <a href='{$url}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
		</fieldset>
	</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: reportsIndex
//===========================================================================
function reportsIndex($reports=array(), $acts="", $pages="", $statuses=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="reports"}
<if test="noviewall:|:!$this->request['showall']">
	<p class='message'>{$this->lang->words['only_active_note']}  <a href='{parse url="app=core&amp;module=reports&amp;do=index&amp;showall=1" base="public"}'>{$this->lang->words['click_here_link']}</a> {$this->lang->words['to_view_allrep']}</p>
</if>
<if test="hasPages:|:$pages">
	<div class='topic_controls'>
		{$pages}
	</div>
</if>
<br class='clear' />
<form method="post" action="{$this->settings['base_url']}app=core&amp;module=reports&amp;do=process&amp;st={$this->request['st']}">
<input type='hidden' name='k' value='{$this->member->form_hash}' />
<table class='ipb_table report_center' summary='{$this->lang->words['reported_content_summary']}'>
	<caption class='maintitle'>{$this->lang->words['list_title']}</caption>
	<tr class='header'>
		<th scope='col' class='col_r_icon'>&nbsp;</th>
		<th scope='col' class='col_r_title'>{$this->lang->words['list_header_title']}</th>
		<th scope='col' class='col_r_section'>{$this->lang->words['list_header_section']}</th>
		<th scope='col' class='col_r_total short'>{$this->lang->words['list_header_reports']}</th>
		<th scope='col' class='col_r_comments short'>{$this->lang->words['list_header_comments']}</th>
		<th scope='col' class='col_r_updated'>{$this->lang->words['list_header_updated_by']}</th>
		<th scope='col' class='col_r_mod short'><input type='checkbox' id='checkAllReports' title='{$this->lang->words['select_all_reports']}' class='input_check' /></th>
	</tr>
	<if test="indexHasReports:|:count($reports)">
		{parse striping="reportsTable" classes="row1,row2"}
		<foreach loop="reports:$reports as $report">
			<tr class='{parse striping="reportsTable"}'>
				<td class='short altrow'>
					<if test="isUnread:|:empty($report['_isRead'])">
						{parse replacement="t_unread"}
					</if>
					<a href="{parse url="app=core&amp;module=reports&amp;section=reports&amp;do=process&amp;report_ids[{$report['id']}]={$report['id']}&amp;newstatus=2&amp;k={$this->member->form_hash}" base="public"}" title='{$this->lang->words['change_current_status']}' class='ipbmenu' id='change_status-{$report['id']}'><span id="rstat-{$report['id']}">{$report['status_icon']}</span></a>
				</td>
				<td>
					<a href="{$this->settings['base_url']}&amp;app=core&amp;module=reports&amp;do=show_report&amp;rid={$report['id']}" title='{$this->lang->words['view_report']}'>{$report['title']}</a>
					<if test="statusesLoop:|:is_array( $statuses ) && count( $statuses )">
						<ul class='ipbmenu_content' id='change_status-{$report['id']}_menucontent'>
							<foreach loop="statuses:$statuses as $status_id => $status">
								<li class='{$status_id} <if test="$status_id == $report['status']">status-selected</if>'><a href="{parse url="app=core&amp;module=reports&amp;section=reports&amp;do=process&amp;report_ids[{$report['id']}]={$report['id']}&amp;newstatus={$status_id}&amp;k={$this->member->form_hash}" base="public"}" title='{$this->lang->words['change_status_title']}' class='change-status' id='{$report['id']}:{$status_id}'>{$this->lang->words['mark_status_as']} <strong>{$status['title']}</strong></a></li>
							</foreach>
						</ul>
					</if>
				</td>
				<td class='altrow'>
					<if test="indexReportUrl:|:$report['section']['url']">
						<if test="!$report['section']['title']">
							<a href="{$report['section']['url']}" title='{$this->lang->words['go_to_section']}' class='desc blend_links'>{$this->lang->words['report_no_title']}</a>
						<else />
							<a href="{$report['section']['url']}" title='{$this->lang->words['go_to_section']}'>{$report['section']['title']}</a>
						</if>
					<else />
						<if test="!$report['section']['title']">
							{$this->lang->words['report_no_title']}
						<else />
							{$report['section']['title']}
						</if>
					</if>
				</td>
				<td class='short'>{$report['num_reports']}</td>
				<td class='short altrow'>{$report['num_comments']}</td>
				<td>
					<a href='{parse url="showuser={$report['updated_by']}" seotitle="{$report['member']['members_seo_name']}" template="showuser" base="public"}' class='ipsUserPhotoLink left'>
						<img src='{$report['member']['pp_small_photo']}' class='ipsUserPhoto ipsUserPhoto_mini' />
					</a>
					<ul class='last_post ipsType_small'>
						<li>{$this->lang->words['by_ucfirst']} {parse template="userHoverCard" group="global" params="$report['member']"}</li>
						<li>{parse date="$report['date_updated']" format="date"}</li>
					</ul>
				</td>
				<td class='short altrow'>
					<input type='checkbox' id='report_check_{$report['id']}' class='input_check checkall' name='report_ids[]' value='{$report['id']}' />
				</td>
			</tr>
		</foreach>
	<else />
		<tr>
			<td colspan='7' class='no_messages row1'>
				{$this->lang->words['no_reports']}
			</td>
		</tr>
	</if>
</table>
<div id='topic_mod' class='moderation_bar rounded with_action clear'>
	<a href='#' class='ipsButton_secondary left' id='prune_reports'>{$this->lang->words['report_option_prune']}</a>
	<span class='desc'>{$this->lang->words['r_with_selected']}</span>
	<select name="newstatus" id="report_actions">
		<option value="x">---</option>
		<if test="accessACP:|:$this->memberData['g_access_cp']">
			<optgroup label="{$this->lang->words['report_actions']}"  style="font-style: normal;">
			<if test="$this->memberData['g_access_cp']"><option value="d">{$this->lang->words['report_option_delete']}</option></if>
			</optgroup>
		</if>
		<optgroup label="{$this->lang->words['report_actions_mark_optgroup']}" style="font-style: normal;">
		{$acts}
		</optgroup>
	</select>
	<label for='pruneDayBox' id='pruneDayLabel'>{$this->lang->words['older_than']}</label>
	<input type="text" name="pruneDays" id="pruneDayBox" class='input_text' size="3" value="" />
	<span id='pruneDayLang'>{$this->lang->words['report_prune_days_box']}</span>
	<input type="submit" id='report_mod' class="input_submit alt" value="{$this->lang->words['r_go']}" />
</div>
</form>
<div id='prune_reports_form' style='display: none'>
	<div class='ipsPad ipsForm_center'>
		<form method="post" action="{$this->settings['base_url']}app=core&amp;module=reports&amp;do=process&amp;st={$this->request['st']}">
		<input type='hidden' name='k' value='{$this->member->form_hash}' />
		<input type='hidden' name='newstatus' value='p' />
		{$this->lang->words['report_prune_1']} <input type="text" name="pruneDays" id="pruneDayBox" class='input_text' size="3" value="" /> {$this->lang->words['report_prune_2']}&nbsp;&nbsp;
		<input type="submit" id='report_mod' class="input_submit alt" value="{$this->lang->words['r_go']}" />
	</div>
</div>
<script type='text/javascript'>
	$('prune_reports').observe('click', function(e){
		Event.stop(e);
		
		if( ipb.reports.prunePopup ){
			ipb.reports.prunePopup.show();
		}
		else
		{
			ipb.reports.prunePopup = new ipb.Popup( 'prune', { type: 'balloon',
													initial: $('prune_reports_form').show(),
													stem: true,
													hideClose: true,
													hideAtStart: false,
													attach: { target: $('prune_reports'), position: 'auto', 'event': 'click' },
													w: '350px' }
												);
			
		}
	});
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: statusIcon
//===========================================================================
function statusIcon($img, $width, $height) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<img src='{$this->settings['public_dir']}{$img}' alt='{$this->lang->words['status']}' />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: viewReport
//===========================================================================
function viewReport($options=array(), $reports=array(), $comments=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="reports"}
<div class='message'>
	{$this->lang->words['report_about_intro']} <if test="handlePmSpecial:|:$options['class'] == 'messages'">
		{$this->lang->words['report_about_pm']} {$options['title']}.
		<if test="canJoinPm:|:in_array( $this->memberData['member_group_id'], explode( ',', $this->registry->getClass('reportLibrary')->plugins['messages']->_extra['plugi_messages_add'] ) )">
			<a href='{parse url="app=core&amp;module=reports&amp;section=reports&amp;do=showMessage&amp;topicID={$options['topicID']}" base="public"}'>{$this->lang->words['report_join_pm']}</a>
		</if>
	<else />
		<a href="{$options['url']}" title="{$this->lang->words['report_view_reported']}">{$options['title']}</a>
	</if>
</div>
<br />
<div class='topic_controls'>
	<ul class='topic_buttons'>
		<if test="$this->memberData['g_access_cp']"><li><a href='{parse url="app=core&amp;module=reports&amp;section=reports&amp;do=process&amp;report_ids[{$options['rid']}]={$options['rid']}&amp;newstatus=d&amp;k={$this->member->form_hash}" base="public"}' title='{$this->lang->words['delete_report']}'><img src='{$this->settings['img_url']}/delete.png' alt='' id='delete_report' /> {$this->lang->words['delete_report']}</a></li></if>
		<li><a href='{parse url="app=core&amp;module=reports&amp;section=reports&amp;do=process&amp;report_ids[{$options['rid']}]={$options['rid']}&amp;newstatus=2&amp;k={$this->member->form_hash}" base="public"}' title='{$this->lang->words['change_current_status']}' class='ipbmenu' id='change_status'>{$options['status_icon']} {$this->lang->words['current_status']} <strong>{$options['status_text']}</strong></a></li>
	</ul>
</div>
<if test="statusesLoop:|:is_array( $options['statuses'] ) && count( $options['statuses'] )">
	<ul class='ipbmenu_content' id='change_status_menucontent'>
		<foreach loop="statuses:$options['statuses'] as $status_id => $status">
			<if test="setStatus:|:$status_id != $options['status_id']">
				<li><a href="{parse url="app=core&amp;module=reports&amp;section=reports&amp;do=process&amp;report_ids[{$options['rid']}]={$options['rid']}&amp;newstatus={$status_id}&amp;k={$this->member->form_hash}" base="public"}" title='{$this->lang->words['change_status_title']}' />{$this->lang->words['mark_status_as']} <strong>{$status['title']}</strong></a></li>
			</if>
		</foreach>
	</ul>
</if>
<br />
<div class='topic hfeed'>
<h2 class='maintitle'>{$this->lang->words['reports_h2']}</h2>
<div class='generic_bar'></div>
<if test="hasReports:|:is_array($reports) AND count($reports)">
	<foreach loop="viewReports:$reports as $report">
		<div class='post_block  hentry clear'>
			<div class='post_wrap'>
				<h3>{parse template="userHoverCard" group="global" params="$report"}</h3>
				<div class='author_info'>
					{parse template="userInfoPane" group="global" params="$report['author'], 'mreport', array( 'id2' => $report['rid'] )"}
				</div>
				<div class='post_body'>
					<p class='posted_info'>{$this->lang->words['posted_prefix']} {parse date="$report['date_reported']" format="long"}</p>
					<div class='post entry-content'>{$report['report']}</div>
				</div>
			</div>
			<br class='clear' />
		</div>
	</foreach>
</if>
</div>
<br /><hr /><br />
<div class='ipsBox'>
	<div class='ipsBox_container ipsPad'>
		<h2 class='ipsType_subtitle'>{$comments['count']} {$this->lang->words['comments_h2']}</h2>
		<div>
			{$comments['html']}
		</div>
	</div>
</div>
<if test="disablelightbox:|:!$this->settings['disable_lightbox']">
{parse template="include_lightbox" group="global" params=""}
</if>
{parse template="include_highlighter" group="global" params="1"}
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>