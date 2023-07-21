<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TimeKeeper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Type\Decimal;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function order_chart()
    {
        $start_week = Carbon::now()->subWeeks(2)->startOfWeek();
        $end_week =  Carbon::now()->subWeeks(2)->endOfWeek();
        $total = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()],
        ])->whereBetween('roaster_date', [$start_week, $end_week])->sum('duration');
        // return $start_week->format('D d-m-y');
        $data['total'] = $total;
        $data = [];
        $data[0] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()],
        ])->where('roaster_date', $start_week)->sum('duration');
        $data[1] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()],
        ])->where('roaster_date', $start_week->addDays())->sum('duration');
        $data[2] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()],
        ])->where('roaster_date', $start_week->addDays())->sum('duration');
        $data[3] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()],
        ])->where('roaster_date', $start_week->addDays())->sum('duration');
        $data[4] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()],
        ])->where('roaster_date', $start_week->addDays())->sum('duration');
        $data[5] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()],
        ])->where('roaster_date', $start_week->addDays())->sum('duration');
        $data[6] = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
            ['user_id', Auth::id()],
        ])->where('roaster_date', $start_week->addDays())->sum('duration');

        // foreach($data as $i => $val){
        //     $data[$i] = round(($val * 100 )/$total,2);
        // }
        return response()->json($data);
    }

    public function revenue_report_chart()
    {

        $amount = [];
        for ($i = 0; $i < 6; $i++) {
            $amount['amount'][$i] = -(int)TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->where([
                ['user_id', Auth::id()],
            ])->whereBetween('roaster_date', [Carbon::now()->subMonths($i)->startOfMonth(), Carbon::now()->subMonths($i)->endOfMonth()])->sum('amount');
            $earn = ($amount['amount'][$i] * 10 / 100);
            $amount['revenue'][$i] = abs($earn + $amount['amount'][$i]);
        }

        return response()->json([
            'amount' => array_reverse($amount['amount']),
            'revenue' => array_reverse($amount['revenue'])
        ]);
    }

    public function client_portion_chart()
    {
        $start_week = Carbon::now()->subWeeks(2)->startOfWeek();
        $end_week =  Carbon::now()->subWeeks(2)->endOfWeek();

        $timekeepers = TimeKeeper::where(function ($q) {
            avoid_rejected_key($q);
        })->select(DB::raw('round(sum(amount)) as amount, round(sum(duration)) as hours , client_id'))
            ->groupBy('client_id')
            ->orderByRaw('amount desc')
            ->whereBetween('roaster_date', [$start_week, $end_week])
            ->where([
                ['user_id', Auth::id()],
            ])
            ->get();
        $total = $timekeepers->sum('amount');

        $data = [];
        $data['percentage'] = [];
        $data['labels'] = [];
        foreach ($timekeepers as $i => $val) {
            $data['percentage'][$i] = round(($val->amount * 100) / $total, 2);
            $data['labels'][$i] = $val->client->cname;
        }

        return response()->json([
            'labels' => $data['labels'],
            'percentage' => $data['percentage'],
        ]);
    }
}
