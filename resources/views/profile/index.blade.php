@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'profile'
])

@section('content')
    <div class="content">
        {{-- notifications --}}
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        @if (session('password_status'))
            <div class="alert alert-success" role="alert">
                {{ session('password_status') }}
            </div>
        @endif
        @if (session('success_status'))
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12 col-md-12 col-sm-12 align-items-center mx-auto">
                    <div class="alert alert-success alert-dismissible login_alert fade show" role="alert">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="nc-icon nc-simple-remove"></i>
                        </button>
                        {{ session('success_status') }}
                    </div>
                </div>
            </div>
        @endif
        @if (session('failed_status'))
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12 col-md-12 col-sm-12 align-items-center mx-auto">
                    <div class="alert alert_smvs_danger alert-dismissible login_alert fade show" role="alert">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="nc-icon nc-simple-remove"></i>
                        </button>
                        {{ session('failed_status') }}
                    </div>
                </div>
            </div>
        @endif

        {{-- directory link --}}
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <a href="#" class="directory_link">My Profile</a>
            </div>
        </div>

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">My Profile</span>
                            @if(auth()->user()->user_status == 'pending')
                                <span class="page_intro_subtitle">Your account is currently not active. Please wait as the System Administrator reviews your registration. Head to the Student Discipline Office if your account is still not active after 2 to 3 days of registration to acticate your account.</span>
                            @else
                                <span class="page_intro_subtitle">This page displays your registered account's information. You can view, edit, and update your profile, and you can also view your activity log histories.</span>
                            @endif
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/my_profile_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- USER ACCOUNT INFORMATION --}}
            <div class="col-lg-4 col-md-5 col-sm-12">
                <div class="accordion" id="profileCollapse">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="profileCollapseHeading">
                            <button id="profile_collapseBtnToggle" class="btn btn-link btn-block custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#profileCollpase" aria-expanded="true" aria-controls="profileCollpase">
                                <div>
                                    <span class="card_body_title">Account Information</span>
                                    <span class="card_body_subtitle">View and update your profile.</span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="profileCollpase" class="collapse show cb_t0b15x25" aria-labelledby="profileCollapseHeading" data-parent="#profileCollapse">
                        @if(auth()->user()->user_type == 'student')
                            {{-- for student type user --}}
                            <ul class="nav nav-pills custom_nav_pills mt-0 mb-3 d-flex justify-content-center" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link custom_nav_link_green active" id="pills_profile_preview_tab_{{auth()->user()->id}}" data-toggle="pill" href="#profile_preview_{{auth()->user()->id}}" role="tab" aria-controls="profile_preview_{{auth()->user()->id}}" aria-selected="true">Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link custom_nav_link_green" id="pills_edit_profile_tab_{{auth()->user()->id}}" data-toggle="pill" href="#pills_edit_profile_{{auth()->user()->id}}" role="tab" aria-controls="pills_edit_profile_{{auth()->user()->id}}" aria-selected="false">Edit Profile</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="studentPills-tabContent">
                                {{-- PROFILE PREVIEW --}}
                                <div class="tab-pane fade show active" id="profile_preview_{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_profile_preview_tab_{{auth()->user()->id}}">
                                    @php
                                        $user_info = DB::table('user_students_tbl')->where('uStud_num', auth()->user()->user_sdca_id)->first();
                                    @endphp
                                    <div class="card card_gbr shadow card-user">
                                        <div class="image">
                                            <img src="{{ asset('paper/img/damir-bosnjak.jpg') }}" alt="...">
                                        </div>
                                        <div class="card-body">
                                            <div class="author">
                                                <a href="#" class="up_img_div">
                                                    <img class="up_stud_user_image shadow border-gray"
                                                    @if(!is_null(auth()->user()->user_image))
                                                        src="{{asset('storage/svms/user_images/'.auth()->user()->user_image)}}" alt="{{auth()->user()->user_fname }} {{ auth()->user()->user_lname}}'s profile image'"
                                                    @else
                                                        src="{{asset('storage/svms/user_images/employee_user_image.jpg')}}" alt="default employee user's profile image"
                                                    @endif
                                                    >
                                                </a>
                                                <span class="up_fullname_txt text-success">{{auth()->user()->user_fname }}  {{auth()->user()->user_lname}}</span>
                                                @if(!is_null(auth()->user()->user_role) AND auth()->user()->user_role != 'pending') <h5 class="up_role_txt">{{ __(auth()->user()->user_role)}}</h5> @endif
                                                
                                                <span class="cat_title_txt">Student Number</span>
                                                <span class="up_info_txt"><i class="nc-icon nc-badge"></i> {{ auth()->user()->user_sdca_id}}</span>
                        
                                                @if(!is_null($user_info->uStud_program)) <span class="up_info_txt mb-0">{{$user_info->uStud_program}}-{{$user_info->uStud_section}}</span> @endif
                                                @if(!is_null($user_info->uStud_school)) <span class="cat_title_txt mb-3">{{$user_info->uStud_school}}</span> @endif
                        
                                                @if(!is_null($user_info->uStud_phnum)) 
                                                <span class="cat_title_txt">Contact Number</span>
                                                <span class="up_info_txt"><i class="nc-icon nc-mobile"></i> {{ $user_info->uStud_phnum}}</span> 
                                                @endif
                                                
                                                @if(!is_null(auth()->user()->email)) 
                                                <span class="cat_title_txt">Email Address</span>
                                                <span class="up_info_txt"><i class="nc-icon nc-email-85"></i> {{ auth()->user()->email}}</span> 
                                                @endif
                        
                                                <span class="cat_title_txt">Account Status</span>
                                                @if(auth()->user()->user_status != 'active') 
                                                {{-- <span class="up_info_txt nactive_stat"><i class="nc-icon nc-circle-10"></i> {{ auth()->user()->user_status}}</span>  --}}
                                                <span class="up_info_txt nactive_stat"><i class="nc-icon nc-circle-10"></i> For activation</span> 
                                                @else
                                                <span class="up_info_txt active_stat"><i class="nc-icon nc-circle-10"></i> {{ auth()->user()->user_status}}</span> 
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- EDIT PROFILE --}}
                                <div class="tab-pane fade" id="pills_edit_profile_{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_edit_profile_tab_{{auth()->user()->id}}">
                                    <div class="card card_gbr shadow">
                                        <div class="card-body p-0">
                                            <div class="card-header cb_p15x25">
                                                <span class="sec_card_body_title">Edit Profile</span>
                                                <span class="sec_card_body_subtitle">Click the <span class="font-weight-bold">'Save Changes'</span> button to save the changes you've made and this will update your profile.</span>
                                            </div>
                                            <form id="form_studUpdateProfile" class="form" method="POST" action="{{route('profile.update_stud_user_profile')}}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="cb_px25 cb_pb15">
                                                    <div class="row d-flex justify-content-center">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 align-items-center">
                                                            <div class="up_img_div text-center">
                                                                <img class="up_stud_user_image stud_imgUpld_targetImg shadow border-gray" src="{{asset('storage/svms/user_images/'.auth()->user()->user_image)}}" alt="{{auth()->user()->user_fname }} {{ auth()->user()->user_lname}}'s profile image'">
                                                            </div>
                                                            <div class="user_image_upload_input_div stud_imgUpload">
                                                                <i class="nc-icon nc-image stud_imgUpld_TrgtBtn"></i>
                                                                <input name="upd_stud_user_image" class="file_upload_input stud_img_imgUpld_fileInpt" type="file" accept="image/*"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label for="upd_stud_email">Email Address</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-email-85" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_stud_email" name="upd_stud_email" type="text" class="form-control" @if(auth()->user()->email != 'null') value="{{auth()->user()->email}}" @else placeholder="Type Email Address" @endif required>
                                                    </div>
                                                    <label for="upd_stud_num">Student Number</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_stud_num" name="upd_stud_num" type="number" min="0" oninput="validity.valid||(value='');" class="form-control" @if(auth()->user()->user_sdca_id != 'null') value="{{auth()->user()->user_sdca_id}}" @else placeholder="Type Student Number" @endif required>
                                                    </div>
                                                    <label for="upd_stud_lname">Last Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-single-02"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_stud_lname" name="upd_stud_lname" type="text" class="form-control" @if(auth()->user()->user_lname != 'null') value="{{auth()->user()->user_lname}}" @else placeholder="Type Last Name" @endif required>
                                                    </div>
                                                    <label for="upd_stud_fname">First Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-single-02"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_stud_fname" name="upd_stud_fname" type="text" class="form-control" @if(auth()->user()->user_fname != 'null') value="{{auth()->user()->user_fname}}" @else placeholder="Type First Name" @endif required>
                                                    </div>
                                                    <label for="upd_stud_gender">Gender</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-single-02"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_stud_gender" list="updateStudGenderOptions" pattern="Male|Female" name="upd_stud_gender" type="text" class="form-control" @if(auth()->user()->user_gender != 'null') value="{{ucfirst(auth()->user()->user_gender)}}" @else placeholder="Select Gender" @endif required>
                                                        <datalist id="updateStudGenderOptions">
                                                            <option value="Male">
                                                            <option value="Female">
                                                        </datalist>
                                                    </div>
                                                    <label for="upd_stud_school">School</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_stud_school" list="updateStudSchoolOptions" pattern="SASE|SBCS|SIHTM|SHSP" name="upd_stud_school" type="text" class="form-control" @if($user_info->uStud_school != 'null') value="{{$user_info->uStud_school}}" @else placeholder="Type Your School" @endif required>
                                                        <datalist id="updateStudSchoolOptions">
                                                            <option value="SASE">
                                                            <option value="SBCS">
                                                            <option value="SIHTM">
                                                            <option value="SHSP">
                                                        </datalist>
                                                    </div>
                                                    <label for="upd_stud_program">Program</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_stud_program" list="updateStudProgramOptions" pattern="BS Psychology|BS Education|BA Communication|BSBA|BSA|BSIT|BSCS|BMA|BSHM|BSTM|BS Biology|BS Pharmacy|BS Radiologic Technology|BS Physical Therapy|BS Medical Technology|BS Nursing" name="upd_stud_program" type="text" class="form-control" @if($user_info->uStud_program != 'null') value="{{$user_info->uStud_program}}" @else placeholder="Type Your Program" @endif required>
                                                        <datalist id="updateStudProgramOptions">
                                                            
                                                        </datalist>
                                                    </div>
                                                    <label for="upd_stud_yearlvl">Year Level</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_stud_yearlvl" list="updateStudYearlvlOptions" pattern="FIRST YEAR|SECOND YEAR|THIRD YEAR|FOURTH YEAR|FIFTH YEAR" name="upd_stud_yearlvl" type="text" class="form-control" @if($user_info->uStud_yearlvl != 'null') value="{{$user_info->uStud_yearlvl}}" @else placeholder="Type Your Year Level" @endif required>
                                                        <datalist id="updateStudYearlvlOptions">

                                                        </datalist>
                                                    </div>
                                                    <label for="upd_stud_section">Section</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_stud_section" name="upd_stud_section" type="text" class="form-control" @if($user_info->uStud_section != 'null') value="{{$user_info->uStud_section}}" @else placeholder="Type Your Section" @endif required>
                                                    </div>
                                                    <label for="upd_stud_phnum">Phone NUmber</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-mobile" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input name="upd_stud_phnum" type="number" pattern="[0-9]{11}" min="0" oninput="validity.valid||(value='');" class="form-control" @if($user_info->uStud_phnum != 'null') value="{{$user_info->uStud_phnum}}" @else placeholder="Type Contact Number" @endif required>
                                                    </div>
                                                    <div class="d-flex justify-content-center">
                                                        <input type="hidden" name="selected_user_id" value="{{auth()->user()->id}}"/>
                                                        <button type="submit" id="update_studInfoBtn" class="btn btn-success btn-round btn_show_icon" disabled>{{ __('Save Changes') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card card_gbr shadow">
                                        <div class="card-body p-0">
                                            <div class="card-header cb_p15x25">
                                                <span class="sec_card_body_title">Change Password</span>
                                                <span class="sec_card_body_subtitle">Verify your old password and enter a new password to change your password.</span>
                                            </div>
                                            <form class="form" method="POST" action="#">
                                                <div class="cb_px25 cb_pb15">
                                                    <label for="cng_studOldPassword">Old Password</label>
                                                    <div class="input-group paswrd_inpt_fld">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-circle-10" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input type="password" id="cng_studOldPassword" name="cng_studOldPassword" class="form-control" placeholder="Type your old Password" required>
                                                        <i class="fa fa-eye" id="toggleStudOldPassword"></i>
                                                    </div>
                                                    <label for="cng_studNewPassword">New Password</label>
                                                    <div class="input-group paswrd_inpt_fld">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-key-25" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input type="password" id="cng_studNewPassword" name="cng_studNewPassword" class="form-control" placeholder="Type your new password" required>
                                                        <i class="fa fa-eye" id="toggleStudNewPassword"></i>
                                                    </div>
                                                    <label for="cng_confirmStudNewPassword">Confirm New Password</label>
                                                    <div class="input-group paswrd_inpt_fld">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-lock-circle-open" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input type="password" id="cng_confirmStudNewPassword" name="cng_confirmStudNewPassword" class="form-control" placeholder="Just in case you mistyped" required>
                                                        <i class="fa fa-eye" id="toggleStudConfirmNewPassword"></i>
                                                    </div>
                                                    <div class="d-flex justify-content-center">
                                                        <button type="submit" class="btn btn-success btn-round btn_show_icon" disabled>{{ __('Update My Password') }}<i class="nc-icon nc-key-25 btn_icon_show_right" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- for employee type user --}}
                            <ul class="nav nav-pills custom_nav_pills mt-0 mb-3 d-flex justify-content-center" id="emp_pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link custom_nav_link_blue active" id="emp_pills_profile_preview_tab_{{auth()->user()->id}}" data-toggle="pill" href="#emp_profile_preview_{{auth()->user()->id}}" role="tab" aria-controls="emp_profile_preview_{{auth()->user()->id}}" aria-selected="true">Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link custom_nav_link_blue" id="emp_pills_edit_profile_tab_{{auth()->user()->id}}" data-toggle="pill" href="#emp_pills_edit_profile_{{auth()->user()->id}}" role="tab" aria-controls="emp_pills_edit_profile_{{auth()->user()->id}}" aria-selected="false">Edit Profile</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="employeePills-tabContent"> 
                                {{-- EMPLOYEE PROFILE PREVIEW --}}
                                <div class="tab-pane fade show active" id="emp_profile_preview_{{auth()->user()->id}}" role="tabpanel" aria-labelledby="emp_pills_profile_preview_tab_{{auth()->user()->id}}">
                                    @php
                                        $user_info = DB::table('user_employees_tbl')->where('uEmp_id', auth()->user()->user_sdca_id)->first();
                                    @endphp
                                    <div class="card card_gbr shadow card-user">
                                        <div class="image">
                                            <img src="{{ asset('paper/img/damir-bosnjak.jpg') }}" alt="...">
                                        </div>
                                        <div class="card-body">
                                            <div class="author">
                                                <a href="#" class="up_img_div">
                                                    <img class="up_user_image shadow border-gray"
                                                    @if(!is_null(auth()->user()->user_image))
                                                        src="{{asset('storage/svms/user_images/'.auth()->user()->user_image)}}" alt="{{auth()->user()->user_fname }} {{ auth()->user()->user_lname}}'s profile image'"
                                                    @else
                                                        src="{{asset('storage/svms/user_images/employee_user_image.jpg')}}" alt="default employee user's profile image"
                                                    @endif
                                                    >
                                                </a>
                                                <span class="up_fullname_txt">{{auth()->user()->user_fname }}  {{auth()->user()->user_lname}}</span>
                                                @if(!is_null(auth()->user()->user_role) AND auth()->user()->user_role != 'pending') <h5 class="up_role_txt">{{ __(auth()->user()->user_role)}}</h5> @endif
                                                
                                                <span class="cat_title_txt">Employee ID</span>
                                                <span class="up_info_txt"><i class="nc-icon nc-badge"></i> {{ auth()->user()->user_sdca_id}}</span>

                                                @if(!is_null($user_info->uEmp_job_desc)) <span class="up_info_txt mb-0">{{$user_info->uEmp_job_desc}}</span> @endif
                                                @if(!is_null($user_info->uEmp_dept)) <span class="cat_title_txt mb-3">{{$user_info->uEmp_dept}}</span> @endif

                                                @if(!is_null($user_info->uEmp_phnum)) 
                                                <span class="cat_title_txt">Contact Number</span>
                                                <span class="up_info_txt"><i class="nc-icon nc-mobile"></i> {{ $user_info->uEmp_phnum}}</span> 
                                                @endif
                                                
                                                @if(!is_null(auth()->user()->email)) 
                                                <span class="cat_title_txt">Email Address</span>
                                                <span class="up_info_txt"><i class="nc-icon nc-email-85"></i> {{ auth()->user()->email}}</span> 
                                                @endif

                                                <span class="cat_title_txt">Account Status</span>
                                                @if(auth()->user()->user_status != 'active') 
                                                {{-- <span class="up_info_txt nactive_stat"><i class="nc-icon nc-circle-10"></i> {{ auth()->user()->user_status}}</span>  --}}
                                                <span class="up_info_txt nactive_stat"><i class="nc-icon nc-circle-10"></i> For activation</span> 
                                                @else
                                                <span class="up_info_txt active_stat"><i class="nc-icon nc-circle-10"></i> {{ auth()->user()->user_status}}</span> 
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- EMPLOYEE EDIT PROFILE --}}
                                <div class="tab-pane fade" id="emp_pills_edit_profile_{{auth()->user()->id}}" role="tabpanel" aria-labelledby="emp_pills_edit_profile_tab_{{auth()->user()->id}}">
                                    <div class="card card_gbr shadow">
                                        <div class="card-body p-0">
                                            <div class="card-header cb_p15x25">
                                                <span class="sec_card_body_title">Edit Profile</span>
                                                <span class="sec_card_body_subtitle">Click the <span class="font-weight-bold">'Save Changes'</span> button to save the changes you've made and this will update your profile.</span>
                                            </div>
                                            <form id="form_empUpdateProfile" class="form" method="POST" action="{{route('profile.update_emp_user_profile')}}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="cb_px25 cb_pb15">
                                                    <div class="row d-flex justify-content-center">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 align-items-center">
                                                            <div class="up_img_div text-center">
                                                                <img class="up_user_image emp_imgUpld_targetImg shadow border-gray" src="{{asset('storage/svms/user_images/'.auth()->user()->user_image)}}" alt="{{auth()->user()->user_fname }} {{ auth()->user()->user_lname}}'s profile image'">
                                                            </div>
                                                            <div class="user_image_upload_input_div emp_imgUpload">
                                                                <i class="nc-icon nc-image emp_imgUpld_TrgtBtn"></i>
                                                                <input name="upd_emp_user_image" class="file_upload_input emp_img_imgUpld_fileInpt" type="file" accept="image/*"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label for="upd_emp_email">Email Address</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-email-85" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_emp_email" name="upd_emp_email" type="text" class="form-control" @if(auth()->user()->email != 'null') value="{{auth()->user()->email}}" @else placeholder="Type Email Address" @endif required>
                                                    </div>
                                                    <label for="upd_emp_id">Employee ID</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_emp_id" name="upd_emp_id" type="number" min="0" oninput="validity.valid||(value='');" class="form-control" @if(auth()->user()->user_sdca_id != 'null') value="{{auth()->user()->user_sdca_id}}" @else placeholder="Type Employee ID" @endif required>
                                                    </div>
                                                    <label for="upd_emp_lname">Last Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-single-02"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_emp_lname" name="upd_emp_lname" type="text" class="form-control" @if(auth()->user()->user_lname != 'null') value="{{auth()->user()->user_lname}}" @else placeholder="Type Last Name" @endif required>
                                                    </div>
                                                    <label for="upd_emp_fname">First Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-single-02"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_emp_fname" name="upd_emp_fname" type="text" class="form-control" @if(auth()->user()->user_fname != 'null') value="{{auth()->user()->user_fname}}" @else placeholder="Type First Name" @endif required>
                                                    </div>
                                                    <label for="upd_emp_fname">Gender</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-single-02"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_emp_gender" list="updateGenderOptions" pattern="Male|Female" name="upd_emp_gender" type="text" class="form-control" @if(auth()->user()->user_gender != 'null') value="{{ucfirst(auth()->user()->user_gender)}}" @else placeholder="Select Gender" @endif required>
                                                        <datalist id="updateGenderOptions">
                                                            <option value="Male">
                                                            <option value="Female">
                                                        </datalist>
                                                    </div>
                                                    <label for="upd_emp_jobdesc">Job Description</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-briefcase-24" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_emp_jobdesc" name="upd_emp_jobdesc" type="text" class="form-control" @if($user_info->uEmp_job_desc != 'null') value="{{$user_info->uEmp_job_desc}}" @else placeholder="Type Job Position" @endif required>
                                                    </div>
                                                    <label for="upd_emp_dept">Department</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-bank" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input id="upd_emp_dept" name="upd_emp_dept" type="text" class="form-control" @if($user_info->uEmp_dept != 'null') value="{{$user_info->uEmp_dept}}" @else placeholder="Type Department" @endif required>
                                                    </div>
                                                    <label for="upd_emp_phnum">Phone NUmber</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-mobile" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input name="upd_emp_phnum" type="number" pattern="[0-9]{11}" min="0" oninput="validity.valid||(value='');" class="form-control" @if($user_info->uEmp_phnum != 'null') value="{{$user_info->uEmp_phnum}}" @else placeholder="Type Contact Number" @endif required>
                                                    </div>
                                                    <div class="d-flex justify-content-center">
                                                        <input type="hidden" name="selected_user_id" value="{{auth()->user()->id}}"/>
                                                        <button type="submit" id="update_empInfoBtn" class="btn btn_svms_blue btn-round btn_show_icon" disabled>{{ __('Save Changes') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card card_gbr shadow">
                                        <div class="card-body p-0">
                                            <div class="card-header cb_p15x25">
                                                <span class="sec_card_body_title">Change Password</span>
                                                <span class="sec_card_body_subtitle">Verify your old password and enter a new password to change your password.</span>
                                            </div>
                                            <form class="form" method="POST" action="#">
                                                <div class="cb_px25 cb_pb15">
                                                    <label for="cng_old_password">Old Password</label>
                                                    <div class="input-group paswrd_inpt_fld">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-circle-10" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input type="password" id="cng_old_password" name="cng_old_password" class="form-control" placeholder="Type your old Password" required>
                                                        <i class="fa fa-eye" id="toggleEmpOldPassword"></i>
                                                    </div>
                                                    <label for="cng_new_password">New Password</label>
                                                    <div class="input-group paswrd_inpt_fld">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-key-25" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input type="password" id="cng_new_password" name="cng_new_password" class="form-control" placeholder="Type your new password" required>
                                                        <i class="fa fa-eye" id="toggleEmpNewPassword"></i>
                                                    </div>
                                                    <label for="cng_confirm_new_password">Confirm New Password</label>
                                                    <div class="input-group paswrd_inpt_fld">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="nc-icon nc-lock-circle-open" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input type="password" id="cng_confirm_new_password" name="cng_confirm_new_password" class="form-control" placeholder="Just in case you mistyped" required>
                                                        <i class="fa fa-eye" id="toggleEmpConfirmNewPassword"></i>
                                                    </div>
                                                    <div class="d-flex justify-content-center">
                                                        <button type="submit" class="btn btn_svms_blue btn-round btn_show_icon" disabled>{{ __('Update My Password') }}<i class="nc-icon nc-key-25 btn_icon_show_right" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
                {{-- <div class="card card_gbr card_ofh shadow-none">
                    <div class="card-body card_body_bg_gray cb_p15x25">
                        <div class="card-header p-0">
                            <span class="card_body_title">Account Information</span>
                            <span class="card_body_subtitle">View and update your profile.</span>
                        </div>
                        
                    </div>
                </div> --}}
            </div>

            <div class="col-lg-8 col-md-7 col-sm-12">
                <div class="accordion" id="activityLogsCollapse">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="headingOne">
                            <button id="actLogs_collapseBtnToggle" class="btn btn-link btn-block custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <div>
                                    <span class="card_body_title">Activity Log Histories</span>
                                    <span class="card_body_subtitle">Below are the list of your transactions with the system.</span>
                                </div>
                                {{-- <div id="actLogs_collapseIconToggle">
                                    <i class="nc-icon nc-minimal-down custom_btn_collapse_icon"></i>
                                </div> --}}
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="collapseOne" class="collapse show p-0" aria-labelledby="headingOne" data-parent="#activityLogsCollapse">
                            <div class="card-body cb_t0b15x25">
                                @if(count($user_activities) > 0)
                                @php
                                    $transactions_count = count($user_activities);
                                @endphp
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <table class="table table-hover cust_table shadow">
                                            <thead class="thead_svms_blue">
                                                <tr>
                                                    <th class="p12 w35prcnt">Date</th>
                                                    <th>Transaction Details</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tbody_svms_white">
                                            @foreach($user_activities->sortByDesc('created_at') as $user_activity)
                                                <tr>
                                                    <td class="p12 w35prcnt">{{date('F d, Y', strtotime($user_activity->created_at)) }} - {{date('D', strtotime($user_activity->created_at)) }} at {{ date('g:i A', strtotime($user_activity->created_at))}}</td>
                                                    <td>
                                                        @if($user_activity->act_type == 'login')
                                                            You logged in to the system.
                                                        @elseif($user_activity->act_type == 'logout')
                                                            You logged out from the system.
                                                        @elseif($user_activity->act_type == 'register')
                                                            You registered as @if(auth()->user()->user_type == 'student') a Student User. @else an Employee User. @endif
                                                        @elseif($user_activity->act_type == 'update account')
                                                            You Updated your Account Information.
                                                        @else
                                                            {{$user_activity->act_details}}.
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="cust_info_txtwicon"><i class="fa fa-history" aria-hidden="true"></i> You made {{$transactions_count}} @if($transactions_count > 1) transactions @else transaction @endif in the system.</span>
                                    </div>
                                </div>
                                @else
                                
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- USER's ACTIVITY LOGS --}}
        </div>
    </div>
@endsection

@push('scripts')
{{-- avtive tab on page refresh --}}
    <script>
        $(document).ready(function() {
            if (location.hash) {
                $("a[href='" + location.hash + "']").tab("show");
            }
            $(document.body).on("click", "a[data-toggle='pill']", function(event) {
                location.hash = this.getAttribute("href");
            });
        });
        $(window).on("popstate", function() {
            var anchor = location.hash || $("a[data-toggle='pill']").first().attr("href");
            $("a[href='" + anchor + "']").tab("show");
        });
    </script>
{{-- avtive tab on page refresh end --}}

{{-- user profile image upload --}}
    {{-- employee profile image --}}
    <script>
        $(document).ready(function() {
            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('.emp_imgUpld_targetImg').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $(".emp_img_imgUpld_fileInpt").on('change', function(){
                readURL(this);
            });
            $(".emp_imgUpld_TrgtBtn").on('click', function() {
                $(".emp_img_imgUpld_fileInpt").click();
            });
        });
    </script>
    {{-- student profile image --}}
    <script>
        $(document).ready(function() {
            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('.stud_imgUpld_targetImg').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $(".stud_img_imgUpld_fileInpt").on('change', function(){
                readURL(this);
            });
            $(".stud_imgUpld_TrgtBtn").on('click', function() {
                $(".stud_img_imgUpld_fileInpt").click();
            });
        });
    </script>
{{-- user profile image upload end --}}

{{-- display datalist options based on previous selected option --}}
    {{-- selected school --}}
    <script>
        $(document).ready(function() {
            $("#upd_stud_school").on("change paste keyup", function() {
                var selectedSchool = $(this).val();
                if(selectedSchool != ''){
                    if(selectedSchool == 'SASE'){
                        $("#updateStudProgramOptions").html('<option value="BS Psychology"> \
                                                    <option value="BS Education"> \
                                                    <option value="BA Communication">');
                    }else if(selectedSchool == 'SBCS'){
                        $("#updateStudProgramOptions").html('<option value="BSBA"> \
                                                    <option value="BSA"> \
                                                    <option value="BSIT"> \
                                                    <option value="BMA">');
                    }else if(selectedSchool == 'SIHTM'){
                        $("#updateStudProgramOptions").html('<option value="BSHM"> \
                                                    <option value="BSTM">');
                    }else if(selectedSchool == 'SHSP'){
                        $("#updateStudProgramOptions").html('<option value="BS Biology"> \
                                                    <option value="BS Pharmacy"> \
                                                    <option value="BS Radiologic Technology"> \
                                                    <option value="BS Physical Therapy"> \
                                                    <option value="BS Medical Technology"> \
                                                    <option value="BS Nursing">');
                    }else{
                        $("#updateStudProgramOptions").html('<option value="Select School First"></option>');
                    }
                }else{
                    $("#updateStudProgramOptions").html('<option value="Select School First"></option>');
                }
            });
        });
    </script>
    {{-- selected program --}}
    <script>
        $(document).ready(function() {
            $("#upd_stud_program").on("change paste keyup", function() {
                var selectedProgram = $(this).val();
                if(selectedProgram != ''){
                    if(selectedProgram == 'BSA' || selectedProgram == 'BS Physical Therapy'){
                        $("#updateStudYearlvlOptions").html('<option value="FIRST YEAR"> \
                                                <option value="SECOND YEAR"> \
                                                <option value="THIRD YEAR"> \
                                                <option value="FOURTH YEAR"> \
                                                <option value="FIFTH YEAR">');
                    }else{
                        $("#updateStudYearlvlOptions").html('<option value="FIRST YEAR"> \
                                                <option value="SECOND YEAR"> \
                                                <option value="THIRD YEAR"> \
                                                <option value="FOURTH YEAR">');
                    }
                }else{
                    $("#updateStudYearlvlOptions").html('<option value="Select Program First"></option>');
                }
            });
        });
    </script>
{{-- display datalist options based on previous selected option --}}

{{-- disable update button on employee profile update if any of inputs have chagned --}}
    <script>
        $(window).on('load', function(e){
            $('#form_empUpdateProfile').each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#update_empInfoBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                /* Check if input with type files has changed */
                var changedFiles = $( ":file" ).filter(function( index ) {
                    return this.value != this.defaultValue;
                }).length;
                if ( changedFiles > 0) {
                    $(this).find('#update_empInfoBtn, input[type="file"]')
                        .prop('disabled', false);
                }
            }).find('#update_empInfoBtn').prop('disabled', true);
        });
    </script>
{{-- disable update button on employee profile update if any of inputs have chagned end --}}
{{-- disable update button on student profile update if any of inputs have chagned --}}
    <script>
        $(window).on('load', function(e){
            $('#form_studUpdateProfile').each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#update_studInfoBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                /* Check if input with type files has changed */
                var changedFiles = $( ":file" ).filter(function( index ) {
                    return this.value != this.defaultValue;
                }).length;
                if ( changedFiles > 0) {
                    $(this).find('#update_studInfoBtn, input[type="file"]')
                        .prop('disabled', false);
                }
            }).find('#update_studInfoBtn').prop('disabled', true);
        });
    </script>
{{-- disable update button on student profile update if any of inputs have chagned end --}}

{{-- paswword toggle visibility --}}
    {{-- student user password update --}}
    <script>
        const toggleStudOldPassword = document.querySelector('#toggleStudOldPassword');
        const cng_studOldPassword = document.querySelector('#cng_studOldPassword');
        toggleStudOldPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = cng_studOldPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            cng_studOldPassword.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    <script>
        const toggleStudNewPassword = document.querySelector('#toggleStudNewPassword');
        const cng_studNewPassword = document.querySelector('#cng_studNewPassword');
        toggleStudNewPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = cng_studNewPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            cng_studNewPassword.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    <script>
        const toggleStudConfirmNewPassword = document.querySelector('#toggleStudConfirmNewPassword');
        const cng_confirmStudNewPassword = document.querySelector('#cng_confirmStudNewPassword');
        toggleStudConfirmNewPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = cng_confirmStudNewPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            cng_confirmStudNewPassword.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    {{-- employee user password update --}}
    <script>
        const toggleEmpOldPassword = document.querySelector('#toggleEmpOldPassword');
        const cng_old_password = document.querySelector('#cng_old_password');
        toggleEmpOldPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = cng_old_password.getAttribute('type') === 'password' ? 'text' : 'password';
            cng_old_password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    <script>
        const toggleEmpNewPassword = document.querySelector('#toggleEmpNewPassword');
        const cng_new_password = document.querySelector('#cng_new_password');
        toggleEmpNewPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = cng_new_password.getAttribute('type') === 'password' ? 'text' : 'password';
            cng_new_password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    <script>
        const toggleEmpConfirmNewPassword = document.querySelector('#toggleEmpConfirmNewPassword');
        const cng_confirm_new_password = document.querySelector('#cng_confirm_new_password');
        toggleEmpConfirmNewPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = cng_confirm_new_password.getAttribute('type') === 'password' ? 'text' : 'password';
            cng_confirm_new_password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
{{-- password toggle visibility end --}}

{{-- change icon on button click --}}
    {{-- for profile collapse icon --}}
    <script>
        $('#profile_collapseBtnToggle').click(function() {
            // $('#actLogs_collapseIconToggle').toggle('1000');
            $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
        });
    </script>
    {{-- for activity log histories collapse icon --}}
    <script>
        $('#actLogs_collapseBtnToggle').click(function() {
            // $('#actLogs_collapseIconToggle').toggle('1000');
            $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
        });
    </script>
{{-- change icon on button click end --}}
@endpush