<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Testimonials extends Public_Controller
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
        $this->lang->load('testimonials');
        $this->load->driver('Streams');
    }
     /**
     * List all TESTIMONIALSs
     *
     * We are using the Streams API to grab
     * data from the testimonialss database. It handles
     * pagination as well.
     *
     * @access	public
     * @return	void
     */
    public function index()
    {
        $params = array(
            'stream' => 'testimonials',
            'namespace' => 'testimonials',
            'paginate' => 'yes',
            'pag_segment' => 4
        );

        $this->data->testimonials = $this->streams->entries->get_entries($params);

        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('index', $this->data);
    }

}

/* End of file testimonials.php */
