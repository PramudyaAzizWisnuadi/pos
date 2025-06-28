<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingTokoResource\Pages;
use App\Models\SettingToko;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class SettingTokoResource extends Resource
{
    protected static ?string $model = SettingToko::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Setting Toko';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Toko')
                    ->description('Kelola informasi dasar toko Anda')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama_toko')
                                    ->label('Nama Toko')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nama toko'),

                                Forms\Components\TextInput::make('telepon')
                                    ->label('No. Telepon')
                                    ->tel()
                                    ->maxLength(20)
                                    ->placeholder('0812-3456-7890'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('info@toko.com'),

                                Forms\Components\TextInput::make('website')
                                    ->label('Website')
                                    ->url()
                                    ->maxLength(255)
                                    ->placeholder('https://www.toko.com'),
                            ]),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->rows(3)
                            ->placeholder('Masukkan alamat lengkap toko'),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Toko')
                            ->rows(3)
                            ->placeholder('Deskripsi singkat tentang toko'),
                    ]),

                Section::make('Logo Toko')
                    ->description('Upload logo toko (Format: JPG, PNG | Max: 2MB)')
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo Toko')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                                '16:9',
                                '4:3',
                            ])
                            ->directory('logos')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                            ->helperText('Ukuran maksimal 2MB. Format yang didukung: JPG, PNG'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->size(60),

                Tables\Columns\TextColumn::make('nama_toko')
                    ->label('Nama Toko')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(50)
                    ->tooltip(function (SettingToko $record): ?string {
                        return $record->alamat;
                    }),

                Tables\Columns\TextColumn::make('telepon')
                    ->label('Telepon')
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->icon('heroicon-m-envelope')
                    ->copyable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tidak ada bulk delete karena setting toko harus selalu ada
                ]),
            ])
            ->emptyStateHeading('Belum ada pengaturan toko')
            ->emptyStateDescription('Buat pengaturan toko pertama untuk memulai.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Setting Toko'),
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
            'index' => Pages\ListSettingTokos::route('/'),
            'create' => Pages\CreateSettingToko::route('/create'),
            'edit' => Pages\EditSettingToko::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        // Hanya izinkan buat jika belum ada setting
        return SettingToko::count() === 0;
    }

    public static function canDeleteAny(): bool
    {
        return false; // Tidak boleh hapus setting toko
    }

    public static function canDelete($record): bool
    {
        return false; // Tidak boleh hapus setting toko
    }
}
