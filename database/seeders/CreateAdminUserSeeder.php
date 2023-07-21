<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
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
            //  'email'=>'superadmin@roaster.com',
              'super_admin'=>'1',
             'password'=> bcrypt('11111111'),
          ],
          [
             'name'=>'Admin',
             'email'=>'admin@roaster.com',
              'is_admin'=>'1',
             'password'=> bcrypt('11111111'),
          ],
      ];

      foreach ($user as $key => $value) {
          User::create($value);
      }
    }
}
