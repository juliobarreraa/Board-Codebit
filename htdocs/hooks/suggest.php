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
class suggestfriends
{
    //Protected
    protected $registry;
    protected $DB;
    
    //Public
    public $lang;
    
    public function __construct() {
        $this->registry     = ipsRegistry::instance();
        $this->lang         = $this->registry->getClass('class_localization');
        $this->memberData	=& $this->registry->member()->fetchMemberData();
        $this->DB           = ipsRegistry::DB();
    }
    
    public function getOutput() {
    	$this->registry->class_localization->loadLanguageFile( array( 'public_profile' ), 'members' );
    	
		/* Load status class */
		if ( ! $this->registry->isClassLoaded( 'suggest' ) )
		{
			$classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'portal' ) . '/sources/classes/member/suggest.php', 'suggest' );
			$this->registry->setClass( 'suggest', new $classToLoad( $this->registry ) );
		}
		
		$friends = array();
		
		$rows = $this->registry->suggest->getfriends();
		
		if( ! is_array( $rows ) )
		{
    		$rows = array();
		}
		
        return $this->registry->output->getTemplate('portal')->suggestShow( $rows );
    }
}