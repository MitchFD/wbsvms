@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'users_logs'
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
                <a href="{{ route('user_management.users_logs', 'users_logs') }}" class="directory_link">User Management </a> <span class="directory_divider"> / </span> <a href="{{ route('user_management.users_logs', 'users_logs') }}" class="directory_active_link">Users Logs </a>
            </div>
        </div>

        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_title">Users Logs</span>
                            <span class="page_intro_subtitle">This page is an overview of registered users, system roles, and their statuses where you can disable/enable them from the system by toggling the <i class="fa fa-toggle-on" aria-hidden="true"></i> icon. Click the <i class="fa fa-eye" aria-hidden="true"></i> icon to view more information about a specific user.</span>
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/um_users_logs_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr card_ofh shadow-none cb_p25 card_body_bg_gray">
                    <div class="row d-flex justify-content-start">
                        <div class="col-lg-4 col-md-8 col-sm-12">
                            <div class="input-group cust_srchInpt_div">
                                <input id="actLogsFiltr_liveSearch" name="actLogsFiltr_liveSearch" type="text" class="form-control cust_srchUsersInpt_box" placeholder="Search Something..." />
                                <i class="nc-icon nc-zoom-split" aria-hidden="true"></i>    
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="form-group">
                                <select class="form-control cust_selectDropdownBox drpdwn_arrow" id="actLogsFiltr_selectUsers">
                                    <option value="0" selected>All Users</option>
                                    @php
                                        $all_users = App\Models\Users::select('id', 'user_lname', 'user_fname')->get();
                                    @endphp
                                    @if(count($all_users) > 0)
                                        @foreach($all_users->sortBy('id') as $select_user)
                                            <option value="{{$select_user->id}}">{{$select_user->user_fname }} {{ $select_user->user_lname }}</option>
                                        @endforeach
                                    @else
                                        
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="form-group">
                                <select class="form-control cust_selectDropdownBox drpdwn_arrow" id="actLogsFiltr_selectCategories">
                                    <option value="0" selected>All Categories</option>
                                    @php
                                        $all_act_types = App\Models\Useractivites::select('act_type')->groupBy('act_type')->get();
                                    @endphp
                                    @if(count($all_act_types) > 0)
                                        @foreach($all_act_types->sortBy('id') as $select_category)
                                            <option value="{{$select_category->act_type}}">{{ucwords($select_category->act_type) }}</option>
                                        @endforeach
                                    @else
                                        
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <table class="table table-hover cust_table shadow">
                                <thead class="thead_svms_blue">
                                    <tr>
                                        <th class="pl12">~ User</th>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody_svms_white" id="usersActLogs_tbody">
                                    {{-- ajax data table --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <span>Total Data: <span class="font-weight-bold" id="total_data_count"> </span> </span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-end">
                            <a href="#" class="btn btn-success cust_bt_links shadow" role="button"><i class="fa fa-print mr-1" aria-hidden="true"></i> Generate Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modals --}}

@endsection

@push('scripts')
{{-- live search --}}
    <script>
        $(document).ready(function(){
            var actLogsFiltr_liveSearch = null;
            $('#actLogsFiltr_liveSearch').on('keyup', function(){
                actLogsFiltr_liveSearch = $(this).val();
            });
            console.log(actLogsFiltr_liveSearch);
        });
    </script>
{{-- live search end --}}
@endpush