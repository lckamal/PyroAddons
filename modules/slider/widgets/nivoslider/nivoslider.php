<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 		Slider Module
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
			'field' => 'category',
			'label' => 'Slider Category',
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
		$this->load->model('slider/slider_category_m');
		$select_cat = $this->slider_category_m->dropdown('id', 'category_title');
		!empty($options['captions']) OR $options['captions'] = 'false';
		!empty($options['folder_id']) OR $options['folder_id'] = null;
		!empty($options['theme']) OR $options['theme'] = 'default';
		!empty($options['settings']) OR $options['settings'] = '';

		return array(
			'options'	=> $options,
			'select_cat'	=> $select_cat,
		);
	}


	public function run($options)
	{
		$this->load->driver('Streams');
		$params = array(
            'stream' => 'sliders',
            'namespace' => 'slider',
            'where' => "`category` = {$options['category']}",
            'order_by' => 'ordering_count'

        );

        $sliders = $this->streams->entries->get_entries($params);
		//var_dump($sliders['entries']);exit;
		// return vars
		return array(
			'options'	=> $options,
			'sliders'	=> $sliders,
		);
	}
}
