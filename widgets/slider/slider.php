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

class Widget_Slider extends Widgets
{
	public $title		= array(
		'en' => 'Slider',
	);
	public $description	= array(
		'en' => 'Display Images to Slide on your website.',
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
		)
	);


	public function form($options)
	{
		// load classes, libs
		$this->load->library(array('files/files'));
		$this->load->model(array(
			'files/file_folders_m',
			'files/file_m',
		));

		// get settings
		$select_folder = $this->file_folders_m->dropdown('id', 'name');


		// option defaults
		!empty($options['captions']) OR $options['captions'] = 'false';
		!empty($options['folder_id']) OR $options['folder_id'] = null;

		$this->pyrocache->delete_all('slider_m');
		// return the good stuff
		return array(
			'options'	=> $options,
			'folder_options'	=> $select_folder,
		);
	}


	public function run($options)
	{
		// load classes, libs
		$this->load->model(array(
			'files/file_folders_m',
			'files/file_m',
		));
		$images = $this->file_m->get_many_by('folder_id', $options['folder_id']);

		// return vars
		return array(
			'options'	=> $options,
			'images'	=> $images,
		);
	}
}
