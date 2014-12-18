<?php defined('BASEPATH') or exit('No direct script access allowed');

   class Module_imagepicker extends Module {

	public $version  = '0.1';

	public function __construct()
    {
        parent::__construct();

        @set_time_limit(60);

        // Load in the FireSale library
        $this->lang->load('imagepicker/imagepicker');
        $this->load->library('streams_core/type');

        // Add our field type path
        $core_path = defined('PYROPATH') ? PYROPATH : APPPATH;

        if (is_dir(SHARED_ADDONPATH.'modules/imagepicker/field_types')) {
            $this->type->addon_paths['imagepicker'] = SHARED_ADDONPATH.'modules/imagepicker/field_types/';
        } elseif (is_dir($core_path.'modules/imagepicker/field_types')) {
            $this->type->addon_paths['imagepicker'] = $core_path.'modules/imagepicker/field_types/';
        } else {
            $this->type->addon_paths['imagepicker'] = ADDONPATH.'modules/imagepicker/field_types/';
        }

        $this->type->gather_types();
    }

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Image/File picker'
			),
			'description' => array(
				'en' => 'Image/File picker Module.'
			),
			'frontend' => TRUE,
			'backend' => TRUE
		);
	}
	
	public function install()
	{
		return TRUE;
	}
	
	public function uninstall()
	{
		return TRUE;
	}
	
	public function upgrade($old_version)
	{
		return TRUE;
	}
	
	public function help()
	{
		return "there is no help for this module for now";
	}
}
