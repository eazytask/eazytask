@php

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
<table id="excel_summary_table">
    <tr>
        <td colspan="3" class="title">
            <center>
                <h3>Employee Wise Summary Report ({{ $start_date }} to {{ $end_date }})</h3>
            </center>
        </td>
    </tr>
    <tr>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td class="bordered-left">Employee</td>
        <td class="bordered-top-down">Total Hours</td>
        <td class="bordered-right">Total Amount</td>
    </tr>
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
        <tr>
            <td>
                {{ Session::get('sort_by') == 'Date' ? \Carbon\Carbon::parse($timekeeper->roaster_date)->format('d-m-Y') : $timekeeper->name }}
            </td>
            <td>{{ $duration }}</td>
            <td>$ {{ $amount }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td class="bg-primary"></td>
        <td class="bg-primary">Total Hours: {{ $total_hours }}</td>
        <td class="bg-primary">Total Amount: $ {{ $total_amount }}</td>
    </tr>
</table>
