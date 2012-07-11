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
	 * CONSTRUCTOR
	 *
	 * @param	object	Registry
	 * @return	@e void
	 */
	public function __construct( ipsRegistry $registry )
	{
		parent::__construct( $registry );
		
		$this->settings['tc_parse_names_internal'] = 1;
	}
	
	/**
	 * Auto parse some stuff
	 *
	 * Eventually could abstract it out but for now, this will do. Mkay.
	 */
	protected function _parseContent( $content, $creator='' )
	{
		parent::_parseContent( $content, $creator );
		
		/* Portal update? */
		if ( $creator == 'portal' )
		{
			if ( $this->settings['tc_parse_names_internal'] )
			{
				$content = preg_replace_callback( '#(^|\s)(\\#([a-z_A-Z0-9:_-]+))#', array( $this, '_autoParseNamesInternal' ), $content );
			}
		}
		
		return $content;
	}
	
	/**
	 * Callback to auto-parse @names
	 * 
	 * @param	array		Matches from the regular expression
	 * @return	string		Converted text
	 */
	protected function _autoParseNamesInternal( $matches )
	{
	    //TODO: Añadir funcionalidad
		return '';//$this->_autoParseUrls( array( '', $matches[1], 'http://www.twitter.com/' . urlencode( $matches[2] ), '@' . $matches[2] ) );
	}
}