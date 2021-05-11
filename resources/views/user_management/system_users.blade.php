@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'system_users'
])

@section('content')
    <div class="content">
        {{-- notifications --}}
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
                <a href="{{ route('user_management.overview_users_management', 'overview_users_management') }}" class="directory_link">Users Management </a> <span class="directory_divider"> / </span> <a href="{{ route('user_management.system_users', 'system_users') }}" class="directory_active_link">System Users </a>
            </div>
        </div>

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">System Users</span>
                            <span class="page_intro_subtitle">This page helps you manage system users where you can activate/deactivate their account from accessing the system, edit/update/delete user account information, view user's profile and activity logs, and create new user accounts or system roles.</span>
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/um_create_users_2_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="accordion" id="systemUsersCollapseParent">
                            <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                                <div class="card-header p-0" id="systemUsersCollapseHeading">
                                    <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#systemUsersCollapseDiv" aria-expanded="true" aria-controls="systemUsersCollapseDiv">
                                        <div>
                                            <span class="card_body_title">Registered Users</span>
                                            <span class="card_body_subtitle">View System Users and their statuses</span>
                                        </div>
                                        <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                                    </button>
                                </div>
                                <div id="systemUsersCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="systemUsersCollapseHeading" data-parent="#systemUsersCollapseParent">
                                    <div class="row mb-3">
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <div class="input-group cust_srchInpt_div">
                                                <input id="search_user" name="search_user" type="text" class="form-control cust_srchUsersInpt_box" placeholder="Search User" />
                                                <i class="nc-icon nc-zoom-split" aria-hidden="true"></i>    
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-12 d-flex align-items-center">
                                            {{-- <span class="usrts_span" id="matched_searches"> </span> --}}
                                            {{-- <span>Total Data: <span class="font-weight-bold font-italic" id="total_users"> </span> </span> --}}
        
                                            {{-- <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-6 pr-0">
                                                    <div class="form-group cust_fltr_dropdowns_div w-100">
                                                        @if($count_total_roles > 0)
                                                            @php
                                                                $select_sysRoles = App\Models\Userroles::select('uRole_id', 'uRole', 'assUsers_count')->get();
                                                            @endphp
                                                            <select class="form-control cust_fltr_dropdowns drpdwn_arrow br_lft_sde">
                                                                <option selected="all_roles">All Roles</option>
                                                                @foreach ($select_sysRoles as $option_sysRoles)
                                                                    <option value="employee_users">{{ucwords($option_sysRoles->uRole)}}@if($option_sysRoles->assUsers_count > 1)s @endif</option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <select class="form-control cust_fltr_dropdowns drpdwn_arrow br_lft_sde shadow" disabled>
                                                                <option selected="all">No Roles Found</option>
                                                            </select>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-6 px-1">
                                                    <div class="form-group cust_fltr_dropdowns_div w-100">
                                                        <select class="form-control cust_fltr_dropdowns drpdwn_arrow">
                                                            <option selected="all_user_types">All User Types</option>
                                                            @if($count_employee_users > 0)
                                                                <option value="employee_users">Employee User<span>@if($count_employee_users > 1)s @endif</span></option>
                                                            @endif
                                                            @if($count_student_users > 0)
                                                                <option value="student_users">Student User<span>@if($count_student_users > 1)s @endif</span></option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-6 pl-0 pr-1">
                                                    <div class="form-group cust_fltr_dropdowns_div w-100">
                                                        <select class="form-control cust_fltr_dropdowns drpdwn_arrow">
                                                            <option selected="all">All Genders</option>
                                                            <option>Male</option>
                                                            <option>Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-6 pl-0">
                                                    <div class="form-group cust_fltr_dropdowns_div w-100">
                                                        <select class="form-control cust_fltr_dropdowns drpdwn_arrow br_rgt_sde">
                                                            <option selected="all">All Status</option>
                                                            <option>Active</option>
                                                            <option>Deactivated</option>
                                                            <option>Deleted</option>
                                                            <option>Pending</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <table class="table table-hover cust_table shadow">
                                                <thead class="thead_svms_blue">
                                                    <tr>
                                                        <th class="pl12">~ User</th>
                                                        <th>ID Number</th>
                                                        <th>Role</th>
                                                        <th>Type</th>
                                                        <th>Sex</th>
                                                        <th>Account Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tbody_svms_white" id="sys_users_tbl">
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
                                            <a href="{{ route('user_management.create_users', 'create_users') }}" class="btn btn-success cust_bt_links shadow" role="button"><i class="nc-icon nc-simple-add mr-1" aria-hidden="true"></i> Create New User</a>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="card-footer cb_t0b15x25">
                                    bottom line
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <span class="cust_info_txtwicon font-weight-bold"><i class="nc-icon nc-circle-10 mr-1" aria-hidden="true"></i> {{ $count_registered_users }} Registered @if($count_registered_users > 1) Users @else User @endif found.</span>
                                            @if($count_active_users > 0)
                                                <span class="cust_info_txtwicon"><i class="fa fa-toggle-on mr-1" aria-hidden="true"></i> {{ $count_active_users }} Active @if($count_active_users > 1) Users @else User @endif found.</span>
                                            @endif
                                            @if($count_deactivated_users > 0)
                                                <span class="cust_info_txtwicon"><i class="fa fa-toggle-off mr-1" aria-hidden="true"></i> {{ $count_deactivated_users }} Deactivated @if($count_deactivated_users > 1) Users @else User @endif found.</span>
                                            @endif
                                            @if($count_pending_users > 0)
                                                <span class="cust_info_txtwicon"><i class="fa fa-clock-o mr-1" aria-hidden="true"></i> {{ $count_pending_users }} Pending @if($count_pending_users > 1) Users @else User @endif found.</span>
                                            @endif
                                            @if($count_deleted_users > 0)
                                                <span class="cust_info_txtwicon"><i class="fa fa-trash mr-1" aria-hidden="true"></i> {{ $count_deleted_users }} Temporarily Deleted @if($count_deleted_users > 1) Users @else User @endif found.</span>
                                            @endif
                                        </div>
                                    </div>
                                    bottom line end
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-lg-3 col-md-4 col-sm-12">
            {{-- mini dash count for users --}}
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="accordion" id="userStatusDisplayCollapseParent">
                            <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                                <div class="card-header p-0" id="userStatusDisplayCollapseHeading">
                                    <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#userStatusDisplayCollapseDiv" aria-expanded="true" aria-controls="userStatusDisplayCollapseDiv">
                                        <div>
                                            <span class="card_body_title">Users Status</span>
                                            {{-- <span class="card_body_subtitle">s</span> --}}
                                        </div>
                                        <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                                    </button>
                                </div>
                                <div id="userStatusDisplayCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="userStatusDisplayCollapseHeading" data-parent="#userStatusDisplayCollapseParent">
                                    <div class="row mb-2">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <span class="cust_status_title mb-2">User Types <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="This system only has two user types: Employee and Student Type Users."></i></span>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-col-sm-6 pr_7">
                                                    <div class="su_dash_cards d-flex justify-content-between align-items-center">
                                                        <div class="su_dash_cards_icon">
                                                            <i class="nc-icon nc-circle-10 text_svms_blue"></i>
                                                        </div>
                                                        <div class="su_dash_cards_text">
                                                            <span class="cat_title">Employees</span>
                                                            <span class="cat_count">{{$count_employee_users}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 pl_7">
                                                    <div class="su_dash_cards d-flex justify-content-between align-items-center">
                                                        <div class="su_dash_cards_icon">
                                                            <i class="nc-icon nc-circle-10 text-success"></i>
                                                        </div>
                                                        <div class="su_dash_cards_text">
                                                            <span class="cat_title">Students</span>
                                                            <span class="cat_count">{{$count_student_users}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-lg-6 col-md-col-sm-6 pr_7">
                                                    <div class="su_dash_cards shadow-none d-flex justify-content-between align-items-center">
                                                        <div class="su_dash_cards_icon">
                                                            <i class="fa fa-male text_svms_blue"></i>
                                                        </div>
                                                        <div class="su_dash_cards_text">
                                                            <span class="cat_title">Males</span>
                                                            <span class="cat_count">{{$count_male_users}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 pl_7">
                                                    <div class="su_dash_cards shadow-none d-flex justify-content-between align-items-center">
                                                        <div class="su_dash_cards_icon">
                                                            <i class="fa fa-female text-success"></i>
                                                        </div>
                                                        <div class="su_dash_cards_text">
                                                            <span class="cat_title">Females</span>
                                                            <span class="cat_count">{{$count_female_users}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4 mb-2">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <span class="cust_status_title mb-2">Accounts Status <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="This system only has two user types: Employee and Student Type Users."></i></span>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-col-sm-6 pr_7">
                                                    <div class="su_dash_cards d-flex justify-content-between align-items-center">
                                                        <div class="su_dash_cards_icon">
                                                            <i class="fa fa-toggle-on text-success"></i>
                                                        </div>
                                                        <div class="su_dash_cards_text">
                                                            <span class="cat_title">Active</span>
                                                            <span class="cat_count">{{$count_active_users}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 pl_7">
                                                    <div class="su_dash_cards d-flex justify-content-between align-items-center">
                                                        <div class="su_dash_cards_icon">
                                                            <i class="fa fa-toggle-off text_svms_red"></i>
                                                        </div>
                                                        <div class="su_dash_cards_text">
                                                            <span class="cat_title">Deactivated</span>
                                                            <span class="cat_count">{{$count_deactivated_users}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-lg-6 col-md-col-sm-6 pr_7">
                                                    <div class="su_dash_cards d-flex justify-content-between align-items-center">
                                                        <div class="su_dash_cards_icon">
                                                            <i class="fa fa-clock-o text_svms_gray"></i>
                                                        </div>
                                                        <div class="su_dash_cards_text">
                                                            <span class="cat_title">Pending</span>
                                                            <span class="cat_count">{{$count_pending_users}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 pl_7">
                                                    <div class="su_dash_cards d-flex justify-content-between align-items-center">
                                                        <div class="su_dash_cards_icon">
                                                            <i class="fa fa-trash-o text_svms_red"></i>
                                                        </div>
                                                        <div class="su_dash_cards_text">
                                                            <span class="cat_title">Deleted</span>
                                                            <span class="cat_count">{{$count_deleted_users}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <span class="cust_info_txtwicon"><i class="nc-icon nc-circle-10 mr-1" aria-hidden="true"></i> {{ $count_registered_users }} Registered @if($count_registered_users > 1) Users @else User @endif found.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- mini dash count for users end --}}
            {{-- system roles display --}}
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="accordion" id="systemRolesDisplayCollapseParent">
                            <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                                <div class="card-header p-0" id="systemRolesDisplayCollapseHeading">
                                    <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#systemRolesDisplayCollapseDiv" aria-expanded="true" aria-controls="systemRolesDisplayCollapseDiv">
                                        <div>
                                            <span class="card_body_title">System Roles</span>
                                            {{-- <span class="card_body_subtitle">s</span> --}}
                                        </div>
                                        <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                                    </button>
                                </div>
                                <div id="systemRolesDisplayCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="systemRolesDisplayCollapseHeading" data-parent="#systemRolesDisplayCollapseParent">
                                    <div class="row mb-2">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <span class="cust_status_title">Active System Roles <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Below System Roles are assigned to specific users for access controls."></i></span>
                                        </div>
                                    </div>
                                {{-- active roles --}}
                                    @if($count_active_roles > 0)
                                        @php
                                            $active_roles = App\Models\Userroles::select('uRole_id', 'uRole')->where('uRole_status', 'active')->get();
                                        @endphp
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 m-0">
                                                <div class="card cust_listCard shadow">
                                                @foreach($active_roles->sortBy('uRole_id') as $active_role)
                                                    @php
                                                        $count_assigned_users = App\Models\Users::where('user_role', $active_role->uRole)->count();
                                                    @endphp
                                                        <div class="card-header cust_listCard_header3">
                                                            <div>
                                                                <span class="accordion_title">{{$active_role->uRole}}@if($count_assigned_users > 1)s @endif</span>
                                                                @if($count_assigned_users < 1)
                                                                <span class="font-italic text_svms_red"> No Assigned Users. </span>
                                                                @endif
                                                                {{-- <span class="accordion_subtitle">@if($count_assigned_users > 0) {{$count_assigned_users }} Assigned @if($count_assigned_users > 1) Users @else User @endif Found. @else <span class="font-italic text_svms_red"> No Assigned Users. </span> @endif</span> --}}
                                                            </div>
                                                            <div class="assignedUsersCirclesDiv">
                                                                <?php
                                                                    if($count_assigned_users > 8){
                                                                        $get_only_8 = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $active_role->uRole)->take(8)->get();
                                                                        $more_count = $count_assigned_users - 8;
                                                                        foreach($get_only_8->sortBy('id') as $display_8userImgs){
                                                                            // tolower case user_type
                                                                            $tolower_uType = Str::lower($display_8userImgs->user_type);
                                                                            // user image handler
                                                                            if(!is_null($display_8userImgs->user_image) OR !empty($display_8userImgs->user_image)){
                                                                                $user_imgJpgFile = $display_8userImgs->user_image;
                                                                            }else{
                                                                                if($tolower_uType === 'employee'){
                                                                                    $user_imgJpgFile = 'employee_user_image.jpg';
                                                                                }elseif($tolower_uType === 'student'){
                                                                                    $user_imgJpgFile = 'student_user_image.jpg';
                                                                                }else{
                                                                                    $user_imgJpgFile = 'disabled_user_image.jpg';
                                                                                }
                                                                            }
                                                                            ?><img class="assignedUsersCirclesImgs2 whiteImg_border1" src="{{asset('storage/svms/user_images/'.$user_imgJpgFile)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $display_8userImgs->id) You @else {{$display_8userImgs->user_fname. ' ' .$display_8userImgs->user_lname}} @endif"> <?php
                                                                        }
                                                                        ?>
                                                                        <div class="moreImgsCounterDiv2" data-toggle="tooltip" data-placement="top" title="{{$more_count}} more @if($more_count > 1) users @else user @endif">
                                                                            <span class="moreImgsCounterTxt2">+{{$more_count}}</span>
                                                                        </div>
                                                                        <?php
                                                                    }else {
                                                                        $get_all_assigned_users = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $active_role->uRole)->get();
                                                                        foreach($get_all_assigned_users->sortBy('id') as $assigned_user) {
                                                                            // tolower case user_type
                                                                            $tolower_uType = Str::lower($assigned_user->user_type);
                                                                            // user image handler
                                                                            if(!is_null($assigned_user->user_image) OR !empty($assigned_user->user_image)){
                                                                                $user_imgJpgFile = $assigned_user->user_image;
                                                                            }else{
                                                                                if($tolower_uType === 'employee'){
                                                                                    $user_imgJpgFile = 'employee_user_image.jpg';
                                                                                }elseif($tolower_uType === 'student'){
                                                                                    $user_imgJpgFile = 'student_user_image.jpg';
                                                                                }else{
                                                                                    $user_imgJpgFile = 'disabled_user_image.jpg';
                                                                                }
                                                                            }
                                                                            ?> <img class="assignedUsersCirclesImgs2 whiteImg_border1" src="{{asset('storage/svms/user_images/'.$user_imgJpgFile)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $assigned_user->id) You @else {{$assigned_user->user_fname. ' ' .$assigned_user->user_lname}} @endif"> <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row mt-2">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="no_data_found_div">
                                                    <span class="no_data_found_txt">No Active Roles Found!</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                {{-- active roles end --}}
                                {{-- deactivated roles --}}
                                    @if($count_deactivated_roles > 0)
                                        <div class="row mt-4 mb-2">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <span class="cust_status_title">Deactivated System Roles <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Below are System Roles that have been deactivated on accessing the system."></i></span>
                                            </div>
                                        </div>
                                        @php
                                            $deactivated_roles = App\Models\Userroles::select('uRole_id', 'uRole')->where('uRole_status', 'deactivated')->get();
                                        @endphp
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 m-0">
                                                <div class="card cust_listCard shadow-none">
                                                @foreach($deactivated_roles->sortBy('uRole_id') as $deactivated_role)
                                                    @php
                                                        $count_assigned_users = App\Models\Users::where('user_role', $deactivated_role->uRole)->count();
                                                    @endphp
                                                        <div class="card-header cust_listCard_header3">
                                                            <div>
                                                                <span class="accordion_title">{{$deactivated_role->uRole}}@if($count_assigned_users > 1)'s @endif</span>
                                                                @if($count_assigned_users < 1)
                                                                <span class="font-italic text_svms_red"> No Assigned Users. </span>
                                                                @endif
                                                                {{-- <span class="accordion_subtitle">@if($count_assigned_users > 0) {{$count_assigned_users }} Assigned @if($count_assigned_users > 1) Users @else User @endif Found. @else <span class="font-italic text_svms_red"> No Assigned Users. </span> @endif</span> --}}
                                                            </div>
                                                            <div class="assignedUsersCirclesDiv">
                                                                <?php
                                                                    if($count_assigned_users > 8){
                                                                        $get_only_8 = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $deactivated_role->uRole)->take(8)->get();
                                                                        $more_count = $count_assigned_users - 8;
                                                                        foreach($get_only_8->sortBy('id') as $display_8userImgs){
                                                                            ?><img class="assignedUsersCirclesImgs2 gray_image_filter whiteImg_border1" src="{{asset('storage/svms/user_images/'.$display_8userImgs->user_image)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $display_8userImgs->id) You @else {{$display_8userImgs->user_fname. ' ' .$display_8userImgs->user_lname}} @endif"> <?php
                                                                        }
                                                                        ?>
                                                                        <div class="moreImgsCounterDiv2" data-toggle="tooltip" data-placement="top" title="{{$more_count}} more @if($more_count > 1) users @else user @endif">
                                                                            <span class="moreImgsCounterTxt2">+{{$more_count}}</span>
                                                                        </div>
                                                                        <?php
                                                                    }else {
                                                                        $get_all_assigned_users = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $deactivated_role->uRole)->get();
                                                                        foreach($get_all_assigned_users->sortBy('id') as $assigned_user) {
                                                                            ?> <img class="assignedUsersCirclesImgs2 gray_image_filter whiteImg_border1" src="{{asset('storage/svms/user_images/'.$assigned_user->user_image)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $assigned_user->id) You @else {{$assigned_user->user_fname. ' ' .$assigned_user->user_lname}} @endif"> <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                {{-- deactivated roles end --}}
                                {{-- pending roles --}}
                                    @if($count_pending_roles > 0)
                                        <div class="row mt-4 mb-2">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <span class="cust_status_title">Pending System Roles <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Below are System Roles that have nont been activated nor have been deactivated that needs action."></i></span>
                                            </div>
                                        </div>
                                        @php
                                            $pending_roles = App\Models\Userroles::select('uRole_id', 'uRole')->where('uRole_status', 'pending')->get();
                                        @endphp
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 m-0">
                                                <div class="card cust_listCard shadow-none">
                                                @foreach($pending_roles->sortBy('uRole_id') as $pending_role)
                                                    @php
                                                        $count_assigned_users = App\Models\Users::where('user_role', $pending_role->uRole)->count();
                                                    @endphp
                                                        <div class="card-header cust_listCard_header3">
                                                            <div>
                                                                <span class="accordion_title">{{$pending_role->uRole}}@if($count_assigned_users > 1)'s @endif</span>
                                                                @if($count_assigned_users < 1)
                                                                <span class="font-italic text_svms_red"> No Assigned Users. </span>
                                                                @endif
                                                                {{-- <span class="accordion_subtitle">@if($count_assigned_users > 0) {{$count_assigned_users }} Assigned @if($count_assigned_users > 1) Users @else User @endif Found. @else <span class="font-italic text_svms_red"> No Assigned Users. </span> @endif</span> --}}
                                                            </div>
                                                            <div class="assignedUsersCirclesDiv">
                                                                <?php
                                                                    if($count_assigned_users > 8){
                                                                        $get_only_8 = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $pending_role->uRole)->take(8)->get();
                                                                        $more_count = $count_assigned_users - 8;
                                                                        foreach($get_only_8->sortBy('id') as $display_8userImgs){
                                                                            ?><img class="assignedUsersCirclesImgs2 gray_image_filter whiteImg_border1" src="{{asset('storage/svms/user_images/'.$display_8userImgs->user_image)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $display_8userImgs->id) You @else {{$display_8userImgs->user_fname. ' ' .$display_8userImgs->user_lname}} @endif"> <?php
                                                                        }
                                                                        ?>
                                                                        <div class="moreImgsCounterDiv2" data-toggle="tooltip" data-placement="top" title="{{$more_count}} more @if($more_count > 1) users @else user @endif">
                                                                            <span class="moreImgsCounterTxt2">+{{$more_count}}</span>
                                                                        </div>
                                                                        <?php
                                                                    }else {
                                                                        $get_all_assigned_users = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $pending_role->uRole)->get();
                                                                        foreach($get_all_assigned_users->sortBy('id') as $assigned_user) {
                                                                            ?> <img class="assignedUsersCirclesImgs2 gray_image_filter whiteImg_border1" src="{{asset('storage/svms/user_images/'.$assigned_user->user_image)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $assigned_user->id) You @else {{$assigned_user->user_fname. ' ' .$assigned_user->user_lname}} @endif"> <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                {{-- pending roles end --}}
                                </div>
                                <div class="card-footer cb_t0b15x25">
                                    {{-- bottom line --}}
                                        <div class="row mt-2">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <span class="cust_info_txtwicon font-weight-bold"><i class="nc-icon nc-circle-10 mr-1" aria-hidden="true"></i> {{ $count_total_roles }} System @if($count_total_roles > 1) Roles @else Role @endif found.</span>
                                                @if($count_active_roles > 0)
                                                    <span class="cust_info_txtwicon"><i class="fa fa-toggle-on mr-1" aria-hidden="true"></i> {{ $count_active_roles }} Active System @if($count_active_roles > 1) Roles @else Role @endif found.</span>
                                                @endif
                                                @if($count_deactivated_roles > 0)
                                                    <span class="cust_info_txtwicon"><i class="fa fa-toggle-off mr-1" aria-hidden="true"></i> {{ $count_deactivated_roles }} Deactivated System @if($count_deactivated_roles > 1) Roles @else Role @endif found.</span>
                                                @endif
                                                @if($count_empty_roles > 0)
                                                    <span class="cust_info_txtwicon"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> {{ $count_empty_roles }} System @if($count_empty_roles > 1) Roles @else Role @endif with no assigned user/s.</span>
                                                @endif
                                                @if($count_pending_roles > 0)
                                                    <span class="cust_info_txtwicon"><i class="fa fa-spinner mr-1" aria-hidden="true"></i> {{ $count_pending_roles }} Pending System @if($count_pending_roles > 1) Roles @else Role @endif found.</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <a href="{{ route('user_management.system_roles', 'system_roles') }}" class="btn btn_svms_blue cust_bt_links shadow" role="button"><i class="nc-icon nc-settings-gear-65 mr-1" aria-hidden="true"></i> Manage System Roles</a>
                                            </div>
                                        </div>
                                    {{-- bottom line end --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- system roles display end --}}
            </div>
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

@endsection

@push('scripts')
{{-- live search users --}}
    <script>
        $(document).ready(function(){
            fetch_searchUsers_results();
            function fetch_searchUsers_results(users_query = ''){
                $.ajax({
                    url:"{{ route('user_management.live_search_users_filter') }}",
                    method:"GET",
                    data:{users_query:users_query},
                    dataType:'json',
                    success:function(data){
                        $('#sys_users_tbl').html(data.sys_users_tbl_data);
                        $('#total_data_count').text(data.total_data_count);
                        $('#search_query').text(data.search_query);
                        $('#matched_searches').text(data.matched_searches);
                    }
                });
            }
            $(document).on('keyup', '#search_user', function(){
                var user_query = $(this).val();
                fetch_searchUsers_results(user_query);
            });
        });
    </script>
{{-- live search users end --}}

{{-- on dropdown change --}}
    <script>
        $(document).ready(function(){
            $(document).on('change', '.cust_fltr_dropdowns', function(){
                $(this).addClass("drpdwn_add_class");
            });
        });
    </script>
{{-- on dropdown change end --}}

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

@endpush