<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'name',
        'phone',
        'position',
        'salary',
        'date',
        'status',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'hire_date' => 'date',
        'status' => 'string',
    ];


    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }


    public function payments()
    {
        return $this->hasMany(EmployeePayment::class);
    }

    /**
     * حساب إجمالي الرواتب المدفوعة
     */
    public function getTotalPaidAttribute()
    {
        return $this->payments->sum('amount');
    }

    /**
     * حساب الرواتب المستحقة (اختياري، بناءً على الراتب الأساسي)
     */
    public function getDueSalaryAttribute()
    {
        // يمكن تخصيص هذا بناءً على منطق الرواتب (مثل الراتب الشهري)
        return $this->salary - $this->total_paid;
    }
}
