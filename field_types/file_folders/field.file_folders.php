<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams File Folders Field Type
 *
 * @package		PyroCMS\addons\shared_addons\Field Types
 * @author		Samul Goodwin
 */
class Field_file_folders
{
	public $field_type_name				= 'File Folders';
	
	public $field_type_slug				= 'file_folders';
	
	public $db_col_type				= 'int';

	public $alt_process				= false;

	public $version					= '1.0';

	public $author					= array('name'=>'Samuel Goodwin', 'url'=>'');

	public $_folder_list				= array();
		
	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function form_output($data, $entry_id, $field)
	{

		return form_dropdown($data['form_slug'], $this->folders($field->is_required), $data['value'], 'id="'.$data['form_slug'].'"');
	}

	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function pre_output($input)
	{
		$folders = $this->folders('yes');
		
		if (trim($input) != '')
		{
			return $folders[$input];
		}
		else
		{
			return null;
		}
	}


	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function pre_output_plugin($input, $params)
	{
		$folders = $this->folders('yes');

		if (trim($input) != '')
		{
			$return['name'] = $folders[$input];
			$return['id']	= (int)$input;
			
			return $return;
		}
		else
		{
			return null;
		}
	}

	private function folders($is_required)
	{

		$this->CI->load->library('files/files');

		$choices = array();
	
		if ($is_required == 'no')
		{
			$choices[null] = get_instance()->config->item('dropdown_choose_null');
		}

		$this->_build_tree_select(Files::folder_tree());

		return $choices + $this->_folder_list;

	}

	private function _build_tree_select($folders, $selected = 0, $level = 0)
	{

		foreach ($folders AS $folder)
		{
			if ($level > 0)
			{
				$indent = '';
				for ($i = 0; $i < ($level*2); $i++)
				{
					$indent .= '&nbsp;';
				}

				$indent .= '-&nbsp;';
			}

			$this->_folder_list[$folder['id']] = $indent . $folder['name']; 
			
			if ( isset( $folder["children"] ) )
			{				
				 $this->_build_tree_select($folder['children'], $selected, $level + 1) ;
			}

		}
	}
	
}
