<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog - Tactile Whisper</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user.css'])
</head>
<body class="page-katalog">
    
    <div id="custom-toast">
        <i class="fa-solid fa-circle-check"></i>
        <span id="toast-message">Pesan sukses di sini!</span>
    </div>

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

    <div class="container">
        
        <div class="sidebar">
            <div class="sidebar-title">Collection</div>
            
            <a href="{{ url('/katalog') }}" class="collection-item {{ !request('kategori') ? 'active' : '' }}">
                All Collections
            </a>

            @foreach($kategoris as $kat)
                @if($kat) 
                <a href="{{ url('/katalog') }}?kategori={{ urlencode($kat) }}" class="collection-item {{ request('kategori') == $kat ? 'active' : '' }}">
                    {{ $kat }}
                </a>
                @endif
            @endforeach
        </div>
        
        <div class="main-content">
            <div class="products-grid">
                
                @forelse($products as $product)
                <a href="{{ route('katalog.show', $product->id) }}" style="text-decoration: none; color: inherit;">
                    <div class="product-card anim-card">
                        <div class="product-image">
                            @if($product->gambar)
                                @if(\Illuminate\Support\Str::startsWith($product->gambar, ['http://', 'https://']))
                                    <img src="{{ $product->gambar }}" alt="{{ $product->nama }}" style="width: 100%; height: 250px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/' . $product->gambar) }}" alt="{{ $product->nama }}" style="width: 100%; height: 250px; object-fit: cover;">
                                @endif
                            @else
                                <img src="https://via.placeholder.com/250x250/fce4e6/8c2a38?text={{ urlencode(substr($product->nama, 0, 5)) }}" alt="{{ $product->nama }}" style="width: 100%; height: 250px; object-fit: cover;">
                            @endif
                        </div>
                        <div class="product-info">
                            <div class="product-name">{{ $product->nama }}</div>
                            <div class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </a>
                @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 80px 20px; color: #999;">
                    <i class="fa-solid fa-ghost" style="font-size: 60px; margin-bottom: 20px; color: #ddd;"></i>
                    <h3 style="color: #666; margin-bottom: 10px;">Wah, kosong!</h3>
                    <p>Koleksi plushie untuk kategori ini belum tersedia.</p>
                </div>
                @endforelse

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            
            // 1. Animasi Staggered Fade-In untuk Kartu Produk
            const cards = document.querySelectorAll('.anim-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('show');
                }, index * 100); // Jeda 100ms per kartu agar muncul bergantian
            });

            // 2. Logika Modern Toast Notification
            const toast = document.getElementById('custom-toast');
            const toastMsg = document.getElementById('toast-message');
            
            // Cek apakah ada session success dari Laravel Blade
            const sessionSuccess = "{{ session('success') }}";
            
            if (sessionSuccess) {
                toastMsg.innerText = sessionSuccess;
                toast.classList.add('show');
                
                // Hilangkan notifikasi otomatis setelah 3.5 detik
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3500);
            }
        });
    </script>
</body>
</html>