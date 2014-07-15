<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a watermark module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Countries Module
 */
class Watermark_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
		
		$this->_table = 'watermarks';
        $this->primary_key = 'id';
	}
	
}
