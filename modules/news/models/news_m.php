<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a Countries module for PyroCMS
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Countries Module
 */
class News_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
		
		$this->_table = 'newss';
        $this->primary_key = 'id';
	}
	public function get_single_news($id)
	{		
		$query = $this->db->query("SELECT * FROM default_newss WHERE id=$id");
		$result = $query->result();
		return $result;
	}
	public function get_all_updates()
	{		
		$query = $this->db->query("SELECT * FROM default_newss WHERE news_category_id='1'");
		$result = $query->result();
		return $result;
	}
	
}
