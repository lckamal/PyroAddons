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

class Widget_Image extends Widgets
{
	public $title		= array(
		'en' => 'Images',
	);
	public $description	= array(
		'en' => 'Display Images from files/images on your website.',
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
			'field' => 'row_class',
			'label' => 'Row Class',
		),array(
			'field' => 'col_class',
			'label' => 'Column class',
		),array(
			'field' => 'limit',
			'label' => 'Limit',
		)
	);


	public function form($options)
	{
		// load classes, libs
		$this->load->model('files/file_folders_m');
		// get settings
		$select_folder = $this->file_folders_m->dropdown('id', 'name');
		// option defaults
		!empty($options['row_class']) OR $options['row_class'] = 'row';
		!empty($options['col_class']) OR $options['col_class'] = 'col-xs-4';
		!empty($options['limit']) OR $options['limit'] = '3';
		!empty($options['captions']) OR $options['captions'] = 'false';
		!empty($options['folder_id']) OR $options['folder_id'] = null;
		// return the good stuff
		return array(
			'options'	=> $options,
			'folder_options'	=> $select_folder,
		);
	}

	public function run($options)
	{
		$this->load->model('files/file_m');
		$images = $this->file_m->order_by('sort')->get_many_by('folder_id', $options['folder_id']);
		return array(
			'options'	=> $options,
			'img_images'	=> $images,
		);
	}
}
