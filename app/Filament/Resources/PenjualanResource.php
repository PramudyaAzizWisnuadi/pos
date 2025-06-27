<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Penjualan;
use App\Exports\PenjualanExport;
use App\Exports\DetailPenjualanExport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationLabel = 'History Penjualan';

    protected static ?string $modelLabel = 'Penjualan';

    protected static ?string $pluralModelLabel = 'History Penjualan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_transaksi')
                    ->label('Kode Transaksi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tanggal_transaksi')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->sortable()
                    ->searchable()
                    ->money('idr'),
                TextColumn::make('jumlah_bayar')
                    ->label('Jumlah Bayar')
                    ->sortable()
                    ->searchable()
                    ->money('idr'),
                TextColumn::make('kembalian')
                    ->label('Kembalian')
                    ->sortable()
                    ->searchable()
                    ->money('idr'),
                TextColumn::make('detailPenjualan')
                    ->label('Jumlah Item')
                    ->getStateUsing(function ($record) {
                        return $record->detailPenjualan->sum('qty') . ' item';
                    }),
            ])
            ->filters([
                Filter::make('tanggal_transaksi')
                    ->label('Filter Tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_dari')
                            ->label('Tanggal Dari'),
                        Forms\Components\DatePicker::make('tanggal_sampai')
                            ->label('Tanggal Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_dari'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_transaksi', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_sampai'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_transaksi', '<=', $date),
                            );
                    }),

                SelectFilter::make('total_harga')
                    ->label('Range Harga')
                    ->options([
                        '0-50000' => 'Rp 0 - Rp 50.000',
                        '50000-100000' => 'Rp 50.000 - Rp 100.000',
                        '100000-500000' => 'Rp 100.000 - Rp 500.000',
                        '500000+' => 'Rp 500.000+',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            function (Builder $query, $value): Builder {
                                return match ($value) {
                                    '0-50000' => $query->whereBetween('total_harga', [0, 50000]),
                                    '50000-100000' => $query->whereBetween('total_harga', [50000, 100000]),
                                    '100000-500000' => $query->whereBetween('total_harga', [100000, 500000]),
                                    '500000+' => $query->where('total_harga', '>=', 500000),
                                };
                            }
                        );
                    }),

                Filter::make('today')
                    ->label('Hari Ini')
                    ->query(fn(Builder $query): Builder => $query->whereDate('tanggal_transaksi', today()))
                    ->toggle(),

                Filter::make('this_week')
                    ->label('Minggu Ini')
                    ->query(fn(Builder $query): Builder => $query->whereBetween('tanggal_transaksi', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]))
                    ->toggle(),

                Filter::make('this_month')
                    ->label('Bulan Ini')
                    ->query(fn(Builder $query): Builder => $query->whereMonth('tanggal_transaksi', Carbon::now()->month))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn(Penjualan $record): string => route('penjualan.detail', $record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn(Penjualan $record): string => route('struk.print', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->default(now()->startOfMonth()),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->default(now()->endOfMonth()),
                        Forms\Components\Select::make('export_type')
                            ->label('Jenis Export')
                            ->options([
                                'summary' => 'Ringkasan Penjualan',
                                'detail' => 'Detail Item Penjualan',
                            ])
                            ->required()
                            ->default('summary'),
                    ])
                    ->action(function (array $data) {
                        $startDate = $data['start_date'];
                        $endDate = $data['end_date'];
                        $exportType = $data['export_type'];

                        $filename = 'penjualan_' . $exportType . '_' .
                            date('Y-m-d', strtotime($startDate)) . '_to_' .
                            date('Y-m-d', strtotime($endDate)) . '.xlsx';

                        if ($exportType === 'summary') {
                            return Excel::download(new PenjualanExport($startDate, $endDate), $filename);
                        } else {
                            return Excel::download(new DetailPenjualanExport($startDate, $endDate), $filename);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export_selected')
                        ->label('Export Excel (Terpilih)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function ($records) {
                            $filename = 'penjualan_selected_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

                            // Create custom export for selected records
                            return Excel::download(new class($records) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping {
                                protected $records;

                                public function __construct($records)
                                {
                                    $this->records = $records;
                                }

                                public function collection()
                                {
                                    return $this->records;
                                }

                                public function headings(): array
                                {
                                    return [
                                        'Kode Transaksi',
                                        'Tanggal',
                                        'Total Harga',
                                        'Jumlah Bayar',
                                        'Kembalian',
                                        'Total Item'
                                    ];
                                }

                                public function map($record): array
                                {
                                    return [
                                        $record->kode_transaksi,
                                        Carbon::parse($record->tanggal_transaksi)->format('d/m/Y H:i'),
                                        'Rp ' . number_format($record->total_harga, 0, ',', '.'),
                                        'Rp ' . number_format($record->jumlah_bayar, 0, ',', '.'),
                                        'Rp ' . number_format($record->kembalian, 0, ',', '.'),
                                        $record->detailPenjualan->sum('qty') . ' item'
                                    ];
                                }
                            }, $filename);
                        }),
                ]),
            ])
            ->recordUrl(
                fn(Penjualan $record): string => route('penjualan.detail', $record->id)
            )
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListPenjualans::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }
}
