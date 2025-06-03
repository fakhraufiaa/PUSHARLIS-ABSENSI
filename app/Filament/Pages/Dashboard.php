<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\CreateAttendanceButtonWidget;
use App\Filament\Widgets\CreateDataAttendance;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            CreateDataAttendance::class,
            // CreateAttendanceButtonWidget::class, // Add your widget here
        ];
    }

    public function makeWidget($widget)
    {
        return app($widget);
    }




}
