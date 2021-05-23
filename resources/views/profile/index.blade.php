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
                <a href="{{ route('profile.index', 'profile') }}" class="directory_link">My Profile</a>
            </div>
        </div>

        {{-- data customization --}}
            @php
                // single quote
                $sq = "'";

                // to lower values
                $toLower_userType = Str::lower(auth()->user()->user_type);
                $toLower_userStatus = Str::lower(auth()->user()->user_status);
                $toLower_userRoleStatus = Str::lower(auth()->user()->user_role_status);

                // image filters
                if($toLower_userType === 'employee'){
                    $user_emp_info   = App\Models\Useremployees::where('uEmp_id', auth()->user()->user_sdca_id)->first();
                    $custom_nav_pill = 'custom_nav_link_blue';
                    $sdca_num_text   = 'Employee ID';
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
                }else if($toLower_userType === 'student'){
                    $user_stud_info  = App\Models\Userstudents::where('uStud_num', auth()->user()->user_sdca_id)->first();
                    $custom_nav_pill = 'custom_nav_link_green';
                    $sdca_num_text   = 'Student Number';
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
                }else{
                    $custom_nav_pill = 'custom_nav_link_gray';
                    $sdca_num_text   = 'Employee ID';
                }

                // user's image
                if(!is_null(auth()->user()->user_image) OR !empty(auth()->user()->user_image)){
                    $user_image_src = asset('storage/svms/user_images/'.auth()->user()->user_image);
                    $user_image_alt = auth()->user()->user_fname . ' ' . auth()->user()->user_lname.''.$sq.'s profile image';
                }else{
                    if($toLower_userStatus == 'active'){
                        if($toLower_userType == 'employee'){
                            $user_image_jpg = 'employee_user_image.jpg';
                        }elseif($toLower_userType == 'student'){
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
        {{-- data customization end --}}

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">My Profile</span>
                            @if($toLower_userStatus == 'pending')
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
        <div class="row mt-2">
            {{-- USER ACCOUNT INFORMATION --}}
            <div class="col-lg-4 col-md-5 col-sm-12">
                <div class="accordion gCardAccordions" id="profileCollapse_{{auth()->user()->id}}">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="profileCollapseHeading">
                            <button id="profile_collapseBtnToggle" class="btn btn-link btn-block custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#collapse_userProfile_{{auth()->user()->id}}" aria-expanded="true" aria-controls="collapse_userProfile_{{auth()->user()->id}}">
                                <div>
                                    <span class="card_body_title">Account Information</span>
                                    <span class="card_body_subtitle">View and update your profile.</span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="collapse_userProfile_{{auth()->user()->id}}" class="collapse gCardAccordions_collapse show cb_t0b15x25" aria-labelledby="profileCollapseHeading" data-parent="#profileCollapse_{{auth()->user()->id}}">
                            <ul class="nav nav-pills custom_nav_pills mt-0 mb-3 d-flex justify-content-center" id="user-pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ $custom_nav_pill }} active" id="pills_userProfile_tab{{auth()->user()->id}}" data-toggle="pill" href="#div_userProfile_tab{{auth()->user()->id}}" role="tab" aria-controls="div_userProfile_tab{{auth()->user()->id}}" aria-selected="true">Profile</a>
                                </li>
                                @if($toLower_userStatus == 'active')
                                    <li class="nav-item">
                                        <a class="nav-link {{ $custom_nav_pill }}" id="pills_userEditProfile_tab{{auth()->user()->id}}" data-toggle="pill" href="#div_userEditProfile_tab{{auth()->user()->id}}" role="tab" aria-controls="div_userEditProfile_tab{{auth()->user()->id}}" aria-selected="false">Edit Profile</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $custom_nav_pill }}" id="pills_userChangePassword_tab{{auth()->user()->id}}" data-toggle="pill" href="#div_userChangePassword_tab{{auth()->user()->id}}" role="tab" aria-controls="div_userChangePassword_tab{{auth()->user()->id}}" aria-selected="false">Change Password</a>
                                    </li>
                                @endif
                            </ul>
                            <div class="tab-content" id="studentPills-tabContent">
                                {{-- profile --}}
                                <div class="tab-pane fade show active" id="div_userProfile_tab{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_userProfile_tab{{auth()->user()->id}}">
                                    <div class="card card_gbr shadow card-user">
                                        <div class="image">
                                            <img src="{{ asset('paper/img/damir-bosnjak.jpg') }}" alt="...">
                                        </div>
                                        <div class="card-body">
                                            <div class="author">
                                                {{-- user image --}}
                                                <a href="#" class="up_img_div">
                                                    <img class="{{ $image_filter }} shadow" src="{{$user_image_src}}" alt="{{$user_image_alt}}">
                                                </a>
                                                {{-- user name --}}
                                                <span class="up_fullname_txt text_svms_blue">{{auth()->user()->user_fname }}  {{auth()->user()->user_lname}}</span>
                                                @if(!is_null(auth()->user()->user_role) OR auth()->user()->user_role !== 'pending')
                                                    <h5 class="up_role_txt">{{ __(auth()->user()->user_role)}}</h5>
                                                @else
                                                    <h5 class="up_role_txt font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> Role is Pending</h5>
                                                @endif
                                                {{-- user sdca ID --}}
                                                <span class="cat_title_txt">{{$sdca_num_text}}</span>
                                                @if(!is_null(auth()->user()->user_sdca_id))
                                                    <span class="up_info_txt"><i class="nc-icon nc-badge"></i> {{ auth()->user()->user_sdca_id}}</span>
                                                @else
                                                    <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> {{$sdca_num_text}} unknown</span>
                                                @endif
                                                {{-- if user type = student --}}
                                                @if($toLower_userType === 'student')
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
                                                {{-- if user type = employee --}}
                                                @elseif($toLower_userType === 'employee')
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
                                                    {{-- employee phone number --}}
                                                    <span class="cat_title_txt">Contact Number</span>
                                                    @if(!is_null($user_emp_info->uEmp_phnum))
                                                        <span class="up_info_txt"><i class="nc-icon nc-mobile"></i> {{ $user_emp_info->uEmp_phnum}}</span> 
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no contact number</span>
                                                    @endif
                                                {{-- unknown user type --}}
                                                @else
                                                    <span class="cat_title_txt">User Type</span>
                                                    <span class="up_info_txt font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> unknown </span>
                                                @endif

                                                {{-- email --}}
                                                <span class="cat_title_txt">Email Address</span>
                                                @if(!is_null(auth()->user()->email))
                                                    <span class="up_info_txt"><i class="nc-icon nc-email-85"></i> {{ auth()->user()->email}}</span> 
                                                @else
                                                    <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no email address</span>
                                                @endif
                                                {{-- gender --}}
                                                <span class="cat_title_txt">Gender</span>
                                                @if(!is_null(auth()->user()->user_gender))
                                                    @if(auth()->user()->user_gender === 'male')
                                                        <span class="up_info_txt mb-0"><i class="fa fa-male"></i> {{ ucwords(auth()->user()->user_gender) }}</span> 
                                                    @elseif(auth()->user()->user_gender === 'female')
                                                        <span class="up_info_txt mb-0"><i class="fa fa-female"></i> {{ ucwords(auth()->user()->user_gender) }}</span> 
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> gender unknown</span>
                                                    @endif
                                                @else
                                                    <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no gender</span>
                                                @endif
                                                {{-- account status --}}
                                                @php
                                                // values for account status
                                                    if($toLower_userRoleStatus === 'active'){
                                                        if($toLower_userStatus === 'active'){
                                                            // deactivate account
                                                            $btn_class    = "btn-success";
                                                            $btn_label    = "Your Account is Activated";
                                                            $btn_icon     = "fa fa-toggle-on";
                                                            $acc_stat_txt = "Active";
                                                        }elseif($toLower_userStatus === 'deactivated'){
                                                            // activate account
                                                            $btn_class    = "btn_svms_red";
                                                            $btn_label    = "Your Account has been Deactivated";
                                                            $btn_icon     = "fa fa-toggle-off";
                                                            $acc_stat_txt = "Deactivated";
                                                        }elseif($toLower_userStatus === 'pending'){
                                                            $btn_class    = "btn-secondary";
                                                            $btn_label    = "Your Account is Pending";
                                                            $btn_icon     = "fa fa-spinner";
                                                            $acc_stat_txt = "Pending";
                                                        }elseif($toLower_userStatus === 'deleted'){
                                                            // user account is deleted - recover option
                                                            $btn_class    = "btn-secondary";
                                                            $btn_label    = "Your Account is Pending";
                                                            $btn_icon     = "fa fa-spinner";
                                                            $acc_stat_txt = "Deleted";
                                                        }else{
                                                            // just activate
                                                            $btn_class    = "btn_svms_red";
                                                            $btn_label    = "Your Account is Pending";
                                                            $btn_icon     = "fa fa-toggle-off";
                                                            $acc_stat_txt = "Pending";
                                                        }
                                                    }elseif($toLower_userRoleStatus === 'deactivated'){
                                                        // activate role first
                                                        $btn_class    = "btn_svms_red";
                                                        $btn_label    = "Your Account has been Deactivated";
                                                        $btn_icon     = "fa fa-toggle-off";
                                                        $acc_stat_txt = "Deactivated";
                                                    }elseif($toLower_userRoleStatus === 'pending'){
                                                        // manage role first
                                                        $btn_class    = "btn-secondary";
                                                        $btn_label    = "Your Account is Pending";
                                                        $btn_icon     = "fa fa-spinner";
                                                        $acc_stat_txt = "Pending";
                                                    }elseif($toLower_userRoleStatus === 'deleted'){
                                                        // role is deleted - assign new role
                                                        $btn_class    = "btn-secondary";
                                                        $btn_label    = "Your Account is Pending";
                                                        $btn_icon     = "fa fa-spinner";
                                                        $acc_stat_txt = "Deleted";
                                                    }else{
                                                        // manage role first
                                                        $btn_class    = "btn-secondary";
                                                        $btn_label    = "Your Account is Pending";
                                                        $btn_icon     = "fa fa-spinner";
                                                        $acc_stat_txt = "Pending";
                                                    }
                                                // values for account status end
                                                @endphp  
                                                <div class="row d-flex justify-content-center mt-4 mb-4">
                                                    <div class="col-lg-8 col-md-10 col-sm-11 p-0 d-flex justify-content-center">
                                                        <div class="btn-group cust_btn_group" role="group" aria-label="User's Account Status / Action">
                                                            <button type="button" class="btn {{ $btn_class }} btn_group_label m-0">{{ $btn_label }}</button>
                                                            <button type="button" class="btn {{ $btn_class }} btn_group_icon btn_def_cur m-0" data-toggle="tooltip" data-placement="top" title="Your Account Status is {{$acc_stat_txt}}"><i class="{{ $btn_icon }}"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- account date created & registered by --}}
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            @if(auth()->user()->user_role === 'administrator')
                                                @if(!is_null(auth()->user()->created_at))
                                                    <span class="cust_info_txtwicon"><i class="fa fa-pencil-square-o mr-1" aria-hidden="true"></i> Your Account was created in&nbsp; <span class="font-weight-bold"> {{ date('F j, Y - g:i A', strtotime(auth()->user()->created_at))}} </span></span>
                                                @else
                                                    <span class="cust_info_txtwicon font-italic text_svms_red"><i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> Account date created unknown </span>
                                                @endif
                                            @else
                                                @if(!is_null(auth()->user()->created_at))
                                                    <span class="cust_info_txtwicon"><i class="fa fa-pencil-square-o mr-1" aria-hidden="true"></i> Your Account was created in&nbsp; <span class="font-weight-bold"> {{ date('F j, Y - g:i A', strtotime(auth()->user()->created_at))}} </span></span>
                                                @else
                                                    <span class="cust_info_txtwicon font-italic text_svms_red"><i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> Account date created unknown </span>
                                                @endif
                                                @if(!is_null(auth()->user()->registered_by))
                                                    @php
                                                        $reg_by_info = App\Models\User::select('id', 'user_role', 'user_lname', 'user_fname')->where('id', auth()->user()->registered_by)->first();
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
                                            @endif
                                        </div>
                                    </div>
                                    {{-- account date created & registered by end --}}
                                </div>
                                @if($toLower_userStatus == 'active')
                                    {{-- edit profile --}}
                                    <div class="tab-pane fade" id="div_userEditProfile_tab{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_userEditProfile_tab{{auth()->user()->id}}">
                                        @if($toLower_userType === 'employee')
                                            <div class="card card_gbr shadow">
                                                <div class="card-body p-0">
                                                    <div class="card-header cb_p15x25">
                                                        <span class="sec_card_body_title">Edit Profile</span>
                                                        <span class="sec_card_body_subtitle">Click the <span class="font-weight-bold">'Save Changes'</span> button to update your profile.</span>
                                                    </div>
                                                    <form id="form_empUserUpdateOwnProfile" class="form" method="POST" action="{{route('profile.update_emp_user_own_profile')}}" enctype="multipart/form-data" onsubmit="update_empUserOwnProfileBtn.disabled = true; return true;">
                                                        @csrf
                                                        <div class="cb_px25 cb_pb15">
                                                            <div class="row d-flex justify-content-center">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 align-items-center">
                                                                    <div class="up_img_div text-center">
                                                                        <img class="{{ $image_filter }} up_user_image empOwn_imgUpld_targetImg shadow" src="{{$user_image_src}}" alt="{{$user_image_alt}}">
                                                                        {{-- <img class="up_user_image empOwn_imgUpld_targetImg shadow border-gray" src="{{asset('storage/svms/user_images/'.auth()->user()->user_image)}}" alt="{{auth()->user()->user_fname }} {{ auth()->user()->user_lname}}'s profile image'"> --}}
                                                                    </div>
                                                                    <div class="user_image_upload_input_div emp_imgUpload">
                                                                        <i class="nc-icon nc-image emp_imgUpld_TrgtBtn"></i>
                                                                        <input name="upd_emp_own_user_image" class="file_upload_input empOwn_img_imgUpld_fileInpt" type="file" accept="image/*"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <label for="upd_emp_own_email">Email Address</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-email-85" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_emp_own_email" name="upd_emp_own_email" type="text" class="form-control" @if(auth()->user()->email != 'null') value="{{auth()->user()->email}}" @else placeholder="Type Email Address" @endif required>
                                                                <span id="empEmailAvail_notice" class="d-none text-right">
    
                                                                </span>
                                                            </div>
                                                            <label for="upd_emp_own_id">Employee ID</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_emp_own_id" name="upd_emp_own_id" type="number" min="0" oninput="validity.valid||(value='');" class="form-control" @if(auth()->user()->user_sdca_id != 'null') value="{{auth()->user()->user_sdca_id}}" @else placeholder="Type Employee ID" @endif required>
                                                            </div>
                                                            <label for="upd_emp_own_lname">Last Name</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-single-02"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_emp_own_lname" name="upd_emp_own_lname" type="text" class="form-control" @if(auth()->user()->user_lname != 'null') value="{{auth()->user()->user_lname}}" @else placeholder="Type Last Name" @endif required>
                                                            </div>
                                                            <label for="upd_emp_own_fname">First Name</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-single-02"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_emp_own_fname" name="upd_emp_own_fname" type="text" class="form-control" @if(auth()->user()->user_fname != 'null') value="{{auth()->user()->user_fname}}" @else placeholder="Type First Name" @endif required>
                                                            </div>
                                                            <label for="upd_emp_own_fname">Gender</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-single-02"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_emp_own_gender" list="updateGenderOptions" pattern="Male|Female" name="upd_emp_own_gender" type="text" class="form-control" @if(auth()->user()->user_gender != 'null') value="{{ucfirst(auth()->user()->user_gender)}}" @else placeholder="Select Gender" @endif required>
                                                                <datalist id="updateGenderOptions">
                                                                    <option value="Male">
                                                                    <option value="Female">
                                                                </datalist>
                                                            </div>
                                                            <label for="upd_emp_own_jobdesc">Job Description</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-briefcase-24" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_emp_own_jobdesc" name="upd_emp_own_jobdesc" type="text" class="form-control" @if($user_emp_info->uEmp_job_desc != 'null') value="{{$user_emp_info->uEmp_job_desc}}" @else placeholder="Type Job Position" @endif required>
                                                            </div>
                                                            <label for="upd_emp_own_dept">Department</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-bank" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_emp_own_dept" name="upd_emp_own_dept" type="text" class="form-control" @if($user_emp_info->uEmp_dept != 'null') value="{{$user_emp_info->uEmp_dept}}" @else placeholder="Type Department" @endif required>
                                                            </div>
                                                            <label for="upd_emp_own_phnum">Phone NUmber</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="fa fa-mobile" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input name="upd_emp_own_phnum" type="number" pattern="[0-9]{11}" min="0" oninput="validity.valid||(value='');" class="form-control" @if($user_emp_info->uEmp_phnum != 'null') value="{{$user_emp_info->uEmp_phnum}}" @else placeholder="Type Contact Number" @endif required>
                                                            </div>
                                                            <div class="d-flex justify-content-center">
                                                                <input type="hidden" name="own_user_id" id="own_user_id" value="{{auth()->user()->id}}"/>
                                                                <button type="submit" id="update_empUserOwnProfileBtn" class="btn btn_svms_blue btn-round btn_show_icon" disabled>{{ __('Save Changes') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @elseif($toLower_userType === 'student')
                                            <div class="card card_gbr shadow">
                                                <div class="card-body p-0">
                                                    <div class="card-header cb_p15x25">
                                                        <span class="sec_card_body_title">Edit Profile</span>
                                                        <span class="sec_card_body_subtitle">Click the <span class="font-weight-bold">'Save Changes'</span> button to save the changes you've made and this will update your profile.</span>
                                                    </div>
                                                    <form id="form_studUpdateOwnProfile" class="form" method="POST" action="{{route('profile.update_stud_user_own_profile')}}" enctype="multipart/form-data" onsubmit="update_studUserOwnProfileBtn.disabled = true; return true;">
                                                        @csrf
                                                        <div class="cb_px25 cb_pb15">
                                                            <div class="row d-flex justify-content-center">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 align-items-center">
                                                                    <div class="up_img_div text-center">
                                                                        <img class="{{ $image_filter }} up_stud_user_image studOwn_imgUpld_targetImg shadow" src="{{$user_image_src}}" alt="{{$user_image_alt}}">
                                                                        {{-- <img class="up_stud_user_image studOwn_imgUpld_targetImg shadow border-gray" src="{{asset('storage/svms/user_images/'.auth()->user()->user_image)}}" alt="{{auth()->user()->user_fname }} {{ auth()->user()->user_lname}}'s profile image'"> --}}
                                                                    </div>
                                                                    <div class="user_image_upload_input_div stud_imgUpload">
                                                                        <i class="nc-icon nc-image stud_imgUpld_TrgtBtn"></i>
                                                                        <input name="upd_stud_own_user_image" class="file_upload_input studOwn_img_imgUpld_fileInpt" type="file" accept="image/*"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <label for="upd_stud_own_email">Email Address</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-email-85" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_stud_own_email" name="upd_stud_own_email" type="text" class="form-control" @if(auth()->user()->email != 'null') value="{{auth()->user()->email}}" @else placeholder="Type Email Address" @endif required>
                                                                <span id="studEmailAvail_notice" class="d-none text-right">
    
                                                                </span>
                                                            </div>
                                                            <label for="upd_stud_own_id">Student Number</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_stud_own_id" name="upd_stud_own_id" type="number" min="0" oninput="validity.valid||(value='');" class="form-control" @if(auth()->user()->user_sdca_id != 'null') value="{{auth()->user()->user_sdca_id}}" @else placeholder="Type Student Number" @endif required>
                                                            </div>
                                                            <label for="upd_stud_own_lname">Last Name</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-single-02"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_stud_own_lname" name="upd_stud_own_lname" type="text" class="form-control" @if(auth()->user()->user_lname != 'null') value="{{auth()->user()->user_lname}}" @else placeholder="Type Last Name" @endif required>
                                                            </div>
                                                            <label for="upd_stud_own_fname">First Name</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-single-02"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_stud_own_fname" name="upd_stud_own_fname" type="text" class="form-control" @if(auth()->user()->user_fname != 'null') value="{{auth()->user()->user_fname}}" @else placeholder="Type First Name" @endif required>
                                                            </div>
                                                            <label for="upd_stud_own_gender">Gender</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-single-02"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_stud_own_gender" list="updateStudGenderOptions" pattern="Male|Female" name="upd_stud_own_gender" type="text" class="form-control" @if(auth()->user()->user_gender != 'null') value="{{ucfirst(auth()->user()->user_gender)}}" @else placeholder="Select Gender" @endif required>
                                                                <datalist id="updateStudGenderOptions">
                                                                    <option value="Male">
                                                                    <option value="Female">
                                                                </datalist>
                                                            </div>
                                                            <label for="upd_stud_own_school">School</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_stud_own_school" list="updateStudSchoolOptions" pattern="SASE|SBCS|SIHTM|SHSP" name="upd_stud_own_school" type="text" class="form-control" @if($user_stud_info->uStud_school != 'null') value="{{$user_stud_info->uStud_school}}" @else placeholder="Type Your School" @endif required>
                                                                <datalist id="updateStudSchoolOptions">
                                                                    <option value="SASE">
                                                                    <option value="SBCS">
                                                                    <option value="SIHTM">
                                                                    <option value="SHSP">
                                                                </datalist>
                                                            </div>
                                                            <label for="upd_stud_own_program">Program</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_stud_own_program" list="updateStudProgramOptions" pattern="BS Psychology|BS Education|BA Communication|BSBA|BSA|BSIT|BSCS|BMA|BSHM|BSTM|BS Biology|BS Pharmacy|BS Radiologic Technology|BS Physical Therapy|BS Medical Technology|BS Nursing" name="upd_stud_own_program" type="text" class="form-control" @if($user_stud_info->uStud_program != 'null') value="{{$user_stud_info->uStud_program}}" @else placeholder="Type Your Program" @endif required>
                                                                <datalist id="updateStudProgramOptions">
                                                                    
                                                                </datalist>
                                                            </div>
                                                            <label for="upd_stud_own_yearlvl">Year Level</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_stud_own_yearlvl" list="updateStudYearlvlOptions" pattern="FIRST YEAR|SECOND YEAR|THIRD YEAR|FOURTH YEAR|FIFTH YEAR" name="upd_stud_own_yearlvl" type="text" class="form-control" @if($user_stud_info->uStud_yearlvl != 'null') value="{{$user_stud_info->uStud_yearlvl}}" @else placeholder="Type Your Year Level" @endif required>
                                                                <datalist id="updateStudYearlvlOptions">
    
                                                                </datalist>
                                                            </div>
                                                            <label for="upd_stud_own_section">Section</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_stud_own_section" name="upd_stud_own_section" type="text" class="form-control" @if($user_stud_info->uStud_section != 'null') value="{{$user_stud_info->uStud_section}}" @else placeholder="Type Your Section" @endif required>
                                                            </div>
                                                            <label for="upd_stud_own_phnum">Phone NUmber</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="fa fa-mobile" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="upd_stud_own_phnum" name="upd_stud_own_phnum" type="number" pattern="[0-9]{11}" min="0" oninput="validity.valid||(value='');" class="form-control" @if($user_stud_info->uStud_phnum != 'null') value="{{$user_stud_info->uStud_phnum}}" @else placeholder="Type Contact Number" @endif required>
                                                            </div>
                                                            <div class="d-flex justify-content-center">
                                                                <input type="hidden" name="own_user_id" id="own_user_id" value="{{auth()->user()->id}}"/>
                                                                <button type="submit" id="update_studUserOwnProfileBtn" class="btn btn-success btn-round btn_show_icon" disabled>{{ __('Save Changes') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @else
    
                                        @endif
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <span class="cust_info_txtwicon"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i>The System will notify you of all the changes you've made thru your registered email address. If you switched to a new email address, you will be logged out from the system and you need to log in again using the new email.</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- change password --}}
                                    <div class="tab-pane fade" id="div_userChangePassword_tab{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_userChangePassword_tab{{auth()->user()->id}}">
                                        <div class="card card_gbr shadow">
                                            <div class="card-body p-0">
                                                <div class="card-header cb_p15x25">
                                                    <span class="sec_card_body_title">Change Password</span>
                                                    <span class="sec_card_body_subtitle">Type your old password first for password change.</span>
                                                </div>
                                                <form class="form" method="POST" action="{{route('profile.update_my_password')}}" enctype="multipart/form-data" onsubmit="change_myPass_btn.disabled = true; return true;">
                                                    @csrf
                                                    <div class="cb_px25 cb_pb15">
                                                        <div class="light_backDrop_card mb-2">
                                                            <label for="my_oldPass_input">Type Old Password First</label>
                                                            <div class="input-group paswrd_inpt_fld">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-key-25" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input type="password" id="my_oldPass_input" name="my_oldPass_input" class="form-control" placeholder="Type your current password" required>
                                                                <i class="fa fa-eye" id="toggleMyOldPassword"></i>
                                                            </div>
                                                            <span id="myOldPass_notice" class="d-none text-right">
    
                                                            </span>
                                                        </div>
                                                        <div class="light_backDrop_card mb-2">
                                                            <label for="upd_myNew_password">Type New Password <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Include numbers, symbols, and uppercase and lowercase letters to have a strong password."></i></label>
                                                            <div class="input-group paswrd_inpt_fld">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-key-25" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input onkeyup="check_my_pass_strenght()" type="password" id="upd_myNew_password" name="upd_myNew_password" class="form-control" placeholder="Type a new password" required disabled>
                                                                <i class="fa fa-eye" id="toggleMyNewPassword"></i>
                                                            </div>
                                                            <div class="pass_strenght_indicator_div d-none">
                                                                <span class="weak"></span>
                                                                <span class="medium"></span>
                                                                <span class="strong"></span>
                                                            </div>
                                                            <div id="pass_strenght_txt">
    
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-center ">
                                                            <input type="hidden" name="selected_user_own_id" value="{{auth()->user()->id}}"/>
                                                            <button id="change_myPass_btn" type="submit" class="btn btn-success btn-round btn_show_icon" disabled>Update My Password<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <span class="cust_info_txtwicon"><i class="nc-icon nc-circle-10 mr-1" aria-hidden="true"></i>The System will notify you of the changes your made to your password thru your registered email address.</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
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
                <div class="accordion gCardAccordions" id="userActivityLogsCollapse_{{auth()->user()->id}}">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="headingOne">
                            <button id="actLogs_collapseBtnToggle" class="btn btn-link btn-block custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#collapse_userActivityLogs_{{auth()->user()->id}}" aria-expanded="true" aria-controls="collapse_userActivityLogs_{{auth()->user()->id}}">
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
                        <div id="collapse_userActivityLogs_{{auth()->user()->id}}" class="collapse gCardAccordions_collapse show p-0" aria-labelledby="headingOne" data-parent="#userActivityLogsCollapse_{{auth()->user()->id}}">
                            <div class="card-body cb_t0b15x25">
                                @php
                                    // date formats for #myActLogsFiltr_datepickerRange placeholder
                                    $my_first_record_date = date('F d, Y (D - g:i A)', strtotime($my_first_record->created_at));
                                    $my_first_latest_date = date('F d, Y (D - g:i A)', strtotime($my_latest_record->created_at));
                                @endphp
                                <div class="row mb-2">
                                    <div class="col-lg-8 col-md-9 col-sm-12">
                                        <div class="cust_inputDiv_wIcon">
                                            <input id="myActLogsFiltr_datepickerRange" name="myActLogsFiltr_datepickerRange" type="text" class="form-control cust_inputv1 readOnlyClass" placeholder="{{$my_first_record_date}} to {{$my_first_latest_date}}" readonly />
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </div>
                                        <input type="hidden" name="myActLogs_hidden_dateRangeFrom" id="myActLogs_hidden_dateRangeFrom">
                                        <input type="hidden" name="myActLogs_hidden_dateRangeTo" id="myActLogs_hidden_dateRangeTo">
                                        <input type="hidden" name="al_hiddenTotalData_found" id="al_hiddenTotalData_found">
                                    </div>
                                    <div class="col-lg-4 col-md-3 col-sm-12">
                                        <div class="form-group cust_inputDiv_wIconv1">
                                            <select id="myActLogsFiltr_categories" class="form-control cust_selectDropdownBox1 drpdwn_arrow">
                                                <option value="0" selected>All Categories</option>
                                                @if(count($my_trans_categories) > 0)
                                                    @foreach ($my_trans_categories as $this_category)
                                                        <option value="{{$this_category->act_type}}">{{ucwords($this_category->act_type) }}</option>
                                                    @endforeach
                                                @endif
                                                
                                            </select>
                                            <i class="fa fa-list-ul" aria-hidden="true"></i>
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
                                            <tbody class="tbody_svms_white" id="al_tableTbody">
                                                {{-- ajax table --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row d-flex align-items-center">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <span>Total Data: <span class="font-weight-bold" id="al_tableTotalData_count"> </span> </span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-center">
                                        @csrf
                                        <input type="hidden" name="dateRangePicker_minDate" id="dateRangePicker_minDate" value="{{$my_first_record_date}}">
                                        <input type="hidden" name="dateRangePicker_maxDate" id="dateRangePicker_maxDate" value="{{$my_first_latest_date}}">
                                        <input type="hidden" name="al_hidden_page" id="al_hidden_page" value="1" />
                                        <div id="al_tablePagination">
                                            {{-- {{ $user_activities->links('pagination::bootstrap-4') }} --}}
                                        </div>
                                    </div>
                                </div>
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
{{-- user image uplaod --}}
    {{-- employee --}}
    <script>
        $(document).ready(function() {
            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('.empOwn_imgUpld_targetImg').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $(".empOwn_img_imgUpld_fileInpt").on('change', function(){
                readURL(this);
            });
            $(".emp_imgUpld_TrgtBtn").on('click', function() {
                $(".empOwn_img_imgUpld_fileInpt").click();
            });
        });
    </script>
    {{-- student --}}
    <script>
        $(document).ready(function() {
            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('.studOwn_imgUpld_targetImg').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $(".studOwn_img_imgUpld_fileInpt").on('change', function(){
                readURL(this);
            });
            $(".stud_imgUpld_TrgtBtn").on('click', function() {
                $(".studOwn_img_imgUpld_fileInpt").click();
            });
        });
    </script>
{{-- user image upload end --}}
{{-- display datalist options based on previous selected option --}}
    {{-- selected school --}}
    <script>
        $(document).ready(function() {
            $("#upd_stud_own_school").on("change paste keyup", function() {
                var selectedSchool = $(this).val();
                document.getElementById('upd_stud_own_program').value = '';
                document.getElementById('upd_stud_own_yearlvl').value = '';
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
            $("#upd_stud_own_program").on("change paste keyup", function() {
                var selectedProgram = $(this).val();
                document.getElementById('upd_stud_own_yearlvl').value = '';
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
{{-- email availability check --}}
    {{-- employee --}}
    <script>
        $(document).ready(function(){
            $('#upd_emp_own_email').blur(function(){
                var error_email = '';
                var emp_id = $('#own_user_id').val();
                var emp_email = $('#upd_emp_own_email').val();
                var _token = $('input[name="_token"]').val();
                var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if(!filter.test(emp_email)){    
                    $('#empEmailAvail_notice').removeClass('d-none');
                    $('#empEmailAvail_notice').addClass('invalid-feedback');
                    $('#empEmailAvail_notice').addClass('d-block');
                    $('#empEmailAvail_notice').html('<strong>Invalid Email Format!</strong>');
                    $('#upd_emp_own_email').addClass('is-invalid');
                }else{
                    $.ajax({
                        url:"{{ route('profile.emp_user_switch_new_email_availability_check') }}",
                        method:"POST",
                        data:{emp_id:emp_id, emp_email:emp_email, _token:_token},
                        success:function(result){
                            if(result == 'unique'){
                                $('#empEmailAvail_notice').removeClass('d-none');
                                $('#empEmailAvail_notice').removeClass('invalid-feedback');
                                $('#empEmailAvail_notice').addClass('valid-feedback');
                                $('#empEmailAvail_notice').html('<strong>Email Available.</strong>');
                                $('#upd_emp_own_email').removeClass('is-invalid');
                                $('#upd_emp_own_email').addClass('is-valid');
                            }else{
                                $('#empEmailAvail_notice').removeClass('d-none');
                                $('#empEmailAvail_notice').addClass('invalid-feedback');
                                $('#empEmailAvail_notice').addClass('d-block');
                                $('#empEmailAvail_notice').html('<strong>Email already in use!</strong>');
                                $('#upd_emp_own_email').addClass('is-invalid');
                                $('#update_empUserOwnProfileBtn').attr('disabled', 'disabled');
                            }
                        }
                    })
                }
            });
        });
    </script>
    {{-- student --}}
    <script>
        $(document).ready(function(){
            $('#upd_stud_own_email').blur(function(){
                var error_email = '';
                var stud_id = $('#own_user_id').val();
                var stud_email = $('#upd_stud_own_email').val();
                var _token = $('input[name="_token"]').val();
                var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if(!filter.test(stud_email)){    
                    $('#studEmailAvail_notice').removeClass('d-none');
                    $('#studEmailAvail_notice').addClass('invalid-feedback');
                    $('#studEmailAvail_notice').addClass('d-block');
                    $('#studEmailAvail_notice').html('<strong>Invalid Email Format!</strong>');
                    $('#upd_stud_own_email').addClass('is-invalid');
                }else{
                    $.ajax({
                        url:"{{ route('profile.stud_user_switch_new_email_availability_check') }}",
                        method:"POST",
                        data:{stud_id:stud_id, stud_email:stud_email, _token:_token},
                        success:function(result){
                            if(result == 'unique'){
                                $('#studEmailAvail_notice').removeClass('d-none');
                                $('#studEmailAvail_notice').removeClass('invalid-feedback');
                                $('#studEmailAvail_notice').addClass('valid-feedback');
                                $('#studEmailAvail_notice').html('<strong>Email Available.</strong>');
                                $('#upd_stud_own_email').removeClass('is-invalid');
                                $('#upd_stud_own_email').addClass('is-valid');
                            }else{
                                $('#studEmailAvail_notice').removeClass('d-none');
                                $('#studEmailAvail_notice').addClass('invalid-feedback');
                                $('#studEmailAvail_notice').addClass('d-block');
                                $('#studEmailAvail_notice').html('<strong>Email already in use!</strong>');
                                $('#upd_stud_own_email').addClass('is-invalid');
                                $('#update_studUserOwnProfileBtn').attr('disabled', 'disabled');
                            }
                        }
                    })
                }
            });
        });
    </script>
{{-- email availability check end --}}
{{-- disable/enable submit buttons of Edit Profile Forms --}}
    {{-- employee form --}}
    <script>
        $(window).on('load', function(e){
            $('#form_empUserUpdateOwnProfile').each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#update_empUserOwnProfileBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                var changedFiles = $( ":file" ).filter(function( index ) {
                    return this.value != this.defaultValue;
                }).length;
                if ( changedFiles > 0) {
                    $(this).find('#update_empUserOwnProfileBtn, input[type="file"]')
                        .prop('disabled', false);
                }
            }).find('#update_empUserOwnProfileBtn').prop('disabled', true);
        });
    </script>
    {{-- student form --}}
    <script>
        $(window).on('load', function(e){
            $('#form_studUpdateOwnProfile').each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#update_studUserOwnProfileBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                var changedFiles = $( ":file" ).filter(function( index ) {
                    return this.value != this.defaultValue;
                }).length;
                if ( changedFiles > 0) {
                    $(this).find('#update_studUserOwnProfileBtn, input[type="file"]')
                        .prop('disabled', false);
                }
            }).find('#update_studUserOwnProfileBtn').prop('disabled', true);
        });
    </script>
{{-- disable/enable submit buttons of Edit Profile Forms end --}}
{{-- toggle password input visibility --}}
    <script>
        const toggleMyOldPassword = document.querySelector('#toggleMyOldPassword');
        const my_oldPass_input = document.querySelector('#my_oldPass_input');
        toggleMyOldPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = my_oldPass_input.getAttribute('type') === 'password' ? 'text' : 'password';
            my_oldPass_input.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    <script>
        const toggleMyNewPassword = document.querySelector('#toggleMyNewPassword');
        const upd_myNew_password = document.querySelector('#upd_myNew_password');
        toggleMyNewPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = upd_myNew_password.getAttribute('type') === 'password' ? 'text' : 'password';
            upd_myNew_password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
{{-- toggle password input visibility end --}}
{{-- verify my old password --}}
    <script>
        $(document).ready(function(){
            $('#my_oldPass_input').blur(function(){
                var my_id = $('#selected_user_own_id').val();
                var my_old_pass = $('#my_oldPass_input').val();
                var _token = $('input[name="_token"]').val();
                if(my_old_pass !== ''){
                    // console.log(my_id);
                    // console.log(my_old_pass);
                    $.ajax({
                        url:"{{ route('profile.check_my_old_password') }}",
                        method:"POST",
                        data:{my_id:my_id, my_old_pass:my_old_pass, _token:_token},
                        success:function(result){
                            if(result === 'same'){
                                $('#myOldPass_notice').removeClass('d-none');
                                $('#myOldPass_notice').addClass('d-block');
                                $('#myOldPass_notice').removeClass('invalid-feedback');
                                $('#myOldPass_notice').addClass('valid-feedback');
                                $('#myOldPass_notice').html('<strong>Current Password Matched!</strong>');
                                $('#my_oldPass_input').removeClass('is-invalid');
                                $('#my_oldPass_input').addClass('is-valid');
                                $('#upd_myNew_password').prop('disabled', false);
                            }else{
                                $('#myOldPass_notice').removeClass('d-none');
                                $('#myOldPass_notice').addClass('d-block');
                                $('#myOldPass_notice').addClass('invalid-feedback');
                                $('#myOldPass_notice').html('<strong>Current Password does not Match!</strong>');
                                $('#my_oldPass_input').addClass('is-invalid');
                                $('#change_myPass_btn').attr('disabled', 'disabled');
                                $('#upd_myNew_password').prop('disabled', true);
                            }
                        }
                    });
                }
            });
        });
    </script>
{{-- verify my old password end --}}
{{-- password check strenght --}} 
    <script>
        const myNewPass_indicator  = document.querySelector(".pass_strenght_indicator_div");
        const myNewPass_input      = document.querySelector("#upd_myNew_password");
        const weak                 = document.querySelector(".weak");
        const medium               = document.querySelector(".medium");
        const strong               = document.querySelector(".strong");
        const text                 = document.querySelector("#pass_strenght_txt");
        const myNewPass_Btn_submit = document.querySelector("#change_myPass_btn");
        let regExpWeak             = /[a-z]/;
        let regExpMedium           = /\d+/;
        let regExpStrong           = /.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/;

        function check_my_pass_strenght(){
            if(myNewPass_input.value !== ""){
                myNewPass_indicator.classList.remove("d-none");
                myNewPass_indicator.style.display = "flex";
                if(myNewPass_input.value.length <= 3 && (myNewPass_input.value.match(regExpWeak) || myNewPass_input.value.match(regExpMedium) || myNewPass_input.value.match(regExpStrong)))no=1;
                if(myNewPass_input.value.length >= 6 && ((myNewPass_input.value.match(regExpWeak) && myNewPass_input.value.match(regExpMedium)) || (myNewPass_input.value.match(regExpMedium) && myNewPass_input.value.match(regExpStrong)) || (myNewPass_input.value.match(regExpWeak) && myNewPass_input.value.match(regExpStrong))))no=2;
                if(myNewPass_input.value.length >= 6 && myNewPass_input.value.match(regExpWeak) && myNewPass_input.value.match(regExpMedium) && myNewPass_input.value.match(regExpStrong))no=3;
                if(no===1){
                    weak.classList.add("active");
                    text.style.display = "block";
                    text.textContent   = "Your Password strength is too week";
                    text.classList.add("weak");
                }
                if(no===2){
                    medium.classList.add("active");
                    weak.classList.remove("active");
                    weak.classList.add("medium_bgColor");
                    text.textContent = "Your Password strength not too strong";
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
                    text.textContent = "Your password strength is strong";
                    text.classList.add("strong");
                    myNewPass_Btn_submit.disabled = false;
                }else{
                    strong.classList.remove("active");
                    text.classList.remove("strong");
                    weak.classList.remove("strong_bgColor");
                    medium.classList.remove("strong_bgColor");
                }
            }else{
                myNewPass_indicator.classList.add("d-none");
                text.style.display = "none";
                myNewPass_Btn_submit.disabled = true;
            }
        }
    </script>
{{-- password check strenght end --}}

{{-- ACTIVITY LOGS --}}
    <script>
        $(document).ready(function(){
            load_myActLogs_table();

            // function for ajax table pagination
                $(window).on('hashchange', function() {
                    if (window.location.hash) {
                        var page = window.location.hash.replace('#', '');
                        if (page == Number.NaN || page <= 0) {
                            return false;
                        }else{
                            getAlPage(page);
                        }
                    }
                });
                $(document).on('click', '.pagination a', function(event){
                    event.preventDefault();
                    var page = $(this).attr('href').split('page=')[1];
                    $('#al_hidden_page').val(page);
                    
                    load_myActLogs_table();
                    getAlPage(page);
                    $('li.page-item').removeClass('active');
                    $(this).parent('li.page-item').addClass('active');
                });
                function getAlPage(page){
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

            function load_myActLogs_table(){
                // get all filters' values
                var al_rangefrom = document.getElementById("myActLogs_hidden_dateRangeFrom").value;
                var al_rangeTo = document.getElementById("myActLogs_hidden_dateRangeTo").value;
                var al_category = document.getElementById("myActLogsFiltr_categories").value;
                var page = document.getElementById("al_hidden_page").value;

                console.log('');
                console.log('From date: ' + al_rangefrom);
                console.log('To Date: ' + al_rangeTo);
                console.log('Category: ' + al_category);
                console.log('page: ' + page);

                $.ajax({
                    url:"{{ route('profile.index') }}",
                    method:"GET",
                    data:{
                        al_rangefrom:al_rangefrom, 
                        al_rangeTo:al_rangeTo, 
                        al_category:al_category, 
                        page:page
                        },
                    dataType:'json',
                    success:function(al_data){
                        $('#al_tableTbody').html(al_data.al_table);
                        $('#al_tablePagination').html(al_data.vr_table_paginate);
                        $('#al_tableTotalData_count').html(al_data.vr_total_rows);
                        $('#al_hiddenTotalData_found').val(al_data.vr_total_data_found);

                        // for disabling/ enabling generate report button
                        // var violationRecs_totalData = document.getElementById("al_hiddenTotalData_found").value;
                        // if(violationRecs_totalData > 0){
                        //     $('#generateViolationRecs_btn').prop('disabled', false);
                        // }else{
                        //     $('#generateViolationRecs_btn').prop('disabled', true);
                        // }
                    }
                });
            }

            // daterange picker
                var vMinDate = document.getElementById("dateRangePicker_minDate").value;
                var vMaxDate = document.getElementById("dateRangePicker_maxDate").value;
                // console.log(vMinDate);
                // console.log(vMaxDate);
                $('#myActLogsFiltr_datepickerRange').daterangepicker({
                    timePicker: true,
                    showDropdowns: true,
                    minYear: vMinDate,
                    maxYear: parseInt(moment().format('YYYY'),10),
                    // maxDate: vMaxDate,
                    // minDate: vMinDate,
                    drops: 'down',
                    opens: 'right',
                    autoUpdateInput: false,
                    locale: {
                        format: 'MMMM DD, YYYY (ddd - hh:mm A)',
                        cancelLabel: 'Clear'
                        }
                });
                $('#myActLogsFiltr_datepickerRange').on('cancel.daterangepicker', function(ev, picker) {
                    document.getElementById("myActLogs_hidden_dateRangeFrom").value = '';
                    document.getElementById("myActLogs_hidden_dateRangeTo").value = '';
                    $(this).val('');
                    $(this).removeClass('cust_input_hasvalue');
                    // table paginatin set to 1
                    $('#al_hidden_page').val(1);
                    load_myActLogs_table();
                });
                $('#myActLogsFiltr_datepickerRange').on('apply.daterangepicker', function(ev, picker) {
                    // for hidden data range inputs
                    var start_range = picker.startDate.format('YYYY-MM-DD HH:MM:SS');
                    var end_range = picker.endDate.format('YYYY-MM-DD HH:MM:SS');
                    document.getElementById("myActLogs_hidden_dateRangeFrom").value = start_range;
                    document.getElementById("myActLogs_hidden_dateRangeTo").value = end_range;
                    // for date range display
                    $(this).val(picker.startDate.format('MMMM DD, YYYY (ddd - hh:mm A)') + ' to ' + picker.endDate.format('MMMM DD, YYYY (ddd - hh:mm A)'));
                    $(this).addClass('cust_input_hasvalue');
                    // table paginatin set to 1
                    $('#al_hidden_page').val(1);
                    load_myActLogs_table();
                });
            // daterange picker end

            // filter log categories
                $('#myActLogsFiltr_categories').on('change paste keyup', function(){
                    var selectedCategory = $(this).val();
                    if(selectedCategory != 0){
                        $(this).addClass('cust_input_hasvalue');
                    }else{
                        $(this).removeClass('cust_input_hasvalue');
                    }
                    // table paginatin set to 1
                    $('#al_hidden_page').val(1);
                    load_myActLogs_table();
                });
            // filter log categories end
        });
    </script>

@endpush