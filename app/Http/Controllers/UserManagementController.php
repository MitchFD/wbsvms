<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Userroles;
use App\Models\Userrolesupdatestatus;
use App\Models\Editedolduserroles;
use App\Models\Editednewuserroles;
use App\Models\Users;
use App\Models\Userupdatesstatus;
use App\Models\Useremployees;
use App\Models\Editedolduseremployees;
use App\Models\Editednewuseremployees;
use App\Models\Userstudents;
use App\Models\Editedoldstudentusers;
use App\Models\Editednewstudentusers;
use App\Models\Passwordupdate;
use App\Models\Useractivites;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Mail\Mailable;
use PDF;
use Dompdf;

class UserManagementController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function index(){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('users management', $get_uRole_access)){
            // users
            $active_users      = Users::where('user_status', 'active')->where('user_role_status', 'active')->get();
            $deactivated_users = Users::where('user_status', 'deactivated')->orWhere('user_role_status', 'deactivated')->get();
            $pending_users     = Users::where('user_role', 'pending')->where('user_status', 'pending')->where('user_role_status', 'pending')->get();
            $deleted_users     = Users::where('user_status', 'deleted')->get();
            $registered_users  = Users::where('user_status', '!=', 'deleted')->get();

            // user roles
            $active_roles      = Userroles::where('uRole_status', 'active')->get();
            $deactivated_roles = Userroles::where('uRole_status', 'deactivated')->get();
            $deleted_roles     = Userroles::where('uRole_status', 'deleted')->get();
            $registered_roles  = Userroles::where('uRole_status', '!=', 'deleted')->get();

            // user activities
            $all_activities = Useractivites::get();
            return view('user_management.index')->with(compact('active_users', 'deactivated_users', 'pending_users', 'deleted_users', 'registered_users', 'active_roles', 'deactivated_roles', 'deleted_roles', 'registered_roles', 'all_activities'));
        }else{
            return view('profile.access_denied');
        }
    }

    // sub-modules
    // overview_users_management
    public function overview_users_management(){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('users management', $get_uRole_access)){
            // users
            $count_active_users      = Users::where('user_status', 'active')->where('user_role_status', 'active')->count();
            $count_deactivated_users = Users::where('user_status', 'deactivated')->orWhere('user_role_status', 'deactivated')->count();
            $count_pending_users     = Users::where('user_role', 'pending')->orWhere('user_status', 'pending')->orWhere('user_role_status', 'pending')->count();
            $count_deleted_users     = Users::where('user_status', 'deleted')->count();
            $count_registered_users  = Users::where('user_status', '!=', 'deleted')->count();

            // system roles
            $count_active_roles      = Userroles::where('uRole_status', 'active')->count();
            $count_deactivated_roles = Userroles::where('uRole_status', 'deactivated')->count();
            $count_deleted_roles     = Userroles::where('uRole_status', 'deleted')->count();
            $count_registered_roles  = Userroles::where('uRole_status', '!=', 'deleted')->count();
            return view('user_management.overview')->with(compact('count_active_users', 'count_deactivated_users', 'count_pending_users', 'count_deleted_users', 'count_registered_users', 'count_active_roles', 'count_deactivated_roles', 'count_deleted_roles', 'count_registered_roles'));
        }else{
            return view('profile.access_denied');
        }
    }

    // create_users
    public function create_users(){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('users management', $get_uRole_access)){
            $employee_system_roles = Userroles::select('uRole_type', 'uRole')->where('uRole_type', 'employee')->get();
            $student_system_roles  = Userroles::select('uRole_type', 'uRole')->where('uRole_type', 'student')->get();
            return view('user_management.create_users')->with(compact('employee_system_roles', 'student_system_roles'));
        }else{
            return view('profile.access_denied');
        }
    }

    // system_users
    public function system_users(){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('users management', $get_uRole_access)){
            // users
            $count_active_users      = Users::where('user_status', 'active')->where('user_role_status', 'active')->count();
            $count_deactivated_users = Users::where('user_status', 'deactivated')->orWhere('user_role_status', 'deactivated')->count();
            $count_deleted_users     = Users::where('user_status', 'deleted')->count();
            $count_registered_users  = Users::count();
            $count_pending_users     = Users::where('user_status', 'pending')->orWhere('user_role_status', 'pending')->count();
            $count_employee_users    = Users::where('user_type', 'employee')->count();
            $count_student_users     = Users::where('user_type', 'student')->count();
            $count_male_users        = Users::where('user_gender', 'male')->count();
            $count_female_users      = Users::where('user_gender', 'female')->count();
            // $count_registered_users  = Users::where('user_status', '!=', 'deleted')->count();

            // system roles
            $count_total_roles       = Userroles::where('uRole_status', '!=', 'deleted')->count();
            $count_active_roles      = Userroles::where('uRole_status', 'active')->count();
            $count_deactivated_roles = Userroles::where('uRole_status', 'deactivated')->count();
            $count_empty_roles       = Userroles::where('assUsers_count', 0)->count();
            $count_pending_roles     = Userroles::where('uRole_status', 'pending')->count();
            return view('user_management.system_users')->with(compact('count_total_roles', 'count_active_users', 'count_deactivated_users', 'count_pending_users', 'count_deleted_users', 'count_registered_users', 'count_employee_users', 'count_student_users', 'count_male_users', 'count_female_users', 'count_active_roles', 'count_deactivated_roles', 'count_empty_roles', 'count_pending_roles'));
        }else{
            return view('profile.access_denied');
        }
    }
    // user_profile
    public function user_profile($user_id){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('users management', $get_uRole_access)){
            // user data
            $user_data = Users::where('id', $user_id)->first();
            // user activities
            $user_activities = Useractivites::where('act_respo_user_id', $user_id)->count();
            // user's first and latest record
            $user_first_record = Useractivites::where('act_respo_user_id', $user_id)->first();
            $user_latest_record = Useractivites::where('act_respo_user_id', $user_id)->latest()->first();
            // my categories options
            $user_trans_categories = Useractivites::select('act_type')->where('act_respo_user_id', $user_id)->groupBy('act_type')->get();
            return view('user_management.user_profile')->with(compact('user_data', 'user_activities', 'user_first_record', 'user_latest_record', 'user_trans_categories'));
        }else{
            return view('profile.access_denied');
        }
    }
    // user's activity logs table
    public function user_act_logs(Request $request){
        // get all request
        $ual_user_id = $request->get('ual_user_id');
        $ual_rangefrom = $request->get('ual_rangefrom');
        $ual_rangeTo = $request->get('ual_rangeTo');
        $ual_category = $request->get('ual_category');
        $page = $request->get('page');
        if($request->ajax()){
            // custom var
            $output = '';
            $ual_paginate = '';
            $ual_total_results = '';
            // get all request
            $ual_rangefrom = $request->get('ual_rangefrom');
            $ual_rangeTo   = $request->get('ual_rangeTo');
            $ual_category  = $request->get('ual_category');
            $page         = $request->get('page');
            // to lower values
            $toLower_category = Str::lower($ual_category);
            // query
            $filter_user_logs_table = Useractivites::where('act_respo_user_id', $ual_user_id)
                                    ->where(function($query) use ($ual_rangefrom, $ual_rangeTo, $ual_category, $toLower_category){
                                        if($ual_category != 0 OR !empty($ual_category)){
                                            $query->where('act_type', '=', $toLower_category);
                                        }
                                        if($ual_rangefrom != 0 OR !empty($ual_rangefrom) OR !is_null($ual_rangefrom) AND $ual_rangeTo != 0 OR !empty($ual_rangeTo) OR !is_null($ual_rangeTo)){
                                            $query->whereBetween('created_at', [$ual_rangefrom, $ual_rangeTo]);
                                        }
                                    })
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate(10);
            // total filtered date
            $al_count_Filtered_result = count($filter_user_logs_table);
            $ual_totalFiltered_result = $filter_user_logs_table->total();
            // custom values
            if($ual_totalFiltered_result > 0){
                if($ual_totalFiltered_result > 1){
                    $s = 's';
                }else{
                    $s = '';
                }
                $ual_total_results = $filter_user_logs_table->firstItem() . ' - ' . $filter_user_logs_table->lastItem() . ' of ' . $ual_totalFiltered_result . ' Record'.$s;
            }else{
                $s = '';
                $ual_total_results = 'No Records Found';
            }
            // display
            if($al_count_Filtered_result > 0){
                foreach($filter_user_logs_table as $users_logs){
                    // custom values
                    $format_createdAt = ''.date('F d, Y (D - g:i A)', strtotime($users_logs->created_at));
                    $output .= '
                    <tr>
                        <td class="p12">
                            <span class="actLogs_contentv1 font-weight-bold">'. date('F d, Y', strtotime($users_logs->created_at)) . ' <span class="sub2 font-weight-normal">' . date('(D - g:i A)', strtotime($users_logs->created_at)). '</span></span>
                        </td>
                        <td><span class="actLogs_contentv1">'.ucwords($users_logs->act_type) . '</span></td>
                        <td><span class="actLogs_contentv1">~ ' . $users_logs->act_details . '</span></td>
                    </tr>
                ';
                }
            }else{
                $output .='
                    <tr class="no_data_row">
                        <td align="center" colspan="7">
                            <div class="no_data_div d-flex justify-content-center align-items-center text-center flex-column">
                                <img class="illustration_svg" src="'. asset('storage/svms/illustrations/no_records_found.svg') .'" alt="no matching users found">
                                <span class="font-italic">No Records Found!
                            </div>
                        </td>
                    </tr>
                ';
            }
            $ual_paginate .= $filter_user_logs_table->links('pagination::bootstrap-4');
            $data = array(
                'ual_table'            => $output,
                'ual_table_paginate'   => $ual_paginate,
                'ual_total_rows'       => $ual_total_results,
                'ual_total_data_found' => $ual_totalFiltered_result
                );
            
            echo json_encode($data);
        }else{
            return view('user_management.user_profile')->with(compact('user_data', 'user_activities', 'user_first_record', 'user_latest_record', 'user_trans_categories'));
        }
    }
    // generate PDF = user's logs 
    public function pdf_user_logs($ual_user_id, $ual_rangefrom, $ual_rangeTo, $ual_category){
        // custom values
        $now_timestamp = now();
        $output = '';
        // to lower values
        $toLower_category = Str::lower($ual_category);
        // query selected user's info
        $query_sel_user = Users::select('user_role','user_lname', 'user_fname')->where('id', $ual_user_id)->first();
        // query responsible user info
        $query_respo_user = Users::select('user_role','user_lname', 'user_fname')->where('id', auth()->user()->id)->first();
        // query user's activity logs
        $query_user_logs = Useractivites::where('act_respo_user_id', $ual_user_id)
                    ->where(function($query) use ($ual_rangefrom, $ual_rangeTo, $ual_category, $toLower_category){
                        if($ual_category != 0 OR !empty($ual_category)){
                            $query->where('act_type', '=', $toLower_category);
                        }
                        if($ual_rangefrom != 0 AND $ual_rangeTo != 0){
                            $query->whereBetween('created_at', [$ual_rangefrom, $ual_rangeTo]);
                        }
                    })
                    ->orderBy('created_at', 'DESC')
                    ->get();
        // count query
        $count_query_user_logs = count($query_user_logs);
        // display categories
        if($ual_category != 0 OR !empty($ual_category)){
            $display_category = ucwords($ual_category) . ' Histories.';
        }else{
            $query_SelCategories = Useractivites::select('act_type')->where('act_respo_user_id', $ual_user_id)->groupBy('act_type')->get();
            $this_categoryArray = array();
            $count_displayCategory = count($query_SelCategories);
            $i = 0;
            foreach($query_SelCategories as $this_category){
                $this_categoryArray[] = ucwords($this_category->act_type);
                $i++;
            }
            if($i === $count_displayCategory) {
                $addTxt = 'Histories.';
            }
            $display_category =  implode(', ', $this_categoryArray) . ' ' . $addTxt;
        }
        // display date range
        if($ual_rangefrom != 0 AND $ual_rangeTo != 0){
            $display_date_range1 = date('F d, Y', strtotime($ual_rangefrom));
            $display_date_range2 = date('(D - g:i A)', strtotime($ual_rangefrom));
            $display_date_range3 = date('F d, Y', strtotime($ual_rangeTo));
            $display_date_range4 = date('(D - g:i A)', strtotime($ual_rangeTo));
        }else{
            // get user's first and latest record
            $user_first_record = Useractivites::where('act_respo_user_id', $ual_user_id)->first();
            $user_latest_record = Useractivites::where('act_respo_user_id', $ual_user_id)->latest()->first();
            $display_date_range1 = date('F d, Y', strtotime($user_first_record->created_at));
            $display_date_range2 = date('(D - g:i A)', strtotime($user_first_record->created_at));
            $display_date_range3 = date('F d, Y', strtotime($user_latest_record->created_at));
            $display_date_range4 = date('(D - g:i A)', strtotime($user_latest_record->created_at));
        }
        // generate pdf
        $pdf = \App::make('dompdf.wrapper');
        // $pdf->loadHTML($output);
        $pdf = PDF::loadView('pdfs/user_logs_pdf', compact('query_user_logs', 'now_timestamp', 'query_respo_user', 'query_sel_user', 'display_category', 'display_date_range1', 'display_date_range2', 'display_date_range3', 'display_date_range4'));
        $pdf->setPaper('A4');
        return $pdf->stream('pdfs/user_logs_pdf.pdf');
    }

    // live search filter for system users table
    public function live_search_users_filter(Request $request){
        if($request->ajax()){
            $output = '';
            $users_query = $request->get('users_query');
            if($users_query != ''){
                $data = Users::select('id', 'user_role', 'user_status', 'user_role_status', 'user_type', 'user_sdca_id', 'user_image', 'user_lname', 'user_fname', 'user_gender')
                            ->where('user_role', 'like', '%'.$users_query.'%')
                            ->orWhere('user_status', 'like', '%'.$users_query.'%')
                            ->orWhere('user_type', 'like', '%'.$users_query.'%')
                            ->orWhere('user_sdca_id', 'like', '%'.$users_query.'%')
                            ->orWhere('user_lname', 'like', '%'.$users_query.'%')
                            ->orWhere('user_fname', 'like', '%'.$users_query.'%')
                            ->orWhere('user_gender', 'like', '%'.$users_query.'%')
                            ->orderBy('id', 'asc')
                            ->get();
            }else{
                $data = Users::orderBy('id', 'asc')->get();
            }
            $total_row  = $data->count();
            if($total_row > 0){
                // output matching users found and total data count
                if($total_row > 1){
                    $matched_results  = $total_row . ' Match Found for <span class="font-weight-bold font-italic"> ' .$users_query.'...</span>';
                    $total_data_count = $total_row . ' rows';
                }else{
                    $matched_results  = $total_row . ' Match Found  for <span class="font-weight-bold font-italic"> ' .$users_query.'...</span>';
                    $total_data_count = $total_row . ' row';
                }

                // output results
                foreach($data as $row){
                    // custom classes
                    $apost = "'";
                    // row text filter
                    if($row->user_status === 'active' AND $row->user_role_status === 'active'){
                        $tr_gray_stat    = '';
                        $img_rslts_deact = '';
                        $stat_txt_filter = 'text-success';
                        $stat_txt_alt    = 'active';
                    }else{
                        if($row->user_status === 'deactivated' OR $row->user_role_status === 'deactivated'){
                            $tr_gray_stat    = 'gry_stat';
                            $img_rslts_deact = 'rslts_deact';
                            $stat_txt_filter = 'text_svms_red';
                            $stat_txt_alt    = 'deactivated';
                        }else{
                            if($row->user_role_status === 'deleted'){
                                $tr_gray_stat    = 'gry_stat';
                                $img_rslts_deact = 'rslts_deact';
                                $stat_txt_filter = 'text_svms_red';
                                $stat_txt_alt    = 'deleted';
                            }else{
                                $tr_gray_stat    = 'gry_stat';
                                $img_rslts_deact = 'rslts_deact';
                                $stat_txt_filter = '';
                                $stat_txt_alt    = 'pending';
                            }
                        }
                    }

                    // tolower case user_type
                    $tolower_uType = Str::lower($row->user_type);

                    // user image handler
                    if(!is_null($row->user_image) OR !empty($row->user_image)){
                        $user_imgJpgFile = $row->user_image;
                    }else{
                        if($tolower_uType === 'employee'){
                            $user_imgJpgFile = 'employee_user_image.jpg';
                        }elseif($tolower_uType === 'student'){
                            $user_imgJpgFile = 'student_user_image.jpg';
                        }else{
                            $user_imgJpgFile = 'disabled_user_image.jpg';
                        }
                    }

                    // user type image filter
                    if($tolower_uType === 'employee'){
                        $uImg_fltr = 'rslts_emp';
                    }elseif($tolower_uType === 'student'){
                        $uImg_fltr = 'rslts_stud';
                    }else{
                        $uImg_fltr = 'rslts_unknown';
                    }

                    // custom texts
                    $deactivated_txt = 'deactivated';

                    $output .='
                        <tr class="'.$tr_gray_stat.'">
                            <td class="pl12">
                                <img class="rslts_userImgs '.$uImg_fltr.' '.$img_rslts_deact.'" src="'.asset('storage/svms/user_images/'.$user_imgJpgFile).'" alt="'.$row->user_fname . ' ' . $row->user_lname.''.$apost.'s profile image">
                                <span class="ml-3">'.preg_replace('/('.$users_query.')/i','<span class="grn_highlight">$1</span>', $row->user_fname). ' ' .preg_replace('/('.$users_query.')/i','<span class="grn_highlight">$1</span>', $row->user_lname).'</span>
                            </td>
                            <td>'.preg_replace('/('.$users_query.')/i','<span class="grn_highlight">$1</span>', ucwords($row->user_sdca_id)).'</td>
                            <td>'.preg_replace('/('.$users_query.')/i','<span class="grn_highlight">$1</span>', ucwords($row->user_role)).'</td>
                            <td>'.preg_replace('/('.$users_query.')/i','<span class="grn_highlight">$1</span>', ucwords($row->user_type)).'</td>
                            <td>'.preg_replace('/('.$users_query.')/i','<span class="grn_highlight">$1</span>', ucwords($row->user_gender)).'</td>
                            <td class="'.$stat_txt_filter.' font-weight-bold">'.preg_replace('/('.$users_query.')/i','<span class="grn_highlight">$1</span>', ucwords($stat_txt_alt)).'</td>
                            <td class="text-center pr12">';
                            // actions
                            if(auth()->user()->id === $row->id){
                                $output .= '<a href="'. route('profile.index', 'profile') .'" class="btn cust_btn_smcircle3 pt7" data-toggle="tooltip" data-placement="top" title="View Your Profile?"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                            }else{
                                $output .= '<a href="'. route('user_management.user_profile', $row->id, 'user_profile') .'" class="btn cust_btn_smcircle3 pt7" data-toggle="tooltip" data-placement="top" title="View '.$row->user_fname . ' ' . $row->user_lname.''.$apost.'s Profile?"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                                if($row->user_role_status === 'active'){
                                    if($row->user_status === 'active'){
                                        $output .= '<button id="'.$row->id.'" class="btn cust_btn_smcircle3" onclick="deactivateUserAccount(this.id)" data-toggle="tooltip" data-placement="top" title="Deactivate '.$row->user_fname . ' ' . $row->user_lname.''.$apost.'s Account"><i class="fa fa-toggle-on" aria-hidden="true"></i></button>';
                                    }else{
                                        if($row->user_status === 'deactivated'){
                                            $output .= '<button id="'.$row->id.'" class="btn cust_btn_smcircle3" onclick="activateUserAccount(this.id)" data-toggle="tooltip" data-placement="top" title="Activate '.$row->user_fname . ' ' . $row->user_lname.''.$apost.'s Account"><i class="fa fa-toggle-off" aria-hidden="true"></i></button>';
                                        }else{
                                            $output .= '';
                                        }
                                    }
                                }else{
                                    if($row->user_role_status === 'deactivated'){
                                        $output .= '<button id="'.$row->id.'" class="btn cust_btn_smcircle3" onclick="activateUserAccount(this.id)" data-toggle="tooltip" data-placement="top" title="Activate '.$row->user_fname . ' ' . $row->user_lname.''.$apost.'s Account"><i class="fa fa-toggle-off" aria-hidden="true"></i></button>';
                                    }else{
                                        $output .= '';
                                    }   
                                }
                                $output .= '<button id="'.$row->id.'" class="btn cust_btn_smcircle3" data-toggle="tooltip" data-placement="top" title="Delete '.$row->user_fname . ' ' . $row->user_lname.''.$apost.'s Account"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                            }
                            

                            $output .='
                            </td>
                        </tr>
                    ';
                }
            }else{
                // output total matched results and total data count
                $total_data_count = $total_row . ' users';
                $matched_results = 'No Match found for '.$users_query.'...';
                $output .='
                    <tr class="no_data_row">
                        <td align="center" colspan="7">
                            <div class="no_data_div d-flex justify-content-center align-items-center text-center flex-column">
                                <img class="illustration_svg" src="'. asset('storage/svms/illustrations/no_matching_users_found.svg') .'" alt="no matching users found">
                                <span class="font-italic">No Matching Users Found for <span class="font-weight-bold"> ' .$users_query.'...</span></span>
                            </div>
                        </td>
                    </tr>
                ';
            }
            $data = array(
                'sys_users_tbl_data' => $output,
                'total_data_count'   => $total_data_count,
                'matched_searches'   => $matched_results,
                'search_query'       => $users_query
               );
         
            echo json_encode($data);
        }
    }

    // system_roles
    public function system_roles(){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('users management', $get_uRole_access)){
            return view('user_management.system_roles');
        }else{
            return view('profile.access_denied');
        }
    }

    // users_logs
    public function users_logs(Request $request){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('users management', $get_uRole_access)){
            if($request->ajax()){
                // custom var
                $output = '';
                $paginate = '';
                $total_matched_results = '';
                // get all request
                $logs_search    = $request->get('logs_search');
                $logs_userTypes = $request->get('logs_userTypes');
                $logs_userRoles = $request->get('logs_userRoles');
                $logs_users     = $request->get('logs_users');
                $logs_category  = $request->get('logs_category');
                $logs_rangefrom = $request->get('logs_rangefrom');
                $logs_rangeTo   = $request->get('logs_rangeTo');
                $logs_page      = $request->get('page');
    
                if($logs_search != ''){
                    $filter_user_logs_table = DB::table('users_activity_tbl')
                                            ->join('users', 'users_activity_tbl.act_respo_user_id', '=', 'users.id')
                                            ->select('users_activity_tbl.*', 'users.id', 'users.user_role', 'users.user_status', 'users.user_role_status', 'users.user_type', 'users.user_sdca_id', 'users.user_image', 'users.user_gender')
                                            ->where(function($query) use ($logs_search) {
                                                $query->orWhere('users.user_sdca_id', 'like', '%'.$logs_search.'%')
                                                            ->orWhere('users.user_role', 'like', '%'.$logs_search.'%')
                                                            ->orWhere('users.user_type', 'like', '%'.$logs_search.'%')
                                                            ->orWhere('users.user_gender', 'like', '%'.$logs_search.'%')
                                                            ->orWhere('users_activity_tbl.act_respo_users_lname', 'like', '%'.$logs_search.'%')
                                                            ->orWhere('users_activity_tbl.act_respo_users_fname', 'like', '%'.$logs_search.'%')
                                                            ->orWhere('users_activity_tbl.act_type', 'like', '%'.$logs_search.'%')
                                                            ->orWhere('users_activity_tbl.act_details', 'like', '%'.$logs_search.'%');
                                            })
                                            ->where(function($query) use ($logs_userTypes, $logs_userRoles, $logs_users, $logs_category, $logs_rangefrom, $logs_rangeTo){
                                                if($logs_userTypes != 0 OR !empty($logs_userTypes)){
                                                    $query->where('users.user_type', '=', $logs_userTypes);
                                                }
                                                if($logs_userRoles != 0 OR !empty($logs_userRoles)){
                                                    $query->where('users.user_role', '=', $logs_userRoles);
                                                }
                                                if($logs_users != 0 OR !empty($logs_users)){
                                                    $query->where('users.id', '=', $logs_users);
                                                }
                                                if($logs_category != 0 OR !empty($logs_category)){
                                                    $query->where('users_activity_tbl.act_type', '=', $logs_category);
                                                }
                                                if($logs_rangefrom != 0 OR !empty($logs_rangefrom) AND $logs_rangeTo != 0 OR !empty($logs_rangeTo)){
                                                    $query->whereBetween('users_activity_tbl.created_at', [$logs_rangefrom, $logs_rangeTo]);
                                                }
                                            })
                                            ->orderBy('users_activity_tbl.created_at', 'DESC')
                                            ->paginate(10);
                    $matched_result_txt = ' Matched Records';
                }else{
                    $filter_user_logs_table = DB::table('users_activity_tbl')
                                            ->join('users', 'users_activity_tbl.act_respo_user_id', '=', 'users.id')
                                            ->select('users_activity_tbl.*', 'users.id', 'users.user_role', 'users.user_status', 'users.user_role_status', 'users.user_type', 'users.user_sdca_id', 'users.user_image', 'users.user_gender')
                                            ->where(function($query) use ($logs_userTypes, $logs_userRoles, $logs_users, $logs_category, $logs_rangefrom, $logs_rangeTo){
                                                if($logs_userTypes != 0 OR !empty($logs_userTypes)){
                                                    $query->where('users.user_type', '=', $logs_userTypes);
                                                }
                                                if($logs_userRoles != 0 OR !empty($logs_userRoles)){
                                                    $query->where('users.user_role', '=', $logs_userRoles);
                                                }
                                                if($logs_users != 0 OR !empty($logs_users)){
                                                    $query->where('users.id', '=', $logs_users);
                                                }
                                                if($logs_category != 0 OR !empty($logs_category)){
                                                    $query->where('users_activity_tbl.act_type', '=', $logs_category);
                                                }
                                                if($logs_rangefrom != 0 OR !empty($logs_rangefrom) AND $logs_rangeTo != 0 OR !empty($logs_rangeTo)){
                                                    $query->whereBetween('users_activity_tbl.created_at', [$logs_rangefrom, $logs_rangeTo]);
                                                }
                                            })
                                            ->orderBy('users_activity_tbl.created_at', 'DESC')
                                            ->paginate(10);
                    $matched_result_txt = ' Record';
                }
                // total filtered date
                $count_filtered_result = count($filter_user_logs_table);
                $total_filtered_result = $filter_user_logs_table->total();
                // plural text
                if($total_filtered_result > 0){
                    if($total_filtered_result > 1){
                        $s = 's';
                    }else{
                        $s = '';
                    }
                    $total_matched_results = $filter_user_logs_table->firstItem() . ' - ' . $filter_user_logs_table->lastItem() . ' of ' . $total_filtered_result . ' ' . $matched_result_txt.''.$s;
                }else{
                    $s = '';
                    $total_matched_results = 'No Records Found';
                }
                if($count_filtered_result > 0){
                    foreach($filter_user_logs_table as $users_logs){
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
                                    <span class="actLogs_content">'.preg_replace('/('.$logs_search.')/i','<span class="grn_highlight2">$1</span>', date('F d, Y', strtotime($users_logs->created_at))) . '</span>
                                    <span class="actLogs_tdSubTitle sub2">'.preg_replace('/('.$logs_search.')/i','<span class="grn_highlight2">$1</span>', date('D', strtotime($users_logs->created_at))) . ' - '.preg_replace('/('.$logs_search.')/i','<span class="grn_highlight2">$1</span>', date('g:i A', strtotime($users_logs->created_at))) . '</span>
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
                                    <span class="font-italic">No Records Found!
                                </div>
                            </td>
                        </tr>
                    ';
                }
                $paginate .= $filter_user_logs_table->links('pagination::bootstrap-4');
                $data = array(
                    'users_logs_table' => $output,
                    'paginate'         => $paginate,
                    'total_rows'       => $total_matched_results,
                    'total_data_found' => $total_filtered_result
                   );
             
                echo json_encode($data);
            }else{
                return view('user_management.users_logs');
            }
        }else{
            return view('profile.access_denied');
        }
    }

    // emailavailability check for creating new user
    public function new_user_email_availability_check(Request $request){
        if($request->get('email')){
            $email = $request->get('email');
            $data  = Users::where('email', $email)->count();
            if($data > 0){
                echo 'not_unique';
            }else{
                echo 'unique';
            }
        }
    }

    // emailavailability check for switching to new email - profile update module
    // for student user
    public function stud_user_switch_new_email_availability_check(Request $request){
        if($request->get('stud_email')){
            $stud_id    = $request->get('stud_id');
            $stud_email = $request->get('stud_email');
            // get user's original email
            $get_stud_org_email = Users::select('id', 'email')->where('id', $stud_id)->first();
            $stud_org_email     = $get_stud_org_email->email;
            
            if($stud_org_email === $stud_email){
                echo 'unique';
            }else{
                $data = Users::where('email', $stud_email)->whereNotIn('id', [$stud_id])->count();
                if($data > 0){
                    echo 'not_unique';
                }else{
                    echo 'unique';
                }
            }
        }
    }
    // for employee user
    public function emp_user_switch_new_email_availability_check(Request $request){
        if($request->get('emp_email')){
            $emp_id    = $request->get('emp_id');
            $emp_email = $request->get('emp_email');
            // get user's original email
            $get_emp_org_email = Users::select('id', 'email')->where('id', $emp_id)->first();
            $emp_org_email     = $get_emp_org_email->email;
            
            if($emp_org_email === $emp_email){
                echo 'unique';
            }else{
                $data = Users::where('email', $emp_email)->whereNotIn('id', [$emp_id])->count();
                if($data > 0){
                    echo 'not_unique';
                }else{
                    echo 'unique';
                }
            }
        }
    }

    // process registration of new employee type user
    public function new_employee_user_process_registration(Request $request){
        $this->validate($request, [
            'email' => 'email',
        ]);
        // get all request
            $create_emp_role      = $request->get('create_emp_role');
            $create_emp_id        = $request->get('create_emp_id');
            $create_emp_lname     = $request->get('create_emp_lname');
            $create_emp_fname     = $request->get('create_emp_fname');
            $create_emp_gender    = $request->get('create_emp_gender');
            $create_emp_jobdesc   = $request->get('create_emp_jobdesc');
            $create_emp_dept      = $request->get('create_emp_dept');
            $create_emp_phnum     = $request->get('create_emp_phnum');
            $create_emp_email     = $request->get('create_emp_email');
            $get_respo_user_id    = $request->get('respo_user_id');
            $get_respo_user_lname = $request->get('respo_user_lname');
            $get_respo_user_fname = $request->get('respo_user_fname');
        // custom values
            $now_timestamp        = now();
            $active_txt           = 'active';
            $employee_txt         = 'employee';
            $employee_image       = 'employee_user_image.jpg';
            $format_now_timestamp = $now_timestamp->format('dmYHis');
            $get_current_year     = $now_timestamp->format('Y');
            $lower_emp_role       = Str::lower($create_emp_role);
            $lower_emp_gender     = Str::lower($create_emp_gender);
        // user image handler
            if($request->hasFile('create_emp_user_image')){
                $get_filenameWithExt = $request->file('create_emp_user_image')->getClientOriginalName();
                $get_justFile        = pathinfo($get_filenameWithExt, PATHINFO_FILENAME);
                $get_justExt         = $request->file('create_emp_user_image')->getClientOriginalExtension();
                $fileNameToStore     = $get_justFile.'_'.$format_now_timestamp.'.'.$get_justExt;
                $uploadImageToPath   = $request->file('create_emp_user_image')->storeAs('public/svms/user_images',$fileNameToStore);
            }else{
                $fileNameToStore = $employee_image;
            }
        // generate unique password
            $create_emp_password = Str::lower($create_emp_lname).'@svms'.$get_current_year;
        // get the status of selected system role
            $get_status_selected_role = Userroles::select('uRole_status')->where('uRole', $lower_emp_role)->first();
            $status_of_selected_role  = $get_status_selected_role->uRole_status;
        // save data to users table
            $reg_emp_user = new Users;
            $reg_emp_user->email             = $create_emp_email;
            $reg_emp_user->email_verified_at = $now_timestamp;
            $reg_emp_user->password          = Hash::make($create_emp_password);
            $reg_emp_user->user_role         = $lower_emp_role;
            $reg_emp_user->user_status       = $active_txt;
            $reg_emp_user->user_role_status  = $status_of_selected_role;
            $reg_emp_user->user_type         = $employee_txt;
            $reg_emp_user->user_sdca_id      = $create_emp_id;
            $reg_emp_user->user_image        = $fileNameToStore;
            $reg_emp_user->user_lname        = $create_emp_lname;
            $reg_emp_user->user_fname        = $create_emp_fname;
            $reg_emp_user->user_gender       = $lower_emp_gender;
            $reg_emp_user->registered_by     = $get_respo_user_id;
            $reg_emp_user->created_at        = $now_timestamp;
            $reg_emp_user->save();
        // save data to user_employees_tbl table
            $reg_emp_info = new Useremployees;
            $reg_emp_info->uEmp_id       = $create_emp_id;
            $reg_emp_info->uEmp_job_desc = $create_emp_jobdesc;
            $reg_emp_info->uEmp_dept     = $create_emp_dept;
            $reg_emp_info->uEmp_phnum    = $create_emp_phnum;
            $reg_emp_info->created_at    = $now_timestamp;
            $reg_emp_info->save();
        // if registration was a success
        if($reg_emp_user AND $reg_emp_info){
            // get new user's id for activity reference
                $get_new_emp_user_id = Users::select('id')->where('user_sdca_id', $create_emp_id)->latest('created_at')->first();
                $new_reg_user_id     = $get_new_emp_user_id->id;
            // get current number of assigned users for selected role
                $get_sel_role_assUsers_count = Userroles::select('uRole_id', 'uRole', 'assUsers_count')->where('uRole', $lower_emp_role)->first();
                $get_sel_uRole_id            = $get_sel_role_assUsers_count->uRole_id;
                $get_current_count_assUsers  = $get_sel_role_assUsers_count->assUsers_count;
                // add 1
                $add_1_assUsers_count        = $get_current_count_assUsers + 1;
                // update assUsers_count from userroles_tbl
                $update_assUsers_count_n = DB::table('user_roles_tbl')
                            ->where('uRole_id', $get_sel_uRole_id)
                            ->update([
                                'assUsers_count' => $add_1_assUsers_count,
                                'updated_at'     => $now_timestamp
                            ]);
            if($update_assUsers_count_n){
                // record activity
                $record_act = new Useractivites;
                $record_act->created_at            = $now_timestamp;
                $record_act->act_respo_user_id     = $get_respo_user_id;
                $record_act->act_respo_users_lname = $get_respo_user_lname;
                $record_act->act_respo_users_fname = $get_respo_user_fname;
                $record_act->act_type              = 'create user';
                $record_act->act_details           = 'Registered ' .$create_emp_fname. ' ' .$create_emp_lname. ' as a ' .$create_emp_role. ' of the system.';
                $record_act->act_affected_id       = $new_reg_user_id;
                $record_act->save();
                if($record_act){
                    return back()->withSuccessStatus('New Employee User Account was registered successfully!');
                }else{
                    return back()->withFailedStatus('New Employee User Account has failed to register. Try again later.');
                }
            }else{
                return back()->withFailedStatus('New Employee User Account has failed to register. Try again later.');
            }
        }else{
            return back()->withFailedStatus('New Employee User Account has failed to register. Try again later.');
        }
        // echo 'REGISTER NEW EMPLOYEE USER <br />';
        // echo 'System Role: ' .$create_emp_role. ' <br />';
        // echo 'System Role Status: ' .$status_of_selected_role. ' <br />';
        // echo 'Employee ID: ' .$create_emp_id. ' <br />';
        // echo 'image: ' .$fileNameToStore. ' <br />';
        // echo 'Last Name: ' .$create_emp_lname. ' <br />';
        // echo 'First Name: ' .$create_emp_fname. ' <br />';
        // echo 'Gender: ' .$lower_emp_gender. ' <br />';
        // echo 'Job Description: ' .$create_emp_jobdesc. ' <br />';
        // echo 'Department: ' .$create_emp_dept. ' <br />';
        // echo 'Phone Number: ' .$create_emp_phnum. ' <br />';
        // echo 'email: ' .$create_emp_email. ' <br />';
        // echo 'password: ' .$create_emp_password. ' <br />';
        // echo '<br />';
        // echo 'by: ' .$get_respo_user_id. ' <br />';
        // echo 'lname: ' .$get_respo_user_lname. ' <br />';
        // echo 'fname: ' .$get_respo_user_fname. ' <br />';
    }
    // process registration of new student type user
    public function new_student_user_process_registration(Request $request){
        // get all request
            $create_stud_role     = $request->get('create_stud_role');
            $create_stud_id       = $request->get('create_stud_id');
            $create_stud_lname    = $request->get('create_stud_lname');
            $create_stud_fname    = $request->get('create_stud_fname');
            $create_stud_gender   = $request->get('create_stud_gender');
            $create_stud_school   = $request->get('create_stud_school');
            $create_stud_program  = $request->get('create_stud_program');
            $create_stud_yearlvl  = $request->get('create_stud_yearlvl');
            $create_stud_section  = $request->get('create_stud_section');
            $create_stud_phnum    = $request->get('create_stud_phnum');
            $create_stud_email    = $request->get('create_stud_email');
            $get_respo_user_id    = $request->get('respo_user_id');
            $get_respo_user_lname = $request->get('respo_user_lname');
            $get_respo_user_fname = $request->get('respo_user_fname');

        // custom values
            $now_timestamp        = now();
            $active_txt           = 'active';
            $student_txt          = 'student';
            $employee_image       = 'student_user_image.jpg';
            $format_now_timestamp = $now_timestamp->format('dmYHis');
            $get_current_year     = $now_timestamp->format('Y');
            $lower_stud_role      = Str::lower($create_stud_role);
            $lower_stud_gender    = Str::lower($create_stud_gender);
        
        // user image handler
            if($request->hasFile('create_stud_user_image')){
                $get_filenameWithExt = $request->file('create_stud_user_image')->getClientOriginalName();
                $get_justFile        = pathinfo($get_filenameWithExt, PATHINFO_FILENAME);
                $get_justExt         = $request->file('create_stud_user_image')->getClientOriginalExtension();
                $fileNameToStore     = $get_justFile.'_'.$format_now_timestamp.'.'.$get_justExt;
                $uploadImageToPath   = $request->file('create_stud_user_image')->storeAs('public/svms/user_images',$fileNameToStore);
            }else{
                $fileNameToStore = $employee_image;
            }

        // generate unique password
            $create_stud_password = Str::lower($create_stud_lname).'@svms'.$get_current_year;

        // get the status of selected system role
            $get_status_selected_role = Userroles::select('uRole_status')->where('uRole', $lower_stud_role)->first();
            $status_of_selected_role  = $get_status_selected_role->uRole_status;


        // save data to users table
            $reg_stud_user = new Users;
            $reg_stud_user->email             = $create_stud_email;
            $reg_stud_user->email_verified_at = $now_timestamp;
            $reg_stud_user->password          = Hash::make($create_stud_password);
            $reg_stud_user->user_role         = $lower_stud_role;
            $reg_stud_user->user_status       = $active_txt;
            $reg_stud_user->user_role_status  = $status_of_selected_role;
            $reg_stud_user->user_type         = $student_txt;
            $reg_stud_user->user_sdca_id      = $create_stud_id;
            $reg_stud_user->user_image        = $fileNameToStore;
            $reg_stud_user->user_lname        = $create_stud_lname;
            $reg_stud_user->user_fname        = $create_stud_fname;
            $reg_stud_user->user_gender       = $lower_stud_gender;
            $reg_stud_user->registered_by     = $get_respo_user_id;
            $reg_stud_user->created_at        = $now_timestamp;
            $reg_stud_user->save();
        // save data to user_employees_tbl table
            $reg_stud_info = new Userstudents;
            $reg_stud_info->uStud_num     = $create_stud_id;
            $reg_stud_info->uStud_school  = $create_stud_school;
            $reg_stud_info->uStud_program = $create_stud_program;
            $reg_stud_info->uStud_yearlvl = $create_stud_yearlvl;
            $reg_stud_info->uStud_section = $create_stud_section;
            $reg_stud_info->uStud_phnum   = $create_stud_phnum;
            $reg_stud_info->created_at    = $now_timestamp;
            $reg_stud_info->save();

        // if registration was a success
        if($reg_stud_user AND $reg_stud_info){
            // get new user's id for activity reference
                $get_new_stud_user_id = Users::select('id')->where('user_sdca_id', $create_stud_id)->latest('created_at')->first();
                $new_reg_user_id     = $get_new_stud_user_id->id;

            // get current number of assigned users for selected role
            $get_sel_role_assUsers_count = Userroles::select('uRole_id', 'uRole', 'assUsers_count')->where('uRole', $lower_stud_role)->first();
            $get_sel_uRole_id            = $get_sel_role_assUsers_count->uRole_id;
            $get_current_count_assUsers  = $get_sel_role_assUsers_count->assUsers_count;
            // add 1
            $add_1_assUsers_count        = $get_current_count_assUsers + 1;
            // update assUsers_count from userroles_tbl
            $update_assUsers_count_n = DB::table('user_roles_tbl')
                        ->where('uRole_id', $get_sel_uRole_id)
                        ->update([
                            'assUsers_count' => $add_1_assUsers_count,
                            'updated_at'     => $now_timestamp
                        ]);

            if($update_assUsers_count_n){
                // record activity
                $record_act = new Useractivites;
                $record_act->created_at            = $now_timestamp;
                $record_act->act_respo_user_id     = $get_respo_user_id;
                $record_act->act_respo_users_lname = $get_respo_user_lname;
                $record_act->act_respo_users_fname = $get_respo_user_fname;
                $record_act->act_type              = 'create user';
                $record_act->act_details           = 'Registered ' .$create_stud_fname. ' ' .$create_stud_lname. ' as a ' .$create_stud_role. ' of the system.';
                $record_act->act_affected_id       = $new_reg_user_id;
                $record_act->save();
            
                if($record_act){
                    return back()->withSuccessStatus('New Student User Account was registered successfully!');
                }else{
                    return back()->withFailedStatus('New Student User Account has failed to register. Try again later.');
                }
            }else{
                return back()->withFailedStatus('New Student User Account has failed to register. Try again later.');
            }
        }else{
            return back()->withFailedStatus('New Student User Account has failed to register. Try again later.');
        }

        // test fetch request data
            // echo 'REGISTER NEW STUDENT USER <br />';
            // echo 'System Role: ' .$create_stud_role. ' <br />';
            // echo 'System Role Status: ' .$status_of_selected_role. ' <br />';
            // echo 'Student Number: ' .$create_stud_id. ' <br />';
            // echo 'image: ' .$fileNameToStore. ' <br />';
            // echo 'Last Name: ' .$create_stud_lname. ' <br />';
            // echo 'First Name: ' .$create_stud_fname. ' <br />';
            // echo 'Gender: ' .$lower_stud_gender. ' <br />';
            // echo 'School: ' .$create_stud_school. ' <br />';
            // echo 'Program: ' .$create_stud_program. ' <br />';
            // echo 'Year Level: ' .$create_stud_yearlvl. ' <br />';
            // echo 'Section: ' .$create_stud_section. ' <br />';
            // echo 'Phone Number: ' .$create_stud_phnum. ' <br />';
            // echo 'email: ' .$create_stud_email. ' <br />';
            // echo 'password: ' .$create_stud_password. ' <br />';
            // echo '<br />';
            // echo 'by: ' .$get_respo_user_id. ' <br />';
            // echo 'lname: ' .$get_respo_user_lname. ' <br />';
            // echo 'fname: ' .$get_respo_user_fname. ' <br />';
    }

    // FUNCTIONS FOR UPDATING SYSTEM USER's ACCOUNTS
    // update student user's account
    public function update_stud_user_profile(Request $request){
        // now timestamp
            $now_timestamp  = now();
            $format_now_timestamp = $now_timestamp->format('dmYHis');
        // get responsible user info for updating this record
            $get_respo_user_id    = $request->get('respo_user_id');
            $get_respo_user_lname = $request->get('respo_user_lname');
            $get_respo_user_fname = $request->get('respo_user_fname');
        // get responsible user's gender 
            $get_respo_user_gender_info = Users::select('id', 'user_gender')->where('id', $get_respo_user_id)->first();
            $get_respo_user_gender      = $get_respo_user_gender_info->user_gender;
        // get all request
            $get_upd_stud_user_image = $request->file('upd_stud_user_image');
            $get_selected_userId     = $request->get('selected_user_id');
            $get_upd_studEmail       = $request->get('upd_stud_email');
            $get_upd_studNum         = $request->get('upd_stud_num');
            $get_upd_studLname       = $request->get('upd_stud_lname');
            $get_upd_studFname       = $request->get('upd_stud_fname');
            $get_upd_studGender      = $request->get('upd_stud_gender');
            $get_upd_studSchool      = $request->get('upd_stud_school');
            $get_upd_studProgram     = $request->get('upd_stud_program');
            $get_upd_studYearlvl     = $request->get('upd_stud_yearlvl');
            $get_upd_studSection     = $request->get('upd_stud_section');
            $get_upd_studPhnum       = $request->get('upd_stud_phnum');
        // get user's original info
            $fetch_original_user = Users::where('id' , $get_selected_userId)->first();
            $stud_orgEmail       = $fetch_original_user->email;
            $stud_orgStudNum     = $fetch_original_user->user_sdca_id;
            $stud_orgImage       = $fetch_original_user->user_image;
            $stud_orgLname       = $fetch_original_user->user_lname;
            $stud_orgFname       = $fetch_original_user->user_fname;
            $stud_orgGender      = $fetch_original_user->user_gender;
            $stud_orgRole        = $fetch_original_user->user_role;
            $stud_orgType        = $fetch_original_user->user_type;

            $fetch_original_stud = Userstudents::where('uStud_num', $stud_orgStudNum)->first();
            $stud_orgSchool      = $fetch_original_stud->uStud_school;
            $stud_orgProgram     = $fetch_original_stud->uStud_program;
            $stud_orgYearlvl     = $fetch_original_stud->uStud_yearlvl;
            $stud_orgSection     = $fetch_original_stud->uStud_section;
            $stud_orgPhnum       = $fetch_original_stud->uStud_phnum;
        // his/her & Mr./Ms. format and apostrophe
            $old_user_gender = Str::lower($stud_orgGender);
            $new_user_gender = Str::lower($get_upd_studGender);
            if($old_user_gender == 'male'){
                $userGenderTxt = 'his';
                $user_mr_ms   = 'Mr.';
            }elseif($old_user_gender == 'female'){
                $userGenderTxt = 'her';
                $user_mr_ms   = 'Ms.';
            }else{
                $userGenderTxt = 'his/her';
                $user_mr_ms   = 'Mr./Ms.';
            }
            if($get_respo_user_gender === 'female'){
                $respo_his_her = 'her';
                $respo_mr_ms   = 'Ms.';
            }elseif($get_respo_user_gender === 'male'){
                $respo_his_her = 'his';
                $respo_mr_ms   = 'Mr.';
            }else{
                $respo_his_her = 'his/her';
                $respo_mr_ms   = 'Mr./Ms.';
            }
            $s_s = "'";
        // user image update handler
            if($request->hasFile('upd_stud_user_image')){
                $get_filenameWithExt = $request->file('upd_stud_user_image')->getClientOriginalName();
                $get_justFile        = pathinfo($get_filenameWithExt, PATHINFO_FILENAME);
                $get_justExt         = $request->file('upd_stud_user_image')->getClientOriginalExtension();
                $fileNameToStore     = $get_justFile.'_'.$format_now_timestamp.'.'.$get_justExt;
                // $uploadImageToPath   = $request->file('upd_stud_user_image')->storeAs('public/storage/svms/user_images',$fileNameToStore);
            }else{
                $fileNameToStore = $stud_orgImage;
            }
        // update record from users table
            $update_users_tbl = DB::table('users')
                ->where('id', $get_selected_userId)
                ->update([
                    'email'        => $get_upd_studEmail,
                    'user_sdca_id' => $get_upd_studNum,
                    'user_image'   => $fileNameToStore,
                    'user_lname'   => $get_upd_studLname,
                    'user_fname'   => $get_upd_studFname,
                    'user_gender'  => $new_user_gender,
                    'updated_at'   => $now_timestamp
                    ]);
        // if update was successful
            if($update_users_tbl){
            // update user_students_tbl
                $update_users_tbl = DB::table('user_students_tbl')
                    ->where('uStud_num', $stud_orgStudNum)
                    ->update([
                        'uStud_num'     => $get_upd_studNum,
                        'uStud_school'  => $get_upd_studSchool,
                        'uStud_program' => $get_upd_studProgram,
                        'uStud_yearlvl' => $get_upd_studYearlvl,
                        'uStud_section' => $get_upd_studSection,
                        'uStud_phnum'   => $get_upd_studPhnum
                        ]);
            // store uploaded image to public/storage/svms/user_images
                if($request->hasFile('upd_stud_user_image')){
                    $destinationPath   = public_path('/storage/svms/user_images');
                    $uploadImageToPath = $request->file('upd_stud_user_image')->move($destinationPath,$fileNameToStore);
                }
            // record original user's info to edited_old_stud_users_tbl
                $rec_orginalUserInfo = new Editedoldstudentusers;
                $rec_orginalUserInfo->from_user_id     = $get_selected_userId;
                $rec_orginalUserInfo->eOld_uRole       = $stud_orgRole;
                $rec_orginalUserInfo->eOld_email       = $stud_orgEmail;
                $rec_orginalUserInfo->eOld_user_type   = $stud_orgType;
                $rec_orginalUserInfo->eOld_user_image  = $stud_orgImage;
                $rec_orginalUserInfo->eOld_user_lname  = $stud_orgLname;
                $rec_orginalUserInfo->eOld_user_fname  = $stud_orgFname;
                $rec_orginalUserInfo->eOld_user_gender = $old_user_gender;
                $rec_orginalUserInfo->eOld_sdca_id     = $stud_orgStudNum;
                $rec_orginalUserInfo->eOld_school      = $stud_orgSchool;
                $rec_orginalUserInfo->eOld_program     = $stud_orgProgram;
                $rec_orginalUserInfo->eOld_yearlvl     = $stud_orgYearlvl;
                $rec_orginalUserInfo->eOld_section     = $stud_orgSection;
                $rec_orginalUserInfo->eOld_phnum       = $stud_orgPhnum;
                $rec_orginalUserInfo->respo_user_id    = $get_selected_userId;
                $rec_orginalUserInfo->edited_at        = $now_timestamp;
                $rec_orginalUserInfo->save();
            // get id from latest update on edited_old_stud_users_tbl
                $get_eOldStud_id  = Editedoldstudentusers::select('eOldStud_id')->where('from_user_id', $get_selected_userId)->latest('edited_at')->first();
                $from_eOldStud_id = $get_eOldStud_id->eOldStud_id;
            // record new user's info to edited_new_emp_users_tbl
                $rec_newStudInfo = new Editednewstudentusers;
                $rec_newStudInfo->from_eOldStud_id = $from_eOldStud_id;
                $rec_newStudInfo->eNew_email       = $get_upd_studEmail;
                $rec_newStudInfo->eNew_uRole       = $stud_orgRole;
                $rec_newStudInfo->eNew_user_type   = $stud_orgType;
                $rec_newStudInfo->eNew_user_image  = $fileNameToStore;
                $rec_newStudInfo->eNew_user_lname  = $get_upd_studLname;
                $rec_newStudInfo->eNew_user_fname  = $get_upd_studFname;
                $rec_newStudInfo->eNew_user_gender = $new_user_gender;
                $rec_newStudInfo->eNew_sdca_id     = $get_upd_studNum;
                $rec_newStudInfo->eNew_school      = $get_upd_studSchool;
                $rec_newStudInfo->eNew_program     = $get_upd_studProgram;
                $rec_newStudInfo->eNew_yearlvl     = $get_upd_studYearlvl;
                $rec_newStudInfo->eNew_section     = $get_upd_studSection;
                $rec_newStudInfo->eNew_phnum       = $get_upd_studPhnum;
                $rec_newStudInfo->edited_at        = $now_timestamp;
                $rec_newStudInfo->save();
            // record activity
                $rec_activity = new Useractivites;
                $rec_activity->created_at            = $now_timestamp;
                $rec_activity->act_respo_user_id     = $get_respo_user_id;
                $rec_activity->act_respo_users_lname = $get_respo_user_lname;
                $rec_activity->act_respo_users_fname = $get_respo_user_fname;
                $rec_activity->act_type              = 'profile update';
                $rec_activity->act_details           = $get_respo_user_fname. ' ' .$get_respo_user_lname . ' Updated ' . $stud_orgFname . ' ' . $stud_orgLname.''.$s_s.'s Profile.';
                $rec_activity->act_affected_id       = $from_eOldStud_id;
                $rec_activity->save();
            // send email
                $details = [
                    'svms_logo'           => "storage/svms/logos/svms_logo_text.png",
                    'title'               => 'PROFILE UPDATE',
                    'recipient'           => $user_mr_ms . ' ' .$stud_orgFname . ' ' . $stud_orgLname,
                    'responsible_user'    => $respo_mr_ms . ' ' .$get_respo_user_fname . ' ' . $get_respo_user_lname,
                    'date_of_changes'     => $now_timestamp
                ];
                $old_profile = [
                    'user_image'      => 'storage/svms/user_images/'.$stud_orgImage,
                    'user_type'       => $stud_orgType,
                    'user_email'      => $stud_orgEmail,
                    'user_role'       => $stud_orgRole,
                    'user_sdca_id'    => $stud_orgStudNum,
                    'user_first_name' => $stud_orgFname,
                    'user_last_name'  => $stud_orgLname,
                    'user_gender'     => $old_user_gender,
                    'user_school'     => $stud_orgSchool,
                    'user_program'    => $stud_orgProgram,
                    'user_yrlvl'      => $stud_orgYearlvl,
                    'user_section'    => $stud_orgSection,
                    'user_phnum'      => $stud_orgPhnum,
                ];
                $new_profile = [
                    'user_image'      => 'storage/svms/user_images/'.$fileNameToStore,
                    'user_type'       => $stud_orgType,
                    'user_email'      => $get_upd_studEmail,
                    'user_role'       => $stud_orgRole,
                    'user_sdca_id'    => $get_upd_studNum,
                    'user_first_name' => $get_upd_studFname,
                    'user_last_name'  => $get_upd_studLname,
                    'user_gender'     => $new_user_gender,
                    'user_school'     => $get_upd_studSchool,
                    'user_program'    => $get_upd_studProgram,
                    'user_yrlvl'      => $get_upd_studYearlvl,
                    'user_section'    => $get_upd_studSection,
                    'user_phnum'      => $get_upd_studPhnum,
                ];
                // if user has email
                    if(!empty($stud_orgEmail)){
                        // notify user from his/her old email
                        \Mail::to('mfodesierto2@gmail.com')->send(new \App\Mail\ProfileUpdateSendMail($details, $old_profile ,$new_profile));
                        // notify user from his page when currently logged in

                        if(!empty($get_upd_studEmail)){
                            if($stud_orgEmail !== $get_upd_studEmail){
                                // deactivate account for switching to new email
                                    // $update_users_tbl = DB::table('users')
                                    // ->where('id', $get_selected_userId)
                                    // ->update([
                                    //     'user_status'  => 'deactivated',
                                    //     'updated_at'   => $now_timestamp
                                    //     ]);
                                // record status update to user_status_updates_tbl
                                    // if($stud_orgRole !== 'deactivated'){
                                    //     $rec_user_stats_update_tbl = new Userupdatesstatus;
                                    //     $rec_user_stats_update_tbl->from_user_id   = $get_selected_userId;
                                    //     $rec_user_stats_update_tbl->updated_status = 'deactivated';
                                    //     $rec_user_stats_update_tbl->reason_update  = 'switching to a new email address';
                                    //     $rec_user_stats_update_tbl->updated_at     = $now_timestamp;
                                    //     $rec_user_stats_update_tbl->updated_by     = $get_respo_user_id;
                                    //     $rec_user_stats_update_tbl->save();
                                    // }
                                    $rec_user_stats_update_tbl = new Userupdatesstatus;
                                    $rec_user_stats_update_tbl->from_user_id   = $get_selected_userId;
                                    $rec_user_stats_update_tbl->updated_status = 'active';
                                    $rec_user_stats_update_tbl->reason_update  = 'switching to a new email address';
                                    $rec_user_stats_update_tbl->updated_at     = $now_timestamp;
                                    $rec_user_stats_update_tbl->updated_by     = $get_respo_user_id;
                                    $rec_user_stats_update_tbl->save();
                                // notify user that this new email has been registered as a user of SVMS
                                    \Mail::to($get_upd_studEmail)->send(new \App\Mail\ProfileUpdateNewEmailSendMail($details, $old_profile ,$new_profile));
                                // logged out user form the system with notification mesasge to check his/her old email

                            }
                        }
                    }
            return back()->withSuccessStatus(''.$stud_orgFname . ' '. $stud_orgLname.''.$s_s.'s Account was updated successfully.');
        }else{
            return back()->withFailedStatus(''.$stud_orgFname . ' '. $stud_orgLname.''.$s_s.'s Account Update has failed, Try again  later.');
        }
    }
    // update employee user's account
    public function update_emp_user_profile(Request $request){
        // now timestamp
            $now_timestamp  = now();
            $format_now_timestamp = $now_timestamp->format('dmYHis');
        // get responsible user info for updating this record
            $get_respo_user_id    = $request->get('respo_user_id');
            $get_respo_user_lname = $request->get('respo_user_lname');
            $get_respo_user_fname = $request->get('respo_user_fname');
        // get responsible user's gender 
            $get_respo_user_gender_info = Users::select('id', 'user_gender')->where('id', $get_respo_user_id)->first();
            $get_respo_user_gender      = $get_respo_user_gender_info->user_gender;
        // get all request
            $get_upd_emp_user_image = $request->file('upd_emp_user_image');
            $get_selected_userId    = $request->get('selected_user_id');
            $get_upd_empEmail       = $request->get('upd_emp_email');
            $get_upd_empID          = $request->get('upd_emp_id');
            $get_upd_empLname       = $request->get('upd_emp_lname');
            $get_upd_empFname       = $request->get('upd_emp_fname');
            $get_upd_empGender      = $request->get('upd_emp_gender');
            $get_upd_empJobDesc     = $request->get('upd_emp_jobdesc');
            $get_upd_empDept        = $request->get('upd_emp_dept');
            $get_upd_empPhnum       = $request->get('upd_emp_phnum');
        // get user's original info
            $fetch_original_user = Users::where('id' , $get_selected_userId)->first();
            $emp_orgEmail        = $fetch_original_user->email;
            $emp_orgEmpID        = $fetch_original_user->user_sdca_id;
            $emp_orgImage        = $fetch_original_user->user_image;
            $emp_orgLname        = $fetch_original_user->user_lname;
            $emp_orgFname        = $fetch_original_user->user_fname;
            $emp_orgGender       = $fetch_original_user->user_gender;
            $emp_orgRole         = $fetch_original_user->user_role;
            $emp_orgType         = $fetch_original_user->user_type;

            $fetch_original_emp = Useremployees::where('uEmp_id', $emp_orgEmpID)->first();
            $emp_orgJobDesc     = $fetch_original_emp->uEmp_job_desc;
            $emp_orgDept        = $fetch_original_emp->uEmp_dept;
            $emp_orgPhnum       = $fetch_original_emp->uEmp_phnum;
        // his/her & Mr./Ms. format and apostrophe
            $old_user_gender = Str::lower($emp_orgGender);
            $new_user_gender = Str::lower($get_upd_empGender);
            if($old_user_gender == 'male'){
                $userGenderTxt = 'his';
                $user_mr_ms   = 'Mr.';
            }elseif($old_user_gender == 'female'){
                $userGenderTxt = 'her';
                $user_mr_ms   = 'Ms.';
            }else{
                $userGenderTxt = 'his/her';
                $user_mr_ms   = 'Mr./Ms.';
            }
            if($get_respo_user_gender === 'female'){
                $respo_his_her = 'her';
                $respo_mr_ms   = 'Ms.';
            }elseif($get_respo_user_gender === 'male'){
                $respo_his_her = 'his';
                $respo_mr_ms   = 'Mr.';
            }else{
                $respo_his_her = 'his/her';
                $respo_mr_ms   = 'Mr./Ms.';
            }
            $s_s = "'";
            // user image update handler
                if($request->hasFile('upd_emp_user_image')){
                    $get_filenameWithExt = $request->file('upd_emp_user_image')->getClientOriginalName();
                    $get_justFile        = pathinfo($get_filenameWithExt, PATHINFO_FILENAME);
                    $get_justExt         = $request->file('upd_emp_user_image')->getClientOriginalExtension();
                    $fileNameToStore     = $get_justFile.'_'.$format_now_timestamp.'.'.$get_justExt;
                    // $uploadImageToPath   = $request->file('upd_emp_user_image')->storeAs('public/storage/svms/user_images',$fileNameToStore);
                }else{
                    $fileNameToStore = $emp_orgImage;
                }
            // update record from users table
                $update_users_tbl = DB::table('users')
                    ->where('id', $get_selected_userId)
                    ->update([
                        'email'        => $get_upd_empEmail,
                        'user_sdca_id' => $get_upd_empID,
                        'user_image'   => $fileNameToStore,
                        'user_lname'   => $get_upd_empLname,
                        'user_fname'   => $get_upd_empFname,
                        'user_gender'  => $new_user_gender,
                        'updated_at'   => $now_timestamp
                        ]);
            // if update was successful
                if($update_users_tbl){
                    // update user_employees_tbl
                        $update_users_tbl = DB::table('user_employees_tbl')
                            ->where('uEmp_id', $emp_orgEmpID)
                            ->update([
                                'uEmp_id'     => $get_upd_empID,
                                'uEmp_job_desc'  => $get_upd_empJobDesc,
                                'uEmp_dept' => $get_upd_empDept,
                                'uEmp_phnum' => $get_upd_empPhnum
                                ]);
                    // store uploaded image to public/storage/svms/user_images
                        if($request->hasFile('upd_emp_user_image')){
                            $destinationPath   = public_path('/storage/svms/user_images');
                            $uploadImageToPath = $request->file('upd_emp_user_image')->move($destinationPath,$fileNameToStore);
                        }
                    // record original user's info to edited_old_emp_users_tbl
                        $rec_orginalUserInfo = new Editedolduseremployees;
                        $rec_orginalUserInfo->from_user_id     = $get_selected_userId;
                        $rec_orginalUserInfo->eOld_uRole       = $emp_orgRole;
                        $rec_orginalUserInfo->eOld_email       = $emp_orgEmail;
                        $rec_orginalUserInfo->eOld_user_type   = $emp_orgType;
                        $rec_orginalUserInfo->eOld_user_image  = $emp_orgImage;
                        $rec_orginalUserInfo->eOld_user_lname  = $emp_orgLname;
                        $rec_orginalUserInfo->eOld_user_fname  = $emp_orgFname;
                        $rec_orginalUserInfo->eOld_user_gender = $old_user_gender;
                        $rec_orginalUserInfo->eOld_sdca_id     = $emp_orgEmpID;
                        $rec_orginalUserInfo->eOld_job_desc    = $emp_orgJobDesc;
                        $rec_orginalUserInfo->eOld_dept        = $emp_orgDept;
                        $rec_orginalUserInfo->eOld_phnum       = $emp_orgPhnum;
                        $rec_orginalUserInfo->respo_user_id    = $get_selected_userId;
                        $rec_orginalUserInfo->edited_at        = $now_timestamp;
                        $rec_orginalUserInfo->save();
                    // get id from latest update on edited_old_emp_users_tbl
                        $get_eOldEmp_id  = Editedolduseremployees::select('eOldEmp_id')->where('from_user_id', $get_selected_userId)->latest('edited_at')->first();
                        $from_eOldEmp_id = $get_eOldEmp_id->eOldEmp_id;
                    // record new user's info to edited_new_emp_users_tbl
                        $rec_newStudInfo = new Editednewuseremployees;
                        $rec_newStudInfo->from_eOldEmp_id  = $from_eOldEmp_id;
                        $rec_newStudInfo->eNew_email       = $get_upd_empEmail;
                        $rec_newStudInfo->eNew_uRole       = $emp_orgRole;
                        $rec_newStudInfo->eNew_user_type   = $emp_orgType;
                        $rec_newStudInfo->eNew_user_image  = $fileNameToStore;
                        $rec_newStudInfo->eNew_user_lname  = $get_upd_empLname;
                        $rec_newStudInfo->eNew_user_fname  = $get_upd_empFname;
                        $rec_newStudInfo->eNew_user_gender = $new_user_gender;
                        $rec_newStudInfo->eNew_sdca_id     = $get_upd_empID;
                        $rec_newStudInfo->eNew_job_desc    = $get_upd_empJobDesc;
                        $rec_newStudInfo->eNew_dept        = $get_upd_empDept;
                        $rec_newStudInfo->eNew_phnum       = $get_upd_empPhnum;
                        $rec_newStudInfo->edited_at        = $now_timestamp;
                        $rec_newStudInfo->save();
                    // record activity
                        $rec_activity = new Useractivites;
                        $rec_activity->created_at            = $now_timestamp;
                        $rec_activity->act_respo_user_id     = $get_respo_user_id;
                        $rec_activity->act_respo_users_lname = $get_respo_user_lname;
                        $rec_activity->act_respo_users_fname = $get_respo_user_fname;
                        $rec_activity->act_type              = 'profile update';
                        $rec_activity->act_details           = $get_respo_user_fname. ' ' .$get_respo_user_lname . ' Updated ' . $emp_orgFname . ' ' . $emp_orgLname.''.$s_s.'s Profile.';
                        $rec_activity->act_affected_id       = $from_eOldEmp_id;
                        $rec_activity->save();
                    // send email
                        $details = [
                            'svms_logo'           => "storage/svms/logos/svms_logo_text.png",
                            'title'               => 'PROFILE UPDATE',
                            'recipient'           => $user_mr_ms . ' ' .$emp_orgFname . ' ' . $emp_orgLname,
                            'responsible_user'    => $respo_mr_ms . ' ' .$get_respo_user_fname . ' ' . $get_respo_user_lname,
                            'date_of_changes'     => $now_timestamp
                        ];
                        $old_profile = [
                            'user_image'      => 'storage/svms/user_images/'.$emp_orgImage,
                            'user_type'       => $emp_orgType,
                            'user_email'      => $emp_orgEmail,
                            'user_role'       => $emp_orgRole,
                            'user_sdca_id'    => $emp_orgEmpID,
                            'user_first_name' => $emp_orgFname,
                            'user_last_name'  => $emp_orgLname,
                            'user_gender'     => $old_user_gender,
                            'user_job_desc'   => $emp_orgJobDesc,
                            'user_dept'       => $emp_orgDept,
                            'user_phnum'      => $emp_orgPhnum,
                        ];
                        $new_profile = [
                            'user_image'      => 'storage/svms/user_images/'.$fileNameToStore,
                            'user_type'       => $emp_orgType,
                            'user_email'      => $get_upd_empEmail,
                            'user_role'       => $emp_orgRole,
                            'user_sdca_id'    => $get_upd_empID,
                            'user_first_name' => $get_upd_empFname,
                            'user_last_name'  => $get_upd_empLname,
                            'user_gender'     => $get_upd_empGender,
                            'user_job_desc'   => $get_upd_empJobDesc,
                            'user_dept'       => $get_upd_empDept,
                            'user_phnum'      => $get_upd_empPhnum,
                        ];
                        // if user has email
                            if(!empty($emp_orgEmail)){
                                // notify user from his/her old email
                                    \Mail::to('mfodesierto2@gmail.com')->send(new \App\Mail\ProfileUpdateSendMail($details, $old_profile ,$new_profile));
                                // notify user from his page when currently logged in
                                    
                                if(!empty($get_upd_empEmail)){
                                    if($emp_orgEmail !== $get_upd_empEmail){
                                        // deactivate account for switching to new email
                                            // $update_users_tbl = DB::table('users')
                                            // ->where('id', $get_selected_userId)
                                            // ->update([
                                            //     'user_status'  => 'deactivated',
                                            //     'updated_at'   => $now_timestamp
                                            //     ]);
                                        // record status update to user_status_updates_tbl
                                            // if($emp_orgRole !== 'deactivated'){
                                            //     $rec_user_stats_update_tbl = new Userupdatesstatus;
                                            //     $rec_user_stats_update_tbl->from_user_id   = $get_selected_userId;
                                            //     $rec_user_stats_update_tbl->updated_status = 'deactivated';
                                            //     $rec_user_stats_update_tbl->reason_update  = 'switching to a new email address';
                                            //     $rec_user_stats_update_tbl->updated_at     = $now_timestamp;
                                            //     $rec_user_stats_update_tbl->updated_by     = $get_respo_user_id;
                                            //     $rec_user_stats_update_tbl->save();
                                            // }
                                            $rec_user_stats_update_tbl = new Userupdatesstatus;
                                            $rec_user_stats_update_tbl->from_user_id   = $get_selected_userId;
                                            $rec_user_stats_update_tbl->updated_status = 'active';
                                            $rec_user_stats_update_tbl->reason_update  = 'switching to a new email address';
                                            $rec_user_stats_update_tbl->updated_at     = $now_timestamp;
                                            $rec_user_stats_update_tbl->updated_by     = $get_respo_user_id;
                                            $rec_user_stats_update_tbl->save();
                                        // notify user that this new email has been registered as a user of SVMS
                                            \Mail::to($get_upd_empEmail)->send(new \App\Mail\ProfileUpdateNewEmailSendMail($details, $old_profile ,$new_profile));
                                        // logged out user form the system with notification mesasge to check his/her old email

                                    }
                                }
                            }
                    return back()->withSuccessStatus(''.$emp_orgFname . ' '. $emp_orgLname.''.$s_s.'s Account was updated successfully.');
                }else{
                    return back()->withFailedStatus(''.$emp_orgFname . ' '. $emp_orgLname.''.$s_s.'s Account Update has failed, Try again  later.');
                }
    }
    // update user's password
    public function update_user_password(Request $request){
        // now timestamp
            $now_timestamp  = now();
            $format_now_timestamp = $now_timestamp->format('dmYHis');
        // get responsible user info for updating this record
            $get_sel_user_id       = $request->get('selected_user_id');
            $get_respo_user_id     = $request->get('respo_user_id');
            $get_respo_user_lname  = $request->get('respo_user_lname');
            $get_respo_user_fname  = $request->get('respo_user_fname');
        // get new pass
            $get_new_user_pass           = $request->get('upd_sysUser_new_password');
            $get_reasons_for_pass_update = $request->get('upd_sysUser_new_password_reason');
        // get selected user's info
            $get_sel_user_info   = Users::select('id', 'email', 'user_lname', 'user_fname', 'user_gender')->where('id', $get_sel_user_id)->first();
            $get_sel_user_email  = $get_sel_user_info->email;
            $get_sel_user_fname  = $get_sel_user_info->user_fname;
            $get_sel_user_lname  = $get_sel_user_info->user_lname;
            $get_sel_user_gender = $get_sel_user_info->user_gender;
        // get responsible user's gender 
            $get_respo_user_gender_info = Users::select('id', 'user_gender')->where('id', $get_respo_user_id)->first();
            $get_respo_user_gender      = $get_respo_user_gender_info->user_gender;
        // custom values
        // his/her & Mr./Ms.
            if($get_sel_user_gender === 'female'){
                $his_her = 'her';
                $mr_ms   = 'Ms.';
            }elseif($get_sel_user_gender === 'male'){
                $his_her = 'his';
                $mr_ms   = 'Mr.';
            }else{
                $his_her = 'his/her';
                $mr_ms   = 'Mr./Ms.';
            }
            if($get_respo_user_gender === 'female'){
                $respo_his_her = 'her';
                $respo_mr_ms   = 'Ms.';
            }elseif($get_respo_user_gender === 'male'){
                $respo_his_her = 'his';
                $respo_mr_ms   = 'Mr.';
            }else{
                $respo_his_her = 'his/her';
                $respo_mr_ms   = 'Mr./Ms.';
            }
        // apostrophe
            $s_s = "'";
        // hass pass
            $hash_new_user_pass = Hash::make($get_new_user_pass);
        // update users table
            $update_sys_users_tbl = DB::table('users')
            ->where('id', $get_sel_user_id)
            ->update([
                'password'   => $hash_new_user_pass,
                'updated_at' => $now_timestamp
                ]);
        // if update was a success
        if($update_sys_users_tbl){
            // record password update to password_updates_tbl
                $rec_pass_update = new Passwordupdate;
                $rec_pass_update->sel_user_id    = $get_sel_user_id;
                $rec_pass_update->upd_by_user_id = $get_respo_user_id;
                $rec_pass_update->reason_update  = $get_reasons_for_pass_update;
                $rec_pass_update->updated_at     = $now_timestamp;
                $rec_pass_update->save();
            // get id from latest update on password_updates_tbl
                $get_pass_upd_id  = Passwordupdate::select('pass_upd_id')->where('sel_user_id', $get_sel_user_id)->latest('updated_at')->first();
                $from_pass_upd_id = $get_pass_upd_id->pass_upd_id;
            // record activity
                $rec_activity = new Useractivites;
                $rec_activity->created_at            = $now_timestamp;
                $rec_activity->act_respo_user_id     = $get_respo_user_id;
                $rec_activity->act_respo_users_lname = $get_respo_user_lname;
                $rec_activity->act_respo_users_fname = $get_respo_user_fname;
                $rec_activity->act_type              = 'password update';
                $rec_activity->act_details           = $get_respo_user_fname. ' ' .$get_respo_user_lname . ' Updated ' . $get_sel_user_fname . ' ' . $get_sel_user_lname.''.$s_s.'s Password.';
                $rec_activity->act_affected_id       = $from_pass_upd_id;
                $rec_activity->save();
            // send email
                $details = [
                    'svms_logo'        => "storage/svms/logos/svms_logo_text.png",
                    'title'            => 'PASSWORD UPDATE',
                    'recipient'        => $mr_ms . ' ' .$get_sel_user_fname . ' ' . $get_sel_user_lname,
                    'responsible_user' => $respo_mr_ms . ' ' .$get_respo_user_fname . ' ' . $get_respo_user_lname,
                    'pass_updt_reason' => $get_reasons_for_pass_update,
                    'sysUser_email'    => $get_sel_user_email,
                    'sysUser_newPass'  => $get_new_user_pass
                ];
                if(!empty($get_sel_user_email)){
                    \Mail::to('mfodesierto2@gmail.com')->send(new \App\Mail\PasswordUpdateSendMail($details));
                }
            return back()->withSuccessStatus(''.$get_sel_user_fname . ' '. $get_sel_user_lname.''.$s_s.'s Password was updated successfully.');
            // test fetch request data
                // echo 'New Password for ' .$get_sel_user_fname. ' ' .$get_sel_user_lname. '<br />';
                // echo 'New Pass: ' .$get_new_user_pass. ' <br />';
                // echo '<br />';
                // echo 'by: ' .$get_respo_user_id. ' <br />';
                // echo 'lname: ' .$get_respo_user_lname. ' <br />';
                // echo 'fname: ' .$get_respo_user_fname. ' <br />';
                // echo 'email has been sent<br />';
        }else{
            return back()->withFailedStatus(''.$get_sel_user_fname . ' '. $get_sel_user_lname.''.$s_s.'s Password Update has failed, Try again  later.');
        }
    }
    // change user's role
    public function change_user_role_modal(Request $request){
        // get the user's id
            $get_sel_user_id = $request->get('sel_user_id');
        // get user's information from user tble
            $get_sel_user_info          = Users::select('id','email', 'user_role', 'user_status', 'user_role_status', 'user_type', 'user_sdca_id', 'user_image', 'user_lname', 'user_fname', 'user_gender', 'registered_by', 'created_at')->where('id', $get_sel_user_id)->first();
            $get_sel_user_email         = $get_sel_user_info->email;
            $get_sel_user_role          = $get_sel_user_info->user_role;
            $get_sel_user_status        = $get_sel_user_info->user_status;
            $get_sel_user_role_status   = $get_sel_user_info->user_role_status;
            $get_sel_user_type          = $get_sel_user_info->user_type;
            $get_sel_user_sdca_id       = $get_sel_user_info->user_sdca_id;
            $get_sel_user_image         = $get_sel_user_info->user_image;
            $get_sel_user_lname         = $get_sel_user_info->user_lname;
            $get_sel_user_fname         = $get_sel_user_info->user_fname;
            $get_sel_user_gender        = $get_sel_user_info->user_gender;
            $get_sel_user_registered_by = $get_sel_user_info->registered_by;
            $get_sel_user_created_at    = $get_sel_user_info->created_at;
        // get all system roles
            $get_all_emp_roles  = Userroles::select('uRole_id', 'uRole_status', 'uRole_type', 'uRole', 'uRole_access')->where('uRole_status', '!=', 'deleted')->where('uRole_type', 'employee')->get();
            $get_all_stud_roles = Userroles::select('uRole_id', 'uRole_status', 'uRole_type', 'uRole', 'uRole_access')->where('uRole_status', '!=', 'deleted')->where('uRole_type', 'student')->get();
        // filter values
            if($get_sel_user_type === 'student'){
                $get_stud_info = Userstudents::select('uStud_num', 'uStud_school', 'uStud_program', 'uStud_yearlvl', 'uStud_section', 'uStud_phnum')->where('uStud_num', $get_sel_user_sdca_id)->first();
                $sdca_id_title = 'Student Number';
                $img_filter = 'studImg_background';
            }else if($get_sel_user_type === 'employee'){
                $get_emp_info = Useremployees::select('uEmp_id', 'uEmp_job_desc', 'uEmp_dept', 'uEmp_phnum')->where('uEmp_id', $get_sel_user_sdca_id)->first();
                $sdca_id_title = 'Employee ID';
                $img_filter = 'empImg_background';
            }else{

            }
        // his/her text, apostrophe
            if($get_sel_user_gender === 'female'){
                $his_her = 'her';
                $mr_ms   = 'Ms.';
            }elseif($get_sel_user_gender === 'male'){
                $his_her = 'his';
                $mr_ms   = 'Mr.';
            }else{
                $his_her = 'his/her';
                $mr_ms   = 'Mr./Ms.';
            }
            $sq = "'";
        $output = '';
        $output .= '
            <div class="modal-body border-0 p-0">
                <div class="cust_modal_body_gray">
                    <div class="accordion shadow cust_accordion_div" id="changeUserRoleModalAccordion_Parent'.$get_sel_user_id.'">
                        <div class="card custom_accordion_card">
                            <div class="card-header p-0" id="changeUserRoleCollapse_heading'.$get_sel_user_id.'">
                                <h2 class="mb-0">
                                    <button class="btn btn-block custom2_btn_collapse cb_x12y20 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#changeUserRoleCollapse_Div'.$get_sel_user_id.'" aria-expanded="true" aria-controls="changeUserRoleCollapse_Div'.$get_sel_user_id.'">
                                        <div class="d-flex justify-content-start align-items-center">
                                            <div class="display_user_image_div text-center">
                                                <img class="'.$img_filter.' shadow-sm" src="'.asset('storage/svms/user_images/'.$get_sel_user_image).'" alt="student user profile">
                                            </div>
                                            <div class="information_div">
                                                <span class="li_info_title">'.$get_sel_user_fname. ' ' .$get_sel_user_lname.'</span>
                                                <span class="li_info_subtitle">'.ucwords($get_sel_user_role).'</span>
                                            </div>
                                        </div>
                                        <i class="nc-icon nc-minimal-down"></i>
                                    </button>
                                </h2>
                            </div>
                            <div id="changeUserRoleCollapse_Div'.$get_sel_user_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="changeUserRoleCollapse_heading'.$get_sel_user_id.'" data-parent="#changeUserRoleModalAccordion_Parent'.$get_sel_user_id.'">
                                <div class="card-body lightBlue_cardBody mb-2">
                                    <span class="lightBlue_cardBody_blueTitle m-0">'.$sdca_id_title.':</span>
                                    <span class="lightBlue_cardBody_list mb-2">'.$get_sel_user_sdca_id.'</span>';
                                    if($get_sel_user_type === 'student'){
                                        $output .= '
                                        <span class="lightBlue_cardBody_blueTitle m-0">School</span>
                                        <span class="lightBlue_cardBody_list mb-2">'.$get_stud_info->uStud_school.'</span>
                                        <span class="lightBlue_cardBody_blueTitle m-0">Year / Program / Section</span>
                                        <span class="lightBlue_cardBody_list mb-2">'.$get_stud_info->uStud_yearlvl . ' / ' . $get_stud_info->uStud_program . ' / ' .$get_stud_info->uStud_section.'</span>
                                        ';
                                        if(!is_null($get_stud_info->uStud_phnum)){
                                            $output .= '
                                            <span class="lightBlue_cardBody_blueTitle m-0">Contact Number</span>
                                            <span class="lightBlue_cardBody_list mb-2">'.$get_stud_info->uStud_phnum.'</span>
                                            ';
                                        }
                                    }elseif($get_sel_user_type === 'employee'){
                                        $output .= '
                                        <span class="lightBlue_cardBody_blueTitle m-0">Department</span>
                                        <span class="lightBlue_cardBody_list mb-2">'.$get_emp_info->uEmp_dept.'</span>
                                        <span class="lightBlue_cardBody_blueTitle m-0">Job Description</span>
                                        <span class="lightBlue_cardBody_list mb-2">'.$get_emp_info->uEmp_job_desc.'</span>
                                        ';
                                        if(!is_null($get_emp_info->uEmp_phnum)){
                                            $output .= '
                                            <span class="lightBlue_cardBody_blueTitle m-0">Contact Number</span>
                                            <span class="lightBlue_cardBody_list mb-2">'.$get_emp_info->uEmp_phnum.'</span>
                                            ';
                                        }
                                    }else{
                                        // unknown user type
                                    }
                                    if(!is_null($get_sel_user_email)){
                                        $output .= '
                                        <span class="lightBlue_cardBody_blueTitle m-0">Email Address</span>
                                        <span class="lightBlue_cardBody_list mb-2">'.$get_sel_user_email.'</span>
                                        ';
                                    }
                                    $output .='
                                    <span class="lightBlue_cardBody_blueTitle m-0">User Type / System Role</span>
                                    <span class="lightBlue_cardBody_list mb-1">'.ucwords($get_sel_user_type) . ' / System ' . ucwords($get_sel_user_role).'</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="form_changeUserRole" action="'.route('user_management.process_change_user_role').'" class="changeUserRoleForm form" enctype="multipart/form-data" method="POST" onsubmit="submit_changeUserRoleBtn.disabled = true; return true;">
                    <div class="modal-body pb-0">
                        <div class="card-body lightBlue_cardBody shadow-none">
                            <span class="lightBlue_cardBody_notice"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> Options available for assigning a System Role is based on the user'.$sq.'s <span class="font-weight-bold font-italic">USER TYPE</span>. The system will notify ' . $mr_ms . ' ' . $get_sel_user_lname . ' of the changes to ' . $his_her . ' account thru ' . $his_her . ' registered email address.
                        </div>
                        <div class="card-body lightBlue_cardBody shadow-none mt-2">
                            <label for="upd_user_role">Assign Role <i class="fa fa-question-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Below Options are ' . ucwords($get_sel_user_type) . ' Type System Role/s because ' . $mr_ms . ' ' . $get_sel_user_lname . ' is a ' . ucwords($get_sel_user_type) . ' Type User."></i></label>
                            ';
                            if($get_sel_user_type === 'employee'){
                                if(count($get_all_emp_roles) > 0){
                                    foreach($get_all_emp_roles->sortBy('uRole_id') as $get_emp_role){
                                        $output .= '
                                        <div class="accordion shadow-none cust_accordion_div2 mb-1" id="changeUserRoleOption_Parent'.$get_emp_role->uRole_id.'">
                                            <div class="card custom_accordion_card">
                                                <div class="card-header p10 d-flex justify-content-between align-items-center" id="changeUserRoleOption_heading'.$get_emp_role->uRole_id.'">
                                                    <div class="form-check cust_radioInptDiv2">
                                                        <input class="form-check-input" type="radio" name="change_user_sys_role" id="radio_InpRoleId'.$get_emp_role->uRole_id.'" value="'.$get_emp_role->uRole.'"'; if($get_sel_user_role === $get_emp_role->uRole){ $output .= 'checked'; } $output .=' required>
                                                        <label class="form-check-label" for="radio_InpRoleId'.$get_emp_role->uRole_id.'">'.ucwords($get_emp_role->uRole).'</label>
                                                    </div>
                                                    <button class="btn cust_btn_smcircle3" type="button" data-toggle="collapse" data-target="#changeUserRoleOption_Div'.$get_emp_role->uRole_id.'" aria-expanded="true" aria-controls="changeUserRoleOption_Div'.$get_emp_role->uRole_id.'">
                                                        <i class="nc-icon nc-minimal-down"></i>
                                                    </button>
                                                </div>
                                                <div id="changeUserRoleOption_Div'.$get_emp_role->uRole_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="changeUserRoleOption_heading'.$get_emp_role->uRole_id.'" data-parent="#changeUserRoleOption_Parent'.$get_emp_role->uRole_id.'">
                                                    <div class="card-body lightBlue_cardBody">
                                                        <span class="lightBlue_cardBody_blueTitle">Default Access Controls:</span>
                                                        ';
                                                        $index = 1;
                                                        foreach(json_decode(json_encode($get_emp_role->uRole_access), true) as $get_emp_role_access){
                                                            $output .= '<span class="lightBlue_cardBody_list"><span class="font-weight-bold">'.$index++.'. </span> '.ucwords($get_emp_role_access).'</span>';
                                                        }
                                                        $output .= '
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        ';
                                    }
                                }else{
                                    $output .= '
                                    <div class="card-body LightBlue2_cardBody">
                                        <span class="lightBlue_cardBody_notice font-italic"><i class="fa fa-info-circle" aria-hidden="true"></i> There are no System Roles available for Employee Type Users!</span>
                                    </div>
                                    ';
                                }
                            }else if($get_sel_user_type === 'student'){
                                if(count($get_all_stud_roles) > 0){
                                    foreach($get_all_stud_roles->sortBy('uRole_id') as $get_stud_role){
                                        $output .= '
                                        <div class="accordion shadow-none cust_accordion_div2 mb-1" id="changeUserRoleOption_Parent'.$get_stud_role->uRole_id.'">
                                            <div class="card custom_accordion_card">
                                                <div class="card-header p10 d-flex justify-content-between align-items-center" id="changeUserRoleOption_heading'.$get_stud_role->uRole_id.'">
                                                    <div class="form-check cust_radioInptDiv2">
                                                        <input class="form-check-input" type="radio" name="change_user_sys_role" id="radio_InpRoleId'.$get_stud_role->uRole_id.'" value="'.$get_stud_role->uRole.'"'; if($get_sel_user_role === $get_stud_role->uRole){ $output .= 'checked'; } $output .='>
                                                        <label class="form-check-label" for="radio_InpRoleId'.$get_stud_role->uRole_id.'">'.ucwords($get_stud_role->uRole).'</label>
                                                    </div>
                                                    <button class="btn cust_btn_smcircle3" type="button" data-toggle="collapse" data-target="#changeUserRoleOption_Div'.$get_stud_role->uRole_id.'" aria-expanded="true" aria-controls="changeUserRoleOption_Div'.$get_stud_role->uRole_id.'">
                                                        <i class="nc-icon nc-minimal-down"></i>
                                                    </button>
                                                </div>
                                                <div id="changeUserRoleOption_Div'.$get_stud_role->uRole_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="changeUserRoleOption_heading'.$get_stud_role->uRole_id.'" data-parent="#changeUserRoleOption_Parent'.$get_stud_role->uRole_id.'">
                                                    <div class="card-body lightGreen_cardBody">
                                                        <span class="lightGreen_cardBody_greenTitle">Default Access Controls:</span>
                                                        ';
                                                        $index = 1;
                                                        foreach(json_decode(json_encode($get_stud_role->uRole_access), true) as $get_stud_role_access){
                                                            $output .= '<span class="lightGreen_cardBody_list"><span class="font-weight-bold">'.$index++.'. </span> '.ucwords($get_stud_role_access).'</span>';
                                                        }
                                                        $output .= '
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        ';
                                    }
                                }else{
                                    $output .= '
                                    <div class="card-body LightBlue2_cardBody">
                                        <span class="lightBlue_cardBody_notice font-italic"><i class="fa fa-info-circle" aria-hidden="true"></i> There are no System Roles available for Student Type Users!</span>
                                    </div>
                                    ';
                                }
                            }else{
                                $output .= '
                                <div class="card-body LightBlue2_cardBody">
                                    <span class="lightBlue_cardBody_notice font-italic"><i class="fa fa-info-circle" aria-hidden="true"></i> ' . $get_sel_user_fname . ' ' . $get_sel_user_lname.''.$sq.'s User Type is unknown!</span>
                                </div>
                                ';
                            }
                            $output .= '
                            <div class="row mt-2">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <a href="#" id="'.$get_sel_user_id.'" onclick="add_newSystemRole_modal(this.id)" class="btn btn-block btn_svms_blue cust_bt_links shadow" role="button"><i class="nc-icon nc-simple-add mr-1" aria-hidden="true"></i> Add New System Role</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body lightBlue_cardBody shadow-none mt-2">
                            <label>Reason <i class="fa fa-question-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="This will let ' . $get_sel_user_fname . ' ' . $get_sel_user_lname . ' know the reason behind changing ' . $his_her . ' system role."></i></label>
                            <div class="form-group">
                                <textarea class="form-control" id="change_user_role_reason" name="change_user_role_reason" rows="3" placeholder="Type reason for changing ' . $get_sel_user_lname.''.$sq.'s Role (Required)" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="change_role_selected_user_id" value="'.$get_sel_user_id.'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-round btn-secondary btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_changeUserRoleBtn" type="submit" class="btn btn-round btn-success btn_show_icon m-0" disabled>Apply Changes <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        ';
        return $output;
    }
    // process change of user's role
    public function process_change_user_role(Request $request){
        // custom values
            $now_timestamp = now();
            $sq = "'";
        // get all request
            $get_selected_user_id   = $request->get('change_role_selected_user_id');
            $get_new_user_role      = $request->get('change_user_sys_role');
            $get_change_role_reason = $request->get('change_user_role_reason');
            $get_respo_user_id      = $request->get('respo_user_id');
            $get_respo_user_lname   = $request->get('respo_user_lname');
            $get_respo_user_fname   = $request->get('respo_user_fname');  
        // test request
            // echo 'Selected User ID : ' . $get_selected_user_id . ' <br />';
            // echo 'Selected User ROLE : ' . $get_new_user_role . ' <br />';
            // echo 'REASON : ' . $get_change_role_reason . ' <br />';
            // echo '<br />';
            // echo 'by user id : ' . $get_respo_user_id . ' <br />';
            // echo 'by user name : ' . $get_respo_user_fname . ' ' . $get_respo_user_lname . ' <br />';
        // get selected user's information from users table
            $get_sel_user_info    = Users::select('id', 'email', 'user_role', 'user_type', 'user_sdca_id', 'user_lname', 'user_fname', 'user_gender')->where('id', $get_selected_user_id)->first();
            $get_sel_user_email   = $get_sel_user_info->email;
            $get_sel_user_role    = $get_sel_user_info->user_role;
            $get_sel_user_type    = $get_sel_user_info->user_type;
            $get_sel_user_sdca_id = $get_sel_user_info->user_sdca_id;
            $get_sel_user_lname   = $get_sel_user_info->user_lname;
            $get_sel_user_fname   = $get_sel_user_info->user_fname;
            $get_sel_user_gender  = $get_sel_user_info->user_gender;
        // get responsible user's information from users table
            $get_respo_user_gender_info = Users::select('id', 'user_gender')->where('id', $get_respo_user_id)->first();
            $get_respo_user_gender      = $get_respo_user_gender_info->user_gender;
        // his/her & Mr./Ms.
            if($get_sel_user_gender === 'female'){
                $his_her = 'her';
                $mr_ms   = 'Ms.';
            }elseif($get_sel_user_gender === 'male'){
                $his_her = 'his';
                $mr_ms   = 'Mr.';
            }else{
                $his_her = 'his/her';
                $mr_ms   = 'Mr./Ms.';
            }
            if($get_respo_user_gender === 'female'){
                $respo_his_her = 'her';
                $respo_mr_ms   = 'Ms.';
            }elseif($get_respo_user_gender === 'male'){
                $respo_his_her = 'his';
                $respo_mr_ms   = 'Mr.';
            }else{
                $respo_his_her = 'his/her';
                $respo_mr_ms   = 'Mr./Ms.';
            }
        // get old user's role from user_roles_tbl
            $get_oldSys_uRole_info     = Userroles::select('uRole_id', 'uRole', 'assUsers_count')->where('uRole', $get_sel_user_role)->first();
            $get_oldSys_uRole_id       = $get_oldSys_uRole_info->uRole_id;
            $get_oldSys_uRole          = $get_oldSys_uRole_info->uRole;
            $get_oldSys_assUsers_count = $get_oldSys_uRole_info->assUsers_count;
        // get new user's role from user_roles_tbl
            $get_newSys_uRole_info     = Userroles::select('uRole_id', 'uRole', 'assUsers_count')->where('uRole', $get_new_user_role)->first();
            $get_newSys_uRole_id       = $get_newSys_uRole_info->uRole_id;
            $get_newSys_uRole          = $get_newSys_uRole_info->uRole;
            $get_newSys_assUsers_count = $get_newSys_uRole_info->assUsers_count;
        // update user's role from users table
            $update_sys_users_tbl = DB::table('users')
                ->where('id', $get_selected_user_id)
                ->update([
                    'user_role'   => $get_new_user_role,
                    'user_status' => 'active',
                    'updated_at'  => $now_timestamp
                    ]);
        // update role count from user_roles_tbl
            if($update_sys_users_tbl){
                // new counts for old and new roles
                    $old_uRole_assUsers_count = $get_oldSys_assUsers_count - 1;
                    $new_uRole_assUsers_count = $get_newSys_assUsers_count + 1;
                // update old role count
                    $update_old_role_count_tbl = DB::table('user_roles_tbl')
                        ->where('uRole', $get_sel_user_role)
                        ->update([
                            'assUsers_count' => $old_uRole_assUsers_count,
                            'updated_at'     => $now_timestamp
                            ]);
                // update new role count
                    $update_new_role_count_tbl = DB::table('user_roles_tbl')
                        ->where('uRole', $get_new_user_role)
                        ->update([
                            'assUsers_count' => $new_uRole_assUsers_count,
                            'updated_at'     => $now_timestamp
                            ]);
                if($update_old_role_count_tbl AND $update_new_role_count_tbl){
                    // record activity
                        $rec_activity = new Useractivites;
                        $rec_activity->created_at            = $now_timestamp;
                        $rec_activity->act_respo_user_id     = $get_respo_user_id;
                        $rec_activity->act_respo_users_lname = $get_respo_user_lname;
                        $rec_activity->act_respo_users_fname = $get_respo_user_fname;
                        $rec_activity->act_type              = 'change user'.$sq.'s role';
                        $rec_activity->act_details           = $get_respo_user_fname. ' ' .$get_respo_user_lname . ' Changed ' . $get_sel_user_fname . ' ' . $get_sel_user_lname.''.$sq.'s System Role from ' .ucwords($get_sel_user_role). ' Role to ' .ucwords($get_new_user_role).' Role.';
                        $rec_activity->act_affected_id       = $get_selected_user_id;
                        $rec_activity->save();
                    // send email
                        $details = [
                            'svms_logo'          => "storage/svms/logos/svms_logo_text.png",
                            'title'              => 'PASSWORD UPDATE',
                            'recipient'          => $mr_ms . ' ' .$get_sel_user_fname . ' ' . $get_sel_user_lname,
                            'responsible_user'   => $respo_mr_ms . ' ' .$get_respo_user_fname . ' ' . $get_respo_user_lname,
                            'change_role_reason' => $get_change_role_reason,
                            'old_sys_role'       => ucwords($get_sel_user_role),
                            'new_sys_role'       => ucwords($get_new_user_role)
                        ];
                        if(!empty($get_sel_user_email)){
                            \Mail::to('mfodesierto2@gmail.com')->send(new \App\Mail\ChangeUserSysRoleSendMail($details));
                        }
                    return back()->withSuccessStatus(''.$get_sel_user_fname . ' '. $get_sel_user_lname.''.$sq.'s Role was updated successfully.');
                }else{
                    return back()->withFailedStatus('Updating System Roles old/New counts has failed, Try again  later.');
                }
            }else{
                return back()->withFailedStatus('Changing ' .$get_sel_user_lname . ' '. $get_sel_user_fname.''.$sq.'s System Role has failed, Try again  later.');
            }
    }

    // FUNCTIONS FOR SYSTEM ROLES 
    // create new role
    public function create_new_system_role(Request $request){
        // get all requests
            $get_prev_modal_id      = $request->get('prev_modal_id');
            $get_create_role_name   = $request->get('create_role_name');
            $get_create_role_type   = $request->get('create_role_type');
            $get_create_role_access = json_decode(json_encode($request->get('create_role_access')));
            $get_respo_user_id      = $request->get('respo_user_id');
            $get_respo_user_lname   = $request->get('respo_user_lname');
            $get_respo_user_fname   = $request->get('respo_user_fname');    
            
        // custom values
            $now_timestamp    = now();
            $active_txt       = 'active';
            $lower_uRole_name = Str::lower($get_create_role_name);

        // save to user_roles_tbl table
            $reg_new_system_role = new Userroles;
            $reg_new_system_role->uRole_status = $active_txt;
            $reg_new_system_role->uRole_type   = $get_create_role_type;
            $reg_new_system_role->uRole        = $lower_uRole_name;
            $reg_new_system_role->uRole_access = $get_create_role_access;
            $reg_new_system_role->created_by   = $get_respo_user_id;
            $reg_new_system_role->created_at   = $now_timestamp;
            $reg_new_system_role->save();

        // if saving new role was a success
        if($reg_new_system_role){
            // get new role's id for activity reference
                $get_new_role_id = Userroles::select('uRole_id')->where('uRole', $get_create_role_name)->latest('created_at')->first();
                $newly_reg_role_id     = $get_new_role_id->uRole_id;

            // record activity
                $record_act = new Useractivites;
                $record_act->created_at            = $now_timestamp;
                $record_act->act_respo_user_id     = $get_respo_user_id;
                $record_act->act_respo_users_lname = $get_respo_user_lname;
                $record_act->act_respo_users_fname = $get_respo_user_fname;
                $record_act->act_type              = 'create role';
                $record_act->act_details           = 'Created a new ' .$get_create_role_type. ' type System Role: ' .$get_create_role_name;
                $record_act->act_affected_id       = $newly_reg_role_id;
                $record_act->save();

            return back()->withSuccessStatus('New System Role: ' . $get_create_role_name. ' was registered successfully!');
        }else{
            return back()->withFailedStatus('New System Role: ' . $get_create_role_name. ' has failed to register. Try again later.');
        }

        // test fetch request data
            // echo 'REGISTER NEW EMPLOYEE USER <br />';
            // echo 'System Role: ' .$get_create_role_name. ' <br />';
            // echo 'System Role Type: ' .$get_create_role_type. ' <br />';
            // echo 'System Role Access: ' .$get_create_role_access. ' <br />';
            // echo '<br />';
            // echo 'by: ' .$get_respo_user_id. ' <br />';
            // echo 'lname: ' .$get_respo_user_lname. ' <br />';
            // echo 'fname: ' .$get_respo_user_fname. ' <br />';
    }
    // add new system role modal
    public function add_new_system_role_modal(Request $request){
        // get previous user's id to get back to change user's role modal
            $get_prev_user_id = $request->get('prev_user_id');
        // get roles based on role type
            $get_all_emp_type_roles    = Userroles::select('uRole_id', 'uRole_status', 'uRole_type', 'uRole', 'uRole_access', 'assUsers_count', 'created_by', 'created_at')->where('uRole_status', '!=', 'deleted')->where('uRole_type', 'employee')->get();
            $get_all_stud_type_roles   = Userroles::select('uRole_id', 'uRole_status', 'uRole_type', 'uRole', 'uRole_access', 'assUsers_count', 'created_by', 'created_at')->where('uRole_status', '!=', 'deleted')->where('uRole_type', 'student')->get();
            $count_all_emp_type_roles  = Userroles::where('uRole_status', '!=', 'deleted')->where('uRole_type', 'employee')->count();
            $count_all_stud_type_roles = Userroles::where('uRole_status', '!=', 'deleted')->where('uRole_type', 'student')->count();

        $output = '';
        $output .= '
            <div class="modal-body border-0 p-0">
                <div class="cust_modal_body_gray">
                    <div class="accordion shadow-none cust_accordion_div" id="empTypeRolesModalAccordion_Parent">
                        <div class="card custom_accordion_card">
                            <div class="card-header p-0" id="empTypeRolesCollapse_heading">
                                <h2 class="mb-0">
                                    <button class="btn btn-block custom2_btn_collapse cb_x12y20 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#empTypeRolesCollapse_Div" aria-expanded="true" aria-controls="empTypeRolesCollapse_Div">
                                        <div>
                                            <span class="li_info_title">Employee Type Roles</span>
                                            <span class="li_info_subtitle">'.$count_all_emp_type_roles.' System '; if($count_all_emp_type_roles > 1){ $output .= ' Roles '; }else{ $output .= ' Role '; } $output .= ' Found</span>
                                        </div>
                                        <i class="nc-icon nc-minimal-down"></i>
                                    </button>
                                </h2>
                            </div>
                            <div id="empTypeRolesCollapse_Div" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="empTypeRolesCollapse_heading" data-parent="#empTypeRolesModalAccordion_Parent">
                                <div class="row mt-0 mb-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12 m-0">
                                        <div class="card cust_listCard shadow-none">
                                        ';
                                        foreach($get_all_emp_type_roles->sortBy('uRole_id') as $emp_type_role){
                                            $count_assigned_emp_users = Userroles::where('uRole', $emp_type_role->uRole)->count();
                                            $output .= '
                                            <div class="card-header cust_listCard_header3 bg_F4F4F5">
                                                <div>
                                                    <span class="accordion_title">'.$emp_type_role->uRole.'';if($emp_type_role->assUsers_count>1){$output.='s';}$output .='</span>';
                                                    if($emp_type_role->assUsers_count <= 0){
                                                        $output .= '<span class="font-italic text_svms_red"> No Assigned Users. </span>';
                                                    }
                                                    $output .='
                                                </div>
                                                <div class="assignedUsersCirclesDiv">';
                                                    if($count_assigned_emp_users > 14){
                                                        $get_only_14_emp_users = Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $emp_type_role->uRole)->take(13)->get();
                                                        $more_emp_users_count = $count_assigned_emp_users - 13;
                                                        foreach($get_only_14_emp_users->sortBy('id') as $display_13_emp_users){
                                                            $output .= '<img class="assignedUsersCirclesImgs2 F4F4F5_border" src="'.asset('storage/svms/user_images/'.$display_13_emp_users->user_image).'" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="';if(auth()->user()->id === $display_13_emp_users->id){$output .='You';}else{ $output .= ''.$display_13_emp_users->user_fname . ' ' .$display_13_emp_users->user_lname. ' ';} $output .= '">';
                                                        }
                                                        $output .= '
                                                        <div class="moreImgsCounterDiv2" data-toggle="tooltip" data-placement="top" title="'.$more_emp_users_count.' more '; if($more_emp_users_count > 1){ $output .= 'users'; }else{ $output .= 'user'; } $output .='">
                                                            <span class="moreImgsCounterTxt2">+ ' .$more_emp_users_count.'</span>
                                                        </div>
                                                        ';
                                                    }else{
                                                        $get_all_assigned_emp_users = Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $emp_type_role->uRole)->get();
                                                        foreach($get_all_assigned_emp_users->sortBy('id') as $assigned_emp_user){
                                                            $output .= '<img class="assignedUsersCirclesImgs2 F4F4F5_border" src="'.asset('storage/svms/user_images/'.$assigned_emp_user->user_image).'" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="';if(auth()->user()->id === $assigned_emp_user->id){$output .='You';}else{ $output .= ''.$assigned_emp_user->user_fname . ' ' .$assigned_emp_user->user_lname. ' ';} $output .= '">';
                                                        }
                                                    }
                                                $output .= '
                                                </div>
                                            </div>
                                            ';
                                        }
                                        $output .= '
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="accordion shadow-none cust_accordion_div mt-2" id="studTypeRolesModalAccordion_Parent">
                        <div class="card custom_accordion_card">
                            <div class="card-header p-0" id="studTypeRolesCollapse_heading">
                                <h2 class="mb-0">
                                    <button class="btn btn-block custom2_btn_collapse cb_x12y20 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#studTypeRolesCollapse_Div" aria-expanded="true" aria-controls="studTypeRolesCollapse_Div">
                                        <div>
                                            <span class="li_info_title">Student Type Roles</span>
                                            <span class="li_info_subtitle">'.$count_all_stud_type_roles.' System '; if($count_all_stud_type_roles > 1){ $output .= ' Roles '; }else{ $output .= ' Role '; } $output .= ' Found</span>
                                        </div>
                                        <i class="nc-icon nc-minimal-down"></i>
                                    </button>
                                </h2>
                            </div>
                            <div id="studTypeRolesCollapse_Div" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="studTypeRolesCollapse_heading" data-parent="#studTypeRolesModalAccordion_Parent">
                                <div class="row mt-0 mb-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12 m-0">
                                        <div class="card cust_listCard shadow-none">
                                        ';
                                        foreach($get_all_stud_type_roles->sortBy('uRole_id') as $stud_type_role){
                                            $count_assigned_stud_users = Userroles::where('uRole', $stud_type_role->uRole)->count();
                                            $output .= '
                                            <div class="card-header cust_listCard_header3 bg_F4F4F5">
                                                <div>
                                                    <span class="accordion_title">'.$stud_type_role->uRole.'';if($stud_type_role->assUsers_count>1){$output.='s';}$output .='</span>';
                                                    if($stud_type_role->assUsers_count <= 0){
                                                        $output .= '<span class="font-italic text_svms_red"> No Assigned Users. </span>';
                                                    }
                                                    $output .='
                                                </div>
                                                <div class="assignedUsersCirclesDiv">';
                                                    if($count_assigned_stud_users > 14){
                                                        $get_only_14_emp_users = Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $stud_type_role->uRole)->take(13)->get();
                                                        $more_stud_users_count = $count_assigned_stud_users - 13;
                                                        foreach($get_only_14_emp_users->sortBy('id') as $display_13_stud_users){
                                                            $output .= '<img class="assignedUsersCirclesImgs2 F4F4F5_border" src="'.asset('storage/svms/user_images/'.$display_13_stud_users->user_image).'" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="';if(auth()->user()->id === $display_13_stud_users->id){$output .='You';}else{ $output .= ''.$display_13_stud_users->user_fname . ' ' .$display_13_stud_users->user_lname. ' ';} $output .= '">';
                                                        }
                                                        $output .= '
                                                        <div class="moreImgsCounterDiv2" data-toggle="tooltip" data-placement="top" title="'.$more_stud_users_count.' more '; if($more_stud_users_count > 1){ $output .= 'users'; }else{ $output .= 'user'; } $output .='">
                                                            <span class="moreImgsCounterTxt2">+ ' .$more_stud_users_count.'</span>
                                                        </div>
                                                        ';
                                                    }else{
                                                        $get_all_assigned_stud_users = Users::select('id', 'user_image', 'user_lname', 'user_fname')->where('user_role', $stud_type_role->uRole)->get();
                                                        foreach($get_all_assigned_stud_users->sortBy('id') as $assigned_stud_user){
                                                            $output .= '<img class="assignedUsersCirclesImgs2 F4F4F5_border" src="'.asset('storage/svms/user_images/'.$assigned_stud_user->user_image).'" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="';if(auth()->user()->id === $assigned_stud_user->id){$output .='You';}else{ $output .= ''.$assigned_stud_user->user_fname . ' ' .$assigned_stud_user->user_lname. ' ';} $output .= '">';
                                                        }
                                                    }
                                                $output .= '
                                                </div>
                                            </div>
                                            ';
                                        }
                                        $output .= '
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
                <form action="'.route('user_management.create_new_system_role').'" class="form" enctype="multipart/form-data" method="POST" onsubmit="submit_newSystemRole_btn.disabled = true; return true;">
                    <div class="modal-body pb-0">
                        <div class="card-body lightBlue_cardBody shadow-none">
                            <label for="create_role_name">Role Name</label>
                            <div class="input-group mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="nc-icon nc-badge"></i>
                                    </span>
                                </div>
                                <input id="create_role_name" name="create_role_name" type="text" class="form-control" placeholder="Type New Role Name" value="'.old('create_role_name').'" required>
                            </div>
                        </div>
                        <div class="card-body lightBlue_cardBody shadow-none mt-2">
                            <div class="form-group cust_fltr_dropdowns_div mb-1">
                                <label for="create_role_type">Select Role Type <i class="fa fa-question-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Role type selection is required for system preference."></i></label>
                                <select class="form-control cust_fltr_dropdowns2 drpdwn_arrow2" id="create_role_type" name="create_role_type" required>
                                    <option value="employee" selected>Employee User</option>
                                    <option value="student">Student User</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body lightGreen_cardBody mt-2">
                            <span class="lightGreen_cardBody_greenTitle">Default Access Controls:</span>
                            <div class="form-group mx-0 mt-0 mb-1">
                                <div class="custom-control custom-checkbox align-items-center">
                                    <input type="checkbox" name="create_role_access[]" value="profile" class="custom-control-input cursor_pointer" id="my_profile_mod" checked>
                                    <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="my_profile_mod">My Profile</label>
                                </div>
                            </div>
                            <div class="form-group mx-0 mt-0 mb-1">
                                <div class="custom-control custom-checkbox align-items-center">
                                    <input type="checkbox" name="create_role_access[]" value="violation entry" class="custom-control-input cursor_pointer" id="violation_entry_mod" checked>
                                    <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="violation_entry_mod">Violation Entry</label>
                                </div>
                            </div>
                            <div class="form-group mx-0 mt-0 mb-1">
                                <div class="custom-control custom-checkbox align-items-center">
                                    <input type="checkbox" name="create_role_access[]" value="student handbook" class="custom-control-input cursor_pointer" id="student_handbook_mod" checked>
                                    <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="student_handbook_mod">Student Handbook</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body lightRed_cardBody mt-2">
                            <span class="lightRed_cardBody_redTitle">Administrative Access Controls:</span>
                            <span class="lightRed_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> Below Modules are Administrative Access Controls that are not recommended for regular System Roles but you can enable them for this role if you wish to.</span>
                            <div class="form-group mx-0 mt-2 mb-1">
                                <div class="custom-control custom-checkbox align-items-center">
                                    <input type="checkbox" name="create_role_access[]" value="dashboard" class="custom-control-input cursor_pointer" id="dashboard_mod">
                                    <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="dashboard_mod">Dashboard</label>
                                </div>
                            </div>
                            <div class="form-group mx-0 mt-0 mb-1">
                                <div class="custom-control custom-checkbox align-items-center">
                                    <input type="checkbox" name="create_role_access[]" value="users management" class="custom-control-input cursor_pointer" id="users_management_mod">
                                    <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="users_management_mod">Users Management</label>
                                </div>
                            </div>
                            <div class="form-group mx-0 mt-0 mb-1">
                                <div class="custom-control custom-checkbox align-items-center">
                                    <input type="checkbox" name="create_role_access[]" value="violation records" class="custom-control-input cursor_pointer" id="violation_record_mod">
                                    <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="violation_record_mod">Violation Records</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button id="'.$get_prev_user_id.'" onclick="changeUserRole(this.id)" type="button" class="btn btn-round btn-success btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_newSystemRole_btn" type="submit" class="btn btn-round btn_svms_blue btn_show_icon m-0">Save New Role <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        ';
        return $output;
    }
    // update role
    public function update_user_role(Request $request){
        // now timestamp
            $now_timestamp  = now();

        // get all requests
            $get_edit_selected_uRole_id = $request->get('edit_selected_uRole_id');
            $get_edit_uRoleName         = Str::lower($request->get('edit_uRoleName'));
            $get_edit_uRole_access      = json_encode($request->get('edit_uRole_access'));
            $get_respo_user_id          = $request->get('respo_user_id');
            $get_respo_user_lname       = $request->get('respo_user_lname');
            $get_respo_user_fname       = $request->get('respo_user_fname');

        // get old data of selected role from user_roles_tbl
            $get_old_uRole_info   = Userroles::where('uRole_id', $get_edit_selected_uRole_id)->first();
            $get_old_uRole_status = $get_old_uRole_info->uRole_status;
            $get_old_uRole_type   = $get_old_uRole_info->uRole_type;
            $get_old_uRole        = $get_old_uRole_info->uRole;
            $get_old_uRole_access = $get_old_uRole_info->uRole_access;

        // update record from user_roles_tbl
            $update_user_role_tbl = DB::table('user_roles_tbl')
                ->where('uRole_id', $get_edit_selected_uRole_id)
                ->update([
                    'uRole_status' => $get_old_uRole_status,
                    'uRole_type'   => $get_old_uRole_type,
                    'uRole'        => $get_edit_uRoleName,
                    'uRole_access' => $get_edit_uRole_access,
                    'updated_at'   => $now_timestamp
                ]);

        // if update was a success
        if($update_user_role_tbl){
            // update the role name on users assigned to this role from users_tbl
            $check_if_any_assigned_users = Users::where('user_role', $get_old_uRole)->count();
            if($check_if_any_assigned_users > 0){
                $update_users_role_name_tbl = DB::table('users')
                ->where('user_role', $get_old_uRole)
                ->update([
                    'user_role'  => $get_edit_uRoleName,
                    'updated_at' => $now_timestamp
                ]);
            }

            // record old uRole data to edited_old_user_roles_tbl
            $record_old_uRole_tbl = new Editedolduserroles;
            $record_old_uRole_tbl->from_uRole_id    = $get_edit_selected_uRole_id;
            $record_old_uRole_tbl->old_uRole_status = $get_old_uRole_status;
            $record_old_uRole_tbl->old_uRole_type   = $get_old_uRole_type;
            $record_old_uRole_tbl->old_uRole        = $get_old_uRole;
            $record_old_uRole_tbl->old_uRole_access = $get_old_uRole_access;
            $record_old_uRole_tbl->respo_user_id    = $get_respo_user_id;
            $record_old_uRole_tbl->edited_at        = $now_timestamp;
            $record_old_uRole_tbl->save();

            // get eOld_uRole_id from edited_old_user_roles_tbl 
            $get_eOld_uRole_id_tbl = Editedolduserroles::where('from_uRole_id', $get_edit_selected_uRole_id)->latest('edited_at')->first();
            $get_eOld_uRole_id     = $get_eOld_uRole_id_tbl->eOld_uRole_id;

            // record edited uRole data to edited_new_user_roles_tbl
            $record_new_uRole_tbl = new Editednewuserroles;
            $record_new_uRole_tbl->from_eOld_uRole_id = $get_eOld_uRole_id;
            $record_new_uRole_tbl->new_uRole_status   = $get_old_uRole_status;
            $record_new_uRole_tbl->new_uRole_type     = $get_old_uRole_type;
            $record_new_uRole_tbl->new_uRole          = $get_edit_uRoleName;
            $record_new_uRole_tbl->new_uRole_access	  = json_decode($get_edit_uRole_access, true);
            $record_new_uRole_tbl->edited_at	      = $now_timestamp;
            $record_new_uRole_tbl->save();

            // record activity
            $rec_activity = new Useractivites;
            $rec_activity->created_at            = $now_timestamp;
            $rec_activity->act_respo_user_id     = $get_respo_user_id;
            $rec_activity->act_respo_users_lname = $get_respo_user_lname;
            $rec_activity->act_respo_users_fname = $get_respo_user_fname;
            $rec_activity->act_type              = 'update role';
            $rec_activity->act_details           = ucwords($get_old_uRole). ' Role Update.';
            $rec_activity->act_affected_id       = $get_eOld_uRole_id;
            $rec_activity->save();

            return back()->withSuccessStatus(ucwords($get_old_uRole). ' Role was updated successfully.');
        }else{
            return back()->withFailedStatus(ucwords($get_old_uRole). ' Role Update Failed! try again later.');
        }
    }
    // deactivate role modal confirmation
    public function deactivate_role_modal(Request $request){
        // get selected uRole_id
            $get_deactivated_uRole_id = $request->get('deactivated_uRole_id');

        // get role details from user_roles_tbl
            $get_role_details     = Userroles::where('uRole_id', $get_deactivated_uRole_id)->first();
            $get_uRole_status     = $get_role_details->uRole_status;
            $get_uRole_type       = $get_role_details->uRole_type;
            $get_uRole            = $get_role_details->uRole;
            $get_uRole_access     = $get_role_details->uRole_access;
            $get_uRole_created_by = $get_role_details->created_by;
            $get_uRole_created_at = $get_role_details->created_at;

        // get info of user who created this role
            $get_created_by_user  = Users::where('id', $get_uRole_created_by)->first();
            $get_created_by_lname = $get_created_by_user->user_lname; 
            $get_created_by_fname = $get_created_by_user->user_fname; 

        // get all assigned users
            $get_assigned_users = Users::where('user_role', $get_uRole)->get();

        $output = '';
        $output .='
            <div class="modal-body border-0 p-0">
                <div class="cust_modal_body_gray">
                    <div class="accordion shadow cust_accordion_div" id="deactivateURoleModalAccordion_Parent'.$get_deactivated_uRole_id.'">
                        <div class="card custom_accordion_card">
                            <div class="card-header p-0" id="deactivateRoleCollapse_heading'.$get_deactivated_uRole_id.'">
                                <h2 class="mb-0">
                                    <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#deactivateURoleCollapse_Div'.$get_deactivated_uRole_id.'" aria-expanded="true" aria-controls="deactivateURoleCollapse_Div'.$get_deactivated_uRole_id.'">
                                        <div>
                                            <span class="accordion_title">'.$get_uRole.'</span>
                                            <span class="accordion_subtitle">'; 
                                                if(count($get_assigned_users) > 0){
                                                    if(count($get_assigned_users) > 1){
                                                        $output .= count($get_assigned_users). ' Assigned Users Found.';
                                                    }else{
                                                        $output .= count($get_assigned_users). ' Assigned User Found.';
                                                    }
                                                }else{
                                                    $output .= 'No Assigned Users.';
                                                }
                                            $output .='
                                            </span>
                                        </div>
                                        <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                    </button>
                                </h2>
                            </div>
                            <div id="deactivateURoleCollapse_Div'.$get_deactivated_uRole_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="deactivateRoleCollapse_heading'.$get_deactivated_uRole_id.'" data-parent="#deactivateURoleModalAccordion_Parent'.$get_deactivated_uRole_id.'">
                                <div class="card-body lightBlue_cardBody mt-2">
                                    <span class="lightBlue_cardBody_blueTitle">Assigned Users:</span>';
                                    if(count($get_assigned_users) > 0){
                                        foreach($get_assigned_users as $index => $assigned_user){
                                            $output .= '<span class="lightBlue_cardBody_list"><span class="lightBlue_cardBody_listCount">'.($index+1).'.</span> ' .$assigned_user->user_fname. ' ' .$assigned_user->user_lname. '</span>';
                                        }
                                    }else{
                                        $output .= '<span class="lightBlue_cardBody_list font-italic">No assigned users found.</span>';
                                    }
                                    $output .= '
                                </div>
                                <div class="card-body lightGreen_cardBody mt-2 mb-2">
                                    <span class="lightGreen_cardBody_greenTitle">Access Controls:</span>';
                                    if(!is_null($get_uRole_access)){
                                        foreach(json_decode(json_encode($get_uRole_access), true) as $index => $uRole_access){
                                            $output .= '<span class="lightGreen_cardBody_list"><span class="lightGreen_cardBody_listCount">'.($index+1).'.</span> '.ucwords($uRole_access).'</span>';
                                        }
                                    }else{
                                        $output .= '<span class="lightGreen_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> No access controls found.</span>';
                                    }
                                    $output .= '    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form action="'.route('user_management.process_deactivate_role').'" class="deactivateRoleConfirmationForm" method="POST">
                <div class="modal-body pb-0">';
                if(count($get_assigned_users) > 0){
                $output .= '
                    <div class="card-body lightRed_cardBody shadow-none">
                        <span class="lightRed_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> Deactivating <span class="font-weight-bold"> ' .ucwords($get_uRole). ' </span> Role will also deactivate the assigned users wherein they will no longer be able to access the system until activation.</span>
                    </div>';
                }
                $output .='
                    <div class="card-body lightBlue_cardBody shadow-none mt-2">
                        <span class="lightBlue_cardBody_blueTitle">Reason for Deactivating ' .ucwords($get_uRole). ' Role:</span>
                        <div class="form-group">
                            <textarea class="form-control" id="deactivate_role_reason" name="deactivate_role_reason" rows="3" placeholder="Type reason for Deactivation (optional)"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="deactivate_selected_role_id" value="'.$get_deactivated_uRole_id.'">
                    <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                    <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                    <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                        <button type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0">Deactivate this Role <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </form>
        ';

        echo $output;
    }
    // process role deactivation
    public function process_deactivate_role(Request $request){
        // now timestamp
            $now_timestamp   = now();
            $deactivated_txt = 'deactivated';

        // get all request
            $get_deactivate_selected_role_id = $request->get('deactivate_selected_role_id');
            $get_respo_user_id               = $request->get('respo_user_id');
            $get_respo_user_lname            = $request->get('respo_user_lname');
            $get_respo_user_fname            = $request->get('respo_user_fname');
            $get_deactivate_role_reason      = $request->get('deactivate_role_reason');

        // get role name from user_roles_tbl
            $get_role_details = Userroles::where('uRole_id', $get_deactivate_selected_role_id)->first();
            if($get_role_details){
                // get details
                $org_uRole_name = $get_role_details->uRole;

                // deactivate the role
                $update_role_status_tbl = DB::table('user_roles_tbl')
                ->where('uRole_id', $get_deactivate_selected_role_id)
                ->update([
                    'uRole_status' => $deactivated_txt,
                    'updated_at'   => $now_timestamp
                ]);

                // if status update was a success
                if($update_role_status_tbl){
                    // update role status from users tbl for assigned users
                    $check_assigned_users = Users::where('user_role', $org_uRole_name)->count();
                    if($check_assigned_users > 0){
                        $update_role_status_tbl = DB::table('users')
                        ->where('user_role', $org_uRole_name)
                        ->update([
                            'user_role_status' => 'deactivated',
                            'updated_at'       => $now_timestamp
                        ]);
                    }
                }

                // record role status' updat to user_role_status_updates_tbl
                $rec_role_stats_update_tbl = new Userrolesupdatestatus;
                $rec_role_stats_update_tbl->from_uRole_id  = $get_deactivate_selected_role_id;
                $rec_role_stats_update_tbl->updated_status = $deactivated_txt;
                $rec_role_stats_update_tbl->reason_update  = $get_deactivate_role_reason;
                $rec_role_stats_update_tbl->updated_at     = $now_timestamp;
                $rec_role_stats_update_tbl->updated_by     = $get_respo_user_id;
                $rec_role_stats_update_tbl->save();

                // get uRoleStatUp_id from user_role_status_updates_tbl fro activity reference
                $get_uRoleStatUp_id_tbl = Userrolesupdatestatus::select('uRoleStatUp_id')->where('from_uRole_id', $get_deactivate_selected_role_id)->latest('updated_at')->first();
                $get_uRoleStatUp_id     = $get_uRoleStatUp_id_tbl->uRoleStatUp_id;

                // record activity
                $rec_activity = new Useractivites;
                $rec_activity->created_at            = $now_timestamp;
                $rec_activity->act_respo_user_id     = $get_respo_user_id;
                $rec_activity->act_respo_users_lname = $get_respo_user_lname;
                $rec_activity->act_respo_users_fname = $get_respo_user_fname;
                $rec_activity->act_type              = 'deactivate role';
                $rec_activity->act_details           = 'Deactivated ' .ucwords($org_uRole_name). ' Role.';
                $rec_activity->act_affected_id       = $get_uRoleStatUp_id;
                $rec_activity->save();

                return back()->withSuccessStatus(ucwords($org_uRole_name). ' Role was Deactivated Successfully.');
            }else{
                return back()->withFailedStatus(ucwords($org_uRole_name). ' Role Deactivation Failed! try again later.');
            }
    }
    // activate role modal confirmation
    public function activate_role_modal(Request $request){
        // get selected uRole_id
            $get_activated_uRole_id = $request->get('activate_uRole_id');

        // get role details from user_roles_tbl
            $get_role_details     = Userroles::where('uRole_id', $get_activated_uRole_id)->first();
            $get_uRole_status     = $get_role_details->uRole_status;
            $get_uRole_type       = $get_role_details->uRole_type;
            $get_uRole            = $get_role_details->uRole;
            $get_uRole_access     = $get_role_details->uRole_access;
            $get_uRole_created_by = $get_role_details->created_by;
            $get_uRole_created_at = $get_role_details->created_at;

        // get info of user who created this role
            $get_created_by_user  = Users::where('id', $get_uRole_created_by)->first();
            $get_created_by_lname = $get_created_by_user->user_lname; 
            $get_created_by_fname = $get_created_by_user->user_fname; 

        // get all assigned users
            $get_assigned_users = Users::where('user_role', $get_uRole)->get();

        $output = '';
        $output .='
        <div class="modal-body border-0 p-0">
            <div class="cust_modal_body_gray">
                <div class="accordion shadow cust_accordion_div" id="activateURoleModalAccordion_Parent'.$get_activated_uRole_id.'">
                    <div class="card custom_accordion_card">
                        <div class="card-header p-0" id="activateRoleCollapse_heading'.$get_activated_uRole_id.'">
                            <h2 class="mb-0">
                                <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#deactivateURoleCollapse_Div'.$get_activated_uRole_id.'" aria-expanded="true" aria-controls="deactivateURoleCollapse_Div'.$get_activated_uRole_id.'">
                                    <div>
                                        <span class="accordion_title_gray">'.$get_uRole.'</span>
                                        <span class="accordion_subtitle_gray">'; 
                                            if(count($get_assigned_users) > 0){
                                                if(count($get_assigned_users) > 1){
                                                    $output .= count($get_assigned_users). ' Assigned Users Found.';
                                                }else{
                                                    $output .= count($get_assigned_users). ' Assigned User Found.';
                                                }
                                            }else{
                                                $output .= 'No Assigned Users.';
                                            }
                                        $output .='
                                        </span>
                                    </div>
                                    <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="deactivateURoleCollapse_Div'.$get_activated_uRole_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="activateRoleCollapse_heading'.$get_activated_uRole_id.'" data-parent="#activateURoleModalAccordion_Parent'.$get_activated_uRole_id.'">
                            <div class="card-body lightBlue_cardBody mt-2">
                                <span class="lightBlue_cardBody_blueTitle grayed_txt">Assigned Users:</span>';
                                if(count($get_assigned_users) > 0){
                                    foreach($get_assigned_users as $index => $assigned_user){
                                        $output .= '<span class="lightBlue_cardBody_list"><span class="lightBlue_cardBody_listCount grayed_txt">'.($index+1).'.</span> ' .$assigned_user->user_fname. ' ' .$assigned_user->user_lname. '</span>';
                                    }
                                }else{
                                    $output .= '<span class="lightBlue_cardBody_list font-italic">No assigned users found.</span>';
                                }
                                $output .= '
                            </div>
                            <div class="card-body lightBlue_cardBody mt-2 mb-2">
                                <span class="lightBlue_cardBody_blueTitle grayed_txt">Access Controls:</span>';
                                if(!is_null($get_uRole_access)){
                                    foreach(json_decode(json_encode($get_uRole_access), true) as $index => $uRole_access){
                                        $output .= '<span class="lightBlue_cardBody_list"><span class="lightBlue_cardBody_listCount grayed_txt">'.($index+1).'.</span> '.ucwords($uRole_access).'</span>';
                                    }
                                }else{
                                    $output .= '<span class="lightBlue_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> No access controls found.</span>';
                                }
                                $output .= '    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="'.route('user_management.process_activate_role').'" class="activateRoleConfirmationForm" method="POST">
            <div class="modal-body pb-0">';
            if(count($get_assigned_users) > 0){
            $output .= '
                <div class="card-body lightGreen_cardBody shadow-none">
                    <span class="lightGreen_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> Activating <span class="font-weight-bold"> ' .ucwords($get_uRole). ' </span> Role will also activate the assigned users which will give them access to the system again.</span>
                </div>';
            }
            $output .='
                <div class="card-body lightBlue_cardBody shadow-none mt-2">
                    <span class="lightBlue_cardBody_blueTitle">Reason for Activating ' .ucwords($get_uRole). ' Role:</span>
                    <div class="form-group">
                        <textarea class="form-control" id="activate_role_reason" name="activate_role_reason" rows="3" placeholder="Type reason for Activation (optional)"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                <input type="hidden" name="activate_selected_role_id" value="'.$get_activated_uRole_id.'">
                <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                    <button type="submit" class="btn btn-round btn-success btn_show_icon m-0">Activate this Role <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                </div>
            </div>
        </form>
        ';

        echo $output;
    }
    // process role activation
    public function process_activate_role(Request $request){
        // now timestamp
            $now_timestamp = now();
            $activated_txt = 'active';

        // get all request
            $get_activate_selected_role_id = $request->get('activate_selected_role_id');
            $get_respo_user_id             = $request->get('respo_user_id');
            $get_respo_user_lname          = $request->get('respo_user_lname');
            $get_respo_user_fname          = $request->get('respo_user_fname');
            $get_activate_role_reason      = $request->get('activate_role_reason');

        $get_role_details = Userroles::where('uRole_id', $get_activate_selected_role_id)->first();
        if($get_role_details){
            // get details
            $org_uRole_name = $get_role_details->uRole;

            // deactivate the role
            $update_role_status_tbl = DB::table('user_roles_tbl')
            ->where('uRole_id', $get_activate_selected_role_id)
            ->update([
                'uRole_status' => $activated_txt,
                'updated_at'   => $now_timestamp
            ]);

            // if status update was a success
            if($update_role_status_tbl){
                // update role status from users tbl for assigned users
                $check_assigned_users = Users::where('user_role', $org_uRole_name)->count();
                if($check_assigned_users > 0){
                    $update_role_status_tbl = DB::table('users')
                    ->where('user_role', $org_uRole_name)
                    ->update([
                        'user_role_status' => $activated_txt,
                        'updated_at'       => $now_timestamp
                    ]);
                }
            }

            // record role status' updat to user_role_status_updates_tbl
            $rec_role_stats_update_tbl = new Userrolesupdatestatus;
            $rec_role_stats_update_tbl->from_uRole_id  = $get_activate_selected_role_id;
            $rec_role_stats_update_tbl->updated_status = $activated_txt;
            $rec_role_stats_update_tbl->reason_update  = $get_activate_role_reason;
            $rec_role_stats_update_tbl->updated_at     = $now_timestamp;
            $rec_role_stats_update_tbl->updated_by     = $get_respo_user_id;
            $rec_role_stats_update_tbl->save();

            // get uRoleStatUp_id from user_role_status_updates_tbl fro activity reference
            $get_uRoleStatUp_id_tbl = Userrolesupdatestatus::select('uRoleStatUp_id')->where('from_uRole_id', $get_activate_selected_role_id)->latest('updated_at')->first();
            $get_uRoleStatUp_id     = $get_uRoleStatUp_id_tbl->uRoleStatUp_id;

            // record activity
            $rec_activity = new Useractivites;
            $rec_activity->created_at            = $now_timestamp;
            $rec_activity->act_respo_user_id     = $get_respo_user_id;
            $rec_activity->act_respo_users_lname = $get_respo_user_lname;
            $rec_activity->act_respo_users_fname = $get_respo_user_fname;
            $rec_activity->act_type              = 'activate role';
            $rec_activity->act_details           = 'Activated ' .ucwords($org_uRole_name). ' Role.';
            $rec_activity->act_affected_id       = $get_uRoleStatUp_id;
            $rec_activity->save();

            return back()->withSuccessStatus(ucwords($org_uRole_name). ' Role was Activated Successfully.');
        }else{
            return back()->withFailedStatus(ucwords($org_uRole_name). ' Role Activation Failed! try again later.');
        }
    }
    // manage role first modal
    public function manage_role_first_modal(Request $request){
        // get the user's id
            $sel_user_id = $request->get('manage_role_first_id');
        // get user's assigned role
            $get_assigned_role    = Users::select('id', 'user_role', 'user_lname', 'user_fname', 'user_gender')->where('id', $sel_user_id)->first();
            $assigned_role        = $get_assigned_role->user_role;
            $assigned_user_lname  = $get_assigned_role->user_lname;
            $assigned_user_fname  = $get_assigned_role->user_fname;
            $assigned_user_gender = $get_assigned_role->user_gender;
        // his/her text
            if($assigned_user_gender === 'female'){
                $his_her = 'her';
            }else{
                $his_her = 'his';
            }
        // get role information based on user's assigned role
            $get_role_info        = Userroles::where('uRole', $assigned_role)->first();
            $get_uRole_status     = $get_role_info->uRole_status;
            $get_uRole_type       = $get_role_info->uRole_type;
            $get_uRole            = $get_role_info->uRole;
            $get_uRole_access     = $get_role_info->uRole_access;
            $get_assUsers_count   = $get_role_info->assUsers_count;
            $get_uRole_created_by = $get_role_info->created_by;
            $get_uRole_created_at = $get_role_info->created_at;
        // custom values 
        if($get_assUsers_count > 1){
            $s = 's';
        }else{
            $s = '';
        }
        // output data
        $output = '';
        $output .= '
            <div class="modal-body border-0 p-0">
                <div class="cust_modal_body_gray">
                    <div class="card cust_listCard shadow">
                        <div class="card-header cust_listCard_header3">';
                        if($get_assUsers_count < 1){
                            $output .= '
                            <div>
                                <span class="accordion_title">'.$get_uRole.''.$s.'</span>
                                <span class="font-italic text_svms_red"> No Assigned Users. </span>
                            </div>
                            ';
                        }else{
                            $output .= '
                            <div>
                                <span class="accordion_title">'.$get_uRole.''.$s.'</span>
                            </div>
                            <div class="assignedUsersCirclesDiv">';
                            if($get_assUsers_count > 14){
                                $get_only_13 = Users::select('id', 'user_role', 'user_image', 'user_lname', 'user_fname')->where('user_role', $get_uRole)->take(13)->get();
                                $more_count  = $get_assUsers_count - 1;
                                foreach($get_assUsers_count->sortBy('id') as $display_13userImgs){
                                    $output .= '<img class="assignedUsersCirclesImgs2 gray_image_filter whiteImg_border1" src="'.asset('storage/svms/user_images/'.$display_13userImgs->user_image.'').'" alt="assigned user" data-toggle="tooltip" data-placement="top" title="'.$display_13userImgs->user_fname . ' ' .$display_13userImgs->user_lname.'">';
                                }
                                $output .= '
                                <div class="moreImgsCounterDiv2" data-toggle="tooltip" data-placement="top" title="'.$more_count.' more '; if($more_count > 1){ $output .= 'users'; }else{ $output .= 'user';} $output .='">
                                    <span class="moreImgsCounterTxt2">+'.$more_count.'</span>
                                </div>
                                ';
                            }else{
                                $get_all_assigned_users = Users::select('id', 'user_role', 'user_image', 'user_lname', 'user_fname')->where('user_role', $get_uRole)->get();
                                foreach($get_all_assigned_users->sortBy('id') as $assigned_users){
                                    $output .= '<img class="assignedUsersCirclesImgs2 gray_image_filter whiteImg_border1" src="'.asset('storage/svms/user_images/'.$assigned_users->user_image.'').'" alt="assigned user" data-toggle="tooltip" data-placement="top" title="'.$assigned_users->user_fname . ' ' .$assigned_users->user_lname.'">';
                                }
                            }
                            $output .='
                            </div>
                            ';
                        }
                        $output .='
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="card-body lightBlue_cardBody shadow-none mt-1">
                        <span class="lightBlue_cardBody_blueTitle">Activate ' . ucwords($get_uRole) . ' Role First</span>
                        <span class="lightBlue_cardBody_notice"><i class="fa fa-unlock-alt" aria-hidden="true"></i> You must activate <span class="font-weight-bold"> ' .ucwords($get_uRole). ' </span> Role where ' .$assigned_user_fname. ' ' .$assigned_user_lname. ' is assigned, then ' .$his_her. ' account will automatically be activated and will regain access to the system effective immediately after Role Activation.</span>
                    </div>
                    <div class="btn-group d-flex justify-content-end mt-3" role="group" aria-label="Activate User Confirmation">
                        <button type="button" class="btn btn-round btn-secondary btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                        <button type="submit" class="btn btn-round btn_svms_blue btn_show_icon m-0">Manage ' . $get_uRole . ' Role <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        ';
        echo $output;
    }

    // FUNCTIONS FOR SYSTEM USERS
    // deactivate user account modal confirmation
    public function deactivate_user_account_modal(Request $request){
        // get request
            $get_selected_user_id = $request->get('deactivate_user_id');

        // get user's details from users table
            $get_user_details_tbl = Users::where('id', $get_selected_user_id)->first();
            $get_user_email       = $get_user_details_tbl->email;
            $get_user_role        = $get_user_details_tbl->user_role;
            $get_user_type        = $get_user_details_tbl->user_type;
            $get_user_sdca_id     = $get_user_details_tbl->user_sdca_id;
            $get_user_image       = $get_user_details_tbl->user_image;
            $get_user_lname       = $get_user_details_tbl->user_lname;
            $get_user_fname       = $get_user_details_tbl->user_fname;
            $get_user_gender      = $get_user_details_tbl->user_gender;

        $output = '';
        $output .= '
        <div class="modal-body border-0 p-0">
            <div class="cust_modal_body_gray">
                <div class="accordion shadow cust_accordion_div" id="deactivateUserAccountModalAccordion_Parent'.$get_selected_user_id.'">
                    <div class="card custom_accordion_card">';
                    if($get_user_type === 'student'){
                        // get student information from user_students_tbl
                        $get_stud_info_tbl = Userstudents::where('uStud_num', $get_user_sdca_id)->first();
                        $get_uStud_school  = $get_stud_info_tbl->uStud_school;
                        $get_uStud_program = $get_stud_info_tbl->uStud_program;
                        $get_uStud_yearlvl = $get_stud_info_tbl->uStud_yearlvl;
                        $get_uStud_section = $get_stud_info_tbl->uStud_section;
                        $get_uStud_phnum  = $get_stud_info_tbl->uStud_phnum;
                        // display user's info
                        $output .= '
                        <div class="card-header p-0" id="deactivateStudUserAccountCollapse_heading'.$get_selected_user_id.'">
                            <h2 class="mb-0">
                                <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#deactivateStudUserAccountCollapse_Div'.$get_selected_user_id.'" aria-expanded="true" aria-controls="deactivateStudUserAccountCollapse_Div'.$get_selected_user_id.'">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="display_user_image_div text-center">
                                            <img class="display_user_image studImg_border shadow-sm" src="'.asset('storage/svms/user_images/'.$get_user_image).'" alt="student user profile">
                                        </div>
                                        <div class="information_div">
                                            <span class="li_info_title">'.$get_user_fname. ' ' .$get_user_lname.'</span>
                                            <span class="li_info_subtitle">'.ucwords($get_user_role).'</span>
                                        </div>
                                    </div>
                                    <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="deactivateStudUserAccountCollapse_Div'.$get_selected_user_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="deactivateStudUserAccountCollapse_heading'.$get_selected_user_id.'" data-parent="#deactivateUserAccountModalAccordion_Parent'.$get_selected_user_id.'">
                            <div class="card-body lightGreen_cardBody mb-2">
                                <span class="lightGreen_cardBody_greenTitle m-0">Student Number:</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_user_sdca_id.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">School</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_uStud_school.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">Year Level</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_uStud_yearlvl.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">Program/Year/Section</span>
                                <span class="lightGreen_cardBody_list mb-3">'.$get_uStud_program. ' ' .$get_uStud_section.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">Email Address</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_user_email.'</span>';
                                if(!is_null($get_uStud_phnum)){
                                    $output .= '
                                    <span class="lightGreen_cardBody_greenTitle m-0">Phone Number</span>
                                    <span class="lightGreen_cardBody_list">'.$get_uStud_phnum.'</span>
                                    ';
                                }
                                $output .= '
                            </div>
                        </div>
                        ';
                    }else{
                        // get employee information from user_employees_tbl
                        $get_emp_info_tbl = Useremployees::where('uEmp_id', $get_user_sdca_id)->first();
                        $get_uEmp_job_desc = $get_emp_info_tbl->uEmp_job_desc;
                        $get_uEmp_dept = $get_emp_info_tbl->uEmp_dept;
                        $get_uEmp_phnum = $get_emp_info_tbl->uEmp_phnum;
                        // display user's info
                        $output .= '
                        <div class="card-header p-0" id="deactivateEmpUserAccountCollapse_heading'.$get_selected_user_id.'">
                            <h2 class="mb-0">
                                <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#deactivateEmpUserAccountCollapse_Div'.$get_selected_user_id.'" aria-expanded="true" aria-controls="deactivateEmpUserAccountCollapse_Div'.$get_selected_user_id.'">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="display_user_image_div text-center">
                                            <img class="display_user_image empImg_border shadow-sm" src="'.asset('storage/svms/user_images/'.$get_user_image).'" alt="student user profile">
                                        </div>
                                        <div class="information_div">
                                            <span class="li_info_title">'.$get_user_fname. ' ' .$get_user_lname.'</span>
                                            <span class="li_info_subtitle">'.ucwords($get_user_role).'</span>
                                        </div>
                                    </div>
                                    <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="deactivateEmpUserAccountCollapse_Div'.$get_selected_user_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="deactivateEmpUserAccountCollapse_heading'.$get_selected_user_id.'" data-parent="#deactivateUserAccountModalAccordion_Parent'.$get_selected_user_id.'">
                            <div class="card-body lightBlue_cardBody mb-2">
                                <span class="lightBlue_cardBody_blueTitle m-0">Employee ID:</span>
                                <span class="lightBlue_cardBody_list mb-1">'.$get_user_sdca_id.'</span>
                                <span class="lightBlue_cardBody_blueTitle m-0">Job Title</span>
                                <span class="lightBlue_cardBody_list mb-1">'.$get_uEmp_job_desc.'</span>
                                <span class="lightBlue_cardBody_blueTitle m-0">Department</span>
                                <span class="lightBlue_cardBody_list mb-3">'.$get_uEmp_dept.'</span>
                                <span class="lightBlue_cardBody_blueTitle m-0">Email Address</span>
                                <span class="lightBlue_cardBody_list mb-1">'.$get_user_email.'</span>';
                                if(!is_null($get_uEmp_phnum)){
                                    $output .= '
                                    <span class="lightBlue_cardBody_blueTitle m-0">Phone Number</span>
                                    <span class="lightBlue_cardBody_list">'.$get_uEmp_phnum.'</span>
                                    ';
                                }
                                $output .= '
                            </div>
                        </div>
                        ';
                    }
                    $output .= '
                    </div>
                </div>
            </div>
            <form action="'.route('user_management.process_deactivate_user_account').'" class="deacivateUserAccountConfirmationForm" method="POST">
                <div class="modal-body pb-0">
                    <div class="card-body lightRed_cardBody shadow-none">
                        <span class="lightRed_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> <span class="font-weight-bold"> ' .$get_user_fname. ' ' .$get_user_lname. ' </span> will no longer be able to access the system effective immediately after account deactivation.</span>
                    </div>
                    <div class="card-body lightBlue_cardBody shadow-none mt-2">
                        <span class="lightBlue_cardBody_blueTitle">Reason for Deactivating ' .$get_user_lname.'s Account:</span>
                        <div class="form-group">
                            <textarea class="form-control" id="deactivate_user_account_reason" name="deactivate_user_account_reason" rows="3" placeholder="Type reason for Account Deactivation (optional)"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="deactivate_selected_user_id" value="'.$get_selected_user_id.'">
                    <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                    <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                    <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                        <button type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0">Deactivate this Account <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </form>
        </div>
        ';
        // <button type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0">Deactivate this Account <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
        return $output;
    }
    // process user account deactivation
    public function process_deactivate_user_account(Request $request){
        // now timestamp
            $now_timestamp   = now();
            $deactivated_txt = 'deactivated';
            $sq              = "'";

        // get all request
            $get_deactivate_selected_user_id    = $request->get('deactivate_selected_user_id');
            $get_respo_user_id                  = $request->get('respo_user_id');
            $get_respo_user_lname               = $request->get('respo_user_lname');
            $get_respo_user_fname               = $request->get('respo_user_fname');
            $get_deactivate_user_account_reason = $request->get('deactivate_user_account_reason');

        // get user's info
            $get_user_details_tbl = Users::select('user_role', 'user_status', 'user_lname', 'user_fname')->where('id', $get_deactivate_selected_user_id)->first();
            $get_user_role        = $get_user_details_tbl->user_role;
            $get_user_status      = $get_user_details_tbl->user_status;
            $get_user_lname       = $get_user_details_tbl->user_lname;
            $get_user_fname       = $get_user_details_tbl->user_fname;

        // update user's status
            $update_user_status_tbl = DB::table('users')
                ->where('id', $get_deactivate_selected_user_id)
                ->update([
                    'user_status' => $deactivated_txt,
                    'updated_at'  => $now_timestamp
                ]);
        if($update_user_status_tbl){
            // record status update to user_status_updates_tbl
            $rec_user_stats_update_tbl = new Userupdatesstatus;
            $rec_user_stats_update_tbl->from_user_id   = $get_deactivate_selected_user_id;
            $rec_user_stats_update_tbl->updated_status = $deactivated_txt;
            $rec_user_stats_update_tbl->reason_update  = $get_deactivate_user_account_reason;
            $rec_user_stats_update_tbl->updated_at     = $now_timestamp;
            $rec_user_stats_update_tbl->updated_by     = $get_respo_user_id;
            $rec_user_stats_update_tbl->save();

            // get uStatUpdate_id from user_status_updates_tbl as activity reference
            $get_uStatUpdate_id_tbl = Userupdatesstatus::select('uStatUpdate_id')->where('from_user_id', $get_deactivate_selected_user_id)->latest('updated_at')->first();
            $get_uStatUpdate_id     = $get_uStatUpdate_id_tbl->uStatUpdate_id;

            // record activity
            $rec_activity = new Useractivites;
            $rec_activity->created_at            = $now_timestamp;
            $rec_activity->act_respo_user_id     = $get_respo_user_id;
            $rec_activity->act_respo_users_lname = $get_respo_user_lname;
            $rec_activity->act_respo_users_fname = $get_respo_user_fname;
            $rec_activity->act_type              = 'deactivate user';
            $rec_activity->act_details           = 'Deactivated ' .$get_user_fname. ' ' .$get_user_lname.''.$sq.'s Account.';
            $rec_activity->act_affected_id       = $get_uStatUpdate_id;
            $rec_activity->save();

            return back()->withSuccessStatus($get_user_fname. ' ' .$get_user_lname.''.$sq.'s Account was Deactivated Successfully.');
        }else{
            return back()->withFailedStatus($get_user_fname. ' ' .$get_user_lname.''.$sq.'s Account Deactivation Failed! try again later.');
        }
    }
    // activate user account modal confirmation
    public function activate_user_account_modal(Request $request){
        // get request
            $get_selected_user_id = $request->get('activate_user_id');

        // get user's details from users table
            $get_user_details_tbl = Users::where('id', $get_selected_user_id)->first();
            $get_user_email       = $get_user_details_tbl->email;
            $get_user_role        = $get_user_details_tbl->user_role;
            $get_user_status      = $get_user_details_tbl->user_status;
            $get_user_role_status = $get_user_details_tbl->user_role_status;
            $get_user_type        = $get_user_details_tbl->user_type;
            $get_user_sdca_id     = $get_user_details_tbl->user_sdca_id;
            $get_user_image       = $get_user_details_tbl->user_image;
            $get_user_lname       = $get_user_details_tbl->user_lname;
            $get_user_fname       = $get_user_details_tbl->user_fname;
            $get_user_gender      = $get_user_details_tbl->user_gender;

        // his/her txt based on user's gender
            if($get_user_gender === 'male'){
                $gender_txt = 'his';
            }elseif($get_user_gender === 'female'){
                $gender_txt = 'her';
            }else{
                $gender_txt = 'his/her';
            }
        
        // custom values
            $sq = "'";

        $output = '';
        $output .= '
        <div class="modal-body border-0 p-0">
            <div class="cust_modal_body_gray">
                <div class="accordion shadow cust_accordion_div" id="activateUserAccountModalAccordion_Parent'.$get_selected_user_id.'">
                    <div class="card custom_accordion_card">';
                    if($get_user_type === 'student'){
                        // get student information from user_students_tbl
                        $get_stud_info_tbl = Userstudents::where('uStud_num', $get_user_sdca_id)->first();
                        $get_uStud_school  = $get_stud_info_tbl->uStud_school;
                        $get_uStud_program = $get_stud_info_tbl->uStud_program;
                        $get_uStud_yearlvl = $get_stud_info_tbl->uStud_yearlvl;
                        $get_uStud_section = $get_stud_info_tbl->uStud_section;
                        $get_uStud_phnum  = $get_stud_info_tbl->uStud_phnum;
                        // display user's info
                        $output .= '
                        <div class="card-header p-0" id="activateStudUserAccountCollapse_heading'.$get_selected_user_id.'">
                            <h2 class="mb-0">
                                <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#activateStudUserAccountCollapse_Div'.$get_selected_user_id.'" aria-expanded="true" aria-controls="activateStudUserAccountCollapse_Div'.$get_selected_user_id.'">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="display_user_image_div text-center">
                                            <img class="display_user_image studImg_border shadow-sm" src="'.asset('storage/svms/user_images/'.$get_user_image).'" alt="student user profile">
                                        </div>
                                        <div class="information_div">
                                            <span class="li_info_title">'.$get_user_fname. ' ' .$get_user_lname.'</span>
                                            <span class="li_info_subtitle">'.ucwords($get_user_role).'</span>
                                        </div>
                                    </div>
                                    <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="activateStudUserAccountCollapse_Div'.$get_selected_user_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="activateStudUserAccountCollapse_heading'.$get_selected_user_id.'" data-parent="#activateUserAccountModalAccordion_Parent'.$get_selected_user_id.'">
                            <div class="card-body lightGreen_cardBody mb-2">
                                <span class="lightGreen_cardBody_greenTitle m-0">Student Number:</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_user_sdca_id.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">School</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_uStud_school.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">Year Level</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_uStud_yearlvl.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">Program/Year/Section</span>
                                <span class="lightGreen_cardBody_list mb-3">'.$get_uStud_program. ' ' .$get_uStud_section.'</span>
                                <span class="lightGreen_cardBody_greenTitle m-0">Email Address</span>
                                <span class="lightGreen_cardBody_list mb-1">'.$get_user_email.'</span>';
                                if(!is_null($get_uStud_phnum)){
                                    $output .= '
                                    <span class="lightGreen_cardBody_greenTitle m-0">Phone Number</span>
                                    <span class="lightGreen_cardBody_list">'.$get_uStud_phnum.'</span>
                                    ';
                                }
                                $output .= '
                            </div>
                        </div>
                        ';
                    }else{
                        // get employee information from user_employees_tbl
                        $get_emp_info_tbl = Useremployees::where('uEmp_id', $get_user_sdca_id)->first();
                        $get_uEmp_job_desc = $get_emp_info_tbl->uEmp_job_desc;
                        $get_uEmp_dept = $get_emp_info_tbl->uEmp_dept;
                        $get_uEmp_phnum = $get_emp_info_tbl->uEmp_phnum;
                        // display user's info
                        $output .= '
                        <div class="card-header p-0" id="activateEmpUserAccountCollapse_heading'.$get_selected_user_id.'">
                            <h2 class="mb-0">
                                <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#activateEmpUserAccountCollapse_Div'.$get_selected_user_id.'" aria-expanded="true" aria-controls="activateEmpUserAccountCollapse_Div'.$get_selected_user_id.'">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="display_user_image_div text-center">
                                            <img class="display_user_image empImg_border shadow-sm" src="'.asset('storage/svms/user_images/'.$get_user_image).'" alt="student user profile">
                                        </div>
                                        <div class="information_div">
                                            <span class="li_info_title">'.$get_user_fname. ' ' .$get_user_lname.'</span>
                                            <span class="li_info_subtitle">'.ucwords($get_user_role).'</span>
                                        </div>
                                    </div>
                                    <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="activateEmpUserAccountCollapse_Div'.$get_selected_user_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="activateEmpUserAccountCollapse_heading'.$get_selected_user_id.'" data-parent="#activateUserAccountModalAccordion_Parent'.$get_selected_user_id.'">
                            <div class="card-body lightBlue_cardBody mb-2">
                                <span class="lightBlue_cardBody_blueTitle m-0">Employee ID:</span>
                                <span class="lightBlue_cardBody_list mb-1">'.$get_user_sdca_id.'</span>
                                <span class="lightBlue_cardBody_blueTitle m-0">Job Title</span>
                                <span class="lightBlue_cardBody_list mb-1">'.$get_uEmp_job_desc.'</span>
                                <span class="lightBlue_cardBody_blueTitle m-0">Department</span>
                                <span class="lightBlue_cardBody_list mb-3">'.$get_uEmp_dept.'</span>
                                <span class="lightBlue_cardBody_blueTitle m-0">Email Address</span>
                                <span class="lightBlue_cardBody_list mb-1">'.$get_user_email.'</span>';
                                if(!is_null($get_uEmp_phnum)){
                                    $output .= '
                                    <span class="lightBlue_cardBody_blueTitle m-0">Phone Number</span>
                                    <span class="lightBlue_cardBody_list">'.$get_uEmp_phnum.'</span>
                                    ';
                                }
                                $output .= '
                            </div>
                        </div>
                        ';
                    }
                    $output .= '
                    </div>
                </div>
            </div>
            <div class="modal-body pb-0">';
                // get reason of account deactivation from user_status_updates_tbl
                $get_deactivation_details_tbl = Userupdatesstatus::where('from_user_id', $get_selected_user_id)->latest('updated_at')->first();
                if($get_deactivation_details_tbl){
                    $get_deact_det_reason_update  = $get_deactivation_details_tbl->reason_update;
                    $get_deact_det_updated_at     = $get_deactivation_details_tbl->updated_at;
                    $get_deact_det_updated_by     = $get_deactivation_details_tbl->updated_by;

                    // get details of responsible user for deactivating this account
                    $get_respo_deact_this_account_tbl = Users::select('user_role', 'user_lname', 'user_fname')->where('id', $get_deact_det_updated_by)->first();
                    
                    if($get_respo_deact_this_account_tbl){
                        $get_respo_deact_user_role  = $get_respo_deact_this_account_tbl->user_role;
                        $get_respo_deact_user_lname = $get_respo_deact_this_account_tbl->user_lname;
                        $get_respo_deact_user_fname = $get_respo_deact_this_account_tbl->user_fname;
                        $output .= ' 
                        <div class="card-body lightBlue_cardBody shadow-none">
                            <span class="lightBlue_cardBody_blueTitle mb-0">Deactivated by:</span>
                            <span class="lightBlue_cardBody_notice mb-2">
                            '.ucwords($get_respo_deact_user_role).': ' .$get_respo_deact_user_fname. ' ' .$get_respo_deact_user_lname. ' ';
                            if(auth()->user()->id === $get_deact_det_updated_by){
                                $output .= ' <span class="font-italic font-weight-bold"> ~ You</span>';
                            }
                            $output .= '
                            </span>';
                            if($get_deactivation_details_tbl){
                                $output .= '
                                <span class="lightBlue_cardBody_blueTitle mb-0">Deactivated at:</span>
                                <span class="lightBlue_cardBody_notice mb-2">'.date('F d, Y', strtotime($get_deact_det_updated_at)). ' - ' .date('D', strtotime($get_deact_det_updated_at)). ' at ' .date('g:i A', strtotime($get_deact_det_updated_at)).'</span>
                                ';
                                if(!is_null($get_deact_det_reason_update)){
                                    $output .= '
                                    <span class="lightBlue_cardBody_blueTitle mb-0">Reason of Deactivation:</span>
                                    <span class="lightBlue_cardBody_notice">'.$get_deact_det_reason_update. '</span>
                                    ';
                                }
                            }
                            $output .= '
                        </div>
                        ';
                    }
                }
                if($get_user_status === 'deactivated' AND $get_user_role_status === 'deactivated'){
                    $output .= '
                    <div class="card-body lightBlue_cardBody shadow-none mt-2">
                        <span class="lightBlue_cardBody_blueTitle">Activate ' .ucwords($get_user_role).' Role First:</span>
                        <span class="lightBlue_cardBody_notice"><i class="fa fa-unlock-alt" aria-hidden="true"></i> You must activate <span class="font-weight-bold"> ' .ucwords($get_user_role). ' </span> Role first where ' .$get_user_fname. ' ' .$get_user_lname. ' is assigned, then you can activate ' .$gender_txt. ' account.</span>
                    </div>
                    <div class="btn-group d-flex justify-content-end my-3" role="group" aria-label="Ok Confirmation">
                        <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal">Ok <i class="fa fa-thumbs-up btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                    ';
                }else{
                    if($get_user_role_status === 'deactivated'){
                        $output .= '
                        <div class="card-body lightBlue_cardBody shadow-none mt-2">
                            <span class="lightBlue_cardBody_blueTitle">Activate ' .ucwords($get_user_role).' Role First:</span>
                            <span class="lightBlue_cardBody_notice"><i class="fa fa-unlock-alt" aria-hidden="true"></i> You must activate <span class="font-weight-bold"> ' .ucwords($get_user_role). ' </span> Role where ' .$get_user_fname. ' ' .$get_user_lname. ' is assigned, then ' .$gender_txt. ' account will automatically be activated and will regain access to the system effective immediately after Role Activation.</span>
                        </div>
                        <div class="btn-group d-flex justify-content-end my-3" role="group" aria-label="Ok Confirmation">
                            <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal">Ok <i class="fa fa-thumbs-up btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                        ';
                    }else{
                        $output .= '
                        <form action="'.route('user_management.process_activate_user_account').'" class="activateUserAccountConfirmationForm" method="POST">
                            <div class="card-body lightGreen_cardBody shadow-none mt-2">
                                <span class="lightGreen_cardBody_notice"><i class="fa fa-unlock-alt" aria-hidden="true"></i> <span class="font-weight-bold"> ' .$get_user_fname. ' ' .$get_user_lname. ' </span> will be able to access the system again effective immediately after account activation.</span>
                            </div>
                            <div class="card-body lightBlue_cardBody shadow-none mt-2">
                                <span class="lightBlue_cardBody_blueTitle">Reason for Activating ' .$get_user_lname.''.$sq.'s Account:</span>
                                <div class="form-group">
                                    <textarea class="form-control" id="activate_user_account_reason" name="activate_user_account_reason" rows="3" placeholder="Type reason for Account Activation (optional)"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input type="hidden" name="activate_selected_user_id" value="'.$get_selected_user_id.'">
                            <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                            <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                            <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                            <div class="btn-group d-flex justify-content-end my-3" role="group" aria-label="Activate User Confirmation">
                                <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                                <button type="submit" class="btn btn-round btn-success btn_show_icon m-0">Activate this Account <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                            </div>
                        </form>
                        ';
                    }
                }
                $output .= '
            </div>
        </div>
        ';
        return $output;
    }
    // process user account activation
    public function process_activate_user_account(Request $request){
        // now timestamp
            $now_timestamp = now();
            $active_txt    = 'active';
            $sq            = "'";

        // get all request
            $get_activate_selected_user_id    = $request->get('activate_selected_user_id');
            $get_respo_user_id                = $request->get('respo_user_id');
            $get_respo_user_lname             = $request->get('respo_user_lname');
            $get_respo_user_fname             = $request->get('respo_user_fname');
            $get_activate_user_account_reason = $request->get('activate_user_account_reason');

        // get user's info
            $get_user_details_tbl = Users::select('user_role', 'user_status', 'user_lname', 'user_fname')->where('id', $get_activate_selected_user_id)->first();
            $get_user_role        = $get_user_details_tbl->user_role;
            $get_user_status      = $get_user_details_tbl->user_status;
            $get_user_lname       = $get_user_details_tbl->user_lname;
            $get_user_fname       = $get_user_details_tbl->user_fname;

        // update user's status
            $update_user_status_tbl = DB::table('users')
                ->where('id', $get_activate_selected_user_id)
                ->update([
                    'user_status' => $active_txt,
                    'updated_at'  => $now_timestamp
                ]);
        if($update_user_status_tbl){
            // record status update to user_status_updates_tbl
            $rec_user_stats_update_tbl = new Userupdatesstatus;
            $rec_user_stats_update_tbl->from_user_id   = $get_activate_selected_user_id;
            $rec_user_stats_update_tbl->updated_status = $active_txt;
            $rec_user_stats_update_tbl->reason_update  = $get_activate_user_account_reason;
            $rec_user_stats_update_tbl->updated_at     = $now_timestamp;
            $rec_user_stats_update_tbl->updated_by     = $get_respo_user_id;
            $rec_user_stats_update_tbl->save();

            // get uStatUpdate_id from user_status_updates_tbl as activity reference
            $get_uStatUpdate_id_tbl = Userupdatesstatus::select('uStatUpdate_id')->where('from_user_id', $get_activate_selected_user_id)->latest('updated_at')->first();
            $get_uStatUpdate_id     = $get_uStatUpdate_id_tbl->uStatUpdate_id;

            // record activity
            $rec_activity = new Useractivites;
            $rec_activity->created_at            = $now_timestamp;
            $rec_activity->act_respo_user_id     = $get_respo_user_id;
            $rec_activity->act_respo_users_lname = $get_respo_user_lname;
            $rec_activity->act_respo_users_fname = $get_respo_user_fname;
            $rec_activity->act_type              = 'activate user';
            $rec_activity->act_details           = 'Activated ' .$get_user_fname. ' ' .$get_user_lname.''.$sq.'s Account.';
            $rec_activity->act_affected_id       = $get_uStatUpdate_id;
            $rec_activity->save();

            return back()->withSuccessStatus($get_user_fname. ' ' .$get_user_lname.''.$sq.'s Account was Activated Successfully.');
        }else{
            return back()->withFailedStatus($get_user_fname. ' ' .$get_user_lname.''.$sq.'s Account Activation Failed! try again later.');
        }
    }

    // FUNCTIONS FOR USER LOGS MODULE
    // get user's info based on selected filter
    public function users_logs_filter_table_user_info(Request $request){
        if($request->ajax()){
            // get selected user's id
            $selectedUser_id = $request->get('selectedUser_id');
            // get selected user's id from users_tbl
            $user_info = Users::select('id', 'user_lname', 'user_fname')->where('id', $selectedUser_id)->first();
            // $data = '';
            if($user_info){
                $data = $user_info->user_fname . ' ' .$user_info->user_lname;
            }else{
                $data = 'User Not Found!';
            }
            // $data = array(
            //     'user_fname' => $user_fname
            //    );
         
            // echo json_encode($data);
            echo $data;
        }
    }
    // generate users activity logs confirmation on modal
    public function generate_act_logs_confirmation_modal(Request $request){
        // get all request
        // values
        $logs_search    = $request->get('logs_search');
        $logs_userTypes = $request->get('logs_userTypes');
        $logs_userRoles = $request->get('logs_userRoles');
        $logs_users     = $request->get('logs_users');
        $logs_category  = $request->get('logs_category');
        $logs_rangefrom = $request->get('logs_rangefrom');
        $logs_rangeTo   = $request->get('logs_rangeTo');
        $logs_totalData = $request->get('logs_totalData');
        // inner texts
        $txt_logs_userTypes = $request->get('txt_logs_userTypes');
        $txt_logs_userRoles = $request->get('txt_logs_userRoles');
        $txt_logs_users     = $request->get('txt_logs_users');
        $txt_logs_category  = $request->get('txt_logs_category');
        
        // plural 
        if($logs_totalData > 1){
            $s = 's';
        }else{
            $s = '';
        }
        if($logs_totalData > 0){
            $dis_enable_printBtn = '';
            $results_found = $logs_totalData . ' Result'.$s.' Found.';
        }else{
            $dis_enable_printBtn = 'disabled';
            $results_found = 'No Results Found!';
        }
        // date range
        if(!empty($logs_rangefrom) OR $logs_rangefrom != 0){
            $fil_fromDate = date('F d, Y, D, g:i A ', strtotime($logs_rangefrom));
            $fil_fromDateClass = 'font-weight-bold';
        }else{
            $fil_fromDate = ' previous days up';
            $fil_fromDateClass = 'font-weight-normal';
        }
        if(!empty($logs_rangeTo) OR $logs_rangeTo != 0){
            $fil_toDate = date('F d, Y, D, g:i A', strtotime($logs_rangeTo)).'.';
            $fil_toDateClass = 'font-weight-bold';
        }else{
            $fil_toDate = ' this day.';
            $fil_toDateClass = 'font-weight-normal';
        }
        $output = '';
        $output .='
            <div class="modal-body border-0 py-0">
                <div class="card-body lightBlue_cardBody shadow-none">
                    <span class="lightBlue_cardBody_blueTitle">Repoort Contents:</span>
                    <span class="lightBlue_cardBody_notice">The system will generate a report based on the filters you have applied as shown below.</span>
                </div>
                <div class="card-body lightBlue_cardBody shadow-none mt-2">
                    <span class="lightBlue_cardBody_blueTitle">Applied Filters:</span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> '.$txt_logs_userTypes.'</span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> '.$txt_logs_userRoles.'</span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> '.$txt_logs_users.'</span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-check-square-o text-success mr-1" aria-hidden="true"></i> '.$txt_logs_category.'</span>
                    ';
                    if(!empty($logs_search) OR $logs_search != 0){
                        $output .= '
                        <span class="lightBlue_cardBody_blueTitle mt-3">Search Filter:</span>
                        <span class="lightBlue_cardBody_notice"><i class="fa fa-search text-success mr-1" aria-hidden="true"></i> ' . $logs_search . '</span>
                        ';
                    }
                    $output .= '
                    
                    <span class="lightBlue_cardBody_blueTitle mt-3">Date Range:</span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-calendar-check-o text-success mr-1" aria-hidden="true"></i> From <span class="'.$fil_fromDateClass.'"> '.$fil_fromDate . ' </span> to <span class="'.$fil_toDateClass.'">  ' . $fil_toDate.' </span></span>
                </div>
                <div class="card-body lightBlue_cardBody shadow-none mt-2">
                    <span class="lightBlue_cardBody_blueTitle">Total Data:</span>
                    <span class="lightBlue_cardBody_notice"><i class="fa fa-list-ul text-success mr-1" aria-hidden="true"></i> ' . $results_found . '</span>
                </div>
            </div>
            <form action="'.route('user_management.users_logs_report_pdf').'" method="POST" enctype="multipart/form-data" onsubmit="process_ActLogsReport_btn.disabled = true; return true;">
                <div class="modal-footer border-0">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                    <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                    <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">

                    <input type="hidden" name="logs_search" value="'.$logs_search.'">
                    <input type="hidden" name="logs_userTypes" value="'.$logs_userTypes.'">
                    <input type="hidden" name="logs_userRoles" value="'.$logs_userRoles.'">
                    <input type="hidden" name="logs_users" value="'.$logs_users.'">
                    <input type="hidden" name="logs_category" value="'.$logs_category.'">
                    <input type="hidden" name="logs_rangefrom" value="'.$logs_rangefrom.'">
                    <input type="hidden" name="logs_rangeTo" value="'.$logs_rangeTo.'">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                        <button id="process_ActLogsReport_btn" type="submit" class="btn btn-round btn-success btn_show_icon m-0" ' .$dis_enable_printBtn.'>Export Report <i class="nc-icon nc-single-copy-04 btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </form>
        ';
        echo $output;
    }
    // process PDF export of activity logs
    public function users_logs_report_pdf(Request $request){
        // get all request
        $logs_search    = $request->get('logs_search');
        $logs_userTypes = $request->get('logs_userTypes');
        $logs_userRoles = $request->get('logs_userRoles');
        $logs_users     = $request->get('logs_users');
        $logs_category  = $request->get('logs_category');
        $logs_rangefrom = $request->get('logs_rangefrom');
        $logs_rangeTo   = $request->get('logs_rangeTo');
        // check value
        // echo 'logs_search: ' . $logs_search . ' <br/>';
        // echo 'logs_userTypes: ' . $logs_userTypes . ' <br/>';
        // echo 'logs_userRoles: ' . $logs_userRoles . ' <br/>';
        // echo 'logs_users: ' . $logs_users . ' <br/>';
        // echo 'logs_category: ' . $logs_category . ' <br/>';
        // echo 'logs_rangefrom: ' . $logs_rangefrom . ' <br/>';
        // echo 'logs_rangeTo: ' . $logs_rangeTo . ' <br/>';

        if($logs_search != ''){
            $filter_user_logs_table = DB::table('users_activity_tbl')
                                    ->join('users', 'users_activity_tbl.act_respo_user_id', '=', 'users.id')
                                    ->select('users_activity_tbl.*', 'users.id', 'users.user_role', 'users.user_status', 'users.user_role_status', 'users.user_type', 'users.user_sdca_id', 'users.user_image', 'users.user_gender')
                                    ->where(function($query) use ($logs_search) {
                                        return $query->orWhere('users.user_sdca_id', 'like', '%'.$logs_search.'%')
                                                    ->orWhere('users.user_role', 'like', '%'.$logs_search.'%')
                                                    ->orWhere('users.user_type', 'like', '%'.$logs_search.'%')
                                                    ->orWhere('users.user_gender', 'like', '%'.$logs_search.'%')
                                                    ->orWhere('users_activity_tbl.act_respo_users_lname', 'like', '%'.$logs_search.'%')
                                                    ->orWhere('users_activity_tbl.act_respo_users_fname', 'like', '%'.$logs_search.'%')
                                                    ->orWhere('users_activity_tbl.act_type', 'like', '%'.$logs_search.'%')
                                                    ->orWhere('users_activity_tbl.act_details', 'like', '%'.$logs_search.'%');
                                    })
                                    ->where(function($query) use ($logs_userTypes, $logs_userRoles, $logs_users, $logs_category, $logs_rangefrom, $logs_rangeTo){
                                        if($logs_userTypes != 0 OR !empty($logs_userTypes)){
                                            return $query->where('users.user_type', '=', $logs_userTypes);
                                        }
                                        if($logs_userRoles != 0 OR !empty($logs_userRoles)){
                                            return $query->where('users.user_role', '=', $logs_userRoles);
                                        }
                                        if($logs_users != 0 OR !empty($logs_users)){
                                            return $query->where('users.id', '=', $logs_users);
                                        }
                                        if($logs_category != 0 OR !empty($logs_category)){
                                            return $query->where('users_activity_tbl.act_type', '=', $logs_category);
                                        }
                                        if($logs_rangefrom != 0 OR !empty($logs_rangefrom) AND $logs_rangeTo != 0 OR !empty($logs_rangeTo)){
                                            return $query->whereBetween('users_activity_tbl.created_at', [$logs_rangefrom, $logs_rangeTo]);
                                        }
                                    })
                                    ->orderBy('users_activity_tbl.created_at', 'DESC')
                                    ->get();
        }else{
            $filter_user_logs_table = DB::table('users_activity_tbl')
                                    ->join('users', 'users_activity_tbl.act_respo_user_id', '=', 'users.id')
                                    ->select('users_activity_tbl.*', 'users.id', 'users.user_role', 'users.user_status', 'users.user_role_status', 'users.user_type', 'users.user_sdca_id', 'users.user_image', 'users.user_gender')
                                    ->where(function($query) use ($logs_userTypes, $logs_userRoles, $logs_users, $logs_category, $logs_rangefrom, $logs_rangeTo){
                                        if($logs_userTypes != 0 OR !empty($logs_userTypes)){
                                            $query->where('users.user_type', '=', $logs_userTypes);
                                        }
                                        if($logs_userRoles != 0 OR !empty($logs_userRoles)){
                                            $query->where('users.user_role', '=', $logs_userRoles);
                                        }
                                        if($logs_users != 0 OR !empty($logs_users)){
                                            $query->where('users.id', '=', $logs_users);
                                        }
                                        if($logs_category != 0 OR !empty($logs_category)){
                                            $query->where('users_activity_tbl.act_type', '=', $logs_category);
                                        }
                                        if($logs_rangefrom != 0 OR !empty($logs_rangefrom) AND $logs_rangeTo != 0 OR !empty($logs_rangeTo)){
                                            $query->whereBetween('users_activity_tbl.created_at', [$logs_rangefrom, $logs_rangeTo]);
                                        }
                                    })
                                    ->orderBy('users_activity_tbl.created_at', 'DESC')
                                    ->get();
        }

        
        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->loadHTML($act_logs_to_pdf);
        // return back()->withSuccessStatus('Report has been generated successfully.');
        $pdf = PDF::loadView('user_management.report_viewer', compact('filter_user_logs_table'));
        return $pdf->stream('Activity-Logs.pdf');
    }
}
