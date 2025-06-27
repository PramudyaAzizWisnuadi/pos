<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            'Laptop',
            'Mouse',
            'Keyboard',
            'Monitor',
            'Printer',
            'CPU',
            'Aksesoris Komputer',
            'Perangkat Jaringan',
        ];

        foreach ($kategoris as $kategori) {
            DB::table('kategoris')->insert([
                'nama_kategori' => $kategori,
                'slug' => Str::slug($kategori),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
