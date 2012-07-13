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
            	
    	$this->registry->class_localization->loadLanguageFile( array( 'public_profile' ), 'members' );
    	
		/* Load status class */
		if ( ! $this->registry->isClassLoaded( 'formatter' ) )
		{
			$classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'portal' ) . '/sources/classes/publish/formatter.php', 'formatter' );
			$this->registry->setClass( 'formatter', new $classToLoad( ipsRegistry::instance() ) );
		}
		
		$publish_rows = array();
		
		if( ( $rows = $this->registry->formatter->get_l_publish() ) )
		{
		      $publish_rows = $this->registry->formatter->setFormatPubs( $rows, array( 'avatars' => true ) );
		}
		
		
		foreach( $publish_rows as $prow )
		{
    		$this->output .= $this->registry->getClass('output')->getTemplate( 'portal' )->$prow[ 'template' ]( $prow );
		}
		
        return $this->registry->output->getTemplate('portal')->poststatusShow( $this->output );
    }
}