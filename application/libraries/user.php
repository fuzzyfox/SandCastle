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
	
	/**
	 * Planet Class
	 * 
	 * Provides the needed functions to make running a basic planet a piece of
	 * cake.
	 *
	 * Supports RSS 2.0 and ATOM feeds
	 *
	 * @package 	SandCastle
	 * @subpackage 	Libraries
	 * @category 	Planet
	 * @author 		William Duyck <wduyck@gmail.com>
	 * @copyright 	Copyleft 2012, William Duyck
	 * @license 	https://www.mozilla.org/MPL/2.0/ MPL v2.0
	 * @link 		http://www.wduyck.com/ wduyck.com
	 *
	 * @todo Planet Features
	 * * add RSS 1.0 support
	 * * add caching support for pulled feeds
	 * * test osort function
	 * * test class
	 * * write testcase
	 */
	class User
	{
		/** @var	CodeIgniter	CodeIgniter instance */
		private $CI;
		/** @var	object		configuration for user management*/
		private $config;
		
		/**
		 * Constructor
		 *
		 * Gets the CodeIgniter instance and loads the user management
		 * configuration
		 */
		public function __construct()
		{
			// get CodeIgniter instance
			$this->CI =& get_instance();
			// get SandCastle config
			$this->CI->config->load('sandcastle');
			// get user management config
			$this->config = (object)$this->CI->config->item('user', 'sandcastle');
		}
	}
	
/* End of file user.php */
/* Location: application/libraries/user.php */