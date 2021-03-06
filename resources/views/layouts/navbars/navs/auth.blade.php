<nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <div class="navbar-toggle">
                <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            <a class="navbar-brand" href="#">{{ __('Student Violation Management System') }}</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
            aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            {{-- <form>
                <div class="input-group no-border">
                    <input type="text" value="" class="form-control" placeholder="Search...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="nc-icon nc-zoom-split"></i>
                        </div>
                    </div>
                </div>
            </form> --}}
            <ul class="navbar-nav">
                {{-- <li class="nav-item">
                    <a class="nav-link btn-magnify" href="#pablo">
                        <i class="nc-icon nc-layout-11"></i>
                        <p>
                            <span class="d-lg-none d-md-block">{{ __('Stats') }}</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item btn-rotate dropdown">
                    <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="nc-icon nc-bell-55"></i>
                        <p>
                            <span class="d-lg-none d-md-block">{{ __('Some Actions') }}</span>
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">{{ __('Action') }}</a>
                        <a class="dropdown-item" href="#">{{ __('Another action') }}</a>
                        <a class="dropdown-item" href="#">{{ __('Something else here') }}</a>
                    </div>
                </li> --}}
                <li class="nav-item btn-rotate dropdown">
                    <a class="nav-link cust_nav_link dropdown-toggle py-0" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{-- <i class="nc-icon nc-settings-gear-65"></i> --}}
                        {{-- <p>
                            <span class="d-lg-none d-md-block">{{ __('Account') }}</span>
                        </p> --}}
                        @php
                            // single quote
                            $sq = "'";
                            // user image filter
                            if(auth()->user()->user_status == 'active'){
                                if(auth()->user()->user_type == 'student'){
                                    $nav_imgFltr = 'nav_userImg_stud';
                                }elseif(auth()->user()->user_type == 'employee'){
                                    $nav_imgFltr = 'nav_userImg_emp';
                                }else{
                                    $nav_imgFltr = 'nav_userImg_unknown';
                                }
                            }elseif(auth()->user()->user_status == 'deactivated'){
                                $nav_imgFltr = 'nav_userImg_red';
                            }else{
                                $nav_imgFltr = 'nav_userImg_unknown';
                            }
                            // user's image src and alt
                            if(!is_null(auth()->user()->user_image) OR !empty(auth()->user()->user_image)){
                                $user_image_src = asset('storage/svms/user_images/'.auth()->user()->user_image);
                                $user_image_alt = auth()->user()->user_fname . ' ' . auth()->user()->user_lname.''.$sq.'s profile image';
                            }else{
                                if(auth()->user()->user_status == 'active'){
                                    if(auth()->user()->user_type == 'employee'){
                                        $user_image_jpg = 'employee_user_image.jpg';
                                    }elseif(auth()->user()->user_type == 'student'){
                                        $user_image_jpg = 'student_user_image.jpg';
                                    }else{
                                        $user_image_jpg = 'disabled_user_image.jpg';
                                    }
                                    $user_image_src = asset('storage/svms/user_images/'.$user_image_jpg);
                                }else{
                                    $user_image_src = asset('storage/svms/user_images/no_student_image.jpg');
                                }
                                $user_image_alt = 'default user'.$sq.'s profile image';
                            }
                        @endphp
                        <img class="nav_userImg {{ $nav_imgFltr }} mr-2" src="{{$user_image_src}}" alt="{{$user_image_alt}}">
                        <span class="nav_userFnameTxt mr-2">{{auth()->user()->user_fname }} {{ auth()->user()->user_lname }}</span>
                    </a>
                    <div class="dropdown-menu cust_dropdown_menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink2">
                        <form class="dropdown-item" action="{{ route('logout') }}" id="formLogOut" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item" onclick="document.getElementById('formLogOut').submit();">{{ __('Log out') }}</a>
                        <a class="dropdown-item" href="{{ route('profile.index', 'profile') }}">{{ __('My Profile') }}</a>
                        {{-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink"> --}}
                            {{-- <a class="dropdown-item" onclick="document.getElementById('formLogOut').submit();">{{ __('Log out') }}</a>
                            <a class="dropdown-item">{{ __('My Profile') }}</a> --}}
                            {{-- <a href="{{url('/log_me_out')}}" class="dropdown-item" >{{ __('Log out') }}</a> --}}
                            {{-- <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('My profile') }}</a> --}}
                        {{-- </div> --}}
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
