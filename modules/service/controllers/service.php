<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Partner module
 * 
 * @author 		Kamal Lamichhane
 * @website		http://lkamal.com.np
 * @package 	access-himalaya.com
 * @subpackage 	Booking
 */
class Service extends Public_Controller
{
	public $data;
    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
		$this->data = new stdClass();
        $this->lang->load('service');
        $this->load->driver('Streams');
        $this->load->model('service_m');
        $this->template->set_layout('service.html');
    }
     /**
     * List all FAQs
     *
     * We are using the Streams API to grab
     * data from the partners database. It handles
     * pagination as well.
     *
     * @access	public
     * @return	void
     */


    public function index()
    {			

    	$params = array(
            'stream' => 'service',
            'namespace' => 'service'
        );               

        //$this->data->clients = $this->streams->entries->get_entries($params);  
        $this->data->services = $this->service_m->get_service();
        // Build the page

        $this->template->title($this->module_details['name'])
                ->build('view_service', $this->data);	
    }

    public function service_info($slug)
    {
        $this->data->service = $this->service_m->get_by('service_slug',$slug);
        //var_dump($this->data->service);exit();
        $this->template->title($this->data->service->name) 
        ->build('single_service.php',$this->data);
    }


}


