<?php
// backend/routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreSettingController;

// ── Public ────────────────────────────────────────────────────
Route::post('/auth/login', [AuthController::class, 'login']);

// ── Auth required ─────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);
    Route::get('/store-settings', [StoreSettingController::class, 'show']);

    // Products - everyone can view/search
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products',        [ProductController::class, 'index']);
    Route::get('/products/{id}',   [ProductController::class, 'show']);

    // Categories - everyone can view (cashier needs it for billing)
    Route::get('/categories', [CategoryController::class, 'index']);

    // Bills - cashier & admin
    Route::post('/bills', [BillController::class, 'store']);

    // ── Admin only ────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {

        // One-time first-run store profile setup
        Route::post('/store-settings', [StoreSettingController::class, 'store']);

        // Products CRUD
        Route::post('/products',        [ProductController::class, 'store']);
        Route::put('/products/{id}',    [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Categories CRUD
        Route::post('/categories',        [CategoryController::class, 'store']);
        Route::put('/categories/{id}',    [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // Stock
        Route::get('/stock',                     [StockController::class, 'index']);
        Route::get('/stock/low',                 [StockController::class, 'lowStock']);
        Route::post('/stock/{productId}',        [StockController::class, 'update']);
        Route::get('/stock/{productId}/history', [StockController::class, 'history']);

        // Reports
        Route::get('/reports/sales',        [ReportController::class, 'sales']);
        Route::get('/reports/inventory',    [ReportController::class, 'inventory']);
        Route::get('/reports/top-products', [ReportController::class, 'topProducts']);
        Route::get('/reports/daily',        [ReportController::class, 'daily']);

        // Users
        Route::get('/users',         [UserController::class, 'index']);
        Route::post('/users',        [UserController::class, 'store']);
        Route::put('/users/{id}',    [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Bills admin
        Route::get('/bills',           [BillController::class, 'index']);
        Route::get('/bills/{id}',      [BillController::class, 'show']);
        Route::put('/bills/{id}/void', [BillController::class, 'void']);
    });
});