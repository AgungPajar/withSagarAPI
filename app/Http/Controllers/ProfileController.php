<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Club;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username'    => 'required|string|max:255|unique:users,username,' . $user->id,
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'password'    => 'nullable|string|min:6',
            'logo'        => 'nullable|image|max:2048',
        ]);

        // ✅ Update user
        $user->username = $request->username;
        $user->name = $request->name; // ✅ Tambahkan ini
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // ✅ Update club jika user punya club_id
        $club = null;
        if ($user->club_id) {
            $club = Club::find($user->club_id);

            if ($club) {
                $club->name = $request->name;
                $club->description = $request->description ?? $club->description;

                // ✅ Upload logo baru jika ada
                if ($request->hasFile('logo')) {
                    // Opsional: hapus logo lama
                    if ($club->logo_path && Storage::disk('public')->exists($club->logo_path)) {
                        Storage::disk('public')->delete($club->logo_path);
                    }

                    $path = $request->file('logo')->store('logos', 'public');
                    $club->logo_path = $path;
                }

                $club->save();
            }
        }

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user,
            'club' => $club->fresh()->makeHidden(['created_at', 'updated_at']),
        ]);
    }
}
