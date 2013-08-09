<?php if(!defined('BASEPATH'))exit('No direct script access allowed'); 
/**
 * This is a Newsletter module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Newsletter
 */
class Admin_groups extends Admin_Controller {

    private $data;
    protected $section = 'groups';

		private $group_rules = array(
               array(
                     'field'   => 'group_name',
                     'label'   => 'Group Name',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'group_description',
                     'label'   => 'Group Description',
                     'rules'   => 'trim|required'
                  ),
				  array(
                     'field'   => 'group_public',
                     'label'   => 'Allow public signup',
                     'rules'   => 'trim|int|required'
                  )
            );
			

	public function __construct()
	{
		parent::__construct();
		$this->load->model('newsletter_m','newsletters');
		$this->load->model('group_m','groups');
		$this->lang->load('newsletters');
        $this->data = new stdClass();
	}
	

	function index($id=false)
	{
		if(!$id)
		{
			$this->data->groups=$this->newsletters->groups();//show all groups
		}
		else
		{
			$this->data->groups=$this->newsletters->groups($id);
			$this->data->recipients=$this->newsletters->recipients(false,$id);//show only recipients in this group
		}
		$this->template->build('admin/groups',$this->data);
	}
	
	function create(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->group_rules);
		if($this->form_validation->run())
		{
			// See if the model can create the record
			if($this->groups->create($this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('newsletters_add_group_success'));
				redirect('admin/newsletters/groups');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('newsletters_add_group_error'));
				redirect('admin/newsletters/groups/create');
			}
		}
		$this->template->build('admin/group_form',$this->data);
	}
	
	function edit($id = NULL){
		if($id) $this->data->groups=$this->newsletters->groups($id);
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->group_rules);
		
		// check if the form validation passed
		if($this->form_validation->run())
		{
			// get rid of the btnAction item that tells us which button was clicked.
			// If we don't unset it MY_Model will try to insert it
			unset($_POST['btnAction']);
			
			if($this->groups->update($id, $this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('newsletters_edit_group_success'));
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('newsletters_edit_group_error'));
			}
			
			redirect('admin/newsletters/groups');
		}
		$this->template->build('admin/group_form',$this->data);
	}


	function delete($id)
	{
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
			// pass the ids and let MY_Model delete the items
			$this->groups->delete_many($this->input->post('action_to'));
		}
		elseif (is_numeric($id))
		{
			// they just clicked the link so we'll delete that one
			$this->groups->delete($id);
		}
		$this->session->set_flashdata('error', lang('newsletters_delete_group_success'));
		redirect('admin/newsletters/groups');
	}

}