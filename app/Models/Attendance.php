<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'club_id', 'status', 'date'];
    public function getTable(){
        return 'attendances';
    }
    public function student(){
        return $this->belongsTo(Student::class);
    }
}
