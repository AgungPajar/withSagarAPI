<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'nisn' => '1234567890',
                'name' => 'Agung Pajar',
                'class' => 'XI PPL 1',
            ],
            [
                'nisn' => '2345678901',
                'name' => 'Dewi Lestari',
                'class' => 'X AKL 2',
            ],
            [
                'nisn' => '3456789012',
                'name' => 'Budi Santoso',
                'class' => 'XI TJK 1',
            ],
            [
                'nisn' => '4567890123',
                'name' => 'Citra Ramadhani',
                'class' => 'XI DKV 3',
            ],
            [
                'nisn' => '5678901234',
                'name' => 'Fajar Pratama',
                'class' => 'X TKF 1',
            ],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }
    }
}
