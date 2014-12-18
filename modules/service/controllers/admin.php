<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Partner page administration
 * 
 * @author 		Kamal Lamichhane
 * @website		http://lkamal.com.np
 * @package 	access-himalaya.com
 * @subpackage 	Booking
 */
class Admin extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'Clients';

    protected $data;

    public function __construct()
    {
        parent::__construct();
		
		$this->data = new stdClass();
		
        $this->lang->load('service');

        $this->load->driver('Streams');
    }

    // --------------------------------------------------------------------------

    /**
     * List all FAQs using Streams CP Driver
     *
     * In this alternate index, we are using the
     * Streams API driver to 
     *
     * @access	public
     * @return	void
     */
    public function index()
    {
        $extra['title'] = lang('service:service');
        $extra['sorting'] = true;
        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => 'admin/service/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/service/delete/-entry_id-',
                'confirm' => true
            )
        );
		// $extra['columns'] = array('name', 'description', 'icon');

        $this->streams->cp->entries_table('service', 'service', 20, 'admin/service/index', true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * List all FAQs using Streams Entries Driver and building a custom template
     *
     * We are using the Streams API to grab
     * data from the service database. It handles
     * pagination as well.
     *
     * @access  public
     * @return  void
     */
    public function _index()
    {
        // The get_entries function in the
        // entries Streams API drivers grabs
        // entries from a stream.
        // The only really required parameters are
        // stream and namespace.

        $params = array(
            'stream' => 'service',
            'namespace' => 'service',
            'paginate' => 'yes',
            'page_segment' => 3
        );

        $this->data->service = $this->streams->entries->get_entries($params);
            
        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('admin/index', $this->data);
    }

    // --------------------------------------------------------------------------

    /**
     * Create a new FAQ entry
     *
     * This uses the Streams API CP driver which
     * is designed to handle repetitive CP tasks
     * down to even loading the page.
     *
     * Certain things can be overridden, but this
     * is an example using almost all default values.
     *
     * @access	public
     * @return	void
     */
    public function create()
    {
        $extra = array(
            'return' => 'admin/service',
            'success_message' => lang('service:submit_success'),
            'failure_message' => lang('service:submit_failure'),
            'title' => 'New Service'
        );

        $this->streams->cp->entry_form('service', 'service', 'new', null, true, $extra);
    }

    // --------------------------------------------------------------------------
    
    /**
     * Edit a FAQ entry
     *
     * This uses the Streams API CP driver which
     * is designed to handle repetitive CP tasks
     * down to even loading the page.
     *
     * Certain things can be overridden, but this
     * is an example using almost all default values.
     *
     * @access	public
     * @return	void
     */
    public function edit($id = 0)
    {
        $extra = array(
            'return' => 'admin/service',
            'success_message' => lang('service:edit_success'),
            'failure_message' => lang('service:edit_failure'),
            'title' => 'Edit Service'
        );

        $this->streams->cp->entry_form('service', 'service', 'edit', $id, true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * Delete a FAQ entry
     * 
     * This uses the Streams API Entries driver which is
     * designed to work with entries within a Stream.
     * 
     * @access  public
     * @param   int $id The id of FAQ to be deleted
     * @return  void
     * @todo    This function does not currently hava any error checking.
     */
    public function delete($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'service', 'service');
        $this->session->set_flashdata('error', lang('service:deleted'));
        redirect('admin/service');
    }

}
/* End of file admin.php */
