<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan masuk dari Reseller.
     */
    public function index()
    {
        // Menggunakan with('user') untuk Eager Loading (Optimasi Query).
        // Mengurutkan pesanan terbaru di paling atas.
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        
        return view('staff.orders.index', compact('orders'));
    }

    /**
     * Memproses pesanan: Mengubah status dan memotong stok barang.
     */
    public function approve(Order $order)
    {
        // 1. Proteksi Ganda: Pastikan pesanan yang diproses adalah yang masih pending
        if ($order->status !== 'pending') {
            return back()->with('error', 'Aksi ditolak: Pesanan ini sudah diproses sebelumnya.');
        }

        // 2. Gunakan Database Transaction untuk keamanan potong stok
        try {
            DB::transaction(function () use ($order) {
                
                // Ubah status nota menjadi 'diproses'
                $order->update(['status' => 'diproses']);

                // Tarik data rincian barang di dalam nota tersebut
                // Relasi 'items' dan 'product' ini mengambil dari Model yang kita buat di Tahap 2
                foreach ($order->items as $item) {
                    $product = $item->product;

                    // Validasi stok final tepat sebelum dipotong
                    if ($product->stock < $item->quantity) {
                        throw new \Exception("Gagal: Stok {$product->name} tersisa {$product->stock} unit, sedangkan pesanan membutuhkan {$item->quantity} unit.");
                    }

                    // Fungsi otomatis Laravel untuk mengurangi angka di database
                    $product->decrement('stock', $item->quantity);
                }
            });

            return back()->with('success', 'Nota Pesanan #'.$order->id.' berhasil disetujui! Stok gudang telah dipotong otomatis.');

        } catch (\Exception $e) {
            // Jika stok tidak cukup, batalkan perubahan status
            return back()->with('error', $e->getMessage());
        }
    }
}