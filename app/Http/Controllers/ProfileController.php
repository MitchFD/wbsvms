<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // get user type
        // $user_type = auth()->user()->user_type;
        // if($user_type == 'student'){
        //     $this_user = DB::table('users')
        //     ->join('user_students_tbl', 'users.id', '=', 'user_students_tbl.uStud_num')
        //     ->get();
        //     return view('profile.student_user_profile')->with('this_user');
        // }else{
        //     $this_user = DB::table('users')
        //     ->join('user_employees_tbl', 'users.id', '=', 'user_employees_tbl.uEmp_id')
        //     ->get();
        //     return view('profile.employee_user_profile')->with('this_user');
        // }
        return view('profile.index');
    }

    // update employee account information
    public function update_emp_user_profile(Request $request){
        // get all request
            $get_selected_user_id = $request->get('selected_user_id');
            $get_upd_emp_email    = $request->get('upd_emp_email');
            $get_upd_emp_id       = $request->get('upd_emp_id');
            $get_upd_emp_lname    = $request->get('upd_emp_lname');
            $get_upd_emp_fname    = $request->get('upd_emp_fname');
            $get_upd_emp_jobdesc  = $request->get('upd_emp_jobdesc');
            $get_upd_emp_dept     = $request->get('upd_emp_dept');
            $get_upd_emp_phnum    = $request->get('upd_emp_phnum');

        // now timestamp
            $now_timestamp  = now();

        // update record from users table
            // $affected = DB::table('users')
            //     ->where('id', 1)
            //     ->update(['votes' => 1]);

        echo 'update profile <br/>'; 
        echo 'id: ' .$get_selected_user_id. ' <br/>'; 
        echo 'employee id: ' .$get_upd_emp_id. ' <br/>'; 
        echo 'full name: ' .$get_upd_emp_fname. ' ' .$get_upd_emp_lname. ' <br/>'; 
        echo 'job description: ' .$get_upd_emp_jobdesc. ' <br/>'; 
        echo 'Department: ' .$get_upd_emp_dept. ' <br/>'; 
        echo 'Phone Number: ' .$get_upd_emp_phnum. ' <br/>'; 
        echo 'Email: ' .$get_upd_emp_email. ' <br/>'; 
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        auth()->user()->update($request->all());

        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Password successfully updated.'));
    }
}
