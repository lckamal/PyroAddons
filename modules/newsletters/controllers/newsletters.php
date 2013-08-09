<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a Newsletter module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Newsletter
 */
class Newsletters extends Public_Controller
{
    protected $data;
	function __construct()
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
	
	function index()
	{
		$this->data->groups = $this->newsletters->groups();
        if($this->input->post('btnSubscribe')){
            $this->load->library('form_validation');
            $this->form_validation->set_rules($this->recipient_rules);
            $this->form_validation->set_message('required', '%s is required.');
            if($this->form_validation->run())
            {
                $input = $this->input->post();
                $input['active'] = 1;
                unset($input['btnSubscribe']);
                if($this->recipients->create($input))
                {
                    $this->session->set_flashdata('success', 'Successfully subscribed to our newsletters.');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Sorry! Please try again.');
                }
                redirect(site_url('newsletters'));
            }
            
            // foreach ($this->recipient_rules AS $rule)
            // {
                // $rule['field'] = $this->input->post($rule['field']);
            // }
        }
        $this->template->build('subscribe_form', $this->data);
	}
	
	function archive($id = '')
	{
		$this->data->newsletter = $this->newsletters_m->getNewsletter($id);
		if ($this->data->newsletter)
		{
			$this->template->build('view', $this->data);
		}
		else
		{
			show_404();
		}
	}
	
	// Public: Register for newsletter
	function subscribe()
	{
		$this->data->groups = $this->newsletters->groups();
        
        if($this->input->post('btnSubscribe')){
            $this->load->library('form_validation');
            $this->form_validation->set_rules($this->recipient_rules);
            $this->form_validation->set_message('required', '%s is required.');
            if($this->form_validation->run())
            {
                $input = $this->input->post();
                $input['active'] = 1;
                unset($input['btnSubscribe']);
                if($this->recipients->create($input))
                {
                    $this->session->set_flashdata('success', 'Successfully subscribed to our newsletters.');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Sorry! Please try again.');
                }
                redirect('newsletters/subscribe');
            }
            
            // foreach ($this->recipient_rules AS $rule)
            // {
                // $rule['field'] = $this->input->post($rule['field']);
            // }
        }
        $this->template->build('subscribe_form', $this->data);
	}
	
	// Public: Register for newsletter
	function subscribed()
	{
		$this->template->build('subscribed', $this->data);
	}
	
	// Public: Unsubscribe from newsletter
	function unsubscribe($email = '')
	{
		if (!$email) redirect('');
		
		if ($this->newsletters_m->unsubscribe($email))
		{
			$this->session->set_flashdata(array('success'=> $this->lang->line('letter_delete_mail_success')));
			redirect('');
			return;
		}
		else
		{
			$this->session->set_flashdata(array('notice'=> $this->lang->line('letter_delete_mail_error')));
			redirect('');
			return;
		}
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
?>