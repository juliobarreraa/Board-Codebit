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
    }
    
    public function getOutput() {
        //Load cache
        
        $this->lang->loadLanguageFile(array('public_global'), 'core'); //Load language
                
        return $this->registry->output->getTemplate('portal')->poststatusShow();
    }
}