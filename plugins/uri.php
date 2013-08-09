<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Uri Plugin
 *
 * extended pyrocms uri plugin
 *
 * @author		Kamal Lamichhane
 * @package		access-himalaya\Addon\Plugins
 * @copyright	Copyright (c) 2013, lkamal.com.np
 */
class Plugin_Uri extends Plugin
{
	public $version = '1.0.0';

	public $name = array(
		'en'	=> 'Uri'
	);

	public $description = array(
		'en'	=> 'Uri plugin structure.'
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
			'is_home' => array(
				'description' => array(// a single sentence to explain the purpose of this method
					'en' => 'extended default plugin uri.'
				),
				'single' => true,// will it work as a single tag?
				'double' => false,// how about as a double tag?
				'variables' => '',// list all variables available inside the double tag. Separate them|like|this
			),
		);
	
		return $info;
	}

	/**
	 * Hello
	 *
	 * Usage:
	 * {{ uri:is_home }}
	 *
	 * @return string
	 */
	function is_home()
	{
		//echo "exit";exit;
		if(current_url() == base_url() || $this->uri->segment(1) == 'home')
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}

/* End of file example.php */