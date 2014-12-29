<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * gUrl Plugin
 *
 * Shorten Long Url using Google URL Shortner
 *
 * @author		Kamal Lamichhane
 * @package		PyroCMS\Addon\Plugins
 * @copyright	MIT
 */
class Plugin_Gurl extends Plugin
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
				'double' => true,// how about as a double tag?
				'variables' => '',// list all variables available inside the double tag. Separate them|like|this
				'attributes' => array(
					'url' => array(// this is the name="World" attribute
						'type' => 'url',// Can be: slug, number, flag, text, array, any.
						'flags' => '',// flags are predefined values like asc|desc|random.
						'default' => 'current uri',// this attribute defaults to this if no value is given
						'requ ired' => true,// is this attribute required?
					),
                    'tag' => array(
                        'type' => 'boolean',
                        'flags' => '1|0',
                        'default'=>'1',
                        'required'=>true
                    ),
				),
			),
		);
	
		return $info;
	}

	/**
	 * Get
	 *
	 * Usage:
	 * {{ gurl:shorten url="http://www.google.com" }}
	 *
	 * @return string
	 */
	function shorten()
	{
        $this->load->spark('google-url-shortener/1.0.4');

        // We have cache about this url ?!
        $cached = $this->pyrocache->get('plugin_gurl_shorten');
 	    if(is_array($cached))
 	    {
 	    	if(array_key_exists($long_url, $cached)){
 	    		return $cached[$long_url];
 	    	}
 	    }

        $url = $this->attribute('url', current_url());
        $url_tag = $this->attribute('tag', 0);

        $short_url = $this->google_url_api->shorten($url);
        if( $url_tag == true ) return '<a href="' . $short_url->id . '" alt="goo.gl">' . $short_url->id . '</a>';

        $cached[$long_url] = $short_url->id;
	    $this->pyrocache->write($cached, 'plugin_gurl_shorten');
        return $short_url->id;
	}

    function expand()
    {
        $this->load->spark('google-url-shortener/1.0.4');

        // We have cache about this url ?!
        $cached = $this->pyrocache->get('plugin_gurl_expand');
 	    if(is_array($cached))
 	    {
 	    	if(array_key_exists($short_url, $cached)){
 	    		return $cached[$short_url];
 	    	}
 	    }

        $url = $this->attribute('url', current_url());
        $url_tag = $this->attribute('tag', 0);

        $expand_url = $this->google_url_api->expand($url);

        if( $url_tag == true ) return '<a href="' . $expand_url->longUrl . '" alt="goo.gl">' . $expand_url->longUrl . '</a>';

        $cached[$short_url] = $expand_url->longUrl;
	    $this->pyrocache->write($expand_url->longUrl, 'plugin_gurl_expand');
        return $expand_url->longUrl;
    }
}

/* End of file example.php */