<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Masterbarang;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use App\Filament\Resources\MasterbarangResource\Pages;
use App\Filament\Resources\MasterbarangResource\RelationManagers;

class MasterbarangResource extends Resource
{
    protected static ?string $model = Masterbarang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralModelLabel = 'Data Barang';

    protected static ?string $navigationLabel = 'Data Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')->required(),
                Select::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama_kategori')
                    ->required(),
                TextInput::make('stok')->numeric()->required(),
                TextInput::make('harga')->prefix('Rp.')->numeric()->required(),
                FileUpload::make('foto')
                    ->label('Foto Barang')
                    ->image()
                    ->nullable()
                    ->maxSize(1024) // 1MB
                    ->enableOpen(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto Barang')
                    ->circular()
                    ->size(50),
                TextColumn::make('nama')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stok')
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('harga')
                    ->label('Harga')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama_kategori'),
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
            'index' => Pages\ListMasterbarangs::route('/'),
            'create' => Pages\CreateMasterbarang::route('/create'),
            'edit' => Pages\EditMasterbarang::route('/{record}/edit'),
        ];
    }
}
