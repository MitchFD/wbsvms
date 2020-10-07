@extends('layouts.app', [
    'class' => 'login-page',
    'backgroundImagePath' => 'svms/sys/img/sdca_bg1.jpg'
])

@section('content')
    <div class="content">
        @if (session('deactivated_account_status'))
            <div class="row d-flex justify-content-center">
                <div class="col-lg-4 col-md-8 col-sm-10 align-items-center mx-auto">
                    <div class="alert alert_smvs_danger alert-dismissible login_alert fade show" role="alert">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="nc-icon nc-simple-remove"></i>
                        </button>
                        {{ session('deactivated_account_status') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="container">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                <form class="form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="card card-login card_gbr">
                        <div class="card-header">
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-12 col-md-12 col-sm-12 align-items-center text-center">
                                    <img class="login_img_logo" src="../storage/svms/sys/logos/svms_logo_title_red.png" alt="svms logo">
                                    <span class="login_title_txt">{{ __('User Login Credentials') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="nc-icon nc-single-02"></i>
                                    </span>
                                </div>
                                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ old('email') }}" required autofocus>
                                
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="input-group paswrd_inpt_fld mb-0">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="nc-icon nc-key-25"></i>
                                    </span>
                                </div>
                                <input id="inputloginPassword" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" type="password" required>
                                <i class="fa fa-eye" id="toggleLoginPassword"></i>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            {{-- <div class="form-group">
                                <div class="form-check">
                                     <label class="form-check-label align-items-center">
                                        <input class="form-check-input" name="remember" type="checkbox" value="" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="form-check-sign"></span>
                                        {{ __('Remember me') }}
                                    </label>
                                </div>
                            </div> --}}
                            <div class="form-group mt-2">
                                <div class="form-check">
                                     <label class="form-check-label">
                                        <input class="form-check-input custom-control-input" name="remember" type="checkbox" value="" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="form-check-sign"></span>
                                        {{ __('Remember me') }}
                                    </label>
                                </div>
                            </div>
                            {{-- <a href="{{ route('password.request') }}" class="frgtPwd_button">
                                {{ __('Forgot password') }}
                            </a> --}}
                        </div>
                        <div class="card-footer">
                            <div class="text-center">
                                <button type="submit" class="btn btn_svms_red btn-round btn_show_icon">{{ __('Log me in') }}<i class="fa fa-long-arrow-right btn_icon_show_right" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- <a href="{{ route('password.request') }}" class="btn btn-link">
                    {{ __('Forgot password') }}
                </a> --}}
                {{-- <a href="{{ route('register') }}" class="btn btn-link float-right">
                    {{ __('Create Account') }}
                </a> --}}
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12 col-md-12 col-sm-12 align-items-center text-center">
                    <a href="{{ route('password.request') }}" type="button" class="btn btn_svms_blue btn_show_icon btn-round">{{ __('Forgot Password') }}<i class="fa fa-question btn_icon_show_right" aria-hidden="true"></i></a>
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

    {{-- pasword toggle visibility --}}
        <script>
            const togglePassword = document.querySelector('#toggleLoginPassword');
            const password = document.querySelector('#inputloginPassword');
            togglePassword.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });
        </script>
    {{-- pasword toggle visibility end --}}
@endpush