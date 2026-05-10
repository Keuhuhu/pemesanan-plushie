<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->nama }} - Tactile Whisper</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user.css'])
</head>
<body class="page-detail">

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
                    @if(\Illuminate\Support\Str::startsWith($product->gambar, ['http://', 'https://']))
                        <img src="{{ $product->gambar }}" alt="{{ $product->nama }}">
                    @else
                        <img src="{{ asset('images/' . $product->gambar) }}" alt="{{ $product->nama }}">
                    @endif
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