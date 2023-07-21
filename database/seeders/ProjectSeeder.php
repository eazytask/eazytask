<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
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
               'pName'=>'project',
               'user_id'=>3,
               'company_code'=>'mes',
            ],
            [
                'pName'=>'hilsha',
                'user_id'=>3,
                'company_code'=>'mes',
            ],
            [
                'pName'=>'jamuna',
                'user_id'=>2,
                'company_code'=>'gsda',
            ],
        ];
  
        foreach ($user as $key => $value) {
            Project::create($value);
        }
    }
}
