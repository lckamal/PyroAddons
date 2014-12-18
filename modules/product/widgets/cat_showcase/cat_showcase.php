<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show featured category icons in your site with a widget.
 *
 * @author  lckamal
 * @package Modules\Holidays\Widgets
 */
class Widget_Cat_showcase extends Widgets
{

	public $author = 'Kamal Lamichhane';

	public $website = 'http://www.lkamal.com.np';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Featured Category Showcase'
	);

	public $description = array(
		'en' => 'Display featured category icons as a showcase.',
	);

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
        class_exists('Product_category_m') OR $this->load->model('product/product_category_m');
		$cat_showcase = $this->product_category_m->select('category_title, category_slug, category_icon, category_image')->where('category_featured', 1)->limit($options['limit'])->get_all();	
		return array('showcase_widget' => $cat_showcase);
	}

}
