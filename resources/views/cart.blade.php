<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Tactile Whisper</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user.css'])
</head>
<body class="page-cart">

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
<!-- LOGIKA CLOUDINARY -->
                    <div class="item-image">
                        @if($cart->product->gambar)
                            @if(\Illuminate\Support\Str::startsWith($cart->product->gambar, ['http://', 'https://']))
                                <img src="{{ $cart->product->gambar }}" alt="{{ $cart->product->nama }}">
                            @else
                                <img src="{{ asset('images/' . $cart->product->gambar) }}" alt="{{ $cart->product->nama }}">
                            @endif
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
                    <span class="value">Included</span>
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