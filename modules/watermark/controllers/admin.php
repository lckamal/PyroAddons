<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PHPImageWorkshop\ImageWorkshop;
class Admin extends Admin_Controller {

	/**
	 * The current active section
	 *
	 * @var string
	 */
	protected $section = 'watermark';
    protected $data;
	
	public $positions = array(
		'LT' => 'Left Top', 
		'RT' => 'Right Top', 
		'MT' => 'Center Top',
		'LM' => 'Center Middle', 
		'MM' => 'Center', 
		'RM' => 'Right Middle', 
		'LB' => 'Left Bottom', 
		'RB' => 'Right Bottom', 
		'MB' => 'Center Bottom'
	);
	public function __construct()
	{
		parent::__construct();

		// load classes, libs, language
		$this->lang->load('watermark');
		$this->load->library('form_validation');
		$this->load->model(array('files/file_folders_m', 'files/file_m','watermark/watermark_m'));

		// template settings
		$this->template->title($this->module_details['name']);
	}


	public function index()
	{
		// if val is run
		if($this->input->post())
		{
			$validation_rules = array(
				array(
					'field' => 'folder_id',
					'label' => 'Folder',
					'rules' => 'required'
				),
				array(
					'field' => 'text',
					'label' => 'Text',
					'rules' => 'required'
				),
				array(
					'field' => 'font_color',
					'label' => 'Font color',
					'rules' => 'required'
				),
			);
			
			$this->form_validation->set_rules($validation_rules);
			if($this->form_validation->run())
			{
				$data = $this->input->post();
				$this->process_watermark($data);
				redirect('admin/watermark');
			}
		}
		
		// get folders for dropdown
		$folders = $this->file_folders_m->get_all();
		foreach($folders as $folder)
		{
			$select_folders[$folder->id] = $folder->name;
		}

		// set template vars and build
		$this->template
			->append_js('module::colorpicker.min.js')
			->append_css('module::colorpicker.min.css')
			->append_js('module::watermark.js')
			->set('folders', $select_folders)
			->set('positions', $this->positions)
			->build('admin/index');
	}
	
	private function process_watermark($data = array())
	{
		$watermarked_files = $this->watermark_m->select('file_id')->where('folder_id', $data['folder_id'])->get_all();
		$watermarked = array();
		if($watermarked_files)
		{
			foreach($watermarked_files as $wf)
			{
				$watermarked[] = $wf->file_id;
			}
		}
		$all_files = $this->file_m->select('id')->where('folder_id', $data['folder_id'])->get_all();
		foreach($all_files as $file)
		{
			if(!in_array($file->id, $watermarked))
			{
				$data['files'][] = $file->id;
			}
		}

		$this->watermark($data);
	}
	
	private function watermark($data = array())
	{
		$this->load->library('files/files');
		$mark_text = isset($data['text']) ? $data['text'] : Settings::get('site_name');
		
		$font_color = isset($data['font_color']) ? $data['font_color'] : 'ffffff';
		$opacity = isset($data['opacity']) ? (int)$data['opacity'] : 100;

		$font_size = isset($data['font_size']) ? (int)$data['font_size'] : 12;
		$files = $data['files'];
		$font_path = './'.SHARED_ADDONPATH.'modules/watermark/fonts/Gabrielle.ttf';
		if(is_array($files) && count($files) > 0)
		{
			foreach($files as $file)
			{
				$filedata = Files::get_file($file);
				if($filedata['status'] === TRUE)
				{
					$dirpath = FCPATH.'/uploads/default/files/';
					$source = $dirpath.$filedata['data']->filename;
					$imageLayer = ImageWorkshop::initFromPath($source);
					//var_dump($imageLayer);exit;
					$textLayer = ImageWorkshop::initTextLayer($mark_text, $font_path, $font_size, $font_color, $data['rotation']);
					$textLayer->opacity($opacity);
					$imageLayer->addLayerOnTop($textLayer, 12, 12, $data['position']);
					
					$image = $imageLayer->getResult();
					$imageLayer->save($dirpath, $filedata['data']->filename, false, null, 100);
					
					$wm_data = array(
						'file_id' => $filedata['data']->id,
						'folder_id' => $filedata['data']->folder_id,
					);
					$this->watermark_m->insert($wm_data);
				}
				
			}

			$this->session->set_flashdata('success', lang('watermark:submit_success'));
			redirect('admin/watermark');
		}
		else{
			$this->session->set_flashdata('error', lang('watermark:no_files_remaining'));
			redirect('admin/watermark');
		}
	}
	
}