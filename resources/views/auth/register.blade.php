@extends('layouts.app', [
    'class' => 'register-page',
    'backgroundImagePath' => 'svms/sys/img/sdca_bg1.jpg'
])

@section('content')
    <div class="content registration_content">
        @if (session('account_registration_failed_status'))
            <div class="row d-flex justify-content-center">
                <div class="col-lg-4 col-md-8 col-sm-10 align-items-center mx-auto">
                    <div class="alert alert_smvs_danger alert-dismissible login_alert fade show" role="alert">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="nc-icon nc-simple-remove"></i>
                        </button>
                        {{ session('account_registration_failed_status') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="container">
            <div class="row">
                {{-- <div class="col-lg-5 col-md-5 ml-auto">
                    <div class="info-area info-horizontal mt-5">
                        <div class="icon icon-primary">
                            <i class="nc-icon nc-tv-2"></i>
                        </div>
                        <div class="description">
                            <h5 class="info-title">{{ __('Marketing') }}</h5>
                            <p class="description">
                                {{ __('We\'ve created the marketing campaign of the website. It was a very interesting collaboration.') }}
                            </p>
                        </div>
                    </div>
                    <div class="info-area info-horizontal">
                        <div class="icon icon-primary">
                            <i class="nc-icon nc-html5"></i>
                        </div>
                        <div class="description">
                            <h5 class="info-title">{{ __('Fully Coded in HTML5') }}</h5>
                            <p class="description">
                                {{ __('We\'ve developed the website with HTML5 and CSS3. The client has access to the code using GitHub.') }}
                            </p>
                        </div>
                    </div>
                    <div class="info-area info-horizontal">
                        <div class="icon icon-info">
                            <i class="nc-icon nc-atom"></i>
                        </div>
                        <div class="description">
                            <h5 class="info-title">{{ __('Built Audience') }}</h5>
                            <p class="description">
                                {{ __('There is also a Fully Customizable CMS Admin Dashboard for this product.') }}
                            </p>
                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-6 col-md-6 ml-auto">
                    <div class="info-area info-horizontal">
                        <div class="row d-flex justify-content-start mb-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 align-items-center text-left">
                                <img class="register_img_logo" src="../storage/svms/sys/logos/svms_logo_title_red.png" alt="svms logo">
                                {{-- <span class="login_title_txt">{{ __('Registration') }}</span> --}}
                            </div>
                        </div>
                        <div class="description">
                            {{-- <h5 class="info-title">{{ __('What is SVMS?') }}</h5> --}}
                            <p class="description">
                                SVMS or Student Violation Management System is a Web-Based Application that serves as an extension of the Student Discipline Office under the Department of Student Affairs and Services for the execution and implementation of the institution's policies, guidelines, rules and regulations by recording and monitoring violations committed by the college students of St. Dominic College of Asia.  
                            </p>
                        </div>
                    </div>
                    <div class="info-area info-horizontal mt-3">
                        <div class="description">
                            <h5 class="info-title">{{ __('SVMS Registration') }}</h5>
                            <p class="description">
                                This page is available to you with the permision of the System Administrator to register your valid credentials such as Employee ID, Name and email address and gain access to the system. If you happen to land on this page without prior notice from the Student Discipline Office, you are free to leave this page.
                            </p>
                        </div>
                    </div>
                    <div class="info-area info-horizontal mt-3">
                        <div class="description">
                            <h5 class="info-title">{{ __('To Register') }}</h5>
                            <p class="description">
                                First, you must select your current status at St. Dominic College of Asia by toggling the two tabs with the 'Employee' and 'Student' labels. Then fill-up the form according to your status. We highly recommend using your sdca-Gmail account for the emailing and login services; kindly head to the MIS Office for creating your own sdca-Gmail account if you don't have any. All input fields are required; be truthful and honest with the credentials you provide
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mr-auto">
                    <div class="card card-signup text-center card_gbr">
                        {{-- <div class="card-header">
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-12 col-md-12 col-sm-12 align-items-center text-center">
                                    <img class="login_img_logo" src="../storage/svms/sys/logos/svms_logo_title_red.png" alt="svms logo">
                                    <span class="login_title_txt">{{ __('Registration') }}</span>
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="card-header ">
                            <h4 class="card-title">{{ __('Register') }}</h4>
                            <div class="social">
                                <button class="btn btn-icon btn-round btn-twitter">
                                    <i class="fa fa-twitter"></i>
                                </button>
                                <button class="btn btn-icon btn-round btn-dribbble">
                                    <i class="fa fa-dribbble"></i>
                                </button>
                                <button class="btn btn-icon btn-round btn-facebook">
                                    <i class="fa fa-facebook-f"></i>
                                </button>
                                <p class="card-description">{{ __('or be classical') }}</p>
                            </div>
                        </div> --}}
                        <div class="card-body px-0">
                            <ul class="nav nav-pills custom_nav_pills mt-3 mb-3 d-flex justify-content-center" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link custom_nav_link_red active" id="employeeRegistrationFormTab" data-toggle="pill" href="#employeeRegistrationForm" role="tab" aria-controls="employeeRegistrationForm" aria-selected="true">Employee</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link custom_nav_link_blue" id="studentRegistrationFormTab" data-toggle="pill" href="#studentRegistrationForm" role="tab" aria-controls="studentRegistrationForm" aria-selected="false">Student</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                {{-- employee form --}}
                                <div class="tab-pane fade show active" id="employeeRegistrationForm" role="tabpanel" aria-labelledby="employeeRegistrationFormTab">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                            <span class="registration_title_txt text_svms_red">{{ __('EMPLOYEE USER REGISTRATION') }}</span>
                                        </div>
                                    </div>
                                    {{-- <form class="form" method="POST" action="{{ route('register') }}"> --}}
                                    <form class="form" method="POST" action="{{route('register.employee_process_registration')}}">
                                        @csrf
                                        <div class="mx-3 mb-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <input id="reg_empId" name="reg_empId" type="number" class="form-control" placeholder="Type Employee ID" value="{{ old('reg_empId') }}" required>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-single-02"></i>
                                                    </span>
                                                </div>
                                                <input id="reg_empLname" name="reg_empLname" type="text" class="form-control" placeholder="Type Last Name" value="{{ old('reg_empLname') }}" required>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-single-02"></i>
                                                    </span>
                                                </div>
                                                <input id="reg_empFname" name="reg_empFname" type="text" class="form-control" placeholder="Type First Name" value="{{ old('reg_empFname') }}" required>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-briefcase-24" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <input id="reg_empJobDesc" name="reg_empJobDesc" type="text" class="form-control" placeholder="Type Job Position" value="{{ old('reg_empJobDesc') }}" required>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-bank" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <input id="reg_empDept" name="reg_empDept" type="text" class="form-control" placeholder="Type Department" value="{{ old('reg_empDept') }}" required>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-mobile" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <input name="reg_empPhnum" type="number" pattern="[0-9]{11}" class="form-control" placeholder="Type Contact Number" value="{{ old('reg_empPhnum') }}" required>
                                            </div>
                                        </div>
                                        <div class="cust_card_body_ligth_bg">
                                            <div class="input-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                                <div class="input-group-prepend is_invalid" id="prepend_status">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-email-85"></i>
                                                    </span>
                                                </div>
                                                <input name="email" type="email" id="empEmail" class="form-control" placeholder="your_email@sdca.edu.ph" required value="{{ old('email') }}">
                                                <span id="email_ver" class="d-none text-right">

                                                </span>
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }} paswrd_inpt_fld">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-key-25"></i>
                                                    </span>
                                                </div>
                                                <input id="inputRegisterPassword" name="password" type="password" class="form-control" placeholder="Password" required>
                                                <i class="fa fa-eye" id="toggleRegisterPassword"></i>
                                                @if ($errors->has('password'))
                                                    <span class="invalid-feedback text-right" style="display: block;" role="alert">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="input-group paswrd_inpt_fld">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-lock-circle-open"></i>
                                                    </span>
                                                </div>
                                                <input id="inputConfirmRegisterPassword" name="password_confirmation" type="password" class="form-control paswrd_inpt_fld" placeholder="Confirm Password" required>
                                                <i class="fa fa-eye" id="toggleConfirmRegisterPassword"></i>
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="invalid-feedback text-right" style="display: block;" role="alert">
                                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <button type="submit" id="empRegAccount" class="btn btn_svms_red btn-round btn_show_icon">{{ __('Register Now') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                    </form>
                                </div>
                                {{-- student form --}}
                                <div class="tab-pane fade" id="studentRegistrationForm" role="tabpanel" aria-labelledby="studentRegistrationFormTab">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                            <span class="registration_title_txt text_svms_blue">{{ __('STUDENT USER REGISTRATION') }}</span>
                                        </div>
                                    </div>
                                    <form class="form" method="POST" action="{{route('register.student_process_registration')}}">
                                        @csrf
                                        <div class="mx-3 mb-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <input id="reg_studNum" name="reg_studNum" type="number" class="form-control" placeholder="Type Student Number" value="{{ old('reg_studNum') }}" required>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-single-02"></i>
                                                    </span>
                                                </div>
                                                <input id="reg_studLname" name="reg_studLname" type="text" class="form-control" placeholder="Type Last Name" value="{{ old('reg_studLname') }}" required>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-single-02"></i>
                                                    </span>
                                                </div>
                                                <input id="reg_studFname" name="reg_studFname" type="text" class="form-control" placeholder="Type First Name" value="{{ old('reg_studFname') }}" required>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <input id="reg_studSchool" name="reg_studSchool" type="text" class="form-control" placeholder="Type School" value="{{ old('reg_studSchool') }}" required>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <input id="reg_studProgram" name="reg_studProgram" type="text" class="form-control" placeholder="Type Program" value="{{ old('reg_studProgram') }}" required>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <input id="reg_studYearlvl" name="reg_studYearlvl" type="text" class="form-control" placeholder="Type Year level" value="{{ old('reg_studYearlvl') }}" required>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-badge" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <input id="reg_studSection" name="reg_studSection" type="text" class="form-control" placeholder="Type Section" value="{{ old('reg_studSection') }}" required>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-mobile" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <input name="reg_studPhnum" type="number" pattern="[0-9]{11}" class="form-control" placeholder="Type Contact Number" value="{{ old('reg_studPhnum') }}" required>
                                            </div>
                                        </div>
                                        <div class="cust_card_body_ligth_bg">
                                            <div class="input-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                                <div class="input-group-prepend is_invalid" id="prepend_status">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-email-85"></i>
                                                    </span>
                                                </div>
                                                <input name="student_email" type="email" id="studEmail" class="form-control" placeholder="your_email@sdca.edu.ph" required value="{{ old('student_email') }}">
                                                <span id="stud_email_ver" class="d-none text-right">

                                                </span>
                                            </div>
                                            <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }} paswrd_inpt_fld">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-key-25"></i>
                                                    </span>
                                                </div>
                                                <input id="inputRegisterPassword_stud" name="student_password" type="password" class="form-control" placeholder="Password" required>
                                                <i class="fa fa-eye" id="toggleRegisterPassword_stud"></i>
                                                @if ($errors->has('student_password'))
                                                    <span class="invalid-feedback text-right" style="display: block;" role="alert">
                                                        <strong>{{ $errors->first('student_password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="input-group paswrd_inpt_fld">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="nc-icon nc-lock-circle-open"></i>
                                                    </span>
                                                </div>
                                                <input id="inputConfirmRegisterPassword_stud" name="student_password_confirmation" type="password" class="form-control paswrd_inpt_fld" placeholder="Confirm Password" required>
                                                <i class="fa fa-eye" id="toggleConfirmRegisterPassword_stud"></i>
                                                @if ($errors->has('student_password_confirmation'))
                                                    <span class="invalid-feedback text-right" style="display: block;" role="alert">
                                                        <strong>{{ $errors->first('student_password_confirmation') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <button type="submit" id="empStudAccount" class="btn btn_svms_blue btn-round btn_show_icon">{{ __('Register Now') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                    </form>
                                </div>
                              </div>
                            {{-- <form class="form" method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="input-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-single-02"></i>
                                        </span>
                                    </div>
                                    <input name="name" type="text" class="form-control" placeholder="Name" value="{{ old('name') }}" required autofocus>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="input-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-email-85"></i>
                                        </span>
                                    </div>
                                    <input name="email" type="email" class="form-control" placeholder="Email" required value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-key-25"></i>
                                        </span>
                                    </div>
                                    <input name="password" type="password" class="form-control" placeholder="Password" required>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-key-25"></i>
                                        </span>
                                    </div>
                                    <input name="password_confirmation" type="password" class="form-control" placeholder="Password confirmation" required>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-check text-left">
                                    <label class="form-check-label">
                                        <input class="form-check-input" name="agree_terms_and_conditions" type="checkbox">
                                        <span class="form-check-sign"></span>
                                            {{ __('I agree to the') }}
                                        <a href="#something">{{ __('terms and conditions') }}</a>.
                                    </label>
                                    @if ($errors->has('agree_terms_and_conditions'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('agree_terms_and_conditions') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="card-footer ">
                                    <button type="submit" class="btn btn_svms_red btn-round btn_show_icon">{{ __('Get Started') }}<i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                </div>
                            </form> --}}
                        </div>
                    </div>
                </div>
             </div>
        </div>
     </div> 
@endsection

@push('scripts')
    {{-- <script>
        $(document).ready(function() {
            demo.checkFullPageBackgroundImage();
        });
    </script> --}}

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

    {{-- pasword toggle visibility --}}
        {{-- employee user registration form --}}
        <script>
            const toggleRegisterPassword = document.querySelector('#toggleRegisterPassword');
            const RegisterPassword = document.querySelector('#inputRegisterPassword');
            toggleRegisterPassword.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = RegisterPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                RegisterPassword.setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });
        </script>
        <script>
            const toggleConfirmRegisterPassword = document.querySelector('#toggleConfirmRegisterPassword');
            const confirmRegisterPassword = document.querySelector('#inputConfirmRegisterPassword');
            toggleConfirmRegisterPassword.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = confirmRegisterPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmRegisterPassword.setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });
        </script>
        {{-- student user registration form --}}
        <script>
            const toggleRegisterPassword_stud = document.querySelector('#toggleRegisterPassword_stud');
            const RegisterPassword_stud = document.querySelector('#inputRegisterPassword_stud');
            toggleRegisterPassword_stud.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = RegisterPassword_stud.getAttribute('type') === 'password' ? 'text' : 'password';
                RegisterPassword_stud.setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });
        </script>
        <script>
            const toggleConfirmRegisterPassword_stud = document.querySelector('#toggleConfirmRegisterPassword_stud');
            const confirmRegisterPassword_stud = document.querySelector('#inputConfirmRegisterPassword_stud');
            toggleConfirmRegisterPassword_stud.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = confirmRegisterPassword_stud.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmRegisterPassword_stud.setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });
        </script>
    {{-- pasword toggle visibility end --}}

    {{-- email check --}}
        {{-- employee email --}}
        <script>
            $(document).ready(function(){
                $('#empEmail').blur(function(){
                    var error_email = '';
                    var email = $('#empEmail').val();
                    var _token = $('input[name="_token"]').val();
                    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    if(!filter.test(email)){    
                        $('#email_ver').removeClass('d-none');
                        $('#email_ver').addClass('invalid-feedback');
                        $('#email_ver').addClass('d-block');
                        $('#prepend_status').addClass('is_invalid');
                        $('#email_ver').html('<strong>Invalid Email Format!</strong>');
                        $('#empEmail').addClass('is-invalid');
                        $('#empRegAccount').attr('disabled', 'disabled');
                    }else{
                        $.ajax({
                            url:"{{ route('register.email_availability_check') }}",
                            method:"POST",
                            data:{email:email, _token:_token},
                            success:function(result){
                                if(result == 'unique'){
                                    $('#email_ver').removeClass('d-none');
                                    $('#email_ver').removeClass('invalid-feedback');
                                    $('#email_ver').addClass('valid-feedback');
                                    $('#email_ver').html('<strong>Email Available.</strong>');
                                    $('#empEmail').removeClass('is-invalid');
                                    $('#empEmail').addClass('is-valid');
                                    $('#empRegAccount').attr('disabled', false);
                                    // console.log('unique');
                                }else{
                                    $('#email_ver').removeClass('d-none');
                                    $('#email_ver').addClass('invalid-feedback');
                                    $('#email_ver').addClass('d-block');
                                    $('#prepend_status').addClass('is_invalid');
                                    $('#email_ver').html('<strong>Email already in use!</strong>');
                                    $('#empEmail').addClass('is-invalid');
                                    $('#empRegAccount').attr('disabled', 'disabled');
                                    // console.log('duplicate');
                                }
                            }
                        })
                    }
                });
            });
        </script>
        {{-- student email --}}
        <script>
            $(document).ready(function(){
                $('#studEmail').blur(function(){
                    var error_email = '';
                    var email = $('#studEmail').val();
                    var _token = $('input[name="_token"]').val();
                    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    if(!filter.test(email)){    
                        $('#stud_email_ver').removeClass('d-none');
                        $('#stud_email_ver').addClass('invalid-feedback');
                        $('#stud_email_ver').addClass('d-block');
                        $('#prepend_status').addClass('is_invalid');
                        $('#stud_email_ver').html('<strong>Invalid Email Format!</strong>');
                        $('#studEmail').addClass('is-invalid');
                        $('#empStudAccount').attr('disabled', 'disabled');
                    }else{
                        $.ajax({
                            url:"{{ route('register.email_availability_check') }}",
                            method:"POST",
                            data:{email:email, _token:_token},
                            success:function(result){
                                if(result == 'unique'){
                                    $('#stud_email_ver').removeClass('d-none');
                                    $('#stud_email_ver').removeClass('invalid-feedback');
                                    $('#stud_email_ver').addClass('valid-feedback');
                                    $('#stud_email_ver').html('<strong>Email Available.</strong>');
                                    $('#studEmail').removeClass('is-invalid');
                                    $('#studEmail').addClass('is-valid');
                                    $('#empStudAccount').attr('disabled', false);
                                    // console.log('unique');
                                }else{
                                    $('#stud_email_ver').removeClass('d-none');
                                    $('#stud_email_ver').addClass('invalid-feedback');
                                    $('#stud_email_ver').addClass('d-block');
                                    $('#prepend_status').addClass('is_invalid');
                                    $('#stud_email_ver').html('<strong>Email already in use!</strong>');
                                    $('#studEmail').addClass('is-invalid');
                                    $('#empStudAccount').attr('disabled', 'disabled');
                                    // console.log('duplicate');
                                }
                            }
                        })
                    }
                });
            });
        </script>
    {{-- email check end --}}
@endpush
