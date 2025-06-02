<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        Attendance::insert([
            [
                'student_id' => 1,
                'club_id' => 1, // 🟢 tambahkan club_id
                'date' => Carbon::parse('2025-04-06'),
                'status' => 'hadir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // tambahkan data lainnya jika perlu
        ]);
    }
}
