@extends('layouts.Admin.master')

@php
    function getTime($date){
        return \Carbon\Carbon::parse($date)->format('H:i');
    }

    $start_date= null;
    $end_date= null;
    if(Session::get('pastFromDate') && Session::get('pastToDate')){
        $start_date = Session::get('pastFromDate')->format('d-m-Y');
        $end_date = Session::get('pastToDate')->format('d-m-Y');
    }
@endphp
@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Event Lists
        @endslot
        @slot('title')
            Past Shifts
        @endslot
    @endcomponent
    <div class="card plan-card">
        <div class="card-header text-primary border-top-0 border-left-0 border-right-0">
            <h3 class="card-title text-primary d-inline">
                Select Roster Dates
            </h3>
        </div>

        <div class="card-body">
            <form action="{{ route('past-shift-search') }}" method="POST" id="dates_form">
                @csrf
                <div class="row row-xs g-2">
                    <div class="col-md-5 col-lg-4  mt-1">
                        <input type="text" name="start_date" required class="form-control format-picker" placeholder="Start Date" value="{{$start_date}}" />
                    </div>
                    <div class="col-md-5 col-lg-4  mt-1">

                        <input type="text" name="end_date" required class="form-control format-picker" placeholder="End Date" value="{{$end_date}}" />
                    </div>

                    <div class="col-md-2 col-lg-3 mt-1">
                        <button type="submit" class="btn btn btn-primary btn-block" id="btn_search">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Button trigger modal -->

        <div class="row" id="table-hover-animation">
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

                        <tr class="{{$row->roaster_type=='Unschedueled'?'bg-light-primary':''}}">
                            <td class="p-0 pl-50">
                                @if($row->is_approved)
                                <i data-feather='check-circle' class="text-primary"></i>
                                @else
                                <span class="pl-1 ml-25"></span>
                                @endif
                                {{ $k + 1 }}
                            </td>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection