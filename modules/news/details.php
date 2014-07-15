<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_News extends Module
{

    public $version = '1.2';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'News'
            ),
            'description' => array(
                'en' => 'News and events module'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'site',
            'sections' => array(
                'news' => array(
                    'name' => 'news:newss',
                    'uri' => 'admin/news',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'news:new',
                            'uri' => 'admin/news/create',
                            'class' => 'add'
                        )
                    )
                ),
                'categories' => array(
                    'name' => 'news:categories',
                    'uri' => 'admin/news/categories/index',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'news:category:new',
                            'uri' => 'admin/news/categories/create',
                            'class' => 'add'
                        )
                    )
                ),
                'newsfile' => array(
                    'name' => 'newsfile:newsfiles',
                    'uri' => 'admin/news/newsfile',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'newsfile:new',
                            'uri' => 'admin/news/newsfile/create',
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
        $this->news_folder('add');
        $this->newsfiles_stream('add');
        return true;
    }

    public function uninstall()
    {
        $this->stream('remove');
        $this->news_folder('remove');
        $this->newsfiles_stream('remove');
		return true;
    }
	
    public function upgrade($old_version)
    {
        // Your Upgrade Logic
        return true;
    }
	
	public function stream($action = 'add')
	{
		if($action == 'add')
		{
			$this->load->driver('Streams');

	        $this->load->language('news/news');

	        // Add newss streams
	        if ( ! $this->streams->streams->add_stream('lang:news:newss', 'newss', 'news', null, null)) return false;
	        if ( ! $categories_stream_id = $this->streams->streams->add_stream('lang:news:categories', 'categories', 'news', 'news_', null)) return false;
			
			//news folder
	        $this->load->library('files/files');
        	$this->load->model('files/file_folders_m');
	        $news_folder =  ($folder = $this->file_folders_m->get_by('slug', 'news-attachments')) ? $folder->id : 0;

	        // Add some fields
	        $fields = array(
	            array(
	                'name' => 'Title',
	                'slug' => 'news_category_title',
	                'namespace' => 'news',
	                'type' => 'text',
	                'assign' => 'categories',
	                'title_column' => true,
	                'required' => true,
	                'unique' => true
	            ),
	            array(
	                'name' => 'Slug',
	                'slug' => 'news_category_slug',
	                'namespace' => 'news',
	                'type' => 'slug',
	                'assign' => 'categories',
	                'extra' => array('slug_field' => 'news_category_title'),
	                'required' => true,
	                'unique' => true
	            ),
	            array(
	                'name' => 'Name',
	                'slug' => 'name',
	                'namespace' => 'news',
	                'type' => 'text',
	                'assign' => 'newss',
	                'extra' => array('max_length' => 200),
	                'title_column' => true,
	                'required' => true,
	                'unique' => true
	            ),
	            array(
	                'name' => 'Slug',
	                'slug' => 'slug',
	                'namespace' => 'news',
	                'type' => 'slug',
	                'assign' => 'newss',
	                'extra' => array('slug_field' => 'name'),
	                'required' => true
	            ),
	            array(
	                'name' => 'Intro',
	                'slug' => 'intro',
	                'namespace' => 'news',
	                'type' => 'textarea',
	                'assign' => 'newss',
	                'required' => true,
	            ),
	            array(
	                'name' => 'Body',
	                'slug' => 'body',
	                'namespace' => 'news',
	                'type' => 'wysiwyg',
	                'extra' => array('editor_type' => 'advanced'),
	                'assign' => 'newss',
	                'required' => true,
	            ),
	            array(
	                'name' => 'Image',
	                'slug' => 'image',
	                'namespace' => 'news',
	                'type' => 'imagepicker',
	                'extra' => array('img_width' => '100'),
	                'assign' => 'newss',
	            ),
	            array(
	            	'name' => 'File/attachment',
	                'slug' => 'file',
	                'namespace' => 'news',
	                'type' => 'imagepicker',
	                'extra' => array('img_width' => '100', 'type' => 'd'),
	                'assign' => 'newss'
	            ),
	            array(
	                'name' => 'Publish Date',
	                'slug' => 'publish_date',
	                'namespace' => 'news',
	                'type' => 'datetime',
	                'extra' => array('use_time' => 'no','input_type' => 'datepicker', 'start_date' => '-1M', 'end_date' => '+10M'),
	                'assign' => 'newss',
	                'required' => true
	            ),
	            array(
	                'name' => 'Category',
	                'slug' => 'news_category_id',
	                'namespace' => 'news',
	                'type' => 'relationship',
	                'assign' => 'newss',
	                'extra' => array('choose_stream' => $categories_stream_id),
	                'required' => true,
	            ),
	            array(
	                'name' => 'Status',
	                'slug' => 'status',
	                'namespace' => 'news',
	                'type' => 'choice',
	                'extra' => array('choice_data' => "0 : Draft\n1 : Live", 'choice_type' => 'dropdown', 'default_value' => 1),
	                'assign' => 'newss',
	                'required' => true,
	            )
	        );
	
	        $this->streams->fields->add_fields($fields);
	
	        $this->streams->streams->update_stream('newss', 'news', array(
	            'view_options' => array(
	                'id',
	                'name',
	                'slug',
	                'publish_date',
	                'news_category_id',
	                'status'
	            )
	        ));
	
	        $this->streams->streams->update_stream('categories', 'news', array(
	            'view_options' => array(
	                'id',
	                'news_category_title'
	            )
	        ));
		}
		else{
			$this->load->driver('Streams');
		
		    $this->streams->utilities->remove_namespace('news');
		}
		return TRUE;
	}

	public function newsfiles_stream($action = 'add')
	{
		if($action == 'add')
		{
			$this->load->driver('Streams');

	        // Add newsfiles streams
	        if ( ! $this->streams->streams->add_stream('lang:newsfile:newsfiles', 'newsfiles', 'newsfile', null)) return false;
	
	        //result folder
	        $this->load->library('files/files');
        	$this->load->model('files/file_folders_m');
	        $news_folder =  ($folder = $this->file_folders_m->get_by('slug', 'news-attachments')) ? $folder->id : 0;
	        
	        // Add some fields
	        $fields = array(
	            array(
	                'name' => 'File',
	                'slug' => 'newsfile',
	                'namespace' => 'newsfile',
	                'type' => 'file',
	                'extra' => array('folder' => $news_folder),
	                'assign' => 'newsfiles',
	                'required' => true
	            )
	        );
	
	        $this->streams->fields->add_fields($fields);
	        $this->db->query("ALTER TABLE `".SITE_REF."_newsfiles` CHANGE `created` `created` DATETIME NULL;");

		}
		else{
			$this->load->driver('Streams');
		
		    $this->streams->utilities->remove_namespace('newsfile');
		}
		return TRUE;
	}

	/**
     * add or remove result folders.
     * 
     * @access public
     * @param $action add|remove (default: 'add')
     * @return void
     */
    public function news_folder($action = 'add') {

        $this->load->library('files/files');
        $this->load->model('files/file_folders_m');
        //$this->load->model('settings/settings_m');

        $slug = 'news-attachments';

        if ($action == 'add') {

        	if( ! $this->file_folders_m->get_by('slug', $slug))
        	{
        		$parent_id = 0;
            	Files::create_folder($parent_id, 'News Attachments', 'local');
        	}
            
            return TRUE;
        } else {

            $this->file_folders_m->delete_by('slug', $slug);

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