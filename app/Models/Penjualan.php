<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'total_harga',
        'jumlah_bayar',
        'kembalian',
        'tanggal_transaksi'
    ];

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class);
    }
    public function getTanggalTransaksiAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}
