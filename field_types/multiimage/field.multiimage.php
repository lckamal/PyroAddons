<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Multiple Image Field Type
 *
 * @package		PyroCMS\Core\Modules\Streams Core\Field Types
 * @author		lckamal
 * @copyright	Copyright (c) 2011 - 2013, lckamal
 * @link		http://lkamal.com.np
 */
class Field_multiimage
{
	public $field_type_slug			= 'multiimage';

	// Files are saved as serialized file id.
	public $db_col_type				= 'text';
	public $col_constraint 			= 500;

	public $custom_parameters		= array('folder', 'resize_width', 'resize_height', 'keep_ratio', 'allowed_types');

	public $version					= '1.0';

	public $author					= array('name' => 'Parse19', 'url' => 'http://parse19.com');

	public $input_is_file			= true;

	// --------------------------------------------------------------------------

	public function __construct()
	{
		get_instance()->load->library('image_lib');
	}

	public function event()
	{
		$this->CI->type->add_js('multiimage', 'imagefield.js');
		$this->CI->type->add_css('multiimage', 'imagefield.css');		
	}

	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function form_output($params)
	{
		$count = isset($params['custom']['limit']) ? $params['custom']['limit'] : 1;
		$this->CI->load->config('files/files');

		$value = (!empty($params['value'])) ? explode('|', $params['value']) : null;

		$out = '';		
		if ($value and count($value) > 0)
		{
			$out .= '<ul class="list-group">';
			foreach($value as $file)
			{
				$out .= '<li class="list-group-item">';
				$out .= '<span class="image_remove" data-file="'.$file.'">X</span>
						<a class="image_link" href="'.site_url('files/large/'.$file).'" target="_break">
							<img src="'.site_url('files/thumb/'.$file).'" height="100" />
						</a><br />';
				$out .= '</li>';
			}
			$out .= '</ul>';
			$out .= form_hidden($params['form_slug'], $params['value']);
		}
		else
		{
			$out .= form_hidden($params['form_slug'], 'dummy');
		}

		$options['name'] 	= $params['form_slug'].'_file[]';
		$options['multiple'] = 'multiple';
		$out .= form_upload($options);
		//}
		return $out;
	}

	// --------------------------------------------------------------------------

	/**
	 * Process before saving to database
	 *
	 * @access	public
	 * @param	array
	 * @param	obj
	 * @return	string
	 */
	public function pre_save($input, $field, $stream, $row_id, $form_data)
	{
		// If we do not have a file that is being submitted. If we do not,
		// it could be the case that we already have one, in which case just
		// return the numeric file record value.
		if ( ! isset($_FILES[$field->field_slug.'_file']) or ! $_FILES[$field->field_slug.'_file'])
		{
			// allow dummy as a reset
			if (isset($form_data[$field->field_slug]) and $form_data[$field->field_slug])
			{
				return $form_data[$field->field_slug];
			}
			else
			{
				return null;
			}
		}

		$this->CI->load->library('files/files');

		// Resize options
		$resize_width 	= (isset($field->field_data['resize_width'])) ? $field->field_data['resize_width'] : null;
		$resize_height 	= (isset($field->field_data['resize_height'])) ? $field->field_data['resize_height'] : null;
		$keep_ratio 	= (isset($field->field_data['keep_ratio']) and $field->field_data['keep_ratio'] == 'yes') ? true : false;

		// If you don't set allowed types, we'll set it to allow all.
		$allowed_types 	= (isset($field->field_data['allowed_types'])) ? $field->field_data['allowed_types'] : '*';
		
		$field_slug = $field->field_slug.'_file';
		$multifiles = $_FILES[$field_slug];
		$_FILES = null;
		$myfiles = array();
		foreach ($multifiles['name'] as $key => $files)
		{
			$myfiles[$field_slug.$key]['name'] = $files;
			$myfiles[$field_slug.$key]['type'] = $multifiles['type'][$key];
			$myfiles[$field_slug.$key]['tmp_name'] = $multifiles['tmp_name'][$key];
			$myfiles[$field_slug.$key]['error'] = $multifiles['error'][$key];
			$myfiles[$field_slug.$key]['size'] = $multifiles['size'][$key];
			
			$_FILES[$field_slug.$key] = $myfiles[$field_slug.$key];
		}

		if(count($myfiles) == 0){
			return null;
		}
		
		$returns = array();
		foreach($myfiles as $file_slug => $file_data)
		{
			$returns[] = Files::upload($field->field_data['folder'], null, $file_slug, $resize_width, $resize_height, $keep_ratio, $allowed_types);
		}
		
		$files = null;
		if (count($returns) > 0)
		{
			foreach($returns as $return)
			{
				if($return['status'] === true)
				{
					$files[] = $return['data']['id'];
				}
			}
			
			//delete unnecessary files and update the selected file to db
			ci()->load->driver('streams/streams');
			$field_slug = $field->field_slug;
			$entries_files = ci()->streams->entries->get_entry($row_id, $stream->stream_slug, $stream->stream_namespace, false)->$field_slug;
			$rfiles = $this->filter_images($entries_files, $form_data[$field->field_slug]);
			
			if($rfiles)
			{
				$files = array_merge($rfiles, (array)$files);
			}

			return implode('|', $files);
		}
		else
		{
			// Return the ID of the file DB entry
			return null;
		}
	}
	
	/**
	 * return formated files and delete updated files
	 */
	public function filter_images($entries_files = null, $field_files = null)
	{
		$this->CI->load->library('files/files');
		
		if($entries_files){
			
			$entries_parts = explode('|', $entries_files);
			$field_parts = explode('|', $field_files);

			if(is_array($entries_parts) && count($entries_parts) > 0)
			{
				foreach($entries_parts as $field)
				{
					if(!in_array($field, (array)$field_parts)){
						Files::delete_file($field);
					}
				}

				return $field_parts;
			}
			return null;
		}
		else{
			return null;
		}
	}
	// --------------------------------------------------------------------------


	/**
	 * Choose a folder to upload to.
	 *
	 * @access	public
	 * @param	[string - value]
	 * @return	string
	 */
	public function param_folder($value = null)
	{
		// Get the folders
		$this->CI->load->model('files/file_folders_m');

		$tree = $this->CI->file_folders_m->get_folders();

		$tree = (array)$tree;

		if ( ! $tree)
		{
			return '<em>'.lang('streams:image.need_folder').'</em>';
		}

		$choices = array();

		foreach ($tree as $tree_item)
		{
			// We are doing this to be backwards compat
			// with PyroStreams 1.1 and below where
			// This is an array, not an object
			$tree_item = (object)$tree_item;

			$choices[$tree_item->id] = $tree_item->name;
		}

		return form_dropdown('folder', $choices, $value);
	}

	// --------------------------------------------------------------------------

	/**
	 * Param Resize Width
	 *
	 * @access	public
	 * @param	[string - value]
	 * @return	string
	 */
	public function param_resize_width($value = null)
	{
		return form_input('resize_width', $value);
	}

	// --------------------------------------------------------------------------

	/**
	 * Param Resize Height
	 *
	 * @access	public
	 * @param	[string - value]
	 * @return	string
	 */
	public function param_resize_height($value = null)
	{
		return form_input('resize_height', $value);
	}

	// --------------------------------------------------------------------------

	/**
	 * Param Allowed Types
	 *
	 * @access	public
	 * @param	[string - value]
	 * @return	string
	 */
	public function param_keep_ratio($value = null)
	{
		$choices = array('yes' => lang('global:yes'), 'no' => lang('global:no'));

		return array(
				'input' 		=> form_dropdown('keep_ratio', $choices, $value),
				'instructions'	=> lang('streams:image.keep_ratio_instr'));
	}

	// --------------------------------------------------------------------------

	/**
	 * Param Allowed Types
	 *
	 * @access	public
	 * @param	[string - value]
	 * @return	string
	 */
	public function param_allowed_types($value = null)
	{
		return array(
				'input'			=> form_input('allowed_types', $value),
				'instructions'	=> lang('streams:image.allowed_types_instr'));
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Obvious alt attribute for <img> tags only
	 *
	 * @access	private
	 * @param	obj
	 * @return	string
	 */
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
