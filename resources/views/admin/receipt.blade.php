<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $order->invoice_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&display=swap');
        
        body {
            font-family: 'Courier Prime', 'Courier New', Courier, monospace;
            background-color: #f0f0f0;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            color: #111;
        }
        
        .receipt-wrapper {
            position: relative;
            background: #fff;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            border-radius: 4px;
        }
        
        /* Zigzag top and bottom effect */
        .receipt-wrapper::before, .receipt-wrapper::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            height: 10px;
            background-size: 20px 20px;
        }
        
        .receipt-wrapper::after {
            bottom: -10px;
            background-image: linear-gradient(135deg, #fff 50%, transparent 50%),
                              linear-gradient(-135deg, #fff 50%, transparent 50%);
            background-position: top left;
        }

        .receipt-container {
            padding: 30px 30px 40px 30px;
        }
        
        /* Fake paper clamp at top */
        .paper-clamp {
            position: absolute;
            top: -5px;
            left: 5%;
            right: 5%;
            height: 15px;
            background: #333;
            border-radius: 10px;
            z-index: 10;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        
        .header-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        
        .header-logo svg {
            width: 32px;
            height: 32px;
            color: #10b981; /* Green color */
        }
        
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            letter-spacing: -0.5px;
        }
        
        .header p {
            margin: 0;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }
        
        .divider-dashed {
            border-top: 1px dashed #333;
            margin: 15px 0;
        }
        
        .divider-solid {
            border-top: 2px solid #333;
            margin: 15px 0;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: start;
        }
        
        .grid-col {
            font-size: 11px;
            line-height: 1.6;
        }
        
        .grid-col:first-child {
            padding-right: 15px;
        }
        
        .grid-col:last-child {
            text-align: right;
            padding-left: 15px;
        }
        
        .grid-divider {
            width: 1px;
            height: 100%;
            border-left: 1px dashed #999;
            margin: 0 auto;
        }
        
        .label {
            color: #555;
            margin-bottom: 2px;
            font-size: 10px;
        }
        
        .value {
            font-weight: bold;
            font-size: 12px;
        }
        
        .address-section {
            font-size: 11px;
        }
        
        .address-text {
            font-weight: bold;
            font-size: 12px;
            margin-top: 4px;
            line-height: 1.4;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        
        th {
            text-align: left;
            font-weight: normal;
            color: #555;
            padding-bottom: 10px;
            font-size: 10px;
            text-transform: uppercase;
        }
        
        th.center { text-align: center; }
        th.right { text-align: right; }
        
        td {
            padding: 8px 0;
            vertical-align: top;
        }
        
        .item-row td {
            padding-bottom: 15px;
        }
        
        td.center { text-align: center; }
        td.right { text-align: right; }
        
        .item-name {
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
            font-size: 12px;
        }
        
        .item-note {
            color: #555;
            display: block;
            line-height: 1.3;
        }
        
        .totals-section {
            background-color: #f5f5f5;
            padding: 15px;
            margin-top: -15px; /* Pull up to touch the solid line */
        }
        
        .total-row {
            display: flex;
            justify-content: flex-end;
            font-size: 11px;
            margin-bottom: 8px;
            line-height: 1.4;
        }
        
        .total-label {
            text-align: right;
            padding-right: 15px;
            color: #333;
        }
        
        .total-value {
            width: 100px;
            text-align: left;
            font-weight: bold;
        }
        
        .total-row.grand-total {
            font-size: 13px;
            margin-top: 10px;
        }
        
        .total-row.grand-total .total-label {
            font-weight: bold;
        }
        
        .btn-status {
            background-color: #007aff;
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            margin: 20px 0;
            font-family: 'Segoe UI', sans-serif;
            cursor: pointer;
        }
        
        .barcode-section {
            text-align: center;
            margin-top: 15px;
        }
        
        /* SVG barcode styling */
        .barcode-svg {
            width: 100%;
            height: 40px;
            margin-bottom: 5px;
        }
        
        .barcode-text {
            font-size: 10px;
            font-weight: bold;
            color: #000;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .paper-clamp {
                display: none;
            }
            .receipt-wrapper {
                box-shadow: none;
                max-width: 100%;
            }
            .receipt-wrapper::after, .receipt-wrapper::before {
                display: none;
            }
            .btn-status {
                display: none;
            }
            .totals-section {
                background-color: transparent !important;
                -webkit-print-color-adjust: exact;
            }
            @page {
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt-wrapper">
        <div class="paper-clamp"></div>
        <div class="receipt-container">
            
            <div class="header">
                <div class="header-logo">
                    @if($settings && $settings->logo_path)
                        <img src="{{ asset($settings->logo_path) }}" alt="{{ $settings->store_name ?? 'Growseri' }}" style="height: 32px; object-fit: contain;">
                    @else
                        <!-- Fallback Logo Icon (Store) -->
                        <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6h16v2H4zm2 4h12v10H6zm3 2v6h2v-6zm4 0v6h2v-6z"/>
                        </svg>
                    @endif
                </div>
                <p>{{ $settings->address ?? 'Alamat Toko Belum Diatur' }}</p>
            </div>
            
            <div class="divider-dashed"></div>
            
            <div class="grid-2">
                <div class="grid-col">
                    <div class="label">Order ID</div>
                    <div class="value">{{ $order->invoice_number }}</div>
                </div>
                <div class="grid-divider"></div>
                <div class="grid-col">
                    <div class="label">Tgl. Order</div>
                    <div class="value">{{ $order->created_at->format('d/m/y H:i') }}</div>
                </div>
            </div>
            
            <div class="divider-dashed"></div>
            
            <div class="grid-2">
                <div class="grid-col">
                    <div class="label">Nama Lengkap</div>
                    <div class="value">{{ $order->recipient_name }}</div>
                </div>
                <div class="grid-divider"></div>
                <div class="grid-col">
                    <div class="label">No WhatsApp</div>
                    <div class="value">{{ $order->recipient_whatsapp }}</div>
                </div>
            </div>
            
            <div class="divider-dashed"></div>
            
            <div class="address-section">
                <div class="label">Alamat</div>
                <div class="address-text">{{ $order->shipping_address }}</div>
            </div>
            
            <div class="divider-solid"></div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 50%;">ITEM</th>
                        <th class="center" style="width: 20%;">QTY</th>
                        <th class="right" style="width: 30%;">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="item-row">
                        <td>
                            <span class="item-name">
                                {{ $item->product_name }}
                                @if($item->variant_name)
                                    ({{ $item->variant_name }})
                                @endif
                            </span>
                            @if($item->note)
                                <span class="item-note">Catatan: {{ $item->note }}</span>
                            @endif
                        </td>
                        <td class="center value">{{ $item->quantity }}</td>
                        <td class="right value">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="divider-solid"></div>
            
            <div class="totals-section">
                <div class="total-row">
                    <div class="total-label">Subtotal</div>
                    <div class="total-value">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</div>
                </div>
                <div class="total-row">
                    <div class="total-label">
                        Ongkir
                        <br>
                        <span style="font-size: 10px;">{{ str_replace('_', ' ', strtoupper($order->shipping_method)) }}</span>
                    </div>
                    <div class="total-value"><br>(Rp{{ number_format($order->shipping_fee, 0, ',', '.') }})</div>
                </div>
                @if($order->discount_amount > 0)
                <div class="total-row">
                    <div class="total-label">Diskon</div>
                    <div class="total-value">- Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</div>
                </div>
                @else
                <div class="total-row">
                    <div class="total-label">Diskon</div>
                    <div class="total-value">Rp0</div>
                </div>
                @endif
                <div class="total-row grand-total">
                    <div class="total-label">Total</div>
                    <div class="total-value">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</div>
                </div>
            </div>
            
            <button class="btn-status">Pesanan {{ ucfirst($order->order_status) }}</button>
            
        </div>
    </div>
</body>
</html>
