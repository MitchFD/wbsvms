<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Update</title>
</head>
<body>
    <img src="{{$message->embed($details['svms_logo'])}}" style="height: 80px !important; width: auto !important;" alt="Student Violation Management System Logo">

    <br />
    <h3>{{$details['title']}}</h3>

    <p>Greetings {{ $details['recipient'] }},</p>
    <p>This email is to notify you of {{ $details['offense_count'] }} Offense{{$details['s']}} you have committed against the policies, guidelines, rules, and regulations implemented by the Student Discipline Unit for the students of St. Dominic College of Asia.</p>
    <p>Below are your said violations:</p>
    <br />
    {{-- changes comparison --}}
    <table style="
        width: auto !important;
        max-width: auto !important;
        margin-bottom: 1rem !important;
        background-color: transparent !important;
        border-collapse: collapse !important;
        border: 1px solid #FBF1F1 !important;
    ">
        <thead style="
            background-color: #BD171B;
            color: #ffffff;
            text-transform: uppercase;
            font-size: 12px;
        ">
            <tr style="line-height: 15px;">
                <th style="padding: 10px 15px; border-top: 0px; border-left: 0px; border: 0px !important;">Recorded Offenses</th>
            </tr>
        </thead>
        <tbody style="background-color: #ffffff; color: #BD171B; font-size: 12px;">
            {{-- offenses --}}
            @if(!is_null($details['minor_off']) OR !empty($details['minor_off']))
                @php
                    $mo_i = 1;
                @endphp
                <tr style="line-height: 12px; margin: 7px 0 !important; border-bottom: 1px solid #FBF1F1 !important;">
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 5px 15px !important;"><strong>Minor Offense{{$details['s']}}:</strong></p>
                        @foreach($details['minor_off'] as $minor_offense)
                            <p style="margin: 4px 15px 10px 15px !important;"><span><strong>{{$mo_i++}}. </strong> </span> {{ $minor_offense }}</p>
                        @endforeach
                    </td>
                </tr>
            @endif
            @if(!is_null($details['less_serious_off']) OR !empty($details['less_serious_off']))
                @php
                    $lso_i = 1;
                @endphp
                <tr style="line-height: 12px; margin: 7px 0 !important; border-bottom: 1px solid #FBF1F1 !important;">
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 5px 15px !important;"><strong>Less Serious Offense{{$details['s']}}:</strong></p>
                        @foreach($details['less_serious_off'] as $less_serious_offense)
                            <p style="margin: 4px 15px 10px 15px !important;"><span><strong>{{$lso_i++}}. </strong> </span> {{ $less_serious_offense }}</p>
                        @endforeach
                    </td>
                </tr>
            @endif
            @if(!is_null($details['other_off']) OR !empty($details['other_off']))
                @if(!in_array(null, $details['other_off']))
                    @php
                        $oo_i = 1;
                    @endphp
                    <tr style="line-height: 12px; margin: 7px 0 !important; border-bottom: 1px solid #FBF1F1 !important;">
                        <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                            <p style="margin: 10px 15px 5px 15px !important;"><strong>Other Offense{{$details['s']}}:</strong></p>
                            @foreach($details['other_off'] as $other_offense)
                                <p style="margin: 4px 15px 10px 15px !important;"><span><strong>{{$oo_i++}}. </strong> </span> {{ $other_offense }}</p>
                            @endforeach
                        </td>
                    </tr>
                @endif
            @endif
            {{-- date recorded --}}
            <tr style="line-height: 12px; margin: 7px 0 !important; background-color: #FBF1F1;">
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 5px 15px !important;"><strong>Date Recorded</strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ date('F d, Y', strtotime($details['date_recorded'])) }} ~  {{ date('l', strtotime($details['date_recorded'])) }} at {{ date('g:i A', strtotime($details['date_recorded'])) }}</p>
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    <p>Kindly report to the Student Discipline Office within three (3) working days for clearing your offenses.</p>
    <p>Thank you for your time, and have a good day.</p>
</body>
</html>