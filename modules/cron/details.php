<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Cron extends Module {

	public $version = '2.1';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Cron'
			),
			'description' => array(
				'en' => 'Enable scheduled tasks for your website.'
			),
			'default_install' => false,
			'frontend' => false,
			'backend' => false,
		);
	}

	public function install()
	{
		return true;
	}

	public function uninstall()
	{
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
/* End of file details.php */
