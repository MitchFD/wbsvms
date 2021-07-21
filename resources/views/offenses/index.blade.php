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
                                <span class="page_intro_subtitle">Create New Offense Details as options to be displayed on the "Violation Form" to ease the selection of offenses instead of typing "Other Offenses".
                                    You will be able to Add new Offense Details, Edit and/or Delete the existing Offense details.</span>
                            </div>
                            <div class="page_illustration">
                                <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/offenses_illustration.svg') }}" alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- buttons for adding categories and offenses --}}
            {{-- <div class="row mb-3">
                <div class="col-lg-12 col-md-12 col-sm-12 text-left">
                    <button type="button" onclick="addNewCategory()" class="btn btn_svms_blue cust_bt_links shadow mr-2"><i class="nc-icon nc-simple-add mr-1" aria-hidden="true"></i> Add New Category</button>
                    <button type="button" onclick="addNewOffenses()" class="btn btn_svms_red cust_bt_links shadow"><i class="fa fa-pencil-square-o mr-1" aria-hidden="true"></i> Add New Offenses</button>
                </div>
            </div> --}}

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
                                            <form action="#" id="{{$this_offCategory->offCat_id}}_offCategoryForm">
                                                @csrf
                                                <input type="hidden" id="{{$this_offCategory->offCat_id}}_offCategoryName_txt" value="{{$offCategory_title}}">
                                                @if($countDefault_perOffCategory > 0)
                                                    @php
                                                        $queryAllDefault_perOffCategory = App\Models\CreatedOffenses::select('crOffense_id', 'crOffense_details')->where('crOffense_category', '=', $this_offCategory->offCategory)->where('crOffense_type', '=', 'default')->get();
                                                    @endphp
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <div class="card-body lightBlue_cardBody">
                                                                <span class="lightBlue_cardBody_blueTitle mb-1">Default {{ $offCategory_title}}: <i class="fa fa-info-circle cust_info_icon ml-1" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Default {{ $offCategory_title }} are based on the SDCA's Student Handbook A.Y. 2019-2020 and can be edited or deleted. Select {{ $offCategory_title }} you want to edit or delete."></i></span>
                                                                @foreach ($queryAllDefault_perOffCategory as $thisDefault_perOffCategory)
                                                                    <div class="form-group mx-0 mt-0 mb-1">
                                                                        <div class="custom-control custom-checkbox align-items-center">
                                                                            <input type="checkbox" value="{{$thisDefault_perOffCategory->crOffense_id}}" class="custom-control-input cursor_pointer" id="{{$thisDefault_perOffCategory->crOffense_id}}">
                                                                            <label class="custom-control-label lightBlue_cardBody_chckboxLabel" for="{{$thisDefault_perOffCategory->crOffense_id}}">{{ $thisDefault_perOffCategory->crOffense_details}}</label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                {{-- <hr class="hr_gryv1">
                                                                <div class="form-group mx-0 mt-0 mb-1">
                                                                    <div class="custom-control custom-checkbox align-items-center">
                                                                        <input type="checkbox" id="selectAllDefault_minorOffenses" class="custom-control-input cursor_pointer">
                                                                        <label class="custom-control-label lightBlue_cardBody_chckboxLabel" for="selectAllDefault_minorOffenses">Select all Default {{ $offCategory_title}}.</label>
                                                                    </div>
                                                                </div> --}}
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
                                                    @endphp
                                                    <div class="row mt-2">
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <div class="card-body lightRed_cardBody">
                                                                <span class="lightRed_cardBody_redTitle mb-1">Custom {{ $offCategory_title}}: <i class="fa fa-info-circle cust_info_icon ml-1" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Custom {{ $offCategory_title }} are added offenses that are not based on the SDCA's Student Handbook A.Y. 2019-2020. Select {{ $offCategory_title }} you want to edit or delete."></i></span>
                                                                @foreach ($queryAllCustom_perOffCategory as $thisCustom_perOffCategory)
                                                                    <div class="form-group mx-0 mt-0 mb-1">
                                                                        <div class="custom-control custom-checkbox align-items-center">
                                                                            <input type="checkbox" value="{{$thisCustom_perOffCategory->crOffense_id}}" class="custom-control-input cursor_pointer" id="{{$thisCustom_perOffCategory->crOffense_id}}">
                                                                            <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="{{$thisCustom_perOffCategory->crOffense_id}}">{{ $thisCustom_perOffCategory->crOffense_details}}</label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                {{-- <hr class="hr_gryv1">
                                                                <div class="form-group mx-0 mt-0 mb-1">
                                                                    <div class="custom-control custom-checkbox align-items-center">
                                                                        <input type="checkbox" id="selectAllDefault_minorOffenses" class="custom-control-input cursor_pointer">
                                                                        <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="selectAllDefault_minorOffenses">Select all Cusotm {{ $offCategory_title}}.</label>
                                                                    </div>
                                                                </div> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <span class="cust_info_txtwicon2"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> Click on any {{ $offCategory_title }} for more options.</span>  
                                                        </div>
                                                    </div>
                                                @endif
                                            </form>
                                        </div>
                                        <div class="card-footer d-flex justify-content-between align-items-center px-0 pb-0">
                                            <div>
                                                <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-list-ul mr-1" aria-hidden="true"></i> {{ $txt_totalOffensesCount }} </span>  
                                                <span class="cust_info_txtwicon"><i class="fa fa-cog mr-1" aria-hidden="true"></i> {{ $txt_totalDefaultOffensesCount }} </span>  
                                                <span class="cust_info_txtwicon"><i class="fa fa-pencil-square-o mr-1" aria-hidden="true"></i> {{ $txt_totalCustomOffensesCount }} </span>  
                                            </div>
                                            <div>
                                                <button id="{{$this_offCategory->offCat_id}}" onclick="addNewOffensesDetails(this.id)" class="btn cust_btn_smcircle5" data-toggle="tooltip" data-placement="top" title="Add New {{ $offCategory_title}}?"><i class="nc-icon nc-simple-add" aria-hidden="true"></i></button>
                                                <button id="{{$this_offCategory->offCat_id}}" onclick="editSelectedOffenseDetails(this.id)" class="btn cust_btn_smcircle5" data-toggle="tooltip" data-placement="top" title="Edit Selected {{ $offCategory_title}}?"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                                <button id="{{$this_offCategory->offCat_id}}" onclick="tempDeleteSelectedOffenseDetails(this.id)" class="btn cust_btn_smcircle5" data-toggle="tooltip" data-placement="top" title="Delete Selected {{ $offCategory_title}}?"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                            </div>
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

            {{-- Deleted offenses --}}
            @if ($count_deletedOffenses > 0)
                @php
                    if($count_deletedOffenses > 1){
                        $txt_countDeletedOff = ''.$count_deletedOffenses . ' Deleted Offenses';
                    }else{
                        $txt_countDeletedOff = ''.$count_deletedOffenses . ' Deleted Offense';
                    }
                    // check if there are offenses categories
                    $queryCheck_hasOffCategories = App\Models\OffensesCategories::count();
                @endphp
                <input type="hidden" id="hasDeletedOffenses" value="{{$count_deletedOffenses}}">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="accordion gCardAccordions" id="deletedOffensesDisplayCollapseParent">
                            <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                                <div class="card-header p-0" id="deletedOffensesDisplayCollapseHeading">
                                    <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#deletedOffensesDisplayCollapseDiv" aria-expanded="true" aria-controls="deletedOffensesDisplayCollapseDiv">
                                        <div>
                                            <span class="card_body_title">Recently Deleted Offenses</span>
                                            <span class="card_body_subtitle">{{ $txt_countDeletedOff }}</span>
                                        </div>
                                        <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                                    </button>
                                </div>
                                <div id="deletedOffensesDisplayCollapseDiv" class="collapse gCardAccordions_collapse show cb_t0b15x25" aria-labelledby="deletedOffensesDisplayCollapseHeading" data-parent="#deletedOffensesDisplayCollapseParent">
                                    <div class="row mb-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="card card_gbr card_ofh shadow-none p-0 m-0 card_body_bg_gray2">
                                                @csrf
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-5-col-sm-12">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <select id="delOffFltr_DelStatus" name="delOffFltr_DelStatus" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                                            <option value="0" selected>All Deleted Violation</option>
                                                                            <option value="temp">Temporary Deleted</option>
                                                                            <option value="perm">Permanently Deleted</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                                    @if ($queryCheck_hasOffCategories > 0)
                                                                        @php
                                                                            // query all offense categories
                                                                            $query_OffCategoriesName = App\Models\OffensesCategories::select('offCategory')->get();
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <select id="delOffFltr_DelOffCategory" name="delOffFltr_DelOffCategory" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                                                <option value="0" selected>All Offense Categories</option>
                                                                                @foreach ($query_OffCategoriesName as $thisOption_offCategory)
                                                                                    @php
                                                                                        $toLower_optionOffCategory = strtolower($thisOption_offCategory->offCategory);
                                                                                    @endphp
                                                                                    <option value="{{$toLower_optionOffCategory}}">{{ucwords($thisOption_offCategory->offCategory)}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    @else
                                                                        <div class="form-group">
                                                                            <select id="" name="" class="form-control cust_selectDropdownBox2 drpdwn_arrow" disabled>
                                                                                <option value="0" selected disabled>No Categories Found</option>
                                                                            </select>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <select id="delOffFltr_DelOffType" name="delOffFltr_DelOffType" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                                            <option value="0" selected>All Offense Types</option>
                                                                            <option value="default">Default</option>
                                                                            <option value="custom">Custom</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                        <label id="delOffFltr_ASCOrderLabel" class="btn btn_svms_blue cust_btn_radio cbr_p" data-toggle="tooltip" data-placement="top" title="Ascending Order?">
                                                                            <input class="m-0 p-0" type="radio" name="delOffFltr_orderByRange" id="delOffFltr_ASCOrderRadio" value="asc" autocomplete="off"> <i class="fa fa-sort-amount-asc cbr_i" aria-hidden="true"></i>
                                                                        </label>
                                                                        <label id="delOffFltr_DESCOrderLabel" class="btn btn_svms_blue cust_btn_radio cbr_p active" data-toggle="tooltip" data-placement="top" title="Descending Order?">
                                                                            <input class="m-0 p-0" type="radio" name="delOffFltr_orderByRange" id="delOffFltr_DESCOrderRadio" value="desc" autocomplete="off" checked> <i class="fa fa-sort-amount-desc cbr_i" aria-hidden="true"></i>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-12 d-flex justify-content-end align-items-center">
                                                            <button type="button" id="resetDelOffensesFilter_btn" class="btn btn_svms_blue cust_bt_links shadow mr-3" disabled><i class="fa fa-refresh mr-1" aria-hidden="true"></i> Reset</button>
                                                            {{-- <button type="button" id="generateDelOffenses_btn" class="btn btn-success cust_bt_links shadow"><i class="nc-icon nc-single-copy-04 mr-1" aria-hidden="true"></i> Generate Report</button> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-8">
                                                    <div class="input-group cust_srchInpt_div">
                                                        <input id="delOffFltr_liveSearch" name="delOffFltr_liveSearch" type="text" class="form-control cust_srchUsersInpt_box" placeholder="Search Something..." />
                                                        <i class="nc-icon nc-zoom-split" aria-hidden="true"></i>    
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-4 d-flex justify-content-end align-items-center">
                                                    <span class="custom_label_subv1 mr-3">Number of Rows </span>
                                                    <div class="form-group my-0" style="width:80px;">
                                                        <select id="delOffFltr_numOfRows" class="form-control cust_selectDropdownBox2 drpdwn_arrow">
                                                            <option value="5" selected>5</option>
                                                            <option value="10">10</option>
                                                            <option value="25">25</option>
                                                            <option value="50">50</option>
                                                            <option value="75">75</option>
                                                            <option value="100">100</option>
                                                            <option value="250">250</option>
                                                            <option value="500">500</option>
                                                        </select>
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
                                                        <th class="pl12">#</th>
                                                        <th>Deletion type</th>
                                                        <th>Deleted by</th>
                                                        <th>Date Deleted & Reason</th>
                                                        <th>Offense Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tbody_svms_white" id="do_tableTbody">
                                                    {{-- ajax data table --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <div class="col-lg-4 col-md-4 col-sm-12 text-left">
                                            <span>Total Data: <span class="font-weight-bold" id="do_tableTotalData_count"> </span> </span>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-12 d-flex justify-content-end">
                                            @csrf
                                            <input type="hidden" name="do_hidden_page" id="do_hidden_page" value="1" />
                                            <div id="do_tablePagination">
                
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="hr_gryv2">
                                    <div class="card-footer d-flex justify-content-between align-items-center p-0">
                                        <div>
                                            <span class="cust_info_txtwicon"><i class="fa fa-trash-o mr-1" aria-hidden="true"></i> <span id="display_CountTempDeleted"> </span> </span>  
                                            <span class="cust_info_txtwicon"><i class="fa fa-trash mr-1" aria-hidden="true"></i> <span id="display_CountPermDeleted"> </span> </span>  
                                            <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-list-ul mr-1" aria-hidden="true"></i> {{ $txt_countDeletedOff }} </span>  
                                        </div>
                                        <div>
                                            <button onclick="recoverAllTempDeletedOffenses()" class="btn cust_btn_smcircle5" data-toggle="tooltip" data-placement="top" title="Recover All Temporary Deleted Offenses?"><i class="fa fa-external-link" aria-hidden="true"></i></button>
                                            <button onclick="permanentDelAllTempDelOffenses()" class="btn cust_btn_smcircle5" data-toggle="tooltip" data-placement="top" title="Permanently Delete All Offenses?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    {{-- no longer needed modals --}}
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
    {{-- add new offense details --}}
        <div class="modal fade" id="addNewOffenseDetailsFormModal" tabindex="-1" role="dialog" aria-labelledby="addNewOffenseDetailsFormModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="addNewOffenseDetailsFormModalLabel">Add New Offense Details?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="addNewOffenseDetailsFormModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- add new offense details end --}}
    {{-- edit selected offense --}}
        <div class="modal fade" id="editSelectedOffenseFormModal" tabindex="-1" role="dialog" aria-labelledby="editSelectedOffenseFormModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="editSelectedOffenseFormModalLabel">Edit Selected Offense?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="editSelectedOffenseFormModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- edit selected offense end --}}

    {{-- updated modals --}}
    {{-- add new offense details to selected category --}}
        <div class="modal fade" id="addNewOffenseDetails_toSelCategoryFormModal" tabindex="-1" role="dialog" aria-labelledby="addNewOffenseDetails_toSelCategoryFormModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="addNewOffenseDetails_toSelCategoryFormModalLabel">Add New Offense Details?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="addNewOffenseDetails_toSelCategoryFormModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- add new offense details to selected category end --}}
    {{-- edit selected offenses details --}}
        <div class="modal fade" id="editOffenseDetails_fromSelCategoryFormModal" tabindex="-1" role="dialog" aria-labelledby="editOffenseDetails_fromSelCategoryFormModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="editOffenseDetails_fromSelCategoryFormModalLabel">Edit Offense Details?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="editOffenseDetails_fromSelCategoryFormModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- edit selected offenses details end --}}
    {{-- temporary delete selected offense details --}}
        <div class="modal fade" id="tempDeleteOffenseDetails_fromSelCategoryFormModal" tabindex="-1" role="dialog" aria-labelledby="tempDeleteOffenseDetails_fromSelCategoryFormModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="tempDeleteOffenseDetails_fromSelCategoryFormModalLabel">Delete Offense Details?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="tempDeleteOffenseDetails_fromSelCategoryFormModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- temporary delete selected offense details end --}}

    {{-- recover all temporary deleted offenses confirmaiton modal --}}
        <div class="modal fade" id="recoverAllTempDeleteOffenses_ConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="recoverAllTempDeleteOffenses_ConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="recoverAllTempDeleteOffenses_ConfirmationModalLabel">Recover Deleted Offenses?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="recoverAllTempDeleteOffenses_ConfirmationModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- recover all temporary deleted offenses confirmaiton modal end --}}

    {{-- permanently delete all temporary deleted offenses confirmaiton modal --}}
        <div class="modal fade" id="PermDelAllTempDelOffenses_ConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="PermDelAllTempDelOffenses_ConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="PermDelAllTempDelOffenses_ConfirmationModalLabel">Permanently Delete Offenses?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="PermDelAllTempDelOffenses_ConfirmationModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- permanently delete all temporary deleted offenses confirmaiton modal end --}}

    {{-- view deleted offense details on modal modal --}}
        <div class="modal fade" id="viewDeletedOffenseDeetailsModal" tabindex="-1" role="dialog" aria-labelledby="viewDeletedOffenseDeetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="viewDeletedOffenseDeetailsModalLabel">Permanently Delete Offenses?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="viewDeletedOffenseDeetailsModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- view deleted offense details on modal modal end --}}

@endsection

@push('scripts')

{{-- no longer needed scripts --}}
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
            var register_new_category_name  = document.querySelector("#register_new_category_name");
            var addCategoryDetails_input  = document.querySelector("#addCategoryDetails_input");
            var categoryDetailsAdd_Btn = document.querySelector("#categoryDetailsAdd_Btn");
            var form_registerNewCategory  = document.querySelector("#form_registerNewCategory");
            var cancel_registerNewCategory_btn = document.querySelector("#cancel_registerNewCategory_btn");
            var process_registerNewCategory_btn = document.querySelector("#process_registerNewCategory_btn");
            var addedCategoryDetials_field = $('.addedCategoryDetials_field').filter(function() {
                    return this.value != '';
                });
            function disenAble_submitBtn(){
                if(addCategoryDetails_input.value === "" ||  register_new_category_name.value === "") {
                    process_registerNewCategory_btn.disabled = true;
                }else{
                    process_registerNewCategory_btn.disabled = false;
                }
                if(addCategoryDetails_input.value !== ""){
                    categoryDetailsAdd_Btn.disabled = false;
                }else{
                    categoryDetailsAdd_Btn.disabled = true;
                }
            }
            $(addCategoryDetails_input).keyup(function(){
                disenAble_submitBtn();
            });
            $(register_new_category_name).keyup(function(){
                disenAble_submitBtn();
            });
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
                                    '<input type="text" name="add_new_category_details[]" class="form-control input_grpInpt2 addedCategoryDetials_field" placeholder="Add New Category Details" aria-label="Add New Category Details" aria-describedby="new-category-details-input" required /> ' +
                                    '<div class="input-group-append"> ' +
                                        '<button class="btn btn_svms_blue m-0 btn_deleteAnother_input" type="button"><i class="nc-icon nc-simple-remove font-weight-bold" aria-hidden="true"></i></button> ' +
                                    '</div> ' +
                                '</div>';
            var x = 1;
            $(categoryDetailsAdd_Btn).click(function(){
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
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('offenses.add_new_offense_details_form') }}",
                method:"GET",
                data:{_token:_token},
                success: function(data){
                    $('#addNewOffenseDetailsFormModalHtmlData').html(data); 
                    $('#addNewOffenseDetailsFormModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#addNewOffenseDetailsFormModal').on('show.bs.modal', function () {
            var select_selectOffenseCategory = document.querySelector('#select_offense_category');
            var addNewOffenses_input  = document.querySelector("#addNewOffenses_input");
            var NewOffensesAdd_Btn = document.querySelector("#NewOffensesAdd_Btn");
            var form_registerNewOffenses  = document.querySelector("#form_registerNewOffenses");
            var cancel_registerNewOffenses_btn = document.querySelector("#cancel_registerNewOffenses_btn");
            var process_registerNewOffenses_btn = document.querySelector("#process_registerNewOffenses_btn");
            var addedOffenses_field = $('.addedOffenses_field').filter(function() {
                    return this.value != '';
                });
            function disenAble_submitBtn1(){
                if(addNewOffenses_input.value !== "") {
                    NewOffensesAdd_Btn.disabled = false;
                }else{
                    NewOffensesAdd_Btn.disabled = true;
                }
                if(addNewOffenses_input.value !== "" && select_selectOffenseCategory.value != 0) {
                    process_registerNewOffenses_btn.disabled = false;
                }else{
                    process_registerNewOffenses_btn.disabled = true;
                }
            }
            $(addNewOffenses_input).keyup(function(){
                disenAble_submitBtn1();
            });
            $(select_selectOffenseCategory).on('change paste keyup', function(){
                disenAble_submitBtn1();
            });
            // appending new input field
            function addOtherOffIndexing1(){
                i = 1;
                $(".addOtherOffIndex").each(function(){
                    $(this).html(i+1 + '.');
                    i++;
                });
            }
            var maxField = 10;
            var addedInputFields_div1 = document.querySelector('.addedInputFields_div1');
            var newInputField = '<div class="input-group mb-2">' +
                                    '<div class="input-group-append"> ' +
                                        '<span class="input-group-text txt_iptgrp_append2 addOtherOffIndex font-weight-bold">1. </span> ' +
                                    '</div> ' +
                                    '<input type="text" name="add_new_offenses[]" class="form-control input_grpInpt2 addedOffenses_field" placeholder="Add New Offenses" aria-label="Add New Offenses" aria-describedby="new-offenses-input" required /> ' +
                                    '<div class="input-group-append"> ' +
                                        '<button class="btn btn_svms_blue m-0 btn_deleteAnother_input1" type="button"><i class="nc-icon nc-simple-remove font-weight-bold" aria-hidden="true"></i></button> ' +
                                    '</div> ' +
                                '</div>';
            var x = 1;
            $(NewOffensesAdd_Btn).click(function(){
                if(x < maxField){
                    x++;
                    $(addedInputFields_div1).append(newInputField);
                    // console.log(x);
                }
                addOtherOffIndexing1();
            });
            $(addedInputFields_div1).on('click', '.btn_deleteAnother_input1', function(e){
                e.preventDefault();
                $(this).closest('.input_grpInpt2').value = '';
                $(this).closest('.input-group').last().remove();
                x--;
                // console.log('click');
                addOtherOffIndexing1();
            });
            // disable cancel and sibmit button on submit
            $(form_registerNewOffenses).submit(function(){
                process_registerNewOffenses_btn.disabled = true;
                cancel_registerNewOffenses_btn.disabled = true;
                return true;
            });
        });
    </script>
{{-- add new Category end --}}
{{-- edit offense details --}}
    <script>
        function editOffenseDetails(selected_crOffenseID){
            var selected_crOffenseID = selected_crOffenseID;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('offenses.edit_selected_offense_form') }}",
                method:"GET",
                data:{selected_crOffenseID:selected_crOffenseID,_token:_token},
                success: function(data){
                    $('#editSelectedOffenseFormModalHtmlData').html(data); 
                    $('#editSelectedOffenseFormModal').modal('show');
                }
            });
        }
    </script>
{{-- edit offense details end--}}

{{-- updated scripts --}}
{{-- add new Offense details --}}
    <script>
        function addNewOffensesDetails(sel_offCategory_id){
            var sel_offCategory_id = sel_offCategory_id;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('offenses.add_new_offense_details_to_selected_category_form') }}",
                method:"GET",
                data:{
                    sel_offCategory_id:sel_offCategory_id,
                    _token:_token
                    },
                success: function(data){
                    $('#addNewOffenseDetails_toSelCategoryFormModalHtmlData').html(data); 
                    $('#addNewOffenseDetails_toSelCategoryFormModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#addNewOffenseDetails_toSelCategoryFormModal').on('show.bs.modal', function () {
            var addNewOffenseDetails_input  = document.querySelector("#addNewOffenseDetails_input");
            var newOffenseDetailsAdd_Btn = document.querySelector("#newOffenseDetailsAdd_Btn");
            var form_registerNewOffenseDetails  = document.querySelector("#form_registerNewOffenseDetails");
            var cancel_registerNewOffenseDetails_btn = document.querySelector("#cancel_registerNewOffenseDetails_btn");
            var process_registerNewOffenseDetails_btn = document.querySelector("#process_registerNewOffenseDetails_btn");
            var addedNewOffenseDetails_field = $('.addedNewOffenseDetails_field').filter(function() {
                    return this.value != '';
                });
            function disenAble_submitBtn2(){
                if(addNewOffenseDetails_input.value !== "") {
                    newOffenseDetailsAdd_Btn.disabled = false;
                    process_registerNewOffenseDetails_btn.disabled = false;
                }else{
                    newOffenseDetailsAdd_Btn.disabled = true;
                    process_registerNewOffenseDetails_btn.disabled = true;
                }
            }
            $(addNewOffenseDetails_input).keyup(function(){
                disenAble_submitBtn2();
            });
            // appending new input field
            function addedNewOffInputIndexing(){
                i = 1;
                $(".addedNewOffInputIndex").each(function(){
                    $(this).html(i+1 + '.');
                    i++;
                });
            }
            var maxField = 10;
            var nOD_addedInputFields_div = document.querySelector('.nOD_addedInputFields_div');
            var newInputField = '<div class="input-group mb-2">' +
                                    '<div class="input-group-append"> ' +
                                        '<span class="input-group-text txt_iptgrp_append2 addedNewOffInputIndex font-weight-bold">1. </span> ' +
                                    '</div> ' +
                                    '<input type="text" name="add_new_offense_details[]" class="form-control input_grpInpt2 addedNewOffenseDetails_field" placeholder="Add New Offense Details" aria-label="Add New Offense Details" aria-describedby="new-offense-details-input" required /> ' +
                                    '<div class="input-group-append"> ' +
                                        '<button class="btn btn_svms_blue m-0 btn_deleteAnother_input2" type="button"><i class="nc-icon nc-simple-remove font-weight-bold" aria-hidden="true"></i></button> ' +
                                    '</div> ' +
                                '</div>';
            var x = 1;
            $(newOffenseDetailsAdd_Btn).click(function(){
                if(x < maxField){
                    x++;
                    $(nOD_addedInputFields_div).append(newInputField);
                    // console.log(x);
                }
                addedNewOffInputIndexing();
            });
            $(nOD_addedInputFields_div).on('click', '.btn_deleteAnother_input2', function(e){
                e.preventDefault();
                $(this).closest('.input_grpInpt2').value = '';
                $(this).closest('.input-group').last().remove();
                x--;
                // console.log('click');
                addedNewOffInputIndexing();
            });
            // disable cancel and sibmit button on submit
            $(form_registerNewOffenseDetails).submit(function(){
                process_registerNewOffenseDetails_btn.disabled = true;
                cancel_registerNewOffenseDetails_btn.disabled = true;
                return true;
            });
        });
    </script>
{{-- add new Offense details end --}}

{{-- edit selected offense details --}}
    <script>
        function editSelectedOffenseDetails(sel_offCategory_id){
            var sel_offCategory_id = sel_offCategory_id;
            var sel_offDetails_ids = [];
            var sel_offCategoryName_txt = document.getElementById(sel_offCategory_id+'_offCategoryName_txt').value;
            var _token = $('input[name="_token"]').val();

            $('#'+sel_offCategory_id+'_offCategoryForm input:checked').each(function() {
                sel_offDetails_ids.push($(this).attr('value'));
            });

            if(sel_offDetails_ids === undefined || sel_offDetails_ids.length === 0){
                // console.log('____________________________________________');
                // console.log('selected category id: ' + sel_offCategory_id);
                // console.log('selected offense ids: ' + sel_offDetails_ids);
                // console.log('');
                alert('Please Select ' + sel_offCategoryName_txt + ' first!');
            }else{
                // console.log('____________________________________________');
                // console.log('selected category id: ' + sel_offCategory_id);
                // console.log('selected offense ids: ' + sel_offDetails_ids);
                // console.log('');
                $.ajax({
                    url:"{{ route('offenses.edit_selected_offense_details_form') }}",
                    method:"GET",
                    data:{
                        sel_offCategory_id:sel_offCategory_id,
                        sel_offDetails_ids:sel_offDetails_ids,
                        _token:_token
                        },
                    success: function(data){
                        $('#editOffenseDetails_fromSelCategoryFormModalHtmlData').html(data); 
                        $('#editOffenseDetails_fromSelCategoryFormModal').modal('show');
                    }
                });
            }
        }
    </script>
    <script>
        $('#editOffenseDetails_fromSelCategoryFormModal').on('show.bs.modal', function () {
            var form_editOffenseDetails  = document.querySelector("#form_editOffenseDetails");
            var cancel_editOffenseDetails_btn = document.querySelector("#cancel_editOffenseDetails_btn");
            var process_editOffenseDetails_btn = document.querySelector("#process_editOffenseDetails_btn");
            // serialized form
            $(form_editOffenseDetails).each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change paste keyup select', function(){
                $(this).find(process_editOffenseDetails_btn).prop('disabled', $(this).serialize() == $(this).data('serialized'));
            }).find(process_editOffenseDetails_btn).prop('disabled', true);

            // disable cancel and sibmit button on submit
            $(form_editOffenseDetails).submit(function(){
                process_editOffenseDetails_btn.disabled = true;
                cancel_editOffenseDetails_btn.disabled = true;
                return true;
            });
        });
    </script>
{{-- edit selected Offense details end --}}

{{-- temporary delete selected Offense details --}}
    <script>
        function tempDeleteSelectedOffenseDetails(sel_offCategory_id){
            var sel_offCategory_id = sel_offCategory_id;
            var sel_offDetails_ids = [];
            var sel_offCategoryName_txt = document.getElementById(sel_offCategory_id+'_offCategoryName_txt').value;
            var _token = $('input[name="_token"]').val();

            $('#'+sel_offCategory_id+'_offCategoryForm input:checked').each(function() {
                sel_offDetails_ids.push($(this).attr('value'));
            });

            if(sel_offDetails_ids === undefined || sel_offDetails_ids.length === 0){
                // console.log('____temporary delete offense details___________');
                // console.log('selected category id: ' + sel_offCategory_id);
                // console.log('selected offense ids: ' + sel_offDetails_ids);
                // console.log('');
                alert('Please Select ' + sel_offCategoryName_txt + ' first!');
            }else{
                // console.log('____temporary delete offense details___________');
                // console.log('selected category id: ' + sel_offCategory_id);
                // console.log('selected offense ids: ' + sel_offDetails_ids);
                // console.log('');
                $.ajax({
                    url:"{{ route('offenses.temporary_delete_selected_offense_details_confirmation_modal') }}",
                    method:"GET",
                    data:{
                        sel_offCategory_id:sel_offCategory_id,
                        sel_offDetails_ids:sel_offDetails_ids,
                        _token:_token
                        },
                    success: function(data){
                        $('#tempDeleteOffenseDetails_fromSelCategoryFormModalHtmlData').html(data); 
                        $('#tempDeleteOffenseDetails_fromSelCategoryFormModal').modal('show');
                    }
                });
            }
        }
    </script>
    <script>
        $('#tempDeleteOffenseDetails_fromSelCategoryFormModal').on('show.bs.modal', function () {
            var form_tempDeleteOffenseDetails  = document.querySelector("#form_tempDeleteOffenseDetails");
            var cancel_tempDeleteOffenseDetails_btn = document.querySelector("#cancel_tempDeleteOffenseDetails_btn");
            var process_tempDeleteOffenseDetails_btn = document.querySelector("#process_tempDeleteOffenseDetails_btn");
            var temp_delete_offenses_reason = document.querySelector("#temp_delete_offenses_reason");
            // option selection
            function dis_en_submit_process_tempDeleteOffenseDetails_btn(){
                var has_temp_deleteSingle_offense = 0;
                $(".temp_deleteSingle_offense").each(function(){
                    if(this.checked){
                        has_temp_deleteSingle_offense = 1;
                    }
                });
                if(temp_delete_offenses_reason.value !== "" && has_temp_deleteSingle_offense != 0){
                    process_tempDeleteOffenseDetails_btn.disabled = false;
                }else{
                    process_tempDeleteOffenseDetails_btn.disabled = true;
                }
            }
            $(temp_delete_offenses_reason).keyup(function(){
                dis_en_submit_process_tempDeleteOffenseDetails_btn();
            });
            $("#temp_deleteAll_offenses").change(function(){
                if(this.checked){
                $(".temp_deleteSingle_offense").each(function(){
                    this.checked=true;
                })              
                }else{
                $(".temp_deleteSingle_offense").each(function(){
                    this.checked=false;
                })              
                }
                dis_en_submit_process_tempDeleteOffenseDetails_btn();
            });
            $(".temp_deleteSingle_offense").click(function () {
                if ($(this).is(":checked")){
                var isDeleteAllChecked = 0;
                $(".temp_deleteSingle_offense").each(function(){
                    if(!this.checked)
                    isDeleteAllChecked = 1;
                })              
                if(isDeleteAllChecked == 0){ $("#temp_deleteAll_offenses").prop("checked", true); }     
                }else {
                $("#temp_deleteAll_offenses").prop("checked", false);
                }
                dis_en_submit_process_tempDeleteOffenseDetails_btn();
            });
            // disable cancel and sibmit button on submit
            $(form_tempDeleteOffenseDetails).submit(function(){
                process_tempDeleteOffenseDetails_btn.disabled = true;
                cancel_tempDeleteOffenseDetails_btn.disabled = true;
                return true;
            });
        });
    </script>
{{-- temporary delete selected Offense details --}}

{{-- DELETED OFFENSES --}}
{{-- load deleted offenses table --}}
    <script>
        $(document).ready(function(){
            var hasDeletedOffenses = document.getElementById('hasDeletedOffenses').value;
            if(hasDeletedOffenses > 0 || hasDeletedOffenses != null){
                load_deletedOffenses_table();

                function load_deletedOffenses_table(){
                    // get all filtered values
                    var do_search = document.getElementById('delOffFltr_liveSearch').value;
                    var do_numOfRows = document.getElementById('delOffFltr_numOfRows').value;
                    var do_delStatus = document.getElementById('delOffFltr_DelStatus').value;
                    var do_offCategory = document.getElementById('delOffFltr_DelOffCategory').value;
                    var do_offType = document.getElementById('delOffFltr_DelOffType').value;
                    var do_orderByRange = document.querySelector('input[type=radio][name=delOffFltr_orderByRange]:checked').value;
                    var page = document.getElementById("do_hidden_page").value;

                    do_numOfRows = parseInt(do_numOfRows);

                    // ajax request
                    $.ajax({
                        url:"{{ route('offenses.load_deleted_offenses_table') }}",
                        method:"GET",
                        data:{
                            do_search:do_search,
                            do_numOfRows:do_numOfRows,
                            do_delStatus:do_delStatus,
                            do_offCategory:do_offCategory,
                            do_offType:do_offType,
                            do_orderByRange:do_orderByRange,
                            hasDeletedOffenses:hasDeletedOffenses,
                            page:page
                        },
                        dataType:'json',
                        success:function(do_data){
                            $('#do_tableTbody').html(do_data.do_table);
                            $('#display_CountTempDeleted').html(do_data.do_temp_deleted_result);
                            $('#display_CountPermDeleted').html(do_data.do_perm_deleted_result);
                            $('#do_tableTotalData_count').html(do_data.do_totalDataFound);
                            $('#do_tablePagination').html(do_data.do_pagination);
                        }
                    });

                    // reset button
                    if(do_delStatus != 0 || do_offCategory != 0 || do_offType != 0 || do_orderByRange != 'desc'){
                        $('#resetDelOffensesFilter_btn').prop('disabled', false);
                    }else{
                        $('#resetDelOffensesFilter_btn').prop('disabled', true);
                    }
                }
                $(document).ready(function(){
                    setInterval(load_deletedOffenses_table,30000);
                });

                // ajax pagination
                    $(window).on('hashchange', function() {
                        if (window.location.hash) {
                            var page = window.location.hash.replace('#', '');
                            if (page == Number.NaN || page <= 0) {
                                return false;
                            }else{
                                do_getData(page);
                            }
                        }
                    });
                    $('#do_tablePagination').on('click', '.pagination a', function(event){
                        event.preventDefault();
                        var page = $(this).attr('href').split('page=')[1];
                        $('#do_hidden_page').val(page);

                        load_deletedOffenses_table();
                        do_getData(page);
                        $('li.page-item').removeClass('active');
                        $(this).parent('li.page-item').addClass('active');
                    });
                    function do_getData(page){
                        $.ajax({
                            url: '?page=' + page,
                            type: "get",
                            datatype: "html"
                        }).done(function(data){
                            location.hash = page;
                        })
                        .fail(function(jqXHR, ajaxOptions, thrownError){
                            // alert('No response from server');
                            location.hash = page;
                        });
                    }
                // pagination end
                
                // live search filter
                    $('#delOffFltr_liveSearch').on('keyup', function(){
                        load_deletedOffenses_table();
                    });
                // live search filter end

                // number of rows
                    $('#delOffFltr_numOfRows').on('change paste keyup', function(){
                        var selectedNumRows = $(this).val();
                        if(selectedNumRows != 5){
                            $(this).addClass('cust_input_hasvalue');
                        }else{
                            $(this).removeClass('cust_input_hasvalue');
                        }
                        // set pagination to page 1
                        $('#do_hidden_page').val(1);
                        load_deletedOffenses_table();
                    });
                // number of rows end

                // deletion type
                    $('#delOffFltr_DelStatus').on('change paste keyup', function(){
                        var selectedDelOffStat = $(this).val();
                        if(selectedDelOffStat != 0){
                            $(this).addClass('cust_input_hasvalue');
                        }else{
                            $(this).removeClass('cust_input_hasvalue');
                        }
                        // set pagination to page 1
                        $('#do_hidden_page').val(1);
                        load_deletedOffenses_table();
                    });
                // deletion type end 

                // offense categories
                    $('#delOffFltr_DelOffCategory').on('change paste keyup', function(){
                        var selectedDelOffCategory = $(this).val();
                        if(selectedDelOffCategory != 0){
                            $(this).addClass('cust_input_hasvalue');
                        }else{
                            $(this).removeClass('cust_input_hasvalue');
                        }
                        // set pagination to page 1
                        $('#do_hidden_page').val(1);
                        load_deletedOffenses_table();
                    });
                // offense categories end

                // offense types
                    $('#delOffFltr_DelOffType').on('change paste keyup', function(){
                        var selectedDelOffType = $(this).val();
                        if(selectedDelOffType != 0){
                            $(this).addClass('cust_input_hasvalue');
                        }else{
                            $(this).removeClass('cust_input_hasvalue');
                        }
                        // set pagination to page 1
                        $('#do_hidden_page').val(1);
                        load_deletedOffenses_table();
                    });
                // offense types end

                // filter ASC/DESC order
                    $('input[type=radio][name=delOffFltr_orderByRange]').change(function() {
                        // set pagination to page 1
                        $('#do_hidden_page').val(1);
                        load_deletedOffenses_table();
                    });
                // filter ASC/DESC order end

                // reset filter
                    $('#resetDelOffensesFilter_btn').on('click', function(){
                        // disable reset button
                        $(this).prop('disabled', true);
                        // deletion type
                        document.getElementById("delOffFltr_DelStatus").classList.remove("cust_input_hasvalue");
                        $('#delOffFltr_DelStatus').val(0);
                        // offense category
                        document.getElementById("delOffFltr_DelOffCategory").classList.remove("cust_input_hasvalue");
                        $('#delOffFltr_DelOffCategory').val(0);
                        // offense type
                        document.getElementById("delOffFltr_DelOffType").classList.remove("cust_input_hasvalue");
                        $('#delOffFltr_DelOffType').val(0);
                        // filter SC/DESC
                        document.getElementById("delOffFltr_ASCOrderLabel").classList.remove("active");
                        document.getElementById("delOffFltr_DESCOrderLabel").classList.add("active");
                        var fltrBack_toDESC = document.getElementById('delOffFltr_DESCOrderRadio');
                        fltrBack_toDESC.checked = true;
                        // set pagination to page 1
                        $('#do_hidden_page').val(1);
                        // load table
                        load_deletedOffenses_table();
                    });
                // reset filter end
            }
        });
    </script>
{{-- load deleted offenses table end --}}
{{-- table ro click - view deleted offense details --}}
    <script>
        function viewDeletedOffensesDetails(sel_delID){
            var sel_delID = sel_delID;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('offenses.view_deleted_offense_details_modal') }}",
                method:"GET",
                data:{
                    sel_delID:sel_delID,
                    _token:_token
                    },
                success: function(data){
                    $('#viewDeletedOffenseDeetailsModalHtmlData').html(data); 
                    $('#viewDeletedOffenseDeetailsModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#viewDeletedOffenseDeetailsModal').on('show.bs.modal', function () {
            var form_singleRecoveryOffense = document.querySelector("#form_singleRecoveryOffense");
            var form_singlePermDeletionOffense = document.querySelector("#form_singlePermDeletionOffense");
            var submit_singleRecoveryOffense_btn = document.querySelector("#submit_singleRecoveryOffense_btn");
            var submit_singlePermDeletetionOffense_btn = document.querySelector("#submit_singlePermDeletetionOffense_btn");
            var close_singlePermDelnRecoveryOffense_btn = document.querySelector("#close_singlePermDelnRecoveryOffense_btn");
            // disable close and submit buttons
            $(form_singleRecoveryOffense).submit(function(){
                submit_singlePermDeletetionOffense_btn.disabled = true;
                submit_singleRecoveryOffense_btn.disabled = true;
                close_singlePermDelnRecoveryOffense_btn.disabled = true;
                return true;
            });
            $(form_singlePermDeletionOffense).submit(function(){
                submit_singlePermDeletetionOffense_btn.disabled = true;
                submit_singleRecoveryOffense_btn.disabled = true;
                close_singlePermDelnRecoveryOffense_btn.disabled = true;
                return true;
            });
        });
    </script>
{{-- table ro click - view deleted offense details end --}}

{{-- recover all temporary deleted offenses confirmation on modal --}}
    <script>
        function recoverAllTempDeletedOffenses(){
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('offenses.recover_all_temporary_deleted_offenses_confirmation') }}",
                method:"GET",
                data:{
                    _token:_token
                    },
                success: function(data){
                    $('#recoverAllTempDeleteOffenses_ConfirmationModalHtmlData').html(data); 
                    $('#recoverAllTempDeleteOffenses_ConfirmationModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#recoverAllTempDeleteOffenses_ConfirmationModal').on('show.bs.modal', function () {
            var form_recoverAllTempDeletedOffenses  = document.querySelector("#form_recoverAllTempDeletedOffenses");
            var cancel_recoverTempDeletedOff_btn = document.querySelector("#cancel_recoverTempDeletedOff_btn");
            var process_recoverTempDeletedOff_btn = document.querySelector("#process_recoverTempDeletedOff_btn");
            // option selection
            function dis_en_submit_process_recoverTempDeletedOff_btn(){
                var has_recoverTempDelOffSingle = 0;
                $(".recoverTempDelOffSingle").each(function(){
                    if(this.checked){
                        has_recoverTempDelOffSingle = 1;
                    }
                });
                if(has_recoverTempDelOffSingle != 0){
                    process_recoverTempDeletedOff_btn.disabled = false;
                }else{
                    process_recoverTempDeletedOff_btn.disabled = true;
                }
            }
            $("#recoverAll_TempDeleted").change(function(){
                if(this.checked){
                $(".recoverTempDelOffSingle").each(function(){
                    this.checked=true;
                })              
                }else{
                $(".recoverTempDelOffSingle").each(function(){
                    this.checked=false;
                })              
                }
                dis_en_submit_process_recoverTempDeletedOff_btn();
            });
            $(".recoverTempDelOffSingle").click(function () {
                if ($(this).is(":checked")){
                var isRecoverAllChecked = 0;
                $(".recoverTempDelOffSingle").each(function(){
                    if(!this.checked)
                    isRecoverAllChecked = 1;
                })              
                if(isRecoverAllChecked == 0){ $("#recoverAll_TempDeleted").prop("checked", true); }     
                }else {
                $("#recoverAll_TempDeleted").prop("checked", false);
                }
                dis_en_submit_process_recoverTempDeletedOff_btn();
            });
            // disable cancel and sibmit button on submit
            $(form_recoverAllTempDeletedOffenses).submit(function(){
                process_recoverTempDeletedOff_btn.disabled = true;
                cancel_recoverTempDeletedOff_btn.disabled = true;
                return true;
            });
        });
    </script>
{{-- recover all temporary deleted offenses confirmation on modal end --}}

{{-- permanently delete all temporary deleted offenses confirmation on modal --}}
    <script>
        function permanentDelAllTempDelOffenses(){
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('offenses.permanent_delete_all_temporary_deleted_offenses_confirmation') }}",
                method:"GET",
                data:{
                    _token:_token
                    },
                success: function(data){
                    $('#PermDelAllTempDelOffenses_ConfirmationModalHtmlData').html(data); 
                    $('#PermDelAllTempDelOffenses_ConfirmationModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#PermDelAllTempDelOffenses_ConfirmationModal').on('show.bs.modal', function () {
            var form_permanentDelAllTempDeletedOffenses  = document.querySelector("#form_permanentDelAllTempDeletedOffenses");
            var cancel_permanentDelAllTempDelOff_btn = document.querySelector("#cancel_permanentDelAllTempDelOff_btn");
            var process_permanentDelAllTempDelOff_btn = document.querySelector("#process_permanentDelAllTempDelOff_btn");
            // option selection
            function dis_en_submit_process_permanentDelAllTempDelOff_btn(){
                var has_permDelAllpermDelTempDelOffSingle = 0;
                $(".permDelAllpermDelTempDelOffSingle").each(function(){
                    if(this.checked){
                        has_permDelAllpermDelTempDelOffSingle = 1;
                    }
                });
                if(has_permDelAllpermDelTempDelOffSingle != 0){
                    process_permanentDelAllTempDelOff_btn.disabled = false;
                }else{
                    process_permanentDelAllTempDelOff_btn.disabled = true;
                }
            }
            $("#permDeleteAll_TempDeleted").change(function(){
                if(this.checked){
                $(".permDelAllpermDelTempDelOffSingle").each(function(){
                    this.checked=true;
                })              
                }else{
                $(".permDelAllpermDelTempDelOffSingle").each(function(){
                    this.checked=false;
                })              
                }
                dis_en_submit_process_permanentDelAllTempDelOff_btn();
            });
            $(".permDelAllpermDelTempDelOffSingle").click(function () {
                if ($(this).is(":checked")){
                var ispermDelAllChecked = 0;
                $(".permDelAllpermDelTempDelOffSingle").each(function(){
                    if(!this.checked)
                    ispermDelAllChecked = 1;
                })              
                if(ispermDelAllChecked == 0){ $("#permDeleteAll_TempDeleted").prop("checked", true); }     
                }else {
                $("#permDeleteAll_TempDeleted").prop("checked", false);
                }
                dis_en_submit_process_permanentDelAllTempDelOff_btn();
            });
            // disable cancel and sibmit button on submit
            $(form_permanentDelAllTempDeletedOffenses).submit(function(){
                process_permanentDelAllTempDelOff_btn.disabled = true;
                cancel_permanentDelAllTempDelOff_btn.disabled = true;
                return true;
            });
        });
    </script>
{{-- permanently delete all temporary deleted offenses confirmation on modal end --}}

@endpush