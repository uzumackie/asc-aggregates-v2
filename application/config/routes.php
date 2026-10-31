<?php
defined('BASEPATH') or exit('No direct script access allowed');

// ============ DEFAULT CONTROLLERS ============ //

$route['default_controller'] = 'baseController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['dashboard'] = 'baseController/dashboard';
$route['main'] = 'baseController/main';
$route['masterfiles/(:any)'] = 'baseController/masterfile/$1';
$route['transactions/(:any)'] = 'baseController/transaction/$1';
$route['reports/(:any)'] = 'baseController/reports/$1';
$route['tools/(:any)'] = 'baseController/tools/$1';
$route['sites/(:any)'] = 'baseController/site/$1';
$route['login'] = 'baseController/login';
$route['pass-reset'] = 'baseController/pass_reset';
$route['logout'] = 'baseController/logout';
$route['session_expire'] = 'baseController/session_expire';
