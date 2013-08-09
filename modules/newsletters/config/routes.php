<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$route['newsletters/admin/groups(/:any)?'] 		 	 = 'admin_groups$1';
$route['newsletters/admin/recipients(/:any)?'] 		 = 'admin_recipients$1';
$route['newsletters/admin/mails(/:any)?']            = 'admin_mails$1';
$route['newsletters/admin'] 			 	  		 = 'newsletters/admin/index';
