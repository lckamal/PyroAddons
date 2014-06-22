<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Slider Module
 *
 * @author 		Kamal Lamichhane
 * @website		http://lkamal.com.np
 * @package 	PyroCMS
 * @subpackage 	Slider Module
 */
class Admin extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'slider';

    protected $data;

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('slider');

        $this->load->driver('Streams');
    }

    // --------------------------------------------------------------------------

    /**
     * List all Slider images
     *
     * @access	public
     * @return	void
     */
    public function index()
    {
        $extra['title'] = lang('slider:sliders');
        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => 'admin/slider/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/slider/delete/-entry_id-',
                'confirm' => true
            )
        );

        $this->streams->cp->entries_table('sliders', 'slider', 30, 'admin/slider/index', true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * List all Sliders images using Streams Entries Driver
     *
     * @access  public
     * @return  void
     */
    public function _index()
    {
        $params = array(
            'stream' => 'sliders',
            'namespace' => 'slider',
            'paginate' => 'yes',
            'page_segment' => 4
        );

        $this->data->sliders = $this->streams->entries->get_entries($params);

        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('admin/index', $this->data);
    }

    // --------------------------------------------------------------------------

    /**
     * Create a new Slider entry
     *
     * @access	public
     * @return	void
     */
    public function create()
    {
        $extra = array(
            'return' => 'admin/slider',
            'success_message' => lang('slider:submit_success'),
            'failure_message' => lang('slider:submit_failure'),
            'title' => 'lang:slider:new'
        );

        $this->streams->cp->entry_form('sliders', 'slider', 'new', null, true, $extra);
    }

    // --------------------------------------------------------------------------
    
    /**
     * Edit a Slider entry
     *
     * @access	public
     * @return	void
     */
    public function edit($id = 0)
    {
        $extra = array(
            'return' => 'admin/slider',
            'success_message' => lang('slider:submit_success'),
            'failure_message' => lang('slider:submit_failure'),
            'title' => 'lang:slider:edit'
        );

        $this->streams->cp->entry_form('sliders', 'slider', 'edit', $id, true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * Delete a Slider entry
     * 
     * This uses the Streams API Entries driver which is
     * designed to work with entries within a Stream.
     * 
     * @access  public
     * @param   int $id The id of Slider to be deleted
     * @return  void
     * @todo    This function does not currently hava any error checking.
     */
    public function delete($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'sliders', 'slider');
        $this->session->set_flashdata('error', lang('slider:deleted'));
        redirect('admin/slider/');
    }

}
/* End of file admin.php */
