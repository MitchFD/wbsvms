@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'sanctions'
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
                <div class="col-lg-7 col-md-7 col-sm-12">
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
                                                                <button type="button" id="editSelectedSanctions_btn" onclick="edit_SelectedSanctions()" class="btn btn-success cust_bt_links shadow ml-1" disabled data-toggle="tooltip" data-placement="top" title="Edit Selected Sanctions?"><i class="fa fa-pencil-square-o mr-1" aria-hidden="true"></i> Edit</button>
                                                                <button type="button" id="deleteSelectedSanctions_btn" onclick="delete_SelectedSanctions()" class="btn btn_svms_red cust_bt_links shadow ml-1" disabled data-toggle="tooltip" data-placement="top" title="Delete Selected Sanctions?"><i class="fa fa-trash mr-1" aria-hidden="true"></i> Delete</button>
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
                                                            <th class="pl12" width="5%">#</th>
                                                            <th> 
                                                                <div class="custom-control custom-checkbox align-items-center">
                                                                    <input type="checkbox" name="select_all_sanctions" value="all" class="custom-control-input cursor_pointer" id="markAllSanctions">
                                                                    <label class="custom-control-label th_checkbox" for="markAllSanctions" disabled data-toggle="tooltip" data-placement="top" title="Mark All Sanctions?"> Sanctions</label>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody_svms_white" id="cs_tableTbody">
                                                        {{-- ajax data table --}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center align-items-center">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <span>Total Data: <span class="font-weight-bold" id="cs_totalDataCount"> </span> </span>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-end">
                                                {{-- <a href="{{ route('user_management.create_users', 'create_users') }}" class="btn btn-success cust_bt_links shadow" role="button"><i class="nc-icon nc-simple-add mr-1" aria-hidden="true"></i> Create New User</a> --}}
                                                @csrf
                                                <input type="hidden" name="cs_hidden_page" id="cs_hidden_page" value="1" />
                                                <div id="cs_tablePagination">
    
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
            
                <div class="col-lg-5 col-md-5 col-sm-12">
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
                                    <div class="card-body cb_p25">
                                        <form id="form_registerNewSanctions" action="{{route('sanctions.register_new_sanctions')}}" method="POST">
                                            @csrf
                                            <div class="card-body lightGreen_cardBody shadow-none mb-4">
                                                <span class="lightGreen_cardBody_notice"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Created Sanction/s will be displayed as default options when adding sanction/s to recorded violations.</span>
                                            </div>
                                            <span class="lightGreen_cardBody_greenTitle">Type Sanctions:</span>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <span class="input-group-text txt_iptgrp_append font-weight-bold">1. </span>
                                                </div>
                                                <input type="text" id="newSanction_firstField_input" name="add_new_sanctions[]" class="form-control input_grpInpt3" placeholder="Type New Sanction" aria-label="Type New Sanction" aria-describedby="new-sanctions-input" required>
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

                                                    <button type="submit" id="save_NewSanctions_btn" class="btn btn_svms_blue btn-round btn_show_icon mb-0" disabled>{{ __('Register New Sanction/s') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
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

    {{-- modals --}}
    {{-- edit selected sanctions on modal --}}
        <div class="modal fade" id="editSelectedSanctionsModal" tabindex="-1" role="dialog" aria-labelledby="editSelectedSanctionsModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="editSelectedSanctionsModalLabel">Edit Selected Sanctions?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="editSelectedSanctionsModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- edit selected sanctions on modal end --}}
@endsection

@push('scripts')
    {{-- created sanctions table --}}
        {{-- ajax load table --}}
            <script>
                $(document).ready(function(){
                    load_createdSanctions_table();

                    // function for ajax table pagination
                    $(window).on('hashchange', function() {
                            if (window.location.hash) {
                                var page = window.location.hash.replace('#', '');
                                if (page == Number.NaN || page <= 0) {
                                    return false;
                                }else{
                                    getcsTPage(page);
                                }
                            }
                        });
                        $(document).on('click', '.pagination a', function(event){
                            event.preventDefault();
                            var page = $(this).attr('href').split('page=')[1];
                            $('#cs_hidden_page').val(page);
                            
                            load_createdSanctions_table();
                            getcsTPage(page);
                            $('li.page-item').removeClass('active');
                            $(this).parent('li.page-item').addClass('active');
                        });
                        function getcsTPage(page){
                            $.ajax({
                                url: '?page=' + page,
                                type: "get",
                                datatype: "html"
                            }).done(function(data){
                                location.hash = page;
                            }).fail(function(jqXHR, ajaxOptions, thrownError){
                                alert('No response from server');
                            });
                        }
                    // function for ajax table pagination end

                    function load_createdSanctions_table(){
                        // get all filtered values
                        var search_sanctions = document.getElementById('search_sanctions').value;
                        var page = document.getElementById("cs_hidden_page").value;

                        $.ajax({
                            url:"{{ route('sanctions.index') }}",
                            method:"GET",
                            data:{
                                search_sanctions:search_sanctions,
                                page:page
                                },
                            dataType:'json',
                            success:function(data){
                                $('#cs_tableTbody').html(data.cs_tbl_data);
                                $('#cs_tablePagination').html(data.cs_paginate);
                                $('#cs_totalDataCount').html(data.cs_total_data_count);

                                // mark sanctions for edit/delete 
                                $("#markAllSanctions").click(function(){
                                    if(this.checked){
                                        $(".mark_thisSanction").each(function(){
                                            this.checked=true;
                                            editSelectedSanctions_btn.disabled = false;
                                            deleteSelectedSanctions_btn.disabled = false;
                                        });              
                                    }else{
                                        $(".mark_thisSanction").each(function(){
                                            this.checked=false;
                                            editSelectedSanctions_btn.disabled = true;
                                            deleteSelectedSanctions_btn.disabled = true;
                                        });              
                                    }
                                });
                                $(".mark_thisSanction").change(function(){
                                    if ($('.mark_thisSanction:checked').length == $('.mark_thisSanction').length) {
                                        $("#markAllSanctions").prop("checked", true);
                                    }else{
                                        $("#markAllSanctions").prop("checked", false);
                                    }
                                    
                                    // disable/enable edit and delete sanctions buttons
                                    var editSelectedSanctions_btn = document.querySelector("#editSelectedSanctions_btn");
                                    var deleteSelectedSanctions_btn = document.querySelector("#deleteSelectedSanctions_btn");
                                    if($('.mark_thisSanction:checked').length > 0 || $('.mark_thisSanction:checked').length == $('.mark_thisSanction').length){
                                        editSelectedSanctions_btn.disabled = false;
                                        deleteSelectedSanctions_btn.disabled = false;
                                    }else{
                                        editSelectedSanctions_btn.disabled = true;
                                        deleteSelectedSanctions_btn.disabled = true;
                                    }
                                });
                            }
                        });
                    }

                    // live search
                        $('#search_sanctions').on('keyup', function(){
                            var liveSearchcreatedSanctions = $(this).val();
                            // add style to this input
                            if(liveSearchcreatedSanctions != ""){
                                $(this).addClass('cust_input_hasvalue');
                            }else{
                                $(this).removeClass('cust_input_hasvalue');
                            }
                            // table paginatin set to 1
                            $('#cs_hidden_page').val(1);
                            // call load_systemUsers_table()
                            load_createdSanctions_table();
                        });
                    // live search end
                });
            </script>
        {{-- ajax load table end --}}
        {{-- edit selected sanctions --}}
            {{-- open modal --}}
            <script>
                function edit_SelectedSanctions(){
                    var sel_sanctions = [];
                    $('.mark_thisSanction:checkbox:checked').each(function(){
                        sel_sanctions.push($(this).val());
                    });
                    var _token = $('input[name="_token"]').val();
                    // console.log(sel_sanctions);
                    $.ajax({
                        url:"{{ route('sanctions.edit_sanctions_form') }}",
                        method:"GET",
                        data:{sel_sanctions:sel_sanctions, _token:_token},
                        success: function(data){
                            $('#editSelectedSanctionsModalHtmlData').html(data); 
                            $('#editSelectedSanctionsModal').modal('show');
                        }
                    });
                }
            </script>
            {{-- on modal --}}
            <script>
                $('#editSelectedSanctionsModal').on('show.bs.modal', function () {
                    var form_updateCreatedSanctions  = document.querySelector("#form_updateCreatedSanctions");
                    var submit_updateCreatedSanctionsBtn = document.querySelector("#submit_updateCreatedSanctionsBtn");
                    var cancel_updateCreatedSanctionsBtn = document.querySelector("#cancel_updateCreatedSanctionsBtn");
                    $('#form_updateCreatedSanctions').each(function(){
                        $(this).data('serialized', $(this).serialize())
                    }).on('change input', function(){
                        $(this).find('#submit_updateCreatedSanctionsBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                    }).find('#submit_updateCreatedSanctionsBtn').prop('disabled', true);
                    // disable cancel and sibmit button on submit
                    $('#form_updateCreatedSanctions').submit(function(){
                        submit_updateCreatedSanctionsBtn.disabled = true;
                        cancel_updateCreatedSanctionsBtn.disabled = true;
                        return true;
                    });
                });
            </script>
        {{-- edit selected sanctions end --}}
        {{-- delete selected sanctions --}}
            <script>
                function delete_SelectedSanctions(){
                    console.log('delete');
                }
            </script>
        {{-- delete selected sanctions end --}}
    {{-- created sanctions table end --}}
 
    {{-- Sanction Registration form --}}
        <script>
            // adding new input for Other Offenses
                // disable/enable add button
                var NewSanction_firstField_input  = document.querySelector("#newSanction_firstField_input");
                var SanctionsAdd_Btn = document.querySelector("#btn_addAnother_input");
                var form_registerNewSanctions  = document.querySelector("#form_registerNewSanctions");
                var save_NewSanctions_btn = document.querySelector("#save_NewSanctions_btn");
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
                                            '<input type="text" name="add_new_sanctions[]" class="form-control input_grpInpt3 addedOtherSanctions_field" placeholder="Type New Sanction" aria-label="Type New Sanction" aria-describedby="new-sanctions-input" required /> ' +
                                            '<div class="input-group-append"> ' +
                                                '<button class="btn btn_svms_blue m-0 btn_deleteAnother_input" type="button"><i class="nc-icon nc-simple-remove font-weight-bold" aria-hidden="true"></i></button> ' +
                                            '</div> ' +
                                        '</div>';
                    var x = 1;
                    $(SanctionsAdd_Btn).click(function(){
                        if(x < maxField){
                            x++;
                            $(addedInputFields_div).append(newInputField);
                        }
                        addOtherSanctionIndexing();
                    });
                    $(addedInputFields_div).on('click', '.btn_deleteAnother_input', function(e){
                        e.preventDefault();
                        $(this).closest('.input_grpInpt3').value = '';
                        $(this).closest('.input-group').last().remove();
                        x--;
                        addOtherSanctionIndexing();
                    });
            // disable cancel and sibmit button on submit
                $(form_registerNewSanctions).submit(function(){
                    save_NewSanctions_btn.disabled = true;
                    return true;
                });
        </script>
    {{-- Sanction Registration form end --}}
@endpush