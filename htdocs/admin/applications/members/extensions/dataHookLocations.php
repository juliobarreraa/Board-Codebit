<?php
/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.4
 * Define data hook locations (Members)
 * Last Updated: $Date: 2012-06-12 10:14:49 -0400 (Tue, 12 Jun 2012) $
 * </pre>
 *
 * @author 		$Author: bfarber $
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @subpackage	Core
 * @link		http://www.invisionpower.com
 * @version		$Rev: 10914 $
 */

$dataHookLocations = array(

	/* MESSENGER DATA LOCATIONS */
	array( 'messengerSendReplyData', 'Messenger: Reply data'),
	array( 'messengerSendTopicData', 'Messenger: New conversation, topic data' ),
	array( 'messengerSendTopicFirstPostData', 'Messenger: New conversation, first post' ),
	
	/* PROFILE DATA LOCATIONS */
	array( 'statusUpdateNew', 'New Status Update' ),
	array( 'statusCommentNew', 'New Status Comment' ),
	array( 'profileFriendsNew', 'Profile: New friend' ),
	
	/* MEMBER WARNINGS LOCATIONS */
	array( 'memberWarningPre', 'Warn Member (Pre Save)' ),
	array( 'memberWarningPost', 'Warn Member (Post Save)' ),
	
);