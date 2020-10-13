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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $user_activities = Useractivites::where('act_respo_user_id', auth()->user()->id)->get();
        return view('profile.index')->with(compact('user_activities'));
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
            $get_upd_empGender      = $request->get('upd_emp_gender');
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
            $emp_org_empGender   = $fetch_original_user->user_gender;
            $emp_org_empuRole    = $fetch_original_user->user_role;
            $emp_org_empuType    = $fetch_original_user->user_type;

            $fetch_original_emp = Useremployees::where('uEmp_id', $emp_org_empID)->first();
            $emp_org_empJobdesc = $fetch_original_emp->uEmp_job_desc;
            $emp_org_empDept    = $fetch_original_emp->uEmp_dept;
            $emp_org_empPhnum   = $fetch_original_emp->uEmp_phnum;
        // user gender format
                $old_user_gender = Str::lower($emp_org_empGender);
                $new_user_gender = Str::lower($get_upd_empGender);
                if($old_user_gender == 'male'){
                    $userGenderTxt = 'his';
                }elseif($old_user_gender == 'female'){
                    $userGenderTxt = 'her';
                }else{
                    $userGenderTxt = 'his/her';
                }
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
                    'user_image'   => $fileNameToStore,
                    'user_lname'   => $get_upd_empLname,
                    'user_fname'   => $get_upd_empFname,
                    'user_gender'  => $new_user_gender,
                    'updated_at'   => $now_timestamp
                    ]);

        // if update was successful
            if($update_users_tbl){
                // update user_employees_tbl
                    $update_users_tbl = DB::table('user_employees_tbl')
                        ->where('uEmp_id', $emp_org_empID)
                        ->update([
                            'uEmp_id'       => $get_upd_empId,
                            'uEmp_job_desc' => $get_upd_empJobdesc,
                            'uEmp_dept'     => $get_upd_empDept,
                            'uEmp_phnum'    => $get_upd_empPhnum
                            ]);
                // store uploaded image to public/storage/svms/user_images
                    if($request->hasFile('upd_emp_user_image')){
                        $destinationPath   = public_path('/storage/svms/user_images');
                        $uploadImageToPath = $request->file('upd_emp_user_image')->move($destinationPath,$fileNameToStore);
                    }
                // record original user's info to edited_old_emp_users_tbl
                    $rec_orginalUserInfo = new Editedolduseremployees;
                    $rec_orginalUserInfo->from_user_id     = $get_selected_userId;
                    $rec_orginalUserInfo->eOld_uRole       = $emp_org_empuRole;
                    $rec_orginalUserInfo->eOld_email       = $emp_org_empEmail;
                    $rec_orginalUserInfo->eOld_user_type   = $emp_org_empuType;
                    $rec_orginalUserInfo->eOld_user_image  = $emp_org_empImage;
                    $rec_orginalUserInfo->eOld_user_lname  = $emp_org_empLname;
                    $rec_orginalUserInfo->eOld_user_fname  = $emp_org_empFname;
                    $rec_orginalUserInfo->eOld_user_gender = $old_user_gender;
                    $rec_orginalUserInfo->eOld_sdca_id     = $emp_org_empID;
                    $rec_orginalUserInfo->eOld_job_desc    = $emp_org_empJobdesc;
                    $rec_orginalUserInfo->eOld_dept        = $emp_org_empDept;
                    $rec_orginalUserInfo->eOld_phnum       = $emp_org_empPhnum;
                    $rec_orginalUserInfo->respo_user_id    = $get_selected_userId;
                    $rec_orginalUserInfo->edited_at        = $now_timestamp;
                    $rec_orginalUserInfo->save();
                // get id from latest update on edited_old_emp_users_tbl
                    $get_eOldEmp_id  = Editedolduseremployees::select('eOldEmp_id')->where('from_user_id', $get_selected_userId)->latest('edited_at')->first();
                    $from_eOldEmp_id = $get_eOldEmp_id->eOldEmp_id;
                // record new user's info to edited_new_emp_users_tbl
                    $rec_orginalEmpInfo = new Editednewuseremployees;
                    $rec_orginalEmpInfo->from_eOldEmp_id  = $from_eOldEmp_id;
                    $rec_orginalEmpInfo->eNew_uRole       = $emp_org_empuRole;
                    $rec_orginalEmpInfo->eNew_email       = $get_upd_empEmail;
                    $rec_orginalEmpInfo->eNew_user_type   = $emp_org_empuType;
                    $rec_orginalEmpInfo->eNew_user_image  = $fileNameToStore;
                    $rec_orginalEmpInfo->eNew_user_lname  = $get_upd_empLname;
                    $rec_orginalEmpInfo->eNew_user_fname  = $get_upd_empFname;
                    $rec_orginalEmpInfo->eNew_user_gender = $new_user_gender;
                    $rec_orginalEmpInfo->eNew_sdca_id     = $get_upd_empId;
                    $rec_orginalEmpInfo->eNew_job_desc    = $get_upd_empJobdesc;
                    $rec_orginalEmpInfo->eNew_dept	      = $get_upd_empDept;
                    $rec_orginalEmpInfo->eNew_phnum       = $get_upd_empPhnum;
                    $rec_orginalEmpInfo->edited_at        = $now_timestamp;
                    $rec_orginalEmpInfo->save();
                // record activity
                    $rec_activity = new Useractivites;
                    $rec_activity->created_at            = $now_timestamp;
                    $rec_activity->act_respo_user_id     = $get_selected_userId;
                    $rec_activity->act_respo_users_lname = $emp_org_empLname;
                    $rec_activity->act_respo_users_fname = $emp_org_empFname;
                    $rec_activity->act_type              = 'update account';
                    $rec_activity->act_details           = $emp_org_empFname. ' ' . $emp_org_empLname . ' Updated ' .$userGenderTxt. ' Account.';
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

    // update student account information
    public function update_stud_user_profile(Request $request){
        // now timestamp
        $now_timestamp  = now();
        $format_now_timestamp = $now_timestamp->format('dmYHis');
    // get all request
        $get_upd_stud_user_image = $request->file('upd_stud_user_image');
        $get_selected_userId     = $request->get('selected_user_id');
        $get_upd_studEmail       = $request->get('upd_stud_email');
        $get_upd_studNum         = $request->get('upd_stud_num');
        $get_upd_studLname       = $request->get('upd_stud_lname');
        $get_upd_studFname       = $request->get('upd_stud_fname');
        $get_upd_studGender      = $request->get('upd_stud_gender');
        $get_upd_studSchool      = $request->get('upd_stud_school');
        $get_upd_studProgram     = $request->get('upd_stud_program');
        $get_upd_studYearlvl     = $request->get('upd_stud_yearlvl');
        $get_upd_studSection     = $request->get('upd_stud_section');
        $get_upd_studPhnum       = $request->get('upd_stud_phnum');
    // get user's original info
        $fetch_original_user = Users::where('id' , $get_selected_userId)->first();
        $stud_orgEmail       = $fetch_original_user->email;
        $stud_orgStudNum     = $fetch_original_user->user_sdca_id;
        $stud_orgImage       = $fetch_original_user->user_image;
        $stud_orgLname       = $fetch_original_user->user_lname;
        $stud_orgFname       = $fetch_original_user->user_fname;
        $stud_orgGender      = $fetch_original_user->user_gender;
        $stud_orgRole        = $fetch_original_user->user_role;
        $stud_orgType        = $fetch_original_user->user_type;

        $fetch_original_stud = Userstudents::where('uStud_num', $stud_orgStudNum)->first();
        $stud_orgSchool      = $fetch_original_stud->uStud_school;
        $stud_orgProgram     = $fetch_original_stud->uStud_program;
        $stud_orgYearlvl     = $fetch_original_stud->uStud_yearlvl;
        $stud_orgSection     = $fetch_original_stud->uStud_section;
        $stud_orgPhnum       = $fetch_original_stud->uStud_phnum;
    // user gender format
            $old_user_gender = Str::lower($stud_orgGender);
            $new_user_gender = Str::lower($get_upd_studGender);
            if($old_user_gender == 'male'){
                $userGenderTxt = 'his';
            }elseif($old_user_gender == 'female'){
                $userGenderTxt = 'her';
            }else{
                $userGenderTxt = 'his/her';
            }
    // user image update handler
        if($request->hasFile('upd_stud_user_image')){
            $get_filenameWithExt = $request->file('upd_stud_user_image')->getClientOriginalName();
            $get_justFile        = pathinfo($get_filenameWithExt, PATHINFO_FILENAME);
            $get_justExt         = $request->file('upd_stud_user_image')->getClientOriginalExtension();
            $fileNameToStore     = $get_justFile.'_'.$format_now_timestamp.'.'.$get_justExt;
            // $uploadImageToPath   = $request->file('upd_stud_user_image')->storeAs('public/storage/svms/user_images',$fileNameToStore);
        }else{
            $fileNameToStore = $stud_orgImage;
        }
    // update record from users table
        $update_users_tbl = DB::table('users')
            ->where('id', $get_selected_userId)
            ->update([
                'email'        => $get_upd_studEmail,
                'user_sdca_id' => $get_upd_studNum,
                'user_image'   => $fileNameToStore,
                'user_lname'   => $get_upd_studLname,
                'user_fname'   => $get_upd_studFname,
                'user_gender'  => $new_user_gender,
                'updated_at'   => $now_timestamp
                ]);

    // if update was successful
        if($update_users_tbl){
            // update user_students_tbl
                $update_users_tbl = DB::table('user_students_tbl')
                    ->where('uStud_num', $stud_orgStudNum)
                    ->update([
                        'uStud_num'     => $get_upd_studNum,
                        'uStud_school'  => $get_upd_studSchool,
                        'uStud_program' => $get_upd_studProgram,
                        'uStud_yearlvl' => $get_upd_studYearlvl,
                        'uStud_section' => $get_upd_studSection,
                        'uStud_phnum'   => $get_upd_studPhnum
                        ]);
            // store uploaded image to public/storage/svms/user_images
                if($request->hasFile('upd_stud_user_image')){
                    $destinationPath   = public_path('/storage/svms/user_images');
                    $uploadImageToPath = $request->file('upd_stud_user_image')->move($destinationPath,$fileNameToStore);
                }
            // record original user's info to edited_old_stud_users_tbl
                $rec_orginalUserInfo = new Editedoldstudentusers;
                $rec_orginalUserInfo->from_user_id     = $get_selected_userId;
                $rec_orginalUserInfo->eOld_uRole       = $stud_orgRole;
                $rec_orginalUserInfo->eOld_email       = $stud_orgEmail;
                $rec_orginalUserInfo->eOld_user_type   = $stud_orgType;
                $rec_orginalUserInfo->eOld_user_image  = $stud_orgImage;
                $rec_orginalUserInfo->eOld_user_lname  = $stud_orgLname;
                $rec_orginalUserInfo->eOld_user_fname  = $stud_orgFname;
                $rec_orginalUserInfo->eOld_user_gender = $old_user_gender;
                $rec_orginalUserInfo->eOld_sdca_id     = $stud_orgStudNum;
                $rec_orginalUserInfo->eOld_school      = $stud_orgSchool;
                $rec_orginalUserInfo->eOld_program     = $stud_orgProgram;
                $rec_orginalUserInfo->eOld_yearlvl     = $stud_orgYearlvl;
                $rec_orginalUserInfo->eOld_section     = $stud_orgSection;
                $rec_orginalUserInfo->eOld_phnum       = $stud_orgPhnum;
                $rec_orginalUserInfo->respo_user_id    = $get_selected_userId;
                $rec_orginalUserInfo->edited_at        = $now_timestamp;
                $rec_orginalUserInfo->save();
            // get id from latest update on edited_old_stud_users_tbl
                $get_eOldStud_id  = Editedoldstudentusers::select('eOldStud_id')->where('from_user_id', $get_selected_userId)->latest('edited_at')->first();
                $from_eOldStud_id = $get_eOldStud_id->eOldStud_id;
            // record new user's info to edited_new_emp_users_tbl
                $rec_newStudInfo = new Editednewstudentusers;
                $rec_newStudInfo->from_eOldStud_id = $from_eOldStud_id;
                $rec_newStudInfo->eNew_email       = $get_upd_studEmail;
                $rec_newStudInfo->eNew_uRole       = $stud_orgRole;
                $rec_newStudInfo->eNew_user_type   = $stud_orgType;
                $rec_newStudInfo->eNew_user_image  = $fileNameToStore;
                $rec_newStudInfo->eNew_user_lname  = $get_upd_studLname;
                $rec_newStudInfo->eNew_user_fname  = $get_upd_studFname;
                $rec_newStudInfo->eNew_user_gender = $new_user_gender;
                $rec_newStudInfo->eNew_sdca_id     = $get_upd_studNum;
                $rec_newStudInfo->eNew_school      = $get_upd_studSchool;
                $rec_newStudInfo->eNew_program     = $get_upd_studProgram;
                $rec_newStudInfo->eNew_yearlvl     = $get_upd_studYearlvl;
                $rec_newStudInfo->eNew_section     = $get_upd_studSection;
                $rec_newStudInfo->eNew_phnum       = $get_upd_studPhnum;
                $rec_newStudInfo->edited_at        = $now_timestamp;
                $rec_newStudInfo->save();
            // record activity
                $rec_activity = new Useractivites;
                $rec_activity->created_at            = $now_timestamp;
                $rec_activity->act_respo_user_id     = $get_selected_userId;
                $rec_activity->act_respo_users_lname = $stud_orgLname;
                $rec_activity->act_respo_users_fname = $stud_orgFname;
                $rec_activity->act_type              = 'update account';
                $rec_activity->act_details           = $stud_orgFname. ' ' . $stud_orgLname . ' Updated ' .$userGenderTxt. ' Account.';
                $rec_activity->act_affected_id       = $from_eOldStud_id;
                $rec_activity->save();

                return back()->withSuccessStatus('Your Account was updated successfully.');
        }else{
            return back()->withFailedStatus('Your Account was updated successfully.');
        }
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
