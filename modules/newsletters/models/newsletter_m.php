<?php if(!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * This is a Newsletter module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Newsletter
 */
class Newsletter_m extends MY_Model {
    
    const MAIL='newsletters';
    const USERS='newsletter_recipients';
    const GROUPS='newsletter_groups';
    const USERS_GROUPS='newsletter_recipients_groups';
    
	public function __construct()
	{
		parent::__construct();
        $this->_table = 'newsletters';
        $this->primary_key = 'id';
	}
	

	//separate the logic that decides if a newsletter is sent, draft, or trash
	//for select only
	function _is_draft(){return $this->db->where(array('date_sent' =>NULL, 'active' => 1));}
	function _is_sent(){return $this->db->where(array('date_sent !=' =>'NULL', 'active' => 1));}
	function _is_trash(){return $this->db->where('active',0);}
	

	//determine status of mail to structure the correct query
	function _get_type($type)
	{
		if(!$type)
			return false;
		elseif($type=='trash')
			return $this->_is_trash();
		elseif($type=='draft')
			return $this->_is_draft();
		elseif($type=='sent')
			return $this->_is_sent();
	}


	//count anything
	function count($type,$group_id=false)
	{
		if($type=='groups')
		{
			$table=self::GROUPS;
		}
		elseif($type=='recipients')
		{
			if($group_id)
			{
				$this->db->where('group_id',$group_id);
				$table=self::USERS_GROUPS;
			}
			else
			{
				$table=self::USERS;
			}
		}
		else
		{
			$this->_get_type($type);//draft,sent,or trash
			$table=$this->_table;
		}
		return $this->db->count_all_results($table);
	}


	function get_newsletters($type=false,$id=false)
	{
		if($id) $this->db->where('id',$id)->limit(1);
		//if($num and $offset) $this->db->limit($num,$offset);
		$this->_get_type($type);
		$this->db->order_by('modified','desc');
		$query=$this->db->get($this->_table);
		return $query->result();
	}
    
    function create($input){
        if(isset($input['btnAction'])) unset($input['btnAction']);
        return $this->db->insert($this->_table, $input);
    }


	function recipients($id=false,$group_id=false,$limit=false,$offset=false)
	{
		if($limit and $offset) $this->db->limit($limit,$offset);//needs pagination in controller
		
		if($id) $this->db->where('id',$id)->limit(1);
		elseif($group_id){
			$this->db->where(self::USERS_GROUPS.'.group_id',$group_id);
			$this->db->join(self::USERS_GROUPS, self::USERS.'.id = '.self::USERS_GROUPS.'.recipient_id');
		}
		$query=$this->db->get(self::USERS);
		return $query->result();
	}


	function groups($id=false,$public=false)
	{
		if($id) $this->db->where('id',$id)->limit(1);
		if($public==true) $this->db->where('group_public',1);
		return $this->db->get(self::GROUPS)->result();
	}


	function user_groups($id)
	{
		$this->db->from(self::USERS_GROUPS);
		$this->db->where('recipient_id',$id);
		$this->db->join(self::GROUPS,self::USERS_GROUPS.'.group_id = '.self::GROUPS.'.id');
		$query=$this->db->get();
		return $query->result();
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


	function edit_recipient($id=false)
	{
		$this->load->helper('email');
	
		$email=$this->input->post('email');
		$name=$this->input->post('name');
		$groups=$this->input->post('group');
		
		//FIX ME
		if($this->_check_email($email,$id)===false)//fix messages
		{//I don't like doing this here, but the import uses this method
		//die($this->session->flashdata('error'));
		$err_msg=$this->session->flashdata('error');//wtf i know
			$this->session->set_flashdata('error',$errmsg.'The email <strong>'.$email.'</strong> is already in use');
			//$status='notice';
		}
		
		
		elseif(is_email($email,true) and !empty($name) and !empty($groups))
		{

			//add or edit the user
			$action='update';
			$id ? $this->db->where('id',$id) : $action='insert';
			$this->db->limit(1);
			$data['name']=$name;
			$data['email']=$email;
			$data['modified']=date("Y-m-d H:i:s");
			$this->db->$action(self::USERS,$data) ? $status=true : $status=false;

			//create user/group associations
			sort($groups);
			!$id ? $id=$this->db->insert_id() : null;
			$this->db->delete(self::USERS_GROUPS,array('recipient_id'=>$id)); //delete existing links
			$data2['recipient_id']=$id;
			foreach($groups as $group)
			{
				$data2['group_id']=$group;
				$this->db->insert(self::USERS_GROUPS,$data2) ? $status=true : $status=false;//add new link
			}
			return $status;
		}
		else
		{
			return false;
		}
	}


	//does not apply to groups yet - do we really need to delete multiple groups at a time?
	function delete($id)
	{
		$args = func_get_args();
		list($table, $id, $restore) = $args;
		
		$restore = isset($restore) ? $restore : false;
		!is_array($id) ? $ids=array($id) : $ids=$id;
	
		if($table=='recipients')
		{
			if(!isset($action)) $action='delete';
			foreach($ids as $id)
			{
				foreach($this->recipients($id) as $item)
				{
					$this->db->where('id',$id)->delete(self::USERS);
					$this->db->where('recipient_id',$id)->delete(self::USERS_GROUPS);
					$items[] = $item->email;
				}
			}
		}
	
		elseif($table=='newsletters')
		{
			foreach($ids as $id)
			{
				foreach($this->get_newsletters(false,$id) as $item)
				{
					if($item->active==1)//move to trash
					{
						$this->db->where('id',$id)->update($this->_table,array('active'=>0));
						if(!isset($action)) $action='trash';
					}
					elseif($item->active==0)
					{
						if($restore==true)//restore
						{
							$this->db->where('id',$id)->update($this->_table,array('active'=>1));
							if(!isset($action)) $action='restore';
						}
						else//delete
						{
							$this->db->where('id',$id)->delete($this->_table);
							if(!isset($action)) $action='delete';
						}
					}
					$items[] = $item->subject;
				}
			}
		}

		if(!empty($items))
		{
			if( count($items) == 1 )// Only deleting one
			{
				$this->session->set_flashdata('success', sprintf($this->lang->line('newsletters_'.$table.'_'.$action.'_success'), $items[0]));
			}			
			else// Deleting multiple
			{
				$this->session->set_flashdata('success', sprintf($this->lang->line('newsletters_'.$table.'_'.$action.'_many_success'), implode(', ', $items)));
			}
		}		
		else// For some reason, none of them were deleted
		{
			$this->session->set_flashdata('notice', lang('newsletters_'.$table.'_'.$action.'_error'));
		}
		return true;// ???
		//die(print_r($this->session->flashdata()));
	}


	function delete_group($id)
	{
		$this->db->delete(self::USERS_GROUPS,array('group_id'=>$id));
		//prevent orphaned users here
		return $this->db->delete(self::GROUPS,array('id'=>$id)) ? true:false;
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
                    $groups[$group_id] = $this->recipient_m->select(self::USERS.'.email,'.self::USERS.'.name')
                        //->from('newsletter_recipients r')
                        ->join(self::USERS_GROUPS. ' g','g.recipient_id = '.self::USERS.'.id')
                        ->where('g.group_id',$group_id)
                        ->get_all();
                        
                        
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
			$newsletter = $this->get($id);
            
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
			$data['date_sent']=date("Y-m-d H:i:s");
			$this->db->where('id',$id)->limit(1);
			$this->db->update($this->_table,$data);
			
			$status_message=$this->email->print_debugger();
		}
		echo $status;exit;
		//redirect with message
		$this->session->set_flashdata($status,$message);
        
		$status==='error' ? redirect('/admin/newsletters/') : redirect('/admin/newsletters');
	}


	function add_users_from_file()
	{
		//move to validation in controller
		if(!$this->input->post('group')):
			$this->session->set_flashdata('error','You must select a group to add to.');
			redirect('/admin/newsletters/batch_add_recipients');
		endif;
		
		//move to validation in controller
		//upload the file
		$config['upload_path']='application/uploads/tmp/';
		$config['allowed_types']='txt|csv';//its allowing at least .xls files to upload - FIX ME!
		$config['overwrite']=true;
		$config['max_size']='0';
		$this->load->library('upload',$config);
		
		//move to validation in controller
		if(!$this->upload->do_upload()):
			$this->session->set_flashdata('error',strip_tags($this->upload->display_errors()));
			redirect('/admin/newsletters/batch_add_recipients');
		endif;
//move to validation in controller
		$file=$this->upload->data();
		$file=$file['full_path'];

		
		$user_data=file_get_contents($file);
		$lines=explode("\n",$user_data);
		
		
		$error_message='';
		$success_message='';
		$error_count=0;
		$success_count=0;
		$message='';
		$data['group_id']=$this->input->post('group');
		$data['modified']=date("Y-m-d H:i:s");
		
	
		
		//add the data
		foreach($lines as $key => $value):
			$line=explode(',',$value);
			isset($line[0]) ? $email=$line[0] : $email='';
			isset($line[1]) ? $name=$line[1] : $name='';
			$_POST['email']=$email;
			$_POST['name']=$name;
			if($this->edit_recipient()):
					$success_count+=1;
					$success_message.='<li>Name: '.$name.' - Email: '.$email.'</li>';
			else:
				$error_count+=1;
				$error_message.='<li>Line '.($key+1).': <em>'.$lines[$key].'</em></li>';
			endif;
			unset($name);
			unset($email);
		endforeach;
		unlink($file);//delete the file

		//$success_message!='' ? $message.='<ul class="success message"><li>'.$success_count.' users added!</li>'.$success_message.'</ul>' : $status='error';
		//$error_message!='' ? $message.='<ul class="notice message"><li>'.$error_count.' users failed!</li>'.$error_message.'</ul>' : $status='success';
		//!isset($status) ? $status='notice' : null;

		if($success_message!='')
			$this->session->set_flashdata('success','<ul><li>'.$success_count.' users added!</li>'.$success_message.'</ul>');
		if($error_message!='')
			$this->session->set_flashdata('notice','<ul><li>'.$error_count.' users failed!</li>'.$error_message.'</ul>');

		redirect('admin/newsletters');
	}
    
    
}