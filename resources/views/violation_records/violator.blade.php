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
                <a href="{{ route('user_management.users_logs', 'users_logs') }}" class="directory_active_link">Violator <span class="directory_divider"> / </span> {{ $violator_info->Last_Name }}</a>
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
                $no_vr_imgFltr = 'up_red_user_image';
            }else{
                $no_vr_imgFltr = 'up_stud_user_image';
            }
        @endphp
        {{-- custom values end --}}
        <div class="col-lg-4 col-md-5 col-sm-12">
            <div class="accordion" id="violatorProfileCollapseParent">
                <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                    <div class="card-header p-0" id="violatorProfileCollapseHeading">
                        <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#violatorProfileCollapseDiv" aria-expanded="true" aria-controls="violatorProfileCollapseDiv">
                            <div>
                                <span class="card_body_title">Violator's Information</span>
                                <span class="card_body_subtitle">14 Offenses</span>
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
                                            src="{{asset('storage/svms/sdca_images/registered_students_imgs/default_students_img.jpg')}}" alt="default violator's profile image"
                                        @endif
                                        >
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- violator's profile card end --}}
    </div>
    </div>

    {{-- modals --}}

@endsection

@push('scripts')

@endpush