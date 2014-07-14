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

class Widget_Flexslider extends Widgets
{
	public $title		= array(
		'en' => 'Flex Slider',
	);
	public $description	= array(
		'en' => 'Display Images with Flex Slide as a widget on your website.',
	);

	public $author		= 'lckamal';
	public $website		= 'http://lkamal.com.np';
	public $version		= '1.0';

	public $fields = array(
		array(
			'field' => 'captions',
			'label' => 'Captions',
		),array(
			'field' => 'folder_id',
			'label' => 'Folder',
		),array(
			'field' => 'settings',
			'label' => 'Settings',
		)
	);


	public function form($options)
	{
		$this->load->model('files/file_folders_m');
		$select_folder = $this->file_folders_m->dropdown('id', 'name');
		!empty($options['captions']) OR $options['captions'] = 'false';
		!empty($options['folder_id']) OR $options['folder_id'] = null;
		!empty($options['settings']) OR $options['settings'] = '';

		return array(
			'options'	=> $options,
			'folder_options'	=> $select_folder,
		);
	}


	public function run($options)
	{
		$this->load->model('files/file_m');
		$images = $this->file_m->order_by('sort')->get_many_by('folder_id', $options['folder_id']);
		// return vars
		return array(
			'options'	=> $options,
			'images'	=> $images,
		);
	}
}
