<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAuthController;

// CSRF cookie route for API calls
Route::get('/sanctum/csrf-cookie', function () {
    try {
        // Start session if not already started
        if (!session()->isStarted()) {
            session()->start();
        }
        
        return response()->json([
            'message' => 'CSRF cookie set', 
            'status' => 'success',
            'csrf_token' => csrf_token(),
            'timestamp' => now()
        ])->header('Access-Control-Allow-Origin', '*')
          ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
          ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, X-XSRF-TOKEN')
          ->header('Access-Control-Allow-Credentials', 'true');
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error setting CSRF cookie',
            'error' => $e->getMessage(),
            'status' => 'error'
        ], 500)->header('Access-Control-Allow-Origin', '*')
          ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
          ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, X-XSRF-TOKEN')
          ->header('Access-Control-Allow-Credentials', 'true');
    }
})->middleware(['web']);

// Health check route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'message' => 'API is healthy'
    ])->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
      ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, X-XSRF-TOKEN')
      ->header('Access-Control-Allow-Credentials', 'true');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Authentication Routes
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::get('forgot-password', [AdminAuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::match(['GET', 'POST'], 'logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        // Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');
        
        // Products Management
        Route::resource('products', \App\Http\Controllers\Admin\AdminProductController::class);
        
        // Categories Management
        Route::resource('categories', \App\Http\Controllers\Admin\AdminCategoryController::class);
        
        // Orders Management
        Route::resource('orders', \App\Http\Controllers\Admin\AdminOrderController::class);
        Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        
        // Settings
        Route::get('settings', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'index'])->name('settings.index');
        Route::put('settings/profile', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'updateProfile'])->name('settings.update-profile');
        Route::put('settings/password', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'updatePassword'])->name('settings.update-password');
        Route::put('settings/system', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'updateSystemSettings'])->name('settings.update-system');
    });
});

// Serve storage files first (before catch-all route)
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    
    if (!file_exists($filePath)) {
        abort(404);
    }
    
    return response()->file($filePath);
})->where('path', '.*');

// Serve sample images for development/testing
Route::get('/sample_images/{filename}', function ($filename) {
    $path = storage_path('app/public/sample_images/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
})->where('filename', '.*');

// Serve real product images
Route::get('/images/{filename}', function ($filename) {
    $path = storage_path('app/public/images/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
})->where('filename', '.*');

// Customer-facing routes (redirect to admin login)
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Catch-all for any other routes (404)
Route::fallback(function () {
    // If it's an API request, return JSON 404
    if (request()->is('api/*') || request()->expectsJson()) {
        return response()->json([
            'message' => 'Endpoint not found.',
            'status' => 'error',
            'code' => 'ENDPOINT_NOT_FOUND'
        ], 404);
    }
    
    // For web requests, show 404 page
    return response()->view('errors.404', [], 404);
});