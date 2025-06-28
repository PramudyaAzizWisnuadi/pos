# 📦 Sistem POS (Point of Sale)
Sistem Point of Sale (POS) modern yang dibangun dengan Laravel dan Bootstrap. Sistem ini menyediakan manajemen penjualan real-time dengan antarmuka yang user-friendly dan responsif.
## ✨ Fitur Utama
##### 🛒 Kasir (Cashier)
- Update Produk Real-time: Produk dan stok terupdate
- Manajemen Keranjang Belanja: Tambah, kurangi, dan hapus item dengan mudah
- Filter Kategori: Filter produk berdasarkan kategori
- Manajemen Stok: Sistem mencegah overselling secara otomatis
- Generate Struk: Buat dan cetak struk pembayaran
- Proses Pembayaran: Kalkulasi otomatis kembalian dan validasi pembayaran

##### 🎨 Antarmuka Pengguna
- Desain Glass Modern: UI dengan efek glass morphism
- Layout Responsif: Optimal di desktop, tablet, dan mobile
- Animasi Loading: Smooth loading dan efek transisi
- Badge Status Stok: Badge "Habis" untuk produk tanpa stok (stok angka disembunyikan)

##### 🛠️ Teknologi yang Digunakan
- Backend: Laravel 12
- Frontend: Bootstrap 5, JavaScript
- Database: SQLite (default), MySQL/PostgreSQL
- Icons: Font Awesome

##### 📋 Persyaratan Sistem
- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite (default) atau MySQL >= 5.7 atau PostgreSQL >= 10

##### 🚀 Instalasi
1. Clone Repository
`https://github.com/PramudyaAzizWisnuadi/pos`
2. Install Dependencies
`composer install`
3. Setup Environment
`cp .env.example .env`
`php artisan key:generate`
4. Database Setup
`php artisan migrate`
`php artisan db:seed`
5. Storage Link
`php artisan storage:link`
6. Jalankan Development Server
`php artisan serve`

##### 📁 Struktur Proyek
    pos-system/
    ├── app/
    │   ├── Http/Controllers/
    │   │   └── KasirController.php      # Controller utama POS
    │   └── Models/
    │       ├── Masterbarang.php         # Model produk
    │       ├── Kategori.php             # Model kategori
    │       ├── Penjualan.php            # Model penjualan
    │       └── DetailPenjualan.php      # Model detail penjualan
    ├── database/
    │   ├── database.sqlite              # Database SQLite
    │   ├── migrations/                  # Database migrations
    │   └── seeders/                     # Database seeders
    ├── resources/
    │   └── views/
    │       └── kasir/
    │           └── index.blade.php      # Antarmuka utama POS
    ├── routes/
    │   └── web.php                      # Routes aplikasi
    └── .env                             # Environment configuration

##### 🙏 Acknowledgments
- Laravel Framework - Backend framework
- Bootstrap - CSS framework
- Font Awesome - Icons
- Community contributors & testers

##### 🔗 Links
Instagram: pramudya_aziz
