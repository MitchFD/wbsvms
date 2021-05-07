@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'violation_entry'
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
                <a href="{{ route('violation_entry.index', 'violation_entry') }}" class="directory_active_link">Violation Entry</a>
            </div>
        </div>

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">Violation Entry</span>
                            @php
                                $current_date = now()->format('F d, Y - l');
                                $current_day  = now()->format('l');
                                $mon_fri_rule = 'Today, every student is expected to wear the SDCA shirt or any red SDCA Foundation shirt if they are reporting to the school.';
                                $tue_thu_rule = 'Today, students are expected to be in school uniform. No students are allowed to enter the campus when not in proper uniform.';
                                $wed_rule     = 'Today, students are expected to be on their casual attire.';
                                $sat_rule     = 'Today, students are expected to wear organizational shirt or any SDCA red shirt. For exemptions, students are advised to visit the Student Discipline Unit.';
                                $sun_rule     = 'Today, students who do not have classes but need to come to the college are also required to wear decent attire or uniform of the day.';
                            @endphp
                            @if(strtolower($current_day) === 'monday') 
                                <span class="date_span_intro">{{ $current_date }}</span>
                                <span class="page_intro_subtitle">{{ $mon_fri_rule }}</span>
                            @elseif(strtolower($current_day) === 'tuesday') 
                                <span class="date_span_intro">{{ $current_date }}</span>
                                <span class="page_intro_subtitle">{{ $tue_thu_rule }}</span>
                            @elseif(strtolower($current_day) === 'wednesday')   
                                <span class="date_span_intro">{{ $current_date }}</span>
                                <span class="page_intro_subtitle">{{ $wed_rule }}</span>
                                <button class="btn btn_outline_svms_red btn-small btn-outline btn-round shadow btn_show_icon" data-toggle="modal" data-target="#notAllowedAttireModal"><i class="fa fa-ban btn_icon_show_left" aria-hidden="true"></i> Not allowed attires...</button>
                            @elseif(strtolower($current_day) === 'thursday') 
                                <span class="date_span_intro">{{ $current_date }}</span>
                                <span class="page_intro_subtitle">{{ $tue_thu_rule }}</span>
                            @elseif(strtolower($current_day) === 'friday') 
                                <span class="date_span_intro">{{ $current_date }}</span>
                                <span class="page_intro_subtitle">{{ $mon_fri_rule }}</span>
                            @elseif(strtolower($current_day) === 'saturday') 
                                <span class="date_span_intro">{{ $current_date }}</span>
                                <span class="page_intro_subtitle">{{ $sat_rule }}</span>
                            @elseif(strtolower($current_day) === 'sunday') 
                                <span class="date_span_intro">{{ $current_date }}</span>
                                <span class="page_intro_subtitle">{{ $mon_fri_rule }}</span>
                            @else 
                                <span class="page_intro_subtitle"> No students are allowed to enter the campus when not in proper uniform. Students who do not have classes but need to come to the college are also required to wear decent attire or uniform of the day.</span>
                            @endif
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/violation_entry_illustration2.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center mt-3">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="cust_tagInput_div">
                    <i class="nc-icon nc-zoom-split cust_tagInput_icon"></i>
                    {{-- <div class="cust_tagInput_pill">
                        <img class="cust_tagInput_img" src="{{asset('storage/svms/user_images/2x2_09042021183001.jpg')}}" alt="student's image">
                        <span> desierto </span>
                        <button class="btn cust_tagInput_removeBtn"><i class="fa fa-times"></i></button>
                    </div> --}}
                    <input type="text" id="search_violators" name="search_violators" class="cust_tagInput_field" id="tagInput_searchViolators" placeholder="Search Students..." />
                    <button class="btn btn_svms_red cust_tagInput_button" id="openViolationFormModal_btn" onclick="openViolationFormModal_btn()" type="button" disabled><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                </div>
                {{-- <div class="input-group cust_inpGrp_div mt-3">
                    <input type="text" id="search_violators" name="search_violators" class="form-control input_grpInpt" placeholder="Search Students by name or student number" aria-label="Search Students by name or student number" autocomplete="off">
                    <i class="nc-icon nc-zoom-split input_grpIcon"></i>
                    <div class="input-group-append">
                        <button class="btn btn_svms_red input_grpBtn" id="openViolationFormModal_btn" type="button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                    </div>
                </div> --}}
                
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12">
                        <div id="displaySearchViolators_results">
                            
                        </div>
                        {{-- <div class="list-group mt-3 shadow cust_list_group_ve" id="displaySearchViolators_results">
                            <a href="#" data-toggle="modal" data-target="#violationEntryModal" class="list-group-item list-group-item-action cust_lg_item_ve">
                                <div class="display_user_image_div text-center">
                                    <img class="display_violator_image shadow-sm" src="{{asset('storage/svms/user_images/default_student_img.jpg')}}" alt="student's image">
                                </div>
                                <div class="information_div">
                                    <span class="li_info_title">Mitch Frankein O. Desierto</span>
                                    <span class="li_info_subtitle"><span class="font-weight-bold">20150348 </span> | SBCS - BSIT 4A | Male</span>
                                </div>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#violationEntryModal" class="list-group-item list-group-item-action cust_lg_item_ve">
                                <div class="display_user_image_div text-center">
                                    <img class="display_violator_image shadow-sm" src="{{asset('storage/svms/user_images/default_student_img.jpg')}}" alt="student's image">
                                </div>
                                <div class="information_div">
                                    <span class="li_info_title">Mitch Frankein O. Desierto</span>
                                    <span class="li_info_subtitle"><span class="font-weight-bold">20150348 </span> | SBCS - BSIT 4A | Male</span>
                                </div>
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modals --}}
    {{-- violation entry modal --}}
        <div class="modal fade" id="violationEntryModal" tabindex="-1" role="dialog" aria-labelledby="violationEntryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0 pb-0">
                        {{-- <span class="modal-title cust_modal_title" id="violationEntryModalLabel">Violation Form</span> --}}
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="violationEntryModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- violation entry modal end --}} 
    {{-- violation entry modal --}}
        <div class="modal fade" id="notAllowedAttireModal" tabindex="-1" role="dialog" aria-labelledby="notAllowedAttireModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="notAllowedAttireModalLabel">Not Allowed Attires</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="lightRed_cardBody_notice mb-2">
                            When entering the campus, the following are NOT allowed:
                        </div>
                        <div class="lightRed_cardBody shadow-none mb-2">
                            <span class="lightRed_cardBody_redTitle mb-0">For Males:</span>
                            <ul class="cust_ul text_svms_red">
                                <li> Sando, boxer short and sleeveless shirt;</li>
                                <li> All kinds of shirts and torn/worn out jeans;</li>
                                <li> Earings;</li>
                                <li> Rubber slippers, beach walks, worn out shoes;</li>
                                <li> Cross-dressing and body piercings.</li>
                            </ul>
                        </div>
                        <div class="lightRed_cardBody shadow-none mb-2">
                            <span class="lightRed_cardBody_redTitle mb-0">For Females:</span>
                            <ul class="cust_ul text_svms_red">
                                <li> Blouse or dress with plunging neckline and/or backless/strapless features;</li>
                                <li> Micro skirts, miniskirts, leggings, jeggings, spaghetti straps, razor back;</li>
                                <li> Rubber slippers, beach walks, worn out shoes;</li>
                                <li> All kinds of shirts and torn/worn out jeans;</li>
                                <li> Black leggings are not substitute for female dark gray slacks;</li>
                                <li> See through attires and body hugging blouses/skirts.</li>
                            </ul>
                        </div>
                        <div class="lightBlue_cardBody shadow-none mb-2">
                            <div class="lightBlue_cardBody_notice">
                                Students who do not have classes but need to come to the college on those days that are not washdays are also required to wear decent attire or uniform of the day.
                            </div>
                        </div>
                        <div class="btn-group d-flex justify-content-end mt-3" role="group" aria-label="Ok Confirmation">
                            <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal">Ok <i class="fa fa-thumbs-up btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- violation entry modal end --}}
@endsection

@push('scripts')
{{-- violation form --}}
    <script>
        $('#violationEntryModal').on('show.bs.modal', function () {
            // adding new input for Other Offenses
                // disable/enable add button
                var otherOffenses_input  = document.querySelector("#addOtherOffenses_input");
                var otherOffensesAdd_Btn = document.querySelector("#btn_addAnother_input");
                var form_addViolation  = document.querySelector("#form_addViolation");
                var submit_violationForm_btn = document.querySelector("#submit_violationForm_btn");
                var cancel_violationForm_btn = document.querySelector("#cancel_violationForm_btn");
                var addedOtherOff_field = $('.addedOtherOff_field').filter(function() {
                    return this.value != '';
                });
                $(otherOffenses_input).keyup(function(){
                    if(otherOffenses_input.value !== ""){
                        otherOffensesAdd_Btn.disabled = false;
                    }else{
                        if (addedOtherOff_field.length == 0) {
                            submit_violationForm_btn.disabled = true;
                        }else{
                            submit_violationForm_btn.disabled = false;
                        }
                        otherOffensesAdd_Btn.disabled = true;
                    }
                });
                // appending new input field
                    // appending new input field
                    function addOtherOffIndexing(){
                        i = 1;
                        $(".addOtherOffIndex").each(function(){
                            $(this).html(i+1 + '.');
                            i++;
                        });
                    }
                    var maxField = 10;
                    var addedInputFields_div = document.querySelector('.addedInputFields_div');
                    var newInputField = '<div class="input-group mb-2">' +
                                            '<div class="input-group-append"> ' +
                                                '<span class="input-group-text txt_iptgrp_append2 addOtherOffIndex font-weight-bold">1. </span> ' +
                                            '</div> ' +
                                            '<input type="text" name="other_offenses[]" class="form-control input_grpInpt2 addedOtherOff_field" placeholder="Type Other Offense" aria-label="Type Other Offense" aria-describedby="other-offenses-input" required /> ' +
                                            '<div class="input-group-append"> ' +
                                                '<button class="btn btn_svms_blue m-0 btn_deleteAnother_input" type="button"><i class="nc-icon nc-simple-remove font-weight-bold" aria-hidden="true"></i></button> ' +
                                            '</div> ' +
                                        '</div>';
                    var x = 1;
                    $(otherOffensesAdd_Btn).click(function(){
                        if(x < maxField){
                            x++;
                            $(addedInputFields_div).append(newInputField);
                            // console.log(x);
                        }
                        addOtherOffIndexing();
                    });
                    $(addedInputFields_div).on('click', '.btn_deleteAnother_input', function(e){
                        e.preventDefault();
                        $(this).closest('.input_grpInpt2').value = '';
                        $(this).closest('.input-group').last().remove();
                        x--;
                        // console.log('click');
                        addOtherOffIndexing();
                    });
            // disable cancel and sibmit button on submit
                $(form_addViolation).submit(function(){
                    cancel_violationForm_btn.disabled = true;
                    submit_violationForm_btn.disabled = true;
                    return true;
                });
        });
    </script>
    {{-- disable/enable submit button --}}
    <script>
        $('#violationEntryModal').on('show.bs.modal', function () {
            $('#form_addViolation').each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#submit_violationForm_btn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
            }).find('#submit_violationForm_btn').prop('disabled', true);
        });
    </script>
{{-- violation form end --}}

{{-- live search violators --}}
    <script>
        $(document).ready(function(){
            var resultsDisplay = document.querySelector("#displaySearchViolators_results");
            fetch_searchViolators_results();
            function fetch_searchViolators_results(violators_query = ''){
                $.ajax({
                    url:"{{ route('violation_entry.search_violators') }}",
                    method:"GET",
                    data:{violators_query:violators_query},
                    dataType:'json',
                    success:function(data){
                        $('#displaySearchViolators_results').html(data.violators_results);
                    }
                });
            }
            $(document).on('keyup', '#search_violators', function(){
                var violator_query = $(this).val();
                if(violator_query === ''){
                    resultsDisplay.classList.remove("d-block");
                    resultsDisplay.classList.add("d-none");
                }else{
                    fetch_searchViolators_results(violator_query);
                    resultsDisplay.classList.remove("d-none");
                    resultsDisplay.classList.add("d-block");
                }
            });
        });
    </script>
{{-- live search violators end --}}

{{-- tag input for adding violators --}}
    {{-- focus of search_violators input field on cust_tagInput_div click --}}
    <script>
        $(document).ready(function(){
            var cust_tagInput_div = document.querySelector(".cust_tagInput_div");
            $(cust_tagInput_div).click(function() { $('#search_violators').focus(); });
        });
    </script>
    {{-- add violator pills --}}
    <script>
        function addViolator(violatorID){
            var search_violators_input = document.querySelector("#search_violators");
            var cust_tagInput_div = document.querySelector(".cust_tagInput_div");
            var openViolationFormModal_btn = document.querySelector("#openViolationFormModal_btn");
            var existing_violators_ids = [];
            var student_info_lname = null;
            var student_info_fname = null;
            var student_info_image = null;
            // get student's info for pill display
            $.ajax({
                url:"{{ route('violation_entry.get_selected_student_info') }}",
                method:"GET",
                data:{violatorID:violatorID},
                dataType:'json',
                async: false,
                global: false,
                success:function(data){
                    student_info_lname = data.sel_student_lname;
                    student_info_fname = data.sel_student_fname;
                    student_info_image = data.sel_student_image;
                }
            });
            // pill to append before input field
            pill_tag_html = '<div class="cust_tagInput_pill" id="'+violatorID+'"> \
                                <img class="cust_tagInput_img" src="{{asset("storage/svms/sdca_images/registered_students_imgs/")}}/'+student_info_image+'" alt="students image"> \
                                <span class="cust_tagInput_name"> ' + student_info_fname + ' ' + student_info_lname + ' </span> \
                                <button class="btn btn_svms_red cust_tagInput_removeBtn"><i class="fa fa-times"></i></button> \
                            </div>';
            // push all existing pills to existing_violators_ids array
            $(".cust_tagInput_pill").each(function(){
                existing_violators_ids.push($(this).attr("id"));
            });
            // console.log(existing_violators_ids);
            // check if id already exist
            if(existing_violators_ids.indexOf(violatorID) !== -1){
                alert(student_info_fname + ' ' + student_info_lname + ' is already on the list');
                search_violators_input.value = "";
                search_violators_input.focus();
            }else{
                existing_violators_ids.push(violatorID);
                search_violators_input.insertAdjacentHTML('beforebegin', pill_tag_html);   
                search_violators_input.value = "";
                search_violators_input.focus();
            }
            // remove pill
            $(document).on('click', '.cust_tagInput_removeBtn', function(){
                $(this).parent().remove();
                if($(cust_tagInput_div).find('.cust_tagInput_pill').length !== 0){
                    openViolationFormModal_btn.disabled = false;
                }else{
                    openViolationFormModal_btn.disabled = true;
                }
            });
            // check i there are pills present else disable openViolationFormModal_btn button
            if($(cust_tagInput_div).find('.cust_tagInput_pill').length !== 0){
                openViolationFormModal_btn.disabled = false;
            }else{
                openViolationFormModal_btn.disabled = true;
            }
        }
    </script>
{{-- tag input for adding violators end --}}

{{-- pass all selected students ids to violation form --}}
    <script>
        function openViolationFormModal_btn(){
            var cust_tagInput_div = document.querySelector(".cust_tagInput_div");
            var _token = $('input[name="_token"]').val();
            var existing_violators_ids = [];
            $(".cust_tagInput_pill").each(function(){
                existing_violators_ids.push($(this).attr("id"));
            });
            var violators_ids = JSON.stringify(existing_violators_ids);
            // console.log(existing_violators_ids);
            // console.log(violators_ids);
            $.ajax({
                    url:"{{ route('violation_entry.open_violation_form_modal') }}",
                    method:"GET",
                    data:{violators_ids:violators_ids, _token:_token},
                    // dataType:'json',
                    success:function(data){
                        $('#violationEntryModalHtmlData').html(data); 
                        $('#violationEntryModal').modal('show');
                    }
                });
        }
    </script>
{{-- pass all selected students ids to violation form end --}}
@endpush