<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\CreateAttendanceButtonWidget;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    protected static array $widgets = [
        CreateAttendanceButtonWidget::class,
        // Jika ada widget default Filament lainnya, mereka juga akan ada di sini
        // misalnya: \App\Filament\Widgets\AccountWidget::class,
        // \App\Filament\Widgets\FilamentInfoWidget::class,
    ];
}
