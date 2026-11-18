<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'jastip_item_id', 'order_code', 'buyer_name', 'buyer_email', 'buyer_phone',
        'shipping_address', 'quantity', 'subtotal', 'service_fee', 'total_price',
        'payment_method', 'payment_status', 'step_status', 'status_label',
        'courier_vehicle', 'courier_plate'
    ];
    public function item() { return $this->belongsTo(JastipItem::class, 'jastip_item_id'); }
}