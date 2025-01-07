<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;

Route::get('/monthly-revenue', [SalesController::class, 'monthlyRevenue']);
Route::get('/most-sold-products', [SalesController::class, 'mostSoldProducts'])->name('admin.reports.most-sold-products');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
