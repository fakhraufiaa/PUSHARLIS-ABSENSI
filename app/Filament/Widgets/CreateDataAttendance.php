<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;

class CreateDataAttendance extends ChartWidget
{
    protected static ?string $heading = 'Chart Event';

    protected function getData(): array
    {
        // Ambil jumlah event per bulan di tahun berjalan
        $events = Event::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Siapkan label bulan
        $labels = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Buat array data sesuai urutan bulan
        $data = [];
        foreach (range(1, 12) as $month) {
            $data[] = $events[$month] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Event',
                    'data' => $data,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => array_values($labels),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
