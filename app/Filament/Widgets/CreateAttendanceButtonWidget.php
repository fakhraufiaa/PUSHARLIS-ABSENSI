<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CreateAttendanceButtonWidget extends Widget
{
    protected static string $view = 'filament.widgets.create-attendance-button-widget';

     // Mengatur lebar widget (misal: satu kolom penuh)
    protected array|string|int $columnSpan = 'full'; // jika Anda coba ubah sendiri

    protected function shouldBeRendered(): bool
    {
        // Pastikan user memiliki izin 'create' untuk model Attendance
        // Ini akan menggunakan AttendancePolicy yang sudah didaftarkan.
        return auth()->user()->can('create', Attendance::class);
    }
}
