<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
	
	/**
	 * SandCastle
	 *
	 * An opensource set of tools for making a basic community site.
	 *
	 * @package 	SandCastle
	 * @author 		William Duyck <wduyck@gmail.com>
	 * @copyright 	Copyleft 2012, William Duyck
	 * @license 	https://www.mozilla.org/MPL/2.0/ MPL v2.0
	 * @link 		http://www.wduyck.com/ wduyck.com
	 * @filesource
	 */
	
	// -------------------------------------------------------------------------
	
	$config['sandcastle'] = array();
	
	/**
	 * SandCastle Core Config
	 * 
	 * Provides the configuration settings for SandCastle's core components.
	 *
	 * @package 	SandCastle
	 * @subpackage 	Config
	 * @category 	Core
	 * @author 		William Duyck <wduyck@gmail.com>
	 * @copyright 	Copyleft 2012, William Duyck
	 * @license 	https://www.mozilla.org/MPL/2.0/ MPL v2.0
	 * @link 		http://www.wduyck.com/ wduyck.com
	 */
	$config['sandcastle']['core'] = array();
	
	// -------------------------------------------------------------------------
	
	/**
	 * SandCastle Planet Config
	 * 
	 * Provides the configuration settings for the SandCastle planet.
	 *
	 * @package 	SandCastle
	 * @subpackage 	Config
	 * @category 	Planet
	 * @author 		William Duyck <wduyck@gmail.com>
	 * @copyright 	Copyleft 2012, William Duyck
	 * @license 	https://www.mozilla.org/MPL/2.0/ MPL v2.0
	 * @link 		http://www.wduyck.com/ wduyck.com
	 */
	$config['sandcastle']['planet'] = array(
		// the prefix to use on cache id's in CodeIgniters cache driver
		'cache_prefix' 	=> '',
		// the number of minutes to keep the cache
		'cache_expires' => 60
	);