<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
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
               'name'=>'SuperAdmin',
               'email'=>'superadmin@roaster.com',
                'super_admin'=>'1',
               'password'=> bcrypt('11111111'),
            ],
            [
               'name'=>'Admin',
               'email'=>'admin@roaster.com',
                'is_admin'=>'1',
               'password'=> bcrypt('11111111'),
            ],
            [
               'name'=>'ahsan',
               'email'=>'iran0601@yahoo.com',
               'is_admin'=>'1',
               'image'=>'1650845821A5AD6B96-9018-453F-954E-B64A630929C6.jpeg',
               'password'=> bcrypt('11111111'),
            ],
            [
               'name'=>'employee',
               'email'=>'employee@roaster.com',
               'password'=> bcrypt('11111111'),
            ],
            [
               'name'=>'altamash',
               'email'=>'iran2008@gmail.com',
               'image'=>'1653517953FEBBC148-DDF8-4B0B-BED2-0CEA7ADF1D10.jpeg',
               'password'=> bcrypt('11111111'),
            ],
            [
               'name'=>'shamim',
               'email'=>'shamimhossen6622@gmail.com',
               'password'=> bcrypt('11111111'),
            ],
        ];
  
        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
