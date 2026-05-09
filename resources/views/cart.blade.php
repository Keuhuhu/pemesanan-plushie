<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Tactile Whisper</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- RESET & ROOT VARIABEL (Tema Tactile Whisper) --- */
        :root {
            --primary-pink: #f18d96;
            --primary-dark: #8c2a38;
            --text-dark: #333;
            --text-gray: #666;
            --text-light: #999;
            --bg-card: #ffffff; 
            --bg-page: #f5f5f5;
            --font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-family); background-color: var(--bg-page); color: var(--text-dark); overflow-x: hidden; }

        /* --- NAVBAR --- */
        .navbar {
            background-color: white; padding: 20px 40px; padding-right: 100px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); display: flex; justify-content: flex-end; gap: 40px; position: relative; z-index: 10;
        }
        .navbar a { font-size: 16px; color: #999; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .navbar a:hover, .navbar button:hover { color: var(--primary-pink); }

        /* --- CONTAINER UTAMA --- */
        .cart-container { max-width: 1100px; margin: 0 auto 60px; padding: 0 30px; }
        
        .page-title {
            color: var(--primary-dark); font-size: 28px; font-weight: 700; margin-bottom: 30px; margin-top: 50px; letter-spacing: -0.5px;
        }

        /* --- LAYOUT GRID --- */
        .cart-layout { display: grid; grid-template-columns: 1fr 380px; gap: 40px; align-items: start; }

        /* --- BAGIAN KIRI (DAFTAR ITEM) --- */
        .cart-items { display: flex; flex-direction: column; gap: 20px; }

        .cart-card {
            background-color: var(--bg-card); border-radius: 20px; padding: 20px; display: flex; align-items: center; gap: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .cart-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08); }

        .item-image {
            width: 100px; height: 100px; border-radius: 12px; overflow: hidden; background-color: #fce4e6; flex-shrink: 0;
        }
        .item-image img { width: 100%; height: 100%; object-fit: cover; }

        .item-details { display: flex; flex-direction: column; justify-content: center; flex: 1; }
        .item-title { font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 6px; }
        .item-variant { font-size: 14px; color: var(--primary-dark); font-weight: 600; margin-bottom: 12px; }

        .qty-selector {
            background-color: var(--bg-page); border-radius: 20px; display: inline-flex; align-items: center; width: fit-content; padding: 6px 15px; border: 1px solid #eee;
        }
        .qty-number { font-size: 13px; font-weight: 600; color: var(--text-gray); }

        /* --- BAGIAN KANAN (SUMMARY) --- */
        .summary-card {
            background-color: var(--bg-card); border-radius: 20px; padding: 35px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }
        .summary-title { font-size: 20px; font-weight: 700; color: var(--text-dark); margin-bottom: 30px; }
        
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 14px; }
        .summary-row .label { color: var(--text-gray); }
        .summary-row .value { color: var(--text-dark); font-weight: 600; }

        .summary-total {
            display: flex; justify-content: space-between; align-items: center; margin-top: 40px; margin-bottom: 35px;
            font-size: 18px; font-weight: 700; color: var(--primary-dark); border-top: 1px solid #eee; padding-top: 20px;
        }

        .checkout-btn {
            background-color: var(--primary-dark); color: white; border: none; width: 100%; padding: 16px; border-radius: 30px;
            font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(140, 42, 56, 0.2);
            display: flex; justify-content: center; align-items: center; gap: 10px;
        }
        .checkout-btn:hover { background-color: var(--primary-pink); transform: translateY(-3px); box-shadow: 0 6px 20px rgba(241, 141, 150, 0.4); }
        .checkout-btn:active { transform: translateY(0); }

        /* --- ANIMASI JS --- */
        .anim-fade-up { opacity: 0; transform: translateY(30px); transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1); }
        .anim-fade-up.show { opacity: 1; transform: translateY(0); }

        /* Responsive */
        @media (max-width: 768px) {
            .cart-layout { grid-template-columns: 1fr; }
            .navbar { padding: 15px 20px; justify-content: center; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="{{ url('/katalog') }}"><i class="fa-solid fa-store"></i> Catalog</a>
        <a href="{{ url('/cart') }}"><i class="fa-solid fa-cart-shopping"></i> Cart</a>
        <a href="{{ url('/history') }}"><i class="fa-solid fa-clock-rotate-left"></i> History</a>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" style="background:none; border:none; color:#999; font-size:16px; font-weight:500; cursor:pointer; font-family:inherit; margin-left: 10px; transition: color 0.3s;">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </nav>

    <main class="cart-container">
        
        <h1 class="page-title anim-fade-up">Your Cart</h1>

        <div class="cart-layout">
            
            <div class="cart-items">
                @forelse($carts as $cart)
                <div class="cart-card anim-fade-up item-anim">
                    <div class="item-image">
                        @if($cart->product->gambar)
                            <img src="{{ asset('images/' . $cart->product->gambar) }}" alt="{{ $cart->product->nama }}">
                        @else
                            <img src="https://via.placeholder.com/100x100/fce4e6/8c2a38?text={{ urlencode(substr($cart->product->nama, 0, 3)) }}" alt="{{ $cart->product->nama }}">
                        @endif
                    </div>
                    <div class="item-details">
                        <div class="item-title">{{ $cart->product->nama }}</div>
                        <div class="item-variant">Rp {{ number_format($cart->product->harga, 0, ',', '.') }}</div>
                        <div class="qty-selector">
                            <span class="qty-number">Qty: {{ $cart->kuantitas }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="anim-fade-up" style="text-align: center; padding: 60px 20px; background: white; border-radius: 20px;">
                    <i class="fa-solid fa-basket-shopping" style="font-size: 50px; color: #ddd; margin-bottom: 20px;"></i>
                    <h3 style="color: var(--text-gray); margin-bottom: 10px;">Keranjangmu masih kosong</h3>
                    <p style="color: var(--text-light); font-size: 14px;">Yuk, lihat-lihat katalog plushie kami dulu!</p>
                </div>
                @endforelse
            </div>

            @if(count($carts) > 0)
            <div class="summary-card anim-fade-up" style="transition-delay: 0.2s;">
                <h2 class="summary-title">Summary</h2>
                
                <div class="summary-row">
                    <span class="label">Subtotal</span>
                    <span class="value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                
                <div class="summary-row">
                    <span class="label">Shipping</span>
                    <span class="value" style="color: #28a745;">Free</span>
                </div>
                
                <div class="summary-row">
                    <span class="label">Taxes</span>
                    <span class="value">Rp 0</span>
                </div>

                <div class="summary-total">
                    <span class="label">Total</span>
                    <span class="value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <form action="{{ url('/checkout') }}" method="GET">
                    <button type="submit" class="checkout-btn">
                        Proceed to Checkout <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>
            </div>
            @endif

        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Animasi judul dan summary
            const basicElements = document.querySelectorAll('.anim-fade-up:not(.item-anim)');
            basicElements.forEach(el => {
                setTimeout(() => { el.classList.add('show'); }, 100);
            });

            // Animasi staggered (berurutan) khusus untuk item di dalam keranjang
            const cartItems = document.querySelectorAll('.item-anim');
            cartItems.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('show');
                }, 150 + (index * 100)); // Delay bertambah 100ms untuk setiap item ke bawah
            });
        });
    </script>
</body>
</html>