<?php
/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.4
 * Setup CLI Script Overrider
 * Last Updated: $LastChangedDate: 2012-06-12 10:14:49 -0400 (Tue, 12 Jun 2012) $
 * </pre>
 *
 * @author 		$Author: bfarber $
 * @copyright	© 2011 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @link		http://www.invisionpower.com
 * @version		$Rev: 10914 $
 *
 */

class CLIOutput extends output
{
	public $currentStep = 1;
	
	private $finished = FALSE;

	public function __construct( $steps, $controller )
	{
		$this->steps = $steps;
		$this->controller = $controller;
		
		parent::__construct( ipsRegistry::instance(), FALSE );
	}
	
	public function setNextAction( $next )
	{
		$next = str_replace( '&amp;', '&', $next );
		if ( substr( $next, 0, 8 ) == 'install&' )
		{
			$next = substr( $next, 8 );
		}

		$this->next = $next;
	}
	
	public function sendOutput( $saveData=TRUE )
	{
		if ( $this->next == 'done' )
		{
			echo "OK";
			exit;
		}
		else
		{
			parse_str( $this->next, $vars );
			$_REQUEST = $vars;
			$this->controller->request = $vars;
			$this->controller->doExecute( ipsRegistry::instance() );
		}
	}

	public function template()
	{
		return new CLIOutputTemplate();
	}
}

class CLIOutputTemplate
{
	public function __call( $name, $arguments )
	{
		return '';
	}
}