<?php defined('BASEPATH') OR exit('No direct script access allowed');
$route['scanmenu/(:any)'] = "hungry/scanmenu/$1";
$route['google-review'] = "hungry/google_review";
$route['qr-menu'] = "hungry/qrmenu";
$route['qr-category'] = "hungry/qrcategory";
$route['qr-categoryitems/(:any)'] = "hungry/qrcategoryitems/$1";
$route['addtocartqr'] = "hungry/addtocartqr";
$route['qr-app-cart'] = "hungry/appcart";
$route['app-details/(:any)/(:any)'] = "hungry/appdetails/$1/$2";
$route['app-details-update/(:any)/(:any)/(:any)'] = "hungry/appdetailsedit/$1/$2/$3";
$route['paymentsqr/(:any)/(:any)/(:any)'] = "hungry/paymentsqr/$1/$2/$3";
$route['payment-processqr'] = "hungry/payments_processqr";
$route['apporedrlist'] = "hungry/apporedrlist";
$route['updatemyorder/(:any)'] = "hungry/updatemyorder/$1";
$route['update-summery/(:any)'] = "hungry/update_summery/$1";

?>