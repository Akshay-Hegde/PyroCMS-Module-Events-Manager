<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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
| 	www.your-site.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://www.codeigniter.com/user_guide/general/routing.html
*/
// front
$route['(events_manager)/events'] = 'em_events/index';
$route['(events_manager)/events/category(/:any)'] = 'em_events/category$2';
$route['(events_manager)/events/page(/:num)'] = 'em_events/index$2';
$route['(events_manager)/event(/:any)'] = 'em_events/event$2';

$route['(events_manager)/calendar'] = 'em_calendar/index';
$route['(events_manager)/calendar/category(/:any)'] = 'em_calendar/category$2';
$route['(events_manager)/calendar/(:num)/(:num)'] = 'em_calendar/index/$2/$3';

// admin
$route['events_manager/admin/categories(/:any)?'] = 'admin_categories$1';
$route['events_manager/admin/fields(/:any)?'] = 'admin_fields$1';
$route['events_manager/admin/colors(/:any)?'] = 'admin_colors$1';
$route['events_manager/admin(/:any)?'] = 'admin$1';

