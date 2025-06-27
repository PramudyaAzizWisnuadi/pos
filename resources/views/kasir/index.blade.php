<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistem Kasir - POS</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            .produk-card {
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .produk-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .produk-card.stok-habis {
                opacity: 0.6;
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
                background-color: #f8f9fa;
            }

            .product-image {
                max-width: 100%;
                max-height: 100%;
                width: auto;
                height: auto;
                object-fit: contain;
                border-radius: 8px;
            }

            .product-image-placeholder {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #e9ecef;
                border-radius: 8px;
            }
        </style>
    </head>

    <body class="bg-light">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar Kategori & Produk -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-shopping-cart"></i> Pilih Produk</h5>
                        </div>
                        <div class="card-body">
                            <!-- Tab Kategori -->
                            <ul class="nav nav-pills mb-3" id="kategori-tabs">
                                <li class="nav-item">
                                    <button class="nav-link active kategori-btn" data-kategori="all">Semua</button>
                                </li>
                                @foreach ($kategoris as $kategori)
                                    <li class="nav-item">
                                        <button class="nav-link kategori-btn"
                                            data-kategori="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</button>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Grid Produk -->
                            <div class="row" id="produk-grid">
                                @foreach ($barangs as $barang)
                                    <div class="col-md-3 mb-3 produk-item" data-kategori="{{ $barang->kategori_id }}">
                                        <div class="card h-100 produk-card" style="cursor: pointer;"
                                            data-id="{{ $barang->id }}" data-nama="{{ $barang->nama }}"
                                            data-harga="{{ $barang->harga }}" data-stok="{{ $barang->stok }}">
                                            <div class="card-body text-center p-2">
                                                <div class="product-image-container mb-2">
                                                    @if ($barang->foto)
                                                        <img src="{{ asset('storage/' . $barang->foto) }}"
                                                            class="product-image" alt="{{ $barang->nama }}">
                                                    @else
                                                        <div class="product-image-placeholder">
                                                            <i class="fas fa-image fa-2x text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <h6 class="card-title mb-1" style="font-size: 0.9rem;">
                                                    {{ $barang->nama }}</h6>
                                                <p class="card-text mb-0">
                                                    <strong class="text-success">Rp
                                                        {{ number_format($barang->harga, 0, ',', '.') }}</strong>
                                                </p>
                                                @if ($barang->stok <= 0)
                                                    <small class="text-danger">Stok Habis</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Keranjang & Pembayaran -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-receipt"></i> Keranjang Belanja</h5>
                        </div>
                        <div class="card-body">
                            <div id="keranjang-items">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                    <p>Keranjang kosong</p>
                                </div>
                            </div>

                            <hr>

                            <!-- Total -->
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Total:</strong>
                                <strong id="total-harga" class="text-success">Rp 0</strong>
                            </div>

                            <!-- Input Pembayaran -->
                            <div class="mb-3">
                                <label class="form-label">Jumlah Bayar:</label>
                                <input type="number" class="form-control" id="jumlah-bayar" placeholder="0">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kembalian:</label>
                                <input type="text" class="form-control" id="kembalian" readonly>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-grid gap-2">
                                <button class="btn btn-success btn-lg" id="btn-bayar" disabled>
                                    <i class="fas fa-money-bill"></i> Bayar
                                </button>
                                <button class="btn btn-secondary" id="btn-reset">
                                    <i class="fas fa-trash"></i> Reset
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
                        <h5 class="modal-title">Struk Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="struk-content">
                        <!-- Struk akan ditampilkan di sini -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="window.print()">Cetak</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            let keranjang = [];
            let totalHarga = 0;

            // Filter produk berdasarkan kategori
            document.querySelectorAll('.kategori-btn').forEach(tab => {
                tab.addEventListener('click', function() {
                    console.log('Kategori diklik:', this.dataset.kategori); // Debug log

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
                <h4>TOKO POS</h4>
                <p class="mb-1">Jl. Contoh No. 123</p>
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
