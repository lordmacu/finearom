<?php

Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => config('admin.prefix'),
    'middleware' => ['auth', 'verified'],
    'as' => 'admin.',
], function () {

    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::post('/config/upload', 'AdminConfigurationController@uploadConfig')->name('config.upload');


    Route::resource('user', 'UserController');
    Route::resource('role', 'RoleController');
    Route::resource('permission', 'PermissionController');
    Route::resource('menu', 'MenuController')->except([
        'show',
    ]);
    Route::resource('menu.item', 'MenuItemController');
    Route::group([
        'prefix' => 'category',
        'as' => 'category.',
    ], function () {
        Route::resource('type', 'CategoryTypeController')->except([
            'show',
        ]);
        Route::resource('type.item', 'CategoryController');
    });
    Route::get('edit-account-info', 'UserController@accountInfo')->name('account.info');
    Route::post('edit-account-info', 'UserController@accountInfoStore')->name('account.info.store');
    Route::post('change-password', 'UserController@changePasswordStore')->name('account.password.store');

    Route::resource('client', App\Http\Controllers\Admin\ClientController::class);
    Route::resource('product', App\Http\Controllers\Admin\ProductController::class);
    Route::get('client/{client}/observations', 'ClientObservationController@create')->name('client.observation.create');
    Route::post('client/{client}/observations', 'ClientObservationController@store')->name('clients.observations.store');
    Route::post('client/import', [ClientController::class, 'import'])->name('client.import');

    Route::get('purchase_orders/getClientBranchOffices/{clientId}', [App\Http\Controllers\Admin\PurchaseOrderController::class, 'getClientBranchOffices']);

    Route::resource('purchase_orders', PurchaseOrderController::class);
    Route::post('purchase_orders/store', 'PurchaseOrderController@store')->name('purchase_orders.store');
    Route::post('purchase_orders/{id}/update', 'PurchaseOrderController@update')->name('purchase_orders.update');

    Route::put('purchase_orders/{id}/update-status','PurchaseOrderController@updateStatus')->name('purchase_orders.updateStatus');

    Route::get('purchase_orders/getClientProducts/{clientId}', 'PurchaseOrderController@getClientProducts')->name('purchase_orders.getClientProducts');
    Route::get('purchase-order/{id}/pdf','PurchaseOrderController@showPdf')->name('purchase-order.pdf');
    Route::delete('purchase-orders/{id}/attachment','PurchaseOrderController@deleteAttachment')
    ->name('purchase-orders.delete-attachment');

    Route::get('client/{clientId}/branch_offices', [App\Http\Controllers\Admin\BranchOfficeController::class, 'index'])->name('branch_offices.index');
    Route::get('client/{clientId}/branch_offices/create', [App\Http\Controllers\Admin\BranchOfficeController::class, 'create'])->name('branch_offices.create');
    Route::post('client/{clientId}/branch_offices', [App\Http\Controllers\Admin\BranchOfficeController::class, 'store'])->name('branch_offices.store');
    Route::get('client/{clientId}/branch_offices/{id}/edit', [App\Http\Controllers\Admin\BranchOfficeController::class, 'edit'])->name('branch_offices.edit');
    Route::put('client/{clientId}/branch_offices/{id}', [App\Http\Controllers\Admin\BranchOfficeController::class, 'update'])->name('branch_offices.update');
    Route::delete('client/{clientId}/branch_offices/{id}', [App\Http\Controllers\Admin\BranchOfficeController::class, 'destroy'])->name('branch_offices.destroy');

    Route::get('products/export', [App\Http\Controllers\Admin\ProductController::class, 'exportExcel'])->name('product_export');
    Route::get('clients/export', [App\Http\Controllers\Admin\ClientController::class, 'exportExcel'])->name('admin.clients.export');
    Route::get('branch-offices/export', [App\Http\Controllers\Admin\BranchOfficeController::class, 'exportExcel'])->name('admin.branch-offices.export');

    Route::get('config', [App\Http\Controllers\Admin\AdminConfigurationController::class, 'index'])->name('config.index');
    Route::post('config', [App\Http\Controllers\Admin\AdminConfigurationController::class, 'store'])->name('config.store');
});
