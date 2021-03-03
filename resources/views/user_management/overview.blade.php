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
                            <span class="page_intro_subtitle">This page is an overview display of registered users, system roles, and their statuses whether they are active or have been deactivated from accessing the system. Click the <i class="fa fa-eye" aria-hidden="true"></i> icon to view more information about a specific user.</span>
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/um_overview_illustration.svg') }}" alt="...">
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
                                    {{-- <span class="card_body_subtitle" id="listRegUsers_subtitleTxt">{{$count_registered_users}} Registered Users Found.</span> --}}
                                    <span class="card_body_subtitle">View user's statuses and profiles.</span>
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
                                @if($count_active_users > 0)
                                    @php
                                        $active_users = App\Models\Users::select('id', 'user_role', 'user_status', 'user_role_status', 'user_type', 'user_image', 'user_lname', 'user_fname')->where('user_status', 'active')->where('user_role_status', 'active')->get();
                                    @endphp
                                    <div class="row mt-2">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card cust_listCard shadow">
                                                @foreach($active_users->sortBy('id') as $active_user)
                                                    <div class="card-header cust_listCard_header2">
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <div class="display_user_image_div text-center">
                                                                @if($active_user->user_type === 'student')
                                                                    <img class="studImg_background shadow-sm" src="{{asset('storage/svms/user_images/'.$active_user->user_image)}}" alt="{{$active_user->user_lname}}'s profile image">
                                                                @else
                                                                    <img class="empImg_background shadow-sm" src="{{asset('storage/svms/user_images/'.$active_user->user_image)}}" alt="{{$active_user->user_lname}}'s profile image">
                                                                @endif
                                                            </div>
                                                            <div class="information_div ml-3 text-left">
                                                                <span class="li_info_title">{{$active_user->user_fname }} {{ $active_user->user_lname }} @if(auth()->user()->id === $active_user->id) <span class="youIndicator"> ~ you </span> @endif</span>
                                                                <span class="li_info_subtitle">{{ucwords($active_user->user_role)}}</span>
                                                            </div>
                                                        </div>
                                                        <a href="#" class="btn cust_btn_smcircle2" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $active_user->id) View your profile? @else View {{ $active_user->user_lname}}'s Account Information? @endif"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                    </div>
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
                            {{-- active user end --}}
                            {{-- deactivated users --}}
                                @if($count_deactivated_users > 0)
                                    <div class="row mt-4">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <span class="cust_status_title">Deactivated Users <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Deactivated Users are users where status' have been deactivated and are no longer be able to access the system until activation."></i></span>
                                        </div>
                                    </div>
                                    @php
                                        $deactivated_users = App\Models\Users::select('id', 'user_role', 'user_status', 'user_role_status', 'user_image', 'user_lname', 'user_fname')->where('user_status', 'deactivated')->orWhere('user_role_status', 'deactivated')->get();
                                    @endphp
                                    <div class="row mt-2">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card cust_listCard shadow-none">
                                                @foreach($deactivated_users->sortBy('id') as $deactivated_user)
                                                    <div class="card-header cust_listCard_header2">
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <div class="display_user_image_div text-center">
                                                                @if($deactivated_user->user_type === 'student')
                                                                <img class="display_user_image grayImg_border shadow-none" src="{{asset('storage/svms/user_images/'.$deactivated_user->user_image)}}" alt="upload user's image">
                                                                @else
                                                                <img class="display_user_image grayImg_border shadow-none" src="{{asset('storage/svms/user_images/'.$deactivated_user->user_image)}}" alt="upload user's image">
                                                                @endif
                                                            </div>
                                                            <div class="information_div ml-3 text-left">
                                                                <span class="li_info_title_gray">{{$deactivated_user->user_fname }} {{ $deactivated_user->user_lname }} @if(auth()->user()->id === $deactivated_user->id) <span class="youIndicator"> ~ you </span> @endif</span>
                                                                <span class="li_info_subtitle_gray">{{ucwords($deactivated_user->user_role)}}</span>
                                                            </div>
                                                        </div>
                                                        <a href="#" class="btn cust_btn_smcircle2" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $deactivated_user->id) View your profile? @else View {{ $deactivated_user->user_lname}}'s Account Information? @endif"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            {{-- deactivated users end --}}
                        </div>
                        <div class="card-footer cb_t0b15x25">
                            {{-- bottom line --}}
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
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-end">
                                        <a href="{{ route('user_management.system_users', 'system_users') }}" class="btn btn-success cust_bt_links shadow" role="button"><i class="nc-icon nc-settings-gear-65 mr-1" aria-hidden="true"></i> Manage System Users</a>
                                    </div>
                                </div>
                            {{-- bottom line end --}}
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
                                    {{-- <span class="card_body_subtitle">{{$count_registered_roles}} Registered System Roles Found.</span> --}}
                                    <span class="card_body_subtitle">View System Roles, their statuses and assigned users.</span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="listUserRolesCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="listUserRolesCollapseHeading" data-parent="#listUserRolesCollapseParent">
                            {{-- active roles --}}
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
                                                    <div class="card-header cust_listCard_header">
                                                        <div>
                                                            <span class="accordion_title">{{$active_role->uRole}}</span>
                                                            <span class="accordion_subtitle">@if($count_assigned_users > 0) {{$count_assigned_users }} Assigned @if($count_assigned_users > 1) Users @else User @endif Found. @else <span class="font-italic text_svms_red"> No Assigned Users. </span> @endif</span>
                                                        </div>
                                                        <div class="assignedUsersCirclesDiv">
                                                            <?php
                                                                if($count_assigned_users > 7){
                                                                    $get_only_6 = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $active_role->uRole)->take(6)->get();
                                                                    $more_count = $count_assigned_users - 6;
                                                                    foreach($get_only_6->sortBy('id') as $display_6userImgs){
                                                                        ?><img class="assignedUsersCirclesImgs whiteImg_border1" src="{{asset('storage/svms/user_images/'.$display_6userImgs->user_image)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $display_6userImgs->id) You @else {{$display_6userImgs->user_fname. ' ' .$display_6userImgs->user_lname}} @endif"> <?php
                                                                    }
                                                                    ?>
                                                                    <div class="moreImgsCounterDiv" data-toggle="tooltip" data-placement="top" title="{{$more_count}} more users">
                                                                        <span class="moreImgsCounterTxt">+{{$more_count}}</span>
                                                                    </div>
                                                                    <?php
                                                                }else {
                                                                    $get_all_assigned_users = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $active_role->uRole)->get();
                                                                    foreach($get_all_assigned_users->sortBy('id') as $assigned_user) {
                                                                        ?> <img class="assignedUsersCirclesImgs whiteImg_border1" src="{{asset('storage/svms/user_images/'.$assigned_user->user_image)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $assigned_user->id) You @else {{$assigned_user->user_fname. ' ' .$assigned_user->user_lname}} @endif"> <?php
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
                                            <span class="cust_status_title">Deactivated System Roles <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="System Roles that have been deactivated on accessing the system."></i></span>
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
                                                    <div class="card-header cust_listCard_header">
                                                        <div>
                                                            <span class="accordion_title_gray">{{$deactivated_role->uRole}}</span>
                                                            <span class="accordion_subtitle_gray">@if($count_assigned_users > 0) {{$count_assigned_users }} Assigned @if($count_assigned_users > 1) Users @else User @endif Found. @else <span class="font-italic text_svms_red"> No Assigned Users. </span> @endif</span>
                                                        </div>
                                                        <div class="assignedUsersCirclesDiv">
                                                            <?php
                                                                if($count_assigned_users > 7){
                                                                    $get_only_6 = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $deactivated_role->uRole)->take(6)->get();
                                                                    $more_count = $count_assigned_users - 6;
                                                                    foreach($get_only_6->sortBy('id') as $display_6userImgs){
                                                                        ?><img class="assignedUsersCirclesImgs grayUsersCirclesImgs_filter" src="{{asset('storage/svms/user_images/'.$display_6_user_images->user_image)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $display_6_user_images->id) You @else {{$display_6_user_images->user_fname. ' ' .$display_6_user_images->user_lname}} @endif"> <?php
                                                                    }
                                                                    ?>
                                                                    <div class="moreImgsCounterDiv" data-toggle="tooltip" data-placement="top" title="{{$more_count}} more users">
                                                                        <span class="moreImgsCounterTxt">+{{$more_count}}</span>
                                                                    </div>
                                                                    <?php
                                                                }else {
                                                                    $get_all_assigned_users = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $deactivated_role->uRole)->get();
                                                                    foreach($get_all_assigned_users->sortBy('id') as $assigned_user) {
                                                                        ?> <img class="assignedUsersCirclesImgs grayUsersCirclesImgs_filter" src="{{asset('storage/svms/user_images/'.$assigned_user->user_image)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $assigned_user->id) You @else {{$assigned_user->user_fname. ' ' .$assigned_user->user_lname}} @endif"> <?php
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
                        </div>
                        <div class="card-footer cb_t0b15x25">
                            {{-- bottom line --}}
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                            <span class="cust_info_txtwicon font-weight-bold"><i class="nc-icon nc-badge mr-1" aria-hidden="true"></i> {{ $count_registered_roles }} Registered @if($count_registered_roles > 1) Roles @else Role @endif found.</span>
                                        @if($count_active_roles > 0)
                                            <span class="cust_info_txtwicon"><i class="fa fa-toggle-on mr-1" aria-hidden="true"></i> {{ $count_active_roles }} Active @if($count_active_roles > 1) Roles @else Role @endif found.</span>
                                        @endif
                                        @if($count_deactivated_roles > 0)
                                            <span class="cust_info_txtwicon"><i class="fa fa-toggle-off mr-1" aria-hidden="true"></i> {{ $count_deactivated_roles }} Deactivated @if($count_deactivated_roles > 1) Roles @else Role @endif found.</span>
                                        @endif
                                        @if($count_deleted_roles > 0)
                                            <span class="cust_info_txtwicon"><i class="fa fa-trash mr-1" aria-hidden="true"></i> {{ $count_deleted_roles }} Deleted @if($count_deleted_roles > 1) Roles @else Role @endif found.</span>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-end">
                                        <a href="{{ route('user_management.system_roles', 'system_roles') }}" class="btn btn_svms_blue cust_bt_links shadow" role="button"><i class="nc-icon nc-settings-gear-65 mr-1" aria-hidden="true"></i> Manage System Roles</a>
                                    </div>
                                </div>
                            {{-- bottom line end --}}
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