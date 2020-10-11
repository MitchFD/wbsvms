<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Useremployees;
use App\Models\Editedolduseremployees;
use App\Models\Editednewuseremployees;
use App\Models\Userstudents;
use App\Models\Editedoldstudentusers;
use App\Models\Editednewstudentusers;
use App\Models\Useractivites;
use Illuminate\Support\Facades\DB;

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
        // validate
            // $this->validate($request, [
            //     'upd_emp_user_image' => 'image|nullable|max:1999'
            // ]);
        // now timestamp
            $now_timestamp  = now();
            $format_now_timestamp = $now_timestamp->format('dmYHis');
        // get all request
            $get_upd_emp_user_image = $request->file('upd_emp_user_image');
            $get_selected_userId    = $request->get('selected_user_id');
            $get_upd_empEmail       = $request->get('upd_emp_email');
            $get_upd_empId          = $request->get('upd_emp_id');
            $get_upd_empLname       = $request->get('upd_emp_lname');
            $get_upd_empFname       = $request->get('upd_emp_fname');
            $get_upd_empJobdesc     = $request->get('upd_emp_jobdesc');
            $get_upd_empDept        = $request->get('upd_emp_dept');
            $get_upd_empPhnum       = $request->get('upd_emp_phnum');
        // get user's original info
            $fetch_original_user = Users::where('id' , $get_selected_userId)->first();
            $emp_org_empEmail    = $fetch_original_user->email;
            $emp_org_empID       = $fetch_original_user->user_sdca_id;
            $emp_org_empImage    = $fetch_original_user->user_image;
            $emp_org_empLname    = $fetch_original_user->user_lname;
            $emp_org_empFname    = $fetch_original_user->user_fname;
            $emp_org_empuRole    = $fetch_original_user->user_role;
            $emp_org_empuType    = $fetch_original_user->user_type;

            $fetch_original_emp = Useremployees::where('uEmp_id', $emp_org_empID)->first();
            $emp_org_empJobdesc = $fetch_original_emp->uEmp_job_desc;
            $emp_org_empDept    = $fetch_original_emp->uEmp_dept;
            $emp_org_empPhnum   = $fetch_original_emp->uEmp_phnum;
        // user image update handler
            if($request->hasFile('upd_emp_user_image')){
                $get_filenameWithExt = $request->file('upd_emp_user_image')->getClientOriginalName();
                $get_justFile        = pathinfo($get_filenameWithExt, PATHINFO_FILENAME);
                $get_justExt         = $request->file('upd_emp_user_image')->getClientOriginalExtension();
                $fileNameToStore     = $get_justFile.'_'.$format_now_timestamp.'.'.$get_justExt;
                // $uploadImageToPath   = $request->file('upd_emp_user_image')->storeAs('public/storage/svms/user_images',$fileNameToStore);
            }else{
                $fileNameToStore = $emp_org_empImage;
            }
        // update record from users table
            $update_users_tbl = DB::table('users')
                ->where('id', $get_selected_userId)
                ->update([
                    'email' => $get_upd_empEmail,
                    'user_sdca_id' => $get_upd_empId,
                    'user_image' => $fileNameToStore,
                    'user_lname' => $get_upd_empLname,
                    'user_fname' => $get_upd_empFname,
                    'updated_at' => $now_timestamp
                    ]);

        // if update was successful
            if($update_users_tbl){
                // update user_employees_tbl
                    $update_users_tbl = DB::table('user_employees_tbl')
                        ->where('uEmp_id', $emp_org_empID)
                        ->update([
                            'uEmp_id' => $get_upd_empId,
                            'uEmp_job_desc' => $get_upd_empJobdesc,
                            'uEmp_dept' => $get_upd_empDept,
                            'uEmp_phnum' => $get_upd_empPhnum
                            ]);
                // store uploaded image to public/storage/svms/user_images
                    if($request->hasFile('upd_emp_user_image')){
                        $uploadImageToPath = $request->file('upd_emp_user_image')->storeAs('public/storage/svms/user_images',$fileNameToStore);
                    }
                // record original user's info to edited_old_emp_users_tbl
                    $rec_orginalUserInfo = new Editedolduseremployees;
                    $rec_orginalUserInfo->from_user_id    = $get_selected_userId;
                    $rec_orginalUserInfo->eOld_uRole      = $emp_org_empuRole;
                    $rec_orginalUserInfo->eOld_email      = $emp_org_empEmail;
                    $rec_orginalUserInfo->eOld_user_type  = $emp_org_empuType;
                    $rec_orginalUserInfo->eOld_user_image = $emp_org_empImage;
                    $rec_orginalUserInfo->eOld_user_lname = $emp_org_empLname;
                    $rec_orginalUserInfo->eOld_user_fname = $emp_org_empFname;
                    $rec_orginalUserInfo->eOld_sdca_id    = $emp_org_empID;
                    $rec_orginalUserInfo->eOld_job_desc   = $emp_org_empJobdesc;
                    $rec_orginalUserInfo->eOld_dept       = $emp_org_empDept;
                    $rec_orginalUserInfo->eOld_phnum      = $emp_org_empPhnum;
                    $rec_orginalUserInfo->respo_user_id   = $get_selected_userId;
                    $rec_orginalUserInfo->edited_at       = $now_timestamp;
                    $rec_orginalUserInfo->save();
                // get id from latest update on edited_old_emp_users_tbl
                    $get_eOldEmp_id  = Editedolduseremployees::select('eOldEmp_id')->where('from_user_id', $get_selected_userId)->latest('edited_at')->first();
                    $from_eOldEmp_id = $get_eOldEmp_id->eOldEmp_id;
                // record new user's info to edited_new_emp_users_tbl
                    $rec_orginalEmpInfo = new Editednewuseremployees;
                    $rec_orginalEmpInfo->from_eOldEmp_id = $from_eOldEmp_id;
                    $rec_orginalEmpInfo->eNew_uRole      = $emp_org_empuRole;
                    $rec_orginalEmpInfo->eNew_email      = $get_upd_empEmail;
                    $rec_orginalEmpInfo->eNew_user_type  = $emp_org_empuType;
                    $rec_orginalEmpInfo->eNew_user_image = $fileNameToStore;
                    $rec_orginalEmpInfo->eNew_user_lname = $get_upd_empLname;
                    $rec_orginalEmpInfo->eNew_user_fname = $get_upd_empFname;
                    $rec_orginalEmpInfo->eNew_sdca_id    = $get_upd_empId;
                    $rec_orginalEmpInfo->eNew_job_desc   = $get_upd_empJobdesc;
                    $rec_orginalEmpInfo->eNew_dept	     = $get_upd_empDept;
                    $rec_orginalEmpInfo->eNew_phnum      = $get_upd_empPhnum;
                    $rec_orginalEmpInfo->edited_at       = $now_timestamp;
                    $rec_orginalEmpInfo->save();
                // record activity
                    $rec_activity = new Useractivites;
                    $rec_activity->created_at            = $now_timestamp;
                    $rec_activity->act_respo_user_id     = $get_selected_userId;
                    $rec_activity->act_respo_users_lname = $emp_org_empLname;
                    $rec_activity->act_respo_users_fname = $emp_org_empFname;
                    $rec_activity->act_type              = 'Update Account';
                    $rec_activity->act_details           = 'Update Account';
                    $rec_activity->act_affected_id       = $from_eOldEmp_id;
                    $rec_activity->save();

                    return back()->withSuccessStatus('Your Account was updated successfully.');
            }else{
                return back()->withFailedStatus('Your Account was updated successfully.');
            }
        // echo 'update profile <br/>'; 
        // echo 'user image: ' .$fileNameToStore. ' <br/>'; 
        // echo 'id: ' .$get_selected_userId. ' <br/>'; 
        // echo 'employee id: ' .$get_upd_empId. ' <br/>'; 
        // echo 'full name: ' .$get_upd_empFname. ' ' .$get_upd_empLname. ' <br/>'; 
        // echo 'job description: ' .$get_upd_empJobdesc. ' <br/>'; 
        // echo 'Department: ' .$get_upd_empDept. ' <br/>'; 
        // echo 'Phone Number: ' .$get_upd_empPhnum. ' <br/>'; 
        // echo 'Email: ' .$get_upd_empEmail. ' <br/>'; 
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
