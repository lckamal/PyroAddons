<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_Bootstrap extends Theme
{
    public $name			= 'Bootstrap';
    public $author			= 'lckamal';
    public $author_website	= 'http://www.lkamal.com.np';
    public $website			= '';
    public $description		= 'An HTML5 and Bootstrap 3 template.';
    public $version			= '1.0.0';
	public $options 		= array(
		'slider' => array(
			'title'         => 'Slider',
			'description'   => 'Would you like to display the slider on the homepage?',
			'default'       => 'yes',
			'type'          => 'radio',
			'options'       => 'yes=Yes|no=No',
			'is_required'   => true
		),
		'show_breadcrumbs' 	=> array(
			'title'         => 'Do you want to show breadcrumbs?',
			'description'   => 'If selected it shows a string of breadcrumbs at the top of the page.',
			'default'       => 'yes',
			'type'          => 'radio',
			'options'       => 'yes=Yes|no=No',
			'is_required'   => true
		),
	);
}

/* End of file theme.php */