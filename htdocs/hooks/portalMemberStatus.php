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
		
        if ( ! $this->registry->isClassLoaded( 'publish' ) ) {
            $classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'portal' ) . '/sources/classes/publish/publish.php', 'publish' );
            $this->registry->setClass( 'publish', new $classToLoad( $this->registry ) );
        }
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
		
		/* Auto parse tags */
		if ( $this->settings['su_parse_url'] )
		{
			$content = preg_replace_callback( '#(^|\s|\(|>|\](?<!\[url\]))((?:http|https|news|ftp)://\w+[^\),\s\<\[]+)#is', array( $this, '_autoParseUrls' ), $content );
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
	
    public function create( $author=null, $owner=null )
    {
    	$author = ( $author === null ) ? $this->getAuthor() : $author;
    	$_owner = $this->getStatusOwner();
    	$owner  = ( $owner  === null ) ? ( ! empty( $_owner['member_id'] ) ? $_owner : $author ) : $owner;
    	$data	= array();
    	
    	if ( $this->canCreate( $author, $owner ) )
    	{
    		if ( $this->getContent() )
    		{
    			$content = $this->_cleanContent( $this->getContent() );
    			$hash    = IPSText::contentToMd5( $content );
    			
    			/* Check for this status update already created */
    			$test = $this->fetchByHash( $owner['member_id'], $hash );
    			
    			if ( $test['status_id'] )
    			{
    				/* Already imported this one */
    				return FALSE;
    			}
    			
    			$dataAttach = array();
        		if( is_array( $this->su_attachment ) )
        		{
        			list( $image, $title, $url, $description ) = $this->su_attachment[ 'attachment' ][ 'params' ][ 'metaTagMap' ];
        			$dataAttach = array
        			              (
        			                     'image'         => $image,
        			                     'title'         => $title,
        			                     'url'           => $url,
        			                     'description'   => $description
        			              );
        		}
    			
    			$data = array( 'status_member_id' => $owner['member_id'],
    						   'status_author_id' => $author['member_id'],
							   'status_date'	  => time(),
							   'status_content'   => $this->_parseContent( $content, $this->_internalData['Creator'] ),
							   'status_hash'      => $hash,
							   'status_replies'	  => 0,
    						   'status_author_ip' => $this->member->ip_address,
    						   'status_approved'  => $this->getIsApproved(),
							   'status_imported'  => intval( $this->_internalData['IsImport'] ),
							   'status_creator'   => trim( addslashes( $this->_internalData['Creator'] ) ),
							   'status_last_ids'  => '',
							   'status_cache'     => serialize( $dataAttach ) );

				/* Data Hook Location */
				IPSLib::doDataHooks( $data, 'statusUpdateNew' );
		
    			$this->DB->insert( 'member_status_updates', $data );
    			
    			$status_id = $this->DB->getInsertId();
    			
    			$data['status_id']	= $status_id;
    			 
    			if ( $owner['member_id'] != $author['member_id'] )
    			{
    				$this->_sendCommentNotification( $author, $owner, $data );
    			}
    			else
    			{
	    			$this->_recordAction( 'new', $author, $data );
	    			
	    			$this->rebuildOwnerLatest( $owner );
	    			
	    			/* Fire off external updates */
	    			$eU = $this->getExternalUpdates();
	    			
	    			if ( ! $this->_internalData['IsImport'] AND is_array( $eU ) )
	    			{
	    				$this->_triggerExternalUpdates( $eU, $status_id, $owner, $content );
	    			}
	    			
	    			//-----------------------------------------
	    			// Notify owner's friends as configured
	    			//-----------------------------------------
	    			
	    			$friends	= array();
	    			
	    			if ( $this->settings['friends_enabled'] AND $author['member_id'] == $owner['member_id'] )
	    			{
		    			$this->DB->build( array( 'select' => 'friends_member_id, friends_approved', 'from' => 'profile_friends', 'where' => 'friends_friend_id=' . $owner['member_id'] ) );
		    			$this->DB->execute();
		    			
		    			while( $_friend = $this->DB->fetch() )
		    			{
		    				if ( $_friend['friends_approved'] )
		    				{
		    					$friends[ $_friend['friends_member_id'] ] = $_friend['friends_member_id'];
		    				}
		    			}
					}
					
					if( count($friends) )
					{
						//-----------------------------------------
						// Notifications library
						//-----------------------------------------
						
						$classToLoad	= IPSLib::loadLibrary( IPS_ROOT_PATH . '/sources/classes/member/notifications.php', 'notifications' );
						$notifyLibrary	= new $classToLoad( $this->registry );
						
		    			$friends = IPSMember::load( $friends );
		    			
		    			$statusUrl = $this->registry->output->buildSEOUrl( 'app=members&amp;module=profile&amp;section=status&amp;type=single&amp;status_id=' . $status_id, 'publicNoSession', 'true', 'members_status_single' );
		    			
		    			foreach( $friends as $friend )
		    			{
		    				$ndata = array( 'NAME'		=> $friend['members_display_name'],
				    						'OWNER'		=> $owner['members_display_name'],
											'STATUS'	=> $data['status_content'],
											'URL'		=> $this->registry->output->buildSEOUrl( 'app=core&amp;module=usercp&amp;tab=core&amp;area=notifications', 'publicNoSession' ) );
							
							IPSText::getTextClass('email')->getTemplate( 'new_status', $friend['language'] );
							IPSText::getTextClass('email')->buildMessage( $ndata );
							
							IPSText::getTextClass('email')->subject	= sprintf( 
																				IPSText::getTextClass('email')->subject, 
																				$this->registry->output->buildSEOUrl( 'showuser=' . $owner['member_id'], 'publicNoSession', $owner['members_seo_name'], 'showuser' ),
																				$owner['members_display_name'],
																				$statusUrl
																			);
			
							$notifyLibrary->setMember( $friend );
							$notifyLibrary->setFrom( $author );
							$notifyLibrary->setNotificationKey( 'friend_status_update' );
							$notifyLibrary->setNotificationUrl( $statusUrl );
							$notifyLibrary->setNotificationText( IPSText::getTextClass('email')->message );
							$notifyLibrary->setNotificationTitle( IPSText::getTextClass('email')->subject );
							
							try
							{
								$notifyLibrary->sendNotification();
							}
							catch( Exception $e ){}
						}
					}
	    		}
    		}
    		
            /** 
             * Data contiene los datos que insertaremos en la base de datos de bitácora
             * Si status_member_id es identico a status_author_id es una publicación realizada al público/amigos en otro caso es realizada a status_member_id desde status_author_id
             * El ID de la tabla de configuración estará dado por la Clave STATUS_UPDATE
            **/
            
            $pformat_data = array
                            (
                                  'configuration_id'       =>    STATUS_UPDATES,
                                  'parent_id'              =>    $data[ 'status_id' ],
                            );
                            
            //Configuramos los datos e insertamos
            $this->registry->publish->setDataPublish( $pformat_data )->do_insert();
    		
    		return $data;
    	}
    	
    	return FALSE;
    }
}