<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 		PyroCMS
 * @subpackage 		RSS Feed Widget
 * @author			Phil Sturgeon - PyroCMS Development Team
 *
 * Show RSS feeds in your site
 */

class Widget_News_archive extends Widgets
{
	public $title		= array(
		'en' => 'News Archive',
		'br' => 'Arquivo do News',
		'ru' => 'Ð�Ñ€Ñ…Ð¸Ð²',
	);
	public $description	= array(
		'en' => 'Display a list of old months with links to posts in those months',
		'br' => 'Mostra uma lista navegaÃ§Ã£o cronolÃ³gica contendo o Ã­ndice dos artigos publicados mensalmente',
		'ru' => 'Ð’Ñ‹Ð²Ð¾Ð´Ð¸Ñ‚ Ñ�Ð¿Ð¸Ñ�Ð¾Ðº Ð¿Ð¾ Ð¼ÐµÑ�Ñ�Ñ†Ð°Ð¼ Ñ�Ð¾ Ñ�Ñ�Ñ‹Ð»ÐºÐ°Ð¼Ð¸ Ð½Ð° Ð·Ð°Ð¿Ð¸Ñ�Ð¸ Ð² Ñ�Ñ‚Ð¸Ñ… Ð¼ÐµÑ�Ñ�Ñ†Ð°Ñ…',
	);
	public $author		= 'Phil Sturgeon';
	public $website		= 'http://philsturgeon.co.uk/';
	public $version		= '1.0';
	
	public function run($options)
	{
		$this->load->model('news/news_m');
		$this->lang->load('news/news');

		return array(
			'archive_months' => $this->news_m->get_archive_months()
		);
	}	
}