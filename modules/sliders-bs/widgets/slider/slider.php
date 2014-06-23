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
		)
	);


	public function form($options)
	{
		// load classes, libs
		$this->load->library(array('files/files'));
		$this->load->model(array(
			'sliders/slider_m',
			'files/file_folders_m',
			'files/file_m',
		));

		// get settings
		$settings = $this->slider_m->get_settings();

		// get child folders of main module folder
		$query = $this->db->order_by('sort', 'asc')->get_where('file_folders', array('parent_id' => $settings->folder_id));
		$folders = $query->result();

		// define the folder dropdown array
		$select_slider = array();
		foreach($folders as $folder)
		{
			$query = $this->db->get_where('files', array('folder_id' => $folder->id, 'type' => 'i'));
			$count = count($query->result()) > 1 ? ' ['.count($query->result()).' images]' : ' ['.count($query->result()).' image]';
			$select_slider[$folder->id] = $folder->name . $count;
		}

		// option defaults
		!empty($options['captions'])				OR $options['captions'] = 'false';

		// return the good stuff
		return array(
			'options'	=> $options,
			'select_slider'	=> $select_slider,
		);
	}


	public function run($options)
	{
		// load classes, libs
		$this->load->library(array('files/files'));
		$this->load->model(array(
			'sliders/slider_m',
			'files/file_folders_m',
			'files/file_m',
		));

		// get settings
		$settings = $this->slider_m->get_settings();

		// get slider and images
		$folder = $this->file_folders_m->get($options['slider_id']);
		$query = $this->db->order_by('sort', 'asc')->get_where('files', array('folder_id' => $settings->folder_id, 'type' => 'i'));
		$images = $query->result();

		// check that the images descriptions are valid urls
		// for($i = 0; $i < count($images); $i++)
		// {
			// $images[$i]->description = preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $images[$i]->description) ? $images[$i]->description : null;
		// }

		// return vars
		return array(
			'options'	=> $options,
			'slider'	=> $settings,
			'images'	=> $images,
		);
	}
}
