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
	 * Planet Model
	 * 
	 * Provides the needed functions to make running a basic planet a piece of
	 * cake. More specifically it deals with the database side of things.
	 *
	 * When talking about the "database result object" this refers to the
	 * CodeIgniter database result object, i.e. `$query->result();`
	 *
	 * @package 	SandCastle
	 * @subpackage 	Models
	 * @category 	Planet
	 * @author 		William Duyck <wduyck@gmail.com>
	 * @copyright 	Copyleft 2012, William Duyck
	 * @license 	https://www.mozilla.org/MPL/2.0/ MPL v2.0
	 * @link 		http://www.wduyck.com/ wduyck.com
	 *
	 * @todo Planet Features
	 * 
	 */
	class Planet_model extends Model
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
		 * Add feed
		 *
		 * Adds a feed url to the database
		 *
		 * @param	string	$userEmail	the user to associate the feed with's email
		 * @param	string	$feedURL	the url of the feed to add
		 * @return	boolean	TRUE on succes
		 */
		public function add_feed($userEmail, $feedURL)
		{
			return ($this->db->insert('feed', array(
				'userEmail' => $userEmail,
				'feedURL' 	=> $feedURL
			))) ? TRUE : FALSE;
		}
		
		/**
		 * Delete feed
		 *
		 * Removes a feed from the database
		 *
		 * @param	string	$feedURL the url of the feed to delete
		 * @return	boolean	TRUE on success
		 */
		public function delete_feed($feedURL)
		{
			return ($this->db->delete('feed', array(
				'feedURL' => $feedURL
			))) ? TRUE : FALSE;
		}
		
		/**
		 * Get feed
		 *
		 * Returns an individual feed from the database
		 *
		 * @param	string	$feedURL	the url of the feed to get
		 * @return	mixed	database result object on success, FALSE on fail
		 */
		public function get_feed($feedURL)
		{
			$feed = $this->db->get_where('feed', array(
				'feedURL' => $feedURL
			));
			
			return ($feed->num_rows() === 1) ? $feed->result() : FALSE;
		}
		
		/**
		 * Get feeds
		 *
		 * Returns multiple feeds details (owner, url) in the database, defaults
		 * to all
		 *
		 * Usage:
		 * 		// all feeds
		 * 		$feeds = $this->planet_model->get_feeds();
		 *
		 * 		// selected feeds
		 *		$feeds = $this->planet_model->get_feeds(array(
		 *			'http://www.example.com/feed.rss',
		 * 			'http://www.anotherexample.com/feed.rss'
		 *		));
		 *
		 * @param	array	$feeds	an array of feeds to get
		 * @return	mixed	database result object on success, FALSE on fail
		 */
		public function get_feeds($feeds = null)
		{
			// specific feeds requested
			if(is_array($feeds))
			{
				$this->db->where('feedURL', $feeds[0]);
				for($i = 1, $j = count($feeds); $i < $j; $i++)
				{
					$this->db->or_where('feedURL', $feeds[$i]);
				}
				$feeds = $this->db->get('feed');
				return ($feeds->num_rows() > 0) ? $feeds->result() : FALSE;
			}
			
			// all feeds
			$feeds = $this->db->get('feed');
			return ($feeds->num_rows() > 0) ? $feeds->result() : FALSE;
		}
	}
	
/* End of file planet.php */
/* Location: application/models/planet_model.php */