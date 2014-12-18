<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show featured product icons in your site with a widget.
 *
 *
 * @author  lckamal
 * @package Modules\Holidays\Widgets
 */
class Widget_Product extends Widgets
{

	public $author = 'Kamal Lamichhane';

	public $website = 'http://www.lkamal.com.np';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Featured Products'
	);

	public $description = array(
		'en' => 'Our Featured Products.',
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
		$this->load->model('product/product_m');
		$featured_products = $this->product_m->get_many_by('product_featured', 1);	
		return array('product_widget' => $featured_products);
	}

}
