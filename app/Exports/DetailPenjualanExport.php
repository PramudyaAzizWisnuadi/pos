<?php
// filepath: d:\laragon\www\pos\app\Exports\DetailPenjualanExport.php
namespace App\Exports;

use App\Models\DetailPenjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class DetailPenjualanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        $query = DetailPenjualan::with(['penjualan', 'masterbarang.kategori']);

        if ($this->startDate && $this->endDate) {
            $query->whereHas('penjualan', function ($q) {
                $q->whereBetween('tanggal_transaksi', [$this->startDate, $this->endDate]);
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Transaksi',
            'Tanggal',
            'Nama Barang',
            'Kategori',
            'Quantity',
            'Harga Satuan',
            'Subtotal',
            'Total Transaksi'
        ];
    }

    public function map($detail): array
    {
        static $no = 1;

        return [
            $no++,
            $detail->penjualan->kode_transaksi,
            Carbon::parse($detail->penjualan->tanggal_transaksi)->format('d/m/Y H:i'),
            $detail->masterbarang->nama,
            $detail->masterbarang->kategori->nama_kategori ?? '-',
            $detail->qty,
            'Rp ' . number_format($detail->harga_satuan, 0, ',', '.'),
            'Rp ' . number_format($detail->subtotal, 0, ',', '.'),
            'Rp ' . number_format($detail->penjualan->total_harga, 0, ',', '.')
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
                    'startColor' => ['rgb' => '28A745']
                ]
            ],
        ];
    }
}
