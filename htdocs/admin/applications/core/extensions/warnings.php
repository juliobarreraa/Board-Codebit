<?php
/**
 * @file		warnings.php 	Warnings extension for core
 *~TERABYTE_DOC_READY~
 * $Copyright: (c) 2001 - 2011 Invision Power Services, Inc.$
 * $License: http://www.invisionpower.com/company/standards.php#license$
 * $Author: bfarber $ (Original: Mark)
 * @since		-
 * $LastChangedDate: 2012-06-12 10:14:49 -0400 (Tue, 12 Jun 2012) $
 * @version		v3.3.4
 * $Revision: 10914 $
 */

if ( ! defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

/**
 * @class		warnings_core
 * @brief		Warnings Extension for core
 */
class warnings_core
{
	/**
	 * Get Content URL
	 *
	 * @param	array		$warning		Row from members_warn_logs
	 * @return	@e array	array( url => URL to the content the warning came from, title => Title )
	 */
	public function getContentUrl( $warning )
	{
		ipsRegistry::getClass('class_localization')->loadLanguageFile( array( 'public_profile' ), 'members' );
		
		if ( $warning['wl_content_id1'] == 'mreport' )
		{		
			$report = ipsRegistry::DB()->buildAndFetch( array( 'select' => '*', 'from' => 'rc_reports_index', 'where' => "id=" . intval( $warning['wl_content_id2'] ) ) );
			
			if ( ! empty($report['id']) )
			{
				return array( 'url' => ipsRegistry::getClass('output')->buildUrl( "app=core&module=reports&do=show_report&rid={$report['id']}" ), 'title' => $report['title'] );
			}
		}
		elseif ( $warning['wl_content_id1'] == 'core-reports' )
		{
			$exploded = explode( '-', $warning['wl_content_id2'] );
		
			require_once( IPS_ROOT_PATH . 'sources/classes/comments/bootstrap.php' );/*noLibHook*/
			$comments = classes_comments_bootstrap::controller( $warning['wl_content_id1'] );
			
			$parent = $comments->remapFromLocal( $comments->fetchParent( $exploded[0] ), 'parent' );
			
			if ( ! empty($parent['parent_id']) )
			{
				return array( 'url' => ipsRegistry::getClass('output')->buildUrl( "app=core&module=global&section=comments&fromApp={$warning['wl_content_id1']}&do=findComment&parentId={$exploded[0]}&comment_id={$exploded[1]}" ), 'title' => $parent['parent_title'] );
			}
		}
		
		return NULL;
	}
}