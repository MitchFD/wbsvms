@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'users_logs'
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
                <a href="{{ route('user_management.users_logs', 'users_logs') }}" class="directory_link">User Management </a> <span class="directory_divider"> / </span> <a href="{{ route('user_management.users_logs', 'users_logs') }}" class="directory_active_link">Users Logs </a>
            </div>
        </div>

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">Users Logs</span>
                            <span class="page_intro_subtitle">This page is an overview of registered users, system roles, and their statuses where you can disable/enable them from the system by toggling the <i class="fa fa-toggle-on" aria-hidden="true"></i> icon. Click the <i class="fa fa-eye" aria-hidden="true"></i> icon to view more information about a specific user.</span>
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/um_users_logs_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="accordion" id="actLogsFilterOptionsCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="actLogsFilterOptionsCollapseHeading">
                            <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#actLogsFilterOptionsCollapseDiv" aria-expanded="true" aria-controls="actLogsFilterOptionsCollapseDiv">
                                <div>
                                    <span class="card_body_title">Filter Options</span>
                                    {{-- <span class="card_body_subtitle">Select available options below to filter desired results.</span> --}}
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="actLogsFilterOptionsCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="actLogsFilterOptionsCollapseHeading" data-parent="#actLogsFilterOptionsCollapseParent">
                            <form id="form_filterUserLogsTable" class="form" method="POST" action="#" enctype="multipart/form-data">
                                @csrf
                                <span class="cust_status_title mb-2">Users Filter Options <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Filter options for specific types of users."></i></span>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="actLogsFiltr_selectUserTypes" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                <option value="0" selected>All User Types</option>
                                                <option value="employee">Employee Users</option>
                                                <option value="student">Student Users</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="actLogsFiltr_selectUserRoles" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                               @php
                                                    $all_roles = App\Models\Userroles::select('uRole_id', 'uRole', 'uRole_type')->get();
                                                @endphp
                                                @if(count($all_roles) > 0)
                                                    <option value="0" data-default-roles="all_roles" selected>All Users Roles</option>
                                                    @foreach($all_roles->sortBy('id') as $select_role)
                                                        <option value="{{$select_role->uRole}}" data-role-type="{{$select_role->uRole_type}}">{{ ucwords($select_role->uRole) }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="" disabled selected>No Roles Found!</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="actLogsFiltr_selectUsers" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                @php
                                                    $all_users = App\Models\Users::select('id', 'user_type', 'user_role', 'user_lname', 'user_fname')->get();
                                                @endphp
                                                @if(count($all_users) > 0)
                                                    <option value="0" data-default-users="all_users" selected>All Users</option>
                                                    @foreach($all_users->sortBy('id') as $select_user)
                                                        <option value="{{$select_user->id}}" data-user-type="{{$select_user->user_type}}" data-user-role="{{$select_user->user_role}}">{{$select_user->user_fname }} {{ $select_user->user_lname }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="" disabled selected>No Users Found!</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <span class="cust_status_title mb-2 mt-3">Logs Filter Options <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Filter options for specific types of users."></i></span>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="actLogsFiltr_selectCategories" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                <option value="0" selected>All Categories</option>
                                                @php
                                                    $all_act_types = App\Models\Useractivites::select('act_type')->groupBy('act_type')->get();
                                                @endphp
                                                @if(count($all_act_types) > 0)
                                                    @foreach($all_act_types->sortBy('id') as $select_category)
                                                        <option value="{{$select_category->act_type}}">{{ucwords($select_category->act_type) }}</option>
                                                    @endforeach
                                                @else
                                                    
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        {{-- <label class="custom_label" for="actLogsFiltr_datepickerRange">Date Range</label> --}}
                                        <input id="actLogsFiltr_datepickerRange" name="actLogsFiltr_datepickerRange" type="text" class="form-control cust_input" placeholder="Select Date Range" />
                                        <input type="hidden" name="hidden_dateRangeFrom" id="hidden_dateRangeFrom">
                                        <input type="hidden" name="hidden_dateRangeTo" id="hidden_dateRangeTo">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12 col-md-12 col-sm-12 text-right">
                                        <a href="#" class="btn btn-success cust_bt_links shadow" role="button"><i class="fa fa-print mr-1" aria-hidden="true"></i> Generate Report</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <div class="card card_gbr card_ofh shadow-none cb_p25 card_body_bg_gray">
                    <div class="row d-flex justify-content-start">
                        <div class="col-lg-5 col-md-8 col-sm-12">
                            <div class="input-group cust_srchInpt_div">
                                <input id="actLogsFiltr_liveSearch" name="actLogsFiltr_liveSearch" type="text" class="form-control cust_srchUsersInpt_box" placeholder="Search Something..." />
                                <i class="nc-icon nc-zoom-split" aria-hidden="true"></i>    
                            </div>
                        </div>
                        {{-- <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="form-group">
                                <select class="form-control cust_selectDropdownBox drpdwn_arrow" id="actLogsFiltr_selectUsers">
                                    <option value="0" selected>All Users</option>
                                    @php
                                        $all_users = App\Models\Users::select('id', 'user_lname', 'user_fname')->get();
                                    @endphp
                                    @if(count($all_users) > 0)
                                        @foreach($all_users->sortBy('id') as $select_user)
                                            <option value="{{$select_user->id}}">{{$select_user->user_fname }} {{ $select_user->user_lname }}</option>
                                        @endforeach
                                    @else
                                        
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="form-group">
                                <select class="form-control cust_selectDropdownBox drpdwn_arrow" id="actLogsFiltr_selectCategories">
                                    <option value="0" selected>All Categories</option>
                                    @php
                                        $all_act_types = App\Models\Useractivites::select('act_type')->groupBy('act_type')->get();
                                    @endphp
                                    @if(count($all_act_types) > 0)
                                        @foreach($all_act_types->sortBy('id') as $select_category)
                                            <option value="{{$select_category->act_type}}">{{ucwords($select_category->act_type) }}</option>
                                        @endforeach
                                    @else
                                        
                                    @endif
                                </select>
                            </div>
                        </div> --}}
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <table class="table table-hover cust_table shadow">
                                <thead class="thead_svms_blue">
                                    <tr>
                                        <th class="pl12">~ Users</th>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody_svms_white" id="usersActLogs_tbody">
                                    {{-- ajax data table --}}
                                    {{-- <tr>
                                        <td class="pl12 d-flex justify-content-start align-items-center">
                                            <img class="rslts_userImgs rslts_emp" src="{{asset('storage/svms/user_images/employee_user_image.jpg')}}" alt="user image">
                                            <div class="cust_td_info">
                                                <span class="actLogs_tdTitle font-weight-bold">John Doe</span>
                                                <span class="actLogs_tdSubTitle"><span class="sub1">20150348 </span> <span class="subDiv"> | </span> <span class="sub2"> Administrator</span></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-inline">
                                                <span class="actLogs_content">January 1, 2021</span>
                                                <span class="actLogs_tdSubTitle sub2">Thursday - 10:40 PM</span>
                                            </div>
                                        </td>
                                        <td><span class="actLogs_content">Profile Update</span></td>
                                        <td><span class="actLogs_content">Mitch Desierto updates his profile infomration</span></td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <span>Total Data: <span class="font-weight-bold" id="total_data_count"> </span> </span>
                        </div>
                        {{-- <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-end">
                            <a href="#" class="btn btn-success cust_bt_links shadow" role="button"><i class="fa fa-print mr-1" aria-hidden="true"></i> Generate Report</a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modals --}}

@endsection

@push('scripts')
{{-- live search --}}
    <script>
        $(document).ready(function(){ 
            // funciton for date range picker
            function loadActLogsTable(){
                var logs_search = document.getElementById('actLogsFiltr_liveSearch');
                var logs_userTypes = document.getElementById("actLogsFiltr_selectUserTypes");
                var logs_userRoles = document.getElementById("actLogsFiltr_selectUserRoles");
                var logs_users = document.getElementById("actLogsFiltr_selectUsers");
                var logs_category = document.getElementById("actLogsFiltr_selectCategories");
                var logs_rangefrom = document.getElementById("hidden_dateRangeFrom");
                var logs_rangeTo = document.getElementById("hidden_dateRangeTo");

                console.log(logs_search.value);
                console.log(logs_userTypes.value);
                console.log(logs_userRoles.value);
                console.log(logs_users.value);
                console.log(logs_category.value);
                console.log(logs_rangefrom.value);
                console.log(logs_rangeTo.value);
                $.ajax({
                    url:"{{ route('violation_records.users_logs_filter_table') }}",
                    method:"GET",
                    data:{
                        logs_search:logs_search, 
                        logs_userTypes:logs_userTypes, 
                        logs_userRoles:logs_userRoles,
                        logs_users:logs_users,
                        logs_category:logs_category,
                        logs_rangefrom:logs_rangefrom,
                        logs_rangeTo:logs_rangeTo
                        },
                    dataType:'json',
                    success:function(data){
                        $('#usersActLogs_tbody').html(data.users_logs_table);
                    }
                });
            }

            // function for capitalizing first letter of a word
            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }

            // daterange picker
            $('#actLogsFiltr_datepickerRange').daterangepicker({
                timePicker: true,
                showDropdowns: true,
                minYear: 2020,
                maxYear: parseInt(moment().format('YYYY'),10),
                drops: 'up',
                opens: 'right',
                autoUpdateInput: false,
                locale: {
                    format: 'MMMM DD, YYYY - hh:mm A',
                    cancelLabel: 'Clear'
                    }
            });
            $('#actLogsFiltr_datepickerRange').on('cancel.daterangepicker', function(ev, picker) {
                document.getElementById("hidden_dateRangeFrom").value = '';
                document.getElementById("hidden_dateRangeTo").value = '';
                $(this).val('');
                $(this).removeClass('cust_input_hasvalue');
                loadActLogsTable();
            });
            $('#actLogsFiltr_datepickerRange').on('apply.daterangepicker', function(ev, picker) {
                // for hidden data range inputs
                var start_range = picker.startDate.format('YYYY-MM-DD HH:MM:SS');
                var end_range = picker.endDate.format('YYYY-MM-DD HH:MM:SS');
                document.getElementById("hidden_dateRangeFrom").value = start_range;
                document.getElementById("hidden_dateRangeTo").value = end_range;
                // for date range display
                $(this).val(picker.startDate.format('MMMM DD, YYYY') + ' - ' + picker.endDate.format('MMMM DD, YYYY'));
                $(this).addClass('cust_input_hasvalue');
                loadActLogsTable();
            });

            // live search filter
            $('#actLogsFiltr_liveSearch').on('keyup', loadActLogsTable);
            
            // user type filter
            $('#actLogsFiltr_selectUserTypes').on('change paste keyup', function(){
                var selectedUserType = $(this).val();
                if(selectedUserType !== 0){
                    document.getElementById("actLogsFiltr_selectUserTypes").classList.add("cust_input_hasvalue");
                    emp_type = 'employee';
                    stud_type = 'student';
                    all_roles = 'all_roles';
                    all_users = 'all_users';
                    if(selectedUserType === 'employee'){
                        // value for System Roles Filter based on selected user type
                        $('#actLogsFiltr_selectUserRoles option[data-role-type="' + stud_type + '"]').hide();
                        $('#actLogsFiltr_selectUserRoles option[data-role-type="' + emp_type + '"]').show();
                        $('#actLogsFiltr_selectUserRoles option[data-default-roles="' + all_roles + '"]').html('All Employee Type Roles');
                        $('#actLogsFiltr_selectUserRoles').val(0);
                        // value for System Users Filter based on selected user type
                        $('#actLogsFiltr_selectUsers option[data-user-type="' + stud_type + '"]').hide();
                        $('#actLogsFiltr_selectUsers option[data-user-type="' + emp_type + '"]').show();
                        $('#actLogsFiltr_selectUsers option[data-default-users="' + all_users + '"]').html('All Employee Type Users');
                        $('#actLogsFiltr_selectUsers').val(0);
                    }else if(selectedUserType === 'student'){
                        // value for System Roles Filter based on selected user type
                        $('#actLogsFiltr_selectUserRoles option[data-role-type="' + emp_type + '"]').hide();
                        $('#actLogsFiltr_selectUserRoles option[data-role-type="' + stud_type + '"]').show();
                        $('#actLogsFiltr_selectUserRoles option[data-default-roles="' + all_roles + '"]').html('All Student Type Roles');
                        $('#actLogsFiltr_selectUserRoles').val(0);
                        // value for System Users Filter based on selected user type
                        $('#actLogsFiltr_selectUsers option[data-user-type="' + stud_type + '"]').show();
                        $('#actLogsFiltr_selectUsers option[data-user-type="' + emp_type + '"]').hide();
                        $('#actLogsFiltr_selectUsers option[data-default-users="' + all_users + '"]').html('All Student Type Users');
                        $('#actLogsFiltr_selectUsers').val(0);
                    }else{
                        // value for System Roles Filter based on selected user type
                        $('#actLogsFiltr_selectUserRoles option[data-role-type="' + stud_type + '"]').show();
                        $('#actLogsFiltr_selectUserRoles option[data-role-type="' + emp_type + '"]').show();
                        $('#actLogsFiltr_selectUserRoles option[data-default-roles="' + all_roles + '"]').html('All Roles');
                        $('#actLogsFiltr_selectUserRoles').val(0);
                        // value for System Users Filter based on selected user type
                        $('#actLogsFiltr_selectUsers option[data-user-type="' + stud_type + '"]').show();
                        $('#actLogsFiltr_selectUsers option[data-user-type="' + emp_type + '"]').show();
                        $('#actLogsFiltr_selectUsers option[data-default-users="' + all_users + '"]').html('All Users');
                        $('#actLogsFiltr_selectUsers').val(0);
                    }
                }else{
                    document.getElementById("actLogsFiltr_selectUserTypes").classList.remove("cust_input_hasvalue");
                }
                loadActLogsTable();
            });

            // user role filter
            $('#actLogsFiltr_selectUserRoles').on('change paste keyup', function(){
                var selectedUserRole = $(this).val();
                if(selectedUserRole != 0){
                    document.getElementById("actLogsFiltr_selectUserRoles").classList.add("cust_input_hasvalue");
                    all_users = 'all_users';
                    emp_type = 'employee';
                    stud_type = 'student';
                    // value for System Users Filter based on selected Role
                    $('#actLogsFiltr_selectUsers option[data-user-role="' + selectedUserRole + '"]').show();
                    $('#actLogsFiltr_selectUsers option[data-user-role!="' + selectedUserRole + '"]').hide();
                    $('#actLogsFiltr_selectUsers option[data-default-users="' + all_users + '"]').html('All ' + capitalizeFirstLetter(selectedUserRole)+'s');
                    $('#actLogsFiltr_selectUsers').val(0);
                }else{
                    // check selected user type first 
                    var sel_user_type = document.getElementById("actLogsFiltr_selectUserTypes");
                    emp_type = 'employee';
                    stud_type = 'student';
                    all_roles = 'all_roles';
                    all_users = 'all_users';
                    // console.log(sel_user_type.value);
                    if(sel_user_type.value === 'employee'){
                        // value for System Roles Filter based on selected user type
                        $('#actLogsFiltr_selectUserRoles option[data-role-type="' + stud_type + '"]').hide();
                        $('#actLogsFiltr_selectUserRoles option[data-role-type="' + emp_type + '"]').show();
                        $('#actLogsFiltr_selectUserRoles option[data-default-roles="' + all_roles + '"]').html('All Employee Type Roles');
                        $('#actLogsFiltr_selectUserRoles').val(0);
                        // value for System Users Filter based on selected user type
                        $('#actLogsFiltr_selectUsers option[data-user-type="' + stud_type + '"]').hide();
                        $('#actLogsFiltr_selectUsers option[data-user-type="' + emp_type + '"]').show();
                        $('#actLogsFiltr_selectUsers option[data-default-users="' + all_users + '"]').html('All Employee Type Users');
                        $('#actLogsFiltr_selectUsers').val(0);
                    }else if(sel_user_type.value === 'student'){
                        // value for System Roles Filter based on selected user type
                        $('#actLogsFiltr_selectUserRoles option[data-role-type="' + emp_type + '"]').hide();
                        $('#actLogsFiltr_selectUserRoles option[data-role-type="' + stud_type + '"]').show();
                        $('#actLogsFiltr_selectUserRoles option[data-default-roles="' + all_roles + '"]').html('All Student Type Roles');
                        $('#actLogsFiltr_selectUserRoles').val(0);
                        // value for System Users Filter based on selected user type
                        $('#actLogsFiltr_selectUsers option[data-user-type="' + stud_type + '"]').show();
                        $('#actLogsFiltr_selectUsers option[data-user-type="' + emp_type + '"]').hide();
                        $('#actLogsFiltr_selectUsers option[data-default-users="' + all_users + '"]').html('All Student Type Users');
                        $('#actLogsFiltr_selectUsers').val(0);
                    }else{
                        // value for System Roles Filter based on selected user type
                        $('#actLogsFiltr_selectUserRoles option[data-role-type="' + stud_type + '"]').show();
                        $('#actLogsFiltr_selectUserRoles option[data-role-type="' + emp_type + '"]').show();
                        $('#actLogsFiltr_selectUserRoles option[data-default-roles="' + all_roles + '"]').html('All Roles');
                        $('#actLogsFiltr_selectUserRoles').val(0);

                        $('#actLogsFiltr_selectUsers option').show();
                        document.getElementById("actLogsFiltr_selectUserRoles").classList.remove("cust_input_hasvalue");
                        $('#actLogsFiltr_selectUsers option[data-default-users]').html('All Users');
                    }
                }
                loadActLogsTable();
            });

            // user filter
            $('#actLogsFiltr_selectUsers').on('change paste keyup', function(){
                var selectedUser = $(this).val();
                if(selectedUser !== 0){
                    document.getElementById("actLogsFiltr_selectUsers").classList.add("cust_input_hasvalue");
                }else{
                    document.getElementById("actLogsFiltr_selectUsers").classList.remove("cust_input_hasvalue");
                }
                loadActLogsTable();
            });

            // act category filter
            $('#actLogsFiltr_selectCategories').on('change paste keyup', function(){
                var selectedCategory = $(this).val();
                if(selectedCategory !== 0){
                    document.getElementById("actLogsFiltr_selectCategories").classList.add("cust_input_hasvalue");
                }else{
                    document.getElementById("actLogsFiltr_selectCategories").classList.remove("cust_input_hasvalue");
                }
                loadActLogsTable();
            });
        });
    </script>
{{-- live search end --}}
@endpush