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
                                                <option value="BS Psychology" data-sase-programs="sase_programs">BS Psychology</option>
                                                <option value="BS Education" data-sase-programs="sase_programs">BS Education</option>
                                                <option value="BA Communication" data-sase-programs="sase_programs">BA Communication</option>

                                                <option value="BSBA" data-sbcs-programs="sbcs_programs">BSBA</option>
                                                <option value="BSA" data-sbcs-programs="sbcs_programs">BSA</option>
                                                <option value="BSIT" data-sbcs-programs="sbcs_programs">BSIT</option>
                                                <option value="BMA" data-sbcs-programs="sbcs_programs">BMA</option>

                                                <option value="BSHM" data-sihtm-programs="sihtm_programs">BSHM</option>
                                                <option value="BSTM" data-sihtm-programs="sihtm_programs">BSTM</option>

                                                <option value="BS Biology" data-shsp-programs="shsp_programs">BS Biology</option>
                                                <option value="BS Pharmacy" data-shsp-programs="shsp_programs">BS Pharmacy</option>
                                                <option value="BS Radiologic Technology" data-shsp-programs="shsp_programs">BS Radiologic Technology</option>
                                                <option value="BS Physical Therapy" data-shsp-programs="shsp_programs">BS Physical Therapy</option>
                                                <option value="BS Medical Technology" data-shsp-programs="shsp_programs">BS Medical Technology</option>
                                                <option value="BS Nursing" data-shsp-programs="shsp_programs">BS Nursing</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="violationRecFltr_yearLvls" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                <option value="0" data-default-yearlvl="all_year_levels" selected>All Year Levels</option>
                                                <option value="1" data-yearlvls="first_yearlvls">FIRST YEAR</option>
                                                <option value="2" data-yearlvls="second_yearlvls">SECOND YEARS</option>
                                                <option value="3" data-yearlvls="third_yearlvls">THIRD YEARS</option>
                                                <option value="4" data-yearlvls="fourth_yearlvls">FOURTH YEARS</option>
                                                <option value="5" data-yearlvls="fifth_yearlvls">FIFTH YEARS</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="violationRecFltr_genders" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                <option value="0" data-default-gender="all_genders" selected>All Genders</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <label class="custom_label" for="create_stud_email">Age Range: </label> <span> 19 to 28 Year Olds</span>
                                        <div class="form-group">
                                            @php
                                                $max_age = App\Models\Students::select('Age')->max('Age');
                                                $min_age = App\Models\Students::select('Age')->min('Age');
                                            @endphp
                                            <div class="slidecontainer">
                                                <input type="range" min="{{$min_age}}" max="{{$max_age}}" value="{{$max_age}}" class="slider" id="violationRecFltr_agesRange">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="cust_status_title mt-2 mb-2">Violations Filter Options <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Filter options for specific students."></i></span>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select id="violationRecFltr_violationStat" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                <option value="0" data-default-year-level="all_violation_status" selected>All Violation Status</option>
                                                <option value="not cleared">Not Cleared</option>
                                                <option value="cleared">Cleared</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <input id="violationRecFltr_datepickerRange" name="violationRecFltr_datepickerRange" type="text" class="form-control cust_input" placeholder="Select Date Range" />
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
                            <span id="filter_schools_txt" class="cust_table_filters_texts"> All Schools </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_programs_txt" class="cust_table_filters_texts"> All Programs </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_yearLvls_txt" class="cust_table_filters_texts"> All Year Levels </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_genders_txt" class="cust_table_filters_texts"> All Genders </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_agesRange_txt" class="cust_table_filters_texts"> Ages 19 to 20 </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_violationStat_txt" class="cust_table_filters_texts"> All Violation Status </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_datepickerRange_txt" class="cust_table_filters_texts"> From Previous Days up to this day </span> <span class="cust_table_filters_texts_divider"> / </span>
                            <span id="filter_liveSearch_txt" class="cust_table_filters_texts"> ...</span>
                        </div>
                    </div>
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
                                <tbody class="tbody_svms_white" id="usersActLogs_tbody">
                                    {{-- ajax data table --}}
                                    <tr>
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
                                    </tr>
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

@endsection

@push('scripts')

@endpush