<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryPrediction extends Model
{
    protected $fillable = [
        'medicine_id',
        'predicted_demand',
        'recommended_stock',
        'prediction_date'
    ];

    protected $dates = [
        'prediction_date'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public static function generatePrediction($medicine_id)
    {
        // Simple prediction based on last 30 days sales
        $medicine = Medicine::find($medicine_id);
        $thirtyDaysAgo = now()->subDays(30);
        
        $totalSold = OrderItem::where('medicine_id', $medicine_id)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('quantity');
        
        $averageDailySales = $totalSold / 30;
        $predictedDemand = ceil($averageDailySales * 30); // Predicted monthly demand
        $recommendedStock = ceil($predictedDemand * 1.2); // 20% buffer
        
        return self::create([
            'medicine_id' => $medicine_id,
            'predicted_demand' => $predictedDemand,
            'recommended_stock' => $recommendedStock,
            'prediction_date' => now()
        ]);
    }
}
