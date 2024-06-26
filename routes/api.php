<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1/')->as('api.v1.')->group(function () {

    // Authentication
    Route::controller(App\Http\Controllers\Api\V1\AuthController::class)->group(function () {
        Route::post('register', 'register')->name('register');
        Route::post('login', 'login')->name('login');
    });

    Route::middleware('auth:api')->prefix('admin/')->as('admin.')->group(function () {
        // Route::resource('user', App\Http\Controllers\Api\V1\Admin\UserController::class);

        Route::post('product/import', [App\Http\Controllers\Api\V1\Admin\ProductController::class, 'import']);
        Route::get('product/download-import-template', [App\Http\Controllers\Api\V1\Admin\ProductController::class, 'downloadImportTemplate']);
        Route::resource('product', App\Http\Controllers\Api\V1\Admin\ProductController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

        Route::post('supplier/import', [App\Http\Controllers\Api\V1\Admin\SupplierController::class, 'import']);
        Route::get('supplier/download-import-template', [App\Http\Controllers\Api\V1\Admin\SupplierController::class, 'downloadImportTemplate']);
        Route::resource('supplier', App\Http\Controllers\Api\V1\Admin\SupplierController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

        Route::get('transaction/export', [App\Http\Controllers\Api\V1\Admin\TransactionController::class, 'export']);
        Route::resource('transaction', App\Http\Controllers\Api\V1\Admin\TransactionController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    });
});
