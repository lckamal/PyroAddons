<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a Newsletter module for PyroCMS
 *
 * @author 		Kamal Lamichhane
 * @website		http://lkamal.com.np
 * @package 	PyroCMS
 * @subpackage 	Newsletter
 */
class Recipient_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
		
		$this->_table = 'newsletter_recipients';
        $this->primary_key = 'id';
	}
	
	//create a new item
	public function create($input)
	{
	    $group = $input['group'];

	    unset($input['btnAction'], $input['group']);

		if($this->db->insert($this->_table, $input)){
		    $recipient_id = $this->db->insert_id();
            return $this->_recipient_groups($recipient_id, $group);
		}
        else{
            return FALSE;
        }
	}
    
    public function update($primary_value, $data, $skip_validation = false){
        $group = $data['group'];

        unset($data['btnAction'], $data['group']);

        if(parent::update($primary_value, $data)){
            return $this->_recipient_groups($primary_value, $group);
        }
        else{
            return FALSE;
        }
    }
    
    public function get_recipients_groups($group_id){
        $groups = $this->select('newsletter_recipients.email,newsletter_recipients.name')
            //->from('newsletter_recipients r')
            ->join('newsletter_recipients_groups g','g.recipient_id = newsletter_recipients.id')
            ->where('g.group_id',$group_id)
            ->get_all();
            
            return $groups;
    }
    /**
     * Inserts recipients to groups
     * 
     * @access protected
     * @param int $recipient_id
     * @param array $groups posted from checkbox
     */
    protected function _recipient_groups($recipient_id, $groups){
        $this->_table = 'newsletter_recipients_groups';
        $this->db->delete($this->_table, array('recipient_id' => $recipient_id));
        
        $rg = array();
        foreach($groups as $group){
            $rg[] = array('recipient_id' => $recipient_id, 'group_id' => $group);
        }
        
        return $this->db->insert_batch($this->_table, $rg);
    }

}
