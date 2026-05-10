<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlushController extends Controller
{
    // ==========================================
    // BAGIAN 1: FITUR SISI PENGGUNA (USER)
    // ==========================================

    /**
     * Menampilkan Halaman Katalog Produk
     */
    public function index(Request $request)
    {
        // 1. Siapkan Kueri Dasar
        $query = \App\Models\Product::query();

        // 2. Jika ada filter kategori di URL yang diklik, saring datanya!
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Ambil produknya
        $products = $query->get();

        // 3. Ambil daftar kategori UNIK dari database untuk Sidebar
        // Ini akan otomatis mengambil "Fate Grand Order", "Cats", dll tanpa ada yang dobel
        $kategoris = \App\Models\Product::select('kategori')->distinct()->pluck('kategori');

        // Pastikan variabel $kategoris ikut dikirim ke view
        return view('katalog', compact('products', 'kategoris'));
    }

    /**
     * Menampilkan Detail Produk Spesifik
     */
    public function show($id)
    {
        // Mencari produk berdasarkan ID, jika tidak ada akan memunculkan error 404
        $product = Product::findOrFail($id);
        return view('detail', compact('product'));
    }

    /**
     * Menambahkan Plushie ke Keranjang (Cart)
     */
    public function addToCart(Request $request, $id)
    {
        // 1. Sistem Validasi Logika: Pastikan kuantitas minimal 1
        $request->validate([
            'kuantitas' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($id);

        // Validasi ketersediaan stok
        if ($product->stock < $request->kuantitas) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        // Cek apakah produk sudah ada di keranjang user ini
        $existingCart = Cart::where('user_id', Auth::id())
                            ->where('product_id', $id)
                            ->first();

        if ($existingCart) {
            // Jika sudah ada, tambahkan kuantitasnya
            $existingCart->update([
                'kuantitas' => $existingCart->kuantitas + $request->kuantitas
            ]);
        } else {
            // Jika belum ada, buat record keranjang baru
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'kuantitas' => $request->kuantitas
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Plushie berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menampilkan Halaman Keranjang Belanja
     */
    public function cartIndex()
    {
        // Ambil data keranjang beserta relasi produknya khusus untuk user yang sedang login
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        
        // Hitung subtotal keseluruhan
        $subtotal = 0;
        foreach ($carts as $cart) {
            $subtotal += ($cart->product->harga * $cart->kuantitas);
        }

        return view('cart', compact('carts', 'subtotal'));
    }
    
    
    public function checkoutIndex()
{
    // Ambil data keranjang khusus user yang login
    $carts = Cart::with('product')->where('user_id', Auth::id())->get();
    
    // Hitung subtotal
    $subtotal = 0;
    foreach ($carts as $cart) {
        $subtotal += ($cart->product->harga * $cart->kuantitas);
    }

    // Hitung pajak (misal 10.000 seperti di desainmu)
    $taxes = 10000;
    $total = $subtotal + $taxes;

    return view('checkout', compact('carts', 'subtotal', 'taxes', 'total'));
}

    /**
     * Proses Checkout dan Pembuatan Transaksi
     */
    public function checkout(Request $request)
    {
        // Ambil semua keranjang milik user
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($carts->isEmpty()) {
            return redirect()->route('katalog')->with('error', 'Keranjang belanja kosong!');
        }

        // Hitung total harga
        $totalHarga = 0;
        foreach ($carts as $cart) {
            $totalHarga += ($cart->product->harga * $cart->kuantitas);
            
            // Kurangi stok produk
            $product = $cart->product;
            $product->update([
                'stock' => $product->stock - $cart->kuantitas
            ]);
        }

        // Buat data transaksi baru
        Transaksi::create([
            'user_id' => Auth::id(),
            'invoice' => 'INV-' . strtoupper(Str::random(8)), // Generate nomor invoice acak
            'status' => 'Pending', // Status awal sesuai outline
            'total_harga' => $totalHarga,
        ]);

        // Kosongkan keranjang user setelah checkout berhasil
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('katalog')->with('success', 'Checkout berhasil! Menunggu pembayaran.');
    }


    // ==========================================
    // BAGIAN 2: FITUR SISI ADMIN (CRUD PRODUK)
    // ==========================================

    /**
     * Menyimpan Data Produk Baru dari Form Admin
     */
    public function storeProduct(Request $request)
    {
        // Form Request Validation wajib untuk admin
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            // image disarankan ditambahkan ke tabel products
            // 'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // Logika upload gambar (jika ada)
        // $imagePath = $request->file('image')->store('produk_images', 'public');

        Product::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stock' => $request->stock,
            // 'image' => $imagePath,
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan!');
    }


    public function prosesCheckout(Request $request)
    {
        // 1. Ambil keranjang user saat ini
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();

        // Cegah user yang memaksa masuk url checkout padahal keranjangnya kosong
        if ($carts->isEmpty()) {
            return redirect('/cart');
        }

        // 2. Hitung Ulang Total Harga (Demi Keamanan)
        $subtotal = 0;
        foreach ($carts as $cart) {
            $subtotal += ($cart->product->harga * $cart->kuantitas);
        }
        $taxes = 10000;
        $total_harga = $subtotal + $taxes;

        // 3. Buat Nomor Invoice Unik (Contoh: INV-1715000000-1)
        // Menggabungkan kata INV, waktu saat ini (agar tidak ada yang sama), dan ID user
        $invoice = 'INV-' . time() . '-' . Auth::id();

        // 4. Simpan ke tabel transaksis (Kita simpan ke variabel $transaksiBaru)
        $transaksiBaru = Transaksi::create([
            'user_id' => Auth::id(),
            'invoice' => $invoice,
            'status' => 'pending',
            'total_harga' => $total_harga
        ]);

        // Menyimpan data form dari halaman checkout ke tabel baru
        \App\Models\CheckoutDetail::create([
            'transaksi_id'  => $transaksi->id,
            // Data Pengiriman
            'first_name'    => $request->input('first_name'),
            'last_name'     => $request->input('last_name'),
            'address'       => $request->input('address'),
            'city'          => $request->input('city'),
            'postal_code'   => $request->input('postal_code'),
            
            // Data Kartu
            'card_name'     => $request->input('card_name'),
            'card_number'   => $request->input('card_number'),
            'expiry_date'   => $request->input('expiry_date'),
            'cvv'           => $request->input('cvv'),
        ]);

        // 5. Salin data dari keranjang ke tabel transaksi_details
        foreach ($carts as $cart) {
            TransaksiDetail::create([
                'transaksi_id' => $transaksiBaru->id, // Sambungkan ke ID transaksi yang baru saja dibuat
                'product_id' => $cart->product_id,    // Catat ID bonekanya
                'kuantitas' => $cart->kuantitas,      // Catat jumlah belinya
                'harga_satuan' => $cart->product->harga // Catat harganya
            ]);

            //LOGIKA BARU: Kurangi stok produk!
            $product = Product::find($cart->product_id);
            if ($product) {
                // decrement() adalah fungsi ajaib Laravel untuk mengurangi angka
                $product->decrement('stock', $cart->kuantitas); 
            }
        }

        // 6. Sekarang aman untuk mengosongkan keranjang
        Cart::where('user_id', Auth::id())->delete();

        // 7. Lempar kembali ke katalog beserta pop-up sukses
        return redirect('/katalog')->with('success', 'Admin akan melakukan review dari transaksi anda. Mohon tunggu untuk di approve.');
    }

    //HISTORY PEMBELIAN
    public function history()
    {
        // Mengambil transaksi yang hanya milik user yang sedang login
        $transactions = \App\Models\Transaksi::where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('history', compact('transactions'));
    }
}


