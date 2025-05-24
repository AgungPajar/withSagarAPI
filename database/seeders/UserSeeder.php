<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Akun Admin Pusat (MPK dan OSIS)
        User::create([
            'name' => 'MPK',
            'username' => 'mpk',
            'email' => 'mpk@example.com',
            'password' => Hash::make('password'),
            'role' => 'osis'
        ]);

        User::create([
            'name' => 'OSIS',
            'username' => 'osis',
            'email' => 'osis@example.com',
            'password' => Hash::make('password'),
            'role' => 'osis'
        ]);

        // Daftar Ekskul
        $clubs = [
            ['name' => 'IRMA', 'id' => 3],
            ['name' => 'PKS', 'id' => 4],
            ['name' => 'FPSH-HAM', 'id' => 5],
            ['name' => 'PRAMUKA PUTRA', 'id' => 6],
            ['name' => 'PRAMUKA PUTRI', 'id' => 7],
            ['name' => 'PASKIBRA', 'id' => 8],
            ['name' => 'VOLY', 'id' => 9],
            ['name' => 'FUTSAL PUTRA', 'id' => 10],
            ['name' => 'FUTSAL PUTRI', 'id' => 11],
            ['name' => 'SEPAK BOLA', 'id' => 12],
            ['name' => 'BASKET', 'id' => 13],
            ['name' => 'HOCKEY', 'id' => 14],
            ['name' => 'BADMINTON', 'id' => 15],
            ['name' => 'KARATE', 'id' => 16],
            ['name' => 'TAEKWONDO', 'id' => 17],
            ['name' => 'SILAT', 'id' => 18],
            ['name' => 'TARUNG DERAJAT', 'id' => 19],
            ['name' => 'RENANG', 'id' => 20],
            ['name' => 'ATLETIK', 'id' => 21],
            ['name' => 'KIR', 'id' => 22],
            ['name' => 'KOPSIS', 'id' => 23],
            ['name' => 'PMR', 'id' => 24],
            ['name' => 'DLH', 'id' => 25],
            ['name' => 'SENI TARI', 'id' => 26],
            ['name' => 'MARCHING BAND', 'id' => 27],
            ['name' => 'SENI KRIYA', 'id' => 28],
            ['name' => 'PADUAN SUARA', 'id' => 29],
            ['name' => 'KARAWITAN', 'id' => 30],
            ['name' => 'TEATER', 'id' => 31],
            ['name' => 'SENI MUSIK', 'id' => 32],
            ['name' => 'IT CLUB', 'id' => 33],
            ['name' => 'BROADCAST', 'id' => 34],
            ['name' => 'E-SPORT', 'id' => 35],
            ['name' => 'EC', 'id' => 36],
            ['name' => 'KC', 'id' => 37],
            ['name' => 'JC', 'id' => 38],
            ['name' => 'DC', 'id' => 39],
        ];

        foreach ($clubs as $club) {
            User::create([
                'name' => $club['name'],
                'username' => strtolower(str_replace(' ', '-', $club['name'])),
                'email' => strtolower(str_replace(' ', '-', $club['name']) . '@example.com'),
                'password' => Hash::make('password'),
                'role' => 'club_pengurus',
                'club_id' => $club['id']
            ]);
        }
    }
}