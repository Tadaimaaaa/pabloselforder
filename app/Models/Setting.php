<?php

namespace App\Models;

class Setting
{
    /**
     * Pengaturan toko default Kopi Pablo tanpa membutuhkan tabel database tambahan,
     * sehingga struktur database tetap bersih dengan 5 tabel utama saja.
     */
    protected static array $defaults = [
        'store_name' => 'Kopi Pablo',
        'tagline' => 'Crafted Coffee, Self-Service Ordering Experience',
        'address' => 'Outlet 1: Jl. Batang Kasang, Alai Parak Kopi | Outlet 2: Jl. Kelenteng, Kp. Pondok, Kota Padang',
        'hours' => 'Padang Baru: 06:30 - 21:00 WIB | Pondok: Buka 24 Jam',
        'phone' => '0811-8899-7766',
        'whatsapp' => '6281188997766',
        'promo' => 'Nongkrong hemat & pesan mandiri tanpa antre di 2 outlet Kopi Pablo Padang!',
    ];

    /**
     * Mengambil nilai pengaturan berdasarkan key.
     */
    public static function get(string $key, $default = null)
    {
        return static::$defaults[$key] ?? $default;
    }

    /**
     * Memperbarui nilai pengaturan sementara dalam memori.
     */
    public static function set(string $key, $value): void
    {
        static::$defaults[$key] = $value;
    }
}
