<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Registered</title>
</head>
<body>
    <img src="{{$message->embed($details['svms_logo'])}}" style="height: 80px !important; width: auto !important;" alt="Student Violation Management System Logo">

    <br />
    <h3>SVMS ACCOUNT CONFIRMATION</h3>

    <p>Greetings {{ $details['recipient'] }},</p>
    <p>Your email <strong> ({{ $new_profile['user_email'] }}) </strong> has been registered as a system {{ ucwords($new_profile['user_role']) }} of the Student Violation Management System, a web-based system developed for the implementation of the policies, rules, and regulations of St. Dominic College of Asia and to manage and monitor violations committed by its college students.</p>
    <p>To Activate your account, you can do the following options:</p>
    <ol>
        <li>Report to the Department of Student Discipline Office and look for Mr. Apolonio Silva for further instructions, or;</li>
        <li>Email us back the following information to confirm your registration:
            <ol>
                <li>Your Name (Last Name, First Name, and Middle Name)</li>
                <li>Your Student Number</li>
                <li>Your School / Program / Course / Year & Section</li>
                <li>Semester and School Year you are currently enrolled</li>
            </ol>
        </li>
      </ol>
    <br />
    <p>If you don't recognize this registration, you can email us back to terminate this registration and delete your email from our system and any information our system has associated with this email address. Or visit us at the Student Discipline Office for your concern/s.</p>
    <br />
    <p>Thank you for your time, and have a good day.</p>
</body>
</html>