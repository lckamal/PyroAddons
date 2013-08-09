<?php defined('BASEPATH') or exit('No direct script access allowed');
	
class Admin extends Admin_Controller
{
	public $id = 0;

	public function __construct() {
		// Call the parent constructor.
		parent::__construct();

		// If the request wasn't a ajax request we don't serve....
		if (!$this->input->is_ajax_request()) {
			// Todo: Handle this properly...
			die('Ajax requests only...');
		}

		// Since we only serve ajax calls we don't want to use a layout.
		$this->template->set_layout(FALSE);

		$this->load->model('files/file_folders_m');
		$this->load->model('files/file_m');
		$this->lang->load('files/files');
		$this->lang->load('imagepicker');

	}
		
	public function index($id = 0, $showSizeSlider = 1, $showAlignButtons = 1, $fileType = 'i') {
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

		$this->template
			->build('admin/index', $data);
	}
}
