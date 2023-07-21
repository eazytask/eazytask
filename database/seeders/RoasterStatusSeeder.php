<?php

namespace Database\Seeders;

use App\Models\RoasterStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoasterStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
               'name'=>'pending',
               'user_id'=>2,
               'company_code'=>'gsda',
            ],
            [
               'name'=>'Not published',
               'user_id'=>3,
               'company_code'=>'mes',
            ],
            [
                'name'=>'Published',
                'user_id'=>3,
                'company_code'=>'mes',
            ],
            [
                'name'=>'Accepted',
                'user_id'=>3,
                'company_code'=>'mes',
            ],
            [
                'name'=>'Rejected',
                'user_id'=>2,
                'company_code'=>'gsda',
            ],
        ];
  
        foreach ($user as $key => $value) {
            RoasterStatus::create($value);
        }
    }
}
