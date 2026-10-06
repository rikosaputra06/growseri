<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $product1 = Product::create([
            'name' => 'Beras Pandan Wangi Premium',
            'slug' => 'beras-pandan-wangi-premium',
            'description' => 'Beras kualitas premium dengan aroma pandan yang wangi alami.',
            'has_variants' => true,
        ]);

        ProductVariant::create(['product_id' => $product1->id, 'variant_name' => '5 Kg', 'price' => 75000, 'stock' => 100]);
        ProductVariant::create(['product_id' => $product1->id, 'variant_name' => '10 Kg', 'price' => 145000, 'stock' => 50]);

        $product2 = Product::create([
            'name' => 'Minyak Goreng Bimoli',
            'slug' => 'minyak-goreng-bimoli',
            'description' => 'Minyak goreng kelapa sawit bening dan jernih.',
            'has_variants' => true,
        ]);

        ProductVariant::create(['product_id' => $product2->id, 'variant_name' => '1 Liter', 'price' => 18000, 'stock' => 200]);
        ProductVariant::create(['product_id' => $product2->id, 'variant_name' => '2 Liter', 'price' => 35000, 'stock' => 100]);

        Product::create([
            'name' => 'Gula Pasir Gulaku',
            'slug' => 'gula-pasir-gulaku',
            'description' => 'Gula tebu murni, manis dan bersih. Kemasan 1 Kg.',
            'has_variants' => false,
            'base_price' => 16500,
        ]);
        
        Product::create([
            'name' => 'Indomie Goreng Original',
            'slug' => 'indomie-goreng-original',
            'description' => 'Mie instan kebanggaan Indonesia.',
            'has_variants' => false,
            'base_price' => 3000,
        ]);

        \App\Models\StoreSetting::create([
            'store_name' => 'Growseri',
            'slogan' => 'Kebutuhan Harian Lebih Dekat dan Cepat!',
            'address' => 'Jl. Merdeka No. 45, Jakarta Pusat',
            'currency' => 'IDR',
            'is_open' => true,
            'store_whatsapp' => '08123456789',
            'store_latitude' => -6.200000,
            'store_longitude' => 106.816666, // Jakarta coordinates as default store location
            'shipping_rate_type' => 'per_km',
            'shipping_flat_rate' => 15000,
            'shipping_per_km_rate' => 3000, // Rp 3.000 per km
            'max_delivery_distance_km' => 20,
            'delivery_label' => 'Jadwal Pengiriman (Waktu Antar)',
            'delivery_time_mode' => 'time_slot',
            'delivery_time_slots' => [
                ['start' => '07:00', 'end' => '10:00'],
                ['start' => '10:00', 'end' => '12:00']
            ],
            'delivery_active_days' => [1, 2, 3, 4, 5, 6], // Mon to Sat
        ]);
    }
}
