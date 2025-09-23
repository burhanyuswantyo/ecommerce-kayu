<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table th,
        .table td {
            border: 1px solid #444;
            padding: 6px 8px;
            text-align: left;
        }

        .table th {
            background-color: #eee;
        }

        .right {
            text-align: right;
        }

        .footer {
            margin-bottom: 4px;
            text-align: right;
            font-size: 11px;
        }

        .details {
            margin-bottom: 16px
        }

        .details th {
            text-align: left;
        }
    </style>

</head>

<body>

    <h2 style="line-height: 8px; text-align: center">Laporan Penjualan</h2>
    <h4 style="line-height: 8px; text-align: center">{{ config('store.store_name') }}</h4>
    <table class="details">
        <tr>
            <th>Tanggal</th>
            <td style="padding-left: 4px; padding-right: 4px">:</td>
            <td>{{ $transactionItems->min('created_at')->format('d/m/Y') }} -
                {{ $transactionItems->max('created_at')->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Dicetak pada</th>
            <td style="padding-left: 4px; padding-right: 4px">:</td>
            <td>{{ now()->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Dicetak oleh</th>
            <td style="padding-left: 4px; padding-right: 4px">:</td>
            <td>{{ auth()->user()->name }}</td>
        </tr>
    </table>
    <table class="table">
        <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th style="text-align: center">Nama Produk</th>
                <th style="text-align: center">Quantity</th>
                <th style="text-align: center">Harga</th>
                <th style="text-align: center">Total</th>
                <th style="text-align: center">Tanggal</th>
                <th style="text-align: center">Ref Invoice</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($transactionItems as $index => $item)
                @php
                    $total = $item->quantity * $item->price;
                    $grandTotal += $total;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name ?? '-' }}</td>
                    <td style="text-align: right">{{ $item->quantity }}</td>
                    <td style="text-align: right">{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($total, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->transaction->invoice_number ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align: right">Grand Total</th>
                <th style="text-align: right">Rp{{ number_format($grandTotal, 0, ',', '.') }}</th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
