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