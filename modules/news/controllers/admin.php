<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Streams Sample Module
 *
 * This is a sample module for PyroCMS
 * that illustrates how to use the streams core API
 * for data management.
 *
 * @author 		Adam Fairholm - PyroCMS Dev Team
 * @website		http://pyrocms.com
 * @package 	PyroCMS
 * @subpackage 	Streams Sample Module
 */
class Admin extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'news';

    protected $data;

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('news');

        $this->load->driver('Streams');
    }

    // --------------------------------------------------------------------------

    /**
     * List all Newss using Streams CP Driver
     *
     * In this alternate index, we are using the
     * Streams API driver to 
     *
     * @access	public
     * @return	void
     */
    public function index()
    {
        $extra['title'] = lang('news:newss');
        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => 'admin/news/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/news/delete/-entry_id-',
                'confirm' => true
            )
        );

        $this->streams->cp->entries_table('newss', 'news', 25, 'admin/news/index', true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * List all Newss using Streams Entries Driver and building a custom template
     *
     * We are using the Streams API to grab
     * data from the newss database. It handles
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
            'stream' => 'newss',
            'namespace' => 'news',
            'paginate' => 'yes',
            'page_segment' => 4
        );

        $this->data->newss = $this->streams->entries->get_entries($params);

        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('admin/index', $this->data);
    }

    // --------------------------------------------------------------------------

    /**
     * Create a new News entry
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
            'return' => 'admin/news',
            'success_message' => lang('news:submit_success'),
            'failure_message' => lang('news:submit_failure'),
            'title' => 'lang:news:new'
        );

        $this->streams->cp->entry_form('newss', 'news', 'new', null, true, $extra);
    }

    // --------------------------------------------------------------------------
    
    /**
     * Edit a News entry
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
            'return' => 'admin/news',
            'success_message' => lang('news:submit_success'),
            'failure_message' => lang('news:submit_failure'),
            'title' => 'lang:news:edit'
        );

        $this->streams->cp->entry_form('newss', 'news', 'edit', $id, true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * Delete a News entry
     * 
     * This uses the Streams API Entries driver which is
     * designed to work with entries within a Stream.
     * 
     * @access  public
     * @param   int $id The id of News to be deleted
     * @return  void
     * @todo    This function does not currently hava any error checking.
     */
    public function delete($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'newss', 'news');
        $this->session->set_flashdata('error', lang('news:deleted'));
        redirect('admin/news/');
    }

}
/* End of file admin.php */
