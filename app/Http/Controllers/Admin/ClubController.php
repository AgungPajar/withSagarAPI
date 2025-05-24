<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// app/Http/Controllers/Admin/ClubController.php

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    // Lihat semua ekskul
    public function index()
    {
        return response()->json(Club::all());
    }

    // Tambah ekskul baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:clubs'
        ]);

        $club = Club::create($validated);
        return response()->json($club, 201);
    }

    // Hapus ekskul
    public function destroy(Club $club)
    {
        $club->delete();
        return response()->json(['message' => 'Ekskul berhasil dihapus']);
    }
}
