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
	 * Event Model
	 * 
	 * Provides the needed functions to make storing and listing upcoming events
	 * easy as 3.141526... More specifically it deals with the database side of
	 * things.
	 *
	 * When talking about the "database result object" this refers to the
	 * CodeIgniter database result object, i.e. `$query->result();`
	 *
	 * @package 	SandCastle
	 * @subpackage 	Models
	 * @category 	Event
	 * @author 		William Duyck <wduyck@gmail.com>
	 * @copyright 	Copyleft 2012, William Duyck
	 * @license 	https://www.mozilla.org/MPL/2.0/ MPL v2.0
	 * @link 		http://www.wduyck.com/ wduyck.com
	 *
	 * @todo Event Features
	 * 
	 */
	class Event_model extends Model
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
		 * Add Event
		 *
		 * Adds an event to the database
		 *
		 * @param	string	$url		the url either the event wiki page or website.
		 * @param	string	$name		name of the event
		 * @param	string	$desc		a description of the event to be displayed.
		 * @param	int		$sdate		the start date for the event.
		 * @param	int		$fdate		the finish date for the event.
		 * @param	mixed	$tags		either a string for one tag, or assoc_array for multiple.
		 * @param	string	$tag_desc	if prev' is string this is used to 
		 * @return	boolean	TRUE on success
		 */
		public function add_event($url, $desc, $sdate, $fdate = NULL, $tags = NULL, $tag_desc = NULL)
		{
			// deal with bits to go into the `event` table.
			if(($this->db->insert('event', array(
				'eventURL'	=> $url,
				'eventDesc'	=> $desc,
				'sdate'		=> $sdate,
				'fdate'		=> $fdate
			))) ? TRUE : FALSE)
			{
				// what to return (no errors so far)
				$rtn = TRUE;
				// id for the last event to be added to the database
				$event_id = $this->db->insert_id();
				
				// deal with adding the tags
				if(($tags !== NULL) && is_array($tags))
				{
					// we want to reuse $desc now so unset it to avoid bad things
					unset($desc);
					
					// loop through all tags and add them to the database
					foreach($tags as $tag => $desc)
					{
						$desc = (isset($desc)) ? $desc : NULL;
						// if a tag couldn't be added for some reason return FALSE
						$rtn = ($this->add_tag($tag, $desc)) ? $rtn : FALSE;
						// if a tag couldn't be linked for some reason return FALSE
						$rtn = ($this->tag_event($tag, $event_id)) ? $rtn : FALSE;
					}
					
					return $rtn;
				}
				elseif($tags !== NULL)
				{
					$rtn = $this->add_tag($tags, $tag_desc);
					$rtn = ($this->tag_event($tags, $event_id)) ? $rtn : FALSE;
				}
				
				return $rtn;
			}
			
			// if we make it to this line then something went wrong
			return FALSE;
		}
		
		/**
		 * Add Tag
		 *
		 * Adds a tag into the database. Tags must be lowercase (will be converted
		 * if not), and unique (a check will be done to avoid collisions).
		 *
		 * @param	string	$name	the name of the tag to be displayed (i.e. mozilla)
		 * @param	string	$desc	a short description of the tag
		 * @return	boolean	TRUE on success (will return TRUE even on colision as tag is still in the database)
		 */
		public function add_tag($name, $desc = NULL)
		{
			// force lowercase tag name
			$name = strtolower($name);
			
			// check if the tag already exists
			$check = $this->db->get_where('tag', array('tagName' => $name));
			if($check->num_rows() === 0)
			{
				// add the new tag in
				return ($this->db->insert('tag', array(
					'tagName' => $name,
					'tagDesc' => $desc
				))) ? TRUE : FALSE;
			}
			else
			{
				// tag in database already... retrun TRUE
				return TRUE;
			}
		}
		
		/**
		 * Tag Event
		 *
		 * Tags an event with a specified tag. Both the tag and event must exist
		 * in their respective database tables already.
		 *
		 * @param	mixed	$tag		string if single tag, array of strings if multiple
		 * @param	int		$eventID	the event to tag
		 */
		public function tag_event($tag, $event_id)
		{
			// recursively add multiple tags
			if(is_array($tag))
			{
				$rtn = TRUE;
				
				foreach($tag as $item)
				{
					$rtn = ($this->tag_event($item, $event_id)) ?  $rtn : FALSE;
				}
				
				return $rtn;
			}
			
			// make a link
			return ($this->db->insert('eventTag', array(
				'tagName' => $tag,
				'eventID' => $event_id
			))) ? TRUE : FALSE;
		}
		
		/**
		 * Get Event
		 *
		 * Gets a single event (and assoc' tags) based on the events ID and
		 * returns a database result object.
		 *
		 * @param	int		$event_id	id of the event to get
		 * @return	mixed	database result object on success, otherwise FALSE
		 */
		public function get_event($event_id)
		{
			// attempt to get the event and tags
			$query = $this->db->select('*')
							->from('event')
							->join('eventTag', 'event.eventID = eventTag.eventID', 'inner')
							->join('tag', 'tag.tagName = eventTag.tagName', 'inner')
							->where('eventID', $event_id);
			
			// check for a result and return
			return ($query->num_rows() === 1) ? $query->result() : FALSE;
		}
		
		/**
		 * Get Events Between
		 *
		 * Gets all events within a given date range (to be given as unix
		 * timestamps), defaults to all events from current time till end of the
		 * current month
		 *
		 * @param	int		$sdate	start of date range (defaults to current date)
		 * @param	int		$fdate	end of date range (defaults to end of current month)
		 * @return	mixed	database result object on success otherwise FASLE
		 */
		public function get_events_between($sdate = NULL, $fdate = NULL)
		{
			// set default timestamps if needed
			if($sdate === NULL)
			{
				$sdate = mktime(0, 0, 0, date('n'), date('j'));
			}
			if($fdate === NULL)
			{
				$fdate = mktime(0, 0, 0, date('n') + 1, 0);
			}
			
			// run the query (complex much!)
			$query = $this->db->select('*')
							->from('event')
							->where('sdate >=', $sdate)
							->where('fdate <=', $fdate);
			
			// check for results and return
			return ($query->num_rows() > 0) ? $query->result() : FALSE;
		}
		
		
	}
	
/* End of file event_model.php */
/* Location: application/models/event_model.php */