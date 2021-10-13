<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = 'handle_404';
$route['translate_uri_dashes'] = false;
$route['user/registration'] = 'user/register';

$route['chotel'] = 'chotel/main';
//$route['chotel/huserinit'] = 'chotel/huserinit';
$route['gman'] = 'gman/main';
$route['b2b'] = 'b2b/dashboard';

$route['sitemap\.xml'] = "Sitemap/index";

$route['^en/(.+)$'] = "$1";
$route['^ar/(.+)$'] = "$1";
// '/en' and '/ar' -> use default controller
$route['^(en|ar)$'] = $route['default_controller'];