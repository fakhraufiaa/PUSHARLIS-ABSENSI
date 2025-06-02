<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'agenda',
        'date',
        'time',
        'place',
        'is_attendance_enabled',
        'signature_admin',
    ];

    public function attendances()
    {
        return $this->hasMany(\App\Models\Attendance::class);
    }
}

