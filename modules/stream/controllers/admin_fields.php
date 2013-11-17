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

        $this->lang->load('stream');
		$this->load->driver('streams');

		// If they cannot administer profile fields,
		// then they can't access anythere here.
		//role_or_die('stream', 'admin_profile_fields');
	}

	// --------------------------------------------------------------------------
	
	/**
	 * List out profile fields
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function index($stream_slug = NULL, $namespace = NULL)
	{
		if( is_null($stream_slug) || is_null($namespace))
		{
			redirect('admin/stream');
		}
		$buttons = array(
			array(
				'url'		=> 'admin/stream/fields/edit/-assign_id-/'.$stream_slug.'/'.$namespace, 
				'label'		=> $this->lang->line('global:edit')
			),
			array(
				'url'		=> 'admin/stream/fields/delete/-assign_id-/'.$stream_slug.'/'.$namespace,
				'label'		=> $this->lang->line('global:delete'),
				'confirm'	=> true
			)
		);

		$this->template->title(lang('user:profile_fields_label'));

		$this->streams->cp->assignments_table(
								$stream_slug,
								$namespace,
								Settings::get('records_per_page'),
								'admin/stream/fields/index/'.$stream_slug.'/'.$namespace,
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
	public function create($stream_slug = NULL, $namespace = NULL)
	{
		if( is_null($stream_slug) || is_null($namespace))
		{
			redirect('admin/stream');
		}
		$extra['title'] 		= lang('streams:new_field');
		$extra['show_cancel'] 	= true;
		$extra['cancel_uri'] 	= 'admin/stream/fields/index/'.$stream_slug.'/'.$namespace;

		$this->streams->cp->field_form($stream_slug, $namespace, 'new', 'admin/stream/fields/index/'.$stream_slug.'/'.$namespace, null, array(), true, $extra);
	}

	// --------------------------------------------------------------------------

	/**
	 * Delete a profile field
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function delete($assign_id= 0, $stream_slug = NULL, $namespace = NULL)
	{
		if( is_null($stream_slug) || is_null($namespace))
		{
			redirect('admin/stream');
		}
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
	
		redirect('admin/stream/fields/index/'.$stream_slug.'/'.$namespace);
	}

	// --------------------------------------------------------------------------

	/**
	 * Edit a profile field
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function edit($assign_id = 0, $stream_slug = NULL, $namespace = NULL)
	{
		if( is_null($stream_slug) || is_null($namespace))
		{
			redirect('admin/stream');
		}
		if ( ! $assign_id = $this->uri->segment(5))
		{
			show_error(lang('streams:cannot_find_assign'));
		}

		$extra['title'] 		= lang('streams:edit_field');
		$extra['show_cancel'] 	= true;
		$extra['cancel_uri'] 	= 'admin/stream/fields/index/'.$stream_slug.'/'.$namespace;

		$this->streams->cp->field_form($stream_slug, $namespace, 'edit', 'admin/stream/fields/index/'.$stream_slug.'/'.$namespace, $assign_id, array(), true, $extra);
	}
}