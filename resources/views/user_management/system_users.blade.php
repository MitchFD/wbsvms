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
                        <div class="accordion gCardAccordions" id="systemUsersCollapseParent">
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
                                <div id="systemUsersCollapseDiv" class="collapse gCardAccordions_collapse show cb_t0b15x25" aria-labelledby="systemUsersCollapseHeading" data-parent="#systemUsersCollapseParent">
                                    <div class="row mb-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="card card_gbr card_ofh shadow-none p-0 m-0 card_body_bg_gray2">
                                                @csrf
                                                <div class="card-body">
                                                    <div class="row mb-3">
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="form-group m-0 cust_inputDiv_wIconv1">
                                                                <select id="systemUsersFiltr_userRoles" class="form-control cust_selectDropdownBox1 drpdwn_arrow">
                                                                    <option value="0" data-default-role="all_roles" selected>All Roles</option>
                                                                    @php
                                                                        $query_all_roles = App\Models\Userroles::select('uRole_type', 'uRole')->get();
                                                                        $count_queryAllRoles = count($query_all_roles);
                                                                    @endphp
                                                                    @if($count_queryAllRoles > 0)
                                                                        @foreach($query_all_roles as $role_option)
                                                                            <option value="{{Str::lower($role_option->uRole)}}" data-role-type="{{Str::lower($role_option->uRole_type)}}">{{ucwords($role_option->uRole)}}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="form-group m-0 cust_inputDiv_wIconv1">
                                                                <select id="systemUsersFiltr_userTypes" class="form-control cust_selectDropdownBox1 drpdwn_arrow">
                                                                    <option value="0" selected>All User Types</option>
                                                                    <option value="employee">Employee Type Users</option>
                                                                    <option value="student">Student Type Users</option>
                                                                </select>
                                                                <i class="nc-icon nc-circle-10" aria-hidden="true"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="form-group m-0 cust_inputDiv_wIconv1">
                                                                <select id="systemUsersFiltr_userGenders" class="form-control cust_selectDropdownBox1 drpdwn_arrow">
                                                                    <option value="0" selected>All Genders</option>
                                                                    <option value="male">Male</option>
                                                                    <option value="female">Female</option>
                                                                </select>
                                                                <i class="nc-icon nc-single-02" aria-hidden="true"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="form-group m-0 cust_inputDiv_wIconv1">
                                                                <select id="systemUsersFiltr_userStatus" class="form-control cust_selectDropdownBox1 drpdwn_arrow">
                                                                    <option value="0" selected>All Status</option>
                                                                    <option value="active">Active</option>
                                                                    <option value="deactivated">Deactivated</option>
                                                                    <option value="pending">Pending</option>
                                                                    <option value="deleted">Deleted</option>
                                                                </select>
                                                                <i class="fa fa-toggle-off" aria-hidden="true"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                                            <div class="input-group cust_srchInpt_div">
                                                                <input id="search_user" name="search_user" type="text" class="form-control cust_srchUsersInpt_box" placeholder="Search User" />
                                                                <i class="nc-icon nc-zoom-split" aria-hidden="true"></i>    
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-12 d-flex justify-content-end align-items-end">
                                                            <a href="{{ route('user_management.create_users', 'create_users') }}" class="btn btn-success cust_bt_links shadow" role="button"><i class="nc-icon nc-simple-add mr-1" aria-hidden="true"></i> Create New User</a>
                                                            <button type="button" id="resetsystemUsersFilter_btn" class="btn btn_svms_blue cust_bt_links shadow ml-1" disabled><i class="fa fa-refresh mr-1" aria-hidden="true"></i> Reset</button>
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
                                            {{-- <a href="{{ route('user_management.create_users', 'create_users') }}" class="btn btn-success cust_bt_links shadow" role="button"><i class="nc-icon nc-simple-add mr-1" aria-hidden="true"></i> Create New User</a> --}}
                                            @csrf
                                            <input type="hidden" name="su_hidden_page" id="su_hidden_page" value="1" />
                                            <div id="su_tablePagination">

                                            </div>
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
                        <div class="accordion gCardAccordions" id="userStatusDisplayCollapseParent">
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
                                <div id="userStatusDisplayCollapseDiv" class="collapse gCardAccordions_collapse show cb_t0b15x25" aria-labelledby="userStatusDisplayCollapseHeading" data-parent="#userStatusDisplayCollapseParent">
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
                                </div>
                                <div class="card-footer cb_t0b15x25">
                                    {{-- bottom line --}}
                                        <div class="row mt-2">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <span class="cust_info_txtwicon font-weight-bold"><i class="nc-icon nc-circle-10 mr-1" aria-hidden="true"></i> {{ $count_registered_users }} Registered @if($count_registered_users > 1) Users @else User @endif found.</span>
                                                @if($count_active_users > 0)
                                                    <span class="cust_info_txtwicon"><i class="fa fa-toggle-on mr-1" aria-hidden="true"></i> {{ $count_active_users }} Active @if($count_active_users > 1) Users @else User @endif found.</span>
                                                @endif
                                                @if($count_deactivated_users > 0)
                                                    <span class="cust_info_txtwicon"><i class="fa fa-toggle-off mr-1" aria-hidden="true"></i> {{ $count_deactivated_users }} Deactivated @if($count_deactivated_users > 1) Users @else User @endif found.</span>
                                                @endif
                                                @if($count_pending_users > 0)
                                                    <span class="cust_info_txtwicon"><i class="fa fa fa-clock-o mr-1" aria-hidden="true"></i> {{ $count_pending_users }} Pending @if($count_pending_users > 1) Users @else User @endif found.</span>
                                                @endif
                                                @if($count_deleted_users > 0)
                                                    <span class="cust_info_txtwicon"><i class="fa fa-trash-o mr-1" aria-hidden="true"></i> {{ $count_deleted_users }} Deleted @if($count_deleted_users > 1) Users @else User @endif found.</span>
                                                @endif
                                            </div>
                                        </div>
                                    {{-- bottom line end --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- mini dash count for users end --}}
            {{-- system roles display --}}
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="accordion gCardAccordions" id="systemRolesDisplayCollapseParent">
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
                                <div id="systemRolesDisplayCollapseDiv" class="collapse gCardAccordions_collapse show cb_t0b15x25" aria-labelledby="systemRolesDisplayCollapseHeading" data-parent="#systemRolesDisplayCollapseParent">
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
                                                                            
                                                                            ?><img id="{{$display_8userImgs->id}}" class="assignedUsersCirclesImgs2 whiteImg_border1 cursor_pointer" src="{{asset('storage/svms/user_images/'.$user_imgJpgFile)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $display_8userImgs->id) You @else {{$display_8userImgs->user_fname. ' ' .$display_8userImgs->user_lname}} @endif"> <?php
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
                                                                            // onclick functions to view user's profiles
                                                                            if(auth()->user()->id == $assigned_user->id){
                                                                                $onClickFunct = 'onclick="viewMyProfile(this.id)"';
                                                                            }else{
                                                                                $onClickFunct = 'onclick="viewMyUserProfile(this.id)"';
                                                                            }
                                                                            ?> <img id="{{$assigned_user->id}}" {{ $onClickFunct }} class="assignedUsersCirclesImgs2 whiteImg_border1 cursor_pointer" src="{{asset('storage/svms/user_images/'.$user_imgJpgFile)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $assigned_user->id) You @else {{$assigned_user->user_fname. ' ' .$assigned_user->user_lname}} @endif"> <?php
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

    {{-- temporary delete user account modal --}}
        <div class="modal fade" id="temporaryDeleteUserAccountModal" tabindex="-1" role="dialog" aria-labelledby="temporaryDeleteUserAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="temporaryDeleteUserAccountModalLabel">Delete User Account?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="temporaryDeleteUserAccountModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- temporary delete user account modal end --}}

@endsection

@push('scripts')
{{-- live search users --}}
    {{-- <script>
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
    </script> --}}
{{-- live search users end --}}
    
{{-- filter system users table --}}
    <script>
        $(document).ready(function(){
            load_systemUsers_table();

            function load_systemUsers_table(){
                // get all filtered values
                var su_search = document.getElementById('search_user').value;
                var su_role = document.getElementById('systemUsersFiltr_userRoles').value;
                var su_type = document.getElementById('systemUsersFiltr_userTypes').value;
                var su_gender = document.getElementById('systemUsersFiltr_userGenders').value;
                var su_status = document.getElementById('systemUsersFiltr_userStatus').value;
                var page = document.getElementById("su_hidden_page").value;

                console.log('---------------------------');
                console.log('live search: ' + su_search);
                console.log('filter Role: ' + su_role);
                console.log('filter Type: ' + su_type);
                console.log('filter Gender: ' + su_gender);
                console.log('filter Status: ' + su_status);
                console.log('page: ' + page);

                $.ajax({
                    url:"{{ route('user_management.load_system_users_table') }}",
                    method:"GET",
                    data:{
                        su_search:su_search,
                        su_role:su_role,
                        su_type:su_type,
                        su_gender:su_gender,
                        su_status:su_status,
                        page:page
                    },
                    dataType:'json',
                    success:function(data){
                        $('#sys_users_tbl').html(data.sys_users_tbl_data);
                        $('#su_tablePagination').html(data.paginate);
                        $('#total_data_count').text(data.total_data_count);
                        $('#search_query').text(data.search_query);
                        $('#matched_searches').text(data.matched_searches);
                    }
                });

                // for disabling/ enabling reset filter button
                if(su_search != '' || su_role != 0 || su_type != 0 || su_gender != 0 || su_status != 0){
                    $('#resetsystemUsersFilter_btn').prop('disabled', false);
                }else{
                    $('#resetsystemUsersFilter_btn').prop('disabled', true);
                }
            }

            // function for ajax table pagination
                $(window).on('hashchange', function() {
                    if (window.location.hash) {
                        var page = window.location.hash.replace('#', '');
                        if (page == Number.NaN || page <= 0) {
                            return false;
                        }else{
                            getData(page);
                        }
                    }
                });
                $(document).on('click', '.pagination a', function(event){
                    event.preventDefault();
                    
                    var page = $(this).attr('href').split('page=')[1];
                    $('#su_hidden_page').val(page);

                    load_systemUsers_table();
                    getData(page);
                    $('li.page-item').removeClass('active');
                    $(this).parent('li.page-item').addClass('active');
                });
                function getData(page){
                    $.ajax(
                    {
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

            // live search
                $('#search_user').on('keyup', function(){
                    var liveSearchSystemUsers = $(this).val();
                    // add style to this input
                    if(liveSearchSystemUsers != ""){
                        $(this).addClass('cust_input_hasvalue');
                    }else{
                        $(this).removeClass('cust_input_hasvalue');
                    }
                    // table paginatin set to 1
                    $('#su_hidden_page').val(1);
                    // call load_systemUsers_table()
                    load_systemUsers_table();
                });
            // live search end

            // role filter
                $('#systemUsersFiltr_userRoles').on('change paste keyup', function(){
                    var selecteduRole = $(this).val();
                    // custom var
                    var employee = 'employee';
                    var student = 'student';
                    if(selecteduRole != 0){
                        $(this).addClass('cust_input_hasvalue');
                        // hide/show user type filter options based on selected role's uRole_type (employee or student)
                        var selectedData = $(this).find(':selected').attr('data-role-type');
                        if(selectedData === employee){
                            $('#systemUsersFiltr_userTypes').val(employee);
                        }else if(selectedData === student){
                            $('#systemUsersFiltr_userTypes').val(student);
                        }else{
                            $('#systemUsersFiltr_userTypes').val(0);
                        }
                        $('#systemUsersFiltr_userTypes').addClass('cust_input_hasvalue');
                    }else{
                        $(this).removeClass('cust_input_hasvalue');
                        $('#systemUsersFiltr_userTypes').removeClass('cust_input_hasvalue');
                        $('#systemUsersFiltr_userTypes').val(0);
                    }
                    // table paginatin set to 1
                    $('#su_hidden_page').val(1);
                    // call load_systemUsers_table()
                    load_systemUsers_table();
                });
            // role filter end

            // user types filter
                $('#systemUsersFiltr_userTypes').on('change paste keyup', function(){
                    var selecteduType = $(this).val();
                    // custom var
                    var all_roles = 'all_roles';
                    var employee = 'employee';
                    var student = 'student';
                    if(selecteduType != 0){
                        $(this).addClass('cust_input_hasvalue');
                        // hide/show user roles filter options based on selected user's uRole_type (employee or student)
                        if(selecteduType === employee){
                            $('#systemUsersFiltr_userRoles option[data-role-type="' + employee + '"]').show();
                            $('#systemUsersFiltr_userRoles option[data-role-type="' + student + '"]').hide();
                            $('#systemUsersFiltr_userRoles option[data-default-role="' + all_roles + '"]').html('All Employee Type Roles');
                            $('#systemUsersFiltr_userRoles').val(0);
                        }else if(selecteduType === student){
                            $('#systemUsersFiltr_userRoles option[data-role-type="' + employee + '"]').hide();
                            $('#systemUsersFiltr_userRoles option[data-role-type="' + student + '"]').show();
                            $('#systemUsersFiltr_userRoles option[data-default-role="' + all_roles + '"]').html('All Student Type Roles');
                            $('#systemUsersFiltr_userRoles').val(0);
                        }else{
                            $('#systemUsersFiltr_userRoles option[data-role-type="' + employee + '"]').hide();
                            $('#systemUsersFiltr_userRoles option[data-role-type="' + student + '"]').hide();
                            $('#systemUsersFiltr_userRoles option[data-default-role="' + all_roles + '"]').html('Select User Type');
                            $('#systemUsersFiltr_userRoles').val(0);
                        }
                        $('#systemUsersFiltr_userRoles').addClass('cust_input_hasvalue');
                    }else{
                        $(this).removeClass('cust_input_hasvalue');
                        $('#systemUsersFiltr_userRoles').removeClass('cust_input_hasvalue');
                        $('#systemUsersFiltr_userRoles').val(0);
                        $('#systemUsersFiltr_userRoles option[data-default-role="' + all_roles + '"]').html('All Roles');
                        $('#systemUsersFiltr_userRoles option[data-role-type="' + employee + '"]').show();
                        $('#systemUsersFiltr_userRoles option[data-role-type="' + student + '"]').show();
                    }
                    // table paginatin set to 1
                    $('#su_hidden_page').val(1);
                    // call load_systemUsers_table()
                    load_systemUsers_table();
                });
            // user types filter end

            // gender filter
                $('#systemUsersFiltr_userGenders').on('change paste keyup', function(){
                    var selectedGender = $(this).val();
                    if(selectedGender != 0){
                        $(this).addClass('cust_input_hasvalue');
                    }else{
                        $(this).removeClass('cust_input_hasvalue');
                    }
                    // table paginatin set to 1
                    $('#su_hidden_page').val(1);
                    // call load_systemUsers_table()
                    load_systemUsers_table();
                });
            // gender filter end

            // user status filter
                $('#systemUsersFiltr_userStatus').on('change paste keyup', function(){
                    var selecteduStatus = $(this).val();
                    if(selecteduStatus != 0){
                        $(this).addClass('cust_input_hasvalue');
                    }else{
                        $(this).removeClass('cust_input_hasvalue');
                    }
                    // table paginatin set to 1
                    $('#su_hidden_page').val(1);
                    // call load_systemUsers_table()
                    load_systemUsers_table();
                });
            // user status filter end

            // reset filter
                $('#resetsystemUsersFilter_btn').on('click', function(){
                    var employee = 'employee';
                    var student = 'student';
                    var all_roles = 'all_roles';
                    // table paginatin set to 1
                    $('#su_hidden_page').val(1);
                    // set value to search input = ''
                    document.getElementById("search_user").value = '';
                    document.getElementById("search_user").classList.remove("cust_input_hasvalue");
                    // Roles Filter
                    $('#systemUsersFiltr_userRoles').removeClass('cust_input_hasvalue');
                    $('#systemUsersFiltr_userRoles').val(0);
                    $('#systemUsersFiltr_userRoles option[data-default-role="' + all_roles + '"]').html('All Roles');
                    $('#systemUsersFiltr_userRoles option[data-role-type="' + employee + '"]').show();
                    $('#systemUsersFiltr_userRoles option[data-role-type="' + student + '"]').show();
                    // Types Filter
                    $('#systemUsersFiltr_userTypes').removeClass('cust_input_hasvalue');
                    $('#systemUsersFiltr_userTypes').val(0);
                    // Genders Filter
                    $('#systemUsersFiltr_userGenders').removeClass('cust_input_hasvalue');
                    $('#systemUsersFiltr_userGenders').val(0);
                    // Status Filter
                    $('#systemUsersFiltr_userStatus').removeClass('cust_input_hasvalue');
                    $('#systemUsersFiltr_userStatus').val(0);
                    // #resetsystemUsersFilter_btn
                    $(this).prop('disabled', true);
                    load_systemUsers_table();
                });
            // reset filter end
        });
    </script>
{{-- filter system users table --}}

{{-- view user's profile thru image circles --}}
    {{-- OWN PROFILE --}}
    <script>
        function viewMyProfile(view_my_user_id){
            var view_my_user_id = view_my_user_id;
            alert(view_my_user_id);
        }
    </script>
    {{-- USER's PROFILE --}}
    <script>
        function viewMyUserProfile(view_user_id){
            var view_user_id = view_user_id;
            alert(view_user_id);
        }
    </script>
{{-- view user's profile thru image circles end --}}

{{-- on dropdown change --}}
    <script>
        $(document).ready(function(){
            $(document).on('change', '.cust_fltr_dropdowns', function(){
                $(this).addClass("drpdwn_add_class");
            });
        });
    </script>
{{-- on dropdown change end --}}

{{-- delete user account open modal for confirmation --}}
    <script>
        function tempDeleteUserAccount(delete_user_id){
            var delete_user_id = delete_user_id;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user_management.temporary_delete_user_account_modal') }}",
                method:"GET",
                data:{delete_user_id:delete_user_id, _token:_token},
                success: function(data){
                    $('#temporaryDeleteUserAccountModalHtmlData').html(data); 
                    $('#temporaryDeleteUserAccountModal').modal('show');
                }
            });
        }
    </script>
{{-- delete user account open modal for confirmation --}}

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