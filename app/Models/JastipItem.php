<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JastipItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'origin_city',
        'category',
        'estimated_arrival',
        'price',
        'quota',
        'weight_grams',
        'is_open',
        'image',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}