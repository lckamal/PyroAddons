<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * faq module
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  faq Module
 */
class Category_m extends MY_Model {
	
	public $site_lang = 'en';
	protected $_folder_list = array();
	public function __construct()
	{		
		parent::__construct();
		
		$this->_table = 'category';
        $this->primary_key = 'id';
        $this->site_lang = Settings::get('site_lang');
        
	}

	/**
	 * Build a multi-array of parent > children.
	 *
	 * @return array An array representing the category tree.
	 */
	public function get_category_tree($options = array())
	{
		$lang = isset($options['lang']) ? $options['lang'] : AUTO_LANGUAGE;
		$where = array('category_lang' => $lang);
		if(isset($options['category_status'])){
			$where['category_status'] = $options['category_status'];
		}
		$all_pages = $this->db
			->select('id, category_id, category_parent, category_title, category_status')
			->order_by('`ordering_count`')
			->where($where)
			->get('category')
			->result_array();

		// First, re-index the array.
		foreach ($all_pages as $row)
		{
			$pages[$row['id']] = $row;
		}

		unset($all_pages);

		// Build a multidimensional array of parent > children.
		foreach ($pages as $row)
		{
			if (array_key_exists($row['category_parent'], $pages))
			{
				// Add this page to the children array of the parent page.
				$pages[$row['category_parent']]['children'][] =& $pages[$row['id']];
			}

			// This is a root page.
			if ($row['category_parent'] == 0)
			{
				$page_array[] =& $pages[$row['id']];
			}
		}
		return $page_array;
	}

	/**
	 * Set the parent > child relations and child order
	 *
	 * @param array $page
	 */
	public function _set_children($category)
	{
		if (isset($category['children']))
		{
			foreach ($category['children'] as $i => $child)
			{
				$child_id = (int) str_replace('category_', '', $child['id']);
				$category_id = (int) str_replace('category_', '', $category['id']);

				$this->update_by(array('category_id' => $child_id), array('category_parent' => $category_id, '`ordering_count`' => $i));

				//repeat as long as there are children
				if (isset($child['children']))
				{
					$this->_set_children($child);
				}
			}
		}
	}

	/**
	 * Optgroup select function
	 * works for 2 level only
	 *
	 * @param $categories
	 *
	 * @return string
	 */
	public function _build_optgroup_select($categories= array())
	{
		$folder_list = array();
		$html = '';
		foreach ($categories AS $category)
		{
			if(isset($category["children"])){
				$html .= '<optgroup label="'.$category['category_title'].'">';
					foreach ($category["children"] as $children) {
						$html .= '<option>'.$children['category_title'].'</option>';
					}
				$html .= '</optgroup>';
			}
			else{
				$html .= '<option>'.$category['category_title'].'</option>';
			}
		}
			echo $html;exit;
	}

	/**
	 * dropdown with tree select
	 */
	public function tree_dropdown()
	{
		$category_tree = $this->get_category_tree(array('category_status' => 1));
		$category_select = $this->_build_tree_select($category_tree, $selected);
		return $this->_folder_list;
	}

	/**
	 * build tree select key : value with respect to categories->children
	 */
	private function _build_tree_select($categories, $level = 0)
	{
		$folder_list = array();
		foreach ($categories AS $category)
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

			$this->_folder_list[$category['category_id']] = $indent . $category['category_title']; 
			
			if ( isset( $category["children"] ) )
			{				
				 $this->_build_tree_select($category['children'], $level + 1) ;
			}
		}
	}
}
