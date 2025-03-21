<?php
use App\Http\Controllers\CartController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierPaymentController;
use App\Http\Controllers\SupplyOrderController;

use App\Http\Controllers\WarehouseMedicineController;
use App\Http\Controllers\WarehouseController;
use App\Models\SupplierPayment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// راوتات السلة
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});
Route::post('/cart/add-multiple', [App\Http\Controllers\CartController::class, 'addMultiple'])->name('cart.addMultiple');
Route::post('/cart/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

Route::middleware('auth')->group(function () {
    Route::post('/payment/debt/{order}', [PaymentController::class, 'recordDebt'])->name('payment.debt');
    Route::post('/payment/make', [PaymentController::class, 'makePayment'])->name('payment.make');
});


Route::get('/', function () {
  return auth()->check() ? redirect()->route('medicines.index') : view('welcome');
})->name('home');

//واجه قائمة الادوية
Route::get('/medicines', [MedicineController::class, 'index'])->name('medicines.index')->middleware('auth');
//واجهة الطلبيات
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::patch('/orders/{order}', [OrderController::class, 'updateStatus'])->name('orders.update');


//عرض المستودعات حسب المدينة
Route::get('/warehouses', [App\Http\Controllers\WarehouseController::class, 'index'])->name('warehouses.index');

    Route::middleware('auth')->prefix('warehouse')->group(function () {
        // عرض قائمة الأدوية
        Route::get('/medicines', [WarehouseMedicineController::class, 'index'])->name('warehouse.medicines.index');

        // عرض صفحة إضافة دواء
        Route::get('/medicines/create', [WarehouseMedicineController::class, 'create'])->name('warehouse.medicines.create');

        // حفظ دواء جديد
        Route::post('/medicines', [WarehouseMedicineController::class, 'store'])->name('warehouse.medicines.store');

        // عرض صفحة تعديل دواء
        Route::get('/medicines/{medicine}/edit', [WarehouseMedicineController::class, 'edit'])->name('warehouse.medicines.edit');

        // تحديث بيانات الدواء
        Route::patch('/medicines/{medicine}', [WarehouseMedicineController::class, 'update'])->name('warehouse.medicines.update');

        // حذف الدواء
        Route::delete('/medicines/{medicine}', [WarehouseMedicineController::class, 'destroy'])->name('warehouse.medicines.destroy');
    });




Route::middleware('auth')->prefix('warehouse')->group(function () {
    Route::get('/dashboard', [WarehouseController::class, 'dashboard'])->name('warehouse.dashboard');
    Route::get('/medicines', [WarehouseMedicineController::class, 'index'])->name('warehouse.medicines.index');
    Route::get('/medicines/create', [WarehouseMedicineController::class, 'create'])->name('warehouse.medicines.create');
    Route::get('/payments', [PaymentController::class, 'index'])->name('warehouse.payments');
    Route::post('/payments/make', [PaymentController::class, 'makePayment'])->name('warehouse.payments.make');
    Route::get('/pharmacies', [PharmacyController::class, 'index'])->name('pharmacies.index');
    Route::get('/pharmacies/{pharmacy}', [PharmacyController::class, 'show'])->name('pharmacies.show');

    Route::get('/settings/account', [SettingsController::class, 'account'])->name('warehouse.settings.account');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('warehouse.notifications');
    Route::get('/reports', [ReportController::class, 'index'])->name('warehouse.reports');
    Route::get('/settings/cities', [SettingsController::class, 'cities'])->name('warehouse.settings.cities');
    Route::patch('/settings/cities', [SettingsController::class, 'updateCities'])->name('warehouse.settings.updateCities');
    //الموردين
Route::get('/suppliers', [SupplierController::class, 'index'])->name('warehouse.suppliers.index');
Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('warehouse.suppliers.create');
Route::get('/suppliers/{id}', [SupplierController::class, 'show'])->name('warehouse.suppliers.show');
Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('warehouse.suppliers.edit');
Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('warehouse.suppliers.update');
Route::post('/suppliers', [SupplierController::class, 'store'])->name('warehouse.suppliers.store');
Route::post('/suppliers/{supplier}/payments', [SupplierPaymentController::class, 'store'])->name('warehouse.supplier_payments.store');
Route::get('/suppliers/{supplier}/order', [SupplyOrderController::class, 'show'])->name('warehouse.supplier_order.show');


});


Route::middleware('auth')->prefix('warehouse')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/approve', [OrderController::class, 'approve'])->name('orders.approve');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::patch('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});


// للصيدلي
Route::prefix('pharmacy')->group(function () {
    Route::get('/balance', [PaymentController::class, 'pharmacyBalance'])->name('pharmacy.balance');
});




































require __DIR__.'/auth.php';
