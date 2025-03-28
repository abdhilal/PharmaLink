<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    protected $fillable = [
        'user_id',
        'points',
        'total_spent',
        'tier'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateTier()
    {
        if ($this->points >= 1000) {
            $this->tier = 'gold';
        } elseif ($this->points >= 500) {
            $this->tier = 'silver';
        } else {
            $this->tier = 'bronze';
        }
        $this->save();
    }

    public function addPoints($amount)
    {
        $this->points += floor($amount * 0.1); // 10% of spent amount as points
        $this->total_spent += $amount;
        $this->calculateTier();
    }
}
