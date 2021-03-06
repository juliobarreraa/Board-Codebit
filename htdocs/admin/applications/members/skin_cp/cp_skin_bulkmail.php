<?php
/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.3
 * ACP bulk mail skin file
 * Last Updated: $Date: 2012-05-10 16:10:13 -0400 (Thu, 10 May 2012) $
 * </pre>
 *
 * @author 		$Author: bfarber $
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @subpackage	Members
 * @link		http://www.invisionpower.com
 * @since		20th February 2002
 * @version		$Rev: 10721 $
 *
 */
 
class cp_skin_bulkmail
{
	/**
	 * Registry Object Shortcuts
	 *
	 * @var		$registry
	 * @var		$DB
	 * @var		$settings
	 * @var		$request
	 * @var		$lang
	 * @var		$member
	 * @var		$memberData
	 * @var		$cache
	 * @var		$caches
	 */
	protected $registry;
	protected $DB;
	protected $settings;
	protected $request;
	protected $lang;
	protected $member;
	protected $memberData;
	protected $cache;
	protected $caches;
	
	/**
	 * Constructor
	 *
	 * @param	object		$registry		Registry object
	 * @return	@e void
	 */
	public function __construct( ipsRegistry $registry )
	{
		$this->registry 	= $registry;
		$this->DB	    	= $this->registry->DB();
		$this->settings		=& $this->registry->fetchSettings();
		$this->request		=& $this->registry->fetchRequest();
		$this->member   	= $this->registry->member();
		$this->memberData	=& $this->registry->member()->fetchMemberData();
		$this->cache		= $this->registry->cache();
		$this->caches		=& $this->registry->cache()->fetchCaches();
		$this->lang 		= $this->registry->class_localization;
	}

/**
 * Bulk mail start
 *
 * @param	array 		Mail data
 * @param	int			Number of members to send this to
 * @return	string		HTML
 */
public function mailSendStart( $mail, $count=0 ) {

$countmembers = sprintf($this->lang->words['b_countmember'], $count );

$IPBHTML = "";
//--starthtml--//

$IPBHTML .= <<<HTML
<div class='information-box'>
	<h3><strong>{$this->lang->words['bulkmail_notes_tl']}</strong></h3> 
	{$this->lang->words['bulkmail_notes_info']}
</div>
<br />
<div class='section_title'>
	<h2>{$this->lang->words['b_title']}</h2>
</div>
<form name='theAdminForm' id='adminform' action='{$this->settings['base_url']}{$this->form_code}&amp;do=mail_send_complete' method='post'>
	<input type='hidden' name='id' value='{$mail['mail_id']}' />
	<input type='hidden' name='_admin_auth_key' value='{$this->registry->getClass('adminFunctions')->_admin_auth_key}' />
	<div class='acp-box'>
		<h3>{$countmembers}</h3>
	
		<table class='ipsTable double_pad' cellspacing='0' cellpadding='0'>
			<tr>
				<th colspan='2'>{$this->lang->words['b_maildetails']}</th>
			</tr>
			<tr>
		 		<td style='width: 40%;'>
					<label>{$this->lang->words['b_subject']}</label>
				</td>
		 		<td style='width: 60%'>
		 			{$mail['mail_subject']}
		 		</td>
		 	</tr>
			<tr>
		 		<td colspan='2'>
					<label>{$this->lang->words['b_content']}</label>
					<br />
					<div style='margin-top: 10px; margin-left: 15px;'>
						<iframe width='100%' height='200px' scrollbars='auto' src='{$this->settings['base_url']}{$this->form_code}&amp;do=mail_preview_do&amp;id={$mail['mail_id']}'></iframe>
					</div>
				</td>
		 	</tr>
			<tr>
				<th colspan='2'>{$this->lang->words['b_sending']}</th>
			</tr>
			<tr>
		 		<td colspan='2'>
					{$this->lang->words['b_sending_info']}
				</td>
		 	</tr>
		</table>
		<div class='acp-actionbar'>
			{$this->lang->words['b_percycle']} <input type='text' class='input_text' size='5' name='pergo' value='20' /> &nbsp; <input type='submit' value='{$this->lang->words['b_mailbutton']}' class='realbutton' />
		</div>
	</div>
</form>
HTML;

//--endhtml--//
return $IPBHTML;
}

/**
 * Bulk mail form
 *
 * @param	string		Type (add|edit)
 * @param	array 		Mail data
 * @param	string		Mail content
 * @return	string		HTML
 */
public function mailForm( $type, $mail, $mail_content ) {

$dd_ltmt	= array(
				  0 => array( 'lt' , $this->lang->words['b_lessthan'] ),
				  1 => array( 'mt' , $this->lang->words['b_morethan'] )
				);
						
if ( $type == 'add' )
{
	$title			= $this->lang->words['b_step1'];
	$button			= $this->lang->words['b_proceed'];
	$html_checked	= 0;
}
else
{
	$title			= $this->lang->words['b_editstored'];
	$button			= $this->lang->words['b_edit'];
	
	//-----------------------------------------
	// Unpack more..
	//-----------------------------------------
	
	$tmp = unserialize( stripslashes( $mail['mail_opts'] ) );
	
	if ( is_array( $tmp ) and count ( $tmp ) )
	{
		foreach( $tmp as $k => $v )
		{
			if ( ! $mail[ $k ] )
			{
				$mail[ $k ] = $v;
			}
		}
	}
	
	$html_checked	= $mail['mail_html_on'];
	
}

$form						= array();
$form['groups']				= '';
$form['mail_subject']		= $this->registry->output->formInput( 'mail_subject', htmlspecialchars( IPSText::stripslashes( $_POST['mail_subject'] ? $_POST['mail_subject'] : $mail['mail_subject'] ), ENT_QUOTES ) );
$form['mail_content']		= $this->registry->output->formTextarea( 'mail_content', $mail_content, 60, 14, '', '', "' style='width: 100%'" ); // Hacky CSS thing, but eh
$form['mail_html']			= $this->registry->output->formCheckbox( 'mail_html_on', $html_checked );
$form['mail_post_ltmt']		= $this->registry->output->formDropdown( 'mail_post_ltmt', $dd_ltmt, $_POST['mail_post_ltml'] ? $_POST['mail_post_ltml'] : $mail['mail_post_ltmt'] );
$form['mail_filter_post']	= $this->registry->output->formSimpleInput( "mail_filter_post", $_POST['mail_filter_post'] ? $_POST['mail_filter_post'] : $mail['mail_filter_post'], 7 );
$form['mail_visit_ltmt']	= $this->registry->output->formDropdown( 'mail_visit_ltmt', $dd_ltmt, $_POST['mail_visit_ltml'] ? $_POST['mail_visit_ltml'] : $mail['mail_visit_ltmt'] );
$form['mail_filter_visit']	= $this->registry->output->formSimpleInput( "mail_filter_visit", $_POST['mail_filter_visit'] ? $_POST['mail_filter_visit'] : $mail['mail_filter_visit'], 7 );
$form['mail_joined_ltmt']	= $this->registry->output->formDropdown( 'mail_joined_ltmt', $dd_ltmt, $_POST['mail_joined_ltml'] ? $_POST['mail_joined_ltml'] : $mail['mail_joined_ltmt'] );
$form['mail_filter_joined']	= $this->registry->output->formSimpleInput( "mail_filter_joined", $_POST['mail_filter_joined'] ? $_POST['mail_filter_joined'] : $mail['mail_filter_joined'], 7 );

foreach( $this->cache->getCache('group_cache') as $g )
{
	if ( $g['g_id'] == $this->settings['guest_group'] )
	{
		continue;
	}
	
	$checked = 0;
	
	if ( $mail['mail_groups'] )
	{
		if ( strstr( ',' . $mail['mail_groups'] . ',', ',' . $g['g_id'] . ',' ) )
		{
			$checked = 1;
		}
	}
	
	$form['groups'] .=  $this->registry->output->formCheckbox( 'sg_' . $g['g_id'], $checked ) . "&nbsp;&nbsp;<b>{$g['g_title']}</b><br />";
}

			
$IPBHTML = "";
//--starthtml--//

$IPBHTML .= <<<HTML
<div class='information-box'>
	<h3><strong>{$this->lang->words['bulkmail_notes_tl']}</strong></h3> 
	{$this->lang->words['bulkmail_notes_info']}
</div>
<br />
<div class='section_title'>
	<h2>{$this->lang->words['b_title']}</h2>
</div>

<script type='text/javascript'>
function runpreview()
{
	thisval = document.theAdminForm.mail_content.value;

	if( document.theAdminForm.mail_html_on.checked == true )
	{
		thatval = 1;
	}
	else
	{
		thatval = 0;
	}
	myUrl	= '{$this->settings['base_url']}{$this->form_code_js}&do=mail_preview';
	myUrl	= myUrl.replace( /&amp;/g, '&' );
	
	myWin   = window.open( myUrl,'newWin','width=500,height=500,resizable=yes,scrollbars=yes');
}
</script>

<form name='theAdminForm' id='adminform' action='{$this->settings['base_url']}{$this->form_code}&amp;do=mail_save' method='post'>
	<input type='hidden' name='id' value='{$mail['mail_id']}' />
	<input type='hidden' name='type' value='{$type}' />
	<input type='hidden' name='_admin_auth_key' value='{$this->registry->getClass('adminFunctions')->_admin_auth_key}' />
	
	<div class='acp-box'>
		<h3>{$title}</h3>
		
		<table class='ipsTable double_pad' cellspacing='0' cellpadding='0'>
			<tr>
				<th colspan='2'>{$this->lang->words['b_step1_title']}</th>
			</tr>
			<tr>
		 		<td class='field_title'>
					<strong class='title'>{$this->lang->words['b_subject']}</strong>
				</td>
		 		<td class='field_field'>
		 			{$form['mail_subject']}
		 		</td>
		 	</tr>
			<tr>
		 		<td class='field_title'>
					<strong class='title'>{$this->lang->words['b_content']}</strong>
				</td>
				<td class='field_field'>
					{$form['mail_content']}<br />
					<p style='margin-top: 5px' class='desctext'>
						{$form['mail_html']}&nbsp;{$this->lang->words['b_html']}
					</p>
				</td>
		 	</tr>
		</table>
	</div>
	<br />
	<div class='acp-box'>
		<h3>{$this->lang->words['b_step2']}</h3>
		
		<div style='width: 100%;'>
			<table class='ipsTable double_pad' cellspacing='0' cellpadding='0'>
				<tr>
					<th colspan='2'>{$this->lang->words['b_addfilters']}</th>
					<th>{$this->lang->words['b_groups']}</th>
				</tr>
				<tr>
			 		<td class='field_title' style='width: 25%; max-width: 280px; min-width: 230px;'>
						<strong class='title'>{$this->lang->words['b_nposts']}</strong>
					</td>
			 		<td class='field_field' style='width: 40%'>
			 			{$form['mail_post_ltmt']} {$form['mail_filter_post']}<br />
						<span class='desctext'>{$this->lang->words['b_leaveblank']}</span>
			 		</td>
					<td style='width: 30%; line-height: 1.5; vertical-align: top' class='larger_text' rowspan='3'>
						{$form['groups']}
					</td>
			 	</tr>
				<tr>
					<td class='field_title'>
						<strong class='title'>{$this->lang->words['b_ndays']}</strong>
					</td>
					<td class='field_field'>
						{$form['mail_visit_ltmt']} {$form['mail_filter_visit']}<br />
						<span class='desctext'>{$this->lang->words['b_leaveblank']}</span>
					</td>
				</tr>
				<tr>
					<td class='field_title'>
						<strong class='title'>{$this->lang->words['b_njoined']}</strong>
					</td>
					<td class='field_field'>
						{$form['mail_joined_ltmt']} {$form['mail_filter_joined']}<br />
						<span class='desctext'>{$this->lang->words['b_leaveblank']}</span>
					</td>
				</tr>
			</table>
		</div>
		<div class='acp-actionbar'>
			<input class='realbutton' onclick='runpreview()' type='button' value='{$this->lang->words['b_preview']}' /> &nbsp; &nbsp; <input class='realbutton' type='submit' value='{$button}' />
		</div>
	</div>
	<br />

<br />
	
	<div class='information-box'>
		<h3><strong>{$this->lang->words['b_qtag']}</strong></h3>
		{$this->lang->words['b_qtag_info']}
		<br /><br />
		<table width='100%'>
			<tr>
				<td><strong>{board_name}</strong></td>
				<td>{$this->lang->words['b_qbname']}</td>
				<td><strong>{board_url}</strong></td>
				<td>{$this->lang->words['b_qboardurl']}</td>
			</tr>
			<tr>
				<td><strong>{reg_total}</strong></td>
				<td>{$this->lang->words['b_qmtotal']}</td>
				<td><strong>{total_posts}</strong></td>
				<td>{$this->lang->words['b_qptotal']}</td>
			</tr>
			<tr>
				<td><strong>{busy_count}</strong></td>
				<td>{$this->lang->words['b_qonline']}</td>
				<td><strong>{busy_time}</strong></td>
				<td>{$this->lang->words['b_qonlinetime']}</td>
			</tr>
			<tr>
				<td><strong>{member_id}</strong></td>
				<td>{$this->lang->words['b_qid']}</td>
				<td><strong>{member_name}</strong></td>
				<td>{$this->lang->words['b_qmname']}</td>
			</tr>
			<tr>
				<td><strong>{member_joined}</strong></td>
				<td>{$this->lang->words['b_qjoin']}</td>
				<td><strong>{member_posts}</strong></td>
				<td>{$this->lang->words['b_qposts']}</td>
			</tr>
		 </table>
	</div>
</form>
HTML;

//--endhtml--//
return $IPBHTML;
}

/**
 * HTML to show inside preview iframe
 *
 * @param	string		Content
 * @return	string		HTML
 */
public function mailIframeContent( $content ) {

$IPBHTML = "";
//--starthtml--//

$IPS_DOC_CHAR_SET = IPS_DOC_CHAR_SET;

$IPBHTML .= <<<HTML
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset={$IPS_DOC_CHAR_SET}" />
	</head>
	<body>
		<div style='padding:6px;text-align:left;font-family:courier, monospace;font-size:12px'>
			{$content}
		</div>
	</body>
</html>
HTML;

//--endhtml--//
return $IPBHTML;
}

/**
 * HTML to show in popup
 *
 * @return	string		HTML
 */
public function mailPopupContent() {

$IPBHTML = "";
//--starthtml--//

$IPS_DOC_CHAR_SET = IPS_DOC_CHAR_SET;

$IPBHTML .= <<<HTML
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset={$IPS_DOC_CHAR_SET}" />
	</head>
	<body onload='doitdude()'>
		<script type='text/javascript'>
			posty = opener.thisval;
			pisty = opener.thatval;
			   
			function doitdude()
			{
				$('theForm').action		= '{$this->settings['base_url']}{$this->form_code_js}&do=mail_preview_do'.replace( /&amp;/g, '&' );
				$('theForm_text').value	= posty;
				$('theForm_html').value	= pisty;
				$('theForm').submit();
			}
		</script>
		<form name='peekaboo' id='theForm'  method='post'>
		<input type='hidden' id='theForm_text' name='text' />
		<input type='hidden' id='theForm_html' name='html' />
		</form>
	</body>
</html>
HTML;

//--endhtml--//
return $IPBHTML;
}


/**
 * Saved bulk mails overview
 *
 * @param	string		Content
 * @return	string		HTML
 */
public function mailOverviewWrapper( $content, $pages ) {

$IPBHTML = "";
//--starthtml--//

$IPBHTML .= <<<HTML
<div class='information-box'>
	<h3><strong>{$this->lang->words['bulkmail_notes_tl']}</strong></h3> 
	{$this->lang->words['bulkmail_notes_info']}
</div>
<br />
<div class='section_title'>
	<h2>{$this->lang->words['b_title']}</h2>
	<ul class='context_menu'>
		<li><a href='{$this->settings['base_url']}{$this->form_code}&amp;do=mail_new'><img src='{$this->settings['skin_acp_url']}/images/icons/add.png' alt='' /> {$this->lang->words['b_create']}</a></li>
	</ul>
</div>

<div class='acp-box'>
	<h3>{$this->lang->words['b_stored']}</h3>
	<table class='ipsTable'>
		<tr>
			<th width='1%'>&nbsp;</th>
			<th width='30%'>{$this->lang->words['b_lsubject']}</th>
			<th width='15%'>{$this->lang->words['b_lsenton']}</th>
			<th width='15%'>{$this->lang->words['b_lsentto']}</th>
			<th width='15%'>{$this->lang->words['b_ltime']}</th>
			<th class='col_buttons'>&nbsp;</th>
		</tr>
HTML;
		if( $content )
		{
			$IPBHTML .= <<<HTML
 				{$content}
HTML;
		}
		else
		{
			$IPBHTML .= <<<HTML
			<tr>
				<td colspan='6' class='no_messages'>{$this->lang->words['b_nobulk']}</td>
			</tr>
HTML;
		}
		
		$IPBHTML .= <<<HTML
	
 	</table>
 	<div class='acp-actionbar'>
		<div class="leftaction">{$pages}</div>
	</div>
</div>
HTML;

//--endhtml--//
return $IPBHTML;
}


/**
 * Bulk mail row
 *
 * @param	array 		Mail data
 * @return	string		HTML
 */
public function mailOverviewRow( $r ) {
$IPBHTML = "";
//--starthtml--//

$inprogress	= "";
$time_taken	= "";

if ( $r['mail_updated'] == $r['mail_start'] )
{
	$time_taken = $this->lang->words['b_notyet'];
}
else
{
	$time_taken = intval($r['mail_updated'] - $r['mail_start']);
	
	if ( $time_taken < 0 )
	{
		$time_taken = 0;
	}
	
	if ( $time_taken )
	{
		$time_taken = ceil( $time_taken / 60 );
	}
	
	$time_taken .= $this->lang->words['b_minutes'];
}

if ( $r['mail_active'] )
{
	$inprogress = " ( {$this->lang->words['b_inprogress']} - <a href='#' class='ipsBadge badge_red' onclick=\"acp.confirmDelete('{$this->settings['base_url']}{$this->form_code_js}&do=mail_send_cancel', '{$this->lang->words['b_cancelconfirm']}'); return false;\">{$this->lang->words['b_cancel']}</a> )";
}


$IPBHTML .= <<<HTML
<tr class='ipsControlRow'>
  <td><img src='{$this->settings['skin_acp_url']}/images/icons/email.png' /></td>
  <td><b><a href='{$this->settings['base_url']}{$this->form_code}&do=mail_edit&id={$r['mail_id']}' title='{$this->lang->words['b_editdot']}'>{$r['mail_subject']}</a></b> {$inprogress}</td>
  <td>{$r['_mail_start']}</td>
  <td>{$r['_mail_sentto']}</td>
  <td>{$time_taken}</td>
  <td class='col_buttons'>
	<ul class='ipsControlStrip'>
		<li class='i_refresh'>
			<a href='{$this->settings['base_url']}{$this->form_code}&do=mail_send_start&id={$r['mail_id']}' title='{$this->lang->words['b_resenddot']}'>{$this->lang->words['b_resenddot']}</a>
		</li>
		<li class='i_edit'>
			<a href='{$this->settings['base_url']}{$this->form_code}&do=mail_edit&id={$r['mail_id']}' title='{$this->lang->words['b_editdot']}'>{$this->lang->words['b_editdot']}</a>
		</li>
		<li class='i_delete'>
			<a href='#' onclick='return acp.confirmDelete("{$this->settings['base_url']}{$this->form_code}&do=mail_delete&id={$r['mail_id']}");' title='{$this->lang->words['b_deletedot']}'>{$this->lang->words['b_deletedot']}</a>
		</li>
	</ul>
  </td>
</tr>
HTML;

//--endhtml--//
return $IPBHTML;
}

}