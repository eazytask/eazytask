@php
    $start_date = Session::get('availity_start_date') ? Session::get('availity_start_date') : \Carbon\Carbon::now()->startOfYear();
    $end_date = Session::get('availity_end_date') ? Session::get('availity_end_date') : \Carbon\Carbon::now();

    $list = App\Models\Myavailability::where([
        ['employee_id', $row->id],
        ['company_code', Auth::user()->company_roles->first()->company->id],
        ['start_date', '>=', \Carbon\Carbon::parse($start_date)->toDateString()],
        // ['end_date', '<=', \Carbon\Carbon::parse($end_date)->toDateString()],
        ['start_date', '<=', \Carbon\Carbon::parse($end_date)->toDateString()],
        ['status', '>=', 'approved'],
    ])
        ->orderBy('start_date', 'desc')
        ->get();
@endphp
<div class="modal fade text-left p-md-1 p-0" id="detailModal{{ $row->id }}" role="dialog"
    aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle py-3">
                <h5 class="modal-title text-capitalize" id="myModalLabel17">{{ $row->fname }}'s Time off
                    day list</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table table-hover-animation table-bordered mb-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total</th>
                                <th>Time off Type</th>
                                <th>Time off Reason</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($list as $k => $leave)
                                <tr>
                                    <td>{{ $k + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d-m-Y') }}</td>
                                    <td>{{ $leave->total }}</td>
                                    <td>{{ $leave->leave_type ? $leave->leave_type->name : 'unspecified' }}</td>
                                    <td>{{ $leave->remarks }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bolder">
                                <td></td>
                                <td colspan="2">Total leave day</td>
                                <td colspan="2">{{ $list->sum('total') }} days</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
