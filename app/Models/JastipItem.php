<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JastipItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'origin_city',
        'price',
        'quota',
        'image',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}