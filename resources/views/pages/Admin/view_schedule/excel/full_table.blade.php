@php

    function getTime($date)
    {
        return \Carbon\Carbon::parse($date)->format('H:i');
    }
    $curr_emp = Session::get('current_employee');

    $start_date = null;
    $end_date = null;
    if (Session::get('fromDate') && Session::get('toDate')) {
        $start_date = Session::get('fromDate')->format('d-m-Y');
        $end_date = Session::get('toDate')->format('d-m-Y');
    }

    //pdf
    $all_roaster = [];
@endphp
<table id="excel_full_table">
    <thead>
        <tr>
            <td colspan="15" class="title">
                <center>
                    <h3>Employee Wise Report ({{ $start_date }} to {{ $end_date }})</h3>
                </center>
            </td>
        </tr>
        <tr>
            <td colspan="15"></td>
        </tr>
        <tr>
            <td colspan="15"></td>
        </tr>
        <tr>
            <th class="bordered-left"><input type="checkbox" class="mt-75 taskcheckAllID" onclick="taskcheckAllID()"></th>
            <th class="bordered-top-down">
                <h5>Employee Name</h5>
            </th>
            <th class="bordered-top-down">
                <h5>Venue</h5>
            </th>
            <th class="bordered-top-down">
                <h5>Roster Date</h5>
            </th>
            <th class="bordered-top-down">
                <h5>Shift Start</h5>
            </th>
            <th class="bordered-top-down">
                <h5>Shift End</h5>
            </th>
            <th class="bordered-top-down">
                <h5>Sign In</h5>
            </th>
            <th class="bordered-top-down">
                <h5>Sign Out</h5>
            </th>
            <th class="bordered-top-down">
                <h5>Rate</h5>
            </th>
            <th class="bordered-top-down">
                <h5>App. Start</h5>
            </th>
            <th class="bordered-top-down">
                <h5>App. End</h5>
            </th>
            <th class="bordered-top-down">
                <h5>App. Rate</h5>
            </th>
            <th class="bordered-top-down">
                <h5>App. Duration</h5>
            </th>
            <th class="bordered-top-down">
                <h5>App. Amount</h5>
            </th>
            <th class="bordered-right">
                <h5>Details</h5>
            </th>
        </tr>
    </thead>

    <tbody>
        @php
            $total_hours = 0;
            $total_amount = 0;
        @endphp
        @foreach ($timekeepers as $i => $timekeeper)
            @php

                if (Session::get('sort_by')) {
                    $sort = Session::get('sort_by');
                    if ($sort == 'Venue') {
                        $filter_sort_by = ['project_id', $timekeeper->id];
                    } elseif ($sort == 'Date') {
                        $filter_sort_by = ['roaster_date', $timekeeper->id];
                    } elseif ($sort == 'Client') {
                        $filter_sort_by = ['client_id', $timekeeper->id];
                    } else {
                        $filter_sort_by = ['employee_id', $timekeeper->id];
                    }
                } else {
                    $filter_sort_by = ['employee_id', $timekeeper->id];
                }
                $filter_roaster_type = Session::get('schedule') && Session::get('schedule') != 'All' ? ['roaster_type', Session::get('schedule')] : ['employee_id', '>', 0];
                $filter_employee = Session::get('employee_id') ? ['employee_id', Session::get('employee_id')] : ['employee_id', '>', 0];
                $filter_project = Session::get('project_id') ? ['project_id', Session::get('project_id')] : ['employee_id', '>', 0];
                //$filter_client = Session::get('client_id') ? ['client_id',Session::get('client_id')]:['employee_id','>',0];

                $fromDate = Session::get('fromDate');
                $toDate = Session::get('toDate');

                $timekeeperDataExcel = App\Models\TimeKeeper::where([
                    ['company_code', Auth::user()->company_roles->first()->company->id],
                    $filter_sort_by,
                    $filter_roaster_type,
                    $filter_employee,
                    $filter_project,
                    //$filter_client
                ])
                    ->orderBy('roaster_date', 'asc')
                    ->orderBy('shift_start', 'asc')
                    ->whereBetween('roaster_date', [$fromDate, $toDate])
                    //->where(function ($q) {
                    //avoid_rejected_key($q);
                    //})
                    ->get();

                $duration = $timekeeperDataExcel->sum('duration');
                $amount = $timekeeperDataExcel->sum('amount');
                $total_hours += floatval($duration);
                $total_amount += floatval($amount);
            @endphp
            @foreach ($timekeeperDataExcel as $k => $row)
                @php
                    $json = json_encode($row->toArray(), false);

                @endphp
                <tr
                    class="{{ $row->roaster_type == 'Unschedueled' ? 'bg-light-primary' : '' }} {{ $row->sing_in ? '' : 'bg-light-danger' }}">
                    <td class="p-0">
                        @if ($row->is_approved)
                            <i data-feather="{{ $row->payment_status ? 'dollar-sign' : 'check-circle' }}"
                                class="text-primary"></i>
                        @else
                            <input type="checkbox" class="taskCheckID" value="{{ $row->id }}">
                        @endif
                        {{ $k + 1 }}
                    </td>
                    <td>
                        {{ $row->employee->fname }}
                    </td>
                    <td>
                        @if (isset($row->project->pName))
                            {{ $row->project->pName }}
                        @else
                            Null
                        @endif
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($row->roaster_date)->format('d-m-Y') }}
                    </td>
                    <td>
                        {{ getTime($row->shift_start) }}
                    </td>
                    <td>
                        {{ getTime($row->shift_end) }}
                    </td>
                    <td class="">
                        {{ $row->sing_in ? getTime($row->sing_in) : 'unspecified' }}
                    </td>
                    <td class="">
                        {{ $row->sing_out ? getTime($row->sing_out) : 'unspecified' }}
                    </td>
                    <td>
                        {{ $row->ratePerHour }}
                    </td>
                    <td class="">
                        {{ getTime($row->Approved_start_datetime) }}
                    </td>
                    <td class="">
                        {{ getTime($row->Approved_end_datetime) }}
                    </td>
                    <td>{{ $row->app_rate }}</td>
                    <td>{{ $row->app_duration }}</td>
                    <td>{{ $row->app_amount }}</td>
                    <td>
                        {{ $row->remarks }}
                    </td>

                </tr>
            @endforeach
            <tr>
                <td colspan=12 class="bg-primary">
                </td>
                <td class="bg-primary">Hours : {{ $duration }}</td>
                <td class="bg-primary">Amount : $ {{ $amount }}</td>
                <td class="bg-primary"></td>
            </tr>
            <tr>
                <td colspan="15"></td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan=12 class="bg-primary">
            </td>
            <td class="bg-primary">Total Hours : {{ $total_hours }}</td>
            <td class="bg-primary">Total Amount : $ {{ $total_amount }}</td>
            <td class="bg-primary"></td>
        </tr>
    </tfoot>
</table>
