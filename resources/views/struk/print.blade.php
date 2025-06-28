<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Struk - {{ $penjualan->kode_transaksi }}</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Courier New', monospace;
                font-size: 12px;
                line-height: 1.4;
                padding: 20px;
                background-color: #f5f5f5;
            }

            .struk {
                width: 350px;
                margin: 0 auto;
                background: white;
                border: 1px solid #ddd;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
                border-bottom: 2px dashed #000;
                padding-bottom: 15px;
            }

            .header h2 {
                font-size: 20px;
                font-weight: bold;
                margin-bottom: 5px;
            }

            .header p {
                font-size: 11px;
                margin: 2px 0;
            }

            .divider {
                border-top: 1px dashed #000;
                margin: 15px 0;
            }

            .info-section {
                margin-bottom: 15px;
            }

            .row {
                display: flex;
                justify-content: space-between;
                margin: 5px 0;
                align-items: flex-start;
            }

            .row span:first-child {
                font-weight: bold;
                min-width: 120px;
            }

            .row span:last-child {
                text-align: right;
                flex: 1;
            }

            .items-section {
                margin: 15px 0;
            }

            .item {
                margin: 8px 0;
                padding: 5px 0;
            }

            .item-name {
                font-weight: bold;
                margin-bottom: 3px;
                word-wrap: break-word;
            }

            .item-detail {
                display: flex;
                justify-content: space-between;
                font-size: 11px;
            }

            .item-qty {
                color: #666;
            }

            .item-price {
                font-weight: bold;
            }

            .total-section {
                border-top: 2px dashed #000;
                padding-top: 15px;
                margin-top: 15px;
            }

            .total-row {
                display: flex;
                justify-content: space-between;
                margin: 8px 0;
                font-size: 13px;
            }

            .grand-total {
                font-weight: bold;
                font-size: 16px;
                border-top: 1px solid #000;
                padding-top: 8px;
                margin-top: 8px;
            }

            .footer {
                text-align: center;
                margin-top: 20px;
                padding-top: 15px;
                border-top: 2px dashed #000;
                font-size: 11px;
            }

            .footer p {
                margin: 3px 0;
            }

            .thank-you {
                font-weight: bold;
                margin-bottom: 8px;
            }

            .controls {
                text-align: center;
                margin-top: 30px;
            }

            .btn {
                padding: 12px 24px;
                margin: 0 10px;
                font-size: 14px;
                font-weight: bold;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .btn-print {
                background-color: #28a745;
                color: white;
            }

            .btn-print:hover {
                background-color: #218838;
            }

            .btn-close {
                background-color: #6c757d;
                color: white;
            }

            .btn-close:hover {
                background-color: #5a6268;
            }

            /* Print Styles */
            @media print {
                body {
                    padding: 0;
                    background: white;
                    font-size: 10px;
                }

                .struk {
                    width: 100%;
                    max-width: 300px;
                    border: none;
                    box-shadow: none;
                    padding: 10px;
                    margin: 0;
                }

                .no-print {
                    display: none !important;
                }

                .header h2 {
                    font-size: 16px;
                }

                .total-row,
                .grand-total {
                    font-size: 11px;
                }

                .grand-total {
                    font-size: 13px;
                }
            }

            /* Responsive */
            @media (max-width: 480px) {
                .struk {
                    width: 95%;
                    margin: 0 auto;
                }
            }
        </style>
    </head>

    <body>
        <div class="struk">
            <!-- Header Toko -->
            <div class="header">
                <h2>{{ setting_toko('nama_toko') ?: 'TOKO POS' }}</h2>
                <p>{{ setting_toko('alamat') ?: 'Alamat tidak tersedia' }}</p>
                <p>Telp: {{ setting_toko('telepon') ?: '-' }}</p>
                @if (setting_toko('email'))
                    <p>Email: {{ setting_toko('email') }}</p>
                @endif
            </div>

            <!-- Info Transaksi -->
            <div class="info-section">
                <div class="row">
                    <span>No. Transaksi:</span>
                    <span>{{ $penjualan->kode_transaksi }}</span>
                </div>
                <div class="row">
                    <span>Tanggal:</span>
                    <span>
                        @if ($penjualan->tanggal_transaksi instanceof \Carbon\Carbon)
                            {{ $penjualan->tanggal_transaksi->format('d/m/Y H:i') }}
                        @else
                            {{ \Carbon\Carbon::parse($penjualan->tanggal_transaksi)->format('d/m/Y H:i') }}
                        @endif
                    </span>
                </div>
                <div class="row">
                    <span>Kasir:</span>
                    <span>Admin</span>
                </div>
                <div class="row">
                    <span>Customer:</span>
                    <span>Umum</span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Detail Items -->
            <div class="items-section">
                @foreach ($penjualan->detailPenjualan as $index => $detail)
                    <div class="item">
                        <div class="item-name">{{ $index + 1 }}. {{ $detail->masterbarang->nama }}</div>
                        <div class="item-detail">
                            <span class="item-qty">
                                {{ $detail->qty }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                            </span>
                            <span class="item-price">
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total Section -->
            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span>Pajak (0%):</span>
                    <span>Rp 0</span>
                </div>
                <div class="total-row">
                    <span>Diskon:</span>
                    <span>Rp 0</span>
                </div>
                <div class="total-row grand-total">
                    <span>TOTAL:</span>
                    <span>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="divider"></div>
                <div class="total-row">
                    <span>Tunai:</span>
                    <span>Rp {{ number_format($penjualan->jumlah_bayar, 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span>Kembalian:</span>
                    <span>Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Summary -->
            <div class="divider"></div>
            <div class="row">
                <span>Total Item:</span>
                <span>{{ $penjualan->detailPenjualan->sum('qty') }} pcs</span>
            </div>
            <div class="row">
                <span>Jenis Barang:</span>
                <span>{{ $penjualan->detailPenjualan->count() }} jenis</span>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p class="thank-you">Terima kasih atas kunjungan Anda!</p>
                <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
                <p>Simpan struk ini sebagai bukti pembelian</p>
                <p>---</p>
                <p>Dicetak: {{ now()->format('d/m/Y H:i:s') }}</p>
                <p>Sistem POS v1.0</p>
            </div>
        </div>

        <!-- Controls (tidak ikut print) -->
        <div class="controls no-print">
            <button onclick="window.print()" class="btn btn-print">
                🖨️ Print Struk
            </button>
            <button onclick="window.close()" class="btn btn-close">
                ❌ Tutup
            </button>
        </div>

        <script>
            // Auto print saat halaman dimuat
            window.addEventListener('load', function() {
                // Delay untuk memastikan halaman sudah siap
                setTimeout(function() {
                    // Check jika ada parameter auto-print di URL
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.get('auto') === 'true') {
                        window.print();
                    }
                }, 1000);
            });

            // Shortcut keyboard
            document.addEventListener('keydown', function(e) {
                // Ctrl+P untuk print
                if (e.ctrlKey && e.key === 'p') {
                    e.preventDefault();
                    window.print();
                }
                // Escape untuk close
                if (e.key === 'Escape') {
                    window.close();
                }
            });
        </script>
    </body>

</html>
