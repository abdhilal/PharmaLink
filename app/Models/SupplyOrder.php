<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyOrder extends Model
{
    protected $fillable = [
        'supplier_id', 'total_quantity', 'total_cost_before_discount',
        'total_cost_after_discount', 'discount_type', 'discount_percentage',
        'discount_amount', 'order_date', 'note'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(SupplyOrderItem::class);
    }
}
