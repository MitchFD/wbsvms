<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Userroles;
use App\Models\Userrolesupdatestatus;
use App\Models\Editedolduserroles;
use App\Models\Editednewuserroles;
use App\Models\Users;
use App\Models\Userupdatesstatus;
use App\Models\Useremployees;
use App\Models\Editedolduseremployees;
use App\Models\Editednewuseremployees;
use App\Models\Userstudents;
use App\Models\Editedoldstudentusers;
use App\Models\Editednewstudentusers;
use App\Models\Useractivites;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function index(){
        // users
        $active_users      = Users::where('user_status', 'active')->where('user_role_status', 'active')->get();
        $deactivated_users = Users::where('user_status', 'deactivated')->orWhere('user_role_status', 'deactivated')->get();
        $pending_users     = Users::where('user_role', 'pending')->where('user_status', 'pending')->where('user_role_status', 'pending')->get();
        $deleted_users     = Users::where('user_status', 'deleted')->get();
        $registered_users  = Users::where('user_status', '!=', 'deleted')->get();

        // user roles
        $active_roles      = Userroles::where('uRole_status', 'active')->get();
        $deactivated_roles = Userroles::where('uRole_status', 'deactivated')->get();
        $deleted_roles     = Userroles::where('uRole_status', 'deleted')->get();
        $registered_roles  = Userroles::where('uRole_status', '!=', 'deleted')->get();

        // user activities
        $all_activities = Useractivites::get();

        return view('user_management.index')->with(compact('active_users', 'deactivated_users', 'pending_users', 'deleted_users', 'registered_users', 'active_roles', 'deactivated_roles', 'deleted_roles', 'registered_roles', 'all_activities'));
    }

    // sub-modules
    // overview_users_management
    public function overview_users_management(){
        // users
        $active_users      = Users::where('user_status', 'active')->where('user_role_status', 'active')->get();
        $deactivated_users = Users::where('user_status', 'deactivated')->orWhere('user_role_status', 'deactivated')->get();
        $pending_users     = Users::where('user_role', 'pending')->where('user_status', 'pending')->where('user_role_status', 'pending')->get();
        $deleted_users     = Users::where('user_status', 'deleted')->get();
        $registered_users  = Users::where('user_status', '!=', 'deleted')->get();

        // user roles
        $active_roles      = Userroles::where('uRole_status', 'active')->get();
        $deactivated_roles = Userroles::where('uRole_status', 'deactivated')->get();
        $deleted_roles     = Userroles::where('uRole_status', 'deleted')->get();
        $registered_roles  = Userroles::where('uRole_status', '!=', 'deleted')->get();

        return view('user_management.overview')->with(compact('active_users', 'deactivated_users', 'pending_users', 'deleted_users', 'registered_users', 'active_roles', 'deactivated_roles', 'deleted_roles', 'registered_roles'));
    }

    // create_users
    public function create_users(){
        $employee_system_roles = Userroles::where('uRole_type', 'employee')->get();
        $student_system_roles  = Userroles::where('uRole_type', 'student')->get();
        return view('user_management.create_users')->with(compact('employee_system_roles', 'student_system_roles'));
    }

    // system_users
    public function system_users(){
        return view('user_management.system_users');
    }

    // system_roles
    public function system_roles(){
        return view('user_management.system_roles');
    }

    // users_logs
    public function users_logs(){
        return view('user_management.users_logs');
    }

    // emailavailability check
    public function new_user_email_availability_check(Request $request){
        if($request->get('email')){
            $email = $request->get('email');
            $data  = Users::where('email', $email)->count();
            if($data > 0){
                echo 'not_unique';
            }else{
                echo 'unique';
            }
        }
    }

    // process registration of new employee type user
    public function new_employee_user_process_registration(Request $request){
        $this->validate($request, [
            'email' => 'email',
        ]);

        // get all request
            $create_emp_role      = $request->get('create_emp_role');
            $create_emp_id        = $request->get('create_emp_id');
            $create_emp_lname     = $request->get('create_emp_lname');
            $create_emp_fname     = $request->get('create_emp_fname');
            $create_emp_gender    = $request->get('create_emp_gender');
            $create_emp_jobdesc   = $request->get('create_emp_jobdesc');
            $create_emp_dept      = $request->get('create_emp_dept');
            $create_emp_phnum     = $request->get('create_emp_phnum');
            $create_emp_email     = $request->get('create_emp_email');
            $get_respo_user_id    = $request->get('respo_user_id');
            $get_respo_user_lname = $request->get('respo_user_lname');
            $get_respo_user_fname = $request->get('respo_user_fname');

        // custom values
            $now_timestamp        = now();
            $active_txt           = 'active';
            $employee_txt         = 'employee';
            $employee_image       = 'employee_user_image.jpg';
            $sq                   = "'";
            $format_now_timestamp = $now_timestamp->format('dmYHis');
            $get_current_year     = $now_timestamp->format('Y');
            $lower_emp_role       = Str::lower($create_emp_role);
            $lower_emp_gender     = Str::lower($create_emp_gender);
        
        // user image handler
            if($request->hasFile('create_emp_user_image')){
                $get_filenameWithExt = $request->file('create_emp_user_image')->getClientOriginalName();
                $get_justFile        = pathinfo($get_filenameWithExt, PATHINFO_FILENAME);
                $get_justExt         = $request->file('create_emp_user_image')->getClientOriginalExtension();
                $fileNameToStore     = $get_justFile.'_'.$format_now_timestamp.'.'.$get_justExt;
                $uploadImageToPath   = $request->file('create_emp_user_image')->storeAs('public/svms/user_images',$fileNameToStore);
            }else{
                $fileNameToStore = $employee_image;
            }

        // generate unique password
            $create_emp_password = Str::lower($create_emp_lname).'@svms'.$get_current_year;

        // get the status of selected system role
            $get_status_selected_role = Userroles::select('uRole_status')->where('uRole', $lower_emp_role)->first();
            $status_of_selected_role  = $get_status_selected_role->uRole_status;


        // save data to users table
            $reg_emp_user = new Users;
            $reg_emp_user->email             = $create_emp_email;
            $reg_emp_user->email_verified_at = $now_timestamp;
            $reg_emp_user->password          = Hash::make($create_emp_password);
            $reg_emp_user->user_role         = $lower_emp_role;
            $reg_emp_user->user_status       = $active_txt;
            $reg_emp_user->user_role_status  = $status_of_selected_role;
            $reg_emp_user->user_type         = $employee_txt;
            $reg_emp_user->user_sdca_id      = $create_emp_id;
            $reg_emp_user->user_image        = $fileNameToStore;
            $reg_emp_user->user_lname        = $create_emp_lname;
            $reg_emp_user->user_fname        = $create_emp_fname;
            $reg_emp_user->user_gender       = $lower_emp_gender;
            $reg_emp_user->registered_by     = $get_respo_user_id;
            $reg_emp_user->created_at        = $now_timestamp;
            $reg_emp_user->save();
        // save data to user_employees_tbl table
            $reg_emp_info = new Useremployees;
            $reg_emp_info->uEmp_id       = $create_emp_id;
            $reg_emp_info->uEmp_job_desc = $create_emp_jobdesc;
            $reg_emp_info->uEmp_dept     = $create_emp_dept;
            $reg_emp_info->uEmp_phnum    = $create_emp_phnum;
            $reg_emp_info->created_at    = $now_timestamp;
            $reg_emp_info->save();

        // if registration was a success
        if($reg_emp_user AND $reg_emp_info){
            // get new user's id for activity reference
                $get_new_emp_user_id = Users::select('id')->where('user_sdca_id', $create_emp_id)->latest('created_at')->first();
                $new_reg_user_id     = $get_new_emp_user_id->id;

            // record activity
                $record_act = new Useractivites;
                $record_act->created_at            = $now_timestamp;
                $record_act->act_respo_user_id     = $get_respo_user_id;
                $record_act->act_respo_users_lname = $get_respo_user_lname;
                $record_act->act_respo_users_fname = $get_respo_user_fname;
                $record_act->act_type              = 'create user';
                $record_act->act_details           = 'Registered ' .$create_emp_fname. ' ' .$create_emp_lname. ' as a ' .$create_emp_role. ' of the system.';
                $record_act->act_affected_id       = $new_reg_user_id;
                $record_act->save();
            
                if($record_act){
                    return back()->withSuccessStatus('New Employee User Account was registered successfully!');
                }else{
                    return back()->withFailedStatus('New Employee User Account has failed to register. Try again later.');
                }
        }else{
            return back()->withFailedStatus('New Employee User Account has failed to register. Try again later.');
        }

        // echo 'REGISTER NEW EMPLOYEE USER <br />';
        // echo 'System Role: ' .$create_emp_role. ' <br />';
        // echo 'System Role Status: ' .$status_of_selected_role. ' <br />';
        // echo 'Employee ID: ' .$create_emp_id. ' <br />';
        // echo 'image: ' .$fileNameToStore. ' <br />';
        // echo 'Last Name: ' .$create_emp_lname. ' <br />';
        // echo 'First Name: ' .$create_emp_fname. ' <br />';
        // echo 'Gender: ' .$lower_emp_gender. ' <br />';
        // echo 'Job Description: ' .$create_emp_jobdesc. ' <br />';
        // echo 'Department: ' .$create_emp_dept. ' <br />';
        // echo 'Phone Number: ' .$create_emp_phnum. ' <br />';
        // echo 'email: ' .$create_emp_email. ' <br />';
        // echo 'password: ' .$create_emp_password. ' <br />';
        // echo '<br />';
        // echo 'by: ' .$get_respo_user_id. ' <br />';
        // echo 'lname: ' .$get_respo_user_lname. ' <br />';
        // echo 'fname: ' .$get_respo_user_fname. ' <br />';
    }
    // process registration of new student type user
    public function new_student_user_process_registration(Request $request){
        echo 'wow student ka toi?';
    }

    // FUNCTIONS FOR SYSTEM ROLES
    // update role
    public function update_user_role(Request $request){
        // now timestamp
            $now_timestamp  = now();

        // get all requests
            $get_edit_selected_uRole_id = $request->get('edit_selected_uRole_id');
            $get_edit_uRoleName         = Str::lower($request->get('edit_uRoleName'));
            $get_edit_uRole_access      = json_encode($request->get('edit_uRole_access'));
            $get_respo_user_id          = $request->get('respo_user_id');
            $get_respo_user_lname       = $request->get('respo_user_lname');
            $get_respo_user_fname       = $request->get('respo_user_fname');

        // get old data of selected role from user_roles_tbl
            $get_old_uRole_info   = Userroles::where('uRole_id', $get_edit_selected_uRole_id)->first();
            $get_old_uRole_status = $get_old_uRole_info->uRole_status;
            $get_old_uRole_type   = $get_old_uRole_info->uRole_type;
            $get_old_uRole        = $get_old_uRole_info->uRole;
            $get_old_uRole_access = $get_old_uRole_info->uRole_access;

        // update record from user_roles_tbl
            $update_user_role_tbl = DB::table('user_roles_tbl')
                ->where('uRole_id', $get_edit_selected_uRole_id)
                ->update([
                    'uRole_status' => $get_old_uRole_status,
                    'uRole_type'   => $get_old_uRole_type,
                    'uRole'        => $get_edit_uRoleName,
                    'uRole_access' => $get_edit_uRole_access,
                    'updated_at'   => $now_timestamp
                ]);

        // if update was a success
        if($update_user_role_tbl){
            // update the role name on users assigned to this role from users_tbl
            $check_if_any_assigned_users = Users::where('user_role', $get_old_uRole)->count();
            if($check_if_any_assigned_users > 0){
                $update_users_role_name_tbl = DB::table('users')
                ->where('user_role', $get_old_uRole)
                ->update([
                    'user_role'  => $get_edit_uRoleName,
                    'updated_at' => $now_timestamp
                ]);
            }

            // record old uRole data to edited_old_user_roles_tbl
            $record_old_uRole_tbl = new Editedolduserroles;
            $record_old_uRole_tbl->from_uRole_id    = $get_edit_selected_uRole_id;
            $record_old_uRole_tbl->old_uRole_status = $get_old_uRole_status;
            $record_old_uRole_tbl->old_uRole_type   = $get_old_uRole_type;
            $record_old_uRole_tbl->old_uRole        = $get_old_uRole;
            $record_old_uRole_tbl->old_uRole_access = $get_old_uRole_access;
            $record_old_uRole_tbl->respo_user_id    = $get_respo_user_id;
            $record_old_uRole_tbl->edited_at        = $now_timestamp;
            $record_old_uRole_tbl->save();

            // get eOld_uRole_id from edited_old_user_roles_tbl 
            $get_eOld_uRole_id_tbl = Editedolduserroles::where('from_uRole_id', $get_edit_selected_uRole_id)->latest('edited_at')->first();
            $get_eOld_uRole_id     = $get_eOld_uRole_id_tbl->eOld_uRole_id;

            // record edited uRole data to edited_new_user_roles_tbl
            $record_new_uRole_tbl = new Editednewuserroles;
            $record_new_uRole_tbl->from_eOld_uRole_id = $get_eOld_uRole_id;
            $record_new_uRole_tbl->new_uRole_status   = $get_old_uRole_status;
            $record_new_uRole_tbl->new_uRole_type     = $get_old_uRole_type;
            $record_new_uRole_tbl->new_uRole          = $get_edit_uRoleName;
            $record_new_uRole_tbl->new_uRole_access	  = json_decode($get_edit_uRole_access, true);
            $record_new_uRole_tbl->edited_at	      = $now_timestamp;
            $record_new_uRole_tbl->save();

            // record activity
            $rec_activity = new Useractivites;
            $rec_activity->created_at            = $now_timestamp;
            $rec_activity->act_respo_user_id     = $get_respo_user_id;
            $rec_activity->act_respo_users_lname = $get_respo_user_lname;
            $rec_activity->act_respo_users_fname = $get_respo_user_fname;
            $rec_activity->act_type              = 'update role';
            $rec_activity->act_details           = ucwords($get_old_uRole). ' Role Update.';
            $rec_activity->act_affected_id       = $get_eOld_uRole_id;
            $rec_activity->save();

            return back()->withSuccessStatus(ucwords($get_old_uRole). ' Role was updated successfully.');
        }else{
            return back()->withFailedStatus(ucwords($get_old_uRole). ' Role Update Failed! try again later.');
        }
    }
    // deactivate role modal confirmation
    public function deactivate_role_modal(Request $request){
        // get selected uRole_id
            $get_deactivated_uRole_id = $request->get('deactivated_uRole_id');

        // get role details from user_roles_tbl
            $get_role_details     = Userroles::where('uRole_id', $get_deactivated_uRole_id)->first();
            $get_uRole_status     = $get_role_details->uRole_status;
            $get_uRole_type       = $get_role_details->uRole_type;
            $get_uRole            = $get_role_details->uRole;
            $get_uRole_access     = $get_role_details->uRole_access;
            $get_uRole_created_by = $get_role_details->created_by;
            $get_uRole_created_at = $get_role_details->created_at;

        // get info of user who created this role
            $get_created_by_user  = Users::where('id', $get_uRole_created_by)->first();
            $get_created_by_lname = $get_created_by_user->user_lname; 
            $get_created_by_fname = $get_created_by_user->user_fname; 

        // get all assigned users
            $get_assigned_users = Users::where('user_role', $get_uRole)->get();

        $output = '';
        $output .='
            <div class="modal-body border-0 p-0">
                <div class="cust_modal_body_gray">
                    <div class="accordion shadow cust_accordion_div" id="deactivateURoleModalAccordion_Parent'.$get_deactivated_uRole_id.'">
                        <div class="card custom_accordion_card">
                            <div class="card-header p-0" id="deactivateRoleCollapse_heading'.$get_deactivated_uRole_id.'">
                                <h2 class="mb-0">
                                    <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#deactivateURoleCollapse_Div'.$get_deactivated_uRole_id.'" aria-expanded="true" aria-controls="deactivateURoleCollapse_Div'.$get_deactivated_uRole_id.'">
                                        <div>
                                            <span class="accordion_title">'.$get_uRole.'</span>
                                            <span class="accordion_subtitle">'; 
                                                if(count($get_assigned_users) > 0){
                                                    if(count($get_assigned_users) > 1){
                                                        $output .= count($get_assigned_users). ' Assigned Users Found.';
                                                    }else{
                                                        $output .= count($get_assigned_users). ' Assigned User Found.';
                                                    }
                                                }else{
                                                    $output .= 'No Assigned Users.';
                                                }
                                            $output .='
                                            </span>
                                        </div>
                                        <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                    </button>
                                </h2>
                            </div>
                            <div id="deactivateURoleCollapse_Div'.$get_deactivated_uRole_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="deactivateRoleCollapse_heading'.$get_deactivated_uRole_id.'" data-parent="#deactivateURoleModalAccordion_Parent'.$get_deactivated_uRole_id.'">
                                <div class="card-body lightBlue_cardBody mt-2">
                                    <span class="lightBlue_cardBody_blueTitle">Assigned Users:</span>';
                                    if(count($get_assigned_users) > 0){
                                        foreach($get_assigned_users as $index => $assigned_user){
                                            $output .= '<span class="lightBlue_cardBody_list"><span class="lightBlue_cardBody_listCount">'.($index+1).'.</span> ' .$assigned_user->user_fname. ' ' .$assigned_user->user_lname. '</span>';
                                        }
                                    }else{
                                        $output .= '<span class="lightBlue_cardBody_list font-italic">No assigned users found.</span>';
                                    }
                                    $output .= '
                                </div>
                                <div class="card-body lightGreen_cardBody mt-2 mb-2">
                                    <span class="lightGreen_cardBody_greenTitle">Access Controls:</span>';
                                    if(!is_null($get_uRole_access)){
                                        foreach(json_decode(json_encode($get_uRole_access), true) as $index => $uRole_access){
                                            $output .= '<span class="lightGreen_cardBody_list"><span class="lightGreen_cardBody_listCount">'.($index+1).'.</span> '.ucwords($uRole_access).'</span>';
                                        }
                                    }else{
                                        $output .= '<span class="lightGreen_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> No access controls found.</span>';
                                    }
                                    $output .= '    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form action="'.route('user_management.process_deactivate_role').'" class="deactivateRoleConfirmationForm" method="POST">
                <div class="modal-body pb-0">';
                if(count($get_assigned_users) > 0){
                $output .= '
                    <div class="card-body lightRed_cardBody shadow-none">
                        <span class="lightRed_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> Deactivating <span class="font-weight-bold"> ' .ucwords($get_uRole). ' </span> Role will also deactivate the assigned users wherein they will no longer be able to access the system until activation.</span>
                    </div>';
                }
                $output .='
                    <div class="card-body lightBlue_cardBody shadow-none mt-2">
                        <span class="lightBlue_cardBody_blueTitle">Reason for Deactivating ' .ucwords($get_uRole). ' Role:</span>
                        <div class="form-group">
                            <textarea class="form-control" id="deactivate_role_reason" name="deactivate_role_reason" rows="3" placeholder="Type reason for Deactivation (optional)"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="deactivate_selected_role_id" value="'.$get_deactivated_uRole_id.'">
                    <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                    <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                    <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                        <button type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0">Deactivate this Role <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </form>
        ';

        echo $output;
    }
    // process role deactivation
    public function process_deactivate_role(Request $request){
        // now timestamp
            $now_timestamp   = now();
            $deactivated_txt = 'deactivated';

        // get all request
            $get_deactivate_selected_role_id = $request->get('deactivate_selected_role_id');
            $get_respo_user_id               = $request->get('respo_user_id');
            $get_respo_user_lname            = $request->get('respo_user_lname');
            $get_respo_user_fname            = $request->get('respo_user_fname');
            $get_deactivate_role_reason      = $request->get('deactivate_role_reason');

        // get role name from user_roles_tbl
            $get_role_details = Userroles::where('uRole_id', $get_deactivate_selected_role_id)->first();
            if($get_role_details){
                // get details
                $org_uRole_name = $get_role_details->uRole;

                // deactivate the role
                $update_role_status_tbl = DB::table('user_roles_tbl')
                ->where('uRole_id', $get_deactivate_selected_role_id)
                ->update([
                    'uRole_status' => $deactivated_txt,
                    'updated_at'   => $now_timestamp
                ]);

                // if status update was a success
                if($update_role_status_tbl){
                    // update role status from users tbl for assigned users
                    $check_assigned_users = Users::where('user_role', $org_uRole_name)->count();
                    if($check_assigned_users > 0){
                        $update_role_status_tbl = DB::table('users')
                        ->where('user_role', $org_uRole_name)
                        ->update([
                            'user_role_status' => 'deactivated',
                            'updated_at'       => $now_timestamp
                        ]);
                    }
                }

                // record role status' updat to user_role_status_updates_tbl
                $rec_role_stats_update_tbl = new Userrolesupdatestatus;
                $rec_role_stats_update_tbl->from_uRole_id  = $get_deactivate_selected_role_id;
                $rec_role_stats_update_tbl->updated_status = $deactivated_txt;
                $rec_role_stats_update_tbl->reason_update  = $get_deactivate_role_reason;
                $rec_role_stats_update_tbl->updated_at     = $now_timestamp;
                $rec_role_stats_update_tbl->updated_by     = $get_respo_user_id;
                $rec_role_stats_update_tbl->save();

                // get uRoleStatUp_id from user_role_status_updates_tbl fro activity reference
                $get_uRoleStatUp_id_tbl = Userrolesupdatestatus::select('uRoleStatUp_id')->where('from_uRole_id', $get_deactivate_selected_role_id)->latest('updated_at')->first();
                $get_uRoleStatUp_id     = $get_uRoleStatUp_id_tbl->uRoleStatUp_id;

                // record activity
                $rec_activity = new Useractivites;
                $rec_activity->created_at            = $now_timestamp;
                $rec_activity->act_respo_user_id     = $get_respo_user_id;
                $rec_activity->act_respo_users_lname = $get_respo_user_lname;
                $rec_activity->act_respo_users_fname = $get_respo_user_fname;
                $rec_activity->act_type              = 'deactivate role';
                $rec_activity->act_details           = 'Deactivated ' .ucwords($org_uRole_name). ' Role.';
                $rec_activity->act_affected_id       = $get_uRoleStatUp_id;
                $rec_activity->save();

                return back()->withSuccessStatus(ucwords($org_uRole_name). ' Role was Deactivated Successfully.');
            }else{
                return back()->withFailedStatus(ucwords($org_uRole_name). ' Role Deactivation Failed! try again later.');
            }
    }
    // activate role modal confirmation
    public function activate_role_modal(Request $request){
        // get selected uRole_id
            $get_activated_uRole_id = $request->get('activate_uRole_id');

        // get role details from user_roles_tbl
            $get_role_details     = Userroles::where('uRole_id', $get_activated_uRole_id)->first();
            $get_uRole_status     = $get_role_details->uRole_status;
            $get_uRole_type       = $get_role_details->uRole_type;
            $get_uRole            = $get_role_details->uRole;
            $get_uRole_access     = $get_role_details->uRole_access;
            $get_uRole_created_by = $get_role_details->created_by;
            $get_uRole_created_at = $get_role_details->created_at;

        // get info of user who created this role
            $get_created_by_user  = Users::where('id', $get_uRole_created_by)->first();
            $get_created_by_lname = $get_created_by_user->user_lname; 
            $get_created_by_fname = $get_created_by_user->user_fname; 

        // get all assigned users
            $get_assigned_users = Users::where('user_role', $get_uRole)->get();

        $output = '';
        $output .='
        <div class="modal-body border-0 p-0">
            <div class="cust_modal_body_gray">
                <div class="accordion shadow cust_accordion_div" id="activateURoleModalAccordion_Parent'.$get_activated_uRole_id.'">
                    <div class="card custom_accordion_card">
                        <div class="card-header p-0" id="activateRoleCollapse_heading'.$get_activated_uRole_id.'">
                            <h2 class="mb-0">
                                <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#deactivateURoleCollapse_Div'.$get_activated_uRole_id.'" aria-expanded="true" aria-controls="deactivateURoleCollapse_Div'.$get_activated_uRole_id.'">
                                    <div>
                                        <span class="accordion_title_gray">'.$get_uRole.'</span>
                                        <span class="accordion_subtitle_gray">'; 
                                            if(count($get_assigned_users) > 0){
                                                if(count($get_assigned_users) > 1){
                                                    $output .= count($get_assigned_users). ' Assigned Users Found.';
                                                }else{
                                                    $output .= count($get_assigned_users). ' Assigned User Found.';
                                                }
                                            }else{
                                                $output .= 'No Assigned Users.';
                                            }
                                        $output .='
                                        </span>
                                    </div>
                                    <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="deactivateURoleCollapse_Div'.$get_activated_uRole_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="activateRoleCollapse_heading'.$get_activated_uRole_id.'" data-parent="#activateURoleModalAccordion_Parent'.$get_activated_uRole_id.'">
                            <div class="card-body lightBlue_cardBody mt-2">
                                <span class="lightBlue_cardBody_blueTitle grayed_txt">Assigned Users:</span>';
                                if(count($get_assigned_users) > 0){
                                    foreach($get_assigned_users as $index => $assigned_user){
                                        $output .= '<span class="lightBlue_cardBody_list"><span class="lightBlue_cardBody_listCount grayed_txt">'.($index+1).'.</span> ' .$assigned_user->user_fname. ' ' .$assigned_user->user_lname. '</span>';
                                    }
                                }else{
                                    $output .= '<span class="lightBlue_cardBody_list font-italic">No assigned users found.</span>';
                                }
                                $output .= '
                            </div>
                            <div class="card-body lightBlue_cardBody mt-2 mb-2">
                                <span class="lightBlue_cardBody_blueTitle grayed_txt">Access Controls:</span>';
                                if(!is_null($get_uRole_access)){
                                    foreach(json_decode(json_encode($get_uRole_access), true) as $index => $uRole_access){
                                        $output .= '<span class="lightBlue_cardBody_list"><span class="lightBlue_cardBody_listCount grayed_txt">'.($index+1).'.</span> '.ucwords($uRole_access).'</span>';
                                    }
                                }else{
                                    $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> No access controls found.</span>';
                                }
                                $output .= '    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="'.route('user_management.process_activate_role').'" class="activateRoleConfirmationForm" method="POST">
            <div class="modal-body pb-0">';
            if(count($get_assigned_users) > 0){
            $output .= '
                <div class="card-body lightGreen_cardBody shadow-none">
                    <span class="lightGreen_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> Activating <span class="font-weight-bold"> ' .ucwords($get_uRole). ' </span> Role will also activate the assigned users which will give them access to the system again.</span>
                </div>';
            }
            $output .='
                <div class="card-body lightBlue_cardBody shadow-none mt-2">
                    <span class="lightBlue_cardBody_blueTitle">Reason for Activating ' .ucwords($get_uRole). ' Role:</span>
                    <div class="form-group">
                        <textarea class="form-control" id="activate_role_reason" name="activate_role_reason" rows="3" placeholder="Type reason for Activation (optional)"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                <input type="hidden" name="activate_selected_role_id" value="'.$get_activated_uRole_id.'">
                <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                    <button type="submit" class="btn btn-round btn-success btn_show_icon m-0">Activate this Role <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                </div>
            </div>
        </form>
        ';

        echo $output;
    }
    // process role activation
    public function process_activate_role(Request $request){
        // now timestamp
            $now_timestamp = now();
            $activated_txt = 'active';

        // get all request
            $get_activate_selected_role_id = $request->get('activate_selected_role_id');
            $get_respo_user_id             = $request->get('respo_user_id');
            $get_respo_user_lname          = $request->get('respo_user_lname');
            $get_respo_user_fname          = $request->get('respo_user_fname');
            $get_activate_role_reason      = $request->get('activate_role_reason');

        $get_role_details = Userroles::where('uRole_id', $get_activate_selected_role_id)->first();
        if($get_role_details){
            // get details
            $org_uRole_name = $get_role_details->uRole;

            // deactivate the role
            $update_role_status_tbl = DB::table('user_roles_tbl')
            ->where('uRole_id', $get_activate_selected_role_id)
            ->update([
                'uRole_status' => $activated_txt,
                'updated_at'   => $now_timestamp
            ]);

            // if status update was a success
            if($update_role_status_tbl){
                // update role status from users tbl for assigned users
                $check_assigned_users = Users::where('user_role', $org_uRole_name)->count();
                if($check_assigned_users > 0){
                    $update_role_status_tbl = DB::table('users')
                    ->where('user_role', $org_uRole_name)
                    ->update([
                        'user_role_status' => $activated_txt,
                        'updated_at'       => $now_timestamp
                    ]);
                }
            }

            // record role status' updat to user_role_status_updates_tbl
            $rec_role_stats_update_tbl = new Userrolesupdatestatus;
            $rec_role_stats_update_tbl->from_uRole_id  = $get_activate_selected_role_id;
            $rec_role_stats_update_tbl->updated_status = $activated_txt;
            $rec_role_stats_update_tbl->reason_update  = $get_activate_role_reason;
            $rec_role_stats_update_tbl->updated_at     = $now_timestamp;
            $rec_role_stats_update_tbl->updated_by     = $get_respo_user_id;
            $rec_role_stats_update_tbl->save();

            // get uRoleStatUp_id from user_role_status_updates_tbl fro activity reference
            $get_uRoleStatUp_id_tbl = Userrolesupdatestatus::select('uRoleStatUp_id')->where('from_uRole_id', $get_activate_selected_role_id)->latest('updated_at')->first();
            $get_uRoleStatUp_id     = $get_uRoleStatUp_id_tbl->uRoleStatUp_id;

            // record activity
            $rec_activity = new Useractivites;
            $rec_activity->created_at            = $now_timestamp;
            $rec_activity->act_respo_user_id     = $get_respo_user_id;
            $rec_activity->act_respo_users_lname = $get_respo_user_lname;
            $rec_activity->act_respo_users_fname = $get_respo_user_fname;
            $rec_activity->act_type              = 'activate role';
            $rec_activity->act_details           = 'Activated ' .ucwords($org_uRole_name). ' Role.';
            $rec_activity->act_affected_id       = $get_uRoleStatUp_id;
            $rec_activity->save();

            return back()->withSuccessStatus(ucwords($org_uRole_name). ' Role was Activated Successfully.');
        }else{
            return back()->withFailedStatus(ucwords($org_uRole_name). ' Role Activation Failed! try again later.');
        }
    }

    // FUNCTIONS FOR SYSTEM USERS
    // deactivate user account modal confirmation
    public function deactivate_user_account_modal(Request $request){
        // get request
            $get_selected_user_id = $request->get('deactivate_user_id');

        // get user's details from users table
            $get_user_details_tbl = Users::where('id', $get_selected_user_id)->first();
            $get_user_email       = $get_user_details_tbl->email;
            $get_user_role        = $get_user_details_tbl->user_role;
            $get_user_type        = $get_user_details_tbl->user_type;
            $get_user_sdca_id     = $get_user_details_tbl->user_sdca_id;
            $get_user_image       = $get_user_details_tbl->user_image;
            $get_user_lname       = $get_user_details_tbl->user_lname;
            $get_user_fname       = $get_user_details_tbl->user_fname;
            $get_user_gender      = $get_user_details_tbl->user_gender;

        $output = '';
        $output .= '
        <div class="modal-body border-0 p-0">
            <div class="cust_modal_body_gray">
                <div class="accordion shadow cust_accordion_div" id="deactivateUserAccountModalAccordion_Parent'.$get_selected_user_id.'">
                    <div class="card custom_accordion_card">';
                    if($get_user_type === 'student'){
                        // get student information from user_students_tbl
                        $get_stud_info_tbl = Userstudents::where('uStud_num', $get_user_sdca_id)->first();
                        $get_uStud_school  = $get_stud_info_tbl->uStud_school;
                        $get_uStud_program = $get_stud_info_tbl->uStud_program;
                        $get_uStud_yearlvl = $get_stud_info_tbl->uStud_yearlvl;
                        $get_uStud_section = $get_stud_info_tbl->uStud_section;
                        $get_uStud_phnum  = $get_stud_info_tbl->uStud_phnum;
                        // display user's info
                        $output .= '
                        <div class="card-header p-0" id="deactivateStudUserAccountCollapse_heading'.$get_selected_user_id.'">
                            <h2 class="mb-0">
                                <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#deactivateStudUserAccountCollapse_Div'.$get_selected_user_id.'" aria-expanded="true" aria-controls="deactivateStudUserAccountCollapse_Div'.$get_selected_user_id.'">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="display_user_image_div text-center">
                                            <img class="display_user_image studImg_border shadow-sm" src="'.asset('storage/svms/user_images/'.$get_user_image).'" alt="student user profile">
                                        </div>
                                        <div class="information_div">
                                            <span class="li_info_title">'.$get_user_fname. ' ' .$get_user_lname.'</span>
                                            <span class="li_info_subtitle">'.ucwords($get_user_role).'</span>
                                        </div>
                                    </div>
                                    <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="deactivateStudUserAccountCollapse_Div'.$get_selected_user_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="deactivateStudUserAccountCollapse_heading'.$get_selected_user_id.'" data-parent="#deactivateUserAccountModalAccordion_Parent'.$get_selected_user_id.'">
                            <div class="card-body lightGreen_cardBody mt-2">
                                <span class="lightGreen_cardBody_greenTitle m-0">Student Number:</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_user_sdca_id.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">School</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_uStud_school.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">Year Level</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_uStud_yearlvl.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">Program/Year/Section</span>
                                <span class="lightGreen_cardBody_list mb-3">'.$get_uStud_program. ' ' .$get_uStud_section.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">Email Address</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_user_email.'</span>';
                                if(!is_null($get_uStud_phnum)){
                                    $output .= '
                                    <span class="lightGreen_cardBody_greenTitle m-0">Phone Number</span>
                                    <span class="lightGreen_cardBody_list">'.$get_uStud_phnum.'</span>
                                    ';
                                }
                                $output .= '
                            </div>
                        </div>
                        ';
                    }else{
                        // get employee information from user_employees_tbl
                        $get_emp_info_tbl = Useremployees::where('uEmp_id', $get_user_sdca_id)->first();
                        $get_uEmp_job_desc = $get_emp_info_tbl->uEmp_job_desc;
                        $get_uEmp_dept = $get_emp_info_tbl->uEmp_dept;
                        $get_uEmp_phnum = $get_emp_info_tbl->uEmp_phnum;
                        // display user's info
                        $output .= '
                        <div class="card-header p-0" id="deactivateEmpUserAccountCollapse_heading'.$get_selected_user_id.'">
                            <h2 class="mb-0">
                                <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#deactivateEmpUserAccountCollapse_Div'.$get_selected_user_id.'" aria-expanded="true" aria-controls="deactivateEmpUserAccountCollapse_Div'.$get_selected_user_id.'">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="display_user_image_div text-center">
                                            <img class="display_user_image empImg_border shadow-sm" src="'.asset('storage/svms/user_images/'.$get_user_image).'" alt="student user profile">
                                        </div>
                                        <div class="information_div">
                                            <span class="li_info_title">'.$get_user_fname. ' ' .$get_user_lname.'</span>
                                            <span class="li_info_subtitle">'.ucwords($get_user_role).'</span>
                                        </div>
                                    </div>
                                    <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="deactivateEmpUserAccountCollapse_Div'.$get_selected_user_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="deactivateEmpUserAccountCollapse_heading'.$get_selected_user_id.'" data-parent="#deactivateUserAccountModalAccordion_Parent'.$get_selected_user_id.'">
                            <div class="card-body lightBlue_cardBody mt-2">
                                <span class="lightBlue_cardBody_blueTitle m-0">Employee ID:</span>
                                <span class="lightBlue_cardBody_list mb-1">'.$get_user_sdca_id.'</span>
                                <span class="lightBlue_cardBody_blueTitle m-0">Job Title</span>
                                <span class="lightBlue_cardBody_list mb-1">'.$get_uEmp_job_desc.'</span>
                                <span class="lightBlue_cardBody_blueTitle m-0">Department</span>
                                <span class="lightBlue_cardBody_list mb-3">'.$get_uEmp_dept.'</span>
                                <span class="lightBlue_cardBody_blueTitle m-0">Email Address</span>
                                <span class="lightBlue_cardBody_list mb-1">'.$get_user_email.'</span>';
                                if(!is_null($get_uEmp_phnum)){
                                    $output .= '
                                    <span class="lightBlue_cardBody_blueTitle m-0">Phone Number</span>
                                    <span class="lightBlue_cardBody_list">'.$get_uEmp_phnum.'</span>
                                    ';
                                }
                                $output .= '
                            </div>
                        </div>
                        ';
                    }
                    $output .= '
                    </div>
                </div>
            </div>
            <form action="'.route('user_management.process_deactivate_user_account').'" class="deacivateUserAccountConfirmationForm" method="POST">
                <div class="modal-body pb-0">
                    <div class="card-body lightRed_cardBody shadow-none">
                        <span class="lightRed_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> <span class="font-weight-bold"> ' .$get_user_fname. ' ' .$get_user_lname. ' </span> will no longer be able to access the system effective immediately after account deactivation.</span>
                    </div>
                    <div class="card-body lightBlue_cardBody shadow-none mt-2">
                        <span class="lightBlue_cardBody_blueTitle">Reason for Deactivating ' .$get_user_lname.'s Account:</span>
                        <div class="form-group">
                            <textarea class="form-control" id="deactivate_user_account_reason" name="deactivate_user_account_reason" rows="3" placeholder="Type reason for Account Deactivation (optional)"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="deactivate_selected_user_id" value="'.$get_selected_user_id.'">
                    <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                    <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                    <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                        <button type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0">Deactivate this Account <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </form>
        </div>
        ';

        return $output;
    }
    // process user account deactivation
    public function process_deactivate_user_account(Request $request){
        // now timestamp
            $now_timestamp   = now();
            $deactivated_txt = 'deactivated';
            $sq              = "'";

        // get all request
            $get_deactivate_selected_user_id    = $request->get('deactivate_selected_user_id');
            $get_respo_user_id                  = $request->get('respo_user_id');
            $get_respo_user_lname               = $request->get('respo_user_lname');
            $get_respo_user_fname               = $request->get('respo_user_fname');
            $get_deactivate_user_account_reason = $request->get('deactivate_user_account_reason');

        // get user's info
            $get_user_details_tbl = Users::select('user_role', 'user_status', 'user_lname', 'user_fname')->where('id', $get_deactivate_selected_user_id)->first();
            $get_user_role        = $get_user_details_tbl->user_role;
            $get_user_status      = $get_user_details_tbl->user_status;
            $get_user_lname       = $get_user_details_tbl->user_lname;
            $get_user_fname       = $get_user_details_tbl->user_fname;

        // update user's status
            $update_user_status_tbl = DB::table('users')
                ->where('id', $get_deactivate_selected_user_id)
                ->update([
                    'user_status' => $deactivated_txt,
                    'updated_at'  => $now_timestamp
                ]);
        if($update_user_status_tbl){
            // record status update to user_status_updates_tbl
            $rec_user_stats_update_tbl = new Userupdatesstatus;
            $rec_user_stats_update_tbl->from_user_id   = $get_deactivate_selected_user_id;
            $rec_user_stats_update_tbl->updated_status = $deactivated_txt;
            $rec_user_stats_update_tbl->reason_update  = $get_deactivate_user_account_reason;
            $rec_user_stats_update_tbl->updated_at     = $now_timestamp;
            $rec_user_stats_update_tbl->updated_by     = $get_respo_user_id;
            $rec_user_stats_update_tbl->save();

            // get uStatUpdate_id from user_status_updates_tbl as activity reference
            $get_uStatUpdate_id_tbl = Userupdatesstatus::select('uStatUpdate_id')->where('from_user_id', $get_deactivate_selected_user_id)->latest('updated_at')->first();
            $get_uStatUpdate_id     = $get_uStatUpdate_id_tbl->uStatUpdate_id;

            // record activity
            $rec_activity = new Useractivites;
            $rec_activity->created_at            = $now_timestamp;
            $rec_activity->act_respo_user_id     = $get_respo_user_id;
            $rec_activity->act_respo_users_lname = $get_respo_user_lname;
            $rec_activity->act_respo_users_fname = $get_respo_user_fname;
            $rec_activity->act_type              = 'deactivate user';
            $rec_activity->act_details           = 'Deactivated ' .$get_user_fname. ' ' .$get_user_lname.''.$sq.'s Account.';
            $rec_activity->act_affected_id       = $get_uStatUpdate_id;
            $rec_activity->save();

            return back()->withSuccessStatus($get_user_fname. ' ' .$get_user_lname.''.$sq.'s Account was Deactivated Successfully.');
        }else{
            return back()->withFailedStatus($get_user_fname. ' ' .$get_user_lname.''.$sq.'s Account Deactivation Failed! try again later.');
        }
    }
    // activate user account modal confirmation
    public function activate_user_account_modal(Request $request){
        // get request
            $get_selected_user_id = $request->get('activate_user_id');

        // get user's details from users table
            $get_user_details_tbl = Users::where('id', $get_selected_user_id)->first();
            $get_user_email       = $get_user_details_tbl->email;
            $get_user_role        = $get_user_details_tbl->user_role;
            $get_user_status      = $get_user_details_tbl->user_status;
            $get_user_role_status = $get_user_details_tbl->user_role_status;
            $get_user_type        = $get_user_details_tbl->user_type;
            $get_user_sdca_id     = $get_user_details_tbl->user_sdca_id;
            $get_user_image       = $get_user_details_tbl->user_image;
            $get_user_lname       = $get_user_details_tbl->user_lname;
            $get_user_fname       = $get_user_details_tbl->user_fname;
            $get_user_gender      = $get_user_details_tbl->user_gender;

        // his/her txt based on user's gender
            if($get_user_gender === 'male'){
                $gender_txt = 'his';
            }elseif($get_user_gender === 'female'){
                $gender_txt = 'her';
            }else{
                $gender_txt = 'his/her';
            }
        
        // custom values
            $sq = "'";

        $output = '';
        $output .= '
        <div class="modal-body border-0 p-0">
            <div class="cust_modal_body_gray">
                <div class="accordion shadow cust_accordion_div" id="activateUserAccountModalAccordion_Parent'.$get_selected_user_id.'">
                    <div class="card custom_accordion_card">';
                    if($get_user_type === 'student'){
                        // get student information from user_students_tbl
                        $get_stud_info_tbl = Userstudents::where('uStud_num', $get_user_sdca_id)->first();
                        $get_uStud_school  = $get_stud_info_tbl->uStud_school;
                        $get_uStud_program = $get_stud_info_tbl->uStud_program;
                        $get_uStud_yearlvl = $get_stud_info_tbl->uStud_yearlvl;
                        $get_uStud_section = $get_stud_info_tbl->uStud_section;
                        $get_uStud_phnum  = $get_stud_info_tbl->uStud_phnum;
                        // display user's info
                        $output .= '
                        <div class="card-header p-0" id="activateStudUserAccountCollapse_heading'.$get_selected_user_id.'">
                            <h2 class="mb-0">
                                <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#activateStudUserAccountCollapse_Div'.$get_selected_user_id.'" aria-expanded="true" aria-controls="activateStudUserAccountCollapse_Div'.$get_selected_user_id.'">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="display_user_image_div text-center">
                                            <img class="display_user_image studImg_border shadow-sm" src="'.asset('storage/svms/user_images/'.$get_user_image).'" alt="student user profile">
                                        </div>
                                        <div class="information_div">
                                            <span class="li_info_title">'.$get_user_fname. ' ' .$get_user_lname.'</span>
                                            <span class="li_info_subtitle">'.ucwords($get_user_role).'</span>
                                        </div>
                                    </div>
                                    <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="activateStudUserAccountCollapse_Div'.$get_selected_user_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="activateStudUserAccountCollapse_heading'.$get_selected_user_id.'" data-parent="#activateUserAccountModalAccordion_Parent'.$get_selected_user_id.'">
                            <div class="card-body lightGreen_cardBody mt-2">
                                <span class="lightGreen_cardBody_greenTitle m-0">Student Number:</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_user_sdca_id.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">School</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_uStud_school.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">Year Level</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_uStud_yearlvl.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">Program/Year/Section</span>
                                <span class="lightGreen_cardBody_list mb-3">'.$get_uStud_program. ' ' .$get_uStud_section.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">Email Address</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_user_email.'</span>';
                                if(!is_null($get_uStud_phnum)){
                                    $output .= '
                                    <span class="lightGreen_cardBody_greenTitle m-0">Phone Number</span>
                                    <span class="lightGreen_cardBody_list">'.$get_uStud_phnum.'</span>
                                    ';
                                }
                                $output .= '
                            </div>
                        </div>
                        ';
                    }else{
                        // get employee information from user_employees_tbl
                        $get_emp_info_tbl = Useremployees::where('uEmp_id', $get_user_sdca_id)->first();
                        $get_uEmp_job_desc = $get_emp_info_tbl->uEmp_job_desc;
                        $get_uEmp_dept = $get_emp_info_tbl->uEmp_dept;
                        $get_uEmp_phnum = $get_emp_info_tbl->uEmp_phnum;
                        // display user's info
                        $output .= '
                        <div class="card-header p-0" id="activateEmpUserAccountCollapse_heading'.$get_selected_user_id.'">
                            <h2 class="mb-0">
                                <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#activateEmpUserAccountCollapse_Div'.$get_selected_user_id.'" aria-expanded="true" aria-controls="activateEmpUserAccountCollapse_Div'.$get_selected_user_id.'">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="display_user_image_div text-center">
                                            <img class="display_user_image empImg_border shadow-sm" src="'.asset('storage/svms/user_images/'.$get_user_image).'" alt="student user profile">
                                        </div>
                                        <div class="information_div">
                                            <span class="li_info_title">'.$get_user_fname. ' ' .$get_user_lname.'</span>
                                            <span class="li_info_subtitle">'.ucwords($get_user_role).'</span>
                                        </div>
                                    </div>
                                    <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="activateEmpUserAccountCollapse_Div'.$get_selected_user_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="activateEmpUserAccountCollapse_heading'.$get_selected_user_id.'" data-parent="#activateUserAccountModalAccordion_Parent'.$get_selected_user_id.'">
                            <div class="card-body lightBlue_cardBody mt-2">
                                <span class="lightBlue_cardBody_blueTitle m-0">Employee ID:</span>
                                <span class="lightBlue_cardBody_list mb-1">'.$get_user_sdca_id.'</span>
                                <span class="lightBlue_cardBody_blueTitle m-0">Job Title</span>
                                <span class="lightBlue_cardBody_list mb-1">'.$get_uEmp_job_desc.'</span>
                                <span class="lightBlue_cardBody_blueTitle m-0">Department</span>
                                <span class="lightBlue_cardBody_list mb-3">'.$get_uEmp_dept.'</span>
                                <span class="lightBlue_cardBody_blueTitle m-0">Email Address</span>
                                <span class="lightBlue_cardBody_list mb-1">'.$get_user_email.'</span>';
                                if(!is_null($get_uEmp_phnum)){
                                    $output .= '
                                    <span class="lightBlue_cardBody_blueTitle m-0">Phone Number</span>
                                    <span class="lightBlue_cardBody_list">'.$get_uEmp_phnum.'</span>
                                    ';
                                }
                                $output .= '
                            </div>
                        </div>
                        ';
                    }
                    $output .= '
                    </div>
                </div>
            </div>
            <div class="modal-body pb-0">';
                // get reason of account deactivation from user_status_updates_tbl
                $get_deactivation_details_tbl = Userupdatesstatus::where('from_user_id', $get_selected_user_id)->latest('updated_at')->first();
                if($get_deactivation_details_tbl){
                    $get_deact_det_reason_update  = $get_deactivation_details_tbl->reason_update;
                    $get_deact_det_updated_at     = $get_deactivation_details_tbl->updated_at;
                    $get_deact_det_updated_by     = $get_deactivation_details_tbl->updated_by;

                    // get details of responsible user for deactivating this account
                    $get_respo_deact_this_account_tbl = Users::select('user_role', 'user_lname', 'user_fname')->where('id', $get_deact_det_updated_by)->first();
                    
                    if($get_respo_deact_this_account_tbl){
                        $get_respo_deact_user_role  = $get_respo_deact_this_account_tbl->user_role;
                        $get_respo_deact_user_lname = $get_respo_deact_this_account_tbl->user_lname;
                        $get_respo_deact_user_fname = $get_respo_deact_this_account_tbl->user_fname;
                        $output .= ' 
                        <div class="card-body lightBlue_cardBody shadow-none">
                            <span class="lightBlue_cardBody_blueTitle mb-0">Deactivated by:</span>
                            <span class="lightBlue_cardBody_notice mb-2">
                            '.ucwords($get_respo_deact_user_role).': ' .$get_respo_deact_user_fname. ' ' .$get_respo_deact_user_lname. ' ';
                            if(auth()->user()->id === $get_deact_det_updated_by){
                                $output .= ' <span class="font-italic font-weight-bold"> ~ You</span>';
                            }
                            $output .= '
                            </span>';
                            if($get_deactivation_details_tbl){
                                $output .= '
                                <span class="lightBlue_cardBody_blueTitle mb-0">Deactivated at:</span>
                                <span class="lightBlue_cardBody_notice mb-2">'.date('F d, Y', strtotime($get_deact_det_updated_at)). ' - ' .date('D', strtotime($get_deact_det_updated_at)). ' at ' .date('g:i A', strtotime($get_deact_det_updated_at)).'</span>
                                ';
                                if(!is_null($get_deact_det_reason_update)){
                                    $output .= '
                                    <span class="lightBlue_cardBody_blueTitle mb-0">Reason of Deactivation:</span>
                                    <span class="lightBlue_cardBody_notice">'.$get_deact_det_reason_update. '</span>
                                    ';
                                }
                            }
                            $output .= '
                        </div>
                        ';
                    }
                }
                if($get_user_status === 'deactivated' AND $get_user_role_status === 'deactivated'){
                    $output .= '
                    <div class="card-body lightBlue_cardBody shadow-none mt-2">
                        <span class="lightBlue_cardBody_blueTitle">Activate ' .ucwords($get_user_role).' Role First:</span>
                        <span class="lightBlue_cardBody_notice"><i class="fa fa-unlock-alt" aria-hidden="true"></i> You must activate <span class="font-weight-bold"> ' .ucwords($get_user_role). ' </span> Role first where ' .$get_user_fname. ' ' .$get_user_lname. ' is assigned, then you can activate ' .$gender_txt. ' account.</span>
                    </div>
                    <div class="btn-group d-flex justify-content-end my-3" role="group" aria-label="Ok Confirmation">
                        <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal">Ok <i class="fa fa-thumbs-up btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                    ';
                }else{
                    if($get_user_role_status === 'deactivated'){
                        $output .= '
                        <div class="card-body lightBlue_cardBody shadow-none mt-2">
                            <span class="lightBlue_cardBody_blueTitle">Activate ' .ucwords($get_user_role).' Role First:</span>
                            <span class="lightBlue_cardBody_notice"><i class="fa fa-unlock-alt" aria-hidden="true"></i> You must activate <span class="font-weight-bold"> ' .ucwords($get_user_role). ' </span> Role where ' .$get_user_fname. ' ' .$get_user_lname. ' is assigned, then ' .$gender_txt. ' account will automatically be activated and will regain access to the system effective immediately after Role Activation.</span>
                        </div>
                        <div class="btn-group d-flex justify-content-end my-3" role="group" aria-label="Ok Confirmation">
                            <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal">Ok <i class="fa fa-thumbs-up btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                        ';
                    }else{
                        $output .= '
                        <form action="'.route('user_management.process_activate_user_account').'" class="activateUserAccountConfirmationForm" method="POST">
                            <div class="card-body lightGreen_cardBody shadow-none mt-2">
                                <span class="lightGreen_cardBody_notice"><i class="fa fa-unlock-alt" aria-hidden="true"></i> <span class="font-weight-bold"> ' .$get_user_fname. ' ' .$get_user_lname. ' </span> will be able to access the system again effective immediately after account activation.</span>
                            </div>
                            <div class="card-body lightBlue_cardBody shadow-none mt-2">
                                <span class="lightBlue_cardBody_blueTitle">Reason for Activating ' .$get_user_lname.''.$sq.'s Account:</span>
                                <div class="form-group">
                                    <textarea class="form-control" id="activate_user_account_reason" name="activate_user_account_reason" rows="3" placeholder="Type reason for Account Activation (optional)"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input type="hidden" name="activate_selected_user_id" value="'.$get_selected_user_id.'">
                            <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                            <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                            <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                            <div class="btn-group d-flex justify-content-end my-3" role="group" aria-label="Activate User Confirmation">
                                <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                                <button type="submit" class="btn btn-round btn-success btn_show_icon m-0">Activate this Account <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                            </div>
                        </form>
                        ';
                    }
                }
                $output .= '
            </div>
        </div>
        ';
        return $output;
    }
    // process user account activation
    public function process_activate_user_account(Request $request){
        // now timestamp
            $now_timestamp = now();
            $active_txt    = 'active';
            $sq            = "'";

        // get all request
            $get_activate_selected_user_id    = $request->get('activate_selected_user_id');
            $get_respo_user_id                = $request->get('respo_user_id');
            $get_respo_user_lname             = $request->get('respo_user_lname');
            $get_respo_user_fname             = $request->get('respo_user_fname');
            $get_activate_user_account_reason = $request->get('activate_user_account_reason');

        // get user's info
            $get_user_details_tbl = Users::select('user_role', 'user_status', 'user_lname', 'user_fname')->where('id', $get_activate_selected_user_id)->first();
            $get_user_role        = $get_user_details_tbl->user_role;
            $get_user_status      = $get_user_details_tbl->user_status;
            $get_user_lname       = $get_user_details_tbl->user_lname;
            $get_user_fname       = $get_user_details_tbl->user_fname;

        // update user's status
            $update_user_status_tbl = DB::table('users')
                ->where('id', $get_activate_selected_user_id)
                ->update([
                    'user_status' => $active_txt,
                    'updated_at'  => $now_timestamp
                ]);
        if($update_user_status_tbl){
            // record status update to user_status_updates_tbl
            $rec_user_stats_update_tbl = new Userupdatesstatus;
            $rec_user_stats_update_tbl->from_user_id   = $get_activate_selected_user_id;
            $rec_user_stats_update_tbl->updated_status = $active_txt;
            $rec_user_stats_update_tbl->reason_update  = $get_activate_user_account_reason;
            $rec_user_stats_update_tbl->updated_at     = $now_timestamp;
            $rec_user_stats_update_tbl->updated_by     = $get_respo_user_id;
            $rec_user_stats_update_tbl->save();

            // get uStatUpdate_id from user_status_updates_tbl as activity reference
            $get_uStatUpdate_id_tbl = Userupdatesstatus::select('uStatUpdate_id')->where('from_user_id', $get_activate_selected_user_id)->latest('updated_at')->first();
            $get_uStatUpdate_id     = $get_uStatUpdate_id_tbl->uStatUpdate_id;

            // record activity
            $rec_activity = new Useractivites;
            $rec_activity->created_at            = $now_timestamp;
            $rec_activity->act_respo_user_id     = $get_respo_user_id;
            $rec_activity->act_respo_users_lname = $get_respo_user_lname;
            $rec_activity->act_respo_users_fname = $get_respo_user_fname;
            $rec_activity->act_type              = 'activate user';
            $rec_activity->act_details           = 'Activated ' .$get_user_fname. ' ' .$get_user_lname.''.$sq.'s Account.';
            $rec_activity->act_affected_id       = $get_uStatUpdate_id;
            $rec_activity->save();

            return back()->withSuccessStatus($get_user_fname. ' ' .$get_user_lname.''.$sq.'s Account was Activated Successfully.');
        }else{
            return back()->withFailedStatus($get_user_fname. ' ' .$get_user_lname.''.$sq.'s Account Activation Failed! try again later.');
        }
    }
}
