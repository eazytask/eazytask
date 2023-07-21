@if($start_date && $end_date)
<div class="row">
        <div class="col-12">
            <p class="h4 text-center mt-2">
                <span class="border-bottom">
                {{Session::get('sort_by')?Session::get('sort_by'):'Employee'}} Wise Summery Report (from {{\Carbon\Carbon::parse($start_date)->format('d-m-Y')}} to {{\Carbon\Carbon::parse($end_date)->format('d-m-Y')}})
                </span>
            </p>
        </div>
                    <div class="col-12">
                        <div class="card card-browser-states border-primary m-2 text-center">
                            
                        <div class="card-header p-75">
                                <div class="row pb-1 pt-1 ml-1 mr-1 bg-primary" style="width: 100%;">
                                    <div class="col-4 ">
                                        <p class="h6 text-light" style="color: white;">{{Session::get('sort_by')?Session::get('sort_by'):'Employee'}} Summery</p>
                                    </div>
                                    <div class="col-4 ">
                                        <p class="h6 text-light">Total Hours: {{array_sum(array_column($all_roaster, 'total_hours'))}}</p>
                                    </div>
                                    <div class="col-4 ">
                                        <p class="h6 text-light">Total Amount: ${{array_sum(array_column($all_roaster, 'total_amount'))}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                            
                                        <tbody>
                                            <tr class="">
                                                <th class="border-t border-b border-l">#</th>
                                                <th class="border-t border-b">Name</th>
                                                <th class="border-t border-b">Duration</th>
                                                <th class="border-t border-b border-r">Amount</th>
                                            </tr>
                                            @foreach($all_roaster as $k => $row)
                                            <tr >
                                                <td class="p-25">{{$k+1}}</td>
                                                <td class="p-25">{{ $row['name']}}</td>
                                                <td class="p-25">{{ $row['total_hours'] }}</td>
                                                <td class="p-25">{{ $row['total_amount'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endif