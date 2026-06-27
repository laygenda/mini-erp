<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Mengizinkan kolom ini diisi secara massal
    protected $fillable = ['name', 'description', 'price', 'stock'];
    // Relasi: satu produk dapat dimiliki oleh banyak detail pesanan
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
