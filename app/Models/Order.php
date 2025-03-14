<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $fillable = ['pharmacy_id', 'warehouse_id', 'status', 'total_price'];

  
  // علاقة Many-to-One مع الصيدلية (User)
  public function pharmacy()
  {
    return $this->belongsTo(User::class, 'pharmacy_id');
  }

  // علاقة Many-to-One مع المستودع
  public function warehouse()
  {
    return $this->belongsTo(Warehouse::class);
  }

  // علاقة One-to-Many مع تفاصيل الطلبية
  public function items()
  {
    return $this->hasMany(OrderItem::class);
  }

}
