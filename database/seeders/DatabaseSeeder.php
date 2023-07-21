<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
            EmployeeSeeder::class,
            JObTypeSeeder::class,
            ProjectSeeder::class,
            RoasterStatusSeeder::class
        ]);
        \App\Models\Client::factory(10)->create();
        \App\Models\TimeKeeper::factory(50)->create();
    }
}
