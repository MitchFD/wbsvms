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

        {{-- system roles cards --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr card_ofh shadow-none cb_x15y25 card_body_bg_gray">
                    <div class="accordion gCardAccordions" id="systemRolesDisplayCollapseParent">
                        <div class="card-header p-0 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="card_body_title">System Roles</span>
                                <span class="card_body_subtitle">{{ $txt_rolesFound }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <form action="#" class="mr-4">
                                    @csrf
                                    <div class="btn-group btn-group-toggle mr-4" data-toggle="buttons">
                                        <label class="btn btn_svms_blue cust_btn_radio cbr_pV1 active" data-toggle="tooltip" data-placement="top" title="Display All Role Types?">
                                            <input class="m-0 p-0" type="radio" name="systemRolesFilterTypes" id="systemRolesFilterTypes_allURoles" value="all_types" autocomplete="off" checked> All Role Types
                                        </label>
                                        <label class="btn btn_svms_blue cust_btn_radio cbr_pV1" data-toggle="tooltip" data-placement="top" title="Display Employee Type Roles Only?">
                                            <input class="m-0 p-0" type="radio" name="systemRolesFilterTypes" id="systemRolesFilterTypes_employeeURoles" value="employee" autocomplete="off"> Employee
                                        </label>
                                        <label class="btn btn_svms_blue cust_btn_radio cbr_pV1" data-toggle="tooltip" data-placement="top" title="Display Student Type Roles Only?">
                                            <input class="m-0 p-0" type="radio" name="systemRolesFilterTypes" id="systemRolesFilterTypes_studentURoles" value="student" autocomplete="off"> Student
                                        </label>
                                    </div>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn_svms_blue cust_btn_radio cbr_p active" data-toggle="tooltip" data-placement="top" title="Display All Roles?">
                                            <input class="m-0 p-0" type="radio" name="systemRolesFilterStatus" id="systemRolesFilterStatus_allURoles" value="all_status" autocomplete="off" checked> <i class="fa fa-list-ul cbr_i" aria-hidden="true"></i>
                                        </label>
                                        <label class="btn btn_svms_blue cust_btn_radio cbr_p" data-toggle="tooltip" data-placement="top" title="Display Active Roles Only?">
                                            <input class="m-0 p-0" type="radio" name="systemRolesFilterStatus" id="systemRolesFilterStatus_activeURoles" value="active" autocomplete="off"> <i class="fa fa-toggle-on cbr_i" aria-hidden="true"></i>
                                        </label>
                                        <label class="btn btn_svms_blue cust_btn_radio cbr_p" data-toggle="tooltip" data-placement="top" title="Display Deactivated Roles Only?">
                                            <input class="m-0 p-0" type="radio" name="systemRolesFilterStatus" id="systemRolesFilterStatus_deactivateURoles" value="deactivated" autocomplete="off"> <i class="fa fa-toggle-off cbr_i" aria-hidden="true"></i>
                                        </label>
                                    </div>
                                </form>
                                <button onclick="registerNewSystemRole()" class="btn cust_btn_smcircle5v2 mr-2" data-toggle="tooltip" data-placement="top" title="Create New System Role??"><i class="nc-icon nc-simple-add" aria-hidden="true"></i></button>
                                <button class="btn cust_btn_smcircle5v2 acc_collapse_cards" data-toggle="collapse" data-target="#systemRolesDisplayCollapseDiv" aria-expanded="true" aria-controls="systemRolesDisplayCollapseDiv"><i class="nc-icon nc-minimal-up" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        <div id="systemRolesDisplayCollapseDiv" class="collapse gCardAccordions_collapse show p-0" aria-labelledby="userStatusDisplayCollapseHeading" data-parent="#systemRolesDisplayCollapseParent">
                            <div class="card-body px-0 pt-0">
                                <div class="row" id="parent_SystemRoles_cards">
                                    {{-- ajax --}}
                                </div>
                            </div>
                            <div class="card-footer align-items-center px-0 pb-0">
                                <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-list-ul mr-1" aria-hidden="true"></i> <span id="totalURoles_found"> </span> </span>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- deleted system roles card --}}
        @php
            $countAll_deletedRoles = count($queryAll_DeletedRoles);
            if($countAll_deletedRoles > 0){
                if($countAll_deletedRoles > 1){
                    $tdrC_s = 's'; 
                }else{
                    $tdrC_s = '';
                }
                $txt_totalDeletedRolesCount = ''.$countAll_deletedRoles . ' Deleted System Role'.$tdrC_s . ' Found.';
            }else{
                $tdrC_s = '';
                $txt_totalDeletedRolesCount = 'No Deleted System Roles Found.';
            }
        @endphp
        @if($countAll_deletedRoles > 0)
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="accordion gCardAccordions" id="recentlyDeletedRolesDisplayCollapseParent">
                        <div class="card card_gbr card_ofh shadow-none p-0 card_body_bg_gray">
                            <div class="card-header p-0" id="recentlyDeletedRolesDisplayCollapseHeading">
                                <button class="btn btn-link btn-block acc_collapse_cards custom_btn_collapse m-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#recentlyDeletedRolesDisplayCollapseDiv" aria-expanded="true" aria-controls="recentlyDeletedRolesDisplayCollapseDiv">
                                    <div>
                                        <span class="card_body_title">Recently Deleted</span>
                                        <span class="card_body_subtitle">Below are System Roles that have been deleted.</span>
                                    </div>
                                    <i class="nc-icon nc-minimal-up custom_btn_collapse_icon"></i>
                                </button>
                            </div>
                            <div id="recentlyDeletedRolesDisplayCollapseDiv" class="collapse gCardAccordions_collapse show cb_t0b15x25" aria-labelledby="recentlyDeletedRolesDisplayCollapseHeading" data-parent="#recentlyDeletedRolesDisplayCollapseParent">
                                <div class="row">
                                    @foreach($queryAll_DeletedRoles as $this_deletedRole)
                                        @php
                                            // get responsible user who created this role
                                            if(auth()->user()->id === $this_deletedRole->del_created_by){
                                                $txtRole_createdByName  = 'Created by You.';
                                                $txtRole_createdByRole = '';
                                            }else{
                                                $queryUser_createdBy   = App\Models\Users::select('id', 'user_fname', 'user_lname', 'user_role')->where('id', '=', $this_deletedRole->del_created_by)->first();
                                                $txtRole_createdByName = ''.$queryUser_createdBy->user_fname . ' ' . $queryUser_createdBy->user_lname.'';
                                                $txtRole_createdByRole = '('.$queryUser_createdBy->user_role.')';
                                            }

                                            // get responsible user who deleted this role
                                            if(auth()->user()->id === $this_deletedRole->deleted_by){
                                                $txtRole_deletedByName = 'Deleted by You.';
                                                $txtRole_deletedByRole = '';
                                            }else{
                                                $queryUser_deletedBy   = App\Models\Users::select('id', 'user_fname', 'user_lname', 'user_role')->where('id', '=', $this_deletedRole->deleted_by)->first();
                                                $txtRole_deletedByName = ''.$queryUser_deletedBy->user_fname . ' ' . $queryUser_deletedBy->user_lname.'';
                                                $txtRole_deletedByRole = '('.$queryUser_deletedBy->user_role.')';
                                            }
                                        @endphp
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-4">
                                            <div class="accordion violaAccordions shadow cust_accordion_div" id="dsr{{$this_deletedRole->del_uRole_id}}Accordion_Parent">
                                                <div class="card custom_accordion_card">
                                                    <div class="card-header p-0" id="changeUserRoleCollapse_heading">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-block custom2_btn_collapse cb_x12y15 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#dsr{{$this_deletedRole->del_uRole_id}}Collapse_Div" aria-expanded="true" aria-controls="dsr{{$this_deletedRole->del_uRole_id}}Collapse_Div">
                                                                <div class="d-flex justify-content-start align-items-center">
                                                                    <div class="information_div2">
                                                                        <span class="li_info_titlev1">{{ $this_deletedRole->del_uRole }}</span>
                                                                        <span class="li_info_subtitle3" data-toggle="tooltip" data-placement="top" title="Date the {{ $this_deletedRole->del_uRole }} Role was deleted:">{{ date('F d, Y (D ~ g:i A)', strtotime($this_deletedRole->deleted_at))}} </span>
                                                                    </div>
                                                                </div>
                                                                <i class="nc-icon nc-minimal-up"></i>
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="dsr{{$this_deletedRole->del_uRole_id}}Collapse_Div" class="collapse violaAccordions_collapse show cb_t0b12y15" aria-labelledby="dsr{{$this_deletedRole->del_uRole_id}}Collapse_heading" data-parent="#dsr{{$this_deletedRole->del_uRole_id}}Accordion_Parent">
                                                        {{-- access controls --}}
                                                        @if(!is_null($this_deletedRole->del_uRole_access) OR !empty($this_deletedRole->del_uRole_access))
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="card-body lightBlue_cardBody mb-2">
                                                                        <span class="lightBlue_cardBody_blueTitlev1 mb-1">Access Controls: <i class="fa fa-info-circle cust_info_icon mx-1" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Pages Accessible to {{ $this_deletedRole->del_uRole }} Role."></i></span>
                                                                        @foreach(json_decode(json_encode($this_deletedRole->del_uRole_access), true) as $this_uRoleAccess)
                                                                        <span class="lightBlue_cardBody_list"><i class="fa fa-check-square-o font-weight-bold mr-1"></i> {{ $this_uRoleAccess }}</span>
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
                                                        {{-- reason for deletion --}}
                                                        @if(!is_null($this_deletedRole->reason_deletion) OR !empty($this_deletedRole->reason_deletion))
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="card-body lightBlue_cardBody mb-2">
                                                                        <span class="lightBlue_cardBody_blueTitlev1 mb-1">Reason of Deletion: </span>
                                                                        <span class="lightBlue_cardBody_list"><i class="fa fa-question-circle font-weight-bold mr-1"></i> {{ $this_deletedRole->reason_deletion }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        {{-- footer --}}
                                                        <div class="row mt-3">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-center">
                                                                <div class="cursor_default" data-toggle="tooltip" data-placement="top" title="Date the {{ $this_deletedRole->del_uRole }} Role was created and created by:">
                                                                    <span class="cust_info_txtwicon mb-1"><i class="fa fa-calendar-plus-o mr-1" aria-hidden="true"></i>{{ date('F d, Y (D ~ g:i A)', strtotime($this_deletedRole->del_created_at)) }}</span> 
                                                                    <span class="cust_info_txtwicon"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> {{ $txtRole_createdByName }} <span class="font-italic"> {{ $txtRole_createdByRole }} </span></span> 
                                                                </div> 
                                                                <div class="d-flex align-items-end">
                                                                    <button id="{{$this_deletedRole->del_uRole_id}}" onclick="recoverDeletedSystemRole(this.id)" class="btn cust_btn_smcircle2" data-toggle="tooltip" data-placement="top" title="Recover {{ $this_deletedRole->del_uRoles }} Role?"><i class="fa fa-external-link" aria-hidden="true"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr class="hr_gry">
                                                        <div class="row mt-2">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-center">
                                                                <div class="cursor_default" data-toggle="tooltip" data-placement="top" title="Deleted by:">
                                                                    <span class="cust_info_txtwicon text_svms_red"><i class="nc-icon nc-tap-01 mr-1" aria-hidden="true"></i> {{ $txtRole_deletedByName }} <span class="font-italic"> {{ $txtRole_deletedByRole }} </span></span> 
                                                                </div> 
                                                                <div class="d-flex align-items-end">
                                                                    <button id="{{$this_deletedRole->del_uRole_id}}" onclick="permanentDeleteSystemRole(this.id)" class="btn cust_btn_smcircle2" data-toggle="tooltip" data-placement="top" title="Delete {{ $this_deletedRole->del_uRole }} Role Permanently?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card-body p-0 d-flex justify-content-between align-items-center">
                                            <span class="cust_info_txtwicon font-weight-bold"><i class="fa fa-trash mr-1" aria-hidden="true"></i> {{ $txt_totalDeletedRolesCount }} </span>  
                                            <div>
                                                <button onclick="recoverAllDeletedRoles()" class="btn cust_btn_smcircle5v1" data-toggle="tooltip" data-placement="top" title="Recover All Recently Deleted Roles?"><i class="fa fa-external-link" aria-hidden="true"></i></button>
                                                <button onclick="permanentDeleteAllDeletedRoles()" class="btn cust_btn_smcircle5v1" data-toggle="tooltip" data-placement="top" title="Permanent Delete All Recently Deleted Roles?"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
                                        <input type="checkbox" name="create_role_access[]" value="disciplinary policies" class="custom-control-input cursor_pointer" id="disciplinary_policies_mod" checked>
                                        <label class="custom-control-label lightGreen_cardBody_chckboxLabel" for="disciplinary_policies_mod">Disciplinary Policies</label>
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
                                        <input type="checkbox" name="create_role_access[]" value="offenses" class="custom-control-input cursor_pointer" id="offenses_mod">
                                        <label class="custom-control-label lightRed_cardBody_chckboxLabel" for="offenses_mod">Offenses</label>
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
    {{-- permanent delete system role modal --}}
        {{-- single permanent deletion --}}
        <div class="modal fade" id="permanentDeleteSystemRoleModal" tabindex="-1" role="dialog" aria-labelledby="permanentDeleteSystemRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="permanentDeleteSystemRoleModalLabel">Permanently Delete System Role?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="permanentDeleteSystemRoleModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
        {{-- multiple permanent deletion --}}
        <div class="modal fade" id="permanentDeleteAllSystemRoleModal" tabindex="-1" role="dialog" aria-labelledby="permanentDeleteAllSystemRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="permanentDeleteAllSystemRoleModalLabel">Permanently Delete All System Role?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="permanentDeleteAllSystemRoleModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- permanent delete system role modal end --}}
    {{-- recover deleted system role modal --}}
        {{-- single recovery --}}
        <div class="modal fade" id="recoverDeletedSystemRoleModal" tabindex="-1" role="dialog" aria-labelledby="recoverDeletedSystemRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="recoverDeletedSystemRoleModalLabel">Recover Deleted System Role?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="recoverDeletedSystemRoleModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
        {{-- multiple recovery --}}
        <div class="modal fade" id="recoverAllDeletedSystemRoleModal" tabindex="-1" role="dialog" aria-labelledby="recoverAllDeletedSystemRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content cust_modal">
                    <div class="modal-header border-0">
                        <span class="modal-title cust_modal_title" id="recoverAllDeletedSystemRoleModalLabel">Recover All Deleted System Role?</span>
                        <button type="button" class="close cust_close_modal_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="recoverAllDeletedSystemRoleModalHtmlData">
                    
                    </div>
                </div>
            </div>
        </div>
    {{-- recover deleted system role modal end --}}

@endsection

@push('scripts')
{{-- load system roles with ajax --}}
    <script>
        $(document).ready(function(){
            load_systemRoles_cards();

            function load_systemRoles_cards(){
                var selectURoles_types = document.querySelector('input[type=radio][name=systemRolesFilterTypes]:checked').value;  
                var selectURoles_status = document.querySelector('input[type=radio][name=systemRolesFilterStatus]:checked').value;  

                console.log('Selected Role Type: ' + selectURoles_types);
                console.log('Selected Role Status: ' + selectURoles_status);

                $.ajax({
                    url:"{{ route('user_management.load_system_roles_cards') }}",
                    method:"GET",
                    data:{
                        selectURoles_status:selectURoles_status,
                        selectURoles_types:selectURoles_types
                    },
                    dataType:'json',
                    success:function(sr_data){
                        $('#parent_SystemRoles_cards').html(sr_data.system_roles_cards);
                        $('#totalURoles_found').html(sr_data.total_roles_found);
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });
            }

            $('input[type=radio][name=systemRolesFilterStatus]').change(function() {
                load_systemRoles_cards();
            });
            $('input[type=radio][name=systemRolesFilterTypes]').change(function() {
                load_systemRoles_cards();
            });
        });
    </script>
{{-- load system roles with ajax end --}}

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
    {{-- temporary delete --}}
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
    {{-- permanent delete --}}
    {{-- single permanent deletion --}}
    <script>
        function permanentDeleteSystemRole(permDelete_uRole_id){
            var permDelete_uRole_id = permDelete_uRole_id;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user_management.permanent_delete_system_role_confirmation_modal') }}",
                method:"GET",
                data:{permDelete_uRole_id:permDelete_uRole_id, _token:_token},
                success: function(data){
                    $('#permanentDeleteSystemRoleModalHtmlData').html(data); 
                    $('#permanentDeleteSystemRoleModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#permanentDeleteSystemRoleModal').on('show.bs.modal', function () {
            var form_systemRolePermDeletion  = document.querySelector("#form_systemRolePermDeletion");
            var process_permDeleteSystemRole_btn = document.querySelector("#process_permDeleteSystemRole_btn");
            var cancel_permDeleteSystemRole_btn = document.querySelector("#cancel_permDeleteSystemRole_btn");
            // disable cancel and sibmit button on submit
            $(form_systemRolePermDeletion).submit(function(){
                cancel_permDeleteSystemRole_btn.disabled = true;
                process_permDeleteSystemRole_btn.disabled = true;
                return true;
            });
        });
    </script>
    {{-- multiple permanent deletion --}}
    <script>
        function permanentDeleteAllDeletedRoles(){
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user_management.permanent_delete_all_system_role_confirmation_modal') }}",
                method:"GET",
                data:{_token:_token},
                success: function(data){
                    $('#permanentDeleteSystemRoleModalHtmlData').html(data); 
                    $('#permanentDeleteSystemRoleModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#permanentDeleteSystemRoleModal').on('show.bs.modal', function () {
            var form_permDeleteAllDeletedRoles  = document.querySelector("#form_permDeleteAllDeletedRoles");
            var submit_permDeleteAllDeletedRolesBtn = document.querySelector("#submit_permDeleteAllDeletedRolesBtn");
            var cancel_permDeleteAllDeletedRolesBtn = document.querySelector("#cancel_permDeleteAllDeletedRolesBtn");
            // disable /enable submit button
            function dis_en_submit_permDeleteAllDeletedRolesBtn(){
                var has_permDeleteRolesMarSingle = 0;
                $(".permDeleteRolesMarSingle").each(function(){
                    if(this.checked){
                        has_permDeleteRolesMarSingle = 1;
                    }
                });
                if(has_permDeleteRolesMarSingle != 0){
                    submit_permDeleteAllDeletedRolesBtn.disabled = false;
                }else{
                    submit_permDeleteAllDeletedRolesBtn.disabled = true;
                }
            }
            // selection of sanctions for deletion
            $("#permDeleteRolesMarkAll").change(function(){
                if(this.checked){
                $(".permDeleteRolesMarSingle").each(function(){
                    this.checked=true;
                })              
                }else{
                $(".permDeleteRolesMarSingle").each(function(){
                    this.checked=false;
                })              
                }
                dis_en_submit_permDeleteAllDeletedRolesBtn();
            });
            $(".permDeleteRolesMarSingle").click(function () {
                if ($(this).is(":checked")){
                var ispermDeleteRolesMarkAllChecked = 0;
                $(".permDeleteRolesMarSingle").each(function(){
                    if(!this.checked)
                    ispermDeleteRolesMarkAllChecked = 1;
                })              
                if(ispermDeleteRolesMarkAllChecked == 0){ $("#permDeleteRolesMarkAll").prop("checked", true); }     
                }else {
                $("#permDeleteRolesMarkAll").prop("checked", false);
                }
                dis_en_submit_permDeleteAllDeletedRolesBtn();
            });
            // disable cancel and sibmit button on submit
            $(form_permDeleteAllDeletedRoles).submit(function(){
                cancel_permDeleteAllDeletedRolesBtn.disabled = true;
                submit_permDeleteAllDeletedRolesBtn.disabled = true;
                return true;
            });
        });
    </script>
{{-- delete system role --}}
{{-- recover system roles --}}
    {{-- single recovery --}}
    <script>
        function recoverDeletedSystemRole(recoverDeleted_uRole_id){
            var recoverDeleted_uRole_id = recoverDeleted_uRole_id;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user_management.recover_deleted_system_role_confirmation_modal') }}",
                method:"GET",
                data:{recoverDeleted_uRole_id:recoverDeleted_uRole_id, _token:_token},
                success: function(data){
                    $('#recoverDeletedSystemRoleModalHtmlData').html(data); 
                    $('#recoverDeletedSystemRoleModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#recoverDeletedSystemRoleModal').on('show.bs.modal', function () {
            var form_deletedSystemRoleRecovery  = document.querySelector("#form_deletedSystemRoleRecovery");
            var process_recoverDeletedRoles_btn = document.querySelector("#process_recoverDeletedRoles_btn");
            var cancel_recoverDeletedRoles_btn = document.querySelector("#cancel_recoverDeletedRoles_btn");
            // disable cancel and sibmit button on submit
            $(form_deletedSystemRoleRecovery).submit(function(){
                cancel_recoverDeletedRoles_btn.disabled = true;
                process_recoverDeletedRoles_btn.disabled = true;
                return true;
            });
        });
    </script>
    {{-- multiple recovery --}}
    <script>
        function recoverAllDeletedRoles(){
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user_management.recover_all_deleted_system_role_confirmation_modal') }}",
                method:"GET",
                data:{_token:_token},
                success: function(data){
                    $('#recoverAllDeletedSystemRoleModalHtmlData').html(data); 
                    $('#recoverAllDeletedSystemRoleModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#recoverAllDeletedSystemRoleModal').on('show.bs.modal', function () {
            var form_recoverAllDeletedRoles  = document.querySelector("#form_recoverAllDeletedRoles");
            var submit_recoverAllDeletedRolesBtn = document.querySelector("#submit_recoverAllDeletedRolesBtn");
            var cancel_recoverAllDeletedRolesBtn = document.querySelector("#cancel_recoverAllDeletedRolesBtn");
            // disable /enable submit button
            function dis_en_submit_recoverAllDeletedRolesBtn(){
                var has_recoverRolesMarSingle = 0;
                $(".recoverRolesMarSingle").each(function(){
                    if(this.checked){
                        has_recoverRolesMarSingle = 1;
                    }
                });
                if(has_recoverRolesMarSingle != 0){
                    submit_recoverAllDeletedRolesBtn.disabled = false;
                }else{
                    submit_recoverAllDeletedRolesBtn.disabled = true;
                }
            }
            // selection of sanctions for deletion
            $("#recoverRolesMarkAll").change(function(){
                if(this.checked){
                $(".recoverRolesMarSingle").each(function(){
                    this.checked=true;
                })              
                }else{
                $(".recoverRolesMarSingle").each(function(){
                    this.checked=false;
                })              
                }
                dis_en_submit_recoverAllDeletedRolesBtn();
            });
            $(".recoverRolesMarSingle").click(function () {
                if ($(this).is(":checked")){
                var isRecoverRolesMarkAllChecked = 0;
                $(".recoverRolesMarSingle").each(function(){
                    if(!this.checked)
                    isRecoverRolesMarkAllChecked = 1;
                })              
                if(isRecoverRolesMarkAllChecked == 0){ $("#recoverRolesMarkAll").prop("checked", true); }     
                }else {
                $("#recoverRolesMarkAll").prop("checked", false);
                }
                dis_en_submit_recoverAllDeletedRolesBtn();
            });
            // disable cancel and sibmit button on submit
            $(form_recoverAllDeletedRoles).submit(function(){
                cancel_recoverAllDeletedRolesBtn.disabled = true;
                submit_recoverAllDeletedRolesBtn.disabled = true;
                return true;
            });
        });
    </script>
{{-- recover system roles end --}}

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