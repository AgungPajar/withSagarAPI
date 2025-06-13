<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nisn'       => 'required|string|max:20|unique:students,nisn',
            'name'       => 'required|string|max:255',
            'class'      => 'required|string|max:50',
            'username'   => 'required|string|max:50|unique:users,username',
            'email'      => 'required|email|max:255|unique:users,email',
            'club_id'    => 'required|exists:clubs,id',
            'password'   => 'required|string|min:6',
            'confirm_password'   => 'required|string|min:6|same:password', // password_confirmation
            'phone'      => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Buat user
        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'student',
        ]);

        // Buat student
        $student = Student::create([
            'user_id' => $user->id,
            'name'    => $request->name,
            'nisn'    => $request->nisn,
            'class'   => $request->class,
            'club_id' => $request->club_id,
            'phone'   => $request->phone,
        ]);
        $student->clubs()->attach($request->club_id);

        return response()->json(['message' => 'Pendaftaran berhasil'], 201);
    }
}
