<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseExpense extends Model
{
    protected $fillable = ['warehouse_id', 'category', 'amount', 'date', 'note'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
