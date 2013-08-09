<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 		PyroCMS
 * @subpackage 		Testimonial Widget
 * @author			lckamal
 *
 * Display a slider configured in the Testimonials Module
 *
 * Usage : on a CMS page add {widget_area('name_of_area')}
 * where 'name_of_area' is the name of the widget area you created in the admin control panel
 */

class Widget_Testimonial extends Widgets
{
	public $title		= array(
		'en' => 'Testimonial',
	);
	public $description	= array(
		'en' => 'Display Testimonials selected as featured as widget.',
	);

	public $author		= 'lckamal';
	public $website		= 'http://lkamal.com.np';
	public $version		= '1.0';

	public $fields = array(
		array(
			'field' => 'limit',
			'label' => 'Number of Testimonials',
		)
	);

	public function form($options)
	{
		$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 5;

		return array(
			'options' => $options
		);
	}

	public function run($options)
	{
		$this->lang->load('testimonials/testimonials');

        $this->load->driver('Streams');

		// sets default number of posts to be shown
		$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 5;

		$params = array(
            'stream' => 'testimonials',
            'namespace' => 'testimonials',
            'paginate' => 'no',
            'where' => "featured = 'yes'",
            'limit' => 5
        );

        $testimonials = $this->streams->entries->get_entries($params);

		// returns the variables to be used within the widget's view
		return array('testimonial_widget' => $testimonials);
	}
}
