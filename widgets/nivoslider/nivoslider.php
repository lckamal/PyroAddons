<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 		PyroCMS
 * @subpackage 		Slider Widget
 * @author			lckamal
 * @website			http://lkamal.com.np
 *
 * Display a slider from files folder
 * from: http://dev7studios.com/plugins/nivo-slider/
 *
 */

class Widget_Nivoslider extends Widgets
{
	public $title		= array(
		'en' => 'Nivo Slider',
	);
	public $description	= array(
		'en' => 'Display Nivo Slide on your website.',
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
			'field' => 'theme',
			'label' => 'Theme',
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
		!empty($options['theme']) OR $options['theme'] = 'default';
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
