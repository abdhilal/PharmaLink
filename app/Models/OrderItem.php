<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'medicine_id', 'quantity', 'price_per_unit', 'subtotal'];

    // علاقة Many-to-One مع الطلبية
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // علاقة Many-to-One مع الدواء (يجب إضافتها)
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}