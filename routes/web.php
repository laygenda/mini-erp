<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\ProductController;
use App\Http\Controllers\Reseller\OrderController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| SMART REDIRECT LOGIN
|--------------------------------------------------------------------------
| Saat user berhasil login, arahkan mereka ke dashboard masing-masing
*/
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    
    if ($role === 'owner') return redirect()->route('owner.dashboard');
    if ($role === 'staff') return redirect()->route('staff.dashboard');
    if ($role === 'reseller') return redirect()->route('reseller.dashboard');
    
    return abort(403);
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| RUTE KHUSUS OWNER (SUPER ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', function () {
        return '<h1>Selamat Datang di Dasbor Owner</h1><p>Halaman Laporan Keuangan akan ada di sini.</p>';
    })->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| RUTE KHUSUS STAF GUDANG (OPERASIONAL)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    
    // Rute utama saat staf berhasil login (langsung melihat daftar produk)
    Route::get('/dashboard', [ProductController::class, 'index'])->name('dashboard');
    
    // Rute untuk fitur CRUD produk lainnya (tambah, edit, hapus)
    Route::resource('products', ProductController::class);

    // RUTE BARU: Manajemen Pesanan
    Route::get('/orders', [\App\Http\Controllers\Staff\OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/approve', [\App\Http\Controllers\Staff\OrderController::class, 'approve'])->name('orders.approve');
});


/*
|--------------------------------------------------------------------------
| RUTE KHUSUS RESELLER / DISTRIBUTOR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:reseller'])->prefix('reseller')->name('reseller.')->group(function () {
    
    // Halaman Dasbor (Katalog Produk)
    Route::get('/dashboard', [OrderController::class, 'index'])->name('dashboard');
    
    // Rute untuk memproses pengiriman formulir pesanan (Checkout)
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout');
});


/*
|--------------------------------------------------------------------------
| RUTE PROFIL BAWAAN BREEZE (Bisa diakses semua role yang login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';