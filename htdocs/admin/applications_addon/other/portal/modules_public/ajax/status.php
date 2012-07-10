<?php
class public_portal_ajax_status extends ipsAjaxCommand {
	protected $cache;
	protected $registry;
	
	function doExecute(ipsRegistry $ipsRegistry) {
		$this->registry = $ipsRegistry;
		$this->cache = ipsRegistry::cache();
		
        //Load functions cache
        if ( ! $this->registry->isClassLoaded( 'portalCache' ) ) {
            $classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'portal' ) . '/sources/PortalCache.php', 'PortalCache' );
            $this->registry->setClass( 'portalCache', new $classToLoad( $this->registry ) );
        }
		
		switch(strtolower($this->request['do'])) {
			default:
			case 'getfriends':
				$this->__getfriends();
			break;
		}
	}
	
	private function __getfriends() {
	    if( ! (int)$this->memberData['member_id'] )
   	           $this->returnJsonError( $this->lang->words['post_status_guest'] );
	   
	    $_friends = $this->registry->portalCache->getfriends();
	       
	    $this->returnJsonArray(array('status' => 'success', 'friends' => $_friends));
	}
	
}