<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userroles;

class DisciplinaryPoliciesController extends Controller
{
    // student handbook module
    public function student_handbook(Request $request){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('disciplinary policies', $get_uRole_access)){
            return view('disciplinary_policies.student_handbook');
        }else{
            return view('profile.access_denied');
        }
    }

    // online class policies module
    public function online_class_policies(Request $request){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('disciplinary policies', $get_uRole_access)){
            return view('disciplinary_policies.online_class_policies');
        }else{
            return view('profile.access_denied');
        }
    }
}
