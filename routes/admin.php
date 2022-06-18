<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 *
 * Admin Routes
 */


/**
 * Permissions
 */
Route::resource('permissions', 'PermissionController');

/**
 * Roles
 */
Route::resource('roles', 'RoleController');


/**
 * Users
 */
Route::resource('users', 'UserController');
Route::get('demo-users', 'UserController@demo')->name('users.demo');
Route::get('create-demo-users', 'UserController@createDemoUser')->name('users.create-demo');
Route::post('create-demo-users', 'UserController@saveDemoUser')->name('save-demo-users');
Route::get('create-demo-users/{id}/edit', 'UserController@editDemoUser')->name('users.edit-demo');
Route::put('create-demo-users/{id}', 'UserController@updateDemoUser')->name('users.update-demo');
Route::get('users/{user}/detail', 'UserController@detail')->name('users.detail');
Route::get('users/with-login/{user}', 'UserController@loginWithUser')->name('users.withLogin');
Route::post('save-gain-image', 'ReportController@saveGainImage')->name('save-gain-image');

Route::post('/ajax/notification', 'UserController@ajaxNotification')->name('users.ajax.notification');
/**
 * Companies
 */

Route::resource('companies', 'CompanyController');
Route::get('companies/process/sub-company/{id}', 'CompanyController@subCompany')->name('companies.sub_company');
Route::get('profile', 'CompanyController@profile')->name('companies.profile');

Route::resource('sectors', 'SectorController', ['except' => ['show']]);
Route::resource('egns', 'EgnController', ['except' => ['show']]);
Route::resource('packets', 'PacketController', ['except' => ['show']]);
Route::resource('departments', 'DepartmentController', ['except' => ['show']]);
Route::resource('jobs', 'JobController', ['except' => ['show']]);
Route::resource('work_titles', 'WorkTitleController', ['except' => ['show']]);
Route::resource('crm_supports', 'CrmSupportController');
Route::post('crm_supports_ajax', 'CrmSupportController@ajaxStore')->name('crm-support-ajax-store');


Route::get('chats', 'ChatsController@index');
Route::get('get-message/{id}', 'ChatsController@getMessage');
Route::post('set-messages', 'ChatsController@setMessage');
