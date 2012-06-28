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
	 * User Model
	 * 
	 * Provides the needed functions to make user management a piece of
	 * cake. More specifically it deals with the database side of things.
	 *
	 * When talking about the "database result object" this refers to the
	 * CodeIgniter database result object, i.e. `$query->result();`
	 *
	 * @package 	SandCastle
	 * @subpackage 	Models
	 * @category 	User
	 * @author 		William Duyck <wduyck@gmail.com>
	 * @copyright 	Copyleft 2012, William Duyck
	 * @license 	https://www.mozilla.org/MPL/2.0/ MPL v2.0
	 * @link 		http://www.wduyck.com/ wduyck.com
	 *
	 * @todo Planet Features
	 * 
	 */
	class Planet_model extends CI_Model
	{
		/**
		 * Constructor
		 *
		 * Initialises the model and laods the database connection.
		 */
		public function __construct()
		{
			parent::__construct();
			$this->load->database();
		}
		
		/**
		 * Gets user status from the database
		 *
		 * @param string	$user_email	The email of the user to get the status of
		 */
		public function get_user_status($user_email)
		{
			// generate query and run it
			$query = $this->db->select('user_status')
							  ->from('user')
							  ->where('email', $user_email)
							  ->get();
			
			// check that a result came back (there can be only one)
			if($query->num_rows() > 0)
			{
				$user = $query->row();
				return $user->user_status;
			}
			
			return FALSE;
		}
		
		/**
		 * Checks that a users sign in credentials are valid
		 *
		 * @param string	$user_email		The user email to check for
		 * @param string	$password_hash	The hash of the password to check
		 * @return boolean	TRUE if valid, FALSE if not
		 */
		public function valid_sign_in($user_email, $password_hash)
		{
			$query = $this->db->select('password')
							  ->from('user')
							  ->where('email', $user_email)
							  ->get();
			if($query->num_rows() > 0)
			{
				$user = $query->row();
				return ($password_hash === $user->password);
			}
			
			return FALSE;
		}
		
		/**
		 * Inserts a new user into the database
		 *
		 * @param	assoc_array	$data	The user data to insert
		 * @return	boolean	TRUE on success
		 */
		public function insert($data)
		{
			return ($this->db->insert('user', $data)) ? TRUE : FALSE;
		}
		
		/**
		 * Updates a users stored data
		 *
		 * @param	string		$user_email	The email address of the user to update
		 * @param	assoc_array	$data		The new user data
		 * @return	boolean	TRUE on success
		 */
		public function update($user_email, $data)
		{
			return ($this->db->update('user', $data, array('user_email' => $user_email))) ? TRUE : FALSE;
		}
		
		/**
		 * Deletes a user
		 *
		 * @param	string	$user_email	The email address of the user to delete
		 * @return	boolean	TRUE on success
		 */
		public function delete($user_email)
		{
			return ($this->db->delete('user', array('user_email' => $user_email))) ? TRUE : FALSE;
		}
	}
	
/* End of file user_model.php */
/* Location: application/models/sandcastle/user_model.php */