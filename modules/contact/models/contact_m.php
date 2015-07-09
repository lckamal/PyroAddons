<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Contact\Models
 */
class Contact_m extends MY_Model {

	// Added by Tony
	public function __construct()
	{		
		parent::__construct();

		/**
		 * If the module's table was named "contacts"
		 * then MY_Model would find it automatically. Since
		 * It's named "contact_log" then we just set the name here.
		 */
		$this->_table = 'contact_log';
	}
	
	public function get_log()
	{
		return $this->db
			->get('contact_log')
			->result();
	}
	
	public function insert_log($input)
	{		
		return $this->db->insert('contact_log', array(
			'email'			=> isset($input['email']) ? $input['email'] : '',
			'subject' 		=> substr($input['subject'], 0, 255),
			'message' 		=> $input['body'],
			'sender_agent' 	=> $input['sender_agent'],
			'sender_ip' 	=> $input['sender_ip'],
			'sender_os' 	=> $input['sender_os'],	
			'sent_at' 		=> time(),
			'attachments'	=> isset($input['attach']) ? implode('|', $input['attach']) : '',
		));
	}

	/**
	 * Get a message log by its ID
	 * 
	 * @access	public
	 * @author	Tony G. Bolaño
	 * @params	int[$id]	ID of message log record
	 * @returns	object	The contact log record
	 */
	public function get($id)
	{
		return $this->db
			->where('id', $id)
			->get('contact_log')
			->row();
	}
}