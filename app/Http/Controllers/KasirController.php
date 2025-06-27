<?php

namespace App\Http\Controllers;

use App\Models\Masterbarang;
use App\Models\Kategori;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        $barangs = Masterbarang::with('kategori')->get();
        return view('kasir.index', compact('kategoris', 'barangs'));
    }

    public function getBarangByKategori($kategoriId)
    {
        $barangs = Masterbarang::where('kategori_id', $kategoriId)->get();
        return response()->json($barangs);
    }

    public function prosesPenjualan(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'total_harga' => 'required|numeric',
            'jumlah_bayar' => 'required|numeric|min:' . $request->total_harga,
        ]);

        DB::beginTransaction();
        try {
            // Generate kode transaksi
            $kodeTransaksi = 'TRX' . date('YmdHis');

            // Simpan penjualan
            $penjualan = Penjualan::create([
                'kode_transaksi' => $kodeTransaksi,
                'total_harga' => $request->total_harga,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian' => $request->jumlah_bayar - $request->total_harga,
                'tanggal_transaksi' => now()
            ]);

            // Simpan detail penjualan dan update stok
            foreach ($request->items as $item) {
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'masterbarang_id' => $item['id'],
                    'qty' => $item['qty'],
                    'harga_satuan' => $item['harga'],
                    'subtotal' => $item['qty'] * $item['harga']
                ]);

                // Update stok barang
                $barang = Masterbarang::find($item['id']);
                $barang->stok -= $item['qty'];
                $barang->save();
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'kode_transaksi' => $kodeTransaksi,
                'kembalian' => $penjualan->kembalian
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
