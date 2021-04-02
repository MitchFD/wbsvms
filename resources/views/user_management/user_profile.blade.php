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
                                    <a class="nav-link {{ $custom_nav_pill }} active" id="pills_userProfile_preview_tab_{{$user_data->id}}" data-toggle="pill" href="#userProfile_preview_{{$user_data->id}}" role="tab" aria-controls="userProfile_preview_{{$user_data->id}}" aria-selected="true">User Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $custom_nav_pill }}" id="pills_edit_userProfile_tab_{{$user_data->id}}" data-toggle="pill" href="#userProfile_edit_{{$user_data->id}}" role="tab" aria-controls="userProfile_edit_{{$user_data->id}}" aria-selected="false">Edit User Profile</a>
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
                                                }elseif($user_data->user_status === 'deactivated'){
                                                    // activate account
                                                    $btn_class  = "btn_svms_red";
                                                    $btn_label  = "Account is Deactivated";
                                                    $btn_icon   = "fa fa-toggle-off";
                                                    $btn_action = 'onclick=activateUserAccount(this.id)';
                                                }elseif($user_data->user_status === 'pending'){
                                                    $btn_class  = "btn-secondary";
                                                    $btn_label  = "Account is Pending";
                                                    $btn_icon   = "fa fa-spinner";
                                                    $btn_action = 'onclick=activateUserAccount(this.id)';
                                                }elseif($user_data->user_status === 'deleted'){
                                                    // user account is deleted - recover option
                                                    $btn_class  = "btn-secondary";
                                                    $btn_label  = "Account is Pending";
                                                    $btn_icon   = "fa fa-spinner";
                                                    $btn_action = 'onclick=activateUserAccount(this.id)';
                                                }else{
                                                    // just activate
                                                    $btn_class  = "btn_svms_red";
                                                    $btn_label  = "Account is Pending";
                                                    $btn_icon   = "fa fa-toggle-off";
                                                    $btn_action = 'onclick=activateUserAccount(this.id)';
                                                }
                                            }elseif($user_data->user_role_status === 'deactivated'){
                                                // activate role first
                                                $btn_class  = "btn_svms_red";
                                                $btn_label  = "Account is Deactivated";
                                                $btn_icon   = "fa fa-toggle-off";
                                                $btn_action = 'onclick=activateUserAccount(this.id)';
                                            }elseif($user_data->user_role_status === 'pending'){
                                                // manage role first
                                                $btn_class  = "btn-secondary";
                                                $btn_label  = "Account is Pending";
                                                $btn_icon   = "fa fa-spinner";
                                                $btn_action = 'onclick=manageRoleFirst(this.id)';
                                            }elseif($user_data->user_role_status === 'deleted'){
                                                // role is deleted - assign new role
                                                $btn_class  = "btn-secondary";
                                                $btn_label  = "Account is Pending";
                                                $btn_icon   = "fa fa-spinner";
                                                $btn_action = 'onclick=manageRoleFirst(this.id)';
                                            }else{
                                                // manage role first
                                                $btn_class  = "btn-secondary";
                                                $btn_label  = "Account is Pending";
                                                $btn_icon   = "fa fa-spinner";
                                                $btn_action = 'onclick=manageRoleFirst(this.id)';
                                            }
                                        // values for account status end
                                        @endphp     
                                        <div class="row d-flex justify-content-center mt-2 mb-4">
                                            <div class="col-lg-8 col-md-10 col-sm-11 p-0 d-flex justify-content-center">
                                                <div class="btn-group cust_btn_group" role="group" aria-label="User's Account Status / Action">
                                                    <button type="button" class="btn {{ $btn_class }} btn_group_label m-0">{{ $btn_label }}</button>
                                                    <button type="button" id="{{$user_data->id}}" {{ $btn_action }} class="btn {{ $btn_class }} btn_group_icon m-0"><i class="{{ $btn_icon }}"></i></button>
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
                                </div>
                            {{-- edit user informatin form --}}
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

@endpush