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

    public function city()
    {
        return $this->hasOne(City::class);
    }

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
    public function accounts()
    {
        return $this->hasMany(Account::class, 'warehouse_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'warehouse_id', 'id');
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'warehouse_id');
    }


    // العلاقة مع الموظفين

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

}
