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
            <div class="accordion" id="violatorProfileCollapseParent">
                <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                    <div class="card-header p-0" id="violatorProfileCollapseHeading">
                        <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#violatorProfileCollapseDiv" aria-expanded="true" aria-controls="violatorProfileCollapseDiv">
                            <div>
                                <span class="card_body_title">Violator's Profile</span>
                                <span class="card_body_subtitle">{{$total_offenses}} Offenses</span>
                            </div>
                            <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                        </button>
                    </div>
                    <div id="violatorProfileCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="violatorProfileCollapseHeading" data-parent="#violatorProfileCollapseParent">
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
                                        <div class="col-lg-8 col-md-10 col-sm-11 p-0 d-flex justify-content-center">
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
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                @if($total_cleared_off > 0)
                                    <span class="cust_info_txtwicon"><i class="fa fa-check-square-o mr-1" aria-hidden="true"></i> {{$total_cleared_off }} Cleared Offenses.</span>  
                                @endif
                                @if($total_notCleared_off > 0)
                                    <span class="cust_info_txtwicon"><i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> {{$total_notCleared_off }} Uncleared Offenses.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- violator's profile card end --}}
    {{-- offenses --}}
        @php
            $yearly_offenses = App\Models\Violations::selectRaw('year(recorded_at) year')
                                                    ->where('stud_num', $violator_info->Student_Number)
                                                    ->groupBy('year')
                                                    ->orderBy('year', 'desc')
                                                    ->get();
        @endphp
        @if(count($yearly_offenses) > 0)
            <div class="col-lg-9 col-md-8 col-sm-12">
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
                                @endphp
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" id="{{$this_yearVal_t}}NavTab" data-toggle="tab" href="#{{$this_yearVal_t}}TabPanel" role="tab" aria-controls="{{$this_yearVal_t}}TabPanel" aria-selected="true">
                                        {{$this_yearVal_t }} 
                                        <span class="badge cust_badge_red2 ml-3" data-toggle="tooltip" data-placement="top" title="{{$yearly_totalOffenses }} Recorded Offenses Found for the Year {{ $this_yearVal_t}}.">
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
                                @endphp
                                <div class="tab-pane card_body_bg_gray card_bbr cb_t20b0x25 fade" id="{{$this_yearVal_tc}}TabPanel" role="tabpanel" aria-labelledby="{{$this_yearVal_tc}}NavTab">
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
                                                    @endphp 
                                                    <li class="nav-item">
                                                        <a class="nav-link d-flex align-items-center" id="{{$yearly_monthlyVal_t}}NavTab" data-toggle="tab" href="#{{$yearly_monthlyVal_t}}TabPanel" role="tab" aria-controls="{{$yearly_monthlyVal_t}}TabPanel" aria-selected="true">
                                                            {{ $monthName }} 
                                                            <span class="badge cust_badge_red3 ml-3" data-toggle="tooltip" data-placement="top" title="{{$monthly_totalOffenses}} Recorded Offenses Found for month of {{ ucwords($monthName) }} year 2021.">
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
                                                    <div class="tab-pane card_body_bg_gray2 card_bbr card_ofh cb_t20b20x25 fade" style="margin-bottom: -25px;" id="{{$yearly_monthlyVal_tc}}TabPanel" role="tabpanel" aria-labelledby="{{$yearly_monthlyVal_tc}}NavTab">
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
                                                                    // plural offense count
                                                                    if($date_offense->offense_count > 1){
                                                                        $oC_s = 's';
                                                                    }else{
                                                                        $oC_s = '';
                                                                    }
                                                                    // responsible user
                                                                    if($date_offense->respo_user_id == auth()->user()->id){
                                                                        $recBy = 'Recorded by you.';
                                                                    }else{
                                                                        $get_recBy_info = App\Models\Users::select('id', 'user_role', 'user_lname')
                                                                                                ->where('id', $date_offense->respo_user_id)
                                                                                                ->first();
                                                                        $recBy = ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_lname;
                                                                    }
                                                                    // cleared/uncleared classes
                                                                    if($date_offense->violation_status === 'cleared'){
                                                                        $light_cardBody       = 'lightGreen_cardBody';
                                                                        $light_cardBody_title = 'lightGreen_cardBody_greenTitle';
                                                                        $light_cardBody_list  = 'lightGreen_cardBody_list';
                                                                        $info_textClass       = 'cust_info_txtwicon4';
                                                                        $info_iconClass       = 'fa fa-check-square-o';
                                                                    }else{
                                                                        $light_cardBody       = 'lightRed_cardBody';
                                                                        $light_cardBody_title = 'lightRed_cardBody_redTitle';
                                                                        $light_cardBody_list  = 'lightRed_cardBody_list';
                                                                        $info_textClass       = 'cust_info_txtwicon3';
                                                                        $info_iconClass       = 'fa fa-exclamation-circle';
                                                                    }
                                                                @endphp
                                                                <div class="col-lg-4 col-md-5 col-sm-12 pt-4">
                                                                    <div class="accordion shadow cust_accordion_div" id="v{{$date_offense->viola_id}}Accordion_Parent">
                                                                        <div class="card custom_accordion_card">
                                                                            <div class="card-header p-0" id="changeUserRoleCollapse_heading">
                                                                                <h2 class="mb-0">
                                                                                    <button class="btn btn-block custom2_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#v{{$date_offense->viola_id}}Collapse_Div" aria-expanded="true" aria-controls="v{{$date_offense->viola_id}}Collapse_Div">
                                                                                        <div class="d-flex justify-content-start align-items-center">
                                                                                            <div class="information_div2">
                                                                                                <span class="li_info_title">{{date('F j, Y', strtotime($date_offense->recorded_at))}}</span>
                                                                                                <span class="li_info_subtitle">{{date('l - g:i A', strtotime($date_offense->recorded_at))}}</span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <i class="nc-icon nc-minimal-up"></i>
                                                                                    </button>
                                                                                </h2>
                                                                            </div>
                                                                            <div id="v{{$date_offense->viola_id}}Collapse_Div" class="collapse show cust_collapse_active cb_t0b12y15" aria-labelledby="v{{$date_offense->viola_id}}Collapse_heading" data-parent="#v{{$date_offense->viola_id}}Accordion_Parent">
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
                                                                                                                            ->orderBy('created_at', 'desc')
                                                                                                                            ->offset(0)
                                                                                                                            ->limit($date_offense->has_sanct_count)
                                                                                                                            ->get();
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
                                                                                <div class="row mt-3">
                                                                                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex align-items-center justify-content-between">
                                                                                        <div>
                                                                                            <span class="{{$info_textClass }} font-weight-bold"><i class="{{$info_iconClass }} mr-1" aria-hidden="true"></i> {{$date_offense->offense_count}} Offense{{$oC_s}}</span>  
                                                                                            <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> {{ $recBy }}</span>  
                                                                                        </div>
                                                                                        <button class="btn cust_btn_smcircle2" data-toggle="tooltip" data-placement="top" title="Delete recorded Offenses?"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
                                                                    <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> {{ $monthly_totalOffenses }} Total Offense{{$tO_s}} for {{ $monthName }} 2021.</span>  
                                                                    @if($monthly_totalUnclearOff > 0)
                                                                        <span class="cust_info_txtwicon"><i class="fa fa-square-o mr-1" aria-hidden="true"></i> {{ $monthly_totalUnclearOff}} Uncleared Offense{{$tUO_s}}.</span> 
                                                                    @endif
                                                                    @if($monthly_totalClearedOff > 0)
                                                                        <span class="cust_info_txtwicon"><i class="fa fa-check-square-o mr-1" aria-hidden="true"></i> {{ $monthly_totalClearedOff }} Cleared Offense{{$tCO_s}}.</span> 
                                                                    @endif
                                                                </div>
                                                                <button class="btn cust_btn_smcircle5" data-toggle="tooltip" data-placement="top" title="Delete all recorded Offenses for the Month of {{ $monthName }} 2021?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                            </div> 
                                                        </div>
                                                    </div> 
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    {{-- offenses end --}}
    </div>
    </div>

    {{-- modals --}}
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
                        <span class="modal-title cust_modal_title" id="editSanctionsModalLabel">Edit Sanction?</span>
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
            const addSanctions_input  = document.querySelector("#addSanctions_input");
            const btn_addAnother_input = document.querySelector("#btn_addAnother_input");
            $(addSanctions_input).keyup(function(){
                if(addSanctions_input.value !== ""){
                    btn_addAnother_input.disabled = false;
                }else{
                    btn_addAnother_input.disabled = true;
                }
            });
        });
    </script>
    {{-- adding new field button --}}
    <script>
        $('#addSanctionsModal').on('show.bs.modal', function () {
            var maxField = 10;
            var btn_addAnother_input = document.querySelector("#btn_addAnother_input");
            var addedInputFields_div = document.querySelector('.addedInputFields_div');
            var newInputField = '<div class="input-group mb-2">' +
                                    '<input type="text" name="sanctions[]" class="form-control input_grpInpt3" placeholder="Type Sanction" aria-label="Type Sanction" aria-describedby="add-sanctions-input"> ' +
                                    '<div class="input-group-append"> ' +
                                        '<button class="btn btn-success m-0 btn_deleteAddedSanction_input" type="button"><i class="nc-icon nc-simple-remove font-weight-bold" aria-hidden="true"></i></button> ' +
                                    '</div> ' +
                                '</div>';
            var x = 1;
            $(btn_addAnother_input).click(function(){
                if(x < maxField){
                    x++;
                    $(addedInputFields_div).append(newInputField);
                }
            });
            $(addedInputFields_div).on('click', '.btn_deleteAddedSanction_input', function(e){
                e.preventDefault();
                $(this).closest('.input_grpInpt3').value = '';
                $(this).closest('.input-group').last().remove();
                x--;
            });
        });
    </script>
    {{-- disable/enable save button --}}
    <script>
        $('#addSanctionsModal').on('show.bs.modal', function () {
            $('#form_addSanctions').each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#submit_addSanctionsBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
            }).find('#submit_addSanctionsBtn').prop('disabled', true);
        });
    </script>
{{-- adding new input field for adding sanctions on modal end --}}

{{-- edit sanctions on form modal --}}
    {{-- initialize nav-pills --}}
    <script>
        $('#editSanctionsModal').on('show.bs.modal', function () {
            $('#editSanctionPills_tabParent a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
            })
        });
    </script>
    {{-- disable/enable save button --}}
    <script>
        $('#editSanctionsModal').on('show.bs.modal', function () {
            $('#form_editSanctions').each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#submit_editSanctionsBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
            }).find('#submit_editSanctionsBtn').prop('disabled', true);
        });
    </script>
    {{-- adding new sanction input field append --}}
    <script>
        $('#editSanctionsModal').on('show.bs.modal', function () {
            const addNewSanction_input  = document.querySelector("#addNewSanction_input");
            const btn_addNewSanct_input = document.querySelector("#btn_addNewSanct_input");
            $(addNewSanction_input).keyup(function(){
                if(addNewSanction_input.value !== ""){
                    btn_addNewSanct_input.disabled = false;
                }else{
                    btn_addNewSanct_input.disabled = true;
                }
            });
        });
    </script>
    <script>
        $('#editSanctionsModal').on('show.bs.modal', function () {
            var btn_addNewSanct_input = document.querySelector("#btn_addNewSanct_input");
            var total_sanct_count = document.getElementById("total_sanct_count").value;
            var addedSanctInputFields_div = document.querySelector('.addedSanctInputFields_div');
            var newSanct_maxField = 10 - total_sanct_count;
            var x = 1;
            var addedSanct_count = total_sanct_count + 2 - 10;
            $(btn_addNewSanct_input).click(function(){
                if(x < newSanct_maxField){
                    x++;
                    addedSanct_count++;
                    var s_addedSanct_count = String(addedSanct_count);
                    var newInputField = '<div class="input-group mt-1 mb-2"> ' +
                                    '<div class="input-group-append"> ' +
                                        '<span class="input-group-text txt_iptgrp_append font-weight-bold">'+ (s_addedSanct_count) +'. </span> ' +
                                    '</div> ' +
                                    '<input type="text" id="addNewSanction_input" name="new_sanctions[]" class="form-control input_grpInpt3v1" placeholder="Type New Sanction" aria-label="Type New Sanction" aria-describedby="add-new-sanctions-input"> ' +
                                    '<div class="input-group-append"> ' +
                                        '<button class="btn btn-success btn_iptgrp_append btn_deleteAddedNewSanct_input m-0" id="btn_addNewSanct_input" type="button"><i class="nc-icon nc-simple-remove font-weight-bold" aria-hidden="true"></i></button> ' +
                                    '</div> ' +
                                '</div>';
                    $(addedSanctInputFields_div).append(newInputField);
                    console.log(newSanct_maxField);
                    console.log(addedSanct_count);
                    console.log(s_addedSanct_count);
                }
            });
            $(addedSanctInputFields_div).on('click', '.btn_deleteAddedNewSanct_input', function(e){
                e.preventDefault();
                $(this).closest('.input_grpInpt3v1').value = '';
                $(this).closest('.input-group').last().remove();
                x--;
                addedSanct_count--;
            });
        });
    </script>
{{-- edit sanctions on form modal end --}}

@endpush