<?php
/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.4
 * Installer: EULA file
 * Last Updated: $LastChangedDate: 2012-06-12 10:14:49 -0400 (Tue, 12 Jun 2012) $
 * </pre>
 *
 * @author 		$Author: bfarber $
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @link		http://www.invisionpower.com
 * @version		$Rev: 10914 $
 *
 */


class install_done extends ipsCommand
{	
	/**
	 * Execute selected method
	 *
	 * @access	public
	 * @param	object		Registry object
	 * @return	@e void
	 */
	public function doExecute( ipsRegistry $registry ) 
	{
		$installLocked = FALSE;
		
		/* Lock the page */
		if ( @file_put_contents( DOC_IPS_ROOT_PATH . 'cache/installer_lock.php', 'Just out of interest, what did you expect to see here?' ) )
		{
			$installLocked = TRUE;
		}
		
		/* Clean conf global */
		IPSInstall::cleanConfGlobal();
		
		/* Simply return the EULA page */
		$this->registry->output->setTitle( "Complete!" );
		$this->registry->output->setHideButton( TRUE );
		$this->registry->output->addContent( $this->registry->output->template()->page_installComplete( $installLocked ) );
		$this->registry->output->sendOutput( FALSE );
	}
}