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
    {{-- view student's offenses --}}
    <script>
        function viewStudentOffenses(violator_id){
            var sel_violator_id = violator_id;
            console.log(sel_violator_id);
        }
    </script>
    {{-- view student's offenses end --}}
    <script>
        $(document).ready(function(){
            load_violationRec_table();

            // funciton for loading vilation records table
            function load_violationRec_table(){
                // get all filtered values
                var vr_search = document.getElementById('violationRecsFiltr_liveSearch').value;
                var vr_schools = document.getElementById('violationRecFltr_schools').value;
                var vr_programs = document.getElementById('violationRecFltr_programs').value;
                var vr_yearlvls = document.getElementById('violationRecFltr_yearLvls').value;
                var vr_genders = document.getElementById('violationRecFltr_genders').value;
                var vr_minAgeRange = document.getElementById('violationRecFltr_minAge').value;
                var vr_maxAgeRange = document.getElementById('violationRecFltr_maxAge').value;
                var df_minAgeRange = document.getElementById('violationRecFltr_hidden_minAgeRange').value;
                var df_maxAgeRange = document.getElementById('violationRecFltr_hidden_maxAgeRange').value;
                var vr_status = document.getElementById('violationRecFltr_violationStat').value;
                var vr_rangefrom = document.getElementById("violationRecFltr_hidden_dateRangeFrom").value;
                var vr_rangeTo = document.getElementById("violationRecFltr_hidden_dateRangeTo").value;
                var page = document.getElementById("vr_hidden_page").value;
                
                // update age range label
                if(vr_minAgeRange == vr_maxAgeRange){
                    $('#filter_ageRange_label').html('All ' + vr_minAgeRange + ' Year Olds');
                }else{
                    $('#filter_ageRange_label').html(vr_minAgeRange + ' to ' + vr_maxAgeRange + ' Year Olds');
                }

                console.log('');
                console.log('search_filter: ' + vr_search);
                console.log('school_filter: ' + vr_schools);
                console.log('course_filter: ' + vr_programs);
                console.log('year_level_filter: ' + vr_yearlvls);
                console.log('gender_filter: ' + vr_genders);
                console.log('min_Age_filter: ' + vr_minAgeRange);
                console.log('max_Age_filter: ' + vr_maxAgeRange);
                console.log('violation_status_filter: ' + vr_status);
                console.log('date_from_filter: ' + vr_rangefrom);
                console.log('date_filter: ' + vr_rangeTo);
                console.log('current_page: ' + page);
                console.log('');

                $.ajax({
                    url:"{{ route('violation_records.index') }}",
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
            // function for ajax table pagination
            $(window).on('hashchange', function() {
                if (window.location.hash) {
                    var vr_page = window.location.hash.replace('#', '');
                    if (vr_page == Number.NaN || vr_page <= 0) {
                        return false;
                    }else{
                        vr_getData(vr_page);
                    }
                }
            });
            $(document).on('click', '.pagination a', function(event){
                event.preventDefault();

                var vr_page = $(this).attr('href').split('page=')[1];
                $('#vr_hidden_page').val(vr_page);
                console.log($(this).val());

                load_violationRec_table();
                vr_getData(vr_page);
                $('li.page-item').removeClass('active');
                $(this).parent('li.page-item').addClass('active');
            });
            function vr_getData(vr_page){
                $.ajax({
                    url: '?page=' + vr_page,
                    type: "get",
                    datatype: "html"
                }).done(function(data){
                    location.hash = vr_page;
                }).fail(function(jqXHR, ajaxOptions, thrownError){
                    alert('No response from server');
                });
            }
            // function for ajax table pagination end

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
                load_violationRec_table();
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
                load_violationRec_table();
            });

            // live search filter
            $('#violationRecsFiltr_liveSearch').on('keyup', function(){
                // var liveSearchValue = $(this).val();
                // table paginatin set to 1
                $('#vr_hidden_page').val(1);
                load_violationRec_table();
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
                    $('#violationRecFltr_programs option[data-programs="' + SHSP + '"]').show();
                    $('#violationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All Programs');
                    document.getElementById("violationRecFltr_programs").classList.remove("cust_input_hasvalue");
                    document.getElementById("violationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                    $('#violationRecFltr_programs').val(0);
                    $('#violationRecFltr_yearLvls').val(0);
                    $(this).removeClass('cust_input_hasvalue');
                }
                // table paginatin set to 1
                $('#vr_hidden_page').val(1);
                load_violationRec_table();
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
                    // show all year levels except 5th year
                    $('#violationRecFltr_yearLvls option[data-yearlvls="' + 1 + '"]').show();
                    $('#violationRecFltr_yearLvls option[data-yearlvls="' + 2 + '"]').show();
                    $('#violationRecFltr_yearLvls option[data-yearlvls="' + 3 + '"]').show();
                    $('#violationRecFltr_yearLvls option[data-yearlvls="' + 4 + '"]').show();
                    $('#violationRecFltr_yearLvls option[data-yearlvls="' + 5 + '"]').show();
                    document.getElementById("violationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                    $('#violationRecFltr_yearLvls').val(0);
                    $(this).removeClass('cust_input_hasvalue');
                }
                // table paginatin set to 1
                $('#vr_hidden_page').val(1);
                load_violationRec_table();
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
                load_violationRec_table();
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
                load_violationRec_table();
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
                // table paginatin set to 1
                $('#vr_hidden_page').val(1);
                load_violationRec_table();
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
                // table paginatin set to 1
                $('#vr_hidden_page').val(1);
                load_violationRec_table();
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
                load_violationRec_table();
            });

            // reset filter
            $('#resetViolationRecsFilter_btn').on('click', function(){
                // disable reset button
                $(this).prop('disabled', true);
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
                $('#violationRecFltr_programs option[data-programs="' + SASE + '"]').show();
                $('#violationRecFltr_programs option[data-programs="' + SBCS + '"]').show();
                $('#violationRecFltr_programs option[data-programs="' + SIHTM + '"]').show();
                $('#violationRecFltr_programs option[data-programs="' + SHSP + '"]').show();
                $('#violationRecFltr_programs').val(0);
                $('#violationRecFltr_programs option[data-default-program="' + all_programs + '"]').html('All Programs');
                // year levels
                document.getElementById("violationRecFltr_yearLvls").classList.remove("cust_input_hasvalue");
                $('#violationRecFltr_yearLvls option[data-yearlvls="' + 1 + '"]').show();
                $('#violationRecFltr_yearLvls option[data-yearlvls="' + 2 + '"]').show();
                $('#violationRecFltr_yearLvls option[data-yearlvls="' + 3 + '"]').show();
                $('#violationRecFltr_yearLvls option[data-yearlvls="' + 4 + '"]').show();
                $('#violationRecFltr_yearLvls option[data-yearlvls="' + 5 + '"]').show();
                $('#violationRecFltr_yearLvls').val(0);
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
                load_violationRec_table();
            });
        });
    </script>
@endpush