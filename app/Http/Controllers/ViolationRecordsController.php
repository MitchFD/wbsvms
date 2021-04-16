<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Useractivites;

class ViolationRecordsController extends Controller
{
    public function index(){
        return view('violation_records.index');
    }

    // filter user logs table 
    public function users_logs_filter_table(Request $request){
        if($request->ajax()){
            $output = '';
            $logs_search = $request->get('logs_search');
            $logs_users = $request->get('logs_users');
            $logs_category = $request->get('logs_category');
            // if($logs_search !== ''){
            //     $filter_user_logs_table = DB::table('users_activity_tbl')
            //                                     ->join('users', 'users_activity_tbl.act_respo_user_id', '=', 'users.id')
            //                                     ->select('users_activity_tbl.*', 'users.id', 'users.user_role', 'users.user_status', 'users.user_role_status', 'users.user_sdca_id', 'users.user_image', 'users.user_gender')
            //                                     ->get(10);
            // }else{
            //     $filter_user_logs_table = Useractivites::select('act_id', 'created_at', 'act_respo_user_id', 'act_respo_users_lname', 'act_respo_users_fname', 'act_type', 'act_details', 'act_affected_id')
            //                                                 ->where('act_respo_user_id', 'like', '%'.$logs_users.'%')
            //                                                 ->orWhere('act_type', 'like', '%'.$logs_category.'%')
            //                                                 ->get(10);
            // }
            // if(count($filter_user_logs_table) > 0){
            //     foreach($filter_user_logs_table->sortBy('act_id') as $users_logs){
            //         $output .= '
            //         <tr>
            //             <td>'.preg_replace('/('.$logs_search.')/i','<span class="red_highlight">$1</span>', $users_logs->act_respo_users_fname) . '</td>
            //             <td>'.$users_logs->created_at.'</td>
            //             <td>'.$users_logs->act_type.'</td>
            //             <td>'.$users_logs->act_details.'</td>
            //         </tr>
            //     ';
            //     }
            // }else{
            //     $output .='
            //         <tr class="no_data_row">
            //             <td align="center" colspan="7">
            //                 <div class="no_data_div d-flex justify-content-center align-items-center text-center flex-column">
            //                     <img class="illustration_svg" src="'. asset('storage/svms/illustrations/no_matching_users_found.svg') .'" alt="no matching users found">
            //                     <span class="font-italic">No Matching Users Found for <span class="font-weight-bold"> ...</span></span>
            //                 </div>
            //             </td>
            //         </tr>
            //     ';
            // }
            $data = array(
                'users_logs_table' => $output
               );
         
            echo json_encode($data);
        }
    }
}
