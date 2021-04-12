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
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/violation_entry_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center mt-3">
            <div class="col-lg-7 col-md-10 col-sm-12">
                <div class="input-group cust_inpGrp_div">
                    <input type="text" id="search_violators" name="search_violators" class="form-control input_grpInpt" placeholder="Recipient's username" aria-label="Recipient's username" autocomplete="off">
                    <i class="nc-icon nc-zoom-split input_grpIcon"></i>
                    <div class="input-group-append">
                        <button class="btn btn_svms_red input_grpBtn" id="openViolator_modal" type="button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                    </div>
                </div>
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
                    {{-- <div id="violationEntryModalHtmlData">
                    
                    </div> --}}
                    <div class="modal-body pt-0">
                        <div class="row d-flex justify-content-center text-center mb-3">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <img class="sdca_logo_img" src="{{asset('storage/svms/sdca_images/sdca_logo.jpg')}}" alt="SDCA Logo">
                                <span class="dsas_text">DEPARTMENT OF STUDENT AFFAIRS AND SERVICES</span>
                                <span class="sdu_text">STUDENT DISCIPLINE UNIT</span>
                                <span class="violation_text">VIOLATION FORM</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="accordion shadow-none cust_accordion_div" id="empTypeRolesModalAccordion_Parent">
                                    <div class="card custom_accordion_card2">
                                        <div class="card-header p-0" id="empTypeRolesCollapse_heading">
                                            <h2 class="mb-0 bg_F4F4F5">
                                                <button class="btn btn-block custom3_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#selectedViolatorsCollapse_Div" aria-expanded="true" aria-controls="selectedViolatorsCollapse_Div">
                                                    <div>
                                                        <span class="li_info_title">Violators</span>
                                                        <span class="li_info_subtitle">3 Students Selected</span>
                                                    </div>
                                                    <i class="nc-icon nc-minimal-up"></i>
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="selectedViolatorsCollapse_Div" class="collapse cust_collapse_active show cb_t0b12y15 bg_F4F4F5" aria-labelledby="empTypeRolesCollapse_heading" data-parent="#empTypeRolesModalAccordion_Parent">
                                            <div class="row mt-0">
                                                <div class="col-lg-6 col-md-6 col-sm-12 m-0">
                                                    <div class="violators_cards_div mb-2 d-flex justify-content-start align-items-center">
                                                        <div class="display_user_image_div text-center">
                                                            <img class="display_violator_image2 shadow-sm" src="{{asset('storage/svms/user_images/default_student_img.jpg')}}" alt="student's image">
                                                        </div>
                                                        <div class="information_div">
                                                            <span class="li_info_title">Mitch Frankein O. Desierto</span>
                                                            <span class="li_info_subtitle2"><span class="font-weight-bold">20150348 </span> | SBCS - BSIT 4A | Male</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 m-0">
                                                    <div class="violators_cards_div mb-2 d-flex justify-content-start align-items-center">
                                                        <div class="display_user_image_div text-center">
                                                            <img class="display_violator_image2 shadow-sm" src="{{asset('storage/svms/user_images/default_student_img.jpg')}}" alt="student's image">
                                                        </div>
                                                        <div class="information_div">
                                                            <span class="li_info_title">Mitch Frankein O. Desierto</span>
                                                            <span class="li_info_subtitle2"><span class="font-weight-bold">20150348 </span> | SBCS - BSIT 4A | Male</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form id="form_addViolation" action="#" enctype="multipart/form-data" method="POST" onsubmit="submit_violationForm_btn.disabled = true; return true;">
                            <div class="row mt-3">
                                <div class="col-lg-6 col-md-6 col-sm-12 pr-0">
                                    <div class="lightRed_cardBody h-100">
                                        <span class="lightRed_cardBody_redTitle">Minor Offenses:</span>
                                        <div class="form-group mx-0 mt-2 mb-1">
                                            <div class="custom-control custom-checkbox align-items-center">
                                                <input type="checkbox" name="minor_offenses[]" value="Violation of Dress Code" class="custom-control-input cursor_pointer" id="mo_1">
                                                <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="mo_1">Violation of Dress Code</label>
                                            </div>
                                        </div>
                                        <div class="form-group mx-0 mt-0 mb-1">
                                            <div class="custom-control custom-checkbox align-items-center">
                                                <input type="checkbox" name="minor_offenses[]" value="Not wearing the prescribed uniform" class="custom-control-input cursor_pointer" id="mo_2">
                                                <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="mo_2">Not wearing the prescribed uniform</label>
                                            </div>
                                        </div>
                                        <div class="form-group mx-0 mt-0 mb-1">
                                            <div class="custom-control custom-checkbox align-items-center">
                                                <input type="checkbox" name="minor_offenses[]" value="Not wearing ID" class="custom-control-input cursor_pointer" id="mo_3">
                                                <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="mo_3">Not wearing ID</label>
                                            </div>
                                        </div>
                                        <div class="form-group mx-0 mt-2 mb-1">
                                            <div class="custom-control custom-checkbox align-items-center">
                                                <input type="checkbox" name="minor_offenses[]" value="Littering" class="custom-control-input cursor_pointer" id="mo_4">
                                                <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="mo_4">Littering</label>
                                            </div>
                                        </div>
                                        <div class="form-group mx-0 mt-0 mb-1">
                                            <div class="custom-control custom-checkbox align-items-center">
                                                <input type="checkbox" name="minor_offenses[]" value="Using cellular phones and other E-gadgets while having a class" class="custom-control-input cursor_pointer" id="mo_5">
                                                <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="mo_5">Using cellular phones and other E-gadgets while having a class</label>
                                            </div>
                                        </div>
                                        <div class="form-group mx-0 mt-0 mb-1">
                                            <div class="custom-control custom-checkbox align-items-center">
                                                <input type="checkbox" name="minor_offenses[]" value="Body Piercing" class="custom-control-input cursor_pointer" id="mo_6">
                                                <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="mo_6">Body Piercing</label>
                                            </div>
                                        </div>
                                        <div class="form-group mx-0 mt-0 mb-1">
                                            <div class="custom-control custom-checkbox align-items-center">
                                                <input type="checkbox" name="minor_offenses[]" value="Indecent Public Display of Affection" class="custom-control-input cursor_pointer" id="mo_7">
                                                <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="mo_7">Indecent Public Display of Affection</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="lightRed_cardBody h-100">
                                        <span class="lightRed_cardBody_redTitle">Less Serious Offenses:</span>
                                        <div class="form-group mx-0 mt-2 mb-1">
                                            <div class="custom-control custom-checkbox align-items-center">
                                                <input type="checkbox" name="less_serious_offenses[]" value="Wearing somebody else's ID" class="custom-control-input cursor_pointer" id="lso_1">
                                                <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="lso_1">Wearing somebody else's ID</label>
                                            </div>
                                        </div>
                                        <div class="form-group mx-0 mt-0 mb-1">
                                            <div class="custom-control custom-checkbox align-items-center">
                                                <input type="checkbox" name="less_serious_offenses[]" value="Wearing Tampered/Unauthorized ID" class="custom-control-input cursor_pointer" id="lso_2">
                                                <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="lso_2">Wearing Tampered/Unauthorized ID</label>
                                            </div>
                                        </div>
                                        <div class="form-group mx-0 mt-0 mb-1">
                                            <div class="custom-control custom-checkbox align-items-center">
                                                <input type="checkbox" name="less_serious_offenses[]" value="Lending His/Her ID" class="custom-control-input cursor_pointer" id="lso_3">
                                                <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="lso_3">Lending His/Her ID</label>
                                            </div>
                                        </div>
                                        <div class="form-group mx-0 mt-2 mb-1">
                                            <div class="custom-control custom-checkbox align-items-center">
                                                <input type="checkbox" name="less_serious_offenses[]" value="Smoking or Possession of Smoking Paraphernalia" class="custom-control-input cursor_pointer" id="lso_4">
                                                <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="lso_4">Smoking or Possession of Smoking Paraphernalia</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="lightRed_cardBody">
                                        <span class="lightRed_cardBody_redTitle">Others:</span>
                                        <div class="input-group mb-2">
                                            <input type="text" id="addOtherOffenses_input" onkeyup="otherOffenses_InputHas_txt()" name="other_offenses[]" class="form-control input_grpInpt2" placeholder="Type Other Offense" aria-label="Type Other Offense" aria-describedby="other-offenses-input">
                                            <div class="input-group-append">
                                                <button class="btn btn_svms_red m-0" id="btn_addAnother_input" type="button" disabled><i class="nc-icon nc-simple-add font-weight-bold" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                        <div class="addedInputFields_div">
                                            {{-- new input field --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 d-flex align-items-center">
                                <div class="col-lg-6 col-md-6 col-sm-12 d-flex-justify-content-start">
                                    @php
                                        $now_timestamp = now();
                                    @endphp
                                    <span class="cust_info_txtwicon2 font-weight-bold"><i class="nc-icon nc-calendar-60 mr-1" aria-hidden="true"></i> {{ date('F d, Y', strtotime($now_timestamp)) }} -  {{ date('D', strtotime($now_timestamp)) }} at {{ date('g:i A', strtotime($now_timestamp)) }}</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end">
                                    <input type="hidden" name="violators[]" value="secret">
                                    <input type="hidden" name="_token" value="'.csrf_token().'">
                                    <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                                    <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                                    <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                                        <button id="submit_violationForm_btn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0" disabled>Save <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
{{-- <script>
    $(window).on('load', function(){
        $('#violationEntryModal').modal('show');
    });
</script> --}}

{{-- violation form --}}
    {{-- check if others' input has text then enable add new input button --}}
    <script>
        const otherOffenses_input  = document.querySelector("#addOtherOffenses_input");
        const otherOffensesAdd_Btn = document.querySelector("#btn_addAnother_input");
        function otherOffenses_InputHas_txt(){
            if(otherOffenses_input.value !== ""){
                otherOffensesAdd_Btn.disabled = false;
            }else{
                otherOffensesAdd_Btn.disabled = true;
            }
        }
    </script>
    {{-- adding new input for other offenses --}}
    <script>
        $('#violationEntryModal').on('show.bs.modal', function () {
            var maxField = 10;
            var btn_addAnother_input = document.querySelector("#btn_addAnother_input");
            var addedInputFields_div = document.querySelector('.addedInputFields_div');
            var newInputField = '<div class="input-group mb-2">' +
                                    '<input type="text" name="other_offenses[]" class="form-control input_grpInpt2" placeholder="Type Other Offense" aria-label="Type Other Offense" aria-describedby="other-offenses-input"> ' +
                                    '<div class="input-group-append"> ' +
                                        '<button class="btn btn_svms_red m-0 btn_deleteAnother_input" type="button"><i class="nc-icon nc-simple-remove font-weight-bold" aria-hidden="true"></i></button> ' +
                                    '</div> ' +
                                '</div>';
            var x = 1;
            $(btn_addAnother_input).click(function(){
                if(x < maxField){
                    x++;
                    $(addedInputFields_div).append(newInputField);
                    // console.log(x);
                }
            });
            $(addedInputFields_div).on('click', '.btn_deleteAnother_input', function(e){
                e.preventDefault();
                $(this).closest('.input_grpInpt2').value = '';
                $(this).closest('.input-group').last().remove();
                x--;
                // console.log('click');
            });
        });
    </script>
    {{-- enable submit button when violatin form has values --}}
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
        // $(document).ready(function(){
            //     $('#search_violators').tokenfield({
            //         autocomplete :{
            //             source: function(request, response)
            //             {
            //                 jQuery.get("{{ url('violation_entry.search_violators') }}", {
            //                     query : request.term
            //                 }, function(data){
            //                     data = JSON.parse(data);
            //                     response(data);
            //                 });
            //             },
            //             delay: 100
            //         }
            //     });
            //     $('#openViolator_modal').click(function(){
            //         $('#displaySearchViolators_results').text($('#search_violators').val());
            //     });
            // });
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

{{-- live search --}}
    {{-- <script>
        $(document).ready(function(){
            fetch_search_result();
            function fetch_search_result(query = ''){
            $.ajax({
                url:"{{ route('violation_entry.search_student') }}",
                method:'GET',
                data:{query:query},
                dataType:'json',
                success:function(data){
                        $('tbody').html();
                    }
                })
            }
            $(document).on('keyup', '#search_student', function(){
                var query = $(this).val();
                fetch_search_result(query);
            });
        });
    </script> --}}
{{-- live search end --}}
@endpush