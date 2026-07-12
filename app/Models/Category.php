<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Relasi ke Produk (Satu kategori memiliki banyak produk menu).
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id')->orderBy('name');
    }
}
