<?php
/**
 * @file		PortalCache.php 	
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


class PortalCache {
	private $cache;
	
	protected $registry;
	
	
	function __construct(ipsRegistry $registry) {
		$this->registry = $registry;
        $this->cache = ipsRegistry::cache();
        
        if ( ! $this->registry->isClassLoaded( 'members' ) ) {
            $classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'portal' ) . '/sources/members.php', 'Members' );
            $this->registry->setClass( 'members', new $classToLoad( $this->registry ) );
        }
        $this->memberData =& $this->registry->member()->fetchMemberData();
	}
	
	/* 
	 * Si solo se desea cachear un usuario en particular no se pasa parámetro alguno en otro caso se pasa false
	 *
	 */
	public function getfriends( ) {
	   
    	if( ! $this->cache->getCache( 'portalfriends_' . (int)$this->memberData['member_id'] ) ) {
    			$this->setFriends();
    	}
		
		return $this->cache->getCache( 'portalfriends_' . (int)$this->memberData['member_id'] );
	}
	
	/* 
	 *
	 *
	 */
	public function setfriends( $member_id = 0 ) {
        $members = $this->registry->members->getListfriends( $member_id );
        if( ! is_array( $members ) ) {
            return $members;
        }
        
        if( ! $member_id ) {
            $member_id = (int)$member_id | (int)$this->memberData['member_id'];
        }
        
		$this->cache->setCache( 'portalfriends_' . $member_id, $members,  array( 'array' => 1, 'donow' => 1 ) );
		$this->cache->rebuildCache( 'portalfriends_' . $member_id, 'portal' );
	}
	
}