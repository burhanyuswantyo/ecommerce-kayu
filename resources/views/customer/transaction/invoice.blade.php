<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $transaction->invoice_number }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            width: '100%';
        }

        h1,
        h2,
        h3 {
            margin: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header,
        .footer,
        .customer-info,
        .items,
        .totals {
            margin-bottom: 20px;
        }

        .header,
        .customer-info {
            display: flex;
            justify-content: space-between;
        }

        .totals {
            display: flex;
            justify-content: flex-end;
        }

        .items table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items th,
        .items td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: right;
        }

        .items th:first-child,
        .items td:first-child {
            text-align: left;
        }

        .totals dt {
            font-weight: bold;
            text-align: right;
        }

        .totals dd {
            text-align: right;
            margin: 0 0 10px 0;
        }

        .totals dl {
            width: 40%;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .button-print {
            display: block;
            padding: 10px 20px;
            font-size: 14px;
            text-align: center
        }

        @media print {
            .button-print {
                display: none;
            }

            body {
                font-size: 12px;
            }

            @page {
                margin: 8mm;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <div>
                <h2>{{ config('store.store_name') }}</h2>
                <p>{{ config('store.store_address') }}</p>
            </div>
            <div style="text-align: right;">
                <h2>Invoice</h2>
                <p><strong>#{{ $transaction->invoice_number }}</strong></p>
            </div>
        </div>

        <div class="customer-info">
            <div style="display: flex;flex-direction: column;gap: 4px;max-width: 40%;">
                <h3>Pelanggan:</h3>
                <strong>{{ $transaction->name }}</strong>
                <span>{{ $transaction->address }}</span>
            </div>
            <div style="text-align: right;display: flex;flex-direction: column;gap: 4px">
                <div style="display: flex;flex-direction: column;gap: 4px">
                    <strong>Tanggal Pembelian:</strong>
                    <span>{{ $transaction->created_at->format('d M Y H:i') }}</span>
                </div>
                <div style="display: flex;flex-direction: column;gap: 4px">
                    <strong>Status:</strong>
                    <span> {{ __(str($transaction->payment_status)->headline()->toString()) }}</span>
                </div>
            </div>
        </div>

        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->transactionItems as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp{{ thousand($item->price) }}</td>
                            <td>Rp{{ thousand($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="totals">
            <dl>
                <dt>Subtotal:</dt>
                <dd>Rp{{ thousand($transaction->total_price) }}</dd>

                <dt>Biaya Kirim:</dt>
                <dd>Rp{{ thousand($transaction->shipping_cost) }}</dd>

                <dt>Total:</dt>
                <dd><strong>Rp{{ thousand($transaction->grand_total) }}</strong></dd>
            </dl>
        </div>

        <a class="button-print" href="#" onclick="window.print()">[Cetak]</a>
    </div>

</body>

</html>
