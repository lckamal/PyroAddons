<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show partners icons in your site with a widget.
 *
 * Intended for use on cms pages. Usage :
 * on a CMS page add:
 *
 *     {widget_area('name_of_area')}
 *
 * 'name_of_area' is the name of the widget area you created in the  admin
 * control panel
 *
 * @author  lckamal
 * @package Modules\Holidays\Widgets
 */
class Widget_Featured extends Widgets
{

	public $author = 'Kamal Lamichhane';

	public $website = 'http://www.lkamal.com.np';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Featured'
	);

	public $description = array(
		'en' => 'Featured Services.',
	);

	// build form fields for the backend
	// MUST match the field name declared in the form.php file
	public $fields = array(
		array(
			'field' => 'limit',
			'label' => 'Number of icons to display at once',
		)
	);

	public function form($options)
	{
		$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 4;

		return array(
			'options' => $options
		);
	}

	public function run($options)
	{
        $this->load->model('service/service_m');
		// $featured = $this->featured_m->get_featured();	
		$featured['limit'] = ( !empty($options['limit'])) ? $options['limit'] : 4;
		$featured = $this->service_m->limit($featured['limit'])->where('status','1')->get_many_by('featured', '1');
		//var_dump($featured);die();
		return array('featured_widget' => $featured);

	}

}
