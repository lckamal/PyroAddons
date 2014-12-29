<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Associate Partners module
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Countries Module
 */
class Service_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
		
		$this->_table = 'service';
        $this->primary_key = 'id';
	}


	public function get_service()
	{
		$sql ="select * from default_service";

		$query =$this->db->query($sql);

		$result = $query->result();

		//var_dump($result);

		return $result;

	}
}
