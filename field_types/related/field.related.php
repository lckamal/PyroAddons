<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Page Field Type
 *
 * Choose a page from a drop down and return the page data.
 *
 * @package		PyroStreams
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Adam Fairholm
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
	
	/**
	 * create CI instance
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
	}

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
		$where = isset($field->field_data['where']) && !empty($field->field_data['where']) ? $field->field_data['where'] : array();
		$selected = $data['value'];

		if ($pages = ci()->db->where($where)->select("$table.$key_field, $table.$title_field")->get($table)->result())
		{

			foreach($pages as $page)
			{
				$html .= '<option value="' . $page->$key_field . '"';
				$html .= $selected == $page->$key_field ? ' selected="selected">': '>';
				$html .= $page->$title_field . '</option>';
				$dropdown[$page->$key_field] = $page->$title_field;
			}
		}

		//$this->CI->db->last_query();exit;
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
	public function pre_output($input, $field)
	{
		if ( ! $input or ! is_numeric($input)) return null;

		$table = isset($field['table']) ? $field['table'] : $field['field_data']['table'];
		$key_field = isset($field['field_data']['key_field']) ? $field['field_data']['key_field'] : (isset($field['key_field']) ? $field['key_field'] : 'id');
		$title_field = isset($field['field_data']['title_field']) ? $field['field_data']['title_field'] : (isset($field['title_field']) ? $field['title_field'] : 'title');
		$where = array($key_field => $input);

		// Get the page
		$page = $this->CI->db
						->limit(1)
						->select("{$key_field}, {$title_field}")
						->where($where)
						->get($table)
						->row();			
		if ( ! $page) return null;

		return $page->$title_field;
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
	public function pre_output_plugin($input, $field, $params)
	{
		if ( ! $input or ! is_numeric($input)) return null;
		
		// $table = $field['field_data']['table'];
		// $key_field = isset($params['field_data']['key_field']) ? $params['field_data']['key_field'] : 'id';
		// $title_field = isset($params['field_data']['title_field']) ? $params['field_data']['title_field'] : 'title';
		// $where = isset($params['field_data']['where']) ? $params['field_data']['where'] : array();

		// // Get the page
		// $page = $this->CI->db
		// 				->limit(1)
		// 				->select('*')
		// 				->where($key_field, $input)
		// 				->get($table)
		// 				->row();
		// if ( ! $page) return null;
		
		// return $page;	
	}

	/**
	 * Table to relate
	 *
	 * @return	string
	 */
	public function param_table( $value = '' )
	{
		return array(
			'input' 		=> form_input('table', $value),
			'instructions'	=> $this->CI->lang->line('related.table_instruction')
		);
	}

	/**
	 * Key field to select
	 *
	 * @return	string
	 */
	public function param_key_field( $value = '' )
	{
		return array(
			'input' 		=> form_input('key_field', $value),
			'instructions'	=> $this->CI->lang->line('related.key_field_instruction')
		);
	}

	/**
	 * Title field to select
	 *
	 * @return	string
	 */
	public function param_title_field( $value = '' )
	{
		return array(
			'input' 		=> form_input('title_field', $value),
			'instructions'	=> $this->CI->lang->line('related.title_field_instruction')
		);
	}
	
	/**
	 * Title field to select
	 *
	 * @return	string
	 */
	public function param_where( $value = null )
	{
		return array(
			'input' 		=> form_input('where', $value),
			'instructions'	=> $this->CI->lang->line('related.where_instruction')
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
		$table = $field->field_data['table'];
		$key_field = isset($field->field_data['key_field']) ? $field->field_data['key_field'] : 'id';
		$title_field = isset($field->field_data['title_field']) ? $field->field_data['title_field'] : 'title';
		$where = isset($field->field_data['where']) ? $field->field_data['where'] : array();

		$params = array_merge(array(
			'table' => $table, 
			'key_field' => $key_field, 
			'title_field' => $title_field, 
			'where' => $where,
		), $params);
		
		extract($params);

		$html = '';
		if ($pages = $this->CI->db->where($where)->select("{$table}.{$key_field}, {$table}.{$title_field}")->get($table)->result())
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