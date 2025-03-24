<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'amount',
        'date',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * العلاقة مع الموظف
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

 
}
