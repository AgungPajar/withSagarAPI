<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $students = [
            ['name' => 'Andi Prasetyo', 'nisn' => '1234567890', 'club_id' => 1],
            ['name' => 'Budi Santoso', 'nisn' => '0987654321', 'club_id' => 2],
            ['name' => 'Cindy Putri', 'nisn' => '1122334455', 'club_id' => 3],
            ['name' => 'Dinda Ayu', 'nisn' => '2233445566', 'club_id' => 4],
            ['name' => 'Eka Putra', 'nisn' => '3344556677', 'club_id' => 5],
            // Tambahkan lebih banyak jika diperlukan
        ];

        foreach ($students as $student) {
            Student::create($student);
        }
    }
}
