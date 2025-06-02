<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityReport;
use Vinkla\Hashids\Facades\Hashids;

class ActivityReportController extends Controller
{
    public function store(Request $request, $hashedId)
    {
        $decoded = Hashids::decode($hashedId);
        if (count($decoded) === 0) {
            return response()->json(['message' => 'ID tidak valid'], 404);
        }
        $clubId = $decoded[0];

        $request->validate([
            'date' => 'required|date',
            'materi' => 'required|string',
            'tempat' => 'required|string',
            'photo' => 'nullable|image|max:2048', // ✅ pastikan ini
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('foto-presensi', 'public');
        }

        $report = ActivityReport::create([
            'club_id' => $clubId,
            'date' => $request->date,
            'materi' => $request->materi,
            'tempat' => $request->tempat,
            'photo_url' => $photoPath, // ✅ isi dengan nama file
        ]);

        return response()->json([
            'message' => 'Berhasil simpan',
            'has_photo' => $request->hasFile('photo'),
            'path' => $photoPath,
            'data' => $report,
        ], 200);
    }
}
