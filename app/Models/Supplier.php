<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name', 'phone', 'address', 'total_orders', 'debt', 'total_paid', 'balance','warehouse_id'
    ];

    public function supplyOrders()
    {
        return $this->hasMany(SupplyOrder::class);
    }

    public function payments()
    {
        return $this->hasMany(SupplierPayment::class);
    }

    

}
