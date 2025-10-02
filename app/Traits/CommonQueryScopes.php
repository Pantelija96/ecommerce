<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait CommonQueryScopes
{
    public function scopeFilterByPrice(Builder $query, $min = null, $max = null)
    {
        if ($min !== null) $query->where('price', '>=', $min);
        if ($max !== null) $query->where('price', '<=', $max);
        return $query;
    }

    public function scopeSearchByName(Builder $query, $search = null)
    {
        if ($search) $query->where('name', 'like', "%{$search}%");
        return $query;
    }
}
