<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Students;
use App\Models\Users;
use App\Models\Userroles;
use App\Models\Useractivites;
use App\Models\OffensesCategories;
use App\Models\CreatedOffenses;
use App\Models\Violations;
use Illuminate\Mail\Mailable;

class ViolationEntryController extends Controller
{
    public function index(){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('profile', $get_uRole_access)){
            return view('violation_entry.index');
        }else{
            return view('profile.access_denied');
        }
    }

    // search violators
    public function search_violators(Request $request){
        if($request->ajax()){
            $output = '';
            $violators_query = $request->get('violators_query');
            if($violators_query != ''){
                $data = Students::select('Student_Number', 'First_Name', 'Middle_Name', 'Last_Name', 'Gender', 'School_Name', 'Course', 'YearLevel', 'Student_Image', 'Status')
                            ->orWhere('Student_Number', 'like', '%'.$violators_query.'%')
                            ->orWhere('First_Name', 'like', '%'.$violators_query.'%')
                            ->orWhere('Middle_Name', 'like', '%'.$violators_query.'%')
                            ->orWhere('Last_Name', 'like', '%'.$violators_query.'%')
                            ->where('Status', '=', 1)
                            ->orderBy('Student_Number', 'asc')
                            ->limit(5)
                            ->get();
                $sq = "'";
                if(count($data) > 0){
                    $output .= '<div class="list-group mt-3 shadow cust_list_group_ve" id="displaySearchViolators_results">';
                    foreach($data as $result){
                        // year level
                        if($result->YearLevel === '1'){
                            $yearLevel_txt = '1st Year';
                        }else if($result->YearLevel === '2'){
                            $yearLevel_txt = '2nd Year';
                        }else if($result->YearLevel === '3'){
                            $yearLevel_txt = '3rd Year';
                        }else if($result->YearLevel === '4'){
                            $yearLevel_txt = '4th Year';
                        }else if($result->YearLevel === '5'){
                            $yearLevel_txt = '5th Year';
                        }else{
                            $yearLevel_txt = $result->YearLevel . ' Year';
                        }
                        // check student's image (use default image if student has no image from database)
                        if(!is_null($result->Student_Image) OR !empty($result->Student_Image)){
                            $display_violator_image = $result->Student_Image;
                        }else{
                            $display_violator_image = 'default_student_img.jpg';
                        }
                        $output .= '
                            <a href="#" id="'.$result->Student_Number.'" onclick="addViolator(this.id)" class="list-group-item list-group-item-action cust_lg_item_ve">
                                <div class="display_user_image_div text-center">
                                    <img class="display_violator_image shadow-sm" src="'.asset('storage/svms/sdca_images/registered_students_imgs/'.$display_violator_image.'').'" alt="violator'.$sq.'s image">
                                </div>
                                <div class="information_div">
                                    <span class="li_info_title">
                                        '.preg_replace('/('.$violators_query.')/i','<span class="red_highlight">$1</span>', $result->First_Name) . ' 
                                        ' . preg_replace('/('.$violators_query.')/i','<span class="red_highlight">$1</span>', $result->Middle_Name) . ' 
                                        ' . preg_replace('/('.$violators_query.')/i','<span class="red_highlight">$1</span>', $result->Last_Name) . '
                                    </span>
                                    <span class="li_info_subtitle"><span class="text_svms_blue">'.preg_replace('/('.$violators_query.')/i','<span class="red_highlight">$1</span>', $result->Student_Number) . ' </span> | ' . $result->Course . ' - ' . $yearLevel_txt . ' | ' . $result->Gender.'</span>
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

    // get selected student's info for pill display
    public function get_selected_student_info(Request $request){
        if($request->ajax()){
            // get student's ID
            $sel_student_id = $request->get('violatorID');
            // get student's information
            $get_selected_student_info = Students::select('Student_Number', 'First_Name', 'Middle_Name', 'Last_Name', 'Gender', 'School_Name', 'Course', 'YearLevel', 'Student_Image', 'Status')->where('Student_Number', $sel_student_id)->first();
            $get_student_image         = $get_selected_student_info->Student_Image;
            $get_student_fname         = $get_selected_student_info->First_Name;
            $get_student_lname         = $get_selected_student_info->Last_Name;
            // check student's image (use default image if student has no image from database)
            if(!is_null($get_selected_student_info->Student_Image) OR !empty($get_selected_student_info->Student_Image)){
                $display_student_image = $get_student_image;
            }else{
                $display_student_image = 'default_student_img.jpg';
            }

            $data = array(
                'sel_student_lname'   => $get_student_lname,
                'sel_student_fname'   => $get_student_fname,
                'sel_student_image'   => $display_student_image
               );
         
            echo json_encode($data);
        }
    }

    // violation form
    public function open_violation_form_modal(Request $request){
        // get all selected violators
        $get_all_violators = $request->get('violators_ids');
        $count_sel_v       = count(explode(",",$get_all_violators));
        $now_timestamp     = now();
        $sq                = "'";
        if($count_sel_v > 1){
            $s = 's';
        }else{
            $s = '';
        }
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
                                                <span class="li_info_subtitle">'.$count_sel_v . ' student'.$s.' selected</span>
                                            </div>
                                            <i class="nc-icon nc-minimal-up"></i>
                                        </button>
                                    </h2>
                                </div>
                                <div id="selectedViolatorsCollapse_Div" class="collapse cust_collapse_active show cb_t0b12y15 bg_F4F4F5" aria-labelledby="empTypeRolesCollapse_heading" data-parent="#empTypeRolesModalAccordion_Parent">
                                    <div class="row mt-0">
                                    ';
                                    if($count_sel_v > 0){
                                        foreach(json_decode($get_all_violators, true) as $violator){
                                            // get student's information
                                            $stud_info   = Students::select('Student_Number', 'First_Name', 'Middle_Name', 'Last_Name', 'Gender', 'School_Name', 'Course', 'YearLevel', 'Student_Image', 'Status')->where('Student_Number', $violator)->first();
                                            $stud_id     = $stud_info->Student_Number;
                                            $stud_lname  = $stud_info->Last_Name;
                                            $stud_mname  = $stud_info->Middle_Name;
                                            $stud_fname  = $stud_info->First_Name;
                                            $stud_image  = $stud_info->Student_Image;
                                            $stud_gender = $stud_info->Gender;
                                            $stud_school = $stud_info->School_Name;
                                            $stud_course = $stud_info->Course;
                                            $stud_yrLvl  = $stud_info->YearLevel;
                                            // check student's image (use default image if student has no image from database)
                                            if(!is_null($stud_image) OR !empty($stud_image)){
                                                $display_student_image = $stud_image;
                                            }else{
                                                $display_student_image = 'default_student_img.jpg';
                                            }
                                            // year level
                                            if($stud_yrLvl === '1'){
                                                $yearLevel_txt = '1st Year';
                                            }else if($stud_yrLvl === '2'){
                                                $yearLevel_txt = '2nd Year';
                                            }else if($stud_yrLvl === '3'){
                                                $yearLevel_txt = '3rd Year';
                                            }else if($stud_yrLvl === '4'){
                                                $yearLevel_txt = '4th Year';
                                            }else if($stud_yrLvl === '5'){
                                                $yearLevel_txt = '5th Year';
                                            }else{
                                                $yearLevel_txt = $stud_yrLvl . ' Year';
                                            }
                                            // course text limit
                                            if($stud_course === 'BS Education'){
                                                $lim_stud_course = 'BS Educ';
                                            }else if($stud_course === 'BS Psychology'){
                                                $lim_stud_course = 'BS Psych';
                                            }else if($stud_course === 'BA Communication'){
                                                $lim_stud_course = 'BA Comm';
                                            }else if($stud_course === 'BS Biology'){
                                                $lim_stud_course = 'BS Bio';
                                            }else if($stud_course === 'BS Pharmacy'){
                                                $lim_stud_course = 'BS Pharma';
                                            }else if($stud_course === 'BS Radiologic Technology'){
                                                $lim_stud_course = 'BS Rad Tech';
                                            }else if($stud_course === 'BS Physical Therapy'){
                                                $lim_stud_course = 'BS Ph Th';
                                            }else if($stud_course === 'BS Medical Technology'){
                                                $lim_stud_course = 'BS Med Tech';
                                            }else{
                                                $lim_stud_course = $stud_course;
                                            }
                                            $output .= '
                                            <div class="col-lg-6 col-md-6 col-sm-12 m-0">
                                                <div class="violators_cards_div mb-2 d-flex justify-content-start align-items-center">
                                                    <div class="display_user_image_div text-center">
                                                        <img class="display_violator_image2 shadow-sm" src="'.asset('storage/svms/sdca_images/registered_students_imgs/'.$display_student_image).'" alt="student'.$sq.'s image">
                                                    </div>
                                                    <div class="information_div">
                                                        <span class="li_info_title">'.$stud_fname . ' ' . $stud_mname . ' ' . $stud_lname.'</span>
                                                        <span class="li_info_subtitle2"><span class="font-weight-bold">'.$stud_id.' </span> | ' . $lim_stud_course . ' - ' . $yearLevel_txt . ' | ' . ucwords($stud_gender).'</span>
                                                    </div>
                                                </div>
                                            </div>
                                            ';
                                        }
                                    }else{

                                    }
                                    $output .= '
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="form_addViolation" action="'.route('violation_entry.submit_violation_form').'" enctype="multipart/form-data" method="POST">
                ';
                    // queries
                    $queryCount_hasMinorOffenses = CreatedOffenses::where('crOffense_category', '=', 'minor offenses')->count();
                    $queryCount_hasLessSeriousOffenses = CreatedOffenses::where('crOffense_category', '=', 'less serious offenses')->count();
                    $queryCount_hasMajorOffenses = CreatedOffenses::where('crOffense_category', '=', 'major offenses')->count();
                $output .= '
                    <div class="row mt-3">
                        <div class="col-lg-6 col-md-6 col-sm-12 pr-0">
                            <div class="accordion shadow-none cust_accordion_div" id="minorOffOptionsModalAccordion_Parent">
                                <div class="card custom_accordion_card2 flex-column h-100">
                                    <div class="card-header p-0" id="minorOffOptionsCollapse_heading">
                                        <h2 class="mb-0 bg_F4F4F5">
                                            <button class="btn btn-block custom4_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#minorOffOptionsCollapse_Div" aria-expanded="true" aria-controls="minorOffOptionsCollapse_Div">
                                                <span class="li_info_titlev3">MINOR OFFENSES:</span>
                                                <i class="nc-icon nc-minimal-up"></i>
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="minorOffOptionsCollapse_Div" class="collapse cust_collapse_active show cb_t0b12y15 bg_FBF1F1" aria-labelledby="minorOffOptionsCollapse_heading" data-parent="#minorOffOptionsModalAccordion_Parent">
                                        <div class="row mt-0">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                            ';
                                                // display minor offenses options
                                                if($queryCount_hasMinorOffenses > 0){
                                                    // query all minor offenses' details
                                                    $queryMinorOffense_details = CreatedOffenses::select('crOffense_id', 'crOffense_category', 'crOffense_details')->where('crOffense_category', '=', 'minor offenses')->get();
                                                    foreach($queryMinorOffense_details as $thisOption_minorOff){
                                                        $output .= '
                                                        <div class="form-group mx-0 mt-2 mb-1">
                                                            <div class="custom-control custom-checkbox align-items-center">
                                                                <input type="checkbox" name="minor_offenses[]" value="'.$thisOption_minorOff->crOffense_details.'" class="custom-control-input cursor_pointer" id="'.$thisOption_minorOff->crOffense_id.'">
                                                                <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="'.$thisOption_minorOff->crOffense_id.'">'.$thisOption_minorOff->crOffense_details.'</label>
                                                            </div>
                                                        </div>
                                                        ';
                                                    }
                                                }
                                            $output .= '
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="accordion shadow-none cust_accordion_div" id="lessSeriousOffOptionsModalAccordion_Parent">
                                <div class="card custom_accordion_card2 flex-column h-100">
                                    <div class="card-header p-0" id="lessSeriousOffOptionsCollapse_heading">
                                        <h2 class="mb-0 bg_F4F4F5">
                                            <button class="btn btn-block custom4_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#lessSeriousOffOptionsCollapse_Div" aria-expanded="true" aria-controls="lessSeriousOffOptionsCollapse_Div">
                                                <span class="li_info_titlev3">LESS SERIOUS OFFENSES:</span>
                                                <i class="nc-icon nc-minimal-up"></i>
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="lessSeriousOffOptionsCollapse_Div" class="collapse cust_collapse_active show cb_t0b12y15 bg_FBF1F1" aria-labelledby="lessSeriousOffOptionsCollapse_heading" data-parent="#lessSeriousOffOptionsModalAccordion_Parent">
                                        <div class="row mt-0">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                            ';
                                            // display less serious offenses options
                                            if($queryCount_hasLessSeriousOffenses > 0){
                                                // query all less serious offenses' details
                                                $queryLessSeriousOffense_details = CreatedOffenses::select('crOffense_id', 'crOffense_category', 'crOffense_details')->where('crOffense_category', '=', 'less serious offenses')->get();
                                                foreach($queryLessSeriousOffense_details as $thisOption_lessSeriousOff){
                                                    $output .= '
                                                    <div class="form-group mx-0 mt-2 mb-1">
                                                        <div class="custom-control custom-checkbox align-items-center">
                                                            <input type="checkbox" name="less_serious_offenses[]" value="'.$thisOption_lessSeriousOff->crOffense_details.'" class="custom-control-input cursor_pointer" id="'.$thisOption_lessSeriousOff->crOffense_id.'">
                                                            <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="'.$thisOption_lessSeriousOff->crOffense_id.'">'.$thisOption_lessSeriousOff->crOffense_details.'</label>
                                                        </div>
                                                    </div>
                                                    ';
                                                }
                                            }
                                            $output .='
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="accordion shadow-none cust_accordion_div" id="majorOffOptionsModalAccordion_Parent">
                                <div class="card custom_accordion_card2">
                                    <div class="card-header p-0" id="majorOffOptionsCollapse_heading">
                                        <h2 class="mb-0 bg_F4F4F5">
                                            <button class="btn btn-block custom4_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#majorOffOptionsCollapse_Div" aria-expanded="true" aria-controls="majorOffOptionsCollapse_Div">
                                                <span class="li_info_titlev3">MAJOR OFFENSES:</span>
                                                <i class="nc-icon nc-minimal-up"></i>
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="majorOffOptionsCollapse_Div" class="collapse cb_t0b12y15 bg_FBF1F1" aria-labelledby="majorOffOptionsCollapse_heading" data-parent="#majorOffOptionsModalAccordion_Parent">
                                        <div class="row mt-0">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                            ';
                                            // display major offenses options
                                            if($queryCount_hasMajorOffenses > 0){
                                                // query all major offenses' details
                                                $queryMajorOffense_details = CreatedOffenses::select('crOffense_id', 'crOffense_category', 'crOffense_details')->where('crOffense_category', '=', 'major offenses')->get();
                                                foreach($queryMajorOffense_details as $thisOption_majorOff){
                                                    $output .= '
                                                    <div class="form-group mx-0 mt-2 mb-1">
                                                        <div class="custom-control custom-checkbox align-items-center">
                                                            <input type="checkbox" name="major_offenses[]" value="'.$thisOption_majorOff->crOffense_details.'" class="custom-control-input cursor_pointer" id="'.$thisOption_majorOff->crOffense_id.'">
                                                            <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="'.$thisOption_majorOff->crOffense_id.'">'.$thisOption_majorOff->crOffense_details.'</label>
                                                        </div>
                                                    </div>
                                                    ';
                                                }
                                            }
                                            $output .='
                                            </div>
                                        </div>
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
                                    <div class="input-group-append">
                                        <span class="input-group-text txt_iptgrp_append2 font-weight-bold">1. </span>
                                    </div>
                                    <input type="text" id="addOtherOffenses_input" name="other_offenses[]" class="form-control input_grpInpt2" placeholder="Type Other Offense" aria-label="Type Other Offense" aria-describedby="other-offenses-input">
                                    <div class="input-group-append">
                                        <button class="btn btn_svms_red m-0" id="btn_addAnother_input" type="button" disabled><i class="nc-icon nc-simple-add font-weight-bold" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="addedInputFields_div">

                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="cust_info_txtwicon3"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> You can only add a total of 10 Other Offenses.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 d-flex align-items-center">
                        <div class="col-lg-6 col-md-6 col-sm-12 d-flex-justify-content-start">
                            <span class="cust_info_txtwicon2 font-weight-bold"><i class="nc-icon nc-calendar-60 mr-1" aria-hidden="true"></i> '.date("F d, Y", strtotime($now_timestamp)) . ' -  ' . date("D", strtotime($now_timestamp)) . ' at ' . date("g:i A", strtotime($now_timestamp)) .'</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end">
                        ';
                        foreach(json_decode($get_all_violators, true) as $violator_id){
                            $output .= '<input type="hidden" name="violator_ids[]" value="'.$violator_id.'">';
                        }
                        $output .= '
                            <input type="hidden" name="violation_timestamp" value="'.$now_timestamp.'">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                            <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                            <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button id="cancel_violationForm_btn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                                <button id="submit_violationForm_btn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0" disabled>Save <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        ';
        return $output;
    }

    // process violation form
    public function submit_violation_form(Request $request){
        // get all request
        $get_selected_students     = json_decode(json_encode($request->get('violator_ids')), true);
        $get_violation_timestamp   = $request->get('violation_timestamp');
        $get_respo_user_id         = $request->get('respo_user_id');
        $get_respo_user_lname      = $request->get('respo_user_lname');
        $get_respo_user_fname      = $request->get('respo_user_fname');   
        $get_minor_offenses        = json_decode(json_encode($request->get('minor_offenses')), true);
        $get_less_serious_offenses = json_decode(json_encode($request->get('less_serious_offenses')), true);
        $get_major_offenses        = json_decode(json_encode($request->get('major_offenses')), true);
        $get_other_offenses        = json_decode(json_encode($request->get('other_offenses')), true);

        // count each offenses
        if(!is_null($get_minor_offenses) OR !empty($get_minor_offenses)){
            $total_mo_count = count($get_minor_offenses);
        }else{
            $total_mo_count = 0;
        }
        if(!is_null($get_less_serious_offenses) OR !empty($get_less_serious_offenses)){
            $total_lso_count = count($get_less_serious_offenses);
        }else{
            $total_lso_count = 0;
        }
        if(!is_null($get_major_offenses) OR !empty($get_major_offenses)){
            $total_mjo_count = count($get_major_offenses);
        }else{
            $total_mjo_count = 0;
        }
        if(!is_null($get_other_offenses) OR !empty($get_other_offenses)){
            if(in_array(null, $get_other_offenses)){
                $total_oo_count = 0;
            }else{
                $total_oo_count = count($get_other_offenses);   
            }
        }else{
            $total_oo_count = 0;
        }
        // total count of all offenses
        $total_offenses_count = $total_mo_count + $total_lso_count + $total_mjo_count + $total_oo_count;
        // pluras s
        if($total_offenses_count > 1){
            $s = 's';
        }else{
            $s = '';
        }

        foreach($get_selected_students as $this_violator){
            // get student's info
            $get_sel_students_info = Students::select('Student_Number', 'First_Name', 'Middle_Name', 'Last_Name', 'Gender', 'School_Name', 'Course', 'YearLevel', 'Email')->where('Student_Number', $this_violator)->first();
            $get_sel_stud_id       = $get_sel_students_info->Student_Number;
            $get_sel_stud_fname    = $get_sel_students_info->First_Name;
            $get_sel_stud_mname    = $get_sel_students_info->Middle_Name;
            $get_sel_stud_lname    = $get_sel_students_info->Last_Name;
            $get_sel_stud_gender   = $get_sel_students_info->Gender;
            $get_sel_stud_school   = $get_sel_students_info->School_Name;
            $get_sel_stud_course   = $get_sel_students_info->Course;
            $get_sel_stud_yrlvl    = $get_sel_students_info->YearLevel;
            $get_sel_stud_email    = $get_sel_students_info->Email;

            // year level
            if($get_sel_stud_yrlvl === '1'){
                $yearLevel_txt = '1st Year';
            }else if($get_sel_stud_yrlvl === '2'){
                $yearLevel_txt = '2nd Year';
            }else if($get_sel_stud_yrlvl === '3'){
                $yearLevel_txt = '3rd Year';
            }else if($get_sel_stud_yrlvl === '4'){
                $yearLevel_txt = '4th Year';
            }else if($get_sel_stud_yrlvl === '5'){
                $yearLevel_txt = '5th Year';
            }else{
                $yearLevel_txt = $get_sel_stud_yrlvl . ' Year';
            }

            // Mr./Mrs format
            $student_gender = Str::lower($get_sel_stud_gender);
            if($student_gender == 'male'){
                $user_his_her = 'his';
                $user_mr_ms   = 'Mr.';
            }elseif($student_gender == 'female'){
                $user_his_her = 'her';
                $user_mr_ms   = 'Ms.';
            }else{
                $user_his_her = 'his/her';
                $user_mr_ms   = 'Mr./Ms.';
            }

            // record offenses to violations_tbl
            $record_offenses = new Violations;
            $record_offenses->recorded_at      = $get_violation_timestamp;
            $record_offenses->offense_count    = $total_offenses_count;
            // $record_offenses->major_off        = $get_major_offenses;
            $record_offenses->minor_off        = $get_minor_offenses;
            $record_offenses->less_serious_off = $get_less_serious_offenses;
            $record_offenses->other_off        = $get_other_offenses;
            $record_offenses->stud_num         = $this_violator;
            $record_offenses->respo_user_id    = $get_respo_user_id;
            $record_offenses->save();

            // send email
            $details = [
                'svms_logo'           => "storage/svms/logos/svms_logo_text.png",
                'title'               => 'Student Violation',
                'recipient'           => $user_mr_ms . ' ' .$get_sel_stud_fname . ' ' . $get_sel_stud_mname . ' ' . $get_sel_stud_lname,
                'date_recorded'       => $get_violation_timestamp,
                'offense_count'       => $total_offenses_count,
                'major_off'           => $get_major_offenses,
                'minor_off'           => $get_minor_offenses,
                'less_serious_off'    => $get_less_serious_offenses,
                'other_off'           => $get_other_offenses,
                's'                   => $s
            ];

            // if record was a success
            if($record_offenses){
                // get this violation id from violations_tbl
                $get_new_viola_id = Violations::select('viola_id')->where('stud_num', $this_violator)->latest('recorded_at')->first();
                $new_viola_id     = $get_new_viola_id->viola_id;
                // record activity
                $record_act = new Useractivites;
                $record_act->created_at            = $get_violation_timestamp;
                $record_act->act_respo_user_id     = $get_respo_user_id;
                $record_act->act_respo_users_lname = $get_respo_user_lname;
                $record_act->act_respo_users_fname = $get_respo_user_fname;
                $record_act->act_type              = 'violation entry';
                $record_act->act_details           = 'Recorded ' . $total_offenses_count . ' Offense'.$s . ' made by ' . $yearLevel_txt . ' ' . $get_sel_stud_course . ' student: ' . $get_sel_stud_fname . ' ' . $get_sel_stud_mname . ' ' . $get_sel_stud_lname;
                $record_act->act_affected_id       = $new_viola_id;
                $record_act->save();

                // send email per student
                if(!empty($get_sel_stud_email) OR !is_null($get_sel_stud_email)){
                    \Mail::to('mfodesierto2@gmail.com')->send(new \App\Mail\ViolationRecordedSendMail($details));
                }
            }else{
                return back()->withFailedStatus('Recording Offenses has failed! Try Again later.');
            }
        }

        if($record_act){
            return back()->withSuccessStatus('Offenses was recorded successfully!');
        }else{
            return back()->withFailedStatus('Recording User Activity has failed!');
        }
        // echo json_encode('students count: ' . count($get_selected_students));
        // echo '<br/>';
        // echo 'Minor Offenses Count: ' . $total_mo_count;
        // echo '<br/>';
        // echo 'Less Serious Offenses count: ' . $total_lso_count;
        // echo '<br/>';
        // echo 'Other Offenses count: ' . $total_oo_count;
        // echo '<br/>';
        // echo 'Total Offenses count: ' . $total_offenses_count;
        // echo '<br/>';
        // echo json_encode($get_selected_students);
        // echo '<br/>';
        // echo $get_violation_timestamp;
        // echo '<br/>';
        // echo $get_respo_user_id;
        // echo '<br/>';
        // echo $get_respo_user_lname;
        // echo '<br/>';
        // echo $get_respo_user_fname;
        // echo '<br/>';
        // echo json_encode($get_minor_offenses);
        // echo '<br/>';
        // echo json_encode($get_less_serious_offenses);
        // echo '<br/>';
        // echo json_encode($get_other_offenses);
        // echo '<br/>';
    }
}
