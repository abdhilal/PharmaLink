<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class CheckNotifications extends Command
{
    protected $signature = 'notifications:check';
    protected $description = 'فحص وإرسال الإشعارات';

    public function handle(NotificationService $notificationService)
    {
        $this->info('بدء فحص الإشعارات...');

        // فحص المخزون المنخفض
        $notificationService->checkLowStock();
        $this->info('تم فحص المخزون المنخفض');

        // فحص الأدوية القريبة من انتهاء الصلاحية
        $notificationService->checkExpiringMedicines();
        $this->info('تم فحص الأدوية القريبة من انتهاء الصلاحية');

        // فحص العروض النشطة
        $notificationService->checkOffers();
        $this->info('تم فحص العروض النشطة');

        // فحص طلبات الموردين
        $notificationService->checkSupplierOrders();
        $this->info('تم فحص طلبات الموردين');

        // فحص الطلبيات المتأخرة
        $notificationService->checkDelayedOrders();
        $this->info('تم فحص الطلبيات المتأخرة');

        // فحص دفعات الموردين
        $notificationService->checkSupplierPayments();
        $this->info('تم فحص دفعات الموردين');

        // إنشاء التقارير الشهرية
        if (now()->day === 1) { // تشغيل فقط في أول يوم من الشهر
            $notificationService->generateMonthlyReport();
            $this->info('تم إنشاء التقارير الشهرية');
        }

        $this->info('تم إكمال فحص الإشعارات بنجاح');
    }
} 