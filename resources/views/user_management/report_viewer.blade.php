<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Activity Logs Report</title>
</head>
<style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            text-align: center;
        }
        header {
            position: fixed;
            top: -30px;
            left: 0px;
            right: 0px;
            height: 60px;

            /** Extra personal styles **/
            background-color: rgb(175, 175, 175);
            color: #000;
            text-align: center;
        }

        main {
            position: relative;
            top: 50px !important;
            page-break-after: always;
        }

        footer {
            position: fixed; 
            bottom: -30px; 
            left: 0px; 
            right: 0px;
            height: 50px; 

            /** Extra personal styles **/
            background-color: rgb(228, 228, 228);
            color: #000;
            text-align: center;
            align-items: center !important;
        }
        .pdf-table{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            border-collapse: collapse;
            width: 100%;
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
            overflow: hidden !important;
            border: 1px solid #f5f5f5 !important;
        }
        .pdf-table thead{
            padding: 10px 15px !important;
            background-color: #242333;
            color: white;
            align-items: center !important;
        }
        .pdf-table tbody{
            padding: 10px 15px !important;
            background-color: #ffffff; 
            color: #000; 
            font-size: 14px;
        }
        .pdf-table thead th{
            padding: 10px 15px !important;
            text-align: left !important;
            border: 0 !important;
        }
        .pdf-table tbody td{
            border-bottom: 1px solid #f5f5f5 !important;
            padding: 5px 15px !important;
            text-align: left !important;
        }

        .actLogs_tdTitle{
            margin: 0;
            font-size: 14px;
            color: #000);
        }
        .actLogs_tdSubTitle{
            margin: 0;
            font-size: 13px;
            font-weight: normal;
        }
        .sub1{
            color: #808080;
        }
        .sub2{
            color:#808080 !important;
        }
        .subDiv{
            color:#808080 !important;
        }
        .actLogs_content{
            margin: 0;
            font-size: 13px;
            font-weight: normal;
            color: #000;
        }
</style>
<body>
    <header>
        <img src="{{public_path('storage/svms/sdca_images/sdca_logo.jpg')}}" style="height: 60px !important; width: auto !important;" alt="SDCA Logo">
    </header>

    <footer>
        <p style="float:left;font-weight:bold;color: #808080;">Student Violation Management System / Users Logs Report</p>
        <img src="{{public_path('storage/svms/logos/svms_logo_title_red.png')}}" style="height: 50px !important; width: auto !important;float:right;" alt="SDCA Logo">
    </footer>

    <main>
        <h4 style="margin: 0 !important;">STUDENT VIOLATION MANAGEMENT SYSTEM</h4>
        <h4>Users Logs Report</h4>
        <table class="pdf-table">
            <thead>
                <tr>
                    <th>Users</th>
                    <th>Date</th>
                    <th>Category/Details</th>
                </tr>
            </thead>
            <tbody>
                @if(count($filter_user_logs_table) > 0)
                    @foreach ($filter_user_logs_table as $users_logs)
                        <tr>
                            <td>
                                <div class="cust_td_info">
                                    <span class="actLogs_tdTitle">{{$users_logs->act_respo_users_fname }} {{ $users_logs->act_respo_users_lname }}</span> <br />
                                    {{-- <span class="actLogs_tdSubTitle"><span class="sub1">{{$users_logs->user_sdca_id }}</span> <span class="subDiv"> / </span> <span class="sub1"> {{ ucwords($users_logs->user_role)}}</span></span> --}}
                                    <span class="actLogs_tdSubTitle"><span class="sub1"> {{ ucwords($users_logs->user_role)}}</span></span>
                                </div>
                            </td>
                            <td>
                                <div class="d-inline">
                                    <span class="actLogs_content">{{date('F d, Y', strtotime($users_logs->created_at)) }}</span> <br />
                                    <span class="actLogs_tdSubTitle sub2">{{date('D', strtotime($users_logs->created_at))  }} {{ date('g:i A', strtotime($users_logs->created_at)) }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-inline">
                                    <span class="actLogs_content">{{$users_logs->act_type }}</span> <br />
                                    <span class="actLogs_tdSubTitle sub2">{{$users_logs->act_details }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
        </table>
    </main>
</body>
</html>