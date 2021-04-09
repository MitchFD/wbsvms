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
    <p>This email is to notify you that the System Administrator of the Student Violation Management System has updated your profile.</p>
    <p>Below are the changes made to your account information:</p>
    <br />
    {{-- changes comparison --}}
    <table style="
        width: auto !important;
        max-width: auto !important;
        margin-bottom: 1rem !important;
        background-color: transparent !important;
        border-collapse: collapse !important;
        border: 1px solid #f5f5f5 !important;
    ">
        <thead style="
            background-color: #242333;
            color: #ffffff;
            text-transform: uppercase;
            font-size: 12px;
        ">
            <tr style="line-height: 15px;">
                <th style="padding: 10px 15px; border-top: 0px; border-left: 0px; border: 0px !important;">Original Profile</th>
                <th style="padding: 10px 15px; border-top: 0px; border-left: 0px; border: 0px !important;">Updated Profile</th>
            </tr>
        </thead>
        <tbody style="background-color: #ffffff; color: #242333; font-size: 12px;">
            {{-- user's image --}}
            <tr style="line-height: 12px; margin: 7px 0 !important; background-color: #f5f5f5;">
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>profile image</strong></p>
                    <img src="{{$message->embed($old_profile['user_image'])}}" 
                        style="height: 60px !important; 
                            width: 60px !important;
                            overflow: hidden !important;
                            border-radius: 50% !important;
                            position: relative !important;
                            object-fit: cover !important;
                            background-size: cover !important;
                            background-position: 50% 50% !important;
                            background-repeat: no-repeat !important;
                            margin: 7px 15px 10px 15px !important;" 
                        alt="your old profile image">
                </td>
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_image'] !== $new_profile['user_image']) updated @endif </em></strong></p>
                    <img src="{{$message->embed($new_profile['user_image'])}}" 
                        style="height: 60px !important; 
                            width: 60px !important;
                            overflow: hidden !important;
                            border-radius: 50% !important;
                            position: relative !important;
                            object-fit: cover !important;
                            background-size: cover !important;
                            background-position: 50% 50% !important;
                            background-repeat: no-repeat !important;
                            margin: 7px 15px 10px 15px !important;" 
                        alt="your new profile image">
                </td>
            </tr>
            {{-- user's role --}}
            <tr style="line-height: 12px; margin: 7px 0 !important;">
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>System Role</strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ ucwords($old_profile['user_role']) }}</p>
                </td>
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_role'] !== $new_profile['user_role']) updated @endif </em></strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ ucwords($new_profile['user_role']) }}</p>
                </td>
            </tr>
            {{-- user's email --}}
            <tr style="line-height: 12px; margin: 7px 0 !important; background-color: #f5f5f5;">
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>Email</strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ $old_profile['user_email'] }}</p>
                </td>
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_email'] !== $new_profile['user_email']) updated @endif </em></strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ $new_profile['user_email'] }}</p>
                </td>
            </tr>
            {{-- user's last name --}}
            <tr style="line-height: 12px; margin: 7px 0 !important;">
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>Last Name</strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ $old_profile['user_last_name'] }}</p>
                </td>
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_last_name'] !== $new_profile['user_last_name']) updated @endif </em></strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ $new_profile['user_last_name'] }}</p>
                </td>
            </tr>
            {{-- user's first name --}}
            <tr style="line-height: 12px; margin: 7px 0 !important; background-color: #f5f5f5;">
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>First Name</strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ $old_profile['user_first_name'] }}</p>
                </td>
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_first_name'] !== $new_profile['user_first_name']) updated @endif </em></strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ $new_profile['user_first_name'] }}</p>
                </td>
            </tr>
            {{-- user's gender --}}
            <tr style="line-height: 12px; margin: 7px 0 !important;">
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>Gender</strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ ucwords($old_profile['user_gender']) }}</p>
                </td>
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_gender'] !== $new_profile['user_gender']) updated @endif </em></strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ ucwords($new_profile['user_gender']) }}</p>
                </td>
            </tr>
            @if($old_profile['user_type'] === 'student')
                {{-- student's number --}}
                <tr style="line-height: 12px; margin: 7px 0 !important; background-color: #f5f5f5;">
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>Student Number</strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $old_profile['user_sdca_id'] }}</p>
                    </td>
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_sdca_id'] !== $new_profile['user_sdca_id']) updated @endif </em></strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $new_profile['user_sdca_id'] }}</p>
                    </td>
                </tr>
                {{-- student's school --}}
                <tr style="line-height: 12px; margin: 7px 0 !important;">
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>School</strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $old_profile['user_school'] }}</p>
                    </td>
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_school'] !== $new_profile['user_school']) updated @endif </em></strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $new_profile['user_school'] }}</p>
                    </td>
                </tr>
                {{-- student's program --}}
                <tr style="line-height: 12px; margin: 7px 0 !important; background-color: #f5f5f5;">
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>Program/Course</strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $old_profile['user_program'] }}</p>
                    </td>
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_program'] !== $new_profile['user_program']) updated @endif </em></strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $new_profile['user_program'] }}</p>
                    </td>
                </tr>
                {{-- student's year level --}}
                <tr style="line-height: 12px; margin: 7px 0 !important;">
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>Year Level</strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $old_profile['user_yrlvl'] }}</p>
                    </td>
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_yrlvl'] !== $new_profile['user_yrlvl']) updated @endif </em></strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $new_profile['user_yrlvl'] }}</p>
                    </td>
                </tr>
                {{-- student's section --}}
                <tr style="line-height: 12px; margin: 7px 0 !important; background-color: #f5f5f5;">
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>Section</strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $old_profile['user_section'] }}</p>
                    </td>
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_section'] !== $new_profile['user_section']) updated @endif </em></strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $new_profile['user_section'] }}</p>
                    </td>
                </tr>
            @elseif($old_profile['user_type'] === 'employee')
                {{-- employee's ID --}}
                <tr style="line-height: 12px; margin: 7px 0 !important; background-color: #f5f5f5;">
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>Employee ID</strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $old_profile['user_sdca_id'] }}</p>
                    </td>
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_sdca_id'] !== $new_profile['user_sdca_id']) updated @endif </em></strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $new_profile['user_sdca_id'] }}</p>
                    </td>
                </tr>
                {{-- employee's Job Description --}}
                <tr style="line-height: 12px; margin: 7px 0 !important;">
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>Job Description</strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $old_profile['user_job_desc'] }}</p>
                    </td>
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_job_desc'] !== $new_profile['user_job_desc']) updated @endif </em></strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $new_profile['user_job_desc'] }}</p>
                    </td>
                </tr>
                {{-- employee's Department --}}
                <tr style="line-height: 12px; margin: 7px 0 !important; background-color: #f5f5f5;">
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>Department</strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $old_profile['user_dept'] }}</p>
                    </td>
                    <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                        <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_dept'] !== $new_profile['user_dept']) updated @endif </em></strong></p>
                        <p style="margin: 4px 15px 10px 15px !important;">{{ $new_profile['user_dept'] }}</p>
                    </td>
                </tr>
            @else
                {{-- no print --}}
            @endif
            {{-- user's phone number --}}
            <tr style="line-height: 12px; margin: 7px 0 !important;">
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>Phone Number</strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ $old_profile['user_phnum'] }}</p>
                </td>
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>~ <em style="color: #6bd098 !important;"> @if($old_profile['user_gender'] !== $new_profile['user_gender']) updated @endif </em></strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ $new_profile['user_phnum'] }}</p>
                </td>
            </tr>
            {{-- date updated --}}
            <tr style="line-height: 12px; margin: 7px 0 !important; background-color: #f5f5f5;">
                <td colspan="2" style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>Date Updated</strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ date('F d, Y', strtotime($details['date_of_changes'])) }} -  {{ date('D', strtotime($details['date_of_changes'])) }} at {{ date('g:i A', strtotime($details['date_of_changes'])) }}</p>
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    @if($old_profile['user_email'] !== $new_profile['user_email'])
        <p>We have sent a message to your new email address <strong> ({{ $new_profile['user_email'] }}) </strong> for instructions to access our system.</p>
    @else
        <p>Kindly head to this link <a href="http://127.0.0.1:8000/">Student Violation Mangement System</a> and log in to the system to view your updated profile and/or edit these changes if incorrect details were found.</p>
    @endif
    <p>Thank you for your time, and have a good day.</p>
</body>
</html>