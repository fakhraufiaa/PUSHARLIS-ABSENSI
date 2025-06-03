<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Support\Facades\Session;

class PublicEventController extends Controller
{
    public function showAttendanceForm($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        // Menggunakan 'is_attendance_enabled'
        if (!$event->is_attendance_enabled) {

            return view('public.event_attendance_closed', ['event' => $event]);
        }

        return view('public.attendance_form', ['event' => $event]);
    }

    public function processAttendance(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        // Menggunakan 'is_attendance_enabled'
        if (!$event->is_attendance_enabled) {
            Session::flash('error', 'Absensi untuk event ini telah ditutup.');
            return redirect()->route('event.attend.show', $event->slug);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'signature' => 'nullable|string', // Validasi untuk signature (jika berupa teks/base64)
        ]);

        Attendance::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'position' => $request->position,
            'email' => $request->email,
            'phone' => $request->phone,
            'signature' => $request->signature,
        ]);

        Session::flash('success', 'Absensi Anda berhasil tercatat!');
        return redirect()->route('event.attend.show', $event->slug);
    }
}
