<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    // protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    // {
    //     $event = $this->record;
    //     $link = route('attendance.form', ['event' => $event->id]);

    //     return \Filament\Notifications\Notification::make()
    //         ->title('Event berhasil dibuat!')
    //         ->body("Bagikan link absensi ke peserta: <a href=\"{$link}\" target=\"_blank\">{$link}</a>")
    //         ->success()
    //         ->persistent();
    // }
}
