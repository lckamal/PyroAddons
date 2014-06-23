<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Slider extends Module
{

    public $version = '1.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Slider'
            ),
            'description' => array(
                'en' => 'Widget ready slider for Nivo Slider and Flex slider'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'content',
            'sections' => array(
                'slider' => array(
                    'name' => 'slider:sliders',
                    'uri' => 'admin/slider',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'slider:new',
                            'uri' => 'admin/slider/create',
                            'class' => 'add'
                        )
                    )
                ),
                'categories' => array(
                    'name' => 'slider:categories',
                    'uri' => 'admin/slider/categories/index',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'slider:category:new',
                            'uri' => 'admin/slider/categories/create',
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

	        $this->load->language('slider/slider');
	
	        // Add sliders streams
	        if ( ! $this->streams->streams->add_stream('lang:slider:sliders', 'sliders', 'slider', 'slider_', null)) return false;
	        if ( ! $categories_stream_id = $this->streams->streams->add_stream('lang:slider:categories', 'categories', 'slider', 'slider_', null)) return false;
	
	        //$slider_categories
	
	        // Add some fields
	        $fields = array(
	           	array(
	            	'name' => 'Image',
	            	'slug' => 'image',
	            	'type' => 'imagepicker',
	            	'namespace' => 'slider',
	            	'assign' => 'sliders',
	            	'extra' => array('img_width' => '100'),
	                'required' => true,
	            ),
	            array(
	                'name' => 'caption',
	                'slug' => 'caption',
	                'namespace' => 'slider',
	                'type' => 'text',
	                'extra' => array('max_length' => 200),
	                'assign' => 'sliders',
	                'title_column' => true,
	                'unique' => true
	            ),
	            array(
	                'name' => 'Description',
	                'slug' => 'description',
	                'namespace' => 'slider',
	                'type' => 'textarea',
	                'assign' => 'sliders',
	            ),
	            array(
	                'name' => 'Link',
	                'slug' => 'link',
	                'namespace' => 'slider',
	                'type' => 'text',
	                'instructions' => 'Provide full url eg. http://lkamal.com.np/blog',
	                'assign' => 'sliders'
	            ),
	            array(
	                'name' => 'Slider',
	                'slug' => 'category',
	                'namespace' => 'slider',
	                'type' => 'relationship',
	                'assign' => 'sliders',
	                'extra' => array('choose_stream' => $categories_stream_id),
	                'required' => true
	            ),
	            array(
	                'name' => 'Title',
	                'slug' => 'category_title',
	                'namespace' => 'slider',
	                'type' => 'text',
	                'assign' => 'categories',
	                'title_column' => true,
	                'required' => true,
	                'unique' => true
	            ),
	            array(
	                'name' => 'Description',
	                'slug' => 'category_desc',
	                'namespace' => 'slider',
	                'type' => 'text',
	                'assign' => 'categories'
	            )
	        );
	
	        $this->streams->fields->add_fields($fields);
	
	        $this->streams->streams->update_stream('sliders', 'slider', array(
	            'view_options' => array(
	                'id',
	                'caption',
	                'image',
	                'category'
	            )
	        ));
	
	        $this->streams->streams->update_stream('categories', 'slider', array(
	            'view_options' => array(
	                'id',
	                'category_title'
	            )
	        ));
		}
		else{
			$this->load->driver('Streams');
		
		    $this->streams->utilities->remove_namespace('slider');
		}
		return TRUE;
	}
	
    public function upgrade($old_version)
    {
        // Your Upgrade Logic
        return true;
    }

    public function help()
    {
        // Return a string containing help info
        // You could include a file and return it here.
        return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
    }

}