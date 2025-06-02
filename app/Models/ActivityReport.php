<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityReport extends Model
{
    protected $fillable = ['club_id', 'date', 'materi', 'tempat', 'photo_url'];
}
