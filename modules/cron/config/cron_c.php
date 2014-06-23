<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	$config['allow_process_1']        = false; // true or false if you want to allow processing every minute
	$config['hash']                   = 'iamfromaccess-himalay.com';	// A good rand_hash here
	$config['master']		              = 'default';	// The site_ref for the primary site. No other sites can execute cron management.
	
?>