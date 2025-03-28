<?php

namespace App\Services;

use App\Models\User;
use App\Models\Medicine;
use App\Models\Offer;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Models\Order;
use Carbon\Carbon;

class NotificationService
{
    public function checkExpiringMedicines()
    {
        $expiringMedicines = Medicine::where('expiry_date', '<=', now()->addMonth())
            ->where('expiry_date', '>', now())
            ->get();

        foreach ($expiringMedicines as $medicine) {
            $daysUntilExpiry = now()->diffInDays($medicine->expiry_date);
            $this->createNotification(
                $medicine->warehouse->user,
                'expiry_date',
                'inventory',
                'دواء قريب من انتهاء الصلاحية',
                "الدواء {$medicine->name} سينتهي صلاحيته خلال {$daysUntilExpiry} يوم"
            );
        }
    }

    public function checkOffers()
    {
        $activeOffers = Offer::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        foreach ($activeOffers as $offer) {
            $this->createNotification(
                $offer->warehouse->user,
                'offer',
                'offers',
                'عرض جديد',
                "عرض جديد: {$offer->title} - خصم {$offer->discount_percentage}%"
            );
        }
    }

    public function checkSupplierOrders()
    {
        $pendingOrders = SupplyOrder::where('status', 'pending')->get();

        foreach ($pendingOrders as $order) {
            $this->createNotification(
                $order->warehouse->user,
                'supplier_order',
                'suppliers',
                'طلب جديد من المورد',
                "طلب جديد من المورد {$order->supplier->name}"
            );
        }
    }

    public function checkSupplierPayments()
    {
        $suppliers = Supplier::all();

        foreach ($suppliers as $supplier) {
            $totalDebt = $supplier->total_debt;
            if ($totalDebt > 1000) { // يمكن تعديل الحد الأدنى حسب الحاجة
                $this->createNotification(
                    $supplier->warehouse->user,
                    'supplier_payment',
                    'suppliers',
                    'دفعة مستحقة للمورد',
                    "دفعة مستحقة للمورد {$supplier->name} بقيمة {$totalDebt}"
                );
            }
        }
    }

    public function generateMonthlyReport()
    {
        $warehouses = \App\Models\Warehouse::all();

        foreach ($warehouses as $warehouse) {
            $monthlySales = $warehouse->orders()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_price');

            $this->createNotification(
                $warehouse->user,
                'monthly_report',
                'reports',
                'التقرير الشهري',
                "إجمالي المبيعات الشهرية: {$monthlySales}"
            );
        }
    }

    public function checkDelayedOrders()
    {
        $delayedOrders = Order::where('status', 'pending')
            ->where('created_at', '<=', now()->subHours(24))
            ->get();

        foreach ($delayedOrders as $order) {
            $hoursDelayed = now()->diffInHours($order->created_at);
            $this->createNotification(
                $order->warehouse->user,
                'order_delayed',
                'orders',
                'طلبية متأخرة',
                "الطلبية رقم {$order->id} متأخرة منذ {$hoursDelayed} ساعة"
            );
        }
    }

    public function checkOrderArrival($order)
    {
        $this->createNotification(
            $order->warehouse->user,
            'order_arrived',
            'orders',
            'وصول طلبية',
            "تم وصول الطلبية رقم {$order->id} من {$order->pharmacy->name}"
        );
    }

    public function notifyOrderSuspension($order)
    {
        $this->createNotification(
            $order->warehouse->user,
            'order_suspended',
            'orders',
            'تعليق طلبية',
            "تم تعليق الطلبية رقم {$order->id} من {$order->pharmacy->name}"
        );
    }

    public function createOrderNotification($order)
    {
        $this->createNotification(
            $order->warehouse->user,
            'new_order',
            'orders',
            'طلبية جديدة',
            "طلبية جديدة من {$order->pharmacy->name} بقيمة {$order->total_price}",
            [
                'order_id' => $order->id,
                'pharmacy_id' => $order->pharmacy_id,
                'total_price' => $order->total_price,
                'items' => $order->items->map(function($item) {
                    return [
                        'medicine_id' => $item->medicine_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price_per_unit
                    ];
                })
            ]
        );
    }

    public function checkLowStock()
    {
        $medicines = Medicine::with(['warehouse', 'company'])->get();

        foreach ($medicines as $medicine) {
            // التحقق من انخفاض المخزون
            if ($medicine->quantity <= $medicine->min_quantity) {
                $this->createLowStockNotification($medicine);
            }

            // التحقق من المخزون قبل النفاد بأسبوع
            if ($medicine->quantity <= ($medicine->min_quantity * 1.5)) { // 50% فوق الحد الأدنى
                $this->createPreLowStockNotification($medicine);
            }

            // إرسال إشعار للمورد إذا كان المخزون منخفض جداً
            if ($medicine->quantity <= ($medicine->min_quantity * 0.5)) { // 50% تحت الحد الأدنى
                $this->notifySupplier($medicine);
            }
        }
    }

    private function createLowStockNotification($medicine)
    {
        $this->createNotification(
            $medicine->warehouse->user,
            'low_stock',
            'inventory',
            'تنبيه: مخزون منخفض',
            "الدواء {$medicine->name} منخفض المخزون. الكمية الحالية: {$medicine->quantity}",
            [
                'medicine_id' => $medicine->id,
                'current_quantity' => $medicine->quantity,
                'min_quantity' => $medicine->min_quantity,
                'has_sound' => true // تفعيل التنبيه الصوتي
            ]
        );
    }

    private function createPreLowStockNotification($medicine)
    {
        $this->createNotification(
            $medicine->warehouse->user,
            'pre_low_stock',
            'inventory',
            'تنبيه: مخزون قريب من النفاد',
            "الدواء {$medicine->name} يقترب من الحد الأدنى للمخزون. الكمية الحالية: {$medicine->quantity}",
            [
                'medicine_id' => $medicine->id,
                'current_quantity' => $medicine->quantity,
                'min_quantity' => $medicine->min_quantity
            ]
        );
    }

    private function notifySupplier($medicine)
    {
        // البحث عن المورد المرتبط بالدواء
        $supplier = Supplier::where('company_id', $medicine->company_id)
            ->where('warehouse_id', $medicine->warehouse_id)
            ->first();

        if ($supplier) {
            $this->createNotification(
                $supplier->user,
                'reorder_stock',
                'inventory',
                'طلب إعادة توريد',
                "مطلوب إعادة توريد {$medicine->name}. الكمية الحالية: {$medicine->quantity}",
                [
                    'medicine_id' => $medicine->id,
                    'current_quantity' => $medicine->quantity,
                    'min_quantity' => $medicine->min_quantity,
                    'company_id' => $medicine->company_id
                ]
            );
        }
    }

    private function createNotification($user, $type, $category, $title, $message, $data = null)
    {
        // التحقق من إعدادات الإشعارات للمستخدم
        $settings = $user->notification_settings ?? [];
        if (isset($settings[$category]) && !$settings[$category]) {
            return; // تخطي إنشاء الإشعار إذا كان معطل
        }

        $user->notifications()->create([
            'type' => $type,
            'category' => $category,
            'title' => $title,
            'message' => $message,
            'data' => $data ? json_encode($data) : null,
            'read' => false,
        ]);
    }
} 