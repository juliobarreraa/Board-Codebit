<?php
/**
 * Class to manage comments
 *
 * @author 		Matt Mecham
 * @copyright	(c) 2001 - 2010 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		Core
 * @link		http://www.invisionpower.com
 * @version		$Rev: 10914 $
 *
 */

if ( ! defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

IPSLib::loadLibrary( IPS_ROOT_PATH . 'sources/classes/comments/bootstrap.php', 'classes_comments_renderer' );

abstract class commentsBootstrap extends classes_comments_renderer
{
	/**
	 * CONSTRUCTOR
	 *
	 * @return	@e void
	 */
	public function __construct()
	{
		parent::__construct();
		
        if ( ! $this->registry->isClassLoaded( 'publish' ) ) {
            $classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'portal' ) . '/sources/classes/publish/publish.php', 'publish' );
            $this->registry->setClass( 'publish', new $classToLoad( $this->registry ) );
        }
	}
    
	/**
	 * Deletes a comment
	 *
	 * @param	int		Image ID of parent
	 * @param	mixed	Comment ID or array of comment IDs
	 * @param	array	Member Data of current member
	 * @reutrn	html
	 * EXCEPTIONS
	 * MISSING_DATA		Ids missing
	 * NO_PERMISSION	No permission
	 */
	public function delete( $parentId, $commentId, $memberData )
	{
	echo "lala";exit;
    	parent::delete( $parentId, $commentId, $memberData );
	}
}