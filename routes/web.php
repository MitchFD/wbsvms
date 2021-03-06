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

// my profile
Route::group(['middleware' => 'auth'], function () {
	// original
	// Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	// Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	// Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	// Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

	// custom profile
	Route::get('profile/index', ['as' => 'profile.index', 'uses' => 'App\Http\Controllers\ProfileController@index']);
	// update profile
	Route::post('profile/update_emp_user_own_profile', ['as' => 'profile.update_emp_user_own_profile', 'uses' => 'App\Http\Controllers\ProfileController@update_emp_user_own_profile']);
	Route::post('profile/update_stud_user_own_profile', ['as' => 'profile.update_stud_user_own_profile', 'uses' => 'App\Http\Controllers\ProfileController@update_stud_user_own_profile']);
	// new email availability check
	Route::post('/profile/emp_user_switch_new_email_availability_check', 'App\Http\Controllers\ProfileController@emp_user_switch_new_email_availability_check')->name('profile.emp_user_switch_new_email_availability_check');
	Route::post('/profile/stud_user_switch_new_email_availability_check', 'App\Http\Controllers\ProfileController@stud_user_switch_new_email_availability_check')->name('profile.stud_user_switch_new_email_availability_check');
	// change password
	Route::post('profile/check_my_old_password', ['as' => 'profile.check_my_old_password', 'uses' => 'App\Http\Controllers\ProfileController@check_my_old_password']);
	Route::post('profile/update_my_password', ['as' => 'profile.update_my_password', 'uses' => 'App\Http\Controllers\ProfileController@update_my_password']);

	// access denied
	Route::get('profile/access_denied', ['as' => 'profile.access_denied', 'uses' => 'App\Http\Controllers\ProfileController@access_denied']);
});

// user management
Route::group(['middleware' => 'auth'], function () {
	Route::get('user_management', ['as' => 'user_management.index', 'uses' => 'App\Http\Controllers\UserManagementController@index']);
	Route::post('/user_management/new_user_email_availability_check', 'App\Http\Controllers\UserManagementController@new_user_email_availability_check')->name('user_management.new_user_email_availability_check');
	Route::post('/user_management/new_employee_user_process_registration', 'App\Http\Controllers\UserManagementController@new_employee_user_process_registration')->name('user_management.new_employee_user_process_registration');
	Route::post('/user_management/new_student_user_process_registration', 'App\Http\Controllers\UserManagementController@new_student_user_process_registration')->name('user_management.new_student_user_process_registration');

	// LINKS
	// overview users management
	Route::get('overview_users_management', ['as' => 'user_management.overview_users_management', 'uses' => 'App\Http\Controllers\UserManagementController@overview_users_management']);
	// create users page
	Route::get('create_users', ['as' => 'user_management.create_users', 'uses' => 'App\Http\Controllers\UserManagementController@create_users']);
	// system users page
	Route::get('system_users', ['as' => 'user_management.system_users', 'uses' => 'App\Http\Controllers\UserManagementController@system_users']);
		// user's profile
		Route::get('user_management/user_profile/{user_id}', ['as' => 'user_management.user_profile', 'uses' => 'App\Http\Controllers\UserManagementController@user_profile']);
		// load user's act logs from ajax request
		Route::get('user_management/user_act_logs', ['as' => 'user_management.user_act_logs', 'uses' => 'App\Http\Controllers\UserManagementController@user_act_logs']);
		// generate PDF - user's act logs
		Route::get('user_management/user_act_logs/pdf_user_logs/{user_id}/{range_from}/{range_to}/{category}', 'App\Http\Controllers\UserManagementController@pdf_user_logs');
	// system roles page
	Route::get('system_roles', ['as' => 'user_management.system_roles', 'uses' => 'App\Http\Controllers\UserManagementController@system_roles']);
	// user logs page
	Route::get('user_management/users_logs', 'App\Http\Controllers\UserManagementController@users_logs')->name('user_management.users_logs');
	// LINKS END

	// for activate/deactivate system users
	Route::get('user_management/deactivate_user_account_modal', 'App\Http\Controllers\UserManagementController@deactivate_user_account_modal')->name('user_management.deactivate_user_account_modal');
	Route::post('user_management/process_deactivate_user_account', 'App\Http\Controllers\UserManagementController@process_deactivate_user_account')->name('user_management.process_deactivate_user_account');
	Route::get('user_management/activate_user_account_modal', 'App\Http\Controllers\UserManagementController@activate_user_account_modal')->name('user_management.activate_user_account_modal');
	Route::post('user_management/process_activate_user_account', 'App\Http\Controllers\UserManagementController@process_activate_user_account')->name('user_management.process_activate_user_account');

	// deleting user accounts
	Route::get('user_management/temporary_delete_user_account_modal', 'App\Http\Controllers\UserManagementController@temporary_delete_user_account_modal')->name('user_management.temporary_delete_user_account_modal');

	// load system users table
	Route::get('user_management/load_system_users_table', 'App\Http\Controllers\UserManagementController@load_system_users_table')->name('user_management.load_system_users_table');

	// for activate/deactivate system roles
	Route::post('user_management/create_new_system_role', 'App\Http\Controllers\UserManagementController@create_new_system_role')->name('user_management.create_new_system_role');
	Route::post('user_management/update_user_role', 'App\Http\Controllers\UserManagementController@update_user_role')->name('user_management.update_user_role');
	Route::get('user_management/deactivate_role_modal', 'App\Http\Controllers\UserManagementController@deactivate_role_modal')->name('user_management.deactivate_role_modal');
	Route::post('user_management/process_deactivate_role', 'App\Http\Controllers\UserManagementController@process_deactivate_role')->name('user_management.process_deactivate_role');
	Route::get('user_management/activate_role_modal', 'App\Http\Controllers\UserManagementController@activate_role_modal')->name('user_management.activate_role_modal');
	Route::post('user_management/process_activate_role', 'App\Http\Controllers\UserManagementController@process_activate_role')->name('user_management.process_activate_role');

	// SYSTEM ROLES MODULE
	// load system roles cards
	Route::get('user_management/load_system_roles_cards', 'App\Http\Controllers\UserManagementController@load_system_roles_cards')->name('user_management.load_system_roles_cards'); 

	// for deleting system roles
	// temporary delete system role confirmation on modal
	Route::get('user_management/temporary_delete_system_role_confirmation_modal', 'App\Http\Controllers\UserManagementController@temporary_delete_system_role_confirmation_modal')->name('user_management.temporary_delete_system_role_confirmation_modal');
	// process temporary deletion of system role
	Route::post('user_management/process_temporary_delete_system_role', 'App\Http\Controllers\UserManagementController@process_temporary_delete_system_role')->name('user_management.process_temporary_delete_system_role');
	// permanent delete system role confirmation on modal
	// single permanent deletion
	Route::get('user_management/permanent_delete_system_role_confirmation_modal', 'App\Http\Controllers\UserManagementController@permanent_delete_system_role_confirmation_modal')->name('user_management.permanent_delete_system_role_confirmation_modal');
	// multiple permanent deletion
	Route::get('user_management/permanent_delete_all_system_role_confirmation_modal', 'App\Http\Controllers\UserManagementController@permanent_delete_all_system_role_confirmation_modal')->name('user_management.permanent_delete_all_system_role_confirmation_modal');
	// process permanent deletion of selected system role
	Route::post('user_management/process_permanent_delete_system_role', 'App\Http\Controllers\UserManagementController@process_permanent_delete_system_role')->name('user_management.process_permanent_delete_system_role');
	// recover deleted system role confirmation on modal
	// single recovery
	Route::get('user_management/recover_deleted_system_role_confirmation_modal', 'App\Http\Controllers\UserManagementController@recover_deleted_system_role_confirmation_modal')->name('user_management.recover_deleted_system_role_confirmation_modal');
	// multiple recovery
	Route::get('user_management/recover_all_deleted_system_role_confirmation_modal', 'App\Http\Controllers\UserManagementController@recover_all_deleted_system_role_confirmation_modal')->name('user_management.recover_all_deleted_system_role_confirmation_modal');
	// process permanent deletion of selected system role
	Route::post('user_management/process_recover_deleted_system_roles', 'App\Http\Controllers\UserManagementController@process_recover_deleted_system_roles')->name('user_management.process_recover_deleted_system_roles');

	// manage role first
	Route::get('user_management/manage_role_first_modal', 'App\Http\Controllers\UserManagementController@manage_role_first_modal')->name('user_management.manage_role_first_modal');

	// change user's role
	Route::get('user_management/change_user_role_modal', 'App\Http\Controllers\UserManagementController@change_user_role_modal')->name('user_management.change_user_role_modal');
	Route::post('user_management/process_change_user_role', 'App\Http\Controllers\UserManagementController@process_change_user_role')->name('user_management.process_change_user_role');

	// add new system role
	Route::get('user_management/add_new_system_role_modal', 'App\Http\Controllers\UserManagementController@add_new_system_role_modal')->name('user_management.add_new_system_role_modal');

	// for updating system user's profile
	Route::post('user_management/update_stud_user_profile', ['as' => 'user_management.update_stud_user_profile', 'uses' => 'App\Http\Controllers\UserManagementController@update_stud_user_profile']);
	Route::post('user_management/update_emp_user_profile', ['as' => 'user_management.update_emp_user_profile', 'uses' => 'App\Http\Controllers\UserManagementController@update_emp_user_profile']);
	// if switching to new email address
	Route::post('/user_management/stud_user_switch_new_email_availability_check', 'App\Http\Controllers\UserManagementController@stud_user_switch_new_email_availability_check')->name('user_management.stud_user_switch_new_email_availability_check');
	Route::post('/user_management/emp_user_switch_new_email_availability_check', 'App\Http\Controllers\UserManagementController@emp_user_switch_new_email_availability_check')->name('user_management.emp_user_switch_new_email_availability_check');
	// for updating system user's passwords
	Route::post('user_management/update_user_password', ['as' => 'user_management.update_user_password', 'uses' => 'App\Http\Controllers\UserManagementController@update_user_password']);

	// USER LOGS MODULE
	// filter users logs table
	Route::get('user_management/users_logs_filter_table', 'App\Http\Controllers\UserManagementController@users_logs_filter_table')->name('user_management.users_logs_filter_table');
	// get selected user's info based on selected user filter
	Route::get('user_management/users_logs_filter_table_user_info', 'App\Http\Controllers\UserManagementController@users_logs_filter_table_user_info')->name('user_management.users_logs_filter_table_user_info');
	// generate users activity logs confiration modal
	Route::get('user_management/generate_act_logs_confirmation_modal', 'App\Http\Controllers\UserManagementController@generate_act_logs_confirmation_modal')->name('user_management.generate_act_logs_confirmation_modal'); 
	// process export of activity logs = PDF
	Route::post('user_management/users_logs_report_pdf', 'App\Http\Controllers\UserManagementController@users_logs_report_pdf')->name('user_management.users_logs_report_pdf');
	// PDF viewer
	Route::post('user_management/report_viewer', 'App\Http\Controllers\UserManagementController@report_viewer')->name('user_management.report_viewer');

	// generate selected user's activity logs confiration modal
	Route::get('user_management/generate_sel_user_act_logs_confirmation_modal', 'App\Http\Controllers\UserManagementController@generate_sel_user_act_logs_confirmation_modal')->name('user_management.generate_sel_user_act_logs_confirmation_modal'); 
	// process export of selected user's activity logs = PDF
	Route::post('user_management/system_user_logs_report_pdf', 'App\Http\Controllers\UserManagementController@system_user_logs_report_pdf')->name('user_management.system_user_logs_report_pdf');
});

// violation entry
Route::group(['middleware' => 'auth'], function () {
	Route::get('violation_entry/index', ['as' => 'violation_entry.index', 'uses' => 'App\Http\Controllers\ViolationEntryController@index']);
	// live search violators
	Route::get('violation_entry/search_violators', 'App\Http\Controllers\ViolationEntryController@search_violators')->name('violation_entry.search_violators');
	// get selected student's info for pill display
	Route::get('violation_entry/get_selected_student_info', 'App\Http\Controllers\ViolationEntryController@get_selected_student_info')->name('violation_entry.get_selected_student_info');
	// open violation form
	Route::get('violation_entry/open_violation_form_modal', 'App\Http\Controllers\ViolationEntryController@open_violation_form_modal')->name('violation_entry.open_violation_form_modal');
	// submit and process violation form
	Route::post('violation_entry/submit_violation_form', 'App\Http\Controllers\ViolationEntryController@submit_violation_form')->name('violation_entry.submit_violation_form');
});

// violation records
Route::group(['middleware' => 'auth'], function () {
	// pages
	Route::get('violation_records/index', ['as' => 'violation_records.index', 'uses' => 'App\Http\Controllers\ViolationRecordsController@index']);
	// violator's profile
	Route::get('violation_records/violator/{violator_id}', ['as' => 'violation_records.violator', 'uses' => 'App\Http\Controllers\ViolationRecordsController@violator']);
	// deleted violation records page
	Route::get('violation_records/deleted_violation_records', ['as' => 'violation_records.deleted_violation_records', 'uses' => 'App\Http\Controllers\ViolationRecordsController@deleted_violation_records']);

	// open violation form
	Route::get('violation_records/new_violation_form_modal', 'App\Http\Controllers\ViolationRecordsController@new_violation_form_modal')->name('violation_records.new_violation_form_modal');

	// add sanctions form on modal
	Route::get('violation_records/add_sanction_form', 'App\Http\Controllers\ViolationRecordsController@add_sanction_form')->name('violation_records.add_sanction_form');
	// submit and process added sanctions form
	Route::post('violation_records/submit_sanction_form', 'App\Http\Controllers\ViolationRecordsController@submit_sanction_form')->name('violation_records.submit_sanction_form');
	// edit sanctions form on modal
	Route::get('violation_records/edit_sanction_form', 'App\Http\Controllers\ViolationRecordsController@edit_sanction_form')->name('violation_records.edit_sanction_form');
	// submit and process updated sanctions form
	Route::post('violation_records/update_sanction_form', 'App\Http\Controllers\ViolationRecordsController@update_sanction_form')->name('violation_records.update_sanction_form');
	// add sanctions to all Yearly Violations form on modal
	Route::get('violation_records/add_sanction_all_yearly_violations_form', 'App\Http\Controllers\ViolationRecordsController@add_sanction_all_yearly_violations_form')->name('violation_records.add_sanction_all_yearly_violations_form');
	// add sanctions to all Monthly Violations form on modal
	Route::get('violation_records/add_sanction_all_monthly_violations_form', 'App\Http\Controllers\ViolationRecordsController@add_sanction_all_monthly_violations_form')->name('violation_records.add_sanction_all_monthly_violations_form');
	// submit and process adding sanctions to all monthly violations
	Route::post('violation_records/process_adding_sanctions_all_violations', 'App\Http\Controllers\ViolationRecordsController@process_adding_sanctions_all_violations')->name('violation_records.process_adding_sanctions_all_violations');

	// add new sanctions form on modal
	Route::post('violation_records/add_new_sanctions', 'App\Http\Controllers\ViolationRecordsController@add_new_sanctions')->name('violation_records.add_new_sanctions');
	// delete sanctions form on modal
	Route::post('violation_records/delete_sanction_form', 'App\Http\Controllers\ViolationRecordsController@delete_sanction_form')->name('violation_records.delete_sanction_form');

	// temporary delete violation confirmation on modal
	Route::get('violation_records/delete_violation_form', 'App\Http\Controllers\ViolationRecordsController@delete_violation_form')->name('violation_records.delete_violation_form');
	// process temporary delete violation
	Route::post('violation_records/delete_violation', 'App\Http\Controllers\ViolationRecordsController@delete_violation')->name('violation_records.delete_violation');
	// permanent delete violation confirmation on modal
	Route::get('violation_records/permanently_delete_violation_form', 'App\Http\Controllers\ViolationRecordsController@permanently_delete_violation_form')->name('violation_records.permanently_delete_violation_form');

	// temporary delete all yearly violation confirmation on modal
	Route::get('violation_records/delete_all_yearly_violations_form', 'App\Http\Controllers\ViolationRecordsController@delete_all_yearly_violations_form')->name('violation_records.delete_all_yearly_violations_form');
	// temporary delete all monthly violation confirmation on modal
	Route::get('violation_records/delete_all_monthly_violations_form', 'App\Http\Controllers\ViolationRecordsController@delete_all_monthly_violations_form')->name('violation_records.delete_all_monthly_violations_form');
	// process temporary deletion of all violation
	Route::post('violation_records/delete_all_monthly_violations', 'App\Http\Controllers\ViolationRecordsController@delete_all_monthly_violations')->name('violation_records.delete_all_monthly_violations');
	// permanent delete all violations confirmation on modal
	Route::get('violation_records/permanent_delete_all_violations_form', 'App\Http\Controllers\ViolationRecordsController@permanent_delete_all_violations_form')->name('violation_records.permanent_delete_all_violations_form');

	// process permanent delete violation
	Route::post('violation_records/permanent_delete_violation', 'App\Http\Controllers\ViolationRecordsController@permanent_delete_violation')->name('violation_records.permanent_delete_violation');

	// single recovery of violations confirmation on modal
	Route::get('violation_records/recover_deleted_violation_form', 'App\Http\Controllers\ViolationRecordsController@recover_deleted_violation_form')->name('violation_records.recover_deleted_violation_form');
	// multiple recvery of violations confirmation on modal
	Route::get('violation_records/recover_all_deleted_violation_form', 'App\Http\Controllers\ViolationRecordsController@recover_all_deleted_violation_form')->name('violation_records.recover_all_deleted_violation_form');
	// process recover deleted violation
	Route::post('violation_records/recover_deleted_violation', 'App\Http\Controllers\ViolationRecordsController@recover_deleted_violation')->name('violation_records.recover_deleted_violation');

	// GENERATE REPORTS
	// generate violations records confiration modal
	Route::get('violation_records/generate_violation_records_confirmation_modal', 'App\Http\Controllers\ViolationRecordsController@generate_violation_records_confirmation_modal')->name('violation_records.generate_violation_records_confirmation_modal'); 
	// process violations records report - PDF
	Route::post('violation_records/violation_records_pdf', 'App\Http\Controllers\ViolationRecordsController@violation_records_pdf')->name('violation_records.violation_records_pdf'); 
	// generate violation records report
	Route::post('violation_records/report_violations_records', 'App\Http\Controllers\ViolationRecordsController@report_violations_records')->name('violation_records.report_violations_records');
	// generate report of violator's recorded offenses
	Route::get('violation_records/violator_offenses_report_confirmation_modal', 'App\Http\Controllers\ViolationRecordsController@violator_offenses_report_confirmation_modal')->name('violation_records.violator_offenses_report_confirmation_modal'); 
	// process violator's records report - PDF
	Route::post('violation_records/violator_records_pdf', 'App\Http\Controllers\ViolationRecordsController@violator_records_pdf')->name('violation_records.violator_records_pdf'); 

	// DELETED VIOLATIONS 
	// permanent delete all recently deleted violations confirmation on modal
	Route::get('violation_records/permanent_delall_recentlydelviolations_confirmation', 'App\Http\Controllers\ViolationRecordsController@permanent_delall_recentlydelviolations_confirmation')->name('violation_records.permanent_delall_recentlydelviolations_confirmation');
	// process permanent delete of all recently deleted violations
	Route::post('violation_records/process_permanent_delete_all_violations', 'App\Http\Controllers\ViolationRecordsController@process_permanent_delete_all_violations')->name('violation_records.process_permanent_delete_all_violations');
	// view deleted offenses' information on modal
	Route::get('violation_records/view_deleted_offenses_information_modal', 'App\Http\Controllers\ViolationRecordsController@view_deleted_offenses_information_modal')->name('violation_records.view_deleted_offenses_information_modal');

	// NOTIFY VIOLATO OF ALL RECORDED OFFENSES
	// notify violator confirmation on modal
	Route::get('violation_records/notify_violator_confirmation_modal', 'App\Http\Controllers\ViolationRecordsController@notify_violator_confirmation_modal')->name('violation_records.notify_violator_confirmation_modal'); 
	// process sending notification to violator
	Route::post('violation_records/process_send_notification_to_violator', 'App\Http\Controllers\ViolationRecordsController@process_send_notification_to_violator')->name('violation_records.process_send_notification_to_violator'); 

});

// sanctions
Route::group(['middleware' => 'auth'], function () {
	Route::get('sanctions/index', ['as' => 'sanctions.index', 'uses' => 'App\Http\Controllers\SanctionsController@index']);
	// register sanctions
	Route::post('sanctions/register_new_sanctions', 'App\Http\Controllers\SanctionsController@register_new_sanctions')->name('sanctions.register_new_sanctions');
	// open edit sanctions form on modal
	Route::get('sanctions/edit_sanctions_form', 'App\Http\Controllers\SanctionsController@edit_sanctions_form')->name('sanctions.edit_sanctions_form');
	// process update of selected sanctions
	Route::post('sanctions/process_update_selected_sanctions', 'App\Http\Controllers\SanctionsController@process_update_selected_sanctions')->name('sanctions.process_update_selected_sanctions');
	// open delete sanctions form confirmation on modal
	Route::get('sanctions/delete_sanctions_confirmation_form', 'App\Http\Controllers\SanctionsController@delete_sanctions_confirmation_form')->name('sanctions.delete_sanctions_confirmation_form');
	// process deletion of selected sanctions
	Route::post('sanctions/process_delete_selected_sanctions', 'App\Http\Controllers\SanctionsController@process_delete_selected_sanctions')->name('sanctions.process_delete_selected_sanctions');
});

// offenses
Route::group(['middleware' => 'auth'], function () {
	Route::get('offenses/index', ['as' => 'offenses.index', 'uses' => 'App\Http\Controllers\OffensesController@index']);
	// add new category form
	Route::get('offenses/add_new_category_form', 'App\Http\Controllers\OffensesController@add_new_category_form')->name('offenses.add_new_category_form');
	// process registration of new category
	Route::post('offenses/process_register_new_category', 'App\Http\Controllers\OffensesController@process_register_new_category')->name('offenses.process_register_new_category');
	// add new offense details form
	Route::get('offenses/add_new_offense_details_form', 'App\Http\Controllers\OffensesController@add_new_offense_details_form')->name('offenses.add_new_offense_details_form');
	// process registration of new offenses
	Route::post('offenses/process_register_new_offenses', 'App\Http\Controllers\OffensesController@process_register_new_offenses')->name('offenses.process_register_new_offenses');
	// edit selected offense's details form
	Route::get('offenses/edit_selected_offense_form', 'App\Http\Controllers\OffensesController@edit_selected_offense_form')->name('offenses.edit_selected_offense_form');


	// add new offense details to selected category
	Route::get('offenses/add_new_offense_details_to_selected_category_form', 'App\Http\Controllers\OffensesController@add_new_offense_details_to_selected_category_form')->name('offenses.add_new_offense_details_to_selected_category_form');
	// process registration of new offense details to selected category
	Route::post('offenses/process_register_new_offense_details_to_selected_category', 'App\Http\Controllers\OffensesController@process_register_new_offense_details_to_selected_category')->name('offenses.process_register_new_offense_details_to_selected_category');
	// edit selected Offense details
	Route::get('offenses/edit_selected_offense_details_form', 'App\Http\Controllers\OffensesController@edit_selected_offense_details_form')->name('offenses.edit_selected_offense_details_form');
	// process update of selected offense details
	Route::post('offenses/process_update_selected_offense_details', 'App\Http\Controllers\OffensesController@process_update_selected_offense_details')->name('offenses.process_update_selected_offense_details');
	// temporary delete selected Offense details confirmation on modal
	Route::get('offenses/temporary_delete_selected_offense_details_confirmation_modal', 'App\Http\Controllers\OffensesController@temporary_delete_selected_offense_details_confirmation_modal')->name('offenses.temporary_delete_selected_offense_details_confirmation_modal');
	// process temporary deletion of selected offense details
	Route::post('offenses/process_temporary_delete_selected_offense_details', 'App\Http\Controllers\OffensesController@process_temporary_delete_selected_offense_details')->name('offenses.process_temporary_delete_selected_offense_details');

	// DELETED OFFENSES 
	// load deleted offenses table
	Route::get('offenses/load_deleted_offenses_table', 'App\Http\Controllers\OffensesController@load_deleted_offenses_table')->name('offenses.load_deleted_offenses_table');
	// recover all temporary deleted offenses confirmation on modal
	Route::get('offenses/recover_all_temporary_deleted_offenses_confirmation', 'App\Http\Controllers\OffensesController@recover_all_temporary_deleted_offenses_confirmation')->name('offenses.recover_all_temporary_deleted_offenses_confirmation');
	// process recovery of selected temporary deleted offenses
	Route::post('offenses/process_recover_selected_teporary_deleted_offenses', 'App\Http\Controllers\OffensesController@process_recover_selected_teporary_deleted_offenses')->name('offenses.process_recover_selected_teporary_deleted_offenses');
	// permanent delete all temporary deleted offenses confirmation on modal
	Route::get('offenses/permanent_delete_all_temporary_deleted_offenses_confirmation', 'App\Http\Controllers\OffensesController@permanent_delete_all_temporary_deleted_offenses_confirmation')->name('offenses.permanent_delete_all_temporary_deleted_offenses_confirmation');
	// process permanent deletion of all temporary deleted offenses
	Route::post('offenses/process_permanent_delete_selected_teporary_deleted_offenses', 'App\Http\Controllers\OffensesController@process_permanent_delete_selected_teporary_deleted_offenses')->name('offenses.process_permanent_delete_selected_teporary_deleted_offenses');

	// view deleted offense's details on modal
	Route::get('offenses/view_deleted_offense_details_modal', 'App\Http\Controllers\OffensesController@view_deleted_offense_details_modal')->name('offenses.view_deleted_offense_details_modal');
});

// disciplinary policies
Route::group(['middleware' => 'auth'], function () {
	// sdca student hand book
	Route::get('disciplinary_policies/student_handbook', ['as' => 'disciplinary_policies.student_handbook', 'uses' => 'App\Http\Controllers\DisciplinaryPoliciesController@student_handbook']);

	// online class policies
	Route::get('disciplinary_policies/online_class_policies', ['as' => 'disciplinary_policies.online_class_policies', 'uses' => 'App\Http\Controllers\DisciplinaryPoliciesController@online_class_policies']);
});

// pages
Route::group(['middleware' => 'auth'], function () {
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
	Route::get('dashboard/load_contents', ['as' => 'dashboard.load_contents', 'uses' => 'App\Http\Controllers\PageController@load_contents']);
});


// Route::put('/log_me_out', 'App\Http\Controllers\Auth\LoginController@get_logout');