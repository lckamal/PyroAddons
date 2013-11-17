<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Partner module
 * 
 * @author 		Kamal Lamichhane
 * @website		http://lkamal.com.np
 * @package 	access-himalaya.com
 * @subpackage 	Booking
 */
class Partner extends Public_Controller
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
        $this->lang->load('partner');
        $this->load->driver('Streams');
        $this->template->append_css('module::partner.css');
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
            'stream' => 'partners',
            'namespace' => 'partner',
            'paginate' => 'yes',
            'pag_segment' => 4
        );

        $this->data->partners = $this->streams->entries->get_entries($params);

        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('index', $this->data);
    }

}

/* End of file partner.php */
