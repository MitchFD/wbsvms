<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Useractivites;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Useremployees;
use App\Models\Userstudents;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'agree_terms_and_conditions' => ['required'],
        ]);
    }

    // email duplicate check
    public function email_availability_check(Request $request){
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

    // register employee type user
    public function employee_process_registration(Request $request){
        $this->validate($request, [
            'email' => 'email',
            'password' => 'required|confirmed|min:6',
        ]);

        // get employee info
            $reg_empId       = $request->get('reg_empId');
            $reg_empLname    = $request->get('reg_empLname');
            $reg_empFname    = $request->get('reg_empFname');
            $reg_empJobDesc  = $request->get('reg_empJobDesc');
            $reg_empDept     = $request->get('reg_empDept');
            $reg_empPhnum    = $request->get('reg_empPhnum');
            $reg_empEmail    = $request->get('email');
            $reg_empPassword = $request->get('password');

        // custom values
            $now_timestamp  = now();
            $pending_txt    = 'pending';
            $employee_txt   = 'employee';
            $employee_image = 'employee_user_image.jpg';
            $sq             = "'";

        // add new user to users and user_employees_tbl
            $reg_emp_user = new Users;
            $reg_emp_user->email             = $reg_empEmail;
            $reg_emp_user->email_verified_at = $now_timestamp;
            $reg_emp_user->password          = Hash::make($reg_empPassword);
            $reg_emp_user->user_role         = $pending_txt;
            $reg_emp_user->user_status       = $pending_txt;
            $reg_emp_user->user_role_status  = $pending_txt;
            $reg_emp_user->user_type         = $employee_txt;
            $reg_emp_user->user_sdca_id      = $reg_empId;
            $reg_emp_user->user_image        = $employee_image;
            $reg_emp_user->user_lname        = $reg_empLname;
            $reg_emp_user->user_fname        = $reg_empFname;
            $reg_emp_user->registered_by     = $reg_empId;
            $reg_emp_user->created_at        = $now_timestamp;
            $reg_emp_user->save();

            $reg_emp_info = new Useremployees;
            $reg_emp_info->uEmp_id       = $reg_empId;
            $reg_emp_info->uEmp_job_desc = $reg_empJobDesc;
            $reg_emp_info->uEmp_dept     = $reg_empDept;
            $reg_emp_info->uEmp_phnum    = $reg_empPhnum;
            $reg_emp_info->created_at    = $now_timestamp;
            $reg_emp_info->save();

        // if registration was success
            if($reg_emp_user AND $reg_emp_info){
                // get new registerd user's id
                $get_new_emp_user_id = Users::select('id')->where('user_sdca_id', $reg_empId)->latest('created_at')->first();
                $new_reg_user_id     = $get_new_emp_user_id->id;
                
                if($new_reg_user_id){
                    // record activity
                    $record_act = new Useractivites;
                    $record_act->created_at            = $now_timestamp;
                    $record_act->act_respo_user_id     = $new_reg_user_id;
                    $record_act->act_respo_users_lname = $reg_empLname;
                    $record_act->act_respo_users_fname = $reg_empFname;
                    $record_act->act_type              = 'register';
                    $record_act->act_details           = $reg_empFname. ' ' .$reg_empLname.$sq.'s Account Registration.';
                    $record_act->act_affected_id       = $new_reg_user_id;
                    $record_act->save();
                }else{
                    echo 'recording registration activity failed';
                }
            }else{
                return back()->withAccountRegistrationFailedStatus('Account Registration Failed! try again later.');
            }
        // echo 'register new employee user: <br/>';
        // echo 'Employee ID: ' .$reg_empId. '<br/>';
        // echo 'Employee name: ' .$reg_empFname. ' ' .$reg_empLname. '<br/>';
        // echo 'Job Description: ' .$reg_empJobDesc. '<br/>';
        // echo 'Job Department: ' .$reg_empDept. '<br/>';
        // echo 'Phone Number: ' .$reg_empPhnum. '<br/>';
        // echo 'Email: ' .$reg_empEmail. '<br/>';
        // echo 'Password: ' .$reg_empPassword. '<br/>';
    }

    // register student type user
    public function student_process_registration(Request $request){
        $this->validate($request, [
            'student_email' => 'email',
            'student_password' => 'required|confirmed|min:6',
        ]);
        // get employee info
            $reg_studNum      = $request->get('reg_studNum');
            $reg_studLname    = $request->get('reg_studLname');
            $reg_studFname    = $request->get('reg_studFname');
            $reg_studSchool   = $request->get('reg_studSchool');
            $reg_studProgram  = $request->get('reg_studProgram');
            $reg_studYearlvl  = $request->get('reg_studYearlvl');
            $reg_studSection  = $request->get('reg_studSection');
            $reg_studPhnum    = $request->get('reg_studPhnum');
            $reg_studEmail    = $request->get('student_email');
            $reg_studPassword = $request->get('student_password');

        // custom values
            $now_timestamp = now();
            $pending_txt   = 'pending';
            $student_txt   = 'student';
            $student_image = 'student_user_image.jpg';
            $sq            = "'";
        
        // add new user to users and user_employees_tbl
            $reg_stud_user = new Users;
            $reg_stud_user->email             = $reg_studEmail;
            $reg_stud_user->email_verified_at = $now_timestamp;
            $reg_stud_user->password          = Hash::make($reg_studPassword);
            $reg_stud_user->user_role         = $pending_txt;
            $reg_stud_user->user_status       = $pending_txt;
            $reg_stud_user->user_role_status  = $pending_txt;
            $reg_stud_user->user_type         = $student_txt;
            $reg_stud_user->user_sdca_id      = $reg_studNum;
            $reg_stud_user->user_image        = $student_image;
            $reg_stud_user->user_lname        = $reg_studLname;
            $reg_stud_user->user_fname        = $reg_studFname;
            $reg_stud_user->registered_by     = $reg_studNum;
            $reg_stud_user->created_at        = $now_timestamp;
            $reg_stud_user->save();

            $reg_stud_info = new Userstudents;
            $reg_stud_info->uStud_num     = $reg_studNum;
            $reg_stud_info->uStud_school  = $reg_studSchool;
            $reg_stud_info->uStud_program = $reg_studProgram;
            $reg_stud_info->uStud_yearlvl = $reg_studYearlvl;
            $reg_stud_info->uStud_section = $reg_studSection;
            $reg_stud_info->uStud_phnum   = $reg_studPhnum;
            $reg_stud_info->created_at    = $now_timestamp;
            $reg_stud_info->save();
        
        // if registration was success
            if($reg_stud_user AND $reg_stud_info){
                // get new registerd user's id
                $get_new_stud_user_id = Users::select('id')->where('user_sdca_id', $reg_studNum)->latest('created_at')->first();
                $new_reg_user_id     = $get_new_stud_user_id->id;

                // record activity
                $record_act = new Useractivites;
                $record_act->created_at            = $now_timestamp;
                $record_act->act_respo_user_id     = $new_reg_user_id;
                $record_act->act_respo_users_lname = $reg_studLname;
                $record_act->act_respo_users_fname = $reg_studFname;
                $record_act->act_type              = 'register';
                $record_act->act_details           = $reg_studFname. ' ' .$reg_studLname.$sq.'s Account Registration.';
                $record_act->act_affected_id       = $new_reg_user_id;
                $record_act->save();
            }else{
                return back()->withAccountRegistrationFailedStatus('Account Registration Failed! try again later.');
            }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
