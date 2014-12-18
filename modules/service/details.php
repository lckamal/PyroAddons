<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Service extends Module
{

    public $version = '1.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Service'
            ),
            'description' => array(
                'en' => 'MOKA Service Details.'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'content',
            
            'shortcuts' => array(
                'create' => array(
                    'name' => 'service:service',
                    'uri' => 'admin/service/create',
                    'class' => 'add'
                        )
                    )           
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
        // Your Upgrade Logic
        return true;
    }
	

	public function stream($action = 'add')
	{
		if ($action == 'add') {
            $folder_name = array('service-folder' => 'Service Folder');
            $folder = $this->file_folders_m->get_by('slug', key($folder_name));
            if(!$folder)
            {
                $folder = Files::create_folder(0, reset($folder_name), 'local');
                $fid=$folder['data']['id'];
            }
            
        } else {
        		$folder_name = array('service-folder' => 'Service Folder');
                $this->file_folders_m->delete_by('slug', key($folder_name));
        }

		if($action == 'add')
		{
			$this->load->driver('Streams');

	        $this->load->language('service/service');
	
	        // Add service streams
	        if ( ! $this->streams->streams->add_stream('lang:service:service', 'service', 'service', null, null)) return false;
	
	        //add some fields
			$fields = array();
	        $template = array('namespace' => 'service', 'assign' => 'service', 'type' => 'text', 'title_column' => FALSE, 'unique' => FALSE);
	
	        $fields = array(
	            array(
	                'name' => 'Title',
	                'slug' => 'title',
	                'type' => 'text',
	                'extra' => array('max_length' => 100),
	                'namespace' => 'service',
                    'assign' => 'service',
	                'title_column' => true,
	                'required' => true,
	                'unique' => true,
	            ),
	            array(
					'name' => 'Service Slug',
					'slug' => 'service_slug',
					'type' => 'slug',
					'namespace' => 'service',
					'assign' => 'service',
					'extra' => array('space_type' => '-', 'slug_field' => 'title'),
	                'required' => true,
	                'unique' => true,
				    ),	            
	            array(
	                'name' => 'Description',
	                'slug' => 'description',
	                'namespace' => 'service',
                    'assign' => 'service',
                    'type' => 'wysiwyg',
	                'extra' => array('editor_type' => 'advanced'),
	                'required' => true,
	            ),
	            array(
	                'name' => 'Image',
	                'slug' => 'image',
	                'namespace' => 'service',
                    'assign' => 'service',
                    'type' => 'image',
	             	'extra' => array('allowed_types' => 'jpg|jpeg|png|gif','folder' => $fid),
	            ),
	            array(
	                'name' => 'Icon',
	                'slug' => 'icon',
	                'namespace' => 'service',
                    'assign' => 'service',
                    'type' => 'text',
	            ),
	            array(
	                'name' => 'Service Quote',
	                'slug' => 'quote',
	                'namespace' => 'service',
                    'assign' => 'service',
                    'type' => 'text',
	            ),	
	            array(
	                'name' => 'Status',
	                'slug' => 'status',
	                'namespace' => 'service',
                    'assign' => 'service',
	                'type' => 'choice',
	                'extra' => array('choice_data' => "0 : Draft\n1 : Live", 'choice_type' => 'dropdown', 'default_value' => 1),
	                'required' => true,
	            ),
	            array(
	                'name' => 'Featured',
	                'slug' => 'featured',
	                'namespace' => 'service',
                    'assign' => 'service',
	                'type' => 'choice',
	                'extra' => array('choice_data' => "0 : No\n1 : Yes", 'choice_type' => 'dropdown', 'default_value' => 0),
	                'required' => true,
	            )
	        );
			// Combine
	        foreach ($fields AS &$field) {
	            $field = array_merge($template, $field);
	        }
	
	        // Add fields to stream
	        $this->streams->fields->add_fields($fields);
	        $this->streams->streams->update_stream('service', 'service', array(
	            'view_options' => array(
	                'id',
	                'title',
	                'status',
	                'featured',
	            )
	        ));
		}else{
			
	        $this->load->driver('Streams');
	
	        $this->streams->utilities->remove_namespace('service');
		}
		return TRUE;
	}
	
	
    public function help()
    {
        // Return a string containing help info
        // You could include a file and return it here.
        return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
    }

}