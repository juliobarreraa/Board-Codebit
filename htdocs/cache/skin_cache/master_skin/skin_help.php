<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: helpShowSection
//===========================================================================
function helpShowSection($one_text="",$two_text="",$three_text="", $text) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="notajax:|:!$this->request['xml']">
<div class='topic_controls'>
	<ul class='topic_buttons'>
		<li><a href="{parse url="app=core&amp;module=help" base="public"}" title="{$this->lang->words['help_back_list_title']}"><img src='{$this->settings['img_url']}/back.png' alt='' /> {$this->lang->words['help_return_list']}</a></li>
	</ul>
</div>
</if>
<if test="isajax:|:$this->request['xml']">
<br />
</if>
<if test="notajax:|:!$this->request['xml']">
	<h1 class='ipsType_pagetitle'>{$one_text}: {$three_text}</h1>
<else />
	<h1 class='ipsType_subtitle'>{$one_text}: {$three_text}</h1>
</if>
<br />
<div class='row2 help_doc ipsPad bullets'>
	{$text}
</div>
<br />
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: helpShowTopics
//===========================================================================
function helpShowTopics($one_text="",$two_text="",$three_text="",$rows) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
{parse js_module="help"}
<p class='message unspecific'>{$two_text}</p>
<h2 class='maintitle'>{$this->lang->words['help_topics']}</h2>
<div class='generic_bar'></div>
<ol id='help_topics'>
	{parse striping="help" classes="row1,row2"}
	<if test="count($rows)">
		<foreach loop="helpfiles:$rows as $entry">
		<li class='{parse striping="help"} helpRow'>
			<h3><a href="{parse url="app=core&amp;module=help&amp;do=01&amp;HID={$entry['id']}" base="public"}" title="{$this->lang->words['help_read_document']}">{$entry['title']}</a></h3>
			<p>
				{$entry['description']}
			</p>
		</li>
		</foreach>
	<else />
		<li class='no_messages'>{$this->lang->words['no_help_topics']}</li>
	</if>
</ol>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>