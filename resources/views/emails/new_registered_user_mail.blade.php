<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Account Registered</title>
</head>
<body>
    <img src="{{$message->embed($details['svms_logo'])}}" style="height: 80px !important; width: auto !important;" alt="Student Violation Management System Logo">

    <br />
    <h3>{{$details['title']}}</h3>

    <p>Greetings {{ $details['recipient'] }},</p>
    <p>This email is to notify you that you have been registered as System User of the Student Violation Management System (SVMS), a web-based system developed for the implementation of the policies, rules, and regulations of St. Dominic College of Asia and to manage and monitor violations committed by its college students.</p>
    <p>Below are your login credentials:</p>
    <br />
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
                <th style="padding: 10px 15px; border-top: 0px; border-left: 0px; border: 0px !important;">Login Credentials</th>
            </tr>
        </thead>
        <tbody style="background-color: #ffffff; color: #242333; font-size: 12px;">
            {{-- user's email --}}
            <tr style="line-height: 12px; margin: 7px 0 !important;">
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>Email</strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ $details['registered_email'] }}</p>
                </td>
            </tr>
            {{-- user's password --}}
            <tr style="line-height: 12px; margin: 7px 0 !important; background-color: #f5f5f5;">
                <td style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>Password</strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ $details['registered_passw'] }}</p>
                </td>
            </tr>
            {{-- date registered --}}
            <tr style="line-height: 12px; margin: 7px 0 !important;">
                <td colspan="2" style="vertical-align-top; border-top: 0px; border-left: 0px; border: 0px !important;">
                    <p style="margin: 10px 15px 3px 15px !important;"><strong>Date Registered</strong></p>
                    <p style="margin: 4px 15px 10px 15px !important;">{{ date('F d, Y', strtotime($details['date_registered'])) }} -  {{ date('D', strtotime($details['date_registered'])) }} at {{ date('g:i A', strtotime($details['date_registered'])) }}</p>
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <p>Kindly head to this link <a target="_blank" href="http://127.0.0.1:8000/">Student Violation Mangement System</a> and login using the email and passsword mentioned above to access your account.</p>
    <p>And Please Delete this email after you have memorized your password for security purposes.</p>
    <p>Thank you for your time, and have a good day.</p>
</body>
</html>