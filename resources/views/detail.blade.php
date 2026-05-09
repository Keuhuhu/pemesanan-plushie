<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->nama }} - Tactile Whisper</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- RESET & ROOT VARIABEL (Sesuai Tema Tactile Whisper) --- */
        :root {
            --primary-pink: #f18d96;
            --primary-dark: #8c2a38;
            --text-dark: #333;
            --text-gray: #666;
            --bg-gray: #f8f9fa;
            --font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-family); background-color: #f5f5f5; color: var(--text-dark); overflow-x: hidden; }

        /* --- NAVBAR (Konsisten dengan Katalog) --- */
        .navbar {
            background-color: white; padding: 20px 40px; padding-right: 100px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); display: flex; justify-content: flex-end; gap: 40px; position: relative; z-index: 10;
        }
        .navbar a { font-size: 16px; color: #999; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .navbar a:hover, .navbar button:hover { color: var(--primary-pink); }

        /* --- LAYOUT UTAMA --- */
        .main-container {
            max-width: 1100px; margin: 50px auto; padding: 0 30px; display: flex; gap: 60px;
        }

        /* --- KOLOM KIRI (GAMBAR) --- */
        .left-col { flex: 1; max-width: 450px; }

        .breadcrumb {
            font-size: 14px; margin-bottom: 25px; color: var(--primary-dark); font-weight: 500;
        }
        .breadcrumb a { color: var(--text-gray); text-decoration: none; transition: color 0.2s; }
        .breadcrumb a:hover { color: var(--primary-pink); }
        .breadcrumb span { color: var(--primary-dark); font-weight: 600; }

        .main-image-container {
            background-color: white; border-radius: 20px; padding: 15px; display: flex; justify-content: center; align-items: center; 
            height: 450px; margin-bottom: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .main-image-container img {
            width: 100%; height: 100%; object-fit: cover; border-radius: 12px; transition: transform 0.4s ease;
        }
        .main-image-container:hover img { transform: scale(1.03); }

        /* --- KOLOM KANAN (INFO PRODUK) --- */
        .right-col { flex: 1; padding-top: 45px; }

        .product-title {
            font-size: 32px; color: var(--text-dark); font-weight: 800; margin-bottom: 15px; letter-spacing: -0.5px; line-height: 1.2;
        }
        .product-price {
            font-size: 24px; color: var(--primary-dark); font-weight: 700; margin-bottom: 30px;
        }
        .product-desc {
            color: var(--text-gray); font-size: 15px; line-height: 1.7; margin-bottom: 25px;
        }
        .product-specs {
            background-color: white; padding: 20px; border-radius: 12px; border-left: 4px solid var(--primary-pink);
            color: var(--text-gray); font-size: 14px; line-height: 1.8; margin-bottom: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        .product-specs strong { color: var(--text-dark); }

        /* --- TOMBOL ADD TO CART --- */
        .add-to-cart-btn {
            background-color: var(--primary-dark); color: white; border: none; padding: 16px 40px; border-radius: 30px; 
            font-size: 16px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; 
            width: fit-content; transition: all 0.3s ease; box-shadow: 0 6px 15px rgba(140, 42, 56, 0.3);
        }
        .add-to-cart-btn:hover {
            background-color: var(--primary-pink); box-shadow: 0 8px 20px rgba(241, 141, 150, 0.4); transform: translateY(-3px);
        }
        .add-to-cart-btn:active { transform: translateY(0); }

        /* --- ANIMASI JS --- */
        .anim-element { opacity: 0; transform: translateY(25px); transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1); }
        .anim-element.show { opacity: 1; transform: translateY(0); }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container { flex-direction: column; gap: 30px; margin: 30px auto; }
            .right-col { padding-top: 10px; }
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

    <main class="main-container">
        
        <div class="left-col anim-element">
            <div class="breadcrumb">
                <a href="{{ url('/katalog') }}">Catalog</a> &rsaquo; 
                <a href="{{ url('/katalog') }}?kategori={{ urlencode($product->kategori) }}">{{ $product->kategori }}</a> &rsaquo; 
                <span>{{ $product->nama }}</span>
            </div>

            <div class="main-image-container">
                @if($product->gambar)
                    <img src="{{ asset('images/' . $product->gambar) }}" alt="{{ $product->nama }}">
                @else
                    <img src="https://via.placeholder.com/400x500/fce4e6/8c2a38?text={{ urlencode($product->nama) }}" alt="{{ $product->nama }}">
                @endif
            </div>
            
            </div>

        <div class="right-col anim-element">
            <h1 class="product-title">{{ $product->nama }}</h1>
            <div class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
            
            <p class="product-desc">
                From <strong>"{{ $product->kategori }}"</strong> 
                comes a plushie of <strong>"{{ $product->nama }}"</strong>.
            </p>
            
            <div class="product-specs">
                <strong><i class="fa-solid fa-tag"></i> Kategori:</strong> {{ $product->kategori }} <br>
                <strong><i class="fa-solid fa-box"></i> Ketersediaan:</strong> 
                @if($product->stock > 0)
                    <span style="color: #28a745; font-weight: 600;">In Stock ({{ $product->stock }} pcs)</span>
                @else
                    <span style="color: #dc3545; font-weight: 600;">Sold Out</span>
                @endif
                <br>
                <strong><i class="fa-solid fa-ruler-combined"></i> Ukuran:</strong> Approx. 170mm in height <br>
                <strong><i class="fa-solid fa-gem"></i> Material:</strong> Premium Polyester
            </div>

            @if($product->stock > 0)
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="kuantitas" value="1">
                    <button type="submit" class="add-to-cart-btn">
                        <i class="fa-solid fa-cart-plus"></i> Add to Cart
                    </button>
                </form>
            @else
                <button class="add-to-cart-btn" style="background-color: #ccc; cursor: not-allowed; box-shadow: none;">
                    <i class="fa-solid fa-ban"></i> Out of Stock
                </button>
            @endif
        </div>

    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const animElements = document.querySelectorAll('.anim-element');
            animElements.forEach((el, index) => {
                setTimeout(() => {
                    el.classList.add('show');
                }, index * 150); // Jeda animasi antara kolom kiri dan kanan
            });
        });
    </script>
</body>
</html>