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
    public function index(Request $request){
        if($request->ajax()){
            // custom var
            $output = '';
            $paginate = '';
            $total_matched_results = '';
            $total_filtered_result = '';
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

            if($vr_search != ''){
                $filter_violation_records_table = DB::table('violations_tbl')
                                        ->join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                                        ->select('violations_tbl.*', 'students_tbl.Student_Number', 'students_tbl.First_Name', 'students_tbl.Middle_Name', 'students_tbl.Last_Name', 'students_tbl.Gender', 'students_tbl.Age', 'students_tbl.School_Name', 'students_tbl.Course', 'students_tbl.YearLevel', 'students_tbl.Student_Image')
                                        ->where(function($query) use ($vr_search) {
                                            return $query->orWhere('students_tbl.Student_Number', 'like', '%'.$vr_search.'%')
                                                        ->orWhere('students_tbl.First_Name', 'like', '%'.$vr_search.'%')
                                                        ->orWhere('students_tbl.Middle_Name', 'like', '%'.$vr_search.'%')
                                                        ->orWhere('students_tbl.Last_Name', 'like', '%'.$vr_search.'%')
                                                        ->orWhere('violations_tbl.stud_num', 'like', '%'.$vr_search.'%');
                                        })
                                        ->where(function($query) use ($vr_schools, $vr_programs, $vr_yearlvls, $vr_genders, $vr_minAgeRange, $vr_maxAgeRange, $df_minAgeRange, $df_maxAgeRange, $vr_status, $vr_rangefrom, $vr_rangeTo){
                                            if($vr_schools != 0 OR !empty($vr_schools)){
                                                return $query->where('students_tbl.School_Name', '=', $vr_schools);
                                            }
                                            if($vr_programs != 0 OR !empty($vr_programs)){
                                                return $query->where('students_tbl.Course', '=', $vr_programs);
                                            }
                                            if($vr_yearlvls != 0 OR !empty($vr_yearlvls)){
                                                return $query->where('students_tbl.YearLevel', '=', $vr_yearlvls);
                                            }
                                            if($vr_genders != 0 OR !empty($vr_genders)){
                                                return $query->where('students_tbl.Gender', '=', $vr_genders);
                                            }
                                            if($vr_minAgeRange != $df_minAgeRange AND $vr_maxAgeRange != $df_maxAgeRange){
                                                return $query->whereBetween('students_tbl.Age', [$vr_minAgeRange, $vr_maxAgeRange]);
                                            }
                                            if($vr_status != 0 OR !empty($vr_status)){
                                                return $query->where('violations_tbl.violation_status', '=', $vr_status);
                                            }
                                            if($vr_rangefrom != 0 OR !empty($vr_rangefrom) AND $vr_rangeTo != 0 OR !empty($vr_rangeTo)){
                                                return $query->whereBetween('violations_tbl.created_at', [$vr_rangefrom, $vr_rangeTo]);
                                            }
                                        })
                                        ->orderBy('violations_tbl.created_at', 'DESC')
                                        ->paginate(10);
                $matched_result_txt = ' Matched Records';
            }else{
                $filter_violation_records_table = DB::table('violations_tbl')
                                        ->join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                                        ->select('violations_tbl.*', 'students_tbl.Student_Number', 'students_tbl.First_Name', 'students_tbl.Middle_Name', 'students_tbl.Last_Name', 'students_tbl.Gender', 'students_tbl.Age', 'students_tbl.School_Name', 'students_tbl.Course', 'students_tbl.YearLevel', 'students_tbl.Student_Image')
                                        ->where(function($query) use ($vr_schools, $vr_programs, $vr_yearlvls, $vr_genders, $vr_minAgeRange, $vr_maxAgeRange, $df_minAgeRange, $df_maxAgeRange, $vr_status, $vr_rangefrom, $vr_rangeTo){
                                            if($vr_schools != 0 OR !empty($vr_schools)){
                                                return $query->where('students_tbl.School_Name', '=', $vr_schools);
                                            }
                                            if($vr_programs != 0 OR !empty($vr_programs)){
                                                return $query->where('students_tbl.Course', '=', $vr_programs);
                                            }
                                            if($vr_yearlvls != 0 OR !empty($vr_yearlvls)){
                                                return $query->where('students_tbl.YearLevel', '=', $vr_yearlvls);
                                            }
                                            if($vr_genders != 0 OR !empty($vr_genders)){
                                                return $query->where('students_tbl.Gender', '=', $vr_genders);
                                            }
                                            if($vr_minAgeRange != $df_minAgeRange AND $vr_maxAgeRange != $df_maxAgeRange){
                                                return $query->whereBetween('students_tbl.Age', [$vr_minAgeRange, $vr_maxAgeRange]);
                                            }
                                            if($vr_status != 0 OR !empty($vr_status)){
                                                return $query->where('violations_tbl.violation_status', '=', $vr_status);
                                            }
                                            if($vr_rangefrom != 0 OR !empty($vr_rangefrom) AND $vr_rangeTo != 0 OR !empty($vr_rangeTo)){
                                                return $query->whereBetween('violations_tbl.created_at', [$vr_rangefrom, $vr_rangeTo]);
                                            }
                                        })
                                        ->orderBy('violations_tbl.created_at', 'DESC')
                                        ->paginate(10);
                $matched_result_txt = ' Record';
            }
            // total filtered date
            $count_filtered_result = count($filter_violation_records_table);
            $total_filtered_result = $filter_violation_records_table->total();
            // plural text
            if($total_filtered_result > 0){
                if($total_filtered_result > 1){
                    $s = 's';
                }else{
                    $s = '';
                }
                $total_matched_results = $filter_violation_records_table->firstItem() . ' - ' . $filter_violation_records_table->lastItem() . ' of ' . $total_filtered_result . ' ' . $matched_result_txt.''.$s;
            }else{
                $s = '';
                $total_matched_results = 'No Records Found';
            }
            if($total_filtered_result > 0){
                // custom values
                $sq = "'";
                foreach($filter_violation_records_table as $this_violator){
                    // year level
                    if($this_violator->YearLevel === '1'){
                        $yearLevel_txt = '1st Year';
                    }else if($this_violator->YearLevel === '2'){
                        $yearLevel_txt = '2nd Year';
                    }else if($this_violator->YearLevel === '3'){
                        $yearLevel_txt = '3rd Year';
                    }else if($this_violator->YearLevel === '4'){
                        $yearLevel_txt = '4th Year';
                    }else if($this_violator->YearLevel === '5'){
                        $yearLevel_txt = '5th Year';
                    }else{
                        $yearLevel_txt = $this_violator->YearLevel . ' Year';
                    }
                    // course text limit
                    if($this_violator->Course === 'BS Education'){
                        $lim_stud_course = 'BS Educ';
                    }else if($this_violator->Course === 'BS Psychology'){
                        $lim_stud_course = 'BS Psych';
                    }else if($this_violator->Course === 'BA Communication'){
                        $lim_stud_course = 'BA Comm';
                    }else if($this_violator->Course === 'BS Biology'){
                        $lim_stud_course = 'BS Bio';
                    }else if($this_violator->Course === 'BS Pharmacy'){
                        $lim_stud_course = 'BS Pharma';
                    }else if($this_violator->Course === 'BS Radiologic Technology'){
                        $lim_stud_course = 'BS Rad Tech';
                    }else if($this_violator->Course === 'BS Physical Therapy'){
                        $lim_stud_course = 'BS Ph Th';
                    }else if($this_violator->Course === 'BS Medical Technology'){
                        $lim_stud_course = 'BS Med Tech';
                    }else{
                        $lim_stud_course = $this_violator->Course;
                    }
                    // offense count
                    if($this_violator->offense_count > 1){
                        $oc_s = 's';
                    }else{
                        $oc_s = '';
                    }
                    $output .= '
                    <tr>
                        <td class="pl12 d-flex justify-content-start align-items-center">
                            <img class="display_violator_image2 shadow-sm" src="'.asset('storage/svms/sdca_images/registered_students_imgs/'.$this_violator->Student_Image.'').'" alt="student'.$sq.'s image">
                            <div class="cust_td_info">
                                <span class="actLogs_tdTitle font-weight-bold">
                                    '.preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $this_violator->First_Name) . ' 
                                    ' . preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $this_violator->Middle_Name) . ' 
                                    ' . preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $this_violator->Last_Name) . '
                                </span>
                                <span class="actLogs_tdSubTitle">
                                    <span class="sub1">'.preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $this_violator->Student_Number) . ' <span class="subDiv"> | </span> 
                                    <span class="sub1"> ' . preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $this_violator->School_Name) . ' - 
                                        ' . preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $lim_stud_course) . ' - 
                                        ' . preg_replace('/('.$vr_search.')/i','<span class="red_highlight2">$1</span>', $yearLevel_txt) . 
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
                                <span class="actLogs_content">'.$this_violator->offense_count.' Offense'.$oc_s.'</span>
                                <span class="actLogs_tdSubTitle sub2">
                                ';
                                if(!is_null($this_violator->minor_off) OR !empty($this_violator->minor_off)){
                                    foreach(json_decode($this_violator->minor_off, true) as $this_mo){
                                        $output .= '<span class="badge cust_badge1"> '.Str::limit($this_mo, $limit=15, $end='...').' </span> ';
                                    }
                                }
                                if(!is_null($this_violator->less_serious_off) OR !empty($this_violator->less_serious_off)){
                                    foreach(json_decode($this_violator->less_serious_off, true) as $this_lso){
                                        $output .= '<span class="badge cust_badge1"> '.Str::limit($this_lso, $limit=15, $end='...').' </span> ';
                                    }
                                }
                                if(!is_null($this_violator->other_off) OR !empty($this_violator->other_off)){
                                    if(!in_array(null, json_decode($this_violator->other_off, true))){
                                        $output .= ''.$this_violator->other_off.' ';
                                    }
                                }
                                $output .= '
                                </span>
                            </div>
                        </td>
                    </tr>
                    ';
                }
            }else{
                $output .='
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
            $paginate .= $filter_violation_records_table->links('pagination::bootstrap-4');
            
            $data = array(
                'violation_records_table' => $output,
                'paginate'                => $paginate,
                'total_rows'              => $total_matched_results,
                'total_data_found'        => $total_filtered_result
               );
         
            echo json_encode($data);
        }else{
            return view('violation_records.index');
        }
    }
}
