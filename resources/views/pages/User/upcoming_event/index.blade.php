@extends('layouts.Admin.master')


@section('admincontent')
    @include('sweetalert::alert')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Upcomming Event</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">

                            <li class="breadcrumb-item active">Event Lists
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="table-hover-animation">
        <div class="col-12">
            <div class="card">
                {{-- <div class="card-header">
                    <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#addEvent">Add Event</a>
                </div>
                @include(
                    'pages.Admin.upcoming_event.modals.addUpcomingeventModal'
                ) --}}

                <div class="container">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover-animation table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Venue Name</th>
                                    <th>Event Date</th>
                                    <th>Shift Start</th>
                                    <th>Shift End</th>
                                    <th>Rate</th>
                                    {{-- <th>Employee Name</th> --}}
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($upcomingevents as $row)
                                    <tr>

                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $row->project->pName }}</td>
                                        <td>{{  \Carbon\Carbon::parse($row->event_date)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->shift_start)->format('H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->shift_end)->format('H:i') }}</td>
                                        <td>{{ $row->rate }}</td>
                                        {{-- <td>{{ $row->employee->fname }}</td> --}}

                                        <td>{{ $row->remarks }}</td>

                                        <td>



                                            {{-- <button style="display: none" class="btn btn-gradient-primary btn-sm">Already
                                                Applied</button> --}}

                                            <form action="{{ route('store-event') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="event_id" value="{{ $row->id }}">



                                                @if (App\Models\Eventrequest::where('event_id',$row->id)->where('user_id',Auth::id())->count())
                                                    
                                                <button class="btn btn-gradient-primary btn-sm" disabled>Requested</button>
                                                @else
                                                <button class="btn btn-gradient-primary btn-sm" type="submit">Interested</button>
                                                @endif



                                            </form>
                                        </td>
                                    </tr>
                                    {{-- @include(
                                        'pages.Admin.upcoming_event.modals.editUpcomingeventModal'
                                    ) --}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <script>
        function interested() {
            var interested = document.getElementById('interested');
            var alreadyApplied = document.getElementById('alreadyApplied');

            alreadyApplied.style.display = "block";
            interested.style.display = "none";
        }
    </script> --}}
@endsection
