<?php
use App\Http\Controllers\PurchaseOrderAuthController;
use App\Http\Controllers\PurchaseOrderController;

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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

Route::get('purchase-order/email', [PurchaseOrderAuthController::class, 'showEmailForm'])->name('purchase_order.email');
Route::post('purchase-order/send-code', [PurchaseOrderAuthController::class, 'sendCode'])->name('purchase_order.send_code');
Route::get('purchase-order/verify', [PurchaseOrderAuthController::class, 'showVerificationForm'])->name('purchase_order.verify');
Route::post('purchase-order/verify-code', [PurchaseOrderAuthController::class, 'verifyCode'])->name('purchase_order.verify_code');

Route::get('purchase-order/dashboard', [PurchaseOrderController::class, 'showDashboard'])
->name('purchase_order.dashboard')
->middleware('check_permission:view purchase order dashboard');

Route::post('purchase-order/store', [PurchaseOrderController::class, 'storeClientPurchase'])
->name('purchase_order.storeClientPurchase')
->middleware('check_permission:create purchase order');

Route::get('purchase-orders/history', [PurchaseOrderController::class, 'showHistory'])
->name('purchase_order.history')
->middleware('check_permission:view purchase order history');

Route::get('purchase-orders/show', [PurchaseOrderController::class, 'show'])
->name('purchase_order.show')
->middleware('check_permission:view purchase order');

Route::get('purchase-orders/{id}/edit', [PurchaseOrderController::class, 'edit'])
->name('purchase_order.edit')
->middleware('check_permission:edit purchase order');

Route::delete('purchase-orders/destroy', [PurchaseOrderController::class, 'destroy'])
->name('purchase_order.destroy')
->middleware('check_permission:delete purchase order');

Route::put('purchase-orders/{id}/edit', [PurchaseOrderController::class, 'updateClientPurchase'])
->name('purchase_order.updateClientPurchase')
->middleware('check_permission:edit purchase order');

Route::get('purchase-orders/{id}', [PurchaseOrderController::class, 'show'])
->name('purchase_order.show')
->middleware('check_permission:view purchase order');

require __DIR__.'/auth.php';
