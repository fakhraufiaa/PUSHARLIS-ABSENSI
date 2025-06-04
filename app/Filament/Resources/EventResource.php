<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Coolsam\SignaturePad\Forms\Components\Fields\SignaturePad;
use PhpOffice\PhpWord\TemplateProcessor;
use Dompdf\Dompdf;
use Illuminate\Support\Str;
use Filament\Forms\Set;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('agenda')
                    ->label('Agenda/Acara')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Set $set) {
                    $slugValue = Str::slug($state ?? '');
                    if ($operation === 'create') {
                        $set('slug', $slugValue); // <--  memperbarui field 'slug'
                    }})
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->label('Hari/Tanggal')
                    ->required(),
                Forms\Components\TimePicker::make('time')
                    ->label('Jam')
                    ->required(),
                Forms\Components\TextInput::make('place')
                    ->label('Tempat')
                    ->required(),
                Forms\Components\Toggle::make('is_attendance_enabled')
                    ->label('Aktifkan Absen')
                    ->default(true),
                Forms\Components\TextInput::make('slug')
                    // ->default(fn (Event $record) => $record->slug ?? Str::slug($record->agenda ?? ''))
                    ->unique(Event::class, 'slug', ignoreRecord: true)
                    ->helperText('Slug akan otomatis dibuat dari agenda acara.'),
                // Tambahkan ini untuk signature admin:
                // \Coolsam\SignaturePad\Forms\Components\Fields\SignaturePad::make('signature_admin')
                //     ->label('Tanda Tangan Penanggung Jawab')
                //     ->backgroundColor('white')
                //     ->penColor('black')
                //     ->strokeMinDistance(2.0)
                //     ->strokeMaxWidth(2.5)
                //     ->strokeMinWidth(1.0)
                //     ->strokeDotSize(2.0)
                //     ->hideDownloadButtons()
                //     ->columnSpan(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->columns([
                Tables\Columns\TextColumn::make('agenda')
                    ->label('Agenda/Acara')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Hari/Tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('time')
                    ->label('Jam'),
                Tables\Columns\TextColumn::make('place')
                    ->label('Tempat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Link Absen')
                    ->formatStateUsing(fn ($state, $record) =>
                        '<a href="' . route('event.attend.show', ['slug' => $state]) . '" class="text-primary-600 underline" target="_blank">' . e($state) . '</a>'
                    )
                    ->html()
                    ->alignCenter(),
                    // ->visible(fn (Event $record) => auth()->user()->can('view', $record)),
            ])
            ->filters([
                Tables\Filters\Filter::make('attendance_enabled')
                    ->label('Absen Aktif')
                    ->query(fn (Builder $query) => $query->where('is_attendance_enabled', 1))
                    ->default(true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('isiAbsen')
                    ->icon('heroicon-o-pencil-square')
                    ->visible(fn ($record) => $record->is_attendance_enabled)
                    ->form([
                        Forms\Components\View::make('components.form-logo'),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('position')
                                    ->label('Jabatan')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('phone')
                                    ->label('No. Telp')
                                    ->required()
                                    ->columnSpan(1),
                                \Coolsam\SignaturePad\Forms\Components\Fields\SignaturePad::make('signature')
                                    ->backgroundColor('white')
                                    ->penColor('black')
                                    ->strokeMinDistance(2.0)
                                    ->strokeMaxWidth(2.5)
                                    ->strokeMinWidth(1.0)
                                    ->strokeDotSize(2.0)
                                    ->hideDownloadButtons()
                                    ->columnSpan(2), // Signature gabung dua kolom
                            ])
                            ->columns(2),
                    ])
                    ->action(function (array $data, $record) {
                        $record->attendances()->create($data);
                        \Filament\Notifications\Notification::make()
                            ->title('Absen berhasil disimpan!')
                            ->success()
                            ->send();
                    })
                    ->modalHeading(false)
                    ->modalSubmitActionLabel('Simpan')
                    ->color('primary'),

            Tables\Actions\Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function ($record) {
                    $event = $record;
                    $attendances = $event->attendances;

                    // Ambil attendance pertama (atau sesuaikan kebutuhan)
                    $attendance = $attendances->first();

                    // Path template docx
                    $templatePath = public_path('attendance_template.docx');
                    $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

                    // Set data event
                    $phpWord->setValue('agenda', $event->agenda);
                    $phpWord->setValue('date', $event->date);
                    $phpWord->setValue('time', $event->time);
                    $phpWord->setValue('place', $event->place);

                    // CLONE ROW ATTENDANCE
                    $phpWord->cloneRow('name', $attendances->count());
                    foreach ($attendances as $i => $attendance) {
                        $row = $i + 1;
                        $phpWord->setValue("no#{$row}", $row);
                        $phpWord->setValue("name#{$row}", $attendance->name);
                        $phpWord->setValue("position#{$row}", $attendance->position);
                        $phpWord->setValue("email#{$row}", $attendance->email);
                        $phpWord->setValue("phone#{$row}", $attendance->phone);

                        // Signature per row
                        $signaturePath = null;
                        if ($attendance->signature && str_starts_with($attendance->signature, 'data:image/png')) {
                            $signatureData = explode(',', $attendance->signature)[1];
                            $signatureFileName = "signature_{$attendance->id}_" . uniqid() . ".png";
                            $signaturePath = storage_path('app/public/' . $signatureFileName); // Path lengkap
                            file_put_contents($signaturePath, base64_decode($signatureData));
                            $phpWord->setImageValue("signature#{$row}", [
                                'path' => $signaturePath,
                                'width' => 200,
                                'height' => 250,
                            ]);
                        } else {
                            $phpWord->setImageValue("signature#{$row}", [
                                'path' => $signaturePath ?? '',
                                'width' => 200,
                                'height' => 250,
                            ]);
                        }
                        // hapus file siganature sementara jika ada
                        if ($signaturePath && file_exists($signaturePath)) {
                            unlink($signaturePath);
                        }

                    }

                    // SET SIGNATURE ADMIN (setelah cloneRow, untuk tabel baru di bawahnya)
                    // $adminSignaturePath = null;
                    // if ($event->signature_admin && str_starts_with($event->signature_admin, 'data:image/png')) {
                    //     $adminSignatureData = explode(',', $event->signature_admin)[1];
                    //     $adminSignaturePath = storage_path("app/public/signature_admin_{$event->id}.png");
                    //     file_put_contents($adminSignaturePath, base64_decode($adminSignatureData));
                    //     $phpWord->setImageValue('signature_admin', [
                    //         'path' => $adminSignaturePath,
                    //         'width' => 350,
                    //         'height' => 500,
                    //     ]);
                    // } else {
                    //     $phpWord->setValue('signature_admin', '');
                    // }

                    // Nama file tanpa spasi dan slash
                    $baseName = str_replace([' ', '/'], '_', $event->agenda) . '_' . $event->date;

                    // Simpan docx sementara
                    $docxPath = storage_path('app/public/' . $baseName . '.docx');
                    $phpWord->saveAs($docxPath);

                    // Nama file PDF
                    $filename = $baseName . '.pdf';
                    $pdfPath = storage_path('app/public/' . $filename);

                    // Pastikan LibreOffice terinstall di server
                    $command = 'soffice --headless --convert-to pdf --outdir ' . escapeshellarg(dirname($pdfPath)) . ' ' . escapeshellarg($docxPath);
                    exec($command, $output, $return_var);

                    if ($return_var !== 0 || !file_exists($pdfPath)) {
                        return response()->json(['error' => 'Failed to convert document to PDF.'], 500);
                    }

                    // Hapus file signature sementara jika ada
                    if ($signaturePath && file_exists($signaturePath)) {
                        unlink($signaturePath);
                    }

                    // File PDF akan ada di $pdfPath
                    return response()->download($pdfPath, $filename)->deleteFileAfterSend(true);
                })
                ->visible(fn ($record) => auth()->user()->can('export', $record))
                ->requiresConfirmation()
                ->color('success')
                ->visible(fn ($record) => \App\Models\Attendance::where('event_id', $record->id)->exists()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
