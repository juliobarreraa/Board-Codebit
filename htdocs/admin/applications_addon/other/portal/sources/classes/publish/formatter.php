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

class formatter
{
        protected $registry;
        protected $DB;
        
        function __construct( ipsRegistry $registry )
        {
            $this->registry        =  $registry;
            $this->DB              = ipsRegistry::DB();
        }
        
        function get_l_publish()
        {
            //Regresara cada una de las publicaciones que se han dado de alta en la tabla publish_format_data
            $this->DB->build( array
                              (
                                    'select'        => 'pf.id, pf.member_id, pf.status_date',
                                    'from'          => array( 'publish_format_data' => 'pf' ),
                                    'limit'         => array (0, 10 ),
                                    'add_join'      => array
                                                       (
                                                            array
                                                            (
                                                                    'select'        => 'pg.field_date, pg.field_author_id, pg.parents',
                                                                    'from'          => array( 'publish_general_configuration' => 'pg' ),
                                                                    'where'         => 'pg.enabled = 1 and pf.configuration_id = pg.id',
                                                                    'type'          => 'inner',
                                                            ),
                                                       ),
                              )
                            );
            
            $this->DB->execute();
            
            while( $r = $this->DB->fetch() )
            {
                $rows[] = $r;
            }
            
            return $rows;
        }
        
        function setFormatPubs( array $pubs )
        {
            if( count( $pubs ) > 0 )
            {
                foreach( $pubs as $pub )
                {
                    $pformats[] = $this->__getFormat( $pub );
                }
                
                return $pformats;
            }
            
            return false;
        }
        
        function setFormatPub( array $pub )
        {
            if( is_array($pub) && count( $pub ) == 1 )
            {
                return $this->__getFormat( $pub );
            }
            
            return false;
        }
        
        private function __getFormat( array $pub )
        {
            //Se da forma al objeto a fin de obtener un arreglo de datos que sirva para parsearse a la vista.
            if( ! count( $pub ) > 0) 
            {
                return false;
            }
            
            $parent = unserialize( $pub[ 'parents' ] );
            
            array_push( $parent[0], $this->__autocompleteJoins( array( 'members' ), $pub[ 'field_author_id' ] ) );
            
            $add_joins = array();
            $index = 0;
            
            foreach( $parent[0] as $inner )
            {
                 //Solo al elemento 1 se le permite no tener el campo de fromId ya que ya se conoce y es parent_id
                 if( ! $index == 0 )
                 {
                     if( ! $this->__exist_list_keys( $inner, array( 'tableName', 'fromId', 'toId' ) ) )
                     {
                         return false;
                     }
                 }else {
                     if( ! $this->__exist_list_keys( $inner, array( 'tableName', 'toId' ) ) )
                     {
                         return false;
                     }
                     //Generamos un LastTableName si no es el primer index
                     $inner[ 'lastTableName' ] = $parent[0][ ( $index - 1 ) ][ 'tableName' ];
                 }
                 
                 
                 if( ! ( $add_joins[] = $this->__getJoin( $inner, array( 'fieldsCollection', 'tableName', 'toId', 'fromId' ) ) ) )
                 {
                     return false;
                 }

                 $pre_formatted = $this->DB->buildAndFetch( array
                                           (
                                               'select'     => 'pf.status_date',
                                               'from'       => array( 'publish_format_data' => 'pf' ),
                                               'where'      => 'pf.id = ' . $pub[ 'id' ],
                                               'add_join'   => $add_joins
                                           )
                                         );
                $index++;
                return $pre_formatted;
            }
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
        
        private function __getJoin( array $inner, array $fields )
        {
            //Una vez comprobado que el join contenga los datos necesarios vamos a comenzar el armado.
            $add_join = array();
            
            if( array_key_exists( 'fieldsCollection', $inner ) )
            {
                $add_join[ 'select' ] = sprintf( '%s.%s', $inner[ 'tableName' ], join( $inner[ 'tableName' ] . ', ', $inner[ 'fieldsCollection' ] ) );
            }
            
            $add_join[ 'from' ]   = array( $inner[ 'tableName' ] => $inner[ 'tableName' ] );
            
            if( array_key_exists( 'fromId', $inner ) && $inner[ 'fromId' ] != 'parent_id' )
            {
                $add_join[ 'where' ]  = "{$inner[ 'lastTableName' ]}.{$inner[ 'fromId' ]} = {$inner[ 'toId' ]}";
            }else {
                $add_join[ 'where' ]  = "pf.parent_id = {$inner[ 'tableName' ]}.{$inner[ 'toId' ]}";
            }
            
            $add_join[ 'type' ] = 'inner';
            
            return $add_join;
        }
        
        private function __autocompleteJoins( array $types, $fromId ) 
        {
            $add_joins = array();
            foreach( $types as $type )
            {
                switch( $type )
                {
                    case 'members':
                       $add_joins[] = array( 'tableName' => 'members', 'fromId' => $fromId, 'toId' => 'member_id', 'fieldsCollection' => array( 'member_id' ) );
                    break;
                }
            }
            
            
            return $add_joins;
        }
}