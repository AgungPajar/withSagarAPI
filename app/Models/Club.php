<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;
    protected $table = 'clubs';
    protected $fillable = ['name', 'description', 'logo_path'];

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : null;
    }

    public function user() {
        return $this->belongsTo(User::class, 'id', 'club_id');
    }
}
