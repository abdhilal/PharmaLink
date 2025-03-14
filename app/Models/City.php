<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name'];

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_cities');
    }
}
