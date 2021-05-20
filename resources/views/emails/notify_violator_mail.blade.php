<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Student Offenses Record</title>
    </head>

    <style>
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
    </style>

    <body>
        <img src="{{$message->embed($details['svms_logo'])}}" style="height: 80px !important; width: auto !important;" alt="Student Violation Management System Logo">

        <br />
        <h3>{{$details['title']}}</h3>

        @php
            // get violator's informaion
            $query_violatorInformation = App\Models\Students::where('Student_Number', '=', $details['sel_Student_Number'])->first();
            // violator's gender handlers
            if(!is_null($query_violatorInformation->Gender) OR !empty($query_violatorInformation->Gender)){
                if($query_violatorInformation->Gender === 'Male'){
                    $Vmr_ms = 'Mr.';
                }elseif($query_violatorInformation->Gender === 'Female'){
                    $Vmr_ms = 'Ms.';
                }else{
                    $Vmr_ms = 'Mr./Ms.';
                }
            }else{
                $Vmr_ms = 'Mr./Ms.';
            }

            // check = has recorded violations
            $has_recordedViolations = App\Models\Violations::where('stud_num', '=', $details['sel_Student_Number'])->count();
        
        @endphp

        <p>Greetings {{ $Vmr_ms }} {{ $query_violatorInformation->Last_Name}},</p>

        {{-- if has recorded violationos --}}
        @if($has_recordedViolations > 0)
            @php
                // query all violations
                $query_allViolations = App\Models\Violations::where('stud_num', '=', $details['sel_Student_Number'])->get();

                // sum all offenses
                $sumAll_Offenses = App\Models\Violations::where('stud_num', '=', $details['sel_Student_Number'])->sum('offense_count');
                $sumAll_ClearedOffenses = App\Models\Violations::where('stud_num', '=', $details['sel_Student_Number'])->where('violation_status', '=', 'cleared')->sum('offense_count');
                $sumAll_UnclearedOffenses = App\Models\Violations::where('stud_num', '=', $details['sel_Student_Number'])->where('violation_status', '!=', 'cleared')->sum('offense_count');

                // plurals
                // all offenses
                if($sumAll_Offenses > 0){
                    if($sumAll_Offenses > 1){
                        $sAO_s = 's';
                    }else{
                        $sAO_s = '';
                    }
                }else{
                    $sAO_s = '';
                }
                // cleared offenses
                if($sumAll_ClearedOffenses > 0){
                    if($sumAll_ClearedOffenses > 1){
                        $sCO_s = 's';
                    }else{
                        $sCO_s = '';
                    }
                }else{
                    $sCO_s = '';
                }
                // not cleared offenses
                if($sumAll_UnclearedOffenses > 0){
                    if($sumAll_UnclearedOffenses > 1){
                        $sUO_s = 's';
                    }else{
                        $sUO_s = '';
                    }
                }else{
                    $sUO_s = '';
                }

            @endphp
            @if(count($query_allViolations) > 0)
                @php
                    // count all completed violations
                    $count_allClearedViolations = App\Models\Violations::where('stud_num', '=', $details['sel_Student_Number'])->where('violation_status', '=', 'cleared')->count();

                    // sacntions
                    // count al corresponding sanctions
                    $countAll_Sanctions = App\Models\Sanctions::where('stud_num', '=', $details['sel_Student_Number'])->count();
                    // count all completed sanctions
                    $countAll_CompletedSanctions = App\Models\Sanctions::where('stud_num', '=', $details['sel_Student_Number'])
                                                        ->where('sanct_status', '=', 'completed')
                                                        ->count();
                    // count all not completed sanctions
                    $countAll_NotCompletedSanctions = App\Models\Sanctions::where('stud_num', '=', $details['sel_Student_Number'])
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

                <p>This email is to notify you the status of the {{ $sumAll_Offenses }} Offense{{$sAO_s }} you have committed against the policies, guidelines, rules, and regulations of St. Dominic College of Asia, and it's Corresponding Sanctions.</p>
                <p>Below are your said violations and it's corresponding sanctions:</p>
                
                <br>

                {{-- tables --}}
                <table style="
                    width: auto !important;
                    max-width: auto !important;
                    margin-bottom: 1rem !important;
                    background-color: transparent !important;
                    border-collapse: collapse !important;
                    border: 0 !important;
                ">
                {{-- violator's information --}}
                    <thead style="
                        background-color: #e7e7e7;
                        color: #242333;
                        text-transform: uppercase;
                        font-size: 12px;
                    ">
                        <tr style="line-height: 15px;">
                            <th style="padding: 10px 15px; border: 0 !important;">
                                Violator's Information
                            </th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #f2f2f2; color: #242333; font-size: 12px;">
                        <tr style="line-height: 12px;">
                            <td style="vertical-align-top; border: 0 !important;">
                                <br>
                                <p style="margin: 10px 15px !important;"><strong>Violator: </strong></p>
                                <p style="margin: 6px 15px 6px 15px !important;"><strong>Name: </strong> {{ $query_violatorInformation->First_Name }} {{ $query_violatorInformation->Middle_Name }} {{ $query_violatorInformation->Last_Name }}</p>
                                <p style="margin: 6px 15px 6px 15px !important;"><strong>Student Number: </strong> {{ $query_violatorInformation->Student_Number }}</p>
                                <p style="margin: 6px 15px 6px 15px !important;"><strong>School/Program/Year Level: </strong> {{ $query_violatorInformation->School_Name }} | {{ $query_violatorInformation->Course }} | {{ $query_violatorInformation->YearLevel}}-Y</p>
                                <p style="margin: 6px 15px 6px 15px !important;"><strong>Age/Gender: </strong> {{ $query_violatorInformation->Age }} y/o | {{ $query_violatorInformation->Gender }} </p>
                                <br>
                            </td>
                        </tr>
                    </tbody>
                    <br>
                {{-- violators's information end --}}
                {{-- overview --}}
                    <thead style="
                        background-color: #e7e7e7;
                        color: #242333;
                        text-transform: uppercase;
                        font-size: 12px;
                    ">
                        <tr style="line-height: 15px;">
                            <th style="padding: 10px 15px; border: 0 !important;">
                                Offenses Overview
                            </th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #f2f2f2; color: #242333; font-size: 12px;">
                        <tr style="line-height: 12px;">
                            <td style="vertical-align-top; border: 0 !important;">
                                <br>
                                <p style="margin: 10px 15px !important;"><strong>Offenses Details: </strong></p>
                                <p style="margin: 6px 15px 6px 15px !important;">Cleared Offense{{$sCO_s}}: <strong> {{ $sumAll_ClearedOffenses}} </strong></p>
                                <p style="margin: 6px 15px 6px 15px !important;">Not Cleared Offense{{$sUO_s}}: <strong> {{ $sumAll_UnclearedOffenses}} </strong></p>
                                <p style="margin: 6px 15px 6px 15px !important;"><strong> Total Offense{{$sAO_s}}: {{ $sumAll_Offenses}} </strong></p>
                                <br>
                                <p style="margin: 10px 15px !important;"><strong>Sanctions Details: </strong></p>
                                <p style="margin: 6px 15px 6px 15px !important;">Completed Sanction{{$CS_s}}: <strong> {{ $countAll_CompletedSanctions}} </strong></p>
                                <p style="margin: 6px 15px 6px 15px !important;">Not Completed Sanction{{$NCS_s}}: <strong> {{ $countAll_NotCompletedSanctions}} </strong></p>
                                <p style="margin: 6px 15px 6px 15px !important;"><strong> Total Sanction{{$TS_s}}: {{ $countAll_Sanctions}} </strong></p>
                                <br>
                            </td>
                        </tr>
                    </tbody>

                    <br>
                    <br>

                {{-- offenses and corresponding sanctions tables --}}
                    @foreach ($query_allViolations as $this_violation)
                    {{-- if violation is Cleared --}}
                        @if($this_violation->violation_status === 'cleared')
                            <thead style="
                                background-color: #c7efd8;
                                color: #47A471;
                                text-transform: uppercase;
                                font-size: 12px;
                            ">
                                <tr style="line-height: 15px;">
                                    <th style="padding: 10px 15px; border: 0 !important;">
                                        Date Recorded: {{ date('F d, Y (D - g:i A)', strtotime($this_violation->recorded_at)) }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="background-color: #F0FAF5; color: #47A471; font-size: 12px;">
                                <tr style="line-height: 12px;">
                                    <td style="vertical-align-top; border: 0 !important;">
                                        <br>
                                        @if(!is_null($this_violation->minor_off) OR !empty($this_violation->minor_off))
                                            @php
                                                $mo_i = 0;
                                            @endphp
                                            <p style="margin: 6px 15px 5px 15px !important;"><strong>Minor Offenses:</strong></p>
                                            @foreach (json_decode(json_encode($this_violation->minor_off), true) as $this_minorOff)
                                                @php
                                                    $mo_i++;
                                                @endphp
                                                <p style="margin: 6px 15px 6px 15px !important;"><span><strong>{{$mo_i}}. </strong> </span> {{ $this_minorOff }}</p>
                                            @endforeach
                                        @endif
                                        @if(!is_null($this_violation->less_serious_off) OR !empty($this_violation->less_serious_off))
                                            @php
                                                $lso_i = 0;
                                            @endphp
                                            <p style="margin: 20px 15px 5px 15px !important;"><strong>Less Serious Offenses:</strong></p>
                                            @foreach (json_decode(json_encode($this_violation->less_serious_off), true) as $this_lessSeriousOff)
                                                @php
                                                    $lso_i++;
                                                @endphp
                                                <p style="margin: 6px 15px 6px 15px !important;"><span><strong>{{$lso_i}}. </strong> </span> {{ $this_lessSeriousOff }}</p>
                                            @endforeach
                                        @endif
                                        @if(!is_null($this_violation->other_off) OR !empty($this_violation->other_off))
                                            @if(!in_array(null, json_decode(json_encode($this_violation->other_off), true)))
                                                @php
                                                    $oo_i = 0;
                                                @endphp
                                                <p style="margin: 20px 15px 5px 15px !important;"><strong>Other Offenses:</strong></p>
                                                @foreach (json_decode(json_encode($this_violation->other_off), true) as $this_OthersOff)
                                                    @php
                                                        $oo_i++;
                                                    @endphp
                                                    <p style="margin: 6px 15px 6px 15px !important;"><span><strong>{{$oo_i}}. </strong> </span> {{ $this_OthersOff }}</p>
                                                @endforeach
                                            @endif
                                        @endif
                                        <br>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody style="background-color: #E9F8F0; color: #47A471; font-size: 12px;">
                                <tr style="line-height: 12px;">
                                    <td style="vertical-align-top; border: 0 !important;">
                                        <p style="margin: 10px 15px !important;">
                                            <strong>Offenses Status: {{ ucwords($this_violation->violation_status) }} ({{ date('F d, Y ~ D - g:i A', strtotime($this_violation->cleared_at)) }})</strong>
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody style="
                                background-color: #c7efd8;
                                color: #47A471;
                                text-transform: uppercase;
                                font-size: 12px;
                            ">
                                <tr style="line-height: 15px;">
                                    <th style="padding: 10px 15px; border: 0 !important;">
                                        Corresponding Sanctions
                                    </th>
                                </tr>
                            </tbody>
                            {{-- has corresponding sanctions --}}
                            @if($this_violation->has_sanction == 1)
                                @php
                                    // query corresponding sanctins
                                    $query_CorrSanctions = App\Models\Sanctions::where('stud_num', '=', $details['sel_Student_Number'])
                                                            ->where('for_viola_id', '=', $this_violation->viola_id)
                                                            ->orderBy('created_at', 'asc')
                                                            ->offset(0)
                                                            ->limit($this_violation->has_sanct_count)
                                                            ->get();
                                    // count all cleared sanctions
                                    $countCompleted_CorrSanctions = App\Models\Sanctions::where('stud_num', '=', $details['sel_Student_Number'])
                                                            ->where('for_viola_id', '=', $this_violation->viola_id)
                                                            ->where('sanct_status', '=', 'completed')
                                                            ->offset(0)
                                                            ->limit($this_violation->has_sanct_count)
                                                            ->count();
                                    // condition for sanctions status
                                    if(count($query_CorrSanctions) == $countCompleted_CorrSanctions){
                                        $txt_Completed = 'Cleared';
                                    }else{
                                        $txt_Completed = 'Not Cleared';
                                    }
                                @endphp
                                <tbody style="background-color: #E9F8F0; color: #47A471; font-size: 12px;">
                                    <tr style="line-height: 12px;">
                                        <td style="vertical-align-top; border: 0 !important;">
                                            <br>
                                            <p style="margin: 6px 15px 5px 15px !important;"><strong>Sanctions:</strong></p>
                                                @if(count($query_CorrSanctions) > 0)
                                                    @php
                                                        $cs_i = 0;
                                                    @endphp
                                                    @foreach($query_CorrSanctions as $thisViola_CorrSanctions)
                                                        @php
                                                            // sanction status
                                                            if($thisViola_CorrSanctions->sanct_status === 'completed'){
                                                                $sanctStat = ' ~ Completed';
                                                            }else{
                                                                $sanctStat = '';
                                                            }
                                                            $cs_i++;
                                                        @endphp
                                                        <p style="margin: 6px 15px 6px 15px !important;">
                                                            <strong>{{$cs_i}}. </strong> {{ $thisViola_CorrSanctions->sanct_details }} <strong><em>{{$sanctStat}}</em></strong>
                                                        </p>
                                                    @endforeach
                                                @else
                                                    
                                                @endif
                                            <br>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody style="background-color: #E9F8F0; color: #47A471; font-size: 12px;">
                                    <tr style="line-height: 12px;">
                                        <td style="vertical-align-top; border: 0 !important;">
                                            <p style="margin: 10px 15px !important;">
                                                <strong>Sanctions Status: {{ $txt_Completed }}</strong>
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            @endif
                            <br>
                            <br>
                    {{-- if violation is Not Cleared --}}
                        @else
                            <thead style="
                                background-color: #F2D0D1;
                                color: #BD171B;
                                text-transform: uppercase;
                                font-size: 12px;
                            ">
                                <tr style="line-height: 15px;">
                                    <th style="padding: 10px 15px; border: 0 !important;">
                                        Date Recorded: {{ date('F d, Y (D - g:i A)', strtotime($this_violation->recorded_at)) }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="background-color: #FBF1F1; color: #BD171B; font-size: 12px;">
                                <tr style="line-height: 12px;">
                                    <td style="vertical-align-top; border: 0 !important;">
                                        <br>
                                        @if(!is_null($this_violation->minor_off) OR !empty($this_violation->minor_off))
                                            @php
                                                $mo_i = 0;
                                            @endphp
                                            <p style="margin: 6px 15px 5px 15px !important;"><strong>Minor Offenses:</strong></p>
                                            @foreach (json_decode(json_encode($this_violation->minor_off), true) as $this_minorOff)
                                                @php
                                                    $mo_i++;
                                                @endphp
                                                <p style="margin: 6px 15px 6px 15px !important;"><span><strong>{{$mo_i}}. </strong> </span> {{ $this_minorOff }}</p>
                                            @endforeach
                                        @endif
                                        @if(!is_null($this_violation->less_serious_off) OR !empty($this_violation->less_serious_off))
                                            @php
                                                $lso_i = 0;
                                            @endphp
                                            <p style="margin: 20px 15px 5px 15px !important;"><strong>Less Serious Offenses:</strong></p>
                                            @foreach (json_decode(json_encode($this_violation->less_serious_off), true) as $this_lessSeriousOff)
                                                @php
                                                    $lso_i++;
                                                @endphp
                                                <p style="margin: 6px 15px 6px 15px !important;"><span><strong>{{$lso_i}}. </strong> </span> {{ $this_lessSeriousOff }}</p>
                                            @endforeach
                                        @endif
                                        @if(!is_null($this_violation->other_off) OR !empty($this_violation->other_off))
                                            @if(!in_array(null, json_decode(json_encode($this_violation->other_off), true)))
                                                @php
                                                    $oo_i = 0;
                                                @endphp
                                                <p style="margin: 20px 15px 5px 15px !important;"><strong>Other Offenses:</strong></p>
                                                @foreach (json_decode(json_encode($this_violation->other_off), true) as $this_OthersOff)
                                                    @php
                                                        $oo_i++;
                                                    @endphp
                                                    <p style="margin: 6px 15px 6px 15px !important;"><span><strong>{{$oo_i}}. </strong> </span> {{ $this_OthersOff }}</p>
                                                @endforeach
                                            @endif
                                        @endif
                                        <br>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody style="background-color: #F8E7E7; color: #BD171B; font-size: 12px;">
                                <tr style="line-height: 12px;">
                                    <td style="vertical-align-top; border: 0 !important;">
                                        <p style="margin: 10px 15px !important;"><strong>Offenses Status: {{ ucwords($this_violation->violation_status) }}</strong></p>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody style="
                                background-color: #c7efd8;
                                color: #47A471;
                                text-transform: uppercase;
                                font-size: 12px;
                            ">
                                <tr style="line-height: 15px;">
                                    <th style="padding: 10px 15px; border: 0 !important;">
                                        Corresponding Sanctions
                                    </th>
                                </tr>
                            </tbody>
                            {{-- has corresponding sanctions --}}
                            @if($this_violation->has_sanction == 1)
                                @php
                                    // query corresponding sanctins
                                    $query_CorrSanctions = App\Models\Sanctions::where('stud_num', '=', $details['sel_Student_Number'])
                                                            ->where('for_viola_id', '=', $this_violation->viola_id)
                                                            ->orderBy('created_at', 'asc')
                                                            ->offset(0)
                                                            ->limit($this_violation->has_sanct_count)
                                                            ->get();
                                    // count all cleared sanctions
                                    $countCompleted_CorrSanctions = App\Models\Sanctions::where('stud_num', '=', $details['sel_Student_Number'])
                                                            ->where('for_viola_id', '=', $this_violation->viola_id)
                                                            ->where('sanct_status', '=', 'completed')
                                                            ->offset(0)
                                                            ->limit($this_violation->has_sanct_count)
                                                            ->count();
                                    // condition for sanctions status
                                    if(count($query_CorrSanctions) == $countCompleted_CorrSanctions){
                                        $txt_Completed = 'Cleared';
                                    }else{
                                        $txt_Completed = 'Not Cleared';
                                    }
                                @endphp
                                <tbody style="background-color: #E9F8F0; color: #47A471; font-size: 12px;">
                                    <tr style="line-height: 12px;">
                                        <td style="vertical-align-top; border: 0 !important;">
                                            <br>
                                            <p style="margin: 6px 15px 5px 15px !important;"><strong>Sanctions:</strong></p>
                                                @if(count($query_CorrSanctions) > 0)
                                                    @php
                                                        $cs_i = 0;
                                                    @endphp
                                                    @foreach($query_CorrSanctions as $thisViola_CorrSanctions)
                                                        @php
                                                            // sanction status
                                                            if($thisViola_CorrSanctions->sanct_status === 'completed'){
                                                                $sanctStat = ' ~ Completed';
                                                            }else{
                                                                $sanctStat = '';
                                                            }
                                                            $cs_i++;
                                                        @endphp
                                                        <p style="margin: 6px 15px 6px 15px !important;">
                                                            <strong>{{$cs_i}}. </strong> {{ $thisViola_CorrSanctions->sanct_details }} <strong><em>{{$sanctStat}}</em></strong>
                                                        </p>
                                                    @endforeach
                                                @else
                                                    
                                                @endif
                                            <br>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody style="background-color: #E9F8F0; color: #47A471; font-size: 12px;">
                                    <tr style="line-height: 12px;">
                                        <td style="vertical-align-top; border: 0 !important;">
                                            <p style="margin: 10px 15px !important;">
                                                <strong>Sanctions Status: {{ $txt_Completed }}</strong>
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            @endif
                            <br>
                            <br>
                        @endif
                    @endforeach
                {{-- offenses and corresponding sanctions tables end --}}
                {{-- overview part II --}}
                    <thead style="
                        background-color: #e7e7e7;
                        color: #242333;
                        text-transform: uppercase;
                        font-size: 12px;
                    ">
                        <tr style="line-height: 15px;">
                            <th style="padding: 10px 15px; border: 0 !important;">
                                Offenses Overview
                            </th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #f2f2f2; color: #242333; font-size: 12px;">
                        <tr style="line-height: 12px;">
                            <td style="vertical-align-top; border: 0 !important;">
                                <br>
                                <p style="margin: 10px 15px !important;"><strong>Offenses Details: </strong></p>
                                <p style="margin: 6px 15px 6px 15px !important;">Cleared Offense{{$sCO_s}}: <strong> {{ $sumAll_ClearedOffenses}} </strong></p>
                                <p style="margin: 6px 15px 6px 15px !important;">Not Cleared Offense{{$sUO_s}}: <strong> {{ $sumAll_UnclearedOffenses}} </strong></p>
                                <p style="margin: 6px 15px 6px 15px !important;"><strong> Total Offense{{$sAO_s}}: {{ $sumAll_Offenses}} </strong></p>
                                <br>
                                <p style="margin: 10px 15px !important;"><strong>Sanctions Details: </strong></p>
                                <p style="margin: 6px 15px 6px 15px !important;">Completed Sanction{{$CS_s}}: <strong> {{ $countAll_CompletedSanctions}} </strong></p>
                                <p style="margin: 6px 15px 6px 15px !important;">Not Completed Sanction{{$NCS_s}}: <strong> {{ $countAll_NotCompletedSanctions}} </strong></p>
                                <p style="margin: 6px 15px 6px 15px !important;"><strong> Total Sanction{{$TS_s}}: {{ $countAll_Sanctions}} </strong></p>
                                <br>
                            </td>
                        </tr>
                    </tbody>

                    <br>

                </table>
                @if(count($query_allViolations) == $count_allClearedViolations)
                    <p>You have Cleared all the <strong> {{ $sumAll_Offenses }} Offense{{$sAO_s }} </strong> you have committed.</p>
                    <p>Kindly report to the Student Discipline Office within three (3) working days for signing your Clearance Form..</p>
                @else
                    <p>Kindly report to the Student Discipline Office within three (3) working days for clearing your offenses.</p>
                @endif
            @else
                <p>This email is to notify you that you are Cleared for Clearance for not having any Offenses committed against the policies, guidelines, rules, and regulations of St. Dominic College of Asia.</p>
                <p>Kindly report to the Student Discipline Office within three (3) working days for signing your Clearance Form.</p>
            @endif
        @else
            <p>This email is to notify you that you are Cleared for Clearance for not having any Offenses committed against the policies, guidelines, rules, and regulations of St. Dominic College of Asia.</p>
            <p>Kindly report to the Student Discipline Office within three (3) working days for signing your Clearance Form.</p>
        @endif

        <p>Thank you for your time, and have a good day.</p>
    </body>
</html>