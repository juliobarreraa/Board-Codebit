<?php

/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.3
 * Admin logs
 * Last Updated: $LastChangedDate: 2012-05-10 16:10:13 -0400 (Thu, 10 May 2012) $
 * </pre>
 *
 * @author 		$Author: bfarber $
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @subpackage	Core
 * @link		http://www.invisionpower.com
 * @since		27th January 2004
 * @version		$Rev: 10721 $
 */

if ( ! defined( 'IN_ACP' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded 'admin.php'.";
	exit();
}

class admin_core_logs_adminlogs extends ipsCommand 
{
	/**
	 * Skin object
	 *
	 * @var		object			Skin templates
	 */
	protected $html;
	
	/**
	 * Main class entry point
	 *
	 * @param	object		ipsRegistry reference
	 * @return	@e void		[Outputs to screen]
	 */
	public function doExecute( ipsRegistry $registry )
	{
		//-----------------------------------------
		// Load skin
		//-----------------------------------------
		
		$this->html			= $this->registry->output->loadTemplate('cp_skin_adminlogs');
		
		//-----------------------------------------
		// Set up stuff
		//-----------------------------------------
		
		$this->form_code	= $this->html->form_code	= 'module=logs&amp;section=adminlogs';
		$this->form_code_js	= $this->html->form_code_js	= 'module=logs&section=adminlogs';
		
		//-----------------------------------------
		// Load lang
		//-----------------------------------------
				
		$this->registry->getClass('class_localization')->loadLanguageFile( array( 'admin_logs' ) );
		
		///----------------------------------------
		// What to do...
		//-----------------------------------------
		
		switch( $this->request['do'] )
		{
			case 'view':
				$this->registry->output->core_nav[]		= array( $this->settings['base_url'] . 'module=logs&section=adminlogs', $this->lang->words['alog_adminlogs'] );
				$this->registry->getClass('class_permissions')->checkPermissionAutoMsg( 'adminlogs_view' );
				$this->_view();
			break;
				
			case 'remove':
				$this->registry->output->core_nav[]		= array( $this->settings['base_url'] . 'module=logs&section=adminlogs', $this->lang->words['alog_adminlogs'] );
				$this->registry->getClass('class_permissions')->checkPermissionAutoMsg( 'adminlogs_delete' );
				$this->_remove();
			break;

			default:
				$this->registry->output->core_nav[]		= array( $this->settings['base_url'] . 'module=logs&section=adminlogs', $this->lang->words['alog_adminlogs'] );
				$this->registry->getClass('class_permissions')->checkPermissionAutoMsg( 'adminlogs_view' );
				$this->_listCurrent();
			break;
			case 'showArchived':
				$this->registry->output->core_nav[]		= array( $this->settings['base_url'] . 'module=logs&section=adminlogs&do=showArchived', $this->lang->words['archiver_title'] );
				$this->_listArchiver();
			break;
		}
		
		/* Output */
		$this->registry->output->html_main .= $this->registry->output->global_template->global_frame_wrapper();
		$this->registry->output->sendOutput();	
	}
	
	/**
	 * View archive logs
	 *
	 * @return	@e void		[Outputs to screen]
	 */
	protected function _listArchiver()
	{
		$data  = array();
		$start = intval( $this->request['st'] );
		
		/* Get count */
		$count = $this->DB->buildAndFetch( array( 'select' => 'count(*) as count', 'from' => 'core_archive_log' ) );
		
		$this->DB->build( array( 'select' => '*',
								 'from'   => 'core_archive_log',
								 'order'  => 'archlog_date DESC',
								 'limit'  => array( $start, 50 ) ) );
		$this->DB->execute();
		
		while( $row = $this->DB->fetch() )
		{
			$data[] = $row;
		}
		
		/* Pagination */
		$links = $this->registry->output->generatePagination( array( 'totalItems'			=> $count['count'],
																	 'itemsPerPage'			=> 50,
																	 'currentStartValue'	=> $start,
																	 'baseUrl'				=> $this->settings['base_url'] . $this->form_code . '&amp;do=showArchived' ) );

		/* Show output */
		
		$this->registry->output->html .= $this->html->archiverlogsView( $data, $links );
	}
	
	/**
	 * View all logs for a given admin
	 *
	 * @return	@e void		[Outputs to screen]
	 */
	protected function _view()
	{
		///----------------------------------------
		// Basic init
		//-----------------------------------------
		
		$start	= intval($this->request['st']) >= 0 ? intval($this->request['st']) : 0;

		///----------------------------------------
		// No mid or search string?
		//-----------------------------------------
				
		if ( !$this->request['search_string'] AND !$this->request['mid'] )
		{
			$this->registry->output->global_message = $this->lang->words['alog_nostring'];
			$this->_listCurrent();
			return;
		}
		
		$this->request['mid']	= $this->request['mid'] == 'zero' ? 0 : $this->request['mid'];
		
		///----------------------------------------
		// mid?
		//-----------------------------------------
		
		if ( !$this->request['search_string'] )
		{
			$row	= $this->DB->buildAndFetch( array( 'select' => 'COUNT(id) as count', 'from' => 'admin_logs', 'where' => "member_id=" . intval($this->request['mid']) ) );

			$query	= "{$this->form_code}&amp;mid=" . ( $this->request['mid'] ? $this->request['mid'] : 'zero' ) . "&amp;do=view";
			
			$this->DB->build( array( 'select'		=> 'm.*',
											'from'		=> array( 'admin_logs' => 'm' ),
											'where'		=> 'm.member_id=' . intval($this->request['mid']),
											'order'		=> 'm.ctime DESC',
											'limit'		=> array( $start, 20 ),
											'add_join'	=> array(
																array( 'select'	=> 'mem.members_display_name',
																		'from'	=> array( 'members' => 'mem' ),
																		'where'	=> 'mem.member_id=m.member_id',
																		'type'	=> 'left'
																	)
																)
								)		);
			$this->DB->execute();
		}
		
		///----------------------------------------
		// search string?
		//-----------------------------------------
		
		else
		{
			$this->request['search_string'] = IPSText::parseCleanValue( urldecode($this->request['search_string'] ) );
			
			if( !$this->DB->checkForField( $this->request['search_type'], 'admin_logs' ) )
			{
				$this->registry->output->showError( $this->lang->words['alog_whatfield'], 4110, true );
			}
			
			if( $this->request['search_type'] == 'member_id' )
			{
				$dbq = "m." . $this->request['search_type'] . "='" . $this->request['search_string'] . "'";
			}
			else
			{
				$dbq = "m." . $this->request['search_type'] . " LIKE '%" . $this->request['search_string'] . "%'";
			}
			
			$row	= $this->DB->buildAndFetch( array( 'select' => 'COUNT(m.member_id) as count', 'from' => 'admin_logs m', 'where' => $dbq ) );

			$query	= "{$this->form_code}&amp;do=view&amp;search_type=" . $this->request['search_type'] . "&amp;search_string=" . urlencode($this->request['search_string']);
			
			$this->DB->build( array( 'select'		=> 'm.*',
											'from'		=> array( 'admin_logs' => 'm' ),
											'where'		=> $dbq,
											'order'		=> 'm.ctime DESC',
											'limit'		=> array( $start, 20 ),
											'add_join'	=> array(
																array( 'select'	=> 'mem.members_display_name',
																		'from'	=> array( 'members' => 'mem' ),
																		'where'	=> 'mem.member_id=m.member_id',
																		'type'	=> 'left'
																	)
																)
								)		);
			$this->DB->execute();
		}
		
		///----------------------------------------
		// Page links
		//-----------------------------------------
		
		$links = $this->registry->output->generatePagination( array( 'totalItems'			=> $row['count'],
																	 'itemsPerPage'			=> 20,
																	 'currentStartValue'	=> $start,
																	 'baseUrl'				=> $this->settings['base_url'] . $query,
														)
												 );

		///----------------------------------------
		// Get db results
		//-----------------------------------------
		
		while ( $row = $this->DB->fetch() )
		{
			if( !$row['member_id'] )
			{
				$row['member_id']	= 'zero';
			}
			
			if( !$row['members_display_name'] )
			{
				$row['members_display_name']	= $this->lang->words['noname_availabl'];
			}

			$row['_time']	= $this->registry->class_localization->getDate( $row['ctime'], 'LONG' );
			$rows[]			= $row;
		}

		///----------------------------------------
		// And output
		//-----------------------------------------
		
		$this->registry->output->html .= $this->html->adminlogsView( $rows, $links );
	}
	
	/**
	 * Remove logs by an admin
	 *
	 * @return	@e void		[Outputs to screen]
	 */
	protected function _remove()
	{
		if ( !$this->request['mid'] )
		{
			$this->registry->output->showError( $this->lang->words['alog_whoselog'], 11114 );
		}
		
		$this->request['mid']	= $this->request['mid'] == 'zero' ? 0 : $this->request['mid'];
		
		$this->DB->delete( 'admin_logs', "member_id=" . intval($this->request['mid']) );
		
		$this->registry->output->silentRedirect( $this->settings['base_url'] . $this->form_code );
	}
	
	/**
	 * List the current logs with links to view per-admin
	 *
	 * @return	@e void		[Outputs to screen]
	 */
	protected function _listCurrent()
	{
		$rows			= array();
		$admins			= array();

		//-----------------------------------------
		// LAST FIVE ACTIONS
		//-----------------------------------------
		
		$this->DB->build( array( 'select'		=> 'm.*',
									'from'		=> array( 'admin_logs' => 'm' ),
									'order'		=> 'm.ctime DESC',
									'limit'		=> array( 5 ),
									'add_join'	=> array(
														array( 'select'	=> 'mem.members_display_name',
																'from'	=> array( 'members' => 'mem' ),
																'where'	=> 'mem.member_id=m.member_id',
																'type'	=> 'left'
															)
														)
							)		);
		$this->DB->execute();

		while ( $row = $this->DB->fetch() )
		{
			$row['_time']	= $this->registry->class_localization->getDate( $row['ctime'], 'LONG' );
			$rows[]			= $row;
		}

		//-----------------------------------------
		// All admins
		//-----------------------------------------
		
		$this->DB->build( array( 
							'select'	=> 'count(l.member_id) as act_count, l.member_id',
							'from'		=> array( 'admin_logs' => 'l' ),
							'group'		=> 'l.member_id, m.members_display_name',
							'order'		=> 'act_count DESC',
							'add_join'	=> array(
												array(
													'select'	=> 'm.members_display_name',
													'from'		=> array( 'members' => 'm' ),
													'where'		=> 'm.member_id=l.member_id',
													'type'		=> 'left',
													),
												),
						)		);
		$this->DB->execute();

		while ( $r = $this->DB->fetch() )
		{
			if( !$r['member_id'] )
			{
				$r['member_id']	= 'zero';
			}
			
			if( !$r['members_display_name'] )
			{
				$r['members_display_name']	= $this->lang->words['noname_availabl'];
			}

			$admins[ $r['member_id'] ] = $r;
		}

		//-----------------------------------------
		// And output
		//-----------------------------------------
		
		$this->registry->output->html .= $this->html->adminlogsWrapper( $rows, $admins );
	}
}
