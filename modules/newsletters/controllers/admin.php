<?php if(!defined('BASEPATH'))exit('No direct script access allowed'); 
/**
 * This is a Newsletter module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Newsletter
 */
class Admin extends Admin_Controller {

    private $data;
    protected $section = 'dashboard';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('newsletter_m','newsletters');
		$this->lang->load('newsletters');
        $this->data = new stdClass();
	}
	
	function index()
	{
		$this->template
		->build('admin/dashboard',$this->data);
	}

}