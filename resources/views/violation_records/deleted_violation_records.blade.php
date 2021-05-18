@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'deleted_violation_records'
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
                <a href="{{ route('violation_records.deleted_violation_records', 'deleted_violation_records') }}" class="directory_active_link">Deleted Violations</a>
            </div>
        </div>

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">Deleted Violations</span>
                            <span class="page_intro_subtitle">This page shows you the list of all deleted violations. You will have the option to recover deleted violations or delete violations permanently. You can filter the table below to view desired outputs and generate report for print or digital copy purposes.</span>
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/deleted_violation_records_illustration2.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- filter options --}}
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="accordion gCardAccordions" id="delViolationRecFiltrOptionsCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="delViolationRecFiltrOptionsCollapseHeading">
                            <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#delViolationRecFiltrOptionsCollapseDiv" aria-expanded="true" aria-controls="delViolationRecFiltrOptionsCollapseDiv">
                                <div>
                                    <span class="card_body_title">Filter Options</span>
                                    {{-- <span class="card_body_subtitle">Select available options below to filter desired results.</span> --}}
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="delViolationRecFiltrOptionsCollapseDiv" class="collapse gCardAccordions_collapse show cb_t0b15x25" aria-labelledby="delViolationRecFiltrOptionsCollapseHeading" data-parent="#delViolationRecFiltrOptionsCollapseParent">
                            <form id="form_filterViolationRecTable" class="form" method="POST" action="{{route('violation_records.report_violations_records')}}" enctype="multipart/form-data">
                                @csrf
                                <span class="cust_status_title mb-2">Students Filter Options <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Filter options for specific students."></i></span>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="delViolationRecFltr_schools" name="delViolationRecFltr_schools" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                <option value="0" selected>All Schools</option>
                                                <option value="SASE">SASE</option>
                                                <option value="SBCS">SBCS</option>
                                                <option value="SIHTM">SIHTM</option>
                                                <option value="SHSP">SHSP</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="delViolationRecFltr_programs" name="delViolationRecFltr_programs" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                <option value="0" data-default-program="all_programs" selected>All Programs</option>
                                                <option value="BS Psychology" data-programs="SASE">BS Psychology</option>
                                                <option value="BS Education" data-programs="SASE">BS Education</option>
                                                <option value="BA Communication" data-programs="SASE">BA Communication</option>

                                                <option value="BSBA" data-programs="SBCS">BSBA</option>
                                                <option value="BSA" data-programs="SBCS">BSA</option>
                                                <option value="BSIT" data-programs="SBCS">BSIT</option>
                                                <option value="BMA" data-programs="SBCS">BMA</option>

                                                <option value="BSHM" data-programs="SIHTM">BSHM</option>
                                                <option value="BSTM" data-programs="SIHTM">BSTM</option>

                                                <option value="BS Biology" data-programs="SHSP">BS Biology</option>
                                                <option value="BS Pharmacy" data-programs="SHSP">BS Pharmacy</option>
                                                <option value="BS Radiologic Technology" data-programs="SHSP">BS Radiologic Technology</option>
                                                <option value="BS Physical Therapy" data-programs="SHSP">BS Physical Therapy</option>
                                                <option value="BS Medical Technology" data-programs="SHSP">BS Medical Technology</option>
                                                <option value="BS Nursing" data-programs="SHSP">BS Nursing</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="delViolationRecFltr_yearLvls" name="delViolationRecFltr_yearLvls" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                <option value="0" data-default-yearlvl="all_year_levels" selected>All Year Levels</option>
                                                <option value="1" data-yearlvls="1">FIRST YEAR</option>
                                                <option value="2" data-yearlvls="2">SECOND YEARS</option>
                                                <option value="3" data-yearlvls="3">THIRD YEARS</option>
                                                <option value="4" data-yearlvls="4">FOURTH YEARS</option>
                                                <option value="5" data-yearlvls="5">FIFTH YEARS</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="delViolationRecFltr_genders" name="delViolationRecFltr_genders" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                <option value="0" selected>All Genders</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $max_age = App\Models\Students::select('Age')->max('Age');
                                    $min_age = App\Models\Students::select('Age')->min('Age');
                                    $all_age = App\Models\Students::select('Age')->groupBy('Age')->get();
                                @endphp
                                <input type="hidden" name="delViolationRecFltr_hidden_maxAgeRange" id="delViolationRecFltr_hidden_maxAgeRange" value="{{$max_age}}">
                                <input type="hidden" name="delViolationRecFltr_hidden_minAgeRange" id="delViolationRecFltr_hidden_minAgeRange" value="{{$min_age}}">
                                <span class="cust_status_title mt-2 mb-2">Age Range: </span> <span class="custom_label_sub" id="filter_ageRange_label"> {{$min_age}} to {{$max_age}} Year Olds</span></label>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 pr-1">
                                                <div class="form-group">
                                                    <select id="delViolationRecFltr_minAge" name="delViolationRecFltr_minAge" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                        @foreach($all_age as $this_age)
                                                            @if($this_age->Age == $min_age)
                                                                <option value="{{$this_age->Age}}" selected>{{$this_age->Age}}</option>
                                                            @else
                                                                <option value="{{$this_age->Age}}">{{$this_age->Age}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 pl-1">
                                                <div class="form-group">
                                                    <select id="delViolationRecFltr_maxAge" name="delViolationRecFltr_maxAge" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                        @foreach($all_age->sortByDesc('Age') as $this_age)
                                                            @if($this_age->Age == $max_age)
                                                                <option value="{{$this_age->Age}}" selected>{{$this_age->Age}}</option>
                                                            @else
                                                                <option value="{{$this_age->Age}}">{{$this_age->Age}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="cust_status_title mt-2 mb-2">Violations Filter Options <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Filter options for specific Violations."></i></span>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="delViolationRecFltr_violationStat" name="delViolationRecFltr_violationStat" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                <option value="0" selected>All Violation Status</option>
                                                <option value="not cleared">Not Cleared</option>
                                                <option value="cleared">Cleared</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <input id="violationRecFltr_datepickerRange" name="violationRecFltr_datepickerRange" type="text" class="form-control cust_input" placeholder="Select Date Range" data-toggle="tooltip" data-placement="top" title="Filter Deleted Violations by Date." readonly />
                                        <input type="hidden" name="delViolationRecFltr_hidden_dateRangeFrom" id="delViolationRecFltr_hidden_dateRangeFrom">
                                        <input type="hidden" name="delViolationRecFltr_hidden_dateRangeTo" id="delViolationRecFltr_hidden_dateRangeTo">
                                        {{-- @php
                                            $count_actLogs = App\Models\Useractivites::all()->count();
                                        @endphp --}}
                                        <input type="hidden" name="delVr_hiddenTotalData_found" id="delVr_hiddenTotalData_found">
                                    </div>
                                </div>
                                <span class="cust_status_title mt-3 mb-2">Order By: </span>
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-12 pr-0">
                                        <div class="form-group">
                                            <select id="delViolationRecFltr_orderBy" name="delViolationRecFltr_orderBy" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                <option value="0" selected>Date Deleted</option>
                                                <option value="1">Student Number</option>
                                                <option value="2">Offense Count</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 pl-0 d-flex justify-content-end">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn_svms_blue cust_btn_radio cbr_p" data-toggle="tooltip" data-placement="top" title="Ascending Order?">
                                                <input class="m-0 p-0" type="radio" name="delViolationRecFltr_orderByRange" id="delViolationRecFltr_orderByRange_ASC" value="asc" autocomplete="off"> <i class="fa fa-sort-amount-asc cbr_i" aria-hidden="true"></i>
                                            </label>
                                            <label class="btn btn_svms_blue cust_btn_radio cbr_p active" data-toggle="tooltip" data-placement="top" title="Descending Order?">
                                                <input class="m-0 p-0" type="radio" name="delViolationRecFltr_orderByRange" id="delViolationRecFltr_orderByRange_DESC" value="desc" autocomplete="off" checked> <i class="fa fa-sort-amount-desc cbr_i" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12 col-md-12 col-sm-12 text-right">
                                        {{-- <button type="submit" id="generateDelViolationRecs_btn" class="btn btn-success cust_bt_links shadow"><i class="nc-icon nc-single-copy-04 mr-1" aria-hidden="true"></i> Generate Report</button> --}}
                                        {{-- <button type="button" onclick="confirm_generateViolationRecReport()" id="generateDelViolationRecs_btn" class="btn btn-success cust_bt_links shadow"><i class="nc-icon nc-single-copy-04 mr-1" aria-hidden="true"></i> Generate Report</button> --}}
                                        <button type="button" id="resetDelViolationRecsFilter_btn" class="btn btn_svms_blue cust_bt_links shadow" disabled><i class="fa fa-refresh mr-1" aria-hidden="true"></i> Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <div class="card card_gbr card_ofh shadow-none cb_p25 card_body_bg_gray">
                    <div class="row">
                        <div class="col-lg-5 col-md-8 col-sm-12 d-flex justify-content-start">
                            <div class="input-group cust_srchInpt_div">
                                <input id="delViolationRecsFiltr_liveSearch" name="delViolationRecsFiltr_liveSearch" type="text" class="form-control cust_srchUsersInpt_box" placeholder="Search Something..." />
                                <i class="nc-icon nc-zoom-split" aria-hidden="true"></i>    
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-4 col-sm-2 d-flex justify-content-end align-items-center">
                            <span class="custom_label_subv1 mr-3">Number of Rows </span>
                            <div class="form-group my-0" style="width:80px;">
                                <select id="delViolationRecsFiltr_numOfRows" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="75">75</option>
                                    <option value="100">100</option>
                                    <option value="250">250</option>
                                    <option value="500">500</option>
                                </select>
                            </div>

                            @php
                                $check_recordsDeletedViolations = App\Models\Deletedviolations::where('del_status', 1)->count();
                            @endphp
                            @if($check_recordsDeletedViolations > 0)
                                <button id="permanentDeleteAll_delViolations_btn" onclick="deleteAll_DelViolations()" class="btn cust_btn_smcircle5 ml-3" data-toggle="tooltip" data-placement="top" title="Permanently Delete all Recently Deleted Violations?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            @endif

                            {{-- <div class="btn-group btn-group-toggle ml-3" data-toggle="buttons">
                                <label class="btn btn_svms_blue active">
                                    <input type="radio" name="options" id="option1" autocomplete="off" checked> <i class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                                </label>
                                <label class="btn btn_svms_blue">
                                    <input type="radio" name="options" id="option2" autocomplete="off"> <i class="fa fa-sort-amount-desc" aria-hidden="true"></i>
                                </label>
                            </div> --}}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <table class="table table-hover cust_table shadow">
                                <thead class="thead_svms_blue">
                                    <tr>
                                        <th class="pl12">~ Students</th>
                                        <th data-toggle="tooltip" data-placement="top" title="Below Columns are the dates which the recorded violation has been deleted.">Date Deleted</th>
                                        <th data-toggle="tooltip" data-placement="top" title="Below Columns shows the Date a violation has been committed, offense count, and the status of the recorded violations.">Offense Details</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody_svms_white" id="delVr_tableTbody">
                                    {{-- ajax data table --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-lg-4 col-md-4 col-sm-12 text-left">
                            <span>Total Data: <span class="font-weight-bold" id="delVr_tableTotalData_count"> </span> </span>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 d-flex justify-content-end">
                            @csrf
                            <input type="hidden" name="delVr_hidden_page" id="delVr_hidden_page" value="1" />
                            <div id="delVr_tablePagination">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    {{-- modals --}}
    {{-- generate violation records report confirmation modal --}}
        <div class="modal fade" id="permanentDeleteAllViolationsRecordsConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="permanentDeleteAllViolationsRecordsConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="permanentDeleteAllViolationsRecordsConfirmationModalLabel">Permenent Delete All Violations?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="permanentDeleteAllViolationsRecordsConfirmationHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- generate violation records report confirmation modal end --}}
@endsection

@push('scripts')

    {{-- view student's offenses --}}
        <script>
            function viewStudentOffenses(violator_id){
                var violator_id = violator_id;
                // console.log(violator_id);
                window.location = "violator/"+violator_id;
            }
        </script>
    {{-- view student's offenses end --}}

    {{-- violation records table --}}
        <script>
            $(document).ready(function(){
                load_delViolationRec_table();

                // load_delViolationRec_table()
                    function load_delViolationRec_table(){
                        // get all filtered values
                        var dvr_search = document.getElementById('delViolationRecsFiltr_liveSearch').value;
                        var dvr_schools = document.getElementById('delViolationRecFltr_schools').value;
                        var dvr_programs = document.getElementById('delViolationRecFltr_programs').value;
                        var dvr_yearlvls = document.getElementById('delViolationRecFltr_yearLvls').value;
                        var dvr_genders = document.getElementById('delViolationRecFltr_genders').value;
                        var dvr_minAgeRange = document.getElementById('delViolationRecFltr_minAge').value;
                        var dvr_maxAgeRange = document.getElementById('delViolationRecFltr_maxAge').value;
                        var ddf_minAgeRange = document.getElementById('delViolationRecFltr_hidden_minAgeRange').value;
                        var ddf_maxAgeRange = document.getElementById('delViolationRecFltr_hidden_maxAgeRange').value;
                        var dvr_status = document.getElementById('delViolationRecFltr_violationStat').value;
                        var dvr_rangefrom = document.getElementById("delViolationRecFltr_hidden_dateRangeFrom").value;
                        var dvr_rangeTo = document.getElementById("delViolationRecFltr_hidden_dateRangeTo").value;
                        var dvr_orderBy = document.getElementById("delViolationRecFltr_orderBy").value;
                        var dselectedOrderByRange = document.querySelector('input[type=radio][name=delViolationRecFltr_orderByRange]:checked').value;  
                        var dvr_numRows = document.getElementById("delViolationRecsFiltr_numOfRows").value;
                        var page = document.getElementById("delVr_hidden_page").value;
                        
                        // update age range label
                        if(dvr_minAgeRange == dvr_maxAgeRange){
                            $('#filter_ageRange_label').html('All ' + dvr_minAgeRange + ' Year Olds');
                        }else{
                            $('#filter_ageRange_label').html(dvr_minAgeRange + ' to ' + dvr_maxAgeRange + ' Year Olds');
                        }

                        dvr_numRows = parseInt(dvr_numRows);

                        console.log('_____________________________');
                        console.log('search_filter: ' + dvr_search);
                        console.log('school_filter: ' + dvr_schools);
                        console.log('course_filter: ' + dvr_programs);
                        console.log('year_level_filter: ' + dvr_yearlvls);
                        console.log('gender_filter: ' + dvr_genders);
                        console.log('min_Age_filter: ' + dvr_minAgeRange);
                        console.log('max_Age_filter: ' + dvr_maxAgeRange);
                        console.log('violation_status_filter: ' + dvr_status);
                        console.log('date_from_filter: ' + dvr_rangefrom);
                        console.log('date_filter: ' + dvr_rangeTo);
                        console.log('order by: ' + dvr_orderBy);
                        console.log('order by Range: ' + dselectedOrderByRange);
                        console.log('number of rows: ' + dvr_numRows);
                        console.log('current_page: ' + page);
                        console.log('');

                        $.ajax({
                            url:"{{ route('violation_records.deleted_violation_records') }}",
                            method:"GET",
                            data:{
                                dvr_search:dvr_search, 
                                dvr_schools:dvr_schools, 
                                dvr_programs:dvr_programs, 
                                dvr_yearlvls:dvr_yearlvls,
                                dvr_genders:dvr_genders,
                                dvr_minAgeRange:dvr_minAgeRange,
                                dvr_maxAgeRange:dvr_maxAgeRange,
                                ddf_minAgeRange:ddf_minAgeRange,
                                ddf_maxAgeRange:ddf_maxAgeRange,
                                dvr_status:dvr_status,
                                dvr_rangefrom:dvr_rangefrom,
                                dvr_rangeTo:dvr_rangeTo,
                                dvr_orderBy:dvr_orderBy,
                                dselectedOrderByRange:dselectedOrderByRange,
                                dvr_numRows:dvr_numRows,
                                page:page
                                },
                            dataType:'json',
                            success:function(vr_data){
                                $('#delVr_tableTbody').html(vr_data.vr_table);
                                $('#delVr_tablePagination').html(vr_data.vr_table_paginate);
                                $('#delVr_tableTotalData_count').html(vr_data.vr_total_rows);
                                $('#delVr_hiddenTotalData_found').val(vr_data.vr_total_data_found);

                                // for disabling/ enabling generate report button
                                var violationRecs_totalData = document.getElementById("delVr_hiddenTotalData_found").value;
                                if(violationRecs_totalData > 0){
                                    $('#generateDelViolationRecs_btn').prop('disabled', false);
                                }else{
                                    $('#generateDelViolationRecs_btn').prop('disabled', true);
                                }
                            }
                        });

                        // for disabling/ enabling reset filter button
                        if(dvr_schools != 0 || dvr_programs != 0 || dvr_yearlvls != 0 || dvr_genders != 0 || dvr_minAgeRange != ddf_minAgeRange || dvr_maxAgeRange != ddf_maxAgeRange || dvr_status != 0 || dvr_rangefrom != '' || dvr_rangeTo != ''){
                            $('#resetDelViolationRecsFilter_btn').prop('disabled', false);
                        }else{
                            $('#resetDelViolationRecsFilter_btn').prop('disabled', true);
                        }
                    }
                    $(document).ready(function(){
                        setInterval(load_delViolationRec_table,30000);
                    });
                // load_delViolationRec_table() end 

                // function for ajax table pagination
                    $(window).on('hashchange', function() {
                        if (window.location.hash) {
                            var page = window.location.hash.replace('#', '');
                            if (page == Number.NaN || page <= 0) {
                                return false;
                            }else{
                                dvr_getData(page);
                            }
                        }
                    });
                    $('#delVr_tablePagination').on('click', '.pagination a', function(event){
                        event.preventDefault();
                        var page = $(this).attr('href').split('page=')[1];
                        $('#delVr_hidden_page').val(page);

                        load_delViolationRec_table();
                        dvr_getData(page);
                        $('li.page-item').removeClass('active');
                        $(this).parent('li.page-item').addClass('active');
                    });
                    function dvr_getData(page){
                        $.ajax({
                            url: '?page=' + page,
                            type: "get",
                            datatype: "html"
                        }).done(function(data){
                            location.hash = page;
                        })
                        .fail(function(jqXHR, ajaxOptions, thrownError){
                            // alert('No response from server');
                            location.hash = page;
                        });
                    }
                // function for ajax table pagination end

                // daterange picker
                    $('#violationRecFltr_datepickerRange').daterangepicker({
                        timePicker: true,
                        showDropdowns: true,
                        maxDate: new Date(),
                        minYear: 2020,
                        maxYear: parseInt(moment().format('YYYY'),10),
                        drops: 'up',
                        opens: 'right',
                        autoUpdateInput: false,
                        locale: {
                            // format: 'MMMM DD, YYYY - hh:mm A',
                            format: 'MMMM DD, YYYY (ddd - hh:mm A)',
                            cancelLabel: 'Clear'
                            }
                    });
                    $('#violationRecFltr_datepickerRange').on('cancel.daterangepicker', function(ev, picker) {
                        document.getElementById("delViolationRecFltr_hidden_dateRangeFrom").value = '';
                        document.getElementById("delViolationRecFltr_hidden_dateRangeTo").value = '';
                        $(this).val('');
                        $(this).removeClass('cust_input_hasvalue');
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                    $('#violationRecFltr_datepickerRange').on('apply.daterangepicker', function(ev, picker) {
                        // for hidden data range inputs
                        var start_range = picker.startDate.format('YYYY-MM-DD HH:MM:SS');
                        var end_range = picker.endDate.format('YYYY-MM-DD HH:MM:SS');
                        document.getElementById("delViolationRecFltr_hidden_dateRangeFrom").value = start_range;
                        document.getElementById("delViolationRecFltr_hidden_dateRangeTo").value = end_range;
                        // display Date range and add style to $this input 
                        $(this).val(picker.startDate.format('MMMM DD, YYYY') + ' - ' + picker.endDate.format('MMMM DD, YYYY'));
                        $(this).addClass('cust_input_hasvalue');
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                // daterange picker end

                // number of rows
                    $('#delViolationRecsFiltr_numOfRows').on('change paste keyup', function(){
                        var selectedNumRows = $(this).val();
                        if(selectedNumRows != 10){
                            $(this).addClass('cust_input_hasvalue');
                        }else{
                            $(this).removeClass('cust_input_hasvalue');
                        }
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                // number of rows end

                // live search filter
                    $('#delViolationRecsFiltr_liveSearch').on('keyup', function(){
                        // var liveSearchValue = $(this).val();
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                // live search filter end

                // filter schools
                    $('#delViolationRecFltr_schools').on('change paste keyup', function(){
                        var selectedSchool = $(this).val();
                        var toUC_selectedSchool = selectedSchool.toUpperCase();
                        // schools values
                        var all_programs = 'all_programs';
                        var SASE = 'SASE';
                        var SBCS = 'SBCS';
                        var SIHTM = 'SIHTM';
                        var SHSP = 'SHSP';
                        // show/hide options for #delViolationRecFltr_programs based on selected school
                        if(toUC_selectedSchool === SASE){
                            // hide/show programs
                            $('#delViolationRecFltr_programs option[data-programs="' + toUC_selectedSchool + '"]').show();
                            $('#delViolationRecFltr_programs option[data-programs="' + SBCS + '"]').hide();
                            $('#delViolationRecFltr_programs option[data-programs="' + SIHTM + '"]').hide();
                            $('#delViolationRecFltr_programs option[data-programs="' + SHSP + '"]').hide();
                            $('#delViolationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All SASE Programs');
                            document.getElementById("delViolationRecFltr_programs").classList.remove("cust_input_hasvalue");
                            document.getElementById("delViolationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                            $('#delViolationRecFltr_programs').val(0);
                            $('#delViolationRecFltr_yearLvls').val(0);
                            $(this).addClass('cust_input_hasvalue');
                        }else if(toUC_selectedSchool === SBCS){
                            // hide/show programs
                            $('#delViolationRecFltr_programs option[data-programs="' + toUC_selectedSchool + '"]').show();
                            $('#delViolationRecFltr_programs option[data-programs="' + SASE + '"]').hide();
                            $('#delViolationRecFltr_programs option[data-programs="' + SIHTM + '"]').hide();
                            $('#delViolationRecFltr_programs option[data-programs="' + SHSP + '"]').hide();
                            $('#delViolationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All SBCS Programs');
                            document.getElementById("delViolationRecFltr_programs").classList.remove("cust_input_hasvalue");
                            document.getElementById("delViolationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                            $('#delViolationRecFltr_programs').val(0);
                            $('#delViolationRecFltr_yearLvls').val(0);
                            $(this).addClass('cust_input_hasvalue');
                        }else if(toUC_selectedSchool === SIHTM){
                            // hide/show programs
                            $('#delViolationRecFltr_programs option[data-programs="' + toUC_selectedSchool + '"]').show();
                            $('#delViolationRecFltr_programs option[data-programs="' + SASE + '"]').hide();
                            $('#delViolationRecFltr_programs option[data-programs="' + SBCS + '"]').hide();
                            $('#delViolationRecFltr_programs option[data-programs="' + SHSP + '"]').hide();
                            $('#delViolationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All SIHTM Programs');
                            document.getElementById("delViolationRecFltr_programs").classList.remove("cust_input_hasvalue");
                            document.getElementById("delViolationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                            $('#delViolationRecFltr_programs').val(0);
                            $('#delViolationRecFltr_yearLvls').val(0);
                            $(this).addClass('cust_input_hasvalue');
                        }else if(toUC_selectedSchool === SHSP){
                            // hide/show programs
                            $('#delViolationRecFltr_programs option[data-programs="' + toUC_selectedSchool + '"]').show();
                            $('#delViolationRecFltr_programs option[data-programs="' + SASE + '"]').hide();
                            $('#delViolationRecFltr_programs option[data-programs="' + SIHTM + '"]').hide();
                            $('#delViolationRecFltr_programs option[data-programs="' + SBCS + '"]').hide();
                            $('#delViolationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All SHSP Programs');
                            document.getElementById("delViolationRecFltr_programs").classList.remove("cust_input_hasvalue");
                            document.getElementById("delViolationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                            $('#delViolationRecFltr_programs').val(0);
                            $('#delViolationRecFltr_yearLvls').val(0);
                            $(this).addClass('cust_input_hasvalue');
                        }else{
                            // show all programs
                            $('#delViolationRecFltr_programs option[data-programs="' + SASE + '"]').show();
                            $('#delViolationRecFltr_programs option[data-programs="' + SBCS + '"]').show();
                            $('#delViolationRecFltr_programs option[data-programs="' + SIHTM + '"]').show();
                            $('#delViolationRecFltr_programs option[data-programs="' + SHSP + '"]').show();
                            $('#delViolationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All Programs');
                            document.getElementById("delViolationRecFltr_programs").classList.remove("cust_input_hasvalue");
                            document.getElementById("delViolationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                            $('#delViolationRecFltr_programs').val(0);
                            $('#delViolationRecFltr_yearLvls').val(0);
                            $(this).removeClass('cust_input_hasvalue');
                        }
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                // filter schools end 

                // filter programs
                    $('#delViolationRecFltr_programs').on('change paste keyup', function(){
                        var selectedProgram = $(this).val();
                        if(selectedProgram != 0){
                            if(selectedProgram == 'BSA' || selectedProgram == 'BS Physical Therapy'){
                                // show all year levels
                                $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 1 + '"]').show();
                                $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 2 + '"]').show();
                                $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 3 + '"]').show();
                                $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 4 + '"]').show();
                                $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 5 + '"]').show();
                            }else{
                                // show all year levels except 5th year
                                $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 1 + '"]').show();
                                $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 2 + '"]').show();
                                $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 3 + '"]').show();
                                $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 4 + '"]').show();
                                $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 5 + '"]').hide();
                            }
                            document.getElementById("delViolationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                            $('#delViolationRecFltr_yearLvls').val(0);
                            $(this).addClass('cust_input_hasvalue');
                        }else{
                            // show all year levels except 5th year
                            $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 1 + '"]').show();
                            $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 2 + '"]').show();
                            $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 3 + '"]').show();
                            $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 4 + '"]').show();
                            $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 5 + '"]').show();
                            document.getElementById("delViolationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                            $('#delViolationRecFltr_yearLvls').val(0);
                            $(this).removeClass('cust_input_hasvalue');
                        }
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                // filter programs end 

                // filter year levels
                    $('#delViolationRecFltr_yearLvls').on('change paste keyup', function(){
                        var selectedYearLvl = $(this).val();
                        if(selectedYearLvl != 0){
                            $(this).addClass('cust_input_hasvalue');
                        }else{
                            $(this).removeClass('cust_input_hasvalue');
                        }
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                // filter year levels end 

                // filter genders
                    $('#delViolationRecFltr_genders').on('change paste keyup', function(){
                        var selectedGender = $(this).val();
                        if(selectedGender != 0){
                            $(this).addClass('cust_input_hasvalue');
                        }else{
                            $(this).removeClass('cust_input_hasvalue');
                        }
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                // filter genders end

                // filter age range
                    // min age
                    $('#delViolationRecFltr_minAge').on('change paste keyup', function(){
                        // get new min age range
                        var newMinAge = $(this).val();
                        // get default min & max age range
                        var ddf_minAgeRange = document.getElementById('delViolationRecFltr_hidden_minAgeRange').value;
                        var ddf_maxAgeRange = document.getElementById('delViolationRecFltr_hidden_maxAgeRange').value;
                        // add style to #delViolationRecFltr_minAge
                        if(newMinAge != ddf_minAgeRange){
                            document.getElementById("delViolationRecFltr_minAge").classList.add("cust_input_hasvalue");
                        }else{
                            document.getElementById("delViolationRecFltr_minAge").classList.remove("cust_input_hasvalue");
                        }
                        // update #delViolationRecFltr_maxAge input value
                        var sel_maxAgeRange_input = document.getElementById('delViolationRecFltr_maxAge').value;
                        if(sel_maxAgeRange_input < newMinAge){
                            $('#delViolationRecFltr_maxAge').val(ddf_maxAgeRange);
                            document.getElementById("delViolationRecFltr_maxAge").classList.remove("cust_input_hasvalue");
                        }
                        // hide/show options for #delViolationRecFltr_maxAge based on new min age range
                        $('#delViolationRecFltr_maxAge option').filter(function(){
                            return (parseInt(this.value,10) < newMinAge );
                        }).hide();
                        $('#delViolationRecFltr_maxAge option').filter(function(){
                            return (parseInt(this.value,10) >= newMinAge );
                        }).show();
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                    // max age
                    $('#delViolationRecFltr_maxAge').on('change paste keyup', function(){
                        // get new min age range
                        var newMaxAge = $(this).val();
                        // get default max age range
                        var ddf_maxAgeRange = document.getElementById('delViolationRecFltr_hidden_maxAgeRange').value;
                        // add style to #delViolationRecFltr_maxAge
                        if(newMaxAge != ddf_maxAgeRange){
                            document.getElementById("delViolationRecFltr_maxAge").classList.add("cust_input_hasvalue");
                        }else{
                            document.getElementById("delViolationRecFltr_maxAge").classList.remove("cust_input_hasvalue");
                        }
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                // filter age range end 

                // filter violation status
                    $('#delViolationRecFltr_violationStat').on('change paste keyup', function(){
                        var selectedViolatinStat = $(this).val();
                        if(selectedViolatinStat != 0){
                            $(this).addClass('cust_input_hasvalue');
                        }else{
                            $(this).removeClass('cust_input_hasvalue');
                        }
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                // filter violation status end 

                // filter order by
                    $('#delViolationRecFltr_orderBy').on('change paste keyup', function(){
                        var selectedOrderBy = $(this).val();
                        if(selectedOrderBy != 0){
                            $(this).addClass('cust_input_hasvalue');
                        }else{
                            $(this).removeClass('cust_input_hasvalue');
                        }
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                // filter order by end 

                // filter ASC/DESC order
                    $('input[type=radio][name=delViolationRecFltr_orderByRange]').change(function() {
                        // var dselectedOrderByRange = $(this).val();
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                // filter ASC/DESC order end

                // reset filter
                    $('#resetDelViolationRecsFilter_btn').on('click', function(){
                        // disable reset button
                        $(this).prop('disabled', true);
                        // get default values of min & max age range
                        var ddf_minAgeRange = document.getElementById('delViolationRecFltr_hidden_minAgeRange').value;
                        var ddf_maxAgeRange = document.getElementById('delViolationRecFltr_hidden_maxAgeRange').value;
                        // custom values
                        var all_programs = 'all_programs';
                        var SASE = 'SASE';
                        var SBCS = 'SBCS';
                        var SIHTM = 'SIHTM';
                        var SHSP = 'SHSP';
                        var all_year_levels = 'all_year_levels';
                        // schools
                        document.getElementById("delViolationRecFltr_schools").classList.remove("cust_input_hasvalue");
                        $('#delViolationRecFltr_schools').val(0);
                        // programs
                        document.getElementById("delViolationRecFltr_programs").classList.remove("cust_input_hasvalue");
                        $('#delViolationRecFltr_programs option[data-programs="' + SASE + '"]').show();
                        $('#delViolationRecFltr_programs option[data-programs="' + SBCS + '"]').show();
                        $('#delViolationRecFltr_programs option[data-programs="' + SIHTM + '"]').show();
                        $('#delViolationRecFltr_programs option[data-programs="' + SHSP + '"]').show();
                        $('#delViolationRecFltr_programs').val(0);
                        $('#delViolationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All Programs');
                        // year levels
                        document.getElementById("delViolationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                        $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 1 + '"]').show();
                        $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 2 + '"]').show();
                        $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 3 + '"]').show();
                        $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 4 + '"]').show();
                        $('#delViolationRecFltr_yearLvls option[data-yearlvls="' + 5 + '"]').show();
                        $('#delViolationRecFltr_yearLvls').val(0);
                        $('#delViolationRecFltr_yearLvls option[data-default-yearlvl="' + all_year_levels + '"]').html('All Year Levels');
                        // genders
                        document.getElementById("delViolationRecFltr_genders").classList.remove("cust_input_hasvalue");
                        $('#delViolationRecFltr_genders').val(0);
                        // age range
                        document.getElementById("delViolationRecFltr_minAge").classList.remove("cust_input_hasvalue");
                        $('#delViolationRecFltr_minAge').val(ddf_minAgeRange);
                        document.getElementById("delViolationRecFltr_maxAge").classList.remove("cust_input_hasvalue");
                        $('#delViolationRecFltr_maxAge').val(ddf_maxAgeRange);
                        // violation status
                        document.getElementById("delViolationRecFltr_violationStat").classList.remove("cust_input_hasvalue");
                        $('#delViolationRecFltr_violationStat').val(0);
                        // date range
                        document.getElementById("violationRecFltr_datepickerRange").classList.remove("cust_input_hasvalue");
                        document.getElementById("violationRecFltr_datepickerRange").value = '';
                        document.getElementById("delViolationRecFltr_hidden_dateRangeFrom").value = '';
                        document.getElementById("delViolationRecFltr_hidden_dateRangeTo").value = '';
                        // table paginatin set to 1
                        $('#delVr_hidden_page').val(1);
                        load_delViolationRec_table();
                    });
                // reset filter end
            });
        </script>
    {{-- violation records table end --}}

    {{-- delete all recently deleted violations --}}
        {{-- modal confirmation --}}
        <script>
            function deleteAll_DelViolations(){
                var _token = $('input[name="_token"]').val();
                $.ajax({
                        url:"{{ route('violation_records.permanent_delall_recentlydelviolations_confirmation') }}",
                        method:"GET",
                        data:{
                            _token:_token
                            },
                        success: function(data){
                            $('#permanentDeleteAllViolationsRecordsConfirmationHtmlData').html(data); 
                            $('#permanentDeleteAllViolationsRecordsConfirmationModal').modal('show');
                        }
                    });
            }
        </script>
        {{-- form --}}
        {{-- submit function --}}
        <script>
            $('#permanentDeleteAllViolationsRecordsConfirmationModal').on('show.bs.modal', function () {
                var form_confirmPermDeleteAllRecViolations  = document.querySelector("#form_confirmPermDeleteAllRecViolations");
                var process_permDeleteAllRecViolations_btn = document.querySelector("#process_permDeleteAllRecViolations_btn");
                var cancel_permDeleteAllRecViolations_btn = document.querySelector("#cancel_permDeleteAllRecViolations_btn");
                var reason_permDeleteAllViolations = document.querySelector("#reason_permDeleteAllViolations");
                // if reason has value
                $(reason_permDeleteAllViolations).keyup(function(){
                    if(reason_permDeleteAllViolations.value !== ""){
                        process_permDeleteAllRecViolations_btn.disabled = false;
                    }else{
                        process_permDeleteAllRecViolations_btn.disabled = true;
                    }
                });
                // on submit
                $(form_confirmPermDeleteAllRecViolations).submit(function(){
                    cancel_permDeleteAllRecViolations_btn.disabled = true;
                    process_permDeleteAllRecViolations_btn.disabled = true;
                    return true;
                });
            });
        </script>
    {{-- delete all recently deleted violations end --}}
    
@endpush