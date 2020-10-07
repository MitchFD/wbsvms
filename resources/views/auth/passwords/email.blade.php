@extends('layouts.app', [
    'class' => 'login-page',
    'backgroundImagePath' => 'svms/sys/img/svms_bg3.jpg'
])

@section('content')
    <div class="content">
        <div class="container">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                <div class="card card-login card_gbr">
                    <div class="card-body">
                        <div class="card-header mb-3">
                            {{-- <h3 class="header text-center">{{ __('Reset Password') }}</h3> --}}
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-12 col-md-12 col-sm-12 align-items-center text-center">
                                    <img class="login_img_logo" src="../storage/svms/sys/logos/svms_logo_title_blue.png" alt="svms logo">
                                    <span class="login_title_txt">{{ __('Reset Password') }}</span>
                                </div>
                            </div>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form class="form" method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }} mb-3">
                                <div class="input-group input-group-alternative mb-0">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="nc-icon nc-single-02"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ old('email') }}" required autofocus>
                                </div>
                                @if ($errors->has('email'))
                                    <div>
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn_svms_blue btn_show_icon btn-round">{{ __('Send Password Reset Link') }}<i class="fa fa-paper-plane btn_icon_show_right" aria-hidden="true"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12 col-md-12 col-sm-12 align-items-center text-center">
                    <a href="/" type="button" class="btn btn_svms_red btn_show_icon btn-round"><i class="fa fa-reply btn_icon_show_left" aria-hidden="true"></i>{{ __('Back to Login') }}</a>
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
@endpush