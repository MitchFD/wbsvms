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
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/deleted_violation_records_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- filter options --}}
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
                            <form id="form_filterViolationRecTable" class="form" method="POST" action="#" enctype="multipart/form-data">
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
                                <span class="cust_status_title mt-2 mb-2">Violations Filter Options <i class="fa fa-info-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Filter options for specific Violations."></i></span>
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