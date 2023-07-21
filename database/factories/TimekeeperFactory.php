<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Timekeeper>
 */
class TimekeeperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $k = rand(2,3);
        $company= [];
        $company[3]= 'mes';
        $company[2]= 'gsda';
        $d= rand(1,10);
        return [
            'user_id' => $k,
            'company_code' => $company[$k],
            'employee_id' => rand(1,3),
            'client_id' => rand(1,9),
            'project_id' => rand(1,3),
            'job_type_id' => rand(1,3),
            'roaster_status_id' => rand(1,5),
            'roaster_date' => Carbon::now()->addDays($d),
            'shift_start' => Carbon::now()->addDays($d)->addHours($d),
            'shift_end' => Carbon::now()->addDays($d)->addHours($d+4),
            'duration' => 4,
            'ratePerHour' => 10,
            'amount' => 40,
            'Approved_start_datetime' => Carbon::now()->addDays($d)->addHours($d),
            'Approved_end_datetime' => Carbon::now()->addDays($d)->addHours($d),
        ];
    }
}
