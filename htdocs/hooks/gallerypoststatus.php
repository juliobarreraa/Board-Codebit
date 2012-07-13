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
 
class gallerypoststatus extends gallery_upload {
    
    function __construct( ipsRegistry $registry )
    {
        parent::__construct( $registry );
        
        if ( ! $this->registry->isClassLoaded( 'publish' ) )
        {
            $classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'portal' ) . '/sources/classes/publish/publish.php', 'publish' );
            $this->registry->setClass( 'publish', new $classToLoad( $this->registry ) );
        }
    }
    
	/**
	 * Finish: Publishes picture to selected album, sends out notifications, has a glass of very cheap wine and calls a cab home.
	 *
	 * @access	public
	 * @param	string		Session key
	 * @param	string
	 *
	 */
	public function finish( $sessionKey )
	{
		/* Init */
		$albums = array();
		
		/* Fetch the data */
		$this->DB->build( array( 'select'   => 'i.*',
								 'from'     => array( 'gallery_images_uploads' => 'i' ),
								 'where'    => 'i.upload_session=\'' . $this->DB->addSlashes( $sessionKey ) .'\'',
								 'order'    => 'i.upload_date ASC',
								 'limit'    => array( 0, 500 ),
								 'add_join' => array( array( 'select' => 'a.*',
								 							 'from'   => array( 'gallery_albums_main' => 'a' ),
								 							 'where'  => 'i.upload_album_id=a.album_id',
								 							 'type'   => 'left' ) ) ) );
								 
		$o = $this->DB->execute();
		
		while( $row = $this->DB->fetch( $o ) )
		{
			/* remap */
			$_i = $this->_remapAsImage( $row );
			
			$thisIsCoverImage = false;
			
			unset( $_i['id'] );
			
			/* Insert */
			if ( $_i['img_album_id'] )
			{
				/* Set approval flag */
				if ( isset( $row['album_g_approve_img'] ) )
				{
					if ( $row['album_g_approve_img'] AND ! $this->registry->gallery->helper('albums')->canModerate( $row ) )
					{
						$_i['approved'] = 0;
					}
				}
				
				/* Als bum again */
				if ( $_i['img_album_id'] AND IPSLib::isSerialized( $row['upload_data'] ) )
				{
					$_data            = unserialize( $row['upload_data'] );
					$thisIsCoverImage = ( isset( $_data['_isCover'] ) AND $_data['_isCover'] ) ? 1 : 0;
					
					/* Size data */
					if ( isset( $_data['sizes'] ) )
					{
						$_i['image_data'] = serialize( array( 'sizes' => $_data['sizes'] ) );
					}
				}
				
				$_i['image_notes'] = '';
				
				/* Data Hook Location */
				IPSLib::doDataHooks( $_i, 'galleryPreAddImage' );
				
				/* Insert into the database */
				$this->DB->insert( 'gallery_images', $_i );
				
				$newId = $this->DB->getInsertId();
				
				
                /** 
                 * Data contiene los datos que insertaremos en la base de datos de bitácora
                 * Si status_member_id es identico a status_author_id es una publicación realizada al público/amigos en otro caso es realizada a status_member_id desde status_author_id
                 * El ID de la tabla de configuración estará dado por la Clave STATUS_UPDATE
                **/
                
                $pformat_data = array
                                (
                                      'configuration_id'       =>    GALLERY_UPDATES,
                                      'parent_id'              =>    $newId,
                                );
                                
                //Configuramos los datos e insertamos
                $this->registry->publish->setDataPublish( $pformat_data )->do_insert();
				
				/* Do we have a taggy tag? */
				if ( ! empty( $_POST['ipsTags_' . $row['upload_key']] ) )
				{
					$this->registry->galleryTags->add( $_POST['ipsTags_' . $row['upload_key']], array(  'meta_id'		 => $newId,
																					      				'meta_parent_id' => $_i['img_album_id'],
																					      				'member_id'	     => $this->memberData['member_id'],
																					      				'meta_visible'   => $_i['approved'] ) );
				}
				
				/* Mark as read for uploader */		
				$this->registry->classItemMarking->markRead( array( 'albumID' => $_i['img_album_id'], 'itemID' => $newId ), 'gallery' );
				
				/* Geo location */
				$this->registry->gallery->helper('image')->setReverseGeoData( $newId );
				
				/* Data Hook Location */
				$_i['id'] = $newId;
				IPSLib::doDataHooks( $_i, 'galleryPostAddImage' );
				
				/* Make sure the id is set */
				if ( ! isset( $albums[ $_i['img_album_id'] ] ) )
				{
					$albums[ $_i['img_album_id'] ] = array();
				}
				
				if ( $thisIsCoverImage )
				{
					$albums[ $_i['img_album_id'] ] = array( 'album_cover_img_id' => intval( $newId ) );
				}
			}
		}
	
		/* Update albums? */
		if ( count( $albums ) )
		{
			/* Save and sync */
			$this->registry->gallery->helper('albums')->save( $albums );
			
			/* Fix image permissions */
			$this->registry->gallery->helper('image')->updatePermissionFromParent( $albums );
			
			/* Send notifications */
			$this->registry->gallery->helper('notification')->sendAlbumNotifications( array_keys( $albums ) );
		}
		
		/* Delete this session */
		$this->DB->delete( 'gallery_images_uploads', 'upload_session=\'' . $this->DB->addSlashes( $sessionKey ) .'\'' );
		
		/* Rebuild stats */
		$this->registry->gallery->rebuildStatsCache();
		
		return array_keys( $albums );
	}
	
}