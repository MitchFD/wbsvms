<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userroles;
use App\Models\OffensesCategories;
use App\Models\CreatedOffenses;

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
                <div class="modal-footer border-0">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="respo_user_id" value="'.auth()->user()->id.'">
                    <input type="hidden" name="respo_user_lname" value="'.auth()->user()->user_lname.'">
                    <input type="hidden" name="respo_user_fname" value="'.auth()->user()->user_fname.'">
                    <div class="btn-group" role="group" aria-label="Add New Category Actions">
                        <button id="cancel_registerNewCategory_btn" type="button" class="btn btn-round btn_svms_red btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                        <button id="process_registerNewCategory_btn" type="submit" class="btn btn-round btn_svms_blue btn_show_icon m-0">Register New Category <i class="nc-icon nc-single-copy-04 btn_icon_show_right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </form>
        </div>
        ';
        echo $output;
    }
    // process registration of new category
    public function process_register_new_category(Request $request){
        echo 'submit new category';
    }
}
