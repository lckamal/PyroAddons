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
class Widget_Service extends Widgets
{

	public $author = 'Kamal Lamichhane';

	public $website = 'http://www.lkamal.com.np';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Service'
	);

	public $description = array(
		'en' => 'Highlighting services on home page.',
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
		$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 5;

		return array(
			'options' => $options
		);
	}

	public function run($options)
	{
        $this->load->model('service/service_m');
		// $service = $this->service_m->get_service();	
		$service['limit'] = ( !empty($options['limit'])) ? $options['limit'] : 3;
		$service = $this->service_m->limit($service['limit'])->get_many_by('status', '1');
		//var_dump($service);die();
		return array('service_widget' => $service);

	}

}
