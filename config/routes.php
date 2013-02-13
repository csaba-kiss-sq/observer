<?php  defined('BASEPATH') or exit('No direct script access allowed');

/*
// public
$route['(blog)/(:num)/(:num)/(:any)']   = 'blog/view/$4';
$route['(blog)/page(/:num)?']           = 'blog/index$2';
$route['(blog)/rss/all.rss']            = 'rss/index';
$route['(blog)/rss/(:any).rss']         = 'rss/category/$2';

// admin
*/
$route['observer/admin/merchants(/:any)?']  = 'admin_merchants$1';
$route['observer/admin/categories(/:any)?'] = 'admin_categories$1';
$route['observer/admin/charts(/:any)?']     = 'admin_charts$1';