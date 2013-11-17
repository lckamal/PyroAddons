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
class Widget_Partners extends Widgets
{

	public $author = 'Kamal Lamichhane';

	public $website = 'http://www.lkamal.com.np';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Partners'
	);

	public $description = array(
		'en' => 'Display Associate Partners with icons',
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
        $this->load->driver('Streams');
		
		$params = array(
            'stream' => 'partners',
            'namespace' => 'partner',
            'paginate' => 'no',
            'where' => "status = '1'"
        );

        $partners = $this->streams->entries->get_entries($params);
		if(empty($partners)) return FALSE;
		$partners['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 5;
		// returns the variables to be used within the widget's view
		return array('partner_widget' => $partners);
	}

}
