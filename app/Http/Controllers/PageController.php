<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userroles;
use DB;
use App\Models\Violations;
use DateTime;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index(string $page){
        if (view()->exists("pages.{$page}")) {
            // $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
            // $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
            // if(in_array($page, $get_uRole_access)){
                return view("pages.{$page}");
            // }else{
            //     return view('profile.access_denied');
            // }
        }

        return abort(404);
    }

    // DASHBOARD 
    public function load_contents(Request $request){
        if($request->ajax()){
            // total count of violators 
                $query_total_violators = Violations::join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                            ->select('violations_tbl.stud_num')
                            ->groupBy('students_tbl.Student_Number')
                            ->get();
                if(count($query_total_violators) > 0){
                    $count_total_violators = count($query_total_violators);
                    if($count_total_violators > 1){
                        $total_S = $count_total_violators . ' total violators found';
                    }else{
                        $total_S = $count_total_violators . ' total violator found';
                    }
                }else{
                    $count_total_violators = 0;
                    $total_S = $count_total_violators . ' violator found';
                }
            // total count of violators end

            // SBCS Violators count
                $count_SBCS_violators = Violations::join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                            ->where('students_tbl.School_Name', '=', 'SBCS')
                            ->groupBy('violations_tbl.stud_num')
                            ->count();
                if($count_SBCS_violators > 0){
                    // $count_SBCS_violators = $count_SBCS_violators;
                    if($count_SBCS_violators > 1){
                        $sbcs_S = $count_SBCS_violators . ' violators found';
                    }else{
                        $sbcs_S = $count_SBCS_violators . ' violator found';
                    }
                }else{
                    // $count_SBCS_violators = 0;
                    $sbcs_S = $count_SBCS_violators . ' violator found';
                }
            // SBCS Violators count end

            // SHSP Violators count
                $count_SHSP_violators = Violations::join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                            ->where('students_tbl.School_Name', '=', 'SHSP')
                            ->groupBy('violations_tbl.stud_num')
                            ->count();
                if($count_SHSP_violators > 0){
                    // $count_SHSP_violators = $count_SHSP_violators;
                    if($count_SHSP_violators > 1){
                        $shsp_S = $count_SHSP_violators . ' violators found';
                    }else{
                        $shsp_S = $count_SHSP_violators . ' violator found';
                    }
                }else{
                    // $count_SHSP_violators = 0;
                    $shsp_S = $count_SHSP_violators . ' violator found';
                }
            // SHSP Violators count end

            // SIHTM Violators count
                $count_SIHTM_violators = Violations::join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                            ->where('students_tbl.School_Name', '=', 'SIHTM')
                            ->groupBy('violations_tbl.stud_num')
                            ->count();
                if($count_SIHTM_violators > 0){
                    // $count_SIHTM_violators = $count_SIHTM_violators;
                    if($count_SIHTM_violators > 1){
                        $sihtm_S = $count_SIHTM_violators . ' violators found';
                    }else{
                        $sihtm_S = $count_SIHTM_violators . ' violator found';
                    }
                }else{
                    // $count_SIHTM_violators = 0;
                    $sihtm_S = $count_SIHTM_violators . ' violator found';
                }
            // SIHTM Violators count end 

            // SASE Violators count
                $count_SASE_violators = Violations::join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                            ->where('students_tbl.School_Name', '=', 'SASE')
                            ->groupBy('violations_tbl.stud_num')
                            ->count();
                if($count_SASE_violators > 0){
                    // $count_SASE_violators = $count_SASE_violators;
                    if($count_SASE_violators > 1){
                        $sase_S = $count_SASE_violators . ' violators found';
                    }else{
                        $sase_S = $count_SASE_violators . ' violator found';
                    }
                }else{
                    // $count_SASE_violators = 0;
                    $sase_S = $count_SASE_violators . ' violator found';
                }
            // SASE Violators count end

            // get all years with recorded violations
                $query_yearly_violators = Violations::selectRaw('year(recorded_at) year')
                                        ->groupBy('year')
                                        ->orderBy('year', 'asc')
                                        ->get();
                $count_yearly_violators = count($query_yearly_violators);
                $yearly_ViolatorsArray = array();
                $monthly_ViolatorsArray = array();
                $months_byNumbers =  array();
                $merge_mY = array();
                if($count_yearly_violators > 0){
                    foreach($query_yearly_violators as $this_year){
                        $this_yearVal_t = str_replace(array( '{', '}', '"', ':', 'year' ), '', $this_year);
                        $yearly_ViolatorsArray[] = $this_yearVal_t;

                        // get all months per year with recorded violations
                            $query_monthly_violators = Violations::selectRaw('month(recorded_at) month')
                                            ->whereYear('recorded_at', $this_yearVal_t)
                                            ->groupBy('month')
                                            ->orderBy('month', 'asc')
                                            ->get();
                            $count_monthly_violators = count($query_monthly_violators); 
                            if($count_monthly_violators > 0){
                                foreach($query_monthly_violators as $this_month){
                                    $this_monthVal_t = str_replace(array( '{', '}', '"', ':', 'month' ), '', $this_month);
                                    $dateObj         = DateTime::createFromFormat('!m', $this_monthVal_t);
                                    $monthName       = $dateObj->format('F');
                                    $monthly_ViolatorsArray[] = $monthName . ' ' . $this_yearVal_t;
                                    $months_byNumbers[] = $this_monthVal_t;
                                    $merge_mY[] = $this_yearVal_t.'-'.$this_monthVal_t;
                                    // $merge_mY[$this_yearVal_t] = $this_monthVal_t;
                                }
                            }
                            // extract $yearly_ViolatorsArray[]
                            $toJson_arrayMonthlyViolators = json_encode($monthly_ViolatorsArray);
                            $ext_toJson_arrayMonthlyViolators = str_replace(array('"'), '', $toJson_arrayMonthlyViolators);
                        // get all months per year with recorded violations end 
                    }
                }
                // extract $yearly_ViolatorsArray[]
                $toJson_arrayYearlyViolators = json_encode($yearly_ViolatorsArray);
                $ext_toJson_arrayYearlyViolators = str_replace(array('"'), '', $toJson_arrayYearlyViolators);
            // get all years with recorded violations end

            // get counts for datasets
                $format_merge_my = array();
                $sbcs_monthlyViolatorDataset = array();
                $sase_monthlyViolatorDataset = array();
                $shsp_monthlyViolatorDataset = array();
                $sihtm_monthlyViolatorDataset = array();
                foreach(json_decode(json_encode($merge_mY), true) as $this_merge_mY){
                    $format_timestamp = date('Y-m-d H:i:s', strtotime($this_merge_mY));
                    $format_Yeartimestamp = date('Y', strtotime($this_merge_mY));
                    $format_Monthtimestamp = date('m', strtotime($this_merge_mY));
                    $format_merge_my[] = $format_timestamp;
                    $query_violatorCountsSBCS = Violations::join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                                    ->whereYear('recorded_at', '=', $format_Yeartimestamp)
                                    ->whereMonth('recorded_at', '=', $format_Monthtimestamp)
                                    ->where('students_tbl.School_Name', '=', 'SBCS')
                                    ->groupBy('violations_tbl.stud_num')
                                    ->count();
                    $sbcs_monthlyViolatorDataset[] = $query_violatorCountsSBCS;

                    $query_violatorCountsSASE = Violations::join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                                    ->whereYear('recorded_at', '=', $format_Yeartimestamp)
                                    ->whereMonth('recorded_at', '=', $format_Monthtimestamp)
                                    ->where('students_tbl.School_Name', '=', 'SASE')
                                    ->groupBy('violations_tbl.stud_num')
                                    ->count();
                    $sase_monthlyViolatorDataset[] = $query_violatorCountsSASE;

                    $query_violatorCountsSIHTM = Violations::join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                                    ->whereYear('recorded_at', '=', $format_Yeartimestamp)
                                    ->whereMonth('recorded_at', '=', $format_Monthtimestamp)
                                    ->where('students_tbl.School_Name', '=', 'SIHTM')
                                    ->groupBy('violations_tbl.stud_num')
                                    ->count();
                    $sihtm_monthlyViolatorDataset[] = $query_violatorCountsSIHTM;

                    $query_violatorCountsSHSP = Violations::join('students_tbl', 'violations_tbl.stud_num', '=', 'students_tbl.Student_Number')
                                    ->whereYear('recorded_at', '=', $format_Yeartimestamp)
                                    ->whereMonth('recorded_at', '=', $format_Monthtimestamp)
                                    ->where('students_tbl.School_Name', '=', 'SHSP')
                                    ->groupBy('violations_tbl.stud_num')
                                    ->count();
                    $shsp_monthlyViolatorDataset[] = $query_violatorCountsSHSP;
                }
                
            // data 
                $data = array(
                    'total_violators_count' => $count_total_violators,
                    'total_S'               => $total_S,
                    'sbcs_violators_count'  => $count_SBCS_violators,
                    'sbcs_S'                => $sbcs_S,
                    'shsp_violators_count'  => $count_SHSP_violators,
                    'shsp_S'                => $shsp_S,
                    'sihtm_violators_count' => $count_SIHTM_violators,
                    'sihtm_S'               => $sihtm_S,
                    'sase_violators_count'  => $count_SASE_violators,
                    'sase_S'                => $sase_S,
                    'years'                 => $ext_toJson_arrayYearlyViolators,
                    'months'                => $monthly_ViolatorsArray,
                    'months_byNumbers'      => $merge_mY,
                    'sbcs_monthlyViolatorDataset' => $sbcs_monthlyViolatorDataset,
                    'sase_monthlyViolatorDataset' => $sase_monthlyViolatorDataset,
                    'sihtm_monthlyViolatorDataset' => $sihtm_monthlyViolatorDataset,
                    'shsp_monthlyViolatorDataset' => $shsp_monthlyViolatorDataset,
                    'format_merge_my' => $format_merge_my
                );
            // data end

            echo json_encode($data);
        }
    }
}
