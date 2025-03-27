<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = ['warehouse_id', 'image', 'title'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
