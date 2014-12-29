<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_category extends Module
{

    public $version = '1.2';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Category'
            ),
            'description' => array(
                'en' => 'Category module.'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'ad',
            'skip_xss' => true,
            'sections' => array(
                'category' => array(
                    'name' => 'category:category',
                    'uri' => 'admin/category/index',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'category:new',
                            'uri' => 'admin/category/lang',
                            'class' => 'add'
                        )
                    )
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
	
	public function stream($action = 'add')
	{
		if($action == 'add')
		{
			$this->load->driver('Streams');

	        $this->load->language('category/category');
	
	        // Add products streams
	        if ( ! $category_stream_id = $this->streams->streams->add_stream('lang:category:category', 'category', 'category')) return false;
	        // Add some fields
	        $fields = array(
	            array(
	                'name' => 'lang:category:title',
	                'slug' => 'category_title',
	                'namespace' => 'category',
	                'type' => 'text',
	                'assign' => 'category',
                    'title_column' => true,
	                'required' => true,
	            ),
				array(
					'name' => 'lang:category:slug',
					'slug' => 'category_slug',
					'type' => 'slug',
					'namespace' => 'category',
					'assign' => 'category',
					'extra' => array('space_type' => '-', 'slug_field' => 'category_title'),
	                'required' => true,
				    ),
	            array(
	            	'name' => 'lang:category:description',
	            	'slug' => 'category_desc',
	            	'namespace' => 'category',
	            	'assign' => 'category',
					'type' => 'wysiwyg',
					'extra' => array('editor_type' => 'simple')
	            ),    
	            array(
	            	'name' => 'lang:category:parent',
                	'slug' => 'category_parent',
                    'namespace' => 'category',
                    'assign' => 'category',
                    'type' => 'related',
                    'extra' => array('table' => 'category','key_field'=>'id','title_field'=>'category_title','where'=>'category_parent IS NULL')
	            ),    
	            array(
	            	'name' => 'lang:category:image',
	            	'slug' => 'category_image',
	            	'type' => 'imagepicker',
	            	'namespace' => 'category',
	            	'assign' => 'category',
	            	'extra' => array('img_width' => '100')
	            ),
	            array(
	                'name' => 'lang:category:lang',
	                'slug' => 'category_lang',
                    'namespace' => 'category',
                    'assign' => 'category',
	                'type' => 'pyro_lang',
	            ),
	            array(
	            	'name' => 'lang:category:id',
                	'slug' => 'category_id',
                    'namespace' => 'category',
                    'assign' => 'category',
                    'type' => 'related',
                    'extra' => array('table' => 'category','key_field'=>'id','title_field'=>'category_title')
	            ),
	            array(
	                'name' => 'lang:category:status',
	                'slug' => 'category_status',
                    'namespace' => 'category',
                    'assign' => 'category',
	                'type' => 'choice',
	                'extra' => array('choice_data' => "0 : Draft\n1 : Live", 'choice_type' => 'dropdown', 'default_value' => 1),
	            )
	        );

	        $this->streams->fields->add_fields($fields);
	
	        $this->streams->streams->update_stream('category', 'category', array(
	            'view_options' => array(
	                'id',
	                'category_title',
	                'category_parent',
	                'category_lang'
	            )
	        ));
		}
		else{
			$this->load->driver('Streams');
		
		    $this->streams->utilities->remove_namespace('category');
		}
		return TRUE;
	}
	
    public function upgrade($old_version)
    {	
    	//your upgrade logic goes here
        return true;
    }

    public function help()
    {
        // Return a string containing help info
        // You could include a file and return it here.
        return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
    }

}










