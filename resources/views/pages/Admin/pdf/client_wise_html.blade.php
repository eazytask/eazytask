<div id="htmlContent">
                <div class="row">
                    <div class="col-12">
                        @foreach($all_roaster as $i => $roster)
                        <div class="card card-browser-states {{$i % 2 == 0 ?'border-info':'border-danger'}} m-2 text-center">
                            <div class="card-header p-75">
                                <div class="row pb-1 pt-1 ml-1 mr-1 bg-primary" style="width: 100%;">
                                    <div class="col-4 ">
                                        <p class="h6 text-light" style="color: white; font-size:18px">Name: {{$roster['name']}}</p>
                                    </div>
                                    <div class="col-4 ">
                                        <p class="h6 text-light" style="color: white; font-size:18px">Hours: {{$roster['total_hours']}}</p>
                                    </div>
                                    <div class="col-4 ">
                                        <p class="h6 text-light" style="color: white; font-size:18px">Amount: ${{$roster['total_amount'] }}</p>
                                    </div>
                                </div>
                            </div>

                                @foreach($roster['projects'] as $n => $project)
                            <div class="card-body">
                                <div class="card-header border-primary bg-light-primary p-1">
                                    <h4 class="m-auto">{{$project['pName']}}</h4>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped border-primary">
                                            
                                        <tbody>
                                            <tr class="">
                                                <th rowspan="2" class="border-t border-b ">Venue</th>
                                                <th rowspan="2" class="border-t border-b">Employee</th>
                                                <th rowspan="2" class="border-t border-b">Roster Date</th>
                                                <th rowspan="2" class="border-t border-b">Duration</th>
                                                <th rowspan="2" class="border-t border-b">Rate</th>
                                                <th rowspan="2" class="border-t border-b">Amount</th>
                                                <th rowspan="2" class="border-t border-b">Remarks</th>
                                            </tr>
                                            <tr></tr>
                                            @foreach($project['roasters'] as $k => $row)
                                            <tr >
                                                <td class="p-25" >{{$row->project->pName}}</td>
                                                <td class="p-25" >{{$row->employee->fname}}</td>
                                                <td class="p-25">{{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y')}}</td>
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
                                @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
