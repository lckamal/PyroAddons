<?php defined('BASEPATH') or exit('No direct script access allowed');

   class Module_imagepicker extends Module {

	public $version  = '0.1';

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
