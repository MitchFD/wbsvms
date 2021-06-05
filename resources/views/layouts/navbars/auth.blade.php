<div class="sidebar" data-color="svms_data_color" data-active-color="svms_data_active_color">
    <div class="logo">
        <a href="#" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="{{ asset('storage/svms/logos/svms_logo.png') }}">
            </div>
        </a>
        <a href="#" class="simple-text logo-normal">
            @if(auth()->user()->user_role == 'pending')
                {{auth()->user()->user_type }} User
            @else
                {{ auth()->user()->user_role }}
            @endif
        </a>
    </div>
    <div class="sidebar-wrapper">
        @if(auth()->user()->user_status == 'active')
            {{-- get access controls --}}
            @php
                $get_user_role_info = App\Models\Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
                $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
            @endphp
            <ul class="nav">
                @if(in_array('profile', $get_uRole_access))
                    <li class="{{ $elementActive == 'profile' ? 'active' : '' }}">
                        <a href="{{ route('profile.index', 'profile') }}">
                            <i class="nc-icon nc-single-02"></i>
                            <p>{{ __('My Profile') }}</p>
                        </a>
                    </li>
                @endif
                @if(in_array('dashboard', $get_uRole_access))
                    <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                        <a href="{{ route('page.index', 'dashboard') }}">
                            <i class="nc-icon nc-layout-11"></i>
                            <p>{{ __('Dashboard') }}</p>
                        </a>
                    </li>
                @endif
                @if(in_array('users management', $get_uRole_access))
                    <li class="{{ $elementActive == 'overview_users_management' || $elementActive == 'user_management' || $elementActive == 'create_users' || $elementActive == 'system_users' || $elementActive == 'system_roles' || $elementActive == 'users_logs' ? 'active' : '' }}">
                        <a data-toggle="collapse" aria-expanded="false" href="#usersManagementCollapse">
                            <i class="nc-icon nc-circle-10"></i>
                            <p>
                                {{ __('USERS MANAGEMENT') }}
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse" id="usersManagementCollapse">
                            <ul class="nav">
                                <li class="{{ $elementActive == 'overview_users_management' ? 'active' : '' }}">
                                    <a style="padding-left: 25px !important;" href="{{ route('user_management.overview_users_management', 'overview_users_management') }}">
                                        <i class="nc-icon nc-minimal-right sidebar-mini-icon"></i>
                                        <span class="sidebar-normal">{{ __(' Overview ') }}</span>
                                    </a>
                                </li>
                                {{-- <li class="{{ $elementActive == 'user_management' ? 'active' : '' }}">
                                    <a style="padding-left: 25px !important;" href="{{ route('user_management.index', 'user_management') }}">
                                        <i class="nc-icon nc-minimal-right sidebar-mini-icon"></i>
                                        <span class="sidebar-normal">{{ __(' Default ') }}</span>
                                    </a>
                                </li> --}}
                                <li class="{{ $elementActive == 'create_users' ? 'active' : '' }}">
                                    <a style="padding-left: 25px !important;" href="{{ route('user_management.create_users', 'create_users') }}">
                                        <i class="nc-icon nc-minimal-right sidebar-mini-icon"></i>
                                        <span class="sidebar-normal">{{ __(' Create Users ') }}</span>
                                    </a>
                                </li>
                                <li class="{{ $elementActive == 'system_users' ? 'active' : '' }}">
                                    <a style="padding-left: 25px !important;" href="{{ route('user_management.system_users', 'system_users') }}">
                                        <i class="nc-icon nc-minimal-right sidebar-mini-icon"></i>
                                        <span class="sidebar-normal">{{ __(' System Users ') }}</span>
                                    </a>
                                </li>
                                <li class="{{ $elementActive == 'system_roles' ? 'active' : '' }}">
                                    <a style="padding-left: 25px !important;" href="{{ route('user_management.system_roles', 'system_roles') }}">
                                        <i class="nc-icon nc-minimal-right sidebar-mini-icon"></i>
                                        <span class="sidebar-normal">{{ __(' System Roles ') }}</span>
                                    </a>
                                </li>
                                <li class="{{ $elementActive == 'users_logs' ? 'active' : '' }}">
                                    <a style="padding-left: 25px !important;" href="{{ route('user_management.users_logs', 'users_logs') }}">
                                        <i class="nc-icon nc-minimal-right sidebar-mini-icon"></i>
                                        <span class="sidebar-normal">{{ __(' Users Logs ') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if(in_array('violation entry', $get_uRole_access))
                    <li class="{{ $elementActive == 'violation_entry' ? 'active' : '' }}">
                        <a href="{{ route('violation_entry.index', 'violation_entry') }}">
                            <i class="nc-icon nc-paper"></i>
                            <p>{{ __('Violation Entry') }}</p>
                        </a>
                    </li>
                @endif
                {{-- @if(in_array('violation records', $get_uRole_access))
                    <li class="{{ $elementActive == 'violation_records' ? 'active' : '' }}">
                        <a href="{{ route('violation_records.index', 'violation_records') }}">
                            <i class="nc-icon nc-box"></i>
                            <p>{{ __('Violation Records') }}</p>
                        </a>
                    </li>
                @endif --}}
                @if(in_array('violation records', $get_uRole_access))
                    <li class="{{ $elementActive == 'violation_records' || $elementActive == 'deleted_violation_records' ? 'active' : '' }}">
                        <a data-toggle="collapse" aria-expanded="false" href="#violationRecordsCollapse">
                            <i class="nc-icon nc-box"></i>
                            <p>
                                {{ __('VIOLATION RECORDS') }}
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse" id="violationRecordsCollapse">
                            <ul class="nav">
                                <li class="{{ $elementActive == 'violation_records' ? 'active' : '' }}">
                                    <a style="padding-left: 25px !important;" href="{{ route('violation_records.index', 'violation_records') }}">
                                        <i class="nc-icon nc-minimal-right sidebar-mini-icon"></i>
                                        <span class="sidebar-normal">{{ __(' Violation Records ') }}</span>
                                    </a>
                                </li>
                                <li class="{{ $elementActive == 'deleted_violation_records' ? 'active' : '' }}">
                                    <a style="padding-left: 25px !important;" href="{{ route('violation_records.deleted_violation_records', 'deleted_violation_records') }}">
                                        <i class="nc-icon nc-minimal-right sidebar-mini-icon"></i>
                                        <span class="sidebar-normal">{{ __(' Deleted Violations ') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                {{-- @if(in_array('offenses', $get_uRole_access))
                    <li class="{{ $elementActive == 'offenses' ? 'active' : '' }}">
                        <a href="{{ route('offenses.index', 'offenses') }}">
                            <i class="nc-icon nc-align-left-2"></i>
                            <p>{{ __('offenses') }}</p>
                        </a>
                    </li>
                @endif --}}
                @if(in_array('sanctions', $get_uRole_access))
                    <li class="{{ $elementActive == 'sanctions' ? 'active' : '' }}">
                        <a href="{{ route('sanctions.index', 'sanctions') }}">
                            <i class="nc-icon nc-bullet-list-67"></i>
                            <p>{{ __('Sanctions') }}</p>
                        </a>
                    </li>
                @endif
                @if(in_array('student handbook', $get_uRole_access))
                    <li class="{{ $elementActive == 'student_handbook' ? 'active' : '' }}">
                        <a href="{{ route('student_handbook.index', 'student_handbook') }}">
                            <i class="nc-icon nc-book-bookmark"></i>
                            <p>{{ __('Student Handbook') }}</p>
                        </a>
                    </li>
                @endif
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
                {{-- <li class="{{ $elementActive == 'icons' ? 'active' : '' }}">
                    <a href="{{ route('page.index', 'icons') }}">
                        <i class="nc-icon nc-diamond"></i>
                        <p>{{ __('Icons') }}</p>
                    </a>
                </li> --}}
                {{-- <li class="{{ $elementActive == 'map' ? 'active' : '' }}">
                    <a href="{{ route('page.index', 'map') }}">
                        <i class="nc-icon nc-pin-3"></i>
                        <p>{{ __('Maps') }}</p>
                    </a>
                </li> --}}
                {{-- <li class="{{ $elementActive == 'notifications' ? 'active' : '' }}">
                    <a href="{{ route('page.index', 'notifications') }}">
                        <i class="nc-icon nc-bell-55"></i>
                        <p>{{ __('Notifications') }}</p>
                    </a>
                </li> --}}
                {{-- <li class="{{ $elementActive == 'tables' ? 'active' : '' }}">
                    <a href="{{ route('page.index', 'tables') }}">
                        <i class="nc-icon nc-tile-56"></i>
                        <p>{{ __('Table List') }}</p>
                    </a>
                </li> --}}
                {{-- <li class="{{ $elementActive == 'typography' ? 'active' : '' }}">
                    <a href="{{ route('page.index', 'typography') }}">
                        <i class="nc-icon nc-caps-small"></i>
                        <p>{{ __('Typography') }}</p>
                    </a>
                </li> --}}
                {{-- <li class="active-pro {{ $elementActive == 'upgrade' ? 'active' : '' }}">
                    <a href="{{ route('page.index', 'upgrade') }}" class="bg-danger">
                        <i class="nc-icon nc-spaceship text-white"></i>
                        <p class="text-white">{{ __('Upgrade to PRO') }}</p>
                    </a>
                </li> --}}
            </ul>
        @else
            <ul class="nav">
                <li class="{{ $elementActive == 'profile' ? 'active' : '' }}">
                    <a href="{{ route('profile.index', 'profile') }}">
                        <i class="nc-icon nc-single-02"></i>
                        <p>{{ __('My Profile') }}</p>
                    </a>
                </li>
            </ul>
        @endif
    </div>
</div>
