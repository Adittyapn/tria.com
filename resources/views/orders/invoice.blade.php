<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Invoice {{ $order->order_number }}</title>
        <style>
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

            .invoice-header {
                display: table;
                width: 100%;
                margin-bottom: 30px;
                border-bottom: 3px solid #2563eb;
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
                color: #2563eb;
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
                color: #2563eb;
                margin-bottom: 10px;
            }

            .invoice-meta {
                font-size: 11px;
                line-height: 1.8;
            }

            .section {
                margin-bottom: 25px;
            }

            .section-title {
                font-size: 14px;
                font-weight: bold;
                color: #2563eb;
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
                background: #2563eb;
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
                background: #2563eb;
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

            .notes {
                clear: both;
                margin-top: 40px;
                padding: 15px;
                background: #fffbeb;
                border-left: 4px solid #f59e0b;
                border-radius: 5px;
            }

            .notes-title {
                font-weight: bold;
                color: #92400e;
                margin-bottom: 5px;
            }

            .notes-content {
                color: #78350f;
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
            }

            .status-paid {
                background: #d1fae5;
                color: #065f46;
            }

            .status-pending {
                background: #fef3c7;
                color: #92400e;
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
        </style>
    </head>
    <body>
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-name">{{ config('app.name', 'Tria.com') }}</div>
                <div class="company-details">
                    Digital Printing & Custom Products
                    <br />
                    Email: info @tria.com | Telp: (021) 1234-5678
                    <br />
                    Website: www.tria.com
                </div>
            </div>
            <div class="invoice-info">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-meta">
                    <strong>No. Invoice:</strong>
                    {{ $order->order_number }}
                    <br />
                    <strong>Tanggal:</strong>
                    {{ $order->created_at->format('d F Y') }}
                    <br />
                    <strong>Status:</strong>
                    <span class="status-badge status-{{ $order->payment_status === 'verified' ? 'paid' : 'pending' }}">
                        {{ $order->payment_status_label }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Customer & Shipping Info -->
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

        <!-- Order Items -->
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

        <!-- Totals -->
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

        <!-- Notes -->
        @if ($order->notes)
            <div class="notes">
                <div class="notes-title">📝 Catatan Pesanan</div>
                <div class="notes-content">{{ $order->notes }}</div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Terima kasih atas kepercayaan Anda!</strong></p>
            <p>Invoice ini dibuat secara otomatis dan sah tanpa tanda tangan.</p>
            <p style="margin-top: 10px">Untuk pertanyaan, hubungi customer service kami.</p>
            <p style="margin-top: 5px; font-size: 9px">Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        </div>
    </body>
</html>
