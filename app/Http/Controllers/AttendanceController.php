<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class AttendanceController extends Controller
{
    public function index()
    {
        return response()->json(Attendance::all());
    }

    public function store(Request $request)
    {
        $data = $request->input('data'); // ← karena frontend kirim { data: [...] }
        Log::info('Data diterima untuk presensi:', $data);

        if (!is_array($data)) {
            return response()->json(['message' => 'Format data salah'], 422);
        }

        foreach ($data as $index => $item) {
            // Decode hashid kalau club_id bukan angka
            if (!is_numeric($item['club_id'])) {
                $decoded = Hashids::decode($item['club_id']);
                if (empty($decoded)) {
                    return response()->json([
                        'message' => "Club ID tidak valid di indeks {$index}"
                    ], 422);
                }
                $data[$index]['club_id'] = $decoded[0];
                $item['club_id'] = $decoded[0];
            }

            // Validasi setiap item
            $validator = Validator::make($item, [
                'student_id' => 'required|exists:students,id',
                'club_id'    => 'required|exists:clubs,id',
                'status'     => 'required|in:hadir,tidak hadir',
                'date'       => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => "Format data salah di indeks {$index}",
                    'errors'  => $validator->errors(),
                ], 422);
            }

            Log::info('Presensi disiapkan:', $data[$index]);

            Attendance::create($data[$index]);
        }

        return response()->json(['message' => 'Presensi berhasil disimpan'], 200);
    }

    public function show(Attendance $attendance)
    {
        return response()->json($attendance);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'student_id' => 'sometimes|required|exists:students,id',
            'date'       => 'sometimes|required|date',
            'status'     => 'sometimes|required|in:hadir,tidak hadir',
        ]);

        $attendance->update($validated);
        return response()->json($attendance);
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return response()->json(null, 204);
    }

    // public function rekap($hashId, Request $request)
    // {
    //     $decoded = Hashids::decode($hashId);
    //     if (count($decoded) === 0) {
    //         return response()->json(['message' => 'ID tidak valid'], 404);
    //     }

    //     $clubId = $decoded[0];

    //     $query = Attendance::with('student')
    //         ->where('club_id', $clubId);

    //     if ($request->has('date')) {
    //         $query->whereDate('date', $request->date);
    //     }

    //     $data = $query->get();

    //     return response()->json($data);
    // }
}
