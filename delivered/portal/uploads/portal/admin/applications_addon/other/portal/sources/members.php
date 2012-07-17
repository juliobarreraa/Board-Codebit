<?php
/**
 * @file		members.php 	
 *~TERABYTE_DOC_READY~
 * $Copyright: (c) 2012 Codebit.org.$
 * $License: http://www.codebit.org$
 * $Author: codebit_org $
 * @since		-
 * $LastChangedDate: 2012-07-09 17:20:18 -0500 (Mon, 09 July 2012) $
 * @version		v3.3.3
 * $Revision: 7750 $
 */

if ( ! defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}
class Members {
    protected $registry;
    protected $DB;
    
    const NOT_IS_MEMBER = -1;
	
	//Constantes con valores fijos para el valor devuelto en la respuesta JSON
	const ID = 'id';
	const NAME = 'name';
	const AVATAR = 'avatar';
	const TYPE = 'type';
    
    function __construct(ipsRegistry $registry) {
        $this->registry = $registry;
        $this->DB = ipsRegistry::DB();
        $this->memberData =& $this->registry->member()->fetchMemberData();
        $this->settings = & $this->registry->fetchSettings(); //Get settings timeline_max_status
    }
    
    function getListfriends($member_id = 0) {
        $member_id = (int)$member_id | (int)$this->memberData['member_id'];
        
		/* Quick check? */
		if ( ! $member_id )
 		{
			return self::NOT_IS_MEMBER;
		}

        
        /* Are we already friends? */
        $this->DB->build(array(
                'select' => 'pf.friends_id',
                'from' => array('profile_friends' => 'pf'),
                'where' => "friends_member_id = $member_id AND friends_approved = 1",
                'add_join' => array(
                    array(
                        'select' => 'm.members_display_name, m.member_id, m.members_seo_name',
                        'from' => array('members' => 'm'),
                        'where' => 'pf.friends_friend_id = m.member_id',
                        'type' => 'inner'
                    ),
                    array(
                        'select' => 'pp.pp_thumb_photo, pp.pp_main_photo, pp.pp_photo_type',
                        'from' => array('profile_portal' => 'pp'),
                        'where' => "m.member_id = pp.pp_member_id",
                    ),
                ),
            )
        );

        $this->DB->execute();
        
        $members = array();
        while ($row = $this->DB->fetch()) {
            $row = IPSMember::buildProfilePhoto($row);
            $members[] = array(
                self::ID => $row['member_id'],
                self::NAME => $row['members_display_name'],
                self::AVATAR => $row['pp_mini_photo'],
                self::TYPE => 'friend',
            );
        }
        return $members;
    }
    
    function getOnlineUsers() {
		$cut_off = $this->settings['au_cutoff'] * 60;
		$time    = time() - $cut_off;
		
		$this->DB->build( array('select' => 'member_id',
								'from'   => 'sessions',
								'where'  => "running_time > {$time}" )	);
		$this->DB->execute();
		
		//-----------------------------------------
		// FETCH...
		//-----------------------------------------
		
		while ( $r = $this->DB->fetch() )
		{
			$rows[ $r['running_time'].'.'.$r['id'] ] = $r;
		}
		
		krsort( $rows );
		
		return $rows;
    }
}