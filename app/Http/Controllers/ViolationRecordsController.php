<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Students;
use App\Models\Violations;
use App\Models\Deletedviolations;
use App\Models\CreatedSanctions;
use App\Models\Sanctions;
use App\Models\Deletedsanctions;
use App\Models\Users;
use App\Models\Userroles;
use App\Models\Useractivites;
use Illuminate\Mail\Mailable;
use Illuminate\Pagination\Paginator;
use PDF;
use Dompdf;

class ViolationRecordsController extends Controller
{
    // main page
    public function index(Request $request){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('violation records', $get_uRole_access)){
            if($request->ajax()){
                // custom var
                $vr_output = '';
                $vr_paginate = '';
                $vr_total_matched_results = '';
                $vr_total_filtered_result = '';
                // get all request
                $vr_search            = $request->get('vr_search');
                $vr_schools           = $request->get('vr_schools');
                $vr_programs          = $request->get('vr_programs');
                $vr_yearlvls          = $request->get('vr_yearlvls');
                $vr_genders           = $request->get('vr_genders');
                $vr_minAgeRange       = $request->get('vr_minAgeRange');
                $vr_maxAgeRange       = $request->get('vr_maxAgeRange');
                $vr_status            = $request->get('vr_status');
                $vr_rangefrom         = $request->get('vr_rangefrom');
                $vr_rangeTo           = $request->get('vr_rangeTo');
                $df_minAgeRange       = $request->get('df_minAgeRange');
                $df_maxAgeRange       = $request->get('df_maxAgeRange');
                $vr_orderBy           = $request->get('vr_orderBy');
                $selectedOrderByRange = $request->get('selectedOrderByRange');
                $vr_numRows           = $request->get('vr_numRows');
                $page                 = $request->get('page');

                // order by 
                if($vr_orderBy != 0 OR !empty($vr_orderBy)){
                    if($vr_orderBy == 1){
                        $orderBy_filterVal = 'stud_num';
                    }elseif($vr_orderBy == 2){
                        $orderBy_filterVal = 'offense_count';
                    }else{
                        $orderBy_filterVal = 'recorded_at';
                    }
                }else{
                    $orderBy_filterVal = 'recorded_at';
                }
                // order by range
                if(!empty($selectedOrderByRange) OR $selectedOrderByRange != 0){
                    if($selectedOrderByRange === 'asc'){
                        $orderByRange_filterVal = 'ASC';
                    }else{
                        $orderByRange_filterVal = 'DESC';
                    }
                }else{
                    $orderByRange_filterVal = 'DESC';
                }
    
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
                                    ->orderBy('violations_tbl.'.$orderBy_filterVal, $orderByRange_filterVal)
                                    ->paginate(intval($vr_numRows));
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
                                    ->orderBy('violations_tbl.'.$orderBy_filterVal, $orderByRange_filterVal)
                                    ->paginate(intval($vr_numRows));
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
                $vr_paginate .= $fltr_VR_tbl->render('pagination::bootstrap-4');
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
        }else{
            return view('profile.access_denied');
        }
    }

    // violator's profile module
    public function violator($violator_id){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('violation records', $get_uRole_access)){
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
        }else{
            return view('profile.access_denied');
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
    public function deleted_violation_records(Request $request){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('violation records', $get_uRole_access)){
            if($request->ajax()){
                // custom var
                $vr_output = '';
                $vr_paginate = '';
                $vr_total_matched_results = '';
                $vr_total_filtered_result = '';
                // get all request
                $vr_search            = $request->get('dvr_search');
                $vr_schools           = $request->get('dvr_schools');
                $vr_programs          = $request->get('dvr_programs');
                $vr_yearlvls          = $request->get('dvr_yearlvls');
                $vr_genders           = $request->get('dvr_genders');
                $vr_minAgeRange       = $request->get('dvr_minAgeRange');
                $vr_maxAgeRange       = $request->get('dvr_maxAgeRange');
                $vr_status            = $request->get('dvr_status');
                $vr_rangefrom         = $request->get('dvr_rangefrom');
                $vr_rangeTo           = $request->get('dvr_rangeTo');
                $df_minAgeRange       = $request->get('ddf_minAgeRange');
                $df_maxAgeRange       = $request->get('ddf_maxAgeRange');
                $vr_orderBy           = $request->get('dvr_orderBy');
                $selectedOrderByRange = $request->get('dselectedOrderByRange');
                $vr_numRows           = $request->get('dvr_numRows');
                $page                 = $request->get('page');

                // order by 
                if($vr_orderBy != 0 OR !empty($vr_orderBy)){
                    if($vr_orderBy == 1){
                        $orderBy_filterVal = 'del_stud_num';
                    }elseif($vr_orderBy == 2){
                        $orderBy_filterVal = 'offense_count';
                    }else{
                        $orderBy_filterVal = 'deleted_at';
                    }
                }else{
                    $orderBy_filterVal = 'deleted_at';
                }
                // order by range
                if(!empty($selectedOrderByRange) OR $selectedOrderByRange != 0){
                    if($selectedOrderByRange === 'asc'){
                        $orderByRange_filterVal = 'ASC';
                    }else{
                        $orderByRange_filterVal = 'DESC';
                    }
                }else{
                    $orderByRange_filterVal = 'DESC';
                }
    
                if($vr_search != ''){
                    $fltr_VR_tbl = DB::table('deleted_violations_tbl')
                                    ->join('students_tbl', 'deleted_violations_tbl.del_stud_num', '=', 'students_tbl.Student_Number')
                                    ->select('deleted_violations_tbl.*', 'students_tbl.*')
                                    ->where(function($vrQuery) use ($vr_search) {
                                        $vrQuery->orWhere('students_tbl.Student_Number', 'like', '%'.$vr_search.'%')
                                                    ->orWhere('students_tbl.First_Name', 'like', '%'.$vr_search.'%')
                                                    ->orWhere('students_tbl.Middle_Name', 'like', '%'.$vr_search.'%')
                                                    ->orWhere('students_tbl.Last_Name', 'like', '%'.$vr_search.'%')
                                                    ->orWhere('students_tbl.Gender', 'like', '%'.$vr_search.'%')
                                                    ->orWhere('students_tbl.School_Name', 'like', '%'.$vr_search.'%')
                                                    ->orWhere('students_tbl.YearLevel', 'like', '%'.$vr_search.'%')
                                                    ->orWhere('students_tbl.Course', 'like', '%'.$vr_search.'%')
                                                    ->orWhere('deleted_violations_tbl.del_stud_num', 'like', '%'.$vr_search.'%');
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
                                            $vrQuery->where('deleted_violations_tbl.del_violation_status', '=', $lower_vr_status);
                                        }
                                        if($vr_rangefrom != 0 OR !empty($vr_rangefrom) AND $vr_rangeTo != 0 OR !empty($vr_rangeTo)){
                                            $vrQuery->whereBetween('deleted_violations_tbl.deleted_at', [$vr_rangefrom, $vr_rangeTo]);
                                        }
                                    })
                                    ->where('del_status', 1)
                                    ->orderBy('deleted_violations_tbl.'.$orderBy_filterVal, $orderByRange_filterVal)
                                    ->paginate(intval($vr_numRows));
                    $matched_result_txt = ' Matched Records';
                }else{
                    $fltr_VR_tbl = DB::table('deleted_violations_tbl')
                                    ->join('students_tbl', 'deleted_violations_tbl.del_stud_num', '=', 'students_tbl.Student_Number')
                                    ->select('deleted_violations_tbl.*', 'students_tbl.*')
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
                                            $vrQuery->where('deleted_violations_tbl.del_violation_status', '=', $lower_vr_status);
                                        }
                                        if($vr_rangefrom != 0 OR !empty($vr_rangefrom) AND $vr_rangeTo != 0 OR !empty($vr_rangeTo)){
                                            $vrQuery->whereBetween('deleted_violations_tbl.deleted_at', [$vr_rangefrom, $vr_rangeTo]);
                                        }
                                    })
                                    ->where('del_status', 1)
                                    ->orderBy('deleted_violations_tbl.'.$orderBy_filterVal, $orderByRange_filterVal)
                                    ->paginate(intval($vr_numRows));
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
                        if($this_violator->del_offense_count > 1){
                            $oc_s = 's';
                        }else{
                            $oc_s = '';
                        }
                        // violation status classes
                        if($this_violator->del_violation_status === 'cleared'){
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
                                    <span class="actLogs_content">'.date('F d, Y', strtotime($this_violator->deleted_at)) . '</span>
                                    <span class="actLogs_tdSubTitle sub2">'.date('D - g:i A', strtotime($this_violator->deleted_at)) . '</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-inline">
                                    <span class="actLogs_content">'.date('F d, Y (D - g:i A) ', strtotime($this_violator->deleted_at)) . ' '.$this_violator->del_offense_count.' Offense'.$oc_s . ' ' . $violation_statTxt.'</span>
                                    <span class="actLogs_tdSubTitle sub2">
                                    ';
                                    // set new array value
                                    $to_array_allOffenses = array();
                                    // merge all offenses to $to_array_allOffenses
                                    if(!is_null($this_violator->del_minor_off) OR !empty($this_violator->del_minor_off)){
                                        foreach(json_decode($this_violator->del_minor_off, true) as $this_mo){
                                            array_push($to_array_allOffenses, $this_mo);
                                        }
                                    }
                                    if(!is_null($this_violator->del_less_serious_off) OR !empty($this_violator->del_less_serious_off)){
                                        foreach(json_decode($this_violator->del_less_serious_off, true) as $this_lso){
                                            array_push($to_array_allOffenses, $this_lso);
                                        }
                                    }
                                    if(!is_null($this_violator->del_other_off) OR !empty($this_violator->del_other_off)){
                                        if(!in_array(null, json_decode($this_violator->del_other_off, true))){
                                            foreach(json_decode($this_violator->del_other_off, true) as $this_oo){
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
                $vr_paginate .= $fltr_VR_tbl->render('pagination::bootstrap-4');
                $vr_data = array(
                    'vr_table'            => $vr_output,
                    'vr_table_paginate'   => $vr_paginate,
                    'vr_total_rows'       => $vr_total_matched_results,
                    'vr_total_data_found' => $vr_total_filtered_result
                   );
                   
                echo json_encode($vr_data);
            }else{
                return view('violation_records.deleted_violation_records');
            }
        }else{
            return view('profile.access_denied');
        }
    }

    // DELETED VIOLATIONS MODULE
    // permanent Delete all recently deleted vioaltions
    public function permanent_delall_recentlydelviolations_confirmation(){
        // get all counts from deleted_violations_tbl
        $count_recentlyDelViolations = Deletedviolations::where('del_status', '=', 1)->count();
        if($count_recentlyDelViolations > 0){
            // custom values
            $output = '';
            // total offenses count
            $sum_offensesCount = Deletedviolations::where('del_status', '=', 1)->sum('del_offense_count');
            // plurals
            if($count_recentlyDelViolations > 1){
                $tr_s = 's';
            }else{
                $tr_s = '';
            }
            if($sum_offensesCount > 1){
                if($sum_offensesCount > 1){
                    $sao_s = 's';
                }else{
                    $sao_s = '';
                }
            }else{
                $sao_s = '';
            }
            $sq = "'";

            $output .= '
            <form id="form_confirmPermDeleteAllRecViolations" action="'.route('violation_records.process_permanent_delete_all_violations').'" method="POST" enctype="multipart/form-data">
                <div class="modal-body border-0 py-0">
                    <div class="card-body lightBlue_cardBody shadow-none">
                        <span class="lightBlue_cardBody_blueTitle"><i class="fa fa-list-ul mr-1" aria-hidden="true"></i> Total Data:</span>
                        <span class="lightBlue_cardBody_notice"><span class="font-weight-bold">'.$count_recentlyDelViolations . ' Recorded Violation'.$tr_s . ' </span> with a total of ' . $sum_offensesCount . ' offense'.$sao_s . ' will be permanently deleted from the system.</span>
                    </div>
                    <div class="card-body lightBlue_cardBody shadow-none mt-3">
                        <span class="lightBlue_cardBody_blueTitle">Reason for Deleting All Violations:</span>
                        <div class="form-group">
                            <textarea class="form-control" id="reason_permDeleteAllViolations" name="reason_perm_del_violations" rows="3" placeholder="Type reason for Deleting Violations (required)" required></textarea>
                        </div>
                    </div>
                    <div class="card-body lightRed_cardBody shadow-none mt-3">
                        <span class="lightRed_cardBody_redTitle"><i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> Warning:</span>
                        <span class="lightRed_cardBody_notice">This action will permanently delete <span class="font-weight-bold"> (All) </span> recently deleted violation'.$tr_s . ' and can'.$sq.'t be undone. You will never be able to recover permanently deleted data.</span>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                    <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                    <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">

                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button id="cancel_permDeleteAllRecViolations_btn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                        <button id="process_permDeleteAllRecViolations_btn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0" disabled>Permanent Delete All <i class="fa fa-trash-o btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </form>
            '; 
            echo $output;
        }
    }
    // process permanent delete of all recently deleted violations
    public function process_permanent_delete_all_violations(Request $request){
        // get all requests
        $get_reason_permDelete = $request->get('reason_perm_del_violations');
        $get_respo_user_id     = $request->get('respo_user_id');
        $get_respo_user_lname  = $request->get('respo_user_lname');
        $get_respo_user_fname  = $request->get('respo_user_fname');
        
        // custom values
        $now_timestamp = now();
        $sq = "'";
        $zero = 0;

        // query all recently deleted violations from deleted_violations_tbl
        $query_delViolations_info = Deletedviolations::select('del_id', 'del_offense_count', 'del_stud_num')->where('del_status', '=', 1)->get();
        $count_foundRecDeletedViolations = count($query_delViolations_info);
        if($count_foundRecDeletedViolations > 0){
            foreach($query_delViolations_info as $this_receDelViolation){
                $perm_delete_all_status = Deletedviolations::where('del_status', '=', 1)
                            ->update([
                                'del_status'      => $zero,
                                'perm_deleted_at' => $now_timestamp,
                                'perm_deleted_by' => $get_respo_user_id
                            ]); 
            }
            if($perm_delete_all_status){
                // get all latest del_id from deleted_violations_tbl as reference for user's activity
                $toArray_permDeleted_AllViolaIds = array();
                $query_allPermDeleted_violaInfo = Deletedviolations::select('del_id', 'del_offense_count', 'del_stud_num')
                                                    ->where('del_status', '=', 0)
                                                    ->latest('perm_deleted_at')
                                                    ->offset(0)
                                                    ->limit($count_foundRecDeletedViolations)
                                                    ->get();
                if(count($query_allPermDeleted_violaInfo) > 0){
                    // pushing all del_ids to $toArray_permDeleted_AllViolaIds
                    foreach($query_allPermDeleted_violaInfo as $thisPermDeleted_violaInfo){
                        array_push($toArray_permDeleted_AllViolaIds, $thisPermDeleted_violaInfo->del_id);
                    }
                    $to_Json_PermDeleted_AllViolaIds = json_encode($toArray_permDeleted_AllViolaIds);
                    $ext_JsonPermDeleted_AllViolaIds = str_replace(array( '{', '}', '"', ':', 'del_id' ), '', $to_Json_PermDeleted_AllViolaIds);
                    // process after deletion
                    foreach($query_allPermDeleted_violaInfo as $thisPermDeleted_violaInfo_){
                        $sel_stud_info = Students::select('Student_Number', 'First_Name', 'Middle_Name', 'Last_Name', 'Email', 'School_Name', 'Course', 'YearLevel')
                                            ->where('Student_Number', $thisPermDeleted_violaInfo_->del_stud_num)
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

                        // offense count
                        if($thisPermDeleted_violaInfo_->del_offense_count > 0){
                            if($thisPermDeleted_violaInfo_->del_offense_count > 1){
                                $sPdo_s = 's';
                            }else{
                                $sPdo_s = '';
                            }
                        }else{
                            $sPdo_s = '';
                        }

                        // record activity
                        $record_act = new Useractivites;
                        $record_act->created_at                 = $now_timestamp;
                        $record_act->act_respo_user_id          = $get_respo_user_id;
                        $record_act->act_respo_users_lname      = $get_respo_user_lname;
                        $record_act->act_respo_users_fname      = $get_respo_user_lname;
                        $record_act->act_type                   = 'violation deletion';
                        $record_act->act_details                = 'Permanently Deleted ' . $thisPermDeleted_violaInfo_->del_offense_count . ' Offense'.$sPdo_s . ' made by ' . $yearLevel_txt . ' ' . $sel_stud_Course . ' student: ' . $sel_stud_Fname . ' ' . $sel_stud_Mname . ' ' . $sel_stud_Lname.'.';
                        $record_act->act_perm_deleted_viola_ids = $ext_JsonPermDeleted_AllViolaIds;
                        $record_act->save();
                    }
                    if($record_act){
                        return back()->withSuccessStatus('Violation has been deleted permanently.');
                    }else{
                        return back()->withFailedStatus('Recordeing User'.$sq.'s Activity: Permanent Deletion of All Violation, has failed!');
                    }
                }
            }else{
                return back()->withFailedStatus('Permanent deletion of All Violations has Failed! try again later.');
            }
        }else{
            return back()->withFailedStatus(' There are no records of recently deleted violations! please reload this page.');
        }
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
                                <span class="lightGreen_cardBody_greenTitle mb-1">Select Sanctions:</span>
                            ';
                            // check if there are created sanctions from created_sanctions_tbl
                            $query_crSanctions = CreatedSanctions::select('crSanct_id', 'crSanct_details')->get();
                            $count_query_crSanctions = count($query_crSanctions);
                            if($count_query_crSanctions > 0){
                                foreach($query_crSanctions as $crSanction_option){
                                    $output .= '
                                    <div class="form-group mx-0 mt-0 mb-1">
                                        <div class="custom-control custom-checkbox align-items-center">
                                            <input type="checkbox" name="sanctions[]" value="'.$crSanction_option->crSanct_details.'" class="custom-control-input cursor_pointer sanctMarkSingle" id="'.$crSanction_option->crSanct_id.'_selectThisCrSanct_id" >
                                            <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="'.$crSanction_option->crSanct_id.'_selectThisCrSanct_id">'.$crSanction_option->crSanct_details.'</label>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                            $output .= '
                                <span class="lightGreen_cardBody_greenTitle mb-1 mt-3">Or Type New Sanctions:</span>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <span class="input-group-text txt_iptgrp_append font-weight-bold">1. </span>
                                    </div>
                                    <input type="text" id="addSanctions_input" name="sanctions[]" class="form-control input_grpInpt3" placeholder="Type Sanction" aria-label="Type Sanction" aria-describedby="add-sanctions-input" />
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
            $new_hasSanctCount = 0;
            if($count_sanctions > 0){
                foreach($get_sanctions as $sanction){
                    if(!is_null($sanction) OR !empty($sanction)){
                        $record_sanctions = new Sanctions;
                        $record_sanctions->stud_num      = $get_sel_stud_num;
                        $record_sanctions->for_viola_id  = $get_for_viola_id;
                        $record_sanctions->sanct_details = $sanction;
                        $record_sanctions->respo_user_id = $get_respo_user_id;
                        $record_sanctions->created_at    = $now_timestamp;
                        $record_sanctions->save();
                        $new_hasSanctCount++;
                    }
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
                                                    ->limit($new_hasSanctCount)
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
                        'has_sanct_count' => $new_hasSanctCount,
                        'updated_at'      => $now_timestamp
                        ]);
            // record activity
                $record_act = new Useractivites;
                $record_act->created_at             = $now_timestamp;
                $record_act->act_respo_user_id      = $get_respo_user_id;
                $record_act->act_respo_users_lname  = $get_respo_user_lname;
                $record_act->act_respo_users_fname  = $get_respo_user_fname;
                $record_act->act_type               = 'sanction entry';
                $record_act->act_details            = 'Added ' . $new_hasSanctCount . ' Sanction'.$sc_s . ' for the ' . $sel_viola_offense_count . ' Offense'.$vc_s . ' made by ' . $yearLevel_txt . ' ' . $sel_stud_Course . ' student: ' . $sel_stud_Fname . ' ' . $sel_stud_Mname . ' ' . $sel_stud_Lname . ' on ' . date('F d, Y', strtotime($sel_viola_recorded_at)).'.';
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
                $get_recBy_info = Users::select('id', 'user_role', 'user_lname')
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
                                    $output .='
                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12" data-toggle="tooltip" data-placement="top" title="' . $recByTooltip . '">
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
                            // to array all registered sanctions
                            $toArray_regSanctionDetails = array();
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
                            if($count_all_sanctions > 0){
                                // to array all registered sanctions
                                foreach($get_all_sanctions as $this_registered_sanct){
                                    array_push($toArray_regSanctionDetails, $this_registered_sanct->sanct_details);
                                }

                                // plural
                                if($count_all_sanctions > 1){
                                    $cas_s = 's';
                                }else{
                                    $cas_s = '';
                                }
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
                                                    <hr class="hr_grn">
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
                                        <span class="lightGreen_cardBody_greenTitle mb-1">Registered Sanctions:</span>
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
                                        <hr class="hr_grn">
                                        <span class="lightGreen_cardBody_greenTitle mb-1">Add New Sanctions:</span>
                                        ';
                                        // check if there are created sanctions from created_sanctions_tbl
                                        $query_crSanctions = CreatedSanctions::select('crSanct_id', 'crSanct_details')->get();
                                        $count_query_crSanctions = count($query_crSanctions);
                                        if($count_query_crSanctions > 0){
                                            foreach($query_crSanctions as $crSanction_option){
                                                if(!in_array($crSanction_option->crSanct_details, json_decode(json_encode($toArray_regSanctionDetails)))){
                                                    $output .= '
                                                    <div class="form-group mx-0 mt-0 mb-1">
                                                        <div class="custom-control custom-checkbox align-items-center">
                                                            <input type="checkbox" name="new_sanctions[]" value="'.$crSanction_option->crSanct_details.'" class="custom-control-input cursor_pointer markAddThis_NewSanction" id="'.$crSanction_option->crSanct_id.'_addThisCrSanct_id" >
                                                            <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="'.$crSanction_option->crSanct_id.'_addThisCrSanct_id">'.$crSanction_option->crSanct_details.'</label>
                                                        </div>
                                                    </div>
                                                    ';
                                                }
                                            }
                                        }
                                        // <div class="input-group-append">
                                        //         <span class="input-group-text txt_iptgrp_append font-weight-bold">'.$txt_iptgrp_append_count.'. </span>
                                        //     </div>
                                        $output .= '
                                        <span class="lightGreen_cardBody_greenTitle mb-1 mt-3">Or Type New Sanctions:</span>
                                        <div class="input-group mt-1 mb-2">
                                            <input type="text" id="addNewSanction_input" name="new_sanctions[]" class="form-control input_grpInpt3v1" placeholder="Type New Sanction" aria-label="Type New Sanction" aria-describedby="add-new-sanctions-input"  />
                                            <div class="input-group-append">
                                                <button class="btn btn-success btn_iptgrp_append m-0" id="btn_addNewSanct_input" type="button" disabled><i class="nc-icon nc-simple-add font-weight-bold" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                        <div class="addedSanctInputFields_div">

                                        </div>
                                        <hr class="hr_grn">
                                        <div class="row mt-3">
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
                                        <input type="hidden" name="allowed_sanctions_count" id="allowed_sanctions_count" value="'.$txt_allowed_append_count.'">
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
                                                    <hr class="hr_grn">
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
                    if(!is_null($new_sanction) OR !empty($sanction)){
                        $record_new_sanctions = new Sanctions;
                        $record_new_sanctions->stud_num      = $get_sel_stud_num;
                        $record_new_sanctions->for_viola_id  = $get_for_viola_id;
                        $record_new_sanctions->sanct_details = $new_sanction;
                        $record_new_sanctions->respo_user_id = $get_respo_user_id;
                        $record_new_sanctions->created_at    = $now_timestamp;
                        $record_new_sanctions->save();
                    }
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
    // add sanctions to all Monthly Violations ~ modal
    public function add_sanction_all_monthly_violations_form(Request $request){
        // get all request
        $sel_monthly_viola = $request->get('sel_monthly_viola');
        $sel_yearly_viola = $request->get('sel_yearly_viola');
        $sel_stud_num = $request->get('sel_stud_num');

        // try 
        // echo 'Year: ' . $sel_yearly_viola . '<br>';
        // echo 'Month: ' . $sel_monthly_viola . '<br>';
        // echo 'Student Number: ' . $sel_stud_num . '<br>';

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
                <form id="form_addSanctionsAllMonthlyViolationRec" action="'.route('violation_records.process_adding_sanctions_all_violations').'" class="form" enctype="multipart/form-data" method="POST">
                    <div class="cust_modal_body_gray">
                    ';
                    // query all violations for the selected month
                    $query_all_viola_info = Violations::where('stud_num', $sel_stud_num)
                    ->whereYear('recorded_at', $sel_yearly_viola)
                    ->whereMonth('recorded_at', $sel_monthly_viola)
                    ->where('has_sanction', '!=', 1)
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
                            <div class="accordion shadow-none cust_accordion_div1 mb-2" id="addSanctionsAllMonthlyViola_SelectOption_Parent'.$get_viola_id.'">
                                <div class="card custom_accordion_card">
                                    <div class="card-header py10l15r10 d-flex justify-content-between align-items-center" id="addSanctionsAllMonthlyViola_SelectOption_heading'.$get_viola_id.'">
                                        <div class="form-group m-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="'.$get_viola_id.'_markThisViola_id" value="'.$get_viola_id.'" name="sel_all_viola_id[]" class="custom-control-input cust_checkbox_label selViolMarkSingle" checked>
                                                <label class="custom-control-label cust_checkbox_label" for="'.$get_viola_id.'_markThisViola_id">
                                                    <span class="li_info_title"> '.date('F d, Y', strtotime($get_viola_recorded_at)).' <span class="'.$class_violationStat1.'"> ' . $txt_violationStat1.'</span></span>
                                                    <span class="li_info_subtitle">'.date('l - g:i A', strtotime($get_viola_recorded_at)).'</span>
                                                </label>
                                            </div>
                                        </div>
                                        <button class="btn cust_btn_smcircle3" type="button" data-toggle="collapse" data-target="#addSanctionsAllMonthlyViola_SelectOption'.$get_viola_id.'" aria-expanded="true" aria-controls="addSanctionsAllMonthlyViola_SelectOption'.$get_viola_id.'">
                                            <i class="nc-icon nc-minimal-down"></i>
                                        </button>
                                    </div>
                                    <div id="addSanctionsAllMonthlyViola_SelectOption'.$get_viola_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="addSanctionsAllMonthlyViola_SelectOption_heading'.$get_viola_id.'" data-parent="#addSanctionsAllMonthlyViola_SelectOption_Parent'.$get_viola_id.'">
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
                        <div class="card-body lightGreen_cardBody shadow-none mb-2">
                            <div class="row mb-2">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group mx-0 mt-0 mb-1">
                                        <div class="custom-control custom-checkbox align-items-center">
                                            <input type="checkbox" name="select_all_violations" value="select_all_violations" class="custom-control-input cursor_pointer" id="selViolMarkAll" checked>
                                            <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="selViolMarkAll">Select All ('.$sum_all_offenses.') Offense'.$sO_s.'.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="lightGreen_cardBody_notice"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> You can still edit added sanctions for specific recorded violations.</span>
                        </div>
                        <div class="card-body lightGreen_cardBody mb-2">
                            <span class="lightGreen_cardBody_greenTitle mb-1">Select Sanctions:</span>
                        ';
                        // check if there are created sanctions from created_sanctions_tbl
                        $query_crSanctions = CreatedSanctions::select('crSanct_id', 'crSanct_details')->get();
                        $count_query_crSanctions = count($query_crSanctions);
                        if($count_query_crSanctions > 0){
                            foreach($query_crSanctions as $crSanction_option){
                                $output .= '
                                <div class="form-group mx-0 mt-0 mb-1">
                                    <div class="custom-control custom-checkbox align-items-center">
                                        <input type="checkbox" name="sanctions[]" value="'.$crSanction_option->crSanct_details.'" class="custom-control-input cursor_pointer sanctMarkSingle" id="'.$crSanction_option->crSanct_id.'_selectThisCrSanct_id" >
                                        <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="'.$crSanction_option->crSanct_id.'_selectThisCrSanct_id">'.$crSanction_option->crSanct_details.'</label>
                                    </div>
                                </div>
                                ';
                            }
                        }
                        $output .= '
                            <span class="lightGreen_cardBody_greenTitle mb-1 mt-3">Or Type New Sanctions:</span>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text txt_iptgrp_append font-weight-bold">1. </span>
                                </div>
                                <input type="text" id="addSanctions_input" name="sanctions[]" class="form-control input_grpInpt3" placeholder="Type Sanction" aria-label="Type Sanction" aria-describedby="add-sanctions-input" />
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
                        <input type="hidden" name="sel_stud_num" value="'.$sel_stud_num.'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <div class="btn-group" role="group" aria-label="add sanctions actions">
                            <button id="cancel_addSanctionsAllMonthlyViolationRecBtn" type="button" class="btn btn-round btn_svms_red btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_addSanctionsAllMonthlyViolationRecBtn" type="submit" class="btn btn-round btn-success btn_show_icon m-0" disabled> Register Sanctions <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        ';

        echo $output;

    }
    // add sanctions to all Monthly Violations ~ modal
    public function add_sanction_all_yearly_violations_form(Request $request){
        // get all request
        $sel_yearly_viola = $request->get('sel_yearly_viola');
        $sel_stud_num = $request->get('sel_stud_num');

        // try 
        // echo 'Year: ' . $sel_yearly_viola . '<br>';
        // echo 'Student Number: ' . $sel_stud_num . '<br>';

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
                <form id="form_addSanctionsAllMonthlyViolationRec" action="'.route('violation_records.process_adding_sanctions_all_violations').'" class="form" enctype="multipart/form-data" method="POST">
                    <div class="cust_modal_body_gray">
                    ';
                    // query all violations for the selected month
                    $query_all_viola_info = Violations::where('stud_num', $sel_stud_num)
                    ->whereYear('recorded_at', $sel_yearly_viola)
                    ->where('has_sanction', '!=', 1)
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
                            <div class="accordion shadow-none cust_accordion_div1 mb-2" id="addSanctionsAllMonthlyViola_SelectOption_Parent'.$get_viola_id.'">
                                <div class="card custom_accordion_card">
                                    <div class="card-header py10l15r10 d-flex justify-content-between align-items-center" id="addSanctionsAllMonthlyViola_SelectOption_heading'.$get_viola_id.'">
                                        <div class="form-group m-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="'.$get_viola_id.'_markThisViola_id" value="'.$get_viola_id.'" name="sel_all_viola_id[]" class="custom-control-input cust_checkbox_label selViolMarkSingle" checked>
                                                <label class="custom-control-label cust_checkbox_label" for="'.$get_viola_id.'_markThisViola_id">
                                                    <span class="li_info_title"> '.date('F d, Y', strtotime($get_viola_recorded_at)).' <span class="'.$class_violationStat1.'"> ' . $txt_violationStat1.'</span></span>
                                                    <span class="li_info_subtitle">'.date('l - g:i A', strtotime($get_viola_recorded_at)).'</span>
                                                </label>
                                            </div>
                                        </div>
                                        <button class="btn cust_btn_smcircle3" type="button" data-toggle="collapse" data-target="#addSanctionsAllMonthlyViola_SelectOption'.$get_viola_id.'" aria-expanded="true" aria-controls="addSanctionsAllMonthlyViola_SelectOption'.$get_viola_id.'">
                                            <i class="nc-icon nc-minimal-down"></i>
                                        </button>
                                    </div>
                                    <div id="addSanctionsAllMonthlyViola_SelectOption'.$get_viola_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="addSanctionsAllMonthlyViola_SelectOption_heading'.$get_viola_id.'" data-parent="#addSanctionsAllMonthlyViola_SelectOption_Parent'.$get_viola_id.'">
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
                        <div class="card-body lightGreen_cardBody shadow-none mb-2">
                            <div class="row mb-2">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group mx-0 mt-0 mb-1">
                                        <div class="custom-control custom-checkbox align-items-center">
                                            <input type="checkbox" name="select_all_violations" value="select_all_violations" class="custom-control-input cursor_pointer" id="selViolMarkAll" checked>
                                            <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="selViolMarkAll">Select All ('.$sum_all_offenses.') Offense'.$sO_s.'.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="lightGreen_cardBody_notice"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> You can still edit added sanctions for specific recorded violations.</span>
                        </div>
                        <div class="card-body lightGreen_cardBody mb-2">
                            <span class="lightGreen_cardBody_greenTitle mb-1">Select Sanctions:</span>
                        ';
                        // check if there are created sanctions from created_sanctions_tbl
                        $query_crSanctions = CreatedSanctions::select('crSanct_id', 'crSanct_details')->get();
                        $count_query_crSanctions = count($query_crSanctions);
                        if($count_query_crSanctions > 0){
                            foreach($query_crSanctions as $crSanction_option){
                                $output .= '
                                <div class="form-group mx-0 mt-0 mb-1">
                                    <div class="custom-control custom-checkbox align-items-center">
                                        <input type="checkbox" name="sanctions[]" value="'.$crSanction_option->crSanct_details.'" class="custom-control-input cursor_pointer sanctMarkSingle" id="'.$crSanction_option->crSanct_id.'_selectThisCrSanct_id" >
                                        <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="'.$crSanction_option->crSanct_id.'_selectThisCrSanct_id">'.$crSanction_option->crSanct_details.'</label>
                                    </div>
                                </div>
                                ';
                            }
                        }
                        $output .= '
                            <span class="lightGreen_cardBody_greenTitle mb-1 mt-3">Or Type New Sanctions:</span>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text txt_iptgrp_append font-weight-bold">1. </span>
                                </div>
                                <input type="text" id="addSanctions_input" name="sanctions[]" class="form-control input_grpInpt3" placeholder="Type Sanction" aria-label="Type Sanction" aria-describedby="add-sanctions-input" />
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
                        <input type="hidden" name="sel_stud_num" value="'.$sel_stud_num.'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <div class="btn-group" role="group" aria-label="add sanctions actions">
                            <button id="cancel_addSanctionsAllMonthlyViolationRecBtn" type="button" class="btn btn-round btn_svms_red btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_addSanctionsAllMonthlyViolationRecBtn" type="submit" class="btn btn-round btn-success btn_show_icon m-0" disabled> Register Sanctions <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        ';

        echo $output;

    }
    // process adding sanctions to all violations
    public function process_adding_sanctions_all_violations(Request $request){
        // get all request
        $get_sel_stud_num     = $request->get('sel_stud_num');
        $get_respo_user_id    = $request->get('respo_user_id');
        $get_respo_user_lname = $request->get('respo_user_lname');
        $get_respo_user_fname = $request->get('respo_user_fname');   
        $get_sanctions        = json_decode(json_encode($request->get('sanctions')));
        $get_sel_all_viola_id = json_decode(json_encode($request->get('sel_all_viola_id')));
        $get_sel_Year         = $request->get('sel_Year');
        $get_sel_Month        = $request->get('sel_Month');   

        // custom values
        $now_timestamp = now();
        $sq = "'";

        // if there are violations selected
        if(count($get_sel_all_viola_id) > 0){
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
            // process adding sanctions fro each selected violations
            foreach($get_sel_all_viola_id as $this_ViolaId){
                // get selected violatin's info
                $sel_viola_info = Violations::select('viola_id', 'recorded_at', 'offense_count')
                        ->where('viola_id', $this_ViolaId)
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
                // save requests to sanctions_tbl
                $new_hasSanctCount = 0;
                if(count($get_sanctions) > 0){
                    $count_sanctions = count($get_sanctions);
                    if($count_sanctions > 0){
                        if($count_sanctions > 1){
                            $sc_s = 's';
                        }else{
                            $sc_s = '';
                        }
                    }
                    foreach($get_sanctions as $sanction){
                        if(!is_null($sanction) OR !empty($sanction)){
                            $record_sanctions = new Sanctions;
                            $record_sanctions->stud_num      = $get_sel_stud_num;
                            $record_sanctions->for_viola_id  = $this_ViolaId;
                            $record_sanctions->sanct_details = $sanction;
                            $record_sanctions->respo_user_id = $get_respo_user_id;
                            $record_sanctions->created_at    = $now_timestamp;
                            $record_sanctions->save();
                            $new_hasSanctCount++;
                        }
                    }
                    if($record_sanctions){
                        // get all recorded sanctions' ids
                        $to_array_sanct_ids = array();
                        $get_all_processed_sanctions = Sanctions::select('sanct_id')
                                                            ->where('stud_num', $get_sel_stud_num)
                                                            ->where('for_viola_id', $this_ViolaId)
                                                            ->where('respo_user_id', $get_respo_user_id)
                                                            ->offset(0)
                                                            ->limit($new_hasSanctCount)
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
                            ->where('viola_id', $this_ViolaId)
                            ->update([
                                'has_sanction'    => 1,
                                'has_sanct_count' => $new_hasSanctCount,
                                'updated_at'      => $now_timestamp
                                ]);
                        // record activity
                        $record_act = new Useractivites;
                        $record_act->created_at             = $now_timestamp;
                        $record_act->act_respo_user_id      = $get_respo_user_id;
                        $record_act->act_respo_users_lname  = $get_respo_user_lname;
                        $record_act->act_respo_users_fname  = $get_respo_user_fname;
                        $record_act->act_type               = 'sanction entry';
                        $record_act->act_details            = 'Added ' . $new_hasSanctCount . ' Sanction'.$sc_s . ' for the ' . $sel_viola_offense_count . ' Offense'.$vc_s . ' made by ' . $yearLevel_txt . ' ' . $sel_stud_Course . ' student: ' . $sel_stud_Fname . ' ' . $sel_stud_Mname . ' ' . $sel_stud_Lname . ' on ' . date('F d, Y', strtotime($sel_viola_recorded_at)).'.';
                        $record_act->act_affected_sanct_ids = $ext_jsonSanct_ids;
                        $record_act->save();
                    }else{
                        return back()->withFailedStatus('Adding Sanctions has failed! Try Again later.');
                    }
                }else{
                    return back()->withFailedStatus('No Corresponding Sanctions has been selected! please Select Sanctions.');
                }
            }
            if($record_act){
                return back()->withSuccessStatus('Sanctions was recorded successfully!');
            }else{
                return back()->withFailedStatus('Recording User Activity has failed!');
            }
        }else{
            return back()->withFailedStatus('No Offenses has been selected! please Select Recorded Offenses to add Sanctions.');
        }

        // try
        // if(count($get_sel_all_viola_id) > 0){
        //     echo 'Student Number: ' .$get_sel_stud_num .'<br>';
        //     echo 'Year: ' .$get_sel_Year .'<br>';
        //     echo 'Month: ' .$get_sel_Month .'<br>';
        //     echo 'violation ids: <br>';
        //     foreach($get_sel_all_viola_id as $this_selViola_id){
        //         echo '~ ' . $this_selViola_id . '<br>';
        //     }
        //     echo '<br>';
        //     if(count($get_sanctions) > 0){
        //         echo 'Sanctions: <br>';
        //         foreach($get_sanctions as $this_selSanction){
        //             echo '~ ' . $this_selSanction . '<br>';
        //         }
        //     }else{
        //         echo 'No sanctions Selected!';
        //     }
        //     echo 'Responsible user: ' .$get_respo_user_id.': ' . $get_respo_user_fname . ' ' . $get_respo_user_lname . '<br>';
        // }else{
        //     echo 'No recorded Violations Selected!';
        // }
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

        // output
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
                            <button id="submit_deleteAllViolationRecBtn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0" disabled> Delete Violations <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
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
    // temporary delete all yearly violations confirmation modal
    public function delete_all_yearly_violations_form(Request $request){
        // get all request
        $sel_yearly_viola = $request->get('sel_yearly_viola');
        $sel_stud_num      = $request->get('sel_stud_num');

        // try
        // echo 'student number: ' . $sel_stud_num.'<br/>';
        // echo 'delete all violations from year: ' . $sel_yearly_viola.'<br/>';

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
                <form id="form_deleteAllYearlyViolationRec" action="'.route('violation_records.delete_all_monthly_violations').'" class="form" enctype="multipart/form-data" method="POST">
                    <div class="cust_modal_body_gray">
                    ';
                    // query all violations for the selected month
                    $query_all_viola_info = Violations::where('stud_num', $sel_stud_num)
                    ->whereYear('recorded_at', $sel_yearly_viola)
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
                            <div class="accordion shadow-none cust_accordion_div1 mb-2" id="delAllYearlyViola_SelectOption_Parent'.$get_viola_id.'">
                                <div class="card custom_accordion_card">
                                    <div class="card-header py10l15r10 d-flex justify-content-between align-items-center" id="delAllYearlyViola_SelectOption_heading'.$get_viola_id.'">
                                        <div class="form-group m-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="'.$get_viola_id.'_markDelThisYearViola_id" value="'.$get_viola_id.'" name="del_all_viola_id[]" class="custom-control-input cust_checkbox_label delViolMarkSingleYear" checked>
                                                <label class="custom-control-label cust_checkbox_label" for="'.$get_viola_id.'_markDelThisYearViola_id">
                                                    <span class="li_info_title"> '.date('F d, Y', strtotime($get_viola_recorded_at)).' <span class="'.$class_violationStat1.'"> ' . $txt_violationStat1.'</span></span>
                                                    <span class="li_info_subtitle">'.date('l - g:i A', strtotime($get_viola_recorded_at)).'</span>
                                                </label>
                                            </div>
                                        </div>
                                        <button class="btn cust_btn_smcircle3" type="button" data-toggle="collapse" data-target="#delAllYearlyViola_SelectOption'.$get_viola_id.'" aria-expanded="true" aria-controls="delAllYearlyViola_SelectOption'.$get_viola_id.'">
                                            <i class="nc-icon nc-minimal-down"></i>
                                        </button>
                                    </div>
                                    <div id="delAllYearlyViola_SelectOption'.$get_viola_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="delAllYearlyViola_SelectOption_heading'.$get_viola_id.'" data-parent="#delAllYearlyViola_SelectOption_Parent'.$get_viola_id.'">
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
                                            <input type="checkbox" name="delete_all_violations" value="delete_all_violations" class="custom-control-input cursor_pointer" id="delViolMarkAllYear" checked>
                                            <label class="custom-control-label lightBlue_cardBody_chckboxLabel" for="delViolMarkAllYear">Delete All ('.$sum_all_offenses.') Offense'.$sO_s.'.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="lightBlue_cardBody_notice"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> Deleting recorded violation will also delete its corresponding sanctions.</span>
                        </div>
                        <div class="card-body lightBlue_cardBody shadow-none">
                            <span class="lightBlue_cardBody_blueTitle">Reason for Deleting Violations:</span>
                            <div class="form-group">
                                <textarea class="form-control" id="delete_all_yearly_violation_reason" name="delete_all_violation_reason" rows="3" placeholder="Type reason for Deleting Recorded Violations (required)" required></textarea>
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
                            <button id="cancel_deleteAllYearlyViolationRecBtn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_deleteAllYearlyViolationRecBtn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0" disabled> Delete Violations <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        ';

        echo $output;
    }
    // process temporary deletion of violation
    public function delete_violation(Request $request){
        // custom values
            $now_timestamp     = now();
        // get all request
            $sel_viola_id          = $request->get('for_viola_id');
            $sel_stud_num          = $request->get('sel_stud_num');
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
        
        if(!is_null($get_tobe_deleted_viola_ids) OR !empty($get_tobe_deleted_viola_ids)){
            $count_selected_viola_ids = count($get_tobe_deleted_viola_ids);
        }else{
            $count_selected_viola_ids = 0;
        }
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
            if(!is_null($sel_viola_ids) OR !empty($sel_viola_ids)){
                $count_sel_viola_ids = count($sel_viola_ids);
            }else{
                $count_sel_viola_ids = 0;
            }
            $sq = "'";
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
                return back()->withFailedStatus('Permanent deletion of Violation has Failed! try again later.');
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
                                            <label class="custom-control-label lightBlue_cardBody_chckboxLabel" for="permDelViolMarkAll">Permanent Delete All ('.$sum_all_del_offenses.') Offense'.$sdO_s.'.</label>
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
                        <input type="hidden" name="recover_viola_ids[]" value="'.$sel_viola_id.'">
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
    // recover all deleted violations confirmation modal
    public function recover_all_deleted_violation_form(Request $request){
        // get all request
        $sel_viola_ids = json_decode($request->get('recover_viola_ids'), true); 
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

        $output = '';
        $output .= '
            <div class="modal-body border-0 p-0">
                <form id="form_recoverAllDeletedViolationRec" action="'.route('violation_records.recover_deleted_violation').'" class="form" enctype="multipart/form-data" method="POST">
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
                            <div class="accordion shadow-none cust_accordion_div1 mb-2" id="recoverDelAllViola_SelectOption_Parent'.$this_viola_id.'">
                                <div class="card custom_accordion_card">
                                    <div class="card-header py10l15r10 d-flex justify-content-between align-items-center" id="recoverDelAllViola_SelectOption_heading'.$this_viola_id.'">
                                        <div class="form-group m-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="'.$this_viola_id.'_markRecoverDelThisViola_id" value="'.$get_from_viola_id.'" name="recover_viola_ids[]" class="custom-control-input cust_checkbox_label recoverDelViolMarkSingle" checked>
                                                <label class="custom-control-label cust_checkbox_label" for="'.$this_viola_id.'_markRecoverDelThisViola_id">
                                                    <span class="li_info_title"> '.date('F d, Y', strtotime($get_viola_del_recorded_at)).' <span class="'.$class_violationStat1.'"> ' . $txt_violationStat1.'</span></span>
                                                    <span class="li_info_subtitle">'.date('l - g:i A', strtotime($get_viola_del_recorded_at)).'</span>
                                                </label>
                                            </div>
                                        </div>
                                        <button class="btn cust_btn_smcircle3" type="button" data-toggle="collapse" data-target="#recoverDelAllViola_SelectOption'.$this_viola_id.'" aria-expanded="true" aria-controls="recoverDelAllViola_SelectOption'.$this_viola_id.'">
                                            <i class="nc-icon nc-minimal-down"></i>
                                        </button>
                                    </div>
                                    <div id="recoverDelAllViola_SelectOption'.$this_viola_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="recoverDelAllViola_SelectOption_heading'.$this_viola_id.'" data-parent="#recoverDelAllViola_SelectOption_Parent'.$this_viola_id.'">
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
                                            <input type="checkbox" name="recover_delete_all_violations" value="recover_delete_all_violations" class="custom-control-input cursor_pointer" id="recoverDelViolMarkAll" checked>
                                            <label class="custom-control-label lightBlue_cardBody_chckboxLabel" for="recoverDelViolMarkAll">Recover All Deleted ('.$sum_all_del_offenses.') Offense'.$sdO_s.'.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="lightBlue_cardBody_notice"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> This action will recover selected Recently Deleted violation records and its corresponding sanctions.</span>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="sel_stud_num" value="'.$sel_stud_num.'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <div class="btn-group" role="group" aria-label="delete sanctions actions">
                            <button id="cancel_recoverAllDeletedViolationRecBtn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_recoverAllDeletedViolationRecBtn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0"> Recover Violation <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
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
            $sel_viola_ids         = json_decode(json_encode($request->get('recover_viola_ids'))); 
            $sel_stud_num          = $request->get('sel_stud_num');
            $sel_respo_user_id     = $request->get('respo_user_id');
            $sel_respo_user_lname  = $request->get('respo_user_lname');
            $sel_respo_user_fname  = $request->get('respo_user_fname');  
        // cusotms
            if(!is_null($sel_viola_ids) OR !empty($sel_viola_ids)){
                $count_sel_viola_ids = count($sel_viola_ids);
            }else{
                $count_sel_viola_ids = 0;
            }
            $sq = "'";
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

    // GENERATE VIOLATIONS RECORDS REPORT
    // REPORT ~ VIOLATION RECORDS MODULE
    // confirm ~ generate violations records report
    public function generate_violation_records_confirmation_modal(Request $request){
        // get all filtered data
            $filtered_SearchInput           = $request->get('fvr_search');
            $filtered_SchoolNames           = $request->get('fvr_schools');
            $filtered_Programs              = $request->get('fvr_programs');
            $filtered_YearLevels            = $request->get('fvr_yearlvls');
            $filtered_Genders               = $request->get('fvr_genders');
            $filtered_MinAgeRange           = $request->get('fvr_minAgeRange');
            $filtered_MaxAgeRange           = $request->get('fvr_maxAgeRange');
            $deafult_MinAgeRange            = $request->get('df_minAgeRange');
            $deafult_MaxAgeRange            = $request->get('df_maxAgeRange');
            $filtered_ViolationStatus       = $request->get('fvr_status');
            $filtered_ViolationDateFrom     = $request->get('fvr_rangefrom');
            $filtered_ViolationDateTo       = $request->get('fvr_rangeTo');
            $filtered_ViolationOrderBy      = $request->get('vr_orderBy');
            $filtered_ViolationOrderByRange = $request->get('selectedOrderByRange');
            $filtered_TotalRecords          = $request->get('fvr_totalRecords');

        // custom values
            // School Names
            if($filtered_SchoolNames != 0 OR !empty($filtered_SchoolNames)){
                $txt_SchoolNames = $filtered_SchoolNames;
            }else{
                $txt_SchoolNames = 'All Schools';
            }
        // Programs
            if($filtered_Programs != 0 OR !empty($filtered_Programs)){
                $txt_Programs = $filtered_Programs;
            }else{
                $txt_Programs = 'All Programs';
            }
        // Year Levels
            if($filtered_YearLevels != 0 OR !empty($filtered_YearLevels)){
                if($filtered_YearLevels == 1){
                    $txt_YearLevels = 'First Year Levels';
                }else if($filtered_YearLevels == 2){
                    $txt_YearLevels = 'Second Year Levels';
                }else if($filtered_YearLevels == 3){
                    $txt_YearLevels = 'Third Year Levels';
                }else if($filtered_YearLevels == 4){
                    $txt_YearLevels = 'Fourth Year Levels';
                }else if($filtered_YearLevels == 5){
                    $txt_YearLevels = 'Fifth Year Levels';
                }else{
                    $txt_YearLevels = $filtered_YearLevels . ' Year Levels';
                }
            }else{
                $txt_YearLevels = 'All Year Levels';
            }
        // Gender
            if($filtered_Genders != 0 OR !empty($filtered_Genders)){
                $txt_Gender = $filtered_Genders;
            }else{
                $txt_Gender = 'All Genders';
            }
        // Age 
            if($filtered_MinAgeRange != 0 OR !empty($filtered_MinAgeRange) OR $filtered_MaxAgeRange != 0 OR !empty($filtered_MaxAgeRange)){
                if($filtered_MinAgeRange != $filtered_MaxAgeRange){
                    $txt_AgeRange = 'Ages ' . $filtered_MinAgeRange . ' to ' . $filtered_MaxAgeRange . ' Year Olds';
                }else{
                    $txt_AgeRange = 'Ages ' . $filtered_MinAgeRange . ' Year Olds';
                }
            }else{
                $txt_AgeRange = 'All Ages';
            }
        // Violatin Status
            if($filtered_ViolationStatus != 0 OR !empty($filtered_ViolationStatus)){
                $txt_ViolationStatus = ''.ucwords($filtered_ViolationStatus) . ' Violations';
            }else{
                $txt_ViolationStatus = 'Cleared & Not Cleared Violations';
            }
        // Date Range
            if($filtered_ViolationDateFrom != 0 OR !empty($filtered_ViolationDateFrom) OR $filtered_ViolationDateTo != 0 OR !empty($filtered_ViolationDateTo)){
                $format_ViolationDateFrom = date('F d, Y (l ~ g:i A)', strtotime($filtered_ViolationDateFrom));
                $format_ViolationDateTo = date('F d, Y (l ~ g:i A)', strtotime($filtered_ViolationDateTo));
                $txt_DateRange = 'From ' . $format_ViolationDateFrom . ' to ' . $format_ViolationDateTo.'';
            }else{
                $txt_DateRange = 'All Recorded Violations';
            }
        // Total Records
            if($filtered_TotalRecords > 0){
                if($filtered_TotalRecords > 1){
                    $txt_TotalRecords = ''.$filtered_TotalRecords . ' Records Found.';
                }else{
                    $txt_TotalRecords = ''.$filtered_TotalRecords . ' Record Found.';
                }
                $disablePrintButton = '';
            }else{
                $txt_TotalRecords = 'No Records Found.';
                $disablePrintButton = 'disabled';
            }
        // order by 
            if($filtered_ViolationOrderBy != 0 OR !empty($filtered_ViolationOrderBy)){
                if($filtered_ViolationOrderBy == 1){
                    $orderBy_filterVal = 'Student Number';
                }elseif($filtered_ViolationOrderBy == 2){
                    $orderBy_filterVal = 'Offense Count';
                }else{
                    $orderBy_filterVal = 'Recorded at';
                }
            }else{
                $orderBy_filterVal = 'Recorded at';
            }
        // order by range
            if(!empty($filtered_ViolationOrderByRange) OR $filtered_ViolationOrderByRange != 0){
                if($filtered_ViolationOrderByRange === 'asc'){
                    $orderByRange_filterVal = '(Ascending)';
                }else{
                    $orderByRange_filterVal = '(Descending)';
                }
            }else{
                $orderByRange_filterVal = '(Descending)';
            }

        // output
        $output = '';
        $output .= '
            <div class="modal-body border-0 py-0">
                <div class="card-body lightBlue_cardBody shadow-none">
                    <span class="lightBlue_cardBody_blueTitle">Report Content:</span>
                    <span class="lightBlue_cardBody_notice">The system will generate a report based on the filters you have applied as shown below.</span>
                </div>
                <div class="card-body lightBlue_cardBody shadow-none mt-2">
                    <span class="lightBlue_cardBody_blueTitle">Students Filters:</span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> Schools: <span class="font-weight-bold"> '.$txt_SchoolNames.' </span> </span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> Programs: <span class="font-weight-bold"> '.$txt_Programs.' </span> </span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> Year Levels: <span class="font-weight-bold"> '.$txt_YearLevels.' </span> </span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> Gender: <span class="font-weight-bold"> '.$txt_Gender.' </span> </span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> Age Range: <span class="font-weight-bold"> '.$txt_AgeRange.' </span> </span>
                    
                    <span class="lightBlue_cardBody_blueTitle mt-3">Violations Filters:</span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> Violation Status: <span class="font-weight-bold"> ' . $txt_ViolationStatus . ' </span> </span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> Order By: <span class="font-weight-bold"> ' . $orderBy_filterVal . ' </span> <span class="font-italic"> '.$orderByRange_filterVal.'</span> </span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-calendar-check-o text-success mr-1" aria-hidden="true"></i> Date Range: <span class="font-weight-bold"> ' . $txt_DateRange . ' </span> </span>

                    ';
                    if(!empty($filtered_SearchInput) OR $filtered_SearchInput != 0 OR !is_null($filtered_SearchInput)){
                        $output .= '
                        <span class="lightBlue_cardBody_blueTitle mt-3">Search Filter:</span>
                        <span class="lightBlue_cardBody_notice"><i class="fa fa-search text-success mr-1" aria-hidden="true"></i> <span class="font-weight-bold"> ' . $filtered_SearchInput . ' ... </span> </span>
                        ';
                    }
                    $output .= '
                </div>
                <div class="card-body lightBlue_cardBody shadow-none mt-2">
                    <span class="lightBlue_cardBody_blueTitle">Total Data:</span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> ' . $txt_TotalRecords . '</span>
                </div>
            </div>
            <form id="form_confirmGenerateViolationRecReport" target="_blank" action="'.route('violation_records.violation_records_pdf').'" method="POST" enctype="multipart/form-data">
                <div class="modal-footer border-0">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                    <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                    <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">

                    <input type="hidden" name="val_search_fltr" value="'.$filtered_SearchInput.'">
                    <input type="hidden" name="val_schools_fltr" value="'.$filtered_SchoolNames.'">
                    <input type="hidden" name="val_programs_fltr" value="'.$filtered_Programs.'">
                    <input type="hidden" name="val_year_levels_fltr" value="'.$filtered_YearLevels.'">
                    <input type="hidden" name="val_genders_fltr" value="'.$filtered_Genders.'">
                    <input type="hidden" name="val_min_age_fltr" value="'.$filtered_MinAgeRange.'">
                    <input type="hidden" name="val_max_age_fltr" value="'.$filtered_MaxAgeRange.'">
                    <input type="hidden" name="val_violation_status_fltr" value="'.$filtered_ViolationStatus.'">
                    <input type="hidden" name="val_date_from_fltr" value="'.$filtered_ViolationDateFrom.'">
                    <input type="hidden" name="val_date_to_fltr" value="'.$filtered_ViolationDateTo.'">
                    <input type="hidden" name="val_order_by" value="'.$filtered_ViolationOrderBy.'">
                    <input type="hidden" name="val_order_by_range" value="'.$filtered_ViolationOrderByRange.'">
                    <input type="hidden" name="val_total_records_fltr" value="'.$filtered_TotalRecords.'">

                    <input type="hidden" name="val_df_min_age_range" value="'.$deafult_MinAgeRange.'">
                    <input type="hidden" name="val_df_max_age_range" value="'.$deafult_MaxAgeRange.'">

                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button id="cancel_GenerateViolationRecordsReport_btn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                        <button id="process_GenerateViolationRecordsReport_btn" type="submit" class="btn btn-round btn-success btn_show_icon m-0" ' . $disablePrintButton . '>Generate Report <i class="nc-icon nc-single-copy-04 btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </form>
        ';
        echo $output;
    }
    // process ~ generate violatins records report - PDF
    public function violation_records_pdf(Request $request){
        // now timestamp
            $now_timestamp        = now();
        // get all request
            $respo_user_id        = $request->get('respo_user_id');
            $respo_user_lname     = $request->get('respo_user_lname');
            $respo_user_fname     = $request->get('respo_user_fname');  

            $filter_SearchInput   = $request->get('val_search_fltr');
            $filter_SchoolName    = $request->get('val_schools_fltr');
            $filter_Programs      = $request->get('val_programs_fltr');
            $filter_YearLevels    = $request->get('val_year_levels_fltr');
            $filter_Genders       = $request->get('val_genders_fltr');
            $filter_MinAgeRange   = $request->get('val_min_age_fltr');
            $filter_MaxAgeRange   = $request->get('val_max_age_fltr');
            $default_MinAgeRange  = $request->get('val_df_min_age_range');
            $default_MaxAgeRange  = $request->get('val_df_max_age_range');
            $filter_ViolationStat = $request->get('val_violation_status_fltr');
            $filter_FromDateRange = $request->get('val_date_from_fltr');
            $filter_ToDateRange   = $request->get('val_date_to_fltr');
            $filter_OrderBy       = $request->get('val_order_by');
            $filter_OrderByRange  = $request->get('val_order_by_range');
            $filter_TotalRecords  = $request->get('val_total_records_fltr');

        // try
            // echo 'Search Filter: ' . $filter_SearchInput . ' <br>';
            // echo 'School Name: ' . $filter_SchoolName . ' <br>';
            // echo 'Programs: ' . $filter_Programs . ' <br>';
            // echo 'Year levels: ' . $filter_YearLevels . ' <br>';
            // echo 'Genders: ' . $filter_Genders . ' <br>';
            // echo 'Min Age Range: ' . $filter_MinAgeRange . ' <br>';
            // echo 'Max Age Range: ' . $filter_MaxAgeRange . ' <br>';
            // echo 'Violation Status: ' . $filter_ViolationStat . ' <br>';
            // echo 'From: ' . $filter_FromDateRange . ' <br>';
            // echo 'To: ' . $filter_ToDateRange . ' <br>';
            // echo 'Total Records: ' . $filter_TotalRecords . ' <br>';

        // 

        // custom values
            // School Names
            if($filter_SchoolName != 0 OR !empty($filter_SchoolName)){
                $txt_SchoolNames = $filter_SchoolName;
            }else{
                $txt_SchoolNames = 'All Schools';
            }
        // Programs
            if($filter_Programs != 0 OR !empty($filter_Programs)){
                $txt_Programs = $filter_Programs;
            }else{
                $txt_Programs = 'All Programs';
            }
        // Year Levels
            if($filter_YearLevels != 0 OR !empty($filter_YearLevels)){
                if($filter_YearLevels == 1){
                    $txt_YearLevels = 'First Year Levels';
                }else if($filter_YearLevels == 2){
                    $txt_YearLevels = 'Second Year Levels';
                }else if($filter_YearLevels == 3){
                    $txt_YearLevels = 'Third Year Levels';
                }else if($filter_YearLevels == 4){
                    $txt_YearLevels = 'Fourth Year Levels';
                }else if($filter_YearLevels == 5){
                    $txt_YearLevels = 'Fifth Year Levels';
                }else{
                    $txt_YearLevels = $filter_YearLevels . ' Year Levels';
                }
            }else{
                $txt_YearLevels = 'All Year Levels';
            }
        // Gender
            if($filter_Genders != 0 OR !empty($filter_Genders)){
                $txt_Gender = ' '.$filter_Genders . ' ';
            }else{
                $txt_Gender = 'All Genders';
            }
        // Age 
            if($filter_MinAgeRange != 0 OR !empty($filter_MinAgeRange) OR $filter_MaxAgeRange != 0 OR !empty($filter_MaxAgeRange)){
                if($filter_MinAgeRange != $filter_MaxAgeRange){
                    $txt_AgeRange = 'Ages ' . $filter_MinAgeRange . ' to ' . $filter_MaxAgeRange . ' Year Olds';
                }else{
                    $txt_AgeRange = 'Ages ' . $filter_MinAgeRange . ' Year Olds';
                }
            }else{
                $txt_AgeRange = 'All Ages';
            }
        // Violatin Status
            if($filter_ViolationStat != 0 OR !empty($filter_ViolationStat)){
                $txt_ViolationStatus = ''.ucwords($filter_ViolationStat) . ' Violations';
            }else{
                $txt_ViolationStatus = 'Cleared & Not Cleared Violations';
            }
        // Date Range
            if($filter_FromDateRange != 0 OR !empty($filter_FromDateRange) OR $filter_ToDateRange != 0 OR !empty($filter_ToDateRange)){
                $format_ViolationDateFrom = date('F d, Y (l ~ g:i A)', strtotime($filter_FromDateRange));
                $format_ViolationDateTo = date('F d, Y (l ~ g:i A)', strtotime($filter_ToDateRange));
                $txt_DateRange = 'From ' . $format_ViolationDateFrom . ' to ' . $format_ViolationDateTo.'';
            }else{
                $txt_DateRange = 'All Recorded Violations';
            }
        // order by 
            if($filter_OrderBy != 0 OR !empty($filter_OrderBy)){
                if($filter_OrderBy == 1){
                    $orderBy_filterVal = 'stud_num';
                }elseif($filter_OrderBy == 2){
                    $orderBy_filterVal = 'offense_count';
                }else{
                    $orderBy_filterVal = 'recorded_at';
                }
            }else{
                $orderBy_filterVal = 'recorded_at';
            }
        // order by range
            if(!empty($filter_OrderByRange) OR $filter_OrderByRange != 0){
                if($filter_OrderByRange === 'asc'){
                    $orderByRange_filterVal = 'ASC';
                }else{
                    $orderByRange_filterVal = 'DESC';
                }
            }else{
                $orderByRange_filterVal = 'DESC';
            }
        // Total Records
            if($filter_TotalRecords > 0){
                if($filter_TotalRecords > 1){
                    $txt_TotalRecords = ''.$filter_TotalRecords . ' Records Found.';
                }else{
                    $txt_TotalRecords = ''.$filter_TotalRecords . ' Record Found.';
                }
                $disablePrintButton = '';
            }else{
                $txt_TotalRecords = 'No Records Found.';
                $disablePrintButton = 'disabled';
            }

        // query responsible user's info
            $query_respo_user = Users::select('user_role','user_lname', 'user_fname')->where('id', $respo_user_id)->first();

        // query
            if($filter_SearchInput != ''){
                $query_violation_records = DB::table('violations_tbl')
                                ->join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                                ->select('violations_tbl.*', 'students_tbl.*')
                                ->where(function($vrQuery) use ($filter_SearchInput) {
                                    $vrQuery->orWhere('students_tbl.Student_Number', 'like', '%'.$filter_SearchInput.'%')
                                                ->orWhere('students_tbl.First_Name', 'like', '%'.$filter_SearchInput.'%')
                                                ->orWhere('students_tbl.Middle_Name', 'like', '%'.$filter_SearchInput.'%')
                                                ->orWhere('students_tbl.Last_Name', 'like', '%'.$filter_SearchInput.'%')
                                                ->orWhere('students_tbl.Gender', 'like', '%'.$filter_SearchInput.'%')
                                                ->orWhere('students_tbl.School_Name', 'like', '%'.$filter_SearchInput.'%')
                                                ->orWhere('students_tbl.YearLevel', 'like', '%'.$filter_SearchInput.'%')
                                                ->orWhere('students_tbl.Course', 'like', '%'.$filter_SearchInput.'%')
                                                ->orWhere('violations_tbl.stud_num', 'like', '%'.$filter_SearchInput.'%');
                                })
                                ->where(function($vrQuery) use ($filter_SchoolName, $filter_Programs, $filter_YearLevels, $filter_Genders, $filter_MinAgeRange, $filter_MaxAgeRange, $default_MinAgeRange, $default_MaxAgeRange, $filter_ViolationStat, $filter_FromDateRange, $filter_ToDateRange){
                                    if($filter_SchoolName != 0 OR !empty($filter_SchoolName)){
                                        $vrQuery->where('students_tbl.School_Name', '=', $filter_SchoolName);
                                    }
                                    if($filter_Programs != 0 OR !empty($filter_Programs)){
                                        $vrQuery->where('students_tbl.Course', '=', $filter_Programs);
                                    }
                                    if($filter_YearLevels != 0 OR !empty($filter_YearLevels)){
                                        $vrQuery->where('students_tbl.YearLevel', '=', $filter_YearLevels);
                                    }
                                    if($filter_Genders != 0 OR !empty($filter_Genders)){
                                        $lower_vr_gender = Str::lower($filter_Genders);
                                        $vrQuery->where('students_tbl.Gender', '=', $lower_vr_gender);
                                    }
                                    if($filter_MinAgeRange != $default_MinAgeRange OR $filter_MaxAgeRange != $default_MaxAgeRange){
                                        $vrQuery->whereBetween('students_tbl.Age', [$filter_MinAgeRange, $filter_MaxAgeRange]);
                                    }
                                    if($filter_ViolationStat != 0 OR !empty($filter_ViolationStat)){
                                        $lower_filter_ViolationStat = Str::lower($filter_ViolationStat);
                                        $vrQuery->where('violations_tbl.violation_status', '=', $lower_filter_ViolationStat);
                                    }
                                    if($filter_FromDateRange != 0 OR !empty($filter_FromDateRange) AND $filter_ToDateRange != 0 OR !empty($filter_ToDateRange)){
                                        $vrQuery->whereBetween('violations_tbl.recorded_at', [$filter_FromDateRange, $filter_ToDateRange]);
                                    }
                                })
                                ->orderBy('violations_tbl.'.$orderBy_filterVal, $orderByRange_filterVal)
                                ->get();
            }else{
                $query_violation_records = DB::table('violations_tbl')
                                ->join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                                ->select('violations_tbl.*', 'students_tbl.*')
                                ->where(function($vrQuery) use ($filter_SchoolName, $filter_Programs, $filter_YearLevels, $filter_Genders, $filter_MinAgeRange, $filter_MaxAgeRange, $default_MinAgeRange, $default_MaxAgeRange, $filter_ViolationStat, $filter_FromDateRange, $filter_ToDateRange){
                                    if($filter_SchoolName != 0 OR !empty($filter_SchoolName)){
                                        $vrQuery->where('students_tbl.School_Name', '=', $filter_SchoolName);
                                    }
                                    if($filter_Programs != 0 OR !empty($filter_Programs)){
                                        $vrQuery->where('students_tbl.Course', '=', $filter_Programs);
                                    }
                                    if($filter_YearLevels != 0 OR !empty($filter_YearLevels)){
                                        $vrQuery->where('students_tbl.YearLevel', '=', $filter_YearLevels);
                                    }
                                    if($filter_Genders != 0 OR !empty($filter_Genders)){
                                        $lower_vr_gender = Str::lower($filter_Genders);
                                        $vrQuery->where('students_tbl.Gender', '=', $lower_vr_gender);
                                    }
                                    if($filter_MinAgeRange != $default_MinAgeRange OR $filter_MaxAgeRange != $default_MaxAgeRange){
                                        $vrQuery->whereBetween('students_tbl.Age', [$filter_MinAgeRange, $filter_MaxAgeRange]);
                                    }
                                    if($filter_ViolationStat != 0 OR !empty($filter_ViolationStat)){
                                        $lower_filter_ViolationStat = Str::lower($filter_ViolationStat);
                                        $vrQuery->where('violations_tbl.violation_status', '=', $lower_filter_ViolationStat);
                                    }
                                    if($filter_FromDateRange != 0 OR !empty($filter_FromDateRange) AND $filter_ToDateRange != 0 OR !empty($filter_ToDateRange)){
                                        $vrQuery->whereBetween('violations_tbl.recorded_at', [$filter_FromDateRange, $filter_ToDateRange]);
                                    }
                                })
                                ->orderBy('violations_tbl.'.$orderBy_filterVal, $orderByRange_filterVal)
                                ->get();
            }
            // Total Records
            $total_query_violation_records = count($query_violation_records);
            if($total_query_violation_records > 0){
                if($total_query_violation_records > 1){
                    $txt_TotalQueryRecords = ''.$total_query_violation_records . ' Records Found.';
                }else{
                    $txt_TotalQueryRecords = ''.$total_query_violation_records . ' Record Found.';
                }
                $disablePrintButton = '';
            }else{
                $txt_TotalQueryRecords = 'No Records Found.';
                $disablePrintButton = 'disabled';
            }
        
        // Generate PDF
            $pdf = \App::make('dompdf.wrapper');
            // $pdf->loadHTML($output);
            $pdf = PDF::loadView('reports/violation_records_pdf', compact('query_violation_records', 'now_timestamp', 'query_respo_user', 'txt_SchoolNames', 'txt_Programs', 'txt_YearLevels', 'txt_Gender', 'txt_AgeRange', 'txt_ViolationStatus', 'txt_DateRange', 'txt_TotalRecords', 'filter_SearchInput', 'filter_OrderBy', 'filter_OrderByRange', 'txt_TotalQueryRecords', 'filter_TotalRecords'));
            $pdf->setPaper('A4');
            $pdf->getDomPDF()->set_option("enable_php", true);
            return $pdf->stream('reports/violation_records_pdf.pdf');
    }

    public function report_violations_records(Request $request){
        // now timestamp
            $now_timestamp        = now();
        // get all request
            $filter_SchoolName    = $request->get('violationRecFltr_schools');
            $filter_Programs      = $request->get('violationRecFltr_programs');
            $filter_YearLevels    = $request->get('violationRecFltr_yearLvls');
            $filter_Genders       = $request->get('violationRecFltr_genders');
            $filter_MinAgeRange   = $request->get('violationRecFltr_minAge');
            $filter_MaxAgeRange   = $request->get('violationRecFltr_maxAge');
            $filter_ViolationStat = $request->get('violationRecFltr_violationStat');
            $filter_FromDateRange = $request->get('violationRecFltr_hidden_dateRangeFrom');
            $filter_ToDateRange   = $request->get('violationRecFltr_hidden_dateRangeTo');

        // try
            echo 'School Name: ' . $filter_SchoolName . ' <br>';
            echo 'Programs: ' . $filter_Programs . ' <br>';
            echo 'Year levels: ' . $filter_YearLevels . ' <br>';
            echo 'Genders: ' . $filter_Genders . ' <br>';
            echo 'Min Age Range: ' . $filter_MinAgeRange . ' <br>';
            echo 'Max Age Range: ' . $filter_MaxAgeRange . ' <br>';
            echo 'Violation Status: ' . $filter_ViolationStat . ' <br>';
            echo 'From: ' . $filter_FromDateRange . ' <br>';
            echo 'To: ' . $filter_ToDateRange . ' <br>';
    }

    // REPORT ~ VIOLATOR's MODULE
    public function violator_offenses_report_confirmation_modal(Request $request){
        // get all request
        $sel_Student_Number = $request->get('sel_Student_Number');
        
        // custom values
        $output = '';
        $sq = "'";

        // get selected student's information
        $query_selViolator_info = Students::where('Student_Number', $sel_Student_Number)->first();

        // check if student have recorded violations
        $studHas_Recorded_offenses = Violations::where('stud_num', $sel_Student_Number)->count();

        // conditions
        if($studHas_Recorded_offenses > 0){
            // check if student has (Cleared, Uncleared Offenses, and corresponding sanctions)
            $count_all_offenses = Violations::where('stud_num', $sel_Student_Number)->sum('offense_count');
            if($count_all_offenses > 0){
                if($count_all_offenses > 1){
                    $caF_s = 's';
                }else{
                    $caF_s = '';
                }
            }else{
                $caF_s = '';
            }
            $studHas_Uncleared_offenses = Violations::where('stud_num', $sel_Student_Number)->where('violation_status', '!=', 'cleared')->count();
            $studHas_Cleared_offenses = Violations::where('stud_num', $sel_Student_Number)->where('violation_status', '=', 'cleared')->count();
            $studHas_Corresponding_sanctions = Violations::where('stud_num', $sel_Student_Number)->where('has_sanction', '=', 1)->count();
            
            // student's image handler
            if(!is_null($query_selViolator_info->Student_Image) OR !empty($query_selViolator_info->Student_Image)){
                $studImage_file = $query_selViolator_info->Student_Image;
                if($studHas_Recorded_offenses == $studHas_Cleared_offenses){
                    $studImge_borderClass = 'display_violator_image3';
                }else{
                    $studImge_borderClass = 'display_violator_image2';
                }
            }else{
                if($studHas_Recorded_offenses == $studHas_Cleared_offenses){
                    $studImage_file = 'default_cleared_student_img.jpg';
                    $studImge_borderClass = 'display_violator_image3';
                }else{
                    $studImage_file = 'default_student_img.jpg';
                    $studImge_borderClass = 'display_violator_image2';
                }
            }

            // student's gender handler (Mr. / Ms.)
            if(!is_null($query_selViolator_info->Gender)){
                if($query_selViolator_info->Gender === 'Male'){
                    $Vmr_ms = 'Mr.';
                }elseif($query_selViolator_info->Gender === 'Female'){
                    $Vmr_ms = 'Ms.';
                }else{
                    $Vmr_ms = 'Mr./Ms.';
                }
            }else{
                $Vmr_ms = 'Mr./Ms.';
            }

            // output
            $output .= '
            <div class="cust_modal_body_gray">
                <div class="row mb-2">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <span class="cust_status_title">Violator'.$sq.'s Information: </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 m-0">
                        <div class="violators_cards_div d-flex justify-content-start align-items-center">
                            <div class="display_user_image_div text-center">
                                <img class="'.$studImge_borderClass . ' shadow-sm" src="'.asset('storage/svms/sdca_images/registered_students_imgs/'.$studImage_file).'" alt="violator'.$sq.'s image">
                            </div>
                            <div class="information_div">
                                <span class="li_info_title">'.$query_selViolator_info->First_Name . ' ' . $query_selViolator_info->Middle_Name . ' ' . $query_selViolator_info->Last_Name.'</span>
                                <span class="li_info_subtitle2"><span class="font-weight-bold">'.$query_selViolator_info->Student_Number.' </span> | ' . $query_selViolator_info->School_Name . ' | ' . $query_selViolator_info->Course . ' ~ ' . $query_selViolator_info->YearLevel.'-Y | ' . ucwords($query_selViolator_info->Gender).'</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body border-0 pb-0">
                <div class="card-body lightBlue_cardBody shadow-none">
                    <span class="lightBlue_cardBody_blueTitle">Report Content:</span>
                    <span class="lightBlue_cardBody_notice">The system will generate a report of all Recorded Offenses made by ' . $Vmr_ms . ' '.$query_selViolator_info->First_Name . ' ' . $query_selViolator_info->Middle_Name . ' ' . $query_selViolator_info->Last_Name . ' and it'.$sq.'s Corresponding Sanctions. </span>
                </div>
                <div class="card-body lightBlue_cardBody shadow-none mt-2">
                    <span class="lightBlue_cardBody_blueTitle">Offenses Details:</span>
                    ';
                    // offenses details
                    // cleared offenses
                    if($studHas_Cleared_offenses > 0){
                        // sum of all cleared offenses
                        $sumAll_Cleared_offenses = Violations::where('stud_num', $sel_Student_Number)->where('violation_status', '=', 'cleared')->sum('offense_count');
                        if($sumAll_Cleared_offenses > 1){
                            $caCF_s = 's';
                        }else{
                            $caCF_s = '';
                        }
                        $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> '.$sumAll_Cleared_offenses . ' Cleared Offense'.$caCF_s.' </span>';
                    }
                    // uncleared offenses
                    if($studHas_Uncleared_offenses > 0){
                        // sum of all cleared offenses
                        $sumAll_Uncleared_offenses = Violations::where('stud_num', $sel_Student_Number)->where('violation_status', '!=', 'cleared')->sum('offense_count');
                        if($sumAll_Uncleared_offenses > 1){
                            $caUF_s = 's';
                        }else{
                            $caUF_s = '';
                        }
                        $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-exclamation-circle text_svms_red mr-1" aria-hidden="true"></i> '.$sumAll_Uncleared_offenses . ' Uncleared Offense'.$caUF_s.' </span>';
                    }
                    // total offenses count
                    if($count_all_offenses > 0){
                        $output .= '<span class="lightBlue_cardBody_notice"><i class="fa  fa-list-ul text_svms_blue mr-1" aria-hidden="true"></i> Total of ' . $count_all_offenses . ' Offense'.$caF_s.' </span>';
                    }else{
                        $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> No Offenses Found. </span>';
                    }

                    $output .= '<span class="lightBlue_cardBody_blueTitle mt-3">Sanctions Details:</span>';
                    // sanctions details
                    if($studHas_Corresponding_sanctions > 0){
                        // sum all sanctions
                        $sumAll_Sanctions = Violations::where('stud_num', $sel_Student_Number)->sum('has_sanct_count');
                        // sum of all completed sanctions
                        $sumAll_Completed_sanctions = Sanctions::where('stud_num', $sel_Student_Number)->where('sanct_status', '=', 'completed')->count();
                        if($sumAll_Completed_sanctions > 0){
                            if($sumAll_Completed_sanctions > 1){
                                $caCS_s = 's';
                            }else{
                                $caCS_s = '';
                            }
                            $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> '.$sumAll_Completed_sanctions . ' Completed Sanction'.$caCS_s.' </span>';
                        }
                        // sum of all not completed sanctions
                        $sumAll_NotCompleted_sanctions = Sanctions::where('stud_num', $sel_Student_Number)->where('sanct_status', '!=', 'completed')->count();
                        if($sumAll_NotCompleted_sanctions > 0){
                            if($sumAll_NotCompleted_sanctions > 1){
                                $caNCS_s = 's';
                            }else{
                                $caNCS_s = '';
                            }
                            $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-exclamation-circle text_svms_red mr-1" aria-hidden="true"></i> '.$sumAll_NotCompleted_sanctions . ' Not Completed Sanction'.$caNCS_s.' </span>';
                        }
                        // total sum of all corresponding sanctions
                        if($sumAll_Sanctions > 1){
                            $caS_s = 's';
                        }else{
                            $caS_s = '';
                        }
                        $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-list-ul text_svms_blue mr-1" aria-hidden="true"></i> Total of ' . $sumAll_Sanctions . ' Corresponding Sanction'.$caS_s.' </span>';
                    }else{
                        $output .= '<span class="lightRed_cardBody_notice"><i class="fa fa-exclamation-circle text_svms_red mr-1" aria-hidden="true"></i> There are No Corresponding Sanctions found!</span>';
                    }
                    $output .= '
                </div>
            </div>
            ';
            if($studHas_Corresponding_sanctions > 0){
                $output .= '
                <form id="form_confirmGenerateViolatorOffensesReport" target="_blank" action="'.route('violation_records.violator_records_pdf').'" method="POST" enctype="multipart/form-data">
                    <div class="modal-footer border-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">

                        <input type="hidden" name="sel_Student_Number" value="'.$sel_Student_Number.'">

                        <div class="btn-group" role="group" aria-label="Generate Violator Records Report Action Buttons">
                            <button id="cancel_GenerateViolatorOffensesReport_btn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="process_GenerateViolatorOffensesReport_btn" type="submit" class="btn btn-round btn-success btn_show_icon m-0">Generate Report <i class="nc-icon nc-single-copy-04 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
                ';
            }else{
                $output .= '
                <div class="modal-footer border-0 pb-0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card-body lightRed_cardBody shadow-none">
                                <span class="lightRed_cardBody_notice"><i class="fa fa-exclamation-circle text_svms_red mr-1" aria-hidden="true"></i> There are No Corresponding Sanctions found for all ' . $count_all_offenses . ' Recorded Offense'.$caF_s . '. Please close this modal and assign Sanctions to Generate a report.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <button id="cancel_GenerateViolatorOffensesReport_btn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal">OK <i class="fa fa-thumbs-o-up btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
                ';
            }
            $output .= '
        ';
        }else{
            
        }

        echo $output;
    }
    // process ~ generate violator's records report - PDF
    public function violator_records_pdf(Request $request){
        // now timestamp
        $now_timestamp      = now();

        // get all request
        $sel_Student_Number = $request->get('sel_Student_Number');
        $respo_user_id      = $request->get('respo_user_id');
        $respo_user_lname   = $request->get('respo_user_lname');
        $respo_user_fname   = $request->get('respo_user_fname');  

        // query responsible user's info
        $query_respo_user = Users::select('user_role','user_lname', 'user_fname')->where('id', $respo_user_id)->first();

        // query student's information
        $query_selViolator_info = Students::where('Student_Number', $sel_Student_Number)->first();

        // query all recorded violations
        $query_selViolator_Offenses = Violations::where('stud_num', $sel_Student_Number)->orderBy('recorded_at', 'DESC')->get();

        // counts
        $countAll_Uncleared_offenses = Violations::where('stud_num', $sel_Student_Number)->where('violation_status', '!=', 'cleared')->sum('offense_count');
        $countAll_Cleared_offenses = Violations::where('stud_num', $sel_Student_Number)->where('violation_status', '=', 'cleared')->sum('offense_count');
        $countTotal_offenses = Violations::where('stud_num', $sel_Student_Number)->sum('offense_count');

        // Generate PDF
        $pdf = \App::make('dompdf.wrapper');
        // $pdf->loadHTML($output);
        $pdf = PDF::loadView('reports/violator_records_pdf', compact('now_timestamp', 'query_respo_user', 'query_selViolator_info', 'query_selViolator_Offenses', 'countAll_Uncleared_offenses', 'countAll_Cleared_offenses', 'countTotal_offenses'));
        $pdf->setPaper('A4');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('reports/violator_records_pdf.pdf');
    }

    // NOTIFY VIOLATOR
    public function notify_violator_confirmation_modal(Request $request){
        // get all request
        $sel_Student_Number = $request->get('sel_Student_Number');
        
        // custom values
        $output = '';
        $sq = "'";

        // get selected student's information
        $query_selViolator_info = Students::where('Student_Number', $sel_Student_Number)->first();

        // check if student have recorded violations
        $studHas_Recorded_offenses = Violations::where('stud_num', $sel_Student_Number)->count();
        // check if all violations has sanctions
        $allOffenses_hasSanctions = Violations::where('stud_num', $sel_Student_Number)->where('has_sanction', '=', 1)->count();
        // check uncleared offenses
        $studHas_Uncleared_offenses = Violations::where('stud_num', $sel_Student_Number)->where('violation_status', '!=', 'cleared')->count();
        // check cleared offenses
        $studHas_Cleared_offenses = Violations::where('stud_num', $sel_Student_Number)->where('violation_status', '=', 'cleared')->count();
        // check corresponding sanctions
        $studHas_Corresponding_sanctions = Violations::where('stud_num', $sel_Student_Number)->where('has_sanction', '=', 1)->count();

        // student's image handler
        if(!is_null($query_selViolator_info->Student_Image) OR !empty($query_selViolator_info->Student_Image)){
            $studImage_file = $query_selViolator_info->Student_Image;
            if($studHas_Recorded_offenses == $studHas_Cleared_offenses){
                $studImge_borderClass = 'display_violator_image3';
            }else{
                $studImge_borderClass = 'display_violator_image2';
            }
        }else{
            if($studHas_Recorded_offenses == $studHas_Cleared_offenses){
                $studImage_file = 'default_cleared_student_img.jpg';
                $studImge_borderClass = 'display_violator_image3';
            }else{
                $studImage_file = 'default_student_img.jpg';
                $studImge_borderClass = 'display_violator_image2';
            }
        }

        // student's gender handler (Mr. / Ms.)
        if(!is_null($query_selViolator_info->Gender)){
            if($query_selViolator_info->Gender === 'Male'){
                $Vmr_ms = 'Mr.';
                $Vhis_her = 'his';
                $Vhe_she = 'he';
                $Vhim_her = 'him';
            }elseif($query_selViolator_info->Gender === 'Female'){
                $Vmr_ms = 'Ms.';
                $Vhis_her = 'her';
                $Vhe_she = 'she';
                $Vhim_her = 'her';
            }else{
                $Vmr_ms = 'Mr./Ms.';
                $Vhis_her = 'his/her';
                $Vhe_she = 'he/she';
                $Vhim_her = 'him/her';
            }
        }else{
            $Vmr_ms = 'Mr./Ms.';
            $Vhis_her = 'his/her';
            $Vhe_she = 'he/she';
            $Vhim_her = 'him/her';
        }

        // output
        $output .= '
            <div class="cust_modal_body_gray">
                <div class="row mb-2">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <span class="cust_status_title">Violator'.$sq.'s Information: </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 m-0">
                        <div class="violators_cards_div d-flex justify-content-start align-items-center">
                            <div class="display_user_image_div text-center">
                                <img class="'.$studImge_borderClass . ' shadow-sm" src="'.asset('storage/svms/sdca_images/registered_students_imgs/'.$studImage_file).'" alt="violator'.$sq.'s image">
                            </div>
                            <div class="information_div">
                                <span class="li_info_title">'.$query_selViolator_info->First_Name . ' ' . $query_selViolator_info->Middle_Name . ' ' . $query_selViolator_info->Last_Name.'</span>
                                <span class="li_info_subtitle2"><span class="font-weight-bold">'.$query_selViolator_info->Student_Number.' </span> | ' . $query_selViolator_info->School_Name . ' | ' . $query_selViolator_info->Course . ' ~ ' . $query_selViolator_info->YearLevel.'-Y | ' . ucwords($query_selViolator_info->Gender).'</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body border-0 pb-0">
        ';

        // conditions
        // if there are violations found
        if($studHas_Recorded_offenses > 0){
            // sum of all offense_count with sanctions
            $sumAll_OffenseCounts = Violations::where('stud_num', $sel_Student_Number)->sum('offense_count');
            if($sumAll_OffenseCounts > 0){
                if($sumAll_OffenseCounts > 1){
                    $SAO_s = 's';
                }else{
                    $SAO_s = '';
                }
            }else{
                $SAO_s = '';
            }
            // sum of all offense_count with sanctions
            $sumAll_OffenseCounts_noSanct = Violations::where('stud_num', $sel_Student_Number)->where('has_sanction', '!=', 1)->sum('offense_count');
            if($sumAll_OffenseCounts_noSanct > 0){
                if($sumAll_OffenseCounts_noSanct > 1){
                    $SAO_nS_s = 's';
                }else{
                    $SAO_nS_s = '';
                }
            }else{
                $SAO_nS_s = '';
            }

            // if all offenses has sanctions
            if($studHas_Recorded_offenses == $allOffenses_hasSanctions){
                $output .= '
                <form id="form_confirmNotifyViolator" action="'.route('violation_records.process_send_notification_to_violator').'" method="POST" enctype="multipart/form-data">
                ';
                // if the violator has Email
                if(!is_null($query_selViolator_info->Email) OR !empty($query_selViolator_info->Email)){
                    $disable_btn = '';
                    $txt_noticetitle = 'Email Content:';
                    $txt_noticeSubTitle = 'The system will send ' . $Vmr_ms . ' '.$query_selViolator_info->First_Name . ' ' . $query_selViolator_info->Middle_Name . ' ' . $query_selViolator_info->Last_Name . ' an email notification of all the ('.$sumAll_OffenseCounts . ' Offense'.$SAO_s.') ' . $Vhe_she . ' has committed and it'.$sq.'s corresponding Sanctions, thru ' . $Vhis_her . ' registered email address:';
                    $emailInput_value = $query_selViolator_info->Email;
                    $emailInput_placeholder = $query_selViolator_info->Email;
                // if the violator has no Email
                }else{
                    $disable_btn = 'disabled';
                    $txt_noticetitle = 'Type Recepient'.$sq.'s Email:';
                    $txt_noticeSubTitle = 'Type '.$query_selViolator_info->First_Name . ' ' . $query_selViolator_info->Middle_Name . ' ' . $query_selViolator_info->Last_Name.''.$sq.'s Email Address to notify ' . $Vhim_her . ' of the ('.$sumAll_OffenseCounts . ' Offense'.$SAO_s.') ' . $Vhe_she . ' has committed and it'.$sq.'s corresponding Sanctions.';
                    $emailInput_value = '';
                    $emailInput_placeholder = 'Type Recepient'.$sq.'s Email Address...';
                }
                $output .= '
                    <div class="modal-body border-0 p-0">
                        <div class="card-body lightBlue_cardBody shadow-none mt-2">
                            <span class="lightBlue_cardBody_blueTitle">Offenses Details:</span>
                            ';
                            // offenses details
                            // cleared offenses
                            if($studHas_Cleared_offenses > 0){
                                // sum of all cleared offenses
                                $sumAll_Cleared_offenses = Violations::where('stud_num', $sel_Student_Number)->where('violation_status', '=', 'cleared')->sum('offense_count');
                                if($sumAll_Cleared_offenses > 1){
                                    $caCF_s = 's';
                                }else{
                                    $caCF_s = '';
                                }
                                $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> '.$sumAll_Cleared_offenses . ' Cleared Offense'.$caCF_s.' </span>';
                            }
                            // uncleared offenses
                            if($studHas_Uncleared_offenses > 0){
                                // sum of all cleared offenses
                                $sumAll_Uncleared_offenses = Violations::where('stud_num', $sel_Student_Number)->where('violation_status', '!=', 'cleared')->sum('offense_count');
                                if($sumAll_Uncleared_offenses > 1){
                                    $caUF_s = 's';
                                }else{
                                    $caUF_s = '';
                                }
                                $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-exclamation-circle text_svms_red mr-1" aria-hidden="true"></i> '.$sumAll_Uncleared_offenses . ' Uncleared Offense'.$caUF_s.' </span>';
                            }
                            // total offenses count
                            if($sumAll_OffenseCounts > 0){
                                $output .= '<span class="lightBlue_cardBody_notice"><i class="fa  fa-list-ul text_svms_blue mr-1" aria-hidden="true"></i> Total of ' . $sumAll_OffenseCounts . ' Offense'.$SAO_s.' </span>';
                            }else{
                                $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> No Offenses Found. </span>';
                            }

                            $output .= '<span class="lightBlue_cardBody_blueTitle mt-3">Sanctions Details:</span>';
                            // sanctions details
                            if($studHas_Corresponding_sanctions > 0){
                                // sum all sanctions
                                $sumAll_Sanctions = Violations::where('stud_num', $sel_Student_Number)->sum('has_sanct_count');
                                // sum of all completed sanctions
                                $sumAll_Completed_sanctions = Sanctions::where('stud_num', $sel_Student_Number)->where('sanct_status', '=', 'completed')->count();
                                if($sumAll_Completed_sanctions > 0){
                                    if($sumAll_Completed_sanctions > 1){
                                        $caCS_s = 's';
                                    }else{
                                        $caCS_s = '';
                                    }
                                    $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> '.$sumAll_Completed_sanctions . ' Completed Sanction'.$caCS_s.' </span>';
                                }
                                // sum of all not completed sanctions
                                $sumAll_NotCompleted_sanctions = Sanctions::where('stud_num', $sel_Student_Number)->where('sanct_status', '!=', 'completed')->count();
                                if($sumAll_NotCompleted_sanctions > 0){
                                    if($sumAll_NotCompleted_sanctions > 1){
                                        $caNCS_s = 's';
                                    }else{
                                        $caNCS_s = '';
                                    }
                                    $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-exclamation-circle text_svms_red mr-1" aria-hidden="true"></i> '.$sumAll_NotCompleted_sanctions . ' Not Completed Sanction'.$caNCS_s.' </span>';
                                }
                                // total sum of all corresponding sanctions
                                if($sumAll_Sanctions > 1){
                                    $caS_s = 's';
                                }else{
                                    $caS_s = '';
                                }
                                $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-list-ul text_svms_blue mr-1" aria-hidden="true"></i> Total of ' . $sumAll_Sanctions . ' Corresponding Sanction'.$caS_s.' </span>';
                            }else{
                                $output .= '<span class="lightRed_cardBody_notice"><i class="fa fa-exclamation-circle text_svms_red mr-1" aria-hidden="true"></i> There are No Corresponding Sanctions found!</span>';
                            }
                            $output .= '
                        </div>
                        <div class="card-body lightBlue_cardBody shadow-none mt-3">
                            <span class="lightBlue_cardBody_blueTitle">'.$txt_noticetitle . ' </span>
                            <span class="lightBlue_cardBody_notice"><i class="fa fa-info-circle text_svms_blue mr-1" aria-hidden="true"></i> ' . $txt_noticeSubTitle . '  </span>
                            <div class="input-group mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="nc-icon nc-email-85"></i>
                                    </span>
                                </div>
                                <input id="violator_email" name="violator_email" type="email" value="'.$emailInput_value.'" class="form-control" placeholder="'.$emailInput_placeholder.'" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">

                        <input type="hidden" name="sel_Student_Number" value="'.$sel_Student_Number.'">

                        <div class="btn-group" role="group" aria-label="Notify Violator Action Buttons">
                            <button id="cancel_notifyViolator_btn" type="button" class="btn btn-round btn-success btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="process_notifyViolator_btn" type="submit" class="btn btn-round btn_svms_blue btn_show_icon m-0" ' . $disable_btn . '>Send Notification <i class="nc-icon nc-send btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
                ';
            // if there are offenses that has no sanctions
            }else{
                $output .= '
                    <div class="modal-body border-0 p-0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card-body lightRed_cardBody shadow-none">
                                    <span class="lightRed_cardBody_notice"><i class="fa fa-exclamation-circle text_svms_red mr-1" aria-hidden="true"></i> There are ' . $sumAll_OffenseCounts_noSanct . ' Offense'.$SAO_nS_s . ' that has no corresponding sanctions, please close this modal and register sanctions to said offenses first to notify student. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal">OK <i class="fa fa-thumbs-o-up btn_icon_show_right" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                ';
            }
        // if there are no violations found
        }else{
            $output .= '
            <div class="modal-body border-0 p-0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card-body lightGreen_cardBody shadow-none">
                            <span class="lightGreen_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> There are no Recorded Violations Found for ' . $Vmr_ms . ' '.$query_selViolator_info->First_Name . ' ' . $query_selViolator_info->Middle_Name . ' ' . $query_selViolator_info->Last_Name . '. ' . ucwords($Vhe_she) . ' is cleared for Clearance.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <button type="button" class="btn btn-round btn-success btn_show_icon m-0" data-dismiss="modal">OK <i class="fa fa-thumbs-o-up btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
            ';
        }

        $output .= '</div>';
        echo $output;
        
    }
    // process sending notification to violator
    public function process_send_notification_to_violator(Request $request){
        // get all request
        $sel_Student_Number = $request->get('sel_Student_Number');
        $sel_Student_Email  = $request->get('violator_email');

        $respo_user_id      = $request->get('respo_user_id');
        $respo_user_lname   = $request->get('respo_user_lname');
        $respo_user_fname   = $request->get('respo_user_fname');  

        // query violator's name
        $query_violatorInfo = Students::select('First_Name', 'Middle_Name', 'Last_Name')->where('Student_Number', '=', $sel_Student_Number)->first();
        $violator_Fname = $query_violatorInfo->First_Name;
        $violator_Mname = $query_violatorInfo->Middle_Name;
        $violator_Lname = $query_violatorInfo->Last_Name;

        // query responsible user's info
        $query_respo_user = Users::select('user_role','user_lname', 'user_fname')->where('id', $respo_user_id)->first();
        
        // custom values
        $now_timestamp = now();
        $output = '';
        $sq = "'";

        // if violator has Email
        if(!is_null($sel_Student_Email) OR !empty($sel_Student_Email)){
            // send email
            $details = [
                'svms_logo'          => "storage/svms/logos/svms_logo_text.png",
                'title'              => 'Student Offenses Record',
                'sel_Student_Number' => $sel_Student_Number
            ];
            \Mail::to('mfodesierto2@gmail.com')->send(new \App\Mail\NotifyViolatorMail($details));

            return back()->withSuccessStatus('Email Notification has been successfully sent to ' . $violator_Fname . ' ' . $violator_Mname . ' ' .$violator_Lname.'.');
        }else{
            return back()->withFailedStatus('There has been a problem sending an email notification, please try again.');
        }
    }


}