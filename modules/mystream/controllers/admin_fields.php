<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin User Fields
 *
 * Manage custom user fields.
 *
 * @author 		PyroCMS Dev Team
 * @package 	PyroCMS\Core\Modules\Users\Controllers
 */
class Admin_fields extends Admin_Controller {

	protected $section = 'fields';

	// --------------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

        $this->lang->load('mystream');
		$this->load->driver('streams');
	}

	// --------------------------------------------------------------------------
	
	/**
	 * List out profile fields
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function index($stream_id = 0)
	{
		$mystream = $this->streams->entries->get_entry($stream_id, 'data_streams', 'mystream');
		if(!$mystream){
			redirect('admin/mystream');
		}
		$stream_slug = $mystream->stream_slug;
		$namespace = $mystream->stream_namespace;
		$buttons = array(
			array(
				'url'		=> 'admin/mystream/fields/edit/-assign_id-/'.$stream_id, 
				'label'		=> $this->lang->line('global:edit')
			),
			array(
				'url'		=> 'admin/mystream/fields/delete/-assign_id-/'.$stream_id,
				'label'		=> $this->lang->line('global:delete'),
				'confirm'	=> true
			)
		);

		$this->template->title(lang('user:profile_fields_label'));

		$this->streams->cp->assignments_table(
								$stream_slug,
								$namespace,
								Settings::get('records_per_page'),
								'admin/mystream/fields/index/'.$stream_slug.'/'.$namespace,
								true,
								array('buttons' => $buttons),
								array('first_name', 'last_name'));
	}

	// --------------------------------------------------------------------------

	/**
	 * Create a new profile field
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function create($stream_id = 0)
	{
		$mystream = $this->streams->entries->get_entry($stream_id, 'data_streams', 'mystream');
		if(!$mystream){
			redirect('admin/mystream');
		}
		$stream_slug = $mystream->stream_slug;
		$namespace = $mystream->stream_namespace;
		$extra['title'] 		= lang('streams:new_field');
		$extra['show_cancel'] 	= true;
		$extra['cancel_uri'] 	= 'admin/mystream/fields/index/'.$stream_id;

		$this->streams->cp->field_form($stream_slug, $namespace, 'new', 'admin/mystream/fields/index/'.$stream_id, null, array(), true, $extra);
	}

	// --------------------------------------------------------------------------

	/**
	 * Delete a profile field
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function delete($assign_id= 0, $stream_id)
	{
		$mystream = $this->streams->entries->get_entry($stream_id, 'data_streams', 'mystream');
		if(!$mystream){
			redirect('admin/mystream');
		}
		$stream_slug = $mystream->stream_slug;
		$namespace = $mystream->stream_namespace;

		if ( ! $assign_id = $this->uri->segment(5))
		{
			show_error(lang('streams:cannot_find_assign'));
		}
	
		// Tear down the assignment
		if ( ! $this->streams->cp->teardown_assignment_field($assign_id))
		{
		    $this->session->set_flashdata('notice', lang('user:profile_delete_failure'));
		}
		else
		{
		    $this->session->set_flashdata('success', lang('user:profile_delete_success'));			
		}
	
		redirect('admin/mystream/fields/index/'.$stream_id);
	}

	// --------------------------------------------------------------------------

	/**
	 * Edit a profile field
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function edit($assign_id = 0, $stream_id)
	{
		$mystream = $this->streams->entries->get_entry($stream_id, 'data_streams', 'mystream');
		if(!$mystream){
			redirect('admin/mystream');
		}
		$stream_slug = $mystream->stream_slug;
		$namespace = $mystream->stream_namespace;
		if ( ! $assign_id = $this->uri->segment(5))
		{
			show_error(lang('streams:cannot_find_assign'));
		}

		$extra['title'] 		= lang('streams:edit_field');
		$extra['show_cancel'] 	= true;
		$extra['cancel_uri'] 	= 'admin/mystream/fields/index/'.$stream_id;

		$this->streams->cp->field_form($stream_slug, $namespace, 'edit', 'admin/mystream/fields/index/'.$stream_id, $assign_id, array(), true, $extra);
	}
}