<?php

/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.4
 * Upgrade gateway
 * Last Updated: $LastChangedDate: 2012-06-12 10:14:49 -0400 (Tue, 12 Jun 2012) $
 * </pre>
 *
 * @author 		$Author: bfarber $
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @link		http://www.invisionpower.com
 * @since		14th May 2003
 * @version		$Rev: 10914 $
 */

define( 'IPB_THIS_SCRIPT', 'admin' );
define( 'IPS_IS_UPGRADER', TRUE );
define( 'IPS_IS_INSTALLER', FALSE );

require_once( '../../initdata.php' );/*noLibHook*/

require_once( IPS_ROOT_PATH . 'setup/sources/base/ipsRegistry_setup.php' );/*noLibHook*/
require_once( IPS_ROOT_PATH . 'setup/sources/base/ipsController_setup.php' );/*noLibHook*/

ipsController::run();

exit();
