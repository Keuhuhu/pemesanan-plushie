<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Tactile Whisper - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- RESET DASAR --- */
        :root {
            --bg-main: #f9f9f9;
            --bg-sidebar: #f5f5f5;
            --bg-card: #ffffff;
            --primary-color: #f18d96; 
            --accent-color: #8c2a38; 
            --status-pending-bg: #e0e0e0; 
            --status-pending-text: #707070;
            --status-delivered-bg: #d4edda; /* Hijau untuk sukses */
            --status-delivered-text: #155724;
            --text-primary: #333333;
            --text-secondary: #777777;
            --font-family: 'Inter', sans-serif;
            --border-radius-card: 20px;
            --shadow-soft: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-main);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* --- TATA LETAK UTAMA --- */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 250px;
            background-color: var(--bg-sidebar);
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 50px;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .admin-profile .text-group h3 {
            font-size: 16px;
            font-weight: 600;
        }

        .sidebar-nav ul {
            list-style: none;
        }

        .sidebar-nav li {
            margin-bottom: 25px;
        }

        .sidebar-nav a {
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            font-weight: 500;
        }

        .sidebar-nav li.active a {
            color: var(--text-primary);
            font-weight: 600;
            background-color: var(--primary-color);
            padding: 10px 15px;
            border-radius: 12px;
        }

        /* --- KONTEN UTAMA --- */
        .main-content {
            flex: 1;
            padding: 50px 70px;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 50px;
        }

        .welcome-message h2 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome-message p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
            color: var(--text-secondary);
        }

        .date-picker {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #eaeaea;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 14px;
            color: var(--text-primary);
            font-weight: 500;
        }

        /* --- TABEL PESANAN --- */
        .recent-orders {
            background-color: var(--bg-card);
            border-radius: var(--border-radius-card);
            box-shadow: var(--shadow-soft);
            padding: 30px;
            width: 100%; /* Dibuat penuh karena metrik samping dihapus */
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th, .data-table td {
            padding: 15px 10px;
            text-align: left;
            font-size: 14px;
            color: var(--text-secondary);
            border-bottom: 1px solid #eaeaea;
            vertical-align: middle;
        }

        .data-table th {
            font-weight: 600;
            color: var(--text-primary);
        }

        .customer-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .customer-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            font-size: 12px;
        }

        .price-cell {
            font-weight: 600;
            color: var(--text-primary);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
            width: 90px;
            text-align: center;
        }

        .status-badge.pending {
            background-color: var(--status-pending-bg);
            color: var(--status-pending-text);
        }

        /* --- TOMBOL APPROVE --- */
        .approve-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .approve-btn:hover {
            background-color: #218838;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .btn-red { background-color: #dc3545; }
        .approve-btn.btn-red:hover { background-color: #791c26; }
        .btn-yellow { background-color: #ffcc66; color: #333; }
        .approve-btn.btn-yellow:hover {background-color: #b28e47;}
        
        /* Tambahan CSS untuk Form */
        .form-container { background-color: var(--bg-card); padding: 30px; border-radius: var(--border-radius-card); box-shadow: var(--shadow-soft); max-width: 600px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: var(--font-family); }
        .form-control:focus { outline: none; border-color: var(--primary-color); }
        .btn-submit { background-color: var(--primary-color); color: white; padding: 12px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; width: 100%; margin-top: 10px; }
        .text-danger { color: #dc3545; font-size: 12px; margin-top: 5px; display: block; }

        /* --- CSS Custom Dropdown Kategori --- */
        .custom-dropdown-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            margin-top: 5px;
            padding: 0;
            list-style: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            
            /* Animasi Berada Di Sini */
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px); /* Posisi awal agak ke atas */
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* Kelas ini akan ditambahkan oleh JS untuk memunculkan dropdown */
        .custom-dropdown-list.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0); /* Meluncur turun ke posisi asli */
        }

        .dropdown-item {
            padding: 12px 15px;
            cursor: pointer;
            font-size: 13px;
            color: var(--text-primary);
            border-bottom: 1px solid #f8f9fa;
            transition: all 0.2s ease;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background-color: #f18d96; /* Warna pink tema toko */
            color: white;
            padding-left: 20px; /* Animasi teks bergeser sedikit saat disentuh */
        }

        /* --- TEMA TACTILE WHISPER UNTUK ADMIN --- */
        :root {
            --primary-pink: #f18d96;
            --primary-dark: #8c2a38;
            --bg-page: #f5f5f5;
        }

        /* Merapikan Tabel agar lebih modern */
        .data-table {
            width: 100%; border-collapse: separate; border-spacing: 0; 
            background: white; border-radius: 16px; overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            margin-top: 15px;
        }
        .data-table th { padding: 15px 20px; font-weight: 600; font-size: 13px; letter-spacing: 0.5px; }
        .data-table td { padding: 15px 20px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
        .data-table tbody tr:hover { background-color: #fcfcfc; }
        .data-table tbody tr:last-child td { border-bottom: none; }

        /* Mempercantik Tombol Admin */
        .approve-btn { border-radius: 8px; transition: all 0.3s ease; border: none; font-weight: 600; }
        .approve-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
        .btn-green { background-color: #28a745; color: white; }
        .btn-red { background-color: var(--primary-dark); color: white; }
        .btn-yellow { background-color: #ffc107; color: #333; }

        /* Mempercantik Form */
        .form-container {
            background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }
        .form-control {
            border-radius: 8px; border: 1px solid #ddd; padding: 12px 15px; transition: all 0.3s;
        }
        .form-control:focus { border-color: var(--primary-pink); box-shadow: 0 0 0 3px rgba(241, 141, 150, 0.2); }
        
        .btn-submit {
            background-color: var(--primary-dark); color: white; border: none; border-radius: 8px; 
            padding: 12px 25px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; width: 100%;
            margin-top: 10px;
        }
        .btn-submit:hover { background-color: var(--primary-pink); transform: translateY(-2px); }

        /* Badge Status */
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .status-badge.pending { background-color: #fff3cd; color: #856404; }
        .status-badge.delivered { background-color: #d4edda; color: #155724; }

        /* --- MEMPERCANTIK SIDEBAR ADMIN --- */
        .sidebar {
            background-color: white;
            box-shadow: 2px 0 15px rgba(0,0,0,0.03);
            border-right: none; /* Menghilangkan border kaku bawaan lama */
            z-index: 10;
        }
        
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        .sidebar-nav li {
            margin-bottom: 5px;
        }

        .sidebar-nav li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 25px;
            color: #666;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            border-left: 4px solid transparent;
        }

        /* Efek Hover: Berubah warna pink dan teks bergeser sedikit ke kanan */
        .sidebar-nav li a:hover {
            color: var(--primary-dark);
            background-color: #fcf0f1;
            border-left-color: var(--primary-pink);
            padding-left: 32px; 
        }

        /* Status Aktif: Warna merah marun tegas */
        .sidebar-nav li.active a {
            color: var(--primary-dark);
            background-color: #fce4e6;
            border-left-color: var(--primary-dark);
            font-weight: 700;
        }

        .sidebar-nav li a i {
            font-size: 18px;
            width: 24px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        /* Ikon sedikit melompat saat di-hover */
        .sidebar-nav li a:hover i {
            transform: scale(1.15);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div>
                <div class="admin-profile">
                    <img src="#" alt="Admin" class="profile-img">
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

            @if(session('success')) <div class="alert-success">{{ session('success') }}</div> @endif
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
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>GAMBAR</th>
                                <th>NAMA PRODUK</th>
                                <th>HARGA</th>
                                <th>STOK</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $p)
                            <tr>
                                <td>
                                    <img src="{{ asset('images/' . $p->gambar) }}" alt="{{ $p->nama_produk }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                </td>
                                <td style="font-weight: 600;">{{ $p->nama }}</td>
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
                                        Foto saat ini: <img src="{{ asset('images/' . $product->gambar) }}" alt="{{ $product->nama }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn-submit" style="position: relative; z-index: 1;">Perbarui Katalog</button>
                        </form>
                    </div>

                @elseif($tipe == 'all' || $tipe == 'pending' || $tipe == 'approved')
                    <div class="section-header">
                        <h3>
                            @if($tipe == 'all') Riwayat Transaksi 
                            @elseif($tipe == 'pending') Transaksi Menunggu Persetujuan
                            @elseif($tipe == 'approved') Transaksi Disetujui
                            @endif
                        </h3>
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
                                <td><span class="status-badge {{ $order->status == 'pending' ? 'pending' : 'delivered' }}">{{ strtoupper($order->status) }}</span></td>
                                <td>
                                    @if($order->status == 'pending')
                                        <form action="{{ route('admin.approve', $order->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="approve-btn btn-green">Approve</button>
                                        </form>
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
</body>
</html>