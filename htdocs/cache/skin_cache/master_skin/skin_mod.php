<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: deleteTopicForm
//===========================================================================
function deleteTopicForm($forum, $topic) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form action='{parse url="app=forums&amp;module=moderate&amp;section=moderate" base="public"}' method='post'>
<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
<input type='hidden' name='do' value='08' />
<input type='hidden' name='t' value='{$topic['tid']}' />
<input type='hidden' name='f' value='{$forum['id']}' />
<input type='hidden' name='st' value='{$this->request['st']}' />
<input type='hidden' name='return' value='{$this->request['return']}' />
<h2 class='maintitle'>{$this->lang->words['top_delete']}: {$forum['name']} -&gt; {$topic['title']}</h2>
<div class='generic_bar'></div>
<div class='post_form'>
	<p class='row1 field'>{$this->lang->words['delete_topic']}</p>
	<fieldset class='submit'>
		<input type="submit" class='input_submit' value="{$this->lang->words['submit_delete']}" /> {$this->lang->words['or']} <a href='{parse url="showtopic={$topic['tid']}&amp;st={$this->request['st']}" base="public" seotitle="{$topic['title_seo']}" template="showtopic"}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
	</fieldset>
</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: editTopicTitle
//===========================================================================
function editTopicTitle($forum, $topic) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form action='{parse url="app=forums&amp;module=moderate&amp;section=moderate" base="public"}' method='post'>
<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
<input type='hidden' name='do' value='12' />
<input type='hidden' name='t' value='{$topic['tid']}' />
<input type='hidden' name='f' value='{$forum['id']}' />
<input type='hidden' name='st' value='{$this->request['st']}' />
<h2 class='maintitle'>{$this->lang->words['top_edit']}: {$forum['name']} &gt; {$topic['title']}</h2>
<div class='generic_bar'></div>
<div class='post_form'>
	<fieldset>
		<h3 class='bar'>{$this->lang->words['editing_topic_title']}</h3>
		<ul>
			<li class='field'>
				<label for='topic_title'>{$this->lang->words['edit_f_title']}</label>
				<input class='input_text' type="text" size="40" id='topic_title' maxlength="{$this->settings['topic_title_max_len']}" name="TopicTitle" value="{$topic['title']}" />
			</li>
		</ul>
	</fieldset>
	<fieldset class='submit'>
		<input type="submit" class='input_submit' value="{$this->lang->words['submit_edit']}" /> {$this->lang->words['or']} <a href='{parse url="showtopic={$topic['tid']}" base="public" seotitle="{$topic['title_seo']}" template="showtopic"}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
	</fieldset>
</div>
</form>
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: mergeMultiplePolls
//===========================================================================
function mergeMultiplePolls($polls, $tids) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h1 class='ipsType_pagetitle'>{$this->lang->words['poll_merge_title']}</h1>
<br />
<div class='message'>
	{$this->lang->words['poll_merge_blurb']}
</div>
<br />
<foreach loop="$polls as $poll">
	<div class='general_box alt poll'>
		<h3>{$poll['poll_question']}</h3>
		<foreach loop="unserialize( stripslashes( $poll['choices'] ) ) as $questionID => $questionData">
			<div class='poll_question'>
				<h4 class='rounded'>{$questionData['question']}</h4>
				<ol>
					<foreach loop="$questionData['choice'] as $choiceID => $name">
						<li>
							<span class='answer'>{$name}</span>
							<span class='votes'>({$questionData['votes'][$choiceID]} {$this->lang->words['poll_votes']})</span>
						</li>	
					</foreach>
				</ol>
			</div>
		</foreach>
		<fieldset class='submit'>
			<a href='{parse url="app=forums&amp;module=moderate&amp;section=moderate&amp;do=topicchoice&amp;tact=merge&amp;selectedtids={$tids}&amp;f={$this->request['f']}&amp;chosenpolltid={$poll['tid']}&amp;auth_key={$this->member->form_hash}" base="public"}' class='ipsButton'>{$this->lang->words['poll_merge_submit']}</a>
		</fieldset>
	</div>
	<br /><br />
</foreach>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: mergePostForm
//===========================================================================
function mergePostForm($editor, $dates, $authors, $uploads, $seoTitle) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="moderate"}
<form action='{parse url="app=forums&amp;module=moderate&amp;section=moderate" base="public"}' method='post'>
<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
<input type='hidden' name='do' value='postchoice' />
<foreach loop="selectedpids:$this->request['selectedpids'] as $pid">
	<input type="hidden" name="selectedpids[{$pid}]" value="{$pid}" />
</foreach>
<if test="$this->request['selectedpidsJS']">
	<foreach loop="selectedpidsjs:explode( ',', $this->request['selectedpidsJS'] ) as $pid">
		<input type="hidden" name="selectedpids[{$pid}]" value="{$pid}" />
	</foreach>
</if>
<input type="hidden" name="t" value="{$this->request['t']}" />
<input type="hidden" name="f" value="{$this->request['f']}" />
<input type='hidden' name='tact' value='merge' />
<input type='hidden' name='checked' value='1' />
<input type='hidden' name='st' value='{$this->request['st']}' />
<p class='message'>{$this->lang->words['merge_select']}</p>
<br />
<h2 class='maintitle'>{$this->lang->words['cm_title']}</h2>
<div class='generic_bar'></div>
<div class='post_form'>
	<fieldset>
		<h3 class='bar'>{$this->lang->words['post_options']}</h3>
		<ul>
			<li class='field'>
				<label for='post_date'>{$this->lang->words['cm_post']}</label>
				<select name="postdate" class="input_select" id='post_date'>
					<if test="mergepostsdates:|:is_array($dates) AND count($dates)">
						<foreach loop="dates:$dates as $date">
							<option value='{$date[0]}'>{$date[1]}</option>
						</foreach>
					</if>
				</select>
			</li>
			<li class='field'>
				<label for='post_author'>{$this->lang->words['cm_author']}</label>
				<select name="postauthor" class="input_select" id='post_author'>
					<if test="mergepostsauthors:|:is_array($authors) AND count($authors)">
						<foreach loop="authors:$authors as $author">
							<option value='{$author[0]}'>{$author[1]}</option>
						</foreach>
					</if>
				</select>
			</li>
		</ul>
		<h3 class='bar'>{$this->lang->words['post_content']}</h3>
		{$editor}
	</fieldset>
	<if test="mergepostsattachments:|:count($uploads)">
		<fieldset>
			<h3 class='bar'>{$this->lang->words['cm_attach']}</h3>
			<h4>{$this->lang->words['cm_attach2']}<h4>
			<table>
				<foreach loop="uploads:$uploads as $attach">
					<tr>
						<td width="1%">
							<select name="attach_{$attach['attach_id']}" class="input_select">
								<option value="keep">{$this->lang->words['cm_keep']}</option>
								<option value="delete">{$this->lang->words['cm_delete']}</option>
							</select>
						</td>
						<td width="1%"><img src="{$this->settings['public_dir']}{$attach['image']}" alt="{$this->lang->words['cm_attach_alt']}" /></td>
						<td width="15%" nowrap="nowrap">{$attach['size']}</td>
						<td width="95%"><a href="{parse url="app=core&amp;module=attach&amp;section=attach&amp;attach_id={$attach['attach_id']}" base="public"}" title="{$this->lang->words['cm_attach_title']}">{$attach['attach_file']}</a> #{$attach['attach_rel_id']}</td>
					</tr>
				</foreach>
			</table>
		</fieldset>
	</if>
	<fieldset class='submit'>
		<php>
			$selectedPids	= implode( ',', $this->request['selectedpids'] );
		</php>
		<input type="submit" class='input_submit' value="{$this->lang->words['cm_submit']}" /> {$this->lang->words['or']} <a href='{parse url="showtopic={$this->request['t']}&amp;selectedpids={$selectedPids}" base="public" seotitle="{$seoTitle}" template="showtopic"}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
	</fieldset>
</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: mergeTopicsForm
//===========================================================================
function mergeTopicsForm($forum, $topic) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form action='{parse url="app=forums&amp;module=moderate&amp;section=moderate" base="public"}' method='post'>
<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
<input type='hidden' name='do' value='61' />
<input type='hidden' name='t' value='{$topic['tid']}' />
<input type='hidden' name='f' value='{$forum['id']}' />
<input type='hidden' name='st' value='{$this->request['st']}' />
<h2 class='maintitle'>{$this->lang->words['mt_top']}: {$forum['name']} &gt; {$topic['title']}</h2>
<div class='generic_bar'></div>
<div class='post_form'>
	<fieldset>
		<h3 class='bar'>{$this->lang->words['merge_topic_options']}</h3>
		<ul>
			<li class='field'>
				<label for='new_title'>{$this->lang->words['mt_new_title']}</label>
				<input type="text" id='new_title' class='input_text' size="40" maxlength="250" name="title" value="{$topic['title']}" />
			</li>
			<li class='field'>
				<label for='topic_url'>{$this->lang->words['mt_tid']}</label>
				<input type="text" id='topic_url' class='input_text' size="40" name="topic_url" value="" />
				<span class='desc'>{$this->lang->words['mt_tid_desc']}</span>
			</li>
		</ul>
	</fieldset>
	<fieldset class='submit'>
		<input type="submit" class='input_submit' value="{$this->lang->words['mt_submit']}" /> {$this->lang->words['or']} <a href='{parse url="showtopic={$topic['tid']}" base="public" seotitle="{$topic['title_seo']}" template="showtopic"}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
	</fieldset>
</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: movePostForm
//===========================================================================
function movePostForm($forum, $topic, $posts, $error='') {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="movepostserror:|:$error">
	<p class="message error">{$error}</p>
</if>
{parse js_module="moderate"}
<form action='{parse url="app=forums&amp;module=moderate&amp;section=moderate" base="public"}' method='post'>
<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
<input type='hidden' name='do' value='postchoice' />
<input type='hidden' name='t' value='{$topic['tid']}' />
<input type='hidden' name='f' value='{$forum['id']}' />
<input type='hidden' name='tact' value='move' />
<input type='hidden' name='checked' value='1' />
<input type='hidden' name='st' value='{$this->request['st']}' />
<h2 class='maintitle'>{$this->lang->words['cmp_title']}: {$forum['name']} -&gt; {$topic['title']}</h2>
<div class='ipsBox clearfix'>
	<div class='ipsBox_container'>
		<ul>
			<li class='field'>
				<label for='topic_url'>{$this->lang->words['cmp_topic']}</label>
				<input type="text" size="50" name="topic_url" value="" id='topic_url' class='input_text' />
				<span class='desc'>{$this->lang->words['cmp_topic2']}</span>
			</li>
		</ul>
	</div>
</div>
<br />
<h2 class='maintitle'>{$this->lang->words['move_topic_summary']}</h2>
<div class='ipsBox clearfix'>
	<div class='ipsBox_container'>
		<if test="movepostsloop:|:is_array( $posts ) AND count( $posts )">
			<foreach loop="posts:$posts as $row">
				<div class='post_block no_sidebar topic_summary'>
					<div class='post_wrap'>
					<h3 class='row2<if test="isGuest:|:empty($row['member_id'])"> guest</if>'><input type='checkbox' id='checkbox_{$row['pid']}' name='selectedpids[{$row['pid']}]' value='{$row['pid']}' class='post_mod' checked='checked' /> {$row['author_name']}</h3>
					<div class='post_body'>
						<p class='posted_info'><label for='checkbox_{$row['pid']}' class='post_mod hide'>{$this->lang->words['st_split']}</label> {$this->lang->words['move_posted']} {$row['date']}</p>
						<div class='post'>{$row['post']}</div>
					</div>
				</div>
			</foreach>
		</if>
	</div>
</div>
<fieldset class='submit'>
	<php>
		$selectedPids	= implode( ',', $this->request['selectedpids'] );
	</php>
	<input type="submit" name="submit" class='input_submit' value="{$this->lang->words['cmp_submit']}" /> {$this->lang->words['or']} <a href='{parse url="showtopic={$topic['tid']}&amp;selectedpids={$selectedPids}" base="public" seotitle="{$topic['title_seo']}" template="showtopic"}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
</fieldset>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: moveTopicForm
//===========================================================================
function moveTopicForm($forum, $topic, $forum_jump) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form action='{parse url="app=forums&amp;module=moderate&amp;section=moderate" base="public"}' method='post'>
<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
<input type='hidden' name='do' value='14' />
<input type='hidden' name='t' value='{$topic['tid']}' />
<input type='hidden' name='f' value='{$forum['id']}' />
<input type='hidden' name='st' value='{$this->request['st']}' />
<p class='message'>{$this->lang->words['move_exp']}</p>
<br />
<h2 class='maintitle'>{$this->lang->words['top_move']}: {$forum['name']} &gt; {$topic['title']}</h2>
<div class='generic_bar'></div>
<div class='post_form'>
	<fieldset>
		<h3 class='bar'>{$this->lang->words['move_topic_options']}</h3>
		<ul>
			<li class='field'>
				<label for='move_from'>{$this->lang->words['move_from']} <strong>{$forum['name']}</strong> {$this->lang->words['to']}</label>
				<select name="move_id" id='move_from' class='input_select'>{$forum_jump}</select>
			</li>
			<li class='field clear checkbox'>
				<input type='checkbox' id='leave_link' class='input_check' name='leave' value='y' />
				<label for='leave_link'>{$this->lang->words['leave_link']}</label>
			</li>
		</ul>
	</fieldset>
	<fieldset class='submit'>
		<input type="submit" class='input_submit' value="{$this->lang->words['submit_move']}" /> {$this->lang->words['or']} <a href='{parse url="showtopic={$topic['tid']}" base="public" seotitle="{$topic['title_seo']}" template="showtopic"}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
	</fieldset>
</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: moveTopicsForm
//===========================================================================
function moveTopicsForm($forum, $jump_html, $topics) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form action='{parse url="app=forums&amp;module=moderate&amp;section=moderate" base="public"}' method='post'>
<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
<input type='hidden' name='do' value='topicchoice' />
<input type='hidden' name='tact' value='domove' />
<input type='hidden' name='f' value='{$forum['id']}' />
<input type='hidden' name='st' value='{$this->request['st']}' />
<input type='hidden' name='fromSearch' value='{$this->request['fromSearch']}' />
<input type='hidden' name='returnUrl' value='{$this->request['returnUrl']}' />
<p class='message'>{$this->lang->words['move_exp']}</p>
<br />
<h2 class='maintitle'>{$this->lang->words['cp_tmove_start']}</h2>
<div class='generic_bar'></div>
<div class='post_form' id='mod_form'>
	<fieldset class='row1'>
		<h3 class='bar'>{$this->lang->words['topics_to_move']}</h3>
		<ul>
			<if test="movetopicsloop:|:count($topics) AND is_array($topics)">
				<foreach loop="topics:$topics as $row">
					<li class='field checkbox'>
						<input class='input_check' type="checkbox" name="selectedtids[{$row['tid']}]" id='t_{$row['tid']}' value="{$row['tid']}" checked="checked" />
						<label for='t_{$row['tid']}'>{$row['title']}</label>
					</li>
				</foreach>
			</if>
		</ul>
	</fieldset>
	<fieldset class='row1'>
		<h3 class='bar'>{$this->lang->words['move_topic_options']}</h3>
		<ul>
			<li class='field'>
				<label for='move_from'>{$this->lang->words['move_from']} <strong>{$forum['name']}</strong> {$this->lang->words['to']}</label>
				<select name="df" id='move_from' class='input_select'>{$jump_html}</select>
			</li>
			<li class='field clear checkbox'>
				<input type='checkbox' id='leave_link' class='input_check' name='leave' value='y' />
				<label for='leave_link'>{$this->lang->words['leave_link']}</label>
			</li>
		</ul>
	</fieldset>
	<fieldset class='submit'>
		<input type="submit" class='input_submit' value="{$this->lang->words['submit_move']}" /> {$this->lang->words['or']} <a href='{parse url="showforum={$forum['id']}" base="public" seotitle="{$forum['name_seo']}" template="showforum"}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
	</fieldset>
</div>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: pruneSplash
//===========================================================================
function pruneSplash($forum="", $forums_html="", $confirm_data="", $complete_html="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="prunecompletehtml:|:$complete_html != ''">
	<div class='message'>{$complete_html}</div><br />
</if>
	
<h2 class='maintitle'>{$this->lang->words['cp_prune']} {$forum['name']}</h2>
<div class='generic_bar'></div>
<div class='post_form'>
	<if test="confirmprune:|:$confirm_data['show'] == 1">
		<fieldset>
			<h3 class='bar'>{$this->lang->words['mpt_confirm']}: {$this->lang->words['cp_check_result']}</h3>
			
			<ul>
				<li class='field'>
					<label>{$this->lang->words['cp_total_topics']}</label>
					{$confirm_data['tcount']}
				</li>
				<li class='field'>
					<label>{$this->lang->words['cp_total_match']}</label>
					{$confirm_data['count']}
				</li>
			</ul>
		</fieldset>
	
		<form action='{parse url="{$confirm_data['link']}" base="public"}' method="post">
		<input type="hidden" name="auth_key" value="{$this->member->form_hash}" />
		<fieldset class='submit'>
			<input type="submit" class='input_submit' value="{$confirm_data['link_text']}" />
		</fieldset>
		</form>
		
	</if>
	<form id='postingform' action="{parse url="" base="public"}" method="post">
	<input type="hidden" name="app" value="forums" />
	<input type="hidden" name="module" value="moderate" />
	<input type="hidden" name="section" value="moderate" />
	<input type="hidden" name="do" value="prune_start" />
	<input type="hidden" name="f" value="{$forum['id']}" />
	<input type="hidden" name="auth_key" value="{$this->member->form_hash}" />
	<input type="hidden" name="check" value="1" />
	<fieldset>
		<h3 class='bar'>{$this->lang->words['mpt_help']}</h3>
		<p class='ipsPad'>{$this->lang->words['cp_prune_text']}</p><br />
		<ul>
			<li class='field'>
				<label for='df'>{$this->lang->words['cp_prune_action2']}</label>
				<select class='input_select' name="df" id="df">
					<option value='prune'>{$this->lang->words['cp_ac_prune']}</option>
					{$forums_html}
				</select>
			</li>
			<li class='field'>
				<label for='pergo'>{$this->lang->words['cp_per_go']}</label>
				<input class='input_text' type="text" size="5" name="pergo" id="pergo" value="{$this->request['pergo']}" />
			</li>
			<li class='field'>
				<label for='entered_name'>{$this->lang->words['cp_prune_member']}</label>
				<input class='input_text' type="text" size="30" name="member" id='entered_name' value="{$this->request['member']}" />
			</li>
			<li class='field'>
				<label for='dateline'>{$this->lang->words['cp_prune_days2']}</label>
				<input class='input_text' type="text" size="5" name="dateline" id="dateline" value="{$this->request['dateline']}" />
			</li>
			<li class='field'>
				<label for='posts'>{$this->lang->words['cp_prune_replies']}</label>
				<input class='input_text' type="text" size="5" name="posts" id="posts" value="{$this->request['posts']}" />
			</li>
			<li class='field'>
				<label for='topic_type'>{$this->lang->words['cp_prune_type']}</label>
				<select class='input_select' name='topic_type' id="topic_type">
					<foreach loop="types:array( 'open', 'closed', 'link', 'all' ) as $type">
						<option value='{$type}'<if test="defaultselectedoption:|:$this->request['topic_type'] == $type"> selected='selected'</if>>{$this->lang->words[ 'cp_pday_' . $type ]}</option>
					</foreach>
				</select>
			</li>
			<li class='field'>
				<label for='cbox'>{$this->lang->words['mps_ignorepin']}</label>
				<input class='input_check' type="checkbox" id="cbox" name="ignore_pin" value="1" checked="checked" />
			</li>
			<li class='field'>
				<span class='desc'>{$this->lang->words['cp_optional']}</span>
			</li>
		</ul>
	</fieldset>
	<fieldset class='submit'>
		<input type="submit" class='input_submit' value="{$this->lang->words['cp_prune_sub1']}" /> {$this->lang->words['or']} <a href='{parse url="showforum={$forum['id']}" base="public" seotitle="{$forum['name_seo']}" template="showforum"}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
	</fieldset>
	</form>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: simplePage
//===========================================================================
function simplePage($title="", $msg="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<strong>{$title}</strong><br /> {$msg}
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: softDeleteSplash
//===========================================================================
function softDeleteSplash($forum, $tids=array(), $canHardDelete=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="moderate"}
<form action='{parse url="app=forums&amp;module=moderate&amp;section=moderate" base="public"}' id='mmForm' method='post'>
<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
<input type='hidden' name='do' value='topicchoice' />
<input type='hidden' name='fromSearch' value='{$this->request['fromSearch']}' />
<input type='hidden' name='returnUrl' value='{$this->request['returnUrl']}' />
<input type='hidden' name='return' value='{$this->request['return']}' />
<foreach loop="$tids as $t">
	<input type='hidden' name="selectedtids[$t]" value="$t" />
</foreach>
<input type='hidden' name='tact' id='mmTact' value='sdelete' />
<input type='hidden' name='f' value='{$forum['id']}' />
<input type='hidden' name='st' value='{$this->request['st']}' />
<h2 class='maintitle'>{parse expression="sprintf($this->lang->words['mm_delete_top'], count( $tids ) )"}</h2>
<div class='ipsBox clearfix'>
	<div class='ipsBox_container'>
		<div class='ipsPad'>
			{$this->lang->words['dltt_remove_from_view_desc']}<br /><br />
			<input type='text' class='input_text' name='deleteReason' id='delPop_reason' value='' style='width: 60%'  /> <input type='submit' class='input_submit' value='{$this->lang->words['topic_hide']}' />
		</div>
	</div>
</div>
</form>
<script type='text/javascript'>
$('delPop_reason').defaultize( "{$this->lang->words['topic_hide_reason_default']}" );
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: softDeleteSplashPosts
//===========================================================================
function softDeleteSplashPosts($forum, $topic, $pids=array(), $canHardDelete=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="moderate"}
<form action='{parse url="app=forums&amp;module=moderate&amp;section=moderate" base="public"}' id='mmForm' method='post'>
<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
<input type='hidden' name='do' value='postchoice' />
<input type='hidden' name='fromSearch' value='{$this->request['fromSearch']}' />
<input type='hidden' name='returnUrl' value='{$this->request['returnUrl']}' />
<input type='hidden' name='return' value='{$this->request['return']}' />
<foreach loop="$pids as $p">
	<input type='hidden' name="selectedpids[$p]" value="$p" />
</foreach>
<input type='hidden' name='tact' id='mmTact' value='sdelete' />
<input type='hidden' name='f' value='{$forum['id']}' />
<input type='hidden' name='t' value='{$topic['tid']}' />
<input type='hidden' name='st' value='{$this->request['st']}' />
<h2 class='maintitle'>{parse expression="sprintf($this->lang->words['mm_delete_top_posts'], count( $pids ) )"}</h2>
<div class='ipsBox clearfix'>
	<div class='ipsBox_container'>
		<div class='ipsPad ipsForm_center'>
			{$this->lang->words['dlp_remove_from_view_desc']}<br />
			<span class='desc lighter ipsType_smaller'>{$this->lang->words['dlp_remove_from_view_extra']}</span><br /><br />
			<input type='text' class='input_text' name='deleteReason' id='delPop_reason' value='' style='width: 60%'  /> <input type='submit' class='input_submit' value='{$this->lang->words['post_hide']}' />
		</div>
	</div>
</div>
</form>
<script type='text/javascript'>
$('delPop_reason').defaultize( "{$this->lang->words['post_hide_reason_default']}" );
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: splitPostForm
//===========================================================================
function splitPostForm($forum, $topic, $posts, $jump) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="moderate"}
<form action='{parse url="app=forums&amp;module=moderate&amp;section=moderate" base="public"}' method='post'>
<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
<input type='hidden' name='do' value='postchoice' />
<input type='hidden' name='t' value='{$topic['tid']}' />
<input type='hidden' name='f' value='{$forum['id']}' />
<input type='hidden' name='tact' value='split' />
<input type='hidden' name='checked' value='1' />
<input type='hidden' name='st' value='{$this->request['st']}' />
<p class='message'>{$this->lang->words['st_explain']}</p>
<br />
<h2 class='maintitle'>{$this->lang->words['st_top']}: {$forum['name']} &gt; {$topic['title']}</h2>
<div class='ipsBox clearfix'>
	<div class='ipsBox_container'>
		<ul>
			<li class='field'>
				<label for='new_title'>{$this->lang->words['mt_new_title']}</label>
				<input type="text" size="40" class='input_text' id='new_title' maxlength="100" name="title" value="" />
			</li>
			<li class='field'>
				<label for='new_forum'>{$this->lang->words['st_forum']}</label>
				<select name="fid" id='new_forum' class='input_select'>{$jump}</select>
			</li>
		</ul>
	</div>
</div>
<br />
<h2 class='maintitle'>{$this->lang->words['st_post']}</h2>
<div class='ipsBox clearfix'>
	<div class='ipsBox_container'>
	<if test="splitpostsloop:|:is_array( $posts ) AND count( $posts )">
		<foreach loop="split_posts:$posts as $row">
			<div class='post_block no_sidebar topic_summary'>
				<h3><input type='checkbox' id='checkbox_{$row['pid']}' name='selectedpids[{$row['pid']}]' value='{$row['pid']}' class='post_mod' checked='checked' /><label for='checkbox_{$row['pid']}' class='post_mod hide'>{$this->lang->words['st_split']}</label> {$row['author_name']}</h3>
				<div class='post_body'>
					<p class='posted_info'>{$this->lang->words['move_posted']} {$row['date']}</p>
					<div class='post'>{$row['post']}</div>
				</div>
			</div>
		</foreach>
	</if>
	</div>
</div>
<fieldset class='submit'>
	<php>
		$selectedPids	= implode( ',', $this->request['selectedpids'] );
	</php>
	<input type="submit" name="submit" class='input_submit' value="{$this->lang->words['st_submit']}" /> {$this->lang->words['or']} <a href='{parse url="showtopic={$topic['tid']}&amp;selectedpids={$selectedPids}" base="public" seotitle="{$topic['title_seo']}" template="showtopic"}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
</fieldset>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: topicHistory
//===========================================================================
function topicHistory($topic, $avg_post, $mod_logs=array()) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse striping="topicHistory" classes="row1,row2"}
<h1 class='ipsType_pagetitle'>{$this->lang->words['th_title']}</h1>
<br />
<div class='ipsBox'>
	<table class='ipb_table' summary="{$this->lang->words['topic_history_summary']} '{$topic['title']}'">
		<tr class='{parse striping="topicHistory"}'>
			<td class='altrow'>{$this->lang->words['th_topic']}</td>
			<td>{$topic['title']}</td>
		</tr>
		<tr class='{parse striping="topicHistory"}'>
			<td class='altrow'>{$this->lang->words['th_start_date']}</td>
			<td>{parse date="$topic['start_date']" format="long"}</td>
		</tr>
		<tr class='{parse striping="topicHistory"}'>
			<td class='altrow'>{$this->lang->words['th_start_name']}</td>
			<td>{IPSMember::makeProfileLink( $topic['starter_name'], $topic['starter_id'], $topic['seo_first_name'] )}</td>
		</tr>
		<tr class='{parse striping="topicHistory"}'>
			<td class='altrow'>{$this->lang->words['th_last_date']}</td>
			<td>{parse date="$topic['last_post']" format="long"}</td>
		</tr>
		<tr class='{parse striping="topicHistory"}'>
			<td class='altrow'>{$this->lang->words['th_last_name']}</td>
			<td>{IPSMember::makeProfileLink( $topic['last_poster_name'], $topic['last_poster_id'], $topic['seo_last_name'] )}</td>
		</tr>
		<tr class='{parse striping="topicHistory"}'>
			<td class='altrow'>{$this->lang->words['th_avg_post']}</td>
			<td>{$avg_post}</td>
		</tr>
	</table>
</div>
<br /><br />
<h3 class='maintitle'>{$this->lang->words['ml_title']}</h3>
<table class='ipb_table' summary="{$this->lang->words['mod_logs_topic_summary']} '{$topic['title']}'">
	<tr class='header'>
		<th scope='col' style='width: 25%'>{$this->lang->words['ml_name']}</th>
		<th scope='col' style='width: 50%'>{$this->lang->words['ml_desc']}</th>
		<th scope='col' style='width: 25%'>{$this->lang->words['ml_date']}</th>
	</tr>
	<if test="topicmodlogs:|:!count($mod_logs) OR !is_array($mod_logs)">
		<tr>
			<td class="no_messages" colspan="3">{$this->lang->words['ml_none']}</td>
		</tr>
	<else />
		{parse striping="modlogs" classes="row1,row2"}
		<foreach loop="mod_logs:$mod_logs as $data">
			<tr class='{parse striping="modlogs"}'>
				<td class='altrow'>{parse template="userHoverCard" group="global" params="$data"}</td>
				<td>{$data['action']}</td>
				<td class="altrow">{parse date="$data['ctime']" format="long"}</td>
			</tr>
		</foreach>
	</if>
</table>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: unsubscribeForm
//===========================================================================
function unsubscribeForm($forum, $topic, $text) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<form action='{parse url="app=forums&amp;module=moderate&amp;section=moderate" base="public"}' method='post'>
<input type='hidden' name='auth_key' value='{$this->member->form_hash}' />
<input type='hidden' name='do' value='31' />
<input type='hidden' name='t' value='{$topic['tid']}' />
<input type='hidden' name='f' value='{$forum['id']}' />
<input type='hidden' name='st' value='{$this->request['st']}' />
<h1 class='ipsType_pagetitle'>{$this->lang->words['ts_title']}: {$forum['name']} &gt; {$topic['title']}</h1>
<br />
<p class='message'>{$text}</p>
<br />
<fieldset class='submit'>
	<input type="submit" class='input_submit' value="{$this->lang->words['ts_submit']}" /> {$this->lang->words['or']} <a href='{parse url="showtopic={$topic['tid']}" base="public" seotitle="{$topic['title_seo']}" template="showtopic"}' title='{$this->lang->words['cancel']}' class='cancel'>{$this->lang->words['cancel']}</a>
</fieldset>
</form>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>