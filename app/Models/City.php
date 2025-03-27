<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'user_id', 'name', 'latitude', 'longitude',
        'range_east', 'range_west', 'range_north', 'range_south'];
    // public function warehouses()
    // {
    //     return $this->belongsToMany(Warehouse::class, 'warehouse_cities');
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
