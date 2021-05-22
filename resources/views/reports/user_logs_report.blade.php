<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>SMVS - {{ $query_selected_user->user_fname }} {{ $query_selected_user->user_lname}}'s Logs History</title>

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

            /* custom borders */
            .b_0{
                border: 0 !important;
            }
            .b_1{
                border: 1px solid #ddd;
            }
            .br_1{
                border-right: 1px solid #ddd;
            }
            .bt_1{
                border-top: 1px solid #ddd;
            }
            .bb_1{
                border-bottom: 1px solid #ddd !important;
            }
            .cg{
                color: rgb(92, 92, 92);
            }

            /* custom paddings */
            .p_0{
                padding: 0 !important;
            }
            .p_x0y2{
                padding: 2px 0px !important;
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
                        Student Violation Management System: {{ $query_selected_user->user_fname }} {{ $query_selected_user->user_lname}}'s Logs History
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
            <h3 class="h3_title">SYSTEM USER'S LOGS REPORT</h3>

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

            @if (count($queryAll_activityLogs) > 0)
                <br>

                <p class="notice text_justify">Below are {{ $query_selected_user->user_fname }} {{ $query_selected_user->user_lname}}'s Information and Log Histories retrieved from the Student Violation Management System generated with custom filters also shown below.</p>
                
                <br>

                <table id="contentsInfo_table">
                    <tbody>
                        {{-- display selected user's information --}}
                        {{-- if user_type == employee --}}
                        {{-- if user_type == student --}}
                        <tr class="tr_bg_DDD">
                            <td class="">
                                <span class="font-weight-bold">User's Information: </span>
                            </td>
                            <td class="">
                                {{-- empty --}}
                            </td>
                        </tr>
                        <tr>
                            <td class="br_1" style="padding-top: 15px !important;">
                                <span class="font-weight-bold">Employee ID: </span> 
                            </td>
                            <td class="br_1" style="padding-top: 15px !important;">
                                <span class="font-weight-bold">User Type: </span> 
                            </td>
                        </tr>
                        <tr>
                            <td class="br_1">
                                <span class="font-weight-bold">Job Description: </span> 
                            </td>
                            <td class="br_1">
                                <span class="font-weight-bold">User Role: </span> 
                            </td>
                        </tr>
                        <tr>
                            <td class="br_1">
                                <span class="font-weight-bold">Department: </span> 
                            </td>
                            <td class="br_1">
                                <span class="font-weight-bold">User Status: </span> 
                            </td>
                        </tr>
                        <tr>
                            <td class="br_1 bb_1" style="padding-bottom: 15px !important;">
                                <span class="font-weight-bold">Gender: </span> 
                            </td>
                            <td class="br_1 bb_1" style="padding-bottom: 15px !important;">
                                {{-- empty --}}
                            </td>
                        </tr>
                        <tr>
                            <td class="b_0 p_x0y2"></td>
                            <td class="b_0 p_x0y2"></td>
                        </tr>
                        <tr class="tr_bg_DDD">
                            <td class="">
                                <span class="font-weight-bold">Applied Filters: </span>
                            </td>
                            <td class="">
                                {{-- empty --}}
                            </td>
                        </tr>
                        <tr>
                            <td class="br_1" style="padding-top: 15px !important;">
                                <span class="font-weight-bold">Date Range: </span> 
                            </td>
                            <td class="br_1" style="padding-top: 15px !important;">
                                <span class="font-weight-bold">Log Category: </span> 
                            </td>
                        </tr>
                        <tr>
                            <td class="br_1" style="padding-bottom: 15px !important;">
                                <span class="font-weight-bold">Total Data Found: </span> 
                            </td>
                            <td class="br_1" style="padding-bottom: 15px !important;">
                                <span class="font-weight-bold">Order: </span> 
                            </td>
                        </tr>

                        {{-- display user's activity logs to table --}}
                    </tbody>
                </table>

                <br>

                <br>
            
            <table id="contentsData_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($queryAll_activityLogs))
                        @php
                            $rowCount = 0;
                        @endphp
                        @foreach($queryAll_activityLogs as $this_log)
                        @php
                            $rowCount++;
                        @endphp
                        <tr>
                            <td class="row_count" width="5%">
                                {{$rowCount}}.
                            </td>
                            <td width="30%">
                                {{date('F d, Y', strtotime($this_log->created_at)) }} 
                                <span class="cg"> {{ date('(D - g:i A)', strtotime($this_log->created_at))}} </span>
                            </td>
                            <td width="20%">{{ucwords($this_log->act_type)}}</td>
                            <td width="45%">{{$this_log->act_details}}</td>
                        </tr>
                        @endforeach
                    @else
                        
                    @endif
                    <tr>
                </tbody>
            </table>

            <br>

            <p class="notice_1">-- end of table <span class="cg"> ({{ $query_selected_user->user_fname }} {{ $query_selected_user->user_lname}}'s Logs History Table) </span> --</p>

            <br>
            <br>
            
            @else
                <br>

                <p class="notice text_justify">There are No Log Histories Found for {{ $query_selected_user->user_fname }} {{ $query_selected_user->user_lname}}!.</p>
                
                <br>
            @endif

        </div>
    </body>
</html>