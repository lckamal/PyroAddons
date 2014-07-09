<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Associate Partners module
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Countries Module
 */
class Mystream_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
		
		$this->_table = 'data_streams';
        $this->primary_key = 'id';
	}
	
}
