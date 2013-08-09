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
        // We're using the streams API to
        // do data setup.
        $this->load->driver('Streams');

        $this->load->language('testimonials/testimonials');

        // Add faqs streams
        if ( ! $this->streams->streams->add_stream(lang('testimonials:testimonials'), 'testimonials', 'testimonials', 'testimonials_', null)) return false;

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
                'name' => 'Featured',
                'slug' => 'featured',
                'namespace' => 'testimonials',
                'type' => 'choice',
                'extra' => array('choice_type' => 'checkboxes', 'choice_data' => 'Yes'),
                'assign' => 'testimonials'
                )
        );

        $this->streams->fields->add_fields($fields);

        return true;
    }

    public function uninstall()
    {
        $this->load->driver('Streams');

        $this->streams->utilities->remove_namespace('testimonials');

        return true;
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