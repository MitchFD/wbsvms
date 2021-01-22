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
                <a href="{{ route('user_management.overview_users_management', 'overview_users_management') }}" class="directory_link">User Management </a> <span class="directory_divider"> / </span> <a href="{{ route('user_management.system_users', 'system_users') }}" class="directory_active_link">System Users </a>
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
                <div class="accordion" id="systemUsersCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="systemUsersCollapseHeading">
                            <button id="listRegUsers_collapseBtnToggle" class="btn btn-link btn-block custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#systemUsersCollapseDiv" aria-expanded="true" aria-controls="systemUsersCollapseDiv">
                                <div>
                                    <span class="card_body_title">Registered Users</span>
                                    <span class="card_body_subtitle">s</span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="systemUsersCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="systemUsersCollapseHeading" data-parent="#systemUsersCollapseParent">
                            <div class="row mb-3">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="input-group cust_srchInpt_div">
                                        <input id="search_user" name="search_user" type="text" class="form-control cust_srchUsersInpt_box" placeholder="Search User" />
                                        <i class="nc-icon nc-zoom-split" aria-hidden="true"></i>    
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6 col-md-6 col-sm-12 d-flex align-items-end justify-content-end">
                                    <span class="usrts_span">Total Data: &nbsp; <span class="font-weight-bold"> 10 users found</span> </span>
                                </div> --}}
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <table class="table table-hover cust_table shadow">
                                        <thead class="thead_svms_blue">
                                            <tr>
                                                <th class="p12">User</th>
                                                <th>Assigned Role</th>
                                                <th>User Type</th>
                                                <th>Account Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody_svms_white">
                                            <tr>
                                                <td class="p12">
                                                    <img class="rslts_userImgs rslts_emp" src="{{asset('storage/svms/user_images/mitch_04012021153254.jpg')}}" alt="user's image">
                                                    <span class="ml-3">Mitch Frankein Desierto</span>
                                                </td>
                                                <td>Administrator</td>
                                                <td>Employee</td>
                                                <td>Active</td>
                                            </tr>
                                            <tr>
                                                <td class="p12">
                                                    <img class="rslts_userImgs rslts_stud" src="{{asset('storage/svms/user_images/student_user_image.jpg')}}" alt="user's image">
                                                    <span class="ml-3">Johnny Bravo</span>
                                                </td>
                                                <td>Student Assistant</td>
                                                <td>Student</td>
                                                <td>Active</td>
                                            </tr>
                                            <tr>
                                                <td class="p12">
                                                    <img class="rslts_userImgs rslts_deact" src="{{asset('storage/svms/user_images/IMG20171215215448_07012021221224.jpg')}}" alt="user's image">
                                                    <span class="ml-3">Tony Stark</span>
                                                </td>
                                                <td>Security Guard</td>
                                                <td>Employee</td>
                                                <td>Deactivated</td>
                                            </tr>
                                            <tr>
                                                <td class="p12">
                                                    <img class="rslts_userImgs rslts_dele" src="{{asset('storage/svms/user_images/62217385_2420221778259719_1607356197306892288_n_07012021221945.jpg')}}" alt="user's image">
                                                    <span class="ml-3">Bruce Banner</span>
                                                </td>
                                                <td>Security Guard</td>
                                                <td>Employee</td>
                                                <td class="text_svms_red">Temporarily Deleted</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <span class="font-italic">Total Data: &nbsp; <span class="font-weight-bold"> 10 users found</span> </span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-end">
                                    <a href="{{ route('user_management.create_users', 'create_users') }}" class="btn btn-success cust_bt_links shadow" role="button"><i class="fa fa-user-plus mr-1" aria-hidden="true"></i> Create New User</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {{-- system roles display --}}
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="accordion" id="systemRolesDisplayCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="systemRolesDisplayCollapseHeading">
                            <button id="systemRolesDisplay_collapseBtnToggle" class="btn btn-link btn-block custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#systemRolesDisplayCollapseDiv" aria-expanded="true" aria-controls="systemRolesDisplayCollapseDiv">
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
                                    <span class="cust_status_title">Active System Roles <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Roles are assigned to specific users for access controls."></i></span>
                                </div>
                            </div>
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
                                                        <span class="accordion_title">{{$active_role->uRole}}@if($count_assigned_users > 1)'s @endif</span>
                                                        @if($count_assigned_users < 1)
                                                        <span class="font-italic text_svms_red"> No Assigned Users. </span>
                                                        @endif
                                                        {{-- <span class="accordion_subtitle">@if($count_assigned_users > 0) {{$count_assigned_users }} Assigned @if($count_assigned_users > 1) Users @else User @endif Found. @else <span class="font-italic text_svms_red"> No Assigned Users. </span> @endif</span> --}}
                                                    </div>
                                                    <div class="assignedUsersCirclesDiv">
                                                        <?php
                                                            if($count_assigned_users > 8){
                                                                $get_only_6 = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $active_role->uRole)->take(6)->get();
                                                                $more_count = $count_assigned_users - 8;
                                                                foreach($get_only_6->sortBy('id') as $display_8userImgs){
                                                                    ?><img class="assignedUsersCirclesImgs2 whiteImg_border1" src="{{asset('storage/svms/user_images/'.$display_8userImgs->user_image)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $display_8userImgs->id) You @else {{$display_8userImgs->user_fname. ' ' .$display_8userImgs->user_lname}} @endif"> <?php
                                                                }
                                                                ?>
                                                                <div class="moreImgsCounterDiv2" data-toggle="tooltip" data-placement="top" title="{{$more_count}} more users">
                                                                    <span class="moreImgsCounterTxt2">+{{$more_count}}</span>
                                                                </div>
                                                                <?php
                                                            }else {
                                                                $get_all_assigned_users = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $active_role->uRole)->get();
                                                                foreach($get_all_assigned_users->sortBy('id') as $assigned_user) {
                                                                    ?> <img class="assignedUsersCirclesImgs2 whiteImg_border1" src="{{asset('storage/svms/user_images/'.$assigned_user->user_image)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $assigned_user->id) You @else {{$assigned_user->user_fname. ' ' .$assigned_user->user_lname}} @endif"> <?php
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
                        </div>
                    </div>
                </div>
            </div>
        {{-- system roles display end --}}
        </div>
    </div>

    {{-- modals --}}

@endsection

@push('scripts')
@endpush