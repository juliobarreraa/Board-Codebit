<?php
/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.3
 * Allow user to change their status
 * Last Updated: $Date: 2012-05-10 16:10:13 -0400 (Thu, 10 May 2012) $
 * </pre>
 *
 * @author 		$Author: bfarber $
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @subpackage	Members
 * @link		http://www.invisionpower.com
 * @since		Tuesday 1st March 2005 (11:52)
 * @version		$Revision: 10721 $
 *
 */

if ( ! defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

IPSLib::loadLibrary( IPS_ROOT_PATH . 'sources/classes/member/status.php', 'memberStatus' );

class portalMemberStatus extends memberStatus
{
	/**
	 * Auto parse some stuff
	 *
	 * Eventually could abstract it out but for now, this will do. Mkay.
	 */
	protected function _parseContent( $content, $creator='' )
	{
		/* Auto parse tags */
		if ( $this->settings['su_parse_url'] )
		{
			$content = preg_replace_callback( '#(^|\s|\(|>|\](?<!\[url\]))((?:http|https|news|ftp)://\w+[^\),\s\<\[]+)#is', array( $this, '_autoParseUrls' ), $content );
		}
		
		/* Twittah? */
		if ( $creator == 'twitter' )
		{
			if ( $this->settings['tc_parse_tags'] )
			{
				$content = preg_replace_callback( '#(^|\s)(\\#([a-z_A-Z0-9:_-]+))#', array( $this, '_autoParseTags' ), $content );
			}
			
			if ( $this->settings['tc_parse_names'] )
			{
				$content = preg_replace_callback('#(^|\s)@([a-z_A-Z0-9]+)#', array( $this, '_autoParseNames' ), $content );
			}
		}
		
		return $content;
	}
}