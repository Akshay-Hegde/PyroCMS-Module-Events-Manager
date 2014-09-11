<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
*/

// front
$route['(events_manager)/events'] = 'em_events/index';
$route['(events_manager)/events/category(/:any)'] = 'em_events/category$2';
$route['(events_manager)/events(/:num)'] = 'em_events/index$2';
$route['(events_manager)/event(/:any)'] = 'em_events/event$2';

$route['(events_manager)/calendar'] = 'em_calendar/index';
$route['(events_manager)/calendar/category(/:any)'] = 'em_calendar/category$2';
$route['(events_manager)/calendar/(:num)/(:num)'] = 'em_calendar/index/$2/$3';
$route['(events_manager)/calendar/(:num)/(:num)/(:num)'] = 'em_calendar/index/$2/$3/$4';

// admin
$route['events_manager/admin/categories(/:any)?'] = 'admin_categories$1';
$route['events_manager/admin/fields(/:any)?'] = 'admin_fields$1';
$route['events_manager/admin/colors(/:any)?'] = 'admin_colors$1';
$route['events_manager/admin/export(/:any)?'] = 'admin_export$1';
$route['events_manager/admin/settings(/:any)?'] = 'admin_settings$1';
//$route['events_manager/admin(/:any)?'] = 'admin';
//$route['events_manager/admin(/:any)?'] = 'admin/index';