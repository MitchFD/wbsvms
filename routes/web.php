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

// registration
Route::post('/register/email_availability_check', 'App\Http\Controllers\Auth\RegisterController@email_availability_check')->name('register.email_availability_check');
Route::post('/register/employee_process_registration', 'App\Http\Controllers\Auth\RegisterController@employee_process_registration')->name('register.employee_process_registration');
Route::post('/register/student_process_registration', 'App\Http\Controllers\Auth\RegisterController@student_process_registration')->name('register.student_process_registration');

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	// original
	// Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	// Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	// Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	// Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

	// custom profile
	Route::get('profile/index', ['as' => 'profile.index', 'uses' => 'App\Http\Controllers\ProfileController@index']);
	Route::post('profile/update_emp_user_profile', ['as' => 'profile.update_emp_user_profile', 'uses' => 'App\Http\Controllers\ProfileController@update_emp_user_profile']);
	Route::post('profile/update_stud_user_profile', ['as' => 'profile.update_stud_user_profile', 'uses' => 'App\Http\Controllers\ProfileController@update_stud_user_profile']);
});

// user management
Route::group(['middleware' => 'auth'], function () {
	Route::get('user_management', ['as' => 'user_management.index', 'uses' => 'App\Http\Controllers\UserManagementController@index']);
	Route::post('/user_management/new_user_email_availability_check', 'App\Http\Controllers\UserManagementController@new_user_email_availability_check')->name('user_management.new_user_email_availability_check');
	Route::post('/user_management/new_employee_user_process_registration', 'App\Http\Controllers\UserManagementController@new_employee_user_process_registration')->name('user_management.new_employee_user_process_registration');
	Route::post('/user_management/new_student_user_process_registration', 'App\Http\Controllers\UserManagementController@new_student_user_process_registration')->name('user_management.new_student_user_process_registration');

	// links
	Route::get('overview_users_management', ['as' => 'user_management.overview_users_management', 'uses' => 'App\Http\Controllers\UserManagementController@overview_users_management']);
	Route::get('create_users', ['as' => 'user_management.create_users', 'uses' => 'App\Http\Controllers\UserManagementController@create_users']);
	Route::get('system_users', ['as' => 'user_management.system_users', 'uses' => 'App\Http\Controllers\UserManagementController@system_users']);
		Route::get('user_profile/{user_id}', ['as' => 'user_management.user_profile', 'uses' => 'App\Http\Controllers\UserManagementController@user_profile']);
	Route::get('system_roles', ['as' => 'user_management.system_roles', 'uses' => 'App\Http\Controllers\UserManagementController@system_roles']);
	Route::get('users_logs', ['as' => 'user_management.users_logs', 'uses' => 'App\Http\Controllers\UserManagementController@users_logs']);

	// for activate/deactivate system users
	Route::get('user_management/deactivate_user_account_modal', 'App\Http\Controllers\UserManagementController@deactivate_user_account_modal')->name('user_management.deactivate_user_account_modal');
	Route::post('user_management/process_deactivate_user_account', 'App\Http\Controllers\UserManagementController@process_deactivate_user_account')->name('user_management.process_deactivate_user_account');
	Route::get('user_management/activate_user_account_modal', 'App\Http\Controllers\UserManagementController@activate_user_account_modal')->name('user_management.activate_user_account_modal');
	Route::post('user_management/process_activate_user_account', 'App\Http\Controllers\UserManagementController@process_activate_user_account')->name('user_management.process_activate_user_account');

	// live search filter for (system users)'s table
	Route::get('user_management/live_search_users_filter', 'App\Http\Controllers\UserManagementController@live_search_users_filter')->name('user_management.live_search_users_filter');

	// for activate/deactivate system roles
	Route::post('user_management/create_new_system_role', 'App\Http\Controllers\UserManagementController@create_new_system_role')->name('user_management.create_new_system_role');
	Route::post('user_management/update_user_role', 'App\Http\Controllers\UserManagementController@update_user_role')->name('user_management.update_user_role');
	Route::get('user_management/deactivate_role_modal', 'App\Http\Controllers\UserManagementController@deactivate_role_modal')->name('user_management.deactivate_role_modal');
	Route::post('user_management/process_deactivate_role', 'App\Http\Controllers\UserManagementController@process_deactivate_role')->name('user_management.process_deactivate_role');
	Route::get('user_management/activate_role_modal', 'App\Http\Controllers\UserManagementController@activate_role_modal')->name('user_management.activate_role_modal');
	Route::post('user_management/process_activate_role', 'App\Http\Controllers\UserManagementController@process_activate_role')->name('user_management.process_activate_role');

	// manage role first
	Route::get('user_management/manage_role_first_modal', 'App\Http\Controllers\UserManagementController@manage_role_first_modal')->name('user_management.manage_role_first_modal');

	// change user's role
	Route::get('user_management/change_user_role_modal', 'App\Http\Controllers\UserManagementController@change_user_role_modal')->name('user_management.change_user_role_modal');
	Route::post('user_management/process_change_user_role', 'App\Http\Controllers\UserManagementController@process_change_user_role')->name('user_management.process_change_user_role');

	// for updating system user's profile
	Route::post('user_management/update_stud_user_profile', ['as' => 'user_management.update_stud_user_profile', 'uses' => 'App\Http\Controllers\UserManagementController@update_stud_user_profile']);
	Route::post('user_management/update_emp_user_profile', ['as' => 'user_management.update_emp_user_profile', 'uses' => 'App\Http\Controllers\UserManagementController@update_emp_user_profile']);
	// if switching to new email address
	Route::post('/user_management/stud_user_switch_new_email_availability_check', 'App\Http\Controllers\UserManagementController@stud_user_switch_new_email_availability_check')->name('user_management.stud_user_switch_new_email_availability_check');
	Route::post('/user_management/emp_user_switch_new_email_availability_check', 'App\Http\Controllers\UserManagementController@emp_user_switch_new_email_availability_check')->name('user_management.emp_user_switch_new_email_availability_check');
	// for updating system user's passwords
	Route::post('user_management/update_user_password', ['as' => 'user_management.update_user_password', 'uses' => 'App\Http\Controllers\UserManagementController@update_user_password']);
});

// violation entry
Route::group(['middleware' => 'auth'], function () {
	Route::get('violation_entry/index', ['as' => 'violation_entry.index', 'uses' => 'App\Http\Controllers\ViolationEntryController@index']);
});

// violation records
Route::group(['middleware' => 'auth'], function () {
	Route::get('violation_records/index', ['as' => 'violation_records.index', 'uses' => 'App\Http\Controllers\ViolationRecordsController@index']);
});

// student handbook
Route::group(['middleware' => 'auth'], function () {
	Route::get('student_handbook/index', ['as' => 'student_handbook.index', 'uses' => 'App\Http\Controllers\StudentHandbookController@index']);
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
});


// Route::put('/log_me_out', 'App\Http\Controllers\Auth\LoginController@get_logout');