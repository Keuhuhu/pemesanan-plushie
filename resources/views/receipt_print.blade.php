<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian - {{ $transaction->invoice }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #fff;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            border: 1px dashed #000;
            padding: 20px;
        }
        .text-center {
            text-align: center;
        }
        .border-bottom {
            border-bottom: 1px dashed #000;
            margin-bottom: 15px;
            padding-bottom: 15px;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        @media print {
            body {
                background-color: #fff;
            }
            .no-print {
                display: none;
            }
            .receipt-container {
                border: none;
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="text-center border-bottom">
        <h2>THE TACTILE WHISPER</h2>
        <p>Toko Plushie Berkualitas</p>
    </div>

    <div class="border-bottom">
        <p><strong>Invoice:</strong> {{ $transaction->invoice }}</p>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y, H:i') }}</p>
        <p><strong>Customer:</strong> {{ auth()->user()->username }}</p>
        <p><strong>Status:</strong> LUNAS</p>
    </div>

    <div class="border-bottom">
        <h4>Item Pembelian:</h4>
        @forelse($transaction->details ?? [] as $detail)
            <div class="item-row">
                <span>{{ $detail->product->nama ?? 'Produk Dihapus' }} (x{{ $detail->kuantitas }})</span>
                <span>Rp {{ number_format(($detail->harga_satuan ?? 0) * $detail->kuantitas, 0, ',', '.') }}</span>
            </div>
        @empty
            <p>Detail item tidak ditemukan.</p>
        @endforelse
        
        <div class="item-row" style="margin-top: 10px;">
            <span>Biaya Layanan & Pajak</span>
            <span>Rp 10.000</span>
        </div>
    </div>

    <div class="total-row">
        <span>TOTAL BAYAR</span>
        <span>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
    </div>

    <div class="text-center" style="margin-top: 30px;">
        <p>Terima kasih atas pembelian Anda!</p>
        <p>Harap simpan struk ini sebagai bukti pembayaran yang sah.</p>
    </div>
</div>

<div class="text-center no-print" style="margin-top: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #6c3fc5; color: #fff; border: none; border-radius: 5px;">Cetak Sekarang</button>
</div>

<script>
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>
