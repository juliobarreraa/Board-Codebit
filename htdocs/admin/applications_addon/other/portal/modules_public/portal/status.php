<?php
/*
+--------------------------------------------------------------------------
|   Status
|   =============================================
|   by Julio Barrera A.
|   Copyright 2012-2014
|   http://www.codebit.org
+--------------------------------------------------------------------------
|   Based on IP.Board Portal by Invision Power Services
|   Website - http://www.invisionpower.com/
+--------------------------------------------------------------------------
*/

if ( ! defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

IPSLib::loadLibrary( IPSLib::getAppDir( 'members' ) . '/modules_public/profile/status.php', 'public_members_profile_status' );

class public_portal_portal_status extends public_members_profile_status
{
    function doExecute( ipsRegistry $ipsregistry ) {
		
		/* Load status class */
		if ( ! $this->registry->isClassLoaded( 'memberStatus' ) )
		{
			$classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir( 'portal' ) . '/sources/classes/member/status.php', 'portalMemberStatus' );
			$this->registry->setClass( 'portalMemberStatus', new $classToLoad( ipsRegistry::instance() ) );
		}
		
		/* WHAT R WE DOING? */
		switch( $this->request['do'] )
		{
			case 'new':
				$this->_new();
			break;
		}
    }
    
	/**
	* Add a new statuses
	*
	*/
	protected function _new()
	{
		$id   = intval( $this->memberData['member_id'] );
		$su_Twitter  = intval( $this->request['su_Twitter'] );
		$su_Facebook = intval( $this->request['su_Facebook'] );
		$forMemberId = intval( $this->request['forMemberId'] );
		
		/* Set Author */
		$this->registry->getClass('portalMemberStatus')->setAuthor( $this->memberData );
		
		/* Set Content */
		$this->registry->getClass('portalMemberStatus')->setContent( trim( $this->request['content'] ) );
		
		/* Can we reply? */
		if ( ! $this->registry->getClass('portalMemberStatus')->canCreate() )
 		{
			$this->registry->output->showError( 'status_off', 10268, null, null, 403 );
		}
		
		/* Update or comment? */
		if ( $forMemberId && $forMemberId != $this->memberData['member_id'] )
		{
			$owner = IPSMember::load( $forMemberId );
			
	    	if ( ! $owner['pp_setting_count_comments'] )
	    	{
	    		$this->registry->output->showError( 'status_off', 10268, null, null, 403 );
	    	}
	
			/* Set owner */
			$this->registry->getClass('portalMemberStatus')->setStatusOwner( $owner );
		}
		else
		{
			/* Set post outs */
			$this->registry->getClass('portalMemberStatus')->setExternalUpdates( array( 'twitter' => $su_Twitter, 'facebook' => $su_Facebook ) );
		}

		/* Update */
		$this->registry->getClass('portalMemberStatus')->create();
		
		/* Got a return URL? */
		if ( $this->request['rurl'] )
		{
			$this->registry->output->redirectScreen( $this->lang->words['status_was_changed'], $this->settings['base_url'] . base64_decode( $this->request['rurl'] ) );
		}
		else
		{
			$this->registry->output->redirectScreen( $this->lang->words['status_was_changed'], $this->settings['base_url'] . 'showuser=' . $id, $this->memberData['members_seo_name'] );
		}
	}
}