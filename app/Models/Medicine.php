<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'scientific_name',
        'category_id',
        'manufacturer_id',
        'price',
        'quantity',
        'min_quantity',
        'expiry_date',
        'description'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'price' => 'decimal:2'
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
