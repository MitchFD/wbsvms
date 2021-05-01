<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Students;
use App\Models\Violations;
use App\Models\Sanctions;
use App\Models\Users;
use App\Models\Useractivites;
use Illuminate\Mail\Mailable;

class ViolationRecordsController extends Controller
{
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
                                <span class="font-italic font-weight-bold">No Records Found!
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
        // get violator's info from students_tbl and violations_tbl
        $violator_info = Students::where('Student_Number', $violator_id)->first();
        $offenses_count = Violations::where('stud_num', $violator_id)->count();
        return view('violation_records.violator')->with(compact('violator_info', 'offenses_count'));
    }

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
                    <form id="form_addSanctions" action="'.route('violation_records.submit_sanction_form').'" class="form" enctype="multipart/form-data" method="POST" onsubmit="submit_addSanctionsBtn.disabled = true; return true;">
                        <div class="modal-body pb-0">
                            <div class="card-body lightGreen_cardBody mb-2">
                                <span class="lightGreen_cardBody_greenTitle mb-1">Sanctions:</span>
                                <div class="input-group mb-2">
                                    <input type="text" id="addSanctions_input" name="sanctions[]" class="form-control input_grpInpt3" placeholder="Type Sanction" aria-label="Type Sanction" aria-describedby="add-sanctions-input">
                                    <div class="input-group-append">
                                        <button class="btn btn-success m-0" id="btn_addAnother_input" type="button" disabled><i class="nc-icon nc-simple-add font-weight-bold" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="addedInputFields_div">

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
                                <button type="button" class="btn btn-round btn_svms_red btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
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
        // get selected violatin's date recorded
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
        // try 
        // echo 'Student Number: ' . $get_sel_stud_num . ' <br />';
        // echo 'violation ID: ' . $get_for_viola_id . ' <br />';
        // echo 'Responsible User ID: ' . $get_respo_user_id . ' <br />';
        // echo 'Responsible Name: ' . $get_respo_user_fname . ' ' . $get_respo_user_lname .' <br />';
        // $count_sanctions = count($get_sanctions);
        // echo 'Number of Sanctions: ' . $count_sanctions . ' <br />';
        // $x = 1;
        // echo 'Sanctions: <br />';
        // foreach($get_sanctions as $sanction){
        //     echo $x++ . ' ' . $sanction . ' <br />';
        // }
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
                    <form id="form_editSanctions" action="'.route('violation_records.update_sanction_form').'" class="form" enctype="multipart/form-data" method="POST" onsubmit="submit_editSanctionsBtn.disabled = true; return true;">
                        ';
                        $get_all_sanctions = Sanctions::select('sanct_id', 'sanct_status', 'sanct_details')
                                                    ->where('stud_num', $sel_stud_num)
                                                    ->where('for_viola_id', $sel_viola_id)
                                                    ->orderBy('created_at', 'desc')
                                                    ->offset(0)
                                                    ->limit($get_viola_has_sanct_count)
                                                    ->get();
                        $count_all_sanctions = count($get_all_sanctions);
                        $output .= '
                        <div class="modal-body pb-0">
                            <ul class="nav nav-pills custom_nav_pills mt-0 mb-3 d-flex justify-content-center" id="editSanctionPills_tabParent" role="tablist">
                                <li class="nav-item mx-1">
                                    <a class="nav-link custom_nav_link_greenv1 active" id="mark_sanctions_tab" data-toggle="pill" href="#mark_sanctions_tabContent" role="tab" aria-controls="mark_sanctions_tabContent" aria-selected="true"><i class="fa fa-check-square-o mr-1" aria-hidden="true"></i> Mark</a>
                                </li>
                                <li class="nav-item mx-1">
                                    <a class="nav-link custom_nav_link_greenv1" id="add_sanctions_tab" data-toggle="pill" href="#add_sanctions_tabContent" role="tab" aria-controls="add_sanctions_tabContent" aria-selected="false"><i class="nc-icon nc-simple-add mr-1" aria-hidden="true"></i> Add</a>
                                </li>
                                <li class="nav-item mx-1">
                                    <a class="nav-link custom_nav_link_greenv1" id="delete_sanctions_tab" data-toggle="pill" href="#delete_sanctions_tabContent" role="tab" aria-controls="delete_sanctions_tabContent" aria-selected="false"><i class="fa fa-trash mr-1" aria-hidden="true"></i> Delete</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="editSanctionPills_tabContent">
                                <div class="tab-pane fade show active" id="mark_sanctions_tabContent" role="tabpanel" aria-labelledby="mark_sanctions_tab">
                                    <div class="card-body lightGreen_cardBody mb-2">
                                        <span class="lightGreen_cardBody_greenTitle mb-1">Mark Sanctions:</span>
                                        ';
                                        foreach($get_all_sanctions as $this_editSanction){
                                            $output .= '
                                            <div class="form-group mx-0 mt-0 mb-1">
                                                <div class="custom-control custom-checkbox align-items-center">
                                                    <input type="checkbox" name="marked_sanctions[]" value="'.$this_editSanction->sanct_id.'" class="custom-control-input cursor_pointer" id="'.$this_editSanction->sanct_id.'_markThisSanct_id" '; if($this_editSanction->sanct_status === 'completed'){ $output .= 'checked'; } $output .='>
                                                    <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="'.$this_editSanction->sanct_id.'_markThisSanct_id">'.$this_editSanction->sanct_details.'</label>
                                                </div>
                                            </div>
                                            ';
                                        }
                                        $output .='
                                        <hr class="hr_grn">
                                        <div class="row mb-1">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                            ';
                                            if($count_all_sanctions > 0){
                                                if($count_all_sanctions > 1){
                                                    $cls_s = 's';
                                                }else{
                                                    $cls_s = '';
                                                }
                                                $output .= '<span class="cust_info_txtwicon4v1 font-weight-bold"><i class="fa fa-list-ul mr-1" aria-hidden="true"></i> ' . $count_all_sanctions . ' Sanction'.$cls_s . ' for the above offenses.</span>';
                                            }
                                            $output .='
                                                <span class="cust_info_txtwicon4v1"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> Mark sanctions that have been completed by ' . $violator_mr_ms . ' ' . $violator_Lname.'.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="add_sanctions_tabContent" role="tabpanel" aria-labelledby="add_sanctions_tab">
                                    <div class="card-body lightGreen_cardBody mb-2">
                                        <span class="lightGreen_cardBody_greenTitle mb-1">Add New Sanctions:</span>
                                        
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="delete_sanctions_tabContent" role="tabpanel" aria-labelledby="delete_sanctions_tab">
                                    <div class="card-body lightGreen_cardBody mb-2">
                                        <span class="lightGreen_cardBody_greenTitle mb-1">Delete Sanctions:</span>
                                            
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
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
                            <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                            <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                            <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-round btn_svms_red btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                                <button id="submit_editSanctionsBtn" type="submit" class="btn btn-round btn-success btn_show_icon m-0" disabled>Save Changes <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </form>
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
            echo 'regsitered sanctions count = ' . $reg_sanct_count . ' <br />';
            echo 'completed sanctions count = ' . $completed_reg_sanct . ' <br />';
            echo 'not completed sanctions count = ' . $not_completed_reg_sanct . ' <br /><br />';
            echo 'marked sanctions count = ' . $marked_sanct_count . ' <br />';

            // if($marked_sanct_count > $completed_reg_sanct){
            // "completed" if there are marked sanctions
            //     foreach($get_marked_sanctions as $updated_sanction){
            //         $update_sanct_statuses = DB::table('sanctions_tbl')
            //         ->where('sanct_id', $updated_sanction)
            //         ->update([
            //             'sanct_status' => $completed_txt,
            //             'completed_at' => $now_timestamp,
            //             'updated_at'   => $now_timestamp
            //         ]);
            //     }
            // }else{
            // "not completed" if there are no marked sanctions

            // }
            

            // "cleared" for selected violation if marked sanctions == registered sanctions






        // mark
        // if(!is_null($get_marked_sanctions) OR !empty($get_marked_sanctions)){
        //     foreach($get_marked_sanctions as $updated_sanction){
        //         $update_sanct_statuses = DB::table('sanctions_tbl')
        //         ->where('sanct_id', $updated_sanction)
        //         ->update([
        //             'sanct_status' => 'completed',
        //             'completed_at' => $now_timestamp,
        //             'updated_at'   => $now_timestamp
        //         ]);
        //     }
        //     if($update_sanct_statuses){
        //         return back()->withSuccessStatus('Sanctions Update was a success.');
        //     }else{
        //         return back()->withFailedStatus('Sanctions Update has Failed! try again later.');
        //     }
        // }
    }

}
