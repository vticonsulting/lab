<?php
defined('BASEPATH') or exit('No direct script access allowed');

//
$route['calendar'] = 'calendar/show';

//
$route['care-requests'] = 'care_requests/index';

//
// $route['episodes']                = 'episodes/index';
// $route['episodes/(:id)']          = 'episodes/show';
// $route['episodes/(:id)/edit']     = 'episodes/edit';
// $route['episodes/(:id)']['patch'] = 'episodes/update';

//
$route['organizations/index']  = 'organizations/index';
$route['organizations/create'] = 'organizations/create';
$route['organizations/update'] = 'organizations/update';
$route['organizations/(:any)'] = 'organizations/show/$1';
$route['organizations']        = 'organizations/index';

//
$route['dashboard']  =  'dashboard/show';

//
$route['events/index']  = 'events/index';
$route['events/create'] = 'events/create';
$route['events/update'] = 'events/update';
$route['events/(:any)'] = 'events/show/$1';
$route['events']        = 'events/index';

//
// $route['podcasts']                  = 'podcasts/index';
// $route['podcasts/new']              = 'podcasts/create';
// $route['podcasts']['post']          = 'podcasts/store';
// $route['podcasts/(:id)']            = 'podcasts/show';
// $route['podcasts/(:id)/edit']       = 'podcasts/edit';
// $route['podcasts/(:id)']['patch']   = 'podcasts/update';
// $route['podcasts/(:id)']['delete']  = 'podcasts/destroy';

//
// $route['podcasts/(:id)/cover-image']['put'] =  'podcast_cover_image/update';

//
// $route['podcasts/(:id)/episodes']         = 'podcast_episodes/index';
// $route['podcasts/(:id)/episodes']['post'] = 'podcast_episodes/store';
// $route['podcasts/(:id)/episodes/new']     = 'podcast_episodes/create';

//
$route['posts/index']  = 'posts/index';
$route['posts/create'] = 'posts/create';
$route['posts/update'] = 'posts/update';
$route['posts/(:any)'] = 'posts/show/$1';
$route['posts']        = 'posts/index';

//
$route['profile'] = 'profile/show';

//
$route['properties/(:any)'] = 'properties/show/$1';
$route['properties']        = 'properties/index';

//
// $route['published-podcasts']['post']        = 'published_podcasts/store';
// $route['published-podcasts/(:id)']['delete'] = 'published_podcasts/destroy';

//
$route['reports/index']  = 'reports/index';
$route['reports/create'] = 'reports/create';
$route['reports/update'] = 'reports/update';
$route['reports/(:any)'] = 'reports/show/$1';
$route['reports']        = 'reports/index';

//
$route['resources']        = 'resources/index';
$route['resources/create'] = 'resources/create';

//
// $route['subscriptions']                 = 'subscriptions/store';
// $route['subscriptions/(:id)']['delete'] = 'subscriptions/destroy';


$route['site/(:any)'] = 'site/index';

$route['default_controller'] = 'pages/show';
$route['(:any)']             = 'pages/show/$1';

$route['404_override']         = '';
$route['translate_uri_dashes'] = false;
