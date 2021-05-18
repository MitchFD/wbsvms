<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Users;
use App\Models\Userroles;
use App\Models\Useractivites;
use App\Models\Violations;
use App\Models\Sanctions;
use App\Models\Deletedsanctions;
use App\Models\CreatedSanctions;
use App\Models\DeletedCreatedSanctions;
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
                                    <input type="checkbox" name="marked_created_sanctions[]" value="'.$row->crSanct_id.'" class="custom-control-input cursor_pointer mark_thisSanction" id="'.$row->crSanct_id.'" '; $output .= '>
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
            $output .= '
            <div class="modal-body">
                <form id="form_updateCreatedSanctions" action="'.route('sanctions.process_update_selected_sanctions').'" method="POST">
                    <div class="card-body lightGreen_cardBody">
                        <span class="lightGreen_cardBody_greenTitle mb-1">Selected Sanctions:</span>
                        ';
                        foreach($sel_sanctions as $sel_crSanct_id){
                            $query_selected_sanction = CreatedSanctions::select('crSanct_id','crSanct_details')->where('crSanct_id', $sel_crSanct_id)->first();
                            $sel_crSanct_id      = $query_selected_sanction->crSanct_id;
                            $sel_crSanct_details = $query_selected_sanction->crSanct_details;
                            $index++;
                            $output .= '
                            <input type="hidden" name="update_sel_crSanct_ids[]" value="'.$sel_crSanct_id.'">
                            <div class="input-group mt-1 mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text txt_iptgrp_append font-weight-bold">'.$index.'.</span>
                                </div>
                                <input type="text" name="update_selected_sanctions[]" class="form-control input_grpInpt3v1" value="'.$sel_crSanct_details.'" placeholder="'.$sel_crSanct_details.'" aria-label="'.$sel_crSanct_details.'" aria-describedby="update-selected-sanctions" required />
                            </div>
                            ';
                        }
                        $output .= '
                        <hr class="hr_grn">
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
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <div class="btn-group" role="group" aria-label="actions">
                            <button id="cancel_updateCreatedSanctionsBtn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_updateCreatedSanctionsBtn" type="submit" class="btn btn-round btn-success btn_show_icon m-0" disabled>Save Changes <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
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
                <button type="button" class="btn btn-round btn_svms_blue btn_show_icon mt-3 mb-3 mx-0 float-right" data-dismiss="modal">OK <i class="fa fa-thumbs-o-up btn_icon_show_right" aria-hidden="true"></i></button>
            </div>
            ';
        }
        echo $output;
    }
    // process update of selected sanctions
    public function process_update_selected_sanctions(Request $request){
        // get all request
        $get_respo_user_id      = $request->get('respo_user_id');
        $get_respo_user_lname   = $request->get('respo_user_lname');
        $get_respo_user_fname   = $request->get('respo_user_fname');  
        $update_sel_crSanct_ids = json_decode(json_encode($request->get('update_sel_crSanct_ids')), true);
        $get_selected_sanctions = json_decode(json_encode($request->get('update_selected_sanctions')), true);

        // count sanctions
        $count_sel_crSanct_ids = count($update_sel_crSanct_ids);

        // update
        if($count_sel_crSanct_ids > 0){
            // merge selected crSanct_ids and crSanct_details
            $merge_sel_crSancts = array_combine($update_sel_crSanct_ids, $get_selected_sanctions);
            // try  
            // foreach($merge_sel_crSancts as $selSanct_id =>  $selSanct_details){
            //     echo ''.$selSanct_id.': ' .$selSanct_details.'<br>';
            // }

            // custom values
            $now_timestamp = now();
            $sq = "'";

            // plural
            if($count_sel_crSanct_ids > 1){
                $ss_S = 's';
            }else{
                $ss_S = '';
            }

            // query original SAnction Details
            $to_org_sanctDetails = array();
            foreach($update_sel_crSanct_ids as $sel_crSanct_ids){
                $query_org_crSanct_details = CreatedSanctions::select('crSanct_id', 'crSanct_details')
                                            ->where("crSanct_id", $sel_crSanct_ids)
                                            ->first();
                array_push($to_org_sanctDetails, $query_org_crSanct_details->crSanct_details);
            }   
            $index_org_sanctDetails = 0;
            $count_onlyUpdated_sanctDetails = 0;

            // process update
            foreach($merge_sel_crSancts as $selSanct_id =>  $selSanct_details){
                // update created_sanctions_tbl
                $update_created_sanctions_tbl = CreatedSanctions::where('crSanct_id', $selSanct_id)
                                                    ->update([
                                                        'crSanct_details' => $selSanct_details,
                                                        'updated_at'      => $now_timestamp
                                                    ]);
                
                // if updating created sanctions was a success
                if($to_org_sanctDetails[$index_org_sanctDetails] != $selSanct_details){
                    // record activity
                    $record_act = new Useractivites;
                    $record_act->created_at             = $now_timestamp;
                    $record_act->act_respo_user_id      = $get_respo_user_id;
                    $record_act->act_respo_users_lname  = $get_respo_user_lname;
                    $record_act->act_respo_users_fname  = $get_respo_user_fname;
                    $record_act->act_type               = 'sanction update';
                    $record_act->act_details            = 'Updated ' . $to_org_sanctDetails[$index_org_sanctDetails]. ' Sanction to ' . $selSanct_details.'.';
                    $record_act->act_affected_id        = $selSanct_id;
                    $record_act->save();

                    // update sanctions_tbl
                    $checkExist_selSanct = Sanctions::where('sanct_details', '=', $to_org_sanctDetails[$index_org_sanctDetails])->count();
                    if($checkExist_selSanct > 0){
                        $update_sanctions_tbl = Sanctions::where('sanct_details', '=', $to_org_sanctDetails[$index_org_sanctDetails])
                                            ->update([
                                                'sanct_details' => $selSanct_details,
                                                'updated_at'    => $now_timestamp
                                            ]);
                    }
                    
                    $index_org_sanctDetails++;
                    $count_onlyUpdated_sanctDetails++;
                }else{
                    $index_org_sanctDetails++;
                }
            }

            if($update_created_sanctions_tbl){
                if($update_sanctions_tbl){
                    // get updted count of sanctions that has been updted only
                    if($count_onlyUpdated_sanctDetails > 0){
                        if($count_onlyUpdated_sanctDetails > 1){
                            $cOpS_s = 's';
                        }
                        else{
                            $cOpS_s = '';
                        }
                    }else{
                        $cOpS_s = '';
                    }
                    return back()->withSuccessStatus($count_onlyUpdated_sanctDetails. ' Sanction'.$cOpS_s.' was Updated Successfully.');
                }else{
                    return back()->withFailedStatus('Corresponding Sanctions Update has failed! please try again.');
                }
            }else{
                return back()->withFailedStatus('Created Sanctions Update has failed! please try again.');
            }
        }else{
            return back()->withFailedStatus('There are no selected Sanctions! please close this modal and select sanctions to Edit.');
        }
    }

    // delete created sanctions confirmation on modal
    public function delete_sanctions_confirmation_form(Request $request){
        // get all request
        $sel_sanctions = json_decode(json_encode($request->get('sel_sanctions')));
        $count_sel_sanctions = count($sel_sanctions);
        $index = 0;
        $output = '';
        if($count_sel_sanctions > 0){
            $output .= '
            <div class="modal-body">
                <form id="form_deleteCreatedSanctions" action="'.route('sanctions.process_delete_selected_sanctions').'" method="POST">
                    <div class="card-body lightRed_cardBody">
                        <span class="lightRed_cardBody_redTitle mb-1">Select Sanctions To Delete:</span>
                        ';
                        foreach($sel_sanctions as $sel_crSanct_id){
                            $query_selected_sanction = CreatedSanctions::select('crSanct_id','crSanct_details')->where('crSanct_id', $sel_crSanct_id)->first();
                            $sel_crSanct_id      = $query_selected_sanction->crSanct_id;
                            $sel_crSanct_details = $query_selected_sanction->crSanct_details;
                            $index++;
                            $output .= '
                            <div class="form-group mx-0 mt-0 mb-1">
                                <div class="custom-control custom-checkbox align-items-center">
                                    <input type="checkbox" name="delete_selected_sanctions[]" value="'.$sel_crSanct_details.'" class="custom-control-input cursor_pointer sanctDeleteSinglecrSanct" id="'.$sel_crSanct_id.'_deleteThisCrSanct_id" checked>
                                    <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="'.$sel_crSanct_id.'_deleteThisCrSanct_id">'.$sel_crSanct_details.'</label>
                                </div>
                            </div>
                            ';
                        }
                        if($count_sel_sanctions > 1){
                            $ssD_s = 's';
                            $output .= '
                            <hr class="hr_red">
                            <div class="form-group mx-0 mt-0 mb-1">
                                <div class="custom-control custom-checkbox align-items-center">
                                    <input type="checkbox" name="delete_all_created_sanctions" value="delete_all_created_sanctions" class="custom-control-input cursor_pointer" id="sanctDeleteAllcrSanct" checked>
                                    <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="sanctDeleteAllcrSanct">Delete All ('.$count_sel_sanctions.') Sanction'.$ssD_s.'</label>
                                </div>
                            </div>
                            ';
                        }else{
                            $ssD_s = '';
                        }
                        $output .= '
                        <hr class="hr_red">
                        <div class="row mt-3">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                            <span class="cust_info_txtwicon3"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> Deleteing the Selected Sanctions will also delete the "Corresponding Sanctions" that has been applied to "Recorded Violations". You will never be able to recover deleted Sanctions.</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-0 pb-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <div class="btn-group" role="group" aria-label="actions">
                            <button id="cancel_deleteCreatedSanctionsBtn" type="button" class="btn btn-round btn-success btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="submit_deleteCreatedSanctionsBtn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0">Delete Sanctions <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            ';
        }else{
            $output .= '
            <div class="modal-body">
                <div class="card-body lightRed_cardBody shadow-none">
                    <span class="lightRed_cardBody_notice"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> There are no Sanctions Selected, Please close this modal and select Sanctions to Delete. </span>
                </div>
                <button type="button" class="btn btn-round btn_svms_blue btn_show_icon mt-3 mb-3 mx-0 float-right" data-dismiss="modal">OK <i class="fa fa-thumbs-o-up btn_icon_show_right" aria-hidden="true"></i></button>
            </div>
            ';
        }
        echo $output;
    }
    // process deletion of selected created sanctions
    public function process_delete_selected_sanctions(Request $request){
        // get all request
        $get_respo_user_id      = $request->get('respo_user_id');
        $get_respo_user_lname   = $request->get('respo_user_lname');
        $get_respo_user_fname   = $request->get('respo_user_fname'); 
        $get_selected_sanctions = json_decode(json_encode($request->get('delete_selected_sanctions')), true);

        // count sanctions
        $count_sel_crSanct_ids = count($get_selected_sanctions);

        // delete
        if($count_sel_crSanct_ids > 0){
            // custom values
            $now_timestamp = now();
            $sq = "'";

            // plural
            if($count_sel_crSanct_ids > 1){
                $ss_S = 's';
            }else{
                $ss_S = '';
            }

            // process delete
            foreach($get_selected_sanctions as $selSanct_details){
                // save selected sanctions to deleted_created_sanctions_tbl
                $save_deletedSanctions = new DeletedCreatedSanctions;
                $save_deletedSanctions->del_crSanct_details = $selSanct_details;
                $save_deletedSanctions->deleted_by          = $get_respo_user_id;
                $save_deletedSanctions->deleted_at          = $now_timestamp;
                $save_deletedSanctions->save();

                // if back-up deleted sanctions was a success
                if($save_deletedSanctions){
                    // update created_sanctions_tbl
                    $delete_created_sanctions_tbl = CreatedSanctions::where('crSanct_details', '=', $selSanct_details)->delete();
                    // if delete was a success
                    if($delete_created_sanctions_tbl){
                        // query del_id from deleted_created_sanctions (recentyl deleted)
                        $query_recentlyDelcrSanct = DeletedCreatedSanctions::select('del_id')->where('del_crSanct_details', '=', $selSanct_details)->first();
                        $recDelId_crSanct = $query_recentlyDelcrSanct->del_id;
                        // record activity
                        $record_act = new Useractivites;
                        $record_act->created_at             = $now_timestamp;
                        $record_act->act_respo_user_id      = $get_respo_user_id;
                        $record_act->act_respo_users_lname  = $get_respo_user_lname;
                        $record_act->act_respo_users_fname  = $get_respo_user_fname;
                        $record_act->act_type               = 'sanction deletion';
                        $record_act->act_details            = 'deleted Sanction: ' . $selSanct_details.'.';
                        $record_act->act_affected_id        = $recDelId_crSanct;
                        $record_act->save();
                        // if recording user's activity was a success
                        if($record_act){
                            // query andbackup corresponding sanctions
                            $checkExist_selSanct = Sanctions::where('sanct_details', '=', $selSanct_details)->count();
                            if($checkExist_selSanct > 0){
                                // get all same sanctions and backup to deleted_sanctions_tbl
                                $getAll_sameSanctions_info = Sanctions::select('sanct_id', 'stud_num', 'for_viola_id', 'sanct_status', 'sanct_details', 'respo_user_id', 'created_at', 'completed_at')
                                            ->where('sanct_details', '=', $selSanct_details)
                                            ->get();
                                $count_getAll_sameSanctions_info = count($getAll_sameSanctions_info);
                                if($count_getAll_sameSanctions_info > 0){
                                    foreach($getAll_sameSanctions_info as $backThis_delSanct){
                                        // for deleted_sanctions_tbl
                                        $backupSame_deleted = new Deletedsanctions;
                                        $backupSame_deleted->del_from_sanct_id = $backThis_delSanct->sanct_id;
                                        $backupSame_deleted->del_by_user_id    = $get_respo_user_id;
                                        $backupSame_deleted->deleted_at        = $now_timestamp;
                                        $backupSame_deleted->reason_deletion   = 'reason';
                                        $backupSame_deleted->del_stud_num      = $backThis_delSanct->stud_num;
                                        $backupSame_deleted->del_sanct_status  = $backThis_delSanct->sanct_status;
                                        $backupSame_deleted->del_sanct_details = $backThis_delSanct->sanct_details;
                                        $backupSame_deleted->del_for_viola_id  = $backThis_delSanct->for_viola_id;
                                        $backupSame_deleted->del_respo_user_id = $backThis_delSanct->respo_user_id;
                                        $backupSame_deleted->del_created_at    = $backThis_delSanct->created_at;
                                        $backupSame_deleted->del_completed_at  = $backThis_delSanct->completed_at;
                                        $backupSame_deleted->save();

                                        // for violations_tbl
                                        // check if there are violations with selected sanctions
                                        $query_from_violations_tbl = Violations::where('viola_id', $backThis_delSanct->for_viola_id)
                                                                            ->where('stud_num', $backThis_delSanct->stud_num)->count();
                                        if($query_from_violations_tbl > 0){
                                            $query_from_violations_tbl = Violations::select('violation_status', 'has_sanction', 'has_sanct_count')
                                                                            ->where('viola_id', $backThis_delSanct->for_viola_id)
                                                                            ->where('stud_num', $backThis_delSanct->stud_num)
                                                                            ->first();
                                            $fromViola_violaStatus   = $query_from_violations_tbl->violation_status;
                                            $fromViola_hasSanct      = $query_from_violations_tbl->has_sanction;
                                            $fromViola_hasSanctCount = $query_from_violations_tbl->has_sanct_count;

                                            $new_hasSanctCount = 0;
                                            if($fromViola_hasSanctCount > 0){
                                                $new_hasSanctCount = $fromViola_hasSanctCount - 1;
                                            }else{
                                                $new_hasSanctCount = 0;
                                            }

                                            if($new_hasSanctCount > 0){
                                                $update_violations_tbl = Violations::where('viola_id', $backThis_delSanct->for_viola_id)
                                                        ->where('stud_num', $backThis_delSanct->stud_num)
                                                        ->update([
                                                            'violation_status' => $fromViola_violaStatus,
                                                            'has_sanction'     => 1,
                                                            'has_sanct_count'  => $new_hasSanctCount,
                                                            'updated_at'       => $now_timestamp
                                                        ]);
                                            }else{
                                                $update_violations_tbl = Violations::where('viola_id', $backThis_delSanct->for_viola_id)
                                                        ->where('stud_num', $backThis_delSanct->stud_num)
                                                        ->update([
                                                            'violation_status' => 'not cleared',
                                                            'has_sanction'     => 0,
                                                            'has_sanct_count'  => 0,
                                                            'updated_at'       => $now_timestamp
                                                        ]);
                                            }
                                        }
                                    }
                                }
                                // delete corresponding sanctions from sanctions_tbl
                                $delete_corresponding_sanctions_tbl = Sanctions::where('sanct_details', '=', $selSanct_details)->delete();
                            }
                        }else{
                            return back()->withFailedStatus('Recording User'.$sq.'s Activity for deleting Created Sanctions has failed!' );
                        }
                    }else{
                        return back()->withFailedStatus('Deleting Selected Sanctions has failed!');
                    }
                }else{
                    return back()->withFailedStatus('Backup Deleted Sanctions has failed!');
                }
            }
            if($delete_corresponding_sanctions_tbl){
                return back()->withSuccessStatus($count_sel_crSanct_ids. ' Sanction'.$ss_S.' was Deleted Successfully.');
            }else{
                return back()->withFailedStatus('Deleting Corresponding Sancitons has failed!' );
            }
        }else{
            return back()->withFailedStatus('There are no selected Sanctions! please close this modal and select sanctions to Delete.');
        }
    }

}
