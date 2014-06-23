<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a Countries module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Countries Module
 */
class Slider_category_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
		
		$this->_table = 'slider_categories';
        $this->primary_key = 'country_id';
	}
	
}
