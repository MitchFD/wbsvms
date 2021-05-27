@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'offenses'
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

    @if(auth()->user()->user_status == 'active')
        @php
            $get_user_role_info = App\Models\Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
            $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        @endphp
        @if(in_array('offenses', $get_uRole_access))
            {{-- directory link --}}
            <div class="row mb-3">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <a href="{{ route('offenses.index', 'offenses') }}" class="directory_active_link">Offenses </a>
                </div>
            </div>

            {{-- card intro --}}
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card card_gbr shadow">
                        <div class="card-body card_intro">
                            <div class="page_intro">
                                <span class="page_intro_title">Offenses</span>
                                <span class="page_intro_subtitle">Create Custom Offenses as default options to ease your task for typing "Other Offenses" to record violations.
                                    You will be able to Add new Offense Details, Edit Custom Offense/s, and/or Delete the custom Offense/s 
                                    you have registered.</span>
                            </div>
                            <div class="page_illustration">
                                <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/offenses_illustration.svg') }}" alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Offense Categories Cards --}}
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card card_gbr card_ofh shadow-none cb_x15y25 card_body_bg_gray">
                    <div class="accordion gCardAccordions" id="minorOffensesDisplayCollapseParent">
                        <div class="card-header p-0 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="card_body_title">Minor Offenses</span>
                                <span class="card_body_subtitle">10 Minor Offenses</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <button class="btn cust_btn_smcircle5v2 mr-2" data-toggle="tooltip" data-placement="top" title="Create New System Role??"><i class="nc-icon nc-simple-add" aria-hidden="true"></i></button>
                                <button class="btn cust_btn_smcircle5v2 acc_collapse_cards" data-toggle="collapse" data-target="#minorOffensesDisplayCollapseDiv" aria-expanded="true" aria-controls="minorOffensesDisplayCollapseDiv"><i class="nc-icon nc-minimal-up" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        <div id="minorOffensesDisplayCollapseDiv" class="collapse gCardAccordions_collapse show" aria-labelledby="userStatusDisplayCollapseHeading" data-parent="#minorOffensesDisplayCollapseParent">
                            <div class="card card_gbr card_ofh shadow cb_p15 mt-3 mb-1">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card-body lightBlue_cardBody mb-2">
                                            <span class="lightBlue_cardBody_blueTitle mb-1"><i class="fa fa-info-circle cust_info_icon mr-1" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Default Minor Offenses are not allowed to edited or deleted from the system."></i> Default Minor Offenses:</span>
                                            <span class="lightBlue_cardBody_list"><span class="font-weight-bold mr-1">1. </span> Violation of Dress Code</span>
                                            <span class="lightBlue_cardBody_list"><span class="font-weight-bold mr-1">1. </span> Not Wearing Prescribed Uniform</span>
                                            <span class="lightBlue_cardBody_list"><span class="font-weight-bold mr-1">1. </span> not Wearing ID</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card-body lightRed_cardBody">
                                            <span class="lightRed_cardBody_redTitle mb-1"><i class="fa fa-info-circle cust_info_icon mr-1" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Custom Minor Offenses can be edited or deleted from the system."></i> Custom Minor Offenses:</span>
                                            <span class="lightRed_cardBody_list cursor_pointer" onclick="editOffenseDetails()"><span class="font-weight-bold mr-1">1. </span> Violation of Dress Code</span>
                                            <span class="lightRed_cardBody_list cursor_pointer" onclick="editOffenseDetails()"><span class="font-weight-bold mr-1">1. </span> Not Wearing Prescribed Uniform</span>
                                            <span class="lightRed_cardBody_list cursor_pointer" onclick="editOffenseDetails()"><span class="font-weight-bold mr-1">1. </span> not Wearing ID</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer align-items-center px-0 pb-0">
                                <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-list-ul mr-1" aria-hidden="true"></i> 10 Default Minor Offenses </span>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @else

        @endif
    @else
        
    @endif
    </div>

    {{-- modals --}}

@endsection

@push('scripts')

{{-- edit offense details --}}
    <script>
        function editOffenseDetails(){
            alert('wow');
        }
    </script>
{{-- edit offense details end--}}

@endpush