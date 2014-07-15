<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$route['news/admin/categories(:any)'] = 'admin_categories$1';
$route['news/admin/streams(:any)'] = 'admin_streams$1';
$route['news/admin/newsfile(:any)?'] = 'admin_newsfile$1';
$route['news/admin(:any)?'] = 'admin$1';
$route['news/(:any)'] = 'news/$1';
