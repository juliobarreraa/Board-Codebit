<?php
/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.3
 * Allow user to change their status
 * Last Updated: $Date: 2012-05-10 16:10:13 -0400 (Thu, 10 May 2012) $
 * </pre>
 *
 * @author 		$Author: bfarber $
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @subpackage	Members
 * @link		http://www.invisionpower.com
 * @since		Tuesday 1st March 2005 (11:52)
 * @version		$Revision: 10721 $
 *
 */

IPSLib::loadLibrary( IPSLib::getAppDir( 'members' ) . '/modules_public/ajax/status.php', 'public_members_ajax_status' );

class public_portal_ajax_status extends public_members_ajax_status {

	protected $cache;
	protected $registry;
	
	function doExecute(ipsRegistry $ipsRegistry) {
        //Load functions cache
        if ( ! $this->registry->isClassLoaded( 'portalCache' ) ) {
            $classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'portal' ) . '/sources/PortalCache.php', 'PortalCache' );
            $this->registry->setClass( 'portalCache', new $classToLoad( $this->registry ) );
        }
        
		
		/* Load status class */
		if ( ! $this->registry->isClassLoaded( 'memberStatus' ) )
		{
			$classToLoad = IPSLib::loadLibrary( IPS_ROOT_PATH . 'sources/classes/member/status.php', 'memberStatus' );
			$this->registry->setClass( 'memberStatus', new $classToLoad( ipsRegistry::instance() ) );
		}
		
		if ( ! $this->registry->isClassLoaded( 'opengraph' ) )
		{
			$classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'portal' ) . '/sources/classes/opengraph/opengraph.php', 'opengraph' );
			$this->registry->setClass( 'opengraph', new $classToLoad( ipsRegistry::instance() ) );
		}
		
		switch($this->request['do']) 
		{
			case 'getfriends':
				$this->__getfriends();
			break;
			case 'urlParser':
			    $this->__getURIParser();
            break;
		}
		
		parent::doExecute( $ipsRegistry );
	}
	
	private function __getfriends() {
	    if( ! (int)$this->memberData['member_id'] )
   	           $this->returnJsonError( $this->lang->words['post_status_guest'] );
	   
	    $_friends = $this->registry->portalCache->getfriends();
	       
	    $this->returnJsonArray(array('status' => 'success', 'friends' => $_friends));
	}
	
	/**
	 * Add a new statussesses
	 *
	 * @return	@e void
	 */
	protected function _new()
	{
	    $this->registry->getClass('memberStatus')->su_Tags = json_decode(str_replace(array('&quot;'), '"', $this->request['su_Tags']));
	    
	    $this->request[ 'attachments' ] = str_replace('&amp;', '&', urldecode($this->request[ 'attachments' ]) );
	    parse_str(urldecode($this->request[ 'attachments' ]), $this->request['attachments']);
	    
	    if( array_key_exists( 'attachments', $this->request ) )
	    {
    	    $this->registry->getClass('memberStatus')->su_attachment = $this->request['attachments']; //Almacenados el arreglo con los datos de parseo del link dado de alta.
	    }
	    
		IPSDebug::fireBug( 'info', array( 'Status content: ' . $_POST['content'] ) );
		IPSDebug::fireBug( 'info', array( 'Cleaned status: ' . trim( $this->convertAndMakeSafe( $_POST['content'] ) ) ) );
		
		/* INIT */
		$smallSpace  = intval( $this->request['smallSpace'] );
		$su_Twitter  = intval( $this->request['su_Twitter'] );
		$su_Facebook = intval( $this->request['su_Facebook'] );
		$skin_group  = $this->getSkinGroup();
		$forMemberId = intval( $this->request['forMemberId'] );
		
		/* Got content? */
		if( !trim( $this->convertAndMakeSafe( str_replace( array( '&nbsp;', '&#160;' ), '', $_POST['content'] ) ) ) )
		{
			$this->returnJsonError( $this->lang->words['no_status_sent'] );
		}
		
		/* Set Author */
		$this->registry->getClass('memberStatus')->setAuthor( $this->memberData );
		
		/* Set Content */
		$this->registry->getClass('memberStatus')->setContent( trim( $this->convertAndMakeSafe( $_POST['content'] ) ) );
		
		/* Can we create? */
		if ( ! $this->registry->getClass('memberStatus')->canCreate() )
 		{
			$this->returnJsonError( $this->lang->words['status_off'] );
		}
			
		/* Update or comment? */
		if ( $forMemberId && $forMemberId != $this->memberData['member_id'] )
		{
			$owner = IPSMember::load( $forMemberId );
			
	    	if ( ! $owner['pp_setting_count_comments'] )
	    	{
	    		$this->returnJsonError( $this->lang->words['status_off'] );
	    	}
	
			/* Set owner */
			$this->registry->getClass('memberStatus')->setStatusOwner( $owner );
		}
		else
		{
			/* Set post outs */
			$this->registry->getClass('memberStatus')->setExternalUpdates( array( 'twitter' => $su_Twitter, 'facebook' => $su_Facebook ) );
			$this->registry->getClass('memberStatus')->setCreator( 'portal' );
		}

		/* Update */
		$newStatus = $this->registry->getClass('memberStatus')->create();
		
		/* Now grab the reply and return it */
		$new = $this->registry->getClass('output')->getTemplate( $skin_group )->statusUpdates( $this->registry->getClass('memberStatus')->fetch( $this->memberData['member_id'], array( 'relatedTo' => $forMemberId, 'isApproved' => true, 'sort_dir' => 'desc', 'limit' => 1 ) ), $smallSpace );
		
		$this->returnJsonArray( array( 'status' => 'success', 'html' => $this->cleanOutput( $new ) ) );
	}
	
	protected function __getURIParser()
	{
	    //TODO: Verificar con expresión regular que efectivamente corresponde a una url
	    $getInfoUrl = $this->request[ 'getInfoUrl' ];
	    
		$propertys = array();
		if( ( $graph = $this->registry->opengraph->fetch($getInfoUrl) ) )
		{
            foreach ($graph as $key => $value) {
                $propertys[ $key ] = IPSText::utf8ToEntities($value);
            }
		}
        
        
        $this->output = $this->registry->getClass('output')->getTemplate( 'portal' )->attachLink( $propertys );
        
		$this->returnJsonArray( array( 'status' => 'success', 'html' => $this->cleanOutput( $this->output ) ) ); 
	}
	
}