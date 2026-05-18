<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f18d96; color: white; }
    </style>
</head>
<body>
    <h2 style="text-align: center; color: #8c2a38;">Laporan Transaksi Tactile Whisper</h2>
    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Customer</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->invoice }}</td>
                <td>{{ $order->user?->username ?? 'Deleted User' }}</td>
                <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                <td>{{ strtoupper($order->status) }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align: right;">Total Keseluruhan:</th>
                <th>Rp {{ number_format($orders->sum('total_harga'), 0, ',', '.') }}</th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>