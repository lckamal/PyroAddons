<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * The admin controller for the Contact module.
 *
 * @author PyroCMS Dev Team
 * @package	 PyroCMS\Core\Modules\Contact\Controllers
 */
class Admin extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

		$this->load->language('contact');
		$this->load->model('contact_m');
    }

	/**
	 * Shows the contact messages list.
	 */
	public function index()
	{
		$contact_log = $this->contact_m->order_by('sent_at', 'desc')->get_log();

		// Create pagination links
		// Added by Tony
		$total_rows = $this->contact_m->count_all();
		$pagination = create_pagination('admin/contact/index', $total_rows);

		$this->template
			->set('contact_log', $contact_log)
			->set('pagination', $pagination)
			->build('index');
	}
	
	/**
	 * Show the message details
	 * Added by Tony
	 */
	public function view($id)
	{
		$message = $this->contact_m->get($id);
		
		$this->template
			->set('message', $message)
			->build('message');
	}

}