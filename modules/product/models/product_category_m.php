<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a Countries module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Countries Module
 */
class product_category_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
		
		$this->_table = 'product_categories';
        $this->primary_key = 'id';
	}

	public function get_header_categories($pos)
	{
		$this->db->where('category_parent_id',NULL);
		$this->db->where('position',$pos);
		$query = $this->db->get($this->_table);
		$result = $query->result();
		return $result;
	}

	public function get_sub_categories($parent_id)
	{
		$this->db->where('category_parent_id',$parent_id);
		$query = $this->db->get($this->_table);
		$result = $query->result();
		return $result;
	}

	public function get_all_header()
	{
		$this->db->where('category_parent_id',NULL);
		$query = $this->db->get($this->_table);
		$result = $query->result();
		return $result;
	}
	
}
