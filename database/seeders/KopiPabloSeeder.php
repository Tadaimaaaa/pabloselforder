<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KopiPabloSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk mengisi data resmi Toko Kopi Pablo berdasarkan Menu Resmi.
     */
    public function run(): void
    {
        // 1. Seed Pengguna (Admin & Customer)
        DB::table('users')->insertOrIgnore([
            [
                'name' => 'Admin Kopi Pablo',
                'email' => 'admin@kopipablo.id',
                'role' => 'admin',
                'phone' => '081188997766',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mahasiswa Skripsi',
                'email' => 'customer@kopipablo.id',
                'role' => 'customer',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Bersihkan tabel categories & products untuk mengganti dengan Menu Resmi 100%
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_details')->truncate();
        DB::table('orders')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Seed Kategori Resmi Toko Kopi Pablo
        $categories = [
            [
                'name' => 'Kopi Susu Series',
                'slug' => 'kopi-susu-series',
                'icon' => 'fa-coffee',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Flavoured Latte Series',
                'slug' => 'flavoured-latte-series',
                'icon' => 'fa-mug-hot',
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Coldbrew Series',
                'slug' => 'coldbrew-series',
                'icon' => 'fa-wine-bottle',
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pabloricano Series',
                'slug' => 'pabloricano-series',
                'icon' => 'fa-glass-water',
                'order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Non Coffee Series',
                'slug' => 'non-coffee-series',
                'icon' => 'fa-cubes',
                'order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sebotol Pablo (1 Liter)',
                'slug' => 'sebotol-pablo',
                'icon' => 'fa-bottle-water',
                'order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Adds On (Topping)',
                'slug' => 'adds-on',
                'icon' => 'fa-plus-circle',
                'order' => 7,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);

        // Ambil ID Kategori
        $catIds = DB::table('categories')->pluck('id', 'slug');

        // 3. Seed Daftar Menu Resmi Toko Kopi Pablo
        $products = [
            // --- KOPI SUSU SERIES ---
            [
                'category_id' => $catIds['kopi-susu-series'],
                'name' => 'Kopi Susu Pablo (Reguler)',
                'slug' => 'kopi-susu-pablo-reguler',
                'description' => 'Iced palm sugar latte khas Kopi Pablo (Reguler: 10K / Large: 16K)',
                'price' => 10000,
                'image' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['kopi-susu-series'],
                'name' => 'Kopi Susu Pablo (Large)',
                'slug' => 'kopi-susu-pablo-large',
                'description' => 'Iced palm sugar latte ukuran Large (16K)',
                'price' => 16000,
                'image' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['kopi-susu-series'],
                'name' => 'Kopi Susu Strong (Reguler)',
                'slug' => 'kopi-susu-strong-reguler',
                'description' => 'Iced palm sugar latte with double espresso (Reguler: 13K / Large: 19K)',
                'price' => 13000,
                'image' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['kopi-susu-series'],
                'name' => 'Kopi Susu Strong (Large)',
                'slug' => 'kopi-susu-strong-large',
                'description' => 'Iced palm sugar latte with double espresso ukuran Large (19K)',
                'price' => 19000,
                'image' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['kopi-susu-series'],
                'name' => 'Kopi Susu Coconut',
                'slug' => 'kopi-susu-coconut',
                'description' => 'Iced latte with coconut milk (Reguler: 12K / Large: 18K)',
                'price' => 12000,
                'image' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['kopi-susu-series'],
                'name' => 'Kopi Susu Strawberry',
                'slug' => 'kopi-susu-strawberry',
                'description' => 'Iced latte with creamy strawberry milk (Reguler: 12K / Large: 18K)',
                'price' => 12000,
                'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['kopi-susu-series'],
                'name' => 'Kopi Susu Less Sugar',
                'slug' => 'kopi-susu-less-sugar',
                'description' => 'Iced palm sugar latte with less sugar (Reguler: 10K / Large: 16K)',
                'price' => 10000,
                'image' => 'https://images.unsplash.com/photo-1570968915860-54d5c301fa9f?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],

            // --- FLAVOURED LATTE SERIES ---
            [
                'category_id' => $catIds['flavoured-latte-series'],
                'name' => 'Salted Caramel Latte',
                'slug' => 'salted-caramel-latte',
                'description' => 'Creamy latte with salted caramel sauce (Reguler: 12K / Large: 18K)',
                'price' => 12000,
                'image' => 'https://images.unsplash.com/photo-1599398054066-846f28917f38?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['flavoured-latte-series'],
                'name' => 'Butterscotch Sea Salt Latte',
                'slug' => 'butterscotch-sea-salt-latte',
                'description' => 'Butterscotch flavoured latte with sea salt cream and cookie crumble on top (Reguler: 15K / Large: 21K)',
                'price' => 15000,
                'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['flavoured-latte-series'],
                'name' => 'Cheese Cream Latte',
                'slug' => 'cheese-cream-latte',
                'description' => 'Cheese flavoured latte with cream cheese on top (Reguler: 15K / Large: 21K)',
                'price' => 15000,
                'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['flavoured-latte-series'],
                'name' => 'Hazelnut Latte',
                'slug' => 'hazelnut-latte',
                'description' => 'Iced latte with hazelnut syrup (Reguler: 12K / Large: 18K)',
                'price' => 12000,
                'image' => 'https://images.unsplash.com/photo-1588195538326-c5b1e9f80a1b?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],

            // --- COLDBREW SERIES ---
            [
                'category_id' => $catIds['coldbrew-series'],
                'name' => 'Coffee Cranberry / Kopi Hitam Pablo',
                'slug' => 'coffee-cranberry',
                'description' => 'Coldbrew with cranberry juice (Reguler: 10K / Large: 16K)',
                'price' => 10000,
                'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['coldbrew-series'],
                'name' => 'Coldbrew Cream',
                'slug' => 'coldbrew-cream',
                'description' => 'Coldbrew with Butterscotch cream (Reguler: 15K / Large: 21K)',
                'price' => 15000,
                'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
                'is_available' => true,
            ],

            // --- PABLORICANO SERIES ---
            [
                'category_id' => $catIds['pabloricano-series'],
                'name' => 'Pabloricano Original',
                'slug' => 'pabloricano-original',
                'description' => '100% Arabica Americano, no sugar (Reguler: 10K / Large: 16K)',
                'price' => 10000,
                'image' => 'https://images.unsplash.com/photo-1551030173-122aabc4489c?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['pabloricano-series'],
                'name' => 'Pabloricano Peach',
                'slug' => 'pabloricano-peach',
                'description' => 'Peach Flavoured 100% Arabica Americano (Reguler: 12K / Large: 18K)',
                'price' => 12000,
                'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['pabloricano-series'],
                'name' => 'Pabloricano Berry Mint',
                'slug' => 'pabloricano-berry-mint',
                'description' => 'Berry and Mint flavoured 100% Arabica Americano (Reguler: 12K / Large: 18K)',
                'price' => 12000,
                'image' => 'https://images.unsplash.com/photo-1556881286-fc6915169721?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],

            // --- NON COFFEE SERIES ---
            [
                'category_id' => $catIds['non-coffee-series'],
                'name' => 'Strawberry Milk',
                'slug' => 'strawberry-milk',
                'description' => 'Fresh strawberry jam with smooth, creamy milk (Reguler: 12K / Large: 18K)',
                'price' => 12000,
                'image' => 'https://images.unsplash.com/photo-1553177595-49275bad88bd?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['non-coffee-series'],
                'name' => 'Orange Vanilla',
                'slug' => 'orange-vanilla',
                'description' => 'Fresh orange juice with creamy vanilla milk (Reguler: 10K / Large: 16K)',
                'price' => 10000,
                'image' => 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['non-coffee-series'],
                'name' => 'Choco Hazelnut',
                'slug' => 'choco-hazelnut',
                'description' => 'Creamy chocolate milk with hazelnut syrup (Reguler: 12K / Large: 18K)',
                'price' => 12000,
                'image' => 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['non-coffee-series'],
                'name' => 'Choco Cheese Cream',
                'slug' => 'choco-cheese-cream',
                'description' => 'Chocolate milk with cheese cream on top (Reguler: 15K / Large: 21K)',
                'price' => 15000,
                'image' => 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['non-coffee-series'],
                'name' => 'Luo Han Kuo Honey Lime',
                'slug' => 'luo-han-kuo-honey-lime',
                'description' => 'Herbal drink with Luo Han Kuo, fresh lime, and natural honey (Reguler: 12K / Large: 18K)',
                'price' => 12000,
                'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
                'is_available' => true,
            ],

            // --- SEBOTOL PABLO (BOTOL 1 LITER) ---
            [
                'category_id' => $catIds['sebotol-pablo'],
                'name' => 'Sebotol Kopi Susu Pablo (1 Liter)',
                'slug' => 'sebotol-kopi-susu-pablo',
                'description' => 'Kopi Susu Pablo kemasan botol 1 Liter siap saji (70K)',
                'price' => 70000,
                'image' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['sebotol-pablo'],
                'name' => 'Sebotol Kopi Susu Less Sugar (1 Liter)',
                'slug' => 'sebotol-kopi-susu-less-sugar',
                'description' => 'Kopi Susu Less Sugar kemasan botol 1 Liter (75K)',
                'price' => 75000,
                'image' => 'https://images.unsplash.com/photo-1570968915860-54d5c301fa9f?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['sebotol-pablo'],
                'name' => 'Sebotol Choco Hazelnut (1 Liter)',
                'slug' => 'sebotol-choco-hazelnut',
                'description' => 'Choco Hazelnut creamy kemasan botol 1 Liter (75K)',
                'price' => 75000,
                'image' => 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['sebotol-pablo'],
                'name' => 'Sebotol Orange Vanilla (1 Liter)',
                'slug' => 'sebotol-orange-vanilla',
                'description' => 'Orange Vanilla creamy kemasan botol 1 Liter (75K)',
                'price' => 75000,
                'image' => 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['sebotol-pablo'],
                'name' => 'Sebotol Kopi Susu Strong (1 Liter)',
                'slug' => 'sebotol-kopi-susu-strong',
                'description' => 'Kopi Susu Strong Double Espresso kemasan botol 1 Liter (85K)',
                'price' => 85000,
                'image' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['sebotol-pablo'],
                'name' => 'Sebotol Kopi Susu Coconut (1 Liter)',
                'slug' => 'sebotol-kopi-susu-coconut',
                'description' => 'Kopi Susu Coconut kemasan botol 1 Liter (85K)',
                'price' => 85000,
                'image' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['sebotol-pablo'],
                'name' => 'Sebotol Kopi Susu Strawberry (1 Liter)',
                'slug' => 'sebotol-kopi-susu-strawberry',
                'description' => 'Kopi Susu Strawberry kemasan botol 1 Liter (85K)',
                'price' => 85000,
                'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['sebotol-pablo'],
                'name' => 'Sebotol Salted Caramel Latte (1 Liter)',
                'slug' => 'sebotol-salted-caramel-latte',
                'description' => 'Salted Caramel Latte kemasan botol 1 Liter (85K)',
                'price' => 85000,
                'image' => 'https://images.unsplash.com/photo-1599398054066-846f28917f38?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['sebotol-pablo'],
                'name' => 'Sebotol Strawberry Milk (1 Liter)',
                'slug' => 'sebotol-strawberry-milk',
                'description' => 'Strawberry Milk kemasan botol 1 Liter (85K)',
                'price' => 85000,
                'image' => 'https://images.unsplash.com/photo-1553177595-49275bad88bd?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],

            // --- ADDS ON (TOPPING TAMBAHAN) ---
            [
                'category_id' => $catIds['adds-on'],
                'name' => 'Extra Shot Espresso',
                'slug' => 'extra-shot-espresso',
                'description' => 'Tambahan 1 shot espresso murni (3K)',
                'price' => 3000,
                'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['adds-on'],
                'name' => 'Sea Salt Cream',
                'slug' => 'sea-salt-cream',
                'description' => 'Tambahan topping cream sea salt gurih (3K)',
                'price' => 3000,
                'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['adds-on'],
                'name' => 'Cheese Cream',
                'slug' => 'cheese-cream',
                'description' => 'Tambahan topping cream cheese lembut (3K)',
                'price' => 3000,
                'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['adds-on'],
                'name' => 'Extra Caramel Crumble',
                'slug' => 'extra-caramel-crumble',
                'description' => 'Tambahan remahan cookie caramel renyah (3K)',
                'price' => 3000,
                'image' => 'https://images.unsplash.com/photo-1599398054066-846f28917f38?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_available' => true,
            ],
        ];

        foreach ($products as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('products')->insert($products);
    }
}
