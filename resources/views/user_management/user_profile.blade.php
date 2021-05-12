@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'system_users'
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
                <a href="{{ route('user_management.overview_users_management', 'overview_users_management') }}" class="directory_link">Users Management</a> <span class="directory_divider"> / </span> <a href="{{ route('user_management.system_users', 'system_users') }}" class="directory_link">System Users </a> <span class="directory_divider"> / </span> <a href="{{ route('user_management.user_profile', 'user_profile') }}" class="directory_active_link">User Profile <span class="directory_divider"> ~ </span> {{ $user_data->user_fname }} {{ $user_data->user_lname }}</a>
            </div>
        </div>

        {{-- data customizations --}}
        @php
        // single quote
        $sq = "'";

        // to lower values
        $toLower_userType = Str::lower($user_data->user_type);
        $toLower_userStatus = Str::lower($user_data->user_status);
        $toLower_userRoleStatus = Str::lower($user_data->user_role_status);
        $toLower_userGender = Str::lower($user_data->user_gender);
        
        // his/her text
            if($toLower_userGender === 'male'){
                $gender_txt = 'his';
            }else{
                $gender_txt = 'her';
            }
        // his/her text end

        // filter for user types 
        if($toLower_userType === 'student'){
            $user_stud_info  = App\Models\Userstudents::where('uStud_num', $user_data->user_sdca_id)->first();
            $custom_nav_pill = 'custom_nav_link_green';
            if($toLower_userRoleStatus === 'active'){
                if($toLower_userStatus === 'active'){
                    $image_filter   = 'up_stud_user_image';
                    $user_alt_image = 'student_user_image';
                }else{
                    if($toLower_userStatus === 'deactivated' OR $toLower_userStatus === 'deleted'){
                        $image_filter   = 'up_red_user_image';
                        $user_alt_image = 'no_student_image';
                    }else{
                        $image_filter   = 'up_gray_user_image';
                        $user_alt_image = 'disabled_user_image';
                    }
                }
            }else{
                if($toLower_userRoleStatus === 'deactivated'  OR $toLower_userRoleStatus === 'deleted'){
                    $image_filter   = 'up_red_user_image';
                    $user_alt_image = 'no_student_image';
                }else{
                    $image_filter   = 'up_gray_user_image';
                    $user_alt_image = 'disabled_user_image';
                }   
            }
        }else if($toLower_userType === 'employee'){
            $user_emp_info   = App\Models\Useremployees::where('uEmp_id', $user_data->user_sdca_id)->first();
            $custom_nav_pill = 'custom_nav_link_blue';
            if($toLower_userRoleStatus === 'active'){
                if($toLower_userStatus === 'active'){
                    $image_filter   = 'up_user_image';
                    $user_alt_image = 'employee_user_image';
                }else{
                    if($toLower_userStatus === 'deactivated' OR $toLower_userStatus === 'deleted'){
                        $image_filter   = 'up_red_user_image';
                        $user_alt_image = 'no_student_image';
                    }else{
                        $image_filter   = 'up_gray_user_image';
                        $user_alt_image = 'disabled_user_image';
                    }
                }
            }else{
                if($toLower_userRoleStatus === 'deactivated' OR $toLower_userRoleStatus === 'deleted'){
                    $image_filter   = 'up_red_user_image';
                    $user_alt_image = 'no_student_image';
                }else{
                    $image_filter   = 'up_gray_user_image';
                    $user_alt_image = 'disabled_user_image';
                }   
            }
        }else{
            $custom_nav_pill = 'custom_nav_link_gray';
        }
        // filter for user types end 

        // user's image
        if(!is_null($user_data->user_image) OR !empty($user_data->user_image)){
            $user_image_src = asset('storage/svms/user_images/'.$user_data->user_image);
            $user_image_alt = $user_data->user_fname . ' ' . $user_data->user_lname.''.$sq.'s profile image';
        }else{
            if($toLower_userStatus == 'active'){
                if($user_data->user_type == 'employee'){
                    $user_image_jpg = 'employee_user_image.jpg';
                }elseif($user_data->user_type == 'student'){
                    $user_image_jpg = 'student_user_image.jpg';
                }else{
                    $user_image_jpg = 'disabled_user_image.jpg';
                }
                $user_image_src = asset('storage/svms/user_images/'.$user_image_jpg);
            }else{
                $user_image_src = asset('storage/svms/user_images/no_student_image.jpg');
            }
            $user_image_alt = 'default user'.$sq.'s profile image';
        }
        @endphp

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">User's Profile</span>
                            <span class="page_intro_subtitle">This page allows you to view and manage <span class="font-weight-bold"> {{ $user_data->user_fname }} {{ $user_data->user_lname}}</span>'s Account Information. You can Activate/Deactivate, Edit, and/or Delete {{ $gender_txt }} account, manage {{ $gender_txt }} assigned system role, view {{ $gender_txt }} logs from the system and generate reports.</span>
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/my_profile_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        {{-- user profile card --}}
            <div class="col-lg-4 col-md-5 col-sm-12">
                <div class="accordion" id="userProfileCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="userProfileCollapseHeading">
                            <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#userProfileCollapseDiv" aria-expanded="true" aria-controls="userProfileCollapseDiv">
                                <div>
                                    <span class="card_body_title">User's Information</span>
                                    <span class="card_body_subtitle">View and update your profile.</span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="userProfileCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="userProfileCollapseHeading" data-parent="#userProfileCollapseParent">
                            <ul class="nav nav-pills custom_nav_pills mt-0 mb-3 d-flex justify-content-center" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ $custom_nav_pill }} active" id="pills_userProfile_preview_tab_{{$user_data->id}}" data-toggle="pill" href="#userProfile_preview_{{$user_data->id}}" role="tab" aria-controls="userProfile_preview_{{$user_data->id}}" aria-selected="true">Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $custom_nav_pill }}" id="pills_edit_userProfile_tab_{{$user_data->id}}" data-toggle="pill" href="#userProfile_edit_{{$user_data->id}}" role="tab" aria-controls="userProfile_edit_{{$user_data->id}}" aria-selected="false">Edit Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $custom_nav_pill }}" id="pills_edit_userPassword_tab_{{$user_data->id}}" data-toggle="pill" href="#userPassword_edit_{{$user_data->id}}" role="tab" aria-controls="userPassword_edit_{{$user_data->id}}" aria-selected="false">Change Password</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="userProfilePills-tabContent">
                            {{-- user information --}}
                                <div class="tab-pane fade show active" id="userProfile_preview_{{$user_data->id}}" role="tabpanel" aria-labelledby="pills_userProfile_preview_tab_{{$user_data->id}}">
                                    <div class="card card_gbr shadow card-user">
                                        <div class="image">
                                            <img src="{{ asset('paper/img/damir-bosnjak.jpg') }}" alt="...">
                                        </div>
                                        <div class="card-body">
                                            <div class="author">
                                                <a href="#" class="up_img_div">
                                                    <img class="{{ $image_filter }} shadow" src="{{$user_image_src}}" alt="{{$user_image_alt}}">
                                                </a>
                                                <span class="up_fullname_txt text_svms_blue">{{$user_data->user_fname }}  {{$user_data->user_lname}}</span>
                                                @if(!is_null($user_data->user_role) OR $user_data->user_role !== 'pending')
                                                    <h5 class="up_role_txt">{{ __($user_data->user_role)}}</h5>
                                                @else
                                                    <h5 class="up_role_txt font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> Role is Pending</h5>
                                                @endif
                                                {{-- if user type = student --}}
                                                @if($user_data->user_type === 'student')
                                                    {{-- student number --}}
                                                    <span class="cat_title_txt">Student Number</span>
                                                    <span class="up_info_txt"><i class="nc-icon nc-badge"></i> {{ $user_data->user_sdca_id}}</span>
                                                    {{-- student school & program --}}
                                                    @if(!is_null($user_stud_info->uStud_program))
                                                        <span class="up_info_txt mb-0">{{$user_stud_info->uStud_program}}-{{$user_stud_info->uStud_section}}</span>
                                                        @if(!is_null($user_stud_info->uStud_school))
                                                        <span class="cat_title_txt mb-3">{{$user_stud_info->uStud_school}}</span>
                                                        @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> school department unknown</span>
                                                        @endif
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> program unknown</span>
                                                        @if(!is_null($user_stud_info->uStud_school))
                                                        <span class="cat_title_txt mb-3">{{$user_stud_info->uStud_school}}</span>
                                                        @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> school department unknown</span>
                                                        @endif
                                                    @endif
                                                    {{-- student phone number --}}
                                                    <span class="cat_title_txt">Contact Number</span>
                                                    @if(!is_null($user_stud_info->uStud_phnum))
                                                        <span class="up_info_txt"><i class="nc-icon nc-mobile"></i> {{ $user_stud_info->uStud_phnum}}</span> 
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no contact number</span>
                                                    @endif
                                                    {{-- email --}}
                                                    <span class="cat_title_txt">Email Address</span>
                                                    @if(!is_null($user_data->email))
                                                        <span class="up_info_txt"><i class="nc-icon nc-email-85"></i> {{ $user_data->email}}</span> 
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no email address</span>
                                                    @endif
                                                    {{-- gender --}}
                                                    <span class="cat_title_txt">Gender</span>
                                                    @if(!is_null($user_data->user_gender))
                                                        @if($toLower_userGender === 'male')
                                                            <span class="up_info_txt"><i class="fa fa-male"></i> {{ ucwords($user_data->user_gender) }}</span> 
                                                        @elseif($toLower_userGender === 'female')
                                                            <span class="up_info_txt"><i class="fa fa-female"></i> {{ ucwords($user_data->user_gender) }}</span> 
                                                        @else
                                                            <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> gender unknown</span>
                                                        @endif
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no gender</span>
                                                    @endif
                                                {{-- if user type = employee --}}
                                                @elseif($user_data->user_type === 'employee')
                                                    {{-- employee ID --}}
                                                    <span class="cat_title_txt">Employee ID</span>
                                                    <span class="up_info_txt"><i class="nc-icon nc-badge"></i> {{ $user_data->user_sdca_id}}</span>
                                                    {{-- employee department & job description --}}
                                                    @if(!is_null($user_emp_info->uEmp_job_desc))
                                                        <span class="up_info_txt mb-0">{{$user_emp_info->uEmp_job_desc}}</span>
                                                        @if(!is_null($user_emp_info->uEmp_dept))
                                                        <span class="cat_title_txt mb-3">{{$user_emp_info->uEmp_dept}}</span>
                                                        @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> department unknown</span>
                                                        @endif
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> job description unknown</span>
                                                        @if(!is_null($user_emp_info->uEmp_dept))
                                                        <span class="cat_title_txt mb-3">{{$user_emp_info->uEmp_dept}}</span>
                                                        @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> department unknown</span>
                                                        @endif
                                                    @endif
                                                    {{-- student phone number --}}
                                                    <span class="cat_title_txt">Contact Number</span>
                                                    @if(!is_null($user_emp_info->uEmp_phnum))
                                                        <span class="up_info_txt"><i class="nc-icon nc-mobile"></i> {{ $user_emp_info->uEmp_phnum}}</span> 
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no contact number</span>
                                                    @endif
                                                    {{-- email --}}
                                                    <span class="cat_title_txt">Email Address</span>
                                                    @if(!is_null($user_data->email))
                                                        <span class="up_info_txt"><i class="nc-icon nc-email-85"></i> {{ $user_data->email}}</span> 
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no email address</span>
                                                    @endif
                                                    {{-- gender --}}
                                                    <span class="cat_title_txt">Gender</span>
                                                    @if(!is_null($user_data->user_gender))
                                                        @if($toLower_userGender === 'male')
                                                            <span class="up_info_txt mb-0"><i class="fa fa-male"></i> {{ ucwords($user_data->user_gender) }}</span> 
                                                        @elseif($toLower_userGender === 'female')
                                                            <span class="up_info_txt mb-0"><i class="fa fa-female"></i> {{ ucwords($user_data->user_gender) }}</span> 
                                                        @else
                                                            <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> gender unknown</span>
                                                        @endif
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no gender</span>
                                                    @endif
                                                {{-- unknown user type --}}
                                                @else
                                                    <span class="cat_title_txt">User Type</span>
                                                    <span class="up_info_txt font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> unknown </span>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- account status --}}
                                        @php
                                        // values for account status
                                            if($toLower_userRoleStatus === 'active'){
                                                if($toLower_userStatus === 'active'){
                                                    // deactivate account
                                                    $btn_class  = "btn-success";
                                                    $btn_label  = "Account is Activated";
                                                    $btn_icon   = "fa fa-toggle-on";
                                                    $btn_action = 'onclick=deactivateUserAccount(this.id)';
                                                    $question   = 'Deactivate';
                                                }elseif($toLower_userStatus === 'deactivated'){
                                                    // activate account
                                                    $btn_class  = "btn_svms_red";
                                                    $btn_label  = "Account is Deactivated";
                                                    $btn_icon   = "fa fa-toggle-off";
                                                    $btn_action = 'onclick=activateUserAccount(this.id)';
                                                    $question   = 'Activate';
                                                }elseif($toLower_userStatus === 'pending'){
                                                    $btn_class  = "btn-secondary";
                                                    $btn_label  = "Account is Pending";
                                                    $btn_icon   = "fa fa-spinner";
                                                    $btn_action = 'onclick=activateUserAccount(this.id)';
                                                    $question   = 'Activate';
                                                }elseif($toLower_userStatus === 'deleted'){
                                                    // user account is deleted - recover option
                                                    $btn_class  = "btn-secondary";
                                                    $btn_label  = "Account is Pending";
                                                    $btn_icon   = "fa fa-spinner";
                                                    $btn_action = 'onclick=activateUserAccount(this.id)';
                                                    $question   = 'Activate';
                                                }else{
                                                    // just activate
                                                    $btn_class  = "btn_svms_red";
                                                    $btn_label  = "Account is Pending";
                                                    $btn_icon   = "fa fa-toggle-off";
                                                    $btn_action = 'onclick=activateUserAccount(this.id)';
                                                    $question   = 'Activate';
                                                }
                                            }elseif($toLower_userRoleStatus === 'deactivated'){
                                                // activate role first
                                                $btn_class  = "btn_svms_red";
                                                $btn_label  = "Account is Deactivated";
                                                $btn_icon   = "fa fa-toggle-off";
                                                $btn_action = 'onclick=activateUserAccount(this.id)';
                                                $question   = 'Activate';
                                            }elseif($toLower_userRoleStatus === 'pending'){
                                                // manage role first
                                                $btn_class  = "btn-secondary";
                                                $btn_label  = "Account is Pending";
                                                $btn_icon   = "fa fa-spinner";
                                                $btn_action = 'onclick=manageRoleFirst(this.id)';
                                                $question   = 'Activate';
                                            }elseif($toLower_userRoleStatus === 'deleted'){
                                                // role is deleted - assign new role
                                                $btn_class  = "btn-secondary";
                                                $btn_label  = "Account is Pending";
                                                $btn_icon   = "fa fa-spinner";
                                                $btn_action = 'onclick=manageRoleFirst(this.id)';
                                                $question   = 'Activate';
                                            }else{
                                                // manage role first
                                                $btn_class  = "btn-secondary";
                                                $btn_label  = "Account is Pending";
                                                $btn_icon   = "fa fa-spinner";
                                                $btn_action = 'onclick=manageRoleFirst(this.id)';
                                                $question   = 'Activate';
                                            }
                                        // values for account status end
                                        @endphp     
                                        <div class="row d-flex justify-content-center mt-2">
                                            <div class="col-lg-8 col-md-10 col-sm-11 p-0 d-flex justify-content-center">
                                                <div class="btn-group cust_btn_group" role="group" aria-label="User's Account Status / Action">
                                                    <button type="button" class="btn {{ $btn_class }} btn_group_label m-0">{{ $btn_label }}</button>
                                                    <button type="button" id="{{$user_data->id}}" {{ $btn_action }} class="btn {{ $btn_class }} btn_group_icon m-0" data-toggle="tooltip" data-placement="top" title="{{ $question }} {{ $user_data->user_fname }} {{ $user_data->user_lname}}'s Account?"><i class="{{ $btn_icon }}"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center mt-2 mb-4">
                                            <div class="col-lg-8 col-md-10 col-sm-11 p-0 d-flex justify-content-center">
                                                <div class="btn-group cust_btn_group" role="group" aria-label="User's Account Status / Action">
                                                    <button type="button" class="btn {{ $btn_class }} btn_group_label m-0">{{ $user_data->user_role }}</button>
                                                    <button type="button" id="{{$user_data->id}}" onclick="changeUserRole(this.id)" class="btn {{ $btn_class }} btn_group_icon m-0" data-toggle="tooltip" data-placement="top" title="Change User's System Role?"><i class="fa fa-pencil"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- account status end --}}
                                    </div>
                                    {{-- account registered date and responsible user --}}
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            {{-- account date created and registered by... --}}
                                            @if(!is_null($user_data->created_at))
                                                <span class="cust_info_txtwicon"><i class="fa fa-pencil-square-o mr-1" aria-hidden="true"></i> Account created in&nbsp; <span class="font-weight-bold"> {{ date('F j, Y - g:i A', strtotime($user_data->created_at))}} </span></span>
                                            @else
                                                <span class="cust_info_txtwicon font-italic text_svms_red"><i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> Account date created unknown </span>
                                            @endif
                                            @if(!is_null($user_data->registered_by))
                                                @php
                                                    $reg_by_info = App\Models\User::select('id', 'user_role', 'user_lname', 'user_fname')->where('id', $user_data->registered_by)->first();
                                                @endphp
                                                @if($reg_by_info)
                                                    <span class="cust_info_txtwicon"><i class="nc-icon nc-circle-10 mr-1" aria-hidden="true"></i> Registered by&nbsp; 
                                                        <span class="font-weight-bold"> 
                                                            @if(!is_null($reg_by_info->user_fname)) {{ $reg_by_info->user_fname }} @endif
                                                            @if(!is_null($reg_by_info->user_lname)) {{ $reg_by_info->user_lname }} @endif
                                                        </span>
                                                        &nbsp;~&nbsp;
                                                        @if(!is_null($reg_by_info->user_role))
                                                            <span class="font-italic"> System {{ ucwords($reg_by_info->user_role) }} </span>
                                                        @else
                                                            <span class="font-italic text_svms_red"> <i class="fa fa-exclamation-circle"></i> {{ ucwords($reg_by_info->user_role) }} </span>
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="cust_info_txtwicon font-italic text_svms_red"><i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> Registered by an unknown user </span>
                                                @endif
                                            @else
                                                <span class="cust_info_txtwicon font-italic text_svms_red"><i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> Account date created unknown </span>
                                            @endif
                                            {{-- account date created and registered by end --}}
                                        </div>
                                    </div>
                                    {{-- account registered date and responsible user end --}}
                                </div>
                            {{-- user information end --}}
                            {{-- edit user informatin form --}}
                                <div class="tab-pane fade" id="userProfile_edit_{{$user_data->id}}" role="tabpanel" aria-labelledby="pills_edit_userProfile_tab_{{$user_data->id}}">
                                    @if($user_data->user_type === 'student')
                                        <div class="card card_gbr shadow">
                                            <div class="card-body p-0">
                                                <div class="card-header cb_p15x25">
                                                    <span class="sec_card_body_title">Edit User's Profile</span>
                                                    <span class="sec_card_body_subtitle">Click the <span class="font-weight-bold">'Save Changes'</span> button to update {{ $user_data->user_fname }} {{ $user_data->user_lname}}'s profile.</span>
                                                </div>
                                                <form id="form_studUserUpdateProfile" class="form" method="POST" action="{{route('user_management.update_stud_user_profile')}}" enctype="multipart/form-data" onsubmit="update_studUserInfoBtn.disabled = true; return true;">
                                                    @csrf
                                                    <div class="cb_px25 cb_pb15">
                                                        <div class="row d-flex justify-content-center">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 align-items-center">
                                                                <div class="up_img_div text-center">
                                                                    {{-- <img class="up_stud_user_image stud_imgUpld_targetImg shadow border-gray" src="{{asset('storage/svms/user_images/'.$user_data->user_image)}}" alt="{{$user_data->user_fname }} {{ $user_data->user_lname}}'s profile image'"> --}}
                                                                    <img class="{{ $image_filter }} up_stud_user_image stud_imgUpld_targetImg shadow" src="{{$user_image_src}}" alt="{{$user_image_alt}}">
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
                                                            <input id="upd_stud_email" name="upd_stud_email" type="text" class="form-control" @if($user_data->email != 'null') value="{{$user_data->email}}" @else placeholder="Type Email Address" @endif required>
                                                            <span id="studEmailAvail_notice" class="d-none text-right">

                                                            </span>
                                                        </div>
                                                        <label for="upd_stud_num">Student Number</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                                </span>
                                                            </div>
                                                            <input id="upd_stud_num" name="upd_stud_num" type="number" min="0" oninput="validity.valid||(value='');" class="form-control" @if($user_data->user_sdca_id != 'null') value="{{$user_data->user_sdca_id}}" @else placeholder="Type Student Number" @endif required>
                                                        </div>
                                                        <label for="upd_stud_lname">Last Name</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="nc-icon nc-single-02"></i>
                                                                </span>
                                                            </div>
                                                            <input id="upd_stud_lname" name="upd_stud_lname" type="text" class="form-control" @if($user_data->user_lname != 'null') value="{{$user_data->user_lname}}" @else placeholder="Type Last Name" @endif required>
                                                        </div>
                                                        <label for="upd_stud_fname">First Name</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="nc-icon nc-single-02"></i>
                                                                </span>
                                                            </div>
                                                            <input id="upd_stud_fname" name="upd_stud_fname" type="text" class="form-control" @if($user_data->user_fname != 'null') value="{{$user_data->user_fname}}" @else placeholder="Type First Name" @endif required>
                                                        </div>
                                                        <label for="upd_stud_gender">Gender</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="nc-icon nc-single-02"></i>
                                                                </span>
                                                            </div>
                                                            <input id="upd_stud_gender" list="updateStudGenderOptions" pattern="Male|Female" name="upd_stud_gender" type="text" class="form-control" @if($user_data->user_gender != 'null') value="{{ucfirst($user_data->user_gender)}}" @else placeholder="Select Gender" @endif required>
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
                                                            <input id="upd_stud_school" list="updateStudSchoolOptions" pattern="SASE|SBCS|SIHTM|SHSP" name="upd_stud_school" type="text" class="form-control" @if($user_stud_info->uStud_school != 'null') value="{{$user_stud_info->uStud_school}}" @else placeholder="Type Your School" @endif required>
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
                                                            <input id="upd_stud_program" list="updateStudProgramOptions" pattern="BS Psychology|BS Education|BA Communication|BSBA|BSA|BSIT|BSCS|BMA|BSHM|BSTM|BS Biology|BS Pharmacy|BS Radiologic Technology|BS Physical Therapy|BS Medical Technology|BS Nursing" name="upd_stud_program" type="text" class="form-control" @if($user_stud_info->uStud_program != 'null') value="{{$user_stud_info->uStud_program}}" @else placeholder="Type Your Program" @endif required>
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
                                                            <input id="upd_stud_yearlvl" list="updateStudYearlvlOptions" pattern="FIRST YEAR|SECOND YEAR|THIRD YEAR|FOURTH YEAR|FIFTH YEAR" name="upd_stud_yearlvl" type="text" class="form-control" @if($user_stud_info->uStud_yearlvl != 'null') value="{{$user_stud_info->uStud_yearlvl}}" @else placeholder="Type Your Year Level" @endif required>
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
                                                            <input id="upd_stud_section" name="upd_stud_section" type="text" class="form-control" @if($user_stud_info->uStud_section != 'null') value="{{$user_stud_info->uStud_section}}" @else placeholder="Type Your Section" @endif required>
                                                        </div>
                                                        <label for="upd_stud_phnum">Phone NUmber</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="fa fa-mobile" aria-hidden="true"></i>
                                                                </span>
                                                            </div>
                                                            <input name="upd_stud_phnum" type="number" pattern="[0-9]{11}" min="0" oninput="validity.valid||(value='');" class="form-control" @if($user_stud_info->uStud_phnum != 'null') value="{{$user_stud_info->uStud_phnum}}" @else placeholder="Type Contact Number" @endif required>
                                                        </div>
                                                        <div class="d-flex justify-content-center">
                                                            <input type="hidden" name="selected_user_id" id="selected_user_id" value="{{$user_data->id}}"/>
                                                            <input type="hidden" name="respo_user_id" value="{{auth()->user()->id}}"/>
                                                            <input type="hidden" name="respo_user_lname" value="{{auth()->user()->user_lname}}"/>
                                                            <input type="hidden" name="respo_user_fname" value="{{auth()->user()->user_fname}}"/>
                                                            <button type="submit" id="update_studUserInfoBtn" class="btn btn-success btn-round btn_show_icon" disabled>{{ __('Save Changes') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @elseif($user_data->user_type === 'employee')
                                        <div class="card card_gbr shadow">
                                            <div class="card-body p-0">
                                                <div class="card-header cb_p15x25">
                                                    <span class="sec_card_body_title">Edit Profile</span>
                                                    <span class="sec_card_body_subtitle">Click the <span class="font-weight-bold">'Save Changes'</span> button to update {{ $user_data->user_fname }} {{ $user_data->user_lname}}'s profile.</span>
                                                </div>
                                                <form id="form_empUserUpdateProfile" class="form" method="POST" action="{{route('user_management.update_emp_user_profile')}}" enctype="multipart/form-data" onsubmit="update_empUserInfoBtn.disabled = true; return true;">
                                                    @csrf
                                                    <div class="cb_px25 cb_pb15">
                                                        <div class="row d-flex justify-content-center">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 align-items-center">
                                                                <div class="up_img_div text-center">
                                                                    {{-- <img class="up_user_image emp_imgUpld_targetImg shadow border-gray" src="{{asset('storage/svms/user_images/'.$user_data->user_image)}}" alt="{{$user_data->user_fname }} {{ $user_data->user_lname}}'s profile image'"> --}}
                                                                    <img class="{{ $image_filter }} up_user_image emp_imgUpld_targetImg shadow" src="{{$user_image_src}}" alt="{{$user_image_alt}}">
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
                                                            <input id="upd_emp_email" name="upd_emp_email" type="text" class="form-control" @if($user_data->email != 'null') value="{{$user_data->email}}" @else placeholder="Type Email Address" @endif required>
                                                            <span id="empEmailAvail_notice" class="d-none text-right">

                                                            </span>
                                                        </div>
                                                        <label for="upd_emp_id">Employee ID</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                                </span>
                                                            </div>
                                                            <input id="upd_emp_id" name="upd_emp_id" type="number" min="0" oninput="validity.valid||(value='');" class="form-control" @if($user_data->user_sdca_id != 'null') value="{{$user_data->user_sdca_id}}" @else placeholder="Type Employee ID" @endif required>
                                                        </div>
                                                        <label for="upd_emp_lname">Last Name</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="nc-icon nc-single-02"></i>
                                                                </span>
                                                            </div>
                                                            <input id="upd_emp_lname" name="upd_emp_lname" type="text" class="form-control" @if($user_data->user_lname != 'null') value="{{$user_data->user_lname}}" @else placeholder="Type Last Name" @endif required>
                                                        </div>
                                                        <label for="upd_emp_fname">First Name</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="nc-icon nc-single-02"></i>
                                                                </span>
                                                            </div>
                                                            <input id="upd_emp_fname" name="upd_emp_fname" type="text" class="form-control" @if($user_data->user_fname != 'null') value="{{$user_data->user_fname}}" @else placeholder="Type First Name" @endif required>
                                                        </div>
                                                        <label for="upd_emp_fname">Gender</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="nc-icon nc-single-02"></i>
                                                                </span>
                                                            </div>
                                                            <input id="upd_emp_gender" list="updateGenderOptions" pattern="Male|Female" name="upd_emp_gender" type="text" class="form-control" @if($user_data->user_gender != 'null') value="{{ucfirst($user_data->user_gender)}}" @else placeholder="Select Gender" @endif required>
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
                                                            <input id="upd_emp_jobdesc" name="upd_emp_jobdesc" type="text" class="form-control" @if($user_emp_info->uEmp_job_desc != 'null') value="{{$user_emp_info->uEmp_job_desc}}" @else placeholder="Type Job Position" @endif required>
                                                        </div>
                                                        <label for="upd_emp_dept">Department</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="nc-icon nc-bank" aria-hidden="true"></i>
                                                                </span>
                                                            </div>
                                                            <input id="upd_emp_dept" name="upd_emp_dept" type="text" class="form-control" @if($user_emp_info->uEmp_dept != 'null') value="{{$user_emp_info->uEmp_dept}}" @else placeholder="Type Department" @endif required>
                                                        </div>
                                                        <label for="upd_emp_phnum">Phone NUmber</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="fa fa-mobile" aria-hidden="true"></i>
                                                                </span>
                                                            </div>
                                                            <input name="upd_emp_phnum" type="number" pattern="[0-9]{11}" min="0" oninput="validity.valid||(value='');" class="form-control" @if($user_emp_info->uEmp_phnum != 'null') value="{{$user_emp_info->uEmp_phnum}}" @else placeholder="Type Contact Number" @endif required>
                                                        </div>
                                                        <div class="d-flex justify-content-center">
                                                            <input type="hidden" name="selected_user_id" id="selected_user_id" value="{{$user_data->id}}"/>
                                                            <input type="hidden" name="respo_user_id" value="{{auth()->user()->id}}"/>
                                                            <input type="hidden" name="respo_user_lname" value="{{auth()->user()->user_lname}}"/>
                                                            <input type="hidden" name="respo_user_fname" value="{{auth()->user()->user_fname}}"/>
                                                            <button type="submit" id="update_empUserInfoBtn" class="btn btn_svms_blue btn-round btn_show_icon" disabled>{{ __('Save Changes') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                         {{-- unknown user type  --}}
                                    @endif
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <span class="cust_info_txtwicon"><i class="nc-icon nc-circle-10 mr-1" aria-hidden="true"></i>The System will notify {{ $user_data->user_fname }} {{ $user_data->user_lname }} of all the changes made to {{ $gender_txt }} profile thru {{ $gender_txt }} registered email address.</span>
                                        </div>
                                    </div>
                                </div>
                            {{-- edit user informatin form --}}
                            {{-- change user's password --}}
                                <div class="tab-pane fade" id="userPassword_edit_{{$user_data->id}}" role="tabpanel" aria-labelledby="pills_edit_userPassword_tab_{{$user_data->id}}">
                                    <div class="card card_gbr shadow">
                                        <div class="card-body p-0">
                                            <div class="card-header cb_p15x25">
                                                <span class="sec_card_body_title">Change User's Password</span>
                                                <span class="sec_card_body_subtitle">Type new password for {{ $user_data->user_fname }} {{ $user_data->user_lname}}'s Account. </span>
                                            </div>
                                            <form class="form" method="POST" action="{{route('user_management.update_user_password')}}" enctype="multipart/form-data" onsubmit="change_studUser_pass_btn.disabled = true; return true;">
                                                @csrf
                                                <div class="cb_px25 cb_pb15">
                                                    <div class="light_backDrop_card mb-2">
                                                        <div class="form-group">
                                                            <label for="upd_sysUser_new_password_reason">Reason <i class="fa fa-question-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="This will let {{ $user_data->user_fname }} {{ $user_data->user_lname }} know the reason behind updating {{ $gender_txt }} account password."></i></label>
                                                            <textarea class="form-control" id="upd_sysUser_new_password_reason" name="upd_sysUser_new_password_reason" rows="3" placeholder="Type reason for Password Update first (required)" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="light_backDrop_card mb-2">
                                                        <label for="upd_sysUser_new_password">Type New Password <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Include numbers, symbols, and uppercase and lowercase letters to have a strong password."></i></label>
                                                        <div class="input-group paswrd_inpt_fld">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="nc-icon nc-key-25" aria-hidden="true"></i>
                                                                </span>
                                                            </div>
                                                            <input type="password" onkeyup="check_pass_strenght()" id="upd_sysUser_new_password" name="upd_sysUser_new_password" class="form-control" placeholder="Type new password" required>
                                                            <i class="fa fa-eye" id="toggleUserNewPassword"></i>
                                                        </div>
                                                        <div class="pass_strenght_indicator_div d-none">
                                                            <span class="weak"></span>
                                                            <span class="medium"></span>
                                                            <span class="strong"></span>
                                                        </div>
                                                        <div id="pass_strenght_txt">

                                                        </div>
                                                        <div class="d-flex justify-content-center ">
                                                            <input type="hidden" name="selected_user_id" value="{{$user_data->id}}"/>
                                                            <input type="hidden" name="respo_user_id" value="{{auth()->user()->id}}"/>
                                                            <input type="hidden" name="respo_user_lname" value="{{auth()->user()->user_lname}}"/>
                                                            <input type="hidden" name="respo_user_fname" value="{{auth()->user()->user_fname}}"/>
                                                            <button id="change_studUser_pass_btn" type="submit" class="btn btn-success btn-round btn_show_icon">Update {{ $user_data->user_lname}}'s Password<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                        </div>
                                                    </div>
                                                    {{-- <span class="or_txt">
                                                        or
                                                    </span>
                                                    <div class="light_backDrop_card">
                                                        <span class="lightBlue_cardBody_notice"><i class="fa fa-info-circle" aria-hidden="true"></i> The <span class="font-weight-bold"> "Generate New Password" </span> button will automatically generate a new password for {{ $user_data->user_fname }} {{ $user_data->user_lname}}'s account. The System will notify the said user thru {{ $gender_txt }} email address.</span>
                                                        <div class="d-flex justify-content-center ">
                                                            <button id="generate_NewSysUserPass_Btn" type="button" class="btn btn_svms_blue btn-round btn_show_icon">Generate New Password<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <span class="cust_info_txtwicon"><i class="nc-icon nc-circle-10 mr-1" aria-hidden="true"></i>The System will notify {{ $user_data->user_fname }} {{ $user_data->user_lname }} of the changes made to {{ $gender_txt }} password thru {{ $gender_txt }} registered email address.</span>
                                        </div>
                                    </div>
                                </div>
                            {{-- change user's password end --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {{-- user profile card end --}}
        {{-- user activity logs --}}
        <div class="col-lg-8 col-md-7 col-sm-12">
            <div class="accordion" id="usersActLogsCollapseParent">
                <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                    <div class="card-header p-0" id="usersActLogsCollapseHeading">
                        <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#usersActLogsCollapseDiv" aria-expanded="true" aria-controls="usersActLogsCollapseDiv">
                            <div>
                                <span class="card_body_title">User's Activity Logs</span>
                                <span class="card_body_subtitle">View {{ $user_data->user_fname }} {{ $user_data->user_lname}}'s Activity Logs and generate report.</span>
                            </div>
                            <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                        </button>
                    </div>
                    <div id="usersActLogsCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="usersActLogsCollapseHeading" data-parent="#usersActLogsCollapseParent">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray2">
                                    <div class="card-body">
                                        <div class="row">
                                            @php
                                                // date formats for #userActLogsFiltr_datepickerRange placeholder
                                                if(!is_null($user_first_record) OR !empty($user_first_record) OR $user_first_record != 0){
                                                    $user_first_record_date = date('F d, Y (D - g:i A)', strtotime($user_first_record->created_at));
                                                }else{
                                                    $user_first_record_date = '';
                                                }
                                                if(!is_null($user_latest_record) OR !empty($user_latest_record) OR $user_latest_record != 0){
                                                    $user_latest_record_date = date('F d, Y (D - g:i A)', strtotime($user_latest_record->created_at));
                                                }else{
                                                    $user_latest_record_date = '';
                                                }
                                                // date range placeholder 
                                                if(!is_null($user_first_record) OR !empty($user_first_record) OR $user_first_record != 0 AND !is_null($user_latest_record) OR !empty($user_latest_record) OR $user_latest_record != 0){
                                                    $dateRange_placeholder = ''.$user_first_record_date . ' to ' . $user_latest_record_date.'';
                                                    $readOnly_class = 'readOnlyClass';
                                                }else{
                                                    $dateRange_placeholder = 'No Records Found...';
                                                    $readOnly_class = '';
                                                }
                                                // categori input placeholder
                                                if(count($user_trans_categories) > 0){
                                                    $categories_placeholder = 'All Categories';
                                                    $readOnly_attr = '';
                                                }else{
                                                    $categories_placeholder = 'No Records Found...';
                                                    $readOnly_attr = 'readonly';
                                                }
                                            @endphp
                                            <div class="col-lg-8 col-md-9 col-sm-12">
                                                <div class="cust_inputDiv_wIcon">
                                                    <input id="userActLogsFiltr_datepickerRange" name="userActLogsFiltr_datepickerRange" type="text" class="form-control cust_inputv1 {{ $readOnly_class }}" placeholder="{{ $dateRange_placeholder }}" readonly />
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </div>
                                                @csrf
                                                <input type="hidden" name="userActLogs_hidden_dateRangeFrom" id="userActLogs_hidden_dateRangeFrom">
                                                <input type="hidden" name="userActLogs_hidden_dateRangeTo" id="userActLogs_hidden_dateRangeTo">
                                                <input type="hidden" name="uac_hiddenTotalData_found" id="uac_hiddenTotalData_found">
                                            </div>
                                            <div class="col-lg-4 col-md-3 col-sm-12">
                                                <div class="form-group cust_inputDiv_wIconv1 mb-1">
                                                    <select id="userActLogsFiltr_categories" class="form-control cust_selectDropdownBox1 drpdwn_arrow" {{ $readOnly_attr }}>
                                                        <option value="0" selected>{{$categories_placeholder}}</option>
                                                        @if(count($user_trans_categories) > 0)
                                                            @foreach ($user_trans_categories as $this_category)
                                                                <option value="{{$this_category->act_type}}">{{ucwords($this_category->act_type) }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <i class="fa fa-list-ul" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                        @if(count($user_trans_categories) > 0)
                                        <div class="row mt-2">
                                            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-end">
                                                <a href="#" type="button" id="generateActLogs_btn" class="btn btn-success cust_bt_links shadow mr-2"><i class="nc-icon nc-single-copy-04 mr-1" aria-hidden="true"></i> Generate Report</a>
                                                <button type="button" id="resetUserActLogsFilter_btn" class="btn btn_svms_blue cust_bt_links shadow" disabled><i class="fa fa-refresh mr-1" aria-hidden="true"></i> Reset</button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <table class="table table-hover cust_table shadow">
                                    <thead class="thead_svms_blue">
                                        <tr>
                                            <th class="pl12">~ Date</th>
                                            <th>Category</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody_svms_white" id="ual_tableTbody">
                                        {{-- ajax data table --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <span>Total Data: <span class="font-weight-bold" id="ual_tableTotalData_count"> </span> </span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-center">
                                @csrf
                                <input type="hidden" name="ual_dateRangePicker_minDate" id="ual_dateRangePicker_minDate" value="{{$user_first_record_date}}">
                                <input type="hidden" name="ual_dateRangePicker_maxDate" id="ual_dateRangePicker_maxDate" value="{{$user_latest_record_date}}">
                                <input type="hidden" name="ual_hidden_page" id="ual_hidden_page" value="1" />
                                <input type="hidden" name="ual_user_id" id="ual_user_id" value="{{$user_data->id}}" />
                                <div id="ual_tablePagination">
                                    {{-- {{ $user_activities->links('pagination::bootstrap-4') }} --}}
                                </div>
                            </div>
                            {{-- <div class="col-lg-6 col-md-6 col-sm-12">
                                <span>Total Data: <span class="font-weight-bold" id="total_data_count"> </span> </span>
                            </div> --}}
                            {{-- <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-end">
                                <a href="#" class="btn btn-success cust_bt_links shadow" role="button"><i class="fa fa-print mr-1" aria-hidden="true"></i> Generate Report</a>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- user activity logs end --}}
        </div>
    </div>

    {{-- modals --}}
    {{-- deactivate user account modal --}}
        <div class="modal fade" id="deactivateUserAccountModal" tabindex="-1" role="dialog" aria-labelledby="deactivateUserAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="deactivateUserAccountModalLabel">Deactivate User Account?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="deactivateUserAccountHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- deactivate user account modal end --}}
    {{-- deactivate user account modal --}}
        <div class="modal fade" id="activateUserAccountModal" tabindex="-1" role="dialog" aria-labelledby="activateUserAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="activateUserAccountModalLabel">Activate User Account?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="activateUserAccountHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- deactivate user account modal end --}}
    {{-- manage user role first modal --}}
        <div class="modal fade" id="manageUserRoleFirstModal" tabindex="-1" role="dialog" aria-labelledby="manageUserRoleFirstModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="manageUserRoleFirstModalLabel">Manage Assigned Role First </span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="manageUserRoleFirstHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- manage user role first modal --}}
    {{-- change user's System Role modal --}}
        <div class="modal fade" id="changeUserRoleModal" tabindex="-1" role="dialog" aria-labelledby="changeUserRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="changeUserRoleModalLabel">Change User's Role?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="changeUserRoleHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- change user's System Role modal --}}
    {{-- add New System Role modal --}}
        <div class="modal fade" id="addNewSystemRoleModal" tabindex="-1" role="dialog" aria-labelledby="addNewSystemRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="addNewSystemRoleModalLabel">Add New System Role?</span>
                        <button type="button" id="back_btn" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="addNewSystemRoleHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- add New System Role modal end --}}

@endsection

@push('scripts')

{{-- activate/deactivate user account open modal for confirmation --}}
    <script>
        function deactivateUserAccount(deactivate_user_id){
            var deactivate_user_id = deactivate_user_id;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user_management.deactivate_user_account_modal') }}",
                method:"GET",
                data:{deactivate_user_id:deactivate_user_id, _token:_token},
                success: function(data){
                    $('#deactivateUserAccountHtmlData').html(data); 
                    $('#deactivateUserAccountModal').modal('show');
                }
            });
        }
    </script>
    <script>
        function activateUserAccount(activate_user_id){
            var activate_user_id = activate_user_id;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user_management.activate_user_account_modal') }}",
                method:"GET",
                data:{activate_user_id:activate_user_id, _token:_token},
                success: function(data){
                    $('#activateUserAccountHtmlData').html(data); 
                    $('#activateUserAccountModal').modal('show');
                }
            });
        }
    </script>
{{-- activate/deactivate user account open modal for confirmation end --}}

{{-- manage role first open modal --}}
    <script>
        function manageRoleFirst(manage_role_first_id){
            var manage_role_first_id = manage_role_first_id;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user_management.manage_role_first_modal') }}",
                method:"GET",
                data:{manage_role_first_id:manage_role_first_id, _token:_token},
                success: function(data){
                    $('#manageUserRoleFirstHtmlData').html(data); 
                    $('#manageUserRoleFirstModal').modal('show');
                }
            });
        }
    </script>
{{-- manage role first open modal end --}}
{{-- change user role open modal --}}
    <script>
        function changeUserRole(sel_user_id){
            var sel_user_id = sel_user_id;
            var _token = $('input[name="_token"]').val();
            $('#addNewSystemRoleModal').modal('hide');
            $.ajax({
                url:"{{ route('user_management.change_user_role_modal') }}",
                method:"GET",
                data:{sel_user_id:sel_user_id, _token:_token},
                success: function(data){
                    $('#changeUserRoleHtmlData').html(data); 
                    $('#changeUserRoleModal').modal('show');
                }
            });
        }
    </script>
{{-- change user role open modal end --}}
{{-- add new system role open modal --}}
    <script>
        function add_newSystemRole_modal(prev_user_id){
            var prev_user_id = prev_user_id;
            var _token = $('input[name="_token"]').val();
            $('#changeUserRoleModal').modal('hide');
            $.ajax({
                url:"{{ route('user_management.add_new_system_role_modal') }}",
                method:"GET",
                data:{prev_user_id:prev_user_id, _token:_token},
                success: function(data){
                    $('#addNewSystemRoleHtmlData').html(data); 
                    $('#addNewSystemRoleModal').modal('show');
                }
            });
        }
    </script>
{{-- add new system role open modal end --}}

{{-- STUDENT USER's PROFILE UPDATE --}}
{{-- student user's image update --}}
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
{{-- student user's image update end --}}
{{-- display datalist options based on previous selected option --}}
    {{-- selected school --}}
    <script>
        $(document).ready(function() {
            $("#upd_stud_school").on("change paste keyup", function() {
                var selectedSchool = $(this).val();
                document.getElementById('upd_stud_program').value = '';
                document.getElementById('upd_stud_yearlvl').value = '';
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
                document.getElementById('upd_stud_yearlvl').value = '';
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
{{-- disable update button on student profile update if any of inputs have chagned --}}
    <script>
        $(window).on('load', function(e){
            $('#form_studUserUpdateProfile').each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#update_studUserInfoBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                /* Check if input with type files has changed */
                var changedFiles = $( ":file" ).filter(function( index ) {
                    return this.value != this.defaultValue;
                }).length;
                if ( changedFiles > 0) {
                    $(this).find('#update_studUserInfoBtn, input[type="file"]')
                        .prop('disabled', false);
                }
            }).find('#update_studUserInfoBtn').prop('disabled', true);
        });
    </script>
{{-- disable update button on student profile update if any of inputs have chagned end --}}

{{-- EMPLOYEE USER's PROFILE UPDATE --}}
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
{{-- employee profile image end --}}
{{-- disable update button on employee profile update if any of inputs have chagned --}}
    <script>
        $(window).on('load', function(e){
            $('#form_empUserUpdateProfile').each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#update_empUserInfoBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                /* Check if input with type files has changed */
                var changedFiles = $( ":file" ).filter(function( index ) {
                    return this.value != this.defaultValue;
                }).length;
                if ( changedFiles > 0) {
                    $(this).find('#update_empUserInfoBtn, input[type="file"]')
                        .prop('disabled', false);
                }
            }).find('#update_empUserInfoBtn').prop('disabled', true);
        });
    </script>
{{-- disable update button on employee profile update if any of inputs have chagned end --}}

{{-- toggle password input visibility --}}
    <script>
        const toggleUserNewPassword = document.querySelector('#toggleUserNewPassword');
        const upd_sysUser_new_password = document.querySelector('#upd_sysUser_new_password');
        toggleUserNewPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = upd_sysUser_new_password.getAttribute('type') === 'password' ? 'text' : 'password';
            upd_sysUser_new_password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
{{-- toggle password input visibility end --}}
    
{{-- for password update --}}
{{-- password check strenght --}} 
    <script>
        const newSysUserPass_indicator    = document.querySelector(".pass_strenght_indicator_div");
        const newSysUserPass_input        = document.querySelector("#upd_sysUser_new_password");
        const newSysUserPass_reason_input = document.querySelector("#upd_sysUser_new_password_reason");
        const weak                        = document.querySelector(".weak");
        const medium                      = document.querySelector(".medium");
        const strong                      = document.querySelector(".strong");
        const text                        = document.querySelector("#pass_strenght_txt");
        const NewSysUserPass_Btn          = document.querySelector("#change_studUser_pass_btn");
        // const generate_NewSysUserPass_Btn = document.querySelector("#generate_NewSysUserPass_Btn");
        let regExpWeak                    = /[a-z]/;
        let regExpMedium                  = /\d+/;
        let regExpStrong                  = /.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/;

        function check_pass_strenght(){
            if(newSysUserPass_input.value !== ""){
                // generate_NewSysUserPass_Btn.disabled = true;
                newSysUserPass_indicator.classList.remove("d-none");
                newSysUserPass_indicator.style.display = "flex";
                if(newSysUserPass_input.value.length <= 3 && (newSysUserPass_input.value.match(regExpWeak) || newSysUserPass_input.value.match(regExpMedium) || newSysUserPass_input.value.match(regExpStrong)))no=1;
                if(newSysUserPass_input.value.length >= 6 && ((newSysUserPass_input.value.match(regExpWeak) && newSysUserPass_input.value.match(regExpMedium)) || (newSysUserPass_input.value.match(regExpMedium) && newSysUserPass_input.value.match(regExpStrong)) || (newSysUserPass_input.value.match(regExpWeak) && newSysUserPass_input.value.match(regExpStrong))))no=2;
                if(newSysUserPass_input.value.length >= 6 && newSysUserPass_input.value.match(regExpWeak) && newSysUserPass_input.value.match(regExpMedium) && newSysUserPass_input.value.match(regExpStrong))no=3;
                if(no===1){
                    weak.classList.add("active");
                    text.style.display = "block";
                    text.textContent   = "Password strength is too week";
                    text.classList.add("weak");
                }
                if(no===2){
                    medium.classList.add("active");
                    weak.classList.remove("active");
                    weak.classList.add("medium_bgColor");
                    text.textContent = "password strength not too strong";
                    text.classList.add("medium");
                }else{
                    medium.classList.remove("active");
                    weak.classList.remove("medium_bgColor");
                    text.classList.remove("medium");
                }
                if(no===3){
                    weak.classList.remove("active");
                    strong.classList.remove("active");
                    weak.classList.add("strong_bgColor");
                    medium.classList.add("strong_bgColor");
                    strong.classList.add("active");
                    text.textContent = "password strength is strong";
                    text.classList.add("strong");
                    NewSysUserPass_Btn.disabled = false;
                }else{
                    strong.classList.remove("active");
                    text.classList.remove("strong");
                    weak.classList.remove("strong_bgColor");
                    medium.classList.remove("strong_bgColor");
                }
            }else{
                newSysUserPass_indicator.classList.add("d-none");
                text.style.display = "none";
                NewSysUserPass_Btn.disabled = true;
                // generate_NewSysUserPass_Btn.disabled = false;
            }
        }
    </script>
{{-- password check strenght end --}}

{{-- email availability for new emai on user's profile update --}}
    {{-- for student user --}}
        <script>
            $(document).ready(function(){
                $('#upd_stud_email').blur(function(){
                    var error_email = '';
                    var stud_id = $('#selected_user_id').val();
                    var stud_email = $('#upd_stud_email').val();
                    var _token = $('input[name="_token"]').val();
                    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    // console.log(stud_id);
                    // console.log(stud_email);
                    if(!filter.test(stud_email)){    
                        $('#studEmailAvail_notice').removeClass('d-none');
                        $('#studEmailAvail_notice').addClass('invalid-feedback');
                        $('#studEmailAvail_notice').addClass('d-block');
                        // $('#prepend_status').addClass('is_invalid');
                        $('#studEmailAvail_notice').html('<strong>Invalid Email Format!</strong>');
                        $('#upd_stud_email').addClass('is-invalid');
                    }else{
                        $.ajax({
                            url:"{{ route('user_management.stud_user_switch_new_email_availability_check') }}",
                            method:"POST",
                            data:{stud_id:stud_id, stud_email:stud_email, _token:_token},
                            success:function(result){
                                if(result == 'unique'){
                                    $('#studEmailAvail_notice').removeClass('d-none');
                                    $('#studEmailAvail_notice').removeClass('invalid-feedback');
                                    $('#studEmailAvail_notice').addClass('valid-feedback');
                                    $('#studEmailAvail_notice').html('<strong>Email Available.</strong>');
                                    $('#upd_stud_email').removeClass('is-invalid');
                                    $('#upd_stud_email').addClass('is-valid');
                                    // console.log('unique');
                                }else{
                                    $('#studEmailAvail_notice').removeClass('d-none');
                                    $('#studEmailAvail_notice').addClass('invalid-feedback');
                                    $('#studEmailAvail_notice').addClass('d-block');
                                    // $('#prepend_status').addClass('is_invalid');
                                    $('#studEmailAvail_notice').html('<strong>Email already in use!</strong>');
                                    $('#upd_stud_email').addClass('is-invalid');
                                    $('#update_studUserInfoBtn').attr('disabled', 'disabled');
                                    // console.log('duplicate');
                                }
                            }
                        })
                    }
                });
            });
        </script>
    {{-- for student user --}}
    {{-- for employee user --}}
        <script>
            $(document).ready(function(){
                $('#upd_emp_email').blur(function(){
                    var error_email = '';
                    var emp_id = $('#selected_user_id').val();
                    var emp_email = $('#upd_emp_email').val();
                    var _token = $('input[name="_token"]').val();
                    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    // console.log(emp_id);
                    // console.log(emp_email);
                    if(!filter.test(emp_email)){    
                        $('#empEmailAvail_notice').removeClass('d-none');
                        $('#empEmailAvail_notice').addClass('invalid-feedback');
                        $('#empEmailAvail_notice').addClass('d-block');
                        // $('#prepend_status').addClass('is_invalid');
                        $('#empEmailAvail_notice').html('<strong>Invalid Email Format!</strong>');
                        $('#upd_emp_email').addClass('is-invalid');
                    }else{
                        $.ajax({
                            url:"{{ route('user_management.emp_user_switch_new_email_availability_check') }}",
                            method:"POST",
                            data:{emp_id:emp_id, emp_email:emp_email, _token:_token},
                            success:function(result){
                                if(result == 'unique'){
                                    $('#empEmailAvail_notice').removeClass('d-none');
                                    $('#empEmailAvail_notice').removeClass('invalid-feedback');
                                    $('#empEmailAvail_notice').addClass('valid-feedback');
                                    $('#empEmailAvail_notice').html('<strong>Email Available.</strong>');
                                    $('#upd_emp_email').removeClass('is-invalid');
                                    $('#upd_emp_email').addClass('is-valid');
                                    // console.log('unique');
                                }else{
                                    $('#empEmailAvail_notice').removeClass('d-none');
                                    $('#empEmailAvail_notice').addClass('invalid-feedback');
                                    $('#empEmailAvail_notice').addClass('d-block');
                                    // $('#prepend_status').addClass('is_invalid');
                                    $('#empEmailAvail_notice').html('<strong>Email already in use!</strong>');
                                    $('#upd_emp_email').addClass('is-invalid');
                                    $('#update_empUserInfoBtn').attr('disabled', 'disabled');
                                    // console.log('duplicate');
                                }
                            }
                        })
                    }
                });
            });
        </script>
    {{-- for employee user --}}
{{-- email availability for new emai on user's profile update end --}}

{{-- CHANGE USER'S ROLE --}}
{{-- disable submit button on Change User Role Modal if any of inputs have chagned --}}
    <script>
        $('#changeUserRoleModal').on('show.bs.modal', function () {
            $('#form_changeUserRole').each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#submit_changeUserRoleBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                console.log('nagbago');
            }).find('#submit_changeUserRoleBtn').prop('disabled', true);
        });
    </script>
{{-- disable submit button on Change User Role Modal if any of inputs have chagned end --}}


{{-- USER'S ACTIVITY LOGS --}}
    <script>
        $(document).ready(function(){
            load_userActLogs_table();

            // function for ajax table pagination
            $(window).on('hashchange', function() {
                    if (window.location.hash) {
                        var page = window.location.hash.replace('#', '');
                        if (page == Number.NaN || page <= 0) {
                            return false;
                        }else{
                            getUalPage(page);
                        }
                    }
                });
                $(document).on('click', '.pagination a', function(event){
                    event.preventDefault();
                    var page = $(this).attr('href').split('page=')[1];
                    $('#ual_hidden_page').val(page);
                    
                    load_userActLogs_table();
                    getUalPage(page);
                    $('li.page-item').removeClass('active');
                    $(this).parent('li.page-item').addClass('active');
                });
                function getUalPage(page){
                    $.ajax({
                        url: '?page=' + page,
                        type: "get",
                        datatype: "html"
                    }).done(function(data){
                        location.hash = page;
                    }).fail(function(jqXHR, ajaxOptions, thrownError){
                        alert('No response from server');
                    });
                }
            // function for ajax table pagination end

            function load_userActLogs_table(){
                // get all filtered values
                var ual_rangefrom = document.getElementById("userActLogs_hidden_dateRangeFrom").value;
                var ual_rangeTo = document.getElementById("userActLogs_hidden_dateRangeTo").value;
                var ual_category = document.getElementById("userActLogsFiltr_categories").value;
                var ual_user_id = document.getElementById("ual_user_id").value;
                var page = document.getElementById("ual_hidden_page").value;

                console.log('');
                console.log('user id: ' + ual_user_id);
                console.log('From date: ' + ual_rangefrom);
                console.log('To Date: ' + ual_rangeTo);
                console.log('Category: ' + ual_category);
                console.log('page: ' + page);

                $.ajax({
                    url:"{{ route('user_management.user_act_logs') }}",
                    method:"GET",
                    data:{
                        ual_user_id:ual_user_id,
                        ual_rangefrom:ual_rangefrom, 
                        ual_rangeTo:ual_rangeTo, 
                        ual_category:ual_category, 
                        page:page
                        },
                    dataType:'json',
                    success:function(ual_data){
                        $('#ual_tableTbody').html(ual_data.ual_table);
                        $('#ual_tablePagination').html(ual_data.ual_table_paginate);
                        $('#ual_tableTotalData_count').html(ual_data.ual_total_rows);
                        $('#uac_hiddenTotalData_found').val(ual_data.ual_total_data_found);

                        // for disabling/ enabling generate report button
                        // var violationRecs_totalData = document.getElementById("al_hiddenTotalData_found").value;
                        // if(violationRecs_totalData > 0){
                        //     $('#generateViolationRecs_btn').prop('disabled', false);
                        // }else{
                        //     $('#generateViolationRecs_btn').prop('disabled', true);
                        // }
                    }
                });

                // for disabling/ enabling reset filter button
                if(ual_category != 0 || ual_rangefrom != '' || ual_rangeTo != ''){
                    $('#resetUserActLogsFilter_btn').prop('disabled', false);
                }else{
                    $('#resetUserActLogsFilter_btn').prop('disabled', true);
                }
            }

            // daterange picker
                var ualMinDate = document.getElementById("ual_dateRangePicker_minDate").value;
                var vMaxDate = document.getElementById("ual_dateRangePicker_maxDate").value;
                // console.log(ualMinDate);
                // console.log(vMaxDate);
                $('#userActLogsFiltr_datepickerRange').daterangepicker({
                    timePicker: true,
                    showDropdowns: true,
                    minYear: ualMinDate,
                    maxYear: parseInt(moment().format('YYYY'),10),
                    maxDate: vMaxDate,
                    minDate: ualMinDate,
                    drops: 'down',
                    opens: 'right',
                    autoUpdateInput: false,
                    locale: {
                        format: 'MMMM DD, YYYY (ddd - hh:mm A)',
                        cancelLabel: 'Clear'
                        }
                });
                $('#userActLogsFiltr_datepickerRange').on('cancel.daterangepicker', function(ev, picker) {
                    document.getElementById("userActLogs_hidden_dateRangeFrom").value = '';
                    document.getElementById("userActLogs_hidden_dateRangeTo").value = '';
                    $(this).val('');
                    $(this).removeClass('cust_input_hasvalue');
                    // table paginatin set to 1
                    $('#ual_hidden_page').val(1);
                    load_userActLogs_table();
                });
                $('#userActLogsFiltr_datepickerRange').on('apply.daterangepicker', function(ev, picker) {
                    // for hidden data range inputs
                    var start_range = picker.startDate.format('YYYY-MM-DD HH:MM:SS');
                    var end_range = picker.endDate.format('YYYY-MM-DD HH:MM:SS');
                    document.getElementById("userActLogs_hidden_dateRangeFrom").value = start_range;
                    document.getElementById("userActLogs_hidden_dateRangeTo").value = end_range;
                    // for date range display
                    $(this).val(picker.startDate.format('MMMM DD, YYYY (ddd - hh:mm A)') + ' to ' + picker.endDate.format('MMMM DD, YYYY (ddd - hh:mm A)'));
                    $(this).addClass('cust_input_hasvalue');
                    // table paginatin set to 1
                    $('#ual_hidden_page').val(1);
                    load_userActLogs_table();
                });
            // daterange picker end

            // filter log categories
                $('#userActLogsFiltr_categories').on('change paste keyup', function(){
                    var selectedCategory = $(this).val();
                    if(selectedCategory != 0){
                        $(this).addClass('cust_input_hasvalue');
                    }else{
                        $(this).removeClass('cust_input_hasvalue');
                    }
                    // table paginatin set to 1
                    $('#ual_hidden_page').val(1);
                    load_userActLogs_table();
                });
            // filter log categories end

            // reset filters
                $('#resetUserActLogsFilter_btn').on('click', function(){
                    // for hidden data range inputs
                    document.getElementById("userActLogs_hidden_dateRangeFrom").value = '';
                    document.getElementById("userActLogs_hidden_dateRangeTo").value = '';
                    // for date range display
                    document.getElementById("userActLogsFiltr_datepickerRange").classList.remove("cust_input_hasvalue");
                    document.getElementById("userActLogsFiltr_datepickerRange").value = '';
                    // categories
                    document.getElementById("userActLogsFiltr_categories").classList.remove("cust_input_hasvalue");
                    $('#userActLogsFiltr_categories').val(0);
                    // disable reset button
                    $(this).prop('disabled', true);
                    // table paginatin set to 1
                    $('#ual_hidden_page').val(1);
                    load_userActLogs_table();
                });
            // reset filters end
        });
    </script>
{{-- USER'S ACTIVITY LOGS end --}}
@endpush