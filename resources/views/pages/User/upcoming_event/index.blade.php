@extends('layouts.Admin.master')


@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Event Lists
        @endslot
        @slot('title')
            Upcomming Event
        @endslot
    @endcomponent


    <div class="card">
        <div class="card-body">
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
                                        <div class="dropdown">
                                            <button class="btn btn-soft-info btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><button class="dropdown-item" disabled>Requested</button></li>
                                            </ul>
                                        </div>
                                        @else
                                        <div class="dropdown">
                                            <button class="btn btn-soft-info btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><button class="dropdown-item" type="submit">Interested</button></li>
                                            </ul>
                                        </div>
                                        @endif
    
    
    
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
