<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Registration</title>
</head>
<body>
    <img src="{{$message->embed($details['svms_logo'])}}" style="height: 80px !important; width: auto !important;" alt="Student Violation Management System Logo">

    <br />
    <h3>SVMS ACCOUNT CONFIRMATION</h3>

    <p>Greetings {{ $details['recipient'] }},</p>
    <p>This Email address has been registered as a <strong> system {{ ucwords($new_profile['user_role']) }} </strong> of the Student Violation Management System, a web-based system developed for the implementation of the policies, rules, and regulations of St. Dominic College of Asia and to manage and monitor violations committed by its college students.</p>
    <p>The System Administrator of SVMS has updated your registered email address from: {{ $old_profile['user_email'] }} to this new email address.</p>
    <p>Your Account Password is still the same.</p>
    <p>If you do not expect or are unaware of these changes to your account, you can email us back to restore your old profile information. Or head to this link <a href="http://127.0.0.1:8000/">Student Violation Mangement System</a> and log in to the system with your new email address <strong> ({{ $new_profile['user_email'] }}) </strong> to view and/or edit your profile.</p>
    {{-- <p>To Activate your account, you can do the following options:</p>
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
      </ol> --}}
    <p>Thank you for your time, and have a good day.</p>
</body>
</html>