<div class="sidebar" data-color="svms_data_color" data-active-color="svms_data_active_color">
    <div class="logo">
        <a href="http://www.creative-tim.com" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="{{ asset('storage/svms/logos/svms_logo.png') }}">
            </div>
        </a>
        <a href="http://www.creative-tim.com" class="simple-text logo-normal">
            @if(auth()->user()->user_role == 'pending')
                {{auth()->user()->user_type }} User
            @else
                {{ auth()->user()->user_role }}
            @endif
        </a>
    </div>
    <div class="sidebar-wrapper">
        @if(auth()->user()->user_status == 'active')
            <ul class="nav">
                <li class="{{ $elementActive == 'profile' ? 'active' : '' }}">
                    <a href="{{ route('profile.index', 'profile') }}">
                        <i class="nc-icon nc-single-02"></i>
                        <p>{{ __('My Profile') }}</p>
                    </a>
                </li>
                <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('page.index', 'dashboard') }}">
                        <i class="nc-icon nc-bank"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>
                {{-- <li class="{{ $elementActive == 'user' || $elementActive == 'profile' ? 'active' : '' }}">
                    <a data-toggle="collapse" aria-expanded="true" href="#laravelExamples">
                        <i class="nc-icon"><img src="{{ asset('paper/img/laravel.svg') }}"></i>
                        <p>
                                {{ __('Laravel examples') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse show" id="laravelExamples">
                        <ul class="nav">
                            <li class="{{ $elementActive == 'profile' ? 'active' : '' }}">
                                <a href="{{ route('profile.edit') }}">
                                    <span class="sidebar-mini-icon">{{ __('UP') }}</span>
                                    <span class="sidebar-normal">{{ __(' User Profile ') }}</span>
                                </a>
                            </li>
                            <li class="{{ $elementActive == 'user' ? 'active' : '' }}">
                                <a href="{{ route('page.index', 'user') }}">
                                    <span class="sidebar-mini-icon">{{ __('U') }}</span>
                                    <span class="sidebar-normal">{{ __(' User Management ') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
                <li class="{{ $elementActive == 'icons' ? 'active' : '' }}">
                    <a href="{{ route('page.index', 'icons') }}">
                        <i class="nc-icon nc-diamond"></i>
                        <p>{{ __('Icons') }}</p>
                    </a>
                </li>
                <li class="{{ $elementActive == 'map' ? 'active' : '' }}">
                    <a href="{{ route('page.index', 'map') }}">
                        <i class="nc-icon nc-pin-3"></i>
                        <p>{{ __('Maps') }}</p>
                    </a>
                </li>
                <li class="{{ $elementActive == 'notifications' ? 'active' : '' }}">
                    <a href="{{ route('page.index', 'notifications') }}">
                        <i class="nc-icon nc-bell-55"></i>
                        <p>{{ __('Notifications') }}</p>
                    </a>
                </li>
                <li class="{{ $elementActive == 'tables' ? 'active' : '' }}">
                    <a href="{{ route('page.index', 'tables') }}">
                        <i class="nc-icon nc-tile-56"></i>
                        <p>{{ __('Table List') }}</p>
                    </a>
                </li>
                <li class="{{ $elementActive == 'typography' ? 'active' : '' }}">
                    <a href="{{ route('page.index', 'typography') }}">
                        <i class="nc-icon nc-caps-small"></i>
                        <p>{{ __('Typography') }}</p>
                    </a>
                </li>
                {{-- <li class="active-pro {{ $elementActive == 'upgrade' ? 'active' : '' }}">
                    <a href="{{ route('page.index', 'upgrade') }}" class="bg-danger">
                        <i class="nc-icon nc-spaceship text-white"></i>
                        <p class="text-white">{{ __('Upgrade to PRO') }}</p>
                    </a>
                </li> --}}
            </ul>
        @else
            @if(auth()->user()->user_status == 'pending')
                <ul class="nav">
                    <li class="{{ $elementActive == 'profile' ? 'active' : '' }}">
                        <a href="{{ route('profile.index', 'profile') }}">
                            <i class="nc-icon nc-single-02"></i>
                            <p>{{ __('My Profile') }}</p>
                        </a>
                    </li>
                </ul>
            @endif
        @endif
    </div>
</div>
