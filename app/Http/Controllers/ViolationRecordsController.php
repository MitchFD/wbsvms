<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Useractivites;
use App\Models\Userroles;
use App\Models\Users;

class ViolationRecordsController extends Controller
{
    public function index(){
        return view('violation_records.index');
    }

    // filter violation records table
    public function vr_table_filter(Request $request){
        if($request->ajax()){
            // custom var
            $vr_output = '';
            $vr_paginate = '';
            $vr_total_matched_results = '';
            $vr_total_filtered_result = '';
            // get all request
            $vr_search = $request->get('vr_search');
            $vr_schools = $request->get('vr_schools');
            $vr_programs = $request->get('vr_programs');
            $vr_yearlvls = $request->get('vr_yearlvls');
            $vr_genders = $request->get('vr_genders');
            $vr_minAgeRange = $request->get('vr_minAgeRange');
            $vr_maxAgeRange = $request->get('vr_maxAgeRange');
            $vr_status = $request->get('vr_status');
            $vr_rangefrom = $request->get('vr_rangefrom');
            $vr_rangeTo = $request->get('vr_rangeTo');
            $df_minAgeRange = $request->get('df_minAgeRange');
            $df_maxAgeRange = $request->get('df_maxAgeRange');
            // customized requests
            $lower_vr_gender = Str::lower($vr_genders);
            $lower_vr_status = Str::lower($vr_status);

            if($vr_search != ''){
                $fltr_VR_tbl = DB::table('violations_tbl')
                                        ->join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                                        ->select('violations_tbl.*', 'students_tbl.Student_Number', 'students_tbl.First_Name', 'students_tbl.Middle_Name', 'students_tbl.Last_Name', 'students_tbl.Gender', 'students_tbl.Age', 'students_tbl.School_Name', 'students_tbl.Course', 'students_tbl.YearLevel', 'students_tbl.Student_Image')
                                        ->where(function($vrQuery) use ($vr_search) {
                                            return $vrQuery->orWhere('students_tbl.Student_Number', 'like', '%'.$vr_search.'%')
                                                        ->orWhere('students_tbl.First_Name', 'like', '%'.$vr_search.'%')
                                                        ->orWhere('students_tbl.Middle_Name', 'like', '%'.$vr_search.'%')
                                                        ->orWhere('students_tbl.Last_Name', 'like', '%'.$vr_search.'%')
                                                        ->orWhere('violations_tbl.stud_num', 'like', '%'.$vr_search.'%')
                                                        ->orWhere('students_tbl.Gender', 'like', '%'.$vr_search.'%')
                                                        ->orWhere('students_tbl.School_Name', 'like', '%'.$vr_search.'%')
                                                        ->orWhere('students_tbl.YearLevel', 'like', '%'.$vr_search.'%')
                                                        ->orWhere('students_tbl.Course', 'like', '%'.$vr_search.'%');
                                        })
                                        ->where(function($vrQuery) use ($vr_schools, $vr_programs, $vr_yearlvls, $vr_genders, $lower_vr_gender, $vr_minAgeRange, $vr_maxAgeRange, $df_minAgeRange, $df_maxAgeRange, $vr_status, $lower_vr_status, $vr_rangefrom, $vr_rangeTo){
                                            if($vr_schools != 0 OR !empty($vr_schools)){
                                                return $vrQuery->where('students_tbl.School_Name', 'like', '%'.$vr_schools.'%');
                                            }
                                            if($vr_programs != 0 OR !empty($vr_programs)){
                                                return $vrQuery->where('students_tbl.Course', 'like', '%'.$vr_programs.'%');
                                            }
                                            if($vr_yearlvls != 0 OR !empty($vr_yearlvls)){
                                                return $vrQuery->where('students_tbl.YearLevel', 'like', '%'.$vr_yearlvls.'%');
                                            }
                                            if($vr_genders != 0 OR !empty($vr_genders)){
                                                return $vrQuery->where('students_tbl.Gender', '=', $lower_vr_gender);
                                            }
                                            if($vr_minAgeRange != $df_minAgeRange OR $vr_maxAgeRange != $df_maxAgeRange){
                                                return $vrQuery->whereBetween('students_tbl.Age', [$vr_minAgeRange, $vr_maxAgeRange]);
                                            }
                                            if($vr_status != 0 OR !empty($vr_status)){
                                                return $vrQuery->where('violations_tbl.violation_status', '=', $lower_vr_status);
                                            }
                                            if($vr_rangefrom != 0 OR !empty($vr_rangefrom) AND $vr_rangeTo != 0 OR !empty($vr_rangeTo)){
                                                return $vrQuery->whereBetween('violations_tbl.created_at', [$vr_rangefrom, $vr_rangeTo]);
                                            }
                                        })
                                        ->orderBy('violations_tbl.created_at', 'DESC')
                                        ->paginate(10);
                $matched_result_txt = ' Matched Records';
            }else{
                $fltr_VR_tbl = DB::table('violations_tbl')
                                        ->join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                                        ->select('violations_tbl.*', 'students_tbl.Student_Number', 'students_tbl.First_Name', 'students_tbl.Middle_Name', 'students_tbl.Last_Name', 'students_tbl.Gender', 'students_tbl.Age', 'students_tbl.School_Name', 'students_tbl.Course', 'students_tbl.YearLevel', 'students_tbl.Student_Image')
                                        ->where(function($vrQuery) use ($vr_schools, $vr_programs, $vr_yearlvls, $vr_genders, $lower_vr_gender, $vr_minAgeRange, $vr_maxAgeRange, $df_minAgeRange, $df_maxAgeRange, $vr_status, $lower_vr_status, $vr_rangefrom, $vr_rangeTo){
                                            if($vr_schools != 0 OR !empty($vr_schools)){
                                                return $vrQuery->where('students_tbl.School_Name', 'like', '%'.$vr_schools.'%');
                                            }
                                            if($vr_programs != 0 OR !empty($vr_programs)){
                                                return $vrQuery->where('students_tbl.Course', 'like', '%'.$vr_programs.'%');
                                            }
                                            if($vr_yearlvls != 0 OR !empty($vr_yearlvls)){
                                                return $vrQuery->where('students_tbl.YearLevel', 'like', '%'.$vr_yearlvls.'%');
                                            }
                                            if($vr_genders != 0 OR !empty($vr_genders)){
                                                return $vrQuery->where('students_tbl.Gender', '=', $lower_vr_gender);
                                            }
                                            if($vr_minAgeRange != $df_minAgeRange OR $vr_maxAgeRange != $df_maxAgeRange){
                                                return $vrQuery->whereBetween('students_tbl.Age', [$vr_minAgeRange, $vr_maxAgeRange]);
                                            }
                                            if($vr_status != 0 OR !empty($vr_status)){
                                                return $vrQuery->where('violations_tbl.violation_status', '=', $lower_vr_status);
                                            }
                                            if($vr_rangefrom != 0 OR !empty($vr_rangefrom) AND $vr_rangeTo != 0 OR !empty($vr_rangeTo)){
                                                return $vrQuery->whereBetween('violations_tbl.created_at', [$vr_rangefrom, $vr_rangeTo]);
                                            }
                                        })
                                        ->orderBy('violations_tbl.created_at', 'DESC')
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
                    // year level
                    // if($this_violator->YearLevel === '1'){
                    //     $yearLevel_txt = '1st Year';
                    // }else if($this_violator->YearLevel === '2'){
                    //     $yearLevel_txt = '2nd Year';
                    // }else if($this_violator->YearLevel === '3'){
                    //     $yearLevel_txt = '3rd Year';
                    // }else if($this_violator->YearLevel === '4'){
                    //     $yearLevel_txt = '4th Year';
                    // }else if($this_violator->YearLevel === '5'){
                    //     $yearLevel_txt = '5th Year';
                    // }else{
                    //     $yearLevel_txt = $this_violator->YearLevel . ' Year';
                    // }
                    // course text limit
                    // if($this_violator->Course === 'BS Education'){
                    //     $lim_stud_course = 'BS Educ';
                    // }else if($this_violator->Course === 'BS Psychology'){
                    //     $lim_stud_course = 'BS Psych';
                    // }else if($this_violator->Course === 'BA Communication'){
                    //     $lim_stud_course = 'BA Comm';
                    // }else if($this_violator->Course === 'BS Biology'){
                    //     $lim_stud_course = 'BS Bio';
                    // }else if($this_violator->Course === 'BS Pharmacy'){
                    //     $lim_stud_course = 'BS Pharma';
                    // }else if($this_violator->Course === 'BS Radiologic Technology'){
                    //     $lim_stud_course = 'BS Rad Tech';
                    // }else if($this_violator->Course === 'BS Physical Therapy'){
                    //     $lim_stud_course = 'BS Ph Th';
                    // }else if($this_violator->Course === 'BS Medical Technology'){
                    //     $lim_stud_course = 'BS Med Tech';
                    // }else{
                    //     $lim_stud_course = $this_violator->Course;
                    // }
                    // offense count
                    if($this_violator->offense_count > 1){
                        $oc_s = 's';
                    }else{
                        $oc_s = '';
                    }
                    // violation status classes
                    if($this_violator->violation_status === 'cleared'){
                        $violation_statTxt = ' <span class="text-success font-italic"> ~ Cleared</span>';
                        $badge_stat = 'cust_badge_grn';
                        $img_class = 'display_violator_image3';
                    }else{
                        $violation_statTxt = ' <span class="text_svms_red font-italic"> ~ Not Cleared</span>';
                        $badge_stat = 'cust_badge_red';
                        $img_class = 'display_violator_image2';
                    }
                    $vr_output .= '
                    <tr id="'.$this_violator->Student_Number.'" onclick="viewStudentOffenses(this.id)" class="tr_pointer">
                        <td class="pl12 d-flex justify-content-start align-items-center">
                            <img class="'.$img_class.' shadow-sm" src="'.asset('storage/svms/sdca_images/registered_students_imgs/'.$this_violator->Student_Image.'').'" alt="student'.$sq.'s image">
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
                                <span class="actLogs_content">'.preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', date('F d, Y', strtotime($this_violator->created_at))) . '</span>
                                <span class="actLogs_tdSubTitle sub2">'.preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', date('D', strtotime($this_violator->created_at))) . ' - '.preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', date('g:i A', strtotime($this_violator->created_at))) . '</span>
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
                            <div class="no_data_div d-flex justify-content-center align-items-center text-center flex-column">
                                <img class="illustration_svg" src="'. asset('storage/svms/illustrations/no_matching_users_found.svg') .'" alt="no matching users found">
                                <span class="font-italic">No Records Found!
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
        }
    }
}
