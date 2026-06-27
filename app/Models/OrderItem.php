<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];
    //relasi : satu detail pesanan wajib dimiliki oleh satu order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    //relasi : satu detail pesanan wajib dimiliki oleh satu produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
