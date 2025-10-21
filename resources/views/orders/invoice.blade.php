<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Invoice {{ $order->order_number }}</title>
        <style>
            /* Mendefinisikan Warna Brand Anda */
            :root {
                --tria-navy: #1e1b4b;
                --tria-navy-light: #312e81;
                --tria-orange: #ff6b35;
                --tria-orange-light: #ff8c69;
                --tria-orange-palest: #fff5f2; /* Latar belakang orange sangat muda */
                --tria-orange-dark: #c2410c; /* Teks orange tua */
                --tria-orange-darker: #9a3412; /* Teks orange sangat tua */
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'DejaVu Sans', Arial, sans-serif;
                font-size: 12px;
                line-height: 1.6;
                color: #333;
                padding: 20px;
            }

            /* ===== Penambahan Logo ===== */
            .logo {
                max-width: 56px; /* Sesuaikan ukuran logo Anda */
                height: auto;
                margin-bottom: 15px;
            }

            .invoice-header {
                display: table;
                width: 100%;
                margin-bottom: 30px;
                border-bottom: 3px solid var(--tria-navy); /* Warna Baru */
                padding-bottom: 15px;
            }

            .company-info {
                display: table-cell;
                width: 60%;
                vertical-align: top;
            }

            .company-name {
                font-size: 24px;
                font-weight: bold;
                color: var(--tria-navy); /* Warna Baru */
                margin-bottom: 5px;
            }

            .company-details {
                font-size: 11px;
                color: #666;
                line-height: 1.5;
            }

            .invoice-info {
                display: table-cell;
                width: 40%;
                text-align: right;
                vertical-align: top;
            }

            .invoice-title {
                font-size: 28px;
                font-weight: bold;
                color: var(--tria-navy); /* Warna Baru */
                margin-bottom: 10px;
            }

            .invoice-meta {
                font-size: 11px;
            }

            /* Perbaikan untuk alignment status */
            .invoice-meta > div {
                margin-bottom: 8px;
            }
            .invoice-meta > div:last-child {
                margin-bottom: 0;
            }

            .section {
                margin-bottom: 25px;
            }

            .section-title {
                font-size: 14px;
                font-weight: bold;
                color: var(--tria-navy); /* Warna Baru */
                margin-bottom: 10px;
                border-bottom: 2px solid #e5e7eb;
                padding-bottom: 5px;
            }

            .info-grid {
                display: table;
                width: 100%;
            }

            .info-column {
                display: table-cell;
                width: 50%;
                vertical-align: top;
                padding: 10px;
                background: #f9fafb;
                border-radius: 8px;
            }

            .info-column:first-child {
                margin-right: 10px;
            }

            .info-label {
                font-weight: bold;
                color: #666;
                font-size: 11px;
                margin-bottom: 3px;
            }

            .info-value {
                color: #333;
                font-size: 12px;
                margin-bottom: 10px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            thead {
                background: var(--tria-navy); /* Warna Baru */
                color: white;
            }

            thead th {
                padding: 12px 10px;
                text-align: left;
                font-weight: bold;
                font-size: 11px;
            }

            tbody tr {
                border-bottom: 1px solid #e5e7eb;
            }

            tbody tr:nth-child(even) {
                background: #f9fafb;
            }

            tbody td {
                padding: 10px;
                font-size: 11px;
            }

            .text-right {
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            .totals {
                margin-top: 20px;
                float: right;
                width: 300px;
            }

            .total-row {
                display: table;
                width: 100%;
                padding: 8px 0;
                border-bottom: 1px solid #e5e7eb;
            }

            .total-row.grand-total {
                background: var(--tria-navy); /* Warna Baru */
                color: white;
                font-weight: bold;
                font-size: 14px;
                padding: 12px 10px;
                border-radius: 5px;
                margin-top: 10px;
            }

            .total-label {
                display: table-cell;
                width: 60%;
                font-weight: 600;
            }

            .total-value {
                display: table-cell;
                width: 40%;
                text-align: right;
            }

            /* ===== Perubahan Warna Catatan (Orange) ===== */
            .notes {
                clear: both;
                margin-top: 40px;
                padding: 15px;
                background: var(--tria-orange-palest); /* Latar orange muda */
                border-left: 4px solid var(--tria-orange); /* Border orange */
                border-radius: 5px;
            }

            .notes-title {
                font-weight: bold;
                color: var(--tria-orange-darker); /* Teks orange tua */
                margin-bottom: 5px;
            }

            .notes-content {
                color: var(--tria-orange-dark); /* Teks orange tua */
                font-size: 11px;
                line-height: 1.6;
            }

            .footer {
                margin-top: 50px;
                padding-top: 20px;
                border-top: 2px solid #e5e7eb;
                text-align: center;
                font-size: 10px;
                color: #666;
            }

            .status-badge {
                display: inline-block;
                padding: 5px 12px;
                border-radius: 20px;
                font-size: 10px;
                font-weight: bold;
                text-transform: uppercase;

                /* Perbaikan untuk alignment status */
                vertical-align: middle; /* Membuat badge sejajar di tengah teks */
                margin-left: 5px; /* Memberi sedikit jarak dari label "Status:" */
            }

            .status-paid {
                background: #d1fae5;
                color: #065f46;
            }

            /* ===== Perubahan Warna Status (Orange) ===== */
            .status-pending {
                background: var(--tria-orange-palest); /* Latar orange muda */
                color: var(--tria-orange-darker); /* Teks orange tua */
            }

            .status-shipped {
                background: #dbeafe;
                color: #1e40af;
            }

            .product-name {
                font-weight: 600;
                color: #1f2937;
            }

            .product-specs {
                font-size: 10px;
                color: #6b7280;
                margin-top: 3px;
            }

            /* ===== CSS UNTUK WATERMARK LUNAS ===== */
            .watermark {
                position: fixed; /* 'fixed' agar posisinya tetap di tengah halaman PDF */
                top: 50%;
                left: 50%;

                /* Trik untuk center + rotasi */
                transform: translate(-50%, -50%) rotate(-45deg);

                font-size: 100px; /* Ukuran font (bisa disesuaikan) */
                font-weight: bold;

                /* Warna hijau pudar (diambil dari warna status-paid) */
                color: rgba(6, 95, 70, 0.15);

                /* PENTING: kirim ke belakang konten */
                z-index: -1;

                text-transform: uppercase;
                letter-spacing: 10px; /* Jarak antar huruf */

                /* Agar tidak bisa terseleksi */
                user-select: none;
            }
        </style>
    </head>
    <body>
        {{-- ===== WATERMARK LUNAS ===== --}}
        @if ($order->payment_status === 'verified')
            <div class="watermark">LUNAS</div>
        @endif

        {{-- =========================== --}}

        <div class="invoice-header">
            <div class="company-info">
                <img src="{{ public_path('images/logo-tria.svg') }}" alt="Tria Logo" class="logo" />

                <div class="company-name">{{ config('app.name', 'Triadigitalprinting.com') }}</div>
                <div class="company-details">
                    Digital Printing & Custom Products
                    <br />
                    Email: info @triadigitalprinting.com | Telp: (021) 1234-5678
                    <br />
                    Website: www.triadigitalprinting.com
                </div>
            </div>
            <div class="invoice-info">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-meta">
                    <div>
                        <strong>No. Invoice:</strong>
                        {{ $order->order_number }}
                    </div>
                    <div>
                        <strong>Tanggal:</strong>
                        {{ $order->created_at->format('d F Y') }}
                    </div>
                    <div>
                        <strong>Status:</strong>
                        <span
                            class="status-badge status-{{ $order->payment_status === 'verified' ? 'paid' : 'pending' }}"
                        >
                            {{ $order->payment_status_label }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Informasi Pelanggan & Pengiriman</div>
            <div class="info-grid">
                <div class="info-column">
                    <div class="info-label">PELANGGAN</div>
                    <div class="info-value">
                        <strong>{{ $order->customer->name }}</strong>
                        <br />
                        Email: {{ $order->customer->email }}
                        <br />
                        Telp: {{ $order->customer->phone ?? '-' }}
                    </div>
                </div>
                <div class="info-column">
                    <div class="info-label">ALAMAT PENGIRIMAN</div>
                    <div class="info-value">
                        {{ $order->full_shipping_address }}
                    </div>
                    @if ($order->shipping_courier)
                        <div class="info-label" style="margin-top: 10px">KURIR</div>
                        <div class="info-value">
                            {{ strtoupper($order->shipping_courier) }} - {{ $order->shipping_service }}
                            <br />
                            @if ($order->tracking_number)
                                Resi:
                                <strong>{{ $order->tracking_number }}</strong>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Detail Pesanan</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 45%">Produk</th>
                        <th style="width: 15%" class="text-center">Jumlah</th>
                        <th style="width: 17%" class="text-right">Harga Satuan</th>
                        <th style="width: 18%" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="product-name">{{ $item->product->name }}</div>
                                <div class="product-specs">
                                    @if ($item->custom_size_width && $item->custom_size_height)
                                        Ukuran: {{ $item->custom_size_width }} x {{ $item->custom_size_height }} cm
                                        @if ($item->selected_material)
                                                • Material: {{ ucfirst($item->selected_material) }}
                                        @endif
                                    @endif

                                    @if ($item->selected_finishing)
                                        <br />
                                        Finishing: {{ ucfirst($item->selected_finishing) }}
                                    @endif

                                    @if ($item->design_notes)
                                        <br />
                                        Catatan: {{ $item->design_notes }}
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-right">
                                <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="totals">
            <div class="total-row">
                <span class="total-label">Subtotal Produk:</span>
                <span class="total-value">Rp {{ number_format($order->subtotal_items, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="total-label">Ongkos Kirim:</span>
                <span class="total-value">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="total-label">Pajak (PPN 11%):</span>
                <span class="total-value">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
            </div>
            <div class="total-row grand-total">
                <span class="total-label">TOTAL PEMBAYARAN:</span>
                <span class="total-value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        @if ($order->notes)
            <div class="notes">
                <div class="notes-title">📝 Catatan Pesanan</div>
                <div class="notes-content">{{ $order->notes }}</div>
            </div>
        @endif

        <div class="footer">
            <p><strong>Terima kasih atas kepercayaan Anda!</strong></p>
            <p>Invoice ini dibuat secara otomatis dan sah tanpa tanda tangan.</p>
            <p style="margin-top: 10px">Untuk pertanyaan, hubungi customer service kami.</p>
            <p style="margin-top: 5px; font-size: 9px">Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        </div>
    </body>
</html>
