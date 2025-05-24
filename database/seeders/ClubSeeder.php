<?php

namespace Database\Seeders;

use App\Models\Club;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    public function run()
    {
        $clubs = [
            ['name' => 'MPK', 'description' => 'Ekstrakurikuler MPK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'OSIS', 'description' => 'Organisasi Siswa Intra Sekolah'],
            ['name' => 'IRMA', 'description' => 'Ekstrakurikuler IRMA'],
            ['name' => 'PKS', 'description' => 'Ekstrakurikuler PKS'],
            ['name' => 'FPSH-HAM', 'description' => 'Ekstrakurikuler FPSH-HAM'],
            ['name' => 'PRAMUKA PUTRA', 'description' => 'Ekstrakurikuler Pramuka Putra'],
            ['name' => 'PRAMUKA PUTRI', 'description' => 'Ekstrakurikuler Pramuka Putri'],
            ['name' => 'PASKIBRA', 'description' => 'Ekstrakurikuler Paskibra'],
            ['name' => 'VOLY', 'description' => 'Ekstrakurikuler Voly'],
            ['name' => 'FUTSAL PUTRA', 'description' => 'Ekstrakurikuler Futsal Putra'],
            ['name' => 'FUTSAL PUTRI', 'description' => 'Ekstrakurikuler Futsal Putri'],
            ['name' => 'SEPAK BOLA', 'description' => 'Ekstrakurikuler Sepak Bola'],
            ['name' => 'BASKET', 'description' => 'Ekstrakurikuler Basket'],
            ['name' => 'HOCKEY', 'description' => 'Ekstrakurikuler Hockey'],
            ['name' => 'BADMINTON', 'description' => 'Ekstrakurikuler Badminton'],
            ['name' => 'KARATE', 'description' => 'Ekstrakurikuler Karate'],
            ['name' => 'TAEKWONDO', 'description' => 'Ekstrakurikuler Taekwondo'],
            ['name' => 'SILAT', 'description' => 'Ekstrakurikuler Silat'],
            ['name' => 'TARUNG DERAJAT', 'description' => 'Ekstrakurikuler Tarung Derajat'],
            ['name' => 'RENANG', 'description' => 'Ekstrakurikuler Renang'],
            ['name' => 'ATLETIK', 'description' => 'Ekstrakurikuler Atletik'],
            ['name' => 'KIR', 'description' => 'Ekstrakurikuler KIR'],
            ['name' => 'KOPSIS', 'description' => 'Ekstrakurikuler Kopi Siswa'],
            ['name' => 'PMR', 'description' => 'Ekstrakurikuler PMR'],
            ['name' => 'DLH', 'description' => 'Ekstrakurikuler DLH'],
            ['name' => 'SENI TARI', 'description' => 'Ekstrakurikuler Seni Tari'],
            ['name' => 'MARCHING BAND', 'description' => 'Ekstrakurikuler Marching Band'],
            ['name' => 'SENI KRIYA', 'description' => 'Ekstrakurikuler Seni Kriya'],
            ['name' => 'PADUAN SUARA', 'description' => 'Ekstrakurikuler Paduan Suara'],
            ['name' => 'KARAWITAN', 'description' => 'Ekstrakurikuler Karawitan'],
            ['name' => 'TEATER', 'description' => 'Ekstrakurikuler Teater'],
            ['name' => 'SENI MUSIK', 'description' => 'Ekstrakurikuler Seni Musik'],
            ['name' => 'IT CLUB', 'description' => 'Ekstrakurikuler IT Club'],
            ['name' => 'BROADCAST', 'description' => 'Ekstrakurikuler Broadcast'],
            ['name' => 'E-SPORT', 'description' => 'Ekstrakurikuler E-Sport'],
            ['name' => 'EC', 'description' => 'Ekstrakurikuler EC'],
            ['name' => 'KC', 'description' => 'Ekstrakurikuler KC'],
            ['name' => 'JC', 'description' => 'Ekstrakurikuler JC'],
            ['name' => 'DC', 'description' => 'Ekstrakurikuler DC'],
        ];

        foreach ($clubs as $club) {
            Club::create($club);
        }
    }
}
