<?php
/**
 * Master skin file
 * Written: Fri, 13 Jul 2012 19:04:19 +0000
 */
class skin_global_1 extends output {
//===========================================================================
// Name: bbcodePopUpList
//===========================================================================
function bbcodePopUpList($rows) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<h2>{$this->lang->words['bbc_title']}</h2>
<span class='desc'>{$this->lang->words['bbc_intro']}</span>
<br /><br />
<table class='ipb_table'>
	<tr class='header'>
		<th style='width: 50%'>{$this->lang->words['bbc_before']}</th>
		<th style='width: 50%'>{$this->lang->words['bbc_after']}</th>
	</tr>
	<foreach loop="bbcode:$rows as $row">
		<tr class='subhead bar altbar'>
			<th colspan='2'>{$row['title']}</th>
		</tr>
		<tr class='row1'>
			<td class='altrow'>
				{$row['before']}
			</td>
			<td>
				{$row['after']}
			</td>
		</tr>
	</foreach>
</table>
{parse template="include_highlighter" group="global" params="1"}
<script type='text/javascript'>
	try {
		ipb.delegate.register('.bbc_spoiler_show', ipb.global.toggleSpoiler);
	} catch(err) { }
</script>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: emoticonPopUpList
//===========================================================================
function emoticonPopUpList($editor_id, $rows, $legacy_editor=false) {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<if test="!$legacy_editor">
	<script type="text/javascript">
	addEmoImage = function(elem){
		var isRte = opener.ipb.textEditor.getEditor().isRte();
		var toAdd = '';
	
		if ( isRte ){
			toAdd = elem.up('tr').down('img').readAttribute('src');
			toAdd = '<img src="' + toAdd + '" />&nbsp;';
		} else {
			toAdd = elem.up('tr').down('a').innerHTML + ' ';
		}
	
		opener.ipb.textEditor.getEditor().insert( toAdd );
	}
	</script>
<else />
	<script type='text/javascript'>
		function addEmoImage(elem){
			var code = elem.up('tr').down('a').innerHTML;
			var title = elem.up('tr').down('img').readAttribute('title');
			ipb.editors[ '{$editor_id}' ].insert_emoticon('', title, code,'');
		}
	</script>
</if>
{parse striping="emoticons" classes="row1,row2"}
<div class='full_emoticon'>
	<table class='ipb_table'>
		<foreach loop="emoticons:$rows as $row">
		<tr class='{parse striping="emoticons"}'>
			<td style='text-align: center; width: 40%;'>
				<a href="#" onclick="addEmoImage(this); return false;" title="{$row['image']}">{$row['code']}</a>
			</td>
			<td style='text-align: center; width: 60%;'>
				<img class='clickable' src="{$this->settings['emoticons_url']}/{$row['image']}" onclick="addEmoImage(this); return false;" id='smid_{$row['smilie_id']}' alt="{$row['image']}" />
			</td>
		</tr>
		</foreach>
	</table>
</div>
EOF;
//--endhtml--//
return $IPBHTML;
}

//===========================================================================
// Name: wrap_tag
//===========================================================================
function wrap_tag($tag="") {
$IPBHTML = "";
//--starthtml--//
$IPBHTML .= <<<EOF
<div><b>{$tag}</b></div>
EOF;
//--endhtml--//
return $IPBHTML;
}


}
?>