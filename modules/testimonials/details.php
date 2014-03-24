<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Testimonials extends Module
{

    public $version = '1.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Testimonials'
            ),
            'description' => array(
                'en' => 'Testimonials'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'content',
            'shortcuts' => array(
                'create' => array(
                    'name' => 'testimonials:new',
                    'uri' => 'admin/testimonials/create',
                    'class' => 'add'
                )
            )
        );
    }

    public function install()
    {
        $this->stream('add');
        $this->folders('add');
        return true;
    }


    public function uninstall()
    {
        $this->stream('remove');
        $this->folders('remove');
        return true;
    }

    public function upgrade($old_version)
    {
        return true;
    }

    public function stream($action = 'add')
    {
        $this->load->driver('Streams');
        $this->load->language('testimonials/testimonials');
        if($action == 'add')
        {
            // Add faqs streams
            if ( ! $this->streams->streams->add_stream(lang('testimonials:testimonials'), 'testimonials', 'testimonials', null, null)) return false;

            // Add some fields
            $fields = array(
                array(
                    'name' => 'Company / Name',
                    'slug' => 'company',
                    'namespace' => 'testimonials',
                    'type' => 'text',
                    'extra' => array('max_length' => 200),
                    'assign' => 'testimonials',
                    'title_column' => true,
                    'required' => true,
                    'unique' => true
                ),
                array(
                    'name' => 'Quote',
                    'slug' => 'quote',
                    'namespace' => 'testimonials',
                    'type' => 'textarea',
                    'assign' => 'testimonials',
                    'required' => true
                ),
                array(
                    'name' => 'Body',
                    'slug' => 'body',
                    'namespace' => 'testimonials',
                    'type' => 'wysiwyg',
                    'extra' => array('editor_type' => 'advanced'),
                    'assign' => 'testimonials'
                ),
                array(
                    'name' => 'Photo',
                    'slug' => 'photo',
                    'namespace' => 'testimonials',
                    'type' => 'imagepicker',
                    'extra' => array('img_width' => '100', 'type' => 'i'),
                    'assign' => 'testimonials'
                ),
                array(
                    'name' => 'Featured',
                    'slug' => 'featured',
                    'namespace' => 'testimonials',
                    'type' => 'choice',
                    'extra' => array('choice_type' => 'radio', 'choice_data' => "0 : No\n 1 : Yes"),
                    'assign' => 'testimonials'
                    )
            );

            $this->streams->fields->add_fields($fields);
            $this->streams->streams->update_stream('testimonials', 'testimonials', array(
                'view_options' => array(
                    'id',
                    'company'
                )
            ));
        }
        else{
            $this->streams->utilities->remove_namespace('testimonials');
        }
    }
    /**
     * add or remove folders.
     * 
     * @access public
     * @param $action add|remove (default: 'add')
     * @return void
     */
    public function folders($action = 'add') {

        $this->load->library('files/files');
        $this->load->model('files/file_folders_m');

        $slug = 'testimonials';

        if ($action == 'add') {

            if( ! $this->file_folders_m->get_by('slug', $slug))
            {
                $parent_id = 0;
                Files::create_folder($parent_id, 'Testimonials', 'local');
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