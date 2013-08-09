<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a Newsletter module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Newsletter
 */
class Group_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
		
		$this->_table = 'newsletter_groups';
        $this->primary_key = 'id';
	}
	
	//create a new item
	public function create($input)
	{
	    unset($input['btnAction']);
		return $this->db->insert($this->_table, $input);
	}

}
