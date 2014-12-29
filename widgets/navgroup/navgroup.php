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

class Widget_Navgroup extends Widgets
{
	public $title		= array(
		'en' => 'Navigation groups',
	);
	public $description	= array(
		'en' => 'Display child navigation as a group .',
	);

	public $author		= 'lckamal';
	public $website		= 'http://lkamal.com.np';
	public $version		= '1.0';

	public $fields = array(
		array(
			'field' => 'navigation_group',
			'label' => 'Navigation Group',
		),array(
			'field' => 'limit',
			'label' => 'Limit',
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
		class_exists("Navigation_m") or $this->load->model('navigation/navigation_m');
		$nav_groups = $this->navigation_m->get_groups();
		$nav_options = array();
		if(count($nav_groups) > 0)
		{
			foreach($nav_groups as $nav)
			{
				$nav_options[$nav->abbrev] = $nav->title;
			}
		}

		!empty($options['navigation_group']) OR $options['navigation_group'] = 'header';
		!empty($options['row_class']) OR $options['row_class'] = 'row';
		!empty($options['col_class']) OR $options['col_class'] = 'col-sm-3';
		!empty($options['limit']) OR $options['limit'] = '4';

		// return the good stuff
		return array(
			'options'	=> $options,
			'nav_options'	=> $nav_options,
		);
	}

	public function run($options)
	{
		$this->load->model('navigtion/navigation_m');
		$all_navigations = $this->navigation_m->get_link_tree($options['navigation_group']);
		if(!$all_navigations) return null;

		$nav_count = 0;
		foreach ($all_navigations as $key => $nav) 
		{
			if(isset($nav['children']) && count($nav['children']) > 0)
			{
				$nav_count ++;
				$navigations[] = $nav;				
				if($nav_count == $options['limit']){ break; }
			}
		}

		return array(
			'options'	=> $options,
			'widget_navigations'	=> $navigations,
		);
	}
}
