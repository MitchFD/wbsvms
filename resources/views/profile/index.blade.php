@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'profile'
])

@section('content')
    <div class="content">
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
        {{-- directory link --}}
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <a href="#" class="directory_link">My Profile</a>
            </div>
        </div>

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">My Profile</span>
                            @if(auth()->user()->user_status == 'pending')
                                <span class="page_intro_subtitle">Your account is currently not active. Please wait as the System Administrator reviews your registration. Head to the Student Discipline Office if your account is still not active after 2 to 3 days of registration.</span>
                            @else
                                <span class="page_intro_subtitle">This page displays your registered account's information. You can view, edit, and update your profile, and you can also view your activity log histories.</span>
                            @endif
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/sys/illustrations/profile_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="card card_gbr card_ofh shadow-none">
                    <div class="card-body card_body_bg_gray cb_p15x25">
                        <div class="card-header p-0">
                            <span class="card_body_title">Account Information</span>
                            <span class="card_body_subtitle">View, edit, and update your account information.</span>
                        </div>
                        <ul class="nav nav-pills custom_nav_pills my-3 d-flex justify-content-center" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link custom_nav_link_blue active" id="pills_profile_preview_tab_{{auth()->user()->id}}" data-toggle="pill" href="#profile_preview_{{auth()->user()->id}}" role="tab" aria-controls="profile_preview_{{auth()->user()->id}}" aria-selected="true">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom_nav_link_blue" id="pills_edit_profile_tab_{{auth()->user()->id}}" data-toggle="pill" href="#pills_edit_profile_{{auth()->user()->id}}" role="tab" aria-controls="pills_edit_profile_{{auth()->user()->id}}" aria-selected="false">Edit Profile</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            {{-- PROFILE PREVIEW --}}
                            <div class="tab-pane fade show active" id="profile_preview_{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_profile_preview_tab_{{auth()->user()->id}}">
                                @if(auth()->user()->user_type == 'employee')
                                    @php
                                        $user_info = DB::table('user_employees_tbl')->where('uEmp_id', auth()->user()->user_sdca_id)->first();
                                    @endphp
                                    @if(auth()->user()->user_role == 'administrator')
                                        <div class="card card_gbr shadow card-user">
                                            <div class="image">
                                                <img src="{{ asset('paper/img/damir-bosnjak.jpg') }}" alt="...">
                                            </div>
                                            <div class="card-body">
                                                <div class="author">
                                                    <a href="#" class="up_img_div">
                                                        <img class="up_user_image shadow border-gray" src="{{asset('storage/svms/user_images/'.auth()->user()->user_image)}}" alt="{{auth()->user()->user_fname }} {{ auth()->user()->user_lname}}'s profile image'">
                                                    </a>
                                                    <span class="up_fullname_txt">{{auth()->user()->user_fname }}  {{auth()->user()->user_lname}}</span>
                                                    @if(!is_null(auth()->user()->user_role) AND auth()->user()->user_role != 'pending') <h5 class="up_role_txt">{{ __(auth()->user()->user_role)}}</h5> @endif
                                                    
                                                    <span class="cat_title_txt">Employee ID</span>
                                                    <span class="up_info_txt"><i class="nc-icon nc-badge"></i> {{ auth()->user()->user_sdca_id}}</span>

                                                    @if(!is_null($user_info->uEmp_job_desc)) <span class="up_info_txt mb-0">{{$user_info->uEmp_job_desc}}</span> @endif
                                                    @if(!is_null($user_info->uEmp_dept)) <span class="cat_title_txt mb-3">{{$user_info->uEmp_dept}}</span> @endif

                                                    @if(!is_null($user_info->uEmp_phnum)) 
                                                    <span class="cat_title_txt">Contact Number</span>
                                                    <span class="up_info_txt"><i class="nc-icon nc-mobile"></i> {{ $user_info->uEmp_phnum}}</span> 
                                                    @endif
                                                    
                                                    @if(!is_null(auth()->user()->email)) 
                                                    <span class="cat_title_txt">Email Address</span>
                                                    <span class="up_info_txt"><i class="nc-icon nc-email-85"></i> {{ auth()->user()->email}}</span> 
                                                    @endif

                                                    <span class="cat_title_txt">Account Status</span>
                                                    @if(auth()->user()->user_status != 'active') 
                                                    <span class="up_info_txt nactive_stat"><i class="nc-icon nc-circle-10"></i> {{ auth()->user()->user_status}}</span> 
                                                    @else
                                                    <span class="up_info_txt active_stat"><i class="nc-icon nc-circle-10"></i> {{ auth()->user()->user_status}}</span> 
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                    @endif
                                @elseif(auth()->user()->user_type == 'student')
                                @else
                                @endif
                            </div>
                            {{-- EDIT PROFILE --}}
                            <div class="tab-pane fade" id="pills_edit_profile_{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_edit_profile_tab_{{auth()->user()->id}}">
                                <div class="card card_gbr shadow">
                                    <div class="card-body p-0">
                                        <div class="card-header cb_p15x25">
                                            <span class="sec_card_body_title">Edit Profile</span>
                                            <span class="sec_card_body_subtitle">Click the <span class="font-weight-bold">'Save Changes'</span> button to save the changes you've made and this will update your profile.</span>
                                        </div>
                                        <form id="form_empUpdateProfile" class="form" method="POST" action="{{route('profile.update_emp_user_profile')}}">
                                            @csrf
                                            <div class="cb_px25 cb_pb15">
                                                <div class="row d-flex justify-content-center">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 align-items-center">
                                                        <div class="up_img_div align-items-center">
                                                            <img class="up_user_image shadow border-gray" src="{{asset('storage/svms/user_images/'.auth()->user()->user_image)}}" alt="{{auth()->user()->user_fname }} {{ auth()->user()->user_lname}}'s profile image'">
                                                        </div>
                                                    </div>
                                                </div>
                                                <label for="upd_emp_email">Email Address</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="nc-icon nc-email-85" aria-hidden="true"></i>
                                                        </span>
                                                    </div>
                                                    <input id="upd_emp_email" name="upd_emp_email" type="text" class="form-control" @if(auth()->user()->email != 'null') value="{{auth()->user()->email}}" @else placeholder="Type Email Address" @endif required>
                                                </div>
                                                <label for="upd_emp_id">Employee ID</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                        </span>
                                                    </div>
                                                    <input id="upd_emp_id" name="upd_emp_id" type="number" class="form-control" @if(auth()->user()->user_sdca_id != 'null') value="{{auth()->user()->user_sdca_id}}" @else placeholder="Type Employee ID" @endif required>
                                                </div>
                                                <label for="upd_emp_lname">Last Name</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="nc-icon nc-single-02"></i>
                                                        </span>
                                                    </div>
                                                    <input id="upd_emp_lname" name="upd_emp_lname" type="text" class="form-control" @if(auth()->user()->user_lname != 'null') value="{{auth()->user()->user_lname}}" @else placeholder="Type Last Name" @endif required>
                                                </div>
                                                <label for="upd_emp_fname">First Name</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="nc-icon nc-single-02"></i>
                                                        </span>
                                                    </div>
                                                    <input id="upd_emp_fname" name="upd_emp_fname" type="text" class="form-control" @if(auth()->user()->user_fname != 'null') value="{{auth()->user()->user_fname}}" @else placeholder="Type First Name" @endif required>
                                                </div>
                                                <label for="upd_emp_jobdesc">Job Description</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="nc-icon nc-briefcase-24" aria-hidden="true"></i>
                                                        </span>
                                                    </div>
                                                    <input id="upd_emp_jobdesc" name="upd_emp_jobdesc" type="text" class="form-control" @if($user_info->uEmp_job_desc != 'null') value="{{$user_info->uEmp_job_desc}}" @else placeholder="Type Job Position" @endif required>
                                                </div>
                                                <label for="upd_emp_dept">Department</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="nc-icon nc-bank" aria-hidden="true"></i>
                                                        </span>
                                                    </div>
                                                    <input id="upd_emp_dept" name="upd_emp_dept" type="text" class="form-control" @if($user_info->uEmp_dept != 'null') value="{{$user_info->uEmp_dept}}" @else placeholder="Type Department" @endif required>
                                                </div>
                                                <label for="upd_emp_phnum">Phone NUmber</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-mobile" aria-hidden="true"></i>
                                                        </span>
                                                    </div>
                                                    <input name="upd_emp_phnum" type="number" pattern="[0-9]{11}" class="form-control" @if($user_info->uEmp_phnum != 'null') value="{{$user_info->uEmp_phnum}}" @else placeholder="Type Contact Number" @endif required>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <input type="hidden" name="selected_user_id" value="{{auth()->user()->id}}"/>
                                                    <button type="submit" id="update_empInfoBtn" class="btn btn_svms_blue btn-round btn_show_icon" disabled>{{ __('Save Changes') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card card_gbr shadow">
                                    <div class="card-body p-0">
                                        <div class="card-header cb_p15x25">
                                            <span class="sec_card_body_title">Change Password</span>
                                            <span class="sec_card_body_subtitle">Verify your old password and enter a new password to change your password.</span>
                                        </div>
                                        <form class="form" method="POST" action="#">
                                            <div class="cb_px25 cb_pb15">
                                                <label for="cng_old_password">Old Password</label>
                                                <div class="input-group paswrd_inpt_fld">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="nc-icon nc-circle-10" aria-hidden="true"></i>
                                                        </span>
                                                    </div>
                                                    <input type="password" id="cng_old_password" name="cng_old_password" class="form-control" placeholder="Type your old Password" required>
                                                    <i class="fa fa-eye" id="toggleEmpOldPassword"></i>
                                                </div>
                                                <label for="cng_new_password">New Password</label>
                                                <div class="input-group paswrd_inpt_fld">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="nc-icon nc-key-25" aria-hidden="true"></i>
                                                        </span>
                                                    </div>
                                                    <input type="password" id="cng_new_password" name="cng_new_password" class="form-control" placeholder="Type your new password" required>
                                                    <i class="fa fa-eye" id="toggleEmpNewPassword"></i>
                                                </div>
                                                <label for="cng_confirm_new_password">Confirm New Password</label>
                                                <div class="input-group paswrd_inpt_fld">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="nc-icon nc-lock-circle-open" aria-hidden="true"></i>
                                                        </span>
                                                    </div>
                                                    <input type="password" id="cng_confirm_new_password" name="cng_confirm_new_password" class="form-control" placeholder="Just in case you mistyped" required>
                                                    <i class="fa fa-eye" id="toggleEmpConfirmNewPassword"></i>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <button type="submit" class="btn btn_svms_blue btn-round btn_show_icon">{{ __('Update My Password') }}<i class="nc-icon nc-key-25 btn_icon_show_right" aria-hidden="true"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

{{-- on change of input values = enable submit button --}}
    <script>
        $(document).ready(function(){
            $('#form_empUpdateProfile').each(function(){
                    $(this).data('serialized', $(this).serialize())
                }).on('change input', function(){
                    $(this).find('#update_empInfoBtn').attr('disabled', $(this).serialize() == $(this).data('serialized'));
                }).find('#update_empInfoBtn').attr('disabled', true);
        });
    </script>
{{-- on change of input values = enable submit button end --}}

{{-- paswword toggle visibility --}}
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
@endpush