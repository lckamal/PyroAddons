<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Codemirror Field Type
 *
 * @package		PyroStreams
 * @author		PyroCMS Dev Team
 * @copyright	Copyright (c) 2011 - 2015, PyroCMS
 */
class Field_codemirror
{
	public $field_type_slug			= 'codemirror';
	
	public $db_col_type				= 'longtext';

	public $admin_display			= 'full';
	
	public $custom_parameters 		= array('editor_type');

	public $version					= '0.1';

	public $author					= array('name' => 'skrollx', 'url'=>'http://skrollx.com');
	
	// --------------------------------------------------------------------------

	/**
	 * Event
	 *
	 * Called before the form is built.
	 *
	 * @access	public
	 * @return	void
	 */
	public function event()
	{
		if (defined('ADMIN_THEME'))
		{
			$this->CI->type->add_misc($this->CI->type->load_view('codemirror', 'codemirror_admin', null));
		}
		else
		{
			$this->CI->type->add_misc($this->CI->type->load_view('codemirror', 'codemirror_entry_form', null));
		}
	}

	// --------------------------------------------------------------------------

	/**
	 * Pre-Ouput WYSUWYG content
	 *
	 * @access 	public
	 * @param 	string
	 * @return 	string
	 */
	public function pre_output($input, $params)
	{
		// Legacy. This was a temp fix for a few things
		// that I'm sure a few sites are utilizing.
		$input = str_replace('&#123;&#123; url:site &#125;&#125;', site_url().'/', $input);

		return $this->CI->parser->parse_string($input, array(), true);
	}

	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function form_output($data)
	{
		// Set editor type
		if (isset($data['custom']['editor_type']))
		{
			$options['class']	= $data['custom']['editor_type'].'_editor';
		}
		else
		{
			$options['class']	= 'html_editor';
		}
	
		$options['name'] 	= $data['form_slug'];
		$options['id']		= $data['form_slug'];
		$options['value']	= html_entity_decode($data['value'], null, 'UTF-8');
		
		return form_textarea($options);
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Editor Type Param
	 *
	 * Choose the type of editor.
	 */
	public function param_editor_type($value = null)
	{
		$types = array(
			'html'	=> lang('streams:codemirror.html'),
            'css'   => lang('streams:codemirror.css'),
			'js'	=> lang('streams:codemirror.js')
		);
	
		return form_dropdown('editor_type', $types, $value);
	}	
	// public function pre_save($input)
	// {
	// 	return nl2br($input);
	// }
}