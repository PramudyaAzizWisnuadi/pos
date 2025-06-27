<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class PenjualanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Penjualan::with(['detailPenjualan.masterbarang.kategori']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_transaksi', [$this->startDate, $this->endDate]);
        }

        return $query->orderBy('tanggal_transaksi', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Transaksi',
            'Tanggal',
            'Jam',
            'Total Harga',
            'Jumlah Bayar',
            'Kembalian',
            'Total Item',
            'Jenis Barang',
            'Detail Barang'
        ];
    }

    public function map($penjualan): array
    {
        static $no = 1;

        // Format detail barang
        $detailBarang = $penjualan->detailPenjualan->map(function ($detail) {
            return $detail->masterbarang->nama . ' (' . $detail->qty . 'x)';
        })->join(', ');

        return [
            $no++,
            $penjualan->kode_transaksi,
            Carbon::parse($penjualan->tanggal_transaksi)->format('d/m/Y'),
            Carbon::parse($penjualan->tanggal_transaksi)->format('H:i:s'),
            'Rp ' . number_format($penjualan->total_harga, 0, ',', '.'),
            'Rp ' . number_format($penjualan->jumlah_bayar, 0, ',', '.'),
            'Rp ' . number_format($penjualan->kembalian, 0, ',', '.'),
            $penjualan->detailPenjualan->sum('qty'),
            $penjualan->detailPenjualan->count(),
            $detailBarang
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ]
            ],
        ];
    }
}
