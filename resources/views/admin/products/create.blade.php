@extends('layouts.admin')

@section('pageTitle', 'Manage Customers')
@section('pageSubtitle', 'Pantau arus transaksi masuk tokomu.')

@section('content')
    <div class="section-header">
        <h3>Upload Plushie Baru</h3>
        <a href="{{ route('admin.products') }}" class="approve-btn" style="background-color: #6c757d; text-decoration: none;"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="form-container">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Nama Plushie</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="form-group" style="position: relative; z-index: 999;">
                <label>Kategori</label>
                <input type="text" name="kategori" id="kategori-input" class="form-control" autocomplete="off" required placeholder="Pilih dari daftar atau ketik kategori baru...">
                
                <ul id="kategori-dropdown" class="custom-dropdown-list">
                    @if(isset($kategoris))
                        @foreach($kategoris as $kat)
                            @if($kat)
                                <li class="dropdown-item">{{ $kat }}</li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
            
            <div style="display: flex; gap: 15px; position: relative; z-index: 1;">
                <div class="form-group" style="flex: 1;" min="0" required>
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control" min="0" required>
                </div>
                <div class="form-group" style="flex: 1;" min="0" required>
                    <label>Stok Awal</label>
                    <input type="number" name="stock" class="form-control" min="0" required>
                </div>
            </div>
            <div class="form-group" style="position: relative; z-index: 1;">
                <label>Foto Produk (Maks 2MB)</label>
                <input type="file" name="gambar" class="form-control" accept="image/*" required style="padding: 9px;">
            </div>
            <button type="submit" class="btn-submit" style="position: relative; z-index: 1;">Simpan ke Katalog</button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputKategori = document.getElementById('kategori-input');
        const dropdownKategori = document.getElementById('kategori-dropdown');
        
        if(inputKategori && dropdownKategori) {
            const items = dropdownKategori.querySelectorAll('.dropdown-item');

            inputKategori.addEventListener('focus', () => {
                dropdownKategori.classList.add('show');
            });

            inputKategori.addEventListener('input', () => {
                const filter = inputKategori.value.toLowerCase();
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
                dropdownKategori.classList.add('show');
            });

            items.forEach(item => {
                item.addEventListener('click', () => {
                    inputKategori.value = item.textContent;
                    dropdownKategori.classList.remove('show');
                });
            });

            document.addEventListener('click', (e) => {
                if (!inputKategori.contains(e.target) && !dropdownKategori.contains(e.target)) {
                    dropdownKategori.classList.remove('show');
                }
            });
        }
    });
</script>
@endsection
