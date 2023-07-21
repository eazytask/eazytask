<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
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
               'user_id'=>'3',
                'company_code'=>'MES',
               'company'=> 'MES',
            ],
            [
                'user_id'=>'2',
                 'company_code'=>'gsda',
                'company'=> 'gsda',
            ],
        ];
  
        foreach ($user as $key => $value) {
            Company::create($value);
        }
    }
}
