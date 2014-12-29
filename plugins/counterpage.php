<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Counter Page Plugin
 *
 * Read and write counter page
 *
 * @package		PyroCMS
 * @author		Eko Muhammad Isa
 * @version     1.0.1
 */
class Plugin_Counterpage extends Plugin
{
	/**
	 * Current uri string
	 *
	 * Table Structure (Required):
	     
	     CREATE TABLE IF NOT EXISTS `default_counter_page` (
                          `id_counter` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `url` varchar(255) NOT NULL DEFAULT '',
                          `counter` int(7) unsigned NOT NULL DEFAULT 0,
                          PRIMARY KEY (`id_counter`),
                          INDEX `url_idx` (`url`)
		    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
		
	 
	 * Usage:
	 * {{ counterpage:current }}
	 *
	 * @return	numeric
	 */
	function current()
	{
		$this_url = trim("{{url:site}}");
		
		if (!$this->db->table_exists($this->db->dbprefix('counter_page'))){
			$this->install();
		}
		$result = $this->db
			->select($this->db->dbprefix('counter_page').'.counter')
			->where('url', $this_url)
			->get($this->db->dbprefix('counter_page'))
			->row();
		if($result){
	        $counter = intval($result->counter) + 1;
        }else{
            $counter = 1;
        }
        if($counter == 1){
            $data = array(
               'url' => $this_url,
               'counter' => $counter
            );
            $this->db->insert($this->db->dbprefix('counter_page'), $data); 
		}else{
		    $data = array(
               'counter' => $counter
            );
		    $this->db->where('url', $this_url);
		    $this->db->update($this->db->dbprefix('counter_page'), $data);
		}
		return $counter;
	}

	function all()
	{

	}
	
	/**
	 * Create table structure
	 *
	 * Usage:
	 * {{ counterpage:install }}
	 *
	 * @return	numeric
	 */
	function install(){
	
	    $counter_page = "
             CREATE TABLE IF NOT EXISTS `".$this->db->dbprefix('counter_page')."` (
                          `id_counter` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `url` varchar(255) NOT NULL DEFAULT '',
                          `counter` int(7) unsigned NOT NULL DEFAULT 0,
                          PRIMARY KEY (`id_counter`),
                          INDEX `url_idx` (`url`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
		";
        $this->db->query($counter_page);
	
	}
}

/* End of file counterpage.php */
