<?php
/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.4
 * Board Rules
 * Last Updated: $Date: 2012-06-12 10:14:49 -0400 (Tue, 12 Jun 2012) $
 * </pre>
 *
 * @author 		$Author $
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @subpackage	Forums
 * @link		http://www.invisionpower.com
 * @since		20th February 2002
 * @version		$Rev: 10914 $
 */
if ( ! defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

class public_forums_extras_boardrules extends ipsCommand
{
	/**
	 * Class entry point
	 *
	 * @param	object		Registry reference
	 * @return	@e void		[Outputs to screen/redirects]
	 */
	public function doExecute( ipsRegistry $registry )
	{
		
		/* Load editor stuff */
		$classToLoad = IPSLib::loadLibrary( IPS_ROOT_PATH . 'sources/classes/editor/composite.php', 'classes_editor_composite' );
		$editor = new $classToLoad();
		
		/* Get board rule (not cached) */
		$row = $this->DB->buildAndFetch( array( 'select' => '*', 'from' => 'core_sys_conf_settings', 'where' => "conf_key='gl_guidelines'" ) );

		$editor->setAllowHtml( true );
			
 		$row['conf_value'] = $editor->switchContent( $row['conf_value'], 0 );

		$this->registry->output->addNavigation( $this->settings['gl_title'], '' );
		$this->registry->output->setTitle( $this->settings['gl_title'] . ' - ' . ipsRegistry::$settings['board_name'] );
		$this->registry->output->addContent( $this->registry->output->getTemplate('emails')->boardRules( $this->settings['gl_title'], IPSText::getTextClass( 'bbcode' )->preDisplayParse( $row['conf_value'] ) ) );
		$this->registry->output->sendOutput();
	}
}