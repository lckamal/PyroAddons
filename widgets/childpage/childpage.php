<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 		PyroCMS
 * @subpackage 		Slider Widget
 * @author			lckamal
 *
 * Display a slider configured in the Sliders Module
 *
 * Usage : on a CMS page add {widget_area('name_of_area')}
 * where 'name_of_area' is the name of the widget area you created in the admin control panel
 */

class Widget_Childpage extends Widgets
{
	public $title		= array(
		'en' => 'Child pages',
	);
	public $description	= array(
		'en' => 'Display Child pages of a page as widget.',
	);

	public $author		= 'lckamal';
	public $website		= 'http://lkamal.com.np';
	public $version		= '1.0';

	public $fields = array(
		array(
			'field' => 'page_id',
			'label' => 'Page',
		),array(
			'field' => 'limit',
			'label' => 'Limit',
		),array(
			'field' => 'read_more',
			'label' => 'Read more link.',
		),array(
			'field' => 'layout',
			'label' => 'Layout',
		),array(
			'field' => 'row_class',
			'label' => 'Row Class',
		),array(
			'field' => 'col_class',
			'label' => 'Column class',
		)
	);


	public function form($options)
	{
		class_exists("Page_m") or $this->load->model('pages/page_m');
		$page_options = $this->_build_tree_select(array('current_parent' => $options['page_id']));
		$layout_options = array('grid' => 'Bootstrip Grid', 'accordion' => 'Bootstrip Accordion');

		!empty($options['page_id']) OR $options['page_id'] = false;
		!empty($options['read_more']) OR $options['read_more'] = false;
		!empty($options['layout']) OR $options['layout'] = 'grid';
		!empty($options['row_class']) OR $options['row_class'] = 'row';
		!empty($options['col_class']) OR $options['col_class'] = 'col-xs-4';
		!empty($options['limit']) OR $options['limit'] = '4';

		// return the good stuff
		return array(
			'options'	=> $options,
			'page_options'	=> $page_options,
			'layout_options'	=> $layout_options,
		);
	}

	public function run($options)
	{
		$this->load->model('pages/page_m');
		$page = $this->page_m->get($options['page_id']);
		$pages = $this->page_m->order_by('order')->where('parent_id', $options['page_id'])->limit($options['limit'])->dropdown('id', 'title');
		$pagedata = null;
		if($pages){
			foreach ($pages as $page_id => $page_title) {
				$pagedata[] = $this->page_m->get($page_id);
			}
		}
		return array(
			'options'	=> $options,
			'childpages'	=> $pagedata,
		);
	}

	private function _build_tree_select($params)
	{
		// isset($params['select']) ? ci()->db->select($params['select']) : '';
		// isset($params['join']) ? ci()->db->join($params['join']['table'], $params['join']['condition'], $params['join']['type']) : '';
		// isset($params['where']) ? ci()->db->where($params['where']) : array();
	
		// unset($params['select']);
		// unset($params['where']);
		// unset($params['join']);
		
		$params = array_merge(array(
			'tree'			=> array(),
			'parent_id'		=> 0,
			'current_parent'=> 0,
			'current_id'	=> 0,
			'level'			=> 0
		), $params);

		extract($params);

		if ( ! $tree)
		{
			if ($pages = ci()->db->select('pages.id, pages.parent_id, pages.title')->get('pages')->result())
			{
				foreach($pages as $page)
				{
					$tree[$page->parent_id][] = $page;
				}
			}
		}

		if ( ! isset($tree[$parent_id]))
		{
			return;
		}

		$html = '';
		
		foreach ($tree[$parent_id] as $item)
		{
			if ($current_id == $item->id)
			{
				continue;
			}

			$html .= '<option value="' . $item->id . '"';
			$html .= $current_parent == $item->id ? ' selected="selected">': '>';

			if ($level > 0)
			{
				for ($i = 0; $i < ($level*2); $i++)
				{
					$html .= '&nbsp;';
				}

				$html .= '-&nbsp;';
			}

			$html .= $item->title . '</option>';

			$html .= $this->_build_tree_select(array(
				'tree'			=> $tree,
				'parent_id'		=> (int) $item->id,
				'current_parent'=> $current_parent,
				'current_id'	=> $current_id,
				'level'			=> $level + 1
			));
		}

		return $html;
	}
}
