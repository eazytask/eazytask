<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
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
               'userID'=>5,
               'user_id'=>3,
               'fname'=>'altamash',
                'company'=>'gsda',
               'email'=> 'iran2008@gmail.com',
            ],
            [
               'userID'=>5,
               'user_id'=>2,
               'fname'=>'altamash',
                'company'=>'mes',
               'email'=> 'iran2008@gmail.com',
            ],
            [
               'userID'=>4,
               'user_id'=>2,
               'fname'=>'employee name',
                'company'=>'mes',
               'email'=> 'employee@roaster.com',
            ],
            [
               'userID'=>6,
               'user_id'=>1,
               'fname'=>'shamim',
                'company'=>'gsda',
               'email'=> 'shamimhossen6622@gmail.com',
            ],
        ];
  
        foreach ($user as $key => $value) {
            Employee::create($value);
        }
    }
}
