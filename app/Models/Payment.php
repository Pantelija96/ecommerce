<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $casts = [
        'status' => PaymentStatus::class
    ];

    public function order(): BelongsTo{
        return $this->belongsTo(Order::class);
    }
}
