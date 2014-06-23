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
class Admin_categories extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'categories';

    protected $data;

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('slider');

        $this->load->driver('Streams');
    }

    /**
     * List all Sliders using Streams CP Driver
     *
     * In this alternate index, we are using the
     * Streams API driver to 
     *
     * @access	public
     * @return	void
     */
    public function index()
    {
        $extra['title'] = lang('slider:categories');
        
        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => 'admin/slider/categories/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/slider/categories/delete/-entry_id-',
                'confirm' => true
            )
        );

        $this->streams->cp->entries_table('categories', 'slider', 3, 'admin/slider/categories/index', true, $extra);
    }

    public function create()
    {
		$extra['title'] = 'lang:slider:new';

        $extra = array(
            'return' => 'admin/slider/categories/index',
            'success_message' => lang('slider:submit_success'),
            'failure_message' => lang('slider:submit_failure'),
            'title' => lang('slider:categories:new')
        );

        $this->streams->cp->entry_form('categories', 'slider', 'new', null, true, $extra);
    }

   /**
     * Edit a Slider categories
     *
     * @access	public
     * @return	void
     */
    public function edit($id = 0)
    {
        $this->template->title(lang('slider:edit'));

        $extra = array(
            'return' => 'admin/slider/categories/index',
            'success_message' => lang('slider:submit_success'),
            'failure_message' => lang('slider:submit_failure'),
            'title' => lang('slider:edit')
        );

        $this->streams->cp->entry_form('categories', 'slider', 'edit', $id, true, $extra);
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
        $this->streams->entries->delete_entry($id, 'categories', 'slider');
        $this->session->set_flashdata('error', lang('slider:deleted'));
        redirect('admin/slider/categories/index');
    }

}