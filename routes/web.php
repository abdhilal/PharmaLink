<?php

use App\Events\NotificationEvent;
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
use App\Http\Controllers\CashController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PharmacyOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Staff\OrderController as StaffOrderController;
use App\Http\Controllers\UrgentOrderController;
use App\Http\Controllers\WarehouseMedicineController;
use App\Http\Controllers\WarehouseController;
use App\Models\SupplierPayment;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/', function () {
    return  view('welcome');
})->name('home');



Route::middleware(['warehouse'])->prefix('warehouse')->group(function () {

    //داش بورد
    Route::get('/dashboard', [WarehouseController::class, 'dashboard'])->name('warehouse.dashboard');
    //عرض ادوية المستودع والتحكم بها
    Route::get('/medicines', [WarehouseMedicineController::class, 'index'])->name('warehouse.medicines.index');
    Route::delete('/medicines/{medicine}', [WarehouseMedicineController::class, 'destroy'])->name('warehouse.medicines.destroy');
    Route::patch('/medicines/{medicine}', [WarehouseMedicineController::class, 'update'])->name('warehouse.medicines.update');
    Route::get('/medicines/{medicine}/edit', [WarehouseMedicineController::class, 'edit'])->name('warehouse.medicines.edit');
    Route::post('/medicines', [WarehouseMedicineController::class, 'store'])->name('warehouse.medicines.store');
    Route::get('/medicines/create', [WarehouseMedicineController::class, 'create'])->name('warehouse.medicines.create');

    // بروشور المستودع والتجكم به
    Route::get('medicines/brochure', [WarehouseMedicineController::class, 'brochure'])->name('warehouse.medicines.brochure');
    Route::post('medicine/is_hidden/{medicineId}', [WarehouseMedicineController::class, 'is_hidden'])->name('warehouse.medicines.is_hidden');

    //العرورض على البروشور
    Route::post('medicine/offer/', [WarehouseMedicineController::class, 'offer'])->name('warehouse.medicines.offer');

    //اعدادات الحساب بالمستودع
    Route::get('/settings/account', [ProfileController::class, 'edit'])->name('warehouse.settings.account');

    //الاشعارات بس لسع مو شغالة
    Route::get('/notifications', [NotificationController::class, 'warehouseIndex'])->name('warehouse.notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('warehouse.notifications.read');

    //اعدادات الموقع
    Route::get('/settings/cities', [SettingsController::class, 'cities'])->name('warehouse.settings.cities');
    Route::patch('/settings/cities', [SettingsController::class, 'updateCities'])->name('warehouse.settings.updateCities');
    Route::post('/location/store', [SettingsController::class, 'store'])->name('warehouse.location.store');

    //التقارير المالية
    Route::get('/financial-report', [ReportController::class, 'financialReport'])->name('warehouse.financial_report');

    //الموردين
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('warehouse.suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('warehouse.suppliers.create');
    Route::get('/suppliers/{id}', [SupplierController::class, 'show'])->name('warehouse.suppliers.show');
    Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('warehouse.suppliers.edit');
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('warehouse.suppliers.update');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('warehouse.suppliers.store');
    //تسجيل دفعه لمورد
    Route::post('/suppliers/{supplier}/payments', [SupplierPaymentController::class, 'store'])->name('warehouse.supplier_payments.store');

    //طلبيات الموردين
    Route::get('/supply-orders/{supplier}/order', [SupplyOrderController::class, 'show'])->name('warehouse.supplier_order.show');
    Route::get('/supply-orders/{supplyOrder}/edit', [SupplyOrderController::class, 'edit'])->name('warehouse.supply_order.edit');
    Route::delete('/supply-orders/{supplyOrder}/destroy', [SupplyOrderController::class, 'destroy'])->name('warehouse.supply_order.destroy');
    Route::post('/supply-orders/{supplyOrder}', [SupplyOrderController::class, 'update'])->name('warehouse.supply_order.update');



    //ادارة الطلبيات والتحكم بها كمان
    Route::get('/orders/create-manual', [OrderController::class, 'createManual'])->name('warehouse.orders.create_manual');
    Route::post('/orders/store-manual', [OrderController::class, 'storeManual'])->name('warehouse.orders.store_manual');

    Route::get('/orders', [OrderController::class, 'index'])->name('warehouse.orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('warehouse.orders.show');
    Route::post('/orders/{order}/approve', [OrderController::class, 'approve'])->name('warehouse.orders.approve');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('warehouse.orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('warehouse.orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('warehouse.orders.destroy');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('warehouse.orders.cancel');



    //طلبيات الخدمة العاجلة
    Route::get('/urgentorders', [UrgentOrderController::class, 'index'])->name('warehouse.urgentorder.index');
    Route::get('/urgentorders/{id}/pharmacy/{pharmacy}', [UrgentOrderController::class, 'show'])->name('warehouse.urgentorder.show');

    // اخذ او قبول الطلبية
    Route::post('/urgentorders/{id}/approve', [UrgentOrderController::class, 'approve'])->name('warehouse.urgentorder.approve');
    Route::post('/urgentorders/store-manual', [UrgentOrderController::class, 'storeManual'])->name('warehouse.urgentorder.store_manual');



    //والمصاريف
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('warehouse.expenses.index');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('warehouse.expenses.store');
    //الصندوق
    Route::get('/cash', [CashController::class, 'index'])->name('warehouse.cash.index');

    //الموظفين
    Route::get('/employees', [EmployeeController::class, 'index'])->name('warehouse.employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('warehouse.employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('warehouse.employees.store');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('warehouse.employees.show');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('warehouse.employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('warehouse.employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('warehouse.employees.destroy');
    //دفع راتب موظف
    Route::post('/employees/{employee}/pay', [EmployeeController::class, 'paySalary'])->name('warehouse.employees.pay');


    //عرض الصيدليات وتسحيل دفعات منهم
    Route::get('/pharmacies', [PharmacyController::class, 'index'])->name('warehouse.pharmacies.index');
    Route::get('/pharmacies/{pharmacy}', [PharmacyController::class, 'show'])->name('warehouse.pharmacies.show');
    Route::get('/payments', [PaymentController::class, 'index'])->name('warehouse.payments.index');
    Route::get('/payments/create/{pharmacyId}', [PaymentController::class, 'createPayment'])->name('warehouse.payments.create');
    Route::post('/payments/store/{pharmacyId}', [PaymentController::class, 'storePayment'])->name('warehouse.payments.store');
});





//طلبيات بيد المناديبب لتسليم الطلبيات لم يجهز بعد
Route::prefix('staff')->middleware(['auth'])->group(function () {

    Route::get('/orders', [StaffOrderController::class, 'index'])->name('staff.orders.index');
    Route::patch('/orders/{order}/deliver', [StaffOrderController::class, 'deliver'])->name('staff.orders.deliver');
});



//التحكم في موظفين المناديب

Route::prefix('warehouse')->middleware(['warehouse'])->group(function () {

    Route::get('/staff', [StaffController::class, 'index'])->name('warehouse.staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('warehouse.staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('warehouse.staff.store');
    Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('warehouse.staff.edit');
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('warehouse.staff.update');
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('warehouse.staff.destroy');
});







//طرف الصيدلي


Route::middleware(['pharmacy'])->prefix('pharmacy')->group(function () {

    //عرض المستودعات التي تخدم هذا الصيدلي حسب الموقع
    Route::get('/warehouses', [WarehouseController::class, 'index'])->name('pharmacy.warehouses.index');

    //عرض ادوية مستودع معين او الروشور
    Route::get('/warehouse/{warehouseId}', [WarehouseController::class, 'show'])->name('pharmacy.warehouses.show');
    //اضافة عناصر الى السلة والتحكم بها
    Route::post('/cart/add-multiple', [CartController::class, 'addMultiple'])->name('pharmacy.cart.addMultiple');
    Route::post('/cart/update', [CartController::class, 'update'])->name('pharmacy.cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('pharmacy.cart.remove');
    Route::get('/cart', [CartController::class, 'show'])->name('pharmacy.cart.show');

    //ارسال الطلبية الى المستودع
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('pharmacy.cart.checkout');

    // عرض جميع طلبيات الصيدلي
    Route::get('/orders', [PharmacyOrderController::class, 'index'])->name('pharmacy.orders.index');
    Route::get('/orders/{order}', [PharmacyOrderController::class, 'show'])->name('pharmacy.orders.show');
    Route::delete('/orders/{order}', [PharmacyOrderController::class, 'destroy'])->name('pharmacy.orders.destroy');
    //الدين للمستودعات
    Route::get('/balance', [PaymentController::class, 'pharmacyBalance'])->name('pharmacy.balance');

    //اعدادات الموقع
    Route::get('/settings/cities', [SettingsController::class, 'cities'])->name('pharmacy.settings.cities');
    Route::patch('/settings/cities', [SettingsController::class, 'updateCities'])->name('pharmacy.settings.updateCities');
    Route::post('/location/store', [SettingsController::class, 'store'])->name('pharmacy.location.store');

    //اعدادات الحساب بالصيدلية
    Route::get('/settings/account', [ProfileController::class, 'edit'])->name('pharmacy.settings.account');

    //الخدمة العاجلة
    Route::get('/UrgentOrder', [UrgentOrderController::class, 'create'])->name('pharmacy.urgentorder.create');
    Route::post('/UrgentOrder/store/', [UrgentOrderController::class, 'store'])->name('pharmacy.urgentorder.store');
});



Route::get('/notify',function(){
    return view('warehouse.notifications.index');
});


Route::get('/send',function(){
    $user_id=Auth::user()->id;
    event(new NotificationEvent('hello man', $user_id,'يا حلو يا مغروم '));
    return Auth::user();
});









require __DIR__ . '/auth.php';
