@if($start_date && $end_date)
<div>
    <div class="row">
        <div class="col-12">
            <p class="h4 text-center mt-2">
                <span class="border-bottom">
                {{Session::get('sort_by')?Session::get('sort_by'):'Employee'}} Wise Report (from {{\Carbon\Carbon::parse($start_date)->format('d-m-Y')}} to {{\Carbon\Carbon::parse($end_date)->format('d-m-Y')}})
                </span>
            </p>
        </div>
        <div class="col-12">
            @foreach($all_roaster as $i => $roster)
            <div class="card card-browser-states border-primary m-2 text-center">
                <div class="card-header p-75">
                    <div class="row pb-1 pt-1 ml-1 mr-1 bg-primary" style="width: 100%;">
                        <div class="col-4 ">
                            <p class="h6 text-light" style="color: white;">Name: {{$roster['name']}}</p>
                        </div>
                        <div class="col-4 ">
                            <p class="h6 text-light">Hours: {{$roster['total_hours']}}</p>
                        </div>
                        <div class="col-4 ">
                            <p class="h6 text-light">Amount: ${{$roster['total_amount'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped">

                            <tbody>
                                <tr class="">
                                    <th class="p-0 border-l" rowspan="2"></th>
                                    <th rowspan="2" class="border-t border-b {{!Session::get('sort_by') || Session::get('sort_by')=='Employee'?'hidden':''}}">Employee</th>
                                    <th rowspan="2" class="border-t border-b {{Session::get('sort_by') && Session::get('sort_by')=='Venue'?'hidden':''}}">Site</th>
                                    <th rowspan="2" class="border-t border-b {{Session::get('sort_by') && Session::get('sort_by')=='Date'?'hidden':''}}">Roster Date</th>
                                    <th colspan="2" class="p-0 border-bottom-success border-t">Roster</th>
                                    <th colspan="2" class="p-0 border-bottom-info border-t">Approved</th>
                                    <th rowspan="2" class="border-t border-b">Duration</th>
                                    <th rowspan="2" class="border-t border-b">Rate</th>
                                    <th rowspan="2" class="border-t border-b">Amount</th>
                                    <th rowspan="2" class="border-t border-b border-r">Remarks</th>
                                </tr>
                                <tr class="">
                                    <td class="p-0 border-b">Start</td>
                                    <td class="p-0 border-b">End</td>
                                    <td class="p-0 border-b">Start</td>
                                    <td class="p-0 border-b">End</td>
                                </tr>
                                @foreach($roster['roasters'] as $k => $row)
                                <tr>
                                    <td class="p-0"></td>
                                    <td class="p-25 {{!Session::get('sort_by') || Session::get('sort_by')=='Employee'?'hidden':''}}">{{$row->employee->fname}}</td>
                                    <td class="p-25 {{Session::get('sort_by') && Session::get('sort_by')=='Venue'?'hidden':''}}">{{$row->project->pName}}</td>
                                    <td class="p-25 {{Session::get('sort_by') && Session::get('sort_by')=='Date'?'hidden':''}}">{{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y')}}</td>
                                    <td class="p-25">{{ getTime($row->shift_start) }}</td>
                                    <td class="p-25">{{ getTime($row->shift_end) }}</td>
                                    <td class="p-25">{{ getTime($row->Approved_start_datetime) }}</td>
                                    <td class="p-25">{{ getTime($row->Approved_end_datetime) }}</td>
                                    <td class="p-25">{{$row->duration}}</td>
                                    <td class="p-25">{{$row->ratePerHour}}</td>
                                    <td class="p-25">{{$row->amount}}</td>
                                    <td class="p-25">{{$row->remarks}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif