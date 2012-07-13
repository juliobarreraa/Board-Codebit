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
                                    'order'         => 'id DESC',
                                    'add_join'      => array
                                                       (
                                                            array
                                                            (
                                                                    'select'        => 'pg.id as st_id, pg.field_date, pg.field_author_id, pg.parents',
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
        
        /**
         *
         * Formatea cada uno de los elementos del arreglo pubs obteniendo una consulta que arma los elementos del arreglo que se retornaran para su parseo en la vista.
         * @param $pubs arreglo de publicaciones obtenidas desde la base de datos.
         * @param $formatters, arreglo que permite modificar la estructura con la que se devuelve el objeto, en base a parámetros de consulta que permitiran añadir otras tablas.
         * 
        **/
        function setFormatPubs( array $pubs, array $formatters = array() )
        {
            if( count( $pubs ) > 0 )
            {
                foreach( $pubs as $pub )
                {
                    $pformats[] = $this->__getFormat( $pub, $formatters );
                }
                
                return $pformats;
            }
            
            return false;
        }
        
        /**
         *
         * Formatea el arreglo pub obteniendo una consulta que arma los elementos del arreglo que se retornaran para su parseo en la vista.
         * @param $pub publicación obtenidas desde la base de datos.
         * @param $formatters, arreglo que permite modificar la estructura con la que se devuelve el objeto, en base a parámetros de consulta que permitiran añadir otras tablas.
         * 
        **/
        function setFormatPub( array $pub, array $formatters = array() )
        {
            if( is_array($pub) && count( $pub ) == 1 )
            {
                return $this->__getFormat( $pub, $formatters );
            }
            
            return false;
        }
        
        /**
         * @description Permite devolver un arreglo en base a una consulta armada, con los parents establecidos desde la base de datos en un arreglo serializado
         * @param $pub, publicación que será formateada
         * @param $formatters, arreglo que permite modificar la estructura con la que se devuelve el objeto, en base a parámetros de consulta que permitiran añadir otras tablas.
         *
        **/
        private function __getFormat( array $pub, array $formatters = array() )
        {
            //Se da forma al objeto a fin de obtener un arreglo de datos que sirva para parsearse a la vista.
            if( ! count( $pub ) > 0) 
            {
                return false;
            }
            
            $parent = unserialize( $pub[ 'parents' ] ); //Arreglo con el conjunto de tablas padre para armar la consulta
            
            //Elementos que necesitan agregarse a la consulta de la base de datos.
            array_push( $parent, $this->__autocompleteJoins( array( 'members' ), $pub[ 'field_author_id' ] ) ); 
            array_push( $parent, $this->__autocompleteJoins( array( 'profile_portal' ) ) );
            
            $add_joins = array(); //Variable que contiene un arreglo de los elementos que haran la construcción del build para pasar a SQL
            
            $pre_formatted = array(); //Elemento a retornar con el formato para parse en la vista
            
            foreach( $parent as $index => $inner )
            {
                 //Solo al elemento 1 se le permite no tener el campo de fromId ya que ya se conoce y es parent_id
                 if( ! $index == 0 )
                 {
                     if( ! $this->__exist_list_keys( $inner, array( 'tableName', 'fromId', 'toId' ) ) ) //Comprobamos que existan las claves necesarias para poder hacer el join se omite fromId ya que por defecto es parent_id
                     {
                         return false;
                     }
                 }else {
                     if( ! $this->__exist_list_keys( $inner, array( 'tableName', 'toId' ) ) ) //Comprobamos que existan las claves necesarias para poder hacer el join
                     {
                         return false;
                     }
                 }
                 
                 //Generamos un LastTableName si no es el primer index para poder consultar la tabla anterior
                 if( array_key_exists( 'isRoot', $inner ) )
                 {
                        $inner[ 'lastTableName' ] = $parent[ 0 ][ 'tableName' ];
                 }else {
                     $inner[ 'lastTableName' ] = $parent[ ( $index - 1 ) ][ 'tableName' ];
                 }
                 
                 
                 if( ! ( $add_joins[] = $this->__getJoin( $inner ) ) ) //Comprobamos que el arreglo join se haya construido correctamente
                 {
                     return false;
                 }

            }
            
            //Ejecutamos el query, validaciones necesarias para evitar una exception
            $pre_formatted = $this->DB->buildAndFetch( array
                                                         (
                                                             'select'     => 'pf.status_date',
                                                             'from'       => array( 'publish_format_data' => 'pf' ),
                                                             'where'      => 'pf.id = ' . $pub[ 'id' ],
                                                             'add_join'   => $add_joins
                                                         )
                                    );
                                    
            //Si existe el formatter 'avatars', devolvemos el arreglo armado con los datos de avatar
            if( array_key_exists( 'avatars', $formatters ) )
            {
                $pre_formatted = $this->__setAvatar( $pre_formatted );
            }
            
            //Set template
            switch( intval( $pub['st_id'] ) )
            {
                case 1:
                     $pre_formatted[ 'template' ] = 'statusUpdates';
                     break;
                case 2:
                     $pre_formatted[ 'template' ] = 'showPhoto';
                     break;
                default:
                     $pre_formatted[ 'template' ] = 'statusUpdates';
                     break;
                     
            }
            
            return $pre_formatted;
        }
        
        /**
         * @description Recorre cada uno de los elementos de $list dentro de $data para comprobar que cada una de las claves de $list estan dentro de $data
         * @param $data, Arreglo para parsear el join
         * @param $list, Listado de claves que debe tener $data para poder hacer el join correctamente
         *
        **/
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
        
        /**
         * @description Devuelve un arreglo con los datos a seleccionar, la tabla desde la que se hará el select y la condicionante.
         * @param $inner, arreglo que será parseado para convertirlo en un elemento join
         *
        **/
        private function __getJoin( array $inner )
        {
            //Una vez comprobado que el join contenga los datos necesarios vamos a comenzar el armado.
            $add_join = array();
            
            //Si existe fieldsCollection entonces lo añadimos a select
            if( array_key_exists( 'fieldsCollection', $inner ) )
            {
                $add_join[ 'select' ] = sprintf( '%s.%s ', $inner[ 'tableName' ], join( ', ', $inner[ 'fieldsCollection' ] ) );
            }
            
            $add_join[ 'from' ]   = array( $inner[ 'tableName' ] => $inner[ 'tableName' ] ); //Obligatorio la tabla de la cual se realizará la consulta
            
            if( array_key_exists( 'fromId', $inner ) && $inner[ 'fromId' ] != 'parent_id' )
            {
                $add_join[ 'where' ]  = "{$inner[ 'lastTableName' ]}.{$inner[ 'fromId' ]} = {$inner[ 'tableName' ]}.{$inner[ 'toId' ]}"; //Si existe fromId se genera el where entre la tabla actual y la anterior
            }else {
                $add_join[ 'where' ]  = "pf.parent_id = {$inner[ 'tableName' ]}.{$inner[ 'toId' ]}"; //Si no existe fromId se general el where entre la tabla actual y la tabla origen root
            }
            
            //TODO: Posiblemente se necesite que también sea left
            $add_join[ 'type' ] = 'inner'; //Tipo inner
            
            return $add_join;
        }
        
        /**
         * @description Permite completar los joins con información extra
         * @param $types, arreglo que permite saber las tablas que serán agregadas
         * @param $fromId elemento que sirve para mostrar el campo que sirve como llave primaria de la clave o de enlace con otra tabla ON tabla.campo = tabla1.campo1
         *
        **/
        //TODO: Modificar estructura para trabajar con multiples joins sin tener que llamar 1 a 1
        private function __autocompleteJoins( array $types, $fromId = '' ) 
        {
            $add_joins = array();
            foreach( $types as $type )
            {
                switch( $type )
                {
                    case 'members':
                       $add_joins = array( 'tableName' => 'members', 'fromId' => $fromId, 'toId' => 'member_id', 'fieldsCollection' => array( 'member_id', 'members_seo_name', 'members_display_name' ), 'isRoot' => true );
                    break;
                    case 'profile_portal':
                       $add_joins = array( 'tableName' => 'profile_portal', 'fromId' => 'member_id', 'toId' => 'pp_member_id', 'fieldsCollection' => array( 'pp_thumb_photo' ) );
                }
            }
            
            
            return $add_joins;
        }
        
        /**
         * @description Permite completar información extra para el arreglo devuelto
         * @param $pub, arreglo que contiene la información preparseada
         * @param $member_id ID del usuario que se devolverá la información de la foto de perfil en caso de no pasarse se tomará de $pub
         *
        **/
        private function __setAvatar( $pub, $member_id = 0 )
        {
             if( ! $member_id )
                  $member_id = (int)$pub[ 'member_id' ];
                  
             return ( $pub + IPSMember::buildProfilePhoto( $member_id ) );
        }
}