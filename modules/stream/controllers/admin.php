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
    protected $section = 'stream';

    protected $data;

    public function __construct()
    {
        parent::__construct();
		
		$this->data = new stdClass();
		
        $this->lang->load('stream');

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
    public function _index()
    {
        $extra['title'] = lang('stream:streams');
        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => 'admin/stream/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/stream/delete/-entry_id-',
                'confirm' => true
            )
        );

        $this->streams->cp->entries_table('data_streams', 'stream', 100, 'admin/stream/index', true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * List all FAQs using Streams Entries Driver and building a custom template
     *
     * We are using the Streams API to grab
     * data from the streams database. It handles
     * pagination as well.
     *
     * @access  public
     * @return  void
     */
    public function index()
    {
        // The get_entries function in the
        // entries Streams API drivers grabs
        // entries from a stream.
        // The only really required parameters are
        // stream and namespace.

        $params = array(
            'stream' => 'data_streams',
            'namespace' => 'stream',
            'paginate' => 'no',
            'page_segment' => 3
        );

        $this->data->streams = $this->streams->entries->get_entries($params);

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
            'return' => 'admin/stream',
            'success_message' => lang('stream:submit_success'),
            'failure_message' => lang('stream:submit_failure'),
            'title' => lang('stream:new')
        );

        $this->streams->cp->entry_form('data_streams', 'stream', 'new', null, true, $extra);
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
            'return' => 'admin/stream',
            'success_message' => lang('stream:submit_success'),
            'failure_message' => lang('stream:submit_failure'),
            'title' => lang('stream:edit')
        );

        $this->streams->cp->entry_form('data_streams', 'stream', 'edit', $id, true, $extra);
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
        $this->streams->entries->delete_entry($id, 'streams', 'stream');
        $this->session->set_flashdata('error', lang('stream:deleted'));
        redirect('admin/stream');
    }

}
/* End of file admin.php */
