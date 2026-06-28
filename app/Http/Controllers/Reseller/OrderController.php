<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Menampilkan Dasbor Reseller (Katalog Produk yang tersedia).
     */
    public function index()
    {
        // Hanya ambil produk yang stoknya lebih dari 0
        $products = Product::where('stock', '>', 0)->get();
        return view('reseller.dashboard', compact('products'));
    }

    /**
     * Memproses pesanan dari Reseller.
     */
    public function store(Request $request)
    {
        // 1. Filter dan bersihkan data: Ambil hanya produk yang diisi jumlah (quantity > 0)
        $items = collect($request->items)->filter(function ($item) {
            return $item['quantity'] > 0;
        });

        // Jika reseller menekan tombol pesan tapi tidak mengisi angka satupun
        if ($items->isEmpty()) {
            return back()->with('error', 'Pesanan gagal: Anda belum memasukkan jumlah produk yang ingin dibeli.');
        }

        // 2. Gunakan Database Transaction untuk keamanan data tingkat tinggi
        try {
            DB::transaction(function () use ($items) {
                $totalPrice = 0;
                $orderItemsData = [];

                // Hitung total harga dan siapkan data rincian
                foreach ($items as $item) {
                    // Cari produk di database untuk memastikan harga asli (bukan harga dari manipulasi HTML/Hacker)
                    $product = Product::findOrFail($item['product_id']);
                    
                    // Cek validitas stok rill (mencegah over-order jika 2 reseller order bersamaan)
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok untuk {$product->name} tidak mencukupi.");
                    }

                    $subtotal = $product->price * $item['quantity'];
                    $totalPrice += $subtotal;

                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price, // Mengunci harga saat transaksi terjadi
                    ];
                }

                // Simpan ke tabel 'orders' (Kepala Transaksi)
                $order = Order::create([
                    'user_id' => auth()->id(), // Mencatat ID Reseller yang sedang login
                    'status' => 'pending',
                    'total_price' => $totalPrice,
                ]);

                // Simpan rincian ke tabel 'order_items' menggunakan relasi Eloquent
                foreach ($orderItemsData as $data) {
                    $order->items()->create($data);
                }
            });

            // Jika transaksi sukses melewati blok DB::transaction tanpa error
            return redirect()->route('reseller.dashboard')->with('success', 'Berhasil! Pesanan Anda telah masuk dan berstatus PENDING menunggu verifikasi Gudang.');

        } catch (\Exception $e) {
            // Jika ada error (misal stok kurang), batalkan semua penyimpanan (Rollback) dan tampilkan pesan
            return back()->with('error', $e->getMessage());
        }
    }
}