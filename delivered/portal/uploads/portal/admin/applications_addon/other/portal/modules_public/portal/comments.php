<?php
/*
+--------------------------------------------------------------------------
|   Portal 1.1.0
|   =============================================
|   by Michael John
|   Copyright 2011-2012 DevFuse
|   http://www.devfuse.com
+--------------------------------------------------------------------------
|   Based on IP.Board Portal by Invision Power Services
|   Website - http://www.invisionpower.com/
+--------------------------------------------------------------------------
*/

if ( ! defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

class public_portal_portal_comments extends ipsCommand
{

	/**
	 * Main class entry point
	 *
	 * @access	public
	 * @param	object		ipsRegistry reference
	 * @return	void		[Outputs to screen]
	 */
	public function doExecute( ipsRegistry $registry ) 
	{
		/* Init some data */
		require_once( IPS_ROOT_PATH . 'sources/classes/comments/bootstrap.php' );/*noLibHook*/
		$this->_comments = classes_comments_bootstrap::controller( 'portal-status' );
		$this->DB->build(array(
		      'select' => '*',
		      'from' => 'member_status_updates',
		      'order' => 'status_id DESC',
		      'limit' => array(0, 10),
		  )
		);
		
		$this->DB->execute();
		
		while($r = $this->DB->fetch()) {
		        $rows[] = $r;
		}
		
		foreach($rows as $r) {
    	   $comment_html = $this->_comments->fetchFormatted( $r, array( 'offset' => intval( 1 ) ) );
    	   echo ($comment_html);
		}
    }
}