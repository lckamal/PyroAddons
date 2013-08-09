<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Events_Newsletters
{

    public function __construct()
    {
        // register the events
        Events::register('subscribe_newsletter', array($this, 'subscribe_newsletter'));
    }
	
	/**
	 * triggers after any other forms and firing newsletter request
	 * 
	 * @param array $data 
	 * 	format:  array (
	 * 			'name' => 'Mr kamal test1 lamichhane',
	 * 			'email' => 'myemail3@email.com',
	 * 			'group' = array (
	 * 					0 => 'Subscribers'
	 * 					1 => 'Trip Updates'
	 * 				)
	 * 			)
	 */
	public function subscribe_newsletter($data = array()){

		ci()->load->model('newsletters/recipient_m');
		ci()->load->model('newsletters/group_m');
		if( ! ci()->recipient_m->get_by('email', $data['email'])){
			$id = ci()->recipient_m->insert(array('name' => $data['name'], 'email' => $data['email'], 'active' => 1));
			
			if($id && isset($data['group']) && is_array($data['group']) && count($data['group']) > 0)
			{
				foreach($data['group'] as $group_name)
				{
					if($group = ci()->group_m->get_by('group_name', $group_name))
					{
						ci()->db->insert('newsletter_recipients_groups', array('recipient_id' => $id, 'group_id' => $group->id));
					}
				}
			}
		}
		return TRUE;	
	}
}
