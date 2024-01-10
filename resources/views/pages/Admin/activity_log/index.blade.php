@extends('layouts.Admin.master')

@php

    $fromRoaster = '';
    $toRoaster = '';
    if (Session::get('fromRoaster')) {
        $fromRoaster = \Carbon\Carbon::parse(Session::get('fromRoaster'))->format('d-m-Y');
    }
    if (Session::get('toRoaster')) {
        $toRoaster = \Carbon\Carbon::parse(Session::get('toRoaster'))->format('d-m-Y');
    }
@endphp

@section('admincontent')
    <div class="col-lg-12 col-md-12">
        <div class="row" id="table-hover-animation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>All activity log</h3>
                        <div class=" pb-0">
                            <form action="{{ route('log.search') }}" method="POST" id="dates_form">
                                @csrf
                                <div class="row row-xs">
                                    <div class="col-lg-4">
                                        <input type="text" name="start_date" required class="form-control format-picker"
                                            placeholder="From" value="{{ $fromRoaster }}" />
                                    </div>
                                    <div class="col-lg-4 mt-25 mt-md-0 ">
                                        <input type="text" name="end_date" required class="form-control format-picker"
                                            placeholder="To" value="{{ $toRoaster }}" />
                                    </div>
                                    <div class="col-md-2 col-lg-3 mt-25 mt-md-0">
                                        <button type="submit" class="btn btn btn-outline-primary btn-block"
                                            id="btn_search"><i data-feather='search'></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <!-- <th>#</th> -->
                                        <th>Time</th>
                                        <th>Activity</th>
                                        <th>Details</th>
                                        <th>Action By</th>
                                    </tr>
                                </thead>
                                <tbody id="tBody">
                                    @foreach ($activity as $k => $row)
                                        @php
                                            if ($row->event == 'deleted') {
                                                $text_color = 'text-danger';
                                            } elseif ($row->event == 'created') {
                                                $text_color = 'text-success';
                                            } else {
                                                $text_color = 'text-primary';
                                            }

                                            $update = \Carbon\Carbon::parse($row->updated_at)->format('d,M,Y H:i');
                                        @endphp

                                        <tr class="{{ $text_color }}">
                                            <!-- <td>{{ $k + 1 }}</td> -->
                                            <td data-sort="{{ $update }}">{{ $update }}</td>
                                            <td>{{ $row->description }}</td>
                                            <td>
                                                <div style="width: 320px;" class="m-auto">
                                                    @if ($row->event == 'deleted')
                                                        {{ json_encode($row->properties['old']) }}
                                                    @else
                                                        {{ json_encode($row->properties['attributes']) }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $row->causer->name ?? '' }} {{ $row->causer->mname ?? '' }}
                                                {{ $row->causer->lname ?? '' }}

                                            </td>
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
    </div>
    </div>

    </div>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                pageLength: 50,
                aaSorting: []
            });

            // $('#myTable').DataTable({
            //     autoWidth: false, //step 1
            //     columnDefs: [{
            //             width: '300px',
            //             targets: 0
            //         }, //step 2, column 1 out of 4
            //         {
            //             width: '300px',
            //             targets: 1
            //         }, //step 2, column 2 out of 4
            //         {
            //             width: '300px',
            //             targets: 2
            //         } //step 2, column 3 out of 4
            //     ]
            // });
        })
    </script>
@endsection
