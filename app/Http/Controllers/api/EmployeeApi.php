<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TimeKeeper;
use Illuminate\Http\Request;

class EmployeeApi extends Controller
{
    public function employee_shift_details(){
        $shift_details = TimeKeeper::leftjoin('companies', 'companies.id', 'time_keepers.company_code')
        ->leftjoin('employees', 'employees.id', 'time_keepers.employee_id')
        ->leftjoin('clients', 'clients.id', 'time_keepers.client_id')
        ->leftjoin('projects', 'projects.id', 'time_keepers.project_id')
        ->select('time_keepers.id as report_id', 'time_keepers.*', 'employees.*', 'companies.*', 'clients.*', 'projects.*')
        ->get();
        return response()->json([
            'status'=> true,
            'data'=> $shift_details
        ]);
    }

}
