<?php

namespace Database\Seeders;

use App\Models\JobType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JObTypeSeeder extends Seeder
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
               'name'=>'ht doc',
               'user_id'=>3,
               'company_code'=>'mes',
            ],
            [
                'name'=>'general',
                'user_id'=>3,
                'company_code'=>'mes',
            ],
            [
                'name'=>'general',
                'user_id'=>2,
                'company_code'=>'gsda',
            ],
        ];
  
        foreach ($user as $key => $value) {
            JobType::create($value);
        }
    }
}
