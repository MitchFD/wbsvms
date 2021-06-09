<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userroles;
use App\Models\OffensesCategories;
use App\Models\CreatedOffenses;
use Illuminate\Support\Str;
use App\Models\Useractivites;

class OffensesController extends Controller
{
    public function index(Request $request){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('sanctions', $get_uRole_access)){
            // get all offenses categories per group
            $query_OffensesCategory = OffensesCategories::select('offCat_id', 'offCategory')->get();
            return view('offenses.index')->with(compact('query_OffensesCategory'));
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
}
