<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sliders Module
 *
 * @author Michael Giuliana
 */
class Module_Sliders extends Module {

	public $version = '1.1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Sliders',
			),
			'description' => array(
				'en' => 'Add Nivo Sliders to your site and display featured content.',
			),
			'frontend' => false,
			'backend'  => true,
			'skip_xss' => false,
			'menu'	  => 'content',

			'roles' => array(),

			'sections' => array(
				'sliders' => array(
					'name' => 'sliders.list_title',
					'uri' => 'admin/sliders',
				),
			),
		);
	}


	public function install()
	{
		$this->dbforge->drop_table('sliders');

		// Define tables
		$tables = array(
			'sliders' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true,),
				'folder_id' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
				'jquery' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
			),
			// 'slider_details' => array(
				// 'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true,),
				// 'slider_id' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
				// 'file_id' => array('type' => 'CHAR', 'constraint' => 15, 'null' => true),
				// 'title' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => true),
				// 'link' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => true),
				// 'link_target' => array('type' => 'ENUM', 'constraint' => array('_self','_blank','_parent'), 'default' => '_self'),
				// 'description' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => true),
				// 'status' => array('type' => 'INT', 'constraint' => 1, 'default' => 0),
			// ),
		);

		// Install Tables
		if ( ! $this->install_tables($tables))
		{
			return false;
		}


		$default_folder = array(
			'id'			=> null,
			'parent_id'		=> 0,
			'slug'			=> 'sliders-module',
			'name'			=> 'Sliders Module',
			'location'		=> 'local',
			'date_added'	=> now(),
			);


		/**
		 * Default file folder
		 */
		$query = $this->db->get_where('file_folders', array('name' => 'Sliders Module'));
		$folder_exists = $query->row();
		if($folder_exists)
		{
			$folder_id = $folder_exists->id;
		}
		else
		{
			$this->db->insert('file_folders', $default_folder);
			$folder_id = $this->db->insert_id();
		}

		

		$default_settings = array(
			'id'		 => 1,
			'folder_id'  => $folder_id,
			'jquery' 	 => 0,
		);

		// Insert config
		if ( ! $this->db->insert('sliders', $default_settings))
		{
			return false;
		}

		return true;
	}


	public function uninstall()
	{
		$this->dbforge->drop_table('sliders');
		//$this->dbforge->drop_table('slider_details');
		return true;
	}


	public function upgrade($old_version)
	{
		// Upgrade Logic
		$tables = array(
			'slider_details' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true,),
				'slider_id' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
				'file_id' => array('type' => 'CHAR', 'constraint' => 15, 'null' => true),
				'title' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => true),
				'link' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => true),
				'link_target' => array('type' => 'ENUM', 'constraint' => array('_self','_blank','_parent'), 'default' => '_self'),
				'description' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => true),
				'status' => array('type' => 'INT', 'constraint' => 1, 'default' => 0),
			),
		);

		// Install Tables
		if ( ! $this->install_tables($tables))
		{
			return false;
		}
		return true;
	}
	
	/**
     * Displays help on menu bar.
     * 
     * @access public
     * @return void
     */
    public function help() {
        // Return a containing help info
        // You could include a file and return it here.
        return "<h4>Overview</h4>
        <p>The slider module is totally dependent to files module.</p>
        <h4>Picture</h4>
        <p>A folder is choosen from files and all pictures inside the folder will be used for slides.</p>
        <h4>Texts</h4>
        <p>The Text on top of picture is from image/file detail page. Two editable fields <b>Alt Attribute</b> and <b>Description</b> can be edited as per the requirements.</p>
        <b>Alt Attribute:</b>
        <p>The Heading of the description can be written as alt Attribute</p>
        <b>Description: </b>
        <p>Description field can hold html tags also. So you can add link inside description. Here is an example of description with a link:
        <code>
        &lt;a href=\"http://fulladdresshere.com/fulladdress\">Your Description here&lt;/a>
        </code></p>";
    }
}