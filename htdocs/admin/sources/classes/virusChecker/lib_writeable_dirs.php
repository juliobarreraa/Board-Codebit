<?php
/**
 * <pre>
 * Invision Power Services
 * IP.Board v3.3.4
 * Virus scanner: writable directories
 * Last Updated: $Date: 2012-06-12 10:14:49 -0400 (Tue, 12 Jun 2012) $
 * </pre>
 *
 * @author 		$Author: bfarber $
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @link		http://www.invisionpower.com
 * @since		Tue. 17th August 2004
 * @version		$Rev: 10914 $
 *
 */


$WRITEABLE_DIRS = array(
'cache',
'cache/skin_cache',
'cache/lang_cache',
PUBLIC_DIRECTORY . '/style_emoticons',
PUBLIC_DIRECTORY . '/style_images',
PUBLIC_DIRECTORY . '/style_css',
'uploads'
);
