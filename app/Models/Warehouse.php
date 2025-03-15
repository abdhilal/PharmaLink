<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['user_id', 'phone', 'address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'warehouse_cities');
    }

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
    public function accounts()
{
    return $this->hasMany(Account::class, 'warehouse_id');
}
}
