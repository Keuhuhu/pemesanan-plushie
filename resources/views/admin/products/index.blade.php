@extends('layouts.admin')

@section('pageTitle', 'Manage Customers')
@section('pageSubtitle', 'Pantau arus transaksi masuk tokomu.')

@section('content')
    <div class="section-header">
        <h3>Katalog Plushie</h3>
        <a href="{{ route('admin.products.create') }}" class="approve-btn btn-green" style="text-decoration: none; padding: 10px 15px;">
            <i class="fa-solid fa-plus"></i> Tambah Produk
        </a>
    </div>
    
    <div style="margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ddd; display: flex; justify-content: flex-end;">
        <form action="{{ route('admin.products') }}" method="GET" id="filterForm" style="display: flex; gap: 15px; align-items: center;">
            <label style="font-size: 13px; font-weight: bold; margin: 0; color: #4a5568;">
                <i class="fa-solid fa-filter"></i> Filter Kategori:
            </label>
            
            <div class="custom-dropdown" id="kategoriDropdown">
                <div class="dropdown-trigger">
                    <span id="dropdown-text">
                        {{ isset($kategoriFilter) && $kategoriFilter != '' ? ucfirst($kategoriFilter) : 'Semua Kategori' }}
                    </span>
                    <i class="fa-solid fa-chevron-down arrow-icon"></i>
                </div>
                
                <ul class="dropdown-menu">
                    <li class="dropdown-item {{ empty($kategoriFilter) ? 'active' : '' }}" data-value="">Semua Kategori</li>
                    
                    @if(isset($kategoris))
                        @foreach($kategoris as $kat)
                            @if($kat)
                                <li class="dropdown-item {{ (isset($kategoriFilter) && $kategoriFilter == $kat) ? 'active' : '' }}" data-value="{{ $kat }}">
                                    {{ ucfirst($kat) }}
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
                
                <input type="hidden" name="kategori" id="hidden-kategori" value="{{ $kategoriFilter ?? '' }}">
            </div>
            
            @if(isset($kategoriFilter) && $kategoriFilter != '')
                <a href="{{ route('admin.products') }}" class="approve-btn" style="background-color: #6c757d; text-decoration: none; padding: 10px 15px; border-radius: 10px;">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>GAMBAR</th>
                <th>NAMA PRODUK</th>
                <th>KATEGORI</th> <th>HARGA</th>
                <th>STOK</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>
<!--LOGIKA CLOUDINARY -->
                    @if($p->gambar)
                        @if(\Illuminate\Support\Str::startsWith($p->gambar, ['http://', 'https://']))
                            <img src="{{ $p->gambar }}" alt="{{ $p->nama }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        @else
                            <img src="{{ asset('images/' . $p->gambar) }}" alt="{{ $p->nama }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        @endif
                    @else
                        <div style="width: 50px; height: 50px; background: #eee; border-radius: 8px;"></div>
                    @endif
                </td>
                <td style="font-weight: 600;">{{ $p->nama }}</td>
                
                <td>
                    <span style="background-color: #e2e8f0; color: #4a5568; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                        {{ ucfirst($p->kategori ?? 'Tanpa Kategori') }}
                    </span>
                </td>
                
                <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                <td>
                    <span class="status-badge {{ $p->stok > 5 ? 'delivered' : 'pending' }}">
                        {{ $p->stock }} Pcs
                    </span>
                </td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('admin.products.edit', $p->id) }}" class="approve-btn btn-yellow" style="text-decoration: none; padding: 8px 12px; display: inline-flex; align-items: center; justify-content: center;" title="Edit Produk">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        
                        <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini dari toko?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="approve-btn btn-red" style="padding: 8px 12px;" title="Hapus Produk"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdown = document.getElementById('kategoriDropdown');
        
        if(dropdown) {
            const trigger = dropdown.querySelector('.dropdown-trigger');
            const items = dropdown.querySelectorAll('.dropdown-item');
            const hiddenInput = document.getElementById('hidden-kategori');
            const form = document.getElementById('filterForm');

            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('open');
            });

            items.forEach(item => {
                item.addEventListener('click', function() {
                    const val = this.getAttribute('data-value');
                    hiddenInput.value = val;
                    dropdown.classList.remove('open');
                    form.submit(); 
                });
            });

            window.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });
        }
    });
</script>
@endsection
