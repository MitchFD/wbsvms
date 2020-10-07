<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Useractivites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    // public function redirectTo(){
    //     get user's info
    //         $user_id          = Auth::user()->id;
    //         $user_status      = Auth::user()->user_status;
    //         $user_role        = Auth::user()->user_role;
    //         $user_role_status = Auth::user()->user_role_status;
    //         $user_lname       = Auth::user()->user_lname;
    //         $user_fname       = Auth::user()->user_fname;
        
    //     now timestamp
    //         $now_timestamp = now();

    //     check user and user role status
    //         if($user_status == 'active' AND $user_role_status == 'active'){
    //             if($user_role == 'administrator'){
    //                 return '/home';
    //             }else{
    //                 return 'violation_entry/index';
    //             }
    //         }else{
    //             return '/';
    //             return redirect('/')->withDeactivatedAccountStatus('Your account has been deactivated by the administrator. Please contact your administrator or head to Student Discipline Office to regain access to your account.');
    //         }
    // }

    protected function authenticated(Request $request, $user){
        // now timestamp
            $now_timestamp = now();
        // get user's info
            $user_id          = $user->id;
            $user_lname       = $user->user_lname;
            $user_fname       = $user->user_fname;
            $user_status      = $user->user_status;
            $user_role_status = $user->user_role_status;
            $user_role        = $user->user_role;
        // redirect based on user's and user's role status
            if($user_status == 'active' AND $user_role_status == 'active'){
                if($user_role == 'administrator'){
                    // record login to activity
                    $record_act = new Useractivites;
                    $record_act->created_at            = $now_timestamp;
                    $record_act->act_respo_user_id     = $user_id;
                    $record_act->act_respo_users_lname = $user_lname;
                    $record_act->act_respo_users_fname = $user_fname;
                    $record_act->act_type              = 'login';
                    $record_act->act_details           = 'logged in to system';
                    $record_act->act_affected_id       = $user_id;
                    $record_act->save();
                    // redirect to admin dashboard
                    return redirect('/home');
                }else{
                    // record login to activity
                    $record_act = new Useractivites;
                    $record_act->created_at            = $now_timestamp;
                    $record_act->act_respo_user_id     = $user_id;
                    $record_act->act_respo_users_lname = $user_lname;
                    $record_act->act_respo_users_fname = $user_fname;
                    $record_act->act_type              = 'login';
                    $record_act->act_details           = 'logged in to system';
                    $record_act->act_affected_id       = $user_id;
                    $record_act->save();
                    // redirect to violation entry
                    return redirect('violation_entry/index');
                }
            }else{
                Auth::logout();
                return back()->withDeactivatedAccountStatus('Your account has been deactivated by the administrator. Please contact your administrator or head to Student Discipline Office to regain access to your account.');
            }
    }
    
    public function logout(Request $request){
        // now timestamp
        $now_timestamp = now();
        // get user's info
            $user_id          = auth()->user()->id;
            $user_lname       = auth()->user()->user_lname;
            $user_fname       = auth()->user()->user_fname;
        // record login to activity
            $record_act = new Useractivites;
            $record_act->created_at            = $now_timestamp;
            $record_act->act_respo_user_id     = $user_id;
            $record_act->act_respo_users_lname = $user_lname;
            $record_act->act_respo_users_fname = $user_fname;
            $record_act->act_type              = 'logout';
            $record_act->act_details           = 'logged out from the system';
            $record_act->act_affected_id       = $user_id;
            $record_act->save();
            
        // echo 'logged out user: <br />';
        // echo 'user id: ' .$user_id. '<br />' ;
        // echo 'user first name: ' .$user_fname. '<br />' ;
        // echo 'user last name: ' .$user_lname. '<br />' ;

        Auth::logout();
        return redirect('/');
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
