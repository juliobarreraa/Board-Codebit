<?php
/**
 * @file		bulkmail.php 	Task to send out bulk emails (dynamically enabled)
 *~TERABYTE_DOC_READY~
 * $Copyright: (c) 2001 - 2011 Invision Power Services, Inc.$
 * $License: http://www.invisionpower.com/company/standards.php#license$
 * $Author: ips_terabyte $
 * @since		-
 * $LastChangedDate: 2011-02-08 17:20:18 -0500 (Tue, 08 Feb 2011) $
 * @version		v3.3.3
 * $Revision: 7750 $
 */

if ( ! defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

/**
 *
 * @class		task_item
 * @brief		Task to send out bulk emails (dynamically enabled)
 *
 */
class task_item
{
	/**
	 * Object that stores the parent task manager class
	 *
	 * @var		$class
	 */
	protected $class;
	
	/**
	 * Array that stores the task data
	 *
	 * @var		$task
	 */
	protected $task = array();
	
	/**
	 * Registry Object Shortcuts
	 *
	 * @var		$registry
	 */
	protected $registry;
	protected $DB;
	
	/**
	 * Constructor
	 *
	 * @param	object		$registry		Registry object
	 * @param	object		$class			Task manager class object
	 * @param	array		$task			Array with the task data
	 * @return	@e void
	 */
	public function __construct( ipsRegistry $registry, $class, $task )
	{
		/* Make registry objects */
		$this->registry	= $registry;
		
		$this->class	= $class;
		$this->task		= $task;
		$this->DB       = ipsRegistry::DB();
		$this->cache = ipsRegistry::cache();
	}
	
	/**
	 * Run this task
	 *
	 * @return	@e void
	 */
	public function runTask()
	{
		//-----------------------------------------
		// Load 
		//-----------------------------------------

		define( 'IN_ACP', 1 );
		
		$classToLoad = IPSLib::loadActionOverloader( IPSLib::getAppDir( 'portal' ) . '/sources/PortalCache.php', 'PortalCache' );
		$portalCache    = new $classToLoad($this->registry);
		
		$rows = $this->registry->members->getOnlineUsers();
		
		foreach($rows as $member) {
   			$portalCache->setFriends( (int)$member['member_id'] );
        }
        
		
		//-----------------------------------------
		// Unlock Task: DO NOT MODIFY!
		//-----------------------------------------
		
		$this->class->unlockTask( $this->task );
	}
}