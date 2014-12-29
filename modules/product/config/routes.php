<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$route['product/admin/categories(:any)'] = 'admin_categories$1';
$route['product/admin(:any)'] = 'admin$1';

$route['product/cat/(:any)'] = 'product/cat/$1';
$route['product/cat/(:any)/(:any)'] = 'product/cat/$1/$2';

$route['product/(:any)'] = 'product/view/$1';