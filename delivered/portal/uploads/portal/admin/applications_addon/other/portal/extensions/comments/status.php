<?php
/**
 * Portal Comments class
 * @author           $author$
 * @copyright        (c) 2012
 * @licence          http://www.codebit.org/
 * @package          IP.Portal
 * @link             http://www.codebit.org
 * @version          $Rev: 001
 */
 
 
if ( ! defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

class comments_portal_status extends classes_comments_renderer 
{
	/** Registry reference */
	protected $registry;
	
	/**
	 * Internal remap array
	 *
	 * @param	array
	 */
	private $_remap = array( 'comment_id'			=> 'portal_comment_id',
							 'comment_author_id'	=> 'portal_comment_author_id',
							 'comment_author_name'  => 'portal_comment_author_name',
							 'comment_text'			=> 'portal_comment_text',
							 'comment_ip_address'   => 'portal_comment_ip_address',
							 'comment_edit_date'	=> 'portal_comment_edit_time',
							 'comment_date'			=> 'portal_comment_date',
							 'comment_approved'		=> 'portal_comment_approved',
							 'comment_parent_id'	=> 'portal_comment_parent_id' );
					 
	/**
	 * Internal parent remap array
	 *
	 * @param	array
	 */
	private $_parentRemap = array( 'parent_id'			=> 'status_id',
							 	   'parent_owner_id'	=> 'status_author_id',
							       'parent_date'	    => 'status_date' );
							       
	/**
	 * CONSTRUCTOR
	 *
	 * @return	@e void
	 */
	public function __construct()
	{
		/* Make registry objects */
		$this->registry   =  ipsRegistry::instance();
		$this->DB         =  $this->registry->DB();
		$this->settings   =& $this->registry->fetchSettings();
		$this->request    =& $this->registry->fetchRequest();
		$this->lang       =  $this->registry->getClass('class_localization');
		$this->member     =  $this->registry->member();
		$this->memberData =& $this->registry->member()->fetchMemberData();
		$this->cache      =  $this->registry->cache();
		$this->caches     =& $this->registry->cache()->fetchCaches();
	}
							       
	/**
	 * Who am I?
	 *
	 * @return	string
	 */
	public function whoAmI()
	{
		return 'portal-status';
	}
	
	/**
	 * Who am I?
	 * 
	 * @return	@e string
	 */
	public function seoTemplate()
	{
		return 'viewstatus';
	}
	
	/**
	 * Comment table
	 *
	 * @return	string
	 */
	public function table()
	{
		return 'comments_portal';
	}
	
    
	/**
	 * Fetch parent
	 *
	 * @return	@e array
	 */
	public function fetchParent( $id )
	{
		return $this->DB->buildAndFetch(array 
		                            (
		                                 'select'    => '*',
		                                 'from'      => 'member_status_updates',
		                                 'where'     => 'status_id = ' . $id,
		                            )
		                      );
	}
	
	/**
	 * Fetch settings
	 *
	 * @return	@e array
	 */
	public function settings()
	{
		return array( 'urls-showParent' => "app=portal&module=portal&section=comments&amp;sid=%s",
					  'urls-report'		=> '',
					 );
	}
	
	/**
	 * Fetch a total count of comments we can see
	 *
	 * @param	mixed	parent Id or parent array
	 * @return	int
	 */
	public function count( $parent )
	{
		return 5;
	}
	
	/**
	 * Perform a permission check
	 *
	 * @param	string	Type of check (add/edit/delete/editall/deleteall/approve all)
	 * @param	array 	Array of GENERIC data
	 * @return	true or string to be used in exception
	 */
	public function can( $type, array $array )
	{ 
		/* Init */
		$comment = array();
		
		/* Got data? */
		if ( empty( $array['comment_parent_id'] ) )
		{
			trigger_error( "No parent ID passed to " . __FILE__, E_USER_WARNING );
		}
		
		/* Still here? We're not telling lies then */
		return true;
	}
	
	/**
	 * Returns remap keys (generic => local)
	 *
	 * @return	@e array
	 */
	public function remapKeys($type='comment')
	{
		return ( $type == 'comment' ) ? $this->_remap : $this->_parentRemap;
	}
}