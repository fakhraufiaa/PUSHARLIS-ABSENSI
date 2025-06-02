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
        $eventId = $this->record->id;
        $absenUrl = url('/attendance/' . $eventId); // Pastikan route '/attendance/{eventId}' ada

        return [
            // Actions\DeleteAction::make(), // Jika Anda punya DeleteAction, pastikan urutannya sesuai
            // Sepertinya Anda sebelumnya memiliki EditAction di sini. Jika masih perlu:
            Actions\EditAction::make(), // Ini adalah EditAction standar untuk halaman EditRecord

            Actions\Action::make('copyAbsenLink')
                ->label('Copy Link Isi Absen')
                ->color('primary')
                ->icon('heroicon-o-link')
                // Atribut ini akan mencoba menyalin ke clipboard saat tombol "Copy Link Isi Absen" diklik
                ->extraAttributes(['onclick' => "navigator.clipboard.writeText('{$absenUrl}'); alert('Link absensi telah disalin!');"]) // Tambahkan feedback alert
                ->modalHeading('Link Isi Absen')
                // Perbaikan di sini: Bungkus string HTML dengan HtmlString
                ->modalDescription(new HtmlString("Link absen untuk event ini:<br><a href=\"{$absenUrl}\" target=\"_blank\" style=\"color: blue; text-decoration: underline;\">{$absenUrl}</a>"))
                // ->action(null) // Jika tombol di modal hanya untuk menutup, tidak ada aksi server-side
                ->modalSubmitActionLabel('Tutup') // Mengubah label tombol submit di modal menjadi "Tutup"
                // ->modalCancelAction(false) // Jika Anda hanya ingin satu tombol "Tutup" (submit)
                // Baris ->modalButton('Tutup'), ->html(), dan ->formatStateUsing() di bawah ini tidak tepat dan harus dihapus dari sini.
        ];
    }
}
