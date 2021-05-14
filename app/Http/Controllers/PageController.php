<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userroles;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index(string $page)
    {
        if (view()->exists("pages.{$page}")) {
            // $get_user_role_info = Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
            // $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
            // if(in_array($page, $get_uRole_access)){
                return view("pages.{$page}");
            // }else{
            //     return view('profile.access_denied');
            // }
        }

        return abort(404);
    }
}
