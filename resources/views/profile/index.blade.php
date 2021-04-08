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

        {{-- data customization --}}
            @php
                if(auth()->user()->user_type === 'employee'){
                    $user_emp_info   = App\Models\Useremployees::where('uEmp_id', auth()->user()->user_sdca_id)->first();
                    $custom_nav_pill = 'custom_nav_link_blue';
                    $sdca_num_text   = 'Employee ID';
                    if(auth()->user()->user_role_status === 'active'){
                        if(auth()->user()->user_status === 'active'){
                            $image_filter   = 'up_user_image';
                            $user_alt_image = 'employee_user_image';
                        }else{
                            if(auth()->user()->user_status === 'deactivated' OR auth()->user()->user_status === 'deleted'){
                                $image_filter   = 'up_red_user_image';
                                $user_alt_image = 'no_student_image';
                            }else{
                                $image_filter   = 'up_gray_user_image';
                                $user_alt_image = 'disabled_user_image';
                            }
                        }
                    }else{
                        if(auth()->user()->user_role_status === 'deactivated' OR auth()->user()->user_role_status === 'deleted'){
                            $image_filter   = 'up_red_user_image';
                            $user_alt_image = 'no_student_image';
                        }else{
                            $image_filter   = 'up_gray_user_image';
                            $user_alt_image = 'disabled_user_image';
                        }   
                    }
                }else if(auth()->user()->user_type === 'student'){
                    $user_stud_info  = App\Models\Userstudents::where('uStud_num', auth()->user()->user_sdca_id)->first();
                    $custom_nav_pill = 'custom_nav_link_green';
                    $sdca_num_text   = 'Student Number';
                    if(auth()->user()->user_role_status === 'active'){
                        if(auth()->user()->user_status === 'active'){
                            $image_filter   = 'up_stud_user_image';
                            $user_alt_image = 'student_user_image';
                        }else{
                            if(auth()->user()->user_status === 'deactivated' OR auth()->user()->user_status === 'deleted'){
                                $image_filter   = 'up_red_user_image';
                                $user_alt_image = 'no_student_image';
                            }else{
                                $image_filter   = 'up_gray_user_image';
                                $user_alt_image = 'disabled_user_image';
                            }
                        }
                    }else{
                        if(auth()->user()->user_role_status === 'deactivated'  OR auth()->user()->user_role_status === 'deleted'){
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
            @endphp
        {{-- data customization end --}}

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
                            <button id="profile_collapseBtnToggle" class="btn btn-link btn-block custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#collapse_userProfile" aria-expanded="true" aria-controls="collapse_userProfile">
                                <div>
                                    <span class="card_body_title">Account Information</span>
                                    <span class="card_body_subtitle">View and update your profile.</span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="collapse_userProfile" class="collapse show cb_t0b15x25" aria-labelledby="profileCollapseHeading" data-parent="#profileCollapse">
                            <ul class="nav nav-pills custom_nav_pills mt-0 mb-3 d-flex justify-content-center" id="user-pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ $custom_nav_pill }} active" id="pills_userProfile_tab{{auth()->user()->id}}" data-toggle="pill" href="#div_userProfile_tab{{auth()->user()->id}}" role="tab" aria-controls="div_userProfile_tab{{auth()->user()->id}}" aria-selected="true">Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $custom_nav_pill }}" id="pills_userEditProfile_tab{{auth()->user()->id}}" data-toggle="pill" href="#div_userEditProfile_tab{{auth()->user()->id}}" role="tab" aria-controls="div_userEditProfile_tab{{auth()->user()->id}}" aria-selected="false">Edit Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $custom_nav_pill }}" id="pills_userChangePassword_tab{{auth()->user()->id}}" data-toggle="pill" href="#div_userChangePassword_tab{{auth()->user()->id}}" role="tab" aria-controls="div_userChangePassword_tab{{auth()->user()->id}}" aria-selected="false">Change Password</a>
                                </li>
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
                                                    <img class="{{ $image_filter }} shadow"
                                                    @if(!is_null(auth()->user()->user_image))
                                                        src="{{asset('storage/svms/user_images/'.auth()->user()->user_image)}}" alt="{{auth()->user()->user_fname }} {{ auth()->user()->user_lname}}'s profile image'"
                                                    @else
                                                        src="{{asset('storage/svms/user_images/'.$user_alt_image.'.jpg')}}" alt="default employee user's profile image"
                                                    @endif
                                                    >
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
                                                @if(auth()->user()->user_type === 'student')
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
                                                @elseif(auth()->user()->user_type === 'employee')
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
                                                        <span class="up_info_txt mb-0"><i class="fa fa-male"></i> {{ auth()->user()->user_gender}}</span> 
                                                    @elseif(auth()->user()->user_gender === 'female')
                                                        <span class="up_info_txt mb-0"><i class="fa fa-female"></i> {{ auth()->user()->user_gender}}</span> 
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> gender unknown</span>
                                                    @endif
                                                @else
                                                    <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no gender</span>
                                                @endif
                                                {{-- account status --}}
                                                @php
                                                // values for account status
                                                    if(auth()->user()->user_role_status === 'active'){
                                                        if(auth()->user()->user_status === 'active'){
                                                            // deactivate account
                                                            $btn_class    = "btn-success";
                                                            $btn_label    = "Your Account is Activated";
                                                            $btn_icon     = "fa fa-toggle-on";
                                                            $acc_stat_txt = "Active";
                                                        }elseif(auth()->user()->user_status === 'deactivated'){
                                                            // activate account
                                                            $btn_class    = "btn_svms_red";
                                                            $btn_label    = "Your Account has been Deactivated";
                                                            $btn_icon     = "fa fa-toggle-off";
                                                            $acc_stat_txt = "Deactivated";
                                                        }elseif(auth()->user()->user_status === 'pending'){
                                                            $btn_class    = "btn-secondary";
                                                            $btn_label    = "Your Account is Pending";
                                                            $btn_icon     = "fa fa-spinner";
                                                            $acc_stat_txt = "Pending";
                                                        }elseif(auth()->user()->user_status === 'deleted'){
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
                                                    }elseif(auth()->user()->user_role_status === 'deactivated'){
                                                        // activate role first
                                                        $btn_class    = "btn_svms_red";
                                                        $btn_label    = "Your Account has been Deactivated";
                                                        $btn_icon     = "fa fa-toggle-off";
                                                        $acc_stat_txt = "Deactivated";
                                                    }elseif(auth()->user()->user_role_status === 'pending'){
                                                        // manage role first
                                                        $btn_class    = "btn-secondary";
                                                        $btn_label    = "Your Account is Pending";
                                                        $btn_icon     = "fa fa-spinner";
                                                        $acc_stat_txt = "Pending";
                                                    }elseif(auth()->user()->user_role_status === 'deleted'){
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
                                {{-- edit profile --}}
                                <div class="tab-pane fade" id="div_userEditProfile_tab{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_userEditProfile_tab{{auth()->user()->id}}">
                                    @if(auth()->user()->user_type === 'employee')
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
                                                                    <img class="up_user_image empOwn_imgUpld_targetImg shadow border-gray" src="{{asset('storage/svms/user_images/'.auth()->user()->user_image)}}" alt="{{auth()->user()->user_fname }} {{ auth()->user()->user_lname}}'s profile image'">
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
                                    @elseif(auth()->user()->user_type === 'student')

                                    @else

                                    @endif
                                </div>
                                {{-- change password --}}
                                <div class="tab-pane fade" id="div_userChangePassword_tab{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_userChangePassword_tab{{auth()->user()->id}}">
                                
                                </div>
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
{{-- user image upload end --}}
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
{{-- disable/enable submit buttons of Edit Profile Forms end --}}
@endpush