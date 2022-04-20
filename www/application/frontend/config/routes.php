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

$route['default_controller'] = "main"; 
$route['404_override'] = '';

$route['(ro|ru|en)'] = 'main/index';
$route['limba/(:any)'] = 'main/limba/$1';



$route['(ro|ru|en)/about_us'] = 'main/about';
$route['(ro|ru|en)/catalog'] = 'main/catalog';
$route['(ro|ru|en)/products/(:any)'] = 'main/products/$2';
$route['(ro|ru|en)/news'] = 'main/news';
$route['(ro|ru|en)/news/(:any)'] = 'main/news/$2';
$route['(ro|ru|en)/info/(:any)'] = 'main/info/$2';
$route['(ro|ru|en)/contacts'] = 'main/contacts';
$route['(ro|ru|en)/search'] = 'main/search';







/* End of file routes.php */
/* Location: ./application/config/routes.php */
