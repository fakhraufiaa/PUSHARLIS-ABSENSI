<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return redirect()->to('/admin');
});

Route::get('/attendance/{event}', [AttendanceController::class, 'showForm'])
    ->middleware(['auth', 'role:user']) // hanya user login dengan role 'peserta'
    ->name('attendance.form');
