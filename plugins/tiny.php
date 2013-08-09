<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * TinyURL Plugin
 *
 * Shorten Long Url
 *
 * @author		Jonathan Fontes
 * @package		PyroCMS\Addon\Plugins
 * @copyright	MIT
 */
class Plugin_TinyUrl extends Plugin
{
	public $version = '1.0.1';

	public $name = array(
		'en'	=> 'Tiny url',
		'pt'	=> 'Tiny url'
	);

	public $description = array(
		'en'	=> 'Short url with Tiny url service',
		'pt'	=> 'Converte grandes urls em pequenos urls.'
	);

	/**
	 * Returns a PluginDoc array that PyroCMS uses 
	 * to build the reference in the admin panel
	 *
	 * All options are listed here but refer 
	 * to the Blog plugin for a larger example
	 *
	 * @return array
	 */
	public function _self_doc()
	{
		$info = array(
			'url' => array(
				'description' => array(// a single sentence to explain the purpose of this method
					'en' => 'shortten url and return'
				),
				'single' => true,// will it work as a single tag?
				'double' => false,// how about as a double tag?
				'variables' => '',// list all variables available inside the double tag. Separate them|like|this
				'attributes' => array(
					'get' => array(// this is the name="World" attribute
						'type' => 'url',// Can be: slug, number, flag, text, array, any.
						'flags' => '',// flags are predefined values like asc|desc|random.
						'default' => 'current uri',// this attribute defaults to this if no value is given
						'required' => true,// is this attribute required?
					),
					'tag' => array(
						'type' => 'boolean',
						'flags' => '1|0',
						'default'=>'1',
						'required'=>true
					)
				),
			),
		);
	
		return $info;
	}

	/**
	 * Get
	 *
	 * Usage:
	 * {{ tiny:url get="http://www.google.com" }}
	 *
	 * @return string
	 */
	function url()
	{

		// We have cache about this url ?!
		$cached = $this->pyrocache->get('plugin_tinyurl');

		/* Cache ? */
		if( $cached !== FALSE )
		{
			/* Yes, returned */
			return $cached;
		}

		$url = $this->attribute('get', current_url());

		$url_tag = $this->attribute('tag', 1);

		$url = $this->_getTinyUrl($url);
		
		if( $url_tag == true ) return '<a href="' . $url . '" alt="tiny url">' . $url . '</a>';

		/* Write cache to not request the same URL : nicer! ;) */
		$this->pyrocache->write($url, 'plugin_tinyurl');

		return $url;
	}

	private function _getTinyUrl($url)
	{
		return file_get_contents("http://tinyurl.com/api-create.php?url=".$url);
	}
}

/* End of file example.php */