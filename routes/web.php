<?php


use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PublicEventController;

Route::get('/', function () {
    return redirect()->to('/admin');
});

Route::get('/Absensi-{slug}', [PublicEventController::class, 'showAttendanceForm'])->name('event.attend.show');
Route::post('/Absensi-{slug}', [PublicEventController::class, 'processAttendance'])->name('event.attend.process');

