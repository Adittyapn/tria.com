<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Pesanan Baru</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                line-height: 1.6;
                color: #333;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                margin: 0;
                padding: 20px;
                min-height: 100vh;
            }
            .email-container {
                max-width: 650px;
                margin: 0 auto;
                background-color: #ffffff;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                border: 1px solid #e1e5e9;
            }
            .header {
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
                color: white;
                padding: 40px 30px;
                text-align: center;
                position: relative;
            }
            .header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 12px 12px 0 0;
            }
            .header h1 {
                margin: 0;
                font-size: 28px;
                font-weight: 700;
                position: relative;
                z-index: 1;
            }
            .header p {
                margin: 8px 0 0;
                font-size: 16px;
                opacity: 0.9;
                position: relative;
                z-index: 1;
            }
            .content {
                padding: 40px 30px;
            }
            .alert-box {
                background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
                border-left: 5px solid #ff9800;
                padding: 20px;
                margin-bottom: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(255, 152, 0, 0.1);
            }
            .alert-box h2 {
                margin: 0 0 8px;
                color: #e65100;
                font-size: 20px;
                font-weight: 600;
            }
            .section {
                margin-bottom: 30px;
                background: #f8f9fa;
                border-radius: 10px;
                padding: 25px;
                border: 1px solid #e9ecef;
            }
            .section-title {
                font-size: 18px;
                font-weight: 700;
                color: #2c3e50;
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 3px solid #3498db;
                display: flex;
                align-items: center;
            }
            .section-title::before {
                content: '';
                width: 6px;
                height: 24px;
                background: #3498db;
                margin-right: 10px;
                border-radius: 3px;
            }
            .info-row {
                display: flex;
                padding: 12px 0;
                border-bottom: 1px solid #e9ecef;
                align-items: center;
            }
            .info-row:last-child {
                border-bottom: none;
            }
            .info-label {
                font-weight: 600;
                width: 150px;
                color: #495057;
                font-size: 14px;
            }
            .info-value {
                flex: 1;
                color: #212529;
                font-weight: 500;
            }
            .product-item {
                background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                padding: 20px;
                margin-bottom: 15px;
                border-radius: 10px;
                border-left: 4px solid #3498db;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                transition: transform 0.2s ease;
            }
            .product-item:hover {
                transform: translateY(-2px);
            }
            .product-name {
                font-weight: 700;
                color: #2c3e50;
                margin-bottom: 8px;
                font-size: 16px;
            }
            .product-details {
                font-size: 14px;
                color: #6c757d;
                line-height: 1.5;
            }
            .product-details div {
                margin-bottom: 4px;
            }
            .total-box {
                background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
                color: white;
                padding: 25px;
                border-radius: 10px;
                margin-top: 20px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }
            .total-row {
                display: flex;
                justify-content: space-between;
                padding: 8px 0;
                align-items: center;
            }
            .total-main {
                font-size: 22px;
                font-weight: 700;
                border-top: 3px solid #3498db;
                padding-top: 15px;
                margin-top: 15px;
                color: #ecf0f1;
            }
            .button-container {
                text-align: center;
                margin: 40px 0;
            }
            .whatsapp-button {
                display: inline-block;
                background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
                color: white !important;
                text-decoration: none;
                padding: 18px 35px;
                border-radius: 50px;
                font-weight: 700;
                font-size: 16px;
                box-shadow: 0 6px 20px rgba(37, 211, 102, 0.3);
                transition: all 0.3s ease;
                margin-bottom: 15px;
                border: 2px solid #25d366;
            }
            .whatsapp-button:hover {
                background: linear-gradient(135deg, #128c7e 0%, #075e54 100%);
                box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
                transform: translateY(-2px);
            }
            .dashboard-button {
                display: inline-block;
                background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                color: white !important;
                text-decoration: none;
                padding: 14px 28px;
                border-radius: 50px;
                font-weight: 600;
                font-size: 14px;
                box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
                transition: all 0.3s ease;
                border: 2px solid #3498db;
            }
            .dashboard-button:hover {
                background: linear-gradient(135deg, #2980b9 0%, #21618c 100%);
                box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
                transform: translateY(-2px);
            }
            .footer {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                padding: 25px;
                text-align: center;
                font-size: 13px;
                color: #6c757d;
                border-top: 1px solid #dee2e6;
            }
            .badge {
                display: inline-block;
                padding: 6px 14px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .badge-warning {
                background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
                color: #856404;
                border: 1px solid #ffc107;
            }
            .badge-success {
                background: linear-gradient(135deg, #d4edda 0%, #a8d5ba 100%);
                color: #155724;
                border: 1px solid #28a745;
            }
            .notes-section {
                background: linear-gradient(135deg, #fff3e0 0%, #fce4ec 100%);
                padding: 20px;
                border-radius: 10px;
                border-left: 4px solid #ff9800;
                font-style: italic;
                color: #4e342e;
            }
            @media (max-width: 600px) {
                .email-container {
                    margin: 10px;
                }
                .header {
                    padding: 30px 20px;
                }
                .content {
                    padding: 30px 20px;
                }
                .info-row {
                    flex-direction: column;
                    align-items: flex-start;
                }
                .info-label {
                    width: auto;
                    margin-bottom: 4px;
                }
                .total-row {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 5px;
                }
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <!-- Header -->
            <div class="header">
                <h1>🛒 PESANAN BARU!</h1>
                <p>Order #{{ $order->order_number }}</p>
            </div>

            <!-- Content -->
            <div class="content">
                <!-- Alert Box -->
                <div class="alert-box">
                    <h2>⚡ Segera Hubungi Customer!</h2>
                    <p style="margin: 8px 0 0">
                        Pesanan baru telah masuk. Klik tombol WhatsApp di bawah untuk langsung menghubungi customer.
                    </p>
                </div>

                <!-- Customer Info -->
                <div class="section">
                    <div class="section-title">👤 Informasi Customer</div>
                    <div class="info-row">
                        <div class="info-label">Nama</div>
                        <div class="info-value">{{ $order->customer->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $order->customer->email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Telepon</div>
                        <div class="info-value">{{ $order->customer->phone ?? '-' }}</div>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="section">
                    <div class="section-title">📋 Detail Pesanan</div>
                    <div class="info-row">
                        <div class="info-label">Order ID</div>
                        <div class="info-value"><strong>{{ $order->order_number }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tanggal</div>
                        <div class="info-value">{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status Order</div>
                        <div class="info-value">
                            <span class="badge badge-warning">{{ $order->status_label }}</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status Bayar</div>
                        <div class="info-value">
                            <span class="badge badge-warning">{{ $order->payment_status_label }}</span>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="section">
                    <div class="section-title">📦 Produk yang Dipesan</div>
                    @foreach ($order->items as $item)
                        <div class="product-item">
                            <div class="product-name">{{ $item->product->name ?? 'Produk' }}</div>
                            <div class="product-details">
                                <div>
                                    Jumlah:
                                    <strong>{{ $item->quantity }}x</strong>
                                </div>
                                @if ($item->custom_size_width && $item->custom_size_height)
                                    <div>
                                        Ukuran: {{ $item->custom_size_width / 100 }}m ×
                                        {{ $item->custom_size_height / 100 }}m
                                    </div>
                                @endif

                                @if ($item->selected_material)
                                    <div>Material: {{ $item->selected_material }}</div>
                                @endif

                                @if ($item->design_notes)
                                    <div>Catatan: {{ $item->design_notes }}</div>
                                @endif

                                <div style="margin-top: 12px; font-size: 18px; color: #2c3e50; font-weight: 700">
                                    <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Shipping Info -->
                <div class="section">
                    <div class="section-title">📍 Alamat Pengiriman</div>
                    <div class="info-row">
                        <div class="info-label">Alamat</div>
                        <div class="info-value">{{ $order->full_shipping_address }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Kurir</div>
                        <div class="info-value">{{ $order->shipping_service_display }}</div>
                    </div>
                </div>

                <!-- Total -->
                <div class="total-box">
                    <div class="total-row">
                        <span>Subtotal Produk</span>
                        <span>Rp {{ number_format($order->subtotal_items, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span>Ongkir</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span>Pajak (11%)</span>
                        <span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row total-main">
                        <span>TOTAL</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if ($order->notes)
                    <div class="section">
                        <div class="section-title">📝 Catatan Customer</div>
                        <div class="notes-section">
                            {{ $order->notes }}
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="button-container">
                    <a href="{{ $whatsappUrl }}" class="whatsapp-button">📱 Hubungi via WhatsApp</a>
                    <br />
                    <a href="{{ route('filament.admin.resources.orders.view', $order->id) }}" class="dashboard-button">
                        🖥️ Buka di Dashboard
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p><strong>Tria Digital Printing</strong></p>
                <p>Email otomatis dari sistem. Jangan balas email ini.</p>
                <p style="margin-top: 12px">© {{ date('Y') }} Tria.com. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
