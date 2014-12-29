<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show featured product icons in your site with a widget.
 *
 *
 * @author  lckamal
 * @package Modules\Holidays\Widgets
 */
class Widget_Service_list extends Widgets
{

	public $author = 'Kamal Lamichhane';

	public $website = 'http://www.lkamal.com.np';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Service List'
	);

	public $description = array(
		'en' => 'Service list in vertical menu.',
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
		$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 8;

		return array(
			'options' => $options
		);
	}


	public function run($options)
	{
		$limit = ( ! empty($options['limit'])) ? $options['limit'] : 8;
		$this->load->model('service/service_m');
		$service_list = $this->service_m->where('status', 1)->limit($limit)->get_all();	
		return array('service_widget' => $service_list);
	}

}
