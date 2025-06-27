<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistem Kasir - {{ config('app.name') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                background-attachment: fixed;
                min-height: 100vh;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            /* Custom Background Pattern */
            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image:
                    radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
                background-size: 50px 50px;
                z-index: -1;
            }

            /* Header dengan Logo */
            .header-custom {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                padding: 15px 0;
                margin-bottom: 20px;
            }

            .logo-container {
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .logo-icon {
                width: 50px;
                height: 50px;
                background: linear-gradient(45deg, #667eea, #764ba2);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 24px;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            }

            .logo-text {
                color: #333;
                margin: 0;
            }

            .logo-text h2 {
                margin: 0;
                font-weight: 700;
                background: linear-gradient(45deg, #667eea, #764ba2);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .logo-text small {
                color: #666;
                font-size: 0.9rem;
            }

            /* Card dengan Glass Effect */
            .card-glass {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                border-radius: 15px;
            }

            .card-header-glass {
                background: linear-gradient(45deg, #667eea, #764ba2);
                color: white;
                border-radius: 15px 15px 0 0 !important;
                border: none;
                padding: 15px 20px;
            }

            .produk-card {
                transition: all 0.3s ease;
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.9);
                border: 1px solid rgba(255, 255, 255, 0.3);
                overflow: hidden;
                backdrop-filter: blur(5px);
            }

            .produk-card:hover {
                transform: translateY(-5px) scale(1.02);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
                background: rgba(255, 255, 255, 1);
            }

            .produk-card.stok-habis {
                opacity: 0.5;
                cursor: not-allowed !important;
            }

            .product-image-container {
                width: 100%;
                height: 120px;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                border-radius: 8px;
                background: linear-gradient(45deg, #f8f9fa, #e9ecef);
                position: relative;
            }

            .product-image {
                max-width: 100%;
                max-height: 100%;
                width: auto;
                height: auto;
                object-fit: contain;
                border-radius: 8px;
                transition: transform 0.3s ease;
            }

            .produk-card:hover .product-image {
                transform: scale(1.1);
            }

            /* Tab Kategori */
            .kategori-btn {
                border-radius: 25px;
                padding: 8px 20px;
                margin: 0 5px;
                border: 2px solid transparent;
                background: rgba(102, 126, 234, 0.1);
                color: #667eea;
                transition: all 0.3s ease;
                font-weight: 500;
            }

            .kategori-btn.active {
                background: linear-gradient(45deg, #667eea, #764ba2);
                color: white;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            }

            .kategori-btn:hover {
                background: rgba(102, 126, 234, 0.2);
                transform: translateY(-2px);
                color: #667eea;
            }

            /* Keranjang Items */
            .keranjang-item {
                background: linear-gradient(45deg, #f8f9fa, #ffffff);
                border-radius: 12px;
                border: 1px solid rgba(102, 126, 234, 0.1);
                transition: all 0.3s ease;
                animation: slideIn 0.3s ease;
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateX(30px);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            /* Buttons */
            .btn-gradient {
                background: linear-gradient(45deg, #667eea, #764ba2);
                border: none;
                border-radius: 12px;
                color: white;
                font-weight: 600;
                transition: all 0.3s ease;
                padding: 12px 20px;
            }

            .btn-gradient:hover {
                background: linear-gradient(45deg, #764ba2, #667eea);
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
                color: white;
            }

            .btn-gradient:disabled {
                opacity: 0.6;
                transform: none;
                box-shadow: none;
            }

            /* Total Display */
            .total-display {
                background: linear-gradient(45deg, #28a745, #20c997);
                color: white;
                border-radius: 12px;
                padding: 15px;
                font-size: 1.3rem;
                font-weight: bold;
                text-align: center;
                box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);
            }

            /* Form Controls */
            .form-control-custom {
                border-radius: 10px;
                border: 2px solid rgba(102, 126, 234, 0.2);
                padding: 12px 15px;
                transition: all 0.3s ease;
                background: rgba(255, 255, 255, 0.9);
            }

            .form-control-custom:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                background: white;
            }

            /* Status Info */
            .status-info {
                background: rgba(255, 255, 255, 0.9);
                border-radius: 10px;
                padding: 10px 15px;
                margin-bottom: 15px;
                border-left: 4px solid #667eea;
            }

            /* Empty State */
            .empty-state {
                text-align: center;
                padding: 40px 20px;
                color: #6c757d;
            }

            .empty-state i {
                font-size: 3rem;
                margin-bottom: 15px;
                opacity: 0.5;
            }

            /* Price Tags */
            .price-tag {
                background: linear-gradient(45deg, #28a745, #20c997);
                color: white;
                padding: 4px 12px;
                border-radius: 15px;
                font-weight: bold;
                font-size: 0.85rem;
            }

            /* Modal Customization */
            .modal-content {
                border-radius: 15px;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(10px);
            }

            .modal-header {
                background: linear-gradient(45deg, #667eea, #764ba2);
                color: white;
                border-radius: 15px 15px 0 0;
                border: none;
            }
        </style>
    </head>

    <body>
        <!-- Header dengan Logo -->
        <div class="header-custom">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo-container">
                        <div class="logo-icon">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <div class="logo-text">
                            <h2>{{ config('app.name') }}</h2>
                            <small>Point of Sale System</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="status-info text-center">
                            <i class="fas fa-calendar-alt text-primary"></i>
                            <small class="d-block fw-bold">{{ date('d/m/Y') }}</small>
                            <small class="text-muted">{{ date('H:i') }}</small>
                        </div>
                        <div class="status-info text-center">
                            <i class="fas fa-user-circle text-success"></i>
                            <small class="d-block fw-bold">Kasir</small>
                            <small class="text-muted">Online</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid px-4">
            <div class="row g-4">
                <!-- Sidebar Kategori & Produk -->
                <div class="col-lg-8">
                    <div class="card card-glass">
                        <div class="card-header card-header-glass">
                            <h5 class="mb-0">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Pilih Produk
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Tab Kategori -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                                <h6 class="mb-0 text-muted">
                                    <i class="fas fa-filter me-1"></i> Filter Kategori:
                                </h6>
                                <div class="d-flex flex-wrap" id="kategori-tabs">
                                    <button class="kategori-btn active" data-kategori="all">
                                        <i class="fas fa-th-large me-1"></i> Semua
                                    </button>
                                    @foreach ($kategoris as $kategori)
                                        <button class="kategori-btn" data-kategori="{{ $kategori->id }}">
                                            <i class="fas fa-tag me-1"></i> {{ $kategori->nama_kategori }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Grid Produk -->
                            <div class="row g-3" id="produk-grid">
                                @foreach ($barangs as $barang)
                                    <div class="col-lg-3 col-md-4 col-sm-6 produk-item"
                                        data-kategori="{{ $barang->kategori_id }}">
                                        <div class="card h-100 produk-card {{ $barang->stok <= 0 ? 'stok-habis' : '' }}"
                                            style="cursor: pointer;" data-id="{{ $barang->id }}"
                                            data-nama="{{ $barang->nama }}" data-harga="{{ $barang->harga }}"
                                            data-stok="{{ $barang->stok }}">
                                            <div class="card-body text-center p-3">
                                                <div class="product-image-container mb-3">
                                                    @if ($barang->foto)
                                                        <img src="{{ asset('storage/' . $barang->foto) }}"
                                                            class="product-image" alt="{{ $barang->nama }}"
                                                            onerror="this.src='https://via.placeholder.com/150x120/e9ecef/6c757d?text=No+Image'">
                                                    @else
                                                        <img src="https://via.placeholder.com/150x120/e9ecef/6c757d?text=No+Image"
                                                            class="product-image" alt="No Image">
                                                    @endif
                                                </div>
                                                <h6 class="card-title mb-2 fw-bold" style="font-size: 0.9rem;">
                                                    {{ $barang->nama }}
                                                </h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="price-tag">
                                                        Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                                    </span>
                                                    @if ($barang->stok <= 0)
                                                        <span class="badge bg-danger">Habis</span>
                                                    @else
                                                        <small class="text-muted">
                                                            <i class="fas fa-boxes"></i> {{ $barang->stok }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Keranjang & Pembayaran -->
                <div class="col-lg-4">
                    <div class="card card-glass">
                        <div class="card-header card-header-glass">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>
                                Keranjang Belanja
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div id="keranjang-items" style="max-height: 400px; overflow-y: auto;">
                                <div class="empty-state">
                                    <i class="fas fa-shopping-cart"></i>
                                    <p class="mb-1 fw-bold">Keranjang Kosong</p>
                                    <small class="text-muted">Pilih produk untuk mulai transaksi</small>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Total -->
                            <div class="total-display mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>TOTAL:</span>
                                    <span id="total-harga">Rp 0</span>
                                </div>
                            </div>

                            <!-- Input Pembayaran -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-money-bill-wave me-1"></i> Jumlah Bayar:
                                </label>
                                <input type="number" class="form-control form-control-custom" id="jumlah-bayar"
                                    placeholder="Masukkan jumlah bayar">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-hand-holding-usd me-1"></i> Kembalian:
                                </label>
                                <input type="text" class="form-control form-control-custom" id="kembalian" readonly>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-grid gap-2">
                                <button class="btn btn-gradient btn-lg" id="btn-bayar" disabled>
                                    <i class="fas fa-credit-card me-2"></i> BAYAR SEKARANG
                                </button>
                                <button class="btn btn-outline-danger" id="btn-reset">
                                    <i class="fas fa-trash-alt me-2"></i> Reset Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Struk -->
        <div class="modal fade" id="modalStruk" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-receipt me-2"></i> Struk Pembayaran
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="struk-content">
                        <!-- Struk akan ditampilkan di sini -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-gradient" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // ...existing JavaScript code remains the same...
            let keranjang = [];
            let totalHarga = 0;

            // Filter produk berdasarkan kategori
            document.querySelectorAll('.kategori-btn').forEach(tab => {
                tab.addEventListener('click', function() {
                    // Update active tab
                    document.querySelectorAll('.kategori-btn').forEach(link => link.classList.remove('active'));
                    this.classList.add('active');

                    const kategoriId = this.dataset.kategori;
                    const produkItems = document.querySelectorAll('.produk-item');

                    produkItems.forEach(item => {
                        if (kategoriId === 'all' || item.dataset.kategori === kategoriId) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });


            // Tambah produk ke keranjang
            document.querySelectorAll('.produk-card').forEach(card => {
                card.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    const harga = parseFloat(this.dataset.harga);
                    const stok = parseInt(this.dataset.stok);

                    if (stok <= 0) {
                        alert('Stok tidak tersedia!');
                        return;
                    }

                    // Cek apakah produk sudah ada di keranjang
                    const existingItem = keranjang.find(item => item.id === id);

                    if (existingItem) {
                        if (existingItem.qty < stok) {
                            existingItem.qty++;
                        } else {
                            alert('Stok tidak mencukupi!');
                            return;
                        }
                    } else {
                        keranjang.push({
                            id: id,
                            nama: nama,
                            harga: harga,
                            qty: 1,
                            stok: stok
                        });
                    }

                    updateKeranjang();
                });
            });

            // Update tampilan keranjang
            function updateKeranjang() {
                const keranjangContainer = document.getElementById('keranjang-items');

                if (keranjang.length === 0) {
                    keranjangContainer.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                    <p>Keranjang kosong</p>
                </div>
            `;
                    totalHarga = 0;
                } else {
                    let html = '';
                    totalHarga = 0;

                    keranjang.forEach((item, index) => {
                        const subtotal = item.harga * item.qty;
                        totalHarga += subtotal;

                        html += `
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                        <div class="flex-grow-1">
                            <small class="fw-bold">${item.nama}</small><br>
                            <small class="text-muted">Rp ${item.harga.toLocaleString()} x ${item.qty}</small>
                        </div>
                        <div class="text-end">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-danger btn-sm" onclick="kurangiQty(${index})">-</button>
                                <button class="btn btn-outline-primary btn-sm" onclick="tambahQty(${index})">+</button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="hapusItem(${index})">×</button>
                            </div>
                            <div class="mt-1">
                                <small class="fw-bold">Rp ${subtotal.toLocaleString()}</small>
                            </div>
                        </div>
                    </div>
                `;
                    });

                    keranjangContainer.innerHTML = html;
                }

                document.getElementById('total-harga').textContent = 'Rp ' + totalHarga.toLocaleString();
                hitungKembalian();
            }

            // Fungsi manipulasi keranjang
            function tambahQty(index) {
                if (keranjang[index].qty < keranjang[index].stok) {
                    keranjang[index].qty++;
                    updateKeranjang();
                } else {
                    alert('Stok tidak mencukupi!');
                }
            }

            function kurangiQty(index) {
                if (keranjang[index].qty > 1) {
                    keranjang[index].qty--;
                    updateKeranjang();
                }
            }

            function hapusItem(index) {
                keranjang.splice(index, 1);
                updateKeranjang();
            }

            // Hitung kembalian
            document.getElementById('jumlah-bayar').addEventListener('input', hitungKembalian);

            function hitungKembalian() {
                const jumlahBayar = parseFloat(document.getElementById('jumlah-bayar').value) || 0;
                const kembalian = jumlahBayar - totalHarga;

                document.getElementById('kembalian').value = kembalian >= 0 ? 'Rp ' + kembalian.toLocaleString() : 'Rp 0';

                // Enable/disable tombol bayar
                const btnBayar = document.getElementById('btn-bayar');
                btnBayar.disabled = !(keranjang.length > 0 && jumlahBayar >= totalHarga);
            }

            // Proses pembayaran
            document.getElementById('btn-bayar').addEventListener('click', function() {
                if (keranjang.length === 0) {
                    alert('Keranjang kosong!');
                    return;
                }

                const jumlahBayar = parseFloat(document.getElementById('jumlah-bayar').value);

                if (jumlahBayar < totalHarga) {
                    alert('Jumlah bayar tidak mencukupi!');
                    return;
                }

                // Kirim data ke server
                fetch('/kasir/proses-penjualan', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            items: keranjang,
                            total_harga: totalHarga,
                            jumlah_bayar: jumlahBayar
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            tampilkanStruk(data);
                            resetKeranjang();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memproses transaksi');
                    });
            });

            // Tampilkan struk
            function tampilkanStruk(data) {
                const strukContent = document.getElementById('struk-content');
                let itemsHtml = '';

                keranjang.forEach(item => {
                    itemsHtml += `
                <div class="d-flex justify-content-between">
                    <span>${item.nama} (${item.qty}x)</span>
                    <span>Rp ${(item.harga * item.qty).toLocaleString()}</span>
                </div>
            `;
                });

                strukContent.innerHTML = `
            <div class="text-center mb-3">
                <h4>{{ config('app.name') }}</h4>
                <p class="mb-1">Jl. Raya Cepu No. 123</p>
                <p>Telp: 0812-3456-7890</p>
            </div>
            <hr>
            <div class="mb-2">
                <strong>No. Transaksi: ${data.kode_transaksi}</strong><br>
                <small>Tanggal: ${new Date().toLocaleString()}</small>
            </div>
            <hr>
            ${itemsHtml}
            <hr>
            <div class="d-flex justify-content-between">
                <strong>Total:</strong>
                <strong>Rp ${totalHarga.toLocaleString()}</strong>
            </div>
            <div class="d-flex justify-content-between">
                <span>Bayar:</span>
                <span>Rp ${parseFloat(document.getElementById('jumlah-bayar').value).toLocaleString()}</span>
            </div>
            <div class="d-flex justify-content-between">
                <strong>Kembalian:</strong>
                <strong>Rp ${data.kembalian.toLocaleString()}</strong>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">Terima kasih atas kunjungan Anda!</p>
            </div>
        `;

                new bootstrap.Modal(document.getElementById('modalStruk')).show();
            }

            // Reset keranjang
            document.getElementById('btn-reset').addEventListener('click', resetKeranjang);

            function resetKeranjang() {
                keranjang = [];
                document.getElementById('jumlah-bayar').value = '';
                document.getElementById('kembalian').value = '';
                updateKeranjang();
            }
        </script>
    </body>

</html>
