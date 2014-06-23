<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * gallery module - myaccess
 *
 *
 * @author 		Kamal Lamichhane
 * @website		http://lkamal.com.np
 * @package 	Access-himalaya
 * @subpackage 	Holidays
 */
class Myaccess extends Myaccess_Controller
{
	/**
	 * data to store
	 */
	var $data;

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
		
		
        $this->data = new stdClass();
        $this->load->model('holidays/holiday_m');
        $this->load->model('holidays/datenprice_m');
        $this->load->model('booking/booking_m');
        $this->load->model('galleries/gallery_m');
        $this->load->model('galleries/gallery_image_m');
		$this->load->model('files/file_m');
		$this->load->model('files/file_folders_m');
		
        $this->lang->load('holidays/holidays');
        $this->lang->load('booking/booking');
        $this->lang->load('galleries');
		
		$this->load->library('files/files');
		
        $this->load->driver('Streams');
		
		if(!$this->_installed_related_modules()){
			$this->session->set_flashdata('notice', lang('galleries:related_module_error'));
			redirect();
		}
		
        $this->template->enable_parser(true)
		->append_js('jquery/jquery-ui.min.js')
		->append_css('jquery/blitzer/jquery-ui.css')
        ->append_js('jquery/jquery.ui.datepicker.min.js')
		->append_js('fancybox/jquery.fancybox.pack.js')
		->append_css('fancybox/jquery.fancybox.css')
		->append_js('gallery.js');
		//->set_layout('full_width');
    }
     /**
     * Shows booked trip list for gallery creation
     *
     * @access	public
     * @return	void
     */
    public function index()
    {
		$params = array(
            'stream' => 'bookings',
            'namespace' => 'booking',
            'paginate' => 'yes',
            'page_segment' => 4,
            'where' => SITE_REF.'_bookings.created_by = ' . $this->current_user->id .' and '. SITE_REF.'_bookings.status = 1'
        );
		
        $bookings = $this->streams->entries->get_entries($params);
		if($bookings['entries'])
		{
			foreach($bookings['entries'] as &$booking)
			{
				$booking['holiday'] = $this->booking_m->get_holiday($booking['id'])->trip_title;
				$booking['gallery'] = $this->gallery_m->get_by(array('user_id' => $this->current_user->id, 'booking_id' => $booking['id']));
			}
		}

		$this->data->bookings = $bookings;
		$this->template->title("My Picture Gallery")
			->build('myaccess/index', $this->data);
    }
	
	public function create($booking_id = NULL)
	{
		$booking_id || show_404();
		//check for wrong access to gallery creation
		$user_booking = $this->booking_m->get_by(array('id' => $booking_id, 'status' => 1, 'created_by' => $this->current_user->id));
		if(empty($user_booking))
		{
			$this->session->set_flashdata('error', lang('gallery:allowed_create_gallery_for_own'));
			redirect(site_url('myaccess/galleries'));
		}
		
		if($this->input->post('submit'))
		{
			$validation_rules = array(
				array(
					'field' => 'name',
					'label' => 'Gallery Name',
					'rules' => 'required|max_length[100]'
				)
			);
			
			$this->form_validation->set_rules($validation_rules);
			
			if($this->form_validation->run())
			{
				$data = $this->save_gallery('insert', $booking_id);
				if($data['status'] == TRUE)
				{
					$this->session->set_flashdata('success', sprintf(lang('gallery:user_gallery_save_success'), $data['images']));
				}
				else{
					$this->session->set_flashdata('success', lang('galleries.create_error'));
				}
				redirect(site_url('myaccess/galleries'));
			}
		}
		
		$this->data->max_images = Settings::get('images_per_user') ? Settings::get('images_per_user') : 10;
		$this->data->booking = $this->booking_m->get($booking_id);
		$this->data->holiday = $this->booking_m->get_holiday($booking_id);
		$this->data->datenprice = $this->booking_m->get_datenprice($booking_id);
		$this->data->gallery = $this->gallery_m->get_by(array('booking_id' => $booking_id, 'user_id' => $this->current_user->id));
		$this->data->images = ($this->data->gallery) ? $this->gallery_image_m->get_many_by(array('gallery_id' => $this->data->gallery->id)) : NULL;
		
		//$this->template->append_metadata('<link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap.min.css">');
		//$this->template->append_metadata('<link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap-responsive.min.css">');
		$this->template->title("My Picture Gallery")
		
			->append_css("fileupload/jquery.fileupload-ui.css")
			->append_js("fileupload/vendor/jquery.ui.widget.js")
			// ->append_js("fileupload/vendor/tmpl.min.js")
			// ->append_js("fileupload/vendor/load-image.min.js")
			// ->append_js("fileupload/vendor/canvas-to-blob.min.js")
			// ->append_js("fileupload/vendor/bootstrap.min.js")
			// ->append_js("fileupload/vendor/blueimp-gallery.min.js")
// 		
			// ->append_js("fileupload/jquery.iframe-transport.js")
			// ->append_js("fileupload/jquery.fileupload.js")
			// ->append_js("fileupload/jquery.fileupload-fp.js")
			// ->append_js("fileupload/jquery.fileupload-ui.js")
			// ->append_js("fileupload/locale.js")
			// ->append_js("fileupload/main.js")
			
			->build('myaccess/gallery/form', $this->data);
	}
	
	function upload()
	{
		error_reporting(E_ALL | E_STRICT);

        $this->load->helper("upload.class");

        $upload_handler = new UploadHandler();

        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Content-Disposition: inline; filename="files.json"');
        header('X-Content-Type-Options: nosniff');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'OPTIONS':
                break;
            case 'HEAD':
            case 'GET':
                $upload_handler->get();
                break;
            case 'POST':
                if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE') {
                    $upload_handler->delete();
                } else {
                    $upload_handler->post();
                }
                break;
            case 'DELETE':
                $upload_handler->delete();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }
	}

	/**
	 * save Gallery
	 * 
	 * @access private
	 * @param string $type insert|update
	 * @param int $booking_id
	 * @return bool
	 */
	private function save_gallery($type = 'insert', $booking_id = 0)
	{
		$gallery_folder = Settings::get('holidays-users-gallery');
		if(!is_numeric($gallery_folder)) return FALSE;
		
		$gallery_name = $this->input->post('name');
		$booking_ref = $this->input->post('booking_ref');
		$gallery_desc = $this->input->post('description');
		$gallery_id = $this->input->post('gallery_id');
		
		
		if($gallery_id == 0)
		{
			$gallery_folder_slug = $this->current_user->username . "-" . $booking_ref . "-" . $gallery_name;
			
			//check if slug already exists redirect to gallery if already exists
			if($this->gallery_m->get_by('slug', $gallery_folder_slug)->id)
			{
				$this->session->set_flashdata('success', lang('gallery:user_gallery_save_success').lang('gallery:upload_images_instruction'));
				redirect('myaccess/galleries/create/'.$booking_id);
			}
			
			$gallery = array(
				'user_id' => $this->current_user->id,
				'datenprice_id' => (int)$this->input->post('datenprice_id'),
				'booking_id' => $booking_id,
				'title' => $gallery_name,
				'description' => $this->input->post('description'),
				'slug' => $gallery_folder_slug,
				'folder_id' => $gallery_folder,
				'description' => $gallery_desc,
				'enable_comments' => 0,
				'published' => 0
			);
			
			$gallery_id = $this->gallery_m->insert($gallery);
			
		}
		else{
			$type = 'update';
			$gallery = (array) $this->gallery_m->get($gallery_id);
			$gallery_folder_name = $this->file_folders_m->get($gallery->folder_id)->name;
			
			$data = array(
				'title' => $gallery_name,
				'description' => $this->input->post('description'),
				'published' => 0
			);
			
			$this->gallery_m->update($gallery_id, $data);
			
		}
		
		$image_count = $this->save_gallery_images($type, $gallery_id);
		
		return array(
			'images' => isset($image_count) ? $image_count : 0,
			'status' => ($gallery_id) ? TRUE : FALSE
		);		
	}
	
	/**
	 * Save / Upload gallery images
	 * 
	 * @param string $type insert|update
	 * @param int $gallery_id 
	 * @return bool
	 */
	private function save_gallery_images($type = 'insert', $gallery_id)
	{
		if(!is_numeric($gallery_id)){ return FALSE; }
		
		$folder_id = $this->gallery_m->get($gallery_id)->folder_id;
		$max_images = $this->input->post('max_images');
		$image_name = $this->current_user->username . "Image ";
		$img_data = NULL;
		
		$img_id = array();
		for ($i=1; $i <= $max_images; $i++)
		{
			$img = Files::upload($folder_id, $image_name.$i, $field = 'image'.$i, $width = 800, $height = 800, $ratio = TRUE);
			if($img['status'] === TRUE)
			{
				$img_data = array(
					'gallery_id' => $gallery_id,
					'file_id' => $img['data']['id']
				);
				
				if($file_id = $this->input->post('image'.$i))
				{
					Files::delete_file($file_id);
					$img_id[] = $this->gallery_image_m->update_by('file_id', $file_id, $img_data);
				}
				else{
					$img_id[] = $this->gallery_image_m->insert($img_data);
				}
			}
		}
		
		return count($img_id);
	}
	
	/**
	 * check for installed module related to galleries
	 * 
	 * @return bool
	 */
	private function _installed_related_modules()
	{
		$this->load->model('addons/module_m');
		$required_modules = array('holidays','booking');
		
		$return = TRUE;
		foreach($required_modules as $module)
		{
			if(!$this->module_m->exists($module))
			{
				$return = FALSE;
			}
		}
		
		return $return;
	}
}/* End of file myaccess.php */
