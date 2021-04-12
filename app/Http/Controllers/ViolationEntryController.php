<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Students;
use App\Models\Users;

class ViolationEntryController extends Controller
{
    public function index(){
        return view('violation_entry.index');
    }

    // search violators
    public function search_violators(Request $request){
        if($request->ajax()){
            $output = '';
            $violators_query = $request->get('violators_query');
            if($violators_query != ''){
                $data = Users::select('id', 'user_sdca_id', 'user_image', 'user_lname', 'user_fname', 'user_gender')
                            ->orWhere('user_sdca_id', 'like', '%'.$violators_query.'%')
                            ->orWhere('user_lname', 'like', '%'.$violators_query.'%')
                            ->orWhere('user_fname', 'like', '%'.$violators_query.'%')
                            ->orderBy('id', 'asc')
                            ->get();
                $total_row = $data->count();
                $sq = "'";
                if($total_row > 0){
                    $output .= '<div class="list-group mt-3 shadow cust_list_group_ve" id="displaySearchViolators_results">';
                    foreach($data as $result){
                        $output .= '
                            <a href="#" data-toggle="modal" data-target="#violationEntryModal" class="list-group-item list-group-item-action cust_lg_item_ve">
                                <div class="display_user_image_div text-center">
                                    <img class="display_violator_image shadow-sm" src="'.asset('storage/svms/user_images/'.$result->user_image.'').'" alt="violator'.$sq.'s image">
                                </div>
                                <div class="information_div">
                                    <span class="li_info_title">'.preg_replace('/('.$violators_query.')/i','<span class="red_highlight">$1</span>', $result->user_fname) . ' ' . preg_replace('/('.$violators_query.')/i','<span class="red_highlight">$1</span>', $result->user_lname).'</span>
                                    <span class="li_info_subtitle"><span class="text_svms_blue">'.preg_replace('/('.$violators_query.')/i','<span class="red_highlight">$1</span>', $result->user_sdca_id) . ' </span> | SBCS - BSIT 4A | Male</span>
                                </div>
                            </a>
                        ';
                    }
                    $output .= '</div';
                }else{
                    $output .= '
                        <div class="no_data_fount_list mt-3">
                            <i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> No Match Found for <span class="font-weight-bold"> ' . $violators_query.'...</span>
                        </div>
                    ';
                }
                $data = array(
                    'violators_results' => $output
                );
                echo json_encode($data);
            }
        }
        // if(isset($_GET["query"]))
        //     {
        //     $connect = new PDO("mysql:host=localhost; dbname=testing", "root", "");

        //     $query = "
        //     SELECT country_name FROM apps_countries 
        //     WHERE country_name LIKE '".$_GET["query"]."%' 
        //     ORDER BY country_name ASC 
        //     LIMIT 15
        //     ";

        //     $statement = $connect->prepare($query);

        //     $statement->execute();

        //     while($row = $statement->fetch(PDO::FETCH_ASSOC))
        //     {
        //     $data[] = $row["country_name"];
        //     }
        //     }

        //     echo json_encode($data);
    }
}
