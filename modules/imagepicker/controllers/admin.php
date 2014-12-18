<?php defined('BASEPATH') or exit('No direct script access allowed');
	
class Admin extends Admin_Controller
{
	public $id = 0;

	public function __construct() {
		// Call the parent constructor.
		parent::__construct();
		$this->load->model('files/file_folders_m');
		$this->load->model('files/file_m');
		$this->lang->load('files/files');
		$this->lang->load('imagepicker');

	}
		
	public function index($id = 0, $fileType = 'i') {
		// if (!$this->input->is_ajax_request()) {
		// 	die('Ajax requests only...');
		// }
		//$this->template->set_layout(FALSE);

		$data = new stdClass;
		$data->showSizeSlider	= $showSizeSlider;
		$data->showAlignButtons	= $showAlignButtons;
		$data->fileType	= $fileType;

		$data->folders          = $this->file_folders_m->get_folders();
		$data->subfolders       = array();
		$data->current_folder   = $id && isset($data->folders[$id]) 
			? $data->folders[$id] 
			: ($data->folders ? current($data->folders) : array());

		// Select the images for the current folder. In the future the selection of the type could become dynamic.
		// For future reference: a => audio, v => video, i => image, d => document, o => other.
		if ($data->current_folder) {   
			$data->current_folder->items = $this->file_m
				->order_by('date_added', 'DESC')
				->where('type', $fileType)
				->get_many_by('folder_id', $data->current_folder->id);

			$subfolders = $this->file_folders_m->folder_tree($data->current_folder->id);
			foreach ($subfolders as $subfolder) {   
				$data->subfolders[$subfolder->id] = repeater('&raquo; ', $subfolder->depth) . $subfolder->name;
			}

			// Set a default label
			$data->subfolders = $data->subfolders 
				? array($data->current_folder->id => lang('files.dropdown_root')) + $data->subfolders
				: array($data->current_folder->id => lang('files.dropdown_no_subfolders'));
		}

		// Array for select
		$data->folders_tree = array();
		foreach ($data->folders as $folder) {
			$data->folders_tree[$folder->id] = repeater('&raquo; ', $folder->depth) . $folder->name;
		}

        Asset::add_path('imagepicker', IMAGEPICKER_PATH.'imagepicker/');
		$this->template
			->set_layout('modal')
            //->append_js('jquery/jquery.1.11.js')
			->append_js('imagepicker::imagepicker.js')
			->append_css('imagepicker::imagepicker.css')
			->build('admin/index', $data);
	}

	public function fileupload()
	{
		$this->load->library('form_validation');

		$rules = array(
			array(
				'field'   => 'name',
				'label'   => 'lang:files:name',
				'rules'   => 'trim'
			),
			array(
				'field'   => 'description',
				'label'   => 'lang:files:description',
				'rules'   => ''
			),
			array(
				'field'   => 'folder_id',
				'label'   => 'lang:files:folder',
				'rules'   => 'required'
			),
		);

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run())
		{
			$input = $this->input->post();

			$results = Files::upload($input['folder_id'], $input['name'], 'userfile');

			// if the upload was successful then we'll add the description too
			if ($results['status'])
			{
				$data = $results['data'];
				$this->file_m->update($data['id'], array('description' => $input['description']));
			}

			// upload has a message to share... good or bad?
			$this->session->set_flashdata($results['status'] ? 'success' : 'notice', $results['message']);
		}
		else
		{
			$this->session->set_flashdata('error', validation_errors());
		}

		//redirect("admin/imagepicker/{$this->input->post('redirect_to')}/upload/{$this->input->post('folder_id')}");
	}

    public function new_folder()
    {
        $folder_name = $this->input->post('name');
        $parent_folder = (int)$this->input->post('folder_id');

        $results = Files::create_folder($parent_folder, $folder_name);

        $this->session->set_flashdata($results['status'] ? 'success' : 'notice', $results['message']);
        $redirect_to = $this->input->post('redirect_to') ? $this->input->post('redirect_to') : 'admin/imagepicker/index/'.$input['folder_id'];
        redirect($redirect_to);
    }
}
