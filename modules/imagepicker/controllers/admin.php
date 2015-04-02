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

        $this->load->library('files/files');
        
        $allowed_extensions = array();
        foreach (config_item('files:allowed_file_ext') as $type) 
        {
            $allowed_extensions = array_merge($allowed_extensions, $type);
        }

        $this->template->append_metadata(
            "<script>
                pyro.lang.fetching = '".lang('files:fetching')."';
                pyro.lang.fetch_completed = '".lang('files:fetch_completed')."';
                pyro.lang.start = '".lang('files:start')."';
                pyro.lang.width = '".lang('files:width')."';
                pyro.lang.height = '".lang('files:height')."';
                pyro.lang.ratio = '".lang('files:ratio')."';
                pyro.lang.full_size = '".lang('files:full_size')."';
                pyro.lang.cancel = '".lang('buttons:cancel')."';
                pyro.lang.synchronization_started = '".lang('files:synchronization_started')."';
                pyro.lang.untitled_folder = '".lang('files:untitled_folder')."';
                pyro.lang.exceeds_server_setting = '".lang('files:exceeds_server_setting')."';
                pyro.lang.exceeds_allowed = '".lang('files:exceeds_allowed')."';
                pyro.files = { permissions : ".json_encode(Files::allowed_actions())." };
                pyro.files.max_size_possible = '".Files::$max_size_possible."';
                pyro.files.max_size_allowed = '".Files::$max_size_allowed."';
                pyro.files.valid_extensions = '".implode('|', $allowed_extensions)."';
                pyro.lang.file_type_not_allowed = '".addslashes(lang('files:file_type_not_allowed'))."';
                pyro.lang.new_folder_name = '".addslashes(lang('files:new_folder_name'))."';
                pyro.lang.alt_attribute = '".addslashes(lang('files:alt_attribute'))."';

                // deprecated
                pyro.files.initial_folder_contents = ".(int)$this->session->flashdata('initial_folder_contents').";
            </script>");

	}

    /**
     * Folder Listing
     */
    public function index($parent_id = 0, $type = null)
    {
        Asset::add_path('files', 'system/cms/modules/files/');

        $this->template
            ->title($this->module_details['name'])
            ->append_css('jquery/jquery.tagsinput.css')
            ->append_css('files::jquery.fileupload-ui.css')
            ->append_css('files::files.css')
            ->append_css('module::files.css')
            ->append_js('jquery/jquery.tagsinput.js')
            ->append_js('files::jquery.fileupload.js')
            ->append_js('files::jquery.fileupload-ui.js')
            ->append_js('module::functions.js')

            // should we show the "no data" message to them?
            ->set('folders', $this->file_folders_m->count_by('parent_id', 0))
            ->set('locations', array_combine(Files::$providers, Files::$providers))
            ->set('type', $type)
            ->set('folder_tree', Files::folder_tree());

        $path_check = Files::check_dir(Files::$path);

        if ( ! $path_check['status'])
        {
            $this->template->set('messages', array('error' => $path_check['message']));
        }

        $this->template
            ->set_layout('colorbox')
            ->build('admin/index');
    }

	public function _index($id = 0, $fileType = 'i') {
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
			->build('files/admin/index', $data);
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

    /**
     * Get all files and folders within a folder
     *
     * Grabs the parent id from the POST data.
     */
    public function folder_contents()
    {
        $parent = $this->input->post('parent');
        $type = $this->input->post('type');
        class_exists('Imagepicker_m') or $this->load->model('imagepicker_m');

        ob_end_clean();
        echo json_encode($this->imagepicker_m->folder_contents($parent, $type));
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
