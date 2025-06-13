<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'nisn', 'class', 'phone'];
    public function clubs() {
        return $this->belongsToMany(Club::class, 'club_student', 'student_id', 'club_id');
    }
}
