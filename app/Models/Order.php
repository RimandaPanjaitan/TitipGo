<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jastip_item_id',
        'quantity',
        'total_price',
        'notes',
        'status',
    ];

    public function jastipItem(): BelongsTo
    {
        return $this->belongsTo(JastipItem::class);
    }
}