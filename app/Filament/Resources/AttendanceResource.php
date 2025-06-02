<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Coolsam\SignaturePad\Forms\Components\Fields\SignaturePad;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;


    protected static ?string $navigationIcon = 'heroicon-o-document-text';




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('event_id')
                    ->label('Agenda/Acara')
                    ->relationship('event', 'agenda')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                Forms\Components\TextInput::make('position')
                    ->label('Jabatan')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->label('No. Telp')
                    ->required(),
                // Custom Signature Pad field (Base64 image in hidden TextArea)
                SignaturePad::make('signature')
                    ->backgroundColor('white') // Set the background color in case you want to download to jpeg
                    ->penColor('black') // Set the pen color
                    ->strokeMinDistance(2.0) // set the minimum stroke distance (the default works fine)
                    ->strokeMaxWidth(2.5) // set the max width of the pen stroke
                    ->strokeMinWidth(1.0) // set the minimum width of the pen stroke
                    ->strokeDotSize(2.0) // set the stroke dot size.
                    ->hideDownloadButtons(), // Hide the download buttons if you don't want them
                Forms\Components\Hidden::make('event_id')
                    ->default(fn () => request()->get('event_id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.agenda')
                    ->label('Agenda/Acara')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('No. Telp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('signature')
                    ->label('Tanda Tangan')
                    ->limit(20), // Optionally show a preview or just indicate it's filled
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
