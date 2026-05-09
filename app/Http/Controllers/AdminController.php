<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi; 
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TransaksiExport;
use Maatwebsite\Excel\Facades\Excel;
use Cloudinary\Cloudinary;


class AdminController extends Controller
{
    // 1. Fungsi untuk menampilkan halaman Pending Orders (Default Dashboard)
    public function index()
    {
        // Mengambil transaksi yang berstatus 'pending'
        $orders = Transaksi::with('user')->where('status', 'pending')->get();
        
        // Penanda bahwa kita sedang di halaman pending
        $tipe = 'pending'; 

        return view('admin.dashboard', compact('orders', 'tipe'));
    }

    // 2. Fungsi BARU untuk menampilkan halaman Approved Orders
    public function approved()
    {
        // Mengambil transaksi yang berstatus 'sukses'
        $orders = Transaksi::with('user')->where('status', 'sukses')->get();
        
        // Penanda bahwa kita sedang di halaman approved
        $tipe = 'approved'; 

        return view('admin.dashboard', compact('orders', 'tipe'));
    }

    // 3. Fungsi untuk mengubah status saat tombol Approve ditekan
    public function approve($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        $transaksi->update([
            'status' => 'sukses'
        ]);

        return redirect()->back()->with('success', 'Transaksi ' . $transaksi->invoice . ' berhasil disetujui!');
    }

    //ALL TRANSAKSI (+FILTER)
    public function all(Request $request)
    {
        $query = Transaksi::with('user');

        // 1. Filter Rentang Tanggal (Start Date & End Date)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            // Tambahkan jam agar mencakup transaksi hingga detik terakhir di hari tersebut
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        } 
        // Jika admin HANYA mengisi Start Date (Mencari dari tanggal X sampai sekarang)
        elseif ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date . ' 00:00:00');
        } 
        // Jika admin HANYA mengisi End Date (Mencari dari awal toko buka sampai tanggal X)
        elseif ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // 2. Filter Status Transaksi
        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();
        $tipe = 'all'; 

        return view('admin.dashboard', compact('orders', 'tipe'));
    }


    // Fungsi untuk menampilkan halaman Daftar User
    public function users()
    {
        // Mengambil semua data user dari tabel users
        $users = \App\Models\userplush::all();
        $tipe = 'users'; // Penanda halaman aktif

        return view('admin.dashboard', compact('users', 'tipe'));
    }

    // Fungsi untuk menghapus user
    public function destroyUser($id)
    {
        $user = \App\Models\userplush::findOrFail($id);
        
        // Mencegah admin menghapus dirinya sendiri (opsional tapi disarankan)
        if ($user->id == auth()->id()) {
            return redirect()->back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User ' . $user->username . ' berhasil dihapus!');
    }


    // 1. Menampilkan form Tambah
    public function createUser()
    {
        $tipe = 'create_user';
        return view('admin.dashboard', compact('tipe'));
    }

    // 2. Menyimpan data user baru ke database
    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        \App\Models\userplush::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password wajib di-hash!
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'User baru berhasil ditambahkan!');
    }

    // 3. Menampilkan form Edit
    public function editUser($id)
    {
        $editUser = \App\Models\userplush::findOrFail($id);
        $tipe = 'edit_user';
        return view('admin.dashboard', compact('editUser', 'tipe'));
    }

    // 4. Menyimpan perubahan data user
    public function updateUser(Request $request, $id)
    {
        $user = \App\Models\userplush::findOrFail($id);

        $request->validate([
            // unique:users,username,'.$id -> Artinya boleh pakai username lama milik dia sendiri
            'username' => 'required|string|max:255|unique:users,username,'.$id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|in:admin,user',
        ]);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->role = $request->role;

        // Hanya ubah password jika kolom password diisi (tidak kosong)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'Data user berhasil diperbarui!');
    }

    //LOGIKA EKSPOR KE CSV
    public function export(Request $request)
    {
    // 1. Ambil data dengan filter yang sama persis seperti di tabel
    $query = Transaksi::with('user');

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
    } elseif ($request->filled('start_date')) {
        $query->where('created_at', '>=', $request->start_date . ' 00:00:00');
    } elseif ($request->filled('end_date')) {
        $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
    }

    if ($request->filled('status_filter')) {
        $query->where('status', $request->status_filter);
    }

    $orders = $query->orderBy('created_at', 'desc')->get();

    // 2. Siapkan file CSV
    $fileName = 'Laporan_Transaksi_' . date('Y-m-d_H-i-s') . '.csv';

    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $columns = ['Invoice', 'Customer', 'Email', 'Total Harga', 'Status', 'Tanggal'];

    $callback = function() use($orders, $columns) {
        $file = fopen('php://output', 'w');
        // Tambahkan BOM agar karakter terbaca dengan benar di Excel (UTF-8)
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($file, $columns);

        foreach ($orders as $order) {
            fputcsv($file, [
                $order->invoice,
                $order->user?->username ?? 'Deleted User',
                $order->user?->email ?? '-',
                $order->total_harga,
                strtoupper($order->status),
                $order->created_at->format('d M Y, H:i')
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

//EKSPOR KE PDF
public function exportPdf(Request $request)
    {
        // 1. Ambil data dengan filter (Logika filternya persis sama dengan fungsi all())
        $query = Transaksi::with('user');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // 2. Kirim data ke tampilan khusus PDF lalu download
        $pdf = Pdf::loadView('admin.report_pdf', compact('orders'));
        
        return $pdf->download('Laporan_Penjualan_Tactile_Whisper.pdf');
    }


    //EKSPOR KE EXCEL
    public function exportExcel(Request $request)
    {
        $query = Transaksi::with('user');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return Excel::download(new TransaksiExport($orders), 'Laporan_Penjualan_Tactile_Whisper.xlsx');
    }

    // 1. Menampilkan Daftar Produk
    public function products()
    {
        $products = \App\Models\Product::orderBy('created_at', 'desc')->get();
        $tipe = 'products';
        return view('admin.dashboard', compact('products', 'tipe'));
    }

    // 2. Menampilkan Form Tambah Produk
    public function createProduct()
    {
        $tipe = 'create_product';
        // Ambil daftar kategori yang sudah ada di database untuk dijadikan sugesti/pilihan
        $kategoris = \App\Models\Product::select('kategori')->distinct()->pluck('kategori');
        return view('admin.dashboard', compact('tipe', 'kategoris'));
    }

    // 3. Menyimpan Produk & Upload Gambar
    public function storeProduct(Request $request)
    {
        // Validasi inputan admin
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Maksimal 2MB
        ]);

        // Upload ke Cloudinary
        // 1. Panggil Cloudinary menggunakan URL dari .env
        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

        // 2. Eksekusi Upload
        $uploadResult = $cloudinary->uploadApi()->upload($request->file('gambar')->getRealPath(), [
            'folder' => 'plushie_shop'
        ]);

        // 3. Ambil URL aman (HTTPS) dari hasil upload
        $uploadedFileUrl = $uploadResult['secure_url'];

        // Simpan ke Database
        \App\Models\Product::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stock' => $request->stock,
            'gambar' => $uploadedFileUrl, // Menyimpan link panjang Cloudinary
        ]);

        return redirect()->route('admin.products')->with('success', 'Plushie baru berhasil ditambahkan ke katalog!');
    }

    // 4. Menghapus Produk
    public function destroyProduct($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
    }

    //EDIT PRODUK
    public function editProduct($id)
    {
        $tipe = 'edit_product';
        $product = \App\Models\Product::findOrFail($id);
        
        // Ambil daftar kategori agar dropdown tetap berfungsi
        $kategoris = \App\Models\Product::select('kategori')->distinct()->pluck('kategori');
        
        return view('admin.dashboard', compact('tipe', 'product', 'kategoris'));
    }

    // Memproses penyimpanan pembaruan data
    public function updateProduct(Request $request, $id)
    {
        // Validasi: perhatikan bahwa gambar sekarang 'nullable' (boleh kosong)
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $product = \App\Models\Product::findOrFail($id);

        // Update data dasar
        $product->nama = $request->nama;
        $product->kategori = $request->kategori;
        $product->harga = $request->harga;
        $product->stock = $request->stock;

        // Cek apakah admin mengunggah gambar baru
        if ($request->hasFile('gambar')) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            $uploadResult = $cloudinary->uploadApi()->upload($request->file('gambar')->getRealPath(), [
                'folder' => 'plushie_shop'
            ]);
            $uploadedFileUrl = $uploadResult['secure_url'];
            
            $product->gambar = $uploadedFileUrl;
        }
        $product->save();

        return redirect()->route('admin.products')->with('success', 'Detail plushie berhasil diperbarui!');
    }
}

