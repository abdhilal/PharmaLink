<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrgentOrder extends Model
{
    protected $fillable = ['pharmacy_id', 'note', 'status','warehouse_id'];

    public function items()
    {
        return $this->hasMany(UrgentOrderItiem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'pharmacy_id');
    }



}
