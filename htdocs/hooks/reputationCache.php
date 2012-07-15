<?php
/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.4
 * Facilitates reputation plugins
 * Last Updated: $Date: 2012-06-12 10:14:49 -0400 (Tue, 12 Jun 2012) $
 * </pre>
 *
 * @author		Joshua Williams <josh@invisionpower.com>
 * @package		IP.Board
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @link		http://www.invisionpower.com
 * @since		Wednesday 14th May 2008 14:00
 */

IPSLib::loadLibrary( IPS_ROOT_PATH . 'sources/classes/class_reputation_cache.php', 'classReputationCache' );

class reputationCache extends classReputationCache
{

	/**
	 * CONSTRUCTOR
	 *
	 * @access	public
	 * @return	@e void
	 */
    public function __construct()
    {
        parent::__construct();
        
        if ( ! $this->registry->isClassLoaded( 'publish' ) ) {
            $classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'portal' ) . '/sources/classes/publish/publish.php', 'publish' );
            $this->registry->setClass( 'publish', new $classToLoad( $this->registry ) );
        }
    }
    
	/**
	 * Adds a rating to the index and updates caches
	 *
	 * @access	public
	 * @param	string		$type		Type of content, ex; Post
	 * @param	integer		$type_id	ID of the type, ex: pid
	 * @param	integer		$rating		Either 1 or -1
	 * @param	string		$message	Message associated with this rating
	 * @param	integer		$member_id	Id of the owner of the content being rated
	 * @param	string		[$app]		App for this content, by default the current application
	 * @todo 	[Future] Move forum notifications to an onRep memberSync callback
	 * @return	bool
	 */
	public function addRate( $type, $type_id, $rating, $message='', $member_id=0, $app='' )
	{
		$this->registry->getClass('class_localization')->loadLanguageFile( array( 'public_global' ), 'core' );
		
		/* Online? */
		if ( ! $this->rep_system_on )
		{
			$this->error_message = $this->lang->words['reputation_offline'];
			return false;
		}
		
		/* INIT */
		$app       = ( $app ) ? $app : ipsRegistry::$current_application;
		$rating    = intval( $rating );
		
		if ( ! $this->memberData['member_id'] )
		{
			$this->error_message = $this->lang->words['reputation_guest'];
			return false;
		}
		
		if ( $rating != -1 && $rating != 1 )
		{
			$this->error_message = $this->lang->words['reputation_invalid'];
			return false;
		}
		
		/* Check for existing rating */
		$currentRating = $this->getCurrentMemberRating( array( 'app' => $app, 'type' => $type, 'id' => $type_id, 'memberId' => $this->memberData['member_id'] ) );
		
		/* Check the point types */
		if ( $rating == -1 && IPSMember::canRepDown( $currentRating, $this->memberData ) === false )
		{
			$this->error_message = $this->lang->words['reputation_invalid'];
			return false;
		}
		
		if ( $rating == 1 && IPSMember::canRepUp( $currentRating, $this->memberData ) === false )
		{
			$this->error_message = $this->lang->words['reputation_invalid'];
			return false;
		}
		
		/* Day Cutoff */
		$day_cutoff = time() - 86400;

		/* Check Max Positive Votes */
		if( $rating == 1 )
		{
			if ( intval( $this->memberData['g_rep_max_positive'] ) === 0 )
			{
				$this->error_message = $this->lang->words['reputation_quota_pos'];
				return false;				
			}
			
			$total = $this->DB->buildAndFetch( array( 'select' => 'count(*) as votes', 
													  'from'   => 'reputation_index', 
													  'where'  => 'member_id=' . $this->memberData['member_id'] . ' AND rep_rating=1 AND rep_date > ' . $day_cutoff )	);
					
			if ( $total['votes'] >= $this->memberData['g_rep_max_positive'] )
			{
				$this->error_message = $this->lang->words['reputation_quota_pos'];
				return false;				
			}
		}
		
		/* Check Max Negative Votes if not like mode */
		if ( $rating == -1 AND ! $this->isLikeMode() )
		{
			if ( intval( $this->memberData['g_rep_max_negative'] ) === 0 )
			{
				$this->error_message = $this->lang->words['reputation_quota_neg'];
				return false;				
			}
			
			$total = $this->DB->buildAndFetch( array( 'select' => 'count(*) as votes', 
													  'from'   => 'reputation_index', 
													  'where'  => 'member_id=' . $this->memberData['member_id'] . ' AND rep_rating=-1 AND rep_date > ' . $day_cutoff )	);
													
			if( $total['votes'] >= $this->memberData['g_rep_max_negative'] )
			{
				$this->error_message = $this->lang->words['reputation_quota_neg'];
				return false;				
			}
		}		
		
		/* If no member id was passed in, we have to query it using the config file */
		if( ! $member_id )
		{
			/* Reputation Config */
			if( is_file( IPSLib::getAppDir( $app ) . '/extensions/reputation.php' ) )
			{
				$rep_author_config = array();
				require( IPSLib::getAppDir( $app ) . '/extensions/reputation.php' );/*maybeLibHook*/
			}
			else
			{
				$this->error_message = $this->lang->words['reputation_config'];
				return false;
			}
			
			if( ! $rep_author_config[$type]['column'] || ! $rep_author_config[$type]['table'] )
			{
				$this->error_message = $this->lang->words['reputation_config'];
				return false;
			}
			
			$_col	= $rep_author_config[$type]['id_field'] ? $rep_author_config[$type]['id_field'] : $type;
			
			/* Query the content author */
			$content_author = $this->DB->buildAndFetch( array( 'select' => "{$rep_author_config[$type]['column']} as id",
															   'from'   => $rep_author_config[$type]['table'],
															   'where'  => "{$_col}={$type_id}" )	);
			
			$member_id = $content_author['id'];
		}
		
		if( ! ipsRegistry::$settings['reputation_can_self_vote'] && $member_id == $this->memberData['member_id'] )
		{
			$this->error_message = $this->lang->words['reputation_yourown'];
			return false;
		}
		
		/* Query the member group */
		if( ipsRegistry::$settings['reputation_protected_groups'] )
		{
			$member_group = $this->DB->buildAndFetch( array( 'select' => 'member_group_id', 'from' => 'members', 'where' => "member_id={$member_id}" ) );
			
			if( in_array( $member_group['member_group_id'], explode( ',', ipsRegistry::$settings['reputation_protected_groups'] ) ) )
			{
				$this->error_message = $this->lang->words['reputation_protected'];
				return false;			
			}
		}
		
		/* Build the insert array */
		$db_insert = array( 'member_id'  => $this->memberData['member_id'],
							'app'        => $app,
							'type'       => $type,
							'type_id'    => $type_id,
							'rep_date'   => time(),
							'rep_msg'    => $message,
							'rep_rating' => $rating );								
		
		/* Insert */
		if ( $currentRating )
		{
			if ( $rating == -1 && $this->isLikeMode() )
			{
			    $r = $this->DB->buildAndFetch(
			                                  array
			                                  (
			                                     'select'        => 'id',
			                                     'from'          => 'reputation_index',
			                                     'where'         => "app='{$app}' AND type='{$type}' AND type_id={$type_id} AND member_id=".$this->memberData['member_id'],
   			                                  )
			                     );
			                     
				$this->DB->delete( 'reputation_index', "app='{$app}' AND type='{$type}' AND type_id={$type_id} AND member_id=".$this->memberData['member_id'] );
				
                /** 
                 * Data contiene los datos que borraremos en la base de datos de bitácora
                 * El ID de la tabla de configuración estará dado por la Clave STATUS_LIKEIT
                **/
                $pformat_data = array
                                (
                                      'configuration_id'       =>    STATUS_LIKEIT,
                                      'parent_id'              =>    intval( $r[ 'id' ] ),
                                );
                                
                //Configuramos los datos y eliminamos
                $this->registry->publish->setDataPublish( $pformat_data )->do_delete();
			}
		}
		else
		{
			$this->DB->replace( 'reputation_index', $db_insert, array( 'app', 'type', 'type_id', 'member_id' ) );
			
            /** 
             * Data contiene los datos que insertaremos en la base de datos de bitácora
             * El ID de la tabla de configuración estará dado por la Clave STATUS_LIKEIT
            **/
            $pformat_data = array
                            (
                                  'configuration_id'       =>    STATUS_LIKEIT,
                                  'parent_id'              =>    $this->DB->getInsertId(),
                            );
                            
            //Configuramos los datos e insertamos
            $this->registry->publish->setDataPublish( $pformat_data )->do_insert();
		}
		
		/* Update cache */
		$this->updateCache( $app, $type, $type_id );

		/* Get authors current rep */
		$author_points = $this->DB->buildAndFetch( array( 'select' => 'pp_reputation_points', 
														  'from'   => 'profile_portal',
														  'where'  => "pp_member_id={$member_id}" )	 );
		
		/* Figure out new rep */
		if( $currentRating['rep_rating'] == -1 )
		{
			$author_points['pp_reputation_points'] += 1;
		}
		else if ( $currentRating['rep_rating'] == 1 )
		{
			$author_points['pp_reputation_points'] -= 1;
		}
		
		/* now add on new rating if we're not like mode-ing */
		if ( ( ! $this->isLikeMode() ) || ( empty( $currentRating['rep_rating'] ) && $this->isLikeMode() ) )
		{
			$author_points['pp_reputation_points'] += $rating;
		}

		$this->DB->update( 'profile_portal', array( 'pp_reputation_points' => $author_points['pp_reputation_points'] ), "pp_member_id={$member_id}" );
		
		/* Notification */
		if ( $rating == 1 && $this->isLikeMode() && $app == 'forums' && $type == 'pid' )
		{
			/* Check for class_forums */
			if ( ! $this->registry->isClassLoaded( 'class_forums' ) )
			{
				$classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'forums' ) . "/sources/classes/forums/class_forums.php", 'class_forums', 'forums' );
				$this->registry->setClass( 'class_forums', new $classToLoad( $this->registry ) );
				$this->registry->strip_invisible = 0;
				$this->registry->class_forums->forumsInit();
			}
		
			$classToLoad   = IPSLib::loadLibrary( IPS_ROOT_PATH . '/sources/classes/member/notifications.php', 'notifications' );
			$notifyLibrary = new $classToLoad( $this->registry );
			
			if ( ! $this->registry->isClassLoaded('topics') )
			{
				$classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'forums' ) . "/sources/classes/topics.php", 'app_forums_classes_topics', 'forums' );
				$this->registry->setClass( 'topics', new $classToLoad( $this->registry ) );
			}
			
			$post        = $this->registry->getClass('topics')->getPostById( $type_id );
			
			/* Set topic data */
			$this->registry->getClass('topics')->setTopicData( $post );
			
			/* Quick check */
			if ( ! $post['author_id'] OR $post['author_id'] == $this->memberData['member_id'] )
			{
				return true;
			}
			
			$_toMember	 = IPSMember::load( $post['author_id'] );
			
			/* Set language */
			$_toMember['language'] = $_toMember['language'] == "" ? IPSLib::getDefaultLanguage() : $_toMember['language'];
			
			/* Quick permission check */
			if ( $this->registry->getClass('topics')->canView() !== true )
			{
				return true;
			}
			
			$url = $this->registry->output->buildSEOUrl( "showtopic={$post['topic_id']}&amp;view=findpost&amp;p={$post['pid']}", "publicNoSession", $post['title_seo'], 'showtopic' );
			
			IPSText::getTextClass('email')->getTemplate( "new_likes", $_toMember['language'] );
		
			IPSText::getTextClass('email')->buildMessage( array('MEMBER_NAME'	=> $this->memberData['members_display_name'],
																'SHORT_POST'	=> IPSText::truncate( IPSText::getTextClass( 'bbcode' )->stripAllTags( $post['post'] ), 300 ),
																'URL'		    => $url ) );
	
			IPSText::getTextClass('email')->subject	= sprintf(  IPSText::getTextClass('email')->subject, 
																$this->registry->output->buildSEOUrl( 'showuser=' . $this->memberData['member_id'], 'publicNoSession', $this->memberData['members_seo_name'], 'showuser' ), 
																$this->memberData['members_display_name'],
																$url,
																$this->registry->output->buildSEOUrl( "showtopic={$post['topic_id']}", "publicNoSession", $post['title_seo'], 'showtopic' ),
																IPSText::truncate( $post['topic_title'], 30 ) );
	
			$notifyLibrary->setMember( $_toMember );
			$notifyLibrary->setFrom( $this->memberData );
			$notifyLibrary->setNotificationKey( 'new_likes' );
			$notifyLibrary->setNotificationUrl( $url );
			$notifyLibrary->setNotificationText( IPSText::getTextClass('email')->message );
			$notifyLibrary->setNotificationTitle( IPSText::getTextClass('email')->subject );
			
			try
			{
				$notifyLibrary->sendNotification();
			}
			catch( Exception $e ){}
		}
		
		return true;		
	}
}