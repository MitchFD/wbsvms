<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Students;

class ViolationEntryController extends Controller
{
    public function index(){
        return view('violation_entry.index');
    }

    // search violators
    public function search_violators(Request $request){
        if($request->query()){
            $data = 'wow';
            echo json_encode($data);
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
