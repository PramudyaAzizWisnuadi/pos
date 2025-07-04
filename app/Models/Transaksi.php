<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $guarded = [];
    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
