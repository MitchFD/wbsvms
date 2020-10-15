<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userroles;
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
        $active_users = Users::where('user_status', 'active')->where('user_role_status', 'active')->get();
        $deactivated_users = Users::where('user_status', 'deactivated')->orWhere('user_role_status', 'deactivated')->get();
        $pending_users = Users::where('user_role', 'pending')->where('user_status', 'pending')->where('user_role_status', 'pending')->get();
        $registered_users = Users::where('user_status', '!=', 'deleted')->get();

        // user roles
        $active_roles = Userroles::where('uRole_status', 'active')->get();
        $deactivated_roles = Userroles::where('uRole_status', 'deactivated')->get();
        $deleted_roles = Userroles::where('uRole_status', 'deleted')->get();
        return view('user_management.index')->with(compact('active_users', 'deactivated_users', 'pending_users', 'registered_users', 'active_roles', 'deactivated_roles', 'deleted_roles'));
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
}
