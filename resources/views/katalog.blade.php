<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog - Tactile Whisper</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; color: #333; overflow-x: hidden; }

        /* Header/Navigation */
        .navbar {
            background-color: white; padding: 20px 40px; padding-right: 100px; /* Disesuaikan agar tidak terlalu jauh ke kiri */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); display: flex; justify-content: flex-end; gap: 40px; position: relative; z-index: 10;
        }
        .navbar a { font-size: 16px; color: #999; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .navbar a:hover, .navbar button:hover { color: #f18d96; }

        /* Main Container */
        .container { display: flex; height: calc(100vh - 60px); }

        /* Sidebar */
        .sidebar {
            width: 300px; background-color: white; border-right: 4px solid #f18d96; padding: 30px 20px; overflow-y: auto;
        }
        .sidebar-title { font-size: 20px; font-weight: 800; color: #333; margin-bottom: 25px; margin-left: 20px; text-transform: uppercase; letter-spacing: 1px; }

        /* PERBAIKAN CSS COLLECTION ITEM */
        .collection-item {
            display: block; /* Dipindah dari inline style */
            text-decoration: none; /* Dipindah dari inline style */
            font-size: 15px; color: #666; padding: 12px 20px; cursor: pointer; transition: all 0.3s ease; border-left: 3px solid transparent; margin-bottom: 5px; border-radius: 0 8px 8px 0;
        }
        .collection-item:hover { color: #f18d96; background-color: #fcf0f1; border-left-color: #f18d96; }
        .collection-item.active { color: #8c2a38; font-weight: 700; background-color: #fce4e6; border-left-color: #8c2a38; }

        /* Main Content */
        .main-content { flex: 1; padding: 40px; overflow-y: auto; }
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 30px; }

        /* Product Card & Animations */
        .product-card {
            background-color: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); cursor: pointer; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            /* Persiapan untuk animasi JS */
            opacity: 0; transform: translateY(30px); 
        }
        .product-card.show {
            opacity: 1; transform: translateY(0);
        }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1); }
        
        .product-image {
            width: 100%; height: 280px; background: #f8f9fa; overflow: hidden; position: relative;
        }
        .product-image img {
            width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;
        }
        .product-card:hover .product-image img { transform: scale(1.05); } /* Efek zoom tipis saat dihover */

        .product-info { padding: 20px; text-align: center; }
        .product-name { font-size: 15px; font-weight: 600; color: #333; margin-bottom: 8px; line-height: 1.4; }
        .product-price { font-size: 18px; color: #8c2a38; font-weight: 700; }

        /* Custom Modern Toast Notification */
        #custom-toast {
            position: fixed; bottom: 30px; right: 30px; background-color: #28a745; color: white; padding: 15px 25px; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 12px; font-weight: 500; z-index: 9999;
            transform: translateX(150%); transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        #custom-toast.show { transform: translateX(0); }

        /* Scrollbar Styling */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #f18d96; }

        /* Responsive */
        @media (max-width: 768px) {
            .container { flex-direction: column; height: auto; }
            .sidebar { width: 100%; border-right: none; border-bottom: 4px solid #f18d96; display: flex; gap: 10px; padding: 20px; overflow-x: auto; white-space: nowrap; }
            .sidebar-title { display: none; } /* Sembunyikan judul di HP agar menghemat ruang */
            .collection-item { border-left: none; border-bottom: 3px solid transparent; padding: 10px 15px; border-radius: 8px 8px 0 0; }
            .collection-item:hover, .collection-item.active { border-left-color: transparent; border-bottom-color: #8c2a38; }
            .navbar { padding: 15px 20px; justify-content: center; }
        }
    </style>
</head>
<body>
    
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