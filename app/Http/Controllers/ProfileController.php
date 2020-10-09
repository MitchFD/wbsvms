<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;

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
