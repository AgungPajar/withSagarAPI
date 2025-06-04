<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClubController extends Controller
{
    // ✅ List semua klub
    public function index()
    {
        $clubs = Club::with('user')->get()->map(function ($club) {
            $club->hash_id = Hashids::encode($club->id);
            return $club;
        });

        return response()->json($clubs);
    }

    // ✅ Tambah klub + user pengurus
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255|unique:clubs,name',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'status'   => 'nullable|string',
        ]);

        // Buat klub
        $club = Club::create([
            'name'   => $validated['name'],
            'status' => $validated['status'] ?? null,
        ]);

        // Buat user pengurus
        $user = User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'role'     => 'club_pengurus',
            'club_id'  => $club->id,
        ]);

        return response()->json($club->load('user'), 201);
    }

    // ✅ Tampilkan detail klub
    public function show(Request $request, $hashedId)
    {
        $decoded = Hashids::decode($hashedId);
        if (count($decoded) === 0) {
            return response()->json(['message' => 'ID tidak valid'], 404);
        }

        $id = $decoded[0];
        $user = $request->user();

        if ($user && $user->role === 'club_pengurus' && $user->club_id != $id) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $club = Club::with('user')->find($id);
        if (!$club) {
            return response()->json(['message' => 'Ekskul tidak ditemukan'], 404);
        }

        return response()->json([
            'id'          => $club->id,
            'name'        => $club->name,
            'description' => $club->description,
            'logo_path'   => $club->logo_path,
            'username'    => $club->user->username ?? null,
        ]);
    }

    // ✅ Update klub dan user
    public function update(Request $request, $hashedId)
    {
        //
    }

    // ✅ Hapus klub dan user pengurus
    public function destroy($id)
    {
        $club = Club::with('user')->findOrFail($id);

        // Hapus user yang terhubung jika ada
        if ($club->user) {
            $club->user->delete();
        }

        $club->delete();
        return response()->json(null, 204);
    }

    // ✅ Ambil anggota
    public function members($hashedId)
    {
        $decoded = Hashids::decode($hashedId);
        if (count($decoded) === 0) {
            return response()->json(['message' => 'ID tidak valid'], 404);
        }

        $id = $decoded[0];
        $club = Club::with('students')->find($id);

        if (!$club) {
            return response()->json(['message' => 'Ekskul tidak ditemukan'], 404);
        }

        return response()->json($club->students);
    }

    // ✅ Alias
    public function getStudents($hashedId)
    {
        return $this->members($hashedId);
    }
}
