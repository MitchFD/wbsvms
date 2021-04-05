@extends('layouts.app', [
    'class' => 'register-page',
    'backgroundImagePath' => 'svms/background_images/svms_bg3.jpg'
])

@section('content')
    <div class="content">
        <div class="container">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                <div class="card card-login card_gbr">
                    <div class="card-body">
                        <form class="form" method="POST" action="{{ route('password.update') }}">

                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="card-header ">
                                {{-- <h3 class="header text-center">{{ __('Reset Password') }}</h3> --}}
                                <div class="row d-flex justify-content-center">
                                    <div class="col-lg-12 col-md-12 col-sm-12 align-items-center text-center">
                                        <img class="login_img_logo" src="{{ asset('../storage/svms/logos/svms_logo_title_blue.png') }}" alt="svms logo">
                                        <span class="login_title_txt mb-3">{{ __('Reset Password') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="nc-icon nc-single-02"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative paswrd_inpt_fld">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="nc-icon nc-key-25"></i></span>
                                    </div>
                                    <input id="inputResetNewPassword" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" type="password" value="{{ old('password') }}" required>
                                    <i class="fa fa-eye" id="toggleResetNewPassword"></i>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group-alternative paswrd_inpt_fld">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="nc-icon nc-key-25"></i></span>
                                    </div>
                                    <input id="inputResetConfirmPassword" class="form-control" name="password_confirmation" placeholder="{{ __('Password Confirmation') }}" type="password" value="{{ old('password_confirmation') }}" required>
                                    <i class="fa fa-eye" id="toggleResetConfirmPassword"></i>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn_svms_blue  btn_show_icon btn-round mb-3">{{ __('Reset My Password') }}<i class="fa fa-refresh btn_icon_show_right" aria-hidden="true"></i></button>
                            </div>
                        </form>
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

    {{-- pasword toggle visibility --}}
        <script>
            const toggleResetNewPassword = document.querySelector('#toggleResetNewPassword');
            const resetNewPassword = document.querySelector('#inputResetNewPassword');
            toggleResetNewPassword.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = resetNewPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                resetNewPassword.setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });
        </script>
        <script>
            const toggleConfirmPassword = document.querySelector('#toggleResetConfirmPassword');
            const resetCofirmPassword = document.querySelector('#inputResetConfirmPassword');
            toggleConfirmPassword.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = resetCofirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                resetCofirmPassword.setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });
        </script>
    {{-- pasword toggle visibility end --}}
@endpush