<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = [
        'event_id',
        'name',
        'position',
        'email',
        'phone',
        'signature',
    ];

    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }
}
