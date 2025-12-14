<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->code }}</title>
    <style>
        @page {
            margin: 0px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
        }

        /* --- LAYOUT UTAMA --- */
        .container {
            padding: 40px 40px;
        }

        /* --- HEADER SECTION --- */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #6B4226;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .logo-img {
            width: 180px;
            height: auto;
            display: block;
        }

        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #6B4226;
            text-align: right;
            letter-spacing: 1px;
            text-transform: uppercase;
            line-height: 1;
        }

        .invoice-subtitle {
            text-align: right;
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            font-weight: 500;
        }

        /* --- INFO CLIENT & DETAILS --- */
        .info-table {
            width: 100%;
            margin-bottom: 40px;
        }

        .info-table td {
            vertical-align: top;
        }

        .label-title {
            font-size: 10px;
            font-weight: bold;
            color: #6B4226;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .client-name {
            font-size: 15px;
            font-weight: bold;
            color: #000;
            margin-bottom: 4px;
        }

        .text-muted {
            color: #555;
            line-height: 1.5;
            font-size: 11px;
        }

        /* Detail Invoice Kanan */
        .invoice-meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-meta-table td {
            padding-bottom: 5px;
            text-align: right;
        }

        .meta-label {
            font-weight: bold;
            color: #555;
            padding-right: 15px;
            font-size: 11px;
        }

        .meta-value {
            color: #333;
            font-weight: bold;
            font-size: 12px;
        }

        /* --- TABEL PRODUK --- */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background-color: #6B4226;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px 12px;
            text-align: left;
            font-size: 10px;
            letter-spacing: 0.5px;
        }

        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* --- TOTAL BLOCK --- */
        .totals-container {
            width: 40%;
            float: right;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 6px 0;
            text-align: right;
        }

        .total-label {
            font-weight: bold;
            color: #666;
            padding-right: 20px;
        }

        .grand-total-block {
            background-color: #6B4226;
            color: #fff;
            padding: 5px 10px;
            font-size: 15px;
            font-weight: bold;
            margin-top: 10px;
            text-align: right;
        }

        /* --- FOOTER --- */
        .notes {
            margin-top: 50px;
            border-top: 1px dashed #ddd;
            padding-top: 15px;
        }

        .notes-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 5px;
            color: #6B4226;
        }

        .footer-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 10px;
            background-color: #6B4226;
        }

        /* Badges */
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }

        .badge-paid {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #065f46;
        }

        .badge-unpaid {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #b45309;
        }
    </style>
</head>

<body>

    <div class="footer-bar"></div>

    <div class="container">

        <table class="header-table">
            <tr>
                <td width="60%" style="vertical-align: middle;">
                    <img src="{{ public_path('assets/image/logo-big.png') }}" class="logo-img" alt="Rizqi Wood Gallery">
                </td>
                <td width="40%" style="vertical-align: middle;">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-subtitle">Original Receipt</div>
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td width="55%">
                    <div class="label-title">Invoice To:</div>
                    <div class="client-name">{{ $order->shipping_name }}</div>
                    <div class="text-muted">
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_district }}, {{ $order->shipping_city }}<br>
                        {{ $order->shipping_province }} {{ $order->shipping_postal_code }}<br>
                        {{ $order->shipping_country }}
                    </div>
                    <div class="text-muted" style="margin-top: 8px;">
                        {{ $order->shipping_phone }} | {{ $order->shipping_email }}
                    </div>
                </td>

                <td width="45%">
                    <table class="invoice-meta-table">
                        <tr>
                            <td class="meta-label">Invoice No:</td>
                            <td class="meta-value">#{{ $order->code }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Date:</td>
                            <td class="meta-value">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Status:</td>
                            <td class="meta-value">
                                @if($order->payment_status == 'paid')
                                    <span class="badge badge-paid">PAID</span>
                                @else
                                    <span class="badge badge-unpaid">{{ strtoupper($order->payment_status) }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="50%">Item Description</th>
                    <th width="15%" class="text-center">Qty</th>
                    <th width="15%" class="text-right">Price</th>
                    <th width="15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->product->name }}</strong>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->price_per_unit, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-container">
            <table class="totals-table">
                <tr>
                    <td class="total-label">Subtotal</td>
                    <td>Rp {{ number_format($order->grand_total - ($order->shipping_price ?? 0), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="total-label">Shipping Cost</td>
                    <td>
                        @if(($order->shipping_price ?? 0) == 0)
                            <span style=" font-weight: bold;">Free</span>
                        @else
                            Rp {{ number_format($order->shipping_price, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>
            </table>

            <div class="grand-total-block">
                Total: Rp {{ number_format($order->grand_total, 0, ',', '.') }}
            </div>
        </div>

        <div style="clear: both;"></div>

        <div class="notes">
            <div class="notes-title">Thank you for your business!</div>
            <p class="text-muted">
                If you have any questions concerning this invoice, please contact <strong>Rizqi Wood Gallery</strong> at
                <strong>+62 819-4559-1108</strong>.
            </p>
        </div>

    </div>

</body>

</html>