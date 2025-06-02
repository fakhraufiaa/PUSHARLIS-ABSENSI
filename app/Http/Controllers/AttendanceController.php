<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function showForm(Event $event)
    {
        // Tampilkan view form absensi, kirim data event
        return view('attendance.form', compact('event'));
    }
}
