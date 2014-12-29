<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show newss icons in your site with a widget.
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
class Widget_Newsticker extends Widgets
{

	public $author = 'Kamal Lamichhane';

	public $website = 'http://www.lkamal.com.np';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'News ticker'
	);

	public $description = array(
		'en' => 'Display News with news ticker',
	);

	// build form fields for the backend
	// MUST match the field name declared in the form.php file
	public $fields = array(
		array(
			'field' => 'limit',
			'label' => 'Number of news to display from each category.',
		),
		array(
			'field' => 'categories',
			'label' => 'Categories to display news from',
		),
		array(
			'field' => 'type',
			'label' => 'Type of news display, eg. scrolling or lists',
		)
	);

	public function form($options)
	{
		$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 2;
		$options['type'] = ( ! empty($options['type'])) ? $options['type'] : 'list';
		$options['categories'] = ( ! empty($options['categories'])) 
			? explode('|', $options['categories']) 
			: null;

		$this->load->model('news/news_category_m');
		$cat_options = $this->news_category_m->dropdown('id', 'news_category_title');
		
		return array(
			'options' => $options,
			'cat_options' => $cat_options
		);
	}

	public function run($options)
	{
        $this->load->driver('Streams');
		class_exists('News_m') or $this->load->model('news/news_m');
		class_exists('News_category_m') or $this->load->model('news/news_category_m');
		$catoptions = explode('|', $options['categories']);
		$limit = ( ! empty($options['limit'])) ? $options['limit'] : 5;
		
		if(count($catoptions) > 0)
		{
			$this->db->where_in('news_category_id', array_values($catoptions));
		}
		$newss = $this->news_m->limit($limit)->get_all();

		if(empty($newss)) return FALSE;
		return array('news_widget' => $newss, 'limit' => $limit, 'options' => $options);
	}
	
	/**
     * save()
     */
    public function save($options)
    {
       if(isset($options)){
       	$cat_options = $options['categories'];
		$cat = null;
        if(is_array($cat_options))
		{
			$cats = implode('|', $cat_options);
		}
		$options['categories'] = $cats;
       }
       return $options;
    }

}
