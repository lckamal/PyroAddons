<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show featured product icons in your site with a widget.
 *
 *
 * @author  lckamal
 * @package Modules\Holidays\Widgets
 */
class Widget_Cat_list extends Widgets
{

	public $author = 'Kamal Lamichhane';

	public $website = 'http://www.lkamal.com.np';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Category List'
	);

	public $description = array(
		'en' => 'Category list in vertical menu.',
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
		$this->load->model('product/product_category_m');
		$product_cat = $this->product_category_m->where('category_status', 1)->limit($limit)->get_all();	
		return array('cat_widget' => $product_cat);
	}

}
