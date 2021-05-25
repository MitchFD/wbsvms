@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'system_roles'
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
                <a href="{{ route('user_management.overview_users_management', 'user_management') }}" class="directory_link">User Management </a> <span class="directory_divider"> / </span> <a href="{{ route('user_management.system_roles', 'system_roles') }}" class="directory_active_link">System Roles </a>
            </div>
        </div>

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">System Roles</span>
                            <span class="page_intro_subtitle">This page displays all the registered system roles, its assigned users and role information. You can create new system role, manage their statuses, or delete roles. You can only delete Roles that has no assigned users.</span>
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/profile_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            if($countAll_RegisteredRoles > 0){
                if($countAll_RegisteredRoles > 1) {
                    $caRR_s = 's';
                }else{
                    $caRR_s = '';
                }
                $txt_rolesFound = ''.$countAll_RegisteredRoles . ' Registered System Role'.$caRR_s . ' Found.';
            }else{
                $caRR_s = '';
                $txt_rolesFound = 'There are No Registered System Roles Found.';
            } 
        @endphp

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr card_ofh shadow-none cb_x15y25 card_body_bg_gray">
                    <div class="card-header p-0 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="card_body_title">System Roles</span>
                            <span class="card_body_subtitle">{{ $txt_rolesFound }}</span>
                        </div>
                        <button onclick="registerNewSystemRole()" class="btn cust_btn_smcircle5" data-toggle="tooltip" data-placement="top" title="Create New System Role??"><i class="nc-icon nc-simple-add" aria-hidden="true"></i></button>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <div class="row">
                        @foreach ($queryAll_RegisteredRoles as $this_RegisteredRole)
                            @php
                                // to lowers
                                $toLower_uRoleName   = Str::lower($this_RegisteredRole->uRole);
                                $toLower_uRoleStatus = Str::lower($this_RegisteredRole->uRole_status);

                                // status classes and texts handler
                                if($toLower_uRoleStatus === 'active'){
                                    $class_uRoleStat = 'text-success font-italic';
                                    $txt_uRoleStat   = '~ Active';
                                    $cardBody_bgCol  = 'lightGreen_cardBody';
                                    $cardBody_title  = 'lightGreen_cardBody_greenTitle';
                                    $cardBody_lists  = 'lightGreen_cardBody_list';
                                }elseif($toLower_uRoleStatus === 'deactivated') {
                                    $class_uRoleStat = 'text_svms_red font-italic';
                                    $txt_uRoleStat   = '~ Deactivated';
                                    $cardBody_bgCol  = 'lightBlue_cardBody';
                                    $cardBody_title  = 'lightBlue_cardBody_blueTitlev1';
                                    $cardBody_lists  = 'lightBlue_cardBody_list';
                                }elseif($toLower_uRoleStatus === 'deleted'){
                                    $class_uRoleStat = 'text_svms_red font-italic';
                                    $txt_uRoleStat   = '~ Deleted';
                                    $cardBody_bgCol  = 'lightBlue_cardBody';
                                    $cardBody_title  = 'lightBlue_cardBody_blueTitlev1';
                                    $cardBody_lists  = 'lightBlue_cardBody_list';
                                }else{
                                    $class_uRoleStat = 'text-secondary font-italic';
                                    $txt_uRoleStat   = '~ Status Pending';
                                    $cardBody_bgCol  = 'lightBlue_cardBody';
                                    $cardBody_title  = 'lightBlue_cardBody_blueTitlev1';
                                    $cardBody_lists  = 'lightBlue_cardBody_list';
                                }

                                // query all assigned users
                                $queryAll_AssignedUsers  = App\Models\Users::where('user_role', '=', $toLower_uRoleName)->get();
                                $countQuery_AssignedUsers = count($queryAll_AssignedUsers);
                                if($countQuery_AssignedUsers > 0){
                                    if($countQuery_AssignedUsers > 1){
                                        $cqaAU_s = 's';
                                    }else{
                                        $cqaAU_s = '';
                                    }
                                    $txt_AssignedUsers   = ''.$countQuery_AssignedUsers . ' Assigned User'.$cqaAU_s.'.';
                                    $class_AssignedUsers = 'li_info_subtitle';
                                }else{
                                    $cqaAU_s = '';
                                    $txt_AssignedUsers = 'No Assigned Users!';
                                    $class_AssignedUsers = 'li_info_subtitle3';
                                }
                            @endphp
                            <div class="col-lg-4 col-md-4 col-sm-12 mt-4">
                                <div class="accordion violaAccordions shadow cust_accordion_div" id="sr{{$this_RegisteredRole->uRole_id}}Accordion_Parent">
                                    <div class="card custom_accordion_card">
                                        <div class="card-header p-0" id="changeUserRoleCollapse_heading">
                                            <h2 class="mb-0">
                                                <button class="btn btn-block custom2_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#sr{{$this_RegisteredRole->uRole_id}}Collapse_Div" aria-expanded="true" aria-controls="sr{{$this_RegisteredRole->uRole_id}}Collapse_Div">
                                                    <div class="d-flex justify-content-start align-items-center">
                                                        <div class="information_div2">
                                                            <span class="li_info_title">{{ucwords($this_RegisteredRole->uRole) }} <span class="{{$class_uRoleStat}}"> {{ $txt_uRoleStat }}</span></span>
                                                            <span class="{{$class_AssignedUsers}}">{{ $txt_AssignedUsers }}</span>
                                                        </div>
                                                    </div>
                                                    <i class="nc-icon nc-minimal-up"></i>
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="sr{{$this_RegisteredRole->uRole_id}}Collapse_Div" class="collapse violaAccordions_collapse show cb_t0b12y15" aria-labelledby="sr{{$this_RegisteredRole->uRole_id}}Collapse_heading" data-parent="#sr{{$this_RegisteredRole->uRole_id}}Accordion_Parent">
                                            {{-- assigned users --}}
                                            @if($countQuery_AssignedUsers > 0)
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="card-body lightBlue_cardBody mb-2">
                                                            <span class="lightBlue_cardBody_blueTitle mb-1">Assigned User{{$cqaAU_s}}:</span>
                                                            <div class="assignedUsersCirclesDiv">
                                                                <?php
                                                                    if($countQuery_AssignedUsers > 13){
                                                                        $getOnly_13UserImgs = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname', 'user_type')->where('user_role', $toLower_uRoleName)->take(13)->get();
                                                                        $more_count = $countQuery_AssignedUsers - 13;
                                                                        foreach($getOnly_13UserImgs->sortBy('id') as $display_13UserImgs){
                                                                            // tolower case user_type
                                                                            $tolower_uType = Str::lower($display_13UserImgs->user_type);
                                                                            // user image handler
                                                                            if(!is_null($display_13UserImgs->user_image) OR !empty($display_13UserImgs->user_image)){
                                                                                $user_imgJpgFile = $display_13UserImgs->user_image;
                                                                            }else{
                                                                                if($tolower_uType == 'employee'){
                                                                                    $user_imgJpgFile = 'employee_user_image.jpg';
                                                                                }elseif($tolower_uType == 'student'){
                                                                                    $user_imgJpgFile = 'student_user_image.jpg';
                                                                                }else{
                                                                                    $user_imgJpgFile = 'disabled_user_image.jpg';
                                                                                }
                                                                            }
                                                                            ?><img id="{{$display_13UserImgs->id}}" class="assignedUsersCirclesImgs4 F4F4F5_border cursor_pointer" src="{{asset('storage/svms/user_images/'.$user_imgJpgFile)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $display_13UserImgs->id) You @else {{$display_13UserImgs->user_fname. ' ' .$display_13UserImgs->user_lname}} @endif"> <?php
                                                                        }
                                                                    }else{
                                                                        $getAll_UserImgs = App\Models\Users::select('id', 'user_image', 'user_lname', 'user_fname', 'user_type')->where('user_role', $toLower_uRoleName)->get();
                                                                        foreach($getAll_UserImgs->sortBy('id') as $displayAll_UserImgs) {
                                                                            // tolower case user_type
                                                                            $tolower_uType = Str::lower($displayAll_UserImgs->user_type);
                                                                            // user image handler
                                                                            if(!is_null($displayAll_UserImgs->user_image) OR !empty($displayAll_UserImgs->user_image)){
                                                                                $user_imgJpgFile = $displayAll_UserImgs->user_image;
                                                                            }else{
                                                                                if($tolower_uType === 'employee'){
                                                                                    $user_imgJpgFile = 'employee_user_image.jpg';
                                                                                }elseif($tolower_uType === 'student'){
                                                                                    $user_imgJpgFile = 'student_user_image.jpg';
                                                                                }else{
                                                                                    $user_imgJpgFile = 'disabled_user_image.jpg';
                                                                                }
                                                                            }
                                                                            // onclick functions to view user's profiles
                                                                            if(auth()->user()->id == $displayAll_UserImgs->id){
                                                                                $onClickFunct = 'onclick="viewMyProfile(this.id)"';
                                                                            }else{
                                                                                $onClickFunct = 'onclick="viewMyUserProfile(this.id)"';
                                                                            }
                                                                            ?> <img id="{{$displayAll_UserImgs->id}}" {{ $onClickFunct }} class="assignedUsersCirclesImgs4 F4F4F5_border cursor_pointer" src="{{asset('storage/svms/user_images/'.$user_imgJpgFile)}}" alt="assigned user image" data-toggle="tooltip" data-placement="top" title="@if(auth()->user()->id === $displayAll_UserImgs->id) You @else {{$displayAll_UserImgs->user_fname. ' ' .$displayAll_UserImgs->user_lname}} @endif"> <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="card-body lightBlue_cardBody mb-2">
                                                            <span class="lightBlue_cardBody_list font-italic"><i class="fa fa-exclamation-circle font-weight-bold mr-1" aria-hidden="true"></i> No Assigned Users Found...</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            {{-- access controls --}}
                                            @if(!is_null($this_RegisteredRole->uRole_access) OR !empty($this_RegisteredRole->uRole_access))
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="card-body {{ $cardBody_bgCol }} mb-2">
                                                            <span class="{{ $cardBody_title }} mb-1">Access Controls: <i class="fa fa-info-circle cust_info_icon mx-1" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Pages Accessible to {{ ucwords($this_RegisteredRole->uRole) }} Role."></i></span>
                                                            @foreach(json_decode(json_encode($this_RegisteredRole->uRole_access), true) as $this_uRoleAccess)
                                                            <span class="{{ $cardBody_lists }}"><i class="fa fa-check-square-o font-weight-bold mr-1"></i> {{ ucwords($this_uRoleAccess) }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="card-body lightBlue_cardBody mb-2">
                                                            <span class="lightBlue_cardBody_list font-italic"><i class="fa fa-exclamation-circle font-weight-bold mr-1" aria-hidden="true"></i> No Access Controls Found...</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            {{-- footer --}}
                                            <div class="row mt-2">
                                                <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-center">
                                                    <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-users mr-1" aria-hidden="true"></i> {{ $txt_AssignedUsers}}</span>  
                                                    <div class="d-flex align-items-end">
                                                        @if($toLower_uRoleName !== 'administrator')
                                                            @php
                                                                if($toLower_uRoleStatus === 'active'){
                                                                    $onClick_icon    = 'fa fa-toggle-on';
                                                                    $onClick_tooltip = 'Deactivate ' . ucwords($this_RegisteredRole->uRole) . ' Role?';
                                                                    $onClick_funct   = 'onclick=deactivateSystemRole(this.id)';
                                                                }elseif($toLower_uRoleStatus === 'deactivated') {
                                                                    $onClick_icon    = 'fa fa-toggle-off';
                                                                    $onClick_tooltip = 'Activate ' . ucwords($this_RegisteredRole->uRole) . ' Role?';
                                                                    $onClick_funct   = 'onclick=activateSystemRole(this.id)';
                                                                }elseif($toLower_uRoleStatus === 'deleted'){
                                                                    $onClick_icon    = '';
                                                                    $onClick_tooltip = '';
                                                                    $onClick_funct   = '';
                                                                }else{
                                                                    $onClick_icon    = '';
                                                                    $onClick_tooltip = '';
                                                                    $onClick_funct   = '';
                                                                } 
                                                            @endphp
                                                            <button id="{{$this_RegisteredRole->uRole_id}}" {{ $onClick_funct }} class="btn cust_btn_smcircle2" data-toggle="tooltip" data-placement="top" title="{{ $onClick_tooltip }}"><i class="{{$onClick_icon}}" aria-hidden="true"></i></button>
                                                            @if($countQuery_AssignedUsers <= 0)
                                                                <button id="{{$this_RegisteredRole->uRole_id}}" onclick="deleteSystemRole(this.id)" class="btn cust_btn_smcircle2" data-toggle="tooltip" data-placement="top" title="Delete {{ ucwords($this_RegisteredRole->uRole) }} Role?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                    <div class="card-footer align-items-center px-0 pb-0">
                        <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-list-ul mr-1" aria-hidden="true"></i> {{ $txt_rolesFound }} </span>  
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modals --}}
    {{-- register new system role modal --}}
        <div class="modal fade" id="registerNewSystemRoleModal" tabindex="-1" role="dialog" aria-labelledby="registerNewSystemRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="registerNewSystemRoleModalLabel">Register New System Role?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body border-0">
                        <form id="form_registerNewSystemRole" action="{{route('user_management.create_new_system_role')}}" method="POST">
                            @csrf
                            <div class="card-body lightBlue_cardBody">
                                <span class="lightBlue_cardBody_blueTitle">Role Name:</span>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-badge"></i>
                                        </span>
                                    </div>
                                    <input id="create_role_name" name="create_role_name" type="text" class="form-control" placeholder="Type New Role Name" value="{{ old('create_role_name') }}" required>
                                </div>
                                <span class="lightBlue_cardBody_notice mt-2"><i class="fa fa-info-circle" aria-hidden="true"></i> Make the New Role Name in Singular Form (recommended).</span>
                            </div>
                            <div class="card-body lightBlue_cardBody shadow-none mt-2">
                                <div class="form-group cust_fltr_dropdowns_div mb-1">
                                    <label for="create_role_type">Select Role Type <i class="fa fa-question-circle cust_info_icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Role type selection is required for system preference."></i></label>
                                    <select class="form-control cust_fltr_dropdowns2 drpdwn_arrow2" id="create_role_type" name="create_role_type" required>
                                        <option value="employee" selected>Employee User</option>
                                        <option value="student">Student User</option>
                                    </select>
                                </div>
                                <span class="lightBlue_cardBody_notice mt-2"><i class="fa fa-info-circle" aria-hidden="true"></i> Role type selection is required for system preferences.</span>
                            </div>
                            <div class="card-body lightGreen_cardBody mt-2">
                                <span class="lightGreen_cardBody_greenTitle">Default Access Controls:</span>
                                <div class="form-group mx-0 mt-0 mb-1">
                                    <div class="custom-control custom-checkbox align-items-center">
                                        <input type="checkbox" name="create_role_access[]" value="profile" class="custom-control-input cursor_pointer" id="my_profile_mod" checked>
                                        <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="my_profile_mod">My Profile</label>
                                    </div>
                                </div>
                                <div class="form-group mx-0 mt-0 mb-1">
                                    <div class="custom-control custom-checkbox align-items-center">
                                        <input type="checkbox" name="create_role_access[]" value="violation entry" class="custom-control-input cursor_pointer" id="violation_entry_mod" checked>
                                        <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="violation_entry_mod">Violation Entry</label>
                                    </div>
                                </div>
                                <div class="form-group mx-0 mt-0 mb-1">
                                    <div class="custom-control custom-checkbox align-items-center">
                                        <input type="checkbox" name="create_role_access[]" value="student handbook" class="custom-control-input cursor_pointer" id="student_handbook_mod" checked>
                                        <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="student_handbook_mod">Student Handbook</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body lightRed_cardBody mt-2">
                                <span class="lightRed_cardBody_redTitle">Administrative Access Controls:</span>
                                <span class="lightRed_cardBody_notice"><i class="fa fa-lock" aria-hidden="true"></i> Below Modules are Administrative Access Controls that are not recommended for regular System Roles but you can enable them for this role if you wish to.</span>
                                <div class="form-group mx-0 mt-2 mb-1">
                                    <div class="custom-control custom-checkbox align-items-center">
                                        <input type="checkbox" name="create_role_access[]" value="dashboard" class="custom-control-input cursor_pointer" id="dashboard_mod">
                                        <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="dashboard_mod">Dashboard</label>
                                    </div>
                                </div>
                                <div class="form-group mx-0 mt-0 mb-1">
                                    <div class="custom-control custom-checkbox align-items-center">
                                        <input type="checkbox" name="create_role_access[]" value="users management" class="custom-control-input cursor_pointer" id="users_management_mod">
                                        <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="users_management_mod">Users Management</label>
                                    </div>
                                </div>
                                <div class="form-group mx-0 mt-0 mb-1">
                                    <div class="custom-control custom-checkbox align-items-center">
                                        <input type="checkbox" name="create_role_access[]" value="violation records" class="custom-control-input cursor_pointer" id="violation_record_mod">
                                        <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="violation_record_mod">Violation Records</label>
                                    </div>
                                </div>
                                <div class="form-group mx-0 mt-0 mb-1">
                                    <div class="custom-control custom-checkbox align-items-center">
                                        <input type="checkbox" name="create_role_access[]" value="sanctions" class="custom-control-input cursor_pointer" id="sanctions_mod">
                                        <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="sanctions_mod">Sanctions</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                    <input type="hidden" name="respo_user_id" value="{{auth()->user()->id}}">
                                    <input type="hidden" name="respo_user_lname" value="{{auth()->user()->user_lname}}">
                                    <input type="hidden" name="respo_user_fname" value="{{auth()->user()->user_fname}}">
                                    <div class="btn-group d-flex justify-content-end" role="group" aria-label="Register New System Role Actions">
                                        <button id="cancel_RegisterNewSystemRole_btn" type="button" class="btn btn-round btn_svms_blue btn_show_icon m-0" data-dismiss="modal"><i class="nc-icon nc-simple-remove btn_icon_show_left" aria-hidden="true"></i> Cancel</button>
                                        <button id="process_RegisterNewSystemRole_btn" type="submit" class="btn btn-round btn-success btn_show_icon m-0" disabled>Register New System Role <i class="nc-icon nc-check-2 btn_icon_show_right" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    {{-- register new system role modal end --}}
    {{-- deactivate system role modal --}}
        <div class="modal fade" id="deactivateSystemRoleModal" tabindex="-1" role="dialog" aria-labelledby="deactivateSystemRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="deactivateSystemRoleModalLabel">Deactivate System Role?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="deactivateSystemRoleModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- deactivate system role modal end --}}
    {{-- deactivate system role modal --}}
        <div class="modal fade" id="activateSystemRoleModal" tabindex="-1" role="dialog" aria-labelledby="activateSystemRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="activateSystemRoleModalLabel">Activate System Role?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="activateSystemRoleModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- deactivate system role modal end --}}
    {{-- temporary delete system role modal --}}
        <div class="modal fade" id="temporaryDeleteSystemRoleModal" tabindex="-1" role="dialog" aria-labelledby="temporaryDeleteSystemRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="temporaryDeleteSystemRoleModalLabel">Delete System Role?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="temporaryDeleteSystemRoleModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- temporary delete system role modal end --}}

@endsection

@push('scripts')
{{-- view user's profile by clicking image circles --}}
    {{-- OWN PROFILE --}}
    <script>
        function viewMyProfile(view_my_user_id){
            var view_my_user_id = view_my_user_id;
            alert(view_my_user_id);
        }
    </script>
    {{-- USER's PROFILE --}}
    <script>
        function viewMyUserProfile(view_user_id){
            var view_user_id = view_user_id;
            alert(view_user_id);
        }
    </script>
{{-- view user's profile by clicking image circles end --}}

{{-- register new system role on modal --}}
    <script>
        function registerNewSystemRole(){
            $('#registerNewSystemRoleModal').modal('show');
        }
    </script>
    <script>
        $('#registerNewSystemRoleModal').on('show.bs.modal', function () {
            var form_registerNewSystemRole  = document.querySelector("#form_registerNewSystemRole");
            var process_RegisterNewSystemRole_btn = document.querySelector("#process_RegisterNewSystemRole_btn");
            var cancel_RegisterNewSystemRole_btn = document.querySelector("#cancel_RegisterNewSystemRole_btn");
            // serialized form
            $(form_registerNewSystemRole).each(function(){
                $(this).data('serialized', $(this).serialize())
            }).on('change input', function(){
                $(this).find('#process_RegisterNewSystemRole_btn').prop('disabled', $(this).serialize() == $(this).data('serialized'));
            }).find('#process_RegisterNewSystemRole_btn').prop('disabled', true);
            // disable cancel and sibmit button on submit
            $(form_registerNewSystemRole).submit(function(){
                cancel_RegisterNewSystemRole_btn.disabled = true;
                process_RegisterNewSystemRole_btn.disabled = true;
                return true;
            });
        });
    </script>
{{-- register new system role on modal end --}}

{{-- delete system role --}}
    <script>
        function deleteSystemRole(tempDelete_uRole_id){
            var tempDelete_uRole_id = tempDelete_uRole_id;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user_management.temporary_delete_system_role_confirmation_modal') }}",
                method:"GET",
                data:{tempDelete_uRole_id:tempDelete_uRole_id, _token:_token},
                success: function(data){
                    $('#temporaryDeleteSystemRoleModalHtmlData').html(data); 
                    $('#temporaryDeleteSystemRoleModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#temporaryDeleteSystemRoleModal').on('show.bs.modal', function () {
            var form_systemRoleTempDeletion  = document.querySelector("#form_systemRoleTempDeletion");
            var process_tempDeleteSystemRole_btn = document.querySelector("#process_tempDeleteSystemRole_btn");
            var cancel_tempDeleteSystemRole_btn = document.querySelector("#cancel_tempDeleteSystemRole_btn");
            // disable cancel and sibmit button on submit
            $(form_systemRoleTempDeletion).submit(function(){
                cancel_tempDeleteSystemRole_btn.disabled = true;
                process_tempDeleteSystemRole_btn.disabled = true;
                return true;
            });
        });
    </script>
{{-- delete system role --}}

{{-- activate system role --}}
    <script>
        function activateSystemRole(activate_uRole_id){
            var activate_uRole_id = activate_uRole_id;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user_management.activate_role_modal') }}",
                method:"GET",
                data:{activate_uRole_id:activate_uRole_id, _token:_token},
                success: function(data){
                    $('#activateSystemRoleModalHtmlData').html(data); 
                    $('#activateSystemRoleModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#activateSystemRoleModal').on('show.bs.modal', function () {
            var form_systemRoleActivation  = document.querySelector("#form_systemRoleActivation");
            var process_activateSystemRole_btn = document.querySelector("#process_activateSystemRole_btn");
            var cancel_activateSystemRole_btn = document.querySelector("#cancel_activateSystemRole_btn");
            // disable cancel and sibmit button on submit
            $(form_systemRoleActivation).submit(function(){
                cancel_activateSystemRole_btn.disabled = true;
                process_activateSystemRole_btn.disabled = true;
                return true;
            });
        });
    </script>
{{-- activate system role end --}}

{{-- deactivate system role --}}
    <script>
        function deactivateSystemRole(deactivated_uRole_id){
            var deactivated_uRole_id = deactivated_uRole_id;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user_management.deactivate_role_modal') }}",
                method:"GET",
                data:{deactivated_uRole_id:deactivated_uRole_id, _token:_token},
                success: function(data){
                    $('#deactivateSystemRoleModalHtmlData').html(data); 
                    $('#deactivateSystemRoleModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#deactivateSystemRoleModal').on('show.bs.modal', function () {
            var form_systemRoleDeactivation  = document.querySelector("#form_systemRoleDeactivation");
            var process_deactivateSystemRole_btn = document.querySelector("#process_deactivateSystemRole_btn");
            var cancel_deactivateSystemRole_btn = document.querySelector("#cancel_deactivateSystemRole_btn");
            // disable cancel and sibmit button on submit
            $(form_systemRoleDeactivation).submit(function(){
                cancel_deactivateSystemRole_btn.disabled = true;
                process_deactivateSystemRole_btn.disabled = true;
                return true;
            });
        });
    </script>
{{-- deactivate system role end --}}
@endpush