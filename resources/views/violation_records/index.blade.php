@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'violation_records'
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
                <a href="{{ route('violation_records.index', 'violation_records') }}" class="directory_active_link">Violation Records</a>
            </div>
        </div>

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">Violation Records</span>
                            <span class="page_intro_subtitle">This page shows you the list of all recorded violations committed by the college students of St. Dominic College of Asia. You can filter the table below to view desired outputs and generate report for print or digital copy purposes.</span>
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/violation_records_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- violators counts per school - dashboard --}}
        {{-- <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="accordion" id="violatorsCountDashboardCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="violatorsCountDashboardCollapseHeading">
                            <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#violatorsCountDashboardCollapseDiv" aria-expanded="true" aria-controls="violatorsCountDashboardCollapseDiv">
                                <div>
                                    <span class="card_body_title">Violators count per School</span>
                                    <span class="card_body_subtitle">View statistical graph of violators per schools.</span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="violatorsCountDashboardCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="violatorsCountDashboardCollapseHeading" data-parent="#violatorsCountDashboardCollapseParent">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="card card_gbr card_ofh shadow">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <img class="dash_cards_img" src="{{asset('storage/svms/sdca_images/schools_logos/sbcs.jpg')}}" alt="SBCS Logo">
                                            <div class="dash_cards_text_div">
                                                <span class="dash_card_title">SBCS</span>
                                                <span class="dash_card_count">20</span>
                                            </div>
                                        </div>
                                        <div class="card-footer dash_card_footer align-items-center">
                                            <i class="fa fa-user mr-1"></i> 20 violators found
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="card card_gbr card_ofh shadow">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <img class="dash_cards_img" src="{{asset('storage/svms/sdca_images/schools_logos/shsp.jpg')}}" alt="SHSP Logo">
                                            <div class="dash_cards_text_div">
                                                <span class="dash_card_title">SHSP</span>
                                                <span class="dash_card_count">32</span>
                                            </div>
                                        </div>
                                        <div class="card-footer dash_card_footer align-items-center">
                                            <i class="fa fa-user mr-1"></i> 31 violators found
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="card card_gbr card_ofh shadow">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <img class="dash_cards_img" src="{{asset('storage/svms/sdca_images/schools_logos/sihtm.jpg')}}" alt="SIHTM Logo">
                                            <div class="dash_cards_text_div">
                                                <span class="dash_card_title">SIHTM</span>
                                                <span class="dash_card_count">15</span>
                                            </div>
                                        </div>
                                        <div class="card-footer dash_card_footer align-items-center">
                                            <i class="fa fa-user mr-1"></i> 15 violators found
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="card card_gbr card_ofh shadow">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <img class="dash_cards_img" src="{{asset('storage/svms/sdca_images/schools_logos/sase.jpg')}}" alt="SASE Logo">
                                            <div class="dash_cards_text_div">
                                                <span class="dash_card_title">SASE</span>
                                                <span class="dash_card_count">8</span>
                                            </div>
                                        </div>
                                        <div class="card-footer dash_card_footer align-items-center">
                                            <i class="fa fa-user mr-1"></i> 8 violators found
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- table data --}}
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="accordion" id="ViolatinRecFiltrOptionsCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="ViolatinRecFiltrOptionsCollapseHeading">
                            <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#ViolatinRecFiltrOptionsCollapseDiv" aria-expanded="true" aria-controls="ViolatinRecFiltrOptionsCollapseDiv">
                                <div>
                                    <span class="card_body_title">Filter Options</span>
                                    {{-- <span class="card_body_subtitle">Select available options below to filter desired results.</span> --}}
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="ViolatinRecFiltrOptionsCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="ViolatinRecFiltrOptionsCollapseHeading" data-parent="#ViolatinRecFiltrOptionsCollapseParent">
                            <form id="form_filterUserLogsTable" class="form" method="POST" action="#" enctype="multipart/form-data">
                                @csrf
                                <span class="cust_status_title mb-2">Students Filter Options <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Filter options for specific students."></i></span>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="violationRecFltr_schools" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
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
                                            <select id="violationRecFltr_programs" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
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
                                            <select id="violationRecFltr_yearLvls" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
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
                                            <select id="violationRecFltr_genders" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
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
                                <input type="hidden" name="violationRecFltr_hidden_maxAgeRange" id="violationRecFltr_hidden_maxAgeRange" value="{{$max_age}}">
                                <input type="hidden" name="violationRecFltr_hidden_minAgeRange" id="violationRecFltr_hidden_minAgeRange" value="{{$min_age}}">
                                <span class="cust_status_title mt-2 mb-2">Age Range: </span> <span class="custom_label_sub" id="filter_ageRange_label"> {{$min_age}} to {{$max_age}} Year Olds</span></label>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 pr-1">
                                                <div class="form-group">
                                                    <select id="violationRecFltr_minAge" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
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
                                                    <select id="violationRecFltr_maxAge" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
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
                                <span class="cust_status_title mt-2 mb-2">Violations Filter Options <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Filter options for specific students."></i></span>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="violationRecFltr_violationStat" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                <option value="0" selected>All Violation Status</option>
                                                <option value="not cleared">Not Cleared</option>
                                                <option value="cleared">Cleared</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <input id="violationRecFltr_datepickerRange" name="violationRecFltr_datepickerRange" type="text" class="form-control cust_input" placeholder="Select Date Range" />
                                        <input type="hidden" name="violationRecFltr_hidden_dateRangeFrom" id="violationRecFltr_hidden_dateRangeFrom">
                                        <input type="hidden" name="violationRecFltr_hidden_dateRangeTo" id="violationRecFltr_hidden_dateRangeTo">
                                        {{-- @php
                                            $count_actLogs = App\Models\Useractivites::all()->count();
                                        @endphp --}}
                                        <input type="hidden" name="vr_hiddenTotalData_found" id="vr_hiddenTotalData_found">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12 col-md-12 col-sm-12 text-right">
                                        <button type="button" id="generateViolationRecs_btn" class="btn btn-success cust_bt_links shadow"><i class="nc-icon nc-single-copy-04 mr-1" aria-hidden="true"></i> Generate Report</button>
                                        <button type="button" id="resetViolationRecsFilter_btn" class="btn btn_svms_blue cust_bt_links shadow" disabled><i class="fa fa-refresh mr-1" aria-hidden="true"></i> Reset</button>
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
                                <input id="violationRecsFiltr_liveSearch" name="violationRecsFiltr_liveSearch" type="text" class="form-control cust_srchUsersInpt_box" placeholder="Search Something..." />
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
                    {{-- <div class="row mt-3">
                        <div class="col-lg-12 col-sm-12">
                            <span class="cust_table_filters_title"> Filters: </span>
                            <span id="filter_schools_txt" class="cust_table_filters_texts"> All Schools </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_programs_txt" class="cust_table_filters_texts"> All Programs </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_yearLvls_txt" class="cust_table_filters_texts"> All Year Levels </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_genders_txt" class="cust_table_filters_texts"> All Genders </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_agesRange_txt" class="cust_table_filters_texts"> {{$min_age}} to {{$max_age}} Year Olds </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_violationStat_txt" class="cust_table_filters_texts"> All Violation Status </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_datepickerRange_txt" class="cust_table_filters_texts"> From Previous Days up to this day </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_liveSearch_txt" class="cust_table_filters_texts"> ...</span>
                        </div>
                    </div> --}}
                    <div class="row mt-3">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <table class="table table-hover cust_table shadow">
                                <thead class="thead_svms_blue">
                                    <tr>
                                        <th class="pl12">~ Students</th>
                                        <th>Date</th>
                                        <th>Offenses</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody_svms_white" id="vr_tableTbody">
                                    {{-- ajax data table --}}
                                    {{-- <tr>
                                        <td class="pl12 d-flex justify-content-start align-items-center">
                                            <img class="display_violator_image2 shadow-sm" src="{{asset('storage/svms/sdca_images/registered_students_imgs/default_student_img.jpg')}}" alt="student's image">
                                            <div class="cust_td_info">
                                                <span class="actLogs_tdTitle font-weight-bold">Mitch Frankein Ovalo Desierto</span>
                                                <span class="actLogs_tdSubTitle"><span class="sub1">20150348 <span class="subDiv"> | </span> <span class="sub1"> SBCS - BSIT - 4th Year </span> <span class="subDiv"> | </span> <span class="sub1"> Male </span> </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-inline">
                                                <span class="actLogs_content">April 1, 2021</span>
                                                <span class="actLogs_tdSubTitle sub2">Fri - 12:00 PM</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-inline">
                                                <span class="actLogs_content">4 Offenses</span>
                                                <span class="actLogs_tdSubTitle sub2">Not Wearing ID, Cheating during Exam, Not Wearing Prescribed Uniform ... </span>
                                            </div>
                                        </td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-lg-4 col-md-4 col-sm-12 text-left">
                            <span>Total Data: <span class="font-weight-bold" id="vr_tableTotalData_count"> </span> </span>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 d-flex justify-content-end">
                            @csrf
                            <input type="hidden" name="vr_hidden_page" id="vr_hidden_page" value="1" />
                            <div id="vr_tablePagination">

                            </div>
                            {{-- <a href="#" class="btn btn-success cust_bt_links shadow" role="button"><i class="fa fa-print mr-1" aria-hidden="true"></i> Generate Report</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    {{-- modals --}}

@endsection

@push('scripts')
    <script>
        function viewStudentOffenses(violator_id){
            var sel_violator_id = violator_id;
            console.log(sel_violator_id);
        }
    </script>
    <script>
        $(document).ready(function(){
            loadViolationRecTable();

            // funciton for loading vilation records table
            function loadViolationRecTable(){
                // get all filtered values
                // live search
                var vr_search = document.getElementById('violationRecsFiltr_liveSearch').value;
                // schools
                var vr_schools = document.getElementById('violationRecFltr_schools').value;
                // programs
                var vr_programs = document.getElementById('violationRecFltr_programs').value;
                // year levels
                var vr_yearlvls = document.getElementById('violationRecFltr_yearLvls').value;
                // genders
                var vr_genders = document.getElementById('violationRecFltr_genders').value;
                // age range
                var vr_minAgeRange = document.getElementById('violationRecFltr_minAge').value;
                var vr_maxAgeRange = document.getElementById('violationRecFltr_maxAge').value;
                var df_minAgeRange = document.getElementById('violationRecFltr_hidden_minAgeRange').value;
                var df_maxAgeRange = document.getElementById('violationRecFltr_hidden_maxAgeRange').value;
                // violation status
                var vr_status = document.getElementById('violationRecFltr_violationStat').value;
                // date range
                var vr_rangefrom = document.getElementById("violationRecFltr_hidden_dateRangeFrom").value;
                var vr_rangeTo = document.getElementById("violationRecFltr_hidden_dateRangeTo").value;
                // page
                var page = document.getElementById("vr_hidden_page").value;
                
                // update age range label
                if(vr_minAgeRange == vr_maxAgeRange){
                    $('#filter_ageRange_label').html('All ' + vr_minAgeRange + ' Year Olds');
                }else{
                    $('#filter_ageRange_label').html(vr_minAgeRange + ' to ' + vr_maxAgeRange + ' Year Olds');
                }

                console.log(vr_search);
                console.log(vr_schools);
                console.log(vr_programs);
                console.log(vr_yearlvls);
                console.log(vr_genders);
                console.log(vr_minAgeRange);
                console.log(vr_maxAgeRange);
                console.log(vr_status);
                console.log(vr_rangefrom);
                console.log(vr_rangeTo);
                console.log(page);

                $.ajax({
                    url:"{{ route('violation_records.vr_table_filter') }}",
                    method:"GET",
                    data:{
                        vr_search:vr_search, 
                        vr_schools:vr_schools, 
                        vr_programs:vr_programs, 
                        vr_yearlvls:vr_yearlvls,
                        vr_genders:vr_genders,
                        vr_minAgeRange:vr_minAgeRange,
                        vr_maxAgeRange:vr_maxAgeRange,
                        df_minAgeRange:df_minAgeRange,
                        df_maxAgeRange:df_maxAgeRange,
                        vr_status:vr_status,
                        vr_rangefrom:vr_rangefrom,
                        vr_rangeTo:vr_rangeTo
                        },
                    dataType:'json',
                    success:function(vr_data){
                        $('#vr_tableTbody').html(vr_data.vr_table);
                        $('#vr_tablePagination').html(vr_data.vr_table_paginate);
                        $('#vr_tableTotalData_count').html(vr_data.vr_total_rows);
                        $('#vr_hiddenTotalData_found').val(vr_data.vr_total_data_found);

                        // for disabling/ enabling generate report button
                        var violationRecs_totalData = document.getElementById("vr_hiddenTotalData_found").value;
                        // console.log(violationRecs_totalData);
                        if(violationRecs_totalData > 0){
                            $('#generateViolationRecs_btn').prop('disabled', false);
                        }else{
                            $('#generateViolationRecs_btn').prop('disabled', true);
                        }
                    }
                });

                // for disabling/ enabling reset filter button
                if(vr_schools != 0 || vr_programs != 0 || vr_yearlvls != 0 || vr_genders != 0 || vr_minAgeRange != df_minAgeRange || vr_maxAgeRange != df_maxAgeRange || vr_status != 0 || vr_rangefrom != '' || vr_rangeTo != ''){
                    $('#resetViolationRecsFilter_btn').prop('disabled', false);
                }else{
                    $('#resetViolationRecsFilter_btn').prop('disabled', true);
                }

            }

            // function for capitalizing first letter of a word
            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }

            // daterange picker
            $('#violationRecFltr_datepickerRange').daterangepicker({
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
            $('#violationRecFltr_datepickerRange').on('cancel.daterangepicker', function(ev, picker) {
                document.getElementById("violationRecFltr_hidden_dateRangeFrom").value = '';
                document.getElementById("violationRecFltr_hidden_dateRangeTo").value = '';
                $(this).val('');
                $(this).removeClass('cust_input_hasvalue');
                // table paginatin set to 1
                $('#vr_hidden_page').val(1);
                loadViolationRecTable();
            });
            $('#violationRecFltr_datepickerRange').on('apply.daterangepicker', function(ev, picker) {
                // for hidden data range inputs
                var start_range = picker.startDate.format('YYYY-MM-DD HH:MM:SS');
                var end_range = picker.endDate.format('YYYY-MM-DD HH:MM:SS');
                document.getElementById("violationRecFltr_hidden_dateRangeFrom").value = start_range;
                document.getElementById("violationRecFltr_hidden_dateRangeTo").value = end_range;
                // display Date range and add style to $this input 
                $(this).val(picker.startDate.format('MMMM DD, YYYY') + ' - ' + picker.endDate.format('MMMM DD, YYYY'));
                $(this).addClass('cust_input_hasvalue');
                // table paginatin set to 1
                $('#vr_hidden_page').val(1);
                loadViolationRecTable();
            });

            // live search filter
            $('#violationRecsFiltr_liveSearch').on('keyup', function(){
                // var liveSearchValue = $(this).val();
                // table paginatin set to 1
                $('#vr_hidden_page').val(1);
                loadViolationRecTable();
            });

            // filter schools
            $('#violationRecFltr_schools').on('change paste keyup', function(){
                var selectedSchool = $(this).val();
                var toUC_selectedSchool = selectedSchool.toUpperCase();
                // schools values
                var all_programs = 'all_programs';
                var SASE = 'SASE';
                var SBCS = 'SBCS';
                var SIHTM = 'SIHTM';
                var SHSP = 'SHSP';
                // show/hide options for #violationRecFltr_programs based on selected school
                if(toUC_selectedSchool === SASE){
                    // hide/show programs
                    $('#violationRecFltr_programs option[data-programs="' + toUC_selectedSchool + '"]').show();
                    $('#violationRecFltr_programs option[data-programs="' + SBCS + '"]').hide();
                    $('#violationRecFltr_programs option[data-programs="' + SIHTM + '"]').hide();
                    $('#violationRecFltr_programs option[data-programs="' + SHSP + '"]').hide();
                    $('#violationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All SASE Programs');
                    document.getElementById("violationRecFltr_programs").classList.remove("cust_input_hasvalue");
                    document.getElementById("violationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                    $('#violationRecFltr_programs').val(0);
                    $('#violationRecFltr_yearLvls').val(0);
                    $(this).addClass('cust_input_hasvalue');
                }else if(toUC_selectedSchool === SBCS){
                    // hide/show programs
                    $('#violationRecFltr_programs option[data-programs="' + toUC_selectedSchool + '"]').show();
                    $('#violationRecFltr_programs option[data-programs="' + SASE + '"]').hide();
                    $('#violationRecFltr_programs option[data-programs="' + SIHTM + '"]').hide();
                    $('#violationRecFltr_programs option[data-programs="' + SHSP + '"]').hide();
                    $('#violationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All SBCS Programs');
                    document.getElementById("violationRecFltr_programs").classList.remove("cust_input_hasvalue");
                    document.getElementById("violationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                    $('#violationRecFltr_programs').val(0);
                    $('#violationRecFltr_yearLvls').val(0);
                    $(this).addClass('cust_input_hasvalue');
                }else if(toUC_selectedSchool === SIHTM){
                    // hide/show programs
                    $('#violationRecFltr_programs option[data-programs="' + toUC_selectedSchool + '"]').show();
                    $('#violationRecFltr_programs option[data-programs="' + SASE + '"]').hide();
                    $('#violationRecFltr_programs option[data-programs="' + SBCS + '"]').hide();
                    $('#violationRecFltr_programs option[data-programs="' + SHSP + '"]').hide();
                    $('#violationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All SIHTM Programs');
                    document.getElementById("violationRecFltr_programs").classList.remove("cust_input_hasvalue");
                    document.getElementById("violationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                    $('#violationRecFltr_programs').val(0);
                    $('#violationRecFltr_yearLvls').val(0);
                    $(this).addClass('cust_input_hasvalue');
                }else if(toUC_selectedSchool === SHSP){
                    // hide/show programs
                    $('#violationRecFltr_programs option[data-programs="' + toUC_selectedSchool + '"]').show();
                    $('#violationRecFltr_programs option[data-programs="' + SASE + '"]').hide();
                    $('#violationRecFltr_programs option[data-programs="' + SIHTM + '"]').hide();
                    $('#violationRecFltr_programs option[data-programs="' + SBCS + '"]').hide();
                    $('#violationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All SHSP Programs');
                    document.getElementById("violationRecFltr_programs").classList.remove("cust_input_hasvalue");
                    document.getElementById("violationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                    $('#violationRecFltr_programs').val(0);
                    $('#violationRecFltr_yearLvls').val(0);
                    $(this).addClass('cust_input_hasvalue');
                }else{
                    // show all programs
                    $('#violationRecFltr_programs option[data-programs="' + SASE + '"]').show();
                    $('#violationRecFltr_programs option[data-programs="' + SBCS + '"]').show();
                    $('#violationRecFltr_programs option[data-programs="' + SIHTM + '"]').show();
                    $('#violationRecFltr_programs option[data-programs="' + SBCS + '"]').show();
                    $('#violationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All Programs');
                    document.getElementById("violationRecFltr_programs").classList.remove("cust_input_hasvalue");
                    document.getElementById("violationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                    $('#violationRecFltr_programs').val(0);
                    $('#violationRecFltr_yearLvls').val(0);
                    $(this).removeClass('cust_input_hasvalue');
                }
                // table paginatin set to 1
                $('#vr_hidden_page').val(1);
                loadViolationRecTable();
            });

            // filter programs
            $('#violationRecFltr_programs').on('change paste keyup', function(){
                var selectedProgram = $(this).val();
                if(selectedProgram != 0){
                    if(selectedProgram == 'BSA' || selectedProgram == 'BS Physical Therapy'){
                        // show all year levels
                        $('#violationRecFltr_yearLvls option[data-yearlvls="' + 1 + '"]').show();
                        $('#violationRecFltr_yearLvls option[data-yearlvls="' + 2 + '"]').show();
                        $('#violationRecFltr_yearLvls option[data-yearlvls="' + 3 + '"]').show();
                        $('#violationRecFltr_yearLvls option[data-yearlvls="' + 4 + '"]').show();
                        $('#violationRecFltr_yearLvls option[data-yearlvls="' + 5 + '"]').show();
                    }else{
                        // show all year levels except 5th year
                        $('#violationRecFltr_yearLvls option[data-yearlvls="' + 1 + '"]').show();
                        $('#violationRecFltr_yearLvls option[data-yearlvls="' + 2 + '"]').show();
                        $('#violationRecFltr_yearLvls option[data-yearlvls="' + 3 + '"]').show();
                        $('#violationRecFltr_yearLvls option[data-yearlvls="' + 4 + '"]').show();
                        $('#violationRecFltr_yearLvls option[data-yearlvls="' + 5 + '"]').hide();
                    }
                    document.getElementById("violationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                    $('#violationRecFltr_yearLvls').val(0);
                    $(this).addClass('cust_input_hasvalue');
                }else{
                    document.getElementById("violationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                    $('#violationRecFltr_yearLvls').val(0);
                    $(this).removeClass('cust_input_hasvalue');
                }
                // table paginatin set to 1
                $('#vr_hidden_page').val(1);
                loadViolationRecTable();
            });

            // filter year levels
            $('#violationRecFltr_yearLvls').on('change paste keyup', function(){
                var selectedYearLvl = $(this).val();
                if(selectedYearLvl != 0){
                    $(this).addClass('cust_input_hasvalue');
                }else{
                    $(this).removeClass('cust_input_hasvalue');
                }
                // table paginatin set to 1
                $('#vr_hidden_page').val(1);
                loadViolationRecTable();
            });

            // filter genders
            $('#violationRecFltr_genders').on('change paste keyup', function(){
                var selectedGender = $(this).val();
                if(selectedGender != 0){
                    $(this).addClass('cust_input_hasvalue');
                }else{
                    $(this).removeClass('cust_input_hasvalue');
                }
                // table paginatin set to 1
                $('#vr_hidden_page').val(1);
                loadViolationRecTable();
            });

            // filter age range
            // min age
            $('#violationRecFltr_minAge').on('change paste keyup', function(){
                // get new min age range
                var newMinAge = $(this).val();
                // get default min & max age range
                var df_minAgeRange = document.getElementById('violationRecFltr_hidden_minAgeRange').value;
                var df_maxAgeRange = document.getElementById('violationRecFltr_hidden_maxAgeRange').value;
                // add style to #violationRecFltr_minAge
                if(newMinAge != df_minAgeRange){
                    document.getElementById("violationRecFltr_minAge").classList.add("cust_input_hasvalue");
                }else{
                    document.getElementById("violationRecFltr_minAge").classList.remove("cust_input_hasvalue");
                }
                // update #violationRecFltr_maxAge input value
                var sel_maxAgeRange_input = document.getElementById('violationRecFltr_maxAge').value;
                if(sel_maxAgeRange_input < newMinAge){
                    $('#violationRecFltr_maxAge').val(df_maxAgeRange);
                    document.getElementById("violationRecFltr_maxAge").classList.remove("cust_input_hasvalue");
                }
                // hide/show options for #violationRecFltr_maxAge based on new min age range
                $('#violationRecFltr_maxAge option').filter(function(){
                    return (parseInt(this.value,10) < newMinAge );
                }).hide();
                $('#violationRecFltr_maxAge option').filter(function(){
                    return (parseInt(this.value,10) >= newMinAge );
                }).show();
                loadViolationRecTable();
            });
            // max age
            $('#violationRecFltr_maxAge').on('change paste keyup', function(){
                // get new min age range
                var newMaxAge = $(this).val();
                // get default max age range
                var df_maxAgeRange = document.getElementById('violationRecFltr_hidden_maxAgeRange').value;
                // add style to #violationRecFltr_maxAge
                if(newMaxAge != df_maxAgeRange){
                    document.getElementById("violationRecFltr_maxAge").classList.add("cust_input_hasvalue");
                }else{
                    document.getElementById("violationRecFltr_maxAge").classList.remove("cust_input_hasvalue");
                }
                loadViolationRecTable();
            });

            // filter violation status
            $('#violationRecFltr_violationStat').on('change paste keyup', function(){
                var selectedViolatinStat = $(this).val();
                if(selectedViolatinStat != 0){
                    $(this).addClass('cust_input_hasvalue');
                }else{
                    $(this).removeClass('cust_input_hasvalue');
                }
                // table paginatin set to 1
                $('#vr_hidden_page').val(1);
                loadViolationRecTable();
            });

            // reset filter
            $('#resetViolationRecsFilter_btn').on('click', function(){
            // get default values of min & max age range
            var df_minAgeRange = document.getElementById('violationRecFltr_hidden_minAgeRange').value;
            var df_maxAgeRange = document.getElementById('violationRecFltr_hidden_maxAgeRange').value;
            // custom values
            var all_programs = 'all_programs';
            var SASE = 'SASE';
            var SBCS = 'SBCS';
            var SIHTM = 'SIHTM';
            var SHSP = 'SHSP';
            var all_year_levels = 'all_year_levels';
            // schools
            document.getElementById("violationRecFltr_schools").classList.remove("cust_input_hasvalue");
            $('#violationRecFltr_schools').val(0);
            // programs
            document.getElementById("violationRecFltr_programs").classList.remove("cust_input_hasvalue");
            $('#violationRecFltr_programs').val(0);
            $('#violationRecFltr_programs option[data-programs="' + SASE + '"]').show();
            $('#violationRecFltr_programs option[data-programs="' + SBCS + '"]').show();
            $('#violationRecFltr_programs option[data-programs="' + SIHTM + '"]').show();
            $('#violationRecFltr_programs option[data-programs="' + SBCS + '"]').show();
            $('#violationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All Programs');
            // year levels
            document.getElementById("violationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
            $('#violationRecFltr_yearLvls').val(0);
            $('#violationRecFltr_yearLvls option[data-yearlvls="' + 1 + '"]').show();
            $('#violationRecFltr_yearLvls option[data-yearlvls="' + 2 + '"]').show();
            $('#violationRecFltr_yearLvls option[data-yearlvls="' + 3 + '"]').show();
            $('#violationRecFltr_yearLvls option[data-yearlvls="' + 4 + '"]').show();
            $('#violationRecFltr_yearLvls option[data-yearlvls="' + 5 + '"]').show();
            $('#violationRecFltr_yearLvls option[data-default-yearlvl="' + all_year_levels + '"]').html('All Year Levels');
            // genders
            document.getElementById("violationRecFltr_genders").classList.remove("cust_input_hasvalue");
            $('#violationRecFltr_genders').val(0);
            // age range
            document.getElementById("violationRecFltr_minAge").classList.remove("cust_input_hasvalue");
            $('#violationRecFltr_minAge').val(df_minAgeRange);
            document.getElementById("violationRecFltr_maxAge").classList.remove("cust_input_hasvalue");
            $('#violationRecFltr_maxAge').val(df_maxAgeRange);
            // violation status
            document.getElementById("violationRecFltr_violationStat").classList.remove("cust_input_hasvalue");
            $('#violationRecFltr_violationStat').val(0);
            // date range
            document.getElementById("violationRecFltr_datepickerRange").classList.remove("cust_input_hasvalue");
            document.getElementById("violationRecFltr_datepickerRange").value = '';
            document.getElementById("violationRecFltr_hidden_dateRangeFrom").value = '';
            document.getElementById("violationRecFltr_hidden_dateRangeTo").value = '';
            // table paginatin set to 1
            $('#vr_hidden_page').val(1);
            loadViolationRecTable();
            });

            // function for ajax table pagination
            $(window).on('hashchange', function() {
                if (window.location.hash) {
                    var page = window.location.hash.replace('#', '');
                    if (page == Number.NaN || page <= 0) {
                        return false;
                    }else{
                        vr_getData(page);
                    }
                }
            });
            $(document).ready(function(){
                $('#vr_tablePagination').on('click', '.pagination a', function(event){
                    event.preventDefault();

                    $('li.page-item').removeClass('active');
                    $(this).parent('li.page-item').addClass('active');
                    
                    var myurl = $(this).attr('href');
                    var page = $(this).attr('href').split('page=')[1];

                    $('#vr_hidden_page').val(page);
                    console.log($(this).val());

                    loadViolationRecTable();
                    vr_getData(page);
                    
                });
            });
            function vr_getData(page){
                $.ajax(
                {
                    url: '?page=' + page,
                    type: "get",
                    datatype: "html"
                }).done(function(data){
                    // $("#vr_tableTbody").empty().html(data.html);
                    location.hash = page;
                }).fail(function(jqXHR, ajaxOptions, thrownError){
                    alert('No response from server');
                });
            }
            // function for ajax table pagination end
        });
    </script>
@endpush