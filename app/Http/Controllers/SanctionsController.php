<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Users;
use App\Models\Userroles;
use App\Models\Useractivites;
use App\Models\CreatedSanctions;
use Illuminate\Mail\Mailable;

class SanctionsController extends Controller
{
    public function index(Request $request){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('sanctions', $get_uRole_access)){
            if($request->ajax()){
                $output    = '';
                $paginate = '';
                // get all request
                $search_sanctions = $request->get('search_sanctions');

                if($search_sanctions != ''){
                    $filter_created_sanctions_table = CreatedSanctions::where('crSanct_details', 'like', '%'.$search_sanctions.'%')
                            ->orderBy('created_at', 'asc')
                            ->paginate(10);
                    $matched_result_txt = ' Matched Record';    
                }else{
                    $filter_created_sanctions_table = CreatedSanctions::orderBy('created_at', 'asc')
                            ->paginate(10);
                    $matched_result_txt = ' Record';
                }
            }else{
                return view('sanctions.index');
            }
            // total filtered date
            $count_filtered_result = count($filter_created_sanctions_table);
            $total_filtered_result = $filter_created_sanctions_table->total();
            // plural text
            if($total_filtered_result > 0){
                if($total_filtered_result > 1){
                    $s = 's';
                }else{
                    $s = '';
                }
                $total_matched_results = $filter_created_sanctions_table->firstItem() . ' - ' . $filter_created_sanctions_table->lastItem() . ' of ' . $total_filtered_result . ' ' . $matched_result_txt.''.$s;
            }else{
                $s = '';
                $total_matched_results = 'No Records Found';
            }
            $total_row  = $filter_created_sanctions_table->count();
            if($total_row > 0){
                // output matching users found and total data count
                if($total_row > 1){
                    $matched_results  = $total_row . ' Match Found for <span class="font-weight-bold font-italic"> ' .$search_sanctions    .'...</span>';
                    $total_data_count = $total_row . ' Users';
                }else{
                    $matched_results  = $total_row . ' Match Found  for <span class="font-weight-bold font-italic"> ' .$search_sanctions   .'...</span>';
                    $total_data_count = $total_row . ' User';
                }

                // index
                $_i = 0;

                // output results
                foreach($filter_created_sanctions_table as $row){
                    // custom classes
                    $_i++;
                    $apost = "'";
                    $output .='
                        <tr>
                            <td class="py12l12r7 font-weight-bold">'.$_i.'</td>
                            <td>
                                <div class="custom-control custom-checkbox align-items-center">
                                    <input type="checkbox" name="marked_created_sanctions[]" value="'.$row->crSanct_id.'" class="custom-control-input cursor_pointer mark_thisSanction" id="'.$row->crSanct_id.'">
                                    <label class="custom-control-label lightBlue_cardBody_chckboxLabel" for="'.$row->crSanct_id.'">'.preg_replace('/('.$search_sanctions.')/i','<span class="grn_highlight">$1</span>', $row->crSanct_details).'</label>
                                </div>
                            </td>
                        </tr>
                    ';
                }
            }else{
                // output total matched results and total data count
                $total_data_count = $total_row . ' Users';
                $matched_results = 'No Match found for '.$search_sanctions.'...';
                $output .='
                    <tr class="no_data_row">
                        <td align="center" colspan="3">
                            <div class="no_data_div d-flex justify-content-center align-items-center text-center flex-column">
                                <img class="illustration_svg" src="'. asset('storage/svms/illustrations/no_records_found.svg') .'" alt="no matching users found">
                                <span class="font-italic">No Matching Sanctions Found for <span class="font-weight-bold"> ' .$search_sanctions.'...</span></span>
                            </div>
                        </td>
                    </tr>
                ';
            }
            $paginate .= $filter_created_sanctions_table->links('pagination::bootstrap-4');
            $data = array(
                'cs_tbl_data'         => $output,
                'cs_paginate'         => $paginate,
                'cs_total_data_count' => $total_matched_results,
                'matched_searches'    => $matched_results
               );
         
            echo json_encode($data);
        }else{
            return view('profile.access_denied');
        }
    }

    // register sanctions
    public function register_new_sanctions(Request $request){
        // get all request
        $get_respo_user_id    = $request->get('respo_user_id');
        $get_respo_user_lname = $request->get('respo_user_lname');
        $get_respo_user_fname = $request->get('respo_user_fname');  
        $get_new_sanctions    = json_decode(json_encode($request->get('add_new_sanctions')), true);

        // try
        // echo 'responsible user: ' . $get_respo_user_id.': ' .$get_respo_user_fname . ' ' . $get_respo_user_lname .'<br>';
        // echo 'sanctions count: ' . count($get_new_sanctions) .'<br>';
        // echo 'sanctions: <br>';

        // count sanctions
        $count_new_sanctions = count($get_new_sanctions);

        // custom values
        $now_timestamp = now();
        $sq = "'";

        // save
        if($count_new_sanctions > 0){
            if($count_new_sanctions >1){
                $s_s = 's';
            }else{
                $s_s = '';
            }

            // save to created_sanctions_tbl
            foreach($get_new_sanctions as $this_sanction){
                $save_new_sanctions = new CreatedSanctions;
                $save_new_sanctions->crSanct_details = $this_sanction;
                $save_new_sanctions->respo_user_id   = $get_respo_user_id;
                $save_new_sanctions->created_at      = $now_timestamp;
                $save_new_sanctions->save();

                // if saving new sanctions was a success
                if($save_new_sanctions){
                    // get this violation id from violations_tbl
                    $get_new_crSanct_id = CreatedSanctions::select('crSanct_id')->latest('created_at')->first();
                    $new_crSanct_id     = $get_new_crSanct_id->crSanct_id;
                    // record activity
                    $record_act = new Useractivites;
                    $record_act->created_at            = $now_timestamp;
                    $record_act->act_respo_user_id     = $get_respo_user_id;
                    $record_act->act_respo_users_lname = $get_respo_user_lname;
                    $record_act->act_respo_users_fname = $get_respo_user_fname;
                    $record_act->act_type              = 'sanction registration';
                    $record_act->act_details           = 'Created a new Sanction: ' . $this_sanction.'.';
                    $record_act->act_affected_id       = $new_crSanct_id;
                    $record_act->save();
                }else{
                    return back()->withFailedStatus('Sanctions Registration has failed! please try again later.');
                }
            }

            if($record_act){
                return back()->withSuccessStatus(''.$count_new_sanctions . ' new Sanction'.$s_s . ' was registered successfully!');
            }else{
                return back()->withFailedStatus('Recording User Activity has failed!');
            }
        }else{
            return back()->withFailedStatus('There are no Sanctions Created! please try again');
        }
        
    }

    // edit selected sanctions on modal
    public function edit_sanctions_form(Request $request){
        $sel_sanctions = json_decode(json_encode($request->get('sel_sanctions')));
        $count_sel_sanctions = count($sel_sanctions);
        $index = 0;
        $output = '';
        if($count_sel_sanctions > 0){
            if($count_sel_sanctions > 1){

            }else{
                
            }
            $output .= '
            <div class="modal-body">
                <form id="form_updateCreatedSanctions" action="'.route('user_management.process_deactivate_user_account').'" class="deacivateUserAccountConfirmationForm" method="POST">
                    <div class="card-body lightGreen_cardBody">
                        <span class="lightGreen_cardBody_greenTitle mb-1">Selected Sanctions:</span>
                        ';
                        foreach($sel_sanctions as $sel_crSanct_id){
                            $query_selected_sanction = CreatedSanctions::select('crSanct_id','crSanct_details')->where('crSanct_id', $sel_crSanct_id)->first();
                            $sel_crSanct_details = $query_selected_sanction->crSanct_details;
                            $index++;
                            $output .= '
                            <div class="input-group mt-1 mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text txt_iptgrp_append font-weight-bold">'.$index.'.</span>
                                </div>
                                <input type="text" name="update_selected_sanctions[]" class="form-control input_grpInpt3v1" value="'.$sel_crSanct_details.'" placeholder="'.$sel_crSanct_details.'" aria-label="'.$sel_crSanct_details.'" aria-describedby="update-selected-sanctions" required />
                            </div>
                            ';
                        }
                        $output .= '
                        <div class="row mt-3">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span class="cust_info_txtwicon4"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> Click the "Save Changes" Button to Update the selected Sanctions.</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body lightBlue_cardBody mt-3">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span class="cust_info_txtwicon2"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> Updating the Selected Sanctions will also update the "Corresponding Sanctions" that has been applied to "Recorded Violations".</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-0 pb-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="update_sel_sanctions" value="'.json_encode($sel_sanctions).'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <div class="btn-group" role="group" aria-label="actions">
                            <button id="cancel_updateCreatedSanctionsBtn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_updateCreatedSanctionsBtn" type="submit" class="btn btn-round btn-success btn_show_icon m-0">Save Changes <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            ';
        }else{
            $output .= '
            <div class="modal-body">
                <div class="card-body lightRed_cardBody shadow-none">
                    <span class="lightRed_cardBody_notice"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> There are no Sanctions Selected, Please close this modal and select Sanctions to Edit. </span>
                </div>
                <button id="cancel_deactivateUserAccountBtn" type="button" class="btn btn-round btn_svms_blue btn_show_icon mt-3 mb-3 mx-0 float-right" data-dismiss="modal">OK <i class="fa fa-thumbs-o-up btn_icon_show_right" aria-hidden="true"></i></button>
            </div>
            ';
        }
        echo $output;
    }
}
