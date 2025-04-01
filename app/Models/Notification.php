<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'message',
        'user_id',
        'order_id',
        'read_at',
    ];
    protected $casts = ['read_at' => 'datetime'];
}
