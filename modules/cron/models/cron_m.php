<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a cron module
 *
 * @author        Ryan Thompson - AI Web Systems, Inc.
 * @website       http://aiwebsystems.com
 * @package 			CMS
 * @subpackage 		Cron Module
 */
class Cron_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
		
		/**
		 * If the cron module's table was named "crons"
		 * then MY_Model would find it automatically. Since
		 * I named it "cron" then we just set the name here.
		 */
		$this->_table = 'cron';
	}
	
}
