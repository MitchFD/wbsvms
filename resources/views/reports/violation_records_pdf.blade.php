<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Violation Records</title>

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
                border-bottom: 1px solid #ddd;
            }

            /* costom colors */
            .cg{
                color: rgb(92, 92, 92);
            }

            /* custom font-style */
            .font-weight-bold{
                font-weight: bold !important;
            }

            /* display class */
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

            /* text colors */
            .text_svms_red{
                color: #BD171B !important;
            }
            .text_svms_green{
                color: #6bd098 !important;
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
            /* #contentsData_table tr:last-child{
                border-bottom-left-radius: 12px !important;
                border-bottom-right-radius: 12px !important;
            } */

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
                        Student Violation Management System: Violation Records
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
            <h3 class="h3_title">VIOLATION RECORDS REPORT</h3>

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
                            <span class="cg">{{ date('(D - g:i A)', strtotime($now_timestamp))}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="txt_right font-weight-bold">Signature:</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <br>

            <table id="contentsInfo_table">
                <tbody>
                    <tr class="tr_bg_DDD">
                        <td class="b_1">
                            <span class="font-weight-bold">Applied Filters (Student): </span>
                        </td>
                        <td class="b_1">
                            <span class="font-weight-bold">Applied Filters (Violations): </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="br_1 pt_15" style="padding-top: 15px !important;">
                            <span class="font-weight-bold">Schools: </span> {{ $txt_SchoolNames }}
                        </td>
                        <td class="br_1 pt_15" style="padding-top: 15px !important;">
                            <span class="font-weight-bold">Violation Status: </span> {{ $txt_ViolationStatus }}
                        </td>
                    </tr>
                    <tr>
                        <td class="br_1">
                            <span class="font-weight-bold">Programs: </span> {{ $txt_Programs }}
                        </td>
                        <td class="br_1">
                            <span class="font-weight-bold">Date Range: </span> {{ $txt_DateRange }}
                        </td>
                    </tr>
                    <tr>
                        <td class="br_1">
                            <span class="font-weight-bold">Year Levels: </span> {{ $txt_YearLevels }}
                        </td>
                        <td>

                        </td>
                    </tr>
                    <tr>
                        <td class="br_1">
                            <span class="font-weight-bold">Gender: </span> {{ $txt_Gender }}
                        </td>
                        @if($filter_SearchInput != '' OR !empty($filter_SearchInput))
                            <td class="br_1">
                                <span class="font-weight-bold">Search Filter: </span>
                            </td>
                        @else
                            <td>

                            </td>
                        @endif
                    </tr>
                    <tr>
                        <td class="br_1 pb_10" style="padding-bottom: 15px !important;">
                            <span class="font-weight-bold">Age Range: </span> {{ $txt_AgeRange }}
                        </td>
                        @if($filter_SearchInput != '' OR !empty($filter_SearchInput))
                            <td class="br_1" style="padding-bottom: 15px !important;">
                                <em>{{ $filter_SearchInput }}...</em>
                            </td>
                        @else
                            <td style="padding-bottom: 15px !important;">

                            </td>
                        @endif
                    </tr>
                    <tr class="tr_bg_DDD">
                        <td class="b_1" colspan="2">
                            <span class="font-weight-bold">Total Rows: </span> {{ $txt_TotalQueryRecords }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <br>

            <p class="notice">Below are the Recorded Violations retrieved from the Student Violation Management System.</p>

            <br>
            
            <table id="contentsData_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Violation</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($query_violation_records))
                        @php
                            $rowCount = 0;
                        @endphp
                        @foreach($query_violation_records as $violator)
                            @php
                                // table row count
                                $rowCount++;
                                // pluras (Offense)
                                if($violator->offense_count > 1){
                                    $oc_s = 's';
                                }else{
                                    $oc_s = '';
                                }
                                // violation status
                                if($violator->violation_status == 'cleared'){
                                    $class_ViolationStatus = 'text_svms_green';
                                    $txt_ViolationStatus = ' ~ Cleared';
                                }else{
                                    $class_ViolationStatus = 'text_svms_red';
                                    $txt_ViolationStatus = ' ~ Not Cleared';
                                }
                                // merge offenses
                                $to_array_allOffenses = array();
                                if(!is_null($violator->minor_off) OR !empty($violator->minor_off)){
                                    foreach(json_decode($violator->minor_off, true) as $this_mo){
                                        array_push($to_array_allOffenses, $this_mo);
                                    }
                                }
                                if(!is_null($violator->less_serious_off) OR !empty($violator->less_serious_off)){
                                    foreach(json_decode($violator->less_serious_off, true) as $this_lso){
                                        array_push($to_array_allOffenses, $this_lso);
                                    }
                                }
                                if(!is_null($violator->other_off) OR !empty($violator->other_off)){
                                    if(!in_array(null, json_decode($violator->other_off, true))){
                                        foreach(json_decode($violator->other_off, true) as $this_oo){
                                            array_push($to_array_allOffenses, $this_oo);
                                        }
                                    }
                                }
                                $toJson = json_encode($to_array_allOffenses);
                                $count_allOffenses = json_encode(count($to_array_allOffenses));
                                $o_x = 0;
                            @endphp
                            <tr>
                                <td class="row_count" width="5%">
                                    {{$rowCount}}.
                                </td>
                                <td width="45%">
                                    <p class="m_0">{{$violator->First_Name }} {{ $violator->Middle_Name }} {{ $violator->Last_Name }}</p>
                                    <p class="m_0 cg">{{$violator->Student_Number }} | {{ $violator->School_Name }} | {{ $violator->Course }} | {{ $violator->YearLevel}}-Y | {{ $violator->Gender }}</p>
                                </td>
                                <td width="50%">
                                    <p class="m_0">{{date('F d, Y', strtotime($violator->recorded_at)) }} <span class="cg"> {{ date('(D - g:i A)', strtotime($violator->recorded_at))}} </span> <span class="{{$class_ViolationStatus}}"> {{ $txt_ViolationStatus }}</span> </p>
                                    <p class="m_0">{{$violator->offense_count }} Offense{{$oc_s}}: 
                                        <span class="cg">
                                            @foreach (json_decode($toJson, true) as $all_offense)
                                                @php
                                                    $o_x++;
                                                    $txt_offense = ''.$o_x.'. ' . $all_offense.'';
                                                @endphp
                                                {{-- {{$o_x}}. {{ $all_offense}} --}}
                                                @php
                                                    if($o_x==$count_allOffenses) {
                                                        $txt_offense = $txt_offense.'. ';
                                                    }else{
                                                        $txt_offense = $txt_offense.'; ';
                                                    }
                                                @endphp
                                                {{ $txt_offense }}
                                            @endforeach
                                        </span>
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        
                    @endif
                </tbody>
            </table>

            <br>
            <br>
            <br>

            <table id="contentsInfo_table">
                <tbody>
                    <tr class="tr_bg_DDD">
                        <td class="b_1">
                            <span class="font-weight-bold">Applied Filters (Student): </span>
                        </td>
                        <td class="b_1">
                            <span class="font-weight-bold">Applied Filters (Violations): </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="br_1 pt_15" style="padding-top: 15px !important;">
                            <span class="font-weight-bold">Schools: </span> {{ $txt_SchoolNames }}
                        </td>
                        <td class="br_1 pt_15" style="padding-top: 15px !important;">
                            <span class="font-weight-bold">Violation Status: </span> {{ $txt_ViolationStatus }}
                        </td>
                    </tr>
                    <tr>
                        <td class="br_1">
                            <span class="font-weight-bold">Programs: </span> {{ $txt_Programs }}
                        </td>
                        <td class="br_1">
                            <span class="font-weight-bold">Date Range: </span> {{ $txt_DateRange }}
                        </td>
                    </tr>
                    <tr>
                        <td class="br_1">
                            <span class="font-weight-bold">Year Levels: </span> {{ $txt_YearLevels }}
                        </td>
                        <td>

                        </td>
                    </tr>
                    <tr>
                        <td class="br_1">
                            <span class="font-weight-bold">Gender: </span> {{ $txt_Gender }}
                        </td>
                        @if($filter_SearchInput != '' OR !empty($filter_SearchInput))
                            <td class="br_1">
                                <span class="font-weight-bold">Search Filter: </span>
                            </td>
                        @else
                            <td>

                            </td>
                        @endif
                    </tr>
                    <tr>
                        <td class="br_1 pb_10" style="padding-bottom: 15px !important;">
                            <span class="font-weight-bold">Age Range: </span> {{ $txt_AgeRange }}
                        </td>
                        @if($filter_SearchInput != '' OR !empty($filter_SearchInput))
                            <td class="br_1" style="padding-bottom: 15px !important;">
                                <em>{{ $filter_SearchInput }}...</em>
                            </td>
                        @else
                            <td style="padding-bottom: 15px !important;">

                            </td>
                        @endif
                    </tr>
                    <tr class="tr_bg_DDD">
                        <td class="b_1" colspan="2">
                            <span class="font-weight-bold">Total Rows: </span> {{ $txt_TotalQueryRecords }}
                        </td>
                    </tr>
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
                            <span class="cg">{{ date('(D - g:i A)', strtotime($now_timestamp))}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="txt_right font-weight-bold">Signature:</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <br>

        </div>
    </body>
</html>