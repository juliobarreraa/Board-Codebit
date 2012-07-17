<?php


$INSERT[] = "INSERT INTO publish_general_configuration (field_date, field_author_id, parents, enabled) VALUES
            ('status_date', 'status_author_id', 'a:1:{i:0;a:3:{s:9:\"tableName\";s:21:\"member_status_updates\";s:4:\"toId\";s:9:\"status_id\";s:16:\"fieldsCollection\";a:1:{i:0;s:14:\"status_content\";}}}a:1:{i:0;a:3:{s:9:\"tableName\";s:21:\"member_status_updates\";s:4:\"toId\";s:9:\"status_id\";s:16:\"fieldsCollection\";a:1:{i:0;s:14:\"status_content\";}}}', 1);";
$INSERT[] = "INSERT INTO publish_general_configuration (field_date, field_author_id, parents, enabled) VALUES
            ('idate', 'member_id', 'a:2:{i:0;a:3:{s:9:\"tableName\";s:14:\"gallery_images\";s:4:\"toId\";s:2:\"id\";s:16:\"fieldsCollection\";a:7:{i:0;s:7:\"caption\";i:1;s:11:\"description\";i:2;s:9:\"directory\";i:3;s:16:\"masked_file_name\";i:4;s:16:\"medium_file_name\";i:5;s:9:\"file_type\";i:6;s:11:\"caption_seo\";}}i:1;a:4:{s:9:\"tableName\";s:19:\"gallery_albums_main\";s:6:\"fromId\";s:12:\"img_album_id\";s:4:\"toId\";s:8:\"album_id\";s:16:\"fieldsCollection\";a:3:{i:0;s:10:\"album_name\";i:1;s:14:\"album_name_seo\";i:2;s:17:\"album_description\";}}}', 1);";


class portal_blocks
{
	protected $registry;
	protected $DB;
	
	public function __construct()
	{
		$this->registry	= ipsRegistry::instance();
		$this->DB		= $this->registry->DB();
        
        # Load block file and import.		
		$block_file = file_get_contents( IPS_ROOT_PATH . 'applications_addon/other/portal/xml/custom_blocks.xml' );
		
		require_once( IPS_KERNEL_PATH.'classXML.php' );

		$xml = new classXML( IPS_DOC_CHAR_SET );
		$xml->loadXML( $block_file );
		
		foreach( $xml->fetchElements('block') as $block )
		{
			$_block	= $xml->fetchElementsFromRecord( $block );

			if( $_block['title'] )
			{
				if( !$_block['name'] )
                {
                    $_block['name'] = IPSText::makeSeoTitle( $_block['title'] );    
                }
				
				$this->DB->insert( "portal_blocks", $_block );
			}
		}

	}    
}

$portalBlockInstall = new portal_blocks();
