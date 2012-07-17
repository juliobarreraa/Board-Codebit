<?php

/**
 * <pre>
 * Invision Power Services
 * IP.Board v4.2.1
 * Reputation configuration for application
 * Last Updated: $Date: 2011-10-19 11:07:17 -0400 (Wed, 19 Oct 2011) $
 * </pre>
 *
 * @author 		$author$
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/community/board/license.html
 * @package		IP.Board
 * @subpackage	Forums
 * @link		http://www.invisionpower.com
 * @version		$Rev: 9635 $ 
 */

if ( ! defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

$rep_author_config = array( 
						'portal_comment_id' => array( 'column' => 'portal_comment_author_id ', 'table'  => 'comments_portal' ),
					);
					
/*
 * The following config items are for the log viewer in the ACP 
 */

$rep_log_joins = array(
						array(
								'from'   => array( 'comments_portal' => 'cp' ),
								'where'  => 'r.type="portal_comment_id" AND r.type_id=cp.portal_comment_id AND r.app="portal"',
								'type'   => 'left'
							),
						array(
								'select' => 't.status_content as repContentTitle, t.status_id as repContentID',
								'from'   => array( 'member_status_updates' => 't' ),
								'where'  => 'cp.portal_comment_parent_id=t.status_id',
								'type'   => 'left'
							),
					);

$rep_log_where = "cp.portal_comment_author_id=%s";

$rep_log_link = 'app=portal&amp;module=portal&amp;section=viewcomment&amp;status_id=%d#comment_%d';