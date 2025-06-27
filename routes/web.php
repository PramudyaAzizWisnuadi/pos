<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\PenjualanController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
Route::get('/kasir/barang/{kategoriId}', [KasirController::class, 'getBarangByKategori']);
Route::post('/kasir/proses-penjualan', [KasirController::class, 'prosesPenjualan']);
Route::get('/penjualan/detail/{penjualan}', [PenjualanController::class, 'detail'])->name('penjualan.detail');
Route::get('/struk/print/{penjualan}', [StrukController::class, 'print'])->name('struk.print');
