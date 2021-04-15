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
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="accordion" id="violatorsCountDashboardCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="violatorsCountDashboardCollapseHeading">
                            <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#violatorsCountDashboardCollapseDiv" aria-expanded="true" aria-controls="violatorsCountDashboardCollapseDiv">
                                <div>
                                    <span class="card_body_title">Violators count per School</span>
                                    {{-- <span class="card_body_subtitle">View statistical graph of violators per schools.</span> --}}
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
        </div>

        {{-- table data --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="accordion" id="vilationRecordsTableCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="vilationRecordsTableCollapseHeading">
                            <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#vilationRecordsTableCollapseDiv" aria-expanded="true" aria-controls="vilationRecordsTableCollapseDiv">
                                <div>
                                    <span class="card_body_title">Recorded Offenses</span>
                                    <span class="card_body_subtitle">Filter Table to view desired outputs.</span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="vilationRecordsTableCollapseDiv" class="collapse show cb_t0b15x25" aria-labelledby="vilationRecordsTableCollapseHeading" data-parent="#vilationRecordsTableCollapseParent">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    
                                </div>
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