<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Attendance::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            '*.student_id' => 'required|exists:students,id',
            '*.club_id' => 'required|exists:clubs,id',
            '*.date' => 'required|date',
            '*.status' => 'required|in:hadir,tidak hadir',
        ]);
        
        foreach ($request->all() as $data) {
            Attendance::create($data);
        }
        
        return response()->json(['message' => 'Presensi berhasil disimpan'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        return response()->json($attendance);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'student_id' => 'sometimes|required|exists:students,id',
            'date' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:hadir,tidak hadir',
        ]);
        
        $attendance->update($validated);
        return response()->json($attendance);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return response()->json(null, 204);
    }
}
