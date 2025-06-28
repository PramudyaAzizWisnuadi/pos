<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\PenjualanController;

Route::get('/', function () {
    return redirect()->route('kasir.index');
});
Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
Route::get('/kasir/barang/{kategoriId}', [KasirController::class, 'getBarangByKategori']);
Route::post('/kasir/proses-penjualan', [KasirController::class, 'prosesPenjualan']);
Route::get('/penjualan/detail/{penjualan}', [PenjualanController::class, 'detail'])->name('penjualan.detail');
Route::get('/struk/print/{penjualan}', [StrukController::class, 'print'])->name('struk.print');
// Simplified endpoints - hanya 2 endpoint
Route::get('/kasir/products-data', [KasirController::class, 'getProductsData'])->name('kasir.products-data');
Route::post('/kasir/check-stok', [KasirController::class, 'checkStok'])->name('kasir.check-stok');
