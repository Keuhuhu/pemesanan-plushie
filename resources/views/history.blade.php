<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History - Tactile Whisper</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user.css'])
</head>
<body class="page-history">

    <nav class="navbar">
        <a href="{{ url('/katalog') }}"><i class="fa-solid fa-store"></i> Catalog</a>
        <a href="{{ url('/cart') }}"><i class="fa-solid fa-cart-shopping"></i> Cart</a>
        <a href="{{ url('/history') }}" class="active"><i class="fa-solid fa-clock-rotate-left"></i> History</a>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" style="background:none; border:none; color:#999; font-size:16px; font-weight:500; cursor:pointer; font-family:inherit; margin-left: 10px; transition: color 0.3s;">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </nav>

    <div class="container">
        <h1 class="page-title anim-item">Purchase History</h1>

        @forelse($transactions as $trx)
        <div class="history-card anim-item list-item {{ strtolower($trx->status) == 'pending' ? 'border-pending' : 'border-sukses' }}">
            <div class="history-info">
                <h3>Invoice: {{ $trx->invoice }}</h3>
                <p><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y, H:i') }}</p>
                <div class="history-price">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</div>
            </div>
            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
                <span class="status-badge {{ strtolower($trx->status) == 'pending' ? 'status-pending' : 'status-sukses' }}">
                    {{ strtoupper($trx->status) }}
                </span>
                
                @if(strtolower($trx->status) == 'sukses')
                <a href="{{ route('history.print', $trx->id) }}" target="_blank" style="background-color: var(--primary-dark); color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; transition: background 0.3s;">
                    <i class="fa-solid fa-print"></i> Cetak Struk
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="anim-item list-item" style="text-align: center; padding: 80px 20px; background: white; border-radius: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <i class="fa-solid fa-receipt" style="font-size: 60px; color: #eaeaea; margin-bottom: 25px;"></i>
            <h3 style="color: var(--text-dark); margin-bottom: 10px; font-size: 20px;">Belum ada riwayat pesanan</h3>
            <p style="color: var(--text-gray); font-size: 15px;">Kamu belum pernah melakukan transaksi. Yuk, mulai koleksi plushie pertamamu!</p>
        </div>
        @endforelse
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const title = document.querySelector('.page-title');
            setTimeout(() => { title.classList.add('show'); }, 100);

            const listItems = document.querySelectorAll('.list-item');
            listItems.forEach((item, index) => {
                setTimeout(() => { item.classList.add('show'); }, 150 + (index * 100));
            });
        });
    </script>
</body>
</html>