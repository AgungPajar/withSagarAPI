<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ClubSeeder::class,
            StudentSeeder::class,
            UserSeeder::class,
            AttendanceSeeder::class,
        ]);
    }
}