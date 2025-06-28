<?php

namespace App\Http\Controllers;

use App\Models\Masterbarang;
use App\Models\Kategori;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

    /**
     * Get all products data untuk real-time update (gabungan barang + stok)
     */
    public function getProductsData(Request $request)
    {
        try {
            $since = $request->input('since');

            // Base query untuk products
            $productsQuery = Masterbarang::with('kategori')
                ->select('id', 'nama', 'harga', 'stok', 'kategori_id', 'foto', 'created_at', 'updated_at');

            // Base query untuk categories
            $categoriesQuery = Kategori::select('id', 'nama', 'created_at', 'updated_at');

            $response = [
                'success' => true,
                'has_changes' => true,
                'last_update' => now()->toISOString()
            ];

            // Jika ada parameter 'since', hanya ambil yang berubah
            if ($since) {
                $sinceDate = Carbon::parse($since);

                // Get updated/new products
                $updatedProducts = $productsQuery
                    ->where('updated_at', '>', $sinceDate)
                    ->orWhere('created_at', '>', $sinceDate)
                    ->get();

                // Get updated/new categories
                $updatedCategories = $categoriesQuery
                    ->where('updated_at', '>', $sinceDate)
                    ->orWhere('created_at', '>', $sinceDate)
                    ->get();

                // Check if there are actual changes
                $hasChanges = $updatedProducts->count() > 0 || $updatedCategories->count() > 0;

                $response['has_changes'] = $hasChanges;
                $response['data'] = [
                    'products' => $updatedProducts,
                    'categories' => $updatedCategories->keyBy('id'),
                    'products_count' => $updatedProducts->count(),
                    'categories_count' => $updatedCategories->count(),
                    'changes_count' => $updatedProducts->count() + $updatedCategories->count()
                ];

                // Log changes for debugging
                if ($hasChanges) {
                    Log::info('Products data changes detected', [
                        'updated_products' => $updatedProducts->count(),
                        'updated_categories' => $updatedCategories->count(),
                        'since' => $since
                    ]);
                }
            } else {
                // Initial load - get all data
                $allProducts = $productsQuery->get();
                $allCategories = $categoriesQuery->get();

                $response['data'] = [
                    'products' => $allProducts,
                    'categories' => $allCategories->keyBy('id'),
                    'products_count' => $allProducts->count(),
                    'categories_count' => $allCategories->count(),
                    'is_initial_load' => true
                ];

                Log::info('Initial products data loaded', [
                    'products_count' => $allProducts->count(),
                    'categories_count' => $allCategories->count()
                ]);
            }

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error getting products data', [
                'error' => $e->getMessage(),
                'since' => $request->input('since')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check stok untuk items tertentu sebelum transaksi
     */
    public function checkStok(Request $request)
    {
        try {
            $items = $request->input('items', []);
            $stockWarnings = [];
            $stockData = [];

            foreach ($items as $item) {
                $barang = Masterbarang::find($item['id']);

                if (!$barang) {
                    $stockWarnings[] = "Barang dengan ID {$item['id']} tidak ditemukan";
                    continue;
                }

                $stockData[$item['id']] = [
                    'nama' => $barang->nama,
                    'stok_tersedia' => $barang->stok,
                    'qty_diminta' => $item['qty']
                ];

                if ($barang->stok < $item['qty']) {
                    $stockWarnings[] = "Stok '{$barang->nama}' tidak mencukupi. Tersedia: {$barang->stok}, diminta: {$item['qty']}";
                }
            }

            return response()->json([
                'success' => empty($stockWarnings),
                'warnings' => $stockWarnings,
                'stock_data' => $stockData,
                'message' => empty($stockWarnings) ? 'Stok mencukupi' : implode(', ', $stockWarnings)
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking stock', [
                'error' => $e->getMessage(),
                'items' => $request->input('items')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa stok: ' . $e->getMessage()
            ], 500);
        }
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
            $kodeTransaksi = 'TRX' . date('YmdHis') . rand(100, 999);

            // Validasi stok sebelum simpan transaksi
            foreach ($request->items as $item) {
                $barang = Masterbarang::find($item['id']);
                if (!$barang || $barang->stok < $item['qty']) {
                    throw new \Exception("Stok barang '{$barang->nama}' tidak mencukupi");
                }
            }

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

            Log::info('Transaction successful', [
                'kode_transaksi' => $kodeTransaksi,
                'total_harga' => $request->total_harga
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'data' => [
                    'penjualan_id' => $penjualan->id,
                    'kode_transaksi' => $kodeTransaksi,
                    'kembalian' => $penjualan->kembalian,
                    'tanggal_transaksi' => $penjualan->tanggal_transaksi->format('d/m/Y H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Transaction failed', [
                'error' => $e->getMessage(),
                'items' => $request->items
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
