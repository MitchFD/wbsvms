<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Students;
use App\Models\Violations;
use App\Models\Deletedviolations;
use App\Models\Sanctions;
use App\Models\Deletedsanctions;
use App\Models\Users;
use App\Models\Useractivites;
use Illuminate\Mail\Mailable;

class ViolationRecordsController extends Controller
{
    // main page
    public function index(Request $request){
        if($request->ajax()){
            // custom var
            $vr_output = '';
            $vr_paginate = '';
            $vr_total_matched_results = '';
            $vr_total_filtered_result = '';
            // get all request
            $vr_search      = $request->get('vr_search');
            $vr_schools     = $request->get('vr_schools');
            $vr_programs    = $request->get('vr_programs');
            $vr_yearlvls    = $request->get('vr_yearlvls');
            $vr_genders     = $request->get('vr_genders');
            $vr_minAgeRange = $request->get('vr_minAgeRange');
            $vr_maxAgeRange = $request->get('vr_maxAgeRange');
            $vr_status      = $request->get('vr_status');
            $vr_rangefrom   = $request->get('vr_rangefrom');
            $vr_rangeTo     = $request->get('vr_rangeTo');
            $df_minAgeRange = $request->get('df_minAgeRange');
            $df_maxAgeRange = $request->get('df_maxAgeRange');

            if($vr_search != ''){
                $fltr_VR_tbl = DB::table('violations_tbl')
                                ->join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                                ->select('violations_tbl.*', 'students_tbl.*')
                                ->where(function($vrQuery) use ($vr_search) {
                                    $vrQuery->orWhere('students_tbl.Student_Number', 'like', '%'.$vr_search.'%')
                                                ->orWhere('students_tbl.First_Name', 'like', '%'.$vr_search.'%')
                                                ->orWhere('students_tbl.Middle_Name', 'like', '%'.$vr_search.'%')
                                                ->orWhere('students_tbl.Last_Name', 'like', '%'.$vr_search.'%')
                                                ->orWhere('students_tbl.Gender', 'like', '%'.$vr_search.'%')
                                                ->orWhere('students_tbl.School_Name', 'like', '%'.$vr_search.'%')
                                                ->orWhere('students_tbl.YearLevel', 'like', '%'.$vr_search.'%')
                                                ->orWhere('students_tbl.Course', 'like', '%'.$vr_search.'%')
                                                ->orWhere('violations_tbl.stud_num', 'like', '%'.$vr_search.'%');
                                })
                                ->where(function($vrQuery) use ($vr_schools, $vr_programs, $vr_yearlvls, $vr_genders, $vr_minAgeRange, $vr_maxAgeRange, $df_minAgeRange, $df_maxAgeRange, $vr_status, $vr_rangefrom, $vr_rangeTo){
                                    if($vr_schools != 0 OR !empty($vr_schools)){
                                        $vrQuery->where('students_tbl.School_Name', '=', $vr_schools);
                                    }
                                    if($vr_programs != 0 OR !empty($vr_programs)){
                                        $vrQuery->where('students_tbl.Course', '=', $vr_programs);
                                    }
                                    if($vr_yearlvls != 0 OR !empty($vr_yearlvls)){
                                        $vrQuery->where('students_tbl.YearLevel', '=', $vr_yearlvls);
                                    }
                                    if($vr_genders != 0 OR !empty($vr_genders)){
                                        $lower_vr_gender = Str::lower($vr_genders);
                                        $vrQuery->where('students_tbl.Gender', '=', $lower_vr_gender);
                                    }
                                    if($vr_minAgeRange != $df_minAgeRange OR $vr_maxAgeRange != $df_maxAgeRange){
                                        $vrQuery->whereBetween('students_tbl.Age', [$vr_minAgeRange, $vr_maxAgeRange]);
                                    }
                                    if($vr_status != 0 OR !empty($vr_status)){
                                        $lower_vr_status = Str::lower($vr_status);
                                        $vrQuery->where('violations_tbl.violation_status', '=', $lower_vr_status);
                                    }
                                    if($vr_rangefrom != 0 OR !empty($vr_rangefrom) AND $vr_rangeTo != 0 OR !empty($vr_rangeTo)){
                                        $vrQuery->whereBetween('violations_tbl.recorded_at', [$vr_rangefrom, $vr_rangeTo]);
                                    }
                                })
                                ->orderBy('violations_tbl.recorded_at', 'DESC')
                                ->paginate(10);
                $matched_result_txt = ' Matched Records';
            }else{
                $fltr_VR_tbl = DB::table('violations_tbl')
                                ->join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                                ->select('violations_tbl.*', 'students_tbl.*')
                                ->where(function($vrQuery) use ($vr_schools, $vr_programs, $vr_yearlvls, $vr_genders, $vr_minAgeRange, $vr_maxAgeRange, $df_minAgeRange, $df_maxAgeRange, $vr_status, $vr_rangefrom, $vr_rangeTo){
                                    if($vr_schools != 0 OR !empty($vr_schools)){
                                        $vrQuery->where('students_tbl.School_Name', '=', $vr_schools);
                                    }
                                    if($vr_programs != 0 OR !empty($vr_programs)){
                                        $vrQuery->where('students_tbl.Course', '=', $vr_programs);
                                    }
                                    if($vr_yearlvls != 0 OR !empty($vr_yearlvls)){
                                        $vrQuery->where('students_tbl.YearLevel', '=', $vr_yearlvls);
                                    }
                                    if($vr_genders != 0 OR !empty($vr_genders)){
                                        $lower_vr_gender = Str::lower($vr_genders);
                                        $vrQuery->where('students_tbl.Gender', '=', $lower_vr_gender);
                                    }
                                    if($vr_minAgeRange != $df_minAgeRange OR $vr_maxAgeRange != $df_maxAgeRange){
                                        $vrQuery->whereBetween('students_tbl.Age', [$vr_minAgeRange, $vr_maxAgeRange]);
                                    }
                                    if($vr_status != 0 OR !empty($vr_status)){
                                        $lower_vr_status = Str::lower($vr_status);
                                        $vrQuery->where('violations_tbl.violation_status', '=', $lower_vr_status);
                                    }
                                    if($vr_rangefrom != 0 OR !empty($vr_rangefrom) AND $vr_rangeTo != 0 OR !empty($vr_rangeTo)){
                                        $vrQuery->whereBetween('violations_tbl.recorded_at', [$vr_rangefrom, $vr_rangeTo]);
                                    }
                                })
                                ->orderBy('violations_tbl.recorded_at', 'DESC')
                                ->paginate(10);
                $matched_result_txt = ' Record';
            }
            // total filtered date
            $vr_count_filtered_result = count($fltr_VR_tbl);
            $vr_total_filtered_result = $fltr_VR_tbl->total();
            // plural text
            if($vr_total_filtered_result > 0){
                if($vr_total_filtered_result > 1){
                    $s = 's';
                }else{
                    $s = '';
                }
                $vr_total_matched_results = $fltr_VR_tbl->firstItem() . ' - ' . $fltr_VR_tbl->lastItem() . ' of ' . $vr_total_filtered_result . ' ' . $matched_result_txt.''.$s;
            }else{
                $s = '';
                $vr_total_matched_results = 'No Records Found';
            }
            if($vr_count_filtered_result > 0){
                // custom values
                $sq = "'";
                foreach($fltr_VR_tbl as $this_violator){
                    if($this_violator->offense_count > 1){
                        $oc_s = 's';
                    }else{
                        $oc_s = '';
                    }
                    // violation status classes
                    if($this_violator->violation_status === 'cleared'){
                        $violator_img = 'default_cleared_student_img.jpg';
                        $violation_statTxt = ' <span class="text-success font-italic"> ~ Cleared</span>';
                        $badge_stat = 'cust_badge_grn';
                        $img_class = 'display_violator_image3';
                    }else{
                        $violator_img = 'default_student_img.jpg';
                        $violation_statTxt = ' <span class="text_svms_red font-italic"> ~ Not Cleared</span>';
                        $badge_stat = 'cust_badge_red';
                        $img_class = 'display_violator_image2';
                    }
                    $vr_output .= '
                    <tr id="'.$this_violator->Student_Number.'" onclick="viewStudentOffenses(this.id)" class="tr_pointer">
                        <td class="pl12 d-flex justify-content-start align-items-center">
                            ';
                            if(!is_null($this_violator->Student_Image) OR !empty($this_violator->Student_Image)){
                                $vr_output .= '<img class="'.$img_class.' shadow-sm" src="'.asset('storage/svms/sdca_images/registered_students_imgs/'.$this_violator->Student_Image.'').'" alt="student'.$sq.'s image">';
                            }else{
                                $vr_output .= '<img class="'.$img_class.' shadow-sm" src="'.asset('storage/svms/sdca_images/registered_students_imgs/'.$violator_img.'').'" alt="student'.$sq.'s image">';
                            }
                            $vr_output .= '
                            <div class="cust_td_info">
                                <span class="actLogs_tdTitle font-weight-bold">
                                    '.preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $this_violator->First_Name) . ' 
                                    ' . preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $this_violator->Middle_Name) . ' 
                                    ' . preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $this_violator->Last_Name) . '
                                </span>
                                <span class="actLogs_tdSubTitle">
                                    <span class="sub1">'.preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $this_violator->Student_Number) . ' <span class="subDiv"> | </span> 
                                    <span class="sub1"> ' . preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $this_violator->School_Name) . ' <span class="subDiv"> | </span> 
                                        ' . preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $this_violator->Course) . ' <span class="subDiv"> | </span> 
                                        ' . preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $this_violator->YearLevel.'-Y') . 
                                        ' </span> <span class="subDiv"> | 
                                    </span> 
                                    <span class="sub1"> ' . preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $this_violator->Gender) . ' </span> </span>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline">
                                <span class="actLogs_content">'.date('F d, Y', strtotime($this_violator->recorded_at)) . '</span>
                                <span class="actLogs_tdSubTitle sub2">'.date('D', strtotime($this_violator->recorded_at)) . ' - '. date('g:i A', strtotime($this_violator->recorded_at)) . '</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline">
                                <span class="actLogs_content">'.$this_violator->offense_count.' Offense'.$oc_s . ' ' . $violation_statTxt.'</span>
                                <span class="actLogs_tdSubTitle sub2">
                                ';
                                // set new array value
                                $to_array_allOffenses = array();
                                // merge all offenses to $to_array_allOffenses
                                if(!is_null($this_violator->minor_off) OR !empty($this_violator->minor_off)){
                                    foreach(json_decode($this_violator->minor_off, true) as $this_mo){
                                        array_push($to_array_allOffenses, $this_mo);
                                    }
                                }
                                if(!is_null($this_violator->less_serious_off) OR !empty($this_violator->less_serious_off)){
                                    foreach(json_decode($this_violator->less_serious_off, true) as $this_lso){
                                        array_push($to_array_allOffenses, $this_lso);
                                    }
                                }
                                if(!is_null($this_violator->other_off) OR !empty($this_violator->other_off)){
                                    if(!in_array(null, json_decode($this_violator->other_off, true))){
                                        foreach(json_decode($this_violator->other_off, true) as $this_oo){
                                            array_push($to_array_allOffenses, $this_oo);
                                        }
                                    }
                                }
                                // convert $to_array_allOffenses to json
                                $toJson = json_encode($to_array_allOffenses);
                                // count all merged offenses
                                $count_allOffenses = json_encode(count($to_array_allOffenses));
                                $x = 0;
                                // display 4 badge
                                foreach(json_decode($toJson, true) as $all_offense){
                                    if($count_allOffenses <= 3){
                                        $vr_output .= ' <span class="badge '.$badge_stat.'"> '.Str::limit($all_offense, $limit=20, $end='...').' </span> ';
                                    }else{
                                        $vr_output .= ' <span class="badge '.$badge_stat.'"> '.Str::limit($all_offense, $limit=15, $end='...').' </span> ';
                                    }
                                    $x++;
                                    if($x == 4){
                                        break;
                                    }
                                }
                                // display more count if offenses count > 4
                                if($count_allOffenses > 4){
                                    $sub_moreOffense_count = $count_allOffenses - 4;
                                    $vr_output .= ' <span class="badge '.$badge_stat.'"> '. $sub_moreOffense_count . ' more...</span> ';
                                }
                                $vr_output .= '
                                </span>
                            </div>
                        </td>
                    </tr>
                    ';
                }
            }else{
                $vr_output .='
                    <tr class="no_data_row">
                        <td align="center" colspan="7">
                            <div class="no_data_div2 d-flex justify-content-center align-items-center text-center flex-column">
                                <img class="no_data_svg" src="'. asset('storage/svms/illustrations/no_violations_found.svg').'" alt="no matching Data found">
                                <span class="font-italic font-weight-bold">No Records Found! </span>
                            </div>
                        </td>
                    </tr>
                ';
            }
            $vr_paginate .= $fltr_VR_tbl->links('pagination::bootstrap-4');
            $vr_data = array(
                'vr_table'            => $vr_output,
                'vr_table_paginate'   => $vr_paginate,
                'vr_total_rows'       => $vr_total_matched_results,
                'vr_total_data_found' => $vr_total_filtered_result
               );
               
            echo json_encode($vr_data);
        }else{
            return view('violation_records.index');
        }
    }

    // violator's profile module
    public function violator($violator_id){
        // check if $violator_id exists in students_tbl
        $check_exist = Students::where('Student_Number', $violator_id)->count();
        if($check_exist > 0){
            // get violator's info from students_tbl and violations_tbl
            $violator_info = Students::where('Student_Number', $violator_id)->first();
            $offenses_count = Violations::where('stud_num', $violator_id)->count();
            return view('violation_records.violator')->with(compact('violator_info', 'offenses_count', 'violator_id'));
        }else{
            return view('violation_records.unknown_violator')->with(compact('violator_id'));
        }
    }

    // add new violation entry
    public function new_violation_form_modal(Request $request){
        // get student number
        $get_sel_violator = $request->get('violator_id');
        $now_timestamp    = now();
        $sq               = "'";

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
                                                <span class="li_info_subtitle">1 student selected</span>
                                            </div>
                                            <i class="nc-icon nc-minimal-up"></i>
                                        </button>
                                    </h2>
                                </div>
                                <div id="selectedViolatorsCollapse_Div" class="collapse cust_collapse_active show cb_t0b12y15 bg_F4F4F5" aria-labelledby="empTypeRolesCollapse_heading" data-parent="#empTypeRolesModalAccordion_Parent">
                                    <div class="row mt-0">
                                    ';
                                    // get student's information
                                    $stud_info   = Students::select('Student_Number', 'First_Name', 'Middle_Name', 'Last_Name', 'Gender', 'School_Name', 'Course', 'YearLevel', 'Student_Image', 'Status')
                                                    ->where('Student_Number', $get_sel_violator)->first();
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="form_addNewViolation" action="'.route('violation_entry.submit_violation_form').'" enctype="multipart/form-data" method="POST">
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
                                    <div class="input-group-append">
                                        <span class="input-group-text txt_iptgrp_append2 font-weight-bold">1. </span>
                                    </div>
                                    <input type="text" id="addOtherOffensesNew_input" name="other_offenses[]" class="form-control input_grpInpt2" placeholder="Type Other Offense" aria-label="Type Other Offense" aria-describedby="other-offenses-input">
                                    <div class="input-group-append">
                                        <button class="btn btn_svms_red m-0" id="btn_addAnother_input" type="button" disabled><i class="nc-icon nc-simple-add font-weight-bold" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="addedInputFieldsNew_div">

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
                            <input type="hidden" name="violator_ids[]" value="'.$get_sel_violator.'">
                            <input type="hidden" name="violation_timestamp" value="'.$now_timestamp.'">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                            <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                            <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button id="cancel_newViolationForm_btn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                                <button id="submit_newViolationForm_btn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0" disabled>Save <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        ';
        return $output;
    }

    // deleted violations module
    public function deleted_violation_records(){
        return view('violation_records.deleted_violation_records');
    }

    // SANCTIONS PROCESSES
    // add sanctions ~ modal
    public function add_sanction_form(Request $request){
        // get selected viola_id & selected stud_num
            $sel_viola_id = $request->get('sel_viola_id');
            $sel_stud_num = $request->get('sel_stud_num');
        // get violation's info
            $get_viola_info = Violations::where('viola_id', $sel_viola_id)->first();
            $get_viola_recorded_at      = $get_viola_info->recorded_at;
            $get_viola_status           = $get_viola_info->violation_status;
            $get_viola_offense_count    = $get_viola_info->offense_count;
            $get_viola_minor_off        = $get_viola_info->minor_off;
            $get_viola_less_serious_off = $get_viola_info->less_serious_off;
            $get_viola_other_off        = $get_viola_info->other_off;
            $get_viola_stud_num         = $get_viola_info->stud_num;
            $get_viola_has_sanction     = $get_viola_info->has_sanction;
            $get_viola_respo_user_id    = $get_viola_info->respo_user_id;
        // custom values
        // plural offense count
            if($get_viola_offense_count > 1){
                $oC_s = 's';
            }else{
                $oC_s = '';
            }
            // responsible user
            if($get_viola_respo_user_id == auth()->user()->id){
                $recBy = 'Recorded by you.';
            }else{
                $get_recBy_info = Users::select('id', 'user_role', 'user_lname')
                                        ->where('id', $get_viola_respo_user_id)
                                        ->first();
                $recBy = ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_lname;
            }
            // cleared/uncleared classes
            if($get_viola_status === 'cleared'){
                $light_cardBody       = 'lightGreen_cardBody';
                $light_cardBody_title = 'lightGreen_cardBody_greenTitle';
                $light_cardBody_list  = 'lightGreen_cardBody_list';
                $info_textClass       = 'cust_info_txtwicon4';
                $info_iconClass       = 'fa fa-check-square-o';
            }else{
                $light_cardBody       = 'lightRed_cardBody';
                $light_cardBody_title = 'lightRed_cardBody_redTitle';
                $light_cardBody_list  = 'lightRed_cardBody_list';
                $info_textClass       = 'cust_info_txtwicon3';
                $info_iconClass       = 'fa fa-exclamation-circle';
            }
        // output
            $output = '';
            $output .= '
                <div class="modal-body border-0 p-0">
                    <div class="cust_modal_body_gray">
                        <div class="accordion shadow cust_accordion_div" id="sv'.$sel_viola_id.'Accordion_Parent">
                            <div class="card custom_accordion_card">
                                <div class="card-header p-0" id="changeUserRoleCollapse_heading">
                                    <h2 class="mb-0">
                                        <button class="btn btn-block custom2_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#sv'.$sel_viola_id.'Collapse_Div" aria-expanded="true" aria-controls="sv'.$sel_viola_id.'Collapse_Div">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="information_div2">
                                                    <span class="li_info_title">'.date('F d, Y', strtotime($get_viola_recorded_at)).'</span>
                                                    <span class="li_info_subtitle">'.date('l - g:i A', strtotime($get_viola_recorded_at)).'</span>
                                                </div>
                                            </div>
                                            <i class="nc-icon nc-minimal-up"></i>
                                        </button>
                                    </h2>
                                </div>
                                <div id="sv'.$sel_viola_id.'Collapse_Div" class="collapse show cust_collapse_active cb_t0b12y15" aria-labelledby="sv'.$sel_viola_id.'Collapse_heading" data-parent="#sv'.$sel_viola_id.'Accordion_Parent">
                                    ';
                                    if(!is_null(json_decode(json_encode($get_viola_minor_off), true)) OR !empty(json_decode(json_encode($get_viola_minor_off), true))){
                                        $vmo_x = 1;
                                        $output .= '
                                        <div class="card-body '.  $light_cardBody  .' mb-2">
                                            <span class="'. $light_cardBody_title  .' mb-1">Minor Offenses:</span>
                                            ';
                                            foreach(json_decode(json_encode($get_viola_minor_off), true) as $viola_minor_offenses){
                                                $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vmo_x++ .'.</span> '. $viola_minor_offenses .'</span>';
                                            }
                                            $output .='
                                        </div>
                                        ';
                                    }
                                    if(!is_null(json_decode(json_encode($get_viola_less_serious_off), true)) OR !empty(json_decode(json_encode($get_viola_less_serious_off), true))){
                                        $vlso_x = 1;
                                        $output .= '
                                        <div class="card-body '.  $light_cardBody  .' mb-2">
                                            <span class="'. $light_cardBody_title  .' mb-1">Less Serious Offenses:</span>
                                            ';
                                            foreach(json_decode(json_encode($get_viola_less_serious_off), true) as $viola_less_serious_offenses){
                                                $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vlso_x++ .'.</span> '. $viola_less_serious_offenses .'</span>';
                                            }
                                            $output .='
                                        </div>
                                        ';
                                    }
                                    if(!is_null(json_decode(json_encode($get_viola_other_off), true)) OR !empty(json_decode(json_encode($get_viola_other_off), true))){
                                        if(!in_array(null, json_decode(json_encode($get_viola_other_off), true))){
                                            $voo_x = 1;
                                            $output .= '
                                            <div class="card-body '.  $light_cardBody  .' mb-2">
                                                <span class="'. $light_cardBody_title  .' mb-1">Other Offenses:</span>
                                                ';
                                                foreach(json_decode(json_encode($get_viola_other_off), true) as $viola_other_offenses){
                                                    $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $voo_x++ .'.</span> '. $viola_other_offenses .'</span>';
                                                }
                                                $output .='
                                            </div>
                                            ';
                                        }
                                    }
                                    $output .='
                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <span class="' .$info_textClass . ' font-weight-bold"><i class="' .$info_iconClass . ' mr-1" aria-hidden="true"></i> ' .$get_viola_offense_count. ' Offense' .$oC_s. '</span>  
                                            <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> ' . $recBy . '</span>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="form_addSanctions" action="'.route('violation_records.submit_sanction_form').'" class="form" enctype="multipart/form-data" method="POST">
                        <div class="modal-body pb-0">
                            <div class="card-body lightGreen_cardBody mb-2">
                                <span class="lightGreen_cardBody_greenTitle mb-1">Sanctions:</span>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <span class="input-group-text txt_iptgrp_append font-weight-bold">1. </span>
                                    </div>
                                    <input type="text" id="addSanctions_input" name="sanctions[]" class="form-control input_grpInpt3" placeholder="Type Sanction" aria-label="Type Sanction" aria-describedby="add-sanctions-input" required />
                                    <div class="input-group-append">
                                        <button class="btn btn-success m-0" id="btn_addAnother_input" type="button" disabled><i class="nc-icon nc-simple-add font-weight-bold" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="addedInputFields_div">

                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="cust_info_txtwicon4v1"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> You can only add a total of 10 Sanctions.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input type="hidden" name="for_viola_id" value="'.$sel_viola_id.'">
                            <input type="hidden" name="sel_stud_num" value="'.$sel_stud_num.'">
                            <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                            <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                            <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button id="cancel_addSanctionsBtn" type="button" class="btn btn-round btn_svms_red btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                                <button id="submit_addSanctionsBtn" type="submit" class="btn btn-round btn-success btn_show_icon m-0" disabled>Save <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            ';
            echo $output;
    }
    // process sanction form
    public function submit_sanction_form(Request $request){
        // get all request
            $get_for_viola_id     = $request->get('for_viola_id');
            $get_sel_stud_num     = $request->get('sel_stud_num');
            $get_respo_user_id    = $request->get('respo_user_id');
            $get_respo_user_lname = $request->get('respo_user_lname');
            $get_respo_user_fname = $request->get('respo_user_fname');   
            $get_sanctions        = json_decode(json_encode($request->get('sanctions')));
        // custom values
            $now_timestamp = now();
            $sq = "'";
            $count_sanctions = count($get_sanctions);
            if($count_sanctions > 0){
                if($count_sanctions > 1){
                    $sc_s = 's';
                }else{
                    $sc_s = '';
                }
            }
        // get selected violatin's info
            $sel_viola_info = Violations::select('viola_id', 'recorded_at', 'offense_count')
                                ->where('viola_id', $get_for_viola_id)
                                ->first();
            $sel_viola_recorded_at   = $sel_viola_info->recorded_at;
            $sel_viola_offense_count = $sel_viola_info->offense_count;
            if($sel_viola_offense_count > 0){
                if($sel_viola_offense_count > 1){
                    $vc_s = 's';
                }else{
                    $vc_s = '';
                }
            }else{
                $vc_s = '';
            }
        // get selected student's info from students_tbl
            $sel_stud_info = Students::select('Student_Number', 'First_Name', 'Middle_Name', 'Last_Name', 'Email', 'School_Name', 'Course', 'YearLevel')
                                ->where('Student_Number', $get_sel_stud_num)
                                ->first();
            $sel_stud_Fname       = $sel_stud_info->First_Name;
            $sel_stud_Mname       = $sel_stud_info->Middle_Name;
            $sel_stud_Lname       = $sel_stud_info->Last_Name;
            $sel_stud_Email       = $sel_stud_info->Email;
            $sel_stud_School_Name = $sel_stud_info->School_Name;
            $sel_stud_Course      = $sel_stud_info->Course;
            $sel_stud_YearLevel   = $sel_stud_info->YearLevel;
        // year level
            if($sel_stud_YearLevel === '1'){
                $yearLevel_txt = '1st Year';
            }else if($sel_stud_YearLevel === '2'){
                $yearLevel_txt = '2nd Year';
            }else if($sel_stud_YearLevel === '3'){
                $yearLevel_txt = '3rd Year';
            }else if($sel_stud_YearLevel === '4'){
                $yearLevel_txt = '4th Year';
            }else if($sel_stud_YearLevel === '5'){
                $yearLevel_txt = '5th Year';
            }else{
                $yearLevel_txt = $sel_stud_YearLevel . ' Year';
            }
        // save requests to sanctions_tbl
            if($count_sanctions > 0){
                foreach($get_sanctions as $sanction){
                    $record_sanctions = new Sanctions;
                    $record_sanctions->stud_num      = $get_sel_stud_num;
                    $record_sanctions->for_viola_id  = $get_for_viola_id;
                    $record_sanctions->sanct_details = $sanction;
                    $record_sanctions->respo_user_id = $get_respo_user_id;
                    $record_sanctions->created_at    = $now_timestamp;
                    $record_sanctions->save();
                }
            }
            if($record_sanctions){
            // get all recorded sanctions' ids
                $to_array_sanct_ids = array();
                $get_all_processed_sanctions = Sanctions::select('sanct_id')
                                                    ->where('stud_num', $get_sel_stud_num)
                                                    ->where('for_viola_id', $get_for_viola_id)
                                                    ->where('respo_user_id', $get_respo_user_id)
                                                    ->offset(0)
                                                    ->limit($count_sanctions)
                                                    ->get();
                if(count($get_all_processed_sanctions) > 0){
                    foreach($get_all_processed_sanctions as $sanction_ids){
                        array_push($to_array_sanct_ids, $sanction_ids);
                    }
                }
                $to_Json_sanct_ids = json_encode($to_array_sanct_ids);
                $ext_jsonSanct_ids = str_replace(array( '{', '}', '"', ':', 'sanct_id' ), '', $to_Json_sanct_ids);
            // update selected violation's "has_sanction"
                $update_sel_viol_tbl = DB::table('violations_tbl')
                    ->where('viola_id', $get_for_viola_id)
                    ->update([
                        'has_sanction'    => 1,
                        'has_sanct_count' => $count_sanctions,
                        'updated_at'      => $now_timestamp
                        ]);
            // record activity
                $record_act = new Useractivites;
                $record_act->created_at             = $now_timestamp;
                $record_act->act_respo_user_id      = $get_respo_user_id;
                $record_act->act_respo_users_lname  = $get_respo_user_lname;
                $record_act->act_respo_users_fname  = $get_respo_user_fname;
                $record_act->act_type               = 'sanction entry';
                $record_act->act_details            = 'Added ' . $count_sanctions . ' Sanction'.$sc_s . ' for the ' . $sel_viola_offense_count . ' Offense'.$vc_s . ' made by ' . $yearLevel_txt . ' ' . $sel_stud_Course . ' student: ' . $sel_stud_Fname . ' ' . $sel_stud_Mname . ' ' . $sel_stud_Lname . ' on ' . date('F d, Y', strtotime($sel_viola_recorded_at)).'.';
                $record_act->act_affected_sanct_ids = $ext_jsonSanct_ids;
                $record_act->save();
            }else{
                return back()->withFailedStatus('Adding Sanctions has failed! Try Again later.');
            }
            if($record_act){
                return back()->withSuccessStatus('Sanctions was recorded successfully!');
            }else{
                return back()->withFailedStatus('Recording User Activity has failed!');
            }
    }
    // edit sanctions ~ modal
    public function edit_sanction_form(Request $request){
        // get selected viola_id & selected stud_num
            $sel_viola_id = $request->get('sel_viola_id');
            $sel_stud_num = $request->get('sel_stud_num');
        // get violation's info
            $get_viola_info = Violations::where('viola_id', $sel_viola_id)->first();
            $get_viola_recorded_at      = $get_viola_info->recorded_at;
            $get_viola_status           = $get_viola_info->violation_status;
            $get_viola_offense_count    = $get_viola_info->offense_count;
            $get_viola_minor_off        = $get_viola_info->minor_off;
            $get_viola_less_serious_off = $get_viola_info->less_serious_off;
            $get_viola_other_off        = $get_viola_info->other_off;
            $get_viola_stud_num         = $get_viola_info->stud_num;
            $get_viola_has_sanction     = $get_viola_info->has_sanction;
            $get_viola_has_sanct_count  = $get_viola_info->has_sanct_count;
            $get_viola_respo_user_id    = $get_viola_info->respo_user_id;
        // get violator's info
            $get_violator_info = Students::select('Last_Name', 'Gender')->where('Student_Number', $sel_stud_num)->first();
            $violator_Lname    = $get_violator_info->Last_Name;
            $violator_Gender   = $get_violator_info->Gender;
            // Mr./Mrs format
            $violator_gender = Str::lower($violator_Gender);
            if($violator_gender == 'male'){
                $violator_mr_ms   = 'Mr.';
            }elseif($violator_gender == 'female'){
                $violator_mr_ms   = 'Ms.';
            }else{
                $violator_mr_ms   = 'Mr./Ms.';
            }
        // custom values
        // plural offense count
            if($get_viola_offense_count > 1){
                $oC_s = 's';
            }else{
                $oC_s = '';
            }
            // responsible user
            if($get_viola_respo_user_id == auth()->user()->id){
                $recBy = 'Recorded by you.';
            }else{
                $get_recBy_info = Users::select('id', 'user_role', 'user_lname')
                                        ->where('id', $get_viola_respo_user_id)
                                        ->first();
                $recBy = ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_lname;
            }
            // cleared/uncleared classes
            if($get_viola_status === 'cleared'){
                $light_cardBody       = 'lightGreen_cardBody';
                $light_cardBody_title = 'lightGreen_cardBody_greenTitle';
                $light_cardBody_list  = 'lightGreen_cardBody_list';
                $info_textClass       = 'cust_info_txtwicon4';
                $info_iconClass       = 'fa fa-check-square-o';
                $class_violationStat1 = 'text-success font-italic';
                $txt_violationStat1   = '~ Cleared';
            }else{
                $light_cardBody       = 'lightRed_cardBody';
                $light_cardBody_title = 'lightRed_cardBody_redTitle';
                $light_cardBody_list  = 'lightRed_cardBody_list';
                $info_textClass       = 'cust_info_txtwicon3';
                $info_iconClass       = 'fa fa-exclamation-circle';
                $class_violationStat1 = 'text_svms_red font-italic';
                $txt_violationStat1   = '~ Not Cleared';    
            }
        // output
            $output = '';
            $output .= '
                <div class="modal-body border-0 p-0">
                    <div class="cust_modal_body_gray">
                        <div class="accordion shadow cust_accordion_div" id="sv'.$sel_viola_id.'Accordion_Parent">
                            <div class="card custom_accordion_card">
                                <div class="card-header p-0" id="changeUserRoleCollapse_heading">
                                    <h2 class="mb-0">
                                        <button class="btn btn-block custom2_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#sv'.$sel_viola_id.'Collapse_Div" aria-expanded="true" aria-controls="sv'.$sel_viola_id.'Collapse_Div">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="information_div2">
                                                    <span class="li_info_title">'.date('F d, Y', strtotime($get_viola_recorded_at)).' <span class="'.$class_violationStat1.'"> ' . $txt_violationStat1.'</span></span>
                                                    <span class="li_info_subtitle">'.date('l - g:i A', strtotime($get_viola_recorded_at)).'</span>
                                                </div>
                                            </div>
                                            <i class="nc-icon nc-minimal-up"></i>
                                        </button>
                                    </h2>
                                </div>
                                <div id="sv'.$sel_viola_id.'Collapse_Div" class="collapse show cust_collapse_active cb_t0b12y15" aria-labelledby="sv'.$sel_viola_id.'Collapse_heading" data-parent="#sv'.$sel_viola_id.'Accordion_Parent">
                                    ';
                                    if(!is_null(json_decode(json_encode($get_viola_minor_off), true)) OR !empty(json_decode(json_encode($get_viola_minor_off), true))){
                                        $vmo_x = 1;
                                        $output .= '
                                        <div class="card-body '.  $light_cardBody  .' mb-2">
                                            <span class="'. $light_cardBody_title  .' mb-1">Minor Offenses:</span>
                                            ';
                                            foreach(json_decode(json_encode($get_viola_minor_off), true) as $viola_minor_offenses){
                                                $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vmo_x++ .'.</span> '. $viola_minor_offenses .'</span>';
                                            }
                                            $output .='
                                        </div>
                                        ';
                                    }
                                    if(!is_null(json_decode(json_encode($get_viola_less_serious_off), true)) OR !empty(json_decode(json_encode($get_viola_less_serious_off), true))){
                                        $vlso_x = 1;
                                        $output .= '
                                        <div class="card-body '.  $light_cardBody  .' mb-2">
                                            <span class="'. $light_cardBody_title  .' mb-1">Less Serious Offenses:</span>
                                            ';
                                            foreach(json_decode(json_encode($get_viola_less_serious_off), true) as $viola_less_serious_offenses){
                                                $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vlso_x++ .'.</span> '. $viola_less_serious_offenses .'</span>';
                                            }
                                            $output .='
                                        </div>
                                        ';
                                    }
                                    if(!is_null(json_decode(json_encode($get_viola_other_off), true)) OR !empty(json_decode(json_encode($get_viola_other_off), true))){
                                        if(!in_array(null, json_decode(json_encode($get_viola_other_off), true))){
                                            $voo_x = 1;
                                            $output .= '
                                            <div class="card-body '.  $light_cardBody  .' mb-2">
                                                <span class="'. $light_cardBody_title  .' mb-1">Other Offenses:</span>
                                                ';
                                                foreach(json_decode(json_encode($get_viola_other_off), true) as $viola_other_offenses){
                                                    $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $voo_x++ .'.</span> '. $viola_other_offenses .'</span>';
                                                }
                                                $output .='
                                            </div>
                                            ';
                                        }
                                    }
                                    $output .='
                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <span class="' .$info_textClass . ' font-weight-bold"><i class="' .$info_iconClass . ' mr-1" aria-hidden="true"></i> ' .$get_viola_offense_count. ' Offense' .$oC_s. '</span>  
                                            <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> ' . $recBy . '</span>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body pb-0">
                        <ul class="nav nav-pills custom_nav_pills mt-0 mb-3 d-flex justify-content-center" id="editSanctionPills_tabParent" role="tablist">
                            <li class="nav-item mx-1">
                                <a class="nav-link custom_nav_link_greenv1 active" id="mark_sanctions_tab" data-toggle="pill" href="#mark_sanctions_tabContent" role="tab" aria-controls="mark_sanctions_tabContent" aria-selected="true"><i class="fa fa-check-square-o mr-1" aria-hidden="true"></i> Mark</a>
                            </li>
                            <li class="nav-item mx-1">
                                <a class="nav-link custom_nav_link_greenv1" id="add_sanctions_tab" data-toggle="pill" href="#add_sanctions_tabContent" role="tab" aria-controls="add_sanctions_tabContent" aria-selected="false"><i class="fa fa-plus mr-1" aria-hidden="true"></i> Add</a>
                            </li>
                            <li class="nav-item mx-1">
                                <a class="nav-link custom_nav_link_greenv1" id="delete_sanctions_tab" data-toggle="pill" href="#delete_sanctions_tabContent" role="tab" aria-controls="delete_sanctions_tabContent" aria-selected="false"><i class="fa fa-trash mr-1" aria-hidden="true"></i> Delete</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="editSanctionPills_tabContent">
                            ';
                            // get all sanctions
                            $get_all_sanctions = Sanctions::select('sanct_id', 'sanct_status', 'sanct_details')
                                                        ->where('stud_num', $sel_stud_num)
                                                        ->where('for_viola_id', $sel_viola_id)
                                                        ->orderBy('created_at', 'asc')
                                                        ->offset(0)
                                                        ->limit($get_viola_has_sanct_count)
                                                        ->get();
                            // count all sanctions
                            $count_all_sanctions = count($get_all_sanctions);
                            // count all completed sanctions
                            $count_completed_sanct = Sanctions::where('stud_num', $sel_stud_num)
                                                        ->where('for_viola_id', $sel_viola_id)
                                                        ->where('sanct_status', '=', 'completed')
                                                        ->offset(0)
                                                        ->limit($get_viola_has_sanct_count)
                                                        ->count();
                            // custom values
                            if($count_all_sanctions > 1){
                                $cas_s = 's';
                            }else{
                                $cas_s = '';
                            }
                            if($count_completed_sanct == $count_all_sanctions){
                                $icon_infoClass1 = 'fa fa-check-square-o';
                                $text_infoClass1 = 'All ('.$count_all_sanctions.') Sanction'.$cas_s . ' have been completed.';
                                $icon_infoClass2 = 'fa fa-calendar-check-o';
                                $text_infoClass2 = date('F d, Y', strtotime($get_viola_recorded_at)) . ' - ' . date('l - g:i A', strtotime($get_viola_recorded_at));
                            }else{
                                $icon_infoClass1 = 'fa fa-list-ul';
                                $text_infoClass1 = ''.$count_all_sanctions.' Sanction'.$cas_s . ' for the above offenses.';
                                $icon_infoClass2 = 'fa fa-info-circle';
                                $text_infoClass2 = 'Mark Sanction/s that have been completed by ' . $violator_mr_ms . ' ' . $violator_Lname.'.';
                            }
                            $output .= '
                            <div class="tab-pane fade show active" id="mark_sanctions_tabContent" role="tabpanel" aria-labelledby="mark_sanctions_tab">
                                <form id="form_markSanctions" action="'.route('violation_records.update_sanction_form').'" class="form" enctype="multipart/form-data" method="POST">
                                    <div class="card-body lightGreen_cardBody">
                                        <span class="lightGreen_cardBody_greenTitle mb-1">Mark Sanctions:</span>
                                        ';
                                        foreach($get_all_sanctions as $this_editSanction){
                                            $output .= '
                                            <div class="form-group mx-0 mt-0 mb-1">
                                                <div class="custom-control custom-checkbox align-items-center">
                                                    <input type="checkbox" name="marked_sanctions[]" value="'.$this_editSanction->sanct_id.'" class="custom-control-input cursor_pointer sanctMarkSingle" id="'.$this_editSanction->sanct_id.'_markThisSanct_id" '; if($this_editSanction->sanct_status === 'completed'){ $output .= 'checked'; } $output .='>
                                                    <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="'.$this_editSanction->sanct_id.'_markThisSanct_id">'.$this_editSanction->sanct_details.'</label>
                                                </div>
                                            </div>
                                            ';
                                        }
                                        $output .='
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                            ';
                                            if($count_all_sanctions > 0){
                                                if($count_all_sanctions > 1){
                                                    $cls_s = 's';
                                                    $output .= '
                                                        <div class="form-group mx-0 mt-0 mb-1">
                                                            <div class="custom-control custom-checkbox align-items-center">
                                                                <input type="checkbox" name="mark_all_sanctions" value="mark_all_sanctions" class="custom-control-input cursor_pointer" id="sanctMarkAll" '; if($count_completed_sanct == $count_all_sanctions){ $output .= 'checked'; } $output .= '>
                                                                <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="sanctMarkAll">Mark All ('.$count_all_sanctions.') Sanction'.$cls_s . ' as completed.</label>
                                                            </div>
                                                        </div>
                                                        ';
                                                }else{
                                                    $cls_s = '';
                                                }
                                                $output .= '
                                                <hr class="hr_grn">
                                                <span class="cust_info_txtwicon4v1 font-weight-bold"><i class="'.$icon_infoClass1 . ' mr-1" aria-hidden="true"></i> ' . $text_infoClass1.'</span>
                                                ';
                                            }
                                            $output .='
                                                <span class="cust_info_txtwicon4v1"><i class="'.$icon_infoClass2 . ' mr-1" aria-hidden="true"></i> ' . $text_infoClass2.'</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer pr-0 border-0">
                                        <input type="hidden" name="_token" value="'.csrf_token().'">
                                        ';
                                        foreach($get_all_sanctions as $this_editSanction){
                                            $output .= '
                                            <input type="hidden" name="reg_sanctions[]" value="'.$this_editSanction->sanct_id.'">
                                            ';
                                        }
                                        $output .= '
                                        <input type="hidden" name="for_viola_id" value="'.$sel_viola_id.'">
                                        <input type="hidden" name="sel_stud_num" value="'.$sel_stud_num.'">
                                        <input type="hidden" name="total_sanct_count_f1" id="total_sanct_count_f1" value="'.$count_all_sanctions.'">
                                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button id="cancel_markSanctionsBtn" type="button" class="btn btn-round btn_svms_red btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                                            <button id="submit_markSanctionsBtn" type="submit" class="btn btn-round btn-success btn_show_icon m-0" disabled>Save Changes <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="add_sanctions_tabContent" role="tabpanel" aria-labelledby="add_sanctions_tab">
                                <form id="form_addNewSanctions" action="'.route('violation_records.add_new_sanctions').'" class="form" enctype="multipart/form-data" method="POST">    
                                    <div class="card-body lightGreen_cardBody">
                                        <span class="lightGreen_cardBody_greenTitle mb-1">Add New Sanctions:</span>
                                        ';
                                        $txt_iptgrp_append_count = $count_all_sanctions + 1;
                                        $txt_allowed_append_count = 10 - $count_all_sanctions;
                                        if($txt_allowed_append_count > 0){
                                            if($txt_allowed_append_count > 1){
                                                $aSC_s = 's';
                                            }else{
                                                $aSC_s = '';
                                            }
                                        }
                                        $add_x = 1;
                                        foreach($get_all_sanctions as $this_addSanction){
                                            $output .= '<span class="lightGreen_cardBody_list"><span class="font-weight-bold mr-1">'. $add_x++ .'.</span> '. $this_addSanction->sanct_details .'</span>';
                                        }
                                        $output .= '
                                        <div class="input-group mt-1 mb-2">
                                            <div class="input-group-append">
                                                <span class="input-group-text txt_iptgrp_append font-weight-bold">'.$txt_iptgrp_append_count.'. </span>
                                            </div>
                                            <input type="text" id="addNewSanction_input" name="new_sanctions[]" class="form-control input_grpInpt3v1" placeholder="Type New Sanction" aria-label="Type New Sanction" aria-describedby="add-new-sanctions-input" required />
                                            <div class="input-group-append">
                                                <button class="btn btn-success btn_iptgrp_append m-0" id="btn_addNewSanct_input" type="button" disabled><i class="nc-icon nc-simple-add font-weight-bold" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                        <div class="addedSanctInputFields_div">

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <span class="cust_info_txtwicon4v1"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> You can now only add ' . $txt_allowed_append_count . ' Sanction'.$aSC_s.'.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer pr-0 border-0">
                                        <input type="hidden" name="_token" value="'.csrf_token().'">
                                        <input type="hidden" name="for_viola_id" value="'.$sel_viola_id.'">
                                        <input type="hidden" name="sel_stud_num" value="'.$sel_stud_num.'">
                                        <input type="hidden" name="prev_sanct_count" id="prev_sanct_count" value="'.$count_all_sanctions.'">
                                        <input type="hidden" name="append_new_index" id="append_new_index" value="'.$txt_iptgrp_append_count.'">
                                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                                        <div class="btn-group" role="group" aria-label="add new sanctions actions">
                                            <button id="cancel_addNewSanctionsBtn" type="button" class="btn btn-round btn_svms_red btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                                            <button id="submit_addNewSanctionsBtn" type="submit" class="btn btn-round btn-success btn_show_icon m-0" disabled> Add New Sanctions <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="delete_sanctions_tabContent" role="tabpanel" aria-labelledby="delete_sanctions_tab">
                                <form id="form_deleteSanctions" action="'.route('violation_records.delete_sanction_form').'" class="form" enctype="multipart/form-data" method="POST">
                                    <div class="card-body lightGreen_cardBody">
                                        <span class="lightGreen_cardBody_greenTitle mb-1">Delete Sanctions:</span>
                                        ';
                                        foreach($get_all_sanctions as $this_deleteSanction){
                                            $output .= '
                                            <div class="form-group mx-0 mt-0 mb-1">
                                                <div class="custom-control custom-checkbox align-items-center">
                                                    <input type="checkbox" name="delete_sanctions[]" value="'.$this_deleteSanction->sanct_id.'" class="custom-control-input cursor_pointer sanctDeleteSingle" id="'.$this_deleteSanction->sanct_id.'_deleteThisSanct_id">
                                                    <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="'.$this_deleteSanction->sanct_id.'_deleteThisSanct_id">'.$this_deleteSanction->sanct_details.'</label>
                                                </div>
                                            </div>
                                            ';
                                        }
                                        $output .='
                                        <hr class="hr_grn">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                            ';
                                            if($count_all_sanctions > 0){
                                                if($count_all_sanctions > 1){
                                                    $cls_s = 's';
                                                    $output .= '
                                                    <div class="form-group mx-0 mt-0 mb-1">
                                                        <div class="custom-control custom-checkbox align-items-center">
                                                            <input type="checkbox" name="delete_all_sanctions" value="delete_all_sanctions" class="custom-control-input cursor_pointer" id="sanctDeleteAll">
                                                            <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="sanctDeleteAll">Delete All ('.$count_all_sanctions.') Sanction'.$cls_s.'</label>
                                                        </div>
                                                    </div>
                                                    ';
                                                }else{
                                                    $cls_s = '';
                                                }
                                                $output .= '
                                                <span class="cust_info_txtwicon4v1 font-weight-bold"><i class="fa fa-list-ul mr-1" aria-hidden="true"></i> ' . $count_all_sanctions . ' Sanction'.$cls_s . ' for the above offenses.</span>
                                                ';
                                            }
                                            $output .='
                                                <span class="cust_info_txtwicon4v1"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> Mark sanctions you want to delete.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer pr-0 border-0">
                                        <input type="hidden" name="_token" value="'.csrf_token().'">
                                        <input type="hidden" name="for_viola_id" value="'.$sel_viola_id.'">
                                        <input type="hidden" name="sel_stud_num" value="'.$sel_stud_num.'">
                                        <input type="hidden" name="total_sanct_count_f3" id="total_sanct_count" value="'.$count_all_sanctions.'">
                                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                                        <div class="btn-group" role="group" aria-label="delete sanctions actions">
                                            <button id="cancel_deleteSanctionsBtn" type="button" class="btn btn-round btn-success btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                                            <button id="submit_deleteSanctionsBtn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0" disabled> Delete Selected <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            ';
            echo $output;
    }
    // process edited sanction form
    public function update_sanction_form(Request $request){
        // custom values
            $now_timestamp     = now();
            $completed_txt     = 'completed';
            $not_completed_txt = 'not completed';
            $cleared_txt       = 'cleared';
            $not_cleared_txt   = 'not cleared';
        // get all request
            $get_for_viola_id     = $request->get('for_viola_id');
            $get_sel_stud_num     = $request->get('sel_stud_num');
            $get_respo_user_id    = $request->get('respo_user_id');
            $get_respo_user_lname = $request->get('respo_user_lname');
            $get_respo_user_fname = $request->get('respo_user_fname');   
            $get_marked_sanctions = json_decode(json_encode($request->get('marked_sanctions'))); 
            $get_reg_sanctions    = json_decode(json_encode($request->get('reg_sanctions'))); 
        // get selected violation's info
            $get_violation_info = Violations::select('has_sanct_count')
                                        ->where('viola_id', $get_for_viola_id)
                                        ->first();
            $get_viola_sanct_count = $get_violation_info->has_sanct_count;
        // count completed sanctions for $get_for_viola_id
            $completed_reg_sanct = Sanctions::where('for_viola_id', $get_for_viola_id)
                                        ->where('sanct_status', '=', $completed_txt)
                                        ->count();
        // count completed sanctions for $get_for_viola_id
            $not_completed_reg_sanct = Sanctions::where('for_viola_id', $get_for_viola_id)
                                        ->where('sanct_status', '=', $not_completed_txt)
                                        ->count();
        // custom values
            if(!is_null($get_reg_sanctions) OR !empty($get_reg_sanctions)){
                $reg_sanct_count = count($get_reg_sanctions);
            }else{
                $reg_sanct_count = 0;
            }
            if(!is_null($get_marked_sanctions) OR !empty($get_marked_sanctions)){
                $marked_sanct_count = count($get_marked_sanctions);
            }else{
                $marked_sanct_count = 0;
            }
        // try
            // $x = 1;
            // echo 'REGISTERED SANCTIONS: <br />';
            // foreach($get_reg_sanctions as $display_reg_sanct){
            //     echo ''.$x++.': ' . $display_reg_sanct . ' <br />';
            // }
            // echo 'regsitered sanctions count = ' . $reg_sanct_count . ' <br /> <br />';
            // echo 'completed sanctions count = ' . $completed_reg_sanct . ' <br />';
            // echo 'not completed sanctions count = ' . $not_completed_reg_sanct . ' <br /><br />';
            // echo 'marked sanctions count = ' . $marked_sanct_count . ' <br />';

            // logics for updating sanction's status
            // all are marked
            if($marked_sanct_count == $reg_sanct_count){
                foreach($get_marked_sanctions as $updated_sanction){
                    $update_sanct_statuses = DB::table('sanctions_tbl')
                    ->where('sanct_id', $updated_sanction)
                    ->update([
                        'sanct_status' => $completed_txt,
                        'completed_at' => $now_timestamp,
                        'updated_at'   => $now_timestamp
                    ]);
                }
                if($update_sanct_statuses){
                    // update violation's status
                    $update_violation_stat = DB::table('violations_tbl')
                                ->where('viola_id', $get_for_viola_id)
                                ->update([
                                    'violation_status' => $cleared_txt,
                                    'cleared_at'       => $now_timestamp,
                                    'updated_at'       => $now_timestamp
                                ]); 
                    return back()->withSuccessStatus('All Sanctions was marked successfully.');
                }else{
                    return back()->withFailedStatus('Sanctions Update has Failed! try again later.');
                }
            }

            // marked is < completed
            if($marked_sanct_count < $completed_reg_sanct){
                $to_array_marked_sanctions = array();
                if($get_marked_sanctions > 0){
                    foreach($get_marked_sanctions as $to_array_this){
                        array_push($to_array_marked_sanctions, $to_array_this);
                    }
                }
                $to_Json_marked_sanct_ids = json_encode($to_array_marked_sanctions);
                $count_not_selected = DB::table('sanctions_tbl')->select('sanct_id', 'sanct_status', 'sanct_details')
                                        ->where('for_viola_id', $get_for_viola_id)
                                        ->where('stud_num', $get_sel_stud_num)
                                        ->whereNotIn('sanct_id', $to_array_marked_sanctions)
                                        ->offset(0)
                                        ->limit($get_reg_sanctions)
                                        ->count();
                if($count_not_selected > 0){
                    $get_all_not_selected = DB::table('sanctions_tbl')->select('sanct_id')
                                            ->where('for_viola_id', $get_for_viola_id)
                                            ->where('stud_num', $get_sel_stud_num)
                                            ->whereNotIn('sanct_id', $to_array_marked_sanctions)
                                            ->offset(0)
                                            ->limit($count_not_selected)
                                            ->get();
                    foreach($get_all_not_selected as $not_slected_sanction){
                        $update_sanct_statuses = DB::table('sanctions_tbl')
                            ->where('sanct_id', $not_slected_sanction->sanct_id)
                            ->update([
                                'sanct_status' => $not_completed_txt,
                                'completed_at' => $now_timestamp,
                                'updated_at'   => $now_timestamp
                            ]);
                    }
                }
                if($update_sanct_statuses){
                    // update violation's status
                    if($marked_sanct_count == $reg_sanct_count){
                        $update_violation_stat = DB::table('violations_tbl')
                            ->where('viola_id', $get_for_viola_id)
                            ->update([
                                'violation_status' => $cleared_txt,
                                'cleared_at'       => $now_timestamp,
                                'updated_at'       => $now_timestamp
                            ]); 
                    }else{
                        $update_violation_stat = DB::table('violations_tbl')
                            ->where('viola_id', $get_for_viola_id)
                            ->update([
                                'violation_status' => $not_cleared_txt,
                                'updated_at'       => $now_timestamp
                            ]); 
                    }
                    return back()->withSuccessStatus('Sanctions Update was a success.');
                }else{
                    return back()->withFailedStatus('Sanctions Update has Failed! try again later.');
                }
            }

            // mark is > completed
            if($marked_sanct_count > $completed_reg_sanct){
                foreach($get_marked_sanctions as $new_marked_sanction){
                    $update_newMarksanct_statuses = DB::table('sanctions_tbl')
                    ->where('sanct_id', $new_marked_sanction)
                    ->update([
                        'sanct_status' => $completed_txt,
                        'completed_at' => $now_timestamp,
                        'updated_at'   => $now_timestamp
                    ]);
                }
                if($update_newMarksanct_statuses){
                    // update violation's status
                    if($marked_sanct_count == $reg_sanct_count){
                        $update_violation_stat = DB::table('violations_tbl')
                            ->where('viola_id', $get_for_viola_id)
                            ->update([
                                'violation_status' => $cleared_txt,
                                'cleared_at'       => $now_timestamp,
                                'updated_at'       => $now_timestamp
                            ]); 
                    }else{
                        $update_violation_stat = DB::table('violations_tbl')
                            ->where('viola_id', $get_for_viola_id)
                            ->update([
                                'violation_status' => $not_cleared_txt,
                                'updated_at'       => $now_timestamp
                            ]); 
                    }
                    return back()->withSuccessStatus('Sanctions Update was a success.');
                }else{
                    return back()->withFailedStatus('Sanctions Update has Failed! try again later.');
                }
            }

    }
    // process adding new sanctions
    public function add_new_sanctions(Request $request){
        // get all request
            $get_for_viola_id     = $request->get('for_viola_id');
            $get_sel_stud_num     = $request->get('sel_stud_num');
            $get_prev_sanct_count = $request->get('prev_sanct_count');
            $get_respo_user_id    = $request->get('respo_user_id');
            $get_respo_user_lname = $request->get('respo_user_lname');
            $get_respo_user_fname = $request->get('respo_user_fname');   
            $get_new_sanctions    = json_decode(json_encode($request->get('new_sanctions')));
        // custom values
            $now_timestamp = now();
            $sq = "'";
            $count_new_sanctions = count($get_new_sanctions);
            if($count_new_sanctions > 0){
                if($count_new_sanctions > 1){
                    $nsc_s = 's';
                }else{
                    $nsc_s = '';
                }
                $new_has_sanct_count = $count_new_sanctions + $get_prev_sanct_count;
            }else{
                $new_has_sanct_count = 0;
            }
        // get selected violatin's info
            $sel_viola_info = Violations::select('viola_id', 'recorded_at', 'offense_count')
                    ->where('viola_id', $get_for_viola_id)
                    ->first();
            $sel_viola_recorded_at   = $sel_viola_info->recorded_at;
            $sel_viola_offense_count = $sel_viola_info->offense_count;
            if($sel_viola_offense_count > 0){
                if($sel_viola_offense_count > 1){
                    $vc_s = 's';
                }else{
                    $vc_s = '';
                }
            }else{
                $vc_s = '';
            }
        // get selected student's info from students_tbl
            $sel_stud_info = Students::select('Student_Number', 'First_Name', 'Middle_Name', 'Last_Name', 'Email', 'School_Name', 'Course', 'YearLevel')
                                ->where('Student_Number', $get_sel_stud_num)
                                ->first();
            $sel_stud_Fname       = $sel_stud_info->First_Name;
            $sel_stud_Mname       = $sel_stud_info->Middle_Name;
            $sel_stud_Lname       = $sel_stud_info->Last_Name;
            $sel_stud_Email       = $sel_stud_info->Email;
            $sel_stud_School_Name = $sel_stud_info->School_Name;
            $sel_stud_Course      = $sel_stud_info->Course;
            $sel_stud_YearLevel   = $sel_stud_info->YearLevel;
        // year level
            if($sel_stud_YearLevel === '1'){
                $yearLevel_txt = '1st Year';
            }else if($sel_stud_YearLevel === '2'){
                $yearLevel_txt = '2nd Year';
            }else if($sel_stud_YearLevel === '3'){
                $yearLevel_txt = '3rd Year';
            }else if($sel_stud_YearLevel === '4'){
                $yearLevel_txt = '4th Year';
            }else if($sel_stud_YearLevel === '5'){
                $yearLevel_txt = '5th Year';
            }else{
                $yearLevel_txt = $sel_stud_YearLevel . ' Year';
            }
        // process adding new sanctions
            if($count_new_sanctions > 0){
                foreach($get_new_sanctions as $new_sanction){
                    $record_new_sanctions = new Sanctions;
                    $record_new_sanctions->stud_num      = $get_sel_stud_num;
                    $record_new_sanctions->for_viola_id  = $get_for_viola_id;
                    $record_new_sanctions->sanct_details = $new_sanction;
                    $record_new_sanctions->respo_user_id = $get_respo_user_id;
                    $record_new_sanctions->created_at    = $now_timestamp;
                    $record_new_sanctions->save();
                }
            }
            if($record_new_sanctions){
            // get all recorded sanctions' ids
                $to_array_new_sanct_ids = array();
                $get_all_processed_new_sanctions = Sanctions::select('sanct_id')
                                                    ->where('stud_num', $get_sel_stud_num)
                                                    ->where('for_viola_id', $get_for_viola_id)
                                                    ->offset(0)
                                                    ->limit($new_has_sanct_count)
                                                    ->get();
                if(count($get_all_processed_new_sanctions) > 0){
                    foreach($get_all_processed_new_sanctions as $new_sanction_ids){
                        array_push($to_array_new_sanct_ids, $new_sanction_ids);
                    }
                }
                $to_Json_new_sanct_ids = json_encode($to_array_new_sanct_ids);
                $ext_jsonNewSanct_ids  = str_replace(array( '{', '}', '"', ':', 'sanct_id' ), '', $to_Json_new_sanct_ids);
            // update selected violation's "has_sanction"
                $update_sel_viol_tbl = DB::table('violations_tbl')
                    ->where('viola_id', $get_for_viola_id)
                    ->update([
                        'violation_status' => 'not cleared',
                        'has_sanction'     => 1,
                        'has_sanct_count'  => $new_has_sanct_count,
                        'updated_at'       => $now_timestamp,
                        'cleared_at'       => null
                        ]);
            // record activity
                $record_act = new Useractivites;
                $record_act->created_at             = $now_timestamp;
                $record_act->act_respo_user_id      = $get_respo_user_id;
                $record_act->act_respo_users_lname  = $get_respo_user_lname;
                $record_act->act_respo_users_fname  = $get_respo_user_fname;
                $record_act->act_type               = 'sanction entry';
                $record_act->act_details            = 'Added ' . $count_new_sanctions . ' New Sanction'.$vc_s . ' for the ' . $sel_viola_offense_count . ' Offense'.$vc_s . ' made by ' . $yearLevel_txt . ' ' . $sel_stud_Course . ' student: ' . $sel_stud_Fname . ' ' . $sel_stud_Mname . ' ' . $sel_stud_Lname . ' on ' . date('F d, Y', strtotime($sel_viola_recorded_at)).'.';
                $record_act->act_affected_sanct_ids = $ext_jsonNewSanct_ids;
                $record_act->save();
            }else{
                return back()->withFailedStatus('Adding New Sanctions has failed! Try Again later.');
            }
            if($record_act){
                return back()->withSuccessStatus('New Sanctions was recorded successfully!');
            }else{
                return back()->withFailedStatus('Recording User Activity has failed!');
            }
    }
    // process deleting sanctions
    public function delete_sanction_form(Request $request){
        // custom values
            $now_timestamp     = now();
        // get all request
            $get_for_viola_id      = $request->get('for_viola_id');
            $get_sel_stud_num      = $request->get('sel_stud_num');
            $get_respo_user_id     = $request->get('respo_user_id');
            $get_respo_user_lname  = $request->get('respo_user_lname');
            $get_respo_user_fname  = $request->get('respo_user_fname');   
            $get_total_sanct_count = $request->get('total_sanct_count_f3');   
            $get_delete_all_sanct  = $request->get('delete_all_sanctions');   
            $get_deleted_sanctions = json_decode(json_encode($request->get('delete_sanctions'))); 
        // cusotms
            $sq = "'";
            $count_deleted_sanct = count($get_deleted_sanctions);
            if($count_deleted_sanct > 0){
                if($count_deleted_sanct > 1){
                    $ds_s = 's';
                }else{
                    $ds_s = '';
                }
            }else{
                $ds_s = '';
            }
        // get status of selected for_viola_id
            $get_violation_info = Violations::select('violation_status', 'recorded_at', 'offense_count', 'has_sanction', 'has_sanct_count')
                                        ->where('viola_id', $get_for_viola_id)
                                        ->first();
            $default_viola_status    = $get_violation_info->violation_status;
            $default_viola_date      = $get_violation_info->recorded_at;
            $default_viola_count     = $get_violation_info->offense_count;
            $default_has_sanction    = $get_violation_info->has_sanction;
            $default_has_sanct_count = $get_violation_info->has_sanct_count;
            if($default_viola_count > 0){
                if($default_viola_count > 1){
                    $vc_s = 's';
                }else{
                    $vc_s = '';
                }
            }
        // get selected student's info from students_tbl
            $sel_stud_info = Students::select('Student_Number', 'First_Name', 'Middle_Name', 'Last_Name', 'Email', 'School_Name', 'Course', 'YearLevel')
                                ->where('Student_Number', $get_sel_stud_num)
                                ->first();
            $sel_stud_Fname       = $sel_stud_info->First_Name;
            $sel_stud_Mname       = $sel_stud_info->Middle_Name;
            $sel_stud_Lname       = $sel_stud_info->Last_Name;
            $sel_stud_Email       = $sel_stud_info->Email;
            $sel_stud_School_Name = $sel_stud_info->School_Name;
            $sel_stud_Course      = $sel_stud_info->Course;
            $sel_stud_YearLevel   = $sel_stud_info->YearLevel;
        // year level
            if($sel_stud_YearLevel === '1'){
                $yearLevel_txt = '1st Year';
            }else if($sel_stud_YearLevel === '2'){
                $yearLevel_txt = '2nd Year';
            }else if($sel_stud_YearLevel === '3'){
                $yearLevel_txt = '3rd Year';
            }else if($sel_stud_YearLevel === '4'){
                $yearLevel_txt = '4th Year';
            }else if($sel_stud_YearLevel === '5'){
                $yearLevel_txt = '5th Year';
            }else{
                $yearLevel_txt = $sel_stud_YearLevel . ' Year';
            }
        // deletion 
            if($count_deleted_sanct > 0){
                foreach($get_deleted_sanctions as $delete_this_sanct){
                    // get each sanction's info
                    $get_sel_sanctions_info = Sanctions::select('sanct_id', 'stud_num', 'for_viola_id', 'sanct_status', 'sanct_details', 'respo_user_id', 'created_at', 'completed_at')
                                            ->where('sanct_id', $delete_this_sanct)
                                            ->first();
                    // save to deleted_sanctions_tbl
                    $save_tobe_deleted = new Deletedsanctions;
                    $save_tobe_deleted->del_from_sanct_id = $delete_this_sanct;
                    $save_tobe_deleted->del_by_user_id    = $get_respo_user_id;
                    $save_tobe_deleted->deleted_at        = $now_timestamp;
                    $save_tobe_deleted->reason_deletion   = 'reason';
                    $save_tobe_deleted->del_stud_num      = $get_sel_stud_num;
                    $save_tobe_deleted->del_sanct_status  = $get_sel_sanctions_info->sanct_status;
                    $save_tobe_deleted->del_sanct_details = $get_sel_sanctions_info->sanct_details;
                    $save_tobe_deleted->del_for_viola_id  = $get_sel_sanctions_info->for_viola_id;
                    $save_tobe_deleted->del_respo_user_id = $get_sel_sanctions_info->respo_user_id;
                    $save_tobe_deleted->del_created_at    = $get_sel_sanctions_info->created_at;
                    $save_tobe_deleted->del_completed_at  = $get_sel_sanctions_info->completed_at;
                    $save_tobe_deleted->save();
                    if($save_tobe_deleted){
                        // delete selected
                        $delete_from_sanctions_tbl = Sanctions::where('sanct_id', $delete_this_sanct)
                                                    ->where('stud_num', $get_sel_stud_num)
                                                    ->where('for_viola_id', $get_for_viola_id)
                                                    ->delete();
                    }
                }
                // update violation's status from violations_tbl
                if($delete_from_sanctions_tbl){
                    if($count_deleted_sanct >= $get_total_sanct_count){
                        $violation_status    = 'not cleared';
                        $new_has_sanction    = 0;
                        $new_has_sanct_count = 0;
                    }else{
                        // subtract original sanctions to deleted sanctions
                        $new_sanct_count = $get_total_sanct_count - $count_deleted_sanct;
                        if($new_sanct_count > 0){
                            // count all remaining sanctions
                            $count_remain_sanct = Sanctions::where('stud_num', $get_sel_stud_num)
                                                ->where('for_viola_id', $get_for_viola_id)
                                                ->count();
                            // count all completed remaining sanctions
                            $count_completed_remain_sanct = Sanctions::where('stud_num', $get_sel_stud_num)
                                                ->where('for_viola_id', $get_for_viola_id)
                                                ->where('sanct_status', 'completed')
                                                ->count();
                        }
                        if($count_remain_sanct == $count_completed_remain_sanct){
                            $violation_status    = 'cleared';
                            $new_has_sanction    = 1;
                            $new_has_sanct_count = $new_sanct_count;
                        }else{
                            $violation_status    = $default_viola_status;
                            $new_has_sanction    = 1;
                            $new_has_sanct_count = $new_sanct_count;
                        }
                    }
                    $update_sel_viol_tbl = DB::table('violations_tbl')
                            ->where('viola_id', $get_for_viola_id)
                            ->update([
                                'violation_status' => $violation_status,
                                'has_sanction'     => $new_has_sanction,
                                'has_sanct_count'  => $new_has_sanct_count,
                                'updated_at'       => $now_timestamp
                                ]);
                    if($update_sel_viol_tbl){
                        // get del_id from deleted_sanctions_tbl
                        $to_array_deleted_sanct_ids = array();
                        $sel_all_deleted_sanct_info = Deletedsanctions::select('del_id')
                                ->where('del_for_viola_id', $get_for_viola_id)
                                ->where('del_stud_num', $get_sel_stud_num)
                                ->offset(0)
                                ->limit($count_deleted_sanct)
                                ->get();
                        if(count($sel_all_deleted_sanct_info) > 0){
                            foreach($sel_all_deleted_sanct_info as $deleted_sanction_ids){
                                array_push($to_array_deleted_sanct_ids, $deleted_sanction_ids);
                            }
                        }
                        $to_Json_deleted_sanct_ids = json_encode($to_array_deleted_sanct_ids);
                        $ext_jsonDeletedSanct_ids = str_replace(array( '{', '}', '"', ':', 'del_id' ), '', $to_Json_deleted_sanct_ids);
                        // record activity
                        $record_act = new Useractivites;
                        $record_act->created_at             = $now_timestamp;
                        $record_act->act_respo_user_id      = $get_respo_user_id;
                        $record_act->act_respo_users_lname  = $get_respo_user_lname;
                        $record_act->act_respo_users_fname  = $get_respo_user_fname;
                        $record_act->act_type               = 'sanction deletion';
                        $record_act->act_details            = 'Deleted ' . $count_deleted_sanct . ' Sanction'.$ds_s . ' for ' . $default_viola_count . ' Offense'.$vc_s . ' made by ' . $yearLevel_txt . ' ' . $sel_stud_Course . ' student: ' . $sel_stud_Fname . ' ' . $sel_stud_Mname . ' ' . $sel_stud_Lname . ' on ' . date('F d, Y', strtotime($default_viola_date)).'.';
                        $record_act->act_affected_sanct_ids = $ext_jsonDeletedSanct_ids;
                        $record_act->save();
                        return back()->withSuccessStatus($count_deleted_sanct . ' Sanction'.$ds_s.' was deleted successfully.');
                    }else{
                        return back()->withFailedStatus('Updating Violation'.$sq.'s status has failed. Try Again later.');
                    }
                }else{
                    return back()->withFailedStatus('Deleting ' . $count_deleted_sanct . ' Sanctions'.$ds_s.' has failed! Try again later.');
                }
            }else{
                return back()->withFailedStatus('No Sanctions were selected for deletion. try again.');
            }
    }

    // VIOLATION DELETION
    // temporary delete violation confirmation modal
    public function delete_violation_form(Request $request){
        // get all request
            $sel_viola_id = $request->get('sel_viola_id');
            $sel_stud_num = $request->get('sel_stud_num');
        // get violation's info
            $get_viola_info = Violations::where('viola_id', $sel_viola_id)->first();
            $get_viola_recorded_at      = $get_viola_info->recorded_at;
            $get_viola_status           = $get_viola_info->violation_status;
            $get_viola_offense_count    = $get_viola_info->offense_count;
            $get_viola_minor_off        = $get_viola_info->minor_off;
            $get_viola_less_serious_off = $get_viola_info->less_serious_off;
            $get_viola_other_off        = $get_viola_info->other_off;
            $get_viola_stud_num         = $get_viola_info->stud_num;
            $get_viola_has_sanction     = $get_viola_info->has_sanction;
            $get_viola_has_sanct_count  = $get_viola_info->has_sanct_count;
            $get_viola_respo_user_id    = $get_viola_info->respo_user_id;
            $get_viola_cleared_at       = $get_viola_info->cleared_at;
        // get violator's info
            $get_violator_info = Students::select('Last_Name', 'Gender')->where('Student_Number', $sel_stud_num)->first();
            $violator_Lname    = $get_violator_info->Last_Name;
            $violator_Gender   = $get_violator_info->Gender;
            // Mr./Mrs format
            $violator_gender = Str::lower($violator_Gender);
            if($violator_gender == 'male'){
                $violator_mr_ms   = 'Mr.';
            }elseif($violator_gender == 'female'){
                $violator_mr_ms   = 'Ms.';
            }else{
                $violator_mr_ms   = 'Mr./Ms.';
            }
        // custom values
            // plural offense count
            if($get_viola_offense_count > 1){
                $oC_s = 's';
            }else{
                $oC_s = '';
            }
            // dates
            $date_recorded = date('F d, Y ~ l - g:i A', strtotime($get_viola_recorded_at));
            // violator's last name and Mr./Mrs
            $query_violator_info = Students::select('Last_Name', 'Gender')
                                            ->where('Student_Number', $sel_stud_num)
                                            ->first();
            $get_violator_lname = $query_violator_info->Last_Name;
            $get_violator_gender = strtolower($query_violator_info->Gender);
            if($get_violator_gender === 'male'){
                $vmr_ms = 'Mr.';
            }elseif($get_violator_gender === 'female'){
                $vmr_ms = 'Ms.';
            }else{
                $vmr_ms = 'Mr./Ms.';
            }
            // responsible user
            if($get_viola_respo_user_id == auth()->user()->id){
                $recBy = 'Recorded by you.';
                $recByTooltip = 'This Violation was recorded by you on ' . $date_recorded.'.';
            }else{
                $get_recBy_info = Users::select('id', 'user_role', 'user_lname', 'user_fname')
                                        ->where('id', $get_viola_respo_user_id)
                                        ->first();
                $recBy = ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_lname;
                $recByTooltip = 'This Violation was recorded by ' . ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_fname . ' ' . $get_recBy_info->user_lname . ' on ' . $date_recorded.'.';
            }
            // cleared/uncleared classes
            if($get_viola_status === 'cleared'){
                $light_cardBody       = 'lightGreen_cardBody';
                $light_cardBody_title = 'lightGreen_cardBody_greenTitle';
                $light_cardBody_list  = 'lightGreen_cardBody_list';
                $info_textClass       = 'cust_info_txtwicon4';
                $info_iconClass       = 'fa fa-check-square-o';
                $class_violationStat1 = 'text-success font-italic';
                $txt_violationStat1   = '~ Cleared';
            }else{
                $light_cardBody       = 'lightRed_cardBody';
                $light_cardBody_title = 'lightRed_cardBody_redTitle';
                $light_cardBody_list  = 'lightRed_cardBody_list';
                $info_textClass       = 'cust_info_txtwicon3';
                $info_iconClass       = 'fa fa-exclamation-circle';
                $class_violationStat1 = 'text_svms_red font-italic';
                $txt_violationStat1   = '~ Not Cleared';    
            }

        $output = '';
        $output .= '
            <div class="modal-body border-0 p-0">
                <div class="cust_modal_body_gray">
                    <div class="accordion shadow cust_accordion_div" id="sv'.$sel_viola_id.'Accordion_Parent">
                        <div class="card custom_accordion_card">
                            <div class="card-header p-0" id="changeUserRoleCollapse_heading">
                                <h2 class="mb-0">
                                    <button class="btn btn-block custom2_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#sv'.$sel_viola_id.'Collapse_Div" aria-expanded="true" aria-controls="sv'.$sel_viola_id.'Collapse_Div">
                                        <div class="d-flex justify-content-start align-items-center">
                                            <div class="information_div2">
                                                <span class="li_info_title">'.date('F d, Y', strtotime($get_viola_recorded_at)).' <span class="'.$class_violationStat1.'"> ' . $txt_violationStat1.'</span></span>
                                                <span class="li_info_subtitle">'.date('l - g:i A', strtotime($get_viola_recorded_at)).'</span>
                                            </div>
                                        </div>
                                        <i class="nc-icon nc-minimal-up"></i>
                                    </button>
                                </h2>
                            </div>
                            <div id="sv'.$sel_viola_id.'Collapse_Div" class="collapse show cust_collapse_active cb_t0b12y15" aria-labelledby="sv'.$sel_viola_id.'Collapse_heading" data-parent="#sv'.$sel_viola_id.'Accordion_Parent">
                                ';
                                if(!is_null(json_decode(json_encode($get_viola_minor_off), true)) OR !empty(json_decode(json_encode($get_viola_minor_off), true))){
                                    $vmo_x = 1;
                                    $output .= '
                                    <div class="card-body '.  $light_cardBody  .' mb-2">
                                        <span class="'. $light_cardBody_title  .' mb-1">Minor Offenses:</span>
                                        ';
                                        foreach(json_decode(json_encode($get_viola_minor_off), true) as $viola_minor_offenses){
                                            $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vmo_x++ .'.</span> '. $viola_minor_offenses .'</span>';
                                        }
                                        $output .='
                                    </div>
                                    ';
                                }
                                if(!is_null(json_decode(json_encode($get_viola_less_serious_off), true)) OR !empty(json_decode(json_encode($get_viola_less_serious_off), true))){
                                    $vlso_x = 1;
                                    $output .= '
                                    <div class="card-body '.  $light_cardBody  .' mb-2">
                                        <span class="'. $light_cardBody_title  .' mb-1">Less Serious Offenses:</span>
                                        ';
                                        foreach(json_decode(json_encode($get_viola_less_serious_off), true) as $viola_less_serious_offenses){
                                            $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vlso_x++ .'.</span> '. $viola_less_serious_offenses .'</span>';
                                        }
                                        $output .='
                                    </div>
                                    ';
                                }
                                if(!is_null(json_decode(json_encode($get_viola_other_off), true)) OR !empty(json_decode(json_encode($get_viola_other_off), true))){
                                    if(!in_array(null, json_decode(json_encode($get_viola_other_off), true))){
                                        $voo_x = 1;
                                        $output .= '
                                        <div class="card-body '.  $light_cardBody  .' mb-2">
                                            <span class="'. $light_cardBody_title  .' mb-1">Other Offenses:</span>
                                            ';
                                            foreach(json_decode(json_encode($get_viola_other_off), true) as $viola_other_offenses){
                                                $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $voo_x++ .'.</span> '. $viola_other_offenses .'</span>';
                                            }
                                            $output .='
                                        </div>
                                        ';
                                    }
                                }
                                if($get_viola_has_sanction > 0){
                                    // get all sanctions 
                                    $get_all_sanctions = Sanctions::select('sanct_status', 'sanct_details')
                                                                        ->where('stud_num', $sel_stud_num)
                                                                        ->where('for_viola_id', $sel_viola_id)
                                                                        ->orderBy('created_at', 'asc')
                                                                        ->offset(0)
                                                                        ->limit($get_viola_has_sanct_count)
                                                                        ->get();
                                    $count_completed_sanction = Sanctions::where('stud_num', $sel_stud_num)
                                                                        ->where('for_viola_id', $sel_viola_id)
                                                                        ->where('sanct_status', '=', 'completed')
                                                                        ->offset(0)
                                                                        ->limit($get_viola_has_sanct_count)
                                                                        ->count();
                                    $count_all_sanctions = count($get_all_sanctions);
                                    if($count_all_sanctions > 1){
                                        $sc_s = 's';
                                    }else{
                                        $sc_s = '';
                                    }
                                    $output .= '
                                    <div class="card-body lightGreen_cardBody mb-2">
                                        <div class="d-flex justify-content-between">
                                            <span class="lightGreen_cardBody_greenTitle mb-1">Sanctions:</span>
                                        </div>
                                        ';
                                        foreach($get_all_sanctions as $this_vrSanction){
                                            if($this_vrSanction->sanct_status === 'completed'){
                                                $sanct_icon = 'fa fa-check-square-o';
                                            }else{
                                                $sanct_icon = 'fa fa-square-o';
                                            }
                                            $output .= '<span class="lightGreen_cardBody_list"><i class="'.$sanct_icon . ' mr-1 font-weight-bold" aria-hidden="true"></i> ' . $this_vrSanction->sanct_details.'</span>';
                                        }
                                        $output .= '
                                    </div>
                                    ';
                                }
                                if($get_viola_has_sanction > 0){
                                    // date completed
                                    $date_completed = date('F d, Y ~ l - g:i A', strtotime($get_viola_cleared_at));
                                    if ($count_completed_sanction == count($get_all_sanctions)) {
                                        $info_icon1Class = 'fa fa-check-square-o';
                                        $sancStatusTooltip = $count_all_sanctions . ' corresponding Sanction'.$sc_s . ' for this violation has been completed by ' . $vmr_ms . ' ' . $get_violator_lname . ' on ' . $date_completed.'.';
                                    }else{
                                        $info_icon1Class = 'fa fa-list-ul';
                                        $sancStatusTooltip = $count_all_sanctions . ' corresponding Sanction'.$sc_s . ' for ' . $get_viola_offense_count . ' Offense'.$oC_s.' committed by ' . $vmr_ms . ' ' . $get_violator_lname . ' on ' . $date_recorded.'.';
                                    }
                                    $output .= '
                                    <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="' . $sancStatusTooltip . '">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                        ';
                                        $output .= '
                                            <span class="cust_info_txtwicon4 font-weight-bold"><i class="'.$info_icon1Class . ' mr-1" aria-hidden="true"></i> ' . $get_viola_has_sanct_count . ' Sanction'.$sc_s.'</span>  
                                        ';
                                        if($get_viola_status === 'cleared'){
                                            $output .= '<span class="cust_info_txtwicon"><i class="fa fa-calendar-check-o mr-1" aria-hidden="true"></i> ' . date('F d, Y ~ l - g:i A', strtotime($get_viola_cleared_at)) . '</span> ';
                                        }
                                        $output .= '
                                        </div>
                                    </div>
                                    <hr class="hr_gry">
                                    ';
                                }
                                $output .= '
                                <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="' . $recByTooltip . '">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="' .$info_textClass . ' font-weight-bold"><i class="' .$info_iconClass . ' mr-1" aria-hidden="true"></i> ' .$get_viola_offense_count. ' Offense' .$oC_s. '</span>
                                        <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> ' . $recBy . '</span>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="form_deleteViolationRec" action="'.route('violation_records.delete_violation').'" class="form" enctype="multipart/form-data" method="POST">
                    <div class="modal-body pb-0">
                    ';
                    if($get_viola_has_sanction > 0){
                        $output .= '
                        <div class="card-body lightBlue_cardBody shadow-none mb-2">
                            <span class="lightBlue_cardBody_notice"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> Deleting this recorded violation will also delete its corresponding sanctions.</span>
                        </div>
                        ';
                    }
                    $output .= '
                        <div class="card-body lightBlue_cardBody shadow-none">
                            <span class="lightBlue_cardBody_blueTitle">Reason for Deleting Violation:</span>
                            <div class="form-group">
                                <textarea class="form-control" id="delete_violation_reason" name="delete_violation_reason" rows="3" placeholder="Type reason for Deleting Recorded Violations (required)" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="for_viola_id" value="'.$sel_viola_id.'">
                        <input type="hidden" name="sel_stud_num" value="'.$sel_stud_num.'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <div class="btn-group" role="group" aria-label="delete sanctions actions">
                            <button id="cancel_deleteViolationRecBtn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_deleteViolationRecBtn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0" disabled> Delete Violation <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        ';

        echo $output;
    }
    // temporary delete all monthly violations confirmation modal
    public function delete_all_monthly_violations_form(Request $request){
        // get all request
        $sel_yearly_viola = $request->get('sel_yearly_viola');
        $sel_monthly_viola = $request->get('sel_monthly_viola');
        $sel_stud_num      = $request->get('sel_stud_num');

        // get violator's info
        $get_violator_info = Students::select('Last_Name', 'Gender')->where('Student_Number', $sel_stud_num)->first();
        $violator_Lname    = $get_violator_info->Last_Name;
        $violator_Gender   = $get_violator_info->Gender;
        // Mr./Mrs format
        $violator_gender = Str::lower($violator_Gender);
        if($violator_gender == 'male'){
            $vmr_ms = 'Mr.';
        }elseif($violator_gender == 'female'){
            $vmr_ms = 'Ms.';
        }else{
            $vmr_ms = 'Mr./Ms.';
        }

        $output = '';
        $output .= '
            <div class="modal-body border-0 p-0">
                <form id="form_deleteAllViolationRec" action="'.route('violation_records.delete_all_monthly_violations').'" class="form" enctype="multipart/form-data" method="POST">
                    <div class="cust_modal_body_gray">
                    ';
                    // query all violations for the selected month
                    $query_all_viola_info = Violations::where('stud_num', $sel_stud_num)
                    ->whereYear('recorded_at', $sel_yearly_viola)
                    ->whereMonth('recorded_at', $sel_monthly_viola)
                    ->get();

                    $count_sel_viola = count($query_all_viola_info);
                    if($count_sel_viola > 0){
                        $sum_all_offenses = 0;
                        foreach($query_all_viola_info as $query_this_viola_info){
                            // get violation info
                            $get_viola_id               = $query_this_viola_info->viola_id;
                            $get_viola_recorded_at      = $query_this_viola_info->recorded_at;
                            $get_viola_status           = $query_this_viola_info->violation_status;
                            $get_viola_offense_count    = $query_this_viola_info->offense_count;
                            $get_viola_minor_off        = $query_this_viola_info->minor_off;
                            $get_viola_less_serious_off = $query_this_viola_info->less_serious_off;
                            $get_viola_other_off        = $query_this_viola_info->other_off;
                            $get_viola_stud_num         = $query_this_viola_info->stud_num;
                            $get_viola_has_sanction     = $query_this_viola_info->has_sanction;
                            $get_viola_has_sanct_count  = $query_this_viola_info->has_sanct_count;
                            $get_viola_respo_user_id    = $query_this_viola_info->respo_user_id;
                            $get_viola_cleared_at       = $query_this_viola_info->cleared_at;

                            // sum of all offenses
                            $sum_all_offenses += $get_viola_offense_count;
                            if($sum_all_offenses > 1){
                                $sO_s = 's';
                            }else{
                                $sO_s = '';
                            }

                            // plural offense count each
                            if($get_viola_offense_count > 1){
                                $oC_s = 's';
                            }else{
                                $oC_s = '';
                            }

                            // dates
                            $date_recorded = date('F d, Y ~ l - g:i A', strtotime($get_viola_recorded_at));
                            $date_cleared = date('F d, Y ~ l - g:i A', strtotime($get_viola_cleared_at));
                            
                            // responsible user
                            if($get_viola_respo_user_id == auth()->user()->id){
                                $recBy = 'Recorded by you.';
                                $recByTooltip = 'This Violation was recorded by you on ' . $date_recorded.'.';
                            }else{
                                $get_recBy_info = Users::select('id', 'user_role', 'user_lname', 'user_fname')
                                            ->where('id', $get_viola_respo_user_id)
                                            ->first();
                                $recBy = ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_lname;
                                $recByTooltip = 'This Violation was recorded by ' . ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_fname . ' ' . $get_recBy_info->user_lname . ' on ' . $date_recorded.'.';
                            }

                            // cleared/uncleared classes
                            if($get_viola_status === 'cleared'){
                                $light_cardBody       = 'lightGreen_cardBody';
                                $light_cardBody_title = 'lightGreen_cardBody_greenTitle';
                                $light_cardBody_list  = 'lightGreen_cardBody_list';
                                $info_textClass       = 'cust_info_txtwicon4';
                                $info_iconClass       = 'fa fa-check-square-o';
                                $class_violationStat1 = 'text-success font-italic';
                                $txt_violationStat1   = '~ Cleared';
                            }else{
                                $light_cardBody       = 'lightRed_cardBody';
                                $light_cardBody_title = 'lightRed_cardBody_redTitle';
                                $light_cardBody_list  = 'lightRed_cardBody_list';
                                $info_textClass       = 'cust_info_txtwicon3';
                                $info_iconClass       = 'fa fa-exclamation-circle';
                                $class_violationStat1 = 'text_svms_red font-italic';
                                $txt_violationStat1   = '~ Not Cleared';    
                            }

                            // ouput
                            $output .= '
                            <div class="accordion shadow-none cust_accordion_div1 mb-2" id="delAllViola_SelectOption_Parent'.$get_viola_id.'">
                                <div class="card custom_accordion_card">
                                    <div class="card-header py10l15r10 d-flex justify-content-between align-items-center" id="delAllViola_SelectOption_heading'.$get_viola_id.'">
                                        <div class="form-group m-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="'.$get_viola_id.'_markDelThisViola_id" value="'.$get_viola_id.'" name="del_all_viola_id[]" class="custom-control-input cust_checkbox_label delViolMarkSingle" checked>
                                                <label class="custom-control-label cust_checkbox_label" for="'.$get_viola_id.'_markDelThisViola_id">
                                                    <span class="li_info_title"> '.date('F d, Y', strtotime($get_viola_recorded_at)).' <span class="'.$class_violationStat1.'"> ' . $txt_violationStat1.'</span></span>
                                                    <span class="li_info_subtitle">'.date('l - g:i A', strtotime($get_viola_recorded_at)).'</span>
                                                </label>
                                            </div>
                                        </div>
                                        <button class="btn cust_btn_smcircle3" type="button" data-toggle="collapse" data-target="#delAllViola_SelectOption'.$get_viola_id.'" aria-expanded="true" aria-controls="delAllViola_SelectOption'.$get_viola_id.'">
                                            <i class="nc-icon nc-minimal-down"></i>
                                        </button>
                                    </div>
                                    <div id="delAllViola_SelectOption'.$get_viola_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="delAllViola_SelectOption_heading'.$get_viola_id.'" data-parent="#delAllViola_SelectOption_Parent'.$get_viola_id.'">
                                    ';
                                    if(!is_null(json_decode(json_encode($get_viola_minor_off), true)) OR !empty(json_decode(json_encode($get_viola_minor_off), true))){
                                        $vmo_x = 1;
                                        $output .= '
                                        <div class="card-body '.  $light_cardBody  .' mb-2">
                                            <span class="'. $light_cardBody_title  .' mb-1">Minor Offenses:</span>
                                            ';
                                            foreach(json_decode(json_encode($get_viola_minor_off), true) as $viola_minor_offenses){
                                                $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vmo_x++ .'.</span> '. $viola_minor_offenses .'</span>';
                                            }
                                            $output .='
                                        </div>
                                        ';
                                    }
                                    if(!is_null(json_decode(json_encode($get_viola_less_serious_off), true)) OR !empty(json_decode(json_encode($get_viola_less_serious_off), true))){
                                        $vlso_x = 1;
                                        $output .= '
                                        <div class="card-body '.  $light_cardBody  .' mb-2">
                                            <span class="'. $light_cardBody_title  .' mb-1">Less Serious Offenses:</span>
                                            ';
                                            foreach(json_decode(json_encode($get_viola_less_serious_off), true) as $viola_less_serious_offenses){
                                                $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vlso_x++ .'.</span> '. $viola_less_serious_offenses .'</span>';
                                            }
                                            $output .='
                                        </div>
                                        ';
                                    }
                                    if(!is_null(json_decode(json_encode($get_viola_other_off), true)) OR !empty(json_decode(json_encode($get_viola_other_off), true))){
                                        if(!in_array(null, json_decode(json_encode($get_viola_other_off), true))){
                                            $voo_x = 1;
                                            $output .= '
                                            <div class="card-body '.  $light_cardBody  .' mb-2">
                                                <span class="'. $light_cardBody_title  .' mb-1">Other Offenses:</span>
                                                ';
                                                foreach(json_decode(json_encode($get_viola_other_off), true) as $viola_other_offenses){
                                                    $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $voo_x++ .'.</span> '. $viola_other_offenses .'</span>';
                                                }
                                                $output .='
                                            </div>
                                            ';
                                        }
                                    }
                                    if($get_viola_has_sanction > 0){
                                        // get all sanctions 
                                        $get_all_sanctions = Sanctions::select('sanct_status', 'sanct_details', 'completed_at')
                                                                            ->where('stud_num', $get_viola_stud_num)
                                                                            ->where('for_viola_id', $get_viola_id)
                                                                            ->orderBy('created_at', 'asc')
                                                                            ->offset(0)
                                                                            ->limit($get_viola_has_sanct_count)
                                                                            ->get();
                                        $count_completed_sanction = Sanctions::where('stud_num', $get_viola_stud_num)
                                                                            ->where('for_viola_id', $get_viola_id)
                                                                            ->where('sanct_status', '=', 'completed')
                                                                            ->offset(0)
                                                                            ->limit($get_viola_has_sanct_count)
                                                                            ->count();
                                        $count_all_sanctions = count($get_all_sanctions);
                                        if($count_all_sanctions > 1){
                                            $sc_s = 's';
                                        }else{
                                            $sc_s = '';
                                        }
                                        $output .= '
                                        <div class="card-body lightGreen_cardBody mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span class="lightGreen_cardBody_greenTitle mb-1">Sanctions:</span>
                                            </div>';

                                            foreach($get_all_sanctions as $this_vrSanction){
                                                if($this_vrSanction->sanct_status === 'completed'){
                                                    $sanct_icon = 'fa fa-check-square-o';
                                                }else{
                                                    $sanct_icon = 'fa fa-square-o';
                                                }
                                                $output .= '<span class="lightGreen_cardBody_list"><i class="'.$sanct_icon . ' mr-1 font-weight-bold" aria-hidden="true"></i> ' . $this_vrSanction->sanct_details.'</span>';
                                            }
                                            $output .= '
                                        </div>
                                        ';
                                    }
                                    if($get_viola_has_sanction > 0){
                                        // date completed
                                        $date_completed = date('F d, Y ~ l - g:i A', strtotime($get_viola_cleared_at));
                                        if ($count_completed_sanction == $count_all_sanctions) {
                                            $info_icon1Class = 'fa fa-check-square-o';
                                            $sancStatusTooltip = $count_all_sanctions . ' corresponding Sanction'.$sc_s . ' for this violation has been completed by ' . $vmr_ms . ' ' . $violator_Lname . ' on ' . $date_completed.'.';
                                        }else{
                                            $info_icon1Class = 'fa fa-list-ul';
                                            $sancStatusTooltip = $count_all_sanctions . ' corresponding Sanction'.$sc_s . ' for ' . $get_viola_offense_count . ' Offense'.$oC_s.' committed by ' . $vmr_ms . ' ' . $violator_Lname . ' on ' . $date_recorded.'.';
                                        }
                                        $output .= '
                                        <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="' . $sancStatusTooltip . '">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                ';
                                                $output .= '
                                                    <span class="cust_info_txtwicon4 font-weight-bold"><i class="'.$info_icon1Class . ' mr-1" aria-hidden="true"></i> ' . $get_viola_has_sanct_count . ' Sanction'.$sc_s.'</span>  
                                                ';
                                                if($get_viola_status === 'cleared'){
                                                    $output .= '<span class="cust_info_txtwicon"><i class="fa fa-calendar-check-o mr-1" aria-hidden="true"></i> ' . date('F d, Y ~ l - g:i A', strtotime($get_viola_cleared_at)) . '</span> ';
                                                }
                                                $output .= '
                                            </div>
                                        </div>
                                        <hr class="hr_gry">
                                        ';
                                    }
                                    $output .='
                                        <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="' . $recByTooltip . '">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <span class="' .$info_textClass . ' font-weight-bold"><i class="' .$info_iconClass . ' mr-1" aria-hidden="true"></i> ' .$get_viola_offense_count. ' Offense' .$oC_s. '</span>
                                                <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> ' . $recBy . '</span>  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ';
                        }
                    }
                    $output .= '
                    </div>
                    <div class="modal-body pb-0">
                        <div class="card-body lightBlue_cardBody shadow-none mb-2">
                            <div class="row mb-2">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group mx-0 mt-0 mb-1">
                                        <div class="custom-control custom-checkbox align-items-center">
                                            <input type="checkbox" name="delete_all_violations" value="delete_all_violations" class="custom-control-input cursor_pointer" id="delViolMarkAll" checked>
                                            <label class="custom-control-label lightBlue_cardBody_chckboxLabel" for="delViolMarkAll">Delete All ('.$sum_all_offenses.') Offense'.$sO_s.'.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="lightBlue_cardBody_notice"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> Deleting recorded violation will also delete its corresponding sanctions.</span>
                        </div>
                        <div class="card-body lightBlue_cardBody shadow-none">
                            <span class="lightBlue_cardBody_blueTitle">Reason for Deleting Violation:</span>
                            <div class="form-group">
                                <textarea class="form-control" id="delete_all_violation_reason" name="delete_all_violation_reason" rows="3" placeholder="Type reason for Deleting Recorded Violations (required)" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="sel_stud_num" value="'.$sel_stud_num.'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <div class="btn-group" role="group" aria-label="delete sanctions actions">
                            <button id="cancel_deleteAllViolationRecBtn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_deleteAllViolationRecBtn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0" disabled> Delete Violation <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        ';

        echo $output;

        // try
        // echo 'Student Number: ' . $sel_stud_num . '<br/>';
        // echo 'Year: ' . $sel_yearly_viola . '<br/>';
        // echo 'Month: ' . $sel_monthly_viola . '<br/>';
        // echo 'violation count: ' . $count_sel_viola . '<br/>';
        // echo 'Offenses count: ' . $sum_all_offenses . '<br/>';
    }
    // process temporary deletion of violation
    public function delete_violation(Request $request){
        // custom values
            $now_timestamp     = now();
        // get all request
            $sel_viola_id      = $request->get('for_viola_id');
            $sel_stud_num      = $request->get('sel_stud_num');
            $sel_respo_user_id     = $request->get('respo_user_id');
            $sel_respo_user_lname  = $request->get('respo_user_lname');
            $sel_respo_user_fname  = $request->get('respo_user_fname');  
            $sel_del_viola_reason  = $request->get('delete_violation_reason');  
        // try
            // echo 'violatin ID: ' . $sel_for_viola_id . '<br/>';
            // echo 'Student ID: ' . $sel_sel_stud_num . '<br/>';
            // echo 'Responsible user ID: ' . $sel_respo_user_id . '<br/>';
            // echo 'Responsible user name: ' . $sel_respo_user_lname . ' ' . $sel_respo_user_fname . '<br/>';
            // echo 'Reason for deletion: ' . $sel_del_viola_reason . '<br/>';
        // get selection violation original data from vilations_tbl
            $get_org_viola_data = Violations::where('viola_id', $sel_viola_id)
                                        ->where('stud_num', $sel_stud_num)
                                        ->first();
            $org_Vrecorded_at      = $get_org_viola_data->recorded_at;
            $org_Vviolation_status = $get_org_viola_data->violation_status;
            $org_Voffense_count    = $get_org_viola_data->offense_count;
            $org_Vminor_off        = $get_org_viola_data->minor_off;
            $org_Vless_serious_off = $get_org_viola_data->less_serious_off;
            $org_Vother_off        = $get_org_viola_data->other_off;
            $org_Vstud_num         = $get_org_viola_data->stud_num;
            $org_Vhas_sanction     = $get_org_viola_data->has_sanction;
            $org_Vhas_sanct_count  = $get_org_viola_data->has_sanct_count;
            $org_Vrespo_user_id    = $get_org_viola_data->respo_user_id;
            $org_Vcleared_at       = $get_org_viola_data->cleared_at;
        // save original record to deleted_violations_tbl
            $backup_violation = new Deletedviolations;
            $backup_violation->from_viola_id        = $sel_viola_id;
            $backup_violation->del_recorded_at      = $org_Vrecorded_at;
            $backup_violation->del_violation_status = $org_Vviolation_status;
            $backup_violation->del_offense_count    = $org_Voffense_count;
            $backup_violation->del_minor_off        = $org_Vminor_off;
            $backup_violation->del_less_serious_off = $org_Vless_serious_off;
            $backup_violation->del_other_off        = $org_Vother_off;
            $backup_violation->del_stud_num         = $org_Vstud_num;
            $backup_violation->del_has_sanction     = $org_Vhas_sanction;
            $backup_violation->del_has_sanct_count  = $org_Vhas_sanct_count;
            $backup_violation->del_respo_user_id    = $org_Vrespo_user_id;
            $backup_violation->del_cleared_at       = $org_Vcleared_at;
            $backup_violation->reason_deletion      = $sel_del_viola_reason;
            $backup_violation->respo_user_id        = $sel_respo_user_id;
            $backup_violation->deleted_at           = $now_timestamp;
            $backup_violation->save();
        // delete violation from violations_tbl
            if($backup_violation){
                $delete_org_viola_data = Violations::where('viola_id', $sel_viola_id)
                                        ->where('stud_num', $sel_stud_num)
                                        ->delete();
                // if deletion was a success
                if($delete_org_viola_data){
                    // get latest del if from deleted_violations_tbl
                        $to_array_deleted_viola_ids = array();
                        $get_latest_del_id = Deletedviolations::select('del_id')
                                                ->where('from_viola_id', $sel_viola_id)
                                                ->latest('deleted_at')
                                                ->first();
                        $latest_del_id = $get_latest_del_id->del_id;
                        array_push($to_array_deleted_viola_ids, $latest_del_id);
                        $add_Bracket = array_values($to_array_deleted_viola_ids);
                        $to_Json_latest_del_id = json_encode($add_Bracket);
                        $ext_jsonDeletedViola_ids = str_replace(array( '{', '}', '"', ':', 'del_id' ), '', $to_Json_latest_del_id);
                    // delete corresponding sanctions from sanctions_tbl
                    if($org_Vhas_sanction > 0){
                        // check if corresponding sanctions exist from sanctions_tbl
                        $check_sanct_exist = Sanctions::where('for_viola_id', $sel_viola_id)
                                                            ->where('stud_num', $sel_stud_num)
                                                            ->offset(0)
                                                            ->limit($org_Vhas_sanct_count)
                                                            ->count();
                        if($check_sanct_exist > 0){
                            $get_org_corresponding_sancts = Sanctions::where('for_viola_id', $sel_viola_id)
                                                            ->where('stud_num', $sel_stud_num)
                                                            ->offset(0)
                                                            ->limit($org_Vhas_sanct_count)
                                                            ->get();
                            // save original sanctions to deleted_sanctions_tbl
                            foreach($get_org_corresponding_sancts as $save_org_sanction){
                                $save_tobe_deleted = new Deletedsanctions;
                                $save_tobe_deleted->del_from_sanct_id = $save_org_sanction->sanct_id;
                                $save_tobe_deleted->del_by_user_id    = $sel_respo_user_id;
                                $save_tobe_deleted->deleted_at        = $now_timestamp;
                                $save_tobe_deleted->reason_deletion   = 'reason';
                                $save_tobe_deleted->del_stud_num      = $save_org_sanction->stud_num;
                                $save_tobe_deleted->del_sanct_status  = $save_org_sanction->sanct_status;
                                $save_tobe_deleted->del_sanct_details = $save_org_sanction->sanct_details;
                                $save_tobe_deleted->del_for_viola_id  = $save_org_sanction->for_viola_id;
                                $save_tobe_deleted->del_respo_user_id = $save_org_sanction->respo_user_id;
                                $save_tobe_deleted->del_created_at    = $save_org_sanction->created_at;
                                $save_tobe_deleted->del_completed_at  = $save_org_sanction->completed_at;
                                $save_tobe_deleted->save();
                            }
                            // delete each sanctions from sanctions_tbl
                            if($save_tobe_deleted){
                                foreach($get_org_corresponding_sancts as $delete_org_sanction){
                                    $delete_from_sanctions_tbl = Sanctions::where('sanct_id', $delete_org_sanction->sanct_id)
                                                            ->where('stud_num', $delete_org_sanction->stud_num)
                                                            ->where('for_viola_id', $delete_org_sanction->for_viola_id)
                                                            ->delete();
                                }
                            }
                        }
                    }
                    // custom values
                        if($org_Voffense_count > 1){
                            $ovc_s = 's';
                        }else{
                            $ovc_s = '';
                            }
                    // get selected student's info from students_tbl
                        $sel_stud_info = Students::select('Student_Number', 'First_Name', 'Middle_Name', 'Last_Name', 'Email', 'School_Name', 'Course', 'YearLevel')
                                    ->where('Student_Number', $sel_stud_num)
                                    ->first();
                        $sel_stud_Fname       = $sel_stud_info->First_Name;
                        $sel_stud_Mname       = $sel_stud_info->Middle_Name;
                        $sel_stud_Lname       = $sel_stud_info->Last_Name;
                        $sel_stud_Email       = $sel_stud_info->Email;
                        $sel_stud_School_Name = $sel_stud_info->School_Name;
                        $sel_stud_Course      = $sel_stud_info->Course;
                        $sel_stud_YearLevel   = $sel_stud_info->YearLevel;
                        // year level
                        if($sel_stud_YearLevel === '1'){
                            $yearLevel_txt = '1st Year';
                        }else if($sel_stud_YearLevel === '2'){
                            $yearLevel_txt = '2nd Year';
                        }else if($sel_stud_YearLevel === '3'){
                            $yearLevel_txt = '3rd Year';
                        }else if($sel_stud_YearLevel === '4'){
                            $yearLevel_txt = '4th Year';
                        }else if($sel_stud_YearLevel === '5'){
                            $yearLevel_txt = '5th Year';
                        }else{
                            $yearLevel_txt = $sel_stud_YearLevel . ' Year';
                        }
                    // record activity
                        $record_act = new Useractivites;
                        $record_act->created_at            = $now_timestamp;
                        $record_act->act_respo_user_id     = $sel_respo_user_id;
                        $record_act->act_respo_users_lname = $sel_respo_user_lname;
                        $record_act->act_respo_users_fname = $sel_respo_user_fname;
                        $record_act->act_type              = 'violation deletion';
                        $record_act->act_details           = 'Temporarily Deleted ' . $org_Voffense_count . ' Offense'.$ovc_s . ' made by ' . $yearLevel_txt . ' ' . $sel_stud_Course . ' student: ' . $sel_stud_Fname . ' ' . $sel_stud_Mname . ' ' . $sel_stud_Lname . ' on ' . date('F d, Y', strtotime($org_Vrecorded_at)).'.';
                        $record_act->act_deleted_viola_ids = $ext_jsonDeletedViola_ids;
                        $record_act->save();

                    return back()->withSuccessStatus('Recorded Violation with ' . $org_Voffense_count . ' Offense'.$ovc_s.' was deleted successfully.');
                }else{
                    return back()->withFailedStatus(' Deleting Recorded Violation has Failed! try again later.');
                }
            }else{
                return back()->withFailedStatus(' Performing Backup for the Deleted Violations has failed.');
            }
    }
    // process temporary deletion of all violation
    public function delete_all_monthly_violations(Request $request){
        // custom values
        $now_timestamp     = now();

        // get all request
        $get_sel_stud_num           = $request->get('sel_stud_num');
        $get_respo_user_id          = $request->get('respo_user_id');
        $get_respo_user_lname       = $request->get('respo_user_lname');
        $get_respo_user_fname       = $request->get('respo_user_fname');  
        $get_tobe_deleted_viola_ids = json_decode(json_encode($request->get('del_all_viola_id')));
        $get_reason_deletion        = $request->get('delete_all_violation_reason'); 
        
        $count_selected_viola_ids = count($get_tobe_deleted_viola_ids);
        if($count_selected_viola_ids > 1){
            $tbV_s = 's';
        }else{
            $tbV_s = '';
        }

        // try
            // echo 'student number: ' . $get_sel_stud_num . '<br/>';
            // echo 'violation count: ' . count($get_tobe_deleted_viola_ids) . '<br/>';
            // echo 'selected violation ids: <br/>';
            // if(count($get_tobe_deleted_viola_ids) > 0){
            //     foreach($get_tobe_deleted_viola_ids as $delete_this_viola_id){
            //         echo ': ' . $delete_this_viola_id . '<br/>';
            //     }
            // }
            // echo 'Responsible user: ' . $get_respo_user_id.': ' . $get_respo_user_fname . ' ' . $get_respo_user_lname . '<br/>';
            // echo 'Reason for deletion: ' . $get_reason_deletion . ' <br />';

        // process deletion
        if($count_selected_viola_ids > 0){
            $sum_all_offenses = 0;
            foreach($get_tobe_deleted_viola_ids as $delete_this_viola_id){
                // get selection violation original data from vilations_tbl
                $get_org_viola_data = Violations::where('viola_id', $delete_this_viola_id)
                                        ->where('stud_num', $get_sel_stud_num)
                                        ->first();

                $org_Vrecorded_at      = $get_org_viola_data->recorded_at;
                $org_Vviolation_status = $get_org_viola_data->violation_status;
                $org_Voffense_count    = $get_org_viola_data->offense_count;
                $org_Vminor_off        = $get_org_viola_data->minor_off;
                $org_Vless_serious_off = $get_org_viola_data->less_serious_off;
                $org_Vother_off        = $get_org_viola_data->other_off;
                $org_Vstud_num         = $get_org_viola_data->stud_num;
                $org_Vhas_sanction     = $get_org_viola_data->has_sanction;
                $org_Vhas_sanct_count  = $get_org_viola_data->has_sanct_count;
                $org_Vrespo_user_id    = $get_org_viola_data->respo_user_id;
                $org_Vcleared_at       = $get_org_viola_data->cleared_at;

                // sum of all offenses
                $sum_all_offenses += $org_Voffense_count;
                if($sum_all_offenses > 1){
                    $sO_s = 's';
                }else{
                    $sO_s = '';
                }

                // save original record to deleted_violations_tbl
                $backup_violation = new Deletedviolations;
                $backup_violation->from_viola_id        = $delete_this_viola_id;
                $backup_violation->del_recorded_at      = $org_Vrecorded_at;
                $backup_violation->del_violation_status = $org_Vviolation_status;
                $backup_violation->del_offense_count    = $org_Voffense_count;
                $backup_violation->del_minor_off        = $org_Vminor_off;
                $backup_violation->del_less_serious_off = $org_Vless_serious_off;
                $backup_violation->del_other_off        = $org_Vother_off;
                $backup_violation->del_stud_num         = $org_Vstud_num;
                $backup_violation->del_has_sanction     = $org_Vhas_sanction;
                $backup_violation->del_has_sanct_count  = $org_Vhas_sanct_count;
                $backup_violation->del_respo_user_id    = $org_Vrespo_user_id;
                $backup_violation->del_cleared_at       = $org_Vcleared_at;
                $backup_violation->reason_deletion      = $get_reason_deletion;
                $backup_violation->respo_user_id        = $get_respo_user_id;
                $backup_violation->deleted_at           = $now_timestamp;
                $backup_violation->save();

                // if backup was a success
                if($backup_violation){
                    // delete each violation
                    $delete_org_viola_data = Violations::where('viola_id', $delete_this_viola_id)
                                        ->where('stud_num', $get_sel_stud_num)
                                        ->delete();
                // if deletion was a success
                if($delete_org_viola_data){
                    // get latest del if from deleted_violations_tbl
                        $to_array_deleted_viola_ids = array();
                        $get_latest_del_id = Deletedviolations::select('del_id')
                                                ->where('from_viola_id', $delete_this_viola_id)
                                                ->latest('deleted_at')
                                                ->first();
                        $latest_del_id = $get_latest_del_id->del_id;
                        array_push($to_array_deleted_viola_ids, $latest_del_id);
                        $add_Bracket = array_values($to_array_deleted_viola_ids);
                        $to_Json_latest_del_id = json_encode($add_Bracket);
                        $ext_jsonDeletedViola_ids = str_replace(array( '{', '}', '"', ':', 'del_id' ), '', $to_Json_latest_del_id);
                    // delete corresponding sanctions from sanctions_tbl
                    if($org_Vhas_sanction > 0){
                        // check if corresponding sanctions exist from sanctions_tbl
                        $check_sanct_exist = Sanctions::where('for_viola_id', $delete_this_viola_id)
                                                            ->where('stud_num', $get_sel_stud_num)
                                                            ->offset(0)
                                                            ->limit($org_Vhas_sanct_count)
                                                            ->count();
                        if($check_sanct_exist > 0){
                            $get_org_corresponding_sancts = Sanctions::where('for_viola_id', $delete_this_viola_id)
                                                            ->where('stud_num', $get_sel_stud_num)
                                                            ->offset(0)
                                                            ->limit($org_Vhas_sanct_count)
                                                            ->get();
                            // save original sanctions to deleted_sanctions_tbl
                            foreach($get_org_corresponding_sancts as $save_org_sanction){
                                $save_tobe_deleted = new Deletedsanctions;
                                $save_tobe_deleted->del_from_sanct_id = $save_org_sanction->sanct_id;
                                $save_tobe_deleted->del_by_user_id    = $get_respo_user_id;
                                $save_tobe_deleted->deleted_at        = $now_timestamp;
                                $save_tobe_deleted->reason_deletion   = 'reason';
                                $save_tobe_deleted->del_stud_num      = $save_org_sanction->stud_num;
                                $save_tobe_deleted->del_sanct_status  = $save_org_sanction->sanct_status;
                                $save_tobe_deleted->del_sanct_details = $save_org_sanction->sanct_details;
                                $save_tobe_deleted->del_for_viola_id  = $save_org_sanction->for_viola_id;
                                $save_tobe_deleted->del_respo_user_id = $save_org_sanction->respo_user_id;
                                $save_tobe_deleted->del_created_at    = $save_org_sanction->created_at;
                                $save_tobe_deleted->del_completed_at  = $save_org_sanction->completed_at;
                                $save_tobe_deleted->save();
                            }
                            // delete each sanctions from sanctions_tbl
                            if($save_tobe_deleted){
                                foreach($get_org_corresponding_sancts as $delete_org_sanction){
                                    $delete_from_sanctions_tbl = Sanctions::where('sanct_id', $delete_org_sanction->sanct_id)
                                                            ->where('stud_num', $delete_org_sanction->stud_num)
                                                            ->where('for_viola_id', $delete_org_sanction->for_viola_id)
                                                            ->delete();
                                }
                            }
                        }
                    }
                    // custom values
                        if($org_Voffense_count > 1){
                            $ovc_s = 's';
                        }else{
                            $ovc_s = '';
                            }
                    // get selected student's info from students_tbl
                        $sel_stud_info = Students::select('Student_Number', 'First_Name', 'Middle_Name', 'Last_Name', 'Email', 'School_Name', 'Course', 'YearLevel')
                                    ->where('Student_Number', $get_sel_stud_num)
                                    ->first();
                        $sel_stud_Fname       = $sel_stud_info->First_Name;
                        $sel_stud_Mname       = $sel_stud_info->Middle_Name;
                        $sel_stud_Lname       = $sel_stud_info->Last_Name;
                        $sel_stud_Email       = $sel_stud_info->Email;
                        $sel_stud_School_Name = $sel_stud_info->School_Name;
                        $sel_stud_Course      = $sel_stud_info->Course;
                        $sel_stud_YearLevel   = $sel_stud_info->YearLevel;
                        // year level
                        if($sel_stud_YearLevel === '1'){
                            $yearLevel_txt = '1st Year';
                        }else if($sel_stud_YearLevel === '2'){
                            $yearLevel_txt = '2nd Year';
                        }else if($sel_stud_YearLevel === '3'){
                            $yearLevel_txt = '3rd Year';
                        }else if($sel_stud_YearLevel === '4'){
                            $yearLevel_txt = '4th Year';
                        }else if($sel_stud_YearLevel === '5'){
                            $yearLevel_txt = '5th Year';
                        }else{
                            $yearLevel_txt = $sel_stud_YearLevel . ' Year';
                        }
                    // record activity
                        $record_act = new Useractivites;
                        $record_act->created_at            = $now_timestamp;
                        $record_act->act_respo_user_id     = $get_respo_user_id;
                        $record_act->act_respo_users_lname = $get_respo_user_lname;
                        $record_act->act_respo_users_fname = $get_respo_user_fname;
                        $record_act->act_type              = 'violation deletion';
                        $record_act->act_details           = 'Temporarily Deleted ' . $org_Voffense_count . ' Offense'.$ovc_s . ' made by ' . $yearLevel_txt . ' ' . $sel_stud_Course . ' student: ' . $sel_stud_Fname . ' ' . $sel_stud_Mname . ' ' . $sel_stud_Lname . ' on ' . date('F d, Y', strtotime($org_Vrecorded_at)).'.';
                        $record_act->act_deleted_viola_ids = $ext_jsonDeletedViola_ids;
                        $record_act->save();

                }else{
                    return back()->withFailedStatus(' Deleting Recorded Violation has Failed! try again later.');
                }
                }else{
                    return back()->withFailedStatus(' Performing Backup for the Deleted Violations has failed.');
                }
            }
            if($record_act){
                return back()->withSuccessStatus('Recorded Violation'.$tbV_s . ' with ' . $sum_all_offenses . ' Offense'.$sO_s.' was deleted successfully.');
            }else{
                return back()->withFailedStatus(' Deleting Recorded Violation has Failed! try again later.');
            }
        }else{
            return back()->withFailedStatus(' There are no selected Violations! please try again.');
        }
    }
    // permanent delete violation record
    public function permanently_delete_violation_form(Request $request){
        // get all request
            $sel_viola_id = $request->get('sel_viola_id');
            $sel_stud_num = $request->get('sel_stud_num');
        // get violation's info
            $get_viola_info = Deletedviolations::where('from_viola_id', $sel_viola_id)->first();
            $get_viola_recorded_at      = $get_viola_info->del_recorded_at;
            $get_viola_status           = $get_viola_info->del_violation_status;
            $get_viola_offense_count    = $get_viola_info->del_offense_count;
            $get_viola_minor_off        = $get_viola_info->del_minor_off;
            $get_viola_less_serious_off = $get_viola_info->del_less_serious_off;
            $get_viola_other_off        = $get_viola_info->del_other_off;
            $get_viola_stud_num         = $get_viola_info->del_stud_num;
            $get_viola_has_sanction     = $get_viola_info->del_has_sanction;
            $get_viola_has_sanct_count  = $get_viola_info->del_has_sanct_count;
            $get_viola_respo_user_id    = $get_viola_info->del_respo_user_id;
            $get_viola_cleared_at       = $get_viola_info->del_cleared_at;
            $get_viola_reason_deletion  = $get_viola_info->reason_deletion;
            $get_viola_deleted_by       = $get_viola_info->respo_user_id;
            $get_viola_deleted_at       = $get_viola_info->deleted_at;
        // get violator's info
            $get_violator_info = Students::select('Last_Name', 'Gender')->where('Student_Number', $sel_stud_num)->first();
            $violator_Lname    = $get_violator_info->Last_Name;
            $violator_Gender   = $get_violator_info->Gender;
            // Mr./Mrs format
            $violator_gender = Str::lower($violator_Gender);
            if($violator_gender == 'male'){
                $violator_mr_ms   = 'Mr.';
            }elseif($violator_gender == 'female'){
                $violator_mr_ms   = 'Ms.';
            }else{
                $violator_mr_ms   = 'Mr./Ms.';
            }
        // custom values
        // plural offense count
            if($get_viola_offense_count > 1){
                $oC_s = 's';
            }else{
                $oC_s = '';
            }
            // dates
            $date_recorded = date('F d, Y ~ l - g:i A', strtotime($get_viola_recorded_at));
            $date_deleted = date('F d, Y ~ l - g:i A', strtotime($get_viola_deleted_at));
            // violator's last name and Mr./Mrs
            $query_violator_info = Students::select('Last_Name', 'Gender')
                                            ->where('Student_Number', $sel_stud_num)
                                            ->first();
            $get_violator_lname = $query_violator_info->Last_Name;
            $get_violator_gender = strtolower($query_violator_info->Gender);
            if($get_violator_gender === 'male'){
                $vmr_ms = 'Mr.';
            }elseif($get_violator_gender === 'female'){
                $vmr_ms = 'Ms.';
            }else{
                $vmr_ms = 'Mr./Ms.';
            }
            // responsible user
            if($get_viola_respo_user_id == auth()->user()->id){
                $recBy = 'Recorded by you.';
                $recByTooltip = 'This Violation was recorded by you on ' . $date_recorded.'.';
            }else{
                $get_recBy_info = Users::select('id', 'user_role', 'user_lname', 'user_fname')
                                        ->where('id', $get_viola_respo_user_id)
                                        ->first();
                $recBy = ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_lname;
                $recByTooltip = 'This Violation was recorded by ' . ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_fname . ' ' . $get_recBy_info->user_lname . ' on ' . $date_recorded.'.';
            }
            // responsible user (deleted violations)
            if($get_viola_deleted_by == auth()->user()->id){
                $delBy = 'Deleted by you.';
                $delByTooltip = 'This Violation was deleted by you on ' . $date_deleted.'.';
            }else{
                $get_delBy_info = App\Models\Users::select('id', 'user_role', 'user_lname', 'user_fname')
                                        ->where('id', $get_viola_deleted_by)
                                        ->first();
                $delBy = ucwords($get_delBy_info->user_role).': ' . $get_delBy_info->user_lname;
                $delByTooltip = 'This Violation was deleted by ' . ucwords($get_delBy_info->user_role).': ' . $get_delBy_info->user_fname . ' ' . $get_delBy_info->user_lname . ' on ' . $date_deleted.'.';
            }
            // cleared/uncleared classes
            if($get_viola_status === 'cleared'){
                $light_cardBody       = 'lightGreen_cardBody';
                $light_cardBody_title = 'lightGreen_cardBody_greenTitle';
                $light_cardBody_list  = 'lightGreen_cardBody_list';
                $info_textClass       = 'cust_info_txtwicon4';
                $info_iconClass       = 'fa fa-check-square-o';
                $class_violationStat1 = 'text-success font-italic';
                $txt_violationStat1   = '~ Cleared';
            }else{
                $light_cardBody       = 'lightRed_cardBody';
                $light_cardBody_title = 'lightRed_cardBody_redTitle';
                $light_cardBody_list  = 'lightRed_cardBody_list';
                $info_textClass       = 'cust_info_txtwicon3';
                $info_iconClass       = 'fa fa-exclamation-circle';
                $class_violationStat1 = 'text_svms_red font-italic';
                $txt_violationStat1   = '~ Not Cleared';    
            }

        $output = '';
        $output .= '
            <div class="modal-body border-0 p-0">
                <div class="cust_modal_body_gray">
                    <div class="accordion shadow cust_accordion_div" id="sv'.$sel_viola_id.'Accordion_Parent">
                        <div class="card custom_accordion_card">
                            <div class="card-header p-0" id="changeUserRoleCollapse_heading">
                                <h2 class="mb-0">
                                    <button class="btn btn-block custom2_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#sv'.$sel_viola_id.'Collapse_Div" aria-expanded="true" aria-controls="sv'.$sel_viola_id.'Collapse_Div">
                                        <div class="d-flex justify-content-start align-items-center">
                                            <div class="information_div2">
                                                <span class="li_info_title">'.date('F d, Y', strtotime($get_viola_recorded_at)).' <span class="'.$class_violationStat1.'"> ' . $txt_violationStat1.'</span></span>
                                                <span class="li_info_subtitle">'.date('l - g:i A', strtotime($get_viola_recorded_at)).'</span>
                                            </div>
                                        </div>
                                        <i class="nc-icon nc-minimal-up"></i>
                                    </button>
                                </h2>
                            </div>
                            <div id="sv'.$sel_viola_id.'Collapse_Div" class="collapse show cust_collapse_active cb_t0b12y15" aria-labelledby="sv'.$sel_viola_id.'Collapse_heading" data-parent="#sv'.$sel_viola_id.'Accordion_Parent">
                                ';
                                if(!is_null(json_decode(json_encode($get_viola_minor_off), true)) OR !empty(json_decode(json_encode($get_viola_minor_off), true))){
                                    $vmo_x = 1;
                                    $output .= '
                                    <div class="card-body '.  $light_cardBody  .' mb-2">
                                        <span class="'. $light_cardBody_title  .' mb-1">Minor Offenses:</span>
                                        ';
                                        foreach(json_decode(json_encode($get_viola_minor_off), true) as $viola_minor_offenses){
                                            $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vmo_x++ .'.</span> '. $viola_minor_offenses .'</span>';
                                        }
                                        $output .='
                                    </div>
                                    ';
                                }
                                if(!is_null(json_decode(json_encode($get_viola_less_serious_off), true)) OR !empty(json_decode(json_encode($get_viola_less_serious_off), true))){
                                    $vlso_x = 1;
                                    $output .= '
                                    <div class="card-body '.  $light_cardBody  .' mb-2">
                                        <span class="'. $light_cardBody_title  .' mb-1">Less Serious Offenses:</span>
                                        ';
                                        foreach(json_decode(json_encode($get_viola_less_serious_off), true) as $viola_less_serious_offenses){
                                            $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vlso_x++ .'.</span> '. $viola_less_serious_offenses .'</span>';
                                        }
                                        $output .='
                                    </div>
                                    ';
                                }
                                if(!is_null(json_decode(json_encode($get_viola_other_off), true)) OR !empty(json_decode(json_encode($get_viola_other_off), true))){
                                    if(!in_array(null, json_decode(json_encode($get_viola_other_off), true))){
                                        $voo_x = 1;
                                        $output .= '
                                        <div class="card-body '.  $light_cardBody  .' mb-2">
                                            <span class="'. $light_cardBody_title  .' mb-1">Other Offenses:</span>
                                            ';
                                            foreach(json_decode(json_encode($get_viola_other_off), true) as $viola_other_offenses){
                                                $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $voo_x++ .'.</span> '. $viola_other_offenses .'</span>';
                                            }
                                            $output .='
                                        </div>
                                        ';
                                    }
                                }
                                if($get_viola_has_sanction > 0){
                                    // get all sanctions 
                                    $get_all_sanctions = Deletedsanctions::select('del_sanct_status', 'del_sanct_details', 'del_completed_at')
                                                                        ->where('del_stud_num', $sel_stud_num)
                                                                        ->where('del_for_viola_id', $sel_viola_id)
                                                                        ->orderBy('del_created_at', 'asc')
                                                                        ->offset(0)
                                                                        ->limit($get_viola_has_sanct_count)
                                                                        ->get();
                                    $count_completed_sanction = Deletedsanctions::where('del_stud_num', $sel_stud_num)
                                                                        ->where('del_for_viola_id', $sel_viola_id)
                                                                        ->where('del_sanct_status', '=', 'completed')
                                                                        ->offset(0)
                                                                        ->limit($get_viola_has_sanct_count)
                                                                        ->count();
                                    $count_all_sanctions = count($get_all_sanctions);
                                    if($count_all_sanctions > 1){
                                        $sc_s = 's';
                                    }else{
                                        $sc_s = '';
                                    }
                                    $output .= '
                                    <div class="card-body lightGreen_cardBody mb-2">
                                        <div class="d-flex justify-content-between">
                                            <span class="lightGreen_cardBody_greenTitle mb-1">Sanctions:</span>
                                        </div>';

                                        foreach($get_all_sanctions as $this_vrSanction){
                                            if($this_vrSanction->del_sanct_status === 'completed'){
                                                $sanct_icon = 'fa fa-check-square-o';
                                            }else{
                                                $sanct_icon = 'fa fa-square-o';
                                            }
                                            $output .= '<span class="lightGreen_cardBody_list"><i class="'.$sanct_icon . ' mr-1 font-weight-bold" aria-hidden="true"></i> ' . $this_vrSanction->del_sanct_details.'</span>';
                                        }
                                        $output .= '
                                    </div>
                                    ';
                                }
                                if($get_viola_has_sanction > 0){
                                    // date completed
                                    $date_completed = date('F d, Y ~ l - g:i A', strtotime($get_viola_cleared_at));
                                    if ($count_completed_sanction == $count_all_sanctions) {
                                        $info_icon1Class = 'fa fa-check-square-o';
                                        $sancStatusTooltip = $count_all_sanctions . ' corresponding Sanction'.$sc_s . ' for this violation has been completed by ' . $vmr_ms . ' ' . $get_violator_lname . ' on ' . $date_completed.'.';
                                    }else{
                                        $info_icon1Class = 'fa fa-list-ul';
                                        $sancStatusTooltip = $count_all_sanctions . ' corresponding Sanction'.$sc_s . ' for ' . $get_viola_offense_count . ' Offense'.$oC_s.' committed by ' . $vmr_ms . ' ' . $get_violator_lname . ' on ' . $date_recorded.'.';
                                    }
                                    $output .= '
                                    <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="' . $sancStatusTooltip . '">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            ';
                                            $output .= '
                                                <span class="cust_info_txtwicon4 font-weight-bold"><i class="'.$info_icon1Class . ' mr-1" aria-hidden="true"></i> ' . $get_viola_has_sanct_count . ' Sanction'.$sc_s.'</span>  
                                            ';
                                            if($get_viola_status === 'cleared'){
                                                $output .= '<span class="cust_info_txtwicon"><i class="fa fa-calendar-check-o mr-1" aria-hidden="true"></i> ' . date('F d, Y ~ l - g:i A', strtotime($get_viola_cleared_at)) . '</span> ';
                                            }
                                            $output .= '
                                        </div>
                                    </div>
                                    <hr class="hr_gry">
                                    ';
                                }
                                $output .= '
                                <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="' . $recByTooltip . '">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="' .$info_textClass . ' font-weight-bold"><i class="' .$info_iconClass . ' mr-1" aria-hidden="true"></i> ' .$get_viola_offense_count. ' Offense' .$oC_s. '</span>
                                        <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> ' . $recBy . '</span>  
                                    </div>
                                </div>
                                ';
                                if(!is_null($get_viola_reason_deletion) OR !empty($get_viola_reason_deletion)){
                                    $output .= '
                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="card-body lightBlue_cardBody shadow-none">
                                                <span class="lightBlue_cardBody_blueTitle">Reason for Deleting Violation:</span>
                                                <span class="lightBlue_cardBody_list">'.$get_viola_reason_deletion.'</span>
                                            </div>
                                        </div>
                                    </div>
                                    ';
                                }
                                $output .= '
                                <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="' . $delByTooltip . '">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="cust_info_txtwicon"><i class="fa fa-calendar-minus-o mr-1" aria-hidden="true"></i> ' . date('F d, Y ~ l - g:i A', strtotime($get_viola_deleted_at)) . '</span> 
                                        <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> ' . $delBy . '</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="form_permDeleteViolationRec" action="'.route('violation_records.permanent_delete_violation').'" class="form" enctype="multipart/form-data" method="POST">
                    <div class="modal-body pb-0">
                        <div class="card-body lightBlue_cardBody shadow-none">
                            <span class="lightBlue_cardBody_notice"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> This action will permanently Delete the recorded violation and its corresponding sanctions.</span>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="perm_delete_viola_ids[]" value="'.$sel_viola_id.'">
                        <input type="hidden" name="sel_stud_num" value="'.$sel_stud_num.'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <div class="btn-group" role="group" aria-label="delete sanctions actions">
                            <button id="cancel_permDeleteViolationRecBtn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_permDeleteViolationRecBtn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0"> Delete Forever <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        ';

        echo $output;
    }
    // process permanent deletion of violation
    public function permanent_delete_violation(Request $request){
        // custom values
            $now_timestamp     = now();
        // get all request
            $sel_viola_ids         = json_decode(json_encode($request->get('perm_delete_viola_ids'))); 
            $sel_stud_num          = $request->get('sel_stud_num');
            $sel_respo_user_id     = $request->get('respo_user_id');
            $sel_respo_user_lname  = $request->get('respo_user_lname');
            $sel_respo_user_fname  = $request->get('respo_user_fname');  
        // cusotms
            $sq = "'";
            $count_sel_viola_ids = count($sel_viola_ids);
            if($count_sel_viola_ids > 1){
                $sv_s = 's';
            }else{
                $sv_s = '';
            }
        // try
            // if($count_sel_viola_ids > 0){
            //     echo 'count to be deleted: ' . $count_sel_viola_ids . '<br/>';
            //     foreach($sel_viola_ids as $this_viola_id){
            //         echo 'to be deleted: ' . $this_viola_id . '<br/>';
            //     }
            // }
            // echo 'stud number: ' . $sel_stud_num . '<br/>';
            // echo 'Responsible user: <br/>';
            // echo '' .$sel_respo_user_id.': ' . $sel_respo_user_fname . ' ' . $sel_respo_user_lname . '<br/>';
        if($count_sel_viola_ids > 0){
            // custom values for update
            $zero = 0;
            
            // update del_status from deleted_violations_tbl
            foreach($sel_viola_ids as $this_sel_viola_id){
                $perm_delete_status = DB::table('deleted_violations_tbl')
                            ->where('from_viola_id', $this_sel_viola_id)
                            ->where('del_stud_num', $sel_stud_num)
                            ->update([
                                'del_status'      => $zero,
                                'perm_deleted_at' => $now_timestamp,
                                'perm_deleted_by' => $sel_respo_user_id
                            ]); 
            }
            if($perm_delete_status){
                // get all latest del_id from deleted_violations_tbl as reference for user's activity
                $toArray_permDeleted_violaIds = array();
                $query_allPermDeleted_violaIds = Deletedviolations::select('del_id')
                                                    ->where('del_stud_num', $sel_stud_num)
                                                    ->latest('perm_deleted_at')
                                                    ->offset(0)
                                                    ->limit($count_sel_viola_ids)
                                                    ->get();
                if(count($query_allPermDeleted_violaIds) > 0){
                    foreach($query_allPermDeleted_violaIds as $thisPermDeleted_violaId){
                        array_push($toArray_permDeleted_violaIds, $thisPermDeleted_violaId);
                    }
                }
                $to_Json_PermDeleted_violaIds = json_encode($toArray_permDeleted_violaIds);
                $ext_JsonPermDeleted_violaIds = str_replace(array( '{', '}', '"', ':', 'del_id' ), '', $to_Json_PermDeleted_violaIds);
                
                // sum of all offenses 
                $query_sumPermDeleted_offenses = Deletedviolations::where('del_stud_num', $sel_stud_num)
                                                    ->latest('perm_deleted_at')
                                                    ->offset(0)
                                                    ->limit($count_sel_viola_ids)
                                                    ->sum('del_offense_count');
                if($query_sumPermDeleted_offenses > 1){
                    $sPdo_s = 's';
                }else{
                    $sPdo_s = '';
                }

                // get selected student's info from students_tbl
                $sel_stud_info = Students::select('Student_Number', 'First_Name', 'Middle_Name', 'Last_Name', 'Email', 'School_Name', 'Course', 'YearLevel')
                                    ->where('Student_Number', $sel_stud_num)
                                    ->first();
                $sel_stud_Fname       = $sel_stud_info->First_Name;
                $sel_stud_Mname       = $sel_stud_info->Middle_Name;
                $sel_stud_Lname       = $sel_stud_info->Last_Name;
                $sel_stud_Email       = $sel_stud_info->Email;
                $sel_stud_School_Name = $sel_stud_info->School_Name;
                $sel_stud_Course      = $sel_stud_info->Course;
                $sel_stud_YearLevel   = $sel_stud_info->YearLevel;
                
                // year level
                if($sel_stud_YearLevel === '1'){
                    $yearLevel_txt = '1st Year';
                }else if($sel_stud_YearLevel === '2'){
                    $yearLevel_txt = '2nd Year';
                }else if($sel_stud_YearLevel === '3'){
                    $yearLevel_txt = '3rd Year';
                }else if($sel_stud_YearLevel === '4'){
                    $yearLevel_txt = '4th Year';
                }else if($sel_stud_YearLevel === '5'){
                    $yearLevel_txt = '5th Year';
                }else{
                    $yearLevel_txt = $sel_stud_YearLevel . ' Year';
                }
                
                // record activity
                $record_act = new Useractivites;
                $record_act->created_at                 = $now_timestamp;
                $record_act->act_respo_user_id          = $sel_respo_user_id;
                $record_act->act_respo_users_lname      = $sel_respo_user_lname;
                $record_act->act_respo_users_fname      = $sel_respo_user_fname;
                $record_act->act_type                   = 'violation deletion';
                $record_act->act_details                = 'Permanently Deleted ' . $query_sumPermDeleted_offenses . ' Offense'.$sPdo_s . ' made by ' . $yearLevel_txt . ' ' . $sel_stud_Course . ' student: ' . $sel_stud_Fname . ' ' . $sel_stud_Mname . ' ' . $sel_stud_Lname.'.';
                $record_act->act_perm_deleted_viola_ids = $ext_JsonPermDeleted_violaIds;
                $record_act->save();
                return back()->withSuccessStatus('Violation has been deleted permanently.');
            }else{
                return back()->withFailedStatus('Permanent deletion of Vioaltion has Failed! try again later.');
            }
        }else{
            return back()->withFailedStatus(' There are no selected Violations for deletion! please try again.');
        }
    }

    // permanent delete all violations confirmation modal
    public function permanent_delete_all_violations_form(Request $request){
        // get all request
        $sel_viola_ids = json_decode($request->get('del_viola_ids'), true); 
        $sel_stud_num  = $request->get('sel_stud_num');

        // try
        // echo 'student number: ' . $sel_stud_num . '<br/>';
        // echo 'selected violation ids: <br/>';
        // if(count($sel_viola_ids) > 0){
        //     foreach($sel_viola_ids as $this_viola_id){
        //         echo ': ' . $this_viola_id . '<br/>';
        //     }
        // }

        // get violator's info
        $get_violator_info = Students::select('Last_Name', 'Gender')->where('Student_Number', $sel_stud_num)->first();
        $violator_Lname    = $get_violator_info->Last_Name;
        $violator_Gender   = $get_violator_info->Gender;
        // Mr./Mrs format
        $violator_gender = Str::lower($violator_Gender);
        if($violator_gender == 'male'){
            $vmr_ms = 'Mr.';
        }elseif($violator_gender == 'female'){
            $vmr_ms = 'Ms.';
        }else{
            $vmr_ms = 'Mr./Ms.';
        }

        // output
        $output = '';
        $output .= '
            <div class="modal-body border-0 p-0">
                <form id="form_permDeleteAllViolationRec" action="'.route('violation_records.permanent_delete_violation').'" class="form" enctype="multipart/form-data" method="POST">
                    <div class="cust_modal_body_gray">
                    ';
                    if(count($sel_viola_ids) > 0){
                        $sum_all_del_offenses = 0;
                        foreach($sel_viola_ids as $this_viola_id){
                            // query selected violation's info
                            $query_this_viola_info = Deletedviolations::where('del_id', $this_viola_id)
                                                        ->where('del_stud_num', $sel_stud_num)
                                                        ->first();
                            $get_from_viola_id              = $query_this_viola_info->from_viola_id;
                            $get_viola_del_recorded_at      = $query_this_viola_info->del_recorded_at;
                            $get_viola_del_status           = $query_this_viola_info->del_violation_status;
                            $get_viola_del_offense_count    = $query_this_viola_info->del_offense_count;
                            $get_viola_del_minor_off        = $query_this_viola_info->del_minor_off;
                            $get_viola_del_less_serious_off = $query_this_viola_info->del_less_serious_off;
                            $get_viola_del_other_off        = $query_this_viola_info->del_other_off;
                            $get_viola_del_stud_num         = $query_this_viola_info->del_stud_num;
                            $get_viola_del_has_sanction     = $query_this_viola_info->del_has_sanction;
                            $get_viola_del_has_sanct_count  = $query_this_viola_info->del_has_sanct_count;
                            $get_viola_del_respo_user_id    = $query_this_viola_info->del_respo_user_id;
                            $get_viola_del_cleared_at       = $query_this_viola_info->del_cleared_at;
                            $get_viola_reason_deletion      = $query_this_viola_info->reason_deletion;
                            $get_viola_respo_user_id        = $query_this_viola_info->respo_user_id;
                            $get_viola_deleted_at           = $query_this_viola_info->deleted_at;

                            // sum of all recently deleted offenses
                            $sum_all_del_offenses += $get_viola_del_offense_count;
                            if($sum_all_del_offenses > 1){
                                $sdO_s = 's';
                            }else{
                                $sdO_s = '';
                            }

                            // plural deleted offense count each
                            if($get_viola_del_offense_count > 1){
                                $doC_s = 's';
                            }else{
                                $doC_s = '';
                            }

                            // dates
                            $del_date_recorded = date('F d, Y ~ l - g:i A', strtotime($get_viola_del_recorded_at));
                            $del_date_cleared = date('F d, Y ~ l - g:i A', strtotime($get_viola_del_cleared_at));
                            $date_deleted_at = date('F d, Y ~ l - g:i A', strtotime($get_viola_deleted_at));

                            // responsible user
                            if($get_viola_del_respo_user_id == auth()->user()->id){
                                $recBy = 'Recorded by you.';
                                $recByTooltip = 'This Violation was recorded by you on ' . $del_date_recorded.'.';
                            }else{
                                $get_recBy_info = Users::select('id', 'user_role', 'user_lname', 'user_fname')
                                            ->where('id', $get_viola_del_respo_user_id)
                                            ->first();
                                $recBy = ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_lname;
                                $recByTooltip = 'This Violation was recorded by ' . ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_fname . ' ' . $get_recBy_info->user_lname . ' on ' . $del_date_recorded.'.';
                            }
                            // responsible user (deleted violations)
                            if($get_viola_respo_user_id == auth()->user()->id){
                                $delBy = 'Deleted by you.';
                                $delByTooltip = 'This Violation was deleted by you on ' . $date_deleted_at.'.';
                            }else{
                                $get_delBy_info = App\Models\Users::select('id', 'user_role', 'user_lname', 'user_fname')
                                                        ->where('id', $get_viola_respo_user_id)
                                                        ->first();
                                $delBy = ucwords($get_delBy_info->user_role).': ' . $get_delBy_info->user_lname;
                                $delByTooltip = 'This Violation was deleted by ' . ucwords($get_delBy_info->user_role).': ' . $get_delBy_info->user_fname . ' ' . $get_delBy_info->user_lname . ' on ' . $date_deleted_at.'.';
                            }

                            // cleared/uncleared classes
                            if($get_viola_del_status === 'cleared'){
                                $light_cardBody       = 'lightGreen_cardBody';
                                $light_cardBody_title = 'lightGreen_cardBody_greenTitle';
                                $light_cardBody_list  = 'lightGreen_cardBody_list';
                                $info_textClass       = 'cust_info_txtwicon4';
                                $info_iconClass       = 'fa fa-check-square-o';
                                $class_violationStat1 = 'text-success font-italic';
                                $txt_violationStat1   = '~ Cleared';
                            }else{
                                $light_cardBody       = 'lightRed_cardBody';
                                $light_cardBody_title = 'lightRed_cardBody_redTitle';
                                $light_cardBody_list  = 'lightRed_cardBody_list';
                                $info_textClass       = 'cust_info_txtwicon3';
                                $info_iconClass       = 'fa fa-exclamation-circle';
                                $class_violationStat1 = 'text_svms_red font-italic';
                                $txt_violationStat1   = '~ Not Cleared';    
                            }

                            // output each recently deleted violations
                            $output .= '
                            <div class="accordion shadow-none cust_accordion_div1 mb-2" id="permDelAllViola_SelectOption_Parent'.$this_viola_id.'">
                                <div class="card custom_accordion_card">
                                    <div class="card-header py10l15r10 d-flex justify-content-between align-items-center" id="permDelAllViola_SelectOption_heading'.$this_viola_id.'">
                                        <div class="form-group m-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="'.$this_viola_id.'_markPermDelThisViola_id" value="'.$get_from_viola_id.'" name="perm_delete_viola_ids[]" class="custom-control-input cust_checkbox_label permDelViolMarkSingle" checked>
                                                <label class="custom-control-label cust_checkbox_label" for="'.$this_viola_id.'_markPermDelThisViola_id">
                                                    <span class="li_info_title"> '.date('F d, Y', strtotime($del_date_recorded)).' <span class="'.$class_violationStat1.'"> ' . $txt_violationStat1.'</span></span>
                                                    <span class="li_info_subtitle">'.date('l - g:i A', strtotime($del_date_recorded)).'</span>
                                                </label>
                                            </div>
                                        </div>
                                        <button class="btn cust_btn_smcircle3" type="button" data-toggle="collapse" data-target="#permDelAllViola_SelectOption'.$this_viola_id.'" aria-expanded="true" aria-controls="permDelAllViola_SelectOption'.$this_viola_id.'">
                                            <i class="nc-icon nc-minimal-down"></i>
                                        </button>
                                    </div>
                                    <div id="permDelAllViola_SelectOption'.$this_viola_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="permDelAllViola_SelectOption_heading'.$this_viola_id.'" data-parent="#permDelAllViola_SelectOption_Parent'.$this_viola_id.'">
                                    ';
                                    if(!is_null(json_decode(json_encode($get_viola_del_minor_off), true)) OR !empty(json_decode(json_encode($get_viola_del_minor_off), true))){
                                        $vmo_x = 1;
                                        $output .= '
                                        <div class="card-body '.  $light_cardBody  .' mb-2">
                                            <span class="'. $light_cardBody_title  .' mb-1">Minor Offenses:</span>
                                            ';
                                            foreach(json_decode(json_encode($get_viola_del_minor_off), true) as $viola_minor_offenses){
                                                $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vmo_x++ .'.</span> '. $viola_minor_offenses .'</span>';
                                            }
                                            $output .='
                                        </div>
                                        ';
                                    }
                                    if(!is_null(json_decode(json_encode($get_viola_del_less_serious_off), true)) OR !empty(json_decode(json_encode($get_viola_del_less_serious_off), true))){
                                        $vlso_x = 1;
                                        $output .= '
                                        <div class="card-body '.  $light_cardBody  .' mb-2">
                                            <span class="'. $light_cardBody_title  .' mb-1">Less Serious Offenses:</span>
                                            ';
                                            foreach(json_decode(json_encode($get_viola_del_less_serious_off), true) as $viola_less_serious_offenses){
                                                $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vlso_x++ .'.</span> '. $viola_less_serious_offenses .'</span>';
                                            }
                                            $output .='
                                        </div>
                                        ';
                                    }
                                    if(!is_null(json_decode(json_encode($get_viola_del_other_off), true)) OR !empty(json_decode(json_encode($get_viola_del_other_off), true))){
                                        if(!in_array(null, json_decode(json_encode($get_viola_del_other_off), true))){
                                            $voo_x = 1;
                                            $output .= '
                                            <div class="card-body '.  $light_cardBody  .' mb-2">
                                                <span class="'. $light_cardBody_title  .' mb-1">Other Offenses:</span>
                                                ';
                                                foreach(json_decode(json_encode($get_viola_del_other_off), true) as $viola_other_offenses){
                                                    $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $voo_x++ .'.</span> '. $viola_other_offenses .'</span>';
                                                }
                                                $output .='
                                            </div>
                                            ';
                                        }
                                    }
                                    if($get_viola_del_has_sanction > 0){
                                        // get all sanctions 
                                        $get_all_sanctions = Deletedsanctions::select('del_sanct_status', 'del_sanct_details', 'del_completed_at')
                                                                            ->where('del_stud_num', $sel_stud_num)
                                                                            ->where('del_for_viola_id', $get_from_viola_id)
                                                                            ->orderBy('deleted_at', 'asc')
                                                                            ->offset(0)
                                                                            ->limit($get_viola_del_has_sanct_count)
                                                                            ->get();
                                        $count_completed_sanction = Deletedsanctions::where('del_stud_num', $sel_stud_num)
                                                                            ->where('del_for_viola_id', $get_from_viola_id)
                                                                            ->where('del_sanct_status', '=', 'completed')
                                                                            ->offset(0)
                                                                            ->limit($get_viola_del_has_sanct_count)
                                                                            ->count();
                                        $count_all_sanctions = count($get_all_sanctions);
                                        if($count_all_sanctions > 1){
                                            $sc_s = 's';
                                        }else{
                                            $sc_s = '';
                                        }
                                        $output .= '
                                        <div class="card-body lightGreen_cardBody mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span class="lightGreen_cardBody_greenTitle mb-1">Sanctions:</span>
                                            </div>';

                                            foreach($get_all_sanctions as $this_vrSanction){
                                                if($this_vrSanction->del_sanct_status === 'completed'){
                                                    $sanct_icon = 'fa fa-check-square-o';
                                                }else{
                                                    $sanct_icon = 'fa fa-square-o';
                                                }
                                                $output .= '<span class="lightGreen_cardBody_list"><i class="'.$sanct_icon . ' mr-1 font-weight-bold" aria-hidden="true"></i> ' . $this_vrSanction->del_sanct_details.'</span>';
                                            }
                                            $output .= '
                                        </div>
                                        ';
                                    }
                                    if($get_viola_del_has_sanction > 0){
                                        // date completed
                                        if ($count_completed_sanction == $count_all_sanctions) {
                                            $info_icon1Class = 'fa fa-check-square-o';
                                            $sancStatusTooltip = $count_all_sanctions . ' corresponding Sanction'.$sc_s . ' for this violation has been completed by ' . $vmr_ms . ' ' . $violator_Lname . ' on ' . $del_date_cleared.'.';
                                        }else{
                                            $info_icon1Class = 'fa fa-list-ul';
                                            $sancStatusTooltip = $count_all_sanctions . ' corresponding Sanction'.$sc_s . ' for ' . $get_viola_del_offense_count . ' Offense'.$doC_s.' committed by ' . $vmr_ms . ' ' . $violator_Lname . ' on ' . $del_date_recorded.'.';
                                        }
                                        $output .= '
                                        <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="' . $sancStatusTooltip . '">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                ';
                                                $output .= '
                                                    <span class="cust_info_txtwicon4 font-weight-bold"><i class="'.$info_icon1Class . ' mr-1" aria-hidden="true"></i> ' . $get_viola_del_has_sanct_count . ' Sanction'.$sc_s.'</span>  
                                                ';
                                                if($get_viola_del_status === 'cleared'){
                                                    $output .= '<span class="cust_info_txtwicon"><i class="fa fa-calendar-check-o mr-1" aria-hidden="true"></i> ' . $del_date_cleared . '</span> ';
                                                }
                                                $output .= '
                                            </div>
                                        </div>
                                        <hr class="hr_gry">
                                        ';
                                    }
                                    $output .='
                                        <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="' . $recByTooltip . '">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <span class="' .$info_textClass . ' font-weight-bold"><i class="' .$info_iconClass . ' mr-1" aria-hidden="true"></i> ' .$get_viola_del_offense_count. ' Offense' .$doC_s. '</span>
                                                <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> ' . $recBy . '</span>  
                                            </div>
                                        </div>
                                        ';
                                        if(!is_null($get_viola_reason_deletion) OR !empty($get_viola_reason_deletion)){
                                            $output .= '
                                            <div class="row mt-3">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="card-body lightBlue_cardBody shadow-none">
                                                        <span class="lightBlue_cardBody_blueTitle">Reason for Deleting Violation:</span>
                                                        <span class="lightBlue_cardBody_list">'.$get_viola_reason_deletion.'</span>
                                                    </div>
                                                </div>
                                            </div>
                                            ';
                                        }
                                        $output .= '
                                        <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="' . $delByTooltip . '">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <span class="cust_info_txtwicon"><i class="fa fa-calendar-minus-o mr-1" aria-hidden="true"></i> ' . $date_deleted_at . '</span> 
                                                <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> ' . $delBy . '</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ';
                        }
                    }
                    $output .= '
                    </div>
                    <div class="modal-body pb-0">
                        <div class="card-body lightBlue_cardBody shadow-none">
                            <div class="row mb-2">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group mx-0 mt-0 mb-1">
                                        <div class="custom-control custom-checkbox align-items-center">
                                            <input type="checkbox" name="perm_delete_all_violations" value="perm_delete_all_violations" class="custom-control-input cursor_pointer" id="permDelViolMarkAll" checked>
                                            <label class="custom-control-label lightBlue_cardBody_chckboxLabel" for="permDelViolMarkAll">Permanent Delete All (3) Offenses.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="lightBlue_cardBody_notice"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> This action will permanently Delete all recently deleted violations and its corresponding sanctions.</span>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="sel_stud_num" value="'.$sel_stud_num.'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <div class="btn-group" role="group" aria-label="delete sanctions actions">
                            <button id="cancel_permDeleteAllViolationRecBtn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_permDeleteAllViolationRecBtn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0"> Delete Violations Forever <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        ';
        echo $output;
    }

    // VIOLATION RECOVERY
    // recover deleted violation confirmation modal
    public function recover_deleted_violation_form(Request $request){
        // get all request
            $sel_viola_id = $request->get('sel_viola_id');
            $sel_stud_num = $request->get('sel_stud_num');
        // get violation's info
            $get_viola_info = Deletedviolations::where('from_viola_id', $sel_viola_id)->first();
            $get_viola_recorded_at      = $get_viola_info->del_recorded_at;
            $get_viola_status           = $get_viola_info->del_violation_status;
            $get_viola_offense_count    = $get_viola_info->del_offense_count;
            $get_viola_minor_off        = $get_viola_info->del_minor_off;
            $get_viola_less_serious_off = $get_viola_info->del_less_serious_off;
            $get_viola_other_off        = $get_viola_info->del_other_off;
            $get_viola_stud_num         = $get_viola_info->del_stud_num;
            $get_viola_has_sanction     = $get_viola_info->del_has_sanction;
            $get_viola_has_sanct_count  = $get_viola_info->del_has_sanct_count;
            $get_viola_respo_user_id    = $get_viola_info->del_respo_user_id;
            $get_viola_cleared_at       = $get_viola_info->del_cleared_at;
            $get_viola_reason_deletion  = $get_viola_info->reason_deletion;
            $get_viola_deleted_by       = $get_viola_info->respo_user_id;
            $get_viola_deleted_at       = $get_viola_info->deleted_at;
        // get violator's info
            $get_violator_info = Students::select('Last_Name', 'Gender')->where('Student_Number', $sel_stud_num)->first();
            $violator_Lname    = $get_violator_info->Last_Name;
            $violator_Gender   = $get_violator_info->Gender;
            // Mr./Mrs format
            $violator_gender = Str::lower($violator_Gender);
            if($violator_gender == 'male'){
                $violator_mr_ms   = 'Mr.';
            }elseif($violator_gender == 'female'){
                $violator_mr_ms   = 'Ms.';
            }else{
                $violator_mr_ms   = 'Mr./Ms.';
            }
        // custom values
        // plural offense count
            if($get_viola_offense_count > 1){
                $oC_s = 's';
            }else{
                $oC_s = '';
            }
            // dates
            $date_recorded = date('F d, Y ~ l - g:i A', strtotime($get_viola_recorded_at));
            $date_deleted = date('F d, Y ~ l - g:i A', strtotime($get_viola_deleted_at));
            // violator's last name and Mr./Mrs
            $query_violator_info = Students::select('Last_Name', 'Gender')
                                            ->where('Student_Number', $sel_stud_num)
                                            ->first();
            $get_violator_lname = $query_violator_info->Last_Name;
            $get_violator_gender = strtolower($query_violator_info->Gender);
            if($get_violator_gender === 'male'){
                $vmr_ms = 'Mr.';
            }elseif($get_violator_gender === 'female'){
                $vmr_ms = 'Ms.';
            }else{
                $vmr_ms = 'Mr./Ms.';
            }
            // responsible user
            if($get_viola_respo_user_id == auth()->user()->id){
                $recBy = 'Recorded by you.';
                $recByTooltip = 'This Violation was recorded by you on ' . $date_recorded.'.';
            }else{
                $get_recBy_info = Users::select('id', 'user_role', 'user_lname', 'user_fname')
                                        ->where('id', $get_viola_respo_user_id)
                                        ->first();
                $recBy = ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_lname;
                $recByTooltip = 'This Violation was recorded by ' . ucwords($get_recBy_info->user_role).': ' . $get_recBy_info->user_fname . ' ' . $get_recBy_info->user_lname . ' on ' . $date_recorded.'.';
            }
            // responsible user (deleted violations)
            if($get_viola_deleted_by == auth()->user()->id){
                $delBy = 'Deleted by you.';
                $delByTooltip = 'This Violation was deleted by you on ' . $date_deleted.'.';
            }else{
                $get_delBy_info = App\Models\Users::select('id', 'user_role', 'user_lname', 'user_fname')
                                        ->where('id', $get_viola_deleted_by)
                                        ->first();
                $delBy = ucwords($get_delBy_info->user_role).': ' . $get_delBy_info->user_lname;
                $delByTooltip = 'This Violation was deleted by ' . ucwords($get_delBy_info->user_role).': ' . $get_delBy_info->user_fname . ' ' . $get_delBy_info->user_lname . ' on ' . $date_deleted.'.';
            }
            // cleared/uncleared classes
            if($get_viola_status === 'cleared'){
                $light_cardBody       = 'lightGreen_cardBody';
                $light_cardBody_title = 'lightGreen_cardBody_greenTitle';
                $light_cardBody_list  = 'lightGreen_cardBody_list';
                $info_textClass       = 'cust_info_txtwicon4';
                $info_iconClass       = 'fa fa-check-square-o';
                $class_violationStat1 = 'text-success font-italic';
                $txt_violationStat1   = '~ Cleared';
            }else{
                $light_cardBody       = 'lightRed_cardBody';
                $light_cardBody_title = 'lightRed_cardBody_redTitle';
                $light_cardBody_list  = 'lightRed_cardBody_list';
                $info_textClass       = 'cust_info_txtwicon3';
                $info_iconClass       = 'fa fa-exclamation-circle';
                $class_violationStat1 = 'text_svms_red font-italic';
                $txt_violationStat1   = '~ Not Cleared';    
            }

        $output = '';
        $output .= '
            <div class="modal-body border-0 p-0">
                <div class="cust_modal_body_gray">
                    <div class="accordion shadow cust_accordion_div" id="sv'.$sel_viola_id.'Accordion_Parent">
                        <div class="card custom_accordion_card">
                            <div class="card-header p-0" id="changeUserRoleCollapse_heading">
                                <h2 class="mb-0">
                                    <button class="btn btn-block custom2_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#sv'.$sel_viola_id.'Collapse_Div" aria-expanded="true" aria-controls="sv'.$sel_viola_id.'Collapse_Div">
                                        <div class="d-flex justify-content-start align-items-center">
                                            <div class="information_div2">
                                                <span class="li_info_title">'.date('F d, Y', strtotime($get_viola_recorded_at)).' <span class="'.$class_violationStat1.'"> ' . $txt_violationStat1.'</span></span>
                                                <span class="li_info_subtitle">'.date('l - g:i A', strtotime($get_viola_recorded_at)).'</span>
                                            </div>
                                        </div>
                                        <i class="nc-icon nc-minimal-up"></i>
                                    </button>
                                </h2>
                            </div>
                            <div id="sv'.$sel_viola_id.'Collapse_Div" class="collapse show cust_collapse_active cb_t0b12y15" aria-labelledby="sv'.$sel_viola_id.'Collapse_heading" data-parent="#sv'.$sel_viola_id.'Accordion_Parent">
                                ';
                                if(!is_null(json_decode(json_encode($get_viola_minor_off), true)) OR !empty(json_decode(json_encode($get_viola_minor_off), true))){
                                    $vmo_x = 1;
                                    $output .= '
                                    <div class="card-body '.  $light_cardBody  .' mb-2">
                                        <span class="'. $light_cardBody_title  .' mb-1">Minor Offenses:</span>
                                        ';
                                        foreach(json_decode(json_encode($get_viola_minor_off), true) as $viola_minor_offenses){
                                            $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vmo_x++ .'.</span> '. $viola_minor_offenses .'</span>';
                                        }
                                        $output .='
                                    </div>
                                    ';
                                }
                                if(!is_null(json_decode(json_encode($get_viola_less_serious_off), true)) OR !empty(json_decode(json_encode($get_viola_less_serious_off), true))){
                                    $vlso_x = 1;
                                    $output .= '
                                    <div class="card-body '.  $light_cardBody  .' mb-2">
                                        <span class="'. $light_cardBody_title  .' mb-1">Less Serious Offenses:</span>
                                        ';
                                        foreach(json_decode(json_encode($get_viola_less_serious_off), true) as $viola_less_serious_offenses){
                                            $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $vlso_x++ .'.</span> '. $viola_less_serious_offenses .'</span>';
                                        }
                                        $output .='
                                    </div>
                                    ';
                                }
                                if(!is_null(json_decode(json_encode($get_viola_other_off), true)) OR !empty(json_decode(json_encode($get_viola_other_off), true))){
                                    if(!in_array(null, json_decode(json_encode($get_viola_other_off), true))){
                                        $voo_x = 1;
                                        $output .= '
                                        <div class="card-body '.  $light_cardBody  .' mb-2">
                                            <span class="'. $light_cardBody_title  .' mb-1">Other Offenses:</span>
                                            ';
                                            foreach(json_decode(json_encode($get_viola_other_off), true) as $viola_other_offenses){
                                                $output .= '<span class="'. $light_cardBody_list  .'"><span class="font-weight-bold mr-1">'. $voo_x++ .'.</span> '. $viola_other_offenses .'</span>';
                                            }
                                            $output .='
                                        </div>
                                        ';
                                    }
                                }
                                if($get_viola_has_sanction > 0){
                                    // get all sanctions 
                                    $get_all_sanctions = Deletedsanctions::select('del_sanct_status', 'del_sanct_details', 'del_completed_at')
                                                                        ->where('del_stud_num', $sel_stud_num)
                                                                        ->where('del_for_viola_id', $sel_viola_id)
                                                                        ->orderBy('del_created_at', 'asc')
                                                                        ->offset(0)
                                                                        ->limit($get_viola_has_sanct_count)
                                                                        ->get();
                                    $count_completed_sanction = Deletedsanctions::where('del_stud_num', $sel_stud_num)
                                                                        ->where('del_for_viola_id', $sel_viola_id)
                                                                        ->where('del_sanct_status', '=', 'completed')
                                                                        ->offset(0)
                                                                        ->limit($get_viola_has_sanct_count)
                                                                        ->count();
                                    $count_all_sanctions = count($get_all_sanctions);
                                    if($count_all_sanctions > 1){
                                        $sc_s = 's';
                                    }else{
                                        $sc_s = '';
                                    }
                                    $output .= '
                                    <div class="card-body lightGreen_cardBody mb-2">
                                        <div class="d-flex justify-content-between">
                                            <span class="lightGreen_cardBody_greenTitle mb-1">Sanctions:</span>
                                        </div>';

                                        foreach($get_all_sanctions as $this_vrSanction){
                                            if($this_vrSanction->del_sanct_status === 'completed'){
                                                $sanct_icon = 'fa fa-check-square-o';
                                            }else{
                                                $sanct_icon = 'fa fa-square-o';
                                            }
                                            $output .= '<span class="lightGreen_cardBody_list"><i class="'.$sanct_icon . ' mr-1 font-weight-bold" aria-hidden="true"></i> ' . $this_vrSanction->del_sanct_details.'</span>';
                                        }
                                        $output .= '
                                    </div>
                                    ';
                                }
                                if($get_viola_has_sanction > 0){
                                    // date completed
                                    $date_completed = date('F d, Y ~ l - g:i A', strtotime($get_viola_cleared_at));
                                    if ($count_completed_sanction == $count_all_sanctions) {
                                        $info_icon1Class = 'fa fa-check-square-o';
                                        $sancStatusTooltip = $count_all_sanctions . ' corresponding Sanction'.$sc_s . ' for this violation has been completed by ' . $vmr_ms . ' ' . $get_violator_lname . ' on ' . $date_completed.'.';
                                    }else{
                                        $info_icon1Class = 'fa fa-list-ul';
                                        $sancStatusTooltip = $count_all_sanctions . ' corresponding Sanction'.$sc_s . ' for ' . $get_viola_offense_count . ' Offense'.$oC_s.' committed by ' . $vmr_ms . ' ' . $get_violator_lname . ' on ' . $date_recorded.'.';
                                    }
                                    $output .= '
                                    <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="' . $sancStatusTooltip . '">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            ';
                                            $output .= '
                                                <span class="cust_info_txtwicon4 font-weight-bold"><i class="'.$info_icon1Class . ' mr-1" aria-hidden="true"></i> ' . $get_viola_has_sanct_count . ' Sanction'.$sc_s.'</span>  
                                            ';
                                            if($get_viola_status === 'cleared'){
                                                $output .= '<span class="cust_info_txtwicon"><i class="fa fa-calendar-check-o mr-1" aria-hidden="true"></i> ' . date('F d, Y ~ l - g:i A', strtotime($get_viola_cleared_at)) . '</span> ';
                                            }
                                            $output .= '
                                        </div>
                                    </div>
                                    <hr class="hr_gry">
                                    ';
                                }
                                $output .= '
                                <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="' . $recByTooltip . '">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="' .$info_textClass . ' font-weight-bold"><i class="' .$info_iconClass . ' mr-1" aria-hidden="true"></i> ' .$get_viola_offense_count. ' Offense' .$oC_s. '</span>
                                        <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> ' . $recBy . '</span>  
                                    </div>
                                </div>
                                ';
                                if(!is_null($get_viola_reason_deletion) OR !empty($get_viola_reason_deletion)){
                                    $output .= '
                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="card-body lightBlue_cardBody shadow-none">
                                                <span class="lightBlue_cardBody_blueTitle">Reason for Deleting Violation:</span>
                                                <span class="lightBlue_cardBody_list">'.$get_viola_reason_deletion.'</span>
                                            </div>
                                        </div>
                                    </div>
                                    ';
                                }
                                $output .= '
                                <div class="row mt-3 cursor_pointer" data-toggle="tooltip" data-placement="top" title="' . $delByTooltip . '">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="cust_info_txtwicon"><i class="fa fa-calendar-minus-o mr-1" aria-hidden="true"></i> ' . date('F d, Y ~ l - g:i A', strtotime($get_viola_deleted_at)) . '</span> 
                                        <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> ' . $delBy . '</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="form_recoverDeletedViolationRec" action="'.route('violation_records.recover_deleted_violation').'" class="form" enctype="multipart/form-data" method="POST">
                    <div class="modal-body pb-0">
                        <div class="card-body lightBlue_cardBody shadow-none">
                            <span class="lightBlue_cardBody_notice"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> This action will recover the Deleted recorded violation and its corresponding sanctions.</span>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="for_viola_id[]" value="'.$sel_viola_id.'">
                        <input type="hidden" name="sel_stud_num" value="'.$sel_stud_num.'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <div class="btn-group" role="group" aria-label="delete sanctions actions">
                            <button id="cancel_recoverDeletedViolationRecBtn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_recoverDeletedViolationRecBtn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0"> Recover Violation <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        ';

        echo $output;
    }
    // process recover deleted violation
    public function recover_deleted_violation(Request $request){
        // custom values
            $now_timestamp     = now();
        // get all request
            $sel_viola_ids         = json_decode(json_encode($request->get('for_viola_id'))); 
            $sel_stud_num          = $request->get('sel_stud_num');
            $sel_respo_user_id     = $request->get('respo_user_id');
            $sel_respo_user_lname  = $request->get('respo_user_lname');
            $sel_respo_user_fname  = $request->get('respo_user_fname');  
        // cusotms
            $sq = "'";
            $count_sel_viola_ids = count($sel_viola_ids);
            if($count_sel_viola_ids > 1){
                $sv_s = 's';
            }else{
                $sv_s = '';
            }
        // try
            // if($count_sel_viola_ids > 0){
            //     echo 'count to be recover: ' . $count_sel_viola_ids . '<br/>';
            //     foreach($sel_viola_ids as $recoverThis_viola_id){
            //         echo 'to be recover: ' . $recoverThis_viola_id . '<br/>';
            //     }
            // }
            // echo 'stud number: ' . $sel_stud_num . '<br/>';
            // echo 'Responsible user: <br/>';
            // echo '' .$sel_respo_user_id.': ' . $sel_respo_user_fname . ' ' . $sel_respo_user_lname . '<br/>';
        if($count_sel_viola_ids > 0){
            // get deleted record from deleted_violations_tbl then save it back to violations_tbl
            foreach($sel_viola_ids as $recoverThis_violaId){
                $query_deletedRecord = Deletedviolations::where('from_viola_id', $recoverThis_violaId)
                                            ->where('del_stud_num', $sel_stud_num)
                                            ->where('del_status', 1)
                                            ->first();
                $get_del_recorded_at        = $query_deletedRecord->del_recorded_at;
                $get_del_violation_status   = $query_deletedRecord->del_violation_status;
                $get_del_offense_count      = $query_deletedRecord->del_offense_count;
                $get_del_minor_off          = $query_deletedRecord->del_minor_off;
                $get_del_serious_off        = $query_deletedRecord->del_less_serious_off;
                $get_del_other_off          = $query_deletedRecord->del_other_off;
                $get_del_stud_num           = $query_deletedRecord->del_stud_num;
                $get_del_has_sanction       = $query_deletedRecord->del_has_sanction;
                $get_del_has_sanction_count = $query_deletedRecord->del_has_sanct_count;
                $get_del_respo_user_id      = $query_deletedRecord->del_respo_user_id;
                $get_del_cleared_at         = $query_deletedRecord->del_cleared_at;
                $get_respo_user_id          = $query_deletedRecord->respo_user_id;
                $get_deleted_at             = $query_deletedRecord->deleted_at;

                $recover_deletedRecord = new Violations;
                $recover_deletedRecord->recorded_at      = $get_del_recorded_at;
                $recover_deletedRecord->violation_status = $get_del_violation_status;
                $recover_deletedRecord->offense_count    = $get_del_offense_count;
                $recover_deletedRecord->minor_off        = $get_del_minor_off;
                $recover_deletedRecord->less_serious_off = $get_del_serious_off;
                $recover_deletedRecord->other_off        = $get_del_other_off;
                $recover_deletedRecord->stud_num         = $get_del_stud_num;
                $recover_deletedRecord->has_sanction     = $get_del_has_sanction;
                $recover_deletedRecord->has_sanct_count  = $get_del_has_sanction_count;
                $recover_deletedRecord->respo_user_id    = $get_del_respo_user_id;
                $recover_deletedRecord->updated_at       = $now_timestamp;
                $recover_deletedRecord->deleted_at       = $get_deleted_at;
                $recover_deletedRecord->recovered_at     = $now_timestamp;
                $recover_deletedRecord->cleared_at       = $get_del_cleared_at;
                $recover_deletedRecord->save();

                $query_new_viola_id = Violations::select('viola_id')
                                        ->where('recorded_at', $get_del_recorded_at)
                                        ->where('stud_num', $get_del_stud_num)
                                        ->latest('recovered_at')
                                        ->first();
                $ext_query_new_viola_id = str_replace(array( '{', '}', '"', ':', 'viola_id' ), '', $query_new_viola_id);

                $remove_deletedViolation = Deletedviolations::where('from_viola_id', $recoverThis_violaId)
                                        ->where('del_stud_num', $sel_stud_num)
                                        ->delete();

                // get deleted sanctions form deleted_sanctions_tbl then recover it back to sanctions_tbl
                if($get_del_has_sanction > 0){
                    $query_deletedSanctions = Deletedsanctions::where('del_stud_num', $sel_stud_num)
                                            ->where('del_for_viola_id', $recoverThis_violaId)
                                            ->offset(0)
                                            ->limit($get_del_has_sanction_count)
                                            ->get();

                    if(count($query_deletedSanctions) > 0){
                        foreach($query_deletedSanctions as $this_deletedSanction){
                            $get_delSanct_deleted_at        = $this_deletedSanction->deleted_at;
                            $get_delSanct_del_stud_num      = $this_deletedSanction->del_stud_num;
                            $get_delSanct_del_sanct_status  = $this_deletedSanction->del_sanct_status;
                            $get_delSanct_del_sanct_details = $this_deletedSanction->del_sanct_details;
                            $get_delSanct_del_for_viola_id  = $this_deletedSanction->del_for_viola_id;
                            $get_delSanct_del_respo_user_id = $this_deletedSanction->del_respo_user_id;
                            $get_delSanct_del_created_at    = $this_deletedSanction->del_created_at;
                            $get_delSanct_del_completed_at  = $this_deletedSanction->del_completed_at;

                            $recover_deletedSanctions = new Sanctions;
                            $recover_deletedSanctions->stud_num      = $get_delSanct_del_stud_num;
                            $recover_deletedSanctions->for_viola_id  = $ext_query_new_viola_id;
                            $recover_deletedSanctions->sanct_status  = $get_delSanct_del_sanct_status;
                            $recover_deletedSanctions->sanct_details = $get_delSanct_del_sanct_details;
                            $recover_deletedSanctions->respo_user_id = $get_delSanct_del_respo_user_id;
                            $recover_deletedSanctions->created_at    = $get_delSanct_del_created_at;
                            $recover_deletedSanctions->completed_at  = $get_delSanct_del_completed_at;
                            $recover_deletedSanctions->updated_at    = $now_timestamp;
                            $recover_deletedSanctions->deleted_at    = $get_delSanct_deleted_at;
                            $recover_deletedSanctions->recovered_at  = $now_timestamp;
                            $recover_deletedSanctions->save();

                            $remove_deletedSanction = Deletedsanctions::where('del_for_viola_id', $recoverThis_violaId)
                                                ->where('del_stud_num', $sel_stud_num)
                                                ->delete();
                        }
                    }
                }
            }
            if($recover_deletedRecord){
                // get this violation id from violations_tbl
                $to_array_recoveredViola_ids = array();
                $get_recovered_viola_ids = Violations::select('viola_id')
                                            ->where('stud_num', $get_del_stud_num)
                                            ->latest('recovered_at')
                                            ->offset(0)
                                            ->limit($count_sel_viola_ids)
                                            ->get();
                foreach($get_recovered_viola_ids as $thisRecovered_violationId){
                    array_push($to_array_recoveredViola_ids, $thisRecovered_violationId);
                }
                $add_Bracket = array_values($to_array_recoveredViola_ids);
                $to_Json_recovered_viola_id_id = json_encode($add_Bracket);
                $ext_jsonRecoveredViola_ids = str_replace(array( '{', '}', '"', ':', 'viola_id' ), '', $to_Json_recovered_viola_id_id);
                
                // sum of all offenses 
                $query_sumReocovered_offenses = Violations::where('stud_num', $sel_stud_num)
                                                    ->whereNotNull('recovered_at')
                                                    ->where('recovered_at', $now_timestamp)
                                                    ->latest('recovered_at')
                                                    ->offset(0)
                                                    ->limit($count_sel_viola_ids)
                                                    ->sum('offense_count');
                if($query_sumReocovered_offenses > 1){
                    $sRo_s = 's';
                }else{
                    $sRo_s = '';
                }

                // get selected student's info from students_tbl
                $sel_stud_info = Students::select('Student_Number', 'First_Name', 'Middle_Name', 'Last_Name', 'Email', 'School_Name', 'Course', 'YearLevel')
                                    ->where('Student_Number', $sel_stud_num)
                                    ->first();
                $sel_stud_Fname       = $sel_stud_info->First_Name;
                $sel_stud_Mname       = $sel_stud_info->Middle_Name;
                $sel_stud_Lname       = $sel_stud_info->Last_Name;
                $sel_stud_Email       = $sel_stud_info->Email;
                $sel_stud_School_Name = $sel_stud_info->School_Name;
                $sel_stud_Course      = $sel_stud_info->Course;
                $sel_stud_YearLevel   = $sel_stud_info->YearLevel;
                
                // year level
                if($sel_stud_YearLevel === '1'){
                    $yearLevel_txt = '1st Year';
                }else if($sel_stud_YearLevel === '2'){
                    $yearLevel_txt = '2nd Year';
                }else if($sel_stud_YearLevel === '3'){
                    $yearLevel_txt = '3rd Year';
                }else if($sel_stud_YearLevel === '4'){
                    $yearLevel_txt = '4th Year';
                }else if($sel_stud_YearLevel === '5'){
                    $yearLevel_txt = '5th Year';
                }else{
                    $yearLevel_txt = $sel_stud_YearLevel . ' Year';
                }
                
                // record activity
                $record_act = new Useractivites;
                $record_act->created_at              = $now_timestamp;
                $record_act->act_respo_user_id       = $sel_respo_user_id;
                $record_act->act_respo_users_lname   = $sel_respo_user_lname;
                $record_act->act_respo_users_fname   = $sel_respo_user_fname;
                $record_act->act_type                = 'violation recovery';
                $record_act->act_details             = 'Recovered ' . $query_sumReocovered_offenses . ' Deleted Offense'.$sRo_s . ' made by ' . $yearLevel_txt . ' ' . $sel_stud_Course . ' student: ' . $sel_stud_Fname . ' ' . $sel_stud_Mname . ' ' . $sel_stud_Lname.'.';
                $record_act->act_recovered_viola_ids = $ext_jsonRecoveredViola_ids;
                $record_act->save();

                return back()->withSuccessStatus(''. $query_sumReocovered_offenses . ' Deleted Offense'.$sRo_s . '  has been recovered successfully.');
            }else{
                return back()->withFailedStatus('Violation Recovey has failed! please try again later.');
            }
        }else{
            return back()->withFailedStatus(' There are no selected Violations for recovery! please try again.');
        }
    }

}