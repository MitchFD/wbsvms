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
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="input-group cust_srchInpt_div">
                                        <input id="search_user" name="search_user" type="text" class="form-control cust_srchUsersInpt_box" placeholder="Search User" />
                                        <i class="nc-icon nc-zoom-split" aria-hidden="true"></i>    
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 d-flex align-items-end justify-content-end">
                                    {{-- <span class="usrts_span">Total Data: &nbsp; <span class="font-weight-bold"> 10 users found</span> </span> --}}
                                    {{-- <span>Total Data: <span class="font-weight-bold font-italic" id="total_users"> </span> </span> --}}
                                </div>
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
                                        <tbody class="tbody_svms_white" id="sys_users_tbl">
                                            {{-- ajax data table --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span>Total Data: <span class="font-weight-bold" id="total_data_count"> </span> </span>
                                </div>
                                {{-- <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-end align-items-end">
                                    <a href="{{ route('user_management.create_users', 'create_users') }}" class="btn btn-success cust_bt_links shadow" role="button"><i class="nc-icon nc-simple-add mr-1" aria-hidden="true"></i> Create New User</a>
                                </div> --}}
                            </div>
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
                                        @if($count_deleted_users > 0)
                                            <span class="cust_info_txtwicon"><i class="fa fa-trash mr-1" aria-hidden="true"></i> {{ $count_deleted_users }} Temporarily Deleted @if($count_deleted_users > 1) Users @else User @endif found.</span>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-end">
                                        <a href="{{ route('user_management.create_users', 'create_users') }}" class="btn btn-success cust_bt_links shadow" role="button"><i class="nc-icon nc-simple-add mr-1" aria-hidden="true"></i> Create New User</a>
                                    </div>
                                </div>
                            {{-- bottom line end --}}
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
                                                        <span class="accordion_title">{{$active_role->uRole}}@if($count_assigned_users > 1)'s @endif</span>
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
                            {{-- active roles end --}}
                            {{-- deactivated roles --}}
                            @if($count_deactivated_users > 0)
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
                                        <div class="card cust_listCard shadow">
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
                                                                    ?><img class="assignedUsersCirclesImgs2 whiteImg_border1" src="{{asset('storage/svms/user_images/'.$display_8userImgs->user_image)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $display_8userImgs->id) You @else {{$display_8userImgs->user_fname. ' ' .$display_8userImgs->user_lname}} @endif"> <?php
                                                                }
                                                                ?>
                                                                <div class="moreImgsCounterDiv2" data-toggle="tooltip" data-placement="top" title="{{$more_count}} more users">
                                                                    <span class="moreImgsCounterTxt2">+{{$more_count}}</span>
                                                                </div>
                                                                <?php
                                                            }else {
                                                                $get_all_assigned_users = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $deactivated_role->uRole)->get();
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
                            @endif
                            {{-- deactivated roles end --}}
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

@endpush