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
	 * Event Class
	 * 
	 * Provides the needed functions to store and list upcoming events, as well
	 * as events from the past.
	 *
	 * @package 	SandCastle
	 * @subpackage 	Libraries
	 * @category 	Event
	 * @author 		William Duyck <wduyck@gmail.com>
	 * @copyright 	Copyleft 2012, William Duyck
	 * @license 	https://www.mozilla.org/MPL/2.0/ MPL v2.0
	 * @link 		http://www.wduyck.com/ wduyck.com
	 *
	 * @todo Event Features
	 * * 
	 */
	class Event
	{
		/** @var	CodeIgniter	CodeIgniter instance */
		private $CI;
		
		/**
		 * Constructor
		 *
		 * Gets the CodeIgniter instance, and loads the Event configuration
		 */
		public function __construct()
		{
			// get CodeIgniter instance
			$this->CI =& get_instance();
			// get SandCastle config
			$this->CI->config->load('sandcastle');
			// get Event config
			$this->config = (object)$this->CI->config->item('event', 'sandcastle');
		}
		
		/**
		 * 
		 */
	}
	
/* End of file event.php */
/* Location: application/libraries/event.php */