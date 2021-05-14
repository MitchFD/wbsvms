@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard'
])

@section('content')
    <div class="content">
    @if(auth()->user()->user_status == 'active')
        @php
            $get_user_role_info = App\Models\Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
            $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        @endphp
        @if(in_array('dashboard', $get_uRole_access))
            {{-- directory link --}}
            <div class="row mb-3">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <a href="{{ route('page.index', 'dashboard') }}" class="directory_active_link">Dashboard </a>
                </div>
            </div>

            @php
                $yearly_offenses = App\Models\Violations::selectRaw('year(recorded_at) year')
                                                ->groupBy('year')
                                                ->orderBy('year', 'desc')
                                                ->get();
                $count_yearly_offenses = count($yearly_offenses);
                $yearly_ViolationsArray = array();
            @endphp

            @if ($count_yearly_offenses > 0)
                @foreach ($yearly_offenses as $this_year)
                    @php
                        $this_yearVal_t = str_replace(array( '{', '}', '"', ':', 'year' ), '', $this_year);
                        
                        $this_monthly_offenses = App\Models\Violations::selectRaw('month(recorded_at) month')
                                                            ->whereYear('recorded_at', $this_yearVal_t)
                                                            ->groupBy('month')
                                                            ->orderBy('month', 'desc')
                                                            ->get();
                        $count_monthly_offenses = count($this_monthly_offenses); 
                        $yearly_ViolationsArray[] = $this_yearVal_t;
                    @endphp
                    {{$this_yearVal_t}} <br>

                    @if ($count_monthly_offenses > 0)
                        @php
                            $monthly_ViolationsArray = array();
                        @endphp
                        @foreach ($this_monthly_offenses as $this_month)
                            @php
                                $yearly_monthlyVal_t = str_replace(array( '{', '}', '"', ':', 'month' ), '', $this_month);
                                $dateObj   = DateTime::createFromFormat('!m', $yearly_monthlyVal_t);
                                $monthName = $dateObj->format('F');
                                $monthly_ViolationsArray[] = $monthName;
                            @endphp
                            {{$monthName}} <br>
                        @endforeach
                    @endif
                @endforeach
                @php
                    $toJson_arrayYearlyViolations = json_encode($monthly_ViolationsArray);
                    $ext_toJson_arrayYearlyViolations = str_replace(array('"'), '', $toJson_arrayYearlyViolations);

                    $toJson_arrayMonthlyViolations = json_encode($yearly_ViolationsArray);
                    $ext_toJson_arrayMonthlyViolations = str_replace(array('"'), '', $toJson_arrayMonthlyViolations);
                @endphp
            @endif
            {{$ext_toJson_arrayYearlyViolations}}
            {{$ext_toJson_arrayMonthlyViolations}}

            {{-- schools violators counts --}}
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="card card_gbr card_ofh shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <img class="dash_cards_img" src="{{asset('storage/svms/sdca_images/schools_logos/sbcs.jpg')}}" alt="SBCS Logo">
                            <div class="dash_cards_text_div">
                                <span class="dash_card_title">SBCS</span>
                                <span class="dash_card_count" id="sbcsViolatorsCount"></span>
                            </div>
                        </div>
                        <div class="card-footer dash_card_footer align-items-center">
                            <i class="fa fa-user-o mr-1"></i> <span id="sbcsInfo"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="card card_gbr card_ofh shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <img class="dash_cards_img" src="{{asset('storage/svms/sdca_images/schools_logos/shsp.jpg')}}" alt="SHSP Logo">
                            <div class="dash_cards_text_div">
                                <span class="dash_card_title">SHSP</span>
                                <span class="dash_card_count" id="shspViolatorsCount"></span>
                            </div>
                        </div>
                        <div class="card-footer dash_card_footer align-items-center">
                            <i class="fa fa-user-o mr-1"></i> <span id="shspInfo"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="card card_gbr card_ofh shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <img class="dash_cards_img" src="{{asset('storage/svms/sdca_images/schools_logos/sihtm.jpg')}}" alt="SIHTM Logo">
                            <div class="dash_cards_text_div">
                                <span class="dash_card_title">SIHTM</span>
                                <span class="dash_card_count" id="sihtmViolatorsCount"></span>
                            </div>
                        </div>
                        <div class="card-footer dash_card_footer align-items-center">
                            <i class="fa fa-user-o mr-1"></i> <span id="sihtmInfo"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="card card_gbr card_ofh shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <img class="dash_cards_img" src="{{asset('storage/svms/sdca_images/schools_logos/sase.jpg')}}" alt="SASE Logo">
                            <div class="dash_cards_text_div">
                                <span class="dash_card_title">SASE</span>
                                <span class="dash_card_count" id="saseViolatorsCount"></span>
                            </div>
                        </div>
                        <div class="card-footer dash_card_footer align-items-center">
                            <i class="fa fa-user-o mr-1"></i> <span id="saseInfo"></span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- schools violators statistical graph --}}
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="accordion" id="schoolsViolatorsGraphCollapseParent">
                        <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                            <div class="card-header p-0" id="schoolsViolatorsGraphCollapseHeading">
                                <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#schoolsViolatorsGraphCollapseDiv" aria-expanded="true" aria-controls="schoolsViolatorsGraphCollapseDiv">
                                    <div>
                                        <span class="card_body_title">Monthly Violators</span>
                                        <span class="card_body_subtitle">Monthly Violators Count per School.</span>
                                    </div>
                                    <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                                </button>
                            </div>
                            <div id="schoolsViolatorsGraphCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="schoolsViolatorsGraphCollapseHeading" data-parent="#schoolsViolatorsGraphCollapseParent">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card card_gbr card_ofh shadow">
                                            <div class="card-body">
                                                <div class="chart-container cust_chart_cointainer">
                                                    <canvas id="shoolsViolatorsChart" height="90" style="margin-top: 20px;">

                                                    </canvas>
                                                </div>
                                            </div>
                                            <div class="card-footer dash_card_footer align-items-center">
                                                <i class="fa fa-user-o mr-1"></i> <span id="totalInfo"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- top 5s --}}
            {{-- <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="accordion" id="top5ViolatorsCollapseParent">
                        <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                            <div class="card-header p-0" id="top5ViolatorsCollapseHeading">
                                <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#top5ViolatorsCollapseDiv" aria-expanded="true" aria-controls="top5ViolatorsCollapseDiv">
                                    <div>
                                        <span class="card_body_title">Top 5 Violators</span>
                                        <span class="card_body_subtitle">Students with most offenses counts.</span>
                                    </div>
                                    <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                                </button>
                            </div>
                            <div id="top5ViolatorsCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="top5ViolatorsCollapseHeading" data-parent="#top5ViolatorsCollapseParent">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="list-group shadow cust_list_group_ve">
                                            <a href="#" data-toggle="modal" class="list-group-item list-group-item-action cust_lg_item_ve2">
                                                <div class="display_user_image_div text-center">
                                                    <img class="display_violator_image2 shadow-sm" src="{{asset('storage/svms/user_images/default_student_img.jpg')}}" alt="student's image">
                                                </div>
                                                <div class="information_div">
                                                    <span class="li_info_title">Mitch Frankein O. Desierto</span>
                                                    <span class="li_info_subtitle2"><span class="font-weight-bold">20150348 </span> | SBCS - BSIT 4A | Male</span>
                                                </div>
                                            </a>
                                            <a href="#" data-toggle="modal" class="list-group-item list-group-item-action cust_lg_item_ve2">
                                                <div class="display_user_image_div text-center">
                                                    <img class="display_violator_image2 shadow-sm" src="{{asset('storage/svms/user_images/default_student_img.jpg')}}" alt="student's image">
                                                </div>
                                                <div class="information_div">
                                                    <span class="li_info_title">Mitch Frankein O. Desierto</span>
                                                    <span class="li_info_subtitle"><span class="font-weight-bold">20150348 </span> | SBCS - BSIT 4A | Male</span>
                                                </div>
                                            </a>
                                            <a href="#" data-toggle="modal" class="list-group-item list-group-item-action cust_lg_item_ve2">
                                                <div class="display_user_image_div text-center">
                                                    <img class="display_violator_image2 shadow-sm" src="{{asset('storage/svms/user_images/default_student_img.jpg')}}" alt="student's image">
                                                </div>
                                                <div class="information_div">
                                                    <span class="li_info_title">Mitch Frankein O. Desierto</span>
                                                    <span class="li_info_subtitle2"><span class="font-weight-bold">20150348 </span> | SBCS - BSIT 4A | Male</span>
                                                </div>
                                            </a>
                                            <a href="#" data-toggle="modal" class="list-group-item list-group-item-action cust_lg_item_ve2">
                                                <div class="display_user_image_div text-center">
                                                    <img class="display_violator_image2 shadow-sm" src="{{asset('storage/svms/user_images/default_student_img.jpg')}}" alt="student's image">
                                                </div>
                                                <div class="information_div">
                                                    <span class="li_info_title">Mitch Frankein O. Desierto</span>
                                                    <span class="li_info_subtitle"><span class="font-weight-bold">20150348 </span> | SBCS - BSIT 4A | Male</span>
                                                </div>
                                            </a>
                                            <a href="#" data-toggle="modal" class="list-group-item list-group-item-action cust_lg_item_ve2">
                                                <div class="display_user_image_div text-center">
                                                    <img class="display_violator_image2 shadow-sm" src="{{asset('storage/svms/user_images/default_student_img.jpg')}}" alt="student's image">
                                                </div>
                                                <div class="information_div">
                                                    <span class="li_info_title">Mitch Frankein O. Desierto</span>
                                                    <span class="li_info_subtitle2"><span class="font-weight-bold">20150348 </span> | SBCS - BSIT 4A | Male</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="accordion" id="top5ProgramsCollapseParent">
                        <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                            <div class="card-header p-0" id="top5ProgramsCollapseHeading">
                                <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#top5ProgramsCollapseDiv" aria-expanded="true" aria-controls="top5ProgramsCollapseDiv">
                                    <div>
                                        <span class="card_body_title">Top 5 Programs</span>
                                        <span class="card_body_subtitle">Programs with the most violators.</span>
                                    </div>
                                    <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                                </button>
                            </div>
                            <div id="top5ProgramsCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="top5ProgramsCollapseHeading" data-parent="#top5ProgramsCollapseParent">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="accordion" id="top5OffensesCollapseParent">
                        <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                            <div class="card-header p-0" id="top5OffensesCollapseHeading">
                                <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#top5OffensesCollapseDiv" aria-expanded="true" aria-controls="top5OffensesCollapseDiv">
                                    <div>
                                        <span class="card_body_title">Top 5 Offenses</span>
                                        <span class="card_body_subtitle">Most Offenses committed by students.</span>
                                    </div>
                                    <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                                </button>
                            </div>
                            <div id="top5OffensesCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="top5OffensesCollapseHeading" data-parent="#top5OffensesCollapseParent">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- original --}}
            {{-- <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5 col-md-4 text-left">
                                    <div class="icon-big text-center icon-warning">
                                        <img class="dash_cards_img" src="{{asset('storage/svms/sdca_images/schools_logos/sase.jpg')}}" alt="SASE Logo">
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">SASE</p>
                                        <p class="card-title">150GB<p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats">
                                <i class="nc-icon nc-circle-10"></i> 20 violators found
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="nc-icon nc-money-coins text-success"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Revenue</p>
                                        <p class="card-title">$ 1,345
                                            <p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-calendar-o"></i> Last day
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="nc-icon nc-vector text-danger"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Errors</p>
                                        <p class="card-title">23
                                            <p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-clock-o"></i> In the last hour
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="nc-icon nc-favourite-28 text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Followers</p>
                                        <p class="card-title">+45K
                                            <p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-refresh"></i> Update now
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="card-title">Users Behavior</h5>
                            <p class="card-category">24 Hours performance</p>
                        </div>
                        <div class="card-body ">
                            <canvas id=chartHours width="400" height="100"></canvas>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-history"></i> Updated 3 minutes ago
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="card-title">Email Statistics</h5>
                            <p class="card-category">Last Campaign Performance</p>
                        </div>
                        <div class="card-body ">
                            <canvas id="chartEmail"></canvas>
                        </div>
                        <div class="card-footer ">
                            <div class="legend">
                                <i class="fa fa-circle text-primary"></i> Opened
                                <i class="fa fa-circle text-warning"></i> Read
                                <i class="fa fa-circle text-danger"></i> Deleted
                                <i class="fa fa-circle text-gray"></i> Unopened
                            </div>
                            <hr>
                            <div class="stats">
                                <i class="fa fa-calendar"></i> Number of emails sent
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h5 class="card-title">NASDAQ: AAPL</h5>
                            <p class="card-category">Line Chart with Points</p>
                        </div>
                        <div class="card-body">
                            <canvas id="speedChart" width="400" height="100"></canvas>
                        </div>
                        <div class="card-footer">
                            <div class="chart-legend">
                                <i class="fa fa-circle text-info"></i> Tesla Model S
                                <i class="fa fa-circle text-warning"></i> BMW 5 Series
                            </div>
                            <hr />
                            <div class="card-stats">
                                <i class="fa fa-check"></i> Data information certified
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}
        @else

        @endif
    @else
        
    @endif
    </div>
@endsection

@push('scripts')
    {{-- <script>
        $(document).ready(function() {
            this is a comment = Javascript method's body can be found in assets/assets-for-demo/js/demo.js
            demo.initChartsPages();
        });
    </script> --}}

    {{-- LOAD DASHBOARD PAGE --}}
        <script>
            $(document).ready(function(){
                load_dashboard_contents();

                function load_dashboard_contents(){
                    $.ajax({
                        url:"{{ route('dashboard.load_contents') }}",
                        method:"GET",
                        dataType:'json',
                        success:function(data){
                            $('#sbcsViolatorsCount').html(data.sbcs_violators_count);
                            $('#sbcsInfo').html(data.sbcs_S);

                            $('#shspViolatorsCount').html(data.shsp_violators_count);
                            $('#shspInfo').html(data.shsp_S);

                            $('#sihtmViolatorsCount').html(data.sihtm_violators_count);
                            $('#sihtmInfo').html(data.sihtm_S);

                            $('#saseViolatorsCount').html(data.sase_violators_count);
                            $('#saseInfo').html(data.sase_S);

                            $('#totalInfo').html(data.total_S);

                            // console.log(data.sbcs_violators_count);
                            // console.log(data.shsp_violators_count);
                            // console.log(data.sihtm_violators_count);
                            // console.log(data.sase_violators_count);

                            console.log(data.years);
                            console.log(data.months);

                            // chart
                            let shoolsViolatorsChart = document.getElementById('shoolsViolatorsChart').getContext('2d');
                            let massPopChart = new Chart(shoolsViolatorsChart, {
                                type: 'line',
                                data: {
                                    labels: [
                                        // 'April 2020', 'January 2021', 'February 2021', 'March 2021', 'April 2021', 'May 2021'
                                        data.months
                                    ],
                                    datasets: [
                                        {
                                            label: 'SBCS',
                                            data: [1, 15, 18, 20, 10, 1],
                                            fill: true,
                                            backgroundColor: 'rgb(114, 114, 114, 0.04)',
                                            borderColor: '#727272',
                                            hoverBackgroundColor: 'rgb(114, 114, 114, 0.9)',
                                            borderWidth: 2,
                                            Color: '#727272'
                                        },
                                        {
                                            label: 'SHSP',
                                            data: [0, 8, 5, 12, 5, 8],
                                            fill: true,
                                            backgroundColor: 'rgb(0, 113, 58, 0.04)',
                                            borderColor: '#00713A',
                                            hoverBackgroundColor: 'rgb(0, 113, 58, 0.9)',
                                            borderWidth: 2,
                                            Color: '#00713A'
                                        },
                                        {
                                            label: 'SIHTM',
                                            data: [0, 3, 8, 3, 2, 9],
                                            fill: true,
                                            backgroundColor: 'rgb(234, 64, 33, 0.04)',
                                            borderColor: '#EA4021',
                                            hoverBackgroundColor: 'rgb(234, 64, 33, 0.9)',
                                            borderWidth: 2,
                                            Color: '#EA4021'
                                        },
                                        {
                                            label: 'SASE',
                                            data: [0, 6, 19, 5, 15, 5],
                                            fill: true,
                                            backgroundColor: 'rgb(153, 51, 101, 0.04)',
                                            borderColor: '#993365',
                                            hoverBackgroundColor: 'rgb(153, 51, 101, 0.9)',
                                            borderWidth: 2,
                                            Color: '#993365'
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    legend: {
                                        position: 'bottom',
                                        display: true,
                                        labels: {
                                            usePointStyle: true,
                                            padding: 30 
                                        }
                                    },
                                    onHover: (event, shoolsViolatorsChart) => {
                                        event.target.style.cursor = shoolsViolatorsChart[0] ? 'pointer' : 'pointer';
                                    }
                                }
                            });
                        }
                        // ,
                        // complete:function(data){
                        //     setTimeout(load_dashboard_contents,30000);
                        // }
                    });
                }
                $(document).ready(function(){
                    setInterval(load_dashboard_contents,30000);
                });
            });
        </script>
    {{-- LOAD DASHBOARD PAGE end --}}

@endpush