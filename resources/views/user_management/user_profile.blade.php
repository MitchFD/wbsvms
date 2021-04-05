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
                <a href="{{ route('user_management.overview_users_management', 'overview_users_management') }}" class="directory_link">Users Management</a> <span class="directory_divider"> / </span> <a href="{{ route('user_management.system_users', 'system_users') }}" class="directory_link">System Users </a> <span class="directory_divider"> / </span> <a href="{{ route('user_management.user_profile', 'user_profile') }}" class="directory_active_link">User Profile ~ {{ $user_data->user_fname }} {{ $user_data->user_lname }}</a>
            </div>
        </div>

        {{-- data customizations --}}
        @php
        // his/her text
            if($user_data->user_gender === 'male'){
                $gender_txt = 'his';
            }else{
                $gender_txt = 'her';
            }
        // his/her text end

        // filter for user types 
            if($user_data->user_type === 'student'){
                $user_stud_info  = App\Models\Userstudents::where('uStud_num', $user_data->user_sdca_id)->first();
                $custom_nav_pill = 'custom_nav_link_green';
                if($user_data->user_role_status === 'active'){
                    if($user_data->user_status === 'active'){
                        $image_filter   = 'up_stud_user_image';
                        $user_alt_image = 'student_user_image';
                    }else{
                        if($user_data->user_status === 'deactivated' OR $user_data->user_status === 'deleted'){
                            $image_filter   = 'up_red_user_image';
                            $user_alt_image = 'no_student_image';
                        }else{
                            $image_filter   = 'up_gray_user_image';
                            $user_alt_image = 'disabled_user_image';
                        }
                    }
                }else{
                    if($user_data->user_role_status === 'deactivated'  OR $user_data->user_role_status === 'deleted'){
                        $image_filter   = 'up_red_user_image';
                        $user_alt_image = 'no_student_image';
                    }else{
                        $image_filter   = 'up_gray_user_image';
                        $user_alt_image = 'disabled_user_image';
                    }   
                }
            }else{
                $user_emp_info   = App\Models\Useremployees::where('uEmp_id', $user_data->user_sdca_id)->first();
                $custom_nav_pill = 'custom_nav_link_blue';
                if($user_data->user_role_status === 'active'){
                    if($user_data->user_status === 'active'){
                        $image_filter   = 'up_user_image';
                        $user_alt_image = 'employee_user_image';
                    }else{
                        if($user_data->user_status === 'deactivated' OR $user_data->user_status === 'deleted'){
                            $image_filter   = 'up_red_user_image';
                            $user_alt_image = 'no_student_image';
                        }else{
                            $image_filter   = 'up_gray_user_image';
                            $user_alt_image = 'disabled_user_image';
                        }
                    }
                }else{
                    if($user_data->user_role_status === 'deactivated' OR $user_data->user_role_status === 'deleted'){
                        $image_filter   = 'up_red_user_image';
                        $user_alt_image = 'no_student_image';
                    }else{
                        $image_filter   = 'up_gray_user_image';
                        $user_alt_image = 'disabled_user_image';
                    }   
                }
            }
        // filter for user types end 
        @endphp

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">User Profile</span>
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
                                                    <img class="{{ $image_filter }} shadow"
                                                    @if(!is_null($user_data->user_image))
                                                        src="{{asset('storage/svms/user_images/'.$user_data->user_image)}}" alt="{{$user_data->user_fname }} {{ $user_data->user_lname}}'s profile image'"
                                                    @else
                                                        src="{{asset('storage/svms/user_images/'.$user_alt_image.'.jpg')}}" alt="default employee user's profile image"
                                                    @endif
                                                    >
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
                                                        @if($user_data->user_gender === 'male')
                                                            <span class="up_info_txt"><i class="fa fa-male"></i> {{ $user_data->user_gender}}</span> 
                                                        @elseif($user_data->user_gender === 'female')
                                                            <span class="up_info_txt"><i class="fa fa-female"></i> {{ $user_data->user_gender}}</span> 
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
                                                        @if($user_data->user_gender === 'male')
                                                            <span class="up_info_txt mb-0"><i class="fa fa-male"></i> {{ $user_data->user_gender}}</span> 
                                                        @elseif($user_data->user_gender === 'female')
                                                            <span class="up_info_txt mb-0"><i class="fa fa-female"></i> {{ $user_data->user_gender}}</span> 
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
                                            if($user_data->user_role_status === 'active'){
                                                if($user_data->user_status === 'active'){
                                                    // deactivate account
                                                    $btn_class  = "btn-success";
                                                    $btn_label  = "Account is Activated";
                                                    $btn_icon   = "fa fa-toggle-on";
                                                    $btn_action = 'onclick=deactivateUserAccount(this.id)';
                                                    $question   = 'Deactivate';
                                                }elseif($user_data->user_status === 'deactivated'){
                                                    // activate account
                                                    $btn_class  = "btn_svms_red";
                                                    $btn_label  = "Account is Deactivated";
                                                    $btn_icon   = "fa fa-toggle-off";
                                                    $btn_action = 'onclick=activateUserAccount(this.id)';
                                                    $question   = 'Activate';
                                                }elseif($user_data->user_status === 'pending'){
                                                    $btn_class  = "btn-secondary";
                                                    $btn_label  = "Account is Pending";
                                                    $btn_icon   = "fa fa-spinner";
                                                    $btn_action = 'onclick=activateUserAccount(this.id)';
                                                    $question   = 'Activate';
                                                }elseif($user_data->user_status === 'deleted'){
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
                                            }elseif($user_data->user_role_status === 'deactivated'){
                                                // activate role first
                                                $btn_class  = "btn_svms_red";
                                                $btn_label  = "Account is Deactivated";
                                                $btn_icon   = "fa fa-toggle-off";
                                                $btn_action = 'onclick=activateUserAccount(this.id)';
                                                $question   = 'Activate';
                                            }elseif($user_data->user_role_status === 'pending'){
                                                // manage role first
                                                $btn_class  = "btn-secondary";
                                                $btn_label  = "Account is Pending";
                                                $btn_icon   = "fa fa-spinner";
                                                $btn_action = 'onclick=manageRoleFirst(this.id)';
                                                $question   = 'Activate';
                                            }elseif($user_data->user_role_status === 'deleted'){
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
                                                    <button type="button" id="{{$user_data->id}}" class="btn {{ $btn_class }} btn_group_icon m-0" data-toggle="tooltip" data-placement="top" title="Change User's System Role?"><i class="fa fa-pencil"></i></button>
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
                                                                    <img class="up_stud_user_image stud_imgUpld_targetImg shadow border-gray" src="{{asset('storage/svms/user_images/'.$user_data->user_image)}}" alt="{{$user_data->user_fname }} {{ $user_data->user_lname}}'s profile image'">
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
                                                            <input type="hidden" name="selected_user_id" value="{{$user_data->id}}"/>
                                                            <input type="hidden" name="respo_user_id" value="{{auth()->user()->id}}"/>
                                                            <input type="hidden" name="respo_user_lname" value="{{auth()->user()->user_lname}}"/>
                                                            <input type="hidden" name="respo_user_fname" value="{{auth()->user()->user_fname}}"/>
                                                            <button type="submit" id="update_studUserInfoBtn" class="btn btn-success btn-round btn_show_icon" disabled>{{ __('Save Changes') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @elseif($user_data === 'employee')

                                    @else
                                        
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
                                                            <label for="upd_sysUser_new_password_reason">Reason <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="This will let {{ $user_data->user_fname }} {{ $user_data->user_lname }} know the reason behind updating {{ $gender_txt }} account password."></i></label>
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
                                                            <i class="fa fa-eye" id="toggleStudUserNewPassword"></i>
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
                        @if($user_data->user_type === 'student')
                            
                        @else
                            
                        @endif
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
                        <div class="row d-flex justify-content-center">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card card_gbr shadow">
                                    <div class="card-body">
                                        <div class="row input-daterange">
                                            <div class="col-md-4">
                                                <input type="text" name="from_date" id="from_date" class="form-control cust_date_filterInput" placeholder="From Date" readonly />
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="to_date" id="to_date" class="form-control cust_date_filterInput" placeholder="To Date" readonly />
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                                                <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
                                            </div>
                                        </div>
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
                                    <tbody class="tbody_svms_white" id="usersActLogs_tbody">
                                        {{-- ajax data table --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <span>Total Data: <span class="font-weight-bold" id="total_data_count"> </span> </span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-end">
                                <a href="#" class="btn btn-success cust_bt_links shadow" role="button"><i class="fa fa-print mr-1" aria-hidden="true"></i> Generate Report</a>
                            </div>
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
    {{-- verify credential for updating user's profile modal --}}
        <div class="modal fade" id="verifyPasswordModal" tabindex="-1" role="dialog" aria-labelledby="verifyPasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="verifyPasswordModalLabel">Manage Assigned Role First </span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="verifyPasswordHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- verify credential for updating user's profile modal --}}

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
{{-- student user's image update --}}
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

{{-- toggle password input visibility --}}
    <script>
        const toggleStudUserNewPassword = document.querySelector('#toggleStudUserNewPassword');
        const upd_sysUser_new_password = document.querySelector('#upd_sysUser_new_password');
        toggleStudUserNewPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = upd_sysUser_new_password.getAttribute('type') === 'password' ? 'text' : 'password';
            upd_sysUser_new_password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
{{-- toggle password input visibility end --}}
    
{{-- for password update --}}
    {{-- <script>
        $(document).ready(function () {
            $('#upd_sysUser_new_password_reason').keyup(function () {
                if ($(this).val() !== '') {
                    $('#generate_NewSysUserPass_Btn').prop('disabled', false);
                    $('#upd_sysUser_new_password').prop('disabled', false);
                }else{
                    $('#generate_NewSysUserPass_Btn').prop('disabled', true);
                    $('#upd_sysUser_new_password').prop('disabled', true);
                }
            })
        });  
    </script> --}}
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

@endpush