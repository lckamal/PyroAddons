<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Watermark Module
 *
 * @author Michael Giuliana
 */
class Module_Watermark extends Module {

	public $version = '1.2';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Watermark',
			),
			'description' => array(
				'en' => 'Add Watermark to your Images from a folder.',
			),
			'frontend' => false,
			'backend'  => true,
			'skip_xss' => false,
			'menu'	  => 'content',

			'roles' => array(),

			'sections' => array(
				'watermark' => array(
					'name' => 'watermark:watermark',
					'uri' => 'admin/watermark',
				),
			),
		);
	}


	public function install()
	{
		$this->stream('add');
		return true;
	}


	public function uninstall()
	{
		$this->stream('remove');
		return true;
	}


	public function upgrade($old_version)
	{
		$this->db->query("ALTER TABLE `".SITE_REF."_watermarks` CHANGE `created` `created` DATETIME NULL;");
		return true;
	}
	
	public function stream($action = 'add')
	{
		if($action == 'add')
		{
			$this->load->driver('Streams');

	        $this->load->language('watermark/watermark');
	
	        // Add watermarks streams
	        if ( ! $this->streams->streams->add_stream('lang:watermark:watermarks', 'watermarks', 'watermark', null, null)) return false;
	
	        //add some fields
		$fields = array();
	        $template = array('namespace' => 'watermark', 'assign' => 'watermarks', 'type' => 'text', 'title_column' => FALSE, 'unique' => FALSE);
	
	        $fields = array(
	            array(
	                'name' => 'File',
	                'slug' => 'file_id',
	                'title_column' => true
	            ),
			    array(
					'name' => 'Folder Id',
					'slug' => 'folder_id',
			    )
	        );
			// Combine
	        foreach ($fields AS &$field) {
	            $field = array_merge($template, $field);
	        }
	
	        // Add fields to stream
	        $this->streams->fields->add_fields($fields);
			$this->db->query("ALTER TABLE `".SITE_REF."_watermarks` CHANGE `created` `created` DATETIME NULL;");
		}
		else{
			$this->load->driver('Streams');
		
		    $this->streams->utilities->remove_namespace('watermark');
		}
		return TRUE;
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
        <p>The watermark helps to put watermark on images of files module.</p>";
    }
}