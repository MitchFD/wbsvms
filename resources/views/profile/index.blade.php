@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'profile'
])

@section('content')
    <div class="content">
        {{-- notifications --}}
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        @if (session('password_status'))
            <div class="alert alert-success" role="alert">
                {{ session('password_status') }}
            </div>
        @endif
        @if (session('success_status'))
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12 col-md-12 col-sm-12 align-items-center mx-auto">
                    <div class="alert alert-success alert-dismissible login_alert fade show" role="alert">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="nc-icon nc-simple-remove"></i>
                        </button>
                        {{ session('success_status') }}
                    </div>
                </div>
            </div>
        @endif
        @if (session('failed_status'))
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12 col-md-12 col-sm-12 align-items-center mx-auto">
                    <div class="alert alert_smvs_danger alert-dismissible login_alert fade show" role="alert">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="nc-icon nc-simple-remove"></i>
                        </button>
                        {{ session('failed_status') }}
                    </div>
                </div>
            </div>
        @endif

        {{-- directory link --}}
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <a href="#" class="directory_link">My Profile</a>
            </div>
        </div>

        {{-- data customization --}}
            @php
                if(auth()->user()->user_type === 'employee'){
                    $user_emp_info   = App\Models\Useremployees::where('uEmp_id', auth()->user()->user_sdca_id)->first();
                    $custom_nav_pill = 'custom_nav_link_blue';
                    if(auth()->user()->user_role_status === 'active'){
                        if(auth()->user()->user_status === 'active'){
                            $image_filter   = 'up_user_image';
                            $user_alt_image = 'employee_user_image';
                        }else{
                            if(auth()->user()->user_status === 'deactivated' OR auth()->user()->user_status === 'deleted'){
                                $image_filter   = 'up_red_user_image';
                                $user_alt_image = 'no_student_image';
                            }else{
                                $image_filter   = 'up_gray_user_image';
                                $user_alt_image = 'disabled_user_image';
                            }
                        }
                    }else{
                        if(auth()->user()->user_role_status === 'deactivated' OR auth()->user()->user_role_status === 'deleted'){
                            $image_filter   = 'up_red_user_image';
                            $user_alt_image = 'no_student_image';
                        }else{
                            $image_filter   = 'up_gray_user_image';
                            $user_alt_image = 'disabled_user_image';
                        }   
                    }
                }else if(auth()->user()->user_type === 'student'){
                    $user_stud_info  = App\Models\Userstudents::where('uStud_num', auth()->user()->user_sdca_id)->first();
                    $custom_nav_pill = 'custom_nav_link_green';
                    if(auth()->user()->user_role_status === 'active'){
                        if(auth()->user()->user_status === 'active'){
                            $image_filter   = 'up_stud_user_image';
                            $user_alt_image = 'student_user_image';
                        }else{
                            if(auth()->user()->user_status === 'deactivated' OR auth()->user()->user_status === 'deleted'){
                                $image_filter   = 'up_red_user_image';
                                $user_alt_image = 'no_student_image';
                            }else{
                                $image_filter   = 'up_gray_user_image';
                                $user_alt_image = 'disabled_user_image';
                            }
                        }
                    }else{
                        if(auth()->user()->user_role_status === 'deactivated'  OR auth()->user()->user_role_status === 'deleted'){
                            $image_filter   = 'up_red_user_image';
                            $user_alt_image = 'no_student_image';
                        }else{
                            $image_filter   = 'up_gray_user_image';
                            $user_alt_image = 'disabled_user_image';
                        }   
                    }
                }else{
                    $custom_nav_pill = 'custom_nav_link_gray';
                }
            @endphp
        {{-- data customization end --}}

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">My Profile</span>
                            @if(auth()->user()->user_status == 'pending')
                                <span class="page_intro_subtitle">Your account is currently not active. Please wait as the System Administrator reviews your registration. Head to the Student Discipline Office if your account is still not active after 2 to 3 days of registration to acticate your account.</span>
                            @else
                                <span class="page_intro_subtitle">This page displays your registered account's information. You can view, edit, and update your profile, and you can also view your activity log histories.</span>
                            @endif
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/my_profile_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- USER ACCOUNT INFORMATION --}}
            <div class="col-lg-4 col-md-5 col-sm-12">
                <div class="accordion" id="profileCollapse">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="profileCollapseHeading">
                            <button id="profile_collapseBtnToggle" class="btn btn-link btn-block custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#collapse_userProfile" aria-expanded="true" aria-controls="collapse_userProfile">
                                <div>
                                    <span class="card_body_title">Account Information</span>
                                    <span class="card_body_subtitle">View and update your profile.</span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="collapse_userProfile" class="collapse show cb_t0b15x25" aria-labelledby="profileCollapseHeading" data-parent="#profileCollapse">
                            <ul class="nav nav-pills custom_nav_pills mt-0 mb-3 d-flex justify-content-center" id="user-pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ $custom_nav_pill }} active" id="pills_userProfile_tab{{auth()->user()->id}}" data-toggle="pill" href="#div_userProfile_tab{{auth()->user()->id}}" role="tab" aria-controls="div_userProfile_tab{{auth()->user()->id}}" aria-selected="true">Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $custom_nav_pill }}" id="pills_userEditProfile_tab{{auth()->user()->id}}" data-toggle="pill" href="#div_userEditProfile_tab{{auth()->user()->id}}" role="tab" aria-controls="div_userEditProfile_tab{{auth()->user()->id}}" aria-selected="false">Edit Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $custom_nav_pill }}" id="pills_userChangePassword_tab{{auth()->user()->id}}" data-toggle="pill" href="#div_userChangePassword_tab{{auth()->user()->id}}" role="tab" aria-controls="div_userChangePassword_tab{{auth()->user()->id}}" aria-selected="false">Change Password</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="studentPills-tabContent">
                                {{-- profile --}}
                                <div class="tab-pane fade show active" id="div_userProfile_tab{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_userProfile_tab{{auth()->user()->id}}">
                                    <div class="card card_gbr shadow card-user">
                                        <div class="image">
                                            <img src="{{ asset('paper/img/damir-bosnjak.jpg') }}" alt="...">
                                        </div>
                                        <div class="card-body">
                                            <div class="author">
                                                <a href="#" class="up_img_div">
                                                    <img class="{{ $image_filter }} shadow"
                                                    @if(!is_null(auth()->user()->user_image))
                                                        src="{{asset('storage/svms/user_images/'.auth()->user()->user_image)}}" alt="{{auth()->user()->user_fname }} {{ auth()->user()->user_lname}}'s profile image'"
                                                    @else
                                                        src="{{asset('storage/svms/user_images/'.$user_alt_image.'.jpg')}}" alt="default employee user's profile image"
                                                    @endif
                                                    >
                                                </a>
                                                <span class="up_fullname_txt text_svms_blue">{{auth()->user()->user_fname }}  {{auth()->user()->user_lname}}</span>
                                                @if(!is_null(auth()->user()->user_role) OR auth()->user()->user_role !== 'pending')
                                                    <h5 class="up_role_txt">{{ __(auth()->user()->user_role)}}</h5>
                                                @else
                                                    <h5 class="up_role_txt font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> Role is Pending</h5>
                                                @endif
                                                {{-- if user type = student --}}
                                                @if(auth()->user()->user_type === 'student')
                                                    {{-- student number --}}
                                                    <span class="cat_title_txt">Student Number</span>
                                                    <span class="up_info_txt"><i class="nc-icon nc-badge"></i> {{ auth()->user()->user_sdca_id}}</span>
                                                    {{-- student school & program --}}
                                                    @if(!is_null($user_stud_info->uStud_program))
                                                        <span class="up_info_txt mb-0">{{$user_stud_info->uStud_program}}-{{$user_stud_info->uStud_section}}</span>
                                                        @if(!is_null($user_stud_info->uStud_school))
                                                        <span class="cat_title_txt mb-3">{{$user_stud_info->uStud_school}}</span>
                                                        @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> school department unknown</span>
                                                        @endif
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> program unknown</span>
                                                        @if(!is_null($user_stud_info->uStud_school))
                                                        <span class="cat_title_txt mb-3">{{$user_stud_info->uStud_school}}</span>
                                                        @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> school department unknown</span>
                                                        @endif
                                                    @endif
                                                    {{-- student phone number --}}
                                                    <span class="cat_title_txt">Contact Number</span>
                                                    @if(!is_null($user_stud_info->uStud_phnum))
                                                        <span class="up_info_txt"><i class="nc-icon nc-mobile"></i> {{ $user_stud_info->uStud_phnum}}</span> 
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no contact number</span>
                                                    @endif
                                                    {{-- email --}}
                                                    <span class="cat_title_txt">Email Address</span>
                                                    @if(!is_null(auth()->user()->email))
                                                        <span class="up_info_txt"><i class="nc-icon nc-email-85"></i> {{ auth()->user()->email}}</span> 
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no email address</span>
                                                    @endif
                                                    {{-- gender --}}
                                                    <span class="cat_title_txt">Gender</span>
                                                    @if(!is_null(auth()->user()->user_gender))
                                                        @if(auth()->user()->user_gender === 'male')
                                                            <span class="up_info_txt"><i class="fa fa-male"></i> {{ auth()->user()->user_gender}}</span> 
                                                        @elseif(auth()->user()->user_gender === 'female')
                                                            <span class="up_info_txt"><i class="fa fa-female"></i> {{ auth()->user()->user_gender}}</span> 
                                                        @else
                                                            <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> gender unknown</span>
                                                        @endif
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no gender</span>
                                                    @endif
                                                {{-- if user type = employee --}}
                                                @elseif(auth()->user()->user_type === 'employee')
                                                    {{-- employee ID --}}
                                                    <span class="cat_title_txt">Employee ID</span>
                                                    <span class="up_info_txt"><i class="nc-icon nc-badge"></i> {{ auth()->user()->user_sdca_id}}</span>
                                                    {{-- employee department & job description --}}
                                                    @if(!is_null($user_emp_info->uEmp_job_desc))
                                                        <span class="up_info_txt mb-0">{{$user_emp_info->uEmp_job_desc}}</span>
                                                        @if(!is_null($user_emp_info->uEmp_dept))
                                                        <span class="cat_title_txt mb-3">{{$user_emp_info->uEmp_dept}}</span>
                                                        @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> department unknown</span>
                                                        @endif
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> job description unknown</span>
                                                        @if(!is_null($user_emp_info->uEmp_dept))
                                                        <span class="cat_title_txt mb-3">{{$user_emp_info->uEmp_dept}}</span>
                                                        @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> department unknown</span>
                                                        @endif
                                                    @endif
                                                    {{-- student phone number --}}
                                                    <span class="cat_title_txt">Contact Number</span>
                                                    @if(!is_null($user_emp_info->uEmp_phnum))
                                                        <span class="up_info_txt"><i class="nc-icon nc-mobile"></i> {{ $user_emp_info->uEmp_phnum}}</span> 
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no contact number</span>
                                                    @endif
                                                    {{-- email --}}
                                                    <span class="cat_title_txt">Email Address</span>
                                                    @if(!is_null(auth()->user()->email))
                                                        <span class="up_info_txt"><i class="nc-icon nc-email-85"></i> {{ auth()->user()->email}}</span> 
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no email address</span>
                                                    @endif
                                                    {{-- gender --}}
                                                    <span class="cat_title_txt">Gender</span>
                                                    @if(!is_null(auth()->user()->user_gender))
                                                        @if(auth()->user()->user_gender === 'male')
                                                            <span class="up_info_txt mb-0"><i class="fa fa-male"></i> {{ auth()->user()->user_gender}}</span> 
                                                        @elseif(auth()->user()->user_gender === 'female')
                                                            <span class="up_info_txt mb-0"><i class="fa fa-female"></i> {{ auth()->user()->user_gender}}</span> 
                                                        @else
                                                            <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> gender unknown</span>
                                                        @endif
                                                    @else
                                                        <span class="up_info_txt mb-0 font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> no gender</span>
                                                    @endif
                                                {{-- unknown user type --}}
                                                @else
                                                    <span class="cat_title_txt">User Type</span>
                                                    <span class="up_info_txt font-italic text_svms_red"><i class="fa fa-exclamation-circle"></i> unknown </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- edit profile --}}
                                <div class="tab-pane fade show active" id="div_userEditProfile_tab{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_userEditProfile_tab{{auth()->user()->id}}">
                                
                                </div>
                                {{-- change password --}}
                                <div class="tab-pane fade show active" id="div_userChangePassword_tab{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_userChangePassword_tab{{auth()->user()->id}}">
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card card_gbr card_ofh shadow-none">
                    <div class="card-body card_body_bg_gray cb_p15x25">
                        <div class="card-header p-0">
                            <span class="card_body_title">Account Information</span>
                            <span class="card_body_subtitle">View and update your profile.</span>
                        </div>
                        
                    </div>
                </div> --}}
            </div>

            <div class="col-lg-8 col-md-7 col-sm-12">
                <div class="accordion" id="activityLogsCollapse">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="headingOne">
                            <button id="actLogs_collapseBtnToggle" class="btn btn-link btn-block custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <div>
                                    <span class="card_body_title">Activity Log Histories</span>
                                    <span class="card_body_subtitle">Below are the list of your transactions with the system.</span>
                                </div>
                                {{-- <div id="actLogs_collapseIconToggle">
                                    <i class="nc-icon nc-minimal-down custom_btn_collapse_icon"></i>
                                </div> --}}
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div id="collapseOne" class="collapse show p-0" aria-labelledby="headingOne" data-parent="#activityLogsCollapse">
                            <div class="card-body cb_t0b15x25">
                                @if(count($user_activities) > 0)
                                @php
                                    $transactions_count = count($user_activities);
                                @endphp
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <table class="table table-hover cust_table shadow">
                                            <thead class="thead_svms_blue">
                                                <tr>
                                                    <th class="p12 w35prcnt">Date</th>
                                                    <th>Transaction Details</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tbody_svms_white">
                                            @foreach($user_activities->sortByDesc('created_at') as $user_activity)
                                                <tr>
                                                    <td class="p12 w35prcnt">{{date('F d, Y', strtotime($user_activity->created_at)) }} - {{date('D', strtotime($user_activity->created_at)) }} at {{ date('g:i A', strtotime($user_activity->created_at))}}</td>
                                                    <td>
                                                        @if($user_activity->act_type == 'login')
                                                            You logged in to the system.
                                                        @elseif($user_activity->act_type == 'logout')
                                                            You logged out from the system.
                                                        @elseif($user_activity->act_type == 'register')
                                                            You registered as @if(auth()->user()->user_type == 'student') a Student User. @else an Employee User. @endif
                                                        @elseif($user_activity->act_type == 'update account')
                                                            You Updated your Account Information.
                                                        @else
                                                            {{$user_activity->act_details}}.
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span class="cust_info_txtwicon"><i class="fa fa-history" aria-hidden="true"></i> You made {{$transactions_count}} @if($transactions_count > 1) transactions @else transaction @endif in the system.</span>
                                    </div>
                                </div>
                                @else
                                
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- USER's ACTIVITY LOGS --}}
        </div>
    </div>
@endsection

@push('scripts')
{{-- avtive tab on page refresh --}}
    <script>
        $(document).ready(function() {
            if (location.hash) {
                $("a[href='" + location.hash + "']").tab("show");
            }
            $(document.body).on("click", "a[data-toggle='pill']", function(event) {
                location.hash = this.getAttribute("href");
            });
        });
        $(window).on("popstate", function() {
            var anchor = location.hash || $("a[data-toggle='pill']").first().attr("href");
            $("a[href='" + anchor + "']").tab("show");
        });
    </script>
{{-- avtive tab on page refresh end --}}

{{-- user profile image upload --}}
    {{-- employee profile image --}}
    <script>
        $(document).ready(function() {
            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('.emp_imgUpld_targetImg').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $(".emp_img_imgUpld_fileInpt").on('change', function(){
                readURL(this);
            });
            $(".emp_imgUpld_TrgtBtn").on('click', function() {
                $(".emp_img_imgUpld_fileInpt").click();
            });
        });
    </script>
    {{-- student profile image --}}
    <script>
        $(document).ready(function() {
            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('.stud_imgUpld_targetImg').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $(".stud_img_imgUpld_fileInpt").on('change', function(){
                readURL(this);
            });
            $(".stud_imgUpld_TrgtBtn").on('click', function() {
                $(".stud_img_imgUpld_fileInpt").click();
            });
        });
    </script>
{{-- user profile image upload end --}}

{{-- display datalist options based on previous selected option --}}
    {{-- selected school --}}
    <script>
        $(document).ready(function() {
            $("#upd_stud_school").on("change paste keyup", function() {
                var selectedSchool = $(this).val();
                if(selectedSchool != ''){
                    if(selectedSchool == 'SASE'){
                        $("#updateStudProgramOptions").html('<option value="BS Psychology"> \
                                                    <option value="BS Education"> \
                                                    <option value="BA Communication">');
                    }else if(selectedSchool == 'SBCS'){
                        $("#updateStudProgramOptions").html('<option value="BSBA"> \
                                                    <option value="BSA"> \
                                                    <option value="BSIT"> \
                                                    <option value="BMA">');
                    }else if(selectedSchool == 'SIHTM'){
                        $("#updateStudProgramOptions").html('<option value="BSHM"> \
                                                    <option value="BSTM">');
                    }else if(selectedSchool == 'SHSP'){
                        $("#updateStudProgramOptions").html('<option value="BS Biology"> \
                                                    <option value="BS Pharmacy"> \
                                                    <option value="BS Radiologic Technology"> \
                                                    <option value="BS Physical Therapy"> \
                                                    <option value="BS Medical Technology"> \
                                                    <option value="BS Nursing">');
                    }else{
                        $("#updateStudProgramOptions").html('<option value="Select School First"></option>');
                    }
                }else{
                    $("#updateStudProgramOptions").html('<option value="Select School First"></option>');
                }
            });
        });
    </script>
    {{-- selected program --}}
    <script>
        $(document).ready(function() {
            $("#upd_stud_program").on("change paste keyup", function() {
                var selectedProgram = $(this).val();
                if(selectedProgram != ''){
                    if(selectedProgram == 'BSA' || selectedProgram == 'BS Physical Therapy'){
                        $("#updateStudYearlvlOptions").html('<option value="FIRST YEAR"> \
                                                <option value="SECOND YEAR"> \
                                                <option value="THIRD YEAR"> \
                                                <option value="FOURTH YEAR"> \
                                                <option value="FIFTH YEAR">');
                    }else{
                        $("#updateStudYearlvlOptions").html('<option value="FIRST YEAR"> \
                                                <option value="SECOND YEAR"> \
                                                <option value="THIRD YEAR"> \
                                                <option value="FOURTH YEAR">');
                    }
                }else{
                    $("#updateStudYearlvlOptions").html('<option value="Select Program First"></option>');
                }
            });
        });
    </script>
{{-- display datalist options based on previous selected option --}}

{{-- disable update button on employee profile update if any of inputs have chagned --}}
    <script>
        $(window).on('load', function(e){
            $('#form_empUpdateProfile').each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#update_empInfoBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                /* Check if input with type files has changed */
                var changedFiles = $( ":file" ).filter(function( index ) {
                    return this.value != this.defaultValue;
                }).length;
                if ( changedFiles > 0) {
                    $(this).find('#update_empInfoBtn, input[type="file"]')
                        .prop('disabled', false);
                }
            }).find('#update_empInfoBtn').prop('disabled', true);
        });
    </script>
{{-- disable update button on employee profile update if any of inputs have chagned end --}}
{{-- disable update button on student profile update if any of inputs have chagned --}}
    <script>
        $(window).on('load', function(e){
            $('#form_studUpdateProfile').each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#update_studInfoBtn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                /* Check if input with type files has changed */
                var changedFiles = $( ":file" ).filter(function( index ) {
                    return this.value != this.defaultValue;
                }).length;
                if ( changedFiles > 0) {
                    $(this).find('#update_studInfoBtn, input[type="file"]')
                        .prop('disabled', false);
                }
            }).find('#update_studInfoBtn').prop('disabled', true);
        });
    </script>
{{-- disable update button on student profile update if any of inputs have chagned end --}}

{{-- paswword toggle visibility --}}
    {{-- student user password update --}}
    <script>
        const toggleStudOldPassword = document.querySelector('#toggleStudOldPassword');
        const cng_studOldPassword = document.querySelector('#cng_studOldPassword');
        toggleStudOldPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = cng_studOldPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            cng_studOldPassword.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    <script>
        const toggleStudNewPassword = document.querySelector('#toggleStudNewPassword');
        const cng_studNewPassword = document.querySelector('#cng_studNewPassword');
        toggleStudNewPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = cng_studNewPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            cng_studNewPassword.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    <script>
        const toggleStudConfirmNewPassword = document.querySelector('#toggleStudConfirmNewPassword');
        const cng_confirmStudNewPassword = document.querySelector('#cng_confirmStudNewPassword');
        toggleStudConfirmNewPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = cng_confirmStudNewPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            cng_confirmStudNewPassword.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    {{-- employee user password update --}}
    <script>
        const toggleEmpOldPassword = document.querySelector('#toggleEmpOldPassword');
        const cng_old_password = document.querySelector('#cng_old_password');
        toggleEmpOldPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = cng_old_password.getAttribute('type') === 'password' ? 'text' : 'password';
            cng_old_password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    <script>
        const toggleEmpNewPassword = document.querySelector('#toggleEmpNewPassword');
        const cng_new_password = document.querySelector('#cng_new_password');
        toggleEmpNewPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = cng_new_password.getAttribute('type') === 'password' ? 'text' : 'password';
            cng_new_password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    <script>
        const toggleEmpConfirmNewPassword = document.querySelector('#toggleEmpConfirmNewPassword');
        const cng_confirm_new_password = document.querySelector('#cng_confirm_new_password');
        toggleEmpConfirmNewPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = cng_confirm_new_password.getAttribute('type') === 'password' ? 'text' : 'password';
            cng_confirm_new_password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
{{-- password toggle visibility end --}}

{{-- change icon on button click --}}
    {{-- for profile collapse icon --}}
    <script>
        $('#profile_collapseBtnToggle').click(function() {
            // $('#actLogs_collapseIconToggle').toggle('1000');
            $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
        });
    </script>
    {{-- for activity log histories collapse icon --}}
    <script>
        $('#actLogs_collapseBtnToggle').click(function() {
            // $('#actLogs_collapseIconToggle').toggle('1000');
            $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
        });
    </script>
{{-- change icon on button click end --}}
@endpush