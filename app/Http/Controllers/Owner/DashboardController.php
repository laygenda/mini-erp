<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menghitung KPI dan merender dasbor eksekutif.
     */
    public function index()
    {
        // 1. Hitung Total Omzet (Hanya dari pesanan yang 'diproses' atau 'selesai')
        $totalRevenue = Order::whereIn('status', ['diproses', 'selesai'])->sum('total_price');

        // 2. Hitung Pesanan yang Menunggu Validasi Gudang
        $pendingOrdersCount = Order::where('status', 'pending')->count();

        // 3. Peringatan Stok Menipis (Ambil produk dengan stok di bawah 20)
        $lowStockProducts = Product::where('stock', '<', 20)->get();

        // 4. Siapkan Data untuk Grafik (Chart.js)
        // Kita ambil nama produk dan sisa stoknya untuk divisualisasikan
        $products = Product::all();
        $chartLabels = $products->pluck('name'); // Mengambil array nama produk
        $chartData = $products->pluck('stock');  // Mengambil array jumlah stok

        return view('owner.dashboard', compact(
            'totalRevenue', 
            'pendingOrdersCount', 
            'lowStockProducts',
            'chartLabels',
            'chartData'
        ));
    }
}