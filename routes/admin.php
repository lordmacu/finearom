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

    // Dashboard (Sin permisos adicionales)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Configuration (Sin permisos adicionales)
    Route::get('config', [AdminConfigurationController::class, 'index'])->name('config.index');
    Route::post('config/restore-backup', [AdminConfigurationController::class, 'restoreBackup'])->name('config.restoreBackup');
    Route::post('config', [AdminConfigurationController::class, 'store'])->name('config.store');
    Route::post('config/upload', [AdminConfigurationController::class, 'uploadConfig'])->name('config.upload');
    Route::post('config/createBackup', [AdminConfigurationController::class, 'createBackup'])->name('config.createBackup');

    // User routes (Sin permisos adicionales)
    Route::get('edit-account-info', [UserController::class, 'accountInfo'])->name('account.info');
    Route::post('edit-account-info', [UserController::class, 'accountInfoStore'])->name('account.info.store');
    Route::post('change-password', [UserController::class, 'changePasswordStore'])->name('account.password.store');
    Route::resource('user', UserController::class);

    // Role and Permission routes (Sin permisos adicionales)
    Route::resource('role', RoleController::class);
    Route::resource('permission', PermissionController::class);

    // Menu and Menu Item routes (Sin permisos adicionales)
    Route::resource('menu', MenuController::class)->except(['show']);
    Route::resource('menu.item', MenuItemController::class);

    // Category and Category Type routes (Sin permisos adicionales)
    Route::group([
        'prefix' => 'category',
        'as' => 'category.',
    ], function () {
        Route::resource('type', CategoryTypeController::class)->except(['show']);
        Route::resource('type.item', CategoryController::class);
    });

    // Client routes (Con permisos)
    Route::group(['middleware' => ['permission:client list']], function () {
        Route::resource('client', ClientController::class);
    });
    Route::group(['middleware' => ['permission:client create']], function () {
        Route::post('client/store', [ClientController::class, 'store'])->name('client.store');
    });
    Route::group(['middleware' => ['permission:client edit']], function () {
        Route::put('client/{id}', [ClientController::class, 'update'])->name('client.update');
    });
    Route::group(['middleware' => ['permission:client delete']], function () {
        Route::delete('client/{id}', [ClientController::class, 'destroy'])->name('client.destroy');
    });

    Route::get('client/{client}/observations', [ClientObservationController::class, 'create'])->name('client.observation.create');
    Route::post('client/{client}/observations', [ClientObservationController::class, 'store'])->name('clients.observations.store');
    Route::post('client/import', [ClientController::class, 'import'])->name('client.import');

    // Branch Office routes (Con permisos)
    Route::group(['middleware' => ['permission:branch_office_view']], function () {
        Route::get('client/{clientId}/branch_offices', [BranchOfficeController::class, 'index'])->name('branch_offices.index');
    });
    Route::group(['middleware' => ['permission:branch_office_create']], function () {
        Route::get('client/{clientId}/branch_offices/create', [BranchOfficeController::class, 'create'])->name('branch_offices.create');
        Route::post('client/{clientId}/branch_offices', [BranchOfficeController::class, 'store'])->name('branch_offices.store');
    });
    Route::group(['middleware' => ['permission:branch_office_edit']], function () {
        Route::get('client/{clientId}/branch_offices/{id}/edit', [BranchOfficeController::class, 'edit'])->name('branch_offices.edit');
        Route::put('client/{clientId}/branch_offices/{id}', [BranchOfficeController::class, 'update'])->name('branch_offices.update');
    });
    Route::group(['middleware' => ['permission:branch_office_delete']], function () {
        Route::delete('client/{clientId}/branch_offices/{id}', [BranchOfficeController::class, 'destroy'])->name('branch_offices.destroy');
    });

    // Product routes (Con permisos)
    Route::group(['middleware' => ['permission:product list']], function () {
        Route::resource('product', ProductController::class);
    });
    Route::group(['middleware' => ['permission:product create']], function () {
        Route::post('product/store', [ProductController::class, 'store'])->name('product.store');
    });
    Route::group(['middleware' => ['permission:product edit']], function () {
        Route::put('product/{id}', [ProductController::class, 'update'])->name('product.update');
    });
    Route::group(['middleware' => ['permission:product delete']], function () {
        Route::delete('product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    });

    Route::get('products/export', [ProductController::class, 'exportExcel'])->name('product_export');
    Route::get('ajax/products', [ProductController::class, 'ajaxProducts'])->name('admin.ajax.products');

    // Purchase Order routes (Con permisos)
    Route::group(['middleware' => ['permission:purchase_order list']], function () {
        Route::resource('purchase_orders', PurchaseOrderController::class);
    });
    Route::group(['middleware' => ['permission:purchase_order create']], function () {
        Route::post('purchase_orders/store', [PurchaseOrderController::class, 'store'])->name('purchase_orders.store');
        Route::post('purchase_orders/import', [PurchaseOrderController::class, 'import'])->name('purchase_orders.import');
    });
    Route::group(['middleware' => ['permission:purchase_order edit']], function () {
        Route::post('purchase_orders/{id}/update', [PurchaseOrderController::class, 'update'])->name('purchase_orders.update');
    });

    Route::group(['middleware' => ['permission:order_change_status']], function () {
        Route::put('purchase_orders/{id}/update-status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase_orders.updateStatus');
    });
    	

 
    Route::group(['middleware' => ['permission:purchase_order delete']], function () {
        Route::delete('purchase-orders/{id}/attachment', [PurchaseOrderController::class, 'deleteAttachment'])->name('purchase-orders.delete-attachment');
    });

    Route::get('purchase_orders/getClientBranchOffices/{clientId}', [PurchaseOrderController::class, 'getClientBranchOffices']);
    Route::get('purchase_orders/getClientProducts/{clientId}', [PurchaseOrderController::class, 'getClientProducts'])->name('purchase_orders.getClientProducts');
    Route::get('purchase-order/{id}/pdf', [PurchaseOrderController::class, 'showPdf'])->name('purchase-order.pdf');

    // Export routes (Sin permisos adicionales)
    Route::get('clients/export', [ClientController::class, 'exportExcel'])->name('admin.clients.export');
    Route::get('branch-offices/export', [BranchOfficeController::class, 'exportExcel'])->name('admin.branch-offices.export');

    Route::post('purchase_orders/addObservation', [PurchaseOrderController::class, 'addObservation'])->name('purchase_orders.addObservation');

});
