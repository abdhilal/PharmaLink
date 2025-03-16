<?php
use App\Http\Controllers\CartController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\WarehouseMedicineController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

//واجهة الحسابات والمدفوعات
Route::get('/payments', App\Http\Controllers\PaymentController::class)->name('payments.index');
//عرض المستودعات حسب المدينة
Route::get('/warehouses', [App\Http\Controllers\WarehouseController::class, 'index'])->name('warehouses.index');

Route::middleware('auth')->prefix('warehouse')->group(function () {
  // عرض الأدوية
  Route::get('/medicines', [WarehouseMedicineController::class, 'index'])->name('warehouse.medicines.index');
  // صفحة إضافة دواء
  Route::get('/medicines/create', [WarehouseMedicineController::class, 'create'])->name('warehouse.medicines.create');
  // تخزين الدواء
  Route::post('/medicines', [WarehouseMedicineController::class, 'store'])->name('warehouse.medicines.store');
});













































require __DIR__.'/auth.php';
