<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Streams Field Type
 *
 * @package   PyroStreams Field Type
 * @author    lck
 * @copyright Copyright (c) 2012
 * @link      lkamal.com.np
 *
 */

class Field_imagepicker
{
	/**
	 * Required variables
	 */
	public $field_type_name = 'File picker';
	public $field_type_slug = 'imagepicker';
	public $db_col_type     = 'char(15)';
	
	/**
	 * Optional variables 
	 */
	public $input_is_file     = false;
	public $extra_validation  =   '';
	public $lang              = array();
	public $version					= '1.0';
	public $custom_parameters		= array('img_width', 'type');
	public $author					= array('name'=>'Kamal Lamichhane', 'url'=>'http://lkamal.com.np');
	
	/**
	 * create CI instance
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('files/files');
	}
	
	/**
	 * Output form input
	 * Used when adding entry to stream
	 * 
	 * @param	array
	 * @param	array
	 * @return string
	 */
	public function form_output($data, $id = false, $field)
	{
		$options['name'] 	= $data['form_slug'];
		$options['id']		= $data['form_slug'];
		$options['type']    = 'hidden';
		$options['maxlength']    = '15';
		$options['value']	= $data['value'];
		
		$img_width = isset($field->field_data['img_width']) ? $field->field_data['img_width'] : '100';
		$type = isset($field->field_data['type']) ? $field->field_data['type'] : 'i';
		$del_button = '<button href="javascript:void(1)" id="remove_'.$data['form_slug'].'" class="btn red">X</button>';
		
		$return = '<script type="text/javascript">
			(function($) {  
				$("#btn_'.$data['form_slug'].'").livequery("click", function(){
					ImagePicker.open({
						fileType : "'.$type.'",
						onPickCallback      : function(imageId, size, alignment, type, name) {
							var image = \'<img class="pyro-image" src="'.base_url().'files/thumb/\'+imageId+\'/'.$img_width.'/'.$img_width.'" width="'.$img_width.'"/>'.$del_button.'\';
							$("#'.$data['form_slug'].'").val(imageId);
							var view = (type == "i") ? image : name + \''.$del_button.'\';

							$("#preview_'.$data['form_slug'].'").html(view);
						}
					});
					return false;
				});
			})(jQuery);
			
			(function($){
				$("#remove_'.$data['form_slug'].'").livequery("click", function(){
					$("#'.$data['form_slug'].'").val("");
					$("#preview_'.$data['form_slug'].'").html("");
				});
			})(jQuery);
		</script>';
		
		$return .= form_input($options);
		$preview = '';
		if($data['value']){
			$file = Files::get_file($data['value']);
			if($file['status']){
				$preview = $file['data']->type == 'i' 
					? '<img class="pyro-image" src="'.base_url().'files/thumb/'.$data['value'].'/'.$img_width.'/'.$img_width.'" width="'.$img_width.'"/>'."&nbsp;".$del_button 
					: $file['data']->name."&nbsp;".$del_button; 
			}
		}
		$return .= '<button href="javascript:void(1)" data-type="'.$type.'" id="btn_'.$data['form_slug'].'" class="">Select '.$field->field_name.'</button>';
		$return .= '<div id="preview_'.$data['form_slug'].'">'.$preview.'</div>';
		
		return $return;
	}
	
	public function pre_output($input, $params)
	{
		if ( ! $input or $input == 'dummy' ) return null;

		// Get image data
		$image = $this->CI->db->select('filename, alt_attribute, description, name')->where('id', $input)->get('files')->row();

		if ( ! $image) return null;

		// This defaults to 100px wide
		return '<img src="'.site_url('files/thumb/'.$input).'" alt="'.$this->obvious_alt($image).'" />';
	}

	public function pre_output_plugin($input, $params)
	{
		if ( ! $input or $input == 'dummy' ) return null;

		$this->CI->load->library('files/files');

		$file = Files::get_file($input);

		if ($file['status'])
		{
			$image = $file['data'];

			// If we don't have a path variable, we must have an
			// older style image, so let's create a local file path.
			if ( ! $image->path)
			{
				$image_data['image'] = base_url($this->CI->config->item('files:path').$image->filename);
			}
			else
			{
				$image_data['image'] = str_replace('{{ url:site }}', base_url(), $image->path);
			}

			// For <img> tags only
			$alt = $this->obvious_alt($image);

			$image_data['filename']			= $image->filename;
			$image_data['name']				= $image->name;
			$image_data['alt']				= $image->alt_attribute;
			$image_data['description']		= $image->description;
			$image_data['img']				= img(array('alt' => $alt, 'src' => $image_data['image']));
			$image_data['ext']				= $image->extension;
			$image_data['mimetype']			= $image->mimetype;
			$image_data['width']			= $image->width;
			$image_data['height']			= $image->height;
			$image_data['id']				= $image->id;
			$image_data['filesize']			= $image->filesize;
			$image_data['download_count']	= $image->download_count;
			$image_data['date_added']		= $image->date_added;
			$image_data['folder_id']		= $image->folder_id;
			$image_data['folder_name']		= $image->folder_name;
			$image_data['folder_slug']		= $image->folder_slug;
			$image_data['thumb']			= site_url('files/thumb/'.$input);
			$image_data['thumb_img']		= img(array('alt' => $alt, 'src'=> site_url('files/thumb/'.$input)));

			return $image_data;
		}
	}

	/**
	 * Default width of image
	 *
	 * @return	string
	 */
	public function param_img_width( $value = 80 )
	{
		return array(
			'input' 		=> form_input('img_width', $value),
			'instructions'	=> $this->CI->lang->line('imagepicker.image_width_instruction')
		);
	}
	
	/**
	 * Default type of picker: enum('a', 'v', 'd', 'i', 'o')
	 *
	 * @return	string
	 */
	public function param_type( $value = 'i' )
	{
		return array(
			'input' 		=> form_dropdown('type', array('i' => 'Image', 'a' => 'Audio', 'v' => 'Video', 'd' => 'Document', 'o' => 'Other'), $value),
			'instructions'	=> $this->CI->lang->line('imagepicker.type_instruction')
		);
	}
	
	
	/**
	*
	* Called before the form is built.
	*      
	* @access public
	* @return void
	*/
	public function event()
	{
		$this->CI->type->add_css('imagepicker', 'imagepicker.css');
		//$this->CI->type->add_css('imagepicker', 'jquery.fileupload-ui.css');
    	$this->CI->type->add_js('imagepicker', 'imagepicker.js');
	}
	
	
	/**
	 * Loads popup file folder chooser
	 */
	public function ajax_viewpicker($id = 0, $showSizeSlider = 0, $showAlignButtons = 0, $fileType = 'i')
	{
		// if (!$this->CI->input->is_ajax_request()) {
		// 	die('Ajax requests only...');
		// }
		
		$this->CI->load->model('files/file_folders_m');
		$this->CI->load->model('files/file_m');
		$this->CI->lang->load('files/files');
		
	    $data = new stdClass;
		$data->showSizeSlider	= $showSizeSlider;
		$data->showAlignButtons	= $showAlignButtons;
		$data->fileType	= $fileType;

		$data->folders          = $this->CI->file_folders_m->get_folders();
		$data->subfolders       = array();
		$data->current_folder   = $id && isset($data->folders[$id]) 
			? $data->folders[$id] 
			: ($data->folders ? current($data->folders) : array());

		// Select the images for the current folder. In the future the selection of the type could become dynamic.
		// For future reference: a => audio, v => video, i => image, d => document, o => other.
		if ($data->current_folder) {   
			$data->current_folder->items = $this->CI->file_m
				->order_by('date_added', 'DESC')
				->where('type', $fileType)
				->get_many_by('folder_id', $data->current_folder->id);

			$subfolders = $this->CI->file_folders_m->folder_tree($data->current_folder->id);
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
		
		echo $this->CI->type->load_view('imagepicker', 'index', $data, true);
	}

	private function obvious_alt($image)
	{
		if ($image->alt_attribute) {
			return $image->alt_attribute;
		}
		if ($image->description) {
			return $image->description;
		}
		return $image->name;
	}
	
}

/* End of file field.imagepicker.php */