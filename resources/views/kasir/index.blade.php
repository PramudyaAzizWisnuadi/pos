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
                margin: 0 5px 5px;
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

            /* Badge habis styling */
            .badge.bg-danger {
                background: linear-gradient(45deg, #dc3545, #c82333) !important;
                font-weight: bold;
                padding: 4px 8px;
                font-size: 0.7rem;
                border-radius: 12px;
                box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
            }

            /* Stok habis card styling */
            .produk-card.stok-habis {
                opacity: 0.7;
                cursor: not-allowed !important;
                filter: grayscale(30%);
            }

            .produk-card.stok-habis:hover {
                transform: none !important;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1) !important;
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

            /* Print Styles */
            @media print {
                @page {
                    size: 80mm auto;
                    margin: 5mm;
                }

                body * {
                    visibility: hidden;
                }

                .print-only,
                .print-only * {
                    visibility: visible;
                }

                .print-only {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    font-family: 'Courier New', monospace;
                    font-size: 10px;
                    line-height: 1.2;
                    color: #000;
                }

                .print-header {
                    text-align: center;
                    margin-bottom: 15px;
                    border-bottom: 2px dashed #000;
                    padding-bottom: 10px;
                }

                .print-header h2 {
                    font-size: 16px;
                    margin: 5px 0;
                    font-weight: bold;
                }

                .print-header p {
                    font-size: 10px;
                    margin: 2px 0;
                }

                .print-divider {
                    border-top: 1px dashed #000;
                    margin: 10px 0;
                }

                .print-row {
                    display: flex;
                    justify-content: space-between;
                    margin: 3px 0;
                    font-size: 10px;
                }

                .print-item {
                    margin: 5px 0;
                    padding: 3px 0;
                }

                .print-item-name {
                    font-weight: bold;
                    margin-bottom: 2px;
                    font-size: 10px;
                }

                .print-item-detail {
                    display: flex;
                    justify-content: space-between;
                    font-size: 9px;
                }

                .print-total-section {
                    border-top: 2px dashed #000;
                    padding-top: 10px;
                    margin-top: 10px;
                }

                .print-grand-total {
                    font-weight: bold;
                    font-size: 12px;
                    border-top: 1px solid #000;
                    padding-top: 5px;
                    margin-top: 5px;
                }

                .print-footer {
                    text-align: center;
                    margin-top: 15px;
                    padding-top: 10px;
                    border-top: 2px dashed #000;
                    font-size: 9px;
                }

                .print-footer p {
                    margin: 2px 0;
                }
            }

            /* Styling untuk struk di modal */
            .struk-content {
                font-family: 'Courier New', monospace;
                font-size: 12px;
                line-height: 1.4;
                max-width: 350px;
                margin: 0 auto;
                color: #000;
            }

            .struk-header {
                text-align: center;
                margin-bottom: 20px;
                border-bottom: 2px dashed #333;
                padding-bottom: 15px;
            }

            .struk-header h4 {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 5px;
                color: #000;
            }

            .struk-header p {
                font-size: 11px;
                margin: 2px 0;
                color: #000;
            }

            .struk-divider {
                border-top: 1px dashed #333;
                margin: 15px 0;
            }

            .struk-row {
                display: flex;
                justify-content: space-between;
                margin: 5px 0;
                align-items: flex-start;
            }

            .struk-item {
                margin: 8px 0;
                padding: 5px 0;
            }

            .struk-item-name {
                font-weight: bold;
                margin-bottom: 3px;
                word-wrap: break-word;
            }

            .struk-item-detail {
                display: flex;
                justify-content: space-between;
                font-size: 11px;
            }

            .struk-total-section {
                border-top: 2px dashed #333;
                padding-top: 15px;
                margin-top: 15px;
            }

            .struk-grand-total {
                font-weight: bold;
                font-size: 16px;
                border-top: 1px solid #333;
                padding-top: 8px;
                margin-top: 8px;
            }

            .struk-footer {
                text-align: center;
                margin-top: 20px;
                padding-top: 15px;
                border-top: 2px dashed #333;
                font-size: 11px;
            }

            .struk-footer p {
                margin: 3px 0;
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
                            @if (setting_toko('logo'))
                                <img src="{{ asset('storage/' . setting_toko('logo')) }}" alt="Logo"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                            @else
                                <i class="fas fa-cash-register"></i>
                            @endif
                        </div>
                        <div class="logo-text">
                            <h2>{{ setting_toko('nama_toko') ?: config('app.name') }}</h2>
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
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <span class="price-tag">
                                                        Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                                    </span>
                                                    <!-- Hanya tampilkan badge habis, hilangkan display stok normal -->
                                                    @if ($barang->stok <= 0)
                                                        <span class="badge bg-danger ms-2">Habis</span>
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
            <div class="modal-dialog modal-dialog-centered">
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
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Tutup
                        </button>
                        <button type="button" class="btn btn-gradient" onclick="printStruk()">
                            <i class="fas fa-print me-1"></i> Cetak Struk
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            let keranjang = [];
            let totalHarga = 0;
            let updateInterval;
            let lastUpdateTime = null;
            let currentProducts = new Map();
            let currentCategories = new Map();

            // Initialize page
            document.addEventListener('DOMContentLoaded', function() {
                initializeRealTimeUpdates();
                loadInitialData();
            });

            // Load initial data
            function loadInitialData() {
                fetch('/kasir/products-data')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            processProductsData(data.data);
                            lastUpdateTime = data.last_update;
                            console.log('✅ Initial data loaded:', data.data.products_count, 'products,', data.data
                                .categories_count, 'categories');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading initial data:', error);
                    });
            }

            // Initialize real-time updates
            function initializeRealTimeUpdates() {
                // Check for updates every 5 seconds
                updateInterval = setInterval(checkForUpdates, 500);
                console.log('✅ Real-time updates initialized');
            }

            // Check for updates (simplified)
            function checkForUpdates() {
                if (!lastUpdateTime) return;

                fetch(`/kasir/products-data?since=${encodeURIComponent(lastUpdateTime)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.has_changes) {
                            processProductsData(data.data);
                            showUpdateNotification(data.data);
                            lastUpdateTime = data.last_update;
                        }
                    })
                    .catch(error => {
                        console.error('Error checking for updates:', error);
                    });
            }

            // Process products data (handle both initial load and updates)
            function processProductsData(data) {
                let hasNewProducts = false;
                let hasUpdatedProducts = false;

                // Process products
                if (data.products && data.products.length > 0) {
                    data.products.forEach(product => {
                        const isNew = !currentProducts.has(product.id.toString());

                        if (isNew && !data.is_initial_load) {
                            addNewProductToDisplay(product);
                            hasNewProducts = true;
                        } else if (!isNew && !data.is_initial_load) {
                            updateExistingProduct(product);
                            hasUpdatedProducts = true;
                        }

                        currentProducts.set(product.id.toString(), product);
                    });
                }

                // Process categories
                if (data.categories) {
                    Object.values(data.categories).forEach(category => {
                        const isNew = !currentCategories.has(category.id.toString());

                        if (isNew && !data.is_initial_load) {
                            addNewCategoryToDisplay(category);
                        }

                        currentCategories.set(category.id.toString(), category);
                    });
                }

                // Re-attach events if there are changes
                if (hasNewProducts || hasUpdatedProducts) {
                    updateEventListeners();
                }
            }

            // Add new product to display
            function addNewProductToDisplay(product) {
                const produkGrid = document.querySelector('.row.g-3');
                if (!produkGrid) return;

                const newProductHtml = createProductCardHtml(product);
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = newProductHtml;
                const newProductElement = tempDiv.firstElementChild;

                newProductElement.classList.add('new-product-animation');
                produkGrid.appendChild(newProductElement);

                console.log('➕ New product added:', product.nama);
            }

            // Create product card HTML (tanpa stock display normal, tapi tetap badge habis)
            function createProductCardHtml(product) {
                const kategoriName = product.kategori ? product.kategori.nama : 'Tanpa Kategori';
                const fotoUrl = product.foto ? `/storage/${product.foto}` :
                    'https://via.placeholder.com/150x120/e9ecef/6c757d?text=No+Image';
                const kategoriId = product.kategori_id || '';
                const stockBadge = product.stok <= 0 ? '<span class="badge bg-danger ms-2">Habis</span>' : '';

                return `
                    <div class="col-lg-3 col-md-4 col-sm-6 produk-item" data-kategori="${kategoriId}">
                        <div class="card h-100 produk-card ${product.stok <= 0 ? 'stok-habis' : ''}"
                             style="cursor: pointer;" data-id="${product.id}" data-nama="${product.nama}"
                             data-harga="${product.harga}" data-stok="${product.stok}">
                            <div class="card-body text-center p-3">
                                <div class="product-image-container mb-3">
                                    <img src="${fotoUrl}" alt="${product.nama}"
                                         class="product-image"
                                         onerror="this.src='https://via.placeholder.com/150x120/e9ecef/6c757d?text=No+Image'">
                                </div>
                                <h6 class="card-title mb-2 fw-bold" style="font-size: 0.9rem;">
                                    ${product.nama}
                                </h6>
                                <div class="d-flex justify-content-center align-items-center">
                                    <span class="price-tag">
                                        Rp ${parseFloat(product.harga).toLocaleString('id-ID')}
                                    </span>
                                    ${stockBadge}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Update existing product (hilangkan stock display, tapi update badge habis)
            function updateExistingProduct(product) {
                const productCard = document.querySelector(`.produk-card[data-id="${product.id}"]`);
                if (!productCard) return;

                // Update data attributes
                productCard.dataset.nama = product.nama;
                productCard.dataset.harga = product.harga;
                productCard.dataset.stok = product.stok;

                // Update display elements
                const namaElement = productCard.querySelector('.card-title');
                const hargaElement = productCard.querySelector('.price-tag');
                const badgeContainer = productCard.querySelector('.d-flex.justify-content-center');

                if (namaElement) namaElement.textContent = product.nama;
                if (hargaElement) hargaElement.textContent = `Rp ${parseFloat(product.harga).toLocaleString('id-ID')}`;

                // Update badge habis
                if (badgeContainer) {
                    // Remove existing badge
                    const existingBadge = badgeContainer.querySelector('.badge');
                    if (existingBadge) {
                        existingBadge.remove();
                    }

                    // Add badge habis if stock is 0
                    if (product.stok <= 0) {
                        const badgeHabis = document.createElement('span');
                        badgeHabis.className = 'badge bg-danger ms-2';
                        badgeHabis.textContent = 'Habis';
                        badgeContainer.appendChild(badgeHabis);

                        // Add stok-habis class
                        productCard.classList.add('stok-habis');
                    } else {
                        // Remove stok-habis class
                        productCard.classList.remove('stok-habis');
                    }
                }

                // Update cart if product is in cart
                updateCartItemIfExists(product);

                // Add update animation
                productCard.classList.add('product-updated-animation');
                setTimeout(() => {
                    productCard.classList.remove('product-updated-animation');
                }, 1000);

                console.log('🔄 Product updated:', product.nama);
            }

            // Add new category to display
            function addNewCategoryToDisplay(category) {
                const categoryNav = document.querySelector('.nav-tabs');
                if (!categoryNav) return;

                const newCategoryHtml = `
                    <li class="nav-item">
                        <button class="nav-link kategori-btn new-category-animation" data-kategori="${category.id}">
                            ${category.nama}
                        </button>
                    </li>
                `;

                const allCategoryTab = categoryNav.querySelector('li:last-child');
                if (allCategoryTab) {
                    allCategoryTab.insertAdjacentHTML('beforebegin', newCategoryHtml);
                } else {
                    categoryNav.insertAdjacentHTML('beforeend', newCategoryHtml);
                }

                console.log('➕ New category added:', category.nama);
            }

            // Update product stock status
            function updateProductStockStatus(productCard, stok) {
                productCard.classList.remove('stock-critical', 'stock-warning');

                if (stok <= 0) {
                    productCard.classList.add('stock-critical');
                } else if (stok <= 5) {
                    productCard.classList.add('stock-warning');
                }
            }

            // Update cart item if exists
            function updateCartItemIfExists(product) {
                const cartItem = keranjang.find(item => item.id === product.id.toString());
                if (cartItem) {
                    const oldQty = cartItem.qty;

                    cartItem.nama = product.nama;
                    cartItem.harga = parseFloat(product.harga);
                    cartItem.stok = parseInt(product.stok);

                    // Adjust quantity if exceeds new stock
                    if (cartItem.qty > cartItem.stok) {
                        cartItem.qty = Math.max(0, cartItem.stok);
                        if (cartItem.qty === 0) {
                            keranjang = keranjang.filter(item => item.id !== product.id.toString());
                            showStockAdjustmentNotification(product.nama, 'dihapus dari keranjang');
                        } else if (cartItem.qty !== oldQty) {
                            showStockAdjustmentNotification(product.nama, `disesuaikan menjadi ${cartItem.qty}`);
                        }
                        updateKeranjang();
                    }
                }
            }

            // Show update notification
            function showUpdateNotification(data) {
                if (!data.changes_count || data.changes_count === 0) return;

                const newProducts = data.products?.filter(p => !currentProducts.has(p.id.toString())).length || 0;
                const updatedProducts = (data.products?.length || 0) - newProducts;

                let message = '';
                if (newProducts > 0) message += `📦 ${newProducts} produk baru `;
                if (updatedProducts > 0) message += `🔄 ${updatedProducts} produk diperbarui `;

                if (message) {
                    showUpdateIndicator(message.trim());
                }
            }

            // Show visual update indicator
            function showUpdateIndicator(message) {
                const indicator = document.createElement('div');
                indicator.className = 'position-fixed top-0 end-0 m-3 alert alert-success alert-dismissible fade show';
                indicator.style.zIndex = '9999';
                indicator.innerHTML = `
                    <i class="fas fa-sync-alt fa-spin me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                document.body.appendChild(indicator);

                setTimeout(() => {
                    if (indicator.parentNode) {
                        indicator.remove();
                    }
                }, 3000);
            }

            // Show stock adjustment notification
            function showStockAdjustmentNotification(productName, action) {
                const message = `Produk "${productName}" ${action} karena perubahan stok`;
                showUpdateIndicator(`⚠️ ${message}`);
            }

            // Update event listeners (hilangkan stock notification untuk user)
            function updateEventListeners() {
                // Re-attach category click events
                document.querySelectorAll('.kategori-btn').forEach(btn => {
                    btn.replaceWith(btn.cloneNode(true));
                });

                document.querySelectorAll('.kategori-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.kategori-btn').forEach(link => link.classList.remove(
                            'active'));
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

                // Re-attach product click events (hilangkan stock alert, silent fail)
                document.querySelectorAll('.produk-card').forEach(card => {
                    card.replaceWith(card.cloneNode(true));
                });

                document.querySelectorAll('.produk-card').forEach(card => {
                    card.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const nama = this.dataset.nama;
                        const harga = parseFloat(this.dataset.harga);
                        const stok = parseInt(this.dataset.stok);

                        // Silent check - tidak tampilkan alert
                        if (stok <= 0) {
                            return; // Silent fail
                        }

                        const existingItem = keranjang.find(item => item.id === id);
                        const requestedQty = existingItem ? existingItem.qty + 1 : 1;

                        if (requestedQty > stok) {
                            return; // Silent fail jika stok tidak mencukupi
                        }

                        if (existingItem) {
                            existingItem.qty++;
                            existingItem.stok = stok;
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
            }

            // Clean up interval when page unloads
            window.addEventListener('beforeunload', function() {
                if (updateInterval) {
                    clearInterval(updateInterval);
                }
            });

            // Initialize event listeners on load
            document.addEventListener('DOMContentLoaded', function() {
                updateEventListeners();
            });

            // Update tampilan keranjang
            function updateKeranjang() {
                const keranjangContainer = document.getElementById('keranjang-items');

                if (keranjang.length === 0) {
                    keranjangContainer.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-shopping-cart"></i>
                            <p class="mb-1 fw-bold">Keranjang Kosong</p>
                            <small class="text-muted">Pilih produk untuk mulai transaksi</small>
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
                            <div class="keranjang-item p-3 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">${item.nama}</h6>
                                        <small class="text-muted">Rp ${item.harga.toLocaleString('id-ID')} x ${item.qty}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="btn-group btn-group-sm mb-1">
                                            <button class="btn btn-outline-danger" onclick="kurangiQty(${index})">-</button>
                                            <span class="btn btn-outline-secondary">${item.qty}</span>
                                            <button class="btn btn-outline-success" onclick="tambahQty(${index})">+</button>
                                            <button class="btn btn-outline-danger" onclick="hapusItem(${index})">×</button>
                                        </div>
                                        <div>
                                            <strong class="text-success">Rp ${subtotal.toLocaleString('id-ID')}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    keranjangContainer.innerHTML = html;
                }

                document.getElementById('total-harga').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
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

                document.getElementById('kembalian').value = kembalian >= 0 ? 'Rp ' + kembalian.toLocaleString('id-ID') :
                    'Rp 0';

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

                const btnBayar = this;
                const originalText = btnBayar.innerHTML;
                btnBayar.disabled = true;
                btnBayar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';

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
                            // Jangan reset keranjang di sini, biarkan sampai selesai print
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memproses transaksi');
                    })
                    .finally(() => {
                        btnBayar.disabled = false;
                        btnBayar.innerHTML = originalText;
                        hitungKembalian();
                    });
            });

            // Tampilkan struk (HANYA SATU FUNGSI INI)
            function tampilkanStruk(data) {
                // Simpan data transaksi dan keranjang untuk print
                window.lastTransactionData = data;
                window.printKeranjang = [...keranjang]; // Copy keranjang
                window.printTotalHarga = totalHarga;
                window.printJumlahBayar = parseFloat(document.getElementById('jumlah-bayar').value);

                const strukContent = document.getElementById('struk-content');
                let itemsHtml = '';

                keranjang.forEach((item, index) => {
                    itemsHtml += `
                        <div class="struk-item">
                            <div class="struk-item-name">${index + 1}. ${item.nama}</div>
                            <div class="struk-item-detail">
                                <span>${item.qty} x Rp ${item.harga.toLocaleString('id-ID')}</span>
                                <span>Rp ${(item.harga * item.qty).toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                    `;
                });

                // Format tanggal Indonesia
                const now = new Date();
                const options = {
                    timeZone: 'Asia/Jakarta',
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                const jakartaTime = now.toLocaleString('id-ID', options);
                const kembalian = data.data ? data.data.kembalian : data.kembalian;
                const kodeTransaksi = data.data ? data.data.kode_transaksi : data.kode_transaksi;

                strukContent.innerHTML = `
                    <div class="struk-content">
                        <!-- Header Toko -->
                        <div class="struk-header">
                            <h4>{{ setting_toko('nama_toko') ?: 'TOKO POS' }}</h4>
                            <p>{{ setting_toko('alamat') ?: 'Alamat tidak tersedia' }}</p>
                            <p>Telp: {{ setting_toko('telepon') ?: '-' }}</p>
                            @if (setting_toko('email'))
                                <p>Email: {{ setting_toko('email') }}</p>
                            @endif
                        </div>

                        <!-- Info Transaksi -->
                        <div class="struk-row">
                            <span>No. Transaksi:</span>
                            <span>${kodeTransaksi}</span>
                        </div>
                        <div class="struk-row">
                            <span>Tanggal:</span>
                            <span>${jakartaTime} WIB</span>
                        </div>
                        <div class="struk-row">
                            <span>Kasir:</span>
                            <span>Admin</span>
                        </div>
                        <div class="struk-row">
                            <span>Customer:</span>
                            <span>Umum</span>
                        </div>

                        <div class="struk-divider"></div>

                        <!-- Detail Items -->
                        <div class="struk-items-section">
                            ${itemsHtml}
                        </div>

                        <!-- Total Section -->
                        <div class="struk-total-section">
                            <div class="struk-row">
                                <span>Subtotal:</span>
                                <span>Rp ${totalHarga.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="struk-row">
                                <span>Pajak (0%):</span>
                                <span>Rp 0</span>
                            </div>
                            <div class="struk-row">
                                <span>Diskon:</span>
                                <span>Rp 0</span>
                            </div>
                            <div class="struk-row struk-grand-total">
                                <span>TOTAL:</span>
                                <span>Rp ${totalHarga.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="struk-divider"></div>
                            <div class="struk-row">
                                <span>Tunai:</span>
                                <span>Rp ${parseFloat(document.getElementById('jumlah-bayar').value).toLocaleString('id-ID')}</span>
                            </div>
                            <div class="struk-row">
                                <span>Kembalian:</span>
                                <span>Rp ${kembalian.toLocaleString('id-ID')}</span>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="struk-divider"></div>
                        <div class="struk-row">
                            <span>Total Item:</span>
                            <span>${keranjang.reduce((total, item) => total + item.qty, 0)} pcs</span>
                        </div>
                        <div class="struk-row">
                            <span>Jenis Barang:</span>
                            <span>${keranjang.length} jenis</span>
                        </div>

                        <!-- Footer -->
                        <div class="struk-footer">
                            <p><strong>Terima kasih atas kunjungan Anda!</strong></p>
                            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
                            <p>Simpan struk ini sebagai bukti pembelian</p>
                            <p>---</p>
                            <p>Dicetak: ${jakartaTime} WIB</p>
                            <p>Sistem POS v1.0</p>
                        </div>
                    </div>
                `;

                new bootstrap.Modal(document.getElementById('modalStruk')).show();
            }

            // Fungsi untuk print struk langsung
            function printStruk() {
                if (!window.lastTransactionData || !window.printKeranjang) {
                    alert('Data transaksi tidak tersedia');
                    return;
                }

                const data = window.lastTransactionData;
                const keranjangPrint = window.printKeranjang;
                const totalHargaPrint = window.printTotalHarga;
                const jumlahBayarPrint = window.printJumlahBayar;

                const kembalian = data.data ? data.data.kembalian : data.kembalian;
                const kodeTransaksi = data.data ? data.data.kode_transaksi : data.kode_transaksi;

                // Format tanggal Indonesia
                const now = new Date();
                const options = {
                    timeZone: 'Asia/Jakarta',
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                const jakartaTime = now.toLocaleString('id-ID', options);

                // Generate items HTML untuk print
                let printItemsHtml = '';
                keranjangPrint.forEach((item, index) => {
                    printItemsHtml += `
                        <div class="print-item">
                            <div class="print-item-name">${index + 1}. ${item.nama}</div>
                            <div class="print-item-detail">
                                <span>${item.qty} x Rp ${item.harga.toLocaleString('id-ID')}</span>
                                <span>Rp ${(item.harga * item.qty).toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                    `;
                });

                // Create print window dengan window.open
                const printWindow = window.open('', '_blank', 'width=400,height=600');

                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Struk Pembayaran</title>
                        <style>
                            @page {
                                size: 80mm auto;
                                margin: 5mm;
                            }

                            body {
                                font-family: 'Courier New', monospace;
                                font-size: 10px;
                                line-height: 1.2;
                                margin: 0;
                                padding: 10px;
                                color: #000;
                            }

                            .print-header {
                                text-align: center;
                                margin-bottom: 15px;
                                border-bottom: 2px dashed #000;
                                padding-bottom: 10px;
                            }

                            .print-header h2 {
                                font-size: 16px;
                                margin: 5px 0;
                                font-weight: bold;
                            }

                            .print-header p {
                                font-size: 10px;
                                margin: 2px 0;
                            }

                            .print-divider {
                                border-top: 1px dashed #000;
                                margin: 10px 0;
                            }

                            .print-row {
                                display: flex;
                                justify-content: space-between;
                                margin: 3px 0;
                                font-size: 10px;
                            }

                            .print-item {
                                margin: 5px 0;
                                padding: 3px 0;
                            }

                            .print-item-name {
                                font-weight: bold;
                                margin-bottom: 2px;
                                font-size: 10px;
                            }

                            .print-item-detail {
                                display: flex;
                                justify-content: space-between;
                                font-size: 9px;
                            }

                            .print-total-section {
                                border-top: 2px dashed #000;
                                padding-top: 10px;
                                margin-top: 10px;
                            }

                            .print-grand-total {
                                font-weight: bold;
                                font-size: 12px;
                                border-top: 1px solid #000;
                                padding-top: 5px;
                                margin-top: 5px;
                            }

                            .print-footer {
                                text-align: center;
                                margin-top: 15px;
                                padding-top: 10px;
                                border-top: 2px dashed #000;
                                font-size: 9px;
                            }

                            .print-footer p {
                                margin: 2px 0;
                            }
                        </style>
                    </head>
                    <body>
                        <!-- Header Toko -->
                        <div class="print-header">
                            <h2>{{ setting_toko('nama_toko') ?: 'TOKO POS' }}</h2>
                            <p>{{ setting_toko('alamat') ?: 'Alamat tidak tersedia' }}</p>
                            <p>Telp: {{ setting_toko('telepon') ?: '-' }}</p>
                            @if (setting_toko('email'))
                                <p>Email: {{ setting_toko('email') }}</p>
                            @endif
                        </div>

                        <!-- Info Transaksi -->
                        <div class="print-row">
                            <span>No. Transaksi:</span>
                            <span>${kodeTransaksi}</span>
                        </div>
                        <div class="print-row">
                            <span>Tanggal:</span>
                            <span>${jakartaTime} WIB</span>
                        </div>
                        <div class="print-row">
                            <span>Kasir:</span>
                            <span>Admin</span>
                        </div>
                        <div class="print-row">
                            <span>Customer:</span>
                            <span>Umum</span>
                        </div>

                        <div class="print-divider"></div>

                        <!-- Detail Items -->
                        ${printItemsHtml}

                        <!-- Total Section -->
                        <div class="print-total-section">
                            <div class="print-row">
                                <span>Subtotal:</span>
                                <span>Rp ${totalHargaPrint.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="print-row">
                                <span>Pajak (0%):</span>
                                <span>Rp 0</span>
                            </div>
                            <div class="print-row">
                                <span>Diskon:</span>
                                <span>Rp 0</span>
                            </div>
                            <div class="print-row print-grand-total">
                                <span>TOTAL:</span>
                                <span>Rp ${totalHargaPrint.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="print-divider"></div>
                            <div class="print-row">
                                <span>Tunai:</span>
                                <span>Rp ${jumlahBayarPrint.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="print-row">
                                <span>Kembalian:</span>
                                <span>Rp ${kembalian.toLocaleString('id-ID')}</span>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="print-divider"></div>
                        <div class="print-row">
                            <span>Total Item:</span>
                            <span>${keranjangPrint.reduce((total, item) => total + item.qty, 0)} pcs</span>
                        </div>
                        <div class="print-row">
                            <span>Jenis Barang:</span>
                            <span>${keranjangPrint.length} jenis</span>
                        </div>

                        <!-- Footer -->
                        <div class="print-footer">
                            <p><strong>Terima kasih atas kunjungan Anda!</strong></p>
                            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
                            <p>Simpan struk ini sebagai bukti pembelian</p>
                            <p>---</p>
                            <p>Dicetak: ${jakartaTime} WIB</p>
                            <p>Sistem POS v1.0</p>
                        </div>
                    </body>
                    </html>
                `);

                printWindow.document.close();
                printWindow.focus();

                // Print setelah dokumen selesai load
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();

                    // Reset keranjang setelah print selesai
                    resetKeranjang();
                }, 500);
            }

            // Reset keranjang
            document.getElementById('btn-reset').addEventListener('click', resetKeranjang);

            function resetKeranjang() {
                keranjang = [];
                document.getElementById('jumlah-bayar').value = '';
                document.getElementById('kembalian').value = '';
                updateKeranjang();

                // Clear window print data
                window.lastTransactionData = null;
                window.printKeranjang = null;
                window.printTotalHarga = 0;
                window.printJumlahBayar = 0;
            }
        </script>
    </body>

</html>
