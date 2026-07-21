<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_phone',
        'table_number',
        'notes',
        'status',
        'total_amount',
        'payment_method',
        'pickup_time',
        'reschedule_status',
        'reschedule_notes',
    ];

    protected $casts = [
        'total_amount' => 'integer',
    ];

    /**
     * Relasi ke Item pesanan.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Alias atribut total ke total_amount
     */
    public function getTotalAttribute(): int
    {
        return (int) $this->total_amount;
    }

    /**
     * Format total harga ke format Rupiah.
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /**
     * Helper persentase progress untuk Progress Step Bar di UI Customer.
     */
    public function getProgressPercentageAttribute(): int
    {
        return match (strtolower($this->status)) {
            'pending', 'menunggu' => 25,
            'processing', 'diproses' => 50,
            'ready', 'siap_diambil' => 75,
            'completed', 'selesai' => 100,
            default => 0,
        };
    }

    /**
     * Helper teks status ramah pengguna dalam bahasa Indonesia.
     */
    public function getStatusLabelAttribute(): string
    {
        return match (strtolower($this->status)) {
            'pending', 'menunggu' => 'Menunggu Konfirmasi Kasir',
            'processing', 'diproses' => 'Sedang Diproses Barista',
            'ready', 'siap_diambil' => 'Siap Diambil di Counter',
            'completed', 'selesai' => 'Pesanan Selesai',
            'cancelled', 'dibatalkan' => 'Pesanan Dibatalkan',
            default => ucfirst($this->status),
        };
    }
}
