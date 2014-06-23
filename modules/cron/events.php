<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Cron Events Class
 * 
 * @package				CMS
 * @subpackage    Cron Module
 * @category    	Events
 * @author        Ryan Thompson - AI Web Systems, Inc.
 * @website       http://aiwebsystems.com
 */
class Events_Cron {

	protected $ci;
	
	public function __construct()
	{
		$this->ci =& get_instance();
		
		// Register the events
		Events::register('cron_process_1', array($this, 'process_1'));
		Events::register('cron_process_5', array($this, 'process_5'));
		Events::register('cron_process_10', array($this, 'process_10'));
		Events::register('cron_process_15', array($this, 'process_15'));
		Events::register('cron_process_30', array($this, 'process_30'));
		Events::register('cron_process_60', array($this, 'process_60'));
		Events::register('cron_process_86400', array($this, 'process_86400'));	// 1 day
		
		// Weekly days
		Events::register('cron_process_sunday', array($this, 'process_sunday'));
		Events::register('cron_process_monday', array($this, 'process_monday'));
		Events::register('cron_process_tuesday', array($this, 'process_tuesday'));
		Events::register('cron_process_wednesday', array($this, 'process_wednesday'));
		Events::register('cron_process_thursday', array($this, 'process_thursday'));
		Events::register('cron_process_friday', array($this, 'process_friday'));
		Events::register('cron_process_saturday', array($this, 'process_saturday'));
		
		// Monthly
		Events::register('cron_process_monthly', array($this, 'process_monthly'));
	}
	   
}
/* End of file events.php */