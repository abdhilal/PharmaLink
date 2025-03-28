<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MedicineExpiry extends Model
{
    protected $table = 'medicine_expiry';
    
    protected $fillable = [
        'medicine_id',
        'expiry_date',
        'quantity',
        'batch_number'
    ];

    protected $dates = [
        'expiry_date'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function isExpiring($days = 30)
    {
        return $this->expiry_date->diffInDays(Carbon::now()) <= $days;
    }

    public function isExpired()
    {
        return $this->expiry_date->isPast();
    }
}
