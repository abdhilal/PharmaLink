<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'warehouse_id',
        'company_id',
        'name',
        'price',
        'quantity',
        'date',
        'barcode',
        'offer',
        'discount_percentage', // نسبة الحسم
        'profit_percentage', // نسبة الربح
        'selling_price', // سعر البيع
        'is_hidden'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function supplyOrderItems()
{
    return $this->hasMany(SupplyOrderItem::class);
}

}
