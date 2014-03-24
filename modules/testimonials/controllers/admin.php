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

    public $data;

    public function __construct()
    {
        parent::__construct();

        $this->data = new stdClass();
        $this->lang->load('testimonials');

        $this->load->driver('Streams');
    }

    // --------------------------------------------------------------------------

    /**
     * Alternat list all testimonials
     *
     * In this alternate index, we are using the
     * Streams API driver to 
     *
     * @access	public
     * @return	void
     */
    public function index()
    {
        $this->load->helper('text');
        $extra['title'] = 'testimonials';
        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => 'admin/testimonials/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/testimonials/delete/-entry_id-',
                'confirm' => true
            )
        );

        $this->streams->cp->entries_table('testimonials', 'testimonials', 25, 'admin/testimonials/index', true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * Create a new testimonials entry
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
        $this->template->title(lang('testimonials:new'));

        $extra = array(
            'return' => 'admin/testimonials',
            'success_message' => lang('testimonials:submit_success'),
            'failure_message' => lang('testimonials:submit_failure'),
            'title' => lang('testimonials:new')
        );

        $this->streams->cp->entry_form('testimonials', 'testimonials', 'new', null, true, $extra);
    }

    // --------------------------------------------------------------------------
    
    /**
     * Edit a testimonials entry
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
        $this->template->title(lang('testimonials:edit'));

        $extra = array(
            'return' => 'admin/testimonials',
            'success_message' => lang('testimonials:submit_success'),
            'failure_message' => lang('testimonials:submit_failure'),
            'title' => lang('testimonials:edit')
        );

        $this->streams->cp->entry_form('testimonials', 'testimonials', 'edit', $id, true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * Delete a testimonials entry
     * 
     * This uses the Streams API Entries driver which is
     * designed to work with entires within a Stream.
     * 
     * @access  public
     * @param   int $id The id of testimonials to be deleted
     * @return  void
     * @todo    This function does not currently hava any error checking.
     */
    public function delete($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'testimonials', 'testimonials');
        $this->session->set_flashdata('error', lang('testimonials:deleted'));
        redirect('admin/testimonials/');
    }

}
/* End of file admin.php */
