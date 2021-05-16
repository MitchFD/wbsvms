@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'sanctions'
])

@section('content')
    <div class="content">
    @if(auth()->user()->user_status == 'active')
        @php
            $get_user_role_info = App\Models\Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
            $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        @endphp
        @if(in_array('sanctions', $get_uRole_access))
            {{-- directory link --}}
            <div class="row mb-3">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <a href="{{ route('sanctions.index', 'sanctions') }}" class="directory_active_link">Sanctions </a>
                </div>
            </div>

            {{-- card intro --}}
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card card_gbr shadow">
                        <div class="card-body card_intro">
                            <div class="page_intro">
                                <span class="page_intro_title">Sanctions</span>
                                <span class="page_intro_subtitle">Create Sanctions as default options to ease your task for adding corresponding sanctions to recorded violations. You will be able to Add new Sanctions, Edit Existing Sanctions, and/or delete created sanctions.</span>
                            </div>
                            <div class="page_illustration">
                                <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/violation_records_illustration.svg') }}" alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- cards --}}
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="accordion gCardAccordions" id="createdSanctionsCollapseParent">
                                <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                                    <div class="card-header p-0" id="createdSanctionsCollapseHeading">
                                        <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#createdSanctionsCollapseDiv" aria-expanded="true" aria-controls="createdSanctionsCollapseDiv">
                                            <div>
                                                <span class="card_body_title">Created Sanctions</span>
                                                <span class="card_body_subtitle">Select Sanciton/s for Edit and Delete.</span>
                                            </div>
                                            <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                                        </button>
                                    </div>
                                    <div id="createdSanctionsCollapseDiv" class="collapse gCardAccordions_collapse show cb_t0b15x25" aria-labelledby="createdSanctionsCollapseHeading" data-parent="#createdSanctionsCollapseParent">
                                        <div class="row mb-3">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="card card_gbr card_ofh shadow-none p-0 m-0 card_body_bg_gray2">
                                                    @csrf
                                                    <div class="card-body">
                                                        <div class="row mb-1">
                                                            <div class="col-lg-5 col-md-5 col-sm-12">
                                                                <div class="input-group cust_srchInpt_div">
                                                                    <input id="search_sanctions" name="search_sanctions" type="text" class="form-control cust_srchUsersInpt_box" placeholder="Search Sanction..." />
                                                                    <i class="nc-icon nc-zoom-split" aria-hidden="true"></i>    
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-12 d-flex justify-content-end align-items-end">
                                                                <button type="button" id="editSelectedSanctions_btn" class="btn btn-success cust_bt_links shadow ml-1" disabled data-toggle="tooltip" data-placement="top" title="Edit Selected Sanctions?"><i class="fa fa-pencil-square-o mr-1" aria-hidden="true"></i> Edit</button>
                                                                <button type="button" id="deleteSelectedSanctions_btn" class="btn btn_svms_red cust_bt_links shadow ml-1" disabled data-toggle="tooltip" data-placement="top" title="Delete Selected Sanctions?"><i class="fa fa-trash mr-1" aria-hidden="true"></i> Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <table class="table table-hover cust_table shadow">
                                                    <thead class="thead_svms_blue">
                                                        <tr>
                                                            <th class="pl12"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></th>
                                                            <th class="pl12">#</th>
                                                            <th>Sanction</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody_svms_white" id="sys_users_tbl">
                                                        {{-- ajax data table --}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center align-items-center">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <span>Total Data: <span class="font-weight-bold" id="total_data_count"> </span> </span>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-end">
                                                {{-- <a href="{{ route('user_management.create_users', 'create_users') }}" class="btn btn-success cust_bt_links shadow" role="button"><i class="nc-icon nc-simple-add mr-1" aria-hidden="true"></i> Create New User</a> --}}
                                                @csrf
                                                <input type="hidden" name="su_hidden_page" id="su_hidden_page" value="1" />
                                                <div id="su_tablePagination">
    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="card-footer cb_t0b15x25">
                                        bottom line
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <span class="cust_info_txtwicon font-weight-bold"><i class="nc-icon nc-circle-10 mr-1" aria-hidden="true"></i> {{ $count_registered_users }} Registered @if($count_registered_users > 1) Users @else User @endif found.</span>
                                                @if($count_active_users > 0)
                                                    <span class="cust_info_txtwicon"><i class="fa fa-toggle-on mr-1" aria-hidden="true"></i> {{ $count_active_users }} Active @if($count_active_users > 1) Users @else User @endif found.</span>
                                                @endif
                                                @if($count_deactivated_users > 0)
                                                    <span class="cust_info_txtwicon"><i class="fa fa-toggle-off mr-1" aria-hidden="true"></i> {{ $count_deactivated_users }} Deactivated @if($count_deactivated_users > 1) Users @else User @endif found.</span>
                                                @endif
                                                @if($count_pending_users > 0)
                                                    <span class="cust_info_txtwicon"><i class="fa fa-clock-o mr-1" aria-hidden="true"></i> {{ $count_pending_users }} Pending @if($count_pending_users > 1) Users @else User @endif found.</span>
                                                @endif
                                                @if($count_deleted_users > 0)
                                                    <span class="cust_info_txtwicon"><i class="fa fa-trash mr-1" aria-hidden="true"></i> {{ $count_deleted_users }} Temporarily Deleted @if($count_deleted_users > 1) Users @else User @endif found.</span>
                                                @endif
                                            </div>
                                        </div>
                                        bottom line end
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="accordion gCardAccordions" id="createNewSanctionsCollapseParent">
                        <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                            <div class="card-header p-0" id="createNewSanctionsCollapseHeading">
                                <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#createNewSanctionsCollapseDiv" aria-expanded="true" aria-controls="createNewSanctionsCollapseDiv">
                                    <div>
                                        <span class="card_body_title">Sanction Registration</span>
                                        <span class="card_body_subtitle">Create New Sanction/s Form</span>
                                    </div>
                                    <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                                </button>
                            </div>
                            <div id="createNewSanctionsCollapseDiv" class="collapse gCardAccordions_collapse show cb_t0b15x25" aria-labelledby="createNewSanctionsCollapseHeading" data-parent="#createNewSanctionsCollapseParent">
                                <div class="card card_gbr shadow">
                                    <div class="card-body cb_p15x25">
                                        <form id="form_registerNewSanctions" action="{{route('user_management.create_new_system_role')}}" class="createSystemRoleForm" method="POST">
                                            @csrf
                                            <span class="lightGreen_cardBody_greenTitle">Type Sanctions:</span>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <span class="input-group-text txt_iptgrp_append font-weight-bold">1. </span>
                                                </div>
                                                <input type="text" id="newSanction_firstField_input" name="register_new_sanctions[]" class="form-control input_grpInpt3" placeholder="Type New Sanction" aria-label="Type New Sanction" aria-describedby="other-offenses-input">
                                                <div class="input-group-append">
                                                    <button class="btn btn-success m-0" id="btn_addAnother_input" type="button" disabled><i class="nc-icon nc-simple-add font-weight-bold" aria-hidden="true"></i></button>
                                                </div>
                                            </div>
                                            <div class="addedInputFields_div">
            
                                            </div>
                                            
                                            <div class="row d-flex justify-content-center mt-2">
                                                <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                                    <input type="hidden" name="respo_user_id" value="{{auth()->user()->id}}">
                                                    <input type="hidden" name="respo_user_lname" value="{{auth()->user()->user_lname}}">
                                                    <input type="hidden" name="respo_user_fname" value="{{auth()->user()->user_fname}}">

                                                    <button type="submit" id="save_NewSanctions_btn" class="btn btn_svms_blue btn-round btn_show_icon" disabled>{{ __('Register New Sanction/s') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
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
@endsection

@push('scripts')
    {{-- Sanction Registration form --}}
        <script>
            // adding new input for Other Offenses
                // disable/enable add button
                var NewSanction_firstField_input  = document.querySelector("#newSanction_firstField_input");
                var SanctionsAdd_Btn = document.querySelector("#btn_addAnother_input");
                var form_registerNewSanctions  = document.querySelector("#form_registerNewSanctions");
                var save_NewSanctions_btn = document.querySelector("#save_NewSanctions_btn");
                var cancel_violationForm_btn = document.querySelector("#cancel_violationForm_btn");
                var addedOtherSanctions_field = $('.addedOtherSanctions_field').filter(function() {
                    return this.value != '';
                });
                $(NewSanction_firstField_input).keyup(function(){
                    if(NewSanction_firstField_input.value !== ""){
                        SanctionsAdd_Btn.disabled = false;
                        save_NewSanctions_btn.disabled = false;
                    }else{
                        if (addedOtherSanctions_field.length == 0) {
                            save_NewSanctions_btn.disabled = true;
                        }else{
                            save_NewSanctions_btn.disabled = false;
                        }
                        SanctionsAdd_Btn.disabled = true;
                    }
                });
                // appending new input field
                    // appending new input field
                    function addOtherSanctionIndexing(){
                        i = 1;
                        $(".addOtherSanctionIndex").each(function(){
                            $(this).html(i+1 + '.');
                            i++;
                        });
                    }
                    var maxField = 10;
                    var addedInputFields_div = document.querySelector('.addedInputFields_div');
                    var newInputField = '<div class="input-group mb-2">' +
                                            '<div class="input-group-append"> ' +
                                                '<span class="input-group-text txt_iptgrp_append addOtherSanctionIndex font-weight-bold">1. </span> ' +
                                            '</div> ' +
                                            '<input type="text" name="register_new_sanctions[]" class="form-control input_grpInpt3 addedOtherSanctions_field" placeholder="Type New Sanction" aria-label="Type New Sanction" aria-describedby="other-offenses-input" required /> ' +
                                            '<div class="input-group-append"> ' +
                                                '<button class="btn btn_svms_blue m-0 btn_deleteAnother_input" type="button"><i class="nc-icon nc-simple-remove font-weight-bold" aria-hidden="true"></i></button> ' +
                                            '</div> ' +
                                        '</div>';
                    var x = 1;
                    $(SanctionsAdd_Btn).click(function(){
                        if(x < maxField){
                            x++;
                            $(addedInputFields_div).append(newInputField);
                            // console.log(x);
                        }
                        addOtherSanctionIndexing();
                    });
                    $(addedInputFields_div).on('click', '.btn_deleteAnother_input', function(e){
                        e.preventDefault();
                        $(this).closest('.input_grpInpt3').value = '';
                        $(this).closest('.input-group').last().remove();
                        x--;
                        // console.log('click');
                        addOtherSanctionIndexing();
                    });
            // disable cancel and sibmit button on submit
                $(form_registerNewSanctions).submit(function(){
                    cancel_violationForm_btn.disabled = true;
                    save_NewSanctions_btn.disabled = true;
                    return true;
                });
        </script>
    {{-- Sanction Registration form end --}}
@endpush