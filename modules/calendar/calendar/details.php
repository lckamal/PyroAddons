<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Calendar extends Module {

	public $version = '1.3.2'; // for PyroCMS 2.2.x and support php5.4

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Calendar',
				
			),
			'description' => array(
				'en' => 'Organize your schedule.',
				),
			'frontend' => TRUE,
			'backend' => TRUE,
			'menu' => 'content',
            
			'sections' => array(
			    'calendar home' => array(
				    'name' => 'calendar_posts_title',
				    'uri' => 'admin/calendar',
				    'shortcuts' => array(
						array(
					 	   'name' => 'calendar_create_title',
						    'uri' => 'admin/calendar/create',
						    'class' => 'add'
						),
                        array(
					 	   'name' => 'calendar_list_title',
						    'uri' => 'admin/calendar/list_calendar',
						    'class' => ''
						),
                        array(
					 	   'name' => 'calendar_setting_title',
						    'uri' => 'admin/calendar/setting',
						    'class' => 'calsetting'
						),
					),
				),
		    ),
		);
	}

	public function install()
	{
		$ok = true;
		$this->db->query("DROP TABLE IF EXISTS ".$this->db->dbprefix('eventcal')."; ");
		$calendar = "
             CREATE TABLE `".$this->db->dbprefix('eventcal')."` (
                          `id_eventcal` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `user_id` int(11) NOT NULL,
                          `event_date_begin` datetime DEFAULT NULL,
                          `event_date_end` datetime DEFAULT NULL,
                          `event_title` varchar(255) NOT NULL,
                          `event_content` text,
                          `event_repeat` smallint(1) NULL DEFAULT 0,
                          `event_repeat_prm` varchar(255) NULL DEFAULT '',
                          `privacy` enum('public','private') NOT NULL DEFAULT 'public',
                          PRIMARY KEY (`id_eventcal`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
		";
        if($this->db->query($calendar) == false and $ok == true){
            $ok = false;
        }
		
		if($ok)
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
        $ok = true;
        
        
		$drop1 = $this->db->query("DROP TABLE IF EXISTS ".$this->db->dbprefix('eventcal')."; ");
        if($drop1 == false and $ok == true){
            $ok = false;
        }
        
		if($ok)
		{
			return TRUE;
		}
	}

	public function upgrade($old_version)
	{
		if($old_version == '1.2.1' or $old_version == '1.2.2'){
			return TRUE;
		}
		
		$upgrade1 = $this->db->query("ALTER TABLE ".$this->db->dbprefix('eventcal')." ADD `event_repeat` SMALLINT( 1 ) NULL DEFAULT '0' AFTER `event_title`, ADD `event_repeat_prm` VARCHAR( 255 ) NULL DEFAULT '' AFTER `event_repeat`; ");
        if($upgrade1 == false){
            return FALSE;			
        }
        
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "<h4>Overview</h4>
		<p>Calendar will help you organize.</p>
		<p>Creating an event is easy. You can create more than events a day.</p>
                <p>In order to see detail of event of a day, you need to click a date.</p>
               <p>When you want to edit details of an event, click a pop-up.</p>";
	}
}
/* End of file details.php */
