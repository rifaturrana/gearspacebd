<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Admin\OrderController as AdminOrderController;


Route::get('install', 'InstallController@installation')->name('install.show');
Route::post('install', 'InstallController@install')->name('install.do');

Route::get('license', 'LicenseController@create')->name('license.create');
Route::post('license', 'LicenseController@store')->name('license.store');

Route::post('admin/orders/remove-product', [\Modules\Order\Http\Controllers\Admin\OrderController::class, 'removeProduct'])
    ->name('admin.orders.remove-product');