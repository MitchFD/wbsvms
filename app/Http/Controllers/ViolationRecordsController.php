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

    // filter user logs table 
    public function users_logs_filter_table(Request $request){
        if($request->ajax()){
            // get all request
            $logs_search = $request->get('logs_search');
            $logs_userTypes = $request->get('logs_userTypes');
            $logs_userRoles = $request->get('logs_userRoles');
            $logs_users = $request->get('logs_users');
            $logs_category = $request->get('logs_category');
            $logs_rangefrom = $request->get('logs_rangefrom');
            $logs_rangeTo = $request->get('logs_rangeTo');
            $output = '';

            if($logs_search !== ''){
                $filter_user_logs_table = DB::table('users_activity_tbl')
                                                ->join('users', 'users_activity_tbl.act_respo_user_id', '=', 'users.id')
                                                ->select('users_activity_tbl.*', 'users.id', 'users.user_role', 'users.user_status', 'users.user_role_status', 'users.user_type', 'users.user_sdca_id', 'users.user_image', 'users.user_gender')
                                                ->where('users.user_sdca_id', 'like', '%'.$logs_search.'%')
                                                ->orWhere('users.user_role', 'like', '%'.$logs_search.'%')
                                                ->orWhere('users.user_type', 'like', '%'.$logs_search.'%')
                                                ->orWhere('users.user_gender', 'like', '%'.$logs_search.'%')
                                                ->orWhere('users_activity_tbl.act_respo_users_lname', 'like', '%'.$logs_search.'%')
                                                ->orWhere('users_activity_tbl.act_respo_users_fname', 'like', '%'.$logs_search.'%')
                                                ->orWhere('users_activity_tbl.act_type', 'like', '%'.$logs_search.'%')
                                                ->orWhere('users_activity_tbl.act_details', 'like', '%'.$logs_search.'%')
                                                ->paginate(10);
            }else{
                $filter_user_logs_table = DB::table('users_activity_tbl')
                                                ->join('users', 'users_activity_tbl.act_respo_user_id', '=', 'users.id')
                                                ->select('users_activity_tbl.*', 'users.id', 'users.user_role', 'users.user_status', 'users.user_role_status', 'users.user_type', 'users.user_sdca_id', 'users.user_image', 'users.user_gender')
                                                ->paginate(10);
            }
            if(count($filter_user_logs_table) > 0){
                foreach($filter_user_logs_table->sortBy('act_id') as $users_logs){
                    // custom values
                    if($users_logs->user_type === 'employee'){
                        $img_border = 'rslts_emp';
                    }elseif($users_logs->user_type === 'student'){
                        $img_border = 'rslts_stud';
                    }else{
                        $img_border = 'rslts_unknown';
                    }
                    $output .= '
                    <tr>
                        <td class="pl12 d-flex justify-content-start align-items-center">
                            <img class="rslts_userImgs ' . $img_border.'" src="'.asset('storage/svms/user_images/'.$users_logs->user_image.'').'" alt="user image">
                            <div class="cust_td_info">
                                <span class="actLogs_tdTitle font-weight-bold">'.preg_replace('/('.$logs_search.')/i','<span class="grn_highlight2">$1</span>', $users_logs->act_respo_users_fname) . ' ' .preg_replace('/('.$logs_search.')/i','<span class="grn_highlight2">$1</span>', $users_logs->act_respo_users_lname) . '</span>
                                <span class="actLogs_tdSubTitle"><span class="sub1">'.preg_replace('/('.$logs_search.')/i','<span class="grn_highlight2">$1</span>', $users_logs->user_sdca_id) . ' </span> <span class="subDiv"> / </span> <span class="sub1"> ' . preg_replace('/('.$logs_search.')/i','<span class="grn_highlight2">$1</span>', ucwords($users_logs->user_role)).'</span></span>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline">
                                <span class="actLogs_content">'.date('F d, Y', strtotime($users_logs->created_at)). '</span>
                                <span class="actLogs_tdSubTitle sub2">'.date('D', strtotime($users_logs->created_at)). ' at ' .date('g:i A', strtotime($users_logs->created_at)).'</span>
                            </div>
                        </td>
                        <td><span class="actLogs_content">'.preg_replace('/('.$logs_search.')/i','<span class="grn_highlight2">$1</span>', $users_logs->act_type) . '</span></td>
                        <td><span class="actLogs_content">'.preg_replace('/('.$logs_search.')/i','<span class="grn_highlight2">$1</span>', $users_logs->act_details) . '</span></td>
                    </tr>
                ';
                }
            }else{
                $output .='
                    <tr class="no_data_row">
                        <td align="center" colspan="7">
                            <div class="no_data_div d-flex justify-content-center align-items-center text-center flex-column">
                                <img class="illustration_svg" src="'. asset('storage/svms/illustrations/no_matching_users_found.svg') .'" alt="no matching users found">
                                <span class="font-italic">No Matching Users Found for <span class="font-weight-bold"> ' . $logs_search.'...</span></span>
                            </div>
                        </td>
                    </tr>
                ';
            }
            $paginate = '';
            $paginate .= $filter_user_logs_table->links('pagination::bootstrap-4');
            $data = array(
                'users_logs_table' => $output,
                'paginate' => $paginate,
                'total_rows' => count($filter_user_logs_table)
               );
         
            echo json_encode($data);
        }
    }
}
