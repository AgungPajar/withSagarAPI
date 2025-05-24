<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $attendances = [
            ['student_id' => 1, 'date' => '2025-04-06', 'status' => 'hadir'],
            ['student_id' => 2, 'date' => '2025-04-06', 'status' => 'tidak hadir'],
            ['student_id' => 3, 'date' => '2025-04-06', 'status' => 'hadir'],
        ];

        foreach ($attendances as $attendance) {
            Attendance::create($attendance);
        }
    }
}