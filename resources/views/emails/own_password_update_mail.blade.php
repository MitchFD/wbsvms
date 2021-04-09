<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Update</title>
</head>
<body>
    <img src="{{$message->embed($details['svms_logo'])}}" style="height: 80px !important; width: auto !important;" alt="Student Violation Management System Logo">

    <br />
    <h3>{{$details['title']}}</h3>

    <p>Greetings {{ $details['recipient'] }},</p>
    <p>This email is to notify you that have updated your password.</p>
    {{-- <p>~ <i>{{$details['pass_updt_reason']}}</i> </p> --}}
    <br />
    <p>Kindly head to this link <a href="http://127.0.0.1:8000/">Student Violation Mangement System</a> and log into the system with the following credentials:</p>
    <p><strong>Your Email: </strong> <span style="text-decoration: none !important; color: #000000 !important;">{{ $details['sysUser_email']}}</span></p>
    <p><strong>Your New Password: </strong> {{ $details['sysUser_newPass']}}</p>
    <br />
    <p>And please <strong><u>delete this email</u></strong> after you have memorized your new password for security purposes.</p>
    <p>Thank you for your time, and have a good day.</p>
</body>
</html>