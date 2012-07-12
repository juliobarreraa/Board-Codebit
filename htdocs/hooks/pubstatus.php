<?php

/**
 * <pre>
 * Codebit.org
 * IP.Board v3.3.0
 * @description
 * Last Updated: $Date: 10-may-2012 -006  $
 * </pre>
 * @filename            labelfriends.php
 * @author 		$Author: juliobarreraa@gmail.com $
 * @package		PRI
 * @subpackage	        
 * @link		http://www.codebit.org
 * @since		10-may-2012
 * @timestamp           17:56:10
 * @version		$Rev:  $
 *
 */

/**
 * Description of labelfriends
 *
 * @author juliobarreraa@gmail.com
 */
class pubstatus {
    //Protected
    protected $registry;
    
    //Public
    public $lang;
    
    public function __construct() {
        $this->registry = ipsRegistry::instance();
        $this->lang = $this->registry->getClass('class_localization');
        $this->memberData	=& $this->registry->member()->fetchMemberData();
    }
    
    public function getOutput() {
        $this->lang->loadLanguageFile(array('public_global'), 'core'); //Load language

    	/* System enabled? */
    	//if ( ! $this->settings['su_enabled'] )
    	//{
    //		return '';
    //	}
    	
    	$this->registry->class_localization->loadLanguageFile( array( 'public_profile' ), 'members' );
    	
		/* Load status class */
		if ( ! $this->registry->isClassLoaded( 'memberStatus' ) )
		{
			$classToLoad = IPSLib::loadLibrary( IPS_ROOT_PATH . 'sources/classes/member/status.php', 'memberStatus' );
			$this->registry->setClass( 'memberStatus', new $classToLoad( ipsRegistry::instance() ) );
		}
		
		/* Fetch */
		$statuses = $this->registry->getClass('memberStatus')->fetch( $this->memberData, array( 'friends_only' => true ) );
		
		$statuses_output = $this->registry->getClass('output')->getTemplate('boards')->hookBoardIndexStatusUpdates( $statuses );
		
        return $this->registry->output->getTemplate('portal')->poststatusShow( $statuses_output );
    }
}