@if(count($all_roaster)>0)
<div id="htmlContent" class="">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-browser-states border-primary m-2 text-center">
                            <div class="card-header p-75">
                                <div class="row pb-1 pt-1 ml-1 mr-1 bg-primary" style="width: 100%;">
                                    <div class="col-4 ">
                                        <p class="h6 text-light" style="color: white;">Employee Summery</p>
                                    </div>
                                    <div class="col-4 ">
                                        <p class="h6 text-light">Total Hours: {{$all_roaster['total_hours']}}</p>
                                    </div>
                                    <div class="col-4 ">
                                        <p class="h6 text-light">Total Amount: ${{$all_roaster['total_amount'] }}</p>
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
                                            @foreach($all_roaster['roasters'] as $k => $row)
                                            <tr >
                                                <td class="p-25">{{$k+1}}</td>
                                                <td class="p-25">{{ $row->name}}</td>
                                                <td class="p-25">{{ $row->total_hours }}</td>
                                                <td class="p-25">{{ $row->total_amount }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endif