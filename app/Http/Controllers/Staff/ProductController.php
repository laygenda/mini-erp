<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data produk dari database
        $products = Product::all();
        // Mengirim data tersebut ke file tampilan (View) bernama 'index'
        return view('staff.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (Super Penting untuk Keamanan)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ], [
            // Kustomisasi pesan error ke bahasa Indonesia
            'name.required' => 'Nama produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.'
        ]);

        // 2. Simpan ke Database (menggunakan data yang sudah tervalidasi)
        Product::create($validatedData);

        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('staff.dashboard')->with('success', 'Produk baru berhasil ditambahkan ke dalam inventaris!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Variabel $product sudah otomatis berisi data dari database berdasarkan ID di URL
        return view('staff.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validasi Input (Sama seperti saat Create)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);

        // 2. Perbarui data di Database
        $product->update($validatedData);

        // 3. Kembalikan ke halaman daftar dengan pesan sukses
        return redirect()->route('staff.dashboard')->with('success', 'Data produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Hapus baris data tersebut
        $product->delete();

        // Kembalikan ke halaman daftar
        return redirect()->route('staff.dashboard')->with('success', 'Produk berhasil dihapus dari sistem.');
    }
}
