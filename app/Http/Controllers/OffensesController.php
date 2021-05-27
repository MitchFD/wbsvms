<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userroles;

class OffensesController extends Controller
{
    public function index(Request $request){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('sanctions', $get_uRole_access)){
            return view('offenses.index');
        }else{
            return view('profile.access_denied');
        }
    }
}
