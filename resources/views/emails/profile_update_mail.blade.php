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
    <p>This email is to notify you that the system Administrator of the Student Violation Management System has updated your profile for the following reason/s:</p>
    <p>~ <i>{{$details['pass_updt_reason']}}</i> </p>
    <br />
    <p>Below are the changes madeto your account information:</p>
    {{-- changes comparison --}}
    <br />
    <p>Kindly head to this link and log in to the system to view your updated profile and/or edit these changes if incorrect details were found.</p>
    <p>Thank you for your time, and have a good day.</p>
</body>
</html>