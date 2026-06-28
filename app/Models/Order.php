<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Mengizinkan kolom ini diisi secara massal
    protected $fillable = ['user_id', 'status', 'total_price'];

    /**
     * Relasi Kebalikan: Satu Order ini WAJIB milik Satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Satu Order bisa memiliki Banyak Item Produk
     * (FUNGSI INI YANG SEBELUMNYA HILANG/TIDAK TERBACA)
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}