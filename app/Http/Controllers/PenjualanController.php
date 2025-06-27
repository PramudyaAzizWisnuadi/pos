<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function detail(Penjualan $penjualan)
    {
        $penjualan->load(['detailPenjualan.masterbarang.kategori']);

        return view('penjualan.detail', compact('penjualan'));
    }
}
