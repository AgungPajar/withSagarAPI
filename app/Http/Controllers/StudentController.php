<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Student;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Student::with('club')->get());
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
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:students,nisn',
            'club_id' => 'required|exists:clubs,id',
        ]);

        $student = Student::create($validated);
        return response()->json($student, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return response()->json($student->load('club'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'nisn' => 'sometimes|required|string|unique:students,nisn' . $student->id,
            'class' => 'nullable|string|max:50',
        ]);

        $student->update($validated);
        return response()->json($student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }

        $student->clubs()->detach(); // hapus dari semua ekskul
        $student->delete();

        return response()->json(['message' => 'Siswa berhasil dihapus']);
    }

    public function storeToClub(Request $request, $hashedId)
    {
        $decoded = Hashids::decode($hashedId);

        if (count($decoded) === 0) {
            return response()->json(['message' => 'ID tidak valid'], 404);
        }

        $clubId = $decoded[0];

        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|max:20',
            'class' => 'nullable|string|max:50',
        ]);

        $student = Student::where('nisn', $request->nisn)->first();

        if (!$student) {
            $student = Student::create([
                'name' => $request->name,
                'nisn' => $request->nisn,
                'class' => $request->class ?? '',
            ]);
        } else {
            // Update nama dan kelas jika kosong atau berubah
            $student->update([
                'name' => $request->name,
                'class' => $request->class ?? $student->class,
            ]);
        }

        // Tambahkan ke ekskul tanpa duplicate
        $student->clubs()->syncWithoutDetaching([$clubId]);


        return response()->json(['message' => 'Siswa ditambahkan ke ekskul', 'student' => $student]);
    }
}
