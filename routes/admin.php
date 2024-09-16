<?php

use App\Http\Controllers\Admin\{
    AdminConfigurationController,
    BranchOfficeController,
    CategoryController,
    CategoryTypeController,
    ClientController,
    ClientObservationController,
    DashboardController,
    MenuController,
    MenuItemController,
    PermissionController,
    ProductController,
    PurchaseOrderController,
    RoleController,
    UserController
};

Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => config('admin.prefix'),
    'middleware' => ['auth'],
    'as' => 'admin.',
], function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Configuration
    Route::get('config', [AdminConfigurationController::class, 'index'])->name('config.index');
    Route::post('config/restore-backup', [AdminConfigurationController::class, 'restoreBackup'])->name('config.restoreBackup');

    Route::post('config', [AdminConfigurationController::class, 'store'])->name('config.store');
    Route::post('config/upload', [AdminConfigurationController::class, 'uploadConfig'])->name('config.upload');

    // User routes
    Route::get('edit-account-info', [UserController::class, 'accountInfo'])->name('account.info');
    Route::post('edit-account-info', [UserController::class, 'accountInfoStore'])->name('account.info.store');
    Route::post('change-password', [UserController::class, 'changePasswordStore'])->name('account.password.store');
    Route::resource('user', UserController::class);

    // Role and Permission routes
    Route::resource('role', RoleController::class);
    Route::resource('permission', PermissionController::class);

    // Menu and Menu Item routes
    Route::resource('menu', MenuController::class)->except(['show']);
    Route::resource('menu.item', MenuItemController::class);

    // Category and Category Type routes
    Route::group([
        'prefix' => 'category',
        'as' => 'category.',
    ], function () {
        Route::resource('type', CategoryTypeController::class)->except(['show']);
        Route::resource('type.item', CategoryController::class);
    });

    // Client routes
    Route::resource('client', ClientController::class);
    Route::get('client/{client}/observations', [ClientObservationController::class, 'create'])->name('client.observation.create');
    Route::post('client/{client}/observations', [ClientObservationController::class, 'store'])->name('clients.observations.store');
    Route::post('client/import', [ClientController::class, 'import'])->name('client.import');

    // Branch Office routes
    Route::group([
        'prefix' => 'client/{clientId}/branch_offices',
        'as' => 'branch_offices.',
    ], function () {
        Route::get('/', [BranchOfficeController::class, 'index'])->name('index');
        Route::get('create', [BranchOfficeController::class, 'create'])->name('create');
        Route::post('/', [BranchOfficeController::class, 'store'])->name('store');
        Route::get('{id}/edit', [BranchOfficeController::class, 'edit'])->name('edit');
        Route::put('{id}', [BranchOfficeController::class, 'update'])->name('update');
        Route::delete('{id}', [BranchOfficeController::class, 'destroy'])->name('destroy');
    });

    // Product routes
    Route::resource('product', ProductController::class);
    Route::get('products/export', [ProductController::class, 'exportExcel'])->name('product_export');
    Route::get('ajax/products', [ProductController::class, 'ajaxProducts'])->name('admin.ajax.products');

    // Purchase Order routes
    Route::resource('purchase_orders', PurchaseOrderController::class);
    Route::post('purchase_orders/store', [PurchaseOrderController::class, 'store'])->name('purchase_orders.store');
    Route::post('purchase_orders/{id}/update', [PurchaseOrderController::class, 'update'])->name('purchase_orders.update');
    Route::put('purchase_orders/{id}/update-status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase_orders.updateStatus');
    Route::get('purchase_orders/getClientBranchOffices/{clientId}', [PurchaseOrderController::class, 'getClientBranchOffices']);
    Route::get('purchase_orders/getClientProducts/{clientId}', [PurchaseOrderController::class, 'getClientProducts'])->name('purchase_orders.getClientProducts');
    Route::get('purchase-order/{id}/pdf', [PurchaseOrderController::class, 'showPdf'])->name('purchase-order.pdf');
    Route::delete('purchase-orders/{id}/attachment', [PurchaseOrderController::class, 'deleteAttachment'])->name('purchase-orders.delete-attachment');

    // Export routes
    Route::get('clients/export', [ClientController::class, 'exportExcel'])->name('admin.clients.export');
    Route::get('branch-offices/export', [BranchOfficeController::class, 'exportExcel'])->name('admin.branch-offices.export');

    Route::post('purchase_orders/addObservation', [PurchaseOrderController::class, 'addObservation'])->name('purchase_orders.addObservation');

});
