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
		 * Provides a dashboard for the admin area
		 */
		public function index()
		{
			// if user is not signed in send the to sign in page
			if(!$this->user->is_signed_in())
			{
				$this->sign_in(strtolower(get_class($this)) . '');
				return;
			}
			
			$this->load->view('sandcastle/admin/index');
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
			// if user is not signed in send the to sign in page
			if(!$this->user->is_signed_in())
			{
				$this->sign_in(strtolower(get_class($this)) . '/users');
				return;
			}
			
			$data['users'] = $this->user_model->get();
			$this->load->view('sandcastle/admin/users', $data);
		}
		
		/**
		 * Allows the addition of users
		 */
		public function add_user()
		{
			// if user is not signed in send the to sign in page
			if(!$this->user->is_signed_in())
			{
				$this->sign_in(strtolower(get_class($this)) . '/add_user');
				return;
			}
			
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
		 * Deletes a user after confirmation
		 */
		public function delete_user()
		{
			// if user is not signed in send the to sign in page
			if(!$this->user->is_signed_in())
			{
				$this->sign_in(strtolower(get_class($this)) . '/delete_user');
				return;
			}
			
			// get the email of the user to remove and show error if not provided
			$user = ($this->input->get('email')) ? $this->input->get('email') : $this->input->post('email');
			if(!$user)
			{
				show_error('No user specified for deletion', 400, '400 Bad Request');
			}
			
			// set validation rules
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback__user_exists');
			
			// run validation and delete user if needed
			if($this->form_validation->run() === FALSE)
			{
				$user = $this->user_model->get($user);
				$data['user'] = $user[0];
				$this->load->view('sandcastle/form/delete_user', $data);
			}
			else
			{
				$this->user_model->delete($user);
				redirect(strtolower(get_class($this)) . '/users');
			}
		}
		
		/**
		 * Allows for the modification of users
		 */
		public function edit_user()
		{
			// if user is not signed in send the to sign in page
			if(!$this->user->is_signed_in())
			{
				$this->sign_in(strtolower(get_class($this)) . '/edit_user');
				return;
			}
			
			// get the email of the user to remove and show error if not provided
			$user = ($this->input->get('email')) ? $this->input->get('email') : $this->input->post('email');
			if(!$user)
			{
				show_error('No user specified for modification', 400, '400 Bad Request');
			}
			
			// set validation rules
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('confirm_email', 'Confirm Email', 'trim|required|matches[email]');
			$this->form_validation->set_rules('name', 'Display Name', 'trim');
			$this->form_validation->set_rules('status', 'Status', 'required');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'matches[password]');
			
			// run validation and delete user if needed
			if($this->form_validation->run() === FALSE)
			{
				$user = $this->user_model->get($user);
				$data['user'] = $user[0];
				$data['statuses'] = $this->user->get_config()->status;
				$this->load->view('sandcastle/form/edit_user', $data);
			}
			else
			{
				$data = array(
					'name'		=> $this->input->post('name'),
					'status'	=> $this->input->post('status'),
					'email'		=> $this->input->post('email')
				);
				
				if($this->input->post('password'))
				{
					$data['password'] = $this->user->hash_password($this->input->post('password'), $this->input->post('email'));
				}
				
				$this->user_model->update($user, $data);
				redirect(strtolower(get_class($this)) . '/users');
			}
		}
		
		/**
		 * Lists events
		 */
		public function events()
		{
			if(!$this->user->is_signed_in())
			{
				$this->sign_in(strtolower(get_class($this)) . '/events');
				return;
			}
			
			$data['events'] = $this->event_model->get_event();
			$this->load->view('sandcastle/admin/events', $data);
		}
		
		/**
		 * Allows users to add events
		 */
		public function add_event()
		{
			if(!$this->user->is_signed_in())
			{
				$this->sign_in(strtolower(get_class($this)) . '/add_event');
				return;
			}
			
			// set validation rules
			$this->form_validation->set_rules('url', 'Event URL', 'trim|prep_url|required');
			$this->form_validation->set_rules('name', 'Event Name', 'trim|max_length[40]|required');
			$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[300]');
			$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required|callback__valid_date_format');
			$start_date = $this->input->post('start_date');
			$this->form_validation->set_rules('finish_date', 'Finish Date', "trim|callback__valid_date_format|callback__valid_finish_date[$start_date]");
			
			// run form validation and add event / tags if needed
			if($this->form_validation->run() === FALSE)
			{
				$this->load->view('sandcastle/form/add_event');
			}
			else
			{
				// deal with tags
				if(($this->input->post('tags') !== FALSE) && preg_match('/,/', $this->input->post('tags')))
				{
					$tags = explode(',', $this->input->post('tags'));
					foreach($tags as &$tag)
					{
						$tag = strtolower(trim($tag));
						if($tag === '')
						{
							unset($tag);
						}
					}
				}
				elseif($this->input->post('tags') !== FALSE)
				{
					$tags = $this->input->post('tags');
				}
				else
				{
					$tags = NULL;
				}
				
				// load the CodeIgniter date helper
				$this->load->helper('date');
				
				// deal with finish date
				$finsih_date = ($this->input->post('finish_date') !== FALSE) ? strtotime($this->input->post('finish_date')) : NULL;
				
				// deal with start date
				$start_date = strtotime($this->input->post('start_date'));
				
				// add event
				$this->event_model->add_event($this->input->post('url'),
											  $this->input->post('name'),
											  $this->input->post('description'),
											  $start_date,
											  $finsih_date,
											  $tags);
				
				// redirect back
				redirect(strtolower(get_class($this)) . '/events');
			}
		}
		
		/**
		 * Allows user to delete events
		 */
		public function delete_event($id = FALSE)
		{
			// if user is not signed in send the to sign in page
			if(!$this->user->is_signed_in())
			{
				$this->sign_in(strtolower(get_class($this)) . '/delete_event/' . $id);
				return;
			}
			
			// check the id of the event to delete was specified
			if($id === FALSE)
			{
				show_error('No event specified for deletion', 400, '400 Bad Request');
			}
			
			// set validation rules
			$this->form_validation->set_rules('event_id', 'event_id', 'trim|required');
			
			// run validation and delete user if needed
			if($this->form_validation->run() === FALSE)
			{
				$event = $this->event_model->get_event($id);
				$data['event'] = $event[0];
				$this->load->view('sandcastle/form/delete_event', $data);
			}
			else
			{
				$this->db->delete('event', array('event_id' => $this->input->post('event_id')));
				redirect(strtolower(get_class($this)) . '/events');
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
		
		/**
		 * Determines if a user exists within the database
		 *
		 * @param	string	$email	The email of the user to check for
		 * @return	boolean	TRUE if user exists
		 */
		public function _user_exists($email)
		{
			$this->form_validation->set_message('_user_exists', 'The user %s does not exist');
			$query = $this->db->get_where('user', array('email' => $email));
			return ($query->num_rows() === 1);
		}
		
		/**
		 * Checks if the date format submitted by the user is valid
		 *
		 * @param	string	$date	The string to check
		 * @return	boolean	TRUE if valid
		 */
		public function _valid_date_format($date)
		{
			$this->form_validation->set_message('_valid_date_format', 'Date format not recognised. Please enter in the following format yyyy-mm-dd');
			//return preg_match('/\d\d\d\d(\/|-|\.)\d{1,2}(\/|-)\d{1,2}/i', $string);
			$this->load->helper('date');
			return (strtotime($date) !== FALSE) ? TRUE : FALSE;
		}
		
		/**
		 * Checks if one date is bigger than the other
		 *
		 * @param	string	$after	The second date
		 * @param	string	$before	The first date (this should be before the previous chronologically)
		 * @return	boolean	TRUE if second date is after first
		 */
		public function _valid_finish_date($after, $before)
		{
			// load the CodeIgniter date helper
			$this->load->helper('date');
			// do the comparison
			return (strtotime($after) >= strtotime($before));
		}
	}
	
/* End of file admin.php */
/* Location: application/controllers/admin.php */
