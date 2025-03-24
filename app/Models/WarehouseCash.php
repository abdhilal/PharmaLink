<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseCash extends Model
{
    protected $fillable = [
        'warehouse_id', 'transaction_type', 'amount', 'description',
        'date', 'related_id', 'related_type'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }


    // العلاقة العكسية مع الكيانات المرتبطة (Polymorphic)

    public function related()
    {
        return $this->morphTo();
    }
}
