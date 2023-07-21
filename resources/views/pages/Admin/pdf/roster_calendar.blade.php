@php
    $data = Session::get('roster_calendar_pdf');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roster Report</title>
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/colors.css')}}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/colors.css')}}"> -->
    <style>
        body {
            font-size: 12px !important;
        }

        @page {
            margin: 15px;
        }
    </style>

</head>

<body>
<div id="htmlContent">
    <div id="content">
        <div class="row" id="table-hover-animation">
            <div class="col-12">
                <div class="card border-primary m-2 pb-1">
                    <div class="card-header text-center">
                        <div class="" id="logo">
                            <!-- <img src="" alt="" class="ml-1" height="42px"> -->
                        </div>
                        <div class="">
                            <h6 id="print_current_week" class="mr-1"></h6>
                        </div>
                    </div>
                    <div class="card-header bg-primary m-1 p-1">
                        <h6 id="print_client" class="text-uppercase text-light"></h6>
                        <h6 id="print_project" class="text-uppercase text-light"></h6>
                        <h6 id="print_hours" class="text-light"></h6>
                        <h6 id="print_amount" class="text-light"></h6>
                    </div>
                    
                    <div class="container">
                        <div class="">
                            <table class="table-bordered text-center" id="printTable" style='width:100%'>
                                <thead>
                                    <tr>
                                        <th style='width:10%'>Employee Name</th>
                                        <th style='width:12%'>Monday</th>
                                        <th style='width:12%'>Tuesday</th>
                                        <th style='width:12%'>Wednesday</th>
                                        <th style='width:12%'>Thursday</th>
                                        <th style='width:12%'>Friday</th>
                                        <th style='width:12%'>Saturday</th>
                                        <th style='width:12%'>Sunday</th>
                                    </tr>
                                </thead>
                                <tbody id="print_tBody">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function setData(){
        let data = data
        $('#print_tBody').html(data.report);
                $('#print_client').html('Client: ' + data.client);
                $('#print_project').html('Venue: ' + data.project);
                $('#print_hours').html('Total Hours: ' + data.hours);
                $('#print_amount').html('Total Amount: $' + data.amount);
                $('#print_current_week').text('Date: ' + data.week_date)
    }
    setData()
</script>
</body>
</html>