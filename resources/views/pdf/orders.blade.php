<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Penjualan - Rizqi Wood Gallery</title>
    <style>
        @page { margin: 0px; }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 11px; 
            line-height: 1.4;
        }

        /* --- LAYOUT UTAMA --- */
        .container { padding: 40px 40px; }

        /* --- HEADER SECTION --- */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #6B4226;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .logo-img {
            width: 150px; 
            height: auto;
            display: block;
        }

        .report-title {
            font-size: 24px;
            font-weight: bold;
            color: #6B4226;
            text-align: right;
            letter-spacing: 1px;
            text-transform: uppercase;
            line-height: 1;
        }

        .report-subtitle {
            text-align: right;
            font-size: 11px;
            color: #666;
            margin-top: 5px;
            font-weight: 500;
        }

        /* --- TABEL DATA --- */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            margin-top: 20px;
        }

        .items-table th {
            background-color: #6B4226;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            letter-spacing: 0.5px;
        }

        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            color: #333;
            vertical-align: middle;
        }

        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* --- TOTAL BLOCK --- */
        .totals-container {
            width: 40%;
            float: right;
        }

        .grand-total-block {
            background-color: #6B4226;
            color: #fff;
            padding: 8px 10px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            text-align: right;
            border-radius: 4px;
        }

        /* --- FOOTER DECORATION --- */
        .footer-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 10px;
            background-color: #6B4226;
        }

        /* --- BADGES STATUS --- */
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }
        .badge-success { background: #d1fae5; color: #065f46; border: 1px solid #065f46; }
        .badge-warning { background: #fffbeb; color: #b45309; border: 1px solid #b45309; }
        .badge-danger  { background: #fef2f2; color: #991b1b; border: 1px solid #991b1b; }
        .badge-info    { background: #eff6ff; color: #1e40af; border: 1px solid #1e40af; }

        .meta-info {
            width: 100%;
            margin-bottom: 10px;
            font-size: 10px;
            color: #555;
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
                    <div class="report-title">SALES RECAP</div>
                    <div class="report-subtitle">Generated on: {{ date('d M Y, H:i') }}</div>
                    @if(!empty($startDate) && !empty($endDate))
                    <div class="report-subtitle">Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</div>
                    @endif
                    <div class="report-subtitle">Total Orders: {{ count($orders) }}</div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Date</th>
                    <th width="20%">Order ID</th>
                    <th width="25%">Customer</th>
                    <th width="15%" class="text-center">Status</th>
                    <th width="20%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($orders as $index => $order)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td class="font-bold">{{ $order->code }}</td>
                        <td>
                            {{ $order->shipping_name }}<br>
                            <span style="font-size: 9px; color: #777;">{{ $order->shipping_city }}</span>
                        </td>
                        <td class="text-center">
                            @if($order->payment_status == 'paid')
                                <span class="badge badge-success">PAID</span>
                            @elseif($order->payment_status == 'unpaid')
                                <span class="badge badge-warning">UNPAID</span>
                            @else
                                <span class="badge badge-danger">{{ strtoupper($order->payment_status) }}</span>
                            @endif
                            
                            <div style="margin-top: 3px; font-size: 8px; color: #666;">
                                {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                            </div>
                        </td>
                        <td class="text-right">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    </tr>
                    @php $grandTotal += $order->grand_total; @endphp
                @endforeach
            </tbody>
        </table>

        <div class="totals-container">
            <div class="grand-total-block">
                Total Revenue: Rp {{ number_format($grandTotal, 0, ',', '.') }}
            </div>
        </div>

        <div style="clear: both;"></div>

        <div style="margin-top: 40px; border-top: 1px dashed #ddd; padding-top: 10px;">
            <p style="font-size: 10px; color: #777; text-align: center;">
                This document is a computerized sales summary generated by <strong>Rizqi Wood Gallery System</strong>.<br>
                Internal Use Only.
            </p>
        </div>

    </div>

</body>
</html>