<?php
/**
 * <pre>
 * Codebit.org
 * IP.Board v3.3.3
 * Allow user to change their status
 * Last Updated: $Date: 2012-07-11 15:31:13 -0400 (Wed, 11 July 2012) $
 * </pre>
 *
 * @author 		$Author: juliobarreraa $
 * @copyright	(c) 2012 - 2015 codebit.org
 * @license		http://www.codebit.org#license
 * @package		IP.Board
 * @subpackage	Portal
 * @link		http://www.codebit.org
 * @since		Wednesday 11st July 2012 (15:30)
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
	public $su_Tags;
	private $keys = null;
	private $_friends = array();
	const USER_NAME = 'name';
	
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
        	    if(is_array($this->su_Tags)) {
            	    foreach($this->su_Tags as $tag) {
                	    $content = $this->_autoParseNamesInternal( $tag, $content );
            	    }
            	    unset($this->keys, $this->_friends);
				}
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
	protected function _autoParseNamesInternal( $matches, $content )
	{
	    //TODO: Añadir funcionalidad
	    
        //Load functions cache
        if( ! $this->keys ) {
        
            if ( ! $this->registry->isClassLoaded( 'portalCache' ) ) {
                $classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'portal' ) . '/sources/PortalCache.php', 'PortalCache' );
                $this->registry->setClass( 'portalCache', new $classToLoad( $this->registry ) );
            }
            
            $this->_friends = $this->registry->portalCache->getfriends();
            
            $this->keys = $this->registry->portalCache->getValuesByKey( 'id', $this->_friends );
        }
        
        if( ( $position = array_search( ( int ) $matches->{'id'}, $this->keys ) ) !== false )
        {
            //Se encontro entonces matched, se consulta la skin template y se retorna el output
            $_friend = $this->_friends[ $position ]; //Amigo, username, avatar, name
            
            if( array_key_exists( self::USER_NAME, $_friend ) ) { //si existe la clave de nombre entonces reemplazamos 
                $member = IPSMember::load( intval( $_friend[ 'id' ] ) );
                $userHoverCard = $this->registry->getClass('output')->getTemplate( 'global' )->userHoverCard( $member );
                $content = preg_replace("#(^|\s)(".$_friend[ self::USER_NAME ].")#", sprintf('&nbsp;%s&nbsp;', $userHoverCard), $content ); //Aquí colocamos el contenido del output
            }
        }
        
		return $content;
	}
}