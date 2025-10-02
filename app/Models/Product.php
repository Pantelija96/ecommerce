<?php

namespace App\Models;

use App\Traits\CommonQueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, CommonQueryScopes;

    protected $fillable = [
        "name",
        "description",
        "price",
        "stock",
        "category_id"
    ];

    public function category(): BelongsTo{
        return $this->belongsTo(Category::class);
    }

    public function carts(): HasMany{
        return $this->hasMany(Cart::class);
    }

    public function orders(): HasMany{
        return $this->hasMany(Order::class);
    }

    public function scopeFilterCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }
}
