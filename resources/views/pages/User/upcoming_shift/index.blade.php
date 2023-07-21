@extends('layouts.Admin.master')

@php
function getTime($date)
{
return \Carbon\Carbon::parse($date)->format('H:i');
}

$start_date= null;
$end_date= null;
if(Session::get('upcomingFromDate') && Session::get('upcomingToDate')){
$start_date = Session::get('upcomingFromDate')->format('d-m-Y');
$end_date = Session::get('upcomingToDate')->format('d-m-Y');
}

@endphp
@section('admincontent')
<div class="card plan-card p-md-4">
    <div class="card-header text-primary border-top-0 border-left-0 border-right-0">
        <h3 class="card-title text-primary d-inline">
            Select Roster Dates
        </h3>
    </div>

    <div class="card-body">

        <form action="{{ route('upcoming-shift-search') }}" method="POST" id="dates_form">
            @csrf
            <div class="row row-xs">
                <div class="col-md-5 col-lg-4  mt-1">
                    <input type="text" name="start_date" required class="form-control format-picker" placeholder="Start Date" value="{{$start_date}}" />
                </div>
                <div class="col-md-5 col-lg-4  mt-1">

                    <input type="text" name="end_date" required class="form-control format-picker" placeholder="End Date" value="{{$end_date}}" />
                </div>

                <div class="col-md-2 col-lg-3 mt-1">
                    <button type="submit" class="btn btn btn-outline-primary btn-block" id="btn_search">Search</button>
                </div>
            </div>
        </form>
    </div>
    <!-- Button trigger modal -->

    <div class="row" id="table-hover-animation">
        <div class="col-12">
            <div class="card">
                <div class="container">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover-animation">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Venue</th>
                                    <th>Roster Date</th>
                                    <th>Shift Start</th>
                                    <th>Shift End</th>
                                    <th>Duration</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roasters as $k => $row)

                                <tr>
                                    <td>{{ $k + 1 }}</td>
                                    <td>
                                        @if (isset($row->project->pName))
                                        {{ $row->project->pName }}
                                        @else
                                        Null
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y')}}
                                    </td>
                                    <td>{{ getTime($row->shift_start) }}</td>
                                    <td>{{ getTime($row->shift_end) }}</td>
                                    <td>{{ $row->duration }}</td>
                                    <td>{{ $row->ratePerHour }}</td>
                                    <td>{{ $row->amount }}</td>

                                </tr>
                                @endforeach
                                <tr class="text-center" {{$roasters->count()==0?'':'hidden'}}>
                                    <td colspan="8">No data found!</td>
                                </tr>
                                <tr class="bg-light">
                                    <td>#</td>
                                    <td colspan="4" class="text-center">Total</td>
                                    <td>{{$roasters->sum('duration')}} hours</td>
                                    <td></td>
                                    <td>$ {{$roasters->sum('amount')}}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection