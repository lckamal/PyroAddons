<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$route['mystream/admin/mystreams(:any)?'] = 'admin_mystreams$1';
$route['mystream/admin/fields(:any)?'] = 'admin_fields$1';
$route['mystream/admin/(:num)?'] = 'admin/index/$1';