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
            $yearly_offenses = App\Models\Violations::select('recorded_at')
                                                    ->where('stud_num', $violator_info->Student_Number)
                                                    ->orderBy('recorded_at', 'DESC')
                                                    ->get()
                                                    ->groupBy(function($date) {
                                                        return Carbon\Carbon::parse($date->recorded_at)->format('Y');
                                                    });
        @endphp
        @if(count($yearly_offenses) > 0)
            <div class="col-lg-9 col-md-8 col-sm-12">
            @foreach($yearly_offenses as $this_year_offenses)
            {{$this_year_offenses}}
                <div class="accordion" id="wowOffensesCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="wowOffensesCollapseHeading">
                            <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#wowOffensesCollapseDiv" aria-expanded="true" aria-controls="wowOffensesCollapseDiv">
                                <div>
                                    <span class="card_body_title">{{$this_year_offenses}} Offenses</span>
                                    <span class="card_body_subtitle">Offenses</span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="wowOffensesCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="wowOffensesCollapseHeading" data-parent="#wowOffensesCollapseParent">
                            
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        @endif
    {{-- offenses end --}}
    </div>
    </div>

    {{-- modals --}}

@endsection

@push('scripts')

@endpush