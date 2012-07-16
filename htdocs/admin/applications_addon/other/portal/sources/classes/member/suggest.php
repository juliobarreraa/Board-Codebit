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

class suggest
{
        protected $registry;
        protected $DB;
        
        private $myfriends;
        
        function __construct( ipsRegistry $registry )
        {
            $this->registry    = $registry;
            $this->DB          = ipsRegistry::DB();
            $this->memberData  =& $this->registry->member()->fetchMemberData();
        }
        
        /*
         *  Description: Obtenemos un listado de mis amigos y a la vez un listado de los amigos de mis amigos.
        **/
        function getfriends()
        {
            $r = $this->__getfriendsMe();
            
            $yourfriends = array();
            
            $yourfriends = $this->__getfriendsHe( intval( $r ) );
            
            return $yourfriends;
        }
        
        function __getfriendsMe()
        {
             $this->DB->build( array
                               (
                                       'select'       => 'pf.friends_friend_id',
                                       'from'         => array( 'profile_friends' => 'pf' ),
                                       'where'        => 'pf.friends_member_id = ' . intval( $this->memberData[ 'member_id' ] ),
                                       'order'        => 'rand()',
                               )
                        );
                        
            $o = $this->DB->execute();
                        
            while( $r = $this->DB->fetch( $o ) )
            {
                $rows[] = $r[ 'friends_friend_id' ];
            }

            if( is_array( $rows ) )
            {
                $this->myfriends = sprintf('%d, %s', $this->memberData[ 'member_id' ], join( ', ', $rows ) );
            }

            return $rows[ intval( count( $rows ) - 1 ) ];
        }
        
        /*
         * Por cada uno de nuestros amigos nos regresara un listado de nuestros amigos los cuales no son nuestros amigos.
        **/
        function __getfriendsHe( $friend_id )
        {
                $this->DB->build( array
                                  (
                                        'select'        => 'pf.friends_member_id, pf.friends_friend_id',
                                        'from'          => array( 'profile_friends' => 'pf' ),
                                        'where'         => 'pf.friends_member_id = ' . intval( $friend_id ) . " and pf.friends_friend_id not in ( {$this->myfriends} )",
                                        'order'         => 'rand()',
                                        'limit'         => array( 0, 5 ),
                                        'add_join' => array(
                                                           array
                                                           (
                                                                'select'      => 'm.members_display_name, m.member_id, m.members_seo_name',
                                                                'from'        => array('members' => 'm'),
                                                                'where'       => 'pf.friends_friend_id = m.member_id',
                                                                'type'        => 'inner',
                                                           ),
                                                           array
                                                           (
                                                                'select'      => 'pp.pp_thumb_photo, pp.pp_main_photo, pp.pp_photo_type',
                                                                'from'        => array('profile_portal' => 'pp'),
                                                                'where'       => 'm.member_id = pp.pp_member_id',
                                                           )
                                                      ),
                                  )
                        );
                        
                $o = $this->DB->execute();
                
                while( $r = $this->DB->fetch( $o ) )
                {
                    $r             = IPSMember::buildProfilePhoto($r);
                    $r[ 'common' ] = $this->__getCountCommon( $r[ 'friends_friend_id' ] );
                    $rows[]        = $r;
                }
                
                
                return $rows;
        }
        
        function __getCountCommon( $friend_id )
        {
                $count = $this->DB->buildAndFetch( array
                                          (
                                                'select'        => 'count( pf.friends_friend_id ) as count',
                                                'from'          => array( 'profile_friends' => 'pf' ),
                                                'where'         => 'pf.friends_member_id = ' . intval( $friend_id ) . " and pf.friends_friend_id not in ( {$this->myfriends} )",
                                          )
                        );
                return intval( $count[ 'count' ] + 1 );
        }
}