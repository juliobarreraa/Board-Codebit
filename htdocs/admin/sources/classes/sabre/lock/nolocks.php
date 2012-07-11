<?php
/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.4
 * Sabre classes by Matt Mecham
 * Last Updated: $Date: 2012-06-12 10:14:49 -0400 (Tue, 12 Jun 2012) $
 * </pre>
 *
 * @author 		$Author: bfarber $
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @link		http://www.invisionpower.com
 * @since		Friday 18th March 2011
 * @version		$Revision: 10914 $
 */
 
class sabre_lock_nolocks extends Sabre_DAV_Locks_Backend_Abstract
{
	public function getLocks( $uri )
	{
		return array();
	}

	public function lock( $uri, Sabre_DAV_Locks_LockInfo $lockInfo )
	{
		return true;
	}

	public function unlock( $uri, Sabre_DAV_Locks_LockInfo $lockInfo )
	{
		return true;
	}
}