<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Tactile Whisper - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css'])
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div>
                <div class="admin-profile">
                    <img src="/images/admin-profile.jpg" alt="Admin" class="profile-img" style="border-radius: 50%; width: 50px; height: 50px; object-fit: cover;">
                    <div class="text-group"><h3>Admin Plush</h3></div>
                </div>
                <nav class="sidebar-nav">
                    <ul>
                        <li class="{{ $tipe == 'pending' ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-clock"></i> Pending Orders</a></li>
                        <li class="{{ $tipe == 'approved' ? 'active' : '' }}"><a href="{{ route('admin.approved') }}"><i class="fa-solid fa-circle-check"></i> Approved Orders</a></li>
                        <li class="{{ $tipe == 'all' ? 'active' : '' }}"><a href="{{ route('admin.all') }}"><i class="fa-solid fa-list"></i> All Transactions</a></li>
                        <li class="{{ $tipe == 'users' ? 'active' : '' }}"><a href="{{ route('admin.users') }}"><i class="fa-solid fa-users"></i> Customers</a></li>
                        <li class="{{ $tipe == 'products' || $tipe == 'create_product' ? 'active' : '' }}">
                            <a href="{{ route('admin.products') }}"><i class="fa-solid fa-box-open"></i> Products</a>
                        </li>
                    </ul>
                </nav>
                <div class="logout-container" style="padding: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="approve-btn btn-red" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px; font-size: 14px; font-weight: bold; border-radius: 8px;">
                            <i class="fa-solid fa-right-from-bracket"></i> Log out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <header class="content-header">
                <div class="welcome-message">
                    <h2>
                        @if($tipe == 'users') Manage Customers
                        @elseif($tipe == 'pending') Pending Approvals
                        @elseif($tipe == 'approved') Approved Orders
                        @else All Transactions @endif
                    </h2>
                    <p>
                        @if($tipe == 'users') Lihat dan kelola akun pengguna terdaftar.
                        @else Pantau arus transaksi masuk tokomu. @endif
                    </p>
                </div>
            </header>

<!-- ALERT NOTIF TAMBAH PRODUK -->
            @if(session('success')) 
                <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div> 
            @endif

            @if(session('error')) 
                <div style="background-color: #fce4e6; color: #8c2a38; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #8c2a38;">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
                </div> 
            @endif

            <section class="recent-orders">
                
                @if($tipe == 'users')
                    <div class="section-header">
                        <h3>Daftar User</h3>
                        <a href="{{ route('admin.users.create') }}" class="approve-btn btn-green" style="text-decoration: none; font-size: 13px; padding: 10px 15px;">
                            <i class="fa-solid fa-plus"></i> Add New User
                        </a>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr><th>ID</th><th>USERNAME</th><th>EMAIL</th><th>ROLE</th><th>ACTION</th></tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                            <tr>
                                <td>{{ $u->id }}</td>
                                <td style="display:flex; align-items:center; gap:10px;">
                                    <div class="customer-avatar">{{ strtoupper(substr($u->username, 0, 1)) }}</div> {{ $u->username }}
                                </td>
                                <td>{{ $u->email }}</td>
                                <td><span class="status-badge {{ $u->role == 'admin' ? 'delivered' : 'pending' }}">{{ strtoupper($u->role) }}</span></td>
                                <td style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.users.edit', $u->id) }}" class="approve-btn btn-yellow" style="text-decoration: none; padding: 8px 12px;"><i class="fa-solid fa-pen"></i></a>
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="approve-btn btn-red" style="padding: 8px 12px;"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                @elseif($tipe == 'create_user')
                    <div class="section-header">
                        <h3>Tambah User Baru</h3>
                        <a href="{{ route('admin.users') }}" class="approve-btn" style="background-color: #6c757d; text-decoration: none;"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
                    </div>
                    <div class="form-container">
                        <form action="{{ route('admin.users.store') }}" method="POST">
                            @csrf
                            <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" required></div>
                            <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" minlength="8" placeholder="Masukkan minimal 8 karakter" required>
                                    <small style="color: var(--text-secondary); font-size: 11px; margin-top: 4px; display: block;">
                                        * Wajib diisi minimal 8 karakter.
                                    </small>
                                </div>
                            <div class="form-group">
                                <label>Role</label>
                                <select name="role" class="form-control" required>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-submit">Simpan User</button>
                        </form>
                    </div>

                @elseif($tipe == 'edit_user')
                    <div class="section-header">
                        <h3>Edit Data User</h3>
                        <a href="{{ route('admin.users') }}" class="approve-btn" style="background-color: #6c757d; text-decoration: none;"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
                    </div>
                    <div class="form-container">
                        <form action="{{ route('admin.users.update', $editUser->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" value="{{ $editUser->username }}" required></div>
                            <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="{{ $editUser->email }}" required></div>
                            <div class="form-group">
                                <label>Password Baru</label>
                                <input type="password" name="password" class="form-control" minlength="8" placeholder="Kosongkan jika tidak diubah">
                                <small style="color: var(--text-secondary); font-size: 11px; margin-top: 4px; display: block;">
                                    * Minimal 8 karakter. Biarkan kosong jika kamu tidak ingin mengganti password user ini.
                                </small>
                            </div>
                                <label>Role</label>
                                <select name="role" class="form-control" required>
                                    <option value="user" {{ $editUser->role == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $editUser->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-submit">Update Data User</button>
                        </form>
                    </div>

                    @elseif($tipe == 'products')
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

                @elseif($tipe == 'create_product')
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

                @elseif($tipe == 'edit_product')
                    <div class="section-header">
                        <h3>Edit Plushie</h3>
                        <a href="{{ route('admin.products') }}" class="approve-btn" style="background-color: #6c757d; text-decoration: none; color: white; padding: 10px 15px;"><i class="fa-solid fa-arrow-left"></i> Batal</a>
                    </div>
                    
                    <div class="form-container">
                        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="form-group">
                                <label>Nama Plushie</label>
                                <input type="text" name="nama" class="form-control" value="{{ $product->nama }}" required>
                            </div>
                            
                            <div class="form-group" style="position: relative; z-index: 999;">
                                <label>Kategori</label>
                                <input type="text" name="kategori" id="kategori-input" class="form-control" autocomplete="off" value="{{ $product->kategori }}" required placeholder="Pilih dari daftar atau ketik kategori baru...">
                                <ul id="kategori-dropdown" class="custom-dropdown-list">
                                    @if(isset($kategoris))
                                        @foreach($kategoris as $kat)
                                            @if($kat) <li class="dropdown-item">{{ $kat }}</li> @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            
                            <div style="display: flex; gap: 15px; position: relative; z-index: 1;">
                                <div class="form-group" style="flex: 1;" min="0" required>
                                    <label>Harga (Rp)</label>
                                    <input type="number" name="harga" class="form-control" value="{{ $product->harga }}" min="0" required>
                                </div>
                                <div class="form-group" style="flex: 1;" min="0" required>
                                    <label>Stok Saat Ini</label>
                                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" min="0" required>
                                </div>
                            </div>
                            
                            <div class="form-group" style="position: relative; z-index: 1;">
                                <label>Ganti Foto Produk (Opsional - Kosongkan jika foto tidak diubah)</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*" style="padding: 9px;">
                                
                                @if($product->gambar)
                                    <div style="margin-top: 10px; font-size: 13px; color: #666; display: flex; align-items: center; gap: 10px;">
                                        Foto saat ini:
                                        @if(\Illuminate\Support\Str::startsWith($product->gambar, ['http://', 'https://']))
                                            <img src="{{ $product->gambar }}" alt="{{ $product->nama }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <img src="{{ asset('images/' . $product->gambar) }}" alt="{{ $product->nama }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <button type="submit" class="btn-submit" style="position: relative; z-index: 1;">Perbarui Katalog</button>
                        </form>
                    </div>

                @elseif($tipe == 'all' || $tipe == 'pending' || $tipe == 'approved')
                    <div class="section-header">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 30px; margin-top: 15px; width: 100%;">
                        <div style="background: white; padding: 24px 30px; border-radius: 12px; border-left: 5px solid #28a745; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: center;">
                            <p style="margin: 0 0 8px 0; color: #8a92a6; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Sukses</p>
                            <h2 style="margin: 0; color: #232d3f; font-size: 36px; font-weight: 800;">{{ \App\Models\Transaksi::where('status', 'sukses')->count() }}</h2>
                        </div>
                        
                        <div style="background: white; padding: 24px 30px; border-radius: 12px; border-left: 5px solid #ffc107; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: center;">
                            <p style="margin: 0 0 8px 0; color: #8a92a6; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Menunggu Persetujuan</p>
                            <h2 style="margin: 0; color: #232d3f; font-size: 36px; font-weight: 800;">{{ \App\Models\Transaksi::where('status', 'pending')->count() }}</h2>
                        </div>
                        
                        <div style="background: white; padding: 24px 30px; border-radius: 12px; border-left: 5px solid #dc3545; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: center;">
                            <p style="margin: 0 0 8px 0; color: #8a92a6; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Ditolak</p>
                            <h2 style="margin: 0; color: #232d3f; font-size: 36px; font-weight: 800;">{{ \App\Models\Transaksi::where('status', 'ditolak')->count() }}</h2>
                        </div>

                    </div>
                    </div>
                    
                    @if($tipe == 'all')
                    <div style="margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ddd;">
                        <form action="{{ route('admin.all') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                            <div>
                                <label style="font-size: 11px; font-weight: bold; display: block; margin-bottom: 5px;">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control" style="padding: 8px;" value="{{ request('start_date') }}">
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: bold; display: block; margin-bottom: 5px;">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" style="padding: 8px;" value="{{ request('end_date') }}">
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: bold; display: block; margin-bottom: 5px;">Status</label>
                                <select name="status_filter" class="form-control" style="padding: 8px;">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="sukses" {{ request('status_filter') == 'sukses' ? 'selected' : '' }}>Sukses</option>
                                </select>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <button type="submit" class="approve-btn btn-green" style="padding: 9px 15px;"><i class="fa-solid fa-filter"></i> Filter</button>
                                <a href="{{ route('admin.all') }}" class="approve-btn" style="background-color: #6c757d; text-decoration: none; padding: 9px 15px;"><i class="fa-solid fa-rotate-right"></i> Reset</a>
                            </div>
                            <div style="margin-left: auto; display: flex; gap: 8px;">
                                <a href="{{ route('admin.export', request()->all()) }}" class="approve-btn" style="background-color: #17a2b8; text-decoration: none; padding: 9px 15px;; display: inline-block;">
                                    <i class="fa-solid fa-file-export"></i> Export Laporan (.csv)
                                </a>
                                <a href="{{ route('admin.export.pdf', request()->all()) }}" class="approve-btn" style="background-color: #b81717; text-decoration: none; padding: 9px 15px;">
                                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                                </a>
                                <a href="{{ route('admin.export.excel', request()->all()) }}" class="approve-btn" style="background-color: #00b62a; text-decoration: none; padding: 9px 15px;">
                                    <i class="fa-solid fa-file-excel"></i> Export Excel
                                </a>
                            </div>
                        </form>
                    </div>
                    @endif

                    <table class="data-table">
                        <thead>
                            <tr><th>INVOICE</th><th>CUSTOMER</th><th>TOTAL PRICE</th><th>STATUS</th><th>ACTION</th></tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->invoice }}</td>
                                <td style="display:flex; align-items:center; gap:10px;">
                                    <div class="customer-avatar">{{ strtoupper(substr($order->user?->username ?? '?', 0, 1)) }}</div>
                                    {{ $order->user?->username ?? 'Deleted User' }}
                                </td>
                                <td style="font-weight:600;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="status-badge {{ $order->status == 'pending' ? 'pending' : ($order->status == 'ditolak' ? 'rejected' : 'delivered') }}" 
                                            style="{{ $order->status == 'ditolak' ? 'background-color: #fce4e6; color: #dc3545;' : '' }}">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                </td>
                                
                                <td>
                                    @if($order->status == 'pending')
                                        <div style="display: flex; gap: 8px;">
                                            <form action="{{ route('admin.approve', $order->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="approve-btn btn-green" title="Setujui Pesanan"><i class="fa-solid fa-check"></i></button>
                                            </form>
                                            
                                            <form action="{{ route('admin.reject', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak transaksi ini?')">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="approve-btn btn-red" title="Tolak Pesanan"><i class="fa-solid fa-xmark"></i></button>
                                            </form>
                                        </div>
                                    @elseif($order->status == 'ditolak')
                                        <span style="color:#dc3545; font-size:11px; font-weight:700;"><i class="fa-solid fa-ban"></i> Ditolak</span>
                                    @else
                                        <span style="color:#28a745; font-size:11px; font-weight:700;"><i class="fa-solid fa-check-double"></i> Verified</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" style="text-align:center;">Tidak ada data transaksi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
                
            </section>
        </main>
    </div>

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

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            
            // A. Animasi untuk Header Seksi dan Pembungkus Form/Tabel
            const mainElements = document.querySelectorAll('.section-header, .form-container, .data-table, form[action*="admin.all"]');
            mainElements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.5s ease, transform 0.5s cubic-bezier(0.25, 0.8, 0.25, 1)';
                
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 100 + (index * 150));
            });

            // B. Animasi Staggered (Berurutan) untuk Baris Tabel
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-15px)';
                row.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                
                setTimeout(() => {
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, 300 + (index * 60));
            });

            // C. Animasi Staggered untuk Kolom Input di dalam Form
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach((group, index) => {
                group.style.opacity = '0';
                group.style.transform = 'translateY(10px)';
                group.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                
                setTimeout(() => {
                    group.style.opacity = '1';
                    group.style.transform = 'translateY(0)';
                }, 250 + (index * 80));
            });

            // D. Animasi Sidebar & Menu Item Berurutan
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.style.opacity = '0';
                sidebar.style.transform = 'translateX(-30px)';
                sidebar.style.transition = 'opacity 0.6s ease, transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1)';
                
                setTimeout(() => {
                    sidebar.style.opacity = '1';
                    sidebar.style.transform = 'translateX(0)';
                }, 50);

                const sidebarItems = document.querySelectorAll('.sidebar-nav li');
                sidebarItems.forEach((item, index) => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-15px)';
                    item.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, 200 + (index * 80));
                });
            }

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logika untuk Custom Dropdown Filter
            const dropdown = document.getElementById('kategoriDropdown');
            
            if(dropdown) {
                const trigger = dropdown.querySelector('.dropdown-trigger');
                const items = dropdown.querySelectorAll('.dropdown-item');
                const hiddenInput = document.getElementById('hidden-kategori');
                const form = document.getElementById('filterForm');

                // 1. Animasi buka/tutup saat diklik
                trigger.addEventListener('click', function(e) {
                    e.stopPropagation(); // Cegah klik bocor
                    dropdown.classList.toggle('open');
                });

                // 2. Saat salah satu kategori diklik
                items.forEach(item => {
                    item.addEventListener('click', function() {
                        const val = this.getAttribute('data-value');
                        
                        // Masukkan nilai kategori ke input tersembunyi
                        hiddenInput.value = val;
                        
                        // Tutup animasi dropdown
                        dropdown.classList.remove('open');
                        
                        // Langsung jalankan loading/submit (Mirip fitur auto-submit sebelumnya)
                        form.submit(); 
                    });
                });

                // 3. Tutup dropdown otomatis jika admin mengklik sembarang tempat di luar kotak
                window.addEventListener('click', function(e) {
                    if (!dropdown.contains(e.target)) {
                        dropdown.classList.remove('open');
                    }
                });
            }
        });
    </script>
</body>
</html>