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
class Plugin_Dateconvert extends Plugin
{
	public $version = '1.0.0';

	public $name = array(
		'en'	=> 'Date Conversion'
	);

	public $description = array(
		'en'	=> 'Convert english date to nepali or viceversa.'
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
			'dateconverter' => array(
				'description' => array(
					'en' => 'Convert english date to nepali or viceversa.'
				),
				'single' => true,
				'double' => false,
				'variables' => '',
				'attributes' => array(
					'date' => array(
						'type' => 'date',
						'flags' => 'Format',
						'default' => '',
						'required' => true,
					),
					'lang' => array(
						'type' => 'text',
						'flags' => 'en|np',
						'default' => 'en',
						'required' => false
					),
				),
			),
		);
	
		return $info;
	}

	/**
	 * Show read mroe text with link
	 *
	 * Usage:
	 * {{ dateconvert:convert }}
	 *
	 * @return string
	 */
	function convert()
	{
		$date = strip_tags($this->attribute('date', date('Y-m-d')));
		$lang = $this->attribute('lang', 'en');
		$seperator = $this->attribute('seperator', '-');
		list($year, $month, $day) = explode('-', $date);
		
		$this->load->library('dateconvert');

		if($lang == 'en'){
			$date = $this->dateconvert->eng_to_nep($year,$month,$day);
		}
		else{
			$date = $this->dateconvert->nep_to_eng($year,$month,$day);
		}

		return $dateString = $date['year'].$seperator.$date['month'].$seperator.$date['num_day']; 
	}
}

/* End of file example.php */