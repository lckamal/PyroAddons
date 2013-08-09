<?php if(!defined('BASEPATH'))exit('No direct script access allowed'); 
/**
 * This is a Newsletter module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Newsletter
 */
class Admin_recipients extends Admin_Controller {

    private $data;
    protected $section = 'recipients';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('newsletter_m','newsletters');
        $this->load->model('recipient_m','recipients');
		$this->lang->load('newsletters');
        $this->data = new stdClass();
        
        $edit = ($this->uri->segment(4) == 'edit') ? '.id' : '';
        
        $this->recipient_rules = array(
               array(
                     'field'   => 'name',
                     'label'   => 'Name',
                     'rules'   => 'trim|required'
                  ),
            array(
                     'field'   => 'email',
                     'label'   => 'Email',
                     'rules'   => 'valid_email|trim|required|callback__check_email[newsletter_recipients.email'.$edit.']'
                  ),
            array(
                     'field'   => 'group[]',
                     'label'   => 'Group',
                     'rules'   => 'required|numeric'
                  )
            );
	}
	

	function index($offset=0)
	{
	    if( $this->input->post('btnAction') )
        {
            // Set action
            $action = $this->input->post('btnAction');

            // Loop IDs
            if ($this->input->post('action_to'))
            {
                foreach( $this->input->post('action_to') AS $id )
                {
                    // Perform action
                    $this->$action($id, FALSE);
                }
            }
        }

		$total_rows = $this->newsletters->count('recipients');
		$this->data->pagination = create_pagination('admin/newsletters/recipients/index/', $total_rows);		
		$this->data->recipients = $this->newsletters->recipients(false,false,$this->data->pagination['limit']);
		$this->template->build('admin/recipients',$this->data);
	}

	function create(){

		$this->data->groups = $this->newsletters->groups();
        
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->recipient_rules);
		if($this->form_validation->run())
        {
            $input = $this->input->post();
            $input['active'] = 1;
            if($this->recipients->create($input))
            {
                $this->session->set_flashdata('success', lang('newsletters_create_success'));
                redirect('admin/newsletters/recipients');
            }
            else
            {
                $this->session->set_flashdata('error', lang('newsletters_edit_error'));
                redirect('admin/newsletters/recipients/create');
            }
        }
        
        foreach ($this->recipient_rules AS $rule)
        {
            $rule['field'] = $this->input->post($rule['field']);
        }

		$this->template->build('admin/recipient_form',$this->data);
	}

	function edit($id = 0){
		
        $this->data->groups = $this->newsletters->groups();
		$this->data->recipient=$this->newsletters->recipients($id);

		$this->form_validation->set_rules($this->recipient_rules);
		if($this->form_validation->run())
        {
            $input = $this->input->post();
            $input['active'] = 1;
            if($this->recipients->update($id, $input))
            {
                $this->session->set_flashdata('success', lang('newsletters_edit_success'));
                redirect('admin/newsletters/recipients');
            }
            else
            {
                $this->session->set_flashdata('error', lang('newsletters_edit_error'));
                redirect('admin/newsletters/recipients/create');
            }
        }
        
		$this->template->build('admin/recipient_form',$this->data);
	}

    function delete($id = 0){
        if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
        {
            // pass the ids and let MY_Model delete the items
            $this->recipients->delete_many($this->input->post('action_to'));
        }
        elseif (is_numeric($id))
        {
            // they just clicked the link so we'll delete that one
            $this->recipients->delete($id);
        }
        redirect('admin/newsletters/recipients');
    }
    function batch_add_recipients()
    {
        $this->data->groups=$this->newsletters->groups();
        $this->template->build('admin/batch_add_recipients',$this->data);
    }
    
    function _check_email($str, $field){
        $edit_id = $this->uri->segment(5);
        if (substr_count($field, '.')==2)
        {
            list($table,$field,$id) = explode('.', $field);
            $query = $this->db->limit(1)->where($field,$str)->where($id.' != ',$edit_id)->get($table);
        }else {
         list($table, $field)=explode('.', $field);
            $query = $this->db->limit(1)->get_where($table, array($field => $str));
        }
        
        if($query->num_rows > 0){
            $this->form_validation->set_message('_check_email', 'The %s is already used.');
            return FALSE;
        }
        else{
            return TRUE;
        }
    }
	
}