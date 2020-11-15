<?php //

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

use Illuminate\Support\Facades\Route;

//direct use

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
Route::get('/change-password', 'Api\Settings\UserController@change_password')->name('change-password');

//user related
Route::get('/drop-user-session', 'Api\Settings\UserController@drop_user_session')->name('drop-user-session');
Route::get('/is-logged-in', 'Api\Settings\UserController@is_logged_in')->name('is-logged-in');
Route::get('/user-details', 'Api\Settings\UserController@get_user_details')->name('get-user-details');
Route::get('/user-permissions', 'Api\Settings\UserController@get_user_permissions')->name('get-user-permissions');

//fetching contact us
Route::get('/fetch/contact-us', 'Api\Settings\ContactController@get_list')->name('get-list-contact-us');
Route::post('/transmit/contact-us', 'Api\Settings\ContactController@insert')->name('transmit-contact-us');


//fetching data news/carousel/homepage content
Route::get('/fetch/about', 'Api\Content\AboutController@get_list')->name('get-about');

Route::get('/fetch/content', 'Api\Content\ContentController@get_list')->name('get-list-content');

//fetching data menu
Route::get('/fetch/menu', 'Api\Prefferences\MenuController@get_list')->name('get-list-menu');
Route::get('/fetch/menu/first', 'Api\Prefferences\MenuController@find')->name('get-menu');
Route::post('/transmit/menu', 'Api\Prefferences\MenuController@insert')->name('transmit-menu');
Route::post('/update/menu', 'Api\Prefferences\MenuController@update')->name('update-menu');

//fetching data icon
Route::get('/fetch/icon', 'Api\Master\IconController@get_list')->name('get-list-icon');

//fetching report
Route::get('/fetch/report-incidents', 'Api\Reports\IncidentsController@get_list')->name('get-list-reports-incidents');
Route::get('/fetch/report-incidents-logs', 'Api\Reports\IncidentsController@get_log_list')->name('get-reports-incidents-logs');
Route::get('/find/report-incidents', 'Api\Reports\IncidentsController@find')->name('find-reports-incidents');
Route::post('/transmit/report-incidents', 'Api\Reports\IncidentsController@insert')->name('transmit-report-incidents');

Route::get('/latest/activity', 'Api\Settings\UserController@get_latest_activity')->name('get-latest-activity');

//fetching citizen
Route::get('/fetch/citizen', 'Api\Master\CitizenController@get_list')->name('get-list-citizen');
Route::get('/fetch/citizen/{gender}', 'Api\Master\CitizenController@get_list')->name('get-list-citizen-gender');

//fetching children
Route::get('/fetch/children', 'Api\Master\ChildrenController@get_list')->name('get-list-children');

/*
 * fetching data family
 */
Route::get('/fetch/family', 'Api\Master\FamilyController@get_list')->name('get-list-family');
Route::get('/fetch/person-details', 'Api\Master\FamilyController@get_person_details')->name('get-person-details');
Route::post('/transmit/family', 'Api\Master\FamilyController@insert')->name('transmit-family');

/*
 * fetching data location
 */
Route::get('/fetch/countries', 'Api\Locations\CountryController@get_list')->name('get-list-country');
//
Route::get('/fetch/provinces', 'Api\Locations\ProvinceController@get_list')->name('get-list-province');
//
Route::get('/fetch/districts', 'Api\Locations\DistrictController@get_list')->name('get-list-district');
//
Route::get('/fetch/sub-districts', 'Api\Locations\SubDistrictController@get_list')->name('get-list-sub-district');
//
Route::get('/fetch/areas', 'Api\Locations\AreaController@get_list')->name('get-list-area');

/*
 * fetching data other incidents params
 */
Route::get('/fetch/isp', 'Api\Locations\IspController@get_list')->name('get-list-isp');
//
Route::get('/fetch/report-types', 'Api\Reports\TypesController@get_list')->name('get-list-report-types');
//
Route::get('/fetch/report-level', 'Api\Reports\LevelController@get_list')->name('get-list-report-level');
/* 
 * 
 * -------------------------------------------------------------------------------------------------------------------------------------------------*
 * 
 */
Route::get('/fetch/get-api-docs', 'Api\Documentation\AController@get_list')->name('get-list-api-docs');
Route::post('/fetch/get-api-docs', 'Api\Documentation\AController@find')->name('get-api-docs');

Route::get('/fetch/get-api-docs-type', 'Api\Documentation\BController@get_list')->name('get-list-api-type-docs');
Route::post('/fetch/get-api-docs-type', 'Api\Documentation\BController@find')->name('get-api-docs-type');
