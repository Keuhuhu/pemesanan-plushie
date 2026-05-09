<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlushController; // Panggil PlushController
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/register');



// ==========================================
// ROUTE UNTUK USER (Customer)
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/katalog', [PlushController::class, 'index'])->name('katalog.index');
    Route::get('/katalog/{id}', [PlushController::class, 'show'])->name('katalog.show');
    
    Route::get('/cart', [PlushController::class, 'cartIndex'])->name('cart.index');
    Route::post('/cart/add/{id}', [PlushController::class, 'addToCart'])->name('cart.add');
    
    Route::get('/checkout', [PlushController::class, 'checkoutIndex'])->name('checkout.index');
    Route::post('/checkout', [PlushController::class, 'checkoutIndex'])->name('checkout.index');
    // Rute POST untuk memproses form checkout
    Route::post('/checkout/proses', [App\Http\Controllers\PlushController::class, 'prosesCheckout'])->name('transaksis.store');
    Route::get('/history', [App\Http\Controllers\PlushController::class, 'history'])->name('user.history');
});

// ==========================================
// ROUTE UNTUK ADMIN
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/approved', [App\Http\Controllers\AdminController::class, 'approved'])->name('admin.approved');
    Route::patch('/admin/approve/{id}', [App\Http\Controllers\AdminController::class, 'approve'])->name('admin.approve');

    Route::get('/admin/all-transactions', [App\Http\Controllers\AdminController::class, 'all'])->name('admin.all');
    // Rute untuk menampilkan daftar user
    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');

    // Rute untuk menghapus user (Perhatikan penggunaan metode DELETE)
    Route::delete('/admin/users/{id}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    // Rute untuk menampilkan form tambah user
    Route::get('/admin/users/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('admin.users.create');
    // Rute untuk memproses data user baru
    Route::post('/admin/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.users.store');

    // Rute untuk menampilkan form edit user
    Route::get('/admin/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('admin.users.edit');
    // Rute untuk memproses update data user
    Route::put('/admin/users/{id}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::get('/admin/export', [App\Http\Controllers\AdminController::class, 'export'])->name('admin.export');
    Route::get('/admin/export/pdf', [App\Http\Controllers\AdminController::class, 'exportPdf'])->name('admin.export.pdf');
    Route::get('/admin/export/excel', [App\Http\Controllers\AdminController::class, 'exportExcel'])->name('admin.export.excel');

    // Rute Manajemen Produk (Plushie)
Route::get('/admin/products', [App\Http\Controllers\AdminController::class, 'products'])->name('admin.products');
Route::get('/admin/products/create', [App\Http\Controllers\AdminController::class, 'createProduct'])->name('admin.products.create');
Route::post('/admin/products', [App\Http\Controllers\AdminController::class, 'storeProduct'])->name('admin.products.store');
Route::delete('/admin/products/{id}', [App\Http\Controllers\AdminController::class, 'destroyProduct'])->name('admin.products.destroy');

// Rute untuk menampilkan form edit
Route::get('/admin/products/{id}/edit', [\App\Http\Controllers\AdminController::class, 'editProduct'])->name('admin.products.edit');

// Rute untuk menyimpan perubahan data ke database (menggunakan PUT)
Route::put('/admin/products/{id}', [\App\Http\Controllers\AdminController::class, 'updateProduct'])->name('admin.products.update');
});

// Route Profil Bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/detail', function () {
    return view('detail');
});

require __DIR__.'/auth.php';