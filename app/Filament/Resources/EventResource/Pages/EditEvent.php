<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;
use Illuminate\Support\HtmlString; // Pastikan ini sudah di-import

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\EditAction::make(), // Ini adalah EditAction standar untuk halaman EditRecord

        ];
    }
}
