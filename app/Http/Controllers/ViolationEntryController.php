<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Students;
use App\Models\Users;

class ViolationEntryController extends Controller
{
    public function index(){
        return view('violation_entry.index');
    }

    // search violators
    public function search_violators(Request $request){
        if($request->ajax()){
            $output = '';
            $violators_query = $request->get('violators_query');
            if($violators_query != ''){
                $data = Users::select('id', 'user_sdca_id', 'user_image', 'user_lname', 'user_fname', 'user_gender')
                            ->orWhere('user_sdca_id', 'like', '%'.$violators_query.'%')
                            ->orWhere('user_lname', 'like', '%'.$violators_query.'%')
                            ->orWhere('user_fname', 'like', '%'.$violators_query.'%')
                            ->orderBy('id', 'asc')
                            ->get();
                $total_row = $data->count();
                $sq = "'";
                if($total_row > 0){
                    $output .= '<div class="list-group mt-3 shadow cust_list_group_ve" id="displaySearchViolators_results">';
                    foreach($data as $result){
                        $output .= '
                            <a href="#" id="'.$result->id.'" onclick="addViolator(this.id)" class="list-group-item list-group-item-action cust_lg_item_ve">
                                <div class="display_user_image_div text-center">
                                    <img class="display_violator_image shadow-sm" src="'.asset('storage/svms/user_images/'.$result->user_image.'').'" alt="violator'.$sq.'s image">
                                </div>
                                <div class="information_div">
                                    <span class="li_info_title">'.preg_replace('/('.$violators_query.')/i','<span class="red_highlight">$1</span>', $result->user_fname) . ' ' . preg_replace('/('.$violators_query.')/i','<span class="red_highlight">$1</span>', $result->user_lname).'</span>
                                    <span class="li_info_subtitle"><span class="text_svms_blue">'.preg_replace('/('.$violators_query.')/i','<span class="red_highlight">$1</span>', $result->user_sdca_id) . ' </span> | SBCS - BSIT 4A | Male</span>
                                </div>
                            </a>
                        ';
                    }
                    $output .= '</div';
                }else{
                    $output .= '
                        <div class="no_data_fount_list mt-3">
                            <i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> No Match Found for <span class="font-weight-bold"> ' . $violators_query.'...</span>
                        </div>
                    ';
                }
                $data = array(
                    'violators_results' => $output
                );
                echo json_encode($data);
            }
        }
    }

    // violation form
    public function open_violatin_form_modal(Request $request){
        // get all selected violators
        $get_all_violators = json_decode(json_encode($request->get('violators_ids')));
        $now_timestamp     = now();
        $sq                = "'";

        $output = '';
        $output .= '
            <div class="modal-body pt-0">
                <div class="row d-flex justify-content-center text-center mb-3">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <img class="sdca_logo_img" src="'.asset("storage/svms/sdca_images/sdca_logo.jpg").'" alt="SDCA Logo">
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
                                                    <img class="display_violator_image2 shadow-sm" src="'.asset("storage/svms/user_images/default_student_img.jpg").'" alt="student'.$sq.'s image">
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
                                                    <img class="display_violator_image2 shadow-sm" src="'.asset("storage/svms/user_images/default_student_img.jpg").'" alt="student'.$sq.'s image">
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
                                        <input type="checkbox" name="less_serious_offenses[]" value="Wearing somebody else'.$sq.'s ID" class="custom-control-input cursor_pointer" id="lso_1">
                                        <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="lso_1">Wearing somebody else'.$sq.'s ID</label>
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
                            <span class="cust_info_txtwicon2 font-weight-bold"><i class="nc-icon nc-calendar-60 mr-1" aria-hidden="true"></i> '.date("F d, Y", strtotime($now_timestamp)) . ' -  ' . date("D", strtotime($now_timestamp)) . ' at ' . date("g:i A", strtotime($now_timestamp)) .'</span>
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
        ';
        return $output;
    }
}
