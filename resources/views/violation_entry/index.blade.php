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
                <a href="#" class="directory_link">Violation Entry</a>
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
                    <input type="text" id="search_violators" name="search_violators" class="form-control input_grpInpt" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <i class="nc-icon nc-zoom-split input_grpIcon"></i>
                    <div class="input-group-append">
                        <button class="btn btn_svms_red input_grpBtn" id="openViolator_modal" type="button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12">
                        <div class="list-group mt-3 shadow cust_list_group_ve" id="displaySearchViolators_results">
                            <a href="#" data-toggle="modal" data-target="#violationEntryModal" class="list-group-item list-group-item-action cust_lg_item_ve">
                                <div class="display_user_image_div text-center">
                                    <img class="display_violator_image shadow-sm" src="{{asset('storage/svms/user_images/default_student_img.jpg')}}" alt="student's image">
                                </div>
                                <div class="information_div">
                                    <span class="li_info_title">Mitch Frankein O. Desierto</span>
                                    <span class="li_info_subtitle">20150348 | BSIT 4A - SBCS | Male</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modals --}}
    {{-- violation entry modal --}}
        <div class="modal fade" id="violationEntryModal" tabindex="-1" role="dialog" aria-labelledby="violationEntryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="violationEntryModalLabel">Violation Form</span>
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
{{-- live search violators --}}
    {{-- <script>
        $(document).ready(function(){
            $('#search_violators').tokenfield({
                autocomplete :{
                    source: function(request, response)
                    {
                        jQuery.get("{{ url('violation_entry.search_violators') }}", {
                            query : request.term
                        }, function(data){
                            data = JSON.parse(data);
                            response(data);
                        });
                    },
                    delay: 100
                }
            });
            $('#openViolator_modal').click(function(){
                $('#displaySearchViolators_results').text($('#search_violators').val());
            });
        });
    </script> --}}
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