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
	 * Planet Controller
	 * 
	 * Provides the needed functions to make running a basic planet a piece of
	 * cake. This controller acts as an example of one way the Planet Library
	 * and Planet Model can be used together to produce a functioning planet.
	 *
	 * @package 	SandCastle
	 * @subpackage 	Controllers
	 * @category 	Planet
	 * @author 		William Duyck <wduyck@gmail.com>
	 * @copyright 	Copyleft 2012, William Duyck
	 * @license 	https://www.mozilla.org/MPL/2.0/ MPL v2.0
	 * @link 		http://www.wduyck.com/ wduyck.com
	 *
	 * @todo Planet Features
	 */
	class Page extends CI_Controller
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
			
			$this->load->library('sandcastle/planet');
			$this->load->model('planet_model');
		}
		
		/**
		 * Planet homepage
		 *
		 * Links the feeds in the database with those in cache/online and
		 * creates a page based on the collected data
		 */
		public function index()
		{
			$feeds = $this->get_all_feeds(FALSE);
		}
		
		/**
		 * Get all planet feeds
		 *
		 * gets all the planet feeds, updates the cache if needed (by virtue of
		 * how the planet lib works) and acts as a cron task if the paramater is
		 * FALSE
		 *
		 * @param	boolean	$rtn	TRUE by default, FALSE to act as cron task
		 * @return	mixed	feed object sorted by time or FALSE on total failure
		 */
		public function get_all_feeds($rtn = TRUE)
		{
			// detect if call is from a browser and redirect to index if so
			if(!$rtn && ENVIRONMENT !== 'development' && !$this->input->is_cli_request())
			{
				redirect('planet', 'location', 302);
			}
			
			// add all feeds into the array using the model to get the feeds
			// from the database
			$dbfeeds = $this->planet_model->get_feeds();
			if($dbfeeds)
			{
				// something to store the list of feeds from the database in.
				$feeds = array();
				
				foreach($dbfeeds as $feed)
				{
					array_push($feeds, $feed->feedURL);
				}
				
				// use the planet library to get the actual feeds and cache them
				// if needed
				$feeds = $this->planet->get_feed($feeds);
				if(ENVIRONMENT === 'development' && $rtn === FALSE)
				{
					echo '<pre>';
					print_r($feeds);
					echo '</pre>';
				}
				elseif($rtn === TRUE)
				{
					return $feeds;
				}
			}
			
			return FALSE;
		}
	}
	
/* End of file planet.php */
/* Location: application/libraries/planet.php */