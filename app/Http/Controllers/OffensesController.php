<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Userroles;
use App\Models\OffensesCategories;
use App\Models\CreatedOffenses;
use App\Models\EditedOldCreatedOffenses;
use App\Models\EditedNewCreatedOffenses;
use App\Models\DeletedCreatedOffenses;
use Illuminate\Support\Str;
use App\Models\Useractivites;
use CreateDeletedCreatedOffensesTable;

class OffensesController extends Controller
{
    public function index(Request $request){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('sanctions', $get_uRole_access)){
            // get all offenses categories per group
            $query_OffensesCategory = OffensesCategories::select('offCat_id', 'offCategory')->get();
            // check if there are deleted offenses
            $count_deletedOffenses = DeletedCreatedOffenses::count();
            return view('offenses.index')->with(compact('query_OffensesCategory', 'count_deletedOffenses'));
        }else{
            return view('profile.access_denied');
        }
    }

    // add new category form
    public function add_new_category_form(Request $request){
        // output
        $output = '';
        // count all existing categories from offenses_categories_tbl
        $countExisting_OffensesCategory = OffensesCategories::count();

        $output .= '
        <div class="modal-body border-0 p-0">
            <div class="cust_modal_body_gray">
            <span class="cust_status_title mb-2">Existing Categories:</span>
        ';
        if($countExisting_OffensesCategory > 0){
            // get all existing categories
            $query_OffensesCategory = OffensesCategories::select('offCat_id', 'offCategory')->get();
            foreach($query_OffensesCategory as $this_offCategory){
                // category title
                $offCategory_title = ucwords($this_offCategory->offCategory);
                // counts
                $countAll_perOffCategory = CreatedOffenses::where('crOffense_category', '=', $this_offCategory->offCategory)->count();
                $countDefault_perOffCategory = CreatedOffenses::where('crOffense_category', '=', $this_offCategory->offCategory)->where('crOffense_type', '=', 'default')->count();
                $countCustom_perOffCategory = CreatedOffenses::where('crOffense_category', '=', $this_offCategory->offCategory)->where('crOffense_type', '!=', 'default')->count();
                // all offenses counts
                if($countAll_perOffCategory > 0){
                    if($countAll_perOffCategory > 1){
                        $caCO_s = 's';
                    }else{
                        $caCO_s = '';
                    }
                    $txt_totalOffensesCount = ''.$countAll_perOffCategory . ' Registered ' . $offCategory_title.'.';
                }else{
                    $caCO_s = '';
                    $txt_totalOffensesCount = 'No Offenses.';
                }
                // default offenses count
                if($countDefault_perOffCategory > 0){
                    if($countDefault_perOffCategory > 1){
                        $cdCO_s = 's';
                    }else{
                        $cdCO_s = '';
                    }
                    $txt_totalDefaultOffensesCount = ''.$countDefault_perOffCategory . ' Default ' . $offCategory_title.'.';
                }else{
                    $cdCO_s = '';
                    $txt_totalDefaultOffensesCount = 'No Default' . $offCategory_title.'.';
                }
                // custom offenses count
                if($countCustom_perOffCategory > 0){
                    if($countCustom_perOffCategory > 1){
                        $ccCO_s = 's';
                    }else{
                        $ccCO_s = '';
                    }
                    $txt_totalCustomOffensesCount = ''.$countCustom_perOffCategory . ' Custom ' . $offCategory_title.'.';
                }else{
                    $ccCO_s = '';
                    $txt_totalCustomOffensesCount = 'No Custom ' . $offCategory_title.'.';
                }
                // output
                $output .= '
                <div class="accordion shadow cust_accordion_div mb-2" id="existingCategoriesModalAccordion_Parent'.$this_offCategory->offCat_id.'">
                    <div class="card custom_accordion_card">
                        <div class="card-header p-0" id="existingCategoriesCollapse_heading'.$this_offCategory->offCat_id.'">
                            <h2 class="mb-0">
                                <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#existingCategoriesCollapse_Div'.$this_offCategory->offCat_id.'" aria-expanded="true" aria-controls="existingCategoriesCollapse_Div'.$this_offCategory->offCat_id.'">
                                    <div>
                                        <span class="li_info_title">'.$offCategory_title.'</span>
                                        <span class="li_info_subtitle">'.$txt_totalOffensesCount.'</span>
                                    </div>
                                    <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="existingCategoriesCollapse_Div'.$this_offCategory->offCat_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="existingCategoriesCollapse_heading'.$this_offCategory->offCat_id.'" data-parent="#existingCategoriesModalAccordion_Parent'.$this_offCategory->offCat_id.'">
                            ';
                            if($countDefault_perOffCategory > 0){
                                // query all defaults
                                $queryAllDefault_perOffCategory = CreatedOffenses::select('crOffense_id', 'crOffense_details')->where('crOffense_category', '=', $this_offCategory->offCategory)->where('crOffense_type', '=', 'default')->get();
                                $defIndex = 0;
                                $output .= '
                                <div class="row mb-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card-body lightBlue_cardBody">
                                            <span class="lightBlue_cardBody_blueTitle mb-1">Default ' . $offCategory_title.':</span>
                                            ';
                                            foreach($queryAllDefault_perOffCategory as $thisDefault_perOffCategory){
                                                $defIndex++;
                                                $output .= '<span class="lightBlue_cardBody_list"><span class="font-weight-bold mr-1">'.$defIndex.'. </span>' . $thisDefault_perOffCategory->crOffense_details .'</span>';
                                            }
                                            $output .= '
                                        </div>
                                    </div>
                                </div>
                                ';
                            }else{
                                $output .= '
                                <div class="row mb-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card-body lightBlue_cardBody">
                                            <span class="lightBlue_cardBody_list font-italic">No Default ' . $offCategory_title.'...</span>
                                        </div>
                                    </div>
                                </div>
                                ';
                            }
                            if($countCustom_perOffCategory > 0){
                                $queryAllCustom_perOffCategory = CreatedOffenses::select('crOffense_id', 'crOffense_details')->where('crOffense_category', '=', $this_offCategory->offCategory)->where('crOffense_type', '!=', 'default')->get();
                                $custIndex = 0;
                                $output .= '
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card-body lightRed_cardBody">
                                            <span class="lightRed_cardBody_redTitle mb-1"> Custom ' . $offCategory_title.':</span>
                                            ';
                                            foreach ($queryAllCustom_perOffCategory as $this_Custom_perOffCategory){
                                                $custIndex++;
                                                $output .= '<span class="lightRed_cardBody_list cursor_pointer" onclick="editOffenseDetails()"><span class="font-weight-bold mr-1">'.$custIndex.'. </span> ' . $this_Custom_perOffCategory->crOffense_details.'</span>';
                                            }
                                            $output .= '
                                        </div>
                                    </div>
                                </div>
                                ';
                            }
                            $output .= '
                            <div class="row mt-3">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="cust_info_txtwicon"><i class="fa fa-cog mr-1" aria-hidden="true"></i> ' . $txt_totalDefaultOffensesCount . ' </span>  
                                    <span class="cust_info_txtwicon"><i class="fa fa-pencil-square-o mr-1" aria-hidden="true"></i> ' . $txt_totalCustomOffensesCount . ' </span>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }
        }else{

        }
        $output .='
            </div>
            <form id="form_registerNewCategory" action="'.route('offenses.process_register_new_category').'" method="POST" enctype="multipart/form-data">
                <div class="modal-body pb-0">
                    <div class="card-body lightBlue_cardBody shadow-none">
                        <span class="lightBlue_cardBody_blueTitle">New Category Name:</span>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="nc-icon nc-bookmark-2"></i>
                                </span>
                            </div>
                            <input id="register_new_category_name" name="register_new_category_name" type="text" class="form-control" placeholder="Type New Category Name" required>
                        </div>
                    </div>
                    <div class="card-body lightRed_cardBody shadow-none mt-2">
                        <span class="lightRed_cardBody_redTitle">Register Details:</span>
                        <div class="input-group mb-2">
                            <div class="input-group-append">
                                <span class="input-group-text txt_iptgrp_append2 font-weight-bold">1. </span>
                            </div>
                            <input type="text" id="addCategoryDetails_input" name="add_new_category_details[]" class="form-control input_grpInpt2" placeholder="Add New Category Details" aria-label="Add New Category Details" aria-describedby="new-category-details-input">
                            <div class="input-group-append">
                                <button class="btn btn_svms_red m-0" id="categoryDetailsAdd_Btn" type="button" disabled><i class="nc-icon nc-simple-add font-weight-bold" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        <div class="addedInputFields_div">

                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span class="cust_info_txtwicon3"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> You can only register a total of 10 Details for now.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                    <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                    <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                    <div class="btn-group" role="group" aria-label="Add New Category Actions">
                        <button id="cancel_registerNewCategory_btn" type="button" class="btn btn-round btn_svms_red btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                        <button id="process_registerNewCategory_btn" type="submit" class="btn btn-round btn_svms_blue btn_show_icon m-0" disabled>Register New Category <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </form>
        </div>
        ';
        echo $output;
    }
    // process registration of new category
    public function process_register_new_category(Request $request){
        // get all request
            $get_respo_user_id              = $request->get('respo_user_id');
            $get_respo_user_lname           = $request->get('respo_user_lname');
            $get_respo_user_fname           = $request->get('respo_user_fname');   
            $get_register_new_category_name = $request->get('register_new_category_name');   
            $get_add_new_category_details   = json_decode(json_encode($request->get('add_new_category_details')));
        // custom values
            $now_timestamp  = now();
            $toLower_NewCategoryName = Str::lower($get_register_new_category_name);
            $txt_custom = 'custom';
            $sq = "'";
        // try
        // echo 'New Category: ' . $get_register_new_category_name .'<br />';
        // echo 'Details <br />';
        // foreach($get_add_new_category_details as $this_newCatDetails){
        //     echo '     ~ ' .$this_newCatDetails.'<br />';
        // }
        // save new category name to offenses_categories_tbl
            $save_NewCategoryName = new OffensesCategories;
            $save_NewCategoryName->offCategory = $toLower_NewCategoryName;
            $save_NewCategoryName->created_by = $get_respo_user_id;
            $save_NewCategoryName->created_at = $now_timestamp;
            $save_NewCategoryName->save();
        // save new category details to created_offenses_tbl
            if($save_NewCategoryName){
                foreach($get_add_new_category_details as $this_newCatDetails){
                    $save_NewCategoryDetails = new CreatedOffenses;
                    $save_NewCategoryDetails->crOffense_category = $toLower_NewCategoryName;
                    // $save_NewCategoryDetails->crOffense_type     = $txt_custom;
                    $save_NewCategoryDetails->crOffense_details  = $this_newCatDetails;
                    $save_NewCategoryDetails->respo_user_id      = $get_respo_user_id;
                    $save_NewCategoryDetails->created_at         = $now_timestamp;
                    $save_NewCategoryDetails->save();
                }
                // record activity
                if($save_NewCategoryDetails){
                    // get the offCat_id of the latest registered Category name from offenses_categories_tbl
                    $queryLatest_offCatID = OffensesCategories::select('offCat_id')->where('offCategory', '=', $toLower_NewCategoryName)->first();
                    $queryLatest_offCategoryId = $queryLatest_offCatID->offCat_id;
                    // record activity - for creating new Category Name
                    $record_act = new Useractivites;
                    $record_act->created_at            = $now_timestamp;
                    $record_act->act_respo_user_id     = $get_respo_user_id;
                    $record_act->act_respo_users_lname = $get_respo_user_lname;
                    $record_act->act_respo_users_fname = $get_respo_user_fname;
                    $record_act->act_type              = 'offense category creation';
                    $record_act->act_details           = 'Registered New Offense Category: ' . $get_register_new_category_name.'.';
                    $record_act->act_affected_id       = $queryLatest_offCategoryId;
                    $record_act->save();
                    // record each category details
                    if($record_act){
                        foreach($get_add_new_category_details as $this_newCatDetails){
                            // get the crOffense_id of the latest registered Category name from created_offenses_tbl
                            $queryLatest_crOffenseID = CreatedOffenses::select('crOffense_id')
                                                            ->where('crOffense_category', '=', $toLower_NewCategoryName)
                                                            ->where('crOffense_details', '=', $this_newCatDetails)
                                                            ->latest('created_at')
                                                            ->first();
                            $queryLatest_crOffCategoryId = $queryLatest_crOffenseID->crOffense_id;
                            // record activity - for adding new Details to newly registered Category Name
                            $record_act = new Useractivites;
                            $record_act->created_at            = $now_timestamp;
                            $record_act->act_respo_user_id     = $get_respo_user_id;
                            $record_act->act_respo_users_lname = $get_respo_user_lname;
                            $record_act->act_respo_users_fname = $get_respo_user_fname;
                            $record_act->act_type              = 'offense details creation';
                            $record_act->act_details           = 'Registered New Offense Detail: ' . $this_newCatDetails.' to ' . $get_register_new_category_name . ' Category.';
                            $record_act->act_affected_id       = $queryLatest_crOffCategoryId;
                            $record_act->save();
                        }
                        if($record_act){
                            return back()->withSuccessStatus('New Offense Category: ' . $get_register_new_category_name . ' was recorded successfully!');
                        }else{
                            return back()->withFailedStatus('Recording User'.$sq.'s Activity for Creating New Offense Details to ' . $get_register_new_category_name . ' Category has failed! please try again.');
                        }
                    }else{
                        return back()->withFailedStatus('Recording User'.$sq.'s Activity for Creating New Category Name: ' . $get_register_new_category_name . ' has failed! please try again.');
                    }
                }else{
                    return back()->withFailedStatus('Adding New Category Details to ' . $get_register_new_category_name . ' has failed! please try again.');
                }
            }else{
                return back()->withFailedStatus('Adding New Category Name: ' . $get_register_new_category_name . ' has failed! please try again.');
            }

    }

    // NO LONGER USED
    // add new offense details form
    public function add_new_offense_details_form(Request $request){
        // output
        $output = '';
        // count all existing categories from offenses_categories_tbl
        $countExisting_OffensesCategory = OffensesCategories::count();

        $output .= '
        <div class="modal-body border-0 p-0">
            <div class="cust_modal_body_gray">
            <span class="cust_status_title mb-2">Existing Categories & Offenses:</span>
        ';
        if($countExisting_OffensesCategory > 0){
            // get all existing categories
            $query_OffensesCategory = OffensesCategories::select('offCat_id', 'offCategory')->get();
            foreach($query_OffensesCategory as $this_offCategory){
                // category title
                $offCategory_title = ucwords($this_offCategory->offCategory);
                // counts
                $countAll_perOffCategory = CreatedOffenses::where('crOffense_category', '=', $this_offCategory->offCategory)->count();
                $countDefault_perOffCategory = CreatedOffenses::where('crOffense_category', '=', $this_offCategory->offCategory)->where('crOffense_type', '=', 'default')->count();
                $countCustom_perOffCategory = CreatedOffenses::where('crOffense_category', '=', $this_offCategory->offCategory)->where('crOffense_type', '!=', 'default')->count();
                // all offenses counts
                if($countAll_perOffCategory > 0){
                    if($countAll_perOffCategory > 1){
                        $caCO_s = 's';
                    }else{
                        $caCO_s = '';
                    }
                    $txt_totalOffensesCount = ''.$countAll_perOffCategory . ' Registered ' . $offCategory_title.'.';
                }else{
                    $caCO_s = '';
                    $txt_totalOffensesCount = 'No Offenses.';
                }
                // default offenses count
                if($countDefault_perOffCategory > 0){
                    if($countDefault_perOffCategory > 1){
                        $cdCO_s = 's';
                    }else{
                        $cdCO_s = '';
                    }
                    $txt_totalDefaultOffensesCount = ''.$countDefault_perOffCategory . ' Default ' . $offCategory_title.'.';
                }else{
                    $cdCO_s = '';
                    $txt_totalDefaultOffensesCount = 'No Default' . $offCategory_title.'.';
                }
                // custom offenses count
                if($countCustom_perOffCategory > 0){
                    if($countCustom_perOffCategory > 1){
                        $ccCO_s = 's';
                    }else{
                        $ccCO_s = '';
                    }
                    $txt_totalCustomOffensesCount = ''.$countCustom_perOffCategory . ' Custom ' . $offCategory_title.'.';
                }else{
                    $ccCO_s = '';
                    $txt_totalCustomOffensesCount = 'No Custom ' . $offCategory_title.'.';
                }
                // output
                $output .= '
                <div class="accordion shadow cust_accordion_div mb-2" id="existingCategoriesModalAccordion_Parent'.$this_offCategory->offCat_id.'">
                    <div class="card custom_accordion_card">
                        <div class="card-header p-0" id="existingCategoriesCollapse_heading'.$this_offCategory->offCat_id.'">
                            <h2 class="mb-0">
                                <button class="btn btn-block custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#existingCategoriesCollapse_Div'.$this_offCategory->offCat_id.'" aria-expanded="true" aria-controls="existingCategoriesCollapse_Div'.$this_offCategory->offCat_id.'">
                                    <div>
                                        <span class="li_info_title">'.$offCategory_title.'</span>
                                        <span class="li_info_subtitle">'.$txt_totalOffensesCount.'</span>
                                    </div>
                                    <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="existingCategoriesCollapse_Div'.$this_offCategory->offCat_id.'" class="collapse cust_collapse_active cb_t0b12y20" aria-labelledby="existingCategoriesCollapse_heading'.$this_offCategory->offCat_id.'" data-parent="#existingCategoriesModalAccordion_Parent'.$this_offCategory->offCat_id.'">
                            ';
                            if($countDefault_perOffCategory > 0){
                                // query all defaults
                                $queryAllDefault_perOffCategory = CreatedOffenses::select('crOffense_id', 'crOffense_details')->where('crOffense_category', '=', $this_offCategory->offCategory)->where('crOffense_type', '=', 'default')->get();
                                $defIndex = 0;
                                $output .= '
                                <div class="row mb-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card-body lightBlue_cardBody">
                                            <span class="lightBlue_cardBody_blueTitle mb-1">Default ' . $offCategory_title.':</span>
                                            ';
                                            foreach($queryAllDefault_perOffCategory as $thisDefault_perOffCategory){
                                                $defIndex++;
                                                $output .= '<span class="lightBlue_cardBody_list"><span class="font-weight-bold mr-1">'.$defIndex.'. </span>' . $thisDefault_perOffCategory->crOffense_details .'</span>';
                                            }
                                            $output .= '
                                        </div>
                                    </div>
                                </div>
                                ';
                            }else{
                                $output .= '
                                <div class="row mb-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card-body lightBlue_cardBody">
                                            <span class="lightBlue_cardBody_list font-italic">No Default ' . $offCategory_title.'...</span>
                                        </div>
                                    </div>
                                </div>
                                ';
                            }
                            if($countCustom_perOffCategory > 0){
                                $queryAllCustom_perOffCategory = CreatedOffenses::select('crOffense_id', 'crOffense_details')->where('crOffense_category', '=', $this_offCategory->offCategory)->where('crOffense_type', '!=', 'default')->get();
                                $custIndex = 0;
                                $output .= '
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card-body lightRed_cardBody">
                                            <span class="lightRed_cardBody_redTitle mb-1"> Custom ' . $offCategory_title.':</span>
                                            ';
                                            foreach ($queryAllCustom_perOffCategory as $this_Custom_perOffCategory){
                                                $custIndex++;
                                                $output .= '<span class="lightRed_cardBody_list cursor_pointer" onclick="editOffenseDetails()"><span class="font-weight-bold mr-1">'.$custIndex.'. </span> ' . $this_Custom_perOffCategory->crOffense_details.'</span>';
                                            }
                                            $output .= '
                                        </div>
                                    </div>
                                </div>
                                ';
                            }
                            $output .= '
                            <div class="row mt-3">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="cust_info_txtwicon"><i class="fa fa-cog mr-1" aria-hidden="true"></i> ' . $txt_totalDefaultOffensesCount . ' </span>  
                                    <span class="cust_info_txtwicon"><i class="fa fa-pencil-square-o mr-1" aria-hidden="true"></i> ' . $txt_totalCustomOffensesCount . ' </span>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }
        }else{

        }
        $output .='
            </div>
            <form id="form_registerNewOffenses" action="'.route('offenses.process_register_new_offenses').'" method="POST" enctype="multipart/form-data">
                <div class="modal-body pb-0">
                    <div class="card-body lightBlue_cardBody shadow-none mt-2">
                        <div class="form-group cust_fltr_dropdowns_div mb-1">
                            <span class="lightBlue_cardBody_blueTitle">Select Category:</span>
                            <select class="form-control cust_fltr_dropdowns2 drpdwn_arrow2" id="select_offense_category" name="select_offense_category" required>
                            ';
                            if($countExisting_OffensesCategory > 0){
                                // get all existing categories
                                $query_OffensesCategory = OffensesCategories::select('offCat_id', 'offCategory')->get();
                                $output .= '<option value="0" selected disabled>Select Offense Category</option>';
                                foreach($query_OffensesCategory as $this_OffCategoryOption){
                                    $output .= '
                                    <option value="'.$this_OffCategoryOption->offCategory.'">'.ucwords($this_OffCategoryOption->offCategory).'</option>
                                    ';
                                }
                            }else{
                                $output .= '<option value="0" selected disabled>No Categories Found</option>';
                            }
                            $output .= '
                            </select>
                        </div>
                    </div>
                    <div class="card-body lightRed_cardBody shadow-none mt-2">
                        <span class="lightRed_cardBody_redTitle">Register New Offenses:</span>
                        <div class="input-group mb-2">
                            <div class="input-group-append">
                                <span class="input-group-text txt_iptgrp_append2 font-weight-bold">1. </span>
                            </div>
                            <input type="text" id="addNewOffenses_input" name="add_new_offenses[]" class="form-control input_grpInpt2" placeholder="Add New Offenses" aria-label="Add New Offenses" aria-describedby="new-offenses-input">
                            <div class="input-group-append">
                                <button class="btn btn_svms_red m-0" id="NewOffensesAdd_Btn" type="button" disabled><i class="nc-icon nc-simple-add font-weight-bold" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        <div class="addedInputFields_div1">

                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span class="cust_info_txtwicon3"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> You can only register a total of 10 Offenses for now.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                    <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                    <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                    <div class="btn-group" role="group" aria-label="Add New Category Actions">
                        <button id="cancel_registerNewOffenses_btn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                        <button id="process_registerNewOffenses_btn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0" disabled>Register New Offenses <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </form>
        </div>
        ';
        echo $output;
    }
    // process registration of new offenses
    public function process_register_new_offenses(Request $request){
        // get all request
            $get_respo_user_id           = $request->get('respo_user_id');
            $get_respo_user_lname        = $request->get('respo_user_lname');
            $get_respo_user_fname        = $request->get('respo_user_fname');   
            $get_select_offense_category = $request->get('select_offense_category');   
            $get_add_new_offenses        = json_decode(json_encode($request->get('add_new_offenses')));
        // custom values
            $now_timestamp  = now();
            $toLower_NewCategoryName = Str::lower($get_select_offense_category);
            $toUcWords_NewCategoryName = ucwords($get_select_offense_category);
            $txt_custom = 'custom';
            $sq = "'";
        // try
            // echo 'selected category: ' . $toUcWords_NewCategoryName . ' <br/>';
            // echo 'New Offenses:';
            // foreach($get_add_new_offenses as $this_Newoffenses){
            //     echo '      ~ ' . $this_Newoffenses . '<br/>';
            // }
        // save new offenses
        foreach($get_add_new_offenses as $this_Newoffenses){
            $save_NewOffensesDetails = new CreatedOffenses;
            $save_NewOffensesDetails->crOffense_category = $toLower_NewCategoryName;
            // $save_NewOffensesDetails->crOffense_type     = $txt_custom;
            $save_NewOffensesDetails->crOffense_details  = $this_Newoffenses;
            $save_NewOffensesDetails->respo_user_id      = $get_respo_user_id;
            $save_NewOffensesDetails->created_at         = $now_timestamp;
            $save_NewOffensesDetails->save();

            if($save_NewOffensesDetails){
                // get the crOffense_id of the latest registered Category name from created_offenses_tbl
                $queryLatest_crOffenseID = CreatedOffenses::select('crOffense_id')
                                                ->where('crOffense_category', '=', $toLower_NewCategoryName)
                                                ->where('crOffense_details', '=', $this_Newoffenses)
                                                ->latest('created_at')
                                                ->first();
                $queryLatest_crOffCategoryId = $queryLatest_crOffenseID->crOffense_id;
                // record activity - for adding new Details to newly registered Category Name
                $record_act = new Useractivites;
                $record_act->created_at            = $now_timestamp;
                $record_act->act_respo_user_id     = $get_respo_user_id;
                $record_act->act_respo_users_lname = $get_respo_user_lname;
                $record_act->act_respo_users_fname = $get_respo_user_fname;
                $record_act->act_type              = 'offense details creation';
                $record_act->act_details           = 'Registered New Offense Detail: ' . $this_Newoffenses.' to ' . $toUcWords_NewCategoryName . ' Category.';
                $record_act->act_affected_id       = $queryLatest_crOffCategoryId;
                $record_act->save();
            }else{
                return back()->withFailedStatus('Adding New Offenses to ' . $toUcWords_NewCategoryName . ' Category has failed! please try again.');
            }
        }
        if($save_NewOffensesDetails AND $record_act){
            return back()->withSuccessStatus('New Offenses has been successfully registered for ' . $toUcWords_NewCategoryName . ' Category.');
        }else{
            return back()->withFailedStatus('Adding New Offenses to ' . $toUcWords_NewCategoryName . ' Category has failed! please try again.');
        }
    }
    // edit selected offense form
    public function edit_selected_offense_form(Request $request){
        echo 'edit?';
    }

    // UPDATED FUNCTIONS
    // add new offense details to selected category
    public function add_new_offense_details_to_selected_category_form(Request $request){
        // get all request
        $sel_offCategory_id = $request->get('sel_offCategory_id');
        // output
        $output = '';
        $output .= '
        <div class="modal-body border-0 p-0">
            <div class="cust_modal_body_gray">
            ';
            // get selected category info
            $query_selCategory = OffensesCategories::select('offCategory')->where('offCat_id', '=', $sel_offCategory_id)->first();
            $selCategory_title = ucwords($query_selCategory->offCategory);
            $toLower_selCategory_title = Str::lower($selCategory_title);
            // count all registered offense details 
            $count_regOffDetails = CreatedOffenses::where('crOffense_category', '=', $toLower_selCategory_title)->count();
            if($count_regOffDetails > 0){
                if($count_regOffDetails > 1){
                    $cROD_s = 's';
                }else{
                    $cROD_s = '';
                }
                $txt_regOffDetails = ''.$count_regOffDetails . ' Registered ' . $selCategory_title.'.'; 
            }else{
                $cROD_s = '';
                $txt_regOffDetails = ''.$count_regOffDetails . ' Registered ' . $selCategory_title.'.'; 
            }
            // count all default registered offense details
            $count_defaultRegOffDetails = CreatedOffenses::where('crOffense_category', '=', $toLower_selCategory_title)->where('crOffense_type', '=', 'default')->count();
            if($count_defaultRegOffDetails > 0){
                if($count_defaultRegOffDetails > 1){
                    $cDROD_s = 's';
                }else{
                    $cDROD_s = '';
                }
                $txt_defaultRegOffDetails = ''.$count_defaultRegOffDetails . ' Default ' . $selCategory_title.'.'; 
            }else{
                $cDROD_s = '';
                $txt_defaultRegOffDetails = ''.$count_defaultRegOffDetails . ' Default ' . $selCategory_title.'.'; 
            }
            // count all custom registered offense details
            $count_customRegOffDetails = CreatedOffenses::where('crOffense_category', '=', $toLower_selCategory_title)->where('crOffense_type', '!=', 'default')->count();
            if($count_customRegOffDetails > 0){
                if($count_customRegOffDetails > 1){
                    $cCROD_s = 's';
                }else{
                    $cCROD_s = '';
                }
                $txt_customRegOffDetails = ''.$count_customRegOffDetails . ' Custom ' . $selCategory_title.'.'; 
            }else{
                $cCROD_s = '';
                $txt_customRegOffDetails = ''.$count_customRegOffDetails . ' Custom ' . $selCategory_title.'.'; 
            }
            $output .= '
                <div class="accordion shadow cust_accordion_div" id="existingCategoriesModalAccordion_Parent'.$sel_offCategory_id.'">
                    <div class="card custom_accordion_card">
                        <div class="card-header p-0" id="existingCategoriesCollapse_heading'.$sel_offCategory_id.'">
                            <h2 class="mb-0">
                                <button class="btn btn-block cb_x12y20 custom2_btn_collapse d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#selCategoryDetailsCollapse_Div'.$sel_offCategory_id.'" aria-expanded="true" aria-controls="selCategoryDetailsCollapse_Div'.$sel_offCategory_id.'">
                                    <div>
                                        <span class="li_info_title">'.$selCategory_title.'</span>
                                        <span class="li_info_subtitle">'.$txt_regOffDetails.'</span>
                                    </div>
                                    <i class="nc-icon nc-minimal-down custom2_btn_collapse_icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="selCategoryDetailsCollapse_Div'.$sel_offCategory_id.'" class="collapse cust_collapse_active cb_t0b12y20 active show" aria-labelledby="existingCategoriesCollapse_heading'.$sel_offCategory_id.'" data-parent="#existingCategoriesModalAccordion_Parent'.$sel_offCategory_id.'">
                            ';
                            if($count_defaultRegOffDetails > 0){
                                // query all defaults
                                $queryAllDefault_perOffCategory = CreatedOffenses::select('crOffense_id', 'crOffense_details')->where('crOffense_category', '=', $toLower_selCategory_title)->where('crOffense_type', '=', 'default')->get();
                                $defIndex = 0;
                                $output .= '
                                <div class="row mb-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card-body lightBlue_cardBody">
                                            <span class="lightBlue_cardBody_blueTitle mb-1">Default ' . $selCategory_title.':</span>
                                            ';
                                            foreach($queryAllDefault_perOffCategory as $thisDefault_perOffCategory){
                                                $defIndex++;
                                                $output .= '<span class="lightBlue_cardBody_list"><span class="font-weight-bold mr-1">'.$defIndex.'. </span>' . $thisDefault_perOffCategory->crOffense_details .'</span>';
                                            }
                                            $output .= '
                                        </div>
                                    </div>
                                </div>
                                ';
                            }else{
                                $output .= '
                                <div class="row mb-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card-body lightBlue_cardBody">
                                            <span class="lightBlue_cardBody_list font-italic">No Default ' . $selCategory_title.'...</span>
                                        </div>
                                    </div>
                                </div>
                                ';
                            }
                            if($count_customRegOffDetails > 0){
                                $queryAllCustom_perOffCategory = CreatedOffenses::select('crOffense_id', 'crOffense_details')->where('crOffense_category', '=', $toLower_selCategory_title)->where('crOffense_type', '!=', 'default')->get();
                                $custIndex = 0;
                                $output .= '
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card-body lightRed_cardBody">
                                            <span class="lightRed_cardBody_redTitle mb-1"> Custom ' . $selCategory_title.':</span>
                                            ';
                                            foreach ($queryAllCustom_perOffCategory as $this_Custom_perOffCategory){
                                                $custIndex++;
                                                $output .= '<span class="lightRed_cardBody_list cursor_pointer" onclick="editOffenseDetails()"><span class="font-weight-bold mr-1">'.$custIndex.'. </span> ' . $this_Custom_perOffCategory->crOffense_details.'</span>';
                                            }
                                            $output .= '
                                        </div>
                                    </div>
                                </div>
                                ';
                            }
                            $output .= '
                            <div class="row mt-3">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-list-ul mr-1" aria-hidden="true"></i> ' . $txt_regOffDetails.'</span>  
                                    <span class="cust_info_txtwicon"><i class="fa fa-cog mr-1" aria-hidden="true"></i> ' . $txt_defaultRegOffDetails.'</span>  
                                    <span class="cust_info_txtwicon"><i class="fa fa-pencil-square-o mr-1" aria-hidden="true"></i> ' . $txt_customRegOffDetails.'</span>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form id="form_registerNewOffenseDetails" action="'.route('offenses.process_register_new_offense_details_to_selected_category').'" method="POST" enctype="multipart/form-data">
                <div class="modal-body pb-0">
                    <div class="card-body lightBlue_cardBody shadow-none">
                        <span class="lightBlue_cardBody_blueTitle">Set as:</span>
                        <select class="form-control cust_fltr_dropdowns2 drpdwn_arrow2" id="offense_details_type" name="offense_details_type" required>
                            <option value="custom" selected>Custom ' . $selCategory_title.'</option>
                            <option value="default">Default ' .$selCategory_title.'</option>
                        </select>
                    </div>
                    <div class="card-body lightRed_cardBody shadow-none mt-2">
                        <span class="lightRed_cardBody_redTitle">Register ' . $selCategory_title . ' Details:</span>
                        <div class="input-group mb-2">
                            <div class="input-group-append">
                                <span class="input-group-text txt_iptgrp_append2 font-weight-bold">1. </span>
                            </div>
                            <input type="text" id="addNewOffenseDetails_input" name="add_new_offense_details[]" class="form-control input_grpInpt2" placeholder="Add New Offense Details" aria-label="Add New Offense Details" aria-describedby="new-offense-details-input">
                            <div class="input-group-append">
                                <button class="btn btn_svms_red m-0" id="newOffenseDetailsAdd_Btn" type="button" disabled><i class="nc-icon nc-simple-add font-weight-bold" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        <div class="nOD_addedInputFields_div">

                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span class="cust_info_txtwicon3"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> You can only register a total of 10 ' . $selCategory_title . ' per form.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                    <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                    <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                    <input type="hidden" name="selected_offense_category" value="'.$toLower_selCategory_title.'">
                    <div class="btn-group" role="group" aria-label="Add New Offense Details Actions">
                        <button id="cancel_registerNewOffenseDetails_btn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                        <button id="process_registerNewOffenseDetails_btn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0" disabled>Register New Offenses <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </form>
        </div>
        ';
        echo $output;
    }
    // process registration of new offense details to selected category
    public function process_register_new_offense_details_to_selected_category(Request $request){
        // get all request
            $get_respo_user_id             = $request->get('respo_user_id');
            $get_respo_user_lname          = $request->get('respo_user_lname');
            $get_respo_user_fname          = $request->get('respo_user_fname');   
            $get_selected_offense_category = $request->get('selected_offense_category');   
            $get_offense_details_type      = $request->get('offense_details_type');   
            $get_add_new_offense_details   = json_decode(json_encode($request->get('add_new_offense_details')));
        // custom values
            $to_Ucwords_selectedOffenseCategory = ucwords($get_selected_offense_category);
            $to_Ucwords_selectedOffenseType = ucwords($get_offense_details_type);
            $toLower_selectedOffenseCategory = Str::lower($get_selected_offense_category);
            $toLower_selectedOffenseType = Str::lower($get_offense_details_type);
            $now_timestamp  = now();
            $sq = "'";
        // try
            // echo 'selected category: ' . $get_selected_offense_category . '<br>';
            // echo 'selected Type: ' . $get_offense_details_type . '<br>';
            // echo 'New offense details: <br>';
            // foreach($get_add_new_offense_details as $addThis_newOffenseDetail){
            //     echo '      - ' . $addThis_newOffenseDetail . '<br>';
            // }
        // save new offense details to created_offenses_tbl
        $count_newOffenseDetails = count($get_add_new_offense_details);
        if($count_newOffenseDetails > 0){
            foreach($get_add_new_offense_details as $addThis_newOffenseDetail){
                $save_newOffenseDetails = new CreatedOffenses;
                $save_newOffenseDetails->crOffense_category = $toLower_selectedOffenseCategory;
                $save_newOffenseDetails->crOffense_type     = $toLower_selectedOffenseType;
                $save_newOffenseDetails->crOffense_details  = $addThis_newOffenseDetail;
                $save_newOffenseDetails->respo_user_id      = $get_respo_user_id;
                $save_newOffenseDetails->created_at         = $now_timestamp;
                $save_newOffenseDetails->save();
            }
            // if registration was a success
            if($save_newOffenseDetails){
                // record activity
                foreach($get_add_new_offense_details as $thisAdded_newOffenseDetail){
                    // get the crOffense_id of the latest registered Offense details from created_offenses_tbl
                    $queryLatest_crOffenseID = CreatedOffenses::select('crOffense_id')
                    ->where('crOffense_category', '=', $toLower_selectedOffenseCategory)
                    ->where('crOffense_type', '=', $toLower_selectedOffenseType)
                    ->where('crOffense_details', '=', $thisAdded_newOffenseDetail)
                    ->latest('created_at')
                    ->first();
                    $queryLatest_crOffCategoryId = $queryLatest_crOffenseID->crOffense_id;
                    // record user's activity
                    $record_act = new Useractivites;
                    $record_act->created_at            = $now_timestamp;
                    $record_act->act_respo_user_id     = $get_respo_user_id;
                    $record_act->act_respo_users_lname = $get_respo_user_lname;
                    $record_act->act_respo_users_fname = $get_respo_user_fname;
                    $record_act->act_type              = 'new offense creation';
                    $record_act->act_details           = 'Registered New Offense Detail: ' . $thisAdded_newOffenseDetail.' as ' . $to_Ucwords_selectedOffenseType . ' option to ' . $to_Ucwords_selectedOffenseCategory . ' Category.';
                    $record_act->act_affected_id       = $queryLatest_crOffCategoryId;
                    $record_act->save();
                }
                if($record_act){
                    return back()->withSuccessStatus('New ' . $to_Ucwords_selectedOffenseCategory . ' were recorded successfully!');
                }else{
                    return back()->withFailedStatus('Recording User'.$sq.'s Activity for Creating New Offense Details to ' . $to_Ucwords_selectedOffenseCategory . ' Category has failed! please try again.');
                }
            }else{
                return back()->withFailedStatus('Saving New ' . $to_Ucwords_selectedOffenseCategory . ' has failed! please try again later.');
            }
        }else{
            return back()->withFailedStatus('You have not provided any new offense details! please try again.');
        }
        
    }
    // edit selected Offense details form
    public function edit_selected_offense_details_form(Request $request){
        // get all request
        $get_sel_offCategory_id = $request->get('sel_offCategory_id');   
        $get_sel_offDetails_ids = json_decode(json_encode($request->get('sel_offDetails_ids')));

        // try
        // echo 'selected category id: ' . $get_sel_offCategory_id. '<br>';
        // echo 'selected offense ids: <br>';
        // if(!empty($get_sel_offDetails_ids) OR !is_null($get_sel_offDetails_ids)){
        //     foreach($get_sel_offDetails_ids as $editThis_selOffDetailID){
        //         echo '          - ' . $editThis_selOffDetailID . '<br>';
        //     }
        // }else{
        //     echo 'No Selected Offenses!';
        // }

        // output
        $output = '';

        // get selected Category Name
        $query_selOffCat_Name = OffensesCategories::select('offCategory')->where('offCat_id', '=', $get_sel_offCategory_id)->first();
        $txt_selOffCatName = $query_selOffCat_Name->offCategory;
        $toLower_selOffCat_Name = Str::lower($txt_selOffCatName);
        $toUcwords_selOffCat_Name = ucwords($txt_selOffCatName);

        $output .= '
        <div class="modal-body border-0 p-0">
            ';
            if(!empty($get_sel_offDetails_ids) OR !is_null($get_sel_offDetails_ids)){
                $output .= '
                <form id="form_editOffenseDetails" action="'.route('offenses.process_update_selected_offense_details').'" method="POST" enctype="multipart/form-data">
                    <div class="modal-body pt-0 pb-0">
                        <div class="card-body lightBlue_cardBody shadow-none mt-2">
                            <span class="lightBlue_cardBody_blueTitle">Selected ' .$txt_selOffCatName.':</span>
                            ';
                            $selOff_index = 1;
                            foreach($get_sel_offDetails_ids as $editThis_selOffDetailID){
                                $query_selOffDetail_Info = CreatedOffenses::select('crOffense_type', 'crOffense_details')->where('crOffense_id', '=', $editThis_selOffDetailID)->first();
                                $query_selOffType = $query_selOffDetail_Info->crOffense_type;
                                $query_selOffDetail = $query_selOffDetail_Info->crOffense_details;
                                // selected type
                                $toLower_query_selOffType = Str::lower($query_selOffType);
                                if($toLower_query_selOffType == 'default'){
                                    $selectedType_default = 'selected';
                                }else{
                                    $selectedType_default = '';
                                }
                                if($toLower_query_selOffType != 'default'){
                                    $selectedType_custom = 'selected';
                                }else{
                                    $selectedType_custom = '';
                                }
                                $output .= '
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <span class="input-group-text txt_iptgrp_append4 font-weight-bold">'.$selOff_index++.'. </span>
                                    </div>
                                    <input type="text" id="addNewOffenses_input" name="edit_offense_details[]" class="form-control input_grpInpt4" value="'.$query_selOffDetail.'" placeholder="'.$query_selOffDetail.'" aria-label="'.$query_selOffDetail.'" aria-describedby="edit-offense-detail-input">
                                    <div class="input-group-append ml-2">
                                        <select name="edit_offense_types[]" class="form-control cust_fltr_dropdowns3 drpdwn_arrow3" id="inputGroupSelect01">
                                            <option value="default"' . $selectedType_default.'>Default</option>
                                            <option value="custom" ' . $selectedType_custom.'>Custom</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="selected_offDetailsIds[]" value="'.$editThis_selOffDetailID.'">
                                ';
                            }
                            $output .= '
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <input type="hidden" name="selected_offCategoryID" value="'.$get_sel_offCategory_id.'">
                        <div class="btn-group" role="group" aria-label="Edit Offense Details Actions">
                            <button id="cancel_editOffenseDetails_btn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="process_editOffenseDetails_btn" type="submit" class="btn btn-round btn_svms_red btn_show_icon m-0" disabled>Save Changes <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
                ';
            }else{
                $output .= '
                <div class="modal-body pb-0">
                    <div class="card-body lightRed_cardBody shadow-none">
                        <span class="lightRed_cardBody_redTitle"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> No Selected ' . $toUcwords_selOffCat_Name.':</span>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span class="cust_info_txtwicon3">Please close this modal and select ' . $toUcwords_selOffCat_Name . ' first to edit offense details.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <div class="btn-group" role="group" aria-label="Ok">
                        <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal">Ok <i class="fa fa-thumbs-up btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
                ';
            }
            $output .='
        </div>
        ';

        echo $output;
    }
    // process update of selected offense details
    public function process_update_selected_offense_details(Request $request){
        // get all request
            $get_respo_user_id          = $request->get('respo_user_id');
            $get_respo_user_lname       = $request->get('respo_user_lname');
            $get_respo_user_fname       = $request->get('respo_user_fname');
            $get_selected_offCategoryID = $request->get('selected_offCategoryID');
            $get_selected_offDetailsIds = json_decode(json_encode($request->get('selected_offDetailsIds')), true);
            $get_edit_offense_details   = json_decode(json_encode($request->get('edit_offense_details')), true);
            $get_edit_offense_types     = json_decode(json_encode($request->get('edit_offense_types')), true);
        // get selected Category Name
            $query_selOffCat_Name = OffensesCategories::select('offCategory')->where('offCat_id', '=', $get_selected_offCategoryID)->first();
            $txt_selOffCatName = $query_selOffCat_Name->offCategory;
            $toLower_selOffCat_Name = Str::lower($txt_selOffCatName);
            $toUcwords_selOffCat_Name = ucwords($txt_selOffCatName);
            $now_timestamp  = now();
            $sq = "'";
        // try
            $toArray_offIds = array();
            $toArray_offTypes = array();
            $toArray_offDetails = array();
            $combine_offIdsnTypesnDetails = [];
            foreach($get_edit_offense_types as $pushThis_offTypes){
                array_push($toArray_offTypes, $pushThis_offTypes);
            }
            foreach($get_edit_offense_details as $pushThis_offDetails){
                array_push($toArray_offDetails, $pushThis_offDetails);
            }
            foreach($get_selected_offDetailsIds as $pushThis_offIds){
                array_push($toArray_offIds, $pushThis_offIds);
            }
            foreach(json_decode(json_encode($toArray_offIds), true) as $ix => $indexThis_offIds){
                $combine_offIdsnTypesnDetails[] = [ $indexThis_offIds => [ $toArray_offTypes[$ix],$toArray_offDetails[$ix] ] ];
            }
            // echo ''. json_encode($combine_offIdsnTypesnDetails) . ' <br>';
        // update created_offenses_tbl
            foreach(json_decode(json_encode($combine_offIdsnTypesnDetails), true) as $this_combinedOffDetails){
                foreach(json_decode(json_encode($this_combinedOffDetails), true) as $index => $updateThis_offDetails){
                    // echo ''. $index . ' => '. json_encode($updateThis_offDetails) . '<br>';
                    // get original offense details
                    $queryOriginal_offDetails = CreatedOffenses::select('crOffense_type', 'crOffense_details')->where('crOffense_id', '=', $index)->where('crOffense_category', '=', $toLower_selOffCat_Name)->first();
                    $org_crOffenseType = $queryOriginal_offDetails->crOffense_type;
                    $org_crOffenseDetails = $queryOriginal_offDetails->crOffense_details;

                    // save original to edited_old_created_offenses_tbl & edited_new_created_offenses_tbl
                    $backedUpOiginal_offDetails = new EditedOldCreatedOffenses;
                    $backedUpOiginal_offDetails->eOld_from_crOffense_id  = $index;
                    $backedUpOiginal_offDetails->eOld_crOffense_category = $toLower_selOffCat_Name;
                    $backedUpOiginal_offDetails->eOld_crOffense_type     = $org_crOffenseType;
                    $backedUpOiginal_offDetails->eOld_crOffense_details  = $org_crOffenseDetails;
                    $backedUpOiginal_offDetails->edited_by               = $get_respo_user_id;
                    $backedUpOiginal_offDetails->edited_at               = $now_timestamp;
                    $backedUpOiginal_offDetails->save();
                    // if original offense details was successfully saved to edited_old_created_offenses_tbl
                    if($backedUpOiginal_offDetails){
                        // get latest eOld_id from edited_old_created_offenses_tbl
                        $queryLates_eOldId = EditedOldCreatedOffenses::select('eOld_id')->where('eOld_from_crOffense_id', '=', $index)->latest('edited_at')->first();
                        $lates_eOldID = $queryLates_eOldId->eOld_id;
                        // save updated offense details to edited_new_created_offenses_tbl
                        $backedUpUpdated_offDetails = new EditedNewCreatedOffenses;
                        $backedUpUpdated_offDetails->eNew_from_eOld_id  = $lates_eOldID;
                        $backedUpUpdated_offDetails->eNew_crOffense_category = $toLower_selOffCat_Name;
                        $backedUpUpdated_offDetails->eNew_crOffense_type     = $updateThis_offDetails[0];
                        $backedUpUpdated_offDetails->eNew_crOffense_details  = $updateThis_offDetails[1];
                        $backedUpUpdated_offDetails->save();
                        // if updated offense details was successfully saved to edited_new_created_offenses_tbl
                        if($backedUpUpdated_offDetails){
                            // update original details from created_offenses_tbl
                            $saveUpdated_offDetails = CreatedOffenses::where('crOffense_id', '=', $index)
                                                            ->where('crOffense_details', '=', $org_crOffenseDetails)
                                                            ->update([
                                                                'crOffense_type'    => $updateThis_offDetails[0],
                                                                'crOffense_details' => $updateThis_offDetails[1],
                                                                'updated_at'        => $now_timestamp
                                                            ]);
                            // if created_offenses_tbl was updated successfully
                            if($saveUpdated_offDetails){
                                // record activity
                                $record_act = new Useractivites;
                                $record_act->created_at             = $now_timestamp;
                                $record_act->act_respo_user_id      = $get_respo_user_id;
                                $record_act->act_respo_users_lname  = $get_respo_user_lname;
                                $record_act->act_respo_users_fname  = $get_respo_user_fname;
                                $record_act->act_type               = 'offense update';
                                $record_act->act_details            = 'Updated ' . ucwords($org_crOffenseType) . ' ' . $toUcwords_selOffCat_Name.': ' . $org_crOffenseDetails . ' to ' . ucwords($updateThis_offDetails[0]) . ' ' . $toUcwords_selOffCat_Name.': ' . ucwords($updateThis_offDetails[1]).'.';
                                $record_act->act_affected_id        = $lates_eOldID;
                                $record_act->save();
                            }else{
                                return back()->withFailedStatus('Updating Offense Details has failed! please try again later.');
                            }
                        }else{
                            return back()->withFailedStatus('Saving Updated Offense Details to Backup has failed! please try again later.');
                        }
                    }else{
                        return back()->withFailedStatus('Saving Original Offense Details to Backup has failed! please try again later.');
                    }
                }
            }
            // if user's activity was recorded successfully
            if($record_act){
                return back()->withSuccessStatus(''.$toUcwords_selOffCat_Name . ' Details was Updated Successfully.');
            }else{
                return back()->withFailedStatus('Recording Your Activty for Updating ' . $toUcwords_selOffCat_Name . ' has failed! please try again later.');
            }
    }

    // temporary delete selected offense details confirmation on modal
    public function temporary_delete_selected_offense_details_confirmation_modal(Request $request){
        // get all request
        $get_sel_offCategory_id = $request->get('sel_offCategory_id');   
        $get_sel_offDetails_ids = json_decode(json_encode($request->get('sel_offDetails_ids')));

        // try
        // echo 'selected category id: ' . $get_sel_offCategory_id. '<br>';
        // echo 'selected offense ids: <br>';
        // if(!empty($get_sel_offDetails_ids) OR !is_null($get_sel_offDetails_ids)){
        //     foreach($get_sel_offDetails_ids as $editThis_selOffDetailID){
        //         echo '          - ' . $editThis_selOffDetailID . '<br>';
        //     }
        // }else{
        //     echo 'No Selected Offenses!';
        // }

        // output
        $output = '';

        // get selected Category Name
        $query_selOffCat_Name = OffensesCategories::select('offCategory')->where('offCat_id', '=', $get_sel_offCategory_id)->first();
        $txt_selOffCatName = $query_selOffCat_Name->offCategory;
        $toLower_selOffCat_Name = Str::lower($txt_selOffCatName);
        $toUcwords_selOffCat_Name = ucwords($txt_selOffCatName);

        $output .= '
        <div class="modal-body border-0 p-0">
            ';
            if(!empty($get_sel_offDetails_ids) OR !is_null($get_sel_offDetails_ids)){
                $output .= '
                <form id="form_tempDeleteOffenseDetails" action="'.route('offenses.process_temporary_delete_selected_offense_details').'" method="POST" enctype="multipart/form-data">
                    <div class="modal-body pt-0 pb-0">
                        <div class="card-body lightBlue_cardBody shadow-none mt-2 mb-2">
                            <span class="lightBlue_cardBody_blueTitle">Selected ' .$toUcwords_selOffCat_Name.':</span>
                            ';
                            foreach($get_sel_offDetails_ids as $editThis_selOffDetailID){
                                $query_selOffDetail_Info = CreatedOffenses::select('crOffense_type', 'crOffense_details')->where('crOffense_id', '=', $editThis_selOffDetailID)->first();
                                $query_selOffType = $query_selOffDetail_Info->crOffense_type;
                                $query_selOffDetail = $query_selOffDetail_Info->crOffense_details;
                                $output .= '
                                <div class="form-group mx-0 mt-0 mb-1">
                                    <div class="custom-control custom-checkbox align-items-center">
                                        <input type="checkbox" value="'.$editThis_selOffDetailID.'" name="temp_delete_offense[]" class="custom-control-input cursor_pointer temp_deleteSingle_offense" id="'.$editThis_selOffDetailID.'_tempDeleteThis" checked/>
                                        <label class="custom-control-label lightBlue_cardBody_chckboxLabel" for="'.$editThis_selOffDetailID.'_tempDeleteThis">'.$query_selOffDetail.'</label>
                                    </div>
                                </div>
                                ';
                            }
                            $output .= '
                            <hr class="hr_gryv1">
                            <div class="form-group mx-0 mt-0 mb-1">
                                <div class="custom-control custom-checkbox align-items-center">
                                    <input type="checkbox" id="temp_deleteAll_offenses" class="custom-control-input cursor_pointer" checked/>
                                    <label class="custom-control-label lightBlue_cardBody_chckboxLabel" for="temp_deleteAll_offenses">Delete all Selected ' . $toUcwords_selOffCat_Name.'.</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body lightBlue_cardBody shadow-none">
                            <span class="lightBlue_cardBody_blueTitle">Reason for Deletion:</span>
                            <div class="form-group">
                                <textarea class="form-control" id="temp_delete_offenses_reason" name="temp_delete_offenses_reason" rows="3" placeholder="Type reason for Deleting Selected ' . $toUcwords_selOffCat_Name . ' (required)" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                        <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                        <input type="hidden" name="selected_offCategoryID" value="'.$get_sel_offCategory_id.'">
                        <div class="btn-group" role="group" aria-label="Edit Offense Details Actions">
                            <button id="cancel_tempDeleteOffenseDetails_btn" type="button" class="btn btn-round btn_svms_red btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                            <button id="process_tempDeleteOffenseDetails_btn" type="submit" class="btn btn-round btn_svms_blue btn_show_icon m-0" disabled>Delete Selected ' . $toUcwords_selOffCat_Name. ' <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
                ';
            }else{
                $output .= '
                <div class="modal-body pb-0">
                    <div class="card-body lightRed_cardBody shadow-none">
                        <span class="lightRed_cardBody_redTitle"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> No Selected ' . $toUcwords_selOffCat_Name.':</span>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span class="cust_info_txtwicon3">Please close this modal and select ' . $toUcwords_selOffCat_Name . ' first to delete offense details.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <div class="btn-group" role="group" aria-label="Ok">
                        <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal">Ok <i class="fa fa-thumbs-up btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
                ';
            }
            $output .='
        </div>
        ';

        echo $output;
    }
    // process temporary deletion of selected offense details
    public function process_temporary_delete_selected_offense_details(Request $request){
        // get all request
        $get_respo_user_id               = $request->get('respo_user_id');
        $get_respo_user_lname            = $request->get('respo_user_lname');
        $get_respo_user_fname            = $request->get('respo_user_fname');
        $get_selected_offCategoryID      = $request->get('selected_offCategoryID');
        $get_temp_delete_offenses_reason = $request->get('temp_delete_offenses_reason');
        $get_temp_delete_offense         = json_decode(json_encode($request->get('temp_delete_offense')), true);
        
        // check if there are values selected
        if(!is_null($get_temp_delete_offense) OR !empty($get_temp_delete_offense)){
            // try
            // echo 'reason for deletion: ' . $get_temp_delete_offenses_reason . '<br>';
            // echo 'selected Category id: ' . $get_selected_offCategoryID . ' <br>';
            // echo 'selected offense ids: <br>';
            // foreach($get_temp_delete_offense as $temDeleteThis_crOffenseID){
            //     echo '          - ' . $temDeleteThis_crOffenseID . '<br>';
            // }

            // get selected Category Name
            $query_selOffCat_Name = OffensesCategories::select('offCategory')->where('offCat_id', '=', $get_selected_offCategoryID)->first();
            $txt_selOffCatName = $query_selOffCat_Name->offCategory;
            $toLower_selOffCat_Name = Str::lower($txt_selOffCatName);
            $toUcwords_selOffCat_Name = ucwords($txt_selOffCatName);
            $now_timestamp  = now();
            $sq = "'";
            
            // count
            $count_get_temp_delete_offense = count($get_temp_delete_offense);

            foreach($get_temp_delete_offense as $tempDeleteThis_crOffenseID){
                // get original offense details from created_offense_tbl
                $queryOrg_offDetails = CreatedOffenses::select('crOffense_category', 'crOffense_type', 'crOffense_details', 'respo_user_id', 'created_at')->where('crOffense_id', '=', $tempDeleteThis_crOffenseID)->where('crOffense_category', '=', $toLower_selOffCat_Name)->first();
                $org_crOffCategory   = $queryOrg_offDetails->crOffense_category;
                $org_crOffType       = $queryOrg_offDetails->crOffense_type;
                $org_crOffDetails    = $queryOrg_offDetails->crOffense_details;
                $org_crOffCreatedBy  = $queryOrg_offDetails->respo_user_id;
                $org_crOffCreatedAt  = $queryOrg_offDetails->created_at;
                
                // backup original offense detail to deleted_created_offenses_tbl
                $backedUp_OrgOffDetails = new DeletedCreatedOffenses;
                $backedUp_OrgOffDetails->del_crOffense_category = $org_crOffCategory;
                $backedUp_OrgOffDetails->del_crOffense_type     = $org_crOffType;
                $backedUp_OrgOffDetails->del_crOffense_details  = $org_crOffDetails;
                $backedUp_OrgOffDetails->del_created_by         = $org_crOffCreatedBy;
                $backedUp_OrgOffDetails->del_created_at         = $org_crOffCreatedAt;
                $backedUp_OrgOffDetails->reason_deletion        = $get_temp_delete_offenses_reason;
                $backedUp_OrgOffDetails->deleted_by             = $get_respo_user_id;
                $backedUp_OrgOffDetails->deleted_at             = $now_timestamp;
                $backedUp_OrgOffDetails->save();
                
                // if backup was a success
                if($backedUp_OrgOffDetails){
                    // delete selected offenses from created_offenses_tbl
                    $deleteOrg_offDetails = CreatedOffenses::where('crOffense_id', '=', $tempDeleteThis_crOffenseID)->where('crOffense_category', '=', $toLower_selOffCat_Name)->delete();
                    
                    // if deletion was a success
                    if($deleteOrg_offDetails){
                        // get latest del_id from deleted_created_offenses_tbl
                        $queryLates_delId = DeletedCreatedOffenses::select('del_id')
                                            ->where('del_crOffense_category', '=', $org_crOffCategory)
                                            ->where('del_crOffense_type', '=', $org_crOffType)
                                            ->where('del_crOffense_details', '=', $org_crOffDetails)
                                            ->latest('deleted_at')
                                            ->first();
                        $lates_delId = $queryLates_delId->del_id;

                        // record user's activity
                        $record_act = new Useractivites;
                        $record_act->created_at             = $now_timestamp;
                        $record_act->act_respo_user_id      = $get_respo_user_id;
                        $record_act->act_respo_users_lname  = $get_respo_user_lname;
                        $record_act->act_respo_users_fname  = $get_respo_user_fname;
                        $record_act->act_type               = 'offense deletion';
                        $record_act->act_details            = 'Temporarily Deleted ' . ucwords($org_crOffType) . ' ' . ucwords($org_crOffCategory).': ' . $org_crOffDetails.'.';
                        $record_act->act_affected_id        = $lates_delId;
                        $record_act->save();
                    }else{
                        return back()->withFailedStatus('Deleting Selected Offenses has failed! Please try again later.');
                    }
                }else{
                    return back()->withFailedStatus('Backing up Selected Offenses before deletion has failed! Please try again later.');
                }
            }
            // if user's activity was recorded successfully
            if($record_act){
                return back()->withSuccessStatus(''. $count_get_temp_delete_offense . ' ' . $toUcwords_selOffCat_Name . ' was Deleted Successfully.');
            }else{
                return back()->withFailedStatus('Recording Your Activty for Updating ' . $toUcwords_selOffCat_Name . ' has failed! please try again later.');
            }
        }else{
            return back()->withFailedStatus('There are no selected ' . $toUcwords_selOffCat_Name . ' to delete! Please select offenses first.');
        }
    }

    // DELETED OFFENSES
    // load deleted offenses table
    public function load_deleted_offenses_table(Request $request){
        if($request->ajax()){
            // custom var
            $do_output = '';
            $count_tempDeletedOff = '';
            $count_permDeletedOff = '';
            $do_paginate = '';

            // get all request
            $do_search       = $request->get('do_search');
            $do_numOfRows    = $request->get('do_numOfRows');
            $do_delStatus    = $request->get('do_delStatus');
            $do_offCategory  = $request->get('do_offCategory');
            $do_offType      = $request->get('do_offType');
            $do_orderByRange = $request->get('do_orderByRange');

            // vars
            // order by range
            if(!empty($do_orderByRange) OR $do_orderByRange != 0){
                if($do_orderByRange === 'asc'){
                    $orderByRange_filterVal = 'ASC';
                }else{
                    $orderByRange_filterVal = 'DESC';
                }
            }else{
                $orderByRange_filterVal = 'DESC';
            }

            // queries
            if($do_search != ''){
                $fltrDO_tbl = DeletedCreatedOffenses::
                    where(function($doQuery) use ($do_search){
                        $doQuery->orWhere('del_crOffense_category', 'like', '%'.$do_search.'%')
                            ->orWhere('del_crOffense_type', 'like', '%'.$do_search.'%')
                            ->orWhere('del_crOffense_details', 'like', '%'.$do_search.'%')
                            ->orWhere('reason_deletion', 'like', '%'.$do_search.'%');
                    })
                    ->where(function($doQuery) use ($do_delStatus, $do_offCategory, $do_offType, $do_orderByRange){
                        if($do_delStatus != 0 OR !empty($do_delStatus)){
                            if($do_delStatus == 'temp'){
                                $doQuery->where('del_Status', '=', 1);
                            }elseif($do_delStatus == 'perm'){
                                $doQuery->where('del_Status', '=', 0);
                            }else{
                                $doQuery->where('del_Status', '>=', 0);
                            }
                        }else{
                            $doQuery->where('del_Status', '>=', 0);
                        }
                        if($do_offCategory != 0 OR !empty($do_offCategory)){
                            $toLower_do_offCategory = Str::lower($do_offCategory);
                            $doQuery->where('del_crOffense_category', '=', $toLower_do_offCategory);
                        }
                        if($do_offType != 0 OR !empty($do_offType)){
                            $toLower_do_offType = Str::lower($do_offType);
                            $doQuery->where('del_crOffense_type', '=', $toLower_do_offType);
                        }
                })
                ->orderBy('del_id', $orderByRange_filterVal)
                ->paginate($do_numOfRows);
                $txt_matchedData = ' Matched Record';
            }else{
                $fltrDO_tbl = DeletedCreatedOffenses::where(function($doQuery) use ($do_delStatus, $do_offCategory, $do_offType, $do_orderByRange){
                    if($do_delStatus != 0 OR !empty($do_delStatus)){
                        if($do_delStatus == 'temp'){
                            $doQuery->where('del_Status', '=', 1);
                        }elseif($do_delStatus == 'perm'){
                            $doQuery->where('del_Status', '=', 0);
                        }else{
                            $doQuery->where('del_Status', '>=', 0);
                        }
                    }else{
                        $doQuery->where('del_Status', '>=', 0);
                    }
                    if($do_offCategory != 0 OR !empty($do_offCategory)){
                        $toLower_do_offCategory = Str::lower($do_offCategory);
                        $doQuery->where('del_crOffense_category', '=', $toLower_do_offCategory);
                    }
                    if($do_offType != 0 OR !empty($do_offType)){
                        $toLower_do_offType = Str::lower($do_offType);
                        $doQuery->where('del_crOffense_type', '=', $toLower_do_offType);
                    }
                })
                ->orderBy('del_id', $orderByRange_filterVal)
                ->paginate($do_numOfRows);
                $txt_matchedData = ' Record';
            }

            // count result
            $do_count_fltrDO_tbl = count($fltrDO_tbl);
            $do_total_fltrDO_tbl = $fltrDO_tbl->total();
            if($do_total_fltrDO_tbl > 0){
                if($do_total_fltrDO_tbl > 1){
                    $t_FDO_s = 's';
                }else{
                    $t_FDO_s = '';
                }
                $do_txtTotalDataFound = $fltrDO_tbl->firstItem() . ' - ' . $fltrDO_tbl->lastItem() . ' of ' . $do_total_fltrDO_tbl . ' ' . $txt_matchedData.''.$t_FDO_s;
            }else{
                $t_FDO_s = '';
                $do_txtTotalDataFound = 'No ' . $txt_matchedData.'s Found';
            }

            // table results
            if($do_count_fltrDO_tbl > 0){
                $doIndex = 1;
                foreach($fltrDO_tbl as $this_deletedOffense){
                    // type of deletion
                    if($this_deletedOffense->del_Status == 1){
                        $txt_deletionType     = 'Temporary deleted';
                        $dbColumn_respoUserId = 'deleted_by';
                        $dbColumn_deletedAt   = 'deleted_at';
                    }else if($this_deletedOffense->del_Status == 0){
                        $txt_deletionType     = 'Permanently deleted';
                        $dbColumn_respoUserId = 'perm_deleted_by';
                        $dbColumn_deletedAt   = 'deleted_at';
                    }else{
                        $txt_deletionType     = 'Unknown';
                        $dbColumn_respoUserId = '';
                        $dbColumn_deletedAt   = '';
                    }

                    // get responsible user's info (for deleting violation)
                    if(auth()->user()->id == $this_deletedOffense->$dbColumn_respoUserId){
                        $txt_youIndicator = '<span class="sub2 font-italic">~ You</span>';
                    }else{
                        $txt_youIndicator = '';
                    }
                    $query_respoUser_DelViola = Users::select('id', 'user_fname', 'user_lname', 'user_role')->where('id', '=', $this_deletedOffense->$dbColumn_respoUserId)->first();
                    $respoUser_FullName = ''.$query_respoUser_DelViola->user_fname . ' ' . $query_respoUser_DelViola->user_lname.'';
                    $respoUser_Role     = ''.ucwords($query_respoUser_DelViola->user_role).'';

                    // data table
                    $do_output .= '
                        <tr id="'.$this_deletedOffense->del_id.'" onclick="viewDeletedOffensesDetails(this.id)" class="tr_pointer">
                            <td class="pl12">
                                <span class="actLogs_content font-weight-bold">'. $doIndex++ . '</span>
                            </td>
                            <td>
                                <span class="actLogs_content">'. $txt_deletionType . '</span>
                            </td>
                            <td>
                                <div class="d-inline">
                                    <span class="actLogs_content">'.$respoUser_FullName . ' ' . $txt_youIndicator . '</span>
                                    <span class="actLogs_tdSubTitle sub2">'.$respoUser_Role.'</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-inline">
                                    <span class="actLogs_content">'.date('F d, Y', strtotime($this_deletedOffense->dbColumn_deletedAt)) . ' <span class="sub2">' . date('(D - g:i A)', strtotime($this_deletedOffense->dbColumn_deletedAt)) . '</span></span>
                                    <span class="actLogs_tdSubTitle">reason: <span class="font-italic font-weight-bold"> ' . preg_replace('/('.$do_search.')/i','<span class="red_highlight2">$1</span>', $this_deletedOffense->reason_deletion) . '</span></span>
                                </div>
                            </td>
                            <td>
                                <div class="d-inline">
                                    <span class="actLogs_content"><span class="font-weight-bold"> ' . preg_replace('/('.$do_search.')/i','<span class="red_highlight2">$1</span>', ucwords($this_deletedOffense->del_crOffense_category)) . ' </span> <span class="sub2"> ~ ' . preg_replace('/('.$do_search.')/i','<span class="red_highlight2">$1</span>', ucwords($this_deletedOffense->del_crOffense_type)) . '</span></span>
                                    <span class="actLogs_tdSubTitle"> ' . preg_replace('/('.$do_search.')/i','<span class="red_highlight2">$1</span>', $this_deletedOffense->del_crOffense_details) . '</span>
                                </div>
                            </td>
                        </tr>
                    '; 
                }

            }else{
                $do_output .='
                <tr class="no_data_row">
                    <td align="center" colspan="5">
                        <div class="no_data_div2 d-flex justify-content-center align-items-center text-center flex-column">
                            <img class="no_data_svg" src="'. asset('storage/svms/illustrations/no_deleted_records_found_red.svg').'" alt="no matching Data found">
                            <span class="font-italic font-weight-bold">No Records Found! </span>
                        </div>
                    </td>
                </tr>
                ';
            }

            // count queries for temporary and permanently deleted offenses
            $queryCount_tempDeletedOff = DeletedCreatedOffenses::where('del_Status', '>', 0)->count();
            $queryCount_permDeletedOff = DeletedCreatedOffenses::where('del_Status', '<=', 0)->count();
            if($queryCount_tempDeletedOff > 0){
                if($queryCount_tempDeletedOff > 1){
                    $cTDO_s = 's';
                }else{
                    $cTDO_s = '';
                }
                $txt_tempDeletedOff = ''. $queryCount_tempDeletedOff . ' Temporary Deleted Offense'.$cTDO_s.'';
            }else{
                $txt_tempDeletedOff = 'No Temporary Deleted Offenses';
            }
            if($queryCount_permDeletedOff > 0){
                if($queryCount_permDeletedOff > 1){
                    $cPDO_s = 's';
                }else{
                    $cPDO_s = '';
                }
                $txt_permDeletedOff = ''. $queryCount_permDeletedOff . ' Permanently Deleted Offense'.$cPDO_s.'';
            }else{
                $txt_permDeletedOff = 'No Permanently Deleted Offenses';
            }

            // results
            $do_paginate .= $fltrDO_tbl->render('pagination::bootstrap-4');
            $do_data = array(
                'do_table'               => $do_output,
                'do_pagination'          => $do_paginate,
                'do_totalDataFound'      => $do_txtTotalDataFound,
                'do_temp_deleted_result' => $txt_tempDeletedOff,
                'do_perm_deleted_result' => $txt_permDeletedOff
            );

            echo json_encode($do_data);
        }else{
            return view('offenses.index');
        }
    }
    // recover all temporary deleted offenses confirmation on modal
    public function recover_all_temporary_deleted_offenses_confirmation(Request $request){
        // 
        $output = '';

        // get all temporary deleted offenses
        $checkExist_tempDeletedOffenses = DeletedCreatedOffenses::where('del_Status', '=', 1)->count();

        if($checkExist_tempDeletedOffenses > 0){
            // query all temporary deleted offenses
            $queryAll_tempDeletedOffenses = DeletedCreatedOffenses::where('del_Status', '=', 1)->get();
            $output .= '
                <div class="modal-body border-0 p-0">
                    <form id="form_recoverAllTempDeletedOffenses" action="'.route('offenses.process_recover_selected_teporary_deleted_offenses').'" method="POST" enctype="multipart/form-data">
                        <div class="cust_modal_body_gray">
                            <span class="lightBlue_cardBody_blueTitle mb-2">Temporary Deleted Offenses:</span>
                            ';
                            foreach($queryAll_tempDeletedOffenses as $thisOption_TempDeletedOff){
                                // get responsible user's info who created this offense & date created at
                                $queryUser_createdThisOffense   = Users::select('user_fname', 'user_lname', 'user_role')->where('id', '=', $thisOption_TempDeletedOff->del_created_by)->first();
                                $txt_FullNameUserCreatedThisOff = ''.$queryUser_createdThisOffense->user_fname . ' ' . $queryUser_createdThisOffense->user_lname.'';
                                $txt_RoleUserCreatedThisOff     = ''.ucwords($queryUser_createdThisOffense->user_role).'';
                                $txt_OffenseCreatedAt           = ''.date('F d, Y ~ (D - g:i A)', strtotime($thisOption_TempDeletedOff->del_created_at)).'';

                                // get responsible user's info who temporary deleted this offense & date created at
                                $queryUser_deletedThisOffense   = Users::select('user_fname', 'user_lname', 'user_role')->where('id', '=', $thisOption_TempDeletedOff->deleted_by)->first();
                                $txt_FullNameUserDeletedThisOff = ''.$queryUser_deletedThisOffense->user_fname . ' ' . $queryUser_deletedThisOffense->user_lname.'';
                                $txt_RoleUserDeletedThisOff     = ''.ucwords($queryUser_deletedThisOffense->user_role).'';
                                $txt_OffenseDeletedAt           = ''.date('F d, Y ~ (D - g:i A)', strtotime($thisOption_TempDeletedOff->deleted_at)).'';
                                $output .= '
                                <div class="accordion shadow-none cust_accordion_div1 mb-2" id="tempDelOff_SelectOption_Parent'.$thisOption_TempDeletedOff->del_id.'">
                                    <div class="card custom_accordion_card">
                                        <div class="card-header py10l15r10 d-flex justify-content-between align-items-center" id="tempDelOff_SelectOption_heading'.$thisOption_TempDeletedOff->del_id.'">
                                            <div class="form-group m-0 width_90p">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" id="'.$thisOption_TempDeletedOff->del_id.'_markThisTempDelOff_id" value="'.$thisOption_TempDeletedOff->del_id.'" name="recover_temp_deleted_offenses[]" class="custom-control-input cust_checkbox_label recoverTempDelOffSingle" checked>
                                                    <label class="custom-control-label cust_checkbox_label" for="'.$thisOption_TempDeletedOff->del_id.'_markThisTempDelOff_id">
                                                        <span class="li_info_titlev2"> '.$thisOption_TempDeletedOff->del_crOffense_details.'</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <button class="btn cust_btn_smcircle3" type="button" data-toggle="collapse" data-target="#tempDelOff_SelectOption'.$thisOption_TempDeletedOff->del_id.'" aria-expanded="true" aria-controls="tempDelOff_SelectOption'.$thisOption_TempDeletedOff->del_id.'">
                                                <i class="nc-icon nc-minimal-down"></i>
                                            </button>
                                        </div>
                                        <div id="tempDelOff_SelectOption'.$thisOption_TempDeletedOff->del_id.'" class="collapse cust_collapse_active cb_t0b12y15" aria-labelledby="tempDelOff_SelectOption_heading'.$thisOption_TempDeletedOff->del_id.'" data-parent="#tempDelOff_SelectOption_Parent'.$thisOption_TempDeletedOff->del_id.'">
                                            <div class="row mb-2">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="card-body lightBlue_cardBody shadow-none mt-0">
                                                        <span class="lightBlue_cardBody_blueTitle">Offense Details:</span>
                                                        <span class="lightBlue_cardBody_notice"> <span class="font-weight-bold"> Category: </span> ' . ucwords($thisOption_TempDeletedOff->del_crOffense_category).' </span>
                                                        <span class="lightBlue_cardBody_notice"> <span class="font-weight-bold"> Type: </span> ' . ucwords($thisOption_TempDeletedOff->del_crOffense_type).' </span>
                                                        <hr class="hr_gryv1">
                                                        <div class="row cursor_pointer" data-toggle="tooltip" data-placement="top" title="Created by and the date this offense was created.">
                                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <span class="lightBlue_cardBody_notice"> <i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> ' . $txt_FullNameUserCreatedThisOff . ' <span class="font-italic"> ('.$txt_RoleUserCreatedThisOff.') </span> </span>
                                                            <span class="lightBlue_cardBody_notice"> <i class="fa fa-calendar mr-1" aria-hidden="true"></i> ' . $txt_OffenseCreatedAt . ' </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2 cursor_pointer" data-toggle="tooltip" data-placement="top" title="Reason behind deletion, Deleted by, and the date this offense was deleted at.">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="card-body lightRed_cardBody shadow-none mt-0">
                                                        <span class="lightRed_cardBody_redTitle">Deletion Details:</span>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <span class="lightRed_cardBody_notice"> <span class="font-weight-bold"> Reason: </span> ' . $thisOption_TempDeletedOff->reason_deletion.' </span>
                                                            <hr class="hr_red">
                                                            <span class="lightRed_cardBody_notice"> <i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> ' . $txt_FullNameUserDeletedThisOff . ' <span class="font-italic"> ('.$txt_RoleUserDeletedThisOff.') </span> </span>
                                                            <span class="lightRed_cardBody_notice"> <i class="fa fa-trash-o mr-1" aria-hidden="true"></i> ' . $txt_OffenseDeletedAt . ' </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ';
                            }
                            $output .= '
                        </div>
                        <div class="modal-body pb-0">
                            <div class="card-body lightBlue_cardBody shadow-none mt-2">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group m-0">
                                            <div class="custom-control custom-checkbox align-items-center">
                                                <input type="checkbox" id="recoverAll_TempDeleted" class="custom-control-input cursor_pointer" checked/>
                                                <label class="custom-control-label lightBlue_cardBody_chckboxLabel" for="recoverAll_TempDeleted">Recover All Offenses.</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="hr_gryv1">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="cust_info_txtwicon2"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> This Action will recover selected temporary deleted offenses.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                            <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                            <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                            <div class="btn-group" role="group" aria-label="Recover Temporary Deleted Offense Actions">
                                <button id="cancel_recoverTempDeletedOff_btn" type="button" class="btn btn-round btn_svms_red btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                                <button id="process_recoverTempDeletedOff_btn" type="submit" class="btn btn-round btn_svms_blue btn_show_icon m-0">Recover Selected Offenses <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            ';
        }else{
            $output .= '
                <div class="modal-body py-0">
                    <div class="card-body lightBlue_cardBody shadow-none">
                        <span class="lightBlue_cardBody_blueTitle"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i> No Temporary Deleted Offenses:</span>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span class="cust_info_txtwicon2">Please close this modal and delete offenses first to access this feature.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <div class="btn-group" role="group" aria-label="Ok">
                        <button type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal">Ok <i class="fa fa-thumbs-up btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            ';
        }

        echo $output;
    }
    // process recovery of all selected temporary deleted offenses
    public function process_recover_selected_teporary_deleted_offenses(Request $request){
        // get all request
        $get_respo_user_id                 = $request->get('respo_user_id');
        $get_respo_user_lname              = $request->get('respo_user_lname');
        $get_respo_user_fname              = $request->get('respo_user_fname');
        $get_recover_temp_deleted_offenses = json_decode(json_encode($request->get('recover_temp_deleted_offenses')), true);

        // try
        if(!is_null($get_recover_temp_deleted_offenses) OR !empty($get_recover_temp_deleted_offenses)){
            // custom values
            $now_timestamp  = now();
            $sq = "'";
            // count
            $count_get_recover_temp_deleted_offenses = count($get_recover_temp_deleted_offenses);
            foreach($get_recover_temp_deleted_offenses as $recoverThis_tempDelOffense){
                // get offense details from deleted_created_offenses_tbl
                $queryInfo_tempDelOffense         = DeletedCreatedOffenses::where('del_id', '=', $recoverThis_tempDelOffense)->first();
                $queryInfo_del_crOffense_category = $queryInfo_tempDelOffense->del_crOffense_category;
                $queryInfo_del_crOffense_type     = $queryInfo_tempDelOffense->del_crOffense_type;
                $queryInfo_del_crOffense_details  = $queryInfo_tempDelOffense->del_crOffense_details;
                $queryInfo_del_created_by         = $queryInfo_tempDelOffense->del_created_by;
                $queryInfo_del_created_at         = $queryInfo_tempDelOffense->del_created_at;
                $queryInfo_reason_deletion        = $queryInfo_tempDelOffense->reason_deletion;
                $queryInfo_deleted_by             = $queryInfo_tempDelOffense->deleted_by;
                $queryInfo_deleted_at             = $queryInfo_tempDelOffense->deleted_at;

                // save deleted offense back to created_offenses_tbl
                $recoverBack_tempDelOff = new CreatedOffenses;
                $recoverBack_tempDelOff->crOffense_category = $queryInfo_del_crOffense_category;
                $recoverBack_tempDelOff->crOffense_type     = $queryInfo_del_crOffense_type;
                $recoverBack_tempDelOff->crOffense_details  = $queryInfo_del_crOffense_details;
                $recoverBack_tempDelOff->respo_user_id      = $queryInfo_del_created_by;
                $recoverBack_tempDelOff->created_at         = $queryInfo_del_created_at;
                $recoverBack_tempDelOff->updated_at         = $now_timestamp;
                $recoverBack_tempDelOff->deleted_at         = $queryInfo_deleted_at;
                $recoverBack_tempDelOff->recovered_at       = $now_timestamp;
                $recoverBack_tempDelOff->save();

                // if recovery was a success
                if($recoverBack_tempDelOff){
                    // delete data from deleted_created_offenses_tbl
                    $deleteData_tempDelOffInfo = DeletedCreatedOffenses::where('del_id', '=', $recoverThis_tempDelOffense)->delete();

                    // if delete was a success
                    if($deleteData_tempDelOffInfo){
                        // get recovered crOffense_id from created_offenses_tbl
                        $queryRecovered_crOffenseID = CreatedOffenses::select('crOffense_id')
                                                        ->where('crOffense_category', '=', $queryInfo_del_crOffense_category)
                                                        ->where('crOffense_type', '=', $queryInfo_del_crOffense_type)
                                                        ->where('crOffense_details', '=', $queryInfo_del_crOffense_details)
                                                        ->latest('recovered_at')
                                                        ->first();
                        $latestRec_crOffenseID = $queryRecovered_crOffenseID->crOffense_id;

                        // record activity
                        $record_act = new Useractivites;
                        $record_act->created_at             = $now_timestamp;
                        $record_act->act_respo_user_id      = $get_respo_user_id;
                        $record_act->act_respo_users_lname  = $get_respo_user_lname;
                        $record_act->act_respo_users_fname  = $get_respo_user_fname;
                        $record_act->act_type               = 'offense recovery';
                        $record_act->act_details            = 'Recovered Temporarily Deleted ' . ucwords($queryInfo_del_crOffense_type) . ' ' . ucwords($queryInfo_del_crOffense_category).': ' . $queryInfo_del_crOffense_details.'.';
                        $record_act->act_affected_id        = $latestRec_crOffenseID;
                        $record_act->save();
                    }else{
                        return back()->withFailedStatus('Removing old record from deleted offenses record has failed! please try again later.');
                    }
                }else{
                    return back()->withFailedStatus('Recovering Temporary Deleted Offenses has failed! please try again later.');
                }
            }
            // if user's activity was recorded successfully
            if($record_act){
                return back()->withSuccessStatus(''. $count_get_recover_temp_deleted_offenses . ' Temporary Deleted Offenses was Recovered Successfully.');
            }else{
                return back()->withFailedStatus('Recording Your Activty for Recovering ' . $count_get_recover_temp_deleted_offenses . ' Temporary Deleted Offenses has failed! please try again later.');
            }
        }else{
            return back()->withFailedStatus('There are no selected "Temporary Deleted Offenses" to recover! Please select deleted offenses first.');
        }
    }
}
