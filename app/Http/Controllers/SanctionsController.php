<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Students;
use App\Models\Users;
use App\Models\Userroles;
use App\Models\Useractivites;
use App\Models\Violations;
use Illuminate\Mail\Mailable;

class SanctionsController extends Controller
{
    public function index(){
        // redirects
        $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
        $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        if(in_array('sanctions', $get_uRole_access)){
            return view('sanctions.index');
        }else{
            return view('profile.access_denied');
        }
    }
}
