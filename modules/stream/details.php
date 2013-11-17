<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Stream extends Module
{

    public $version = '1.2';

    public function info()
    {
    	$stream_name = is_numeric($this->uri->segment(5)) ? $this->uri->segment(6) : $this->uri->segment(5);
    	$namespace = is_numeric($this->uri->segment(6)) ? $this->uri->segment(7) : $this->uri->segment(6);
        return array(
            'name' => array(
                'en' => 'Streams'
            ),
            'description' => array(
                'en' => 'Create streams for dynamic fields'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'data',
            'sections' => array(
                'stream' => array(
                    'name' => 'stream:streams',
                    'uri' => 'admin/stream',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'stream:new',
                            'uri' => 'admin/stream/create',
                            'class' => 'add'
                        )
                    )
                ),
                'fields' => array(
                    'name' => 'stream:fields',
                    'uri' => 'admin/stream/fields/index/'.$stream_name.'/'.$namespace,
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'stream:field:new',
                            'uri' => 'admin/stream/fields/create/'.$stream_name.'/'.$namespace,
                            'class' => 'add'
                        )
                    )
                ),
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
    	$this->stream_fields();
        // Your Upgrade Logic
        return true;
    }
	
	public function stream($action = 'add')
	{
		$this->load->driver('Streams');
		if($action == 'add')
		{
	        $this->load->language('stream/stream');
	
	        // Add streams streams
	        if ( ! $this->streams->utilities->convert_table_to_stream('data_streams', 'stream', null, 'streams', 'Create streams from streams', 'stream_name', $view_options = array('id', 'stream_name', 'stream_slug', 'stream_namespace'))) return false;
			
		}else{
			
	        //$this->streams->utilities->remove_namespace('stream');
		}
		return TRUE;
	}
	
	public function stream_fields()
	{
		$stream_slug = 'data_streams';
		$namespace = 'stream';
		
		$this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Stream name', 'stream_name', 'text', $extra = array('max_length' => '50'), array('required', true));
		$this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Stream slug', 'stream_slug', 'text', $extra = array('max_length' => '50'));
		$this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Stream namespace', 'stream_namespace', 'text', $extra = array('max_length' => '50'));
		$this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Stream prefix', 'stream_prefix', 'text', $extra = array('max_length' => '50'));
		$this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'About Stream', 'about', 'text', $extra = array('max_length' => '100'));
		$this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'View Options', 'view_options', 'text', $extra = array('max_length' => '100'));
		$this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Sorting by', 'sorting', 'choice', 
				$extra = array('choice_data' => "title : Title\ncustom : Custom", 'choice_type' => 'dropdown', 'default_value' => 'title'));
		$this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Is Hidden', 'is_hidden', 'choice', 
				$extra = array('choice_data' => "no : No\nyes : Yes", 'choice_type' => 'dropdown', 'default_value' => 'no'));
		return true;
	}
	
    public function help()
    {
        // Return a string containing help info
        // You could include a file and return it here.
        return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
    }

}