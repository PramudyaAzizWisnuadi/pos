<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $guarded = [];
    public function barang()
    {
        return $this->belongsTo(Masterbarang::class, 'masterbarang_id');
    }
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
