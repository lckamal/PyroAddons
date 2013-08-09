<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Page Field Type
 *
 * Choose a page from a drop down and return the page data.
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Adam Fairholm
 * @link 		https://github.com/adamfairholm/PyroStreams-Page-Field-Type
 *
 *	Usage:
 *	'name' => 'Tout/Trek Styles',
 *      'slug' => 'tour_styles',
 *      'type' => 'page',
 *      'extra' => array(
 *           			'select' => 'dpf.page_type',
 *           			'where' => array('dpf.page_type' => 'tour_styles'),
 *           			'join' => array('table' => 'def_page_fields dpf', 'condition' => 'pages.id = dpf.id', 'type' => 'inner'),
 *       			),
 */
class Field_related
{
	public $field_type_name			= 'Related';
	 
	public $field_type_slug			= 'related';
	
	public $db_col_type				= 'int';

	public $version					= '1.0';
	
	public $custom_parameters		= array('table','key_field','title_field', 'where');

	public $author					= array(
										'name' => 'Kamal Lamichhane',
										'url' => 'http://www.lkamal.com.np');
	
	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @access	public
	 * @param	array
	 * @param	array
	 * @param	obj
	 * @return	string
	 */
	public function form_output($data, $params, $field)
	{	
		$html = '<select name="'.$data['form_slug'].'" id="'.$data['form_slug'].'">';
		
		if ($field->is_required == 'no')
		{
			$html .= '<option value="">'.get_instance()->config->item('dropdown_choose_null').'</option>';
		}
		
		$table = $field->field_data['table'];
		$key_field = isset($field->field_data['key_field']) ? $field->field_data['key_field'] : 'id';
		$title_field = isset($field->field_data['title_field']) ? $field->field_data['title_field'] : 'title';
		$where = isset($field->field_data['where']) ? $field->field_data['where'] : array();
		$selected = $data['value'];

		if ($pages = $this->CI->db->select("{$table}.{$key_field}, {$table}.{$title_field}")->get($table)->result())
		{
			

			foreach($pages as $page)
			{
				$html .= '<option value="' . $page->$key_field . '"';
				$html .= $selected == $page->$key_field ? ' selected="selected">': '>';
				$html .= $page->$title_field . '</option>';
				//$dropdown[$page->$key_field] = $page->$title_field;
			}
		}
		//echo $this->CI->db->last_query();exit;
		$html .= '</select>';
		
		return $html;
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Output for Admin
	 *
	 * @access 	public
	 * @param	string
	 * @param	array
	 * @return	string
	 */
	public function pre_output($input, $params)
	{
		if ( ! $input or ! is_numeric($input)) return null;
		
		// Get the page
		$page = $this->CI->db
						->limit(1)
						->select('id, title')
						->where('id', $input)
						->get('pages')
						->row();

		if ( ! $page) return null;
				
		return '<a href="'.site_url('admin/pages/edit/'.$page->id).'">'.$page->title.'</a>';
	}

	// --------------------------------------------------------------------------

	/**
	 * Tag output variables
	 *
	 * @access 	public
	 * @param	string
	 * @param	array
	 * @return	array
	 */
	public function pre_output_plugin($input, $params)
	{
		if ( ! $input or ! is_numeric($input)) return null;
	
		// Get the page
		$page = $this->CI->db
						->limit(1)
						->select('uri, slug, title, id, status')
						->where('id', $input)
						->get('pages')
						->row();

		if ( ! $page) return null;
		
		$this->CI->load->helper('url');
		
		// Is this the current one?
		$current = ($page->uri == $this->CI->uri->uri_string()) ? true : false;

		return array(
			'link'		=> site_url($page->uri),
			'slug'		=> $page->slug,
			'title'		=> $page->title,
			'id'		=> $page->id,
			'status'	=> $page->status,
			'current'	=> $current
		);		
	}

	// --------------------------------------------------------------------------

	/**
	 * Tree select function
	 *
	 * Creates a tree to form select.
	 *
	 * This originally appears in the PyroCMS navigation
	 * admin controller, but we need it here so here it is.
	 *
	 * @access 	private
	 * @param	array
	 * @return	array
	 */
	private function _build_tree_select($params)
	{
		$params = array_merge(array(
			'table' => 'pages', 
			'key_field' => 'id', 
			'title_field' => 'title', 
			'where' => array(),
		), $params);
		
		extract($params);

		$html = '';
		if ($pages = $this->CI->db->select("{$table}.{$key_field}, {$table}.{$title_field}")->get($table)->result())
		{

			foreach($pages as $page)
			{
				$html .= '<option value="' . $page->$key_field . '"';
				$html .= $selected == $page->$key_field ? ' selected="selected">': '>';
				$html .= $page->$title_field . '</option>';
				//$dropdown[$page->$key_field] = $page->$title_field;
			}
		}
		

		return $html;
	}
	
}