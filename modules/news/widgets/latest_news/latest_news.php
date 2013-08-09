<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 		PyroCMS
 * @subpackage 		Latest news Widget
 * @author			Patrick Patons
 *
 * Show Latest news in your site with a widget. Intended for use on cms pages
 *
 * Usage : on a CMS page add {widget_area('name_of_area')}
 * where 'name_of_area' is the name of the widget area you created in the admin control panel
 */

class Widget_Latest_news extends Widgets
{
	public $title		= array(
		'en' => 'News posts',
		'br' => 'Artigos recentes do News',
		'ru' => 'ÐŸÐ¾Ñ�Ð»ÐµÐ´Ð½Ð¸Ðµ Ð·Ð°Ð¿Ð¸Ñ�Ð¸',
	);
	public $description	= array(
		'en' => 'Display latest news posts with a widget',
		'br' => 'Mostra uma lista de navegaÃ§Ã£o para abrir os Ãºltimos artigos publicados no News',
		'ru' => 'Ð’Ñ‹Ð²Ð¾Ð´Ð¸Ñ‚ Ñ�Ð¿Ð¸Ñ�Ð¾Ðº Ð¿Ð¾Ñ�Ð»ÐµÐ´Ð½Ð¸Ñ… Ð·Ð°Ð¿Ð¸Ñ�ÐµÐ¹ Ð±Ð»Ð¾Ð³Ð° Ð²Ð½ÑƒÑ‚Ñ€Ð¸ Ð²Ð¸Ð´Ð¶ÐµÑ‚Ð°',
	);
	public $author		= 'Patrick Patons';
	public $website		= 'http://github.com/ppatons/';
	public $version		= '1.0';

	// build form fields for the backend
	// MUST match the field name declared in the form.php file
	public $fields = array(
		array(
			'field' => 'limit',
			'label' => 'Number of posts',
		)
	);

	public function form($options)
	{
		!empty($options['limit']) OR $options['limit'] = 5;

		return array(
			'options' => $options
		);
	}

	public function run($options)
	{
		// load the news module's model
		class_exists('News_m') OR $this->load->model('news/news_m');

		// sets default number of posts to be shown
		empty($options['limit']) AND $options['limit'] = 5;

		// retrieve the records using the news module's model
		$news_widget = $this->news_m->limit($options['limit'])->get_many_by(array('status' => 'live'));

		// returns the variables to be used within the widget's view
		return array('news_widget' => $news_widget);
	}
}