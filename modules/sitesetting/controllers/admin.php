<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Site settings Module
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Prduct Module
 */
class Admin extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'sitesetting';
    
    protected $data;

    public function __construct()
    {
        parent::__construct();
        $this->data = new stdClass();
        
        $this->load->driver('Streams');
    }
    
    public function index()
    {
        redirect('admin/streams');
    }
}
