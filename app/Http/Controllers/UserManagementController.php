<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userroles;
use App\Models\Editedolduserroles;
use App\Models\Editednewuserroles;
use App\Models\Users;
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
            $create_emp_id      = $request->get('create_emp_id');
            $create_emp_lname   = $request->get('create_emp_lname');
            $create_emp_fname   = $request->get('create_emp_fname');
            $create_emp_gender  = $request->get('create_emp_gender');
            $create_emp_jobdesc = $request->get('create_emp_jobdesc');
            $create_emp_dept    = $request->get('create_emp_dept');
            $create_emp_phnum   = $request->get('create_emp_phnum');
            $create_emp_email   = $request->get('create_emp_email');

        // custom values
            $now_timestamp        = now();
            $pending_txt          = 'pending';
            $employee_txt         = 'employee';
            $employee_image       = 'employee_user_image.jpg';
            $sq                   = "'";
            $format_now_timestamp = $now_timestamp->format('dmYHis');
            $get_current_year     = $now_timestamp->format('Y');
            $lower_emp_gender     = Str::lower($create_emp_gender);
        
        // user image handler
            if($request->hasFile('create_emp_user_image')){
                $get_filenameWithExt = $request->file('create_emp_user_image')->getClientOriginalName();
                $get_justFile        = pathinfo($get_filenameWithExt, PATHINFO_FILENAME);
                $get_justExt         = $request->file('create_emp_user_image')->getClientOriginalExtension();
                $fileNameToStore     = $get_justFile.'_'.$format_now_timestamp.'.'.$get_justExt;
                // $uploadImageToPath   = $request->file('create_emp_user_image')->storeAs('public/storage/svms/user_images',$fileNameToStore);
            }else{
                $fileNameToStore = $employee_image;
            }

        // generate unique password
            $create_emp_password = Str::lower($create_emp_lname).'@'.$get_current_year;

        echo 'REGISTER NEW EMPLOYEE USER <br />';
        echo 'Employee ID: ' .$create_emp_id. ' <br />';
        echo 'image: ' .$fileNameToStore. ' <br />';
        echo 'Last Name: ' .$create_emp_lname. ' <br />';
        echo 'First Name: ' .$create_emp_fname. ' <br />';
        echo 'Gender: ' .$lower_emp_gender. ' <br />';
        echo 'Job Description: ' .$create_emp_jobdesc. ' <br />';
        echo 'Department: ' .$create_emp_dept. ' <br />';
        echo 'Phone Number: ' .$create_emp_phnum. ' <br />';
        echo 'email: ' .$create_emp_email. ' <br />';
        echo 'password: ' .$create_emp_password. ' <br />';
    }




    // SYSTEM ROLES
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
                $update_users_role_name_tbl = DB::table('users')
                    ->where('user_role', $get_old_uRole)
                    ->update([
                        'user_role'  => $get_edit_uRoleName,
                        'updated_at' => $now_timestamp
                    ]);

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

        

        // echo 'edit moto ' .$get_edit_uRoleName. '<br/>';
        // echo 'access controls ' .$get_edit_uRole_access. '<br/>';
        // echo '<br/>';
        // echo 'respo user id ' .$get_respo_user_id. '<br/>';
        // echo 'respo first name ' .$get_respo_user_lname. '<br/>';
        // echo 'respo last name ' .$get_respo_user_fname. '<br/>';

        // echo '<br/>';echo '<br/>';echo '<br/>';echo '<br/>';echo '<br/>';

        // echo 'old u role ' .$get_old_uRole. '<br/>';
        // echo 'old access controls ' .json_encode($get_old_uRole_access). '<br/>';
        // echo 'old role status ' .$get_old_uRole_status. '<br/>';
        // echo 'old role type' .$get_old_uRole_type. '<br/>';
    }
}
