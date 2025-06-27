<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class StrukController extends Controller
{
    public function print(Penjualan $penjualan)
    {
        $penjualan->load(['detailPenjualan.masterbarang.kategori']);

        return view('struk.print', compact('penjualan'));
    }
}
