<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'status', 'total_price'];
    // relasi kebalikan: Satu order wajib dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relasi: satu order dapat memiliki banyak item produk
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
