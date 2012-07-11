<?php

/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.4
 * Login handler abstraction
 * Last Updated: $Date: 2012-06-12 10:14:49 -0400 (Tue, 12 Jun 2012) $
 * </pre>
 *
 * @author 		$Author: bfarber $
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @link		http://www.invisionpower.com
 * @since		Tuesday 1st March 2005 (11:52)
 * @version		$Revision: 10914 $
 *
 */

interface interface_login
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	object		ipsRegistry reference
	 * @param	array 		Configuration info for this method
	 * @param	array 		Custom configuration info for this method
	 * @return	@e void
	 */
	public function __construct( ipsRegistry $registry, $method, $conf=array() );

	/**
	 * Authenticate via local database
	 *
	 * @param	string		Username [Username or Email Address must be supplied]
	 * @param	string		Email Address [Username or Email Address must be supplied]
	 * @param	string		Password
	 * @return	boolean		Authentication successful
	 */
	public function authLocal( $username, $email_address, $password );
	
	/**
	 * Create a record of the user locally
	 *
	 * @param	array 		Member information
	 * @return	@e void
	 */
	public function createLocalMember( $member );
	
	/**
	 * Normal authentication routine for the login method
	 *
	 * @param	string		Username  [Username or Email Address must be supplied]
	 * @param	string		Email Address  [Username or Email Address must be supplied]
	 * @param	string		Password
	 * @return	boolean
	 */
	public function authenticate( $username, $email_address, $password );
}
