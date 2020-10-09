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
                            <div class="tab-pane fade show active" id="profile_preview_{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_profile_preview_tab_{{auth()->user()->id}}">
                                @if(auth()->user()->user_type == 'employee')
                                    @php
                                        $user_info = DB::table('user_employees_tbl')->where('uEmp_id', auth()->user()->id)->first();
                                    @endphp
                                    @if(auth()->user()->user_role == 'administrator')
                                        <div class="card card_gbr shadow card-user">
                                            <div class="image">
                                                <img src="{{ asset('paper/img/damir-bosnjak.jpg') }}" alt="...">
                                            </div>
                                            <div class="card-body">
                                                <div class="author">
                                                    <a href="#" class="up_img_div">
                                                        <img class="up_user_image shadow border-gray" src="{{asset('storage/svms/user_images/'.auth()->user()->user_image)}}" alt="...">
                                                    </a>
                                                    <span class="up_fullname_txt">{{auth()->user()->user_fname }}  {{auth()->user()->user_lname}}</span>
                                                    <h5 class="up_role_txt">{{ __(auth()->user()->user_role)}}</h5>
                                                    
                                                    <span class="cat_title_txt">Employee ID</span>
                                                    <span class="up_info_txt"><i class="nc-icon nc-badge mr-1"></i> {{ auth()->user()->id}}</span>

                                                    <span class="up_info_txt mb-0">{{$user_info->uEmp_job_desc}}</span>
                                                    <span class="cat_title_txt mb-3">{{$user_info->uEmp_dept}}</span>

                                                    <span class="cat_title_txt">Contact Number</span>
                                                    <span class="up_info_txt"><i class="nc-icon nc-mobile mr-1"></i> {{ $user_info->uEmp_phnum}}</span>

                                                    <span class="cat_title_txt">Email Address</span>
                                                    <span class="up_info_txt"><i class="nc-icon nc-email-85 mr-1"></i> {{ auth()->user()->email}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                    @endif
                                @elseif(auth()->user()->user_type == 'student')
                                @else
                                @endif
                            </div>
                            <div class="tab-pane fade" id="pills_edit_profile_{{auth()->user()->id}}" role="tabpanel" aria-labelledby="pills_edit_profile_tab_{{auth()->user()->id}}">...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection