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
    {{-- notifications end --}}

    {{-- directory link --}}
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <a href="{{ route('violation_records.index', 'violation_records') }}" class="directory_link">Violation Records</a> 
                <span class="directory_divider"> / </span> 
                <a href="{{ route('violation_records.violator', $violator_info->Student_Number , 'violation_records') }}" class="directory_active_link">Violator <span class="directory_divider"> ~ </span> {{ $violator_info->Last_Name }}</a>
            </div>
        </div>
    {{-- directory link end --}}

    {{-- card intro --}}
        {{-- <div class="row">
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
        </div> --}}
    {{-- card intro --}}
    
    {{-- violator's info --}}
        <div class="row">
        {{-- violator's profile card --}}
            {{-- custom values --}}
            @php
                if($offenses_count > 0){
                    $total_offenses = App\Models\Violations::select('offense_count')->where('stud_num', $violator_info->Student_Number)->sum('offense_count');
                    $total_notCleared_off = App\Models\Violations::select('offense_count', 'violation_status', 'stud_num')
                                                ->where('stud_num', $violator_info->Student_Number)
                                                ->where('violation_status', '!=', 'cleared')
                                                ->sum('offense_count');
                    $total_cleared_off = App\Models\Violations::select('offense_count', 'violation_status', 'stud_num')
                                                ->where('stud_num', $violator_info->Student_Number)
                                                ->where('violation_status', '=', 'cleared')
                                                ->sum('offense_count');
                    if($total_offenses > 0){
                        if($total_offenses > 1){
                            $toc_s = 's';
                        }else{
                            $toc_s = '';
                        }
                    }else{
                        $toc_s = '';
                    }
                    if($total_notCleared_off > 0){
                        if($total_notCleared_off > 1){
                            $tUoc_s = 's';
                            $all_txt = 'all';
                        }else{
                            $tUoc_s = '';
                            $all_txt = '';
                        }
                    }else{
                        $tUoc_s = '';
                        $all_txt = '';
                    }
                    if($total_cleared_off == $total_offenses){
                        $no_vr_imgFltr = 'up_stud_user_image';
                        $default_studImg = 'default_cleared_student_img.jpg';
                        $btn_label = 'Clearance';
                        $btn_class  = "btn-success";
                        $btn_icon   = "nc-icon nc-check-2";
                        $btn_tooltip = 'All ' . $total_offenses . ' offense/s made by ' . $violator_info->First_Name . ' ' . $violator_info->Last_Name . ' have been cleared.';
                    }else{
                        $no_vr_imgFltr = 'up_red_user_image';
                        $default_studImg = 'default_student_img.jpg';
                        $btn_label = 'Clearance';
                        $btn_class  = "btn_svms_red";
                        $btn_icon   = "fa fa-exclamation-circle";
                        $btn_tooltip = 'There are still ' . $total_notCleared_off . ' uncleared offense/s made by ' . $violator_info->First_Name . ' ' . $violator_info->Last_Name;
                    }
                }else{
                    $total_offenses = 0;
                    $total_notCleared_off = 0;
                    $total_cleared_off = 0;
                    $toc_s = '';
                    $no_vr_imgFltr = 'up_stud_user_image';
                    $default_studImg = 'default_cleared_student_img.jpg';
                    $btn_label = 'No Offenses Found';
                    $btn_class  = "btn-success";
                    $btn_icon   = "nc-icon nc-check-2";
                    $btn_tooltip = 'There are no offenses found for ' . $violator_info->First_Name . ' ' . $violator_info->Last_Name;
                }
            @endphp
            {{-- custom values end --}}
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="accordion gCardAccordions" id="violator{{$violator_info->Student_Number}}_ProfileCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="violatorProfileCollapseHeading">
                            <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#violator{{$violator_info->Student_Number}}_ProfileCollapseDiv" aria-expanded="true" aria-controls="violator{{$violator_info->Student_Number}}_ProfileCollapseDiv">
                                <div>
                                    <span class="card_body_title">Violator's Profile</span>
                                    <span class="card_body_subtitle">
                                        @if($total_offenses > 0)
                                            {{$total_offenses }} Offenses
                                        @else
                                            No Offenses Found
                                        @endif
                                    </span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="violator{{$violator_info->Student_Number}}_ProfileCollapseDiv" class="collapse gCardAccordions_collapse show cb_t0b15x25" aria-labelledby="violatorProfileCollapseHeading" data-parent="#violatorProfileCollapseParent">
                            <div class="card card_gbr shadow card-user">
                                <div class="image">
                                    <img src="{{ asset('paper/img/damir-bosnjak.jpg') }}" alt="...">
                                </div>
                                <div class="card-body">
                                    <div class="author">
                                        <a href="#" class="up_img_div">
                                            <img class="{{ $no_vr_imgFltr }} shadow"
                                            @if(!is_null($violator_info->Student_Image))
                                                src="{{asset('storage/svms/sdca_images/registered_students_imgs/'.$violator_info->Student_Image)}}" alt="{{$violator_info->First_Name }} {{ $violator_info->Last_Name}}'s profile image'"
                                            @else
                                                src="{{asset('storage/svms/sdca_images/registered_students_imgs/'.$default_studImg)}}" alt="default violator's profile image"
                                            @endif
                                            >
                                        </a>
                                        <span class="up_fullname_txt text_svms_blue">{{$violator_info->First_Name }}  {{ $violator_info->Middle_Name }} {{ $violator_info->Last_Name}}</span>
                                        <span class="up_info_txt"><i class="nc-icon nc-badge"></i> {{ $violator_info->Student_Number}}</span>

                                        <span class="cat_title_txt">{{$violator_info->School_Name}}</span>
                                        <span class="up_info_txt mb-3">{{$violator_info->Course}} <span class="subDiv"> | </span> {{$violator_info->YearLevel}}-Y</span>

                                        <span class="cat_title_txt">Gender / Age</span>
                                        @if($violator_info->Gender === 'Male')
                                            <span class="up_info_txt"><i class="fa fa-male"></i> {{ $violator_info->Gender}} - {{ $violator_info->Age}} y/o </span> 
                                        @elseif($violator_info->Gender === 'Female')
                                            <span class="up_info_txt"><i class="fa fa-female"></i> {{ $violator_info->Gender}} - {{ $violator_info->Age}} y/o</span> 
                                        @else
                                            <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> gender unknown</span>
                                        @endif

                                        <div class="row d-flex justify-content-center my-2">
                                            <div class="col-lg-12 col-md-12 col-sm-11 p-0 d-flex justify-content-center">
                                                <div class="btn-group cust_btn_group" role="group" aria-label="User's Account Status / Action">
                                                    <button type="button" class="btn {{ $btn_class }} btn_group_label m-0">{{ $btn_label }}</button>
                                                    <button type="button" class="btn {{ $btn_class }} btn_group_icon m-0" data-toggle="tooltip" data-placement="top" title="{{$btn_tooltip}}"><i class="{{ $btn_icon }}"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-center">
                                    <div>
                                    @if($total_offenses > 0)
                                        <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-list-ul mr-1" aria-hidden="true"></i> {{$total_offenses }} Total Offense{{$toc_s }} Found.</span>
                                    @else
                                        <span class="cust_info_txtwicon font-weight-bold"><i class="nc-icon nc-check-2 mr-1" aria-hidden="true"></i> No Offenses Found.</span>
                                    @endif
                                    @if($total_cleared_off > 0)
                                        <span class="cust_info_txtwicon"><i class="fa fa-check-square-o mr-1" aria-hidden="true"></i> {{$total_cleared_off }} Cleared Offenses.</span>  
                                    @endif
                                    @if($total_notCleared_off > 0)
                                        <span class="cust_info_txtwicon"><i class="fa fa-square-o mr-1" aria-hidden="true"></i> {{$total_notCleared_off }} Uncleared Offenses.</span>
                                    @endif
                                    </div>
                                    <button id="{{$violator_info->Student_Number}}" onclick="addViolationToStudent(this.id)" class="btn cust_btn_smcircle5v1" data-toggle="tooltip" data-placement="top" title="Record new Offenses for {{ $violator_info->First_Name }}  {{ $violator_info->Middle_Name }} {{ $violator_info->Last_Name}}?"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                </div>
                            </div>
                            {{-- check if all offenses has corresponding sanctions to notify the violator --}}
                            @if($offenses_count > 0)
                                @if(!is_null($violator_info->Email) OR !empty($violator_info->Email))
                                    @php
                                        $count_allRecViola = App\Models\Violations::where('stud_num', $violator_info->Student_Number)->count();
                                        $check_allRecViola_hasSanct = App\Models\Violations::where('stud_num', $violator_info->Student_Number)->where('has_sanction', 1)->count();
                                        $violator_gender = strtolower($violator_info->Gender);
                                        if($violator_gender === 'male'){
                                            $vMr_Ms = 'Mr.';
                                            $vHe_She = 'he';
                                        }elseif($violator_gender === 'female'){
                                            $vMr_Ms = 'Ms.';
                                            $vHe_She = 'she';
                                        }else{
                                            $vMr_Ms = 'Mr./Ms.';
                                            $vHe_She = 'he/she';
                                        }
                                    @endphp
                                    @if($check_allRecViola_hasSanct == $count_allRecViola)
                                        <div class="row mt-3">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="card card_gbr mb-2 shadow">
                                                    <div class="card-body">
                                                        <div class="card-body lightBlue_cardBody">
                                                            <span class="cust_info_txtwicon2 text-justify">Corresponding Sanctions have been aplied to {{ $all_txt }} {{ $total_notCleared_off }} Uncleared Offense{{$tUoc_s }} made by {{ $violator_info->First_Name }}  {{ $violator_info->Middle_Name }} {{ $violator_info->Last_Name}}.</span>
                                                        </div>
                                                        <div class="row mt-1">
                                                            <div class="col-lg-12 col-md-12 col-sm-11 d-flex justify-content-center">
                                                                <button id="{{$violator_info->Student_Number}}" onclick="notifyViolator(this.id)" type="submit" class="btn btn_svms_blue btn-round btn_show_icon1 shadow" data-toggle="tooltip" data-placement="top" title="Notify {{ $vMr_Ms }} {{ $violator_info->Last_Name }} of {{ $all_txt }} {{ $total_notCleared_off }} Uncleared Offense{{$tUoc_s }} {{ $vHe_She }} has committed and its corresponding sanctions?">Notify Student<i class="nc-icon nc-send btn_icon_show_right1" aria-hidden="true"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        {{-- violator's profile card end --}}
        
        {{-- offenses --}}
            <div class="col-lg-9 col-md-8 col-sm-12">
                @if($offenses_count > 0)
                    @php
                        $yearly_offenses = App\Models\Violations::selectRaw('year(recorded_at) year')
                                                                ->where('stud_num', $violator_info->Student_Number)
                                                                ->groupBy('year')->orderBy('year', 'desc')->get();
                    @endphp
                    @if(count($yearly_offenses) > 0)
                    {{-- has offenses found --}}
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <ul class="nav nav-tabs cust_nav_tabs" id="yearlyOffensesTab" role="tablist">
                                    @foreach($yearly_offenses as $yearly_tab)
                                        @php
                                            // extract year value
                                            $this_yearVal_t = str_replace(array( '{', '}', '"', ':', 'year' ), '', $yearly_tab);
                                            // count all offenses for this year
                                            $yearly_totalOffenses = App\Models\Violations::where('stud_num', $violator_info->Student_Number)
                                                    ->whereYear('recorded_at', $this_yearVal_t)
                                                    ->sum('offense_count');
                                            if($yearly_totalOffenses > 1){
                                                $yOc_s = 's'; 
                                            }else{
                                                $yOc_s = '';
                                            }
                                            // count all violations per year
                                            $yearly_totalViolaRec = App\Models\Violations::where('stud_num', $violator_info->Student_Number)
                                                    ->whereYear('recorded_at', $this_yearVal_t)
                                                    ->count();
                                            // count all cleared violations per year
                                            $yearly_totalClearedViolaRec = App\Models\Violations::where('stud_num', $violator_info->Student_Number)
                                                    ->whereYear('recorded_at', $this_yearVal_t)
                                                    ->where('violation_status', '=', 'cleared')
                                                    ->count();
                                            // custom values
                                            if($yearly_totalClearedViolaRec == $yearly_totalViolaRec){
                                                $class_custBadge_Y = 'cust_badge_grn';
                                                $toolTip_custBadge_Y = ''.$yearly_totalOffenses . ' Cleared Offense'.$yOc_s.' for the Year ' .$this_yearVal_t.'.';
                                            }else{
                                                $class_custBadge_Y = 'cust_badge_red2';
                                                $toolTip_custBadge_Y = ''.$yearly_totalOffenses . ' Recorded Offense'.$yOc_s.' Found for the Year ' .$this_yearVal_t.'.';
                                            }
                                        @endphp
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" id="{{$this_yearVal_t}}NavTab" data-toggle="tab" href="#{{$this_yearVal_t}}TabPanel" role="tab" aria-controls="{{$this_yearVal_t}}TabPanel" aria-selected="true">
                                                {{$this_yearVal_t }} 
                                                <span class="badge {{ $class_custBadge_Y }} ml-3" data-toggle="tooltip" data-placement="top" title="{{$toolTip_custBadge_Y}}">
                                                    {{$yearly_totalOffenses}}
                                                </span> 
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content" id="yearlyOffensesTabContent">
                                    @foreach($yearly_offenses as $yearly_tabContent)
                                        @php
                                            // extract year value
                                            $this_yearVal_tc = str_replace(array( '{', '}', '"', ':', 'year' ), '', $yearly_tabContent);
                                            // get all offenses for this year per month
                                            $this_year_offenses = App\Models\Violations::selectRaw('month(recorded_at) month')
                                                            ->where('stud_num', $violator_info->Student_Number)
                                                            ->whereYear('recorded_at', $this_yearVal_tc)
                                                            ->groupBy('month')
                                                            ->orderBy('month', 'desc')
                                                            ->get();
                                            // count all offenses for this month
                                            $yearly_totalOffenses = App\Models\Violations::where('stud_num', $violator_info->Student_Number)
                                                    ->whereYear('recorded_at', $this_yearVal_tc)
                                                    ->sum('offense_count');
                                            if($yearly_totalOffenses > 1){
                                                $yOc_s = 's'; 
                                            }else{
                                                $yOc_s = '';
                                            }
                                            // count all violations per year
                                            $yearly_totalUnclearedOffenses = App\Models\Violations::where('stud_num', $violator_info->Student_Number)
                                                    ->whereYear('recorded_at', $this_yearVal_tc)
                                                    ->where('violation_status', '=', 'not cleared')
                                                    ->sum('offense_count');
                                            if($yearly_totalUnclearedOffenses > 1){
                                                $yOC_s = 's'; 
                                            }else{
                                                $yOC_s = '';
                                            }
                                            // count all cleared violations per year
                                            $yearly_totalClearedOffenses = App\Models\Violations::where('stud_num', $violator_info->Student_Number)
                                                    ->whereYear('recorded_at', $this_yearVal_tc)
                                                    ->where('violation_status', '=', 'cleared')
                                                    ->sum('offense_count');
                                            if($yearly_totalClearedOffenses > 1){
                                                $yOUC_s = 's'; 
                                            }else{
                                                $yOUC_s = '';
                                            }
                                        @endphp
                                        <div class="tab-pane card_body_bg_gray card_bbr cb_y20l0r25 fade" style="margin-right: 40px;" id="{{$this_yearVal_tc}}TabPanel" role="tabpanel" aria-labelledby="{{$this_yearVal_tc}}NavTab">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <ul class="nav nav-tabs cust_nav_tabs2" id="monthlyOffensesTab" role="tablist">
                                                        @foreach($this_year_offenses as $monthly_tab)
                                                            @php
                                                                // extract month
                                                                $yearly_monthlyVal_t = str_replace(array( '{', '}', '"', ':', 'month' ), '', $monthly_tab);
                                                                $dateObj   = DateTime::createFromFormat('!m', $yearly_monthlyVal_t);
                                                                $monthName = $dateObj->format('F');
                                                                // count all offenses for this month
                                                                $monthly_totalOffenses = App\Models\Violations::where('stud_num', $violator_info->Student_Number)
                                                                        ->whereYear('recorded_at', $this_yearVal_tc)
                                                                        ->whereMonth('recorded_at', $yearly_monthlyVal_t)
                                                                        ->sum('offense_count');
                                                                if($monthly_totalOffenses > 1){
                                                                    $mOc_s = 's'; 
                                                                }else{
                                                                    $mOc_s = '';
                                                                }
                                                                // count all violations per month
                                                                $monthly_totalViolaRec = App\Models\Violations::where('stud_num', $violator_info->Student_Number)
                                                                        ->whereYear('recorded_at', $this_yearVal_tc)
                                                                        ->whereMonth('recorded_at', $yearly_monthlyVal_t)
                                                                        ->count();
                                                                // count all cleared violations per month
                                                                $monthly_totalClearedViolaRec = App\Models\Violations::where('stud_num', $violator_info->Student_Number)
                                                                        ->whereYear('recorded_at', $this_yearVal_tc)
                                                                        ->whereMonth('recorded_at', $yearly_monthlyVal_t)
                                                                        ->where('violation_status', '=', 'cleared')
                                                                        ->count();
                                                                // custom values
                                                                if($monthly_totalClearedViolaRec == $monthly_totalViolaRec){
                                                                    $class_custBadge_M = 'cust_badge_grn';
                                                                    $toolTip_custBadge_M = ''.$monthly_totalOffenses . ' Cleared Offense'.$mOc_s.' for Month of ' . ucwords($monthName).'.';
                                                                }else{
                                                                    $class_custBadge_M = 'cust_badge_red2';
                                                                    $toolTip_custBadge_M = ''.$monthly_totalOffenses . ' Recorded Offense'.$mOc_s.' Found for Month of ' . ucwords($monthName).'.';
                                                                }
                                                            @endphp 
                                                            <li class="nav-item">
                                                                <a class="nav-link d-flex align-items-center" id="{{$yearly_monthlyVal_t}}NavTab" data-toggle="tab" href="#{{$yearly_monthlyVal_t}}TabPanel" role="tab" aria-controls="{{$yearly_monthlyVal_t}}TabPanel" aria-selected="true">
                                                                    {{ $monthName }} 
                                                                    <span class="badge {{$class_custBadge_M}} ml-3" data-toggle="tooltip" data-placement="top" title="{{$toolTip_custBadge_M}}">
                                                                        {{$monthly_totalOffenses}}
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    <div class="tab-content" id="monthlyOffensesTabContent">
                                                        @foreach($this_year_offenses as $monthly_tabContent)
                                                            @php
                                                                // extract month
                                                                $yearly_monthlyVal_tc = str_replace(array( '{', '}', '"', ':', 'month' ), '', $monthly_tabContent);
                                                                $dateObj   = DateTime::createFromFormat('!m', $yearly_monthlyVal_tc);
                                                                $monthName = $dateObj->format('F');
                                                                // count offenses for this month
                                                                // total offenses
                                                                $monthly_totalOffenses = App\Models\Violations::where('stud_num', $violator_info->Student_Number)
                                                                        ->whereYear('recorded_at', $this_yearVal_tc)
                                                                        ->whereMonth('recorded_at', $yearly_monthlyVal_tc)
                                                                        ->sum('offense_count');
                                                                    if($monthly_totalOffenses > 1){
                                                                        $tO_s = 's';
                                                                    }else{
                                                                        $tO_s = '';
                                                                    }
                                                                // total unclear offenses
                                                                $monthly_totalUnclearOff = App\Models\Violations::where('stud_num', $violator_info->Student_Number)
                                                                        ->whereYear('recorded_at', $this_yearVal_tc)
                                                                        ->whereMonth('recorded_at', $yearly_monthlyVal_tc)
                                                                        ->where('violation_status', '!=', 'cleared')
                                                                        ->sum('offense_count');
                                                                    if($monthly_totalUnclearOff > 1){
                                                                        $tUO_s = 's';
                                                                    }else{
                                                                        $tUO_s = '';
                                                                    }
                                                                // total cleared offenses
                                                                $monthly_totalClearedOff = App\Models\Violations::where('stud_num', $violator_info->Student_Number)
                                                                        ->whereYear('recorded_at', $this_yearVal_tc)
                                                                        ->whereMonth('recorded_at', $yearly_monthlyVal_tc)
                                                                        ->where('violation_status', 'cleared')
                                                                        ->sum('offense_count');
                                                                    if($monthly_totalClearedOff > 1){
                                                                        $tCO_s = 's';
                                                                    }else{
                                                                        $tCO_s = '';
                                                                    }
                                                            @endphp
                                                            <div class="tab-pane card_body_bg_gray2 card_bbr card_ofh cb_t20b20x25 fade" style="margin-right: -40px;" id="{{$yearly_monthlyVal_tc}}TabPanel" role="tabpanel" aria-labelledby="{{$yearly_monthlyVal_tc}}NavTab">
                                                                <div class="row">
                                                                    @php
                                                                        // get all offenses for this month per date
                                                                        $this_month_offenses = App\Models\Violations::where('stud_num', $violator_info->Student_Number)
                                                                                        ->whereYear('recorded_at', $this_yearVal_tc)
                                                                                        ->whereMonth('recorded_at', $yearly_monthlyVal_tc)
                                                                                        ->orderBy('recorded_at', 'desc')
                                                                                        ->get();
                                                                    @endphp
                                                                    @foreach($this_month_offenses as $date_offense)
                                                                        {{-- custom values --}}
                                                                        @php
                                                                            // date recorded
                                                                            $date_recorded = date('F d, Y ~ l - g:i A', strtotime($date_offense->recorded_at));
                                                                            // plural offense & sanctions count
                                                                            if($date_offense->offense_count > 1){
                                                                                $oC_s = 's';
                                                                            }else{
                                                                                $oC_s = '';
                                                                            }
                                                                            if($date_offense->has_sanct_count > 1){
                                                                                $sC_s = 's';
                                                                            }else{
                                                                                $sC_s = '';
                                                                            }
                                                                            // violator's last name and Mr./Mrs
                                                                            $query_violator_info = App\Models\Students::select('Last_Name', 'Gender')
                                                                                                                ->where('Student_Number', $violator_info->Student_Number)
                                                                                                                ->first();
                                                                            $get_violator_lname = $query_violator_info->Last_Name;
                                                                            $get_violator_gender = strtolower($query_violator_info->Gender);
                                                                            if($get_violator_gender === 'male'){
                                                                                $vmr_ms = 'Mr.';
                                                                            }elseif($get_violator_gender === 'female'){
                                                                                $vmr_ms = 'Ms.';
                                                                            }else{
                                                                                $vmr_ms = 'Mr./Ms.';
                                                                            }
                                                                            // responsible user
                                                                            if($date_offense->respo_user_id == auth()->user()->id){
                                                                                $recBy = 'Recorded by you.';
                                                                                $recByTooltip = 'This Violation was recorded by you on ' . $date_recorded.'.';
                                                                            }else{
                                                                                $get_recBy_info = App\Models\Users::select('id', 'user_role', 'user_lname', 'user_fname')
                                                                                                        ->where('id', $date_offense->respo_user_id)
                                                                                                        ->first();
                                                                                $recBy = ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_lname;
                                                                                $recByTooltip = 'This Violation was recorded by ' . ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_fname . ' ' . $get_recBy_info->user_lname . ' on ' . $date_recorded.'.';
                                                                            }
                                                                            // count all sanctions for this violation
                                                                            if($date_offense->has_sanction > 0){
                                                                                $count_allCompletedSanctions = App\Models\Sanctions::where('stud_num', $violator_info->Student_Number)
                                                                                                                    ->where('for_viola_id', $date_offense->viola_id)
                                                                                                                    ->where('sanct_status', '=', 'completed')
                                                                                                                    ->count();
                                                                            }else{
                                                                                $count_allCompletedSanctions = 0;
                                                                            }
                                                                            // cleared/uncleared classes
                                                                            if($date_offense->violation_status === 'cleared'){
                                                                                $light_cardBody       = 'lightGreen_cardBody';
                                                                                $light_cardBody_title = 'lightGreen_cardBody_greenTitle';
                                                                                $light_cardBody_list  = 'lightGreen_cardBody_list';
                                                                                $info_textClass       = 'cust_info_txtwicon4';
                                                                                $info_iconClass       = 'fa fa-check-square-o';
                                                                                $class_violationStat  = 'text-success font-italic';
                                                                                $txt_violationStat    = '~ Cleared';
                                                                            }else{
                                                                                if($date_offense->has_sanct_count > 0){
                                                                                    if($count_allCompletedSanctions == $date_offense->has_sanct_count){
                                                                                        $light_cardBody       = 'lightGreen_cardBody';
                                                                                        $light_cardBody_title = 'lightGreen_cardBody_greenTitle';
                                                                                        $light_cardBody_list  = 'lightGreen_cardBody_list';
                                                                                        $info_textClass       = 'cust_info_txtwicon4';
                                                                                        $info_iconClass       = 'fa fa-check-square-o';
                                                                                        $class_violationStat  = 'text-success font-italic';
                                                                                        $txt_violationStat    = '~ Cleared';
                                                                                    }else{
                                                                                        $light_cardBody       = 'lightRed_cardBody';
                                                                                        $light_cardBody_title = 'lightRed_cardBody_redTitle';
                                                                                        $light_cardBody_list  = 'lightRed_cardBody_list';
                                                                                        $info_textClass       = 'cust_info_txtwicon3';
                                                                                        $info_iconClass       = 'fa fa-exclamation-circle';
                                                                                        $class_violationStat  = 'text_svms_red font-italic';
                                                                                        $txt_violationStat    = '~ Not Cleared';
                                                                                    }
                                                                                }else{
                                                                                    $light_cardBody       = 'lightRed_cardBody';
                                                                                    $light_cardBody_title = 'lightRed_cardBody_redTitle';
                                                                                    $light_cardBody_list  = 'lightRed_cardBody_list';
                                                                                    $info_textClass       = 'cust_info_txtwicon3';
                                                                                    $info_iconClass       = 'fa fa-exclamation-circle';
                                                                                    $class_violationStat  = 'text_svms_red font-italic';
                                                                                    $txt_violationStat    = '~ Not Cleared';
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <div class="col-lg-4 col-md-5 col-sm-12 pt-4">
                                                                            <div class="accordion violaAccordions shadow cust_accordion_div" id="v{{$date_offense->viola_id}}Accordion_Parent">
                                                                                <div class="card custom_accordion_card">
                                                                                    <div class="card-header p-0" id="changeUserRoleCollapse_heading">
                                                                                        <h2 class="mb-0">
                                                                                            <button class="btn btn-block custom2_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#v{{$date_offense->viola_id}}Collapse_Div" aria-expanded="true" aria-controls="v{{$date_offense->viola_id}}Collapse_Div">
                                                                                                <div class="d-flex justify-content-start align-items-center">
                                                                                                    <div class="information_div2">
                                                                                                        <span class="li_info_title">{{date('F j, Y', strtotime($date_offense->recorded_at)) }} <span class="{{$class_violationStat}}"> {{ $txt_violationStat}}</span></span>
                                                                                                        <span class="li_info_subtitle">{{date('l - g:i A', strtotime($date_offense->recorded_at))}}</span>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <i class="nc-icon nc-minimal-up"></i>
                                                                                            </button>
                                                                                        </h2>
                                                                                    </div>
                                                                                    <div id="v{{$date_offense->viola_id}}Collapse_Div" class="collapse violaAccordions_collapse show cb_t0b12y15" aria-labelledby="v{{$date_offense->viola_id}}Collapse_heading" data-parent="#v{{$date_offense->viola_id}}Accordion_Parent">
                                                                                        @if(!is_null($date_offense->minor_off) OR !empty($date_offense->minor_off))
                                                                                            @php
                                                                                                $mo_x = 1;
                                                                                            @endphp
                                                                                            <div class="card-body {{ $light_cardBody }} mb-2">
                                                                                                <span class="{{$light_cardBody_title }} mb-1">Minor Offenses:</span>
                                                                                                @foreach(json_decode(json_encode($date_offense->minor_off), true) as $minor_offenses)
                                                                                                <span class="{{$light_cardBody_list }}"><span class="font-weight-bold mr-1">{{$mo_x++}}.</span> {{$minor_offenses}}</span>
                                                                                                @endforeach
                                                                                            </div>
                                                                                        @endif
                                                                                        @if(!is_null($date_offense->less_serious_off) OR !empty($date_offense->less_serious_off))
                                                                                            @php
                                                                                                $lso_x = 1;
                                                                                            @endphp
                                                                                            <div class="card-body {{ $light_cardBody }} mb-2">
                                                                                                <span class="{{$light_cardBody_title }} mb-1">Less Serious Offenses:</span>
                                                                                                @foreach(json_decode(json_encode($date_offense->less_serious_off), true) as $less_serious_offenses)
                                                                                                <span class="{{$light_cardBody_list }}"><span class="font-weight-bold mr-1">{{$lso_x++}}.</span> {{$less_serious_offenses}}</span>
                                                                                                @endforeach
                                                                                            </div>
                                                                                        @endif
                                                                                        @if(!is_null($date_offense->other_off) OR !empty($date_offense->other_off))
                                                                                            @if(!in_array(null, json_decode(json_encode($date_offense->other_off), true)))
                                                                                                @php
                                                                                                    $oo_x = 1;
                                                                                                @endphp
                                                                                                <div class="card-body {{ $light_cardBody }} mb-2">
                                                                                                    <span class="{{$light_cardBody_title }} mb-1">Other Offenses:</span>
                                                                                                    @foreach(json_decode(json_encode($date_offense->other_off), true) as $other_offenses)
                                                                                                    <span class="{{$light_cardBody_list }}"><span class="font-weight-bold mr-1">{{$oo_x++}}.</span> {{$other_offenses}}</span>
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            @endif
                                                                                        @endif
                                                                                        @csrf
                                                                                        <input type="hidden" name="vp_hidden_stud_num" id="vp_hidden_stud_num" value="{{$violator_info->Student_Number}}" />
                                                                                        @if($date_offense->has_sanction > 0)
                                                                                            @php
                                                                                                $get_all_sanctions = App\Models\Sanctions::select('sanct_status', 'sanct_details')
                                                                                                                                    ->where('stud_num', $violator_info->Student_Number)
                                                                                                                                    ->where('for_viola_id', $date_offense->viola_id)
                                                                                                                                    ->orderBy('created_at', 'asc')
                                                                                                                                    ->offset(0)
                                                                                                                                    ->limit($date_offense->has_sanct_count)
                                                                                                                                    ->get();
                                                                                                $count_completed_sanction = App\Models\Sanctions::where('stud_num', $violator_info->Student_Number)
                                                                                                                                    ->where('for_viola_id', $date_offense->viola_id)
                                                                                                                                    ->where('sanct_status', '=', 'completed')
                                                                                                                                    ->offset(0)
                                                                                                                                    ->limit($date_offense->has_sanct_count)
                                                                                                                                    ->count();
                                                                                            @endphp
                                                                                            <div class="card-body lightGreen_cardBody mb-2">
                                                                                                <div class="d-flex justify-content-between">
                                                                                                    <span class="lightGreen_cardBody_greenTitle mb-1">Sanctions:</span>
                                                                                                    <button id="{{$date_offense->viola_id}}" onclick="editSanction(this.id)" class="btn cust_btn_smcircle4v1" data-toggle="tooltip" data-placement="top" title="Edit Sanctions?"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                                                                </div>
                                                                                                @foreach($get_all_sanctions as $this_vrSanction)
                                                                                                    {{-- custom values for sanctions --}}
                                                                                                    @php
                                                                                                        if($this_vrSanction->sanct_status === 'completed'){
                                                                                                            $sanct_icon = 'fa fa-check-square-o';
                                                                                                        }else{
                                                                                                            $sanct_icon = 'fa fa-square-o';
                                                                                                        }
                                                                                                    @endphp
                                                                                                    <span class="lightGreen_cardBody_list"><i class="{{$sanct_icon }} mr-1 font-weight-bold" aria-hidden="true"></i> {{ $this_vrSanction->sanct_details}}</span>
                                                                                                @endforeach
                                                                                                {{-- <hr class="hr_grn">
                                                                                                <div class="row">
                                                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                                                        <span class="cust_info_txtwicon4"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> Security Guard: Wick</span>  
                                                                                                        <span class="cust_info_txtwicon4"><i class="fa fa-calendar mr-1" aria-hidden="true"></i> April 1, 1998</span>  
                                                                                                    </div>
                                                                                                </div> --}}
                                                                                            </div>
                                                                                        @else
                                                                                            <div class="row m-0">
                                                                                                <div class="col-lg-12 col-md-12 col-sm-12 p-0">
                                                                                                    <button id="{{$date_offense->viola_id}}" onclick="addSanction(this.id)" type="button" class="btn btn-success btn-block cust_bt_links shadow m-0"><i class="nc-icon nc-simple-add mr-1" aria-hidden="true"></i> Add Sanctions</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                        @if($date_offense->has_sanction > 0)
                                                                                            @php
                                                                                                // date completed
                                                                                                $date_completed = date('F d, Y ~ l - g:i A', strtotime($date_offense->cleared_at));
                                                                                                if ($count_completed_sanction == count($get_all_sanctions)) {
                                                                                                    $info_icon1Class = 'fa fa-check-square-o';
                                                                                                    $sancStatusTooltip = $date_offense->has_sanct_count . ' corresponding Sanction'.$sC_s . ' for this violation has been completed by ' . $vmr_ms . ' ' . $get_violator_lname . ' on ' . $date_completed.'.';
                                                                                                }else{
                                                                                                    $info_icon1Class = 'fa fa-list-ul';
                                                                                                    $sancStatusTooltip = $date_offense->has_sanct_count . ' corresponding Sanction'.$sC_s . ' for ' . $date_offense->offense_count . ' Offense'.$oC_s.' committed by ' . $vmr_ms . ' ' . $get_violator_lname . ' on ' . $date_recorded.'.';
                                                                                                }
                                                                                            @endphp
                                                                                            <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="{{ $sancStatusTooltip }}">
                                                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                                                    <span class="cust_info_txtwicon4 font-weight-bold"><i class="{{$info_icon1Class }} mr-1" aria-hidden="true"></i> {{$date_offense->has_sanct_count}} Sanction{{$sC_s}}</span>  
                                                                                                    @if($date_offense->violation_status === 'cleared')
                                                                                                        <span class="cust_info_txtwicon"><i class="fa fa-calendar-check-o mr-1" aria-hidden="true"></i> {{$date_completed}}</span> 
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                            <hr class="hr_gry">
                                                                                        @endif
                                                                                        <div class="row mt-3">
                                                                                            <div class="col-lg-12 col-md-12 col-sm-12 d-flex align-items-center justify-content-between">
                                                                                                <div class="cursor_pointer" data-toggle="tooltip" data-placement="top" title="{{ $recByTooltip }}">
                                                                                                    <span class="{{$info_textClass }} font-weight-bold"><i class="{{$info_iconClass }} mr-1" aria-hidden="true"></i> {{$date_offense->offense_count}} Offense{{$oC_s}}</span> 
                                                                                                    <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> {{ $recBy }}</span>  
                                                                                                </div>
                                                                                                <button id="{{$date_offense->viola_id}}" onclick="deleteThisViolation(this.id)" class="btn cust_btn_smcircle2" data-toggle="tooltip" data-placement="top" title="Delete recorded Offenses?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <div class="row mt-3">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-end">
                                                                        <div>
                                                                            <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-list-ul mr-1" aria-hidden="true"></i> {{ $monthly_totalOffenses }} Total Offense{{$tO_s}} for {{ $monthName }} {{ $this_yearVal_tc}}.</span>  
                                                                            @if($monthly_totalClearedOff > 0)
                                                                                <span class="cust_info_txtwicon"><i class="fa fa-check-square-o mr-1" aria-hidden="true"></i> {{ $monthly_totalClearedOff }} Cleared Offense{{$tCO_s}}.</span> 
                                                                            @endif
                                                                            @if($monthly_totalUnclearOff > 0)
                                                                                <span class="cust_info_txtwicon"><i class="fa fa-square-o mr-1" aria-hidden="true"></i> {{ $monthly_totalUnclearOff}} Uncleared Offense{{$tUO_s}}.</span> 
                                                                            @endif
                                                                        </div>
                                                                        <div class="d-flex align-items-end">
                                                                            <button id="{{$yearly_monthlyVal_tc}}" onclick="addSanctions_allMonthlyViolations(this.id)" class="btn cust_btn_smcircle5" data-toggle="tooltip" data-placement="top" title="Add Sanctions to all recorded Offenses for the Month of {{ $monthName }} {{ $this_yearVal_tc}}?"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                                            <button id="{{$yearly_monthlyVal_tc}}" onclick="delete_allMonthlyViolations(this.id, {{$this_yearVal_tc}})" class="btn cust_btn_smcircle5" data-toggle="tooltip" data-placement="top" title="Delete all recorded Offenses for the Month of {{ $monthName }} {{ $this_yearVal_tc}}?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                                        </div>
                                                                    </div> 
                                                                </div>
                                                            </div> 
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-end">
                                                    <div>
                                                        <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-list-ul mr-1" aria-hidden="true"></i> {{ $yearly_totalOffenses }} Total Offense{{$yOc_s}} for the year {{ $this_yearVal_tc}}.</span>  
                                                        @if($yearly_totalClearedOffenses > 0)
                                                            <span class="cust_info_txtwicon"><i class="fa fa-check-square-o mr-1" aria-hidden="true"></i> {{ $yearly_totalClearedOffenses }} Cleared Offense{{$yOC_s}}.</span> 
                                                        @endif
                                                        @if($yearly_totalUnclearedOffenses > 0)
                                                            <span class="cust_info_txtwicon"><i class="fa fa-square-o mr-1" aria-hidden="true"></i> {{ $yearly_totalUnclearedOffenses}} Uncleared Offense{{$yOUC_s}}.</span> 
                                                        @endif
                                                    </div>
                                                    <div class="d-flex align-items-end">
                                                        {{-- <button id="{{$yearly_monthlyVal_tc}}" onclick="addSanctions_allMonthlyViolations(this.id)" class="btn cust_btn_smcircle5" data-toggle="tooltip" data-placement="top" title="Add Sanctions to all recorded Offenses for the Month of {{ $monthName }} 2021?"><i class="fa fa-pencil" aria-hidden="true"></i></button> --}}
                                                        <button style="margin-right: 25px;" id="{{$this_yearVal_tc}}" onclick="delete_allYearlyViolations(this.id)" class="btn cust_btn_smcircle5" data-toggle="tooltip" data-placement="top" title="Delete all recorded Offenses for the Year {{ $this_yearVal_tc}}?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                {{-- has offenses found end --}}
                @else
                {{-- no offenses found --}}
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card card_gbr card_ofh shadow-none cb_p25 card_body_bg_gray" style="margin-bottom: -25px;">
                                <div class="no_data_div3 d-flex justify-content-center align-items-center text-center flex-column">
                                    <img class="no_data_svg" src="{{asset('storage/svms/illustrations/no_violations_found_2.svg')}}" alt="no offenses found">
                                    <span class="font-italic font-weight-bold">No Recorded Violation Found. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- no offenses found end --}}
                @endif

                {{-- has deleted offenses --}}
                @php
                    $has_deleted_offenses = App\Models\Deletedviolations::where('del_stud_num', $violator_id)->where('del_status', 1)->count();
                @endphp
                @if($has_deleted_offenses > 0)
                    @php
                        // queries
                        $count_deleted_violations = App\Models\Deletedviolations::where('del_stud_num', $violator_id)->where('del_status', 1)->count();
                        $sum_deleted_offenses = App\Models\Deletedviolations::where('del_stud_num', $violator_id)->where('del_status', 1)->sum('del_offense_count');
                        // custom values
                        if($sum_deleted_offenses > 1){
                            $doc_s = 's';
                        }else{
                            $doc_s = '';
                        }
                    @endphp
                    <div class="row mt-4">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="accordion gCardAccordions" id="deletedOffenses{{$violator_info->Student_Number}}_CollapseParent">
                                <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                                    <div class="card-header p-0" id="deletedOffensesCollapseHeading">
                                        <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#deletedOffenses{{$violator_info->Student_Number}}_CollapseDiv" aria-expanded="true" aria-controls="deletedOffenses{{$violator_info->Student_Number}}_CollapseDiv">
                                            <div>
                                                <span class="card_body_title">deleted Offenses</span>
                                                <span class="card_body_subtitle">{{$sum_deleted_offenses }} Deleted Offense{{$doc_s}}</span>
                                            </div>
                                            <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                                        </button>
                                    </div>
                                    <div id="deletedOffenses{{$violator_info->Student_Number}}_CollapseDiv" class="collapse gCardAccordions_collapse show cb_t0b15x25" aria-labelledby="deletedOffensesCollapseHeading" data-parent="#deletedOffensesCollapseParent">
                                        <div class="row">
                                            @if($count_deleted_violations > 0)
                                                @php
                                                    $query_del_violations = App\Models\Deletedviolations::where('del_stud_num', $violator_id)->where('del_status', 1)->orderBy('deleted_at', 'desc')->get();
                                                @endphp
                                                @foreach($query_del_violations as $deleted_violation)
                                                    @php
                                                        // dates
                                                        $date_recorded = date('F d, Y ~ l - g:i A', strtotime($deleted_violation->del_recorded_at));
                                                        $date_deleted = date('F d, Y ~ l - g:i A', strtotime($deleted_violation->deleted_at));
                                                        // plural offense & sanctions count
                                                        if($deleted_violation->del_offense_count > 1){
                                                            $oC_s = 's';
                                                        }else{
                                                            $oC_s = '';
                                                        }
                                                        if($deleted_violation->del_has_sanct_count > 1){
                                                            $sC_s = 's';
                                                        }else{
                                                            $sC_s = '';
                                                        }
                                                        // violator's last name and Mr./Mrs
                                                        $query_violator_info = App\Models\Students::select('Last_Name', 'Gender')
                                                                                            ->where('Student_Number', $deleted_violation->del_stud_num)
                                                                                            ->first();
                                                        $get_violator_lname = $query_violator_info->Last_Name;
                                                        $get_violator_gender = strtolower($query_violator_info->Gender);
                                                        if($get_violator_gender === 'male'){
                                                            $vmr_ms = 'Mr.';
                                                        }elseif($get_violator_gender === 'female'){
                                                            $vmr_ms = 'Ms.';
                                                        }else{
                                                            $vmr_ms = 'Mr./Ms.';
                                                        }
                                                        // responsible user (recorded violations)
                                                        if($deleted_violation->del_respo_user_id == auth()->user()->id){
                                                            $recBy = 'Recorded by you.';
                                                            $recByTooltip = 'This Violation was recorded by you on ' . $date_recorded.'.';
                                                        }else{
                                                            $get_recBy_info = App\Models\Users::select('id', 'user_role', 'user_lname', 'user_fname')
                                                                                    ->where('id', $deleted_violation->del_respo_user_id)
                                                                                    ->first();
                                                            $recBy = ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_lname;
                                                            $recByTooltip = 'This Violation was recorded by ' . ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_fname . ' ' . $get_recBy_info->user_lname . ' on ' . $date_recorded.'.';
                                                        }
                                                        // responsible user (deleting violation)
                                                        if($deleted_violation->respo_user_id == auth()->user()->id){
                                                            $delBy = 'Deleted by you.';
                                                            $delByTooltip = 'This Violation was deleted by you on ' . $date_deleted.'.';
                                                        }else{
                                                            $get_delBy_info = App\Models\Users::select('id', 'user_role', 'user_lname', 'user_fname')
                                                                                    ->where('id', $deleted_violation->respo_user_id)
                                                                                    ->first();
                                                            $delBy = ucwords($get_delBy_info->user_role).': ' . $get_delBy_info->user_lname;
                                                            $delByTooltip = 'This Violation was deleted by ' . ucwords($get_delBy_info->user_role).': ' . $get_delBy_info->user_fname . ' ' . $get_delBy_info->user_lname . ' on ' . $date_deleted.'.';
                                                        }
                                                        // count all sanctions for this violation
                                                        if($deleted_violation->del_has_sanction > 0){
                                                            $count_allDelCompletedSanctions = App\Models\Deletedsanctions::where('del_stud_num', $violator_id)
                                                                                                ->where('del_for_viola_id', $deleted_violation->viola_id)
                                                                                                ->where('del_sanct_status', '=', 'completed')
                                                                                                ->count();
                                                        }else{
                                                            $count_allDelCompletedSanctions = 0;
                                                        }
                                                        // cleared/uncleared classes
                                                        if($deleted_violation->del_violation_status === 'cleared'){
                                                            $light_cardBody       = 'lightGreen_cardBody';
                                                            $light_cardBody_title = 'lightGreen_cardBody_greenTitle';
                                                            $light_cardBody_list  = 'lightGreen_cardBody_list';
                                                            $info_textClass       = 'cust_info_txtwicon4';
                                                            $info_iconClass       = 'fa fa-check-square-o';
                                                            $class_violationStat  = 'text-success font-italic';
                                                            $txt_violationStat    = '~ Cleared';
                                                        }else{
                                                            if($deleted_violation->del_has_sanct_count > 0){
                                                                if($count_allDelCompletedSanctions == $deleted_violation->del_has_sanct_count){
                                                                    $light_cardBody       = 'lightGreen_cardBody';
                                                                    $light_cardBody_title = 'lightGreen_cardBody_greenTitle';
                                                                    $light_cardBody_list  = 'lightGreen_cardBody_list';
                                                                    $info_textClass       = 'cust_info_txtwicon4';
                                                                    $info_iconClass       = 'fa fa-check-square-o';
                                                                    $class_violationStat  = 'text-success font-italic';
                                                                    $txt_violationStat    = '~ Cleared';
                                                                }else{
                                                                    $light_cardBody       = 'lightRed_cardBody';
                                                                    $light_cardBody_title = 'lightRed_cardBody_redTitle';
                                                                    $light_cardBody_list  = 'lightRed_cardBody_list';
                                                                    $info_textClass       = 'cust_info_txtwicon3';
                                                                    $info_iconClass       = 'fa fa-exclamation-circle';
                                                                    $class_violationStat  = 'text_svms_red font-italic';
                                                                    $txt_violationStat    = '~ Not Cleared';
                                                                }
                                                            }else{
                                                                $light_cardBody       = 'lightRed_cardBody';
                                                                $light_cardBody_title = 'lightRed_cardBody_redTitle';
                                                                $light_cardBody_list  = 'lightRed_cardBody_list';
                                                                $info_textClass       = 'cust_info_txtwicon3';
                                                                $info_iconClass       = 'fa fa-exclamation-circle';
                                                                $class_violationStat  = 'text_svms_red font-italic';
                                                                $txt_violationStat    = '~ Not Cleared';
                                                            }
                                                        }
                                                    @endphp
                                                    <div class="col-lg-4 col-md-5 col-sm-12 pt-4">
                                                        <div class="accordion hidden_violaAccordions shadow cust_accordion_div" id="v{{$deleted_violation->from_viola_id}}Accordion_Parent">
                                                            <div class="card custom_accordion_card">
                                                                <div class="card-header p-0" id="changeUserRoleCollapse_heading">
                                                                    <h2 class="mb-0">
                                                                        <button class="btn btn-block custom2_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#vD{{$deleted_violation->from_viola_id}}Collapse_Div" aria-expanded="true" aria-controls="vD{{$deleted_violation->from_viola_id}}Collapse_Div">
                                                                            <div class="d-flex justify-content-start align-items-center">
                                                                                <div class="information_div2">
                                                                                    <span class="li_info_title">{{date('F j, Y', strtotime($deleted_violation->del_recorded_at)) }} <span class="{{$class_violationStat}}"> {{ $txt_violationStat}}</span></span>
                                                                                    <span class="li_info_subtitle">{{date('l - g:i A', strtotime($deleted_violation->del_recorded_at))}}</span>
                                                                                </div>
                                                                            </div>
                                                                            <i class="nc-icon nc-minimal-up"></i>
                                                                        </button>
                                                                    </h2>
                                                                </div>
                                                                <div id="vD{{$deleted_violation->from_viola_id}}Collapse_Div" class="hidden_violaAccordions_collapse collapse cb_t0b12y15" aria-labelledby="v{{$deleted_violation->from_viola_id}}Collapse_heading" data-parent="#v{{$deleted_violation->from_viola_id}}Accordion_Parent">
                                                                    @if(!is_null($deleted_violation->del_minor_off) OR !empty($deleted_violation->del_minor_off))
                                                                        @php
                                                                            $mo_x = 1;
                                                                        @endphp
                                                                        <div class="card-body {{ $light_cardBody }} mb-2">
                                                                            <span class="{{$light_cardBody_title }} mb-1">Minor Offenses:</span>
                                                                            @foreach(json_decode(json_encode($deleted_violation->del_minor_off), true) as $minor_offenses)
                                                                            <span class="{{$light_cardBody_list }}"><span class="font-weight-bold mr-1">{{$mo_x++}}.</span> {{$minor_offenses}}</span>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                    @if(!is_null($deleted_violation->del_less_serious_off) OR !empty($deleted_violation->del_less_serious_off))
                                                                        @php
                                                                            $lso_x = 1;
                                                                        @endphp
                                                                        <div class="card-body {{ $light_cardBody }} mb-2">
                                                                            <span class="{{$light_cardBody_title }} mb-1">Less Serious Offenses:</span>
                                                                            @foreach(json_decode(json_encode($deleted_violation->del_less_serious_off), true) as $less_serious_offenses)
                                                                            <span class="{{$light_cardBody_list }}"><span class="font-weight-bold mr-1">{{$lso_x++}}.</span> {{$less_serious_offenses}}</span>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                    @if(!is_null($deleted_violation->del_other_off) OR !empty($deleted_violation->del_other_off))
                                                                        @if(!in_array(null, json_decode(json_encode($deleted_violation->del_other_off), true)))
                                                                            @php
                                                                                $oo_x = 1;
                                                                            @endphp
                                                                            <div class="card-body {{ $light_cardBody }} mb-2">
                                                                                <span class="{{$light_cardBody_title }} mb-1">Other Offenses:</span>
                                                                                @foreach(json_decode(json_encode($deleted_violation->del_other_off), true) as $other_offenses)
                                                                                <span class="{{$light_cardBody_list }}"><span class="font-weight-bold mr-1">{{$oo_x++}}.</span> {{$other_offenses}}</span>
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                    @csrf
                                                                    <input type="hidden" name="vp_hidden_stud_num" id="vp_hidden_stud_num" value="{{$violator_id}}" />
                                                                    @if($deleted_violation->del_has_sanction > 0)
                                                                        @php
                                                                            $completed_txt = 'completed';
                                                                            $get_all_sanctions = App\Models\Deletedsanctions::select('del_sanct_status', 'del_sanct_details')
                                                                                                                ->where('del_stud_num', $violator_id)
                                                                                                                ->where('del_for_viola_id', $deleted_violation->from_viola_id)
                                                                                                                ->orderBy('del_created_at', 'asc')
                                                                                                                ->offset(0)
                                                                                                                ->limit($deleted_violation->del_has_sanct_count)
                                                                                                                ->get();
                                                                            $count_completed_sanction = App\Models\Deletedsanctions::where('del_stud_num', $violator_id)
                                                                                                                ->where('del_for_viola_id', $deleted_violation->from_viola_id)
                                                                                                                ->where('del_sanct_status', '=', $completed_txt)
                                                                                                                ->offset(0)
                                                                                                                ->limit($deleted_violation->del_has_sanct_count)
                                                                                                                ->count();
                                                                        @endphp
                                                                        <div class="card-body lightGreen_cardBody mb-2">
                                                                            <div class="d-flex justify-content-between">
                                                                                <span class="lightGreen_cardBody_greenTitle mb-1">Sanctions:</span>
                                                                            </div>
                                                                            @foreach($get_all_sanctions as $this_vrSanction)
                                                                                @php
                                                                                    if($this_vrSanction->del_sanct_status === $completed_txt){
                                                                                        $sanct_icon = 'fa fa-check-square-o';
                                                                                    }else{
                                                                                        $sanct_icon = 'fa fa-square-o';
                                                                                    }
                                                                                @endphp
                                                                                <span class="lightGreen_cardBody_list"><i class="{{$sanct_icon }} mr-1 font-weight-bold" aria-hidden="true"></i> {{ $this_vrSanction->del_sanct_details}}</span>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                    @if($deleted_violation->del_has_sanction > 0)
                                                                        @php
                                                                            // date completed
                                                                            $date_completed = date('F d, Y ~ l - g:i A', strtotime($deleted_violation->del_cleared_at));
                                                                            if ($count_completed_sanction == count($get_all_sanctions)) {
                                                                                $info_icon1Class = 'fa fa-check-square-o';
                                                                                $sancStatusTooltip = $deleted_violation->del_has_sanct_count . ' corresponding Sanction'.$sC_s . ' for this violation has been completed by ' . $vmr_ms . ' ' . $get_violator_lname . ' on ' . $date_completed.'.';
                                                                            }else{
                                                                                $info_icon1Class = 'fa fa-list-ul';
                                                                                $sancStatusTooltip = $deleted_violation->del_has_sanct_count . ' corresponding Sanction'.$sC_s . ' for ' . $deleted_violation->del_offense_count . ' Offense'.$oC_s.' committed by ' . $vmr_ms . ' ' . $get_violator_lname . ' on ' . $date_recorded.'.';
                                                                            }
                                                                        @endphp
                                                                        <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="{{ $sancStatusTooltip }}">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                                                <span class="cust_info_txtwicon4 font-weight-bold"><i class="{{$info_icon1Class }} mr-1" aria-hidden="true"></i> {{$deleted_violation->del_has_sanct_count}} Sanction{{$sC_s}}</span>  
                                                                                @if($deleted_violation->del_violation_status === 'cleared')
                                                                                    <span class="cust_info_txtwicon"><i class="fa fa-calendar-check-o mr-1" aria-hidden="true"></i> {{date('F d, Y ~ D - g:i A', strtotime($deleted_violation->del_cleared_at)) }}</span> 
                                                                                @endif 
                                                                            </div>
                                                                        </div>
                                                                        <hr class="hr_gry">
                                                                    @endif
                                                                    <div class="row mt-3">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 d-flex align-items-center justify-content-between">
                                                                            <div class="cursor_pointer" data-toggle="tooltip" data-placement="top" title="{{ $recByTooltip }}"> 
                                                                                <span class="{{$info_textClass }} font-weight-bold"><i class="{{$info_iconClass }} mr-1" aria-hidden="true"></i> {{$deleted_violation->del_offense_count}} Offense{{$oC_s}}</span>
                                                                                <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> {{ $recBy }}</span>
                                                                            </div>
                                                                            <button id="{{$deleted_violation->from_viola_id}}" onclick="recoverThisDeletedViolation(this.id)" class="btn cust_btn_smcircle2" data-toggle="tooltip" data-placement="top" title="Recover this recorded Violation?"><i class="fa fa-external-link" aria-hidden="true"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    {{-- <hr class="hr_gry"> --}}
                                                                    @if(!is_null($deleted_violation->reason_deletion) OR !empty($deleted_violation->reason_deletion))
                                                                        <div class="row mt-3">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                                                <div class="card-body lightBlue_cardBody shadow-none">
                                                                                    <span class="lightBlue_cardBody_blueTitle">Reason for Deleting Violation:</span>
                                                                                    <span class="lightBlue_cardBody_list">{{$deleted_violation->reason_deletion}}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    <div class="row mt-3">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 d-flex align-items-center justify-content-between">
                                                                            <div class="cursor_pointer" data-toggle="tooltip" data-placement="top" title="{{ $delByTooltip }}">
                                                                                <span class="cust_info_txtwicon"><i class="fa fa-calendar-minus-o mr-1" aria-hidden="true"></i> {{date('F d, Y ~ D - g:i A', strtotime($deleted_violation->deleted_at)) }}</span> 
                                                                                <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> {{ $delBy }}</span>
                                                                            </div>
                                                                            <button id="{{$deleted_violation->from_viola_id}}" onclick="permanentDeleteThisViolation(this.id)" class="btn cust_btn_smcircle2" data-toggle="tooltip" data-placement="top" title="Permanently Delete recorded Offenses?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-end">
                                                <div>
                                                    <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-trash mr-1" aria-hidden="true"></i> {{$sum_deleted_offenses }} Deleted Offense{{$doc_s}} found.</span> 
                                                </div>
                                                <div class="d-flex align-items-end">
                                                    @if($count_deleted_violations > 0)
                                                        @php
                                                            $query_delViolaIds_only = App\Models\Deletedviolations::select('del_id')->where('del_stud_num', $violator_id)->where('del_status', 1)->orderBy('deleted_at', 'desc')->get();
                                                            if(count($query_delViolaIds_only) > 0){
                                                                $array_deletedViolaIds = array();
                                                                foreach($query_delViolaIds_only as $this_delViolaId){
                                                                    $array_deletedViolaIds[] = $this_delViolaId;
                                                                }
                                                                $toJson_arrayDeletedViolaIds = json_encode($array_deletedViolaIds);
                                                                $ext_toJson_arrayDeletedViolaIds = str_replace(array( '{', '}', '"', ':', 'del_id' ), '', $toJson_arrayDeletedViolaIds);
                                                            }
                                                        @endphp
                                                    @endif
                                                    <button id="{{$ext_toJson_arrayDeletedViolaIds}}" onclick="recover_allDeletedViolations(this.id)" class="btn cust_btn_smcircle5" data-toggle="tooltip" data-placement="top" title="Recover all Recently Deleted Violations?"><i class="fa fa-external-link" aria-hidden="true"></i></button>
                                                    <button id="{{$ext_toJson_arrayDeletedViolaIds}}" onclick="permDelete_allDeletedViolations(this.id)" class="btn cust_btn_smcircle5" data-toggle="tooltip" data-placement="top" title="Permanently Delete all Recently Deleted Violations?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- has deleted offenses end --}}
            </div>
        {{-- offenses end --}}
        </div>
    </div>
    {{-- violator's info end --}}

    {{-- modals --}}
    {{-- new violation entry modal --}}
        <div class="modal fade" id="newViolationEntryModal" tabindex="-1" role="dialog" aria-labelledby="newViolationEntryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0 pb-0">
                        {{-- <span class="modal-title cust_modal_title" id="newViolationEntryModalLabel">Violation Form</span> --}}
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="newViolationEntryModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- new violation entry modal end --}}
    {{-- add sanctions on modal --}}
        <div class="modal fade" id="addSanctionsModal" tabindex="-1" role="dialog" aria-labelledby="addSanctionsModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="addSanctionsModalLabel">Add Sanction?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="addSanctionModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- add sanctions on modal end --}}
    {{-- edit sanctions on modal --}}
        <div class="modal fade" id="editSanctionsModal" tabindex="-1" role="dialog" aria-labelledby="editSanctionsModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="editSanctionsModalLabel">Edit Sanctions?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="editSanctionModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- edit sanctions on modal end --}}
    {{-- temporary delete violation on modal --}}
        <div class="modal fade" id="deleteViolationModal" tabindex="-1" role="dialog" aria-labelledby="deleteViolationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="deleteViolationModalLabel">Delete Violation?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="deleteViolationModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- temporary delete violation on modal end --}}
    {{-- permanently delete violation on modal --}}
        <div class="modal fade" id="permanentDeleteViolationModal" tabindex="-1" role="dialog" aria-labelledby="permanentDeleteViolationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="permanentDeleteViolationModalLabel">Permanently Delete Violation?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="permanentDeleteViolationModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- permanently delete violation on modal end --}}
    {{-- delete all monthly violations modal --}}
        <div class="modal fade" id="deleteAllMonthlyViolationModal" tabindex="-1" role="dialog" aria-labelledby="deleteAllMonthlyViolationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="deleteAllMonthlyViolationModalLabel">Delete All  <span id="monthTxt_modalTitle" class="font-weight-bold">  </span>  Violations?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="deleteAllMonthlyViolationModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- delete all monthly violations modal end --}}
    {{-- delete all yearly violations modal --}}
        <div class="modal fade" id="deleteAllYearlyViolationModal" tabindex="-1" role="dialog" aria-labelledby="deleteAllYearlyViolationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="deleteAllYearlyViolationModalLabel">Delete All <span id="yearTxt_modalTitle" class="font-weight-bold">  </span> Violations?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="deleteAllYearlyViolationModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- delete all yearly violations modal end --}}
    {{-- permanently delete all violations modal --}}
        <div class="modal fade" id="permanentDeleteAllViolations" tabindex="-1" role="dialog" aria-labelledby="permanentDeleteAllViolationsLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="permanentDeleteAllViolationsLabel">Permanent Deleted Violations?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="permanentDeleteAllViolationsHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- permanently delete all violations modal end --}}

    {{-- recover delete violation on modal --}}
        {{-- single recovey --}}
        <div class="modal fade" id="recoverDeletedViolationModal" tabindex="-1" role="dialog" aria-labelledby="recoverDeletedViolationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="recoverDeletedViolationModalLabel">Recover Violation?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="recoverDeletedViolationModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
        {{-- multiple recovery --}}
        <div class="modal fade" id="recoverAllDeletedViolationModal" tabindex="-1" role="dialog" aria-labelledby="recoverAllDeletedViolationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="recoverAllDeletedViolationModalLabel">Recover Deleted Violations?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="recoverAllDeletedViolationModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- recover delete violation on modal end --}}

@endsection

@push('scripts')
{{-- activate nav-tabs & tab-contents first child --}}
    <script>
        $(function(){
            // yearly 
            $('#yearlyOffensesTab li:nth-child(1) a').addClass('active');
            $('#yearlyOffensesTabContent .tab-pane:nth-child(1)').addClass('show active');
            // monthly
            $('#monthlyOffensesTab li:nth-child(1) a').addClass('active');
            $('#monthlyOffensesTabContent .tab-pane:nth-child(1)').addClass('show active');
        });
    </script>
{{-- activate nav-tabs & tab-contents first child end --}}

{{-- notify violator --}}
    <script>
        function notifyViolator(sel_Student_Number){
            alert(sel_Student_Number);
        }
    </script>
{{-- notify violator end --}}

{{-- recording new offenses for the student --}}
    <script>
        function addViolationToStudent(sel_Student_Number){
            var violator_id = sel_Student_Number;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('violation_records.new_violation_form_modal') }}",
                method:"GET",
                data:{violator_id:violator_id, _token:_token},
                success: function(data){
                    $('#newViolationEntryModalHtmlData').html(data);
                    $('#newViolationEntryModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#newViolationEntryModal').on('show.bs.modal', function () {
            var newViolationEntry_form  = document.querySelector("#form_addNewViolation");
            var btn_submitNewViolationEntry = document.querySelector("#submit_newViolationForm_btn");
            var btn_cancelNewViolationEntry = document.querySelector("#cancel_newViolationForm_btn");
            var otherOffenses_input  = document.querySelector("#addOtherOffensesNew_input");
            var otherOffensesAdd_Btn = document.querySelector("#btn_addAnother_input");
            var addedNewOtherOff_field = $('.addedNewOtherOff_field').filter(function() {
                return this.value != '';
            });
            // disable cancel and submit button on form submit
            $(newViolationEntry_form).submit(function(){
                btn_cancelNewViolationEntry.disabled = true;
                btn_submitNewViolationEntry.disabled = true;
                return true;
            });

            // adding new input field
            $(otherOffenses_input).keyup(function(){
                if(otherOffenses_input.value !== ""){
                    otherOffensesAdd_Btn.disabled = false;
                }else{
                    if (addedNewOtherOff_field.length == 0) {
                        btn_submitNewViolationEntry.disabled = true;
                    }else{
                        btn_submitNewViolationEntry.disabled = false;
                    }
                    otherOffensesAdd_Btn.disabled = true;
                }
            });
            // appending new input field
            function addOtherOffNewIndexing(){
                i = 1;
                $(".addOtherOffIndexNew").each(function(){
                    $(this).html(i+1 + '.');
                    i++;
                });
            }

            var maxField = 10;
            var addedInputFieldsNew_div = document.querySelector('.addedInputFieldsNew_div');
            var newInputField = '<div class="input-group mb-2"> ' +
                                    '<div class="input-group-append"> ' +
                                        '<span class="input-group-text txt_iptgrp_append2 addOtherOffIndexNew font-weight-bold">1. </span> ' +
                                    '</div> ' +
                                    '<input type="text" name="other_offenses[]" class="form-control input_grpInpt2 addedNewOtherOff_field" placeholder="Type Other Offense" aria-label="Type Other Offense" aria-describedby="other-offenses-input" required /> ' +
                                    '<div class="input-group-append"> ' +
                                        '<button class="btn btn_svms_blue m-0 btn_deleteAnother_input" type="button"><i class="nc-icon nc-simple-remove font-weight-bold" aria-hidden="true"></i></button> ' +
                                    '</div> ' +
                                '</div>';
            var x = 1;
            $(otherOffensesAdd_Btn).click(function(){
                if(x < maxField){
                    x++;
                    $(addedInputFieldsNew_div).append(newInputField);
                    // console.log(x);
                }
                addOtherOffNewIndexing();
            });
            $(addedInputFieldsNew_div).on('click', '.btn_deleteAnother_input', function(e){
                e.preventDefault();
                $(this).closest('.input_grpInpt2').value = '';
                $(this).closest('.input-group').last().remove();
                x--;
                // console.log('click');
                addOtherOffNewIndexing();
            });
            
            // serialized form
            $('#form_addNewViolation').each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#submit_newViolationForm_btn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
            }).find('#submit_newViolationForm_btn').prop('disabled', true);
        });
    </script>
{{-- recording new offenses for the student end --}}

{{-- add sanctions on modal --}}
    <script>
        function addSanction(sel_viola_id){
            var sel_viola_id = sel_viola_id;
            var sel_stud_num = document.getElementById("vp_hidden_stud_num").value;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('violation_records.add_sanction_form') }}",
                method:"GET",
                data:{sel_viola_id:sel_viola_id, sel_stud_num:sel_stud_num, _token:_token},
                success: function(data){
                    $('#addSanctionModalHtmlData').html(data); 
                    $('#addSanctionsModal').modal('show');
                }
            });
        }
    </script>
{{-- add sanctions on modal end --}}

{{-- edit sanctions on modal --}}
    <script>
        function editSanction(sel_viola_id){
            var sel_viola_id = sel_viola_id;
            var sel_stud_num = document.getElementById("vp_hidden_stud_num").value;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('violation_records.edit_sanction_form') }}",
                method:"GET",
                data:{sel_viola_id:sel_viola_id, sel_stud_num:sel_stud_num, _token:_token},
                success: function(data){
                    $('#editSanctionModalHtmlData').html(data); 
                    $('#editSanctionsModal').modal('show');
                }
            });
        }
    </script>
{{-- edit sanctions on modal end --}}

{{-- adding new input field for adding sanctions on modal --}}
    {{-- check if first input has value to enable add new field button --}}
    <script>
        $('#addSanctionsModal').on('show.bs.modal', function () {
            var addSanctions_form  = document.querySelector("#form_addSanctions");
            var addSanctions_input  = document.querySelector("#addSanctions_input");
            var btn_addAnother_input = document.querySelector("#btn_addAnother_input");
            var btn_submitAddSanction = document.querySelector("#submit_addSanctionsBtn");
            var btn_cancelAddSanction = document.querySelector("#cancel_addSanctionsBtn");
            // disable add another input field and submit button if first input is empty
            $(addSanctions_input).keyup(function(){
                if(addSanctions_input.value !== ""){
                    btn_addAnother_input.disabled = false;
                    btn_submitAddSanction.disabled = false;
                }else{
                    btn_addAnother_input.disabled = true;
                    btn_submitAddSanction.disabled = true;
                }
            });
            // disable cancel and sibmit button on submit
            $(addSanctions_form).submit(function(){
                btn_cancelAddSanction.disabled = true;
                btn_submitAddSanction.disabled = true;
                return true;
            });
            // adding new input field
            function addSanctIndexing(){
                i = 1;
                $(".addSanctIndex").each(function(){
                    $(this).html(i+1 + '.');
                    i++;
                });
            }
            var maxField = 10;
            var addedInputFields_div = document.querySelector('.addedInputFields_div');
            var newInputField = '<div class="input-group mb-2">' +
                                    '<div class="input-group-append"> ' +
                                       '<span class="input-group-text txt_iptgrp_append addSanctIndex font-weight-bold"></span> ' +
                                    '</div>' +
                                    '<input type="text" name="sanctions[]" class="form-control input_grpInpt3" placeholder="Type Sanction" aria-label="Type Sanction" aria-describedby="add-sanctions-input" required /> ' +
                                    '<div class="input-group-append"> ' +
                                        '<button class="btn btn_svms_red m-0 btn_deleteAddedSanction_input" type="button"><i class="nc-icon nc-simple-remove font-weight-bold" aria-hidden="true"></i></button> ' +
                                    '</div> ' +
                                '</div>';
            var x = 1;
            $(btn_addAnother_input).click(function(){
                if(x < maxField){
                    x++;
                    $(addedInputFields_div).append(newInputField);
                }
                addSanctIndexing();
            });
            $(addedInputFields_div).on('click', '.btn_deleteAddedSanction_input', function(e){
                e.preventDefault();
                $(this).closest('.input_grpInpt3').value = '';
                $(this).closest('.input-group').last().remove();
                x--;
                addSanctIndexing();
            });
        });
    </script>

{{-- edit sanctions on form modal --}}
    <script>
        $('#editSanctionsModal').on('show.bs.modal', function () {
            // initialize nav-pills
                $('#editSanctionPills_tabParent a').on('click', function (e) {
                    e.preventDefault();
                    $(this).tab('show');
                });
            // initialize nav-pills end

            // disable/enable save buttons when form has changed
                // mark sanctions form serialized
                $('#form_markSanctions').each(function(){
                    $(this).data('serialized', $(this).serialize())
                }).on('change input', function(){
                    $(this).find('#submit_markSanctionsBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                }).find('#submit_markSanctionsBtn').prop('disabled', true);

                // delete sanctions form serialized
                $('#form_deleteSanctions').each(function(){
                    $(this).data('serialized', $(this).serialize())
                }).on('change input', function(){
                    $(this).find('#submit_deleteSanctionsBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                }).find('#submit_deleteSanctionsBtn').prop('disabled', true);
            // disable/enable save buttons when form has changed end

            // marking sanctions as completed form
                // selection of sanctions for marking as completed
                $("#sanctMarkAll").change(function(){
                    if(this.checked){
                        $(".sanctMarkSingle").each(function(){
                            this.checked=true;
                        });              
                    }else{
                        $(".sanctMarkSingle").each(function(){
                            this.checked=false;
                        });             
                    }
                });
                $(".sanctMarkSingle").click(function () {
                    if ($(this).is(":checked")){
                        var isMarkAllChecked = 0;
                        $(".sanctMarkSingle").each(function(){
                            if(!this.checked)
                            isMarkAllChecked = 1;
                        });              
                        if(isMarkAllChecked == 0){ $("#sanctMarkAll").prop("checked", true); }     
                    }else {
                        $("#sanctMarkAll").prop("checked", false);
                    }
                });
                var form_markSanctions  = document.querySelector("#form_markSanctions");
                var submit_markSanctionsBtn = document.querySelector("#submit_markSanctionsBtn");
                var cancel_markSanctionsBtn = document.querySelector("#cancel_markSanctionsBtn");
                // disable cancel and sibmit button on submit
                $(form_markSanctions).submit(function(){
                    cancel_markSanctionsBtn.disabled = true;
                    submit_markSanctionsBtn.disabled = true;
                    return true;
                });
            // marking sanctions as completed form end

            // adding new sanctions form
                // check if first input is not empty to enable add button
                var form_addNewSanctions  = document.querySelector("#form_addNewSanctions");
                var addNewSanction_input  = document.querySelector("#addNewSanction_input");
                var btn_addNewSanct_input = document.querySelector("#btn_addNewSanct_input");
                var submit_addNewSanctionsBtn = document.querySelector("#submit_addNewSanctionsBtn");
                var cancel_addNewSanctionsBtn = document.querySelector("#cancel_addNewSanctionsBtn");
                $(addNewSanction_input).keyup(function(){
                    if(addNewSanction_input.value !== ""){
                        btn_addNewSanct_input.disabled = false;
                        submit_addNewSanctionsBtn.disabled = false;
                    }else{
                        btn_addNewSanct_input.disabled = true;
                        submit_addNewSanctionsBtn.disabled = true;
                    }
                });
                // disable cancel and sibmit button on submit
                $(form_addNewSanctions).submit(function(){
                    cancel_addNewSanctionsBtn.disabled = true;
                    submit_addNewSanctionsBtn.disabled = true;
                    return true;
                });
                // initialize appending new input field
                var append_new_index = document.getElementById("append_new_index").value;
                var addedSanctInputFields_div = document.querySelector('.addedSanctInputFields_div');
                var newSanct_maxField = 10 - append_new_index;
                var newSanct_index = parseInt(append_new_index) + parseInt(1, 10);
                var x = 1;
                function addNewSanctIndexing(){
                    var n_i = newSanct_index;
                    $(".addNewSanctIndex").each(function(){
                        $(this).html('' + n_i + '.');
                        n_i++;
                    });
                }
                $(btn_addNewSanct_input).click(function(){
                    if(x <= newSanct_maxField){
                        x++;
                        var newInputField = '<div class="input-group mt-1 mb-2"> ' +
                                        '<div class="input-group-append"> ' +
                                            '<span class="input-group-text txt_iptgrp_append addNewSanctIndex font-weight-bold"></span> ' +
                                        '</div> ' +
                                        '<input type="text" name="new_sanctions[]" class="form-control input_grpInpt3v1" placeholder="Type New Sanction" aria-label="Type New Sanction" aria-describedby="added-new-sanctions-input" required /> ' +
                                        '<div class="input-group-append"> ' +
                                            '<button class="btn btn_svms_red btn_iptgrp_append btn_deleteAddedNewSanct_input m-0" id="btn_addNewSanct_input" type="button"><i class="nc-icon nc-simple-remove font-weight-bold" aria-hidden="true"></i></button> ' +
                                        '</div> ' +
                                    '</div>';
                        $(addedSanctInputFields_div).append(newInputField);
                    }
                    addNewSanctIndexing();
                });
                $(addedSanctInputFields_div).on('click', '.btn_deleteAddedNewSanct_input', function(e){
                    e.preventDefault();
                    $(this).closest('.input_grpInpt3v1').value = '';
                    $(this).closest('.input-group').last().remove();
                    x--;
                    addNewSanctIndexing();
                });
            // adding new sanctions form end

            // deleting sanctions form
                // selection of sanctions for deletion
                $("#sanctDeleteAll").change(function(){
                    if(this.checked){
                    $(".sanctDeleteSingle").each(function(){
                        this.checked=true;
                    })              
                    }else{
                    $(".sanctDeleteSingle").each(function(){
                        this.checked=false;
                    })              
                    }
                });
                $(".sanctDeleteSingle").click(function () {
                    if ($(this).is(":checked")){
                    var isDeleteAllChecked = 0;
                    $(".sanctDeleteSingle").each(function(){
                        if(!this.checked)
                        isDeleteAllChecked = 1;
                    })              
                    if(isDeleteAllChecked == 0){ $("#sanctDeleteAll").prop("checked", true); }     
                    }else {
                    $("#sanctDeleteAll").prop("checked", false);
                    }
                });
                // disable cancel and sibmit button on submit
                var form_deleteSanctions  = document.querySelector("#form_deleteSanctions");
                var submit_deleteSanctionsBtn = document.querySelector("#submit_deleteSanctionsBtn");
                var cancel_deleteSanctionsBtn = document.querySelector("#cancel_deleteSanctionsBtn");
                $(form_deleteSanctions).submit(function(){
                    cancel_deleteSanctionsBtn.disabled = true;
                    submit_deleteSanctionsBtn.disabled = true;
                    return true;
                });
            // deleting sanctions form end
        });
    </script>
{{-- edit sanctions on form modal end --}}

{{-- delete recorded violation --}}
    {{-- temporary deletion --}}
    <script>
        function deleteThisViolation(sel_viola_id){
            var sel_viola_id = sel_viola_id;
            var sel_stud_num = document.getElementById("vp_hidden_stud_num").value;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('violation_records.delete_violation_form') }}",
                method:"GET",
                data:{sel_viola_id:sel_viola_id, sel_stud_num:sel_stud_num, _token:_token},
                success: function(data){
                    $('#deleteViolationModalHtmlData').html(data); 
                    $('#deleteViolationModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#deleteViolationModal').on('show.bs.modal', function () {
            var form_deleteViolationRec  = document.querySelector("#form_deleteViolationRec");
            var reason_textareaInput  = document.querySelector("#delete_violation_reason");
            var btn_submitDeleteViolationRec = document.querySelector("#submit_deleteViolationRecBtn");
            var btn_cancelDeleteViolationRec = document.querySelector("#cancel_deleteViolationRecBtn");
            // disable add another input field and submit button if reason textarea is empty
            $(reason_textareaInput).keyup(function(){
                if(reason_textareaInput.value !== ""){
                    btn_submitDeleteViolationRec.disabled = false;
                }else{
                    btn_submitDeleteViolationRec.disabled = true;
                }
            });
            // disable cancel and sibmit button on submit
            $(form_deleteViolationRec).submit(function(){
                btn_cancelDeleteViolationRec.disabled = true;
                btn_submitDeleteViolationRec.disabled = true;
                return true;
            });
        });
    </script>
    {{-- permanent deletion --}}
    <script>
        function permanentDeleteThisViolation(sel_viola_id){
            var sel_viola_id = sel_viola_id;
            var sel_stud_num = document.getElementById("vp_hidden_stud_num").value;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('violation_records.permanently_delete_violation_form') }}",
                method:"GET",
                data:{sel_viola_id:sel_viola_id, sel_stud_num:sel_stud_num, _token:_token},
                success: function(data){
                    $('#permanentDeleteViolationModalHtmlData').html(data);
                    $('#permanentDeleteViolationModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#permanentDeleteViolationModal').on('show.bs.modal', function () {
            var form_permDeleteViolationRec  = document.querySelector("#form_permDeleteViolationRec");
            var btn_submitPermDeleteViolationRec = document.querySelector("#submit_permDeleteViolationRecBtn");
            var btn_cancelPermDeleteViolationRec = document.querySelector("#cancel_permDeleteViolationRecBtn");
            // disable cancel and sibmit button on submit
            $(form_permDeleteViolationRec).submit(function(){
                btn_cancelPermDeleteViolationRec.disabled = true;
                btn_submitPermDeleteViolationRec.disabled = true;
                return true;
            });
        });
    </script>
    {{-- delete all recorded vilation per month --}}
    <script>
        function delete_allMonthlyViolations(sel_monthly_viola, sel_yearly_viola){
            var sel_monthly_viola = sel_monthly_viola;
            var sel_yearly_viola = sel_yearly_viola;
            var months = [ "January", "February", "March", "April", "May", "June", 
                    "July", "August", "September", "October", "November", "December" ];

            var selectedMonthName = months[sel_monthly_viola - 1];
            var sel_stud_num = document.getElementById("vp_hidden_stud_num").value;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('violation_records.delete_all_monthly_violations_form') }}",
                method:"GET",
                data:{sel_yearly_viola:sel_yearly_viola, sel_monthly_viola:sel_monthly_viola, sel_stud_num:sel_stud_num, _token:_token},
                success: function(data){
                    $('#deleteAllMonthlyViolationModalHtmlData').html(data);
                    $('#deleteAllMonthlyViolationModal').modal('show');
                    $('#monthTxt_modalTitle').html(' '+selectedMonthName+' '+' '+sel_yearly_viola+' ');
                }
            });
        }
    </script>
    <script>
        $('#deleteAllMonthlyViolationModal').on('show.bs.modal', function () {
            var form_deleteAllViolationRec  = document.querySelector("#form_deleteAllViolationRec");
            var reason1_textareaInput  = document.querySelector("#delete_all_violation_reason");
            var btn_submitDeleteAllViolationRec = document.querySelector("#submit_deleteAllViolationRecBtn");
            var btn_cancelDeleteAllViolationRec = document.querySelector("#cancel_deleteAllViolationRecBtn");
            // disable /enable submit button
            function dis_en_btn_submitDeleteAllViolationRec(){
                var has_delViolMarkSingle = 0;
                $(".delViolMarkSingle").each(function(){
                    if(this.checked){
                        has_delViolMarkSingle = 1;
                    }
                });
                if(reason1_textareaInput.value !== "" && has_delViolMarkSingle != 0){
                    btn_submitDeleteAllViolationRec.disabled = false;
                }else{
                    btn_submitDeleteAllViolationRec.disabled = true;
                }
            }
            // selection of sanctions for deletion
            $("#delViolMarkAll").change(function(){
                if(this.checked){
                $(".delViolMarkSingle").each(function(){
                    this.checked=true;
                })              
                }else{
                $(".delViolMarkSingle").each(function(){
                    this.checked=false;
                })              
                }
                dis_en_btn_submitDeleteAllViolationRec();
            });
            $(".delViolMarkSingle").click(function () {
                if ($(this).is(":checked")){
                var isDeleteAllChecked = 0;
                $(".delViolMarkSingle").each(function(){
                    if(!this.checked)
                    isDeleteAllChecked = 1;
                })              
                if(isDeleteAllChecked == 0){ $("#delViolMarkAll").prop("checked", true); }     
                }else {
                $("#delViolMarkAll").prop("checked", false);
                }
                dis_en_btn_submitDeleteAllViolationRec();
            });
            // disable add submit button if reason textarea is empty
            $(reason1_textareaInput).keyup(function(){
                dis_en_btn_submitDeleteAllViolationRec();
            });
            // disable cancel and sibmit button on submit
            $(form_deleteAllViolationRec).submit(function(){
                btn_cancelDeleteAllViolationRec.disabled = true;
                btn_submitDeleteAllViolationRec.disabled = true;
                return true;
            });
        });
    </script>
    {{-- delete all recorded vilation per year --}}
    <script>
        function delete_allYearlyViolations(sel_yearly_viola){
            var sel_yearly_viola = sel_yearly_viola;
            var sel_stud_num = document.getElementById("vp_hidden_stud_num").value;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('violation_records.delete_all_yearly_violations_form') }}",
                method:"GET",
                data:{sel_yearly_viola:sel_yearly_viola, sel_stud_num:sel_stud_num, _token:_token},
                success: function(data){
                    $('#deleteAllYearlyViolationModalHtmlData').html(data);
                    $('#deleteAllYearlyViolationModal').modal('show');
                    $('#yearTxt_modalTitle').html(sel_yearly_viola);
                }
            });
        }
    </script>
    <script>
        $('#deleteAllYearlyViolationModal').on('show.bs.modal', function () {
            var form_deleteAllYearlyViolationRec  = document.querySelector("#form_deleteAllYearlyViolationRec");
            var reason2_textareaInput  = document.querySelector("#delete_all_yearly_violation_reason");
            var btn_submitDeleteAllYearlyViolationRec = document.querySelector("#submit_deleteAllYearlyViolationRecBtn");
            var btn_cancelDeleteAllYearlyViolationRec = document.querySelector("#cancel_deleteAllYearlyViolationRecBtn");
            // disable /enable submit button
            function dis_en_btn_submitDeleteAllYearlyViolationRec(){
                var has_delViolMarkSingleYear = 0;
                $(".delViolMarkSingleYear").each(function(){
                    if(this.checked){
                        has_delViolMarkSingleYear = 1;
                    }
                });
                if(reason2_textareaInput.value !== "" && has_delViolMarkSingleYear != 0){
                    btn_submitDeleteAllYearlyViolationRec.disabled = false;
                }else{
                    btn_submitDeleteAllYearlyViolationRec.disabled = true;
                }
            }
            // selection of sanctions for deletion
            $("#delViolMarkAllYear").change(function(){
                if(this.checked){
                $(".delViolMarkSingleYear").each(function(){
                    this.checked=true;
                })              
                }else{
                $(".delViolMarkSingleYear").each(function(){
                    this.checked=false;
                })              
                }
                dis_en_btn_submitDeleteAllYearlyViolationRec();
            });
            $(".delViolMarkSingleYear").click(function () {
                if ($(this).is(":checked")){
                var isDeleteAllChecked = 0;
                $(".delViolMarkSingleYear").each(function(){
                    if(!this.checked)
                    isDeleteAllChecked = 1;
                })              
                if(isDeleteAllChecked == 0){ $("#delViolMarkAllYear").prop("checked", true); }     
                }else {
                $("#delViolMarkAllYear").prop("checked", false);
                }
                dis_en_btn_submitDeleteAllYearlyViolationRec();
            });
            // disable add submit button if reason textarea is empty
            $(reason2_textareaInput).keyup(function(){
                dis_en_btn_submitDeleteAllYearlyViolationRec();
            });
            // disable cancel and sibmit button on submit
            $(form_deleteAllYearlyViolationRec).submit(function(){
                btn_cancelDeleteAllYearlyViolationRec.disabled = true;
                btn_submitDeleteAllYearlyViolationRec.disabled = true;
                return true;
            });
        });
    </script>
    {{-- permanent delete all deleted violations --}}
    <script>
        function permDelete_allDeletedViolations(del_viola_ids){
            var del_viola_ids = del_viola_ids;
            var sel_stud_num = document.getElementById("vp_hidden_stud_num").value;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('violation_records.permanent_delete_all_violations_form') }}",
                method:"GET",
                data:{del_viola_ids:del_viola_ids, sel_stud_num:sel_stud_num, _token:_token},
                success: function(data){
                    $('#permanentDeleteAllViolationsHtmlData').html(data);
                    $('#permanentDeleteAllViolations').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#permanentDeleteAllViolations').on('show.bs.modal', function () {
            var form_permDeleteAllViolationRec  = document.querySelector("#form_permDeleteAllViolationRec");
            var btn_submitPermDeleteAllViolationRec = document.querySelector("#submit_permDeleteAllViolationRecBtn");
            var btn_cancelPermDeleteAllViolationRec = document.querySelector("#cancel_permDeleteAllViolationRecBtn");
            // disable / enable sumbit button 
            function dis_en_btn_submitPermDeleteAllViolationRec(){
                var has_permDelViolMarkSingle = 0;
                $(".permDelViolMarkSingle").each(function(){
                    if(this.checked){
                        has_permDelViolMarkSingle = 1;
                    }
                });
                if(has_permDelViolMarkSingle == 0){
                    btn_submitPermDeleteAllViolationRec.disabled = true;
                }else{
                    btn_submitPermDeleteAllViolationRec.disabled = false;
                }
            }

            // selection of sanctions for deletion
            $("#permDelViolMarkAll").change(function(){
                if(this.checked){
                    $(".permDelViolMarkSingle").each(function(){
                        this.checked=true;
                    });              
                }else{
                    $(".permDelViolMarkSingle").each(function(){
                        this.checked=false;
                    });             
                }
                dis_en_btn_submitPermDeleteAllViolationRec();
            });
            $(".permDelViolMarkSingle").click(function () {
                if ($(this).is(":checked")){
                    var isDeleteAllChecked = 0;
                    $(".permDelViolMarkSingle").each(function(){
                        if(!this.checked)
                        isDeleteAllChecked = 1;
                    })              
                    if(isDeleteAllChecked == 0){ $("#permDelViolMarkAll").prop("checked", true); }     
                }else {
                    $("#permDelViolMarkAll").prop("checked", false);
                }
                dis_en_btn_submitPermDeleteAllViolationRec();
            });

            // disable cancel and sibmit button on submit
            $(form_permDeleteAllViolationRec).submit(function(){
                btn_cancelPermDeleteAllViolationRec.disabled = true;
                btn_submitPermDeleteAllViolationRec.disabled = true;
                return true;
            });
        });
    </script>
{{-- permanent delete all deleted violations end --}}

{{-- recover deleted violation --}}
    {{-- single recovery --}}
    <script>
        function recoverThisDeletedViolation(sel_viola_id){
            var sel_viola_id = sel_viola_id;
            var sel_stud_num = document.getElementById("vp_hidden_stud_num").value;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('violation_records.recover_deleted_violation_form') }}",
                method:"GET",
                data:{sel_viola_id:sel_viola_id, sel_stud_num:sel_stud_num, _token:_token},
                success: function(data){
                    $('#recoverDeletedViolationModalHtmlData').html(data);
                    $('#recoverDeletedViolationModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#recoverDeletedViolationModal').on('show.bs.modal', function () {
            var form_recoverDeletedViolationRec  = document.querySelector("#form_recoverDeletedViolationRec");
            var btn_submitRecoverDeletedViolationRec = document.querySelector("#submit_recoverDeletedViolationRecBtn");
            var btn_cancelRecoverDeletedViolationRec = document.querySelector("#cancel_recoverDeletedViolationRecBtn");
            // disable cancel and sibmit button on submit
            $(form_recoverDeletedViolationRec).submit(function(){
                btn_cancelRecoverDeletedViolationRec.disabled = true;
                btn_submitRecoverDeletedViolationRec.disabled = true;
                return true;
            });
        });
    </script>
    {{-- multiple recovery --}}
    <script>
        function recover_allDeletedViolations(recover_viola_ids){
            var recover_viola_ids = recover_viola_ids;
            var sel_stud_num = document.getElementById("vp_hidden_stud_num").value;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('violation_records.recover_all_deleted_violation_form') }}",
                method:"GET",
                data:{recover_viola_ids:recover_viola_ids, sel_stud_num:sel_stud_num, _token:_token},
                success: function(data){
                    $('#recoverAllDeletedViolationModalHtmlData').html(data);
                    $('#recoverAllDeletedViolationModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#recoverAllDeletedViolationModal').on('show.bs.modal', function () {
            var form_recoverAllDeletedViolationRec  = document.querySelector("#form_recoverAllDeletedViolationRec");
            var btn_submitRecoverAllDeletedViolationRec = document.querySelector("#submit_recoverAllDeletedViolationRecBtn");
            var btn_cancelRecoverAllDeletedViolationRec = document.querySelector("#cancel_recoverAllDeletedViolationRecBtn");
            // disable / enable sumbit button 
            function dis_en_btn_submitRecoverAllDeletedViolationRec(){
                var has_recoverAllDelViolMarkSingle = 0;
                $(".recoverDelViolMarkSingle").each(function(){
                    if(this.checked){
                        has_recoverAllDelViolMarkSingle = 1;
                    }
                });
                if(has_recoverAllDelViolMarkSingle == 0){
                    btn_submitRecoverAllDeletedViolationRec.disabled = true;
                }else{
                    btn_submitRecoverAllDeletedViolationRec.disabled = false;
                }
            }

            // selection of sanctions for deletion
            $("#recoverDelViolMarkAll").change(function(){
                if(this.checked){
                    $(".recoverDelViolMarkSingle").each(function(){
                        this.checked=true;
                    });              
                }else{
                    $(".recoverDelViolMarkSingle").each(function(){
                        this.checked=false;
                    });             
                }
                dis_en_btn_submitRecoverAllDeletedViolationRec();
            });
            $(".recoverDelViolMarkSingle").click(function () {
                if ($(this).is(":checked")){
                    var isRecoverAllChecked = 0;
                    $(".recoverDelViolMarkSingle").each(function(){
                        if(!this.checked)
                        isRecoverAllChecked = 1;
                    })              
                    if(isRecoverAllChecked == 0){ $("#recoverDelViolMarkAll").prop("checked", true); }     
                }else {
                    $("#recoverDelViolMarkAll").prop("checked", false);
                }
                dis_en_btn_submitRecoverAllDeletedViolationRec();
            });

            // disable cancel and sibmit button on submit
            $(form_recoverAllDeletedViolationRec).submit(function(){
                btn_cancelRecoverAllDeletedViolationRec.disabled = true;
                btn_submitRecoverAllDeletedViolationRec.disabled = true;
                return true;
            });
        });
    </script>
{{-- recover deleted violation end --}}

{{-- adding sanctions to all violations per month --}}
    <script>
        function addSanctions_allMonthlyViolations(sel_monthly_viola){
            alert(sel_monthly_viola);
        }
    </script>
{{-- adding sanctions to all violations per month end --}}

@endpush