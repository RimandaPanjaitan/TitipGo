<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JastipItem extends Model
{
    protected $fillable = [
        'name', 'origin_country', 'origin_city', 'category', 'estimated_shipping',
        'price', 'quota', 'weight', 'po_close_date', 'jastiper_name', 'description', 'image'
    ];
    public function orders() { return $this->hasMany(Order::class); }
}