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

            {{-- buttons for adding categories and offenses --}}
            <div class="row mb-3">
                <div class="col-lg-12 col-md-12 col-sm-12 text-left">
                    <button type="button" onclick="addNewCategory()" class="btn btn_svms_blue cust_bt_links shadow mr-2"><i class="nc-icon nc-simple-add mr-1" aria-hidden="true"></i> Add New Category</button>
                    <button type="button" onclick="addNewOffenses()" class="btn btn_svms_red cust_bt_links shadow"><i class="fa fa-pencil-square-o mr-1" aria-hidden="true"></i> Add New Offenses</button>
                </div>
            </div>

            {{-- Offense Categories Cards --}}
            @if(count($query_OffensesCategory) > 0)
                <div class="row">
                    @foreach ($query_OffensesCategory as $this_offCategory)
                        @php
                            // category title
                            $offCategory_title = ucwords($this_offCategory->offCategory);
                            // counts
                            $countAll_perOffCategory = App\Models\CreatedOffenses::where('crOffense_category', '=', $this_offCategory->offCategory)->count();
                            $countDefault_perOffCategory = App\Models\CreatedOffenses::where('crOffense_category', '=', $this_offCategory->offCategory)->where('crOffense_type', '=', 'default')->count();
                            $countCustom_perOffCategory = App\Models\CreatedOffenses::where('crOffense_category', '=', $this_offCategory->offCategory)->where('crOffense_type', '!=', 'default')->count();
                            // displays
                            // all offenses counts
                            if($countAll_perOffCategory > 0){
                                if($countAll_perOffCategory > 1){
                                    $caCO_s = 's';
                                }else{
                                    $caCO_s = '';
                                }
                                $txt_totalOffensesCount = ''.$countAll_perOffCategory . ' Registered ' . $offCategory_title.'.';
                            }else{
                                $caCO_s = '';
                                $txt_totalOffensesCount = 'No Offenses.';
                            }
                            // default offenses count
                            if($countDefault_perOffCategory > 0){
                                if($countDefault_perOffCategory > 1){
                                    $cdCO_s = 's';
                                }else{
                                    $cdCO_s = '';
                                }
                                $txt_totalDefaultOffensesCount = ''.$countDefault_perOffCategory . ' Default ' . $offCategory_title.'.';
                            }else{
                                $cdCO_s = '';
                                $txt_totalDefaultOffensesCount = 'No Default' . $offCategory_title.'.';
                            }
                            // custom offenses count
                            if($countCustom_perOffCategory > 0){
                                if($countCustom_perOffCategory > 1){
                                    $ccCO_s = 's';
                                }else{
                                    $ccCO_s = '';
                                }
                                $txt_totalCustomOffensesCount = ''.$countCustom_perOffCategory . ' Custom' . $offCategory_title.'.';
                            }else{
                                $ccCO_s = '';
                                $txt_totalCustomOffensesCount = 'No Custom ' . $offCategory_title.'.';
                            }
                        @endphp
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="accordion gCardAccordions" id="{{$this_offCategory->offCat_id}}_offCategoryDisplayCollapseParent">
                                <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                                    <div class="card-header p-0" id="{{$this_offCategory->offCat_id}}_offCategoryDisplayCollapseHeading">
                                        <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#{{$this_offCategory->offCat_id}}_offCategoryDisplayCollapseDiv" aria-expanded="true" aria-controls="{{$this_offCategory->offCat_id}}_offCategoryDisplayCollapseDiv">
                                            <div>
                                                <span class="card_body_title">{{ $offCategory_title }}</span>
                                                <span class="card_body_subtitle">{{ $txt_totalOffensesCount }}</span>
                                            </div>
                                            <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                                        </button>
                                    </div>
                                    <div id="{{$this_offCategory->offCat_id}}_offCategoryDisplayCollapseDiv" class="collapse gCardAccordions_collapse show cb_t0b15x25" aria-labelledby="{{$this_offCategory->offCat_id}}_offCategoryDisplayCollapseHeading" data-parent="#{{$this_offCategory->offCat_id}}_offCategoryDisplayCollapseParent">
                                        <div class="card card_gbr card_ofh shadow cb_p15 mt-0 mb-1">
                                            @if($countDefault_perOffCategory > 0)
                                                @php
                                                    $queryAllDefault_perOffCategory = App\Models\CreatedOffenses::select('crOffense_id', 'crOffense_details')->where('crOffense_category', '=', $this_offCategory->offCategory)->where('crOffense_type', '=', 'default')->get();
                                                    $defIndex = 0;
                                                @endphp
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="card-body lightBlue_cardBody">
                                                            <span class="lightBlue_cardBody_blueTitle mb-1"><i class="fa fa-info-circle cust_info_icon mr-1" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Default {{ $offCategory_title }} are not allowed to be edited or deleted from the system."></i> Default {{ $offCategory_title}}:</span>
                                                            @foreach ($queryAllDefault_perOffCategory as $thisDefault_perOffCategory)
                                                                @php
                                                                    $defIndex++;
                                                                @endphp
                                                                <span class="lightBlue_cardBody_list"><span class="font-weight-bold mr-1">{{$defIndex}}. </span>{{ $thisDefault_perOffCategory->crOffense_details }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="card-body lightBlue_cardBody">
                                                            <span class="lightBlue_cardBody_list font-italic">No Default {{ $offCategory_title}}...</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($countCustom_perOffCategory > 0)
                                                @php
                                                    $queryAllCustom_perOffCategory = App\Models\CreatedOffenses::select('crOffense_id', 'crOffense_details')->where('crOffense_category', '=', $this_offCategory->offCategory)->where('crOffense_type', '!=', 'default')->get();
                                                    $custIndex = 0;
                                                @endphp
                                                <div class="row mt-2">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="card-body lightRed_cardBody">
                                                            <span class="lightRed_cardBody_redTitle mb-1"><i class="fa fa-info-circle cust_info_icon mr-1" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Custom {{ $offCategory_title }} can be edited or deleted from the system."></i> Custom {{ $offCategory_title}}:</span>
                                                            @foreach ($queryAllCustom_perOffCategory as $this_Custom_perOffCategory)
                                                                @php
                                                                    $custIndex++;
                                                                @endphp
                                                                <span class="lightRed_cardBody_list cursor_pointer" onclick="editOffenseDetails()"><span class="font-weight-bold mr-1">{{$custIndex}}. </span> {{ $this_Custom_perOffCategory->crOffense_details}}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <span class="cust_info_txtwicon3"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> Click on any Custom {{ $offCategory_title }} for more options.</span>  
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-footer align-items-center px-0 pb-0">
                                            <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-list-ul mr-1" aria-hidden="true"></i> {{ $txt_totalOffensesCount }} </span>  
                                            <span class="cust_info_txtwicon"><i class="fa fa-cog mr-1" aria-hidden="true"></i> {{ $txt_totalDefaultOffensesCount }} </span>  
                                            <span class="cust_info_txtwicon"><i class="fa fa-pencil-square-o mr-1" aria-hidden="true"></i> {{ $txt_totalCustomOffensesCount }} </span>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card-body card_body_bg_gray2 card_gbr card_ofh mb-2">
                            <span class="lightBlue_cardBody_list font-italic"><i class="fa fa-exclamation-circle font-weight-bold mr-1" aria-hidden="true"></i> No Offense Categories Found...</span>
                        </div>
                    </div>
                </div>
            @endif

        @else

        @endif
    @else
        
    @endif
    </div>

    {{-- modals --}}
    {{-- add new category --}}
        <div class="modal fade" id="addNewCategoryFormModal" tabindex="-1" role="dialog" aria-labelledby="addNewCategoryFormModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="addNewCategoryFormModalLabel">Add New Category?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="addNewCategoryFormModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- add new category end --}}

@endsection

@push('scripts')

{{-- add new Category --}}
    <script>
        function addNewCategory(){
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('offenses.add_new_category_form') }}",
                method:"GET",
                data:{_token:_token},
                success: function(data){
                    $('#addNewCategoryFormModalHtmlData').html(data); 
                    $('#addNewCategoryFormModal').modal('show');
                }
            });

        }
    </script>
    <script>
        $('#addNewCategoryFormModal').on('show.bs.modal', function () {
            var form_registerNewCategory  = document.querySelector("#form_registerNewCategory");
            var cancel_registerNewCategory_btn = document.querySelector("#cancel_registerNewCategory_btn");
            var process_registerNewCategory_btn = document.querySelector("#process_registerNewCategory_btn");
            // disable cancel and sibmit button on submit
            $(form_registerNewCategory).submit(function(){
                process_registerNewCategory_btn.disabled = true;
                cancel_registerNewCategory_btn.disabled = true;
                return true;
            });
        });
    </script>
{{-- add new Category end --}}

{{-- add new Category --}}
    <script>
        function addNewOffenses(){
            alert('add new custom Offenses');
        }
    </script>
{{-- add new Category end --}}

{{-- edit offense details --}}
    <script>
        function editOffenseDetails(){
            alert('wow');
        }
    </script>
{{-- edit offense details end--}}

@endpush