<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detail Transaksi - {{ $penjualan->kode_transaksi }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
            .card-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }

            .detail-card {
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border: none;
                border-radius: 10px;
            }

            .item-image {
                width: 60px;
                height: 60px;
                object-fit: cover;
                border-radius: 8px;
            }

            .status-badge {
                font-size: 0.8rem;
            }

            .total-section {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 20px;
            }

            @media print {
                .no-print {
                    display: none !important;
                }
            }
        </style>
    </head>

    <body class="bg-light">
        <div class="container my-4">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">Detail Transaksi</h2>
                            <p class="text-muted mb-0">{{ $penjualan->kode_transaksi }}</p>
                        </div>
                        <div class="no-print">
                            <a href="{{ route('struk.print', $penjualan->id) }}" target="_blank"
                                class="btn btn-success me-2">
                                <i class="fas fa-print"></i> Print Struk
                            </a>
                            <button onclick="window.close()" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Transaksi -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card detail-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Transaksi</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%"><strong>Kode Transaksi:</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-primary status-badge">{{ $penjualan->kode_transaksi }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal:</strong></td>
                                            <td>{{ $penjualan->tanggal_transaksi->format('d F Y, H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Item:</strong></td>
                                            <td>{{ $penjualan->detailPenjualan->sum('qty') }} item</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%"><strong>Jenis Barang:</strong></td>
                                            <td>{{ $penjualan->detailPenjualan->count() }} jenis</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kasir:</strong></td>
                                            <td>Admin</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td><span class="badge bg-success status-badge">Selesai</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card detail-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-calculator"></i> Ringkasan Pembayaran</h5>
                        </div>
                        <div class="card-body total-section">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span class="fw-bold">Rp
                                    {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Jumlah Bayar:</span>
                                <span>Rp {{ number_format($penjualan->jumlah_bayar, 0, ',', '.') }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Kembalian:</span>
                                <span class="fw-bold text-success">Rp
                                    {{ number_format($penjualan->kembalian, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Items -->
            <div class="row">
                <div class="col-12">
                    <div class="card detail-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Detail Barang</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="10%">Foto</th>
                                            <th width="30%">Nama Barang</th>
                                            <th width="15%">Kategori</th>
                                            <th width="10%" class="text-center">Qty</th>
                                            <th width="15%" class="text-end">Harga Satuan</th>
                                            <th width="20%" class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($penjualan->detailPenjualan as $detail)
                                            <tr>
                                                <td>
                                                    @if ($detail->masterbarang->foto)
                                                        <img src="{{ asset('storage/' . $detail->masterbarang->foto) }}"
                                                            class="item-image" alt="{{ $detail->masterbarang->nama }}">
                                                    @else
                                                        <div
                                                            class="item-image bg-light d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="fw-bold">{{ $detail->masterbarang->nama }}</div>
                                                    <small
                                                        class="text-muted">{{ $detail->masterbarang->kategori->nama_kategori }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info status-badge">
                                                        {{ $detail->masterbarang->kategori->nama_kategori }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-secondary status-badge">{{ $detail->qty }}</span>
                                                </td>
                                                <td class="text-end">
                                                    Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end fw-bold">
                                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-dark">
                                        <tr>
                                            <th colspan="5" class="text-end">TOTAL:</th>
                                            <th class="text-end">Rp
                                                {{ number_format($penjualan->total_harga, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card detail-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-store"></i> Informasi Toko</h6>
                                    <p class="mb-1">STTR POS</p>
                                    <p class="mb-1">Jl. Raya Cepu No. 123, Blora</p>
                                    <p class="mb-0">Telp: 0812-3456-7890</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <h6><i class="fas fa-clock"></i> Waktu Transaksi</h6>
                                    <p class="mb-1">Dibuat: {{ $penjualan->created_at->format('d F Y') }}</p>
                                    <p class="mb-0">Dilihat: {{ now()->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Auto print jika ada parameter print
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === 'true') {
                window.print();
            }
        </script>
    </body>

</html>
