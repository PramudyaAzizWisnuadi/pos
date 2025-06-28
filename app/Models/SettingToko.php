<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SettingToko extends Model
{
    use HasFactory;

    protected $table = 'setting_tokos';

    protected $fillable = [
        'nama_toko',
        'alamat',
        'telepon',
        'logo',
        'email',
        'website',
        'deskripsi'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        // Clear cache saat setting diupdate
        static::saved(function () {
            Cache::forget('setting_toko');
        });

        static::deleted(function () {
            Cache::forget('setting_toko');
        });
    }

    // Singleton pattern - hanya ada 1 setting
    public static function getSetting()
    {
        return Cache::remember('setting_toko', 300, function () {
            return self::first() ?? self::create([
                'nama_toko' => config('app.name'),
                'alamat' => '',
                'telepon' => '',
                'email' => '',
                'website' => '',
                'deskripsi' => ''
            ]);
        });
    }
}
