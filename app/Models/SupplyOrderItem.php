<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyOrderItem extends Model
{
    protected $fillable = [
        'supply_order_id', 'name', 'company_name', 'quantity', 'unit_price',
        'discount_percentage', 'discount_amount', 'subtotal',  'medicine_id',
    ];

    public function supplyOrder()
    {
        return $this->belongsTo(SupplyOrder::class);
    }
    public function medicine()
{
    return $this->belongsTo(Medicine::class);
}
}
