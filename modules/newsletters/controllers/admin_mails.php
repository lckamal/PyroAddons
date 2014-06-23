<?php if(!defined('BASEPATH'))exit('No direct script access allowed'); 
/**
 * This is a Newsletter module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Newsletter
 */
class Admin_mails extends Admin_Controller {

    private $data;
    protected $section = 'newsletters';
	// Validation rules to be used for create and edit
	
	private $newsletter_rules = array(
       array(
             'field'   => 'subject',
             'label'   => 'Subject',
             'rules'   => 'trim|required'
          ),
       array(
             'field'   => 'body',
             'label'   => 'Body',
             'rules'   => 'trim|required'
          )
    );


	public function __construct()
	{
		parent::__construct();
		$this->load->model('newsletter_m','newsletters');
		$this->lang->load('newsletters');
        $this->data = new stdClass();
	}
	

	//prevent duplicate emails
	function _check_email($str,$id)
	{
		$query = $this->db->get_where('newsletter_recipients', array('email'=>$str));
		if ($query->num_rows() == 0)
		{
			return true;
		}
		elseif($query->row()->id == $id and $query->num_rows() == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// Admin: Different actions
	function action($table)
	{
		switch($this->input->post('btnAction'))
		{
			case 'trash':
				$this->newsletters->delete($table,$this->input->post('action_to'));
			break;
			case 'restore':
				$this->newsletters->delete($table,$this->input->post('action_to'),true);
			break;
			case 'delete':
				$this->newsletters->delete($table,$this->input->post('action_to')) or die('wwww');
			break;
		}
		$table=='recipients' ? $redirect='recipients' : $redirect='';//get more specific
		redirect('admin/newsletters/'.$redirect);
	}


	function index($type='draft')
	{
		$this->data->type=$type;
		$this->data->newsletters=$this->newsletters->get_newsletters($type);
        
		$this->template
		->build('admin/mails',$this->data);
	}


	function preview($id)
	{
		foreach($this->newsletters->get_newsletters(false,$id) as $mail)
		{//move to views with template header/footer
			echo 'Subject: '.$mail->subject;
			echo '<a href="/admin/newsletters/send_mail/'.$id.'/preview">Send me a preview</a>';
			echo '<hr />';
			echo $mail->body;
		}
	}
	
	function create()
	{
        $this->load->model('templates/email_templates_m');
       $email_templates = $this->email_templates_m->where('is_default',0)->dropdown('id','name');
       
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->newsletter_rules);

        if($this->form_validation->run())
        {
            $input = $this->input->post();
            $input['active'] = 1;
            if($this->newsletters->create($input))
            {
                $this->session->set_flashdata('success', lang('newsletters_create_success'));
                redirect('admin/newsletters/mails');
            }
            else
            {
                $this->session->set_flashdata('error', lang('newsletters_edit_error'));
                redirect('admin/newsletters/mails/create');
            }
        }
        
        foreach ($this->newsletter_rules AS $rule)
        {
            $rule['field'] = $this->input->post($rule['field']);
        }
		$this->template->append_metadata($this->load->view('fragments/wysiwyg',$this->data,TRUE))
            ->set('templates', $email_templates)
            ->build('admin/mail_form', $this->data);
	}

	function edit($id=false)
	{
        $this->load->model('templates/email_templates_m');
	    $email_templates = $this->email_templates_m->where('is_default',0)->dropdown('id','name');
		if($id) $this->data->newsletter=$this->newsletters->get_newsletters(false,$id);
        
        $this->form_validation->set_rules($this->newsletter_rules);
        if($this->form_validation->run())
        {
            unset($_POST['btnAction']);
            $input = $this->input->post();
            $input['modified'] = date("Y-m-d H:i:s");
            
            if($this->newsletters->update($id, $input))
            {
                // All good...
                $this->session->set_flashdata('success', lang('newsletters_edit_success'));
                redirect('admin/newsletters/mails/edit/'.$id);
            }
            else
            {
                $this->session->set_flashdata('error', lang('newsletters_edit_error'));
                redirect('admin/newsletters/mails/edit');
            }
        }
        
		$this->template->append_metadata($this->load->view('fragments/wysiwyg',$this->data,TRUE))
            ->set('templates', $email_templates)
            ->build('admin/mail_form', $this->data);
	}

	

	function confirm_send($id)
	{
		foreach($this->newsletters->get_newsletters('draft',$id) as $mail)
		{
			$this->data->mail_id=$mail->id;
            $this->data->template_id=$mail->template_id;
			$this->data->subject=$mail->subject;
			$this->data->body=$mail->body;
		}
		$this->data->groups=$this->newsletters->groups();
		$this->template->build('admin/confirm_send',$this->data);
	}


	
	function send_mail($id,$preview=false)
	{
		$this->load->model('recipient_m');
        $this->load->model('group_m');
        $this->load->model('templates/email_templates_m');
        $template_id = $this->input->post('template_id');
        
        $data['slug'] = $this->email_templates_m->get($template_id)->slug;
        //check if the mail is being previewed by the logged in user
        if($preview===true)
        {
            //get the logged in users email address
            $this->db->from('users');
            $this->db->where('id',$this->session->userdata('recipient_id'));
            $query=$this->db->get();
            foreach($query->result() as $user)
            {
                $email_recipients[$user->email]=$user->first_name.' '.$user->last_name;
            }
        }
        else{
            if($this->input->post('group'))
            {
                foreach($this->input->post('group') as $group_id)
                {
                    $groups[$group_id] = $this->recipient_m->get_recipients_groups($group_id);
                        
                }
            }
            $emails = array();
            if(isset($groups) && count($groups) > 0){
                foreach($groups as $group){
                    foreach($group as $user){
                        $emails[$user->email] = $user->name;
                    }
                }
            }
            
            //get additional recipients from textarea DELETE ME
            if($this->input->post('additional_recipients'))
            {
                //consolidate the entries
                $recipients=$this->input->post('additional_recipients');
                $recipients=preg_replace("/\n/","|",$recipients);
                $recipients=str_replace(',',"|",$recipients);
                $recipients=preg_replace("/\s+/","|",$recipients);
                
                $recipients=explode("|",$recipients);
                foreach($recipients as $value)
                {
                    $value=trim($value);
                    if(!empty($value))
                    {
                        $emails[$value]='';
                    }
                }
            }
        }

        //by now we should have the users names and emails in an array
        if(is_array($emails))
        {
            //get the mail contents
            $newsletter = $this->newsletters->get($id);
            
            $data['subject'] = $newsletter->subject;
            $data['body'] = $newsletter->body;
            
            $delivery_count = 0;
            $failure_count = 0;
            foreach ($emails as $email => $name) {
                $data['email'] = $email;
                $data['name'] = $name;
                 
                if((bool) $this->_send_email($data)){
                    $delivery_count ++;
                }
                else{
                    $failure_count ++;
                }
            }
            $message = "";
            if(($delivery_count > 0) && ($failure_count == 0)){
                $status = 'success'; 
                $message = "Email Successfully sent to ".$delivery_count." Recipients.<br />";
            }
            elseif(($delivery_count == 0) && ($failure_count > 0)){
                $status = 'error'; 
                $message = "Email Sending failed to ".$failure_count." Recipients.";
            }
            else{
                $status = 'notice'; 
                $message = "Email Sent to ".$delivery_count." recipients and failed to ".$failure_count." recipients.";
            }
        
        }

        if($status!='error' and $preview===false)
        {
            //move the message to 'sent items' if the send succeeded
            $input['date_sent'] = date("Y-m-d H:i:s");
            $this->newsletters->update($id, $input);
            
            //$status_message=$this->email->print_debugger();
        }
        
        //$this->session->set_flashdata($status,$message);
        $this->session->set_flashdata($status, $message);
        redirect('admin/newsletters/mails');
	}


    function add_users_from_file(){$this->newsletters->add_users_from_file();}
    
    /**
     * Send an email
     *
     * @param array $comment The comment data.
     * @param array $entry The entry data.
     * @return boolean 
     */
    private function _send_email($data)
    {
        $this->load->library('email');
        $this->load->library('user_agent');
        
        // Add in some extra details
        $content['slug'] = $data['slug'];
        $content['name'] = ($data['name'] != '') ? $data['name'] : $data['email'];
        $content['to'] = $data['email'];
        $content['subject'] = $data['subject'];
        $content['body'] = $data['body'];
           
        //trigger the event
        return (bool) Events::trigger('email', $content);
    }

}