<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AddressController;

/*
|--------------------------------------------------------------------------
| CSRF Cookie Route
|--------------------------------------------------------------------------
*/
Route::get('csrf-cookie', function () {
    try {
        return response()->json([
            'message' => 'CSRF cookie set', 
            'status' => 'success',
            'timestamp' => now()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error setting CSRF cookie',
            'error' => $e->getMessage(),
            'status' => 'error'
        ], 500);
    }
});

// Test route to check if API is working
Route::get('test', function () {
    return response()->json(['message' => 'Electronics Mart API is working', 'timestamp' => now()]);
});

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/
// Products and Categories (Public for browsing)
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::get('products/brands', [ProductController::class, 'getBrands']);
Route::get('products/energy-ratings', [ProductController::class, 'getEnergyRatings']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

/*
|--------------------------------------------------------------------------
| Authentication Routes (Public)
|--------------------------------------------------------------------------
*/
Route::post('check-auth-method', [AuthController::class, 'checkAuthMethod']);

// Customer Authentication Routes
Route::post('register', [AuthController::class, 'registerCustomer']);
Route::post('login', [AuthController::class, 'loginCustomer']);
Route::post('verify-email', [AuthController::class, 'verifyEmailCode']);
Route::post('resend-verification', [AuthController::class, 'resendVerificationCode']);
Route::post('password/send-reset-code', [AuthController::class, 'sendResetCode']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);

/*
|--------------------------------------------------------------------------
| Authenticated Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {
    // User Profile
    Route::get('/user', [AuthController::class, 'me']);
    Route::put('/user', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    
    // User Addresses
    Route::prefix('user')->group(function () {
        Route::apiResource('addresses', AddressController::class);
        Route::patch('addresses/{address}/default', [AddressController::class, 'setDefault']);
    });
    
    // Cart Routes (Authenticated Only)
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::put('/{item}', [CartController::class, 'update']);
        Route::delete('/{item}', [CartController::class, 'destroy']);
        Route::delete('/', [CartController::class, 'clear']);
    });
    
    // Order Routes (Authenticated Only)
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::post('/validate-checkout', [OrderController::class, 'validateCheckout']);
        Route::put('/{order}/payment-status', [OrderController::class, 'updatePaymentStatus']);
    });
});