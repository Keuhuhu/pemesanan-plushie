<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History - Tactile Whisper</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-pink: #f18d96;
            --primary-dark: #8c2a38;
            --text-dark: #333;
            --text-gray: #666;
            --bg-page: #f5f5f5;
            --font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-family); background-color: var(--bg-page); color: var(--text-dark); overflow-x: hidden; padding-bottom: 60px; }

        /* --- NAVBAR --- */
        .navbar {
            background-color: white; padding: 20px 40px; padding-right: 100px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); display: flex; justify-content: flex-end; gap: 40px; position: sticky; top: 0; z-index: 10;
        }
        .navbar a { font-size: 16px; color: #999; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .navbar a:hover, .navbar button:hover { color: var(--primary-pink); }
        .navbar a.active { color: var(--primary-dark); font-weight: 700; border-bottom: 2px solid var(--primary-dark); padding-bottom: 5px; }

        /* --- CONTAINER UTAMA --- */
        .container { max-width: 900px; margin: 50px auto 0; padding: 0 30px; }
        .page-title { color: var(--primary-dark); font-size: 28px; font-weight: 700; margin-bottom: 30px; letter-spacing: -0.5px; }

        /* --- HISTORY CARDS --- */
        .history-card { 
            background: white; border-radius: 20px; padding: 30px; margin-bottom: 20px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center; 
            transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 5px solid transparent;
        }
        .history-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }

        .history-info h3 { font-size: 18px; margin-bottom: 8px; color: var(--text-dark); }
        .history-info p { font-size: 14px; color: var(--text-gray); margin-bottom: 5px; }
        .history-price { font-size: 18px; font-weight: 700; color: var(--primary-dark); margin-top: 15px; }

        .status-badge { 
            padding: 10px 20px; border-radius: 30px; font-size: 13px; font-weight: 700; 
            text-transform: uppercase; letter-spacing: 0.5px; display: inline-block;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-sukses { background-color: #d4edda; color: #155724; }

        /* Status card dynamic border */
        .history-card.border-pending { border-left-color: #ffc107; }
        .history-card.border-sukses { border-left-color: #28a745; }

        /* --- ANIMASI JS --- */
        .anim-item { opacity: 0; transform: translateY(20px); transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1); }
        .anim-item.show { opacity: 1; transform: translateY(0); }

        @media (max-width: 768px) {
            .history-card { flex-direction: column; align-items: flex-start; gap: 20px; }
            .navbar { padding: 15px 20px; justify-content: center; }
        }
    </style>
</head>
<body>

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
            <div>
                <span class="status-badge {{ strtolower($trx->status) == 'pending' ? 'status-pending' : 'status-sukses' }}">
                    {{ $trx->status }}
                </span>
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