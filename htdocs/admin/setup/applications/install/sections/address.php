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


class install_address extends ipsCommand
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
		/* INIT */
		$error = false;
		
		/* Check input? */
		if ( $this->request['do'] == 'check' )
		{
			/* Check Directory */
			if ( ! ( is_dir( $this->request['install_dir'] ) ) )
			{
				$error = true;
				$this->registry->output->addWarning( 'The specified directory does not exist' );
			}
			
			/* Check URL */
			if ( ! $this->request['install_dir'] )
			{
				$error = true;
				$this->registry->output->addWarning( 'You did not specify a URL' );
			}

			if( ! $error )
			{
				/* Save Form Data */
				IPSSetUp::setSavedData('install_dir', preg_replace( "#(//)$#", "", str_replace( '\\', '/', $this->request['install_dir'] ) . '/' ) );
				IPSSetUp::setSavedData('install_url', preg_replace( "#(//)$#", "", str_replace( '\\', '/', $this->request['install_url'] ) . '/' ) );
				
				/* Next Action */
				$this->registry->autoLoadNextAction( 'license' );
			}
		}
		
		/* Guess at directory */
		
		if( !defined('CP_DIRECTORY') )
		{
			define( 'CP_DIRECTORY', 'admin' );
		}

		$dir = str_replace( CP_DIRECTORY . '/install'  , '' , getcwd() );
		$dir = str_replace( CP_DIRECTORY . '\install'  , '' , $dir ); // Windows
		$dir = str_replace( '\\'       , '/', $dir );

		/* Guess at URL */
		$url = str_replace( "/" . CP_DIRECTORY . "/installer/index.php"	, "", $_SERVER['HTTP_REFERER'] );
		$url = str_replace( "/" . CP_DIRECTORY . "/installer/"			, "", $url);
		$url = str_replace( "/" . CP_DIRECTORY . "/installer"			, "", $url);
		$url = str_replace( "/" . CP_DIRECTORY . "/install/index.php"	, "", $_SERVER['HTTP_REFERER'] );
		$url = str_replace( "/" . CP_DIRECTORY . "/install/"			, "", $url);
		$url = str_replace( "/" . CP_DIRECTORY . "/install"				, "", $url);
		$url = str_replace( "/" . CP_DIRECTORY							, "", $url);
		$url = str_replace( "index.php"									, "", $url);
		$url = preg_replace( "!\?(.+?)*!"								, "", $url );	
		$url = "{$url}/";
		
		/* Page Output */
		$this->registry->output->setTitle( "Paths and URLs" );
		$this->registry->output->setNextAction( "address&do=check" );
		$this->registry->output->addContent( $this->registry->output->template()->page_address( $dir, $url ) );
		$this->registry->output->sendOutput();
	}
	
}