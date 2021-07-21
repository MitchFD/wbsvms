<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>SMVS - System Users Logs History</title>

        <style type="text/css">
            @page {
                margin: 0px;
            }
            body {
                /* margin: 0px; */
                margin: 120px 50px 50px 50px !important;
            }
            * {
                font-family: Arial, Helvetica, sans-serif;
            }
            a {
                color: #fff;
                text-decoration: none;
            }
            /* .txt_right{
                text-align: right !important;
            } */
            .br_1{
                border-right: 1px solid #ddd;
            }
            .bt_1{
                border-top: 1px solid #ddd;
            }

            .font-weight-bold{
                font-weight: bold !important;
            }
            .d-block{
                display: block;
            }

            /* custom table row bg color */
            .tr_bg_DDD{
                background-color: #f2f2f2 !important
            }

            /* custom margins */
            .m_0{
                margin: 0 !important;
            }

            /* costom colors */
            .cg{
                color: rgb(92, 92, 92) !important;
            }

            /* page header */
            #page_header{
                /* background-color: #BD171B; */
                color: #F8E7E7;
                position: fixed;
                top: 10px;
                left: 40px;
                right: 40px;
            }
            .sdca_logo{
                height: 80px;
                width: auto;
            }
            .svms_logo{
                height: 60px;
                width: auto;
            }

            /* content body */
            .content_body{
                /* padding-left: 48px !important;
                padding-right: 48px !important;
                padding-bottom: 50px !important; */
                margin: 0;
                padding: 0 !important;
            }

            /* titles and sub-titles */
            .h5_title{
                margin: 0 !important;
                font-weight: bold !important;
                text-align: center !important;
                text-transform: capitalize !important;
            }
            .h5_subTitle{
                margin: 0 !important;
                font-weight: normal !important;
                text-align: center !important;
                text-transform: capitalize !important;
            }
            .h3_title{
                font-weight: bold !important;
                text-align: center !important;
                text-transform: capitalize !important;
            }
            /* contents information table */
            #contentsInfo_table{
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
                border: 1px solid #ddd;
                font-size: 13px;
            }
            #contentsInfo_table tbody tr td{
                padding: 6px 15px;
            }

            /* notice */
            .notice{
                font-size: 14px;
            }

            /* end text for tables */
            .notice_1{
                font-size: 13px !important;
                font-style: italic !important;
                text-align: center !important;
            }

            .text_justify {
                text-align: justify !important;
            }

            /* contents data table */
            #contentsData_table{
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
                font-size: 13px;
            }
            #contentsData_table{
                border-top-left-radius: 12px !important;
                border-top-right-radius: 12px !important;
                border-bottom-left-radius: 12px !important;
                border-bottom-right-radius: 12px !important;
                overflow: hidden;
            }
            #contentsData_table thead tr th{
                background-color: #2F2E41;
                color: #fff;
                padding: 8px;
                text-align: left;
            }
            #contentsData_table tbody{
                border-left: 1px solid #f2f2f2;
                border-right: 1px solid #f2f2f2;
            }
            #contentsData_table tbody tr td{
                padding: 8px;
                text-align: left;
                font-size: 12px;
                line-height: 16px;
                border-bottom: 1px solid #f2f2f2;
            }
            #contentsData_table tr:nth-child(even){background-color: #f2f2f2;}
            .row_count{
                font-weight: bold !important;
                text-align: left !important;
            }

            /* page footer */
            #page_footer{
                /* position: absolute; 
                bottom: 0; */
                position: fixed !important;
                bottom: 42px;
                left: 0;
                right: 0;
                background-color: #BD171B;
                color: #F8E7E7;
                padding: 10px 48px !important;
                font-size: 13px;
            }
            #page_footer ._info{
                width: 80%;
                text-align: left;
            }
            #page_footer ._page{
                width: 20%;
                text-align: right;
            }

            /* page number */
            ._current_page:before {
                content: counter(page);
            } 
        </style>

    </head>
    <body>
        <div id="page_header">
            <table width="100%">
                <tr>
                    <td>
                        <img class="sdca_logo" src="{{ public_path('storage/svms/sdca_images/sdca_logo.jpg') }}" alt="St. Dominic College of Asia Logo">
                    </td>
                    <td>
                        <img class="svms_logo" src="{{ public_path('storage/svms/logos/svms_logo_text.png') }}" alt="SVMS Logo">
                    </td>
                </tr>
            </table>
        </div>

        <div id="page_footer">
            <table width="100%">
                <tr>
                    <td class="_info">
                        Student Violation Management System: System Users Logs History
                    </td>
                    <td class="_page">
                        {{-- Page <span class="_current_page"></span> of <span class="_total_page"></span>  --}}
                        <script type="text/php">
                            if (isset($pdf)) {
                                $x = 511;
                                $y = 821;
                                $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
                                $font = null;
                                $size = 9;
                                $color = array(248,231,231);
                                $word_space = 0.0;  //  default
                                $char_space = 0.0;  //  default
                                $angle = 0.0;   //  default
                                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
                            }
                        </script>
                    </td>
                </tr>
            </table>
        </div>

        <div class="content_body">
            <h5 class="h5_title">DEPARTMENT OF STUDENT AFFAIRS AND SERVICES</h5>
            <h5 class="h5_subTitle">STUDENT DISCIPLINE UNIT</h5>
            <h3 class="h3_title">SYSTEM USERS LOGS REPORT</h3>

            <br>
            <br>

            <table id="contentsInfo_table">
                <tbody>
                    <tr>
                        <td class="txt_right">
                            <span class="font-weight-bold">Printed By: </span>
                            {{ $query_respo_user->user_fname }} {{ $query_respo_user->user_lname }} 
                            <span class="cg"> (System {{ ucwords($query_respo_user->user_role) }})</span>
                        </td>
                        <td class="txt_right">
                            <span class="font-weight-bold">Date Printed: </span> 
                            {{ date('F d, Y', strtotime($now_timestamp))}} 
                            <span class="cg">{{ date('(l - g:i A)', strtotime($now_timestamp))}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="txt_right font-weight-bold">Signature:</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <br>

            @php
                // user type
                if($logs_userTypes != 0 OR !empty($logs_userTypes)){
                    $tolower_uType = Str::lower($logs_userTypes);
                    if($tolower_uType === 'employee'){
                        $txt_filteredUserType = 'Employee Users';
                    }elseif($tolower_uType === 'student'){
                        $txt_filteredUserType = 'Student Users';
                    }else{
                        $txt_filteredUserType = 'All Users (Employee and Student)';
                    }
                }else{
                    $txt_filteredUserType = 'All Users (Employee and Student)';
                }
                // user role
                if($logs_userRoles != 0 OR !empty($logs_userRoles)){
                    $txt_filteredUserRole = ''.ucwords($logs_userRoles).'';
                }else{
                    $txt_filteredUserRole = 'All Roles';
                }
                // user
                if($logs_users != 0 OR !empty($logs_users)){
                    $sel_user_info = App\Models\Users::select('id', 'user_lname', 'user_fname')->where('id', '=', $logs_users)->first();
                    $sel_Fname = $sel_user_info->user_fname;
                    $sel_Lname = $sel_user_info->user_lname;
                    $txt_filteredUser = ''.$sel_Fname . ' ' . $sel_Lname.'';
                }else{
                    $txt_filteredUser = 'All Users';
                }
                // category
                if($logs_category != 0 OR !empty($logs_category)){
                    $txt_filteredCategory = ''.ucwords($logs_category) . ' Histories';
                }else{
                    $txt_filteredCategory = 'All Logs Category';
                }
                // order by 
                if($logs_orderBy != 0 OR !empty($logs_orderBy)){
                    if($logs_orderBy == 1){
                        $orderBy_filterVal = 'Employee ID';
                    }else{
                        $orderBy_filterVal = 'Date Recorded';
                    }
                }else{
                    $orderBy_filterVal = 'Date Recorded';
                }
                // order by range
                if(!empty($logs_orderByRange) OR $logs_orderByRange != 0){
                    if($logs_orderByRange === 'asc'){
                        $orderByRange_filterVal = '(Ascending)';
                    }else{
                        $orderByRange_filterVal = '(Descending)';
                    }
                }else{
                    $orderByRange_filterVal = '(Descending)';
                }
                // Date Range
                if($logs_rangefrom != 0 OR !empty($logs_rangefrom) OR $logs_rangeTo != 0 OR !empty($logs_rangeTo)){
                    $format_logsDateFrom = date('F d, Y (D ~ g:i A)', strtotime($logs_rangefrom));
                    $format_logsDateTo = date('F d, Y (D ~ g:i A)', strtotime($logs_rangeTo));
                    $txt_DateRange = 'From ' . $format_logsDateFrom . ' to ' . $format_logsDateTo.'';
                }else{
                    $txt_DateRange = 'All Recorded Logs';
                }

                // from query recorded logs
                $total_filter_user_logs_table = count($filter_user_logs_table);
                if($total_filter_user_logs_table > 0){
                    if($total_filter_user_logs_table > 1){
                        $tfAL_s = 's';
                    }else{
                        $tfAL_s = '';
                    }
                }else{
                    $tfAL_s = '';
                }
            @endphp

            <table id="contentsInfo_table">
                <tbody>
                    <tr class="tr_bg_DDD">
                        <td class="b_1">
                            <span class="font-weight-bold">Applied Filters</span>
                        </td>
                        <td class="b_1">
                            <span class="font-weight-bold">Applied Filters (Violations): </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="br_1" style="padding-top: 15px !important;">
                            <span class="font-weight-bold">User Type: </span> {{ $txt_filteredUserType }}
                        </td>
                        <td class="br_1" style="padding-top: 15px !important;">
                            <span class="font-weight-bold">Logs Category: </span> {{ $txt_filteredCategory }}
                        </td>
                    </tr>
                    <tr>
                        <td class="br_1">
                            <span class="font-weight-bold">User Role: </span> {{ $txt_filteredUserRole }}
                        </td>
                        <td class="br_1">
                            <span class="font-weight-bold">Date Range: </span> {{ $txt_DateRange }}
                        </td>
                    </tr>
                    <tr>
                        <td class="br_1" style="padding-bottom: 15px !important;">
                            <span class="font-weight-bold">User: </span> {{ $txt_filteredUser }}
                        </td>
                        <td class="br_1" style="padding-bottom: 15px !important;">
                            <span class="font-weight-bold">Order By: </span> {{ $orderBy_filterVal }} {{$orderByRange_filterVal }}
                        </td>
                    </tr>
                    @if($logs_search != '' OR !empty($logs_search))
                        <tr class="tr_bg_DDD">
                            <td class="b_1">
                                <span class="font-weight-bold">Total Row{{$tfAL_s}}: {{ $total_filter_user_logs_table }}</span>
                            </td>
                            <td class="br_1">
                                <span class="font-weight-bold">Search Filter: </span> <em> {{ $logs_search}}... </em>
                            </td>
                        </tr>
                    @else
                        <tr class="tr_bg_DDD">
                            <td class="b_1" colspan="2">
                                <span class="font-weight-bold">Total Row{{$tfAL_s}}: {{ $total_filter_user_logs_table }}</span>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <br>
           
            <p class="notice text_justify">Below are System Users Log Histories retrieved from the Student Violation Management System generated with custom filters as shown above.</p>

            <br>

            <table id="contentsData_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Date Recorded</th>
                        <th>Category</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @if($total_filter_user_logs_table > 0)
                        @php
                            $rowCount = 0;
                        @endphp
                        @foreach($filter_user_logs_table as $this_log)
                        @php
                            $rowCount++;
                        @endphp
                        <tr>
                            <td class="row_count" width="3%">
                                {{$rowCount}}.
                            </td>
                            <td width="30%">
                                <p class="m_0">{{ $this_log->act_respo_users_fname }} {{ $this_log->act_respo_users_lname }}</p>
                                <p class="m_0 cg">#{{$this_log->user_sdca_id }} | {{ ucwords($this_log->user_role) }}</p>
                            </td>
                            <td width="18%">
                                <p class="m_0">{{date('F d, Y', strtotime($this_log->created_at)) }} </p>
                                <p class="m_0 cg"> {{ date('(D - g:i A)', strtotime($this_log->created_at))}} </p>
                            </td>
                            <td width="7%">{{ucwords($this_log->act_type)}}</td>
                            <td width="42%">{{$this_log->act_details}}</td>
                        </tr>
                        @endforeach
                    @else
                        
                    @endif
                </tbody>
            </table>

            {{-- <br> --}}
            
            <p class="notice_1">-- end of table <span class="cg"> (Users Logs Table) </span> --</p>
            
            <br>

            @if ($total_filter_user_logs_table > 20)
                <table id="contentsInfo_table">
                    <tbody>
                        <tr class="tr_bg_DDD">
                            <td class="b_1">
                                <span class="font-weight-bold">Applied Filters</span>
                            </td>
                            <td class="b_1">
                                <span class="font-weight-bold">Applied Filters (Violations): </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="br_1" style="padding-top: 15px !important;">
                                <span class="font-weight-bold">User Type: </span> {{ $txt_filteredUserType }}
                            </td>
                            <td class="br_1" style="padding-top: 15px !important;">
                                <span class="font-weight-bold">Logs Category: </span> {{ $txt_filteredCategory }}
                            </td>
                        </tr>
                        <tr>
                            <td class="br_1">
                                <span class="font-weight-bold">User Role: </span> {{ $txt_filteredUserRole }}
                            </td>
                            <td class="br_1">
                                <span class="font-weight-bold">Date Range: </span> {{ $txt_DateRange }}
                            </td>
                        </tr>
                        <tr>
                            <td class="br_1" style="padding-bottom: 15px !important;">
                                <span class="font-weight-bold">User: </span> {{ $txt_filteredUser }}
                            </td>
                            <td class="br_1" style="padding-bottom: 15px !important;">
                                <span class="font-weight-bold">Order By: </span> {{ $orderBy_filterVal }} {{$orderByRange_filterVal }}
                            </td>
                        </tr>
                        @if($logs_search != '' OR !empty($logs_search))
                            <tr class="tr_bg_DDD">
                                <td class="b_1">
                                    <span class="font-weight-bold">Total Row{{$tfAL_s}}: {{ $total_filter_user_logs_table }}</span>
                                </td>
                                <td class="br_1">
                                    <span class="font-weight-bold">Search Filter: </span> <em> {{ $logs_search}}... </em>
                                </td>
                            </tr>
                        @else
                            <tr class="tr_bg_DDD">
                                <td class="b_1" colspan="2">
                                    <span class="font-weight-bold">Total Row{{$tfAL_s}}: {{ $total_filter_user_logs_table }}</span>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                
                <br>
                
                <table id="contentsInfo_table">
                    <tbody>
                        <tr>
                            <td class="txt_right">
                                <span class="font-weight-bold">Printed By: </span>
                                {{ $query_respo_user->user_fname }} {{ $query_respo_user->user_lname }} 
                                <span class="cg"> (System {{ ucwords($query_respo_user->user_role) }})</span>
                            </td>
                            <td class="txt_right">
                                <span class="font-weight-bold">Date Printed: </span> 
                                {{ date('F d, Y', strtotime($now_timestamp))}} 
                                <span class="cg">{{ date('(l - g:i A)', strtotime($now_timestamp))}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="txt_right font-weight-bold">Signature:</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <br>
            @endif

        </div>
    </body>
</html>