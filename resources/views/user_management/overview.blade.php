@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'overview_users_management'
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
                <a href="{{ route('user_management.overview_users_management', 'overview_users_management') }}" class="directory_link">User Management </a> <span class="directory_divider"> / </span> <a href="{{ route('user_management.overview_users_management', 'overview_users_management') }}" class="directory_active_link">Overview </a>
            </div>
        </div>

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">Overview</span>
                            <span class="page_intro_subtitle">This page is an overview of registered users, system roles, and their statuses where you can disable/enable them from the system by toggling the <i class="fa fa-toggle-on" aria-hidden="true"></i> icon. Click the <i class="fa fa-eye" aria-hidden="true"></i> icon to view more information about a specific user.</span>
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/profile_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
        {{-- LIST OF REGISTERED USERS --}}
            <div class="col-lg-5 col-md-5 col-sm-12">
                <div class="accordion" id="listRegisteredUsersCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="listRegisteredUsersCollapseHeading">
                            <button id="listRegUsers_collapseBtnToggle" class="btn btn-link btn-block custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#listRegisteredUsersCollapseDiv" aria-expanded="true" aria-controls="listRegisteredUsersCollapseDiv">
                                <div>
                                    <span class="card_body_title">Registered Users</span>
                                    <span class="card_body_subtitle">Hover on any list to view more available options.</span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="listRegisteredUsersCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="listRegisteredUsersCollapseHeading" data-parent="#listRegisteredUsersCollapseParent">
                            {{-- active users --}}
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="cust_status_title">Active Users <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Active Users are registered users who can access the system according to their system roles."></i></span>
                                </div>
                            </div>
                            @if(count($active_users) > 0)
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="list-group shadow" id="registeredUsers_listGroup">
                                            @foreach($active_users->sortBy('id') as $active_user)
                                            <a href="#" class="list-group-item list-group-item-action cust_list_group_item">
                                                <div class="display_user_image_div text-center">
                                                    @if($active_user->user_type === 'student')
                                                    <img class="studImg_background shadow-sm" src="{{asset('storage/svms/user_images/'.$active_user->user_image)}}" alt="upload user's image">
                                                    @else
                                                    <img class="empImg_background shadow-sm" src="{{asset('storage/svms/user_images/'.$active_user->user_image)}}" alt="upload user's image">
                                                    @endif
                                                </div>
                                                <div class="information_div">
                                                    <span class="li_info_title">{{$active_user->user_fname }} {{ $active_user->user_lname }} @if(auth()->user()->id === $active_user->id) <span class="youIndicator"> ~ you </span> @endif</span>
                                                    <span class="li_info_subtitle">{{ucwords($active_user->user_role)}}</span>
                                                </div>
                                                <div class="li_options_div">
                                                    <button class="btn cust_btn_smcircle" data-toggle="tooltip" data-placement="top" title="View {{ $active_user->user_lname}}'s Account Information?"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                    @if($active_user->user_role !== 'administrator')
                                                    <button id="{{$active_user->id}}" onclick="deactivateUserAccount(this.id)" class="btn cust_btn_smcircle" data-toggle="tooltip" data-placement="top" title="Deactivate {{ $active_user->user_lname}}'s Account?"><i class="fa fa-toggle-on" aria-hidden="true"></i></button>
                                                    <button class="btn cust_btn_smcircle" data-toggle="tooltip" data-placement="top" title="Delete {{ $active_user->user_lname}}'s Account?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                    @endif
                                                </div>
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="no_data_found_div">
                                            <span class="no_data_found_txt">No Active Users Found!</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- deactivated users --}}
                            @if(count($deactivated_users) > 0)
                                <div class="row mt-4">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="cust_status_title">Deactivated Users <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Deactivated Users are users where status' have been deactivated and are no longer be able to access the system until activation."></i></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="list-group" id="registeredUsers_listGroup">
                                            @foreach($deactivated_users->sortBy('id') as $deactivated_user)
                                            <a href="#" class="list-group-item list-group-item-action cust_list_group_item">
                                                <div class="display_user_image_div text-center">
                                                    <img class="display_user_image grayImg_border shadow-sm" src="{{asset('storage/svms/user_images/'.$deactivated_user->user_image)}}" alt="upload user's image">
                                                </div>
                                                <div class="information_div">
                                                    <span class="li_info_title_gray">{{$deactivated_user->user_fname }} {{ $deactivated_user->user_lname }}</span>
                                                    <span class="li_info_subtitle_gray">{{ucwords($deactivated_user->user_role)}}</span>
                                                </div>
                                                <div class="li_options_div">
                                                    <button class="btn cust_btn_smcircle" data-toggle="tooltip" data-placement="top" title="View {{ $deactivated_user->user_lname}}'s Account Information?"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                    <button id="{{$deactivated_user->id}}" onclick="activateUserAccount(this.id)" class="btn cust_btn_smcircle" data-toggle="tooltip" data-placement="top" title="Activate {{ $deactivated_user->user_lname}}'s Account?"><i class="fa fa-toggle-off" aria-hidden="true"></i></button>
                                                    <button class="btn cust_btn_smcircle" data-toggle="tooltip" data-placement="top" title="Delete {{ $deactivated_user->user_lname}}'s Account?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                </div>
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- deactivated users end --}}

                            {{-- pending users --}}
                            @if(count($pending_users) > 0)
                                <div class="row mt-4">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="cust_status_title">Pending Users <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Pending Users are users that have not yet been assigned a role and are newly registered accounts."></i></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="list-group" id="registeredUsers_listGroup">
                                            @foreach($pending_users->sortBy('id') as $pending_user)
                                            <a href="#" class="list-group-item list-group-item-action cust_list_group_item">
                                                <div class="display_user_image_div text-center">
                                                    <img class="display_user_image grayImg_border shadow-sm" src="{{asset('storage/svms/user_images/'.$pending_user->user_image)}}" alt="upload user's image">
                                                </div>
                                                <div class="information_div">
                                                    <span class="li_info_title_gray">{{$pending_user->user_fname }} {{ $pending_user->user_lname }}</span>
                                                    <span class="li_info_subtitle_gray">{{ucwords($pending_user->user_role)}}</span>
                                                </div>
                                                <div class="li_options_div">
                                                    <button class="btn cust_btn_smcircle" data-toggle="tooltip" data-placement="top" title="View {{ $pending_user->user_lname}}'s Account Information?"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                    <button class="btn cust_btn_smcircle" data-toggle="tooltip" data-placement="top" title="Delete {{ $pending_user->user_lname}}'s Account?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                </div>
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- pending users end --}}

                            @if(count($active_users) > 0)
                            <div class="row mt-3">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="cust_info_txtwicon"><i class="fa fa-toggle-on" aria-hidden="true"></i> {{ count($active_users) }} Active @if(count($active_users) > 1) Users @else User @endif found.</span>
                                </div>
                            </div>
                            @endif
                            @if(count($deactivated_users) > 0)
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="cust_info_txtwicon"><i class="fa fa-toggle-off" aria-hidden="true"></i> {{ count($deactivated_users) }} Deactivated @if(count($deactivated_users) > 1) Users @else User @endif found.</span>
                                </div>
                            </div>
                            @endif
                            @if(count($pending_users) > 0)
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="cust_info_txtwicon"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ count($pending_users) }} Pending @if(count($pending_users) > 1) Users @else User @endif found.</span>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-th-list" aria-hidden="true"></i> {{ count($registered_users) }} Registered @if(count($registered_users) > 1) Users @else User @endif found.</span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <a href="{{ route('user_management.create_users', 'create_users') }}" class="btn btn-success btn-sm btn_normal_fontsz shadow" role="button">Create New User <i class="fa fa-user-plus ml-1" aria-hidden="true"></i></a>
                                    <a href="{{ route('user_management.create_users', 'create_users') }}" class="btn btn_svms_blue btn-sm btn_normal_fontsz shadow" role="button">Manage Users <i class="fa fa-external-link ml-1" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {{-- LIST OF REGISTERED USERS end --}}
        {{-- LIST OF REGISTERED SYSTEM ROLES --}}
            <div class="col-lg-7 col-md-7 col-sm-12">
                <div class="accordion" id="listUserRolesCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="listUserRolesCollapseHeading">
                            <button id="listUserRoles_collapseBtnToggle" class="btn btn-link btn-block custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#listUserRolesCollapseDiv" aria-expanded="true" aria-controls="listUserRolesCollapseDiv">
                                <div>
                                    <span class="card_body_title">System Roles</span>
                                    <span class="card_body_subtitle">Click on a role to view more information.</span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="listUserRolesCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="listUserRolesCollapseHeading" data-parent="#listUserRolesCollapseParent">
                            {{-- active roles --}}
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="cust_status_title">Active Roles <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Roles are assigned to specific users for access controls."></i></span>
                                </div>
                            </div>
                        @if(count($active_roles) > 0)
                            <div class="row mt-2">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="accordion cust_accordion_active_target shadow cust_accordion_div" id="uRolesAccordion_Parent">
                                        @foreach($active_roles->sortBy('uRole_id') as $active_role)
                                        @php
                                            $assigned_users = App\Models\Users::where('user_role', $active_role->uRole)->get();
                                        @endphp
                                        <div class="card custom_accordion_card cust_accordion_active">
                                            <div class="card-header p-0" id="userRoleCollapse_heading{{$active_role->uRole_id}}">
                                                <h2 class="mb-0">
                                                <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#uRoleCollapse_Div{{$active_role->uRole_id}}" aria-expanded="true" aria-controls="uRoleCollapse_Div{{$active_role->uRole_id}}">
                                                    <div>
                                                        <span class="accordion_title">{{$active_role->uRole}}</span>
                                                        <span class="accordion_subtitle">@if(count($assigned_users) > 0) {{count($assigned_users) }} Assigned @if(count($assigned_users) > 1) Users @else User @endif Found. @else No Assigned Users. @endif</span>
                                                    </div>
                                                    {{-- <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i> --}}
                                                    <div class="assignedUsersCirclesDiv">
                                                        {{-- @foreach ($assigned_users->sortBy('id') as $assigned_user)
                                                            @if($assigned_user->user_type === 'student')
                                                            <img class="display_user_image studImg_border shadow-sm" src="{{asset('storage/svms/user_images/'.$active_user->user_image)}}" alt="upload user's image">
                                                            @else
                                                            <img class="display_user_image empImg_border shadow-sm" src="{{asset('storage/svms/user_images/'.$active_user->user_image)}}" alt="upload user's image">
                                                            @endif
                                                        @endforeach --}}
                                                        <img class="assignedUsersCirclesImgs whiteImg_border1" src="{{asset('storage/svms/user_images/student_user_image.jpg')}}" alt="user_image" data-toggle="tooltip" data-placement="top" title="User Name 1">
                                                        <img class="assignedUsersCirclesImgs whiteImg_border1" src="{{asset('storage/svms/user_images/employee_user_image.jpg')}}" alt="user_image" data-toggle="tooltip" data-placement="top" title="User Name 2">
                                                        <img class="assignedUsersCirclesImgs whiteImg_border1" src="{{asset('storage/svms/user_images/Group 272.jpg')}}" alt="user_image" data-toggle="tooltip" data-placement="top" title="User Name 3">
                                                        <img class="assignedUsersCirclesImgs whiteImg_border1" src="{{asset('storage/svms/user_images/mfodpic_13102020234509.png')}}" alt="user_image" data-toggle="tooltip" data-placement="top" title="User Name 4">
                                                        <img class="assignedUsersCirclesImgs whiteImg_border1" src="{{asset('storage/svms/user_images/mitch_13102020230525.jpg')}}" alt="user_image" data-toggle="tooltip" data-placement="top" title="User Name 5">
                                                        <img class="assignedUsersCirclesImgs whiteImg_border1" src="{{asset('storage/svms/user_images/disabled_user_image.jpg')}}" alt="user_image" data-toggle="tooltip" data-placement="top" title="User Name 6">
                                                        {{-- <img class="assignedUsersCirclesImgs whiteImg_border1" src="{{asset('storage/svms/user_images/null_bg.jpg')}}" alt="more_users"> --}}
                                                        <div class="moreImgsCounterDiv" data-toggle="tooltip" data-placement="top" title="3 more users">
                                                            <span class="moreImgsCounterTxt">+3</span>
                                                        </div>
                                                    </div>
                                                </button>
                                                </h2>
                                            </div>
                                            <div id="uRoleCollapse_Div{{$active_role->uRole_id}}" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="userRoleCollapse_heading{{$active_role->uRole_id}}" data-parent="#uRolesAccordion_Parent">
                                            {{-- if role is administrator --}}
                                                @if($active_role->uRole === 'administrator')
                                                <div class="card-body lightBlue_cardBody">
                                                    <span class="lightBlue_cardBody_blueTitle">Role Status:</span>
                                                    <span class="lightBlue_cardBody_list">{{ ucwords($active_role->uRole) }} Role is always active and cannot be deactivated.</span>
                                                </div>
                                                <div class="card-body lightBlue_cardBody mt-2">
                                                    <span class="lightBlue_cardBody_blueTitle">Assigned Users:</span>
                                                    @if(count($assigned_users) > 0)
                                                        @foreach($assigned_users as $index => $assigned_user)
                                                        <span class="lightBlue_cardBody_list"><span class="lightBlue_cardBody_listCount cnTab">{{$index+1}}.</span> {{ $assigned_user->user_fname }} {{ $assigned_user->user_lname }}</span>
                                                        @endforeach
                                                    @else
                                                    <span class="lightBlue_cardBody_list font-italic">No assigned users found.</span>
                                                    @endif
                                                </div>
                                                <div class="card-body lightGreen_cardBody mt-2 mb-2">
                                                    <span class="lightGreen_cardBody_greenTitle">Access Controls:</span>
                                                    @if(!is_null($active_role->uRole_access))
                                                        @foreach(json_decode(json_encode($active_role->uRole_access), true) as $index => $uRole_access)
                                                        <span class="lightGreen_cardBody_list"><span class="lightGreen_cardBody_listCount cnTab">{{$index+1}}.</span> {{ ucwords($uRole_access) }}</span>
                                                        @endforeach
                                                    @else
                                                    <span class="lightGreen_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> No access controls found.</span>
                                                    @endif
                                                </div>
                                                <div class="card-body lightBlue_cardBody mt-2 mb-2">
                                                    <span class="lightBlue_cardBody_notice"><span class="font-weight-bold"><i class="fa fa-lock" aria-hidden="true"></i> Administrator </span> is a fixed user role that cannot be edited or deleted from the system, all modules are accessible to Administrator Role.</span>
                                                </div>
                                            {{-- else --}}
                                                @else
                                                <div class="card-body p-0">
                                                    <ul class="nav nav-pills custom_nav_pills mt-0 mb-3 d-flex justify-content-center" id="uRoleOptions_tabList" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link @if($active_role->uRole_type === 'student') custom_nav_link_green @else custom_nav_link_blue @endif active" id="previewUserRoleTab_{{$active_role->uRole_id}}" data-toggle="pill" href="#previewUserRoleLink_{{$active_role->uRole_id}}" role="tab" aria-controls="previewUserRoleLink_{{$active_role->uRole_id}}" aria-selected="true">Preview</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link @if($active_role->uRole_type === 'student') custom_nav_link_green @else custom_nav_link_blue @endif" id="editUserRoleTab_{{$active_role->uRole_id}}" data-toggle="pill" href="#editUserRoleLink_{{$active_role->uRole_id}}" role="tab" aria-controls="editUserRoleLink_{{$active_role->uRole_id}}" aria-selected="false">Edit Role</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        {{-- PREVIEW ROLE --}}
                                                        <div class="tab-pane fade show active" id="previewUserRoleLink_{{$active_role->uRole_id}}" role="tabpanel" aria-labelledby="previewUserRoleTab_{{$active_role->uRole_id}}">
                                                            <div class="card-body lightBlue_cardBody">
                                                                <span class="lightBlue_cardBody_blueTitle">Role Status:</span>
                                                                <span class="roleStatusDiv"> <i id="{{$active_role->uRole_id}}" onclick="deactivateRoleModal(this.id)" class="fa fa-toggle-on roleStatusToggleIcon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Deactivate {{ ucwords($active_role->uRole) }} Role?"></i> &nbsp; {{ ucwords($active_role->uRole) }} Role is Activated.</span>
                                                            </div>
                                                            <div class="card-body lightBlue_cardBody mt-2">
                                                            @if(count($assigned_users) > 0)
                                                                @if(count($assigned_users) > 1)
                                                                <span class="lightBlue_cardBody_blueTitle">Assigned Users:</span>
                                                                @else
                                                                <span class="lightBlue_cardBody_blueTitle">Assigned User:</span>
                                                                @endif
                                                                @foreach($assigned_users as $index => $assigned_user)
                                                                <span class="lightBlue_cardBody_list"><span class="lightBlue_cardBody_listCount">{{$index+1}}. &nbsp; </span> {{ $assigned_user->user_fname }} {{ $assigned_user->user_lname }}</span>
                                                                @endforeach
                                                            @else
                                                                <span class="lightBlue_cardBody_list font-italic">No assigned users found.</span>
                                                            @endif
                                                            </div>
                                                            <div class="card-body lightGreen_cardBody mt-2 mb-2">
                                                                <span class="lightGreen_cardBody_greenTitle">Access Controls:</span>
                                                                @if(!is_null($active_role->uRole_access))
                                                                    @foreach(json_decode(json_encode($active_role->uRole_access), true) as $index => $uRole_access)
                                                                    <span class="lightGreen_cardBody_list"><span class="lightGreen_cardBody_listCount">{{$index+1}}. &nbsp; </span> {{ ucwords($uRole_access) }}</span>
                                                                    @endforeach
                                                                @else
                                                                <span class="lightBlue_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> No access controls found.</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        {{-- EDIT ROLE --}}
                                                        <div class="tab-pane fade" id="editUserRoleLink_{{$active_role->uRole_id}}" role="tabpanel" aria-labelledby="editUserRoleTab_{{$active_role->uRole_id}}">
                                                            <form action="{{route('user_management.update_user_role')}}" class="editUserRoleForm" method="POST">
                                                                @csrf
                                                                <div class="card-body lightBlue_cardBody">
                                                                    <span class="lightBlue_cardBody_blueTitle">Role Name:</span>
                                                                    <div class="input-group m-0">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">
                                                                                <i class="nc-icon nc-badge"></i>
                                                                            </span>
                                                                        </div>
                                                                        <input id="edit_uRoleName" name="edit_uRoleName" type="text" class="form-control" @if(!is_null($active_role->uRole)) value="{{ucwords($active_role->uRole)}}" @else placeholder="Type Role Name" @endif required>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body lightGreen_cardBody mt-2">
                                                                    <span class="lightGreen_cardBody_greenTitle">Access Controls:</span>
                                                                    @php
                                                                        $make_array_uRole_access = json_decode(json_encode($active_role->uRole_access), true);
                                                                    @endphp
                                                                    {{-- {{json_encode($make_array_uRole_access)}} --}}
                                                                    <div class="form-group mx-0 mt-0 mb-1">
                                                                        <div class="custom-control custom-checkbox align-items-center">
                                                                            <input type="checkbox" name="edit_uRole_access[]" value="profile" class="custom-control-input cursor_pointer" id="profileModCheck_{{$active_role->uRole_id}}" @if(!is_null($active_role->uRole_access)) @if(in_array('profile', $make_array_uRole_access)) checked="checked" @endif @endif>
                                                                            <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="profileModCheck_{{$active_role->uRole_id}}">Profile</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group mx-0 mt-0 mb-1">
                                                                        <div class="custom-control custom-checkbox align-items-center">
                                                                            <input type="checkbox" name="edit_uRole_access[]" value="violation entry" class="custom-control-input cursor_pointer" id="violation_entryModCheck_{{$active_role->uRole_id}}" @if(!is_null($active_role->uRole_access)) @if(in_array('violation entry', $make_array_uRole_access)) checked="checked" @endif @endif>
                                                                            <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="violation_entryModCheck_{{$active_role->uRole_id}}">Violation Entry</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group mx-0 mt-0 mb-1">
                                                                        <div class="custom-control custom-checkbox align-items-center">
                                                                            <input type="checkbox" name="edit_uRole_access[]" value="student handbook" class="custom-control-input cursor_pointer" id="student_handbookModCheck_{{$active_role->uRole_id}}" @if(!is_null($active_role->uRole_access)) @if(in_array('student handbook', $make_array_uRole_access)) checked="checked" @endif @endif>
                                                                            <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="student_handbookModCheck_{{$active_role->uRole_id}}">Student Handbook</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body lightRed_cardBody mt-2">
                                                                    <span class="lightRed_cardBody_redTitle">Access Controls:</span>
                                                                    <span class="lightRed_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> Below Modules are Administrative Access Controls that are not recommended for regular System Roles but you can enable them for this role if you wish to.</span>
                                                                    <div class="form-group mx-0 mt-2 mb-1">
                                                                        <div class="custom-control custom-checkbox align-items-center">
                                                                            <input type="checkbox" name="edit_uRole_access[]" value="dashboard" class="custom-control-input cursor_pointer" id="dashboardModCheck_{{$active_role->uRole_id}}" @if(!is_null($active_role->uRole_access)) @if(in_array('dashboard', $make_array_uRole_access)) checked="checked" @endif @endif>
                                                                            <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="dashboardModCheck_{{$active_role->uRole_id}}">Dashboard</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group mx-0 mt-0 mb-1">
                                                                        <div class="custom-control custom-checkbox align-items-center">
                                                                            <input type="checkbox" name="edit_uRole_access[]" value="user management" class="custom-control-input cursor_pointer" id="user_managementModCheck_{{$active_role->uRole_id}}" @if(!is_null($active_role->uRole_access)) @if(in_array('user management', $make_array_uRole_access)) checked="checked" @endif @endif>
                                                                            <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="user_managementModCheck_{{$active_role->uRole_id}}">User Management</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group mx-0 mt-0 mb-1">
                                                                        <div class="custom-control custom-checkbox align-items-center">
                                                                            <input type="checkbox" name="edit_uRole_access[]" value="violation records" class="custom-control-input cursor_pointer" id="violation_recordsModCheck_{{$active_role->uRole_id}}" @if(!is_null($active_role->uRole_access)) @if(in_array('violation records', $make_array_uRole_access)) checked="checked" @endif @endif>
                                                                            <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="violation_recordsModCheck_{{$active_role->uRole_id}}">Violation Records</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row d-flex justify-content-center">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                                                        <input type="hidden" name="edit_selected_uRole_id" value="{{$active_role->uRole_id}}">
                                                                        <input type="hidden" name="respo_user_id" value="{{auth()->user()->id}}">
                                                                        <input type="hidden" name="respo_user_lname" value="{{auth()->user()->user_lname}}">
                                                                        <input type="hidden" name="respo_user_fname" value="{{auth()->user()->user_fname}}">
                                                                        <button type="submit" class="btn saveChangesEdtRole btn_svms_blue btn-round btn_show_icon" disabled>{{ __('Save Changes') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>                                              
                                </div>
                            </div>

                            {{-- deactivated roles --}}
                            @if(count($deactivated_roles) > 0)
                                <div class="row mt-4">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="cust_status_title">Deactivated Roles <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="System Roles that have been deactivated on accessing the system."></i></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="accordion cust_accordion_active_target2 cust_accordion_div" id="deactvdURolesAccordion_Parent">
                                            @foreach($deactivated_roles->sortBy('uRole_id') as $deactivated_role)
                                            @php
                                                $assigned_users_d = App\Models\Users::where('user_role', $deactivated_role->uRole)->get();
                                            @endphp
                                            <div class="card custom_accordion_card cust_accordion_active2">
                                                <div class="card-header p-0" id="deactvdURoleCollapse_heading{{$deactivated_role->uRole_id}}">
                                                    <h2 class="mb-0">
                                                    <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#deactvdURoleCollapse_Div{{$deactivated_role->uRole_id}}" aria-expanded="true" aria-controls="deactvdURoleCollapse_Div{{$deactivated_role->uRole_id}}">
                                                        <div>
                                                            <span class="accordion_title_gray">{{$deactivated_role->uRole}}</span>
                                                            <span class="accordion_subtitle_gray">@if(count($assigned_users_d) > 0) {{count($assigned_users_d) }} Assigned @if(count($assigned_users_d) > 1) Users @else User @endif Found. @else No Assigned Users. @endif</span>
                                                        </div>
                                                        {{-- <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i> --}}
                                                        <div class="assignedUsersCirclesDiv">
                                                            {{-- @foreach ($assigned_users->sortBy('id') as $assigned_user)
                                                                @if($assigned_user->user_type === 'student')
                                                                <img class="display_user_image studImg_border shadow-sm" src="{{asset('storage/svms/user_images/'.$active_user->user_image)}}" alt="upload user's image">
                                                                @else
                                                                <img class="display_user_image empImg_border shadow-sm" src="{{asset('storage/svms/user_images/'.$active_user->user_image)}}" alt="upload user's image">
                                                                @endif
                                                            @endforeach --}}
                                                            <img class="assignedUsersCirclesImgs grayUsersCirclesImgs_filter" src="{{asset('storage/svms/user_images/student_user_image.jpg')}}" alt="user_image" data-toggle="tooltip" data-placement="top" title="User Name 1">
                                                            <img class="assignedUsersCirclesImgs grayUsersCirclesImgs_filter" src="{{asset('storage/svms/user_images/employee_user_image.jpg')}}" alt="user_image" data-toggle="tooltip" data-placement="top" title="User Name 2">
                                                            <img class="assignedUsersCirclesImgs grayUsersCirclesImgs_filter" src="{{asset('storage/svms/user_images/Group 272.jpg')}}" alt="user_image" data-toggle="tooltip" data-placement="top" title="User Name 3">
                                                            <img class="assignedUsersCirclesImgs grayUsersCirclesImgs_filter" src="{{asset('storage/svms/user_images/mfodpic_13102020234509.png')}}" alt="user_image" data-toggle="tooltip" data-placement="top" title="User Name 4">
                                                            <img class="assignedUsersCirclesImgs grayUsersCirclesImgs_filter" src="{{asset('storage/svms/user_images/mitch_13102020230525.jpg')}}" alt="user_image" data-toggle="tooltip" data-placement="top" title="User Name 5">
                                                            <img class="assignedUsersCirclesImgs grayUsersCirclesImgs_filter" src="{{asset('storage/svms/user_images/disabled_user_image.jpg')}}" alt="user_image" data-toggle="tooltip" data-placement="top" title="User Name 6">
                                                            {{-- <img class="assignedUsersCirclesImgs whiteImg_border1" src="{{asset('storage/svms/user_images/null_bg.jpg')}}" alt="more_users"> --}}
                                                            <div class="moreImgsCounterDiv" data-toggle="tooltip" data-placement="top" title="3 more users">
                                                                <span class="moreImgsCounterTxt">+3</span>
                                                            </div>
                                                        </div>
                                                    </button>
                                                    </h2>
                                                </div>
                                                <div id="deactvdURoleCollapse_Div{{$deactivated_role->uRole_id}}" class="collapse cust_collapse_active2 cb_t0b12y20" aria-labelledby="deactvdURoleCollapse_heading{{$deactivated_role->uRole_id}}" data-parent="#deactvdURolesAccordion_Parent">
                                                    <div class="card-body lightBlue_cardBody">
                                                        <span class="lightBlue_cardBody_blueTitle grayed_txt">Role Status:</span>
                                                        <span class="roleStatusDiv"><i id="{{$deactivated_role->uRole_id}}" onclick="activateRoleModal(this.id)" class="fa fa-toggle-off roleStatusToggleIcon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Activate {{ ucwords($deactivated_role->uRole) }} Role?"></i> &nbsp; {{ ucwords($deactivated_role->uRole) }} Role is Deactivated.</span>
                                                    </div>
                                                    <div class="card-body lightBlue_cardBody mt-2">
                                                        @if(count($assigned_users_d) > 0)
                                                            @if(count($assigned_users_d) > 1)
                                                            <span class="lightBlue_cardBody_blueTitle grayed_txt">Assigned Users:</span>
                                                            @else
                                                            <span class="lightBlue_cardBody_blueTitle grayed_txt">Assigned User:</span>
                                                            @endif
                                                            @foreach($assigned_users_d as $index => $assigned_user_d)
                                                            <span class="lightBlue_cardBody_list"><span class="lightBlue_cardBody_listCount grayed_txt">{{$index+1}}. &nbsp;</span> {{ $assigned_user_d->user_fname }} {{ $assigned_user_d->user_lname }}</span>
                                                            @endforeach
                                                        @else
                                                            <span class="lightBlue_cardBody_list font-italic">No assigned users found.</span>
                                                        @endif
                                                    </div>
                                                    <div class="card-body lightBlue_cardBody mt-2">
                                                        <span class="lightBlue_cardBody_blueTitle grayed_txt">Access Controls:</span>
                                                        @if(!is_null($deactivated_role->uRole_access))
                                                            @foreach(json_decode(json_encode($deactivated_role->uRole_access), true) as $index => $uRole_access)
                                                            <span class="lightBlue_cardBody_list"><span class="lightBlue_cardBody_listCount grayed_txt">{{$index+1}}. &nbsp;</span> {{ ucwords($uRole_access) }}</span>
                                                            @endforeach
                                                        @else
                                                        <span class="lightBlue_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> No access controls found.</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                                
                            {{-- deleted roles --}}
                            @if(count($deleted_roles) > 0)
                                <div class="row mt-4">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="cust_status_title">Deleted Roles <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Roles that are temporarily deleted from the system which can be recovered back again or be deleted permanently from the system."></i></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="accordion cust_accordion_div" id="deletedURolesAccordion_Parent">
                                            @foreach($deleted_roles->sortBy('uRole_id') as $deleted_role)
                                            @php
                                                $assigned_users_d = App\Models\Users::where('user_role', $deleted_role->uRole)->get();
                                            @endphp
                                            <div class="card custom_accordion_card">
                                                <div class="card-header p-0" id="deletedURoleCollapse_heading{{$deleted_role->uRole_id}}">
                                                    <h2 class="mb-0">
                                                    <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#deletedURoleCollapse_Div{{$deleted_role->uRole_id}}" aria-expanded="true" aria-controls="deletedURoleCollapse_Div{{$deleted_role->uRole_id}}">
                                                        <div>
                                                            <span class="accordion_title_gray">{{$deleted_role->uRole}}</span>
                                                            <span class="accordion_subtitle_gray">@if(count($assigned_users_d) > 0) {{count($assigned_users_d) }} Assigned @if(count($assigned_users_d) > 1) Users @else User @endif Found. @else No Assigned Users. @endif</span>
                                                        </div>
                                                        <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                                    </button>
                                                    </h2>
                                                </div>
                                                <div id="deletedURoleCollapse_Div{{$deleted_role->uRole_id}}" class="collapse cb_t0b12y20" aria-labelledby="deletedURoleCollapse_heading{{$deleted_role->uRole_id}}" data-parent="#deletedURolesAccordion_Parent">
                                                    <div class="card-body lightBlue_cardBody mt-2">
                                                        @if(count($assigned_users_d) > 0)
                                                            @if(count($assigned_users_d) > 1)
                                                            <span class="lightBlue_cardBody_blueTitle grayed_txt">Assigned Users:</span>
                                                            @else
                                                            <span class="lightBlue_cardBody_blueTitle grayed_txt">Assigned User:</span>
                                                            @endif
                                                            @foreach($assigned_users_d as $index => $assigned_user_d)
                                                            <span class="lightBlue_cardBody_list"><span class="lightBlue_cardBody_listCount grayed_txt">{{$index+1}}.</span> {{ $assigned_user_d->user_fname }} {{ $assigned_user_d->user_lname }}</span>
                                                            @endforeach
                                                        @else
                                                            <span class="lightBlue_cardBody_list font-italic">No assigned users found.</span>
                                                        @endif
                                                    </div>
                                                    <div class="card-body lightBlue_cardBody mt-2">
                                                        <span class="lightBlue_cardBody_blueTitle grayed_txt">Access Controls:</span>
                                                        @if(!is_null($deleted_role->uRole_access))
                                                            @foreach(json_decode(json_encode($deleted_role->uRole_access), true) as $index => $uRole_access)
                                                            <span class="lightBlue_cardBody_list"><span class="lightBlue_cardBody_listCount grayed_txt">{{$index+1}}.</span> {{ ucwords($uRole_access) }}</span>
                                                            @endforeach
                                                        @else
                                                        <span class="lightBlue_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> No access controls found.</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif


                            @if(count($active_roles) > 0)
                            <div class="row mt-3">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="cust_info_txtwicon"><i class="fa fa-toggle-on" aria-hidden="true"></i> {{ count($active_roles) }} Active @if(count($active_roles) > 1) Roles @else Role @endif found.</span>
                                </div>
                            </div>
                            @endif
                            @if(count($deactivated_roles) > 0)
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="cust_info_txtwicon"><i class="fa fa-toggle-off" aria-hidden="true"></i> {{ count($deactivated_roles) }} Deactivated @if(count($deactivated_roles) > 1) Roles @else Role @endif found.</span>
                                </div>
                            </div>
                            @endif
                            @if(count($deleted_roles) > 0)
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="cust_info_txtwicon"><i class="fa fa-trash" aria-hidden="true"></i> {{ count($deleted_roles) }} Deleted @if(count($deleted_roles) > 1) Roles @else Role @endif found.</span>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-th-list" aria-hidden="true"></i> {{ count($registered_roles) }} Registered @if(count($registered_roles) > 1) Roles @else Role @endif found.</span>
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
        {{-- LIST OF REGISTERED SYSTEM ROLES end --}}
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
    {{-- activate user account modal --}}
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
    {{-- activate user account modal end --}}

    {{-- deactivate role modal --}}
        <div class="modal fade" id="deactivateRoleModal" tabindex="-1" role="dialog" aria-labelledby="deactivateRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="deactivateRoleModalLabel">Deactivate System Role?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="deactivateRoleHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- deactivate role modal end --}}
    {{-- activate role modal --}}
        <div class="modal fade" id="activateRoleModal" tabindex="-1" role="dialog" aria-labelledby="activateRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="activateRoleModalLabel">Activate System Role?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="activeRoleHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- activate role modal end --}}
@endsection

@push('scripts')
{{-- scripts for list of system roles --}}
{{-- divide list on collapse --}}
    {{-- first collpase --}}
    <script>
        $(document).ready(function() {
            $('.cust_collapse_active').on('shown.bs.collapse', function () {
                $('.cust_accordion_active').addClass('my15br15');
                $('.cust_accordion_active_target').removeClass('shadow');
            });
            $('.cust_collapse_active').on('hidden.bs.collapse', function () {
                $('.cust_accordion_active').removeClass('my15br15');
                $('.cust_accordion_active_target').addClass('shadow');
            });
        });
    </script>
    {{-- second collapse --}}
    {{-- secind --}}
    <script>
        $(document).ready(function() {
            $('.cust_collapse_active2').on('shown.bs.collapse', function () {
                $('.cust_accordion_active2').addClass('my15br15');
                // $('.cust_accordion_active_target2').removeClass('shadow');
            });
            $('.cust_collapse_active2').on('hidden.bs.collapse', function () {
                $('.cust_accordion_active2').removeClass('my15br15');
                // $('.cust_accordion_active_target2').addClass('shadow');
            });
        });
    </script>
{{-- divide list on collapse end --}}

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

{{-- activate/deactivate role open modal for confirmation--}}
    <script>
        function deactivateRoleModal(deactivated_uRole_id){
            var deactivated_uRole_id = deactivated_uRole_id;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user_management.deactivate_role_modal') }}",
                method:"GET",
                data:{deactivated_uRole_id:deactivated_uRole_id, _token:_token},
                success: function(data){
                    $('#deactivateRoleHtmlData').html(data); 
                    $('#deactivateRoleModal').modal('show');
                }
            });
        }
    </script>
    <script>
        function activateRoleModal(activate_uRole_id){
            var activate_uRole_id = activate_uRole_id;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user_management.activate_role_modal') }}",
                method:"GET",
                data:{activate_uRole_id:activate_uRole_id, _token:_token},
                success: function(data){
                    $('#activeRoleHtmlData').html(data); 
                    $('#activateRoleModal').modal('show');
                }
            });
        }
    </script>
{{-- activate/deactivate role open modal for confirmation end --}}

{{-- edit role form on change enable 'Save Changes' button --}}
    <script>
        $(window).on('load', function (e) {
            $('.editUserRoleForm').each(function(){
                    $(this).data('serialized', $(this).serialize())
                }).on('change input', function(){
                    $(this).find('.saveChangesEdtRole').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                }).find('.saveChangesEdtRole').prop('disabled', true);
        });
    </script>
{{-- edit role form on change enable 'Save Changes' button end --}}
@endpush