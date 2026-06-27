<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat Akun Eksekutif (Owner)
        User::create([
            'name' => 'Owner Konking Agro',
            'email' => 'owner@konking.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
        ]);

        // 2. Membuat Akun Operasional (Staf Gudang)
        User::create([
            'name' => 'Staf Gudang',
            'email' => 'staf@konking.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        // 3. Membuat Akun Reseller/Distributor
        User::create([
            'name' => 'Reseller Surabaya',
            'email' => 'reseller@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'reseller',
        ]);

        // 4. Memasukkan Master Data Produk (Katalog Awal)
        Product::create([
            'name' => 'KONGBANA Rasa Cokelat Lumer',
            'description' => 'Snack pisang premium dengan balutan cokelat tebal.',
            'price' => 15000,
            'stock' => 100,
        ]);

        Product::create([
            'name' => 'KONGBANA Keju Gurih',
            'description' => 'Snack pisang dengan taburan keju melimpah.',
            'price' => 16000,
            'stock' => 50,
        ]);
    }
}