<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_product extends Module
{

    public $version = '1.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Products'
            ),
            'description' => array(
                'en' => 'MOKA Products.'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'content',
            'skip_xss' => true,
            'sections' => array(
                'product' => array(
                    'name' => 'product:products',
                    'uri' => 'admin/product',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'product:new',
                            'uri' => 'admin/product/create',
                            'class' => 'add'
                        )
                    )
                ),
                'categories' => array(
                    'name' => 'product:categories',
                    'uri' => 'admin/product/categories/index',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'product:category:new',
                            'uri' => 'admin/product/categories/create',
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

	        $this->load->language('product/product');
	
	        // Add products streams
	        if ( ! $this->streams->streams->add_stream('lang:product:products', 'products', 'product', null, null)) return false;
	        if ( ! $categories_stream_id = $this->streams->streams->add_stream('lang:product:categories', 'categories', 'product', 'product_', null)) return false;
		
	        //$product_categories
	
	        // Add some fields
	        $fields = array(
	            array(
	                'name' => 'Product Name',
	                'slug' => 'product_name',
	                'namespace' => 'product',
	                'type' => 'text',
	                'extra' => array('max_length' => 200),
	                'assign' => 'products',
	                'required' => true,
	                'unique' => true
	            ),
				    array(
					'name' => 'Product Slug',
					'slug' => 'product_slug',
					'type' => 'slug',
					'namespace' => 'product',
					'assign' => 'products',
					'extra' => array('space_type' => '-', 'slug_field' => 'product_name'),
	                'required' => true,
	                'unique' => true
				    ),
	            array(
	                'name' => 'Product Description',
	                'slug' => 'product_desc',
	                'namespace' => 'product',
	                'type' => 'wysiwyg',
	                'assign' => 'products',
	                'extra' => array('editor_type' => 'advanced')
	            ),
	           	array(
	            	'name' => 'Product Image',
	            	'slug' => 'product_image',
	            	'type' => 'imagepicker',
	            	'namespace' => 'product',
	            	'assign' => 'products',
	            	'extra' => array('img_width' => '100')
	            ),
	           	array(
	            	'name' => 'Product Gallery',
	            	'slug' => 'product_gallery',
	            	'type' => 'file_folders',
	            	'namespace' => 'product',
	            	'assign' => 'products',
	            ),
	            array(
	            	'name' => 'Category',
                    'slug' => 'product_category',
                    'namespace' => 'product',
                    'assign' => 'products',
                    'title_column' => true,
                    'type' => 'related',
                    'extra' => array('table' => 'product_categories','key_field'=>'id','title_field'=>'category_title')
	            ),
	            array(
	                'name' => 'Status',
	                'slug' => 'product_status',
                    'namespace' => 'product',
                    'assign' => 'products',
	                'type' => 'choice',
	                'extra' => array('choice_data' => "0 : Draft\n1 : Live", 'choice_type' => 'dropdown', 'default_value' => 1),
	            ),
	            array(
	                'name' => 'Featured',
	                'slug' => 'product_featured',
                    'namespace' => 'product',
                    'assign' => 'products',
	                'type' => 'choice',
	                'extra' => array('choice_data' => "0 : No\n1 : Yes", 'choice_type' => 'dropdown', 'default_value' => 0),
	            ),		    	
	            array(
	                'name' => 'Category Title',
	                'slug' => 'category_title',
	                'namespace' => 'product',
	                'type' => 'text',
	                'assign' => 'categories',
	                'required' => true,
	                'unique' => true
	            ),
				    array(
					'name' => 'Category Slug',
					'slug' => 'category_slug',
					'type' => 'slug',
					'namespace' => 'product',
					'assign' => 'categories',
					'extra' => array('space_type' => '-', 'slug_field' => 'category_title'),
	                'required' => true,
	                'unique' => true
				    ),
	            array(
	                'name' => 'Category Status',
	                'slug' => 'category_status',
                    'namespace' => 'product',
                    'assign' => 'categories',
	                'type' => 'choice',
	                'extra' => array('choice_data' => "0 : Draft\n1 : Live", 'choice_type' => 'dropdown', 'default_value' => 1),
	            )
	        );
	

	        $this->streams->fields->add_fields($fields);
	
	        $this->streams->streams->update_stream('products', 'product', array(
	            'view_options' => array(
	                'id',
	                'product_name',
	                'product_category',
	                'product_status'
	            )
	        ));
	
	        $this->streams->streams->update_stream('categories', 'product', array(
	            'view_options' => array(
	                'id',
	                'category_title',
	                'category_status'
	            )
	        ));
		}
		else{
			$this->load->driver('Streams');
		
		    $this->streams->utilities->remove_namespace('product');
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










