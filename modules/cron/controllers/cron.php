<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a cron module to handle scheduled tasks
 *
 * @author        Ryan Thompson - AI Web Systems, Inc.
 * @website       http://aiwebsystems.com
 * @package 			CMS
 * @subpackage 		Cron Module
 */
class Cron extends Public_Controller
{

	public function __construct()
	{
		parent::__construct();

		// Load the required classes
		$this->lang->load('cron');
		$this->config->load('cron_c');
		$this->load->library('curl');
	}

	// CRON calling...
	public function index($hash = '')
	{
		if($hash == $this->config->item('hash') && SITE_REF == $this->config->item('master'))
		{
			// I work ooouut.. 
			$time 				= time();
			
			// Piece apart the time
			$minute	 			= date('i', $time);
			$hour		 			= date('H', $time);
			$date 				= date('d', $time); // 01 - 31
			$day_of_week	= date('w', $time); // 0 = Sunday
			
			// Process 1?
			if($this->config->item('allow_process_1')) $intervals[]='1';
			
			// 5 Minutes
			(substr($minute, -1)=='0'||substr($minute, -1)=='5'?$intervals[]='5':NULL);
			
			// 10 Minutes
			(substr($minute, -1)=='0'?$intervals[]='10':NULL);
			
			// 15 Minutes
			($minute=='00'||$minute=='15'||$minute=='30'||$minute=='45'?$intervals[]='15':NULL);
			
			// 30 Minutes
			($minute=='00'||$minute=='30'?$intervals[]='30':NULL);
			
			// 60 Minutes
			($minute=='00'?$intervals[]='60':NULL);
			
			// 1 Day (86400)
			($hour=='00'&&$minute=='00'?$intervals[]='86400':NULL);
			
			// Weekly (sunday - saturday = 0 - 1)2222
			($hour=='12'&&$minute=='00'&&$day_of_week=='0'?$intervals[]='sunday':NULL);
			($hour=='12'&&$minute=='00'&&$day_of_week=='1'?$intervals[]='monday':NULL);
			($hour=='12'&&$minute=='00'&&$day_of_week=='2'?$intervals[]='tuesday':NULL);
			($hour=='12'&&$minute=='00'&&$day_of_week=='3'?$intervals[]='wednesday':NULL);
			($hour=='12'&&$minute=='00'&&$day_of_week=='4'?$intervals[]='thursday':NULL);
			($hour=='12'&&$minute=='00'&&$day_of_week=='5'?$intervals[]='friday':NULL);
			($hour=='12'&&$minute=='00'&&$day_of_week=='6'?$intervals[]='saturday':NULL);
			
			// Monthly
			($hour=='12'&&$minute=='00'&&$date=='01'?$intervals[]='monthly':NULL);
			
			// Make the intervals into a string
			$interval_string = implode('-', $intervals);
			
			/*
			 * 	Now we get all the valid sites to process cron for
			 */
			$query = "SELECT domain, ref FROM core_sites WHERE active = 1";
			
			// Cycle em and test who has CRON
			foreach($this->db->query($query)->result() as $row)
			{
				// Query to get CRON enabled sites
				$query 		= "SELECT id FROM ".$row->ref."_modules WHERE slug = 'cron' AND installed = 1";
				$result 	= $this->db->query($query);
				
				if($result->num_rows() != 0)
				{
					// Make the calls
					$this->curl->simple_get("http://".$row->domain."/cron/process/".$hash."/".$interval_string);
				}
			}
		}
	}
	
	function process($hash, $interval_string = FALSE)
	{
		if($hash == $this->config->item('hash'))
		{
			if($interval_string !== FALSE)
			{
				// Make array
				$intervals = explode('-', $interval_string);
				
				// Trigger a login event for each passed interval
				foreach($intervals as $interval)
				{
					Events::trigger('cron_process_'.$interval);
				}
				
			}
		}
	}
	
	function test()
	{
		//Events::trigger('cron_process_test');
	}
	
}