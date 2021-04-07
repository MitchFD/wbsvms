<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>System Role Update</title>
</head>
<body>
    <img src="{{$message->embed($details['svms_logo'])}}" style="height: 80px !important; width: auto !important;" alt="Student Violation Management System Logo">

    <br />
    <h3>{{$details['title']}}</h3>

    <p>Greetings {{ $details['recipient'] }},</p>
    <p>This email is to notify you that the system Administrator of the Student Violation Management System has changed your System Role from <strong> {{ $details['old_sys_role'] }} Role </strong> to <strong> {{ $details['new_sys_role'] }} Role </strong> for the following reason/s:</p>
    <p>~ <i>{{$details['change_role_reason']}}</i> </p>
    <br />
    <p>Kindly head to this link <a href="http://127.0.0.1:8000/">Student Violation Mangement System</a> and log into the system to access your new Role for SVMS.</p>
    <br />
    <p>Thank you for your time, and have a good day.</p>
</body>
</html>