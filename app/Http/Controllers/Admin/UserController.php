<?php

namespace App\Http\Controllers\Admin;

use App\Models\Club;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'password' => 'required|min:6'
        ]);

        // Ambil nama klub dari database
        $club = Club::find($validated['club_id']);
        if (!$club) return response()->json(['message' => 'Ekskul tidak ditemukan'], 404);

        // Hash password
        $password = bcrypt($request->password);

        // Buat user
        $user = User::create([
            'name' => $club->name,
            'username' => $club->name,
            'password' => $password,
            'role' => 'club_pengurus',
            'club_id' => $club->id
        ]);

        return response()->json([
            'message' => 'Pengurus ekskul berhasil dibuat',
            'user' => $user->only(['name', 'username', 'role', 'club_id'])
        ], 201);
    }
}