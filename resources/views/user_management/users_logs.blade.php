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
                                        <input id="actLogsFiltr_datepickerRange" name="actLogsFiltr_datepickerRange" type="text" class="form-control cust_input" placeholder="Select Date Range" />
                                        <input type="hidden" name="hidden_dateRangeFrom" id="hidden_dateRangeFrom">
                                        <input type="hidden" name="hidden_dateRangeTo" id="hidden_dateRangeTo">
                                        {{-- @php
                                            $count_actLogs = App\Models\Useractivites::all()->count();
                                        @endphp --}}
                                        <input type="hidden" name="hidden_totalDataFound" id="hidden_totalDataFound">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12 col-md-12 col-sm-12 text-right">
                                        <button type="button" id="generateActLogs_btn" onclick="generateActLogs_modal()" class="btn btn-success cust_bt_links shadow"><i class="nc-icon nc-single-copy-04 mr-1" aria-hidden="true"></i> Generate Report</button>
                                        <button type="button" id="resetActLogsFilter_btn" class="btn btn_svms_blue cust_bt_links shadow" disabled><i class="fa fa-refresh mr-1" aria-hidden="true"></i> Reset</button>
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
                        {{-- <div class="col-lg-7 col-md-4 col-sm-2">
                            <div class="form-group">
                                <select id="actLogsFiltr_numOfRows" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div> --}}
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12 col-sm-12">
                            <span class="cust_table_filters_title"> Filters: </span>
                            <span id="filter_userTypes_txt" class="cust_table_filters_texts"> All User Types </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_userRoles_txt" class="cust_table_filters_texts"> All User Roles </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_users_txt" class="cust_table_filters_texts"> All Users </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_logCat_txt" class="cust_table_filters_texts"> All Log Categories </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_date_txt" class="cust_table_filters_texts"> From Previous Days up to this day </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_liveSearch_txt" class="cust_table_filters_texts"> ...</span>
                        </div>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-lg-4 col-md-4 col-sm-12 text-left">
                            <span>Total Data: <span class="font-weight-bold" id="total_data_count"> </span> </span>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 d-flex justify-content-end">
                            @csrf
                            <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                            <div id="usersActLogs_pagination">

                            </div>
                            {{-- <a href="#" class="btn btn-success cust_bt_links shadow" role="button"><i class="fa fa-print mr-1" aria-hidden="true"></i> Generate Report</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modals --}}
    {{-- generate users logs report confirmation modal --}}
        <div class="modal fade" id="generateActLogsConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="generateActLogsConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="generateActLogsConfirmationModalLabel">Generate Report?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="generateActLogsConfirmationHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- generate users logs report confirmation modal end --}}

@endsection

@push('scripts')
{{-- Activity Logs Filter --}}
    <script>
        // function for ajax table pagination
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
        // function for ajax table pagination end
        
        $(document).ready(function(){ 
            loadActLogsTable();

            // funciton for loading activity logs table
            function loadActLogsTable(){
                var logs_search = document.getElementById('actLogsFiltr_liveSearch').value;
                var logs_userTypes = document.getElementById("actLogsFiltr_selectUserTypes").value;
                var logs_userRoles = document.getElementById("actLogsFiltr_selectUserRoles").value;
                var logs_users = document.getElementById("actLogsFiltr_selectUsers").value;
                var logs_category = document.getElementById("actLogsFiltr_selectCategories").value;
                var logs_rangefrom = document.getElementById("hidden_dateRangeFrom").value;
                var logs_rangeTo = document.getElementById("hidden_dateRangeTo").value;
                var page = document.getElementById("hidden_page").value;
                const ajax_logs_totalData = 0;

                console.log(logs_search);
                console.log(logs_userTypes);
                console.log(logs_userRoles);
                console.log(logs_users);
                console.log(logs_category);
                console.log(logs_rangefrom);
                console.log(logs_rangeTo);
                console.log(page);

                $.ajax({
                    url:"{{ route('user_management.users_logs') }}",
                    method:"GET",
                    data:{
                        logs_search:logs_search, 
                        logs_userTypes:logs_userTypes, 
                        logs_userRoles:logs_userRoles,
                        logs_users:logs_users,
                        logs_category:logs_category,
                        logs_rangefrom:logs_rangefrom,
                        logs_rangeTo:logs_rangeTo,
                        page:page
                        },
                    dataType:'json',
                    success:function(data){
                        $('#usersActLogs_tbody').html(data.users_logs_table);
                        $('#usersActLogs_pagination').html(data.paginate);
                        $('#total_data_count').html(data.total_rows);
                        $('#hidden_totalDataFound').val(data.total_data_found);

                        // for disabling/ enabling generate report button
                        var logs_totalData = document.getElementById("hidden_totalDataFound").value;
                        console.log(logs_totalData);
                        if(logs_totalData > 0){
                            $('#generateActLogs_btn').prop('disabled', false);
                        }else{
                            $('#generateActLogs_btn').prop('disabled', true);
                        }
                    }
                });
                
                // for disabling/ enabling reset filter button
                if(logs_userTypes != 0 || logs_userRoles != 0 || logs_users != 0 || logs_category != 0 || logs_rangefrom != '' || logs_rangeTo != ''){
                    $('#resetActLogsFilter_btn').prop('disabled', false);
                }else{
                    $('#resetActLogsFilter_btn').prop('disabled', true);
                }
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
                // inner HTML for filter texts
                $('#filter_date_txt').html('From Previous Days up to this day');
                document.getElementById("filter_date_txt").classList.remove("font-weight-bold");
                // table paginatin set to 1
                $('#hidden_page').val(1);
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
                // inner HTML for filter texts
                $('#filter_date_txt').html('From ' + picker.startDate.format('MMMM DD, YYYY') + ' to ' + picker.endDate.format('MMMM DD, YYYY'));
                document.getElementById("filter_date_txt").classList.add("font-weight-bold");
                // table paginatin set to 1
                $('#hidden_page').val(1);
                loadActLogsTable();
            });

            // live search filter
            $('#actLogsFiltr_liveSearch').on('keyup', function(){
                var liveSearchValue = $(this).val();
                if(liveSearchValue != ''){
                    $('#filter_liveSearch_txt').html(liveSearchValue);
                    document.getElementById("filter_liveSearch_txt").classList.add("font-weight-bold");
                }else{
                    $('#filter_liveSearch_txt').html('...');
                    document.getElementById("filter_liveSearch_txt").classList.remove("font-weight-bold");
                }
                $('#hidden_page').val(1);
                loadActLogsTable();
            });

            // user type filter
            $('#actLogsFiltr_selectUserTypes').on('change paste keyup', function(){
                var selectedUserType = $(this).val();
                // table paginatin set to 1
                $('#hidden_page').val(1);
                emp_type = 'employee';
                stud_type = 'student';
                all_roles = 'all_roles';
                all_users = 'all_users';
                if(selectedUserType != 0){
                    document.getElementById("actLogsFiltr_selectUserTypes").classList.add("cust_input_hasvalue");
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
                        // inner HTML for filter texts
                        $('#filter_userTypes_txt').html('Employee Type Users');
                        $('#filter_userRoles_txt').html('All Employee Type Roles');
                        $('#filter_users_txt').html('All Employee Type Users');
                        document.getElementById("filter_userTypes_txt").classList.add("font-weight-bold");
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
                        // inner HTML for filter texts
                        $('#filter_userTypes_txt').html('Student Type Users');
                        $('#filter_userRoles_txt').html('All Student Type Roles');
                        $('#filter_users_txt').html('All Student Type Users');
                        document.getElementById("filter_userTypes_txt").classList.add("font-weight-bold");
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
                        // inner HTML for filter texts
                        $('#filter_userTypes_txt').html('All User Types');
                        $('#filter_userRoles_txt').html('All User Roles');
                        $('#filter_users_txt').html('All Users');
                        document.getElementById("filter_userTypes_txt").classList.remove("font-weight-bold");
                    }
                }else{
                    document.getElementById("actLogsFiltr_selectUserTypes").classList.remove("cust_input_hasvalue");
                    // value for System Roles Filter based on selected user type
                    $('#actLogsFiltr_selectUserRoles option[data-role-type="' + stud_type + '"]').show();
                    $('#actLogsFiltr_selectUserRoles option[data-role-type="' + emp_type + '"]').show();
                    $('#actLogsFiltr_selectUserRoles option[data-default-roles="' + all_roles + '"]').html('All Roles');
                    $('#actLogsFiltr_selectUserRoles').val(0);
                    document.getElementById("actLogsFiltr_selectUserRoles").classList.remove("cust_input_hasvalue");
                    // value for System Users Filter based on selected user type
                    $('#actLogsFiltr_selectUsers option[data-user-type="' + stud_type + '"]').show();
                    $('#actLogsFiltr_selectUsers option[data-user-type="' + emp_type + '"]').show();
                    $('#actLogsFiltr_selectUsers option[data-default-users="' + all_users + '"]').html('All Users');
                    $('#actLogsFiltr_selectUsers').val(0);
                    document.getElementById("actLogsFiltr_selectUsers").classList.remove("cust_input_hasvalue");
                    // inner HTML for filter texts
                    $('#filter_userTypes_txt').html('All User Types');
                    $('#filter_userRoles_txt').html('All Users Roles');
                    $('#filter_users_txt').html('All Users');
                    document.getElementById("filter_userTypes_txt").classList.remove("font-weight-bold");
                    document.getElementById("filter_userRoles_txt").classList.remove("font-weight-bold");
                    document.getElementById("filter_users_txt").classList.remove("font-weight-bold");
                }
                loadActLogsTable();
            });

            // user role filter
            $('#actLogsFiltr_selectUserRoles').on('change paste keyup', function(){
                var selectedUserRole = $(this).val();
                // table paginatin set to 1
                $('#hidden_page').val(1);
                if(selectedUserRole != 0){
                    document.getElementById("actLogsFiltr_selectUserRoles").classList.add("cust_input_hasvalue");
                    all_roles = 'all_roles';
                    all_users = 'all_users';
                    emp_type = 'employee';
                    stud_type = 'student';
                    // value for System Users Filter based on selected Role
                    $('#actLogsFiltr_selectUsers option[data-user-role="' + selectedUserRole + '"]').show();
                    $('#actLogsFiltr_selectUsers option[data-user-role!="' + selectedUserRole + '"]').hide();
                    $('#actLogsFiltr_selectUsers option[data-default-users="' + all_users + '"]').html('All ' + capitalizeFirstLetter(selectedUserRole)+'s');
                    $('#actLogsFiltr_selectUsers').val(0);
                    // inner HTML for filter texts
                    $('#filter_userRoles_txt').html(capitalizeFirstLetter(selectedUserRole) + ' Role');
                    $('#filter_users_txt').html('All ' + capitalizeFirstLetter(selectedUserRole)+'s');
                    document.getElementById("filter_userRoles_txt").classList.add("font-weight-bold");
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
                        // inner HTML for filter texts
                        $('#filter_userRoles_txt').html('All Employee Type Roles');
                        $('#filter_users_txt').html('All Employee Type Users');
                        document.getElementById("filter_userRoles_txt").classList.add("font-weight-bold");
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
                        // inner HTML for filter texts
                        $('#filter_userRoles_txt').html('All Student Type Roles');
                        $('#filter_users_txt').html('All Student Type Users');
                        document.getElementById("filter_userRoles_txt").classList.add("font-weight-bold");
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
                        document.getElementById("actLogsFiltr_selectUsers").classList.remove("cust_input_hasvalue");
                        $('#actLogsFiltr_selectUsers').val(0);
                        document.getElementById("actLogsFiltr_selectUserRoles").classList.remove("cust_input_hasvalue");
                        // inner HTML for filter texts
                        $('#filter_userRoles_txt').html('All Users Roles');
                        $('#filter_users_txt').html('All Users');
                        document.getElementById("filter_userRoles_txt").classList.remove("font-weight-bold");
                        document.getElementById("filter_users_txt").classList.remove("font-weight-bold");
                    }
                }
                loadActLogsTable();
            });

            // user filter
            $('#actLogsFiltr_selectUsers').on('change paste keyup', function(){
                var selectedUser = $(this).val();
                // table paginatin set to 1
                $('#hidden_page').val(1);
                if(selectedUser != 0){
                    document.getElementById("actLogsFiltr_selectUsers").classList.add("cust_input_hasvalue");
                    document.getElementById("filter_users_txt").classList.add("font-weight-bold");
                    var selectedUser_id = document.getElementById('actLogsFiltr_selectUsers').value;
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('user_management.users_logs_filter_table_user_info') }}",
                        method:"GET",
                        data:{selectedUser_id:selectedUser_id, _token:_token},
                        // dataType:'json',
                        success:function(data){
                            $('#filter_users_txt').html(data);
                        }
                    });
                }else{
                    document.getElementById("actLogsFiltr_selectUsers").classList.remove("cust_input_hasvalue");
                    document.getElementById("filter_users_txt").classList.remove("font-weight-bold");
                    $('#filter_users_txt').html('All Users');
                }
                loadActLogsTable();
            });

            // act category filter
            $('#actLogsFiltr_selectCategories').on('change paste keyup', function(){
                var selectedCategory = $(this).val();
                // table paginatin set to 1
                $('#hidden_page').val(1);
                if(selectedCategory != 0){
                    document.getElementById("actLogsFiltr_selectCategories").classList.add("cust_input_hasvalue");
                    $('#filter_logCat_txt').html(capitalizeFirstLetter(selectedCategory) + ' histories');
                    document.getElementById("filter_logCat_txt").classList.add("font-weight-bold");
                }else{
                    document.getElementById("actLogsFiltr_selectCategories").classList.remove("cust_input_hasvalue");
                    $('#filter_logCat_txt').html('All Log History');
                    document.getElementById("filter_logCat_txt").classList.remove("font-weight-bold");
                }
                loadActLogsTable();
            });

            // reset filter
            $('#resetActLogsFilter_btn').on('click', function(){
                emp_type = 'employee';
                stud_type = 'student';
                all_roles = 'all_roles';
                all_users = 'all_users';
                // table paginatin set to 1
                $('#hidden_page').val(1);
                // User Type
                document.getElementById("actLogsFiltr_selectUserTypes").classList.remove("cust_input_hasvalue");
                $('#actLogsFiltr_selectUserTypes').val(0);
                // User Role
                $('#actLogsFiltr_selectUserRoles option[data-role-type="' + stud_type + '"]').show();
                $('#actLogsFiltr_selectUserRoles option[data-role-type="' + emp_type + '"]').show();
                $('#actLogsFiltr_selectUserRoles option[data-default-roles="' + all_roles + '"]').html('All Roles');
                document.getElementById("actLogsFiltr_selectUserRoles").classList.remove("cust_input_hasvalue");
                $('#actLogsFiltr_selectUserRoles').val(0);
                // Users
                $('#actLogsFiltr_selectUsers option[data-user-type="' + stud_type + '"]').show();
                $('#actLogsFiltr_selectUsers option[data-user-type="' + emp_type + '"]').show();
                $('#actLogsFiltr_selectUsers option[data-default-users="' + all_users + '"]').html('All Users');
                document.getElementById("actLogsFiltr_selectUsers").classList.remove("cust_input_hasvalue");
                $('#actLogsFiltr_selectUsers').val(0);
                // inner HTML for filter texts
                $('#filter_userTypes_txt').html('All User Types');
                $('#filter_userRoles_txt').html('All User Roles');
                $('#filter_users_txt').html('All Users');
                document.getElementById("filter_userTypes_txt").classList.remove("font-weight-bold");
                document.getElementById("filter_userRoles_txt").classList.remove("font-weight-bold");
                document.getElementById("filter_users_txt").classList.remove("font-weight-bold");
                // categories
                document.getElementById("actLogsFiltr_selectCategories").classList.remove("cust_input_hasvalue");
                $('#actLogsFiltr_selectCategories').val(0);
                $('#filter_logCat_txt').html('All Log History');
                document.getElementById("filter_logCat_txt").classList.remove("font-weight-bold");
                // for hidden data range inputs
                document.getElementById("hidden_dateRangeFrom").value = '';
                document.getElementById("hidden_dateRangeTo").value = '';
                // for date range display
                document.getElementById("actLogsFiltr_datepickerRange").classList.remove("cust_input_hasvalue");
                document.getElementById("actLogsFiltr_datepickerRange").value = '';
                // inner HTML for filter texts
                $('#filter_date_txt').html('From Previous Days up to this day');
                document.getElementById("filter_date_txt").classList.remove("font-weight-bold");
                $(this).prop('disabled', true);
                loadActLogsTable();
            });

            // hanle page link
            $(document).on('click', '.pagination a', function(event){
                event.preventDefault();
                
                var page = $(this).attr('href').split('page=')[1];
                $('#hidden_page').val(page);
                console.log($(this).val());

                loadActLogsTable();
                getData(page);
                $('li.page-item').removeClass('active');
                $(this).parent('li.page-item').addClass('active');
            });
        });
    </script>
{{-- Activity Logs Filter end --}}

{{-- generate activity logs for print --}}
    <script>
        function generateActLogs_modal(){
            // values
            var logs_search = document.getElementById('actLogsFiltr_liveSearch').value;
            var logs_userTypes = document.getElementById("actLogsFiltr_selectUserTypes").value;
            var logs_userRoles = document.getElementById("actLogsFiltr_selectUserRoles").value;
            var logs_users = document.getElementById("actLogsFiltr_selectUsers").value;
            var logs_category = document.getElementById("actLogsFiltr_selectCategories").value;
            var logs_rangefrom = document.getElementById("hidden_dateRangeFrom").value;
            var logs_rangeTo = document.getElementById("hidden_dateRangeTo").value;
            var logs_totalData = document.getElementById("hidden_totalDataFound").value;
            var _token = $('input[name="_token"]').val();

            // inner texts
            var txt_logs_userTypes = document.getElementById("filter_userTypes_txt").innerText;
            var txt_logs_userRoles = document.getElementById("filter_userRoles_txt").innerText;
            var txt_logs_users = document.getElementById("filter_users_txt").innerText;
            var txt_logs_category = document.getElementById("filter_logCat_txt").innerText;

            console.log(logs_search);
            console.log(logs_userTypes);
            console.log(logs_userRoles);
            console.log(logs_users);
            console.log(logs_category);
            console.log(logs_rangefrom);
            console.log(logs_rangeTo);
            console.log(logs_totalData);

            $.ajax({
                url:"{{ route('user_management.generate_act_logs_confirmation_modal') }}",
                method:"GET",
                data:{
                    logs_search:logs_search, 
                    logs_userTypes:logs_userTypes, 
                    logs_userRoles:logs_userRoles, 
                    logs_users:logs_users, 
                    logs_category:logs_category, 
                    logs_rangefrom:logs_rangefrom,
                    logs_rangeTo:logs_rangeTo,
                    logs_totalData:logs_totalData,
                    txt_logs_userTypes:txt_logs_userTypes, 
                    txt_logs_userRoles:txt_logs_userRoles,
                    txt_logs_users:txt_logs_users,
                    txt_logs_category:txt_logs_category,
                    _token:_token
                    },
                success: function(data){
                    $('#generateActLogsConfirmationHtmlData').html(data); 
                    $('#generateActLogsConfirmationModal').modal('show');
                }
            });
        }
    </script>
{{-- generate activity logs for print --}}
@endpush