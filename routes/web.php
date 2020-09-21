<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/', 'Api\Settings\UserController@index')->name('/');

Route::get('/generate-token-access', 'Api\Settings\UserController@generate_token_access')->name('generate-token-access');
Route::get('/generate-token-user', 'Api\Settings\UserController@generate_token_user')->name('generate-token-user');
Route::get('/validate-token', 'Api\Settings\UserController@validate_token')->name('validate-token');
Route::get('/verify-password', 'Api\Settings\UserController@verify_password')->name('verify-password');

//user related
Route::get('/drop-user-session', 'Api\Settings\UserController@drop_user_session')->name('drop-user-session');
Route::get('/is-logged-in', 'Api\Settings\UserController@is_logged_in')->name('is-logged-in');
Route::get('/user-details', 'Api\Settings\UserController@get_user_details')->name('get-user-details');
Route::get('/user-permissions', 'Api\Settings\UserController@get_user_permissions')->name('get-user-permissions');

//fetching data news/carousel/homepage content
Route::get('/fetch/about', 'Api\Content\AboutController@get_list')->name('get-about');

Route::get('/fetch/content', 'Api\Content\ContentController@get_list')->name('get-list-country');
Route::get('/find/content', 'Api\Content\ContentController@find')->name('get-country');

//fetching data menu
Route::get('/fetch/menu', 'Api\Prefferences\MenuController@get_list')->name('get-list-menu');
Route::get('/fetch/menu/first', 'Api\Prefferences\MenuController@find')->name('get-menu');
Route::post('/transmit/menu', 'Api\Prefferences\MenuController@insert')->name('transmit-menu');
Route::post('/update/menu', 'Api\Prefferences\MenuController@update')->name('update-menu');

//fetching data icon
Route::get('/fetch/icon', 'Api\Master\IconController@get_list')->name('get-list-icon');

//fetching report
Route::get('/fetch/report-incidents', 'Api\Reports\IncidentsController@get_list')->name('get-list-reports-incidents');
Route::get('/find/report-incidents', 'Api\Reports\IncidentsController@find')->name('find-reports-incidents');
Route::post('/transmit/report-incidents', 'Api\Reports\IncidentsController@insert')->name('transmit-report-incidents');
Route::get('/latest/report-incidents', 'Api\Reports\IncidentsController@get_latest_list')->name('get-latest-reports-incidents');

Route::get('/latest/activity', 'Api\Settings\UserController@get_latest_activity')->name('get-latest-activity');

Route::get('/fetch/citizen/{key}', 'Api\Master\CitizenController@get_list')->name('get-list-citizen');
Route::get('/fetch/family/', 'Api\Master\FamilyController@get_list')->name('get-list-family');
Route::get('/fetch/children', 'Api\Reports\IncidentsController@get_list')->name('get-list-reports');
////fetching data location
//Route::get('/fetch/countries', 'Api\Locations\CountryController@get_list')->name('get-list-country');
//Route::post('/fetch/countries', 'Api\Locations\CountryController@find')->name('get-country');
//
//Route::get('/fetch/provinces', 'Api\Locations\ProvinceController@get_list')->name('get-list-province');
//Route::post('/fetch/provinces', 'Api\Locations\ProvinceController@find')->name('get-province');
//
//Route::get('/fetch/districts', 'Api\Locations\DistrictController@get_list')->name('get-list-district');
//Route::post('/fetch/districts', 'Api\Locations\DistrictController@find')->name('get-district');
//
//Route::get('/fetch/sub-districts', 'Api\Locations\SubDistrictController@get_list')->name('get-list-sub-district');
//Route::post('/fetch/sub-districts', 'Api\Locations\SubDistrictController@find')->name('get-sub-district');
//
//Route::get('/fetch/areas', 'Api\Locations\AreaController@get_list')->name('get-list-area');
//Route::post('/fetch/areas', 'Api\Locations\AreaController@find')->name('get-area');
//
////fetching report
//Route::get('/fetch/report-incidents', 'Api\Reports\IncidentsController@get_list')->name('get-list-reports');
//Route::post('/fetch/report-incidents', 'Api\Reports\IncidentsController@find')->name('get-reports');
//Route::post('/transmit/report-incident', 'Api\Reports\IncidentsController@insert')->name('transmit-report');
//
//Route::get('/fetch/report-types', 'Api\Reports\TypesController@get_list')->name('get-list-report-types');
//Route::post('/fetch/report-types', 'Api\Reports\TypesController@find')->name('get-report-types');
//
////fetching data master
////data integrated services posts (posyandu)
//Route::get('/fetch/integrated-services-posts', 'Api\Master\IntegratedServicesPostController@get_list')->name('get-list-integrated-services-posts');
//Route::post('/fetch/integrated-services-posts', 'Api\Master\IntegratedServicesPostController@find')->name('get-integrated-services-posts');
//Route::post('/transmit/integrated-services-post', 'Api\Master\IntegratedServicesPostController@insert')->name('transmit-integrated-services-post');
//
////fetching data family
//Route::get('/fetch/family/', 'Api\Master\FamilyController@get_list')->name('get-list-family');
//Route::post('/fetch/family/', 'Api\Master\FamilyController@find')->name('get-family');
//Route::post('/fetch/person-details', 'Api\Master\FamilyController@get_person_details')->name('get-person-details');
//Route::post('/transmit/family', 'Api\Master\FamilyController@insert')->name('transmit-family');
//
////fetching data family properties
//Route::get('/fetch/family-properties', 'Api\Master\PropertyController@get_list')->name('get-list-properties');
//Route::post('/fetch/family-properties', 'Api\Master\PropertyController@find')->name('get-property');
//Route::post('/transmit/family-property', 'Api\Master\PropertyController@insert')->name('transmit-property');
//

////fetching data volunteer
//Route::get('/fetch/volunteer/', 'Api\Locations\VolunteerController@get_list')->name('get-list-volunteer');
//
////fetching data web settings
//Route::get('/fetch/config', 'Api\Settings\UserController@get_config')->name('get-config');
//Route::get('/fetch/menus', 'Api\Settings\UserController@get_menus')->name('get-menus');
//Route::get('/fetch/icons', 'Api\Settings\UserController@get_icons')->name('get-icons');
//Route::get('/fetch/groups', 'Api\Settings\UserController@get_groups')->name('get-groups');
//
//
////put route
//
//
//
////post route
//
//
//
////delete route
//
//
//
////
////Api DOCS start here
////


Route::get('/fetch/get-api-docs', 'Api\Documentation\AController@get_list')->name('get-list-api-docs');
Route::post('/fetch/get-api-docs', 'Api\Documentation\AController@find')->name('get-api-docs');

Route::get('/fetch/get-api-docs-type', 'Api\Documentation\BController@get_list')->name('get-list-api-type-docs');
Route::post('/fetch/get-api-docs-type', 'Api\Documentation\BController@find')->name('get-api-docs-type');