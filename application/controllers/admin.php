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
	 * Admin Controller
	 * 
	 * Provides the needed functions to make managing a basic community portal a
	 * piece of cake. This controller acts as an example of one way the Planet
	 * Library and Planet Model can be used together to produce a functioning
	 * planet, as well as an events listing.
	 *
	 * @package 	SandCastle
	 * @subpackage 	Controllers
	 * @category 	Portal
	 * @author 		William Duyck <wduyck@gmail.com>
	 * @copyright 	Copyleft 2012, William Duyck
	 * @license 	https://www.mozilla.org/MPL/2.0/ MPL v2.0
	 * @link 		http://www.wduyck.com/ wduyck.com
	 *
	 * @todo Admin Features
	 * * write views for all pages
	 * * setup some uri routing within the config files
	 */
	class Admin extends CI_Controller
	{
		/**
		 * Constructor
		 *
		 * constructs the controller and ensures that the parent is linked to
		 * correctly when we load libraries, models, and helpers
		 */
		public function __construct()
		{
			parent::__construct();
			
			$this->load->library('form_validation');
			$this->load->helper(array('form', 'url'))
			
			// load sandcastle components needed over and over
			$this->load->library('sandcastle/user');
			$this->load->model('sandcastle/user_model');
		}
		
		/**
		 * Signs a user into the admin area
		 *
		 * @param	string	$redirect_path	The path to redirect to on success
		 */
		public function sign_in($redirect_path = NULL)
		{
			// `get_class` used so that this class can be renamed and still work
			// decoupling the class from the uri a little more
			$redirect_path = ($redirect_path !== NULL) ? $redirect_path : get_class($this);
			
			// set validation rules
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$email = $this->input->post('email');
			$this->form_validation->set_rules('password', 'Password', "required|calback__valid_sign_in[$email]");
			
			// run form validation and check login credentials if form entry is valid
			if($this->form_validation->run() === FALSE)
			{
				// load the sign in form
			}
			else
			{
				$this->user->sign_in($email, $this->user_model->get_status($email), $redirect_path);
			}
		}
		
		/**
		 * Checks if a sign in is valid
		 *
		 * @param	string	$email		The provided email of the user attempting to sign in
		 * @param	string	$password	The provided password of the user attempting to sign in
		 * @return	boolean	Whether or not a sign in is valid
		 */
		private function _valid_sign_in($email, $password)
		{
			$password_hash = $this->user->hash_password($password, $email);
			return $this->user_model->valid_sign_in($email, $password_hash);
		}
	}
	
/* End of file admin.php */
/* Location: application/controllers/admin.php */