@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'create_users'
])

@section('content')
    <div class="content">
        {{-- notifications --}}
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
                <a href="{{ route('user_management.index', 'user_management') }}" class="directory_link">User Management </a> <span class="directory_divider"> / </span> <a href="{{ route('user_management.create_users', 'create_users') }}" class="directory_active_link">Create Users </a>
            </div>
        </div>

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">Create Users</span>
                            <span class="page_intro_subtitle">This page allows you to register new system users, you can create a user account for an employee type user or a student type user by filling-up the required information. You can also create a new system role if there are no options available to be assigned to the new user and select modules for its access control.</span>
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/profile_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- user creation form --}}
            <div class="col-lg-7 col-md-7 col-sm-12">
                <div class="accordion" id="createUserCollapseParent">
                    <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                        <div class="card-header p-0" id="createUserCollapseHeading">
                            <button id="createUser_collapseBtnToggle" class="btn btn-link btn-block custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#createUserCollapseDiv" aria-expanded="true" aria-controls="createUserCollapseDiv">
                                <div>
                                    <span class="card_body_title">User Registration</span>
                                    <span class="card_body_subtitle">Select a user type to create new user account.</span>
                                </div>
                                <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                            </button>
                        </div>
                        <div class="cb_t0b15x25">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 d-flex justify-content-center align-items-center">
                                    <div class="nav cust_vNav_div flex-column nav-pills" id="userRegistrationTab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link cust_vNav_link active" id="uEmployeeForm-tab" data-toggle="pill" href="#uEmployeeForm" role="tab" aria-controls="uEmployeeForm" aria-selected="true">Employee</a>
                                        <a class="nav-link cust_vNav_link" id="uStudentForm-tab" data-toggle="pill" href="#uStudentForm" role="tab" aria-controls="uStudentForm" aria-selected="false">Student</a>
                                        <a class="nav-link cust_vNav_link" id="sendLink-tab" data-toggle="pill" href="#sendLink" role="tab" aria-controls="sendLink" aria-selected="false">Send Link</a>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-10">
                                    <div class="tab-content" id="userRegistrationTabContent">
                                        <div class="tab-pane fade show active" id="uEmployeeForm" role="tabpanel" aria-labelledby="uEmployeeForm-tab">
                                            <div class="card card_gbr shadow">
                                                <div class="card-body p-0">
                                                    <div class="card-header cb_p15x25">
                                                        <span class="sec_card_body_title">Employee Type User</span>
                                                        {{-- <span class="sec_card_body_subtitle">Click the <span class="font-weight-bold">'Register User'</span> button to register new user.</span> --}}
                                                    </div>
                                                    <form id="form_empUpdateProfile" class="form" method="POST" action="{{route('user_management.new_employee_user_process_registration')}}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="cb_px25 cb_pb15">
                                                            <div class="row d-flex justify-content-center">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 align-items-center">
                                                                    <div class="up_img_div text-center">
                                                                        <img class="up_user_image createEmp_imgUpld_targetImg shadow" src="{{asset('storage/svms/user_images/employee_user_image.jpg')}}" alt="upload user's image">
                                                                    </div>
                                                                    <div class="user_image_upload_input_div emp_imgUpload">
                                                                        <i class="nc-icon nc-image createEmp_imgUpld_TrgtBtn"></i>
                                                                        <input name="create_emp_user_image" class="file_upload_input createEmp_img_imgUpld_fileInpt" value="{{ old('create_emp_user_image') }}" type="file" accept="image/*"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <label for="create_emp_id">Employee ID</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="create_emp_id" name="create_emp_id" type="number" min="0" oninput="validity.valid||(value='');" class="form-control" placeholder="Type Employee ID" value="{{ old('create_emp_id') }}" required>
                                                            </div>
                                                            <label for="create_emp_lname">Last Name</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-single-02"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="create_emp_lname" name="create_emp_lname" type="text" class="form-control" placeholder="Type Last Name" value="{{ old('create_emp_lname') }}" required>
                                                            </div>
                                                            <label for="create_emp_fname">First Name</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-single-02"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="create_emp_fname" name="create_emp_fname" type="text" class="form-control" placeholder="Type First Name" value="{{ old('create_emp_fname') }}" required>
                                                            </div>
                                                            <label for="create_emp_gender">Gender</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-single-02"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="create_emp_gender" list="updateGenderOptions" pattern="Male|Female" name="create_emp_gender" type="text" class="form-control" placeholder="Select Gender" value="{{ old('updateGenderOptions') }}" required>
                                                                <datalist id="updateGenderOptions">
                                                                    <option value="Male">
                                                                    <option value="Female">
                                                                </datalist>
                                                            </div>
                                                            <label for="create_emp_jobdesc">Job Description</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-briefcase-24" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="create_emp_jobdesc" name="create_emp_jobdesc" type="text" class="form-control" placeholder="Type Job Position" value="{{ old('create_emp_jobdesc') }}" required>
                                                            </div>
                                                            <label for="create_emp_dept">Department</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-bank" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="create_emp_dept" name="create_emp_dept" type="text" class="form-control" placeholder="Type Department" value="{{ old('create_emp_dept') }}" required>
                                                            </div>
                                                            <label for="create_emp_phnum">Phone NUmber</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="fa fa-mobile" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="create_emp_phnum" name="create_emp_phnum" type="number" pattern="[0-9]{11}" min="0" oninput="validity.valid||(value='');" class="form-control" placeholder="Type Contact Number" value="{{ old('create_emp_phnum') }}" required>
                                                            </div>
                                                            <label for="create_emp_email">Email Address</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="nc-icon nc-email-85" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="create_emp_email" name="create_emp_email" type="text" class="form-control" placeholder="user's_email@sdca.edu.ph" value="{{ old('create_emp_email') }}" required>
                                                                <span id="createEmpEmail_ver" class="d-none text-right">
        
                                                                </span>
                                                            </div>
                                                            <div class="d-flex justify-content-center">
                                                                <input type="hidden" name="respo_user_id" value="{{auth()->user()->id}}"/>
                                                                <input type="hidden" name="respo_user_lname" value="{{auth()->user()->user_last_name}}"/>
                                                                <input type="hidden" name="respo_user_fname" value="{{auth()->user()->user_first_name}}"/>
                                                                <button type="submit" id="createEmpUser_RegisterBtn" class="btn btn_svms_blue btn-round btn_show_icon" disabled>{{ __('Register User') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="uStudentForm" role="tabpanel" aria-labelledby="uStudentForm-tab">...</div>
                                        <div class="tab-pane fade" id="sendLink" role="tabpanel" aria-labelledby="sendLink-tab">...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- user creation form end --}}
            {{-- system role creation form --}}
            <div class="col-lg-5 col-md-5 col-sm-12">
                <div class="card card_gbr card_ofh shadow-none card_body_bg_gray">
                    <div class="card-header">
                        <span class="card_body_title">System Role Registration</span>
                        <span class="card_body_subtitle">Create new User Role if there are no options available to be assigned to the new user.</span>
                    </div>
                    <div class="card-body">
                        
                    </div>
                </div>
            </div>
            {{-- system role creation form end --}}
        </div>
    </div>
@endsection

@push('scripts')
{{-- initialize tooltip --}}
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
{{-- initialize tooltip end --}}
@endpush