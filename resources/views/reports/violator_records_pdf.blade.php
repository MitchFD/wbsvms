<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{$query_selViolator_info->First_Name }} {{ $query_selViolator_info->Middle_Name }} {{ $query_selViolator_info->Last_Name}}'s Violation Records</title>
        
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

            /* miscellaneous symbols  https://en.wikipedia.org/wiki/Miscellaneous_Symbols */
            .ms {
                display: inline;
                font-style: normal;
                font-variant: normal;
                font-weight: normal;
                font-size: 1.125rem;
                line-height: .7 !important;
                font-family: DejaVu Sans, sans-serif;
            }
            .ms-check-square-o:before{
                font-family: DejaVu Sans, sans-serif;
                content: "\2611";
                font-size:1.125rem;
            }
            .ms-square-o:before{
                font-family: DejaVu Sans, sans-serif;
                content: "\2610";
                font-size:1.125rem;
            }

            /* custom borders */
            .b_1{
                border: 1px solid #ddd;
            }
            .b_0{
                border: 0 !important;
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
            .bb_0{
                border-bottom: 0 !important;
            }
            .br_0 {
                border-right: 0 !important;
            }
            .bl_0 {
                border-left: 0 !important;
            }

            /* custom paddings */
            .p_0{
                padding: 0 !important;
            }
            .p_x0y2{
                padding: 2px 0px !important;
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
                background-color: #f2f2f2 !important;
            }
            .tr_bg_red{
                background-color: #F2D0D1 !important;
            }
            .tr_bg_red1{
                background-color: #FBF1F1 !important;
            }
            .tr_bg_red2{
                background-color: #F8E7E7 !important;
            }
            .tr_bg_grn{
                background-color: #E1F6EA !important;
            }
            .tr_bg_grn1{
                background-color: #F0FAF5 !important;
            }
            .tr_bg_grn2{
                background-color: #E9F8F0 !important;
            }

            /* custom margins */
            .m_0{
                margin: 0 !important;
            }
            .mb_2{
                margin-bottom: 2px !important;
            }

            /* alignment classes */
            .va_top{
                vertical-align: text-top !important;
            }

            /* text colors */
            .text_svms_red{
                color: #BD171B !important;
            }
            .text_svms_green{
                color: #6bd098 !important;
            }

            .txtRed_title{
                color: #BD171B !important;
                font-weight: bold !important;
            }
            .txtRed_subTitle{
                color: #BD171B !important;
                font-weight: normal !important;
            }
            .txtGrn_title{
                color: #47A471 !important;
                font-weight: bold !important;
            }
            .txtGrn_subTitle{
                color: #47A471 !important;
                font-weight: normal !important;
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
            .h4_title{
                font-weight: bold !important;
                text-align: center !important;
                text-transform: capitalize !important;
            }
            .h3_title{
                font-weight: bold !important;
                text-align: center !important;
                text-transform: capitalize !important;
            }
            .h2_title{
                font-weight: bold !important;
                text-align: center !important;
                text-transform: capitalize !important;
            }
            /* contents information table */
            #contentsInfo_table{
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
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
                        Student Violation Management System: Violator's Records
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
            <br>
            <h3 class="h3_title m_0">VIOLATOR'S RECORDS REPORT</h3>
            <h5 class="h5_subTitle">STUDENT VIOLATION MANAGEMENT SYSTEM</h5>

            <br>
            <br>

            <table id="contentsInfo_table" class="b_1">
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
                    // counts
                    // uncleared offenses count
                    if($countAll_Uncleared_offenses > 0){
                        $UC_offCount = $countAll_Uncleared_offenses;
                        if($countAll_Uncleared_offenses > 1){
                            $UC_s = 's';
                        }else{
                            $UC_s = '';
                        }
                    }else{
                        $UC_offCount = 0 ;
                        $UC_s = '';
                    }
                    // cleared offenses count
                    if($countAll_Cleared_offenses > 0){
                        $C_offCount = $countAll_Cleared_offenses;
                        if($countAll_Cleared_offenses > 1){
                            $C_s = 's';
                        }else{
                            $C_s = '';
                        }
                    }else{
                        $C_offCount = 0 ;
                        $C_s = '';
                    }
                    // total offenses count
                    if($countTotal_offenses > 0){
                        $T_offCount = $countTotal_offenses;
                        if($countTotal_offenses > 1){
                            $TO_s = 's';
                        }else{
                            $TO_s = '';
                        }
                    }else{
                        $T_offCount = 0 ;
                        $TO_s = '';
                    }

                    // sacntions
                    // count al corresponding sanctions
                    $countAll_Sanctions = App\Models\Sanctions::where('stud_num', '=', $query_selViolator_info->Student_Number)->count();
                    // count all completed sanctions
                    $countAll_CompletedSanctions = App\Models\Sanctions::where('stud_num', '=', $query_selViolator_info->Student_Number)
                                                        ->where('sanct_status', '=', 'completed')
                                                        ->count();
                    // count all not completed sanctions
                    $countAll_NotCompletedSanctions = App\Models\Sanctions::where('stud_num', '=', $query_selViolator_info->Student_Number)
                                                        ->where('sanct_status', '!=', 'completed')
                                                        ->count();
                    // plurals
                    if($countAll_Sanctions > 0){
                        if($countAll_Sanctions > 1){
                            $TS_s = 's';
                        }else{
                            $TS_s = '';
                        }
                    }else{
                        $TS_s = '';
                    }
                    if($countAll_CompletedSanctions > 0){
                        if($countAll_CompletedSanctions > 1){
                            $CS_s = 's';
                        }else{
                            $CS_s = '';
                        }
                    }else{
                        $CS_s = '';
                    }
                    if($countAll_NotCompletedSanctions > 0){
                        if($countAll_NotCompletedSanctions > 1){
                            $NCS_s = 's';
                        }else{
                            $NCS_s = '';
                        }
                    }else{
                        $NCS_s = '';
                    }
                @endphp  

            <table id="contentsInfo_table" class="b_1">   
                <tbody>
                    <tr class="tr_bg_DDD">
                        <td colspan="2"><span class="font-weight-bold">Violator's Information: </span></td>
                    </tr>
                    <tr>
                        <td class="bb_1"><span class="font-weight-bold">Name: </span> {{ $query_selViolator_info->First_Name }} {{ $query_selViolator_info->Middle_Name }} {{ $query_selViolator_info->Last_Name}}</td>
                        <td class="bb_1"><span class="font-weight-bold">Gender: </span> {{ $query_selViolator_info->Gender }}</td>
                    </tr>
                    <tr>
                        <td class="bb_1"><span class="font-weight-bold">Student Number: </span> {{ $query_selViolator_info->Student_Number }}</td>
                        <td class="bb_1"><span class="font-weight-bold">Age: </span> {{ $query_selViolator_info->Age }}</td>
                    </tr>
                    <tr>
                        <td class="bb_1"><span class="font-weight-bold">School: </span> {{ $query_selViolator_info->School_Name }}</td>
                        <td class="bb_1"><span class="font-weight-bold">Year Level: </span> {{ $query_selViolator_info->YearLevel }}</td>
                    </tr>
                    <tr>
                        <td class="bb_1"><span class="font-weight-bold">Program: </span> {{ $query_selViolator_info->Course }}</td>
                        <td class="bb_1"></td>
                    </tr>
                    <tr>
                        <td class="b_0 p_x0y2"></td>
                        <td class="b_0 p_x0y2"></td>
                    </tr>
                    <tr class="tr_bg_DDD">
                        <td><span class="font-weight-bold">Offense Details: </span></td>
                        <td><span class="font-weight-bold">Sanctions Details: </span></td>
                    </tr>
                    <tr>
                        <td class="bb_1"><span class="font-weight-bold">Cleared Offense{{$C_s}}: </span> {{ $countAll_Cleared_offenses}}</td>
                        <td class="bb_1"><span class="font-weight-bold">Completed Sanction{{$CS_s}}: </span> {{ $countAll_CompletedSanctions}}</td>
                    </tr>
                    <tr>
                        <td class="bb_1"><span class="font-weight-bold">Uncleared Offense{{$UC_s}}: </span> {{ $UC_offCount}}</td>
                        <td class="bb_1"><span class="font-weight-bold">Not Completed Sanction{{$NCS_s}}: </span> {{ $countAll_NotCompletedSanctions}}</td>
                    </tr>
                    <tr>
                        <td class="bb_1"><span class="font-weight-bold">Total Offense{{$TO_s}}: {{ $countTotal_offenses}}</span></td>
                        <td class="bb_1"><span class="font-weight-bold">Total Sanction{{$TS_s}}: {{ $countAll_Sanctions}}</span></td>
                    </tr>
                </tbody>
            </table>

            <br>

            <p class="notice">Below are {{ $query_selViolator_info->First_Name }} {{ $query_selViolator_info->Middle_Name }} {{ $query_selViolator_info->Last_Name}}'s the Recorded Violations and it's Corresponding Sanctions retrieved from the Student Violation Management System.</p>

            <br>
            
            <table id="contentsInfo_table">
                <tbody>
                    @if(count($query_selViolator_Offenses) > 0)
                        @foreach($query_selViolator_Offenses as $violator_Offense)
                            @php
                                // violatins status class
                                if($violator_Offense->violation_status === 'cleared'){
                                    $tr_bg         = 'tr_bg_grn';
                                    $tr_bg1        = 'tr_bg_grn1';
                                    $tr_bg2        = 'tr_bg_grn2';
                                    $txt_title     = 'txtGrn_title';
                                    $txt_subTitle  = 'txtGrn_subTitle';
                                    $txt_clearedAt = ' ('.date('F d, Y ~ D - g:i A', strtotime($violator_Offense->cleared_at)).') ';
                                }else{
                                    $tr_bg         = 'tr_bg_red';
                                    $tr_bg1        = 'tr_bg_red1';
                                    $tr_bg2        = 'tr_bg_red2';
                                    $txt_title     = 'txtRed_title';
                                    $txt_subTitle  = 'txtRed_subTitle';
                                    $txt_clearedAt = '';
                                }

                                // sanctions status class
                                if($violator_Offense->has_sanction == 1){
                                    $s_tr_bg             = 'tr_bg_grn';
                                    $s_tr_bg1            = 'tr_bg_grn1';
                                    $s_tr_bg2            = 'tr_bg_grn2';
                                    $s_txt_title         = 'txtGrn_title';
                                    $s_txt_subTitle      = 'txtGrn_title';
                                    $s_txt_labelTitle    = 'Sanction Status:';
                                    // count al corresponding sanctions
                                    $countAll_CorrSanctions = App\Models\Sanctions::where('stud_num', '=', $query_selViolator_info->Student_Number)
                                                                        ->where('for_viola_id', '=', $violator_Offense->viola_id)
                                                                        ->offset(0)
                                                                        ->limit($violator_Offense->has_sanct_count)
                                                                        ->count();
                                    // count all completed sanctions
                                    $countAll_CompletedCorrSanctions = App\Models\Sanctions::where('stud_num', '=', $query_selViolator_info->Student_Number)
                                                                        ->where('for_viola_id', '=', $violator_Offense->viola_id)
                                                                        ->where('sanct_status', '=', 'completed')
                                                                        ->offset(0)
                                                                        ->limit($violator_Offense->has_sanct_count)
                                                                        ->count();
                                    if($countAll_CorrSanctions == $countAll_CompletedCorrSanctions){
                                        $s_txt_labelsubTitle = 'Completed';
                                    }else{
                                        $s_txt_labelsubTitle = 'Not Completed';
                                    }
                                }else{
                                    $s_tr_bg             = 'tr_bg_red';
                                    $s_tr_bg1            = 'tr_bg_red1';
                                    $s_tr_bg2            = 'tr_bg_red2';
                                    $s_txt_title         = 'txtRed_title';
                                    $s_txt_subTitle      = 'txtRed_subTitle';
                                    $s_txt_labelTitle    = '';
                                    $s_txt_labelsubTitle = 'No Corresponding Sanctions Found...';
                                }
                            @endphp
                            <tr>
                                <td class="{{ $tr_bg }} {{ $txt_title }}">Date Committed: {{ date('F d, Y (D - g:i A)', strtotime($violator_Offense->recorded_at))}}</td>
                                <td class="{{ $s_tr_bg }} {{ $s_txt_title }}"></td>
                            </tr>
                            <tr class="va_top">
                                {{-- offenses --}}
                                <td class="{{$tr_bg1}}">
                                    @if(!is_null($violator_Offense->minor_off) OR !empty($violator_Offense->minor_off))
                                        @php
                                            $mo_index = 0;
                                        @endphp
                                        <span class="d-block mb_2 {{ $txt_title }}">Minor Offenses:</span>
                                        @foreach(json_decode(json_encode($violator_Offense->minor_off), true) as $vo_minorOff)
                                            @php
                                                $mo_index++;
                                            @endphp
                                            <span class="d-block mb_2"><span class="{{ $txt_title }}">{{$mo_index}}. </span> <span class="{{ $txt_subTitle }}">{{ $vo_minorOff}}</span></span>
                                        @endforeach
                                        <br>
                                    @endif
                                    @if(!is_null($violator_Offense->less_serious_off) OR !empty($violator_Offense->less_serious_off))
                                        @php
                                            $lso_index = 0;
                                        @endphp
                                        <span class="d-block mb_2 {{ $txt_title }}">Less Serious Offenses:</span>
                                        @foreach(json_decode(json_encode($violator_Offense->less_serious_off), true) as $vo_lessSeriousOff)
                                            @php
                                                $lso_index++;
                                            @endphp
                                            <span class="d-block mb_2"><span class="{{ $txt_title }}">{{$lso_index}}. </span> <span class="{{ $txt_subTitle }}">{{ $vo_lessSeriousOff}}</span></span>
                                        @endforeach
                                        <br>
                                    @endif
                                    @if(!is_null($violator_Offense->other_off) OR !empty($violator_Offense->other_off))
                                        @if(!in_array(null, json_decode(json_encode($violator_Offense->other_off), true)))
                                            @php
                                                $oo_index = 0;
                                            @endphp
                                            <span class="d-block mb_2 {{ $txt_title }}">Others:</span>
                                            @foreach(json_decode(json_encode($violator_Offense->other_off), true) as $vo_othersOff)
                                                @php
                                                    $oo_index++;
                                                @endphp
                                                <span class="d-block mb_2"><span class="{{ $txt_title }}">{{$oo_index}}. </span> <span class="{{ $txt_subTitle }}">{{ $vo_othersOff}}</span></span>
                                            @endforeach
                                            <br>
                                        @endif
                                    @endif
                                </td>
                                {{-- sanctions --}}
                                <td class="{{ $s_tr_bg1 }}">
                                    <span class="d-block mb_2"><span class="{{ $s_txt_title }}">Sanctions: </span></span>
                                    @if($violator_Offense->has_sanction == 1)
                                        @php
                                            // query corresponding sanctins
                                            $query_CorrSanctions = App\Models\Sanctions::where('stud_num', '=', $query_selViolator_info->Student_Number)
                                                                        ->where('for_viola_id', '=', $violator_Offense->viola_id)
                                                                        ->orderBy('created_at', 'asc')
                                                                        ->offset(0)
                                                                        ->limit($violator_Offense->has_sanct_count)
                                                                        ->get();
                                        @endphp
                                        @if (count($query_CorrSanctions) > 0)
                                            @foreach ($query_CorrSanctions as $thisViola_CorrSanctions)
                                            @php
                                                // icons classes for sanction's status
                                                if($thisViola_CorrSanctions->sanct_status === 'completed'){
                                                    $sanct_icon = 'ms-check-square-o';
                                                }else{
                                                    $sanct_icon = 'ms-square-o';
                                                }
                                            @endphp
                                            <span class="d-block mb_2"><span class="{{ $s_txt_title }}"><i class="ms {{ $sanct_icon }}" aria-hidden="true"></i> </span> <span class="{{ $s_txt_subTitle }}">{{ $thisViola_CorrSanctions->sanct_details}} </span></span> 
                                            @endforeach
                                        @else
                                        <span class="d-block mb_2"><span class="{{ $s_txt_subTitle }}"> <em> No Registered Sanctions...</em> </span></span>
                                        @endif
                                    @else
                                        <span class="d-block mb_2"><span class="{{ $s_txt_subTitle }}"> <em> No Registered Sanctions...</em> </span></span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                {{-- statuses --}}
                                <td class="{{ $tr_bg2 }}"><span class="{{ $txt_title }}">Offense Status: </span> <span class="{{ $txt_subTitle }}">{{ucwords($violator_Offense->violation_status) }} {{ $txt_clearedAt }}</span></td>
                                <td class="{{ $s_tr_bg2 }}"><span class="{{ $s_txt_title }}">{{$s_txt_labelTitle}} </span> <span class="{{ $s_txt_subTitle }}">{{ $s_txt_labelsubTitle }}</span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <br>
            
            <p class="notice_1">-- end of table <span class="cg"> (Violator's Recorded Violations Table) </span> --</p>
            
            <br>

            @if (count($query_selViolator_Offenses) > 10)
                <table id="contentsInfo_table" class="b_1">   
                    <tbody>
                        <tr class="tr_bg_DDD">
                            <td colspan="2"><span class="font-weight-bold">Violator's Information: </span></td>
                        </tr>
                        <tr>
                            <td class="bb_1"><span class="font-weight-bold">Name: </span> {{ $query_selViolator_info->First_Name }} {{ $query_selViolator_info->Middle_Name }} {{ $query_selViolator_info->Last_Name}}</td>
                            <td class="bb_1"><span class="font-weight-bold">Gender: </span> {{ $query_selViolator_info->Gender }}</td>
                        </tr>
                        <tr>
                            <td class="bb_1"><span class="font-weight-bold">Student Number: </span> {{ $query_selViolator_info->Student_Number }}</td>
                            <td class="bb_1"><span class="font-weight-bold">Age: </span> {{ $query_selViolator_info->Age }}</td>
                        </tr>
                        <tr>
                            <td class="bb_1"><span class="font-weight-bold">School: </span> {{ $query_selViolator_info->School_Name }}</td>
                            <td class="bb_1"><span class="font-weight-bold">Year Level: </span> {{ $query_selViolator_info->YearLevel }}</td>
                        </tr>
                        <tr>
                            <td class="bb_1"><span class="font-weight-bold">Program: </span> {{ $query_selViolator_info->Course }}</td>
                            <td class="bb_1"></td>
                        </tr>
                        <tr>
                            <td class="b_0 p_x0y2"></td>
                            <td class="b_0 p_x0y2"></td>
                        </tr>
                        <tr class="tr_bg_DDD">
                            <td><span class="font-weight-bold">Offense Details: </span></td>
                            <td><span class="font-weight-bold">Sanctions Details: </span></td>
                        </tr>
                        <tr>
                            <td class="bb_1"><span class="font-weight-bold">Cleared Offense{{$C_s}}: </span> {{ $countAll_Cleared_offenses}}</td>
                            <td class="bb_1"><span class="font-weight-bold">Completed Sanction{{$CS_s}}: </span> {{ $countAll_CompletedSanctions}}</td>
                        </tr>
                        <tr>
                            <td class="bb_1"><span class="font-weight-bold">Uncleared Offense{{$UC_s}}: </span> {{ $UC_offCount}}</td>
                            <td class="bb_1"><span class="font-weight-bold">Not Completed Sanction{{$NCS_s}}: </span> {{ $countAll_NotCompletedSanctions}}</td>
                        </tr>
                        <tr>
                            <td class="bb_1"><span class="font-weight-bold">Total Offense{{$TO_s}}: {{ $countTotal_offenses}}</span></td>
                            <td class="bb_1"><span class="font-weight-bold">Total Sanction{{$TS_s}}: {{ $countAll_Sanctions}}</span></td>
                        </tr>
                    </tbody>
                </table>

                <br>
            @endif

            <table id="contentsInfo_table" class="b_1">
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