<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "admin/login";
$route['404_override'] = '';

$route['newsletter/(:num)'] = "newsletter/index/$1";

$route['errors/(:num)'] = "util/error_manager/index/$1";

$route['gallery/(:num)'] = "gallery/index/$1";

$route['admin'] = "admin/main";
$route['logout'] = "admin/login/logout";
$route['offers/(:num)'] = "offers/index/$1";
$route['iris/contact'] = "util/error_manager/issue";

$route['reservations/(:num)'] = "reservations/index/$1";
$route['admin/reservations/(:num)'] = "admin/reservations/index/$1";
$route['admin/reservations/(:num)/confirm'] = "admin/reservations/confirm/$1";
$route['admin/reservations/(:num)/unconfirm'] = "admin/reservations/confirm/$1/0";

$route['admin/websites/(:num)/edit'] = "admin/websites/edit/$1";
$route['admin/websites/(:num)/remove'] = "admin/websites/remove/$1";

$route['admin/subscription'] = "admin/websites";
$route['admin/subscription/add'] = "admin/websites/add_subscr"; // Add a subscription
$route['admin/subscription/add/(:num)'] = "admin/websites/add_subscr/$1"; // Add a subscription
$route['admin/subscription/extend/(:num)/(:any)'] = "admin/websites/extend_subscr/$1/$2"; //Extend a subscription
$route['admin/subscription/remove/(:num)/(:any)'] = "admin/websites/extend_subscr/$1/$2/TRUE"; //Removes a service

$route['admin/users/(:num)'] = "admin/users/index/$1";
$route['admin/newsletter/user/(:any)/(:any)'] = "admin/newsletter/user/$1/$2";
$route['admin/newsletter/(:any)/(:any)'] = "admin/newsletter/action/$2/$1";


/* End of file routes.php */
/* Location: ./application/config/routes.php */