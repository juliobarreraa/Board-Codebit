<?php
/**
 * <pre>
 * Codebit.org
 * IP.Board v3.3.3
 * Allow user to change their status
 * Last Updated: $Date: 2012-07-11 15:31:13 -0400 (Wed, 11 July 2012) $
 * </pre>
 *
 * @author 		$Author: juliobarreraa $
 * @copyright	(c) 2012 - 2015 codebit.org
 * @license		http://www.codebit.org#license
 * @package		IP.Board
 * @subpackage	Portal
 * @link		http://www.codebit.org
 * @since		Wednesday 11st July 2012 (15:30)
 * @version		$Revision: 10721 $
 *
 */

if ( ! defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

define( 'STATUS_UPDATES', 1 );
define( 'GALLERY_UPDATES', 2);
define( 'STATUS_LIKEIT', 3);
class publish
{
        protected $registry;
        protected $DB;
        
        private $_data = array();
        
        
        function __construct( ipsRegistry $registry )
        {
            $this->registry         = $registry;
            $this->DB               = $registry::DB();
            $this->memberData   	=& $this->registry->member()->fetchMemberData();
        }
        
        function setDataPublish( array $data )
        {
            if( ! $this->__exist_list_keys( $data, array( 'configuration_id', 'parent_id' ) ) )
            {
                return false;
            }
            
            $this->_data                    = $data;
        
            //Una vez comprobadas las etiquetas vamos a rellenar con los campos faltantes
            $this->_data[ 'member_id' ]     = $this->memberData[ 'member_id' ];
            $this->_data[ 'status_date' ]   = time();
            
            return $this;
        }
        
        function getDataPublish()
        {
            
            if( ! $this->__exist_list_keys( $this->_data, array( 'configuration_id', 'parent_id', 'member_id', 'status_date' ) ) )
            {
                return false;
            }
            
            return $this->_data;
        }
        
        function do_insert()
        {
            if( ! $this->getDataPublish() ) {
                return false;
            }
            
            $this->DB->insert( 'publish_format_data', $this->_data );
            
            return $this->DB->getInsertId();
        }
        
        function do_delete()
        {
            if( ! $this->getDataPublish() ) {
                return false;
            }
            
            $this->DB->delete( 'publish_format_data', 'configuration_id = ' . $this->_data[ 'configuration_id' ] . ' and parent_id = ' . $this->_data[ 'parent_id' ] );
            
            return true;
        }
        
        private function __exist_list_keys( array $data, array $list )
        {
            foreach( $list as $item )
            {
                if( ! array_key_exists( $item, $data ) )
                {
                    return false;
                }
            }
            return true;
        }
}