<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    public function category(): BelongsTo{
        return $this->belongsTo(Category::class);
    }

    public function carts(): HasMany{
        return $this->hasMany(Cart::class);
    }

    public function orders(): HasMany{
        return $this->hasMany(Order::class);
    }
}
