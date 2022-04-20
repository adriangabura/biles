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


$route['default_controller'] = "admin";  
$route['404_override'] = '';
//$route['admin/login'] = 'admin_auth/login';
$route['login'] = 'admin_auth/login';
$route['logout'] = 'admin/logout';
$route['get_photo_add'] = 'admin/get_photo_add';
$route['del_photo'] = 'admin/del_photo';
$route['del_photos'] = 'admin/del_photos';
$route['move_photo'] = 'admin/move_photo';

$route['main'] = 'admin/main';

$route['rubrica'] = 'admin/rubrica';



$route['export'] = 'admin/export';

$route['get_raport'] = 'admin/get_raport';

$route['serdec'] = 'admin/serdec';
$route['raport'] = 'admin/raport';
$route['raport/(:num)'] = 'admin/raport/$1';
$route['raport/(:num)/(:num)'] = 'admin/raport/$1/$2';


$route['banners_form/(:num)/(:num)'] = 'admin/banners_form/$1/$2';
$route['banners_form/(:num)'] = 'admin/banners_form/$1';
$route['edit_banners'] = 'admin/edit_banners';
$route['add_banners']   = 'admin/add_banners';
$route['del_banners/(:num)'] = 'admin/del_banners/$1';
$route['banners/(:num)/(:num)'] = 'admin/banners/$1/$2';
$route['banners'] = 'admin/banners';
$route['banners/(:num)'] = 'admin/banners/$1';

$route['langs_form/(:num)/(:num)'] = 'systems/langs_form/$1/$2';
$route['langs_form/(:num)'] = 'systems/langs_form/$1';
$route['change_lang/(:num)'] = 'systems/change_lang/$1';
$route['edit_langs'] = 'systems/edit_langs';
$route['add_langs']   = 'systems/add_langs';
$route['del_langs/(:num)'] = 'systems/del_langs/$1';
$route['langs/(:num)/(:num)'] = 'systems/langs/$1/$2';
$route['langs'] = 'systems/langs';
$route['langs/(:num)'] = 'systems/langs/$1';


$route['social_form/(:num)/(:num)'] = 'admin/social_form/$1/$2';
$route['social_form/(:num)'] = 'admin/social_form/$1';
$route['edit_social'] = 'admin/edit_social';
$route['add_social']   = 'admin/add_social';
$route['del_social/(:num)'] = 'admin/del_social/$1';
$route['social/(:num)/(:num)'] = 'admin/social/$1/$2';
$route['social'] = 'admin/social';
$route['social/(:num)'] = 'admin/social/$1';

$route['do_upload'] = 'admin/do_upload';

$route['catalog_form/(:num)/(:num)'] = 'admin/catalog_form/$1/$2';
$route['catalog_form/(:num)'] = 'admin/catalog_form/$1';
$route['active_multi_prices'] = 'admin/active_multi_prices';
$route['edit_catalog'] = 'admin/edit_catalog';
$route['edit_upload_photo'] = 'admin/edit_upload_photo';
$route['delete_foto_color'] = 'admin/delete_foto_color';
$route['update_color'] = 'admin/update_color';
$route['add_catalog']   = 'admin/add_catalog';
$route['del_catalog/(:num)'] = 'admin/del_catalog/$1';
$route['catalog/(:num)/(:num)'] = 'admin/catalog/$1/$2';
$route['catalog'] = 'admin/catalog';
$route['catalog/(:num)'] = 'admin/catalog/$1';


$route['news_form/(:num)/(:num)'] = 'admin/news_form/$1/$2';
$route['news_form/(:num)'] = 'admin/news_form/$1';
$route['edit_news'] = 'admin/edit_news';
$route['add_news']   = 'admin/add_news';
$route['del_news/(:num)'] = 'admin/del_news/$1';
$route['news/(:num)/(:num)'] = 'admin/news/$1/$2';
$route['news'] = 'admin/news';
$route['news/(:num)'] = 'admin/news/$1';

$route['offers_form/(:num)/(:num)'] = 'admin/offers_form/$1/$2';
$route['offers_form/(:num)'] = 'admin/offers_form/$1';
$route['edit_offers'] = 'admin/edit_offers';
$route['add_offers']   = 'admin/add_offers';
$route['del_offers/(:num)'] = 'admin/del_offers/$1';
$route['offers/(:num)/(:num)'] = 'admin/offers/$1/$2';
$route['offers'] = 'admin/offers';
$route['offers/(:num)'] = 'admin/offers/$1';
$route['active_cons'] = 'admin/active_cons';


$route['text_form/(:num)/(:num)'] = 'admin/text_form/$1/$2';
$route['text_form/(:num)'] = 'admin/text_form/$1';
$route['edit_text'] = 'admin/edit_text';
$route['add_text']   = 'admin/add_text';
$route['del_text/(:num)'] = 'admin/del_text/$1';
$route['text/(:num)/(:num)'] = 'admin/text/$1/$2';
$route['text'] = 'admin/text';
$route['text/(:num)'] = 'admin/text/$1';


$route['slider_form/(:num)/(:num)'] = 'admin/slider_form/$1/$2';
$route['slider_form/(:num)'] = 'admin/slider_form/$1';
$route['edit_slider'] = 'admin/edit_slider';
$route['add_slider']   = 'admin/add_slider';
$route['del_slider/(:num)'] = 'admin/del_slider/$1';
$route['slider/(:num)/(:num)'] = 'admin/slider/$1/$2';
$route['slider'] = 'admin/slider';
$route['slider/(:num)'] = 'admin/slider/$1';


$route['baner_form/(:num)/(:num)'] = 'admin/baner_form/$1/$2';
$route['baner_form/(:num)'] = 'admin/baner_form/$1';
$route['edit_baner'] = 'admin/edit_baner';
$route['add_baner']   = 'admin/add_baner';
$route['del_baner/(:num)'] = 'admin/del_baner/$1';
$route['baner/(:num)/(:num)'] = 'admin/baner/$1/$2';
$route['baner'] = 'admin/baner';
$route['baner/(:num)'] = 'admin/baner/$1';

$route['del_photo'] = 'admin/del_photo';

//$route['do_upload'] = 'admin/do_upload';
$route['admin/get_last_photo'] = 'admin/get_last_photo';
$route['admin/radu'] = 'admin/radu';
$route['get_last_photo'] = 'admin/get_last_photo';
$route['admin/do_upload'] = '/admin/do_upload';
$route['vars'] = 'admin/vars';


$route['del_pages/(:num)'] = 'admin/del_pages/$1';
$route['del_offerts/(:num)'] = 'admin/del_offerts/$1';
$route['del_rev/(:num)'] = 'admin/del_rev/$1';

$route['pages_form/(:num)'] = 'admin/pages_form/$1';
$route['add_pages'] = 'admin/add_pages';


$route['pages'] = 'admin/pages';
$route['pages/(:num)'] = 'admin/pages/$1';
$route['pages_form/(:num)/(:num)'] = 'admin/pages_form/$1/$2';
$route['edit_pages'] = 'admin/edit_pages';
$route['pages/(:num)/(:num)'] = 'admin/pages/$1/$2';


$route['vars'] = 'systems/vars';
$route['vars/(:num)'] = 'systems/vars/$1';
$route['vars_form/(:num)'] = 'systems/vars_form/$1';
$route['vars_form'] = 'systems/vars_form';

$route['vars_form/(:num)/(:num)'] = 'systems/vars_form/$1/$2';
$route['edit_vars'] = 'systems/edit_vars';
$route['add_vars'] = 'systems/add_vars';


$route['snippets'] = 'systems/snippets';
$route['snippets/(:num)'] = 'systems/snippets/$1';
$route['snippets_form/(:num)'] = 'systems/snippets_form/$1';
$route['snippets_form'] = 'systems/snippets_form';

$route['snippets_form/(:num)/(:num)'] = 'systems/snippets_form/$1/$2';
$route['edit_snippets'] = 'systems/edit_snippets';
$route['add_snippets'] = 'systems/add_snippets';


$route['settings'] = 'systems/settings';
$route['settings/(:num)'] = 'systems/settings/$1';
$route['settings_form/(:num)'] = 'systems/settings/$1';
$route['settings_form/(:num)/(:num)'] = 'systems/settings_form/$1/$2';
$route['edit_settings'] = 'systems/edit_settings';
$route['add_settings'] = 'systems/add_settings';

$route['users'] = 'systems/users';
$route['users/(:num)'] = 'systems/users/$1';
$route['users_form'] = 'systems/users_form';
$route['users_form/(:num)'] = 'systems/users_form/$1';
$route['users_form/(:num)/(:any)'] = 'systems/users_form/$1/$2';
$route['users_form/(:num)/(:num)/(:num)'] = 'systems/users_form/$1/$2/$3';
$route['edit_users'] = 'systems/edit_users';
$route['add_users'] = 'systems/add_users';

// products
$route['del_obiective/(:num)'] = 'admin/del_obiective/$1';
$route['obiective_form/(:num)'] = 'admin/obiective_form/$1';
$route['add_obiective'] = 'admin/add_obiective';
$route['obiective'] = 'admin/obiective';
$route['obiective/(:num)'] = 'admin/obiective/$1';
$route['obiective_form/(:num)/(:num)'] = 'admin/obiective_form/$1/$2';
$route['edit_obiective'] = 'admin/edit_obiective';
$route['obiective/(:num)/(:num)'] = 'admin/obiective/$1/$2';

// auto
$route['del_auto/(:num)'] = 'admin/del_auto/$1';
$route['auto_form/(:num)'] = 'admin/auto_form/$1';
$route['add_auto'] = 'admin/add_auto';
$route['auto'] = 'admin/auto';
$route['auto/(:num)'] = 'admin/auto/$1';
$route['auto_form/(:num)/(:num)'] = 'admin/auto_form/$1/$2';
$route['edit_auto'] = 'admin/edit_auto';
$route['auto/(:num)/(:num)'] = 'admin/auto/$1/$2';

// truck
$route['del_truck/(:num)'] = 'admin/del_truck/$1';
$route['truck_form/(:num)'] = 'admin/truck_form/$1';
$route['add_truck'] = 'admin/add_truck';
$route['truck'] = 'admin/truck';
$route['truck/(:num)'] = 'admin/truck/$1';
$route['truck_form/(:num)/(:num)'] = 'admin/truck_form/$1/$2';
$route['edit_truck'] = 'admin/edit_truck';
$route['truck/(:num)/(:num)'] = 'admin/truck/$1/$2';

// moto
$route['del_moto/(:num)'] = 'admin/del_moto/$1';
$route['moto_form/(:num)'] = 'admin/moto_form/$1';
$route['add_moto'] = 'admin/add_moto';
$route['moto'] = 'admin/moto';
$route['moto/(:num)'] = 'admin/moto/$1';
$route['moto_form/(:num)/(:num)'] = 'admin/moto_form/$1/$2';
$route['edit_moto'] = 'admin/edit_moto';
$route['moto/(:num)/(:num)'] = 'admin/moto/$1/$2';

$route['del_zona/(:num)'] = 'admin/del_zona/$1';
$route['zona_form/(:num)'] = 'admin/zona_form/$1';
$route['add_zona'] = 'admin/add_zona';
$route['zona'] = 'admin/zona';
$route['zona/(:num)'] = 'admin/zona/$1';
$route['zona_form/(:num)/(:num)'] = 'admin/zona_form/$1/$2';
$route['edit_zona'] = 'admin/edit_zona';
$route['zona/(:num)/(:num)'] = 'admin/zona/$1/$2';


$route['rubrica'] = 'product/rubrica';
$route['rubrica/(:num)'] = 'product/rubrica/$1';
$route['rubrica_form/(:num)/(:num)'] = 'product/rubrica_form/$1/$2';
$route['rubrica_form/(:num)'] = 'product/rubrica_form/$1';
$route['edit_rubrica'] = 'product/edit_rubrica';
$route['add_rubrica']   = 'product/add_rubrica';
$route['del_rubrica/(:num)'] = 'product/del_rubrica/$1';
$route['rubrica/(:num)/(:num)'] = 'product/rubrica/$1/$2';


$route['categoria'] = 'product/categoria';
$route['categoria/(:num)'] = 'product/categoria/$1';
$route['categoria_form/(:num)/(:num)'] = 'product/categoria_form/$1/$2';
$route['categoria_form/(:num)'] = 'product/categoria_form/$1';
$route['edit_categoria'] = 'product/edit_categoria';
$route['add_categoria']   = 'product/add_categoria';
$route['del_categoria/(:num)'] = 'product/del_categoria/$1';
$route['categoria/(:num)/(:num)'] = 'product/categoria/$1/$2';

$route['criteriu'] = 'product/criteriu';
$route['criteriu/(:num)'] = 'product/criteriu/$1';
$route['criteriu_form/(:num)/(:num)'] = 'product/criteriu_form/$1/$2';
$route['criteriu_form/(:num)'] = 'product/criteriu_form/$1';
$route['edit_criteriu'] = 'product/edit_criteriu';
$route['add_criteriu']   = 'product/add_criteriu';
$route['del_criteriu/(:num)'] = 'product/del_criteriu/$1';
$route['criteriu/(:num)/(:num)'] = 'product/criteriu/$1/$2';
$route['edit_criteriu/(:num)'] = 'product/edit_criteriu/$1';

$route['criteriu_cat'] = 'product/criteriu_cat';
$route['criteriu_cat/(:num)'] = 'product/criteriu_cat/$1';
$route['criteriu_cat_form/(:num)/(:num)'] = 'product/criteriu_cat_form/$1/$2';
$route['criteriu_cat_form/(:num)'] = 'product/criteriu_cat_form/$1';
$route['edit_criteriu_cat'] = 'product/edit_criteriu_cat';
$route['add_criteriu_cat']   = 'product/add_criteriu_cat';
$route['del_criteriu_cat/(:num)'] = 'product/del_criteriu_cat/$1';
$route['criteriu_cat/(:num)/(:num)'] = 'product/criteriu_cat/$1/$2';
$route['edit_criteriu_cat/(:num)'] = 'product/edit_criteriu_cat/$1';


$route['add_crit_val'] = 'product/add_crit_val';
$route['del_crit_val'] = 'product/del_crit_val';

$route['users_site'] = 'systems/users_site';
$route['users_site/(:num)'] = 'systems/users_site/$1';
$route['users_site_form'] = 'systems/users_site_form';
$route['users_site_form/(:num)'] = 'systems/users_site_form/$1';
$route['users_site_form/(:num)/(:any)'] = 'systems/users_site_form/$1/$2';
$route['users_site_form/(:num)/(:num)/(:num)'] = 'systems/users_site_form/$1/$2/$3';
$route['edit_users_site'] = 'systems/edit_users_site';
$route['add_users_site'] = 'systems/add_users_site';

$route['users_site/(:num)/(:num)'] = 'systems/users_site/$1/$2';

$route['categoria_detaliu'] = 'product/categoria_detaliu';
$route['categoria_detaliu/(:num)'] = 'product/categoria_detaliu/$1';
$route['categoria_detaliu_form/(:num)/(:num)'] = 'product/categoria_detaliu_form/$1/$2';
$route['categoria_detaliu_form/(:num)/(:num)/(:num)'] = 'product/categoria_detaliu_form/$1/$2/$3';
$route['categoria_detaliu_form/(:num)'] = 'product/categoria_detaliu_form/$1';
$route['edit_categoria_detaliu'] = 'product/edit_categoria_detaliu';
$route['add_categoria_detaliu']   = 'product/add_categoria_detaliu';
$route['del_categoria_detaliu/(:num)'] = 'product/del_categoria_detaliu/$1';
$route['del_categoria_detaliu/(:num)/(:num)'] = 'product/del_categoria_detaliu/$1/$2';
$route['categoria_detaliu/(:num)/(:num)'] = 'product/categoria_detaliu/$1/$2';
$route['categoria_detaliu/(:num)/(:num)/(:num)'] = 'product/categoria_detaliu/$1/$2/$3';


$route['set_no_pay/(:num)/(:num)'] = 'systems/set_no_pay/$1/$2';

$route['ajax_get_categorii'] = 'admin/ajax_get_categorii';


// anunturi
$route['del_anunturi/(:num)'] = 'product/del_anunturi/$1';
$route['anunturi_form/(:num)'] = 'product/anunturi_form/$1';
$route['add_anunturi'] = 'product/add_anunturi';
$route['anunturi'] = 'product/anunturi';
$route['anunturi/(:num)'] = 'product/anunturi/$1';
$route['anunturi_form/(:num)/(:num)'] = 'product/anunturi_form/$1/$2';
$route['edit_anunturi'] = 'product/edit_anunturi';
$route['anunturi/(:num)/(:num)'] = 'product/anunturi/$1/$2';
$route['anunturi/(:num)'] = 'product/anunturi/$1';
$route['anunturi'] = 'product/anunturi';
/* End of file routes.php */
/* Location: ./application/config/routes.php */