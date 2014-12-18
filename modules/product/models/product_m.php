<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a Countries module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Countries Module
 */
class product_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
		$this->_table = 'products';
        $this->primary_key = 'id';
	}

	public function get_all_users()
	{
		$query = $this->db->query("SELECT * FROM default_users");
		$result = $query->result();
		return $result;
	}

	public function max_order_count()
	{
		$query = $this->db->query("SELECT MAX(ordering_count) AS max FROM default_products");
		$result = $query->result();
		return $result;
	}
	
	public function get_active_products()
	{
		return $this->product_m->select('id, product_slug')->where('product_status', 1)->get_all();
	}
	
}
