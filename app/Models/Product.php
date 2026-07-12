<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'is_available',
        'is_featured',
        'total_sold',
    ];

    protected $casts = [
        'price' => 'integer',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'total_sold' => 'integer',
    ];

    /**
     * Relasi ke Kategori produk.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Alias favorit untuk kompatibilitas tampilan.
     */
    public function getIsFavoriteAttribute(): bool
    {
        return (bool) $this->is_featured;
    }

    /**
     * Helper format harga Rupiah (misal: Rp 28.000)
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
