<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Google url shortner helper
 *
 * Shorten Long Url
 *
 * @author		Kamal Lamichhnae
 * @package		PyroCMS\Addon\Plugins
 * @copyright	MIT
 */

/**
 * shorten long url
 * @return string
 */
if ( ! function_exists('gurl_shorten'))
{
	function gurl_shorten($long_url = "", $tag = false)
	{
	    ci()->load->spark('google-url-shortener/1.0.4');

	    // We have cache about this url ?!
 	    $cached = ci()->pyrocache->get('plugin_gurl_shorten');
 	    if(is_array($cached))
 	    {
 	    	if(array_key_exists($long_url, $cached)){
 	    		return $cached[$long_url];
 	    	}
 	    }
	    $short_url = ci()->google_url_api->shorten($long_url);
	    if( $tag == true ) return '<a href="' . $short_url->id . '" alt="goo.gl">' . $short_url->id . '</a>';

	    $cached[$long_url] = $short_url->id;
	    ci()->pyrocache->write($cached, 'plugin_gurl_shorten');
	    return $short_url->id;
	}
}

/**
 * expand short url
 * @return string
 */
if ( ! function_exists('gurl_expand'))
{
	function gurl_expand($short_url = "", $tag = false)
	{
	    ci()->load->spark('google-url-shortener/1.0.4');

	    // We have cache about this url ?!
	    $cached = ci()->pyrocache->get('plugin_gurl_expand');
 	    if(is_array($cached))
 	    {
 	    	if(array_key_exists($short_url, $cached)){
 	    		return $cached[$short_url];
 	    	}
 	    }

	    $expand_url = ci()->google_url_api->expand($short_url);

	    if( $tag == true ) return '<a href="' . $expand_url->longUrl . '" alt="goo.gl">' . $expand_url->longUrl . '</a>';
	    $cached[$short_url] = $expand_url->longUrl;
	    ci()->pyrocache->write($expand_url->longUrl, 'plugin_gurl_expand');
	    return $expand_url->longUrl;
	}
}
/* End of file gurl_helper.php */