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
			$this->load->helper(array('form', 'url'));
			
			// load sandcastle components needed over and over
			$this->load->library(array('sandcastle/user', 'sandcastle/planet'));
			$this->load->model(array('sandcastle/user_model', 'sandcastle/planet_model', 'sandcastle/event_model'));
			
		}
		
		/**
		 * Signs a user into the admin area
		 *
		 * @param	string	$redirect_path	The path to redirect to on success
		 */
		public function sign_in($redirect_path = NULL)
		{
			// check if user is signed in and if so send them to the admin index
			if($this->user->is_signed_in())
			{
				redirect(strtolower(get_class($this)));
			}
			
			// `get_class` used so that this class can be renamed and still work
			// decoupling the class from the uri a little more
			$redirect_path = ($redirect_path !== NULL) ? $redirect_path : strtolower(get_class($this));
			
			// set validation rules
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$email = $this->input->post('email');
			$this->form_validation->set_rules('password', 'Password', "required|callback__valid_sign_in[$email]");
			
			// run form validation and check login credentials if form entry is valid
			if($this->form_validation->run() === FALSE)
			{
				$this->load->view('sandcastle/form/sign_in');
			}
			else
			{
				$this->user->sign_in($email, $this->user_model->get_status($email), $redirect_path);
			}
		}
		
		/**
		 * Signs a user out of the admin area
		 *
		 * @param	string	$redirect_path	The path to redirect to on success
		 */
		public function sign_out($redirect_path = NULL)
		{
			// if user is not signed in send the to sign in page
			if(!$this->user->is_signed_in())
			{
				redirect(strtolower(get_class($this)) . '/sign_in');
			}
			
			// `get_class` used so that this class can be renamed and still work
			// decoupling the class from the uri a little more
			$redirect_path = ($redirect_path !== NULL) ? $redirect_path : strtolower(get_class($this));
			
			$this->user->sign_out($redirect_path);
		}
		
		/**
		 * Lists all feeds in the database and provides links to add new feeds OR delete old ones
		 */
		public function feeds()
		{
			// if user is not signed in send the to sign in page
			if(!$this->user->is_signed_in())
			{
				$this->sign_in(strtolower(get_class($this)) . '/feeds');
				return;
			}
			
			$data['feeds'] = $this->planet_model->get_feeds();
			$this->load->view('sandcastle/admin/feeds', $data);
		}
		
		/**
		 * Allows the addition of feeds
		 */
		public function add_feed()
		{
			// if user is not signed in send the to sign in page
			if(!$this->user->is_signed_in())
			{
				$this->sign_in(strtolower(get_class($this)) . '/add_feed');
				return;
			}
			
			// set validation rules
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
			$this->form_validation->set_rules('feed_url', 'Feed URL', 'trim|prep_url|required|callback__valid_feed');
			
			// run form validation and add feed if possible
			if($this->form_validation->run() === FALSE)
			{
				$this->load->view('sandcastle/form/add_feed');	
			}
			else
			{
				$this->planet_model->add_feed($this->input->post('email'), $this->input->post('feed_url'));
				redirect(strtolower(get_class($this)) . '/feeds');
			}
		}
		
		/**
		 * Allows the deletion of feeds
		 */
		public function delete_feed()
		{
			// if user is not signed in send the to sign in page
			if(!$this->user->is_signed_in())
			{
				$this->sign_in(strtolower(get_class($this)) . '/delete_feed');
				return;
			}
			
			// get the url of the feed to remove and show error if not provided
			$url = ($this->input->get('feed_url')) ? $this->input->get('feed_url') : $this->input->post('feed_url');
			if(!$url)
			{
				show_error('No feed specified for deletion', 400, '400 Bad Request');
			}
			
			// set validation rules
			$this->form_validation->set_rules('feed_url', 'Feed URL', 'required');
			
			// run from validation and remove feed if confrimation given
			if($this->form_validation->run() === FALSE)
			{
				$data['feed_url'] = $url;
				$data['feed_title'] = $this->planet->get_feed_title($url);
				$this->load->view('sandcastle/form/delete_feed', $data);
			}
			else
			{
				$this->planet_model->delete_feed($url);
				redirect(strtolower(get_class($this)) . '/feeds');
			}
		}
		
		/**
		 * Lists all users and provides a few links to make changes as needed 
		 */
		public function users()
		{
			$data['users'] = $this->user_model->get();
			$this->load->view('sandcastle/admin/users', $data);
		}
		
		/**
		 * Allows the addition of users
		 */
		public function add_user()
		{
			// set validation rules
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[user.email]');
			$this->form_validation->set_rules('confirm_email', 'Confirm Email', 'trim|required|matches[email]');
			$this->form_validation->set_rules('name', 'Display Name', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
			$this->form_validation->set_rules('status', 'Status', 'required');
			
			// run form validation and add user if needed
			if($this->form_validation->run() === FALSE)
			{
				$data['statuses'] = $this->user->get_config()->status;
				$this->load->view('sandcastle/form/add_user', $data);
			}
			else
			{
				// add the user to the database
				$data = array(
					'name'		=> $this->input->post('name'),
					'email'		=> $this->input->post('email'),
					'password'	=> $this->user->hash_password($this->input->post('password'), $this->input->post('email')),
					'status'	=> $this->input->post('status')
				);
				$this->user_model->insert($data);
				
				// redirect to users page
				redirect(strtolower(get_class($this)) . '/users');
			}
		}
		
		/**
		 * Checks if a sign in is valid
		 *
		 * @param	string	$email		The provided email of the user attempting to sign in
		 * @param	string	$password	The provided password of the user attempting to sign in
		 * @return	boolean	TRUE if valid
		 */
		public function _valid_sign_in($password, $email)
		{
			$this->form_validation->set_message('_valid_sign_in', 'Invalid sign in credentials');
			$password_hash = $this->user->hash_password($password, $email);
			return $this->user_model->valid_sign_in($email, $password_hash);
		}
		
		/**
		 * Alias to the planet library's `valid_feed` function
		 *
		 * @param	string	$url	The url the feed resides at
		 * @return	boolean	TRUE if valid
		 */
		public function _valid_feed($url)
		{
			$this->form_validation->set_message('_valid_feed', 'There is an error with the feed at %s. Is the url valid? Is the feed online? Is the feed RSS 2.0 or ATOM?');
			return $this->planet->valid_feed($url);
		}
	}
	
/* End of file admin.php */
/* Location: application/controllers/admin.php */