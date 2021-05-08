<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use App\Models\Userupdatesstatus;
use App\Models\Useremployees;
use App\Models\Editedolduseremployees;
use App\Models\Editednewuseremployees;
use App\Models\Userstudents;
use App\Models\Editedoldstudentusers;
use App\Models\Editednewstudentusers;
use App\Models\Passwordupdate;  
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
    public function edit(){
        return view('profile.edit');
    }

    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function index(){
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
        $my_first_record = Useractivites::where('act_respo_user_id', auth()->user()->id)->first();
        $my_latest_record = Useractivites::where('act_respo_user_id', auth()->user()->id)->latest()->first();
        $user_activities = Useractivites::where('act_respo_user_id', auth()->user()->id)->paginate(10);
        return view('profile.index')->with(compact('user_activities', 'my_first_record', 'my_latest_record'));
    }

    // FUNCTIONS FOR CHECKING NEW EMAIL AVAILABILITY
    // employee email
    public function emp_user_switch_new_email_availability_check(Request $request){
        if($request->get('emp_email')){
            $emp_id    = $request->get('emp_id');
            $emp_email = $request->get('emp_email');
            // get user's original email
            $get_emp_org_email = Users::select('id', 'email')->where('id', $emp_id)->first();
            $emp_org_email     = $get_emp_org_email->email;
            
            if($emp_org_email === $emp_email){
                echo 'unique';
            }else{
                $data = Users::where('email', $emp_email)->whereNotIn('id', [$emp_id])->count();
                if($data > 0){
                    echo 'not_unique';
                }else{
                    echo 'unique';
                }
            }
        }
    }
    // student email
    public function stud_user_switch_new_email_availability_check(Request $request){
        if($request->get('stud_email')){
            $stud_id    = $request->get('stud_id');
            $stud_email = $request->get('stud_email');
            // get user's original email
            $get_stud_org_email = Users::select('id', 'email')->where('id', $stud_id)->first();
            $stud_org_email     = $get_stud_org_email->email;
            
            if($stud_org_email === $stud_email){
                echo 'unique';
            }else{
                $data = Users::where('email', $stud_email)->whereNotIn('id', [$stud_id])->count();
                if($data > 0){
                    echo 'not_unique';
                }else{
                    echo 'unique';
                }
            }
        }
    }

    // FUNCTIONS FOR UPDATING ACCOUNTS
    // update employee account information
    public function update_emp_user_own_profile(Request $request){
        // validate
            // $this->validate($request, [
            //     'upd_emp_own_user_image' => 'image|nullable|max:1999'
            // ]);
        // now timestamp
            $now_timestamp  = now();
            $format_now_timestamp = $now_timestamp->format('dmYHis');
        // get all request
            $get_selected_userId    = $request->get('own_user_id');
            $get_upd_emp_user_image = $request->file('upd_emp_own_user_image');
            $get_upd_empEmail       = $request->get('upd_emp_own_email');
            $get_upd_empId          = $request->get('upd_emp_own_id');
            $get_upd_empLname       = $request->get('upd_emp_own_lname');
            $get_upd_empFname       = $request->get('upd_emp_own_fname');
            $get_upd_empGender      = $request->get('upd_emp_own_gender');
            $get_upd_empJobdesc     = $request->get('upd_emp_own_jobdesc');
            $get_upd_empDept        = $request->get('upd_emp_own_dept');
            $get_upd_empPhnum       = $request->get('upd_emp_own_phnum');
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
                    $user_mr_ms   = 'Mr.';
                }elseif($old_user_gender == 'female'){
                    $userGenderTxt = 'her';
                    $user_mr_ms   = 'Ms.';
                }else{
                    $userGenderTxt = 'his/her';
                    $user_mr_ms   = 'Mr./Ms.';
                }
        // user image update handler
            if($request->hasFile('upd_emp_own_user_image')){
                $get_filenameWithExt = $request->file('upd_emp_own_user_image')->getClientOriginalName();
                $get_justFile        = pathinfo($get_filenameWithExt, PATHINFO_FILENAME);
                $get_justExt         = $request->file('upd_emp_own_user_image')->getClientOriginalExtension();
                $fileNameToStore     = $get_justFile.'_'.$format_now_timestamp.'.'.$get_justExt;
                // $uploadImageToPath   = $request->file('upd_emp_own_user_image')->storeAs('public/storage/svms/user_images',$fileNameToStore);
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
                    if($request->hasFile('upd_emp_own_user_image')){
                        $destinationPath   = public_path('/storage/svms/user_images');
                        $uploadImageToPath = $request->file('upd_emp_own_user_image')->move($destinationPath,$fileNameToStore);
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
                // send email
                    $details = [
                        'svms_logo'           => "storage/svms/logos/svms_logo_text.png",
                        'title'               => 'PROFILE UPDATE',
                        'recipient'           => $user_mr_ms . ' ' .$emp_org_empFname . ' ' . $emp_org_empLname,
                        'date_of_changes'     => $now_timestamp
                    ];
                    $old_profile = [
                        'user_image'      => 'storage/svms/user_images/'.$emp_org_empImage,
                        'user_type'       => $emp_org_empuType,
                        'user_email'      => $emp_org_empEmail,
                        'user_role'       => $emp_org_empuRole,
                        'user_sdca_id'    => $emp_org_empID,
                        'user_first_name' => $emp_org_empFname,
                        'user_last_name'  => $emp_org_empLname,
                        'user_gender'     => $old_user_gender,
                        'user_job_desc'   => $emp_org_empJobdesc,
                        'user_dept'       => $emp_org_empDept,
                        'user_phnum'      => $emp_org_empPhnum,
                    ];
                    $new_profile = [
                        'user_image'      => 'storage/svms/user_images/'.$fileNameToStore,
                        'user_type'       => $emp_org_empuType,
                        'user_email'      => $get_upd_empEmail,
                        'user_role'       => $emp_org_empuRole,
                        'user_sdca_id'    => $get_upd_empId,
                        'user_first_name' => $get_upd_empFname,
                        'user_last_name'  => $get_upd_empLname,
                        'user_gender'     => $new_user_gender,
                        'user_job_desc'   => $get_upd_empJobdesc,
                        'user_dept'       => $get_upd_empDept,
                        'user_phnum'      => $get_upd_empPhnum,
                    ];
                    // if user has email
                        if(!empty($emp_org_empEmail)){
                            // notify user from his/her old email
                            \Mail::to('mfodesierto2@gmail.com')->send(new \App\Mail\OwnProfileUpdateSendMail($details, $old_profile ,$new_profile));

                            if(!empty($get_upd_empEmail)){
                                if($emp_org_empEmail !== $get_upd_empEmail){
                                        $rec_user_stats_update_tbl = new Userupdatesstatus;
                                        $rec_user_stats_update_tbl->from_user_id   = $get_selected_userId;
                                        $rec_user_stats_update_tbl->updated_status = 'active';
                                        $rec_user_stats_update_tbl->reason_update  = 'switching to a new email address';
                                        $rec_user_stats_update_tbl->updated_at     = $now_timestamp;
                                        $rec_user_stats_update_tbl->updated_by     = $get_selected_userId;
                                        $rec_user_stats_update_tbl->save();
                                    // notify user that this new email has been registered as a user of SVMS
                                        \Mail::to($get_upd_empEmail)->send(new \App\Mail\OwnProfileUpdateNewEmailSendMail($details, $old_profile ,$new_profile));
                                    // log out user an dredirect to login page
                                        $record_act = new Useractivites;
                                        $record_act->created_at            = $now_timestamp;
                                        $record_act->act_respo_user_id     = $get_selected_userId;
                                        $record_act->act_respo_users_lname = $emp_org_empLname;
                                        $record_act->act_respo_users_fname = $emp_org_empFname;
                                        $record_act->act_type              = 'logout';
                                        $record_act->act_details           = 'Logged out.';
                                        $record_act->act_affected_id       = $get_selected_userId;
                                        $record_act->save();
                                        Auth::logout();
                                        return redirect('/')->withSuccessStatus('Your Account was updated successfully. Use your newly registered email address to log in to the system.');
                                }else{
                                    return back()->withSuccessStatus('Your Account was updated successfully.');
                                }
                            }
                        }
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
    public function update_stud_user_own_profile(Request $request){
        // now timestamp
            $now_timestamp  = now();
            $format_now_timestamp = $now_timestamp->format('dmYHis');
        // get all request
            $get_upd_stud_user_image = $request->file('upd_stud_own_user_image');
            $get_selected_userId     = $request->get('own_user_id');
            $get_upd_studEmail       = $request->get('upd_stud_own_email');
            $get_upd_studNum         = $request->get('upd_stud_own_id');
            $get_upd_studLname       = $request->get('upd_stud_own_lname');
            $get_upd_studFname       = $request->get('upd_stud_own_fname');
            $get_upd_studGender      = $request->get('upd_stud_own_gender');
            $get_upd_studSchool      = $request->get('upd_stud_own_school');
            $get_upd_studProgram     = $request->get('upd_stud_own_program');
            $get_upd_studYearlvl     = $request->get('upd_stud_own_yearlvl');
            $get_upd_studSection     = $request->get('upd_stud_own_section');
            $get_upd_studPhnum       = $request->get('upd_stud_own_phnum');
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
                    $user_mr_ms   = 'Mr.';
                }elseif($old_user_gender == 'female'){
                    $userGenderTxt = 'her';
                    $user_mr_ms   = 'Ms.';
                }else{
                    $userGenderTxt = 'his/her';
                    $user_mr_ms   = 'Mr./Ms.';
                }
        // user image update handler
            if($request->hasFile('upd_stud_own_user_image')){
                $get_filenameWithExt = $request->file('upd_stud_own_user_image')->getClientOriginalName();
                $get_justFile        = pathinfo($get_filenameWithExt, PATHINFO_FILENAME);
                $get_justExt         = $request->file('upd_stud_own_user_image')->getClientOriginalExtension();
                $fileNameToStore     = $get_justFile.'_'.$format_now_timestamp.'.'.$get_justExt;
                // $uploadImageToPath   = $request->file('upd_stud_own_user_image')->storeAs('public/storage/svms/user_images',$fileNameToStore);
            }else{
                $fileNameToStore = $stud_orgImage;
            }
        // echo 'update profile <br/>'; 
            // echo 'user image: ' .$fileNameToStore. ' === ' .$stud_orgImage. ' <br/>'; 
            // echo 'id: ' .$get_selected_userId. '  <br/>'; 
            // echo 'employee id: ' .$get_upd_studNum. ' === ' .$stud_orgStudNum. '  <br/>'; 
            // echo 'full name: ' .$get_upd_studFname. ' ' .$get_upd_studLname. ' === ' .$stud_orgFname. ' ' .$stud_orgLname. '  <br/>'; 
            // echo 'School: ' .$get_upd_studSchool. ' === ' .$stud_orgSchool. '  <br/>'; 
            // echo 'Program: ' .$get_upd_studProgram. ' === ' .$stud_orgProgram. '  <br/>'; 
            // echo 'Year Level: ' .$get_upd_studYearlvl. ' === ' .$stud_orgYearlvl. '  <br/>'; 
            // echo 'Section: ' .$get_upd_studSection. ' === ' .$stud_orgSection. '  <br/>'; 
            // echo 'Phone Number: ' .$get_upd_studPhnum. ' === ' .$stud_orgPhnum. '  <br/>'; 
            // echo 'Email: ' .$get_upd_studEmail. ' === ' .$stud_orgEmail. '  <br/>';
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
                    if($request->hasFile('upd_stud_own_user_image')){
                        $destinationPath   = public_path('/storage/svms/user_images');
                        $uploadImageToPath = $request->file('upd_stud_own_user_image')->move($destinationPath,$fileNameToStore);
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
                // send email
                    $details = [
                        'svms_logo'           => "storage/svms/logos/svms_logo_text.png",
                        'title'               => 'PROFILE UPDATE',
                        'recipient'           => $user_mr_ms . ' ' .$stud_orgFname . ' ' . $stud_orgLname,
                        'date_of_changes'     => $now_timestamp
                    ];
                    $old_profile = [
                        'user_image'      => 'storage/svms/user_images/'.$stud_orgImage,
                        'user_type'       => $stud_orgType,
                        'user_email'      => $stud_orgEmail,
                        'user_role'       => $stud_orgRole,
                        'user_sdca_id'    => $stud_orgStudNum,
                        'user_first_name' => $stud_orgFname,
                        'user_last_name'  => $stud_orgLname,
                        'user_gender'     => $old_user_gender,
                        'user_school'     => $stud_orgSchool,
                        'user_program'    => $stud_orgProgram,
                        'user_yrlvl'      => $stud_orgYearlvl,
                        'user_section'    => $stud_orgSection,
                        'user_phnum'      => $stud_orgPhnum,
                    ];
                    $new_profile = [
                        'user_image'      => 'storage/svms/user_images/'.$fileNameToStore,
                        'user_type'       => $stud_orgType,
                        'user_email'      => $get_upd_studEmail,
                        'user_role'       => $stud_orgRole,
                        'user_sdca_id'    => $get_upd_studNum,
                        'user_first_name' => $get_upd_studFname,
                        'user_last_name'  => $get_upd_studLname,
                        'user_gender'     => $new_user_gender,
                        'user_school'     => $get_upd_studSchool,
                        'user_program'    => $get_upd_studProgram,
                        'user_yrlvl'      => $get_upd_studYearlvl,
                        'user_section'    => $get_upd_studSection,
                        'user_phnum'      => $get_upd_studPhnum,
                    ];
                    // if user has email
                        if(!empty($stud_orgEmail)){
                            // notify user from his/her old email
                            \Mail::to('mfodesierto2@gmail.com')->send(new \App\Mail\OwnProfileUpdateSendMail($details, $old_profile ,$new_profile));

                            if(!empty($get_upd_studEmail)){
                                if($stud_orgEmail !== $get_upd_studEmail){
                                        $rec_user_stats_update_tbl = new Userupdatesstatus;
                                        $rec_user_stats_update_tbl->from_user_id   = $get_selected_userId;
                                        $rec_user_stats_update_tbl->updated_status = 'active';
                                        $rec_user_stats_update_tbl->reason_update  = 'switching to a new email address';
                                        $rec_user_stats_update_tbl->updated_at     = $now_timestamp;
                                        $rec_user_stats_update_tbl->updated_by     = $get_selected_userId;
                                        $rec_user_stats_update_tbl->save();
                                    // notify user that this new email has been registered as a user of SVMS
                                        \Mail::to($get_upd_studEmail)->send(new \App\Mail\OwnProfileUpdateNewEmailSendMail($details, $old_profile ,$new_profile));
                                    // log out user an dredirect to login page
                                        $record_act = new Useractivites;
                                        $record_act->created_at            = $now_timestamp;
                                        $record_act->act_respo_user_id     = $get_selected_userId;
                                        $record_act->act_respo_users_lname = $stud_orgLname;
                                        $record_act->act_respo_users_fname = $stud_orgFname;
                                        $record_act->act_type              = 'logout';
                                        $record_act->act_details           = 'Logged out.';
                                        $record_act->act_affected_id       = $get_selected_userId;
                                        $record_act->save();
                                        Auth::logout();
                                        return redirect('/')->withSuccessStatus('Your Account was updated successfully. Use your newly registered email address to log in to the system.');
                                }else{
                                    return back()->withSuccessStatus('Your Account was updated successfully.');
                                }
                            }
                        }
                    return back()->withSuccessStatus('Your Account was updated successfully.');
            }else{
                return back()->withFailedStatus('Your Account was updated successfully.');
            }
    }

    // FUNCTIONS FOR CHANGING PASSWORD
    // check original password
    public function check_my_old_password(Request $request){
       if($request->get('my_old_pass')){
           $my_id = $request->get('my_id');
           $my_old_pass = $request->get('my_old_pass');
           if(Hash::check($my_old_pass, auth()->user()->password)){
                echo 'same';
           }else{
               echo 'not_same';
           }
       }
    }
    // Change password
    public function update_my_password(Request $request){
        // now timestamp
            $now_timestamp        = now();
            $format_now_timestamp = $now_timestamp->format('dmYHis');
        // get all request
            $get_my_id         = $request->get('selected_user_own_id');
            $get_my_new_pass = $request->get('upd_myNew_password');
        // echo $get_my_id .' <br /> ';
        // echo $get_my_new_pass .' <br /> ';
        // get user's info
            $get_my_info = Users::select('id', 'email', 'user_lname', 'user_fname', 'user_gender')->where('id', $get_my_id)->first();
            $get_my_email  = $get_my_info->email;
            $get_my_fname  = $get_my_info->user_fname;
            $get_my_lname  = $get_my_info->user_lname;
            $get_my_gender = $get_my_info->user_gender;
        // his/her & Mr./Ms.
            if($get_my_gender === 'female'){
                $his_her = 'her';
                $mr_ms   = 'Ms.';
            }elseif($get_my_gender === 'male'){
                $his_her = 'his';
                $mr_ms   = 'Mr.';
            }else{
                $his_her = 'his/her';
                $mr_ms   = 'Mr./Ms.';
            }
        // apostrophe
            $s_s = "'";
        // hass pass
            $hash_my_new_pass = Hash::make($get_my_new_pass);
        // update users table
            $update_sys_users_tbl = DB::table('users')
            ->where('id', $get_my_id)
            ->update([
                'password'   => $hash_my_new_pass,
                'updated_at' => $now_timestamp
                ]);
        // if update was a success
            if($update_sys_users_tbl){
                // record password update to password_updates_tbl
                    $rec_pass_update = new Passwordupdate;
                    $rec_pass_update->sel_user_id    = $get_my_id;
                    $rec_pass_update->upd_by_user_id = $get_my_id;
                    $rec_pass_update->reason_update  = ' ';
                    $rec_pass_update->updated_at     = $now_timestamp;
                    $rec_pass_update->save();
                // get id from latest update on password_updates_tbl
                    $get_pass_upd_id  = Passwordupdate::select('pass_upd_id')->where('sel_user_id', $get_my_id)->latest('updated_at')->first();
                    $from_pass_upd_id = $get_pass_upd_id->pass_upd_id;
                // record activity
                    $rec_activity = new Useractivites;
                    $rec_activity->created_at            = $now_timestamp;
                    $rec_activity->act_respo_user_id     = $get_my_id;
                    $rec_activity->act_respo_users_lname = $get_my_lname;
                    $rec_activity->act_respo_users_fname = $get_my_fname;
                    $rec_activity->act_type              = 'password update';
                    $rec_activity->act_details           = $get_my_fname. ' ' .$get_my_lname . ' Updated ' . $his_her . ' Password.';
                    $rec_activity->act_affected_id       = $from_pass_upd_id;
                    $rec_activity->save();
                // send email
                    $details = [
                        'svms_logo'        => "storage/svms/logos/svms_logo_text.png",
                        'title'            => 'PASSWORD UPDATE',
                        'recipient'        => $mr_ms . ' ' .$get_my_fname . ' ' . $get_my_lname,
                        'pass_updt_reason' => ' ',
                        'sysUser_email'    => $get_my_email,
                        'sysUser_newPass'  => $get_my_new_pass
                    ];
                    if(!empty($get_my_email)){
                        \Mail::to('mfodesierto2@gmail.com')->send(new \App\Mail\OwnPasswordUpdateSendMail($details));
                    }
                    // log out user an dredirect to login page
                        $record_act = new Useractivites;
                        $record_act->created_at            = $now_timestamp;
                        $record_act->act_respo_user_id     = $get_my_id;
                        $record_act->act_respo_users_lname = $get_my_lname;
                        $record_act->act_respo_users_fname = $get_my_fname;
                        $record_act->act_type              = 'logout';
                        $record_act->act_details           = 'Logged out.';
                        $record_act->act_affected_id       = $get_my_id;
                        $record_act->save();
                        Auth::logout();
                        return redirect('/')->withSuccessStatus('Your Password was updated successfully. Use your new password to log in to the system.');
                // return back()->withSuccessStatus('Your Password was updated successfully.');
            }else{
                return back()->withFailedStatus('Your Password Update has failed, Try again  later.');
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
