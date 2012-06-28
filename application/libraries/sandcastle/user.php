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
	 * User Class
	 * 
	 * Provides the needed functions to make user management easy.
	 *
	 * @package 	SandCastle
	 * @subpackage 	Libraries
	 * @category 	User
	 * @author 		William Duyck <wduyck@gmail.com>
	 * @copyright 	Copyleft 2012, William Duyck
	 * @license 	https://www.mozilla.org/MPL/2.0/ MPL v2.0
	 * @link 		http://www.wduyck.com/ wduyck.com
	 *
	 * @todo User Features
	 * * write testcases
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
			// load CodeIgniter sessions library
			$this->CI->load->library('session');
		}
		
		/**
		 * Signs a user in
		 *
		 * Sets a session cookie for a user so they stay signed for a set amount
		 * of time, defined in the CodeIgniter config file
		 * `application/config/config.php`. The user will then be redirected to
		 * the path passed to the function
		 *
		 * @param	string	$user_email		The user to sign in
		 * @param	int		$user_status	The status of the user (e.g. 0 = admin (by default)
		 * @param	string	$redirect_path	The uri to redirect the user to once complete
		 */
		public function sign_in($user_email, $user_status, $redirect_path)
		{
			// load CI url helper for redirect
			$this->CI->load->helper('url');
			
			// create the user session
			$this->CI->session->set_userdata(array(
				'user_email'	= $user_email,
				'user_status'	= $user_status
			));
			
			// redirect to the correct page
			redirect($redirect_path);
		}
		
		/**
		 * Signs a user out
		 *
		 * Destroys the users session cookie and redirects to the path passed to
		 * the function.
		 *
		 * @param	string	$redirect_path	The uri to redirect the user to once complete
		 */
		public function sign_out($redirect_path)
		{
			// load CI url helper for redirect
			$this->CI->load->helper('url');
			
			// destroy user session outright
			$this->CI->session->sess_destroy();
			
			// redirect the user
			redirect($redirect_path);
		}
		
		/**
		 * Checks if a user is signed in
		 *
		 * @return boolean	TRUE if user signed in
		 */
		public function is_signed_in()
		{
			return ($this->CI->session->userdata('user_email') !== FALSE) ? TRUE : FALSE;
		}
		
		/**
		 * Hashes a users password
		 *
		 * @param	string	$str	The string to be hashed
		 * @param	string	$salt	This is a unique salt for this password
		 * @return	string	The hashed form of the string
		 */
		public function hash_password($str, $salt)
		{
			return hash('sha512', $this->config->secret_salt . $str . $salt);
		}
	}
	
/* End of file user.php */
/* Location: application/libraries/user.php */