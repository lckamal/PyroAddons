<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Excerpt Plugin
 *
 * extended pyrocms uri plugin
 *
 * @author		Kamal Lamichhane
 * @package		access-himalaya\Addon\Plugins
 * @copyright	Copyright (c) 2013, lkamal.com.np
 */
class Plugin_Excerpt extends Plugin
{
	public $version = '1.0.0';

	public $name = array(
		'en'	=> 'Excerpt'
	);

	public $description = array(
		'en'	=> 'Excerpt plugin to display short description with read more link.'
	);

	/**
	 * Returns a PluginDoc array that PyroCMS uses 
	 * to build the reference in the admin panel
	 *
	 * @return array
	 */
	public function _self_doc()
	{
		$info = array(
			'excerpt' => array(
				'description' => array(
					'en' => 'Excerpt plugin to display short description with read more link.'
				),
				'single' => true,
				'double' => false,
				'variables' => '',
				'attributes' => array(
					'text' => array(
						'type' => 'text',
						'flags' => '',
						'default' => '',
						'required' => true,
					),
					'word_count' => array(
						'type' => 'text',
						'flags' => '',
						'default' => '20',
						'required' => false
					),
					'url' => array(
						'type' => 'text',
						'flags' => '',
						'default' => '',
						'required' => false
					),
					'show_link' => array(
						'type' => 'text',
						'flags' => '',
						'default' => 'true',
						'required' => false
					),
					'more_text' => array(
						'type' => 'text',
						'flags' => '',
						'default' => 'read&nbsp;more..',
						'required' => false
					),
					'link_class' => array(
						'type' => 'text',
						'flags' => '',
						'default' => '',
						'required' => false
					)
				),
			),
		);
	
		return $info;
	}

	/**
	 * Show read mroe text with link
	 *
	 * Usage:
	 * {{ excerpt:more }}
	 *
	 * @return string
	 */
	function excerpt()
	{
		$text = strip_tags($this->attribute('text'));
		$word_count = $this->attribute('word_count');
		$url = $this->attribute('url', site_url());
		$show_link = $this->attribute('show_link', false);
		$more_text = $this->attribute('more_text', 'read&nbsp;more..');
		$link_class = $this->attribute('link_class', '');
		
		//$excerpt = preg_replace("/^\W*((\w[\w'-]*\b\W*){1,$word_count}).*/ms", '\\1', $text);
		$wordArray = explode(' ', $text);

	    if( sizeof($wordArray) > $word_count )
	    {
	        $wordArray = array_slice($wordArray, 0, $word_count);
	        $text = implode(' ', $wordArray);
	        
			$text .= '... ';
	        if($show_link == 'true')
	        {
	        	$text .= '<a class="'.$link_class.'" href="'.site_url($url).'">'.$more_text.'</a>';
	        }
	    }
		
		// if( str_word_count($text) !== str_word_count($excerpt) && $show_link == 'true' ) {
	    	// $excerpt .= '... <a class='.$link_class.' href='.$url.'>'.$more_text.'</a>';
		// }
		return $text;
	}
}

/* End of file example.php */