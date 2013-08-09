<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_News extends Module {

	public $version = '1.2';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'News',
				'ar' => 'Ø§Ù„Ù…Ø¯ÙˆÙ‘Ù†Ø©',
				'el' => 'Î™ÏƒÏ„Î¿Î»ÏŒÎ³Î¹Î¿',
				'br' => 'News',
				'he' => '×‘×œ×•×’',
				'lt' => 'Newsas',
				'ru' => 'Ð‘Ð»Ð¾Ð³'
			),
			'description' => array(
				'en' => 'Post news entries.',
				'nl' => 'Post nieuwsartikelen en newss op uw site.',
				'es' => 'Escribe entradas para los artÃ­culos y news (web log).', #update translation
				'fr' => 'Envoyez de nouveaux posts et messages de news.', #update translation
				'de' => 'VerÃ¶ffentliche neue Artikel und News-EintrÃ¤ge', #update translation
				'pl' => 'Postuj nowe artykuÅ‚y oraz wpisy w newsu', #update translation
				'br' => 'Escrever publicaÃ§Ãµes de news',
				'zh' => 'ç™¼è¡¨æ–°è�žè¨Šæ�¯ã€�éƒ¨è�½æ ¼æ–‡ç« ã€‚', #update translation
				'it' => 'Pubblica notizie e post per il news.', #update translation
				'ru' => 'Ð£Ð¿Ñ€Ð°Ð²Ð»ÐµÐ½Ð¸Ðµ Ð·Ð°Ð¿Ð¸Ñ�Ñ�Ð¼Ð¸ Ð±Ð»Ð¾Ð³Ð°.',
				'ar' => 'Ø£Ù†Ø´Ø± Ø§Ù„Ù…Ù‚Ø§Ù„Ø§Øª Ø¹Ù„Ù‰ Ù…Ø¯ÙˆÙ‘Ù†ØªÙƒ.',
				'cs' => 'Publikujte novÃ© Ä�lÃ¡nky a pÅ™Ã­spÄ›vky na news.', #update translation
				'sl' => 'Objavite news prispevke',
				'fi' => 'Kirjoita uutisartikkeleita tai newsi artikkeleita.', #update translation
				'el' => 'Î”Î·Î¼Î¹Î¿Ï…Ï�Î³Î®ÏƒÏ„Îµ Î¬Ï�Î¸Ï�Î± ÎºÎ±Î¹ ÎµÎ³Î³Ï�Î±Ï†Î­Ï‚ ÏƒÏ„Î¿ Î¹ÏƒÏ„Î¿Î»ÏŒÎ³Î¹Î¿ ÏƒÎ±Ï‚.',
				'he' => '× ×™×”×•×œ ×‘×œ×•×’',
				'lt' => 'RaÅ¡ykite naujienas bei news\'o Ä¯raÅ¡us.',
				'da' => 'Skriv newsindlÃ¦g'
			),
			'frontend'	=> TRUE,
			'backend'	=> TRUE,
			'skip_xss'	=> TRUE,
			'menu'		=> 'content',

			'roles' => array(
				'put_live', 'edit_live', 'delete_live'
			),
			
			'sections' => array(
			    'posts' => array(
				    'name' => 'news_posts_title',
				    'uri' => 'admin/news',
				    'shortcuts' => array(
						array(
					 	   'name' => 'news_create_title',
						    'uri' => 'admin/news/create',
						    'class' => 'add'
						),
					),
				),
				'categories' => array(
				    'name' => 'cat_list_title',
				    'uri' => 'admin/news/categories',
				    'shortcuts' => array(
						array(
						    'name' => 'cat_create_title',
						    'uri' => 'admin/news/categories/create',
						    'class' => 'add'
						),
				    ),
			    ),
		    ),
		);
	}

	public function install()
	{
		$this->dbforge->drop_table('news_categories');
		$this->dbforge->drop_table('news');

		$news_categories = "
			CREATE TABLE " . $this->db->dbprefix('news_categories') . " (
			  `id` int(11) NOT NULL auto_increment,
			  `slug` varchar(20) collate utf8_unicode_ci NOT NULL default '',
			  `title` varchar(20) collate utf8_unicode_ci NOT NULL default '',
			  PRIMARY KEY  (`id`),
			  UNIQUE KEY `slug - unique` (`slug`),
			  UNIQUE KEY `title - unique` (`title`),
			  KEY `slug - normal` (`slug`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='News Categories.';
		";

		$news = "
			CREATE TABLE " . $this->db->dbprefix('news') . " (
			  `id` int(11) NOT NULL auto_increment,
			  `title` varchar(100) collate utf8_unicode_ci NOT NULL default '',
			  `slug` varchar(100) collate utf8_unicode_ci NOT NULL default '',
			  `category_id` int(11) NOT NULL,
			  `attachment` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  `intro` text collate utf8_unicode_ci NOT NULL,
			  `body` text collate utf8_unicode_ci NOT NULL,
			  `parsed` text collate utf8_unicode_ci NOT NULL,
			  `keywords` varchar(32) NOT NULL default '',
			  `author_id` int(11) NOT NULL default '0',
			  `created_on` int(11) NOT NULL,
			  `updated_on` int(11) NOT NULL default 0,
              `comments_enabled` INT(1)  NOT NULL default '1',
			  `status` enum('draft','live') collate utf8_unicode_ci NOT NULL default 'draft',
			  `type` set('html','markdown','wysiwyg-advanced','wysiwyg-simple') collate utf8_unicode_ci NOT NULL,
			  PRIMARY KEY  (`id`),
			  UNIQUE KEY `title` (`title`),
			  KEY `category_id - normal` (`category_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='News posts.';
		";

		if ($this->db->query($news_categories) && $this->db->query($news))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		//it's a core module, lets keep it around
		return FALSE;
	}

	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		/**
		 * Either return a string containing help info
		 * return "Some help info";
		 *
		 * Or add a language/help_lang.php file and
		 * return TRUE;
		 *
		 * help_lang.php contents
		 * $lang['help_body'] = "Some help info";
		*/
		return TRUE;
	}
}

/* End of file details.php */