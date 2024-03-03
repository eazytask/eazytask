@extends('layouts.Admin.master')


@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Report
        @endslot
        @slot('title')
            Sign-In Report
        @endslot
    @endcomponent
    <!-- Basic Tables start -->
    <!-- Table Hover Animation start -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body text-primary">
                    <div class="row row-xs" id="filter">
                        <div class="col-lg-4 pl-25 pr-25 mt-1">
                            <input type="text" name="start_date" id="start_date" required
                                class="form-control format-picker" placeholder="Roster Start"
                                value="{{ \Carbon\Carbon::now()->startOfWeek()->format('d-m-Y') }}" />
                        </div>
                        <div class="col-lg-4 pl-25 pr-25 mt-1">
                            <input type="text" name="end_date" id="end_date" required class="form-control format-picker"
                                placeholder="Roster End"
                                value="{{ \Carbon\Carbon::now()->endOfWeek()->format('d-m-Y') }}" />
                        </div>

                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="employee_id" id="employee_id">
                                <option value="all" selected>All Employee</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}"
                                        {{ Session::get('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->fname }} {{ $emp->mname }} {{ $emp->lname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="project_id" id="project_id">
                                <option value="all" selected>All Venue</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ Session::get('project_id') == $project->id ? 'selected' : '' }}>{{ $project->pName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 pl-25 pr-25 mt-1">
                            <select class="form-control select2" name="type" id="type">
                                <option value="all" selected>All Types</option>
                                <option value="sign_in">Who Sign In</option>
                                <option value="not_sign_in">Who Not Sign In</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="card p-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="" class="table table-striped table-bordered mb-1">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Shift Start</th>
                                    <th>Shift Out</th>
                                    <th>Sign In</th>
                                    <th>Sign Out</th>
                                    <th>Sign In Image</th>
                                    <th>Sign Out Image</th>
                                    <th>Sign In Comment</th>
                                    <th>Sign Out Comment</th>
                                    <th>Site</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody id="reportBody">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    <!-- Table head options end -->
    <!-- Basic Tables end -->
@endsection

@push('scripts')
    @include('sweetalert::alert')
    @include('components.select2')
    <script>
        $(document).ready(function() {

            // select input placeholder
            $('#type').wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Select Type',
                dropdownParent: $('#type').parent()
            });

            fetchReports = function() {
                $.ajax({
                    url: '/admin/home/sign/in/status/search',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        'project': $('#project_id').val(),
                        'employee': $('#employee_id').val(),
                        'start': $('#start_date').val(),
                        'end': $('#end_date').val(),
                        'type': $('#type').val(),
                    },
                    success: function(data) {
                        if (data.data) {
                            $('#reportBody').html(data.data)
                        } else {
                            $('#reportBody').html(
                                '<tr><td colspan="11" class="text-center p-2">No data found!</td></tr>'
                                )
                        }
                        feather.replace({
                            width: 14,
                            height: 14
                        });
                    },
                    error: function(err) {
                        console.log(err)
                    }
                });
            }
            fetchReports()

            $(document).on("change", "#filter", function() {
                fetchReports()
            })

        })
    </script>
@endpush
