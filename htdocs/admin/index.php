<?php

/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.4
 * Admin control panel gateway index.php file
 * Last Updated: $Date: 2012-06-12 10:14:49 -0400 (Tue, 12 Jun 2012) $
 * </pre>
 *
 * @author 		$Author: bfarber $
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @link		http://www.invisionpower.com
 * @version		$Rev: 10914 $
 *
 */

define( 'IPB_THIS_SCRIPT', 'admin' );
require_once( '../initdata.php' );/*noLibHook*/

if ( !isset( $_REQUEST['adsess'] ) && isset( $_SERVER['REQUEST_URI'] ) )
{
	$uri = preg_replace( '#/index.php(\?.+)?#', '', $_SERVER['REQUEST_URI'] );
	$uri = trim( $uri, '/' );
	$bits = explode( '/', $uri );
	$adminDir = array_pop( $bits );
	if ( $adminDir != CP_DIRECTORY )
	{
		header( 'Location: ' . str_replace( $adminDir, CP_DIRECTORY, $uri ) );
		exit;
	}
}

require_once( IPS_ROOT_PATH . 'sources/base/ipsRegistry.php' );/*noLibHook*/
require_once( IPS_ROOT_PATH . 'sources/base/ipsController.php' );/*noLibHook*/

ipsController::run();

exit();