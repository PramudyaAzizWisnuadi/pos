<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use App\Models\Masterbarang;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\TransaksiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiResource\RelationManagers;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode')
                    ->label('Kode Transaksi')
                    ->default(fn() => 'TRX-' . now()->format('YmdHis') . rand(100, 999))
                    ->disabled()
                    ->dehydrated(), // tetap tersimpan walau disabled
                DateTimePicker::make('tanggal')
                    ->label('Tanggal')
                    ->default(now())
                    ->required(),
                Repeater::make('details')
                    ->relationship()
                    ->schema([
                        Select::make('masterbarang_id')
                            ->label('Barang')
                            ->options(Masterbarang::all()->pluck('nama', 'id'))
                            ->searchable()
                            ->required()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $barang = \App\Models\Masterbarang::find($state);
                                $set('harga', $barang?->harga ?? 0);
                                // Reset qty dan subtotal saat barang diganti
                                $set('qty', 1);
                                $set('subtotal', ($barang?->harga ?? 0) * 1);
                            }),
                        TextInput::make('harga')
                            ->label('Harga')
                            ->numeric()
                            ->required()
                            ->readOnly(),
                        TextInput::make('qty')
                            ->label('Qty')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $set('subtotal', ($get('harga') ?? 0) * ($state ?? 0));
                            }),
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->required()
                            ->readOnly(),
                    ])
                    ->createItemButtonLabel('Tambah Barang'),
                TextInput::make('total')
                    ->label('Total')
                    ->numeric()
                    ->required()
                    ->readOnly(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
