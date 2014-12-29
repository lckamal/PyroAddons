<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Choice extends Public_Controller
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
        $this->lang->load('choice');
        $this->load->driver('Streams');
    }
     /**
     * List all Choices
     *
     * @access	public
     * @return	void
     */
    public function index()
    {
        $params = array(
            'stream' => 'choices',
            'namespace' => 'choice',
            'paginate' => 'yes',
            'pag_segment' => 4
        );

        $this->data->choices = $this->streams->entries->get_entries($params);

        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('index', $this->data);
    }

}

/* End of file choice.php */
