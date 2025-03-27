<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrgentOrderItiem extends Model
{
    protected $fillable = ['urgent_order_id', 'name', 'quantity'];
    
    public function order()
    {
        return $this->belongsTo(UrgentOrder::class);
    }
}
